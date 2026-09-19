<?php
/**
 * Baseline security hardening for Caviar of Switzerland.
 */

defined('ABSPATH') || exit;

if (defined('XMLRPC_REQUEST') && XMLRPC_REQUEST) {
    status_header(403);
    nocache_headers();
    exit('XML-RPC is disabled.');
}

// These headers do not interfere with WooCommerce payment iframes or popups.
add_action('send_headers', function (): void {
    if (headers_sent()) return;

    header_remove('X-Powered-By');
    header('X-Content-Type-Options: nosniff');
    header('X-Frame-Options: SAMEORIGIN');
    header('Referrer-Policy: strict-origin-when-cross-origin');
    header('Permissions-Policy: camera=(), microphone=(), geolocation=(), browsing-topics=()');
});

// XML-RPC is unused by this storefront. Remove both authenticated methods and
// pingback discovery to reduce brute-force and amplification attack surface.
add_filter('xmlrpc_enabled', '__return_false');
add_filter('wp_headers', function (array $headers): array {
    unset($headers['X-Pingback']);
    return $headers;
});
add_filter('xmlrpc_methods', function (array $methods): array {
    unset($methods['pingback.ping'], $methods['pingback.extensions.getPingbacks']);
    return $methods;
});
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wlwmanifest_link');

// Prevent public user enumeration while preserving administrator access.
add_filter('rest_pre_dispatch', function ($result, WP_REST_Server $server, WP_REST_Request $request) {
    if (
        preg_match('#^/wp/v2/users(?:/|$)#', $request->get_route()) &&
        ! current_user_can('list_users')
    ) {
        return new WP_Error(
            'rest_forbidden',
            'User records are not publicly available.',
            array('status' => 403)
        );
    }
    return $result;
}, 10, 3);

add_action('template_redirect', function (): void {
    if (is_author() && ! current_user_can('list_users')) {
        global $wp_query;
        $wp_query->set_404();
        status_header(404);
        nocache_headers();
    }
}, 0);

// Do not reveal whether a username or email address exists during login.
add_filter('login_errors', static fn (): string => 'Login unsuccessful.');

/**
 * Login-attempt throttling — security audit finding H3: wp-login.php had no
 * brute-force protection at all (6 consecutive wrong-password POSTs all
 * returned 200 with no delay/lockout). Keyed off a hash of the IP, never the
 * IP itself, same privacy pattern as the contact form's rate limiter
 * (cos-contact.php) and the CSP-report endpoint's rate limiter above.
 *
 * 5 failed attempts within a 10-minute window locks that IP out of
 * authentication entirely for 15 minutes — checked on the 'authenticate'
 * filter at an early priority, so a locked-out IP never even reaches the
 * password-verification code path.
 */
const COS_LOGIN_MAX_ATTEMPTS   = 5;
const COS_LOGIN_ATTEMPT_WINDOW = 10 * MINUTE_IN_SECONDS;
const COS_LOGIN_LOCKOUT        = 15 * MINUTE_IN_SECONDS;

function cos_login_throttle_key(): string {
    $ip = sanitize_text_field($_SERVER['REMOTE_ADDR'] ?? 'unknown');
    return 'cos_login_' . md5($ip);
}

/**
 * Hooked at priority 40 — deliberately *after* WordPress core's own
 * wp_authenticate_username_password()/wp_authenticate_email_password()
 * (both priority 20). Those don't check for an existing WP_Error before
 * running their own credential check when $username/$password are
 * non-empty, so a throttle hooked at an earlier priority would simply be
 * overwritten by core's real result — including a *successful* login, which
 * would defeat the entire point of a lockout. Running after them and always
 * overriding with our own WP_Error when locked out is the only way to
 * guarantee a throttled IP can't authenticate even with a correct password.
 */
add_filter('authenticate', function ($user, $username, $password) {
    if ('' === $username && '' === $password) {
        return $user; // initial GET of the login form, nothing to throttle yet
    }

    $key = cos_login_throttle_key();

    // Same three-factor local-only test-hook gate used everywhere else in
    // this project (cos_contact_test_hooks_enabled(), cos-contact.php) —
    // lets the automated suite reset its own lockout between test runs
    // without this ever being reachable on staging/production.
    if (
        function_exists('cos_contact_test_hooks_enabled') && cos_contact_test_hooks_enabled()
        && ! empty($_POST['cos_test_reset_login_throttle'])
    ) {
        delete_transient($key . '_locked');
        delete_transient($key . '_count');
    }

    if (get_transient($key . '_locked')) {
        return new WP_Error('cos_login_throttled', 'Too many failed login attempts. Please wait 15 minutes and try again.');
    }

    return $user;
}, 40, 3);

add_action('wp_login_failed', function (): void {
    $key   = cos_login_throttle_key();
    $count = (int) get_transient($key . '_count') + 1;

    if ($count >= COS_LOGIN_MAX_ATTEMPTS) {
        set_transient($key . '_locked', 1, COS_LOGIN_LOCKOUT);
        delete_transient($key . '_count');
        return;
    }

    set_transient($key . '_count', $count, COS_LOGIN_ATTEMPT_WINDOW);
});

// A successful login clears this IP's own attempt count — a legitimate user
// who mistyped a password a couple of times shouldn't stay half-throttled.
add_action('wp_login', function (): void {
    $key = cos_login_throttle_key();
    delete_transient($key . '_count');
    delete_transient($key . '_locked');
});

// Remove generator metadata; version patching remains enforced operationally.
remove_action('wp_head', 'wp_generator');
add_filter('the_generator', '__return_empty_string');

/**
 * A single random value per HTTP request/response, echoed onto every inline
 * <script>/<style> tag this theme's own PHP files control. Static-cached so
 * it's identical everywhere it's called within one request.
 *
 * Migration in progress, phase 1 of 2: this theme's own PHP-file inline
 * blocks now all carry a nonce attribute, but the CSP header below
 * deliberately does NOT add 'nonce-{value}' to script-src/style-src yet —
 * per spec, the moment a nonce-source appears in a directive, browsers stop
 * honouring 'unsafe-inline' for it at all (nonce-aware browsers only, but
 * that's everything current). The ~20 hand-authored pages' post_content
 * still has zero nonced inline blocks, so turning the nonce-source on now
 * would make every one of those un-nonced blocks register as a fresh
 * Report-Only violation on every single page load — confirmed 2026-08-17:
 * this exact change briefly shipped, flooded /wp-json/cos/v1/csp-report far
 * past its 30-reports/5-min rate limit within a handful of page loads, and
 * the browser's own failed report-POSTs (429s) then showed up as console
 * errors on pages that have nothing to do with CSP. Phase 2 — turning
 * 'nonce-{value}' on in the header — only happens once the page-content
 * nonce migration (tracked separately, OWNER-ACTIONS.md §19/§8) is done too.
 */
function cos_csp_nonce(): string {
    static $nonce = null;
    if (null === $nonce) {
        $nonce = bin2hex(random_bytes(16));
    }
    return $nonce;
}

/* ─── Content-Security-Policy: report-only, observation phase ──────
 * REPORT-ONLY — never blocks a single request. The theme relies heavily on
 * inline <script>/<style> throughout (product gallery, cart, checkout,
 * analytics bootstrap), so an *enforced* CSP without nonces would break
 * WooCommerce, Complianz, Klaviyo, and payment provider scripts outright.
 * This header only asks browsers to report what an eventual policy would
 * have blocked, so real violations can be observed and the policy — and
 * the inline-script migration this depends on — refined before ever
 * enforcing it. See OWNER-ACTIONS.md for the production rollout steps
 * (nonce migration, HSTS, and switching this to enforced).
 */
add_action('send_headers', function (): void {
    if (headers_sent() || is_admin()) return;

    $report_endpoint = rest_url('cos/v1/csp-report');
    $policy = implode('; ', [
        "default-src 'self'",
        // 'unsafe-inline' is required today (see cos_csp_nonce() docblock
        // above) — the long-term goal is nonce-based scripts sitewide, once
        // both halves of the migration (theme files done; page content not
        // yet) are complete.
        "script-src 'self' 'unsafe-inline' https://www.googletagmanager.com https://www.google-analytics.com https://js.stripe.com https://a.klaviyo.com https://static.klaviyo.com https://*.stripe.network",
        "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com",
        "img-src 'self' data: https:",
        "font-src 'self' data: https://fonts.gstatic.com",
        "connect-src 'self' https://www.google-analytics.com https://a.klaviyo.com https://static.klaviyo.com https://*.stripe.com https://*.wordpress.com",
        "frame-src 'self' https://js.stripe.com https://*.stripe.network https://www.googletagmanager.com",
        "object-src 'none'",
        "base-uri 'self'",
        "report-uri {$report_endpoint}",
    ]);

    header("Content-Security-Policy-Report-Only: {$policy}");
});

// Local, non-PII violation log: URI, blocked/violated directive, source
// file, line — never the full report body (which can include query strings
// off other pages the visitor was on, arguably still their browsing data).
//
// This is a public, unauthenticated, write-only endpoint by necessity (any
// visitor's browser needs to be able to POST to it), so it's hardened the
// same way as the contact form's public endpoint: bounded body/field sizes,
// per-IP rate limiting, and rejection of anything that doesn't look like a
// real browser CSP report — rather than trusting and storing whatever
// arrives.
const COS_CSP_REPORT_MAX_BODY_BYTES  = 8192; // real browser reports are a few hundred bytes
const COS_CSP_REPORT_MAX_FIELD_CHARS = 500;

/**
 * Strips query strings and fragments from a report URL before it's ever
 * stored — blocked-uri/document-uri/source-file can otherwise carry a
 * visitor's order key or other query-string data from whatever page they
 * were on. Non-URL values (e.g. CSP's own "inline", "eval") are just
 * length-capped, since there's nothing to parse.
 */
function cos_csp_report_clean_url(string $url): string {
    if ('' === $url) return '';
    $parts = wp_parse_url($url);
    if (empty($parts['host'])) {
        return substr($url, 0, COS_CSP_REPORT_MAX_FIELD_CHARS);
    }
    $clean = ($parts['scheme'] ?? 'https') . '://' . $parts['host'];
    if (!empty($parts['port'])) $clean .= ':' . $parts['port'];
    if (!empty($parts['path'])) $clean .= $parts['path'];
    return substr($clean, 0, COS_CSP_REPORT_MAX_FIELD_CHARS);
}

/**
 * Privacy-safe rate limit: keyed off a hash of the IP (never the IP itself,
 * and never persisted anywhere beyond this transient's own key), same
 * pattern as the contact form's rate limiter. A generous allowance, since a
 * single page load can legitimately fire several distinct violation
 * reports at once.
 */
function cos_csp_report_rate_limited(WP_REST_Request $request): bool {
    $ip  = sanitize_text_field($_SERVER['REMOTE_ADDR'] ?? 'unknown');
    $key = 'cos_csp_rl_' . md5($ip);

    // Same local-only, three-factor-gated test hook pattern as the contact
    // form's rate limiter (cos_contact_test_hooks_enabled(), defined in the
    // cos-contact.php mu-plugin, loaded before this file) — keeps automated
    // test runs isolated from each other without ever being reachable on
    // production or public staging. Read as a query param (not $_POST):
    // real browser CSP reports are a raw JSON body
    // (Content-Type: application/csp-report), which PHP never populates
    // $_POST from.
    if (
        function_exists('cos_contact_test_hooks_enabled') && cos_contact_test_hooks_enabled()
        && $request->get_param('cos_test_reset_rate_limit')
    ) {
        delete_transient($key);
    }

    $count = (int) get_transient($key);
    if ($count >= 30) {
        return true;
    }
    set_transient($key, $count + 1, 5 * MINUTE_IN_SECONDS);
    return false;
}

add_action('rest_api_init', function () {
    register_rest_route('cos/v1', '/csp-report', [
        'methods'             => 'POST',
        'callback'            => function (WP_REST_Request $request) {
            $raw = $request->get_body();

            // Body-size limit — reject oversized payloads before ever
            // touching json_decode().
            if (strlen($raw) > COS_CSP_REPORT_MAX_BODY_BYTES) {
                return new WP_REST_Response(null, 413);
            }

            if (cos_csp_report_rate_limited($request)) {
                return new WP_REST_Response(null, 429);
            }

            $body   = json_decode($raw, true);
            $report = is_array($body) ? ($body['csp-report'] ?? null) : null;

            // Reject anything that doesn't look like a real CSP violation
            // report rather than silently accepting/storing malformed input.
            $directive = $report['violated-directive'] ?? $report['effective-directive'] ?? '';
            if (!is_array($report) || '' === trim((string) $directive)) {
                return new WP_REST_Response(null, 400);
            }

            /**
             * Whether violation reports get written to the local
             * `cos_csp_reports` option at all. Defaults on (needed to
             * review violations before ever enforcing the policy — see
             * OWNER-ACTIONS.md), but can be filtered off — e.g. once an
             * external CSP reporting provider (report-uri.com, Sentry, etc.)
             * is wired up via the `report-uri`/`report-to` directive above
             * instead, local collection is no longer needed.
             */
            if (apply_filters('cos_csp_report_local_storage_enabled', true)) {
                $log   = get_option('cos_csp_reports', []);
                $log[] = [
                    'time'               => current_time('mysql'),
                    'blocked_uri'        => cos_csp_report_clean_url(sanitize_text_field($report['blocked-uri'] ?? '')),
                    'violated_directive' => substr(sanitize_text_field((string) $directive), 0, COS_CSP_REPORT_MAX_FIELD_CHARS),
                    'document_uri'       => cos_csp_report_clean_url(sanitize_text_field($report['document-uri'] ?? '')),
                    'source_file'        => cos_csp_report_clean_url(sanitize_text_field($report['source-file'] ?? '')),
                    'line_number'        => absint($report['line-number'] ?? 0),
                ];
                update_option('cos_csp_reports', array_slice($log, -200), false);
            }

            return new WP_REST_Response(null, 204);
        },
        'permission_callback' => '__return_true',
    ]);

    // Read-only visibility for the automated suite to verify reports are
    // actually stored, and stored *correctly* (query strings/fragments
    // stripped, fields length-capped) — without exposing anything publicly.
    // Gated behind the exact same three-factor local-only test-hook gate as
    // every other test hook in this project (cos_contact_test_hooks_enabled(),
    // defined in the cos-contact.php mu-plugin). Never reachable without
    // COS_ENABLE_TEST_HOOKS + 'local' environment + COS_TEST_MODE all being
    // true — i.e. never on staging/production regardless of request.
    register_rest_route('cos/v1', '/csp-report-test-inspect', [
        'methods'             => 'GET',
        'callback'            => function () {
            if (!function_exists('cos_contact_test_hooks_enabled') || !cos_contact_test_hooks_enabled()) {
                return new WP_REST_Response(['message' => 'Not available.'], 403);
            }
            return new WP_REST_Response(get_option('cos_csp_reports', []), 200);
        },
        'permission_callback' => '__return_true',
    ]);
});
