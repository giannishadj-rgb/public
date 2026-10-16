<?php
/**
 * Science chapters — sub-navigation under the header (chapters only; the hub has its own index).
 * ≥1024px: "Swiss science" + the five chapters, current one marked.
 * Phones/tablets: "Swiss science" back link + "Chapter 0X of 05" (the full index sits at the
 * end of every chapter, in next.php).
 */
defined( 'ABSPATH' ) || exit;

$chapters = hxsc_chapters();
$current  = $args['path'] ?? '';
$num      = $args['chapter'] ?? '';
?>
<nav class="hxsc-sub" aria-label="Swiss science">
	<div class="hxsc-sub__in hx-wrap">
		<a class="hxsc-sub__home" href="<?php echo hxsc_url( '/science/' ); ?>"><span class="hxsc-sub__back" aria-hidden="true"></span>Swiss science</a>
		<ol class="hxsc-sub__list" role="list">
			<?php foreach ( $chapters as $c ) : $is = ( $c['path'] === $current ); ?>
			<li><a href="<?php echo hxsc_url( $c['path'] ); ?>"<?php echo $is ? ' aria-current="page"' : ''; ?>><span class="hxsc-sub__n" aria-hidden="true"><?php echo esc_html( $c['num'] ); ?></span><?php echo esc_html( $c['title'] ); ?></a></li>
			<?php endforeach; ?>
		</ol>
		<p class="hxsc-sub__pos" aria-hidden="true">Chapter <?php echo esc_html( $num ); ?> <span>of 05</span></p>
	</div>
</nav>
