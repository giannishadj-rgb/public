<?php
/**
 * /science/clinical-studies/ (page 347) — chapter 04, the source of truth for every study claim
 * (round 2, 17 Oct). Leads with the three results as single statements, each with its
 * product (cut-out, unaltered, never clipped) and a method footnote directly beneath; the study
 * text from page 347 sits, unchanged, in a "Study detail" disclosure. Then ONE compact section:
 * method + the 28-day protocol (drawn to scale) + the note on clinical language.
 * Self-reported results never get the large figure (owner, 20 Oct): the cream's line stays text.
 * Owner-pending lines (dermatologist evaluation, +22%/+38%, density/tewametry, "statistically
 * significant", blinded, n=28–34, visual grading) are kept verbatim where they remain.
 */
defined( 'ABSPATH' ) || exit;

get_template_part( 'template-parts/hx/science/masthead', null, array(
	'mode'  => 'bleed',
	'label' => 'Chapter 04 <span aria-hidden="true">·</span> Clinical studies',
	'title' => 'Evidence you can read.',
	'lead'  => 'Every statistic we publish is backed by an independent dermatologist evaluation. Methodology, panel size, age range and duration are disclosed in full.',
	'plate' => array(
		// bleed (25 Oct 2026): the wide portrait (grey ground at the lower left for the type);
		// phones keep the 4:5 portrait
		'base' => 'evidence-wide', 'widths' => array( 1440, 1920, 2400 ), 'ratio' => array( 2400, 1450 ),
		'alt'  => 'Portrait of a woman with clear skin, her chin resting on her hand',
		'tall' => array( 'evidence-portrait', array( 640, 960, 1280 ), array( 4, 5 ) ),
		'pos'  => '50% 30%',
		'pos_wide' => '50% 30%',
		'caption' => 'Illustrative model, not a study participant.',
	),
) );

$all     = hxsc_studies();
$interim = $all[3];
$studies = array_slice( $all, 0, 3 );
?>
<section class="hxsc-sec hxsc-claims" aria-labelledby="cs-results">
	<div class="hx-wrap">
		<div class="hxsc-sec__head hxsc-sec__head--wide" data-hx-reveal>
			<h2 id="cs-results" class="hx-heading hxsc-sec__title">Each formula, studied on its own.</h2>
			<p class="hx-body hxsc-sec__intro">Separate studies by an independent contract research organisation. There is no study of the complete ritual, and the Micellar Water Cleanser has not been clinically studied.</p>
		</div>
		<ol class="hxsc-claims__list" role="list">
			<?php foreach ( $studies as $s ) : $m = ( 'measured' === $s['kind'] ); ?>
			<li class="hxsc-claim hxsc-claim--<?php echo esc_attr( $s['kind'] ); ?>" data-hx-reveal>
				<div class="hxsc-claim__obj"><?php echo cos_h26_picture( 'pack-' . $s['obj'], array( 480, 720 ), array( 2, 3 ), '', array( 'sizes' => '(min-width: 900px) 240px, 150px', 'w' => 480 ) ); // phpcs:ignore ?></div>
				<div class="hxsc-claim__text">
					<?php if ( $m ) : ?>
					<p class="hx-numeral hxsc-claim__n"><?php echo esc_html( $s['fig'] ); ?><span class="hxsc-pct">%</span></p>
					<h3 class="hxsc-claim__finding"><?php echo esc_html( $s['finding'] ); ?></h3>
					<?php else : ?>
					<h3 class="hxsc-claim__finding hxsc-claim__finding--reported"><?php echo esc_html( $s['fig'] ); ?>% panellist-reported satisfaction <span class="hxsc-nw">at 28 days</span></h3>
					<?php endif; ?>
					<p class="hxsc-claim__product"><a href="<?php echo hxsc_url( $s['url'] ); ?>"><?php echo esc_html( $s['product'] ); ?></a></p>
					<p class="hxsc-claim__foot"><span class="hxsc-kind"><?php echo $m ? 'Instrument‑measured' : 'Self‑reported'; ?></span> <span aria-hidden="true">·</span> <?php echo esc_html( $s['method'] ); ?> <span aria-hidden="true">·</span> <span class="hxsc-nw"><?php echo esc_html( $s['panel'] ); ?> aged <?php echo esc_html( $s['ages'] ); ?></span> <span aria-hidden="true">·</span> <span class="hxsc-nw"><?php echo esc_html( strtolower( $s['regimen'] ) ); ?></span></p>
					<?php if ( 'serum' === $s['obj'] ) : ?>
					<p class="hxsc-claim__interim">Day 7 interim, same study: <strong><?php echo esc_html( $interim['fig'] ); ?>%</strong> surface smoothness <span class="hxsc-nw">(<?php echo esc_html( strtolower( $interim['method'] ) ); ?>)</span>.</p>
					<?php endif; ?>
					<details class="hxsc-claim__more">
						<summary>Study detail</summary>
						<p class="hx-body"><?php echo hxsc_kses( $s['text'] ); ?></p>
						<?php if ( 'serum' === $s['obj'] ) : ?><p class="hx-body"><?php echo hxsc_kses( $interim['text'] ); ?></p><?php endif; ?>
					</details>
				</div>
			</li>
			<?php endforeach; ?>
		</ol>
	</div>
</section>

<section class="hxsc-sec hxsc-sec--tint" aria-labelledby="cs-method">
	<div class="hx-wrap hxsc-sec__grid">
		<div class="hxsc-sec__head" data-hx-reveal>
			<h2 id="cs-method" class="hx-heading hxsc-sec__title">How the studies were conducted.</h2>
			<div class="hxsc-prose hxsc-method-prose">
				<p>Independent dermatologist evaluation commissioned by Caviar of Switzerland, conducted by a third-party CRO under blinded conditions where applicable. No internal assessments are used as primary data.</p>
				<p>Female subjects aged 40–65, selected to represent the skin concern being measured: firmness, peri-orbital ageing or dehydration. Panel sizes n=28–34 per study. Methods: cutometry (elasticity), optical profilometry (wrinkle depth), tewametry (barrier function), dermatologist visual grading and validated subject self-assessment.</p>
			</div>
		</div>
		<figure class="hxsc-tl" data-hx-reveal>
			<figcaption class="hxsc-tl__cap">The 28-day protocol, twice-daily application</figcaption>
			<div class="hxsc-tl__axis" aria-hidden="true"></div>
			<ol class="hxsc-tl__list" role="list">
				<li style="--d:0"><p class="hxsc-tl__day">Day 0</p><p class="hxsc-tl__label">Baseline</p><p class="hxsc-tl__desc">Cutometry, profilometry and tewametry readings recorded.</p></li>
				<li style="--d:7"><p class="hxsc-tl__day">Day 7</p><p class="hxsc-tl__label">Interim I</p><p class="hxsc-tl__desc">Early texture and hydration response.</p></li>
				<li style="--d:14"><p class="hxsc-tl__day">Day 14</p><p class="hxsc-tl__label">Interim II</p><p class="hxsc-tl__desc">Barrier function and elasticity progress.</p></li>
				<li style="--d:28"><p class="hxsc-tl__day">Day 28</p><p class="hxsc-tl__label">Final assessment</p><p class="hxsc-tl__desc">Full efficacy evaluation; results published.</p></li>
			</ol>
		</figure>
		<div class="hxsc-sec__full hxsc-legal" data-hx-reveal>
			<h3 class="hxsc-legal__t">We do not make medical claims.</h3>
			<p class="hx-body">These results relate to cosmetic efficacy: the visible and measurable improvement in the appearance and feel of the skin. They are not medical advice. If you have a specific dermatological condition, please consult a physician before starting any new skincare protocol.</p>
		</div>
	</div>
</section>
