<?php
/**
 * Template Name: COS Cart
 *
 * The bag (hx rebuild, 16 Oct 2026 — cart & checkout agent, prefix hxco-).
 * Fully theme-owned markup; every action posts to WooCommerce's own cart handler
 * (WC_Form_Handler::update_cart_action): quantity +/- (update_cart), remove (remove_item
 * link), promotion code (apply_coupon / remove_coupon). Styles: assets/css/hx/checkout.css.
 * The legacy cos-cp-* class names are kept on the matching elements as hooks for the
 * existing Playwright suite (tests/03, 05, 07, 18); they carry no styles any more.
 */
defined( 'ABSPATH' ) || exit;

WC()->cart->calculate_totals();
$cart_items   = WC()->cart->get_cart();
$subtotal     = WC()->cart->get_subtotal();
$total        = WC()->cart->get_total( 'edit' );
$checkout_url = wc_get_checkout_url();
$is_empty     = WC()->cart->is_empty();
$item_count   = WC()->cart->get_cart_contents_count();

$has_bundle       = false;
$bundle_price     = 0.0;
foreach ( $cart_items as $cart_item ) {
	if ( 'COS-BUNDLE' === $cart_item['data']->get_sku() ) {
		$has_bundle   = true;
		$bundle_price = (float) $cart_item['data']->get_price();
		break;
	}
}

// Real-time saving, not a hardcoded figure — sum of the 4 individual
// products' current prices minus the bundle's current price, same
// computation single-product.php already uses on the bundle PDP.
$bundle_saving = 0.0;
if ( $has_bundle ) {
	$step_skus     = array( 'COS-001', 'COS-002', 'COS-003', 'COS-004' );
	$parts_total   = 0.0;
	foreach ( $step_skus as $step_sku ) {
		$step_id = wc_get_product_id_by_sku( $step_sku );
		$step_wc = $step_id ? wc_get_product( $step_id ) : null;
		$parts_total += $step_wc ? (float) $step_wc->get_price() : 0;
	}
	$bundle_saving = $parts_total - $bundle_price;
}

$cos_cart_gateway_ids = function_exists( 'cos_child_enabled_payment_gateways' )
	? array_keys( cos_child_enabled_payment_gateways() )
	: array();
if ( array( 'cod' ) === $cos_cart_gateway_ids ) {
	$cos_cart_payment_label = 'Pay on Delivery';
} elseif ( in_array( 'cod', $cos_cart_gateway_ids, true ) && count( $cos_cart_gateway_ids ) > 1 ) {
	$cos_cart_payment_label = 'Secure Payment or Pay on Delivery';
} elseif ( $cos_cart_gateway_ids ) {
	$cos_cart_payment_label = 'Secure Online Payment';
} else {
	$cos_cart_payment_label = 'Contact Us to Order';
}

// Step number + verb and size per formula, from the homepage's single product source.
$cos_steps = array();
if ( function_exists( 'cos_h26_products' ) ) {
	foreach ( cos_h26_products() as $hp ) {
		$cos_steps[ $hp['slug'] ] = $hp;
	}
}

// Notices: shown here, so they are consumed on this page instead of piling up on
// checkout. "… has been added to your cart" confirmations are dropped — the bag itself
// is the confirmation.
$cos_notices = function_exists( 'wc_get_notices' ) ? wc_get_notices() : array();
if ( ! empty( $cos_notices['success'] ) ) {
	$cos_notices['success'] = array_values( array_filter( $cos_notices['success'], static function ( $n ): bool {
		$text = is_array( $n ) ? (string) ( $n['notice'] ?? '' ) : (string) $n;
		return false === stripos( wp_strip_all_tags( $text ), 'added to your cart' );
	} ) );
}
// A promotion-code error is shown inline under the code field (with the field marked
// invalid) instead of in the page-level notices.
$cos_coupon_msgs = array();
if ( ! empty( $_POST['apply_coupon'] ) && ! empty( $cos_notices['error'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification -- display only
	foreach ( $cos_notices['error'] as $n ) {
		$msg = html_entity_decode( wp_strip_all_tags( is_array( $n ) ? (string) ( $n['notice'] ?? '' ) : (string) $n ), ENT_QUOTES, 'UTF-8' );
		if ( false !== stripos( $msg, 'does not exist' ) ) {
			$msg = 'We don’t recognise that code. Please check it and try again.';
		}
		$cos_coupon_msgs[] = $msg;
	}
	$cos_notices['error'] = array();
}
if ( function_exists( 'wc_set_notices' ) ) wc_set_notices( array_filter( $cos_notices ) );

$cos_coupons      = WC()->cart->get_coupons();
$cos_ex_tax       = WC()->cart->display_cart_ex_tax;
$cos_cart_url     = wc_get_cart_url();
$cos_coupon_value = isset( $_POST['coupon_code'] ) ? wc_clean( wp_unslash( $_POST['coupon_code'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification -- display only
$cos_coupon_error = (bool) $cos_coupon_msgs;
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<?php cos_child_custom_document_head( 'Cart - Caviar of Switzerland' ); ?>
<?php wp_head(); ?>
</head>
<body <?php body_class( 'woocommerce woocommerce-page' ); ?>>
<?php wp_body_open(); ?>

<main id="cos-main-content" class="hxco hxco--cart">
<div id="cos-cart-pg">
	<div class="hx-wrap hxco__wrap">

		<header class="hxco-head">
			<h1 class="hx-heading hxco-head__title">Your ritual bag</h1>
			<?php if ( ! $is_empty ) : ?>
			<p class="hx-detail hxco-head__meta"><?php echo esc_html( sprintf( _n( '%d item', '%d items', $item_count, 'cos' ), $item_count ) ); ?></p>
			<?php endif; ?>
		</header>

		<div class="hxco-notices" role="status">
			<?php if ( function_exists( 'wc_print_notices' ) ) wc_print_notices(); ?>
		</div>

		<?php if ( $is_empty ) : ?>

		<section class="hxco-empty" aria-labelledby="hxco-empty-title">
			<h2 id="hxco-empty-title" class="hx-title hxco-empty__title">Your bag is empty.</h2>
			<p class="hx-body hxco-empty__text">Four formulas, made in Zug, Switzerland. Begin with one step, or the complete ritual.</p>
			<div class="hxco-empty__actions">
				<a href="/shop/" class="hx-btn cos-cp-empty-btn" data-cro-cta="cart-continue-shopping">Discover the collection<span class="hx-arrow" aria-hidden="true"></span></a>
				<a href="/products/the-complete-ritual/" class="hx-link">The Complete Ritual<span class="hx-arrow" aria-hidden="true"></span></a>
			</div>
			<?php if ( $cos_steps ) : ?>
			<ol class="hxco-empty__row">
				<?php foreach ( $cos_steps as $hp ) :
					$hp_post = get_page_by_path( $hp['slug'], OBJECT, 'product' );
					$hp_wc   = $hp_post ? wc_get_product( $hp_post ) : null;
					if ( ! $hp_wc ) continue;
					?>
				<li>
					<a class="hxco-empty__item" href="<?php echo esc_url( $hp['url'] ); ?>">
						<span class="hxco-thumb hxco-thumb--empty"><?php echo cos_hxco_line_image( $hp_wc, '(min-width: 900px) 180px, 40vw', $hp['cardalt'] ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
						<span class="hxco-empty__step"><span class="hx-step"><?php echo esc_html( $hp['num'] ); ?></span> <?php echo esc_html( $hp['verb'] ); ?></span>
						<span class="hxco-empty__name"><?php echo esc_html( $hp['name'][0] . ' ' . $hp['name'][1] ); ?></span>
						<?php if ( $hp['price'] ) : ?><span class="hxco-empty__price"><?php echo esc_html( $hp['price'] ); ?></span><?php endif; ?>
					</a>
				</li>
				<?php endforeach; ?>
			</ol>
			<?php endif; ?>
		</section>

		<?php else : ?>

		<div class="hxco-grid">

			<section class="hxco-lines" aria-label="Items in your bag">
				<ul class="hxco-lines__list cos-cp-items">
					<?php foreach ( $cart_items as $cart_key => $item ) :
						$product = $item['data'];
						if ( ! $product ) continue;
						$sku        = $product->get_sku();
						$slug       = $product->get_slug();
						$hp         = $cos_steps[ $slug ] ?? null;
						$name_id    = 'hxco-name-' . sanitize_html_class( $cart_key );
						$line_price = WC()->cart->get_product_subtotal( $product, $item['quantity'] );
						$remove_url = wc_get_cart_remove_url( $cart_key );
						$permalink  = $product->is_visible() ? $product->get_permalink( $item ) : '';
						if ( $hp ) {
							$step_txt = $hp['num'] . ' · ' . $hp['verb'];
							$meta_txt = $hp['size'];
						} elseif ( 'COS-BUNDLE' === $sku ) {
							$step_txt = 'The complete set';
							$meta_txt = 'All four formulas';
						} else {
							$step_txt = '';
							$meta_txt = '';
						}
						?>
					<li class="hxco-line cos-cp-item">
						<?php if ( $permalink ) : ?><a class="hxco-thumb hxco-line__thumb cos-cp-item-img" href="<?php echo esc_url( $permalink ); ?>" tabindex="-1" aria-hidden="true"><?php else : ?><span class="hxco-thumb hxco-line__thumb cos-cp-item-img"><?php endif; ?>
							<?php echo cos_hxco_line_image( $product, '(min-width: 600px) 136px, 96px', '' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
						<?php echo $permalink ? '</a>' : '</span>'; ?>

						<div class="hxco-line__body">
							<?php if ( $step_txt ) : ?><p class="hx-label hxco-line__step"><?php echo esc_html( $step_txt ); ?></p><?php endif; ?>
							<h2 class="hx-title hxco-line__name cos-cp-item-name" id="<?php echo esc_attr( $name_id ); ?>">
								<?php if ( $permalink ) : ?><a href="<?php echo esc_url( $permalink ); ?>"><?php echo esc_html( $product->get_name() ); ?></a><?php else : echo esc_html( $product->get_name() ); endif; ?>
							</h2>
							<p class="hx-detail hxco-line__meta"><?php echo esc_html( $meta_txt ); ?><?php if ( $meta_txt && $item['quantity'] > 1 ) : ?><span aria-hidden="true"> · </span><?php endif; ?><?php if ( $item['quantity'] > 1 ) : ?><?php echo wp_kses_post( wc_price( $product->get_price() ) ); ?> each<?php endif; ?></p>

							<div class="hxco-line__actions">
								<div class="hxco-qty cos-cp-qty-wrap" role="group" aria-label="<?php echo esc_attr( 'Quantity, ' . $product->get_name() ); ?>">
									<form method="post" action="<?php echo esc_url( $cos_cart_url ); ?>" class="hxco-qty__form">
										<?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>
										<input type="hidden" name="cart[<?php echo esc_attr( $cart_key ); ?>][qty]" value="<?php echo esc_attr( max( 0, $item['quantity'] - 1 ) ); ?>"/>
										<button type="submit" name="update_cart" value="1" class="hxco-qty__btn cos-cp-qty-btn" aria-label="Decrease quantity" aria-describedby="<?php echo esc_attr( $name_id ); ?>"><span aria-hidden="true" class="hxco-qty__minus"></span></button>
									</form>
									<span class="hxco-qty__val cos-cp-qty-val" aria-live="polite"><?php echo esc_html( $item['quantity'] ); ?></span>
									<form method="post" action="<?php echo esc_url( $cos_cart_url ); ?>" class="hxco-qty__form">
										<?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>
										<input type="hidden" name="cart[<?php echo esc_attr( $cart_key ); ?>][qty]" value="<?php echo esc_attr( $item['quantity'] + 1 ); ?>"/>
										<button type="submit" name="update_cart" value="1" class="hxco-qty__btn cos-cp-qty-btn" aria-label="Increase quantity" aria-describedby="<?php echo esc_attr( $name_id ); ?>"><span aria-hidden="true" class="hxco-qty__plus"></span></button>
									</form>
								</div>
								<a href="<?php echo esc_url( $remove_url ); ?>" class="hxco-remove cos-cp-remove" aria-describedby="<?php echo esc_attr( $name_id ); ?>">Remove</a>
							</div>
						</div>

						<p class="hxco-line__price"><?php echo wp_kses_post( $line_price ); ?></p>
					</li>
					<?php endforeach; ?>
				</ul>
				<a href="/shop/" class="hx-link hxco-lines__continue cos-cp-continue" data-cro-cta="cart-continue-shopping">Continue shopping<span class="hx-arrow" aria-hidden="true"></span></a>
			</section>

			<aside class="hxco-summary" aria-labelledby="hxco-summary-title">
				<h2 id="hxco-summary-title" class="hx-label hxco-summary__title">Summary</h2>

				<dl class="hxco-rows cos-cp-rows">
					<div class="hxco-row">
						<dt>Subtotal</dt>
						<dd><?php echo wp_kses_post( wc_price( $subtotal ) ); ?></dd>
					</div>
					<?php foreach ( $cos_coupons as $code => $coupon ) :
						$disc = WC()->cart->get_coupon_discount_amount( $code, $cos_ex_tax );
						?>
					<div class="hxco-row hxco-row--discount">
						<dt>Code <span class="hxco-code"><?php echo esc_html( strtoupper( $code ) ); ?></span>
							<a class="hxco-row__remove" href="<?php echo esc_url( add_query_arg( 'remove_coupon', rawurlencode( $code ), $cos_cart_url ) ); ?>" aria-label="<?php echo esc_attr( 'Remove code ' . strtoupper( $code ) ); ?>">Remove</a></dt>
						<dd>&minus;<?php echo wp_kses_post( wc_price( $disc ) ); ?></dd>
					</div>
					<?php endforeach; ?>
					<div class="hxco-row">
						<dt>Delivery</dt>
						<dd><?php echo esc_html( cos_child_shipping_message( 'short' ) ); ?></dd>
					</div>
				</dl>

				<div class="hxco-total cos-cp-row-total">
					<span class="hxco-total__label">Total</span>
					<span class="hxco-total__value"><?php echo wp_kses_post( wc_price( $total ) ); ?></span>
				</div>
				<?php if ( $has_bundle && $bundle_saving > 0 ) : ?>
				<p class="hxco-note hxco-note--saving cos-cp-row-saving">Includes your Complete Ritual saving of <?php echo wp_kses_post( wc_price( $bundle_saving ) ); ?> on the four formulas bought separately.</p>
				<?php endif; ?>

				<details class="hxco-coupon"<?php echo ( $cos_coupon_error || $cos_coupon_value ) ? ' open' : ''; ?>>
					<summary class="hxco-coupon__toggle">Add a promotion code</summary>
					<form method="post" action="<?php echo esc_url( $cos_cart_url ); ?>" class="hxco-coupon__form">
						<?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>
						<label class="hxco-field__label" for="hxco-coupon-code">Promotion code</label>
						<div class="hxco-coupon__row">
							<input class="hxco-input" type="text" id="hxco-coupon-code" name="coupon_code" value="<?php echo esc_attr( $cos_coupon_value ); ?>" autocomplete="off" autocapitalize="characters" spellcheck="false" enterkeyhint="go"<?php echo $cos_coupon_error ? ' aria-invalid="true" aria-describedby="hxco-coupon-error"' : ''; ?> required>
							<button type="submit" class="hxco-btn-quiet" name="apply_coupon" value="1">Apply</button>
						</div>
						<?php if ( $cos_coupon_error ) : ?><p class="hxco-field__error" id="hxco-coupon-error" role="alert"><?php echo esc_html( implode( ' ', $cos_coupon_msgs ) ); ?></p><?php endif; ?>
					</form>
				</details>

				<a href="<?php echo esc_url( $checkout_url ); ?>" class="hx-btn hx-btn--block hxco-summary__cta cos-cp-checkout-btn" data-cro-cta="cart-checkout">Proceed to checkout<span class="hx-arrow" aria-hidden="true"></span></a>
				<p class="hxco-note cos-cp-delivery-note">Delivery estimate shown at checkout, based on your address.</p>

				<ul class="hxco-assure cos-cp-trust">
					<li class="cos-cp-trust-item"><?php echo esc_html( cos_child_shipping_message( 'short' ) ); ?></li>
					<li><a href="/shipping-returns/" class="cos-cp-trust-item" data-cro-cta="cart-returns-link">30-day returns</a></li>
					<li class="cos-cp-trust-item"><?php echo esc_html( $cos_cart_payment_label ); ?></li>
					<li class="cos-cp-trust-item">Made in Switzerland</li>
				</ul>
			</aside>

		</div>

		<?php endif; ?>

	</div>
</div>
</main>

<?php
$cos_cart_items = array();
if ( ! $is_empty ) {
	foreach ( $cart_items as $cart_item ) {
		$cp = $cart_item['data'];
		if ( ! $cp ) continue;
		$cos_cart_items[] = array(
			'item_id'   => $cp->get_sku() ?: (string) $cp->get_id(),
			'item_name' => $cp->get_name(),
			'price'     => (float) wc_get_price_to_display( $cp ),
			'quantity'  => (int) $cart_item['quantity'],
		);
	}
}
?>
<script nonce="<?php echo esc_attr( cos_csp_nonce() ); ?>">
document.addEventListener('DOMContentLoaded', function () {
  var items = <?php echo wp_json_encode( $cos_cart_items ); ?>;
  if (window.cos && cos.track && items.length) {
    cos.track('view_cart', { currency: 'EUR', value: <?php echo wp_json_encode( (float) $total ); ?>, items: items });
  }
});
</script>

<?php wp_footer(); ?>
</body>
</html>
