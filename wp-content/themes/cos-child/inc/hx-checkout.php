<?php
/**
 * COS Child Theme — cart, checkout and account styling hooks (hx rebuild, 16 Oct 2026).
 *
 * Owner: cart & checkout agent. Class prefix hxco-. Stylesheet: assets/css/hx/checkout.css,
 * loaded only on the cart, checkout (incl. order-received) and account pages.
 * Markup: page-cart.php (fully theme-owned) and page-checkout.php (wraps WooCommerce's
 * classic [woocommerce_checkout] shortcode; its fields, notices, payment box and thank-you
 * page are WooCommerce markup, restyled in checkout.css).
 *
 * Nothing here changes how an order is taken: no field, validation or gateway logic.
 * It only adds the stylesheet, a body class, product images in the order summary,
 * the system button class on "Place order" and quieter coupon wording.
 */

defined( 'ABSPATH' ) || exit;

/** True on the pages this family styles. */
function cos_hxco_is_page(): bool {
	if ( ! function_exists( 'is_cart' ) ) return false;
	return is_cart() || is_checkout() || is_account_page();
}

add_action( 'wp_enqueue_scripts', static function (): void {
	if ( ! cos_hxco_is_page() ) return;
	$file = get_stylesheet_directory() . '/assets/css/hx/checkout.css';
	wp_enqueue_style( 'cos-hx-checkout', get_stylesheet_directory_uri() . '/assets/css/hx/checkout.css', array( 'cos-hx-system' ), (string) filemtime( $file ) );
}, 21 );

add_filter( 'body_class', static function ( array $classes ): array {
	if ( cos_hxco_is_page() ) $classes[] = 'hxco-page';
	return $classes;
} );

/**
 * The owner's product image for a cart line: the homepage collection cut-outs for the four
 * formulas, the silver-tray photograph (all four) for The Complete Ritual. Falls back to the
 * WooCommerce product image for anything else.
 */
function cos_hxco_line_image( WC_Product $product, string $sizes = '120px', ?string $alt = null ): string {
	$slug = $product->get_slug();
	if ( $product->get_parent_id() ) {
		$parent = wc_get_product( $product->get_parent_id() );
		if ( $parent ) $slug = $parent->get_slug();
	}
	$alt     = $alt ?? $product->get_name();
	$objects = array(
		'micellar-water-cleanser' => 'cleanser',
		'advanced-repair-serum'   => 'serum',
		'revitalizing-eye-cream'  => 'eye',
		'24h-regeneration-cream'  => 'cream',
	);
	if ( function_exists( 'cos_h26_picture' ) ) {
		if ( isset( $objects[ $slug ] ) ) {
			return cos_h26_picture( 'pack-' . $objects[ $slug ], array( 480, 720 ), array( 2, 3 ), $alt, array( 'class' => 'hxco-thumb__pic hxco-thumb__pic--object', 'sizes' => $sizes, 'w' => 480 ) );
		}
		if ( 'the-complete-ritual' === $slug ) {
			return cos_h26_picture( 'final-tray-sq', array( 560 ), array( 1, 1 ), $alt, array( 'class' => 'hxco-thumb__pic hxco-thumb__pic--photo', 'sizes' => $sizes, 'w' => 560 ) );
		}
	}
	$img_id = $product->get_image_id();
	return $img_id ? wp_get_attachment_image( $img_id, 'medium', false, array( 'class' => 'hxco-thumb__img', 'alt' => $alt, 'loading' => 'lazy' ) ) : '';
}

/* Order summary on checkout: a small product image beside each line. Limited to the
   review-order table (also re-rendered by WooCommerce's update_order_review AJAX). */
add_filter( 'woocommerce_cart_item_name', static function ( $name, $cart_item ) {
	if ( ! did_action( 'woocommerce_review_order_before_cart_contents' ) || did_action( 'woocommerce_review_order_after_cart_contents' ) ) return $name;
	$product = $cart_item['data'] ?? null;
	if ( ! $product instanceof WC_Product ) return $name;
	return '<span class="hxco-sumline"><span class="hxco-thumb hxco-thumb--sm" aria-hidden="true">' . cos_hxco_line_image( $product, '64px', '' ) . '</span><span class="hxco-sumline__name">' . $name . '</span></span>';
}, 20, 2 );
/* "Place order" wears the system button. */
add_filter( 'woocommerce_order_button_html', static function ( string $html ): string {
	return preg_replace( '/class="button alt/', 'class="button alt hx-btn hx-btn--block hxco-place', $html, 1 );
}, 20 );

/* Quieter coupon wording on checkout (same accessible toggle WooCommerce renders). */
add_filter( 'woocommerce_checkout_coupon_message', static function (): string {
	return 'Have a promotion code? <a href="#" role="button" aria-label="Enter your promotion code" aria-controls="woocommerce-checkout-form-coupon" aria-expanded="false" class="showcoupon">Enter it here</a>';
} );
