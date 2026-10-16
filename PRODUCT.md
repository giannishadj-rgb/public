# Product

<!-- impeccable:product-schema 1 -->

## Platform

web

## Users

### Core customer

A sophisticated, internationally minded woman, usually 40+, who values premium skincare, visible cosmetic results, Swiss quality and understated luxury. She prefers a curated, credible daily ritual to a complicated routine. When evaluating an unfamiliar premium brand, she needs both desire and reasons to trust its quality and efficacy.

Use three complementary personas: the mature skincare buyer sets the primary brand priorities; the international luxury discoverer informs growth and aspiration; the mobile researcher informs usability and conversion. These are overlapping motivations and behaviours, not three mutually exclusive customer segments.

### Primary: The Affluent Mature Skincare Buyer

**Directional profile:** A woman approximately 45–65+, comfortable considering premium skincare for concerns such as loss of firmness, fine lines and dryness. Affluence and buying motivations are inferred from the supplied analysis, not directly established by GA4 demographics.

**Job:** Decide whether an unfamiliar skincare house offers enough quality, relevant benefits and credible evidence to justify its price; choose an individual formula or a manageable complete ritual.

**Needs:** Visible cosmetic benefits, precise scientific reassurance, premium formulation, beautiful packaging and a sophisticated routine that is easy to understand. She should be able to explain why the brand is premium, why caviar matters, what each formula does and how the four formulas complement one another.

**Brand priorities:** Calm authority, relevant skin concerns, readable evidence, Swiss provenance and curation. “Four formulas. One complete ritual.” makes the small range a deliberate advantage. Represent mature customers with respect rather than assuming that youthful imagery or trend-led language will resonate with everyone.

### Secondary: The Global Luxury Discoverer

**Directional profile:** An internationally minded, digitally confident premium buyer, approximately 35–54. Age and lifestyle motivations are hypotheses from the supplied analysis. The reported international audience includes the United States, Switzerland, United Kingdom, Canada, Germany, Singapore and Thailand.

**Job:** Discover a distinctive skincare house and assess whether its products, provenance and experience belong in her wider premium lifestyle.

**Needs:** A memorable brand identity, tangible product quality, authentic Swiss provenance, a distinctive ingredient story and confidence when ordering internationally. Luxury beauty, travel and shopping interests are audience signals, not proof that every visitor shares the same lifestyle.

**Brand priorities:** Strong product presentation, understated prestige and coherent storytelling, supported by factual evidence and clear delivery/returns information. Communicate the curated range without inventing scarcity, endorsements or exclusivity claims.

### Behavioural: The Mobile Beauty Researcher

**Profile:** A visitor who discovers and compares skincare on a phone, often with limited attention. This behaviour can apply to either persona above; it is not a separate age or gender category.

**Job:** Understand the brand and product benefits quickly, compare formulas and reach the appropriate purchase information with minimal friction.

**Needs:** Immediate clarity, readable text, clear navigation, one recognisable benefit per formula, easy comparison and obvious next actions. Maintain a good desktop purchase experience as well as a strong mobile discovery experience. iPhone/iOS and Safari deserve particular validation because the supplied analysis reports meaningful revenue from those audiences.

**UX priorities:** Mobile readability and touch targets, concise explanations, accessible interactions and continuity of product information from discovery to purchase. Do not assume that an individual mobile researcher later buys on desktop: aggregate device revenue does not establish a cross-device journey.

### Evidence and confidence

Source: user-supplied GA4 persona analysis provided on 12 October 2026. The underlying GA4 exports and reporting period were not supplied for this update. Figures below are approximate reported observations, not independently verified analytics or permanent audience targets.

- A large proportion of age/gender values are unknown. Demographic ranges and persona motivations are directional; do not treat the identifiable subset as the entire customer base.
- Among known-gender revenue, the analysis reports approximately €3,395 from women versus €559.50 from men. Reported engagement is roughly 68–71% across the 45–54, 55–64 and 65+ age groups; the 65+ group has a 2.21% user key-event rate. A key event is not necessarily a purchase.
- The analysis reports approximately 30,066 mobile users versus 24,452 desktop users, with about €14.0k mobile revenue and €19.2k desktop revenue. This supports prioritising mobile discovery while retaining desktop quality; it does not prove a conversion cause or a device-switching journey.
- Reported iOS revenue is approximately €10.3k and Safari revenue €13.7k. OS and browser figures are overlapping views and must not be added together or treated as an exclusively iPhone audience.

Treat the primary persona as a strategic priority, not a proven highest-value demographic segment. Refine these hypotheses with dated analytics, customer interviews and purchase behaviour as evidence becomes available.

## Product Purpose

Caviar of Switzerland is a Swiss skincare house selling a deliberately small collection: four caviar-based formulas used as one daily ritual, available individually or together as the Complete Ritual. The site sells those products directly (WooCommerce) and explains the science behind them on dedicated pages. Success: a first-time visitor understands the four formulas, trusts the evidence, and buys a product or the full ritual.

## Positioning

Swiss restraint and a small, considered ritual. A focused four-step routine — Cleanse, Treat, Revive, Restore — with caviar at the heart of every formula, developed and made in Zug. Quiet, intimate luxury rather than the theatrical, range-heavy presentation of larger caviar-skincare houses (La Prairie owns cobalt blue + caviar; do not echo it). The homepage makes her want the brand; the deeper pages make her believe it.

## Operating Context

- Products (live WooCommerce prices; the homepage reads them dynamically):
  - 01 Cleanse — Micellar Water All-in-One Cleanser, 150 ml
  - 02 Treat — Advanced Repair Serum, 30 ml
  - 03 Revive — Revitalizing Eye Cream, 15 ml
  - 04 Restore — 24h Regeneration Cream, 50 ml
  - The Complete Ritual — all four full-size formulas, priced below the four bought separately (the homepage shows the comparison only while it is true).
- Used morning and evening; each step prepares the skin for the next (explained on /the-ritual/).
- Signature active blend ("High-Tech Complex"): Caviar Extract, SYN®-COLL peptide, Hyaluronic Acid, Superoxide Dismutase, Resveratrol. Caviar actives are micro-encapsulated for protected potency and time-release delivery.
- Service: free worldwide shipping, 30-day returns.

## Capabilities and Constraints

- WordPress + WooCommerce, child theme `cos-child`. The homepage is built from theme components (`page-home-2026.php`, `template-parts/home-2026/`); many other pages are hand-authored HTML in each page's post_content.
- Staging deploy via WinSCP; database changes do not travel with theme deploys.
- Payments: only Cash on Delivery enabled locally; Stripe/WooCommerce Payments pending merchant onboarding (see OWNER-ACTIONS.md).
- Claims are governed by `CLAIMS-REGISTER.md` — no claim beyond what it marks Verified/Fixed.
- **Confirmed facts:**
  - Caviar source: Siberian sturgeon (*Acipenser baerii*), sustainably farmed, fully traceable, never wild-harvested. The four INCI lists in `inc/product-data.php` still read "Acipenser Gueldenstaedtii Roe Extract" and must be corrected to match.
  - The brand has been established for more than 20 years (may be stated).
- **Undecided — do not claim:**
  - The name of the independent contract research organisation (CRO) that ran the studies; describe it only as "an independent contract research organisation".
  - Whether the cleanser ships inside the Complete Ritual box or alongside it.
  - Company registration / VAT details (legal sign-off pending).

## Brand Commitments

- Name: Caviar of Switzerland. Logo: silver wordmark with the red Swiss cross — shown as supplied.
- Voice: calm, precise, understated; sentence case; no hype, no exclamation, no medical claims (cosmetic efficacy only).
- Product photography must show accurate packaging and labels (e.g. cleanser 150 ml / 5.1 FL.OZ.us); corrected masters live in `app/design-sources/home-2026/`.
- No star ratings, testimonials or press logos until genuine, attributable material exists (an owner decision).

## Evidence on Hand

From /science/clinical-studies/ (page 347) — four separate per-product studies, 28 days, independent CRO with dermatologist evaluation; there is no complete-ritual study and none for the cleanser:
- +47% skin firmness — Advanced Repair Serum, cutometry (R2), 32 women aged 45–60 (instrument-measured).
- −38% crow's-feet depth — Revitalizing Eye Cream, optical profilometry, 30 women aged 42–65 (instrument-measured).
- 100% panellist-reported satisfaction — 24h Regeneration Cream, self-assessment, 34 women aged 40–65 (self-reported; always presented as secondary).
- +22% surface smoothness at day 7 — Advanced Repair Serum (profilometry).
- SYN®-COLL "up to 4.5× collagen": in-vitro fibroblast research only — never present as a finished-product or clinical result.
- Manufacturing: GMP-certified laboratory in Zug, ISO 22716:2007; batch release and traceability.

Absent — must not be fabricated: customer reviews, testimonials, press coverage, awards, retailer endorsements, the CRO's name, report IDs.

## Product Principles

1. Desire before explanation: product and photography lead; technical detail lives on deeper pages.
2. Evidence precisely labelled: instrument-measured results first; self-reported clearly secondary; every figure named with its product, method and panel.
3. Four formulas, one ritual: present the collection as a curated set, never as a catalogue.
4. Swiss restraint: fewer, better elements; quiet confidence over theatre.
5. Truth over polish: never claim, invent or imply what the evidence and owner have not confirmed.

## Accessibility & Inclusion

Mobile-first for a broad audience that includes mature skincare buyers aged approximately 45–65+: readable body text (16px+), generous touch targets (44px+), WCAG AA contrast and reduced-motion preferences. Validate iPhone/Safari and desktop experiences; do not assume all customers use the same device or have the same visual and motor needs.
