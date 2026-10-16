<?php
/**
 * 01 — Hero (14 Oct 2026 rebuild, prefix hxh-).
 *
 * The 24h Regeneration Cream as a jewellery campaign: the owner's photograph
 * (hero-jar_master.png) with its silk environment extended for negative space
 * (design-sources/home-2026/build_hxh_hero.py). The product pixels are untouched.
 * Two art-directed frames: hxh-wide (>=600px; on landscape screens the object sits
 * right of centre with the headline in the calm field to its left, on portrait
 * tablets it is centred under the headline) and hxh-tall (phones: headline above the
 * object, the action on the reflective floor below it).
 *
 * The <picture> media query and sizes below must match the LCP preload in
 * inc/home-2026.php ($hxh_wide_media, $hxh_*_sizes).
 *
 * `h26-hero--pearl` is kept only as the hook for the graphite-on-light transparent
 * header in home-2026-nocturne.css; hero.css neutralises its legacy section styles.
 */
defined( 'ABSPATH' ) || exit;

$hxh_wide_media = '(min-width: 600px)'; // tablets (both orientations) and up; the tall frame is for phones only
$hxh_wide_sizes = '(min-aspect-ratio: 237/100) 100vw, 237vh';   // frame is 2632x1111 (2.37:1), covers by height
$hxh_tall_sizes = '(max-aspect-ratio: 518/1000) 51.8vh, 100vw'; // frame is 720x1391 (0.518:1)
$hxh_alt        = 'The 24h Regeneration Cream, open and filled with white cream, its polished chrome lid with the Swiss cross resting against the jar, reflected on a pale champagne silk surface';
?>
<section class="hxh h26-hero--pearl" aria-labelledby="hxh-title">
	<div class="hxh__media">
		<div class="hxh__drift" data-hx-drift="0.07">
			<?php
			echo cos_h26_picture(
				'hxh-tall',
				array( 540, 720 ),
				array( 720, 1391 ),
				$hxh_alt,
				array(
					'class' => 'hxh__picture',
					'eager' => true,
					'sizes' => $hxh_tall_sizes,
					'w'     => 720,
					'art'   => array(
						array( 'media' => $hxh_wide_media, 'base' => 'hxh-wide', 'widths' => array( 1440, 1920, 2560 ), 'sizes' => $hxh_wide_sizes ),
					),
				)
			);
			?>
		</div>
		<span class="hxh__veil" aria-hidden="true"></span>
	</div>

	<div class="hxh__inner">
		<div class="hxh__copy">
			<h1 id="hxh-title" class="hxh__title hx-display">
				<span class="hxh__line hx-load-rise" style="--hx-i:3">The Swiss art of</span>
				<span class="hxh__line hx-load-rise" style="--hx-i:4"><em>caviar skincare</em></span>
			</h1>
			<p class="hxh__lead hx-load-rise" style="--hx-i:6">Four caviar formulas. One complete&nbsp;ritual.</p>
			<?php /* Round 1 critique: one quiet line for "premium science". Facts: BRIEF §4 — made in
			   Zug; serum (cutometry) and eye cream (optical profilometry) results are instrument-measured. */ ?>
			<p class="hxh__credit hx-load-rise" style="--hx-i:7">Made&nbsp;in&nbsp;Zug<span class="hxh__sep">&nbsp;· </span>Instrument&#8209;measured&nbsp;results</p>
		</div>
		<div class="hxh__action hx-load-rise" style="--hx-i:8">
			<a class="hx-btn hxh__cta" href="<?php echo esc_url( home_url( '/products/the-complete-ritual/' ) ); ?>" data-cro-cta="home-hero-primary">Shop the Complete Ritual<span class="hx-arrow" aria-hidden="true"></span></a>
		</div>
	</div>
</section>
