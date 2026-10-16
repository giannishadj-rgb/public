<?php
/**
 * The Ritual — hero (hxrt-hero). The four formulas on the silver tray (owner photograph,
 * hxrt-tray, built by design-sources/home-2026/build_the_ritual_page.py) beside the page's
 * one h1 and a quiet index of the four steps (in-page anchors). No button here: the page has
 * one primary action, the Complete Ritual offer at its end.
 * Phones: photograph first (full width, 4:5), copy beneath. ≥900px: copy left, photograph right.
 */
defined( 'ABSPATH' ) || exit;
$steps = $args['steps'] ?? array();
?>
<section class="hxrt-hero" aria-labelledby="hxrt-h1">
	<div class="hxrt-hero__media">
		<?php
		echo cos_h26_picture(
			'hxrt-tray',
			array( 720, 880 ),
			array( 4, 5 ),
			'The four Caviar of Switzerland formulas, Advanced Repair Serum, 24h Regeneration Cream, Revitalizing Eye Cream and Micellar Water All-in-One Cleanser, presented on a silver tray',
			array( 'class' => 'hxrt-hero__pic hx-load-settle', 'sizes' => '(min-width: 900px) 46vw, 100vw', 'w' => 880, 'eager' => true )
		);
		?>
	</div>
	<div class="hxrt-hero__copy">
		<p class="hx-label hx-load-fade">The Ritual</p>
		<h1 id="hxrt-h1" class="hx-display hxrt-hero__title hx-load-rise" style="--hx-i:1"><span class="hxrt-hero__line">Four formulas.</span> <em class="hxrt-hero__line">One complete ritual.</em></h1>
		<p class="hx-lead hxrt-hero__lede hx-load-rise" style="--hx-i:2">Effective skincare is a sequence, not a single product. Each step prepares the skin for the next, morning and evening.</p>
		<nav class="hxrt-index hx-load-fade" style="--hx-i:3" aria-label="The four steps">
			<ol class="hxrt-index__list">
				<?php foreach ( $steps as $s ) : ?>
				<li><a class="hxrt-index__link" href="#step-<?php echo esc_attr( strtolower( $s['verb'] ) ); ?>"><span class="hxrt-index__num"><?php echo esc_html( $s['num'] ); ?></span><span class="hxrt-index__verb"><?php echo esc_html( $s['verb'] ); ?></span></a></li>
				<?php endforeach; ?>
			</ol>
		</nav>
	</div>
</section>
