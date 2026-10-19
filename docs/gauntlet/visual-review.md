# Independent visual review — 2026-10-18

Read-only, non-blind review of `current-desktop.png` (1440px), `current-mobile.png` (390px), fresh first-viewport captures `review-{desktop,mobile}-first.png`, and the three saved actual reference screenshots (La Prairie, Clé de Peau, Augustinus Bader). Full-page sticky-header placement is a capture artifact; fresh captures confirm a correct header. No competitor live interaction or source authentication was performed.

## Findings, ranked

1. **P1 — Mobile collection navigation is too implicit.** The peek of product 2 and `1 / 4` do signal a carousel, but the first product occupies roughly 650px and there is no visible directional control. Three products and their benefits remain concealed unless the visitor discovers horizontal scrolling. Add 44px previous/next buttons beside the count, preserve the native swipe and next-card peek, and provide direct collection access near the hero. Do not shrink product photography into small tiles.
2. **P2 — The science diagram contributes little visual or explanatory value.** The desktop wave band is very faint; mobile reduces it to a narrow sliver beside text. It risks implying anatomical precision beyond what the visible copy establishes. Remove depth/layer imagery. Preserve rhythm with three large serif verbs in columns on desktop and numbered rows on mobile, separated with fine rules. Let the measured results provide the evidence and the authentic caviar macro provide the sensory contrast.
3. **P2 — Small action labels and repetitive ritual framing weaken shopping clarity.** Thin, widely tracked uppercase action labels are markedly less readable than the body text, particularly for the intended 40+ audience. Four-step/complete-ritual language recurs in hero, collection, daily ritual and closing. Raise action labels to about 14–15px while retaining restraint; a quiet secondary collection link near the hero adds a useful alternative to the €450 complete ritual. Avoid adding unsupported benefit copy to solve this.

## Non-blind benchmark comparison

Caviar's warm silver/ivory hero, generous authentic product scale and consistent editorial typography compete credibly with the saved luxury references. It is calmer than the Clé de Peau holiday campaign and has stronger heading treatment than the Bader reference. La Prairie's visibly crisp packaging and immediately structured navigation still convey greater commercial certainty. Bader's human subject communicates an outcome and emotional relevance that Caviar's entirely object-led story does not. These are differences in observed strengths, not proof of overall superiority. Caviar's greatest gap is discovery/interaction confidence and repetitive middle-page rhythm, not lack of decoration. No wholesale hero redesign is warranted.

## Scores (before the focused fixes)

| Dimension | /10 | Evidence/limit |
|---|---:|---|
| Luxury perception | 8.8 | Hero and palette are sophisticated; middle loses momentum. |
| Visual originality | 8.1 | Coherent own identity; satin/product and caviar tropes remain familiar. |
| Art direction | 8.7 | Excellent hero scale and mobile crop; weak diagram. |
| Typography | 8.4 | Elegant hierarchy; tracked micro-actions need readability. |
| Product presentation | 8.7 | Large faithful-looking product assets; three concealed on mobile. |
| Brand differentiation | 8.0 | Caviar and Zug clear; repeated four-step story has limited specificity. |
| Credibility | 8.0 | Study methods shown; factual/source validation belongs to authenticity review. |
| UX and conversion | 7.7 | Clear PDP routes, but carousel and full-ritual-first routing limit choice. |
| Mobile design | 8.2 | Intentionally art directed; lengthy science block and implicit carousel. |
| Interaction quality | 7.5* | Visible affordances only; keyboard/cart/reduced-motion not tested here. |
| Technical quality | — | Screenshot review cannot establish this; require engineer's measured tests. |
| Overall flagship quality | 8.4 | Strong visual foundation; not defensibly 9+ across dimensions yet. |

Product shape/labels appear coherent, but this visual review cannot certify fidelity without original asset comparison. No technical passing grade is inferred from screenshots. Re-review the proposed controls, readable actions and revised science block at 390px and 1440px after implementation.

## Final focused review — after fixes, 2026-10-18

Reviewed actual revised hero, collection, evidence and closing screenshots at 390px and 1440px. Also exercised the live mobile carousel with Playwright. This remains a **non-blind** comparison against the same saved competitor references. Sticky-header overlap in element screenshots is a capture artifact; it is not scored as a visual defect.

**Observed improvements:** The sentence-case shopping actions are decisively easier to read while retaining restraint. The secondary hero link makes individual formulas accessible without first choosing the whole ritual. The mobile carousel now has clear 44×44px directional buttons; three next clicks reached formula 4 and disabled Next, then Previous returned to formula 3. The next-card peek survives. Removing the anatomical graphic improves credibility and creates a cleaner editorial rhythm; the large verbs and fine rules are enough structure. The evidence block remains legible and product-linked. The closing group composition and clear bundle pricing are strong on both widths.

**Remaining priorities:** No new P0 or P1 visual defect found in reviewed regions. P2: live-region text currently serializes as `Formula 4 / of 4`; hide the decorative slash from accessibility APIs or announce a clean `Formula 4 of 4`. P2: product story remains entirely object/material-led, and the hero communicates origin/ritual more readily than a distinctive skin outcome. This is a longer-term brand-content gap requiring verified art/content, not justification for another speculative layout rewrite. P3: the long mobile evidence passage remains more rational than emotional; retain its credibility rather than compressing research qualifiers merely to shorten the page.

| Dimension | Final /10 |
|---|---:|
| Luxury perception | 9.0 |
| Visual originality | 8.2 |
| Art direction | 8.9 |
| Typography | 8.9 |
| Product presentation | 9.0 |
| Brand differentiation | 8.1 |
| Credibility | 8.3* |
| UX and conversion | 8.7 |
| Mobile design | 8.8 |
| Interaction quality | 8.6* |
| Technical quality | Not scored by this reviewer |
| Overall flagship quality | 8.8 |

*Credibility is conditional on the authenticity specialist's source verification; interaction score reflects visible affordances and the exercised carousel, not a full navigation/cart/accessibility audit. Product fidelity still requires source-asset comparison. Technical quality must come from measured engineering QA.

**Benchmark conclusion:** Warm ivory/silver art direction, full-scale products and editorial typography now sit credibly beside the saved luxury references. Caviar is less visually busy than the Clé de Peau campaign, and the product-led clarity is strong. La Prairie still has a more immediately recognizable product/brand world; Bader's human photographic subject conveys emotional relevance Caviar does not yet supply. These focused fixes improve usability without sacrificing desirability. The result is sophisticated and commercially clearer; the evidence does not justify claiming blanket superiority or 9+ across every dimension.

### Accessibility correction and final distinction recommendation

The earlier live-region concern was a review error: `allTextContents()` includes deliberately hidden decorative text. Playwright `ariaSnapshot()` verifies the actual accessible status is **`Formula 1 of 4`**. No count change is required; that P2 finding is withdrawn.

One further grounded refinement: replace provenance's generic `Swiss precision. Considered care.` heading with **`Created and filled / in Zug, Switzerland.`**, retaining the second-line italic and existing composition. Shorten its supporting copy to the verified twenty-plus-years expertise. This elevates the strongest owner-confirmed brand fact from body copy into visual hierarchy without new assets or claims. It is a meaningful specificity improvement, not a promise of a 9+ score.

The remaining originality gap is chiefly exclusive brand material: the provenance plate is illustrative; satin, caviar macro and silver packaging are familiar luxury vocabulary; source study reports and facility documentary photographs remain unavailable. New composition alone cannot honestly resolve those evidence/content limitations. Do not replace authentic imagery with speculative generated product scenes merely to chase numerical ratings.

### Final disposition — provenance refinement

Inspected `final-390-heritage.png` and `final-1440-heritage.png`. The new source-specific headline wraps gracefully on mobile and desktop, with clear hierarchy and no visible text overflow. Removing the redundant location kicker simplifies the composition. This is a concrete improvement in brand specificity; the unchanged material-study photograph remains illustrative campaign art. Brand differentiation moves from **8.1 to 8.4**; **overall remains 8.8/10** because this targeted copy/hierarchy refinement does not close the exclusive-photography and independent-substantiation gaps. No additional material visual defect found in this final section. No further speculative composition changes recommended. Final technical acceptance remains with the engineering QA record.
