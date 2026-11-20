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
 *
 * 6 Nov 2026 (owner: more eye-catching and elegant, in the site's look): new photographs in one silver
 * palette with the lineup hero, built by design-sources/home-2026/build_hxv_story_2026-11-06.py:
 *   hxv-zug-tall     Zug old-town lakefront + Rigi (Unsplash, Louis Droege), silver monochrome
 *   hxv-caviar-tall  black caviar macro (brand stock, Dropbox Assets/Stock Images/Caviar)
 *   hxv-ritual-tall  owner June 2026 shoot CAV59851, the four formulas on glass blocks
 * The old images above stay on disk (hxhe-valley-warm-tall is still used on /heritage/).
 *
 * 4 Nov 2026 (distill, owner): the outlined DISCOVER (a third link to /heritage/) was removed, so the
 * section links there once, through "Our history". Each panel line is now one fact, not a tagline:
 *   Siberian Caviar  "From Siberian sturgeon, farmed and traceable"  (PRODUCT.md confirmed facts)
 *   The Ritual       "Four steps, morning and evening"               (replaces a repeat of the hero line)
 * "More than twenty years in Zug" was already a fact and stays.
 */
defined( 'ABSPATH' ) || exit;

$chapters = array(
	array(
		'title'  => 'Our history',
		'italic' => false,
		'line'   => 'More than twenty years in Zug',
		'url'    => '/heritage/',
		'pos'    => '50% 62%', // phones (4:3): keep the old-town houses and the Rigi in frame
		'img'    => array( 'hxv-zug-tall', array( 640, 960, 1280, 1600 ), array( 4, 5 ), 'The old town of Zug on the lakefront, the snow-capped Rigi beyond, in black and white' ),
	),
	array(
		'title'  => 'Siberian Caviar',
		'italic' => true,
		'line'   => 'From Siberian sturgeon, farmed and traceable',
		'url'    => '/science/why-caviar/',
		'img'    => array( 'hxv-caviar-tall', array( 640, 960, 1280, 1600 ), array( 4, 5 ), 'A close view of glossy black caviar, each grain catching the light' ),
	),
	array(
		'title'  => 'The Ritual',
		'italic' => true,
		'line'   => 'Four steps, morning and evening',
		'url'    => '/the-ritual/',
		'pos'    => '50% 92%', // phones (4:3): keep the jar and eye cream whole on the glass blocks
		'img'    => array( 'hxv-ritual-tall', array( 640, 960, 1280, 1600 ), array( 4, 5 ), 'The four Caviar of Switzerland formulas arranged on clear glass blocks in soft grey light' ),
	),
);

?>
<section id="caviar" class="hxv" aria-labelledby="hxv-title">
	<div class="hx-wrap">
		<header class="hxv__head">
			<h2 id="hxv-title" class="hx-heading hxv__title" data-hx-reveal="light">Our story</h2>
			<p class="hxv__story" data-hx-reveal data-hx-delay="120">It begins in Zug, with&nbsp;caviar.</p><?php /* 4 Nov 2026 (clarify): was "Our story begins…", repeating the title */ ?>
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
