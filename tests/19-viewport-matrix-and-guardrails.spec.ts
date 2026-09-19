import { expect, test } from '@playwright/test';
import {
  collectSevereBrowserErrors,
  criticalRoutes,
  expectHealthyPageShell,
  expectNoHorizontalOverflow,
  gotoAndCheck
} from './helpers/cos-helpers';

/**
 * Phase 10 guardrails from the CRO/conversion-funnel brief that weren't yet
 * covered by a permanent test: the full seven-width viewport matrix
 * (320/375/390/430/768/1024/1440), prefers-reduced-motion behavior, a CLS
 * regression guard for the two sticky/toggling elements added this pass
 * (PDP sticky Add to Bag bar, checkout coupon toggle), and an explicit,
 * dedicated console-error sweep across every significant page — distinct
 * from gotoAndCheck's per-test error check, which exists but is scattered
 * across many files rather than being one discoverable guardrail.
 */

const VIEWPORT_WIDTHS = [320, 375, 390, 430, 768, 1024, 1440] as const;
const VIEWPORT_HEIGHT = 900;

const STATIC_PAGES = [
  { name: 'home', path: '/' },
  { name: 'shop', path: '/shop/' },
  { name: 'product', path: '/products/advanced-repair-serum/' }
] as const;

async function addRitualSerumToBag(page: import('@playwright/test').Page) {
  await gotoAndCheck(page, '/products/advanced-repair-serum/');
  await page.locator('#cos-pdp-atc-btn').click();
  await page.waitForLoadState('networkidle');
}

test.describe('Viewport matrix (320/375/390/430/768/1024/1440): overflow and shell health', () => {
  for (const width of VIEWPORT_WIDTHS) {
    for (const pageDef of STATIC_PAGES) {
      test(`${pageDef.name} at ${width}px has no horizontal overflow and a healthy shell`, async ({ page }) => {
        await page.setViewportSize({ width, height: VIEWPORT_HEIGHT });
        await gotoAndCheck(page, pageDef.path);
        await expectHealthyPageShell(page);
        await expectNoHorizontalOverflow(page);
      });
    }
  }
});

test.describe('Viewport matrix: cart and checkout at the two widths not covered elsewhere (320, 430)', () => {
  // 390/768/1024/1440 are already covered for cart/checkout by
  // 08-visual-regression.spec.ts and 18-cro-funnel.spec.ts — only 320 and
  // 430 are new coverage here.
  for (const width of [320, 430] as const) {
    test(`cart at ${width}px has no horizontal overflow`, async ({ page }) => {
      await page.setViewportSize({ width, height: VIEWPORT_HEIGHT });
      await addRitualSerumToBag(page);
      await gotoAndCheck(page, '/cart/');
      await expectNoHorizontalOverflow(page);
    });

    test(`checkout at ${width}px has no horizontal overflow`, async ({ page }) => {
      await page.setViewportSize({ width, height: VIEWPORT_HEIGHT });
      await addRitualSerumToBag(page);
      await gotoAndCheck(page, '/checkout/');
      await expectNoHorizontalOverflow(page);
    });
  }
});

test.describe('prefers-reduced-motion guardrail', () => {
  test('the PDP sticky bar\'s CSS transition collapses under reduced motion', async ({ page }) => {
    await page.emulateMedia({ reducedMotion: 'reduce' });
    await page.setViewportSize({ width: 390, height: 844 });
    await gotoAndCheck(page, '/products/advanced-repair-serum/');

    const duration = await page.locator('#cos-pdp-sticky-atc').evaluate(el => getComputedStyle(el).transitionDuration);
    // The sitewide reduced-motion reset forces this to .01ms — parse and
    // assert well under a real 250ms transition rather than matching the
    // exact string, so this doesn't break if the reset value is retuned.
    expect(parseFloat(duration) * 1000, `transition-duration was ${duration}`).toBeLessThan(10);
  });

  test('without reduced motion requested, the sticky bar keeps its real transition', async ({ page }) => {
    // Regression guard for the opposite mistake: fx.off / the CSS reset
    // must be conditional, not applied unconditionally to everyone.
    await page.setViewportSize({ width: 390, height: 844 });
    await gotoAndCheck(page, '/products/advanced-repair-serum/');

    const duration = await page.locator('#cos-pdp-sticky-atc').evaluate(el => getComputedStyle(el).transitionDuration);
    expect(parseFloat(duration) * 1000).toBeGreaterThan(100);
  });

  test('the checkout coupon toggle reaches its final state immediately under reduced motion', async ({ page }) => {
    // Real bug found and fixed while building this guardrail: WooCommerce's
    // own checkout.js animates the coupon form via jQuery .slideToggle(400),
    // which is a JS-driven height interpolation that never consults CSS —
    // the sitewide prefers-reduced-motion CSS reset has zero effect on it.
    // Fixed via inc/core.php's cos_child_reduced_motion_jquery_bridge(),
    // which sets the documented jQuery.fx.off global switch.
    await page.emulateMedia({ reducedMotion: 'reduce' });
    await addRitualSerumToBag(page);
    await gotoAndCheck(page, '/checkout/');

    const fxOff = await page.evaluate(() => (window as any).jQuery?.fx?.off);
    expect(fxOff, 'jQuery.fx.off should be set under reduced motion').toBe(true);

    const form = page.locator('form.checkout_coupon');
    await page.locator('.showcoupon').click();
    // One frame is enough time for an instant (non-animated) state change;
    // a real 400ms jQuery slide would still be mid-animation here.
    await page.waitForTimeout(32);
    await expect(form).toBeVisible();
    const height = await form.evaluate(el => el.getBoundingClientRect().height);
    expect(height, 'Coupon form should already be at its full height, not mid-slide').toBeGreaterThan(50);
  });

  test('without reduced motion requested, the coupon toggle still animates smoothly', async ({ page }) => {
    await addRitualSerumToBag(page);
    await gotoAndCheck(page, '/checkout/');

    const fxOff = await page.evaluate(() => (window as any).jQuery?.fx?.off);
    expect(fxOff, 'jQuery.fx.off should be untouched for users who did not request reduced motion').not.toBe(true);
  });
});

test.describe('CLS regression guard: sticky/toggling elements never shift page content', () => {
  async function measureCls(page: import('@playwright/test').Page, trigger: () => Promise<void>) {
    await page.evaluate(() => {
      (window as any).__clsEntries = [];
      const po = new PerformanceObserver(list => {
        for (const entry of list.getEntries() as any[]) {
          if (!entry.hadRecentInput) (window as any).__clsEntries.push(entry.value);
        }
      });
      po.observe({ type: 'layout-shift', buffered: true });
      (window as any).__clsObserver = po;
    });
    await trigger();
    await page.waitForTimeout(400);
    return page.evaluate(() => ((window as any).__clsEntries as number[]).reduce((a, b) => a + b, 0));
  }

  test('PDP sticky bar sliding into view causes no measurable layout shift', async ({ page, browserName }) => {
    test.skip(browserName !== 'chromium', 'Layout Instability API is Chromium-only');
    await page.setViewportSize({ width: 390, height: 844 });
    await gotoAndCheck(page, '/products/advanced-repair-serum/');

    const cls = await measureCls(page, async () => {
      await page.locator('#cos-pdp-atc-btn').scrollIntoViewIfNeeded();
      await page.locator('.cos-pdp-desc-section').scrollIntoViewIfNeeded();
    });
    expect(cls, 'Sticky bar is position:fixed and must not shift in-flow content').toBeLessThan(0.02);
  });

  test('checkout coupon toggle expanding never moves content above it', async ({ page, browserName }) => {
    test.skip(browserName !== 'chromium', 'Layout Instability API is Chromium-only');
    await addRitualSerumToBag(page);
    await gotoAndCheck(page, '/checkout/');
    // Let WooCommerce's own post-load order-review AJAX recalculation (and
    // any other late-settling content unrelated to the coupon toggle) finish
    // before measuring, so this test isolates the toggle's own effect
    // rather than pre-existing checkout-page settling noise — out of scope
    // for a guard specifically about the elements added this pass.
    await page.waitForTimeout(1000);

    // The coupon form legitimately pushes content BELOW it down the page
    // when it expands — that's the correct, expected result of the user's
    // own click, not a CLS regression, and the Layout Shift API doesn't
    // distinguish "caused by this click" from "coincidental." The
    // meaningful invariant is that content ABOVE/outside the toggle's own
    // local flow — the page header — never moves because of it.
    const headerBefore = await page.locator('#cos-nav-wrap').evaluate(el => el.getBoundingClientRect().top);
    await page.locator('.showcoupon').click();
    await page.waitForTimeout(500);
    const headerAfter = await page.locator('#cos-nav-wrap').evaluate(el => el.getBoundingClientRect().top);
    expect(headerAfter, 'Page header should not move when the coupon form expands').toBe(headerBefore);
  });
});

test.describe('Dedicated console-error sweep', () => {
  const errorSweepPages = [
    ...criticalRoutes.map(r => ({ name: r.name, path: r.path })),
    { name: 'product', path: '/products/advanced-repair-serum/' },
    { name: 'bundle product', path: '/products/the-complete-ritual/' }
  ];

  for (const pageDef of errorSweepPages) {
    test(`${pageDef.name} produces zero severe browser errors`, async ({ page }) => {
      const errors = collectSevereBrowserErrors(page);
      try {
        await page.goto(pageDef.path, { waitUntil: 'networkidle' });
        expect(errors.severe, `Severe errors on ${pageDef.path}`).toEqual([]);
      } finally {
        errors.dispose();
      }
    });
  }

  test('cart and checkout with a real item produce zero severe browser errors', async ({ page }) => {
    const errors = collectSevereBrowserErrors(page);
    try {
      await addRitualSerumToBag(page);
      await page.goto('/cart/', { waitUntil: 'networkidle' });
      await page.goto('/checkout/', { waitUntil: 'networkidle' });
      expect(errors.severe, 'Severe errors across the populated cart/checkout flow').toEqual([]);
    } finally {
      errors.dispose();
    }
  });
});
