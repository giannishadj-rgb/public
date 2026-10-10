<?php
/**
 * Template Name: COS Cart
 */
defined( 'ABSPATH' ) || exit;

WC()->cart->calculate_totals();
$cart_items   = WC()->cart->get_cart();
$subtotal     = WC()->cart->get_subtotal();
$total        = WC()->cart->get_total( 'edit' );
$checkout_url = wc_get_checkout_url();
$is_empty     = WC()->cart->is_empty();

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

$step_map = array(
	'COS-001'    => 'Step 01',
	'COS-002'    => 'Step 02',
	'COS-003'    => 'Step 03',
	'COS-004'    => 'Step 04',
	'COS-BUNDLE' => 'Complete Set',
);
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<?php cos_child_custom_document_head( 'Cart - Caviar of Switzerland' ); ?>
<?php wp_head(); ?>
</head>
<body <?php body_class( 'woocommerce woocommerce-page' ); ?>>
<?php wp_body_open(); ?>
<style nonce="<?php echo esc_attr( cos_csp_nonce() ); ?>">
#cos-cart-pg{font-family:var(--cos-body);color:var(--cos-ink,#111111);background:var(--cos-pearl,#F2F2EF);min-height:70vh}
#cos-cart-pg *{box-sizing:border-box}
.cos-cp-spacer{height:var(--cos-nav-h,80px)}
.cos-cp-wrap{max-width:1100px;margin:0 auto;padding:48px 24px;display:grid;grid-template-columns:1fr 360px;gap:48px;align-items:start}
.cos-cp-eyebrow{font-size:9px;font-weight:600;letter-spacing:.3em;text-transform:uppercase;color:var(--cos-gold,#8B692F);margin-bottom:10px}
.cos-cp-h1{font-family:var(--cos-display);font-style:italic;font-weight:300;font-size:clamp(26px,3vw,40px);color:var(--cos-ink);margin:0 0 32px}
/* Empty */
.cos-cp-empty{background:#fff;border:1px solid var(--cos-stone,#E5DED4);padding:64px 32px;text-align:center}
.cos-cp-empty p{font-size:14px;font-weight:300;color:var(--cos-slate,#5C6675);margin:0 0 24px;line-height:1.7}
.cos-cp-empty-btn{display:inline-block;padding:14px 32px;background:var(--cos-navy,#050505);color:#fff;font-size:9px;font-weight:600;letter-spacing:.2em;text-transform:uppercase;text-decoration:none;transition:background .2s}
.cos-cp-empty-btn:hover{background:var(--cos-ink,#111111)}
/* Items */
.cos-cp-items{display:flex;flex-direction:column;border:1px solid var(--cos-stone,#E5DED4);background:#fff}
.cos-cp-item{display:grid;grid-template-columns:88px 1fr auto;gap:20px;padding:24px;border-bottom:1px solid var(--cos-stone,#E5DED4);align-items:center}
.cos-cp-item:last-child{border-bottom:none}
.cos-cp-item-img{width:88px;height:110px;object-fit:cover;display:block;background:var(--cos-gallery-bg,#E8E8E5)}
.cos-cp-item-step{font-size:9px;font-weight:600;letter-spacing:.22em;text-transform:uppercase;color:var(--cos-gold,#8B692F);margin-bottom:4px}
.cos-cp-item-name{font-family:var(--cos-display);font-style:italic;font-weight:300;font-size:18px;color:var(--cos-ink);margin:0 0 6px;line-height:1.2}
.cos-cp-item-meta{font-size:11px;font-weight:300;color:var(--cos-slate);margin-bottom:14px}
.cos-cp-qty-wrap{display:flex;align-items:center;border:1px solid var(--cos-stone);width:fit-content}
.cos-cp-qty-form{display:flex;margin:0}
.cos-cp-qty-btn{width:44px;height:44px;display:flex;align-items:center;justify-content:center;background:none;border:none;cursor:pointer;font-size:15px;color:var(--cos-ink);font-family:var(--cos-body);transition:background .15s;text-decoration:none}
.cos-cp-qty-btn:hover{background:var(--cos-stone)}
.cos-cp-qty-val{width:40px;height:32px;text-align:center;border-left:1px solid var(--cos-stone);border-right:1px solid var(--cos-stone);font-size:13px;font-weight:300;color:var(--cos-ink);display:flex;align-items:center;justify-content:center}
.cos-cp-item-right{display:flex;flex-direction:column;align-items:flex-end;gap:12px}
.cos-cp-item-price{font-size:18px;font-weight:300;color:var(--cos-ink);white-space:nowrap}
.cos-cp-remove{font-size:9px;color:var(--cos-slate);background:none;border:none;cursor:pointer;text-decoration:underline;font-family:var(--cos-body);letter-spacing:.05em;text-align:right;display:inline-flex;align-items:center;justify-content:flex-end;min-height:24px;min-width:24px;padding:6px 4px;margin:-6px -4px}
.cos-cp-remove:hover{color:var(--cos-ink)}
/* Sidebar */
.cos-cp-sidebar{background:#fff;border:1px solid var(--cos-stone,#E5DED4);padding:28px;position:sticky;top:calc(var(--cos-nav-h,80px) + 20px)}
.cos-cp-sidebar-title{font-size:9px;font-weight:600;letter-spacing:.24em;text-transform:uppercase;color:var(--cos-ink);margin-bottom:24px;padding-bottom:16px;border-bottom:1px solid var(--cos-stone)}
.cos-cp-rows{display:flex;flex-direction:column;gap:12px;margin-bottom:20px;padding-bottom:20px;border-bottom:1px solid var(--cos-stone)}
.cos-cp-row{display:flex;justify-content:space-between;font-size:13px;font-weight:300;color:var(--cos-slate)}
.cos-cp-row-saving{color:var(--cos-teal,#46656B);font-weight:500}
.cos-cp-row-total{display:flex;justify-content:space-between;font-size:15px;font-weight:600;color:var(--cos-ink);margin-bottom:24px}
.cos-cp-checkout-btn{display:block;width:100%;padding:17px;background:var(--cos-navy,#050505);color:#fff;font-family:var(--cos-body);font-size:9px;font-weight:600;letter-spacing:.22em;text-transform:uppercase;text-decoration:none;text-align:center;border:none;cursor:pointer;transition:background .2s;margin-bottom:10px}
.cos-cp-checkout-btn:hover{background:var(--cos-ink,#111111);color:#fff}
.cos-cp-delivery-note{font-size:10px;font-weight:300;color:var(--cos-slate,#5C6675);text-align:center;margin:0 0 16px}
.cos-cp-continue{display:block;text-align:center;padding:14px;border:1px solid var(--cos-stone);color:var(--cos-slate);font-size:9px;font-weight:600;letter-spacing:.2em;text-transform:uppercase;text-decoration:none;transition:border-color .2s,color .2s}
.cos-cp-continue:hover{border-color:var(--cos-navy);color:var(--cos-navy)}
.cos-cp-trust{margin-top:20px;padding-top:20px;border-top:1px solid var(--cos-stone);display:flex;flex-direction:column;gap:7px}
.cos-cp-trust-item{font-size:11px;font-weight:300;color:var(--cos-slate);display:flex;align-items:center;gap:8px;text-decoration:none}
a.cos-cp-trust-item:hover{color:var(--cos-navy,#050505)}
.cos-cp-trust-dot{width:3px;height:3px;background:var(--cos-gold-l,#B79A5B);flex-shrink:0;display:inline-block}
@media(max-width:900px){
  .cos-cp-wrap{grid-template-columns:1fr}
  .cos-cp-sidebar{position:static}
}
@media(max-width:540px){
  .cos-cp-item{grid-template-columns:72px 1fr}
  /* The grid track narrowed to 72px here but the image itself stayed at
     its 88px desktop width, so it overflowed the column into the product
     details next to it. Match the image to the track (keeping its 4:5
     aspect ratio) instead of widening the column back out, so the 20px
     .cos-cp-item gap above still comfortably clears the 16px minimum
     between image and details. */
  .cos-cp-item-img{width:72px;height:90px}
  .cos-cp-item-right{display:flex;grid-column:2;align-items:flex-start;gap:8px;margin-top:8px}
  .cos-cp-item-price{font-size:14px}
}
</style>

<main id="cos-main-content">
<div id="cos-cart-pg">
  <div class="cos-cp-spacer"></div>
  <h1 class="cos-cp-eyebrow" style="max-width:1100px;margin:0 auto;padding:48px 24px 0">Your Ritual Bag</h1>
  <div class="cos-cp-wrap" style="padding-top:20px">

    <!-- Left: items -->
    <div>

      <?php if ( $is_empty ) : ?>
      <div class="cos-cp-empty">
        <p>Your Caviar of Switzerland ritual bag is empty.</p>
        <a href="/shop/" class="cos-cp-empty-btn">Start the Ritual &rarr;</a>
      </div>

      <?php else : ?>
      <div class="cos-cp-items">
        <?php foreach ( $cart_items as $cart_key => $item ) :
          $product = $item['data'];
          $sku     = $product->get_sku();
          $gal_raw = get_post_meta( $product->get_id(), '_product_image_gallery', true );
          $gal_arr = array_filter( array_map( 'intval', explode( ',', $gal_raw ) ) );
          $img_id  = ! empty( $gal_arr ) ? reset( $gal_arr ) : $product->get_image_id();
          $img_src = $img_id ? wp_get_attachment_image_url( $img_id, 'medium' ) : '';
          $line_price = wc_price( $product->get_price() * $item['quantity'] );
          $remove_url = wc_get_cart_remove_url( $cart_key );
        ?>
        <div class="cos-cp-item">
          <?php if ( $img_src ) : ?>
          <img class="cos-cp-item-img" src="<?php echo esc_url( $img_src ); ?>" alt="<?php echo esc_attr( $product->get_name() ); ?>" loading="lazy"/>
          <?php endif; ?>
          <div>
            <p class="cos-cp-item-step"><?php echo esc_html( $step_map[ $sku ] ?? '' ); ?></p>
            <p class="cos-cp-item-name"><?php echo esc_html( $product->get_name() ); ?></p>
            <p class="cos-cp-item-meta"><?php echo wc_price( $product->get_price() ); ?> each</p>
            <div class="cos-cp-qty-wrap">
              <form method="post" class="cos-cp-qty-form">
                <?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>
                <input type="hidden" name="cart[<?php echo esc_attr( $cart_key ); ?>][qty]" value="<?php echo esc_attr( max( 0, $item['quantity'] - 1 ) ); ?>"/>
                <button type="submit" name="update_cart" value="1" class="cos-cp-qty-btn" aria-label="Decrease quantity">−</button>
              </form>
              <span class="cos-cp-qty-val"><?php echo esc_html( $item['quantity'] ); ?></span>
              <form method="post" class="cos-cp-qty-form">
                <?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>
                <input type="hidden" name="cart[<?php echo esc_attr( $cart_key ); ?>][qty]" value="<?php echo esc_attr( $item['quantity'] + 1 ); ?>"/>
                <button type="submit" name="update_cart" value="1" class="cos-cp-qty-btn" aria-label="Increase quantity">+</button>
              </form>
            </div>
          </div>
          <div class="cos-cp-item-right">
            <span class="cos-cp-item-price"><?php echo $line_price; ?></span>
            <a href="<?php echo esc_url( $remove_url ); ?>" class="cos-cp-remove">Remove</a>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>
    </div>

    <!-- Right: summary -->
    <div class="cos-cp-sidebar">
      <div class="cos-cp-sidebar-title">Order Summary</div>
      <div class="cos-cp-rows">
        <div class="cos-cp-row">
          <span>Subtotal</span>
          <span><?php echo $is_empty ? wc_price( 0 ) : wc_price( $subtotal ); ?></span>
        </div>
        <?php if ( $has_bundle && $bundle_saving > 0 ) : ?>
        <div class="cos-cp-row cos-cp-row-saving">
          <span>Complete Ritual saving</span>
          <span>&minus;<?php echo wp_kses_post( wc_price( $bundle_saving ) ); ?></span>
        </div>
        <?php endif; ?>
        <div class="cos-cp-row">
          <span>Shipping</span>
          <span><?php echo esc_html( cos_child_shipping_message( 'short' ) ); ?></span>
        </div>
      </div>
      <div class="cos-cp-row-total">
        <span>Total</span>
        <span><?php echo $is_empty ? wc_price( 0 ) : wc_price( $total ); ?></span>
      </div>
      <?php if ( ! $is_empty ) : ?>
      <a href="<?php echo esc_url( $checkout_url ); ?>" class="cos-cp-checkout-btn" data-cro-cta="cart-checkout">Proceed to Secure Checkout</a>
      <p class="cos-cp-delivery-note">Delivery estimate shown at checkout, based on your address.</p>
      <?php endif; ?>
      <a href="/shop/" class="cos-cp-continue" data-cro-cta="cart-continue-shopping"><?php echo $is_empty ? 'Start the Ritual' : 'Continue Shopping'; ?></a>
      <div class="cos-cp-trust">
        <div class="cos-cp-trust-item"><span class="cos-cp-trust-dot"></span>Made in Switzerland — every formula</div>
        <div class="cos-cp-trust-item"><span class="cos-cp-trust-dot"></span><?php echo esc_html( cos_child_shipping_message( 'short' ) ); ?></div>
        <a href="/shipping-returns/" class="cos-cp-trust-item" data-cro-cta="cart-returns-link"><span class="cos-cp-trust-dot"></span>30-Day Comfort Guarantee</a>
        <div class="cos-cp-trust-item"><span class="cos-cp-trust-dot"></span><?php echo esc_html( $cos_cart_payment_label ); ?></div>
      </div>
    </div>

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
