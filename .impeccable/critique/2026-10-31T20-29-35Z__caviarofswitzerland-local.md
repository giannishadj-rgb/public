---
target: website against attached luxury skincare benchmark
total_score: 30
max_score: 40
na_heuristics: 
p0_count: 0
p1_count: 2
target_identity: "url:http://caviarofswitzerland.local/"
timestamp: 2026-10-31T20-29-35Z
slug: caviarofswitzerland-local
---
# Luxury Skincare Website Critique

## Design Health Score

| # | Heuristic | Score | Key issue |
|---|---|---:|---|
| 1 | Visibility of System Status | 3/4 | Gallery, section, empty-cart, and sticky purchase states are clear; account validation and failure feedback are less visible. |
| 2 | Match System / Real World | 4/4 | Cleanse, Treat, Revive, Restore is natural and memorable; evidence is attached to real products and methods. |
| 3 | User Control and Freedom | 3/4 | Navigation and recovery are strong; the clipped mobile PDP index weakens control of the long page. |
| 4 | Consistency and Standards | 3/4 | The main editorial/commerce system is coherent; the account page retains undersized legacy gold/black styling. |
| 5 | Error Prevention | 2/4 | Quantity constraints are good, but account forms use novalidate and rely heavily on post-submit notices. |
| 6 | Recognition Rather Than Recall | 3/4 | Formula, ritual, price, and evidence context stays visible; icon-only bag controls require inference. |
| 7 | Flexibility and Efficiency | 2/4 | Sticky purchase controls help, but the seven-item PDP index and absent direct ritual finder slow selection. |
| 8 | Aesthetic and Minimalist Design | 4/4 | Excellent restraint, material photography, whitespace, and episodic dark contrast. |
| 9 | Error Recovery | 3/4 | Empty-cart recovery is excellent; account and add-to-bag failure recovery are less evident. |
| 10 | Help and Documentation | 3/4 | Help, FAQs, shipping, returns, and contact exist; human help is buried away from purchase decisions. |
| **Total** | | **30/40** | **Good** |

## Design Specificity Verdict

Highly authored and product-specific, approximately 8.5/10. Warm ivory, polished silver, caviar macro imagery, Matterhorn/Zug provenance, the four-step ritual, and product-specific studies could not be transferred unchanged to an unrelated skincare brand. The homepage is the strongest expression: object-led desire, four-formula selection, provenance, measured efficacy, ritual, and set purchase.

The weaker layer is service and utility. The account page contains legacy styling and very small type, while the purchase experience offers ordinary shipping, returns, and security rather than a visible luxury service system.

The deterministic detector reported 316 findings: 302 advisories and 14 warnings. Most are low-value token-drift reports in legacy or staging CSS. Credible findings are the 8-13px account roles and 10px 404 actions. Inter overuse, Source Serif declarations, Montserrat fallback, and side-tab reports are false positives or non-rendering legacy declarations.

Browser overlay injection was unavailable because the browser access host was unavailable. Fresh Playwright desktop/mobile captures and DOM-backed tests were used instead.

## Overall Impression

The site already looks and behaves like a serious luxury skincare house. Its biggest opportunity is not more decoration; it is making the concierge, gifting, sample, personalisation, and replenishment experience as real and visible as the photography and science.

## What's Working

1. Real material properties carry the luxury: glass, chrome, cream texture, caviar pearls, water, skin, and alpine photography.
2. Proof is unusually precise and responsibly framed: result, product, method, panel, age range, and duration stay connected.
3. Mobile preserves the editorial world instead of collapsing into a generic commerce feed; the sticky purchase action is especially effective.

## Cognitive Load

Moderate: 2 of 8 checklist failures. Chunking and minimal choices fail on the seven-item PDP index. Decision points over four items include the five-item desktop navigation, seven-item PDP section index, six-question FAQ, and dense desktop footer columns. The core purchase choice remains disciplined: four formulas or one complete ritual.

## Emotional Journey

The homepage has a convincing desire-to-proof-to-ritual arc. The shop's black Complete Ritual chapter acts as ceremony rather than generic darkness. The PDP begins decisively, then enters a long rational valley of science and ingredients before recovering with the ritual and FAQ. Cart recovery is calm and useful. Account and footer utility surfaces end the journey more conventionally than the brand promise suggests.

## Priority Issues

### [P1] Human service is promised more strongly than it is delivered

At this price point, shipping, returns, and secure checkout are baseline. The account promises personalised ritual guidance and a ritual profile without an obvious operational service. Ship and surface a real consultation, sample selection, gifting note/wrapping, replenishment reminder, or named concierge action beside Add to Bag; otherwise remove unsupported promises.

Suggested command: `$impeccable shape`.

### [P1] Account typography and visual language fall below the site's own floor

The account source contains many 8-13px roles and legacy gold accents. Raise reading text to 16px, secondary detail to 14px, labels to 12px, actions to 12.5px, and maintain 44px targets. Bring the page into the graphite, chrome, champagne, and hairline system.

Suggested command: `$impeccable typeset`.

### [P2] Mobile PDP navigation exposes seven clipped choices

Only the first destinations are visible at 390px and discoverability of the rest is weak. Reduce the index to four chapters or use an On this page disclosure. If horizontal scrolling remains, add edge fade, snap, and current-position feedback.

Suggested command: `$impeccable adapt`.

### [P2] Product-card purchase actions are visually icon-only

The bag glyph is elegant but ambiguous to first-time shoppers. Use a compact persistent Add or Add to bag label, retaining the icon as reinforcement.

Suggested command: `$impeccable clarify`.

### [P2] Secondary edge surfaces need cleanup

404 actions render at 10px and the sticky-bar accessibility test expects obsolete class `is-visible` while production uses `is-on`. Raise the 404 actions to the established action token and repair the stale assertion.

Suggested command: `$impeccable polish`.

## Persona Red Flags

**Jordan, first-time buyer:** icon-only bag actions require interpretation; the clipped PDP index hides later sections; no visible Which formula is right for me human pathway appears beside the purchase decision.

**Riley, stress tester:** Ritual profile and personalised concierge guidance appear as promises without an evident feature; account forms use novalidate and need explicit verification for malformed, Unicode, interrupted, and preserved submissions.

**Casey, distracted mobile shopper:** the sticky Add to Bag bar is excellent, but the long PDP and seven-item horizontal index make resumption difficult after interruption.

## Minor Observations

- The black Complete Ritual chapter is a successful exception to the light system.
- Empty-cart recovery preserves the four-product proposition and gives two clear exits.
- Desktop footer density is high, but mobile accordion grouping contains it.
- Motion is restrained and reduced-motion handling is present.
- The 404's 10px actions are the clearest current Web Interface Guidelines violation.
- All direct Axe scans for home, shop, PDP, cart, and account passed across Chromium and iPhone/WebKit with no serious or critical violations.

## Questions to Consider

- What real human service can the house own so service feels as premium as the photography?
- If the range has four formulas, why does a PDP need seven navigation destinations?
- Is Ritual profile a shipped capability, an upcoming feature, or decorative copy?
- Could the final emotional peak be consultation, sampling, gifting, or replenishment rather than another footer?
- Would a confident store say Add to bag plainly on every product card?
