<?php
/**
 * Below the purchase area, for the four formulas (hxpd): the formula's story beside its
 * campaign photograph, key ingredients, the result of its own study (serum, eye cream,
 * 24h cream only — the cleanser has none, so no section and no implication), how to use,
 * the place in the ritual (with a quiet link to The Complete Ritual), questions.
 */
defined( 'ABSPATH' ) || exit;
$pd    = $args['pd'];
$me    = $args['me'];
$study = $args['study'];

// Art-directed photograph from assets/images/<dir>-<desktop|mobile>-<w>.{avif,webp,jpg}.
$art = static function ( array $cfg, string $dsizes, string $msizes ): string {
	$base = get_stylesheet_directory_uri() . '/assets/images/' . $cfg['dir'];
	$set  = static fn( string $crop, array $ws, string $ext ): string => implode( ', ', array_map( static fn( int $w ): string => "{$base}-{$crop}-{$w}.{$ext} {$w}w", $ws ) );
	$d = $cfg['desktop']; $m = $cfg['mobile'];
	$o = '<picture>';
	foreach ( array( 'avif', 'webp', 'jpg' ) as $ext ) {
		$o .= '<source media="(min-width: 900px)"' . ( 'jpg' === $ext ? '' : ' type="image/' . $ext . '"' ) . ' srcset="' . esc_attr( $set( 'desktop', $d['widths'], $ext ) ) . '" sizes="' . esc_attr( $dsizes ) . '" width="' . (int) $d['w'] . '" height="' . (int) $d['h'] . '">';
	}
	foreach ( array( 'avif', 'webp' ) as $ext ) {
		$o .= '<source type="image/' . $ext . '" srcset="' . esc_attr( $set( 'mobile', $m['widths'], $ext ) ) . '" sizes="' . esc_attr( $msizes ) . '">';
	}
	$o .= '<img src="' . esc_url( $base . '-mobile-' . end( $m['widths'] ) . '.jpg' ) . '" srcset="' . esc_attr( $set( 'mobile', $m['widths'], 'jpg' ) ) . '" sizes="' . esc_attr( $msizes ) . '" width="' . (int) $m['w'] . '" height="' . (int) $m['h'] . '" alt="' . esc_attr( $cfg['alt'] ) . '" loading="lazy" decoding="async">';
	return $o . '</picture>';
};

// The story: heading + paragraphs from the product's description (sentence case).
$story_h = '';
$story_p = array();
if ( ! empty( $pd['description'] ) ) {
	if ( preg_match( '#<h2>(.*?)</h2>#s', $pd['description'], $m ) ) $story_h = ucfirst( strtolower( wp_strip_all_tags( $m[1] ) ) );
	if ( preg_match_all( '#<p>(.*?)</p>#s', $pd['description'], $mm ) ) $story_p = array_map( 'wp_strip_all_tags', $mm[1] );
}
?>

<?php if ( $story_h || $story_p ) : ?>
<section class="hxpd-story" aria-labelledby="hxpd-story-title">
	<div class="hx-wrap hxpd-story__grid<?php echo empty( $pd['description_image'] ) ? ' is-text' : ''; ?>">
		<?php if ( ! empty( $pd['description_image'] ) ) : ?>
		<figure class="hxpd-story__media hx-frame" data-hx-reveal="settle"><?php echo $art( $pd['description_image'], '(min-width: 1440px) 560px, 40vw', '100vw' ); // phpcs:ignore ?></figure>
		<?php endif; ?>
		<div class="hxpd-story__body">
			<h2 id="hxpd-story-title" class="hx-heading hxpd-story__title" data-hx-reveal><?php echo esc_html( $story_h ); ?></h2>
			<?php foreach ( $story_p as $i => $p ) : ?>
			<p class="<?php echo 0 === $i ? 'hx-lead' : 'hx-body'; ?> hxpd-story__text" data-hx-reveal><?php echo esc_html( html_entity_decode( $p, ENT_QUOTES, 'UTF-8' ) ); ?></p>
			<?php endforeach; ?>
		</div>
	</div>
</section>
<?php endif; ?>

<?php if ( ! empty( $pd['actives'] ) ) : ?>
<section class="hxpd-actives" aria-labelledby="hxpd-actives-title">
	<div class="hx-wrap">
		<h2 id="hxpd-actives-title" class="hx-heading hxpd-actives__title" data-hx-reveal>Key ingredients</h2>
		<ol class="hxpd-actives__list" data-hx-stagger>
			<?php foreach ( $pd['actives'] as $i => $a ) : ?>
			<li class="hxpd-actives__item" data-hx-reveal>
				<span class="hx-step hxpd-actives__num" aria-hidden="true"><?php echo esc_html( sprintf( '%02d', $i + 1 ) ); ?></span>
				<h3 class="hxpd-actives__name"><?php echo esc_html( $a[0] ); ?></h3>
				<p class="hxpd-actives__text"><?php echo esc_html( $a[1] ); ?></p>
			</li>
			<?php endforeach; ?>
		</ol>
		<p class="hxpd-actives__more"><a class="hx-link" href="#hxpd-ingredients" data-hxpd-open="hxpd-ingredients">Full ingredient list<span class="hx-arrow" aria-hidden="true"></span></a></p>
	</div>
</section>
<?php endif; ?>

<?php if ( $study ) :
	$self = 'Self-reported' === $study['kind']; ?>
<section class="hxpd-result" id="hxpd-result" aria-labelledby="hxpd-result-title">
	<div class="hx-wrap hxpd-result__grid">
		<div class="hxpd-result__fig" data-hx-reveal>
			<p class="hx-numeral hxpd-result__n" aria-hidden="true"><?php echo esc_html( $study['sign'] . $study['n'] ); ?><span class="hxpd-result__unit"><?php echo esc_html( $study['unit'] ); ?></span></p>
		</div>
		<div class="hxpd-result__body" data-hx-reveal>
			<h2 id="hxpd-result-title" class="hxpd-result__title"><span class="hx-sr"><?php echo esc_html( $study['sign'] . $study['n'] . $study['unit'] . ' ' ); ?></span><?php echo esc_html( $study['finding'] ); ?></h2>
			<p class="hxpd-result__kind"><?php echo esc_html( $study['kind'] ); ?></p>
			<dl class="hxpd-result__facts">
				<div><dt>Method</dt><dd><?php echo esc_html( $study['method'] ); ?></dd></div>
				<div><dt>Panel</dt><dd><?php echo esc_html( $study['panel'] ); ?></dd></div>
				<div><dt>Use</dt><dd><?php echo esc_html( ucfirst( $study['regimen'] ) ); ?></dd></div>
			</dl>
			<p class="hx-detail hxpd-result__note">An independent study of <?php echo esc_html( $study['product'] ); ?> alone, by a contract research organisation.<?php echo $self ? ' The figure is what panellists reported, not an instrument measurement.' : ''; ?></p>
			<p class="hxpd-result__more"><a class="hx-link" href="<?php echo esc_url( $args['clinical'] ); ?>">Read the study<span class="hx-arrow" aria-hidden="true"></span></a></p>
		</div>
	</div>
</section>
<?php endif; ?>

<?php if ( ! empty( $pd['how'] ) ) : ?>
<section class="hxpd-use" aria-labelledby="hxpd-use-title">
	<div class="hx-wrap hxpd-use__grid<?php echo empty( $pd['editorial'] ) ? ' is-text' : ''; ?>">
		<div class="hxpd-use__body">
			<h2 id="hxpd-use-title" class="hx-heading hxpd-use__title" data-hx-reveal>How to use</h2>
			<ol class="hxpd-use__steps">
				<?php foreach ( $pd['how'] as $i => $step ) : ?>
				<li class="hxpd-use__step" data-hx-reveal><span class="hx-step" aria-hidden="true"><?php echo esc_html( sprintf( '%02d', $i + 1 ) ); ?></span><p class="hx-body"><?php echo esc_html( $step ); ?></p></li>
				<?php endforeach; ?>
			</ol>
		</div>
		<?php if ( ! empty( $pd['editorial'] ) ) : ?>
		<figure class="hxpd-use__media hx-frame" data-hx-reveal="settle"><?php echo $art( $pd['editorial'], '(min-width: 1440px) 560px, 40vw', '100vw' ); // phpcs:ignore ?></figure>
		<?php endif; ?>
	</div>
</section>
<?php endif; ?>

<section class="hxpd-ritual" aria-labelledby="hxpd-ritual-title">
	<div class="hx-wrap">
		<div class="hxpd-ritual__head">
			<h2 id="hxpd-ritual-title" class="hx-heading hxpd-ritual__title" data-hx-reveal>Within the ritual</h2>
			<p class="hx-lead hxpd-ritual__lede" data-hx-reveal>Each formula prepares the skin for the next. Used together, morning and evening, they make one complete ritual.</p>
		</div>
		<ol class="hxpd-ritual__list">
			<?php foreach ( $args['formulas'] as $f ) :
				$tag = $f['current'] ? 'div' : 'a'; ?>
			<li class="hxpd-ritual__item<?php echo $f['current'] ? ' is-current' : ''; ?>" data-hx-reveal>
				<<?php echo $tag; ?> class="hxpd-ritual__link"<?php echo $f['current'] ? ' aria-current="page"' : ' href="' . esc_url( $f['url'] ) . '"'; ?>>
					<span class="hxpd-ritual__stage"><?php echo cos_h26_picture( 'collection-object-' . $f['key'], array( 480, 720, 960 ), array( 2, 3 ), '', array( 'class' => 'hxpd-ritual__object', 'sizes' => '(min-width: 1024px) 180px, 22vw', 'w' => 480 ) ); // phpcs:ignore ?></span>
					<span class="hxpd-ritual__step"><span class="hx-step"><?php echo esc_html( $f['num'] ); ?></span> <?php echo esc_html( $f['verb'] ); ?></span>
					<span class="hxpd-ritual__name"><?php echo esc_html( $f['full'] ); ?></span>
					<?php if ( $f['current'] ) : ?><span class="hxpd-ritual__here">This formula</span><?php endif; ?>
				</<?php echo $tag; ?>>
			</li>
			<?php endforeach; ?>
		</ol>
		<?php if ( $args['ritual'] ) : ?>
		<p class="hxpd-ritual__set" data-hx-reveal><a class="hx-link" href="<?php echo esc_url( $args['ritual']['url'] ); ?>" data-cro-cta="pdp-secondary-learn-ritual">The Complete Ritual, all four formulas, <?php echo esc_html( $args['ritual']['price'] ); ?><span class="hx-arrow" aria-hidden="true"></span></a></p>
		<?php endif; ?>
	</div>
</section>

<?php if ( ! empty( $pd['faqs'] ) ) : ?>
<section class="hxpd-faq" aria-labelledby="hxpd-faq-title">
	<div class="hx-wrap hxpd-faq__grid">
		<h2 id="hxpd-faq-title" class="hx-heading hxpd-faq__title" data-hx-reveal>Questions</h2>
		<div class="hxpd-faq__list">
			<?php foreach ( $pd['faqs'] as $faq ) : ?>
			<details class="hxpd-acc__item hxpd-faq__item">
				<summary class="hxpd-acc__sum hxpd-faq__q"><?php echo esc_html( $faq[0] ); ?><span class="hxpd-acc__icon" aria-hidden="true"></span></summary>
				<p class="hx-body hxpd-faq__a"><?php echo esc_html( html_entity_decode( $faq[1], ENT_QUOTES, 'UTF-8' ) ); ?></p>
			</details>
			<?php endforeach; ?>
		</div>
	</div>
</section>
<?php endif; ?>
