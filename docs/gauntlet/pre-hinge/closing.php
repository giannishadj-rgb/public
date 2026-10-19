<?php
/**
 * Closing (hxz, luxury pass 17 Oct 2026): the house statement as the last emotional beat,
 * then the four formulas standing together as one set (hxz-set, build_hxz_set.py) beside
 * a quiet Complete Ritual offer. The brand sign-off lives in the footer, not here.
 */
defined( 'ABSPATH' ) || exit;
$products   = $args['products'] ?? cos_h26_products();
$price      = $args['ritual'] ?? cos_h26_ritual_price();
$total      = cos_h26_individual_total();
$set_post   = get_page_by_path( 'the-complete-ritual', OBJECT, 'product' );
$set_prod   = $set_post && function_exists( 'wc_get_product' ) ? wc_get_product( $set_post ) : null;
$set_amount = $set_prod ? (float) $set_prod->get_price() : 0;
$set_url    = $set_post ? get_permalink( $set_post ) : home_url( '/products/the-complete-ritual/' );
$names      = array();
foreach ( $products as $p ) {
	$names[] = implode( ' ', (array) $p['name'] );
}
$set_alt = 'The four formulas of the Complete Ritual standing together: ' . implode( ', ', array_slice( $names, 0, -1 ) ) . ' and ' . end( $names );
?>
<section id="closing" class="hxz" aria-labelledby="hxz-title">
 <div class="hx-wrap hxz__inner">
  <h2 id="hxz-title" class="hx-display hxz__title"><span class="hxz__line">Swiss skincare,</span> <span class="hxz__line hxz__line--2">refined to <span class="hxz__keep">the essential.</span></span></h2>
  <div class="hxz__stage">
   <?php
   echo cos_h26_picture(
	   'hxz-set',
	   array( 640, 900, 1150 ),
	   array( 115, 144 ),
	   $set_alt,
	   array(
		   'class' => 'hxz__set',
		   'sizes' => '(min-width: 1500px) 540px, (min-width: 900px) 36vw, 383px',
		   'w'     => 900,
	   )
   );
   ?>
   <div class="hxz__offer">
    <h3 class="hx-label hxz__set-title">The Complete Ritual</h3>
    <?php if ( $price ) : ?><p class="hxz__price"><?php echo esc_html( $price ); ?></p><?php endif; ?>
    <p class="hxz__inclusion">All four full-size formulas, morning and evening.<?php if ( $set_amount > 0 && $total > $set_amount ) : ?> <span class="hxz__individual"><?php echo esc_html( html_entity_decode( wp_strip_all_tags( wc_price( $total, array( 'decimals' => 0 ) ) ), ENT_QUOTES, 'UTF-8' ) ); ?> when purchased individually.</span><?php endif; ?></p>
    <a class="hx-btn hxz__cta" href="<?php echo esc_url( $set_url ); ?>" data-cro-cta="home-final-ritual">Discover the Complete Ritual<span class="hx-arrow" aria-hidden="true"></span></a>
    <ul class="hxz__assure" role="list" aria-label="Delivery and returns"><li>Free worldwide shipping</li><li>30-day returns</li></ul>
   </div>
  </div>
 </div>
</section>
