<?php
/**
 * /science/ingredients/ (page 60) — chapter 03 (round 2, 17 Oct): the five actives, each with its
 * photograph (one warm monochrome treatment in CSS so five stock images read as one set).
 * SYN®-COLL's 4.5× keeps its "Ingredient research · in vitro" label, apart from any product result.
 *
 * 31 Oct 2026 (owner reference: an ingredient spread with a central hairline): the actives zigzag
 * either side of one vertical hairline, each photograph set against the line and its name and text
 * on the other side, aligned to the outer edge. Name, INCI, description, link (and the in-vitro
 * note); the numbered role eyebrow and the Protect / Repair tags were removed for that calm.
 */
defined( 'ABSPATH' ) || exit;

get_template_part( 'template-parts/hx/science/masthead', null, array(
	'mode'  => 'bleed',
	'label' => 'Chapter 03 <span aria-hidden="true">·</span> The five actives',
	'title' => 'Every active. Explained in full.',
	'lead'  => 'Five actives, selected for evidence and the way they work together: their source, their role and how research describes them.',
	'plate' => array(
		'base' => 'caviar-spoon-light', 'widths' => array( 720, 960, 1130 ), 'ratio' => array( 1130, 640 ),
		'alt'  => 'A silver spoon holding a measure of caviar pearls on a pale ground',
		'pos'  => '62% 40%',
		'pos_wide' => '50% 40%',
	),
) );

?>
<section class="hxsc-sec" aria-labelledby="ih-list">
	<div class="hx-wrap">
		<h2 id="ih-list" class="hx-sr">The five actives</h2>
		<ol class="hxsc-acts" role="list">
			<?php foreach ( hxsc_actives() as $i => $a ) : ?>
			<li class="hxsc-act<?php echo $i % 2 ? ' hxsc-act--flip' : ''; ?>" id="<?php echo esc_attr( $a['slug'] ); ?>">
				<figure class="hxsc-act__img hxsc-act__img--tex" data-hx-reveal="settle"><?php $tx = get_stylesheet_directory_uri() . '/assets/images/ingredients/' . $a['tex']; ?><picture><?php foreach ( array( 'avif', 'webp' ) as $ext ) : ?><source type="image/<?php echo $ext; ?>" srcset="<?php echo esc_attr( "$tx-600.$ext 600w, $tx-900.$ext 900w" ); ?>" sizes="(min-width: 900px) 34vw, 100vw"><?php endforeach; ?><img src="<?php echo esc_url( "$tx-900.jpg" ); ?>" srcset="<?php echo esc_attr( "$tx-600.jpg 600w, $tx-900.jpg 900w" ); ?>" sizes="(min-width: 900px) 34vw, 100vw" width="900" height="900" alt="<?php echo esc_attr( $a['img'][3] ); ?>" loading="lazy" decoding="async"></picture></figure>
				<div class="hxsc-act__text" data-hx-reveal>
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
					<p><a class="hx-link" href="<?php echo hxsc_url( '/science/ingredients/' . $a['slug'] . '/' ); ?>">Read in depth<span class="hx-sr">: <?php echo esc_html( $a['name'] ); ?></span><?php echo hxsc_arrow(); // phpcs:ignore ?></a></p>
				</div>
			</li>
			<?php endforeach; ?>
		</ol>
	</div>
</section>
