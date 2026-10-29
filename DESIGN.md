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
    fontSize: "clamp(1.625rem, 1.3rem + 1.3vw, 2.5rem)"
    fontWeight: 300
    lineHeight: 1.14
    letterSpacing: "0.04em"
    textTransform: "uppercase"
  statement:
    fontFamily: "Cormorant Garamond, Georgia, serif"
    fontSize: "min(3.25rem, (100vw - 2 * gutter) * .076)"
    fontWeight: 300
    lineHeight: 1.18
    letterSpacing: "0.04em"
    textTransform: "uppercase"
  title:
    fontFamily: "Cormorant Garamond, Georgia, serif"
    fontSize: "clamp(1.25rem, 1.15rem + .4vw, 1.5rem)"
    fontWeight: 400
    lineHeight: 1.22
  figure:
    fontFamily: "Cormorant Garamond, Georgia, serif"
    fontSize: "clamp(3rem, 2.4rem + 2.4vw, 4.5rem)"
    fontWeight: 300
    lineHeight: 0.95
    letterSpacing: "-0.01em"
  body:
    fontFamily: "Inter, system-ui, -apple-system, Segoe UI, Arial, sans-serif"
    fontSize: "1rem"
    fontWeight: 400
    lineHeight: 1.7
  detail:
    fontFamily: "Inter, system-ui, -apple-system, Segoe UI, Arial, sans-serif"
    fontSize: "0.875rem"
    fontWeight: 400
    lineHeight: 1.55
    letterSpacing: "0.01em"
  label:
    fontFamily: "Inter, system-ui, -apple-system, Segoe UI, Arial, sans-serif"
    fontSize: "0.75rem"
    fontWeight: 500
    lineHeight: 1.5
    letterSpacing: "0.2em"
    textTransform: "uppercase"
  action:
    fontFamily: "Inter, system-ui, -apple-system, Segoe UI, Arial, sans-serif"
    fontSize: "0.78125rem"
    fontWeight: 500
    lineHeight: 1.3
    letterSpacing: "0.16em"
    textTransform: "uppercase"
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

**Couture typeset (owner, 19 October 2026: "prestigious and luxurious like Dior", hierarchy restored 29 October 2026).** The homepage uses seven roles, set in `assets/css/hx/typeset.css`, which loads last on the homepage only. Authority comes from capitals, tracking and space, not from indiscriminate enlargement, and colour is monochrome ink and graphite (no warm-accent type). Display: Cormorant 300 in capitals with +0.04em tracking (28 to 40px for section titles, 32 to 52px for the closing statement). Title: Cormorant 400 sentence case at 21 to 24px for findings and editorial facts. Figure: 48 to 72px (reported 32 to 40px). Body: Inter 16px/1.65. Product names: Inter 500 at 15 to 16px. Benefits: Inter 400 at 15px minimum. Detail: 14px. Label: Inter 500, 12px capitals, 0.2em. Action: Inter 500, 12.5px capitals, 0.16em, for every link, button and CTA.

The campaign hero headline is the positioning line, set in capitals: "SWISS SKINCARE, / *REFINED TO THE ESSENTIAL.*" (the source keeps title case; capitals come from CSS). It uses the display voice at a composition-specific 28px floor, rising fluidly to about 40px at 1440px; on the narrowest phones it may wrap deliberately rather than shrink into the supporting-title tier. One quiet proposition line follows at the 16px reading floor in body graphite. The header navigation matches the label register: Inter 12px capitals, 0.14em. Editorial chapter pages keep their own scales; these are homepage roles, not replacement global scales.

**Two families, sitewide (verified on every page, 25 October 2026).** Only Cormorant Garamond and Inter render anywhere on the site, including the header, footer, skip link and hand-authored content pages. Inter is used at 400 (reading and metadata) and 500 (labels, actions and homepage collection names). There is no Inter Light (300) and no Inter italic. Cormorant italic is reserved for an `<em>` inside a heading; Latin species names may be italic. Source Serif 4 was removed from the theme.

**The Reading Floor Rule.** Main reading copy, ledes and study methods stay at least 16px. Product benefits stay at least 15px. Actions are 12.5px tracked capitals (owner, 19 October 2026), always with a 44px or taller target; uppercase labels never go below 12px. The 14px tier is reserved for secondary commerce metadata and credits.

## Layout

**Plates and rooms (owner, 19 October 2026: "prestigious and luxurious like Dior").** The homepage alternates two kinds of space, set in `assets/css/hx/composition.css` (homepage only, loaded after typeset.css). Plates (hero, heritage) are full-bleed photographs with type composed on the image, left. Rooms (collection, our story, science, ritual, closing) share one centred axis: a centred head, intros capped at 34em, product captions centred under their objects, and centred closing links. They also share one vertical rhythm, `--ts-room` (88px on phones to 152px wide), applied top and bottom; the closing takes 1.25× before its statement and a tight signature after. Reading prose (science pillars, ritual instructions, result columns) stays left-aligned inside its column.

Use open grids and the shared fluid gutter. The homepage content width is 1440px plus its outer gutters; editorial introductions and photographs cap at 1440px, chapters at 1280px. Body measures are usually 24–34em; chapter prose may extend to 40em. Shared sections use the compact section token; intentional full photographic fields establish their own height.

Collection objects form two columns on phones and four from 1100px. Each object keeps its name, benefit, size, dynamic price and action visible. Homepage ritual steps form two columns on phones and four from 1100px, with instructions always visible. Science pillars become three columns from 700px, while homepage measured results become three (the self-reported cream without a figure). At 900px, provenance uses adjacent copy and photography. Editorial chapter and ritual rows stack below 700px.

The hero uses a full photographic field: centred copy and a lower action on phones, left copy and adjacent product space from 768px when landscape. Minimum hero heights are 560px on phones and 600px on landscape desktop, expanding to the small viewport height. Fixed navigation is 80px desktop and 64px below 1024px. Section anchors clear it with additional space.

## Elevation & Depth

UI stays flat: no card or button shadows. Separate surfaces with space, a tonal change or a fine rule. Real photographic light, chrome reflections and product volume provide depth. Product images retain their own authentic backgrounds; editorial photographs can have deliberate rectangular edges.

**The Flat Surface Rule.** Use space, tone and hairlines to establish hierarchy; do not add decorative elevation.

## Shapes

Buttons, fields, image stages and editorial bands use square corners. Rules are one pixel. Homepage collection objects are contained rather than cropped: stages are 2:3 at all widths. Ritual photography uses 4:5 at all widths. Meaningful circular controls or iconography may retain their functional shape.

## Components

**Primary button:** graphite with pearl text, square, at least 52px high. Shared buttons use the frontmatter padding. Hover gently changes the fill; the arrow moves 4px. The former inset decorative frame is disabled. **Hero exception (owner, 19 October 2026):** the hero action is a slim graphite outline (1px inset hairline, graphite 15px label, hairline arrow) that fills graphite with pearl text on hover and keyboard focus, so it does not compete with the chrome lid; 24px horizontal padding on phones and 28px desktop, 52px / 54px high.

**Text link:** a visible underline or resting hairline, comfortable 48px height and a trailing arrow where useful. Hover strengthens the underline. Keyboard focus remains visible, using a two-pixel outline with four-pixel offset; specific surfaces use three to seven pixels of offset.

**Our story (owner, 19 October 2026: after Sensai's "Our Story", in the site's colours; "pearls have nothing to do with it"):** the former caviar section tells the house's own story in Sensai's register. A centred head (display title "Our story", the Inter line "Our story begins in Zug, with caviar.", an outlined square "Discover" to /heritage/) sits above a triptych of 4:5 photographs on a 6px seam (4:3 stacked below 900px), each chapter a link: “Our history” / "More than twenty years in Zug" (alpine valley, → heritage), “Siberian Caviar” / "The heart of every formula" (owner caviar macro, → why caviar), “The Ritual” / "Four formulas, one daily ritual" (November 2022 photograph of the four current packs, → the ritual). Chapter names use the same Inter 500 sentence-case role as collection names. Short evocative lines only; no pearl wording, no result claims.

**Measured on skin (owner, 20 October 2026; compact science section after Our story, `measured.php`, `hx/measured.css`, prefix hxm-, id #evidence):** a pearl room between the two ivory ones. Centred display title "Measured, formula by formula" and one framing line (separate 28-day single-formula studies by an independent contract research organisation). Three results, one per studied formula, three across from 900px with the middle (eye) result set lower as an editorial stagger: a 5:4 owner photograph of the product (serum: hxhe-drop; eye cream: story-eye, captioned "Campaign photograph, not study participants" because the models look younger than the panel; 24h cream: hxhe-jar, the same shoot as the serum), then the serif figure beside its finding (above it between 900 and 1099px), a product link in tracked capitals, and method · panel · duration at the 16px reading floor; the serum adds +22% smoothness by day 7. The cream is self-reported, so it never gets a figure: "Overall satisfaction", its product link, "Self-assessment, 34 women aged 40–65, 28 days" and "100% panellist-reported satisfaction" in text (owner, 20 October 2026). Below a hairline, "Read the studies" on the right. No figure is ever placed on a face.

**Swiss science section (owner-confirmed shape brief, 20 October 2026; `/science/` hub + five chapters, `inc/hx-science.php`, `template-parts/hx/science/*`, `hx/science-pages.css`, prefix hxsc-):** the homepage's world at reading pace. Type always comes before the photograph and never sits on it: the opening plate is either a band (caps h1 left, lead right, then the photograph full-bleed) or a split (copy left, photograph bleeding off the right edge); no eyebrow above the h1, the chapter sub-navigation carries the position. Warm white grounds, because the product cut-outs (pack-*) are shot on #FBFAF6 and stand on the page with no frame; pearl for alternate sections; 1px rules; monochrome labels. The hub is proof, then the way in: the three words, the three studied formulas as aligned columns (cut-out, result, finding, product, method), the five chapters as a photo index, then a quiet close to the Complete Ritual. Only instrument-measured results take the serif figure; the 24h cream's self-reported result is always text. Chapter components: two line diagrams (the four relations between the actives; hyaluronic acid at three depths), the five actives alternating image and text in one warm-monochrome treatment, study rows with a "Study detail" disclosure, the 28-day protocol, six stages and four facts.

**Every other page (owner, 21 October 2026: "use the homepage as your guide for fonts, styling, look and feel and apply it on all pages"; `hx/typeset-site.css`, enqueued last on every page but the homepage):** the homepage roles carried sitewide. Page titles (h1) in Cormorant 300 capitals +.04em at 32→48px (product names one step down, 26→40px); section titles in the 26→40px display capitals; headings inside reading copy (journal, skin concerns, help, shipping, ingredients) in 20→26px capitals; names, cards and questions in the 20→24px sentence-case serif title; figures 48→72px; reading copy 16px Inter 400; labels 12px tracked capitals in ink (steps and meta muted); every button and CTA 12.5px tracked capitals. Monochrome: the bronze accent and the old gold are replaced by muted graphite, warm ivory bands by pearl, pure-black buttons by graphite ink. The old hand-authored pages no longer put white italic type on a near-black photo veil: the title sits on the warm-white ground first and the photograph follows as a full-bleed plate, as on the science and heritage pages; dark title bands (journal category hubs, sitemap) and the black evidence band on skin-concern pages are light. Theme-only, no page content changed.

**Collection tile (owner, 20 October 2026; rebuilt against Guerlain's Orchidée Impériale grid in a blind builder/critic loop, then finished with the owner's own photography):** each tile is the owner's studio packshot (wc-stage-*): the pack on a graphite stage (never navy: La Prairie owns cobalt) with a lit horizon and a real floor reflection, photographed 4:5 and shown uncropped in a 4:5 tile at every width. Four across from 1100px, near edge to edge on a 12px seam; two across below (960px max on tablets). Type sizes match Guerlain's measured grid: section title in Cormorant capitals 28px (20px on phones), lede 16px on two balanced lines; beneath each photograph, left-aligned on one inset: ritual step 12px Inter tracked label, name in Cormorant capitals 600 at 14px in #111, benefit 12px directly under it, then price · size 14px (12px on phones). Step and price rows align across each row by subgrid. The bag is a bare hairline pearl glyph (no filled block) in a 44px target in the photograph's bottom-right corner, 8px in (36px on phones inside a 44px tap area); hover and focus draw a 1px pearl frame, and it fills pearl only briefly once added; hover eases the photograph in (scale 1.03) and underlines the name. The bag button is a sibling of the product link, never nested; without JavaScript it is a WooCommerce ?add-to-cart= link; with JavaScript it adds in-page (wc-ajax + `hx:added_to_cart` → mini-bag); simple, purchasable, in-stock products only.

**Collection object (earlier):** an unboxed, linked composition containing an accurate packshot, the ritual verb (serif italic at the lead size, no numeral or dash: the list order carries the sequence), serif name, visible benefit and one quiet price line ("€150 · 50 ml"), all left-aligned. Since 19 October 2026 there is no separate "Discover" row: the whole object is the link, and hover or keyboard focus underlines the name. The section uses four type sizes (heading, name, lead, 16px body); its heading is "The essential four." No overlay hides purchase information. Desktop hover raises the homepage product by 6px; shop cards remain still. Since 19 October 2026 the homepage packshots are `wc-coll-*`, cut from one supplied studio photograph of all four packs (all_high_res.jpg, May 2021, current silver-foil print): one scale for every canvas, rendered at one width on a shared baseline so the packs keep their true relative sizes, with only their photographed contact shadow and reflection. The older `collection-object-*` set (from the rejected flat masters: black print, equalised heights, painted drop shadows) must not return anywhere. Since 19 October 2026 every single-product context (homepage science, product gallery and ritual strip, Complete Ritual set, science hub and studies, the ritual page offer, shop grid, checkout thumbnails) uses `pack-*`: the same masters on the same 2:3 transparent canvas the old set used, so layouts were unchanged (`build_pack_canvas.py`, `pack.origin.json`).

**Evidence:** product-first attribution with a serif measured figure, finding, method, panel and duration. Homepage figures use 56px; self-reported figures use 36px and a separate ruled region. Editorial study figures use 64px and reported figures 40px. Never imply a complete-ritual study.

**Ritual step:** image, number and verb, followed by instructions that are always visible. The image may scale gently to 1.025 on hover. Content does not depend on hover.

**Fields:** pearl fill, square border, readable 16px input and a 50–52px minimum field height. Newsletter fields use a restrained warm border and an adjacent graphite action; keep visible status and accessible labels. Checkout and account retain their native WooCommerce semantics and feedback.

**Navigation and footer:** official logo with no filter or redraw. On the homepage's light header it is the graphite version (the homepage footer is dark graphite and keeps the silver original) (owner approved, 19 October 2026: the silver lettering is barely legible on light grounds): `logo-graphite.svg`, the official vector paths with only the lettering fill changed to #2A2B2D, red square and white cross unchanged (`build_logo_graphite.py`). The silver original `logo-master.svg` remains the mark for dark grounds; warm ivory navigation, 12px tracked-capital links and graphite utility icons, identical on every page; no Help icon in the bar. Mobile navigation uses a readable serif list. The footer continues the light material world with warm linen, clear service links and the same field/button vocabulary.

Motion has one idea: light travelling over polished silver (owner request, 20 October 2026). The hero is the authored moment: the light comes up and the photograph racks into focus (blur 10px to 0, scale 1.035 to 1, 1.7s), then a soft band of light writes each headline line in turn, starting partly lit; the supporting line and action follow with a 12px rise from opacity 0.86. Further down, the same light reveals each section heading (data-hx-reveal="light", starting mostly lit, 800ms), and the closing line repeats it as a bookend. **Below-fold timing (animate pass, 20 October 2026):** every scroll reveal finishes in 600–900ms and starts at most 150ms after it enters the viewport (50ms list stagger, delays capped), so text reads almost at once: body text fades and rises 14px in 700ms, labels fade in 600ms. **Photographs are never hidden by motion:** no clip curtain, darkness or fade gates an image, so it is visible as soon as the browser paints it, independent of when the reveal fires or the file loads; image reveals are a 1.04 to 1 scale settle inside their own frame (900ms), The Ritual's panels rise 14px as a group, and in the collection a soft band of studio light crosses each photograph once (900ms overlay, the photograph untouched) with its text fading in from intersection. Reveals are skipped (shown at once) for anything on screen at load, content entering from above, any jump of more than a viewport (deep links, End, scrollbar drags, find-in-page), and keyboard focus moving into a section. Same-page anchors glide natively for up to two viewports and jump beyond that, update the hash and move focus to the target; there is no CSS smooth scrolling, which used to animate deep links through the whole page on load. Reveals are decelerating (--hx-ease), hide text only when the script has run and only below the fold, and never hijack scrolling or loop. Content remains visible without JavaScript. Reduced motion shows everything at once, without masks, blur or movement; use native scrolling. **Sitewide since 25 October 2026 (owner: "uniform across the entire site"):** this is the motion of every page, not only the homepage. One engine (assets/js/hx-motion.js) and one set of motion tokens (700ms reveal, 900ms image, 14px rise, 50ms stagger, 150ms cap) serve all pages. Every page's opening (the section holding its h1) gets the hero's entrance: its photograph or video racks into focus, the h1 is written by the light, and the lines around it rise in. Every h2 is written by the light as it scrolls in. Hand-authored content pages (journal, FAQ, contact, help, shipping, skin concerns, sitemap, 404) are tagged at load with the same language, replacing their old one-off fade-ups. Cart, checkout and account have no reveals; only control feedback moves there.

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

Homepage benefits remain visible at 16px on phones. Purchase actions use 15px minimum (superseded 19 October 2026: 12.5px tracked capitals, see Typography). Ritual instructions are visible in a two-column mobile/four-column desktop grid. The footer uses light stone #EAE6DE. Scroll reveals and parallax are removed; one one-second hero entrance begins from 86% opacity. Content remains readable without JavaScript.

Independent visual comparison and browser geometry review: output/critic-20261017/independent-review.md. Scoped commerce/accessibility review: output/experience-20261017/REPORT.md. Mechanical detector findings were advisory type-ramp mismatches against earlier documentation, not observed accessibility failures. Physical iPhone Safari and production network performance remain unverified.

## Independent quality reopening — 17 October 2026

The first finish statement did not establish the commissioned luxury threshold. Fresh independent art direction, mobile UX and asset/performance reviewers reopened the work. The initial new art-direction verdict was FAIL for the provenance image, mixed ritual portraits and text-only closing. The commissioned revisions replace the suspect lab rendering with a product-free glass/water/stone study; use one illustrative mature application portrait beside four clear ordered steps; close on the unchanged four product assets with a factual full-size offer and live set/separate price comparison only while valid. Mechanism copy is shorter, with precise study attribution retained.

The two new images are generated illustrative campaign art, not documentary facility images, testimonials or study participants. Their complete built-in imagegen prompts, source paths and proportional derivative records sit beside them as swiss-material-study-20261017.origin.json and ritual-portrait-20261017.origin.json in assets/images/home-2026/. No product packaging appears in either.

The hero audit establishes pixel identity for the protected jar/lid/reflection region in the saved composed master against hero-jar_master.png. It does not assert that the entire photograph is unmodified: the silk environment is extended. Collection exports trace to the four source packshots with backdrop normalisation and alpha decomposition; geometry and label content are retained. Never substitute the broader claim that every product pixel was untouched by that historical pipeline.

Final practical verification: the art-direction reviewer independently approves all seven homepage sections beside the observed La Prairie, Augustinus Bader and Cle de Peau references. All five opening concepts are explicit on desktop and mobile. Three cold local mobile runs under 1.6 Mbps/150 ms/4x CPU give median LCP 1.740 s and initial CLS 0. A separate desktop lazy-image shift was traced to an indefinite provenance figure width and fixed with width:100%; the focused scroll rerun records zero shifts. Source evidence and limits: output/luxury-reopened-20261017/assets-performance.md. These measurements are local lab evidence, not production field performance.

Text-enlargement accommodation: collection names, step labels and price metadata can wrap; the nested product link has a minmax(0,1fr) column. Homepage measured results use em-based responsive columns and wrap method notes. This avoids the observed 200% text viewport clipping while preserving normal-size composition. Synthetic text enlargement is not a physical Safari test.
