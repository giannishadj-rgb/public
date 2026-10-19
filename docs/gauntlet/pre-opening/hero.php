<?php
/**
 * 01 — Hero (17 Oct 2026 luxury pass, prefix hxh-).
 *
 * The 24h Regeneration Cream as a jewellery campaign: the owner's photograph
 * (hero-jar_master.png) with its silk environment regraded to pearl and extended
 * (design-sources/home-2026/build_hxh_pearl.py). The product pixels (jar, cream, lid,
 * Swiss cross, reflections) are untouched; see assets/images/home-2026/hxh.origin.json.
 * Two art-directed frames: hxh-wide (>=600px; landscape: object right of centre,
 * headline in the calm field to its left) and hxh-tall (phones: object high in the
 * frame, words and the action on the reflective floor below it, in thumb reach).
 *
 * The <picture> media query and sizes below must match the LCP preload in
 * inc/home-2026.php ($hxh_wide_media, $hxh_*_sizes). Frame ratios are unchanged.
 *
 * `h26-hero--pearl` is kept only as the hook for the graphite-on-light transparent
 * header in home-2026-nocturne.css; hero.css neutralises its legacy section styles.
 */
defined( 'ABSPATH' ) || exit;

$hxh_wide_media = '(min-width: 600px)'; // tablets (both orientations) and up; the tall frame is for phones only
$hxh_wide_sizes = '(min-aspect-ratio: 237/100) 100vw, 237vh';   // frame is 2632x1111 (2.37:1), covers by height
$hxh_tall_sizes = '(max-aspect-ratio: 518/1000) 51.8vh, 100vw'; // frame is 720x1391 (0.518:1)
$hxh_alt        = 'The 24h Regeneration Cream, open and filled with white cream, its polished chrome lid with the Swiss cross resting against the jar, reflected on a pale pearl silk surface';
?>
<section class="hxh h26-hero--pearl" aria-labelledby="hxh-title">
	<div class="hxh__media">
		<div class="hxh__drift">
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
	</div>

	<div class="hxh__inner">
		<div class="hxh__copy">
			<h1 id="hxh-title" class="hxh__title hx-display">
				<span class="hxh__line">The Swiss art of</span>
				<span class="hxh__line"><em>caviar skincare</em></span>
			</h1>
			<?php /* Swiss development/manufacture: supplied 2021 brand presentation, page 4.
			   Specific manufacturing city is omitted pending source reconciliation. */ ?>
			<p class="hxh__lead"><span class="hxh__ln">Four caviar formulas, one complete&nbsp;ritual.</span> <span class="hxh__ln">Developed and made in&nbsp;Switzerland.</span></p>
		</div>
		<div class="hxh__action">
			<a class="hx-btn hxh__cta" href="<?php echo esc_url( home_url( '/products/the-complete-ritual/' ) ); ?>" data-cro-cta="home-hero-primary">Shop the Complete Ritual</a>
		</div>
		<a class="hx-link hxh__explore" href="#collection" data-cro-cta="home-hero-collection">Explore the four formulas<span class="hx-arrow" aria-hidden="true"></span></a>
	</div>
</section>
