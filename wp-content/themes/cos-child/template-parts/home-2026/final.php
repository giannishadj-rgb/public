<?php
/**
 * 08 — The Ritual: the closing section, after the evidence (owner, 4 Oct 2026:
 * proof before price). Renamed from "The Complete Ritual" and redesigned
 * 11 Oct 2026 after the owner's editorial-board reference: a text panel
 * ("The" upright, "Ritual" in italic) beside a portrait (DSC_6518b), then the
 * four steps as numbered tiles: the step, a model using that formula, and
 * what the step does and how (owner: explain the step, not the product name),
 * then a band that closes the
 * board with the one call to action between two hairlines: the Complete
 * Ritual bundle (the product keeps its name; owner, 11 Oct 2026: no price or
 * comparison here, the product page carries them) and the two service
 * assurances (owner: only these two, 6 Oct 2026). Phones: tiles 2×2.
 * The portrait drifts (parallax, see home-2026.js). Styles: .h26r in
 * assets/css/home-2026.css. Earlier versions: design-sources/home-2026/alt-final-*.
 */
defined( 'ABSPATH' ) || exit;

// Service assurances under the CTA (terms from /shipping-returns/).
$assurances = array(
	array( 'Free worldwide shipping', '<circle cx="16" cy="16" r="10"/><path d="M6 16h20M16 6c3 3 4.5 6.4 4.5 10S19 23 16 26c-3-3-4.5-6.4-4.5-10S13 9 16 6Z"/>' ),
	array( '30-day returns', '<path d="M9 13h11a6 6 0 0 1 0 12h-7"/><path d="m13 9-4 4 4 4"/>' ),
);
// Each step: a model using that formula (the card-* studio set) and what the step does
// (owner, 11 Oct 2026: explain the step, not the product name). Wording drawn from each
// product's 'how' in inc/product-data.php; cosmetic description only (no regeneration claims).
$steps = array(
	'micellar-water-cleanser' => array( 'card-cleanser', 'A woman in a black halter top holding the Micellar Water All-in-One Cleanser against her arm', 'Sweep a soaked cotton pad over face and neck to lift make-up and the day’s impurities. No rinsing: the skin is ready for what follows.' ),
	'advanced-repair-serum'   => array( 'card-serum', 'A woman with her eyes closed resting her head on her hands, holding the Advanced Repair Serum', 'The most concentrated step. Press two or three drops into face and neck, then give them a minute to absorb.' ),
	'revitalizing-eye-cream'  => array( 'card-eye', 'A woman holding the Revitalizing Eye Cream up beside her eye', 'Care for the thinnest skin of the face. Pat a little along the orbital bone, from the inner to the outer corner. Never drag.' ),
	'24h-regeneration-cream'  => array( 'card-cream', 'A smiling woman resting her head on her arm, her hand on the 24h Regeneration Cream jar', 'The final step seals the ritual. Warm a pea-sized amount between your palms and press it in, under SPF by day and overnight by night.' ),
);
?>
<section class="h26r" aria-labelledby="h26-final-title">
	<div class="h26r__board">
		<div class="h26r__intro">
			<p class="h26r__kicker">Morning &amp; evening</p>
			<span class="h26r__rule" aria-hidden="true"></span>
			<h2 id="h26-final-title" class="h26r__title"><span>The</span> <em>Ritual</em></h2>
			<?php /* Who it suits (owner, 5 Oct 2026; COS-BUNDLE who_for/concerns in inc/product-data.php). */ ?>
			<p class="h26r__lede">Four full-size formulas, one after another. For loss of firmness, fine lines and dryness. Gentle on most skin types.</p>
		</div>
		<figure class="h26r__model" data-h26-parallax=".05">
			<?php echo cos_h26_picture( 'ritual-model', array( 640, 960, 1280 ), array( 4, 5 ), 'A woman with her eyes closed and her head tilted back, her skin luminous in warm light', array( 'sizes' => '(min-width: 900px) 50vw, 100vw', 'w' => 960 ) ); ?>
		</figure>
		<ol class="h26r__steps" aria-label="The four steps">
			<?php foreach ( cos_h26_products() as $p ) : ?>
			<li class="h26r__tile">
				<?php $st = $steps[ $p['slug'] ]; ?>
				<p class="h26r__step"><span class="h26r__num"><?php echo esc_html( (int) $p['num'] ); ?></span><span class="h26r__verb"><?php echo esc_html( $p['verb'] ); ?></span></p>
				<?php echo cos_h26_picture( $st[0], array( 480, 720, 960 ), array( 4, 5 ), $st[1], array( 'class' => 'h26r__shot', 'sizes' => '(min-width: 900px) 25vw, 50vw', 'w' => 720 ) ); ?>
				<p class="h26r__how"><?php echo esc_html( str_replace( '-', "\u{2011}", $st[2] ) ); // non-breaking hyphens: "make-up", "pea-sized" never split ?></p>
			</li>
			<?php endforeach; ?>
		</ol>
	</div>

	<div class="h26r__close">
		<span class="h26r__line" aria-hidden="true"></span>
		<div class="h26r__offer">
			<a class="h26-btn h26-btn--dark h26r__cta" href="<?php echo esc_url( home_url( '/products/the-complete-ritual/' ) ); ?>" data-cro-cta="home-final-ritual">Shop the Complete Ritual <?php echo cos_h26_arrow(); ?></a>
			<ul class="h26r__facts" role="list" aria-label="Delivery and returns">
				<?php foreach ( $assurances as [ $k, $icon ] ) : ?>
				<li class="h26r__fact"><svg viewBox="0 0 32 32" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><?php echo $icon; // static markup above ?></svg><?php echo esc_html( $k ); ?></li>
				<?php endforeach; ?>
			</ul>
		</div>
		<span class="h26r__line" aria-hidden="true"></span>
	</div>
</section>
