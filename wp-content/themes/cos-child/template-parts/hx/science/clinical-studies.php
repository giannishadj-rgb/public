<?php
/**
 * /science/clinical-studies/ (page 347) — chapter 04, the source of truth for every study claim.
 * Every finding, panel, age range, regimen and method is carried over exactly. Presentation adds
 * only labels that page 347 already implies: each result names its single product; the cream's
 * 100% is self-reported; the +22% is the serum study's Day 7 interim. The scope note states what
 * was not studied (the complete ritual; the Micellar Water Cleanser), per the owner's rules.
 * Text change: the methodology's "Day 1 baseline" now reads "baseline", so it no longer
 * contradicts the protocol timeline's "Day 0".
 */
defined( 'ABSPATH' ) || exit;

get_template_part( 'template-parts/hx/science/masthead', null, array(
	'label' => 'Chapter 04 <span aria-hidden="true">·</span> Clinical studies',
	'title' => 'Evidence you can read.',
	'lead'  => 'Every statistic we publish is backed by an independent dermatologist evaluation. Methodology, panel size, age range and duration are disclosed in full. We believe you should be able to read the evidence, not just accept the claim.',
) );

$studies = hxsc_studies();
?>
<section class="hxsc-principles" aria-label="Study credentials">
	<div class="hx-wrap">
		<dl class="hxsc-principles__list" data-hx-stagger>
			<div data-hx-reveal><dt>Independent CRO</dt><dd>Third-party contract research organisation. No in-house testing.</dd></div>
			<div data-hx-reveal><dt>Blinded conditions</dt><dd>Where applicable, evaluators assess without product knowledge.</dd></div>
			<div data-hx-reveal><dt>28-day protocol</dt><dd>Minimum duration, with interim assessments at Day 7 and Day 14.</dd></div>
			<div data-hx-reveal><dt>Full disclosure</dt><dd>Panel size, age range, methods and duration published in full.</dd></div>
		</dl>
	</div>
</section>

<section class="hxsc-sec" aria-labelledby="cs-results">
	<div class="hx-wrap">
		<div class="hxsc-sec__head hxsc-sec__head--wide" data-hx-reveal>
			<p class="hx-label hxsc-sec__label">Published results</p>
			<h2 id="cs-results" class="hx-heading hxsc-sec__title">Four findings, each from an independent study of a single formula.</h2>
			<p class="hx-body hxsc-scope">Each formula was studied on its own. There is no study of the complete ritual, and the Micellar Water Cleanser has not been clinically studied.</p>
		</div>
		<ol class="hxsc-results" role="list">
			<?php foreach ( $studies as $s ) : $measured = ( 'measured' === $s['kind'] ); ?>
			<li class="hxsc-result hxsc-result--<?php echo esc_attr( $s['kind'] ); ?><?php echo ! empty( $s['interim'] ) ? ' hxsc-result--interim' : ''; ?>" data-hx-reveal>
				<div class="hxsc-result__fig">
					<?php echo cos_h26_picture( 'collection-object-' . $s['obj'], array( 480, 720 ), array( 2, 3 ), '', array( 'class' => 'hxsc-result__obj', 'sizes' => '120px', 'w' => 480 ) ); // phpcs:ignore ?>
					<div>
						<p class="hxsc-result__product"><?php echo esc_html( $s['product'] ); ?> <span class="hxsc-result__step">Step <?php echo esc_html( $s['step'] ); ?></span></p>
						<p class="hx-numeral hxsc-result__n"><?php echo esc_html( $s['fig'] ); ?><span class="hxsc-pct">%</span></p>
						<p class="hxsc-kind hxsc-result__kind"><?php echo $measured ? 'Instrument‑measured' : 'Self‑reported'; ?><?php echo ! empty( $s['interim'] ) ? ' · Day 7 interim' : ''; ?></p>
					</div>
				</div>
				<div class="hxsc-result__body">
					<h3 class="hx-title hxsc-result__finding"><?php echo esc_html( $s['finding'] ); ?></h3>
					<p class="hx-body"><?php echo hxsc_kses( $s['text'] ); ?></p>
					<p><a class="hx-link" href="<?php echo hxsc_url( $s['url'] ); ?>">Shop Step <?php echo esc_html( (string) (int) $s['step'] ); ?><span class="hx-sr">: <?php echo esc_html( $s['product'] ); ?></span><?php echo hxsc_arrow(); // phpcs:ignore ?></a></p>
				</div>
				<dl class="hxsc-result__meta">
					<div><dt>Panel</dt><dd><?php echo esc_html( $s['panel'] ); ?></dd></div>
					<div><dt>Ages</dt><dd><?php echo esc_html( $s['ages'] ); ?></dd></div>
					<div><dt>Regimen</dt><dd><?php echo esc_html( $s['regimen'] ); ?></dd></div>
					<div><dt>Method</dt><dd><?php echo esc_html( $s['method'] ); ?></dd></div>
				</dl>
			</li>
			<?php endforeach; ?>
		</ol>
	</div>
</section>

<section class="hxsc-sec hxsc-sec--tint" aria-labelledby="cs-method">
	<div class="hx-wrap">
		<div class="hxsc-sec__head hxsc-sec__head--wide" data-hx-reveal>
			<p class="hx-label hxsc-sec__label">Our methodology</p>
			<h2 id="cs-method" class="hx-heading hxsc-sec__title">How the studies were conducted.</h2>
		</div>
		<dl class="hxsc-method" data-hx-stagger>
			<div data-hx-reveal><dt>Evaluation type</dt><dd>Independent dermatologist evaluation commissioned by Caviar of Switzerland. Conducted by a third-party CRO under blinded conditions where applicable. No internal assessments are used as primary data.</dd></div>
			<div data-hx-reveal><dt>Study duration</dt><dd>All efficacy studies run for a minimum of 28 days with twice-daily application. Baseline, Day 7 interim, Day 14 interim and Day 28 final assessments are included in the full study protocol.</dd></div>
			<div data-hx-reveal><dt>Panel composition</dt><dd>Female subjects aged 40–65, selected to represent the primary skin concern being measured: firmness, peri-orbital ageing or dehydration. Panel sizes n=28–34 per study, meeting statistical significance thresholds.</dd></div>
			<div data-hx-reveal><dt>Measurement methods</dt><dd>Cutometry (elasticity), optical profilometry (wrinkle depth), tewametry (barrier function), dermatologist visual grading and validated subject self-assessment. Multiple methods are used per study to ensure multi-source corroboration.</dd></div>
		</dl>
	</div>
</section>

<section class="hxsc-sec" aria-labelledby="cs-protocol">
	<div class="hx-wrap">
		<div class="hxsc-sec__head hxsc-sec__head--wide" data-hx-reveal>
			<p class="hx-label hxsc-sec__label">Study design</p>
			<h2 id="cs-protocol" class="hx-heading hxsc-sec__title">The 28-day protocol.</h2>
		</div>
		<figure class="hxsc-tl" data-hx-reveal>
			<figcaption class="hx-sr">Assessment days, drawn to scale across 28 days</figcaption>
			<div class="hxsc-tl__axis" aria-hidden="true" data-hx-reveal="mask-x"></div>
			<ol class="hxsc-tl__list" role="list">
				<li style="--d:0"><p class="hxsc-tl__day">Day 0</p><p class="hxsc-tl__label">Baseline</p><p class="hxsc-tl__desc">Full biometric baseline. Cutometry, profilometry and tewametry readings recorded.</p></li>
				<li style="--d:7"><p class="hxsc-tl__day">Day 7</p><p class="hxsc-tl__label">Interim I</p><p class="hxsc-tl__desc">Early texture and hydration response. Surface smoothness measured by profilometry.</p></li>
				<li style="--d:14"><p class="hxsc-tl__day">Day 14</p><p class="hxsc-tl__label">Interim II</p><p class="hxsc-tl__desc">Barrier function and elasticity progress. Tewametry and dermatologist grading.</p></li>
				<li style="--d:28"><p class="hxsc-tl__day">Day 28</p><p class="hxsc-tl__label">Final assessment</p><p class="hxsc-tl__desc">Full efficacy evaluation. Primary endpoints confirmed. Results published.</p></li>
			</ol>
		</figure>
	</div>
</section>

<section class="hxsc-statement" aria-label="Scientific commitment">
	<div class="hx-wrap">
		<blockquote class="hxsc-statement__q" data-hx-reveal>
			<p>We commission studies we don’t control. We publish results whether or not they exceed our expectations. Transparency is not a marketing choice; it is the foundation of trust.</p>
			<footer class="hx-label hxsc-statement__by">Caviar of Switzerland · Scientific standards</footer>
		</blockquote>
	</div>
</section>

<section class="hxsc-sec hxsc-note" aria-labelledby="cs-note">
	<div class="hx-wrap hxsc-sec__grid">
		<div class="hxsc-sec__head" data-hx-reveal>
			<p class="hx-label hxsc-sec__label">A note on clinical language</p>
			<h2 id="cs-note" class="hx-heading hxsc-sec__title">We do not make medical claims.</h2>
		</div>
		<div class="hxsc-prose" data-hx-reveal>
			<p>The results presented here relate to cosmetic efficacy: the visible and measurable improvement in the appearance and feel of the skin. They do not constitute medical advice and should not be interpreted as therapeutic claims. If you have a specific dermatological condition, we recommend consulting a physician before starting any new skincare protocol.</p>
		</div>
	</div>
</section>
