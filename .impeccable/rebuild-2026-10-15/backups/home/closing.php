<?php
/**
 * 09 — Closing: the brand statement that seals the page (14 Oct 2026 rebuild,
 * revised after critique round 1).
 *
 * Typography carries it. The positioning line is set flush left as the largest
 * type on the page, sized so the italic second line never breaks (two balanced
 * lines from 320px to 1920px). Beneath it is the page's final conversion moment,
 * moved here from the ritual section and set as a quiet text-and-rule action
 * rather than a second filled button. It has one action (the Complete Ritual; no
 * price here, the product page carries it) and the two service assurances
 * (owner, 6 Oct: only these two). The section ends on a signature: a hairline,
 * the brand mark's own Swiss cross (geometry and red from
 * assets/images/logo.png; no new logo), the house name and "Made in Zug".
 * "Four formulas. One complete ritual." belongs to the hero only (round-1 ruling).
 * No product image: the gift-box composite (attachment 23723) and the tray
 * photograph both made the ending busier, not stronger.
 * Styles: assets/css/hx/closing.css (.hxz-*).
 */
defined( 'ABSPATH' ) || exit;
?>
<section id="closing" class="hxz" aria-labelledby="hxz-title">
	<div class="hx-wrap hxz__inner">
		<h2 id="hxz-title" class="hx-display hxz__title" data-hx-reveal><span class="hxz__line">Swiss skincare,</span> <span class="hxz__line"><em>refined to the essential.</em></span></h2>

		<div class="hxz__act" data-hx-reveal data-hx-delay="240">
			<a class="hxz__cta" href="<?php echo esc_url( home_url( '/products/the-complete-ritual/' ) ); ?>" data-cro-cta="home-final-ritual"><span class="hxz__cta-text">Shop the Complete Ritual</span><span class="hxz__cta-rule" aria-hidden="true"></span></a>
			<ul class="hxz__assure" role="list" aria-label="Delivery and returns">
				<li>Free worldwide shipping</li>
				<li>30-day returns</li>
			</ul>
		</div>

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
