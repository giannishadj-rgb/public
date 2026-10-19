> Latest directive, 18 October 2026: match the local homepage to https://staging.caviarofswitzerland.com/. This supersedes earlier creative-direction decisions. Staging public assets and HEAD 367aec9 are the verified implementation reference. See STATE.md and QA.md for the completed synchronization. No independent claim verification was performed during this parity task.

# Caviar of Switzerland homepage

Transform the existing homepage into a distinctive luxury skincare shopping experience. Scope is `/` only; preserve all other pages, navigation, WooCommerce, cart and analytics. No production deployment is authorised.

Audience: internationally minded women typically 40+, valuing Swiss quality, readable information, a simple ritual and understated luxury. Mobile discovery is a primary use case.

Stack: local WordPress/PHP + WooCommerce, `wp-content/themes/cos-child`. No frontend compilation pipeline. Homepage routing/enqueues: `inc/home-2026.php`; document: `page-home-2026.php`; sections: `template-parts/home-2026/`; styles: `assets/css/hx/{system,hero,collection,science,closing}.css`. Shared navigation/footer: existing theme hooks; do not redesign other surfaces.

Product names, prices, sizes and links come from `cos_h26_products()` and live WooCommerce. Preserve dynamic pricing and conditional ritual/separate comparison. Shopping paths are existing `/products/*`, `/shop/`, `/cart/`, `/checkout/`.

Never alter packaging shape, proportions, labels, logos, typography or colours. Existing originals and builders: `../design-sources/home-2026/`; shipped derivatives and provenance records: `assets/images/home-2026/`. No new product artwork was generated in this session.

Canonical brief: user commission 18 October 2026. Prior records: root `PRODUCT.md`, `DESIGN.md`, `CLAIMS-REGISTER.md` and `output/luxury-reopened-20261017/`. These already contain substantial work: do not restart discovery. Existing-source claims are not equivalent to independent study validation. Brand materials: supplied Dropbox folder in SOURCES.md.

Required: factual copy, authentic products, mobile art direction, keyboard/touch accessibility, reduced motion, working shopping routes, real browser screenshots, independent visual/commercial review and honest benchmark results. Keep this directory compact. Resume by reading STATE.md only, then targeted files.

Mandatory source repair: inherited flat masters FAIL label/geometry fidelity and must never return to homepage. Current original campaign sources/derivatives independently hash-audited; see product-fidelity-audit.md. No generative packaging or unsupported clinical/retailer claims.

Current responsive hero uses source-direct counter photography on phone/wide desktop, with media-matched preloads and a conditional-picture helper to avoid unused tablet downloads. See compact DESIGN/STATE for accepted breakpoints; do not replace authenticated assets with inherited altered flat masters.
