<?php
/**
 * Purchase column (hxpd). Order of reading: step → name → promise → price · size →
 * (set: what is included) → quantity + Add to bag → assurances (services) → evidence → the
 * ritual → benefits → details.
 * The form is a real WooCommerce add-to-cart POST (works without JS: the add is processed
 * once and redirects to /cart/, inc/commerce.php); hx-product.js upgrades it to AJAX so
 * the page stays put and the bag count updates in place.
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
		<?php if ( $is_set && 'g' === ( $args['look'] ?? '' ) ) :
			// 3 Nov 2026 (owner: "apply the serum changes"): no line above the name; the four steps are the
			// routine row under the services ?>
		<?php elseif ( $is_set ) : ?>
		<p class="hxpd-step"><span class="hxpd-step__verb">The four steps</span><span class="hxpd-step__rule" aria-hidden="true"></span><span class="hxpd-step__of">Cleanse · Treat · Revive · Restore</span></p>
		<?php elseif ( $me && 'g' === ( $args['look'] ?? '' ) ) :
			// 3 Nov 2026 (owner): the step line and its meter moved into "In the ritual" below, so the
			// step is stated once ?>
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
		<?php // the "You save / bought separately" lines were removed from the set page (owner, 3 Nov 2026) ?>

		<?php if ( $is_set && ! ( 'g' === ( $args['look'] ?? '' ) ) ) : // the routine row below lists the four on this look ?>
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

		<p class="hxpd-confirm" role="status" aria-live="polite" data-hxpd-confirm></p>

		<?php // 1 Nov 2026 (owner, after a critique): the service assurances sit directly under Add to bag ?>
		<?php if ( 'g' === ( $args['look'] ?? '' ) ) : ?>
		<ul class="hxpd-services" aria-label="Our services">
			<li><span class="hxpd-services__icon hxpd-services__icon--ship" aria-hidden="true"></span><?php echo esc_html( cos_child_shipping_message( 'short' ) ); ?></li>
			<li><span class="hxpd-services__icon hxpd-services__icon--return" aria-hidden="true"></span>30-day returns</li>
			<li><span class="hxpd-services__icon hxpd-services__icon--lock" aria-hidden="true"></span>Secure checkout</li>
		</ul>
		<?php else : ?>
		<ul class="hxpd-assure">
			<li><?php echo esc_html( $args['shipping'] ); ?></li>
			<li><a href="<?php echo esc_url( home_url( '/shipping-returns/' ) ); ?>">Returns within 30 days</a></li>
		</ul>
		<?php endif; ?>

		<?php // the one-line study result under the services was removed (owner, 3 Nov 2026); the full result stays further down the page (#hxpd-result) ?>

		<?php // the step row was removed from The Complete Ritual (owner, 3 Nov 2026) ?>
		<?php if ( $me && 'g' === ( $args['look'] ?? '' ) ) : // the four formula pages ?>
		<?php // 3 Nov 2026 (owner, ref: an "at-home facial" step chart; then a "Your daily routine" row): the ritual as one horizontal
		// timeline — "Step N" over a hairline with a dot per step, each pack at its true relative size,
		// the step named in italic serif. This formula is the filled dot; the other three link to their
		// pages. Under it, this step's one gesture. "In your bag" per step (hx-product.js keeps it current). ?>
		<section class="hxpd-tl" aria-labelledby="hxpd-tl-title">
			<div class="hxpd-tl__head">
				<?php // visible heading removed (owner, 3 Nov 2026); kept for screen readers as the section's name ?>
				<h2 id="hxpd-tl-title" class="hx-sr"><?php echo $is_set ? 'Included in The Complete Ritual' : 'The ritual, morning and evening'; ?></h2>
				<p class="hxpd-tl__count" data-hxpd-owned<?php echo $args['owned'] ? '' : ' hidden'; ?>><?php echo esc_html( $args['owned'] . ' of 4 in your bag' ); ?></p>
			</div>
			<ol class="hxpd-tl__list">
				<?php foreach ( $args['formulas'] as $f ) :
					$tag = $f['current'] ? 'div' : 'a'; ?>
				<li class="hxpd-tl__step hxpd-tl__step--<?php echo esc_attr( $f['key'] ); ?><?php echo $f['current'] ? ' is-current' : ''; ?><?php echo ! empty( $f['owned'] ) ? ' is-owned' : ''; ?>" data-hxpd-pid="<?php echo (int) ( $f['pid'] ?? 0 ); ?>"<?php echo $f['current'] ? ' aria-current="step"' : ''; ?>>
					<<?php echo $tag; ?> class="hxpd-tl__link"<?php echo $f['current'] ? '' : ' href="' . esc_url( $f['url'] ) . '"'; ?>>
						<?php // 12 Nov 2026 (owner, ref: numbered hairline circles): no packshots; the step named inside a fine ring, its number above ?>
						<span class="hxpd-tl__no"><span class="hx-sr">Step </span><?php echo esc_html( $f['num'] ); ?></span>
						<span class="hxpd-tl__orb"><span class="hxpd-tl__verb"><?php echo esc_html( $f['verb'] ); ?></span></span>
						<span class="hxpd-tl__why"><?php echo esc_html( $is_set ? $f['size'] : str_replace( '-', "‑", $f['benefit'] ) ); // the set: what is in the box ?></span>
						<span class="hx-sr">: <?php echo esc_html( $f['full'] ); ?><?php echo $f['current'] ? ', this formula' : ''; ?></span>
						<span class="hxpd-tl__state" data-hxpd-state><?php echo ! empty( $f['owned'] ) ? 'In your bag' : ''; ?></span>
					</<?php echo $tag; ?>>
				</li>
				<?php endforeach; ?>
			</ol>
			<?php // the gesture line under the steps was removed (owner, 3 Nov 2026); "How to use" further down carries it ?>
		</section>
		<?php endif; ?>
		<?php if ( 'g' === ( $args['look'] ?? '' ) && function_exists( 'cos_child_ritual_bag_script' ) ) echo cos_child_ritual_bag_script(); // phpcs:ignore -- JSON of product ids; replaced by the add-to-cart fragment ?>


		<?php if ( 'ab' === ( $args['look'] ?? '' ) ) :
			get_template_part( 'template-parts/hx/product/purchase-ab', null, $args );
		elseif ( ! empty( $pd['benefits'] ) && ! $is_set && 'g' !== ( $args['look'] ?? '' ) ) : // owner, 25 Oct 2026: none on the Micellar Water page ?>
		<ul class="hxpd-benefits" aria-label="Benefits">
			<?php foreach ( array_slice( $pd['benefits'], 0, 3 ) as $b ) : ?><li><?php echo esc_html( $b ); ?></li><?php endforeach; ?>
		</ul>
		<?php endif; ?>

		<?php if ( ! in_array( $args['look'] ?? '', array( 'ab', 'g' ), true ) ) : // the Micellar Water page has no accordions (owner, 25 Oct 2026); its ingredient list sits under Key ingredients ?>
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
		<?php endif; ?>
		</div>
	</div>
</div>
