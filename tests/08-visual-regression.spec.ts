import { expect, test, type Page } from '@playwright/test';
import {
  addFirstProductToBag,
  discoverProductLinks,
  gotoAndCheck,
  prepareStableVisualState
} from './helpers/cos-helpers';

const visualRoutes = [
  { name: 'home-hero', path: '/' },
  { name: 'shop-storefront', path: '/shop/' },
  { name: 'ritual-overview', path: '/the-ritual/' },
  { name: 'contact-form', path: '/contact/' },
  { name: 'account-entry', path: '/account/' },
  { name: 'cart-empty', path: '/cart/' }
] as const;

async function expectViewportSnapshot(page: Page, name: string) {
  await prepareStableVisualState(page);
  await expect(page).toHaveScreenshot(`${name}.png`, {
    animations: 'disabled',
    caret: 'hide',
    fullPage: false,
    maxDiffPixelRatio: 0.02,
    scale: 'css'
  });
}

test.describe('Visual regression scenarios', () => {
  for (const route of visualRoutes) {
    test(`${route.name} viewport remains visually stable`, async ({ page }) => {
      await gotoAndCheck(page, route.path);
      await expectViewportSnapshot(page, route.name);
    });
  }

  test('product detail viewport remains visually stable', async ({ page }) => {
    const productLinks = await discoverProductLinks(page);
    expect(productLinks.length, 'Shop should link to a product detail page for visual coverage').toBeGreaterThan(0);

    await gotoAndCheck(page, productLinks[0]);
    await expectViewportSnapshot(page, 'product-detail');
  });

  test('non-empty cart viewport remains visually stable', async ({ page }) => {
    await addFirstProductToBag(page);
    await expectViewportSnapshot(page, 'cart-with-item');
  });

  test('open mobile navigation viewport remains visually stable', async ({ page }) => {
    await page.setViewportSize({ width: 390, height: 844 });
    await gotoAndCheck(page, '/');

    await page.getByRole('button', { name: /open navigation menu/i }).click();
    await expect(page.locator('#cos-mob-menu')).toHaveClass(/open/);
    await expectViewportSnapshot(page, 'mobile-menu-open');
  });

  // Tablet coverage — the existing projects only cover ~390px (mobile) and
  // 1440px (desktop), which is exactly the gap the responsive fixes in this
  // pass lived in (768/1024 is where the PDP hero, footer newsletter, and
  // checkout gutter bugs actually happened). New baselines only; the
  // existing 390/1440 snapshots above are untouched.
  test.describe('tablet viewport visual coverage (768 / 1024)', () => {
    for (const width of [768, 1024]) {
      test(`home-hero at ${width}px remains visually stable`, async ({ page }) => {
        await page.setViewportSize({ width, height: 1024 });
        await gotoAndCheck(page, '/');
        await expectViewportSnapshot(page, `home-hero-tablet-${width}`);
      });

      test(`product detail at ${width}px remains visually stable`, async ({ page }) => {
        await page.setViewportSize({ width, height: 1024 });
        await gotoAndCheck(page, '/products/advanced-repair-serum/');
        await expectViewportSnapshot(page, `product-detail-tablet-${width}`);
      });

      test(`checkout with item at ${width}px remains visually stable`, async ({ page }) => {
        await page.setViewportSize({ width, height: 1024 });
        await addFirstProductToBag(page);
        await gotoAndCheck(page, '/checkout/');
        await expectViewportSnapshot(page, `checkout-with-item-tablet-${width}`);
      });
    }
  });
});
