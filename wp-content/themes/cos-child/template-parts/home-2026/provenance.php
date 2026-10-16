<?php
/**
 * 05 — Swiss provenance (homepage rebuild, 14–15 Oct 2026). Styles: assets/css/hx/provenance.css (hxp-).
 *
 * Owner decision (14 Oct): keep the Matterhorn — the only landscape photography there is — refined,
 * never a postcard. Round 1 critique (15 Oct): the summit must be unmistakable and the field must sit
 * in the warm sequence. So: a tight crop on the north/east face with cloud streaming off it
 * (design-sources/home-2026/build_provenance_matterhorn.py), a full-range silver print with a slight
 * split tone (cool graphite shadows, warm ivory highlights). Only the label, the plate caption and the
 * heading sit on the photograph (owner: text on the photo); the reading copy, links and facts sit on a
 * warm ivory band directly beneath it — a clean edge, no fade. The caption names the place, so the
 * mountain is never mistaken for Zug, where the formulas are made.
 *
 * Facts (brief §4, PRODUCT.md, page 348): created and filled in Zug, for more than 20 years,
 * under cosmetic GMP / ISO 22716. No parallax drift here (it exposed the frame edge — round 1 seam).
 * Restored 16 Oct to the round-3-approved state (owner) after an unrequested evening rewrite.
 */
defined( 'ABSPATH' ) || exit;

$hxp_arrow = '<span class="hx-arrow" aria-hidden="true"></span>';
?>
<section id="heritage" class="hxp" aria-labelledby="hxp-title">
	<div class="hxp__plate">
		<figure class="hxp__photo hx-frame" data-hx-reveal="settle">
			<?php
			echo cos_h26_picture(
				'provenance-summit-tall',
				array( 640, 960 ),
				array( 1100, 1540 ),
				'The north and east faces of the Matterhorn, cloud streaming from the summit, printed in silver tones',
				array(
					'sizes' => '100vw',
					'w'     => 960,
					'art'   => array(
						array( 'media' => '(min-width: 760px)', 'base' => 'provenance-summit-wide', 'widths' => array( 1440, 1920 ), 'sizes' => '100vw' ),
					),
				)
			);
			?>
			<figcaption class="hxp__caption"><span class="hxp__place">Matterhorn<span class="hxp__region">, Valais</span></span> <span class="hxp__alt">4&#8239;478&nbsp;m</span></figcaption>
		</figure>

		<div class="hxp__over hx-wrap" data-hx-stagger>
			<p class="hx-label hxp__label" data-hx-reveal="fade">Swiss provenance</p>
			<h2 id="hxp-title" class="hx-heading hxp__title" data-hx-reveal>Fewer things, made&nbsp;well.</h2>
		</div>
	</div>

	<div class="hxp__band hx-wrap">
		<div class="hxp__intro" data-hx-stagger>
			<p class="hx-lead hxp__lede" data-hx-reveal>Precision is not a style here; it is how the work is done. Each formula is made in one place, patiently, and with care.</p>
			<p class="hxp__links" data-hx-reveal>
				<a class="hx-link" href="<?php echo esc_url( home_url( '/heritage/' ) ); ?>">Our Swiss heritage<?php echo $hxp_arrow; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a>
				<a class="hx-link" href="<?php echo esc_url( home_url( '/science/swiss-formulation/' ) ); ?>">How we formulate in Zug<?php echo $hxp_arrow; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a>
			</p>
		</div>

		<dl class="hxp__facts" data-hx-stagger>
			<div class="hxp__fact" data-hx-reveal>
				<dt>Zug, Switzerland</dt>
				<dd>Where every formula is created and filled</dd>
			</div>
			<div class="hxp__fact" data-hx-reveal>
				<dt>20+&nbsp;years</dt>
				<dd>Making skincare in Zug</dd>
			</div>
			<div class="hxp__fact" data-hx-reveal>
				<dt>ISO&nbsp;22716</dt>
				<dd>Good manufacturing practice (GMP) for cosmetics</dd>
			</div>
		</dl>
	</div>
</section>
