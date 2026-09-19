<?php
/**
 * COS Child Theme — JSON-LD Structured Data
 *
 * Product/Article/Breadcrumb schema, the site-wide Organization/WebSite
 * entity, and FAQPage schema. Uses cos_child_seo_is_journal_article() and
 * cos_child_seo_clean_title() from inc/seo.php.
 */

/**
 * wp_strip_all_tags() alone concatenates adjacent block elements with no
 * separator — "<p>...individual purchase.</p><ul><li>All 4 products</li>"
 * became the literal string "...individual purchase.All 4 products" in
 * JSON-LD descriptions once tags were stripped. Insert a space at every
 * block-level boundary first, so stripped text still reads as separate
 * sentences/items.
 */
function cos_child_html_to_plain_description( string $html ): string {
	$html = preg_replace( '#</(p|li|div|h[1-6]|tr|br)\s*>#i', ' ', $html );
	$html = preg_replace( '#<br\s*/?>#i', ' ', $html );
	$text = wp_strip_all_tags( $html );
	$text = preg_replace( '/\s+/', ' ', $text );
	return trim( $text );
}

/**
 * OfferShippingDetails, scoped to whatever cos_child_ship_to_country_codes()
 * (inc/shipping.php) says is genuinely fulfillable. As of the 2026-09-11
 * owner decision, WooCommerce has a real "Rest of World" zero-cost zone
 * alongside the Switzerland zone, so this now lists every country checkout
 * actually supports rather than a single hardcoded one — reads from the
 * same single source of truth as every other shipping claim sitewide, so
 * it can't drift out of sync with what checkout offers again (see
 * OWNER-ACTIONS.md §10).
 */
function cos_child_shipping_details(): array {
	$country_codes = cos_child_ship_to_country_codes();
	return [
		'@type'                => 'OfferShippingDetails',
		'shippingRate'         => [
			'@type'    => 'MonetaryAmount',
			'value'    => '0',
			'currency' => get_woocommerce_currency(),
		],
		'shippingDestination'  => [
			'@type'          => 'DefinedRegion',
			'addressCountry' => $country_codes,
		],
		'deliveryTime'         => [
			'@type'        => 'ShippingDeliveryTime',
			'handlingTime' => [ '@type' => 'QuantitativeValue', 'minValue' => 0, 'maxValue' => 1, 'unitCode' => 'DAY' ],
			'transitTime'  => [ '@type' => 'QuantitativeValue', 'minValue' => 1, 'maxValue' => 2, 'unitCode' => 'DAY' ],
		],
	];
}

/**
 * MerchantReturnPolicy sourced directly from the published Shipping &
 * Returns page (post 357, "Returns & Refunds" section): a 30-day
 * no-questions-asked window, full refund, return shipping paid by the
 * customer except where Caviar of Switzerland made an error or the product
 * is faulty (that carve-out isn't representable in this single-value
 * schema, so returnFees reflects the general/default case as published).
 * Scoped to the same country list as cos_child_shipping_details() above, so
 * it can't drift out of sync with what checkout actually supports.
 */
function cos_child_return_policy(): array {
	return [
		'@type'                => 'MerchantReturnPolicy',
		'applicableCountry'    => cos_child_ship_to_country_codes(),
		'returnPolicyCategory' => 'https://schema.org/MerchantReturnFiniteReturnWindow',
		'merchantReturnDays'   => 30,
		'returnMethod'         => 'https://schema.org/ReturnByMail',
		'returnFees'           => 'https://schema.org/ReturnShippingFees',
		'refundType'           => 'https://schema.org/FullRefund',
	];
}

function cos_child_structured_data(): void {
	$is_product = is_singular( 'product' );
	$is_post    = is_singular( 'post' );
	$is_shop    = function_exists( 'is_shop' ) && is_shop();
	if ( ! is_singular( 'page' ) && ! $is_product && ! $is_post && ! $is_shop ) return;

	// The shop archive is a post-type archive, not a singular page, so
	// get_queried_object() below would return a WP_Post_Type (no post_name /
	// post_title) rather than a WP_Post. It only ever needs a Home > Shop
	// breadcrumb, so build and output that directly rather than falling
	// through into the singular-post logic below.
	if ( $is_shop ) {
		$shop_url = get_permalink( wc_get_page_id( 'shop' ) ) ?: home_url( '/shop/' );

		// Live query, same shape and order as archive-product.php's own
		// listing (menu_order ASC, bundle excluded from the query and given
		// its own leading position instead) — pulled fresh rather than
		// hardcoded so this can never drift from what the page actually
		// renders, same rule the rest of this file already follows for
		// Product schema.
		$shop_products_query = new WP_Query( [
			'post_type'      => 'product',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'orderby'        => 'menu_order',
			'order'          => 'ASC',
			'meta_query'     => [ [ 'key' => '_price', 'compare' => 'EXISTS' ] ],
		] );

		$list_items   = [];
		$position     = 1;
		$bundle_entry = null;
		$step_entries = [];

		if ( $shop_products_query->have_posts() ) {
			while ( $shop_products_query->have_posts() ) {
				$shop_products_query->the_post();
				$shop_product = wc_get_product( get_the_ID() );
				if ( ! $shop_product ) continue;
				$entry = [
					'name' => $shop_product->get_name(),
					'url'  => get_permalink(),
				];
				if ( 'COS-BUNDLE' === $shop_product->get_sku() ) {
					$bundle_entry = $entry;
				} else {
					$step_entries[] = $entry;
				}
			}
			wp_reset_postdata();
		}

		// The Complete Ritual is featured in its own section above the grid
		// (archive-product.php, #cos-ritual), not inside it — position 1
		// reflects that real page order, individual products follow.
		foreach ( array_filter( [ $bundle_entry, ...$step_entries ] ) as $entry ) {
			$list_items[] = [
				'@type'    => 'ListItem',
				'position' => $position++,
				'name'     => $entry['name'],
				'url'      => $entry['url'],
			];
		}

		$shop_schema = [
			'@context' => 'https://schema.org',
			'@graph'   => [
				[
					'@type'           => 'BreadcrumbList',
					'itemListElement' => [
						[ '@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => get_site_url() . '/' ],
						[ '@type' => 'ListItem', 'position' => 2, 'name' => 'Shop' ],
					],
				],
				[
					'@type'      => 'CollectionPage',
					'@id'        => $shop_url . '#collection',
					'url'        => $shop_url,
					'name'       => 'The Collection — Caviar of Switzerland',
					'isPartOf'   => [ '@type' => 'WebSite', 'url' => get_site_url() ],
					'mainEntity' => [ '@id' => $shop_url . '#itemlist' ],
				],
				[
					'@type'           => 'ItemList',
					'@id'             => $shop_url . '#itemlist',
					'itemListElement' => $list_items,
				],
			],
		];
		echo '<script type="application/ld+json">' . wp_json_encode( $shop_schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>' . "\n";
		return;
	}

	$post      = get_queried_object();
	$slug      = $post->post_name;
	$site_url  = get_site_url();
	$logo      = get_stylesheet_directory_uri() . '/assets/images/logo.png';

	// Product data map
	$products = [
		'micellar-water-cleanser'  => [ 'id' => 'COS-001', 'name' => 'Micellar Water Cleanser', 'price' => '55',  'size' => '150ml', 'step' => 1, 'desc' => 'Gentle micellar all-in-one cleanser that removes, balances, and prepares skin for the active layers of the Caviar of Switzerland ritual.' ],
		'advanced-repair-serum'    => [ 'id' => 'COS-002', 'name' => 'Advanced Repair Serum',   'price' => '150', 'size' => '30ml',  'step' => 2, 'desc' => 'Concentrated clinical serum with Syn®-Coll Peptide and full High-Tech Complex. +47% perceived firmness in 28-day independent study.' ],
		'revitalizing-eye-cream'   => [ 'id' => 'COS-003', 'name' => 'Revitalizing Eye Cream',  'price' => '145', 'size' => '15ml',  'step' => 3, 'desc' => 'Precision eye care targeting puffiness, dark circles, and crow\'s feet. −38% fine line depth in 28-day independent study.' ],
		'24h-regeneration-cream'   => [ 'id' => 'COS-004', 'name' => '24h Regeneration Cream',  'price' => '150', 'size' => '50ml',  'step' => 4, 'desc' => '24-hour regeneration cream that seals and sustains the ritual\'s clinical gains. 100% panellist satisfaction at 28 days.' ],
		'the-complete-ritual'      => [ 'id' => 'COS-BUNDLE', 'name' => 'The Complete Ritual', 'price' => '450', 'size' => '4 products', 'step' => 0, 'desc' => 'The full four-step Caviar of Switzerland protocol: Micellar Cleanser, Advanced Repair Serum, Revitalizing Eye Cream, and 24h Regeneration Cream.' ],
	];

	// BreadcrumbList for all pages
	$breadcrumb_items = [
		[ '@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => $site_url . '/' ],
	];

	if ( $is_product ) {
		// Products aren't hierarchical — give them a Home > Shop > Product trail.
		// Uses the same hardcoded /shop/ path the rest of the theme's nav links to,
		// since wc_get_page_id( 'shop' ) doesn't reliably resolve on this install.
		$breadcrumb_items[] = [ '@type' => 'ListItem', 'position' => 2, 'name' => 'Shop', 'item' => $site_url . '/shop/' ];
		$breadcrumb_items[] = [ '@type' => 'ListItem', 'position' => 3, 'name' => $post->post_title ];
	} elseif ( $is_post ) {
		// Journal articles aren't hierarchical either — Home > Journal > Article.
		$breadcrumb_items[] = [ '@type' => 'ListItem', 'position' => 2, 'name' => 'Journal', 'item' => $site_url . '/journal/' ];
		$breadcrumb_items[] = [ '@type' => 'ListItem', 'position' => 3, 'name' => $post->post_title ];
	} else {
		$parent = $post->post_parent ? get_post( $post->post_parent ) : null;
		if ( $parent ) {
			$grandparent = $parent->post_parent ? get_post( $parent->post_parent ) : null;
			if ( $grandparent ) {
				$breadcrumb_items[] = [ '@type' => 'ListItem', 'position' => 2, 'name' => $grandparent->post_title, 'item' => get_permalink( $grandparent->ID ) ];
				$breadcrumb_items[] = [ '@type' => 'ListItem', 'position' => 3, 'name' => $parent->post_title, 'item' => get_permalink( $parent->ID ) ];
				$breadcrumb_items[] = [ '@type' => 'ListItem', 'position' => 4, 'name' => $post->post_title ];
			} else {
				$breadcrumb_items[] = [ '@type' => 'ListItem', 'position' => 2, 'name' => $parent->post_title, 'item' => get_permalink( $parent->ID ) ];
				$breadcrumb_items[] = [ '@type' => 'ListItem', 'position' => 3, 'name' => $post->post_title ];
			}
		} else {
			$breadcrumb_items[] = [ '@type' => 'ListItem', 'position' => 2, 'name' => $post->post_title ];
		}
	}

	$schema = [
		'@context' => 'https://schema.org',
		'@graph'   => [
			[
				'@type'           => 'BreadcrumbList',
				'itemListElement' => $breadcrumb_items,
			],
		],
	];

	// WebPage schema for the Skin Concerns hub (page 23776) and its 5
	// purchase-intent concern pages — scoped by exact ID/parent rather than
	// added for every generic page, so this never changes schema output for
	// any of this site's many other hand-authored pages (FAQ, Help, Terms,
	// etc.) that were out of scope for this pass. Reuses the same
	// title/description already defined in cos_child_seo_page_meta() so this
	// can't drift from the <title>/meta description shown for the same page.
	$concern_hub_id = 23776;
	if ( $post->ID === $concern_hub_id || (int) $post->post_parent === $concern_hub_id ) {
		$page_meta_for_schema = cos_child_seo_page_meta();
		$permalink            = get_permalink( $post );
		$schema['@graph'][]   = [
			'@type'       => 'WebPage',
			'@id'         => $permalink . '#webpage',
			'url'         => $permalink,
			'name'        => $page_meta_for_schema[ $slug ][0] ?? get_the_title( $post ),
			'description' => $page_meta_for_schema[ $slug ][1] ?? '',
			'isPartOf'    => [ '@type' => 'WebSite', '@id' => $site_url . '/#website' ],
		];
	}

	// Product schema for PDPs and bundle page — pulled live so price/stock can't drift from reality.
	if ( $is_product ) {
		$product = wc_get_product( $post->ID );
		if ( $product ) {
			$permalink  = get_permalink( $post );
			$image_id   = $product->get_image_id();
			$image_url  = $image_id ? wp_get_attachment_image_url( $image_id, 'full' ) : '';
			$desc       = $product->get_short_description() ?: $product->get_description();
			$schema['@graph'][] = [
				'@type'           => 'Product',
				'@id'             => $permalink . '#product',
				'name'            => $product->get_name(),
				'description'     => cos_child_html_to_plain_description( $desc ),
				'sku'             => $product->get_sku(),
				// Caviar of Switzerland is both brand and manufacturer for this
				// single-brand line, so its own SKU is a genuine manufacturer part
				// number — not a fabricated identifier. GTIN is intentionally still
				// omitted: no real UPC/EAN exists for these products (OWNER-ACTIONS.md §17).
				'mpn'             => $product->get_sku(),
				'image'           => $image_url,
				'brand'           => [ '@type' => 'Brand', 'name' => 'Caviar of Switzerland' ],
				'manufacturer'    => [ '@type' => 'Organization', 'name' => 'Caviar of Switzerland', 'address' => [ '@type' => 'PostalAddress', 'addressLocality' => 'Zug', 'addressCountry' => 'CH' ] ],
				'countryOfOrigin' => 'Switzerland',
				'offers'          => [
					'@type'                   => 'Offer',
					'price'                   => $product->get_price(),
					'priceCurrency'           => get_woocommerce_currency(),
					'availability'            => $product->is_in_stock() ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
					'url'                     => $permalink,
					'itemCondition'           => 'https://schema.org/NewCondition',
					'seller'                  => [ '@type' => 'Organization', 'name' => 'Caviar of Switzerland' ],
					'shippingDetails'         => cos_child_shipping_details(),
					'hasMerchantReturnPolicy' => cos_child_return_policy(),
				],
			];
		}
	} elseif ( isset( $products[ $slug ] ) ) {
		$p             = $products[ $slug ];
		$permalink     = get_permalink();
		$schema['@graph'][] = [
			'@type'       => 'Product',
			'@id'         => $permalink . '#product',
			'name'        => $p['name'],
			'description' => $p['desc'],
			'sku'         => $p['id'],
			'mpn'         => $p['id'],
			'brand'       => [ '@type' => 'Brand', 'name' => 'Caviar of Switzerland' ],
			'manufacturer'=> [ '@type' => 'Organization', 'name' => 'Caviar of Switzerland', 'address' => [ '@type' => 'PostalAddress', 'addressLocality' => 'Zug', 'addressCountry' => 'CH' ] ],
			'countryOfOrigin' => 'Switzerland',
			'offers'      => [
				'@type'                   => 'Offer',
				'price'                   => $p['price'],
				'priceCurrency'           => 'EUR',
				'availability'            => 'https://schema.org/InStock',
				'url'                     => $permalink,
				'itemCondition'           => 'https://schema.org/NewCondition',
				'seller'                  => [ '@type' => 'Organization', 'name' => 'Caviar of Switzerland' ],
				'shippingDetails'         => cos_child_shipping_details(),
				'hasMerchantReturnPolicy' => cos_child_return_policy(),
			],
		];
	} elseif ( $is_post || cos_child_seo_is_journal_article( $post ) ) {
		// $is_post (is_singular('post')) never actually fires for the Journal —
		// its 15 articles are pages (children of 297), not WP posts — so this
		// branch previously never ran for them at all. cos_child_seo_is_journal_article()
		// is the same parent-slug check already relied on elsewhere (title tag,
		// Open Graph) to detect them correctly.
		$permalink = get_permalink( $post );
		$image_id  = get_post_thumbnail_id( $post );
		$article   = [
			'@type'            => 'Article',
			'@id'              => $permalink . '#article',
			'headline'         => cos_child_seo_is_journal_article( $post ) ? cos_child_seo_clean_title( $post->post_title ) : get_the_title( $post ),
			'description'      => cos_child_html_to_plain_description( get_the_excerpt( $post ) ),
			'datePublished'    => get_the_date( 'c', $post ),
			'dateModified'     => get_the_modified_date( 'c', $post ),
			'mainEntityOfPage' => $permalink,
			'author'           => [ '@type' => 'Organization', 'name' => 'Caviar of Switzerland' ],
			'publisher'        => [ '@id' => $site_url . '/#organization' ],
		];
		if ( $image_id ) {
			$article['image'] = wp_get_attachment_image_url( $image_id, 'full' );
		}

		// Named-author/reviewer override, read from postmeta. Defaults to the
		// Organization above until a real credentialed person is set on the
		// article — deliberately never fabricated here. See §03/§07 of the
		// editorial program plan for the credentialing decision this depends on.
		$author_name = get_post_meta( $post->ID, '_cos_journal_author_name', true );
		if ( $author_name ) {
			$author = [ '@type' => 'Person', 'name' => $author_name ];
			$author_title = get_post_meta( $post->ID, '_cos_journal_author_title', true );
			if ( $author_title ) $author['jobTitle'] = $author_title;
			$article['author'] = $author;
		}
		$reviewer_name = get_post_meta( $post->ID, '_cos_journal_reviewer_name', true );
		if ( $reviewer_name ) {
			$reviewer = [ '@type' => 'Person', 'name' => $reviewer_name ];
			$reviewer_title = get_post_meta( $post->ID, '_cos_journal_reviewer_title', true );
			if ( $reviewer_title ) $reviewer['jobTitle'] = $reviewer_title;
			$article['reviewedBy'] = $reviewer;
		}

		$schema['@graph'][] = $article;
	}

	echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>' . "\n";
}
add_action( 'wp_head', 'cos_child_structured_data', 99 );

/* ─── Organization + WebSite JSON-LD (site-wide) ────────────────── */
// A single Organization/WebSite entity, referenced by @id from the Article
// schema's "publisher" above, so search engines have one canonical brand
// entity instead of a fresh inline Organization on every page.
function cos_child_organization_schema(): void {
	$site_url = get_site_url();

	$schema = [
		'@context' => 'https://schema.org',
		'@graph'   => [
			[
				'@type'   => 'Organization',
				'@id'     => $site_url . '/#organization',
				'name'    => 'Caviar of Switzerland',
				'url'     => $site_url . '/',
				'logo'    => get_stylesheet_directory_uri() . '/assets/images/logo.png',
				'address' => [
					'@type'           => 'PostalAddress',
					'streetAddress'   => 'Chamerstrasse 115',
					'addressLocality' => 'Zug',
					'postalCode'      => '6300',
					'addressCountry'  => 'CH',
				],
				'sameAs'  => [
					'https://www.instagram.com/caviarofswitzerland/',
					'https://www.facebook.com/caviarofswitzerland/',
					'https://www.youtube.com/@caviarofswitzerland',
				],
			],
			[
				'@type'     => 'WebSite',
				'@id'       => $site_url . '/#website',
				'name'      => 'Caviar of Switzerland',
				'url'       => $site_url . '/',
				'publisher' => [ '@id' => $site_url . '/#organization' ],
			],
		],
	];

	echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>' . "\n";
}
add_action( 'wp_head', 'cos_child_organization_schema', 98 );

/* ─── FAQPage JSON-LD (page 355) ─────────────────────────────────── */
// Parsed live from the .cos-faq-item accordion markup so the schema can't
// drift out of sync with the visible Q&A content (same "pulled live"
// principle as the Product schema's price/stock above).
function cos_child_faq_schema(): void {
	if ( ! is_page( 355 ) ) return;

	$content = get_post_field( 'post_content', get_queried_object_id() );
	$found   = preg_match_all(
		'#<button class="cos-faq-q-btn"[^>]*>(.*?)</button>\s*<div class="cos-faq-answer"[^>]*>(.*?)</div>\s*</div>#s',
		$content,
		$matches,
		PREG_SET_ORDER
	);
	if ( ! $found ) return;

	$faq_items = [];
	foreach ( $matches as $m ) {
		$question = trim( wp_strip_all_tags( $m[1] ) );
		$answer   = trim( $m[2] );
		if ( '' === $question || '' === $answer ) continue;
		$faq_items[] = [
			'@type'          => 'Question',
			'name'           => $question,
			'acceptedAnswer' => [
				'@type' => 'Answer',
				'text'  => $answer,
			],
		];
	}
	if ( empty( $faq_items ) ) return;

	$schema = [
		'@context'   => 'https://schema.org',
		'@type'      => 'FAQPage',
		'mainEntity' => $faq_items,
	];

	echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>' . "\n";
}
add_action( 'wp_head', 'cos_child_faq_schema', 99 );
