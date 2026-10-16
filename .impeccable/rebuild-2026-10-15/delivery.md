# Caviar of Switzerland — local redesign handoff

Completed 16 October 2026. Preview: http://caviarofswitzerland.local/

## Implemented

- Seven-chapter homepage: cream campaign, four-object collection, caviar macro, attributed science, Swiss making, visible four-step ritual, brand close.
- Unna/Inter typography, ivory/pearl/champagne/graphite materials, restrained controls and one visible-default hero entrance with reduced-motion handling.
- Shared navigation/footer and storefront styling, readable product and purchase controls, consistent Cleanse / Treat / Revive / Restore names, dynamic prices and savings.
- Theme-owned editorial pages at `/the-ritual/`, `/science/`, `/science/why-caviar/`, `/science/clinical-studies/`, `/science/swiss-formulation/`, `/heritage/`, `/heritage/craftsmanship/`, `/heritage/sustainability/`. Existing page records, URLs and SEO integration remain in place; no database content replacement.
- Existing product artwork retained. New hero variants are proportional conversions of the incumbent master; no packaging redraw. The three generated concept images remain development references and do not supply fictional products to the storefront.
- DESIGN.md and .impeccable/design.json refreshed, with prior versions backed up.

## Evidence

Independent visual and mobile reviews: `visual-review.md`, `mobile-review.md`. Final disposition in `finish-review.md`: **ship for local stakeholder review**, with both final material findings resolved. This is approval at the observed scope, not proof of global competitive superiority.

Verified first-view PNGs: `.impeccable/review/desktop.png` and `.impeccable/review/mobile.png` from the project root (actual 1440×950 and 375×812). Section and commerce captures are alongside the review reports. Early fullpage captures with blank lazy-image regions are superseded and are not delivery evidence.

Route, navigation, commerce and automated accessibility checks passed during implementation. Chromium: 21 route/accessibility checks passed; seven additional mobile accessibility checks passed for the new editorial routes. WebKit 26.5 was subsequently installed and an iPhone 13 smoke project added, scoped to 38 route/navigation/commerce/accessibility scenarios. Initial runs passed 36 and exposed two failures: default WebKit keyboard navigation skipped the skip link, and unconfigured Klaviyo checkout identification requested an invalid HTTP CDN URL. Explicit keyboard focusability and a visible 44px focused target fixed the skip link. The checkout integration now stays unloaded without an account key; configured CDN scripts use HTTPS. Both affected tests then passed in Chromium and WebKit (four focused reruns). No test assertions were relaxed. PHP lint passed changed templates/modules; motion JavaScript syntax passed.

`provenance/shipping-raster-manifest.json` records 150 scoped raster variants: 144 origin records added with decoded pixel hashes unchanged; six prior records preserved. No shipping-pattern file remained flagged in the recorded scan. Unused incumbent files outside that scope were preserved; directory-wide metadata cleanup is not claimed. Origin records do not invent original generation prompts or certify documentary facility photography.

## Practical limits

Physical iPhone/Safari, real payments, network-throttled performance and production deployment are not certified by this handoff. Payment gateway availability remains the merchant's existing configuration. Broader journal, ingredient and service pages receive the shared presentation system; their entire content has not been rewritten. The unchanged silver masthead mark is visually faint; a stronger original brand-approved export would improve authority without recoloring the mark. Independent reviewers also noted mixed photographic finish and optional empty-cart simplification.

Preserve existing uncommitted work. No commit, deployment, real order or customer message was performed.
