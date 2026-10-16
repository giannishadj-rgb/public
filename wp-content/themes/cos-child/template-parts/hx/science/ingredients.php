<?php
/**
 * /science/ingredients/ (page 60) — chapter 03, the index of the five actives. Copy from page 60.
 * SYN®-COLL's "up to 4.5×" stays labelled as in-vitro ingredient research.
 */
defined( 'ABSPATH' ) || exit;

get_template_part( 'template-parts/hx/science/masthead', null, array(
	'label' => 'Chapter 03 <span aria-hidden="true">·</span> The five actives',
	'title' => 'Every active. Explained in full.',
	'lead'  => 'Five actives, selected for clinical evidence and synergistic interaction. This index provides the full scientific context behind each ingredient: source, mechanism and role in the protocol.',
	'plate' => array(
		'base' => 'caviar-spoon-light', 'widths' => array( 720, 960, 1130 ), 'ratio' => array( 1130, 640 ),
		'alt'  => 'A silver spoon holding a measure of caviar pearls on a pale ground',
		'tall' => array( 'caviar-spoon-mobile', array( 640, 972 ), array( 640, 458 ) ),
	),
) );

$pillars = hxsc_pillars();
?>
<section class="hxsc-sec" aria-labelledby="ih-list">
	<div class="hx-wrap">
		<h2 id="ih-list" class="hx-sr">The five actives</h2>
		<ol class="hxsc-ilist" role="list">
			<?php foreach ( hxsc_actives() as $a ) : ?>
			<li class="hxsc-ilist__item" data-hx-reveal>
				<span class="hxsc-ilist__n" aria-hidden="true"><?php echo esc_html( $a['num'] ); ?></span>
				<div class="hxsc-ilist__head">
					<h3 class="hx-title hxsc-ilist__name"><?php echo esc_html( $a['name'] ); ?></h3>
					<p class="hxsc-ilist__role"><?php echo esc_html( $a['irole'] ); ?></p>
					<p class="hxsc-tags"><span class="hx-sr">Role: </span><?php foreach ( $a['roles'] as $r ) echo '<span class="hxsc-tag">' . esc_html( $pillars[ $r ][0] ) . '</span> '; ?></p>
				</div>
				<div class="hxsc-ilist__body">
					<p class="hx-body"><?php echo hxsc_kses( $a['index'] ); ?></p>
					<?php if ( ! empty( $a['vitro'] ) ) : ?>
					<p class="hx-body hxsc-ilist__vitro"><span class="hxsc-vitro__k">In vitro</span> In-vitro studies show it increases Type I collagen production by up to 4.5× in treated fibroblasts vs. control. Ingredient research, not a result measured with our products.</p>
					<?php endif; ?>
					<p><a class="hx-link" href="<?php echo hxsc_url( '/science/ingredients/' . $a['slug'] . '/' ); ?>">Read in depth<span class="hx-sr">: <?php echo esc_html( $a['name'] ); ?></span><?php echo hxsc_arrow(); // phpcs:ignore ?></a></p>
				</div>
			</li>
			<?php endforeach; ?>
		</ol>
	</div>
</section>
