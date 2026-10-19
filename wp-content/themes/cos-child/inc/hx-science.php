<?php
/**
 * Swiss science pages — theme templates (whole-site rebuild, 16 Oct 2026). Prefix hxsc-.
 *
 * The six science pages used to be hand-written HTML + CSS in post_content. They are now
 * rendered from theme components chosen by page path; the DB content is untouched and stays
 * as the fallback (remove a path from hxsc_routes() to fall back to it).
 *
 *   /science/                     page 344  hub          template-parts/hx/science/hub.php
 *   /science/why-caviar/          page 345  chapter 01   …/why-caviar.php
 *   /science/high-tech-complex/   page 346  chapter 02   …/high-tech-complex.php
 *   /science/ingredients/         page 60   chapter 03   …/ingredients.php
 *   /science/clinical-studies/    page 347  chapter 04   …/clinical-studies.php
 *   /science/swiss-formulation/   page 348  chapter 05   …/swiss-formulation.php
 *
 * Shell: page-hx-science.php. Shared parts: template-parts/hx/science/{masthead,index,next}.php.
 * Styles: assets/css/hx/science-pages.css (depends on cos-hx-system, sitewide since 16 Oct).
 * Titles/descriptions: inc/seo.php (keyed by slug, unchanged).
 *
 * Claim rules (BRIEF §4, PRODUCT.md, page 347 = source of truth for every study figure):
 * four separate single-product studies by an independent contract research organisation;
 * no study of the complete ritual; none of the Micellar Water Cleanser; the 24h cream's 100%
 * is self-reported; SYN®-COLL's 4.5× is in-vitro ingredient research, never a product result.
 */

defined( 'ABSPATH' ) || exit;

/** page path => [ part, chapter number ('' for the hub) ] */
function hxsc_routes(): array {
	return array(
		'science'                   => array( 'hub', '' ),
		'science/why-caviar'        => array( 'why-caviar', '01' ),
		'science/high-tech-complex' => array( 'high-tech-complex', '02' ),
		'science/ingredients'       => array( 'ingredients', '03' ),
		'science/clinical-studies'  => array( 'clinical-studies', '04' ),
		'science/swiss-formulation' => array( 'swiss-formulation', '05' ),
	);
}

/** The current science route ([ part, chapter, path ]) or null. */
function hxsc_route(): ?array {
	static $route = false;
	if ( false !== $route ) return $route;
	$route = null;
	if ( is_admin() || ! is_page() ) return $route;
	$post = get_queried_object();
	if ( ! $post instanceof WP_Post ) return $route;
	$path   = get_page_uri( $post );
	$routes = hxsc_routes();
	if ( isset( $routes[ $path ] ) ) $route = array( $routes[ $path ][0], $routes[ $path ][1], $path );
	return $route;
}

/** The five chapters, in reading order (also the sub-navigation). */
function hxsc_chapters(): array {
	return array(
		array( 'num' => '01', 'path' => '/science/why-caviar/', 'title' => 'Why caviar', 'line' => 'The material, and why its lipids matter.' ),
		array( 'num' => '02', 'path' => '/science/high-tech-complex/', 'title' => 'The High-Tech Complex', 'line' => 'Five actives, and how they work together.' ),
		array( 'num' => '03', 'path' => '/science/ingredients/', 'title' => 'The five actives', 'line' => 'Each active: its source and its role.' ),
		array( 'num' => '04', 'path' => '/science/clinical-studies/', 'title' => 'Clinical studies', 'line' => 'Every published figure, with its method.' ),
		array( 'num' => '05', 'path' => '/science/swiss-formulation/', 'title' => 'Swiss formulation', 'line' => 'How a formula is made and released in Zug.' ),
	);
}

/**
 * The five actives of the High-Tech Complex (round 2, 17 Oct: cosmetic wording only — brand rule,
 * BRIEF §2 — no "regeneration", "dermis", "penetration", immune or longevity language).
 * Sources: pages 344 / 346 / 60. 'roles' = Protect / Repair / Rejuvenate as on the homepage.
 * 'img' = [ src (theme- or uploads-relative), width, height, alt ] — the ingredient photographs,
 * shown in one warm monochrome (CSS) so five different stock photographs read as one set.
 */
function hxsc_actives(): array {
	$t = get_stylesheet_directory_uri() . '/assets/images/ingredients/';
	$u = content_url( '/uploads/2026/05/' );
	return array(
		array(
			'num'   => '01',
			'slug'  => 'caviar-extract',
			'name'  => 'Precious Caviar Extract',
			'inci'  => 'Acipenser Baerii Roe Extract (Caviar Extract)',
			'role'  => 'Barrier lipids',
			'roles' => array( 'protect', 'repair', 'rejuvenate' ),
			'desc'  => 'Full-spectrum extract of Acipenser Baerii (Siberian) sturgeon roe, rich in phospholipids, amino acids, vitamins and trace minerals. Its phospholipid profile mirrors the skin’s own intercellular lipids, so it helps replenish the barrier with the kind of lipids that cleansing and time remove. Micro-encapsulated to protect its potency.',
			'img'   => array( $t . 'caviar-extract-700x700.webp', 700, 700, 'Caviar pearls on a silver spoon at the lips, in black and white' ),
		),
		array(
			'num'   => '02',
			'slug'  => 'syn-coll-peptide',
			'name'  => 'Syn®‑Coll Peptide',
			'inci'  => 'Palmitoyl Tripeptide-1',
			'role'  => 'Firming peptide',
			'roles' => array( 'rejuvenate' ),
			'desc'  => 'Palmitoyl Tripeptide-1, the primary firming active in the High-Tech Complex. The peptide mimics a fragment of collagen, a signal studied in collagen research, for skin that looks firmer and smoother.',
			// In-vitro ingredient research: always shown with this label, never as a product result.
			'vitro' => array( '4.5×', 'Type I collagen in treated fibroblasts vs. control', 'Published in-vitro studies of the ingredient on dermal fibroblasts. Ingredient research in cell culture, not a result measured with our products.' ),
			'img'   => array( $t . 'syn-coll-peptide-700x700.webp', 700, 700, 'A pipette releasing drops of serum onto a glass dish beside a swatch of cream' ),
		),
		array(
			'num'   => '03',
			'slug'  => 'hyaluronic-acid',
			'name'  => 'Hyaluronic Acid',
			'inci'  => 'Sodium Hyaluronate / Hydrolysed Sodium Hyaluronate / Sodium Hyaluronate Crosspolymer',
			'role'  => 'Hydration at three depths',
			'roles' => array( 'repair' ),
			'desc'  => 'Three molecular weights hold moisture at the surface, in the outer layers and deeper in the epidermis, while a crosspolymer form sustains release for 24 hours. One molecule of hyaluronic acid can hold 1,000× its own weight in water.',
			'img'   => array( $u . 'hyaluronic-acid-700x700.webp', 700, 700, 'A cluster of clear bubbles in a fluid' ),
			'depth' => array(
				array( 'High', '1,500–1,800 kDa', 'Surface', 'Forms a hydrating film on the surface and helps prevent water loss (TEWL).' ),
				array( 'Medium', '200–500 kDa', 'Stratum corneum', 'Hydrates the outer layers of the epidermis.' ),
				array( 'Low', '10–50 kDa', 'Deeper epidermis', 'Hydrates the deeper epidermis, for a plumper, smoother look.' ),
			),
		),
		array(
			'num'   => '04',
			'slug'  => 'superoxide-dismutase',
			'name'  => 'Superoxide Dismutase',
			'inci'  => 'Superoxide Dismutase (SOD)',
			'role'  => 'Antioxidant enzyme',
			'roles' => array( 'protect' ),
			'desc'  => 'One of the skin’s own antioxidant enzymes. SOD converts the superoxide radical, the most abundant free radical produced in the skin, into hydrogen peroxide and oxygen: it neutralises the radical at its source, before the chain of stress that degrades collagen begins.',
			'img'   => array( $u . 'superoxide-dismutase-700x700.webp', 700, 700, 'Clouds of pigment unfolding in water' ),
		),
		array(
			'num'   => '05',
			'slug'  => 'resveratrol',
			'name'  => 'Resveratrol',
			'inci'  => 'Resveratrol (from Polygonum Cuspidatum)',
			'role'  => 'Antioxidant & UV resilience',
			'roles' => array( 'protect' ),
			'desc'  => 'A polyphenol extracted from Polygonum Cuspidatum root. A direct antioxidant that helps skin resist daily UV and environmental stress, and works alongside SOD for broader antioxidant cover.',
			'img'   => array( $u . 'resveratrol-700x700.webp', 700, 700, 'Dark grapes beaded with water' ),
		),
	);
}

/** Protect / Repair / Rejuvenate — the organising idea (owner-approved), as on the homepage. */
function hxsc_pillars(): array {
	return array(
		'protect'    => array( 'Protect', 'Micro‑encapsulated caviar keeps its potency, while two antioxidants help defend skin against daily environmental stress.' ),
		'repair'     => array( 'Repair', 'Caviar lipids, close to the skin’s own, help replenish its barrier as hyaluronic acid holds moisture at three depths.' ),
		'rejuvenate' => array( 'Rejuvenate', 'A peptide studied in collagen research joins caviar extract, for skin that looks firmer and smoother.' ),
	);
}

/**
 * Published study findings — page 347, verbatim facts. Each is a separate study of ONE formula.
 * kind: 'measured' (instrument) or 'reported' (panellist self-assessment).
 */
function hxsc_studies(): array {
	return array(
		array(
			'fig' => '+47', 'kind' => 'measured', 'obj' => 'serum', 'step' => '02',
			'product' => 'Advanced Repair Serum', 'url' => '/products/advanced-repair-serum/',
			'finding' => 'Improved skin firmness',
			'short'   => 'Skin firmness',
			'panel' => '32 women', 'ages' => '45–60', 'regimen' => 'Twice daily, 28 days', 'method' => 'Cutometry (R2 parameter)',
			'text' => 'Cutometry measurement of skin elasticity (R2 parameter) showed a statistically significant improvement in subjects using the Advanced Repair Serum twice daily for 28 days. All five High-Tech Complex actives contribute to this outcome through distinct but synergistic mechanisms, with the Syn®-Coll Peptide delivering the primary firming response.',
		),
		array(
			'fig' => '−38', 'kind' => 'measured', 'obj' => 'eye', 'step' => '03',
			'product' => 'Revitalizing Eye Cream', 'url' => '/products/revitalizing-eye-cream/',
			'finding' => 'Reduction in crow’s‑feet depth',
			'short'   => 'Crow’s‑feet depth',
			'panel' => '30 women', 'ages' => '42–65', 'regimen' => 'Twice daily, 28 days', 'method' => 'Optical profilometry',
			'text' => 'Optical profilometry measured a 38% average reduction in lateral peri-orbital fine line depth in subjects using the Revitalizing Eye Cream. The Syn®-Coll Peptide and Caviar Extract combination showed the strongest dermal response in the periorbital zone, the thinnest and most delicate skin on the face. This result underpins our <a href="/skin-concerns/dark-circles/">eye care for dark circles</a> and <a href="/skin-concerns/fine-lines-wrinkles/">skincare for fine lines and wrinkles</a>.',
		),
		array(
			'fig' => '100', 'kind' => 'reported', 'obj' => 'cream', 'step' => '04',
			'product' => '24h Regeneration Cream', 'url' => '/products/24h-regeneration-cream/',
			'finding' => 'Panellist satisfaction at 28 days',
			'short'   => 'Panellist‑reported satisfaction',
			'panel' => '34 women', 'ages' => '40–65', 'regimen' => 'Twice daily, 28 days', 'method' => 'Tewametry + self-assessment',
			'text' => 'Every panellist in the 24h Regeneration Cream study reported visible improvement in skin comfort, density and radiance at the 28-day assessment. Tewametry confirmed measurable improvement in barrier function across all subjects by Day 14, two weeks before the study’s final evaluation point. The same radiance result is the basis of our <a href="/skin-concerns/dull-skin/">skincare for dull skin and lost radiance</a> guidance.',
		),
		array(
			'fig' => '+22', 'kind' => 'measured', 'obj' => 'serum', 'step' => '02', 'interim' => true,
			'product' => 'Advanced Repair Serum', 'url' => '/products/advanced-repair-serum/',
			'finding' => 'Surface smoothness at Day 7',
			'short'   => 'Surface smoothness, Day 7',
			'panel' => '32 women', 'ages' => '45–60', 'regimen' => 'Day 7 interim', 'method' => 'Optical profilometry',
			'text' => 'Profilometry at the Day 7 interim assessment showed measurable skin texture refinement, an early-response result attributed to the fast-penetrating Low-MW Hyaluronic Acid matrix and the immediate free-radical neutralisation from Superoxide Dismutase. The result increased to +38% by Day 28.',
		),
	);
}

/* ─── Routing ─────────────────────────────────────────────────────── */

add_filter( 'template_include', static function ( string $template ): string {
	return hxsc_route() ? get_stylesheet_directory() . '/page-hx-science.php' : $template;
}, 99 );

add_filter( 'body_class', static function ( array $classes ): array {
	if ( $r = hxsc_route() ) array_push( $classes, 'hxsc-page', 'hxsc-page--' . $r[0] );
	return $classes;
} );

add_action( 'wp_enqueue_scripts', static function (): void {
	if ( ! hxsc_route() ) return;
	$dir = get_stylesheet_directory();
	wp_enqueue_style( 'cos-hx-science-pages', get_stylesheet_directory_uri() . '/assets/css/hx/science-pages.css', array( 'cos-hx-system' ), (string) filemtime( $dir . '/assets/css/hx/science-pages.css' ) );
}, 30 );

add_action( 'wp_head', static function (): void {
	if ( ! hxsc_route() ) return;
	echo '<meta name="color-scheme" content="light">' . "\n" . '<meta name="theme-color" content="#FBFAF7">' . "\n";
}, 1 );

/* ─── Small helpers used by the parts ─────────────────────────────── */

function hxsc_arrow(): string {
	return '<span class="hx-arrow" aria-hidden="true"></span>';
}

/** Internal link (site-relative path → home_url). */
function hxsc_url( string $path ): string {
	return esc_url( home_url( $path ) );
}

/** Allowed inline markup inside carried-over copy. */
function hxsc_kses( string $html ): string {
	return wp_kses( $html, array( 'a' => array( 'href' => true ), 'em' => array(), 'sub' => array(), 'sup' => array(), 'span' => array( 'class' => true ), 'abbr' => array( 'title' => true ) ) );
}
