<?php
/**
 * COS — WooCommerce Single Product
 */
defined( 'ABSPATH' ) || exit;
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<?php cos_child_custom_document_head( single_post_title( '', false ) ?: 'Product - Caviar of Switzerland' ); ?>
<?php wp_head(); ?>
</head>
<body <?php body_class( 'woocommerce woocommerce-page' ); ?>>
<?php wp_body_open(); ?>
<?php

while ( have_posts() ) :
	the_post();
	global $product;
	if ( ! $product ) $product = wc_get_product( get_the_ID() );
	if ( ! $product ) continue;

	$sku      = $product->get_sku();
	$img_id   = $product->get_image_id();
	$img_data = $img_id ? wp_get_attachment_image_src( $img_id, 'large' ) : false;
	$img_src  = $img_data ? $img_data[0] : '';
	$img_w    = $img_data ? $img_data[1] : 0;
	$img_h    = $img_data ? $img_data[2] : 0;
	$price   = $product->get_price();
	$sale    = $product->get_sale_price();
	$regular = $product->get_regular_price();
	$name    = $product->get_name();
	$desc    = $product->get_description();

	$step_map = array(
		'COS-001'    => array( 'num' => '01', 'total' => '04', 'label' => 'Step 01 of 04', 'role' => 'Cleanse & Prepare',   'color' => '#46656B' ),
		'COS-002'    => array( 'num' => '02', 'total' => '04', 'label' => 'Step 02 of 04', 'role' => 'Concentrate & Treat', 'color' => '#46656B' ),
		'COS-003'    => array( 'num' => '03', 'total' => '04', 'label' => 'Step 03 of 04', 'role' => 'Restore & Refine',    'color' => '#46656B' ),
		'COS-004'    => array( 'num' => '04', 'total' => '04', 'label' => 'Step 04 of 04', 'role' => 'Seal & Sustain',      'color' => '#46656B' ),
		'COS-BUNDLE' => array( 'num' => '',   'total' => '',   'label' => 'The Complete Set', 'role' => 'All Four Steps',   'color' => '#8B692F' ),
	);
	$step = $step_map[ $sku ] ?? array( 'num' => '', 'total' => '', 'label' => '', 'role' => '', 'color' => '#8B692F' );

	$attrs = $product->get_attributes();
	$size  = '';
	foreach ( $attrs as $attr ) {
		if ( strtolower( $attr->get_name() ) === 'size' ) {
			$opts = $attr->get_options();
			$size = $opts[0] ?? '';
			break;
		}
	}

	$gallery_ids = $product->get_gallery_image_ids();

	// Product-specific page data — single source of truth in inc/product-data.php.
	$pd = cos_get_product_copy( $sku );

	// Above-fold trim: only the first 3 benefits render near the CTA; the
	// rest move into the full description section further down the page.
	$cos_pdp_benefits_all  = $pd['benefits'] ?? array();
	$cos_pdp_benefits_lead = array_slice( $cos_pdp_benefits_all, 0, 3 );
	$cos_pdp_benefits_more = array_slice( $cos_pdp_benefits_all, 3 );

	// Real-time payment-method summary — reflects whichever gateways are
	// actually enabled today rather than an unverifiable generic claim.
	$cos_pdp_gateway_ids = function_exists( 'cos_child_enabled_payment_gateways' )
		? array_keys( cos_child_enabled_payment_gateways() )
		: array();
	if ( array( 'cod' ) === $cos_pdp_gateway_ids ) {
		$cos_pdp_payment_label = 'Pay on Delivery';
	} elseif ( in_array( 'cod', $cos_pdp_gateway_ids, true ) && count( $cos_pdp_gateway_ids ) > 1 ) {
		$cos_pdp_payment_label = 'Secure Payment or Pay on Delivery';
	} elseif ( $cos_pdp_gateway_ids ) {
		$cos_pdp_payment_label = 'Secure Online Payment';
	} else {
		$cos_pdp_payment_label = 'Contact Us to Order';
	}

	// Clinical methodology link near the CTA only where real per-product
	// stats exist (Cleanser has none) or on the bundle, which has its own
	// combined-results section further down — never implied where absent.
	$cos_pdp_has_clinical_proof = ( 'COS-BUNDLE' === $sku ) || ! empty( $pd['stats'] );

	// In-page contents menu — every entry here mirrors the exact condition
	// its target <section> renders under further down this same file, so the
	// nav can never link to a section that doesn't actually exist for this
	// product. Computed once, up front, purely so it can be printed before
	// the sections themselves without an output-buffering rewrite of the
	// whole template. This product page runs to 8+ sections even for a
	// single (non-bundle) item, longer still for the bundle — with nothing
	// else to jump between them on mobile.
	$cos_pdp_jump_links = array();
	if ( $desc ) {
		$cos_pdp_jump_links[] = array( 'id' => 'pdp-description', 'label' => 'Description' );
	}
	if ( $pd && ! empty( $pd['who_for'] ) ) {
		$cos_pdp_jump_links[] = array( 'id' => 'pdp-who', 'label' => "Who It's For" );
	}
	if ( ! empty( $pd['actives'] ) ) {
		$cos_pdp_jump_links[] = array( 'id' => 'pdp-actives', 'label' => 'Key Actives' );
	}
	$cos_pdp_jump_links[] = array( 'id' => 'pdp-how', 'label' => 'How to Apply' ); // unconditional, matches the section below
	if ( ! empty( $pd['stats'] ) ) {
		$cos_pdp_jump_links[] = array( 'id' => 'pdp-clinical', 'label' => 'Clinical Results' );
	}
	$cos_pdp_jump_links[] = array( 'id' => 'pdp-ingredients', 'label' => 'Ingredients' ); // unconditional, matches the section below
	if ( $pd && ! empty( $pd['specs'] ) ) {
		$cos_pdp_jump_links[] = array( 'id' => 'pdp-specs', 'label' => 'Specifications' );
	}
	if ( $pd && ! empty( $pd['faqs'] ) ) {
		$cos_pdp_jump_links[] = array( 'id' => 'pdp-faq', 'label' => 'FAQ' );
	}
	if ( 'COS-BUNDLE' === $sku ) {
		$cos_pdp_jump_links[] = array( 'id' => 'pdp-ritual-contents', 'label' => "What's Included" );
		$cos_pdp_jump_links[] = array( 'id' => 'pdp-compare', 'label' => 'Price Comparison' );
		$cos_pdp_jump_links[] = array( 'id' => 'pdp-synergy', 'label' => 'Why It Works' );
		$cos_pdp_jump_links[] = array( 'id' => 'pdp-clinical', 'label' => 'Clinical Results' );
		$cos_pdp_jump_links[] = array( 'id' => 'pdp-faq', 'label' => 'FAQ' );
	}
endwhile;

// Opt-in product video slide, appended after the image gallery. Only set on
// The Complete Ritual right now (_cos_pdp_video_url postmeta) — every other
// product has no meta here and falls back to the plain image-only gallery
// exactly as before.
$cos_video_url = get_post_meta( $product->get_id(), '_cos_pdp_video_url', true );
?>
<style nonce="<?php echo esc_attr( cos_csp_nonce() ); ?>">
#cos-pdp{font-family:var(--cos-body);color:var(--cos-ink,#171A1F);background:var(--cos-pearl,#F7F3ED)}
#cos-pdp *{box-sizing:border-box}
#cos-pdp h1,#cos-pdp h2,#cos-pdp h3,#cos-pdp h4,#cos-pdp p,#cos-pdp ul,#cos-pdp ol,#cos-pdp li,#cos-pdp figure,#cos-pdp blockquote{margin:0;padding:0}

/* ── Hero ── */
.cos-pdp-hero{display:grid;grid-template-columns:1fr;align-items:stretch}
.cos-pdp-img-col{display:flex;flex-direction:column;background:var(--cos-stone,#E5DED4)}
.cos-pdp-gallery-main{position:relative;flex:1;min-height:420px;display:flex;align-items:center;justify-content:center;overflow:hidden}
.cos-pdp-gallery-main img{position:absolute;inset:0;width:100%;height:100%;object-fit:cover;display:block;transition:opacity .3s}
.cos-pdp-gallery-main video{position:absolute;inset:0;width:100%;height:100%;object-fit:cover;display:block}
.cos-pdp-thumb-play{position:absolute;inset:0;display:flex;align-items:center;justify-content:center;background:rgba(15,27,45,.28)}
.cos-pdp-thumb-play svg{width:16px;height:16px;fill:#fff}

/* ── Thumbnail strip ── */
.cos-pdp-thumbstrip{display:none}
.cos-pdp-thumbstrip::-webkit-scrollbar{display:none}
.cos-pdp-thumb{width:68px;height:68px;flex-shrink:0;cursor:pointer;border:2px solid transparent;overflow:hidden;opacity:.45;transition:opacity .25s,border-color .25s;background:var(--cos-stone)}
.cos-pdp-thumb img{width:100%;height:100%;object-fit:cover;display:block}
.cos-pdp-thumb.is-active{opacity:1;border-color:var(--cos-ink,#171A1F)}
.cos-pdp-thumb:hover:not(.is-active){opacity:.8}

/* ── Dot navigation ── */
.cos-pdp-dots{display:flex;align-items:center;justify-content:center;gap:4px;padding:14px 0;background:rgba(229,222,212,.5);border-top:1px solid rgba(196,202,209,.15)}
/* Visual dot stays 6px (::before) — the button itself is a 44x44 CSS-pixel
   tap target (this project's preferred bar for primary/frequently-used
   controls, not just the 24px WCAG floor) so the hit area doesn't shrink
   with it. */
.cos-pdp-dot{width:44px;height:44px;min-width:44px;border-radius:50%;background:transparent;border:none;padding:0;margin:0;cursor:pointer;transition:background .2s;flex-shrink:0;display:flex;align-items:center;justify-content:center;position:relative}
.cos-pdp-dot::before{content:'';width:6px;height:6px;border-radius:50%;background:rgba(92,102,117,.3);transition:background .2s,transform .2s}
.cos-pdp-dot.is-active::before{background:var(--cos-ink,#171A1F);transform:scale(1.5)}
.cos-pdp-dot:hover:not(.is-active)::before{background:rgba(92,102,117,.6)}

/* ── Info col ── */
.cos-pdp-info{display:flex;flex-direction:column;padding:48px 24px;background:#fff}

/* ── Header block (step row + title + subtitle) ── */
.cos-pdp-header-block{margin-bottom:28px;padding-bottom:28px;border-bottom:1px solid var(--cos-stone,#E5DED4)}

/* ── Step row (pill + inline progress track) ── */
.cos-pdp-step-row{display:flex;align-items:center;gap:12px;margin-bottom:18px}
.cos-pdp-step-circle{width:28px;height:28px;border:1.5px solid var(--cos-gold,#8B692F);border-radius:50%;display:flex;align-items:center;justify-content:center;font-family:var(--cos-display);font-style:italic;font-weight:300;font-size:13px;color:var(--cos-gold,#8B692F);flex-shrink:0;line-height:1}
.cos-pdp-step-str{font-size:10px;font-weight:600;letter-spacing:.28em;text-transform:uppercase;color:var(--cos-gold,#8B692F);white-space:nowrap;flex-shrink:0}
@media(max-width:767px){.cos-pdp-step-str{letter-spacing:.18em}}

.cos-pdp-name{font-family:var(--cos-display);font-style:italic;font-weight:300;font-size:clamp(26px,3.5vw,42px);color:var(--cos-ink);line-height:1.1;margin-bottom:10px}

/* ── Ritual progress track ── */
.cos-pdp-ritual-track{display:flex;gap:4px;flex:1;min-width:50px}
.cos-pdp-rt-seg{height:2px;flex:1;border-radius:1px;background:var(--cos-stone,#E5DED4)}
.cos-pdp-rt-seg.is-past{background:var(--cos-teal,#46656B)}
.cos-pdp-rt-seg.is-cur{background:var(--cos-gold,#8B692F)}

.cos-pdp-price-row{display:flex;align-items:baseline;gap:12px;margin-bottom:16px}
.cos-pdp-price{font-size:30px;font-weight:300;color:var(--cos-ink);font-variant-numeric:lining-nums tabular-nums}
.cos-pdp-sale-original{font-size:16px;font-weight:300;color:var(--cos-slate);text-decoration:line-through}
.cos-pdp-save{font-size:11px;font-weight:500;color:var(--cos-teal,#46656B);padding:3px 8px;border:1px solid var(--cos-teal)}

/* ── Size line ── */
.cos-pdp-size-line{font-size:11px;font-weight:400;color:var(--cos-slate,#5C6675);letter-spacing:.04em;margin-bottom:32px;display:flex;align-items:center;gap:6px}
.cos-pdp-size-line::before{content:'';display:inline-block;width:3px;height:3px;background:var(--cos-gold-l,#B8965F);flex-shrink:0}

.cos-pdp-short-desc{font-size:14px;font-weight:300;color:var(--cos-slate);line-height:1.8;margin-bottom:24px}
.cos-pdp-short-desc ul{padding-left:16px;list-style:disc;display:flex;flex-direction:column;gap:5px;margin-top:12px}

/* ── ATC Form ── */
.cos-pdp-form{display:flex;flex-direction:column;gap:12px;margin-bottom:16px}
.cos-pdp-qty-row{display:flex;align-items:center;gap:16px}
.cos-pdp-qty-label{font-size:10px;font-weight:600;letter-spacing:.12em;text-transform:uppercase;color:var(--cos-slate);min-width:32px}
.cos-pdp-qty-wrap{display:flex;align-items:center;border:1px solid var(--cos-stone,#E5DED4);background:#fff}
.cos-pdp-qty-btn{width:44px;height:44px;display:flex;align-items:center;justify-content:center;background:none;border:none;cursor:pointer;font-size:17px;color:var(--cos-ink);font-family:var(--cos-body);transition:background .15s}
.cos-pdp-qty-btn:hover{background:var(--cos-stone)}
.cos-pdp-qty-input{width:44px;height:44px;text-align:center;border:none;border-left:1px solid var(--cos-stone);border-right:1px solid var(--cos-stone);font-size:14px;font-weight:300;font-family:var(--cos-body);color:var(--cos-ink);background:#fff;outline:none;-moz-appearance:textfield}
.cos-pdp-qty-input::-webkit-inner-spin-button,.cos-pdp-qty-input::-webkit-outer-spin-button{-webkit-appearance:none}
.cos-pdp-atc-btn{padding:18px 36px;min-height:44px;box-sizing:border-box;background:var(--cos-navy,#0F1B2D);color:#fff;font-size:10px;font-weight:600;letter-spacing:.2em;text-transform:uppercase;border:none;cursor:pointer;transition:background .2s;font-family:var(--cos-body);text-align:center;display:block;width:100%}
.cos-pdp-atc-btn:hover{background:var(--cos-ink,#171A1F)}
.cos-pdp-view-ritual{display:block;text-align:center;padding:13px 36px;border:1px solid var(--cos-stone,#E5DED4);color:var(--cos-slate);font-size:10px;font-weight:600;letter-spacing:.2em;text-transform:uppercase;text-decoration:none;transition:border-color .2s,color .2s;margin-top:20px;margin-bottom:20px}
.cos-pdp-view-ritual:hover{border-color:var(--cos-navy);color:var(--cos-navy)}

.cos-pdp-bundle-nudge{display:block;text-align:center;font-size:11px;font-weight:500;color:var(--cos-teal,#46656B);text-decoration:underline;text-underline-offset:2px;margin-top:20px;margin-bottom:20px}
.cos-pdp-bundle-nudge:hover{color:var(--cos-navy,#0F1B2D)}

.cos-pdp-who-mini{font-size:12px;font-weight:300;color:var(--cos-slate,#5C6675);line-height:1.65;margin-bottom:24px}
.cos-pdp-who-mini strong{font-weight:600;color:var(--cos-ink)}

/* ── Trust grid with SVG icons ── */
.cos-pdp-trust{display:grid;grid-template-columns:1fr 1fr;gap:14px 20px;margin-top:24px;padding-top:20px;border-top:1px solid var(--cos-stone,#E5DED4)}
.cos-pdp-trust-item{font-size:11px;font-weight:500;letter-spacing:.08em;text-transform:uppercase;color:var(--cos-slate,#5C6675);display:flex;align-items:center;gap:7px;line-height:1.4;text-decoration:none}
a.cos-pdp-trust-item:hover{color:var(--cos-navy,#0F1B2D)}
.cos-pdp-trust-ico{width:13px;height:13px;flex-shrink:0;color:var(--cos-gold-l,#B8965F)}

.cos-pdp-methodology-link{font-size:10px;font-weight:500;letter-spacing:.04em;color:var(--cos-slate,#5C6675);text-align:center;margin-top:16px}
.cos-pdp-methodology-link a{color:inherit;text-decoration:underline;text-underline-offset:2px}

/* ── Sticky mobile Add to Bag bar ── */
.cos-pdp-sticky-atc{position:fixed;left:0;right:0;bottom:0;z-index:40;display:flex;align-items:center;gap:12px;background:#fff;border-top:1px solid var(--cos-stone,#E5DED4);box-shadow:0 -4px 16px rgba(18,27,44,.08);padding:10px 16px calc(10px + env(safe-area-inset-bottom));transform:translateY(100%);transition:transform .25s ease;will-change:transform}
.cos-pdp-sticky-atc.is-visible{transform:translateY(0)}
.cos-pdp-sticky-info{flex:1;min-width:0}
.cos-pdp-sticky-name{display:block;font-size:11px;font-weight:600;color:var(--cos-ink);white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.cos-pdp-sticky-price{display:block;font-size:12px;font-weight:300;color:var(--cos-slate,#5C6675);font-variant-numeric:tabular-nums}
.cos-pdp-sticky-btn{flex-shrink:0;padding:0 24px;min-height:44px;display:flex;align-items:center;justify-content:center;background:var(--cos-navy,#0F1B2D);color:#fff;font-size:10px;font-weight:600;letter-spacing:.16em;text-transform:uppercase;text-decoration:none;border:none;cursor:pointer;transition:background .2s}
.cos-pdp-sticky-btn:hover{background:var(--cos-ink,#171A1F)}
.cos-pdp-sticky-btn.loading{opacity:.55;pointer-events:none}
@media(min-width:768px){.cos-pdp-sticky-atc{display:none}}

/* ── In-page contents menu ── */
/* Not sticky: html/body carry a sitewide overflow-x:hidden safeguard
   (prevents horizontal-scroll bugs elsewhere), which makes position:sticky
   unreliable here — the containing scroll box it resolves against isn't the
   viewport once an ancestor sets overflow to anything but visible. A static
   row right above the content it indexes still fully solves the actual
   problem (jump straight to a section instead of a long undifferentiated
   scroll) without fighting that constraint. Horizontal scroll rather than
   wrap: a fixed single-row height that never pushes page content down by a
   variable amount as the link count changes per product. */
.cos-pdp-jumpnav{display:flex;gap:6px;overflow-x:auto;-webkit-overflow-scrolling:touch;scrollbar-width:none;padding:16px;background:rgba(229,222,212,.4);border-top:1px solid var(--cos-stone,#E5DED4);border-bottom:1px solid var(--cos-stone,#E5DED4)}
.cos-pdp-jumpnav::-webkit-scrollbar{display:none}
.cos-pdp-jump-link{flex-shrink:0;display:inline-flex;align-items:center;min-height:44px;padding:0 16px;background:#fff;border:1px solid var(--cos-stone,#E5DED4);border-radius:99px;font-family:var(--cos-body);font-size:10px;font-weight:600;letter-spacing:.06em;text-transform:uppercase;color:var(--cos-slate,#5C6675);text-decoration:none;white-space:nowrap;transition:border-color .2s,color .2s}
.cos-pdp-jump-link:hover{border-color:var(--cos-navy,#0F1B2D);color:var(--cos-navy,#0F1B2D)}

/* ── Description ── */
.cos-pdp-desc-section{background:#fff;border-top:1px solid var(--cos-stone,#E5DED4);padding:72px 24px}
.cos-pdp-desc-inner{max-width:760px;margin:0 auto}
.cos-pdp-desc-inner h2{font-family:var(--cos-display);font-style:italic;font-weight:300;font-size:clamp(22px,2.5vw,34px);color:var(--cos-ink);margin-bottom:24px;line-height:1.2}
.cos-pdp-desc-inner h3{font-family:var(--cos-body);font-size:10px;font-weight:600;letter-spacing:.22em;text-transform:uppercase;color:var(--cos-gold,#8B692F);margin-top:32px;margin-bottom:12px}
.cos-pdp-desc-inner p{font-size:14px;font-weight:300;color:var(--cos-slate,#5C6675);line-height:1.85;margin-bottom:16px}
.cos-pdp-desc-inner ul{padding-left:18px;list-style:disc;margin-bottom:14px}
.cos-pdp-desc-inner li{font-size:14px;font-weight:300;color:var(--cos-slate);line-height:1.7;margin-bottom:6px}

/* ── Key Actives ── */
.cos-pdp-actives{background:var(--cos-pearl,#F7F3ED);padding:72px 24px}
.cos-pdp-actives-inner{max-width:1100px;margin:0 auto}
.cos-pdp-section-eyebrow{font-size:10px;font-weight:600;letter-spacing:.28em;text-transform:uppercase;color:var(--cos-gold,#8B692F);margin-bottom:12px}
@media(max-width:767px){.cos-pdp-section-eyebrow{letter-spacing:.18em}}
.cos-pdp-section-title{font-family:var(--cos-display);font-style:italic;font-weight:300;font-size:clamp(22px,2.5vw,34px);color:var(--cos-ink);margin-bottom:40px;line-height:1.2}
.cos-pdp-actives-grid{display:grid;grid-template-columns:1fr 1fr;gap:2px}
.cos-pdp-active-card{background:#fff;padding:36px 32px 40px;border-top:2px solid var(--cos-gold,#8B692F);position:relative;overflow:hidden}
.cos-pdp-active-name{font-size:11px;font-weight:600;letter-spacing:.14em;text-transform:uppercase;color:var(--cos-ink);margin-bottom:12px;position:relative;z-index:1}
.cos-pdp-active-desc{font-size:13px;font-weight:300;color:var(--cos-slate,#5C6675);line-height:1.8;position:relative;z-index:1}
.cos-pdp-active-bg-n{position:absolute;bottom:-20px;right:12px;font-family:var(--cos-display);font-style:italic;font-size:96px;font-weight:300;color:rgba(229,222,212,.55);line-height:1;pointer-events:none;user-select:none;z-index:0;letter-spacing:-.02em}

/* ── How to Apply ── */
.cos-pdp-how{background:#fff;padding:72px 24px;border-top:1px solid var(--cos-stone,#E5DED4)}
.cos-pdp-how-inner{max-width:760px;margin:0 auto}
.cos-pdp-how-steps{position:relative;display:flex;flex-direction:column;gap:0;margin-top:8px}
.cos-pdp-how-steps::before{content:'';position:absolute;left:19px;top:4px;bottom:4px;width:1px;background:linear-gradient(to bottom,transparent 0%,var(--cos-stone,#E5DED4) 8%,var(--cos-stone,#E5DED4) 92%,transparent 100%);z-index:0}
.cos-pdp-how-step{display:grid;grid-template-columns:40px 1fr;gap:16px;padding:20px 0;border-bottom:1px solid var(--cos-stone,#E5DED4);align-items:start;position:relative;z-index:1}
.cos-pdp-how-step:last-child{border-bottom:none}
.cos-pdp-how-num{font-family:var(--cos-display);font-style:italic;font-weight:300;font-size:22px;color:var(--cos-gold,#8B692F);line-height:1;background:#fff;position:relative;z-index:2;padding:2px 0}
.cos-pdp-how-text{font-size:14px;font-weight:300;color:var(--cos-slate,#5C6675);line-height:1.8;padding-top:2px}

/* ── Clinical Results ── */
.cos-pdp-clinical{background:var(--cos-navy,#0F1B2D);padding:72px 24px}
.cos-pdp-clinical-inner{max-width:1100px;margin:0 auto}
.cos-pdp-clinical .cos-pdp-section-eyebrow{color:var(--cos-gold-l,#B8965F)}
.cos-pdp-clinical .cos-pdp-section-title{color:#fff;margin-bottom:48px}
.cos-pdp-stats{display:grid;grid-template-columns:repeat(3,1fr);gap:1px;background:rgba(255,255,255,.06)}
.cos-pdp-stats-4{grid-template-columns:repeat(2,1fr)}
.cos-pdp-stat{background:var(--cos-navy,#0F1B2D);padding:44px 28px;text-align:center;position:relative}
.cos-pdp-stat::before{content:'';position:absolute;top:0;left:50%;transform:translateX(-50%);width:30px;height:2px;background:var(--cos-gold-l,#B8965F);opacity:.5}
.cos-pdp-stat-num{font-family:var(--cos-display);font-style:italic;font-weight:300;font-size:clamp(36px,4vw,58px);color:#fff;line-height:1;margin-bottom:14px;display:block}
.cos-pdp-stat-label{font-size:11px;font-weight:300;color:rgba(255,255,255,.55);line-height:1.7;letter-spacing:.04em}
.cos-pdp-clinical-note{font-size:10px;font-weight:300;color:rgba(255,255,255,.6);text-align:center;margin-top:32px;letter-spacing:.06em;text-transform:uppercase}

/* ── Full Ingredients ── */
.cos-pdp-inci{background:#fff;padding:72px 24px;border-top:1px solid var(--cos-stone,#E5DED4)}
.cos-pdp-inci-inner{max-width:760px;margin:0 auto}
.cos-pdp-inci-inner > .cos-pdp-section-title{margin-bottom:24px}
.cos-pdp-inci-details{border:1px solid var(--cos-stone,#E5DED4)}
.cos-pdp-inci-toggle{display:flex;justify-content:space-between;align-items:center;cursor:pointer;padding:18px 24px;list-style:none;user-select:none;background:#fff;transition:background .15s}
.cos-pdp-inci-toggle::-webkit-details-marker{display:none}
.cos-pdp-inci-toggle:hover{background:var(--cos-pearl,#F7F3ED)}
.cos-pdp-inci-toggle-label{font-size:10px;font-weight:600;letter-spacing:.18em;text-transform:uppercase;color:var(--cos-ink)}
.cos-pdp-inci-toggle-icon{font-size:18px;font-weight:300;color:var(--cos-slate);transition:transform .25s;display:inline-block;line-height:1}
details[open] .cos-pdp-inci-toggle-icon{transform:rotate(45deg)}
.cos-pdp-inci-body{padding:20px 24px;border-top:1px solid var(--cos-stone,#E5DED4)}
.cos-pdp-inci-text{font-size:12px;font-weight:300;color:var(--cos-slate,#5C6675);line-height:1.9;letter-spacing:.02em}

/* ── Bundle: Price Comparison ── */
.cos-pdp-compare{background:var(--cos-pearl,#F7F3ED);padding:72px 24px;border-top:1px solid var(--cos-stone,#E5DED4)}
.cos-pdp-compare-inner{max-width:640px;margin:0 auto}
.cos-pdp-compare-table{width:100%;border-collapse:collapse}
.cos-pdp-compare-table tr{border-bottom:1px solid var(--cos-stone,#E5DED4)}
.cos-pdp-compare-table td{padding:16px 0;font-size:13px;font-weight:300;color:var(--cos-slate,#5C6675);vertical-align:middle}
.cos-pdp-compare-table td:last-child{text-align:right;font-variant-numeric:tabular-nums;color:var(--cos-ink)}
.cos-pdp-compare-total td{padding-top:20px;border-top:1px solid var(--cos-ink,#171A1F)}
.cos-pdp-compare-total td:first-child{font-weight:500;color:var(--cos-ink)}
.cos-pdp-compare-total td:last-child{text-decoration:line-through;color:var(--cos-slate,#5C6675)}
.cos-pdp-compare-bundle td{border-bottom:none;padding-top:14px}
.cos-pdp-compare-bundle td:first-child{font-weight:600;color:var(--cos-ink);font-size:14px}
.cos-pdp-compare-bundle td:last-child{font-weight:600;font-size:18px;color:var(--cos-ink)}
.cos-pdp-compare-save{background:var(--cos-navy,#0F1B2D);display:flex;align-items:center;justify-content:space-between;padding:18px 24px;margin-top:24px}
.cos-pdp-compare-save-label{font-size:10px;font-weight:600;letter-spacing:.16em;text-transform:uppercase;color:rgba(255,255,255,.7)}
.cos-pdp-compare-save-amt{font-family:var(--cos-display);font-style:italic;font-weight:300;font-size:24px;color:var(--cos-gold-l,#B8965F)}

/* ── Bundle: What's in the Ritual ── */
.cos-pdp-ritual{background:#fff;padding:72px 24px;border-top:1px solid var(--cos-stone,#E5DED4)}
.cos-pdp-ritual-inner{max-width:1100px;margin:0 auto}
.cos-pdp-ritual-grid{display:grid;grid-template-columns:1fr 1fr;gap:2px;background:var(--cos-stone,#E5DED4);border:1px solid var(--cos-stone,#E5DED4)}
.cos-pdp-ritual-card{background:#fff;display:flex;flex-direction:column;text-decoration:none;color:inherit;transition:box-shadow .3s}
.cos-pdp-ritual-card:hover{box-shadow:0 16px 40px rgba(18,27,44,.12)}
.cos-pdp-ritual-img-wrap{position:relative;width:100%;padding-bottom:75%;overflow:hidden;background:var(--cos-pearl,#F7F3ED)}
.cos-pdp-ritual-img{position:absolute!important;inset:0;width:100%!important;height:100%!important;object-fit:contain!important;padding:20px;transition:transform .5s cubic-bezier(.25,.46,.45,.94)}
.cos-pdp-ritual-card:hover .cos-pdp-ritual-img{transform:scale(1.05)}
.cos-pdp-ritual-body{padding:24px}
.cos-pdp-ritual-step{font-family:var(--cos-display);font-style:italic;font-weight:300;font-size:11px;color:var(--cos-gold,#8B692F);letter-spacing:.1em;text-transform:uppercase;margin-bottom:6px}
.cos-pdp-ritual-name{font-family:var(--cos-display);font-style:italic;font-weight:300;font-size:19px;color:var(--cos-ink);margin-bottom:4px;line-height:1.25}
.cos-pdp-ritual-subtitle{font-size:10px;font-weight:600;letter-spacing:.08em;text-transform:uppercase;color:var(--cos-slate,#5C6675);margin-bottom:10px}
.cos-pdp-ritual-desc{font-size:13px;font-weight:300;color:var(--cos-slate,#5C6675);line-height:1.75}
.cos-pdp-ritual-link{font-size:10px;font-weight:600;letter-spacing:.14em;text-transform:uppercase;color:var(--cos-ink);border-bottom:1px solid var(--cos-stone,#E5DED4);padding-bottom:2px;display:inline-block;margin-top:12px}
.cos-pdp-ritual-card:hover .cos-pdp-ritual-link{border-color:var(--cos-gold,#8B692F);color:var(--cos-gold,#8B692F)}

/* ── Bundle: full-bleed visual break ── */
.cos-pdp-visual-break{position:relative;height:60vh;min-height:380px;max-height:640px;overflow:hidden;display:flex;align-items:flex-end}
.cos-pdp-visual-break-img{position:absolute;inset:0;width:100%;height:100%;object-fit:cover;object-position:center 30%}
.cos-pdp-visual-break-overlay{position:absolute;inset:0;background:linear-gradient(to top,rgba(15,27,45,.82) 0%,rgba(15,27,45,.25) 45%,rgba(15,27,45,0) 70%)}
.cos-pdp-visual-break-cap{position:relative;z-index:1;padding:40px 24px;max-width:520px}
.cos-pdp-visual-break-eyebrow{font-size:10px;font-weight:600;letter-spacing:.26em;text-transform:uppercase;color:var(--cos-gold-l,#B8965F);margin-bottom:10px}
@media(max-width:767px){.cos-pdp-visual-break-eyebrow{letter-spacing:.18em}}
.cos-pdp-visual-break-text{font-family:var(--cos-display);font-style:italic;font-weight:300;font-size:clamp(20px,2.6vw,30px);color:#fff;line-height:1.3}

/* ── Bundle: Synergy (image + steps split) ── */
.cos-pdp-synergy{background:var(--cos-pearl,#F7F3ED);padding:0}
.cos-pdp-synergy-grid{display:grid;grid-template-columns:1fr}
.cos-pdp-synergy-img-col{position:relative;min-height:320px;background:var(--cos-stone,#E5DED4)}
.cos-pdp-synergy-img{position:absolute;inset:0;width:100%;height:100%;object-fit:cover}
.cos-pdp-synergy-text-col{padding:56px 24px}
.cos-pdp-synergy-inner{max-width:520px}
.cos-pdp-synergy-lede{font-size:14px;font-weight:300;color:var(--cos-slate,#5C6675);line-height:1.85;margin-bottom:32px}
.cos-pdp-synergy-flow{display:flex;flex-direction:column;gap:0;margin-bottom:32px}
.cos-pdp-synergy-step{display:grid;grid-template-columns:32px 1fr;gap:16px;padding:14px 0;border-bottom:1px solid var(--cos-stone,#E5DED4)}
.cos-pdp-synergy-step:last-child{border-bottom:none}
.cos-pdp-synergy-arrow{font-family:var(--cos-display);font-style:italic;font-size:16px;color:var(--cos-gold,#8B692F);line-height:1.6}
.cos-pdp-synergy-text{font-size:13px;font-weight:300;color:var(--cos-slate,#5C6675);line-height:1.75}
.cos-pdp-synergy-text strong{color:var(--cos-ink);font-weight:600}
.cos-pdp-synergy-note{background:#fff;border-left:3px solid var(--cos-gold,#8B692F);padding:20px 24px;font-size:13px;font-weight:300;color:var(--cos-slate,#5C6675);line-height:1.8}

/* ── Bundle: Presentation (image spread) ── */
.cos-pdp-presentation{background:#fff;padding:72px 24px;border-top:1px solid var(--cos-stone,#E5DED4)}
.cos-pdp-presentation-inner{max-width:1100px;margin:0 auto}
.cos-pdp-presentation-lede{font-size:14px;font-weight:300;color:var(--cos-slate,#5C6675);line-height:1.85;margin-bottom:36px;max-width:640px}
.cos-pdp-presentation-grid{display:grid;grid-template-columns:1fr;gap:2px}
.cos-pdp-presentation-item{position:relative;aspect-ratio:4/5;overflow:hidden;background:var(--cos-stone,#E5DED4)}
.cos-pdp-presentation-item img{position:absolute;inset:0;width:100%;height:100%;object-fit:cover;transition:transform .5s cubic-bezier(.25,.46,.45,.94)}
.cos-pdp-presentation-item:hover img{transform:scale(1.05)}
.cos-pdp-presentation-cap{position:absolute;left:0;right:0;bottom:0;padding:18px 20px;background:linear-gradient(to top,rgba(15,27,45,.75),transparent);color:#fff;font-size:11px;font-weight:500;letter-spacing:.04em}

/* ── Responsive ── */
@media(min-width:768px){
  .cos-pdp-info{padding:64px 52px;justify-content:center}
  .cos-pdp-desc-section{padding:88px 48px}
  .cos-pdp-actives{padding:88px 48px}
  .cos-pdp-how{padding:88px 48px}
  .cos-pdp-clinical{padding:88px 48px}
  .cos-pdp-inci{padding:72px 48px}
  .cos-pdp-ritual{padding:88px 48px}
  .cos-pdp-synergy-grid{grid-template-columns:1fr 1fr}
  .cos-pdp-synergy-img-col{min-height:auto}
  .cos-pdp-synergy-text-col{padding:72px 56px}
  .cos-pdp-presentation{padding:88px 48px}
  .cos-pdp-presentation-grid{grid-template-columns:repeat(3,1fr)}
  .cos-pdp-visual-break-cap{padding:0 0 64px 64px}
}
/* .cos-pdp-hero stays single-column through 1023px — the 2-column split
   only starts here. align-items:start (instead of the grid default of
   stretch) stops .cos-pdp-img-col from being force-stretched to match
   .cos-pdp-info's height, which could otherwise run long (short
   description, benefits list, trust grid) and drag the product photo into
   an oversized, cropped-looking box. The image is sized by its own
   aspect-ratio/max-height instead, and stickied within the (still taller)
   info column's row height so it stays in view while the visitor scrolls
   through the longer copy next to it. */
@media(min-width:1024px){
  .cos-pdp-hero{grid-template-columns:1fr 1fr;align-items:start}
  .cos-pdp-img-col{position:sticky;top:calc(var(--cos-nav-h,80px) + 24px);align-self:start}
  .cos-pdp-gallery-main{flex:none;min-height:0;aspect-ratio:4/5;max-height:calc(100svh - var(--cos-nav-h,80px) - 48px)}
  .cos-pdp-ritual-grid{grid-template-columns:repeat(4,1fr)}
}
@media(min-width:1200px){
  .cos-pdp-info{padding:80px 72px}
}
@media(max-width:600px){
  .cos-pdp-actives-grid{grid-template-columns:1fr}
  .cos-pdp-stats{grid-template-columns:1fr}
}

/* ── Subtitle ── */
.cos-pdp-subtitle{font-family:var(--cos-display);font-style:italic;font-weight:300;font-size:13px;color:var(--cos-slate);letter-spacing:.02em}

/* ── Benefits list ── */
.cos-pdp-benefits-list{margin:0 0 36px;padding:0;list-style:none;display:flex;flex-direction:column;gap:11px}
.cos-pdp-benefit-item{font-size:13px;font-weight:300;color:var(--cos-slate);display:flex;align-items:flex-start;gap:10px;line-height:1.6}
.cos-pdp-benefit-ico{width:14px;height:14px;flex-shrink:0;color:var(--cos-gold-l,#B8965F);margin-top:2px}

/* ── Evidence near CTA (UI/UX pass 2026-09-19, task 5) ── */
/* Only ever rendered for products with a real, published statistic
   ($pd['stats'] -- see inc/product-data.php) -- the cleanser has none and
   never gets this block. Reuses that exact statistic text unchanged; this
   is a compact pointer to it near the point of purchase, not a
   restatement/rewrite. The full "Combined Clinical Results" section
   further down the page is unaffected and still shows every stat. */
.cos-pdp-evidence{display:flex;align-items:baseline;flex-wrap:wrap;gap:6px 14px;margin-bottom:20px;padding:14px 16px;background:var(--cos-pearl,#F7F3ED);border-left:3px solid var(--cos-gold,#8B692F)}
.cos-pdp-evidence-num{font-family:var(--cos-display);font-style:italic;font-weight:300;font-size:24px;color:var(--cos-ink,#171A1F);line-height:1}
.cos-pdp-evidence-label{font-size:12px;font-weight:400;color:var(--cos-slate,#5C6675);line-height:1.4;flex:1;min-width:140px}
.cos-pdp-evidence-link{font-size:10px;font-weight:600;letter-spacing:.08em;text-transform:uppercase;color:var(--cos-teal,#46656B);text-decoration:underline;text-underline-offset:2px;white-space:nowrap}
.cos-pdp-evidence-link:hover{color:var(--cos-navy,#0F1B2D)}

/* ── Who It Is For + Skin Concerns ── */
.cos-pdp-who{background:var(--cos-pearl,#F7F3ED);padding:72px 24px;border-top:1px solid var(--cos-stone,#E5DED4)}
.cos-pdp-who-inner{max-width:1100px;margin:0 auto;display:grid;grid-template-columns:1fr;gap:48px}
.cos-pdp-who-block .cos-pdp-section-eyebrow{margin-bottom:14px}
.cos-pdp-who-text{font-size:14px;font-weight:300;color:var(--cos-slate);line-height:1.85}
.cos-pdp-concerns-tags{display:flex;flex-wrap:wrap;gap:8px;margin-top:4px}
.cos-pdp-concern-tag{font-size:10px;font-weight:500;letter-spacing:.1em;text-transform:uppercase;color:var(--cos-ink);padding:8px 14px;border:1px solid var(--cos-stone,#E5DED4);background:#fff;text-decoration:none;display:inline-block}
a.cos-pdp-concern-tag{cursor:pointer;transition:border-color .2s,color .2s}
a.cos-pdp-concern-tag:hover{border-color:var(--cos-gold,#8B692F);color:var(--cos-gold,#8B692F)}

/* ── Texture & Sensorial ── */
.cos-pdp-texture{margin-top:32px;padding-top:28px;border-top:1px solid var(--cos-stone,#E5DED4)}
.cos-pdp-texture p{font-size:14px;font-weight:300;color:var(--cos-slate);line-height:1.85;font-style:italic}

/* ── Product Specifications ── */
.cos-pdp-specs{background:var(--cos-pearl,#F7F3ED);padding:72px 24px;border-top:1px solid var(--cos-stone,#E5DED4)}
.cos-pdp-specs-inner{max-width:760px;margin:0 auto}
.cos-pdp-specs-table{width:100%;border-collapse:collapse;margin-top:24px}
.cos-pdp-specs-table tr{border-bottom:1px solid var(--cos-stone,#E5DED4)}
.cos-pdp-specs-table td{padding:14px 0;font-size:13px;font-weight:300;color:var(--cos-slate);line-height:1.5;vertical-align:top}
.cos-pdp-specs-table td:first-child{font-size:10px;font-weight:600;letter-spacing:.14em;text-transform:uppercase;color:var(--cos-ink);width:40%;padding-right:24px}
.cos-pdp-clean-note{margin-top:32px;padding-top:24px;border-top:1px solid var(--cos-stone,#E5DED4)}
.cos-pdp-clean-note p{font-size:12px;font-weight:300;color:var(--cos-slate);line-height:1.85;font-style:italic}

/* ── FAQ ── */
.cos-pdp-faq{background:#fff;padding:72px 24px;border-top:1px solid var(--cos-stone,#E5DED4)}
.cos-pdp-faq-inner{max-width:760px;margin:0 auto}
.cos-pdp-faq-item{border-bottom:1px solid var(--cos-stone,#E5DED4)}
.cos-pdp-faq-toggle{display:flex;justify-content:space-between;align-items:center;cursor:pointer;padding:20px 0;list-style:none;user-select:none;transition:color .15s}
.cos-pdp-faq-toggle::-webkit-details-marker{display:none}
.cos-pdp-faq-q{font-size:13px;font-weight:500;color:var(--cos-ink);line-height:1.5;flex:1;padding-right:16px}
.cos-pdp-faq-icon{font-size:18px;font-weight:300;color:var(--cos-slate);transition:transform .25s;display:inline-block;line-height:1;flex-shrink:0}
details.cos-pdp-faq-item[open] .cos-pdp-faq-icon{transform:rotate(45deg)}
.cos-pdp-faq-a{padding:0 0 20px;font-size:13px;font-weight:300;color:var(--cos-slate);line-height:1.85}

@media(min-width:768px){
  .cos-pdp-who{padding:88px 48px}
  .cos-pdp-who-inner{grid-template-columns:1fr 1fr}
  .cos-pdp-specs{padding:88px 48px}
  .cos-pdp-faq{padding:88px 48px}
}
</style>

<main id="cos-main-content">
  <div id="cos-pdp">
    <div style="height:var(--cos-nav-h,80px)"></div>

    <!-- Hero -->
    <div class="cos-pdp-hero">

      <!-- Image column -->
      <div class="cos-pdp-img-col">
        <?php
        $first_id   = ! empty( $gallery_ids ) ? $gallery_ids[0] : $img_id;
        $first_data = $first_id ? wp_get_attachment_image_src( $first_id, 'large' ) : false;
        $first_src  = $first_data ? $first_data[0] : $img_src;
        $first_w    = $first_data ? $first_data[1] : 0;
        $first_h    = $first_data ? $first_data[2] : 0;
        $first_srcset = $first_id ? wp_get_attachment_image_srcset( $first_id, 'large' ) : false;
        $first_sizes  = $first_id ? wp_get_attachment_image_sizes( $first_id, 'large' ) : false;
        $all_srcs   = array();
        foreach ( $gallery_ids as $gid ) {
            $s = wp_get_attachment_image_url( $gid, 'large' );
            if ( $s ) $all_srcs[] = $s;
        }
        $srcs_json = wp_json_encode( $all_srcs );
        ?>
        <div class="cos-pdp-gallery-main">
          <?php
          // NOT wrapped in cos_child_nextgen_picture() like the shop cards/thumbnails
          // below — this <img>'s src is swapped by plain JS on thumbnail/dot click
          // (cosGallerySet() further down), which needed its own real fix 2026-08-18
          // (see comment there) for a pre-existing bug the wrapping attempt surfaced.
          // A <picture> adds a second, independent layer of the same class of
          // problem — tested and confirmed: removing a <picture>'s <source>
          // siblings via JS does not reliably make Chrome re-resolve currentSrc, so
          // the stale first photo kept rendering even with `src` correctly updated
          // and the <source>s gone. Not worth the added risk here; left as plain
          // JPEG. The static gallery thumbnails and every shop grid image are still
          // AVIF/WebP via the same helper, since neither of them has this swap
          // interaction.
          ?>
          <img id="cos-gallery-main" src="<?php echo esc_url( $first_src ); ?>"<?php if ( $first_srcset ) : ?> srcset="<?php echo esc_attr( $first_srcset ); ?>" sizes="<?php echo esc_attr( $first_sizes ?: '(min-width: 768px) 50vw, 100vw' ); ?>"<?php endif; ?> width="<?php echo esc_attr( $first_w ); ?>" height="<?php echo esc_attr( $first_h ); ?>" alt="<?php echo esc_attr( $pd['image_alt'] ?? $name ); ?>" fetchpriority="high"/>
          <?php if ( $cos_video_url ) : ?>
          <video id="cos-gallery-video" data-src="<?php echo esc_url( $cos_video_url ); ?>" preload="none" poster="<?php echo esc_url( $img_src ); ?>" muted loop playsinline style="display:none"></video>
          <?php endif; ?>
        </div>

        <?php $cos_slide_count = count( $gallery_ids ) + ( $cos_video_url ? 1 : 0 ); ?>
        <?php if ( $cos_slide_count > 1 ) : ?>
        <div class="cos-pdp-thumbstrip" id="cos-thumbstrip">
          <?php foreach ( $gallery_ids as $ti => $tid ) :
            $tdata    = wp_get_attachment_image_src( $tid, 'medium' );
            if ( ! $tdata ) continue;
            $tsrcset = wp_get_attachment_image_srcset( $tid, 'medium' );
          ?>
          <button class="cos-pdp-thumb<?php echo $ti === 0 ? ' is-active' : ''; ?>"
            onclick="cosGallerySet(<?php echo $ti; ?>)"
            type="button"
            aria-label="View image <?php echo $ti + 1; ?>">
            <?php
            $thumb_sizes = $tsrcset ? '68px' : '';
            $thumb_html  = '<img src="' . esc_url( $tdata[0] ) . '"' . ( $tsrcset ? ' srcset="' . esc_attr( $tsrcset ) . '" sizes="' . esc_attr( $thumb_sizes ) . '"' : '' ) . ' width="' . esc_attr( $tdata[1] ) . '" height="' . esc_attr( $tdata[2] ) . '" alt="" loading="lazy" aria-hidden="true"/>';
            echo cos_child_nextgen_picture( $tdata[0], $tsrcset ?: '', $thumb_sizes, $thumb_html );
            ?>
          </button>
          <?php endforeach; ?>
          <?php if ( $cos_video_url ) : ?>
          <button class="cos-pdp-thumb"
            onclick="cosGallerySet(<?php echo count( $gallery_ids ); ?>)"
            type="button"
            aria-label="Play product video">
            <?php
            $video_thumb_html = '<img src="' . esc_url( $img_src ) . '" width="' . esc_attr( $img_w ) . '" height="' . esc_attr( $img_h ) . '" alt="" loading="lazy" aria-hidden="true"/>';
            echo cos_child_nextgen_picture( $img_src, '', '', $video_thumb_html );
            ?>
            <span class="cos-pdp-thumb-play" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg></span>
          </button>
          <?php endif; ?>
        </div>
        <div class="cos-pdp-dots" id="cos-dots" role="group" aria-label="Image navigation">
          <?php foreach ( $gallery_ids as $ti => $tid ) : ?>
          <button class="cos-pdp-dot<?php echo $ti === 0 ? ' is-active' : ''; ?>"
            onclick="cosGallerySet(<?php echo $ti; ?>)"
            type="button"
            aria-label="Image <?php echo $ti + 1; ?>"></button>
          <?php endforeach; ?>
          <?php if ( $cos_video_url ) : ?>
          <button class="cos-pdp-dot" onclick="cosGallerySet(<?php echo count( $gallery_ids ); ?>)" type="button" aria-label="Product video"></button>
          <?php endif; ?>
        </div>
        <?php endif; ?>
      </div>

      <!-- Info column -->
      <div class="cos-pdp-info">

        <div class="cos-pdp-header-block">

          <?php if ( $step['num'] ) :
            $sn = (int) $step['num'];
          ?>
          <div class="cos-pdp-step-row" aria-label="Step <?php echo $sn; ?> of 4<?php echo $step['role'] ? ' — ' . esc_attr( $step['role'] ) : ''; ?>">
            <span class="cos-pdp-step-circle"><?php echo esc_html( $step['num'] ); ?></span>
            <span class="cos-pdp-step-str"><?php echo esc_html( $step['label'] ); ?></span>
            <div class="cos-pdp-ritual-track">
              <?php for ( $s = 1; $s <= 4; $s++ ) :
                $sc = $s < $sn ? ' is-past' : ( $s === $sn ? ' is-cur' : '' );
              ?>
              <div class="cos-pdp-rt-seg<?php echo $sc; ?>"></div>
              <?php endfor; ?>
            </div>
          </div>
          <?php endif; ?>

          <h1 class="cos-pdp-name"><?php echo esc_html( $name ); ?></h1>

          <?php if ( isset( $pd['subtitle'] ) ) : ?>
          <p class="cos-pdp-subtitle"><?php echo esc_html( $pd['subtitle'] ); ?></p>
          <?php endif; ?>

        </div>

        <!-- Price -->
        <div class="cos-pdp-price-row">
          <?php if ( $sale ) : ?>
            <span class="cos-pdp-price"><?php echo wc_price( $sale ); ?></span>
            <span class="cos-pdp-sale-original"><?php echo wc_price( $regular ); ?></span>
            <span class="cos-pdp-save">Save <?php echo wc_price( $regular - $sale ); ?></span>
          <?php else : ?>
            <span class="cos-pdp-price"><?php echo wc_price( $price ); ?></span>
          <?php endif; ?>
        </div>

        <?php if ( $size ) : ?>
        <p class="cos-pdp-size-line"><?php echo esc_html( $size ); ?></p>
        <?php endif; ?>

        <!-- Short description -->
        <div class="cos-pdp-short-desc">
          <?php echo wpautop( wp_kses_post( $product->get_short_description() ) ); ?>
        </div>

        <?php if ( ! empty( $cos_pdp_benefits_lead ) ) : ?>
        <ul class="cos-pdp-benefits-list">
          <?php foreach ( $cos_pdp_benefits_lead as $benefit ) : ?>
          <li class="cos-pdp-benefit-item">
            <svg class="cos-pdp-benefit-ico" viewBox="0 0 14 14" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="2,7 5.5,11 12,3"/></svg>
            <?php echo esc_html( $benefit ); ?>
          </li>
          <?php endforeach; ?>
        </ul>
        <?php endif; ?>

        <?php if ( ! empty( $pd['stats'] ) ) :
          $cos_pdp_first_stat = $pd['stats'][0];
        ?>
        <div class="cos-pdp-evidence">
          <span class="cos-pdp-evidence-num"><?php echo esc_html( $cos_pdp_first_stat[0] ); ?></span>
          <span class="cos-pdp-evidence-label"><?php echo esc_html( $cos_pdp_first_stat[1] ); ?></span>
          <a href="/science/clinical-studies/" class="cos-pdp-evidence-link">View study methodology</a>
        </div>
        <?php endif; ?>

        <!-- Add to Cart -->
        <form class="cos-pdp-form" action="<?php echo esc_url( $product->add_to_cart_url() ); ?>" method="post">
          <div class="cos-pdp-qty-row">
            <span class="cos-pdp-qty-label">Qty</span>
            <div class="cos-pdp-qty-wrap">
              <button type="button" class="cos-pdp-qty-btn" onclick="cosQty(-1)" aria-label="Decrease quantity">−</button>
              <input id="cos-qty" class="cos-pdp-qty-input" type="number" name="quantity" value="1" min="1" max="<?php echo (int) COS_MAX_LINE_ITEM_QTY; ?>" aria-label="Quantity"/>
              <button type="button" class="cos-pdp-qty-btn" onclick="cosQty(1)" aria-label="Increase quantity">+</button>
            </div>
          </div>
          <input type="hidden" name="add-to-cart" value="<?php echo esc_attr( $product->get_id() ); ?>"/>
          <button type="submit" class="cos-pdp-atc-btn" id="cos-pdp-atc-btn" data-cro-cta="pdp-primary-atc">Add to Bag</button>
        </form>

        <!-- Trust signals: moved directly below the Add to Bag form (UI/UX
             pass 2026-09-19) so reassurance sits right at the point of
             purchase instead of after the bundle nudge/ritual link/who-for
             copy. Content unchanged, not duplicated -- only the position
             relative to the elements below it moved. -->
        <div class="cos-pdp-trust">
          <span class="cos-pdp-trust-item">
            <svg class="cos-pdp-trust-ico" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="10" cy="10" r="8"/><path d="M2 10h16"/><path d="M10 2a12 12 0 0 1 0 16M10 2a12 12 0 0 0 0 16"/></svg>
            <?php echo esc_html( cos_child_shipping_message( 'badge' ) ); ?>
          </span>
          <a href="/shipping-returns/" class="cos-pdp-trust-item">
            <svg class="cos-pdp-trust-ico" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M15.5 9A5.5 5.5 0 1 1 10 4"/><path d="M10 4V7.5l2.5 1.5"/><path d="M15 4h2v2"/></svg>
            30-Day Returns
          </a>
          <span class="cos-pdp-trust-item">
            <svg class="cos-pdp-trust-ico" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="9" width="14" height="9" rx="1"/><path d="M7 9V6a3 3 0 1 1 6 0v3"/></svg>
            <?php echo esc_html( $cos_pdp_payment_label ); ?>
          </span>
          <span class="cos-pdp-trust-item">
            <svg class="cos-pdp-trust-ico" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M10 2.5L3 5.7v5.1c0 3.8 2.9 6.8 7 7.4 4.1-.6 7-3.6 7-7.4V5.7L10 2.5z"/><path d="M7 10l2 2 4-4"/></svg>
            Authentic Swiss Formula
          </span>
        </div>

        <?php if ( $cos_pdp_has_clinical_proof ) : ?>
        <p class="cos-pdp-methodology-link"><a href="/science/clinical-studies/">See the study methodology &rarr;</a></p>
        <?php endif; ?>

        <?php if ( 'COS-BUNDLE' !== $sku ) : ?>
        <a href="/products/the-complete-ritual/" class="cos-pdp-bundle-nudge" data-cro-cta="pdp-bundle-nudge">Part of the 4-Step Ritual &mdash; save &euro;50 buying all four together</a>
        <?php endif; ?>

        <a href="/the-ritual/" class="cos-pdp-view-ritual" data-cro-cta="pdp-secondary-learn-ritual">Learn the Ritual</a>

        <?php if ( ! empty( $pd['who_for'] ) ) : ?>
        <p class="cos-pdp-who-mini"><strong>Who it's for:</strong> <?php echo esc_html( $pd['who_for'] ); ?></p>
        <?php endif; ?>

      </div>
    </div>

    <?php if ( count( $cos_pdp_jump_links ) > 1 ) : ?>
    <nav class="cos-pdp-jumpnav" aria-label="Jump to a section on this page">
      <?php foreach ( $cos_pdp_jump_links as $jump ) : ?>
      <a href="#<?php echo esc_attr( $jump['id'] ); ?>" class="cos-pdp-jump-link"><?php echo esc_html( $jump['label'] ); ?></a>
      <?php endforeach; ?>
    </nav>
    <?php endif; ?>

    <!-- Full description -->
    <?php if ( $desc ) : ?>
    <section class="cos-pdp-desc-section" id="pdp-description">
      <div class="cos-pdp-desc-inner">
        <?php echo wp_kses_post( wpautop( $desc ) ); ?>
        <?php if ( ! empty( $cos_pdp_benefits_more ) ) : ?>
        <div class="cos-pdp-texture">
          <h3>In Full</h3>
          <ul class="cos-pdp-benefits-list">
            <?php foreach ( $cos_pdp_benefits_more as $benefit ) : ?>
            <li class="cos-pdp-benefit-item">
              <svg class="cos-pdp-benefit-ico" viewBox="0 0 14 14" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="2,7 5.5,11 12,3"/></svg>
              <?php echo esc_html( $benefit ); ?>
            </li>
            <?php endforeach; ?>
          </ul>
        </div>
        <?php endif; ?>
        <?php if ( $pd && ! empty( $pd['texture'] ) ) : ?>
        <div class="cos-pdp-texture">
          <h3>Texture in the Ritual</h3>
          <p><?php echo esc_html( $pd['texture'] ); ?></p>
        </div>
        <?php endif; ?>
      </div>
    </section>
    <?php endif; ?>

    <!-- Who It Is For + Skin Concerns -->
    <?php if ( $pd && ! empty( $pd['who_for'] ) ) : ?>
    <section class="cos-pdp-who" id="pdp-who">
      <div class="cos-pdp-who-inner">
        <div class="cos-pdp-who-block">
          <p class="cos-pdp-section-eyebrow">Who It Is For</p>
          <p class="cos-pdp-who-text"><?php echo esc_html( $pd['who_for'] ); ?></p>
        </div>
        <?php if ( ! empty( $pd['concerns'] ) ) : ?>
        <div class="cos-pdp-who-block">
          <p class="cos-pdp-section-eyebrow">Skin Concerns</p>
          <div class="cos-pdp-concerns-tags">
            <?php
            // Links a concern tag to its dedicated /skin-concerns/ page where
            // one exists (keyword match on the concern label, not an exact
            // list, since wording varies slightly per product -- e.g. "Fine
            // lines around the eyes" vs "Fine lines & wrinkles"). Tags with
            // no matching page (e.g. "Daily impurities", "Environmental
            // stress") render as plain, unlinked tags -- never forced.
            $cos_concern_page_map = [
                '/fine line|wrinkle/i' => '/skin-concerns/fine-lines-wrinkles/',
                '/firmness/i'          => '/skin-concerns/loss-of-firmness/',
                '/dark circ/i'         => '/skin-concerns/dark-circles/',
                '/dry|dehydrat/i'      => '/skin-concerns/dry-dehydrated-skin/',
                '/dull/i'              => '/skin-concerns/dull-skin/',
            ];
            foreach ( $pd['concerns'] as $concern ) :
                $concern_url = null;
                foreach ( $cos_concern_page_map as $pattern => $url ) {
                    if ( preg_match( $pattern, $concern ) ) { $concern_url = $url; break; }
                }
            ?>
            <?php if ( $concern_url ) : ?>
            <a class="cos-pdp-concern-tag" href="<?php echo esc_url( $concern_url ); ?>"><?php echo esc_html( $concern ); ?></a>
            <?php else : ?>
            <span class="cos-pdp-concern-tag"><?php echo esc_html( $concern ); ?></span>
            <?php endif; ?>
            <?php endforeach; ?>
          </div>
        </div>
        <?php endif; ?>
      </div>
    </section>
    <?php endif; ?>


    <?php if ( ! empty( $pd['actives'] ) ) : ?>

    <!-- Key Actives -->
    <section class="cos-pdp-actives" id="pdp-actives">
      <div class="cos-pdp-actives-inner">
        <p class="cos-pdp-section-eyebrow">Active Ingredients</p>
        <h2 class="cos-pdp-section-title"><?php echo esc_html( $pd['actives_h2'] ?? 'The Caviar of Switzerland actives in this formula.' ); ?></h2>
        <div class="cos-pdp-actives-grid">
          <?php foreach ( $pd['actives'] as $ai => $active ) : ?>
          <div class="cos-pdp-active-card">
            <p class="cos-pdp-active-name"><?php echo esc_html( $active[0] ); ?></p>
            <p class="cos-pdp-active-desc"><?php echo esc_html( $active[1] ); ?></p>
            <span class="cos-pdp-active-bg-n" aria-hidden="true"><?php echo sprintf( '%02d', $ai + 1 ); ?></span>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </section>

    <!-- How to Apply -->
    <section class="cos-pdp-how" id="pdp-how">
      <div class="cos-pdp-how-inner">
        <p class="cos-pdp-section-eyebrow">Application</p>
        <h2 class="cos-pdp-section-title">Apply it within the four-step ritual.</h2>
        <div class="cos-pdp-how-steps">
          <?php foreach ( $pd['how'] as $i => $step_txt ) : ?>
          <div class="cos-pdp-how-step">
            <span class="cos-pdp-how-num"><?php echo sprintf( '%02d', $i + 1 ); ?></span>
            <p class="cos-pdp-how-text"><?php echo esc_html( $step_txt ); ?></p>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </section>

    <!-- Clinical Results -->
    <?php if ( ! empty( $pd['stats'] ) ) : ?>
    <section class="cos-pdp-clinical" id="pdp-clinical">
      <div class="cos-pdp-clinical-inner">
        <p class="cos-pdp-section-eyebrow">Clinical Proof</p>
        <h2 class="cos-pdp-section-title">Independent results. No claims without data.</h2>
        <div class="cos-pdp-stats">
          <?php foreach ( $pd['stats'] as $stat ) : ?>
          <div class="cos-pdp-stat">
            <span class="cos-pdp-stat-num"><?php echo esc_html( $stat[0] ); ?></span>
            <span class="cos-pdp-stat-label"><?php echo esc_html( $stat[1] ); ?></span>
          </div>
          <?php endforeach; ?>
        </div>
        <p class="cos-pdp-clinical-note">Results from independent third-party studies, 28 days &mdash; instrumental measurement or panellist self-report, as disclosed per statistic. <a href="/science/clinical-studies/" style="color:inherit;text-decoration:underline">See the study methodology &rarr;</a></p>
      </div>
    </section>
    <?php endif; ?>

    <!-- Full Ingredients -->
    <section class="cos-pdp-inci" id="pdp-ingredients">
      <div class="cos-pdp-inci-inner">
        <p class="cos-pdp-section-eyebrow">Transparency</p>
        <h2 class="cos-pdp-section-title">Full ingredient list.</h2>
        <details class="cos-pdp-inci-details">
          <summary class="cos-pdp-inci-toggle">
            <span class="cos-pdp-inci-toggle-label">View INCI List</span>
            <span class="cos-pdp-inci-toggle-icon">+</span>
          </summary>
          <div class="cos-pdp-inci-body">
            <p class="cos-pdp-inci-text"><?php echo esc_html( $pd['inci'] ); ?></p>
          </div>
        </details>
      </div>
    </section>

    <!-- Product Specifications -->
    <?php if ( $pd && ! empty( $pd['specs'] ) ) : ?>
    <section class="cos-pdp-specs" id="pdp-specs">
      <div class="cos-pdp-specs-inner">
        <p class="cos-pdp-section-eyebrow">Product Details</p>
        <h2 class="cos-pdp-section-title">Caviar of Switzerland product details.</h2>
        <table class="cos-pdp-specs-table">
          <?php foreach ( $pd['specs'] as $spec ) : ?>
          <tr>
            <td><?php echo esc_html( $spec[0] ); ?></td>
            <td><?php echo esc_html( $spec[1] ); ?></td>
          </tr>
          <?php endforeach; ?>
        </table>
        <div class="cos-pdp-clean-note">
          <p>Each Caviar of Switzerland formula is developed and made in Switzerland around the High-Tech Complex: Caviar Extract, SYN®-COLL, Hyaluronic Acid, Superoxide Dismutase, and Resveratrol. The complete ingredient list is published for transparency; refer to the product packaging for the most up-to-date information.</p>
        </div>
      </div>
    </section>
    <?php endif; ?>

    <!-- FAQ -->
    <?php if ( $pd && ! empty( $pd['faqs'] ) ) : ?>
    <section class="cos-pdp-faq" id="pdp-faq">
      <div class="cos-pdp-faq-inner">
        <p class="cos-pdp-section-eyebrow">Questions</p>
        <h2 class="cos-pdp-section-title">Questions about this formula.</h2>
        <?php foreach ( $pd['faqs'] as $faq ) : ?>
        <details class="cos-pdp-faq-item">
          <summary class="cos-pdp-faq-toggle">
            <span class="cos-pdp-faq-q"><?php echo esc_html( $faq[0] ); ?></span>
            <span class="cos-pdp-faq-icon">+</span>
          </summary>
          <p class="cos-pdp-faq-a"><?php echo esc_html( $faq[1] ); ?></p>
        </details>
        <?php endforeach; ?>
      </div>
    </section>
    <?php endif; ?>

    <?php endif; ?>

    <?php if ( 'COS-BUNDLE' === $sku ) :
      $ritual_products = array(
        array(
          'sku'      => 'COS-001',
          'slug'     => 'micellar-water-cleanser',
          'step'     => 'Step 01',
          'name'     => 'Micellar Water All-in-One Cleanser',
          'subtitle' => 'Cleanser · Make-up Remover · Toner',
          'desc'     => 'Removes make-up and daily impurities in one no-rinse step, preparing skin for the actives that follow.',
        ),
        array(
          'sku'      => 'COS-002',
          'slug'     => 'advanced-repair-serum',
          'step'     => 'Step 02',
          'name'     => 'Advanced Repair Serum',
          'subtitle' => 'The Flagship Serum',
          'desc'     => 'Concentrated actives including SYN®-COLL Peptide — +47% perceived firmness in a 28-day independent study.',
        ),
        array(
          'sku'      => 'COS-003',
          'slug'     => 'revitalizing-eye-cream',
          'step'     => 'Step 03',
          'name'     => 'Revitalizing Eye Cream',
          'subtitle' => 'Precision Eye Contour Care',
          'desc'     => 'Targets fine lines and dark circles around the eyes — −38% fine line depth at 28 days.',
        ),
        array(
          'sku'      => 'COS-004',
          'slug'     => '24h-regeneration-cream',
          'step'     => 'Step 04',
          'name'     => '24h Regeneration Cream',
          'subtitle' => 'Day-and-Night Caviar Moisturiser',
          'desc'     => 'Seals the ritual and sustains hydration, with 100% panellist satisfaction in our independent consumer panel study.',
        ),
      );
    ?>

    <!-- What's in the Ritual -->
    <section class="cos-pdp-ritual" id="pdp-ritual-contents">
      <div class="cos-pdp-ritual-inner">
        <p class="cos-pdp-section-eyebrow">What's Included</p>
        <h2 class="cos-pdp-section-title">Four products. Four roles. One ritual.</h2>
        <div class="cos-pdp-ritual-grid">
          <?php foreach ( $ritual_products as $rp ) :
            $rp_id     = wc_get_product_id_by_sku( $rp['sku'] );
            $rp_thumb_id = $rp_id ? (int) get_post_meta( $rp_id, '_thumbnail_id', true ) : 0;
            $rp_data   = $rp_thumb_id ? wp_get_attachment_image_src( $rp_thumb_id, 'medium' ) : false;
            $rp_srcset = $rp_thumb_id ? wp_get_attachment_image_srcset( $rp_thumb_id, 'medium' ) : false;
          ?>
          <a class="cos-pdp-ritual-card" href="/products/<?php echo esc_attr( $rp['slug'] ); ?>/">
            <div class="cos-pdp-ritual-img-wrap">
              <?php if ( $rp_data ) :
                $rp_sizes = $rp_srcset ? '(min-width: 768px) 25vw, 50vw' : '';
                $rp_html  = '<img class="cos-pdp-ritual-img" src="' . esc_url( $rp_data[0] ) . '"' . ( $rp_srcset ? ' srcset="' . esc_attr( $rp_srcset ) . '" sizes="' . esc_attr( $rp_sizes ) . '"' : '' ) . ' width="' . esc_attr( $rp_data[1] ) . '" height="' . esc_attr( $rp_data[2] ) . '" alt="" loading="lazy"/>';
                echo cos_child_nextgen_picture( $rp_data[0], $rp_srcset ?: '', $rp_sizes, $rp_html );
              endif; ?>
            </div>
            <div class="cos-pdp-ritual-body">
              <p class="cos-pdp-ritual-step"><?php echo esc_html( $rp['step'] ); ?></p>
              <p class="cos-pdp-ritual-name"><?php echo esc_html( $rp['name'] ); ?></p>
              <p class="cos-pdp-ritual-subtitle"><?php echo esc_html( $rp['subtitle'] ); ?></p>
              <p class="cos-pdp-ritual-desc"><?php echo esc_html( $rp['desc'] ); ?></p>
              <span class="cos-pdp-ritual-link">View product →</span>
            </div>
          </a>
          <?php endforeach; ?>
        </div>
      </div>
    </section>

    <!-- Bundle vs. Individual -->
    <?php
    $compare_rows  = array();
    $compare_total = 0;
    foreach ( $ritual_products as $rp ) {
      $rp_id    = wc_get_product_id_by_sku( $rp['sku'] );
      $rp_wc    = $rp_id ? wc_get_product( $rp_id ) : null;
      $rp_price = $rp_wc ? (float) $rp_wc->get_price() : 0;
      $compare_rows[] = array( $rp['name'], $rp_price );
      $compare_total += $rp_price;
    }
    $compare_bundle_price = (float) ( $sale ?: $price );
    $compare_savings      = $compare_total - $compare_bundle_price;
    ?>
    <section class="cos-pdp-compare" id="pdp-compare">
      <div class="cos-pdp-compare-inner">
        <p class="cos-pdp-section-eyebrow">Bundle vs. Individual</p>
        <h2 class="cos-pdp-section-title">What you'd pay buying separately.</h2>
        <table class="cos-pdp-compare-table">
          <?php foreach ( $compare_rows as $row ) : ?>
          <tr>
            <td><?php echo esc_html( $row[0] ); ?></td>
            <td><?php echo wp_kses_post( wc_price( $row[1] ) ); ?></td>
          </tr>
          <?php endforeach; ?>
          <tr class="cos-pdp-compare-total">
            <td>Buying separately</td>
            <td><?php echo wp_kses_post( wc_price( $compare_total ) ); ?></td>
          </tr>
          <tr class="cos-pdp-compare-bundle">
            <td>The Complete Ritual</td>
            <td><?php echo wp_kses_post( wc_price( $compare_bundle_price ) ); ?></td>
          </tr>
        </table>
        <?php if ( $compare_savings > 0 ) : ?>
        <div class="cos-pdp-compare-save">
          <span class="cos-pdp-compare-save-label">You Save</span>
          <span class="cos-pdp-compare-save-amt"><?php echo wp_kses_post( wc_price( $compare_savings ) ); ?></span>
        </div>
        <?php endif; ?>
      </div>
    </section>

    <!-- Visual break: gift presentation -->
    <?php
    $visual_break_data   = wp_get_attachment_image_src( 23719, 'large' );
    $visual_break_srcset = wp_get_attachment_image_srcset( 23719, 'large' );
    ?>
    <?php if ( $visual_break_data ) :
      $visual_break_sizes = $visual_break_srcset ? '100vw' : '';
      $visual_break_html  = '<img class="cos-pdp-visual-break-img" src="' . esc_url( $visual_break_data[0] ) . '"' . ( $visual_break_srcset ? ' srcset="' . esc_attr( $visual_break_srcset ) . '" sizes="' . esc_attr( $visual_break_sizes ) . '"' : '' ) . ' width="' . esc_attr( $visual_break_data[1] ) . '" height="' . esc_attr( $visual_break_data[2] ) . '" loading="lazy" alt="Caviar of Switzerland The Complete Ritual gift box presented in hand"/>';
    ?>
    <section class="cos-pdp-visual-break">
      <?php echo cos_child_nextgen_picture( $visual_break_data[0], $visual_break_srcset ?: '', $visual_break_sizes, $visual_break_html ); ?>
      <div class="cos-pdp-visual-break-overlay"></div>
      <div class="cos-pdp-visual-break-cap">
        <p class="cos-pdp-visual-break-eyebrow">Ready to Give</p>
        <p class="cos-pdp-visual-break-text">Presented in the Caviar of Switzerland ritual box, with a written ritual guide included.</p>
      </div>
    </section>
    <?php endif; ?>

    <!-- Why They Work in Synergy -->
    <?php
    $synergy_data   = wp_get_attachment_image_src( 23721, 'large' );
    $synergy_srcset = wp_get_attachment_image_srcset( 23721, 'large' );
    ?>
    <section class="cos-pdp-synergy" id="pdp-synergy">
      <div class="cos-pdp-synergy-grid">
        <div class="cos-pdp-synergy-img-col">
          <?php if ( $synergy_data ) :
            $synergy_sizes = $synergy_srcset ? '(min-width: 768px) 50vw, 100vw' : '';
            $synergy_html  = '<img class="cos-pdp-synergy-img" src="' . esc_url( $synergy_data[0] ) . '"' . ( $synergy_srcset ? ' srcset="' . esc_attr( $synergy_srcset ) . '" sizes="' . esc_attr( $synergy_sizes ) . '"' : '' ) . ' width="' . esc_attr( $synergy_data[1] ) . '" height="' . esc_attr( $synergy_data[2] ) . '" alt="Caviar of Switzerland cream texture, close-up" loading="lazy"/>';
            echo cos_child_nextgen_picture( $synergy_data[0], $synergy_srcset ?: '', $synergy_sizes, $synergy_html );
          endif; ?>
        </div>
        <div class="cos-pdp-synergy-text-col">
        <div class="cos-pdp-synergy-inner">
        <p class="cos-pdp-section-eyebrow">The System</p>
        <h2 class="cos-pdp-section-title">Why the four steps work in synergy.</h2>
        <p class="cos-pdp-synergy-lede">These aren't four unrelated products sold together for convenience. Each one is formulated to hand off to the next — and all four share the same active foundation, so the effects compound rather than compete.</p>
        <div class="cos-pdp-synergy-flow">
          <div class="cos-pdp-synergy-step">
            <span class="cos-pdp-synergy-arrow">01</span>
            <p class="cos-pdp-synergy-text"><strong>Micellar Water</strong> clears make-up and daily impurities without stripping the skin, leaving it clean and receptive — so the serum's actives aren't fighting residue to get in.</p>
          </div>
          <div class="cos-pdp-synergy-step">
            <span class="cos-pdp-synergy-arrow">02</span>
            <p class="cos-pdp-synergy-text"><strong>Advanced Repair Serum</strong> delivers concentrated, micro-encapsulated actives into freshly cleansed skin, then absorbs quickly so nothing is left sitting on the surface when the next step is applied.</p>
          </div>
          <div class="cos-pdp-synergy-step">
            <span class="cos-pdp-synergy-arrow">03</span>
            <p class="cos-pdp-synergy-text"><strong>Revitalizing Eye Cream</strong> treats the eye contour specifically — skin too thin and delicate for the serum or moisturiser to be formulated for — closing the one gap a two-step routine leaves open.</p>
          </div>
          <div class="cos-pdp-synergy-step">
            <span class="cos-pdp-synergy-arrow">04</span>
            <p class="cos-pdp-synergy-text"><strong>24h Regeneration Cream</strong> is the sealing step — it locks in everything applied before it, whether worn under SPF by day or left to work overnight.</p>
          </div>
        </div>
        <div class="cos-pdp-synergy-note">
          <p>Every formula draws from the same Caviar of Switzerland High-Tech Complex — Caviar Extract, SYN®-COLL, Hyaluronic Acid, Superoxide Dismutase, and Resveratrol — with each step formulated around the specific actives suited to its role, not the full complex uniformly. See each product's ingredient list for exactly which actives it contains.</p>
        </div>
        </div>
        </div>
      </div>
    </section>

    <!-- Presentation -->
    <?php
    $presentation_items = array(
      array( 'id' => 23717, 'cap' => 'The closed ritual box' ),
      array( 'id' => 23720, 'cap' => 'Packaging detail' ),
      array( 'id' => 23722, 'cap' => 'True-to-size scale' ),
    );
    ?>
    <section class="cos-pdp-presentation">
      <div class="cos-pdp-presentation-inner">
        <p class="cos-pdp-section-eyebrow">Presentation</p>
        <h2 class="cos-pdp-section-title">Considered down to the box.</h2>
        <p class="cos-pdp-presentation-lede">The set arrives in the Caviar of Switzerland ritual box — a magnetic-close case built to protect the four products in transit and to be kept afterwards.</p>
        <div class="cos-pdp-presentation-grid">
          <?php foreach ( $presentation_items as $pi ) :
            $pi_data   = wp_get_attachment_image_src( $pi['id'], 'large' );
            if ( ! $pi_data ) continue;
            $pi_srcset = wp_get_attachment_image_srcset( $pi['id'], 'large' );
          ?>
          <?php
          $pi_sizes = $pi_srcset ? '(min-width: 768px) 33vw, 100vw' : '';
          $pi_html  = '<img src="' . esc_url( $pi_data[0] ) . '"' . ( $pi_srcset ? ' srcset="' . esc_attr( $pi_srcset ) . '" sizes="' . esc_attr( $pi_sizes ) . '"' : '' ) . ' width="' . esc_attr( $pi_data[1] ) . '" height="' . esc_attr( $pi_data[2] ) . '" alt="' . esc_attr( $pi['cap'] ) . '" loading="lazy"/>';
          ?>
          <div class="cos-pdp-presentation-item">
            <?php echo cos_child_nextgen_picture( $pi_data[0], $pi_srcset ?: '', $pi_sizes, $pi_html ); ?>
            <div class="cos-pdp-presentation-cap"><?php echo esc_html( $pi['cap'] ); ?></div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </section>

    <!-- Combined Clinical Results -->
    <section class="cos-pdp-clinical" id="pdp-clinical">
      <div class="cos-pdp-clinical-inner">
        <p class="cos-pdp-section-eyebrow">Clinical Proof</p>
        <h2 class="cos-pdp-section-title">Independent results across the three treatment formulas.</h2>
        <div class="cos-pdp-stats cos-pdp-stats-4">
          <div class="cos-pdp-stat"><span class="cos-pdp-stat-num">+47%</span><span class="cos-pdp-stat-label">Perceived firmness at 28 days — Advanced Repair Serum</span></div>
          <div class="cos-pdp-stat"><span class="cos-pdp-stat-num">−38%</span><span class="cos-pdp-stat-label">Fine line depth reduction — Revitalizing Eye Cream</span></div>
          <div class="cos-pdp-stat"><span class="cos-pdp-stat-num">100%</span><span class="cos-pdp-stat-label">Panellist satisfaction at 28 days — 24h Regeneration Cream</span></div>
        </div>
        <p class="cos-pdp-clinical-note">Results from independent third-party studies, per product, over 28 days &mdash; instrumental measurement or panellist self-report, as disclosed per statistic. <a href="/science/clinical-studies/" style="color:inherit;text-decoration:underline">See the study methodology &rarr;</a></p>
      </div>
    </section>

    <!-- Bundle FAQ -->
    <section class="cos-pdp-faq" id="pdp-faq">
      <div class="cos-pdp-faq-inner">
        <p class="cos-pdp-section-eyebrow">Questions</p>
        <h2 class="cos-pdp-section-title">Questions about The Complete Ritual.</h2>
        <?php
        $bundle_faqs = array(
          array( 'Do I have to use all four products together?', 'Each product works on its own, but they\'re formulated to work in sequence — the cleanser prepares skin for the serum\'s actives, the eye cream covers what the serum and moisturiser aren\'t formulated to treat, and the 24h cream seals the routine. The clinical results for each product were achieved using that product as directed within the full ritual.' ),
          array( 'What order do I apply them in?', 'Morning and evening, in this order: Micellar Water Cleanser, then Advanced Repair Serum, then Revitalizing Eye Cream, then 24h Regeneration Cream. A written ritual guide is included in the box.' ),
          array( 'Is this a good gift?', 'Yes — the set is presented in the Caviar of Switzerland ritual box with a written ritual guide, ready to give.' ),
          array( 'What if I only need one or two steps?', 'The Complete Ritual is built for anyone starting the full routine and saving €50 versus buying separately. Each product is also sold individually if you only need certain steps.' ),
          array( 'What is the Caviar of Switzerland High-Tech Complex?', 'The High-Tech Complex is the signature active blend of Caviar Extract, SYN®-COLL, Hyaluronic Acid, Superoxide Dismutase, and Resveratrol — chosen to help support hydration, visible firmness, antioxidant care, and a more youthful-looking complexion. It\'s present across all four products in this set.' ),
          array( 'How long will the set last?', 'With twice-daily use at the recommended amounts, the serum, eye cream and 24h cream each last approximately 6–8 weeks, and the cleanser 8–10 weeks.' ),
        );
        foreach ( $bundle_faqs as $faq ) : ?>
        <details class="cos-pdp-faq-item">
          <summary class="cos-pdp-faq-toggle">
            <span class="cos-pdp-faq-q"><?php echo esc_html( $faq[0] ); ?></span>
            <span class="cos-pdp-faq-icon">+</span>
          </summary>
          <p class="cos-pdp-faq-a"><?php echo esc_html( $faq[1] ); ?></p>
        </details>
        <?php endforeach; ?>
      </div>
    </section>

    <?php endif; ?>

  </div>
</main>

<?php if ( $product->is_in_stock() ) : ?>
<div id="cos-pdp-sticky-atc" class="cos-pdp-sticky-atc" aria-hidden="true">
  <div class="cos-pdp-sticky-info">
    <span class="cos-pdp-sticky-name"><?php echo esc_html( $name ); ?></span>
    <span class="cos-pdp-sticky-price"><?php echo wp_kses_post( wc_price( $sale ?: $price ) ); ?></span>
  </div>
  <a href="<?php echo esc_url( $product->add_to_cart_url() ); ?>" class="cos-pdp-sticky-btn add_to_cart_button ajax_add_to_cart" data-product_id="<?php echo esc_attr( $product->get_id() ); ?>" data-quantity="1" data-cro-cta="pdp-sticky-atc" rel="nofollow">Add to Bag</a>
</div>
<?php endif; ?>

<script nonce="<?php echo esc_attr( cos_csp_nonce() ); ?>">
function cosQty(delta){
  var inp=document.getElementById('cos-qty');
  var v=parseInt(inp.value,10)||1;
  var max=parseInt(inp.getAttribute('max'),10)||<?php echo (int) COS_MAX_LINE_ITEM_QTY; ?>;
  v=Math.max(1,Math.min(max,v+delta));
  inp.value=v;
}
var cosSrcs=<?php echo $srcs_json; ?>;
var cosVideoIndex=<?php echo $cos_video_url ? (int) count( $gallery_ids ) : -1; ?>;
function cosGallerySet(i){
  var main=document.getElementById('cos-gallery-main');
  var video=document.getElementById('cos-gallery-video');
  if (i===cosVideoIndex && video) {
    main.style.display='none';
    video.style.display='block';
    if (!video.src && video.dataset.src) { video.src = video.dataset.src; }
    video.play().catch(function(){});
  } else {
    if (video) { video.pause(); video.style.display='none'; }
    main.style.display='block';
    main.style.opacity='0';
    setTimeout(function(){
      // main still carries the FIRST photo's srcset/sizes from initial render.
      // Per spec, srcset wins over a plain src whenever it has a matching
      // candidate — which it always does, since it's a valid (stale) srcset —
      // so setting src alone silently kept rendering photo 1 regardless of i.
      // Clearing srcset/sizes makes the freshly-set src actually take effect.
      main.removeAttribute('srcset');
      main.removeAttribute('sizes');
      main.src=cosSrcs[i];
      main.style.opacity='1';
    },240);
  }
  document.querySelectorAll('.cos-pdp-thumb').forEach(function(d,idx){
    d.classList.toggle('is-active', idx===i);
  });
  document.querySelectorAll('.cos-pdp-dot').forEach(function(d,idx){
    d.classList.toggle('is-active', idx===i);
  });
}

(function () {
  var stickyBar = document.getElementById('cos-pdp-sticky-atc');
  var primaryBtn = document.getElementById('cos-pdp-atc-btn');
  if (!stickyBar || !primaryBtn || !('IntersectionObserver' in window)) return;
  var stickyLink = stickyBar.querySelector('a, button');
  var navHeight = parseInt(getComputedStyle(document.documentElement).getPropertyValue('--cos-nav-h')) || 80;
  // Bug fix (UI/UX pass 2026-09-19): the primary CTA starts below the fold on
  // mobile (tall hero image above it), so on initial load isIntersecting is
  // already false -- the old `!visible` logic showed the sticky bar
  // immediately, before the visitor had even scrolled to the real button.
  // boundingClientRect.top distinguishes "hasn't been reached yet" (a large
  // positive top, still below the viewport) from "scrolled past" (top has
  // gone above the sticky nav) -- only the second case should reveal the bar.
  var observer = new IntersectionObserver(function (entries) {
    var entry = entries[0];
    var show = !entry.isIntersecting && entry.boundingClientRect.top < navHeight;
    stickyBar.classList.toggle('is-visible', show);
    stickyBar.setAttribute('aria-hidden', show ? 'false' : 'true');
    // axe-core regression caught while fixing the above: with aria-hidden
    // now genuinely "true" while hidden (it was effectively always "false"
    // under the old bug, which masked this), the real <a>/<button> inside
    // still stayed keyboard-focusable -- an aria-hidden ancestor must never
    // contain focusable content (WCAG/axe rule aria-hidden-focus). Toggling
    // tabindex in lockstep keeps it out of the tab order while hidden.
    if (stickyLink) stickyLink.setAttribute('tabindex', show ? '0' : '-1');
  }, { rootMargin: '-' + navHeight + 'px 0px 0px 0px', threshold: 0 });
  observer.observe(primaryBtn);
})();

jQuery(function ($) {
  var stickyBtn = $('#cos-pdp-sticky-atc .cos-pdp-sticky-btn');
  $(document.body).on('adding_to_cart', function (e, btn) {
    if (btn && btn.is(stickyBtn)) stickyBtn.text('Adding…');
  });
  $(document.body).on('added_to_cart', function (e, fragments, hash, btn) {
    if (!btn || !btn.is(stickyBtn)) return;
    stickyBtn.text('Added ✓');
    setTimeout(function () { stickyBtn.text('Add to Bag'); }, 1800);
  });
});
</script>

<?php
$cos_view_item = array(
	'item_id'   => $sku ?: (string) $product->get_id(),
	'item_name' => $name,
	'price'     => (float) wc_get_price_to_display( $product ),
);
?>
<script nonce="<?php echo esc_attr( cos_csp_nonce() ); ?>">
document.addEventListener('DOMContentLoaded', function () {
  if (window.cos && cos.track) {
    var item = <?php echo wp_json_encode( $cos_view_item ); ?>;
    cos.track('view_item', { currency: 'EUR', value: item.price, items: [item] });
  }
});
</script>

<?php wp_footer(); ?>
</body>
</html>
