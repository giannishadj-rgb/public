<?php
/**
 * /science/ (page 344) — the Swiss science hub.
 *
 * Clarify pass, 23 Oct 2026 (owner: "help the customer understand the ingredients, the
 * benefits, the why, the how"). The hub now answers a customer's questions in the order she
 * asks them, each as its own section heading, with the chapters as the way to read further:
 * 1 Plate: "Inside every formula." (headline changed 25 Oct 2026) and the lead, the caviar field, then the five
 *   questions as an in-page index.
 * 2 Why caviar?                     the material, from the actives data (PRODUCT.md facts).
 * 3 What is inside?                 the five actives, name · role · one line (stone room).
 * 4 What does it do for your skin?  Protect / Repair / Rejuvenate, each naming its actives.
 * 5 How does it work?               encapsulation, one complex in four steps, made in Zug (stone).
 * 6 How do we know?                 the studied formulas (unchanged proof: separate single-formula
 *   studies, page 347; no ritual study; no cleanser study; only instrument-measured results get
 *   the large figure; the cream's self-reported result stays text).
 * 7 The five chapters (stone), then a quiet close: the Complete Ritual.
 * All wording comes from approved sources (hxsc_actives / hxsc_pillars / PRODUCT.md); no new claims.
 * Plain-language pass, 25 Oct 2026: jargon glossed or replaced (barrier, micro-encapsulated, free
 * radical, cutometry, profilometry, ISO 22716), each idea said once, benefits led by the concern.
 */
defined( 'ABSPATH' ) || exit;

$pillars = hxsc_pillars();
// The hub states each benefit from the customer's side: the concern first, then what helps (25 Oct 2026).
$pillar_lines = array(
	'protect'    => 'For skin under daily strain. Two antioxidants help defend it against UV and environmental stress.',
	'repair'     => 'For skin that feels dry or tight. Caviar lipids help replenish its protective outer layer, and hyaluronic acid holds moisture in.',
	'rejuvenate' => 'For skin losing firmness. A peptide studied in collagen research works with caviar extract, for skin that looks firmer and smoother.',
);
$actives = hxsc_actives();
$all     = hxsc_studies();
$studies = array_slice( $all, 0, 3 );
$interim = $all[3];
// Each instrument named with what it measures, in plain words (25 Oct 2026).
$method_plain = array(
	'serum' => 'Cutometry, which measures how skin springs back (R2 parameter)',
	'eye'   => 'Optical profilometry, a 3D scan of the skin’s surface',
);

// One line per active for the hub, condensed from each active's approved description.
$active_lines = array(
	'caviar-extract'       => 'Lipids close to the skin’s own, to help replenish its protective outer layer.',
	'syn-coll-peptide'     => 'A peptide studied in collagen research, for skin that looks firmer and smoother.',
	'hyaluronic-acid'      => 'Holds water in the skin, from the surface down to its deeper layers.',
	'superoxide-dismutase' => 'An antioxidant enzyme your skin also makes. It helps neutralise free radicals, the unstable molecules that daily stress creates.',
	'resveratrol'          => 'A plant antioxidant that helps skin resist daily UV and environmental stress.',
);
// Short names for the "With …" line under each benefit.
$short = array(
	'caviar-extract'       => 'Caviar Extract',
	'syn-coll-peptide'     => 'Syn®‑Coll Peptide',
	'hyaluronic-acid'      => 'Hyaluronic Acid',
	'superoxide-dismutase' => 'Superoxide Dismutase',
	'resveratrol'          => 'Resveratrol',
);
$with = array();
foreach ( $actives as $a ) {
	foreach ( $a['roles'] as $r ) $with[ $r ][] = $short[ $a['slug'] ] ?? $a['name'];
}

$questions = array(
	array( 'why', 'Why caviar?' ),
	array( 'inside', 'What is inside?' ),
	array( 'benefits', 'What does it do for your skin?' ),
	array( 'how', 'How does it work?' ),
	array( 'evidence', 'How do we know?' ),
);
$and = static function ( array $names ): string {
	if ( count( $names ) < 2 ) return implode( '', $names );
	return implode( ', ', array_slice( $names, 0, -1 ) ) . ' and ' . end( $names );
};

get_template_part( 'template-parts/hx/science/masthead', null, array(
	'mode'  => 'bleed',
	'title' => 'Inside every formula.',
	'lead'  => 'Caviar from farmed Siberian sturgeon and four supporting actives, made in Zug. Here is what each one does for your skin, and every study result with its panel and method.',
	// Owner, 25 Oct 2026: the hero video is the owner's red-roe close-up clip (8s, 1280x720 source),
	// self-hosted; it opens on a dark ground with no photograph ('dark'). The earlier
	// science-caviar-hero-*.mp4 (dark pearls in water) stays in assets/video/ for a restore.
	'video' => array(
		'mobile'  => 'science-roe-hero-960.mp4',
		'desktop' => 'science-roe-hero-1280.mp4',
	),
	'dark'  => true,
	'plate' => array(
		'base' => 'hxv-field', 'widths' => array( 1440, 2048, 2880 ), 'ratio' => array( 2, 1 ),
		'alt'  => 'Glossy black caviar pearls on a pale, reflective surface',
		'tall' => array( 'hxv-field-tall', array( 640, 960, 1280 ), array( 4, 5 ) ),
		'pos'  => '70% 50%',
	),
) );
?>

<nav class="hxsc-qnav" aria-label="On this page">
	<div class="hx-wrap">
		<ol class="hxsc-qnav__list">
			<?php foreach ( $questions as $i => [ $id, $q ] ) : ?>
			<li><a class="hxsc-qnav__link" href="#<?php echo esc_attr( $id ); ?>"><span class="hxsc-qnav__n" aria-hidden="true"><?php echo esc_html( sprintf( '%02d', $i + 1 ) ); ?></span><?php echo esc_html( $q ); ?></a></li>
			<?php endforeach; ?>
		</ol>
	</div>
</nav>

<section id="why" class="hxsc-q" aria-labelledby="hxsc-why-t">
	<div class="hx-wrap hxsc-q__split hxsc-q__split--fig">
		<h2 id="hxsc-why-t" class="hx-heading hxsc-q__title" data-hx-reveal>Why caviar?</h2>
		<figure class="hxsc-q__fig" data-hx-reveal><?php echo cos_h26_picture( 'wc-cav-glass', array( 480, 640, 800 ), array( 4, 5 ), 'Close-up of glossy caviar pearls on a pale surface, light passing through them', array( 'sizes' => '(min-width: 900px) 36vw, 100vw', 'w' => 800 ) ); // phpcs:ignore ?></figure>
		<div class="hxsc-q__body" data-hx-reveal>
			<p class="hx-lead">Caviar is the heart of every formula. Ours comes from Siberian sturgeon (<i lang="la">Acipenser baerii</i>), sustainably farmed and fully traceable, never wild-harvested.</p>
			<p class="hx-body">The roe is rich in lipids, amino acids, vitamins and trace minerals. Its lipids are close to the ones your skin makes to stay supple and hold in moisture. Cleansing and time wear those down; caviar helps replenish them.</p>
			<p class="hxsc-q__more"><a class="hx-link" href="<?php echo hxsc_url( '/science/why-caviar/' ); ?>">Read: Why caviar<?php echo hxsc_arrow(); // phpcs:ignore ?></a></p>
		</div>
	</div>
</section>

<section id="inside" class="hxsc-q hxsc-q--room" aria-labelledby="hxsc-inside-t">
	<div class="hx-wrap">
		<div class="hxsc-q__split hxsc-q__split--head">
			<h2 id="hxsc-inside-t" class="hx-heading hxsc-q__title" data-hx-reveal>What is inside?</h2>
			<p class="hx-lead hxsc-q__body" data-hx-reveal>Five actives, together called the High-Tech Complex. Every formula carries all five, in the concentration its step in the ritual calls for.</p>
		</div>
		<ol class="hxsc-actlist" data-hx-stagger>
			<?php foreach ( $actives as $a ) : ?>
			<li class="hxsc-actlist__row" data-hx-reveal>
				<span class="hxsc-actlist__n" aria-hidden="true"><?php echo esc_html( $a['num'] ); ?></span>
				<h3 class="hxsc-actlist__name"><a href="<?php echo hxsc_url( '/science/ingredients/' ); ?>#<?php echo esc_attr( $a['slug'] ); ?>"><?php echo esc_html( $a['name'] ); ?></a></h3>
				<p class="hxsc-actlist__role"><?php echo esc_html( $a['role'] ); ?></p>
				<p class="hxsc-actlist__line"><?php echo esc_html( $active_lines[ $a['slug'] ] ?? '' ); ?></p>
			</li>
			<?php endforeach; ?>
		</ol>
		<p class="hxsc-q__more hxsc-q__more--row" data-hx-reveal>
			<a class="hx-link" href="<?php echo hxsc_url( '/science/ingredients/' ); ?>">Read: The five actives<?php echo hxsc_arrow(); // phpcs:ignore ?></a>
			<a class="hx-link" href="<?php echo hxsc_url( '/science/high-tech-complex/' ); ?>">How they work together<?php echo hxsc_arrow(); // phpcs:ignore ?></a>
		</p>
	</div>
</section>

<section id="benefits" class="hxsc-q" aria-labelledby="hxsc-benefits-t">
	<div class="hx-wrap">
		<div class="hxsc-q__split hxsc-q__split--head">
			<h2 id="hxsc-benefits-t" class="hx-heading hxsc-q__title" data-hx-reveal>What does it do for your skin?</h2>
			<p class="hx-lead hxsc-q__body" data-hx-reveal>Three benefits, each with the actives behind it.</p>
		</div>
		<?php
		$pillar_pics = array(
			// owner photographs used nowhere else on the site (25 Oct 2026: no repeated pictures)
			'protect'    => array( 'hxsc-protect', 'A woman holding the Advanced Repair Serum against her chest' ),
			'repair'     => array( 'hxsc-repair', 'A woman resting her arms on a stone sphere beside the 24h Regeneration Cream jar' ),
			'rejuvenate' => array( 'hxsc-rejuvenate', 'Portrait of a woman with smooth, even skin, her hand at her jaw' ),
		);
		?>
		<ul class="hxsc-words__list hxsc-trio hxsc-trio--pics" role="list" data-hx-stagger>
			<?php foreach ( $pillars as $key => $p ) : ?>
			<li data-hx-reveal>
				<?php if ( isset( $pillar_pics[ $key ] ) ) : ?><figure class="hxsc-trio__fig hxsc-trio__fig--<?php echo esc_attr( $key ); ?>"><?php echo cos_h26_picture( $pillar_pics[ $key ][0], array( 640, 960, 1280 ), array( 4, 5 ), $pillar_pics[ $key ][1], array( 'sizes' => '(min-width: 900px) 30vw, 100vw', 'w' => 960 ) ); // phpcs:ignore ?></figure><?php endif; ?>
				<h3 class="hxsc-words__w"><?php echo esc_html( $p[0] ); ?></h3>
				<p class="hx-body"><?php echo esc_html( $pillar_lines[ $key ] ?? $p[1] ); ?></p>
				<?php if ( ! empty( $with[ $key ] ) ) : ?><p class="hxsc-trio__with"><span class="hxsc-trio__k">With</span> <?php echo esc_html( $and( $with[ $key ] ) ); ?></p><?php endif; ?>
			</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>

<section id="how" class="hxsc-q hxsc-q--room" aria-labelledby="hxsc-how-t">
	<div class="hx-wrap">
		<div class="hxsc-q__split hxsc-q__split--head">
			<h2 id="hxsc-how-t" class="hx-heading hxsc-q__title" data-hx-reveal>How does it work?</h2>
			<p class="hx-lead hxsc-q__body" data-hx-reveal>What keeps the actives working, how the four formulas share them, and where they are made.</p>
		</div>
		<ul class="hxsc-words__list hxsc-trio hxsc-trio--pics" role="list" data-hx-stagger>
			<li data-hx-reveal>
				<figure class="hxsc-trio__fig"><?php echo cos_h26_picture( 'caviar-spoon', array( 1280, 1672 ), array( 1672, 941 ), 'A silver spoon heaped with glossy black caviar on a dark ground', array( 'sizes' => '(min-width: 900px) 30vw, 100vw', 'w' => 1280 ) ); // phpcs:ignore -- the spheres open the High-Tech Complex chapter, so not repeated here ?></figure>
				<h3 class="hxsc-words__w">Protected until applied</h3>
				<p class="hx-body">The caviar actives are sealed in microscopic capsules. These keep them stable in the bottle and release them gradually on your skin.</p>
				<p class="hxsc-trio__with"><a class="hx-link" href="<?php echo hxsc_url( '/science/high-tech-complex/' ); ?>">The High-Tech Complex<?php echo hxsc_arrow(); // phpcs:ignore ?></a></p>
			</li>
			<li data-hx-reveal>
				<figure class="hxsc-trio__fig hxsc-trio__fig--set"><?php echo cos_h26_picture( 'hxz-set', array( 640, 900, 1150 ), array( 1150, 1440 ), 'The four formulas together: cleanser, serum, eye cream and 24h cream', array( 'sizes' => '(min-width: 900px) 30vw, 100vw', 'w' => 900 ) ); // phpcs:ignore ?></figure>
				<h3 class="hxsc-words__w">One complex, four steps</h3>
				<p class="hx-body">All four formulas carry the same five actives, each in the strength its step needs. Used in order, morning and evening, each step prepares your skin for the next.</p>
				<p class="hxsc-trio__with"><a class="hx-link" href="<?php echo hxsc_url( '/the-ritual/' ); ?>">The Ritual<?php echo hxsc_arrow(); // phpcs:ignore ?></a></p>
			</li>
			<li data-hx-reveal>
				<figure class="hxsc-trio__fig"><?php echo cos_h26_picture( 'swiss-material-study-20261017', array( 640, 1122 ), array( 1122, 1402 ), '', array( 'sizes' => '(min-width: 900px) 30vw, 100vw', 'w' => 1122 ) ); // illustrative still life (glass, stone, water), not the laboratory, so decorative ?></figure>
				<h3 class="hxsc-words__w">Made and released in Zug</h3>
				<p class="hx-body">Developed and made in a GMP-certified laboratory in Zug, to ISO 22716, the international standard for cosmetics manufacturing. Each formula is tested for stability, safety and efficacy, and every batch is traced before release.</p>
				<p class="hxsc-trio__with"><a class="hx-link" href="<?php echo hxsc_url( '/science/swiss-formulation/' ); ?>">Swiss formulation<?php echo hxsc_arrow(); // phpcs:ignore ?></a></p>
			</li>
		</ul>
	</div>
</section>

<section id="evidence" class="hxsc-proof" aria-labelledby="hxsc-proof-t">
	<div class="hx-wrap">
		<div class="hxsc-proof__head" data-hx-reveal>
			<h2 id="hxsc-proof-t" class="hx-heading hxsc-sec__title">How do we know?</h2>
			<p class="hx-body hxsc-proof__note">Three formulas were each tested on their own, in separate 28-day studies by an independent contract research organisation. The cleanser has not been studied, and there is no study of the complete ritual.</p>
		</div>
		<ul class="hxsc-proof__list" role="list" data-hx-stagger>
			<?php foreach ( $studies as $s ) : $m = ( 'measured' === $s['kind'] ); ?>
			<li class="hxsc-proof__item hxsc-proof__item--<?php echo esc_attr( $s['kind'] ); ?>" data-hx-reveal>
				<a class="hxsc-proof__obj" href="<?php echo hxsc_url( $s['url'] ); ?>" tabindex="-1" aria-hidden="true"><?php echo cos_h26_picture( 'pack-' . $s['obj'], array( 480, 720 ), array( 2, 3 ), '', array( 'sizes' => '(min-width: 900px) 220px, 160px', 'w' => 480 ) ); // phpcs:ignore ?></a>
				<?php if ( $m ) : ?>
				<p class="hx-numeral hxsc-proof__n" aria-hidden="true"><?php echo esc_html( $s['fig'] ); ?><span class="hxsc-pct">%</span></p>
				<h3 class="hxsc-proof__finding"><span class="hx-sr"><?php echo esc_html( $s['fig'] ); ?>% </span><?php echo esc_html( $s['short'] ); ?></h3>
				<?php else : ?>
				<p class="hxsc-proof__stmt"><?php echo esc_html( $s['fig'] ); ?>% panellist-reported satisfaction</p>
				<h3 class="hxsc-proof__finding">Overall satisfaction</h3>
				<?php endif; ?>
				<p class="hxsc-proof__product"><a href="<?php echo hxsc_url( $s['url'] ); ?>"><?php echo esc_html( $s['product'] ); ?></a></p>
				<p class="hxsc-proof__method"><span class="hxsc-kind"><?php echo $m ? 'Instrument‑measured' : 'Self‑reported'; ?></span><span class="hx-sr">:</span> <?php echo esc_html( $m ? ( $method_plain[ $s['obj'] ] ?? $s['method'] ) . ', ' : '' ); ?><span class="hxsc-nw"><?php echo esc_html( $s['panel'] ); ?></span> <span class="hxsc-nw">aged <?php echo esc_html( $s['ages'] ); ?>,</span> <span class="hxsc-nw">28 days</span></p>
				<?php if ( 'serum' === $s['obj'] ) : ?>
				<p class="hxsc-proof__interim"><?php echo esc_html( $interim['fig'] ); ?>% surface smoothness <span class="hxsc-nw">by day 7</span> <span class="hxsc-nw">(<?php echo esc_html( strtolower( $interim['method'] ) ); ?>)</span></p>
				<?php endif; ?>
			</li>
			<?php endforeach; ?>
		</ul>
		<p class="hxsc-proof__more" data-hx-reveal><a class="hx-link" href="<?php echo hxsc_url( '/science/clinical-studies/' ); ?>">Read the studies<?php echo hxsc_arrow(); // phpcs:ignore ?></a></p>
	</div>
</section>

<section class="hxsc-sec hxsc-chapters" aria-labelledby="hxsc-chapters-t">
	<div class="hx-wrap">
		<h2 id="hxsc-chapters-t" class="hx-heading hxsc-sec__title hxsc-chapters__t" data-hx-reveal>Read further, chapter by chapter.</h2>
		<?php
		$lines = array(
			'/science/why-caviar/'        => array( 'Responsibly farmed Acipenser Baerii roe, fully traceable from aquaculture source to final formula.', array( 'hxv-field', array( 1440, 2048 ), array( 2, 1 ) ) ),
			'/science/high-tech-complex/' => array( 'Five actives selected for evidence, bioavailability and the way they work together.', array( 'caviar-macro-light', array( 960, 1440 ), array( 3, 2 ) ) ),
			'/science/ingredients/'       => array( 'Each active: its source, its role and how it is described in research.', array( 'caviar-spoon-light', array( 720, 960 ), array( 1130, 640 ) ) ),
			'/science/clinical-studies/'  => array( 'Independent evaluations, footnoted with panel size, age range, method and duration.', array( 'evidence-portrait', array( 640, 960 ), array( 4, 5 ) ) ),
			'/science/swiss-formulation/' => array( 'Developed and made in Zug, tested for stability, safety and efficacy before release.', array( 'heritage-alps-wide', array( 1440 ), array( 2400, 1356 ) ) ),
		);
		$items = array();
		foreach ( hxsc_chapters() as $c ) {
			$c['line'] = $lines[ $c['path'] ][0];
			$c['img']  = $lines[ $c['path'] ][1];
			$items[]   = $c;
		}
		get_template_part( 'template-parts/hx/science/index', null, array( 'items' => $items ) );
		?>
	</div>
</section>

<section class="hxsc-close" aria-labelledby="hxsc-close-t">
	<div class="hx-wrap hxsc-close__in" data-hx-reveal>
		<h2 id="hxsc-close-t" class="hx-heading hxsc-close__t">Four formulas. One complete ritual.</h2>
		<p class="hxsc-ctas hxsc-close__ctas"><a class="hx-btn" href="<?php echo hxsc_url( '/products/the-complete-ritual/' ); ?>" data-cro-cta="science-hub-ritual">Discover the Complete Ritual<?php echo hxsc_arrow(); // phpcs:ignore ?></a> <a class="hx-link" href="<?php echo hxsc_url( '/shop/' ); ?>">Shop the four formulas<?php echo hxsc_arrow(); // phpcs:ignore ?></a></p>
	</div>
</section>
