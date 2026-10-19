---
name: Caviar of Switzerland
description: Swiss skincare, refined to the essential.
colors:
  warm-ivory: "#F4F1EA"
  pearl: "#FCFBF8"
  champagne: "#E8E0D2"
  pale-stone: "#DDD8CF"
  graphite: "#202221"
  body-graphite: "#3A3B3D"
  muted-graphite: "#5D5F60"
  warm-accent: "#625646"
  chrome: "#A7ACB2"
  footer-linen: "#EDE8DE"
  rule: "rgba(28,29,31,.14)"
  rule-strong: "rgba(28,29,31,.28)"
typography:
  display:
    fontFamily: "Cormorant Garamond, Georgia, serif"
    fontSize: "clamp(2.75rem, 1.73rem + 4.19vw, 5.5rem)"
    fontWeight: 400
    lineHeight: 1
    letterSpacing: "-0.018em"
  heading:
    fontFamily: "Cormorant Garamond, Georgia, serif"
    fontSize: "clamp(2.5rem, 1.65rem + 3.4vw, 4.5rem)"
    fontWeight: 400
    lineHeight: 1.06
    letterSpacing: "-0.012em"
  title:
    fontFamily: "Cormorant Garamond, Georgia, serif"
    fontSize: "clamp(1.5rem, 1.36rem + .57vw, 1.875rem)"
    fontWeight: 400
    lineHeight: 1.18
    letterSpacing: "-0.004em"
  lead:
    fontFamily: "Inter, system-ui, -apple-system, Segoe UI, Arial, sans-serif"
    fontSize: "clamp(1.0625rem, 1.016rem + .19vw, 1.1875rem)"
    fontWeight: 400
    lineHeight: 1.6
  body:
    fontFamily: "Inter, system-ui, -apple-system, Segoe UI, Arial, sans-serif"
    fontSize: "1rem"
    fontWeight: 400
    lineHeight: 1.7
  detail:
    fontFamily: "Inter, system-ui, -apple-system, Segoe UI, Arial, sans-serif"
    fontSize: "1rem"
    fontWeight: 400
    lineHeight: 1.55
    letterSpacing: "0.005em"
  label:
    fontFamily: "Inter, system-ui, -apple-system, Segoe UI, Arial, sans-serif"
    fontSize: "0.9375rem"
    fontWeight: 500
    lineHeight: 1.4
    letterSpacing: "0.2em"
  action:
    fontFamily: "Inter, system-ui, -apple-system, Segoe UI, Arial, sans-serif"
    fontSize: "0.9375rem"
    fontWeight: 500
    lineHeight: 1.3
    letterSpacing: "0.015em"
rounded:
  square: "0"
spacing:
  xs: "8px"
  sm: "16px"
  md: "24px"
  lg: "32px"
  xl: "48px"
  xxl: "64px"
  gutter: "clamp(24px, 5.2vw, 80px)"
  section: "clamp(72px, 4rem + 4vw, 128px)"
components:
  button-primary:
    backgroundColor: "{colors.graphite}"
    textColor: "{colors.pearl}"
    typography: "{typography.action}"
    rounded: "{rounded.square}"
    padding: "0 34px"
    height: "52px"
  text-link:
    textColor: "{colors.graphite}"
    typography: "{typography.detail}"
    height: "48px"
  input:
    backgroundColor: "{colors.pearl}"
    textColor: "{colors.graphite}"
    typography: "{typography.body}"
    rounded: "{rounded.square}"
    height: "52px"
---

# Design System: Caviar of Switzerland

## Overview

**Creative North Star: "The Swiss Atelier"**

Four real formulas are presented as a considered collection of precious objects. Warm light, chrome, cream, water and stone give the photography material presence; the interface supplies quiet structure and clear decisions. Upright Cormorant Garamond, selective genuine italic and readable Inter establish a restrained editorial voice.

The homepage expresses desire through the campaign; the storefront supports choice and purchase; editorial chapters make provenance, science and practice understandable. They share the same light material world without forcing the same composition onto every surface.

**Key Characteristics:**
- Warm tonal surfaces and graphite text.
- Exact packaging and a supplied, unchanged silver wordmark with Swiss cross.
- Unboxed compositions, square controls, generous spacing and fine rules.
- Visible benefits, readable evidence and natural scrolling.

## Colors

### Primary

Graphite carries headings, navigation and primary actions. Pearl provides the action's contrasting text. There is no saturated interface accent.

### Neutral

Warm ivory is the page ground; pearl is the brighter collection and science surface; champagne supports considered editorial and product evidence bands. Pale stone supplies dividers. Body graphite carries reading copy, muted graphite secondary information, and warm accent restrained labels. Chrome is decorative metal, never a substitute for legible text. Footer linen closes the same warm world.

**The Material Rule.** Depth belongs to the photograph; interface color remains a quiet material ground.

## Typography

**Display Font:** Cormorant Garamond, with Georgia and serif fallbacks. Local variable faces use weight 300 for display and 400 for product names.

**Body Font:** Inter, with system sans fallbacks. Reading text and editorial ledes use regular weight (400); actions use medium (500).

The frontmatter records shared homepage roles. Use genuine italic selectively within a heading; keep long reading passages in Inter and sentence case. Use lining numerals for product steps and evidence.

The campaign hero headline is the positioning line, "Swiss skincare, / *refined to the essential.*" (since 19 October 2026). Its composition-specific size: 40px on phones (31px below 360px, so the italic line stays on one line above the jar), 48px from 700px, 44px on narrow landscape screens (768–1099px), then fluid with the photographic field (48→60px across 1100–1440px, 60→72px across 1440–1920px), with a 1.04 line height. One proposition line follows in the lead role (17→19px, weight 400 over the photograph); provenance and evidence facts are left to the science and heritage sections. Editorial chapter titles use 44–80px, chapter headings 32–44px, and body copy 17px/1.7. These are surface variants, not replacement global scales.

**The Reading Floor Rule.** Main reading copy stays at least 16px; purchase actions stay at least 15px. The 14px tier is reserved for secondary metadata, credits and supporting navigation details.

## Layout

Use open grids and the shared fluid gutter. The homepage content width is 1440px plus its outer gutters; editorial introductions and photographs cap at 1440px, chapters at 1280px. Body measures are usually 24–34em; chapter prose may extend to 40em. Shared sections use the compact section token; intentional full photographic fields establish their own height.

Collection objects form two columns on phones and four from 1100px. Each object keeps its name, benefit, size, dynamic price and action visible. Homepage ritual steps form two columns on phones and four from 1100px, with instructions always visible. Science pillars become three columns from 700px, while measured results become two and self-reported findings remain separate. At 900px, provenance uses adjacent copy and photography. Editorial chapter and ritual rows stack below 700px.

The hero uses a full photographic field: centred copy and a lower action on phones, left copy and adjacent product space from 768px when landscape. Minimum hero heights are 560px on phones and 600px on landscape desktop, expanding to the small viewport height. Fixed navigation is 80px desktop and 64px below 1024px. Section anchors clear it with additional space.

## Elevation & Depth

UI stays flat: no card or button shadows. Separate surfaces with space, a tonal change or a fine rule. Real photographic light, chrome reflections and product volume provide depth. Product images retain their own authentic backgrounds; editorial photographs can have deliberate rectangular edges.

**The Flat Surface Rule.** Use space, tone and hairlines to establish hierarchy; do not add decorative elevation.

## Shapes

Buttons, fields, image stages and editorial bands use square corners. Rules are one pixel. Homepage collection objects are contained rather than cropped: stages are 2:3 at all widths. Ritual photography uses 4:5 at all widths. Meaningful circular controls or iconography may retain their functional shape.

## Components

**Primary button:** graphite with pearl text, square, at least 52px high. Shared buttons use the frontmatter padding. Hover gently changes the fill; the arrow moves 4px. The former inset decorative frame is disabled. **Hero exception (owner, 19 October 2026):** the hero action is a slim graphite outline (1px inset hairline, graphite 15px label, hairline arrow) that fills graphite with pearl text on hover and keyboard focus, so it does not compete with the chrome lid; 24px horizontal padding on phones and 28px desktop, 52px / 54px high.

**Text link:** a visible underline or resting hairline, comfortable 48px height and a trailing arrow where useful. Hover strengthens the underline. Keyboard focus remains visible, using a two-pixel outline with four-pixel offset; specific surfaces use three to seven pixels of offset.

**Collection object:** an unboxed, linked composition containing an accurate packshot, the ritual verb (serif italic at the lead size, no numeral or dash: the list order carries the sequence), serif name, visible benefit and one quiet price line ("€150 · 50 ml"), all left-aligned. Since 19 October 2026 there is no separate "Discover" row: the whole object is the link, and hover or keyboard focus underlines the name. The section uses four type sizes (heading, name, lead, 16px body); its heading is "The essential four." No overlay hides purchase information. Desktop hover raises the homepage product by 6px; shop cards remain still. Since 19 October 2026 the homepage packshots are `wc-coll-*`, cut from one supplied studio photograph of all four packs (all_high_res.jpg, May 2021, current silver-foil print): one scale for every canvas, rendered at one width on a shared baseline so the packs keep their true relative sizes, with only their photographed contact shadow and reflection. The older `collection-object-*` set (from the rejected flat masters: black print, equalised heights, painted drop shadows) must not return anywhere. Since 19 October 2026 every single-product context (homepage science, product gallery and ritual strip, Complete Ritual set, science hub and studies, the ritual page offer, shop grid, checkout thumbnails) uses `pack-*`: the same masters on the same 2:3 transparent canvas the old set used, so layouts were unchanged (`build_pack_canvas.py`, `pack.origin.json`).

**Evidence:** product-first attribution with a serif measured figure, finding, method, panel and duration. Homepage figures use 56px; self-reported figures use 36px and a separate ruled region. Editorial study figures use 64px and reported figures 40px. Never imply a complete-ritual study.

**Ritual step:** image, number and verb, followed by instructions that are always visible. The image may scale gently to 1.025 on hover. Content does not depend on hover.

**Fields:** pearl fill, square border, readable 16px input and a 50–52px minimum field height. Newsletter fields use a restrained warm border and an adjacent graphite action; keep visible status and accessible labels. Checkout and account retain their native WooCommerce semantics and feedback.

**Navigation and footer:** official logo with no filter or redraw. On the homepage's light header it is the graphite version (the homepage footer is dark graphite and keeps the silver original) (owner approved, 19 October 2026: the silver lettering is barely legible on light grounds): `logo-graphite.svg`, the official vector paths with only the lettering fill changed to #2A2B2D, red square and white cross unchanged (`build_logo_graphite.py`). The silver original `logo-master.svg` remains the mark for dark grounds; warm ivory navigation, sentence-case links and graphite utility icons. Mobile navigation uses a readable serif list. The footer continues the light material world with warm linen, clear service links and the same field/button vocabulary.

Motion is limited to a single one-second hero entrance from an already visible state (12px rise, opacity 0.86 to 1), restrained control transitions and optional hover image response. The motion script reveals existing hooks immediately and removes scroll gating. Content remains visible without JavaScript. Reduced motion removes the authored entrance and transitions; use native scrolling.

## Do's and Don'ts

### Do:
- **Do** preserve exact products, labels, pack proportions and the supplied official mark.
- **Do** keep benefits and purchase information visible on every device.
- **Do** scope every study result to its actual product, method, panel and duration.
- **Do** keep reading copy at least 16px and meaningful touch targets at least 44px.
- **Do** keep each shipping raster traceable to its source and transformation in its adjacent provenance sidecar.
- **Do** preserve the hero jar and lid region exactly. Since 19 October 2026 the hero places that protected region (jar, lid and their real floor reflection, pixel-identical to hero-jar_master.png) in a built ivory studio set instead of champagne silk (wc-hero-*, build_wc_hero.py, wc-hero.origin.json); responsive exports resize and compress the composed master. The source is 1672px wide, so the jar is enlarged on retina screens: a higher-resolution photograph of this composition is still needed.

### Don't:
- **Don't** ship generated direction studies as product artwork or treat their invented packs as approved assets.
- **Don't** redraw packaging, wordmarks or the Swiss cross, or recolor the official logo beyond the owner-approved graphite lettering for light grounds (never the red square or white cross).
- **Don't** add decorative shadows, rounded cards, cobalt styling or theatrical interface effects.
- **Don't** add invented reviews, press marks, scarcity, clinical claims or facility provenance.
- **Don't** hide explanations behind hover, scroll-gate content or introduce compulsory parallax.

## Verified refinement — 17 October 2026

The homepage retains the supplied hero and collection imagery without product-pixel edits. Provenance now uses an asymmetric cream/glass/steel formulation study beside factual Zug copy, rather than mountain imagery. The study is illustrative, not certified documentary photography of the manufacturing facility. The original silver logo is used without filters on navigation and footer.

Homepage benefits remain visible at 16px on phones. Purchase actions use 15px minimum. Ritual instructions are visible in a two-column mobile/four-column desktop grid. The footer uses light stone #EAE6DE. Scroll reveals and parallax are removed; one one-second hero entrance begins from 86% opacity. Content remains readable without JavaScript.

Independent visual comparison and browser geometry review: output/critic-20261017/independent-review.md. Scoped commerce/accessibility review: output/experience-20261017/REPORT.md. Mechanical detector findings were advisory type-ramp mismatches against earlier documentation, not observed accessibility failures. Physical iPhone Safari and production network performance remain unverified.

## Independent quality reopening — 17 October 2026

The first finish statement did not establish the commissioned luxury threshold. Fresh independent art direction, mobile UX and asset/performance reviewers reopened the work. The initial new art-direction verdict was FAIL for the provenance image, mixed ritual portraits and text-only closing. The commissioned revisions replace the suspect lab rendering with a product-free glass/water/stone study; use one illustrative mature application portrait beside four clear ordered steps; close on the unchanged four product assets with a factual full-size offer and live set/separate price comparison only while valid. Mechanism copy is shorter, with precise study attribution retained.

The two new images are generated illustrative campaign art, not documentary facility images, testimonials or study participants. Their complete built-in imagegen prompts, source paths and proportional derivative records sit beside them as swiss-material-study-20261017.origin.json and ritual-portrait-20261017.origin.json in assets/images/home-2026/. No product packaging appears in either.

The hero audit establishes pixel identity for the protected jar/lid/reflection region in the saved composed master against hero-jar_master.png. It does not assert that the entire photograph is unmodified: the silk environment is extended. Collection exports trace to the four source packshots with backdrop normalisation and alpha decomposition; geometry and label content are retained. Never substitute the broader claim that every product pixel was untouched by that historical pipeline.

Final practical verification: the art-direction reviewer independently approves all seven homepage sections beside the observed La Prairie, Augustinus Bader and Cle de Peau references. All five opening concepts are explicit on desktop and mobile. Three cold local mobile runs under 1.6 Mbps/150 ms/4x CPU give median LCP 1.740 s and initial CLS 0. A separate desktop lazy-image shift was traced to an indefinite provenance figure width and fixed with width:100%; the focused scroll rerun records zero shifts. Source evidence and limits: output/luxury-reopened-20261017/assets-performance.md. These measurements are local lab evidence, not production field performance.

Text-enlargement accommodation: collection names, step labels and price metadata can wrap; the nested product link has a minmax(0,1fr) column. Homepage measured results use em-based responsive columns and wrap method notes. This avoids the observed 200% text viewport clipping while preserving normal-size composition. Synthetic text enlargement is not a physical Safari test.
