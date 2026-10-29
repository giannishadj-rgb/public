<?php
/**
 * /heritage/craftsmanship/ (page 390) — "Not a label. A standard." Styles: assets/css/hx/heritage.css.
 *
 * Copy carried over from post_content (facts unchanged). Narrowed so nothing contradicts page 347
 * (four separate per-product studies; none for the cleanser). Critic round 1 (17 Oct): cut by ~40%:
 * two laboratory paragraphs (incl. the 18-month cycle), the extraction paragraph ("cellular
 * intelligence of the roe" removed on purpose) and the duplicated pull quote; the caviar macro is the
 * page's one full-bleed plate, with the sourcing heading set on it. Two eyebrows only.
 * Hero: owner photograph DSC_6612 (hxhe-drop). The philosophy-lab image is NOT used (unverified provenance).
 */
defined( 'ABSPATH' ) || exit;

$a = cos_hxhe_arrow();
?>
<?php
/* Opening (25 Oct 2026, owner: "hero image first", like the hub and sustainability): the bleed
   plate. The owner's serum photograph fills the first screen; the h1 and lead in ink on its warm
   grey ground at the lower left (phones: the photograph, then the type beneath). */
?>
<header class="hxhe-hero hxhe-hero--bleed hxhe-hero--drop" aria-labelledby="hxhe-title">
	<figure class="hxhe-hero__plate hx-load-fade">
		<?php echo cos_h26_picture( 'hxhe-craft', array( 640, 960, 1280 ), array( 4, 5 ), 'Two hands holding the Micellar Water All-in-One Cleanser, a lather of foam on the fingers, against a grey ground', array( 'sizes' => '100vw', 'w' => 960, 'eager' => true ) ); // phpcs:ignore ?>
	</figure>
	<div class="hx-wrap hxhe-hero__text">
		<h1 id="hxhe-title" class="hx-display hxhe-hero__title hx-load-rise" style="--hx-i:1">Not a label. <em>A standard.</em></h1>
		<p class="hx-lead hxhe-hero__lede hx-load-rise" style="--hx-i:2">Every formula is developed and manufactured at a certified Swiss laboratory in Zug. Every active ingredient is sourced with traceability requirements that meet or exceed the standards of pharmaceutical-grade cosmetic production.</p>
	</div>
</header>

<section class="hxhe-sec hxhe-lab" aria-labelledby="hxhe-lab-title">
	<div class="hx-wrap hxhe-statement">
		<h2 id="hxhe-lab-title" class="hx-heading hxhe-h2 hxhe-h2--wide" data-hx-reveal>Formulated in Zug, Switzerland. Manufactured to pharmaceutical standards.</h2>
		<div class="hxhe-cols" data-hx-stagger>
			<div class="hxhe-prose" data-hx-reveal>
				<p>Our formulation laboratory is in Zug, the canton synonymous with precision chemistry and advanced cosmetic science. It operates under ISO&nbsp;22716 Good Manufacturing Practice (GMP) certification: the international standard for cosmetic production that governs cleanliness, documentation, quality control and batch traceability.</p>
				<p>We manufacture in small batches. Each batch is tested for stability, microbial safety and active concentration before release; none leaves the facility without passing all parameters.</p>
			</div>
			<div class="hxhe-prose" data-hx-reveal>
				<p>We do not outsource formulation to a contract manufacturer and apply a Swiss label after the fact. The intellectual property of the <a href="<?php echo esc_url( home_url( '/science/high-tech-complex/' ) ); ?>">High-Tech Complex</a> (its concentrations, its encapsulation technologies, the engineering that keeps five actives stable in a single formula) belongs to Caviar of Switzerland and is developed and protected in Zug.</p>
			</div>
		</div>
	</div>
</section>

<section class="hxhe-source" aria-labelledby="hxhe-source-title">
	<div class="hxhe-plate">
		<figure class="hxhe-plate__photo hx-frame" data-hx-reveal="settle">
			<?php
			echo cos_h26_picture(
				'caviar-backlit',
				array( 960, 1440, 1920, 2400 ),
				array( 3, 2 ),
				'Caviar pearls in macro, translucent and backlit on a pale ground',
				array( 'sizes' => '100vw', 'w' => 1440 )
			);
			?>
		</figure>
		<div class="hx-wrap hxhe-plate__over" data-hx-stagger>
			<p class="hx-label hxhe-plate__label" data-hx-reveal="fade">Caviar extract sourcing</p>
			<h2 id="hxhe-source-title" class="hx-heading hxhe-plate__title" data-hx-reveal>Traceable from aquaculture to formula. No&nbsp;exceptions.</h2>
		</div>
	</div>
	<div class="hx-wrap hxhe-sec hxhe-source__body">
		<div class="hxhe-cols" data-hx-stagger>
			<div class="hxhe-prose" data-hx-reveal>
				<p>The caviar extract in the High-Tech Complex is derived from <i>Acipenser baerii</i> (Siberian) sturgeon, sourced exclusively from certified responsible aquaculture facilities. We do not use wild-caught sturgeon. We do not use species listed on the CITES endangered species register. Every batch is traceable to its source facility, its harvest date and its processing conditions.</p>
			</div>
			<div class="hxhe-prose" data-hx-reveal>
				<p>Our aquaculture partners operate under the principle of regenerative farming: the roe is harvested after natural spawning without harm to the fish, in conditions that meet the highest animal welfare standards in the EU aquaculture certification framework. We audit these facilities annually.</p>
				<p class="hxhe-links"><a class="hx-link" href="<?php echo esc_url( home_url( '/science/ingredients/caviar-extract/' ) ); ?>">Caviar extract, in depth<?php echo $a; // phpcs:ignore ?></a></p>
			</div>
		</div>
	</div>
</section>

<section class="hxhe-sec hxhe-certs" aria-labelledby="hxhe-certs-title">
	<div class="hx-wrap">
		<h2 id="hxhe-certs-title" class="hx-heading hxhe-h2 hxhe-certs__title" data-hx-reveal>The standards that govern our process.</h2>
		<ul class="hxhe-certs__list" data-hx-stagger>
			<li class="hxhe-cert" data-hx-reveal>
				<p class="hxhe-cert__mark">ISO&nbsp;22716</p>
				<p class="hxhe-cert__body"><strong>GMP certified.</strong> Cleanliness protocols, batch documentation, quality control testing, and traceability from raw material to finished product.</p>
			</li>
			<li class="hxhe-cert" data-hx-reveal>
				<p class="hxhe-cert__mark">EU</p>
				<p class="hxhe-cert__body"><strong>Cosmetics Regulation compliant.</strong> All formulas comply with Regulation (EC) No&nbsp;1223/2009. Every ingredient is on the EU approved list. Every claim is substantiated.</p>
			</li>
			<li class="hxhe-cert" data-hx-reveal>
				<p class="hxhe-cert__mark">Independent</p>
				<p class="hxhe-cert__body"><strong>Dermatologically tested.</strong> The clinical results published on this site come from independent studies with dermatologist evaluation, not internal studies. <a href="<?php echo esc_url( home_url( '/science/clinical-studies/' ) ); ?>">See the clinical studies</a>.</p>
			</li>
		</ul>
	</div>
</section>
<?php
cos_hxhe_continue( 'craftsmanship' );
