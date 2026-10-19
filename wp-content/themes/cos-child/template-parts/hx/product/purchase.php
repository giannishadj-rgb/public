<?php
/**
 * Purchase column (hxpd). Order of reading: step → name → promise → price · size →
 * (set: what is included) → quantity + Add to bag → assurances → benefits → details.
 * The form is a real WooCommerce add-to-cart POST (works without JS); hx-product.js
 * upgrades it to AJAX so the page stays put and the bag count updates in place.
 * Legacy ids kept for tests/analytics: #cos-pdp-atc-btn, #cos-qty, data-cro-cta.
 */
defined( 'ABSPATH' ) || exit;
/** @var WC_Product $product */
$product = $args['product'];
$pd      = $args['pd'];
$me      = $args['me'];
$study   = $args['study'];
$is_set  = $args['is_set'];
$plus    = '<span class="hxpd-acc__icon" aria-hidden="true"></span>';
?>
<div class="hxpd-panel">
	<div class="hxpd-panel__inner">
		<div class="hxpd-summary">
		<?php if ( $is_set ) : ?>
		<p class="hxpd-step"><span class="hxpd-step__verb">The four steps</span><span class="hxpd-step__rule" aria-hidden="true"></span><span class="hxpd-step__of">Cleanse · Treat · Revive · Restore</span></p>
		<?php elseif ( $me ) : ?>
		<p class="hxpd-step"><span class="hx-sr">Step </span><span class="hx-step"><?php echo esc_html( $me['num'] ); ?></span><span class="hxpd-step__rule" aria-hidden="true"></span><span class="hxpd-step__verb"><?php echo esc_html( $me['verb'] ); ?></span><span class="hxpd-step__of"><span class="hx-sr">, </span>Step <?php echo esc_html( (string) (int) $me['num'] ); ?> of 4</span></p>
		<?php endif; ?>

		<h1 id="hxpd-name" class="hxpd-name"><?php echo esc_html( $args['name'] ); ?></h1>
		<?php if ( $args['promise'] ) : ?><p class="hxpd-promise"><?php echo esc_html( $args['promise'] ); ?></p><?php endif; ?>
		</div>

		<div class="hxpd-purchase">
		<p class="hxpd-price">
			<span class="hxpd-price__amt"><?php echo esc_html( $args['price_txt'] ); ?></span>
			<?php if ( $args['size'] && ! $is_set ) : ?><span class="hxpd-price__dot" aria-hidden="true"></span><span class="hx-sr">, </span><span class="hxpd-price__size"><?php echo esc_html( $args['size'] ); ?></span><?php endif; ?>
		</p>
		<?php if ( $is_set && $args['saving'] > 0 ) : ?>
		<p class="hxpd-saving"><?php echo esc_html( cos_hxpd_money( $args['individual'] ) ); ?> when bought separately <span aria-hidden="true">·</span> you save <?php echo esc_html( cos_hxpd_money( $args['saving'] ) ); ?></p>
		<?php endif; ?>

		<?php if ( $is_set ) : ?>
		<ol class="hxpd-contents" aria-label="Included in The Complete Ritual">
			<?php foreach ( $args['formulas'] as $f ) : ?>
			<li><a class="hxpd-contents__link" href="<?php echo esc_url( $f['url'] ); ?>"><span class="hxpd-contents__num"><?php echo esc_html( $f['num'] ); ?></span><span class="hxpd-contents__name"><?php echo esc_html( $f['full'] ); ?></span><span class="hxpd-contents__size"><?php echo esc_html( $f['size'] ); ?></span></a></li>
			<?php endforeach; ?>
		</ol>
		<?php endif; ?>

		<?php if ( function_exists( 'wc_print_notices' ) && wc_notice_count() ) : ?>
		<div class="hxpd-notices" role="alert"><?php wc_print_notices(); ?></div>
		<?php endif; ?>

		<?php if ( ! $args['is_simple'] ) : ?>
			<div class="hxpd-wcform"><?php woocommerce_template_single_add_to_cart(); ?></div>
		<?php elseif ( $args['in_stock'] ) : ?>
		<form class="hxpd-form cos-pdp-form cart" action="<?php echo esc_url( get_permalink( $product->get_id() ) ); ?>" method="post" enctype="multipart/form-data" data-hxpd-form data-product-id="<?php echo (int) $args['id']; ?>">
			<div class="hxpd-qty">
				<label class="hx-sr" for="cos-qty">Quantity</label>
				<button type="button" class="hxpd-qty__btn cos-pdp-qty-btn" data-hxpd-step="-1" aria-label="Decrease quantity" aria-controls="cos-qty"><span aria-hidden="true">−</span></button>
				<input id="cos-qty" class="hxpd-qty__input qty" type="number" name="quantity" value="1" min="1" max="<?php echo (int) $args['max_qty']; ?>" step="1" inputmode="numeric" autocomplete="off">
				<button type="button" class="hxpd-qty__btn cos-pdp-qty-btn" data-hxpd-step="1" aria-label="Increase quantity" aria-controls="cos-qty"><span aria-hidden="true">+</span></button>
			</div>
			<input type="hidden" name="add-to-cart" value="<?php echo (int) $args['id']; ?>">
			<button type="submit" class="hx-btn hxpd-add" id="cos-pdp-atc-btn" data-cro-cta="pdp-primary-atc"><span data-hxpd-label>Add to bag</span></button>
		</form>
		<?php else : ?>
		<p class="hxpd-unavailable">Currently unavailable. <?php echo $is_set ? 'Each formula can still be bought on its own.' : ''; ?></p>
		<?php endif; ?>

		<p class="hxpd-confirm" role="status" aria-live="polite" data-hxpd-confirm><?php if ( $args['added'] ) : ?>Added to your bag. <a class="hxpd-confirm__link" href="<?php echo esc_url( $args['cart_url'] ); ?>">View bag</a><?php endif; ?></p>

		<?php if ( $study ) : ?>
		<p class="hxpd-evidence"><a href="#hxpd-result" class="hxpd-evidence__link"><span class="hxpd-evidence__fig"><?php echo esc_html( $study['sign'] . $study['n'] . $study['unit'] ); ?></span> <?php echo esc_html( strtolower( $study['finding'] ) ); ?> <span class="hxpd-evidence__kind">(<?php echo esc_html( strtolower( $study['kind'] ) ); ?>, 28 days)</span></a></p>
		<?php endif; ?>

		<ul class="hxpd-assure">
			<li><?php echo esc_html( $args['shipping'] ); ?></li>
			<li><a href="<?php echo esc_url( home_url( '/shipping-returns/' ) ); ?>">Returns within 30 days</a></li>
		</ul>

		<?php if ( ! empty( $pd['benefits'] ) && ! $is_set ) : ?>
		<ul class="hxpd-benefits" aria-label="Benefits">
			<?php foreach ( array_slice( $pd['benefits'], 0, 3 ) as $b ) : ?><li><?php echo esc_html( $b ); ?></li><?php endforeach; ?>
		</ul>
		<?php endif; ?>

		<div class="hxpd-acc">
			<?php if ( ! $is_set ) : ?>
			<details class="hxpd-acc__item">
				<summary class="hxpd-acc__sum">Details<?php echo $plus; // phpcs:ignore ?></summary>
				<dl class="hxpd-specs">
					<?php foreach ( $pd['specs'] ?? array() as $spec ) : ?>
					<div><dt><?php echo esc_html( $spec[0] ); ?></dt><dd><?php echo esc_html( $spec[1] ); ?></dd></div>
					<?php endforeach; ?>
					<?php if ( $me ) : ?><div><dt>In the ritual</dt><dd>Step <?php echo esc_html( (string) (int) $me['num'] ); ?> of 4, morning and evening</dd></div><?php endif; ?>
				</dl>
			</details>
			<details class="hxpd-acc__item" id="hxpd-ingredients">
				<summary class="hxpd-acc__sum">Full ingredient list<?php echo $plus; // phpcs:ignore ?></summary>
				<p class="hxpd-inci"><?php echo esc_html( $pd['inci'] ?? '' ); ?></p>
				<p class="hxpd-inci__note">Refer to the packaging for the most up-to-date list.</p>
			</details>
			<?php else : ?>
			<details class="hxpd-acc__item">
				<summary class="hxpd-acc__sum">The ritual box<?php echo $plus; // phpcs:ignore ?></summary>
				<p class="hxpd-acc__text">All four formulas, presented in the Caviar of Switzerland ritual box with a written ritual guide. Ready to give.</p>
			</details>
			<details class="hxpd-acc__item" id="hxpd-ingredients">
				<summary class="hxpd-acc__sum">Full ingredient lists<?php echo $plus; // phpcs:ignore ?></summary>
				<?php foreach ( $args['formulas'] as $f ) : ?>
				<p class="hxpd-inci"><strong><?php echo esc_html( $f['full'] ); ?>.</strong> <?php echo esc_html( $f['inci'] ); ?></p>
				<?php endforeach; ?>
				<p class="hxpd-inci__note">Refer to the packaging for the most up-to-date lists.</p>
			</details>
			<?php endif; ?>
			<details class="hxpd-acc__item">
				<summary class="hxpd-acc__sum">Delivery &amp; returns<?php echo $plus; // phpcs:ignore ?></summary>
				<p class="hxpd-acc__text"><?php echo esc_html( $args['shipping'] ); ?> Returns are accepted within 30 days. <a href="<?php echo esc_url( home_url( '/shipping-returns/' ) ); ?>">Delivery and returns in full</a></p>
			</details>
		</div>
		</div>
	</div>
</div>
