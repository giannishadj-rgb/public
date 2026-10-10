<?php
/**
 * 06b — Swiss provenance. Replaces the former "Swiss Precision" (04) and
 * "A Swiss Philosophy" (07) sections, which overlapped. One section, specific
 * facts instead of abstract statements.
 *
 * Two facts, from /science/swiss-formulation/ (page 348): GMP / ISO 22716:2007
 * manufacturing in Zug and batch traceability. Testing stages and stability
 * details live on that page (linked below), not here.
 * Imagery: the laboratory filling shot alone (Matterhorn inset removed
 * 3 Oct 2026 at the owner's request — it read as an added postcard).
 */
defined( 'ABSPATH' ) || exit;

$facts = array(
	array( 'Manufacturing', 'GMP-certified laboratory in Zug (ISO 22716:2007).' ),
	array( 'Traceability', 'Every batch is checked before release. Every jar and bottle is traceable.' ),
);
?>
<section class="h26-provenance" aria-labelledby="h26-provenance-title">
	<div class="h26-provenance__media">
		<div class="h26-provenance__photo h26-reveal">
			<?php echo cos_h26_picture( 'philosophy-lab', array( 640, 1122 ), array( 4, 5 ), 'A gloved hand in the laboratory holding the 24h Regeneration Cream jar as a steel nozzle fills it with cream', array( 'sizes' => '(min-width: 900px) 50vw, 100vw', 'w' => 1122 ) ); ?>
		</div>
	</div>
	<div class="h26-provenance__body">
		<p class="h26-eyebrow h26-eyebrow--rule h26-reveal">Swiss Provenance</p>
		<h2 id="h26-provenance-title" class="h26-provenance__title h26-reveal">Formulated and made in Zug</h2>
		<p class="h26-provenance__text h26-reveal">A Swiss skincare house for more than 20 years. Developed and produced in Zug, from formulation to final fill.</p>
		<dl class="h26-facts">
			<?php foreach ( $facts as $i => [ $k, $v ] ) : ?>
			<div class="h26-fact h26-reveal" style="--h26-i:<?php echo (int) $i; ?>"><dt><?php echo esc_html( $k ); ?></dt><dd><?php echo esc_html( $v ); ?></dd></div>
			<?php endforeach; ?>
		</dl>
		<p class="h26-reveal"><a class="h26-link" href="<?php echo esc_url( home_url( '/science/swiss-formulation/' ) ); ?>">How we formulate in Zug <?php echo cos_h26_arrow(); ?></a></p>
	</div>
</section>
