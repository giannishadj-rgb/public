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

	// Payment-method summary — reflects whichever gateways are switched on in
	// WooCommerce settings rather than an unverifiable generic claim. Reads the
	// configured `enabled` flag, not get_available_payment_gateways(): the
	// latter is filtered per session/cart, so a fresh visitor (or an audit
	// tool) could get "Contact Us to Order" while a shopper with a bag got
	// "Pay on Delivery" -- the same page announcing two different messages.
	$cos_pdp_gateway_ids = array();
	if ( function_exists( 'WC' ) && WC()->payment_gateways() ) {
		foreach ( WC()->payment_gateways()->payment_gateways() as $cos_gw_id => $cos_gw ) {
			if ( 'yes' === $cos_gw->enabled ) $cos_pdp_gateway_ids[] = $cos_gw_id;
		}
	}
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
	// Optional per-product PDP copy overrides (inc/product-data.php): a
	// shorter purchase-panel proposition and a consolidated description.
	if ( ! empty( $pd['description'] ) ) $desc = $pd['description'];
	// "30ml" -> "30 ml" (SI spacing) for the price line and sticky bar.
	$cos_pdp_size_label = $size ? preg_replace( '/(\d)\s*(ml|g|oz)\b/i', '$1 $2', $size ) : '';

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
	// Editorial redesign (2026-09-20): the bundle page no longer has a section-
	// navigation bar at all (see DESIGN INTENT — fewer, larger elements; the
	// page now reads top to bottom as one narrative rather than a set of
	// independently-jumpable sections), so it needs no jump-link array of its
	// own. $cos_pdp_jump_links is left at whatever the loop above computed for
	// a normal product, but that value is never read on the bundle branch
	// below, only in the individual-product (else) branch further down.
endwhile;

// Opt-in product video slide, appended after the image gallery. Only set on
// The Complete Ritual right now (_cos_pdp_video_url postmeta) — every other
// product has no meta here and falls back to the plain image-only gallery
// exactly as before.
$cos_video_url = get_post_meta( $product->get_id(), '_cos_pdp_video_url', true );
?>
<style nonce="<?php echo esc_attr( cos_csp_nonce() ); ?>">
#cos-pdp{font-family:var(--cos-body);color:var(--cos-ink,#111111);background:var(--cos-pearl,#F2F2EF)}
#cos-pdp *{box-sizing:border-box}
#cos-pdp h1,#cos-pdp h2,#cos-pdp h3,#cos-pdp h4,#cos-pdp p,#cos-pdp ul,#cos-pdp ol,#cos-pdp li,#cos-pdp figure,#cos-pdp blockquote{margin:0;padding:0}

/* ── Hero ── */
.cos-pdp-hero{display:grid;grid-template-columns:1fr;align-items:stretch}
.cos-pdp-img-col{display:flex;flex-direction:column;background:var(--cos-gallery-bg,#E8E8E5)}
.cos-pdp-gallery-main{position:relative;flex:1;min-height:420px;display:flex;align-items:center;justify-content:center;overflow:hidden}
.cos-pdp-gallery-main img{position:absolute;inset:0;width:100%;height:100%;object-fit:cover;display:block;transition:opacity .3s}
.cos-pdp-gallery-main video{position:absolute;inset:0;width:100%;height:100%;object-fit:cover;display:block}
.cos-pdp-thumb-play{position:absolute;inset:0;display:flex;align-items:center;justify-content:center;background:rgba(5, 5, 5,.28)}
.cos-pdp-thumb-play svg{width:16px;height:16px;fill:#fff}

/* ── Thumbnail strip ── */
.cos-pdp-thumbstrip{display:none}
.cos-pdp-thumbstrip::-webkit-scrollbar{display:none}
.cos-pdp-thumb{width:68px;height:68px;flex-shrink:0;cursor:pointer;border:2px solid transparent;overflow:hidden;opacity:.45;transition:opacity .25s,border-color .25s;background:var(--cos-gallery-bg,#E8E8E5)}
.cos-pdp-thumb img{width:100%;height:100%;object-fit:cover;display:block}
.cos-pdp-thumb.is-active{opacity:1;border-color:var(--cos-ink,#111111)}
.cos-pdp-thumb:hover:not(.is-active){opacity:.8}

/* ── Dot navigation ── */
.cos-pdp-dots{display:flex;align-items:center;justify-content:center;gap:4px;padding:14px 0;background:rgba(229,222,212,.5);border-top:1px solid rgba(196,202,209,.15)}
/* Visual dot stays 6px (::before) — the button itself is a 44x44 CSS-pixel
   tap target (this project's preferred bar for primary/frequently-used
   controls, not just the 24px WCAG floor) so the hit area doesn't shrink
   with it. */
.cos-pdp-dot{width:44px;height:44px;min-width:44px;border-radius:50%;background:transparent;border:none;padding:0;margin:0;cursor:pointer;transition:background .2s;flex-shrink:0;display:flex;align-items:center;justify-content:center;position:relative}
.cos-pdp-dot::before{content:'';width:6px;height:6px;border-radius:50%;background:rgba(92,102,117,.3);transition:background .2s,transform .2s}
.cos-pdp-dot.is-active::before{background:var(--cos-ink,#111111);transform:scale(1.5)}
.cos-pdp-dot:hover:not(.is-active)::before{background:rgba(92,102,117,.6)}

/* ── Info col ── */
.cos-pdp-info{display:flex;flex-direction:column;padding:48px 24px;background:var(--cos-pearl,#F2F2EF)}

/* ── Header block (step row + title + subtitle) ── */
.cos-pdp-header-block{margin-bottom:28px;padding-bottom:28px;border-bottom:1px solid var(--cos-stone,#E5DED4)}

/* ── Step row (pill + inline progress track) ── */
.cos-pdp-step-row{display:flex;align-items:center;gap:12px;margin-bottom:18px}
.cos-pdp-step-circle{width:28px;height:28px;border:1.5px solid var(--cos-gold,#8B692F);border-radius:50%;display:flex;align-items:center;justify-content:center;font-family:var(--cos-display);font-style:italic;font-weight:300;font-size:13px;color:var(--cos-gold,#8B692F);flex-shrink:0;line-height:1}
.cos-pdp-step-str{font-size:10px;font-weight:600;letter-spacing:.28em;text-transform:uppercase;color:var(--cos-gold,#8B692F);white-space:nowrap;flex-shrink:0}
@media(max-width:767px){.cos-pdp-step-str{letter-spacing:.18em}}

.cos-pdp-name{font-family:var(--cos-display);font-style:italic;font-weight:300;font-size:clamp(26px,3.5vw,42px);color:var(--cos-ink);line-height:1.1;margin-bottom:10px!important}

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
.cos-pdp-size-line{font-size:11px;font-weight:400;color:var(--cos-slate,#5C6675);letter-spacing:.04em;margin-bottom:32px!important;display:flex;align-items:center;gap:6px}
.cos-pdp-size-line::before{content:'';display:inline-block;width:3px;height:3px;background:var(--cos-gold-l,#B79A5B);flex-shrink:0}

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
.cos-pdp-atc-btn{padding:18px 36px;min-height:44px;box-sizing:border-box;background:var(--cos-navy,#050505);color:#fff;font-size:10px;font-weight:600;letter-spacing:.2em;text-transform:uppercase;border:none;cursor:pointer;transition:background .2s;font-family:var(--cos-body);text-align:center;display:block;width:100%}
.cos-pdp-atc-btn:hover{background:var(--cos-ink,#111111)}
.cos-pdp-view-ritual{display:block;text-align:center;padding:13px 36px;border:1px solid var(--cos-stone,#E5DED4);color:var(--cos-slate);font-size:10px;font-weight:600;letter-spacing:.2em;text-transform:uppercase;text-decoration:none;transition:border-color .2s,color .2s;margin-top:20px;margin-bottom:20px}
.cos-pdp-view-ritual:hover{border-color:var(--cos-navy);color:var(--cos-navy)}

.cos-pdp-bundle-nudge{display:block;text-align:center;font-size:11px;font-weight:500;color:var(--cos-teal,#46656B);text-decoration:underline;text-underline-offset:2px;margin-top:20px;margin-bottom:20px}
.cos-pdp-bundle-nudge:hover{color:var(--cos-navy,#050505)}

.cos-pdp-who-mini{font-size:12px;font-weight:300;color:var(--cos-slate,#5C6675);line-height:1.65;margin-bottom:24px!important}
.cos-pdp-who-mini strong{font-weight:600;color:var(--cos-ink)}

/* ── Trust grid with SVG icons ── */
.cos-pdp-trust{display:grid;grid-template-columns:1fr 1fr;gap:14px 20px;margin-top:24px;padding-top:20px;border-top:1px solid var(--cos-stone,#E5DED4)}
.cos-pdp-trust-item{font-size:11px;font-weight:500;letter-spacing:.08em;text-transform:uppercase;color:var(--cos-slate,#5C6675);display:flex;align-items:center;gap:7px;line-height:1.4;text-decoration:none}
a.cos-pdp-trust-item:hover{color:var(--cos-navy,#050505)}
.cos-pdp-trust-ico{width:13px;height:13px;flex-shrink:0;color:var(--cos-gold-l,#B79A5B)}

.cos-pdp-methodology-link{font-size:10px;font-weight:500;letter-spacing:.04em;color:var(--cos-slate,#5C6675);text-align:center;margin-top:16px!important}
.cos-pdp-methodology-link a{color:inherit;text-decoration:underline;text-underline-offset:2px}

/* ── Sticky mobile Add to Bag bar ── */
.cos-pdp-sticky-atc{position:fixed;left:0;right:0;bottom:0;z-index:40;display:flex;align-items:center;gap:12px;background:#fff;border-top:1px solid var(--cos-stone,#E5DED4);box-shadow:0 -4px 16px rgba(5, 5, 5,.08);padding:10px 16px calc(10px + env(safe-area-inset-bottom));transform:translateY(100%);transition:transform .25s ease;will-change:transform}
.cos-pdp-sticky-atc.is-visible{transform:translateY(0)}
.cos-pdp-sticky-info{flex:1;min-width:0}
.cos-pdp-sticky-name{display:block;font-size:11px;font-weight:600;color:var(--cos-ink);white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.cos-pdp-sticky-price{display:block;font-size:12px;font-weight:300;color:var(--cos-slate,#5C6675);font-variant-numeric:tabular-nums}
.cos-pdp-sticky-btn{flex-shrink:0;padding:0 24px;min-height:44px;display:flex;align-items:center;justify-content:center;background:var(--cos-navy,#050505);color:#fff;font-size:10px;font-weight:600;letter-spacing:.16em;text-transform:uppercase;text-decoration:none;border:none;cursor:pointer;transition:background .2s}
.cos-pdp-sticky-btn:hover{background:var(--cos-ink,#111111)}
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
.cos-pdp-jump-link:hover{border-color:var(--cos-navy,#050505);color:var(--cos-navy,#050505)}

/* ── Description ── */
.cos-pdp-desc-section{background:var(--cos-pearl,#F2F2EF);border-top:1px solid var(--cos-stone,#E5DED4);padding:72px 24px}
.cos-pdp-desc-inner{max-width:760px;margin:0 auto}
.cos-pdp-desc-inner h2{font-family:var(--cos-display);font-style:italic;font-weight:300;font-size:clamp(22px,2.5vw,34px);color:var(--cos-ink);margin-bottom:24px;line-height:1.2}
.cos-pdp-desc-inner h3{font-family:var(--cos-body);font-size:10px;font-weight:600;letter-spacing:.22em;text-transform:uppercase;color:var(--cos-gold,#8B692F);margin-top:32px;margin-bottom:12px}
.cos-pdp-desc-inner p{font-size:14px;font-weight:300;color:var(--cos-slate,#5C6675);line-height:1.85;margin-bottom:16px}
.cos-pdp-desc-inner ul{padding-left:18px;list-style:disc;margin-bottom:14px}
.cos-pdp-desc-inner li{font-size:14px;font-weight:300;color:var(--cos-slate);line-height:1.7;margin-bottom:6px}

/* ── Key Actives ── */
.cos-pdp-actives{background:var(--cos-pearl,#F2F2EF);padding:72px 24px}
.cos-pdp-actives-inner{max-width:1100px;margin:0 auto}
.cos-pdp-section-eyebrow{font-size:10px;font-weight:600;letter-spacing:.28em;text-transform:uppercase;color:var(--cos-gold,#8B692F);margin-bottom:12px!important}
@media(max-width:767px){.cos-pdp-section-eyebrow{letter-spacing:.18em}}
.cos-pdp-section-title{font-family:var(--cos-display);font-style:italic;font-weight:300;font-size:clamp(22px,2.5vw,34px);color:var(--cos-ink);margin-bottom:40px!important;line-height:1.2}
.cos-pdp-actives-grid{display:grid;grid-template-columns:1fr 1fr;gap:2px}
.cos-pdp-active-card{background:#fff;padding:36px 32px 40px;border-top:2px solid var(--cos-gold,#8B692F);position:relative;overflow:hidden}
.cos-pdp-active-name{font-size:11px;font-weight:600;letter-spacing:.14em;text-transform:uppercase;color:var(--cos-ink);margin-bottom:12px!important;position:relative;z-index:1}
.cos-pdp-active-desc{font-size:13px;font-weight:300;color:var(--cos-slate,#5C6675);line-height:1.8;position:relative;z-index:1}
/* Decorative numeral via generated content (::after) so it isn't text
   that contrast checkers score -- it's intentionally faint. */
.cos-pdp-active-bg-n::after{content:attr(data-n)}
.cos-pdp-active-bg-n{position:absolute;bottom:-20px;right:12px;font-family:var(--cos-display);font-style:italic;font-size:96px;font-weight:300;color:rgba(229,222,212,.55);line-height:1;pointer-events:none;user-select:none;z-index:0;letter-spacing:-.02em}

/* ── How to Apply ── */
.cos-pdp-how{background:var(--cos-pearl,#F2F2EF);padding:72px 24px;border-top:1px solid var(--cos-stone,#E5DED4)}
.cos-pdp-how-inner{max-width:760px;margin:0 auto}
.cos-pdp-how-steps{position:relative;display:flex;flex-direction:column;gap:0;margin-top:8px}
.cos-pdp-how-steps::before{content:'';position:absolute;left:19px;top:4px;bottom:4px;width:1px;background:linear-gradient(to bottom,transparent 0%,var(--cos-stone,#E5DED4) 8%,var(--cos-stone,#E5DED4) 92%,transparent 100%);z-index:0}
.cos-pdp-how-step{display:grid;grid-template-columns:40px 1fr;gap:16px;padding:20px 0;border-bottom:1px solid var(--cos-stone,#E5DED4);align-items:start;position:relative;z-index:1}
.cos-pdp-how-step:last-child{border-bottom:none}
.cos-pdp-how-num{font-family:var(--cos-display);font-style:italic;font-weight:300;font-size:22px;color:var(--cos-gold,#8B692F);line-height:1;background:var(--cos-pearl,#F2F2EF);position:relative;z-index:2;padding:2px 0}
.cos-pdp-how-text{font-size:14px;font-weight:300;color:var(--cos-slate,#5C6675);line-height:1.8;padding-top:2px}

/* ── Clinical Results ── */
.cos-pdp-clinical{background:var(--cos-navy,#050505);padding:72px 24px}
.cos-pdp-clinical-inner{max-width:1100px;margin:0 auto}
.cos-pdp-clinical .cos-pdp-section-eyebrow{color:var(--cos-gold-l,#B79A5B)}
.cos-pdp-clinical .cos-pdp-section-title{color:#fff;margin-bottom:48px!important}
.cos-pdp-stats{display:grid;grid-template-columns:repeat(3,1fr);gap:1px;background:rgba(255,255,255,.06)}
.cos-pdp-stats-4{grid-template-columns:repeat(2,1fr)}
.cos-pdp-stat{background:var(--cos-navy,#050505);padding:44px 28px;text-align:center;position:relative}
.cos-pdp-stat::before{content:'';position:absolute;top:0;left:50%;transform:translateX(-50%);width:30px;height:2px;background:var(--cos-gold-l,#B79A5B);opacity:.5}
.cos-pdp-stat-num{font-family:var(--cos-display);font-style:italic;font-weight:300;font-size:clamp(36px,4vw,58px);color:#fff;line-height:1;margin-bottom:14px;display:block}
.cos-pdp-stat-label{font-size:11px;font-weight:300;color:rgba(255,255,255,.55);line-height:1.7;letter-spacing:.04em}
.cos-pdp-clinical-note{font-size:10px;font-weight:300;color:rgba(255,255,255,.6);text-align:center;margin-top:32px!important;letter-spacing:.06em;text-transform:uppercase}

/* ── Full Ingredients ── */
.cos-pdp-inci{background:var(--cos-pearl,#F2F2EF);padding:72px 24px;border-top:1px solid var(--cos-stone,#E5DED4)}
.cos-pdp-inci-inner{max-width:760px;margin:0 auto}
.cos-pdp-inci-inner > .cos-pdp-section-title{margin-bottom:24px!important}
.cos-pdp-inci-details{border:1px solid var(--cos-stone,#E5DED4)}
.cos-pdp-inci-toggle{display:flex;justify-content:space-between;align-items:center;cursor:pointer;padding:18px 24px;list-style:none;user-select:none;background:#fff;transition:background .15s}
.cos-pdp-inci-toggle::-webkit-details-marker{display:none}
.cos-pdp-inci-toggle:hover{background:var(--cos-stone,#E5DED4)}
.cos-pdp-inci-toggle-label{font-size:10px;font-weight:600;letter-spacing:.18em;text-transform:uppercase;color:var(--cos-ink)}
.cos-pdp-inci-toggle-icon{font-size:18px;font-weight:300;color:var(--cos-slate);transition:transform .25s;display:inline-block;line-height:1}
details[open] .cos-pdp-inci-toggle-icon{transform:rotate(45deg)}
.cos-pdp-inci-body{padding:20px 24px;border-top:1px solid var(--cos-stone,#E5DED4)}
.cos-pdp-inci-text{font-size:12px;font-weight:300;color:var(--cos-slate,#5C6675);line-height:1.9;letter-spacing:.02em}

/* ── Bundle: Value Moment (editorial redesign, 2026-09-20) — a quiet
   typographic interlude between sections, replacing the old standalone
   comparison table/dark-save-box. Three short lines, centered, generous
   space around them; no table, no navy block, no border. ── */
.cos-pdp-value{background:var(--cos-pearl,#F2F2EF);padding:88px 24px;text-align:center}
.cos-pdp-value-inner{max-width:420px;margin:0 auto}
.cos-pdp-value-row{display:flex;align-items:baseline;justify-content:space-between;padding:10px 0;font-size:14px;font-weight:300;color:var(--cos-slate,#5C6675)}
.cos-pdp-value-row + .cos-pdp-value-row{border-top:1px solid var(--cos-stone,#E5DED4)}
.cos-pdp-value-row-main{font-size:16px;color:var(--cos-ink)}
.cos-pdp-value-row-main .cos-pdp-value-amt{font-family:var(--cos-display);font-style:normal;font-weight:400;font-size:28px;color:var(--cos-ink)}
.cos-pdp-value-amt{font-variant-numeric:tabular-nums}
.cos-pdp-value-row-original .cos-pdp-value-amt{text-decoration:line-through;color:var(--cos-slate,#5C6675)}
.cos-pdp-value-row-save{color:var(--cos-gold,#8B692F)}
.cos-pdp-value-row-save .cos-pdp-value-amt{color:var(--cos-gold,#8B692F);font-weight:500}
.cos-pdp-value-cta{display:inline-flex;align-items:center;min-height:44px;margin-top:28px;padding:0 4px 3px;background:none;border:none;border-bottom:1px solid var(--cos-ink,#111111);font-family:var(--cos-body);font-size:11px;font-weight:600;letter-spacing:.14em;text-transform:uppercase;color:var(--cos-ink,#111111);text-decoration:none;cursor:pointer;transition:color .2s,border-color .2s}
.cos-pdp-value-cta:hover{color:var(--cos-gold,#8B692F);border-color:var(--cos-gold,#8B692F)}
@media(min-width:768px){.cos-pdp-value{padding:88px 24px}}

/* ── Responsive ── */
@media(min-width:768px){
  .cos-pdp-info{padding:64px 52px;justify-content:center}
  .cos-pdp-desc-section{padding:88px 48px}
  .cos-pdp-actives{padding:88px 48px}
  .cos-pdp-how{padding:88px 48px}
  .cos-pdp-clinical{padding:88px 48px}
  .cos-pdp-inci{padding:72px 48px}
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
.cos-pdp-benefit-ico{width:14px;height:14px;flex-shrink:0;color:var(--cos-gold-l,#B79A5B);margin-top:2px}

/* ── Evidence near CTA (UI/UX pass 2026-09-19, task 5) ── */
/* Only ever rendered for products with a real, published statistic
   ($pd['stats'] -- see inc/product-data.php) -- the cleanser has none and
   never gets this block. Reuses that exact statistic text unchanged; this
   is a compact pointer to it near the point of purchase, not a
   restatement/rewrite. The full "Combined Clinical Results" section
   further down the page is unaffected and still shows every stat. */
.cos-pdp-evidence{display:flex;align-items:baseline;flex-wrap:wrap;gap:6px 14px;margin-bottom:20px;padding:14px 16px;background:var(--cos-pearl,#F2F2EF);border-left:3px solid var(--cos-gold,#8B692F)}
.cos-pdp-evidence-num{font-family:var(--cos-display);font-style:italic;font-weight:300;font-size:24px;color:var(--cos-ink,#111111);line-height:1}
.cos-pdp-evidence-label{font-size:12px;font-weight:400;color:var(--cos-slate,#5C6675);line-height:1.4;flex:1;min-width:140px}
.cos-pdp-evidence-link{font-size:10px;font-weight:600;letter-spacing:.08em;text-transform:uppercase;color:var(--cos-teal,#46656B);text-decoration:underline;text-underline-offset:2px;white-space:nowrap}
.cos-pdp-evidence-link:hover{color:var(--cos-navy,#050505)}

/* ── Who It Is For + Skin Concerns ── */
.cos-pdp-who{background:var(--cos-pearl,#F2F2EF);padding:72px 24px;border-top:1px solid var(--cos-stone,#E5DED4)}
.cos-pdp-who-inner{max-width:1100px;margin:0 auto;display:grid;grid-template-columns:1fr;gap:48px}
.cos-pdp-who-block .cos-pdp-section-eyebrow{margin-bottom:14px!important}
.cos-pdp-who-text{font-size:14px;font-weight:300;color:var(--cos-slate);line-height:1.85}
.cos-pdp-concerns-tags{display:flex;flex-wrap:wrap;gap:8px;margin-top:4px}
.cos-pdp-concern-tag{font-size:10px;font-weight:500;letter-spacing:.1em;text-transform:uppercase;color:var(--cos-ink);padding:8px 14px;border:1px solid var(--cos-stone,#E5DED4);background:#fff;text-decoration:none;display:inline-block}
a.cos-pdp-concern-tag{cursor:pointer;transition:border-color .2s,color .2s}
a.cos-pdp-concern-tag:hover{border-color:var(--cos-gold,#8B692F);color:var(--cos-gold,#8B692F)}

/* ── Texture & Sensorial ── */
.cos-pdp-texture{margin-top:32px;padding-top:28px;border-top:1px solid var(--cos-stone,#E5DED4)}
.cos-pdp-texture p{font-size:14px;font-weight:300;color:var(--cos-slate);line-height:1.85;font-style:italic}

/* ── Product Specifications ── */
.cos-pdp-specs{background:var(--cos-pearl,#F2F2EF);padding:72px 24px;border-top:1px solid var(--cos-stone,#E5DED4)}
.cos-pdp-specs-inner{max-width:760px;margin:0 auto}
.cos-pdp-specs-table{width:100%;border-collapse:collapse;margin-top:24px}
.cos-pdp-specs-table tr{border-bottom:1px solid var(--cos-stone,#E5DED4)}
.cos-pdp-specs-table td{padding:14px 0;font-size:13px;font-weight:300;color:var(--cos-slate);line-height:1.5;vertical-align:top}
.cos-pdp-specs-table td:first-child{font-size:10px;font-weight:600;letter-spacing:.14em;text-transform:uppercase;color:var(--cos-ink);width:40%;padding-right:24px}
.cos-pdp-clean-note{margin-top:32px;padding-top:24px;border-top:1px solid var(--cos-stone,#E5DED4)}
.cos-pdp-clean-note p{font-size:12px;font-weight:300;color:var(--cos-slate);line-height:1.85;font-style:italic}

/* ── FAQ ── */
.cos-pdp-faq{background:var(--cos-pearl,#F2F2EF);padding:72px 24px;border-top:1px solid var(--cos-stone,#E5DED4)}
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

/* ══════════════════════════════════════════════════════════════════
   Individual-product PDP audit (2026-09-27). Scoped with
   #cos-pdp:not(.cos-pdp-bundle) so none of it reaches the bundle page,
   which shares some class names (.cos-pdp-gallery-main etc.).
   ══════════════════════════════════════════════════════════════════ */
/* No sticky gallery (2026-09-27, owner request): with the purchase panel
   now about the same height as the gallery, pinning the gallery only made
   the right column appear to scroll on its own for a moment before the
   page moved. Both columns scroll with the page from the first pixel. */
@media(min-width:1024px){#cos-pdp:not(.cos-pdp-bundle) .cos-pdp-img-col{position:static}}
#cos-pdp:not(.cos-pdp-bundle){--cos-pdp-body:var(--cos-slate,#5C6675)}
#cos-pdp:not(.cos-pdp-bundle) [id^="pdp-"]{scroll-margin-top:calc(var(--cos-nav-h,80px) + 16px)}
#cos-pdp:not(.cos-pdp-bundle) :focus-visible{outline:2px solid var(--cos-focus,#2F6FED);outline-offset:2px}
.cos-sr-only{position:absolute!important;width:1px;height:1px;padding:0;margin:-1px;overflow:hidden;clip:rect(0 0 0 0);white-space:nowrap;border:0}

/* Gallery: stacked slides, cross-fade */
#cos-pdp:not(.cos-pdp-bundle) .cos-pdp-gallery{position:relative;display:flex;flex-direction:column}
#cos-pdp:not(.cos-pdp-bundle) .cos-pdp-gallery:focus-visible{outline-offset:-2px}
#cos-pdp:not(.cos-pdp-bundle) .cos-pdp-gallery-main{flex:none;min-height:0;aspect-ratio:1/1;touch-action:pan-y}
#cos-pdp:not(.cos-pdp-bundle) .cos-pdp-slide{position:absolute;inset:0;opacity:0;transition:opacity .35s ease}
#cos-pdp:not(.cos-pdp-bundle) .cos-pdp-slide.is-active{opacity:1;z-index:1}
#cos-pdp:not(.cos-pdp-bundle) .cos-pdp-slide picture{display:contents}
#cos-pdp:not(.cos-pdp-bundle) .cos-pdp-slide img,#cos-pdp:not(.cos-pdp-bundle) .cos-pdp-slide video{position:absolute;inset:0;width:100%;height:100%;object-fit:cover;display:block}
/* Slide 1 is the upright product shot: bias the crop so the full bottle,
   cap to base, stays in frame whatever the box ratio (tuned visually). */
#cos-pdp:not(.cos-pdp-bundle) .cos-pdp-slide:first-child img{object-position:50% 55%}
@media(prefers-reduced-motion:reduce){#cos-pdp:not(.cos-pdp-bundle) .cos-pdp-slide{transition:none}}
#cos-pdp:not(.cos-pdp-bundle) .cos-pdp-gallery-arrow{position:absolute;top:50%;z-index:2;transform:translateY(-50%);width:44px;height:44px;display:flex;align-items:center;justify-content:center;padding:0;border:1px solid rgba(5, 5, 5,.12);border-radius:50%;background:rgba(242,242,239,.88);color:var(--cos-ink,#111);cursor:pointer;transition:background .2s}
#cos-pdp:not(.cos-pdp-bundle) .cos-pdp-gallery-arrow:hover{background:#fff}
#cos-pdp:not(.cos-pdp-bundle) .cos-pdp-gallery-arrow svg{width:18px;height:18px;fill:none;stroke:currentColor;stroke-width:1.5;stroke-linecap:round;stroke-linejoin:round}
#cos-pdp:not(.cos-pdp-bundle) .cos-pdp-gallery-arrow.is-prev{left:12px}
#cos-pdp:not(.cos-pdp-bundle) .cos-pdp-gallery-arrow.is-next{right:12px}
#cos-pdp:not(.cos-pdp-bundle) .cos-pdp-gallery-count{position:absolute;right:12px;bottom:12px;z-index:2;margin:0;padding:4px 10px;background:rgba(242,242,239,.88);font-size:11px;font-weight:500;letter-spacing:.08em;color:var(--cos-ink,#111);font-variant-numeric:tabular-nums}
#cos-pdp:not(.cos-pdp-bundle) .cos-pdp-thumbstrip{display:none}
#cos-pdp:not(.cos-pdp-bundle) .cos-pdp-thumb{position:relative;width:64px;height:64px;padding:0;opacity:.55;border:1px solid transparent;outline-offset:2px}
#cos-pdp:not(.cos-pdp-bundle) .cos-pdp-thumb.is-active{opacity:1;border-color:var(--cos-ink,#111)}
#cos-pdp:not(.cos-pdp-bundle) .cos-pdp-dots{padding:4px 0;background:var(--cos-pearl,#F2F2EF);border-top:0}
@media(min-width:768px) and (max-width:1023px){
  #cos-pdp:not(.cos-pdp-bundle) .cos-pdp-gallery-main{aspect-ratio:4/5;max-height:72svh}
}
@media(min-width:1024px){
  #cos-pdp:not(.cos-pdp-bundle) .cos-pdp-img-col{background:transparent}
  #cos-pdp:not(.cos-pdp-bundle) .cos-pdp-gallery-main{aspect-ratio:4/5;max-height:calc(100svh - var(--cos-nav-h,80px) - 136px);background:var(--cos-gallery-bg,#E8E8E5)}
  #cos-pdp:not(.cos-pdp-bundle) .cos-pdp-thumbstrip{display:flex;gap:8px;padding:12px 0 0}
  #cos-pdp:not(.cos-pdp-bundle) .cos-pdp-dots{display:none}
  #cos-pdp:not(.cos-pdp-bundle) .cos-pdp-info{padding:40px 56px 56px;justify-content:flex-start}
}
/* Small laptops (1024-1199): a narrower gutter so the copy wraps less and
   Add to Bag still lands inside a 768px-tall first viewport. */
@media(min-width:1024px) and (max-width:1199px){
  #cos-pdp:not(.cos-pdp-bundle) .cos-pdp-info{padding:28px 32px 48px}
  #cos-pdp:not(.cos-pdp-bundle) .cos-pdp-header-block{margin-bottom:14px}
}
@media(min-width:1200px){#cos-pdp:not(.cos-pdp-bundle) .cos-pdp-info{padding:48px 72px 64px}}

/* Purchase panel */
/* No subtitle or divider under the product name (removed 2026-09-27):
   the name flows straight into the price line. */
#cos-pdp:not(.cos-pdp-bundle) .cos-pdp-header-block{margin-bottom:18px;padding-bottom:0;border-bottom:none}
#cos-pdp:not(.cos-pdp-bundle) .cos-pdp-price-row{align-items:baseline;gap:10px;margin-bottom:18px}
#cos-pdp:not(.cos-pdp-bundle) .cos-pdp-price-size{font-size:16px;font-weight:400;color:var(--cos-pdp-body)}
#cos-pdp:not(.cos-pdp-bundle) .cos-pdp-price-size span{margin-right:2px;color:var(--cos-slate,#5C6675)}
#cos-pdp:not(.cos-pdp-bundle) .cos-pdp-short-desc{font-size:16px;font-weight:400;line-height:1.6;color:var(--cos-pdp-body);margin-bottom:18px}
#cos-pdp:not(.cos-pdp-bundle) .cos-pdp-benefits-list{margin-bottom:22px;gap:8px}
#cos-pdp:not(.cos-pdp-bundle) .cos-pdp-benefit-item{font-size:15px;font-weight:400;line-height:1.55;color:var(--cos-pdp-body)}
/* Evidence line: a quiet footnote-style sentence rather than a boxed callout
   (2026-09-27) -- no panel, no gold rule, figure at text size, inline link. */
#cos-pdp:not(.cos-pdp-bundle) .cos-pdp-evidence{display:block;margin:-6px 0 22px;padding:0;background:none;border:0;font-size:13px;line-height:1.55;color:var(--cos-slate,#5C6675)}
#cos-pdp:not(.cos-pdp-bundle) .cos-pdp-evidence-num{font-family:var(--cos-body);font-style:normal;font-weight:500;font-size:13px;color:var(--cos-pdp-body);margin-right:4px;line-height:inherit}
#cos-pdp:not(.cos-pdp-bundle) .cos-pdp-evidence-label{font-size:13px;color:var(--cos-slate,#5C6675);line-height:inherit}
#cos-pdp:not(.cos-pdp-bundle) .cos-pdp-evidence-link{margin-left:8px;font-size:13px;font-weight:400;letter-spacing:0;text-transform:none;white-space:nowrap;color:var(--cos-slate,#5C6675);text-decoration-color:rgba(92,102,117,.45);text-underline-offset:3px}
#cos-pdp:not(.cos-pdp-bundle) .cos-pdp-evidence-link:hover{color:var(--cos-ink,#111111);text-decoration-color:currentColor}
#cos-pdp:not(.cos-pdp-bundle) .cos-pdp-trust{margin-top:20px;padding-top:18px;gap:12px 20px}
#cos-pdp:not(.cos-pdp-bundle) .cos-pdp-trust-item{letter-spacing:.06em;color:var(--cos-pdp-body)}
#cos-pdp:not(.cos-pdp-bundle) .cos-pdp-bundle-nudge{margin:20px 0 12px;font-size:12px}
#cos-pdp:not(.cos-pdp-bundle) .cos-pdp-view-ritual{margin:28px 0 0;color:var(--cos-pdp-body)}

/* Section navigation: horizontal scroll with an edge fade that clears once
   the last link is reachable (.is-end, set by script), square-ish links,
   scroll-spy active state. */
#cos-pdp:not(.cos-pdp-bundle) .cos-pdp-jumpnav-wrap{position:relative;background:#EDEAE4;border-top:1px solid var(--cos-stone,#E5DED4);border-bottom:1px solid var(--cos-stone,#E5DED4)}
#cos-pdp:not(.cos-pdp-bundle) .cos-pdp-jumpnav-wrap::after{content:'';position:absolute;top:0;right:0;bottom:0;width:48px;pointer-events:none;background:linear-gradient(to right,rgba(237,234,228,0),#EDEAE4 80%);transition:opacity .2s}
#cos-pdp:not(.cos-pdp-bundle) .cos-pdp-jumpnav-wrap.is-end::after{opacity:0}
#cos-pdp:not(.cos-pdp-bundle) .cos-pdp-jumpnav{background:none;border:0;padding:12px 16px;scroll-padding-inline:16px}
#cos-pdp:not(.cos-pdp-bundle) .cos-pdp-jumpnav::after{content:'';flex:0 0 32px}
#cos-pdp:not(.cos-pdp-bundle) .cos-pdp-jump-link{border-radius:2px;color:var(--cos-pdp-body)}
#cos-pdp:not(.cos-pdp-bundle) .cos-pdp-jump-link[aria-current="true"]{border-color:var(--cos-ink,#111);color:var(--cos-ink,#111)}
@media(min-width:1024px){
  #cos-pdp:not(.cos-pdp-bundle) .cos-pdp-jumpnav{justify-content:center;flex-wrap:wrap}
  #cos-pdp:not(.cos-pdp-bundle) .cos-pdp-jumpnav::after,#cos-pdp:not(.cos-pdp-bundle) .cos-pdp-jumpnav-wrap::after{display:none}
}

/* Typography aligned to the sitewide system (2026-09-27), measured from
   Home/Shop/Ritual/Science/Heritage: Cormorant Garamond light italic for
   titles (H1 up to 56px like the Shop hero, section H2 ~40px, card/FAQ
   titles 22px), Inter Light 14px / ~1.8 in slate for running copy, 600-
   weight tracked uppercase for labels. Replaces the earlier PDP-only 16px
   regular body treatment, which read heavier than every other page. */
#cos-pdp:not(.cos-pdp-bundle) .cos-pdp-name{font-size:clamp(38px,3.9vw,56px);line-height:1.08;letter-spacing:-.005em}
#cos-pdp:not(.cos-pdp-bundle) .cos-pdp-section-title,
#cos-pdp:not(.cos-pdp-bundle) .cos-pdp-desc-inner h2{font-size:clamp(28px,2.8vw,40px);line-height:1.15}
#cos-pdp:not(.cos-pdp-bundle) .cos-pdp-short-desc{font-size:16px;font-weight:400;line-height:1.65;color:var(--cos-navy,#050505)}
#cos-pdp:not(.cos-pdp-bundle) .cos-pdp-benefit-item,
#cos-pdp:not(.cos-pdp-bundle) .cos-pdp-desc-inner p,
#cos-pdp:not(.cos-pdp-bundle) .cos-pdp-desc-inner li,
#cos-pdp:not(.cos-pdp-bundle) .cos-pdp-who-text,
#cos-pdp:not(.cos-pdp-bundle) .cos-pdp-active-desc,
#cos-pdp:not(.cos-pdp-bundle) .cos-pdp-how-text,
#cos-pdp:not(.cos-pdp-bundle) .cos-pdp-faq-a,
#cos-pdp:not(.cos-pdp-bundle) .cos-pdp-texture p,
#cos-pdp:not(.cos-pdp-bundle) .cos-pdp-specs-table td{font-size:14px;font-weight:300;line-height:1.8;color:var(--cos-slate,#5C6675)}
#cos-pdp:not(.cos-pdp-bundle) .cos-pdp-benefit-item{line-height:1.7}
#cos-pdp:not(.cos-pdp-bundle) .cos-pdp-specs-table td:first-child{font-size:10px;font-weight:600;letter-spacing:.14em;color:var(--cos-ink,#111111)}
#cos-pdp:not(.cos-pdp-bundle) .cos-pdp-inci-text,
#cos-pdp:not(.cos-pdp-bundle) .cos-pdp-clean-note p{font-size:13px;font-weight:300;color:var(--cos-slate,#5C6675)}
/* Card and question titles use the display serif, as Shop cards and the
   Home FAQ do. */
#cos-pdp:not(.cos-pdp-bundle) .cos-pdp-active-name{font-family:var(--cos-display);font-style:italic;font-weight:300;font-size:clamp(19px,1.8vw,22px);letter-spacing:0;text-transform:none;line-height:1.2;color:var(--cos-ink,#111111)}
#cos-pdp:not(.cos-pdp-bundle) .cos-pdp-faq-q{font-family:var(--cos-display);font-style:italic;font-weight:300;font-size:clamp(19px,1.8vw,22px);line-height:1.3;color:var(--cos-ink,#111111)}
/* Labels: same tracking as the Shop trust strip and site buttons. */
#cos-pdp:not(.cos-pdp-bundle) .cos-pdp-trust-item{font-size:10px;font-weight:600;letter-spacing:.14em;color:var(--cos-ink,#111111)}
#cos-pdp:not(.cos-pdp-bundle) .cos-pdp-jump-link{letter-spacing:.14em}
#cos-pdp:not(.cos-pdp-bundle) .cos-pdp-price-size{color:var(--cos-slate,#5C6675)}
#cos-pdp:not(.cos-pdp-bundle) .cos-pdp-desc-inner h2{margin-bottom:16px}
#cos-pdp:not(.cos-pdp-bundle) .cos-pdp-desc-inner p + p{margin-top:14px}
/* "Who it's for" is short now; don't stack its bottom padding on top of the
   actives section's own top padding. */
#cos-pdp:not(.cos-pdp-bundle) .cos-pdp-who{padding-bottom:24px}
@media(min-width:768px){#cos-pdp:not(.cos-pdp-bundle) .cos-pdp-who{padding-bottom:32px}}

/* Skin concerns: links look and behave like links; concerns without a
   page are plain text (no border, hover, pointer or focus). */
#cos-pdp:not(.cos-pdp-bundle) .cos-pdp-concern-tag{display:inline-flex;align-items:center;gap:6px;min-height:36px;padding:0 12px}
#cos-pdp:not(.cos-pdp-bundle) .cos-pdp-concern-tag.is-link svg{width:10px;height:10px;fill:none;stroke:currentColor;stroke-width:1.5}
#cos-pdp:not(.cos-pdp-bundle) .cos-pdp-concern-tag.is-text{background:transparent;border-color:transparent;color:var(--cos-pdp-body);cursor:default;padding:0 8px}

/* Description beside a supporting photograph (products that set
   'description_image'): image first on small screens, 5/7 split from 900px. */
#cos-pdp:not(.cos-pdp-bundle) .cos-pdp-desc-section.has-media .cos-pdp-desc-inner{max-width:560px;margin:0}
#cos-pdp:not(.cos-pdp-bundle) .cos-pdp-desc-split{max-width:1100px;margin:0 auto;display:grid;grid-template-columns:1fr;gap:32px;align-items:center}
#cos-pdp:not(.cos-pdp-bundle) .cos-pdp-desc-media{margin:0 -24px}
#cos-pdp:not(.cos-pdp-bundle) .cos-pdp-desc-media img{display:block;width:100%;height:auto}
@media(min-width:768px){#cos-pdp:not(.cos-pdp-bundle) .cos-pdp-desc-media{margin:0 auto;max-width:560px;width:100%}}
@media(min-width:900px){#cos-pdp:not(.cos-pdp-bundle) .cos-pdp-desc-split{grid-template-columns:5fr 7fr;gap:72px}}
/* Application photograph inside "How to Apply": steps left, photo right
   from 900px (mirrors the description section); photo under the steps on
   smaller screens. */
#cos-pdp:not(.cos-pdp-bundle) .cos-pdp-how-split{max-width:1100px;margin:0 auto;display:grid;grid-template-columns:1fr;gap:40px;align-items:center}
#cos-pdp:not(.cos-pdp-bundle) .cos-pdp-how.has-media .cos-pdp-how-inner{max-width:560px;margin:0}
#cos-pdp:not(.cos-pdp-bundle) .cos-pdp-how-media{margin:0 -24px}
#cos-pdp:not(.cos-pdp-bundle) .cos-pdp-how-media img{display:block;width:100%;height:auto}
@media(min-width:768px){#cos-pdp:not(.cos-pdp-bundle) .cos-pdp-how-media{margin:0 auto;max-width:560px;width:100%}}
@media(min-width:900px){#cos-pdp:not(.cos-pdp-bundle) .cos-pdp-how-split{grid-template-columns:7fr 5fr;gap:72px}}

/* Clinical proof: sized to its content (one stat = one centred column, no
   empty grid cells), tighter vertical rhythm, readable note. */
#cos-pdp:not(.cos-pdp-bundle) .cos-pdp-clinical .cos-pdp-section-title{margin-bottom:32px!important}
#cos-pdp:not(.cos-pdp-bundle) .cos-pdp-stats-n1{grid-template-columns:1fr;max-width:560px;margin:0 auto;background:none}
#cos-pdp:not(.cos-pdp-bundle) .cos-pdp-stats-n2{grid-template-columns:repeat(2,1fr)}
#cos-pdp:not(.cos-pdp-bundle) .cos-pdp-stat{padding:32px 24px}
#cos-pdp:not(.cos-pdp-bundle) .cos-pdp-stat-label{font-size:14px;color:rgba(255,255,255,.8)}
#cos-pdp:not(.cos-pdp-bundle) .cos-pdp-clinical-note{font-size:13px;letter-spacing:0;text-transform:none;color:rgba(255,255,255,.78);margin-top:24px!important;line-height:1.6}
#cos-pdp:not(.cos-pdp-bundle) .cos-pdp-clinical-note a{color:#fff;text-decoration:underline;text-underline-offset:2px}
@media(max-width:767px){
  #cos-pdp:not(.cos-pdp-bundle) .cos-pdp-clinical{padding:56px 24px}
  #cos-pdp:not(.cos-pdp-bundle) .cos-pdp-stat{padding:24px 16px}
  #cos-pdp:not(.cos-pdp-bundle) .cos-pdp-clinical .cos-pdp-section-title{margin-bottom:20px!important}
}
@media(min-width:768px){#cos-pdp:not(.cos-pdp-bundle) .cos-pdp-clinical{padding:72px 48px}}

/* Sticky mobile bar: readable at 360px */
.cos-pdp-sticky-name{font-size:13px}
.cos-pdp-sticky-price{font-size:13px;color:var(--cos-ink,#111)}
.cos-pdp-sticky-btn{min-height:48px}

/* Ritual stepper as navigation (2026-09-27): four equal steps, each a
   44px-tall link to that product with a short label under its bar. Wraps
   to its own full-width row whenever it can't keep ~72px per step beside
   the "Step 02 of 04" label. */
#cos-pdp:not(.cos-pdp-bundle) .cos-pdp-step-row{flex-wrap:wrap;row-gap:6px}
#cos-pdp:not(.cos-pdp-bundle) .cos-pdp-ritual-track{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:4px;flex:1 1 300px;min-width:min(288px,100%);margin:0}
#cos-pdp:not(.cos-pdp-bundle) .cos-pdp-rt-step{display:flex;flex-direction:column;justify-content:center;gap:7px;min-height:44px;padding:8px 0 6px;text-decoration:none;color:var(--cos-slate,#5C6675)}
#cos-pdp:not(.cos-pdp-bundle) .cos-pdp-rt-seg{display:block;flex:none;height:2px;width:100%;transition:background .2s}
#cos-pdp:not(.cos-pdp-bundle) .cos-pdp-rt-lbl{font-size:9px;font-weight:600;letter-spacing:.16em;text-transform:uppercase;line-height:1.2;white-space:nowrap;transition:color .2s}
#cos-pdp:not(.cos-pdp-bundle) .cos-pdp-rt-num{font-family:var(--cos-display);font-style:italic;font-weight:300;font-size:12px;letter-spacing:0;text-transform:none;margin-right:2px}
#cos-pdp:not(.cos-pdp-bundle) .cos-pdp-rt-step.is-cur{color:var(--cos-gold,#8B692F)}
#cos-pdp:not(.cos-pdp-bundle) a.cos-pdp-rt-step:hover{color:var(--cos-ink,#111111)}
#cos-pdp:not(.cos-pdp-bundle) a.cos-pdp-rt-step:hover .cos-pdp-rt-seg{background:var(--cos-navy,#050505)}
#cos-pdp:not(.cos-pdp-bundle) a.cos-pdp-rt-step:focus-visible{outline-offset:3px}
@media(max-width:420px){#cos-pdp:not(.cos-pdp-bundle) .cos-pdp-rt-num{display:none}#cos-pdp:not(.cos-pdp-bundle) .cos-pdp-rt-lbl{letter-spacing:.1em}}

/* ══════════════════════════════════════════════════════════════════
   Bundle editorial redesign (2026-09-20) — everything below this line
   is scoped to #cos-pdp.cos-pdp-bundle and never affects the four
   individual-product pages, which keep every rule above unchanged.
   Narrative sequence: Desire (hero) → Discovery (Four Acts of Care) →
   Understanding (Designed to Work Together) → Proof (Proven Results) →
   Purchase (Final Purchase Moment). No "Chapter" labelling anywhere on
   this page (2026-09-22 revision) -- section eyebrows are plain category
   labels (e.g. "The Full Range") or omitted entirely.
   ══════════════════════════════════════════════════════════════════ */

/* Type system: the display serif reverts to its roman weight across the
   bundle (individual products keep their italic treatment everywhere
   else) -- italics are reserved for the handful of short emotional
   section headlines that opt in via .cos-pdp-title-emotive. Heading
   scale is also increased over the individual-product default. */
.cos-pdp-bundle .cos-pdp-name{font-style:normal;font-weight:400;font-size:clamp(34px,5vw,60px);line-height:1.06}
.cos-pdp-bundle .cos-pdp-section-title{font-style:normal;font-weight:400;font-size:clamp(30px,3.8vw,48px);line-height:1.15}
.cos-pdp-bundle .cos-pdp-section-title.cos-pdp-title-emotive{font-style:italic;font-weight:300}
.cos-pdp-bundle .cos-pdp-subtitle{font-family:var(--cos-body);font-style:normal;font-weight:400;font-size:13px;letter-spacing:.02em;color:var(--cos-slate,#5C6675)}
/* Stricter colour discipline: gold is reserved for price/value and
   interactive accents only (hover states, the saving figure, stat
   numerals) rather than decorating every small label -- section eyebrows
   read in quiet ink with wide tracking instead, closer to how a luxury
   maison keeps colour scarce and lets typography and imagery carry the
   page. */
.cos-pdp-bundle .cos-pdp-section-eyebrow{color:var(--cos-ink,#111111);letter-spacing:.32em;font-weight:500}
/* A quiet fade-up on each section's inner content, running once on its own
   fixed CSS clock -- deliberately NOT scroll- or JS-triggered. An earlier
   IntersectionObserver-driven version left content at opacity:0 whenever
   the observer didn't fire in time (full-page screenshots, print/PDF
   export, any JS error), which is a worse failure than simply not having
   the effect. A plain `animation` has no such failure mode: every element
   reaches opacity:1 within .8s of paint regardless of scroll position,
   print, or script errors, and is fully inert under reduced motion. */
@media(prefers-reduced-motion:no-preference){
  .cos-pdp-reveal{animation:cosPdpFadeUp .8s ease both}
}
@keyframes cosPdpFadeUp{
  from{opacity:0;transform:translateY(18px)}
  to{opacity:1;transform:none}
}

/* ── 1. Hero — full-bleed poster image only (2026-09-21 revision), in the
   manner of a luxury-house ritual page opener: no purchase chrome overlaid
   on the photograph itself. .cos-pdp-hero reverts to a single block (the
   base stylesheet's grid/sticky rules only ever applied via
   min-width:1024px, so overriding display:block here is enough to cancel
   them for the bundle without touching the shared rules individual
   products still use). The purchase panel that used to sit beside the
   image now lives in its own section directly below, #pdp-purchase. ── */
.cos-pdp-bundle .cos-pdp-hero{display:block}
.cos-pdp-bundle .cos-pdp-img-col{position:static}
.cos-pdp-bundle .cos-pdp-gallery-main{background:var(--cos-pearl,#F2F2EF);min-height:0;aspect-ratio:1672/941}
.cos-pdp-bundle .cos-pdp-gallery-main img{object-fit:cover;object-position:center 5%;inset:0;width:100%;height:100%}
@media(min-width:768px){.cos-pdp-bundle .cos-pdp-gallery-main{max-height:calc(100svh - var(--cos-nav-h,80px))}}
/* Hero caption (2026-09-26) — names what the photograph shows: the whole
   collection as one set. Below 1200px it's a black band under the image
   (the image is too short to carry text without covering products); from
   1200px it sits over the empty black left of the photo, whose products
   start at ~31% of its width -- the caption stops at 27vw. Text only, no
   purchase chrome, so the hero stays a poster. #cos-pdp prefix: the page
   reset ("#cos-pdp p{margin:0}") would otherwise win on specificity. */
#cos-pdp.cos-pdp-bundle .cos-pdp-hero{position:relative}
#cos-pdp .cos-pdp-hero-caption{background:#000;color:var(--cos-pearl,#F2F2EF);padding:36px 24px 48px;text-align:center}
#cos-pdp .cos-pdp-hero-caption-eyebrow{font-size:10px;font-weight:600;letter-spacing:.24em;text-transform:uppercase;color:var(--cos-gold-l,#B79A5B);margin-bottom:14px}
#cos-pdp .cos-pdp-hero-caption-title{font-family:var(--cos-display);font-style:italic;font-weight:300;font-size:clamp(32px,8vw,40px);line-height:1.1;color:#fff;margin-bottom:14px}
#cos-pdp .cos-pdp-hero-caption-body{font-size:15px;font-weight:300;line-height:1.65;color:rgba(247,243,238,.82);max-width:34ch;margin:0 auto}
@media(min-width:1200px){
  #cos-pdp .cos-pdp-hero-caption{position:absolute;top:0;bottom:0;left:0;width:27vw;display:flex;flex-direction:column;justify-content:center;background:none;text-align:left;padding:0 0 0 clamp(56px,5vw,96px)}
  #cos-pdp .cos-pdp-hero-caption-eyebrow{margin-bottom:18px}
  #cos-pdp .cos-pdp-hero-caption-title{font-size:clamp(36px,2.9vw,50px);margin-bottom:18px}
  #cos-pdp .cos-pdp-hero-caption-body{font-size:16px;margin:0}
}

/* ── Statement — a short editorial line between the poster hero and the
   purchase panel: brand mark, one headline, one descriptive paragraph, a
   footnoted claims line. This is the same beat the CHANEL Sublimage line
   page uses directly under its own hero, adapted to this brand's own real,
   already-disclosed ingredient story (the High-Tech Complex) and its own
   real per-product study results (Proven Results further down / the
   methodology page), rather than restating Chanel's claim. ── */
.cos-pdp-statement{background:var(--cos-pearl,#F2F2EF);padding:88px 24px 48px;text-align:center;display:flex;align-items:center;justify-content:center}
.cos-pdp-statement-inner{max-width:760px;margin:0 auto}
/* !important: the sitewide reset "#cos-pdp h1,h2,h3,h4,p,...{margin:0}"
   (near the top of this stylesheet) carries an ID in its selector, which
   outranks a plain class selector on specificity alone regardless of
   source order -- confirmed via computed style (every margin below was
   silently resolving to 0px). Overriding it locally here rather than
   requalifying every selector with #cos-pdp. */
.cos-pdp-statement-inner .cos-pdp-section-eyebrow{margin-bottom:44px!important}
.cos-pdp-statement-inner .cos-pdp-section-title{margin-bottom:64px!important}
.cos-pdp-statement-body{font-size:16px;font-weight:300;color:var(--cos-slate,#5C6675);line-height:1.7;margin-bottom:48px!important}
.cos-pdp-statement-note{font-size:11px;font-weight:400;color:var(--cos-slate,#5C6675);line-height:1.7;margin-top:8px!important}
.cos-pdp-statement-note a{color:inherit;text-decoration:underline;text-underline-offset:2px}
@media(min-width:768px){.cos-pdp-statement{padding:140px 48px 56px}}
@media(min-width:1200px){.cos-pdp-statement-body{font-size:18px}}

/* ── Discovery Strip — three tall image panels (key ingredient, the result,
   the experience), each a full link into deeper content. The same beat
   CHANEL's Sublimage line page runs directly under its own opening
   statement -- adapted here with this brand's own real imagery/pages
   rather than Chanel's. Stacked on mobile, three even columns from 1024px. ── */
.cos-pdp-discovery{display:flex;flex-direction:column}
.cos-pdp-discovery-panel{position:relative;display:block;min-height:480px;overflow:hidden;text-decoration:none;color:#fff}
.cos-pdp-discovery-panel img{position:absolute;inset:0;width:100%;height:100%;object-fit:cover;transition:transform 1s cubic-bezier(.25,.46,.45,.94)}
.cos-pdp-discovery-panel::before{content:'';position:absolute;inset:0;background:linear-gradient(to top,rgba(5, 5, 5,.72) 0%,rgba(5, 5, 5,.12) 45%,rgba(5, 5, 5,.28) 100%);z-index:1}
@media(hover:hover) and (prefers-reduced-motion:no-preference){.cos-pdp-discovery-panel:hover img{transform:scale(1.05)}}
.cos-pdp-discovery-eyebrow{position:absolute;left:24px;top:24px;z-index:2;font-size:10px;font-weight:600;letter-spacing:.28em;text-transform:uppercase;color:rgba(255,255,255,.82)}
.cos-pdp-discovery-title{position:absolute;left:24px;right:24px;bottom:64px;z-index:2;font-family:var(--cos-display);font-style:normal;font-weight:400;font-size:clamp(22px,3vw,30px);line-height:1.15;text-transform:uppercase;letter-spacing:.01em}
.cos-pdp-discovery-cta{position:absolute;left:24px;bottom:24px;z-index:2;display:inline-flex;align-items:center;gap:10px;font-size:10px;font-weight:600;letter-spacing:.16em;text-transform:uppercase;color:#fff}
.cos-pdp-discovery-plus{width:26px;height:26px;border-radius:50%;background:rgba(255,255,255,.16);display:inline-flex;align-items:center;justify-content:center;font-size:14px;font-weight:300;line-height:1;transition:background .2s}
.cos-pdp-discovery-panel:hover .cos-pdp-discovery-plus{background:rgba(255,255,255,.32)}
@media(min-width:1024px){
  .cos-pdp-discovery{flex-direction:row}
  .cos-pdp-discovery-panel{flex:1;min-height:calc(100svh - var(--cos-nav-h,80px))}
}

/* ── Purchase — name, price, quantity, Add to Bag, reassurance, as a
   contained, centred section directly beneath the poster hero rather than
   split beside it. ── */
.cos-pdp-purchase{background:var(--cos-pearl,#F2F2EF);border-bottom:1px solid var(--cos-stone,#E5DED4)}
.cos-pdp-purchase .cos-pdp-info{max-width:560px;margin:0 auto;padding:56px 24px;text-align:center}
.cos-pdp-purchase .cos-pdp-price-row{justify-content:center}
.cos-pdp-purchase .cos-pdp-qty-row{justify-content:center}
.cos-pdp-purchase .cos-pdp-trust{grid-template-columns:1fr 1fr;justify-items:center}
.cos-pdp-bundle .cos-pdp-header-block{border-bottom:none;padding-bottom:0;margin-bottom:20px}
.cos-pdp-bundle .cos-pdp-name{margin-bottom:8px!important}
.cos-pdp-bundle .cos-pdp-price-row{margin-bottom:20px}
/* The "Save €50" figure becomes plain coloured text instead of a bordered
   tag -- one fewer small bordered element in the purchase panel. */
.cos-pdp-bundle .cos-pdp-save{border:none;padding:0;font-weight:500;color:var(--cos-gold,#8B692F)}
.cos-pdp-bundle .cos-pdp-short-desc{margin-bottom:28px}
@media(min-width:768px){
  .cos-pdp-purchase .cos-pdp-info{padding:80px 24px}
  .cos-pdp-purchase .cos-pdp-trust{grid-template-columns:repeat(4,auto)}
}
/* The poster hero pushes Add to Bag below the first viewport by design, so
   the mobile-only sticky bar (base rule further up this stylesheet) also
   stays available on desktop for this page specifically -- higher
   specificity than the plain .cos-pdp-sticky-atc selector it's overriding,
   so source order doesn't matter here. */
@media(min-width:768px){
  .cos-pdp-bundle .cos-pdp-sticky-atc{display:flex}
}

/* ── Four Acts of Care — an editorial sequence of four
   alternating image/copy rows (mobile: stacked) in place of the old
   four-card grid. Every image sits in the same large, consistent
   square stage. ── */
.cos-pdp-acts{background:var(--cos-pearl,#F2F2EF);padding:80px 24px}
.cos-pdp-acts-inner{max-width:1100px;margin:0 auto}
.cos-pdp-acts-list{margin-top:48px;display:flex;flex-direction:column;gap:72px}
.cos-pdp-acts-row{display:flex;flex-direction:column;gap:24px}
/* A slightly taller-than-square editorial crop (4:5) rather than a
   perfect square -- closer to how a fashion/beauty house frames a
   portrait-oriented photograph than a social-grid thumbnail. */
.cos-pdp-acts-media{position:relative;aspect-ratio:4/5;background:var(--cos-pearl,#F2F2EF);overflow:hidden}
/* Real photoshoot imagery (hands, models, application shots), not an
   isolated product cutout -- object-fit:cover gives each photo a proper
   full-bleed editorial crop instead of letterboxing it inside the square
   stage the way a flat white-background packshot would need to. A very
   slow, subtle zoom on hover (desktop pointer only) -- a quiet touch of
   "alive" motion consistent with a luxury-house image treatment, gated
   behind reduced-motion like the rest of the page's motion. */
.cos-pdp-acts-media img{position:absolute;inset:0;width:100%;height:100%;object-fit:cover}
@media(hover:hover) and (prefers-reduced-motion:no-preference){
  .cos-pdp-acts-media img{transition:transform 1.2s cubic-bezier(.25,.46,.45,.94)}
  .cos-pdp-acts-media:hover img{transform:scale(1.04)}
}
.cos-pdp-acts-step{font-size:11px;font-weight:600;letter-spacing:.22em;text-transform:uppercase;color:var(--cos-ink,#111111);margin-bottom:14px!important}
.cos-pdp-acts-name{font-family:var(--cos-display);font-style:normal;font-weight:400;font-size:clamp(22px,2.6vw,30px);color:var(--cos-ink);line-height:1.2;margin-bottom:2px!important}
.cos-pdp-acts-size{font-size:11px;color:var(--cos-slate,#5C6675);font-variant-numeric:tabular-nums;margin-bottom:20px;display:block}
.cos-pdp-acts-evocative{font-family:var(--cos-display);font-style:italic;font-weight:300;font-size:18px;color:var(--cos-ink);line-height:1.5;margin-bottom:16px!important}
.cos-pdp-acts-benefit{font-size:14px;font-weight:300;color:var(--cos-slate,#5C6675);line-height:1.75;margin-bottom:20px!important}
.cos-pdp-acts-link{display:inline-flex;align-items:center;min-height:44px;padding:0 4px 2px;margin:0 0 0 -4px;font-size:10px;font-weight:600;letter-spacing:.12em;text-transform:uppercase;color:var(--cos-ink);border-bottom:1px solid var(--cos-stone,#E5DED4);text-decoration:none}
.cos-pdp-acts-link:hover{border-color:var(--cos-gold,#8B692F);color:var(--cos-gold,#8B692F)}
@media(min-width:768px){.cos-pdp-acts{padding:96px 48px}}
@media(min-width:1024px){
  .cos-pdp-acts{padding:128px 48px}
  .cos-pdp-acts-list{gap:128px}
  .cos-pdp-acts-row{display:grid;grid-template-columns:1fr 1fr;gap:72px;align-items:center}
  .cos-pdp-acts-row:nth-child(even) .cos-pdp-acts-media{order:2}
  .cos-pdp-acts-body{max-width:420px}
  .cos-pdp-acts-row:nth-child(even) .cos-pdp-acts-body{margin-left:auto}
}

/* ── Quiet full-bleed visual break, true edge-to-edge (no max-width
   container) -- currently used once, right after the Discovery Strip, as a
   cinematic pause with a footnoted claims line before Purchase. ── */
.cos-pdp-visual-break{position:relative;width:100%;height:calc(100svh - var(--cos-nav-h,80px));min-height:560px;max-height:1080px;overflow:hidden}
.cos-pdp-visual-break img{position:absolute;inset:0;width:100%;height:100%;object-fit:cover}
.cos-pdp-visual-break::after{content:'';position:absolute;inset:0;background:linear-gradient(rgba(5, 5, 5,0) 35%,rgba(5, 5, 5,.72) 100%)}
.cos-pdp-visual-break-text{position:absolute;left:50%;bottom:56px;transform:translateX(-50%);z-index:1;width:88%;max-width:600px;text-align:center}
.cos-pdp-visual-break-quote{font-family:var(--cos-display);font-style:italic;font-weight:300;font-size:clamp(18px,2.4vw,27px);line-height:1.5;color:#fff}
.cos-pdp-visual-break-note{margin-top:16px!important;font-size:11px;font-weight:400;color:rgba(255,255,255,.75);line-height:1.6}
.cos-pdp-visual-break-note a{color:inherit;text-decoration:underline;text-underline-offset:2px}
@media(min-width:768px){.cos-pdp-visual-break-text{bottom:88px}}

/* ── Designed to Work Together — one large editorial
   application photo beside four brief numbered statements, ending with
   "Who It's For" merged in as one short paragraph plus a plain-text
   (never pill/tag) list of concerns. ── */
.cos-pdp-howworks{background:var(--cos-pearl,#F2F2EF);padding:0}
.cos-pdp-howworks-grid{display:grid;grid-template-columns:1fr}
.cos-pdp-howworks-img-wrap{position:relative;min-height:420px;background:var(--cos-gallery-bg,#E8E8E5)}
.cos-pdp-howworks-img-wrap img{position:absolute;inset:0;width:100%;height:100%;object-fit:cover}
.cos-pdp-howworks-body{padding:64px 24px}
.cos-pdp-howworks-inner{max-width:480px}
.cos-pdp-howworks-steps{display:flex;flex-direction:column;gap:0;margin-top:32px}
.cos-pdp-howworks-step{display:grid;grid-template-columns:28px 1fr;gap:14px;padding:16px 0;border-bottom:1px solid var(--cos-stone,#E5DED4)}
.cos-pdp-howworks-step:last-child{border-bottom:none}
.cos-pdp-howworks-num{font-family:var(--cos-display);font-style:normal;font-weight:400;font-size:17px;color:var(--cos-ink,#111111);line-height:1.5}
.cos-pdp-howworks-text{font-size:13px;font-weight:300;color:var(--cos-slate,#5C6675);line-height:1.75}
.cos-pdp-howworks-text strong{color:var(--cos-ink);font-weight:600}
.cos-pdp-howworks-who{margin-top:40px;padding-top:36px;border-top:1px solid var(--cos-stone,#E5DED4)}
.cos-pdp-howworks-who-text{font-size:14px;font-weight:300;color:var(--cos-slate,#5C6675);line-height:1.85;margin-bottom:12px!important}
.cos-pdp-howworks-concerns{font-size:11px;font-weight:500;letter-spacing:.02em;color:var(--cos-slate,#5C6675);line-height:1.9}
@media(min-width:768px){
  /* A slightly image-dominant split (11:9 rather than 1:1), echoing the
     hero's own ratio, so the large editorial photograph keeps leading
     the page rather than settling into an even two-column layout. */
  .cos-pdp-howworks-grid{grid-template-columns:11fr 9fr}
  .cos-pdp-howworks-img-wrap{min-height:auto}
  .cos-pdp-howworks-body{padding:88px 64px}
}

/* ── Proven Results — the shared navy clinical-results section, with the
   three statistics enlarged so they read as the dominant visual element;
   the subtle 1px hairline column dividers and the already-legible
   disclaimer note are inherited unchanged from the shared rule above. ── */
.cos-pdp-bundle .cos-pdp-stat-num{font-size:clamp(42px,5.5vw,68px)}

/* ── Final Purchase Moment — a composed, quiet navy close (navy is used
   only here and in Proven Results, per the visual system) with no
   floating card: name, one closing line, price, Add to Bag, reassurance. ── */
.cos-pdp-final{background:var(--cos-navy,#050505);padding:96px 24px;text-align:center}
.cos-pdp-final-inner{max-width:460px;margin:0 auto}
/* !important throughout: same sitewide "#cos-pdp p,h1-h4{margin:0}" reset
   (ID selector, outranks any plain class on specificity) that silently
   zeroed every margin in the Statement section also applies here -- every
   element below is a <p>. */
.cos-pdp-final-name{font-family:var(--cos-display);font-style:normal;font-weight:400;font-size:clamp(30px,4.5vw,46px);color:#fff;line-height:1.1;margin-bottom:18px!important}
.cos-pdp-final-statement{font-family:var(--cos-display);font-style:italic;font-weight:300;font-size:17px;color:rgba(255,255,255,.82);line-height:1.6;margin-bottom:36px!important}
.cos-pdp-final-price{font-family:var(--cos-display);font-style:normal;font-weight:400;font-size:34px;color:#fff;margin-bottom:6px!important}
.cos-pdp-final-original{font-size:15px;color:rgba(255,255,255,.5);text-decoration:line-through;margin-bottom:6px!important}
.cos-pdp-final-save{font-size:11px;font-weight:500;letter-spacing:.06em;text-transform:uppercase;color:var(--cos-gold-l,#B79A5B);margin-bottom:32px!important}
.cos-pdp-final-cta{display:inline-block;min-width:240px;padding:17px 36px;min-height:44px;box-sizing:border-box;background:#fff;color:var(--cos-navy,#050505);font-family:var(--cos-body);font-size:10px;font-weight:600;letter-spacing:.2em;text-transform:uppercase;text-align:center;text-decoration:none;border:none;cursor:pointer;transition:background .2s,color .2s}
.cos-pdp-final-cta:hover{background:var(--cos-gold-l,#B79A5B);color:var(--cos-navy,#050505)}
.cos-pdp-final-reassurance{margin-top:24px!important;font-size:11px;font-weight:500;letter-spacing:.03em;color:rgba(255,255,255,.6)}
@media(min-width:768px){.cos-pdp-final{padding:136px 24px}}

/* ── FAQ — visually quiet and narrower than the editorial sections
   above it; accordion functionality and copy are unchanged. ── */
.cos-pdp-bundle .cos-pdp-faq-inner{max-width:600px}
.cos-pdp-bundle .cos-pdp-faq{padding:96px 24px;border-top:none}
@media(min-width:768px){.cos-pdp-bundle .cos-pdp-faq{padding:120px 48px}}

/* ── Discover the Range — appended directly onto the Statement section
   (2026-09-22), inviting an early browse of the full line right after the
   brand introduction rather than only at the very end of the page. Plain
   product cards (clean packshot, step label, name, price) linking to each
   individual PDP -- no new claims, just the same four products already
   named throughout this page. ── */
.cos-pdp-discover{background:var(--cos-pearl,#F2F2EF);padding:48px 24px 88px}
.cos-pdp-discover-inner{max-width:1100px;margin:0 auto}
.cos-pdp-discover-grid{display:grid;grid-template-columns:repeat(2,1fr);gap:28px}
.cos-pdp-discover-card{display:block;text-decoration:none;color:inherit}
/* White (not pearl) now that the section itself is pearl -- keeps each
   packshot on its own visible card instead of blending into the section. */
.cos-pdp-discover-media{display:block;position:relative;aspect-ratio:1;background:#fff;overflow:hidden;margin-bottom:16px}
.cos-pdp-discover-media img{position:absolute;inset:0;width:100%;height:100%;object-fit:cover}
@media(hover:hover) and (prefers-reduced-motion:no-preference){
  .cos-pdp-discover-media img{transition:transform .5s ease}
  .cos-pdp-discover-card:hover .cos-pdp-discover-media img{transform:scale(1.05)}
}
.cos-pdp-discover-step{font-size:10px;font-weight:600;letter-spacing:.16em;text-transform:uppercase;color:var(--cos-slate,#5C6675);display:block;margin-bottom:6px}
.cos-pdp-discover-name{font-family:var(--cos-display);font-style:normal;font-weight:400;font-size:16px;color:var(--cos-ink);display:block;margin-bottom:4px;line-height:1.3}
.cos-pdp-discover-price{font-size:13px;font-weight:300;color:var(--cos-slate,#5C6675);display:block}
@media(min-width:768px){
  .cos-pdp-discover{padding:64px 48px 120px}
  .cos-pdp-discover-grid{grid-template-columns:repeat(4,1fr);gap:32px}
}
</style>

<main id="cos-main-content">
  <div id="cos-pdp"<?php echo 'COS-BUNDLE' === $sku ? ' class="cos-pdp-bundle"' : ''; ?>>
    <div style="height:var(--cos-nav-h,80px)"></div>

    <?php if ( 'COS-BUNDLE' === $sku ) :
      // Editorial redesign (2026-09-20) — rebuilds the bundle page as a
      // story about the complete four-step ritual (Desire → Discovery →
      // Understanding → Proof → Craftsmanship → Purchase) rather than a
      // catalogue of sections. Reuses the same $product/$pd/$gallery_ids/
      // $name/$price/$sale/$regular setup computed above, plus the exact
      // same ATC form, qty stepper, trust grid, and sticky-bar markup/JS
      // as every other product page (untouched, further down this same
      // file) so cart/quantity/sticky behaviour isn't reimplemented, just
      // reused. The hero's own image is the sideloaded reflective-surface
      // shot, attachment $hero_id further down — see the comment there.
      $ritual_products = array(
        // 'img' points at a real photoshoot image from each product's own
        // gallery (hands, models, application shots) rather than the flat
        // studio packshot (used elsewhere for OG/social sharing, see
        // cos_child_seo_image_meta() in inc/seo.php) -- at this section's
        // large image scale, real photography reads as editorial rather
        // than the synthetic, rendered look of an isolated product cutout.
        // 'evocative' and 'benefit' are both drawn from each product's own
        // product-data.php entry (subtitle/benefits), not invented here.
        array(
          'sku' => 'COS-001', 'slug' => 'micellar-water-cleanser', 'step' => '01', 'act' => 'Cleanse',
          'name' => 'Micellar Water Cleanser', 'size' => '150ml', 'img' => 90,
          'evocative' => 'The ritual begins with a no-rinse micellar water that clears the day without stripping the skin.',
          'benefit'   => 'Cleanses, tones, and refreshes in one step — ready for the serum that follows.',
        ),
        array(
          'sku' => 'COS-002', 'slug' => 'advanced-repair-serum', 'step' => '02', 'act' => 'Repair',
          'name' => 'Advanced Repair Serum', 'size' => '30ml', 'img' => 202,
          'evocative' => 'The flagship of the ritual — a silky, fast-absorbing serum that disappears into the skin in seconds.',
          'benefit'   => 'Supports a firmer, smoother-looking complexion.',
        ),
        array(
          'sku' => 'COS-003', 'slug' => 'revitalizing-eye-cream', 'step' => '03', 'act' => 'Restore',
          'name' => 'Revitalizing Eye Cream', 'size' => '15ml', 'img' => 186,
          'evocative' => 'A precise, comforting cream calibrated for the eye contour — skin no other step is formulated to treat.',
          'benefit'   => 'Helps diminish the appearance of dark circles and fine lines.',
        ),
        array(
          'sku' => 'COS-004', 'slug' => '24h-regeneration-cream', 'step' => '04', 'act' => 'Seal',
          'name' => '24h Regeneration Cream', 'size' => '50ml', 'img' => 176,
          'evocative' => 'The final layer — a day-and-night cream that seals in everything applied before it.',
          'benefit'   => 'Provides lasting hydration and comfort, day and night.',
        ),
      );
      $compare_total = 0;
      foreach ( $ritual_products as $rp ) {
        $rp_id    = wc_get_product_id_by_sku( $rp['sku'] );
        $rp_wc    = $rp_id ? wc_get_product( $rp_id ) : null;
        $compare_total += $rp_wc ? (float) $rp_wc->get_price() : 0;
      }
      $compare_bundle_price = (float) ( $sale ?: $price );
      $compare_savings      = $compare_total - $compare_bundle_price;
      // Poster hero (2026-09-26): a black-background product shot (attachment
      // 23789, sideloaded for this redesign) -- supersedes both the earlier
      // reflective-surface still (23788) and the brief 2026-09-22 background-
      // video treatment (autoplay video + pause/play toggle removed
      // entirely, per an explicit revert back to a static image). Replaces
      // the plain-background gallery image ($gallery_ids[0]/482, still used
      // further down anywhere the old flat packshot is still wanted) -- the
      // hero is this single full-bleed image with no purchase chrome
      // overlaid on it, in the manner of a luxury-house ritual page opener.
      // The Add to Bag panel that used to sit beside it lives in its own
      // section right below instead (#pdp-purchase, further down this file).
      $hero_id      = 23789;
      $first_data   = wp_get_attachment_image_src( $hero_id, 'full' );
      $first_src    = $first_data ? $first_data[0] : $img_src;
      $first_w      = $first_data ? $first_data[1] : 0;
      $first_h      = $first_data ? $first_data[2] : 0;
      $first_srcset = wp_get_attachment_image_srcset( $hero_id, 'full' );
      $first_sizes  = wp_get_attachment_image_sizes( $hero_id, 'full' );
    ?>

    <!-- Hero: Desire — full-bleed image only, no purchase chrome -->
    <div class="cos-pdp-hero cos-pdp-hero-full">
      <div class="cos-pdp-img-col">
        <div class="cos-pdp-gallery-main">
          <img src="<?php echo esc_url( $first_src ); ?>"<?php if ( $first_srcset ) : ?> srcset="<?php echo esc_attr( $first_srcset ); ?>" sizes="<?php echo esc_attr( $first_sizes ?: '100vw' ); ?>"<?php endif; ?> width="<?php echo esc_attr( $first_w ); ?>" height="<?php echo esc_attr( $first_h ); ?>" alt="The Complete Ritual — Micellar Water Cleanser, Advanced Repair Serum, Revitalizing Eye Cream, and 24h Regeneration Cream, all four Caviar of Switzerland products together on a reflective black surface" fetchpriority="high"/>
        </div>
      </div>
      <div class="cos-pdp-hero-caption">
        <p class="cos-pdp-hero-caption-eyebrow">The Complete Collection</p>
        <p class="cos-pdp-hero-caption-title">Four formulas.<br>One ritual.</p>
        <p class="cos-pdp-hero-caption-body">Cleanser, serum, eye cream and moisturiser &mdash; every Caviar of Switzerland formula, together in one set.</p>
      </div>
    </div>

    <!-- Statement — brand mark, headline, one paragraph, directly beneath
         the poster hero and above the purchase panel -->
    <section class="cos-pdp-statement" id="pdp-statement">
      <div class="cos-pdp-statement-inner cos-pdp-reveal">
        <p class="cos-pdp-section-eyebrow">Caviar of Switzerland</p>
        <h2 class="cos-pdp-section-title cos-pdp-title-emotive">The complete regeneration ritual.</h2>
        <p class="cos-pdp-statement-body">Built around the High-Tech Complex — Caviar Extract, SYN&reg;-COLL, Hyaluronic Acid, Superoxide Dismutase, and Resveratrol — the ritual moves through four formulas in sequence, each carrying the blend in a concentration suited to its role: cleanse, repair, restore, seal. A daily routine for skin that looks more hydrated, firmer, and more radiant, one step at a time.</p>
      </div>
    </section>

    <!-- Discover the Range — appended directly onto the Statement section
         (2026-09-22), per each individual product's own packshot/slug/price,
         reusing $ritual_products defined further up this same file -->
    <section class="cos-pdp-discover" id="pdp-discover">
      <div class="cos-pdp-discover-inner cos-pdp-reveal">
        <div class="cos-pdp-discover-grid">
          <?php foreach ( $ritual_products as $rp ) :
            $rp_id   = wc_get_product_id_by_sku( $rp['sku'] );
            $rp_wc   = $rp_id ? wc_get_product( $rp_id ) : null;
            if ( ! $rp_wc ) continue;
            // Prefer the same real photoshoot image Chapter One/Four Acts of
            // Care uses ($rp['img']) over each product's own WooCommerce
            // featured image -- the featured images are a mix of editorial
            // shots and flat packshots depending on the SKU (Revitalizing Eye
            // Cream's is a plain packshot), which read as visibly
            // inconsistent side by side in this grid; falls back to the
            // featured image only if a product has no editorial shot set.
            $pack_id     = ! empty( $rp['img'] ) ? $rp['img'] : $rp_wc->get_image_id();
            $pack_data   = $pack_id ? wp_get_attachment_image_src( $pack_id, 'medium' ) : false;
            $pack_srcset = $pack_id ? wp_get_attachment_image_srcset( $pack_id, 'medium' ) : false;
          ?>
          <a class="cos-pdp-discover-card" href="/products/<?php echo esc_attr( $rp['slug'] ); ?>/">
            <span class="cos-pdp-discover-media">
              <?php if ( $pack_data ) : ?>
              <img src="<?php echo esc_url( $pack_data[0] ); ?>"<?php if ( $pack_srcset ) : ?> srcset="<?php echo esc_attr( $pack_srcset ); ?>" sizes="(min-width: 768px) 25vw, 50vw"<?php endif; ?> width="<?php echo esc_attr( $pack_data[1] ); ?>" height="<?php echo esc_attr( $pack_data[2] ); ?>" alt="<?php echo esc_attr( $rp['name'] ); ?>" loading="lazy"/>
              <?php endif; ?>
            </span>
            <span class="cos-pdp-discover-step"><?php echo esc_html( $rp['step'] . ' — ' . $rp['act'] ); ?></span>
            <span class="cos-pdp-discover-name"><?php echo esc_html( $rp['name'] ); ?></span>
            <span class="cos-pdp-discover-price"><?php echo wp_kses_post( wc_price( $rp_wc->get_price() ) ); ?></span>
          </a>
          <?php endforeach; ?>
        </div>
      </div>
    </section>

    <!-- Discovery Strip: The Key Ingredient / The Result / The Experience —
         three full-link image panels, each into real, existing content. -->
    <?php
    $discovery_result_data   = wp_get_attachment_image_src( 23721, 'large' );
    $discovery_result_srcset = wp_get_attachment_image_srcset( 23721, 'large' );
    $discovery_exp_data      = wp_get_attachment_image_src( 202, 'large' );
    $discovery_exp_srcset    = wp_get_attachment_image_srcset( 202, 'large' );
    $discovery_ingredient_src = get_stylesheet_directory_uri() . '/assets/images/ingredients/caviar-extract.jpg';
    ?>
    <section class="cos-pdp-discovery cos-pdp-reveal">
      <a class="cos-pdp-discovery-panel" href="/science/ingredients/caviar-extract/">
        <img src="<?php echo esc_url( $discovery_ingredient_src ); ?>" width="725" height="725" alt="Caviar Extract, the signature active of the Caviar of Switzerland High-Tech Complex" loading="lazy"/>
        <span class="cos-pdp-discovery-eyebrow">The Key Ingredient</span>
        <span class="cos-pdp-discovery-title">Caviar Extract</span>
        <span class="cos-pdp-discovery-cta">Discover <span class="cos-pdp-discovery-plus" aria-hidden="true">+</span></span>
      </a>
      <a class="cos-pdp-discovery-panel" href="/science/clinical-studies/">
        <?php if ( $discovery_result_data ) : ?>
        <img src="<?php echo esc_url( $discovery_result_data[0] ); ?>"<?php if ( $discovery_result_srcset ) : ?> srcset="<?php echo esc_attr( $discovery_result_srcset ); ?>" sizes="(min-width: 1024px) 34vw, 100vw"<?php endif; ?> width="<?php echo esc_attr( $discovery_result_data[1] ); ?>" height="<?php echo esc_attr( $discovery_result_data[2] ); ?>" alt="A close-up application of a Caviar of Switzerland formula to the skin" loading="lazy"/>
        <?php endif; ?>
        <span class="cos-pdp-discovery-eyebrow">The Result</span>
        <span class="cos-pdp-discovery-title">Visible Regeneration</span>
        <span class="cos-pdp-discovery-cta">Discover <span class="cos-pdp-discovery-plus" aria-hidden="true">+</span></span>
      </a>
      <a class="cos-pdp-discovery-panel" href="/the-ritual/">
        <?php if ( $discovery_exp_data ) : ?>
        <img src="<?php echo esc_url( $discovery_exp_data[0] ); ?>"<?php if ( $discovery_exp_srcset ) : ?> srcset="<?php echo esc_attr( $discovery_exp_srcset ); ?>" sizes="(min-width: 1024px) 34vw, 100vw"<?php endif; ?> width="<?php echo esc_attr( $discovery_exp_data[1] ); ?>" height="<?php echo esc_attr( $discovery_exp_data[2] ); ?>" alt="Holding a Caviar of Switzerland formula, close-up" loading="lazy"/>
        <?php endif; ?>
        <span class="cos-pdp-discovery-eyebrow">The Experience</span>
        <span class="cos-pdp-discovery-title">An Everyday Ritual</span>
        <span class="cos-pdp-discovery-cta">Discover <span class="cos-pdp-discovery-plus" aria-hidden="true">+</span></span>
      </a>
    </section>

    <!-- Purchase — name, price, and Add to Bag as their own section directly
         after the Discovery Strip, so buying stays reachable early rather
         than after the cinematic break below -->
    <section class="cos-pdp-purchase" id="pdp-purchase">
      <div class="cos-pdp-info">
        <div class="cos-pdp-header-block">
          <h1 class="cos-pdp-name"><?php echo esc_html( $name ); ?></h1>
          <?php if ( isset( $pd['subtitle'] ) ) : ?>
          <p class="cos-pdp-subtitle"><?php echo esc_html( $pd['subtitle'] ); ?></p>
          <?php endif; ?>
        </div>

        <div class="cos-pdp-price-row">
          <?php if ( $sale ) : ?>
            <span class="cos-pdp-price"><?php echo wc_price( $sale ); ?></span>
            <span class="cos-pdp-sale-original"><?php echo wc_price( $regular ); ?></span>
            <span class="cos-pdp-save">Save <?php echo wc_price( $regular - $sale ); ?></span>
          <?php else : ?>
            <span class="cos-pdp-price"><?php echo wc_price( $price ); ?></span>
          <?php endif; ?>
        </div>

        <div class="cos-pdp-short-desc">
          <p>The complete four-step Caviar of Switzerland ritual — cleanser, serum, eye cream, and regeneration cream — formulated to work in sequence. Save &euro;50 versus buying each product separately.</p>
        </div>

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
        <p class="cos-pdp-methodology-link"><a href="/science/clinical-studies/">See the study methodology &rarr;</a></p>
      </div>
    </section>

    <!-- Quiet full-bleed visual break with a footnoted claims line, between
         Purchase and Four Acts of Care -- the same cinematic "breathing
         moment" beat the CHANEL Sublimage page runs after its own 3-panel
         strip, with this brand's own real, disclosed clinical results
         rather than Chanel's unsourced claim. -->
    <?php
    $break2_data   = wp_get_attachment_image_src( 23722, 'full' );
    $break2_srcset = wp_get_attachment_image_srcset( 23722, 'full' );
    ?>
    <?php if ( $break2_data ) : ?>
    <div class="cos-pdp-visual-break">
      <img src="<?php echo esc_url( $break2_data[0] ); ?>"<?php if ( $break2_srcset ) : ?> srcset="<?php echo esc_attr( $break2_srcset ); ?>" sizes="100vw"<?php endif; ?> width="<?php echo esc_attr( $break2_data[1] ); ?>" height="<?php echo esc_attr( $break2_data[2] ); ?>" alt="" loading="lazy"/>
      <div class="cos-pdp-visual-break-text cos-pdp-reveal">
        <p class="cos-pdp-visual-break-quote">With consistent use, skin looks visibly firmer and fine lines appear softer — the ritual's promise, borne out in our own clinical testing.*</p>
        <p class="cos-pdp-visual-break-note">*Per-product results, independent third-party studies, 28 days. <a href="/science/clinical-studies/">See the study methodology.</a></p>
      </div>
    </div>
    <?php endif; ?>

    <!-- Discovery: Four Acts of Care -->
    <section class="cos-pdp-acts" id="pdp-acts">
      <div class="cos-pdp-acts-inner cos-pdp-reveal">
        <h2 class="cos-pdp-section-title cos-pdp-title-emotive">Four acts of care.</h2>
        <div class="cos-pdp-acts-list">
          <?php foreach ( $ritual_products as $rp ) :
            $rp_data   = ! empty( $rp['img'] ) ? wp_get_attachment_image_src( $rp['img'], 'large' ) : false;
            $rp_srcset = ! empty( $rp['img'] ) ? wp_get_attachment_image_srcset( $rp['img'], 'large' ) : false;
          ?>
          <div class="cos-pdp-acts-row">
            <?php if ( $rp_data ) : ?>
            <div class="cos-pdp-acts-media">
              <img src="<?php echo esc_url( $rp_data[0] ); ?>"<?php if ( $rp_srcset ) : ?> srcset="<?php echo esc_attr( $rp_srcset ); ?>" sizes="(min-width: 1024px) 50vw, 100vw"<?php endif; ?> width="<?php echo esc_attr( $rp_data[1] ); ?>" height="<?php echo esc_attr( $rp_data[2] ); ?>" alt="<?php echo esc_attr( $rp['name'] ); ?>" loading="lazy"/>
            </div>
            <?php endif; ?>
            <div class="cos-pdp-acts-body">
              <p class="cos-pdp-acts-step"><?php echo esc_html( $rp['step'] . ' — ' . $rp['act'] ); ?></p>
              <p class="cos-pdp-acts-name"><?php echo esc_html( $rp['name'] ); ?></p>
              <span class="cos-pdp-acts-size"><?php echo esc_html( $rp['size'] ); ?></span>
              <p class="cos-pdp-acts-evocative"><?php echo esc_html( $rp['evocative'] ); ?></p>
              <p class="cos-pdp-acts-benefit"><?php echo esc_html( $rp['benefit'] ); ?></p>
              <a class="cos-pdp-acts-link" href="/products/<?php echo esc_attr( $rp['slug'] ); ?>/">View product</a>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </section>

    <!-- Understanding: Designed to Work Together (Who It's
         For merged in at the end, per the redesign brief) -->
    <?php
    $howworks_data   = wp_get_attachment_image_src( 23721, 'large' );
    $howworks_srcset = wp_get_attachment_image_srcset( 23721, 'large' );
    ?>
    <section class="cos-pdp-howworks" id="pdp-how-it-works">
      <div class="cos-pdp-howworks-grid cos-pdp-reveal">
        <div class="cos-pdp-howworks-img-wrap">
          <?php if ( $howworks_data ) :
            $howworks_sizes = $howworks_srcset ? '(min-width: 768px) 50vw, 100vw' : '';
            $howworks_html  = '<img src="' . esc_url( $howworks_data[0] ) . '"' . ( $howworks_srcset ? ' srcset="' . esc_attr( $howworks_srcset ) . '" sizes="' . esc_attr( $howworks_sizes ) . '"' : '' ) . ' width="' . esc_attr( $howworks_data[1] ) . '" height="' . esc_attr( $howworks_data[2] ) . '" alt="Applying a Caviar of Switzerland formula to the skin, close-up" loading="lazy"/>';
            echo cos_child_nextgen_picture( $howworks_data[0], $howworks_srcset ?: '', $howworks_sizes, $howworks_html );
          endif; ?>
        </div>
        <div class="cos-pdp-howworks-body">
          <div class="cos-pdp-howworks-inner">
            <h2 class="cos-pdp-section-title cos-pdp-title-emotive">Designed to work together.</h2>
            <div class="cos-pdp-howworks-steps">
              <div class="cos-pdp-howworks-step">
                <span class="cos-pdp-howworks-num">01</span>
                <p class="cos-pdp-howworks-text"><strong>Micellar Water</strong> cleanses and prepares the skin for what follows.</p>
              </div>
              <div class="cos-pdp-howworks-step">
                <span class="cos-pdp-howworks-num">02</span>
                <p class="cos-pdp-howworks-text"><strong>Advanced Repair Serum</strong> delivers concentrated actives into freshly cleansed skin.</p>
              </div>
              <div class="cos-pdp-howworks-step">
                <span class="cos-pdp-howworks-num">03</span>
                <p class="cos-pdp-howworks-text"><strong>Revitalizing Eye Cream</strong> treats the eye contour — skin the other steps aren't formulated for.</p>
              </div>
              <div class="cos-pdp-howworks-step">
                <span class="cos-pdp-howworks-num">04</span>
                <p class="cos-pdp-howworks-text"><strong>24h Regeneration Cream</strong> seals every step before it, day or night.</p>
              </div>
            </div>
            <?php if ( $pd && ! empty( $pd['who_for'] ) ) : ?>
            <div class="cos-pdp-howworks-who">
              <p class="cos-pdp-howworks-who-text"><?php echo esc_html( $pd['who_for'] ); ?></p>
              <?php if ( ! empty( $pd['concerns'] ) ) : ?>
              <p class="cos-pdp-howworks-concerns"><?php echo esc_html( implode( ' · ', $pd['concerns'] ) ); ?></p>
              <?php endif; ?>
            </div>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </section>

    <!-- Value Moment — a quiet typographic interlude, not a comparison table -->
    <section class="cos-pdp-value">
      <div class="cos-pdp-value-inner cos-pdp-reveal">
        <div class="cos-pdp-value-row cos-pdp-value-row-main">
          <span>The Complete Ritual</span>
          <span class="cos-pdp-value-amt"><?php echo wp_kses_post( wc_price( $compare_bundle_price ) ); ?></span>
        </div>
        <div class="cos-pdp-value-row cos-pdp-value-row-original">
          <span>Individually</span>
          <span class="cos-pdp-value-amt"><?php echo wp_kses_post( wc_price( $compare_total ) ); ?></span>
        </div>
        <?php if ( $compare_savings > 0 ) : ?>
        <div class="cos-pdp-value-row cos-pdp-value-row-save">
          <span>Your saving</span>
          <span class="cos-pdp-value-amt"><?php echo wp_kses_post( wc_price( $compare_savings ) ); ?></span>
        </div>
        <?php endif; ?>
        <a href="<?php echo esc_url( add_query_arg( 'add-to-cart', $product->get_id(), wc_get_cart_url() ) ); ?>" class="cos-pdp-value-cta add_to_cart_button ajax_add_to_cart" data-product_id="<?php echo esc_attr( $product->get_id() ); ?>" data-quantity="1" data-cro-cta="pdp-value-atc" rel="nofollow">Add Complete Ritual to Bag</a>
      </div>
    </section>

    <!-- Proof: Proven Results -->
    <section class="cos-pdp-clinical" id="pdp-clinical">
      <div class="cos-pdp-clinical-inner cos-pdp-reveal">
        <h2 class="cos-pdp-section-title">Independent results across the three treatment formulas.</h2>
        <div class="cos-pdp-stats">
          <div class="cos-pdp-stat"><span class="cos-pdp-stat-num">+47%</span><span class="cos-pdp-stat-label">Cutometer-measured firmness improvement at 28 days — Advanced Repair Serum</span></div>
          <div class="cos-pdp-stat"><span class="cos-pdp-stat-num">−38%</span><span class="cos-pdp-stat-label">Fine line depth reduction — Revitalizing Eye Cream</span></div>
          <div class="cos-pdp-stat"><span class="cos-pdp-stat-num">100%</span><span class="cos-pdp-stat-label">Panellist satisfaction at 28 days — 24h Regeneration Cream</span></div>
        </div>
        <p class="cos-pdp-clinical-note">Results from independent third-party studies, per product, over 28 days &mdash; instrumental measurement or panellist self-report, as disclosed per statistic. <a href="/science/clinical-studies/" style="color:inherit;text-decoration:underline">See the study methodology &rarr;</a></p>
      </div>
    </section>

    <!-- Final Purchase Moment: Purchase -->
    <section class="cos-pdp-final">
      <div class="cos-pdp-final-inner cos-pdp-reveal">
        <p class="cos-pdp-final-name">The Complete Ritual</p>
        <p class="cos-pdp-final-statement">Four steps. One ritual. Everything you need, in one box.</p>
        <p class="cos-pdp-final-price"><?php echo wp_kses_post( wc_price( $compare_bundle_price ) ); ?></p>
        <p class="cos-pdp-final-original"><?php echo wp_kses_post( wc_price( $compare_total ) ); ?> individually</p>
        <?php if ( $compare_savings > 0 ) : ?>
        <p class="cos-pdp-final-save">Save <?php echo wp_kses_post( wc_price( $compare_savings ) ); ?></p>
        <?php endif; ?>
        <a href="<?php echo esc_url( add_query_arg( 'add-to-cart', $product->get_id(), wc_get_cart_url() ) ); ?>" class="cos-pdp-final-cta add_to_cart_button ajax_add_to_cart" data-product_id="<?php echo esc_attr( $product->get_id() ); ?>" data-quantity="1" data-cro-cta="pdp-final-atc" rel="nofollow">Add to Bag</a>
        <p class="cos-pdp-final-reassurance"><?php echo esc_html( cos_child_shipping_message( 'badge' ) ); ?> &middot; 30-Day Returns</p>
      </div>
    </section>

    <!-- FAQ -->
    <section class="cos-pdp-faq" id="pdp-faq">
      <div class="cos-pdp-faq-inner cos-pdp-reveal">
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

    <!-- The .cos-pdp-reveal fade-up is a plain CSS animation keyed to paint
         time, not scroll -- no JS is needed for it. -->

    <?php else : ?>

    <!-- Hero -->
    <div class="cos-pdp-hero">

      <!-- Image column: gallery (PDP audit 2026-09-27). Every photo is its own
           stacked slide inside a <picture> (AVIF/WebP), cross-faded by class --
           this replaces the old single <img> whose src was swapped by JS, which
           needed srcset stripping to work at all and couldn't use <picture>.
           Only slide 1 is eager/high-priority; the rest lazy-load. Controls:
           arrows inside the frame, thumbnails (>=1024px) or dots (below),
           arrow keys while the gallery has focus, swipe on touch. -->
      <div class="cos-pdp-img-col">
        <?php
        $first_id = ! empty( $gallery_ids ) ? $gallery_ids[0] : $img_id;
        $cos_slide_ids = $gallery_ids ?: ( $img_id ? array( $img_id ) : array() );
        $cos_slide_count = count( $cos_slide_ids ) + ( $cos_video_url ? 1 : 0 );
        $cos_gallery_sizes = '(min-width: 1024px) 50vw, 100vw';
        // Next-gen sibling srcset: swaps each candidate's extension for $ext and
        // keeps only files that actually exist (AVIF for .jpg/.webp sizes, WebP
        // for .jpg sizes). The shared cos_child_nextgen_srcset() only knows .jpg.
        $cos_alt_srcset = static function ( string $srcset, string $ext ): string {
            $out = array();
            foreach ( array_filter( array_map( 'trim', explode( ',', $srcset ) ) ) as $part ) {
                $sp  = strrpos( $part, ' ' );
                $url = false !== $sp ? substr( $part, 0, $sp ) : $part;
                $alt = preg_replace( 'webp' === $ext ? '/\.jpe?g$/i' : '/\.(jpe?g|webp)$/i', '.' . $ext, $url );
                if ( $alt === $url ) continue;
                if ( file_exists( ABSPATH . ltrim( (string) wp_parse_url( $alt, PHP_URL_PATH ), '/' ) ) ) {
                    $out[] = $alt . ( false !== $sp ? substr( $part, $sp ) : '' );
                }
            }
            return implode( ', ', $out );
        };
        $cos_slide_picture = static function ( int $aid, int $index, string $alt ) use ( $cos_alt_srcset, $cos_gallery_sizes ): string {
            $data = wp_get_attachment_image_src( $aid, 'large' );
            if ( ! $data ) return '';
            $srcset = (string) wp_get_attachment_image_srcset( $aid, 'large' );
            $img = '<img' . ( 0 === $index ? ' id="cos-gallery-main" fetchpriority="high"' : ' loading="lazy" decoding="async"' )
                . ' src="' . esc_url( $data[0] ) . '"'
                . ( $srcset ? ' srcset="' . esc_attr( $srcset ) . '" sizes="' . esc_attr( $cos_gallery_sizes ) . '"' : '' )
                . ' width="' . (int) $data[1] . '" height="' . (int) $data[2] . '" alt="' . esc_attr( $alt ) . '"/>';
            $sources = '';
            foreach ( array( 'avif', 'webp' ) as $ext ) {
                $set = $srcset ? $cos_alt_srcset( $srcset, $ext ) : '';
                if ( $set ) $sources .= '<source type="image/' . $ext . '" srcset="' . esc_attr( $set ) . '" sizes="' . esc_attr( $cos_gallery_sizes ) . '">';
            }
            return $sources ? '<picture>' . $sources . $img . '</picture>' : $img;
        };
        ?>
        <div class="cos-pdp-gallery" id="cos-gallery"<?php if ( $cos_slide_count > 1 ) : ?> role="region" aria-roledescription="carousel" aria-label="<?php echo esc_attr( $name ); ?> images" tabindex="0"<?php endif; ?>>
          <div class="cos-pdp-gallery-main">
            <?php foreach ( $cos_slide_ids as $si => $sid ) :
              // Slide 1 carries the product's own alt copy; the rest use the
              // attachment's alt text, falling back to a numbered description.
              $s_alt = 0 === $si ? ( $pd['image_alt'] ?? $name ) : ( get_post_meta( $sid, '_wp_attachment_image_alt', true ) ?: sprintf( '%s, image %d of %d', $name, $si + 1, $cos_slide_count ) );
            ?>
            <div class="cos-pdp-slide<?php echo 0 === $si ? ' is-active' : ''; ?>"<?php if ( $cos_slide_count > 1 ) : ?> role="group" aria-roledescription="slide" aria-label="<?php echo esc_attr( ( $si + 1 ) . ' of ' . $cos_slide_count ); ?>"<?php echo 0 === $si ? '' : ' aria-hidden="true"'; ?><?php endif; ?>>
              <?php echo $cos_slide_picture( (int) $sid, $si, $s_alt ); // phpcs:ignore -- built from escaped parts above ?>
            </div>
            <?php endforeach; ?>
            <?php if ( $cos_video_url ) : ?>
            <div class="cos-pdp-slide cos-pdp-slide-video" role="group" aria-roledescription="slide" aria-label="<?php echo esc_attr( $cos_slide_count . ' of ' . $cos_slide_count ); ?>" aria-hidden="true">
              <video id="cos-gallery-video" data-src="<?php echo esc_url( $cos_video_url ); ?>" preload="none" poster="<?php echo esc_url( $img_src ); ?>" muted loop playsinline></video>
            </div>
            <?php endif; ?>

            <?php if ( $cos_slide_count > 1 ) : ?>
            <button type="button" class="cos-pdp-gallery-arrow is-prev" data-gallery-step="-1" aria-label="Previous image" aria-controls="cos-gallery"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M15 5l-7 7 7 7"/></svg></button>
            <button type="button" class="cos-pdp-gallery-arrow is-next" data-gallery-step="1" aria-label="Next image" aria-controls="cos-gallery"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M9 5l7 7-7 7"/></svg></button>
            <p class="cos-pdp-gallery-count" aria-hidden="true"><span id="cos-gallery-index">1</span> / <?php echo (int) $cos_slide_count; ?></p>
            <?php endif; ?>
          </div>

          <?php if ( $cos_slide_count > 1 ) : ?>
          <div class="cos-pdp-thumbstrip" id="cos-thumbstrip" role="group" aria-label="Choose an image">
            <?php foreach ( $cos_slide_ids as $ti => $tid ) :
              $tdata = wp_get_attachment_image_src( $tid, 'thumbnail' );
              if ( ! $tdata ) continue;
            ?>
            <button class="cos-pdp-thumb<?php echo 0 === $ti ? ' is-active' : ''; ?>" type="button" data-gallery-index="<?php echo (int) $ti; ?>" aria-label="Show image <?php echo ( $ti + 1 ) . ' of ' . $cos_slide_count; ?>"<?php echo 0 === $ti ? ' aria-current="true"' : ''; ?>>
              <img src="<?php echo esc_url( $tdata[0] ); ?>" width="<?php echo (int) $tdata[1]; ?>" height="<?php echo (int) $tdata[2]; ?>" alt="" loading="lazy" decoding="async"/>
            </button>
            <?php endforeach; ?>
            <?php if ( $cos_video_url ) : ?>
            <button class="cos-pdp-thumb" type="button" data-gallery-index="<?php echo count( $cos_slide_ids ); ?>" aria-label="Play product video">
              <img src="<?php echo esc_url( $img_src ); ?>" width="<?php echo (int) $img_w; ?>" height="<?php echo (int) $img_h; ?>" alt="" loading="lazy"/>
              <span class="cos-pdp-thumb-play" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg></span>
            </button>
            <?php endif; ?>
          </div>
          <div class="cos-pdp-dots" id="cos-dots" role="group" aria-label="Choose an image">
            <?php for ( $di = 0; $di < $cos_slide_count; $di++ ) : ?>
            <button class="cos-pdp-dot<?php echo 0 === $di ? ' is-active' : ''; ?>" type="button" data-gallery-index="<?php echo (int) $di; ?>" aria-label="Show image <?php echo ( $di + 1 ) . ' of ' . $cos_slide_count; ?>"<?php echo 0 === $di ? ' aria-current="true"' : ''; ?>></button>
            <?php endfor; ?>
          </div>
          <p class="cos-sr-only" id="cos-gallery-status" aria-live="polite"></p>
          <?php endif; ?>
        </div>
      </div>

      <!-- Info column -->
      <div class="cos-pdp-info">

        <div class="cos-pdp-header-block">

          <?php if ( $step['num'] ) :
            $sn = (int) $step['num'];
          ?>
          <?php
          // Ritual stepper: each segment links to that step's product (looked
          // up by SKU, so it follows permalink changes); the current step is
          // plain text marked aria-current.
          $cos_ritual_steps = array( 1 => array( 'COS-001', 'Cleanse' ), 2 => array( 'COS-002', 'Treat' ), 3 => array( 'COS-003', 'Eye' ), 4 => array( 'COS-004', 'Moisturise' ) );
          ?>
          <div class="cos-pdp-step-row">
            <span class="cos-pdp-step-circle" aria-hidden="true"><?php echo esc_html( $step['num'] ); ?></span>
            <span class="cos-pdp-step-str"><?php echo esc_html( $step['label'] ); ?></span>
            <nav class="cos-pdp-ritual-track" aria-label="The four-step ritual">
              <?php foreach ( $cos_ritual_steps as $s => [ $s_sku, $s_short ] ) :
                $sc      = $s < $sn ? ' is-past' : ( $s === $sn ? ' is-cur' : '' );
                $s_pid   = wc_get_product_id_by_sku( $s_sku );
                $s_prod  = $s_pid ? wc_get_product( $s_pid ) : null;
                $s_label = sprintf( 'Step %d: %s', $s, $s_prod ? $s_prod->get_name() : $s_short );
                $s_inner = '<span class="cos-pdp-rt-seg' . $sc . '"></span><span class="cos-pdp-rt-lbl"><span class="cos-pdp-rt-num">' . sprintf( '%02d', $s ) . '</span> ' . esc_html( $s_short ) . '</span>';
              ?>
              <?php if ( $s === $sn || ! $s_prod ) : ?>
              <span class="cos-pdp-rt-step<?php echo $sc; ?>" aria-current="<?php echo $s === $sn ? 'page' : 'false'; ?>" aria-label="<?php echo esc_attr( $s_label ); ?>"><?php echo $s_inner; // escaped above ?></span>
              <?php else : ?>
              <a class="cos-pdp-rt-step<?php echo $sc; ?>" href="<?php echo esc_url( get_permalink( $s_pid ) ); ?>" aria-label="<?php echo esc_attr( $s_label ); ?>" data-cro-cta="pdp-ritual-step-<?php echo (int) $s; ?>"><?php echo $s_inner; // escaped above ?></a>
              <?php endif; ?>
              <?php endforeach; ?>
            </nav>
          </div>
          <?php endif; ?>

          <h1 class="cos-pdp-name"><?php echo esc_html( $name ); ?></h1>
        </div>

        <!-- Price · size, one line ("€150 · 30 ml") -->
        <div class="cos-pdp-price-row">
          <?php if ( $sale ) : ?>
            <span class="cos-pdp-price"><?php echo wc_price( $sale ); ?></span>
            <span class="cos-pdp-sale-original"><?php echo wc_price( $regular ); ?></span>
          <?php else : ?>
            <span class="cos-pdp-price"><?php echo wc_price( $price ); ?></span>
          <?php endif; ?>
          <?php if ( $cos_pdp_size_label ) : ?>
            <span class="cos-pdp-price-size"><span aria-hidden="true">&middot;</span> <?php echo esc_html( $cos_pdp_size_label ); ?></span>
          <?php endif; ?>
          <?php if ( $sale ) : ?>
            <span class="cos-pdp-save">Save <?php echo wc_price( $regular - $sale ); ?></span>
          <?php endif; ?>
        </div>

        <!-- Short description (per-product PDP summary where one is set) -->
        <div class="cos-pdp-short-desc">
          <?php echo ! empty( $pd['summary'] ) ? '<p>' . esc_html( $pd['summary'] ) . '</p>' : wpautop( wp_kses_post( $product->get_short_description() ) ); ?>
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
          <?php // Payment-method and "Authentic Swiss Formula" items removed from individual PDPs at the owner's request (2026-09-27). ?>
        </div>

        <?php // data-cro-cta keeps its original "learn" name so analytics history stays continuous. ?>
        <a href="/the-ritual/" class="cos-pdp-view-ritual" data-cro-cta="pdp-secondary-learn-ritual">Discover the Ritual</a>

      </div>
    </div>

    <?php if ( count( $cos_pdp_jump_links ) > 1 ) : ?>
    <nav class="cos-pdp-jumpnav-wrap" aria-label="Jump to a section on this page">
      <div class="cos-pdp-jumpnav" id="cos-pdp-jumpnav">
        <?php foreach ( $cos_pdp_jump_links as $jump ) : ?>
        <a href="#<?php echo esc_attr( $jump['id'] ); ?>" class="cos-pdp-jump-link"><?php echo esc_html( $jump['label'] ); ?></a>
        <?php endforeach; ?>
      </div>
    </nav>
    <?php endif; ?>

    <?php
    // Art-directed editorial photo from static theme assets, one crop per
    // breakpoint: assets/images/<dir>-<desktop|mobile>-<width>.{avif,webp,jpg}.
    // $cfg: dir, desktop/mobile => [widths, w, h], alt. Always lazy (below the fold).
    $cos_pdp_art_picture = static function ( array $cfg, string $desktop_sizes, string $mobile_sizes = '100vw', string $bp = '(min-width: 768px)' ): string {
        $base = get_stylesheet_directory_uri() . '/assets/images/' . $cfg['dir'];
        $set  = static fn( string $crop, array $widths, string $ext ): string => implode( ', ', array_map( static fn( int $w ): string => "{$base}-{$crop}-{$w}.{$ext} {$w}w", $widths ) );
        $d = $cfg['desktop']; $m = $cfg['mobile'];
        $out = '<picture>';
        foreach ( array( 'avif', 'webp', 'jpg' ) as $ext ) {
            $out .= '<source media="' . esc_attr( $bp ) . '"' . ( 'jpg' === $ext ? '' : ' type="image/' . $ext . '"' ) . ' srcset="' . esc_attr( $set( 'desktop', $d['widths'], $ext ) ) . '" sizes="' . esc_attr( $desktop_sizes ) . '" width="' . (int) $d['w'] . '" height="' . (int) $d['h'] . '">';
        }
        foreach ( array( 'avif', 'webp' ) as $ext ) {
            $out .= '<source type="image/' . $ext . '" srcset="' . esc_attr( $set( 'mobile', $m['widths'], $ext ) ) . '" sizes="' . esc_attr( $mobile_sizes ) . '">';
        }
        $out .= '<img src="' . esc_url( $base . '-mobile-' . end( $m['widths'] ) . '.jpg' ) . '" srcset="' . esc_attr( $set( 'mobile', $m['widths'], 'jpg' ) ) . '" sizes="' . esc_attr( $mobile_sizes ) . '" width="' . (int) $m['w'] . '" height="' . (int) $m['h'] . '" alt="' . esc_attr( $cfg['alt'] ) . '" loading="lazy" decoding="async">';
        return $out . '</picture>';
    };
    $cos_desc_img = $pd['description_image'] ?? null;
    ?>
    <!-- Full description (optionally beside a supporting photograph) -->
    <?php if ( $desc ) : ?>
    <section class="cos-pdp-desc-section<?php echo $cos_desc_img ? ' has-media' : ''; ?>" id="pdp-description">
      <?php if ( $cos_desc_img ) : ?>
      <div class="cos-pdp-desc-split">
        <figure class="cos-pdp-desc-media"><?php echo $cos_pdp_art_picture( $cos_desc_img, '(min-width: 1200px) 460px, 38vw', '(min-width: 768px) 560px, 100vw', '(min-width: 900px)' ); // escaped in helper ?></figure>
      <?php endif; ?>
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
      <?php if ( $cos_desc_img ) : ?>
      </div>
      <?php endif; ?>
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
            <a class="cos-pdp-concern-tag is-link" href="<?php echo esc_url( $concern_url ); ?>"><?php echo esc_html( $concern ); ?><svg viewBox="0 0 12 12" aria-hidden="true"><path d="M4 2.5L7.5 6 4 9.5"/></svg></a>
            <?php else : ?>
            <span class="cos-pdp-concern-tag is-text"><?php echo esc_html( $concern ); ?></span>
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
            <span class="cos-pdp-active-bg-n" aria-hidden="true" data-n="<?php echo sprintf( '%02d', $ai + 1 ); ?>"></span>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </section>

    <!-- How to Apply (with the application photograph beside the steps
         where the product sets one — kept inside the section rather than
         as a standalone band, so the page stays shorter) -->
    <?php $cos_how_img = $pd['editorial'] ?? null; ?>
    <section class="cos-pdp-how<?php echo $cos_how_img ? ' has-media' : ''; ?>" id="pdp-how">
      <?php if ( $cos_how_img ) : ?><div class="cos-pdp-how-split"><?php endif; ?>
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
      <?php if ( $cos_how_img ) : ?>
        <figure class="cos-pdp-how-media"><?php echo $cos_pdp_art_picture( $cos_how_img, '(min-width: 1200px) 460px, 38vw', '(min-width: 768px) 560px, 100vw', '(min-width: 900px)' ); // escaped in helper ?></figure>
      </div>
      <?php endif; ?>
    </section>

    <!-- Clinical Results -->
    <?php if ( ! empty( $pd['stats'] ) ) : ?>
    <section class="cos-pdp-clinical" id="pdp-clinical">
      <div class="cos-pdp-clinical-inner">
        <p class="cos-pdp-section-eyebrow">Clinical Proof</p>
        <h2 class="cos-pdp-section-title">Independently measured performance.</h2>
        <div class="cos-pdp-stats cos-pdp-stats-n<?php echo min( 3, count( $pd['stats'] ) ); ?>">
          <?php foreach ( $pd['stats'] as $stat ) : ?>
          <div class="cos-pdp-stat">
            <span class="cos-pdp-stat-num"><?php echo esc_html( $stat[0] ); ?></span>
            <span class="cos-pdp-stat-label"><?php echo esc_html( $stat[1] ); ?></span>
          </div>
          <?php endforeach; ?>
        </div>
        <p class="cos-pdp-clinical-note">Independent 28-day study. <a href="/science/clinical-studies/">View study methodology</a></p>
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
    <?php endif; // end else (individual products) — bundle branch is rendered separately above ?>

  </div>
</main>

<?php if ( $product->is_in_stock() ) : ?>
<div id="cos-pdp-sticky-atc" class="cos-pdp-sticky-atc" aria-hidden="true">
  <div class="cos-pdp-sticky-info">
    <span class="cos-pdp-sticky-name"><?php echo esc_html( $name ); ?></span>
    <span class="cos-pdp-sticky-price"><?php echo wp_kses_post( wc_price( $sale ?: $price ) ); ?><?php if ( $cos_pdp_size_label && 'COS-BUNDLE' !== $sku ) : ?> <span aria-hidden="true">&middot;</span> <?php echo esc_html( $cos_pdp_size_label ); ?><?php endif; ?></span>
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
// Product gallery (individual products): stacked slides toggled by class.
// Arrows, thumbnails, dots, Left/Right keys while the gallery has focus, and
// horizontal swipe all route through cosGalleryGo().
(function () {
  var gallery = document.getElementById('cos-gallery');
  if (!gallery) return;
  var slides = gallery.querySelectorAll('.cos-pdp-slide');
  if (slides.length < 2) return;
  var pickers = gallery.querySelectorAll('[data-gallery-index]');
  var indexEl = document.getElementById('cos-gallery-index');
  var status = document.getElementById('cos-gallery-status');
  var video = document.getElementById('cos-gallery-video');
  var current = 0;

  function go(i, announce) {
    var n = slides.length;
    i = ((i % n) + n) % n;
    if (i === current) return;
    slides.forEach(function (s, idx) {
      var on = idx === i;
      s.classList.toggle('is-active', on);
      s.setAttribute('aria-hidden', on ? 'false' : 'true');
      // A lazy image the visitor jumps to directly is fetched right away.
      var img = s.querySelector('img[loading="lazy"]');
      if (on && img) img.loading = 'eager';
    });
    pickers.forEach(function (b) {
      var on = +b.getAttribute('data-gallery-index') === i;
      b.classList.toggle('is-active', on);
      if (on) b.setAttribute('aria-current', 'true'); else b.removeAttribute('aria-current');
    });
    if (video) {
      var onVideo = slides[i].contains(video);
      if (onVideo) { if (!video.src && video.dataset.src) video.src = video.dataset.src; video.play().catch(function () {}); }
      else video.pause();
    }
    current = i;
    if (indexEl) indexEl.textContent = String(i + 1);
    if (status && announce !== false) status.textContent = 'Image ' + (i + 1) + ' of ' + n;
  }
  window.cosGalleryGo = go;

  gallery.addEventListener('click', function (e) {
    var step = e.target.closest('[data-gallery-step]');
    if (step) { go(current + (+step.getAttribute('data-gallery-step'))); return; }
    var pick = e.target.closest('[data-gallery-index]');
    if (pick) go(+pick.getAttribute('data-gallery-index'));
  });
  gallery.addEventListener('keydown', function (e) {
    if (e.key === 'ArrowRight') { go(current + 1); e.preventDefault(); }
    else if (e.key === 'ArrowLeft') { go(current - 1); e.preventDefault(); }
  });
  var main = gallery.querySelector('.cos-pdp-gallery-main'), x0 = null, y0 = null;
  main.addEventListener('touchstart', function (e) { x0 = e.touches[0].clientX; y0 = e.touches[0].clientY; }, { passive: true });
  main.addEventListener('touchend', function (e) {
    if (x0 === null) return;
    var dx = e.changedTouches[0].clientX - x0, dy = e.changedTouches[0].clientY - y0;
    if (Math.abs(dx) > 40 && Math.abs(dx) > Math.abs(dy) * 1.2) go(current + (dx < 0 ? 1 : -1));
    x0 = y0 = null;
  }, { passive: true });
})();

// Section navigation: edge fade only while more links are off-screen, and a
// scroll-spy that marks the section currently in view.
(function () {
  var wrap = document.querySelector('.cos-pdp-jumpnav-wrap');
  var nav = document.getElementById('cos-pdp-jumpnav');
  if (!wrap || !nav) return;
  function edge() { wrap.classList.toggle('is-end', nav.scrollLeft + nav.clientWidth >= nav.scrollWidth - 4); }
  nav.addEventListener('scroll', edge, { passive: true });
  window.addEventListener('resize', edge);
  edge();
  if (!('IntersectionObserver' in window)) return;
  var links = {};
  nav.querySelectorAll('a[href^="#"]').forEach(function (a) { links[a.getAttribute('href').slice(1)] = a; });
  var io = new IntersectionObserver(function (entries) {
    entries.forEach(function (en) {
      if (!en.isIntersecting || !links[en.target.id]) return;
      Object.keys(links).forEach(function (id) {
        if (id === en.target.id) links[id].setAttribute('aria-current', 'true'); else links[id].removeAttribute('aria-current');
      });
    });
  }, { rootMargin: '-40% 0px -55% 0px' });
  Object.keys(links).forEach(function (id) { var el = document.getElementById(id); if (el) io.observe(el); });
})();

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
