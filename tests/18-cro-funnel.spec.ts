import { expect, test } from '@playwright/test';
import { gotoAndCheck, scrollWindowIncrementally } from './helpers/cos-helpers';

/**
 * Coverage for the 2026-08-20 CRO/conversion-funnel implementation pass —
 * homepage value proposition, CTA hierarchy, shop/PDP/cart/checkout
 * improvements. Grouped here rather than scattered across the existing
 * per-page files since this is one coherent brief; see OWNER-ACTIONS.md and
 * the plan file for the fuller context.
 */

test.describe('Homepage value proposition and CTA hierarchy', () => {
  test('hero has the new H1, supporting copy, and reassurance line', async ({ page }) => {
    await gotoAndCheck(page, '/');
    await expect(page.locator('.cos-hero-h1')).toHaveText('A four-step ritual for firmer, smoother-looking skin.');
    await expect(page.locator('.cos-hero-eyebrow')).toHaveText('SWISS CAVIAR SKINCARE');
    await expect(page.locator('.cos-hero-sub')).toContainText('independently tested results at 28 days');
    await expect(page.locator('.cos-hero-reassurance')).toContainText('4 full-size products');
    await expect(page.locator('.cos-hero-reassurance')).toContainText('30-day returns');
  });

  test('primary CTA is visually dominant over the secondary CTA', async ({ page }) => {
    await gotoAndCheck(page, '/');
    const primary = page.locator('[data-cro-cta="home-hero-primary"]');
    const secondary = page.locator('[data-cro-cta="home-hero-secondary"]');

    await expect(primary).toHaveText('Shop the 4-Step Ritual — Save €50');
    await expect(primary).toHaveAttribute('href', '/products/the-complete-ritual/');
    await expect(secondary).toHaveText('See the 4 Steps');
    await expect(secondary).toHaveAttribute('href', '/the-ritual/');

    const [primaryBg, secondaryBg] = await Promise.all([
      primary.evaluate(el => getComputedStyle(el).backgroundColor),
      secondary.evaluate(el => getComputedStyle(el).backgroundColor),
    ]);
    // Primary is filled (opaque background); secondary is a transparent/outline ghost button.
    expect(primaryBg).not.toBe('rgba(0, 0, 0, 0)');
    expect(secondaryBg).toBe('rgba(0, 0, 0, 0)');
  });

  test('trust strip links point to real, substantiated pages', async ({ page }) => {
    await gotoAndCheck(page, '/');
    await expect(page.locator('.cos-hero-trust a', { hasText: 'Independently tested' })).toHaveAttribute('href', '/science/clinical-studies/');
    await expect(page.locator('.cos-hero-trust a', { hasText: '30-day returns' })).toHaveAttribute('href', '/shipping-returns/');
  });

  test('the complete hero value proposition is visible without scrolling at 390x844', async ({ page }) => {
    await page.setViewportSize({ width: 390, height: 844 });
    await gotoAndCheck(page, '/');

    const hero = page.locator('#cos-hero');
    const heroBox = await hero.boundingBox();
    expect(heroBox).not.toBeNull();

    for (const selector of ['.cos-hero-eyebrow', '.cos-hero-h1', '.cos-hero-sub', '.cos-cta-group', '.cos-hero-reassurance', '.cos-hero-trust']) {
      const box = await page.locator(selector).first().boundingBox();
      expect(box, `${selector} should be present`).not.toBeNull();
      expect(box!.y).toBeGreaterThanOrEqual(0);
      expect(box!.y + box!.height, `${selector} should not overflow the 100svh hero`).toBeLessThanOrEqual(heroBox!.y + heroBox!.height + 2);
    }
  });

  test('the complete hero value proposition is visible without scrolling at 1440x900', async ({ page }) => {
    await page.setViewportSize({ width: 1440, height: 900 });
    await gotoAndCheck(page, '/');

    const hero = page.locator('#cos-hero');
    const heroBox = await hero.boundingBox();
    const trustBox = await page.locator('.cos-hero-trust').boundingBox();
    expect(trustBox!.y + trustBox!.height).toBeLessThanOrEqual(heroBox!.y + heroBox!.height + 2);
  });
});

test.describe('Shop page: compact hero and concern selector', () => {
  test('the Complete Ritual offer begins within the first mobile viewport (390x844)', async ({ page }) => {
    await page.setViewportSize({ width: 390, height: 844 });
    await gotoAndCheck(page, '/shop/');

    const bundleSection = page.locator('#cos-ritual');
    const box = await bundleSection.boundingBox();
    expect(box!.y, 'bundle section should begin within the first viewport').toBeLessThan(844);

    // Hero's own content must not be clipped by the fixed nav either —
    // regression guard for the align-items:flex-end + reduced-height
    // interaction that clipped the eyebrow off-screen during development.
    const eyebrow = await page.locator('.cos-shop-hero-eyebrow').boundingBox();
    expect(eyebrow!.y).toBeGreaterThan(80); // clear of the fixed nav bar
  });

  test('shop hero CTA is filled/high-contrast, links directly to the bundle PDP, and states the saving', async ({ page }) => {
    await gotoAndCheck(page, '/shop/');
    const cta = page.locator('[data-cro-cta="shop-hero-primary"]');
    await expect(cta).toHaveText('View Complete Ritual — Save €50');
    await expect(cta).toHaveAttribute('href', '/products/the-complete-ritual/');
    const bg = await cta.evaluate(el => getComputedStyle(el).backgroundColor);
    expect(bg).not.toBe('rgba(0, 0, 0, 0)');
    const box = await cta.boundingBox();
    expect(box!.height).toBeGreaterThanOrEqual(44);
  });

  test('the concern filter row has been removed — all products show unconditionally', async ({ page }) => {
    // The concern-filter UI (.cos-concerns / .cos-concern-btn) was removed
    // 2026-09-19 at the user's request. This guards against it silently
    // reappearing and confirms every product card is simply always visible
    // now that there's no filter to hide them.
    await gotoAndCheck(page, '/shop/');
    await expect(page.locator('.cos-concerns')).toHaveCount(0);
    await expect(page.locator('.cos-concern-btn')).toHaveCount(0);
    const totalCount = await page.locator('.cos-card').count();
    expect(totalCount).toBeGreaterThan(0);
    expect(await page.locator('.cos-card:visible').count()).toBe(totalCount);
    await expect(page.locator('#cos-ritual')).toBeVisible();
  });

  test('all products remain visible with JavaScript disabled', async ({ browser }) => {
    const context = await browser.newContext({ javaScriptEnabled: false });
    const page = await context.newPage();
    await page.goto('/shop/', { waitUntil: 'domcontentloaded' });
    const visibleCount = await page.locator('.cos-card').count();
    expect(visibleCount).toBeGreaterThan(0);
    for (let i = 0; i < visibleCount; i++) {
      await expect(page.locator('.cos-card').nth(i)).toBeVisible();
    }
    await context.close();
  });
});

test.describe('Product page: above-fold trim, sticky mobile CTA, decision support', () => {
  const pdpUrl = '/products/advanced-repair-serum/'; // COS-002, has clinical stats

  test('benefit list near the CTA is trimmed to 3, with the rest preserved further down the page', async ({ page }) => {
    await gotoAndCheck(page, pdpUrl);
    const leadList = page.locator('.cos-pdp-form').locator('xpath=preceding-sibling::ul[contains(@class,"cos-pdp-benefits-list")][1]');
    await expect(leadList.locator('.cos-pdp-benefit-item')).toHaveCount(3);

    // The 3 items trimmed from the top are not deleted — they're preserved lower on the page.
    const fullSection = page.locator('.cos-pdp-desc-section .cos-pdp-benefits-list');
    await expect(fullSection.locator('.cos-pdp-benefit-item')).toHaveCount(3);
  });

  test('"Ideal for", bundle saving, returns link, and payment method are all near the CTA', async ({ page }) => {
    await gotoAndCheck(page, pdpUrl);
    await expect(page.locator('.cos-pdp-who-mini')).toContainText("Who it's for:");
    const bundleNudge = page.locator('[data-cro-cta="pdp-bundle-nudge"]');
    await expect(bundleNudge).toContainText('save €50');
    await expect(bundleNudge).toHaveAttribute('href', '/products/the-complete-ritual/');

    const returnsLink = page.locator('.cos-pdp-trust a.cos-pdp-trust-item');
    await expect(returnsLink).toHaveAttribute('href', '/shipping-returns/');

    // Only Cash on Delivery is enabled today — the trust grid must say so, not "Secure Checkout".
    await expect(page.locator('.cos-pdp-trust')).toContainText('Pay on Delivery');
    await expect(page.locator('.cos-pdp-trust')).not.toContainText('Secure Checkout');

    await expect(page.locator('.cos-pdp-methodology-link a')).toHaveAttribute('href', '/science/clinical-studies/');
  });

  test('the bundle page does not nudge itself toward the bundle', async ({ page }) => {
    await gotoAndCheck(page, '/products/the-complete-ritual/');
    await expect(page.locator('[data-cro-cta="pdp-bundle-nudge"]')).toHaveCount(0);
  });

  test('a product with no disclosed clinical stats does not show a methodology link near the CTA', async ({ page }) => {
    await gotoAndCheck(page, '/products/micellar-water-cleanser/');
    await expect(page.locator('.cos-pdp-methodology-link')).toHaveCount(0);
  });

  test('a product with a verified statistic shows exactly its first stat near the CTA, unchanged, with a methodology link — and never before the benefits list', async ({ page }) => {
    await gotoAndCheck(page, pdpUrl); // COS-002: $pd['stats'] = [['+47%', 'Perceived firmness increase at 28 days']]
    const evidence = page.locator('.cos-pdp-evidence');
    await expect(evidence).toHaveCount(1);
    await expect(evidence.locator('.cos-pdp-evidence-num')).toHaveText('+47%');
    await expect(evidence.locator('.cos-pdp-evidence-label')).toHaveText('Perceived firmness increase at 28 days');
    await expect(evidence.locator('.cos-pdp-evidence-link')).toHaveText('View study methodology');
    await expect(evidence.locator('.cos-pdp-evidence-link')).toHaveAttribute('href', '/science/clinical-studies/');

    // Ordering: benefits list, then evidence, then the Add to Bag form —
    // never the other way around.
    const order = await page.locator('.cos-pdp-info > *').evaluateAll(els =>
      els.map(e => e.className.split(' ')[0]).filter(c => ['cos-pdp-benefits-list', 'cos-pdp-evidence', 'cos-pdp-form'].includes(c))
    );
    expect(order).toEqual(['cos-pdp-benefits-list', 'cos-pdp-evidence', 'cos-pdp-form']);

    // The full clinical-results section further down the page is untouched
    // by this near-CTA block — both must be present simultaneously.
    await expect(page.locator('#pdp-clinical .cos-pdp-stat')).toHaveCount(1);
  });

  test('products without a verified statistic (cleanser, bundle) never show the near-CTA evidence block', async ({ page }) => {
    await gotoAndCheck(page, '/products/micellar-water-cleanser/');
    await expect(page.locator('.cos-pdp-evidence')).toHaveCount(0);

    await gotoAndCheck(page, '/products/the-complete-ritual/');
    await expect(page.locator('.cos-pdp-evidence')).toHaveCount(0);
  });

  test('sticky mobile Add to Bag bar stays hidden until scrolled past the real CTA, then reappears, and adds to the bag', async ({ page }) => {
    await page.setViewportSize({ width: 390, height: 844 });
    await gotoAndCheck(page, pdpUrl);

    const sticky = page.locator('#cos-pdp-sticky-atc');
    // Bug fix (UI/UX pass 2026-09-19): on mobile the product photo fills the
    // first viewport, so the real CTA starts below the fold. The bar used to
    // show immediately on load (before the visitor had even scrolled to the
    // real CTA) because the old logic showed it whenever the CTA merely
    // wasn't intersecting, without checking which side it was on. It must
    // now stay hidden here, at the very top of the page.
    await expect(page.locator('#cos-pdp-atc-btn')).toBeVisible();
    await expect(sticky).not.toHaveClass(/is-visible/);
    await expect(sticky).toHaveAttribute('aria-hidden', 'true');

    const btnBottom = await page.locator('#cos-pdp-atc-btn').evaluate(el => el.getBoundingClientRect().bottom + window.scrollY);

    // Scroll to the real CTA itself — still in view, still hidden.
    await scrollWindowIncrementally(page, btnBottom - 700);
    await expect(sticky).not.toHaveClass(/is-visible/);

    // Scroll well past it — now it should appear.
    await scrollWindowIncrementally(page, btnBottom + 900);
    await expect(sticky).toHaveClass(/is-visible/);
    await expect(sticky).toHaveAttribute('aria-hidden', 'false');
    await expect(sticky.locator('.cos-pdp-sticky-name')).toBeVisible();

    // Scrolling back up to the CTA hides it again (bidirectional, not a one-way latch).
    await scrollWindowIncrementally(page, 0);
    await expect(sticky).not.toHaveClass(/is-visible/);

    // Scroll past it again to reach the add-to-bag assertion.
    await scrollWindowIncrementally(page, btnBottom + 900);
    await expect(sticky).toHaveClass(/is-visible/);

    const ajaxAddToCart = page.waitForResponse(response => /[?&]wc-ajax=add_to_cart/i.test(response.url()), { timeout: 10_000 });
    await sticky.locator('.cos-pdp-sticky-btn').click();
    await ajaxAddToCart;
    await expect(page.locator('.cos-bag-count').first()).not.toHaveText('', { timeout: 10_000 });
  });

  test('sticky bar is not rendered on desktop viewports', async ({ page }) => {
    await page.setViewportSize({ width: 1440, height: 900 });
    await gotoAndCheck(page, pdpUrl);
    await page.locator('.cos-pdp-desc-section').scrollIntoViewIfNeeded();
    await expect(page.locator('#cos-pdp-sticky-atc')).not.toBeVisible();
  });
});

async function addProductToBagByUrl(page: import('@playwright/test').Page, path: string) {
  await gotoAndCheck(page, path);
  await page.locator('#cos-pdp-atc-btn').click();
  await page.waitForLoadState('networkidle');
}

test.describe('Cart: bundle saving, destination-aware shipping, and quantity/remove analytics', () => {
  test('order summary states the real bundle saving, real shipping destination, and a delivery-estimate note instead of a fake date', async ({ page }) => {
    await addProductToBagByUrl(page, '/products/the-complete-ritual/');
    await gotoAndCheck(page, '/cart/');

    await expect(page.locator('.cos-cp-row-saving')).toContainText('Complete Ritual saving');
    await expect(page.locator('.cos-cp-row-saving')).toContainText('€50');
    // Shipping is worldwide as of the 2026-09-11 owner decision (see
    // inc/shipping.php / OWNER-ACTIONS.md §10) — the cart's shipping row
    // reflects that via cos_child_shipping_message('short'), not a
    // single destination country.
    await expect(page.locator('.cos-cp-rows')).toContainText('worldwide');
    await expect(page.locator('.cos-cp-delivery-note')).toContainText('Delivery estimate shown at checkout');
  });

  test('the saving line is absent when the bundle is not in the cart', async ({ page }) => {
    await addProductToBagByUrl(page, '/products/advanced-repair-serum/');
    await gotoAndCheck(page, '/cart/');
    await expect(page.locator('.cos-cp-row-saving')).toHaveCount(0);
  });

  test('trust strip reflects the real enabled payment method and links the returns policy', async ({ page }) => {
    await addProductToBagByUrl(page, '/products/advanced-repair-serum/');
    await gotoAndCheck(page, '/cart/');
    await expect(page.locator('.cos-cp-trust')).toContainText('Pay on Delivery');
    await expect(page.locator('a.cos-cp-trust-item[href="/shipping-returns/"]')).toBeVisible();
  });

  test('increasing quantity fires an add_to_cart delta event with real product data', async ({ page }) => {
    await addProductToBagByUrl(page, '/products/advanced-repair-serum/');
    await gotoAndCheck(page, '/cart/');

    await page.locator('.cos-cp-qty-btn[aria-label="Increase quantity"]').click();
    await page.waitForLoadState('networkidle');

    const events = await page.evaluate(() => (window as any).dataLayer.filter((e: any) => e.event === 'add_to_cart'));
    expect(events.length).toBeGreaterThan(0);
    const last = events[events.length - 1];
    expect(last.items[0].item_id).toBe('COS-002');
    expect(last.items[0].quantity).toBe(1); // the delta, not the new running total of 2
  });

  test('removing an item fires a remove_from_cart event with real product data', async ({ page }) => {
    await addProductToBagByUrl(page, '/products/advanced-repair-serum/');
    await gotoAndCheck(page, '/cart/');

    await page.locator('.cos-cp-remove').click();
    await page.waitForLoadState('networkidle');
    expect(page.url()).toMatch(/removed_item=1/);

    const events = await page.evaluate(() => (window as any).dataLayer.filter((e: any) => e.event === 'remove_from_cart'));
    expect(events.length).toBeGreaterThan(0);
    expect(events[0].items[0].item_id).toBe('COS-002');

    // Refreshing the same URL must not re-fire the event a second time.
    await page.reload();
    await page.waitForLoadState('networkidle');
    const eventsAfterReload = await page.evaluate(() => (window as any).dataLayer.filter((e: any) => e.event === 'remove_from_cart'));
    expect(eventsAfterReload.length).toBe(0);
  });
});

test.describe('Checkout: coupon, collapsed optional fields, reassurance, and error recovery', () => {
  test.beforeEach(async ({ page }) => {
    await addProductToBagByUrl(page, '/products/advanced-repair-serum/');
    await gotoAndCheck(page, '/checkout/');
  });

  test('the coupon form is a real, non-nested <form> that the accessible toggle actually opens', async ({ page }) => {
    // Regression guard for a real bug: a previous customization hooked the
    // coupon form inside the outer checkout <form>, which is invalid HTML —
    // browsers silently drop nested <form> tags, so the toggle did nothing
    // and "Apply coupon" had no form to submit to.
    const nestedForms = await page.evaluate(() =>
      Array.from(document.querySelectorAll('form')).filter(f => f.parentElement?.closest('form')).length
    );
    expect(nestedForms).toBe(0);

    const couponForm = page.locator('form.checkout_coupon');
    await expect(couponForm).toHaveCount(1);
    await expect(couponForm).toBeHidden();

    const toggle = page.locator('.showcoupon');
    await expect(toggle).toHaveAttribute('aria-expanded', 'false');
    await toggle.click();
    await expect(couponForm).toBeVisible();
    await expect(toggle).toHaveAttribute('aria-expanded', 'true');
    await expect(page.locator('#coupon_code')).toHaveCount(1);
  });

  test('address line 2 and order notes are collapsed behind accessible toggles by default', async ({ page }) => {
    const addr2 = page.locator('#billing_address_2_field');
    const notes = page.locator('#order_comments_field');
    await expect(addr2).toBeHidden();
    await expect(notes).toBeHidden();

    const addr2Toggle = page.locator('.cos-co-field-toggle[aria-controls="billing_address_2_field"]');
    await expect(addr2Toggle).toHaveAttribute('aria-expanded', 'false');
    await addr2Toggle.click();
    await expect(addr2).toBeVisible();
    await expect(addr2Toggle).toHaveAttribute('aria-expanded', 'true');
    await expect(page.locator('#billing_address_2')).toBeFocused();
  });

  test('standard address fields carry autocomplete attributes', async ({ page }) => {
    await expect(page.locator('#billing_first_name')).toHaveAttribute('autocomplete', /given-name/);
    await expect(page.locator('#billing_email')).toHaveAttribute('autocomplete', /email/);
    await expect(page.locator('#billing_postcode')).toHaveAttribute('autocomplete', /postal-code/);
  });

  test('a reassurance line naming the real payment method and a returns link sits under Place Order', async ({ page }) => {
    const reassurance = page.locator('.cos-co-reassurance');
    await expect(reassurance).toContainText('Pay on Delivery');
    await expect(reassurance).toContainText('30-Day Returns');
    await expect(reassurance.locator('a')).toHaveAttribute('href', '/shipping-returns/');
  });

  test('an invalid submission shows an error summary, focuses the invalid field, preserves entered data, and fires checkout_error', async ({ page }) => {
    await page.fill('#billing_first_name', 'Test');
    await page.fill('#billing_last_name', 'User');
    await page.fill('#billing_address_1', 'Teststrasse 1');
    await page.fill('#billing_postcode', '6300');
    await page.fill('#billing_city', 'Zug');
    await page.fill('#billing_email', 'not-a-valid-email');
    await page.check('#terms');

    await page.evaluate(() => { (window as any).dataLayer.length = 0; });
    await page.click('#place_order');
    await expect(page.locator('.woocommerce-NoticeGroup-checkout')).toBeVisible({ timeout: 10_000 });

    await expect(page.locator('#billing_email')).toBeFocused();
    await expect(page.locator('#billing_first_name')).toHaveValue('Test');
    await expect(page.locator('#billing_address_1')).toHaveValue('Teststrasse 1');

    const events = await page.evaluate(() => (window as any).dataLayer.filter((e: any) => e.event === 'checkout_error'));
    expect(events.length).toBeGreaterThan(0);
    expect(events[0].error_message).toContain('email');
  });
});

test.describe('Sitewide cta_click tracking', () => {
  // Every data-cro-cta target here navigates away on click (they're real
  // links/submit buttons), which clears window.dataLayer before a
  // post-click read could see the push — so the dataLayer.push override is
  // installed first and reports pushes back synchronously as they happen,
  // rather than reading the array after the fact.
  async function captureNextCtaClick(page: import('@playwright/test').Page, selector: string) {
    const pushed: any[] = [];
    await page.exposeFunction('__reportPush', (args: any) => pushed.push(args));
    await page.evaluate(() => {
      const orig = (window as any).dataLayer.push.bind((window as any).dataLayer);
      (window as any).dataLayer.push = function (...args: any[]) {
        (window as any).__reportPush(args);
        return orig(...args);
      };
    });
    await page.locator(selector).click();
    await page.waitForTimeout(300);
    return pushed.flat().filter((e: any) => e?.event === 'cta_click');
  }

  test('clicking the homepage primary CTA fires cta_click with cta_name, page, and destination', async ({ page }) => {
    await gotoAndCheck(page, '/');
    const events = await captureNextCtaClick(page, '[data-cro-cta="home-hero-primary"]');
    expect(events).toEqual([
      { event: 'cta_click', cta_name: 'home-hero-primary', page: '/', destination: '/products/the-complete-ritual/' },
    ]);
  });

  test('clicking the PDP secondary CTA fires cta_click scoped to the product page', async ({ page }) => {
    await gotoAndCheck(page, '/products/advanced-repair-serum/');
    const events = await captureNextCtaClick(page, '[data-cro-cta="pdp-secondary-learn-ritual"]');
    expect(events.length).toBe(1);
    expect(events[0].cta_name).toBe('pdp-secondary-learn-ritual');
    expect(events[0].page).toBe('/products/advanced-repair-serum/');
  });

  test('every CTA named in the hierarchy table carries a data-cro-cta attribute', async ({ page }) => {
    await gotoAndCheck(page, '/');
    await expect(page.locator('[data-cro-cta="home-hero-primary"]')).toHaveCount(1);
    await expect(page.locator('[data-cro-cta="home-hero-secondary"]')).toHaveCount(1);

    await gotoAndCheck(page, '/shop/');
    await expect(page.locator('[data-cro-cta="shop-hero-primary"]')).toHaveCount(1);

    await gotoAndCheck(page, '/products/advanced-repair-serum/');
    await expect(page.locator('[data-cro-cta="pdp-primary-atc"]')).toHaveCount(1);
    await expect(page.locator('[data-cro-cta="pdp-secondary-learn-ritual"]')).toHaveCount(1);

    await addProductToBagByUrl(page, '/products/advanced-repair-serum/');
    await gotoAndCheck(page, '/cart/');
    await expect(page.locator('[data-cro-cta="cart-checkout"]')).toHaveCount(1);

    await gotoAndCheck(page, '/checkout/');
    await expect(page.locator('[data-cro-cta="checkout-place-order"]')).toHaveCount(1);
  });
});

test.describe('Footer trust signals', () => {
  test('footer states the confirmed hosting/security facts as plain line-icon badges, never a third-party seal', async ({ page }) => {
    await gotoAndCheck(page, '/');
    const badges = page.locator('.cos-footer-security-item');
    await expect(badges).toHaveCount(3);
    await expect(badges.nth(0)).toContainText('SSL-Encrypted Checkout');
    await expect(badges.nth(1)).toContainText('WAF + DDoS Protection');
    await expect(badges.nth(2)).toContainText('Daily Malware Scanning');
    for (let i = 0; i < 3; i++) {
      await expect(badges.nth(i).locator('svg')).toHaveCount(1);
    }

    // Regression guard: never claim a specific third-party certification
    // (Norton/McAfee/TRUSTe/etc.) unless this site is actually enrolled in
    // it — a look-alike badge for a service not actually subscribed to is
    // trademark misuse and a fabricated trust signal, not just a copy nit.
    const footerText = await page.locator('#cos-footer').innerText();
    expect(footerText).not.toMatch(/norton|mcafee|trustpilot|verisign|truste\b/i);
  });
});

test.describe('The Ritual: hero CTA, post-steps CTA, and per-step product links', () => {
  test('hero has a primary CTA to the bundle, stating the real saving', async ({ page }) => {
    await gotoAndCheck(page, '/the-ritual/');
    const cta = page.locator('[data-cro-cta="ritual-hero-primary"]');
    await expect(cta).toHaveText('Shop the Complete Ritual — Save €50');
    await expect(cta).toHaveAttribute('href', '/products/the-complete-ritual/');
  });

  test('each of the 4 steps links to its real product page', async ({ page }) => {
    await gotoAndCheck(page, '/the-ritual/');
    const expected = [
      ['1', '/products/micellar-water-cleanser/'],
      ['2', '/products/advanced-repair-serum/'],
      ['3', '/products/revitalizing-eye-cream/'],
      ['4', '/products/24h-regeneration-cream/'],
    ];
    for (const [n, href] of expected) {
      const link = page.locator(`[data-cro-cta="ritual-step-${n}-view-product"]`);
      await expect(link).toHaveAttribute('href', href);
      await expect(link).toHaveText('View product →');
    }
  });

  test('a second primary CTA sits after the four steps, and the secondary Science CTA is unchanged', async ({ page }) => {
    await gotoAndCheck(page, '/the-ritual/');
    const postCta = page.locator('[data-cro-cta="ritual-poststeps-primary"]');
    await expect(postCta).toHaveAttribute('href', '/products/the-complete-ritual/');

    // Regression guard: the post-steps CTA must sit between the steps
    // section and "Why the Sequence Matters", not appear before step 1.
    const stepsBottom = await page.locator('#crv3-step-4').evaluate(el => el.getBoundingClientRect().bottom);
    const ctaTop = await postCta.evaluate(el => el.getBoundingClientRect().top);
    expect(ctaTop).toBeGreaterThanOrEqual(stepsBottom);

    const secondary = page.locator('a', { hasText: 'Explore Swiss Science' });
    await expect(secondary).toHaveAttribute('href', '/science/');
    await expect(secondary).toHaveClass(/crv3-btn-dark/);
  });
});

test.describe('PDP in-page contents menu', () => {
  test('links reflect exactly the sections actually present, in real page order, and each one scrolls to a real target', async ({ page }) => {
    await gotoAndCheck(page, '/products/advanced-repair-serum/');
    const links = page.locator('.cos-pdp-jump-link');
    const count = await links.count();
    expect(count).toBeGreaterThan(3);

    for (let i = 0; i < count; i++) {
      const href = await links.nth(i).getAttribute('href');
      const id = href!.replace('#', '');
      await expect(page.locator('#' + id), `${href} should point to a real element`).toHaveCount(1);
    }
  });

  test('a product with no clinical stats omits "Clinical Results" from the menu, matching the section it actually has', async ({ page }) => {
    await gotoAndCheck(page, '/products/micellar-water-cleanser/');
    await expect(page.locator('.cos-pdp-jump-link', { hasText: 'Clinical Results' })).toHaveCount(0);
    await expect(page.locator('#pdp-clinical')).toHaveCount(0);
  });

  test('the bundle menu includes its own extra sections without duplicating shared ones', async ({ page }) => {
    await gotoAndCheck(page, '/products/the-complete-ritual/');
    const labels = await page.locator('.cos-pdp-jump-link').allTextContents();
    expect(labels).toContain("What's Included");
    expect(labels).toContain('Price Comparison');
    expect(labels).toContain('Why It Works');
    // Clinical Results and FAQ each appear once, not twice (common section
    // + bundle-specific section are mutually exclusive per product).
    expect(labels.filter(l => l === 'Clinical Results').length).toBe(1);
    expect(labels.filter(l => l === 'FAQ').length).toBe(1);
  });

  test('clicking a link actually scrolls its target section into view', async ({ page }) => {
    await page.setViewportSize({ width: 390, height: 844 });
    await gotoAndCheck(page, '/products/advanced-repair-serum/');
    const faqLink = page.locator('.cos-pdp-jump-link', { hasText: 'FAQ' });
    await faqLink.click();
    await page.waitForTimeout(300);
    const inView = await page.locator('#pdp-faq').evaluate(el => {
      const r = el.getBoundingClientRect();
      return r.top < window.innerHeight && r.bottom > 0;
    });
    expect(inView).toBe(true);
  });
});
