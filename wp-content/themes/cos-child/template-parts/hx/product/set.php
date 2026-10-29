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
$sz      = '(min-width: 900px) 23vw, 80vw';

$faqs = array(
	array( 'Do I have to use all four formulas together?', 'Each formula works on its own, but they are made to be used in sequence: the cleanser prepares the skin for the serum, the eye cream cares for the eye contour the other steps are not formulated for, and the 24h cream seals the routine.' ),
	array( 'What order do I apply them in?', 'Morning and evening: Micellar Water All-in-One Cleanser, then Advanced Repair Serum, then Revitalizing Eye Cream, then 24h Regeneration Cream. A written ritual guide comes with the set.' ),
	array( 'Are the clinical results for the ritual as a whole?', 'No. Advanced Repair Serum, Revitalizing Eye Cream and 24h Regeneration Cream were each studied on their own, in separate 28-day studies. The four formulas have not been studied together as a set, and the cleanser has not been clinically studied.' ),
	array( 'Is this a good gift?', 'Yes. The set is presented in the Caviar of Switzerland ritual box with a written ritual guide, ready to give.' ),
	array( 'What if I only need one or two steps?', 'Each formula is also sold on its own. The Complete Ritual is for anyone starting the full routine, and costs less than buying the four separately.' ),
);

$parts = array(
	array( 'hxpd-sci', 'The four steps' ),
	array( 'hxpd-actives', 'Ingredients' ),
	array( 'hxpd-result', 'Results' ),
	array( 'hxpd-use', 'How to use' ),
	array( 'hxpd-faq', 'Questions' ),
);
?>
<nav class="hxpd-secnav" aria-label="On this page">
	<div class="hx-wrap"><ol class="hxpd-secnav__list">
		<?php foreach ( $parts as $p ) : ?><li><a class="hxpd-secnav__link" href="#<?php echo esc_attr( $p[0] ); ?>"><?php echo esc_html( $p[1] ); ?></a></li><?php endforeach; ?>
	</ol></div>
</nav>

<section class="hxpd-sci hxpd-sci--set" id="hxpd-sci" aria-labelledby="hxpd-sci-title">
	<div class="hx-wrap">
		<div class="hxpd-sci__head">
			<h2 id="hxpd-sci-title" class="hx-heading hxpd-sci__title" data-hx-reveal>The four steps</h2>
			<p class="hx-lead hxpd-sci__lede" data-hx-reveal>Each formula prepares the skin for the next, morning and evening. All four come full-size in the Caviar of Switzerland ritual box, with a written ritual guide, and each is also sold on its own.</p>
		</div>
		<ol class="hxpd-sci__list" data-hx-stagger>
			<?php foreach ( $args['formulas'] as $f ) : ?>
			<li class="hxpd-sci__card" data-hx-reveal>
				<a class="hxpd-sci__media" href="<?php echo esc_url( $f['url'] ); ?>" tabindex="-1" aria-hidden="true"><?php echo cos_h26_picture( 'ritual-cut-' . $f['key'], array( 360, 540, 720 ), array( 3, 4 ), '', array( 'sizes' => $sz, 'w' => 720, 'class' => 'hxpd-sci__pack' ) ); // phpcs:ignore -- escaped in the helper ?></a>
				<p class="hxpd-sci__step">Step <?php echo esc_html( (string) (int) $f['num'] ); ?> <span aria-hidden="true">·</span> <?php echo esc_html( $f['verb'] ); ?></p>
				<h3 class="hxpd-sci__name"><a href="<?php echo esc_url( $f['url'] ); ?>"><?php echo esc_html( $f['full'] ); ?></a></h3>
				<p class="hxpd-sci__text"><?php echo esc_html( $f['copy'] ); ?></p>
				<p class="hxpd-sci__meta"><?php echo esc_html( $f['size'] ); ?><?php if ( $f['price'] ) : ?> <span aria-hidden="true">·</span> <?php echo esc_html( $f['price'] ); ?> on its own<?php endif; ?></p>
			</li>
			<?php endforeach; ?>
		</ol>
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

<section class="hxpd-result hxpd-result--set" id="hxpd-result" aria-labelledby="hxpd-result-title">
	<div class="hx-wrap">
		<div class="hxpd-ritual__head">
			<h2 id="hxpd-result-title" class="hx-heading" data-hx-reveal>Measured, formula by formula</h2>
			<p class="hx-body hxpd-ritual__lede" data-hx-reveal>Three of the four formulas were each studied on their own, by an independent contract research organisation. The ritual has not been studied as a set, and the cleanser has no study.</p>
		</div>
		<ul class="hxpd-results" data-hx-stagger>
			<?php foreach ( $studies as $s ) : ?>
			<li class="hxpd-results__item" data-hx-reveal>
				<p class="hxpd-results__product"><?php echo esc_html( $s['product'] ); ?></p>
				<p class="hx-numeral hxpd-results__n"><?php echo esc_html( $s['sign'] . $s['n'] ); ?><span class="hxpd-result__unit"><?php echo esc_html( $s['unit'] ); ?></span></p>
				<p class="hxpd-results__finding"><?php echo esc_html( $s['finding'] ); ?></p>
				<p class="hx-detail hxpd-results__method"><span class="hxpd-results__kind"><?php echo esc_html( $s['kind'] ); ?></span> <?php echo esc_html( $s['method'] . ', ' . $s['panel'] . ', 28 days' ); ?></p>
			</li>
			<?php endforeach; ?>
		</ul>
		<p class="hxpd-result__more"><a class="hx-link" href="<?php echo esc_url( $args['clinical'] ); ?>">Read the studies<span class="hx-arrow" aria-hidden="true"></span></a></p>
	</div>
</section>

<section class="hxpd-use hxpd-use--set" id="hxpd-use" aria-labelledby="hxpd-use-title">
	<div class="hx-wrap hxpd-use__grid">
		<div class="hxpd-use__body">
			<h2 id="hxpd-use-title" class="hx-heading hxpd-use__title" data-hx-reveal>How to use</h2>
			<ol class="hxpd-use__steps">
				<?php foreach ( $args['formulas'] as $f ) : ?>
				<li class="hxpd-use__step" data-hx-reveal><span class="hx-step" aria-hidden="true"><?php echo esc_html( $f['num'] ); ?></span><p class="hx-body"><strong><?php echo esc_html( $f['verb'] ); ?>.</strong> <?php echo esc_html( $f['full'] ); ?>: <?php echo esc_html( lcfirst( $f['cue'] ) ); ?></p></li>
				<?php endforeach; ?>
			</ol>
		</div>
		<figure class="hxpd-use__media hx-frame" data-hx-reveal="settle"><?php echo cos_hxpd_attachment_picture( 23722, 'Micellar Water All-in-One Cleanser held in two hands, the first step of the ritual', '(min-width: 1440px) 560px, 40vw' ); // phpcs:ignore ?></figure>
	</div>
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
