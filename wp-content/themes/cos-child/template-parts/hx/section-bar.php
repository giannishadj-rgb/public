<?php
/**
 * Section bar (hx-bar) — the one sub-navigation used on every page that has one (31 Oct 2026, owner:
 * "the same on all the pages", left-aligned, sticky). Sits under the header and sticks there:
 * The Ritual, Swiss science hub + chapters, Swiss heritage hub, Shop, product pages.
 * Styles: the "Section bar" block in assets/css/hx/system.css. Script: assets/js/hx-section-bar.js
 * marks the part in view (aria-current="location"); links to other pages carry aria-current="page".
 * Without JS it is a plain row of links.
 *
 * $args:
 *   label  string  the nav's accessible name
 *   items  array   each array( 'href' => '#id' | URL, 'text' => '…', 'current' => bool (other-page links),
 *                  'cta' => optional data-cro-cta tracking name,
 *                  'also' => optional array of further section ids this chapter covers, so the link
 *                  stays current while they are in view )
 *   aside  bool    optional right-hand status line (The Ritual: "Step 2 of 4 · Treat"), filled by the script
 *   fit    bool    a short index (product pages, 1 Nov 2026): on phones the links spread across the full
 *                  width instead of scrolling
 */
defined( 'ABSPATH' ) || exit;
$items = $args['items'] ?? array();
if ( ! $items ) return;
?>
<nav class="hx-bar<?php echo ! empty( $args['fit'] ) ? ' hx-bar--fit' : ''; ?>" aria-label="<?php echo esc_attr( $args['label'] ?? 'On this page' ); ?>">
	<div class="hx-wrap hx-bar__in">
		<ol class="hx-bar__list" role="list">
			<?php foreach ( $items as $it ) : $local = ( '#' === substr( $it['href'], 0, 1 ) ); ?>
			<li><a class="hx-bar__link" href="<?php echo $local ? esc_attr( $it['href'] ) : esc_url( $it['href'] ); ?>"<?php echo $local ? ' data-hx-bar-part="' . esc_attr( substr( $it['href'], 1 ) ) . '"' : ''; ?><?php echo $local && ! empty( $it['also'] ) ? ' data-hx-bar-also="' . esc_attr( implode( ' ', $it['also'] ) ) . '"' : ''; ?><?php echo ! empty( $it['current'] ) ? ' aria-current="page"' : ''; ?><?php echo ! empty( $it['cta'] ) ? ' data-cro-cta="' . esc_attr( $it['cta'] ) . '"' : ''; ?>><?php echo esc_html( $it['text'] ); ?></a></li>
			<?php endforeach; ?>
		</ol>
		<?php if ( ! empty( $args['aside'] ) ) : ?><p class="hx-bar__aside" aria-hidden="true" hidden></p><?php endif; ?>
	</div>
</nav>
