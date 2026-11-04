<?php
/**
 * Below the purchase area, for The Complete Ritual (hxpd). Since 29 Oct 2026 it follows the four
 * formula pages (owner: "like the product pages"): the sticky section bar, the four steps as the
 * formulas' image cards, the full ingredient lists, the results (each attached to its own formula,
 * with the honest note that the set itself was never studied and the cleanser has no study), how
 * to use, and questions. The set has no copy of its own beyond this file: everything else is read
 * from the four formulas.
 */
defined( 'ABSPATH' ) || exit;
$studies = $args['studies'];

$faqs = array(
	array( 'Do I have to use all four formulas together?', 'Each formula works on its own, but they are made to be used in sequence: the cleanser prepares the skin for the serum, the eye cream cares for the eye contour the other steps are not formulated for, and the 24h cream seals the routine.' ),
	array( 'What order do I apply them in?', 'Morning and evening: Micellar Water All-in-One Cleanser, then Advanced Repair Serum, then Revitalizing Eye Cream, then 24h Regeneration Cream.' ),
	array( 'Are the clinical results for the ritual as a whole?', 'No. Advanced Repair Serum, Revitalizing Eye Cream and 24h Regeneration Cream were each studied on their own, in separate 28-day studies. The four formulas have not been studied together as a set, and the cleanser has not been clinically studied.' ),
	array( 'What if I only need one or two steps?', 'Each formula is also sold on its own. The Complete Ritual is for anyone starting the full routine, and costs less than buying the four separately.' ),
);

$parts = array(
	array( 'hxcr-intro', 'The ritual' ),
	array( 'hxpd-sci', 'The four steps' ),
	array( 'hxpd-use', 'How to use' ),
	array( 'hxpd-actives', 'Ingredients' ),
	array( 'hxpd-result', 'Results' ),
	array( 'hxpd-faq', 'Questions' ),
);
get_template_part( 'template-parts/hx/section-bar', null, array( 'items' => array_map( static fn( $p ) => array( 'href' => '#' . $p[0], 'text' => $p[1] ), $parts ) ) );

// 3 Nov 2026 (owner: "like" Chanel's Sublimage skincare-ritual page, in this site's light look,
// purchase block kept first): a centred opening, a three-panel triptych, a full-bleed light band
// with one line, the four formulas as large alternating rows, the gesture, then the facts, and
// the line at the end. Chanel's refill carousel has no equivalent (no refill programme, 31 Oct).
$tri = array(
	array( 'hxcr-caviar', array( 480, 720, 1080 ), 'In every formula', // 3 Nov 2026 (owner): free Unsplash caviar photograph, hxcr-caviar.origin.json
		 'Caviar extract', 'Caviar extract is in all four formulas, from the cleanser to the cream.', '#hxpd-actives', 'The ingredients' ),
	array( 'ritual-model', array( 640, 960, 1280 ), 'Morning and evening', 'Four steps, one order', 'Cleanse, treat, revive, restore: the same four gestures, in the same order, every day.', '#hxpd-sci', 'The four steps' ),
	array( 'texture-application', array( 600, 800, 1200 ), 'The textures', 'From water to cream', 'A micellar water, a serum, an eye cream and a 24h cream, applied from the lightest to the richest.', '#hxpd-use', 'How to use' ),
);
?>

<section class="hxcr-intro" id="hxcr-intro" aria-labelledby="hxcr-intro-title">
	<div class="hx-wrap hxcr-intro__inner">
		<p class="hxcr-kicker" data-hx-reveal>The Complete Ritual</p>
		<h2 id="hxcr-intro-title" class="hx-heading hxcr-intro__title" data-hx-reveal>Four formulas, used in one order</h2>
		<p class="hx-lead hxcr-intro__lede" data-hx-reveal>Micellar Water All-in-One Cleanser, Advanced Repair Serum, Revitalizing Eye Cream and 24h Regeneration Cream: the whole routine, each full size, in one set.</p>
	</div>
</section>

<section class="hxcr-tri" aria-label="The ritual in three parts">
	<div class="hx-wrap">
		<ul class="hxcr-tri__list" data-hx-stagger>
			<?php foreach ( $tri as $t ) : ?>
			<li class="hxcr-tri__panel" data-hx-reveal>
				<div class="hxcr-tri__media<?php echo 'auth-ritual-spoon' === $t[0] ? ' hxcr-tri__media--whole' : ''; ?>"><?php echo cos_h26_picture( $t[0], $t[1], array( 3, 4 ), '', array( 'sizes' => '(min-width: 900px) 30vw, 88vw', 'w' => 720 ) ); // phpcs:ignore -- escaped in the helper ?></div>
				<p class="hxcr-kicker"><?php echo esc_html( $t[2] ); ?></p>
				<h3 class="hxcr-tri__title"><?php echo esc_html( $t[3] ); ?></h3>
				<p class="hx-body hxcr-tri__text"><?php echo esc_html( $t[4] ); ?></p>
				<a class="hx-link hxcr-tri__link" href="<?php echo esc_attr( $t[5] ); ?>"><?php echo esc_html( $t[6] ); ?><span class="hx-arrow" aria-hidden="true"></span></a>
			</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>

<section class="hxcr-band" aria-label="The ritual, morning and evening">
	<?php
	// 3 Nov 2026 (owner: "use a pic with a model from the photoshoots"): CAVIAR3602, the serum held to the
	// arm, model on the left and the studio wall extended to the right for the line (hxcr-model.origin.json)
	echo cos_h26_picture( 'hxcr-model-wide', array( 1440, 1920, 2560 ), array( 2560, 1280 ), 'A woman holding the Advanced Repair Serum against her arm', array( // phpcs:ignore -- escaped in the helper
		'sizes' => '100vw',
		'w'     => 1920,
		'class' => 'hxcr-band__pic',
		'art'   => array( array( 'media' => '(max-width: 699.98px)', 'base' => 'hxcr-model-tall', 'widths' => array( 540, 720, 1080 ), 'sizes' => '100vw' ) ),
	) );
	?>
	<p class="hxcr-band__line" data-hx-reveal>Each formula prepares the skin for the next, morning and evening.</p>
</section>

<section class="hxpd-sci hxcr-steps" id="hxpd-sci" aria-labelledby="hxpd-sci-title">
	<div class="hx-wrap">
		<div class="hxcr-steps__head">
			<p class="hxcr-kicker" data-hx-reveal>Cleanse · Treat · Revive · Restore</p>
			<h2 id="hxpd-sci-title" class="hx-heading hxcr-steps__title" data-hx-reveal>The four steps</h2>
			<p class="hx-body hxcr-steps__lede" data-hx-reveal>All four come full size, and each is also sold on its own.</p>
		</div>
		<?php // 3 Nov 2026 (owner ref: a four-card product sheet): 2 x 2 cards — a close-up detail photograph above
		// (sci-*-what), the owner's studio packshot standing on the seam (set-pack-*, true cut-outs,
		// set-pack.origin.json), a hairline corner, then the name, the copy and the price ?>
		<?php $hxcr_max = array( 'cleanser' => 863, 'serum' => 864, 'eye' => 863, 'cream' => 960 ); // largest sci-*-what export of each ?>
		<?php $hxcr_pack = array( 'cleanser' => array( 1026, 3858 ), 'serum' => array( 820, 2797 ), 'eye' => array( 758, 3081 ), 'cream' => array( 1658, 1664 ) ); ?>
		<ol class="hxcr-steps__list">
			<?php foreach ( $args['formulas'] as $f ) : $dim = $hxcr_pack[ $f['key'] ] ?? array( 1, 3 ); ?>
			<li class="hxcr-step hxcr-step--<?php echo esc_attr( $f['key'] ); ?>">
				<a class="hxcr-step__stage" href="<?php echo esc_url( $f['url'] ); ?>" tabindex="-1" aria-hidden="true">
					<span class="hxcr-step__detail"><?php echo cos_h26_picture( 'sci-' . $f['key'] . '-what', array( 480, 720, $hxcr_max[ $f['key'] ] ?? 720 ), array( 4, 5 ), '', array( 'sizes' => '(min-width: 1220px) 568px, (min-width: 760px) calc(50vw - 56px), calc(100vw - 32px)', 'w' => 720 ) ); // phpcs:ignore -- escaped in the helper ?></span>
					<span class="hxcr-step__corner"></span>
					<span class="hxcr-step__pack"><?php echo cos_h26_picture( 'set-pack-' . $f['key'], array( 240, 360, 480 ), $dim, '', array( 'sizes' => 'cream' === $f['key'] ? '160px' : '90px', 'w' => 360 ) ); // phpcs:ignore -- escaped in the helper ?></span>
				</a>
				<div class="hxcr-step__copy" data-hx-reveal><?php // the text rises in; the photographs are never faded (DESIGN.md motion) ?>
					<p class="hxcr-kicker">Step <?php echo esc_html( (string) (int) $f['num'] ); ?> <span aria-hidden="true">·</span> <?php echo esc_html( $f['verb'] ); ?></p>
					<h3 class="hxcr-step__name"><a href="<?php echo esc_url( $f['url'] ); ?>"><?php echo esc_html( $f['full'] ); ?></a></h3>
					<p class="hx-body hxcr-step__text"><?php echo esc_html( $f['copy'] ); ?></p>
					<p class="hxcr-step__meta"><?php if ( $f['price'] ) : ?><span class="hxcr-step__price"><?php echo esc_html( $f['price'] ); ?></span> <span aria-hidden="true">·</span> <?php endif; ?><?php echo esc_html( $f['size'] ); ?></p>
				</div>
			</li>
			<?php endforeach; ?>
		</ol>
	</div>
</section>

<section class="hxpd-use hxcr-gesture" id="hxpd-use" aria-labelledby="hxpd-use-title">
	<div class="hx-wrap hxcr-gesture__grid">
		<?php // 3 Nov 2026 (owner ref: a "how much product" swatch chart): the four textures at their amounts, each with a hairline callout.
		// Amounts are the product pages' own directions (inc/product-data.php). Swatches: hxcr-dose.origin.json. ?>
		<figure class="hxcr-dose" data-hx-reveal="settle">
			<?php
			$hxcr_dose = array(
				'cleanser' => array( 'Micellar Water', 'Enough to soak a cotton pad', array( 284, 284 ) ),
				'serum'    => array( 'Serum', '2–3 drops', array( 292, 292 ) ),
				'cream'    => array( '24h Cream', 'A hazelnut‑sized amount', array( 524, 339 ) ), // swatch: the owner's own cream smear photograph (3 Nov 2026), cut out, colour as supplied
				'eye'      => array( 'Eye Cream', 'A grain of rice per eye', array( 115, 114 ) ),
			);
			foreach ( $hxcr_dose as $k => $d ) :
				echo '<span class="hxcr-dose__sw hxcr-dose__sw--' . esc_attr( $k ) . '" aria-hidden="true">' . cos_h26_picture( 'hxcr-dose-' . $k, 'eye' === $k ? array( 115 ) : ( 'cream' === $k ? array( 240, 480, 524 ) : array( 240, $d[2][0] ) ), $d[2], '', array( 'sizes' => 'cream' === $k ? '(min-width: 900px) 18vw, 40vw' : '(min-width: 900px) 14vw, 34vw', 'w' => $d[2][0] ) ) . '</span>'; // phpcs:ignore -- escaped in the helper
			endforeach;
			?>
			<svg class="hxcr-dose__lines" viewBox="0 0 100 125" preserveAspectRatio="none" aria-hidden="true" focusable="false">
				<polyline points="64,36 90,36 90,17" /><circle cx="64" cy="36" r=".7" />
				<polyline points="31,50 5,50" /><circle cx="31" cy="50" r=".7" />
				<polyline points="33,86 33,104 4,104" /><circle cx="33" cy="86" r=".7" />
				<polyline points="74,80 86,80 86,95" /><circle cx="74" cy="80" r=".7" />
			</svg>
			<?php foreach ( $hxcr_dose as $k => $d ) : ?>
			<p class="hxcr-dose__label hxcr-dose__label--<?php echo esc_attr( $k ); ?>"><span class="hxcr-dose__name"><?php echo esc_html( $d[0] ); ?></span><span class="hxcr-dose__amt"><?php echo esc_html( $d[1] ); ?></span></p>
			<?php endforeach; ?>
			<figcaption class="hx-sr">How much of each formula to use</figcaption>
		</figure>
		<div class="hxcr-gesture__body">
			<p class="hxcr-kicker" data-hx-reveal>The gesture</p>
			<h2 id="hxpd-use-title" class="hx-heading hxcr-gesture__title" data-hx-reveal>How to use</h2>
			<ol class="hxcr-gesture__steps">
				<?php foreach ( $args['formulas'] as $f ) : ?>
				<li class="hxcr-gesture__step" data-hx-reveal><span class="hxcr-gesture__num" aria-hidden="true"><?php echo esc_html( $f['num'] ); ?></span><p class="hx-body"><strong><?php echo esc_html( $f['verb'] ); ?>.</strong> <?php echo esc_html( $f['full'] ); ?>: <?php echo esc_html( lcfirst( $f['cue'] ) ); ?></p></li>
				<?php endforeach; ?>
			</ol>
		</div>
	</div>
</section>

<section class="hxpd-orbs hxpd-orbs--set" id="hxpd-actives" aria-labelledby="hxpd-actives-title">
	<div class="hx-wrap hxpd-orbs__grid">
		<div class="hxpd-orbs__head">
			<h2 id="hxpd-actives-title" class="hx-heading hxpd-orbs__title" data-hx-reveal>What you put on your skin matters</h2>
			<p class="hx-body hxpd-orbs__lede" data-hx-reveal>Caviar extract is in all four formulas. The key ingredients of each are on its own page.</p>
		</div>
		<div class="hxpd-orbs__lists" id="hxpd-ingredients">
			<?php foreach ( $args['formulas'] as $f ) : ?>
			<details class="hxpd-acc__item">
				<summary class="hxpd-acc__sum"><?php echo esc_html( $f['full'] ); ?><span class="hxpd-acc__icon" aria-hidden="true"></span></summary>
				<p class="hxpd-inci"><?php echo esc_html( $f['inci'] ); ?></p>
			</details>
			<?php endforeach; ?>
			<p class="hxpd-inci__note">Refer to the packaging for the most up-to-date lists.</p>
		</div>
	</div>
</section>

<?php
// 3 Nov 2026 (owner ref: a results band over a strip of texture close-ups): "Measured, formula by formula"
// on a soft pearl ground, the two instrument-measured results as serif figures and the cream's
// self-reported result in words (it never takes a figure, DESIGN.md), then the four textures edge to edge.
// Textures (hxcr-tex.origin.json): the serum is the owner's photograph (DSC_6612); cleanser, eye cream and
// 24h cream are Unsplash-licence photographs (the note under the strip was removed by the owner, 3 Nov 2026).
$hxcr_tex = array(
	'cleanser' => 'Rippling clear water, illustrating the micellar water',
	'serum'    => 'The Advanced Repair Serum dispensed onto a fingertip',
	'eye'      => 'A smear of white cream, illustrating the eye cream',
	'cream'    => 'A smear of rich white cream, illustrating the 24h cream',
);
?>
<section class="hxpd-result hxcr-tex" id="hxpd-result" aria-labelledby="hxpd-result-title">
	<div class="hxcr-tex__sky">
		<div class="hx-wrap">
			<div class="hxcr-tex__head">
				<h2 id="hxpd-result-title" class="hx-heading" data-hx-reveal>Measured, formula by formula</h2>
				<p class="hx-body hxcr-tex__lede" data-hx-reveal>Three of the four formulas were each studied on their own, by an independent contract research organisation. The ritual has not been studied as a set, and the cleanser has no study.</p>
			</div>
			<ul class="hxcr-tex__results" data-hx-stagger>
				<?php foreach ( $studies as $s ) : $measured = 'Instrument-measured' === $s['kind']; ?>
				<li class="hxcr-tex__result<?php echo $measured ? '' : ' hxcr-tex__result--reported'; ?>" data-hx-reveal>
					<?php if ( $measured ) : ?>
					<p class="hx-numeral hxcr-tex__n" aria-hidden="true"><?php echo esc_html( $s['sign'] . $s['n'] ); ?><span class="hxcr-tex__unit"><?php echo esc_html( $s['unit'] ); ?></span></p>
					<h3 class="hxcr-tex__finding"><span class="hx-sr"><?php echo esc_html( $s['sign'] . $s['n'] . $s['unit'] ); ?> </span><?php echo esc_html( $s['finding'] ); ?></h3>
					<?php else : ?>
					<p class="hxcr-tex__words"><?php echo esc_html( $s['n'] . $s['unit'] ); ?> panellist-reported satisfaction</p>
					<h3 class="hxcr-tex__finding">Overall satisfaction</h3>
					<?php endif; ?>
					<p class="hxcr-tex__product"><?php echo esc_html( $s['product'] ); ?></p>
					<p class="hxcr-tex__method"><?php echo esc_html( $s['kind'] . ' · ' . $s['method'] . ', ' . $s['panel'] . ', 28 days' ); ?></p>
				</li>
				<?php endforeach; ?>
			</ul>
			<p class="hxcr-tex__more" data-hx-reveal><a class="hx-link" href="<?php echo esc_url( $args['clinical'] ); ?>">Read the studies<span class="hx-arrow" aria-hidden="true"></span></a></p>
		</div>
	</div>
	<ul class="hxcr-tex__strip" aria-label="The four textures">
		<?php foreach ( $args['formulas'] as $f ) : ?>
		<li class="hxcr-tex__panel hxcr-tex__panel--<?php echo esc_attr( $f['key'] ); ?>">
			<a class="hxcr-tex__link" href="<?php echo esc_url( $f['url'] ); ?>">
				<?php echo cos_h26_picture( 'hxcr-tex-' . $f['key'], array( 480, 720, 1080 ), array( 3, 4 ), $hxcr_tex[ $f['key'] ] ?? '', array( 'class' => 'hxcr-tex__pic', 'sizes' => '(min-width: 760px) 25vw, 50vw', 'w' => 720 ) ); // phpcs:ignore -- escaped in the helper ?>
				<span class="hxcr-tex__cap" data-hx-reveal><span class="hxcr-tex__step">Step <?php echo esc_html( (string) (int) $f['num'] ); ?> · <?php echo esc_html( $f['verb'] ); ?></span><span class="hxcr-tex__name"><?php echo esc_html( $f['full'] ); ?></span></span>
			</a>
		</li>
		<?php endforeach; ?>
	</ul>
</section>

<section class="hxpd-faq" id="hxpd-faq" aria-labelledby="hxpd-faq-title">
	<div class="hx-wrap hxpd-faq__grid">
		<h2 id="hxpd-faq-title" class="hx-heading hxpd-faq__title" data-hx-reveal>Questions</h2>
		<div class="hxpd-faq__list">
			<?php foreach ( $faqs as $faq ) : ?>
			<details class="hxpd-acc__item hxpd-faq__item">
				<summary class="hxpd-acc__sum hxpd-faq__q"><?php echo esc_html( $faq[0] ); ?><span class="hxpd-acc__icon" aria-hidden="true"></span></summary>
				<p class="hx-body hxpd-faq__a"><?php echo esc_html( $faq[1] ); ?></p>
			</details>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<section class="hxpd-steps hxcr-line" id="hxpd-line" aria-labelledby="hxpd-line-title">
	<div class="hx-wrap">
		<div class="hxpd-steps__head hxcr-line__head">
			<p class="hxcr-kicker" data-hx-reveal>Discover the line</p>
			<h2 id="hxpd-line-title" class="hx-heading hxpd-steps__title" data-hx-reveal>Each formula on its own</h2>
		</div>
		<?php get_template_part( 'template-parts/home-2026/collection', null, array( 'embed' => true ) ); ?>
	</div>
</section>
