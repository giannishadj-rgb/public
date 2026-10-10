<?php
/**
 * COS Child Theme — Performance
 *
 * Asset loading: what CSS/JS gets shipped and when, regardless of whether
 * the target is WooCommerce, GTM, or fonts. Uses cos_child_is_raw_html_page()
 * from inc/core.php.
 */

/* ─── WebP derivatives for newly uploaded images ────────────────── */
// Only affects uploads from this point forward — it does not touch or
// regenerate any existing image files, so nothing already on disk changes.
// Backfilling WebP/AVIF for the existing media library is a bulk,
// resource-intensive operation (regenerate thumbnails across every
// attachment) that belongs to an owner-scheduled maintenance window, not an
// automatic side effect here — see OWNER-ACTIONS.md.
add_filter( 'image_editor_output_format', function ( array $formats ): array {
	$formats['image/jpeg'] = 'image/webp';
	$formats['image/png']  = 'image/webp';
	return $formats;
} );

/* ─── Next-gen format backfill (Shop grid + PDP gallery) ──────────
 * The image_editor_output_format filter above only affects sub-sizes
 * generated from this point forward. 2026-08-17/18: backfilled WebP+AVIF
 * sibling files (same name, .webp/.avif extension) for every JPEG actually
 * referenced by the shop grid and product-detail galleries, via a one-off
 * GD batch script (not tracked in the repo — mirror it if more images need
 * backfilling later). This helper serves them as a <picture> with AVIF and
 * WebP <source>s ahead of the original JPEG <img> fallback, generated only
 * for whichever of the two sibling files actually exist on disk — never
 * assumes both were backfilled, so it degrades cleanly for any image this
 * pass didn't reach.
 */
function cos_child_nextgen_srcset( string $srcset, string $ext ): string {
	if ( '' === trim( $srcset ) ) return '';
	$out = [];
	foreach ( explode( ',', $srcset ) as $part ) {
		$part = trim( $part );
		if ( '' === $part ) continue;
		$lastSpace  = strrpos( $part, ' ' );
		$url        = false !== $lastSpace ? substr( $part, 0, $lastSpace ) : $part;
		$descriptor = false !== $lastSpace ? substr( $part, $lastSpace ) : '';
		$newUrl     = preg_replace( '/\.jpe?g$/i', '.' . $ext, $url );
		if ( $newUrl === $url ) continue; // wasn't a .jpg/.jpeg URL
		$path = ABSPATH . ltrim( (string) wp_parse_url( $newUrl, PHP_URL_PATH ), '/' );
		if ( file_exists( $path ) ) {
			$out[] = $newUrl . $descriptor;
		}
	}
	return implode( ', ', $out );
}

/**
 * Wraps a JPEG <img> tag's src/srcset in a <picture> with AVIF/WebP <source>s
 * for whichever sibling next-gen files exist on disk. $img_html is the
 * complete, already-built <img ...> tag (kept exactly as the caller
 * constructed it, including class/loading/alt/etc.) — this only adds
 * <source> siblings in front of it, never modifies the <img> itself.
 */
function cos_child_nextgen_picture( string $src, string $srcset, string $sizes, string $img_html ): string {
	$fallbackSrcset = $srcset ?: ( $src . ' 1x' );
	$avif = cos_child_nextgen_srcset( $fallbackSrcset, 'avif' );
	$webp = cos_child_nextgen_srcset( $fallbackSrcset, 'webp' );
	if ( ! $avif && ! $webp ) return $img_html; // nothing backfilled for this image — plain <img>, no wrapper needed

	$sizesAttr = $sizes ? ' sizes="' . esc_attr( $sizes ) . '"' : '';
	$out = '<picture>';
	if ( $avif ) $out .= '<source type="image/avif" srcset="' . esc_attr( $avif ) . '"' . $sizesAttr . '>';
	if ( $webp ) $out .= '<source type="image/webp" srcset="' . esc_attr( $webp ) . '"' . $sizesAttr . '>';
	$out .= $img_html . '</picture>';
	return $out;
}

// Small portrait sizes for the homepage ritual-rail/spotlight packshots
// (2:3 source ratio) — the registered size ladder previously jumped straight
// from 200x300 (thumbnail-ish) to 683x1024 ('large'), with nothing sized for
// how narrow these actually render in the 2-column mobile grid.
add_action( 'after_setup_theme', function (): void {
	add_image_size( 'cos-packshot-320', 320, 480, false );
	add_image_size( 'cos-packshot-480', 480, 720, false );
} );

/* ─── Base styles & fonts ──────────────────────────────────────── */

// style.css is printed inline (below) rather than linked as an external
// stylesheet — it's small (~7KB) and was the page's only render-blocking
// network request; inlining it removes that round-trip from the critical
// path entirely. The file itself is kept on disk since WordPress still
// needs it as the theme's stylesheet header (Theme Name, Template, Version).
function cos_child_inline_styles(): void {
	$css_path = get_stylesheet_directory() . '/style.css';
	if ( ! file_exists( $css_path ) ) return;
	$css = file_get_contents( $css_path );
	if ( false === $css ) return;
	// Strip the theme metadata header comment — irrelevant at runtime.
	$css = preg_replace( '#^/\*.*?\*/#s', '', $css, 1 );
	// url(...) in style.css was relative to the theme directory (where the
	// file lives on disk); printed inline, those same relative paths would
	// instead resolve against the page's own URL, so rewrite them absolute.
	$theme_uri = get_stylesheet_directory_uri();
	$css = preg_replace( "#url\\((['\"]?)(?!https?://|data:|/)#i", 'url($1' . $theme_uri . '/', $css );
	echo '<style id="cos-child-style-inline" nonce="' . esc_attr( cos_csp_nonce() ) . '">' . $css . '</style>' . "\n";
}
add_action( 'wp_head', 'cos_child_inline_styles', 9 );

// Dequeue WooCommerce scripts on pages that don't need them (homepage, editorial, etc.)
add_action( 'wp_enqueue_scripts', function (): void {
	if ( ! function_exists( 'WC' ) ) return;
	$is_woo_page = is_woocommerce() || is_cart() || is_checkout() || is_account_page() || is_page( 62 );
	if ( $is_woo_page ) return;
	$woo_scripts = [
		'wc-add-to-cart',
		'wc-cart-fragments',
		'woocommerce',
		'jquery-blockui',
		'js-cookie',
		'wc-order-attribution',
		'sourcebuster',
		'jquery',
		'jquery-core',
	];
	foreach ( $woo_scripts as $handle ) {
		wp_dequeue_script( $handle );
		wp_deregister_script( $handle );
	}
}, 99 );

// Dequeue jquery-migrate on the frontend — only needed for legacy plugins
add_action( 'wp_enqueue_scripts', function (): void {
	wp_dequeue_script( 'jquery-migrate' );
}, 99 );

// Force-remove sourcebuster and any remaining WooCommerce tracking scripts on non-shop pages
add_filter( 'script_loader_tag', function ( string $tag, string $handle ): string {
	if ( is_admin() ) return $tag;
	$is_woo_page = ( function_exists( 'is_woocommerce' ) && is_woocommerce() ) || is_cart() || is_checkout() || is_account_page() || is_page( 62 );
	if ( $is_woo_page ) return $tag;
	$blocked = [ 'sourcebuster', 'sourcebuster-js', 'wc-order-attribution', 'wc-cart-fragments', 'jquery-blockui', 'js-cookie', 'woocommerce' ];
	if ( in_array( $handle, $blocked, true ) ) return '';
	return $tag;
}, 10, 2 );

function cos_child_optimize_front_page_assets(): void {
	// The shop archive, single-product, cart, and checkout pages all render
	// through their own custom full-document PHP templates
	// (woocommerce/archive-product.php, woocommerce/single-product.php,
	// page-cart.php, page-checkout.php) exactly like the "raw html" pages do --
	// none of them use block-editor markup. But cos_child_is_raw_html_page()
	// deliberately excludes anything is_woocommerce()/is_cart()/is_checkout()
	// (that carve-out matters for its OTHER uses: wpautop and script-entity
	// decoding). Left as the sole gate here, it meant these pages' block-theme
	// "global-styles" stylesheet never got dequeued, silently overriding
	// elements with no explicit line-height of their own (e.g. the shared
	// footer's link lists) with the theme.json default -- taller row spacing
	// there than every other page.
	$is_custom_full_doc_template = cos_child_is_raw_html_page()
		|| ( function_exists( 'is_shop' ) && is_shop() )
		|| is_singular( 'product' )
		|| ( function_exists( 'is_cart' ) && is_cart() )
		|| ( function_exists( 'is_checkout' ) && is_checkout() );
	if ( ! $is_custom_full_doc_template ) return;
	wp_dequeue_style( 'wp-block-library' );
	wp_dequeue_style( 'wp-block-library-theme' );
	wp_dequeue_style( 'global-styles' );
	wp_dequeue_style( 'classic-theme-styles' );

	// The shop archive is a fully custom document with its own component CSS.
	// WooCommerce's block-theme styles are unused there and include page-level
	// form rules that otherwise leak into the shared newsletter/footer.
	if ( function_exists( 'is_shop' ) && is_shop() ) {
		$shop_block_styles = [
			'woocommerce-blocktheme',
			'wc-blocks-style',
			'wc-blocks-vendors-style',
			'wc-blocks-editor-style',
			'wc-blocks',
		];
		foreach ( $shop_block_styles as $handle ) {
			wp_dequeue_style( $handle );
			wp_deregister_style( $handle );
		}
	}
}
add_action( 'wp_enqueue_scripts', 'cos_child_optimize_front_page_assets', 100 );

// Dequeue WooCommerce CSS on non-shop pages
add_action( 'wp_enqueue_scripts', function (): void {
	$is_woo_page = ( function_exists( 'is_woocommerce' ) && is_woocommerce() ) || is_cart() || is_checkout() || is_account_page() || is_page( 62 );
	if ( $is_woo_page ) return;
	$woo_styles = [
		'woocommerce-blocktheme',
		'wc-blocks-style',
		'wc-blocks-vendors-style',
		'wc-blocks-editor-style',
		'woocommerce-general',
		'woocommerce-layout',
		'woocommerce-smallscreen',
		'wc-blocks',
	];
	foreach ( $woo_styles as $handle ) {
		wp_dequeue_style( $handle );
		wp_deregister_style( $handle );
	}
}, 100 );

// Block wc-blocks CSS via style_loader_tag on non-shop pages
add_filter( 'style_loader_tag', function ( string $tag, string $handle ): string {
	if ( is_admin() ) return $tag;
	$is_woo_page = ( function_exists( 'is_woocommerce' ) && is_woocommerce() ) || is_cart() || is_checkout() || is_account_page() || is_page( 62 );
	if ( $is_woo_page ) return $tag;
	$blocked = [ 'woocommerce-blocktheme', 'wc-blocks-style', 'wc-blocks', 'wc-blocks-vendors-style' ];
	if ( in_array( $handle, $blocked, true ) ) return '';
	return $tag;
}, 10, 2 );

/* ─── Inline Complianz's cookie-blocker CSS ─────────────────────── */
// Complianz enqueues assets/css/cookieblocker.min.css (handle
// 'cmplz-general') as an external stylesheet on every page — see
// documents/class-document.php's enqueue_assets(), hooked on
// wp_enqueue_scripts at the default priority. It's tiny (~1.1KB gzipped)
// but still costs a full render-blocking round trip. Dequeue the external
// link after Complianz registers it, and print its content inline instead
// — read fresh from the plugin's own file on every request (never copied
// into this codebase) so a future Complianz update keeps working
// automatically with no stale copy to remember to refresh.
add_action( 'wp_enqueue_scripts', function (): void {
	if ( ! wp_style_is( 'cmplz-general', 'registered' ) ) return;
	wp_dequeue_style( 'cmplz-general' );
	wp_deregister_style( 'cmplz-general' );
}, 20 );

function cos_child_inline_complianz_cookieblocker(): void {
	if ( ! defined( 'CMPLZ_PATH' ) ) return;
	if ( function_exists( 'cmplz_get_option' ) && 1 === cmplz_get_option( 'disable_cookie_block' ) ) return;
	$min      = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';
	$css_path = CMPLZ_PATH . "assets/css/cookieblocker{$min}.css";
	if ( ! file_exists( $css_path ) ) return;
	$css = file_get_contents( $css_path );
	if ( false === $css ) return;
	echo '<style id="cmplz-general-inline" nonce="' . esc_attr( cos_csp_nonce() ) . '">' . $css . '</style>' . "\n";
}
add_action( 'wp_head', 'cos_child_inline_complianz_cookieblocker', 9 );

// Preload critical fonts for faster first paint
function cos_child_preload_fonts(): void {
	$font_uri = get_stylesheet_directory_uri() . '/assets/fonts/';
	// One variable-font file now covers Inter 300-600 (see style.css) — a
	// single preload replaces the old one-static-file-at-a-time preload,
	// which only ever covered weight 400.
	echo '<link rel="preload" href="' . esc_url( $font_uri . 'inter-var-latin.woff2' ) . '" as="font" type="font/woff2" crossorigin>' . "\n";
	// Cormorant Garamond is self-hosted (see style.css) — only italic/300 is
	// ever used anywhere in the theme, so only that one variant is preloaded.
	echo '<link rel="preload" href="' . esc_url( $font_uri . 'cormorant-garamond-300-italic-latin.woff2' ) . '" as="font" type="font/woff2" crossorigin>' . "\n";
}
add_action( 'wp_head', 'cos_child_preload_fonts', 1 );

// Homepage hero (page content, art-directed <picture>): preload only the AVIF
// crop this viewport will use, matching the <source media> breakpoints.
// Browsers without AVIF support ignore a type="image/avif" preload.
function cos_child_preload_home_hero(): void {
	// The 2026 homepage (inc/home-2026.php) preloads its own hero.
	if ( ! is_front_page() || ( function_exists( 'cos_h26_is_active' ) && cos_h26_is_active() ) ) return;
	$dir = get_stylesheet_directory_uri() . '/assets/images/home-hero/';
	foreach ( array(
		array( '(min-width: 1280px)', 'wide', array( 1200, 1600 ) ),
		array( '(min-width: 1024px) and (max-width: 1279.98px)', 'compact', array( 900, 1200 ) ),
		array( '(min-width: 768px) and (max-width: 1023.98px)', 'tablet', array( 768, 1024 ) ),
		array( '(max-width: 767.98px)', 'mobile', array( 720, 1024 ) ),
	) as [ $media, $crop, $widths ] ) {
		$set = implode( ', ', array_map( static fn( int $w ): string => "{$dir}home-hero-{$crop}-{$w}.avif {$w}w", $widths ) );
		echo '<link rel="preload" as="image" type="image/avif" fetchpriority="high" media="' . esc_attr( $media ) . '" imagesrcset="' . esc_attr( $set ) . '" imagesizes="100vw">' . "
";
	}
}
add_action( 'wp_head', 'cos_child_preload_home_hero', 2 );
