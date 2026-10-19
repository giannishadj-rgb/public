<?php
/**
 * Product gallery (hxpd). The owner's photography, never altered.
 * ≥1024px: an editorial stack beside the sticky purchase column — the packshot large,
 * the photographs two by two beneath it. Phones/tablets: one swipeable row (CSS scroll
 * snap, native momentum on iOS) with a hairline progress bar and a quiet counter.
 * Every image is in the DOM as a list item, so screen readers and keyboards get all of
 * them without a carousel widget.
 */
defined( 'ABSPATH' ) || exit;
$slides = $args['slides'];
$n      = count( $slides );
if ( ! $n ) return;
?>
<div class="hxpd-gallery<?php echo 'COS-004' === $args['sku'] ? ' hxpd-gallery--cream' : ''; ?>" data-hxpd-gallery>
	<ul class="hxpd-gallery__track<?php echo 0 === ( $n - 1 ) % 2 ? '' : ' is-odd'; ?>" tabindex="0" aria-label="<?php echo esc_attr( $args['name'] ); ?>, product images">
		<?php foreach ( $slides as $i => $html ) : ?>
		<li class="hxpd-gallery__item<?php echo 0 === $i ? ' hxpd-gallery__item--lead' : ''; ?>">
			<figure class="hxpd-gallery__frame"><?php echo $html; // phpcs:ignore -- escaped in the picture helpers ?></figure>
		</li>
		<?php endforeach; ?>
	</ul>
	<?php if ( $n > 1 ) : ?>
	<div class="hxpd-gallery__meter" aria-hidden="true">
		<span class="hxpd-gallery__bar"><span class="hxpd-gallery__fill" data-hxpd-n="<?php echo (int) $n; ?>"></span></span>
		<span class="hxpd-gallery__count"><span data-hxpd-index>1</span> / <?php echo (int) $n; ?></span>
	</div>
	<?php endif; ?>
</div>
