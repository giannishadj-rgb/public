<?php
/**
 * COS Child Theme — HOMEPAGE navigation (front page only; see the 'wp' hook at the end).
 *
 * Page-level global CSS (tokens, overflow guard, legacy fixes), the header /
 * mobile-menu markup, and the footer markup + scripts for the homepage. Inner pages keep
 * inc/navigation.php. STYLES: assets/css/home-staging/chrome.css (homepage only).
 * 18 Oct 2026: light footer (no .hx-on-dark, header logo rendering), house promise in the menu. The footer calls cos_child_newsletter_form() /
 * cos_child_newsletter_form_script() from inc/forms.php for the newsletter
 * signup block.
 */

/* ─── Global CSS (legacy tokens + page-level fixes) ─────────────── */

function cos_staging_global_styles(): void { ?>
<style id="cos-global" nonce="<?php echo esc_attr( cos_csp_nonce() ); ?>">
:root{--cos-ink:#111111!important;--cos-navy:#050505!important;--cos-pearl:#F8F8F8!important;--cos-gallery-bg:#E8E8E5!important;--cos-stone:#E5DED4!important;--cos-silver:#C4CAD1!important;--cos-slate:#5C6675!important;--cos-gold:#8B692F!important;--cos-gold-l:#B79A5B!important;--cos-teal:#46656B!important;--cos-focus:#2F6FED!important;--cos-success:#2D6A57!important;--cos-warning:#A56712!important;--cos-error:#A13B3B!important;--cos-display:'Cormorant Garamond','EB Garamond',Georgia,serif!important;--cos-body:'Inter',system-ui,-apple-system,'Segoe UI',Arial,sans-serif!important;--cos-nav-h:80px;--cos-black:#111111!important;--cos-mid:#5C6675!important;--cos-light:#E5DED4!important;--cos-red:#050505!important;--cos-promo-h:0px}
/* The off-canvas mobile menu (#cos-mob-menu) is a fixed, inset:0 panel moved
   off-screen with translateX — mobile browsers still count its full-width
   layout box toward the page's scrollable area, producing a phantom
   horizontal scrollbar even though nothing is visibly clipped. */
html,body{overflow-x:hidden;max-width:100%}
.cos-spot-price,.cos-step-price,.cos-stat-big,.cos-spot-price *,.cos-step-price *{font-variant-numeric:lining-nums tabular-nums}
/* Header, mobile menu and footer styles: assets/css/home-staging/chrome.css (homepage
   only, enqueued by inc/hx-system.php). Only page-level bits stay inline here. */
/* Page top spacer (non-hero pages) */
.cos-page-top{height:var(--cos-nav-h)}
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
@media(prefers-reduced-motion:reduce){
  *,*::before,*::after{animation-duration:.01ms!important;animation-iteration-count:1!important;transition-duration:.01ms!important;scroll-behavior:auto!important}
}
</style>
<?php }

/* ─── Global nav (header + mobile menu) ─────────────────────────── */

/**
 * URL of the homepage logo (versioned by mtime). 19 Oct 2026 (owner approved): the graphite
 * version of the official mark, because the silver lettering (#BCBEC0) is barely legible on
 * the light hero, header and footer. Same official vector paths; only the lettering fill
 * differs; the red square and white Swiss cross keep their official colours
 * (build_logo_graphite.py, logo-graphite.origin.json). Silver original: logo-master.svg.
 */
function cos_staging_logo_svg(): string {
	$file = get_stylesheet_directory() . '/assets/images/logo-graphite.svg';
	return esc_url( get_stylesheet_directory_uri() . '/assets/images/logo-graphite.svg?v=' . ( is_file( $file ) ? (int) filemtime( $file ) : 0 ) );
}

/** The official silver mark, for the dark graphite footer (the graphite version disappears there). */
function cos_staging_logo_svg_on_dark(): string {
	$file = get_stylesheet_directory() . '/assets/images/logo-master.svg';
	return esc_url( get_stylesheet_directory_uri() . '/assets/images/logo-master.svg?v=' . ( is_file( $file ) ? (int) filemtime( $file ) : 0 ) );
}

/**
 * Which top-level section the current request belongs to (for aria-current).
 * Products and the shop archive count as Shop.
 */
function cos_staging_nav_current(): string {
	$path = (string) wp_parse_url( isset( $_SERVER['REQUEST_URI'] ) ? wp_unslash( $_SERVER['REQUEST_URI'] ) : '/', PHP_URL_PATH );
	$path = '/' . ltrim( $path, '/' );
	if ( '/' === $path ) return '/';
	foreach ( array( '/shop/' => '/shop/', '/products/' => '/shop/', '/the-ritual/' => '/the-ritual/', '/science/' => '/science/', '/heritage/' => '/heritage/' ) as $prefix => $section ) {
		if ( 0 === strpos( trailingslashit( $path ), $prefix ) ) return $section;
	}
	return '';
}

/** aria-current attribute for a nav href, or ''. */
function cos_staging_nav_aria( string $href ): string {
	return cos_staging_nav_current() === $href ? ' aria-current="page"' : '';
}

function cos_staging_global_nav(): void {
	$logo_svg  = cos_staging_logo_svg();
	$primary = array(
		'/'            => 'Home',
		'/shop/'       => 'Shop',
		'/the-ritual/' => 'The Ritual',
		'/science/'    => 'Swiss Science',
		'/heritage/'   => 'Swiss Heritage',
	);
	?>
<header id="cos-nav-wrap" class="hxn" role="banner">
  <nav id="cos-nav" aria-label="Primary navigation">
    <?php /* First in the DOM so the phone tab order matches the bar: menu → logo → bag.
       Hidden ≥1280px (home-staging/chrome.css); absolutely positioned at the left edge below that. */ ?>
    <button type="button" class="cos-mob-btn" id="cos-mob-btn" aria-label="Open navigation menu" aria-expanded="false" aria-controls="cos-mob-menu">
      <span id="cos-h1"></span><span id="cos-h2"></span><span id="cos-h3"></span>
    </button>
    <div class="cos-nav-links">
      <?php foreach ( $primary as $href => $label ) : ?>
      <a href="<?php echo esc_attr( $href ); ?>"<?php echo cos_staging_nav_aria( $href ); ?>><?php echo esc_html( $label ); ?></a>
      <?php endforeach; ?>
    </div>
    <div class="cos-nav-logo">
      <a href="/" aria-label="Caviar of Switzerland &mdash; Home">
        <?php /* 18 Oct 2026: the vector mark inner pages use since 17 Oct (official master, graphite lettering,
           Swiss square + cross in official colours; design-sources/home-2026/build_logo_graphite.py).
           Replaces the darkened raster PNG: crisp at 3x, no CSS filter, one logo sitewide. */ ?>
        <img class="hxn-logo" src="<?php echo $logo_svg; ?>" alt="Caviar of Switzerland" width="128" height="40" fetchpriority="high" loading="eager" decoding="sync"/>
      </a>
    </div>
    <div class="cos-nav-utils">
      <?php /* 19 Oct 2026: no Help icon in the bar (owner); Help lives in the menu and the footer. */ ?>
      <a href="/account/" aria-label="Account" class="cos-nav-account"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="8" r="3.25"/><path d="M5.5 18.5a6.5 6.5 0 0 1 13 0"/></svg></a>
      <a href="/cart/" aria-label="Bag" class="cos-bag-link"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M6 8h12l-1 11H7L6 8Z"/><path d="M9 8a3 3 0 1 1 6 0"/></svg><span class="cos-bag-count" aria-live="polite"></span></a>
    </div>
  </nav>
</header>
<div id="cos-mob-menu" class="hxn-sheet" aria-hidden="true" role="dialog" aria-modal="true" aria-label="Navigation menu">
  <div class="hxn-menu">
    <nav class="hxn-menu__primary" aria-label="Menu">
      <?php $i = 0; foreach ( $primary as $href => $label ) : ?>
      <a class="hxn-menu__link" style="--hxn-i:<?php echo (int) $i++; ?>" href="<?php echo esc_attr( $href ); ?>"<?php echo cos_staging_nav_aria( $href ); ?>><?php echo esc_html( $label ); ?><span class="hx-arrow" aria-hidden="true"></span></a>
      <?php endforeach; ?>
    </nav>
    <nav class="hxn-menu__secondary" style="--hxn-i:<?php echo (int) $i; ?>" aria-label="Customer service">
      <a class="hxn-menu__small" href="/help/">Help</a>
      <a class="hxn-menu__small" href="/account/">Account</a>
      <a class="hxn-menu__small" href="/contact/">Contact</a>
    </nav>
    <ul class="hxn-menu__assure" role="list" style="--hxn-i:<?php echo (int) $i + 1; ?>">
      <li>Free worldwide shipping</li>
      <li>30-day returns</li>
    </ul>
    <div class="hxn-menu__foot" style="--hxn-i:<?php echo (int) $i + 2; ?>"><span class="hxn-cross" aria-hidden="true"></span><p>Caviar of Switzerland · Zug</p></div>
  </div>
</div>
<?php }

/* ─── Global footer ─────────────────────────────────────────────── */

function cos_staging_global_footer(): void {
	$groups = array(
		'Customer Care' => array( '/contact/' => 'Contact', '/shipping-returns/' => 'Shipping &amp; Returns', '/faq/#tracking' => 'Track My Order', '/account/' => 'My Account', '/terms/' => 'Terms &amp; Conditions', '/privacy/' => 'Privacy Policy' ),
		'Explore'       => array( '/shop/' => 'Shop', '/the-ritual/' => 'The Ritual', '/science/' => 'Swiss Science', '/heritage/' => 'Swiss Heritage', '/journal/' => 'Journal', '/heritage/sustainability/' => 'Sustainability', '/sitemap/' => 'Sitemap' ),
	);
	$social = array(
		'Instagram' => array( 'https://www.instagram.com/caviarofswitzerland/', '<rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4.25"/><circle cx="17.25" cy="6.75" r=".6" fill="currentColor" stroke="none"/>' ),
		'Facebook'  => array( 'https://www.facebook.com/caviarofswitzerland/', '<path d="M17 3h-2.5A4.5 4.5 0 0 0 10 7.5V10H7v3.5h3V21h3.5v-7.5H16l.75-3.5H13.5V7.75c0-.55.45-1 1-1H17z"/>' ),
		'YouTube'   => array( 'https://www.youtube.com/@caviarofswitzerland', '<path d="M21.6 7.2a2.6 2.6 0 0 0-1.8-1.8C18.2 5 12 5 12 5s-6.2 0-7.8.4a2.6 2.6 0 0 0-1.8 1.8C2 8.8 2 12 2 12s0 3.2.4 4.8a2.6 2.6 0 0 0 1.8 1.8C5.8 19 12 19 12 19s6.2 0 7.8-.4a2.6 2.6 0 0 0 1.8-1.8c.4-1.6.4-4.8.4-4.8s0-3.2-.4-4.8z"/><path d="M10 15.2 15.2 12 10 8.8z" fill="currentColor" stroke="none"/>' ),
	);
	?>
<footer id="cos-footer" class="hxf" role="contentinfo">
  <div class="hxf__inner">
    <section class="hxf-nl" aria-labelledby="hxf-nl-title">
      <div class="hxf-nl__intro">
        <h2 id="hxf-nl-title" class="hxf-nl__title cos-nl-headline">The Caviar Circle</h2>
        <p class="hxf-nl__sub">Ritual guidance, product news and brand updates.</p>
      </div>
      <?php cos_child_newsletter_form(); ?>
    </section>

    <div class="hxf-cols">
      <div class="hxf-brand">
        <a class="hxf-brand__logo" href="/" aria-label="Caviar of Switzerland &mdash; Home">
            <?php /* Dark graphite footer: the official silver mark (the graphite header mark disappears on it). */ ?>
            <img src="<?php echo cos_staging_logo_svg_on_dark(); ?>" alt="Caviar of Switzerland" width="128" height="40" loading="lazy" decoding="async"/>
        </a>
        <?php /* Kept as .cos-trust-item: tests/18 reads these four assurances. */ ?>
        <ul class="hxf-assure" role="list" aria-label="Our promise">
          <li class="cos-trust-item">Made in Switzerland</li>
          <li class="cos-trust-item">Free worldwide shipping</li>
          <li class="cos-trust-item">30-day returns</li>
          <li class="cos-trust-item">Secure checkout</li>
        </ul>
      </div>
      <?php foreach ( $groups as $title => $links ) : ?>
      <details class="hxf-group cos-footer-details" open>
        <summary class="hxf-group__summary cos-footer-col-summary"><h3 class="hxf-group__title"><?php echo esc_html( $title ); ?></h3><span class="hxf-plus" aria-hidden="true"></span></summary>
        <div class="hxf-group__body cos-footer-col-body"><ul class="hxf-links">
          <?php foreach ( $links as $href => $label ) : ?><li><a href="<?php echo esc_attr( $href ); ?>"><?php echo $label; // phpcs:ignore -- static, pre-escaped labels ?></a></li><?php endforeach; ?>
        </ul></div>
      </details>
      <?php endforeach; ?>
      <details class="hxf-group hxf-house cos-footer-details" open>
        <summary class="hxf-group__summary cos-footer-col-summary"><h3 class="hxf-group__title">The House in Zug</h3><span class="hxf-plus" aria-hidden="true"></span></summary>
        <div class="hxf-group__body cos-footer-col-body">
          <address>Caviar of Switzerland<br>Chamerstrasse 115<br>6300 Zug, Switzerland</address>
          <a class="hxf-house__mail" href="mailto:info@caviarofswitzerland.com">info@caviarofswitzerland.com</a>
          <div class="hxf-social">
            <?php foreach ( $social as $name => [ $href, $paths ] ) : ?>
            <a href="<?php echo esc_url( $href ); ?>" rel="noopener noreferrer" target="_blank" aria-label="<?php echo esc_attr( $name ); ?>"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><?php echo $paths; // phpcs:ignore -- static SVG ?></svg></a>
            <?php endforeach; ?>
          </div>
        </div>
      </details>
    </div>

    <div class="hxf-base">
      <p class="hxf-base__copy">&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> Caviar of Switzerland. All rights reserved.</p>
      <div class="hxf-base__end">
        <?php if ( function_exists( 'cmplz_get_option' ) ) : ?>
        <p class="cos-footer-cookie-link"><button type="button" id="cos-cookie-settings-btn">Cookie Settings</button></p>
        <?php endif; ?>
        <p class="hxf-base__place"><span class="hxf-cross" aria-hidden="true"></span>Zug, Switzerland</p>
      </div>
    </div>
  </div>
</footer>
<?php if ( function_exists( 'cmplz_get_option' ) ) : ?>
<script nonce="<?php echo esc_attr( cos_csp_nonce() ); ?>">
(function(){
  // Open the Complianz preference centre when its banner script is present;
  // otherwise go to the cookie section of the privacy policy (never a no-op).
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
    // Block page scroll behind the sheet, but let the sheet itself scroll (short phones, zoom).
    if(open && !mobMenu.contains(e.target)) e.preventDefault();
  }
  function setMenu(next, restoreFocus){
    if(next===open) return;
    open=next;
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
      if(first) first.focus({preventScroll:true});
    }else if(restoreFocus!==false){
      mobBtn.focus({preventScroll:true});
    }
  }
  function toggleMenu(){setMenu(!open);}
  setMenuFocusable(false);
  mobBtn.addEventListener('click',toggleMenu);
  window.addEventListener('wheel',preventBackgroundScroll,{passive:false});
  window.addEventListener('touchmove',preventBackgroundScroll,{passive:false});
  mobMenu.querySelectorAll('a').forEach(function(a){a.addEventListener('click',function(){if(open)setMenu(false,false);});});
  // Rotating a tablet past 1280px hides the sheet: close it so the page is never left locked.
  var wide=window.matchMedia('(min-width: 1280px)');
  function onWide(e){if(e.matches&&open)setMenu(false,false);}
  if(wide.addEventListener) wide.addEventListener('change',onWide); else if(wide.addListener) wide.addListener(onWide);
  document.addEventListener('keydown',function(e){
    if(!open) return;
    if(e.key==='Escape'){setMenu(false);return;}
    if(e.key==='Tab'){
      // Focus stays in the open menu: links cycle (Shift+Tab on the first goes to the
      // last), and Tab past the last link reaches the close button, then wraps.
      var items=focusable(); if(!items.length) return;
      var first=items[0], last=items[items.length-1], cur=document.activeElement;
      if(e.shiftKey && (cur===first||cur===mobBtn)){e.preventDefault();last.focus();}
      else if(!e.shiftKey && cur===last){e.preventDefault();mobBtn.focus();}
      else if(!e.shiftKey && cur===mobBtn){e.preventDefault();first.focus();}
      else if(cur!==mobBtn && !mobMenu.contains(cur)){e.preventDefault();first.focus();}
    }
  });
})();
</script>
<script nonce="<?php echo esc_attr( cos_csp_nonce() ); ?>">
(function(){
  // Footer groups render open (no-JS safe); on phones JS closes them into
  // accordions. ≥768px they stay open: the summary is inert by CSS and this
  // `toggle` backstop reopens a group closed from the keyboard.
  var mq = window.matchMedia('(max-width: 767px)');
  var groups = document.querySelectorAll('.cos-footer-details');
  function applyCollapse(isMobile){
    groups.forEach(function(d){
      var summary=d.querySelector('summary');
      if (isMobile) {
        d.removeAttribute('open');
        if(summary) summary.removeAttribute('tabindex');
      } else {
        d.setAttribute('open','');
        if(summary) summary.setAttribute('tabindex','-1');
      }
    });
  }
  applyCollapse(mq.matches);
  if (mq.addEventListener) mq.addEventListener('change', function(e){ applyCollapse(e.matches); });
  groups.forEach(function(d){
    d.addEventListener('toggle', function(){
      if (!mq.matches && !d.open) d.open = true;
    });
  });
})();
</script>
<?php cos_child_newsletter_form_script(); ?>
<?php }

add_action( 'wp', static function (): void {
 if ( ! cos_h26_is_front() ) return;
 remove_action( 'wp_head', 'cos_child_global_styles', 20 );
 remove_action( 'wp_body_open', 'cos_child_global_nav' );
 remove_action( 'wp_footer', 'cos_child_global_footer', 5 );
 add_action( 'wp_head', 'cos_staging_global_styles', 20 );
 add_action( 'wp_body_open', 'cos_staging_global_nav' );
 add_action( 'wp_footer', 'cos_staging_global_footer', 5 );
} );
