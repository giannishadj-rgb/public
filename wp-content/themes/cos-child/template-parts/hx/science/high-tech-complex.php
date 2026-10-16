<?php
/**
 * /science/high-tech-complex/ (page 346) — chapter 02. The five actives in depth, each tagged with
 * its role (Protect / Repair / Rejuvenate); hyaluronic acid carries the one diagram that clarifies
 * (three molecular weights at three depths); SYN®-COLL's 4.5× is shown as labelled in-vitro
 * ingredient research, apart from any product result. Then the synergy, as four relations.
 */
defined( 'ABSPATH' ) || exit;

get_template_part( 'template-parts/hx/science/masthead', null, array(
	'label' => 'Chapter 02 <span aria-hidden="true">·</span> The High-Tech Complex',
	'title' => 'Engineered for synergy.',
	'lead'  => 'Most luxury skincare adds actives for marketing value. We selected five for clinical evidence, bioavailability and synergistic interaction. Each one does something the others cannot. Together, they address the full spectrum of skin ageing.',
	'plate' => array(
		'base' => 'caviar-macro-light', 'widths' => array( 1440, 2400 ), 'ratio' => array( 3, 2 ),
		'alt'  => 'A clear droplet of gel holding a cluster of fine bubbles on a pale surface',
	),
) );

$pillars = hxsc_pillars();
?>

<section class="hxsc-sec" aria-labelledby="htc-actives">
	<div class="hx-wrap">
		<div class="hxsc-sec__head hxsc-sec__head--wide" data-hx-reveal>
			<p class="hx-label hxsc-sec__label">The five actives, in depth</p>
			<h2 id="htc-actives" class="hx-heading hxsc-sec__title">Each selected for a specific, evidenced mechanism.</h2>
		</div>
		<ol class="hxsc-actives" role="list">
			<?php foreach ( hxsc_actives() as $a ) : ?>
			<li class="hxsc-active" id="<?php echo esc_attr( $a['slug'] ); ?>" data-hx-reveal>
				<div class="hxsc-active__head">
					<span class="hxsc-active__n" aria-hidden="true"><?php echo esc_html( $a['num'] ); ?></span>
					<h3 class="hx-title hxsc-active__name"><?php echo esc_html( $a['name'] ); ?></h3>
					<p class="hxsc-active__inci"><span class="hx-sr">INCI: </span><?php echo esc_html( $a['inci'] ); ?></p>
					<p class="hxsc-tags"><span class="hx-sr">Role: </span><?php foreach ( $a['roles'] as $r ) echo '<span class="hxsc-tag">' . esc_html( $pillars[ $r ][0] ) . '</span> '; ?></p>
				</div>
				<div class="hxsc-active__body hxsc-prose">
					<p><?php echo hxsc_kses( $a['desc'] ); ?></p>
					<?php if ( ! empty( $a['depth'] ) ) : ?>
					<figure class="hxsc-depth">
						<figcaption class="hxsc-depth__cap">Three molecular weights, three depths</figcaption>
						<ol class="hxsc-depth__rows" role="list">
							<?php foreach ( $a['depth'] as $d => $row ) : ?>
							<li class="hxsc-depth__row hxsc-depth__row--<?php echo (int) $d; ?>">
								<span class="hxsc-depth__layer"><?php echo esc_html( $row[2] ); ?></span>
								<span class="hxsc-depth__dot" aria-hidden="true"></span>
								<span class="hxsc-depth__mw"><strong><?php echo esc_html( $row[0] ); ?></strong> <?php echo esc_html( $row[1] ); ?></span>
								<span class="hxsc-depth__d"><?php echo esc_html( $row[3] ); ?></span>
							</li>
							<?php endforeach; ?>
						</ol>
					</figure>
					<?php endif; ?>
					<?php if ( ! empty( $a['vitro'] ) ) : ?>
					<aside class="hxsc-vitro" aria-label="Ingredient research, in vitro">
						<p class="hxsc-vitro__k">Ingredient research · in vitro</p>
						<p class="hxsc-vitro__fig"><span class="hxsc-vitro__n"><?php echo esc_html( $a['vitro'][0] ); ?></span> <span><?php echo esc_html( $a['vitro'][1] ); ?></span></p>
						<p class="hxsc-vitro__note"><?php echo esc_html( $a['vitro'][2] ); ?></p>
					</aside>
					<?php endif; ?>
					<p class="hxsc-mech"><span class="hxsc-mech__k">Mechanism</span> <?php echo hxsc_kses( $a['mech'] ); ?></p>
					<p><a class="hx-link" href="<?php echo hxsc_url( '/science/ingredients/' . $a['slug'] . '/' ); ?>">Full ingredient profile<span class="hx-sr">: <?php echo esc_html( $a['name'] ); ?></span><?php echo hxsc_arrow(); // phpcs:ignore ?></a></p>
				</div>
			</li>
			<?php endforeach; ?>
		</ol>
	</div>
</section>

<section class="hxsc-sec hxsc-sec--tint" aria-labelledby="htc-syn">
	<div class="hx-wrap hxsc-sec__grid">
		<div class="hxsc-sec__head" data-hx-reveal>
			<p class="hx-label hxsc-sec__label">Why these five?</p>
			<h2 id="htc-syn" class="hx-heading hxsc-sec__title">Synergy is not a marketing word.</h2>
		</div>
		<div class="hxsc-prose" data-hx-reveal>
			<p>The five actives in the High-Tech Complex do not merely coexist. They complement each other at the mechanistic level.</p>
			<ol class="hxsc-rel" role="list">
				<li><span class="hxsc-rel__a">Caviar Extract</span><span class="hxsc-rel__line" aria-hidden="true"></span><span class="hxsc-rel__b">SOD</span><span class="hxsc-rel__t">Caviar Extract rebuilds the lipid matrix that SOD must penetrate to do its enzymatic work.</span></li>
				<li><span class="hxsc-rel__a">Caviar Extract</span><span class="hxsc-rel__line" aria-hidden="true"></span><span class="hxsc-rel__b">Syn®‑Coll Peptide</span><span class="hxsc-rel__t">Syn®-Coll Peptide stimulates the collagen synthesis that Caviar Extract provides the raw materials for.</span></li>
				<li><span class="hxsc-rel__a">Hyaluronic Acid</span><span class="hxsc-rel__line" aria-hidden="true"></span><span class="hxsc-rel__b">Syn®‑Coll Peptide</span><span class="hxsc-rel__t">Hyaluronic Acid creates the hydrated environment in which peptide-receptor interactions are more efficient.</span></li>
				<li><span class="hxsc-rel__a">Resveratrol</span><span class="hxsc-rel__line" aria-hidden="true"></span><span class="hxsc-rel__b">Syn®‑Coll Peptide</span><span class="hxsc-rel__t">Resveratrol activates sirtuin pathways that protect the fibroblasts that Syn®-Coll Peptide signals.</span></li>
			</ol>
			<p>This is why we use all five in every product, at different concentrations calibrated for each formula’s primary function. The serum delivers the highest active concentration. The cream maintains it. The eye cream focuses it. The cleanser primes for it. That is how a system works. That is what makes it a ritual.</p>
			<p class="hxsc-ctas"><a class="hx-btn" href="<?php echo hxsc_url( '/the-ritual/' ); ?>">Discover the complete ritual<?php echo hxsc_arrow(); // phpcs:ignore ?></a></p>
		</div>
	</div>
</section>
