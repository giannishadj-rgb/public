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
if ( 'ab' === ( $args['look'] ?? '' ) && ! empty( $args['study'] ) ) $n++; // the results card is a slide too
?>
<div class="hxpd-gallery<?php echo 'COS-004' === $args['sku'] ? ' hxpd-gallery--cream' : ''; ?>" data-hxpd-gallery>
	<ul class="hxpd-gallery__track<?php echo 0 === ( $n - 1 ) % 2 ? '' : ' is-odd'; ?>" tabindex="0" aria-label="<?php echo esc_attr( $args['name'] ); ?>, product images">
		<?php foreach ( $slides as $i => $html ) : ?>
		<li class="hxpd-gallery__item<?php echo 0 === $i ? ' hxpd-gallery__item--lead' : ''; ?>">
			<figure class="hxpd-gallery__frame"><?php echo $html; // phpcs:ignore -- escaped in the picture helpers ?></figure>
		</li>
		<?php if ( 0 === $i && 'ab' === ( $args['look'] ?? '' ) && ! empty( $args['study'] ) ) : $st = $args['study']; ?>
		<li class="hxpd-gallery__item hxpd-gallery__item--proof">
			<a class="hxpd-proofcard" href="#hxpd-proof">
				<span class="hxpd-proofcard__n"><?php echo esc_html( $st['sign'] . $st['n'] ); ?><span class="hxpd-proofcard__u"><?php echo esc_html( $st['unit'] ); ?></span></span>
				<span class="hxpd-proofcard__what"><?php echo esc_html( $st['finding'] ); ?></span>
				<span class="hxpd-proofcard__how"><?php echo esc_html( $st['kind'] ); ?>, <?php echo esc_html( strtolower( $st['method'] ) ); ?>. <?php echo esc_html( $st['panel'] ); ?>, <?php echo esc_html( $st['regimen'] ); ?>.</span>
			</a>
		</li>
		<?php endif; ?>
		<?php endforeach; ?>
	</ul>
	<?php if ( 'g' === ( $args['look'] ?? '' ) && $n > 1 ) : ?>
	<ol class="hxpd-thumbs" aria-label="Choose an image">
		<?php foreach ( $args['thumbs'] as $i => $t ) : ?>
		<li><button type="button" class="hxpd-thumbs__btn<?php echo 0 === $i ? ' is-on' : ''; ?>" data-hxpd-thumb="<?php echo (int) $i; ?>" aria-label="Image <?php echo (int) ( $i + 1 ); ?> of <?php echo (int) $n; ?>"<?php echo 0 === $i ? ' aria-current="true"' : ''; ?>><?php echo $t; // phpcs:ignore -- escaped in the picture helpers ?></button></li>
		<?php endforeach; ?>
	</ol>
	<button type="button" class="hxpd-gallery__nav hxpd-gallery__nav--prev" data-hxpd-step-img="-1" aria-label="Previous image"><span aria-hidden="true"></span></button>
	<button type="button" class="hxpd-gallery__nav hxpd-gallery__nav--next" data-hxpd-step-img="1" aria-label="Next image"><span aria-hidden="true"></span></button>
	<?php endif; ?>
	<?php if ( $n > 1 ) : ?>
	<div class="hxpd-gallery__meter" aria-hidden="true">
		<span class="hxpd-gallery__bar"><span class="hxpd-gallery__fill" data-hxpd-n="<?php echo (int) $n; ?>"></span></span>
		<span class="hxpd-gallery__count"><span data-hxpd-index>1</span> / <?php echo (int) $n; ?></span>
	</div>
	<?php endif; ?>
</div>
