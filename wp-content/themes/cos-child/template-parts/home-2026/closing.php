<?php
/**
 * 09 — Closing: The Complete Ritual as the page's final object (29 Oct 2026, critique of the
 * homepage, owner: "set object in closing"; layout C of the closing alternatives canvas, owner's
 * pick the same day: the tray photograph full-bleed on the left, the set on mineral ivory right). Until then this section repeated the hero's
 * positioning line word for word and sold the set with a text link only; the set was the one
 * product on the page that could not be seen or priced.
 *
 * Now: the four formulas together on the silver tray (hxpd-tray, also the set page's first
 * photograph; the red-ribbon gift-box composite 23723 was judged too busy on 14 Oct and is still
 * not used), its name, a line of its own, the four formulas it holds as 01–04, the
 * live set price with the separate total only while it is lower, one action and the two
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
?>
<section id="closing" class="hxz" aria-labelledby="hxz-title">
	<div class="hxz__set">
		<figure class="hxz__media" data-hx-reveal="settle">
			<a class="hxz__photo" href="<?php echo esc_url( $hxz_set_url ); ?>" tabindex="-1" aria-hidden="true">
				<?php echo cos_h26_picture( 'hxpd-tray', array( 640, 1024 ), array( 4, 5 ), '', array( 'class' => 'hxz__img', 'sizes' => '(min-width: 900px) 54vw, 100vw', 'w' => 1024 ) ); // phpcs:ignore -- escaped in the helper ?>
			</a>
		</figure>

		<div class="hxz__body">
			<div class="hxz__top">
				<h2 id="hxz-title" class="hx-display hxz__title" data-hx-reveal="light">The Complete <em>Ritual</em></h2>
				<p class="hx-lead hxz__lede" data-hx-reveal data-hx-delay="120">All four formulas, full size, for every morning and every evening.</p>
				<ol class="hxz__contents" aria-label="In The Complete Ritual" data-hx-reveal data-hx-delay="200">
					<?php foreach ( cos_h26_products() as $p ) : ?>
					<li class="hxz__item"><span class="hxz__num" aria-hidden="true"><?php echo esc_html( $p['num'] ); ?></span><span class="hxz__name"><?php echo esc_html( $p['name'][0] . ' ' . $p['name'][1] ); ?></span><span class="hxz__size"><?php echo esc_html( $p['size'] ); ?></span></li>
					<?php endforeach; ?>
				</ol>
			</div>

			<div class="hxz__act" data-hx-reveal data-hx-delay="240">
				<?php if ( $hxz_price ) : ?>
				<p class="hxz__price"><span class="hxz__amt"><?php echo esc_html( $hxz_price ); ?></span><?php if ( $hxz_apart ) : ?><span class="hxz__apart"><?php echo esc_html( $hxz_apart ); ?> bought separately</span><?php endif; ?></p>
				<?php endif; ?>
				<div class="hxz__buy">
					<a class="hx-btn hxz__btn" href="<?php echo esc_url( $hxz_set_url ); ?>" data-cro-cta="home-final-ritual">Shop the Complete Ritual<span class="hx-arrow" aria-hidden="true"></span></a>
					<ul class="hxz__assure" role="list" aria-label="Delivery and returns">
						<li>Free worldwide shipping</li>
						<li>30-day returns</li>
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
