# Authenticity and commercial review — 18 October 2026

Read-only review of the seven homepage sections, `inc/home-2026.php`, `CLAIMS-REGISTER.md`, `PRODUCT.md`, relevant product INCI and image-origin records. No implementation edits. This is a source/code review, not a rendered visual certification.

## Priority findings

- **P1 — conflicting species.** `caviar.php` names Siberian sturgeon / *Acipenser baerii*, while all four INCI strings in `inc/product-data.php` name *Acipenser Gueldenstaedtii* roe extract. PRODUCT.md acknowledges this unresolved conflict. Remove the scientific species line from the homepage pending manufacturer reconciliation; retain the general caviar ingredient story. Do not silently change product INCI.
- **P1 — substantiation is circular.** The +47%, −38%, 100%, independent-CRO, ISO 22716 and manufacturing claims are supported here by existing site pages or earlier project assertions, not inspected study reports, certificates or manufacturer records. CLAIMS-REGISTER “Verified” means internally consistent/disclosed, not independently substantiated. Preserve this distinction in the handoff; obtain primary records or omit the precise study/certification claims from the homepage. No evidence of fabrication was established, but factual verification is incomplete.
- **P1 — mechanism illustration implies deeper action.** `trust.php` says each action has “its own place in the skin”, labels “Where it acts”, and places Rejuvenate at “Structure”. Even with cosmetic “looks firmer” wording, the visual/text combination implies demonstrated depth-specific action beyond the supplied evidence. Replace with ingredient roles without anatomical action claims.
- **P2 — authenticity source chain incomplete.** Origin records trace the shipped hero to `hero-jar_master.png` and packs to `*-flat-2026-10-13.png`; they do not prove those masters match original Dropbox packaging. The Dropbox URL failed in the web reader. Compare the existing masters to original supplied files before declaring complete product fidelity.

## What is sound

- Study numbers remain associated with individual products, time, method and panel; self-reported satisfaction is differentiated. No combined ritual study is claimed.
- Product URLs and prices are derived from WooCommerce. Set-vs-individual price appears conditionally only while the numerical comparison is true. Individual discovery and ritual purchase routes are clear in code.
- Hero origin record reports exact protected-region pixel identity before compression. Collection record honestly discloses proportional resizing, backdrop normalisation and RGB changes; do not call those literal untouched pixels. Grouped set derives from the same packs. No evidence here of generated replacement packaging.
- Provenance still-life has no product and its alt text identifies illustration; no documentary laboratory image claim.
- Free worldwide shipping has an existing operational configuration record in OWNER-ACTIONS.md; 30-day returns are existing policy assertions. Verify link destinations and checkout in browser QA.

## Commercial limits

First CTA sells the whole ritual before showing price or contents. This is a potential friction point, not a proven defect: retain strong individual-product access immediately below. Avoid claims of 9/10 credibility until the source gaps above are resolved. No invented reviews, endorsements or scarcity observed.

## Final disposition and primary source check

Implementation owner removed species from the homepage and removed the depth-specific anatomy diagram. Existing published study findings are retained with attribution; supporting reports were not supplied/inspected, so the gap is documented rather than represented as a new validation requirement. Owner-recorded Zug/20+ year facts are preserved as existing assertions.

Shell HTTP successfully accessed Dropbox (200). Examined relevant product, ingredient and brand folder listings. Downloaded/read `sources/ingredients-list-main.docx`: caviar is listed in all four formulas; Syn-Coll, hyaluronic acid, SOD and Resveratrol appear in eye/serum/cream. Quantitative efficacy remains site-sourced. No species in that primary document.

Downloaded supplied 2020 jar JPG and confirmed exact SHA256 match to local `../design-sources/home-2026/packshots-2026-10-12/cream.jpg`. Visual comparison to the shipping flat master confirms corresponding visible package identity/wording/50ml size; angle and lighting differ, so no pixel-identity claim between original and flat master. See SOURCES.md for exact links/hash and remaining source-chain limit. No large archive downloaded.
