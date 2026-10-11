<?php
/**
 * 06b — Swiss heritage (owner, 11 Oct 2026: "this needs to show the Swiss
 * heritage — change the style, image and text"). A full-bleed plate of the
 * Matterhorn in cloud, cropped above the village so it reads as landscape,
 * not postcard (design-sources/home-2026/build_heritage_matterhorn.py), with
 * the label, the shared section heading and the lede on its calm sky; a small
 * caption names the place, so the photograph is never mistaken for Zug.
 * Below, a Swiss-grid band: three facts as large serif figures on hairlines.
 * Facts only from PRODUCT.md (20+ years, confirmed) and /science/swiss-formulation/
 * (page 348: GMP / ISO 22716:2007 in Zug, batch release and traceability).
 * Styles: .h26h in assets/css/home-2026.css.
 */
defined( 'ABSPATH' ) || exit;

$facts = array(
	array( '20+', 'Years', 'A Swiss skincare house for more than two decades.' ),
	array( 'Zug', 'Switzerland', 'Every formula developed and produced here, from formulation to final fill.' ),
	array( 'ISO 22716', 'GMP-certified', 'Every batch checked before release. Every jar and bottle traceable.' ),
);
?>
<section class="h26h" aria-labelledby="h26-provenance-title">
	<figure class="h26h__plate">
		<div class="h26h__photo" data-h26-parallax=".05">
			<?php
			echo cos_h26_picture(
				'heritage-alps-tall',
				array( 640, 960 ),
				array( 4, 5 ),
				'The Matterhorn rising through cloud above a forested ridge in the Swiss Alps',
				array(
					'sizes' => '100vw',
					'w'     => 960,
					'art'   => array(
						array( 'media' => '(min-width: 900px)', 'base' => 'heritage-alps-wide', 'widths' => array( 1440, 1920, 2400 ), 'sizes' => '100vw' ),
					),
				)
			);
			?>
		</div>
		<div class="h26h__copy">
			<p class="h26h__label">Swiss heritage</p>
			<h2 id="h26-provenance-title" class="h26-provenance__title">A Swiss house, made in Zug</h2>
			<p class="h26h__lede">For more than 20 years, Caviar of Switzerland has developed and produced every formula in Zug, from formulation to final fill.</p>
		</div>
		<figcaption class="h26h__caption">The Matterhorn, Valais</figcaption>
	</figure>
	<div class="h26h__band">
		<dl class="h26h__facts">
			<?php foreach ( $facts as [ $fig, $k, $v ] ) : ?>
			<div class="h26h__fact">
				<dt><span class="h26h__fig"><?php echo esc_html( $fig ); ?></span><span class="h26h__key"><?php echo esc_html( $k ); ?></span></dt>
				<dd><?php echo esc_html( $v ); ?></dd>
			</div>
			<?php endforeach; ?>
		</dl>
		<p class="h26h__links">
			<a class="h26-link" href="<?php echo esc_url( home_url( '/heritage/' ) ); ?>">Our Swiss heritage <?php echo cos_h26_arrow(); ?></a>
			<a class="h26-link" href="<?php echo esc_url( home_url( '/science/swiss-formulation/' ) ); ?>">How we formulate in Zug <?php echo cos_h26_arrow(); ?></a>
		</p>
	</div>
</section>
