# Owner Actions — Caviar of Switzerland

Generated as part of the production-readiness remediation. Everything here
requires a business decision, real data, or an external account the codebase
cannot supply on its own — nothing in this list is a code defect.

## 1. Consent banner (Complianz)

Complianz is installed and its setup wizard is complete, but **Safe Mode is
enabled** (`wp-admin → Complianz → Settings`), which deliberately suppresses
the live cookie banner while a site is being configured. Before go-live:

1. Go to `wp-admin → Complianz → Settings → General` and turn off **Safe
   Mode**.
2. Confirm the banner appears on a fresh (no-cookie) visit.
3. Verify the footer's "Cookie Settings" control (added this pass) correctly
   reopens the banner — it calls Complianz's own `cmplz_set_banner_status()`.
4. Consider enabling Complianz's native "Consent Mode" integration
   (`Integrations` tab) if you want Complianz itself to manage the Google
   Consent Mode bridge instead of the theme's `cos_child_complianz_consent_bridge()`.

## 2. Analytics — GTM container ID

`COS_GTM_ID` is now a constant/filter (`inc/analytics.php`) instead of a
hardcoded ID, and GTM only loads when `wp_get_environment_type() === 'production'`
**and** an ID is set. To enable GTM on the live site:

```php
// wp-config.php on production only
define( 'COS_GTM_ID', 'GTM-52RTXMJ' ); // or your container ID
```

Also confirm `WP_ENVIRONMENT_TYPE` is set to `'production'` on the live
server (it's `'local'` here) — both conditions must be true.

## 3. Payment gateways

Only **Cash on Delivery** is currently enabled in WooCommerce. Stripe and
WooCommerce Payments are installed and configured (test mode) but disabled.
Terms and Privacy now render the accepted-methods sentence dynamically via
`[cos_payment_methods]`, so this will update itself once you:

1. Complete Stripe/WooCommerce Payments merchant onboarding (API keys, bank
   account, business verification).
2. Switch the gateway out of test mode and enable it in
   `WooCommerce → Settings → Payments`.
3. Run a real end-to-end test transaction before relying on it for live
   orders.
4. Decide whether to keep Cash on Delivery active alongside card payments,
   or disable it — and either way, write the **COD refund process** (see
   §5 below, this is currently undocumented anywhere on the site).

## 4. Legal copy needing your/legal's sign-off

- **EU ODR platform reference** — removed from Terms §9 (the linked platform
  was discontinued). The paragraph is now an empty HTML comment marker in
  `wp_posts.post_content` for page ID 442. Legal should supply current,
  jurisdiction-appropriate dispute-resolution wording (e.g. national ADR
  bodies) to replace it.
- **Company registration number / VAT ID** — not stated anywhere on the site
  (Terms, Privacy, or footer). Only "Caviar of Switzerland, Zug,
  Switzerland" appears (owner, 29 Oct 2026: the company is "Caviar of Switzerland",
  not a GmbH; "GmbH" removed from Privacy and Terms). Add the CHE-xxx.xxx.xxx UID and VAT number if
  applicable.
- **EU/UK representative obligations** — if you ship into the EU/UK as a
  non-EU/UK entity, confirm whether an Art. 27 GDPR representative or UK
  equivalent is legally required, and publish their details if so.
- **DDP/DAP and import VAT wording** — Shipping & Returns states "no import
  duties apply" for EU/Switzerland and DAP shipping with possible VAT/duties
  for the UK (orders over £135). Have this verified against current customs
  rules for every market you actually ship to before relying on it.
- **"No EU import duties" statement** — verify this is still accurate (rules
  change); it's a specific, checkable factual claim.
- **Cash-on-delivery refund process** — genuinely undocumented anywhere
  (Terms, Privacy, Shipping & Returns, FAQ). Since COD has no card on file,
  refunds need a defined process (bank transfer? store credit?) — write this
  once decided, it wasn't invented here.
- **Partially-used cosmetics return policy** — already reasonably described
  on Shipping & Returns and FAQ ("partially used products are accepted...").
  Have legal confirm this is the policy you actually want to honor.
- **Same-day dispatch promise** ("orders before 14:00 CET dispatched same
  day") — appears on Shipping & Returns and FAQ; confirm fulfilment can
  actually meet this before it's advertised as a promise rather than a goal.

## 5. Trust/claims register

Every occurrence of the flagged claim categories was inventoried; items below
either already have adequate disclosed evidence (left as-is) or were
softened where they didn't. This register is for anything that still needs a
business decision.

| Claim | Where | Status | Action needed |
|---|---|---|---|
| "Verified buyer" | Home page testimonials | **Fixed** → "Customer testimonial" | None — but if you want to reinstate "Verified buyer," you need real verified-purchase evidence (WooCommerce currently shows 0 product reviews) |
| Press mentions (Vogue, Tatler, Madame Figaro, NZZ Style, FT HTSI) | Home page | **Softened** — outlet names kept, "featuring Caviar of Switzerland" claim removed | If any of these outlets genuinely covered the brand, add a citation/link and the claim can be restored to its stronger form |
| PDP stats: 99.8%, 0%, +38%, 94%, −29%, 91%, +52%/72hr, −24% | Individual PDPs + bundle | **Removed/softened** — no methodology disclosed anywhere for these figures | If real studies back these numbers, add them to `/science/clinical-studies/` with n-size/age/duration/method, then they can go back on the PDP data provider (`inc/product-data.php`) with a footnote link |
| +47%, −38%, 100%, +22% | PDPs, Clinical Studies page | Kept — disclosed with n-size/age/duration/method | None |
| "Clinical-grade" / SIRT-1 / collagen mechanism copy | Science pages, ingredient pages | Left largely as-is — mostly already hedged ("in vitro studies show...") | Recommend a full legal/scientific pass on the Science section specifically; it wasn't rewritten wholesale this round |
| Carbon-neutral delivery | Sitewide footer | **Linked** to `/heritage/sustainability/` where it's explained | Consider publishing the actual offset-provider certificate/registry ID for full substantiation |
| "Suitable for all skin types" | PDP + FAQ | **Softened** to "most skin types... patch test recommended," matching the Help page | None |
| AggregateRating/Review schema | — | Confirmed: none exists anywhere | Do not add until real reviews exist (WooCommerce reviews are currently at 0) |

## 6. Video asset

`the-complete-ritual-video.mp4` is ~25MB. It's now deferred (no fetch until
the visitor activates the video slide, `preload="none"`), which fixes the
eager-load problem, but the file itself should still be transcoded to a
smaller web-delivery size (target well under 5MB for a short product loop) —
keep the original master, don't re-encode destructively. This wasn't done in
this pass since it needs a video toolchain (ffmpeg or similar) and a decision
on acceptable quality trade-offs.

## 7. Editorial

- **`/find-your-ritual/`** (the old 404 "Find My Ritual" link target) is a
  **trashed** page (ID 373, "Skin Diagnostic Quiz"). The 404 page now points
  to `/shop/` instead. If you want a real skin-finder/quiz experience,
  that trashed page may be a starting point to restore and build out.
- **Journal editorial ownership** — the `_cos_journal_author_name` /
  `_cos_journal_reviewer_name` / `_cos_journal_reviewed_date` postmeta fields
  exist and are wired into both the JSON-LD Article schema and a visible
  "Reviewed by X on Y" front-end badge, but are unset on every article.
  Assign a real named author/reviewer per article when you have one — this
  is deliberately left empty rather than fabricated.
- Only 2 of the several claims-heavy journal articles were touched this pass
  ("Five Ingredients..." absolute "reverse aging" wording, and the
  "Luxury Skincare Trends" 2025→2026 date fix from an earlier pass). The rest
  of the Journal was intentionally not rewritten wholesale.

## 8. Outstanding from the original code-quality scope

- **CSS/JS externalization on the product template** — `single-product.php`
  still has its ~280-line `<style>` block, inline gallery/quantity `<script>`,
  and 6 inline `onclick=` handlers rather than being moved to enqueued,
  versioned `assets/css/product.css` / `assets/js/product.js` files. This was
  explicitly deferred: the file is large (940+ lines) and business-critical
  (pricing, gallery, WooCommerce hooks), and a mechanical CSS/JS extraction
  without a visual QA tool available carried more regression risk than the
  value justified in this pass, versus the data-consolidation and
  image/video fixes which were completed and fully tested. Worth a dedicated,
  carefully-tested follow-up.

---

# Addendum — 2026-08-14 remediation pass

Everything below was found/fixed during a second remediation pass (security
hardening, contact/consent/checkout/inventory/analytics/accessibility/SEO).
Code-level fixes are already committed; this section is only the remainder
that genuinely needs an owner decision, real credentials, or external
account access.

## 9. Consent banner (Complianz) — correction to §1 above

Testing this pass found Complianz's cookie banner script
(`cookiebanner/js/complianz.min.js`) is **not enqueued at all** on this site
right now (confirmed via direct HTTP inspection — no `window.cmplz_set_banner_status`,
no banner markup, anywhere). Turning off **Safe Mode** alone (as §1
describes) was tested and did **not** bring the banner back, so the actual
gating condition is more layered than Safe Mode by itself — Complianz's own
`site_needs_cookie_warning()` / region-detection / cookie-scan status likely
also needs to be checked. Before go-live:

1. Turn off Safe Mode as §1 describes.
2. Open the site in an incognito window and check the Network tab for
   `cookiebanner/js/complianz`. If it still doesn't load, go to
   `wp-admin → Complianz → Wizard` and re-run/complete the cookie scan and
   region targeting — an incomplete scan can suppress the banner even with
   Safe Mode off.
3. The footer "Cookie Settings" control (`inc/navigation.php`) is now robust
   either way: it reopens the real Complianz preference centre when
   available, and otherwise sends visitors to `/privacy/#cookies` (the
   Privacy page's Cookies section) instead of silently doing nothing. No
   further code change is needed once Complianz's banner is actually live —
   the full accept/reject/reopen/withdraw Playwright suite
   (`tests/10-consent-and-analytics.spec.ts`) is already written and will
   start running for real (currently self-skips with a clear reason) the
   moment the banner is live.

## 10. Shipping zones vs. "Free Worldwide Shipping" claim — RESOLVED, now worldwide (2026-09-11 owner decision)

**Update (2026-09-11):** the owner confirmed real worldwide fulfilment is in
place (carriers/customs/duties handling, zero-cost basis) and made the call
to advertise it. What changed:

1. `inc/shipping.php`'s `cos_child_operational_ship_to_countries()` now
   returns `null` (= every country WooCommerce lists), not a CH-only array.
   The `woocommerce_countries` filter no longer restricts the billing/
   shipping dropdowns. A real WooCommerce "Rest of World" zone (zone_id 0)
   with an unconditional, zero-minimum `free_shipping` method sits alongside
   the existing Switzerland zone — `tests/14-checkout-payment-readiness.spec.ts`
   covers both the country selector and that CH/DE/US/AU all get a real
   zero-cost rate at checkout.
2. Every shipping claim sitewide (PDP trust badge, shop archive, cart, home
   hero/service-strip, FAQ, Terms, Help, Shipping & Returns) reads from
   `cos_child_shipping_message()`, which now returns the worldwide copy
   ("Free Worldwide Shipping" / "Free shipping on every order, worldwide." /
   "Free worldwide shipping").
3. `inc/schema.php`'s `OfferShippingDetails`/`MerchantReturnPolicy` now read
   the real country list via `cos_child_ship_to_country_codes()` instead of
   a hardcoded `'CH'`, so structured data can't drift from what checkout
   actually offers again.

**Open item, NOT resolved by this pass:** no specific country-exclusion list
(export control / sanctions) was supplied when this was actioned — worldwide
currently means "every country WooCommerce's own list includes," with no
additional carve-outs. If any destination needs to be excluded for legal
reasons, add it back via `cos_child_operational_ship_to_countries()` (return
a restricted array instead of `null`) — that function stays the single
place to do this. Get real legal sign-off on the exclusion list rather than
guessing it in code.

## 11. Cash-on-delivery refund process — still undocumented (raised again)

Already flagged in §5 above and still true: "refund to original payment
method" copy on Shipping & Returns, Terms, and Help was factually wrong for
COD orders (nothing was charged electronically) and has been corrected this
pass to say a concierge will contact the customer to arrange it — but the
actual mechanism (bank transfer? store credit?) is still undecided and
wasn't invented. Decide and document it, then update those three pages'
COD-specific sentence to state the real process.

## 12. Production payment gateways, Apple Pay / Google Pay, test transactions

WooCommerce Payments is installed but every one of its gateways (Card,
Apple Pay, Google Pay, and the regional methods) is disabled — only Cash on
Delivery is live. **Update (2026-08-21 CRO brief):** the checkout UI now
detects whichever gateways are actually enabled and adapts around that
instead of assuming a fixed set — `cos_child_enabled_payment_gateways()`
(`inc/commerce.php`) is the one place this is read from, consumed by:
- the checkout "Place order" button text (dynamic: "Pay €X securely" for a
  real online gateway vs. "Place order — pay on delivery" for COD, updated
  live if the shopper switches gateways),
- a clear recovery message + concierge contact link if
  `get_available_payment_gateways()` is ever empty for a destination,
  instead of a broken/empty payment section,
- the PDP, cart, and checkout trust-strip payment-method labels (no longer
  claims a generic "Secure Checkout" when only COD is actually live).

None of this required or assumed a specific gateway going live — it is
correct today (COD-only) and will automatically start saying "Pay €X
securely" the moment a real online gateway is turned on below, with no
further code change. **Still not done, and cannot be done without your
credentials/business decisions:**

1. Complete WooCommerce Payments / Stripe merchant onboarding (business
   verification, bank account, API keys) in `wp-admin → Payments`.
2. Take the gateway out of test mode and enable it in
   `WooCommerce → Settings → Payments`. The theme's gateway-script dequeue
   (`inc/commerce.php`, `cos_child_any_woopayments_gateway_enabled()`) is
   already environment-agnostic — its checkout JS will load automatically
   the moment any `woocommerce_payments_*` gateway is enabled, no further
   code change needed.
3. Verify Apple Pay/Google Pay domain association and enable the express-
   checkout buttons in the WooCommerce Payments settings once the base
   gateway is live.
4. Run at least one real end-to-end test transaction and one real declined-
   card transaction before relying on this for live orders. **This pass did
   not and could not run one** — no sandbox gateway is configured — so
   nothing about live payment processing is claimed to be verified beyond
   what's stated above (UI adapts correctly to whatever's enabled; the
   actual money-moving path is untested).
5. Verify a real refund end-to-end (WooCommerce → Order → Refund) once
   dealing with real transactions.
6. Decide whether Cash on Delivery stays enabled alongside card payments.

## 13. COD country and order-value rules

No documented policy exists for which countries/order values COD should be
available for. The code-side fix this pass made (hiding COD where there's
no shipping rate — see §10) is a safety net, not a business rule. If you
want COD restricted further (e.g. Switzerland-only, or a maximum order
value), configure it via WooCommerce's own "Cash on Delivery → Enable for
shipping methods" restriction once real shipping methods/zones exist.

## 14. Swiss/EU/UK VAT and import-duty review

Unchanged from §4 above — have current shipping/tax copy verified against
today's customs rules for every market you actually ship to. As of §10's
2026-09-11 change this is now worldwide, not just Switzerland, which makes
this review considerably more urgent: VAT/duty rules vary per destination
and the current copy does not yet differentiate by country.

## 15. Clinical-study evidence and regulatory review

See the new `CLAIMS-REGISTER.md` in the project root for the full audit.
Two items specifically need your/legal's sign-off, not just a code fix:

1. **Carbon-neutral delivery** — copy names "a third-party offset provider
   that meets the Gold Standard framework" but no specific registry ID or
   certificate is published. Publish it, or soften the claim further.
2. **"Biomimetic" / "cellular regeneration" short-label copy** on the Home
   page, Ingredients index, Swiss Science, and FAQ pages currently relies on
   a visitor clicking through to the Why Caviar page for the actual
   justification. Consider a full legal/scientific pass specifically on
   whether that's sufficient substantiation-at-a-glance, or whether an
   inline caveat is required on the short labels themselves.

## 16. Production analytics / Google Ads conversion tracking

- `google-listings-and-ads` (Google for WooCommerce) is installed but its
  Google Ads conversion tracking (`GlobalSiteTag` class) is entirely inert
  until a real Google Ads conversion action is connected in that plugin's
  own settings — confirmed it does nothing without that connection.
- **When you do connect it**: it tracks Google Ads conversions (a different
  system from the theme's own GA4/GTM `dataLayer` purchase event), so the
  two are not inherently duplicative — but if you separately connect
  "Google Analytics for WooCommerce" (a different, currently-uninstalled
  plugin) or otherwise wire Google Ads conversions to read from the same
  GA4 purchase event, you can end up double-counting the same order. Keep
  ownership clear: the theme's `dataLayer` push (`page-checkout.php`) is
  the GA4 ecommerce source of truth; Google for WooCommerce should own Ads
  conversion attribution only.
- **Enhanced Conversions** (a Google for WooCommerce feature, off by
  default): if enabled, it pushes SHA-256-hashed customer email/name/
  address/phone into `window.dataLayer` via `gtag('set','user_data', ...)`.
  This is Google's standard, privacy-safe hashing pattern for ad-conversion
  matching, but it is customer PII (hashed, still linkable) landing in the
  same `dataLayer` the theme documents as PII-free. Decide, with legal, if
  that's acceptable before enabling it, and disclose it in the Privacy
  Policy if so.
- Verify the GA4 property/Ads conversion actually receives real events via
  GTM/GA4 DebugView and the Google Ads "Tag Diagnostics" tool once live —
  reaching `dataLayer` is not proof the tag vendor actually received and
  processed the event.

## 17. GTIN / product-feed data

No GTIN/MPN identifiers exist in the codebase and none were added — Product
JSON-LD correctly omits them (adding a fake identifier would be worse than
omitting one). If Google Merchant Center / the product feed needs real
GTINs, source them from the manufacturer and add them to
`inc/product-data.php`'s per-SKU array once available.

## 18. Optimised bundle video encoding

`the-complete-ritual-video.mp4` (found under `wp-content/uploads/2026/08/`)
is still the original, unoptimised master — no `ffmpeg` or video toolchain
is available in this environment to transcode it. It is deferred/click-to-
load only (confirmed, tested), so it no longer costs an eager page-load
fetch, but the file itself should still be transcoded to a smaller web-
delivery target (well under 5MB, H.264 + reasonable CRF, or AV1/VP9 if your
delivery pipeline supports it) — keep the original master file, don't
re-encode destructively over it.

## 19. Production CSP and HSTS

A **report-only** Content-Security-Policy is now live sitewide
(`wp-content/mu-plugins/cos-security.php`) — it never blocks anything, it
only logs what an eventual enforced policy would block, via
`wp-json/cos/v1/csp-report` into the `cos_csp_reports` option (capped at the
last 200 reports, no PII collected — URI/directive/source file/line only,
with query strings/fragments stripped from every URL before storage and
every field length-capped). The endpoint is hardened for public,
unauthenticated exposure: an 8KB body-size cap, IP-based rate limiting
(30 reports/5 min), and rejection of malformed reports — none of this
requires owner action, it's already live. If you'd rather not collect
reports locally at all (e.g. once wired up to an external CSP-reporting
provider like report-uri.com or Sentry instead — just point the
`report-uri`/`report-to` directive at their endpoint), filter local storage
off with `add_filter('cos_csp_report_local_storage_enabled', '__return_false');`.
Before enforcing the policy in production:

1. Let it run for a real traffic period and review `cos_csp_reports`
   (`wp-admin` → any DB/option viewer, or a quick `wp option get
   cos_csp_reports` once WP-CLI is available) for legitimate violations.
2. The current policy allows `'unsafe-inline'` for scripts/styles because
   the theme has extensive inline `<script>`/`<style>` blocks — moving to
   nonce- or hash-based CSP (dropping `unsafe-inline`) is a real migration,
   not a config flag; tackle it alongside the CSS/JS externalization work
   in §8.
   **2026-08-17 update — phase 1 of 2 done:** every inline `<script>`/
   `<style>` this theme's own PHP files control now carries a
   `nonce="<?php echo esc_attr( cos_csp_nonce() ); ?>"` attribute
   (`cos_csp_nonce()` in `cos-security.php`). The CSP header itself does
   **not** yet add `'nonce-{value}'` to `script-src`/`style-src` — do not
   add it until phase 2 (nonce-tagging or externalizing the ~20
   hand-authored pages' `post_content` inline blocks — these live in the
   database, not template files, see the raw-HTML-page pattern this repo
   uses) is also done. This was tested and confirmed the hard way: turning
   the nonce-source on in the header is an all-or-nothing switch for the
   whole site, not a gradual rollout — per the CSP spec, the instant a
   nonce-source appears in a directive, browsers stop honouring
   `'unsafe-inline'` for it, in every mode including Report-Only. With only
   the theme files nonced, every un-nonced page-content block started
   reporting a fresh violation on every page load, which flooded the
   report endpoint's own rate limit within a handful of requests. Don't
   flip the header switch until phase 2 covers 100% of inline content, not
   just theme files.
3. Only switch the header from `Content-Security-Policy-Report-Only` to
   enforcing `Content-Security-Policy` after confirming zero unexpected
   violations across checkout, consent, and analytics flows.
4. **HSTS** was not added — it requires the production domain to be
   HTTPS-only with no HTTP fallback anywhere (including subdomains, if
   `includeSubDomains` is used), which is a hosting/DNS-level guarantee
   this pass can't verify from the codebase. Add
   `Strict-Transport-Security: max-age=63072000; includeSubDomains; preload`
   at the server/CDN level once that's confirmed true in production —
   adding it prematurely can lock out visitors if any subdomain still runs
   plain HTTP.

## 20. Bundle component-level stock tracking — decision needed

None of the 5 catalogue products (COS-001–004, COS-BUNDLE) currently has
WooCommerce "manage stock" turned on — all show as unconditionally in
stock. This pass added:

- A sane per-order quantity cap (10 units, `inc/commerce.php`) so no one can
  accidentally order 9,999 of anything — this is a technical safety fix,
  not a real inventory decision.
- SKU-based bundle-availability sync: if you turn on stock management for
  the 4 component products in the future and any of them go out of stock,
  the bundle automatically reports as out-of-stock too
  (`cos_child_bundle_components_in_stock()`), rather than silently letting
  the bundle keep selling.

What this pass deliberately did **not** do: make bundle purchases actually
decrement/reserve the 4 components' stock counts. That requires either a
bundle/composite-product plugin (e.g. WooCommerce Product Bundles) or
custom order-processing stock-adjustment code, and building a partial,
untested version of that would be exactly the "misleading partial solution"
this project was told to avoid. Decide:

1. Do you want real per-unit stock tracking at all (currently everything is
   treated as always available)?
2. If yes, do you want a bundle-extension plugin, or custom stock-sync code
   built and tested against your actual fulfilment process?

## 21. Legacy duplicate CSS on the Home page — resolved

Update: the ~18.8KB legacy `<style id="cos-v4-css">` block described here
previously has since been audited and the duplicate portion removed. The
48 rules (plus 2 related `@media` fragments) that were exact functional
duplicates of the shared header/mobile-menu/footer/newsletter CSS in
`inc/navigation.php` — including the two selectors that needed a defensive
`!important` override (`.cos-nl-btn`, `.cos-nl-consent input`) — were
stripped directly from the Home page's `post_content` via `$wpdb->update()`,
verified with the Playwright visual-regression suite (zero pixel diff
before/after, both desktop and mobile). The `!important` overrides in
`inc/navigation.php` were left in place as harmless defense-in-depth.

What was deliberately **not** touched: the page's `:root` custom-property
redefinition (it sets slightly different color-token values than the
shared `:root` in `inc/navigation.php`, which would very likely be a
genuine improvement to fix, but touching it changes colors across every
homepage-unique section simultaneously — hero, science, spotlight
carousel, clinical stats — and verifying that safely needs a deliberate,
side-by-side visual review pass of its own, not a blind duplicate-removal
pass). The homepage-unique content sections (hero, ritual rail, science,
spotlight carousel, clinical stats) were also left as-is — they have no
shared-component equivalent to be a duplicate of.

## 22. Homepage and ingredient images — responsive srcset now added

Update: this has been resolved for the Home page and PDP gallery. Root
cause was more specific than "not audited yet" — the images referenced by
filename in the Home page's `post_content` (product packshots for the
ritual-rail/spotlight-carousel sections, and 3 of 5 "High-Tech Complex"
ingredient photos) pointed at WordPress-generated-looking size-variant
filenames (e.g. `c4-1600x2400.webp`) that matched **no Media Library
attachment record at all** — the files existed on disk but were orphaned,
almost certainly from the earlier AI1WM migration losing the DB rows. Two
of the four packshot base images (`c3.jpg`, `CAVIAR3501-copy.jpg`,
`CAVIAR3507-copy.jpg`) turned out to still have valid attachment records
under their base filename; `c4.jpg` and all 3 uploaded ingredient photos
did not and were re-registered as proper attachments (`wp_insert_attachment()`
+ `wp_generate_attachment_metadata()`, using GD — Imagick isn't available
in this environment but isn't required). All 11 affected `<img>` tags in
the Home page now have accurate `srcset`/`sizes`, verified live and via
the visual-regression suite. The PDP gallery hero, thumbnail strip, ritual
cards, visual-break, synergy, and presentation-gallery images were also
completed (a separate, earlier fix in this same remediation program).

Still open: 2 of the 5 High-Tech Complex ingredient images (Caviar
Extract, Syn®-Coll Peptide) are theme-bundled static assets under
`assets/images/ingredients/`, not Media Library uploads — they have only a
single 700×700 file each with no smaller derivative sizes, so they weren't
in scope for this fix (WordPress's attachment/srcset system doesn't apply
to theme-bundled files). If these need responsive sources too, either move
them into the Media Library as real attachments, or manually generate 2–3
smaller derivative sizes and hand-write a `srcset` the way the homepage
hero image already does.

## 23. SEO/trust items — genuine data still needed, nothing fabricated

A further review pass asked for GTIN/MPN, shipping/return schema, a
stronger shop title, real reviews, press-mention links, journal
author/reviewer credentials, a carbon-offset certificate, and production
GMC/GSC/GA4/Ads verification. What could be done from genuinely-published,
already-true site content was done in code; everything below still needs
real data, credentials, or a live production environment this pass can't
supply.

**Done in code this pass:**
- **Shop archive title/description** — `is_shop()` (a post-type archive)
  never matched the `is_page()`/`is_singular()` checks in the `<title>` tag
  filter or Open Graph function, so it silently fell through to
  WooCommerce's generic default ("Shop – Caviar Of Switzerland") even
  though a proper title ("The Collection — Caviar of Switzerland") and
  description were already authored for it in `inc/seo.php`'s
  `cos_child_seo_page_meta()` and simply never reached. Now wired up in
  both `inc/seo.php`'s title filter and `cos_child_open_graph()` — no new
  copy was written, the existing authored copy just now actually renders.
- **MPN** — added to Product JSON-LD (`inc/schema.php`) using each
  product's real SKU (COS-001–004, COS-BUNDLE). This is legitimate, not
  fabricated: Caviar of Switzerland is both brand and manufacturer for
  this single-brand line, so its own SKU is a genuine manufacturer part
  number. **GTIN is still correctly omitted** — no real UPC/EAN exists for
  these products (unchanged from §17 below).
- **`shippingDetails` / `hasMerchantReturnPolicy`** — added to every
  Product Offer, sourced directly from the published Shipping & Returns
  page (post 357): 30-day return window (no unjustified refusal, subject to
  reasonable trial use), full refund, return shipping paid by the customer
  except for Caviar of Switzerland's own error or a faulty item. **As of
  §10's 2026-09-11 change, scoped to every country `cos_child_ship_to_country_codes()`
  (`inc/shipping.php`) returns** — real fulfilment now genuinely covers all
  of them via the Switzerland + Rest-of-World zones, so this no longer
  needs the CH-only carve-out described here previously; it reads from the
  same single source of truth as the rest of the shipping copy, so it
  can't drift from what checkout actually offers again.
- **Product reviews** — confirmed WooCommerce reviews and star ratings are
  already enabled (`woocommerce_enable_reviews`, `woocommerce_enable_review_rating`
  both "yes", verified-purchase label on, verification not required to
  leave one). The 0 review count is simply because no real customer has
  left one yet — no code change was needed, the system is ready to count
  real reviews the moment they arrive. `AggregateRating`/`Review` schema
  should stay absent until then (unchanged from the original trust
  register — adding it with 0 real reviews would be fabrication).

**Still needs real data/credentials/production access — nothing invented:**

1. **Genuine product reviews** — the only way review count moves off zero
   is real customers leaving real reviews; nothing to build, just time and
   traffic (or a review-solicitation email flow, if you want one added).
2. **Links supporting the displayed publication names** (Vogue, Tatler,
   Madame Figaro, NZZ Style, FT HTSI — see §5's trust register). If any of
   these outlets genuinely covered the brand, supply the real article
   URLs and they can be added as citations; none were guessed or invented
   here.
3. **Real author/reviewer details for journal content** — the postmeta
   fields (`_cos_journal_author_name`, `_cos_journal_reviewer_name`, etc.)
   are wired into both the JSON-LD `Article` schema and a visible "Reviewed
   by X on Y" badge, but are unset on every article (see §7). Assign real
   named, credentialed people per article when available.
4. **Carbon-offset certificate/registry number** — the footer's
   "Carbon-neutral delivery" claim names a third-party offset provider
   framework but no specific certificate/registry ID is published anywhere
   (see §15). Publish the real one, or soften the claim further.
5. **Production Google Merchant Center + Search Console validation** —
   both require the live production domain; nothing here can be validated
   from local dev. Once live: submit/verify the domain in Search Console,
   submit the product feed to Merchant Center, and confirm it actually
   ingests the new `mpn`/`shippingDetails`/`hasMerchantReturnPolicy` fields
   without errors or warnings (Merchant Center in particular will flag a
   mismatch if the live checkout still can't fulfil orders to countries
   implied elsewhere on the site — see §10 again).
6. **Production GA4 and Google Ads receipt verification** — largely
   overlaps with §16 above (GTM container ID, Google Ads conversion
   connection). Once both are configured on the live production domain,
   confirm real events arrive via GA4 DebugView and the Google Ads Tag
   Diagnostics tool — reaching `dataLayer` locally is not proof the tag
   vendor actually received and processed the event in production.

## 24. Mobile PageSpeed optimization pass — re-test against staging once deployed

A full mobile performance pass was completed and verified locally (median of
3 cold Lighthouse runs against this dev environment: Performance 80→98,
FCP 2.25s→0.95s, LCP 4.65s→2.34s, Speed Index 3.26s→1.25s, CLS steady at 0,
Accessibility/SEO steady at 100). The **Best Practices score reads 78
locally only because this dev site runs on plain HTTP** — the two failing
checks are `is-on-https` and `redirects-http`, both of which the real
staging/production domain already satisfies over HTTPS; nothing in the
code changes touches Best Practices otherwise.

1. Deploy this pass to `https://staging.caviarofswitzerland.com/` (per
   [[reference_staging_deploy_tooling]] — excludes list in
   [[project_staging_deploy_excludes]] still applies; check
   [[project_staging_plugin_drift]] before a full mirror).
2. Re-run the PageSpeed Insights report against that exact URL to get a
   number directly comparable to the original baseline report this pass
   was built from — local and staging network/server conditions differ
   enough that the local numbers above are a strong directional signal,
   not a substitute for the real one.
3. Confirm Best Practices reads 100 there (it should, automatically, once
   HTTPS is in play — no further action expected).

## 25. Production indexability — deployment checklist

Indexability is entirely code-enforced, keyed off `wp_get_environment_type()`
(`inc/seo.php`) — **not** WordPress's own "Discourage search engines" option
(`Settings → Reading`) or the `blog_public` row it writes, which this codebase
deliberately does not rely on (that setting is exactly the kind of thing that
gets left in whatever state a DB clone/migration happened to carry over).
Three independent layers key off the same function: the `wp_robots` meta tag,
the `X-Robots-Tag` HTTP header, and `robots.txt`'s `Disallow: /`. All three
default to **blocked** whenever `WP_ENVIRONMENT_TYPE` is anything other than
`'production'` — including when the constant is simply undefined, which is
`local`/`staging`'s failure-safe direction, not production's. No canonical,
Open Graph, or schema URL is ever hardcoded to a domain — all resolve via
`get_site_url()`/`home_url()`/`get_permalink()`, so they automatically follow
whatever `siteurl`/`home` the environment's own `wp_options` (or `WP_HOME`/
`WP_SITEURL` in wp-config.php) actually says.

**Before going live on `https://caviarofswitzerland.com/` (or whichever
domain replaces the current `staging.caviarofswitzerland.com/`, per §24):**

1. In production's `wp-config.php`, add (or confirm):
   ```php
   define( 'WP_ENVIRONMENT_TYPE', 'production' );
   ```
   This is the *only* switch. Nothing else in this checklist changes code —
   everything below is verifying that switch actually took effect.
2. Confirm staging's `wp-config.php` explicitly sets
   `WP_ENVIRONMENT_TYPE` to `'staging'` (or `'development'`) — if it's ever
   left undefined there, staging is indistinguishable from production to
   this code and would become indexable by accident.
3. Load `https://<production-domain>/robots.txt` — must show real rules
   (not `Disallow: /`) and end with a `Sitemap:` line pointing at
   `https://<production-domain>/wp-sitemap.xml`.
4. View source on the homepage and one product page — `<meta
   name="robots">` must read `index, follow` (not `noindex`), and there
   must be exactly one `<link rel="canonical">`, pointing at the
   `https://` production URL.
5. Load `/wp-sitemap.xml` and confirm it 200s (not 404 — see the
   `pre_handle_404` fix earlier in `inc/seo.php`) and every URL inside uses
   the production domain.
6. Password-protecting staging at the server/host level remains the more
   robust safeguard this code can't replace on its own — code can only ask
   a crawler not to index a page it already has the URL for, not stop it
   from reaching that page in the first place.
7. Submit the production sitemap in Google Search Console and Bing
   Webmaster Tools (see the Search Console checklist added alongside this
   SEO pass for the full submission steps).

## 26. Pre-existing unverified claims on /the-ritual/ — legal/clinical review needed

Discovered while adding internal links to `/the-ritual/` (page ID 59) for the
new skin-concern pages — **not introduced by this SEO pass**, and left as-is
since rewriting existing marketing copy was out of scope for this engagement
("preserve current product copy," "do not alter visual identity beyond the
minimal content additions required for SEO"). Flagging for owner/legal review
because it's the same category of claim the rest of this pass was told to
avoid introducing (dermis-penetration and mechanism-of-action language with
no corresponding study in `inc/product-data.php` or `page 347`, the clinical
source of truth per project notes).

Two spots, both in page 59's `post_content`:

1. **Hero subhead**: "...allowing active ingredients to penetrate deeper and
   work in harmony." — a penetration claim with no supporting study on file.
2. **"Why the Sequence Matters" section** (`#cos-seq-section`): four card
   descriptions make specific physiological claims that don't trace back to
   any documented study or ingredient data — "resets the skin pH" (Cleanser),
   "small-molecule nutrients for immediate cellular uptake" (Serum),
   "targeted care to the thin dermis" (Eye Cream), and "creates an occlusive
   barrier, preventing trans-epidermal water loss" (Regeneration Cream).

None of this was fabricated by this pass — it predates it — but it sits on a
page that this pass just added inbound SEO links to and made more
discoverable, so it's a reasonable time to have legal/clinical sign off on
whether this language should stay as-is or be softened to match the
qualified, study-referenced tone used everywhere else on the site (e.g. the
PDP copy in `inc/product-data.php`, which never states a mechanism without a
citation).

## 27. Search Console / Bing Webmaster Tools submission checklist

To be done once production is live and §25 above is confirmed:

1. **Verify the property** in Google Search Console (Domain property, e.g.
   `caviarofswitzerland.com`) and Bing Webmaster Tools — either DNS TXT
   record or the HTML-tag method both work with this codebase (no existing
   verification meta tag to conflict with).
2. **Submit the sitemap index**: `https://<production-domain>/wp-sitemap.xml`
   in both consoles' Sitemaps section. Do not submit the individual
   `wp-sitemap-posts-*.xml` sub-files directly — the index is sufficient and
   is what WordPress core expects to be submitted.
3. **Request indexing** individually (Search Console → URL Inspection →
   Request Indexing) for the 6 new pages, since they won't be crawled on
   Google's own schedule for a while otherwise:
   - `/skin-concerns/`
   - `/skin-concerns/fine-lines-wrinkles/`
   - `/skin-concerns/loss-of-firmness/`
   - `/skin-concerns/dark-circles/`
   - `/skin-concerns/dry-dehydrated-skin/`
   - `/skin-concerns/dull-skin/`
4. **Confirm the 5 product-category archives are excluded as intended** —
   Search Console will likely show them under "Excluded → Page marked
   'noindex'" within a few days. That is the correct, expected state (see
   Phase 4 of this SEO pass) — do not "fix" it by requesting indexing on
   those URLs.
5. **Check the URL Inspection tool's rendered HTML** for at least one of the
   new concern pages and one product page to confirm Google is reading the
   same `index, follow` meta tag and canonical this pass verified locally
   under simulated production.
6. **Monitor Coverage/Pages report weekly for the first month** for any
   unexpected `noindex` or `Duplicate without user-selected canonical`
   flags — the most likely early false-positive is Google initially
   flagging the new concern pages as near-duplicates of the shop/product
   pages they link heavily to, which should self-resolve as the unique
   editorial content on each gets crawled and indexed.
7. **Set up an Enhancements → Product snippets check** once product pages
   have accumulated enough impressions — confirms the Product/Offer schema
   (Phase 7) is being read correctly for price/availability rich results.

## 28. Security audit remediation — 2026-09-18

A defensive security audit found several issues; the code-fixable ones were
remediated the same day (this repo has no VCS, so there's no commit history
to point to instead).

**Fixed already (no action needed):**
- 7 AI1WM `.wpress` backup archives were sitting in `wp-content/ai1wm-backups/`,
  fully downloadable by anyone who knew/guessed the filename (dynamically
  confirmed: 200, full `Content-Length`, no auth). Moved to
  `backups/security-quarantine-2026-09-18/ai1wm-backups/` — this is the
  **second** time this exact issue has been quarantined (see
  `backups/security-quarantine-2026-07-26/` from a prior pass) — see the
  owner action below, this will keep recurring otherwise.
- `local-xdebuginfo.php` was publicly reachable and disclosed PHP/Xdebug
  config. Initially gated to logged-in administrators on `local` only —
  **corrected the same day** after a UI/UX pass's test run surfaced that
  this file had already been deleted outright once before (2026-08-17, a
  prior security review's explicit decision, documented in
  `tests/13-diagnostic-file-protection.spec.ts`'s own header comment: no
  legitimate reason for it to exist in the deployable webroot at all).
  Deleted again to match that established decision, rather than leaving two
  conflicting remediations for the same finding in place.
- `wp-login.php` had no brute-force protection (6 straight wrong-password
  POSTs all returned 200, no lockout). Added a 5-attempts/10-minute →
  15-minute-lockout throttle, keyed by hashed IP, in `cos-security.php`.
- `cos_child_test_get_order_meta` (`inc/commerce.php`) accepted any
  `order_id` with no ownership proof. Now requires the same `order_key`
  `hash_equals()` check its sibling cancel-function already had.
- Three `wp_json_encode($item)` calls embedding data into inline `<script>`
  blocks (`inc/analytics.php`) now pass `JSON_HEX_TAG | JSON_HEX_AMP`,
  closing a theoretical `</script>`-breakout path.
- Removed the empty, unused `wp-content/uploads/wp-migrate-db/` leftover
  directory.
- Added nginx-native deny rules for `.wpress`/`.sql`/`.log` extensions and
  `local-xdebuginfo.php` to this **local** site's nginx config, since the
  root cause behind the backup exposure above is that every `.htaccess` file
  in this project (root, `wp-content/uploads/`, `ai1wm-backups/`, `wc-logs/`)
  assumes Apache and is silently ignored by nginx (dynamically confirmed:
  `/readme.html` and `/wp-content/uploads/wc-logs/` both returned 200
  despite explicit Apache "deny all" rules for both). **This local nginx
  edit is not durable** — it lives in
  `%APPDATA%\Local\run\<siteid>\conf\nginx\includes\restrictions.conf`,
  which Local regenerates from its own `.hbs` template and is outside this
  project's own codebase entirely.

**Owner action still needed:**

1. **Restart this Local site** (or restart the Local app) so the nginx
   config edit above actually takes effect — it was written to the live
   config file but nginx needs a reload/restart to read it.
2. **Fix the AI1WM recurrence at the source.** Backups keep landing back in
   the webroot after being quarantined twice now (July and September).
   Either configure AI1WM (if reinstalled) to write backups outside the
   webroot, or stop using it in favor of a backup method that never writes
   into `wp-content/` at all, or add a scheduled task that relocates
   anything matching `wp-content/ai1wm-backups/*.wpress` automatically.
3. **Replicate the nginx rules durably** wherever staging/production's real
   web server config lives (this is a one-time, ops-owned task — the exact
   rule text is in `restrictions.conf` above, translate directly if that
   server is also nginx, or confirm the existing `.htaccess` rules actually
   work if it's genuinely Apache there).
4. **Run WPScan or Wordfence's vulnerability scanner** against the real
   staging/production stack. This local environment's WordPress core
   reports version "7.1", which doesn't correspond to any real published
   WordPress release, so a meaningful CVE lookup wasn't possible here;
   staging/production will have real version strings to check.
5. **Decide on the CSP enforcement timeline.** Not changed in this pass —
   the existing Report-Only header is a deliberate, already-documented
   transition state (see `cos-security.php`'s own comments and §19), and
   flipping it to enforced before the page-content nonce migration finishes
   would break WooCommerce/Klaviyo/Stripe scripts sitewide. No new action;
   just don't let this stall indefinitely.

## 29. SEO correction pass — 2026-09-19

Fixed a metadata collision and several SEO findings entirely in code; nothing
here required production access. Everything was verified against this local
environment plus a temporary, disposable PHP dev-server instance that
pre-defined `WP_ENVIRONMENT_TYPE=production` before `wp-config.php` loaded —
this local site's own indexability was never changed.

**Fixed already (no action needed):**
- `cos_child_seo_page_meta()` (`inc/seo.php`) defined the array key
  `'skin-concerns'` twice — once for the journal pillar hub
  (`/journal/skin-concerns/`) and once for the commercial concern hub
  (`/skin-concerns/`) — because both pages share the same `post_name` under
  different parents. The second definition silently won, so both pages were
  serving the commercial hub's title/description. Added
  `cos_child_seo_resolve_meta_key()`, which qualifies the lookup by parent
  slug only when a qualified key exists, and used it in the `<title>` filter,
  Open Graph/Twitter tags, and the WebPage JSON-LD in `inc/schema.php`. Every
  other page's metadata lookup is unaffected.
- Shortened all 5 product `<title>` tags (`cos_child_seo_pdp_meta()`) —
  previously ~73–79 characters once " — Caviar of Switzerland" was appended.
  Product names, H1s, slugs, breadcrumbs, cart labels, and Product schema
  `name` fields were not touched.
- Replaced the 5 concern pages' medical-sounding "Causes and Treatment"
  titles with commercial-but-helpful phrasing, and rewrote their meta
  descriptions to ~135–160 characters. Clinical qualifications (e.g. the
  disclosed 28-day results) were preserved verbatim; no new claims added.
- Added/revised the 5 product subtitles rendered under each PDP's H1
  (`inc/product-data.php`) to more search-relevant phrasing. No second H1 was
  introduced; the bundle's separate "What's Included" ritual-grid subtitles
  (a distinct piece of copy, only shown on the bundle page) were deliberately
  left as-is per the task's own "avoid excessive repetition elsewhere"
  guidance.
- Internal linking: added a visible "Shop by Concern" link row to `/shop/`
  (distinct from the concern-filter buttons removed earlier), and added two
  contextual links from `/science/clinical-studies/` so `fine-lines-wrinkles`,
  `dark-circles`, and `dull-skin` each now receive links from at least two
  distinct journal/science pages (the other two concerns already had this via
  earlier work this session; all 5 concerns already had links from `/shop/`,
  `/skin-concerns/`, and at least one product PDP).

**Owner action still needed:**

1. **Re-run Search Console / PageSpeed Insights against the real domain**
   once this reaches staging/production (see §27) — the corrected titles,
   descriptions, and internal links can only be evaluated for actual ranking
   impact against a real, crawled, indexed domain; nothing local can predict
   that.
2. **Re-verify indexation behavior on the real staging/production web
   server** the same way §25 already asks — this pass only added a
   verification technique (a disposable local PHP server simulating
   `WP_ENVIRONMENT_TYPE=production`), it didn't change the underlying
   environment-detection logic, so §25's existing checklist still applies
   unchanged.
3. **Optional, no urgency:** the same "Causes and Treatment"-style medical
   framing and the generic-fallback description gap that existed on the 5
   concern pages also exists on 4 ingredient science pages and 13 journal
   articles (per an earlier SEO pass's own note, §23) — those were
   deliberately left unfixed by choice and are unaffected by this pass.
