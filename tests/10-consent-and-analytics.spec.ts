import { expect, test } from '@playwright/test';
import { addFirstProductToBag, gotoAndCheck, isProductionLikeBaseUrl } from './helpers/cos-helpers';

// Fixture identity for the order-creating test below. "example.test" is an
// IANA/RFC 2606 domain reserved specifically for testing — never assignable
// to a real customer — which is exactly what lets the server side
// (cos_child_is_test_order() in inc/commerce.php) unambiguously recognise,
// tag, silence notifications for, and auto-cancel orders this test creates.
const TEST_ORDER_EMAIL = 'playwright-purchase-test@example.test';

// Local dev's Mailpit SMTP-capture inbox (see project reference memory on
// local DB/service access) — the real, external proof that no notification
// email actually left the system for a tagged test order, independent of
// (and a check on) the internal suppression-filter logic itself.
const MAILPIT_URL = process.env.MAILPIT_URL || 'http://127.0.0.1:10000';

async function countMailpitMessagesTo(request: import('@playwright/test').APIRequestContext, email: string): Promise<number> {
  const response = await request.get(`${MAILPIT_URL}/api/v1/search`, { params: { query: `to:${email}` } });
  if (!response.ok()) return -1; // Mailpit unreachable — caller decides how to treat this
  const body = await response.json();
  return Array.isArray(body.messages) ? body.messages.length : 0;
}

test.describe('Consent and analytics scenarios', () => {
  test('GTM does not load on this (non-production) environment, but Consent Mode defaults still fire', async ({ page }) => {
    await gotoAndCheck(page, '/');

    const html = await page.content();
    expect(html).not.toMatch(/googletagmanager\.com\/gtm\.js/i);
    expect(html).not.toMatch(/googletagmanager\.com\/ns\.html/i);

    // gtag() pushes its `arguments` object (array-like, not a real Array),
    // so entries are read positionally rather than checked with Array.isArray.
    const hasConsentDefault = await page.evaluate(() => {
      const dataLayer = (window as any).dataLayer as ArrayLike<unknown>[] | undefined;
      return (dataLayer || []).some(entry => entry && entry[0] === 'consent' && entry[1] === 'default');
    });
    expect(hasConsentDefault).toBe(true);
  });

  test.describe('Footer "Cookie Settings" control', () => {
    test('always performs a meaningful action — opens the real preference centre, or a working fallback, never a silent no-op', async ({ page }) => {
      await gotoAndCheck(page, '/');

      const errors: string[] = [];
      page.on('pageerror', error => errors.push(error.message));

      const bannerAvailable = await page.evaluate(() => typeof (window as any).cmplz_set_banner_status === 'function');

      const cookieBtn = page.locator('.cos-footer-cookie-link button');
      await expect(cookieBtn).toBeVisible();
      await cookieBtn.click();
      expect(errors).toEqual([]);

      if (bannerAvailable) {
        // Real Complianz preference centre present: the button must actually
        // reopen it, not merely avoid throwing.
        await expect(page.locator('.cmplz-cookiebanner-container, #cmplz-manage-consent-container')).toBeVisible({ timeout: 5000 });
      } else {
        // Complianz's own banner script isn't enqueued in this environment
        // (Complianz "Safe Mode" — see OWNER-ACTIONS.md). The control must
        // still do something real rather than nothing: it sends the visitor
        // to the cookie-policy section of the Privacy page.
        await page.waitForURL(/\/privacy\/#cookies/, { timeout: 5000 });
        await expect(page.locator('#cookies')).toBeVisible();
      }
    });

    test('reopening preferences, accept, reject, and withdrawal all work against the live Complianz banner', async ({ page }) => {
      await gotoAndCheck(page, '/');
      const bannerAvailable = await page.evaluate(() => typeof (window as any).cmplz_set_banner_status === 'function');
      test.skip(
        !bannerAvailable,
        'Complianz\'s cookie-banner script is not enqueued in this environment (Safe Mode). ' +
          'See OWNER-ACTIONS.md — this test runs for real once an owner completes Complianz activation.'
      );

      // First-time visitor sees the banner.
      const banner = page.locator('.cmplz-cookiebanner-container');
      await expect(banner).toBeVisible();

      // Reject optional cookies.
      const rejectBtn = page.locator('.cmplz-cookiebanner-container').getByRole('button', { name: /deny|reject/i }).first();
      await rejectBtn.click();
      await expect(banner).toBeHidden();

      // Reopen from the footer control and accept.
      await page.locator('.cos-footer-cookie-link button').click();
      await expect(banner).toBeVisible();
      const acceptBtn = page.locator('.cmplz-cookiebanner-container').getByRole('button', { name: /accept/i }).first();
      await acceptBtn.click();
      await expect(banner).toBeHidden();

      // Consent can be withdrawn again via the same reopened control.
      await page.locator('.cos-footer-cookie-link button').click();
      await expect(banner).toBeVisible();
      const rejectAgain = page.locator('.cmplz-cookiebanner-container').getByRole('button', { name: /deny|reject/i }).first();
      await rejectAgain.click();
      await expect(banner).toBeHidden();
    });

    test('analytics/marketing requests never fire before the required consent, against the live Complianz banner', async ({ page }) => {
      const bannerAvailable = await page.evaluate(() => typeof (window as any).cmplz_set_banner_status === 'function').catch(() => false);
      test.skip(!bannerAvailable, 'Requires the live Complianz banner — see OWNER-ACTIONS.md.');

      const trackingRequests: string[] = [];
      page.on('request', req => {
        if (/google-analytics\.com\/collect|analytics\.google\.com|doubleclick\.net|googleadservices\.com/i.test(req.url())) {
          trackingRequests.push(req.url());
        }
      });

      await gotoAndCheck(page, '/');
      await expect(page.locator('.cmplz-cookiebanner-container')).toBeVisible();
      expect(trackingRequests, 'No analytics/marketing request should fire before consent is given').toEqual([]);
    });
  });

  test('the Complianz consent bridge updates Google Consent Mode when a category choice changes (independent of the banner UI)', async ({ page }) => {
    // cos_child_complianz_consent_bridge() (inc/analytics.php) listens for
    // Complianz's cmplz_status_change event unconditionally — this is our
    // own bridge code, so it's testable without the live banner UI being
    // enqueued (see the Safe Mode note in OWNER-ACTIONS.md for why the UI
    // itself may not be present in this environment).
    await gotoAndCheck(page, '/');

    await page.evaluate(() => {
      (window as any).dataLayer = (window as any).dataLayer || [];
      document.dispatchEvent(new CustomEvent('cmplz_status_change', { detail: { category: 'statistics', status: 'allow' } }));
      document.dispatchEvent(new CustomEvent('cmplz_status_change', { detail: { category: 'marketing', status: 'deny' } }));
    });

    const pushes = await page.evaluate(() => (window as any).dataLayer as ArrayLike<unknown>[]);
    const consentUpdates = pushes.filter(entry => entry && entry[0] === 'consent' && entry[1] === 'update');
    expect(consentUpdates.length, 'A consent update should be pushed after each category change').toBeGreaterThanOrEqual(2);

    const statsUpdate = consentUpdates.find((entry: any) => entry[2] && 'analytics_storage' in entry[2]) as any;
    expect(statsUpdate?.[2]?.analytics_storage).toBe('granted');

    const marketingUpdate = consentUpdates.find((entry: any) => entry[2] && 'ad_storage' in entry[2]) as any;
    expect(marketingUpdate?.[2]?.ad_storage).toBe('denied');
  });

  test('the purchase-confirm endpoint rejects an invalid order/nonce', async ({ request, baseURL }) => {
    const response = await request.post(`${baseURL}wp-admin/admin-ajax.php`, {
      form: {
        action: 'cos_confirm_purchase_tracked',
        order_id: '999999999',
        order_key: 'wc_order_not-a-real-key',
        nonce: 'not-a-real-nonce'
      }
    });
    expect(response.status()).toBe(400);
    const body = await response.json();
    expect(body.success).toBe(false);
  });

  test('a completed order tracks the purchase event exactly once, deduplicated server-side', async ({ page, request, baseURL }) => {
    // This test places a REAL order. Never run it against anything but a
    // recognised local/dedicated test environment — see OWNER-ACTIONS.md
    // and inc/commerce.php's cos_child_is_test_order() for the server-side
    // half of this guarantee (tagging, no notifications, auto-cancel).
    test.skip(isProductionLikeBaseUrl(baseURL), 'Order-creating tests must not run against a non-local environment.');

    // Captured before placing the order so the post-order check below is a
    // delta against this specific test run, not confused by any unrelated
    // messages already sitting in the local Mailpit inbox.
    const mailCountBefore = await countMailpitMessagesTo(request, TEST_ORDER_EMAIL);

    let orderId: string | undefined;
    let orderKey: string | null = null;

    try {

    await addFirstProductToBag(page);
    await gotoAndCheck(page, '/checkout/');

    await page.locator('#billing_first_name').fill('Test');
    await page.locator('#billing_last_name').fill('Visitor');
    await page.locator('#billing_country').selectOption({ label: 'Switzerland' }).catch(() => undefined);
    await page.locator('#billing_address_1').fill('Chamerstrasse 1');
    await page.locator('#billing_city').fill('Zug');
    await page.locator('#billing_postcode').fill('6300');
    await page.locator('#billing_phone').fill('+41 79 000 00 00');
    await page.locator('#billing_email').fill(TEST_ORDER_EMAIL);

    const codOption = page.locator('input[name="payment_method"][value="cod"]');
    if (await codOption.count()) {
      await codOption.check();
    }

    const terms = page.locator('#terms');
    if (await terms.count()) {
      await terms.check();
    }

    await page.waitForLoadState('networkidle', { timeout: 10_000 }).catch(() => undefined);
    await page.locator('#place_order').click();
    await page.waitForURL(/order-received/, { timeout: 20_000 });
    await page.waitForLoadState('networkidle', { timeout: 10_000 }).catch(() => undefined);

    const url = new URL(page.url());
    orderId = url.pathname.match(/order-received\/(\d+)/)?.[1];
    orderKey = url.searchParams.get('key');
    expect(orderId, 'Order id should be present in the thank-you URL').toBeTruthy();

    // Confirm the server actually tagged this as a test order (see
    // cos_child_is_test_order() in inc/commerce.php) — it is never fulfilled
    // for real, never emailed, and auto-cancelled shortly after creation.
    // order_key is required here (security audit finding M1, 2026-09-18):
    // cos_child_test_get_order_meta() now re-verifies the order key via
    // hash_equals() before returning anything, matching the pattern its
    // sibling cancel-order endpoint already used.
    const metaCheck = await request.post(`${baseURL}wp-admin/admin-ajax.php`, {
      form: { action: 'cos_test_get_order_meta', order_id: orderId!, order_key: orderKey! }
    });
    expect(metaCheck.status(), 'Test-order visibility endpoint should be reachable in this local environment').toBe(200);
    const metaBody = await metaCheck.json();
    expect(metaBody.success).toBe(true);
    expect(metaBody.data.is_test_order, 'Order created with the reserved example.test billing email should be tagged _cos_test_order').toBe(true);
    expect(orderKey, 'Order key should be present in the thank-you URL').toBeTruthy();

    // Order status right after placing it must be a normal COD "processing"
    // state (that transition is what "order successfully placed" *is* — it
    // can't be skipped), but must never be anything that implies it's
    // waiting on a human/warehouse — i.e. never on-hold. The finally block
    // below moves it out of any fulfilment-ready status immediately.
    expect(metaBody.data.status, 'A freshly placed COD test order should be "processing", not left on-hold').toBe('processing');

    // Prove the suppression *code path* itself was reached and engaged for
    // both the admin and customer notifications — not just inferred from an
    // empty inbox, which could be true for unrelated reasons (e.g.
    // WooCommerce emails disabled globally in settings).
    const suppressed: Array<{ email_id: string; was_enabled: boolean }> = metaBody.data.suppressed_emails || [];
    const suppressedIds = suppressed.map(s => s.email_id);
    expect(suppressedIds, 'The admin "new order" notification should have been evaluated and suppressed').toContain('new_order');
    expect(suppressedIds, 'The customer "processing order" notification should have been evaluated and suppressed').toContain('customer_processing_order');
    expect(suppressed.every(s => s.was_enabled), 'Every suppressed email must have genuinely been enabled (i.e. really would have sent) before this filter blocked it').toBe(true);

    // The strongest proof: no message actually landed in the local
    // mail-capture inbox for this address as a result of this order.
    const mailCountAfter = await countMailpitMessagesTo(request, TEST_ORDER_EMAIL);
    if (mailCountAfter >= 0 && mailCountBefore >= 0) {
      expect(mailCountAfter, 'No new email should have been delivered for this test order').toBe(mailCountBefore);
    }

    const purchasePush = await page.evaluate(() => {
      const dataLayer = (window as any).dataLayer as unknown[] | undefined;
      return (dataLayer || []).find((entry: any) => entry && entry.event === 'purchase') || null;
    });
    expect(purchasePush, 'A purchase event should be pushed to dataLayer').not.toBeNull();
    const purchase = purchasePush as any;
    expect(purchase.value).toBeGreaterThan(0);
    expect(purchase.transaction_id).toBeTruthy();
    expect(purchase.currency).toBe('EUR');
    expect(typeof purchase.tax).toBe('number');
    expect(typeof purchase.shipping).toBe('number');
    expect('coupon' in purchase, 'Purchase event should carry a coupon field (empty string when none used)').toBe(true);
    expect(Array.isArray(purchase.items)).toBe(true);
    expect(purchase.items.length).toBeGreaterThan(0);
    for (const item of purchase.items) {
      expect(item.item_id, 'Each item should carry a SKU/id').toBeTruthy();
      expect(typeof item.price).toBe('number');
      expect(item.quantity).toBeGreaterThan(0);
    }

    // No customer PII anywhere in the pushed payload.
    const serialized = JSON.stringify(purchase).toLowerCase();
    expect(serialized).not.toMatch(/visitor@example\.test/);
    expect(serialized).not.toMatch(/test visitor/);
    expect(serialized).not.toMatch(/chamerstrasse/);
    expect(serialized).not.toMatch(/\+41/);
    expect(serialized).not.toMatch(/"email"|"phone"|"address"|"first_name"|"last_name"/);

    // Pull the same nonce the page itself already used for its automatic
    // confirm call (WP nonces are time-window-valid, not single-use), so a
    // second, manual call exercises real dedup rather than an auth failure.
    const pageNonce = await page.evaluate(() => {
      const match = document.documentElement.outerHTML.match(
        /action',\s*'cos_confirm_purchase_tracked'[\s\S]{0,400}?nonce',\s*"([a-f0-9]+)"/
      );
      return match ? match[1] : null;
    });
    expect(pageNonce, 'Should be able to recover the confirm nonce from the rendered page').toBeTruthy();

    // First manual confirm should already report the order as tracked, since
    // the page's own DOMContentLoaded handler already confirmed it once.
    const secondConfirm = await request.post(`${baseURL}wp-admin/admin-ajax.php`, {
      form: {
        action: 'cos_confirm_purchase_tracked',
        order_id: orderId!,
        order_key: orderKey!,
        nonce: pageNonce!
      }
    });
    expect(secondConfirm.status()).toBe(200);
    const secondBody = await secondConfirm.json();
    expect(secondBody.success).toBe(true);
    expect(secondBody.data.already_tracked).toBe(true);

    // A mismatched order key must never be accepted, even with a valid nonce.
    const wrongKeyConfirm = await request.post(`${baseURL}wp-admin/admin-ajax.php`, {
      form: {
        action: 'cos_confirm_purchase_tracked',
        order_id: orderId!,
        order_key: 'wc_order_wrong-key-entirely',
        nonce: pageNonce!
      }
    });
    expect(wrongKeyConfirm.status()).toBe(403);

    // Reloading the thank-you page must not push a second purchase event.
    // The sessionStorage guard in page-checkout.php's order-received script
    // means cos.track('purchase', ...) simply never runs again on reload —
    // dataLayer (freshly re-initialised by the reload) should have zero
    // purchase entries, not just "no more than one". This has to come after
    // the nonce-based checks above: once the order is marked tracked
    // (already true by now), page-checkout.php stops rendering the confirm
    // script/nonce entirely on reload, by design.
    await page.reload({ waitUntil: 'networkidle' });
    const pushesAfterReload = await page.evaluate(() => {
      const dataLayer = (window as any).dataLayer as any[] | undefined;
      return (dataLayer || []).filter(entry => entry && entry.event === 'purchase').length;
    });
    expect(pushesAfterReload, 'Reloading the order-received page must not push a duplicate purchase event').toBe(0);

    } finally {
      // Teardown: cancel the order this test just created immediately,
      // rather than leaving it to the hourly cron's 10-minute-age window —
      // runs even if an assertion above failed/threw, via `finally`, so a
      // broken test doesn't also leave a lingering order behind. The cron
      // (inc/commerce.php) remains as a fallback only, for a test run
      // killed before reaching this point at all (e.g. the whole process
      // crashing rather than a single assertion failing).
      if (orderId && orderKey) {
        const cancelResponse = await request.post(`${baseURL}wp-admin/admin-ajax.php`, {
          form: { action: 'cos_test_cancel_order', order_id: orderId, order_key: orderKey }
        });
        const cancelBody = await cancelResponse.json().catch(() => null);
        expect(cancelResponse.status(), 'Immediate test-order cancellation should succeed').toBe(200);
        expect(cancelBody?.success).toBe(true);
        expect(cancelBody?.data?.status, 'The test order must end this test run cancelled, not sitting in a fulfilment-ready status').toBe('cancelled');
      }
    }
  });
});
