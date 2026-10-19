<?php
/**
 * Ingredient-led science, sourced to the supplied 2021 brand presentation and
 * Ingredients List MAIN. The five-active complex belongs to the three treatment
 * formulas, not the cleanser. No unpublished clinical percentages or depth claims.
 */
defined( 'ABSPATH' ) || exit;
$pillars = array(
 array( 'Firmness.', 'Caviar extract + SYN-COLL peptide.' ),
 array( 'Hydration.', 'Hyaluronic acid to help retain moisture.' ),
 array( 'Antioxidant care.', 'Superoxide dismutase + resveratrol.' ),
);
?>
<section id="evidence" class="hxs" aria-labelledby="hxs-title">
 <div class="hxs__wrap hx-wrap">
  <header class="hxs__head">
   <h2 id="hxs-title" class="hx-heading hxs__title">Five actives.<br><em>One considered complex.</em></h2>
   <p class="hx-lead hxs__lead">The High-Tech Complex combines these actives in our serum, eye cream and 24h cream.</p>
  </header>
  <ul class="hxs__roles" role="list">
   <?php foreach ( $pillars as $pillar ) : ?>
   <li class="hxs__role">
    <h3 class="hxs__role-title"><?php echo esc_html( $pillar[0] ); ?></h3>
    <p class="hx-body hxs__role-text"><?php echo esc_html( $pillar[1] ); ?></p>
   </li>
   <?php endforeach; ?>
  </ul>
  <p class="hxs__more"><a class="hx-link" href="<?php echo esc_url( home_url( '/science/high-tech-complex/' ) ); ?>">Explore the High-Tech Complex<span class="hx-arrow" aria-hidden="true"></span></a></p>
 </div>
</section>
