<?php
/** Caviar-to-skin opening: authentic campaign and ingredient photography. */
defined( 'ABSPATH' ) || exit;
$ritual_price = $args['ritual'] ?? cos_h26_ritual_price();
?>
<section class="hxh h26-hero--pearl" aria-labelledby="hxh-title">
 <div class="hxh__layout">
  <div class="hxh__copy">
   <h1 id="hxh-title" class="hxh__title hx-display"><span><?php esc_html_e( 'Caviar,', 'cos-child' ); ?></span><em><?php esc_html_e( 'in every formula.', 'cos-child' ); ?></em></h1>

   <div class="hxh__purchase">
    <div class="hxh__buy">
   <p class="hxh__lead"><span><?php esc_html_e( 'Swiss-made skincare.', 'cos-child' ); ?></span><span><?php esc_html_e( 'For firmer-looking, hydrated skin.', 'cos-child' ); ?></span></p>
   <a class="hx-btn hxh__cta" href="<?php echo esc_url( home_url( '/products/the-complete-ritual/' ) ); ?>" data-cro-cta="home-hero-primary"><?php esc_html_e( 'Shop the ritual', 'cos-child' ); ?></a>
   <p class="hxh__inclusion"><?php esc_html_e( '4 full-size formulas', 'cos-child' ); ?><?php if ( $ritual_price ) : ?> <span class="hxh__price"><span class="hxh__price-separator" aria-hidden="true">&middot;</span> <?php echo esc_html( $ritual_price ); ?></span><?php endif; ?></p>
   <a class="hx-link hxh__explore" href="#collection" data-cro-cta="home-hero-collection"><?php esc_html_e( 'View collection', 'cos-child' ); ?><span class="hx-arrow" aria-hidden="true"></span></a>
    </div>
    <?php echo cos_h26_picture( 'auth-ritual-spoon-original', array( 320, 480, 640, 960, 1200 ), array( 8551, 8593 ), 'The Complete Ritual: the cleanser, serum, eye cream and 24h cream together', array( 'class' => 'hxh__set', 'sizes' => '(min-width: 1100px) 27.5vw, (min-width: 768px) 165px, 176px', 'w' => 640 ) ); ?>
   </div>
   <a class="hxh__assurance" href="<?php echo esc_url( home_url( '/shipping-returns/' ) ); ?>"><?php esc_html_e( 'Free worldwide shipping. 30-day returns.', 'cos-child' ); ?></a>
  </div>
  <?php echo cos_h26_picture( 'ritual-campaign-june-2026', array( 720, 1080, 1440, 1920 ), array( 2, 3 ), 'Two women resting with eyes closed in warm light, from the Caviar of Switzerland June 2026 campaign', array( 'class' => 'hxh__campaign', 'eager' => true, 'sizes' => '(min-width: 1100px) 42vw, (min-width: 768px) 46vw, 72vw', 'w' => 1080 ) ); ?>
  <?php echo cos_h26_picture( 'hxv-macro', array( 800, 1200, 1600 ), array( 4, 5 ), 'Glossy caviar pearls in close-up', array( 'class' => 'hxh__caviar', 'sizes' => '(min-width: 1100px) 58vw, (min-width: 768px) 54vw, 28vw', 'w' => 800 ) ); ?>
  <div class="hxh__provenance" id="caviar">
   <p class="hxh__origin"><?php esc_html_e( 'Aquitaine caviar. Swiss extraction.', 'cos-child' ); ?></p>
   <p class="hxh__composition"><?php esc_html_e( 'Farmed in France. Rich in lipids, proteins and minerals.', 'cos-child' ); ?></p>
   <a class="hx-link hxh__why" href="<?php echo esc_url( home_url( '/science/why-caviar/' ) ); ?>"><?php esc_html_e( 'Why caviar', 'cos-child' ); ?><span class="hx-arrow" aria-hidden="true"></span></a>
  </div>
 </div>
</section>
