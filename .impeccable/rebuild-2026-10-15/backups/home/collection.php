<?php
/**
 * 02 - The Collection (homepage rebuild, 14 Oct 2026, hxc- prefix).
 *
 * The four products are shown as objects, not shop cards: each stands free on the
 * section's pearl-white ground with its own soft studio shadow (no tile, frame or
 * box), all four at one presence and on ONE baseline, read left to right in ritual
 * order. Beneath each: step number and verb, name, one benefit (hidden on phones),
 * price with size, and a quiet "Discover" cue. Two by two below 1100px (phones too),
 * four across above. The whole object is the link; hover lifts the product image
 * (no underline on the name). Round 1 critique (14 Oct): no eyebrow, upright
 * heading, the hero alone owns "Four formulas. One complete ritual."
 *
 * Images: collection-object-* (transparent AVIF/WebP), built from the owner's
 * flat-lay packshots by design-sources/home-2026/build_collection_objects.py.
 * Copy, sizes, prices and URLs: cos_h26_products(). Styles: assets/css/hx/collection.css.
 * Previous Dior-style tile row: git history (h26c-- classes) and
 * design-sources/home-2026/alt-collection-*.
 */
defined( 'ABSPATH' ) || exit;
$products = $args['products'] ?? cos_h26_products();
$objects  = array(
	'micellar-water-cleanser' => 'cleanser',
	'advanced-repair-serum'   => 'serum',
	'revitalizing-eye-cream'  => 'eye',
	'24h-regeneration-cream'  => 'cream',
);
$nb = static function ( string $s ): string { // keep "All-in-One" and "make-up" whole
	return esc_html( str_replace( '-', "\u{2011}", $s ) );
};
?>
<section id="collection" class="hxc" aria-labelledby="hxc-title">
	<div class="hx-wrap">
		<header class="hxc__head">
			<h2 id="hxc-title" class="hx-heading hxc__title" data-hx-reveal>The Collection</h2>
			<p class="hx-lead hxc__lede" data-hx-reveal data-hx-delay="160">Cleanse, treat, revive, restore. Four formulas in white and polished silver, each made for one precise step.</p>
		</header>

		<ol class="hxc__list" data-hx-stagger>
			<?php foreach ( $products as $p ) :
				$key = $objects[ $p['slug'] ] ?? '';
				if ( ! $key ) continue;
				?>
			<li class="hxc__item" data-hx-reveal>
				<a class="hxc__link" href="<?php echo esc_url( $p['url'] ); ?>">
					<span class="hxc__stage">
						<?php echo cos_h26_picture( 'collection-object-' . $key, array( 480, 720, 960 ), array( 2, 3 ), $p['cardalt'], array( 'class' => 'hxc__object', 'sizes' => '(min-width: 1100px) min(24vw, 360px), (min-width: 640px) 300px, 46vw', 'w' => 720 ) ); ?>
					</span>
					<span class="hxc__step"><span class="hxc__num"><?php echo esc_html( $p['num'] ); ?></span><span class="hxc__verb"><?php echo esc_html( $p['verb'] ); ?></span></span>
					<h3 class="hxc__name"><span><?php echo $nb( $p['name'][0] ); ?></span> <span><?php echo $nb( $p['name'][1] ); ?></span></h3>
					<span class="hxc__benefit"><?php echo $nb( $p['benefit'] ); ?></span>
					<span class="hxc__meta">
						<?php if ( $p['price'] ) : ?><span class="hxc__price"><?php echo esc_html( $p['price'] ); ?></span><span class="hxc__dot" aria-hidden="true"></span><span class="hx-sr">, </span><?php endif; ?>
						<span class="hxc__size"><?php echo esc_html( $p['size'] ); ?></span>
					</span>
					<span class="hxc__cta" aria-hidden="true">Discover<span class="hx-arrow"></span></span>
				</a>
			</li>
			<?php endforeach; ?>
		</ol>
	</div>
</section>
