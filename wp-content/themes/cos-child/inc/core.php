<?php
/**
 * COS Child Theme — Bootstrap / template plumbing
 *
 * Custom-template routing, the shared document-head helper, raw-HTML-page
 * detection (also used by inc/performance.php), wpautop/script-entity
 * decoding for the theme's raw-HTML pages, emoji-asset disabling, and the
 * skip link. Doesn't belong to navigation/commerce/seo/schema/performance/
 * analytics/forms — kept separate rather than forced into one of those.
 */

/* ─── Force PHP templates for custom pages ─────────────────────── */
// Resolved via wc_get_page_id() rather than hardcoded post IDs, so this
// keeps working if WooCommerce ever regenerates its default cart/account/
// checkout pages (Tools > Status > "Create default WooCommerce pages"
// assigns fresh IDs) instead of silently routing to the wrong template.
add_filter( 'template_include', function ( string $template ): string {
	if ( ! function_exists( 'wc_get_page_id' ) ) return $template;
	$overrides = array(
		wc_get_page_id( 'cart' )      => '/page-cart.php',
		wc_get_page_id( 'myaccount' ) => '/page-account.php',
		wc_get_page_id( 'checkout' )  => '/page-checkout.php',
	);
	foreach ( $overrides as $page_id => $file ) {
		if ( $page_id > 0 && is_page( $page_id ) ) {
			$custom = get_stylesheet_directory() . $file;
			if ( file_exists( $custom ) ) return $custom;
		}
	}
	return $template;
}, 99 );

/* ─── Viewport meta ──────────────────────────────────────────────── */
// Block templates already receive a viewport meta tag from WordPress. The
// custom full-document PHP templates call this helper before wp_head().
//
// Two separate core hooks print a <title> tag depending on theme type, and
// both are unconditionally registered once a block theme is active (see
// wp-includes/block-template.php:135, "...and make it unconditional") —
// removing only the classic one left _block_template_render_title_tag
// still firing on wp_head for these raw-HTML templates, producing two
// <title> tags (confirmed via $wp_filter['wp_head']->callbacks inspection:
// twentytwentyfive is a block theme, so core registers
// _block_template_render_title_tag, not just legacy _wp_render_title_tag).
function cos_child_custom_document_head( string $fallback_title = '' ): void {
	remove_action( 'wp_head', '_wp_render_title_tag', 1 );
	remove_action( 'wp_head', '_block_template_render_title_tag', 1 );
	remove_action( 'wp_head', '_block_template_viewport_meta_tag', 0 );

	$title = trim( wp_get_document_title() );
	if ( '' === $title ) $title = $fallback_title;
	if ( '' === $title ) $title = get_bloginfo( 'name' );

	echo '<title>' . esc_html( $title ) . '</title>' . "\n";
	echo '<meta name="viewport" content="width=device-width, initial-scale=1">' . "\n";
}

/* ─── WordPress setup ───────────────────────────────────────────── */

function cos_child_is_raw_html_page(): bool {
	if ( is_admin() ) return false;
	if ( function_exists( 'is_woocommerce' ) && ( is_woocommerce() || is_cart() || is_checkout() ) ) return false;
	return is_front_page() || is_singular( 'page' );
}

function cos_child_disable_wpautop_for_raw_html_pages(): void {
	if ( cos_child_is_raw_html_page() ) remove_filter( 'the_content', 'wpautop' );
}
add_action( 'wp', 'cos_child_disable_wpautop_for_raw_html_pages' );

/**
 * Remove a leaked CSS preamble from the Journal collection hubs.
 *
 * These pages were saved with a style block in post_content, but the style
 * tags were stripped while their CSS text remained. The component rules now
 * live in style.css; this narrowly removes only the text before a matching
 * hub root and leaves every other raw-HTML page untouched.
 */
function cos_child_clean_journal_hub_content( string $content ): string {
	if ( is_admin() || ! is_singular( 'page' ) ) return $content;

	$marker  = '<div class="cos-jnl-hub">';
	$hub_pos = strpos( $content, $marker );
	$css_pos = strpos( $content, '.cos-jnl-hub{' );

	if ( false === $hub_pos || false === $css_pos || $css_pos > $hub_pos ) return $content;

	return substr( $content, $hub_pos );
}
add_filter( 'the_content', 'cos_child_clean_journal_hub_content', 1 );

function cos_child_decode_script_body_entities( string $html ): string {
	$decoded = preg_replace_callback(
		'#<script\b([^>]*)>(.*?)</script>#is',
		function ( array $m ): string {
			$attributes = $m[1];
			if ( preg_match( '/\btype\s*=\s*["\']?(?:application\/ld\+json|application\/json|speculationrules)["\']?/i', $attributes ) ) {
				return $m[0];
			}
			return '<script' . $attributes . '>' . html_entity_decode( $m[2], ENT_QUOTES | ENT_HTML5, 'UTF-8' ) . '</script>';
		},
		$html
	);

	return is_string( $decoded ) ? $decoded : $html;
}

function cos_child_decode_raw_html_script_bodies( string $content ): string {
	if ( ! cos_child_is_raw_html_page() ) return $content;
	return cos_child_decode_script_body_entities( $content );
}
add_filter( 'the_content', 'cos_child_decode_raw_html_script_bodies', PHP_INT_MAX );

function cos_child_decode_raw_html_output_script_bodies(): void {
	if ( ! cos_child_is_raw_html_page() ) return;
	ob_start( 'cos_child_decode_script_body_entities' );
}
add_action( 'template_redirect', 'cos_child_decode_raw_html_output_script_bodies', 0 );

function cos_child_disable_emoji_assets(): void {
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
}
add_action( 'init', 'cos_child_disable_emoji_assets' );

/* ─── Skip Link (WCAG 2.2 §2.4.1) ─────────────────────────────── */

// WordPress core auto-injects its own "Skip to content" link on any page
// rendered through the block-theme template canvas (regular Pages/posts
// using twentytwentyfive), which duplicated the custom skip link below on
// those pages — WooCommerce's raw-PHP templates bypass the canvas so they
// only ever had the one. Unhooking core's is the documented way themes with
// their own skip link avoid the double-up (see wp-includes/theme-templates.php).
remove_action( 'wp_enqueue_scripts', 'wp_enqueue_block_template_skip_link' );
remove_action( 'wp_footer', 'the_block_template_skip_link' );

function cos_child_skip_link(): void { ?>
<a class="cos-skip-link screen-reader-text" tabindex="0" href="#cos-main-content" style="position:absolute;left:-9999px;top:auto;width:1px;height:1px;overflow:hidden;z-index:9999;background:var(--cos-navy,#050505);color:var(--cos-pearl,#F8F8F8);padding:12px 20px;font-family:var(--hx-sans,'Inter',system-ui,sans-serif);font-size:15px;font-weight:500;text-decoration:none">
Skip to main content
</a>
<style nonce="<?php echo esc_attr( cos_csp_nonce() ); ?>">
.cos-skip-link:focus{left:8px!important;top:8px!important;width:auto!important;height:auto!important;min-height:44px;box-sizing:border-box;overflow:visible!important}
:focus-visible{outline:2px solid #2F6FED;outline-offset:2px;box-shadow:0 0 0 2px #F8F8F8}
button:focus-visible,a:focus-visible,input:focus-visible,select:focus-visible,textarea:focus-visible{outline:2px solid #2F6FED;outline-offset:2px;box-shadow:0 0 0 2px #F8F8F8}
</style>
<?php }
add_action( 'wp_body_open', 'cos_child_skip_link', 1 );

/* ─── Respect prefers-reduced-motion for jQuery-driven UI ───────────
 * The sitewide CSS reset (inc/navigation.php, cos_child_global_styles())
 * already forces animation/transition-duration to ~0 under reduced-motion,
 * but that only affects CSS transitions/@keyframes — it has no effect on
 * WooCommerce core's own JS-driven animations (checkout.js's coupon-form
 * toggle uses jQuery .slideToggle(400, ...), which interpolates height via
 * requestAnimationFrame entirely in JS and never consults CSS at all).
 * jQuery.fx.off is the documented, global switch: every jQuery animation
 * method (slideToggle/slideUp/slideDown/animate/fadeIn/fadeOut) jumps
 * straight to its end state when set, checked at call time — so this only
 * needs to run once before the first user interaction, not before any
 * particular script binds its handlers.
 */
function cos_child_reduced_motion_jquery_bridge(): void {
	if ( is_admin() ) return;
	?>
<script nonce="<?php echo esc_attr( cos_csp_nonce() ); ?>">
(function(){
  function applyFxOff(){ if (window.jQuery) { jQuery.fx.off = true; } }
  var mq = window.matchMedia ? window.matchMedia('(prefers-reduced-motion: reduce)') : null;
  if (mq && mq.matches) applyFxOff();
  if (mq && mq.addEventListener) {
    mq.addEventListener('change', function(e){ if (e.matches) applyFxOff(); });
  }
})();
</script>
	<?php
}
add_action( 'wp_footer', 'cos_child_reduced_motion_jquery_bridge', 1 );

/* ─── CSP nonce for the 404 template's inline <style> ─────────────
 * templates/404.html is a static FSE block template — no <?php ?> available
 * to interpolate cos_csp_nonce() the way every other theme file in this
 * migration does. Its single <style> tag sits inside a core/html block,
 * rendered verbatim by WordPress; render_block_core/html is the one place
 * that content passes through PHP before output, so the nonce is added
 * there instead — gated to is_404() so this never touches any other
 * core/html block (Pages' hand-authored post_content uses the same block
 * type and is deliberately out of scope for this pass).
 */
add_filter( 'render_block_core/html', function ( string $block_content ): string {
	if ( ! is_404() ) return $block_content;
	return str_replace( '<style>', '<style nonce="' . esc_attr( cos_csp_nonce() ) . '">', $block_content );
} );
