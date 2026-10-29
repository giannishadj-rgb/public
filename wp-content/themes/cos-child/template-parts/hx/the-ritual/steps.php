<?php
/**
 * The Ritual — part 2, step by step (#step-by-step; its own h2 since the clarify pass of
 * 23 Oct 2026, steps are h3 and carry the bar's "Step N of 4" label). The science note and
 * links moved here from the former order section. The four steps (hxrt-step), one chapter each, alternating sides on wide
 * screens. Model photography: hxrt-step-* (the homepage Ritual panels' crops and grade,
 * exported larger; build_the_ritual_page.py), shown in colour and never held back by a
 * scroll reveal (critic r1: images must arrive with their text).
 * Each chapter: step number, verb (h2), product, what it does, amount / when / size, then
 * "How to apply" (product data) and "Who it suits" as native <details>: open on wide
 * screens (set by the small script below), collapsed on phones to keep the page short.
 * Without JS they stay collapsed and remain one tap away.
 */
defined( 'ABSPATH' ) || exit;
$steps = $args['steps'] ?? array();
$url   = $args['url'] ?? static fn( string $p ): string => home_url( $p );
$nb    = static fn( string $s ): string => str_replace( '-', "\u{2011}", $s ); // keep make-up, pea-sized whole
?>
<section id="step-by-step" class="hxrt-steps" aria-labelledby="hxrt-steps-title">
	<div class="hx-wrap">
		<header class="hxrt-part__head hxrt-steps__head">
			<h2 id="hxrt-steps-title" class="hx-heading" data-hx-reveal>Step by step</h2>
			<p class="hx-lead hxrt-part__lede" data-hx-reveal>How much to use, how to apply it, and who each formula suits.</p>
		</header>
	</div>
	<?php foreach ( $steps as $i => $s ) :
		$n    = (int) $s['num'];
		$slug = strtolower( $s['verb'] );
		$name = $s['name'][0] . ' ' . $s['name'][1];
		?>
	<article id="step-<?php echo esc_attr( $slug ); ?>" class="hxrt-step<?php echo $i % 2 ? ' hxrt-step--flip' : ''; ?>" aria-labelledby="hxrt-step-<?php echo $n; ?>" data-hxrt-label="<?php echo esc_attr( sprintf( 'Step %d of %d · %s', $n, count( $steps ), $s['verb'] ) ); ?>">
		<div class="hxrt-step__media">
			<div class="hx-frame hxrt-step__frame">
				<?php
				echo cos_h26_picture(
					'hxrt-step-' . $s['key'],
					array( 720, 1080, 1400 ),
					array( 4, 5 ),
					$s['alt'],
					array( 'class' => 'hxrt-step__pic', 'sizes' => '(min-width: 1540px) 700px, (min-width: 900px) 46vw, 100vw', 'w' => 1080 )
				);
				?>
			</div>
		</div>
		<div class="hxrt-step__body" data-hx-reveal="fade">
			<p class="hxrt-step__num" aria-hidden="true"><?php echo esc_html( $s['num'] ); ?></p>
			<h3 id="hxrt-step-<?php echo $n; ?>" class="hx-heading hxrt-step__title"><span class="hx-sr">Step <?php echo $n; ?>: </span><?php echo esc_html( $s['verb'] ); ?></h3>
			<p class="hxrt-step__product"><?php echo esc_html( $nb( $name ) ); ?></p>
			<p class="hx-body hxrt-step__what"><?php echo $s['what_html']; // escaped in page-the-ritual.php ?></p>

			<dl class="hxrt-facts">
				<div class="hxrt-facts__row"><dt>Amount</dt><dd><?php echo esc_html( $nb( $s['amount'] ) ); ?></dd></div>
				<div class="hxrt-facts__row"><dt>When</dt><dd><?php echo esc_html( $s['when'] ); ?></dd></div>
			</dl>

			<details class="hxrt-more" data-hxrt-open>
				<summary class="hxrt-more__sum"><span class="hxrt-sub">How to apply</span><span class="hxrt-more__icon" aria-hidden="true"></span></summary>
				<ol class="hxrt-how">
					<?php foreach ( $s['how'] as $line ) : ?>
					<li><?php echo esc_html( $nb( $line ) ); ?></li>
					<?php endforeach; ?>
				</ol>
			</details>
			<details class="hxrt-more" data-hxrt-open>
				<summary class="hxrt-more__sum"><span class="hxrt-sub">Who it suits</span><span class="hxrt-more__icon" aria-hidden="true"></span></summary>
				<p class="hxrt-step__suits-text"><?php echo esc_html( $nb( $s['suits'] ) ); ?></p>
			</details>

			<p class="hxrt-step__shop">
				<a class="hx-link" href="<?php echo esc_url( $s['url'] ); ?>" data-cro-cta="ritual-step-<?php echo $n; ?>-view-product">Discover the <?php echo esc_html( $nb( $s['name'][0] === 'Micellar Water' ? 'Micellar Water' : $name ) ); ?><span class="hx-arrow" aria-hidden="true"></span></a>
				<?php if ( $s['price'] ) : ?><span class="hxrt-step__price"><?php echo esc_html( $s['price'] ); ?><span class="hx-sr">,</span><span aria-hidden="true"> ·</span> <?php echo esc_html( $s['size'] ); ?></span><?php endif; ?>
			</p>
		</div>
	</article>
	<?php endforeach; ?>
	<div class="hx-wrap hxrt-steps__more" data-hx-reveal>
		<p class="hxrt-steps__note">At the heart of every step: caviar extract from sustainably farmed Siberian sturgeon (<i lang="la">Acipenser baerii</i>), formulated in Zug, Switzerland.</p>
		<p class="hxrt-steps__links">
			<a class="hx-link" href="<?php echo esc_url( $url( '/science/' ) ); ?>">Explore Swiss science<span class="hx-arrow" aria-hidden="true"></span></a>
			<a class="hx-link" href="<?php echo esc_url( $url( '/science/clinical-studies/' ) ); ?>">Clinical studies<span class="hx-arrow" aria-hidden="true"></span></a>
		</p>
	</div>
</section>
<script nonce="<?php echo esc_attr( function_exists( 'cos_csp_nonce' ) ? cos_csp_nonce() : '' ); ?>">
(function(){var d=document.querySelectorAll('details[data-hxrt-open]');if(!d.length||!window.matchMedia)return;var m=window.matchMedia('(min-width: 900px)');function s(){for(var i=0;i<d.length;i++)d[i].open=m.matches;}s();if(m.addEventListener)m.addEventListener('change',s);})();
</script>
