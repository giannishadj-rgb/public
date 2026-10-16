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
    fontFamily: "Unna, Georgia, serif"
    fontSize: "clamp(2.75rem, 1.73rem + 4.19vw, 5.5rem)"
    fontWeight: 400
    lineHeight: 1
    letterSpacing: "-0.018em"
  heading:
    fontFamily: "Unna, Georgia, serif"
    fontSize: "clamp(2.5rem, 1.65rem + 3.4vw, 4.5rem)"
    fontWeight: 400
    lineHeight: 1.06
    letterSpacing: "-0.012em"
  title:
    fontFamily: "Unna, Georgia, serif"
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

Four real formulas are presented as a considered collection of precious objects. Warm light, chrome, cream, water and stone give the photography material presence; the interface supplies quiet structure and clear decisions. Upright Unna, selective genuine italic and readable Inter establish a restrained editorial voice.

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

**Display Font:** Unna, with Georgia and serif fallbacks. Local regular and italic faces use their actual weight (400).

**Body Font:** Inter, with system sans fallbacks. Reading text and editorial ledes use regular weight (400); actions use medium (500).

The frontmatter records shared homepage roles. Use genuine italic selectively within a heading; keep long reading passages in Inter and sentence case. Use lining numerals for product steps and evidence.

The campaign hero has an observed composition-specific title: 44px on phones (40px below 360px), 60px on portrait tablets, and 54–88px on landscape desktop, with a 1.02 line height. Editorial chapter titles use 44–80px, chapter headings 32–44px, and body copy 17px/1.7. These are surface variants, not replacement global scales.

**The Reading Floor Rule.** Main reading copy stays at least 16px; purchase actions stay at least 15px. The 14px tier is reserved for secondary metadata, credits and supporting navigation details.

## Layout

Use open grids and the shared fluid gutter. The homepage content width is 1440px plus its outer gutters; editorial introductions and photographs cap at 1440px, chapters at 1280px. Body measures are usually 24–34em; chapter prose may extend to 40em. Shared sections use the compact section token; intentional full photographic fields establish their own height.

Collection objects form two columns on phones and four from 900px. Each object keeps its name, benefit, size, dynamic price and action visible. Ritual steps form one column, two from 640px and four from 1100px. Science pillars become three columns from 700px, while measured results become two and self-reported findings remain separate. At 900px, provenance uses adjacent copy and photography. Editorial chapter and ritual rows stack below 700px.

The hero uses a full photographic field: centred copy and a lower action on phones, left copy and adjacent product space from 768px when landscape. Minimum hero heights are 740px on phones and 680px on landscape desktop, with viewport height respected. Fixed navigation is 80px desktop and 64px below 1024px. Section anchors clear it with additional space.

## Elevation & Depth

UI stays flat: no card or button shadows. Separate surfaces with space, a tonal change or a fine rule. Real photographic light, chrome reflections and product volume provide depth. Product images retain their own authentic backgrounds; editorial photographs can have deliberate rectangular edges.

**The Flat Surface Rule.** Use space, tone and hairlines to establish hierarchy; do not add decorative elevation.

## Shapes

Buttons, fields, image stages and editorial bands use square corners. Rules are one pixel. Homepage collection objects are contained rather than cropped: stages are 2:3 on phones and 3:4 desktop. Ritual photography uses 4:5, moving to 3:4 desktop. Meaningful circular controls or iconography may retain their functional shape.

## Components

**Primary button:** graphite with pearl text, square, at least 52px high. The hero uses 24px horizontal padding on phones and 28px desktop with a 54px desktop height. Shared buttons use the frontmatter padding. Hover gently changes the fill; the arrow moves 4px. The former inset decorative frame is disabled.

**Text link:** a visible underline or resting hairline, comfortable 48px height and a trailing arrow where useful. Hover strengthens the underline. Keyboard focus remains visible, using a two-pixel outline with four-pixel offset; specific surfaces use three to seven pixels of offset.

**Collection object:** an unboxed, linked composition containing an accurate packshot, numbered ritual verb, serif name, visible benefit, size, dynamic price and underlined action. No overlay hides purchase information. Desktop hover raises the homepage product by 6px; shop cards remain still.

**Evidence:** product-first attribution with a serif measured figure, finding, method, panel and duration. Homepage figures use 56px; self-reported figures use 36px and a separate ruled region. Editorial study figures use 64px and reported figures 40px. Never imply a complete-ritual study.

**Ritual step:** image, number and verb, followed by instructions that are always visible. The image may scale gently to 1.025 on hover. Content does not depend on hover.

**Fields:** pearl fill, square border, readable 16px input and a 50–52px minimum field height. Newsletter fields use a restrained warm border and an adjacent graphite action; keep visible status and accessible labels. Checkout and account retain their native WooCommerce semantics and feedback.

**Navigation and footer:** supplied logo with no filter or redraw; warm ivory navigation, sentence-case links and graphite utility icons. Mobile navigation uses a readable serif list. The footer continues the light material world with warm linen, clear service links and the same field/button vocabulary.

Motion is limited to a single one-second hero entrance from an already visible state (12px rise, opacity 0.86 to 1), restrained control transitions and optional hover image response. The motion script reveals existing hooks immediately and removes scroll gating. Content remains visible without JavaScript. Reduced motion removes the authored entrance and transitions; use native scrolling.

## Do's and Don'ts

### Do:
- **Do** preserve exact products, labels, pack proportions and the supplied official mark.
- **Do** keep benefits and purchase information visible on every device.
- **Do** scope every study result to its actual product, method, panel and duration.
- **Do** keep reading copy at least 16px and meaningful touch targets at least 44px.
- **Do** keep each shipping raster traceable to its source and transformation in its adjacent provenance sidecar.
- **Do** use original hero artwork through proportional resizing and format conversion only.

### Don't:
- **Don't** ship generated direction studies as product artwork or treat their invented packs as approved assets.
- **Don't** redraw packaging, wordmarks or the Swiss cross, or recolor the official logo.
- **Don't** add decorative shadows, rounded cards, cobalt styling or theatrical interface effects.
- **Don't** add invented reviews, press marks, scarcity, clinical claims or facility provenance.
- **Don't** hide explanations behind hover, scroll-gate content or introduce compulsory parallax.
