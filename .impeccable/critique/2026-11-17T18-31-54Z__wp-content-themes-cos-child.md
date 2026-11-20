---
target: the whole site
total_score: 27
max_score: 40
na_heuristics: 
p0_count: 0
p1_count: 3
target_identity: "file:C:\\Users\\giann\\Local Sites\\studio-caviar-of-switzerland-2026-05-28-10-36-43\\app\\public\\wp-content\\themes\\cos-child"
timestamp: 2026-11-17T18-31-54Z
slug: wp-content-themes-cos-child
---
# Whole-site critique, 17 Nov 2026 (dual-agent): 27/40 Acceptable

Heuristics: 1 Status 3 · 2 Real world 3 · 3 Control 3 · 4 Consistency 2 · 5 Error prevention 2 · 6 Recognition 3 · 7 Flexibility 2 · 8 Minimalist 3 · 9 Error recovery 3 · 10 Help 3.

Specificity: theme-built surfaces (home, shop, PDPs, set plate) are authored. DB-authored deep pages (heritage, journal, shipping, clinical hero) fall to stock-beauty imagery and grandiose copy.

Priority issues:
- P1 Imagery vs audience/evidence: 20-something faces site-wide; /science/clinical-studies/ puts a young face (with a measurement target) beside "32 women aged 45–60". Shop hero video (lips), shipping hero, journal hero. Fix: product/texture/hand imagery or panel-age models; no targets on faces.
- P1 Claims/voice: clinical-studies.php:18 "Every statistic … independent dermatologist evaluation" (false for the self-reported 100%); hx-science.php:134 + seo.php:137 "UV resilience"; "concierge" (page-account.php:574, seo.php:101-111, commerce.php:272); journal "Push & Press", "measurably improves absorption"; heritage "four centuries of watchmaking", "constituted by it".
- P1 Product truth: /heritage/ card shows a Neck & Décolleté cream + jade roller (not in range).
- P2 Complete Ritual PDP ~10,000px desktop / 12,200px phone, four formulas shown ~6 times, 6-item bar, callouts 01/03/04/02 left→right, no "€500 separately" line at the buy block.
- P2 Purchase moment: canton preselected Luzern; stock Woo errors duplicated banner+inline; title-case "Encrypted Checkout · Pay on Delivery · 30-Day Returns"; full nav/newsletter footer on checkout; no set upgrade in bag.

Minor: inconsistent free-shipping wording (Free delivery / Free worldwide shipping); 10px cart badge; /my-account/ 404; shop bar labels near-identical + truncated on phone; serum "Step 2 between Micellar and 24h cream" skips eye cream; ritual hero cream reads pink-lilac (cream is ivory); journal ground #fff; "Varies by destination" shipping; science h3 rhythm (24 above / 72 below); collection.css:494-517 padding/max-width transitions; style.css:262 legacy side-tab; legacy #2F6FED/#9DBBFF/#E03A40 colours.

Detector: 316 CLI (mostly design-system advisories); browser false positives: .hx-link low-contrast (gradient underline read as bg), italic-serif-display (upright), Inter overuse, 14px/1.25 tight-leading (owner-settled).
