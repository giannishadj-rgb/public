<?php
/**
 * 06 — Clinical evidence as measurement callouts (owner brief, 10 Oct 2026:
 * annotated-photograph references; a single model, no product in shot; the
 * text is part of the picture, no colour band above or below it).
 * The two instrument results are pinned to the skin they describe on the
 * DSC_4392 portrait: firmness to a cheek, crow's-feet depth to an eye corner.
 * Critique 11 Oct 2026: the 24h cream's self-reported satisfaction measures no
 * place on the face, so it is no longer pinned; it is a separate secondary
 * caption, and the portrait is labelled illustrative. The heading sits in the
 * headroom above her and "Read the studies" on the photo.
 * Desktop (≥1100px): the section is one full-bleed plate (backdrop extended by
 * design-sources/home-2026/build_evidence_portrait.py) with hairline leaders
 * running to figures on the grey. Narrower screens: numbered markers on a 5:7
 * crop, keyed to the figures listed beneath it.
 * Each figure is named with its single product, method and panel — never
 * aggregated into a ritual claim (the cleanser has no study).
 * Source: /science/clinical-studies/ (page 347).
 * Styles: .h26e in assets/css/home-2026.css.
 */
defined( 'ABSPATH' ) || exit;

// figure, finding, product, method, panel, [x,y] on the 5:7 crop, [x,y] on the wide plate, side.
// Coordinates printed by build_evidence_portrait.py from points on the original photo.
$calls = array(
	array( '+47%', 'Skin firmness', 'Advanced Repair Serum', 'Skin-elasticity measurement (cutometry)', '32 women aged 45–60, 28 days', array( 28.75, 68.3 ), array( 40.06, 71.01 ), 'left' ),
	array( '−38%', 'Crow’s-feet depth', 'Revitalizing Eye Cream', 'Skin-surface measurement (optical profilometry)', '30 women aged 42–65, 28 days', array( 68.12, 56.91 ), array( 58.44, 58.7 ), 'right' ),
);
$h26e_pos = static function ( array $c ): string {
	return sprintf( '--x:%s%%;--y:%s%%;--xd:%s%%;--yd:%s%%', $c[5][0], $c[5][1], $c[6][0], $c[6][1] );
};
?>
<section class="h26e" aria-labelledby="h26-trust-title">
	<div class="h26e__plate h26-reveal" data-h26-parallax="-.05">
		<figure class="h26e__photo">
			<?php
			echo cos_h26_picture(
				'evidence-portrait',
				array( 640, 960, 1280 ),
				array( 5, 7 ),
				'Portrait of a woman with fair skin and her hair drawn back, one hand resting beneath her chin',
				array(
					'sizes' => '(min-width: 700px) 640px, 100vw',
					'w'     => 960,
					'art'   => array(
						array( 'media' => '(min-width: 1100px)', 'base' => 'evidence-wide', 'widths' => array( 1440, 1920, 2400 ), 'sizes' => '100vw' ),
					),
				)
			);
			?>
			<?php foreach ( $calls as $i => $c ) : ?>
			<span class="h26e__mark h26e__mark--<?php echo esc_attr( $c[7] ); ?>" style="<?php echo esc_attr( $h26e_pos( $c ) ); ?>" aria-hidden="true"><span class="h26e__mark-n"><?php echo (int) $i + 1; ?></span></span>
			<?php endforeach; ?>
		</figure>
		<header class="h26e__head">
			<h2 id="h26-trust-title" class="h26e__title">Clinical evidence</h2>
			<span class="h26e__rule" aria-hidden="true"></span>
			<?php /* Testing body per page 347: not named there; name it only once the owner supplies it. */ ?>
			<p class="h26e__lede">Evaluated over 28 days by an independent contract research organisation.</p>
		</header>
		<ul class="h26e__calls" role="list">
			<?php foreach ( $calls as $i => $c ) : [ $fig, $finding, $product, $method, $panel ] = $c; ?>
			<li class="h26e__call h26e__call--<?php echo esc_attr( $c[7] ); ?>" style="<?php echo esc_attr( $h26e_pos( $c ) ); ?>">
				<span class="h26e__call-n" aria-hidden="true"><?php echo (int) $i + 1; ?></span>
				<span class="h26e__above">
					<span class="h26e__product"><?php echo esc_html( $product ); ?></span>
					<span class="h26e__fig"><?php echo esc_html( $fig ); ?></span>
				</span>
				<span class="h26e__lead" aria-hidden="true"></span>
				<span class="h26e__below">
					<span class="h26e__finding"><?php echo esc_html( $finding ); ?></span>
					<span class="h26e__kind"><span class="h26e__dot" aria-hidden="true"></span>Instrument-measured<span class="h26-sr">:</span></span>
					<span class="h26e__panel"><?php echo esc_html( $panel ); ?></span>
					<span class="h26e__method"><?php echo esc_html( $method ); ?></span>
				</span>
			</li>
			<?php endforeach; ?>
		</ul>
		<div class="h26e__aside">
			<p class="h26e__note"><span class="h26e__tag">Self-reported</span> 24h Regeneration Cream: <strong>100%</strong> panellist-reported satisfaction<span class="h26-sr">,</span> <span class="h26e__note-panel">34 women aged 40–65, 28 days</span></p>
			<p class="h26e__credit">Illustrative portrait; not a study participant.</p>
		</div>
		<p class="h26e__more"><a class="h26-link" href="<?php echo esc_url( home_url( '/science/clinical-studies/' ) ); ?>">Read the studies <?php echo cos_h26_arrow(); ?></a></p>
	</div>
</section>
