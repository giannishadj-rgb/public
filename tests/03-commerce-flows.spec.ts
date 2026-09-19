import { expect, test } from '@playwright/test';
import {
  addFirstProductToBag,
  discoverProductLinks,
  expectNoHorizontalOverflow,
  getAddToBagControl,
  gotoAndCheck
} from './helpers/cos-helpers';

test.describe('WooCommerce storefront scenarios', () => {
  test('shop presents the complete ritual, products, prices, and add-to-bag controls', async ({ page }) => {
    await gotoAndCheck(page, '/shop/');

    await expect(page.locator('body')).toContainText(/Shop|Ritual/i);

    const productCards = page.locator('.cos-card, .cos-sv3-card, article:has-text("Step 01")');
    expect(await productCards.count(), 'Shop should show at least the four individual ritual products').toBeGreaterThanOrEqual(4);

    const addToBagControls = page.locator('a,button').filter({ hasText: /^Add to Bag$/i });
    expect(await addToBagControls.count(), 'Shop should expose add-to-bag controls').toBeGreaterThan(0);

    await expect(page.locator('body')).toContainText(/Step\s*01|Cleanse/i);
    await expect(page.locator('body')).toContainText(/Step\s*02|Treat/i);
    await expect(page.locator('body')).toContainText(/Step\s*03|Eye/i);
    await expect(page.locator('body')).toContainText(/Step\s*04|Seal|Moisturi[sz]e/i);
    await expectNoHorizontalOverflow(page);
  });

  test('empty cart scenario shows order summary, reassurance, and path back to shop', async ({ page }) => {
    await gotoAndCheck(page, '/cart/');

    await expect(page.locator('body')).toContainText(/Your Ritual Bag/i);
    await expect(page.locator('body')).toContainText(/ritual bag is empty/i);
    await expect(page.locator('body')).toContainText(/Order Summary/i);
    await expect(page.getByRole('link', { name: /Start the Ritual/i }).first()).toBeVisible();
  });

  test('add-to-bag scenario creates a non-empty cart and exposes checkout', async ({ page }) => {
    await addFirstProductToBag(page);

    await expect(page).toHaveURL(/\/cart\/?/);
    await expect(page.locator('body')).not.toContainText(/ritual bag is empty/i);
    await expect(page.locator('body')).toContainText(/Order Summary|Subtotal|Total/i);
    await expect(page.getByRole('link', { name: /Proceed to (Secure )?Checkout/i })).toBeVisible();
    await expect(page.getByRole('link', { name: /Continue Shopping/i })).toBeVisible();
  });

  test('cart quantity and remove scenarios keep totals and empty state coherent', async ({ page }) => {
    await addFirstProductToBag(page);

    const increase = page.getByRole('button', { name: /increase quantity/i }).first();
    if (await increase.count()) {
      await increase.click();
      await page.waitForLoadState('domcontentloaded').catch(() => undefined);
      await expect(page.locator('.cos-cp-qty-val').first()).toHaveText('2');
    }

    const remove = page.getByRole('link', { name: /^Remove$/i }).first();
    await expect(remove).toBeVisible();
    await remove.click();
    await page.waitForLoadState('domcontentloaded').catch(() => undefined);
    await expect(page.locator('body')).toContainText(/ritual bag is empty/i);
  });

  test('checkout with a cart shows required billing, payment, and terms controls', async ({ page }) => {
    await addFirstProductToBag(page);
    await page.getByRole('link', { name: /Proceed to (Secure )?Checkout/i }).click();
    await page.waitForLoadState('domcontentloaded').catch(() => undefined);

    await expect(page).toHaveURL(/\/checkout\/?/);
    await expect(page.locator('body')).toContainText(/Complete your ritual|Billing details|Secure Checkout/i);
    await expect(page.locator('#billing_first_name, input[name="billing_first_name"]').first()).toBeVisible();
    await expect(page.locator('#billing_last_name, input[name="billing_last_name"]').first()).toBeVisible();
    await expect(page.locator('#billing_email, input[name="billing_email"]').first()).toBeVisible();
    await expect(page.locator('#place_order, button[name="woocommerce_checkout_place_order"]').first()).toBeVisible();
  });

  test('product detail pages expose gallery, quantity, add-to-bag, details, and structured content', async ({ page }) => {
    const productLinks = await discoverProductLinks(page);
    expect(productLinks.length, 'Shop should link to product detail pages').toBeGreaterThan(0);

    for (const path of productLinks) {
      await gotoAndCheck(page, path);

      await expect(page.locator('h1').first(), `${path} should have a product heading`).toBeVisible();
      await expect(await getAddToBagControl(page), `${path} should have an Add to Bag control`).toBeVisible();
      await expect(page.locator('body')).toContainText(/Active Ingredients|How to apply|Product Details|Frequently asked|Clinical/i);

      const quantityInput = page.locator('#cos-qty, input.qty, input[name="quantity"]').first();
      if (await quantityInput.count()) {
        await expect(quantityInput).toBeVisible();
      }

      const details = page.locator('details').first();
      if (await details.count()) {
        await details.locator('summary').click();
        await expect(details).toHaveAttribute('open', '');
      }
    }
  });
});
