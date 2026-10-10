<?php
/**
 * 02b — The Complete Ritual: the closing section, after the evidence (owner,
 * 4 Oct 2026: proof before price; a compact buy line also sits under the
 * clinical evidence in trust.php). The complete set on the silver tray (retouched export,
 * Downloads/ChatGPT Image 27 Sept 2026) beside one
 * call to action: the Complete Ritual bundle. The collection link lives in the
 * hero; this section asks for one thing only. 4 Oct 2026: one line of
 * storytelling, the price on its own line, and a shorter CTA; shipping and
 * returns sit under the CTA (owner: only these two, 6 Oct 2026).
 */
defined( 'ABSPATH' ) || exit;
$ritual = $args['ritual'];

// Service assurances under the CTA (terms from /shipping-returns/).
$assurances = array(
	array( 'Free worldwide shipping', 'On every order', '<circle cx="16" cy="16" r="10"/><path d="M6 16h20M16 6c3 3 4.5 6.4 4.5 10S19 23 16 26c-3-3-4.5-6.4-4.5-10S13 9 16 6Z"/>' ),
	array( '30-day returns', 'Hassle-free returns', '<path d="M9 13h11a6 6 0 0 1 0 12h-7"/><path d="m13 9-4 4 4 4"/>' ),
);
?>
<section class="h26-final" aria-labelledby="h26-final-title">
	<div class="h26-final__media h26-reveal">
		<?php
		echo cos_h26_picture(
			'final-tray',
			array( 640 ),
			array( 4, 5 ),
			'The four formulas of the Complete Ritual, Advanced Repair Serum, 24h Regeneration Cream, Revitalizing Eye Cream and Micellar Water Cleanser, presented on a silver tray held on an outstretched hand',
			array(
				'sizes' => '50vw',
				'w'     => 640,
				// Desktop: full-bleed 16:9 frame (backdrop extended left for the copy).
				// Phones/tablets: a tighter square crop so the bottles fill the frame.
				'art'   => array(
					array( 'media' => '(min-width: 900px)', 'base' => 'final-tray-wide', 'widths' => array( 1280, 1920, 2560 ), 'sizes' => '100vw' ),
					array( 'media' => '(max-width: 899.98px)', 'base' => 'final-tray-sq', 'widths' => array( 560 ), 'sizes' => '100vw' ),
				),
			)
		);
		?>
	</div>
	<div class="h26-final__body h26-reveal">
		<p class="h26-eyebrow">Four full-size formulas</p>
		<h2 id="h26-final-title" class="h26-final__title">The Complete Ritual</h2>
		<?php /* Who it suits + how the formulas complement one another (5 Oct 2026, owner).
		   Sources: COS-BUNDLE who_for/concerns/benefits and each product's description
		   in inc/product-data.php. Kept short (owner, 6 Oct): the step-by-step
		   explanation lives on /the-ritual/ ("Each step prepares the skin for the next"). */ ?>
		<p class="h26-final__lede">For loss of firmness, fine lines and dryness. Gentle on most skin types.</p>
		<ol class="h26-final__steps" aria-label="The four steps">
			<?php /* Critique 3 (owner, 7 Oct 2026): name what the price buys, product + size per step. */ ?>
			<?php foreach ( cos_h26_products() as $p ) : ?><li class="h26-final__step"><span class="h26-final__step-num"><?php echo esc_html( $p['num'] ); ?></span><span class="h26-final__step-verb"><?php echo esc_html( $p['verb'] ); ?><span class="h26-sr">:</span></span><span class="h26-final__step-name"><?php echo esc_html( implode( ' ', $p['name'] ) ); ?> <span class="h26-final__step-size">· <?php echo esc_html( $p['size'] ); ?></span></span></li><?php endforeach; ?>
		</ol>
		<?php if ( $ritual ) :
			// Only compare when the live separate prices really total more.
			$post     = get_page_by_path( 'the-complete-ritual', OBJECT, 'product' );
			$wc       = ( $post && function_exists( 'wc_get_product' ) ) ? wc_get_product( $post ) : null;
			$separate = cos_h26_individual_total();
			$compare  = $wc && $separate > (float) $wc->get_price();
			?>
		<?php /* Calm invitation (owner, 6 Oct 2026): photo, main price and CTA lead; the
		   individual-price comparison is a subordinate line; shipping + returns sit
		   grouped beneath the CTA. */ ?>
		<p class="h26-final__amount"><span class="h26-sr">Price: </span><?php echo esc_html( $ritual ); ?></p>
		<?php if ( $compare ) : ?><p class="h26-final__separate"><?php echo esc_html( html_entity_decode( wp_strip_all_tags( wc_price( $separate, array( 'decimals' => 0 ) ) ), ENT_QUOTES, 'UTF-8' ) . ' if bought individually' ); ?></p><?php endif; ?>
		<?php endif; ?>
		<a class="h26-btn h26-btn--dark h26-final__cta" href="<?php echo esc_url( home_url( '/products/the-complete-ritual/' ) ); ?>" data-cro-cta="home-final-ritual">Shop the Complete Ritual <?php echo cos_h26_arrow(); ?></a>
		<ul class="h26-final__facts" role="list" aria-label="Delivery and returns">
			<?php foreach ( $assurances as [ $k, $v, $icon ] ) : ?>
			<li class="h26-final__fact">
				<svg class="h26-final__icon" viewBox="0 0 32 32" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><?php echo $icon; // static markup above ?></svg>
				<span class="h26-final__fact-k"><?php echo esc_html( $k ); ?></span>
			</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>
