<?php
/**
 * COS Contact Form AJAX handler.
 *
 * Uses a WordPress nonce, honeypot, and simple rate limiting by default.
 * If COS_RECAPTCHA_SECRET or the cos_recaptcha_secret option is configured,
 * submitted reCAPTCHA tokens are verified as an additional check.
 *
 * PII handling: by default, no submission content (name/email/message) is
 * stored locally — only a non-identifying operational log (correlation id,
 * timestamp, success/failure) is kept, so a failed send can be traced without
 * retaining what the visitor wrote. Set COS_CONTACT_STORE_PII to true (or
 * filter 'cos_contact_store_pii') to opt into storing full submissions,
 * subject to the age-based purge below.
 */

defined('ABSPATH') || exit;

// Nonce endpoint for the contact form JS
add_action('rest_api_init', function () {
    register_rest_route('cos/v1', '/nonce', [
        'methods'             => 'GET',
        'callback'            => function () {
            $response = rest_ensure_response(['nonce' => wp_create_nonce('cos_contact_nonce')]);
            $response->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
            return $response;
        },
        'permission_callback' => '__return_true',
    ]);
});

/**
 * Server-rendered nonce/status markup for the contact form, so a genuinely
 * JavaScript-disabled visitor gets a real, valid nonce from first paint —
 * not just visitors whose JS happened to run.
 *
 * This deliberately does NOT use WordPress shortcodes ([cos_contact_nonce]).
 * Shortcodes inside a quoted HTML attribute value (this nonce sits in
 * value="...") go through do_shortcodes_in_html_tags() ->
 * wp_kses_attr_parse(), which only re-commits the shortcode's output for
 * element names KSES recognises with allowed attributes for that context;
 * for an element like <input> it silently discards the result and leaves
 * the literal "[cos_contact_nonce]" text in place — confirmed empirically
 * (wp-includes/shortcodes.php). A plain string filter on 'the_content'
 * doesn't run through that pipeline at all, so it isn't subject to it.
 */
add_filter('the_content', function ($content) {
    if (false === strpos($content, '{{cos_contact_')) {
        return $content;
    }

    if (false !== strpos($content, '{{cos_contact_nonce}}')) {
        $content = str_replace(
            '{{cos_contact_nonce}}',
            esc_attr(wp_create_nonce('cos_contact_nonce')),
            $content
        );
    }

    if (false !== strpos($content, '{{cos_contact_status}}')) {
        $content = str_replace('{{cos_contact_status}}', cos_contact_status_markup(), $content);
    }

    return $content;
}, 5);

/**
 * Post/redirect/get result markup for a no-JS contact form submission (see
 * cos_contact_redirect_url()). Only a status flag and a short reason *code*
 * ever travel in the URL, so this never has PII to render — the message
 * text below is a fixed, generic string per code, same as the AJAX path's
 * error copy.
 */
function cos_contact_status_markup() {
    if ( ! isset($_GET['cos_sent']) ) {
        return '';
    }

    if ( '1' === $_GET['cos_sent'] ) {
        $text = 'Thank you. Your message has been received — we will be in touch shortly.';
        $class = 'success';
    } else {
        $messages = [
            'nonce'        => 'Invalid request. Please refresh and try again.',
            'honeypot'     => 'Your message could not be sent.',
            'rate_limited' => 'Too many messages from this connection. Please wait a few minutes and try again.',
            'spam_check'   => 'Spam check failed. Please try again.',
            'validation'   => 'Please check your details and try again.',
            'mail_failed'  => 'We could not send your message right now. Please try again shortly, or email us directly.',
            'method'       => 'Invalid request. Please try again.',
        ];
        $reason = isset($_GET['cos_reason']) ? sanitize_key(wp_unslash($_GET['cos_reason'])) : '';
        $text   = $messages[$reason] ?? 'Something went wrong. Please try again.';
        $class  = 'error';
    }

    return sprintf(
        '<div class="cos-ct-msg %1$s" role="alert" aria-live="polite" style="display:block">%2$s</div>',
        esc_attr($class),
        esc_html($text)
    );
}

add_action('wp_ajax_nopriv_cos_contact', 'cos_handle_contact');
add_action('wp_ajax_cos_contact',        'cos_handle_contact');

/**
 * Gate for every test-only hook in this file. Requires ALL three:
 *  - COS_ENABLE_TEST_HOOKS defined true (only ever defined in this
 *    machine's local wp-config.php — never deployed, see project memory
 *    on staging deploy excludes).
 *  - wp_get_environment_type() === 'local' specifically (not 'staging' —
 *    a public staging visitor must never be able to reset rate limits or
 *    force a mail failure for other visitors).
 *  - COS_TEST_MODE defined true — a second, independent explicit
 *    test-mode flag, so a single leaked/misconfigured constant isn't
 *    enough on its own.
 * All three are local-only opt-ins; a request itself can never satisfy any
 * of them.
 */
function cos_contact_test_hooks_enabled() {
    return defined('COS_ENABLE_TEST_HOOKS') && true === COS_ENABLE_TEST_HOOKS
        && 'local' === wp_get_environment_type()
        && defined('COS_TEST_MODE') && true === COS_TEST_MODE;
}

/**
 * Test-only wp_mail failure injection. Only reachable when the site itself
 * opts in via cos_contact_test_hooks_enabled() AND the request explicitly
 * asks for it — this can never fire in production or on public staging
 * regardless of what a request sends.
 */
add_filter('pre_wp_mail', function ($null, $atts) {
    if (! cos_contact_test_hooks_enabled()) {
        return $null;
    }
    if (empty($_POST['cos_test_force_mail_failure'])) {
        return $null;
    }
    return false; // wp_mail() will report failure without attempting a real send.
}, 10, 2);

/**
 * True for the fetch()-driven submission the contact page's own script
 * makes (it explicitly sends this header — see the Contact page markup).
 * False for a plain browser form POST, which happens only when that script
 * never ran (JS disabled/blocked/failed) — the progressive-enhancement,
 * no-JS path.
 */
function cos_contact_is_ajax_request() {
    return isset($_SERVER['HTTP_X_REQUESTED_WITH'])
        && 'cos-fetch' === $_SERVER['HTTP_X_REQUESTED_WITH'];
}

/**
 * Post/redirect/get target for the no-JS fallback. Only a short status and
 * reason *code* travel in the URL — never the submitted name/email/message
 * — so a plain form POST can never leak PII into the address bar or
 * browser history.
 */
function cos_contact_redirect_url($sent, $reason = '') {
    $referer = wp_get_referer();
    $base    = $referer ? $referer : home_url('/contact/');
    $args    = ['cos_sent' => $sent ? '1' : '0'];
    if ($reason) {
        $args['cos_reason'] = $reason;
    }
    // Strip any previous status from the base URL so repeat failures don't
    // accumulate query args, and never carry over other query params that
    // could themselves have contained stray PII from an earlier GET fallback.
    $base = remove_query_arg(['cos_sent', 'cos_reason'], strtok($base, '?'));
    return add_query_arg($args, $base);
}

function cos_contact_respond_error($message, $status, $reason) {
    if ( cos_contact_is_ajax_request() ) {
        wp_send_json_error($message, $status);
    }
    wp_safe_redirect(cos_contact_redirect_url(false, $reason));
    exit;
}

function cos_contact_respond_success($message) {
    if ( cos_contact_is_ajax_request() ) {
        wp_send_json_success($message);
    }
    wp_safe_redirect(cos_contact_redirect_url(true));
    exit;
}

function cos_contact_correlation_id() {
    return substr(wp_hash(uniqid('', true)), 0, 12);
}

/**
 * Non-PII operational log: correlation id, time, ip hash, event, never the
 * submitted name/email/message. Kept separate from the optional full-PII log.
 */
function cos_contact_log_event($correlation_id, $event) {
    $ip  = sanitize_text_field($_SERVER['REMOTE_ADDR'] ?? 'unknown');
    $log = get_option('cos_contact_ops_log', []);
    $log[] = [
        'id'    => $correlation_id,
        'time'  => current_time('mysql'),
        'ip'    => substr(wp_hash($ip), 0, 16), // hashed, not the raw IP
        'event' => $event,
    ];
    update_option('cos_contact_ops_log', array_slice($log, -500), false);
}

function cos_handle_contact() {
    if ( ($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST' ) {
        cos_contact_respond_error('Invalid request method.', 405, 'method');
    }

    $nonce = sanitize_text_field(wp_unslash($_POST['nonce'] ?? ''));
    if ( ! wp_verify_nonce($nonce, 'cos_contact_nonce') ) {
        cos_contact_respond_error('Invalid request. Please refresh and try again.', 403, 'nonce');
    }

    // Reject automated submissions before consuming the sender's rate limit.
    // Field name/id/label are deliberately generic (not "company") so
    // browser autofill heuristics have nothing recognisable to latch onto.
    if ( ! empty($_POST['cos_hp_field']) ) {
        cos_contact_respond_error('Your message could not be sent.', 400, 'honeypot');
    }

    $ip       = sanitize_text_field($_SERVER['REMOTE_ADDR'] ?? 'unknown');
    $rate_key = 'cos_contact_' . md5($ip);

    // Test-only rate-limit reset, same three-way gate as the mail-failure
    // injection above — keeps automated test runs (which reuse one IP across
    // many requests) isolated from each other without ever being reachable
    // on production or public staging.
    if ( cos_contact_test_hooks_enabled() && ! empty($_POST['cos_test_reset_rate_limit']) ) {
        delete_transient($rate_key);
    }

    $rate_count = (int) get_transient($rate_key);
    if ( $rate_count >= 5 ) {
        cos_contact_respond_error('Too many messages from this connection. Please wait a few minutes and try again.', 429, 'rate_limited');
    }
    set_transient($rate_key, $rate_count + 1, 10 * MINUTE_IN_SECONDS);

    $recaptcha_secret = defined('COS_RECAPTCHA_SECRET') ? COS_RECAPTCHA_SECRET : get_option('cos_recaptcha_secret', '');
    $token            = sanitize_text_field(wp_unslash($_POST['g-recaptcha-response'] ?? ''));

    if ( $recaptcha_secret ) {
        if ( ! $token ) {
            cos_contact_respond_error('Please complete the spam check and try again.', 400, 'spam_check');
        }

        $verify = wp_remote_post('https://www.google.com/recaptcha/api/siteverify', [
            'timeout' => 10,
            'redirection' => 0,
            'body'    => [
                'secret'   => $recaptcha_secret,
                'response' => $token,
                'remoteip' => $ip,
            ],
        ]);

        if ( is_wp_error($verify) ) {
            cos_contact_respond_error('Could not verify the spam check. Please try again.', 502, 'spam_check');
        }

        $result = json_decode(wp_remote_retrieve_body($verify), true);
        if ( empty($result['success']) ) {
            cos_contact_respond_error('Spam check failed. Please try again.', 400, 'spam_check');
        }
    }

    // Sanitise fields
    $name    = sanitize_text_field(wp_unslash($_POST['cos_name'] ?? ''));
    $email   = sanitize_email(wp_unslash($_POST['cos_email'] ?? ''));
    $subject = sanitize_text_field(wp_unslash($_POST['cos_subject'] ?? 'Website enquiry'));
    $message = sanitize_textarea_field(wp_unslash($_POST['cos_message'] ?? ''));

    if ( empty($name) || empty($email) || empty($message) ) {
        cos_contact_respond_error('Please fill in all required fields.', 400, 'validation');
    }
    if ( ! is_email($email) ) {
        cos_contact_respond_error('Please enter a valid email address.', 400, 'validation');
    }
    if (
        mb_strlen($name) > 100 ||
        mb_strlen($email) > 254 ||
        mb_strlen($subject) > 150 ||
        mb_strlen($message) > 5000
    ) {
        cos_contact_respond_error('One or more fields are too long.', 400, 'validation');
    }

    $correlation_id = cos_contact_correlation_id();

    // Optional full-PII storage — off by default. See file header.
    if ( apply_filters('cos_contact_store_pii', defined('COS_CONTACT_STORE_PII') && COS_CONTACT_STORE_PII) ) {
        $log   = get_option('cos_contact_log', []);
        $log[] = compact('name', 'email', 'subject', 'message') + [
            'time' => current_time('mysql'),
            'id'   => $correlation_id,
        ];
        update_option('cos_contact_log', array_slice($log, -200), false); // never autoload PII
    }

    // Destination address: filterable/configurable, defaults to admin_email.
    $to = apply_filters(
        'cos_contact_recipient',
        defined('COS_CONTACT_EMAIL') && COS_CONTACT_EMAIL ? COS_CONTACT_EMAIL : get_option('admin_email')
    );

    $sent = wp_mail(
        $to,
        '[COS] ' . $subject,
        "Name: $name\nEmail: $email\n\n$message",
        [ 'Reply-To: ' . $email ]
    );

    if ( ! $sent ) {
        cos_contact_log_event($correlation_id, 'send_failed');
        if ( cos_contact_is_ajax_request() ) {
            wp_send_json_error([
                'message'         => 'We could not send your message right now. Please try again shortly, or email us directly.',
                'correlation_id'  => $correlation_id,
            ], 502);
        }
        wp_safe_redirect(cos_contact_redirect_url(false, 'mail_failed'));
        exit;
    }

    cos_contact_log_event($correlation_id, 'sent');
    cos_contact_respond_success('Thank you, ' . $name . '. Your message has been received — we will be in touch shortly.');
}

/* ─── Age-based purge of the optional full-PII log ──────────────── */

add_action('init', function () {
    if ( ! wp_next_scheduled('cos_contact_log_purge') ) {
        wp_schedule_event(time(), 'daily', 'cos_contact_log_purge');
    }
});

add_action('cos_contact_log_purge', function () {
    $retention_days = (int) apply_filters('cos_contact_log_retention_days', 90);
    $cutoff         = strtotime("-{$retention_days} days");

    foreach ( ['cos_contact_log', 'cos_contact_ops_log'] as $option_name ) {
        $log = get_option($option_name, []);
        if ( empty($log) ) {
            continue;
        }
        $log = array_values(array_filter($log, function ($entry) use ($cutoff) {
            $entry_time = isset($entry['time']) ? strtotime($entry['time']) : false;
            return $entry_time && $entry_time >= $cutoff;
        }));
        update_option($option_name, $log, false);
    }
});

/* ─── WordPress privacy exporter/eraser integration ─────────────── */
// No-op when the optional PII log is empty/disabled — present so a request
// is honoured correctly if PII storage is ever turned on for this site.

add_filter('wp_privacy_personal_data_exporters', function ($exporters) {
    $exporters['cos-contact-log'] = [
        'exporter_friendly_name' => __('Contact form submissions', 'cos'),
        'callback'                => function ($email_address, $page = 1) {
            $log  = get_option('cos_contact_log', []);
            $data = [];
            foreach ( $log as $entry ) {
                if ( isset($entry['email']) && strtolower($entry['email']) === strtolower($email_address) ) {
                    $data[] = [
                        'group_id'    => 'cos-contact-log',
                        'group_label' => __('Contact form submissions', 'cos'),
                        'item_id'     => 'cos-contact-log-' . ($entry['id'] ?? md5(serialize($entry))),
                        'data'        => [
                            [ 'name' => __('Name', 'cos'), 'value' => $entry['name'] ?? '' ],
                            [ 'name' => __('Email', 'cos'), 'value' => $entry['email'] ?? '' ],
                            [ 'name' => __('Subject', 'cos'), 'value' => $entry['subject'] ?? '' ],
                            [ 'name' => __('Message', 'cos'), 'value' => $entry['message'] ?? '' ],
                            [ 'name' => __('Date', 'cos'), 'value' => $entry['time'] ?? '' ],
                        ],
                    ];
                }
            }
            return [ 'data' => $data, 'done' => true ];
        },
    ];
    return $exporters;
});

add_filter('wp_privacy_personal_data_erasers', function ($erasers) {
    $erasers['cos-contact-log'] = [
        'eraser_friendly_name' => __('Contact form submissions', 'cos'),
        'callback'              => function ($email_address, $page = 1) {
            $log          = get_option('cos_contact_log', []);
            $removed      = 0;
            $remaining    = [];
            foreach ( $log as $entry ) {
                if ( isset($entry['email']) && strtolower($entry['email']) === strtolower($email_address) ) {
                    $removed++;
                    continue;
                }
                $remaining[] = $entry;
            }
            if ( $removed ) {
                update_option('cos_contact_log', $remaining, false);
            }
            return [
                'items_removed'  => $removed,
                'items_retained' => false,
                'messages'       => [],
                'done'           => true,
            ];
        },
    ];
    return $erasers;
});
