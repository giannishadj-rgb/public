<?php
/** Five documented actives, scoped to the three treatment formulas. */
defined( 'ABSPATH' ) || exit;
$actives = array(
 array( 'Caviar extract', 'Lipids, proteins and minerals.' ),
 array( 'SYN-COLL peptide', 'Firmer, smoother-looking skin.' ),
 array( 'Hyaluronic acid', 'Helps retain moisture.' ),
 array( 'Superoxide dismutase', 'Antioxidant care.' ),
 array( 'Resveratrol', 'Antioxidant care.' ),
);
?>
<section id="evidence" class="hxs hx-on-dark" aria-labelledby="hxs-title">
 <div class="hxs__wrap hx-wrap">
  <header class="hxs__head">
   <h2 id="hxs-title" class="hx-heading hxs__title">Five actives.<br><em>One Swiss complex.</em></h2>
   <p class="hx-lead hxs__lead">Our High-Tech Complex brings caviar extract together with four complementary actives. Shared by our serum, eye cream and 24h cream.</p>
  </header>
  <ul class="hxs__actives" role="list">
   <?php foreach ( $actives as $active ) : ?>
   <li class="hxs__active"><h3><?php echo esc_html( $active[0] ); ?></h3><p><?php echo esc_html( $active[1] ); ?></p></li>
   <?php endforeach; ?>
  </ul>
  <p class="hxs__more"><a class="hx-link" href="<?php echo esc_url( home_url( '/science/high-tech-complex/' ) ); ?>">Explore the High-Tech Complex<span class="hx-arrow" aria-hidden="true"></span></a></p>
 </div>
</section>
