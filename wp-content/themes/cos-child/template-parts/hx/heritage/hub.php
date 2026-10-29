<?php
/**
 * /heritage/ (page 61) — "Precision born from the Alps." Styles: assets/css/hx/heritage.css (hxhe-).
 *
 * Copy carried over from the page's post_content (16 Oct 2026 snapshot), facts unchanged; wording
 * tightened only where the facts stay identical. Critic round 1 (17 Oct): halved. The "life sciences
 * capital" (Zug corridor) and "Alpine standard" (glacier water) sections, the 480+/GMP+/6300 figures
 * and one guarantee paragraph were cut; several of those claims await the owner. Two eyebrows only (one since 24 Oct: the hero eyebrow went with the bleed plate).
 * Photography: the homepage's approved Matterhorn summit plate (its one reuse, the signature moment;
 * captioned with the place so it is never read as Zug) and owner product photography.
 * Never the philosophy-lab image (unverified provenance).
 */
defined( 'ABSPATH' ) || exit;

$a = cos_hxhe_arrow();
?>
<?php
/*
 * Opening (24 Oct 2026, owner: "layout the heritage page so that it is like the science page"):
 * the science hub's bleed plate. The Matterhorn fills the first screen under the transparent
 * header, the h1 and lead in ink on the cloud at the lower left (#9A–#A2 there: ink 5.9:1);
 * phones: the tall crop on a clean edge, type beneath. Then the page's four parts as an index,
 * as the science hub lists its five questions. The eyebrow and the two hero links left: the
 * links are the closing section's two actions.
 */
$parts = array(
	array( 'standard', 'Where one standard rules all others' ),
	array( 'history', 'A heritage of doing things properly' ),
	array( 'code', 'The Helvetic code' ),
	array( 'explore', 'Explore the heritage' ),
);
?>
<header class="hxhe-hero hxhe-hero--bleed" aria-labelledby="hxhe-title">
	<figure class="hxhe-hero__plate hx-load-fade">
		<?php
		// 25 Oct 2026 (no repeated pictures): the film's own graded poster frame, so the still
		// before the film plays (and under reduced motion) matches it; the summit plate is the homepage's
		echo cos_h26_picture(
			'swiss-mountain',
			array( 640, 960 ),
			array( 1, 1 ),
			'The Matterhorn under drifting cloud, printed in silver tones',
			array(
				'sizes' => '100vw',
				'w'     => 960,
				'eager' => true,
			)
		);
		?>
		<video id="hxhe-hero-video" class="hxhe-hero__video" width="960" height="960" muted loop playsinline preload="metadata" aria-hidden="true" tabindex="-1">
			<source src="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/images/home-2026/swiss-mountain-640.mp4' ); ?>" type="video/mp4" media="(max-width: 699px)">
			<source src="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/images/home-2026/swiss-mountain-960.mp4' ); ?>" type="video/mp4">
		</video>
		<figcaption class="hxhe-caption">Matterhorn, Valais <span aria-hidden="true">·</span> 4&#8239;478&nbsp;m</figcaption>
	</figure>
	<button class="hxhe-hero__control" type="button" aria-controls="hxhe-hero-video" aria-label="Play hero video"><span class="hxhe-hero__control-label">Play</span></button>
	<div class="hx-wrap hxhe-hero__text">
		<h1 id="hxhe-title" class="hx-display hxhe-hero__title hx-load-rise" style="--hx-i:1">Precision born <em>from the Alps.</em></h1>
		<p class="hx-lead hxhe-hero__lede hx-load-rise" style="--hx-i:2">Switzerland is not a backdrop. It is a methodology: four centuries of watchmaking mastery, pharmaceutical leadership and Alpine purity, distilled into every formula we create.</p>
	</div>
</header>

<nav class="hxhe-index" aria-label="On this page">
	<div class="hx-wrap">
		<ol class="hxhe-index__list">
			<?php foreach ( $parts as $i => [ $id, $title ] ) : ?>
			<li><a class="hxhe-index__link" href="#<?php echo esc_attr( $id ); ?>"><span class="hxhe-index__n" aria-hidden="true"><?php echo esc_html( sprintf( '%02d', $i + 1 ) ); ?></span><?php echo esc_html( $title ); ?></a></li>
			<?php endforeach; ?>
		</ol>
	</div>
</nav>

<section id="standard" class="hxhe-sec hxhe-guarantee" aria-labelledby="hxhe-guarantee-title">
	<div class="hx-wrap hxhe-split hxhe-split--image-left">
		<figure class="hxhe-split__media hx-frame" data-hx-reveal="settle">
			<?php echo cos_h26_picture( 'hxhe-hands', array( 640, 960, 1280 ), array( 4, 5 ), 'Two hands presenting the Micellar Water All-in-One Cleanser against a white ground', array( 'sizes' => '(min-width: 900px) 42vw, 100vw', 'w' => 960 ) ); // phpcs:ignore ?>
		</figure>
		<div class="hxhe-split__text" data-hx-stagger>
			<h2 id="hxhe-guarantee-title" class="hx-heading hxhe-h2" data-hx-reveal>Where one standard rules all others.</h2>
			<div class="hxhe-prose" data-hx-reveal>
				<p>In Switzerland, precision is not a marketing claim. It is a cultural inheritance, threaded through four centuries of watchmaking mastery, pharmaceutical innovation and the enduring discipline of life in the Alps.</p>
				<p>When we chose to formulate in Switzerland, we were not choosing a label. We were choosing to be held accountable. Every batch tested. Every ingredient traced. Every formula validated before it reaches your skin.</p>
			</div>
			<figure class="hxhe-quote" data-hx-reveal>
				<blockquote><p>“In Switzerland, good enough has never been good&nbsp;enough.”</p></blockquote>
			</figure>
		</div>
	</div>
</section>

<section id="history" class="hxhe-sec hxhe-eras" aria-labelledby="hxhe-eras-title">
	<div class="hx-wrap">
		<h2 id="hxhe-eras-title" class="hx-heading hxhe-h2 hxhe-eras__title" data-hx-reveal>A heritage of doing things properly.</h2>
		<ol class="hxhe-eras__list" data-hx-stagger>
			<li class="hxhe-era" data-hx-reveal>
				<p class="hxhe-num">16th – 18th century</p>
				<h3 class="hx-title hxhe-era__title">The age of the watchmaker</h3>
				<p class="hxhe-era__body">Swiss craftsmen in Geneva and Le Locle proved that the finest things demand the deepest patience. The mechanical watch, assembled by hand under magnification, made Swiss precision a practice, not a philosophy.</p>
			</li>
			<li class="hxhe-era" data-hx-reveal>
				<p class="hxhe-num">19th – 20th century</p>
				<h3 class="hx-title hxhe-era__title">The pharmaceutical revolution</h3>
				<p class="hxhe-era__body">Basel became the pharmaceutical capital of the world, and the Canton of Zug emerged as its life sciences corridor. That infrastructure is our context.</p>
			</li>
			<li class="hxhe-era" data-hx-reveal>
				<p class="hxhe-num">21st century</p>
				<h3 class="hx-title hxhe-era__title">Caviar of Switzerland</h3>
				<p class="hxhe-era__body">Founded here, in the Canton of Zug, as a direct heir to this tradition. Not inspired by Swiss heritage; constituted by it.</p>
			</li>
		</ol>
	</div>
</section>

<section id="code" class="hxhe-sec hxhe-code" aria-labelledby="hxhe-code-title">
	<div class="hx-wrap hxhe-code__grid">
		<div class="hxhe-code__aside">
			<h2 id="hxhe-code-title" class="hx-heading hxhe-h2" data-hx-reveal>The Helvetic code.</h2>
			<p class="hx-lead hxhe-head__lede" data-hx-reveal>Four concepts drawn from Swiss cultural tradition, applied to every decision we make.</p>
			<figure class="hxhe-code__media hx-frame" data-hx-reveal="fade">
				<?php echo cos_h26_picture( 'hxhe-code', array( 640, 960, 1280 ), array( 4, 5 ), 'The four formulas and the cream jar arranged on dark stone among broad green leaves', array( 'sizes' => '(min-width: 1100px) 30vw, 100vw', 'w' => 960 ) ); // phpcs:ignore ?>
			</figure>
		</div>
		<ol class="hxhe-code__list" data-hx-stagger>
			<li class="hxhe-principle" data-hx-reveal>
				<p class="hxhe-principle__fr" lang="fr">Précision</p>
				<p class="hxhe-principle__body"><span class="hxhe-principle__en">Precision.</span> Switzerland built the world’s most trusted timepieces by refusing approximation. We apply the same intolerance to formulation: exact concentrations, validated stability, documented efficacy.</p>
			</li>
			<li class="hxhe-principle" data-hx-reveal>
				<p class="hxhe-principle__fr" lang="fr">Discrétion</p>
				<p class="hxhe-principle__body"><span class="hxhe-principle__en">Discretion.</span> True luxury does not announce itself. No fragrance for its own sake, no theatrical textures. The results speak for themselves.</p>
			</li>
			<li class="hxhe-principle" data-hx-reveal>
				<p class="hxhe-principle__fr" lang="fr">Maîtrise</p>
				<p class="hxhe-principle__body"><span class="hxhe-principle__en">Mastery.</span> Our formulas draw on decades of ingredient science developed in Swiss laboratories. We do not follow trends. We follow data.</p>
			</li>
			<li class="hxhe-principle" data-hx-reveal>
				<p class="hxhe-principle__fr" lang="fr">Durée</p>
				<p class="hxhe-principle__body"><span class="hxhe-principle__en">Endurance.</span> Swiss watches are made to be passed to the next generation. Swiss skincare should work the same way: not a temporary sensation, but measurable, lasting improvement over time.</p>
			</li>
		</ol>
	</div>
</section>

<section id="explore" class="hxhe-sec hxhe-explore" aria-labelledby="hxhe-explore-title">
	<div class="hx-wrap">
		<h2 id="hxhe-explore-title" class="hx-title hxhe-explore__title" data-hx-reveal="fade">Explore the heritage</h2>
		<ul class="hxhe-explore__list" data-hx-stagger>
			<li data-hx-reveal>
				<a class="hxhe-card" href="<?php echo esc_url( home_url( '/heritage/craftsmanship/' ) ); ?>">
					<span class="hxhe-card__media hx-frame"><?php echo cos_h26_picture( 'hxhe-craft', array( 640, 960, 1280 ), array( 4, 5 ), '', array( 'sizes' => '(min-width: 760px) 46vw, 100vw', 'w' => 960 ) ); // phpcs:ignore ?></span>
					<span class="hx-title hxhe-card__title">The art of Swiss manufacture.</span>
					<span class="hxhe-card__body">From raw ingredient to sealed vessel: manual verification at each stage, and a formula released only when it meets our quality standard.</span>
					<span class="hxhe-card__cta">Craftsmanship &amp; sourcing<?php echo $a; // phpcs:ignore ?></span>
				</a>
			</li>
			<li data-hx-reveal>
				<a class="hxhe-card" href="<?php echo esc_url( home_url( '/heritage/sustainability/' ) ); ?>">
					<span class="hxhe-card__media hx-frame"><?php echo cos_h26_picture( 'hxhe-sustain-tall', array( 640, 960, 1280 ), array( 4, 5 ), '', array( 'sizes' => '(min-width: 760px) 46vw, 100vw', 'w' => 960 ) ); // phpcs:ignore ?></span>
					<span class="hx-title hxhe-card__title">Responsibility as a Swiss value.</span>
					<span class="hxhe-card__body">Recyclable packaging, sustainable ingredient sourcing, and products built for longevity, not disposability.</span>
					<span class="hxhe-card__cta">Sustainability<?php echo $a; // phpcs:ignore ?></span>
				</a>
			</li>
		</ul>
	</div>
</section>

<section class="hxhe-close" aria-labelledby="hxhe-close-title">
	<div class="hx-wrap hxhe-close__in" data-hx-stagger>
		<p class="hx-label" data-hx-reveal="fade">Formulated in Zug <span aria-hidden="true">·</span> Swiss precision</p>
		<h2 id="hxhe-close-title" class="hx-heading hxhe-close__title" data-hx-reveal>The Swiss standard, in your&nbsp;hands.</h2>
		<p class="hxhe-close__actions" data-hx-reveal>
			<a class="hx-btn" href="<?php echo esc_url( home_url( '/shop/' ) ); ?>">Shop the collection<?php echo $a; // phpcs:ignore ?></a>
			<a class="hx-link" href="<?php echo esc_url( home_url( '/science/' ) ); ?>">The science behind it<?php echo $a; // phpcs:ignore ?></a>
		</p>
	</div>
</section>
