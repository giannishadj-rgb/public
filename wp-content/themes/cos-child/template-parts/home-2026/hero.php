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

// 19 Oct 2026: the jar now sits in a built ivory studio set (wc-hero-*, build_wc_hero.py; the jar,
// lid and floor reflection are the owner photograph, pixel-identical, see wc-hero.origin.json).
// Art direction switches by shape, not width: landscape screens get the wide frame, portrait
// screens (phones and portrait tablets) the tall one.
$hxh_wide_media = '(min-aspect-ratio: 1/1)';
$hxh_wide_sizes = '(min-aspect-ratio: 1969/1000) 100vw, 196.9vh'; // frame is 2560x1300 (1.969:1), covers by height
$hxh_tall_sizes = '(max-aspect-ratio: 6/13) 46.16vh, 100vw';      // frame is 1080x2340 (0.4615:1)
$hxh_alt        = 'The 24h Regeneration Cream, open and filled with white cream, its polished chrome lid with the Swiss cross resting against the jar, reflected on a pale ivory studio floor';
?>
<section class="hxh h26-hero--pearl" aria-labelledby="hxh-title">
	<div class="hxh__media">
		<div class="hxh__drift" data-hx-drift="0.07">
			<?php
			echo cos_h26_picture(
				'wc-hero-tall',
				array( 640, 860, 1080 ),
				array( 1080, 2340 ),
				$hxh_alt,
				array(
					'class' => 'hxh__picture',
					'eager' => true,
					'sizes' => $hxh_tall_sizes,
					'w'     => 1080,
					'art'   => array(
						array( 'media' => $hxh_wide_media, 'base' => 'wc-hero-wide', 'widths' => array( 1440, 1920, 2560 ), 'sizes' => $hxh_wide_sizes ),
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
				<span class="hxh__line hx-load-rise" style="--hx-i:3">Swiss skincare,</span>
				<span class="hxh__line hx-load-rise" style="--hx-i:4"><em>refined to the essential.</em></span>
			</h1>
			<?php /* 19 Oct 2026 (owner): headline is the brand positioning line; the provenance/evidence
			   facts line was removed from the hero — the science and heritage sections carry those. */ ?>
			<p class="hxh__lead hx-load-rise" style="--hx-i:6">Four caviar formulas. One complete&nbsp;ritual.</p>
		</div>
		<div class="hxh__action hx-load-rise" style="--hx-i:8">
			<a class="hx-btn hxh__cta" href="<?php echo esc_url( home_url( '/products/the-complete-ritual/' ) ); ?>" data-cro-cta="home-hero-primary">Shop the Complete Ritual<span class="hx-arrow" aria-hidden="true"></span></a>
		</div>
	</div>
</section>

