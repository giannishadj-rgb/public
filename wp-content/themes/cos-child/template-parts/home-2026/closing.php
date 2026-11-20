<?php
/**
 * 09 — Closing: The Complete Ritual as the page's final object (29 Oct 2026, critique of the
 * homepage, owner: "set object in closing"; layout C of the closing alternatives canvas, owner's
 * pick the same day: the tray photograph full-bleed on the left, the set on mineral ivory right). Until then this section repeated the hero's
 * positioning line word for word and sold the set with a text link only; the set was the one
 * product on the page that could not be seen or priced.
 *
 * Now (31 Oct 2026, owner): the four formulas on stepped mirror glass, full-bleed, a hairline from
 * each to its name and size (hxz-mirror-wide ≥900px, hxz-mirror-narrow below; owner's photo, the
 * cleanser label's "50 ml" corrected to "150 ml" by copying the label's own "1", as on the mirror
 * hero; the silver tray, hxpd-tray, is still the set page's photo). Beneath: its name, a line of
 * its own (the 01–04 list went: the photograph names the four now), the live set price with the separate total only while it is lower, one action and the two
 * service assurances (owner, 6 Oct: only these two). "Four formulas. One complete ritual."
 * stays the hero's line (round-1 ruling). Nothing here says the cleanser travels inside the
 * box: that is still undecided (PRODUCT.md).
 *
 * The section still ends on the signature: a hairline, the brand mark's own Swiss cross, the
 * house name and "Made in Zug". Styles: assets/css/hx/closing.css (.hxz-*).
 */
defined( 'ABSPATH' ) || exit;

$hxz_set_url = home_url( '/products/the-complete-ritual/' );
$hxz_price   = function_exists( 'cos_h26_ritual_price' ) ? cos_h26_ritual_price() : '';
$hxz_post    = get_page_by_path( 'the-complete-ritual', OBJECT, 'product' );
$hxz_wc      = ( $hxz_post && function_exists( 'wc_get_product' ) ) ? wc_get_product( $hxz_post ) : null;
$hxz_total   = function_exists( 'cos_h26_individual_total' ) ? cos_h26_individual_total() : 0.0;
$hxz_apart   = ( $hxz_wc && $hxz_total > (float) $hxz_wc->get_price() )
	? html_entity_decode( wp_strip_all_tags( wc_price( $hxz_total, array( 'decimals' => 0 ) ) ), ENT_QUOTES, 'UTF-8' )
	: '';

/*
 * The callouts (owner, 31 Oct 2026: "use lines to point to each product and show the product name").
 * Anchors are measured on the 1448x1086 photograph and given in % of each crop:
 *   stage  (>=1200px, x -361-1809, y -400-820; widened 361px a side and 400px on top along the
 *          slabs' own diagonals, scratchpad extend.py, the added top lifted a touch so the set's
 *          name, price and action sit on it in ink): --sx/--sy/--slen
 *   wide   (900-1199px, x -145-1593, y -80-834): --x/--y = where the line meets the product, --len its length
 *   narrow (<900px,  x 360-1100, y 0-630): --mx = the product's centre; the tag hangs below the photo
 *   tall   (<600px, x 721-1461, y 100-1030 of the stage crop; 7 Nov 2026): the full name sits just
 *          above its product, centred on it (--qx, column width --pw, % of the crop), on a short
 *          hairline (--ql, default 24px) ending at the product's top (--py)
 *   --t / --mt = when the timed entrance's band of light crosses that product (only where the
 *   browser has no scroll-driven animation; closing.css, Motion): (x + 10%) / 170% of 1400ms.
 * Each line runs into open background: serum to the left, the eye and 24h creams upward (over
 * their caps), the cleanser to the right (its cap nearly meets the top edge).
 */
$hxz_by_slug = array();
foreach ( cos_h26_products() as $p ) $hxz_by_slug[ $p['slug'] ] = $p;
$hxz_calls = array(
	array( 'slug' => 'advanced-repair-serum',   'short' => 'Serum',     'dir' => 'left',  'pos' => '--sx:35.62%;--sy:63.11%;--slen:5.5%;--x:32.05%;--y:49.23%;--len:6%;--mx:14.9%;--qx:15%;--pw:22%;--py:49.7%;--ql:3.75rem;--t:346ms;--mt:205ms' ),
	array( 'slug' => 'revitalizing-eye-cream',  'short' => 'Eye Cream', 'dir' => 'up',    'pos' => '--sx:44.42%;--sy:49.75%;--slen:9%;--x:43.04%;--y:31.4%;--len:10%;--mx:32.8%;--qx:33.1%;--pw:23%;--py:54.8%;--t:436ms;--mt:352ms' ),
	array( 'slug' => '24h-regeneration-cream',  'short' => '24h Cream', 'dir' => 'up',    'pos' => '--sx:53.04%;--sy:60.16%;--slen:9%;--x:53.8%;--y:45.3%;--len:10%;--mx:58.1%;--qx:58.1%;--pw:26%;--py:68.4%;--t:527ms;--mt:561ms' ),
	array( 'slug' => 'micellar-water-cleanser', 'short' => 'Cleanser',  'dir' => 'right', 'pos' => '--sx:65.76%;--sy:49.18%;--slen:5.5%;--x:69.68%;--y:41.58%;--len:6%;--mx:85.8%;--qx:85.8%;--pw:23%;--py:35.6%;--t:659ms;--mt:789ms' ),
);
?>
<section id="closing" class="hxz" aria-labelledby="hxz-title">
	<div class="hxz__stage">
	<figure class="hxz__media" data-hx-reveal="settle" data-hx-at="0.8">
		<div class="hxz__canvas">
			<?php
			echo cos_h26_picture( // phpcs:ignore -- escaped in the helper
				'hxz-mirror-narrow',
				array( 740 ),
				array( 740, 630 ),
				'The four formulas of The Complete Ritual on stepped mirror glass: Advanced Repair Serum, Revitalizing Eye Cream, 24h Regeneration Cream and Micellar Water All-in-One Cleanser',
				array(
					'class' => 'hxz__img',
					'sizes' => '100vw',
					'w'     => 740,
					'art'   => array(
						// 7 Nov 2026 (owner): phones get a taller crop (stage x 721-1461, y 100-1030: the narrow crop
							// plus 300px of the stage's own extended grey above) so the full names sit over the products
							array( 'media' => '(max-width: 599.98px)', 'base' => 'hxz-mirror-tall', 'widths' => array( 740 ), 'sizes' => '100vw' ),
							array( 'media' => '(min-width: 1200px)', 'base' => 'hxz-mirror-stage', 'widths' => array( 1440, 2170 ), 'sizes' => '100vw' ),
						array( 'media' => '(min-width: 900px)', 'base' => 'hxz-mirror-wide', 'widths' => array( 1080, 1448, 1738 ), 'sizes' => '100vw' ),
					),
				)
			);
			?>
			<?php foreach ( $hxz_calls as $c ) : $p = $hxz_by_slug[ $c['slug'] ] ?? null; if ( ! $p ) continue; ?>
			<span class="hxz__call hxz__call--<?php echo esc_attr( $c['dir'] ); ?>" style="<?php echo esc_attr( $c['pos'] ); ?>">
				<span class="hxz__line" aria-hidden="true"></span>
				<a class="hxz__tag" href="<?php echo esc_url( $p['url'] ); ?>" data-cro-cta="home-closing-callout"><span class="hxz__tag-name"><span class="hxz__tag-full"><?php echo esc_html( str_replace( '-', "\u{2011}", $p['name'][0] . ' ' . $p['name'][1] ) ); // non-breaking hyphens: never "All-" / "in-One" ?></span><span class="hxz__tag-short"><?php echo esc_html( $c['short'] ); ?></span></span><span class="hxz__tag-size"><?php echo esc_html( $p['size'] ); ?></span></a>
			</span>
			<?php endforeach; ?>
		</div>
	</figure>

	<div class="hx-wrap hxz__body">
		<div class="hxz__top">
			<h2 id="hxz-title" class="hx-display hxz__title" data-hx-reveal="light">The Complete <em>Ritual</em></h2>
			<p class="hx-lead hxz__lede" data-hx-reveal data-hx-delay="120">All four formulas, full size, for every morning and every evening.</p>
		</div>

		<div class="hxz__act" data-hx-reveal data-hx-delay="200">
			<?php if ( $hxz_price ) : ?>
			<p class="hxz__price"><span class="hxz__amt"><?php echo esc_html( $hxz_price ); ?></span><?php if ( $hxz_apart ) : ?><span class="hxz__apart"><?php echo esc_html( $hxz_apart ); ?> bought separately</span><?php endif; ?></p>
			<?php endif; ?>
			<div class="hxz__buy">
				<a class="hx-btn hxz__btn" href="<?php echo esc_url( $hxz_set_url ); ?>" data-cro-cta="home-final-ritual">Shop the Complete Ritual<span class="hx-arrow" aria-hidden="true"></span></a>
				<?php /* 7 Nov 2026 (owner): both on one line, short words, a small line icon each */ ?>
				<ul class="hxz__assure" role="list" aria-label="Delivery and returns">
					<li><svg class="hxz__ico" viewBox="0 0 20 20" aria-hidden="true" focusable="false"><path d="M1.5 5.5h10v8h-10zM11.5 8.5h3.6l2.4 2.6v2.4h-6"/><circle cx="5" cy="14.5" r="1.5"/><circle cx="14.5" cy="14.5" r="1.5"/></svg>Free delivery</li>
					<li><svg class="hxz__ico" viewBox="0 0 20 20" aria-hidden="true" focusable="false"><path d="M4.5 8.5a6 6 0 1 1 1.2 6.2"/><path d="M4.2 4.5v4.3h4.3"/></svg>30-day returns</li>
				</ul>
			</div>
		</div>
	</div>
	</div>

	<div class="hx-wrap hxz__inner">
		<div class="hxz__sign" data-hx-reveal="fade">
			<span class="hxz__rule" aria-hidden="true"></span>
			<p class="hxz__house">
				<svg class="hxz__cross" viewBox="0 0 33 33" aria-hidden="true" focusable="false"><rect width="33" height="33" fill="#E33A41"/><path d="M13.2 6h6.6v7.2H27v6.6h-7.2V27h-6.6v-7.2H6v-6.6h7.2z" fill="#fff"/></svg>
				<span class="hx-label">Caviar of Switzerland</span>
				<span class="hx-label hxz__place">Made in Zug</span>
			</p>
		</div>
	</div>
</section>
