<?php
/**
 * COS — Homepage 2026 (editorial).
 *
 * Routed by inc/home-2026.php for the `home-2026` page. Each section is a
 * component in template-parts/home-2026/; product copy comes from
 * cos_h26_products(). Styles: assets/css/home-2026.css.
 *
 * Section order follows the brief: luxury first, understanding second,
 * proof third, commerce fourth.
 */
defined( 'ABSPATH' ) || exit;

$cos_h26 = array(
	'products' => cos_h26_products(),
	'ritual'   => cos_h26_ritual_price(),
);
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<?php cos_child_custom_document_head( 'Caviar of Switzerland' ); ?>
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<main id="cos-main-content" class="h26">
<?php
foreach ( array( 'hero', 'collection', 'caviar', 'trust', 'provenance', 'final' ) as $cos_h26_part ) {
	get_template_part( 'template-parts/home-2026/' . $cos_h26_part, null, $cos_h26 );
}
?>
</main>
<?php wp_footer(); ?>
</body>
</html>
