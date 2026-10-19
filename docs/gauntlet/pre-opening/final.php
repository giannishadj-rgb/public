<?php
/**
 * The ritual (hxr): how to use the four formulas, and in what order.
 * Source June 2026 brand campaign beside an ordered, practical index.
 * No portrait is presented as a testimonial, product result or study participant.
 */
defined( 'ABSPATH' ) || exit;
$how = array(
	'micellar-water-cleanser' => 'Sweep over face and neck with a cotton pad to lift away make‑up and impurities. No rinsing.',
	'advanced-repair-serum'   => 'Press two or three drops into face and neck. Allow a moment to absorb.',
	'revitalizing-eye-cream'  => 'Pat a little along the orbital bone, from the inner to the outer corner.',
	'24h-regeneration-cream'  => 'Warm a pea-sized amount between the fingertips and press into face and neck. By day, follow with SPF.',
);
?>
<section id="ritual" class="hxr" aria-labelledby="hxr-title">
 <div class="hx-wrap">
  <header class="hxr__head">
   <h2 id="hxr-title" class="hx-heading hxr__title">The daily ritual.</h2>
   <div class="hxr__intro">
    <p class="hxr__lede">Four steps, morning and evening. From the lightest texture to the richest.</p>
    <p class="hxr__more"><a class="hx-link" href="<?php echo esc_url( home_url( '/the-ritual/' ) ); ?>">The ritual, step by step<span class="hx-arrow" aria-hidden="true"></span></a></p>
   </div>
  </header>
  <div class="hxr__body">
   <?php echo cos_h26_picture( 'ritual-campaign-june-2026', array( 720, 1080, 1440 ), array( 2, 3 ), 'Two women resting with eyes closed in warm light, from the Caviar of Switzerland June 2026 campaign', array( 'class' => 'hxr__portrait', 'sizes' => '(min-width: 1200px) 480px, (min-width: 900px) 40vw, (min-width: 600px) 540px, calc(100vw - 48px)', 'w' => 1080 ) ); ?>
   <ol class="hxr__steps">
   <?php foreach ( cos_h26_products() as $p ) :
    if ( empty( $how[ $p['slug'] ] ) ) continue;
    ?>
   <li class="hxr__step">
    <h3 class="hxr__verb"><span class="hxr__num" aria-hidden="true"><?php echo esc_html( $p['num'] ); ?></span><a href="<?php echo esc_url( $p['url'] ); ?>"><?php echo esc_html( $p['verb'] ); ?><span class="hx-sr">: <?php echo esc_html( implode( ' ', $p['name'] ) . ', ' . $p['size'] ); ?></span></a></h3>
    <p class="hxr__how"><?php echo esc_html( $how[ $p['slug'] ] ); ?></p>
   </li>
   <?php endforeach; ?>
   </ol>
  </div>
 </div>
</section>
