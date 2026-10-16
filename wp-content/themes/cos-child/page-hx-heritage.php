<?php
/**
 * COS — Heritage pages (whole-site rebuild, 16 Oct 2026).
 *
 * Routed by inc/hx-heritage.php for /heritage/, /heritage/craftsmanship/ and
 * /heritage/sustainability/. Content: template-parts/hx/heritage/{hub,craftsmanship,sustainability}.php.
 * Styles: assets/css/hx/heritage.css (hxhe-).
 */
defined( 'ABSPATH' ) || exit;

$cos_hxhe_part = cos_hxhe_part();
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<?php cos_child_custom_document_head( get_the_title() ); ?>
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<main id="cos-main-content" class="hxhe">
<?php get_template_part( 'template-parts/hx/heritage/' . $cos_hxhe_part ); ?>
</main>
<?php wp_footer(); ?>
</body>
</html>
