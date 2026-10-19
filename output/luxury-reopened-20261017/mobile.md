# Independent mobile luxury and conversion review — 17 October 2026

Scope: fresh rendered homepage, Shop, 24h Regeneration Cream PDP and Ritual, after reading PRODUCT.md and DESIGN.md. Previous review reports were not consulted. Playwright iPhone 13 WebKit (390 × 664), additional 320px WebKit, and 1440px desktop Chromium. This is an independent mobile assessment within the parent review, not a standalone Impeccable dual-assessment run. Questions skipped: the parent authorized concrete fixes and final validation.

## Material findings and disposition

1. **P1 — Mobile PDP asks for purchase before explaining the product. Fixed.** Originally the cream title began at y639.5 on the 664px viewport. The first screen was packshot plus a fixed Add to bag bar, with the benefit and price below it. The existing summary now introduces the image, followed immediately by price and the normal quantity/Add to bag form. At 390px the title is y135.6, benefit y185.0, price y527.3 and normal CTA y575.3–627.3. At 320px the title/benefit remain above the full cream jar; price is y565.9 and the normal CTA y613.9–665.9. The image is not squeezed; the cream stage removes its empty photographic surround through CSS positioning. Other product photos remain contained.

2. **P1 — Shop delays individual formula discovery behind a second complete-ritual pitch. Fixed.** Original first formula names were y2436.2, with the catalogue itself below the hero and full ritual promotion. Actual DOM order is now hero → four formulas → complete retained ritual offer → about. First formula names are y1124.4 at 390px, a reduction of about 1312px. The hero now gives “Swiss caviar skincare”, “Four formulas. One complete ritual.” and the catalogue shortcut before the complete, uncropped group photograph on phones. No gray side bars or invented assets remain.

3. **P1 — Enlarged text could clip card names or turn names into narrow letter stacks. Fixed in Shop; homepage passed to parent.** At the initial synthetic 200% test, the homepage cream card extended to x428.4 and science result text to x492.2 on a 390px screen; Shop’s cream card reached x397.5. The parent owns the homepage corrections. Shop now uses minimum widths, wrapping and a font-relative grid: two columns at the normal text size, one column at 200%. Final 320/390px Shop heading/paragraph/link bounds have no viewport escapes, and the 200% screenshot shows complete product names, benefit, price and action together.

4. **P2 — Purchase detail text was undersized for the stated mature customer. Fixed within the PDP.** Benefits, ingredient descriptions, opened details/specifications and INCI copy now use a 16px reading floor. Purchase accordion actions use 15px. Supporting shipping text and the mobile ritual-step label use 14px. The ordinary form and fixed bar wrap when enlarged text needs another row; their labels and actions remain usable.

5. **P2 — Ritual repeats the same four gestures twice after already explaining each in detail. Fixed narrowly.** Removed eight repeated AM/PM step rows. Kept the existing same-four-steps introduction, daytime SPF guidance, evening removal of make-up/SPF and absorption before sleep. The four detailed chapters, order explanation and complete ritual offer remain. The guide is still intentionally extensive; its length alone is not an outstanding purchase blocker because Shop and PDPs now offer a direct decision path.

## Verification

- All five PDPs checked at 390px and 320px, normal and synthetic 200% text; document widths match the viewport in every capture.
- Cream axe scan: zero WCAG A/AA violations at 390px and 320px. This is automated coverage, not a full VoiceOver audit.
- Quantity increase/decrease passed. At 320px with 200% text, the normal Add to bag and the fixed-bar Add to bag both completed and displayed confirmation. The automatically opened mini-bag was closed with Escape between actions.
- Shop and Ritual normal/200% geometry checks at 390px and 320px: no visible main heading/paragraph/link viewport escapes. Desktop 1440px checked after changes; PDP adjacent purchase column and four-column Shop remain intact.
- Text enlargement method: snapshot computed font sizes and numeric line heights for body descendants, then double both using inline `!important` styles. Values are snapshotted before mutation to avoid compounding inheritance. This deliberately stresses text-only reflow; physical iPhone Safari text controls, pinch zoom and VoiceOver were not exercised. The 720px desktop reflow capture is supplementary.

## Changed files

- `wp-content/themes/cos-child/assets/css/hx/product.css`
- `wp-content/themes/cos-child/template-parts/hx/product/purchase.php`
- `wp-content/themes/cos-child/template-parts/hx/product/gallery.php` — cream-only stage class
- `wp-content/themes/cos-child/assets/css/hx/shop.css`
- `wp-content/themes/cos-child/woocommerce/archive-product.php`
- `wp-content/themes/cos-child/assets/css/hx/the-ritual.css`
- `wp-content/themes/cos-child/template-parts/hx/the-ritual/day.php`

Existing work was preserved. Before snapshots and all browser artifacts are retained beside this report. No raster image or JavaScript changes were made by this reviewer.

Evidence: `mobile-artifacts/pdp-verification.json`, `mobile-artifacts/shop-ritual-verification.json`; final captures begin `verified-`. Initial findings are retained in `evidence.json` and `review-details.json`. Reproduction scripts are `mobile-pdp-check.cjs` and `mobile-shop-ritual-check.cjs`.

**Final verdict:** No remaining material mobile comprehension, purchase-hierarchy or enlarged-text blocker in the assigned surfaces after these fixes and the parent’s homepage corrections. The remaining unverified boundary is physical iPhone Safari behavior; this review supports a WebKit-emulated release verdict, not a claim of physical-device certification.
