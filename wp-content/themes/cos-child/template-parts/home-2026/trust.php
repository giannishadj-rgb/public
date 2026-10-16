<?php
/**
 * 04 — Swiss science: "Protect. Repair. Rejuvenate." (14 Oct 2026 rebuild, science agent;
 * round 1 critique: no icon diagrams, one sentence per verb, upright heading;
 * round 2: each instrument-measured figure stands beside its own product).
 *
 * The three verbs are the statement: set large in Cormorant across one row, under a
 * single hairline measuring scale (one tick per verb) that opens left→right once in view.
 * Each verb carries one sentence; the ingredient names live in the small line beneath.
 * Then the measured results as large serif numerals with quiet method notes.
 *
 * Claim sources (keep in step with them):
 *   Verbs — ingredient roles as published on /science/the-high-tech-complex/ (page 346)
 *     and PRODUCT.md: caviar extract micro-encapsulated (potency protected); SOD +
 *     Resveratrol (antioxidant defence); phospholipids close to skin's own lipids
 *     (barrier); hyaluronic acid in three molecular weights (three depths); SYN®-COLL
 *     (collagen research — in-vitro only, so NO figure). Cosmetic wording only.
 *   Results — /science/clinical-studies/ (page 347), BRIEF §4. Each figure stays with its
 *     single product, method and panel; separate per-product studies; no complete-ritual
 *     study and none for the cleanser; the cream's figure is self-reported and secondary.
 *     The CRO is not named (PRODUCT.md: undecided).
 * Styles: assets/css/hx/science.css (prefix hxs-).
 */
defined( 'ABSPATH' ) || exit;

$pillars = array(
	array( 'Protect', 'Micro‑encapsulated caviar keeps its potency, while two antioxidants help defend skin against daily environmental stress.', array( 'Caviar extract', 'Superoxide Dismutase', 'Resveratrol' ) ),
	array( 'Repair', 'Caviar lipids, close to the skin’s own, help replenish its barrier as hyaluronic acid holds moisture at three depths.', array( 'Caviar phospholipids', 'Hyaluronic acid' ) ),
	array( 'Rejuvenate', 'A peptide studied in collagen research joins caviar extract, for skin that looks firmer and smoother.', array( 'SYN®‑COLL peptide', 'Caviar extract' ) ),
);

// sign + number, finding, product, method, panel, product image key — page 347.
$measured = array(
	array( '+47', 'Skin firmness', 'Advanced Repair Serum', 'Cutometry', '32 women <span>aged 45–60</span>', 'serum' ),
	array( '−38', 'Crow’s‑feet depth', 'Revitalizing Eye Cream', 'Optical profilometry', '30 women <span>aged 42–65</span>', 'eye' ),
);
?>
<section id="evidence" class="hxs" aria-labelledby="hxs-title">
	<div class="hxs__wrap hx-wrap">
		<p class="hx-label hxs__label" data-hx-reveal="fade">Swiss science</p>
		<h2 id="hxs-title" class="hx-sr">Protect. Repair. Rejuvenate.</h2>

		<div class="hxs__scale" data-hx-reveal="mask-x" aria-hidden="true"><span></span><span></span><span></span></div>
		<ol class="hxs__pillars" role="list" data-hx-stagger>
			<?php foreach ( $pillars as $p ) : ?>
			<li class="hxs__pillar" data-hx-reveal>
				<h3 class="hx-heading hxs__verb"><?php echo esc_html( $p[0] ); ?>.</h3>
				<p class="hx-body hxs__text"><?php echo esc_html( $p[1] ); ?></p>
				<p class="hx-detail hxs__actives"><span class="hx-sr">Key actives: </span><?php
				$last = count( $p[2] ) - 1;
				foreach ( $p[2] as $i => $a ) {
					// the separator travels with the name before it, so a wrapped line never starts with "·"
					echo '<span class="hxs__active">' . esc_html( $a ) . ( $i < $last ? '<span aria-hidden="true"> ·</span><span class="hx-sr">,</span>' : '' ) . '</span>' . ( $i < $last ? ' ' : '' );
				}
				?></p>
			</li>
			<?php endforeach; ?>
		</ol>

		<div class="hxs__proof">
			<div class="hxs__proof-head" data-hx-reveal>
				<h3 class="hxs__proof-title">Measured, formula by formula.</h3>
				<p class="hx-detail hxs__proof-note">Separate 28‑day studies, each of a single formula, by an independent contract research organisation.</p>
			</div>
			<div class="hxs__proof-body">
				<ul class="hxs__results" role="list" data-hx-stagger>
					<?php foreach ( $measured as $m ) : [ $fig, $finding, $product, $method, $panel, $obj ] = $m; ?>
					<li class="hxs__result" data-hx-reveal>
						<?php /* the owner's product, unaltered (transparent AVIF/WebP; JPEG fallback on the same #FBFAF6 ground); decorative — the name follows in text */ ?>
						<?php echo cos_h26_picture( 'collection-object-' . $obj, array( 480, 720, 960 ), array( 2, 3 ), '', array( 'class' => 'hxs__object', 'sizes' => '(min-width: 1100px) 160px, 112px', 'w' => 480 ) ); // phpcs:ignore ?>
						<p class="hxs__product"><?php echo esc_html( $product ); ?></p>
						<p class="hx-numeral hxs__fig-n"><?php echo esc_html( $fig ); ?><span class="hxs__pct">%</span></p>
						<p class="hxs__finding"><?php echo esc_html( $finding ); ?></p>
						<p class="hx-detail hxs__method"><span class="hxs__kind">Instrument‑<wbr>measured</span><span class="hx-sr">:</span> <?php echo esc_html( $method ); ?>, <?php echo wp_kses( $panel, array( 'span' => array() ) ); ?>, <span>28 days</span></p>
					</li>
					<?php endforeach; ?>
					<li class="hxs__result hxs__result--reported" data-hx-reveal>
						<p class="hxs__product">24h Regeneration Cream</p>
						<p class="hxs__fig-n hxs__fig-n--sm">100<span class="hxs__pct">%</span></p>
						<p class="hxs__finding">Panellist‑reported satisfaction</p>
						<p class="hx-detail hxs__method"><span class="hxs__kind">Self‑reported</span><span class="hx-sr">:</span> 34 women <span>aged 40–65</span>, <span>28 days</span></p>
					</li>
				</ul>
				<p class="hxs__more" data-hx-reveal><a class="hx-link" href="<?php echo esc_url( home_url( '/science/clinical-studies/' ) ); ?>">Read the studies<span class="hx-arrow" aria-hidden="true"></span></a></p>
			</div>
		</div>
	</div>
</section>
