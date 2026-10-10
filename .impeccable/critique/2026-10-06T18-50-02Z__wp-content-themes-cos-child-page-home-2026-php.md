---
target: homepage (page-home-2026.php)
total_score: 20
max_score: 28
na_heuristics: 7,9,10
p0_count: 0
p1_count: 1
target_identity: "file:C:\\Users\\giann\\Local Sites\\studio-caviar-of-switzerland-2026-05-28-10-36-43\\app\\public\\wp-content\\themes\\cos-child\\page-home-2026.php"
target_fingerprint: "sha256:6ddec197bf609b7035eb1f12667b7bf2170fa6c4d03e1ea3f4e3d651429c99c5"
target_path: "C:\\Users\\giann\\Local Sites\\studio-caviar-of-switzerland-2026-05-28-10-36-43\\app\\public\\wp-content\\themes\\cos-child\\page-home-2026.php"
timestamp: 2026-10-06T18-50-02Z
slug: wp-content-themes-cos-child-page-home-2026-php
closed: true
---
Method: dual-agent (A: a34576bee9a90fe9f · B: a438dd251397078a0)

Score 20/28 (Good, 71%). n/a: 7, 9, 10 (kept same as prior runs for comparability). H1 3 · H2 3 (SYN-COLL, "panellist" unexplained) · H3 3 · H4 3 (3 CTA labels for one destination; outline hero vs filled final) · H5 3 · H6 2 (final step list = verbs only; contents of €450 not named at purchase) · H8 3 (6 text tiers per stat card).

Specificity: content authored (evidence attribution, caviar 3 beats, real packshots, ritual spine); visual grammar is the stock quiet-luxury kit (owner choice). Generic words: hero subline, texture line.

Detector: source clean; rendered 3 (clipped-overflow html/body — no visible clipping; Inter taste). Live 9 @1440 / 11 @390: low-contrast x4 FP (underline gradient; real 14.9–19.1:1), text-occlusion = detector overlay FP, clipped main FP; real-but-stylistic: kicker-above-heading x4 (owner choice), Inter 78%. No dark-glow this run. CLS 0 at 320/390/768/1440, 0 console errors, no overflow, alt/dims complete, min contrast 5.28:1. Anchor fixed (eyebrow 40px below nav @390). Buyline present. Hero bottles fully in 390x844 view; caps+labels in 320x640. Final steps 2x2. Footer consent checkbox label 42px tall (2px short).

Priority issues:
- [P1] Claim precision: trust lede "Instrument-measured over 28 days by an independent CRO" umbrellas the self-reported 100% result; provenance "tested in Zug" can read as clinical testing in Zug. Fix: "Measured over 28 days by…"; "Developed, formulated and produced in Zug" (or "batch-tested"). clarify.
- [P2] Complete Ritual never names its contents at purchase — steps show "01 Cleanse…" only. Add product name + size per step from cos_h26_products(). clarify.
- [P2] Card price is the weakest text on the card (14px #5E5E5E vs benefit 16px #3A3A3A). Raise to 16px darker. typeset.
- [P2] Hero primary CTA is a hairline outline; three different labels for one destination. Fill the primary; unify verbs. bolder/polish.
- [P3] Texture line generic for its footprint; product link 14px grey caption. Use published sensory wording; clearer link. clarify/typeset.

Personas: Jordan (bundle CTA before knowing what it is; SYN-COLL unexplained), Casey (706px cards; final CTA ~8 screens down; buyline is a text link only), Riley (no-JS reveal fallback unverified; nav sentence-case rule loses → spaced caps), 52 on iPhone (all fine print 14px grey; "who is the CRO?"; who is the house beyond Zug).

Minor: footer consent label 42px; Privacy Policy inline 17px; uneven stat column ends; sparse desktop texture right column; h1 <br> textContent "ofCaviar"; thin dark band along top of mobile hero image; buyline textContent "·,".

Questions: hero primary → collection instead of €450 bundle? fill the frames vs keep restraint? state honestly that each formula was studied separately and the cleanser has no study?
