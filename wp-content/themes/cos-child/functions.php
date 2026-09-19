<?php
/**
 * COS Child Theme — functions.php
 *
 * Thin bootstrap. Implementation lives in inc/ — see each file's header
 * comment for what it owns.
 */

// Require order matters here: several hooks across these files share the
// same action name AND the same priority (e.g. wp_head@1, wp_body_open@1,
// init@10), and WordPress runs same-priority callbacks in registration
// order. This sequence reproduces the original monolithic file's exact
// registration order for every such collision — see the plan/PR notes for
// the full (hook, priority) collision table. Do not reorder casually.
require_once get_stylesheet_directory() . '/inc/product-data.php';
require_once get_stylesheet_directory() . '/inc/shipping.php';
require_once get_stylesheet_directory() . '/inc/commerce.php';
require_once get_stylesheet_directory() . '/inc/performance.php';
require_once get_stylesheet_directory() . '/inc/analytics.php';
require_once get_stylesheet_directory() . '/inc/core.php';
require_once get_stylesheet_directory() . '/inc/seo.php';
require_once get_stylesheet_directory() . '/inc/schema.php';
require_once get_stylesheet_directory() . '/inc/navigation.php';
require_once get_stylesheet_directory() . '/inc/forms.php';
