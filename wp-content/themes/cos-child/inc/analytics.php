<?php
/**
 * COS Child Theme — Analytics
 *
 * GTM + Consent Mode v2 bootstrap, the GA4 dataLayer/cos.track() bridge, and
 * the full add-to-cart tracking pipeline end to end: the WooCommerce capture
 * hook that populates $GLOBALS['cos_last_added_to_cart'] lives here too (not
 * in inc/commerce.php) because analytics is its only consumer.
 */

/* ─── Google Tag Manager + Consent Mode v2 ──────────────────────── */
// Consent Mode v2 default (all denied) must be set BEFORE the GTM container
// loads, so every tag inside it respects it from the very first pageview —
// this default is printed on every environment (harmless with no GTM present,
// and it's what Complianz's own Consent Mode integration would do too).
//
// The GTM container itself only loads when BOTH the environment identifies as
// production AND an ID is actually configured — see cos_child_gtm_id(). This
// keeps local/staging from ever firing production analytics by default; the
// container id is never hardcoded here.
//
// Complianz (installed, wizard completed — see OWNER-ACTIONS.md for the
// remaining "Safe Mode" admin step needed before its banner renders live)
// owns the actual consent banner UI. cos_child_complianz_consent_bridge()
// below listens for Complianz's own cmplz_status_change event and forwards
// the visitor's category choices into gtag('consent','update', ...) — no
// second, competing consent system is built here.
function cos_child_gtm_id(): string {
	$id = defined( 'COS_GTM_ID' ) ? COS_GTM_ID : get_option( 'cos_gtm_id', '' );
	return (string) apply_filters( 'cos_gtm_id', $id );
}

function cos_child_gtm_should_load(): bool {
	return apply_filters(
		'cos_gtm_should_load',
		'production' === wp_get_environment_type() && '' !== cos_child_gtm_id()
	);
}

function cos_child_gtm_consent_bootstrap(): void {
	if ( is_admin() ) return;
	?>
<script nonce="<?php echo esc_attr( cos_csp_nonce() ); ?>">
window.dataLayer=window.dataLayer||[];
function gtag(){dataLayer.push(arguments);}
gtag('consent','default',{'ad_storage':'denied','ad_user_data':'denied','ad_personalization':'denied','analytics_storage':'denied','wait_for_update':500});
</script>
	<?php
	if ( ! cos_child_gtm_should_load() ) return;
	$gtm_id = esc_js( cos_child_gtm_id() );
	?>
<!-- Google Tag Manager -->
<script nonce="<?php echo esc_attr( cos_csp_nonce() ); ?>">(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src='https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);})(window,document,'script','dataLayer','<?php echo $gtm_id; ?>');</script>
<!-- End Google Tag Manager -->
	<?php
}
add_action( 'wp_head', 'cos_child_gtm_consent_bootstrap', 1 );

function cos_child_gtm_noscript(): void {
	if ( is_admin() || ! cos_child_gtm_should_load() ) return;
	$gtm_id = esc_attr( cos_child_gtm_id() );
	?><noscript><iframe src="https://www.googletagmanager.com/ns.html?id=<?php echo $gtm_id; ?>" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript><?php
}
add_action( 'wp_body_open', 'cos_child_gtm_noscript', 1 );

/* ─── Complianz consent bridge ───────────────────────────────────── */
// Complianz fires 'cmplz_status_change' on <body> whenever a category's
// consent state changes (including on load, from a previously-stored
// choice) — {detail: {category: 'statistics'|'marketing'|..., status:
// 'allow'|'deny'}}. This maps that into Google's Consent Mode v2 signals;
// GTM/GA tags already loaded honour the update per Consent Mode's own
// wait-for-update behaviour, so a visitor granting consent after the
// thank-you page has already rendered is handled correctly with no extra
// re-fire logic needed here.
function cos_child_complianz_consent_bridge(): void {
	if ( is_admin() ) return;
	?>
<script nonce="<?php echo esc_attr( cos_csp_nonce() ); ?>">
document.addEventListener('cmplz_status_change', function(e){
  if (!window.gtag || !e.detail || !e.detail.category) return;
  var granted = e.detail.status === 'allow';
  var update = {};
  if (e.detail.category === 'statistics') {
    update.analytics_storage = granted ? 'granted' : 'denied';
  } else if (e.detail.category === 'marketing') {
    update.ad_storage = granted ? 'granted' : 'denied';
    update.ad_user_data = granted ? 'granted' : 'denied';
    update.ad_personalization = granted ? 'granted' : 'denied';
  } else {
    return;
  }
  gtag('consent', 'update', update);
});
</script>
	<?php
}
add_action( 'wp_footer', 'cos_child_complianz_consent_bridge', 1 );

/* ─── GA4 dataLayer bootstrap ───────────────────────────────────── */
// cos.track() is the single funnel into dataLayer/GTM. Every ecommerce event
// (add_to_cart, view_item, view_item_list, select_item, view_cart,
// begin_checkout, add_shipping_info, add_payment_info, purchase) is fired
// from server-confirmed WooCommerce data at its real hook point — never from
// this JS file guessing prices. The old cos.products/localStorage cart below
// remains ONLY for the on-page "ritual completion" badge (a cosmetic nice-to-
// have, explicitly never blended into the authoritative bag count), and is
// no longer used to compute any analytics value.
function cos_child_ga4_datalayer(): void { ?>
<script nonce="<?php echo esc_attr( cos_csp_nonce() ); ?>">
window.dataLayer=window.dataLayer||[];
window.cos=window.cos||{};
cos.track=function(event,params){window.dataLayer.push(Object.assign({event:event},params));};
cos.cart=JSON.parse(localStorage.getItem('cos_cart')||'[]');
cos.currency='EUR';
cos.products={
  'micellar-water-cleanser':{id:'COS-001',name:'Micellar Water Cleanser',price:55,step:1,category:'Cleanse'},
  'advanced-repair-serum':{id:'COS-002',name:'Advanced Repair Serum',price:150,step:2,category:'Treat'},
  'revitalizing-eye-cream':{id:'COS-003',name:'Revitalizing Eye Cream',price:145,step:3,category:'Eye'},
  '24h-regeneration-cream':{id:'COS-004',name:'24h Regeneration Cream',price:150,step:4,category:'Moisturise'},
  'the-complete-ritual':{id:'COS-BUNDLE',name:'The Complete Ritual',price:450,step:0,category:'Bundle'}
};
cos.addToCart=function(slug,qty,subscribe){
  // Cosmetic "ritual completion" tracking only — NOT the analytics source.
  // Real add_to_cart GA4 events fire server-side-confirmed, from the
  // woocommerce_add_to_cart hook (see below), for both the AJAX fragment
  // path and the plain-form-post pageload path.
  qty=qty||1;
  var p=cos.products[slug];if(!p)return;
  var cart=JSON.parse(localStorage.getItem('cos_cart')||'[]');
  var existing=cart.find(function(i){return i.id===p.id&&i.subscribe===(subscribe||false);});
  if(existing){existing.qty+=qty;}else{cart.push({id:p.id,slug:slug,name:p.name,price:p.price,step:p.step,qty:qty,subscribe:subscribe||false});}
  localStorage.setItem('cos_cart',JSON.stringify(cart));
  cos.updateBagCount();
  return cart;
};
cos.updateBagCount=function(){
  // The visible bag count is the WooCommerce cart/session count — the only
  // authoritative source. localStorage is never blended in, since a stale
  // local copy could only ever inflate the badge and never self-correct.
  var total = window.cosWooCount || 0;
  document.querySelectorAll('.cos-bag-count').forEach(function(el){el.textContent=total>0?String(total):'';});
  var cart=JSON.parse(localStorage.getItem('cos_cart')||'[]');
  var hasBundle=cart.some(function(i){return i.id==='COS-BUNDLE';});
  var steps=cart.filter(function(i){return i.step>0;}).map(function(i){return i.step;});
  var completion=hasBundle?4:(new Set(steps)).size;
  document.querySelectorAll('.cos-ritual-completion').forEach(function(el){el.textContent=completion+' of 4';});
};
document.addEventListener('DOMContentLoaded',cos.updateBagCount);
// Server-confirmed add_to_cart for the AJAX path (shop grid "Add to Bag"
// buttons): WooCommerce's own added_to_cart event fires after the AJAX
// fragments response is applied; #cos-atc-data is one of those fragments,
// populated with the real product/price WooCommerce just added — see
// cos_child_track_add_to_cart_fragment() in PHP. Never the static map above.
if (window.jQuery) {
  jQuery(document.body).on('added_to_cart', function () {
    try {
      var el = document.getElementById('cos-atc-data');
      var data = el && el.textContent ? JSON.parse(el.textContent) : null;
      if (data && data.item_id) {
        cos.track('add_to_cart', { currency: cos.currency, value: data.price * data.quantity,
          items: [{ item_id: data.item_id, item_name: data.item_name, price: data.price, quantity: data.quantity, item_category: data.item_category }] });
      }
    } catch (e) {}
  });
}
// Sitewide CTA tracking — a single delegated listener rather than one
// inline handler per button, so every data-cro-cta-tagged element (home/shop
// hero CTAs, PDP add-to-bag/sticky-bag/bundle-nudge/learn-ritual, cart
// checkout/continue/returns, checkout place-order, and any future one) is
// covered automatically just by adding the attribute — no JS change needed
// per new CTA. Plain addEventListener, not jQuery, so this still works on
// pages WooCommerce's own scripts aren't loaded on.
document.addEventListener('click', function (event) {
  var el = event.target.closest('[data-cro-cta]');
  if (!el || !(window.cos && cos.track)) return;
  cos.track('cta_click', {
    cta_name: el.getAttribute('data-cro-cta'),
    page: location.pathname,
    destination: el.getAttribute('href') || null
  });
});
</script>
<script type="application/json" id="cos-atc-data">{}</script>
<?php }
add_action( 'wp_head', 'cos_child_ga4_datalayer', 5 );

/* ─── add_to_cart: server-confirmed (AJAX path) ─────────────────── */
// Captures the real product/quantity/price WooCommerce just added (fires
// during the AJAX add-to-cart request, before the fragments filter runs),
// then exposes it via the same fragments response the bag-count already
// uses. The #cos-atc-data placeholder printed above is what gets replaced.
add_action( 'woocommerce_add_to_cart', function ( $cart_item_key, $product_id, $quantity, $variation_id, $variation, $cart_item_data ): void {
	$product = wc_get_product( $variation_id ?: $product_id );
	if ( ! $product ) return;
	$terms = get_the_terms( $product->get_id(), 'product_cat' );
	$GLOBALS['cos_last_added_to_cart'] = [
		'item_id'       => $product->get_sku() ?: (string) $product->get_id(),
		'item_name'     => $product->get_name(),
		'price'         => (float) wc_get_price_to_display( $product ),
		'quantity'      => (int) $quantity,
		'item_category' => ( $terms && ! is_wp_error( $terms ) && $terms ) ? $terms[0]->name : '',
	];
}, 10, 6 );

add_filter( 'woocommerce_add_to_cart_fragments', function ( array $fragments ): array {
	if ( ! empty( $GLOBALS['cos_last_added_to_cart'] ) ) {
		$fragments['#cos-atc-data'] = '<script type="application/json" id="cos-atc-data">' . wp_json_encode( $GLOBALS['cos_last_added_to_cart'] ) . '</script>';
	}
	return $fragments;
} );

/* ─── add_to_cart: server-confirmed (non-AJAX form-post path) ───── */
// This site has woocommerce_cart_redirect_after_add = no, so the
// single-product form POSTs back to the same page and WooCommerce
// re-renders it in place — no redirect, no ?added-to-cart= query var ever
// appears. woocommerce_add_to_cart still fires normally during wp_loaded
// (before this wp_footer hook runs), so $cos_last_added_to_cart from the
// hook above is already populated with the real confirmed item by the time
// we get here. True AJAX add-to-cart requests never reach this point at all
// — WooCommerce's AJAX handler exits via wp_die() before wp_footer runs —
// so there's no double-fire risk between the two paths.
function cos_child_track_add_to_cart_pageload(): void {
	if ( is_admin() || empty( $GLOBALS['cos_last_added_to_cart'] ) ) return;
	$item = $GLOBALS['cos_last_added_to_cart'];
	?>
<script nonce="<?php echo esc_attr( cos_csp_nonce() ); ?>">
document.addEventListener('DOMContentLoaded',function(){
  if (window.cos && cos.track) {
    var item = <?php echo wp_json_encode( $item, JSON_HEX_TAG | JSON_HEX_AMP ); ?>;
    cos.track('add_to_cart',{currency:'EUR',value:item.price*item.quantity,items:[item]});
  }
});
</script>
	<?php
}
add_action( 'wp_footer', 'cos_child_track_add_to_cart_pageload' );

/* ─── remove_from_cart: server-confirmed, redirect-safe via session ─── */
// The cart page's "Remove" link is a plain wc_get_cart_remove_url() —
// WooCommerce processes it and then 302-redirects back to /cart/?removed_item=1
// (a real HTTP redirect, unlike the same-request add-to-cart form-post path
// above), so $GLOBALS from this request can't survive to render the tracking
// script. WC()->session (already used for chosen_payment_method elsewhere in
// this theme) does survive the redirect, so the item is stashed there and
// consumed exactly once on the very next page load — mirrors the
// _cos_purchase_tracked "write once, never at render time" pattern above.
// Hooks into woocommerce_remove_cart_item (fires before the item is actually
// unset from cart_contents) rather than woocommerce_cart_item_removed,
// because WC_Cart::remove_cart_item() strips the 'data' product object out
// of removed_cart_contents before firing the later hook.
add_action( 'woocommerce_remove_cart_item', function ( $cart_item_key, $cart ): void {
	if ( ! function_exists( 'WC' ) || ! WC()->session ) return;
	$item = $cart->get_cart_item( $cart_item_key );
	if ( empty( $item['data'] ) ) return;
	$product = $item['data'];
	$terms   = get_the_terms( $product->get_id(), 'product_cat' );
	WC()->session->set( 'cos_last_removed_item', [
		'item_id'       => $product->get_sku() ?: (string) $product->get_id(),
		'item_name'     => $product->get_name(),
		'price'         => (float) wc_get_price_to_display( $product ),
		'quantity'      => (int) $item['quantity'],
		'item_category' => ( $terms && ! is_wp_error( $terms ) && $terms ) ? $terms[0]->name : '',
	] );
}, 10, 2 );

function cos_child_track_remove_from_cart_pageload(): void {
	if ( is_admin() || empty( $_GET['removed_item'] ) || ! function_exists( 'WC' ) || ! WC()->session ) return;
	$item = WC()->session->get( 'cos_last_removed_item' );
	if ( ! $item ) return;
	WC()->session->set( 'cos_last_removed_item', null );
	?>
<script nonce="<?php echo esc_attr( cos_csp_nonce() ); ?>">
document.addEventListener('DOMContentLoaded',function(){
  if (window.cos && cos.track) {
    var item = <?php echo wp_json_encode( $item, JSON_HEX_TAG | JSON_HEX_AMP ); ?>;
    cos.track('remove_from_cart',{currency:'EUR',value:item.price*item.quantity,items:[item]});
  }
});
</script>
	<?php
}
add_action( 'wp_footer', 'cos_child_track_remove_from_cart_pageload' );

/* ─── quantity change: server-confirmed (same-request, no redirect) ─── */
// The cart page's qty +/- buttons post straight back to /cart/ with no
// redirect (unlike Remove), so this can reuse the same-request $GLOBALS
// pattern as the add-to-cart form-post path. A quantity increase is
// reported as an add_to_cart delta and a decrease as a remove_from_cart
// delta — the quantity GA4 expects on these events is the change itself,
// not the item's new running total.
add_action( 'woocommerce_after_cart_item_quantity_update', function ( $cart_item_key, $quantity, $old_quantity ): void {
	$delta = (int) $quantity - (int) $old_quantity;
	if ( 0 === $delta || ! function_exists( 'WC' ) ) return;
	$item = WC()->cart->get_cart_item( $cart_item_key );
	if ( empty( $item['data'] ) ) return;
	$product = $item['data'];
	$terms   = get_the_terms( $product->get_id(), 'product_cat' );
	$GLOBALS['cos_last_qty_change'] = [
		'event' => $delta > 0 ? 'add_to_cart' : 'remove_from_cart',
		'item'  => [
			'item_id'       => $product->get_sku() ?: (string) $product->get_id(),
			'item_name'     => $product->get_name(),
			'price'         => (float) wc_get_price_to_display( $product ),
			'quantity'      => abs( $delta ),
			'item_category' => ( $terms && ! is_wp_error( $terms ) && $terms ) ? $terms[0]->name : '',
		],
	];
}, 10, 3 );

function cos_child_track_quantity_change_pageload(): void {
	if ( is_admin() || empty( $GLOBALS['cos_last_qty_change'] ) ) return;
	$event = $GLOBALS['cos_last_qty_change']['event'];
	$item  = $GLOBALS['cos_last_qty_change']['item'];
	?>
<script nonce="<?php echo esc_attr( cos_csp_nonce() ); ?>">
document.addEventListener('DOMContentLoaded',function(){
  if (window.cos && cos.track) {
    var item = <?php echo wp_json_encode( $item, JSON_HEX_TAG | JSON_HEX_AMP ); ?>;
    cos.track('<?php echo esc_js( $event ); ?>',{currency:'EUR',value:item.price*item.quantity,items:[item]});
  }
});
</script>
	<?php
}
add_action( 'wp_footer', 'cos_child_track_quantity_change_pageload' );

/* ─── purchase: confirm the browser actually pushed the event ───── */
// Called from page-checkout.php's order-received script, only after
// cos.track('purchase', ...) has run client-side. This is the ONLY place
// _cos_purchase_tracked gets written — never at render time — so a visitor
// who never executes that script (JS disabled, ad blocker, closed tab before
// it ran) never has their order silently marked as tracked. The order key
// (a secret, unguessable value already used to authorise this same page) is
// the real access control here; the nonce is defense-in-depth on top of it.
add_action( 'wp_ajax_cos_confirm_purchase_tracked', 'cos_child_confirm_purchase_tracked' );
add_action( 'wp_ajax_nopriv_cos_confirm_purchase_tracked', 'cos_child_confirm_purchase_tracked' );

function cos_child_confirm_purchase_tracked(): void {
	$order_id  = absint( $_POST['order_id'] ?? 0 );
	$order_key = sanitize_text_field( wp_unslash( $_POST['order_key'] ?? '' ) );
	$nonce     = sanitize_text_field( wp_unslash( $_POST['nonce'] ?? '' ) );

	if ( ! $order_id || ! wp_verify_nonce( $nonce, 'cos_confirm_purchase_' . $order_id ) ) {
		wp_send_json_error( 'Invalid request.', 400 );
	}

	$order = wc_get_order( $order_id );
	if ( ! $order || ! $order_key || ! hash_equals( $order->get_order_key(), $order_key ) ) {
		wp_send_json_error( 'Invalid order.', 403 );
	}

	if ( $order->get_meta( '_cos_purchase_tracked' ) ) {
		wp_send_json_success( [ 'already_tracked' => true ] );
	}

	$order->update_meta_data( '_cos_purchase_tracked', 1 );
	$order->save();

	wp_send_json_success( [ 'already_tracked' => false ] );
}
