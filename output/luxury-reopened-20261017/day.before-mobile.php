<?php
/**
 * The Ritual — morning and evening (hxrt-day). Both protocols side by side (no toggle: the
 * difference is the point), kept deliberately brief: the full gestures live in each step
 * chapter above, so each line here only names what is particular to that time of day.
 * Guidance follows the product pages (no-rinse cleanser, 2–3 drops of serum, SPF 50+ over
 * the cream by day, the cream as an overnight treatment); "absorb fully before sleeping"
 * from the DB page. The DB page's double cleanse and morning rinse are not on the cleanser's
 * product page, so they are not repeated here.
 */
defined( 'ABSPATH' ) || exit;
$times = array(
	'morning' => array(
		'title' => 'Morning',
		'steps' => array(
			array( 'Cleanse', 'One soaked pad. No rinsing.' ),
			array( 'Treat', 'Two or three drops; 60 seconds to absorb.' ),
			array( 'Revive', 'A grain of rice per eye.' ),
			array( 'Restore', 'A pea-sized amount, pressed in.' ),
		),
		'close' => 'Finish with a broad-spectrum SPF 50+.',
	),
	'evening' => array(
		'title' => 'Evening',
		'steps' => array(
			array( 'Cleanse', 'Lift the day’s make-up and SPF.' ),
			array( 'Treat', 'Two or three drops, unhurried.' ),
			array( 'Revive', 'Patted along the orbital bone.' ),
			array( 'Restore', 'Your overnight treatment.' ),
		),
		'close' => 'Allow the cream to absorb fully before sleeping.',
	),
);
?>
<section class="hxrt-day" aria-labelledby="hxrt-day-title">
	<div class="hx-wrap">
		<header class="hxrt-day__head">
			<h2 id="hxrt-day-title" class="hx-heading" data-hx-reveal="fade">Morning and evening</h2>
			<p class="hx-lead hxrt-day__lede" data-hx-reveal="fade">The same four steps, twice a day, in the same order. Only the details change.</p>
		</header>
		<div class="hxrt-day__grid">
			<?php foreach ( $times as $k => $t ) : ?>
			<div class="hxrt-day__col hxrt-day__col--<?php echo esc_attr( $k ); ?>" data-hx-reveal="fade">
				<h3 class="hx-title hxrt-day__title"><?php echo esc_html( $t['title'] ); ?></h3>
				<ol class="hxrt-day__list">
					<?php foreach ( $t['steps'] as $j => [ $verb, $text ] ) : ?>
					<li class="hxrt-day__step"><span class="hx-step"><?php echo esc_html( sprintf( '%02d', $j + 1 ) ); ?></span><span class="hxrt-day__verb"><?php echo esc_html( $verb ); ?></span><span class="hxrt-day__text"><?php echo esc_html( str_replace( '-', "\u{2011}", $text ) ); ?></span></li>
					<?php endforeach; ?>
				</ol>
				<p class="hxrt-day__close"><?php echo esc_html( $t['close'] ); ?></p>
			</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
