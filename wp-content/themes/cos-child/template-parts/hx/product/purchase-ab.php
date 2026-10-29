<?php
/**
 * Purchase column, Augustinus Bader-style (owner, 25 Oct 2026: the Revitalizing Eye Cream only).
 * After Add to bag: the AB accordion set (Key benefits · Proven results · Ideal for · Ingredients ·
 * Delivery & returns), then "Complete your routine": the other three formulas, each with an Add.
 * Included by purchase.php when $args['look'] === 'ab'. The proven result is this formula's own
 * study only (page 347); nothing implies the ritual or the cleanser were studied.
 */
defined( 'ABSPATH' ) || exit;
$pd    = $args['pd'];
$me    = $args['me'];
$study = $args['study'];
$plus  = '<span class="hxpd-acc__icon" aria-hidden="true"></span>';
?>
<div class="hxpd-acc hxpd-acc--ab">
	<?php if ( ! empty( $pd['benefits'] ) ) : ?>
	<details class="hxpd-acc__item" open>
		<summary class="hxpd-acc__sum">Key benefits<?php echo $plus; // phpcs:ignore ?></summary>
		<ul class="hxpd-benefits hxpd-benefits--acc">
			<?php foreach ( $pd['benefits'] as $b ) : ?><li><?php echo esc_html( $b ); ?></li><?php endforeach; ?>
		</ul>
	</details>
	<?php endif; ?>
	<?php if ( $study ) : ?>
	<details class="hxpd-acc__item">
		<summary class="hxpd-acc__sum">Proven results<?php echo $plus; // phpcs:ignore ?></summary>
		<div class="hxpd-acc__proof">
			<p class="hxpd-acc__fig"><?php echo esc_html( $study['sign'] . $study['n'] . $study['unit'] ); ?> <span><?php echo esc_html( strtolower( $study['finding'] ) ); ?></span></p>
			<p class="hxpd-acc__text"><?php echo esc_html( $study['kind'] ); ?> by <?php echo esc_html( strtolower( $study['method'] ) ); ?>: <?php echo esc_html( $study['panel'] ); ?>, <?php echo esc_html( $study['regimen'] ); ?>. An independent study of this formula alone.</p>
			<p><a class="hx-link" href="#hxpd-proof">See the result<span class="hx-arrow" aria-hidden="true"></span></a></p>
		</div>
	</details>
	<?php endif; ?>
	<?php if ( ! empty( $pd['concerns'] ) ) : ?>
	<details class="hxpd-acc__item">
		<summary class="hxpd-acc__sum">Ideal for<?php echo $plus; // phpcs:ignore ?></summary>
		<?php if ( ! empty( $pd['who_for'] ) ) : ?><p class="hxpd-acc__text"><?php echo esc_html( $pd['who_for'] ); ?></p><?php endif; ?>
		<ul class="hxpd-concerns"><?php foreach ( $pd['concerns'] as $c ) : ?><li><?php echo esc_html( $c ); ?></li><?php endforeach; ?></ul>
	</details>
	<?php endif; ?>
	<details class="hxpd-acc__item" id="hxpd-ingredients">
		<summary class="hxpd-acc__sum">Ingredients<?php echo $plus; // phpcs:ignore ?></summary>
		<?php if ( ! empty( $pd['actives'] ) ) : ?>
		<p class="hxpd-acc__text"><?php echo esc_html( implode( ', ', array_map( static fn( $a ) => $a[0], $pd['actives'] ) ) ); ?>.</p>
		<?php endif; ?>
		<p class="hxpd-inci"><?php echo esc_html( $pd['inci'] ?? '' ); ?></p>
		<p class="hxpd-inci__note">Refer to the packaging for the most up-to-date list.</p>
	</details>
	<details class="hxpd-acc__item">
		<summary class="hxpd-acc__sum">Delivery &amp; returns<?php echo $plus; // phpcs:ignore ?></summary>
		<p class="hxpd-acc__text"><?php echo esc_html( $args['shipping'] ); ?> Returns are accepted within 30 days. <a href="<?php echo esc_url( home_url( '/shipping-returns/' ) ); ?>">Delivery and returns in full</a></p>
	</details>
</div>

<div class="hxpd-routine" aria-labelledby="hxpd-routine-title">
	<h2 class="hxpd-routine__title" id="hxpd-routine-title">Complete your routine</h2>
	<ul class="hxpd-routine__list">
		<?php foreach ( $args['formulas'] as $f ) :
			if ( $f['current'] ) continue; ?>
		<li class="hxpd-routine__item">
			<a class="hxpd-routine__pic" href="<?php echo esc_url( $f['url'] ); ?>" tabindex="-1" aria-hidden="true"><?php echo cos_h26_picture( 'pack-' . $f['key'], array( 480 ), array( 2, 3 ), '', array( 'sizes' => '64px', 'w' => 480 ) ); // phpcs:ignore ?></a>
			<div class="hxpd-routine__body">
				<p class="hxpd-routine__step"><span class="hx-step"><?php echo esc_html( $f['num'] ); ?></span> <?php echo esc_html( $f['verb'] ); ?></p>
				<a class="hxpd-routine__name" href="<?php echo esc_url( $f['url'] ); ?>"><?php echo esc_html( $f['full'] ); ?></a>
				<p class="hxpd-routine__meta"><?php echo esc_html( $f['benefit'] ); ?></p>
				<p class="hxpd-routine__price"><?php echo esc_html( $f['price'] ); ?> <span aria-hidden="true">·</span> <?php echo esc_html( $f['size'] ); ?></p>
			</div>
			<?php if ( ! empty( $f['buy'] ) ) : ?>
			<a class="hxpd-routine__add add_to_cart_button ajax_add_to_cart" href="<?php echo esc_url( add_query_arg( 'add-to-cart', $f['pid'], get_permalink() ) ); ?>" data-product_id="<?php echo (int) $f['pid']; ?>" data-quantity="1" rel="nofollow" aria-label="<?php echo esc_attr( sprintf( 'Add %s to bag', $f['full'] ) ); ?>">Add</a>
			<?php endif; ?>
		</li>
		<?php endforeach; ?>
	</ul>
</div>
