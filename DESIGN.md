---
name: Caviar of Switzerland
description: Swiss skincare, refined to the essential.
colors:
  pearl: "#F8F8F8"
  stone-ground: "#EDE9E1"
  champagne: "#E8E0D2"
  pale-stone: "#DDD8CF"
  ink: "#1C1D1F"
  graphite: "#2A2B2D"
  body-graphite: "#3A3B3D"
  muted-graphite: "#5C5C5A"
  warm-accent: "#625646"
  chrome: "#A7ACB2"
  chrome-numeral: "#8A8E94"
  footer-ink: "#1C1D1F"
  footer-text: "#C9C7C1"
  rule: "rgba(28,29,31,.14)"
  rule-strong: "rgba(28,29,31,.28)"
  rule-hair: "rgba(28,29,31,.08)"
typography:
  h1:
    fontFamily: "Source Serif 4, Georgia, Times New Roman, serif"
    fontSize: "clamp(2rem, 1.6rem + 1.6vw, 3rem)"
    fontWeight: 300
    lineHeight: 1.05
    letterSpacing: "-0.01em"
  section:
    fontFamily: "Source Serif 4, Georgia, Times New Roman, serif"
    fontSize: "clamp(1.375rem, 1.15rem + .85vw, 2rem)"
    fontWeight: 300
    lineHeight: 1.15
    letterSpacing: "-0.01em"
  title:
    fontFamily: "Source Serif 4, Georgia, Times New Roman, serif"
    fontSize: "clamp(1.125rem, 1.05rem + .25vw, 1.25rem)"
    fontWeight: 300
    lineHeight: 1.2
    letterSpacing: "-0.005em"
  body:
    fontFamily: "Inter, system-ui, -apple-system, Segoe UI, Arial, sans-serif"
    fontSize: "0.875rem"
    fontWeight: 400
    lineHeight: 1.25
  prose:
    fontFamily: "Inter, system-ui, -apple-system, Segoe UI, Arial, sans-serif"
    fontSize: "0.875rem"
    fontWeight: 400
    lineHeight: 1.5
  footer:
    fontFamily: "Inter, system-ui, -apple-system, Segoe UI, Arial, sans-serif"
    fontSize: "1rem"
    fontWeight: 400
    lineHeight: 1.5
  label:
    fontFamily: "Inter, system-ui, -apple-system, Segoe UI, Arial, sans-serif"
    fontSize: "0.75rem"
    fontWeight: 500
    lineHeight: 1.3
    letterSpacing: "0.14em"
  nav:
    fontFamily: "Inter, system-ui, -apple-system, Segoe UI, Arial, sans-serif"
    fontSize: "0.75rem"
    fontWeight: 400
    lineHeight: 1.5
    letterSpacing: "0.14em"
rounded:
  square: "0"
spacing:
  s1: "4px"
  s2: "8px"
  s3: "12px"
  s4: "16px"
  s5: "24px"
  s6: "32px"
  s7: "48px"
  s8: "64px"
  s9: "96px"
  s10: "128px"
  margin: "clamp(24px, 5.2vw, 80px)"
  gap: "clamp(16px, 2.2vw, 32px)"
  section-y: "clamp(88px, 72.4px + 4vw, 130px)"
components:
  button-primary:
    backgroundColor: "{colors.ink}"
    textColor: "{colors.pearl}"
    typography: "{typography.label}"
    rounded: "{rounded.square}"
    padding: "14px 34px"
    height: "52px"
  button-primary-hover:
    backgroundColor: "{colors.graphite}"
    textColor: "{colors.pearl}"
  button-outline:
    backgroundColor: "transparent"
    textColor: "{colors.ink}"
    typography: "{typography.label}"
    rounded: "{rounded.square}"
    padding: "0 34px"
    height: "52px"
  button-outline-hover:
    backgroundColor: "{colors.ink}"
    textColor: "{colors.pearl}"
  bag-button:
    backgroundColor: "transparent"
    textColor: "{colors.ink}"
    rounded: "{rounded.square}"
    size: "48px"
  text-link:
    textColor: "{colors.ink}"
    typography: "{typography.label}"
    height: "44px"
  field-underline:
    backgroundColor: "transparent"
    textColor: "{colors.pearl}"
    typography: "{typography.footer}"
    rounded: "{rounded.square}"
    height: "56px"
  header:
    backgroundColor: "{colors.pearl}"
    textColor: "{colors.ink}"
    typography: "{typography.nav}"
    height: "80px"
  footer:
    backgroundColor: "{colors.footer-ink}"
    textColor: "{colors.footer-text}"
    typography: "{typography.footer}"
  video-control:
    backgroundColor: "rgba(248,248,248,.86)"
    textColor: "{colors.ink}"
    typography: "{typography.label}"
    rounded: "{rounded.square}"
    padding: "0 14px"
    height: "44px"
---

# Design System: Caviar of Switzerland

## Overview

**Creative North Star: "The Swiss Atelier"**

Four real formulas are presented as a considered collection of precious objects, made in Zug. The interface is the atelier's bench: one pale pearl surface, ink type, fine rules and square edges, so the photographs (chrome lids, cream, water, stone, skin) carry all of the colour, light and depth. Nothing on the page competes with the object; restraint is the luxury.

The voice is light and quiet. Headings are set in a light, optically sized serif, in sentence case, at a small number of sizes; everything else is a compact, precise Inter. Authority comes from space, alignment and exact detail rather than size, colour or effects. The homepage creates desire through full-bleed photography; the deeper pages (science, heritage, the ritual, journal) use the same world at reading pace, so moving from campaign to evidence never feels like a change of brand.

Motion has one idea, light travelling over polished silver: openings rack into focus and headings are written by a soft band of light. It is decelerating, quick to finish and never hides a photograph or blocks reading.

**Key Characteristics:**
- One light ground (pearl) on every page; the footer is the only dark band.
- Light serif headings in sentence case; compact Inter for everything else; 12px tracked capitals only for short labels and actions.
- Square corners, one-pixel rules, no shadows, no rounded cards.
- Exact, unaltered product photography and the official logo as supplied.
- Evidence presented precisely: product, method, panel and duration with every figure.

## Colors

A near-monochrome pearl-and-ink palette; colour belongs to the photographs.

### Primary
- **Atelier Ink** (ink): headings, primary text on light grounds, the filled primary button, outline-button frames, focus rings on light grounds.
- **Lettering Graphite** (graphite): the official logo's lettering on light grounds (owner-approved graphite version of the mark) and the primary button's hover fill.

### Neutral
- **Pearl** (pearl): the single page ground on every page, the solid header, light text on the footer and on dark openings.
- **Stone Ground** (stone-ground): a rare alternate room ground where a section needs separation by tone rather than by a rule.
- **Champagne** and **Pale Stone** (champagne, pale-stone): quiet supporting surfaces and dividers in older editorial content; never a page ground on new work.
- **Body Graphite** (body-graphite): reading copy.
- **Muted Graphite** (muted-graphite): secondary text, captions, metadata.
- **Warm Accent** (warm-accent): a restrained bronze kept for legacy labels; new work stays monochrome.
- **Chrome** (chrome): decorative metal only (hairline ornaments, large numerals as decoration). **Chrome Numeral** (chrome-numeral) is the deeper chrome to use when a numeral carries meaning, such as ritual step numbers (3.1:1 on pearl, the large-text minimum).
- **Footer Ink** and **Footer Text** (footer-ink, footer-text): the footer band and its link colour; footer headings and the newsletter title use pearl.
- **Rules** (rule, rule-strong, rule-hair): one-pixel dividers; hair for the scrolled header edge, strong for control outlines.

### Named Rules
**The Material Rule.** Depth and colour belong to the photograph; interface colour stays a quiet ground. No saturated interface accent, no gold, no gradients on UI.

**The One Dark Band Rule.** The footer is the only dark interface surface. Dark openings exist only where the photograph or film itself is dark (the /science/ caviar film). Do not add new dark sections or dark cards.

**The Cobalt Rule.** Never pair cobalt or navy with caviar: another caviar-skincare house owns that combination.

## Typography

**Display Font:** Source Serif 4 (Georgia, Times New Roman fallbacks), self-hosted variable file with optical sizing, weight pinned to Light 300 so every serif renders light.
**Body Font:** Inter (system sans fallbacks), weights 400 and 500 only. No Inter Light, no Inter italic.

**Character:** a fine, contemporary book serif for names and statements against a precise Swiss sans; the serif brings the warmth, Inter the exactness. If licensed Suisse Works / Suisse Int'l files are added to `assets/fonts/suisse/`, they replace both automatically.

### Hierarchy
- **H1** (300, 32→48px, 1.05): page titles, hero statements, the closing title, measured figures. Sentence case; a genuine italic only for an `<em>` inside a heading.
- **Section** (300, 22→32px, 1.15): section titles and headings inside reading copy.
- **Title** (300, 18→20px, 1.2): product names, questions, serif prices, serif step numbers.
- **Body** (400, 14px, 1.25): all reading copy and commerce metadata: leads, benefits, prices, sizes, service lines.
- **Prose** (400, 14px, 1.5): long-form running text (journal articles, legal pages, FAQ answers, science and heritage prose), where 1.25 would tire the eye.
- **Footer** (400, 16px): footer links and small print stay at 16px.
- **Label** (500, 12px, 0.14em, capitals): short labels and every action (buttons, text links, footer group titles). Longer phrases are set in sentence case instead.
- **Nav** (400, 12px, 0.14em, capitals): the header navigation (owner, 19 Oct 2026).

Measures: intros cap at 34em, narrow columns 24em, prose up to 40em. Lining numerals for steps, prices and evidence; tabular numerals where figures align.

### Named Rules
**The Six Roles Rule.** Every readable text maps to one role above (set in `assets/css/hx/scale.css`, which loads last on every page). A new style never introduces a new size.

**The Fourteen Rule.** Reading copy is 14px at every width (owner decision, 8 Nov 2026); nothing readable sits below 12px, and capitals are always 12px or larger. Do not raise reading copy to 16px unasked.

**The Two Families Rule.** Only Source Serif 4 and Inter render anywhere on the site, including header, footer, skip link and hand-authored content pages.

## Layout

A single centred column system: content caps at 1440px (wide plates at 1760px) inside a fluid page margin (24px on phones to 80px wide), with a fluid gap (16→32px) on a 12-column grid. Sections share one vertical rhythm (section-y, 88px on phones to 130px wide) with no hairlines between sections; rooms separate by space, not by lines.

Two kinds of space alternate:
- **Plates**: full-bleed photographs or films under the transparent header, the page's h1 and lead composed on the image at the lower left, on a soft pearl wash where the photograph is busy. Every page opens on a plate.
- **Rooms**: pearl grounds with a centred head, intros capped at 34em, and objects or text aligned to the grid. Reading prose stays left-aligned.

Responsive behaviour: phones get one column, a two-column collection and ritual grid, and the menu button; the desktop header appears from 1024px; the collection becomes four across from 1100px; editorial rows go side by side from 900px. Breakpoints in use: 600, 700, 768, 900, 1024, 1100, 1280px. Two-column grids sized in em collapse under 200% text so nothing clips. Pages longer than a screen carry one sticky, left-aligned section bar under the header for their chapters.

The fixed header is 80px at every width; anchors clear it with 8px to spare.

## Elevation & Depth

Flat. The interface has no shadows, no glass and no layered cards; hierarchy comes from space, a change of ground and one-pixel rules. Depth is photographic: real light, chrome reflections, product volume and floor reflections inside the images. The only blur in the system is motion (an opening racking into focus), never a surface treatment.

### Named Rules
**The Flat Surface Rule.** Use space, tone and hairlines for hierarchy; never add decorative elevation, glow or drop shadows to UI.

**The Photograph Is Never Hidden Rule.** No mask, curtain, darkness or fade may gate an image; it is visible the moment it paints. Image motion is limited to a gentle settle inside its own frame (1.04 → 1).

## Shapes

Square everywhere: buttons, fields, image stages, chips and bands have 0 radius. Rules and control outlines are one pixel (drawn as an inset hairline on buttons). Product images are contained, never cropped through the pack; collection stages are 4:5. Editorial photographs keep deliberate rectangular edges. Circles are allowed only where a control or icon is functionally round.

## Components

Every control is **tailored and restrained**: square, ink or hairline, 12px tracked capitals, with a 44px or larger target.

### Buttons
- **Shape:** square (0).
- **Primary:** filled atelier ink with pearl label, 52px high, 14px × 34px padding, label role. Used for the decisive action on a page: Add to bag on product pages, checkout steps.
- **Hover / Focus:** fill eases to lettering graphite (320ms); a trailing arrow moves 4px. Keyboard focus is a 2px ring with 4px offset in ink (pearl on dark grounds); click focus shows no ring.
- **Outline:** transparent with a one-pixel inset ink frame and ink label, 52–54px high; fills ink with a pearl label on hover and keyboard focus. Used for campaign actions over photographs (the hero's "Shop the Complete Ritual", the closing set) so the action never competes with the chrome lids.
- **Bag button:** a 48px square with a one-pixel strong-rule frame and a hairline bag glyph, in the photograph's corner on collection tiles; on desktop "Add to bag" slides out on hover. It is a sibling of the product link (never nested), works as a plain add-to-cart link without JavaScript, and adds in place with it.

### Text links
Label role in ink, 44px tall target, with a trailing arrow where it leads onward; the underline draws in on hover (700ms). Links inside reading copy are underlined in the text colour with an offset underline.

### Inputs / Fields
- **Newsletter field (footer):** an underline-only field, 56px high, 16px pearl text on the footer band, with the Join action set as a text button on the same line and an arrow.
- **Commerce fields (checkout, account):** square, one-pixel rule border on pearl, 16px text, at least 50px high; WooCommerce's native semantics, labels and error feedback are kept.
- **Focus:** the 2px ink ring with 4px offset; errors use text, never colour alone.

### Navigation
- **Header:** 80px. Transparent over the opening plate once its script has run, solid pearl with a hairline bottom edge once scrolled (or without JavaScript). Desktop: five destinations in nav-role capitals on the left, the logo centred, account and bag glyphs on the right. Below 1024px: menu button, logo, bag, in visual and tab order. Over a dark film the logo swaps to the pearl-lettered version and labels turn pearl. The current page's link is underlined.
- **Mobile menu:** a pearl sheet under the header with the five destinations in the section serif, each with a hairline arrow and a rule between, then Help, Account and Contact in body Inter, then the house line (Swiss cross, "Caviar of Switzerland · Zug" in label capitals). It is a disclosure, not a modal: the rest of the page becomes inert, the × toggle stays in the focus loop, Escape closes it, and its accessible name switches Open/Close navigation menu.
- **Section bar:** one sticky, left-aligned bar under the header on long pages (ritual, product, science, heritage, shop), naming the page's chapters; it scrolls horizontally with an edge fade when it does not fit.

### Footer
The single dark band, footer ink with footer-text links at 16px. Top: "The Caviar Circle" newsletter (pearl serif title, one-line promise, underline field, consent line). Then three link groups under 12px capital titles (Customer care, including Help; Explore; The house in Zug with address, email and three social glyphs), which collapse to plus-sign disclosures on phones. Then the service line (Made in Switzerland · Free worldwide shipping · 30-day returns · Secure checkout), hidden on the homepage only because its closing section already states the assurances. It closes on the silver original logo centred above the copyright.

### Collection tile (signature)
A 4:5 photograph of one formula (owner's overhead packshot on its own grey ground and shadow, shown as supplied), the bag button in its corner, then beneath, left-aligned on one inset: ritual step in label capitals, the name in the title serif, benefit, and price · size in body Inter. Step and price rows align across the row. Hover eases the photograph to 1.03 and underlines the name.

### Evidence figure (signature)
A measured result is a serif figure (H1 or section size) beside its finding, always with product, method, panel and duration in body Inter. Only instrument-measured results take a figure; self-reported results are always written as text. A figure is never placed on a face.

### Video opening control
Openings with a looping film (shop, heritage, science) always show a Pause / Play control: a flat square chip in translucent pearl, 44px high, ink label in 12px tracked capitals, top right under the header. Never a pill, never blurred.

### Motion
One engine and one set of tokens on every page: ease `cubic-bezier(.19,1,.22,1)` (state changes use the softer `(.33,0,.2,1)`), 320ms for UI state, 700ms text reveals rising 14px, 800ms heading light, 900ms image settle, 50ms list stagger capped at 150ms. Each opening racks into focus and its h1 is written by the light; each h2 is written by the light as it enters. Reveals are skipped for anything already on screen, deep links, large jumps and keyboard focus moves. Reduced motion shows everything at once with no masks, blur or movement. Scroll-linked effects need `body{overflow-x:clip}`, never `hidden`.

## Do's and Don'ts

### Do:
- **Do** keep one pearl ground per page and let photographs carry colour and depth.
- **Do** set every heading in the light serif, sentence case, at one of the three serif roles.
- **Do** keep reading copy at 14px (1.25, or 1.5 for long-form prose), footer text at 16px, and capitals at 12px or larger.
- **Do** give every interactive control a target of at least 44px and a visible 2px keyboard focus ring.
- **Do** show exact packaging and labels and the official logo as supplied: graphite lettering on light grounds, the silver original on the footer, the pearl-lettered version only over dark film. The red square and white cross never change.
- **Do** label every study figure with its product, method, panel and duration; keep self-reported results as text.
- **Do** use the deeper chrome numeral colour whenever a chrome numeral carries meaning.
- **Do** keep benefits, prices and purchase actions visible on every device; never behind hover.

### Don't:
- **Don't** add new dark backgrounds, gold, rounded cards, shadows, badges or oversized type (owner, 31 Oct 2026): ask first.
- **Don't** introduce a third typeface, Inter Light or Inter italic, or a new text size outside the six roles.
- **Don't** use cobalt or navy with caviar imagery.
- **Don't** redraw or recolour packaging, the wordmark or the Swiss cross, or ship generated imagery as product artwork.
- **Don't** add star ratings, testimonials, press marks, scarcity cues or invented clinical claims.
- **Don't** hide a photograph behind motion, hijack scrolling, or make content depend on hover or JavaScript.
- **Don't** put a decorative kicker or eyebrow above a section heading or h1, or numbers that carry no information. (The ritual step above a product name, and the 01–04 step numerals, are information and stay.)
