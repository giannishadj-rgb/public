# Campaign revision technical review — 18 October 2026

Changed homepage surface verified with real Chromium and mobile WebKit. Prior successful commerce smoke remains applicable; purchase controls were unchanged and commerce was not repeated.

- **360, 390, 430, 768, 1024, 1440, 1920px:** six expected homepage sections, one h1 followed by section h2s, no document overflow, broken images or corrupt visible text. Zero main axe WCAG 2 A/AA and 2.1 AA violations at every width. No page exceptions or HTTP asset failures observed.
- **Layout stability:** PerformanceObserver attached before document execution, then fonts resolved and whole page scrolled to load lazy assets. No non-input layout-shift entries at any of the seven widths in these local runs (observed CLS 0). This is a lab observation, not field data.
- **Campaign portrait:** AVIF source selected in Chromium; all 720/1080/1440 source candidates present. Desktop image is 480×720, mobile390 is342×456. Both women's faces remain visible in inspected desktop/mobile crops; mobile 3:4 crop trims background/body rather than faces. Source carries descriptive alt text and predictable aspect ratio before load.
- **Science destination:** actual click on “Explore the High-Tech Complex” reaches `/science/high-tech-complex/`, title “The High-Tech Complex — Caviar of Switzerland” and h1 “Engineered for synergy.” No failed navigation.
- **Mobile WebKit390:** revised portrait loads as WebP, 342×456; no document overflow. All four ritual product links present with correct existing destinations.

No actionable technical defects found in this changed-surface pass. Screenshot captures of individual sections include the site's sticky header as a capture artifact; it does not alter the image or content geometry.

Evidence: `campaign-technical-results.json`, `screenshots/campaign-ritual-qa-390.png`, `screenshots/campaign-ritual-qa-1440.png`. Repeatable runner: `qa-campaign.cjs`.

## Final formula-selector CRO revision

Reran the seven-width campaign runner after the direct selector, spacing and mobile ritual typography changes: no overflow, broken images, page exceptions, failed assets, non-input layout shifts or main axe A/AA violations at360/390/430/768/1024/1440/1920. Revised mobile WebKit portrait/ritual smoke and science link remain valid.

At360px, all four selectors fit in a single row: each78×52px with15px labels. Direct selection4→2→3→1 updates the visible formula, live count and exactly one `aria-pressed=true`. Enter selects4; Space selects2. Next from2 selects3. An actual emulated touch swipe from the first formula scrolls to the second and synchronizes count2/Serum pressed. Native rail scrolling back to start synchronizes Cleanser/count1. With JavaScript disabled, selector and arrow groups remain hidden, all four existing product links remain present, and the rail retains native horizontal scrolling.

No actionable defects. Detailed selector/touch/no-JS evidence: `selectors-technical-results.json`. Commerce not repeated because checkout pathways and controls were unchanged.
