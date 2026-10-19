<?php
/**
 * COS — WooCommerce Shop Archive (/shop/). Whole-site rebuild, 16 Oct 2026 (prefix hxshop-).
 *
 * Same world as the homepage Collection: the four formulas stand free as objects on the
 * pearl-white ground (owner flat-lays, collection-object-*), with The Complete Ritual offered
 * on the owner's silver-tray photograph. Order:
 *   1. Hero — owner's glass-blocks photograph CAV59851 (owner pick 27 Sep), light; quiet link to the formulas.
 *   2. The Complete Ritual (#cos-ritual) — real price + saving from WooCommerce.
 *   3. The four formulas (#cos-products) — step, name, one benefit, size, price, add to bag.
 *   4. "About the collection" (category copy, native <details>); services sit in the footer.
 * Styles: assets/css/hx/shop.css (enqueued below, shop only). Tokens/utilities: hx/system.css.
 * Legacy hook classes kept for analytics/tests: cos-shop-hero-eyebrow, cos-shop-hero-img,
 * cos-card, cos-card-btn, cos-bundle-btn-primary, cos-select-item, data-cro-cta.
 * Previous version: design-sources/home-2026/rebuild-2026-10-14/work/shop/archive-product.before.php
 */
defined( 'ABSPATH' ) || exit;

add_action( 'wp_enqueue_scripts', static function (): void {
	$file = get_stylesheet_directory() . '/assets/css/hx/shop.css';
	wp_enqueue_style( 'cos-hx-shop', get_stylesheet_directory_uri() . '/assets/css/hx/shop.css', array( 'cos-hx-system' ), (string) filemtime( $file ) );
	if ( function_exists( 'WC' ) ) wp_enqueue_script( 'wc-add-to-cart' );
}, 30 );

$products_query = new WP_Query( array(
	'post_type'      => 'product',
	'post_status'    => 'publish',
	'posts_per_page' => -1,
	'orderby'        => 'menu_order',
	'order'          => 'ASC',
	'meta_query'     => array(
		array( 'key' => '_price', 'compare' => 'EXISTS' ),
	),
) );

// The bundle (COS-BUNDLE) is not one of the four formulas: it gets its own
// offer section (#cos-ritual) instead of a place in the list.
$individual = array();
$bundle     = null;
if ( $products_query->have_posts() ) {
	while ( $products_query->have_posts() ) {
		$products_query->the_post();
		$wc_product = wc_get_product( get_the_ID() );
		if ( ! $wc_product ) continue;
		if ( $wc_product->get_sku() === 'COS-BUNDLE' ) {
			$bundle = $wc_product;
			continue;
		}
		$individual[] = $wc_product;
	}
	wp_reset_postdata();
}

// Ritual order by SKU (launch catalogue). New products sort last.
usort( $individual, function ( $a, $b ) {
	$steps = array( 'COS-001' => 1, 'COS-002' => 2, 'COS-003' => 3, 'COS-004' => 4 );
	return ( $steps[ $a->get_sku() ] ?? 9 ) - ( $steps[ $b->get_sku() ] ?? 9 );
} );

// Editorial data (step, verb, benefit, two-line name, size, alt) shared with the homepage.
$h26 = array();
foreach ( cos_h26_products() as $p ) $h26[ $p['slug'] ] = $p;
$objects = array(
	'micellar-water-cleanser' => 'cleanser',
	'advanced-repair-serum'   => 'serum',
	'revitalizing-eye-cream'  => 'eye',
	'24h-regeneration-cream'  => 'cream',
);
$nb = static function ( string $s ): string { // keep "All-in-One" and "make-up" whole
	return esc_html( str_replace( '-', "\u{2011}", $s ) );
};

// Bought-separately total from the live prices of the four formulas (0 if any is missing).
$separate_total = 0.0;
foreach ( $individual as $ip ) {
	if ( '' === $ip->get_price() ) { $separate_total = 0.0; break; }
	$separate_total += (float) $ip->get_price();
}
$bundle_price  = $bundle ? (float) $bundle->get_price() : 0.0;
$bundle_saving = ( $bundle && $separate_total > $bundle_price && count( $individual ) >= 4 ) ? $separate_total - $bundle_price : 0.0;

$hero_dir = get_stylesheet_directory_uri() . '/assets/images/shop-hero/';
$hero_set = static function ( string $crop, array $widths, string $ext ) use ( $hero_dir ): string {
	return implode( ', ', array_map( static fn( int $w ): string => $hero_dir . "hxshop-hero-{$crop}-{$w}.{$ext} {$w}w", $widths ) );
};
// Crops built by design-sources/home-2026/build_hxshop_hero.py (tabletop continued below the products,
// pearl fill light). Below 1024px the mobile crop sits above the copy; from 1024px the desktop crop fills the hero.
$hero_crops = array(
	'desktop' => array( '(min-width: 1024px)', array( 1200, 1600, 2400 ) ),
);

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<?php cos_child_custom_document_head( 'Shop - Caviar of Switzerland' ); ?>
<?php
foreach ( array(
	array( '(min-width: 1024px)', 'desktop', array( 1200, 1600, 2400 ) ),
	array( '(max-width: 1023.98px)', 'mobile', array( 720, 1080 ) ),
) as [ $pl_media, $pl_crop, $pl_widths ] ) {
	echo '<link rel="preload" as="image" type="image/avif" fetchpriority="high" media="' . esc_attr( $pl_media ) . '" imagesrcset="' . esc_attr( $hero_set( $pl_crop, $pl_widths, 'avif' ) ) . '" imagesizes="100vw">' . "\n";
}
?>
<?php wp_head(); ?>
</head>
<body <?php body_class( 'woocommerce woocommerce-page hxshop-page' ); ?>>
<?php wp_body_open(); ?>

<main id="cos-main-content" class="hxshop">

	<!-- 1. Hero: the owner's CAV59851 glass-blocks photograph -->
	<section class="hxshop-hero cos-shop-hero" aria-labelledby="hxshop-title">
		<div class="hxshop-hero__media">
			<picture>
				<?php foreach ( $hero_crops as $crop => [ $media, $widths ] ) : foreach ( array( 'avif', 'webp', 'jpg' ) as $ext ) : ?>
				<source media="<?php echo esc_attr( $media ); ?>"<?php echo 'jpg' === $ext ? '' : ' type="image/' . $ext . '"'; ?> srcset="<?php echo esc_attr( $hero_set( $crop, $widths, $ext ) ); ?>" sizes="100vw" width="2400" height="1460">
				<?php endforeach; endforeach; ?>
				<source type="image/avif" srcset="<?php echo esc_attr( $hero_set( 'mobile', array( 720, 1080 ), 'avif' ) ); ?>" sizes="100vw" width="1080" height="961">
				<source type="image/webp" srcset="<?php echo esc_attr( $hero_set( 'mobile', array( 720, 1080 ), 'webp' ) ); ?>" sizes="100vw" width="1080" height="961">
				<img class="hxshop-hero__img cos-shop-hero-img" src="<?php echo esc_url( $hero_dir . 'hxshop-hero-mobile-1080.jpg' ); ?>" srcset="<?php echo esc_attr( $hero_set( 'mobile', array( 720, 1080 ), 'jpg' ) ); ?>" sizes="100vw" width="1080" height="961" alt="The four Caviar of Switzerland formulas arranged on clear glass blocks." fetchpriority="high" decoding="async">
			</picture>
		</div>
		<div class="hx-wrap hxshop-hero__wrap">
			<div class="hxshop-hero__copy">
				<p class="hx-label hxshop-hero__label cos-shop-hero-eyebrow hx-load-fade" style="--hx-i:0">The Collection</p>
				<h1 id="hxshop-title" class="hx-display hxshop-hero__title hx-load-rise" style="--hx-i:1">Swiss caviar skincare</h1>
				<p class="hx-lead hxshop-hero__lede hx-load-rise" style="--hx-i:2">Four formulas, each made for one precise step, and The Complete Ritual that unites them.</p>
				<div class="hxshop-hero__cta hx-load-rise" style="--hx-i:3">
					<a href="#cos-products" class="hx-link hxshop-hero__link" data-cro-cta="shop-hero-primary">View the four formulas<span class="hx-arrow hxshop-hero__down" aria-hidden="true"></span></a>
				</div>
			</div>
		</div>
	</section>

	<?php if ( function_exists( 'wc_notice_count' ) && wc_notice_count() > 0 ) : ?>
	<div class="hx-wrap hxshop-notices"><?php woocommerce_output_all_notices(); ?></div>
	<?php endif; ?>

	<!-- 2. The Complete Ritual -->
	<?php if ( $bundle ) :
		$bundle_url     = get_permalink( $bundle->get_id() );
		$bundle_atc_url = add_query_arg( 'add-to-cart', $bundle->get_id(), wc_get_cart_url() );
		$bundle_can_buy = $bundle->is_purchasable() && $bundle->is_in_stock();
		?>
	<section class="hxshop-ritual" id="cos-ritual" aria-labelledby="hxshop-ritual-title">
		<div class="hx-wrap hxshop-ritual__grid">
			<a class="hxshop-ritual__media hx-frame" href="<?php echo esc_url( $bundle_url ); ?>" tabindex="-1" aria-hidden="true" data-hx-reveal="settle">
				<?php echo cos_h26_picture( 'final-tray-wide', array( 1280, 1920, 2560 ), array( 16, 9 ), '', array( 'class' => 'hxshop-ritual__pic', 'sizes' => '(min-width: 900px) 104vw, 222vw', 'w' => 1920 ) ); ?>
			</a>
			<div class="hxshop-ritual__body">
				<p class="hx-label" data-hx-reveal>The Complete Ritual</p>
				<h2 id="hxshop-ritual-title" class="hx-heading hxshop-ritual__title" data-hx-reveal>The four formulas, as one ritual.</h2>
				<p class="hx-body hxshop-ritual__copy" data-hx-reveal>Cleanse to restore, presented together: one considered routine for morning and evening.</p>
				<ol class="hxshop-ritual__steps" data-hx-reveal>
					<?php foreach ( $individual as $sp ) :
						$d = $h26[ $sp->get_slug() ] ?? null; ?>
					<li>
						<?php if ( $d ) : ?><span class="hxshop-ritual__step"><span class="hx-sr">Step </span><span class="hxshop-ritual__num"><?php echo esc_html( $d['num'] ); ?></span><span class="hxshop-ritual__verb"><?php echo esc_html( $d['verb'] ); ?></span></span><?php endif; ?>
						<span class="hxshop-ritual__name"><?php echo $nb( $sp->get_name() ); ?></span>
					</li>
					<?php endforeach; ?>
				</ol>
				<div class="hxshop-ritual__price" data-hx-reveal>
					<span class="hxshop-ritual__amount"><span class="hx-sr">Price: </span><?php echo wp_kses_post( wc_price( $bundle_price ) ); ?></span>
					<?php if ( $bundle_saving > 0 ) : ?>
					<span class="hxshop-ritual__compare"><?php echo wp_kses_post( wc_price( $separate_total ) ); ?> bought separately<span class="hxshop-ritual__sep" aria-hidden="true"></span><span class="hx-sr">; </span><span class="hxshop-ritual__save">you save <?php echo wp_kses_post( wc_price( $bundle_saving ) ); ?></span></span>
					<?php endif; ?>
				</div>
				<div class="hxshop-ritual__actions" data-hx-reveal>
					<?php if ( $bundle_can_buy ) : ?>
					<a href="<?php echo esc_url( $bundle_atc_url ); ?>" class="hx-btn hxshop-ritual__add hxshop-add cos-bundle-btn-primary add_to_cart_button ajax_add_to_cart" data-product_id="<?php echo esc_attr( $bundle->get_id() ); ?>" data-product_sku="<?php echo esc_attr( $bundle->get_sku() ); ?>" data-quantity="1" data-hxshop-name="<?php echo esc_attr( $bundle->get_name() ); ?>" aria-label="<?php echo esc_attr( sprintf( 'Add %s to bag', $bundle->get_name() ) ); ?>" rel="nofollow"><span class="hxshop-add__label">Add to bag</span></a>
					<?php else : ?>
					<p class="hx-detail hxshop-unavailable">Temporarily unavailable</p>
					<?php endif; ?>
					<a href="<?php echo esc_url( $bundle_url ); ?>" class="hx-link hxshop-ritual__more cos-bundle-view">Discover the ritual<span class="hx-arrow" aria-hidden="true"></span></a>
				</div>
				<p class="hx-detail hxshop-ritual__service" data-hx-reveal><?php echo esc_html( cos_child_shipping_message( 'short' ) ); ?><span class="hxshop-dot" aria-hidden="true"></span>30-day returns</p>
			</div>
		</div>
	</section>
	<?php endif; ?>

	<!-- 3. The four formulas -->
	<section class="hxshop-list" id="cos-products" aria-labelledby="hxshop-list-title">
		<div class="hx-wrap">
			<header class="hxshop-list__head">
				<h2 id="hxshop-list-title" class="hx-heading hxshop-list__title" data-hx-reveal>The four formulas</h2>
				<p class="hx-lead hxshop-list__lede" data-hx-reveal data-hx-delay="160">Each made for one step, each complete on its own. Choose one, or follow all four.</p>
			</header>
			<ol class="hxshop-list__items" data-hx-stagger>
				<?php foreach ( $individual as $product ) :
					$slug    = $product->get_slug();
					$d       = $h26[ $slug ] ?? null;
					$key     = $objects[ $slug ] ?? '';
					$url     = get_permalink( $product->get_id() );
					$name    = $product->get_name();
					$can_buy = $product->is_type( 'simple' ) && $product->is_purchasable() && $product->is_in_stock();
					$size    = $d['size'] ?? '';
					if ( ! $size ) {
						foreach ( $product->get_attributes() as $attr ) {
							if ( strtolower( $attr->get_name() ) === 'size' ) { $size = $attr->get_options()[0] ?? ''; break; }
						}
					}
					?>
				<li class="hxshop-item cos-card" data-hx-reveal>
					<a class="hxshop-item__stage cos-select-item" href="<?php echo esc_url( $url ); ?>" data-product_id="<?php echo esc_attr( $product->get_id() ); ?>" tabindex="-1" aria-hidden="true">
						<?php
						if ( $key ) {
							echo cos_h26_picture( 'collection-object-' . $key, array( 480, 720, 960 ), array( 2, 3 ), '', array( 'class' => 'hxshop-item__object', 'sizes' => '(min-width: 1100px) min(24vw, 360px), (min-width: 640px) 300px, 46vw', 'w' => 720 ) );
						} else {
							echo $product->get_image( 'woocommerce_single', array( 'class' => 'hxshop-item__wcimg', 'alt' => '' ) ); // phpcs:ignore
						}
						?>
					</a>
					<?php if ( $d ) : ?>
					<p class="hxshop-item__step"><span class="hx-sr">Step </span><span class="hxshop-item__num"><?php echo esc_html( $d['num'] ); ?></span><span class="hxshop-item__verb"><?php echo esc_html( $d['verb'] ); ?></span></p>
					<?php else : ?>
					<p class="hxshop-item__step" aria-hidden="true"></p>
					<?php endif; ?>
					<h3 class="hxshop-item__name">
						<a class="cos-select-item" href="<?php echo esc_url( $url ); ?>" data-product_id="<?php echo esc_attr( $product->get_id() ); ?>">
							<?php if ( $d ) : ?><span><?php echo $nb( $d['name'][0] ); ?></span> <span><?php echo $nb( $d['name'][1] ); ?></span><?php else : echo esc_html( $name ); endif; ?>
						</a>
					</h3>
					<p class="hxshop-item__benefit"><?php echo $d ? $nb( $d['benefit'] ) : esc_html( wp_trim_words( wp_strip_all_tags( $product->get_short_description() ), 10, '…' ) ); ?></p>
					<p class="hxshop-item__meta">
						<span class="hxshop-item__price"><?php echo wp_kses_post( wc_price( (float) $product->get_price() ) ); ?></span>
						<?php if ( $size ) : ?><span class="hxshop-item__dot" aria-hidden="true"></span><span class="hx-sr"> for </span><span class="hxshop-item__size"><?php echo esc_html( $size ); ?></span><?php endif; ?>
					</p>
					<div class="hxshop-item__actions">
						<?php if ( $can_buy ) : ?>
						<a href="<?php echo esc_url( add_query_arg( 'add-to-cart', $product->get_id(), wc_get_cart_url() ) ); ?>" class="hxshop-item__add hxshop-add cos-card-btn add_to_cart_button ajax_add_to_cart" data-product_id="<?php echo esc_attr( $product->get_id() ); ?>" data-product_sku="<?php echo esc_attr( $product->get_sku() ); ?>" data-quantity="1" data-hxshop-name="<?php echo esc_attr( $name ); ?>" aria-label="<?php echo esc_attr( sprintf( 'Add %s to bag', $name ) ); ?>" rel="nofollow"><span class="hxshop-add__label">Add to bag</span></a>
						<?php elseif ( ! $product->is_in_stock() ) : ?>
						<p class="hx-detail hxshop-unavailable">Currently unavailable</p>
						<?php else : ?>
						<a href="<?php echo esc_url( $url ); ?>" class="hxshop-item__add">Choose<span class="hx-sr"> <?php echo esc_html( $name ); ?></span></a>
						<?php endif; ?>
					</div>
				</li>
				<?php endforeach; ?>
			</ol>
		</div>
	</section>

	<!-- 4. About the collection (services live in the sitewide footer) -->
	<section class="hxshop-about" aria-labelledby="hxshop-about-title">
		<div class="hx-wrap">
			<div class="hxshop-about__grid">
				<div class="hxshop-about__intro">
					<h2 id="hxshop-about-title" class="hx-heading hxshop-about__title" data-hx-reveal>Built as one system</h2>
					<p class="hx-body" data-hx-reveal>This collection is made for the visible signs of ageing (fine lines, dullness, dryness and loss of firmness) as a considered daily ritual rather than a shelf of disconnected products. Every formula draws from the same High-Tech Complex, in concentrations suited to each step’s role, and each product’s study results are published on the <a class="hxshop-inline" href="/science/clinical-studies/">Clinical Studies</a> page.</p>
				</div>

				<div class="hxshop-about__faq" data-hx-reveal>
					<details class="hxshop-faq">
						<summary class="hxshop-faq__q">How the ritual works<span class="hxshop-faq__icon" aria-hidden="true"></span></summary>
						<div class="hxshop-faq__a hx-body">
							<p>Micellar Water Cleanser removes make-up and daily impurities without stripping the skin, preparing it for the steps that follow. Advanced Repair Serum concentrates the firming actives. Revitalizing Eye Cream treats the eye contour specifically. 24h Regeneration Cream seals the routine, morning and night.</p>
							<p>Every formula can be used on its own, alongside an existing routine, or as <a href="/products/the-complete-ritual/">The Complete Ritual</a>. See the full four-step sequence in <a href="/the-ritual/">the ritual guide</a>.</p>
						</div>
					</details>
					<details class="hxshop-faq">
						<summary class="hxshop-faq__q">Ingredients and clinical evidence<span class="hxshop-faq__icon" aria-hidden="true"></span></summary>
						<div class="hxshop-faq__a hx-body">
							<p>Every formula draws from the same High-Tech Complex (Caviar Extract, SYN&reg;-COLL Peptide, Hyaluronic Acid, Superoxide Dismutase and Resveratrol) in the concentrations suited to each step’s role. Caviar Extract comes from the roe of Siberian sturgeon (<i>Acipenser baerii</i>) and is rich in lipids, amino acids and minerals: it is there for what it brings to the skin, not as a luxury signal.</p>
							<p>The range addresses five concerns: <a href="/skin-concerns/fine-lines-wrinkles/">fine lines and wrinkles</a>, <a href="/skin-concerns/loss-of-firmness/">loss of firmness</a>, <a href="/skin-concerns/dark-circles/">dark circles</a>, <a href="/skin-concerns/dry-dehydrated-skin/">dryness</a> and <a href="/skin-concerns/dull-skin/">dullness</a>. In independent 28-day studies, Advanced Repair Serum showed +47% skin firmness (instrument-measured, cutometry); Revitalizing Eye Cream showed &minus;38% crow’s-feet depth (instrument-measured, optical profilometry); and 24h Regeneration Cream reached 100% panellist-reported satisfaction (self-reported). Each result comes from that product’s own study and is never a claim for the ritual as a whole. Full methodology, panel sizes and duration are published on the <a href="/science/clinical-studies/">Clinical Studies</a> page.</p>
						</div>
					</details>
					<details class="hxshop-faq">
						<summary class="hxshop-faq__q">Which product should I start with?<span class="hxshop-faq__icon" aria-hidden="true"></span></summary>
						<div class="hxshop-faq__a hx-body">
							<p>It depends on the concern that matters most: the cleanser for anyone simplifying an existing routine, the serum for firmness and fine lines, the eye cream for the eye contour, and the 24h cream for lasting hydration and comfort.</p>
							<p>Every formula can be used on its own, morning and evening, alongside an existing routine, or start with all four as <a href="/products/the-complete-ritual/">The Complete Ritual</a><?php if ( $bundle_saving > 0 ) : ?>, <?php echo wp_kses_post( wc_price( $bundle_saving ) ); ?> less than buying them separately<?php endif; ?>.</p>
						</div>
					</details>
				</div>
			</div>
		</div>
	</section>

	<p class="hx-sr" id="hxshop-status" role="status" aria-live="polite"></p>
</main>

<?php
// view_item_list / select_item — from the same $individual array rendered above.
$cos_list_items = array();
foreach ( $individual as $i => $listed_product ) {
	$cos_list_items[] = array(
		'item_id'        => $listed_product->get_sku() ?: (string) $listed_product->get_id(),
		'product_id'     => $listed_product->get_id(),
		'item_name'      => $listed_product->get_name(),
		'price'          => (float) wc_get_price_to_display( $listed_product ),
		'index'          => $i,
		'item_list_id'   => 'shop',
		'item_list_name' => 'Shop',
	);
}
?>
<script nonce="<?php echo esc_attr( cos_csp_nonce() ); ?>">
document.addEventListener('DOMContentLoaded', function () {
  var items = <?php echo wp_json_encode( $cos_list_items ); ?>;
  if (window.cos && cos.track && items.length) {
    cos.track('view_item_list', { item_list_id: 'shop', item_list_name: 'Shop', items: items });
  }
  document.querySelectorAll('.cos-select-item').forEach(function (el) {
    el.addEventListener('click', function () {
      var pid = parseInt(el.getAttribute('data-product_id'), 10);
      var match = items.find(function (i) { return i.product_id === pid; });
      if (match && window.cos && cos.track) {
        cos.track('select_item', { item_list_id: 'shop', item_list_name: 'Shop', items: [match] });
      }
    });
  });
});
</script>

<?php wp_footer(); ?>
<script nonce="<?php echo esc_attr( cos_csp_nonce() ); ?>">
/* Add-to-bag feedback (shop only): Adding… → Added, a polite screen-reader
   announcement, and WooCommerce's appended "View cart" link renamed "View bag". */
(function ($) {
  if (!$) return;
  var status = document.getElementById('hxshop-status');
  function label(btn, text) { btn.find('.hxshop-add__label').text(text); }
  $(document.body).on('adding_to_cart', function (e, btn) {
    if (!btn || !btn.hasClass('hxshop-add')) return;
    btn.addClass('is-busy').attr('aria-busy', 'true');
    label(btn, 'Adding…');
  });
  // WooCommerce appends its "View cart" link after our handler runs; rename it once it exists.
  $(document.body).on('wc_cart_button_updated', function (e, btn) {
    if (!btn || !btn.hasClass('hxshop-add')) return;
    btn.siblings('.added_to_cart').text('View bag').addClass('hx-link hxshop-viewbag');
  });
  $(document.body).on('added_to_cart', function (e, fragments, hash, btn) {
    if (!btn || !btn.hasClass('hxshop-add')) return;
    btn.removeClass('is-busy').removeAttr('aria-busy').addClass('is-added');
    label(btn, 'Added to bag');
    if (status) status.textContent = (btn.attr('data-hxshop-name') || 'Item') + ' added to your bag.';
    clearTimeout(btn[0].hxshopT);
    btn[0].hxshopT = setTimeout(function () {
      btn.removeClass('is-added');
      label(btn, 'Add to bag');
    }, 2400);
  });
})(window.jQuery);
</script>
</body>
</html>
