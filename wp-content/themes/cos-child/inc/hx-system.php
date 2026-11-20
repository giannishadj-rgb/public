<?php
/**
 * COS Child Theme — hx design system, sitewide (16 Oct 2026).
 *
 * Loads the design system built for the 14 Oct homepage rebuild on EVERY page:
 *   assets/css/hx/system.css  tokens (--hx-*, on :root), type/layout/link/button utilities,
 *                             reveal states, the upright Cormorant face
 *   assets/js/hx-motion.js    data-hx-reveal / data-hx-drift (window.hxMotion)
 *   assets/css/hx/chrome.css  the sitewide header, mobile menu and footer
 *
 * The homepage (inc/home-2026.php) already enqueues system.css + hx-motion.js in its own
 * order (after the legacy homepage sheets); this file leaves that untouched and only adds
 * chrome.css after the homepage's last section sheet. Rules at the end of system.css that
 * are scoped to body.cos-h26 (transparent header over the hero etc.) stay homepage-only.
 *
 * Page families add their own sheet with 'cos-hx-system' as a dependency, e.g.
 *   wp_enqueue_style( 'cos-hx-shop', $uri . '/assets/css/hx/shop.css', array( 'cos-hx-system' ), $ver );
 */

defined( 'ABSPATH' ) || exit;

add_action( 'wp_enqueue_scripts', static function (): void {
	$dir     = get_stylesheet_directory();
	$uri     = get_stylesheet_directory_uri();
	$is_home = function_exists( 'cos_h26_is_active' ) && cos_h26_is_active();

	if ( ! $is_home ) {
		wp_enqueue_style( 'cos-hx-system', $uri . '/assets/css/hx/system.css', array(), (string) filemtime( $dir . '/assets/css/hx/system.css' ) );
		wp_enqueue_script( 'cos-hx-motion', $uri . '/assets/js/hx-motion.js', array(), (string) filemtime( $dir . '/assets/js/hx-motion.js' ), array( 'strategy' => 'defer', 'in_footer' => true ) );
		// 25 Oct 2026: light or dark type on the old pages' bleed openings (see the file header)
		wp_enqueue_script( 'cos-hx-hero-tone', $uri . '/assets/js/hx-hero-tone.js', array(), (string) filemtime( $dir . '/assets/js/hx-hero-tone.js' ), array( 'strategy' => 'defer', 'in_footer' => true ) );
		// 31 Oct 2026: the shared sticky section bar (template-parts/hx/section-bar.php); exits at once on pages without one
		wp_enqueue_script( 'cos-hx-section-bar', $uri . '/assets/js/hx-section-bar.js', array(), (string) filemtime( $dir . '/assets/js/hx-section-bar.js' ), array( 'strategy' => 'defer', 'in_footer' => true ) );
	}
	// One header/menu/footer sheet on every page since 8 Nov 2026 (home-staging/chrome.css retired).
	$chrome = '/assets/css/hx/chrome.css';
	wp_enqueue_style( 'cos-hx-chrome', $uri . $chrome, array( $is_home ? 'cos-hx-closing' : 'cos-hx-system' ), (string) filemtime( $dir . $chrome ) );

	// Type floor for the hand-authored content pages (journal, ingredients, skin concerns,
	// contact, FAQ, help, shipping, sitemap, 404): see the header of legacy-floor.css.
	// Audit 8 Nov 2026: product pages match none of its rules (0% coverage), so there the handle
	// is an empty alias (src false) — typeset-site.css still depends on it for its print order.
	if ( ! $is_home ) {
		$floor = function_exists( 'is_product' ) && is_product() ? false : $uri . '/assets/css/hx/legacy-floor.css';
		wp_register_style( 'cos-hx-legacy-floor', $floor, array( 'cos-hx-chrome' ), (string) filemtime( $dir . '/assets/css/hx/legacy-floor.css' ) );
		wp_enqueue_style( 'cos-hx-legacy-floor' );
	}
}, 20 );

// The homepage's type roles, palette and hero treatment on every other page (21 Oct 2026):
// see the header of typeset-site.css. Late priority so it prints after the family sheets.
add_action( 'wp_enqueue_scripts', static function (): void {
	if ( function_exists( 'cos_h26_is_active' ) && cos_h26_is_active() ) {
		return;
	}
	$dir = get_stylesheet_directory();
	wp_enqueue_style( 'cos-hx-typeset-site', get_stylesheet_directory_uri() . '/assets/css/hx/typeset-site.css', array( 'cos-hx-legacy-floor' ), (string) filemtime( $dir . '/assets/css/hx/typeset-site.css' ) );
}, 100 );

// Six type roles on every page (1 Nov 2026): loaded after every other sheet; see its header.
add_action( 'wp_enqueue_scripts', static function (): void {
	$dir = get_stylesheet_directory();
	wp_enqueue_style( 'cos-hx-scale', get_stylesheet_directory_uri() . '/assets/css/hx/scale.css', array(), (string) filemtime( $dir . '/assets/css/hx/scale.css' ) );
}, 120 );

// Site typefaces (owner brief, 29 Oct 2026; free pair chosen 4 Nov 2026).
// Preferred: Suisse Works (display) + Suisse Int'l (text), commercially licensed. When all three
// licensed files are in assets/fonts/suisse/ (names in its README), every page uses them.
// Until then: Source Serif 4 Light (OFL variable, optical sizing) for display, Inter 400/500 for text.
// Tokens are set !important on body so they beat system.css (:root,body.cos-h26) and the
// legacy :root tokens in inc/navigation.php that older DB page content reads.
// Attached to cos-hx-scale, which loads last on every page.
add_action( 'wp_enqueue_scripts', static function (): void {
	$dir    = get_stylesheet_directory();
	$uri    = get_stylesheet_directory_uri() . '/assets/fonts/';
	$face   = static fn( $fam, $w, $f, $style = 'normal' ) => "@font-face{font-family:'{$fam}';font-style:{$style};font-weight:{$w};font-display:swap;src:url('" . esc_url( $uri . $f ) . "') format('woff2')}";
	$suisse = array( 'works-400' => 'suisse/SuisseWorks-Regular.woff2', 'intl-400' => 'suisse/SuisseIntl-Regular.woff2', 'intl-500' => 'suisse/SuisseIntl-Medium.woff2' );
	if ( ! array_filter( $suisse, static fn( $f ) => ! file_exists( $dir . '/assets/fonts/' . $f ) ) ) {
		$css   = $face( 'Suisse Works', 400, $suisse['works-400'] ) . $face( "Suisse Int'l", 400, $suisse['intl-400'] ) . $face( "Suisse Int'l", 500, $suisse['intl-500'] );
		$serif = "'Suisse Works','Source Serif 4',Georgia,serif";
		$sans  = "'Suisse Int\'l','Inter',system-ui,-apple-system,'Segoe UI',Arial,sans-serif";
	} else {
		// Owner, 4 Nov 2026: thinner headings. Variable Source Serif 4 (Google Fonts, OFL; weight 200–900,
		// optical size 8–60, so large headings get the finer Display design automatically). The weight
		// descriptor is pinned at 300, which clamps every requested serif weight to Light.
		// Revert: the static 300/400 files are still in assets/fonts.
		$css   = $face( 'Source Serif 4', '300', 'source-serif4-var-latin.woff2' )
			. $face( 'Source Serif 4', '300', 'source-serif4-var-italic-latin.woff2', 'italic' );
		$serif = "'Source Serif 4',Georgia,'Times New Roman',serif";
		$sans  = "'Inter',system-ui,-apple-system,'Segoe UI',Arial,sans-serif";
	}
	$css .= "html body{--hx-serif:{$serif}!important;--hx-sans:{$sans}!important;--cos-display:{$serif}!important;--cos-serif:{$serif}!important;--cos-body:{$sans}!important}";
	wp_add_inline_style( 'cos-hx-scale', $css );
}, 121 );
