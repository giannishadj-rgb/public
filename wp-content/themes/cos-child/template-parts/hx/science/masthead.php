<?php
/**
 * Shared opening plate for the science hub and chapters (round 3, 20 Oct: the homepage's world).
 *
 * $args: title (h1), lead, plate = [ base, widths, ratio, alt, tall?=[base,widths,ratio], pos?, caption? ],
 *        mode = 'overlay' (title and lead on one band, then the photograph full-bleed beneath)
 *             | 'split'   (≥900px: type on the left, the photograph bleeding off the right edge)
 *             | 'bleed'   (hub, 24 Oct 2026, after Sensai / Augustinus Bader / Lyma: the
 *                          photograph fills the first screen and the type sits on its open
 *                          lower left; landscape ≥700px uses the wide plate, phones and portrait
 *                          tablets the tall crop dissolving into its own ground, type beneath).
 * In 'overlay' and 'split' type comes first and never sits on the photograph. 'label' is accepted but no longer
 * printed: no eyebrow above the h1; the chapter sub-navigation carries the position.
 * Above the fold, so motion is the CSS-only load set.
 */
defined( 'ABSPATH' ) || exit;

$plate = $args['plate'];
$mode  = $args['mode'] ?? 'overlay';
$video = $args['video'] ?? null;
$dark  = ! empty( $args['dark'] ); // a video on its own near-black ground, no photograph (25 Oct 2026)
$opts  = array( 'sizes' => 'split' === $mode ? '(min-width: 900px) 50vw, 100vw' : '100vw', 'eager' => true, 'w' => 1440, 'class' => 'hxsc-plate__pic' );
if ( 'bleed' === $mode ) {
	$opts['w'] = 1440;
}
if ( isset( $plate['tall'] ) ) {
	$opts['art'] = array( array( 'media' => 'bleed' === $mode ? 'not all and (min-width: 700px) and (orientation: landscape)' : '(max-width: 899.98px)', 'base' => $plate['tall'][0], 'widths' => $plate['tall'][1], 'sizes' => '100vw' ) );
}
?>
<header class="hxsc-plate hxsc-plate--<?php echo esc_attr( $mode ); ?><?php echo $video ? ' hxsc-plate--video' : ''; ?><?php echo $dark ? ' hxsc-plate--dark' : ''; ?>"<?php
	$style = ( isset( $plate['pos'] ) ? '--hxsc-pos:' . $plate['pos'] . ';' : '' ) . ( isset( $plate['pos_wide'] ) ? '--hxsc-pos-wide:' . $plate['pos_wide'] . ';' : '' ); // pos_wide: the landscape bleed crop
	echo $style ? ' style="' . esc_attr( $style ) . '"' : '';
?>>
	<figure class="hxsc-plate__media hx-load-fade">
		<?php if ( ! $dark ) : // owner, 25 Oct 2026: no photograph before the video; it opens on its dark ground
			echo cos_h26_picture( $plate['base'], $plate['widths'], $plate['ratio'], $plate['alt'], $opts ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		endif; ?>
		<?php if ( $video ) : ?>
		<video id="hxsc-hero-video" class="hxsc-plate__video" width="1920" height="1080" muted loop playsinline preload="metadata" aria-hidden="true" tabindex="-1">
			<source src="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/video/' . $video['mobile'] ); ?>" type="video/mp4" media="(max-width: 699px)">
			<source src="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/video/' . $video['desktop'] ); ?>" type="video/mp4">
		</video>
		<?php endif; ?>
		<?php if ( ! empty( $plate['caption'] ) ) : ?><figcaption class="hxsc-plate__caption"><?php echo hxsc_kses( $plate['caption'] ); ?></figcaption><?php endif; ?>
	</figure>
	<?php if ( $video ) : ?><button class="hxsc-plate__control" type="button" aria-controls="hxsc-hero-video" aria-label="Play hero video"><span class="hxsc-plate__control-label">Play</span></button><?php endif; ?>
	<div class="hxsc-plate__text hx-wrap">
		<div class="hxsc-plate__copy">
			<h1 class="hx-display hxsc-plate__title hx-load-rise" style="--hx-i:1"><?php echo hxsc_kses( $args['title'] ); ?></h1>
			<?php if ( ! empty( $args['lead'] ) ) : ?><p class="hx-lead hxsc-plate__lead hx-load-rise" style="--hx-i:2"><?php echo hxsc_kses( $args['lead'] ); ?></p><?php endif; ?>
		</div>
	</div>
</header>
