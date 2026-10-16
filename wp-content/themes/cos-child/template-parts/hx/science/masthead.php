<?php
/**
 * Shared masthead for the science hub and chapters.
 *
 * $args: label (string), title (h1, may contain <br> / <span>), lead (string, may contain inline
 * markup), plate (optional: base, widths, ratio, alt, tall => [base, widths, ratio], caption, pos).
 * Above the fold, so motion is the CSS-only load set (no scroll reveal).
 */
defined( 'ABSPATH' ) || exit;

$label = $args['label'] ?? '';
$plate = $args['plate'] ?? null;
?>
<header class="hxsc-mast<?php echo $plate ? ' hxsc-mast--plate' : ''; ?>">
	<div class="hxsc-mast__in hx-wrap">
		<?php if ( $label ) : ?><p class="hx-label hxsc-mast__label hx-load-fade"><?php echo hxsc_kses( $label ); ?></p><?php endif; ?>
		<h1 class="hx-display hxsc-mast__title hx-load-rise" style="--hx-i:1"><?php echo hxsc_kses( $args['title'] ); ?></h1>
		<?php if ( ! empty( $args['lead'] ) ) : ?>
		<p class="hx-lead hxsc-mast__lead hx-load-rise" style="--hx-i:2"><?php echo hxsc_kses( $args['lead'] ); ?></p>
		<?php endif; ?>
	</div>
	<?php if ( $plate ) : ?>
	<figure class="hxsc-mast__plate hx-wrap hx-load-fade" style="--hx-i:3">
		<div class="hxsc-mast__frame hx-frame"<?php echo isset( $plate['pos'] ) ? ' style="--hxsc-pos:' . esc_attr( $plate['pos'] ) . '"' : ''; ?>>
			<?php
			$opts = array( 'sizes' => '(min-width: 1600px) 1440px, 92vw', 'eager' => true, 'w' => 1440 );
			if ( isset( $plate['tall'] ) ) {
				// phones: the portrait crop of the same photograph
				$opts['art'] = array( array( 'media' => '(max-width: 699.98px)', 'base' => $plate['tall'][0], 'widths' => $plate['tall'][1], 'sizes' => '100vw' ) );
			}
			echo cos_h26_picture( $plate['base'], $plate['widths'], $plate['ratio'], $plate['alt'], $opts ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			?>
		</div>
		<?php if ( ! empty( $plate['caption'] ) ) : ?><figcaption class="hx-detail hxsc-mast__caption"><?php echo hxsc_kses( $plate['caption'] ); ?></figcaption><?php endif; ?>
	</figure>
	<?php endif; ?>
</header>
