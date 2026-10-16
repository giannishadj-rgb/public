<?php
/**
 * /heritage/sustainability/ (page 391) — "Luxury without compromise." Styles: assets/css/hx/heritage.css.
 *
 * Copy carried over from post_content word for word in substance: the five commitments (incl. the
 * 2026 refill pilot, stated as not yet operational) and "Honesty about limits". No claim added.
 * The valley photograph is atmosphere only (uncaptioned); the box is the Complete Ritual packaging.
 */
defined( 'ABSPATH' ) || exit;
?>
<section class="hxhe-hero hxhe-hero--split" aria-labelledby="hxhe-title">
	<div class="hx-wrap hxhe-split hxhe-split--hero">
		<div class="hxhe-split__text hxhe-hero__text">
			<p class="hx-label hxhe-hero__label"><span class="hxhe-nw">Sustainability <span aria-hidden="true">·</span></span> <span class="hxhe-nw">Responsible practice</span></p>
			<h1 id="hxhe-title" class="hx-display hxhe-hero__title hx-load-rise" style="--hx-i:1">Luxury without <em>compromise.</em></h1>
			<p class="hx-lead hxhe-hero__lede hx-load-rise" style="--hx-i:2">Sustainability is not a communications strategy. It is a set of operational decisions with measurable outcomes. We report what we do, not what we aspire to.</p>
		</div>
		<figure class="hxhe-split__media hxhe-hero__media hx-frame hx-load-fade" style="--hx-i:2">
			<?php echo cos_h26_picture( 'hxhe-valley-tall', array( 640, 960, 1280 ), array( 4, 5 ), 'An Alpine valley: dark forest, a mountain stream and snow-dusted peaks under a pale sky', array( 'sizes' => '(min-width: 900px) 48vw, 100vw', 'w' => 960, 'eager' => true ) ); // phpcs:ignore ?>
		</figure>
	</div>
</section>

<section class="hxhe-sec hxhe-commit" aria-labelledby="hxhe-commit-title">
	<div class="hx-wrap hxhe-commit__grid">
		<header class="hxhe-head hxhe-commit__head" data-hx-stagger>
			<p class="hx-label" data-hx-reveal="fade">Our commitments</p>
			<h2 id="hxhe-commit-title" class="hx-heading hxhe-h2" data-hx-reveal>What we do. Measurably.</h2>
		</header>
		<ol class="hxhe-commit__list" data-hx-stagger>
			<li class="hxhe-commitment" data-hx-reveal>
				<span class="hx-step">01</span>
				<h3 class="hx-title">Responsible aquaculture</h3>
				<p>Our caviar extract is sourced from regenerative aquaculture: no wild-caught species, no endangered varieties, annual facility audits, and documented animal welfare standards.</p>
			</li>
			<li class="hxhe-commitment" data-hx-reveal>
				<span class="hx-step">02</span>
				<h3 class="hx-title">Recyclable packaging</h3>
				<p>All primary and secondary packaging is designed for recyclability in EU waste streams. We have eliminated single-use plastic from our shipping materials since 2024.</p>
			</li>
			<li class="hxhe-commitment" data-hx-reveal>
				<span class="hx-step">03</span>
				<h3 class="hx-title">No animal testing</h3>
				<p>The ritual and all its components are formulated and tested without animal testing. EU Cosmetics Regulation prohibits it. We go further: our supply chain partners are contractually required to confirm compliance.</p>
			</li>
			<li class="hxhe-commitment" data-hx-reveal>
				<span class="hx-step">04</span>
				<h3 class="hx-title">Local Swiss manufacturing</h3>
				<p>Manufacturing in Zug means a short supply chain. Raw materials travel to Switzerland; finished products travel to customers. We do not manufacture in lower-cost regions for margin reasons.</p>
			</li>
			<li class="hxhe-commitment" data-hx-reveal>
				<span class="hx-step">05</span>
				<h3 class="hx-title">Refill programme <span class="hxhe-commitment__note">(2026)</span></h3>
				<p>We are developing refill formats for the 24h Regeneration Cream and Micellar Water Cleanser. Piloting with subscription clients in Q4&nbsp;2026.</p>
			</li>
		</ol>
	</div>
</section>

<section class="hxhe-sec hxhe-limits" aria-labelledby="hxhe-limits-title">
	<div class="hx-wrap hxhe-split hxhe-split--image-right">
		<div class="hxhe-split__text" data-hx-stagger>
			<p class="hx-label" data-hx-reveal="fade">What we will not do</p>
			<h2 id="hxhe-limits-title" class="hx-heading hxhe-h2" data-hx-reveal>Honesty about limits.</h2>
			<div class="hxhe-prose" data-hx-reveal>
				<p>We are not a net-zero business. Our manufacturing process has an energy footprint. The glass and aluminium in our packaging has an embedded carbon cost, and international shipping adds to it. We do not claim otherwise.</p>
				<p>We believe that honesty about what we are doing, and what we are not yet able to do, is more useful than aspirational language that overstates our current position. Every commitment listed on this page is operational, not aspirational. If it is not operational yet, we say so explicitly (as with the refill programme).</p>
				<p>We will update this page annually with measurable progress. If something we committed to is not achieved, we will report that too.</p>
			</div>
		</div>
		<figure class="hxhe-split__media hx-frame" data-hx-reveal="mask">
			<?php echo cos_h26_picture( 'hxhe-box', array( 640, 864 ), array( 864, 1080 ), 'The white Caviar of Switzerland presentation box with its red inner edge, standing on a grey ground', array( 'sizes' => '(min-width: 900px) 42vw, 100vw', 'w' => 864 ) ); // phpcs:ignore ?>
		</figure>
	</div>
</section>
<?php
cos_hxhe_continue( 'sustainability' );
