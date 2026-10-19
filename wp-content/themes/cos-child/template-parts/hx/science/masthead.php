<?php
/**
 * Shared opening plate for the science hub and chapters (round 2: one signature image moment per page).
 *
 * $args: label, title (h1), lead, plate = [ base, widths, ratio, alt, tall?=[base,widths,ratio], pos?, caption? ],
 *        mode = 'overlay' (full-bleed photograph, type set on its light field, ≥900px)
 *             | 'split'   (photograph bleeds to the right edge, type against it on the left).
 * Phones (<900px): type first, then the photograph full-bleed beneath it (never type on a busy crop).
 * Above the fold, so motion is the CSS-only load set.
 */
defined( 'ABSPATH' ) || exit;

$plate = $args['plate'];
$mode  = $args['mode'] ?? 'overlay';
$opts  = array( 'sizes' => 'split' === $mode ? '(min-width: 900px) 50vw, 100vw' : '100vw', 'eager' => true, 'w' => 1440, 'class' => 'hxsc-plate__pic' );
if ( isset( $plate['tall'] ) ) {
	$opts['art'] = array( array( 'media' => '(max-width: 899.98px)', 'base' => $plate['tall'][0], 'widths' => $plate['tall'][1], 'sizes' => '100vw' ) );
}
?>
<header class="hxsc-plate hxsc-plate--<?php echo esc_attr( $mode ); ?>"<?php echo isset( $plate['pos'] ) ? ' style="--hxsc-pos:' . esc_attr( $plate['pos'] ) . '"' : ''; ?>>
	<figure class="hxsc-plate__media hx-load-fade">
		<?php echo cos_h26_picture( $plate['base'], $plate['widths'], $plate['ratio'], $plate['alt'], $opts ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		<?php if ( ! empty( $plate['caption'] ) ) : ?><figcaption class="hxsc-plate__caption"><?php echo hxsc_kses( $plate['caption'] ); ?></figcaption><?php endif; ?>
	</figure>
	<div class="hxsc-plate__text hx-wrap">
		<div class="hxsc-plate__copy">
			<?php if ( ! empty( $args['label'] ) ) : ?><p class="hx-label hxsc-plate__label hx-load-fade"><?php echo hxsc_kses( $args['label'] ); ?></p><?php endif; ?>
			<h1 class="hx-display hxsc-plate__title hx-load-rise" style="--hx-i:1"><?php echo hxsc_kses( $args['title'] ); ?></h1>
			<?php if ( ! empty( $args['lead'] ) ) : ?><p class="hx-lead hxsc-plate__lead hx-load-rise" style="--hx-i:2"><?php echo hxsc_kses( $args['lead'] ); ?></p><?php endif; ?>
		</div>
	</div>
</header>
