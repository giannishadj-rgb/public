<?php
/**
 * /science/ (page 344) — the Swiss science hub (round 2, 17 Oct).
 *
 * 1 Plate: the caviar macro, "Protect. Repair. Rejuvenate." set on its light field.
 * 2 The three words, one line each (a quiet band directly under the plate).
 * 3 Results: the visual centre — each figure stands with its product cut-out, method and panel
 *   (page 347). Separate single-formula studies; no ritual study; no cleanser study.
 * 4 The five chapters, as an editorial index with photographs (the hub's only navigation).
 */
defined( 'ABSPATH' ) || exit;

$pillars = hxsc_pillars();
$studies = array_slice( hxsc_studies(), 0, 3 );

get_template_part( 'template-parts/hx/science/masthead', null, array(
	'label' => 'Swiss science',
	'title' => 'Protect. Repair. Rejuvenate.',
	'lead'  => 'Every claim, every result: verifiable. These pages exist so you can read the evidence for yourself.',
	'plate' => array(
		'base' => 'hxv-field', 'widths' => array( 1440, 2048, 2880 ), 'ratio' => array( 2, 1 ),
		'alt'  => 'Glossy black caviar pearls on a pale, reflective surface',
		'tall' => array( 'hxv-field-tall', array( 640, 960, 1280 ), array( 4, 5 ) ),
		'pos'  => '70% 50%',
	),
) );
?>

<section class="hxsc-words" aria-label="Protect, repair, rejuvenate">
	<div class="hx-wrap">
		<ul class="hxsc-words__list" role="list" data-hx-stagger>
			<?php foreach ( $pillars as $p ) : ?>
			<li data-hx-reveal><h2 class="hxsc-words__w"><?php echo esc_html( $p[0] ); ?></h2><p class="hx-body"><?php echo esc_html( $p[1] ); ?></p></li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>

<section class="hxsc-proof" aria-labelledby="hxsc-proof-t">
	<div class="hx-wrap">
		<div class="hxsc-proof__head" data-hx-reveal>
			<p class="hx-label">Clinical results</p>
			<h2 id="hxsc-proof-t" class="hx-heading hxsc-sec__title">Measured, formula by formula.</h2>
			<p class="hx-body hxsc-proof__note">Separate 28-day studies, each of a single formula, by an independent contract research organisation. There is no study of the complete ritual, and none of the Micellar Water Cleanser.</p>
		</div>
		<ul class="hxsc-proof__list" role="list" data-hx-stagger>
			<?php foreach ( $studies as $s ) : $m = ( 'measured' === $s['kind'] ); ?>
			<li class="hxsc-proof__item hxsc-proof__item--<?php echo esc_attr( $s['kind'] ); ?>" data-hx-reveal>
				<?php echo cos_h26_picture( 'pack-' . $s['obj'], array( 480, 720 ), array( 2, 3 ), '', array( 'class' => 'hxsc-proof__obj', 'sizes' => '(min-width: 900px) 200px, 140px', 'w' => 480 ) ); // phpcs:ignore ?>
				<p class="hx-numeral hxsc-proof__n"><?php echo esc_html( $s['fig'] ); ?><span class="hxsc-pct">%</span></p>
				<p class="hxsc-proof__finding"><?php echo esc_html( $s['short'] ); ?></p>
				<p class="hxsc-proof__product"><?php echo esc_html( $s['product'] ); ?></p>
				<p class="hxsc-proof__method"><span class="hxsc-kind"><?php echo $m ? 'Instrument‑measured' : 'Self‑reported'; ?></span><span class="hx-sr">:</span> <?php echo esc_html( $m ? $s['method'] . ', ' : '' ); ?><span class="hxsc-nw"><?php echo esc_html( $s['panel'] ); ?></span> <span class="hxsc-nw">aged <?php echo esc_html( $s['ages'] ); ?>,</span> <span class="hxsc-nw">28 days</span></p>
			</li>
			<?php endforeach; ?>
		</ul>
		<p class="hxsc-proof__more" data-hx-reveal><a class="hx-link" href="<?php echo hxsc_url( '/science/clinical-studies/' ); ?>">Read the studies<?php echo hxsc_arrow(); // phpcs:ignore ?></a></p>
	</div>
</section>

<section class="hxsc-sec hxsc-chapters" aria-labelledby="hxsc-chapters-t">
	<div class="hx-wrap">
		<h2 id="hxsc-chapters-t" class="hx-heading hxsc-sec__title hxsc-chapters__t" data-hx-reveal>Five chapters, one standard.</h2>
		<?php
		$lines = array(
			'/science/why-caviar/'        => array( 'Responsibly farmed Acipenser Baerii roe, fully traceable from aquaculture source to final formula.', array( 'caviar-backlit', array( 960, 1440 ), array( 3, 2 ) ) ),
			'/science/high-tech-complex/' => array( 'Five actives selected for evidence, bioavailability and the way they work together.', array( 'caviar-macro-light', array( 960, 1440 ), array( 3, 2 ) ) ),
			'/science/ingredients/'       => array( 'Each active: its source, its role and how it is described in research.', array( 'caviar-spoon-mobile', array( 640, 972 ), array( 640, 458 ) ) ),
			'/science/clinical-studies/'  => array( 'Independent evaluations, footnoted with panel size, age range, method and duration.', array( 'collection-tile-serum', array( 360, 540, 720 ), array( 540, 580 ) ) ),
			'/science/swiss-formulation/' => array( 'Developed and made in Zug, tested for stability, safety and efficacy before release.', array( 'provenance-summit-tall', array( 640, 960 ), array( 640, 896 ) ) ),
		);
		$items = array();
		foreach ( hxsc_chapters() as $c ) {
			$c['line'] = $lines[ $c['path'] ][0];
			$c['img']  = $lines[ $c['path'] ][1];
			$items[]   = $c;
		}
		get_template_part( 'template-parts/hx/science/index', null, array( 'items' => $items ) );
		?>
	</div>
</section>
