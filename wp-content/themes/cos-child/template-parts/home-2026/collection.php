<?php
/**
 * 02 - The Collection (homepage rebuild, 14 Oct 2026, hxc- prefix).
 *
 * 20 Oct 2026 (owner, Guerlain Orchidée Impériale grid as reference): rebuilt in a blind
 * builder/critic loop; the final version won 4 of 4 blind judgments against the reference.
 * Each product on a graphite stage (not navy: La Prairie owns cobalt) with a chrome horizon,
 * contact light and floor reflection; each pack fills its own frame (owner decision, round 2;
 * never distorted). Four across on 4:5 stages from 1100px, two across on squares below. Beneath,
 * left-aligned: ritual step label, serif capital name + benefit (one .hxc__copy row, so the
 * benefit sits under its own name), price · size. hxc__item--{key} carries each pack's scale.
 * Styles: hx/collection.css.
 *
 * Before that, 19 Oct 2026 (owner: "like the Dior L'Or de Vie grid, in the website's colours"): each
 * product stands on its own pearl tile (#F8F8F8, the site canvas and the packshots'
 * backdrop) on the section's warm-white ground, tiles almost touching (2px seam). Inside the
 * tile: the ritual step as a small caption top-left, the packshot on a shared baseline, and a
 * square bag button bottom-right. Beneath: name, one benefit, price with size, all centred.
 * Two across below 1100px (phones too), four across above.
 *
 * Two controls per product, side by side in the DOM (never nested): the product link (tile +
 * text) and the bag button. The bag button is a plain WooCommerce ?add-to-cart= link without
 * JavaScript (the page reloads and the mini-bag opens from its printed data); with
 * JavaScript it adds over wc-ajax, swaps the cart fragments and opens the mini-bag through
 * the jQuery-free `hx:added_to_cart` event (inc/hx-minibag.php). Only simple, purchasable,
 * in-stock products get one (cos_h26_products()['can_add']).
 *
 * Images (owner, 20 Oct 2026): wc-stage-{key} — the owner's four studio packshots, each pack
 * on its own graphite stage with a lit horizon and floor reflection, 1122x1402 (4:5), exported
 * at 400/600/800/1122 in AVIF/WebP/JPEG (originals: design-sources/home-2026/
 * stage-packshots-2026-10-20/). They replace the CSS-built stage over wc-coll-* cut-outs.
 * Since 24 Oct 2026 (owner: "colorize the product cards to suit the brand, don't change the style")
 * the homepage shows wc-stage-warm-{key}: the same photographs with the stage only recoloured in
 * the brand's warm family (espresso ground, champagne horizon), the packs masked and untouched
 * (design-sources/home-2026/build_wc_stage_warm.py). Same day, owner: "in graphite" — now
 * wc-stage-graphite-{key} (ground lifted to the brand ink #202221, silver horizon; --tone graphite).
 * The warm set stays on disk. The shop grid still uses wc-stage-{key}.
 * Since 29 Oct 2026 (owner, La Prairie face-serum grid as reference): no stage. Each product is the
 * owner's new studio packshot cut out of its grey sweep, packshot-{key} (transparent AVIF/WebP; built
 * by design-sources/home-2026/packshots-2026-10-29/build_packshots.py), standing on the page's own light ground, small and
 * centred with generous air, every pack on one shared baseline at its true relative size (the jar is
 * shorter than the bottles). The graphite set stays on disk; styles: the last block of collection.css.
 * Same day (owner: "use these instead", "use them as they are, no change in background colour"): the
 * owner's overhead photographs with their own cast shadows and light grey ground, coll-photo-{key},
 * only cropped to 4:5 (design-sources/home-2026/collection-photos-2026-10-29/build_collection_photos.py);
 * each fills its tile. The packshot-{key} cut-outs stay on disk.
 * Copy, sizes, prices, IDs and URLs: cos_h26_products(). Styles: assets/css/hx/collection.css.
 */
defined( 'ABSPATH' ) || exit;
$products = $args['products'] ?? cos_h26_products();
$objects  = array( // slug => [image key, (unused since 20 Oct: canvas height of the old wc-coll cut-out)]
	'micellar-water-cleanser' => array( 'cleanser', 2167 ),
	'advanced-repair-serum'   => array( 'serum', 1534 ),
	'revitalizing-eye-cream'  => array( 'eye', 1377 ),
	'24h-regeneration-cream'  => array( 'cream', 950 ),
);
$nb = static function ( string $s ): string { // keep "All-in-One" and "make-up" whole
	return esc_html( str_replace( '-', "\u{2011}", $s ) );
};
$has_bag = false;
?>
<section id="collection" class="hxc" aria-labelledby="hxc-title">
	<div class="hx-wrap">
		<header class="hxc__head">
			<h2 id="hxc-title" class="hx-heading hxc__title" data-hx-reveal="light">The essential four</h2>
			<p class="hx-lead hxc__lede" data-hx-reveal data-hx-delay="160">Cleanse, treat, revive, restore. Four formulas in white and polished silver, each made for one precise step.</p>
		</header>

		<ol class="hxc__list" data-hx-stagger>
			<?php foreach ( $products as $p ) :
				if ( empty( $objects[ $p['slug'] ] ) ) continue;
				[ $key, $canvas_h ] = $objects[ $p['slug'] ];
				$full_name = $p['name'][0] . ' ' . $p['name'][1];
				?>
			<li class="hxc__item hxc__item--<?php echo esc_attr( $key ); ?>" data-hx-reveal>
				<a class="hxc__link" href="<?php echo esc_url( $p['url'] ); ?>">
					<span class="hxc__tile">
						<?php echo cos_h26_picture( 'coll-photo-' . $key, array( 480, 800 ), array( 4, 5 ), $p['cardalt'], array( 'class' => 'hxc__photo', 'sizes' => '(min-width: 1100px) min(24vw, 600px), 48vw', 'w' => 800 ) ); // the owner's overhead photograph, as supplied (4:5 crop only) ?>
					</span>
					<div class="hxc__copy">
					<h3 class="hxc__name"><span><?php echo $nb( $p['name'][0] ); ?></span> <span><?php echo $nb( $p['name'][1] ); ?></span></h3>
					<span class="hxc__benefit"><?php echo $nb( $p['benefit'] ); ?></span>
					</div>
					<span class="hxc__meta">
						<?php if ( $p['price'] ) : ?><span class="hxc__price"><?php echo esc_html( $p['price'] ); ?></span><span class="hxc__sep" aria-hidden="true">&nbsp;·&nbsp;</span><span class="hx-sr">, </span><?php endif; ?>
						<span class="hxc__size"><?php echo esc_html( $p['size'] ); ?></span>
					</span>
				</a>
				<?php if ( ! empty( $p['can_add'] ) ) : $has_bag = true; ?>
				<a class="hxc__bag" href="<?php echo esc_url( add_query_arg( 'add-to-cart', (int) $p['id'], home_url( '/' ) ) ); ?>" rel="nofollow" data-product-id="<?php echo (int) $p['id']; ?>" data-cro-cta="home-collection-add" aria-label="<?php echo esc_attr( sprintf( 'Add %s to bag', $full_name ) ); ?>"><svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M6 8h12l-1 11H7L6 8Z"/><path d="M9 8a3 3 0 1 1 6 0"/></svg></a>
				<?php endif; ?>
			</li>
			<?php endforeach; ?>
		</ol>
	</div>
</section>
<?php if ( $has_bag ) : ?>
<script nonce="<?php echo esc_attr( cos_csp_nonce() ); ?>">
(function(){
  // Add to bag without leaving the page (no jQuery on the homepage). Any failure falls back
  // to the button's own ?add-to-cart= link, so WooCommerce explains what went wrong.
  if(!window.fetch||!window.FormData) return;
  var url=(window.wc_add_to_cart_params&&window.wc_add_to_cart_params.wc_ajax_url)?String(window.wc_add_to_cart_params.wc_ajax_url).replace('%%endpoint%%','add_to_cart'):'/?wc-ajax=add_to_cart';
  document.querySelectorAll('.hxc__bag').forEach(function(a){
    a.addEventListener('click',function(e){
      e.preventDefault();
      if(a.getAttribute('aria-busy')==='true') return;
      a.setAttribute('aria-busy','true');
      var body=new FormData(); body.append('product_id',a.getAttribute('data-product-id')); body.append('quantity','1');
      fetch(url,{method:'POST',body:body,credentials:'same-origin'})
        .then(function(r){return r.json();})
        .then(function(res){
          if(!res||res.error||!res.fragments) throw new Error('add failed');
          Object.keys(res.fragments).forEach(function(sel){
            document.querySelectorAll(sel).forEach(function(el){el.outerHTML=res.fragments[sel];});
          });
          a.removeAttribute('aria-busy');
          a.classList.add('is-added');
          setTimeout(function(){a.classList.remove('is-added');},2400);
          document.dispatchEvent(new CustomEvent('hx:added_to_cart',{detail:{fragments:res.fragments,button:a}}));
        })
        .catch(function(){window.location.href=a.href;});
    });
  });
})();
</script>
<?php endif; ?>
