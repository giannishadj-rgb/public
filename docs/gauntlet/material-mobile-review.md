# Material opening mobile review — 18 October 2026

Focused rendered crop, native keyboard and selector regression review of final five-section opening. Root separately runs seven-width axe/technical checks; these were not duplicated. No source edits.

## Observations

- Inspected decoded opening screenshots at360/390/430/768/1024/1440/1920. Portrait retains both faces at every width;1920 crops hair/background, not facial features. Mobile caviar strip remains visibly recognizable as glossy pearls despite its narrow28% width. Tablet and desktop wide macro has clean edges and no unusual stretching.
- Mobile opening communicates “Swiss-made caviar skincare”, skin benefit, four full-size formulas, €450 and the four named product types. Offer is understandable without packshots. This is a usability judgment, not evidence of improved sales or customer response.
- Commercial tradeoff: first actual product picture begins at document y1347px on390×844, about1.6 screens down. Removing opening packshots delays packaging-led desire for unfamiliar visitors. Clear direct links and the collection anchor mitigate this. Optional next refinement: reduce mobile material/provenance vertical budget while preserving the two authentic images.
- Native hero keyboard order is primaryCTA→collectionCTA→four product links→Why caviar. Visible focus outlines present. Actual Enter activation on Serum opens existing `/products/advanced-repair-serum/` with correct h1. Collection anchor activated by Enter lands collection heading at y164px, clear of61px sticky header.
- Selector regression at360:4→2→3→1, Enter,Space,next, native rail scroll and actual emulated touch swipe synchronize exactly one pressed selector and the live count. All selector targets78×52px/font15px. No-JS hides nonworking controls and retains all four links/native horizontal rail.

## Actionable polish

- **P2,360 only:** Eye cream and24h cream wrap to two lines; center-aligned formula content places the first two verb baselines about7px lower than the last two. Smallest fix: align content to start with consistent top padding, or reserve equal two-line type height. At390+ all four types remain one line. No clipped text or blocked links.

Evidence: `material-mobile-results.json`, seven `screenshots/material-qa-{width}.png`, updated `selectors-technical-results.json`. Hero screenshots decoded only the relevant eager opening images; existing latest full-page captures were untouched. No commerce repeated.

## Final four-section revision — focused acceptance

Supersedes the earlier no-packshot opening observations and text-index issue: hero now shows the actual four-product set beside the purchase offer; text index removed. Root independently reran seven-width automated checks; this pass covered changed visual details without duplicating that suite.

- Decoded real renders at360/390: full-width skin proposition,160px set, primary action and four-full-size/€450 inclusion all fit with no clipping or document overflow. Actual packaging is visible before scrolling into the portrait, resolving the earlier1.6-screen product-discovery concern. At360 secondary “Explore formulas” wraps to two lines; its44px target remains usable and separate from products and price.
- Closing decoded real renders at600/768/1024: complete jar, chrome lid, cross and label remain visible. Offer never overlaps packaging. At600 the primaryCTA wraps to two lines inside its own column, with approximately32px separation from image column. At768 and1024 primaryCTA remains a single line. Image caption is clear of offer/actions.
- Closing keyboard sequence verified with visible focus: Shop the Complete Ritual→Explore the ritual, step by step→Free worldwide shipping→30-day returns. Heights52/44/44/44px.
- Collection source attribution is a44px link. Accessible name: “Michele Elyzabeth, LATF USA, 2022 (opens in a new tab)”. Real external article URL is exposed; target_blank andnoopener/noreferrer present. Quote clearly refers to24h Regeneration Cream.

No blocking visual or interaction defects found. Remaining360 secondary-link wrapping is a minor layout tradeoff, not a clipping failure. Evidence: `final-detail-results.json`, `screenshots/final-detail-qa-{360,390,600,768,1024}.png`; existing latest full screenshots untouched. No source edits or repeated commerce checks.

## Final authentic-source image replacement — supersedes previous image acceptance

The earlier `hxz-set`, flat `collection-object-set` and open-cream image observations are no longer acceptance evidence for current product imagery. Root's source audit replaced them with supplied raw packaging photographs.

Fresh decoded real renders inspected at360/390/430/600/768/1024/1440/1920:

- Hero source `auth-ritual-spoon`: original four products and spoon fit the160px mobile picture. No jar, lid, bottle or spoon clipping. The14px four-full-size label and26px €450 figure remain separate, readable and clear of the purchase action. At360 the collection link wraps, but neither text nor hit area overlaps the product photograph.
- Closing source `auth-care-on-ice`: all three photographed products remain complete at every inspected width, including the entire jar/base/lid and both bottle caps.1920 wide crop preserves all packaging;600 narrow panel likewise preserves all three. No offer/image collision. Caption explicitly names serum, eye cream and24h cream; adjacent offer explicitly includes cleanser and all four full-size formulas, avoiding an implication that the photograph shows all four.
- Loaded source URLs recorded for actual `auth-pack-*` collection images, `auth-ritual-spoon` hero and `auth-care-on-ice` closing. This pass validates rendered crop/layout; root owns source provenance comparison.
- Red hero purchase button retains a visible2px keyboard focus outline and light text. No horizontal document overflow in the eight focused widths. Root separately runs current seven-width axe verification.

No unresolved presentation defects found in these replacement assets. Evidence: `authentic-detail-results.json`, `screenshots/authentic-hero-qa-{360,390,430}.png`, `screenshots/authentic-closing-qa-{360,390,430,600,768,1024,1440,1920}.png`. No source edits or repeated commerce checks.

## Final native campaign delta — current acceptance

Supersedes the three-care-on-ice closing above. Current hero uses `auth-ritual-spoon-original` from native8551×8593 source; closing uses `auth-ritual-nocturne` from native5549×3700 source and now shows all four products plus spoon.

Fresh decoded crop/layout checks at360/390/430/600/768/900/1024/1440/1920: no document overflow. Hero group remains whole with all four packages and spoon visible; mobile14px full-size label and26px €450 price remain separate from the redCTA. Focused heroCTA retains visible2px outline.

Closing actual renders inspected at390/768/900/1024/1440, with whole-composition geometry verified at remaining widths. Below900 photograph precedes the offer; at900+42/58 split keeps all four bottles/jar, lids/caps and spoon inside their photograph, with no cropping or offer collision.900 and1024 retain intentional black space above/below the full photograph; source is contained, not stretched. Photo and four-full-size offer now describe the same complete set. No checkout/order action taken.

No actionable defects found. Updated evidence overwrites `authentic-detail-results.json` and corresponding `authentic-hero-qa-*`/`authentic-closing-qa-*` QA captures;900 crop added. Sticky header can overlay the very top of long element screenshots when Playwright scrolls to capture; computed contain geometry confirms packaging is not cropped by its image container.

## Mobile product-sheet refinement — current focused delta

Reviewed actual `type-390-0..3.png` viewport captures plus live360/430 mobile and768 tablet.82vw mobile slides produce a materially larger selected product presentation than the prior60vw rail: at390 the slide measure increases234→319.8px and photograph cap increases234→300px.28px product names,14px step and16px benefit/price create a clearer reading hierarchy. This is a demonstrated presentation/readability improvement, not a measured sales result.

At360/430, first and last slides remain whole, product names fit, count4 endpoint is correct, and native touch swipe from1 synchronizes Serum/count2. Direct selector keyboard activation and visible2px focus pass; selector target78×52px.768 uses a legible two-column layout with complete pictures and prices. No document overflow.

Viewport screenshot captures preserve rail position:360 final917→917px and430 final1076→1076px before/after. These checks avoid known full-page/element screenshot viewport resizing artifacts.

MinorP2: supplied390 Serum/Eye screenshot states show a tiny clipped fragment of the adjacent card's text at far right below the photo. This slightly contradicts the intended image-only preview; mask or hide nonselected details after the photo while preserving accessible links. It does not obstruct the selected card or controls. No blocking defects found.

Evidence: `mobile-sheet-results.json`, `screenshots/sheet-qa-360-{0,3}.png`, `sheet-qa-430-{0,3}.png`, `sheet-qa-768.png`, existing `type-390-{0..3}.png`. No source edits or checkout actions.

### Product-sheet director fix verified

Reinspected regenerated390 Serum/Eye/Cream states and fresh live360/430 all-four-state viewport captures. Adjacent-text fragments are removed: neighboring slides now preview image only, while every selected name/benefit remains readable. Final cream photograph aligns consistently with the first slide after trailing-padding change. The earlierP2 preview-text issue is resolved.

Live360/430 count4 endpoint, native swipe→Serum/count2, keyboard selector focus and no-overflow checks still pass. Viewport captures preserve scroll position before/after. Updated `mobile-sheet-results.json` and `sheet-qa-{360,430}-{0..3}.png` provide evidence. No unresolved defects from this focused mobile-sheet review.

## Mobile full-campaign trial — independent verdict

**Keep, after the small secondary-link overlap fix.** Fresh real viewport renders at320/360/390/430/599 show a stronger initial product presentation than the small white pack group: all four original packages are clearly visible, offer explicitly says4 full-size formulas/€450, and primary action is legible on the photograph's original empty upper field. No product body or label is covered by heading, lead, primaryCTA or price. White keyboard focus outline is conspicuous. No document overflow.599 boundary remains composed; existing600+ composition was outside this focused delta.

Three specific findings:

1. **P1 small-screen overlap:** at320 and360, the “View collection” arrow and long underline cross the serum's chrome cap/body; the words remain clear. Image evidence `campaign-mobile-focus-320.png` and `campaign-mobile-focus-360.png`. Smallest fix: hide decorative arrow and let link shrink naturally at<=374px. Do not move/alter product photograph.
2. **P2 readability:**320 uses12px “4 full-size formulas”. This is small relative to the40+ audience and essential offer qualification;14px should fit if secondary link is shortened. It remains readable in the captured render, so this is a refinement rather than a blocker.
3. **Composition tradeoff:** campaign stage→policy bar→second portrait/caviar field is a long sequence before collection controls. Product desirability improves because real packaging now leads, but direct choice remains lower down. Preserve direct collection action and avoid adding further opening content.

The52px white policy bar is clear and avoids previous low contrast over photography. Product crop/fidelity checks here assess whole authentic photographs in browser; source authenticity is already approved by root. No automatic9/10 or sales improvement claim is inferred.

Evidence: `campaign-mobile-review-results.json`, `campaign-mobile-qa-{320,360,390,430,599}.png`, focusedCTA captures at320/360. No source edits or commerce actions.

### Mobile campaign acceptance after narrow-link fix

Fresh320/360/390/430/599 real renders confirm the cap occlusion is closed. At320/360 decorative arrow is absent; collection link now measures106×44px (x24–130), including a visible2px light focus outline clear of serum packaging. PrimaryCTA focus remains obvious. All four packages remain whole; heading, benefit, price and both actions avoid product labels/caps/bodies. No overflow or new crop issue. White52px policy bar stays clear.

Verdict: **keep the mobile campaign**. NoP0/P1 remains from this focused review. The32012px full-size qualifier and opening length remain minor readability/composition tradeoffs, already documented. Root independently reports conservative actual-text photograph contrast measurements above5:1; this reviewer did not duplicate that measurement.

Updated `campaign-mobile-review-results.json` and320/360 focused screenshots reflect the fix. No source edits, checkout or order.
