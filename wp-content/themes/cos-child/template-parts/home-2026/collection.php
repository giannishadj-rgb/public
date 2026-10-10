<?php
/**
 * 02 - The four-product ritual (owner pick, 10 Oct 2026, from a reference
 * layout): the section in the packshots' backdrop tone; a spaced-caps serif
 * title over a short rule and a centred lede; then the four packshots with
 * name, size · price and an outlined "Shop now". Phones 2×2, desktop one row of four.
 * Replaced the swipe-rail cards (.h26-rail / .h26-pack); styles: .h26c in
 * assets/css/home-2026.css.
 */
defined( 'ABSPATH' ) || exit;
$products = $args['products'];
?>
<section id="collection" class="h26c" aria-labelledby="h26-collection-title">
	<div class="h26c__panel">
		<header class="h26c__head">
			<h2 id="h26-collection-title" class="h26c__title">The Collection</h2>
			<span class="h26c__rule" aria-hidden="true"></span>
			<p class="h26c__lede">Four caviar formulas, developed and made in Zug and used morning and evening. Each step prepares the skin for the next.<em>Four formulas. One complete ritual.</em></p>
		</header>
		<p class="h26c__sub" aria-hidden="true">The four formulas</p>
		<ul class="h26c__grid" role="list">
			<?php foreach ( $products as $p ) : ?>
			<li>
				<a class="h26c__item" href="<?php echo esc_url( $p['url'] ); ?>">
					<?php echo cos_h26_picture( $p['card'], array( 480, 720, 960 ), array( 4, 5 ), $p['cardalt'], array( 'class' => 'h26c__img', 'sizes' => '(min-width: 1100px) 22vw, 46vw', 'w' => 720 ) ); ?>
					<span class="h26c__name"><?php echo esc_html( $p['name'][0] ); ?><br><?php echo esc_html( $p['name'][1] ); ?></span>
					<span class="h26c__price"><?php echo esc_html( $p['size'] ); ?><?php if ( $p['price'] ) : ?><span aria-hidden="true"> · </span><span class="h26-sr">, </span><?php echo esc_html( $p['price'] ); ?><?php endif; ?></span>
					<span class="h26c__btn">Shop now</span>
				</a>
			</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>
