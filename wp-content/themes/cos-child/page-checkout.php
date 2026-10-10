<?php
/**
 * Template Name: COS Checkout
 */
defined( 'ABSPATH' ) || exit;

if ( WC()->cart->is_empty() && ! is_wc_endpoint_url( 'order-received' ) ) {
	wp_safe_redirect( wc_get_cart_url() );
	exit;
}
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<?php cos_child_custom_document_head( 'Checkout - Caviar of Switzerland' ); ?>
<?php wp_head(); ?>
</head>
<body <?php body_class( 'woocommerce woocommerce-checkout woocommerce-page' ); ?>>
<?php wp_body_open(); ?>
<style nonce="<?php echo esc_attr( cos_csp_nonce() ); ?>">
#cos-checkout-pg{font-family:var(--cos-body);color:var(--cos-ink,#111111);background:var(--cos-pearl,#F2F2EF);min-height:70vh}
#cos-checkout-pg *{box-sizing:border-box}
.cos-co-spacer{height:var(--cos-nav-h,80px)}
.cos-co-head{max-width:1100px;margin:0 auto;padding:48px 24px 0}
.cos-co-eyebrow{font-size:9px;font-weight:600;letter-spacing:.3em;text-transform:uppercase;color:var(--cos-gold,#8B692F);margin:0 0 10px}
.cos-co-h1{font-family:var(--cos-display);font-style:italic;font-weight:300;font-size:clamp(26px,3vw,40px);color:var(--cos-ink);margin:0 0 8px}
.cos-co-sub{font-size:13px;font-weight:300;color:var(--cos-slate,#5C6675);margin:0}
.cos-co-wrap{max-width:1100px;margin:0 auto;padding:32px 24px 64px}

/* ── Restyle the native WooCommerce checkout to match the site ── */
/* style.css's sitewide `.woocommerce-checkout .woocommerce` rule gives the
   shortcode's own wrapper div a second max-width/margin/padding (and a
   second nav-height top margin) — .cos-co-wrap above already establishes
   both, so the two were stacking into doubled gutters (worse on mobile,
   where style.css also narrows its copy to 16px on top of this one's
   24px). #cos-checkout-pg's ID gives this more specificity, so it wins
   without needing !important. */
#cos-checkout-pg .woocommerce{max-width:none;margin:0;padding:0}
#cos-checkout-pg .woocommerce-notices-wrapper{margin-bottom:20px}
#cos-checkout-pg .woocommerce-error,#cos-checkout-pg .woocommerce-message,#cos-checkout-pg .woocommerce-info{background:#fff;border:1px solid var(--cos-stone,#E5DED4);border-left:3px solid var(--cos-gold,#8B692F);padding:16px 20px;font-size:13px;font-weight:300;color:var(--cos-ink);list-style:none;margin:0 0 24px}
#cos-checkout-pg form.checkout{display:grid;grid-template-columns:1fr 380px;gap:48px;align-items:start;min-width:0}
/* Grid items default to min-width:auto, sizing to their content's intrinsic
   minimum — a table cell or long line inside #order_review/#customer_details
   can otherwise force its column (and the whole form) wider than the page,
   overflowing #cos-checkout-pg horizontally. */
#cos-checkout-pg form.checkout > #customer_details,
#cos-checkout-pg form.checkout > #order_review{min-width:0}
@media(max-width:900px){#cos-checkout-pg form.checkout{grid-template-columns:1fr}}
@media(max-width:480px){
  .cos-co-head{padding-left:16px;padding-right:16px}
  .cos-co-wrap{padding-left:16px;padding-right:16px}
  #cos-checkout-pg #customer_details{padding:18px}
  #cos-checkout-pg #order_review{padding:18px}
}
#cos-checkout-pg h3{font-family:var(--cos-display);font-style:italic;font-weight:300;font-size:20px;color:var(--cos-ink);margin:0 0 20px}
#cos-checkout-pg #customer_details{background:#fff;border:1px solid var(--cos-stone,#E5DED4);padding:28px}
#cos-checkout-pg .col-2{margin-top:32px}
#cos-checkout-pg .form-row{margin:0 0 16px}
#cos-checkout-pg .form-row label{display:block;font-size:9px;font-weight:600;letter-spacing:.16em;text-transform:uppercase;color:var(--cos-slate,#5C6675);margin-bottom:6px}
#cos-checkout-pg .form-row .required{color:var(--cos-gold,#8B692F);text-decoration:none}
#cos-checkout-pg input.input-text,#cos-checkout-pg select,#cos-checkout-pg textarea{width:100%;min-height:44px;box-sizing:border-box;padding:12px 14px;border:1.5px solid var(--cos-stone,#E5DED4);background:#fff;font-family:var(--cos-body);font-size:13px;color:var(--cos-ink);outline:none;border-radius:0;transition:border-color .2s}
#cos-checkout-pg input.input-text:focus,#cos-checkout-pg select:focus,#cos-checkout-pg textarea:focus{border-color:var(--cos-navy,#050505)}
#cos-checkout-pg .select2-container .select2-selection--single{height:44px;border:1.5px solid var(--cos-stone,#E5DED4);border-radius:0;padding:6px 14px}
#cos-checkout-pg .woocommerce-form__label-for-checkbox{display:flex;align-items:flex-start;gap:8px;font-size:12px;font-weight:300;color:var(--cos-slate);margin:16px 0}
#cos-checkout-pg #order_review_heading{display:none}
#cos-checkout-pg #order_review{background:#fff;border:1px solid var(--cos-stone,#E5DED4);padding:28px;position:sticky;top:calc(var(--cos-nav-h,80px) + 20px)}
#cos-checkout-pg table.shop_table{width:100%;border:none;margin:0 0 20px}
#cos-checkout-pg table.shop_table th,#cos-checkout-pg table.shop_table td{border:none;border-bottom:1px solid var(--cos-stone,#E5DED4);padding:10px 0;font-size:13px;font-weight:300;color:var(--cos-slate)}
#cos-checkout-pg table.shop_table .product-name{color:var(--cos-ink)}
#cos-checkout-pg table.shop_table .order-total th,#cos-checkout-pg table.shop_table .order-total td{font-size:15px;font-weight:600;color:var(--cos-ink);border-bottom:none;border-top:1px solid var(--cos-stone)}
#cos-checkout-pg #payment{background:var(--cos-pearl,#F2F2EF);border-top:1px solid var(--cos-stone,#E5DED4);padding-top:20px}
#cos-checkout-pg ul.payment_methods{list-style:none;margin:0 0 16px;padding:0;display:flex;flex-direction:column;gap:10px}
#cos-checkout-pg ul.payment_methods li{background:#fff;border:1px solid var(--cos-stone,#E5DED4);padding:14px 16px}
#cos-checkout-pg ul.payment_methods label{font-size:12px;font-weight:500;color:var(--cos-ink)}
#cos-checkout-pg .payment_box{font-size:12px;font-weight:300;color:var(--cos-slate);line-height:1.7;padding-top:10px}
#cos-checkout-pg #place_order{display:block;width:100%;padding:17px;background:var(--cos-navy,#050505);color:#fff;font-family:var(--cos-body);font-size:9px;font-weight:600;letter-spacing:.22em;text-transform:uppercase;border:none;cursor:pointer;transition:background .2s}
#cos-checkout-pg #place_order:hover{background:var(--cos-ink,#111111)}
#cos-checkout-pg .woocommerce-terms-and-conditions-wrapper{font-size:11px;font-weight:300;color:var(--cos-slate);margin:16px 0}
#cos-checkout-pg .woocommerce-terms-and-conditions-wrapper a{color:var(--cos-ink)}
#cos-checkout-pg .blockUI.blockOverlay{position:relative}
#cos-checkout-pg .woocommerce-shipping-methods{list-style:none;margin:0;padding:0}

/* ── Coupon (collapsed by default, WooCommerce's own accessible toggle) ── */
#cos-checkout-pg .woocommerce-form-coupon-toggle{margin:0 0 24px}
#cos-checkout-pg .wc-block-components-notice-banner{display:flex;align-items:center;gap:10px;background:#fff;border:1px solid var(--cos-stone,#E5DED4);padding:14px 18px;font-size:12px;font-weight:300;color:var(--cos-slate,#5C6675)}
#cos-checkout-pg .wc-block-components-notice-banner svg{width:16px;height:16px;flex-shrink:0;fill:var(--cos-gold,#8B692F)}
#cos-checkout-pg .showcoupon{color:var(--cos-ink);font-weight:600;text-decoration:underline;text-underline-offset:2px}
#cos-checkout-pg .checkout_coupon.woocommerce-form-coupon{display:flex;gap:10px;margin:0 0 24px;padding:16px;background:#fff;border:1px solid var(--cos-stone,#E5DED4);flex-wrap:wrap}
#cos-checkout-pg .checkout_coupon .form-row{margin:0;flex:1;min-width:180px}
#cos-checkout-pg .checkout_coupon .form-row-last{flex:none}
#cos-checkout-pg .checkout_coupon button{padding:0 24px;min-height:44px;background:var(--cos-navy,#050505);color:#fff;font-family:var(--cos-body);font-size:9px;font-weight:600;letter-spacing:.16em;text-transform:uppercase;border:none;cursor:pointer;transition:background .2s}
#cos-checkout-pg .checkout_coupon button:hover{background:var(--cos-ink,#111111)}

/* ── Reassurance line under Place Order, optional-field toggles ── */
#cos-checkout-pg .cos-co-reassurance{font-size:10px;font-weight:300;color:var(--cos-slate,#5C6675);text-align:center;margin:14px 0 0}
#cos-checkout-pg .cos-co-reassurance a{color:inherit;text-decoration:underline;text-underline-offset:2px}
#cos-checkout-pg .cos-co-field-toggle{display:block;background:none;border:none;padding:6px 0;margin:0 0 16px;font-size:11px;font-weight:600;letter-spacing:.02em;color:var(--cos-slate,#5C6675);text-decoration:underline;text-underline-offset:2px;cursor:pointer;text-align:left;font-family:var(--cos-body)}
#cos-checkout-pg .cos-co-field-toggle:hover{color:var(--cos-navy,#050505)}
/* !important: WooCommerce server-renders these fields with an inline
   style="display:block" and its own checkout.js re-applies inline
   display via jQuery .show() during address-field locale handling — a
   plain inline style toggle from our own script gets silently clobbered
   by whichever of those runs last. An !important class rule beats any
   non-important inline style regardless of write order, so this is the
   only reliably deterministic way to keep the field collapsed. */
#cos-checkout-pg .cos-co-collapsed{display:none !important}
</style>

<main id="cos-main-content">
<div id="cos-checkout-pg">
  <div class="cos-co-spacer"></div>
  <div class="cos-co-head">
    <p class="cos-co-eyebrow">Caviar of Switzerland Secure Checkout</p>
    <h1 class="cos-co-h1">Complete your ritual.</h1>
    <p class="cos-co-sub">Encrypted checkout &middot; Made in Switzerland &middot; 30-day comfort guarantee</p>
  </div>
  <div class="cos-co-wrap">
    <?php echo do_shortcode( '[woocommerce_checkout]' ); ?>
  </div>
</div>
</main>

<?php if ( ! is_wc_endpoint_url( 'order-received' ) ) : ?>
<script nonce="<?php echo esc_attr( cos_csp_nonce() ); ?>">
document.addEventListener('DOMContentLoaded', function () {
  // Collapse the two optional fields behind a quiet toggle — progressive
  // enhancement only: with no JS every field stays visible and fully
  // functional (same principle as the shop concern-selector). A field that
  // already carries a value (browser autofill, back-navigation, a retry
  // after a validation error) is left open rather than hidden, so nothing
  // the shopper already entered ever disappears from view.
  function collapseOptionalField(fieldId, label) {
    var field = document.getElementById(fieldId);
    if (!field) return;
    var input = field.querySelector('input, textarea');
    if (!input || input.value) return;
    field.classList.add('cos-co-collapsed');
    var toggle = document.createElement('button');
    toggle.type = 'button';
    toggle.className = 'cos-co-field-toggle';
    toggle.setAttribute('aria-expanded', 'false');
    toggle.setAttribute('aria-controls', fieldId);
    toggle.textContent = label;
    toggle.addEventListener('click', function () {
      field.classList.remove('cos-co-collapsed');
      toggle.hidden = true;
      toggle.setAttribute('aria-expanded', 'true');
      input.focus();
    });
    field.parentNode.insertBefore(toggle, field);
  }
  collapseOptionalField('billing_address_2_field', '+ Add apartment, suite, or unit');
  collapseOptionalField('shipping_address_2_field', '+ Add apartment, suite, or unit');
  collapseOptionalField('order_comments_field', '+ Add delivery notes');

  if (window.jQuery) {
    jQuery(document.body).on('checkout_error', function (e, errorMessage) {
      if (window.cos && cos.track) {
        var text = jQuery('<div>').html(errorMessage).text().trim();
        cos.track('checkout_error', { error_message: text.slice(0, 200) });
      }
      // WooCommerce's own submit_error() already focuses the error notice
      // banner; additionally focus the first specific invalid field inside
      // it (set via aria-invalid by WooCommerce's inline-error rendering),
      // which is more actionable for a keyboard/screen-reader user than the
      // banner alone.
      var firstInvalid = document.querySelector('#customer_details [aria-invalid="true"], #customer_details .woocommerce-invalid input, #customer_details .woocommerce-invalid select');
      if (firstInvalid) { firstInvalid.focus(); }
    });
  }
});
</script>
<?php endif; ?>

<?php
if ( is_wc_endpoint_url( 'order-received' ) ) {
	// purchase — the source of truth is the WC_Order itself, fetched fresh
	// here, never the localStorage cart. The _cos_purchase_tracked meta flag
	// is deliberately NOT set here: setting it at render time would mark the
	// order as tracked even if the visitor's browser never actually pushes
	// the event (JS disabled, ad blocker, closed tab). Instead the flag is
	// only written by cos_child_confirm_purchase_tracked() (inc/analytics.php),
	// called via AJAX after cos.track() has run client-side — see that
	// function for the order-key-verified confirm step. A sessionStorage
	// guard (below) additionally prevents a rapid refresh from re-firing the
	// push client-side before that confirm round-trip lands.
	$order_id     = absint( get_query_var( 'order-received' ) );
	$order        = $order_id ? wc_get_order( $order_id ) : false;
	$order_key    = $order ? $order->get_order_key() : '';
	$order_key_ok = $order && isset( $_GET['key'] ) && hash_equals( $order_key, wc_clean( wp_unslash( $_GET['key'] ) ) );

	if ( $order && $order_key_ok && ! $order->get_meta( '_cos_purchase_tracked' ) ) {
		$purchase_items = array();
		foreach ( $order->get_items() as $order_item ) {
			$op               = $order_item->get_product();
			$purchase_items[] = array(
				'item_id'   => $op ? ( $op->get_sku() ?: (string) $op->get_id() ) : (string) $order_item->get_product_id(),
				'item_name' => $order_item->get_name(),
				'price'     => (float) $order->get_item_total( $order_item, false, false ),
				'quantity'  => (int) $order_item->get_quantity(),
			);
		}
		$cos_purchase = array(
			'transaction_id' => $order->get_order_number(),
			'value'          => (float) $order->get_total(),
			'tax'            => (float) $order->get_total_tax(),
			'shipping'       => (float) $order->get_shipping_total(),
			'currency'       => $order->get_currency(),
			'coupon'         => implode( ', ', $order->get_coupon_codes() ),
			'items'          => $purchase_items,
		);
		$confirm_nonce = wp_create_nonce( 'cos_confirm_purchase_' . $order_id );
		?>
<script nonce="<?php echo esc_attr( cos_csp_nonce() ); ?>">
document.addEventListener('DOMContentLoaded', function () {
  if (!(window.cos && cos.track)) return;
  var orderId = <?php echo wp_json_encode( $order_id ); ?>;
  var sessionKey = 'cos_purchase_pushed_' + orderId;
  if (sessionStorage.getItem(sessionKey)) return;
  cos.track('purchase', <?php echo wp_json_encode( $cos_purchase ); ?>);
  sessionStorage.setItem(sessionKey, '1');
  var data = new FormData();
  data.append('action', 'cos_confirm_purchase_tracked');
  data.append('order_id', orderId);
  data.append('order_key', <?php echo wp_json_encode( $order_key ); ?>);
  data.append('nonce', <?php echo wp_json_encode( $confirm_nonce ); ?>);
  fetch('/wp-admin/admin-ajax.php', { method: 'POST', body: data, credentials: 'same-origin' }).catch(function(){});
});
</script>
		<?php
	}
} elseif ( ! WC()->cart->is_empty() ) {
	// begin_checkout / add_shipping_info / add_payment_info — cart contents
	// come from WC()->cart, and shipping/payment selections come from
	// WooCommerce's own checkout DOM state after its real AJAX recalculation
	// (updated_checkout), not assumed from a click.
	$cos_checkout_items = array();
	foreach ( WC()->cart->get_cart() as $cart_item ) {
		$cp = $cart_item['data'];
		if ( ! $cp ) continue;
		$cos_checkout_items[] = array(
			'item_id'   => $cp->get_sku() ?: (string) $cp->get_id(),
			'item_name' => $cp->get_name(),
			'price'     => (float) wc_get_price_to_display( $cp ),
			'quantity'  => (int) $cart_item['quantity'],
		);
	}
	// Detection for the two CRO events below — country code only, never any
	// entered customer data. Both are structurally rare right now: shipping
	// is only "unavailable" if a destination needs shipping but has no rate
	// (essentially unreachable while checkout only offers Switzerland, which
	// always has the free_shipping method — see inc/shipping.php), and
	// payment is only "unavailable" if cos_child_enabled_payment_gateways()
	// is empty for a destination that does have a shipping rate. Wired now
	// so both fire correctly the moment either condition becomes reachable
	// (e.g. shipping expands to a country with a gap in its rates).
	$cos_shipping_unavailable = false;
	if ( WC()->cart->needs_shipping() ) {
		$cos_shipping_unavailable = true;
		foreach ( WC()->shipping()->get_packages() as $package ) {
			if ( ! empty( $package['rates'] ) ) { $cos_shipping_unavailable = false; break; }
		}
	}
	$cos_payment_unavailable = empty( cos_child_enabled_payment_gateways() );
	$cos_checkout_country    = WC()->customer ? WC()->customer->get_billing_country() : '';
	?>
<script nonce="<?php echo esc_attr( cos_csp_nonce() ); ?>">
document.addEventListener('DOMContentLoaded', function () {
  if (window.cos && cos.track) {
    cos.track('begin_checkout', { currency: 'EUR', value: <?php echo wp_json_encode( (float) WC()->cart->get_total( 'edit' ) ); ?>, items: <?php echo wp_json_encode( $cos_checkout_items ); ?> });
    <?php if ( $cos_shipping_unavailable ) : ?>
    cos.track('shipping_unavailable', { country: <?php echo wp_json_encode( $cos_checkout_country ); ?> });
    <?php endif; ?>
    <?php if ( $cos_payment_unavailable ) : ?>
    cos.track('payment_unavailable', { country: <?php echo wp_json_encode( $cos_checkout_country ); ?> });
    <?php endif; ?>
  }
  if (window.jQuery) {
    var lastShipping = null;
    jQuery(document.body).on('updated_checkout', function () {
      var checked = document.querySelector('.woocommerce-shipping-methods input[type=radio]:checked, .woocommerce-shipping-methods input[type=hidden]');
      var label = checked ? (checked.closest('li') ? checked.closest('li').textContent.trim() : checked.value) : null;
      if (label && label !== lastShipping && window.cos && cos.track) {
        lastShipping = label;
        cos.track('add_shipping_info', { currency: 'EUR', shipping_tier: label });
      }
    });
    jQuery(document.body).on('change', 'input[name=payment_method]', function () {
      if (window.cos && cos.track) { cos.track('add_payment_info', { currency: 'EUR', payment_type: this.value }); }
      // Keep the place-order button text matched to the selected gateway —
      // server-rendered on load (cos_child_dynamic_order_button_text() in
      // inc/commerce.php), updated live here so switching gateways doesn't
      // leave a stale "pay securely" label on a Cash on Delivery order or
      // vice versa. Reads the total from the order-review table itself
      // (not a page-load snapshot) so it stays correct after a coupon or
      // shipping-method AJAX refresh changes the total.
      var btn = document.getElementById('place_order');
      if (!btn) return;
      var text;
      if (this.value === 'cod') {
        text = 'Place order — pay on delivery';
      } else {
        var totalEl = document.querySelector('.order-total .woocommerce-Price-amount');
        text = totalEl ? ('Pay ' + totalEl.textContent.trim() + ' securely') : 'Pay securely';
      }
      btn.textContent = text;
      btn.setAttribute('value', text);
    });
  }
});
</script>
	<?php
}
?>

<?php wp_footer(); ?>
</body>
</html>
