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
require_once get_stylesheet_directory() . '/inc/home-2026.php';
require_once get_stylesheet_directory() . '/inc/hx-system.php'; // sitewide hx design system + header/footer styles (16 Oct 2026)
require_once get_stylesheet_directory() . '/inc/hx-minibag.php'; // add-to-bag confirmation panel (16 Oct 2026)
require_once get_stylesheet_directory() . '/inc/hx-heritage.php'; // Heritage pages as theme templates (16 Oct 2026, heritage-pages agent)
require_once get_stylesheet_directory() . '/inc/hx-science.php'; // Swiss science pages as theme templates (16 Oct 2026, science-pages agent)
require_once get_stylesheet_directory() . '/inc/hx-checkout.php'; // cart/checkout/account hx styling hooks (16 Oct 2026, cart & checkout agent)
