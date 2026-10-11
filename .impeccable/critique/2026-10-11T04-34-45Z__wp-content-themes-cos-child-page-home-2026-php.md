---
target: homepage
total_score: 25
max_score: 36
na_heuristics: 7
p0_count: 0
p1_count: 1
target_identity: "file:C:\\Users\\giann\\Local Sites\\studio-caviar-of-switzerland-2026-05-28-10-36-43\\app\\public\\wp-content\\themes\\cos-child\\page-home-2026.php"
target_fingerprint: "sha256:92fe9456d1bf401ca9b4dc5ac2936526a36ad6969586e7800072f530fc27eb2e"
target_path: "C:\\Users\\giann\\Local Sites\\studio-caviar-of-switzerland-2026-05-28-10-36-43\\app\\public\\wp-content\\themes\\cos-child\\page-home-2026.php"
timestamp: 2026-10-11T04-34-45Z
slug: wp-content-themes-cos-child-page-home-2026-php
closed: true
---
Method: dual-agent (A: /root/critique_a_oct11 · B: /root/critique_b_oct11)

# Homepage critique — 11 October 2026

Target: `wp-content/themes/cos-child/page-home-2026.php`. Current homepage, Persuade mode, evaluated for an unfamiliar Swiss luxury skincare brand and primarily iPhone customers aged 45–55. PRODUCT.md and DESIGN.md were read alongside current source. Recent owner-requested collection/evidence/ritual layouts take precedence over superseded design documentation. No website code changed.

## Design specificity and overall impression

The homepage feels authored for Caviar of Switzerland. Chrome packaging, mirrored product staging, real caviar, manufacturing photography, named product studies and the compact four-formula range give familiar luxury editorial devices a clear purpose. The pearl palette and restrained serif typography remain appropriate. The new Ritual board contributes intimacy through a portrait and numbered packshot tiles.

The strongest opportunity is to make information as precise as photography: help the unfamiliar customer choose a formula, and distinguish measured change from reported satisfaction at a glance. Additional decorative styling is not the main need.

The detector returned `[]`, exit 0, with no findings or false positives. Its single markup scan preceded a concurrent change to the final component; it is not a clean-scan certification of that later variant. Browser/source evidence verified the current editorial board afterward. No detector overlay was injected because native evaluate is read-only; settled screenshots and DOM/AX/source supplied the fallback evidence.

## Design health

| # | Heuristic | Score | Key finding |
|---|---|---:|---|
| 1 | Visibility of system status | 3 | Newsletter has pending copy but outcomes lack announcement semantics |
| 2 | Match with real world | 3 | Familiar product types; skin-concern relevance arrives late |
| 3 | User control and freedom | 3 | Product links, exploratory anchor and conventional navigation |
| 4 | Consistency and standards | 3 | Coherent system; new editorial compositions are intentional |
| 5 | Error prevention | 3 | Native email/consent validation; truthful conditional comparison |
| 6 | Recognition rather than recall | 2 | Collection omits benefit cues needed to compare formulas |
| 7 | Flexibility and efficiency | n/a | Expert shortcuts are not material to this persuasive homepage |
| 8 | Aesthetic and minimalist design | 3 | Elegant composition; evidence annotation can imply more than its labels |
| 9 | Error recovery | 2 | Generic newsletter error allows retry but offers no alternative route |
| 10 | Help and documentation | 3 | Help/study/service routes exist; downstream content was not reviewed |
| **Total** | | **25/36 — Acceptable (69.4%)** | Nine applicable heuristics; narrowly below the 70% Good threshold |

This is a usability/communication review score, not an aesthetic rating or conversion measurement. Different heuristic sets make previous scores unsuitable for direct comparison.

## What works

- Hero imagery gives the actual objects presence. At 390×844, the headline, action and four bottles fit in the first screen; the primary action is 54px high.
- The collection is a curated four-column desktop / two-column mobile grid. Product names are 19px, size and price are explicit, and Shop now targets are 44px high. Current tested widths have no horizontal document overflow.
- The evidence names the product, method and panel. The final purchase offer presents €450, the €500 individual comparison and shipping/returns. The comparison is guarded by live price totals.

## Priority issues

### [P1] Newsletter outcomes need accessible feedback and recovery

**Location:** `wp-content/themes/cos-child/inc/forms.php`, `showError()` and success branch.

Failure appends a plain paragraph; success replaces form contents without an announcement region or deliberate focus destination. Users relying on a screen reader can miss the result; the focused submit control can disappear. “Something went wrong. Please try again.” offers little recovery beyond repetition.

**Fix:** Introduce a persistent status region, announce pending/success/error, preserve email and focus, restore retry after failure, and provide Contact as an alternative after failure. Source verified without a real submission or speech-output test. The announcement defect is a WCAG 4.1.3 concern; synthesis raises A's P2 classification to P1 under the audit severity convention. **Command:** `$impeccable harden`.

### [P2] The first product choice lacks a benefit cue

**Location:** `template-parts/home-2026/collection.php`, `.h26c__item`.

The cards show names, volumes and prices but no benefit/concern cue. An unfamiliar visitor considering a €145–€150 formula must infer relevance from product type or leave the homepage. Later evidence and suitability copy do not fully solve that first comparison.

**Fix:** Add one short, verified benefit under each product name, drawing from existing product data. Keep the chosen packshot grid and its restraint. Do not revive the retired carousel or invent stronger claims. **Command:** `$impeccable clarify`.

### [P2] Satisfaction is visually mapped to an anatomical result

**Location:** `template-parts/home-2026/trust.php`, reported callout and section introduction.

The 100% panellist-reported satisfaction result is pinned to the forehead using the annotation system used for measured eye/cheek changes. The smaller figure, hollow marker and Self-reported label mitigate confusion, but satisfaction does not measure a forehead change. “Measured over 28 days” also heads a mixed group containing self-assessment.

**Fix:** Preserve the owner-requested annotated portrait for measured outcomes. Remove the anatomical leader/marker for satisfaction and place it in a distinct secondary caption. Use an introduction such as “Evaluated over 28 days…” that encompasses both kinds of evidence. Do not suggest that the photographed model was a study participant. **Command:** `$impeccable clarify`.

### [P2] Supporting information is too small for comfortable mobile reading

**Location:** `assets/css/home-2026.css`, clinical panel/method rules and current `.h26r` lede/caption rules.

Clinical product/panel/method text measures 14px. In the current mobile Ritual board, intro copy is 15px and product/size captions 12px. These are purchase-relevant information, not decorative labels. The mature-skin audience should not have to work to read what gives the offer credibility or identifies its contents.

**Fix:** Give reading copy a 16px target and improve line spacing/measure. Keep short labels distinct. Check wrapping in both 390px and 320px two-column tiles; if needed let captions sit below imagery rather than reducing their size to fit. Preserve the large editorial titles and photographic hierarchy. Small text alone is not a proven WCAG failure. **Commands:** `$impeccable typeset`, then `$impeccable adapt` for tile fit.

Priority counts: 0 P0, 1 P1, 3 P2. No purchase blocker was established by the tested homepage interactions.

## Cognitive load and emotional journey

Single focus, chunking, grouping, hierarchy, sequential decisions and progressive disclosure work well. Two checklist failures/weaknesses remain: working memory when formula relevance is elsewhere, and choice counts above four in navigation/footer. Five desktop discovery routes and grouped footer lists of six/seven links are modest loads, not justification to delete useful routes. The product grid itself has four options.

The hero creates desire, the collection offers a purchasing pause, caviar supplies character, clinical evidence prompts evaluation, and filling photography provides tangible Swiss reassurance. The refreshed Ritual board restores intimacy before the offer. Its current mobile section is approximately 1708px tall; much of that is intentional portrait plus 2×2 tiles. Test pacing, but do not label height alone a defect. The clinical section is the main attention valley because its qualifications demand more reading effort than its large figures suggest.

## Persona red flags

- **Jordan, unfamiliar buyer:** Recognises product types and price, but lacks a quick reason to choose one formula for her concern.
- **Sam, accessibility-dependent reader:** Faces small purchase/evidence details and newsletter results without explicit announcement semantics.
- **Primary customer evaluating premium skincare for firmness/dryness:** Relevant concerns arrive late, while the forehead satisfaction annotation can blur measured and reported evidence.
- **Casey, distracted mobile visitor:** Current grid exposes all four products without swiping, but the long final board and small captions require careful reading before reaching its closing offer.

## Minor observations

- Collection copy repeats “four formulas” in the lede, italic line and subheading; remove one repetition to sharpen the edit.
- Review whether the portrait casting reflects the audience's experience; perceived age is a design judgment, not a fact about the model.
- Earlier blank/faded screenshots settled correctly. No persistent loading failure was established.
- The current Ritual board's numbering and Cleanse/Treat/Revive/Restore verbs are useful; preserve them.

## Questions to consider

Should the next pass lead with formula choice or evidence precision? Should the refinement cover the four priorities or include repetition and mobile final pacing as well?

## Evidence limits

Independent fresh Chrome tabs; desktop, 390×844 and B's 320×740 checks. B measured matching document client/scroll widths at all three sizes and confirmed the collection anchor clears the fixed header. A discarded mobile closing observations from the removed version, verified refreshed final source/desktop, and B verified the current mobile board.

Concurrent edits changed the final section during this run. This report concerns the refreshed editorial board where stated, not the removed warm model-panel variant. The detector ran once before the change and was not rerun. No physical device/Safari test, transaction, real newsletter request, full keyboard or screen-reader audit, text enlargement or performance measurement. No UI edits.
