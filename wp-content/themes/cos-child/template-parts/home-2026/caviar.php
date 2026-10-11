<?php
/**
 * 03 — Caviar, the signature ingredient, as a light ingredient macro (owner,
 * 11 Oct 2026, ingredient-spotlight references: luminous macro, type set on
 * the photograph). A full-bleed backlit macro of the caviar pearls on a light
 * field (supplied by the owner), with the spaced label, the shared heading, the
 * species in italic serif and the lede on its calm left field; the three facts
 * and the link continue below in the photograph's own tone. Phones: the plate
 * carries the label; heading and copy follow beneath.
 * Every statement is already published on the site:
 *   sourcing        — /science/why-caviar/ ("Sustainably farmed. Fully traceable.",
 *                     licensed aquaculture, no wild harvest)
 *   encapsulation   — /science/why-caviar/ + serum/eye-cream PDP actives
 *                     (micro-encapsulated: potency protected, time-release delivery)
 *   firmness        — serum PDP (encapsulated caviar peptides + SYN®-COLL) and its
 *                     instrument-measured 28-day result, shown in trust.php below.
 * Species: Siberian sturgeon (Acipenser baerii), confirmed by the owner 5 Oct 2026.
 * Image: design-sources/home-2026/build_caviar_backlit_photo.py (3000px master).
 * The previous dark
 * version is saved in design-sources/home-2026/alt-caviar-dark-2026-10-11/.
 * Styles: .h26v in assets/css/home-2026.css.
 */
defined( 'ABSPATH' ) || exit;

$benefits = array(
	array( 'The ingredient', 'Caviar from sustainably farmed Siberian sturgeon. Fully traceable, never wild‑harvested.' ),
	array( 'The formulation', 'Micro-encapsulated, so its actives keep their potency in the formula and are released over time.' ),
	array( 'The result', 'In Advanced Repair Serum, paired with SYN®-COLL peptide, for visibly firmer skin, instrument-measured over 28 days.' ),
);
?>
<section class="h26v" aria-labelledby="h26-caviar-title">
	<div class="h26v__plate">
		<div class="h26v__photo" data-h26-parallax=".04">
			<?php echo cos_h26_picture( 'caviar-backlit', array( 960, 1440, 1920, 2400 ), array( 3, 2 ), 'Macro photograph of glossy black sturgeon caviar pearls heaped on a light, reflective surface', array( 'sizes' => '100vw', 'w' => 1920 ) ); ?>
		</div>
		<p class="h26v__label">Signature ingredient</p>
		<div class="h26v__copy">
			<h2 id="h26-caviar-title" class="h26-caviar__title">Caviar in every formula</h2>
			<p class="h26v__species"><em>Acipenser baerii</em></p>
			<p class="h26v__lede">Rich in the lipids, amino acids and minerals skin relies on. What sets ours apart is where it comes from, and how it is formulated.</p>
		</div>
	</div>
	<div class="h26v__foot">
		<ul class="h26v__facts" role="list">
			<?php foreach ( $benefits as [ $k, $v ] ) : ?>
			<li class="h26v__fact"><span class="h26v__fact-k"><?php echo esc_html( $k ); ?></span><span class="h26v__fact-v"><?php echo esc_html( $v ); ?></span></li>
			<?php endforeach; ?>
		</ul>
		<p class="h26v__more"><a class="h26-link" href="<?php echo esc_url( home_url( '/science/why-caviar/' ) ); ?>">Why caviar <?php echo cos_h26_arrow(); ?></a></p>
	</div>
</section>
