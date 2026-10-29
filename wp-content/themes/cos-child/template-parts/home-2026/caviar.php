<?php
/**
 * 03 — The caviar story (styles: assets/css/hx/caviar.css, prefix hxv-).
 *
 * 19 Oct 2026 (owner: "make the caviar section more like Sensai's Our Story"; then "more like
 * Our story, pearls have nothing to do with it"): the house's own story in Sensai's register.
 * A centred head (OUR STORY, one short story line, an outlined DISCOVER to /heritage/), then a
 * triptych of photographs, each a link to the page that tells that chapter in full:
 *   OUR HISTORY       More than twenty years in Zug     → /heritage/
 *   Siberian Caviar   The heart of every formula        → /science/why-caviar/
 *   The Ritual        Four formulas, one daily ritual   → /the-ritual/
 * Capitals for the first chapter, italic for the two named ones (as Sensai sets "Koishimaru
 * Silk" and "Saho"). Site colours (warm ivory ground, graphite type), not Sensai's black.
 *
 * Every statement is already published on the site:
 *   Zug, 20+ years — heritage figures band ("20+ years · Making skincare in Zug"); PRODUCT.md
 *   caviar         — Siberian sturgeon (owner, 5 Oct 2026), in every formula (/science/why-caviar/)
 *   ritual         — four formulas used morning and evening (PRODUCT.md, /the-ritual/)
 * No result claim here: results live in the next section (measured.php, 20 Oct 2026; a
 * short-lived results rail inside this section was removed the same day).
 *
 * Images (all 4:5 panels, object-fit cover):
 *   hxhe-valley-warm-tall  alpine valley in warm monochrome (also on /heritage/)
 *   wc-cav-heap-tall       owner caviar macro (wc-cav.origin.json), 1600×2000
 *   wc-close-print         November 2022 shoot of the four current packs, crop + resize only
 *                          (wc-close-print.origin.json)
 * Previous version (caviar plate + facts): git history.
 */
defined( 'ABSPATH' ) || exit;

$chapters = array(
	array(
		'title'  => 'Our history',
		'italic' => false,
		'line'   => 'More than twenty years in Zug',
		'url'    => '/heritage/',
		'img'    => array( 'hxhe-valley-warm-tall', array( 640, 960, 1280 ), array( 4, 5 ), 'A Swiss alpine valley beneath snow-covered peaks, in warm monochrome' ),
	),
	array(
		'title'  => 'Siberian Caviar',
		'italic' => true,
		'line'   => 'The heart of every formula',
		'url'    => '/science/why-caviar/',
		'img'    => array( 'wc-cav-heap-tall', array( 640, 960, 1290, 1600 ), array( 4, 5 ), 'Glossy black caviar heaped on a pale, reflective surface' ),
	),
	array(
		'title'  => 'The Ritual',
		'italic' => true,
		'line'   => 'Four formulas, one daily ritual',
		'url'    => '/the-ritual/',
		'pos'    => '50% 88%', // the packs stand low in the frame: keep the jar whole in the 4:3 phone crop
		'img'    => array( 'wc-close-print', array( 480, 720, 960, 1200 ), array( 4, 5 ), 'The four Caviar of Switzerland formulas on a stone counter before reeded glass' ),
	),
);

?>
<section id="caviar" class="hxv" aria-labelledby="hxv-title">
	<div class="hx-wrap">
		<header class="hxv__head">
			<h2 id="hxv-title" class="hx-heading hxv__title" data-hx-reveal="light">Our story</h2>
			<p class="hxv__story" data-hx-reveal data-hx-delay="120">Our story begins in Zug, with&nbsp;caviar.</p>
			<p class="hxv__act" data-hx-reveal data-hx-delay="240"><a class="hxv__cta" href="<?php echo esc_url( home_url( '/heritage/' ) ); ?>" data-cro-cta="home-caviar-discover">Discover<span class="hx-sr"> our story</span></a></p>
		</header>

		<ul class="hxv__chapters" role="list" data-hx-stagger>
			<?php foreach ( $chapters as $c ) : [ $base, $widths, $ratio, $alt ] = $c['img']; ?>
			<li class="hxv__chapter" data-hx-reveal="mask">
				<a class="hxv__link" href="<?php echo esc_url( home_url( $c['url'] ) ); ?>">
					<span class="hxv__frame"<?php echo ! empty( $c['pos'] ) ? ' style="--hxv-pos:' . esc_attr( $c['pos'] ) . '"' : ''; ?>>
						<?php echo cos_h26_picture( $base, $widths, $ratio, $alt, array( 'class' => 'hxv__img', 'sizes' => '(min-width: 900px) 31vw, 100vw', 'w' => $widths[1] ) ); ?>
					</span>
					<h3 class="hxv__name<?php echo $c['italic'] ? ' hxv__name--italic' : ''; ?>"><?php echo $c['italic'] ? '<em>' . esc_html( $c['title'] ) . '</em>' : esc_html( $c['title'] ); ?></h3>
					<span class="hxv__line"><?php echo esc_html( $c['line'] ); ?></span>
				</a>
			</li>
			<?php endforeach; ?>
		</ul>

	</div>
</section>
