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
	}
	$chrome = $is_home ? '/assets/css/home-staging/chrome.css' : '/assets/css/hx/chrome.css';
	wp_enqueue_style( 'cos-hx-chrome', $uri . $chrome, array( $is_home ? 'cos-hx-closing' : 'cos-hx-system' ), (string) filemtime( $dir . $chrome ) );
}, 20 );
