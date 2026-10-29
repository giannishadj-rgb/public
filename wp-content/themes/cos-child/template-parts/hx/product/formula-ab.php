<?php
/**
 * Below the purchase area, Augustinus Bader-style (owner, 25 Oct 2026: the Revitalizing Eye
 * Cream only; reference: augustinusbader.com, The Rich Cream). In AB's order:
 *   the science (four image cards: what it is · how it works · what it does · who it is for)
 *   → proven results (a dark band, this formula's own study) → key ingredients (on an arc)
 *   → how to apply (photograph beside the steps) → your streamlined routine (four step cards)
 *   → questions beside a photograph.
 * Our own palette and type, not AB's blue and copper. No reviews or ratings (brand rule).
 * Claims: only the eye cream's −38% (page 347), labelled with method, panel and duration.
 */
defined( 'ABSPATH' ) || exit;
$pd    = $args['pd'];
$me    = $args['me'];
$study = $args['study'];

// Art-directed photograph from assets/images/<dir>-<desktop|mobile>-<w>.{avif,webp,jpg} (as formula.php).
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

// The story's opening sentence leads the science section (the story itself has no own section here).
$lede = '';
if ( ! empty( $pd['description'] ) && preg_match( '#<p>(.*?)</p>#s', $pd['description'], $m ) ) $lede = html_entity_decode( wp_strip_all_tags( $m[1] ), ENT_QUOTES, 'UTF-8' );

$cards = array(
	array( 'What it is', 'The High-Tech Complex: Caviar Extract, SYN®-COLL, Hyaluronic Acid, Superoxide Dismutase and Resveratrol, the five actives in every formula of the ritual.',
		cos_h26_picture( 'hxv-macro', array( 800, 1200 ), array( 4, 5 ), 'Glossy black caviar pearls in close-up', array( 'sizes' => '(min-width: 900px) 23vw, 80vw', 'w' => 800 ) ) ),
	array( 'How it works', $pd['actives'][0][1] ?? '',
		cos_h26_picture( 'card-eye-texture', array( 480, 720, 960 ), array( 4, 5 ), 'The Revitalizing Eye Cream among clear, pearl-like drops', array( 'sizes' => '(min-width: 900px) 23vw, 80vw', 'w' => 720 ) ) ),
	array( 'What it does', implode( '. ', array_slice( $pd['benefits'] ?? array(), 0, 3 ) ) . '.',
		cos_h26_picture( 'wc-rit-eye', array( 480, 720, 960 ), array( 4, 5 ), 'A hand holding the Revitalizing Eye Cream', array( 'sizes' => '(min-width: 900px) 23vw, 80vw', 'w' => 720 ) ) ),
	array( 'Who it is for', ( $pd['who_for'] ?? '' ) . ' ' . implode( ', ', array_slice( $pd['concerns'] ?? array(), 0, 3 ) ) . '.',
		cos_hxpd_attachment_picture( 182, 'A woman holding the Revitalizing Eye Cream beside her face', '(min-width: 900px) 23vw, 80vw' ) ),
);
?>

<section class="hxpd-sci" id="hxpd-sci" aria-labelledby="hxpd-sci-title">
	<div class="hx-wrap">
		<div class="hxpd-sci__head">
			<h2 id="hxpd-sci-title" class="hx-heading hxpd-sci__title" data-hx-reveal>The science of the formula</h2>
			<?php if ( $lede ) : ?><p class="hx-lead hxpd-sci__lede" data-hx-reveal><?php echo esc_html( $lede ); ?></p><?php endif; ?>
		</div>
		<ul class="hxpd-sci__list" data-hx-stagger>
			<?php foreach ( $cards as $c ) : ?>
			<li class="hxpd-sci__card" data-hx-reveal>
				<figure class="hxpd-sci__media"><?php echo $c[2]; // phpcs:ignore -- escaped in the picture helpers ?></figure>
				<h3 class="hxpd-sci__name"><?php echo esc_html( $c[0] ); ?></h3>
				<p class="hxpd-sci__text"><?php echo esc_html( trim( $c[1] ) ); ?></p>
			</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>

<?php if ( $study ) : ?>
<section class="hxpd-proof" id="hxpd-proof" aria-labelledby="hxpd-proof-title">
	<div class="hx-wrap">
		<div class="hxpd-proof__head">
			<h2 id="hxpd-proof-title" class="hx-heading hxpd-proof__title" data-hx-reveal>Proven results</h2>
			<p class="hxpd-proof__kind" data-hx-reveal><?php echo esc_html( $study['kind'] ); ?></p>
		</div>
		<div class="hxpd-proof__row" data-hx-reveal>
			<p class="hxpd-proof__n" aria-hidden="true"><?php echo esc_html( $study['sign'] . $study['n'] ); ?><span><?php echo esc_html( $study['unit'] ); ?></span></p>
			<p class="hxpd-proof__what"><span class="hx-sr"><?php echo esc_html( $study['sign'] . $study['n'] . $study['unit'] . ' ' ); ?></span><?php echo esc_html( $study['finding'] ); ?>, measured by <?php echo esc_html( strtolower( $study['method'] ) ); ?></p>
		</div>
		<div class="hxpd-proof__foot" data-hx-reveal>
			<p class="hxpd-proof__note">An independent 28-day study of the Revitalizing Eye Cream alone, by a contract research organisation: <?php echo esc_html( $study['panel'] ); ?>, <?php echo esc_html( $study['regimen'] ); ?>.</p>
			<a class="hxpd-proof__more" href="<?php echo esc_url( $args['clinical'] ); ?>">Read the study</a>
		</div>
	</div>
</section>
<?php endif; ?>

<?php if ( ! empty( $pd['actives'] ) ) : ?>
<section class="hxpd-arc" id="hxpd-actives" aria-labelledby="hxpd-arc-title">
	<div class="hx-wrap hxpd-arc__grid">
		<div class="hxpd-arc__head">
			<h2 id="hxpd-arc-title" class="hx-heading hxpd-arc__title" data-hx-reveal>Key ingredients</h2>
			<p class="hx-lead hxpd-arc__lede" data-hx-reveal>Micro-encapsulated caviar actives with three companions chosen for the thinnest skin of the face.</p>
			<p class="hxpd-arc__more" data-hx-reveal><a class="hxpd-arc__btn" href="#hxpd-ingredients" data-hxpd-open="hxpd-ingredients">Full ingredient list</a></p>
		</div>
		<ol class="hxpd-arc__list" data-hx-stagger>
			<?php // the arc: a quadratic curve stretched over the list; the dots sit on it at t = .2/.4/.6/.8 ?>
			<svg class="hxpd-arc__line" viewBox="0 0 100 100" preserveAspectRatio="none" aria-hidden="true" focusable="false"><path d="M0 100 Q15 10 100 0" fill="none" vector-effect="non-scaling-stroke"/></svg>
			<?php foreach ( $pd['actives'] as $i => $a ) : ?>
			<li class="hxpd-arc__item hxpd-arc__item--<?php echo (int) $i; ?>" data-hx-reveal>
				<span class="hxpd-arc__dot" aria-hidden="true"></span>
				<h3 class="hxpd-arc__name"><?php echo esc_html( $a[0] ); ?></h3>
				<p class="hxpd-arc__text"><?php echo esc_html( $a[1] ); ?></p>
			</li>
			<?php endforeach; ?>
		</ol>
	</div>
</section>
<?php endif; ?>

<?php if ( ! empty( $pd['how'] ) ) : ?>
<section class="hxpd-apply" id="hxpd-use" aria-labelledby="hxpd-apply-title">
	<div class="hx-wrap hxpd-apply__grid">
		<?php if ( ! empty( $pd['editorial'] ) ) : ?>
		<figure class="hxpd-apply__media hx-frame" data-hx-reveal="settle"><?php echo $art( $pd['editorial'], '(min-width: 1440px) 640px, 45vw', '100vw' ); // phpcs:ignore ?></figure>
		<?php endif; ?>
		<div class="hxpd-apply__body">
			<h2 id="hxpd-apply-title" class="hx-heading hxpd-apply__title" data-hx-reveal>How to apply</h2>
			<p class="hx-lead hxpd-apply__lede" data-hx-reveal>Morning and evening, after the serum.</p>
			<ol class="hxpd-use__steps">
				<?php foreach ( $pd['how'] as $i => $step ) : ?>
				<li class="hxpd-use__step" data-hx-reveal><span class="hx-step" aria-hidden="true"><?php echo esc_html( sprintf( '%02d', $i + 1 ) ); ?></span><p class="hx-body"><?php echo esc_html( $step ); ?></p></li>
				<?php endforeach; ?>
			</ol>
		</div>
	</div>
</section>
<?php endif; ?>

<section class="hxpd-steps" id="hxpd-ritual" aria-labelledby="hxpd-steps-title">
	<div class="hx-wrap">
		<div class="hxpd-steps__head">
			<h2 id="hxpd-steps-title" class="hx-heading hxpd-steps__title" data-hx-reveal>Your streamlined routine</h2>
			<?php if ( $args['ritual'] ) : ?><a class="hx-link hxpd-steps__set" href="<?php echo esc_url( $args['ritual']['url'] ); ?>" data-cro-cta="pdp-secondary-learn-ritual">All four as The Complete Ritual, <?php echo esc_html( $args['ritual']['price'] ); ?><span class="hx-arrow" aria-hidden="true"></span></a><?php endif; ?>
		</div>
		<ol class="hxpd-steps__list">
			<?php foreach ( $args['formulas'] as $f ) : ?>
			<li class="hxpd-steps__card<?php echo $f['current'] ? ' is-current' : ''; ?>" data-hx-reveal>
				<p class="hxpd-steps__badge">Step <?php echo esc_html( (string) (int) $f['num'] ); ?> <span aria-hidden="true">·</span> <?php echo esc_html( $f['verb'] ); ?></p>
				<<?php echo $f['current'] ? 'div' : 'a'; ?> class="hxpd-steps__stage"<?php echo $f['current'] ? '' : ' href="' . esc_url( $f['url'] ) . '" tabindex="-1" aria-hidden="true"'; ?>><?php echo cos_h26_picture( 'pack-' . $f['key'], array( 480, 720 ), array( 2, 3 ), '', array( 'sizes' => '(min-width: 1024px) 200px, 40vw', 'w' => 480 ) ); // phpcs:ignore ?></<?php echo $f['current'] ? 'div' : 'a'; ?>>
				<?php if ( $f['current'] ) : ?>
				<p class="hxpd-steps__name"><?php echo esc_html( $f['full'] ); ?></p>
				<p class="hxpd-steps__price">This formula</p>
				<?php else : ?>
				<a class="hxpd-steps__name" href="<?php echo esc_url( $f['url'] ); ?>"><?php echo esc_html( $f['full'] ); ?></a>
				<p class="hxpd-steps__price"><?php echo esc_html( $f['price'] ); ?> <span aria-hidden="true">·</span> <?php echo esc_html( $f['size'] ); ?></p>
				<?php if ( ! empty( $f['buy'] ) ) : ?>
				<a class="hxpd-steps__add add_to_cart_button ajax_add_to_cart" href="<?php echo esc_url( add_query_arg( 'add-to-cart', $f['pid'], get_permalink() ) ); ?>" data-product_id="<?php echo (int) $f['pid']; ?>" data-quantity="1" rel="nofollow" aria-label="<?php echo esc_attr( sprintf( 'Add %s to bag', $f['full'] ) ); ?>">Add to bag</a>
				<?php endif; ?>
				<?php endif; ?>
			</li>
			<?php endforeach; ?>
		</ol>
	</div>
</section>

<?php if ( ! empty( $pd['faqs'] ) ) : ?>
<section class="hxpd-qa" id="hxpd-faq" aria-labelledby="hxpd-faq-title">
	<div class="hxpd-qa__grid">
		<div class="hxpd-qa__body">
			<h2 id="hxpd-faq-title" class="hx-heading hxpd-qa__title" data-hx-reveal>Questions</h2>
			<div class="hxpd-faq__list">
				<?php foreach ( $pd['faqs'] as $faq ) : ?>
				<details class="hxpd-acc__item hxpd-faq__item">
					<summary class="hxpd-acc__sum hxpd-faq__q"><?php echo esc_html( $faq[0] ); ?><span class="hxpd-acc__icon" aria-hidden="true"></span></summary>
					<p class="hx-body hxpd-faq__a"><?php echo esc_html( html_entity_decode( $faq[1], ENT_QUOTES, 'UTF-8' ) ); ?></p>
				</details>
				<?php endforeach; ?>
			</div>
		</div>
		<?php if ( ! empty( $pd['description_image'] ) ) : ?>
		<figure class="hxpd-qa__media"><?php echo $art( $pd['description_image'], '50vw', '100vw' ); // phpcs:ignore ?></figure>
		<?php endif; ?>
	</div>
</section>
<?php endif; ?>
