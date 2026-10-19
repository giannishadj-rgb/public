<?php
/** Authentic campaign, four original products, one daily ritual. */
defined( 'ABSPATH' ) || exit;
$products = $args['products'] ?? cos_h26_products();
$ritual_price = $args['ritual'] ?? cos_h26_ritual_price();
$objects = array( 'micellar-water-cleanser' => 'cleanser', 'advanced-repair-serum' => 'serum', 'revitalizing-eye-cream' => 'eye', '24h-regeneration-cream' => 'cream' );
?>
<section class="hxh h26-hero--pearl" aria-labelledby="hxh-title">
 <div class="hxh__layout">
  <div class="hxh__copy">
   <h1 id="hxh-title" class="hxh__title hx-display"><span><?php esc_html_e( 'Four formulas.', 'cos-child' ); ?></span><em><?php esc_html_e( 'One daily ritual.', 'cos-child' ); ?></em></h1>
   <p class="hxh__lead"><span><?php esc_html_e( 'Caviar in every formula.', 'cos-child' ); ?></span><span><?php esc_html_e( 'Developed and made in Switzerland.', 'cos-child' ); ?></span></p>
   <a class="hx-btn hxh__cta" href="<?php echo esc_url( home_url( '/products/the-complete-ritual/' ) ); ?>" data-cro-cta="home-hero-primary"><?php esc_html_e( 'Shop the Complete Ritual', 'cos-child' ); ?></a>
   <p class="hxh__inclusion"><?php esc_html_e( 'Four full-size essentials', 'cos-child' ); ?><?php if ( $ritual_price ) : ?> <span aria-hidden="true">&middot;</span> <?php echo esc_html( $ritual_price ); ?><?php endif; ?></p>
   <a class="hx-link hxh__explore" href="#collection" data-cro-cta="home-hero-collection"><?php esc_html_e( 'Explore the four formulas', 'cos-child' ); ?><span class="hx-arrow" aria-hidden="true"></span></a>
  </div>
  <ol class="hxh__score" aria-label="Your four-step skincare ritual">
   <?php foreach ( $products as $p ) :
    $key = $objects[ $p['slug'] ] ?? '';
    if ( ! $key ) continue;
   ?>
   <li><a class="hxh__formula" href="<?php echo esc_url( $p['url'] ); ?>" data-cro-cta="home-hero-<?php echo esc_attr( $key ); ?>">
    <span class="hxh__object-stage"><?php echo cos_h26_picture( 'collection-object-set-hxc-' . $key, array( 320, 480, 640 ), array( 640, 1100 ), '', array( 'class' => 'hxh__object', 'sizes' => '(min-width: 768px) 11vw, 22vw', 'w' => 320 ) ); ?></span>
    <span class="hxh__caption"><span class="hxh__num"><?php echo esc_html( $p['num'] ); ?></span> <?php echo esc_html( $p['verb'] ); ?></span><span class="hx-sr">: <?php echo esc_html( implode( ' ', $p['name'] ) . ', ' . $p['size'] ); ?></span>
   </a></li>
   <?php endforeach; ?>
  </ol>
  <?php echo cos_h26_picture( 'ritual-campaign-june-2026', array( 720, 1080, 1440, 1920 ), array( 2, 3 ), 'Two women resting with eyes closed in warm light, from the Caviar of Switzerland June 2026 campaign', array( 'class' => 'hxh__campaign', 'eager' => true, 'sizes' => '(min-width: 768px) 46vw, 100vw', 'w' => 1080 ) ); ?>
 </div>
</section>
