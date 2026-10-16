<?php
/**
 * The Ritual — why the order matters (hxrt-order). Intro carried over from the DB page
 * ("the architecture of application"); the four roles rewritten in cosmetic language (the
 * old cards spoke of pH resets, open pores and cellular uptake, which are not claims we can
 * stand behind). A restrained diagram: one line from the lightest texture to the richest,
 * the line itself gaining weight step by step. Ends with the way into the science pages.
 */
defined( 'ABSPATH' ) || exit;
$url   = $args['url'] ?? static fn( string $p ): string => home_url( $p );
$roles = array(
	array( 'Cleanse', 'Water', 'Prepare', 'The cleanser lifts make-up and the day’s impurities without rinsing, leaving the skin clean and comfortable for what follows.' ),
	array( 'Treat', 'Fluid', 'Concentrate', 'The lightest, most concentrated texture goes on first, onto clean skin, and is given a minute to absorb.' ),
	array( 'Revive', 'Light cream', 'Precision', 'The eye contour receives its own formula, calibrated for the thinnest skin of the face, where a face cream may feel too rich.' ),
	array( 'Restore', 'Rich cream', 'Seal', 'The richest texture comes last, sealing in the layers beneath it with lasting hydration and comfort.' ),
);
?>
<section class="hxrt-order" aria-labelledby="hxrt-order-title">
	<div class="hx-wrap">
		<header class="hxrt-order__head">
			<h2 id="hxrt-order-title" class="hx-heading" data-hx-reveal>Why the order matters</h2>
			<p class="hx-lead hxrt-order__lede" data-hx-reveal data-hx-delay="160">Skincare is not merely about ingredients; it is about the architecture of application. Always in this order, from the lightest texture to the richest: each step prepares the skin for the one that follows.</p>
		</header>

		<div class="hxrt-scale" aria-hidden="true" data-hx-reveal="mask-x">
			<span class="hxrt-scale__end">Lightest</span>
			<span class="hxrt-scale__line"><span></span><span></span><span></span><span></span></span>
			<span class="hxrt-scale__end">Richest</span>
		</div>

		<ol class="hxrt-order__list" data-hx-stagger>
			<?php foreach ( $roles as $i => [ $verb, $texture, $title, $text ] ) : ?>
			<li class="hxrt-order__item" data-hx-reveal>
				<p class="hxrt-order__meta"><span class="hx-step"><?php echo esc_html( sprintf( '%02d', $i + 1 ) ); ?></span><span class="hxrt-order__verb"><?php echo esc_html( $verb ); ?></span><span class="hxrt-order__texture"><?php echo esc_html( $texture ); ?></span></p>
				<h3 class="hx-title hxrt-order__title"><?php echo esc_html( $title ); ?></h3>
				<p class="hxrt-order__text"><?php echo esc_html( str_replace( '-', "\u{2011}", $text ) ); ?></p>
			</li>
			<?php endforeach; ?>
		</ol>

		<div class="hxrt-order__more" data-hx-reveal>
			<p class="hxrt-order__note">At the heart of every step: caviar extract from sustainably farmed Siberian sturgeon (<i lang="la">Acipenser baerii</i>), formulated in Zug, Switzerland.</p>
			<p class="hxrt-order__links">
				<a class="hx-link" href="<?php echo esc_url( $url( '/science/' ) ); ?>">Explore Swiss science<span class="hx-arrow" aria-hidden="true"></span></a>
				<a class="hx-link" href="<?php echo esc_url( $url( '/science/clinical-studies/' ) ); ?>">Clinical studies<span class="hx-arrow" aria-hidden="true"></span></a>
			</p>
		</div>
	</div>
</section>
