<?php
/**
 * COS Child Theme — add-to-bag confirmation ("mini-bag"), sitewide (16 Oct 2026).
 *
 * When WooCommerce adds a product — AJAX (shop cards, the Complete Ritual, any
 * .ajax_add_to_cart button: jQuery `added_to_cart` + cart fragments) or the
 * product page's form post (page reload) — a small panel confirms it: image,
 * name, quantity and line price, bag subtotal, "Checkout" and "View bag".
 * Desktop: anchored under the bag icon. Phones: a bottom sheet.
 *
 * Data: one extra cart fragment, script#hxn-bag-data (JSON). The existing
 * fragments (span.cos-bag-count, #cos-atc-data) are untouched.
 * Styles: assets/css/hx/chrome.css (.hxn-bag*).
 *
 * Behaviour: announced in a polite live region. Focus moves into the panel only
 * when the add was made from the keyboard; otherwise it auto-dismisses after 7s
 * unless hovered or focused. Escape / the close button close it and return focus
 * to the control that added the item. Reduced motion: no slide.
 */

defined( 'ABSPATH' ) || exit;

/** Remember the cart line just added (fires inside the add-to-cart request). */
add_action( 'woocommerce_add_to_cart', static function ( $cart_item_key ): void {
	$GLOBALS['cos_hxn_bag_key'] = (string) $cart_item_key;
}, 20, 1 );

/** Plain-text money (wc_price HTML → "€145.00"). */
function cos_hxn_bag_money( string $html ): string {
	return trim( html_entity_decode( wp_strip_all_tags( $html ), ENT_QUOTES, 'UTF-8' ) );
}

/** Data for the panel, or null when nothing was added in this request. */
function cos_hxn_bag_data(): ?array {
	if ( empty( $GLOBALS['cos_hxn_bag_key'] ) || ! function_exists( 'WC' ) || ! WC()->cart ) return null;
	$cart = WC()->cart;
	$item = $cart->get_cart_item( $GLOBALS['cos_hxn_bag_key'] );
	if ( ! $item || empty( $item['data'] ) ) return null;
	$cart->calculate_totals();
	/** @var WC_Product $product */
	$product = $item['data'];
	$img_id  = $product->get_image_id();
	if ( ! $img_id && $product->get_parent_id() ) {
		$parent = wc_get_product( $product->get_parent_id() );
		$img_id = $parent ? $parent->get_image_id() : 0;
	}
	$count = (int) $cart->get_cart_contents_count();
	return array(
		'name'     => wp_strip_all_tags( $product->get_name() ),
		'url'      => get_permalink( $product->get_parent_id() ?: $product->get_id() ),
		'image'    => $img_id ? (string) wp_get_attachment_image_url( $img_id, 'woocommerce_thumbnail' ) : '',
		'qty'      => (int) $item['quantity'],
		'line'     => cos_hxn_bag_money( $cart->get_product_subtotal( $product, $item['quantity'] ) ),
		'subtotal' => cos_hxn_bag_money( $cart->get_cart_subtotal() ),
		'count'    => $count,
		'cart'     => wc_get_cart_url(),
		'checkout' => wc_get_checkout_url(),
	);
}

function cos_hxn_bag_json( ?array $data ): string {
	return '<script type="application/json" id="hxn-bag-data">' . wp_json_encode( $data ?: new stdClass(), JSON_HEX_TAG | JSON_HEX_AMP | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>';
}

add_filter( 'woocommerce_add_to_cart_fragments', static function ( array $fragments ): array {
	$data = cos_hxn_bag_data();
	if ( $data ) $fragments['#hxn-bag-data'] = cos_hxn_bag_json( $data );
	return $fragments;
}, 20 );

/** Panel shell + script. A form-post add (product page) prints its data for an open-on-load. */
add_action( 'wp_footer', static function (): void {
	if ( is_admin() || ! function_exists( 'WC' ) ) return;
	$on_load = cos_hxn_bag_data(); // non-null only right after a form-post add on this request
	echo cos_hxn_bag_json( $on_load ); // phpcs:ignore -- JSON with HEX flags
	?>
<p id="hxn-bag-live" class="hx-sr" role="status" aria-live="polite"></p>
<section id="hxn-bag" class="hxn-bag" role="dialog" aria-modal="false" aria-labelledby="hxn-bag-title" hidden>
  <div class="hxn-bag__head">
    <h2 id="hxn-bag-title" class="hxn-bag__title" tabindex="-1">Added to your bag</h2>
    <button type="button" class="hxn-bag__close" aria-label="Close"><span aria-hidden="true"></span></button>
  </div>
  <div class="hxn-bag__item">
    <div class="hxn-bag__media"><img class="hxn-bag__img" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" alt="" width="80" height="100" decoding="async"/></div>
    <div class="hxn-bag__info">
      <a class="hxn-bag__name" href="#"></a>
      <p class="hxn-bag__qty"></p>
    </div>
    <p class="hxn-bag__line"></p>
  </div>
  <div class="hxn-bag__total"><span class="hxn-bag__count">Bag subtotal</span><span class="hxn-bag__subtotal"></span></div>
  <div class="hxn-bag__actions">
    <a class="hx-btn hx-btn--block hxn-bag__checkout" href="<?php echo esc_url( wc_get_checkout_url() ); ?>" data-cro-cta="minibag-checkout">Checkout<span class="hx-arrow" aria-hidden="true"></span></a>
    <a class="hx-link hxn-bag__view" href="<?php echo esc_url( wc_get_cart_url() ); ?>" data-cro-cta="minibag-view-bag">View bag</a>
  </div>
  <p class="hxn-bag__note">Free worldwide shipping · 30-day returns</p>
</section>
<script nonce="<?php echo esc_attr( cos_csp_nonce() ); ?>">
(function(){
  var panel=document.getElementById('hxn-bag'); if(!panel) return;
  var live=document.getElementById('hxn-bag-live');
  var title=panel.querySelector('.hxn-bag__title');
  var closeBtn=panel.querySelector('.hxn-bag__close');
  var lastInput='pointer', trigger=null, timer=null, hideT=null, hovering=false;
  var reduce=window.matchMedia&&window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  document.addEventListener('keydown',function(e){ if(e.key==='Enter'||e.key===' ') lastInput='key'; },true);
  document.addEventListener('pointerdown',function(){ lastInput='pointer'; },true);
  function q(s){return panel.querySelector(s);}
  function parse(str){
    if(!str) return null;
    var m=String(str).match(/>([\s\S]*)<\/script>/); try{ var d=JSON.parse(m?m[1]:str); return d&&d.name?d:null; }catch(e){ return null; }
  }
  function fill(d){
    var img=q('.hxn-bag__img'), media=q('.hxn-bag__media');
    if(d.image){ img.src=d.image; img.alt=''; media.hidden=false; } else { media.hidden=true; }
    var name=q('.hxn-bag__name'); name.textContent=''; name.href=d.url||'#';
    // keep hyphenated words ("All-in-One") on one line
    String(d.name).split(/(\s+)/).forEach(function(part){ if(part.indexOf('-')>0){ var s=document.createElement('span'); s.style.whiteSpace='nowrap'; s.textContent=part; name.appendChild(s); } else name.appendChild(document.createTextNode(part)); });
    q('.hxn-bag__qty').textContent='Quantity '+d.qty;
    q('.hxn-bag__line').textContent=d.line||'';
    q('.hxn-bag__count').textContent='Bag subtotal'+(d.count?' ('+d.count+(d.count===1?' item)':' items)'):'');
    q('.hxn-bag__subtotal').textContent=d.subtotal||'';
    if(d.cart) q('.hxn-bag__view').href=d.cart;
    if(d.checkout) q('.hxn-bag__checkout').href=d.checkout;
  }
  function schedule(){
    clearTimeout(timer);
    timer=setTimeout(function(){ if(!hovering && !panel.contains(document.activeElement)) close(false); else schedule(); },7000);
  }
  function open(d,from){
    fill(d);
    trigger=from||null;
    clearTimeout(hideT);
    panel.hidden=false;
    panel.offsetWidth; // commit the start state before animating in
    panel.classList.add('is-open');
    live.textContent='';
    setTimeout(function(){ live.textContent=d.name+' added to your bag. Bag subtotal '+(d.subtotal||'')+'.'; },60);
    if(lastInput==='key'){ title.focus({preventScroll:true}); }
    schedule();
  }
  function close(restore){
    if(panel.hidden) return;
    clearTimeout(timer);
    var hadFocus=panel.contains(document.activeElement);
    panel.classList.remove('is-open');
    hideT=setTimeout(function(){ panel.hidden=true; }, reduce?0:420);
    if(restore!==false||hadFocus){
      var back=(trigger&&document.contains(trigger))?trigger:document.querySelector('.cos-bag-link');
      if(back && hadFocus) back.focus({preventScroll:true});
    }
  }
  closeBtn.addEventListener('click',function(){ close(true); });
  panel.addEventListener('mouseenter',function(){ hovering=true; });
  panel.addEventListener('mouseleave',function(){ hovering=false; schedule(); });
  panel.addEventListener('focusout',function(){ setTimeout(function(){ if(!panel.contains(document.activeElement)) schedule(); },0); });
  document.addEventListener('keydown',function(e){ if(e.key==='Escape' && !panel.hidden) close(true); });
  // AJAX adds (WooCommerce triggers this jQuery event after applying the fragments).
  function bindJQ(){
    if(!window.jQuery) return false;
    window.jQuery(document.body).on('added_to_cart',function(e,fragments,hash,btn){
      var d=parse(fragments&&fragments['#hxn-bag-data']);
      if(!d){ var el=document.getElementById('hxn-bag-data'); d=parse(el&&el.textContent); }
      if(d) open(d, btn&&btn[0]?btn[0]:null);
    });
    return true;
  }
  // Form-post add (product page reload): data printed with the page.
  function boot(){
    bindJQ();
    var el=document.getElementById('hxn-bag-data'); var d=parse(el&&el.textContent);
    if(d) open(d,null);
  }
  if(document.readyState==='loading') document.addEventListener('DOMContentLoaded',boot); else boot();
})();
</script>
	<?php
}, 30 );
