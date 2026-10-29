<?php
/**
 * /heritage/sustainability/ (page 391) — "Luxury without compromise." Styles: assets/css/hx/heritage.css.
 *
 * Copy carried over from post_content: the five commitments (incl. the 2026 refill pilot, stated as
 * not yet operational) and "Honesty about limits", tightened. No claim added; several await the owner.
 * Critic round 1 (17 Oct): the valley is now a warm-monochrome full-bleed plate under the title (the
 * page's one signature image; atmosphere only, uncaptioned); "Honesty about limits" is a two-column
 * statement without the low-resolution box photograph. Two eyebrows only.
 */
defined( 'ABSPATH' ) || exit;
?>
<?php
/*
 * Opening (25 Oct 2026, owner: "layout this page like the rest with the hero image first"): the
 * heritage hub's bleed plate. The valley fills the first screen under the transparent header,
 * the h1 and lead in ink at the lower left on a soft pearl wash; phones: the tall crop on a clean
 * edge, type beneath. The eyebrow left with the old layout, as on the hub.
 */
?>
<header class="hxhe-hero hxhe-hero--bleed hxhe-hero--dark" aria-labelledby="hxhe-title">
	<figure class="hxhe-hero__plate hx-load-fade">
		<?php
		echo cos_h26_picture(
			'hxhe-sustain-tall',
			array( 640, 960, 1280 ),
			array( 4, 5 ),
			'The four formulas and the cream jar on dark marble among broad green leaves, with a jade roller and a gua sha stone',
			array(
				'sizes' => '100vw',
				'w'     => 960,
				'eager' => true,
				'art'   => array(
					array( 'media' => '(min-width: 700px) and (orientation: landscape)', 'base' => 'hxhe-sustain-wide', 'widths' => array( 1440, 1707 ), 'sizes' => '100vw' ),
				),
			)
		);
		?>
	</figure>
	<div class="hx-wrap hxhe-hero__text">
		<h1 id="hxhe-title" class="hx-display hxhe-hero__title hx-load-rise" style="--hx-i:1">Luxury without <em>compromise.</em></h1>
		<p class="hx-lead hxhe-hero__lede hx-load-rise" style="--hx-i:2">Sustainability is not a communications strategy. It is a set of operational decisions with measurable outcomes. We report what we do, not what we aspire to.</p>
	</div>
</header>

<section class="hxhe-sec hxhe-commit" aria-labelledby="hxhe-commit-title">
	<div class="hx-wrap">
		<p class="hx-label" data-hx-reveal="fade">Our commitments</p>
		<h2 id="hxhe-commit-title" class="hx-heading hxhe-h2 hxhe-commit__title" data-hx-reveal>What we do. Measurably.</h2>
		<ol class="hxhe-commit__list" data-hx-stagger>
			<li class="hxhe-commitment" data-hx-reveal>
				<p class="hxhe-num">01</p>
				<h3 class="hx-title">Responsible aquaculture</h3>
				<p>No wild-caught species, no endangered varieties, annual facility audits, and documented animal welfare standards.</p>
			</li>
			<li class="hxhe-commitment" data-hx-reveal>
				<p class="hxhe-num">02</p>
				<h3 class="hx-title">Recyclable packaging</h3>
				<p>All primary and secondary packaging is designed for recyclability in EU waste streams. No single-use plastic in our shipping materials since 2024.</p>
			</li>
			<li class="hxhe-commitment" data-hx-reveal>
				<p class="hxhe-num">03</p>
				<h3 class="hx-title">No animal testing</h3>
				<p>Formulated and tested without animal testing, as EU Cosmetics Regulation requires. Our supply chain partners are contractually required to confirm compliance.</p>
			</li>
			<li class="hxhe-commitment" data-hx-reveal>
				<p class="hxhe-num">04</p>
				<h3 class="hx-title">Local Swiss manufacturing</h3>
				<p>Made in Zug, on a short supply chain. We do not manufacture in lower-cost regions for margin reasons.</p>
			</li>
			<li class="hxhe-commitment" data-hx-reveal>
				<p class="hxhe-num">05 <span class="hxhe-num__note">· In development</span></p>
				<h3 class="hx-title">Refill programme</h3>
				<p>Refill formats for the 24h Regeneration Cream and Micellar Water Cleanser. Piloting with subscription clients in Q4&nbsp;2026.</p>
			</li>
		</ol>
	</div>
</section>

<section class="hxhe-sec hxhe-limits" aria-labelledby="hxhe-limits-title">
	<div class="hx-wrap hxhe-statement hxhe-statement--split">
		<h2 id="hxhe-limits-title" class="hx-heading hxhe-h2" data-hx-reveal>Honesty about limits.</h2>
		<div class="hxhe-prose hxhe-limits__prose" data-hx-reveal>
			<p class="hx-lead">We are not a net-zero business. Our manufacturing has an energy footprint; the glass and aluminium in our packaging carry an embedded carbon cost, and international shipping adds to it. We do not claim otherwise.</p>
			<p>Every commitment on this page is operational, not aspirational. If it is not operational yet, we say so, as with the refill programme. We will update this page annually with measurable progress, and if something we committed to is not achieved, we will report that too.</p>
		</div>
	</div>
</section>
<?php
cos_hxhe_continue( 'sustainability' );
