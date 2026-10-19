<?php
/**
 * /science/swiss-formulation/ (page 348) — chapter 05 (round 2, 17 Oct: cut by half). Kept: the
 * Swiss standard, the six stages (one sentence each), the four figures. Cut: the "Why Switzerland"
 * trio and the documentation table (their owner-pending lines — Swissmedic, glacier water — go with
 * them; nothing was reworded). Stage 05 stays inside page 347 (no "each product is tested").
 */
defined( 'ABSPATH' ) || exit;

get_template_part( 'template-parts/hx/science/masthead', null, array(
	'label' => 'Chapter 05 <span aria-hidden="true">·</span> Swiss formulation',
	'title' => 'Made in Zug, to a Swiss standard.',
	'lead'  => 'Every formula is subject to rigorous stability, safety and independent efficacy testing before it reaches your skin. This is what “Made in Switzerland” means in practice.',
	'plate' => array(
		'base' => 'provenance-summit-wide', 'widths' => array( 1440, 1920 ), 'ratio' => array( 1440, 900 ),
		'alt'  => 'The north and east faces of the Matterhorn, cloud streaming from the summit, printed in silver tones',
		'tall' => array( 'provenance-summit-tall', array( 640, 960 ), array( 640, 896 ) ),
		'pos'  => '50% 40%',
		'caption' => 'Matterhorn, Valais. The formulas are made in Zug.',
	),
) );

$stages = array(
	array( 'Active selection', 'Each candidate active is assessed for bioavailability, evidence of mechanism and compatibility; actives that degrade one another are eliminated, regardless of marketing appeal.' ),
	array( 'Formulation', 'With the Zug laboratory’s cosmetic chemists, each formula is developed to one functional objective, and finalised only once it achieves its sensorial profile and passes preliminary stability checks.' ),
	array( 'Stability testing', 'Accelerated (40 °C / 75% RH), freeze-thaw and long-term storage over 24 months confirm that actives, pH, viscosity, colour and microbiological status stay within specification.' ),
	array( 'Safety assessment', 'A qualified safety assessor reviews each formula under Annex I of EU Regulation 1223/2009, ingredient by ingredient, at actual concentration.' ),
	array( 'Independent efficacy testing', 'Published results come from independent studies of single products by a third-party dermatology panel, never from in-house assessments or supplier data. <a href="/science/clinical-studies/">See the studies</a>.' ),
	array( 'GMP manufacturing & batch release', 'Each batch is made to written specification in the GMP-certified Zug facility, released only after quality control, and traceable from batch to jar.' ),
);
?>

<section class="hxsc-sec" aria-labelledby="sf-std">
	<div class="hx-wrap hxsc-sec__grid">
		<div class="hxsc-sec__head" data-hx-reveal>
			<h2 id="sf-std" class="hx-heading hxsc-sec__title">Switzerland is not a provenance claim. It is a formulation standard.</h2>
		</div>
		<div class="hxsc-prose" data-hx-reveal>
			<p>Swiss cosmetics are made under the Cosmetics Ordinance (SR 817.023.31), aligned to EU Regulation 1223/2009: documentation, safety and stability requirements that are enforced, not aspirational.</p>
			<p>Caviar of Switzerland formulas are developed in a GMP-certified laboratory in Zug, at the centre of Switzerland’s life-sciences corridor. We formulate here not for the country’s association with luxury, but for what the standard demands of everyone who manufactures here.</p>
		</div>
	</div>
</section>

<section class="hxsc-sec hxsc-sec--tint" aria-labelledby="sf-proc">
	<div class="hx-wrap">
		<div class="hxsc-sec__head hxsc-sec__head--wide" data-hx-reveal>
			<h2 id="sf-proc" class="hx-heading hxsc-sec__title">Six stages before a formula is released.</h2>
		</div>
		<ol class="hxsc-stages" role="list" data-hx-stagger>
			<?php foreach ( $stages as $i => $s ) : ?>
			<li data-hx-reveal>
				<span class="hxsc-num hxsc-stages__n" aria-hidden="true"><?php echo esc_html( sprintf( '%02d', $i + 1 ) ); ?></span>
				<h3 class="hxsc-stages__t"><?php echo esc_html( $s[0] ); ?></h3>
				<p class="hx-body"><?php echo hxsc_kses( $s[1] ); ?></p>
			</li>
			<?php endforeach; ?>
		</ol>
	</div>
</section>

<section class="hxsc-sec" aria-labelledby="sf-close">
	<div class="hx-wrap hxsc-sec__grid">
		<div class="hxsc-sec__head" data-hx-reveal>
			<h2 id="sf-close" class="hx-heading hxsc-sec__title">You are not buying provenance. You are buying a process.</h2>
			<p class="hx-body hxsc-sec__intro">The four formulas are this process applied four times, to four distinct objectives, with the same five actives at concentrations calibrated for each.</p>
			<p class="hxsc-ctas"><a class="hx-btn" href="<?php echo hxsc_url( '/shop/' ); ?>">Shop the ritual<?php echo hxsc_arrow(); // phpcs:ignore ?></a></p>
		</div>
		<dl class="hxsc-facts" data-hx-stagger>
			<div data-hx-reveal><dt>GMP</dt><dd>ISO 22716:2007 certified manufacturing facility, Zug, Switzerland</dd></div>
			<div data-hx-reveal><dt>6</dt><dd>Stages of testing before any formula is approved for production</dd></div>
			<div data-hx-reveal><dt>24 months</dt><dd>Real-time stability testing duration per formula</dd></div>
			<div data-hx-reveal><dt>0</dt><dd>Efficacy claims based on in-house testing: every one is independently tested</dd></div>
		</dl>
	</div>
</section>
