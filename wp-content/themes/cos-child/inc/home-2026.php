<?php
/**
 * Homepage 2026 — "Enter the world of Caviar of Switzerland".
 *
 * Owns the editorial homepage that lives on the page with slug `home-2026`.
 * Unlike older pages (hand-authored HTML in post_content), this page is
 * rendered from theme components: page-home-2026.php + template-parts/home-2026/*.
 *
 * Look: the "Nocturne" layer (assets/css/home-2026-nocturne.css) over the base
 * component styles (home-2026.css). On 4 Oct 2026 the owner chose it as the only
 * homepage; the previous homepage (page 50), its draft variant (23767) and the
 * look previews (23794 nocturne, 23795 atelier) were moved to the trash.
 * Backup of page 50: app/private-exports/home50_backup_2026-10-01_*.
 */

defined( 'ABSPATH' ) || exit;

const COS_H26_SLUG = 'home-2026';

/** True when the current request renders the 2026 homepage (as front page or at its own URL). */
function cos_h26_is_active(): bool {
	if ( is_admin() || ! is_page() ) return false;
	$post = get_queried_object();
	return $post instanceof WP_Post && COS_H26_SLUG === $post->post_name;
}

/** True when the 2026 homepage is the site's front page. */
function cos_h26_is_front(): bool {
	return cos_h26_is_active() && is_front_page();
}

// The child theme's templates/front-page.html wins the hierarchy for any front
// page, so the component template is routed explicitly.
add_filter( 'template_include', static function ( string $template ): string {
	return cos_h26_is_active() ? get_stylesheet_directory() . '/page-home-2026.php' : $template;
}, 99 );

add_filter( 'body_class', static function ( array $classes ): array {
	if ( cos_h26_is_active() ) array_push( $classes, 'cos-h26', 'cos-h26-nocturne' );
	return $classes;
} );

add_action( 'wp_enqueue_scripts', static function (): void {
	if ( ! cos_h26_is_active() ) return;
	$dir = get_stylesheet_directory();
	$uri = get_stylesheet_directory_uri();
	wp_enqueue_style( 'cos-home-2026', $uri . '/assets/css/home-2026.css', array(), (string) filemtime( $dir . '/assets/css/home-2026.css' ) );
	// The "Nocturne" look (dark obsidian + champagne, owner's pick 4 Oct 2026) is
	// the homepage's look: a layer over the base component styles above.
	wp_enqueue_style( 'cos-home-2026-nocturne', $uri . '/assets/css/home-2026-nocturne.css', array( 'cos-home-2026' ), (string) filemtime( $dir . '/assets/css/home-2026-nocturne.css' ) );
	wp_enqueue_script( 'cos-home-2026', $uri . '/assets/js/home-2026.js', array(), (string) filemtime( $dir . '/assets/js/home-2026.js' ), array( 'strategy' => 'defer', 'in_footer' => true ) );
} );

// The homepage is light-led (pearl + graphite, 4 Oct 2026): tell the browser, so native
// controls, scrollbars and mobile browser chrome match. Homepage only.
add_action( 'wp_head', static function (): void {
	if ( ! cos_h26_is_active() ) return;
	echo '<meta name="color-scheme" content="light">' . "
" . '<meta name="theme-color" content="#F2F2EF">' . "
";
}, 1 );

// Hero LCP: preload only the AVIF crop this viewport uses (matches the
// <source media> breakpoints in template-parts/home-2026/hero.php).
add_action( 'wp_head', static function (): void {
	if ( ! cos_h26_is_active() ) return;
	$hero = cos_h26_hero();
	foreach ( array(
		array( '(min-width: 1100px)', cos_h26_srcset( $hero['desktop'], $hero['desktop_w'], 'avif' ), $hero['desktop_sizes'] ?? '100vw' ),
		array( '(max-width: 1099.98px)', cos_h26_srcset( $hero['mobile'], $hero['mobile_w'], 'avif' ), '100vw' ),
	) as [ $media, $set, $sizes ] ) {
		echo '<link rel="preload" as="image" type="image/avif" fetchpriority="high" media="' . esc_attr( $media ) . '" imagesrcset="' . esc_attr( $set ) . '" imagesizes="' . esc_attr( $sizes ) . '">' . "\n";
	}
}, 2 );

/* ─── Admin: homepage content still to add ─────────────────────── */

// Editorial to-dos live in wp-admin, never in the customer-facing page.
add_action( 'wp_dashboard_setup', static function (): void {
	if ( ! current_user_can( 'edit_pages' ) ) return;
	wp_add_dashboard_widget( 'cos_h26_content_todo', 'Homepage — content to add', static function (): void {
		$items = array(
			array( 'Verified customer reviews', 'Genuine reviews from a verified review provider. Never invent names, quotes or star ratings.' ),
			array( 'Press & recognition', 'Real press mentions, awards or certifications, each with a source that can be linked.' ),
		);
		echo '<p>The homepage has no review or press section yet. Add one only once genuine material exists:</p><ul style="list-style:disc;padding-left:1.4em">';
		foreach ( $items as [ $title, $note ] ) {
			echo '<li><strong>' . esc_html( $title ) . '</strong> — ' . esc_html( $note ) . '</li>';
		}
		echo '</ul>';
	} );
} );

/* ─── Data ─────────────────────────────────────────────────────── */

/**
 * Which hero image set is live. 'night' = CoS_Collection_Night (caviar-orb
 * backdrop); 'shelf' = owner's black-shelf render; 'orb' = the same render
 * with a subdued caviar disc behind and the cleanser label corrected to 150 ml.
 * The original shelf trial still reads "50 ml". Switch here.
 */
const COS_H26_HERO = 'jar'; // 11 Oct 2026 (owner); previous: 'mirror' (4 Oct), 'orb'

function cos_h26_hero(): array {
	$sets = array(
		'night' => array( 'desktop' => 'hero-desktop', 'desktop_w' => array( 1280, 1920, 2560 ), 'mobile' => 'hero-mobile', 'mobile_w' => array( 640, 960, 1280 ), 'mobile_ratio' => array( 1730, 1830 ), 'alt' => 'The four Caviar of Switzerland formulas, Micellar Water Cleanser, Advanced Repair Serum, 24h Regeneration Cream and Revitalizing Eye Cream, standing before a luminous disc of caviar pearls' ),
		// Owner render, 4 Oct 2026: the four formulas on stepped mirrored glass, light grey.
		// Cleanser label corrected 50 -> 150 ml (design-sources/home-2026/fix_mirror_hero.py).
		// Desktop: full screen, from hero5-crisp (backdrop extended left with sharp
		// glass edges and no blur, owner pick in live mode 7 Oct 2026;
		// build_hero_noblur_variants.py). Phones/
		// tablets: square product crop after the copy. Light photo → .h26-hero--light.
		'mirror' => array( 'desktop' => 'hero5-crisp', 'desktop_w' => array( 1440, 2172 ), 'mobile' => 'hero4-mobile', 'mobile_w' => array( 640, 770 ), 'mobile_ratio' => array( 1, 1 ), 'light' => true, 'alt' => 'The four Caviar of Switzerland formulas, Advanced Repair Serum, Revitalizing Eye Cream, 24h Regeneration Cream and Micellar Water Cleanser, arranged on stepped mirrored glass against a soft grey background' ),
		// Owner image, 11 Oct 2026: the 24h Regeneration Cream on pearl silk
		// (design-sources/home-2026/build_hero_jar.py). Light on every screen
		// ('pearl'): graphite copy and header over the photograph.
		'jar'   => array( 'desktop' => 'hero-jar-wide', 'desktop_w' => array( 1280, 1672 ), 'mobile' => 'hero-jar-sq', 'mobile_w' => array( 640, 880 ), 'mobile_ratio' => array( 1, 1 ), 'light' => true, 'pearl' => true, 'alt' => 'The 24h Regeneration Cream, open with its silver lid resting against the jar, on flowing pearl silk' ),
		'orb'   => array( 'desktop' => 'hero3-desktop-restrained', 'desktop_w' => array( 1280, 1920 ), 'mobile' => 'hero3-mobile-restrained', 'mobile_w' => array( 640, 792 ), 'mobile_ratio' => array( 792, 941 ), 'alt' => 'The four Caviar of Switzerland formulas, Revitalizing Eye Cream, Advanced Repair Serum, 24h Regeneration Cream and Micellar Water Cleanser, on a polished black shelf before a subdued disc of caviar pearls against a black background' ),
		'shelf' => array( 'desktop' => 'hero2-desktop', 'desktop_w' => array( 1280, 1920 ), 'mobile' => 'hero2-mobile', 'mobile_w' => array( 640, 792 ), 'mobile_ratio' => array( 792, 837 ), 'alt' => 'The four Caviar of Switzerland formulas, Revitalizing Eye Cream, Advanced Repair Serum, 24h Regeneration Cream and Micellar Water Cleanser, lined up on a polished black shelf' ),
	);
	return $sets[ COS_H26_HERO ];
}

/* ─── Product data ─────────────────────────────────────────────── */

/**
 * The four core formulas. Copy is limited to claims already published on the
 * site (PDP benefit lines, /science/why-caviar/, /science/clinical-studies/).
 */
function cos_h26_products(): array {
	$products = array(
		array(
			'slug'    => 'micellar-water-cleanser',
			'num'     => '01',
			'verb'    => 'Cleanse',
			'cue'     => 'Sweep with a cotton pad. No rinsing.', // from 'how' in inc/product-data.php
			'benefit' => 'Removes make-up & replenishes lipids',
			'name'    => array( 'Micellar Water', 'All-in-One Cleanser' ),
			'size'    => '150 ml',
			'card'    => 'card-cleanser-photo', // owner's studio packshot WITH its own backdrop (build_card_photo.py); cut-out alternative: card-cleanser-texture
			'story'   => 'story-cleanser',
			'alt'     => 'A hand holding the Micellar Water All-in-One Cleanser as droplets of water splash from the bottle',
			'cardalt' => 'Micellar Water All-in-One Cleanser bottle with a polished silver cap',
			'copy'    => 'A single step that gently lifts make-up and the day’s impurities, while caviar extract helps replenish the lipids that cleansing removes.',
		),
		array(
			'slug'    => 'advanced-repair-serum',
			'num'     => '02',
			'verb'    => 'Treat',
			'cue'     => 'Press two or three drops into the skin.', // from 'how' in inc/product-data.php
			'benefit' => 'Visibly firms & smooths',
			'name'    => array( 'Advanced Repair', 'Serum' ),
			'size'    => '30 ml',
			'card'    => 'card-serum-photo', // owner's studio packshot WITH its own backdrop (build_card_photo.py); cut-out alternatives: card-serum-plain, card-serum-texture
			'story'   => 'story-serum',
			'alt'     => 'The Advanced Repair Serum standing before a woman in a black halter top',
			'cardalt' => 'Advanced Repair Serum bottle with polished silver caps',
			'copy'    => 'The most concentrated formula of the ritual. Micro-encapsulated caviar extract and Syn®-Coll peptide support a firmer, smoother-looking complexion.',
		),
		array(
			'slug'    => 'revitalizing-eye-cream',
			'num'     => '03',
			'verb'    => 'Revive',
			'cue'     => 'Pat gently along the orbital bone.', // from 'how' in inc/product-data.php
			'benefit' => 'Softens the look of dark circles & fine lines',
			'name'    => array( 'Revitalizing', 'Eye Cream' ),
			'size'    => '15 ml',
			'card'    => 'card-eye-photo', // owner's studio packshot WITH its own backdrop (build_card_photo.py); cut-out alternative: card-eye-texture
			'story'   => 'story-eye',
			'alt'     => 'The Revitalizing Eye Cream held between the cheeks of two women with their eyes closed',
			'cardalt' => 'Revitalizing Eye Cream bottle with polished silver caps',
			'copy'    => 'Calibrated for the thinnest skin of the face. Helps diminish the appearance of dark circles and fine lines around the eyes.',
		),
		array(
			'slug'    => '24h-regeneration-cream',
			'num'     => '04',
			'verb'    => 'Restore',
			'cue'     => 'Warm a pea-sized amount, press in to seal.', // from 'how' in inc/product-data.php
			'benefit' => 'Lasting hydration & barrier support',
			'name'    => array( '24h Regeneration', 'Cream' ),
			'size'    => '50 ml',
			'card'    => 'card-cream-photo', // owner's studio packshot WITH its own backdrop (build_card_photo.py); cut-out alternative: card-cream-texture
			'story'   => 'story-cream',
			'alt'     => 'A woman with her head tilted back holding the 24h Regeneration Cream jar',
			'cardalt' => '24h Regeneration Cream jar with a polished silver lid, reflected on a glossy white surface',
			'copy'    => 'The final step seals the ritual. Lasting hydration and comfort, day and night, with a caviar lipid complex that supports the skin’s barrier.',
		),
	);

	foreach ( $products as &$p ) {
		$post       = get_page_by_path( $p['slug'], OBJECT, 'product' );
		$p['url']   = $post ? get_permalink( $post ) : home_url( '/products/' . $p['slug'] . '/' );
		$p['price'] = '';
		if ( $post && function_exists( 'wc_get_product' ) && ( $wc = wc_get_product( $post ) ) ) {
			$p['price'] = html_entity_decode( wp_strip_all_tags( wc_price( (float) $wc->get_price(), array( 'decimals' => 0 ) ) ), ENT_QUOTES, 'UTF-8' );
		}
	}
	return $products;
}

/** Price of The Complete Ritual bundle, formatted, or '' if unavailable. */
function cos_h26_ritual_price(): string {
	$post = get_page_by_path( 'the-complete-ritual', OBJECT, 'product' );
	if ( ! $post || ! function_exists( 'wc_get_product' ) || ! ( $wc = wc_get_product( $post ) ) ) return '';
	return html_entity_decode( wp_strip_all_tags( wc_price( (float) $wc->get_price(), array( 'decimals' => 0 ) ) ), ENT_QUOTES, 'UTF-8' );
}

/**
 * What the four formulas cost bought separately (live WooCommerce prices), for
 * an honest comparison beside the Complete Ritual. 0 if any price is missing.
 */
function cos_h26_individual_total(): float {
	$total = 0.0;
	foreach ( cos_h26_products() as $p ) {
		$post = get_page_by_path( $p['slug'], OBJECT, 'product' );
		$wc   = ( $post && function_exists( 'wc_get_product' ) ) ? wc_get_product( $post ) : null;
		if ( ! $wc || '' === $wc->get_price() ) return 0.0;
		$total += (float) $wc->get_price();
	}
	return $total;
}

/* ─── Image helpers ────────────────────────────────────────────── */

function cos_h26_img_uri( string $file ): string {
	$uri  = get_stylesheet_directory_uri() . '/assets/images/home-2026/' . $file;
	$path = get_stylesheet_directory() . '/assets/images/home-2026/' . $file;
	// Version real files by mtime so re-exported images (same name) aren't served
	// stale from browser caches. Base names without an extension (the video
	// helper appends one) are returned untouched.
	return is_file( $path ) ? $uri . '?v=' . filemtime( $path ) : $uri;
}

function cos_h26_srcset( string $base, array $widths, string $ext ): string {
	return implode( ', ', array_map( static fn( int $w ): string => cos_h26_img_uri( "{$base}-{$w}.{$ext}" ) . " {$w}w", $widths ) );
}

/**
 * Responsive <picture> (AVIF → WebP → JPEG) for an asset built by the
 * home-2026 image script: files are {base}-{width}.{avif|webp|jpg}.
 *
 * @param array $ratio  [w, h] intrinsic ratio, used for width/height attrs.
 * @param array $opts   class, sizes, eager (bool), extra <source> art-direction list.
 */
function cos_h26_picture( string $base, array $widths, array $ratio, string $alt, array $opts = array() ): string {
	$sizes   = $opts['sizes'] ?? '100vw';
	$eager   = ! empty( $opts['eager'] );
	$largest = max( $widths );
	$w       = (int) ( $opts['w'] ?? min( $largest, 1280 ) );
	$h       = (int) round( $w * $ratio[1] / $ratio[0] );
	$out     = '<picture' . ( isset( $opts['class'] ) ? ' class="' . esc_attr( $opts['class'] ) . '"' : '' ) . '>';
	foreach ( $opts['art'] ?? array() as $art ) {
		foreach ( array( 'avif', 'webp' ) as $ext ) {
			$out .= '<source media="' . esc_attr( $art['media'] ) . '" type="image/' . $ext . '" srcset="' . esc_attr( cos_h26_srcset( $art['base'], $art['widths'], $ext ) ) . '" sizes="' . esc_attr( $art['sizes'] ?? $sizes ) . '">';
		}
		$out .= '<source media="' . esc_attr( $art['media'] ) . '" srcset="' . esc_attr( cos_h26_srcset( $art['base'], $art['widths'], 'jpg' ) ) . '" sizes="' . esc_attr( $art['sizes'] ?? $sizes ) . '">';
	}
	foreach ( array( 'avif', 'webp' ) as $ext ) {
		$out .= '<source type="image/' . $ext . '" srcset="' . esc_attr( cos_h26_srcset( $base, $widths, $ext ) ) . '" sizes="' . esc_attr( $sizes ) . '">';
	}
	$out .= '<img src="' . esc_url( cos_h26_img_uri( "{$base}-{$widths[ (int) floor( ( count( $widths ) - 1 ) / 2 ) ]}.jpg" ) ) . '"'
		. ' srcset="' . esc_attr( cos_h26_srcset( $base, $widths, 'jpg' ) ) . '" sizes="' . esc_attr( $sizes ) . '"'
		. ' width="' . $w . '" height="' . $h . '" alt="' . esc_attr( $alt ) . '"'
		. ( $eager ? ' loading="eager" fetchpriority="high"' : ' loading="lazy"' ) . ' decoding="async">';
	return $out . '</picture>';
}

/** The section-link arrow used throughout the page. */
function cos_h26_arrow(): string {
	return '<span class="h26-arrow" aria-hidden="true">→</span>';
}
