<?php
/**
 * The Ritual — section bar, sticky under the header (clarify pass, 23 Oct 2026). Since 31 Oct 2026 it is
 * the sitewide section bar (template-parts/hx/section-bar.php); the aside names the step in view inside
 * "Step by step" ("Step 2 of 4 · Treat", from each step's data-hx-bar-label).
 */
defined( 'ABSPATH' ) || exit;
$items = array();
foreach ( $args['parts'] ?? array() as $p ) {
	$items[] = array( 'href' => '#' . $p['id'], 'text' => $p['short'] );
}
get_template_part( 'template-parts/hx/section-bar', null, array( 'label' => 'The Ritual, on this page', 'items' => $items, 'aside' => true ) );
