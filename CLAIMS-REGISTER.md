# Claims Register — Caviar of Switzerland

Generated during the Phase 4 remediation pass. Method: booted WordPress via
a PHP/`$wpdb` script and scanned every published Page's `post_content` for
the flagged claim categories, cross-checked against `inc/product-data.php`
and the WooCommerce templates (`single-product.php`) that render PDP copy
which doesn't live in `post_content`. No new evidence was invented — every
"Verified" row below cites the page that already discloses the
n-size/age/duration/method; every "Softened/Fixed" row was edited to stop
short of a claim this project has no evidence for; every "Needs evidence"
row is left as a flagged, open item, not fabricated or silently removed
where removal would itself be an undocumented decision.

## Status legend

- **Verified** — methodology (n-size, age range, duration, measurement type) is disclosed and consistent everywhere the claim appears.
- **Fixed this pass** — was inconsistent or unsubstantiated; copy has been corrected/softened below.
- **Needs evidence** — still stated without a disclosed method; flagged for an owner/regulatory decision, not altered (removing it would itself be an editorial call this pass didn't have authority to make where the underlying science claim, as opposed to the specific number, appears defensible).

## 1. −38% fine-line depth reduction (Revitalizing Eye Cream) — methodology mismatch

| Field | Detail |
|---|---|
| Claim | "−38% Fine line depth reduction at 28 days" |
| Product | Revitalizing Eye Cream (COS-003), also cited in the Complete Ritual bundle |
| Category | Instrumental measurement |
| Evidence source | Home (page 50) and Clinical Studies (page 347): **optical profilometry**, independent dermatologist evaluation. Terms (page 442) and the PDP/bundle template previously said **"consumer panel"** — a different, self-report methodology. |
| Study | n=30 women, age 42–65, twice-daily use, 28 days |
| Measurement method | Optical profilometry (instrumental) |
| Report ID / date | Not published on-site (no report ID). Owner action: see OWNER-ACTIONS.md if a formal report/certificate is meant to be cited. |
| Status | **Fixed this pass.** `single-product.php` (Combined Clinical Results + individual PDP note) and the Terms page's blanket "consumer panel studies" sentence no longer misattribute this figure to a panel; both now read "instrumental measurement or panellist self-report, as disclosed per statistic" and link to the Clinical Studies page, which already correctly names optical profilometry for this figure. |

## 2. +47% perceived firmness (Advanced Repair Serum) — same class of issue

| Field | Detail |
|---|---|
| Claim | "+47% firmness at 28 days" |
| Product | Advanced Repair Serum (COS-002) |
| Category | Instrumental measurement |
| Evidence source | Clinical Studies page (347): **cutometer measurement**. The product FAQ (`inc/product-data.php`) previously said "independent consumer panel study." |
| Study | n=32, age 45–60, 28-day study (Clinical Studies page + Syn-Coll ingredient page, page 350) |
| Measurement method | Cutometry (instrumental) |
| Report ID / date | Not published on-site |
| Status | **Fixed this pass.** FAQ copy in `inc/product-data.php` now reads "cutometer-measured skin firmness improved by +47%..." instead of attributing it to a consumer panel. |

## 3. 100% panellist satisfaction (24h Regeneration Cream)

| Field | Detail | 
|---|---|
| Claim | "100% panellist satisfaction at 28 days" |
| Product | 24h Regeneration Cream (COS-004) |
| Category | Consumer perception (self-report) |
| Evidence source | Clinical Studies page — disclosed as a self-report satisfaction metric |
| Study | Same panel cohort as above, 28 days |
| Measurement method | Self-reported satisfaction survey |
| Status | **Verified.** Consistently described as a panel/self-report metric everywhere it appears — no change needed. |

## 4. +22% surface smoothness / Day-7 interim (Advanced Repair Serum)

| Claim | Category | Evidence | Status |
|---|---|---|---|
| "+22% surface smoothness, Day 7" | Instrumental (profilometry) | Clinical Studies page + Syn-Coll ingredient page, n=32/age 45–60 | **Verified** |

## 5. SYN®-COLL Peptide "+4.5× collagen production"

| Field | Detail |
|---|---|
| Claim | "increases Type I collagen production by up to 4.5×" |
| Product/page | Home (50), Ingredients index (60); properly framed already on the Syn-Coll ingredient page (350) and High-Tech Complex page (346) |
| Category | **In-vitro ingredient research** — not a finished-product or clinical claim |
| Evidence source | Published in-vitro studies on human dermal fibroblasts (cited on pages 346/350) |
| Status | **Fixed this pass.** Home and Ingredients-index previously presented this figure under a "Clinical collagen synthesis stimulator" / "Clinical-grade... in controlled studies" framing, which implied finished-product clinical substantiation. Both now explicitly say "in published in-vitro fibroblast studies" / "In-vitro studies show...", matching the honest framing already used on the ingredient detail pages, and continue to link through to them. |

## 6. "Cellular source" (Advanced Repair Serum step tile, Home page)

| Field | Detail |
|---|---|
| Claim (before) | "High-potency caviar extract targets loss of firmness directly at the cellular source." |
| Category | Unqualified mechanistic/medical-sounding claim, no evidence link |
| Status | **Fixed this pass** — softened to "High-potency caviar extract helps target the visible signs of firmness loss," removing the specific, uncited cellular-mechanism assertion while preserving the premium tone. |

## 7. "Biomimetic regeneration" / "cellular intelligence" / "biomimetic cellular chemistry"

| Field | Detail |
|---|---|
| Where | Home (50), Ingredients index (60), Swiss Science (344), FAQ (355) — short card/label copy |
| Category | Mechanism-of-action framing |
| Evidence source | The Why Caviar page (345) gives the substantive justification: caviar extract's phospholipid profile is "the closest naturally occurring match to human skin's intercellular lipid matrix identified in the published literature. That is the basis of the biomimetic claim." |
| Status | **Verified, with a caveat.** Every occurrence checked links through to a science/ingredient detail page that either is or leads to the Why Caviar page's justification. Left as-is this pass rather than rewritten, since the term is defined and evidenced elsewhere on-site — but this relies on the reader following the link. Recommend a follow-up pass adding an inline footnote/tooltip on the short-label occurrences (Home, Ingredients index, Swiss Science, FAQ) rather than requiring click-through, if a stricter standard is wanted. |

## 8. SIRT-1 / sirtuin activation (Resveratrol)

| Field | Detail |
|---|---|
| Where | Home, Ingredients index, Swiss Science, High-Tech Complex, Resveratrol ingredient page (353), FAQ |
| Category | In-vitro/published-literature ingredient science — no numeric efficacy stat is attached to it anywhere |
| Evidence source | Resveratrol ingredient page frames it as published dermatological literature ("independently studied and confirmed in the context of photoprotection"), not as this finished product's own clinical result |
| Status | **Verified as background science**, correctly not presented as a finished-product clinical claim. No numbers to correct. |

## 9. "Independently tested by dermatologists" / "panel-verified" / "fully verifiable"

| Field | Detail |
|---|---|
| Where | Home (50) Clinical Rigour trust bullet, Swiss Science (344), Craftsmanship (390) |
| Category | Evidence-process claim |
| Evidence source | Clinical Studies page (347) discloses: "Independent dermatologist evaluation commissioned by Caviar of Switzerland. Conducted by a third-party CRO under blinded conditions where applicable." |
| Status | **Verified**, substantively backed by the Clinical Studies page's methodology disclosure. The specific "panel-verified" wording sat next to the −38% stat mislabelled as panel-sourced — that inconsistency is the same one fixed in item 1 above, not a separate defect. |

## 10. UV resilience

| Field | Detail |
|---|---|
| Where | Product/ingredient copy | 
| Category | Cosmetic claim, correctly not conflated with SPF/sun protection | 
| Status | **Verified** — "UV resilience" is used, not "UV protection," and the site does not claim SPF-equivalent protection from these products. No change needed. |

## 11. Carbon-neutral delivery

| Field | Detail |
|---|---|
| Where | Swiss Heritage (61), Sustainability (391) |
| Category | Environmental claim |
| Evidence source | Sustainability page names a "third-party offset provider that meets the Gold Standard framework" and explicitly states "We are not a net-zero business... We do not claim otherwise." |
| Status | **Needs evidence for full substantiation** — the standard (Gold Standard) is named but no specific registry ID/certificate is published. Already reasonably hedged (explicit anti-greenwashing disclaimer present); added to OWNER-ACTIONS.md for publishing the certificate/registry ID. Not altered further this pass — the existing copy does not overclaim. |

## 12. Dermatologist/reviewer journal byline system

`_cos_journal_author_name` / `_cos_journal_reviewer_name` / `_cos_journal_reviewed_date` postmeta fields exist (wired into JSON-LD and a front-end "Reviewed by X on Y" badge) but are **unset on every article** — confirmed via direct postmeta query. Correctly empty rather than fabricated; the badge does not render placeholder/fake content. No action needed beyond what OWNER-ACTIONS.md already tracks (assign real names when available).

## Previously removed (confirmed still absent — no regression)

COS-001's 99.8%/0%, COS-002's 94%, COS-003's −29%/91%, and COS-004's
+52%/−24% were removed in an earlier pass for lack of disclosed
methodology. Verified still absent from `inc/product-data.php` and the
live PDP templates.

## Not found on site

"SIRT1" (no-hyphen), "dermatologist verified," "clinical-grade" outside
the collagen/Syn-Coll context, "UV protection" (as distinct from the
correctly-used "UV resilience"), "panel verified" (unhyphenated).
