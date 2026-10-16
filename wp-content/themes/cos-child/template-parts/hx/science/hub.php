<?php
/**
 * /science/ (page 344) — the Swiss science hub.
 *
 * 1 Masthead: the organising idea, "Protect. Repair. Rejuvenate." (owner-approved), with the
 *   page's own promise ("Every claim. Every result — verifiable.") and lead.
 * 2 Three roles: one sentence per verb, then a hairline matrix of the five actives against
 *   the three roles (the one diagram on this page: it shows which active does what).
 * 3 Results at a glance — page 347 facts. Corrections against the old DB copy: the serum's +47%
 *   was "perceived" (it is cutometry-measured) and the cream's 100% was attributed to "the full
 *   ritual" (it is the 24h Regeneration Cream's own study, self-reported). No ritual study exists.
 * 4 The chapters (the old "four pillars of evidence" copy), as an editorial index.
 */
defined( 'ABSPATH' ) || exit;

$pillars = hxsc_pillars();
$actives = hxsc_actives();
$studies = hxsc_studies();

get_template_part( 'template-parts/hx/science/masthead', null, array(
	'label' => 'Swiss science',
	'title' => 'Every claim. Every result. Verifiable.',
	'lead'  => 'Swiss aesthetic medicine has long understood what luxury skincare is only beginning to acknowledge: efficacy is not a feature, it is an obligation. These pages exist so you can read the evidence for yourself.',
) );
?>

<section class="hxsc-sec hxsc-roles" aria-labelledby="hxsc-roles-t">
	<div class="hx-wrap">
		<h2 id="hxsc-roles-t" class="hx-label hxsc-roles__label">The organising idea</h2>
		<ol class="hxsc-roles__verbs" role="list" data-hx-stagger>
			<?php foreach ( $pillars as $key => $p ) : ?>
			<li class="hxsc-roles__verb" data-hx-reveal>
				<h3 class="hx-heading hxsc-roles__word"><?php echo esc_html( $p[0] ); ?>.</h3>
				<p class="hx-body hxsc-roles__text"><?php echo esc_html( $p[1] ); ?></p>
			</li>
			<?php endforeach; ?>
		</ol>

		<div class="hxsc-matrix" data-hx-reveal>
			<div class="hxsc-matrix__head">
				<h3 class="hx-title hxsc-matrix__title">One complex. Five actives. Engineered to work in synergy.</h3>
				<p class="hx-body hxsc-matrix__intro">Each active was selected for a specific, evidenced mechanism. Together, they address the full spectrum of skin ageing: collagen loss, oxidative stress, dehydration, structural thinning and inflammation.</p>
			</div>
			<table class="hxsc-matrix__table">
				<caption class="hx-sr">The five actives of the High-Tech Complex and the roles each one serves</caption>
				<thead>
					<tr>
						<th scope="col"><span class="hx-sr">Active</span></th>
						<?php foreach ( $pillars as $p ) : ?><th scope="col"><?php echo esc_html( $p[0] ); ?></th><?php endforeach; ?>
					</tr>
				</thead>
				<tbody>
					<?php foreach ( $actives as $a ) : ?>
					<tr>
						<th scope="row">
							<a class="hxsc-matrix__name" href="<?php echo hxsc_url( '/science/ingredients/' . $a['slug'] . '/' ); ?>"><span class="hxsc-matrix__n" aria-hidden="true"><?php echo esc_html( $a['num'] ); ?></span><span><?php echo esc_html( $a['name'] ); ?><span class="hxsc-matrix__role"><?php echo esc_html( $a['role'] ); ?></span></span></a>
						</th>
						<?php foreach ( $pillars as $key => $p ) : $on = in_array( $key, $a['roles'], true ); ?>
						<td class="<?php echo $on ? 'is-on' : 'is-off'; ?>"><span class="hxsc-matrix__mark" aria-hidden="true"></span><span class="hx-sr"><?php echo $on ? esc_html( $p[0] ) : 'No'; ?></span></td>
						<?php endforeach; ?>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
			<p class="hxsc-matrix__more"><a class="hx-link" href="<?php echo hxsc_url( '/science/high-tech-complex/' ); ?>">Explore the complex<?php echo hxsc_arrow(); // phpcs:ignore ?></a></p>
		</div>
	</div>
</section>

<section class="hxsc-sec hxsc-sec--tint hxsc-glance" aria-labelledby="hxsc-glance-t">
	<div class="hx-wrap">
		<div class="hxsc-glance__head" data-hx-reveal>
			<p class="hx-label">Clinical results at a glance</p>
			<h2 id="hxsc-glance-t" class="hx-heading hxsc-sec__title">Numbers we are confident enough to put on the front page.</h2>
			<p class="hx-detail hxsc-glance__note">Separate 28-day studies, each of a single formula, by an independent contract research organisation. There is no study of the complete ritual, and none of the Micellar Water Cleanser.</p>
		</div>
		<ul class="hxsc-glance__list" role="list" data-hx-stagger>
			<?php foreach ( array_slice( $studies, 0, 3 ) as $s ) : ?>
			<li class="hxsc-glance__item hxsc-glance__item--<?php echo esc_attr( $s['kind'] ); ?>" data-hx-reveal>
				<p class="hxsc-glance__product"><?php echo esc_html( $s['product'] ); ?></p>
				<p class="hx-numeral hxsc-glance__fig"><?php echo esc_html( $s['fig'] ); ?><span class="hxsc-pct">%</span></p>
				<p class="hxsc-glance__finding"><?php echo esc_html( $s['short'] ); ?></p>
				<p class="hx-detail hxsc-glance__method"><span class="hxsc-kind"><?php echo 'measured' === $s['kind'] ? 'Instrument‑measured' : 'Self‑reported'; ?></span><span class="hx-sr">:</span> <?php echo esc_html( 'measured' === $s['kind'] ? $s['method'] . ', ' : '' ); ?><span class="hxsc-nw"><?php echo esc_html( $s['panel'] ); ?></span> <span class="hxsc-nw">aged <?php echo esc_html( $s['ages'] ); ?>,</span> <span class="hxsc-nw">28 days</span></p>
			</li>
			<?php endforeach; ?>
		</ul>
		<p class="hxsc-glance__more" data-hx-reveal><a class="hx-link" href="<?php echo hxsc_url( '/science/clinical-studies/' ); ?>">View all clinical studies<?php echo hxsc_arrow(); // phpcs:ignore ?></a></p>
	</div>
</section>

<section class="hxsc-sec hxsc-chapters" aria-labelledby="hxsc-chapters-t">
	<div class="hx-wrap">
		<div class="hxsc-chapters__head" data-hx-reveal>
			<p class="hx-label">Four pillars of evidence</p>
			<h2 id="hxsc-chapters-t" class="hx-heading hxsc-sec__title">The framework that supports every product claim.</h2>
		</div>
		<?php
		$lines = array(
			'/science/why-caviar/'        => array( 'Responsibly farmed Acipenser Baerii sturgeon roe, fully traceable from aquaculture source to final formula. Biomimetic to human skin’s own cellular chemistry.', array( 'hxv-field-tall', array( 640, 960 ), array( 4, 5 ) ) ),
			'/science/high-tech-complex/' => array( 'Five actives selected for clinical evidence, bioavailability and synergistic interaction. Not five random ingredients: five that amplify each other.', array( 'caviar-macro-light', array( 960, 1440 ), array( 3, 2 ) ) ),
			'/science/ingredients/'       => array( 'The full scientific context behind each ingredient: source, mechanism and role in the protocol.', array( 'caviar-spoon-mobile', array( 640, 972 ), array( 640, 458 ) ) ),
			'/science/clinical-studies/'  => array( 'Independent dermatologist evaluations, footnoted with n-size, age range, methodology and duration. Every statistic on our site links to its study.', array( 'collection-tile-serum', array( 360, 540, 720 ), array( 540, 580 ) ) ),
			'/science/swiss-formulation/' => array( 'Developed at a certified Swiss laboratory in Zug. Every formula is subject to stability, safety and efficacy testing before reaching your skin.', array( 'provenance-summit-tall', array( 640, 960 ), array( 640, 896 ) ) ),
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
