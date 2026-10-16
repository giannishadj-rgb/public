<?php
/**
 * The chapter index. Compact (end of a chapter): one hairline row per chapter.
 * Full (hub): adds the chapter photograph and the pillar copy from page 344 ($args['items']).
 */
defined( 'ABSPATH' ) || exit;

$current = $args['current'] ?? '';
$compact = ! empty( $args['compact'] );
$items   = $args['items'] ?? hxsc_chapters();
?>
<ol class="hxsc-index<?php echo $compact ? ' hxsc-index--compact' : ''; ?>" role="list" data-hx-stagger>
	<?php foreach ( $items as $c ) : $is = ( $c['path'] === $current ); ?>
	<li class="hxsc-index__item" data-hx-reveal>
		<a class="hxsc-index__link" href="<?php echo hxsc_url( $c['path'] ); ?>"<?php echo $is ? ' aria-current="page"' : ''; ?>>
			<span class="hxsc-index__n" aria-hidden="true"><?php echo esc_html( $c['num'] ); ?></span>
			<span class="hxsc-index__title"><?php echo esc_html( $c['title'] ); ?></span>
			<span class="hxsc-index__line"><?php echo esc_html( $c['line'] ); ?></span>
			<?php if ( ! $compact && ! empty( $c['img'] ) ) : ?>
			<span class="hxsc-index__img hx-frame" aria-hidden="true"><?php echo cos_h26_picture( $c['img'][0], $c['img'][1], $c['img'][2], '', array( 'sizes' => '(min-width: 900px) 220px, 96px', 'w' => 480 ) ); // phpcs:ignore ?></span>
			<?php endif; ?>
			<span class="hxsc-index__go" aria-hidden="true"><?php echo $is ? '' : '<span class="hx-arrow"></span>'; ?></span>
		</a>
	</li>
	<?php endforeach; ?>
</ol>
