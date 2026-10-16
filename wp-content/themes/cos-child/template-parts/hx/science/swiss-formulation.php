<?php
/**
 * /science/swiss-formulation/ (page 348) — chapter 05. Copy carried over; changes, all to keep
 * claims inside page 347: stage 05 no longer says "each product is tested" (the cleanser has no
 * study) and its assessment days follow 347; the closing "100% independently tested efficacy"
 * figure (easily misread beside the cream's 100%) became "0 in-house efficacy claims" — the same
 * fact. "Pharmaceutical-grade" testing in the lead is softened to "rigorous" (brief: never
 * pharmaceutical in tone); the factual pharmaceutical comparisons in the body are kept.
 */
defined( 'ABSPATH' ) || exit;

get_template_part( 'template-parts/hx/science/masthead', null, array(
	'label' => 'Chapter 05 <span aria-hidden="true">·</span> Swiss formulation',
	'title' => 'Precision. Performance.',
	'lead'  => 'Formulated at a certified laboratory in Zug, Switzerland. Every formula is subject to rigorous stability, safety and independent efficacy testing before it reaches your skin. This is what “Made in Switzerland” means in practice.',
	'plate' => array(
		'base' => 'provenance-summit-wide', 'widths' => array( 1440, 1920 ), 'ratio' => array( 1440, 900 ),
		'alt'  => 'The north and east faces of the Matterhorn, cloud streaming from the summit, printed in silver tones',
		'tall' => array( 'provenance-summit-tall', array( 640, 960 ), array( 640, 896 ) ),
		'caption' => 'Matterhorn, Valais. The formulas are made in Zug.',
	),
) );

$stages = array(
	array( 'Active selection & compatibility testing', 'Each candidate active is assessed for bioavailability, clinical evidence of mechanism, and compatibility with the other actives in the intended formula. Actives that are chemically antagonistic, or that degrade each other under realistic skin conditions, are eliminated at this stage, regardless of marketing appeal. The five actives in the High-Tech Complex passed this assessment and are stable and synergistic across all four formulas.' ),
	array( 'Formulation development', 'Working with the Zug laboratory’s cosmetic chemists, each formula is developed to achieve a defined functional objective: the cleanser primes the barrier; the serum delivers the highest active payload; the eye cream targets the periorbital zone at appropriate concentrations; the 24h Regeneration Cream seals and sustains. The formula is not finalised until it achieves the target sensorial profile and passes preliminary stability checks.' ),
	array( 'Stability testing', 'Each formula is tested under ICH-compliant accelerated and real-time stability conditions: elevated temperature (40 °C / 75% RH), freeze-thaw cycling, and ambient long-term storage over 24 months. Stability testing confirms that the active concentrations, pH, viscosity, colour and microbiological status of the formula remain within specification for the full product life. A formula that degrades its actives over time is not a stable formula, and is not approved for production.' ),
	array( 'Safety assessment', 'A qualified safety assessor reviews each formula in accordance with Annex I of EU Regulation 1223/2009. This includes toxicological assessment of each ingredient at actual concentration in the final formula, potential for systemic absorption, and sensitisation risk. Products containing actives at elevated concentrations require a more detailed assessment. This documentation is held on file and is legally required for any product placed on the Swiss or European market.' ),
	array( 'Independent clinical efficacy testing', 'Efficacy claims are not made on the basis of in-house assessments or ingredient-supplier data. Each published result comes from an independent study of a single product, by a third-party dermatology panel, under standardised clinical conditions. Panellists are recruited to the relevant age range and skin concern profile. Measurements are taken at baseline, at interim points and at 28 days using validated instruments. Every statistic published by Caviar of Switzerland refers to these independent studies; the methodology, n-size and age range are documented on the <a href="/science/clinical-studies/">Clinical Studies</a> page.' ),
	array( 'GMP manufacturing & batch release', 'Production takes place in the GMP-certified Zug facility. Every batch is produced to a written specification and is released only after in-process and finished-product quality control checks confirm that pH, viscosity, active concentration, microbial limits and packaging integrity are all within specification. Batch records are retained. Every jar and bottle of Caviar of Switzerland is traceable to its manufacturing batch.' ),
);
$docs = array(
	array( 'Product Information File (PIF)', 'A complete technical dossier for each formula, including the formulation, manufacturing method, safety assessment, stability data, claims substantiation and CPNP notification. Required for every cosmetic product on the EU/Swiss market.' ),
	array( 'Safety assessment', 'Completed by a qualified EU-recognised cosmetic safety assessor under Annex I of EU Regulation 1223/2009. Covers the toxicological profile of each ingredient at actual use concentration, sensitisation risk and systemic exposure assessment.' ),
	array( 'Stability data', 'ICH-aligned accelerated (40 °C / 75% RH for 6 months) and long-term (25 °C / 60% RH for 24 months) stability studies. Confirms active integrity, physical appearance, pH, viscosity and microbiological status remain within specification.' ),
	array( 'GMP compliance', 'Manufacturing facility holds GMP certification (ISO 22716:2007, Cosmetics Good Manufacturing Practices). Covers personnel, premises, equipment, raw materials, production, finished products, quality control and documentation.' ),
	array( 'INCI declaration', 'Full ingredient list in descending order of concentration, published on product packaging and on this site, in accordance with EU Cosmetics Regulation. No ingredients are concealed under trade-name umbrella terms without INCI disclosure.' ),
	array( 'Independent efficacy testing', 'Third-party consumer panel studies conducted by an independent dermatology evaluation institute. Study reports available on the Clinical Studies page, with n-size, methodology, age range and duration stated.' ),
);
?>

<section class="hxsc-sec" aria-labelledby="sf-std">
	<div class="hx-wrap hxsc-sec__grid">
		<div class="hxsc-sec__head" data-hx-reveal>
			<p class="hx-label hxsc-sec__label">The Swiss standard</p>
			<h2 id="sf-std" class="hx-heading hxsc-sec__title">Switzerland is not a provenance claim. It is a formulation standard.</h2>
		</div>
		<div class="hxsc-prose" data-hx-reveal>
			<p>Switzerland is home to the most stringent cosmetic manufacturing regulations in Europe, operating under the Cosmetics Ordinance (SR 817.023.31) and aligned to EU Regulation 1223/2009. For a formula to be developed and manufactured here, it must meet documentation, safety and stability requirements that are enforced, not aspirational.</p>
			<p>Caviar of Switzerland formulas are developed in a GMP-certified (Good Manufacturing Practice) laboratory in Zug, the centre of Switzerland’s life-sciences corridor. The same regulatory infrastructure that governs Swiss pharmaceutical production governs what goes into our formulas.</p>
			<p>This is why we formulate in Switzerland: not for the country’s association with luxury, but for what the standard demands of everyone who manufactures here.</p>
			<blockquote class="hxsc-quote"><p>A GMP-certified cosmetic laboratory in Switzerland operates to standards that are, in practical terms, indistinguishable from those applied in pharmaceutical manufacturing. That is the environment in which Caviar of Switzerland was developed.</p></blockquote>
		</div>
	</div>
</section>

<section class="hxsc-sec hxsc-sec--tint" aria-labelledby="sf-proc">
	<div class="hx-wrap hxsc-sec__grid">
		<div class="hxsc-sec__head" data-hx-reveal>
			<p class="hx-label hxsc-sec__label">From concept to formula</p>
			<h2 id="sf-proc" class="hx-heading hxsc-sec__title">Every formula earns its place on your skin.</h2>
		</div>
		<ol class="hxsc-stages" role="list" data-hx-stagger>
			<?php foreach ( $stages as $i => $s ) : ?>
			<li data-hx-reveal>
				<span class="hxsc-stages__n" aria-hidden="true"><?php echo esc_html( sprintf( '%02d', $i + 1 ) ); ?></span>
				<h3 class="hxsc-stages__t"><?php echo esc_html( $s[0] ); ?></h3>
				<p class="hx-body"><?php echo hxsc_kses( $s[1] ); ?></p>
			</li>
			<?php endforeach; ?>
		</ol>
	</div>
</section>

<section class="hxsc-sec" aria-labelledby="sf-why">
	<div class="hx-wrap">
		<div class="hxsc-sec__head hxsc-sec__head--wide" data-hx-reveal>
			<p class="hx-label hxsc-sec__label">Why Switzerland</p>
			<h2 id="sf-why" class="hx-heading hxsc-sec__title">Three reasons we would not formulate anywhere else.</h2>
		</div>
		<div class="hxsc-three" data-hx-stagger>
			<article data-hx-reveal>
				<p class="hx-label hxsc-three__k">Regulatory standard</p>
				<h3 class="hx-title hxsc-three__t">The most demanding cosmetic regulatory framework in Europe.</h3>
				<p class="hx-body">Switzerland aligns to EU Regulation 1223/2009 and enforces it through Swissmedic and cantonal authorities. GMP certification is not optional for a laboratory that serves the European market. Every ingredient is documented. Every concentration is justified. Safety is assessed by a qualified assessor, not self-declared. This is the regulatory reality of what “Swiss formulation” means in practice.</p>
			</article>
			<article data-hx-reveal>
				<p class="hx-label hxsc-three__k">Scientific infrastructure</p>
				<h3 class="hx-title hxsc-three__t">Proximity to Europe’s pharmaceutical and life-sciences research base.</h3>
				<p class="hx-body">The Basel–Zurich corridor is home to some of the world’s largest pharmaceutical and biotechnology companies. The cosmetic chemistry talent, raw-material supply chain and analytical laboratory infrastructure that this creates is unmatched. Access to pharmaceutical-grade actives, specialised delivery systems and peer-reviewed formulation science is a practical reality in Zug that it is not in most other manufacturing locations.</p>
			</article>
			<article data-hx-reveal>
				<p class="hx-label hxsc-three__k">Alpine water</p>
				<h3 class="hx-title hxsc-three__t">Ultra-pure Alpine meltwater as the base of every formula.</h3>
				<p class="hx-body">Every Caviar of Switzerland formula is based on Swiss glacier meltwater sourced from the Alpine region. The water is exceptionally pure, low in heavy metals, and naturally elevated in silica and bioavailable trace minerals. This is a measurable difference in the base ingredient that constitutes the largest proportion of any aqueous skincare formula. The base matters.</p>
			</article>
		</div>
	</div>
</section>

<section class="hxsc-sec hxsc-sec--tint" aria-labelledby="sf-docs">
	<div class="hx-wrap hxsc-sec__grid">
		<div class="hxsc-sec__head" data-hx-reveal>
			<p class="hx-label hxsc-sec__label">Documentation &amp; compliance</p>
			<h2 id="sf-docs" class="hx-heading hxsc-sec__title">What every Caviar of Switzerland formula is required to carry.</h2>
			<p class="hx-body hxsc-sec__intro">The following is not a list of aspirational standards. Each item is a legal or contractual requirement of the formulation and manufacturing process.</p>
		</div>
		<div data-hx-reveal>
			<dl class="hxsc-docs">
				<?php foreach ( $docs as $d ) : ?>
				<div><dt><?php echo esc_html( $d[0] ); ?></dt><dd><?php echo esc_html( $d[1] ); ?></dd></div>
				<?php endforeach; ?>
			</dl>
			<p class="hx-detail hxsc-docs__note">This documentation is held on file and can be made available to regulatory authorities upon request. For questions about the formulation or safety of a specific product, please <a href="<?php echo hxsc_url( '/contact/' ); ?>">contact us</a>.</p>
		</div>
	</div>
</section>

<section class="hxsc-sec" aria-labelledby="sf-close">
	<div class="hx-wrap hxsc-sec__grid">
		<div class="hxsc-sec__head" data-hx-reveal>
			<p class="hx-label hxsc-sec__label">The standard in practice</p>
			<h2 id="sf-close" class="hx-heading hxsc-sec__title">The ritual, from the country that takes performance seriously.</h2>
		</div>
		<div class="hxsc-prose" data-hx-reveal>
			<p>The Caviar of Switzerland four-step ritual (Micellar Water Cleanser, Advanced Repair Serum, Revitalizing Eye Cream, 24h Regeneration Cream) is the result of this process applied four times, to four distinct formula objectives, using the same five clinically evidenced actives at concentrations calibrated for each product’s function.</p>
			<p>You are not buying provenance. You are buying the output of a process that Switzerland requires, and that most skincare markets do not.</p>
			<p class="hxsc-ctas"><a class="hx-btn" href="<?php echo hxsc_url( '/shop/' ); ?>">Shop the ritual<?php echo hxsc_arrow(); // phpcs:ignore ?></a> <a class="hx-link" href="<?php echo hxsc_url( '/science/clinical-studies/' ); ?>">View clinical studies<?php echo hxsc_arrow(); // phpcs:ignore ?></a></p>
		</div>
		<dl class="hxsc-facts hxsc-sec__full" data-hx-stagger>
			<div data-hx-reveal><dt>GMP</dt><dd>ISO 22716:2007 certified manufacturing facility, Zug, Switzerland</dd></div>
			<div data-hx-reveal><dt>6</dt><dd>Stages of testing before any formula is approved for production</dd></div>
			<div data-hx-reveal><dt>24 months</dt><dd>Real-time stability testing duration per formula</dd></div>
			<div data-hx-reveal><dt>0</dt><dd>Efficacy claims based on in-house testing: every one is independently tested</dd></div>
		</dl>
	</div>
</section>
