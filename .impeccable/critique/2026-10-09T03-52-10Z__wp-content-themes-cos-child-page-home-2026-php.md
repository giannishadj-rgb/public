---
target: homepage
total_score: 23
max_score: 32
na_heuristics: 7,10
p0_count: 0
p1_count: 1
target_identity: "file:C:\\Users\\giann\\Local Sites\\studio-caviar-of-switzerland-2026-05-28-10-36-43\\app\\public\\wp-content\\themes\\cos-child\\page-home-2026.php"
target_fingerprint: "sha256:6ddec197bf609b7035eb1f12667b7bf2170fa6c4d03e1ea3f4e3d651429c99c5"
target_path: "C:\\Users\\giann\\Local Sites\\studio-caviar-of-switzerland-2026-05-28-10-36-43\\app\\public\\wp-content\\themes\\cos-child\\page-home-2026.php"
timestamp: 2026-10-09T03-52-10Z
slug: wp-content-themes-cos-child-page-home-2026-php
---
Method: dual-agent (A: /root/critique_a_oct9 · B: /root/critique_b_oct9)

# Homepage critique — 9 October 2026

Target: `wp-content/themes/cos-child/page-home-2026.php`, current local homepage. Persuade mode. Reviewed against current PRODUCT.md and DESIGN.md, for an unfamiliar Swiss luxury skincare brand and its primarily iPhone audience aged 45–55. No website code changed.

## Design specificity and overall impression

The homepage feels authored for Caviar of Switzerland. Actual silver packaging, mirrored glass, the caviar macro, precise product-specific evidence and the repeated 01–04 ritual sequence give it a coherent identity. The serif/editorial structure is familiar luxury grammar, but the small four-formula range gives that structure purpose.

The homepage already communicates elegance. Its biggest opportunity is to make mobile browsing as carefully composed as the opening image. Preserve the visual world; improve the pacing and choice structure.

The independent detector returned zero findings (`[]`, exit 0) across the homepage template, component directory and helper file. It supplies no PHP coverage/file count and does not assess persuasion, touch usability or newsletter announcements. No false positives were reported. Native browser evaluation is read-only, so no visual detector overlay was injected; screenshots, DOM/AX and source were used instead.

## Design health

| # | Heuristic | Score | Key finding |
|---|---|---:|---|
| 1 | Visibility of system status | 3 | Menu/rail state works; newsletter outcomes lack announcement semantics |
| 2 | Match system / real world | 3 | Familiar ritual structure; some application/science language needs translation |
| 3 | User control and freedom | 3 | Primary and exploratory paths; menu Escape returns focus |
| 4 | Consistency and standards | 4 | Cohesive typography, numbering and action hierarchy |
| 5 | Error prevention | 3 | Native email/consent checks; truthful conditional bundle comparison |
| 6 | Recognition rather than recall | 3 | Named products and prices; mobile comparison requires remembering cards |
| 7 | Flexibility and efficiency | n/a | Expert accelerators are not material to this persuasive homepage |
| 8 | Aesthetic and minimalist design | 3 | Uncluttered; mobile collection delays decision information |
| 9 | Error recovery | 1 | Generic newsletter failure with no alternative route |
| 10 | Help and documentation | n/a | Deeper help was outside this persuasive surface review |
| **Total** | | **23/32 — Good** | Eight applicable heuristics; bounded expert review |

## What works

- On the inspected 390×844 viewport, headline, both actions and all four bottles fit within the opening screen. Body copy is 16px and the primary CTA is 54px high. This establishes desire and a clear next step.
- Clinical evidence names the measured product, method and panel. Self-reported cream satisfaction is secondary. These distinctions support trust without invented testimonials or ritual-wide clinical claims.
- The closing ritual identifies all four formulas and sizes beside the €450 price, €500 individual comparison, shipping and returns. The visitor can understand what the purchase includes.

## Priority issues

### [P1] Newsletter outcomes need accessible feedback and useful recovery

**Location:** `wp-content/themes/cos-child/inc/forms.php`, `showError()` and success branch.

Failure appends a plain paragraph; success replaces the form contents without an announced status or deliberate focus destination. The generic “Something went wrong. Please try again.” offers only repetition. Screen-reader users can miss the result, and keyboard users can lose the focused control. The announcement gap is a code-level WCAG 4.1.3 concern; it was not verified with screen-reader speech or a real submission.

**Fix:** Add a persistent status region, preserve input and focus through state changes, allow retry after failure, and offer Contact as an alternative when retry fails. Do not diagnose an invalid email from a generic network error. **Command:** `$impeccable harden`.

### [P2] Mobile collection takes too long to reveal decision information

**Location:** `template-parts/home-2026/collection.php` and mobile collection/packshot CSS.

At 390×844, the collection measured about 992px tall. The initial collection view presents step, use cue and a large photograph; price, “View formula” and pagination arrive below the viewport. The visitor must scroll vertically to discover controls for horizontal exploration. This is a pacing issue, not a broken carousel: the buttons are labelled and 44×44px.

**Fix:** Reduce the mobile photograph canvas and reserved gaps, or bring product identity and benefit nearer the step. Aim to show name, benefit, price and browsing controls together in a comfortable viewport while preserving accurate packaging and generous spacing. **Command:** `$impeccable adapt`.

### [P2] Mobile navigation gives eight destinations equal weight

**Location:** shared mobile navigation in `wp-content/themes/cos-child/inc/navigation.php`.

Home, Shop, The Ritual, Swiss Science, Swiss Heritage, Help, Account and Contact form one undifferentiated list. Brand discovery and service tasks compete at the same visual level.

**Fix:** Group the discovery routes separately from a quieter Help/Account/Contact group. The linked wordmark can provide Home if the separate Home entry is removed. Preserve familiar routes and keyboard access. **Command:** `$impeccable distill`.

Severity synthesis: 0 P0, 1 P1, 2 P2 priority findings. Assessment A classified newsletter feedback P2; synthesis elevates its verified announcement defect to P1 based on the audit severity definition. No purchase blocker was observed in the tested homepage interactions.

## Cognitive load and emotional journey

Three of eight checklist items fail or partly fail: chunking and minimal choices in the mobile menu, plus working memory when comparing cards. Single focus, grouping through the page, hierarchy, sequential decisions and deeper-content disclosure work well. Overall load is moderate and localized. The mobile menu presents eight choices; desktop main navigation presents five, with utilities grouped separately. The collection itself has four choices.

The opening photograph is the emotional peak. The tall mobile collection creates an attention valley; texture restores intimacy, caviar explains the ingredient, and clinical evidence/Zug manufacturing build belief. The closing tray and explicit purchase information create a confident end. Preserve this sequence and improve its mobile rhythm.

## Persona red flags

- **Jordan, first-time buyer:** Eight equally weighted navigation choices obscure the simplest discovery route; sequential cards make comparison harder.
- **Casey, distracted mobile visitor:** Collection controls sit below the initial view, making it easier to scroll past the range without exploring it.
- **Primary product persona, mature-skin buyer evaluating €450:** Relevant benefits and age-matched evidence help. The suitability summary is late; using its existing verified wording earlier is an optional refinement.
- **Sam, screen-reader/keyboard user:** Dynamic newsletter outcomes lack announcements. Menu Escape/focus return worked in the inspected interaction.

## Minor observations

- Clinical details are 14px on mobile. For the stated audience, test 16px supporting text where practical; the current measurement alone is not a WCAG failure.
- The menu button remains labelled “Open navigation menu” while expanded; update it to “Close navigation menu.”
- Review “orbital bone” for a plainer, still accurate application cue.
- Product-card links combine instructions, image alternative text, benefits and prices into long accessible names; assess verbosity in a screen-reader link list before shortening.

## Questions to consider

Can each mobile formula communicate identity, benefit and price in one comfortable view? Can navigation distinguish discovery from service without adding another layer? Would the existing suitability line make the collection more immediately relevant?

## Evidence limits

Two independent fresh Chrome tabs. Desktop and 390×844 mobile inspected; mobile menu Escape and rail selection exercised by A, exploratory anchor and rendered target bounds checked by B. No document overflow in B's tested views. Reduced-motion and newsletter paths also inspected in source. No real forms submitted, checkout tested, physical touch/Safari exercised, full keyboard/screen-reader audit performed, 200% text enlargement checked or performance measured. Settled views were used, excluding transient resize/reveal frames.
