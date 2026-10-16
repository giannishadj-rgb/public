<?php
/**
 * Phone/tablet add-to-bag bar (hxpd, <1024px). Shown by hx-product.js while the primary
 * Add to bag button is out of view; it submits the same form (same quantity), so there is
 * one purchase path. Hidden from AT and the tab order while off screen.
 */
defined( 'ABSPATH' ) || exit;
if ( ! $args['in_stock'] || ! $args['is_simple'] ) return;
?>
<div id="cos-pdp-sticky-atc" class="hxpd-bar" aria-hidden="true" data-hxpd-bar>
	<div class="hxpd-bar__info">
		<span class="hxpd-bar__name"><?php echo esc_html( $args['name'] ); ?></span>
		<span class="hxpd-bar__price"><?php echo esc_html( $args['price_txt'] ); ?><?php if ( $args['size'] && ! $args['is_set'] ) : ?> <span aria-hidden="true">·</span> <?php echo esc_html( $args['size'] ); ?><?php endif; ?></span>
	</div>
	<button type="button" class="hx-btn hxpd-bar__btn" tabindex="-1" data-hxpd-bar-btn data-cro-cta="pdp-sticky-atc"><span data-hxpd-label>Add to bag</span></button>
</div>
