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
<?php $cos_is_received = is_wc_endpoint_url( 'order-received' ); ?>
<main id="cos-main-content" class="hxco hxco--checkout<?php echo $cos_is_received ? ' hxco--received' : ''; ?>">
<div id="cos-checkout-pg">
	<div class="hx-wrap hxco__wrap">
		<?php if ( $cos_is_received ) : ?>
		<header class="hxco-head hxco-head--received">
			<h1 class="hx-heading hxco-head__title">Your order</h1>
		</header>
		<?php else : ?>
		<header class="hxco-head">
			<h1 class="hx-heading hxco-head__title">Checkout</h1>
			<p class="hx-detail hxco-head__meta"><?php echo esc_html( cos_child_shipping_message( 'short' ) ); ?><span aria-hidden="true"> · </span>30-day returns<span aria-hidden="true"> · </span>Made in Switzerland</p>
			<a class="hx-link hxco-head__back" href="<?php echo esc_url( wc_get_cart_url() ); ?>">Return to bag</a>
		</header>
		<?php endif; ?>
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
    if (!field || field.querySelector('.cos-co-field-toggle')) return;
    var input = field.querySelector('input, textarea');
    if (!input || input.value) return;
    // The toggle lives INSIDE the field row (.form-row): WooCommerce's address-i18n script
    // re-sorts the rows by priority after load, which used to strand a sibling toggle at
    // the top of the form. Only the row's label + input are hidden (checkout.css).
    field.classList.add('cos-co-collapsed');
    var toggle = document.createElement('button');
    toggle.type = 'button';
    toggle.className = 'cos-co-field-toggle hxco-field-toggle';
    toggle.setAttribute('aria-expanded', 'false');
    toggle.setAttribute('aria-controls', fieldId);
    toggle.textContent = label;
    toggle.addEventListener('click', function () {
      field.classList.remove('cos-co-collapsed');
      toggle.hidden = true;
      toggle.setAttribute('aria-expanded', 'true');
      input.focus();
    });
    field.insertBefore(toggle, field.firstChild);
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
