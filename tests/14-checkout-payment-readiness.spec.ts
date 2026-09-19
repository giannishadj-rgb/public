import { expect, test } from '@playwright/test';
import { addFirstProductToBag, gotoAndCheck } from './helpers/cos-helpers';

test.describe('Checkout and payment readiness', () => {
  test('WooCommerce Payments assets are not enqueued while every WooPayments gateway is disabled', async ({ page }) => {
    await gotoAndCheck(page, '/shop/');
    const shopHtml = await page.content();
    expect(shopHtml).not.toMatch(/WCPAY_ASSETS|wcpayAssets/);

    const wcpayScriptRequests: string[] = [];
    page.on('request', req => {
      if (/woocommerce-payments\/dist\//i.test(req.url()) || /js\.stripe\.com/i.test(req.url())) {
        wcpayScriptRequests.push(req.url());
      }
    });

    await addFirstProductToBag(page);
    // gotoAndCheck() itself would fail the whole test on the console error
    // this guards against — go directly so a regression here reports as a
    // clear "wcpay script requested" failure, not a generic page error.
    await page.goto('/checkout/', { waitUntil: 'networkidle' });

    const checkoutHtml = await page.content();
    expect(checkoutHtml).not.toMatch(/WCPAY_ASSETS|wcpayAssets/);
    // The real regression this guards: WooCommerce Payments' own
    // "maybe_load_checkout_scripts" self-defence mechanism force-loads its
    // Stripe-backed checkout bundle even with every gateway disabled,
    // unless the theme explicitly dequeues it (see inc/commerce.php).
    expect(wcpayScriptRequests, 'No WooCommerce Payments / Stripe script should load while all its gateways are disabled').toEqual([]);
  });

  test('checkout offers every country, and Germany (and other representative destinations) can be selected', async ({ page }) => {
    // Fulfilment genuinely expanded beyond Switzerland (see inc/shipping.php
    // / OWNER-ACTIONS.md §10 — 2026-09-11 owner decision): a real "Rest of
    // World" WooCommerce zone with an unconditional free_shipping method
    // now sits alongside the Switzerland zone, so cos_child_operational_ship_to_countries()
    // no longer restricts the woocommerce_countries filter. The country
    // selector should offer the full WooCommerce list, not just CH.
    await addFirstProductToBag(page);
    await gotoAndCheck(page, '/checkout/');

    const billingCountry = page.locator('#billing_country');
    const shippingCountry = page.locator('#shipping_country');

    const optionCount = await billingCountry.locator('option').count();
    expect(optionCount, 'Billing country selector should offer more than one country').toBeGreaterThan(1);
    await expect(shippingCountry.locator('option')).toHaveCount(optionCount);

    const escapeRegExp = (value: string) => value.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');

    for (const country of ['Germany', 'United States (US)', 'Australia', 'Switzerland']) {
      await expect(
        page.locator('#billing_country option', { hasText: new RegExp(`^${escapeRegExp(country)}$`) }),
        `${country} should be selectable at checkout`
      ).toHaveCount(1);
    }

    await billingCountry.selectOption({ label: 'Germany' });
    await expect(billingCountry).toHaveValue('DE');
  });

  for (const { label, code } of [
    { label: 'Switzerland', code: 'CH' },
    { label: 'Germany', code: 'DE' },
    { label: 'United States (US)', code: 'US' },
    { label: 'Australia', code: 'AU' }
  ]) {
    test(`${label} receives a valid zero-cost shipping rate at checkout`, async ({ page }) => {
      await addFirstProductToBag(page);
      await gotoAndCheck(page, '/checkout/');

      const updated = page.waitForResponse(response => /wc-ajax=update_order_review/i.test(response.url()), { timeout: 15_000 }).catch(() => null);
      await page.locator('#billing_country').selectOption({ label }).catch(async () => {
        await page.locator('#billing_country').selectOption(code);
      });
      await updated;
      await page.waitForLoadState('networkidle', { timeout: 10_000 }).catch(() => undefined);

      const shippingRow = page.locator('.woocommerce-shipping-totals, #shipping_method, .shipping');
      await expect(shippingRow.first(), `${label} should show a shipping line at checkout`).toBeVisible();

      const bodyText = (await page.locator('.woocommerce-checkout-review-order, #order_review').first().textContent()) || '';
      expect(bodyText, `${label} should not show "no shipping options" at checkout`).not.toMatch(/no shipping options? (are )?available/i);
      expect(bodyText, `${label} should be offered a free ($0) shipping rate`).toMatch(/free|(?:CHF|€|\$)\s?0([.,]00)?\b/i);
    });
  }

  test('Cash on Delivery is offered at checkout for a destination with a real shipping rate (Switzerland)', async ({ page }) => {
    await addFirstProductToBag(page);
    await gotoAndCheck(page, '/checkout/');

    const updated = page.waitForResponse(response => /wc-ajax=update_order_review/i.test(response.url()), { timeout: 15_000 }).catch(() => null);
    await page.locator('#billing_country').selectOption({ label: 'Switzerland' }).catch(() => undefined);
    await updated;
    await page.waitForLoadState('networkidle', { timeout: 10_000 }).catch(() => undefined);

    const codOption = page.locator('input[name="payment_method"][value="cod"]');
    await expect(codOption).toHaveCount(1);
  });

  test('exactly one coupon form exists after a checkout refresh', async ({ page }) => {
    await addFirstProductToBag(page);
    await gotoAndCheck(page, '/checkout/');

    await expect(page.locator('input[name="coupon_code"]')).toHaveCount(1);

    // Trigger the same wc-ajax=update_order_review refresh used by the COD
    // tests above — this is what re-renders the review-order table and is
    // the point at which a coupon form hooked inside that table would
    // duplicate (see inc/commerce.php).
    const updated = page.waitForResponse(response => /wc-ajax=update_order_review/i.test(response.url()), { timeout: 15_000 }).catch(() => null);
    await page.locator('#billing_country').selectOption({ label: 'Switzerland' }).catch(() => undefined);
    await updated;
    await page.waitForLoadState('networkidle', { timeout: 10_000 }).catch(() => undefined);

    await expect(page.locator('input[name="coupon_code"]')).toHaveCount(1);
  });

  test('refund copy does not unconditionally promise a refund to the "original payment method" for Cash on Delivery orders', async ({ page }) => {
    for (const path of ['/shipping-returns/', '/terms/', '/help/']) {
      await gotoAndCheck(page, path);
      const html = await page.content();
      const mentionsOriginalPaymentMethod = /original payment method/i.test(html);
      if (mentionsOriginalPaymentMethod) {
        expect(html, `${path} should qualify the "original payment method" refund promise for Cash on Delivery orders`).toMatch(/cash on delivery/i);
      }
    }
  });

  const publicPagesToScan = [
    '/', '/shop/', '/cart/', '/terms/', '/faq/', '/help/', '/shipping-returns/',
    '/products/micellar-water-cleanser/',
    '/products/advanced-repair-serum/',
    '/products/revitalizing-eye-cream/',
    '/products/24h-regeneration-cream/',
    '/products/the-complete-ritual/'
  ];

  for (const path of publicPagesToScan) {
    test(`${path} does not contain stale "Switzerland only" shipping copy`, async ({ page }) => {
      await gotoAndCheck(page, path);
      const bodyText = (await page.locator('body').textContent()) || '';
      expect(bodyText, `${path} should not claim shipping is Switzerland-only`).not.toMatch(/switzerland only/i);
      expect(bodyText, `${path} should not use the old CH-only badge copy`).not.toMatch(/free shipping in switzerland/i);
    });
  }

  test('product structured data reflects worldwide shipping, not a CH-only destination', async ({ page }) => {
    for (const path of [
      '/products/micellar-water-cleanser/',
      '/products/advanced-repair-serum/',
      '/products/revitalizing-eye-cream/',
      '/products/24h-regeneration-cream/',
      '/products/the-complete-ritual/'
    ]) {
      await gotoAndCheck(page, path);
      const jsonLdBlocks = await page.locator('script[type="application/ld+json"]').allTextContents();
      const productSchema = jsonLdBlocks
        .map(block => {
          try {
            return JSON.parse(block);
          } catch {
            return null;
          }
        })
        .flatMap(parsed => (parsed?.['@graph'] ? parsed['@graph'] : [parsed]))
        .find((node: any) => node?.['@type'] === 'Product');

      expect(productSchema, `${path} should emit Product structured data`).toBeTruthy();

      const shippingCountries = productSchema.offers?.shippingDetails?.shippingDestination?.addressCountry;
      const returnCountries = productSchema.offers?.hasMerchantReturnPolicy?.applicableCountry;

      const asList = (value: unknown) => (Array.isArray(value) ? value : [value]);

      expect(asList(shippingCountries), `${path} shippingDestination should not be CH-only`).not.toEqual(['CH']);
      expect(asList(returnCountries), `${path} return policy applicableCountry should not be CH-only`).not.toEqual(['CH']);
    }
  });
});
