<?php
/**
 * /heritage/craftsmanship/ (page 390) — "Not a label. A standard." Styles: assets/css/hx/heritage.css.
 *
 * Copy carried over from post_content (facts unchanged). Two sentences were narrowed so they no longer
 * contradict page 347 (four separate per-product studies; none for the cleanser): the development-cycle
 * list no longer says every product had an independent clinical evaluation, and "Dermatologically tested"
 * describes the published studies instead of "every product in the ritual".
 * Hero: owner photograph DSC_6612 (hxhe-drop). The philosophy-lab image is NOT used (unverified provenance).
 */
defined( 'ABSPATH' ) || exit;

$a = cos_hxhe_arrow();
?>
<section class="hxhe-hero hxhe-hero--split" aria-labelledby="hxhe-title">
	<div class="hx-wrap hxhe-split hxhe-split--hero">
		<div class="hxhe-split__text hxhe-hero__text">
			<p class="hx-label hxhe-hero__label"><span class="hxhe-nw">Craftsmanship &amp; sourcing <span aria-hidden="true">·</span></span> <span class="hxhe-nw">Switzerland</span></p>
			<h1 id="hxhe-title" class="hx-display hxhe-hero__title hx-load-rise" style="--hx-i:1">Not a label. <em>A standard.</em></h1>
			<p class="hx-lead hxhe-hero__lede hx-load-rise" style="--hx-i:2">Every formula is developed and manufactured at a certified Swiss laboratory in Zug. Every active ingredient is sourced with traceability requirements that meet or exceed the standards of pharmaceutical-grade cosmetic production.</p>
		</div>
		<figure class="hxhe-split__media hxhe-hero__media hx-frame hx-load-fade" style="--hx-i:2">
			<?php echo cos_h26_picture( 'hxhe-drop', array( 640, 960, 1280 ), array( 4, 5 ), 'One hand dispensing a drop of the Advanced Repair Serum onto the fingertip of another, against a warm grey ground', array( 'sizes' => '(min-width: 900px) 48vw, 100vw', 'w' => 960, 'eager' => true ) ); // phpcs:ignore ?>
		</figure>
	</div>
</section>

<section class="hxhe-sec hxhe-lab" aria-labelledby="hxhe-lab-title">
	<div class="hx-wrap">
		<header class="hxhe-head" data-hx-stagger>
			<p class="hx-label" data-hx-reveal="fade">The laboratory</p>
			<h2 id="hxhe-lab-title" class="hx-heading hxhe-h2 hxhe-h2--wide" data-hx-reveal>Formulated in Zug, Switzerland. Manufactured to pharmaceutical standards.</h2>
		</header>
		<div class="hxhe-cols" data-hx-stagger>
			<div class="hxhe-prose" data-hx-reveal>
				<p>The Caviar of Switzerland formulation laboratory is located in Zug, the canton that has become synonymous with precision chemistry and advanced cosmetic science. The facility operates under ISO&nbsp;22716 Good Manufacturing Practice (GMP) certification: the international standard for cosmetic production that governs cleanliness, documentation, quality control and batch traceability.</p>
				<p>Every formula is developed by a team of cosmetic chemists and dermatologists whose discipline sits at the intersection of aesthetic medicine and everyday skincare. The development cycle for each product in the ritual exceeded 18 months, including stability testing, compatibility testing and in-use testing.</p>
				<p>The laboratory manufactures in small batches to maintain quality control at every stage. Each batch is tested for stability, microbial safety and active concentration before release. No batch leaves the facility without passing all parameters.</p>
			</div>
			<div class="hxhe-prose" data-hx-reveal>
				<p>We do not outsource formulation to a contract manufacturer and apply a Swiss label after the fact. The intellectual property of the <a href="<?php echo esc_url( home_url( '/science/high-tech-complex/' ) ); ?>">High-Tech Complex</a> (the specific concentrations, the encapsulation technologies, the compatibility engineering that makes five actives stable in a single formula) belongs to Caviar of Switzerland and is developed and protected at the Zug facility.</p>
				<p>This distinction matters because it is the source of the clinical efficacy. A formula can contain the correct actives on paper and still deliver no clinical result if the concentrations are wrong, if the delivery mechanism is unsuitable, or if the actives degrade before they reach the skin. Our chemists control all of these variables because they control the full formulation process.</p>
			</div>
		</div>
	</div>
</section>

<section class="hxhe-source" aria-labelledby="hxhe-source-title">
	<figure class="hxhe-source__plate hx-frame" data-hx-reveal="settle">
		<?php echo cos_h26_picture( 'caviar-macro-light', array( 960, 1440, 2400 ), array( 3, 2 ), 'Caviar pearls in macro, translucent and backlit on a pale ground', array( 'sizes' => '100vw', 'w' => 1440 ) ); // phpcs:ignore ?>
	</figure>
	<div class="hx-wrap hxhe-sec hxhe-source__body">
		<header class="hxhe-head" data-hx-stagger>
			<p class="hx-label" data-hx-reveal="fade">Caviar extract sourcing</p>
			<h2 id="hxhe-source-title" class="hx-heading hxhe-h2" data-hx-reveal>Traceable from aquaculture to formula. No exceptions.</h2>
		</header>
		<div class="hxhe-source__grid">
			<div class="hxhe-prose" data-hx-reveal>
				<p>The Precious Caviar Extract in the High-Tech Complex is derived from <i>Acipenser baerii</i> (Siberian) sturgeon, sourced exclusively from certified responsible aquaculture facilities. We do not use wild-caught sturgeon. We do not use species listed on the CITES endangered species register. Every batch of caviar extract is traceable to its source facility, its harvest date and its processing conditions.</p>
				<p>The aquaculture partners we work with operate under the principle of regenerative farming: the roe is harvested after natural spawning without harm to the fish. The sturgeon are maintained in conditions that meet the highest animal welfare standards in the EU aquaculture certification framework. We audit these facilities annually.</p>
				<p>The extraction process preserves the full-spectrum phospholipid, amino acid and vitamin profile that makes caviar biologically active on human skin. The extraction technology is proprietary to our laboratory partner. The result is an extract that contains the intact cellular intelligence of the roe: not a fraction of it, not a standardised surrogate, but the complete molecular profile that gives the ingredient its clinical relevance.</p>
				<p class="hxhe-links"><a class="hx-link" href="<?php echo esc_url( home_url( '/science/ingredients/caviar-extract/' ) ); ?>">Caviar extract, in depth<?php echo $a; // phpcs:ignore ?></a></p>
			</div>
			<figure class="hxhe-quote hxhe-quote--large" data-hx-reveal>
				<blockquote><p>“Every batch of caviar extract is traceable to its source facility, its harvest date and its processing conditions.”</p></blockquote>
			</figure>
		</div>
	</div>
</section>

<section class="hxhe-sec hxhe-certs" aria-labelledby="hxhe-certs-title">
	<div class="hx-wrap">
		<header class="hxhe-head" data-hx-stagger>
			<p class="hx-label" data-hx-reveal="fade">Quality standards</p>
			<h2 id="hxhe-certs-title" class="hx-heading hxhe-h2" data-hx-reveal>The certifications that govern our process.</h2>
		</header>
		<ul class="hxhe-certs__list" data-hx-stagger>
			<li class="hxhe-cert" data-hx-reveal>
				<p class="hxhe-cert__mark">ISO&nbsp;22716</p>
				<h3 class="hxhe-cert__title">GMP certified</h3>
				<p class="hxhe-cert__body">Good Manufacturing Practice certification covers cleanliness protocols, batch documentation, quality control testing, and traceability from raw material to finished product.</p>
			</li>
			<li class="hxhe-cert" data-hx-reveal>
				<p class="hxhe-cert__mark">EU</p>
				<h3 class="hxhe-cert__title">Cosmetics Regulation compliant</h3>
				<p class="hxhe-cert__body">All formulas comply with Regulation (EC) No&nbsp;1223/2009 on cosmetic products. Every ingredient is on the EU approved list. Every claim is substantiated.</p>
			</li>
			<li class="hxhe-cert" data-hx-reveal>
				<p class="hxhe-cert__mark">Independent</p>
				<h3 class="hxhe-cert__title">Dermatologically tested</h3>
				<p class="hxhe-cert__body">The clinical results published on this site come from independent studies with dermatologist evaluation, not internal studies. <a href="<?php echo esc_url( home_url( '/science/clinical-studies/' ) ); ?>">See the clinical studies</a>.</p>
			</li>
		</ul>
	</div>
</section>
<?php
cos_hxhe_continue( 'craftsmanship' );
