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
		array( 'num' => '01', 'path' => '/science/why-caviar/', 'title' => 'Why caviar', 'line' => 'The material: a lipid profile close to skin’s own.' ),
		array( 'num' => '02', 'path' => '/science/high-tech-complex/', 'title' => 'The High-Tech Complex', 'line' => 'Five actives, chosen to work as one.' ),
		array( 'num' => '03', 'path' => '/science/ingredients/', 'title' => 'The five actives', 'line' => 'Each active, its source and its role.' ),
		array( 'num' => '04', 'path' => '/science/clinical-studies/', 'title' => 'Clinical studies', 'line' => 'Every published figure, with its method.' ),
		array( 'num' => '05', 'path' => '/science/swiss-formulation/', 'title' => 'Swiss formulation', 'line' => 'How a formula is made and released in Zug.' ),
	);
}

/**
 * The five actives of the High-Tech Complex. Copy from pages 344 (short role), 346 (in depth,
 * mechanism), 60 (index role + summary). 'roles' = Protect / Repair / Rejuvenate, as published
 * on the homepage science section (trust.php): caviar extract protects (encapsulated potency),
 * repairs (phospholipids) and rejuvenates; SOD + resveratrol protect; HA repairs; SYN®-COLL rejuvenates.
 */
function hxsc_actives(): array {
	return array(
		array(
			'num'   => '01',
			'slug'  => 'caviar-extract',
			'name'  => 'Precious Caviar Extract',
			'inci'  => 'Acipenser Baerii Roe Extract (Caviar Extract)',
			'role'  => 'Biomimetic cellular regeneration', // 344
			'irole' => 'Biomimetic cellular regeneration', // 60
			'roles' => array( 'protect', 'repair', 'rejuvenate' ),
			'index' => 'Full-spectrum Acipenser Baerii sturgeon roe extract. The phospholipid profile mirrors human skin’s own intercellular lipid matrix, enabling genuine structural repair at the barrier level.',
			'desc'  => 'Full-spectrum extract from Acipenser Baerii (Siberian) sturgeon roe. Rich in phospholipids, amino acids, vitamins A, D and B complex, and trace minerals. Its phospholipid profile is remarkably similar to human skin’s own intercellular lipid matrix, making it genuinely biomimetic in a way that most “cellular” ingredients are not.',
			'mech'  => 'Replenishes the skin’s lipid bilayer, supports the natural desquamation cycle, and provides the raw materials for collagen and elastin synthesis at the cellular level.',
		),
		array(
			'num'   => '02',
			'slug'  => 'syn-coll-peptide',
			'name'  => 'Syn®‑Coll Peptide',
			'inci'  => 'Palmitoyl Tripeptide-1',
			'role'  => 'Collagen synthesis stimulation',
			'irole' => 'Collagen synthesis stimulation',
			'roles' => array( 'rejuvenate' ),
			'index' => 'Palmitoyl Tripeptide-1. The primary firming active in the High-Tech Complex.',
			'desc'  => 'A peptide studied for collagen synthesis. Palmitoyl Tripeptide-1 mimics a collagen fragment, signalling dermal fibroblasts to upregulate Type I collagen production. Not a “collagen booster” in the vague marketing sense, but a specific molecular signal for a specific biological response.',
			'mech'  => 'A peptide signal pathway activating fibroblast collagen synthesis. The palmitoyl tail improves skin penetration by increasing lipophilicity.',
			// In-vitro ingredient research: always shown with this label, never as a product result.
			'vitro' => array( '4.5×', 'Type I collagen in treated fibroblasts vs. control', 'Published in-vitro studies of the ingredient on dermal fibroblasts. Ingredient research in cell culture, not a result measured with our products.' ),
		),
		array(
			'num'   => '03',
			'slug'  => 'hyaluronic-acid',
			'name'  => 'Hyaluronic Acid',
			'inci'  => 'Sodium Hyaluronate / Hydrolysed Sodium Hyaluronate / Sodium Hyaluronate Crosspolymer',
			'role'  => 'Triple-depth hydration matrix',
			'irole' => 'Triple-depth hydration matrix',
			'roles' => array( 'repair' ),
			'index' => 'Three molecular weights delivering surface film, mid-layer hydration and deep epidermal volumising simultaneously. The crosspolymer form sustains moisture release for 24 hours.',
			'desc'  => 'Three molecular weights in a deliberate, tiered delivery system. Crosspolymer provides the 24-hour sustained release.',
			'mech'  => 'A moisture-binding matrix at three skin depths simultaneously. One molecule of hyaluronic acid can hold 1,000× its own weight in water.',
			'depth' => array(
				array( 'High', '1,500–1,800 kDa', 'Surface', 'Sits on the surface, forming a hydrating film and preventing TEWL.' ),
				array( 'Medium', '200–500 kDa', 'Stratum corneum', 'Penetrates the stratum corneum, hydrating the outer epidermal layers.' ),
				array( 'Low', '10–50 kDa', 'Deeper epidermis', 'Reaches the deeper epidermis, providing volumising hydration that lifts from within.' ),
			),
		),
		array(
			'num'   => '04',
			'slug'  => 'superoxide-dismutase',
			'name'  => 'Superoxide Dismutase',
			'inci'  => 'Superoxide Dismutase (SOD)',
			'role'  => 'Enzymatic antioxidant defence',
			'irole' => 'Enzymatic antioxidant defence',
			'roles' => array( 'protect' ),
			'index' => 'The skin’s primary enzymatic antioxidant. Catalyses superoxide radical conversion before the MMP collagen-degradation cascade begins. Not a scavenger: a catalyst.',
			'desc'  => 'The primary enzymatic antioxidant of the skin’s own defence system. SOD catalyses the dismutation of the superoxide radical (O<sub>2</sub><sup>•−</sup>), the most abundant and reactive free radical produced in the skin, into hydrogen peroxide and oxygen. Unlike vitamin C or resveratrol, which scavenge free radicals after they form, SOD neutralises them at their source.',
			'mech'  => 'Enzymatic catalysis of superoxide radical conversion. Prevents the downstream collagen-degrading MMP enzyme cascade before it begins.',
		),
		array(
			'num'   => '05',
			'slug'  => 'resveratrol',
			'name'  => 'Resveratrol',
			'inci'  => 'Resveratrol (from Polygonum Cuspidatum)',
			'role'  => 'Sirtuin pathway activation',
			'irole' => 'Sirtuin activation & UV resilience',
			'roles' => array( 'protect' ),
			'index' => 'Stilbene polyphenol from Polygonum Cuspidatum. SIRT-1 activator, COX-2 inhibitor and direct radical scavenger. Synergistic with SOD for comprehensive antioxidant coverage.',
			'desc'  => 'A stilbene polyphenol extracted from Polygonum Cuspidatum root. Resveratrol is a SIRT-1 activator: it stimulates the sirtuin protein family associated with cellular longevity and stress resilience. In skin biology, it increases the expression of SIRT-1, which modulates p53 activity and protects against UV-induced apoptosis. It also provides direct free-radical scavenging and inhibits the COX-2 inflammatory pathway.',
			'mech'  => 'SIRT-1 activation, UV-stress resilience, anti-inflammatory COX-2 inhibition and direct radical scavenging. Synergistic with SOD in comprehensive antioxidant coverage.',
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
