<?php
/**
 * The Ritual — hero (hxrt-hero). One full-bleed photographic plate, type set on the photograph
 * (owner, 24 Oct 2026: "begin like" Sensai's About, Augustinus Bader's Science and Lyma's
 * Science of Youth): the four formulas on the silver tray, the page's one h1 and one line in
 * the lower left, the sticky section bar at the fold. The old "On this page" map left the
 * hero; the bar directly beneath carries the same three parts.
 * Landscape ≥700px: the wide tray (final-tray-wide, products right, open grey left) fills the
 * plate. Phones and portrait tablets: the 4:5 crop (hxrt-tray) dissolves into the
 * photograph's own grey, and the copy continues on that ground.
 */
defined( 'ABSPATH' ) || exit;
?>
<section class="hxrt-hero" aria-labelledby="hxrt-h1">
	<div class="hxrt-hero__media">
		<?php
		// 25 Oct 2026 (owner): the June 2026 shoot, CAV59741 (cream on the cheek), in place of the
		// brand film. Wide: mirrored so the plain wall sits under the lower-left type, with the wall
		// extended (synthesised) to 16:10; phones: the portrait as shot. Built by
		// design-sources/home-2026/build_ritual_cav59741.py (Display P3 -> sRGB).
		echo cos_h26_picture(
			'hxrt-cav-tall',
			array( 640, 960, 1280 ),
			array( 4, 5 ),
			'A woman in profile with a dab of the 24h Regeneration Cream on her cheek',
			array(
				'class' => 'hxrt-hero__pic',
				'sizes' => '100vw',
				'w'     => 960,
				'eager' => true,
				'art'   => array( array( 'media' => '(min-width: 700px) and (orientation: landscape)', 'base' => 'hxrt-cav-wide', 'widths' => array( 1440, 1920, 2560 ) ) ),
			)
		);
		?>
	</div>
	<div class="hxrt-hero__copy">
		<h1 id="hxrt-h1" class="hx-display hxrt-hero__title hx-load-rise" style="--hx-i:1"><span class="hxrt-hero__line">Four formulas.</span> <em class="hxrt-hero__line">One complete ritual.</em></h1>
		<p class="hx-lead hxrt-hero__lede hx-load-rise" style="--hx-i:2">Effective skincare is a sequence, not a single product. Each step prepares the skin for the next, morning and evening.</p>
	</div>
</section>
