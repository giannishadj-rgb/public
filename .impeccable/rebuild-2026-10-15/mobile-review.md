# Independent mobile finish review — 15 October 2026

Reviewed the rendered local storefront in a separate native Chrome tab through mcp__cua_repl, with documented viewport overrides at 390×844 and 375×812. Reviewed homepage through footer, menu, shop, cream PDP, bundle PDP and empty cart. The empty checkout route correctly redirects to cart. No order or customer information entered. Viewport reset after review. Read direction.md and implementation CSS/asset metadata. This is Chrome emulation, not physical iOS Safari evidence.

## Verdict

Mobile homepage composition is persuasive and coherent. The prominent open cream, generous ivory field, deliberate serif line breaks, retained red mark detail and visible first-screen shopping action support the brief unusually well. At 375×812, the complete shopping button still fits without horizontal overflow. Shop, cream page, menu and footer share the same material palette. Approve mobile direction with two remaining finish issues below; the initially discovered PDP readability/step-system defects were fixed and independently rechecked during this review.

## Remaining prioritized findings

1. **P1 — Bundle landing title is demoted to fine print.** The homepage's primary CTA lands on `/products/the-complete-ritual/`. Its first screen shows a 16px uppercase kicker above the two-line italic 'Four formulas. One ritual.' at approximately 14px. The actual product H1 arrives far down the page after introductory/editorial blocks. On mobile this is a large drop in authority from the homepage and makes the main proposition easy to miss. Give the caption title a real campaign scale (around 34–40px) and an upright/italic hierarchy consistent with the homepage. Preserve the working direct purchase-section jump.
2. **P1 — Caviar label loses contrast against the photograph.** In the mobile caviar panel, 'Siberian sturgeon, Acipenser baerii' crosses dark blurred pearls. Grey type is visibly weak there even though the section heading remains readable. Place both lines in a reliably pale field, strengthen an appropriately scoped light scrim, or move the label out of the photograph. Root notified and owns fix.
3. **P2 — Empty cart carries unnecessary checkout furniture.** The empty bag shows a full Order Summary (€0 subtotal/total and free shipping) plus a second 'Start the Ritual' button. This makes an empty state feel like an unfinished transaction. One confident shop CTA and concise reassurance would be clearer. No functional blocker observed.

## Closed findings, independently verified

- Cream PDP benefit list was 14px, ritual link and jump links 10px. Exact selectors `.cos-pdp-benefit-item`, `.cos-pdp-view-ritual`, `.cos-pdp-jump-link` sent to storefront owner. Fresh reload after correction verifies benefits at 16px and actions at 15px. Corrected screenshot: `mobile-cream-purchase-final.jpg`.
- Conflicting named steps appeared as Eye/Moisturise on cream and Repair/Restore/Seal on bundle. Storefront owner normalized the arrays and sentences; fresh cream reload exposes active **Restore**. Homepage already consistently presents Cleanse, Treat, Revive, Restore.
- Earlier homepage science statistics were accidentally reduced by shared typography. Root/storefront owner resolved the selector collision; this initial observation is superseded and is not an outstanding finding.

## Interaction/accessibility observations

- Menu links are clear, generous, and visually consistent. Opening moves focus to Home. Escape closes the menu and restores focus to the trigger. Current trigger accessible name remains 'Open navigation menu' while expanded, a minor copy detail; expanded state is correctly announced.
- Quantity controls are 44px, add-to-bag button is comfortably prominent, prices and volumes are available. Cream benefits remain visible on mobile.
- Bundle purchase jump worked and revealed quantity, €450/€500/€50 saving, primary shopping action, delivery and returns. Payment availability was only observed, not changed.
- No horizontal overflow observed on the sampled mobile homepage. Readable 16px explanatory copy, sensible content widths, and native scrolling throughout.
- Newsletter has an email label and explicit consent checkbox. No submission performed.

## Motion and performance

Code review confirms hero entrance is gated by `prefers-reduced-motion:no-preference`, with explicit reduced-motion cancellation. Shared storefront transition/animation cancellation exists. `hx-motion.js` removes the old reveal mode so inherited scroll-reveal CSS does not imply an active reveal system. Content is accessible in the page tree. No compulsory scroll hijack observed.

Mobile browser selected AVIF hero `hxh-tall-540.avif` (13,091 bytes); collection AVIF files are roughly 19–24KB each. Hero eager load and downstream lazy loading are sensible. No measured network-throttled performance budget or physical-device performance claim is made. Transient blank images in immediate scroll screenshots resolved in settled captures; these were capture timing artifacts and are not reported as rendering defects.

Evidence retained alongside this report: `mobile-hero.jpg`, `mobile-menu.jpg`, `mobile-cream-purchase.jpg` (pre-fix), `mobile-cream-purchase-final.jpg` (verified correction). No implementation edits made by this reviewer.

## Final confirmation pass — supersedes open P1 findings above

**Final verdict: pass for the reviewed mobile surfaces.** A bounded confirmation pass used a fresh separate native Chrome tab, explicit reloads, bundle/homepage at 375×812 and the new `/the-ritual/` at 390×844. Both prior P1 findings are closed:

- Bundle caption title now computes to **36px Unna**, with 16px supporting copy. It reads as the campaign proposition, with a clear €450 price and direct discovery/purchase jump. `mobile-bundle-final.jpg`.
- Caviar species label now sits in the solid ivory notes below the photograph. It no longer crosses dark pearls; both lines are readable. `mobile-caviar-final.jpg`.
- Homepage collection spacing now keeps image, step, product, benefit, price and action within a coherent pair of columns. The first row fits in approximately one viewport instead of requiring several screens. No cramped type or collisions observed at 375px. `mobile-collection-final.jpg`.
- New ritual editorial page preserves the ivory/graphite/Unna world with plain, useful application instructions, the canonical four-step names, correct prices/volumes and individual discovery links. Main copy measures 17px; shopping/discovery actions 16px. Document width equals scroll width (375px content area within a 390px viewport with scrollbar); no horizontal overflow. It feels like a continuation of the storefront rather than the previous unrelated editorial shell. `mobile-ritual-final.jpg`, `mobile-ritual-formula-final.jpg`.

No new material mobile defects found in this confirmation scope. The original P2 empty-cart simplification remains optional and does not block the finish. Physical iOS Safari and network-throttled performance remain outside this evidence. Temporary viewport override reset. No implementation changes made by reviewer.
