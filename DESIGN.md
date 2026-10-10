---
name: Caviar of Switzerland
description: Four caviar formulas, one quiet daily ritual, made in Zug.
colors:
  pearl-canvas: "#F2F2EF"
  pearl-band: "#E8E8E5"
  graphite-ink: "#1C1C1C"
  charcoal: "#151515"
  body-graphite: "#3A3A3A"
  muted-graphite: "#5E5E5E"
  obsidian: "#080909"
  obsidian-raised: "#0C0E12"
  footer-blue-black: "#0D1117"
  platinum: "#B4B9BF"
  silver-mist: "#D9DDE1"
  porcelain: "#FAFAF8"
  on-dark-text: "#C7CBD1"
  on-dark-muted: "#9AA0A8"
  swiss-red: "#D52B1E"
typography:
  display:
    fontFamily: "Cormorant Garamond, Georgia, serif"
    fontSize: "2.5rem"
    fontWeight: 300
    lineHeight: 1.06
    letterSpacing: "0"
  headline:
    fontFamily: "Cormorant Garamond, Georgia, serif"
    fontSize: "2rem"
    fontWeight: 300
    lineHeight: 1.2
    letterSpacing: "-0.01em"
  figure:
    fontFamily: "Cormorant Garamond, Georgia, serif"
    fontSize: "3rem"
    fontWeight: 400
    lineHeight: 1
    fontFeature: "lnum, tnum"
  title:
    fontFamily: "Cormorant Garamond, Georgia, serif"
    fontSize: "1.375rem"
    fontWeight: 400
    lineHeight: 1.25
  body:
    fontFamily: "Inter, system-ui, -apple-system, Segoe UI, Arial, sans-serif"
    fontSize: "1rem"
    fontWeight: 300
    lineHeight: 1.65
  small:
    fontFamily: "Inter, system-ui, -apple-system, Segoe UI, Arial, sans-serif"
    fontSize: "0.875rem"
    fontWeight: 400
    lineHeight: 1.5
  label:
    fontFamily: "Inter, system-ui, -apple-system, Segoe UI, Arial, sans-serif"
    fontSize: "0.75rem"
    fontWeight: 500
    lineHeight: 1.5
    letterSpacing: "0.14em"
  action:
    fontFamily: "Inter, system-ui, -apple-system, Segoe UI, Arial, sans-serif"
    fontSize: "0.9375rem"
    fontWeight: 500
    lineHeight: 1.5
    letterSpacing: "0.01em"
rounded:
  none: "0"
  hairline: "2px"
spacing:
  gutter: "22px"
  container: "1360px"
  section-mobile: "72px"
  section-desktop: "128px"
components:
  button-primary:
    backgroundColor: "{colors.graphite-ink}"
    textColor: "{colors.pearl-canvas}"
    typography: "{typography.action}"
    rounded: "{rounded.none}"
    padding: "0 30px"
    height: "54px"
  button-primary-hover:
    backgroundColor: "{colors.body-graphite}"
    textColor: "{colors.pearl-canvas}"
  button-on-dark:
    backgroundColor: "{colors.porcelain}"
    textColor: "{colors.charcoal}"
    typography: "{typography.action}"
    rounded: "{rounded.none}"
    padding: "0 30px"
    height: "54px"
  button-on-dark-hover:
    backgroundColor: "#E4E6E9"
    textColor: "{colors.charcoal}"
  text-link:
    textColor: "{colors.graphite-ink}"
    typography: "{typography.action}"
    height: "44px"
  input-on-dark:
    backgroundColor: "{colors.obsidian-raised}"
    textColor: "{colors.porcelain}"
    rounded: "{rounded.none}"
    padding: "0 16px"
    height: "44px"
  tag-self-reported:
    textColor: "{colors.body-graphite}"
    typography: "{typography.label}"
    rounded: "{rounded.hairline}"
    padding: "1px 8px"
---

# Design System: Caviar of Switzerland

## Overview

**Creative North Star: "The Quiet Ritual"**

The site is a small, unhurried daily ceremony made visible. There are four steps and nothing extra, and every element earns its place by helping her understand or want one of the four formulas. The page is light-led, with a pearl canvas and graphite type. Darkness is punctuation, reserved for photographic moments: the desktop hero, the caviar macro and the footer. Type pairs a fine high-contrast serif for voice with a light sans for reading, and short spaced-caps labels for wayfinding.

Restraint is the method. Elements are spaced generously and set in a narrow palette. The ornaments are the photography of the real objects (chrome caps, mirrored glass, a silver tray, a fingertip of cream) and the ritual numerals 01–04. Precision shows in the details: lining numerals, hairline rules, figures always named with their product, method and panel. Intimacy comes from scale. The interface never shouts, and the objects are photographed close.

**Key Characteristics:**
- Pearl canvas (#F2F2EF) with graphite ink; dark only behind photography.
- Cormorant Garamond 300 for voice, Inter 300/400 for reading, Inter 500 tracked caps for short labels.
- Square corners, hairline rules, no shadows.
- The 01–04 ritual numerals as the page's recurring structural device.
- Real, label-accurate product photography as the primary ornament.

## Colors

A near-achromatic palette: pearl, graphite and platinum, with the Swiss red reserved for the logo.

### Primary
- **Graphite Ink** (graphite-ink): headings, primary text, filled buttons on light surfaces. It is the brand's "black", softer than true black.
- **Pearl Canvas** (pearl-canvas): the page background site-wide and the text on filled graphite buttons.

### Secondary
- **Platinum** (platinum): labels and accents on dark moments, and the newsletter button fill in the footer.
- **Silver Mist** (silver-mist): hover state and focus ring on dark moments.

### Neutral
- **Pearl Band** (pearl-band): a raised band for the clinical evidence section, and image placeholders.
- **Body Graphite** (body-graphite): body copy on pearl (10:1).
- **Muted Graphite** (muted-graphite): supporting copy, eyebrows and captions (6:1 on pearl; the floor for small text).
- **Charcoal** (charcoal): text over light photographic backdrops and inside the evidence band.
- **Obsidian** (obsidian) / **Obsidian Raised** (obsidian-raised): the dark moments (desktop hero, caviar band) and input fills on dark.
- **Footer Blue-Black** (footer-blue-black): the footer, a deliberate blue-black rather than neutral.
- **Porcelain** (porcelain): headings and primary text on dark, and the filled hero button on the dark desktop hero.
- **On-dark Text** (on-dark-text) / **On-dark Muted** (on-dark-muted): body and supporting copy on dark.

### Tertiary
- **Swiss Red** (swiss-red): the cross in the logo only.

**The Punctuation Rule.** Dark surfaces appear only behind photography (desktop hero, caviar macro) and in the footer. A text-only section is never dark.

**The One Red Rule.** Swiss red belongs to the logo's cross. It never colours a button, link, badge or sale price.

**The Achromatic Rule.** No hue enters the palette. Warmth comes from photography, not from tinted UI.

## Typography

**Display Font:** Cormorant Garamond (with Georgia, serif)
**Body Font:** Inter (with system-ui, -apple-system, Segoe UI, Arial)

**Character:** A fine, high-contrast serif carries the brand's voice in light weights. A calm, neutral sans carries everything she needs to read or tap. The serif never sets body copy, and the sans never sets a headline.

### Hierarchy
- **Display** (300, 2.5rem mobile, scaling up on desktop, 1.06): the hero line only, balanced over two lines.
- **Headline** (300, 2rem, 1.2, -0.01em): section headings and the texture statement.
- **Figure** (400, 3rem, 1, lining + tabular numerals): clinical results and the ritual numerals (300 at 3–3.75rem in the collection).
- **Title** (400, 1.375rem): product names on cards, set over two lines.
- **Body** (300, 1rem, 1.65): ledes and reading copy; measure kept around 40–60ch.
- **Small** (400, 0.875rem, 1.5): methods, glosses, cues and captions, in muted graphite.
- **Label** (500, 0.75rem, 0.14em tracking, uppercase): eyebrows, ritual verbs and the footer column heads. Short phrases only, never a sentence.
- **Action** (500, 0.9375rem, 0.01em, sentence case): buttons and text links.

**The Short Caps Rule.** Spaced caps are only for labels of four words or fewer. Anything longer is sentence case.

**The Lining Numeral Rule.** Any numeral set in Cormorant (prices, figures, 01–04) uses lining numerals; old-style figures read as "o1".

## Layout

The homepage is a single column of full-width sections, each with one focus.
- **Container:** content sits in a 1360px container with a 22px mobile gutter that widens on desktop.
- **Breakpoints:** 700px (tablet: collection grid replaces the mobile swipe rail), 900px (two-column editorial splits and side-by-side evidence), 1100px (desktop hero art direction and the dark hero).
- **Section rhythm:** about 72px vertical padding on phones and 128px on desktop. Image-led sections run full-bleed with no padding.
- **Compositions:**
  - Half image, half copy (texture, provenance, final).
  - Full-bleed photograph with copy overlaid on a calm side (desktop hero, caviar, final tray).
  - A centred narrow column for evidence.
- **Collection:** a 4-up grid on desktop and a horizontal swipe rail with 44px pagination dots on phones.
- **Anchors:** in-page anchors carry `scroll-margin-top` to clear the 80px fixed header.

**The Mobile First Screen Rule.** On a 390×844 iPhone, the hero headline, primary action and the four bottles all sit inside the first screen.

## Elevation & Depth

Flat by design. There are no box-shadows anywhere in the system. Depth comes from photography (reflections on mirrored glass, the caviar macro), from tonal shifts between pearl (#F2F2EF), the pearl band (#E8E8E5) and obsidian, and from hairline rules at 10–20% graphite. The fixed header is a 94%-opaque pearl bar with a hairline bottom rule, not a shadow.

**The Flat Surface Rule.** If something needs separating, use space first, a hairline second, and a tone change third. Never a shadow.

## Shapes

Square. Buttons, inputs, images and sections all have 0 radius. The only exceptions are the 2px hairline radius on the "Self-reported" tag, and circles used as meaning (the instrument-measured dot, the rail pagination dots). Rules are 1px hairlines, often short: a 44px rule under each clinical figure, and a rule trailing each ritual numeral to the next step.

## Components

### Buttons
Square and understated.
- **Shape:** square corners (0), 54px tall, 30px horizontal padding; full width in the mobile hero.
- **Primary:**
  - On pearl: a graphite-ink fill with pearl text.
  - On the dark desktop hero: a porcelain fill with charcoal text.
  - Label is sentence case with a trailing arrow (→).
- **Hover / Focus:**
  - Hover lightens the fill one step (graphite to body graphite; porcelain to #E4E6E9) and slides the arrow 5px right over 0.35s.
  - Focus is a 2px outline with a 4px offset, in silver mist on dark and graphite on light.
- **One primary per view:** the hero and the closing section each carry one filled button. Everything else is a text link.

### Text links
- **Style:**
  - Inter 500 in graphite ink, with a trailing arrow and a 44px tap height.
  - The underline is a 1px line drawn in on hover rather than shown at rest.
- **Secondary action** (hero): an always-underlined sentence link with a soft rule colour.

### Inputs / Fields
- **Style:** square, 44px high, a hairline border at 22% on an obsidian-raised fill (footer newsletter). Text stays 16px so iOS never zooms.
- **Focus:** the border shifts to platinum, plus the 2px focus outline.

### Ritual step (signature component)
- **Structure:**
  - A Cormorant 300 numeral (01–04, lining numerals) beside a tracked-caps verb (Cleanse, Treat, Revive, Restore), then a 1px rule running to the next step.
  - Below it, a one-line application cue in small muted graphite.
- **Placement:** heads each collection card.
- **Compact form:** in the closing section the step also names the product and size, as numeral, verb, then name · size.

### Clinical figure (signature component)
- **Stack:** the product name, a Cormorant figure, a 44px hairline, the finding, a "● Instrument-measured" kind line, the panel and the plain-language method.
- **Self-reported results** never take this form. They sit below a rule as a sentence with a "Self-reported" tag.

### Navigation
- **Desktop header:**
  - A fixed 80px pearl bar: tracked-caps links on the left, the centred logo, and help, account and bag icons on the right.
  - It is transparent over the dark hero and becomes pearl on scroll.
- **Mobile:** a menu button, the logo and the bag.

## Do's and Don'ts

### Do:
- **Do** use the pearl canvas (#F2F2EF) for every text-led section; reserve obsidian for photography and the footer.
- **Do** name every clinical figure with its product, method and panel, and mark self-reported results as secondary.
- **Do** keep tap targets at 44px or more and small text at muted graphite (#5E5E5E) or darker.
- **Do** use lining numerals for any Cormorant numeral.
- **Do** photograph the real packaging with accurate labels; product imagery is the ornament.

### Don't:
- **Don't** add shadows, rounded corners or gradients to UI elements.
- **Don't** use Swiss red anywhere except the logo.
- **Don't** set long phrases in spaced caps.
- **Don't** add star ratings, testimonials or press logos.
- **Don't** echo cobalt blue with caviar (another house's signature).
