# Homepage technical review — 18 October 2026

Final live implementation tested with local Playwright Chromium and axe-core 4.12.1. Homepage only; destination pages were read-only smoke checks, apart from a disposable browser cart. No order submitted.

## Results

- Responsive Chromium: 360, 390, 430, 768, 1024, 1440, 1920px. No document horizontal overflow, broken images, browser exceptions, console errors, or HTTP failure responses. Product rail clipping is intentional and contained.
- Hero: checked 360×640 and 360×800 screenshots. Headline, lead, primary action and secondary collection link fit within the viewport. Product label remains unobscured; shorter frame places title over reflection, not packaging. Normal hero copy bounds also inspected across the seven widths.
- Accessibility: zero axe WCAG 2 A/AA and 2.1 AA violations in main at all seven widths; zero full-page mobile violations. This automated result is not a claim of complete WCAG conformance.
- Keyboard: skip link is first and becomes visible on focus. Mobile menu contains focus, includes usable 44px controls, locks background scrolling, and closes with Escape. Carousel controls respond to Enter and Space.
- Carousel: all four products reachable forward and backward. Last product fully visible at 390px; disabled states correctly reflect rail endpoints. Reduced motion has no active homepage animation.
- Navigation: all ten unique homepage internal destinations, including the collection anchor and existing product/science/ritual paths, returned HTTP 200.
- Commerce: homepage cleanser card → actual product → Add to bag → cart → checkout. One cleanser at €55 appeared in bag and cart; billing first name, email and place-order controls appeared at checkout. No purchase submitted; context discarded.
- Live main text has no replacement characters, mojibake patterns, or stray question marks.
- Mobile WebKit installed and smoke passed at 390×844: no document overflow, headline/action visible, navigation opens.

## Evidence

- `technical-results.json`: seven-width geometry, axe, network, route and menu results.
- `commerce-carousel-results.json`: rail endpoints, keyboard, complete commerce smoke and mobile full-page axe.
- `viewport-supplement.json`: short hero dimensions and WebKit smoke.
- `screenshots/qa-mobile-360x640.png`, `screenshots/qa-mobile-360x800.png`: short/mobile hero checks.
- Existing `current-desktop.png` and `current-mobile.png` supplied to independent visual reviewers separately.

## Actionable defects

None found in this technical pass. No production bundle task exists in this WordPress theme; parent owner separately verifies changed PHP syntax and first-party JS syntax. Lighthouse and real-user performance are not measured by this report. Safari coverage is a smoke check, not a full device audit.
