<?php
/**
 * The Ritual — the Complete Ritual offer (hxrt-offer), the page's ONE primary action.
 * The four formulas stand as objects (pack-*, single-product canvases cut like the homepage Collection's
 * transparent packshots; never altered). Price, the separate total and the saving are live
 * WooCommerce prices (page-the-ritual.php); the saving line only shows when it is real.
 * What is included: the bundle data in inc/product-data.php (COS-BUNDLE).
 * data-cro-cta keeps the old page's primary id (ritual-hero-primary) so CRO reporting continues.
 */
defined( 'ABSPATH' ) || exit;
$steps = $args['steps'] ?? array();
$o     = $args['offer'] ?? array();
?>
<section id="complete-ritual" class="hxrt-offer" aria-labelledby="hxrt-offer-title">
	<div class="hx-wrap hxrt-offer__inner">
		<ol class="hxrt-offer__objects" aria-label="Included" data-hx-stagger>
			<?php foreach ( $steps as $s ) : ?>
			<li class="hxrt-offer__object" data-hx-reveal>
				<?php echo cos_h26_picture( 'pack-' . $s['key'], array( 480, 720, 960 ), array( 2, 3 ), $s['cardalt'], array( 'class' => 'hxrt-offer__pic', 'sizes' => '(min-width: 900px) 13vw, 24vw', 'w' => 480 ) ); ?>
				<span class="hxrt-offer__cap"><span class="hxrt-offer__num"><?php echo esc_html( $s['num'] ); ?></span> <?php echo esc_html( $s['verb'] ); ?></span>
			</li>
			<?php endforeach; ?>
		</ol>

		<div class="hxrt-offer__copy">
			<h2 id="hxrt-offer-title" class="hx-heading hxrt-offer__title" data-hx-reveal>The Complete <em>Ritual</em></h2>
			<p class="hx-body hxrt-offer__lede" data-hx-reveal>All four full-size formulas, presented in the Caviar of Switzerland ritual box with a written ritual guide. For anyone ready to adopt the complete ritual rather than building it product by product.</p>

			<?php if ( ! empty( $o['price'] ) ) : ?>
			<p class="hxrt-offer__price" data-hx-reveal>
				<span class="hxrt-offer__amount"><?php echo esc_html( $o['price'] ); ?></span>
				<?php if ( ! empty( $o['saving'] ) ) : ?>
				<span class="hxrt-offer__compare"><?php echo esc_html( $o['separate'] ); ?> bought separately<span class="hx-sr">,</span><span aria-hidden="true"> ·</span> you save <?php echo esc_html( $o['saving'] ); ?></span>
				<?php endif; ?>
			</p>
			<?php endif; ?>

			<div class="hxrt-offer__act" data-hx-reveal>
				<a class="hx-btn hxrt-offer__btn" href="<?php echo esc_url( $o['url'] ?? home_url( '/products/the-complete-ritual/' ) ); ?>" data-cro-cta="ritual-hero-primary">Shop the Complete Ritual<span class="hx-arrow" aria-hidden="true"></span></a>
				<ul class="hxrt-offer__assure" aria-label="Delivery and returns">
					<li>Free worldwide shipping</li>
					<li>30-day returns</li>
				</ul>
			</div>
		</div>
	</div>
</section>
