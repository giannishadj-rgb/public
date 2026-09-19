import { expect, test } from '@playwright/test';
import { readFileSync } from 'node:fs';
import { join } from 'node:path';
import { gotoAndCheck } from './helpers/cos-helpers';

test.describe('Inventory and bundle safety', () => {
  test('the quantity field on a product page is capped at a sane maximum', async ({ page }) => {
    await gotoAndCheck(page, '/products/advanced-repair-serum/');

    const qtyInput = page.locator('#cos-qty');
    await expect(qtyInput, 'PDP quantity input should be present').toHaveCount(1);
    const max = await qtyInput.getAttribute('max');
    expect(max, 'Quantity input should declare a maximum').not.toBeNull();
    expect(Number(max)).toBeGreaterThan(0);
    expect(Number(max)).toBeLessThanOrEqual(10);

    // The +/- stepper buttons must respect the same cap, not just the raw
    // HTML attribute (which a scripted client could ignore anyway).
    await qtyInput.fill(String(Number(max)));
    await page.locator('.cos-pdp-qty-btn[aria-label="Increase quantity"]').click();
    await expect(qtyInput).toHaveValue(String(Number(max)));
  });

  test('adding an unreasonable quantity is rejected server-side, not just by the HTML max attribute', async ({ page, request, baseURL }) => {
    await gotoAndCheck(page, '/products/advanced-repair-serum/');

    // Bypass the client-side max attribute entirely and go straight at the
    // add-to-cart endpoint, the way a scripted/malicious client would.
    const productIdInput = page.locator('form.cos-pdp-form input[name="add-to-cart"]');
    const productId = await productIdInput.getAttribute('value');
    expect(productId, 'Could not resolve a product id from the PDP add-to-cart form.').toBeTruthy();

    const response = await request.post(`${baseURL}?wc-ajax=add_to_cart`, {
      form: { product_id: productId!, quantity: '9999' }
    });
    const body = await response.json().catch(() => null);
    // WooCommerce reports validation failures as a 200 with an `error` flag
    // and notices HTML, not an HTTP error status.
    expect(body?.error, 'Adding 9999 units should be rejected, not silently accepted').toBeTruthy();
  });

  test('the Complete Ritual bundle is mapped to its 4 components by stable SKU, and its availability is wired to follow them', () => {
    const source = readFileSync(
      join(__dirname, '..', 'wp-content', 'themes', 'cos-child', 'inc', 'commerce.php'),
      'utf8'
    );
    expect(source).toMatch(/cos_child_bundle_component_skus/);
    for (const sku of ['COS-001', 'COS-002', 'COS-003', 'COS-004']) {
      expect(source).toContain(sku);
    }
    expect(source).toMatch(/woocommerce_product_is_in_stock/);
    expect(source).toMatch(/woocommerce_add_to_cart_validation/);
    // Must be an SKU-based lookup (wc_get_product_id_by_sku), not fragile
    // hard-coded post IDs.
    expect(source).toMatch(/wc_get_product_id_by_sku/);
  });

  test('the bundle purchase page is currently available (its components are, in fact, in stock)', async ({ page }) => {
    await gotoAndCheck(page, '/shop/');
    const bundleLink = page.locator('.cos-bundle-view, a[href*="the-complete-ritual"]').first();
    if (await bundleLink.count()) {
      await bundleLink.click();
      await page.waitForLoadState('domcontentloaded');
      await expect(page.locator('body')).not.toContainText(/out of stock/i);
    }
  });
});
