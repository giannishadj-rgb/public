<?php
/**
 * /science/why-caviar/ (page 345) — chapter 01 (round 2, 17 Oct: cut ~45%; one essay kept — the
 * biology — with the comparison table and nutrient grid removed; cosmetic wording only).
 */
defined( 'ABSPATH' ) || exit;

get_template_part( 'template-parts/hx/science/masthead', null, array(
	'mode'  => 'split',
	'label' => 'Chapter 01 <span aria-hidden="true">·</span> Why caviar',
	'title' => 'A lipid profile close to skin’s own.',
	'lead'  => 'Caviar Extract is not a luxury ingredient in the cosmetic sense; it is a biologically precise one. The phospholipids of Acipenser Baerii roe mirror those of the skin’s own barrier with a fidelity no synthetic blend replicates.',
	'plate' => array(
		'base' => 'caviar-backlit', 'widths' => array( 960, 1440, 1920 ), 'ratio' => array( 3, 2 ),
		'alt'  => 'Glossy black caviar pearls, backlit on a pale surface',
		'pos'  => '62% 50%',
	),
) );

$products = cos_h26_products();
$steps    = array(
	'The micellar system lifts impurities while caviar phospholipids replenish the lipids that cleansing removes.',
	'The highest concentration of caviar extract, micro-encapsulated for time-release.',
	'Calibrated for the thinnest skin of the face, at concentrations suited to its lipid needs.',
	'The sealing step: a caviar lipid complex that sustains the barrier between applications.',
);
?>

<section class="hxsc-sec" aria-labelledby="wc-bio">
	<div class="hx-wrap hxsc-sec__grid">
		<div class="hxsc-sec__head" data-hx-reveal>
			<h2 id="wc-bio" class="hx-heading hxsc-sec__title">Sturgeon roe is not glamorous by accident. It is nutrient-dense by biological necessity.</h2>
		</div>
		<div class="hxsc-prose" data-hx-reveal>
			<p>A fertilised sturgeon egg must contain everything required to build a fully functional organism from a single cell: phospholipids, amino acids, nucleic acids, vitamins and trace minerals, in concentrations that are, by necessity, extraordinarily high.</p>
			<p>Its phospholipids are what make it relevant to skin. The composition of caviar roe, dominated by phosphatidylcholine, phosphatidylethanolamine and phosphatidylserine, mirrors the lipids of the skin’s own barrier with a precision that synthetic lipid blends cannot achieve.</p>
			<p>This is the meaning of “biomimetic”: not loosely analogous, but chemically near-identical in the components that matter. When the skin’s lipids are depleted, by cleansing, environment or age, caviar phospholipids help replenish them with the same kind of molecules that were lost.</p>
			<blockquote class="hxsc-quote"><p>The phospholipid profile of Acipenser Baerii caviar roe is the closest naturally occurring match to human skin’s intercellular lipid matrix identified in the published literature. That is the basis of the biomimetic claim.</p></blockquote>
		</div>
	</div>
</section>

<section class="hxsc-sec hxsc-sec--tint" aria-labelledby="wc-src">
	<div class="hx-wrap hxsc-sec__grid">
		<div class="hxsc-sec__head" data-hx-reveal>
			<h2 id="wc-src" class="hx-heading hxsc-sec__title">Sustainably farmed. Fully traceable.</h2>
		</div>
		<div class="hxsc-prose" data-hx-reveal>
			<p>The Siberian sturgeon (Acipenser Baerii) is a CITES Appendix II species. Wild harvest is prohibited in all Caviar of Switzerland supplier markets: every gram of extract comes from certified, closed-system freshwater aquaculture, traceable from egg to extract.</p>
			<p>The roe is a by-product of the caviar food industry and meets food-grade standards before it enters cosmetic production. Nothing is wasted; nothing is wild-caught.</p>
		</div>
	</div>
</section>

<section class="hxsc-sec" aria-labelledby="wc-formula">
	<div class="hx-wrap hxsc-sec__grid">
		<div class="hxsc-sec__head" data-hx-reveal>
			<h2 id="wc-formula" class="hx-heading hxsc-sec__title">One extract, in every step of the ritual.</h2>
			<p class="hx-body hxsc-sec__intro">At a concentration calibrated for each formula’s function.</p>
			<p class="hxsc-ctas"><a class="hx-btn" href="<?php echo hxsc_url( '/shop/' ); ?>">Shop the ritual<?php echo hxsc_arrow(); // phpcs:ignore ?></a></p>
		</div>
		<ol class="hxsc-steps" role="list" data-hx-stagger>
			<?php foreach ( $products as $i => $p ) : ?>
			<li data-hx-reveal>
				<p class="hxsc-steps__n"><span class="hxsc-num"><?php echo esc_html( $p['num'] ); ?></span> <span class="hxsc-steps__verb"><?php echo esc_html( $p['verb'] ); ?></span></p>
				<h3 class="hxsc-steps__t"><a href="<?php echo esc_url( $p['url'] ); ?>"><?php echo esc_html( implode( ' ', $p['name'] ) ); ?></a></h3>
				<p class="hxsc-steps__d"><?php echo esc_html( $steps[ $i ] ); ?></p>
			</li>
			<?php endforeach; ?>
		</ol>
	</div>
</section>
