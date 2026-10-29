---
target: the homepage
total_score: 23
max_score: 32
na_heuristics: 7,10
p0_count: 0
p1_count: 2
target_identity: "file:C:\\Users\\giann\\Local Sites\\studio-caviar-of-switzerland-2026-05-28-10-36-43\\app\\public\\wp-content\\themes\\cos-child\\page-home-2026.php"
target_fingerprint: "sha256:b2559c905855a5d86172f983684a930bb52d55d8f93b81208a973ea6682088b5"
target_path: "C:\\Users\\giann\\Local Sites\\studio-caviar-of-switzerland-2026-05-28-10-36-43\\app\\public\\wp-content\\themes\\cos-child\\page-home-2026.php"
timestamp: 2026-10-29T06-33-34Z
slug: wp-content-themes-cos-child-page-home-2026-php
closed: true
---
Method: dual-agent (A: design-review agent · B: detector + browser agent)

## Design Health Score
| # | Heuristic | Score | Key Issue |
|---|---|---|---|
| 1 | Visibility of System Status | 4 | Bag add: mini-bag, live region, badge |
| 2 | Match System / Real World | 3 | Cutometry / profilometry / CRO unexplained |
| 3 | User Control and Freedom | 3 | No remove in mini-bag; mobile ritual carousel swipe-only |
| 4 | Consistency and Standards | 2 | Closing repeats hero h1; provenance kicker labels; Inter vs Cormorant h3s; 3 CTA styles |
| 5 | Error Prevention | 3 | Single-size add is safe |
| 6 | Recognition Rather Than Recall | 2 | Ritual panels hide product names (sr-only), instruction hover-only on desktop |
| 7 | Flexibility and Efficiency | n/a | Persuade surface |
| 8 | Aesthetic and Minimalist Design | 3 | Redundant links (/the-ritual/ x3), echoed closing |
| 9 | Error Recovery | 3 | Newsletter errors one round at a time |
| 10 | Help and Documentation | n/a | Marketing homepage |
| Total | | 23/32 | Good (72%) |

## Design Specificity Verdict
Mostly authored (graphite stage packshots, Matterhorn plate + Zug/20+/ISO band, formula-by-formula Measured, verb panels). Generic skeleton: centred jar hero, Sensai-style story triptych, closing echoes hero. Detector (URL, 1440/390): kicker-above-heading (real), italic-serif-display + overused-font (brand faces, accepted), clipped-overflow x2 (false positives: hero clip, ritual scroller). Source scan clean; static-HTML flat-type-hierarchy false positive.

## Priority Issues
- [P1] The Complete Ritual invisible as a product (no image/price/contents; only a closing text link; hero CTA goes to editorial). Fix: set object (23723) + price + contents + assurances in closing; owner rulings first (closing.php keeps price off; cleanser-in-box undecided). layout, clarify.
- [P1] Ritual panels never show product names at rest (sr-only); instruction hover-only on desktop. Fix: visible product name under Step N. clarify.
- [P2] Closing h2 = hero h1 verbatim; weak peak-end, louder newsletter footer. Fix: own closing line + set object. clarify, delight.
- [P2] Consistency drift: provenance kicker labels (11px) vs no-eyebrow rule; Our story h3 Inter vs Cormorant elsewhere; "Precision is not a style here; it is…" not-X-but-Y line; mobile menu disc bullets; Measured caption under middle photo only (misaligns −38%, implies others are participants). polish, typeset.
- [P2] Mobile: collection bag buttons 36×36 (<44); footer Privacy link 16px tall; carousel no controls/count; LCP 5.2s desktop from hero headline light-sweep (re-measure on staging). adapt, harden.

## Persona Red Flags
Jordan: no who-it's-for above fold; hero CTA editorial; redundant story line. Casey: 36px bag targets next to tile link; swipe-only carousel; ~150px empty bands. Riley: no remove in mini-bag; sequential newsletter errors; sr separators; site served WP maintenance 503 mid-session. Margaret (58, iPhone): imagery all ~25–35 vs 42–65 study panels; jargon at 14px grey; no set price; trust facts scattered.

## Minor Observations
Flat mobile display scale (32/22/18) lets +47% outrank h2s; mini-bag serum thumb differs from tile; ritual cleanser photo reads body-care, gold ring visible in colour on mobile; hero focus-pull reduced-motion check; Discover + Our history both → /heritage/; desktop Measured middle column staggered.

## Questions to Consider
1. Why is the set the one product you can't see, price or add? 2. Would a 58-year-old see herself here? 3. Is the closing a destination or an echo?
