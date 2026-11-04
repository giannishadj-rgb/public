<?php
/**
 * COS — Account Page
 *
 * Typeset 1 Nov 2026: this file owns the account typography (checkout.css and
 * typeset-site.css no longer restyle it). Roles, all on the hx tokens:
 *   title    Cormorant 300 capitals (sitewide h1 rule, display size)
 *   body     Inter 400 16px / 1.7   — reading copy, field values, addresses
 *   detail   Inter 400 14px / 1.55  — secondary metadata, field labels, notices
 *   label    Inter 500 12px capitals .2em — card titles, keys, status
 *   action   Inter 500 12.5px capitals .16em — tabs, buttons, links; 44px+ targets
 * Monochrome: graphite ink, muted graphite and hairlines; no gold, no shadow.
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
/* ─── Roles ─── */
#cos-account {
  --acc-ink: var(--hx-ink, #1C1D1F);
  --acc-text: var(--hx-text, #3A3B3D);
  --acc-muted: var(--hx-muted, #5C5C5A);
  --acc-line: var(--hx-line, rgba(28,29,31,.14));
  --acc-line-strong: var(--hx-line-strong, rgba(28,29,31,.28));
  --acc-ground: var(--hx-pearl, #F8F8F8);
  --acc-field: #FFFFFF;
  --acc-error: #A13B3B;
  --acc-ease: var(--hx-ease-soft, cubic-bezier(.33,0,.2,1));
  --acc-dur: var(--hx-dur-ui, 320ms);
  font-family: var(--hx-sans, 'Inter', system-ui, sans-serif);
  font-size: 1rem;
  font-weight: 400;
  line-height: 1.7;
  color: var(--acc-text);
  background: var(--acc-ground);
  min-height: 100vh;
}
#cos-account * { box-sizing: border-box; }
#cos-account :is(h1, h2, h3, p, ul, li, figure) { margin: 0; padding: 0; list-style: none; }
#cos-account ::selection { background: var(--hx-selection-bg, #E3D8C5); color: var(--acc-ink); }
#cos-account :is(a, button, input, [role="tab"]):focus-visible {
  outline: var(--hx-focus-width, 2px) solid var(--hx-focus-color, #1C1D1F);
  outline-offset: 3px;
}

/* label: 12px tracked capitals */
#cos-account :is(.cos-acc-dc-title, .cos-acc-info-key, .cos-acc-order-status, .cos-acc-security) {
  font-family: var(--hx-sans, 'Inter', system-ui, sans-serif);
  font-size: .75rem;
  font-weight: 500;
  line-height: 1.5;
  letter-spacing: .2em;
  text-transform: uppercase;
}
/* action: 12.5px tracked capitals, 44px or taller */
#cos-account :is(.cos-acc-tab, .cos-acc-submit, .cos-acc-logout, .cos-acc-dc-link) {
  font-family: var(--hx-sans, 'Inter', system-ui, sans-serif);
  font-size: .78125rem;
  font-weight: 500;
  line-height: 1.3;
  letter-spacing: .16em;
  text-transform: uppercase;
  min-height: 44px;
}
/* detail: 14px */
#cos-account :is(.cos-acc-label, .cos-acc-check-label, .cos-acc-consent, .cos-acc-forgot, .cos-acc-order-meta, .cos-acc-brand-perk, .cos-acc-notices) {
  font-size: .875rem;
  line-height: 1.55;
}

/* ═══════════════════════════════════════════════
   SIGNED OUT — brand panel + form
═══════════════════════════════════════════════ */
.cos-acc-page {
  min-height: calc(100vh - var(--cos-nav-h, 80px));
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 64px 24px 88px;
}
.cos-acc-layout {
  display: flex;
  align-items: center;
  gap: 80px;
  width: 100%;
  max-width: 960px;
}

.cos-acc-brand { flex: 1; min-width: 0; }
.cos-acc-brand-line {
  display: block;
  width: 32px;
  height: 1px;
  background: var(--acc-line-strong);
  margin-bottom: 28px;
}
#cos-account .cos-acc-brand-heading { margin-bottom: 24px; }
#cos-account .cos-acc-brand-sub {
  color: var(--acc-text);
  margin-bottom: 32px;
  max-width: 30em;
}
.cos-acc-perk-sep {
  width: 32px;
  height: 1px;
  background: var(--acc-line-strong);
  margin-bottom: 24px;
}
.cos-acc-brand-perks {
  display: flex;
  flex-direction: column;
  gap: 12px;
}
#cos-account .cos-acc-brand-perk {
  display: flex;
  align-items: center;
  gap: 12px;
  color: var(--acc-text);
}
.cos-acc-brand-perk svg {
  width: 16px;
  height: 16px;
  flex-shrink: 0;
  color: var(--acc-muted);
}

.cos-acc-card-col { flex: 0 0 440px; width: 440px; }
.cos-acc-card {
  background: var(--acc-ground);
  border: 1px solid var(--acc-line);
}

/* Tabs */
.cos-acc-tabs { display: flex; }
#cos-account .cos-acc-tab {
  flex: 1;
  min-height: 56px;
  padding: 0 12px;
  color: var(--acc-muted);
  background: transparent;
  border: 0;
  border-bottom: 1px solid var(--acc-line);
  border-radius: 0;
  cursor: pointer;
  transition: color var(--acc-dur) var(--acc-ease), border-color var(--acc-dur) var(--acc-ease);
}
#cos-account .cos-acc-tab.is-active {
  color: var(--acc-ink);
  border-bottom-color: var(--acc-ink);
}
#cos-account .cos-acc-tab:hover:not(.is-active) { color: var(--acc-ink); }
#cos-account .cos-acc-tab:focus-visible { outline-offset: -2px; }

/* WooCommerce notices inside the card */
#cos-account .cos-acc-notices { padding: 24px 36px 0; }
#cos-account .cos-acc-notices :is(ul.woocommerce-error, .woocommerce-message, .woocommerce-info) {
  list-style: none;
  margin: 0;
  padding: 12px 0 0;
  border: 0;
  border-top: 1px solid currentColor;
  background: none;
  font-size: inherit;
  line-height: inherit;
}
#cos-account .cos-acc-notices :is(ul.woocommerce-error, ul.woocommerce-error li) { color: var(--acc-error); }
#cos-account .cos-acc-notices :is(.woocommerce-message, .woocommerce-info) { color: var(--acc-ink); }
#cos-account .cos-acc-notices :is(ul.woocommerce-error, .woocommerce-message, .woocommerce-info)::before { content: none; }
#cos-account .cos-acc-notices li { padding: 2px 0; }
#cos-account .cos-acc-notices a { color: inherit; text-decoration: underline; text-underline-offset: 3px; }

/* Form panels */
.cos-acc-panels { padding: 32px 36px 40px; }
.cos-acc-panel { display: none; }
.cos-acc-panel.is-active { display: block; }

/* Fields */
.cos-acc-field { margin-bottom: 20px; }
#cos-account .cos-acc-label {
  display: block;
  font-weight: 500;
  color: var(--acc-ink);
  margin-bottom: 8px;
}
#cos-account .cos-acc-input {
  display: block;
  width: 100%;
  height: 52px;
  margin: 0;
  padding: 0 16px;
  border: 1px solid var(--acc-line-strong);
  border-radius: 0;
  background: var(--acc-field);
  font-family: var(--hx-sans, 'Inter', system-ui, sans-serif);
  font-size: 1rem;
  font-weight: 400;
  line-height: 1.5;
  color: var(--acc-ink);
  caret-color: var(--acc-ink);
  box-shadow: none;
  outline: none;
  -webkit-appearance: none;
  appearance: none;
  transition: border-color var(--acc-dur) var(--acc-ease), box-shadow var(--acc-dur) var(--acc-ease);
}
#cos-account .cos-acc-input:focus,
#cos-account .cos-acc-input:focus-visible {
  border-color: var(--acc-ink);
  box-shadow: inset 0 0 0 1px var(--acc-ink);
  outline: none;
}
#cos-account .cos-acc-input:user-invalid { border-color: var(--acc-error); }
#cos-account .cos-acc-input::placeholder { color: #7A7A77; opacity: 1; }

.cos-acc-two-col { display: grid; grid-template-columns: minmax(0,1fr) minmax(0,1fr); gap: 16px; }

#cos-account :is(.cos-acc-check-row, .cos-acc-consent) {
  display: flex;
  gap: 12px;
  min-height: 44px;
  margin-top: 16px;
  color: var(--acc-text);
  cursor: pointer;
}
#cos-account .cos-acc-check-row { align-items: center; }
#cos-account .cos-acc-consent { align-items: flex-start; padding-top: 10px; }
#cos-account :is(.cos-acc-check-row, .cos-acc-consent) input[type="checkbox"] {
  width: 20px;
  height: 20px;
  margin: 0;
  flex-shrink: 0;
  accent-color: var(--acc-ink);
  cursor: pointer;
}
#cos-account .cos-acc-consent input[type="checkbox"] { margin-top: 1px; }
#cos-account .cos-acc-consent a {
  color: var(--acc-ink);
  text-decoration: underline;
  text-decoration-color: var(--acc-line-strong);
  text-underline-offset: 4px;
}
#cos-account .cos-acc-consent a:hover { text-decoration-color: var(--acc-ink); }

#cos-account .cos-acc-submit {
  display: block;
  width: 100%;
  min-height: 52px;
  margin-top: 28px;
  padding: 0 24px;
  background: var(--acc-ink);
  color: var(--acc-ground);
  border: 0;
  border-radius: 0;
  cursor: pointer;
  transition: background-color var(--acc-dur) var(--acc-ease);
}
#cos-account .cos-acc-submit:hover { background: #2B2C2F; }

/* Card foot */
.cos-acc-card-foot {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 8px;
  padding: 12px 36px 24px;
  border-top: 1px solid var(--acc-line);
}
#cos-account .cos-acc-forgot {
  display: inline-flex;
  align-items: center;
  min-height: 44px;
  color: var(--acc-ink);
  text-decoration: underline;
  text-decoration-color: var(--acc-line-strong);
  text-underline-offset: 4px;
  transition: text-decoration-color var(--acc-dur) var(--acc-ease);
}
#cos-account .cos-acc-forgot:hover { text-decoration-color: var(--acc-ink); }
#cos-account .cos-acc-security {
  display: flex;
  align-items: center;
  gap: 8px;
  color: var(--acc-muted);
}
.cos-acc-security svg { width: 14px; height: 14px; flex-shrink: 0; }

/* ═══════════════════════════════════════════════
   SIGNED IN — dashboard
═══════════════════════════════════════════════ */
.cos-acc-banner {
  padding: 56px 24px 40px;
  border-bottom: 1px solid var(--acc-line);
}
.cos-acc-banner-inner {
  max-width: 1080px;
  margin: 0 auto;
  display: flex;
  align-items: flex-end;
  justify-content: space-between;
  gap: 24px;
  flex-wrap: wrap;
}
#cos-account .cos-acc-logout {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  padding: 0 24px;
  border: 1px solid var(--acc-line-strong);
  color: var(--acc-ink);
  text-decoration: none;
  white-space: nowrap;
  transition: border-color var(--acc-dur) var(--acc-ease), background-color var(--acc-dur) var(--acc-ease), color var(--acc-dur) var(--acc-ease);
}
#cos-account .cos-acc-logout:hover { background: var(--acc-ink); border-color: var(--acc-ink); color: var(--acc-ground); }

.cos-acc-body {
  max-width: calc(1080px + 48px);
  margin: 0 auto;
  padding: 48px 24px 96px;
}
.cos-acc-grid {
  display: grid;
  grid-template-columns: minmax(0,1fr);
  gap: 48px 40px;
}

/* Dashboard sections: a strong hairline, a label head, open content */
.cos-acc-dc { border-top: 1px solid var(--acc-line-strong); }
.cos-acc-dc-head {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 16px;
  min-height: 64px;
  border-bottom: 1px solid var(--acc-line);
}
#cos-account .cos-acc-dc-title { color: var(--acc-ink); }
#cos-account .cos-acc-dc-link {
  display: inline-flex;
  align-items: center;
  margin-right: -4px;
  padding: 0 4px;
  color: var(--acc-ink);
  text-decoration: none;
}
#cos-account .cos-acc-dc-link .hx-arrow { width: 14px; margin-left: 10px; }
#cos-account .cos-acc-dc-link:hover .hx-arrow { transform: translateX(4px); }
.cos-acc-dc-body { padding-top: 8px; }

/* Orders */
.cos-acc-order {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 16px;
  padding: 16px 0;
  border-bottom: 1px solid var(--acc-line);
}
.cos-acc-order:last-child { border-bottom: 0; }
#cos-account .cos-acc-order-id { font-weight: 500; color: var(--acc-ink); line-height: 1.5; }
#cos-account .cos-acc-order-meta { color: var(--acc-muted); font-variant-numeric: tabular-nums lining-nums; }
#cos-account .cos-acc-order-status {
  flex-shrink: 0;
  letter-spacing: .14em;
  color: var(--acc-muted);
  text-align: right;
}
#cos-account .cos-acc-order-status:is(.s-processing, .s-on-hold, .s-completed) { color: var(--acc-ink); }
#cos-account .cos-acc-order-status:is(.s-cancelled, .s-failed) { color: var(--acc-error); }

/* Address */
#cos-account .cos-acc-addr {
  padding-top: 8px;
  color: var(--acc-text);
  font-style: normal;
}
.cos-acc-addr-name { font-weight: 500; color: var(--acc-ink); display: block; }

/* Account details */
.cos-acc-info {
  display: flex;
  align-items: baseline;
  gap: 16px;
  padding: 12px 0;
  border-bottom: 1px solid var(--acc-line);
}
.cos-acc-info:last-child { border-bottom: 0; }
#cos-account .cos-acc-info-key {
  flex-shrink: 0;
  width: 7.5rem;
  letter-spacing: .14em;
  color: var(--acc-muted);
}
#cos-account .cos-acc-info-val { min-width: 0; color: var(--acc-ink); overflow-wrap: anywhere; }
#cos-account .cos-acc-info-val.is-masked { letter-spacing: .1em; color: var(--acc-muted); }

#cos-account .cos-acc-empty { padding-top: 8px; color: var(--acc-text); }
#cos-account .cos-acc-empty a {
  display: inline-flex;
  align-items: center;
  min-height: 44px;
  color: var(--acc-ink);
  text-decoration: underline;
  text-decoration-color: var(--acc-line-strong);
  text-underline-offset: 4px;
}
#cos-account .cos-acc-empty a:hover { text-decoration-color: var(--acc-ink); }

/* ── Responsive ── */
@media (min-width: 640px) {
  .cos-acc-grid { grid-template-columns: repeat(2, minmax(0,1fr)); }
}
@media (min-width: 900px) {
  .cos-acc-grid { grid-template-columns: repeat(3, minmax(0,1fr)); }
}
@media (max-width: 860px) {
  .cos-acc-layout { flex-direction: column; align-items: stretch; gap: 0; }
  .cos-acc-brand { padding: 32px 0 40px; text-align: center; }
  .cos-acc-brand-line { margin: 0 auto 24px; }
  #cos-account .cos-acc-brand-sub { margin-left: auto; margin-right: auto; }
  .cos-acc-perk-sep { margin: 0 auto 20px; }
  .cos-acc-brand-perks { flex-direction: row; flex-wrap: wrap; justify-content: center; gap: 12px 24px; }
  .cos-acc-card-col { flex: none; width: 100%; max-width: 480px; margin: 0 auto; }
  .cos-acc-page { align-items: flex-start; padding-top: 32px; }
}
@media (max-width: 460px) {
  .cos-acc-panels { padding: 24px 20px 28px; }
  #cos-account .cos-acc-notices { padding: 20px 20px 0; }
  .cos-acc-card-foot { padding: 8px 20px 20px; }
  .cos-acc-two-col { grid-template-columns: minmax(0,1fr); gap: 0; }
  .cos-acc-banner { padding: 40px 20px 32px; }
}
@media (prefers-reduced-motion: reduce) {
  #cos-account *, #cos-account .hx-arrow { transition: none; }
}
</style>

<main id="cos-main-content">
<div id="cos-account">
<div style="height:var(--cos-nav-h,80px)"></div>

<?php if ( $logged_in ) : ?>

<!-- ═══════ SIGNED IN ═══════ -->
<div class="cos-acc-banner">
  <div class="cos-acc-banner-inner">
    <h1 class="cos-acc-welcome-name">Welcome back, <?php echo esc_html( $first_name ); ?></h1>
    <a href="<?php echo esc_url( $logout_url ); ?>" class="cos-acc-logout">Sign out</a>
  </div>
</div>

<div class="cos-acc-body">
  <div class="cos-acc-grid">

    <!-- Orders -->
    <section class="cos-acc-dc" aria-labelledby="cos-acc-h-orders">
      <div class="cos-acc-dc-head">
        <h2 class="cos-acc-dc-title" id="cos-acc-h-orders">My orders</h2>
        <a href="<?php echo esc_url( $orders_url ); ?>" class="cos-acc-dc-link">View all<span class="hx-arrow" aria-hidden="true"></span></a>
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
          <span class="cos-acc-order-status s-<?php echo esc_attr( $s ); ?>"><?php echo esc_html( wc_get_order_status_name( $s ) ); ?></span>
        </div>
        <?php endforeach; else : ?>
        <p class="cos-acc-empty">No Caviar of Switzerland orders yet.<br><a href="/shop/">Explore the four-step ritual</a></p>
        <?php endif; ?>
      </div>
    </section>

    <!-- Address -->
    <section class="cos-acc-dc" aria-labelledby="cos-acc-h-address">
      <div class="cos-acc-dc-head">
        <h2 class="cos-acc-dc-title" id="cos-acc-h-address">Delivery address</h2>
        <a href="<?php echo esc_url( $addr_url ); ?>" class="cos-acc-dc-link" aria-label="Edit delivery address">Edit<span class="hx-arrow" aria-hidden="true"></span></a>
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
        <p class="cos-acc-empty">No address saved yet.<br><a href="<?php echo esc_url( $addr_url ); ?>">Add a delivery address</a></p>
        <?php endif; ?>
      </div>
    </section>

    <!-- Account details -->
    <section class="cos-acc-dc" aria-labelledby="cos-acc-h-details">
      <div class="cos-acc-dc-head">
        <h2 class="cos-acc-dc-title" id="cos-acc-h-details">Account details</h2>
        <a href="<?php echo esc_url( $detail_url ); ?>" class="cos-acc-dc-link" aria-label="Edit account details">Edit<span class="hx-arrow" aria-hidden="true"></span></a>
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
          <span class="cos-acc-info-val is-masked" aria-label="Hidden">••••••••</span>
        </div>
      </div>
    </section>

  </div>
</div>

<?php else : /* ═══════ SIGNED OUT ═══════ */ ?>

<?php
ob_start();
if ( function_exists( 'wc_print_notices' ) ) wc_print_notices();
$notices = ob_get_clean();

$active = ( ! empty( $_POST['register'] ) || ( ! empty( $_GET['action'] ) && $_GET['action'] === 'register' ) ) ? 'register' : 'login';
?>

<div class="cos-acc-page">
  <div class="cos-acc-layout">

    <!-- Brand panel -->
    <div class="cos-acc-brand">
      <span class="cos-acc-brand-line"></span>
      <h1 class="cos-acc-brand-heading">Your ritual,<br>your account.</h1>
      <p class="cos-acc-brand-sub">Manage your orders, update your details, and access personalised ritual guidance from your Caviar of Switzerland concierge.</p>
      <div class="cos-acc-perk-sep"></div>
      <div class="cos-acc-brand-perks">
        <span class="cos-acc-brand-perk">
          <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M10 2.5L3 5.7v5.1c0 3.8 2.9 6.8 7 7.4 4.1-.6 7-3.6 7-7.4V5.7L10 2.5z"/><path d="M7 10l2 2 4-4"/></svg>
          Order history
        </span>
        <span class="cos-acc-brand-perk">
          <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="2" y="6.5" width="11" height="7" rx="1"/><path d="M13 9.5h1.5a2 2 0 0 1 0 4H13"/><circle cx="5.5" cy="15" r="1.5"/><circle cx="10.5" cy="15" r="1.5"/></svg>
          Saved addresses
        </span>
        <span class="cos-acc-brand-perk">
          <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="10" cy="7.5" r="3.5"/><path d="M4 17c0-3 2.7-5.5 6-5.5s6 2.5 6 5.5"/></svg>
          Ritual profile
        </span>
      </div>
    </div>

    <!-- Form card -->
    <div class="cos-acc-card-col">
      <div class="cos-acc-card">

        <div class="cos-acc-tabs" role="tablist" aria-label="Account">
          <button class="cos-acc-tab <?php echo $active === 'login' ? 'is-active' : ''; ?>"
            type="button" role="tab" id="cos-t-login"
            aria-selected="<?php echo $active === 'login' ? 'true' : 'false'; ?>"
            aria-controls="cos-p-login"
            tabindex="<?php echo $active === 'login' ? '0' : '-1'; ?>">Sign in</button>
          <button class="cos-acc-tab <?php echo $active === 'register' ? 'is-active' : ''; ?>"
            type="button" role="tab" id="cos-t-register"
            aria-selected="<?php echo $active === 'register' ? 'true' : 'false'; ?>"
            aria-controls="cos-p-register"
            tabindex="<?php echo $active === 'register' ? '0' : '-1'; ?>">Register</button>
        </div>

        <?php if ( $notices ) : ?>
        <div class="cos-acc-notices" role="alert"><?php echo $notices; ?></div>
        <?php endif; ?>

        <div class="cos-acc-panels">

          <!-- Sign-in panel -->
          <div class="cos-acc-panel <?php echo $active === 'login' ? 'is-active' : ''; ?>"
            id="cos-p-login" role="tabpanel" aria-labelledby="cos-t-login">
            <form method="post" action="<?php echo esc_url( get_permalink() ); ?>">
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
            <form method="post" action="<?php echo esc_url( get_permalink() ); ?>">
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
                <span>I agree to the <a href="/terms/" target="_blank">Terms &amp; Conditions</a> and <a href="/privacy/" target="_blank">Privacy Policy</a>.</span>
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
            <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="5" y="9" width="10" height="8" rx="1"/><path d="M7 9V7a3 3 0 0 1 6 0v2"/></svg>
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
(function () {
  var tabs = Array.prototype.slice.call(document.querySelectorAll('#cos-account [role="tab"]'));
  if (!tabs.length) return;
  function select(tab, focus) {
    tabs.forEach(function (t) {
      var on = (t === tab);
      var panel = document.getElementById(t.getAttribute('aria-controls'));
      t.classList.toggle('is-active', on);
      t.setAttribute('aria-selected', on ? 'true' : 'false');
      t.tabIndex = on ? 0 : -1;
      if (panel) panel.classList.toggle('is-active', on);
    });
    if (focus) tab.focus();
  }
  tabs.forEach(function (t, i) {
    t.addEventListener('click', function () { select(t, false); });
    t.addEventListener('keydown', function (e) {
      var next = null;
      if (e.key === 'ArrowRight') next = tabs[(i + 1) % tabs.length];
      else if (e.key === 'ArrowLeft') next = tabs[(i - 1 + tabs.length) % tabs.length];
      else if (e.key === 'Home') next = tabs[0];
      else if (e.key === 'End') next = tabs[tabs.length - 1];
      if (next) { e.preventDefault(); select(next, true); }
    });
  });
})();
</script>

<?php wp_footer(); ?>
</body>
</html>
