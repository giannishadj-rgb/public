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
	}
	$chrome = $is_home ? '/assets/css/home-staging/chrome.css' : '/assets/css/hx/chrome.css';
	wp_enqueue_style( 'cos-hx-chrome', $uri . $chrome, array( $is_home ? 'cos-hx-closing' : 'cos-hx-system' ), (string) filemtime( $dir . $chrome ) );

	// Type floor for the hand-authored content pages (journal, ingredients, skin concerns,
	// contact, FAQ, help, shipping, sitemap, 404): see the header of legacy-floor.css.
	if ( ! $is_home ) {
		wp_enqueue_style( 'cos-hx-legacy-floor', $uri . '/assets/css/hx/legacy-floor.css', array( 'cos-hx-chrome' ), (string) filemtime( $dir . '/assets/css/hx/legacy-floor.css' ) );
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

// Swiss scientific luxury typography (owner brief, 29 Oct 2026; every page since the same day):
// Suisse Works (display) and Suisse Int'l (text) are commercially licensed and not in the
// project. When the licensed files are placed in assets/fonts/suisse/ (names in its README),
// the whole site switches to them; until then it keeps the provisional Cormorant + Inter.
add_action( 'wp_enqueue_scripts', static function (): void {
	$dir    = get_stylesheet_directory();
	$uri    = get_stylesheet_directory_uri();
	$suisse = array( 'works-400' => 'SuisseWorks-Regular.woff2', 'intl-400' => 'SuisseIntl-Regular.woff2', 'intl-500' => 'SuisseIntl-Medium.woff2' );
	if ( array_filter( $suisse, static fn( $f ) => ! file_exists( $dir . '/assets/fonts/suisse/' . $f ) ) ) return;
	$handle = wp_style_is( 'cos-hx-typeset', 'enqueued' ) ? 'cos-hx-typeset' : 'cos-hx-typeset-site';
	$face   = static fn( $fam, $w, $f ) => "@font-face{font-family:'{$fam}';font-style:normal;font-weight:{$w};font-display:swap;src:url('" . esc_url( $uri . '/assets/fonts/suisse/' . $f ) . "') format('woff2')}";
	wp_add_inline_style( $handle, $face( 'Suisse Works', 400, $suisse['works-400'] ) . $face( "Suisse Int'l", 400, $suisse['intl-400'] ) . $face( "Suisse Int'l", 500, $suisse['intl-500'] )
		. "html body{--hx-serif:'Suisse Works','Cormorant Garamond',Georgia,serif;--hx-sans:'Suisse Int\'l','Inter',system-ui,sans-serif}" );
}, 110 );
