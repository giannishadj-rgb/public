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
 * Images (19 Oct 2026): wc-coll-* (transparent AVIF/WebP), cut from ONE supplied studio
 * photograph of all four packs (gauntlet-supplied-20261018/product-photo-candidates/
 * all_high_res.jpg, May 2021, current silver-foil print) by design-sources/home-2026/
 * build_wc_collection.py; see wc-coll.origin.json. Same scale for every canvas (1000px =
 * the same physical width), so rendering them at one width shows their true relative sizes.
 * Replaces collection-object-*, which came from the rejected flat masters (black print,
 * equalised heights, painted drop shadows).
 * Copy, sizes, prices and URLs: cos_h26_products(). Styles: assets/css/hx/collection.css.
 * Previous Dior-style tile row: git history (h26c-- classes) and
 * design-sources/home-2026/alt-collection-*.
 */
defined( 'ABSPATH' ) || exit;
$products = $args['products'] ?? cos_h26_products();
$objects  = array( // slug => [image key, canvas height at 1000px wide]
	'micellar-water-cleanser' => array( 'cleanser', 2167 ),
	'advanced-repair-serum'   => array( 'serum', 1534 ),
	'revitalizing-eye-cream'  => array( 'eye', 1377 ),
	'24h-regeneration-cream'  => array( 'cream', 950 ),
);
$nb = static function ( string $s ): string { // keep "All-in-One" and "make-up" whole
	return esc_html( str_replace( '-', "\u{2011}", $s ) );
};
?>
<section id="collection" class="hxc" aria-labelledby="hxc-title">
	<div class="hx-wrap">
		<header class="hxc__head">
			<h2 id="hxc-title" class="hx-heading hxc__title" data-hx-reveal>The essential four.</h2>
			<p class="hx-lead hxc__lede" data-hx-reveal data-hx-delay="160">Cleanse, treat, revive, restore. Four formulas in white and polished silver, each made for one precise step.</p>
		</header>

		<ol class="hxc__list" data-hx-stagger>
			<?php foreach ( $products as $p ) :
				if ( empty( $objects[ $p['slug'] ] ) ) continue;
				[ $key, $canvas_h ] = $objects[ $p['slug'] ];
				?>
			<li class="hxc__item" data-hx-reveal>
				<a class="hxc__link" href="<?php echo esc_url( $p['url'] ); ?>">
					<span class="hxc__stage">
						<?php echo cos_h26_picture( 'wc-coll-' . $key, array( 400, 600, 800, 1000 ), array( 1000, $canvas_h ), $p['cardalt'], array( 'class' => 'hxc__object', 'sizes' => '(min-width: 1100px) min(16vw, 242px), (min-width: 640px) 200px, 31vw', 'w' => 1000 ) ); // rendered at 67% of the stage width ?>
					</span>
					<?php /* 19 Oct 2026: no "01 —" numbering (the <ol> and the left-to-right order carry the
					   sequence), price and size on one quiet line, no separate "Discover" row: the
					   whole object is the link, and hover/focus underlines the name. */ ?>
					<span class="hxc__step"><span class="hxc__verb"><?php echo esc_html( $p['verb'] ); ?></span></span>
					<h3 class="hxc__name"><span><?php echo $nb( $p['name'][0] ); ?></span> <span><?php echo $nb( $p['name'][1] ); ?></span></h3>
					<span class="hxc__benefit"><?php echo $nb( $p['benefit'] ); ?></span>
					<span class="hxc__meta">
						<?php if ( $p['price'] ) : ?><span class="hxc__price"><?php echo esc_html( $p['price'] ); ?></span><span class="hxc__sep" aria-hidden="true">&nbsp;·&nbsp;</span><span class="hx-sr">, </span><?php endif; ?>
						<span class="hxc__size"><?php echo esc_html( $p['size'] ); ?></span>
					</span>
				</a>
			</li>
			<?php endforeach; ?>
		</ol>
	</div>
</section>

