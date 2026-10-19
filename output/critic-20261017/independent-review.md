# Independent visual and structural critique — 17 October 2026

This review uses actual Playwright Chromium at 1440×950 and Playwright WebKit at 390×844. Screenshots were scrolled through before capture to trigger legitimate lazy loading/reveals. A raw full-page screenshot before scrolling is not a reliable final evaluation because below-fold motion elements are intentionally hidden. No production files were edited by this reviewer.

## Observed official references

- La Prairie: https://www.laprairie.com/en-ch — visited 17 October 2026, full-page reference-laprairie-revealed.png. Campaign hero shows large gold jar held by hands, approximately half-page object composition, minimal copy on opposite side; 32px campaign/collection headings; product sets and isolated white-ground objects; ~three item product rows. Newsletter and commerce utilities are materially denser than the Caviar brief requests. Their presence is not a reason to introduce similar density here.
- Augustinus Bader: https://augustinusbader.com/en-uk — visited same date, reference-bader-hero.png and reference-bader-revealed.png. Full-screen blue product with reflection, concise opposing copy. 48px brand and results headings, 64px science headline. Scientific macro background connects science to visual texture. The blue identity and floating quick-shop elements should not be copied.
- These are observed references, not a universal or statistically validated geometry model of all luxury sites. Dior, Chanel, La Mer and Cle de Peau were not all visually inspected in this pass.

## Baseline geometry

1440px desktop: page content 1290px (75px margins). Collection four ~299px columns and 31.68px gaps; image stage 299×448; title ~30px and aligned through subgrid. Science three ~397px columns and 49px gaps, measured results two ~621px columns. Hero 950px high. Baseline section heights: collection 1063, caviar 1198, science 1247, provenance 1149, ritual 1049, closing 855. Whole page about 8325px including footer. At 390px, no horizontal document overflow. Repeated grids become two columns or a deliberate swipe rail.

## Section comparisons and findings

Hero: The object scale, reflected chrome and copy isolation can sit in the same visual category as the two observed campaigns. Caviar's typographic contrast is more editorial. The silk background is the most familiar luxury-stock cue and risks becoming beige skincare if multiplied elsewhere. Keep that motif restricted to this image. Packaging requires original-source verification: the apparent unit text at the lower rim reads like “1.8 OZ. us 50 g”, so do not assert exact product preservation from a generated campaign without comparing original artwork. Never fix that with another generative pass over the jar. Desktop hero image fills the viewport without a box; mobile art direction retains the object and CTA.

Collection: Stronger restraint than La Prairie's promotional product rows; coherent open-object layout, transparent stages, aligned name/price/action. Side shadows look heavy relative to the elegant page and are a weaker craft detail, especially cleanser. More important: the original phone CSS deliberately hides benefits, which misses the brief and makes the four formulas harder to distinguish. Restore one concise benefit on mobile, keeping a shared row. Two columns remain viable at 390px with 14px benefit text. Price, volume and CTA are readable.

Caviar: Strongest editorial section in the baseline. Large abstract glossy pearls establish ingredient identity faster than paragraphs. Comparable visual authority to Bader's science macro, with its own graphic material. Intro taxonomy adds credibility but can be tertiary; retain only restrained copy below the photograph. No need for additional generic diagram here.

Science: Three verbs, a continuous rule and explicit product-specific proof are substantially better than generic badge cards. The 100% self-reported satisfaction figure is visually separated from the instrument results, which preserves clarity. Mobile is long (~four viewport heights), but type remains readable and the order is logical. Avoid adding motion that hides results on first scroll. A zero-value decorative molecular diagram would weaken this. Validate claims separately against source documentation; this reviewer did not validate study authenticity.

Provenance: Baseline mountain image is a direct brief violation even if artfully photographed; parent builder is replacing it. A Swiss architecture/stone/water composition can have equal image-led weight without another theatrical image. Do not imply a depicted building is the actual laboratory unless verified.

Ritual: Tall photographic rhythm adds human presence and separates it from the object collection. Original desktop four adjacent panels are effective but the heavy lower dark gradient and large vertical tracked labels are comparatively fashion-editorial rather than quiet Swiss product clarity. Mobile has useful step instruction captions. A bare hairline progress track is subtle to the point of being unclear, particularly for older customers. Add an explicit count or accessible previous/next buttons, then verify keyboard and touch. Existing CSS scroll animation is progressive; fallback must remain useful.

Closing: The oversized final statement and calm open field are coherent and distinctive enough to end the story; comparable restraint to brand statements on Bader. The original 855px closing can feel like a long empty tail after a long page. Target approximately 620–720px desktop with the type and single action intentionally positioned, not a generic centered promo. Keep service assurances below action.

Header/footer: Center logo and uncluttered icon group support the luxury tone. Fixed header appears over content in element screenshots; verify real anchors have scroll margin equal to header height. Footer is appropriately functional but a darker visual weight than body; keep it graphite, avoiding expansion into a second campaign.

## Technical checks

Initial baseline was blocked by PHP parse error in inc/product-data.php line 227; parent corrected it and homepage rendered.

Axe WCAG 2 A/AA and WCAG 2.1 AA: zero automated violations at desktop viewport. This does not prove accessibility; keyboard interaction, zoom, screen-reader order, disclosures and contrast over every image state require separate checks.

Playwright WebKit renders the phone layout; this is not a physical iPhone Safari test. No horizontal document overflow at 390px. Screenshot files called baseline were overwritten after the parse fix; revealed captures show actual loaded content. geometry.json captures computed grid details during ongoing parent iteration, so it may differ from initial screenshot dimensions.

## Verdict

Baseline is already a cohesive editorial ecommerce composition with credible spacing and strong caviar imagery. It is not uniformly finished: mountain provenance conflicts with brief, mobile hides the benefits, swipe discovery is insufficiently explicit, and exact hero packaging remains an asset verification dependency. Those are concrete issues; no honest audit can certify “utter perfection” or guarantee blind parity with every major luxury house. Re-evaluate the updated build after these fixes, using the same viewports and real interaction checks.

## Final build re-audit

The builder addressed the structural brief mismatches. This paragraph supersedes earlier findings where marked fixed. Final captures in desktop/mobile-section-0 through -6.png and desktop/mobile-revealed.png now show the updated build. Desktop and mobile baseline captures intentionally do not force lazy images to load.

- Hero: concise science/Zug cue now replaces “instrument measured results” in the first viewport, giving a clearer Swiss/ingredient/ritual identity within seconds. CTA sentence case is quieter. Object dominates and copy remains distinct.
- Collection: FIXED mobile benefit omission. Each of four benefits is visible at 16px; two-column stage and shared rows remain coherent. The heavy side-shadow observation is still a photographic craft preference, not a layout blocker.
- Caviar: retained strong editorial macro; no extra clutter introduced. This is among the most successful sections beside the observed reference ingredient/science art direction.
- Science: readable typographic authority and restrained results layout retained; it is information-dense on mobile but uses a logical hierarchy and legible text. No theatrical diagrams were introduced.
- Provenance: FIXED mountain conflict. New two-column copy/formulation-study image uses cream, chrome and glass, captioned “Formulation study — cream, glass, precision.” It should be understood as illustrative; the source accuracy of jar artwork remains a verification question. The composition has cleaner Swiss restraint than the baseline mountain and compares appropriately with the reference brands' split editorial panels.
- Ritual: FIXED swipe/hidden instruction issue. All four steps are visible in a 2×2 mobile grid (161px columns, 20px column gap and 40px row gap at 390px), four columns desktop. Photos remain the incumbent source, 4:5 crop; instructions always visible. This trades dramatic tall fashion panels for stronger comprehension and a coherent luxury ritual. The text line length is short on mobile but still readable; no overflow.
- Closing/footer: closing is now ~552px at 390px and the footer uses a light stone ground. The smaller contrast jump gives a much more continuous page ending. The large statement remains appropriately prominent.

Final checks: mobile WebKit 390×844 has no broken images after sequential scroll, no page errors, and zero document overflow; 320px also has zero document overflow. Four collection benefits each compute at 16px. Desktop automated axe check still zero WCAG 2 A/AA and 2.1 AA violations. Screenshotting arbitrary section elements can place the fixed navigation across the middle of a tall screenshot; this is a capture artifact, not proof that the header floats halfway down the visible viewport.

Remaining bounded observations: the logo asset in the scrolled header appears comparatively pale on ivory and could be darkened through the correct original dark logo asset (not colour-inverting the brand). Current asset is logo-222w.webp, opacity 1, no CSS filter. Product artwork exactness must be established against the actual product originals; a browser check cannot certify provenance. Chrome jar typography looks unusual at close range but may reflect supplied artwork, so this is a verification request rather than a proven product alteration. No remaining material geometry or mobile navigation blocker was found in this homepage pass. Physical Safari hardware, purchase completion and full-site document/claims review are outside this visual verdict.

Final verdict: coherent, restrained luxury direction with the explicit section-flow and mobile-comprehension defects corrected. It stands credibly beside the two observed reference sites in object scale, whitespace, material texture and editorial pacing. This is a professional visual assessment, not a guarantee of blind-test parity or perfection.
