# COS Test Explorer Scenarios

This suite is built for VS Code Test Explorer through the Playwright runner.

## Coverage Map

- Public routes: home, shop, ritual, science, heritage, contact, journal, cart, account, checkout redirect, branded 404.
- Navigation: desktop header, utility links, mobile menu open/close, focus trap, skip link, horizontal overflow.
- Commerce: shop product grid, empty cart, add to bag, quantity, remove, checkout fields, product detail pages.
- Account: logged-out sign-in, registration tabs, invalid login notice, required consent/password metadata.
- SEO and integrity: title/meta/OG tags, single viewport meta, JSON-LD breadcrumbs, same-origin asset checks, browser errors.
- Browser state: analytics dataLayer, local cart helper, WooCommerce bag-count authority.
- Accessibility: automated axe scans for serious/critical WCAG issues on key routes and open mobile navigation.
- UI/UX responsiveness: mobile scroll locking, primary tap-target sizing, contact form validation, sticky-header skip navigation.
- Visual regression: viewport snapshots for key storefront states, product detail, non-empty cart, and open mobile menu.

## Run

Start the Local WordPress site first, then run:

```powershell
npm.cmd test
```

Use a different URL when needed:

```powershell
$env:TEST_BASE_URL = "http://caviarofswitzerland.local/"
npm.cmd test
```

Update visual baselines after intentional design changes:

```powershell
npm.cmd run test:update-snapshots
```

In VS Code, install/enable the recommended `ms-playwright.playwright` extension so these scenarios appear in the Testing/Test Explorer view.

## iPhone WebKit smoke checks

The `mobile-webkit` project uses the iPhone 13 preset and runs the 38 route,
navigation, commerce and accessibility scenarios in files 01, 02, 03 and 06.
It does not create new visual-regression baselines. Start Local WP, then run:

```powershell
npx.cmd playwright install webkit
npx.cmd playwright test --project=mobile-webkit
```

This is WebKit engine coverage on Windows, not a physical iPhone Safari check.
The October 2026 redesign was checked with these focused suites; the entire
repository suite and historical visual baselines were not revalidated.
