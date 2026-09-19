<?php
/**
 * COS Child Theme — Navigation
 *
 * Site-chrome CSS (hero/nav/footer/mobile-menu, kept as one block to avoid
 * any cascade-order risk from splitting it), the header/nav/mobile-menu
 * markup, and the footer. The footer calls cos_child_newsletter_form() /
 * cos_child_newsletter_form_script() from inc/forms.php for the newsletter
 * signup block.
 */

/* ─── Global CSS (design tokens + nav + footer) ────────────────── */

function cos_child_global_styles(): void { ?>
<style id="cos-global" nonce="<?php echo esc_attr( cos_csp_nonce() ); ?>">
:root{--cos-ink:#111111;--cos-navy:#121B2C;--cos-pearl:#F7F3EE;--cos-stone:#E5DED4;--cos-silver:#C4CAD1;--cos-slate:#5C6675;--cos-gold:#8B692F;--cos-gold-l:#B79A5B;--cos-teal:#46656B;--cos-focus:#2F6FED;--cos-success:#2D6A57;--cos-warning:#A56712;--cos-error:#A13B3B;--cos-display:'Cormorant Garamond','EB Garamond','Source Serif 4',Georgia,serif;--cos-body:'Inter',system-ui,-apple-system,'Segoe UI',Arial,sans-serif;--cos-nav-h:80px;--cos-black:#111111;--cos-mid:#5C6675;--cos-light:#E5DED4;--cos-red:#121B2C;--cos-promo-h:0px}
/* The off-canvas mobile menu (#cos-mob-menu) is a fixed, inset:0 panel moved
   off-screen with translateX — mobile browsers still count its full-width
   layout box toward the page's scrollable area, producing a phantom
   horizontal scrollbar even though nothing is visibly clipped. */
html,body{overflow-x:hidden;max-width:100%}
.cos-spot-price,.cos-step-price,.cos-stat-big,.cos-spot-price *,.cos-step-price *{font-variant-numeric:lining-nums tabular-nums}
/* Hero */
#cos-hero{height:100svh!important;min-height:unset!important;overflow:hidden!important;align-items:center!important}
.cos-hero-bg{position:absolute!important;inset:0!important;overflow:hidden!important;height:100%!important}
.cos-hero-img-el{position:absolute!important;inset:0!important;width:100%!important;height:100%!important;object-fit:cover!important;object-position:50% 15%!important}
.cos-hero-overlay{background:rgba(0,0,0,0.12)!important}
.cos-hero-h1{font-family:var(--cos-display)!important;font-style:italic!important;font-weight:300!important;font-size:clamp(44px,6.5vw,84px)!important;line-height:1.1!important}
.cos-hero-eyebrow{color:rgba(255,255,255,.8)!important}
/* Hero content indent */
.cos-hero-content{padding-left:88px!important}
@media(max-width:768px){.cos-hero-content{padding-left:24px!important}}
/* Navigation */
#cos-nav-wrap{position:fixed;top:0;left:0;right:0;z-index:90;height:var(--cos-nav-h);background:rgba(247,243,237,.93);backdrop-filter:blur(14px);-webkit-backdrop-filter:blur(14px);border-bottom:1px solid rgba(196,202,209,.35);transition:box-shadow .25s}
#cos-nav-wrap.scrolled{box-shadow:0 2px 20px rgba(26,26,26,.06)}
#cos-nav{max-width:1600px;margin:0 auto;height:100%;display:flex;align-items:center;justify-content:space-between;padding:0 48px;gap:24px}
.cos-nav-links{display:flex;align-items:center;gap:28px;flex:1}
.cos-nav-links a{font-family:var(--cos-body);font-size:11px;font-weight:500;letter-spacing:.18em;text-transform:uppercase;color:var(--cos-slate);transition:color .2s;white-space:nowrap;text-decoration:none}
.cos-nav-links a:hover,.cos-nav-links a.active{color:var(--cos-ink)}
.cos-nav-logo{flex:0 0 auto}
/* contrast(1.2): a subtle CSS treatment only -- no crop/resize/distortion,
   and the red Swiss cross in the mark is untouched by a pure contrast
   filter (it doesn't shift hue or add color, only widens the existing
   light/dark separation already in the source file). UI/UX pass 2026-09-19,
   task 2: the logo read faintly against the header's translucent pearl
   background at default contrast. */
.cos-nav-logo img{height:42px;width:auto;max-width:none;object-fit:contain;display:block;filter:contrast(1.2)}
.cos-nav-utils{display:flex;align-items:center;gap:0;flex:1;justify-content:flex-end}
/* !important on size only: some older raw-HTML pages (e.g. the Home page's
   post_content) still embed a legacy, page-local copy of this exact
   selector with stale, sub-44px dimensions (from before the shared header
   was consolidated here) — same specificity, and it renders later in the
   document than wp_head, so it would otherwise win the cascade on those
   pages specifically. This is the shared, authoritative header component;
   it must render at a consistent, accessible size everywhere regardless of
   what an individual page's legacy CSS still says. */
.cos-nav-utils a{font-family:var(--cos-body);color:var(--cos-slate);transition:color .2s;display:flex;align-items:center;justify-content:center;width:44px!important;height:44px!important;flex:0 0 44px!important;text-decoration:none}
.cos-nav-utils a:hover{color:var(--cos-ink)}
.cos-nav-utils svg{width:19px;height:19px}
.cos-bag-link{position:relative}
.cos-bag-count{position:absolute;top:8px;right:4px;min-width:16px;height:16px;padding:0 4px;border-radius:8px;background:var(--cos-navy);color:#fff;font-family:var(--cos-body);font-size:8px;font-weight:700;line-height:16px;text-align:center;display:none;pointer-events:none;box-sizing:border-box}
.cos-bag-count:not(:empty){display:block}
/* !important on size for the same reason as .cos-nav-utils a above — a
   legacy page-local copy of this selector (40x40) still exists in some
   pages' post_content and would otherwise win by document order. */
.cos-mob-btn{display:none;color:var(--cos-slate);width:44px!important;height:44px!important;align-items:center;justify-content:center;flex-direction:column;gap:5px;cursor:pointer;border:none;background:none;font-family:var(--cos-body)}
.cos-mob-btn span{display:block;width:20px;height:1px;background:currentColor;transition:transform .3s,opacity .3s}
/* Mobile menu */
#cos-mob-menu{position:fixed;inset:0;background:var(--cos-pearl);z-index:89;transform:translateX(-100%);transition:transform .4s cubic-bezier(.25,.46,.45,.94);overflow-y:auto;display:flex;flex-direction:column}
#cos-mob-menu.open{transform:translateX(0)}
.cos-mob-inner{padding:calc(var(--cos-nav-h) + 40px) 40px 40px;flex:1;display:flex;flex-direction:column}
.cos-mob-links{display:flex;flex-direction:column}
.cos-mob-links a{font-family:var(--cos-display);font-style:italic;font-weight:300;font-size:34px;color:var(--cos-ink);padding:16px 0;border-bottom:1px solid var(--cos-stone);transition:padding-left .2s,color .2s;text-decoration:none}
.cos-mob-links a:last-child{border-bottom:none}
.cos-mob-links a:hover{padding-left:10px;color:var(--cos-gold)}
.cos-mob-foot{margin-top:auto;padding-top:32px;border-top:1px solid var(--cos-stone)}
.cos-mob-foot p{font-family:var(--cos-body);font-size:10px;font-weight:600;letter-spacing:.18em;text-transform:uppercase;color:var(--cos-slate);opacity:1}
/* Page top spacer (non-hero pages) */
.cos-page-top{height:var(--cos-nav-h)}
/* Footer */
#cos-footer{background:var(--cos-navy);color:var(--cos-pearl)}
.cos-nl-wrap{max-width:1400px;margin:0 auto;padding:64px 24px;display:grid;grid-template-columns:1fr;gap:40px;border-bottom:1px solid rgba(255,255,255,.06)}
.cos-nl-headline{font-family:var(--cos-display);font-style:italic;font-weight:300;font-size:clamp(22px,2.5vw,34px);color:#fff;margin-bottom:8px}
.cos-nl-sub{font-family:var(--cos-body);font-size:12px;font-weight:300;color:rgba(248,247,244,.78);line-height:1.65}
.cos-nl-form{display:flex;flex-direction:column;gap:12px;min-width:0}
.cos-nl-row{display:flex;flex-direction:column;gap:8px}
.cos-nl-input{flex:1;min-width:0;padding:14px 16px;background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.1);color:#fff;font-size:13px;font-weight:300;outline:none;border-radius:0;font-family:var(--cos-body)}
.cos-nl-input::placeholder{color:rgba(255,255,255,.45)}
.cos-nl-input:focus{border-color:rgba(255,255,255,.28)}
/* min-height + !important: some pages (Home) still carry a legacy, page-local
   copy of this selector with zero vertical padding, which collapses the
   button to ~13px tall in a column flex layout (mobile) where align-items:
   stretch can't rescue it the way it does in the row layout on wider
   viewports — same class of stale-CSS cascade issue as .cos-nav-utils a /
   .cos-mob-btn elsewhere in this file. This is the shared, authoritative
   newsletter component; it must stay at an accessible height everywhere. */
.cos-nl-btn{width:100%;min-height:44px!important;box-sizing:border-box;display:flex;align-items:center;justify-content:center;padding:14px 24px;background:var(--cos-pearl);border:1px solid var(--cos-pearl);color:var(--cos-navy);font-size:10px;font-weight:600;letter-spacing:.2em;text-transform:uppercase;white-space:nowrap;border-radius:0;cursor:pointer;font-family:var(--cos-body);transition:background .2s,border-color .2s}
.cos-nl-btn:hover{background:#fff;border-color:#fff}
#cos-footer .cos-nl-consent{display:flex;align-items:flex-start;gap:8px;margin:0;font-size:10px;font-weight:300;color:rgba(248,247,244,.78);line-height:1.55;font-family:var(--cos-body)}
.cos-nl-consent input{width:24px!important;height:24px!important;margin-top:0;flex-shrink:0;accent-color:var(--cos-focus)}
.cos-trust{border-bottom:1px solid rgba(255,255,255,.06);padding:24px}
.cos-trust-items{max-width:1400px;margin:0 auto;display:flex;flex-wrap:wrap;gap:20px 36px;justify-content:center}
.cos-trust-item{font-family:var(--cos-body);font-size:11px;font-weight:500;letter-spacing:.14em;text-transform:uppercase;color:rgba(248,247,244,.70);display:flex;align-items:center;gap:7px}
.cos-trust-item a{display:inline-block;padding:7px 0;margin:-7px 0}
.cos-trust-dot{width:3px;height:3px;background:var(--cos-gold-l);display:inline-block;flex-shrink:0}
.cos-footer-cols{max-width:1400px;margin:0 auto;padding:48px 24px;display:grid;grid-template-columns:repeat(2,1fr);gap:36px}
.cos-footer-col h3{font-family:var(--cos-body);font-size:10px;font-weight:600;letter-spacing:.28em;text-transform:uppercase;color:rgba(248,247,244,.70);margin-bottom:18px}
.cos-footer-col ul{display:flex;flex-direction:column;gap:10px;list-style:none;margin:0;padding:0}
.cos-footer-col li{font-family:var(--cos-body);font-size:12px;line-height:1.5}
.cos-footer-col a{font-family:var(--cos-body);font-size:12px;font-weight:300;color:rgba(248,247,244,.82);transition:color .2s;text-decoration:none;display:inline-block;padding:5px 0;margin:-5px 0}
.cos-footer-col a:hover{color:#fff}
/* Small labels tighten their letter-spacing on mobile only (UI/UX pass
   2026-09-19, task 3) -- desktop keeps the wider luxury tracking. */
@media(max-width:767px){.cos-footer-col h3{letter-spacing:.18em}}
.cos-footer-bottom{max-width:1400px;margin:0 auto;padding:20px 24px;border-top:1px solid rgba(255,255,255,.05);display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px}
.cos-footer-legal{display:flex;align-items:center;gap:16px;flex-wrap:wrap}
.cos-footer-copy{font-family:var(--cos-body);font-size:10px;font-weight:300;color:rgba(248,247,244,.70)}
.cos-footer-cookie-link{font-family:var(--cos-body);font-size:10px;font-weight:300}
.cos-footer-cookie-link a,.cos-footer-cookie-link button{display:inline-block;background:none;border:none;padding:8px 2px;margin:-8px -2px;min-height:24px;font:inherit;color:rgba(248,247,244,.82);text-decoration:underline;cursor:pointer}
.cos-footer-cookie-link a:hover,.cos-footer-cookie-link button:hover{color:#fff}
.cos-footer-security{display:flex;align-items:center;gap:14px;flex-wrap:wrap}
.cos-footer-security-item{display:flex;align-items:center;gap:6px;font-family:var(--cos-body);font-size:10px;font-weight:500;letter-spacing:.06em;text-transform:uppercase;color:rgba(248,247,244,.70)}
.cos-footer-security-ico{width:13px;height:13px;flex-shrink:0;color:var(--cos-gold-l)}
.cos-footer-logo{height:28px;width:auto;max-width:none;opacity:.5;display:block}
/* Footer column groups: <details> below 768px (collapsed by default, native
   keyboard support, no JS accordion), plain always-open sections at/above
   768px (UI/UX pass 2026-09-19, task 6). Server-renders with the `open`
   attribute so content is present without JS either way; the summary
   trigger itself is what disappears on desktop, which is what actually
   prevents it from ever being toggled closed there. */
.cos-footer-col-summary{display:flex;align-items:center;justify-content:space-between;gap:12px;min-height:44px;cursor:pointer;list-style:none;-webkit-tap-highlight-color:transparent}
.cos-footer-col-summary::-webkit-details-marker{display:none}
.cos-footer-col-summary h3{margin-bottom:0}
.cos-footer-details-icon{font-size:16px;font-weight:300;color:rgba(248,247,244,.55);transition:transform .25s;flex-shrink:0;line-height:1}
.cos-footer-details[open] .cos-footer-details-icon{transform:rotate(45deg)}
.cos-footer-col-body{padding-top:14px}
@media(max-width:767px){
  .cos-footer-cols{gap:0}
  .cos-footer-details{border-bottom:1px solid rgba(255,255,255,.08);padding:16px 0}
  .cos-footer-details:first-child{border-top:1px solid rgba(255,255,255,.08)}
}
@media(min-width:768px){
  /* The trigger itself is what's removed -- see the block comment above --
     so nothing on desktop (mouse or keyboard) can toggle a section shut. */
  .cos-footer-details > summary{display:none}
  .cos-footer-col-body{padding-top:0}
}
/* Hide old nav wrappers from legacy page designs */
.cos-ritual-nav,.cos-shop-nav,.cos-journal-nav,.cos-about-nav,.cos-contact-nav,
#cos-ritual .nav,#cos-shop .nav,#cos-journal .nav,#cos-about .nav,#cos-contact .nav,
#cos-ritual .nav-spacer,#cos-shop .nav-spacer,#cos-journal .nav-spacer,#cos-about .nav-spacer{display:none!important}
.cos-stat-fn{color:var(--cos-slate,#5C6675)!important}
#crv3 .crv3-stat-label{color:var(--cos-silver,#C4CAD1)!important}
#cos-main-content{scroll-margin-top:calc(var(--cos-nav-h,80px) + 8px)}
@media(max-width:600px){
  #crv3 .crv3-reveal{transform:translateY(24px)!important}
  #crv3 .crv3-reveal.is-visible{transform:none!important}
}
/* Responsive */
@media(min-width:768px){
  .cos-nl-wrap{grid-template-columns:1fr 1fr;padding:64px 48px}
  .cos-footer-cols{grid-template-columns:repeat(3,1fr);padding:48px 48px}
  .cos-footer-bottom{padding:20px 48px}
  .cos-trust{padding:28px 48px}
}
/* The newsletter input+button row stays stacked through 1023px even
   though .cos-nl-wrap above already splits into its own two columns at
   768px — switching both at once used to leave the form column too
   narrow for a legible input right at 768px. minmax(220px,1fr) guarantees
   the input never drops below a usable width once it does go horizontal. */
@media(min-width:1024px){
  .cos-nl-row{display:grid;grid-template-columns:minmax(220px,1fr) auto;gap:0}
  .cos-nl-input{border-right:none}
  .cos-nl-btn{width:auto;padding:0 24px}
}
@media(max-width:1023px){
  .cos-nav-links{display:none}
  .cos-mob-btn{display:flex;position:absolute;left:20px;top:50%;transform:translateY(-50%)}
  #cos-nav{padding:0 20px;position:relative}
  /* Help and Account move into the off-canvas drawer below this breakpoint
     (they're already linked in .cos-mob-links) instead of squeezing into
     the header. The previous approach kept all three icons in the header
     and closed the gaps between them with negative margins so they'd fit
     — that shifted each 44px tap target (the WCAG 2.5.8 minimum, kept
     untouched) on top of its neighbour by 8px rather than actually making
     room, so Help/Account/Bag genuinely overlapped as touch targets at
     every width in this range, not just visually. Only Bag stays in the
     header now, so there's nothing left for it to overlap. Selector needs
     the .cos-nav-utils/a prefix to out-specify the base .cos-nav-utils
     a{display:flex} rule above — a bare .cos-nav-help{display:none} loses
     that cascade despite coming later in the file. */
  .cos-nav-utils a.cos-nav-help,.cos-nav-utils a.cos-nav-account{display:none}
  .cos-nav-utils{margin-right:-10px}
  .cos-nav-logo{position:absolute;left:50%;transform:translateX(-50%)}
  /* True 50% centering is what it looks like, but the hamburger corner
     (20px pad + 44px button = 64px) and the icon group on the right occupy
     different widths, so the genuinely clear middle space isn't the full
     viewport width — that's what visibly overlapped "SWITZERLAND" on
     narrow phones before this was first fixed. Rather than shift the logo
     off-center to dodge it, scale its own size down to whatever the
     true-centered middle space actually allows: 38.2vw tracks how that
     space grows with viewport width (half of it, since the logo grows
     outward from center in both directions), and the -93px constant
     reserves room for the icon group. That constant was tuned for a wider
     3-icon group before Help/Account moved to the drawer above, so it now
     reserves more space than the single remaining Bag icon needs — safe
     (the logo just runs a little smaller than it strictly has to at the
     narrowest widths) rather than reworking a proven clamp() for a purely
     cosmetic gain. The 21px floor stops it from disappearing on any
     narrower phone. */
  .cos-nav-logo img{height:clamp(21px,38.2vw - 93px,42px)}
}
@media(min-width:1024px){#cos-mob-menu{display:none!important}}
@media(prefers-reduced-motion:reduce){
  *,*::before,*::after{animation-duration:.01ms!important;animation-iteration-count:1!important;transition-duration:.01ms!important;scroll-behavior:auto!important}
}
</style>
<?php }
add_action( 'wp_head', 'cos_child_global_styles', 20 );

/* ─── Global nav (promo + header + mobile menu) ─────────────────── */

function cos_child_global_nav(): void {
	$logo      = esc_url( get_stylesheet_directory_uri() . '/assets/images/logo.png' );
	$logo_webp = esc_url( get_stylesheet_directory_uri() . '/assets/images/logo-111w.webp' );
	$logo_webp_2x = esc_url( get_stylesheet_directory_uri() . '/assets/images/logo-222w.webp' );
	?>
<header id="cos-nav-wrap" role="banner">
  <nav id="cos-nav" aria-label="Primary navigation">
    <div class="cos-nav-links">
      <a href="/">Home</a>
      <a href="/shop/">Shop</a>
      <a href="/the-ritual/">The Ritual</a>
      <a href="/science/">Swiss Science</a>
      <a href="/heritage/">Swiss Heritage</a>
    </div>
    <div class="cos-nav-logo">
      <a href="/" aria-label="Caviar of Switzerland &mdash; Home">
        <picture>
          <source type="image/webp" srcset="<?php echo $logo_webp; ?> 111w, <?php echo $logo_webp_2x; ?> 222w" sizes="111px">
          <img src="<?php echo $logo; ?>" alt="Caviar of Switzerland" width="111" height="42" fetchpriority="high" loading="eager"/>
        </picture>
      </a>
    </div>
    <div class="cos-nav-utils">
      <a href="/help/" aria-label="Help" class="cos-nav-help"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M12 17v.5"/><path d="M12 13.5a2.5 2.5 0 1 0-2.5-2.5"/></svg></a>
      <a href="/account/" aria-label="Account" class="cos-nav-account"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="8" r="3.25"/><path d="M5.5 18.5a6.5 6.5 0 0 1 13 0"/></svg></a>
      <a href="/cart/" aria-label="Bag" class="cos-bag-link"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M6 8h12l-1 11H7L6 8Z"/><path d="M9 8a3 3 0 1 1 6 0"/></svg><span class="cos-bag-count" aria-live="polite"></span></a>
      <button class="cos-mob-btn" id="cos-mob-btn" aria-label="Open navigation menu" aria-expanded="false" aria-controls="cos-mob-menu">
        <span id="cos-h1"></span><span id="cos-h2"></span><span id="cos-h3"></span>
      </button>
    </div>
  </nav>
</header>
<div id="cos-mob-menu" aria-hidden="true" role="dialog" aria-label="Navigation menu">
  <div class="cos-mob-inner">
    <nav class="cos-mob-links">
      <a href="/">Home</a>
      <a href="/shop/">Shop</a>
      <a href="/the-ritual/">The Ritual</a>
      <a href="/science/">Swiss Science</a>
      <a href="/heritage/">Swiss Heritage</a>
      <a href="/help/">Help</a>
      <a href="/account/">Account</a>
      <a href="/contact/">Contact</a>
    </nav>
    <div class="cos-mob-foot"><p>Caviar of Switzerland · Zug</p></div>
  </div>
</div>
<?php }
add_action( 'wp_body_open', 'cos_child_global_nav' );

/* ─── Global footer ─────────────────────────────────────────────── */

function cos_child_global_footer(): void {
	$logo      = esc_url( get_stylesheet_directory_uri() . '/assets/images/logo.png' );
	$logo_webp = esc_url( get_stylesheet_directory_uri() . '/assets/images/logo-111w.webp' );
	$logo_webp_2x = esc_url( get_stylesheet_directory_uri() . '/assets/images/logo-222w.webp' );
	?>
<footer id="cos-footer" role="contentinfo">
  <div class="cos-nl-wrap">
    <div>
      <h2 class="cos-nl-headline"><em>Enter the world of Caviar of Switzerland.</em></h2>
      <p class="cos-nl-sub">Receive ritual guidance, product news, and exclusive brand updates.</p>
    </div>
    <?php cos_child_newsletter_form(); ?>
  </div>
  <div class="cos-trust">
    <ul class="cos-trust-items" role="list">
      <li class="cos-trust-item"><span class="cos-trust-dot"></span>Made in Switzerland</li>
      <li class="cos-trust-item"><span class="cos-trust-dot"></span>Clinical-grade formulas</li>
      <li class="cos-trust-item"><span class="cos-trust-dot"></span>30-day returns</li>
    </ul>
  </div>
  <div class="cos-footer-cols">
    <details class="cos-footer-col cos-footer-details" open>
      <summary class="cos-footer-col-summary"><h3>Customer Care</h3><span class="cos-footer-details-icon" aria-hidden="true">+</span></summary>
      <div class="cos-footer-col-body"><ul><li><a href="/contact/">Contact</a></li><li><a href="/shipping-returns/">Shipping &amp; Returns</a></li><li><a href="/faq/#tracking">Track My Order</a></li><li><a href="/account/">My Account</a></li><li><a href="/terms/">Terms &amp; Conditions</a></li><li><a href="/privacy/">Privacy Policy</a></li></ul></div>
    </details>
    <details class="cos-footer-col cos-footer-details" open>
      <summary class="cos-footer-col-summary"><h3>Explore</h3><span class="cos-footer-details-icon" aria-hidden="true">+</span></summary>
      <div class="cos-footer-col-body"><ul><li><a href="/shop/">Shop</a></li><li><a href="/the-ritual/">The Ritual</a></li><li><a href="/science/">Swiss Science</a></li><li><a href="/heritage/">Swiss Heritage</a></li><li><a href="/heritage/sustainability/">Sustainability</a></li><li><a href="/sitemap/">Sitemap</a></li></ul></div>
    </details>
    <details class="cos-footer-col cos-footer-details" open>
      <summary class="cos-footer-col-summary"><h3>Caviar of Switzerland in Zug</h3><span class="cos-footer-details-icon" aria-hidden="true">+</span></summary>
      <div class="cos-footer-col-body"><address style="font-style:normal"><p style="font-family:var(--cos-body);font-size:12px;font-weight:300;color:rgba(248,247,244,.82);line-height:1.8">Caviar of Switzerland<br>Chamerstrasse 115<br>6300 Zug<br>Switzerland<br><a href="mailto:info@caviarofswitzerland.com" style="color:inherit;text-decoration:underline">info@caviarofswitzerland.com</a></p></address><div style="display:flex;gap:20px;margin-top:16px">
  <a href="https://www.instagram.com/caviarofswitzerland/" rel="noopener noreferrer" target="_blank" aria-label="Instagram" style="color:rgba(248,247,244,.82);transition:color .2s;display:inline-flex;align-items:center;justify-content:center;width:24px;height:24px" onmouseover="this.style.color='rgba(248,247,244,1)'" onmouseout="this.style.color='rgba(248,247,244,.82)'">
    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="2" y="2" width="20" height="20" rx="5"/><circle cx="12" cy="12" r="5"/><circle cx="17.5" cy="6.5" r=".5" fill="currentColor" stroke="none"/></svg>
  </a>
  <a href="https://www.facebook.com/caviarofswitzerland/" rel="noopener noreferrer" target="_blank" aria-label="Facebook" style="color:rgba(248,247,244,.82);transition:color .2s;display:inline-flex;align-items:center;justify-content:center;width:24px;height:24px" onmouseover="this.style.color='rgba(248,247,244,1)'" onmouseout="this.style.color='rgba(248,247,244,.82)'">
    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
  </a>
  <a href="https://www.youtube.com/@caviarofswitzerland" rel="noopener noreferrer" target="_blank" aria-label="YouTube" style="color:rgba(248,247,244,.82);transition:color .2s;display:inline-flex;align-items:center;justify-content:center;width:24px;height:24px" onmouseover="this.style.color='rgba(248,247,244,1)'" onmouseout="this.style.color='rgba(248,247,244,.82)'">
    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22.54 6.42a2.78 2.78 0 0 0-1.95-1.96C18.88 4 12 4 12 4s-6.88 0-8.59.46A2.78 2.78 0 0 0 1.46 6.42 29 29 0 0 0 1 12a29 29 0 0 0 .46 5.58 2.78 2.78 0 0 0 1.95 1.96C5.12 20 12 20 12 20s6.88 0 8.59-.46a2.78 2.78 0 0 0 1.95-1.96A29 29 0 0 0 23 12a29 29 0 0 0-.46-5.58z"/><polygon points="9.75 15.02 15.5 12 9.75 8.98 9.75 15.02" stroke="none" fill="currentColor"/></svg>
  </a>
</div></div>
    </details>
  </div>
  <div class="cos-footer-bottom">
    <div class="cos-footer-legal">
      <p class="cos-footer-copy">&copy; <?php echo date('Y'); ?> Caviar of Switzerland. All rights reserved. Zug, Switzerland.</p>
      <?php if ( function_exists( 'cmplz_get_option' ) ) : ?>
      <p class="cos-footer-cookie-link"><button type="button" id="cos-cookie-settings-btn">Cookie Settings</button></p>
      <?php endif; ?>
    </div>
    <div class="cos-footer-security">
      <span class="cos-footer-security-item">
        <svg class="cos-footer-security-ico" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="9" width="14" height="9" rx="1"/><path d="M7 9V6a3 3 0 1 1 6 0v3"/></svg>
        SSL-Encrypted Checkout
      </span>
      <span class="cos-footer-security-item">
        <svg class="cos-footer-security-ico" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M10 2.5L3 5.7v5.1c0 3.8 2.9 6.8 7 7.4 4.1-.6 7-3.6 7-7.4V5.7L10 2.5z"/></svg>
        WAF + DDoS Protection
      </span>
      <span class="cos-footer-security-item">
        <svg class="cos-footer-security-ico" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="8.5" cy="8.5" r="5.5"/><path d="M17 17l-3.6-3.6"/></svg>
        Daily Malware Scanning
      </span>
    </div>
    <picture>
      <source type="image/webp" srcset="<?php echo $logo_webp; ?> 111w, <?php echo $logo_webp_2x; ?> 222w" sizes="120px">
      <img class="cos-footer-logo" src="<?php echo $logo; ?>" alt="Caviar of Switzerland" width="120" height="28" loading="lazy"/>
    </picture>
  </div>
</footer>
<?php if ( function_exists( 'cmplz_get_option' ) ) : ?>
<script nonce="<?php echo esc_attr( cos_csp_nonce() ); ?>">
(function(){
  // The Complianz preference centre only exists once its cookie banner
  // script has actually been enqueued (it isn't, e.g. while Complianz
  // "Safe Mode" is on — see OWNER-ACTIONS.md). This control must never be a
  // silent no-op either way: if the real preference centre is available,
  // open it; otherwise send the visitor to the cookie/privacy policy so the
  // click still does something meaningful.
  var btn = document.getElementById('cos-cookie-settings-btn');
  if (!btn) return;
  btn.addEventListener('click', function () {
    if (window.cmplz_set_banner_status) {
      cmplz_set_banner_status('show');
      return;
    }
    window.location.href = '/privacy/#cookies';
  });
})();
</script>
<?php endif; ?>
<script nonce="<?php echo esc_attr( cos_csp_nonce() ); ?>">
(function(){
  var navWrap=document.getElementById('cos-nav-wrap');
  window.addEventListener('scroll',function(){navWrap.classList.toggle('scrolled',window.scrollY>10);},{passive:true});
  var mobBtn=document.getElementById('cos-mob-btn');
  var mobMenu=document.getElementById('cos-mob-menu');
  var h1=document.getElementById('cos-h1');
  var h2=document.getElementById('cos-h2');
  var h3=document.getElementById('cos-h3');
  var open=false;
  var lockedScrollY=0;
  function focusable(){return Array.prototype.slice.call(mobMenu.querySelectorAll('a'));}
  function setMenuFocusable(enabled){
    focusable().forEach(function(a){
      if(enabled) a.removeAttribute('tabindex');
      else a.setAttribute('tabindex','-1');
    });
  }
  function lockPage(){
    lockedScrollY=window.scrollY||window.pageYOffset||0;
    document.documentElement.style.overflow='hidden';
    document.body.style.overflow='hidden';
    document.body.style.position='fixed';
    document.body.style.top='-'+lockedScrollY+'px';
    document.body.style.left='0';
    document.body.style.right='0';
    document.body.style.width='100%';
  }
  function unlockPage(){
    document.documentElement.style.overflow='';
    document.body.style.overflow='';
    document.body.style.position='';
    document.body.style.top='';
    document.body.style.left='';
    document.body.style.right='';
    document.body.style.width='';
    window.scrollTo(0,lockedScrollY);
  }
  function preventBackgroundScroll(e){
    if(open) e.preventDefault();
  }
  function toggleMenu(){
    open=!open;
    mobMenu.classList.toggle('open',open);
    mobMenu.setAttribute('aria-hidden',String(!open));
    mobBtn.setAttribute('aria-expanded',String(open));
    setMenuFocusable(open);
    if(open) lockPage();
    else unlockPage();
    h1.style.transform=open?'translateY(6px) rotate(45deg)':'';
    h2.style.opacity=open?'0':'';
    h3.style.transform=open?'translateY(-6px) rotate(-45deg)':'';
    if(open){
      var first=focusable()[0];
      if(first) first.focus();
    }else{
      mobBtn.focus();
    }
  }
  setMenuFocusable(false);
  mobBtn.addEventListener('click',toggleMenu);
  window.addEventListener('wheel',preventBackgroundScroll,{passive:false});
  window.addEventListener('touchmove',preventBackgroundScroll,{passive:false});
  mobMenu.querySelectorAll('a').forEach(function(a){a.addEventListener('click',function(){if(open)toggleMenu();});});
  document.addEventListener('keydown',function(e){
    if(!open) return;
    if(e.key==='Escape'){toggleMenu();return;}
    if(e.key==='Tab'){
      var items=focusable();
      if(!items.length) return;
      var first=items[0], last=items[items.length-1];
      if(e.shiftKey && document.activeElement===first){e.preventDefault();last.focus();}
      else if(!e.shiftKey && document.activeElement===last){e.preventDefault();first.focus();}
    }
  });
})();
</script>
<script nonce="<?php echo esc_attr( cos_csp_nonce() ); ?>">
(function(){
  // Footer column groups render open (<details open>) so every visitor,
  // JS or not, sees full content by default -- this only *closes* them on
  // mobile once JS has actually run, rather than relying on JS to open
  // them (which would leave a no-JS mobile visitor's footer permanently
  // collapsed, since the trigger needs no-JS-safe native <details> behavior
  // either way). Desktop is never touched here: its <summary> is hidden by
  // CSS instead, which is what actually keeps it open there, with or
  // without JS. UI/UX pass 2026-09-19, task 6.
  var mq = window.matchMedia('(max-width: 767px)');
  var groups = document.querySelectorAll('.cos-footer-details');
  function applyCollapse(isMobile){
    groups.forEach(function(d){
      if (isMobile) d.removeAttribute('open');
      else d.setAttribute('open','');
    });
  }
  applyCollapse(mq.matches);
  if (mq.addEventListener) mq.addEventListener('change', function(e){ applyCollapse(e.matches); });
})();
</script>
<?php cos_child_newsletter_form_script(); ?>
<?php }
add_action( 'wp_footer', 'cos_child_global_footer', 5 );
