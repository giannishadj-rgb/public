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

<?php
$g = 'g' === ( $args['look'] ?? '' );
if ( $g ) :
	// Guerlain-style section bar (sticky under the header): the page's parts, in order
	$parts = array( array( 'hxpd-sci', 'The science' ) );
	if ( $story_h || $story_p ) $parts[] = array( 'hxpd-story', $story_h ?: 'The formula' );
	if ( ! empty( $pd['actives'] ) ) $parts[] = array( 'hxpd-actives', 'Ingredients' );
	if ( $study ) $parts[] = array( 'hxpd-result', 'Results' );
	if ( ! empty( $pd['how'] ) ) $parts[] = array( 'hxpd-use', 'How to use' );
	$parts[] = array( 'hxpd-ritual', 'Complete your ritual' );
	if ( ! empty( $pd['faqs'] ) ) $parts[] = array( 'hxpd-faq', 'Questions' );
	?>
<nav class="hxpd-secnav" aria-label="On this page">
	<div class="hx-wrap"><ol class="hxpd-secnav__list">
		<?php foreach ( $parts as $p ) : ?><li><a class="hxpd-secnav__link" href="#<?php echo esc_attr( $p[0] ); ?>"><?php echo esc_html( $p[1] ); ?></a></li><?php endforeach; ?>
	</ol></div>
</nav>

<?php
/* The science of the formula (owner, 28 Oct 2026: "a section like" the eye cream page's): four
   image cards. Facts only from each product's ingredient list and approved copy (the cleanser's
   list has no SYN-COLL, SOD or resveratrol, so they are not named there). 28 Oct 2026: all four
   formulas; no photograph repeats one already on the same page. */
$sz  = '(min-width: 900px) 23vw, 80vw';
$k   = $args['me']['key'] ?? 'cleanser';
$pack = cos_h26_picture( 'ritual-cut-' . $k, array( 360, 540, 720 ), array( 3, 4 ), $args['me']['full'] ?? '', array( 'sizes' => $sz, 'w' => 720, 'class' => 'hxpd-sci__pack' ) );
$does = implode( '. ', $pd['benefits'] ?? array() ) . '.';
$sci_by = array(
	'COS-001' => array( 'first',
		'A no-rinse micellar water made in Zug, with caviar extract, panthenol, allantoin, chamomile and hyaluronic acid.',
		'Micelles lift make-up and impurities onto the cotton pad, so there is nothing to rinse away; caviar extract then helps replace what cleansing removes.',
		cos_h26_picture( 'wc-cav-heap', array( 1200, 1800, 2400 ), array( 6, 5 ), 'Glossy black caviar pearls on a pale surface', array( 'sizes' => $sz, 'w' => 1200 ) ),
		cos_h26_picture( 'story-cleanser', array( 640, 960, 1280 ), array( 4, 5 ), 'A hand holding the Micellar Water All-in-One Cleanser as droplets of water splash from the bottle', array( 'sizes' => $sz, 'w' => 960 ) ),
		'Anyone who wants one step to remove make-up, cleanse and refresh. Gentle enough for most skin types, including sensitive skin; if you have specific concerns, try it on a small area first.',
		cos_hxpd_attachment_picture( 195, 'A woman in a black top holding the Micellar Water All-in-One Cleanser against her arm', $sz ) ),
	'COS-002' => array( 'second',
		'A lightweight, concentrated serum made in Zug, with micro-encapsulated caviar extract, SYN®-COLL peptide, low molecular weight hyaluronic acid and resveratrol.',
		'Micro-encapsulation protects the caviar extract until it meets the skin. The silky, non-oily texture absorbs within a minute, so the cream that follows seals it in.',
		cos_h26_picture( 'hxv-macro', array( 800, 1200, 1600 ), array( 4, 5 ), 'Glossy black caviar pearls in close-up', array( 'sizes' => $sz, 'w' => 800 ) ),
		cos_h26_picture( 'card-serum-texture', array( 480, 720, 960 ), array( 4, 5 ), 'The Advanced Repair Serum among clear, pearl-like drops', array( 'sizes' => $sz, 'w' => 720, 'class' => 'hxpd-sci__pack' ) ),
		'Skin showing fine lines, dullness or a loss of firmness, or feeling the strain of everyday environmental stress. Used morning and evening, after the Micellar Water.',
		cos_hxpd_attachment_picture( 202, 'A woman in a white halter top resting the Advanced Repair Serum against her arm', $sz ) ),
	'COS-003' => array( 'third',
		'A rich, melting eye cream made in Zug, with micro-encapsulated caviar extract, caffeine, vitamin K2 and hyaluronic acid in three molecular weights.',
		'Encapsulation calibrated for the thinnest skin of the face. Tapped along the orbital bone with the ring finger, it melts on contact, with no dragging and no heaviness.',
		cos_h26_picture( 'caviar-macro', array( 480, 600 ), array( 4, 5 ), 'Black caviar heaped on a silver spoon', array( 'sizes' => $sz, 'w' => 600 ) ),
		cos_h26_picture( 'card-eye-texture', array( 480, 720, 960 ), array( 4, 5 ), 'The Revitalizing Eye Cream among clear, pearl-like drops', array( 'sizes' => $sz, 'w' => 720, 'class' => 'hxpd-sci__pack' ) ),
		'An eye contour showing fine lines, dark circles, dryness or visible fatigue. Allow it a moment to absorb before make-up or SPF.',
		cos_hxpd_attachment_picture( 182, 'A woman holding the Revitalizing Eye Cream beside her face', $sz ) ),
	'COS-004' => array( 'final',
		'A silky day-and-night cream made in Zug, with a caviar lipid complex, hyaluronic acid in three molecular weights and superoxide dismutase, on Swiss glacier meltwater.',
		'The caviar lipid complex mirrors the skin’s own lipids to replenish its barrier, while three weights of hyaluronic acid hold moisture: the last step seals in the ones before it.',
		cos_h26_picture( 'card-cream-texture', array( 480, 720, 960 ), array( 4, 5 ), 'The 24h Regeneration Cream beside a sweep of its texture', array( 'sizes' => $sz, 'w' => 720, 'class' => 'hxpd-sci__pack' ) ),
		cos_hxpd_attachment_picture( 176, 'A woman holding a jar of 24h Regeneration Cream close to her face', $sz ),
		'Skin that feels dry or dehydrated, or shows fine lines and a loss of elasticity. Rich enough for the night, light enough to wear under SPF by day.',
		cos_hxpd_attachment_picture( 179, 'A woman in a pale halter top resting a jar of 24h Regeneration Cream on her arm', $sz ) ),
);
$sc  = $sci_by[ $args['sku'] ] ?? $sci_by['COS-001'];
$sci = array(
	array( 'What it is', $sc[1], $pack ),
	array( 'How it works', $sc[2], $sc[3] ),
	array( 'What it does', $does, $sc[4] ),
	array( 'Who it is for', $sc[5], $sc[6] ),
);
?>
<section class="hxpd-sci" id="hxpd-sci" aria-labelledby="hxpd-sci-title">
	<div class="hx-wrap">
		<div class="hxpd-sci__head">
			<h2 id="hxpd-sci-title" class="hx-heading hxpd-sci__title" data-hx-reveal>The science of the formula</h2>
			<p class="hx-lead hxpd-sci__lede" data-hx-reveal>What is inside the <?php echo esc_html( $sc[0] ); ?> step of the ritual, how it works on the skin, and who it suits.</p>
		</div>
		<ul class="hxpd-sci__list" data-hx-stagger>
			<?php foreach ( $sci as $c ) : ?>
			<li class="hxpd-sci__card" data-hx-reveal>
				<figure class="hxpd-sci__media"><?php echo $c[2]; // phpcs:ignore -- escaped in the picture helpers ?></figure>
				<h3 class="hxpd-sci__name"><?php echo esc_html( $c[0] ); ?></h3>
				<p class="hxpd-sci__text"><?php echo esc_html( $c[1] ); ?></p>
			</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>
<?php endif; ?>

<?php if ( $story_h || $story_p ) : ?>
<section class="hxpd-story" id="hxpd-story" aria-labelledby="hxpd-story-title">
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
<?php if ( $g ) : ?>
<?php
// one authored drawing per active (owner, 28 Oct 2026: on all four formulas' pages)
$hex = static function ( float $cx, float $cy, float $r ): array { // pointy-top hexagon vertices
	return array_map( static fn( $k ) => array( $cx + $r * cos( M_PI / 6 + $k * M_PI / 3 ), $cy + $r * sin( M_PI / 6 + $k * M_PI / 3 ) ), range( 0, 5 ) );
};
$ring = static function ( array $pts, int $dot = 4 ): string { // outline + a pearl at each vertex
	$d = 'M' . implode( 'L', array_map( static fn( $p ) => sprintf( '%.1f %.1f', $p[0], $p[1] ), $pts ) ) . 'z';
	return '<path d="' . $d . '" fill="none" stroke="#3A3B3D" stroke-width="1.4"/>' . implode( '', array_map( static fn( $p ) => sprintf( '<circle cx="%.1f" cy="%.1f" r="%d" fill="url(#hxo-pearl)"/>', $p[0], $p[1], $dot ), $pts ) );
};
$icons = array(
	// caviar: a cluster of glossy pearls
	'caviar'    => '<circle cx="38" cy="56" r="14" fill="url(#hxo-pearl)"/><circle cx="62" cy="58" r="12" fill="url(#hxo-pearl)"/><circle cx="50" cy="38" r="12" fill="url(#hxo-pearl)"/><circle cx="66" cy="38" r="8" fill="url(#hxo-pearl)"/><circle cx="34" cy="34" r="7" fill="url(#hxo-pearl)"/><circle cx="50" cy="72" r="8" fill="url(#hxo-pearl)"/>',
	// micro-encapsulated caviar: pearls held inside a clear shell
	'capsule'   => '<circle cx="50" cy="50" r="36" fill="url(#hxo-drop)" opacity=".55" stroke="#9A9B9D" stroke-width=".8"/><circle cx="42" cy="56" r="11" fill="url(#hxo-pearl)"/><circle cx="60" cy="54" r="9" fill="url(#hxo-pearl)"/><circle cx="50" cy="38" r="9" fill="url(#hxo-pearl)"/>',
	// micelle: a ring of heads with tails turned in, a speck of make-up held at the centre
	'micelle'   => '<g fill="none" stroke="#3A3B3D" stroke-width="1.2"><circle cx="50" cy="50" r="7" fill="#1C1D1F" stroke="none" opacity=".75"/>' . implode( '', array_map( static function ( $k ) { $a = $k * M_PI / 8; $x = 50 + 28 * cos( $a ); $y = 50 + 28 * sin( $a ); $x2 = 50 + 15 * cos( $a ); $y2 = 50 + 15 * sin( $a ); return sprintf( '<line x1="%.1f" y1="%.1f" x2="%.1f" y2="%.1f"/><circle cx="%.1f" cy="%.1f" r="4.2" fill="url(#hxo-pearl-l)" stroke="none"/>', $x, $y, $x2, $y2, $x, $y ); }, range( 0, 15 ) ) ) . '</g>',
	// moisture (panthenol, hyaluronic acid): drops in three sizes
	'drops'     => '<g fill="url(#hxo-drop)" stroke="#9A9B9D" stroke-width=".8"><path d="M50 20c9 13 15 21 15 30a15 15 0 0 1-30 0c0-9 6-17 15-30z"/><path d="M30 52c5 7 8 11 8 16a8 8 0 0 1-16 0c0-5 3-9 8-16z"/><path d="M71 58c4 6 7 9 7 13a7 7 0 0 1-14 0c0-4 3-7 7-13z"/></g>',
	// allantoin: its molecule, two rings joined
	'allantoin' => '<g fill="none" stroke="#3A3B3D" stroke-width="1.4"><path d="M24 50l10-17h20l10 17-10 17H34z"/><path d="M64 50l14-10 10 15-10 15z" /></g><g fill="url(#hxo-pearl)"><circle cx="24" cy="50" r="4"/><circle cx="34" cy="33" r="4"/><circle cx="54" cy="33" r="4"/><circle cx="64" cy="50" r="4"/><circle cx="54" cy="67" r="4"/><circle cx="34" cy="67" r="4"/><circle cx="78" cy="40" r="4"/><circle cx="88" cy="55" r="4"/><circle cx="78" cy="70" r="4"/></g>',
	// tripeptide: three amino acids on a chain, each with its side group
	'peptide'   => '<g fill="none" stroke="#3A3B3D" stroke-width="1.4"><path d="M16 60L38 42L62 58L84 40"/><path d="M38 42V24M62 58v18M84 40V24"/></g><g fill="url(#hxo-pearl)"><circle cx="16" cy="60" r="5"/><circle cx="38" cy="42" r="8"/><circle cx="62" cy="58" r="8"/><circle cx="84" cy="40" r="5"/></g><g fill="url(#hxo-pearl-l)" stroke="#9A9B9D" stroke-width=".8"><circle cx="38" cy="22" r="5"/><circle cx="62" cy="78" r="5"/><circle cx="84" cy="22" r="5"/></g>',
	// resveratrol: two rings joined by a double bond
	'stilbene'  => $ring( $hex( 22, 50, 13 ) ) . $ring( $hex( 78, 50, 13 ) ) . '<path d="M33.3 46.5H66.7M33.3 53.5H66.7" stroke="#3A3B3D" stroke-width="1.4"/>',
	// caffeine: a six-ring fused to a five-ring
	'caffeine'  => $ring( $hex( 40, 50, 16 ) ) . $ring( array( array( 53.9, 42 ), array( 69.1, 37.1 ), array( 78.5, 50 ), array( 69.1, 62.9 ), array( 53.9, 58 ) ) ),
	// vitamin K2: two fused rings and its long side chain
	'k2'        => $ring( $hex( 26, 50, 14 ) ) . $ring( $hex( 50.2, 50, 14 ) ) . '<path d="M62.3 43L70 50l8-7 8 7 8-7" fill="none" stroke="#3A3B3D" stroke-width="1.4"/><circle cx="94" cy="43" r="3" fill="url(#hxo-pearl)"/>',
	// Swiss glacier meltwater: an ice crystal
	'crystal'   => '<g stroke="#3A3B3D" stroke-width="1.4" fill="none">' . implode( '', array_map( static function ( $k ) { $a = $k * M_PI / 3 - M_PI / 2; $c = cos( $a ); $s = sin( $a ); $b = static fn( $t, $o ) => sprintf( '%.1f %.1f', 50 + $t * $c - $o * $s, 50 + $t * $s + $o * $c ); return '<path d="M50 50L' . $b( 36, 0 ) . 'M' . $b( 20, 0 ) . 'L' . $b( 28, 8 ) . 'M' . $b( 20, 0 ) . 'L' . $b( 28, -8 ) . '"/>'; }, range( 0, 5 ) ) ) . '</g>' . implode( '', array_map( static fn( $k ) => sprintf( '<circle cx="%.1f" cy="%.1f" r="4" fill="url(#hxo-pearl-l)" stroke="#9A9B9D" stroke-width=".8"/>', 50 + 36 * cos( $k * M_PI / 3 - M_PI / 2 ), 50 + 36 * sin( $k * M_PI / 3 - M_PI / 2 ) ), range( 0, 5 ) ) ) . '<circle cx="50" cy="50" r="5" fill="url(#hxo-pearl)"/>',
	// superoxide dismutase: a folded protein chain around its metal centre
	'enzyme'    => '<path d="M18 62C18 30 44 22 50 40S30 70 46 76 82 70 78 46 56 22 66 18" fill="none" stroke="#3A3B3D" stroke-width="1.6" stroke-linecap="round"/><circle cx="52" cy="54" r="9" fill="url(#hxo-pearl)"/><circle cx="18" cy="62" r="4" fill="url(#hxo-pearl-l)" stroke="#9A9B9D" stroke-width=".8"/><circle cx="66" cy="18" r="4" fill="url(#hxo-pearl-l)" stroke="#9A9B9D" stroke-width=".8"/>',
);
$orb = array_map( static fn( $n ) => $icons[ $n ], array(
	'COS-001' => array( 'caviar', 'micelle', 'drops', 'allantoin' ),
	'COS-002' => array( 'capsule', 'peptide', 'drops', 'stilbene' ),
	'COS-003' => array( 'capsule', 'caffeine', 'k2', 'drops' ),
	'COS-004' => array( 'caviar', 'drops', 'crystal', 'enzyme' ),
)[ $args['sku'] ] ?? array() );
?>
<section class="hxpd-orbs" id="hxpd-actives" aria-labelledby="hxpd-actives-title">
	<svg width="0" height="0" aria-hidden="true" focusable="false" style="position:absolute"><defs>
		<radialGradient id="hxo-pearl" cx="35%" cy="30%" r="75%"><stop offset="0" stop-color="#8A8C8F"/><stop offset=".35" stop-color="#2A2B2D"/><stop offset="1" stop-color="#050505"/></radialGradient>
		<radialGradient id="hxo-pearl-l" cx="35%" cy="30%" r="75%"><stop offset="0" stop-color="#FFFFFF"/><stop offset=".5" stop-color="#C9CBCE"/><stop offset="1" stop-color="#7C7E81"/></radialGradient>
		<linearGradient id="hxo-drop" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#FFFFFF"/><stop offset=".55" stop-color="#DADCDF"/><stop offset="1" stop-color="#A9ACB0"/></linearGradient>
	</defs></svg>
	<div class="hx-wrap hxpd-orbs__grid">
		<div class="hxpd-orbs__head">
			<h2 id="hxpd-actives-title" class="hx-heading hxpd-orbs__title" data-hx-reveal>What you put on your skin matters</h2>
			<p class="hxpd-orbs__more" data-hx-reveal><a class="hxpd-orbs__link" href="#hxpd-ingredients" data-hxpd-open="hxpd-ingredients">See all ingredients<span class="hxpd-orbs__arrow" aria-hidden="true"></span></a></p>
		</div>
		<ol class="hxpd-orbs__list" data-hx-stagger>
			<?php foreach ( $pd['actives'] as $i => $a ) : ?>
			<li class="hxpd-orbs__item" data-hx-reveal>
				<span class="hxpd-orbs__orb" aria-hidden="true"><svg viewBox="0 0 100 100" focusable="false"><?php echo $orb[ $i ] ?? ''; // phpcs:ignore -- authored markup ?></svg></span>
				<h3 class="hxpd-orbs__name"><?php echo esc_html( $a[0] ); ?></h3>
				<p class="hxpd-orbs__text"><?php echo esc_html( $a[1] ); ?></p>
			</li>
			<?php endforeach; ?>
		</ol>
		<details class="hxpd-acc__item hxpd-orbs__inci" id="hxpd-ingredients">
			<summary class="hxpd-acc__sum">Full ingredient list<span class="hxpd-acc__icon" aria-hidden="true"></span></summary>
			<p class="hxpd-inci"><?php echo esc_html( $pd['inci'] ?? '' ); ?></p>
			<p class="hxpd-inci__note">Refer to the packaging for the most up-to-date list.</p>
		</details>
	</div>
</section>
<?php else : ?>
<section class="hxpd-actives" id="hxpd-actives" aria-labelledby="hxpd-actives-title">
	<div class="hx-wrap<?php echo $g ? ' hxpd-actives__grid' : ''; ?>">
		<?php if ( $g ) : ?>
		<figure class="hxpd-actives__media hx-frame" data-hx-reveal="settle"><?php echo cos_h26_picture( 'card-cleanser-texture', array( 480, 720, 960 ), array( 4, 5 ), 'The Micellar Water All-in-One Cleanser among clear drops of water', array( 'sizes' => '(min-width: 1024px) 40vw, 100vw', 'w' => 960 ) ); // phpcs:ignore ?></figure>
		<div class="hxpd-actives__body">
		<?php endif; ?>
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
		<?php if ( $g ) : ?>
		<details class="hxpd-acc__item hxpd-actives__inci" id="hxpd-ingredients">
			<summary class="hxpd-acc__sum">Full ingredient list<span class="hxpd-acc__icon" aria-hidden="true"></span></summary>
			<p class="hxpd-inci"><?php echo esc_html( $pd['inci'] ?? '' ); ?></p>
			<p class="hxpd-inci__note">Refer to the packaging for the most up-to-date list.</p>
		</details>
		</div>
		<?php else : ?>
		<p class="hxpd-actives__more"><a class="hx-link" href="#hxpd-ingredients" data-hxpd-open="hxpd-ingredients">Full ingredient list<span class="hx-arrow" aria-hidden="true"></span></a></p>
		<?php endif; ?>
	</div>
</section>
<?php endif; ?>
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
<section class="hxpd-use" id="hxpd-use" aria-labelledby="hxpd-use-title">
	<div class="hx-wrap hxpd-use__grid<?php echo ( empty( $pd['editorial'] ) && ! $g ) ? ' is-text' : ''; ?>">
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
		<?php elseif ( $g ) : ?>
		<figure class="hxpd-use__media hx-frame" data-hx-reveal="settle"><?php echo cos_h26_picture( 'auth-stone-cleanser', array( 480, 640, 960 ), array( 4, 5 ), 'The Micellar Water All-in-One Cleanser standing on dark stone', array( 'sizes' => '(min-width: 1440px) 560px, 40vw', 'w' => 960 ) ); // phpcs:ignore ?></figure>
		<?php endif; ?>
	</div>
</section>
<?php endif; ?>

<?php if ( $g ) : // owner, 28 Oct 2026: exactly the eye cream page's "Your streamlined routine", with this page's title ?>
<section class="hxpd-steps" id="hxpd-ritual" aria-labelledby="hxpd-ritual-title">
	<div class="hx-wrap">
		<div class="hxpd-steps__head">
			<h2 id="hxpd-ritual-title" class="hx-heading hxpd-steps__title" data-hx-reveal>Complete your ritual</h2>
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
<?php else : ?>
<section class="hxpd-ritual" id="hxpd-ritual" aria-labelledby="hxpd-ritual-title">
	<div class="hx-wrap">
		<div class="hxpd-ritual__head">
			<h2 id="hxpd-ritual-title" class="hx-heading hxpd-ritual__title" data-hx-reveal><?php echo $g ? 'Complete your ritual' : 'Within the ritual'; ?></h2>
			<p class="hx-lead hxpd-ritual__lede" data-hx-reveal>Each formula prepares the skin for the next. Used together, morning and evening, they make one complete ritual.</p>
		</div>
		<ol class="hxpd-ritual__list">
			<?php foreach ( $args['formulas'] as $f ) :
				$tag = $f['current'] ? 'div' : 'a'; ?>
			<li class="hxpd-ritual__item<?php echo $f['current'] ? ' is-current' : ''; ?>" data-hx-reveal>
				<<?php echo $tag; ?> class="hxpd-ritual__link"<?php echo $f['current'] ? ' aria-current="page"' : ' href="' . esc_url( $f['url'] ) . '"'; ?>>
					<span class="hxpd-ritual__stage"><?php echo cos_h26_picture( 'pack-' . $f['key'], array( 480, 720, 960 ), array( 2, 3 ), '', array( 'class' => 'hxpd-ritual__object', 'sizes' => '(min-width: 1024px) 180px, 22vw', 'w' => 480 ) ); // phpcs:ignore ?></span>
					<span class="hxpd-ritual__step"><span class="hx-step"><?php echo esc_html( $f['num'] ); ?></span> <?php echo esc_html( $f['verb'] ); ?></span>
					<span class="hxpd-ritual__name"><?php echo esc_html( $f['full'] ); ?></span>
					<?php if ( $f['current'] ) : ?><span class="hxpd-ritual__here">This formula</span><?php endif; ?>
					<?php if ( $g && ! empty( $f['price'] ) ) : ?><span class="hxpd-ritual__price"><?php echo esc_html( $f['price'] ); ?> <span aria-hidden="true">·</span> <?php echo esc_html( $f['size'] ); ?></span><?php endif; ?>
				</<?php echo $tag; ?>>
				<?php if ( $g && ! $f['current'] && ! empty( $f['buy'] ) ) : ?>
				<a class="hxpd-ritual__add add_to_cart_button ajax_add_to_cart" href="<?php echo esc_url( add_query_arg( 'add-to-cart', $f['pid'], get_permalink() ) ); ?>" data-product_id="<?php echo (int) $f['pid']; ?>" data-quantity="1" rel="nofollow" aria-label="<?php echo esc_attr( sprintf( 'Add %s to bag', $f['full'] ) ); ?>">Add to bag</a>
				<?php endif; ?>
			</li>
			<?php endforeach; ?>
		</ol>
		<?php if ( $args['ritual'] ) : ?>
		<p class="hxpd-ritual__set" data-hx-reveal><a class="hx-link" href="<?php echo esc_url( $args['ritual']['url'] ); ?>" data-cro-cta="pdp-secondary-learn-ritual">The Complete Ritual, all four formulas, <?php echo esc_html( $args['ritual']['price'] ); ?><span class="hx-arrow" aria-hidden="true"></span></a></p>
		<?php endif; ?>
	</div>
</section>
<?php endif; ?>

<?php if ( ! empty( $pd['faqs'] ) ) : ?>
<section class="hxpd-faq" id="hxpd-faq" aria-labelledby="hxpd-faq-title">
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
