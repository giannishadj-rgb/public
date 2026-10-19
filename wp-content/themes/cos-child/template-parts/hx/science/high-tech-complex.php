<?php
/**
 * /science/high-tech-complex/ (page 346) — chapter 02 (round 2, 17 Oct): the synergy story only.
 * The five actives one by one now live on /science/ingredients/ (no duplication).
 * Two restrained hairline diagrams: the four relations between the actives (≥760px; phones read
 * the numbered list), and hyaluronic acid's three molecular weights at three depths.
 */
defined( 'ABSPATH' ) || exit;

get_template_part( 'template-parts/hx/science/masthead', null, array(
	'label' => 'Chapter 02 <span aria-hidden="true">·</span> The High-Tech Complex',
	'title' => 'Engineered for synergy.',
	'lead'  => 'Five actives, selected for evidence, bioavailability and the way they work together. Each does something the others cannot.',
	'plate' => array(
		'base' => 'caviar-macro-light', 'widths' => array( 1440, 2400 ), 'ratio' => array( 3, 2 ),
		'alt'  => 'A clear droplet of gel holding a cluster of fine bubbles on a pale surface',
		'pos'  => '50% 50%',
	),
) );

$ha   = hxsc_actives()[2];
$rels = array(
	array( 'Caviar Extract', 'Superoxide Dismutase', 'Caviar Extract rebuilds the lipid matrix in which SOD does its antioxidant work.' ),
	array( 'Caviar Extract', 'Syn®‑Coll Peptide', 'Caviar Extract supplies the amino acids collagen is built from; Syn®-Coll Peptide is the signal studied in collagen research.' ),
	array( 'Hyaluronic Acid', 'Syn®‑Coll Peptide', 'Hyaluronic Acid keeps the skin hydrated, the environment in which the peptide works best.' ),
	array( 'Resveratrol', 'Syn®‑Coll Peptide', 'Resveratrol helps defend the skin against the daily stress that works against firmness.' ),
);
?>

<section class="hxsc-sec" aria-labelledby="htc-syn">
	<div class="hx-wrap">
		<div class="hxsc-sec__head hxsc-sec__head--wide" data-hx-reveal>
			<h2 id="htc-syn" class="hx-heading hxsc-sec__title">Synergy is not a marketing word.</h2>
			<p class="hx-body hxsc-sec__intro">The five actives in the High-Tech Complex do not merely coexist. They complement one another.</p>
		</div>
		<figure class="hxsc-syn" data-hx-reveal>
			<div class="hxsc-syn__map" aria-hidden="true">
				<svg viewBox="0 0 100 50" preserveAspectRatio="none" focusable="false">
					<line x1="30" y1="8" x2="70" y2="8"/><line x1="30" y1="8" x2="70" y2="33"/><line x1="30" y1="25" x2="70" y2="33"/><line x1="30" y1="42" x2="70" y2="33"/>
				</svg>
				<span class="hxsc-syn__node hxsc-syn__node--l" style="--x:30;--y:8">Caviar Extract</span>
				<span class="hxsc-syn__node hxsc-syn__node--l" style="--x:30;--y:25">Hyaluronic Acid</span>
				<span class="hxsc-syn__node hxsc-syn__node--l" style="--x:30;--y:42">Resveratrol</span>
				<span class="hxsc-syn__node hxsc-syn__node--r" style="--x:70;--y:8">Superoxide Dismutase</span>
				<span class="hxsc-syn__node hxsc-syn__node--r" style="--x:70;--y:33">Syn®‑Coll Peptide</span>
				<span class="hxsc-syn__k" style="--x:50;--y:8">1</span>
				<span class="hxsc-syn__k" style="--x:50;--y:20.5">2</span>
				<span class="hxsc-syn__k" style="--x:50;--y:29">3</span>
				<span class="hxsc-syn__k" style="--x:50;--y:37.5">4</span>
			</div>
			<figcaption class="hx-sr">How the five actives complement one another</figcaption>
			<ol class="hxsc-syn__list" role="list">
				<?php foreach ( $rels as $i => $r ) : ?>
				<li><span class="hxsc-syn__i" aria-hidden="true"><?php echo (int) $i + 1; ?></span><span class="hxsc-syn__pair"><?php echo esc_html( $r[0] ); ?> <span aria-hidden="true">→</span><span class="hx-sr"> and </span> <?php echo esc_html( $r[1] ); ?></span><span class="hxsc-syn__t"><?php echo esc_html( $r[2] ); ?></span></li>
				<?php endforeach; ?>
			</ol>
		</figure>
	</div>
</section>

<section class="hxsc-sec hxsc-sec--tint" aria-labelledby="htc-ha">
	<div class="hx-wrap hxsc-sec__grid">
		<div class="hxsc-sec__head" data-hx-reveal>
			<h2 id="htc-ha" class="hx-heading hxsc-sec__title">Hydration at three depths.</h2>
			<p class="hx-body hxsc-sec__intro"><?php echo esc_html( $ha['desc'] ); ?></p>
		</div>
		<figure class="hxsc-depth" data-hx-reveal>
			<figcaption class="hx-sr">Hyaluronic acid: three molecular weights, three depths</figcaption>
			<ol class="hxsc-depth__rows" role="list">
				<?php foreach ( $ha['depth'] as $d => $row ) : ?>
				<li class="hxsc-depth__row hxsc-depth__row--<?php echo (int) $d; ?>">
					<span class="hxsc-depth__layer"><?php echo esc_html( $row[2] ); ?></span>
					<span class="hxsc-depth__dot" aria-hidden="true"></span>
					<span class="hxsc-depth__mw"><?php echo esc_html( $row[0] ); ?> <span class="hxsc-depth__kda"><?php echo esc_html( $row[1] ); ?></span></span>
					<span class="hxsc-depth__d"><?php echo esc_html( $row[3] ); ?></span>
				</li>
				<?php endforeach; ?>
			</ol>
		</figure>
	</div>
</section>

<section class="hxsc-sec" aria-labelledby="htc-all">
	<div class="hx-wrap hxsc-sec__grid">
		<div class="hxsc-sec__head" data-hx-reveal>
			<h2 id="htc-all" class="hx-heading hxsc-sec__title">All five, in every formula.</h2>
		</div>
		<div class="hxsc-prose" data-hx-reveal>
			<p>This is why we use all five in every product, at concentrations calibrated for each formula’s primary function. The serum delivers the highest active concentration. The cream maintains it. The eye cream focuses it. The cleanser primes for it. That is how a system works. That is what makes it a ritual.</p>
			<p class="hxsc-ctas"><a class="hx-btn" href="<?php echo hxsc_url( '/the-ritual/' ); ?>">Discover the complete ritual<?php echo hxsc_arrow(); // phpcs:ignore ?></a> <a class="hx-link" href="<?php echo hxsc_url( '/science/ingredients/' ); ?>">The five actives<?php echo hxsc_arrow(); // phpcs:ignore ?></a></p>
		</div>
	</div>
</section>
