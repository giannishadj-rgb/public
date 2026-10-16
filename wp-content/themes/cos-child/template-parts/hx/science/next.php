<?php
/**
 * End of every chapter: the next chapter as one large link, then the full chapter index
 * (the phones' navigation between chapters lives here).
 */
defined( 'ABSPATH' ) || exit;

$chapters = hxsc_chapters();
$current  = $args['path'] ?? '';
$next     = null;
foreach ( $chapters as $i => $c ) {
	if ( $c['path'] === $current ) $next = $chapters[ $i + 1 ] ?? null;
}
?>
<nav class="hxsc-next" aria-label="Continue reading">
	<div class="hx-wrap">
		<?php if ( $next ) : ?>
		<a class="hxsc-next__link" href="<?php echo hxsc_url( $next['path'] ); ?>" data-hx-reveal>
			<span class="hx-label hxsc-next__label">Next chapter <span class="hxsc-next__n"><?php echo esc_html( $next['num'] ); ?></span></span>
			<span class="hx-heading hxsc-next__title"><?php echo esc_html( $next['title'] ); ?><span class="hxsc-next__arrow" aria-hidden="true"></span></span>
			<span class="hx-body hxsc-next__line"><?php echo esc_html( $next['line'] ); ?></span>
		</a>
		<?php else : ?>
		<a class="hxsc-next__link" href="<?php echo hxsc_url( '/science/' ); ?>" data-hx-reveal>
			<span class="hx-label hxsc-next__label">Swiss science</span>
			<span class="hx-heading hxsc-next__title">Protect. Repair. Rejuvenate.<span class="hxsc-next__arrow" aria-hidden="true"></span></span>
			<span class="hx-body hxsc-next__line">Return to the overview of every chapter.</span>
		</a>
		<?php endif; ?>
		<?php get_template_part( 'template-parts/hx/science/index', null, array( 'current' => $current, 'compact' => true ) ); ?>
	</div>
</nav>
