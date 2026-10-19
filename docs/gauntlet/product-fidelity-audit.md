# Product fidelity audit — P0

18 October2026. Compared actual raw photographs and13October flat masters for all four products. **Verdict: FAIL for the four flat masters and derivatives sourced from them.** This supersedes the earlier limited statement that the cream's visible identity “corresponds”. Similar names/colours are insufficient for the user's strict label/typography fidelity gate.

## Observable discrepancies

- **Cleanser:** raw volume line ends `FL.OZ.us`; flat visibly ends `FL.OZ.US`. Raw product-name lettering is pale grey and heavier; flat is thin black. Flat adds a conspicuous translucent neck blob absent from raw. Flat shows different cap-top/bottle-bottom geometry and packaging presentation; differences are not established as an authentic alternate source photograph.
- **Serum:** raw `Advanced Repair Serum` label is heavier/bold pale-grey type; flat is thin black with different glyph proportions. Wordmark/name/volume spacing and relative scale differ. Raw chrome reflection is broad silver with narrow dark stripe; flat has dominant sharp black bands, with different cap/body proportions. Lighting differences alone would be allowed, but changed typography is not.
- **Eye cream:** raw `Revitalizing Eye Cream` is bold pale-grey; flat is thin dark type. Wordmark/name/volume relative spacing differs. The cap/body/bottom-collar proportions and perspective differ. No original alternate photograph establishes this as the authentic pack.
- **24h cream:** raw volume is `50ml`; flat is `50 mL`/capital L. Raw product-name label is heavier pale-grey; flat is thin black. Raw cap shows a very narrow top ellipse; flat shows broad top face with Swiss cross. The jar/body/cap relationships and wordmark scale differ. Authentic angle changes can be allowed, but the unverified flat master also changes label typography/case.

## Approved replacement source paths

All relative to public directory:

- `../design-sources/home-2026/packshots-2026-10-12/cleanser.jpg` —3543×8268
- `../design-sources/home-2026/packshots-2026-10-12/serum.jpg` —1514×5336
- `../design-sources/home-2026/packshots-2026-10-12/eye.jpg` —1097×4807
- `../design-sources/home-2026/packshots-2026-10-12/cream.jpg` —2687×2972; SHA256 previously matched original Dropbox2020 jar.

Reject `*-flat-2026-10-13.png` for homepage product exports until authentic original provenance is established. Existing collection-object-set and hxz-set originate from rejected flat files; preserving their pixels only preserves the flawed intermediate. Rebuild displayed product imagery from the raw JPGs with proportional crop/resizing/encoding only, retaining actual label pixels. Do not redraw lettering, regenerate packaging or force these photographs into altered perspectives.

## Inspection artifacts

`docs/gauntlet/sources/product-pairs-audit.jpg`, `cleanser-label-audit.jpg`, `serum-label-audit.jpg`, `eye-label-audit.jpg` are inspection crops/contact sheets only. They are not shipping assets. No implementation or source-image edits performed by reviewer. Gate remains open until source-direct replacement derivatives are rendered and reviewed.

## Source-direct campaign replacements

Inspected actual `../design-sources/home-2026/gauntlet-supplied-20261018/brand-ppt-image54.png` (817×711) and `brand-ppt-image5.jpeg` (1853×2471). **PASS as authentic brand-supplied assets with observable packaging consistency.** Both local files byte-identically match their embedded `ppt/media/` entries in supplied March2020 brand deck.

- image54: four original packs plus caviar spoon. Cleanser150ml/5.1FL.OZ.us; pale/heavier product typography consistent with raw sources. No observed label mismatch. SHA256 `62fa5375ae1890661db0522c40c47c6296b32fbc5eed532c4cb10d972870d2b6`.
- image5: eye/serum/24h cream on ice; correct pale-grey product lettering. SHA256 `39765868311267b3d93ef74c997a9075bade49d6b8df7217c6d07aaecea8c350`. Caption/alt must identify three care formulas; do not claim it depicts all four products or implies ice storage instructions.

Direct crop/proportional resizing/encoding from these originals is appropriate. Avoid enlarging image54 beyond its817px native width where possible. This assessment establishes supplied-source lineage and visible package consistency, not physical camera provenance, current batch packaging identity or pixel identity after lossy encoding. Final rendered derivatives still require inspection.

## Final source-direct replacement review — P0 closed

Inspected all four actual `auth-pack-{cleanser,serum,eye,cream}-640.jpg` exports against the raw photographs; reviewed `build-authentic-assets.py`, `authentic-originals.origin.json` and latest rendered390/1440 captures. **PASS: the identified flat-master packaging alterations have been removed from the reviewed homepage.**

- All four exports retain original product body/cap edges, pale-grey heavier name labels, original volume casing and authentic chrome reflections. No product crop/truncation observed. The cream's generous upper whitespace preserves the common baseline; it is presentation spacing, not missing packaging.
- Builder uses crop of surrounding margin, proportional Lanczos scaling, white padding and encoding. No flat-master input, normalisation, matte/redraw, perspective transform or label replacement. All6source hashes and75output hashes match the recorded manifest at review.
- Hero four-product group is source-direct PPTimage54, visibly intact and below native817px size at inspected widths. Closing uses source-direct PPTimage5; three product caps/bodies visible on390/1440. Caption correctly identifies serum/eye/24h cream while offered bundle copy explicitly includes cleanser as the fourth formula. No whole-pack cropping defect observed.
- Rendered collection at1440 shows four complete original packs;390 shows complete cleanser and intentional next-product preview. Other mobile slides were validated by inspecting each exported image; horizontal navigation interactions are outside this narrow authenticity review.
- Small/low-contrast volume and name lettering follows the authentic original photography; do not redraw or darken packaging typography to improve legibility. Adjacent native product names/sizes provide readable shopping information.

Evidence: `screenshots/latest-1440.png`, `screenshots/latest-390.png`; inspection crops `sources/fidelity-render-*-{top,end}.png` and `sources/fidelity-closing-*.png`. This closes the specific replacement fidelity defect, not a claim of physical-camera provenance, manufacturing audit or sitewide packaging compliance. Older rejected assets may remain on disk but must stay unused by the homepage.

## High-resolution original campaign upgrade — PASS

Reviewed `build-chosen-photography.py`, `authentic-campaign.origin.json`, current hero/closing markup and rendered `material-qa-{390,1440}.png` / `authentic-closing-qa-{390,1440,1920}.png`.

- All2source hashes and30derivative hashes match actual files. Script performs complete-composition proportional Lanczos downscale and AVIF/WebP/JPEG encoding only; no crop, flattening, masks, recolouring, relabeling or generated pixels.
- Hero `auth-ritual-spoon-original` directly derives from supplied8551×8593 spoon photograph. Markup uses actual8551:8593 ratio;1200px export is1200×1206 (rounding only). Rendered390/1440 preserve all four packages/caps/bases and spoon. No distortion or label substitution observed.
- Closing `auth-ritual-nocturne` directly derives from supplied5419.JPG (5549×3700). Markup/CSS use actual5549:3700 ratio, image height auto/contain;1200px export1200×800. Rendered390/1440/1920 show all four complete original packages, original dark reflections and spoon. No whole-package crop failure. Native offer copy correctly names four formulas.
- Source-direct raw collection remains the previously passed implementation. This upgrade keeps the identified P0 fidelity defect closed. Packaging typography/reflective imperfections remain as photographed; no assurance of current batch or physical-camera provenance is added.

## Bounded stone-gallery trial — identity PASS

Reviewed current `build-stone-trial.py`, `authentic-stone.origin.json`, all4 `auth-stone-*-640.jpg` exports, and latest390/1440 renders. All4source hashes and48output hashes match actual files. Crop removes only outer photographic surround to4:5; full cap/body/base retained for cleanser, serum, eye and cream. Labels/shapes/metallic reflections match the approved supplied photographs5580/5491/5517a/5438. Pipeline performs proportional scaling and encoding; no repainting, relabeling, backdrop replacement or geometric distortion. Output pixels naturally differ through resampling/encoding; no claim of literal pixel identity is made.

Desktop shows four intact packages; mobile visible cleanser intact, next-slide partial preview is intentional viewport clipping rather than a cropped source package. All other slide packages inspected in exports. No new P0 fidelity issue. This verdict concerns identity only; visual/commercial critics decide whether to retain the trial. Evidence `screenshots/latest-{390,1440}.png` and inspection crops `sources/stone-trial-review-{390,1440}.png`.

## Desktop counter-photo opening — PASS

Reviewed source-direct `build-hero-counter.py` and `authentic-hero-counter.origin.json`:1source/15output hashes match actual files. Complete original DSC_5783 photograph is proportionally downscaled/encoded without retouching, redraw, relabeling or background replacement.

Captured actual hero at1280/1440/1920 (`screenshots/counter-fidelity-*.png`) and inspected each rendered frame. CSS cover at50%80% crops only photographic surround: all four caps, labelled bodies and bases remain visible. At1920 the jar retains bottom/base margin; no package edge reaches the image boundary. Native product proportions and authentic chrome reflections remain intact. No new P0 fidelity defect. Mobile/tablet continue using the previously approved white original group; uniform1.1 display enlargement does not alter packaging geometry.

## Mobile counter-photo art direction — crop PASS, small overlay caveat

Inspected actual `campaign-mobile-qa-{320,360}.png` and `campaign-mobile-{390,430,599}.png`. All four photographed packages retain complete caps, labelled bodies and bases at all5widths. No text covers a product label. Policy links correctly sit in a separate white bar below the photograph. No new product-identity/P0 crop failure.

At320px, the View collection arrow crosses the serum chrome cap; at360px its end touches the cap's left edge. This is a small overlay polish defect, not altered product pixels or obscured labelling. Suggested smallest fix: hide the decorative arrow below360px or shorten its gap. Other copy stays on the original upper negative space. Unchanged source/exports were not rehashed.
