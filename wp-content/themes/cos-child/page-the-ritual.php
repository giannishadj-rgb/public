<?php
/**
 * COS — The Ritual (/the-ritual/, page 59). Whole-site rebuild, 16 Oct 2026; prefix hxrt-.
 *
 * Rendered from theme components instead of the hand-written HTML in post_content (which
 * stays in the database untouched, as the source and fallback). WordPress picks this file
 * by page slug (page-{slug}.php); the old block template templates/page-the-ritual.html
 * (it only printed post_content) was moved to
 * design-sources/home-2026/rebuild-2026-10-14/work/the-ritual/page-the-ritual.html.bak so
 * this PHP template wins the hierarchy. Delete this file and restore that one to roll back.
 *
 * Sections (template-parts/hx/the-ritual/): hero, prelude, steps, order, day, offer.
 * Styles: assets/css/hx/the-ritual.css (on top of the sitewide hx/system.css).
 * Copy: carried over from the DB content and inc/product-data.php (how-to-apply, amounts,
 * who it suits), cosmetic wording only; prices are live WooCommerce prices.
 */
defined( 'ABSPATH' ) || exit;

add_action( 'wp_enqueue_scripts', static function (): void {
	$dir = get_stylesheet_directory();
	wp_enqueue_style( 'cos-hx-the-ritual', get_stylesheet_directory_uri() . '/assets/css/hx/the-ritual.css', array( 'cos-hx-system' ), (string) filemtime( $dir . '/assets/css/hx/the-ritual.css' ) );
}, 30 );

add_filter( 'body_class', static function ( array $classes ): array {
	$classes[] = 'hxrt-page';
	return $classes;
} );

// Hero image preload (the 4:5 tray crop is the LCP on every viewport).
add_action( 'wp_head', static function (): void {
	echo '<link rel="preload" as="image" type="image/avif" fetchpriority="high" imagesrcset="' . esc_attr( cos_h26_srcset( 'hxrt-tray', array( 720, 880 ), 'avif' ) ) . '" imagesizes="(min-width: 900px) 46vw, 100vw">' . "\n";
}, 2 );

/** Internal page URL by path, with a literal fallback. */
$hxrt_url = static function ( string $path ): string {
	$page = get_page_by_path( trim( $path, '/' ) );
	return $page ? get_permalink( $page ) : home_url( $path );
};

/*
 * The four steps. Base data (name, url, live price, size, verb) from cos_h26_products();
 * the ritual copy below from post_content of page 59 and the 'how' / 'faqs' / 'concerns'
 * of inc/product-data.php (COS-001 … COS-004). Usage amounts follow the product pages
 * (the old DB page said "3–4 drops … 30 seconds" for the serum and "rinse" for the
 * cleanser; the PDP data, which the homepage also uses, says 2–3 drops, 60 seconds, no rinse).
 */
$hxrt_copy = array(
	'micellar-water-cleanser' => array(
		'key'    => 'cleanser',
		'role'   => 'The first gesture',
		'what'   => 'Micelles lift away make-up, impurities and pollutants, while Aloe Vera, Tamarind Seed Extract and a Skin Barrier Optimizer preserve comfort and prepare the skin for Advanced Repair Serum. It cleanses, tones and refreshes in one step, without rinsing or residue.',
		'amount' => 'One soaked cotton pad',
		'when'   => 'Morning and evening',
		'how'    => array(
			'Soak a cotton pad with Micellar Water.',
			'Sweep it gently across the face and neck in upward motions.',
			'For eye make-up, hold the pad over the closed eye for a few seconds, then wipe downwards and outwards. Do not rub.',
			'No rinsing required. Follow immediately with Advanced Repair Serum.',
		),
		'suits'  => 'Gentle for most skin types, including sensitive skin (if you have specific concerns, we recommend a patch test). For daily impurities, make-up removal, dryness and tightness.',
		'alt'    => 'A model holding the Micellar Water All-in-One Cleanser against her shoulder',
	),
	'advanced-repair-serum'   => array(
		'key'    => 'serum',
		'role'   => 'The heart of the ritual',
		'what'   => 'The most concentrated formula of the ritual. Micro-encapsulated caviar extract and Syn®-Coll peptide support a firmer, smoother-looking complexion. Its silky, non-oily texture absorbs quickly, so the steps that follow layer without heaviness.',
		'amount' => 'Two to three drops',
		'when'   => 'Morning and evening',
		'how'    => array(
			'Apply to cleansed skin, after the Micellar Water.',
			'Dispense two or three drops into the palm and press gently across face and neck.',
			'Allow 60 seconds to absorb fully before the next step.',
		),
		'suits'  => 'For fine lines and wrinkles, dullness, loss of firmness, environmental stress and dehydrated-looking skin.',
		'alt'    => 'A model resting her cheek on her hands around the Advanced Repair Serum',
	),
	'revitalizing-eye-cream'  => array(
		'key'    => 'eye',
		'role'   => 'The detail that changes everything',
		'what'   => 'Calibrated for the thinnest skin of the face. It melts on contact, helps diminish the appearance of %1$s and smooths the look of %2$s around the eyes, leaving the contour hydrated and softly replenished.',
		'links'  => array( array( 'dark circles', '/skin-concerns/dark-circles/' ), array( 'fine lines', '/skin-concerns/fine-lines-wrinkles/' ) ),
		'amount' => 'A grain of rice per eye',
		'when'   => 'Morning and evening',
		'how'    => array(
			'Apply after the serum, before the cream.',
			'Place a grain-of-rice amount on the ring finger, for each eye.',
			'Pat gently along the orbital bone, from the inner to the outer corner. Never drag.',
			'Compatible with SPF and make-up: allow a moment to absorb first.',
		),
		'suits'  => 'For an eye contour showing fine lines, dark circles, loss of firmness, dryness or visible fatigue.',
		'alt'    => 'A model holding the Revitalizing Eye Cream beside her cheek',
	),
	'24h-regeneration-cream'  => array(
		'key'    => 'cream',
		'role'   => 'The completion',
		'what'   => 'The final step seals the ritual. A silky, velvet-textured cream that locks in moisture and the serum and eye-care steps before it, with a caviar lipid complex that supports the skin’s barrier: lasting hydration and comfort, day and night.',
		'amount' => 'A pea-sized amount',
		'when'   => 'Morning and evening',
		'how'    => array(
			'The final step, after serum and eye cream.',
			'Warm a pea-sized amount between the palms for three seconds.',
			'Press it across face and neck to seal in the layers beneath.',
			'By day, wear it under a broad-spectrum SPF 50+. By night, it is your overnight treatment.',
		),
		'suits'  => 'For dryness, dehydration, fine lines and wrinkles, loss of elasticity and dullness.',
		'alt'    => 'A model smiling with her eyes closed, her arm around the 24h Regeneration Cream',
	),
);

$hxrt_steps = array();
foreach ( cos_h26_products() as $p ) {
	if ( empty( $hxrt_copy[ $p['slug'] ] ) ) continue;
	$c = $hxrt_copy[ $p['slug'] ];
	if ( ! empty( $c['links'] ) ) {
		$a = array();
		foreach ( $c['links'] as [ $label, $path ] ) {
			$a[] = '<a class="hxrt-inline" href="' . esc_url( $hxrt_url( $path ) ) . '">' . esc_html( $label ) . '</a>';
		}
		$c['what_html'] = sprintf( esc_html( $c['what'] ), ...$a );
	} else {
		$c['what_html'] = esc_html( $c['what'] );
	}
	$hxrt_steps[] = array_merge( $p, $c );
}

// The Complete Ritual offer: live WooCommerce prices (bundle vs the four bought separately).
$hxrt_bundle_post = get_page_by_path( 'the-complete-ritual', OBJECT, 'product' );
$hxrt_offer       = array(
	'url'      => $hxrt_bundle_post ? get_permalink( $hxrt_bundle_post ) : home_url( '/products/the-complete-ritual/' ),
	'price'    => cos_h26_ritual_price(),
	'separate' => '',
	'saving'   => '',
);
$hxrt_total  = cos_h26_individual_total();
$hxrt_bundle = ( $hxrt_bundle_post && function_exists( 'wc_get_product' ) && ( $wc = wc_get_product( $hxrt_bundle_post ) ) ) ? (float) $wc->get_price() : 0.0;
if ( $hxrt_total > 0 && $hxrt_bundle > 0 && $hxrt_total > $hxrt_bundle ) {
	$fmt                  = static fn( float $v ): string => html_entity_decode( wp_strip_all_tags( wc_price( $v, array( 'decimals' => 0 ) ) ), ENT_QUOTES, 'UTF-8' );
	$hxrt_offer['separate'] = $fmt( $hxrt_total );
	$hxrt_offer['saving']   = $fmt( $hxrt_total - $hxrt_bundle );
}

$hxrt_args = array(
	'steps' => $hxrt_steps,
	'offer' => $hxrt_offer,
	'url'   => $hxrt_url,
);
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<?php cos_child_custom_document_head( 'The Ritual — Caviar of Switzerland' ); ?>
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<main id="cos-main-content" class="hxrt">
<?php
foreach ( array( 'hero', 'prelude', 'steps', 'order', 'day', 'offer' ) as $hxrt_part ) {
	get_template_part( 'template-parts/hx/the-ritual/' . $hxrt_part, null, $hxrt_args );
}
?>
</main>
<?php wp_footer(); ?>
</body>
</html>
