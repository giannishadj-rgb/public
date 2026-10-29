<?php
/**
 * End of every chapter: the next chapter as one large link (round 2: the sub-navigation bar is the
 * chapter index; no second list here).
 */
defined( 'ABSPATH' ) || exit;

$chapters = hxsc_chapters();
$current  = $args['path'] ?? '';
$next     = null;
foreach ( $chapters as $i => $c ) {
	if ( $c['path'] === $current ) $next = $chapters[ $i + 1 ] ?? null;
}
$link = $next
	? array( hxsc_url( $next['path'] ), 'Next chapter', $next['title'], $next['line'] )
	: array( hxsc_url( '/science/' ), 'Swiss science', 'Inside every formula.', 'Return to the overview.' );
?>
<nav class="hxsc-next" aria-label="Continue reading">
	<div class="hx-wrap">
		<a class="hxsc-next__link" href="<?php echo $link[0]; // phpcs:ignore -- escaped by hxsc_url ?>" data-hx-reveal>
			<span class="hx-label hxsc-next__label"><?php echo esc_html( $link[1] ); ?></span>
			<span class="hx-heading hxsc-next__title"><?php echo esc_html( $link[2] ); ?><span class="hxsc-next__arrow" aria-hidden="true"></span></span>
			<span class="hx-body hxsc-next__line"><?php echo esc_html( $link[3] ); ?></span>
		</a>
	</div>
</nav>
