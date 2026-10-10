<?php
/**
 * 03 — Why caviar? Full-bleed dark macro of caviar on a silver spoon, copy on
 * its dark left side; along the bottom a three-beat story (5 Oct 2026, owner):
 * the ingredient → the formulation distinction → the skin benefit.
 * Every statement is already published on the site:
 *   sourcing        — /science/why-caviar/ ("Sustainably farmed. Fully traceable.",
 *                     licensed aquaculture, no wild harvest)
 *   encapsulation   — /science/why-caviar/ + serum/eye-cream PDP actives
 *                     (micro-encapsulated: potency protected, time-release delivery)
 *   firmness        — serum PDP (encapsulated caviar peptides + SYN®-COLL) and its
 *                     instrument-measured 28-day result, shown in trust.php below.
 * Species: Siberian sturgeon (Acipenser baerii), confirmed by the owner 5 Oct
 * 2026 (the PDPs' earlier "Oscietra" wording was corrected to match).
 */
defined( 'ABSPATH' ) || exit;

$benefits = array(
	array( 'The ingredient', 'Caviar from sustainably farmed Siberian sturgeon (Acipenser baerii). Fully traceable, never wild-harvested.' ),
	array( 'The formulation', 'Micro-encapsulated, so its actives keep their potency in the formula and are released over time.' ),
	array( 'The result', 'In Advanced Repair Serum, paired with SYN®-COLL peptide, for visibly firmer skin, instrument-measured over 28 days.' ),
);
?>
<section class="h26-caviar" aria-labelledby="h26-caviar-title">
	<div class="h26-caviar__media">
		<?php
		echo cos_h26_picture(
			'caviar-spoon-mobile',
			array( 640, 972 ),
			array( 972, 695 ),
			'',
			array(
				'sizes' => '100vw',
				'w'     => 972,
				'art'   => array( array( 'media' => '(min-width: 900px)', 'base' => 'caviar-spoon', 'widths' => array( 1280, 1672 ), 'sizes' => '100vw' ) ),
			)
		);
		?>
	</div>
	<div class="h26-wrap h26-caviar__inner">
		<div class="h26-caviar__copy h26-reveal">
			<p class="h26-eyebrow">Signature ingredient</p>
			<h2 id="h26-caviar-title" class="h26-caviar__title">Caviar in every formula</h2>
			<p class="h26-caviar__text">Rich in the lipids, amino acids and minerals skin relies on. What sets ours apart is where it comes from, and how it is formulated.</p>
			<a class="h26-link h26-link--light" href="<?php echo esc_url( home_url( '/science/why-caviar/' ) ); ?>">Why caviar <?php echo cos_h26_arrow(); ?></a>
		</div>
		<ul class="h26-benefits" role="list">
			<?php foreach ( $benefits as $i => [ $k, $v ] ) : ?>
			<li class="h26-benefit h26-reveal" style="--h26-i:<?php echo (int) $i; ?>"><span class="h26-benefit__k"><?php echo esc_html( $k ); ?></span><span class="h26-benefit__v"><?php echo esc_html( $v ); ?></span></li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>
