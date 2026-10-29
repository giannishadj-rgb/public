<?php
/**
 * COS — Account Page
 */
defined( 'ABSPATH' ) || exit;

$logged_in = is_user_logged_in();
if ( $logged_in ) {
	$me         = wp_get_current_user();
	$uid        = $me->ID;
	$first_name = $me->first_name ?: $me->display_name;
	$orders     = wc_get_orders( [ 'customer' => $uid, 'limit' => 5, 'orderby' => 'date', 'order' => 'DESC' ] );
	$customer   = new WC_Customer( $uid );
	$billing    = $customer->get_billing();
	$has_addr   = ! empty( $billing['address_1'] ) || ! empty( $billing['city'] );
	$logout_url = wc_get_account_endpoint_url( 'customer-logout' );
	$orders_url = wc_get_account_endpoint_url( 'orders' );
	$addr_url   = wc_get_account_endpoint_url( 'edit-address' );
	$detail_url = wc_get_account_endpoint_url( 'edit-account' );
}
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<?php cos_child_custom_document_head( 'Account - Caviar of Switzerland' ); ?>
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<style nonce="<?php echo esc_attr( cos_csp_nonce() ); ?>">
/* ─── Reset only what's needed, without the specificity trap ─── */
#cos-account { font-family: var(--cos-body); color: var(--cos-ink, #111111); background: var(--cos-pearl, #F8F8F8); min-height: 100vh; }
#cos-account * { box-sizing: border-box; }
#cos-account h1, #cos-account h2, #cos-account h3, #cos-account p, #cos-account ul, #cos-account li, #cos-account figure { margin: 0; padding: 0; list-style: none; }

/* ═══════════════════════════════════════════════
   LOGGED-OUT — two-column layout
═══════════════════════════════════════════════ */
.cos-acc-page {
  min-height: calc(100vh - var(--cos-nav-h, 80px));
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 64px 24px 80px;
}
.cos-acc-layout {
  display: flex;
  align-items: center;
  gap: 72px;
  width: 100%;
  max-width: 940px;
}

/* ── Left brand panel ── */
.cos-acc-brand {
  flex: 1;
  min-width: 0;
  position: relative;
  overflow: hidden;
}
.cos-acc-brand > * { position: relative; z-index: 1; }
.cos-acc-brand-line {
  display: block;
  width: 32px;
  height: 1px;
  background: var(--cos-gold, #8B692F);
  margin-bottom: 28px;
}
.cos-acc-brand-eyebrow {
  font-size: 9px;
  font-weight: 600;
  letter-spacing: .3em;
  text-transform: uppercase;
  color: var(--cos-gold, #8B692F);
  margin-bottom: 28px;
}
.cos-acc-brand-heading {
  font-family: var(--cos-display);
  font-style: italic;
  font-weight: 300;
  font-size: clamp(32px, 3.6vw, 52px);
  color: var(--cos-ink);
  line-height: 1.08;
  margin-bottom: 32px;
}
.cos-acc-brand-sub {
  font-size: 13px;
  font-weight: 300;
  color: var(--cos-slate, #5C6675);
  line-height: 1.85;
  margin-bottom: 40px;
  max-width: 340px;
}
/* Gold rule separating description from perks */
.cos-acc-perk-sep {
  width: 28px;
  height: 1px;
  background: var(--cos-gold, #8B692F);
  margin-bottom: 28px;
  opacity: .55;
}
.cos-acc-brand-perks {
  display: flex;
  flex-direction: column;
  gap: 16px;
}
.cos-acc-brand-perk {
  display: flex;
  align-items: center;
  gap: 10px;
  font-size: 9px;
  font-weight: 600;
  letter-spacing: .14em;
  text-transform: uppercase;
  color: var(--cos-slate);
}
.cos-acc-brand-perk svg {
  width: 13px;
  height: 13px;
  flex-shrink: 0;
  color: var(--cos-gold-l, #B79A5B);
}

/* ── Right card ── */
.cos-acc-card-col {
  flex: 0 0 440px;
  width: 440px;
}

/* Card shell */
.cos-acc-card {
  background: #fff;
  border-top: 2px solid var(--cos-gold, #8B692F);
  box-shadow: 0 8px 56px rgba(5, 5, 5, .11), 0 2px 12px rgba(139, 105, 47, .07);
}

/* Tabs */
.cos-acc-tabs {
  display: flex;
  border-bottom: 1px solid var(--cos-stone, #E5DED4);
}
.cos-acc-tab {
  flex: 1;
  padding: 16px 12px;
  font-size: 9px;
  font-weight: 700;
  letter-spacing: .22em;
  text-transform: uppercase;
  color: var(--cos-slate, #5C6675);
  cursor: pointer;
  border: none;
  font-family: var(--cos-body);
  background: var(--cos-pearl, #F8F8F8);
  border-bottom: 2px solid transparent;
  margin-bottom: -1px;
  transition: color .2s, background .2s, border-color .2s;
}
.cos-acc-tab + .cos-acc-tab { border-left: 1px solid var(--cos-stone, #E5DED4); }
.cos-acc-tab.is-active {
  color: var(--cos-navy, #050505);
  background: #fff;
  border-bottom-color: var(--cos-gold, #8B692F);
}
.cos-acc-tab:hover:not(.is-active) { color: var(--cos-ink); }

/* WooCommerce notices inside the card */
.cos-acc-notices ul.woocommerce-error {
  list-style: none;
  background: #fdf3f3;
  border-left: 3px solid var(--cos-error, #A13B3B);
  padding: 12px 16px;
  margin: 0;
}
.cos-acc-notices ul.woocommerce-error li {
  font-size: 12px;
  color: var(--cos-error, #A13B3B);
  padding: 2px 0;
}
.cos-acc-notices .woocommerce-message {
  background: #f3faf6;
  border-left: 3px solid var(--cos-success, #2D6A57);
  padding: 12px 16px;
  font-size: 12px;
  color: var(--cos-success, #2D6A57);
}

/* Form panels */
.cos-acc-panels { padding: 32px 36px 40px; }
.cos-acc-panel { display: none; }
.cos-acc-panel.is-active { display: block; }

/* Fields */
.cos-acc-field { margin-bottom: 18px; }
.cos-acc-label {
  display: block;
  font-size: 9px;
  font-weight: 600;
  letter-spacing: .16em;
  text-transform: uppercase;
  color: var(--cos-slate);
  margin-bottom: 7px;
}
/* Override any WooCommerce/TT5 input styles */
.cos-acc-input,
.cos-acc-input[type="text"],
.cos-acc-input[type="email"],
.cos-acc-input[type="password"] {
  display: block !important;
  width: 100% !important;
  padding: 12px 14px !important;
  border: 1px solid var(--cos-stone, #E5DED4) !important;
  border-radius: 0 !important;
  background: #fff !important;
  font-size: 13px !important;
  font-weight: 300 !important;
  color: var(--cos-ink) !important;
  font-family: var(--cos-body) !important;
  outline: none !important;
  box-shadow: none !important;
  transition: border-color .2s !important;
  -webkit-appearance: none !important;
  appearance: none !important;
  height: auto !important;
  min-height: 44px !important;
  line-height: 1.5 !important;
}
.cos-acc-input:focus,
.cos-acc-input[type="text"]:focus,
.cos-acc-input[type="email"]:focus,
.cos-acc-input[type="password"]:focus {
  border-color: var(--cos-navy, #050505) !important;
  box-shadow: none !important;
  outline: none !important;
}
.cos-acc-input::placeholder { color: rgba(92, 102, 117, .38); }

.cos-acc-two-col { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }

.cos-acc-check-row {
  display: flex;
  align-items: center;
  gap: 10px;
  margin-top: 18px;
}
.cos-acc-check-row input[type="checkbox"] {
  width: 24px;
  height: 24px;
  flex-shrink: 0;
  accent-color: var(--cos-navy);
  cursor: pointer;
}
.cos-acc-check-label {
  font-size: 12px;
  font-weight: 300;
  color: var(--cos-slate);
  cursor: pointer;
}
.cos-acc-consent {
  display: flex;
  align-items: flex-start;
  gap: 10px;
  margin-top: 18px;
  font-size: 11px;
  font-weight: 300;
  color: var(--cos-slate);
  line-height: 1.65;
}
.cos-acc-consent input[type="checkbox"] {
  width: 24px;
  height: 24px;
  margin-top: 2px;
  flex-shrink: 0;
  accent-color: var(--cos-navy);
}
.cos-acc-consent a { color: var(--cos-navy); text-decoration: underline; text-underline-offset: 2px; }

.cos-acc-submit {
  display: block !important;
  width: 100% !important;
  padding: 16px !important;
  min-height: 52px !important;
  box-sizing: border-box !important;
  background: var(--hx-ink, #1C1D1F) !important;
  color: #fff !important;
  font-size: 12.5px !important;
  font-weight: 500 !important;
  letter-spacing: .16em !important;
  text-transform: uppercase !important;
  border: none !important;
  cursor: pointer !important;
  font-family: var(--cos-body) !important;
  transition: background .2s !important;
  margin-top: 26px !important;
  border-radius: 0 !important;
}
.cos-acc-submit:hover { background: #2B2C2F !important; }

/* Card bottom strip */
.cos-acc-card-foot {
  padding: 18px 36px 24px;
  text-align: center;
  border-top: 1px solid var(--cos-stone, #E5DED4);
}
.cos-acc-forgot {
  font-size: 11px;
  font-weight: 400;
  color: var(--cos-slate);
  text-decoration: none;
  transition: color .2s;
  display: block;
  min-height: 24px;
  line-height: 24px;
  margin-bottom: 14px;
}
.cos-acc-forgot:hover { color: var(--cos-navy); }
.cos-acc-security {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 6px;
  font-size: 9px;
  font-weight: 600;
  letter-spacing: .16em;
  text-transform: uppercase;
  color: var(--cos-slate);
}
.cos-acc-security svg { width: 11px; height: 11px; flex-shrink: 0; color: var(--cos-gold-l, #B79A5B); }

/* ═══════════════════════════════════════════════
   LOGGED-IN — dashboard
═══════════════════════════════════════════════ */
.cos-acc-banner {
  background: var(--cos-navy, #050505);
  padding: 48px 24px 44px;
}
.cos-acc-banner-inner {
  max-width: 1080px;
  margin: 0 auto;
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 24px;
  flex-wrap: wrap;
}
.cos-acc-welcome-label {
  font-size: 9px;
  font-weight: 600;
  letter-spacing: .28em;
  text-transform: uppercase;
  color: var(--cos-gold-l, #B79A5B);
  margin-bottom: 8px;
}
.cos-acc-welcome-name {
  font-family: var(--cos-display);
  font-style: italic;
  font-weight: 300;
  font-size: clamp(26px, 3vw, 40px);
  color: #fff;
  line-height: 1.1;
}
.cos-acc-logout {
  padding: 11px 26px;
  border: 1px solid rgba(255,255,255,.18);
  color: rgba(255,255,255,.6);
  font-size: 9px;
  font-weight: 600;
  letter-spacing: .18em;
  text-transform: uppercase;
  text-decoration: none;
  transition: border-color .2s, color .2s;
  white-space: nowrap;
  display: inline-block;
}
.cos-acc-logout:hover { border-color: rgba(255,255,255,.5); color: #fff; }

.cos-acc-body {
  max-width: 1080px;
  margin: 0 auto;
  padding: 44px 24px 88px;
}
.cos-acc-grid {
  display: grid;
  grid-template-columns: 1fr;
  gap: 2px;
}

/* Dashboard cards */
.cos-acc-dc {
  background: #fff;
  border-top: 2px solid var(--cos-gold, #8B692F);
}
.cos-acc-dc-head {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 22px 28px;
  border-bottom: 1px solid var(--cos-stone, #E5DED4);
}
.cos-acc-dc-title {
  font-size: 9px;
  font-weight: 700;
  letter-spacing: .22em;
  text-transform: uppercase;
  color: var(--cos-ink);
}
.cos-acc-dc-link {
  font-size: 9px;
  font-weight: 600;
  letter-spacing: .12em;
  text-transform: uppercase;
  color: var(--cos-gold);
  text-decoration: none;
  transition: color .2s;
  display: inline-flex;
  align-items: center;
  gap: 5px;
  min-height: 24px;
  padding: 6px 4px;
  margin: -6px -4px;
}
.cos-acc-dc-link:hover { color: var(--cos-navy); }
.cos-acc-dc-link::after { content: '→'; font-style: normal; }
.cos-acc-dc-body { padding: 22px 28px; }

/* Orders list */
.cos-acc-order {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 12px;
  padding: 13px 0;
  border-bottom: 1px solid var(--cos-stone, #E5DED4);
}
.cos-acc-order:last-child { border-bottom: none; }
.cos-acc-order-id { font-size: 12px; font-weight: 500; color: var(--cos-ink); }
.cos-acc-order-meta { font-size: 11px; font-weight: 300; color: var(--cos-slate); margin-top: 2px; }
.cos-acc-order-badge {
  font-size: 8px;
  font-weight: 700;
  letter-spacing: .1em;
  text-transform: uppercase;
  padding: 4px 9px;
  border: 1px solid currentColor;
  white-space: nowrap;
  flex-shrink: 0;
}
.cos-acc-order-badge.s-completed   { color: var(--cos-teal, #46656B); }
.cos-acc-order-badge.s-processing  { color: var(--cos-gold, #8B692F); }
.cos-acc-order-badge.s-on-hold,
.cos-acc-order-badge.s-pending-payment { color: var(--cos-slate); }
.cos-acc-order-badge.s-cancelled,
.cos-acc-order-badge.s-failed      { color: var(--cos-error, #A13B3B); }

/* Address block */
.cos-acc-addr {
  font-size: 13px;
  font-weight: 300;
  color: var(--cos-slate);
  line-height: 1.9;
  font-style: normal;
}
.cos-acc-addr-name { font-weight: 500; color: var(--cos-ink); display: block; }

/* Account detail rows */
.cos-acc-info {
  display: flex;
  align-items: baseline;
  gap: 12px;
  padding: 12px 0;
  border-bottom: 1px solid var(--cos-stone, #E5DED4);
}
.cos-acc-info:last-child { border-bottom: none; }
.cos-acc-info-key {
  font-size: 9px;
  font-weight: 600;
  letter-spacing: .14em;
  text-transform: uppercase;
  color: var(--cos-slate);
  flex-shrink: 0;
  width: 84px;
}
.cos-acc-info-val {
  font-size: 13px;
  font-weight: 300;
  color: var(--cos-ink);
  word-break: break-all;
}

.cos-acc-empty {
  font-size: 13px;
  font-weight: 300;
  color: var(--cos-slate);
  line-height: 1.75;
}
.cos-acc-empty a { color: var(--cos-navy); text-decoration: underline; text-underline-offset: 2px; }

/* ── Responsive ── */
@media (min-width: 900px) {
  .cos-acc-grid { grid-template-columns: repeat(3, 1fr); }
}
@media (min-width: 640px) and (max-width: 899px) {
  .cos-acc-grid { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 860px) {
  .cos-acc-layout { flex-direction: column; align-items: stretch; gap: 0; }
  .cos-acc-brand { padding: 48px 0 40px; text-align: center; overflow: visible; }
  .cos-acc-brand-bg { display: none; }
  .cos-acc-brand-line { margin: 0 auto 20px; }
  .cos-acc-brand-sub { margin-left: auto; margin-right: auto; }
  .cos-acc-perk-sep { margin: 0 auto 20px; }
  .cos-acc-brand-perks { flex-direction: row; flex-wrap: wrap; justify-content: center; }
  .cos-acc-card-col { flex: none; width: 100%; max-width: 480px; margin: 0 auto; }
  .cos-acc-page { align-items: flex-start; }
}
@media (max-width: 460px) {
  .cos-acc-panels { padding: 24px 20px 28px; }
  .cos-acc-card-foot { padding: 16px 20px 22px; }
  .cos-acc-two-col { grid-template-columns: 1fr; }
}
/* Suppress WooCommerce blocktheme form resets on this page */
#cos-account .cos-acc-input { all: unset; }
#cos-account .cos-acc-input,
#cos-account .cos-acc-input[type="text"],
#cos-account .cos-acc-input[type="email"],
#cos-account .cos-acc-input[type="password"] {
  box-sizing: border-box !important;
  display: block !important;
  width: 100% !important;
  min-height: 44px !important;
  padding: 12px 14px !important;
  border: 1px solid var(--cos-stone, #E5DED4) !important;
  border-radius: 0 !important;
  background: #fff !important;
  font-size: 13px !important;
  font-weight: 300 !important;
  color: var(--cos-ink, #111111) !important;
  font-family: var(--cos-body, sans-serif) !important;
  line-height: 1.5 !important;
  outline: none !important;
  box-shadow: none !important;
  transition: border-color .2s !important;
  -webkit-appearance: none !important;
  appearance: none !important;
  margin: 0 !important;
}
#cos-account .cos-acc-input:focus,
#cos-account .cos-acc-input[type="text"]:focus,
#cos-account .cos-acc-input[type="email"]:focus,
#cos-account .cos-acc-input[type="password"]:focus {
  border-color: var(--cos-navy, #050505) !important;
  box-shadow: none !important;
}
</style>

<main id="cos-main-content">
<div id="cos-account">
<div style="height:var(--cos-nav-h,80px)"></div>

<?php if ( $logged_in ) : ?>

<!-- ═══════ LOGGED-IN ═══════ -->
<div class="cos-acc-banner">
  <div class="cos-acc-banner-inner">
    <div>
      <p class="cos-acc-welcome-label">Welcome back to your ritual</p>
      <p class="cos-acc-welcome-name"><?php echo esc_html( $first_name ); ?></p>
    </div>
    <a href="<?php echo esc_url( $logout_url ); ?>" class="cos-acc-logout">Sign out</a>
  </div>
</div>

<div class="cos-acc-body">
  <div class="cos-acc-grid">

    <!-- Orders -->
    <div class="cos-acc-dc">
      <div class="cos-acc-dc-head">
        <span class="cos-acc-dc-title">My Orders</span>
        <a href="<?php echo esc_url( $orders_url ); ?>" class="cos-acc-dc-link">View all</a>
      </div>
      <div class="cos-acc-dc-body">
        <?php if ( ! empty( $orders ) ) :
          foreach ( $orders as $o ) :
            $s = $o->get_status();
        ?>
        <div class="cos-acc-order">
          <div>
            <div class="cos-acc-order-id">Order #<?php echo esc_html( $o->get_id() ); ?></div>
            <div class="cos-acc-order-meta">
              <?php echo $o->get_date_created() ? esc_html( $o->get_date_created()->date_i18n( 'j M Y' ) ) : '—'; ?>
              &middot; <?php echo wp_kses_post( $o->get_formatted_order_total() ); ?>
            </div>
          </div>
          <span class="cos-acc-order-badge s-<?php echo esc_attr( $s ); ?>"><?php echo esc_html( wc_get_order_status_name( $s ) ); ?></span>
        </div>
        <?php endforeach; else : ?>
        <p class="cos-acc-empty">No Caviar of Switzerland orders yet.<br><a href="/shop/">Explore the four-step ritual →</a></p>
        <?php endif; ?>
      </div>
    </div>

    <!-- Address -->
    <div class="cos-acc-dc">
      <div class="cos-acc-dc-head">
        <span class="cos-acc-dc-title">Delivery Address</span>
        <a href="<?php echo esc_url( $addr_url ); ?>" class="cos-acc-dc-link">Edit</a>
      </div>
      <div class="cos-acc-dc-body">
        <?php if ( $has_addr ) :
          $bn = trim( ( $billing['first_name'] ?? '' ) . ' ' . ( $billing['last_name'] ?? '' ) );
          $countries = WC()->countries->get_countries();
        ?>
        <address class="cos-acc-addr">
          <?php if ( $bn ) : ?><span class="cos-acc-addr-name"><?php echo esc_html( $bn ); ?></span><?php endif; ?>
          <?php if ( ! empty( $billing['company'] ) )   echo esc_html( $billing['company'] ) . '<br>'; ?>
          <?php if ( ! empty( $billing['address_1'] ) ) echo esc_html( $billing['address_1'] ) . '<br>'; ?>
          <?php if ( ! empty( $billing['address_2'] ) ) echo esc_html( $billing['address_2'] ) . '<br>'; ?>
          <?php
          $city_line = trim( implode( ' ', array_filter( [ $billing['city'] ?? '', $billing['postcode'] ?? '' ] ) ) );
          if ( $city_line ) echo esc_html( $city_line ) . '<br>';
          if ( ! empty( $billing['country'] ) ) echo esc_html( $countries[ $billing['country'] ] ?? $billing['country'] );
          ?>
        </address>
        <?php else : ?>
        <p class="cos-acc-empty">No address saved yet.<br><a href="<?php echo esc_url( $addr_url ); ?>">Add a delivery address →</a></p>
        <?php endif; ?>
      </div>
    </div>

    <!-- Account details -->
    <div class="cos-acc-dc">
      <div class="cos-acc-dc-head">
        <span class="cos-acc-dc-title">Account Details</span>
        <a href="<?php echo esc_url( $detail_url ); ?>" class="cos-acc-dc-link">Edit</a>
      </div>
      <div class="cos-acc-dc-body">
        <div class="cos-acc-info">
          <span class="cos-acc-info-key">Name</span>
          <span class="cos-acc-info-val"><?php echo esc_html( trim( $me->first_name . ' ' . $me->last_name ) ?: $me->display_name ); ?></span>
        </div>
        <div class="cos-acc-info">
          <span class="cos-acc-info-key">Email</span>
          <span class="cos-acc-info-val"><?php echo esc_html( $me->user_email ); ?></span>
        </div>
        <div class="cos-acc-info">
          <span class="cos-acc-info-key">Password</span>
          <span class="cos-acc-info-val" style="letter-spacing:.1em;color:var(--cos-slate)">••••••••</span>
        </div>
      </div>
    </div>

  </div>
</div>

<?php else : /* ═══════ LOGGED-OUT ═══════ */ ?>

<?php
ob_start();
if ( function_exists( 'wc_print_notices' ) ) wc_print_notices();
$notices = ob_get_clean();

$active = ( ! empty( $_POST['register'] ) || ( ! empty( $_GET['action'] ) && $_GET['action'] === 'register' ) ) ? 'register' : 'login';
?>

<div class="cos-acc-page">
  <div class="cos-acc-layout">

    <!-- Brand panel (visible on wide screens) -->
    <div class="cos-acc-brand">
      <span class="cos-acc-brand-line"></span>
      <p class="cos-acc-brand-eyebrow">Caviar of Switzerland</p>
      <h1 class="cos-acc-brand-heading">Your ritual,<br>your account.</h1>
      <p class="cos-acc-brand-sub">Manage your orders, update your details, and access personalised ritual guidance from your Caviar of Switzerland concierge.</p>
      <div class="cos-acc-perk-sep"></div>
      <div class="cos-acc-brand-perks">
        <span class="cos-acc-brand-perk">
          <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M10 2.5L3 5.7v5.1c0 3.8 2.9 6.8 7 7.4 4.1-.6 7-3.6 7-7.4V5.7L10 2.5z"/><path d="M7 10l2 2 4-4"/></svg>
          Order history
        </span>
        <span class="cos-acc-brand-perk">
          <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="2" y="6.5" width="11" height="7" rx="1"/><path d="M13 9.5h1.5a2 2 0 0 1 0 4H13"/><circle cx="5.5" cy="15" r="1.5"/><circle cx="10.5" cy="15" r="1.5"/></svg>
          Saved addresses
        </span>
        <span class="cos-acc-brand-perk">
          <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="10" cy="7.5" r="3.5"/><path d="M4 17c0-3 2.7-5.5 6-5.5s6 2.5 6 5.5"/></svg>
          Ritual profile
        </span>
      </div>
    </div>

    <!-- Form card -->
    <div class="cos-acc-card-col">
      <div class="cos-acc-card">

        <div class="cos-acc-tabs" role="tablist">
          <button class="cos-acc-tab <?php echo $active === 'login' ? 'is-active' : ''; ?>"
            role="tab" id="cos-t-login"
            aria-selected="<?php echo $active === 'login' ? 'true' : 'false'; ?>"
            aria-controls="cos-p-login"
            onclick="cosTab('login')">Sign in</button>
          <button class="cos-acc-tab <?php echo $active === 'register' ? 'is-active' : ''; ?>"
            role="tab" id="cos-t-register"
            aria-selected="<?php echo $active === 'register' ? 'true' : 'false'; ?>"
            aria-controls="cos-p-register"
            onclick="cosTab('register')">Register</button>
        </div>

        <?php if ( $notices ) : ?>
        <div class="cos-acc-notices"><?php echo $notices; ?></div>
        <?php endif; ?>

        <div class="cos-acc-panels">

          <!-- Sign-in panel -->
          <div class="cos-acc-panel <?php echo $active === 'login' ? 'is-active' : ''; ?>"
            id="cos-p-login" role="tabpanel" aria-labelledby="cos-t-login">
            <form method="post" action="<?php echo esc_url( get_permalink() ); ?>" novalidate>
              <div class="cos-acc-field">
                <label class="cos-acc-label" for="cos-l-email">Email address</label>
                <input class="cos-acc-input" type="email" id="cos-l-email" name="username"
                  autocomplete="email" required
                  value="<?php echo esc_attr( wp_unslash( $_POST['username'] ?? '' ) ); ?>"
                  placeholder="your@email.com"/>
              </div>
              <div class="cos-acc-field">
                <label class="cos-acc-label" for="cos-l-pass">Password</label>
                <input class="cos-acc-input" type="password" id="cos-l-pass" name="password"
                  autocomplete="current-password" required placeholder="••••••••"/>
              </div>
              <label class="cos-acc-check-row">
                <input type="checkbox" name="rememberme" value="forever"/>
                <span class="cos-acc-check-label">Keep me signed in</span>
              </label>
              <input type="hidden" name="redirect" value="<?php echo esc_url( get_permalink() ); ?>"/>
              <?php wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ); ?>
              <button class="cos-acc-submit" type="submit" name="login" value="Sign in">Sign in</button>
            </form>
          </div>

          <!-- Register panel -->
          <div class="cos-acc-panel <?php echo $active === 'register' ? 'is-active' : ''; ?>"
            id="cos-p-register" role="tabpanel" aria-labelledby="cos-t-register">
            <form method="post" action="<?php echo esc_url( get_permalink() ); ?>" novalidate>
              <div class="cos-acc-two-col">
                <div class="cos-acc-field">
                  <label class="cos-acc-label" for="cos-r-fn">First name</label>
                  <input class="cos-acc-input" type="text" id="cos-r-fn" name="first_name"
                    autocomplete="given-name"
                    value="<?php echo esc_attr( wp_unslash( $_POST['first_name'] ?? '' ) ); ?>"
                    placeholder="Sophie"/>
                </div>
                <div class="cos-acc-field">
                  <label class="cos-acc-label" for="cos-r-ln">Last name</label>
                  <input class="cos-acc-input" type="text" id="cos-r-ln" name="last_name"
                    autocomplete="family-name"
                    value="<?php echo esc_attr( wp_unslash( $_POST['last_name'] ?? '' ) ); ?>"
                    placeholder="Blanc"/>
                </div>
              </div>
              <div class="cos-acc-field">
                <label class="cos-acc-label" for="cos-r-email">Email address</label>
                <input class="cos-acc-input" type="email" id="cos-r-email" name="email"
                  autocomplete="email" required
                  value="<?php echo esc_attr( wp_unslash( $_POST['email'] ?? '' ) ); ?>"
                  placeholder="your@email.com"/>
              </div>
              <div class="cos-acc-field">
                <label class="cos-acc-label" for="cos-r-pass">Password</label>
                <input class="cos-acc-input" type="password" id="cos-r-pass" name="password"
                  autocomplete="new-password" required minlength="8"
                  placeholder="Min. 8 characters"/>
              </div>
              <label class="cos-acc-consent">
                <input type="checkbox" name="gdpr_consent" required/>
                I agree to the <a href="/terms/" target="_blank">Terms &amp; Conditions</a> and <a href="/privacy/" target="_blank">Privacy Policy</a>.
              </label>
              <input type="hidden" name="redirect" value="<?php echo esc_url( get_permalink() ); ?>"/>
              <?php wp_nonce_field( 'woocommerce-register', 'woocommerce-register-nonce' ); ?>
              <button class="cos-acc-submit" type="submit" name="register" value="Register">Create account</button>
            </form>
          </div>

        </div><!-- /panels -->

        <div class="cos-acc-card-foot">
          <a href="<?php echo esc_url( wp_lostpassword_url( get_permalink() ) ); ?>" class="cos-acc-forgot">Forgot your password?</a>
          <div class="cos-acc-security">
            <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="5" y="9" width="10" height="8" rx="1"/><path d="M7 9V7a3 3 0 0 1 6 0v2"/></svg>
            Encrypted &amp; secure
          </div>
        </div>

      </div><!-- /card -->
    </div><!-- /card-col -->

  </div><!-- /layout -->
</div><!-- /page -->

<?php endif; ?>
</div><!-- #cos-account -->
</main>

<script nonce="<?php echo esc_attr( cos_csp_nonce() ); ?>">
function cosTab(tab) {
  ['login','register'].forEach(function(t) {
    var btn   = document.getElementById('cos-t-' + t);
    var panel = document.getElementById('cos-p-' + t);
    var on    = (t === tab);
    if (btn)   { btn.classList.toggle('is-active', on); btn.setAttribute('aria-selected', on); }
    if (panel) { panel.classList.toggle('is-active', on); }
  });
}
</script>

<?php wp_footer(); ?>
</body>
</html>
