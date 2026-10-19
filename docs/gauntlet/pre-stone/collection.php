<?php
/**
 * Four authentic original product photographs, live WooCommerce details and
 * native product links. Source-direct crops/encodes: authentic-originals.origin.json.
 * Mobile selectors enhance a native swipe rail; no-JS retains all four links.
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
<section id="collection" class="hxc hxc--rail" aria-labelledby="hxc-title">
	<div class="hx-wrap">
		<header class="hxc__head">
			<h2 id="hxc-title" class="hx-heading hxc__title">The Collection</h2>
			<p class="hx-lead hxc__lede">Swiss skincare since 2008. Four formulas, composed as one complete daily ritual.</p>
		</header>
		<div class="hxc__choices" role="group" aria-label="Choose a formula" hidden>
			<button type="button" data-formula="0" aria-controls="hxc-products" aria-pressed="true">Cleanser</button>
			<button type="button" data-formula="1" aria-controls="hxc-products" aria-pressed="false">Serum</button>
			<button type="button" data-formula="2" aria-controls="hxc-products" aria-pressed="false">Eye cream</button>
			<button type="button" data-formula="3" aria-controls="hxc-products" aria-pressed="false">24h cream</button>
		</div>

		<ol class="hxc__list" id="hxc-products">
			<?php foreach ( $products as $p ) :
				$key = $objects[ $p['slug'] ] ?? '';
				if ( ! $key ) continue;
				$id   = 'hxc-' . $key;
				$name = $p['name']; // "24h Regeneration / Cream": qualifier line, then the noun (critic round 2)
				?>
			<li class="hxc__item hxc__item--<?php echo esc_attr( $key ); ?>">
				<a class="hxc__link" href="<?php echo esc_url( $p['url'] ); ?>">
					<span class="hxc__stage">
						<?php echo cos_h26_picture( 'auth-pack-' . $key, array( 320, 480, 640, 960 ), array( 640, 1100 ), '', array( 'class' => 'hxc__object', 'sizes' => '(min-width: 1100px) min(22vw, 330px), (min-width: 640px) min(42vw, 300px), 45.6vw', 'w' => 640 ) ); // Adjacent text supplies the full native link name. ?>
					</span>
					<span class="hxc__step" id="<?php echo esc_attr( $id ); ?>-step"><span class="hxc__num"><?php echo esc_html( $p['num'] ); ?></span><span class="hxc__verb"><?php echo esc_html( $p['verb'] ); ?></span></span>
					<h3 class="hxc__name" id="<?php echo esc_attr( $id ); ?>-name"><span><?php echo $nb( $name[0] ); ?></span> <span><?php echo $nb( $name[1] ); ?></span></h3>
					<span class="hxc__benefit" id="<?php echo esc_attr( $id ); ?>-benefit"><?php echo $nb( 'cleanser' === $key ? 'Cleanses, removes make-up & tones' : $p['benefit'] ); ?></span>
					<span class="hxc__meta" id="<?php echo esc_attr( $id ); ?>-meta">
						<?php if ( $p['price'] ) : ?><span class="hxc__price"><?php echo esc_html( $p['price'] ); ?></span><span class="hx-sr">, </span><span class="hxc__dot" aria-hidden="true"></span><?php endif; ?>
						<span class="hxc__size"><?php echo esc_html( $p['size'] ); ?></span>
					</span>
					<span class="hxc__cta"><span class="hxc__cta-label">Discover</span></span>
				</a>
			</li>
			<?php endforeach; ?>
		</ol>
		<div class="hxc__rail-ui">
			<p class="hxc__count" role="status" aria-live="polite" aria-atomic="true"><span class="hx-sr">Formula </span><span class="hxc__count-now">1</span><span aria-hidden="true"> / </span><span class="hx-sr"> of </span><?php echo (int) count( $objects ); ?></p>
			<span class="hxc__track" aria-hidden="true"><span class="hxc__thumb"></span></span>
			<div class="hxc__controls" hidden>
				<button class="hxc__previous" type="button" aria-label="Previous formula" aria-controls="hxc-products" aria-disabled="true"><svg viewBox="0 0 24 24" width="20" height="20" aria-hidden="true" focusable="false"><path d="M19 12H5m6-6-6 6 6 6"/></svg></button>
				<button class="hxc__next" type="button" aria-label="Next formula" aria-controls="hxc-products"><svg viewBox="0 0 24 24" width="20" height="20" aria-hidden="true" focusable="false"><path d="M5 12h14m-6-6 6 6-6 6"/></svg></button>
			</div>
		</div>
  <figure class="hxc__press">
   <blockquote cite="https://www.latfusa.com/article/2022/07/caviar-for-your-skin"><p>&ldquo;What I liked the most about this cream is its lightweight texture.&rdquo;</p></blockquote>
   <figcaption><span>On the 24h Regeneration Cream</span><a href="https://www.latfusa.com/article/2022/07/caviar-for-your-skin" target="_blank" rel="noopener noreferrer">Michele Elyzabeth, LATF USA, 2022<span class="hx-sr"> (opens in a new tab)</span><span class="hx-arrow" aria-hidden="true"></span></a></figcaption>
  </figure>
	</div>
</section>
