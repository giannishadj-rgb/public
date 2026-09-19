<?php
/**
 * COS Child Theme — SEO
 *
 * Journal pillar taxonomy, the shared title/description meta maps (also
 * consumed by inc/schema.php's Article headline logic), the <title> tag,
 * Open Graph/Twitter meta, robots meta, sitemap exclusions, and legacy
 * journal-article redirects.
 */

/* ─── Journal pillar taxonomy ────────────────────────────────────── */
// The Journal index page (297) already sorts its articles into four
// editorial streams via a client-side-only filter bar (data-cat attributes
// on each card — "science", "routines", "concerns", "editorial") with no
// real taxonomy behind it: no per-stream URL, nothing crawlable, nothing
// structured data can reference. This registers that same grouping as a
// real taxonomy so hub pages and JSON-LD can use it as source of truth.
function cos_child_register_journal_pillar_taxonomy(): void {
	register_taxonomy( 'journal_pillar', 'page', [
		'labels'            => [
			'name'          => 'Journal Pillars',
			'singular_name' => 'Journal Pillar',
		],
		'public'            => false,
		'show_ui'           => true,
		'show_admin_column' => true,
		'show_in_rest'      => true,
		'hierarchical'      => false,
		'rewrite'           => false,
	] );
}
add_action( 'init', 'cos_child_register_journal_pillar_taxonomy' );

/* ─── Fix: /wp-sitemap.xml (and every wp-sitemap-*.xml sub-sitemap) ─
 * incorrectly returns HTTP 404 despite rendering the correct XML body ──
 * Confirmed via direct trace: WP_Query::parse_query() correctly sets
 * is_sitemap() true and the rewrite rules correctly route the request
 * (WP_Sitemaps' own renderer produces the right content) — but
 * WP::handle_404() only exempts is_admin()/is_robots()/is_favicon() from
 * its "no matching posts → 404" fallback logic. is_sitemap() was never
 * added to that exemption list, so handle_404() calls status_header(404)
 * before the sitemap renderer ever gets a chance to run, and the renderer
 * doesn't correct the status code afterward — the classic "right body,
 * wrong status" symptom, confirmed with a request-level stack trace during
 * diagnosis (status_header(404) called from WP::handle_404(), never
 * followed by a second call).
 *
 * pre_handle_404 is the documented, correct extension point for exactly
 * this — WP::handle_404() itself checks it first and returns immediately
 * if any filter returns non-false, before any of the buggy logic above
 * runs. This never touches a core file, so it survives WordPress updates.
 */
add_filter( 'pre_handle_404', function ( $preempt, WP_Query $query ) {
	if ( $preempt ) return $preempt;
	return $query->is_sitemap() ? true : $preempt;
}, 10, 2 );

/* ─── Shared SEO meta maps ───────────────────────────────────────── */
// Single source of truth for page/PDP titles + descriptions, shared by the
// <title> tag (cos_child_document_title) and Open Graph/Twitter tags
// (cos_child_open_graph) below, so the two can't drift out of sync.

function cos_child_seo_image_meta(): array {
	return [
		'home'                    => 'DSC_3849b-1365x2048.jpg',
		'shop'                    => 'DSC_4331-768x1152.jpg',
		'the-ritual'              => 'c9-1024x1536.jpg',
		'science'                 => 'DSC_4573b-1024x1536.jpg',
		'heritage'                => 'DSC_4392-1024x1536.jpg',
		'journal'                 => 'DSC_4449-1024x1536.jpg',
		'micellar-water-cleanser' => 'MiscellarWater_2018-439x1024.jpg',
		'advanced-repair-serum'   => 'Caviar_repair_serum_30ml-2018-291x1024.jpg',
		'revitalizing-eye-cream'  => 'Caviar_eye_cream_15ml-2018-234x1024.jpg',
		'24h-regeneration-cream'  => 'Caviar_24h_cream_50ml-2018-926x1024.jpg',
		'the-complete-ritual'     => 'the-complete-ritual-closed-box-1024x1536.jpg',
	];
}

function cos_child_seo_page_meta(): array {
	return [
		'shop'       => [ 'Luxury Anti-Aging Skincare & Caviar Skin Care Products', 'Shop Swiss caviar skincare for fine lines, firmness, dark circles, dryness and dullness. Explore individual formulas or save €50 on the complete ritual.' ],
		'the-ritual' => [ '4-Step Anti-Aging Skincare Routine for Mature Skin', 'Learn the four-step Swiss caviar skincare routine: cleanse, treat, eye and moisturise. See how to layer each formula morning and evening.' ],
		'science'    => [ 'Swiss Science — Caviar of Switzerland', 'Explore the High-Tech Complex, five active ingredients, and independent clinical evidence behind the ritual.' ],
		'heritage'   => [ 'Swiss Heritage — Caviar of Switzerland', 'Four centuries of Swiss watchmaking precision and Basel pharmaceutical science, formulated in Canton Zug under GMP-grade manufacturing standards.' ],
		'journal'    => [ 'Journal — Caviar of Switzerland', 'Editorial guidance on Swiss skincare science, rituals, ingredients, and the Caviar of Switzerland protocol.' ],
		// Journal pillar hub pages — see cos_child_register_journal_pillar_taxonomy().
		'ingredients-science' => [ 'Ingredient Science — Caviar of Switzerland Journal', 'What each active ingredient in the ritual actually does, and the clinical evidence behind the claim.' ],
		'routines-how-to'     => [ 'Routines & How-To — Caviar of Switzerland Journal', 'Sequencing, timing, and the routine-building guidance behind the Caviar of Switzerland ritual.' ],
		'skin-concerns'       => [ 'Skin Concerns — Caviar of Switzerland Journal', 'Breakouts, sensitivity, hydration, and under-eye aging, addressed by skin concern and life stage.' ],
		'editorial'           => [ 'Editorial — Caviar of Switzerland Journal', 'Comparisons, trends, and the wider conversation in luxury and clinical skincare.' ],
		'cart'       => [ 'Cart — Caviar of Switzerland', 'Review your Caviar of Switzerland ritual bag, samples, gifts, and checkout options.' ],
		// noindex,follow (see the wp_robots filter below) same as cart/account —
		// still gets a real title/description for the browser tab and any
		// shared link (e.g. an abandoned-cart email), not for search display.
		'checkout'   => [ 'Checkout — Caviar of Switzerland', 'Complete your Caviar of Switzerland order securely — free worldwide shipping and 30-day returns on every purchase.' ],
		'contact'    => [ 'Contact — Caviar of Switzerland', 'Contact the Caviar of Switzerland concierge team for order, ritual, and product guidance.' ],
		'account'    => [ 'Account — Caviar of Switzerland', 'Access Caviar of Switzerland account guidance, order support, and concierge assistance.' ],
		'privacy'    => [ 'Privacy Policy — Caviar of Switzerland', 'Read how Caviar of Switzerland handles contact, consultation, order, and newsletter information.' ],
		'terms'      => [ 'Terms and Conditions — Caviar of Switzerland', 'Read the terms for using the Caviar of Switzerland website, product pages, services, and orders.' ],
		// These four previously had no entry here, so the description fell
		// through to the generic wp_trim_words() fallback below — which just
		// concatenates the page's raw visible text (nav crumbs, hero copy,
		// headings) with no sentence structure, producing 200+ character
		// descriptions well past typical search-result display limits.
		'clinical-studies' => [ 'Clinical Studies — Caviar of Switzerland', 'Independent, dermatologist-reviewed clinical studies behind every Caviar of Switzerland claim — methodology, panel size, and results disclosed in full.' ],
		'help'              => [ 'Help — Caviar of Switzerland', 'Get help with your Caviar of Switzerland order or ritual — shipping, returns, payment, and product questions answered, or contact our concierge.' ],
		'shipping-returns'  => [ 'Shipping & Returns — Caviar of Switzerland', cos_child_shipping_message( 'long' ) . ' Hassle-free returns within 30 days — what to know before you order from Caviar of Switzerland.' ],
		'faq'               => [ 'FAQ — Caviar of Switzerland', 'Answers to common questions about the Caviar of Switzerland ritual — ingredients, usage, shipping, returns, and the High-Tech Complex actives.' ],

		// Skin Concerns hub + 5 purchase-intent landing pages (page 23776 and
		// children) — see inc/schema.php's cos_child_concern_page_schema() for
		// the matching WebPage schema.
		'skin-concerns'          => [ 'Skin Concerns — Caviar of Switzerland', 'Shop Caviar of Switzerland by skin concern — fine lines, firmness, dark circles, dryness, and dullness — with the evidence behind each formula.' ],
		'fine-lines-wrinkles'    => [ 'Fine Lines & Wrinkles — Causes and Treatment | Caviar of Switzerland', 'What causes fine lines and wrinkles, and the Caviar of Switzerland actives with independently disclosed evidence behind treating them.' ],
		'loss-of-firmness'       => [ 'Loss of Firmness — Causes and Treatment | Caviar of Switzerland', 'What causes loss of skin firmness, and the formula behind the disclosed +47% perceived firmness result at 28 days.' ],
		'dark-circles'           => [ 'Dark Circles — Causes and Treatment | Caviar of Switzerland', 'What causes dark circles, what genuinely helps, and what skincare alone can\'t fix — plus the eye cream formulated for the eye contour.' ],
		'dry-dehydrated-skin'    => [ 'Dry & Dehydrated Skin — Causes and Treatment | Caviar of Switzerland', 'The difference between dry and dehydrated skin, and the Caviar of Switzerland routine formulated to treat both.' ],
		'dull-skin'              => [ 'Dull Skin — Causes and Treatment | Caviar of Switzerland', 'What causes dull, tired-looking skin, and the antioxidant actives formulated to help restore radiance.' ],
		// Same generic-fallback gap as above, flagged by an SEO review: descriptions
		// in the 200-222 character range that read as extracted/concatenated page
		// text rather than written copy.
		'sustainability'  => [ 'Sustainability — Caviar of Switzerland', 'Five measurable commitments — responsible aquaculture, recyclable packaging, no animal testing, Swiss manufacturing — reported honestly, not aspirationally.' ],
		'ingredients'     => [ 'The High-Tech Complex — Caviar of Switzerland', 'Five clinically-evidenced actives behind the ritual — Caviar Extract, Syn-Coll Peptide, Hyaluronic Acid, Superoxide Dismutase, Resveratrol — explained in full.' ],
		'caviar-extract'  => [ 'Caviar Extract — Caviar of Switzerland', 'Sourced from Acipenser Baerii roe, Caviar Extract\'s phospholipid profile mirrors skin\'s own lipid matrix — reinforcing barrier integrity and collagen synthesis.' ],
		'sitemap'         => [ 'Sitemap — Caviar of Switzerland', 'Browse every page on Caviar of Switzerland — shop, the ritual, science, journal, and customer support — in one place.' ],

		// Science sub-pages (High-Tech Complex overview + the 5 individual
		// actives) and the Craftsmanship heritage sub-page previously had no
		// entry here, same generic-fallback gap as the group above.
		'craftsmanship'        => [ 'Craftsmanship & Sourcing — Caviar of Switzerland', 'Not a label, a standard: every formula is developed and manufactured at a certified Swiss laboratory in Zug, with full ingredient traceability.' ],
		'high-tech-complex'    => [ 'The High-Tech Complex — Caviar of Switzerland', 'Five actives selected for clinical evidence and synergy, not marketing value — engineered to work together, not just alongside each other.' ],
		'hyaluronic-acid'      => [ 'Hyaluronic Acid — Caviar of Switzerland', 'Three molecular weights of hyaluronic acid in one tiered delivery matrix — surface hydration, mid-layer plumping, and deep reservoir moisture.' ],
		'resveratrol'          => [ 'Resveratrol — Caviar of Switzerland', 'A stilbene polyphenol with the broadest mechanism profile in the complex: sirtuin activation, UV resilience, and direct antioxidant activity.' ],
		'superoxide-dismutase' => [ 'Superoxide Dismutase — Caviar of Switzerland', 'Skin\'s own primary enzymatic antioxidant, sourced and concentrated — a catalyst against free-radical damage, not just a scavenger.' ],
		'syn-coll-peptide'     => [ 'Syn-Coll Peptide — Caviar of Switzerland', 'A clinical-grade collagen synthesis stimulator — the most evidence-backed firming active in the High-Tech Complex.' ],
		'swiss-formulation'    => [ 'Swiss Formulation — Caviar of Switzerland', 'Formulated at a certified Swiss laboratory in Zug, under pharmaceutical-grade stability, safety, and efficacy testing before release.' ],
		'why-caviar'           => [ 'Why Caviar — Caviar of Switzerland', 'Caviar Extract is a biologically precise ingredient, not a luxury signal — its phospholipid profile mirrors skin\'s own structure.' ],

		// All 14 Journal articles now have hand-authored entries (this one was
		// the original sample the review checked; the other 13 previously fell
		// through to the mechanical title-cleanup / wp_trim_words() path below,
		// which produced titles/descriptions well past search-result display
		// limits since post_title/post_content are written as body copy, not
		// SEO strings). cos_child_seo_is_journal_article()'s fallback stays in
		// place below for any future article that doesn't get an entry here yet.
		'why-caviar-extract-works'             => [ 'Why Caviar Extract Works — Caviar of Switzerland', 'Caviar extract’s phospholipids mirror skin’s own cell membranes, so it integrates rather than overrides — the biochemistry behind its anti-aging results.' ],
		'adult-acne-breakouts'                 => [ 'Understanding Adult Acne — Caviar of Switzerland', 'Adult breakouts are not teenage acne and rarely respond to the same advice — what actually drives them, and what helps.' ],
		'back-to-basics-routine'                => [ 'A Routine That Actually Works — Caviar of Switzerland', 'Twelve-step routines are a trend, not a strategy. What a routine actually needs — and what it doesn\'t.' ],
		'caring-for-sensitive-skin'             => [ 'Caring for Sensitive Skin — Caviar of Switzerland', 'Sensitive skin needs fewer variables, not more products — a simplified approach that actually helps.' ],
		'case-for-overnight-regeneration'       => [ 'The Case for Overnight Repair — Caviar of Switzerland', 'Skin does its most important repair work while you sleep. Most routines hand it a moisturizer and stop there.' ],
		'five-ingredients-that-earn-anti-aging' => [ 'Ingredients That Earn "Anti-Aging" — Caviar of Switzerland', 'Only a handful of actives have the clinical record to justify the word "anti-aging." Here is what separates them.' ],
		'get-the-most-from-your-ritual'         => [ 'Get the Most From Your Ritual — Caviar of Switzerland', 'Four products, one sequence, and the details most people skip — and shouldn\'t.' ],
		'identify-your-skin-type'               => [ 'How to Identify Your Skin Type — Caviar of Switzerland', 'Most people are guessing. A reliable answer takes about twenty minutes and no products at all.' ],
		'luxury-ingredients-worth-it'           => [ 'Luxury Ingredients Worth the Price — Caviar of Switzerland', 'Price is not proof of performance — how to tell an ingredient that earns its premium from one that just costs more.' ],
		'luxury-skincare-trends-2025'           => [ 'Luxury Skincare Trends to Watch — Caviar of Switzerland', 'From ingredient transparency to clinical proof — what separates this year\'s luxury skincare from the last decade\'s.' ],
		'single-step-cleanser'                  => [ 'A Single-Step Cleanser, Explained — Caviar of Switzerland', 'Cleansing is the step most routines get wrong. Our case for simplifying it rather than multiplying it.' ],
		'skincare-routine-after-40'             => [ 'Your Skincare Routine After 40 — Caviar of Switzerland', 'At 40, skin\'s needs shift faster than most routines do — what deserves a place in yours, and what doesn\'t.' ],
		'swiss-precision-vs-k-beauty'           => [ 'Swiss Precision vs. K-Beauty — Caviar of Switzerland', 'Two of the world\'s most respected skincare traditions take opposite approaches to the same destination.' ],
		'truth-about-under-eye-aging'           => [ 'The Truth About Under-Eye Aging — Caviar of Switzerland', 'Skin under the eyes ages differently from the rest of your face, and most products ignore that.' ],
		'year-round-hydration'                  => [ 'The Guide to Year-Round Hydration — Caviar of Switzerland', 'Hydration needs change with the seasons, the climate, and the air around you. Most routines do not.' ],
	];
}

function cos_child_seo_pdp_meta(): array {
	return [
		'micellar-water-cleanser' => [ 'Luxury Micellar Water Cleanser with Caviar Extract', 'A gentle Swiss micellar cleanser with caviar extract that removes makeup and impurities without stripping the skin. 150 ml, €55.' ],
		'advanced-repair-serum'   => [ 'Firming Anti-Aging Face Serum with Caviar Extract', 'A concentrated Swiss firming serum with caviar extract and SYN®-COLL. Targets fine lines, dullness and loss of firmness. 30 ml, €150.' ],
		'revitalizing-eye-cream'  => [ 'Anti-Aging Eye Cream for Fine Lines & Dark Circles', 'Swiss caviar eye cream targeting dark circles, puffiness and fine lines. Independently tested with a 38% reduction in fine-line depth. 15 ml, €145.' ],
		'24h-regeneration-cream'  => [ '24-Hour Regenerating Face Cream with Caviar Extract', 'A rich Swiss regenerating face cream with caviar extract for lasting hydration, firmness and comfort. 50 ml, €150.' ],
		'the-complete-ritual'     => [ 'Luxury Anti-Aging Skincare Set – Complete 4-Step Ritual', 'Shop the complete four-step Swiss caviar skincare set. Five high-performance actives, four full-size formulas and a €50 saving.' ],
	];
}

function cos_child_seo_home_title(): string {
	return 'Luxury Caviar Skincare Made in Switzerland | Caviar of Switzerland';
}

// The 14 Journal articles (children of the /journal/ hub) store their
// post_title as a full authored sentence ("Why caviar extract outperforms
// conventional anti-aging actives.") meant as body copy, not a <title> tag —
// it leaks through as-is (lowercase start, trailing period) into both the
// <title> tag and Open Graph/Twitter titles below. Clean it up mechanically
// rather than hand-writing 14 new titles without reviewing each article.
function cos_child_seo_is_journal_article( WP_Post $post ): bool {
	if ( ! $post->post_parent ) return false;
	$parent = get_post( $post->post_parent );
	return $parent instanceof WP_Post && 'journal' === $parent->post_name;
}

function cos_child_seo_clean_title( string $title ): string {
	$clean = rtrim( trim( $title ), '.' );
	return mb_strtoupper( mb_substr( $clean, 0, 1 ) ) . mb_substr( $clean, 1 );
}

/* ─── Editorial review date badge (Journal articles) ─────────────── */
// Dormant until an owner explicitly sets both _cos_journal_reviewed_date and
// _cos_journal_reviewer_name postmeta on an article (see the same pattern
// already used for _cos_journal_author_name/_cos_journal_reviewer_name in
// inc/schema.php's Article JSON-LD) — never fabricated here. Prepended to
// the_content at low priority, well before core_child_decode_raw_html_*
// (PHP_INT_MAX) runs, and contains no <script> tags so that ordering
// doesn't matter either way.
function cos_child_journal_reviewed_badge( string $content ): string {
	if ( is_admin() || ! is_singular( 'page' ) ) return $content;
	$post = get_queried_object();
	if ( ! ( $post instanceof WP_Post ) || ! cos_child_seo_is_journal_article( $post ) ) return $content;

	$reviewed_date = get_post_meta( $post->ID, '_cos_journal_reviewed_date', true );
	$reviewer_name = get_post_meta( $post->ID, '_cos_journal_reviewer_name', true );
	if ( ! $reviewed_date || ! $reviewer_name ) return $content;

	$formatted = mysql2date( get_option( 'date_format' ), $reviewed_date );
	$badge = '<p class="cos-art-reviewed" style="font-size:11px;font-weight:500;letter-spacing:.04em;color:var(--cos-slate,#5C6675);margin:0 0 24px">'
		. 'Reviewed by ' . esc_html( $reviewer_name ) . ' on ' . esc_html( $formatted ) . '</p>';

	return $badge . $content;
}
add_filter( 'the_content', 'cos_child_journal_reviewed_badge', 5 );

/* ─── <title> tag ────────────────────────────────────────────────── */
// Reuses the same maps as Open Graph below so the browser-tab/SERP title
// can't silently diverge from the social title the way the bare front-page
// title ("Caviar Of Switzerland", no keywords) had drifted from the much
// better og:title already defined for it.
add_filter( 'pre_get_document_title', function ( string $title ): string {
	if ( is_admin() ) return $title;

	if ( is_front_page() ) return cos_child_seo_home_title();

	// The shop archive is a post-type archive (get_queried_object() returns a
	// WP_Post_Type, not a WP_Post — it never matches is_page()/is_singular()
	// below), so it fell straight through to WooCommerce's generic default
	// ("Shop – Caviar Of Switzerland") even though a proper title/description
	// was already authored for it in cos_child_seo_page_meta()['shop'].
	if ( function_exists( 'is_shop' ) && is_shop() ) {
		$page_meta = cos_child_seo_page_meta();
		return $page_meta['shop'][0];
	}

	$post = get_queried_object();
	$slug = $post->post_name ?? '';

	if ( is_singular( 'product' ) ) {
		$pdp_meta = cos_child_seo_pdp_meta();
		if ( isset( $pdp_meta[ $slug ] ) ) return $pdp_meta[ $slug ][0] . ' — Caviar of Switzerland';
	} elseif ( is_page() ) {
		$page_meta = cos_child_seo_page_meta();
		if ( isset( $page_meta[ $slug ] ) ) return $page_meta[ $slug ][0];
		if ( $post instanceof WP_Post && cos_child_seo_is_journal_article( $post ) ) {
			return cos_child_seo_clean_title( $post->post_title ) . ' — Caviar of Switzerland';
		}
	}

	return $title;
} );

/* ─── Open Graph / Social Meta ──────────────────────────────────── */

function cos_child_open_graph(): void {
	$site_name = 'Caviar of Switzerland';
	$uploads   = trailingslashit( content_url( 'uploads/2026/04' ) );
	$og        = [
		'site_name'   => $site_name,
		'type'        => 'website',
		'locale'      => 'en_GB',
		'url'         => get_permalink() ?: get_site_url(),
		'title'       => '',
		'description' => '',
		'image'       => $uploads . 'DSC_3849b-1365x2048.jpg',
	];

	$post = get_queried_object();
	$slug = $post->post_name ?? '';

	$image_meta = cos_child_seo_image_meta();
	$page_meta  = cos_child_seo_page_meta();
	$pdp_meta   = cos_child_seo_pdp_meta();

	if ( is_front_page() ) {
		$og['title']       = cos_child_seo_home_title();
		$og['description'] = 'Discover Swiss luxury skincare powered by precious caviar extract and independently tested formulas. Shop the complete four-step anti-aging ritual.';
		$og['type']        = 'website';
		$og['image']       = $uploads . $image_meta['home'];
	} elseif ( function_exists( 'is_shop' ) && is_shop() ) {
		// Same WP_Post_Type gap as the <title> tag above — the shop archive
		// never reached its own authored page_meta/image_meta entries.
		$og['title']       = $page_meta['shop'][0];
		$og['description'] = $page_meta['shop'][1];
		$og['type']        = 'website';
		$og['image']       = $uploads . $image_meta['shop'];
	} elseif ( isset( $pdp_meta[ $slug ] ) ) {
		$og['title']       = $pdp_meta[ $slug ][0] . ' — Caviar of Switzerland';
		$og['description'] = $pdp_meta[ $slug ][1];
		$og['type']        = 'product';
		$og['image']       = $uploads . ( $image_meta[ $slug ] ?? $image_meta['the-complete-ritual'] );
	} elseif ( isset( $page_meta[ $slug ] ) ) {
		$og['title']       = $page_meta[ $slug ][0];
		$og['description'] = $page_meta[ $slug ][1];
		$og['image']       = $uploads . ( $image_meta[ $slug ] ?? $image_meta['home'] );
	} elseif ( $post instanceof WP_Post ) {
		$title             = cos_child_seo_is_journal_article( $post ) ? cos_child_seo_clean_title( $post->post_title ) : $post->post_title;
		$og['title']       = $title . ' — ' . $site_name;
		$og['description'] = wp_trim_words( wp_strip_all_tags( $post->post_content ), 30, '…' );
	}

	$og['title']       = $og['title']       ?: $site_name;
	$og['description'] = $og['description'] ?: 'Swiss cellular luxury skincare. The four-step ritual of Caviar of Switzerland — formulated in Zug, independently clinically tested.';

	echo '<meta property="og:site_name" content="' . esc_attr( $og['site_name'] ) . '">' . "\n";
	echo '<meta property="og:type" content="' . esc_attr( $og['type'] ) . '">' . "\n";
	echo '<meta property="og:locale" content="' . esc_attr( $og['locale'] ) . '">' . "\n";
	echo '<meta property="og:url" content="' . esc_url( $og['url'] ) . '">' . "\n";
	echo '<meta property="og:title" content="' . esc_attr( $og['title'] ) . '">' . "\n";
	echo '<meta property="og:description" content="' . esc_attr( $og['description'] ) . '">' . "\n";
	echo '<meta property="og:image" content="' . esc_url( $og['image'] ) . '">' . "\n";
	echo '<meta name="description" content="' . esc_attr( $og['description'] ) . '">' . "\n";
	echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
	echo '<meta name="twitter:title" content="' . esc_attr( $og['title'] ) . '">' . "\n";
	echo '<meta name="twitter:description" content="' . esc_attr( $og['description'] ) . '">' . "\n";
	echo '<meta name="twitter:image" content="' . esc_url( $og['image'] ) . '">' . "\n";
}
add_action( 'wp_head', 'cos_child_open_graph', 10 );

/* ─── Shop canonical ─────────────────────────────────────────────── */
// Core's rel_canonical() only fires for is_singular() — the shop archive is
// a post-type archive, not a singular page, so WordPress never emits a
// canonical for it at all (nothing to "unblock"; this adds one from scratch).
function cos_child_shop_canonical(): void {
	if ( ! function_exists( 'is_shop' ) || ! is_shop() ) return;
	$shop_page_id = function_exists( 'wc_get_page_id' ) ? wc_get_page_id( 'shop' ) : 0;
	$url          = $shop_page_id > 0 ? get_permalink( $shop_page_id ) : home_url( '/shop/' );
	echo '<link rel="canonical" href="' . esc_url( $url ) . '">' . "\n";
}
add_action( 'wp_head', 'cos_child_shop_canonical', 10 );

/* ─── Product category archive canonical ────────────────────────── */
// Same gap as the shop archive above: core's rel_canonical() only fires for
// is_singular(), so /product-category/*/ never gets a canonical of its own.
function cos_child_product_category_canonical(): void {
	if ( ! is_tax( 'product_cat' ) ) return;
	$term = get_queried_object();
	if ( ! ( $term instanceof WP_Term ) ) return;
	echo '<link rel="canonical" href="' . esc_url( get_term_link( $term ) ) . '">' . "\n";
}
add_action( 'wp_head', 'cos_child_product_category_canonical', 10 );

/* ─── Empty product category archives: redirect instead of indexing ── */
// WooCommerce auto-creates "Uncategorized" and assigns it to any product with
// no other category, and any future category can end up temporarily empty
// the same way (products re-tagged, a category created ahead of stock). An
// empty product_cat archive has no content of its own — it's either a blank
// shell or, worse, silently falls back to listing unrelated products — so it
// should never be a crawlable, indexable URL. Redirect (not just noindex) so
// the empty archive is never served as a 200 at all; this self-heals the
// moment products actually get assigned back to it.
function cos_child_redirect_empty_product_category(): void {
	if ( is_admin() || ! is_tax( 'product_cat' ) ) return;
	$term = get_queried_object();
	if ( ! ( $term instanceof WP_Term ) || $term->count > 0 ) return;

	$shop_page_id = function_exists( 'wc_get_page_id' ) ? wc_get_page_id( 'shop' ) : 0;
	$url          = $shop_page_id > 0 ? get_permalink( $shop_page_id ) : home_url( '/shop/' );
	wp_safe_redirect( $url, 302, 'Caviar of Switzerland' );
	exit;
}
add_action( 'template_redirect', 'cos_child_redirect_empty_product_category', 1 );

/* ─── Non-production environments: hard block on indexing ───────────
 * Staging is currently indexable with a self-referencing canonical, and its
 * robots.txt was found pointing at the live production site's old
 * sitemap_index.xml rather than its own content — both symptoms of the same
 * root problem: nothing in this codebase ever tied "is this actually the
 * live production site" to whether it's indexable. WordPress's own
 * "Discourage search engines" setting (Settings -> Reading) is a manually
 * toggled wp_options row that's exactly the kind of thing that gets left in
 * whatever state a database clone/migration happened to carry over — it
 * must not be the only thing standing between staging and Google. This
 * enforces the block in code, keyed off WP_ENVIRONMENT_TYPE, on three
 * independent layers (meta tag, HTTP header, robots.txt) so a crawler that
 * ignores any one of them still hits the other two:
 *
 * 1. wp_robots meta tag — noindex, nofollow on every response.
 * 2. X-Robots-Tag HTTP header — same directive, but also covers non-HTML
 *    responses (PDFs, images, XML sitemaps) the meta tag can't reach.
 * 3. robots.txt — a blanket Disallow: /, regardless of whatever the
 *    "Discourage search engines" option is currently set to.
 *
 * IMPORTANT — this only protects environments where wp-config.php actually
 * defines WP_ENVIRONMENT_TYPE as something other than 'production'.
 * WordPress's own default, when the constant is undefined at all, is
 * 'production' — so if staging's wp-config.php never sets this constant,
 * it is indistinguishable from production here and this fix does nothing
 * for it. Confirm staging's wp-config.php explicitly sets
 * WP_ENVIRONMENT_TYPE to 'staging' (or 'development') before relying on
 * this. Password-protecting staging at the server/host level remains the
 * more robust option this can't replace — code can't stop a crawler that
 * already has a page's URL from a shared link, only ask it not to index.
 */
function cos_child_is_production_environment(): bool {
	return 'production' === wp_get_environment_type();
}

add_filter( 'wp_robots', function ( array $robots ): array {
	if ( cos_child_is_production_environment() ) return $robots;
	return [ 'noindex' => true, 'nofollow' => true ];
}, 99 );

add_action( 'send_headers', function (): void {
	if ( cos_child_is_production_environment() || headers_sent() ) return;
	header( 'X-Robots-Tag: noindex, nofollow' );
} );

add_filter( 'robots_txt', function ( string $output ): string {
	if ( cos_child_is_production_environment() ) return $output;
	return "User-agent: *\nDisallow: /\n";
}, 99 );

/* ─── Robots meta ────────────────────────────────────────────────── */
// Feed directives through core's wp_robots filter (single source of truth for
// the <meta name="robots"> tag) instead of also echoing a second, competing
// tag on wp_head — WP core already prints one via this filter by default.
//
// Cart, checkout, and account are session-specific pages with no evergreen
// content to rank. WooCommerce's own wp_robots callback already sets
// noindex on them, but this filter used to unconditionally force
// index=true on every published page afterwards — producing a single
// <meta name="robots"> tag containing both `index` and `noindex` (plus a
// couple of malformed leftover directives) on all three. Priority 20 makes
// this filter run after WooCommerce's default-priority one, so it's the
// final, authoritative word: a clean `noindex, follow` for these three,
// `index, follow` (unchanged) for every other published page.
//
// Privacy Policy and Terms & Conditions get the same noindex,follow — they
// serve a real legal/compliance purpose (still linked from the footer on
// every page, still fully reachable) but have no organic-search intent of
// their own, and indexing boilerplate legal text alongside real commercial
// and editorial content dilutes what the site's ranking signals are about.
// follow stays true so internal links these pages carry (e.g. mentions of
// the returns policy) still pass equity normally.
add_filter( 'wp_robots', function ( array $robots ): array {
	if ( is_cart() || is_checkout() || is_account_page() || is_page( [ 'privacy', 'terms' ] ) ) {
		unset( $robots['index'], $robots['max-snippet'], $robots['max-image-preview'], $robots['max-video-preview'] );
		$robots['noindex'] = true;
		$robots['follow']  = true;
		return $robots;
	}

	// Shop archive and PDPs previously fell through this filter untouched —
	// neither is_singular('page') (the shop is a post-type archive, PDPs are
	// is_singular('product')) — so they never got an explicit index/follow
	// directive at all. Absence of noindex is still indexable by default,
	// but an explicit directive is clearer to verify and matches how every
	// other real content type on this site is already handled here.
	$is_indexable_shop_or_pdp = is_singular( 'product' ) || ( function_exists( 'is_shop' ) && is_shop() );
	if ( ( is_singular( 'page' ) && get_queried_object()->post_status === 'publish' ) || $is_indexable_shop_or_pdp ) {
		$robots['index']             = true;
		$robots['follow']            = true;
		$robots['max-snippet']       = -1;
		$robots['max-image-preview'] = 'large';
		$robots['max-video-preview'] = -1;
	}
	return $robots;
}, 20 );

/* ─── Exclude transactional/legal pages from the XML sitemap ─────── */
// WP core's sitemap builds its page list from a direct DB query — it has no
// way to know about the wp_robots noindex decision above, so it needs its
// own, separate exclusion for the same pages. A noindex URL sitting in the
// sitemap is a mixed signal to crawlers (an explicit "crawl this" alongside
// an explicit "don't index it"), not just a harmless redundancy.
add_filter( 'wp_sitemaps_posts_query_args', function ( array $args, $post_type ) {
	if ( 'page' === ( is_object( $post_type ) ? $post_type->name : $post_type ) ) {
		$cart_id     = wc_get_page_id( 'cart' );
		$checkout_id = wc_get_page_id( 'checkout' );
		$account_id  = wc_get_page_id( 'myaccount' );
		$privacy     = get_page_by_path( 'privacy' );
		$terms       = get_page_by_path( 'terms' );
		$exclude     = array_filter(
			[ $cart_id, $checkout_id, $account_id, $privacy->ID ?? 0, $terms->ID ?? 0 ],
			fn( $id ) => $id > 0
		);
		if ( $exclude ) {
			$args['post__not_in'] = array_merge( $args['post__not_in'] ?? [], $exclude );
		}
	}
	return $args;
}, 10, 2 );

/* ─── Exclude empty product categories from the XML sitemap ──────── */
// Core's taxonomy sitemap provider already passes hide_empty => true by
// default (wp-includes/sitemaps/providers/class-wp-sitemaps-taxonomies.php),
// so this is redundant with a healthy term count — but term counts on a
// WooCommerce install can go stale (e.g. a bulk product edit that doesn't
// trigger a recount), and an empty archive reaching the sitemap while still
// silently returning 200 would undo the point of the redirect above. Make
// the exclusion explicit rather than relying only on count staying accurate.
add_filter( 'wp_sitemaps_taxonomies_query_args', function ( array $args, $taxonomy ) {
	if ( 'product_cat' === ( is_object( $taxonomy ) ? $taxonomy->name : $taxonomy ) ) {
		$args['hide_empty'] = true;
	}
	return $args;
}, 10, 2 );

/* ─── Thin product-category archives: noindex, keep crawlable ──────
 * Each of these product_cat terms currently holds exactly one product, so
 * the archive has no content of its own beyond that single product card —
 * a near-duplicate of the product's own (indexable) page. Unlike the empty
 * "Uncategorized"-style archives above (which redirect, since they have
 * nothing to show at all), these genuinely render a real product, so they
 * stay reachable and crawlable (follow, not disallowed) — only the index
 * signal is withheld, so link equity to the product still passes normally.
 *
 * REVERSAL: once a category in this list has 4+ products and enough
 * unique category-level copy to justify its own ranking (not just a
 * one-item grid), remove its slug from $cos_thin_product_categories below.
 * No other code changes are needed — the noindex and sitemap exclusion
 * both key off this one array.
 */
function cos_child_thin_product_categories(): array {
	return [ 'cleanser', 'serum', 'eye-cream', 'moisturizer', 'ritual-sets' ];
}

add_filter( 'wp_robots', function ( array $robots ): array {
	if ( is_tax( 'product_cat', cos_child_thin_product_categories() ) ) {
		unset( $robots['index'], $robots['max-snippet'], $robots['max-image-preview'], $robots['max-video-preview'] );
		$robots['noindex'] = true;
		$robots['follow']  = true;
	}
	return $robots;
}, 21 );

add_filter( 'wp_sitemaps_taxonomies_query_args', function ( array $args, $taxonomy ) {
	if ( 'product_cat' === ( is_object( $taxonomy ) ? $taxonomy->name : $taxonomy ) ) {
		$thin_terms = get_terms( [
			'taxonomy'   => 'product_cat',
			'slug'       => cos_child_thin_product_categories(),
			'fields'     => 'ids',
			'hide_empty' => false,
		] );
		if ( ! is_wp_error( $thin_terms ) && $thin_terms ) {
			$args['exclude'] = array_merge( $args['exclude'] ?? [], $thin_terms );
		}
	}
	return $args;
}, 10, 2 );

/* Redirect superseded generic journal articles to their COS-specific editions. */
function cos_child_redirect_legacy_journal_articles(): void {
	$redirects = array(
		'top-5-anti-aging-ingredients-dermatologists-swear-by' => '/journal/five-ingredients-that-earn-anti-aging/',
		// Retired for an overclaiming "reverse aging" slug/headline — see OWNER-ACTIONS.md.
		'five-ingredients-that-reverse-aging'                   => '/journal/five-ingredients-that-earn-anti-aging/',
		'the-best-anti-aging-skincare-ritual-for-women-over-40' => '/journal/skincare-routine-after-40/',
		'swiss-skincare-vs-korean-skincare'                     => '/journal/swiss-precision-vs-k-beauty/',
		'the-top-5-benefits-of-caviar-extract-in-skincare'      => '/journal/why-caviar-extract-works/',
		'why-caviar-is-the-ultimate-anti-aging-ingredient'       => '/journal/why-caviar-extract-works/',
		'top-5-luxury-skincare-trends-of-2025'                  => '/journal/luxury-skincare-trends-2025/',
		'caviar-anti-aging-secret'                              => '/journal/why-caviar-extract-works/',
		'seasonal-skincare-winter-hydration'                    => '/journal/year-round-hydration/',
		'luxury-skincare-ingredients-worth-the-investment'      => '/journal/luxury-ingredients-worth-it/',
		'best-skincare-ritual-for-glowing-skin-in-30-days'      => '/journal/get-the-most-from-your-ritual/',
	);

	$path = trim( (string) wp_parse_url( $_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH ), '/' );
	$slug = basename( $path );

	if ( isset( $redirects[ $slug ] ) ) {
		wp_safe_redirect( home_url( $redirects[ $slug ] ), 301, 'Caviar of Switzerland' );
		exit;
	}
}
add_action( 'template_redirect', 'cos_child_redirect_legacy_journal_articles', 1 );
