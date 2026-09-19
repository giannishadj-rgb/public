<?php
/**
 * COS — WooCommerce Shop Archive
 */
defined( 'ABSPATH' ) || exit;

$products_query = new WP_Query( array(
	'post_type'      => 'product',
	'post_status'    => 'publish',
	'posts_per_page' => -1,
	'orderby'        => 'menu_order',
	'order'          => 'ASC',
	'meta_query'     => array(
		array( 'key' => '_price', 'compare' => 'EXISTS' ),
	),
) );

// The bundle (COS-BUNDLE) is deliberately excluded from this grid -- it's a
// simple product, not a taxonomy, so filtering by SKU here is the only way to
// keep it out of the individual-products listing. It gets its own featured
// section instead (§ Complete Ritual, below) rather than sitting in a grid
// literally labelled "Individual Products".
$individual = array();
$bundle     = null;
if ( $products_query->have_posts() ) {
	while ( $products_query->have_posts() ) {
		$products_query->the_post();
		$wc_product = wc_get_product( get_the_ID() );
		if ( ! $wc_product ) continue;
		if ( $wc_product->get_sku() === 'COS-BUNDLE' ) {
			$bundle = $wc_product;
			continue;
		}
		$individual[] = $wc_product;
	}
	wp_reset_postdata();
}

// Ritual order is hardcoded by SKU for launch. If products are renumbered or
// new steps are added, move this to product meta or a taxonomy term order
// instead of editing this map.
usort( $individual, function( $a, $b ) {
	$steps = array( 'COS-001' => 1, 'COS-002' => 2, 'COS-003' => 3, 'COS-004' => 4 );
	return ( $steps[ $a->get_sku() ] ?? 9 ) - ( $steps[ $b->get_sku() ] ?? 9 );
} );

$step_roles = array(
	'COS-001' => array( 'num' => '01', 'role' => 'Cleanse & Prepare' ),
	'COS-002' => array( 'num' => '02', 'role' => 'Treat & Repair' ),
	'COS-003' => array( 'num' => '03', 'role' => 'Revive & Illuminate' ),
	'COS-004' => array( 'num' => '04', 'role' => 'Moisturise & Protect' ),
);

// Editorial card copy -- shorter and more consumer-facing than the WooCommerce
// short_description (which is written for PDP/SEO use). Keyed by SKU so it
// survives product renumbering the same way $step_roles does.
$card_copy = array(
	'COS-001' => 'The first gesture of the ritual. A gentle yet powerful cleansing water that removes make-up, lifts away impurities, and refreshes the skin in one elegant step.',
	'COS-002' => 'A luxurious concentrated serum created to visibly revive the complexion and restore the look of radiant, youthful skin. +47% perceived firmness at 28 days.',
	'COS-003' => 'A refined anti-aging eye cream that helps smooth, hydrate, and illuminate the delicate eye contour. −38% fine line depth at 28 days.',
	'COS-004' => 'A silky Swiss caviar cream that wraps the skin in lasting comfort and luminous anti-aging care. 100% panellist satisfaction at 28 days.',
);

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<?php cos_child_custom_document_head( 'Shop - Caviar of Switzerland' ); ?>
<?php wp_head(); ?>
</head>
<body <?php body_class( 'woocommerce woocommerce-page' ); ?>>
<?php wp_body_open(); ?>
<style nonce="<?php echo esc_attr( cos_csp_nonce() ); ?>">
#cos-shop{font-family:var(--cos-body);color:var(--cos-ink,#171A1F);background:var(--cos-pearl,#F7F3ED)}
#cos-shop *{box-sizing:border-box}
/* Keep the shop reset inside the shop document. :where() contributes no
   specificity, so the component classes below can still set their own spacing
   without this reset leaking into the shared navigation or footer. */
:where(#cos-shop) :where(p,h1,h2,h3,ul,li){margin:0;padding:0}

/* ── Hero ── */
.cos-shop-hero{position:relative;height:100svh;min-height:600px;display:flex;align-items:flex-end;justify-content:flex-start;overflow:hidden}
.cos-shop-hero-bg{position:absolute;inset:0}
.cos-shop-hero-img{position:absolute;inset:0;width:100%;height:100%;object-fit:cover;object-position:center 30%}
.cos-shop-hero-overlay{position:absolute;inset:0;background:linear-gradient(to top,rgba(13,19,32,.96) 0%,rgba(13,19,32,.74) 45%,rgba(13,19,32,.32) 100%)}
.cos-shop-hero-inner{position:relative;z-index:2;padding:0 48px calc(var(--cos-promo-h,0px) + var(--cos-nav-h,80px) + 72px);max-width:820px}
.cos-shop-hero-eyebrow{font-size:10px;font-weight:600;letter-spacing:.3em;text-transform:uppercase;color:rgba(255,255,255,.8);margin-bottom:18px;display:flex;align-items:center;gap:12px}
@media(max-width:767px){.cos-shop-hero-eyebrow{letter-spacing:.18em}}
.cos-shop-hero-eyebrow::before{content:'';display:inline-block;width:24px;height:1px;background:#B8965F;flex-shrink:0}
.cos-shop-hero-inner h1{font-family:var(--cos-display);font-style:italic;font-weight:300;font-size:clamp(44px,6.5vw,84px);color:#fff;line-height:1.06;margin:0 0 10px}
.cos-shop-hero-subtitle{font-family:var(--cos-display);font-style:italic;font-weight:300;font-size:15px;color:rgba(255,255,255,.75);margin:0 0 18px;letter-spacing:.02em}
.cos-shop-hero-sub{font-size:14px;font-weight:300;color:rgba(248,247,244,.65);max-width:520px;margin:0 0 40px;line-height:1.85}
.cos-shop-hero-ctas{display:flex;flex-wrap:wrap;gap:10px}
.cos-shop-hero-cta{display:inline-block;min-height:44px;box-sizing:border-box;padding:14px 26px;background:var(--cos-pearl,#F7F3ED);border:1px solid var(--cos-pearl,#F7F3ED);color:var(--cos-navy,#0F1B2D)!important;font-size:10px;font-weight:600;letter-spacing:.2em;text-transform:uppercase;text-decoration:none;transition:background .2s,border-color .2s}
.cos-shop-hero-cta:hover{background:#fff;border-color:#fff}
/* ── Ritual flow (hero) ── */
.cos-shop-ritual-flow{display:flex;align-items:center;justify-content:flex-start;flex-wrap:wrap;margin-top:44px;padding-top:36px;border-top:1px solid rgba(255,255,255,.12);opacity:0;animation:cos-shop-fade-up .9s 1s forwards}
.cos-srf-step{display:flex;flex-direction:column;align-items:center;gap:6px;padding:0 20px 0 0}
.cos-srf-num{font-family:var(--cos-display);font-style:italic;font-weight:300;font-size:28px;color:rgba(255,255,255,.3);line-height:1}
.cos-srf-lbl{font-size:8px;font-weight:600;letter-spacing:.22em;text-transform:uppercase;color:rgba(255,255,255,.25)}
.cos-srf-sep{width:32px;height:1px;background:rgba(255,255,255,.1);margin:0 0 24px;flex-shrink:0}
.cos-shop-scroll{position:absolute;bottom:36px;left:50%;transform:translateX(-50%);display:flex;flex-direction:column;align-items:center;gap:8px;z-index:2;opacity:0;animation:cos-shop-fade-up .8s 1.2s forwards}
.cos-shop-scroll-line{width:1px;height:44px;background:rgba(255,255,255,.38);animation:cos-shop-scroll-pulse 2.2s 1.6s ease-in-out infinite}
.cos-shop-scroll-text{font-size:8px;font-weight:500;letter-spacing:.28em;text-transform:uppercase;color:rgba(255,255,255,.45)}
@keyframes cos-shop-fade-up{from{opacity:0;transform:translateY(22px)}to{opacity:1;transform:translateY(0)}}
@keyframes cos-shop-scroll-pulse{0%,100%{transform:scaleY(1);opacity:.38}55%{transform:scaleY(1.35);opacity:.85}}
@media(max-width:479px){.cos-shop-hero-inner{padding:0 24px calc(var(--cos-nav-h,80px) + 60px)}.cos-srf-sep{width:16px}.cos-srf-step{padding:0 12px 0 0}}
@media(min-width:768px){.cos-shop-hero-inner{padding:0 88px calc(var(--cos-nav-h,80px) + 88px)}}

/* ── Mobile hero: shortened from 100svh to a fixed, tighter height (UI/UX
   pass 2026-09-19) so the first purchasable section starts much sooner on
   mobile, without touching the desktop hero at all. Overlay opacity is
   reduced only slightly and only at the top of the gradient (where the
   product photo actually shows) -- the bottom stop, where the heading/body
   text sits, stays close to its original near-opaque value so body-text
   contrast stays well above 4.5:1. */
@media(max-width:767px){
  .cos-shop-hero{height:540px;min-height:540px}
  .cos-shop-hero-overlay{background:linear-gradient(to top,rgba(13,19,32,.92) 0%,rgba(13,19,32,.68) 45%,rgba(13,19,32,.26) 100%)}
  .cos-shop-hero-inner{padding-bottom:26px}
  .cos-shop-hero-eyebrow{margin-bottom:12px}
  .cos-shop-hero-inner h1{font-size:clamp(32px,9vw,42px);margin-bottom:8px}
  .cos-shop-hero-subtitle{margin-bottom:10px}
  .cos-shop-hero-sub{margin-bottom:20px;line-height:1.6}
  .cos-shop-ritual-flow{margin-top:18px;padding-top:14px}
  .cos-shop-scroll{display:none}
}

/* ── Trust strip ── */
.cos-shop-trust{background:var(--cos-pearl,#F7F3EE);border-top:1px solid var(--cos-stone,#E5DED4);border-bottom:1px solid var(--cos-stone,#E5DED4);padding:36px 24px}
.cos-shop-trust-items{max-width:1100px;margin:0 auto;display:flex;flex-wrap:wrap;gap:24px 48px;justify-content:center;list-style:none;padding:0}
.cos-shop-trust-item{font-size:11px;font-weight:600;letter-spacing:.14em;text-transform:uppercase;color:var(--cos-ink,#111111);display:flex;align-items:center;gap:10px}
.cos-shop-trust-ico{width:16px;height:16px;flex-shrink:0;color:var(--cos-gold-l,#B79A5B)}

/* ── Complete Ritual (featured bundle) ── */
.cos-bundle{padding:80px 24px 8px;background:var(--cos-pearl,#F7F3EE)}
.cos-bundle-inner{max-width:1200px;margin:0 auto;background:var(--cos-navy,#0F1B2D);display:grid;grid-template-columns:1fr;overflow:hidden}
.cos-bundle-img-wrap{position:relative;aspect-ratio:4/3;background:var(--cos-stone,#E5DED4)}
.cos-bundle-img{position:absolute;inset:0;width:100%;height:100%;object-fit:cover;object-position:center bottom;display:block}
.cos-bundle-body{padding:44px 32px 48px;display:flex;flex-direction:column;color:#fff}
.cos-bundle-eyebrow{font-size:10px;font-weight:600;letter-spacing:.28em;text-transform:uppercase;color:var(--cos-gold-l,#B8965F);margin-bottom:16px;display:flex;align-items:center;gap:12px}
@media(max-width:767px){.cos-bundle-eyebrow{letter-spacing:.18em}}
.cos-bundle-eyebrow::before{content:'';display:inline-block;width:24px;height:1px;background:#B8965F;flex-shrink:0}
.cos-bundle-title{font-family:var(--cos-display);font-style:italic;font-weight:300;font-size:clamp(26px,3.2vw,40px);line-height:1.15;margin:0 0 16px}
.cos-bundle-desc{font-size:13px;font-weight:300;color:rgba(247,243,237,.72);line-height:1.8;margin:0 0 24px}
.cos-bundle-steps{display:flex;flex-wrap:wrap;gap:8px 20px;margin:0 0 28px;list-style:none;padding:0}
.cos-bundle-steps li{font-size:10px;font-weight:500;letter-spacing:.06em;color:rgba(247,243,237,.85);display:flex;align-items:center;gap:8px}
.cos-bundle-steps li::before{content:'';width:4px;height:4px;border-radius:50%;background:var(--cos-gold-l,#B8965F);flex-shrink:0}
.cos-bundle-price-row{display:flex;align-items:baseline;gap:12px;margin-bottom:24px}
.cos-bundle-price{font-size:28px;font-weight:300;color:#fff;font-variant-numeric:lining-nums tabular-nums}
.cos-bundle-price-was{font-size:15px;font-weight:300;color:rgba(247,243,237,.5);text-decoration:line-through}
.cos-bundle-save{font-size:10px;font-weight:600;letter-spacing:.04em;color:var(--cos-gold-l,#B8965F);padding:4px 9px;border:1px solid var(--cos-gold-l,#B8965F)}
.cos-bundle-ctas{display:flex;flex-wrap:wrap;gap:10px;margin-top:auto}
.cos-bundle-btn-primary{display:inline-flex;align-items:center;justify-content:center;min-height:44px;box-sizing:border-box;padding:15px 28px;background:var(--cos-pearl,#F7F3ED);color:var(--cos-navy,#0F1B2D)!important;font-size:10px;font-weight:600;letter-spacing:.18em;text-transform:uppercase;text-decoration:none;border:1px solid var(--cos-pearl,#F7F3ED);transition:background .2s}
.cos-bundle-btn-primary:hover{background:#fff;border-color:#fff}
.cos-bundle-btn-ghost{display:inline-block;padding:15px 28px;background:transparent;color:#fff!important;font-size:10px;font-weight:600;letter-spacing:.18em;text-transform:uppercase;text-decoration:none;border:1px solid rgba(255,255,255,.4);transition:all .2s}
.cos-bundle-btn-ghost:hover{border-color:#fff;background:rgba(255,255,255,.07)}
@media(min-width:900px){.cos-bundle-inner{grid-template-columns:1fr 1fr}.cos-bundle-img-wrap{aspect-ratio:unset}.cos-bundle-body{padding:56px 56px 56px 48px;justify-content:center}}

/* ── Products grid ── */
.cos-products{padding:88px 24px;background:var(--cos-pearl,#F7F3EE)}
.cos-products-header{text-align:center;margin-bottom:56px}
.cos-products-eyebrow{font-size:10px;font-weight:600;letter-spacing:.28em;text-transform:uppercase;color:var(--cos-gold,#8B692F);margin-bottom:12px}
@media(max-width:767px){.cos-products-eyebrow{letter-spacing:.18em}}
.cos-products-title{font-family:var(--cos-display);font-style:italic;font-weight:300;font-size:clamp(26px,3vw,40px);color:var(--cos-ink,#111111);line-height:1.2;margin:0}
.cos-grid{display:grid;grid-template-columns:1fr;gap:2px;max-width:1200px;margin:0 auto}

/* ── About the collection (category copy) ── */
.cos-shop-about{padding:0 24px 88px;background:var(--cos-pearl,#F7F3EE)}
.cos-shop-about-inner{max-width:720px;margin:0 auto}
.cos-shop-about-eyebrow{font-size:10px;font-weight:600;letter-spacing:.28em;text-transform:uppercase;color:var(--cos-gold,#8B692F);margin-bottom:12px;text-align:center}
@media(max-width:767px){.cos-shop-about-eyebrow{letter-spacing:.18em}}
.cos-shop-about-title{font-family:var(--cos-display);font-style:italic;font-weight:300;font-size:clamp(22px,2.5vw,32px);color:var(--cos-ink,#111111);line-height:1.25;margin:0 0 28px;text-align:center}
.cos-shop-about p{font-size:14px;font-weight:300;color:var(--cos-slate,#5C6675);line-height:1.85;margin:0 0 18px}
.cos-shop-about p:last-child{margin-bottom:0}
.cos-shop-about-intro{margin-bottom:8px}
/* Condensed 2026-09-19: three long paragraphs replaced with one short intro
   plus three native <details> disclosures -- no JS accordion, works
   identically at every width. Collapsed by default everywhere; 44px
   summary targets throughout, not just on mobile. */
.cos-shop-about-details{border-top:1px solid var(--cos-stone,#E5DED4)}
.cos-shop-about-details:last-of-type{border-bottom:1px solid var(--cos-stone,#E5DED4)}
.cos-shop-about-summary{display:flex;align-items:center;justify-content:space-between;gap:16px;min-height:44px;padding:14px 0;cursor:pointer;list-style:none;font-family:var(--cos-body);font-size:13px;font-weight:600;color:var(--cos-ink,#111111);-webkit-tap-highlight-color:transparent}
.cos-shop-about-summary::-webkit-details-marker{display:none}
.cos-shop-about-icon{font-size:18px;font-weight:300;color:var(--cos-slate,#5C6675);transition:transform .25s;flex-shrink:0;line-height:1}
.cos-shop-about-details[open] .cos-shop-about-icon{transform:rotate(45deg)}
.cos-shop-about-body{padding:0 0 20px}
.cos-shop-about-body p{margin:0 0 14px}
.cos-shop-about-body p:last-child{margin-bottom:0}
.cos-shop-about-body a{color:inherit;text-decoration:underline;text-underline-offset:2px}
@media(min-width:768px){.cos-shop-about{padding:0 48px 96px}}

/* ── Product card ── */
.cos-card{background:#fff;display:flex;flex-direction:column;text-decoration:none;color:inherit;transition:box-shadow .3s,transform .3s;border-top:2px solid var(--cos-stone,#E5DED4);position:relative;overflow:hidden}
.cos-card:hover{box-shadow:0 16px 48px rgba(18,27,44,.13);transform:translateY(-3px)}
.cos-card-img-wrap{display:block;position:relative;width:100%;padding-bottom:100%;overflow:hidden;background:var(--cos-stone,#E5DED4)}
.cos-card-img{position:absolute!important;top:0!important;left:0!important;width:100%!important;height:100%!important;object-fit:cover!important;display:block;transition:transform .55s cubic-bezier(.25,.46,.45,.94)}
.cos-card:hover .cos-card-img{transform:scale(1.06)}
.cos-card-overlay{position:absolute;top:0;left:0;right:0;bottom:0;background:rgba(18,27,44,0);display:flex;align-items:center;justify-content:center;transition:background .3s;pointer-events:none}
.cos-card:hover .cos-card-overlay{background:rgba(18,27,44,.52)}
.cos-card-overlay-label{font-size:9px;font-weight:600;letter-spacing:.22em;text-transform:uppercase;color:#fff;opacity:0;transition:opacity .3s;white-space:nowrap;padding:10px 24px;border:1px solid rgba(255,255,255,.5)}
.cos-card:hover .cos-card-overlay-label{opacity:1}
.cos-card-body{padding:28px 24px 32px;display:flex;flex-direction:column;flex:1}
.cos-card-step{font-size:10px;font-weight:600;letter-spacing:.25em;text-transform:uppercase;color:var(--cos-gold,#8B692F);margin-bottom:8px}
@media(max-width:767px){.cos-card-step{letter-spacing:.18em}}
.cos-card-name{font-family:var(--cos-display);font-style:italic;font-weight:300;font-size:clamp(17px,1.8vw,22px);color:var(--cos-ink,#111111);line-height:1.2;margin:0 0 5px}
.cos-card-name a{color:inherit;text-decoration:none}
.cos-card-role{font-size:10px;font-weight:500;letter-spacing:.1em;text-transform:uppercase;color:var(--cos-slate,#5C6675);margin-bottom:14px}
.cos-card-desc{font-size:14px;font-weight:300;color:var(--cos-slate,#5C6675);line-height:1.75;flex:1;padding-bottom:20px}
.cos-card-footer{display:flex;align-items:center;justify-content:space-between;padding-top:20px;border-top:1px solid var(--cos-stone,#E5DED4)}
.cos-card-price{font-size:20px;font-weight:300;color:var(--cos-ink,#111111);font-variant-numeric:lining-nums tabular-nums}
.cos-card-size{font-size:10px;font-weight:400;color:var(--cos-slate,#5C6675);margin-left:5px}
.cos-card-btn{display:inline-flex;align-items:center;justify-content:center;min-height:44px;box-sizing:border-box;padding:11px 20px;background:var(--cos-ink,#111111);color:#fff!important;font-size:10px;font-weight:600;letter-spacing:.15em;text-transform:uppercase;text-decoration:none;transition:background .2s;white-space:nowrap;border:none;cursor:pointer;font-family:var(--cos-body)}
.cos-card-btn:hover{background:var(--cos-gold,#8B692F)}
.cos-card-soldout{display:inline-block;padding:11px 20px;font-size:10px;font-weight:600;letter-spacing:.15em;text-transform:uppercase;color:var(--cos-slate,#5C6675);border:1px solid var(--cos-stone,#E5DED4);font-family:var(--cos-body)}

/* ── Responsive ── */
@media(min-width:600px){.cos-grid{grid-template-columns:repeat(2,1fr)}}
@media(min-width:1024px){.cos-grid{grid-template-columns:repeat(4,1fr)}}
</style>

<main id="cos-main-content">
  <div id="cos-shop">

    <!-- Hero -->
    <?php
    // attachment_url_to_postid() can't find this one: WordPress's "big
    // image" auto-scaling renamed the registered file to *-scaled.jpg,
    // so the original un-scaled filename this markup used to hardlink no
    // longer matches _wp_attached_file. Look it up by guid (the original
    // upload URL, preserved even after scaling) instead.
    global $wpdb;
    $cos_shop_hero_url = '/wp-content/uploads/2026/04/DSC_6724.jpg';
    $cos_shop_hero_id  = (int) $wpdb->get_var( $wpdb->prepare(
        "SELECT ID FROM {$wpdb->posts} WHERE post_type='attachment' AND guid=%s",
        home_url( $cos_shop_hero_url )
    ) );
    $cos_shop_hero_src    = $cos_shop_hero_id ? wp_get_attachment_image_url( $cos_shop_hero_id, 'full' ) : $cos_shop_hero_url;
    $cos_shop_hero_srcset = $cos_shop_hero_id ? wp_get_attachment_image_srcset( $cos_shop_hero_id, 'full' ) : false;
    $cos_shop_hero_sizes  = $cos_shop_hero_id ? wp_get_attachment_image_sizes( $cos_shop_hero_id, 'full' ) : false;
    $cos_shop_hero_sizes  = $cos_shop_hero_sizes ?: '100vw';
    $cos_shop_hero_img_html = '<img class="cos-shop-hero-img" src="' . esc_url( $cos_shop_hero_src ) . '"' . ( $cos_shop_hero_srcset ? ' srcset="' . esc_attr( $cos_shop_hero_srcset ) . '" sizes="' . esc_attr( $cos_shop_hero_sizes ) . '"' : '' ) . ' alt="Caviar of Switzerland — The Collection" width="1600" height="2400" fetchpriority="high"/>';
    ?>
    <section class="cos-shop-hero">
      <div class="cos-shop-hero-bg">
        <?php echo cos_child_nextgen_picture( $cos_shop_hero_src, (string) $cos_shop_hero_srcset, $cos_shop_hero_sizes, $cos_shop_hero_img_html ); ?>
        <div class="cos-shop-hero-overlay"></div>
      </div>
      <div class="cos-shop-hero-inner">
        <p class="cos-shop-hero-eyebrow">Swiss Caviar Skincare</p>
        <h1>Luxury Caviar Skincare</h1>
        <p class="cos-shop-hero-subtitle">Shop the Ritual</p>
        <p class="cos-shop-hero-sub">Complete luxury Swiss skincare in four essential anti-aging steps, formulated with precious Caviar Extract, with independently tested results available for selected formulas.</p>
        <div class="cos-shop-hero-ctas">
          <a href="/products/the-complete-ritual/" class="cos-shop-hero-cta" data-cro-cta="shop-hero-primary">View Complete Ritual — Save &euro;50</a>
        </div>

        <!-- Ritual step flow -->
        <div class="cos-shop-ritual-flow" aria-hidden="true">
          <div class="cos-srf-step"><span class="cos-srf-num">01</span><span class="cos-srf-lbl">Cleanse</span></div>
          <div class="cos-srf-sep"></div>
          <div class="cos-srf-step"><span class="cos-srf-num">02</span><span class="cos-srf-lbl">Treat</span></div>
          <div class="cos-srf-sep"></div>
          <div class="cos-srf-step"><span class="cos-srf-num">03</span><span class="cos-srf-lbl">Eye</span></div>
          <div class="cos-srf-sep"></div>
          <div class="cos-srf-step"><span class="cos-srf-num">04</span><span class="cos-srf-lbl">Moisturise</span></div>
        </div>
      </div>
      <div class="cos-shop-scroll">
        <div class="cos-shop-scroll-line"></div>
        <span class="cos-shop-scroll-text">Scroll</span>
      </div>
    </section>

    <!-- Trust strip -->
    <section class="cos-shop-trust">
      <ul class="cos-shop-trust-items" role="list">
        <li class="cos-shop-trust-item">
          <svg class="cos-shop-trust-ico" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="10" cy="10" r="8"/><path d="M2 10h16"/><path d="M10 2a12 12 0 0 1 0 16M10 2a12 12 0 0 0 0 16"/></svg>
          <?php echo esc_html( cos_child_shipping_message( 'badge' ) ); ?>
        </li>
        <li class="cos-shop-trust-item">
          <svg class="cos-shop-trust-ico" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M15.5 9A5.5 5.5 0 1 1 10 4"/><path d="M10 4V7.5l2.5 1.5"/><path d="M15 4h2v2"/></svg>
          30-Day Returns
        </li>
        <li class="cos-shop-trust-item">
          <svg class="cos-shop-trust-ico" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="9" width="14" height="9" rx="1"/><path d="M7 9V6a3 3 0 1 1 6 0v3"/></svg>
          Secure Checkout
        </li>
        <li class="cos-shop-trust-item">
          <svg class="cos-shop-trust-ico" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M10 2.5L3 5.7v5.1c0 3.8 2.9 6.8 7 7.4 4.1-.6 7-3.6 7-7.4V5.7L10 2.5z"/><path d="M7 10l2 2 4-4"/></svg>
          Authentic Swiss Formulas
        </li>
      </ul>
    </section>

    <!-- Complete Ritual (featured bundle) -->
    <?php if ( $bundle ) :
      // A dedicated shop-teaser image (all 4 products + box) if one is set,
      // otherwise fall back to the product's normal featured image.
      $bundle_img_id   = (int) get_post_meta( $bundle->get_id(), '_cos_shop_teaser_image_id', true ) ?: $bundle->get_image_id();
      $bundle_img_data = $bundle_img_id ? wp_get_attachment_image_src( $bundle_img_id, 'large' ) : false;
      $bundle_img_src  = $bundle_img_data ? $bundle_img_data[0] : '';
      $bundle_img_w    = $bundle_img_data ? $bundle_img_data[1] : 0;
      $bundle_img_h    = $bundle_img_data ? $bundle_img_data[2] : 0;
      $bundle_img_srcset = $bundle_img_id ? wp_get_attachment_image_srcset( $bundle_img_id, 'large' ) : '';
      $bundle_url     = get_permalink( $bundle->get_id() );
      $bundle_atc_url = esc_url( add_query_arg( 'add-to-cart', $bundle->get_id(), wc_get_cart_url() ) );
      $bundle_regular = (float) $bundle->get_regular_price();
      $bundle_sale    = (float) $bundle->get_sale_price();
    ?>
    <section class="cos-bundle" id="cos-ritual">
      <div class="cos-bundle-inner">
        <div class="cos-bundle-img-wrap">
          <?php if ( $bundle_img_src ) :
            $bundle_img_sizes = $bundle_img_srcset ? '(min-width: 768px) 50vw, 100vw' : '';
            $bundle_img_html  = '<img class="cos-bundle-img" src="' . esc_url( $bundle_img_src ) . '" width="' . esc_attr( $bundle_img_w ) . '" height="' . esc_attr( $bundle_img_h ) . '" ' . ( $bundle_img_srcset ? 'srcset="' . esc_attr( $bundle_img_srcset ) . '" sizes="' . esc_attr( $bundle_img_sizes ) . '" ' : '' ) . 'alt="' . esc_attr( $bundle->get_name() ) . '" loading="lazy"/>';
            echo cos_child_nextgen_picture( $bundle_img_src, (string) $bundle_img_srcset, $bundle_img_sizes, $bundle_img_html );
          endif; ?>
        </div>
        <div class="cos-bundle-body">
          <p class="cos-bundle-eyebrow">The Complete Ritual</p>
          <h2 class="cos-bundle-title">All four steps. One Swiss ritual.</h2>
          <p class="cos-bundle-desc">Every product in the Caviar of Switzerland protocol, in ritual order — cleanse, treat, eye, and moisturise. The complete daily system, together.</p>
          <ul class="cos-bundle-steps">
            <?php foreach ( $individual as $step_product ) : ?>
            <li><?php echo esc_html( $step_product->get_name() ); ?></li>
            <?php endforeach; ?>
          </ul>
          <div class="cos-bundle-price-row">
            <span class="cos-bundle-price"><?php echo wc_price( $bundle_sale ?: $bundle->get_price() ); ?></span>
            <?php if ( $bundle_sale && $bundle_regular > $bundle_sale ) : ?>
            <span class="cos-bundle-price-was"><?php echo wc_price( $bundle_regular ); ?></span>
            <span class="cos-bundle-save">Save <?php echo wc_price( $bundle_regular - $bundle_sale ); ?></span>
            <?php endif; ?>
          </div>
          <div class="cos-bundle-ctas">
            <?php if ( $bundle->is_in_stock() ) : ?>
            <a href="<?php echo $bundle_atc_url; ?>" class="cos-bundle-btn-primary add_to_cart_button ajax_add_to_cart" data-product_id="<?php echo esc_attr( $bundle->get_id() ); ?>" data-quantity="1" rel="nofollow">Add to Bag</a>
            <?php endif; ?>
            <a href="<?php echo esc_url( $bundle_url ); ?>" class="cos-bundle-btn-ghost">View the Ritual</a>
          </div>
        </div>
      </div>
    </section>
    <?php endif; ?>

    <!-- Individual Products -->
    <section class="cos-products" id="cos-products">
      <div class="cos-products-header">
        <p class="cos-products-eyebrow">Individual Products</p>
        <h2 class="cos-products-title">Each step, available separately.</h2>
      </div>
      <div class="cos-grid">
        <?php foreach ( $individual as $product ) :
          $sku        = $product->get_sku();
          $gallery_raw = get_post_meta( $product->get_id(), '_product_image_gallery', true );
          $gallery_arr = array_filter( array_map( 'intval', explode( ',', $gallery_raw ) ) );
          $img_id      = ! empty( $gallery_arr ) ? reset( $gallery_arr ) : (int) get_post_meta( $product->get_id(), '_thumbnail_id', true );
          $img_data    = $img_id ? wp_get_attachment_image_src( $img_id, 'large' ) : false;
          $img_src     = $img_data ? $img_data[0] : '';
          $img_w       = $img_data ? $img_data[1] : 0;
          $img_h       = $img_data ? $img_data[2] : 0;
          $img_srcset  = $img_id ? wp_get_attachment_image_srcset( $img_id, 'large' ) : '';
          $url        = get_permalink( $product->get_id() );
          $atc_url    = esc_url( add_query_arg( 'add-to-cart', $product->get_id(), wc_get_cart_url() ) );
          $step_info  = $step_roles[ $sku ] ?? array( 'num' => '—', 'role' => '' );
          $attrs      = $product->get_attributes();
          $size       = '';
          foreach ( $attrs as $attr ) {
            if ( strtolower( $attr->get_name() ) === 'size' ) {
              $opts = $attr->get_options();
              $size = $opts[0] ?? '';
              break;
            }
          }
        ?>
        <div class="cos-card">
          <a href="<?php echo esc_url( $url ); ?>" class="cos-card-img-wrap cos-select-item" data-product_id="<?php echo esc_attr( $product->get_id() ); ?>" aria-label="<?php echo esc_attr( sprintf( 'View %s', $product->get_name() ) ); ?>">
            <?php if ( $img_src ) :
              $card_img_sizes = $img_srcset ? '(min-width: 1024px) 25vw, (min-width: 768px) 33vw, 50vw' : '';
              $card_img_html  = '<img class="cos-card-img" src="' . esc_url( $img_src ) . '" width="' . esc_attr( $img_w ) . '" height="' . esc_attr( $img_h ) . '" ' . ( $img_srcset ? 'srcset="' . esc_attr( $img_srcset ) . '" sizes="' . esc_attr( $card_img_sizes ) . '" ' : '' ) . 'alt="" loading="lazy"/>';
              echo cos_child_nextgen_picture( $img_src, $img_srcset, $card_img_sizes, $card_img_html );
            endif; ?>
            <div class="cos-card-overlay" aria-hidden="true">
              <span class="cos-card-overlay-label">View Product</span>
            </div>
          </a>
          <div class="cos-card-body">
            <p class="cos-card-step">Step <?php echo esc_html( $step_info['num'] ); ?></p>
            <h3 class="cos-card-name"><a href="<?php echo esc_url( $url ); ?>" class="cos-select-item" data-product_id="<?php echo esc_attr( $product->get_id() ); ?>"><?php echo esc_html( $product->get_name() ); ?></a></h3>
            <p class="cos-card-role"><?php echo esc_html( $step_info['role'] ); ?></p>
            <p class="cos-card-desc"><?php echo esc_html( $card_copy[ $sku ] ?? wp_trim_words( wp_strip_all_tags( $product->get_short_description() ), 18, '…' ) ); ?></p>
            <div class="cos-card-footer">
              <div>
                <span class="cos-card-price"><?php echo wc_price( $product->get_price() ); ?></span>
                <?php if ( $size ) : ?><span class="cos-card-size"><?php echo esc_html( $size ); ?></span><?php endif; ?>
              </div>
              <?php if ( $product->is_in_stock() ) : ?>
              <a href="<?php echo $atc_url; ?>" class="cos-card-btn add_to_cart_button ajax_add_to_cart" data-product_id="<?php echo esc_attr( $product->get_id() ); ?>" data-quantity="1" rel="nofollow">Add to Bag</a>
              <?php else : ?>
              <span class="cos-card-soldout">Sold Out</span>
              <?php endif; ?>
            </div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </section>

    <!-- About the collection (category copy) -->
    <section class="cos-shop-about" aria-labelledby="cos-shop-about-h2">
      <div class="cos-shop-about-inner">
        <p class="cos-shop-about-eyebrow">About the Collection</p>
        <h2 class="cos-shop-about-title" id="cos-shop-about-h2">Luxury caviar skincare, built as one system.</h2>
        <p class="cos-shop-about-intro">This collection is built for the visible signs of aging — fine lines, dullness, dryness, and loss of firmness — as a considered daily ritual rather than a shelf of disconnected products. Every formula draws from the same High-Tech Complex, in concentrations suited to each step's role, with results independently tested and published in full.</p>

        <details class="cos-shop-about-details">
          <summary class="cos-shop-about-summary">How the ritual works<span class="cos-shop-about-icon" aria-hidden="true">+</span></summary>
          <div class="cos-shop-about-body">
            <p>Micellar Water Cleanser removes make-up and daily impurities without stripping the skin, preparing it for the steps that follow. Advanced Repair Serum concentrates the firming actives. Revitalizing Eye Cream treats the eye contour specifically. 24h Regeneration Cream seals the routine, morning and night.</p>
            <p>Every formula can be used on its own, alongside an existing routine, or as <a href="/products/the-complete-ritual/">The Complete Ritual</a> — see the full four-step sequence in <a href="/the-ritual/">the ritual guide</a>.</p>
          </div>
        </details>

        <details class="cos-shop-about-details">
          <summary class="cos-shop-about-summary">Ingredients and clinical evidence<span class="cos-shop-about-icon" aria-hidden="true">+</span></summary>
          <div class="cos-shop-about-body">
            <p>Every formula draws from the same High-Tech Complex — Caviar Extract, SYN&reg;-COLL Peptide, Hyaluronic Acid, Superoxide Dismutase, and Resveratrol — in the concentrations suited to each step's role. Caviar Extract itself is sourced from Oscietra roe and formulated to replenish the skin's own lipids and trace minerals, rather than included as a luxury signal.</p>
            <p>The range addresses five concerns: <a href="/skin-concerns/fine-lines-wrinkles/">fine lines and wrinkles</a>, <a href="/skin-concerns/loss-of-firmness/">loss of firmness</a>, <a href="/skin-concerns/dark-circles/">dark circles</a>, <a href="/skin-concerns/dry-dehydrated-skin/">dryness</a>, and <a href="/skin-concerns/dull-skin/">dullness</a>. Advanced Repair Serum showed +47% perceived firmness in an independent 28-day study; Revitalizing Eye Cream showed a &minus;38% reduction in fine-line depth over the same period; 24h Regeneration Cream reported 100% panellist satisfaction. Each result comes from that product's own single-product study, never presented as a claim for the ritual as a whole — full methodology, panel size, and duration are published on the <a href="/science/clinical-studies/">Clinical Studies</a> page.</p>
          </div>
        </details>

        <details class="cos-shop-about-details">
          <summary class="cos-shop-about-summary">Which product should I start with?<span class="cos-shop-about-icon" aria-hidden="true">+</span></summary>
          <div class="cos-shop-about-body">
            <p>It depends on the concern that matters most: the cleanser for anyone simplifying an existing routine, the serum for firmness and fine lines, the eye cream for the eye contour, and the 24h cream for overnight regeneration and lasting comfort.</p>
            <p>Every formula can be used on its own, morning and evening, alongside an existing routine — or start with all four as <a href="/products/the-complete-ritual/">The Complete Ritual</a>, at a &euro;50 saving over buying them separately.</p>
          </div>
        </details>
      </div>
    </section>

  </div>
</main>

<?php
// view_item_list / select_item — sourced from the same $individual array
// (real wc_get_product() objects) the grid above was rendered from, not a
// re-derived or hardcoded list.
$cos_list_items = array();
foreach ( $individual as $i => $listed_product ) {
	$cos_list_items[] = array(
		'item_id'       => $listed_product->get_sku() ?: (string) $listed_product->get_id(),
		'product_id'    => $listed_product->get_id(),
		'item_name'     => $listed_product->get_name(),
		'price'         => (float) wc_get_price_to_display( $listed_product ),
		'index'         => $i,
		'item_list_id'  => 'shop',
		'item_list_name'=> 'Shop',
	);
}
?>
<script nonce="<?php echo esc_attr( cos_csp_nonce() ); ?>">
document.addEventListener('DOMContentLoaded', function () {
  var items = <?php echo wp_json_encode( $cos_list_items ); ?>;
  if (window.cos && cos.track && items.length) {
    cos.track('view_item_list', { item_list_id: 'shop', item_list_name: 'Shop', items: items });
  }
  document.querySelectorAll('.cos-select-item').forEach(function (el) {
    el.addEventListener('click', function () {
      var pid = parseInt(el.getAttribute('data-product_id'), 10);
      var match = items.find(function (i) { return i.product_id === pid; });
      if (match && window.cos && cos.track) {
        cos.track('select_item', { item_list_id: 'shop', item_list_name: 'Shop', items: [match] });
      }
    });
  });
});
</script>

<?php wp_footer(); ?>
</body>
</html>
