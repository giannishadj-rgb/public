<?php
/**
 * 02a — Tactile moment: the 24h Regeneration Cream texture (application close-up).
 */
defined( 'ABSPATH' ) || exit;
?>
<?php /* Tactile moment (owner, 6 Oct 2026): how the formula feels.
   6 Oct 2026 (critique): its own part, right after the collection, so the 24h Cream
   jar no longer leads provenance, texture and tray back to back.
   Photo: the brand's own application shot (also on the 24h Cream PDP, assets/images/pdp/
   cream-application-*). Copy is the 24h Regeneration Cream description in
   inc/product-data.php, shortened (owner, 6 Oct): "A silky texture. Soft, comfortable
   skin." The fuller description stays on the product page, which the caption links to. */ ?>
<section class="h26-texture" aria-label="The texture">
	<figure class="h26-texture__media h26-reveal">
		<?php echo cos_h26_picture( 'texture-application', array( 600, 800, 1200 ), array( 1, 1 ), 'Two hands holding the open 24h Regeneration Cream jar, a fingertip of cream lifted and a swatch on the back of the hand', array( 'sizes' => '(min-width: 900px) 42vw, 100vw', 'w' => 1200 ) ); ?>
	</figure>
	<div class="h26-texture__body h26-reveal">
		<p class="h26-eyebrow">The texture</p>
		<?php /* Critique 3 (7 Oct 2026): a specific sensory line, still short, from the
		   published description ("a concentrated yet lightweight silky cream that melts
		   into the skin ... never heavy"). */ ?>
		<p class="h26-texture__line">Silky and lightweight, it melts into the skin. Never heavy.</p>
		<p class="h26-texture__caption"><a class="h26-link" href="<?php echo esc_url( home_url( '/products/24h-regeneration-cream/' ) ); ?>">24h Regeneration Cream <?php echo cos_h26_arrow(); ?></a></p>
	</div>
</section>
