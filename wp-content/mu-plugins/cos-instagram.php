<?php
/**
 * COS Instagram Feed
 * - Fetches the 6 most recent posts from @caviarofswitzerland daily via WP Cron
 * - Caches results in wp_options
 * - Serves cached posts via /wp-json/cos/v1/instagram-posts
 * - Admin-only /wp-json/cos/v1/refresh-instagram to force a refresh
 */
defined('ABSPATH') || exit;

const COS_IG_USER    = 'caviarofswitzerland';
const COS_IG_CACHE   = 'cos_instagram_posts';
const COS_IG_FETCHED = 'cos_instagram_last_fetch';
const COS_IG_COUNT   = 6;

function cos_is_allowed_instagram_image_url(string $url): bool {
    if ( ! wp_http_validate_url($url) ) return false;

    $parts = wp_parse_url($url);
    $host  = strtolower($parts['host'] ?? '');
    if ( ($parts['scheme'] ?? '') !== 'https' || ! $host ) return false;

    return str_ends_with($host, '.fbcdn.net')
        || str_ends_with($host, '.cdninstagram.com')
        || $host === 'fbcdn.net'
        || $host === 'cdninstagram.com';
}

/* ── Cron schedule ──────────────────────────────────────────── */
add_action('wp', function () {
    if ( ! wp_next_scheduled('cos_refresh_instagram') ) {
        wp_schedule_event(time(), 'daily', 'cos_refresh_instagram');
    }
});
add_action('cos_refresh_instagram', 'cos_fetch_instagram_posts');

/* ── Fetcher ────────────────────────────────────────────────── */
function cos_fetch_instagram_posts(): bool {
    // Try the public web-profile endpoint Instagram uses internally
    $endpoints = [
        'https://www.instagram.com/api/v1/users/web_profile_info/?username=' . COS_IG_USER,
        'https://i.instagram.com/api/v1/users/web_profile_info/?username='   . COS_IG_USER,
    ];

    $headers = [
        'User-Agent'      => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0 Safari/537.36',
        'Accept'          => 'application/json, text/plain, */*',
        'Accept-Language' => 'en-US,en;q=0.9',
        'X-IG-App-ID'     => '936619743392459',
        'X-Requested-With'=> 'XMLHttpRequest',
        'Referer'         => 'https://www.instagram.com/' . COS_IG_USER . '/',
        'Origin'          => 'https://www.instagram.com',
    ];

    $data = null;
    foreach ($endpoints as $url) {
        $resp = wp_remote_get($url, ['timeout' => 20, 'headers' => $headers]);
        if (is_wp_error($resp)) continue;
        $code = wp_remote_retrieve_response_code($resp);
        if ($code !== 200) continue;
        $json = json_decode(wp_remote_retrieve_body($resp), true);
        if ( ! empty($json['data']['user']['edge_owner_to_timeline_media']['edges']) ) {
            $data = $json;
            break;
        }
    }

    if ( ! $data ) return false;

    $edges = $data['data']['user']['edge_owner_to_timeline_media']['edges'];
    $posts = [];
    foreach ($edges as $edge) {
        $n       = $edge['node'];
        $shortcode = (string) ($n['shortcode'] ?? '');
        $thumb   = $n['thumbnail_src']  ?? $n['display_url'] ?? '';
        $caption = $n['edge_media_to_caption']['edges'][0]['node']['text'] ?? '';

        // Shortcodes become filenames and URL path components; reject anything
        // outside Instagram's expected alphabet rather than trying to clean it.
        if ( ! preg_match('/^[A-Za-z0-9_-]+$/', $shortcode) ) continue;
        if ( ! cos_is_allowed_instagram_image_url($thumb) ) $thumb = '';

        $posts[] = [
            'shortcode' => $shortcode,
            'url'       => 'https://www.instagram.com/p/' . rawurlencode($shortcode) . '/',
            'thumbnail' => $thumb,
            'caption'   => sanitize_text_field(mb_substr(wp_strip_all_tags($caption), 0, 120)),
            'is_video'  => ! empty($n['is_video']),
            'likes'     => absint($n['edge_liked_by']['count'] ?? 0),
        ];
        if (count($posts) >= COS_IG_COUNT) break;
    }

    if (empty($posts)) return false;

    // Download thumbnails locally so the browser can load them
    // (Instagram CDN blocks hotlinking from external origins)
    $upload_dir = wp_upload_dir();
    $ig_dir     = $upload_dir['basedir'] . '/instagram-cache';
    $ig_url     = $upload_dir['baseurl'] . '/instagram-cache';
    wp_mkdir_p($ig_dir);

    foreach ($posts as &$post) {
        $filename  = 'ig-' . $post['shortcode'] . '.jpg';
        $dest_file = $ig_dir . '/' . $filename;
        $dest_url  = $ig_url . '/' . $filename;

        // Only re-download if the file doesn't exist yet
        if ( ! file_exists($dest_file) && ! empty($post['thumbnail']) ) {
            $img = wp_remote_get($post['thumbnail'], [
                'timeout' => 20,
                'redirection' => 2,
                'reject_unsafe_urls' => true,
                'limit_response_size' => 5 * MB_IN_BYTES,
                'headers' => ['Referer' => 'https://www.instagram.com/'],
            ]);
            $content_type = is_wp_error($img) ? '' : strtolower((string) wp_remote_retrieve_header($img, 'content-type'));
            $allowed_type = in_array(strtok($content_type, ';'), ['image/jpeg', 'image/png', 'image/webp'], true);
            $body         = is_wp_error($img) ? '' : wp_remote_retrieve_body($img);
            if (
                ! is_wp_error($img) &&
                wp_remote_retrieve_response_code($img) === 200 &&
                $allowed_type &&
                $body !== '' &&
                strlen($body) <= 5 * MB_IN_BYTES
            ) {
                file_put_contents($dest_file, $body, LOCK_EX);
            }
        }

        if ( file_exists($dest_file) ) {
            $post['thumbnail'] = $dest_url;
        }
        // else keep the original CDN URL as fallback (will likely fail in browser, but better than nothing)
    }
    unset($post);

    update_option(COS_IG_CACHE,   $posts, false);
    update_option(COS_IG_FETCHED, time(), false);
    return true;
}

/* ── REST endpoints ─────────────────────────────────────────── */
add_action('rest_api_init', function () {

    // Public: serve cached posts to the page JS
    register_rest_route('cos/v1', '/instagram-posts', [
        'methods'             => 'GET',
        'callback'            => function () {
            $posts     = get_option(COS_IG_CACHE, []);
            $last      = (int) get_option(COS_IG_FETCHED, 0);
            $resp      = rest_ensure_response([
                'posts'      => $posts,
                'last_fetch' => $last ? date('c', $last) : null,
                'count'      => count($posts),
            ]);
            $resp->header('Cache-Control', 'public, max-age=3600');
            return $resp;
        },
        'permission_callback' => '__return_true',
    ]);

    // Admin-only: force a refresh
    register_rest_route('cos/v1', '/refresh-instagram', [
        'methods'             => 'POST',
        'callback'            => function () {
            $ok = cos_fetch_instagram_posts();
            return [
                'success' => $ok,
                'count'   => count(get_option(COS_IG_CACHE, [])),
                'message' => $ok ? 'Feed refreshed.' : 'Fetch failed — Instagram may be blocking the request.',
            ];
        },
        'permission_callback' => fn() => current_user_can('manage_options'),
    ]);
});
