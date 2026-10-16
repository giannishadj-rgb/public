<?php
/**
 * Swiss science pages — shell (16 Oct 2026). Routed by inc/hx-science.php for the hub and its
 * five chapters; the body comes from template-parts/hx/science/<part>.php.
 * Styles: assets/css/hx/science-pages.css (prefix hxsc-).
 */
defined( 'ABSPATH' ) || exit;

$hxsc_route = hxsc_route();
$hxsc_args  = array(
	'part'    => $hxsc_route[0],
	'chapter' => $hxsc_route[1],
	'path'    => '/' . $hxsc_route[2] . '/',
);
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<?php cos_child_custom_document_head( get_the_title() . ' — Caviar of Switzerland' ); ?>
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<main id="cos-main-content" class="hxsc">
<?php
if ( '' !== $hxsc_args['chapter'] ) {
	get_template_part( 'template-parts/hx/science/subnav', null, $hxsc_args );
}
get_template_part( 'template-parts/hx/science/' . $hxsc_args['part'], null, $hxsc_args );
if ( '' !== $hxsc_args['chapter'] ) {
	get_template_part( 'template-parts/hx/science/next', null, $hxsc_args );
}
?>
</main>
<?php wp_footer(); ?>
</body>
</html>
