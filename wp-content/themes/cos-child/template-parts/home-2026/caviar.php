<?php
/**
 * 03 — Caviar, the signature ingredient (14 Oct 2026 rebuild, round-1 fixes 15 Oct; styles:
 * assets/css/hx/caviar.css, prefix hxv-). One photograph carries the section: the owner's caviar
 * macro at about one screen tall, the upright heading set on its own light field. Beneath it, on
 * the same warm ivory, one statement, two short facts in a row and the link. Brief: do not
 * over-explain, the imagery carries the luxury. (Round 1 removed the two inset details: one
 * repeated the macro, the other was below its resolution.)
 *
 * Every statement is already published on the site:
 *   species       — Siberian sturgeon (Acipenser baerii), confirmed by the owner 5 Oct 2026
 *   composition   — page 346: phospholipids, amino acids, vitamins, trace minerals (orchestrator-checked)
 *   sourcing      — /science/why-caviar/ ("Sustainably farmed. Fully traceable.", no wild harvest)
 *   formulation   — /science/why-caviar/ + serum/eye-cream PDPs (micro-encapsulated: potency
 *                   protected, released over time)
 * No result claim here: results live in the science section (trust.php), attached to products.
 *
 * Images: design-sources/home-2026/build_hxv_caviar.py (owner photograph, never upscaled).
 *   hxv-field       2:1, the 3000px macro at native scale with its light field continued left
 *   hxv-field-tall  4:5 phone crop, the heap centred and low, clean field above
 * Previous version: design-sources/home-2026/snapshot-2026-10-14/theme/template-parts/home-2026/caviar.php
 */
defined( 'ABSPATH' ) || exit;
?>
<section id="caviar" class="hxv" aria-labelledby="hxv-title">
	<div class="hxv__plate">
		<header class="hxv__head">
			<p class="hx-label hxv__label" data-hx-reveal="fade">The signature ingredient</p>
			<h2 id="hxv-title" class="hx-heading hxv__title" data-hx-reveal>Caviar in every formula</h2>
			<p class="hxv__species" data-hx-reveal style="--hx-i:1">Siberian sturgeon, <em lang="la">Acipenser baerii</em></p>
		</header>
		<div class="hxv__photo hx-frame" data-hx-reveal="settle">
			<?php
			echo cos_h26_picture(
				'hxv-field',
				array( 1440, 2048, 2880, 4000 ),
				array( 2, 1 ),
				'Glossy black caviar pearls heaped on a pale, reflective surface, a few rolling loose into the light',
				array(
					'sizes' => '(min-width: 1000px) 116vw, 100vw',
					'w'     => 2048,
					'art'   => array(
						array(
							'media'  => '(max-width: 699.98px)',
							'base'   => 'hxv-field-tall',
							'widths' => array( 640, 960, 1280, 1600 ),
							'sizes'  => '100vw',
						),
					),
				)
			);
			?>
		</div>
	</div>

	<div class="hxv__notes" data-hx-stagger>
		<p class="hxv__statement" data-hx-reveal>Rich in the lipids, amino acids and minerals skin relies&nbsp;on.</p>
		<dl class="hxv__facts">
			<div class="hxv__fact" data-hx-reveal>
				<dt class="hxv__k">Origin</dt>
				<dd class="hxv__v">Sustainably farmed, fully traceable, never wild&#8209;harvested.</dd>
			</div>
			<div class="hxv__fact" data-hx-reveal>
				<dt class="hxv__k">Formulation</dt>
				<dd class="hxv__v">Micro&#8209;encapsulated, so the extract keeps its potency and is released over time.</dd>
			</div>
		</dl>
		<p class="hxv__more" data-hx-reveal><a class="hx-link" href="<?php echo esc_url( home_url( '/science/why-caviar/' ) ); ?>">Why caviar<span class="hx-arrow" aria-hidden="true"></span></a></p>
	</div>
</section>
