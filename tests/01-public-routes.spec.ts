import { expect, test } from '@playwright/test';
import {
  criticalRoutes,
  expectHealthyPageShell,
  expectNoHorizontalOverflow,
  expectSingleViewportMeta,
  gotoAndCheck
} from './helpers/cos-helpers';

test.describe('Public route scenarios', () => {
  for (const route of criticalRoutes) {
    test(`${route.name} route returns a healthy storefront page`, async ({ page }) => {
      const response = await gotoAndCheck(page, route.path);

      expect(response!.status(), `${route.path} should not be missing`).toBeLessThan(400);
      await expect(page.locator('body')).toContainText(route.expected);
      await expectHealthyPageShell(page);
      await expectSingleViewportMeta(page);
      await expectNoHorizontalOverflow(page);
    });
  }

  test('checkout without a cart redirects safely to the cart scenario', async ({ page }) => {
    await gotoAndCheck(page, '/checkout/');

    await expect(page, 'Empty checkout should return shoppers to the cart').toHaveURL(/\/cart\/?$/);
    await expect(page.locator('body')).toContainText(/Your Ritual Bag|ritual bag is empty|Order Summary/i);
  });

  test('unknown route returns a branded not-found scenario without PHP errors', async ({ page }) => {
    const response = await gotoAndCheck(page, '/not-a-real-cos-test-page/', {
      allowedSevereErrors: [/Failed to load resource: the server responded with a status of 404/i]
    });

    expect(response!.status(), 'Unknown content should render as a 404').toBe(404);
    await expect(page.locator('body')).toContainText(/404|not found|Caviar of Switzerland|page/i);
  });
});
