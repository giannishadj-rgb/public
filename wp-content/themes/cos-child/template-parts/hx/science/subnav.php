<?php
/**
 * Science chapters — sub-navigation under the header (chapters only; the hub has its own section bar).
 * Since 31 Oct 2026 it is the sitewide sticky section bar (template-parts/hx/section-bar.php): the
 * overview + the five chapters, current one marked; on phones the row scrolls sideways with the
 * current chapter kept in view. The full index also sits at the end of every chapter (next.php).
 */
defined( 'ABSPATH' ) || exit;

$current = $args['path'] ?? '';
$items   = array( array( 'href' => hxsc_url( '/science/' ), 'text' => 'Overview' ) );
foreach ( hxsc_chapters() as $c ) {
	$items[] = array( 'href' => hxsc_url( $c['path'] ), 'text' => $c['title'], 'current' => $c['path'] === $current );
}
get_template_part( 'template-parts/hx/section-bar', null, array( 'label' => 'Swiss science', 'items' => $items ) );
