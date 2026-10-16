<?php
/**
 * /science/why-caviar/ (page 345) — chapter 01. Copy carried over from the DB page; facts unchanged.
 * Step names follow the products (the DB said "Moisturiser" for the 24h Regeneration Cream).
 */
defined( 'ABSPATH' ) || exit;

get_template_part( 'template-parts/hx/science/masthead', null, array(
	'label' => 'Chapter 01 <span aria-hidden="true">·</span> Why caviar',
	'title' => 'The densest material in nature.',
	'lead'  => 'Caviar Extract is not a luxury ingredient in the cosmetic sense; it is a biologically precise one. The phospholipid, amino acid and mineral profile of Acipenser Baerii roe mirrors human skin’s own cellular chemistry with a fidelity that no synthetic ingredient can replicate.',
	'plate' => array(
		'base' => 'hxv-field', 'widths' => array( 1440, 2048, 2880 ), 'ratio' => array( 2, 1 ),
		'alt'  => 'Glossy black caviar pearls on a pale, reflective surface',
		'tall' => array( 'hxv-field-tall', array( 640, 960, 1280 ), array( 4, 5 ) ),
	),
) );

$products = cos_h26_products();
$steps    = array(
	'Micellar system binds impurities while caviar phospholipids immediately replenish the barrier disrupted by the cleansing process.',
	'Highest active concentration. Micro-encapsulated for time-release delivery into the dermis, where the phospholipid matrix does its most important work.',
	'Periorbital calibration. The thinnest skin on the body receives caviar actives at concentrations appropriate for its unique lipid requirements.',
	'The sealing step. Caviar Lipid Complex forms the sustained phospholipid reservoir that maintains barrier integrity between ritual applications.',
);
?>

<section class="hxsc-sec" aria-labelledby="wc-bio">
	<div class="hx-wrap hxsc-sec__grid">
		<div class="hxsc-sec__head" data-hx-reveal>
			<p class="hx-label hxsc-sec__label">The biology</p>
			<h2 id="wc-bio" class="hx-heading hxsc-sec__title">Sturgeon roe is not glamorous by accident. It is nutrient-dense by biological necessity.</h2>
		</div>
		<div class="hxsc-prose" data-hx-reveal>
			<p>A fertilised sturgeon egg must contain everything required to build a fully functional organism from a single cell. That means the complete molecular toolkit for cellular construction: phospholipids, amino acids, nucleic acids, vitamins and trace minerals in concentrations that are, by necessity, extraordinarily high.</p>
			<p>The phospholipid profile of Acipenser Baerii caviar is what makes it genuinely relevant to skin biology. Phospholipids are the primary structural component of the cell membrane, the lipid bilayer. The specific phospholipid composition of caviar roe (dominated by phosphatidylcholine, phosphatidylethanolamine and phosphatidylserine) mirrors the composition of the human epidermal barrier with a precision that synthetic lipid blends cannot achieve.</p>
			<p>This is the correct meaning of “biomimetic”: not structurally analogous in a loose sense, but chemically near-identical in the components that matter for skin function. When the skin’s intercellular lipid matrix is depleted, by cleansing, environmental exposure or age, caviar phospholipids can replenish it using the same molecules that were lost.</p>
			<blockquote class="hxsc-quote"><p>The phospholipid profile of Acipenser Baerii caviar roe is the closest naturally occurring match to human skin’s intercellular lipid matrix identified in the published literature. That is the basis of the biomimetic claim.</p></blockquote>
		</div>
	</div>
</section>

<section class="hxsc-sec hxsc-sec--tint" aria-labelledby="wc-profile">
	<div class="hx-wrap">
		<div class="hxsc-sec__head hxsc-sec__head--wide" data-hx-reveal>
			<p class="hx-label hxsc-sec__label">The profile</p>
			<h2 id="wc-profile" class="hx-heading hxsc-sec__title">What caviar extract contains. And why it matters.</h2>
		</div>
		<dl class="hxsc-figs" data-hx-stagger>
			<div class="hxsc-figs__item" data-hx-reveal>
				<dt><span class="hxsc-figs__n">47+</span><span class="hxsc-figs__label">Amino acids and peptides</span></dt>
				<dd>Including all essential amino acids required for collagen and elastin synthesis. Proline and hydroxyproline, the two most critical for collagen triple-helix formation, are present at especially high concentrations in caviar roe.</dd>
			</div>
			<div class="hxsc-figs__item" data-hx-reveal>
				<dt><span class="hxsc-figs__n">~30%</span><span class="hxsc-figs__label">Phospholipid content by dry weight</span></dt>
				<dd>Predominantly phosphatidylcholine (PC) and phosphatidylethanolamine (PE), the same lipid classes that form the dominant fraction of the human stratum corneum’s intercellular lipid bilayer.</dd>
			</div>
			<div class="hxsc-figs__item" data-hx-reveal>
				<dt><span class="hxsc-figs__n">A, D, B</span><span class="hxsc-figs__label">Vitamin complex</span></dt>
				<dd>Vitamins A, D, B1, B2, B6, B12 and E. Vitamin A supports cell turnover. Vitamin D modulates immune function in the skin. B-complex vitamins support enzymatic skin repair processes. Vitamin E provides direct antioxidant protection.</dd>
			</div>
		</dl>
	</div>
</section>

<section class="hxsc-sec" aria-labelledby="wc-bm">
	<div class="hx-wrap hxsc-sec__grid">
		<div class="hxsc-sec__head" data-hx-reveal>
			<p class="hx-label hxsc-sec__label">The biomimetic argument</p>
			<h2 id="wc-bm" class="hx-heading hxsc-sec__title">Most ingredients work on the skin. Caviar works like the skin.</h2>
		</div>
		<div class="hxsc-prose" data-hx-reveal>
			<p>The skin barrier is a lipid-protein matrix. Its integrity depends on the availability of the right lipids in the right ratios: ceramides, fatty acids and phospholipids arranged in lamellar layers between the corneocytes of the stratum corneum. This matrix is what prevents transepidermal water loss (TEWL), excludes environmental aggressors and maintains the skin’s acid mantle.</p>
			<p>Most moisturising ingredients, including synthetic lipids, emollients and occlusive agents, work by sitting on top of or within this matrix and mimicking its function. Caviar phospholipids are different: they are structurally compatible with the lamellar architecture itself. The skin’s own repair processes can incorporate them directly into the intercellular matrix rather than simply adding a layer above it.</p>
		</div>
		<div class="hxsc-sec__full" data-hx-reveal>
			<table class="hxsc-table hxsc-table--compare">
				<caption class="hx-sr">Synthetic lipid blends compared with caviar phospholipids</caption>
				<thead><tr><th scope="col">Property</th><th scope="col">Synthetic lipid blends</th><th scope="col">Caviar phospholipids</th></tr></thead>
				<tbody>
					<tr><th scope="row">Structural compatibility</th><td data-label="Synthetic lipid blends">Analogue: similar function, different structure</td><td data-label="Caviar phospholipids">Homologous: same lipid classes, same structure</td></tr>
					<tr><th scope="row">Barrier integration</th><td data-label="Synthetic lipid blends">Supplemental: adds above or between</td><td data-label="Caviar phospholipids">Incorporable: integrated into lamellar layers</td></tr>
					<tr><th scope="row">Amino acid delivery</th><td data-label="Synthetic lipid blends">Requires separate peptide addition</td><td data-label="Caviar phospholipids">Native: full amino acid profile present in extract</td></tr>
					<tr><th scope="row">Mineral cofactors</th><td data-label="Synthetic lipid blends">Not present unless separately formulated</td><td data-label="Caviar phospholipids">Native: zinc, selenium, iodine present in extract</td></tr>
				</tbody>
			</table>
		</div>
	</div>
</section>

<section class="hxsc-sec" aria-labelledby="wc-src">
	<div class="hx-wrap hxsc-sec__grid">
		<div class="hxsc-sec__head" data-hx-reveal>
			<p class="hx-label hxsc-sec__label">Responsible sourcing</p>
			<h2 id="wc-src" class="hx-heading hxsc-sec__title">Acipenser Baerii. Sustainably farmed. Fully traceable.</h2>
		</div>
		<div class="hxsc-prose" data-hx-reveal>
			<p>The Siberian sturgeon (Acipenser Baerii) is a CITES Appendix II species. Wild harvest is prohibited in all Caviar of Switzerland supplier markets. Every gram of caviar extract used in our formulas comes from certified aquaculture operations: closed-system freshwater farming under controlled conditions.</p>
			<p>The roe is harvested as a by-product of the caviar food industry. Our extract is produced from roe that meets food-grade quality standards before it enters the cosmetic production stream. Nothing is wasted; nothing is wild-caught.</p>
		</div>
		<ol class="hxsc-list hxsc-sec__full" role="list" data-hx-stagger>
			<li data-hx-reveal><h3 class="hxsc-list__t">CITES compliant</h3><p>All Acipenser Baerii roe sourced from licensed aquaculture under the CITES Appendix II framework. No wild-harvest supply.</p></li>
			<li data-hx-reveal><h3 class="hxsc-list__t">Closed-system aquaculture</h3><p>Recirculating aquaculture systems (RAS) allow complete water management, eliminate ecosystem runoff and enable full traceability from egg to extract.</p></li>
			<li data-hx-reveal><h3 class="hxsc-list__t">Food-grade upstream</h3><p>Extract produced from food-grade roe. Quality standards upstream of the cosmetic production process are the highest applied in any supply chain context.</p></li>
			<li data-hx-reveal><h3 class="hxsc-list__t">Micro-encapsulation</h3><p>Active caviar compounds are micro-encapsulated in the final formula, protecting potency during shelf life and enabling time-release delivery into the dermis during application.</p></li>
		</ol>
	</div>
</section>

<section class="hxsc-sec hxsc-sec--tint" aria-labelledby="wc-formula">
	<div class="hx-wrap hxsc-sec__grid">
		<div class="hxsc-sec__head" data-hx-reveal>
			<p class="hx-label hxsc-sec__label">In the formula</p>
			<h2 id="wc-formula" class="hx-heading hxsc-sec__title">Caviar Extract works in every step of the ritual, at concentrations calibrated for each formula’s function.</h2>
		</div>
		<div class="hxsc-prose" data-hx-reveal>
			<p>The cleanser uses caviar extract to replenish the lipids lost during cleansing. The serum delivers it at the highest concentration, micro-encapsulated for dermal penetration. The eye cream focuses it on the periorbital zone at concentrations calibrated for the thinness of that skin. The 24h Regeneration Cream seals the active layers of the ritual and provides the sustained phospholipid reservoir.</p>
			<p>One extract, four applications, one complete biological system.</p>
			<p class="hxsc-ctas"><a class="hx-btn" href="<?php echo hxsc_url( '/shop/' ); ?>">Shop the ritual<?php echo hxsc_arrow(); // phpcs:ignore ?></a> <a class="hx-link" href="<?php echo hxsc_url( '/science/high-tech-complex/' ); ?>">The full complex<?php echo hxsc_arrow(); // phpcs:ignore ?></a></p>
		</div>
		<ol class="hxsc-steps hxsc-sec__full" role="list" data-hx-stagger>
			<?php foreach ( $products as $i => $p ) : ?>
			<li data-hx-reveal>
				<p class="hxsc-steps__n"><span class="hx-step"><?php echo esc_html( $p['num'] ); ?></span> <span class="hxsc-steps__verb"><?php echo esc_html( $p['verb'] ); ?></span></p>
				<h3 class="hxsc-steps__t"><a href="<?php echo esc_url( $p['url'] ); ?>"><?php echo esc_html( implode( ' ', $p['name'] ) ); ?></a></h3>
				<p class="hxsc-steps__d"><?php echo esc_html( $steps[ $i ] ); ?></p>
			</li>
			<?php endforeach; ?>
		</ol>
	</div>
</section>
