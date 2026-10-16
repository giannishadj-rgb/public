<?php
/**
 * Caviar of Switzerland — single product (whole-site rebuild, 16 Oct 2026, prefix hxpd-).
 *
 * One template for the five products: the four formulas (COS-001…004) and The Complete
 * Ritual (COS-BUNDLE). Markup lives in template-parts/hx/product/, styles in
 * assets/css/hx/product.css, behaviour in assets/js/hx-product.js (all product-only).
 *
 *   gallery.php   owner photography: studio packshot first, then the WooCommerce gallery
 *                 (desktop: editorial stack beside a sticky purchase column; phones: swipe)
 *   purchase.php  step, name, promise, price · size, quantity + Add to bag (the primary
 *                 action; a real WooCommerce form that also works without JS), assurances,
 *                 benefits, details / INCI / delivery accordions
 *   formula.php   the four formulas: story, key ingredients, result, how to use, ritual, FAQ
 *   set.php       The Complete Ritual: the four steps, results per formula, order of use, FAQ
 *   sticky.php    phone add-to-bag bar, shown while the primary button is out of view
 *
 * Claims: each study figure stays with its own formula and method (page 347, BRIEF §4);
 * the cleanser has no study and the ritual has not been studied as a set — nothing here
 * implies otherwise. No ratings or reviews (brand rule; WooCommerce reviews are off).
 * Structured data: inc/schema.php (wp_head). SEO title/meta: inc/seo.php.
 * Previous template: design-sources/home-2026/rebuild-2026-10-14/work/product/single-product.before.php
 */
defined( 'ABSPATH' ) || exit;
require_once get_stylesheet_directory() . '/inc/hx-product.php';

the_post();
global $product;
if ( ! $product instanceof WC_Product ) $product = wc_get_product( get_the_ID() );

$hxpd = cos_hxpd_context( $product );

$theme_dir = get_stylesheet_directory();
$theme_uri = get_stylesheet_directory_uri();
wp_enqueue_style( 'cos-hx-product', $theme_uri . '/assets/css/hx/product.css', array( 'cos-hx-system' ), (string) filemtime( $theme_dir . '/assets/css/hx/product.css' ) );
wp_enqueue_script( 'cos-hx-product', $theme_uri . '/assets/js/hx-product.js', array( 'jquery' ), (string) filemtime( $theme_dir . '/assets/js/hx-product.js' ), array( 'strategy' => 'defer', 'in_footer' => true ) );
if ( function_exists( 'WC' ) ) wp_enqueue_script( 'wc-add-to-cart' );
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<?php cos_child_custom_document_head( single_post_title( '', false ) ?: 'Product - Caviar of Switzerland' ); ?>
<?php wp_head(); ?>
</head>
<body <?php body_class( 'woocommerce woocommerce-page hxpd-page' . ( $hxpd['is_set'] ? ' hxpd-page--set' : '' ) ); ?>>
<?php wp_body_open(); ?>

<main id="cos-main-content" class="hxpd<?php echo $hxpd['is_set'] ? ' hxpd--set' : ''; ?>">
	<section class="hxpd-buy" aria-labelledby="hxpd-name">
		<div class="hxpd-buy__grid">
			<?php get_template_part( 'template-parts/hx/product/gallery', null, $hxpd ); ?>
			<?php get_template_part( 'template-parts/hx/product/purchase', null, $hxpd ); ?>
		</div>
	</section>
	<?php get_template_part( 'template-parts/hx/product/' . ( $hxpd['is_set'] ? 'set' : 'formula' ), null, $hxpd ); ?>
</main>

<?php get_template_part( 'template-parts/hx/product/sticky', null, $hxpd ); ?>

<?php
$cos_view_item = array(
	'item_id'   => $hxpd['sku'] ?: (string) $product->get_id(),
	'item_name' => $hxpd['name'],
	'price'     => (float) wc_get_price_to_display( $product ),
);
?>
<script nonce="<?php echo esc_attr( cos_csp_nonce() ); ?>">
document.addEventListener('DOMContentLoaded', function () {
  if (window.cos && cos.track) {
    var item = <?php echo wp_json_encode( $cos_view_item ); ?>;
    cos.track('view_item', { currency: 'EUR', value: item.price, items: [item] });
  }
});
</script>

<?php wp_footer(); ?>
</body>
</html>
