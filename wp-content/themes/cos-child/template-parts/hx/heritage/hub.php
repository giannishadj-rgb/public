<?php
/**
 * /heritage/ (page 61) — "Precision born from the Alps." Styles: assets/css/hx/heritage.css (hxhe-).
 *
 * Copy carried over from the page's post_content (16 Oct 2026 snapshot), facts unchanged; wording
 * tightened only where the facts stay identical. Photography: the homepage's approved Matterhorn
 * summit plate (its one reuse; captioned with the place so it is never read as Zug), owner product
 * photography (never the unverified philosophy-lab image), and the Alpine valley from the media library (graded, uncaptioned: atmosphere only).
 */
defined( 'ABSPATH' ) || exit;

$a = cos_hxhe_arrow();
?>
<section class="hxhe-hero hxhe-hero--plate" aria-labelledby="hxhe-title">
	<div class="hx-wrap hxhe-hero__text">
		<p class="hx-label hxhe-hero__label"><span class="hxhe-nw">Swiss heritage <span aria-hidden="true">·</span></span> <span class="hxhe-nw">Canton of Zug</span></p>
		<h1 id="hxhe-title" class="hx-display hxhe-hero__title hx-load-rise" style="--hx-i:1">Precision born <em>from the Alps.</em></h1>
		<div class="hxhe-hero__aside hx-load-rise" style="--hx-i:2">
			<p class="hx-lead hxhe-hero__lede">Switzerland is not a backdrop. It is a methodology: four centuries of watchmaking mastery, pharmaceutical leadership and Alpine purity, distilled into every formula we create.</p>
			<p class="hxhe-links">
				<a class="hx-link" href="<?php echo esc_url( home_url( '/shop/' ) ); ?>">Experience the collection<?php echo $a; // phpcs:ignore ?></a>
				<a class="hx-link" href="<?php echo esc_url( home_url( '/science/' ) ); ?>">Explore the science<?php echo $a; // phpcs:ignore ?></a>
			</p>
		</div>
	</div>
	<figure class="hxhe-hero__plate hx-frame hx-load-fade" style="--hx-i:3">
		<?php
		echo cos_h26_picture(
			'provenance-summit-tall',
			array( 640, 960 ),
			array( 1100, 1540 ),
			'The north and east faces of the Matterhorn, cloud streaming from the summit, printed in silver tones',
			array(
				'sizes' => '100vw',
				'w'     => 960,
				'eager' => true,
				'art'   => array(
					array( 'media' => '(min-width: 760px)', 'base' => 'provenance-summit-wide', 'widths' => array( 1440, 1920 ), 'sizes' => '100vw' ),
				),
			)
		);
		?>
		<figcaption class="hxhe-caption">Matterhorn, Valais <span aria-hidden="true">·</span> 4&#8239;478&nbsp;m</figcaption>
	</figure>
</section>

<section class="hxhe-sec hxhe-guarantee" aria-labelledby="hxhe-guarantee-title">
	<div class="hx-wrap hxhe-split hxhe-split--image-left">
		<figure class="hxhe-split__media hx-frame" data-hx-reveal="mask">
			<?php echo cos_h26_picture( 'hxhe-hands', array( 640, 960, 1280 ), array( 4, 5 ), 'Two hands presenting the Micellar Water All-in-One Cleanser against a white ground', array( 'sizes' => '(min-width: 900px) 42vw, 100vw', 'w' => 960 ) ); // phpcs:ignore ?>
		</figure>
		<div class="hxhe-split__text" data-hx-stagger>
			<p class="hx-label" data-hx-reveal="fade">The Swiss guarantee</p>
			<h2 id="hxhe-guarantee-title" class="hx-heading hxhe-h2" data-hx-reveal>Where one standard rules all others.</h2>
			<div class="hxhe-prose" data-hx-reveal>
				<p>In Switzerland, precision is not a marketing claim. It is a cultural inheritance, threaded through four centuries of watchmaking mastery, pharmaceutical innovation and the enduring discipline of life in the Alps.</p>
				<p>When we chose to formulate in Switzerland, we were not choosing a label. We were choosing to be held accountable to the highest standard of manufacturing excellence on earth. Every batch tested. Every ingredient traced. Every formula validated before it reaches your skin.</p>
				<p>Switzerland’s life sciences sector operates under GMP regulations that exceed EU pharmaceutical standards. Our laboratory in the Canton of Zug is governed by the same regulatory framework as the global pharmaceutical industry headquartered here.</p>
			</div>
			<figure class="hxhe-quote" data-hx-reveal>
				<blockquote><p>“In Switzerland, good enough has never been good&nbsp;enough.”</p></blockquote>
				<figcaption>The Caviar of Switzerland standard</figcaption>
			</figure>
			<dl class="hxhe-figure" data-hx-reveal>
				<dt class="hx-numeral">480<span class="hxhe-figure__plus">+</span></dt>
				<dd>Swiss life science companies within 50&nbsp;km of our laboratory</dd>
			</dl>
		</div>
	</div>
</section>

<section class="hxhe-sec hxhe-eras" aria-labelledby="hxhe-eras-title">
	<div class="hx-wrap">
		<header class="hxhe-head" data-hx-stagger>
			<p class="hx-label" data-hx-reveal="fade">Three centuries of Swiss excellence</p>
			<h2 id="hxhe-eras-title" class="hx-heading hxhe-h2" data-hx-reveal>A heritage of doing things properly.</h2>
		</header>
		<ol class="hxhe-eras__list" data-hx-stagger>
			<li class="hxhe-era" data-hx-reveal>
				<p class="hxhe-era__when">16th – 18th century</p>
				<h3 class="hx-title hxhe-era__title">The age of the watchmaker</h3>
				<p class="hxhe-era__body">Swiss craftsmen in Geneva and Le Locle spent generations proving that the finest things demand the deepest patience. The mechanical watch, accurate to fractions of a second and assembled by hand under magnification, became the world’s first proof that Swiss precision was not a philosophy but a practice. We honour that inheritance in every formulation step.</p>
			</li>
			<li class="hxhe-era" data-hx-reveal>
				<p class="hxhe-era__when">19th – 20th century</p>
				<h3 class="hx-title hxhe-era__title">The pharmaceutical revolution</h3>
				<p class="hxhe-era__body">Basel became the pharmaceutical capital of the world. Roche, Novartis, Lonza: names synonymous with global medicine built their foundations in Swiss laboratories. The Canton of Zug emerged as the life sciences corridor, where global pharmaceutical companies locate their most critical operations because the standard of science here remains unmatched. That infrastructure is our context.</p>
			</li>
			<li class="hxhe-era" data-hx-reveal>
				<p class="hxhe-era__when">21st century</p>
				<h3 class="hx-title hxhe-era__title">Cellular luxury redefined</h3>
				<p class="hxhe-era__body">Caviar of Switzerland was founded here, in the Canton of Zug, as a direct heir to this tradition. Not inspired by Swiss heritage; constituted by it. Every formula undergoes the same validation protocols as pharmaceutical products manufactured in this corridor. The same laboratories. The same certification bodies. The same uncompromising standard, applied to your daily ritual.</p>
			</li>
		</ol>
	</div>
</section>

<section class="hxhe-sec hxhe-zug" aria-labelledby="hxhe-zug-title">
	<div class="hx-wrap hxhe-zug__grid">
		<div class="hxhe-zug__text" data-hx-stagger>
			<p class="hx-label" data-hx-reveal="fade">Canton of Zug <span aria-hidden="true">·</span> Switzerland</p>
			<h2 id="hxhe-zug-title" class="hx-heading hxhe-h2" data-hx-reveal>The life sciences capital of&nbsp;Europe.</h2>
			<div class="hxhe-prose" data-hx-reveal>
				<p>The Canton of Zug is not chosen for its scenery, though the Alps do rise sharply from the northern shore of the Zugersee. It is chosen because it is home to one of the highest concentrations of pharmaceutical, biotech and life sciences companies on earth.</p>
				<p>Within a 50-kilometre radius of our laboratory, more than 480 life science companies operate under Swiss GMP (Good Manufacturing Practice) regulations: regulations that are stricter, more detailed and more consistently enforced than almost any other jurisdiction in the world.</p>
				<p>When we say our formulas are manufactured in Switzerland, we are not invoking a geographic brand. We are describing a regulatory environment, a talent pool and a culture of quality that cannot be replicated elsewhere.</p>
			</div>
			<dl class="hxhe-facts" data-hx-reveal>
				<div class="hxhe-fact"><dt>GMP+</dt><dd>Swiss pharmaceutical manufacturing certification, exceeding EU standards</dd></div>
				<div class="hxhe-fact"><dt>6300</dt><dd>The postal code of Zug, Switzerland’s premier life sciences address</dd></div>
			</dl>
		</div>
		<ul class="hxhe-notes" data-hx-stagger>
			<li class="hxhe-note" data-hx-reveal>
				<h3 class="hx-title hxhe-note__title">The Zug corridor</h3>
				<p>Switzerland’s highest concentration of pharmaceutical and biotechnology companies, operating under the world’s most rigorous manufacturing standards. Our neighbours include some of the most trusted names in global medicine.</p>
			</li>
			<li class="hxhe-note" data-hx-reveal>
				<h3 class="hx-title hxhe-note__title">Regulatory precision</h3>
				<p>Every Caviar of Switzerland formula is manufactured under GMP protocols governed by Swissmedic, the Swiss regulatory authority, with standards that frequently exceed both the FDA and EMA.</p>
			</li>
			<li class="hxhe-note" data-hx-reveal>
				<h3 class="hx-title hxhe-note__title">Traceability by default</h3>
				<p>Swiss manufacturing law requires full ingredient traceability. We know the source, batch, purity and certification of every compound in every product: not as a brand promise, but as a legal requirement.</p>
			</li>
		</ul>
	</div>
</section>

<section class="hxhe-alpine" aria-labelledby="hxhe-alpine-title">
	<figure class="hxhe-alpine__plate hx-frame" data-hx-reveal="settle">
		<?php
		echo cos_h26_picture(
			'hxhe-valley-tall',
			array( 640, 960, 1280 ),
			array( 4, 5 ),
			'An Alpine valley: dark forest, a mountain stream and snow-dusted peaks under a pale sky',
			array(
				'sizes' => '100vw',
				'w'     => 960,
				'art'   => array(
					array( 'media' => '(min-width: 760px)', 'base' => 'hxhe-valley-wide', 'widths' => array( 1440, 1920 ), 'sizes' => '100vw' ),
				),
			)
		);
		?>
	</figure>
	<div class="hx-wrap hxhe-sec hxhe-alpine__body">
		<header class="hxhe-head hxhe-head--split" data-hx-stagger>
			<p class="hx-label" data-hx-reveal="fade">The Alpine standard</p>
			<h2 id="hxhe-alpine-title" class="hx-heading hxhe-h2" data-hx-reveal>Water that has spent decades becoming pure.</h2>
			<p class="hx-lead hxhe-head__lede" data-hx-reveal>Swiss glacier meltwater is among the most chemically pure water on earth. Filtered through thousands of metres of Alpine rock over decades, it carries a mineral profile uniquely suited to high-performance skincare formulation.</p>
		</header>
		<ol class="hxhe-trio" data-hx-stagger>
			<li class="hxhe-trio__item" data-hx-reveal>
				<span class="hx-step">I</span>
				<h3 class="hx-title">Glacial origin</h3>
				<p>Our base water originates from Alpine glaciers above 2,000 metres, where UV exposure, extreme cold and natural filtration through granite and limestone produce water of extraordinary purity, with a mineral content precisely suited to skin biology.</p>
			</li>
			<li class="hxhe-trio__item" data-hx-reveal>
				<span class="hx-step">II</span>
				<h3 class="hx-title">Mineral balance</h3>
				<p>Alpine water carries a unique mineral fingerprint: silica, calcium and magnesium in proportions that support hydration and skin barrier function. This is not treated water returned to purity. It is water that has never lost it.</p>
			</li>
			<li class="hxhe-trio__item" data-hx-reveal>
				<span class="hx-step">III</span>
				<h3 class="hx-title">Altitude purity</h3>
				<p>At elevation, microorganisms cannot survive where our source waters form. Formulation begins with a base that requires no chemical treatment, only the precision of Swiss laboratory handling. Purity is not achieved here. It is inherited.</p>
			</li>
		</ol>
	</div>
</section>

<section class="hxhe-sec hxhe-code" aria-labelledby="hxhe-code-title">
	<div class="hx-wrap hxhe-code__grid">
		<div class="hxhe-code__aside">
			<header class="hxhe-code__head" data-hx-stagger>
				<p class="hx-label" data-hx-reveal="fade">The four principles</p>
				<h2 id="hxhe-code-title" class="hx-heading hxhe-h2" data-hx-reveal>The Helvetic code.</h2>
				<p class="hx-lead hxhe-head__lede" data-hx-reveal>Four concepts drawn from Swiss cultural tradition, applied to every decision we make: from formulation to packaging to the way we speak with our clients.</p>
			</header>
			<figure class="hxhe-code__media hx-frame" data-hx-reveal="mask">
				<?php echo cos_h26_picture( 'hxhe-jar', array( 640, 960, 1280 ), array( 4, 5 ), 'Hands holding the 24h Regeneration Cream jar, a touch of cream on the fingertip and the back of the hand', array( 'sizes' => '(min-width: 1100px) 36vw, 100vw', 'w' => 960 ) ); // phpcs:ignore ?>
			</figure>
		</div>
		<ol class="hxhe-code__list" data-hx-stagger>
			<li class="hxhe-principle" data-hx-reveal>
				<p class="hxhe-principle__fr" lang="fr">Précision</p>
				<p class="hxhe-principle__en">Precision</p>
				<p class="hxhe-principle__body">Switzerland built the world’s most trusted timepieces by refusing approximation at every stage. We apply the same intolerance for imprecision to formulation: exact concentrations, validated stability, documented efficacy. Nothing is approximate.</p>
			</li>
			<li class="hxhe-principle" data-hx-reveal>
				<p class="hxhe-principle__fr" lang="fr">Discrétion</p>
				<p class="hxhe-principle__en">Discretion</p>
				<p class="hxhe-principle__body">Swiss culture has always spoken through quality rather than volume. True luxury does not announce itself. Our formulas are designed to work without sensation: no fragrance for its own sake, no theatrical textures. The results speak for themselves.</p>
			</li>
			<li class="hxhe-principle" data-hx-reveal>
				<p class="hxhe-principle__fr" lang="fr">Maîtrise</p>
				<p class="hxhe-principle__en">Mastery</p>
				<p class="hxhe-principle__body">Swiss pharmaceutical expertise spans generations. The knowledge in our formulas draws on decades of molecular biology research, clinical dermatology and ingredient science developed in Swiss laboratories. We do not follow trends. We follow data.</p>
			</li>
			<li class="hxhe-principle" data-hx-reveal>
				<p class="hxhe-principle__fr" lang="fr">Durée</p>
				<p class="hxhe-principle__en">Endurance</p>
				<p class="hxhe-principle__body">Swiss watches are designed to outlast their owners and be passed to the next generation. Swiss skincare should work the same way: not a temporary sensation, but measurable, lasting improvement to the skin over time.</p>
			</li>
		</ol>
	</div>
</section>

<section class="hxhe-sec hxhe-explore" aria-labelledby="hxhe-explore-title">
	<div class="hx-wrap">
		<h2 id="hxhe-explore-title" class="hx-label hxhe-explore__label" data-hx-reveal="fade">Explore the heritage</h2>
		<ul class="hxhe-explore__list" data-hx-stagger>
			<li data-hx-reveal>
				<a class="hxhe-card" href="<?php echo esc_url( home_url( '/heritage/craftsmanship/' ) ); ?>">
					<span class="hxhe-card__media hx-frame"><?php echo cos_h26_picture( 'hxhe-drop', array( 640, 960, 1280 ), array( 4, 5 ), '', array( 'sizes' => '(min-width: 760px) 46vw, 100vw', 'w' => 960 ) ); // phpcs:ignore ?></span>
					<span class="hxhe-card__tag">Swiss craftsmanship</span>
					<span class="hx-title hxhe-card__title">The art of Swiss manufacture.</span>
					<span class="hxhe-card__body">From raw ingredient to sealed vessel, every step in our production reflects the Swiss manufacturing tradition: manual verification at each stage, zero-tolerance quality control, and a final formula released only when it meets the Caviar of Switzerland quality standard.</span>
					<span class="hxhe-card__cta">Explore craftsmanship<?php echo $a; // phpcs:ignore ?></span>
				</a>
			</li>
			<li data-hx-reveal>
				<a class="hxhe-card" href="<?php echo esc_url( home_url( '/heritage/sustainability/' ) ); ?>">
					<span class="hxhe-card__media hx-frame"><?php echo cos_h26_picture( 'hxhe-box', array( 640, 864 ), array( 864, 1080 ), '', array( 'sizes' => '(min-width: 760px) 46vw, 100vw', 'w' => 864 ) ); // phpcs:ignore ?></span>
					<span class="hxhe-card__tag">Sustainability</span>
					<span class="hx-title hxhe-card__title">Responsibility as a Swiss value.</span>
					<span class="hxhe-card__body">Switzerland’s relationship with its Alpine environment is one of deep, generational stewardship. We extend that ethic to every aspect of our brand: recyclable packaging, sustainable ingredient sourcing, and a product design philosophy built for longevity, not disposability.</span>
					<span class="hxhe-card__cta">Explore sustainability<?php echo $a; // phpcs:ignore ?></span>
				</a>
			</li>
		</ul>
	</div>
</section>

<section class="hxhe-close" aria-labelledby="hxhe-close-title">
	<div class="hx-wrap hxhe-close__in" data-hx-stagger>
		<p class="hx-label" data-hx-reveal="fade">Formulated in Zug <span aria-hidden="true">·</span> Swiss precision</p>
		<h2 id="hxhe-close-title" class="hx-heading hxhe-close__title" data-hx-reveal>The Swiss standard, in your&nbsp;hands.</h2>
		<p class="hx-lead hxhe-close__lede" data-hx-reveal>Every product we make carries the full weight of Swiss manufacturing heritage. Not as a story we tell, but as a standard we are legally and ethically bound to uphold.</p>
		<p class="hxhe-close__actions" data-hx-reveal>
			<a class="hx-btn" href="<?php echo esc_url( home_url( '/shop/' ) ); ?>">Shop the collection<?php echo $a; // phpcs:ignore ?></a>
			<a class="hx-link" href="<?php echo esc_url( home_url( '/science/' ) ); ?>">The science behind it<?php echo $a; // phpcs:ignore ?></a>
		</p>
	</div>
</section>
