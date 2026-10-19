<?php
/**
 * COS Child Theme — Heritage pages as theme templates (whole-site rebuild, 16 Oct 2026).
 *
 * /heritage/ (page 61), /heritage/craftsmanship/ (390) and /heritage/sustainability/ (391) used to be
 * hand-written HTML + CSS in post_content. They now render from page-hx-heritage.php and the parts in
 * template-parts/hx/heritage/{hub,craftsmanship,sustainability}.php, styled by assets/css/hx/heritage.css
 * (class prefix hxhe-). The copy is carried over from the DB content (facts unchanged); the DB content
 * is left untouched as the fallback — remove a slug from cos_hxhe_part() to fall back to it.
 * Images: assets/images/home-2026/hxhe-* (design-sources/home-2026/build_hxhe_heritage.py) plus
 * homepage assets (provenance-summit-*, caviar-macro-light-*; never philosophy-lab (unverified)).
 */

defined( 'ABSPATH' ) || exit;

/** Template part for the current request ('hub' | 'craftsmanship' | 'sustainability'), or '' if not a heritage page. */
function cos_hxhe_part(): string {
	static $part = null;
	if ( null !== $part ) return $part;
	$part = '';
	if ( is_admin() || ! is_page() ) return $part;
	$post = get_queried_object();
	if ( ! $post instanceof WP_Post ) return $part;
	if ( 'heritage' === $post->post_name && 0 === (int) $post->post_parent ) {
		$part = 'hub';
	} elseif ( in_array( $post->post_name, array( 'craftsmanship', 'sustainability' ), true ) && $post->post_parent ) {
		$parent = get_post( $post->post_parent );
		if ( $parent && 'heritage' === $parent->post_name ) $part = $post->post_name;
	}
	return $part;
}

add_filter( 'template_include', static function ( string $template ): string {
	return cos_hxhe_part() ? get_stylesheet_directory() . '/page-hx-heritage.php' : $template;
}, 99 );

add_filter( 'body_class', static function ( array $classes ): array {
	if ( $part = cos_hxhe_part() ) array_push( $classes, 'hxhe-page', 'hxhe-page--' . $part );
	return $classes;
} );

add_action( 'wp_enqueue_scripts', static function (): void {
	if ( ! cos_hxhe_part() ) return;
	$dir = get_stylesheet_directory();
	wp_enqueue_style( 'cos-hx-heritage', get_stylesheet_directory_uri() . '/assets/css/hx/heritage.css', array( 'cos-hx-system' ), (string) filemtime( $dir . '/assets/css/hx/heritage.css' ) );
}, 30 );

add_action( 'wp_head', static function (): void {
	if ( ! cos_hxhe_part() ) return;
	echo '<meta name="theme-color" content="#FBFAF7">' . "\n";
}, 1 );

/** The arrow used by .hx-link / .hx-btn. */
function cos_hxhe_arrow(): string {
	return '<span class="hx-arrow" aria-hidden="true"></span>';
}

/**
 * The quiet "continue" navigation shared by the three heritage pages (was .cos-about-nav in the DB pages).
 * The current page is marked with aria-current and not linked twice.
 */
function cos_hxhe_continue( string $current ): void {
	$links = array(
		'hub'            => array( '/heritage/', 'Our story' ),
		'craftsmanship'  => array( '/heritage/craftsmanship/', 'Craftsmanship & sourcing' ),
		'sustainability' => array( '/heritage/sustainability/', 'Sustainability' ),
		'science'        => array( '/science/', 'The science' ),
	);
	echo '<nav class="hxhe-continue" aria-label="Swiss heritage pages"><div class="hx-wrap hxhe-continue__in">';
	echo '<ul class="hxhe-continue__list">';
	foreach ( $links as $key => [ $href, $text ] ) {
		$is = ( $key === $current );
		echo '<li><a class="hxhe-continue__link" href="' . esc_url( home_url( $href ) ) . '"' . ( $is ? ' aria-current="page"' : '' ) . '>'
			. '<span>' . esc_html( $text ) . '</span>' . ( $is ? '' : cos_hxhe_arrow() ) . '</a></li>';
	}
	echo '</ul></div></nav>';
}
