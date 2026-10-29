<?php
/**
 * The Ritual — part 1, the ritual at a glance (hxrt-glance, #at-a-glance). Clarify pass,
 * 23 Oct 2026: the former "Why the order matters" (order.php), moved up to follow the hero so
 * the whole ritual is understood before the detail. Each step names its formula and links to
 * its chapter in "Step by step". Roles in cosmetic language (the old DB cards spoke of pH
 * resets, open pores and cellular uptake, which are not claims we can stand behind). The line
 * gaining weight step by step is the lightest → richest order.
 */
defined( 'ABSPATH' ) || exit;
$steps = $args['steps'] ?? array();
$roles = array(
	'cleanser' => array( 'Water', 'Lifts make-up and the day’s impurities without rinsing, leaving the skin clean and comfortable for what follows.' ),
	'serum'    => array( 'Fluid', 'The lightest, most concentrated texture goes on first, onto clean skin, and is given a minute to absorb.' ),
	'eye'      => array( 'Light cream', 'The eye contour receives its own formula, calibrated for the thinnest skin of the face, where a face cream may feel too rich.' ),
	'cream'    => array( 'Rich cream', 'The richest texture comes last, sealing in the layers beneath it with lasting hydration and comfort.' ),
);
$nb = static fn( string $s ): string => str_replace( '-', "\u{2011}", $s );
?>
<section id="at-a-glance" class="hxrt-glance" aria-labelledby="hxrt-glance-title">
	<div class="hx-wrap">
		<header class="hxrt-part__head">
			<h2 id="hxrt-glance-title" class="hx-heading" data-hx-reveal>The ritual at a glance</h2>
			<p class="hx-lead hxrt-part__lede" data-hx-reveal>Four formulas, applied in the same order every morning and evening: from the lightest texture to the richest, so each step prepares the skin for the next.</p>
		</header>

		<div class="hxrt-scale" aria-hidden="true" data-hx-reveal="mask-x">
			<span class="hxrt-scale__end">Lightest</span>
			<span class="hxrt-scale__line"><span></span><span></span><span></span><span></span></span>
			<span class="hxrt-scale__end">Richest</span>
		</div>

		<ol class="hxrt-glance__list" data-hx-stagger>
			<?php foreach ( $steps as $s ) :
				if ( empty( $roles[ $s['key'] ] ) ) continue;
				[ $texture, $text ] = $roles[ $s['key'] ];
				?>
			<li class="hxrt-glance__item" data-hx-reveal>
				<p class="hxrt-glance__meta"><span class="hx-step"><?php echo esc_html( $s['num'] ); ?></span><span class="hxrt-glance__verb"><?php echo esc_html( $s['verb'] ); ?></span><span class="hxrt-glance__texture"><?php echo esc_html( $texture ); ?></span></p>
				<h3 class="hx-title hxrt-glance__name"><?php echo esc_html( $nb( $s['name'][0] . ' ' . $s['name'][1] ) ); ?></h3>
				<p class="hxrt-glance__text"><?php echo esc_html( $nb( $text ) ); ?></p>
				<a class="hxrt-glance__go" href="#step-<?php echo esc_attr( strtolower( $s['verb'] ) ); ?>">How to apply<span class="hx-sr"> <?php echo esc_html( $s['name'][0] . ' ' . $s['name'][1] ); ?></span><span class="hxrt-glance__down" aria-hidden="true"></span></a>
			</li>
			<?php endforeach; ?>
		</ol>
	</div>
</section>
