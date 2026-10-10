---
target: "Homepage: luxury, prestige and trust for an unfamiliar Swiss skincare brand"
total_score: 25
max_score: 32
na_heuristics: 7,9
p0_count: 0
p1_count: 3
target_identity: "file:C:\\Users\\giann\\Local Sites\\studio-caviar-of-switzerland-2026-05-28-10-36-43\\app\\public\\wp-content\\themes\\cos-child\\page-home-2026.php"
target_fingerprint: "sha256:6948cad1e8c9fc6cf08d8dc4ff37661686ee570de5cf41d8b44a1eda11d0fd5a"
target_path: "C:\\Users\\giann\\Local Sites\\studio-caviar-of-switzerland-2026-05-28-10-36-43\\app\\public\\wp-content\\themes\\cos-child\\page-home-2026.php"
timestamp: 2026-10-04T16-49-02Z
slug: wp-content-themes-cos-child-page-home-2026-php
---
Method: dual-agent (A: /root/design_review; B: /root/technical_evidence)

The homepage already looks luxurious and elegant. Its main weakness is that it presents the prices before it earns a new customer's confidence. Prestige is visually convincing; trust and value need stronger support.

Design specificity: coherent and product-specific. The silver packaging, caviar disc, restrained serif typography and Zug manufacturing story belong together. The verbal identity is less distinctive: Swiss precision and natural renewal do little to explain the premium.

Design health: 25/32, Good. Scores are heuristic judgments, not conversion measurements.

| Heuristic | Score /4 | Finding |
|---|---:|---|
| System status | 3 | Clear rail pagination; limited current-page emphasis |
| Real-world language | 3 | Benefits and prices clear; technical terms add friction |
| User control | 3 | Normal navigation and scrolling |
| Consistency | 3 | Coherent visual language; Discover labels blur destinations |
| Error prevention | 3 | Prices/sizes clear; suitability and terms distant |
| Recognition | 2 | Mobile comparison requires recall |
| Efficiency | n/a | Expert accelerators not relevant to this marketing surface |
| Aesthetic restraint | 3 | Elegant; repeated four-formula premise |
| Error recovery | n/a | Form and checkout recovery not exercised |
| Help/guidance | 2 | Help exists; starting-product guidance absent |

Technical health: 17/20, Good. Accessibility 3/4; performance 3/4; responsive design 3/4; theming 4/4; implementation integrity 4/4. No verified technical P0/P1 blocker in the tested homepage flow. Performance score reflects implementation review, not measured Core Web Vitals.

What works: the campaign hero is a strong luxury opening; collection cards show benefits, sizes and prices without promotional clutter; clinical figures name products, methods and panels; Zug provides concrete provenance. Preserve black/silver, generous spacing and the pale clinical section.

Priority issues: 3 P1 persuasion issues, 4 P2 issues, 0 P0.

1. [P1] Proof arrives after prices. page-home-2026.php: hero > collection > EUR450 ritual > gestures > ingredient > clinical > provenance > assurances. An unfamiliar-brand visitor meets the investment before the reasons to trust it. Retain the hero; place a compact verified proof point near the first product decision and move clinical/provenance evidence before detailed usage. Suggested command: impeccable shape.
2. [P1] The premium is insufficiently explained. hero.php/caviar.php/provenance.php describe Swiss precision, amino acids/lipids and GMP production. Explain a verified formulation distinction, its relevant customer benefit and its product-specific evidence. Product source material includes the serum's encapsulation/peptide pairing, subject to substantiation. Do not invent exclusivity, potency, sourcing or endorsements. Suggested command: impeccable clarify.
3. [P1] The EUR450 ritual lacks nearby reassurance and selection guidance. final.php repeats the four-formula premise; shipping/returns appear in assurances.php near the footer. Add concise, verified suitability guidance and service reassurance beside the CTA. Individual prices currently total EUR500, but any bundle comparison must remain accurate as prices change. Give first-time visitors a reason to choose one product or the whole ritual. Suggested command: impeccable onboard.
4. [P2] Satisfaction visually competes with measured results. trust.php and home-2026-nocturne.css give 100% self-reported satisfaction similar prominence to +47% firmness and -38% crow's-feet depth. Labels correctly distinguish evidence types; make that distinction easier to perceive. Identify/link the independent testing organisation if substantiated. Suggested command: impeccable polish.
5. [P2] Mobile comparison requires memory. collection.php and home-2026.css show one card at a time, beginning with cleanser. Keep the editorial rail but add a compact overview or verified skin-goal guidance. Suggested command: impeccable adapt.
6. [P2] Pagination ignores reduced-motion preference. home-2026.js:39 unconditionally uses smooth scroll. Use auto when reduced motion is requested. Suggested command: impeccable animate.
7. [P2] Expanded mobile footer links have small tap areas. inc/navigation.php:109 and home-2026.css:381 render roughly 31px-high anchors; Shop is about 34px wide. Increase hit areas toward 44px. This is a usability recommendation, not an established WCAG AA violation. Suggested command: impeccable adapt.

Cognitive load and emotional journey: visual noise is low and four products are manageable. Five desktop navigation destinations slightly exceed the skill's four-choice heuristic but are not a major problem. The meaningful burden is connecting later evidence to earlier products. Aspiration peaks in the hero, confidence dips at the price, proof restores it late, and the closing invitation becomes quieter than the newsletter.

Persona red flags: first-time buyers lack a confident starting recommendation; sceptical buyers see brand-authored claims without customer or third-party voices; distracted mobile buyers must swipe and recall prices before comparing. Genuine reviews and press are explicitly pending in admin. Add them only when authentic, attributable material exists.

Detector: scoped PHP scan ran once and returned zero primary/advisory findings. It does not execute WordPress or prove linked stylesheet/accessibility quality. No false positives returned. Native Chrome screenshots and measurements supplement the scan; mutable overlay injection is unavailable.

Recommended technical sequence: impeccable animate for reduced-motion pagination; impeccable adapt for footer hit areas and mobile comparison; impeccable polish after the selected fixes. Re-run impeccable audit after fixes. Commands can be run individually or together.

Questions to consider: which should lead the next pass, trust/value messaging, mobile product selection, or both? Should the first-time purchase path recommend one starting product, emphasise the complete ritual, or guide visitors to either by skin goal?
