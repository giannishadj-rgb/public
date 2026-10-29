<?php
/**
 * 04 — Measured on skin: the compact science section (20 Oct 2026, impeccable shape brief
 * confirmed by the owner). Styles: assets/css/hx/measured.css, prefix hxm-.
 *
 * Follows Our story. Replaces the removed Swiss science section (trust.php) and the short-lived
 * results rail inside Our story. Three results, one per studied formula, each paired with an
 * owner photograph of its own product (owner, 20 Oct: the cream joins the row with a photograph
 * like the others). The two instrument-measured results carry a figure; the self-reported cream
 * result never does: its finding, panel and the satisfaction line stay in text, prefixed as
 * self-assessment. Pearl room between two ivory ones.
 *
 * Claims (page 347 /science/clinical-studies/, PRODUCT.md "Evidence on Hand"): separate
 * 28-day single-formula studies by an independent contract research organisation (unnamed);
 * no complete-ritual study, nothing for the cleanser, no SYN®-COLL in-vitro figure. Every
 * figure names product, method, panel and duration.
 *
 * Images: hxhe-drop (owner photograph DSC_6612, the serum dispensed onto a fingertip; also on
 * /heritage/), story-eye (owner campaign photograph, the eye cream between two faces) and
 * hxhe-jar (the same shoot as hxhe-drop: hands holding the open cream jar; also on /heritage/).
 * The eye models look younger than the 42–65 panel, so the figure never sits on the face and
 * the photograph is captioned as a campaign image.
 */
defined( 'ABSPATH' ) || exit;

$urls = array();
foreach ( $args['products'] ?? cos_h26_products() as $p ) {
	$urls[ $p['slug'] ] = $p['url'];
}

// Keep "aged 45–60", "28 days" and "day 7" whole: a range or duration split across lines misreads.
$nb = static function ( string $t ): string {
	return preg_replace( '/(aged \d+–\d+|\d+ days|day \d+)/u', '<span class="hxm__nb">$1</span>', esc_html( $t ) );
};

$results = array(
	array(
		'key'     => 'serum',
		'slug'    => 'advanced-repair-serum',
		'product' => 'Advanced Repair Serum',
		'figure'  => '+47',
		'finding' => 'Skin firmness',
		'method'  => 'Cutometry, 32 women aged 45–60, 28 days',
		'note'    => '+22% surface smoothness by day 7 (profilometry)',
		'img'     => 'hxhe-drop',
		'alt'     => 'A drop of the Advanced Repair Serum dispensed onto a fingertip',
		'credit'  => '',
	),
	array(
		'key'     => 'eye',
		'slug'    => 'revitalizing-eye-cream',
		'product' => 'Revitalizing Eye Cream',
		'figure'  => '−38',
		'finding' => 'Crow’s-feet depth',
		'method'  => 'Optical profilometry, 30 women aged 42–65, 28 days',
		'note'    => '',
		'img'     => 'story-eye',
		'alt'     => 'The Revitalizing Eye Cream held between the cheeks of two women with their eyes closed',
		'credit'  => 'Campaign photograph, not study participants',
	),
	array(
		'key'     => 'cream',
		'slug'    => '24h-regeneration-cream',
		'product' => '24h Regeneration Cream',
		'figure'  => '', // self-reported: never set as a figure
		'finding' => 'Overall satisfaction',
		'method'  => 'Self-assessment, 34 women aged 40–65, 28 days',
		'note'    => '100% panellist-reported satisfaction',
		'img'     => 'hxhe-jar',
		'alt'     => 'Hands holding the open 24h Regeneration Cream jar, a touch of cream on the fingertip and the back of the hand',
		'credit'  => '',
	),
);
?>
<section id="evidence" class="hxm" aria-labelledby="hxm-title">
	<div class="hx-wrap">
		<header class="hxm__head">
			<h2 id="hxm-title" class="hx-heading hxm__title" data-hx-reveal="light">Measured, formula by&nbsp;formula</h2>
			<p class="hxm__frame-line" data-hx-reveal data-hx-delay="120">Separate 28-day studies, each of a single formula, by an independent contract research organisation.</p>
		</header>

		<ul class="hxm__results" role="list" data-hx-stagger>
			<?php foreach ( $results as $r ) : $url = $urls[ $r['slug'] ] ?? home_url( '/shop/' ); ?>
			<li class="hxm__result hxm__result--<?php echo esc_attr( $r['key'] ); ?>">
				<figure class="hxm__figure" data-hx-reveal="mask">
					<a class="hxm__photo" href="<?php echo esc_url( $url ); ?>" tabindex="-1" aria-hidden="true">
						<?php echo cos_h26_picture( $r['img'], array( 640, 960, 1280 ), array( 4, 5 ), $r['alt'], array( 'class' => 'hxm__img', 'sizes' => '(min-width: 900px) min(40vw, 560px), 100vw', 'w' => 960 ) ); // phpcs:ignore ?>
					</a>
					<?php if ( $r['credit'] ) : ?><figcaption class="hxm__credit"><?php echo esc_html( $r['credit'] ); ?></figcaption><?php endif; ?>
				</figure>
				<div class="hxm__copy" data-hx-reveal>
					<?php if ( $r['figure'] ) : ?><p class="hxm__fig" aria-hidden="true"><?php echo esc_html( $r['figure'] ); ?><span class="hxm__pct">%</span></p><?php endif; ?>
					<div class="hxm__text">
						<h3 class="hxm__finding"><?php if ( $r['figure'] ) : ?><span class="hx-sr"><?php echo esc_html( $r['figure'] ); ?>% </span><?php endif; ?><?php echo esc_html( $r['finding'] ); ?></h3>
						<p class="hxm__product"><a href="<?php echo esc_url( $url ); ?>"><?php echo esc_html( $r['product'] ); ?></a></p>
						<p class="hxm__method"><?php if ( $r['figure'] ) : ?><span class="hx-sr">Instrument-measured: </span><?php endif; ?><?php echo $nb( $r['method'] ); // phpcs:ignore -- escaped inside $nb ?></p>
						<?php if ( $r['note'] ) : ?><p class="hxm__note"><?php echo $nb( $r['note'] ); // phpcs:ignore -- escaped inside $nb ?></p><?php endif; ?>
					</div>
				</div>
			</li>
			<?php endforeach; ?>
		</ul>

		<div class="hxm__foot" data-hx-reveal>
			<p class="hxm__more"><a class="hx-link" href="<?php echo esc_url( home_url( '/science/clinical-studies/' ) ); ?>" data-cro-cta="home-measured-studies">Read the studies<span class="hx-arrow" aria-hidden="true"></span></a></p>
		</div>
	</div>
</section>
