# Experience audit — 17 October 2026

Read-only audit expanded to three authorised focused fixes. Existing uncommitted work preserved.

## Corrected

- Product gallery: focusable labelled scroll region; iPhone WebKit ArrowRight advances one 390px frame. Original serious axe scrollable-region-focusable violation resolved.
- Shop: Add to bag labels increased from 12px to 15px; product benefits increased from 13px to 16px. Ritual Add to bag label increased to 15px. Existing touch target heights retained (48–52px).
- Product: primary and sticky purchase labels increased from 12px to 15px, tracking reduced to .08em. Visible WebKit sticky has no internal overflow.

Files: template-parts/hx/product/gallery.php; assets/css/hx/product.css; assets/css/hx/shop.css.

## Rendered verification

- Homepage: Chromium desktop 1440×950, Pixel7 Chromium, iPhone13 WebKit: HTTP200, no horizontal overflow, zero axe WCAG2A/AA and 2.1A/AA violations.
- Shop and 24h Regeneration Cream PDP: each tested on Chromium desktop1440, Chromium320px and iPhone13 WebKit. All six cases: no horizontal overflow, zero axe violations, purchase labels15px. Shop benefits16px.
- Keyboard gallery tested in WebKit. Physical Safari and physical touch hardware unverified.
- Scoped mechanical detector: existing advisory type-ramp drift; no material new findings from these edits.

## Remaining findings owned by main builder

- Homepage Collection Complete Ritual CTA computed11px, closing CTA12px: bring purchase action text to15px.
- Global token/documentation drift: loaded Cormorant Garamond differs from DESIGN.md Unna; preserve chosen family, reconcile documentation separately.
- Several homepage study details14px and key-actives descriptions14px: essential reading should16px; metadata may remain14px.
- Shared footer/privacy narrow hit areas are outside this scoped change. Inline text links are not automatically WCAG failures; assess spacing rather than treating every inline link as a required44×44 box.

## Geometry

1440px homepage section heights: hero950, collection1063, caviar1198, science1247, provenance1149, ritual1049, closing855. Shared desktop section padding approximately106–122px. Mobile sections use approximately64–80px. iPhone WebKit hero664px versus Pixel839px: visually inspect the short Safari viewport to ensure the art and CTA remain deliberate. No overflow observed.

## Performance and references

Local resource timing is not a production speed score. Homepage mobile AVIF assets were predominantly20–70KB; PDP WebKit main packshot WebP174KB. No basis for claiming measured Core Web Vitals.

Observed official La Prairie homepage https://www.laprairie.com/en-us: photographic hero with isolated large product, restrained interface and a direct CTA; current campaign is warmer and more commercial than this brief. Screenshot saved reference-laprairie.png. La Mer official page https://www.cremedelamer.com readable via web source, direct browser returned Access Denied; no visual or geometry superiority claim made. This comparison supports one unmistakable product and action per campaign, not imitation of its type or palette.

Evidence: audit.json (includes initial transient500 before parent fixed PHP); home-audit.json (corrected homepage); focused-final.json (six shop/PDP cases); final-iphone-pdp-sticky.png (final corrected sticky15px); detector.json.
