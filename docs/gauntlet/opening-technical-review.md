# Final opening technical review — 18 October 2026

Tested final campaign-first opening, four native product links, ritual cream photograph and live €450 inclusion line. No implementation edits. Seven-width runner completed after the tablet680px height adjustment.

- Chromium360/390/430/768/1024/1440/1920: zero main axe WCAG2A/AA and2.1AA violations, document overflow, broken images, console errors, page exceptions or failed asset responses. No non-input layout-shift events observed during initial load, font readiness and full lazy-image scroll in these local runs; this is lab evidence, not field CLS.
- H1 remains precisely two lines at every width. Native hero links: primary complete-ritual route, collection anchor and four existing product routes. All four product accessible names include number, verb, full product name and size, confirmed in Chromium and WebKit accessibility snapshots.
- Hero targets: minimum width72px and minimum height44px across all seven sizes; formula pictures are contained inside their hit areas. Reduced-motion mode has no active hero animation. Existing explicit focus styles remain present.
- Portrait: inspected390px mobile and1440px desktop captures. Both faces remain visible. An initial768×1024 split crop clipped the first model's mouth; the final680px tablet panel fixes this, verified in `opening-qa-768.png` with both complete faces visible.
- Ritual cream photograph: inspected decoded390/1440 images. Whole jar, product label, original chrome lid and Swiss cross remain visible without clipping. These checks assess presentation geometry, not pixel-level provenance.
- Mobile WebKit390: no document overflow; accessibility snapshot contains both CTAs, live inclusion line and all four correctly named product links.

No unresolved technical defects found. Checkout smoke was not repeated because purchase controls and destinations were unchanged; prior successful test remains relevant. No Lighthouse score is inferred from these checks.

Evidence: `opening-technical-results.json`; decoded-image captures `screenshots/opening-qa-390.png`, `opening-qa-1440.png`, `opening-qa-768.png`, `opening-cream-qa-390.png`, `opening-cream-qa-1440.png`. Runner: `qa-opening.cjs`. Existing latest screenshots were not overwritten.
