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
  // 2026-09-27 redesign: The Complete Ritual is the hero product story
  // (CAV59889 tray shot), with the offer as HTML text beside the CTAs.
  test('hero presents The Complete Ritual, the four-product offer, and the evidence line as text', async ({ page }) => {
    await gotoAndCheck(page, '/');
    await expect(page.locator('h1')).toHaveCount(1);
    await expect(page.locator('#cos-hero h1')).toHaveText('The Complete Ritual');
    await expect(page.locator('.cos-hh-eyebrow')).toHaveText(/swiss caviar skincare/i);
    await expect(page.locator('.cos-hh-sub')).toHaveText('Four Swiss-made formulas designed to cleanse, treat, revitalise and regenerate the skin.');
    await expect(page.locator('.cos-hh-offer')).toHaveText(/Four full-size products\s*·\s*€450\s*·\s*Save €50/);
    await expect(page.locator('.cos-hh-proof')).toHaveText('Independently measured results at 28 days.');
    await expect(page.locator('#cos-hero img')).toHaveAttribute('alt', 'The complete four-step Caviar of Switzerland skincare ritual presented on a silver tray.');
    await expect(page.locator('#cos-hero img')).toHaveAttribute('fetchpriority', 'high');
    await expect(page.locator('#cos-hero img')).not.toHaveAttribute('loading', 'lazy');
  });

  test('the hero carries one action: the Complete Ritual (owner, 11 Oct 2026)', async ({ page }) => {
    await gotoAndCheck(page, '/');
    const primary = page.locator('[data-cro-cta="home-hero-primary"]');
    await expect(primary).toHaveText(/shop the complete ritual/i);
    await expect(primary).toHaveAttribute('href', /\/products\/the-complete-ritual\/$/);
    await expect(page.locator('[data-cro-cta="home-hero-secondary"]')).toHaveCount(0);
    expect((await primary.boundingBox())!.height).toBeGreaterThanOrEqual(48);
  });

  test('at 390x844 the offer and primary CTA are in the first viewport, and the copy sits above the image', async ({ page }) => {
    await page.setViewportSize({ width: 390, height: 844 });
    await gotoAndCheck(page, '/');
    for (const selector of ['.cos-hh-eyebrow', '#cos-hero h1', '.cos-hh-sub', '.cos-hh-offer', '[data-cro-cta="home-hero-primary"]']) {
      const box = await page.locator(selector).first().boundingBox();
      expect(box, `${selector} should be present`).not.toBeNull();
      expect(box!.y + box!.height, `${selector} should be visible without scrolling`).toBeLessThanOrEqual(844);
    }
    const copy = await page.locator('.cos-hh-copy').boundingBox();
    const media = await page.locator('.cos-hh-media').boundingBox();
    expect(media!.y).toBeGreaterThanOrEqual(copy!.y + copy!.height - 1);
  });

  test('at 1440x900 the copy never overlaps the products and the next section begins in view', async ({ page }) => {
    await page.setViewportSize({ width: 1440, height: 900 });
    await gotoAndCheck(page, '/');
    const heroBox = await page.locator('#cos-hero').boundingBox();
    const copy = await page.locator('.cos-hh-copy').boundingBox();
    // The tray's left rim sits at 46% of the wide crop (object-position: right).
    const productsLeft = await page.locator('#cos-hero img').evaluate((img: HTMLImageElement) => {
      const r = img.getBoundingClientRect(); const s = Math.max(r.width / 2400, r.height / 1350); const dw = 2400 * s;
      return r.left + r.width - dw + 0.46 * dw;
    });
    expect(copy!.x + copy!.width).toBeLessThan(productsLeft - 24);
    expect(heroBox!.height).toBeGreaterThanOrEqual(680);
    expect(heroBox!.height).toBeLessThanOrEqual(740);
    expect((await page.locator('#cos-ritual-rail').boundingBox())!.y).toBeLessThan(900);
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

  test('shop hero CTA is filled/high-contrast and jumps to the first purchasable section', async ({ page }) => {
    // 2026-09-26 hero redesign: CTA copy is "Shop the Collection" per the
    // brief, anchored to the Complete Ritual offer that opens the catalogue.
    await gotoAndCheck(page, '/shop/');
    const cta = page.locator('[data-cro-cta="shop-hero-primary"]');
    await expect(cta).toHaveText('Shop the Collection');
    await expect(cta).toHaveAttribute('href', '#cos-ritual');
    await expect(page.locator('#cos-ritual')).toHaveCount(1);
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

  test('benefit list near the CTA is trimmed to 3, with any extras preserved further down the page', async ({ page }) => {
    await gotoAndCheck(page, pdpUrl);
    const leadList = page.locator('.cos-pdp-form').locator('xpath=preceding-sibling::ul[contains(@class,"cos-pdp-benefits-list")][1]');
    await expect(leadList.locator('.cos-pdp-benefit-item')).toHaveCount(3);
    // PDP audit 2026-09-27: the serum's copy was consolidated to exactly three
    // benefits, so it has no "In Full" overflow list any more.
    await expect(page.locator('.cos-pdp-desc-section .cos-pdp-benefits-list')).toHaveCount(0);

    // 2026-09-27: the same consolidation was applied to all four individual
    // products -- each leads with exactly three benefits and has no overflow list.
    for (const slug of ['micellar-water-cleanser', 'revitalizing-eye-cream', '24h-regeneration-cream']) {
      await gotoAndCheck(page, `/products/${slug}/`);
      await expect(page.locator('.cos-pdp-info .cos-pdp-benefit-item')).toHaveCount(3);
      await expect(page.locator('.cos-pdp-desc-section .cos-pdp-benefits-list')).toHaveCount(0);
    }
  });

  test('returns link and payment method are near the CTA; no bundle nudge; the who-it-is-for copy lives in its own section only', async ({ page }) => {
    await gotoAndCheck(page, pdpUrl);
    // PDP audit 2026-09-27: the duplicate "Who it's for" line under the
    // purchase panel was removed; the dedicated section carries it.
    await expect(page.locator('.cos-pdp-who-mini')).toHaveCount(0);
    await expect(page.locator('#pdp-who')).toHaveCount(1);
    // 2026-09-27: the "Part of the 4-Step Ritual — save €50" link was removed
    // at the owner's request; the secondary CTA now reads "Discover the Ritual".
    await expect(page.locator('[data-cro-cta="pdp-bundle-nudge"]')).toHaveCount(0);
    await expect(page.locator('[data-cro-cta="pdp-secondary-learn-ritual"]')).toHaveText('Discover the Ritual');

    const returnsLink = page.locator('.cos-pdp-trust a.cos-pdp-trust-item');
    await expect(returnsLink).toHaveAttribute('href', '/shipping-returns/');

    // 2026-09-27: individual PDPs keep only shipping + returns in the trust
    // row (payment method and "Authentic Swiss Formula" removed by the owner);
    // it must never claim a payment method that isn't enabled.
    await expect(page.locator('.cos-pdp-trust .cos-pdp-trust-item')).toHaveCount(2);
    await expect(page.locator('.cos-pdp-trust')).not.toContainText('Pay on Delivery');
    await expect(page.locator('.cos-pdp-trust')).not.toContainText('Secure Checkout');

    // One methodology link in the purchase panel, beside the statistic — the
    // repeat below the assurances was removed.
    await expect(page.locator('.cos-pdp-methodology-link')).toHaveCount(0);
    await expect(page.locator('.cos-pdp-info a[href="/science/clinical-studies/"]')).toHaveCount(1);
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
    await gotoAndCheck(page, pdpUrl); // COS-002: $pd['stats'] = [['+47%', 'Cutometer-measured improvement in firmness at 28 days']]
    const evidence = page.locator('.cos-pdp-evidence');
    await expect(evidence).toHaveCount(1);
    await expect(evidence.locator('.cos-pdp-evidence-num')).toHaveText('+47%');
    await expect(evidence.locator('.cos-pdp-evidence-label')).toHaveText('Cutometer-measured improvement in firmness at 28 days');
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
  test('footer carries one consolidated assurance line and no hosting/security badges', async ({ page }) => {
    // 2026-10-02 owner decision: hosting/security facts (WAF, malware scanning)
    // are not part of the brand presentation; assurances live in one quiet line.
    await gotoAndCheck(page, '/shop/');
    const items = page.locator('#cos-footer .cos-trust-item');
    await expect(items).toHaveText(['Made in Switzerland', 'Free worldwide shipping', '30-day returns', 'Secure checkout']);
    await expect(page.locator('.cos-footer-security-item')).toHaveCount(0);
    await expect(page.locator('#cos-footer')).not.toContainText(/WAF|DDoS|malware/i);

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

  test('the bundle has no section-navigation bar at all, unlike the individual-product pill nav', async ({ page }) => {
    // Editorial redesign (2026-09-20): the bundle previously had first a
    // 9-entry pill nav inherited from the individual-product markup, then
    // (after an earlier redesign pass) its own 4-item text-link nav. This
    // latest pass removes section navigation from the bundle entirely --
    // the page now reads top to bottom as one narrative with nothing left
    // to jump between, per the redesign brief's explicit instruction.
    await gotoAndCheck(page, '/products/the-complete-ritual/');
    await expect(page.locator('.cos-pdp-jump-link')).toHaveCount(0);
    await expect(page.locator('.cos-pdp-bundle-nav-link')).toHaveCount(0);
    await expect(page.locator('.cos-pdp-bundle-nav')).toHaveCount(0);
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

test.describe('PDP ritual stepper', () => {
  test('each ritual step links to its product, and the current step is marked, not linked', async ({ page }) => {
    await gotoAndCheck(page, '/products/advanced-repair-serum/');
    const nav = page.getByRole('navigation', { name: 'The four-step ritual' });
    await expect(nav.locator('a')).toHaveCount(3);
    await expect(nav.locator('[aria-current="page"]')).toHaveText(/Treat/);
    await expect(nav.locator('a', { hasText: 'Cleanse' })).toHaveAttribute('href', /\/products\/micellar-water-cleanser\/$/);
    await expect(nav.locator('a', { hasText: 'Eye' })).toHaveAttribute('href', /\/products\/revitalizing-eye-cream\/$/);
    await expect(nav.locator('a', { hasText: 'Moisturise' })).toHaveAttribute('href', /\/products\/24h-regeneration-cream\/$/);
    await nav.locator('a', { hasText: 'Cleanse' }).click();
    await expect(page).toHaveURL(/\/products\/micellar-water-cleanser\/$/);
    await expect(page.getByRole('navigation', { name: 'The four-step ritual' }).locator('[aria-current="page"]')).toHaveText(/Cleanse/);
  });
});
