<?php
/**
 * COS Child Theme — product page data (hxpd, 16 Oct 2026).
 *
 * Loaded only by woocommerce/single-product.php. Builds everything the product
 * templates in template-parts/hx/product/ render, from three sources of truth:
 *   - WooCommerce (name, live price, stock, gallery, add-to-cart),
 *   - cos_get_product_copy() in inc/product-data.php (benefits, actives, how, faqs, INCI…),
 *   - cos_h26_products() in inc/home-2026.php (step number + verb, names, sizes, packshots),
 * plus the per-formula study details below (page 347, BRIEF §4).
 */

defined( 'ABSPATH' ) || exit;

/**
 * Study behind each published figure — one formula, one method, one panel.
 * Source: /science/clinical-studies/ (page 347). The cleanser has no study; the four
 * formulas were never studied together as a set. Keep in step with trust.php.
 */
function cos_hxpd_studies(): array {
	return array(
		'COS-002' => array(
			'product' => 'Advanced Repair Serum',
			'sign'    => '+',
			'n'       => '47',
			'unit'    => '%',
			'finding' => 'Skin firmness',
			'kind'    => 'Instrument-measured',
			'method'  => 'Cutometry',
			'panel'   => '32 women aged 45–60',
			'regimen' => 'twice daily for 28 days',
		),
		'COS-003' => array(
			'product' => 'Revitalizing Eye Cream',
			'sign'    => '−',
			'n'       => '38',
			'unit'    => '%',
			'finding' => 'Crow’s-feet depth',
			'kind'    => 'Instrument-measured',
			'method'  => 'Optical profilometry',
			'panel'   => '30 women aged 42–65',
			'regimen' => 'twice daily for 28 days',
		),
		'COS-004' => array(
			'product' => '24h Regeneration Cream',
			'sign'    => '',
			'n'       => '100',
			'unit'    => '%',
			'finding' => 'Panellist-reported satisfaction',
			'kind'    => 'Self-reported',
			'method'  => 'Panellist self-assessment',
			'panel'   => '34 women aged 40–65',
			'regimen' => 'twice daily for 28 days',
		),
	);
}

/** One-line promise under the product name (claims already published on the PDPs). */
function cos_hxpd_promise( string $sku ): string {
	$promises = array(
		'COS-001'    => 'A no-rinse micellar water that lifts make-up and the day’s impurities in one gentle gesture.',
		'COS-002'    => 'A lightweight, concentrated serum for skin that looks firmer and smoother.',
		'COS-003'    => 'A rich, melting eye cream that softens the look of fine lines and dark circles.',
		'COS-004'    => 'A silky day-and-night cream that seals in lasting hydration and comfort.',
		'COS-BUNDLE' => 'The four formulas of the house, made to be used in sequence, morning and evening.',
	);
	return $promises[ $sku ] ?? '';
}

/** "30ml" → "30 ml". */
function cos_hxpd_size( WC_Product $product ): string {
	foreach ( $product->get_attributes() as $attr ) {
		if ( 'size' === strtolower( $attr->get_name() ) ) {
			$opts = $attr->get_options();
			$size = (string) ( $opts[0] ?? '' );
			return $size ? preg_replace( '/(\d)\s*(ml|g|oz)\b/i', '$1 $2', $size ) : '';
		}
	}
	return '';
}

/** Price formatted like the rest of the site (no decimals for whole euros). */
function cos_hxpd_money( float $amount ): string {
	$decimals = ( floor( $amount ) == $amount ) ? 0 : wc_get_price_decimals(); // phpcs:ignore -- loose compare on purpose
	return html_entity_decode( wp_strip_all_tags( wc_price( $amount, array( 'decimals' => $decimals ) ) ), ENT_QUOTES, 'UTF-8' );
}

/**
 * <picture> for a media-library attachment with AVIF/WebP siblings where they exist on
 * disk (the image-optimisation backfill wrote them next to each size).
 */
function cos_hxpd_attachment_picture( int $aid, string $alt, string $sizes, bool $eager = false ): string {
	$data = wp_get_attachment_image_src( $aid, 'large' );
	if ( ! $data ) return '';
	$srcset = (string) wp_get_attachment_image_srcset( $aid, 'large' );
	$alt_srcset = static function ( string $set, string $ext ): string {
		$out = array();
		foreach ( array_filter( array_map( 'trim', explode( ',', $set ) ) ) as $part ) {
			$sp  = strrpos( $part, ' ' );
			$url = false !== $sp ? substr( $part, 0, $sp ) : $part;
			$alt = preg_replace( 'webp' === $ext ? '/\.jpe?g$/i' : '/\.(jpe?g|webp)$/i', '.' . $ext, $url );
			if ( $alt === $url ) continue;
			if ( file_exists( ABSPATH . ltrim( (string) wp_parse_url( $alt, PHP_URL_PATH ), '/' ) ) ) {
				$out[] = $alt . ( false !== $sp ? substr( $part, $sp ) : '' );
			}
		}
		return implode( ', ', $out );
	};
	$out = '<picture>';
	if ( $srcset ) {
		foreach ( array( 'avif', 'webp' ) as $ext ) {
			$set = $alt_srcset( $srcset, $ext );
			if ( $set ) $out .= '<source type="image/' . $ext . '" srcset="' . esc_attr( $set ) . '" sizes="' . esc_attr( $sizes ) . '">';
		}
	}
	$out .= '<img src="' . esc_url( $data[0] ) . '"'
		. ( $srcset ? ' srcset="' . esc_attr( $srcset ) . '" sizes="' . esc_attr( $sizes ) . '"' : '' )
		. ' width="' . (int) $data[1] . '" height="' . (int) $data[2] . '" alt="' . esc_attr( $alt ) . '"'
		. ( $eager ? ' fetchpriority="high"' : ' loading="lazy"' ) . ' decoding="async">';
	return $out . '</picture>';
}

/** Everything the product templates need, in one array (passed as $args). */
function cos_hxpd_context( WC_Product $product ): array {
	$sku    = (string) $product->get_sku();
	$is_set = 'COS-BUNDLE' === $sku;
	$pd     = cos_get_product_copy( $sku ) ?: array();

	// The four formulas, in ritual order, with the homepage's step verbs and packshots.
	$keys = array(
		'micellar-water-cleanser' => array( 'cleanser', 'COS-001' ),
		'advanced-repair-serum'   => array( 'serum', 'COS-002' ),
		'revitalizing-eye-cream'  => array( 'eye', 'COS-003' ),
		'24h-regeneration-cream'  => array( 'cream', 'COS-004' ),
	);
	$formulas = array();
	foreach ( cos_h26_products() as $p ) {
		if ( ! isset( $keys[ $p['slug'] ] ) ) continue;
		[ $p['key'], $p['sku'] ] = $keys[ $p['slug'] ];
		$p['full']    = str_replace( '-', "\u{2011}", trim( $p['name'][0] . ' ' . $p['name'][1] ) ); // keep "All-in-One" whole
		$p['current'] = $p['slug'] === $product->get_slug();
		$p['inci']    = ( cos_get_product_copy( $p['sku'] ) ?: array() )['inci'] ?? '';
		$formulas[]   = $p;
	}
	$me = null;
	foreach ( $formulas as $f ) if ( $f['current'] ) $me = $f;

	// Gallery: the studio packshot first (the product on its own), then the owner's
	// photographs from the WooCommerce gallery. Images used further down the page are
	// left out so nothing repeats. Sizes match the CSS layout (first large, rest 2-up).
	$slides     = array();
	$first_size = '(min-width: 1024px) 56vw, 100vw';
	$rest_size  = '(min-width: 1024px) 28vw, 100vw';
	if ( $is_set ) {
		$slides[] = cos_h26_picture( 'hxpd-tray', array( 640, 1024 ), array( 4, 5 ), 'The Complete Ritual: the four Caviar of Switzerland formulas on a silver tray', array( 'sizes' => $first_size, 'eager' => true, 'w' => 1024 ) );
		$ids = array( 23725, 23717, 482, 23721 );
	} else {
		if ( $me ) {
			// The clean cut-out packshot shared with the homepage collection and the shop
			// (transparent AVIF/WebP; JPEG fallback on #FBFAF6), shown whole on the page ground.
			$slides[] = cos_h26_picture( 'pack-' . $me['key'], array( 480, 720, 960 ), array( 2, 3 ), $pd['image_alt'] ?? $me['cardalt'], array( 'sizes' => '(min-width: 1024px) 40vw, 80vw', 'eager' => true, 'w' => 960, 'class' => 'hxpd-gallery__object' ) );
		}
		// 151 = used elsewhere on the site; 182 (CAVIAR3378) opens "Who it is for" on the eye cream's page
		// 195 (CAVIAR3501) opens "Who it is for" on the cleanser's page, 202 (CAVIAR3602) on the
		// serum's, 179 (CAVIAR3365) on the cream's, whose "What it does" is 176 (CAVIAR3326) (28 Oct 2026)
		$skip = array( 'COS-003' => array( 151, 478, 182 ), 'COS-001' => array( 195 ), 'COS-002' => array( 202 ), 'COS-004' => array( 176, 179 ) );
		$ids  = array_values( array_diff( $product->get_gallery_image_ids() ?: array( (int) $product->get_image_id() ), $skip[ $sku ] ?? array() ) );
	}
	$odd_last = 1 === count( $ids ) % 2 ? end( $ids ) : 0; // spans the full column on desktop
	foreach ( $ids as $aid ) {
		$alt = (string) get_post_meta( $aid, '_wp_attachment_image_alt', true );
		$alt = $alt ? str_replace( ' ? ', ' — ', $alt ) : $product->get_name() . ', photographed by Caviar of Switzerland';
		$pic = cos_hxpd_attachment_picture( (int) $aid, $alt, $aid === $odd_last ? $first_size : $rest_size );
		if ( $pic ) $slides[] = $pic;
	}
	// The LCP image keeps its legacy id (tests/12, analytics): #cos-gallery-main.
	if ( $slides ) $slides[0] = preg_replace( '/<img /', '<img id="cos-gallery-main" ', $slides[0], 1 );

	// The Guerlain-style page: one large image with a thumbnail rail. Thumbnails are the same
	// pictures asked for at rail size, lazily. Owner, 25 Oct 2026: the Micellar Water page only;
	// 28 Oct 2026: all four formulas. (The eye cream's Augustinus Bader-style page, 'ab' with
	// formula-ab.php / purchase-ab.php, is kept on disk but no longer mapped.)
	// 29 Oct 2026: The Complete Ritual too ("like the product pages"); it keeps its tray photograph first.
	$look   = ( $me || $is_set ) ? 'g' : '';
	$thumbs = array();
	if ( 'g' === $look ) {
		// Owner, 28 Oct 2026: remove the bottle-only packshot and lead with the
		// first owner gallery photograph (the cleanser: presented between two hands).
		if ( count( $slides ) > 1 && ! $is_set ) {
			array_shift( $slides );
			$slides[0] = preg_replace( '/\ssizes="[^"]*"/', ' sizes="(min-width: 1024px) 44vw, 100vw"', $slides[0] );
			$slides[0] = preg_replace( '/\sloading="lazy"/', ' loading="eager" fetchpriority="high"', $slides[0], 1 );
			if ( 'COS-001' === $sku ) $slides[0] = preg_replace( '/\salt="[^"]*"/', ' alt="Micellar Water All-in-One Cleanser presented between two hands"', $slides[0], 1 );
			$slides[0] = preg_replace( '/<img /', '<img id="cos-gallery-main" ', $slides[0], 1 );
		}
		foreach ( $slides as $html ) {
			$t = preg_replace( '/\ssizes="[^"]*"/', ' sizes="88px"', $html );
			$t = preg_replace( '/\s(id|fetchpriority)="[^"]*"/', '', $t );
			$t = preg_replace( '/\sloading="[^"]*"/', '', $t );
			$t = preg_replace( '/\salt="[^"]*"/', ' alt=""', $t );
			$thumbs[] = preg_replace( '/<img /', '<img loading="lazy" ', $t, 1 );
		}
	}
	if ( $look ) {
		// the ritual row is shoppable: each formula's price and id
		foreach ( $formulas as &$f ) {
			$fp = get_page_by_path( $f['slug'], OBJECT, 'product' );
			$fw = $fp ? wc_get_product( $fp ) : null;
			$f['pid']   = $fw ? $fw->get_id() : 0;
			$f['price'] = $fw ? cos_hxpd_money( (float) $fw->get_price() ) : '';
			$f['buy']   = $fw && $fw->is_purchasable() && $fw->is_in_stock() && $fw->is_type( 'simple' );
		}
		unset( $f );
	}

	// Price, comparison and stock.
	$price      = (float) $product->get_price();
	$individual = function_exists( 'cos_h26_individual_total' ) ? cos_h26_individual_total() : 0.0;
	$saving     = $is_set && $individual > $price ? $individual - $price : 0.0;

	$ritual_post  = get_page_by_path( 'the-complete-ritual', OBJECT, 'product' );
	$ritual_wc    = $ritual_post ? wc_get_product( $ritual_post ) : null;

	// Non-JS add-to-cart: WooCommerce re-renders this page after the POST. Its success
	// message is blanked sitewide (inc/commerce.php), so confirm it here ourselves.
	$posted = isset( $_POST['add-to-cart'] ) && absint( wp_unslash( $_POST['add-to-cart'] ) ) === $product->get_id(); // phpcs:ignore WordPress.Security.NonceVerification
	$added  = $posted && function_exists( 'wc_notice_count' ) && 0 === wc_notice_count( 'error' );

	return array(
		'product'    => $product,
		'id'         => $product->get_id(),
		'sku'        => $sku,
		'is_set'     => $is_set,
		'name'       => str_replace( '-', "\u{2011}", $product->get_name() ), // "All-in-One" never splits
		'promise'    => cos_hxpd_promise( $sku ),
		'size'       => cos_hxpd_size( $product ),
		'price'      => $price,
		'price_txt'  => cos_hxpd_money( $price ),
		'individual' => $individual,
		'saving'     => $saving,
		'pd'         => $pd,
		'me'         => $me,
		'formulas'   => $formulas,
		'look'       => $look,
		'thumbs'     => $thumbs,
		'study'      => cos_hxpd_studies()[ $sku ] ?? null,
		'studies'    => cos_hxpd_studies(),
		'slides'     => $slides,
		'in_stock'   => $product->is_in_stock() && $product->is_purchasable(),
		'is_simple'  => $product->is_type( 'simple' ),
		'max_qty'    => (int) COS_MAX_LINE_ITEM_QTY,
		'cart_url'   => wc_get_cart_url(),
		'added'      => $added,
		'ritual'     => $ritual_wc ? array( 'url' => get_permalink( $ritual_post ), 'price' => cos_hxpd_money( (float) $ritual_wc->get_price() ) ) : null,
		'clinical'   => home_url( '/science/clinical-studies/' ),
		'shipping'   => cos_child_shipping_message( 'long' ),
	);
}
