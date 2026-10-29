---
version: 1
slug: "wp-content-themes-cos-child-page-the-ritual-php"
primary_target: "wp-content/themes/cos-child/page-the-ritual.php"
related_targets: ["wp-content/themes/cos-child/template-parts/hx/the-ritual","wp-content/themes/cos-child/assets/css/hx/the-ritual.css"]
---

# The Ritual (/the-ritual/, page 59) — "The Ritual Guide"

Shape brief confirmed by the owner, 23 October 2026. Mode: Persuade. Visual world unchanged (DESIGN.md); this brief changes structure only. Build path: code-led (no image generation). Surface seed 4ee578be; the owner chose "The Ritual Guide" over "The Day" and "The Sequence".

## Job and audience
A visitor deciding whether to buy, mostly arriving from the homepage or navigation, often on a phone. In about two minutes she must understand why four steps beat one product, then buy the Complete Ritual.

## Outcome and proof
Primary action: Complete Ritual Add to bag (simple product 481, in stock), in the first viewport and again at the close, in-page via the homepage collection mechanism (?add-to-cart= link without JS; wc-ajax + `hx:added_to_cart` → mini-bag with JS). Facts: live bundle price, separate total and saving (only while true); bundle data "includes a written ritual guide" (the concept's grounding); lightest → richest order with textures Water / Fluid / Light cream / Rich cream; amounts and gestures from inc/product-data.php. No study figures on this page; science stays one link away.

## Structure
1. First viewport: hxrt-tray photograph + h1 "Four formulas. One complete ritual." + one short line + price (+ comparison when true) + Add to bag + "Free worldwide shipping · 30-day returns". Must not read as a template split hero.
2. The guide sheet: narrow, centred, ruled "printed card from the box". Heading, then one rule: morning and evening, always in this order, lightest to richest. Four numbered entries: pack-* cut-out in the left margin; number, verb as h2, product, texture, one line on what it does, amount, gestures; morning/evening particulars inline (evening make-up removal at 01, SPF 50+ by day / overnight at 04); product link with price · size. Footnote: Siberian sturgeon (Acipenser baerii) / Zug line and the two science links.
3. One full-bleed model plate.
4. "What's in the box": the four packs in a row with step captions, box/guide copy, price, Add to bag again, assurances.

Removed: prelude quote; "Why the order matters" and "Morning and evening" sections (absorbed into the guide); three of the four model photographs; the <details> open/close script. Target length about 4,500px desktop (was ~8,700px).

## Scope and boundaries
Rebuild template-parts/hx/the-ritual/* and assets/css/hx/the-ritual.css; page-the-ritual.php stays the data source. Leave the world and hx/typeset-site.css alone. Keep data-cro-cta ids ritual-hero-primary and ritual-step-N-view-product; give the two bag buttons new ids. Anti-goals: split hero over a card row, collapsed instructions, decorative badges, any study figure on this page.

## States, layout, accessibility
Phones: each entry stacks (small cut-out beside the number, then text); all instructions visible. Bag button states: default, adding, added (mini-bag), failure falls back to the link. Saving line hidden when not true; price block hidden if no price. Buttons 52px, reading copy 16px+, light reveal on headings only, no motion gating content; works without JS and with reduced motion. Validate iPhone Safari and desktop.

## Open decisions (owner did not answer at confirmation; provisional defaults, ask before shipping)
1. Ritual box wording: whether the cleanser ships inside the box is undecided. Default: neutral wording ("with a written ritual guide"), no "inside the box" implication.
2. Model plate photograph: default the serum photograph (hxrt-step-serum).
3. "Who it suits" lives only on product pages.
