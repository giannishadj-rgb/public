# Homepage technical audit — 6 October 2026

Scope: `/audit` using impeccable. Current local homepage at http://caviarofswitzerland.local/, homepage template, components, data/enqueue code, styles, JavaScript and shared newsletter form. No homepage code changed.

## Implementation integrity verdict

**Pass within the inspected scope.** The implementation expresses a coherent skincare storefront: four named formulas, ingredient and manufacturing sections, differentiated clinical measures, and a priced complete ritual. The bundled detector ran once over the homepage PHP template, components and helper file and returned zero findings. That result is a narrow static check, not certification of the browser experience or marketing claims.

## Audit health score

| Dimension | Score | Key finding |
|---|---:|---|
| Accessibility | 3/4 | Newsletter outcome messages lack announcement semantics; menu focus return works |
| Performance | 3/4 | Responsive images, loading priorities and deferred script; runtime performance unmeasured |
| Responsive design | 3/4 | No document overflow at tested sizes; caption link only 17px high |
| Theming | 3/4 | Scoped palette tokens work, but component colors still bypass tokens |
| Implementation integrity | 4/4 | Product-specific structure and clean detector result |
| **Total** | **16/20** | **Good — address weak dimensions** |

Scores reflect inspected implementation, not a full WCAG conformance assessment. The theming score assesses token consistency more strictly than the earlier mixed design/technical report; it does not indicate a visual regression. An automatic dark-mode toggle is not required for this deliberately composed brand palette.

## Executive summary

Found **0 P0, 1 P1, 1 P2 and 1 P3** issues. Prioritize newsletter status announcements, then the caption target. Token cleanup is optional maintenance. No blocking task-completion failure was observed in the interactions exercised.

## Detailed findings

### [P1] Newsletter outcomes are not announced accessibly

- **Location:** `wp-content/themes/cos-child/inc/forms.php:36–38` and `:54`.
- **Category:** Accessibility.
- **Evidence:** `showError()` appends an ordinary paragraph with no `role` or `aria-live`. Success replaces the entire form contents with another ordinary paragraph. Neither branch moves focus to the new message. The rendered form has no status region. A separate page live region exists, but these code paths do not update it.
- **Impact:** Screen-reader users can miss the subscription result. Removing the focused submit control on success can also leave keyboard users without a deliberate focus destination.
- **Standard:** WCAG 2.2, 4.1.3 Status Messages (AA): these dynamically inserted outcome messages need programmatic status semantics when focus is not moved to them.
- **Recommendation:** Render a persistent `role="status"` region before submission and update it with the result. Keep the form/control stable or deliberately manage focus if replacing them. Announce errors, and connect field-specific errors to the field when applicable.
- **Suggested command:** `$impeccable harden`.
- **Verification limit:** Verified from source and initial rendered DOM. No real subscription was sent, network outcomes were not simulated, and screen-reader speech was not tested.

### [P2] Texture caption product link has a small touch target

- **Location:** `wp-content/themes/cos-child/template-parts/home-2026/final.php:36`; `assets/css/home-2026-nocturne.css:438`.
- **Category:** Responsive design / accessibility.
- **Evidence:** The “24h Regeneration Cream” caption is an inline anchor measuring approximately **165 × 17px** at desktop and 390px phone width. Its rule styles the underline but adds no target padding.
- **Impact:** Tapping the product link requires more precision than the surrounding mobile controls.
- **Standard:** Below the audit's preferred 44px target height. This measurement alone does **not** establish a WCAG AA violation: inline and spacing exceptions must be considered.
- **Recommendation:** Give the caption anchor an inline-flex hit area with a 44px minimum height, retaining the restrained typography and underline. Adjust surrounding spacing to preserve the current composition.
- **Suggested command:** `$impeccable adapt`.

### [P3] Component colors bypass the existing palette tokens

- **Location:** `wp-content/themes/cos-child/assets/css/home-2026-nocturne.css:79–80`, `:205–210`, `:255–262` and `:446–447`.
- **Category:** Theming.
- **Evidence:** Buttons, ritual text/focus rules and clinical copy directly repeat colors such as `#151515`, `#484848` and `#F2F2EF`, alongside an existing scoped token system.
- **Impact:** No current visible failure was verified. Future palette changes can update inherited text while leaving these controls or evidence captions behind.
- **Recommendation:** Define semantic section/button tokens for these intentional variants and use them consistently. Preserve the current palette.
- **Suggested command:** `$impeccable polish`.

## Patterns and positive findings

The small caption target is isolated among the mobile controls measured; carousel buttons are 44 × 44px. Color literals recur across several sections and represent maintenance drift rather than a reason to redesign the page.

- One main H1 and a coherent H2 sequence describe the homepage sections.
- Main images provide intrinsic dimensions; product and manufacturing photography have descriptive alt text. The decorative macro image uses empty alt text.
- The hero is eager-loaded with responsive AVIF preload and high priority; supporting imagery is lazy-loaded. Homepage JavaScript is deferred.
- Reveal enhancement preserves content when JavaScript or IntersectionObserver is unavailable. Reduced motion skips reveals; carousel clicks choose automatic rather than smooth scrolling when reduced motion is requested.
- Opening the mobile navigation and pressing Escape closes it, resets `aria-expanded` and returns focus to `cos-mob-btn`.
- Native mobile rail scrolling is paired with named pagination controls and current-item state, rather than mouse-only dragging.
- Light and dark chapters use locally scoped palette overrides and explicit color-scheme settings.
- Newsletter email and consent fields have labels and native required validation.

## Validation and limitations

Fresh Chrome tab, desktop 1536px viewport and emulated widths 390px and 320px. Document client/scroll widths matched: 1521/1521, 375/375 and 305/305 respectively. Mobile hero was inspected visually; caption/dot targets were measured from rendered bounds. Native navigation Escape/focus return was exercised. Source inspection covered loading priorities, motion behavior, newsletter outcome paths and palette usage.

Not tested: physical touch/swipe gestures, a screen reader, all keyboard paths, 200% text enlargement, every intermediate breakpoint, real newsletter submission, checkout, network failure behavior or Core Web Vitals. Emulated viewport layout is not evidence that physical gestures work. No Lighthouse/axe conformance result or production speed claim is made.

## Recommended actions

1. **[P1] `$impeccable harden`** — announce newsletter success/error states and preserve a deliberate focus destination.
2. **[P2] `$impeccable adapt`** — enlarge the texture caption link hit area.
3. **[P3] `$impeccable polish`** — consolidate component palette literals into semantic tokens and check the resulting focus/hover styling.

These commands can run individually, together, or in the preferred order. Re-run `$impeccable audit` after fixes.
