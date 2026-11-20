---
target: the homepage
total_score: 22
max_score: 32
na_heuristics: 7,10
p0_count: 0
p1_count: 2
target_identity: "file:C:\\Users\\giann\\Local Sites\\studio-caviar-of-switzerland-2026-05-28-10-36-43\\app\\public\\wp-content\\themes\\cos-child\\page-home-2026.php"
target_fingerprint: "sha256:b2559c905855a5d86172f983684a930bb52d55d8f93b81208a973ea6682088b5"
target_path: "C:\\Users\\giann\\Local Sites\\studio-caviar-of-switzerland-2026-05-28-10-36-43\\app\\public\\wp-content\\themes\\cos-child\\page-home-2026.php"
timestamp: 2026-11-04T15-51-32Z
slug: wp-content-themes-cos-child-page-home-2026-php
---
Method: dual-agent (A: design review sub-agent · B: detector sub-agent)

## Design Health Score
| # | Heuristic | Score | Key issue |
|---|---|---|---|
| 1 | Visibility of system status | 3 | Add-to-bag confirms well (ADDED, badge, mini-bag, SR announcement); no pending state |
| 2 | Match system / real world | 3 | Calm plain copy; "Revive/Restore", "Caviar Circle" are brand words |
| 3 | User control & freedom | 3 | Mobile ritual carousel swipe-only |
| 4 | Consistency & standards | 2 | 3 CTA styles; Add button moves position desktop vs mobile; 3 links to /heritage/; mini-bag shadow |
| 5 | Error prevention | 3 | Idempotent add; newsletter accepts "anna@example" |
| 6 | Recognition over recall | 2 | Desktop ritual how-to hover-only; set saving shown ~5,000px from the prices |
| 7 | Flexibility & efficiency | n/a | Persuade surface |
| 8 | Aesthetic & minimalist | 3 | Very restrained; middle third is mood without new information |
| 9 | Error recovery | 3 | Clear inline newsletter error in live region |
| 10 | Help & documentation | n/a | Homepage |
| Total | | 22/32 (69%) | Acceptable, at the edge of Good |

## Design specificity
Authored. Hero, packshot tiles, Measured evidence, closing mirror-glass callouts are this brand. Generic stretch: "Our story" Sensai-style triptych + B&W vertical-word ritual panels. DESIGN.md typography prose (caps headings) no longer matches render.
Detector: CLI 0 findings (10 files). Browser: 11 desktop / 8 mobile — clipped-overflow-container (intentional parallax/clip), low-contrast x3 on .hx-link (false positive: gradient underline read as bg), italic-serif-display x2, overused-font Inter 73% (style calls). axe: 0 site violations. No overflow, no console errors. Tap targets <44: .hxc__bag 38px tall, .hxz__tag 27px, nav 18px, footer links 34px, consent link 17px.

## Priority issues
- [P1] Eye-cream result (−38%, women 42–65) beside young campaign models, caption removed (measured.php:59 'credit' => '', committed in 1ede23a; was "Campaign photograph, not study participants"). Fix: restore caption or use hand/pack image.
- [P1] .hxc__bag 142x38 / 76x38 under 44px floor; mobile button left-aligned under centred caption. Fix: min-height 48px, centre on phones.
- [P2] Desktop ritual explanations hover-only (final.php .hxr__more/.hxr__how). Fix: one-line how at rest.
- [P2] Middle third (#caviar + #heritage, ~2,200/2,500px) repeats "Swiss/Zug" without new information; 3 links to /heritage/. Fix: one factual line per story panel, drop Discover button, shorten plate on phones.
- [P3] Set offer only at the end; hero CTA sells €450 before any price. Fix: quiet line under collection grid.

## Persona red flags
Jordan: no "what caviar does" above fold; story taglines don't answer why caviar.
Casey: empty ivory below mobile hero jar; 38px ADD; ritual carousel easy to scroll past; 9,100px page.
Riley: email validation weak; hover-gated content; 18px nav hit boxes; mini-bag thumbnail ≠ tile packshot.
55-year-old on iPhone: uncaptioned young faces beside eye result; 12px grey caps labels effortful; only one age-relevant benefit line in collection.

## Minor
"Our story" title + "Our story begins…" repeat; "Four formulas, one daily ritual" repeats hero; redundant Home nav link; mobile heritage hairline reads as underline; gold ring in Cleanse panel photo; weak signature row after closing; dark footer only dark ground; "dark circles"/"replenishes lipids" not in CLAIMS-REGISTER; "Read the studies" isolated at right edge; .hx-link and product names render 12px vs 15px base.

## Questions
1. Why does Measured come after the mood triptych rather than straight after the collection?
2. Should the hero sell the €450 set before any price is seen?
3. Why is the ritual's how-to hover-gated on desktop and swipe-only on mobile?
