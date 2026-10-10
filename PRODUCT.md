# Product

<!-- impeccable:product-schema 1 -->

## Platform

web

## Users

Primary: women aged 45–55 with mature-skin concerns — loss of firmness, fine lines, dryness — who discover, compare and buy mostly on an iPhone. She is evaluating an unfamiliar premium brand and needs to want it first and believe it second.

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

Mobile-first for a 45–55 audience: readable body text (16px+), generous touch targets (44px+), WCAG AA contrast, respects reduced-motion preferences.
