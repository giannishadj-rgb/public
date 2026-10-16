---
target: homepage
total_score: 31
max_score: 36
na_heuristics: 7
p0_count: 0
p1_count: 0
target_identity: "file:C:\\Users\\giann\\Local Sites\\studio-caviar-of-switzerland-2026-05-28-10-36-43\\app\\public\\wp-content\\themes\\cos-child\\page-home-2026.php"
target_fingerprint: "sha256:92fe9456d1bf401ca9b4dc5ac2936526a36ad6969586e7800072f530fc27eb2e"
target_path: "C:\\Users\\giann\\Local Sites\\studio-caviar-of-switzerland-2026-05-28-10-36-43\\app\\public\\wp-content\\themes\\cos-child\\page-home-2026.php"
timestamp: 2026-10-12T14-58-19Z
slug: wp-content-themes-cos-child-page-home-2026-php
---
Method: dual-agent (A: /root/critique_a_oct12 · B: /root/critique_b_oct12)

# Homepage critique — 12 October 2026

Target: `wp-content/themes/cos-child/page-home-2026.php`. Current live homepage and current PRODUCT.md/DESIGN.md. Persuade mode: help women aged 45–55 want and trust an unfamiliar Swiss luxury skincare house. No website code changed.

## Design specificity and overall impression

Authored for this product. Cream on pearl silk, chrome packaging, literal caviar, Zug provenance, a small four-formula range and product-specific clinical findings create a coherent identity. The serif, landscape and portraits are familiar luxury conventions, but real objects and factual product detail give them substance.

Quiet, polished and desirable. Preserve the current visual direction. The strongest next improvement is to make the transition from an aspirational image to understanding the complete offer more immediate, then refine narrow-screen reading.

The independent detector ran once over the homepage PHP, component directory and helper file: exit 0, JSON `[]`, zero findings/rules/locations and no false positives. It cannot evaluate persuasion or certify accessibility. Native browser evaluation is read-only; no detector overlay was injected. Source, DOM measurements and native screenshots supplied the fallback evidence.

## Design health

| # | Heuristic | Score | Key finding |
|---|---|---:|---|
| 1 | Visibility of system status | 3 | State semantics present; submission outcomes not executed |
| 2 | Match with real world | 3 | Clear benefits/instructions; ingredient language still technical |
| 3 | User control and freedom | 3 | Conventional navigation and product links; no blocking interruption observed |
| 4 | Consistency and standards | 4 | Cohesive typography, photography and controls |
| 5 | Error prevention | 3 | Explicit email label and native required consent |
| 6 | Recognition rather than recall | 3 | Product facts grouped; cream-only hero asks visitors to infer the full set |
| 7 | Flexibility and efficiency | n/a | Expert accelerators are not relevant to this persuasive surface |
| 8 | Aesthetic and minimalist design | 3 | Strong hierarchy; portrait qualification arrives late on mobile |
| 9 | Error recovery | 3 | Source retains input and supports retry/Contact; runtime untested |
| 10 | Help and documentation | 3 | Studies, help and service routes available; destinations unreviewed |
| **Total** | | **31/36 — Good (86.1%)** | Nine applicable heuristics; bounded review, not conversion measurement |

Synthesis note: A scored 28/32 with error recovery unscored. B's independent source evidence supports a provisional 3/4 for recovery, yielding 31/36. This does not claim newsletter delivery or spoken announcements were tested.

## What works

- Product photography leads. At 390px the hero title and action are immediately visible; at 320px the 38px title, 16px body and 15px CTA fit within the content width.
- Collection names, benefits, volumes and individual prices stay together. Four desktop columns and two mobile columns make comparison straightforward; the current grid replaces the old carousel.
- The closing editorial board explains actual use, with numbered instructions and a final purchase CTA plus shipping/returns. Respect the owner's decision that bundle pricing belongs on its product page.
- Source improvements are real: clinical panel/method reading text is 16px; product attribution is 15px. Newsletter now has an explicit label, persistent polite status region, input-preserving failure path, Contact alternative and focused confirmation on success. Do not carry forward the former missing-announcement defect as a current finding.

## Priority issues

### [P2] Explain the complete offer behind the cream-only hero

**Location:** `template-parts/home-2026/hero.php`, hero image, lede and CTA.

The photograph shows one cream jar while the sole action sells the Complete Ritual. “Four essential formulas” supplies a number but does not identify the set. The collection resolves this after scrolling; an unfamiliar buyer still has to infer what the first action offers.

**Fix:** Preserve the image and one CTA. Make the lede explicitly describe the four-formula daily set, naming the formula types if that fits the composition. For example: “Cleanser, serum, eye cream and face cream. Four caviar formulas, developed and made in Switzerland.” This is a proposed copy direction using existing product facts, not approved replacement copy. No need to restore the secondary link or homepage bundle price. **Command:** `$impeccable clarify`.

### [P2] Place the illustrative portrait qualification beside the mobile photograph

**Location:** `template-parts/home-2026/trust.php`, `.h26e__credit` and mobile evidence layout.

Desktop shows “Illustrative portrait; not a study participant” within the photographic composition. Mobile shows the marked portrait, then two substantial result blocks, with the qualification later in the secondary note. A skimming visitor can see measurement markers on a face before learning its illustrative status.

**Fix:** Position the existing credit immediately after the mobile image, before the results. Preserve the annotated-photo direction and all factual evidence. This is an implication risk, not a claim that the page explicitly identifies the model as a participant. **Command:** `$impeccable polish`.

### [P2] Give ritual explanations more width on narrow phones

**Location:** mobile `.h26r__tile` / `.h26r__how` rules in `assets/css/home-2026.css`.

Below 520px, each step uses a photo beside instructions. At 320px the instruction column measures about 147px; 16px paragraphs become 176–224px tall, leaving substantial empty space below the smaller image. At 390px the column is roughly 189px. The text is readable but unnecessarily fragmented at the narrowest width.

**Fix:** Keep the model imagery and step instruction format. At the narrowest breakpoint, let the explanation span the full row beneath the image/title, or otherwise widen its column. Retain 16px copy rather than shrinking text to fit. Verify 320px and 390px. **Command:** `$impeccable adapt`.

### [P3] Lead the caviar explanation with plain-language meaning

**Location:** `template-parts/home-2026/caviar.php`, ingredient explanation.

“Acipenser baerii”, “micro-encapsulated” and “SYN-COLL peptide” accumulate before a novice reaches their practical meaning. Accurate terminology is useful, but it adds decoding effort to a persuasive page.

**Fix:** Put the existing verified plain-language benefit first, retaining scientific detail where useful and routing deeper explanation through “Why caviar.” Do not add new efficacy claims or alter confirmed provenance. **Command:** `$impeccable clarify`.

Counts: 0 P0, 0 P1, 3 P2, 1 P3 priority issues. No critical rendering failure was found in the bounded checks.

## Cognitive load and emotional journey

Single focus, primary-content chunking, grouping, hierarchy and sequential decisions work. Secondary navigation exceeds four options but remains purposefully grouped: five desktop discovery links and separate utility icons; footer groups have six/seven links. Do not remove useful navigation solely to satisfy a mechanical count.

Working memory has two local demands: linking the cream hero to the full set and finding the portrait qualification after mobile results. Those, with navigation choice counts, give moderate checklist load. The collection's four choices are manageable.

The cream/silk hero is the main emotional peak. Caviar sustains product character; clinical evidence provides evaluation; Swiss landscape/provenance resets the pace; the editorial Ritual board restores intimacy and ends with reassurance. Portraits may appear younger than the intended audience: a future casting consideration, not a fact about the models or a reason to replace the owner's composition.

## Persona red flags

- **Jordan, first-time buyer:** The hero's bundle relationship requires inference; unfamiliar ingredient names require decoding.
- **Casey, distracted mobile visitor:** Can shop immediately, but may miss the later portrait qualification. Narrow ritual columns make skimming slower.
- **Riley, verifier:** Product names, methods and panels are good signals; the mobile placement of the illustrative credit makes evidence interpretation harder.
- **Primary mature-skin customer:** Benefits and age-specific study panels are relevant. Future portraits could reflect the audience more closely without changing the editorial identity.

## Minor observations

- At 320px, some collection names occupy three lines and benefits two/three lines. Dense but no clipping or broken layout was established.
- Newsletter placeholder truncates visually at 320px; the explicit accessible label remains available. Small visual polish, not a missing-label defect.
- Desktop utility icons have accessible names but less visual guidance than text labels.
- DESIGN.md still describes some retired layouts. Current owner decisions and live implementation govern this critique; no context document repaired during this review.
- Transient resize/reveal screenshots settled correctly and were not treated as persistent defects.

## Questions to consider

Should the next pass start with offer clarity, evidence interpretation or narrow-phone ritual reading? Should it address the three P2 refinements only, or include ingredient-copy simplification?

## Evidence limits

Independent fresh Chrome tabs. A inspected settled desktop and 390×844. B inspected desktop 1536×799, 390×844 and 320×740; narrow document client/scroll widths matched at 305/305. No broken images or console errors in inspected states. B's initial/end source hashes matched; no concurrent change detected. Newsletter outcomes verified only in source. No real submission, checkout, destination review, screen-reader speech, full keyboard walkthrough, photographic-pixel contrast audit, zoom test or physical-device/network testing. Viewport overrides restored; no source edits.
