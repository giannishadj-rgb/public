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
// 25 Oct 2026 (owner: "hero image first", like the hub): a chapter opens on its bleed plate under
// the transparent header; its sub-navigation follows the plate (after the masthead's </header>).
ob_start();
get_template_part( 'template-parts/hx/science/' . $hxsc_args['part'], null, $hxsc_args );
$hxsc_body = (string) ob_get_clean();
if ( '' !== $hxsc_args['chapter'] ) {
	ob_start();
	get_template_part( 'template-parts/hx/science/subnav', null, $hxsc_args );
	$hxsc_sub  = (string) ob_get_clean();
	$hxsc_at   = strpos( $hxsc_body, '</header>' );
	$hxsc_body = false === $hxsc_at ? $hxsc_sub . $hxsc_body : substr_replace( $hxsc_body, $hxsc_sub, $hxsc_at + 9, 0 );
}
echo $hxsc_body; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- theme template output
if ( '' !== $hxsc_args['chapter'] ) {
	get_template_part( 'template-parts/hx/science/next', null, $hxsc_args );
}
?>
</main>
<?php wp_footer(); ?>
</body>
</html>
