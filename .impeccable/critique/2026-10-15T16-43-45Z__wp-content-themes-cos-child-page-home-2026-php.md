---
target: "homepage: luxury, prestige, elegance and updated customer personas"
total_score: 24
max_score: 32
na_heuristics: 5,9
p0_count: 0
p1_count: 0
target_identity: "file:C:\\Users\\giann\\Local Sites\\studio-caviar-of-switzerland-2026-05-28-10-36-43\\app\\public\\wp-content\\themes\\cos-child\\page-home-2026.php"
target_fingerprint: "sha256:fdc0792275619ad5ff8e5f810de9f1ad70d346d00c51348846446ba51a831c3f"
target_path: "C:\\Users\\giann\\Local Sites\\studio-caviar-of-switzerland-2026-05-28-10-36-43\\app\\public\\wp-content\\themes\\cos-child\\page-home-2026.php"
timestamp: 2026-10-15T16-43-45Z
slug: wp-content-themes-cos-child-page-home-2026-php
---
Method: dual-agent (A: /root/home_a_oct15 · B: /root/home_b_oct15)

# Homepage critique — 15 October 2026

Target: `wp-content/themes/cos-child/page-home-2026.php`, current local homepage. Assessment uses the 14 October rebuild, current PRODUCT.md personas and current DESIGN.md. Persuade mode: an unfamiliar Swiss skincare house must earn desire and confidence. No website code changed.

## Design specificity and overall impression

The rebuilt homepage feels authored, elegant and convincingly premium. Champagne silk, polished silver packaging, restrained serif typography and the four-product range belong to one visual world. Product photography and editorial pacing convey luxury without crowding the page. Specific provenance and properly attributed clinical results give that luxury substance.

Preserve this direction. The next pass should restore the information and legibility mobile customers need to choose confidently, rather than change the aesthetic again.

The detector ran once over the homepage template, component directory and helper file and returned exit 0 / JSON `[]`, zero findings and no false positives. Manual browser inspection found two responsive/legibility issues the static scan did not surface. Native evaluate is read-only, so no detector overlay was injected; screenshots and computed DOM/AX supplied the fallback evidence.

## Design health

| # | Heuristic | Score | Main observation |
|---|---|---:|---|
| 1 | Visibility of system status | 3 | Mobile menu states clear; transaction feedback untested |
| 2 | Match with real world | 3 | Familiar steps; scientific detail explained progressively |
| 3 | User control and freedom | 3 | Menu closes with Escape and returns focus |
| 4 | Consistency and standards | 4 | Cohesive typography, surfaces and product/action patterns |
| 5 | Error prevention | n/a | No form/cart/checkout scenario exercised |
| 6 | Recognition rather than recall | 2 | Mobile omits comparison benefits visible on desktop |
| 7 | Flexibility and efficiency | 3 | Individual, ritual and deeper evidence routes available |
| 8 | Aesthetic and minimalist design | 4 | Strong restraint, hierarchy and material identity |
| 9 | Error recovery | n/a | No error-producing flow tested |
| 10 | Help and documentation | 2 | Contextual science and care routes exist; destinations unreviewed |
| **Total** | | **24/32 — Good (75%)** | Eight applicable homepage-discovery heuristics |

This is a scoped usability review, not a numerical luxury certification or whole-store audit. Different applicable heuristics and scope prevent direct comparison with prior whole-site or homepage scores.

## What works

- **Hero composition:** Desktop text/product balance is composed and restrained. At 390×844 the 40px heading, product, provenance credit and primary action remain within the opening screen; CTA bounds approximately y761–809. Desktop heading measures 60px.
- **Desktop comparison:** Four products show aligned step, benefit, price and size information. Four formulas are a manageable choice, consistent with the curated positioning.
- **Clinical presentation:** Serum/eye instrument results are attributed to their individual products, method, panel ages and duration. Cream satisfaction is explicitly self-reported and visually secondary. This validates presentation, not study truth.
- **Navigation and responsive foundations:** Mobile menu rows are generous, expansion is communicated, and Escape closes the menu with focus return. No document horizontal overflow at 1536px, 390px or 320px. Ritual's horizontal track is intentional and is not page overflow.

## Priority issues

### [P2] Mobile collection hides the benefits needed for product choice

**Location:** `wp-content/themes/cos-child/assets/css/hx/collection.css:124–125`, `.hxc__benefit`; larger breakpoint restores it around line 188.

All four benefits render at 0×0 on phones. Desktop shows, for example, “Visibly firms & smooths”; mobile presents the serum name, step, price, volume and Discover action without that cue. This omission is confirmed in computed geometry, AX and settled screenshots.

**Impact:** The mobile researcher must infer suitability from product names or open multiple PDPs. The page simplifies its appearance by removing customer-relevant information.

**Recommendation:** Retain one concise, verified benefit per formula on phones. Accept a little additional height or adjust the comparison layout. Keep current images, type voice, prices and direct product links. Shorten wording only if needed without strengthening claims.

**Suggested command:** `$impeccable adapt`; `$impeccable clarify` only if existing benefit text needs shortening.

### [P2] Purchase labels and evidence qualifications are too small for the intended reading experience

**Location:** `assets/css/hx/system.css` type tokens and `.hx-btn` rules (around lines 155–156 and 302 onward), plus `assets/css/hx/science.css:75–78`.

At 390px the hero CTA measures 11px, closing CTA 12px, hero evidence credit 13px, product price/size 14px and study product/method details 14px. The hero action is 48px high, so its hit area is generous; its label is the issue. Clinical cards are about 155px wide at 390px and 122px at 320px, compounding supporting-text density.

**Impact:** Women in the primary 45–65+ audience must work harder to read the information that supports purchase and trust. Display typography receives generous scale while decision-critical details are compressed.

**Recommendation:** Use the established 15–16px action/purchase-detail scale and a 16px target for reading copy and study explanations. Reduce tracking where necessary and allow wrapping or a single-column mobile evidence layout if that gives the text enough width. Preserve figure hierarchy and minimum target sizes.

**Suggested commands:** `$impeccable typeset`, then `$impeccable polish` for final spacing and fit.

Counts: 0 P0, 0 P1, 2 P2 priority findings. No release-blocking defect was established in this bounded homepage review. Small text alone is not a proven WCAG violation.

## Cognitive load and emotional journey

Primary choice load is low: four products and a clear ritual structure. Single focus, chunking, grouping, hierarchy and sequential storytelling work. Working memory is the clear weak point on mobile because benefits disappear; the refined presentation asks the user to infer more. No need to invent additional choice-count failures or remove useful routes mechanically.

The hero conveys composure and material quality. Collection introduces the practical choice; ingredient/provenance chapters build character; clinical detail supplies reassurance; the closing ritual and action supply direction. The emotional world is coherent. Mobile comprehension and small qualifications are the principal remaining friction.

## Persona red flags

- **Affluent Mature Skincare Buyer:** Study ages and concerns are relevant; small method/action text weakens comfortable reading.
- **Global Luxury Discoverer:** Consistent packaging, editorial scale and Swiss provenance support prestige. International service assurances appear later; their actual destination policies were outside scope.
- **Mobile Beauty Researcher:** Attractive product cards omit the exact benefit that helps identify a suitable formula. Restoring that information is the highest-value refinement.

## Minor observations and research questions

- The headline/credit identifies Swiss caviar skincare and a four-formula ritual. Whether first-time visitors understand all four are full-size before clicking is a research question, not a confirmed defect.
- “Instrument-measured results” is a broad hero teaser; the detailed section properly distinguishes results. Do not invent a ritual-wide study or stronger claim.
- Homepage bundle-price omission is intentional; both ritual actions lead to its PDP. No destination purchase test was performed, so price omission is an information tradeoff, not a broken path.
- New `hx` styles and motion are the current visual truth. Old `h26` rules are not evidence of current rendered defects.
- Offscreen ritual cards and transient blank reveal captures were not misclassified as broken layout or missing content.

## Recommended actions

1. `$impeccable adapt` — restore mobile collection benefits and comfortable comparison.
2. `$impeccable typeset` — increase purchase/action and study-reading text while retaining the current typography roles.
3. `$impeccable polish` — verify resulting spacing and wrapping at 320px, 390px and desktop.

Commands can run individually or together; re-run critique after fixes. Questions skipped: only two priority issues, both narrowly scoped refinements within the existing direction.

## Evidence limits

Independent fresh Chrome tabs. A inspected approximately 1536×855 and 390×844; B inspected 1536×855, 390×844 and 320×844. Native recovery succeeded after an initial detached-tab error. Settled viewport screenshots used; full-page lazy/reveal omissions excluded from findings. Document scroll widths matched available content widths (1521/375/305). A exercised menu Escape/focus return; B inspected computed geometry and current style loading. No checkout, form submission, real iPhone/Safari, screen-reader speech, exhaustive keyboard/zoom/contrast or performance test. Temporary viewports reset, B tab closed and detector JSON removed. No source edit, live server or overlay.
