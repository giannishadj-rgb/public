<?php
/**
 * /science/ingredients/ (page 60) — chapter 03 (round 2, 17 Oct): the five actives, each with its
 * photograph (one warm monochrome treatment in CSS so five stock images read as one set).
 * SYN®-COLL's 4.5× keeps its "Ingredient research · in vitro" label, apart from any product result.
 */
defined( 'ABSPATH' ) || exit;

get_template_part( 'template-parts/hx/science/masthead', null, array(
	'label' => 'Chapter 03 <span aria-hidden="true">·</span> The five actives',
	'title' => 'Every active. Explained in full.',
	'lead'  => 'Five actives, selected for evidence and the way they work together: their source, their role and how research describes them.',
	'plate' => array(
		'base' => 'caviar-spoon-light', 'widths' => array( 720, 960, 1130 ), 'ratio' => array( 1130, 640 ),
		'alt'  => 'A silver spoon holding a measure of caviar pearls on a pale ground',
		'tall' => array( 'caviar-spoon-mobile', array( 640, 972 ), array( 640, 458 ) ),
		'pos'  => '62% 40%',
	),
) );

$pillars = hxsc_pillars();
?>
<section class="hxsc-sec" aria-labelledby="ih-list">
	<div class="hx-wrap">
		<h2 id="ih-list" class="hx-sr">The five actives</h2>
		<ol class="hxsc-acts" role="list">
			<?php foreach ( hxsc_actives() as $i => $a ) : ?>
			<li class="hxsc-act<?php echo $i % 2 ? ' hxsc-act--flip' : ''; ?>" id="<?php echo esc_attr( $a['slug'] ); ?>">
				<figure class="hxsc-act__img hx-frame" data-hx-reveal="settle"><img src="<?php echo esc_url( $a['img'][0] ); ?>" width="<?php echo (int) $a['img'][1]; ?>" height="<?php echo (int) $a['img'][2]; ?>" alt="<?php echo esc_attr( $a['img'][3] ); ?>" loading="lazy" decoding="async"></figure>
				<div class="hxsc-act__text" data-hx-reveal>
					<p class="hxsc-act__n"><span class="hxsc-num"><?php echo esc_html( $a['num'] ); ?></span> <span class="hxsc-act__role"><?php echo esc_html( $a['role'] ); ?></span></p>
					<h3 class="hx-heading hxsc-act__name"><?php echo esc_html( $a['name'] ); ?></h3>
					<p class="hxsc-act__inci"><span class="hx-sr">INCI: </span><?php echo esc_html( $a['inci'] ); ?></p>
					<p class="hx-body hxsc-act__desc"><?php echo esc_html( $a['desc'] ); ?></p>
					<?php if ( ! empty( $a['vitro'] ) ) : ?>
					<aside class="hxsc-vitro" aria-label="Ingredient research, in vitro">
						<p class="hxsc-vitro__k">Ingredient research · in vitro</p>
						<p class="hxsc-vitro__fig"><span class="hxsc-vitro__n"><?php echo esc_html( $a['vitro'][0] ); ?></span> <span><?php echo esc_html( $a['vitro'][1] ); ?></span></p>
						<p class="hxsc-vitro__note"><?php echo esc_html( $a['vitro'][2] ); ?></p>
					</aside>
					<?php endif; ?>
					<p class="hxsc-tags"><span class="hx-sr">Role: </span><?php foreach ( $a['roles'] as $r ) echo '<span class="hxsc-tag">' . esc_html( $pillars[ $r ][0] ) . '</span> '; ?></p>
					<p><a class="hx-link" href="<?php echo hxsc_url( '/science/ingredients/' . $a['slug'] . '/' ); ?>">Read in depth<span class="hx-sr">: <?php echo esc_html( $a['name'] ); ?></span><?php echo hxsc_arrow(); // phpcs:ignore ?></a></p>
				</div>
			</li>
			<?php endforeach; ?>
		</ol>
	</div>
</section>
