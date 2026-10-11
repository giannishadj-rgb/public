<?php
/**
 * 01 — Hero. A campaign image, one line of copy, one action.
 */
defined( 'ABSPATH' ) || exit;
$hero = cos_h26_hero();
?>
<section class="h26-hero<?php echo empty( $hero['light'] ) ? '' : ' h26-hero--light'; ?><?php echo empty( $hero['pearl'] ) ? '' : ' h26-hero--pearl'; ?>" aria-labelledby="h26-hero-title">
	<div class="h26-hero__media" data-h26-parallax=".06">
		<?php
		echo cos_h26_picture(
			$hero['mobile'],
			$hero['mobile_w'],
			$hero['mobile_ratio'],
			$hero['alt'],
			array(
				'eager' => true,
				'sizes' => '100vw',
				'w'     => max( $hero['mobile_w'] ),
				'art'   => array(
					array( 'media' => '(min-width: 1100px)', 'base' => $hero['desktop'], 'widths' => $hero['desktop_w'], 'sizes' => $hero['desktop_sizes'] ?? '100vw' ),
				),
			)
		);
		?>
	</div>
	<div class="h26-wrap h26-hero__copy">
		<h1 id="h26-hero-title" class="h26-hero__title">The Swiss Art of <br><span class="h26-hero__accent">Caviar Skincare</span></h1>
		<p class="h26-hero__lines">Four essential formulas, crafted with caviar and Swiss precision.</p>
		<div class="h26-hero__actions">
			<a class="h26-btn h26-btn--light" href="<?php echo esc_url( home_url( '/products/the-complete-ritual/' ) ); ?>" data-cro-cta="home-hero-primary">Shop the Complete Ritual <?php echo cos_h26_arrow(); ?></a>
		</div>
	</div>
</section>
