<?php
/**
 * 06 — The Ritual (14 Oct 2026 rebuild; styles: assets/css/hx/ritual.css, prefix hxr-).
 *
 * The four steps, Cleanse · Treat · Revive · Restore, as tall monochrome portrait
 * panels separated by hairline gutters — the owner-shaped presentation of 13 Oct,
 * refined: the step word reads upward in the top-left corner; "Step N" stays on the
 * centre axis (owner, 15 Oct: "leave the Step one where it is"), low on the panel clear
 * of the packaging; on hover a centred hairline and the explanation in balanced lines
 * settle in beneath it; no buttons (the whole panel links to that formula), no scroll animation.
 *
 * Pointer screens ≥900px: on hover or keyboard focus the photograph returns to
 * colour and slowly enlarges, the label rises, a hairline draws and what the step
 * does (owner, 11 Oct: explain the step, not the product name) settles in beneath.
 * Touch screens and anything narrower: the explanation is always visible as a
 * caption under the photograph ("Step N" heads it); below 900px the panels become a
 * snap-swipe row with a hairline progress track, and the panel in front returns to
 * colour as it is swiped into place (CSS scroll-driven; static monochrome where
 * unsupported).
 *
 * The page's single closing call to action and the service assurances now live in
 * the closing section (closing.php), so the offer band that used to end this
 * section is gone. Earlier versions: design-sources/home-2026/alt-final-*.
 * Restored 16 Oct to the round-3 approved state (an unwanted 15 Oct evening rewrite is
 * archived in design-sources/archive-15oct-evening-edits/).
 */
defined( 'ABSPATH' ) || exit;

// Each step: the model photograph and what the step does
// (owner, 11 Oct 2026: explain the step, not the product name). Wording drawn from each
// product's 'how' in inc/product-data.php; cosmetic description only (no regeneration claims).
// Photographs: hxr-{step} (4:5, phones) and hxr-{step}-tall (1:2, >=900px), graded from the
// owner's full-resolution studio originals by design-sources/home-2026/build_ritual_panels.py.
$steps = array(
	'micellar-water-cleanser' => array( 'cleanser', 'Sweep a soaked cotton pad over face and neck to lift make-up and the day’s impurities. No rinsing: the skin is ready for what follows.' ),
	'advanced-repair-serum'   => array( 'serum', 'The most concentrated step. Press two or three drops into face and neck, then give them a minute to absorb.' ),
	'revitalizing-eye-cream'  => array( 'eye', 'Care for the thinnest skin of the face. Pat a little along the orbital bone, from the inner to the outer corner. Never drag.' ),
	'24h-regeneration-cream'  => array( 'cream', 'The final step seals the ritual. Warm a pea-sized amount between your palms and press it in, under SPF by day and overnight by night.' ),
);
$nbh = static function ( string $s ): string {
	return str_replace( '-', "\u{2011}", $s ); // non-breaking hyphens: "make-up", "pea-sized" never split
};
?>
<section id="ritual" class="hxr" aria-labelledby="hxr-title">
	<header class="hxr__head">
		<h2 id="hxr-title" class="hxr__title" data-hx-reveal="light"><span>The</span> <em>Ritual</em></h2>
		<?php /* Round 2 (15 Oct): one line, the ritual's own (the order of application) — who-it-suits copy lives on the product page. */ ?>
		<p class="hxr__lede" data-hx-reveal data-hx-delay="200">Always in this order, from the lightest texture to the richest.</p>
	</header>

	<div class="hxr__stage">
		<ol class="hxr__steps" aria-label="The four steps" data-hx-reveal="blind">
			<?php
			foreach ( cos_h26_products() as $p ) :
				$st  = $steps[ $p['slug'] ];
				$num = (int) $p['num'];
				?>
			<li class="hxr__step">
				<a class="hxr__link" href="<?php echo esc_url( $p['url'] ); ?>">
					<span class="hxr__frame">
						<?php
						echo cos_h26_picture(
							'hxr-' . $st[0],
							array( 480, 720, 960 ),
							array( 4, 5 ),
							'', // the link's text names the step; the photograph is atmosphere
							array(
								'class' => 'hxr__shot',
								'sizes' => '(min-width: 700px) 46vw, 86vw',
								'w'     => 480,
								'art'   => array( array( 'media' => '(min-width: 900px)', 'base' => 'hxr-' . $st[0] . '-tall', 'widths' => array( 480, 720, 960, 1200 ), 'sizes' => '(min-width: 1540px) 360px, 23vw' ) ),
							)
						);
						?>
						<span class="hxr__word" aria-hidden="true"><?php echo esc_html( $p['verb'] ); ?></span>
					</span>
					<span class="hxr__text">
						<span class="hxr__stepno">Step <?php echo $num; ?></span><span class="hx-sr">: <?php echo esc_html( $p['verb'] ); ?>, </span>
						<?php /* 29 Oct 2026 (homepage critique; owner chose it): the formula is named at rest, on every
						   screen, so each step maps to its product; the hover still explains the step (11 Oct ruling). */ ?>
						<span class="hxr__name"><?php echo esc_html( $nbh( $p['name'][0] . ' ' . $p['name'][1] ) ); ?><span class="hx-sr">. </span></span>
						<span class="hxr__more"><span class="hxr__inner"><span class="hxr__rule" aria-hidden="true"></span><span class="hxr__how"><?php echo esc_html( $nbh( $st[1] ) ); ?></span></span></span>
					</span>
				</a>
			</li>
			<?php endforeach; ?>
		</ol>
		<span class="hxr__track" aria-hidden="true"><span class="hxr__thumb"></span></span>
		<p class="hxr__count" aria-hidden="true"><span data-hxr-i>1</span> / 4</p>
	</div>
</section>
<script nonce="<?php echo esc_attr( function_exists( 'cos_csp_nonce' ) ? cos_csp_nonce() : '' ); ?>">
(function(){
  // Below 900px the steps are a snap-swipe row: say which of the four is in front (decorative; the list is the real structure).
  var row=document.querySelector('.hxr__steps'),out=document.querySelector('[data-hxr-i]');
  if(!row||!out) return;
  var items=row.querySelectorAll('.hxr__step'),raf=0;
  function update(){raf=0;var mid=row.getBoundingClientRect().left+row.clientWidth/2,best=0,d=1e9;
    items.forEach(function(it,i){var r=it.getBoundingClientRect(),c=Math.abs(r.left+r.width/2-mid);if(c<d){d=c;best=i;}});
    out.textContent=String(best+1);}
  row.addEventListener('scroll',function(){if(!raf)raf=requestAnimationFrame(update);},{passive:true});
})();
</script>
