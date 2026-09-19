import { expect, test } from '@playwright/test';
import {
  addFirstProductToBag,
  checkSameOriginAssets,
  criticalRoutes,
  discoverProductLinks,
  expectNoHorizontalOverflow,
  expectRequiredSeo,
  expectSingleViewportMeta,
  gotoAndCheck
} from './helpers/cos-helpers';

test.describe('SEO, assets, and browser integrity scenarios', () => {
  for (const route of criticalRoutes.filter(route => route.path !== '/journal/')) {
    test(`${route.name} has SEO metadata and a valid mobile viewport`, async ({ page }) => {
      await gotoAndCheck(page, route.path);

      await expectRequiredSeo(page);
      await expectSingleViewportMeta(page);
    });
  }

  test('product and page structured data parse as JSON-LD with breadcrumb coverage', async ({ page }) => {
    const targets = ['/', '/shop/', '/science/'];
    const productLinks = await discoverProductLinks(page);
    targets.push(...productLinks.slice(0, 2));

    for (const path of targets) {
      await gotoAndCheck(page, path);

      const scripts = await page.locator('script[type="application/ld+json"]').allTextContents();
      expect(scripts.length, `${path} should include JSON-LD`).toBeGreaterThan(0);

      const parsed = scripts.map((script, index) => {
        try {
          return JSON.parse(script);
        } catch (error) {
          throw new Error(`${path} JSON-LD script ${index + 1} is not valid JSON: ${(error as Error).message}`);
        }
      });

      const hasBreadcrumb = parsed.some(entry => {
        const graph = Array.isArray(entry['@graph']) ? entry['@graph'] : [entry];
        return graph.some((node: Record<string, unknown>) => node['@type'] === 'BreadcrumbList');
      });
      expect(hasBreadcrumb, `${path} should include BreadcrumbList schema`).toBe(true);
    }
  });

  test('critical page assets are not broken', async ({ page }) => {
    test.slow();

    for (const path of ['/', '/shop/', '/cart/', '/account/']) {
      await gotoAndCheck(page, path);
      await checkSameOriginAssets(page);
    }
  });

  test('core pages stay within viewport on narrow and wide screens', async ({ page }) => {
    // Populate the cart once up front (persists via cookies/session for the
    // rest of this test) so /checkout/ renders its real form instead of
    // redirecting to the empty cart. No order is ever placed.
    await addFirstProductToBag(page);
    const productLinks = await discoverProductLinks(page);
    const pdpPath = productLinks[0] ?? '/shop/';

    for (const viewport of [
      { width: 390, height: 844 },
      { width: 768, height: 1024 },
      { width: 1440, height: 950 }
    ]) {
      await page.setViewportSize(viewport);
      for (const path of ['/', '/shop/', '/cart/', '/account/', pdpPath, '/contact/', '/checkout/']) {
        await gotoAndCheck(page, path);
        await expectNoHorizontalOverflow(page);
      }
    }
  });

  test('analytics data layer and local cart helpers initialise without stale badge inflation', async ({ page }) => {
    await gotoAndCheck(page, '/');

    const initialState = await page.evaluate(() => {
      const win = window as Window & {
        cos?: {
          products?: Record<string, unknown>;
          addToCart?: (slug: string, qty?: number, subscribe?: boolean) => unknown;
          updateBagCount?: () => void;
        };
        dataLayer?: unknown[];
        cosWooCount?: number;
      };

      return {
        hasCos: !!win.cos,
        hasProducts: !!win.cos?.products && Object.keys(win.cos.products).length >= 5,
        hasAddToCart: typeof win.cos?.addToCart === 'function',
        hasUpdateBagCount: typeof win.cos?.updateBagCount === 'function',
        hasDataLayer: Array.isArray(win.dataLayer),
        wooCount: win.cosWooCount || 0
      };
    });

    expect(initialState).toEqual({
      hasCos: true,
      hasProducts: true,
      hasAddToCart: true,
      hasUpdateBagCount: true,
      hasDataLayer: true,
      wooCount: 0
    });

    await page.evaluate(() => {
      const win = window as Window & {
        cos: {
          addToCart: (slug: string, qty?: number, subscribe?: boolean) => unknown;
          updateBagCount: () => void;
        };
      };
      win.cos.addToCart('advanced-repair-serum', 2, false);
      win.cos.updateBagCount();
    });

    const browserCartState = await page.evaluate(() => ({
      cart: JSON.parse(localStorage.getItem('cos_cart') || '[]'),
      badgeText: document.querySelector('.cos-bag-count')?.textContent || ''
    }));

    expect(browserCartState.cart).toMatchObject([
      {
        id: 'COS-002',
        slug: 'advanced-repair-serum',
        qty: 2
      }
    ]);
    expect(browserCartState.badgeText, 'Bag badge should reflect WooCommerce, not stale localStorage').toBe('');
  });

  test('mobile header logo stays centered and clear of the nav icon group', async ({ page }) => {
    // .cos-nav-logo is truly centered on the nav width at every mobile/
    // tablet width (.cos-nav-logo's own position never changes), but the
    // hamburger corner (64px) and the icon group (152px, 3 x 44px WCAG
    // targets with no gap) occupy very different widths, so the genuinely
    // clear middle space shrinks fast below ~600px. The logo's own size
    // (inc/navigation.php's clamp(21px,38.2vw - 122px,42px)) scales down
    // to fit that space instead of the logo overlapping the icons the way
    // it used to on real phones (~375-412px, the majority of devices).
    for (const width of [360, 375, 390, 412, 414, 480, 600, 768, 900, 1023]) {
      await page.setViewportSize({ width, height: 900 });
      await gotoAndCheck(page, '/');
      const logoBox = await page.locator('.cos-nav-logo').boundingBox();
      // .cos-nav-utils a.first() used to target this, but at every width in
      // this loop (<=1023px) the Help and Account links are display:none
      // (see inc/navigation.php's @media(max-width:1023px) rule) — the Bag
      // link is the actual leftmost visible icon, so target it directly
      // rather than relying on DOM order among links that include hidden ones.
      const firstIconBox = await page.locator('.cos-bag-link').boundingBox();
      expect(logoBox, `Logo box at ${width}px`).not.toBeNull();
      expect(firstIconBox, `First nav icon box at ${width}px`).not.toBeNull();
      expect(
        logoBox!.x + logoBox!.width,
        `Logo right edge should not overlap the nav icon group at ${width}px`
      ).toBeLessThanOrEqual(firstIconBox!.x + 1);
      expect(
        logoBox!.x + logoBox!.width / 2,
        `Logo should be horizontally centered in the nav at ${width}px`
      ).toBeCloseTo(width / 2, 0);
    }

    // 320px (iPhone SE 1st-gen, no longer supported by current iOS) is a
    // documented, deliberate exception: the icon group alone leaves under
    // 16px of true-centered space, less than any legible logo can fit
    // into. Assert the residual stays small (a fraction of the original
    // ~47px bug), not that it's eliminated outright.
    await page.setViewportSize({ width: 320, height: 900 });
    await gotoAndCheck(page, '/');
    const logoBoxNarrow = await page.locator('.cos-nav-logo').boundingBox();
    const firstIconBoxNarrow = await page.locator('.cos-bag-link').boundingBox();
    expect(logoBoxNarrow && firstIconBoxNarrow).toBeTruthy();
    expect(
      logoBoxNarrow!.x + logoBoxNarrow!.width - firstIconBoxNarrow!.x,
      'Residual 320px overlap should stay well below the original ~47px bug'
    ).toBeLessThanOrEqual(25);
  });

  test('footer newsletter input never collapses to a cramped width once it goes horizontal', async ({ page }) => {
    // The row stays stacked (full-width input) below 1024px, so the 220px
    // floor only needs to hold from 1024px up, where .cos-nl-row switches to
    // minmax(220px,1fr) auto.
    for (const width of [1024, 1200, 1440]) {
      await page.setViewportSize({ width, height: 900 });
      await gotoAndCheck(page, '/');
      const box = await page.locator('.cos-nl-input').first().boundingBox();
      expect(box?.width ?? 0, `Newsletter input width at ${width}px`).toBeGreaterThanOrEqual(220);
    }
  });

  test('PDP gallery image is not stretched to match a taller info column', async ({ page }) => {
    for (const slug of ['advanced-repair-serum', 'the-complete-ritual']) {
      for (const width of [1024, 1440]) {
        await page.setViewportSize({ width, height: 900 });
        await gotoAndCheck(page, `/products/${slug}/`);

        const galleryBox = await page.locator('.cos-pdp-gallery-main').boundingBox();
        const infoBox = await page.locator('.cos-pdp-info').boundingBox();
        expect(galleryBox, `${slug} gallery box at ${width}px`).not.toBeNull();
        expect(infoBox, `${slug} info box at ${width}px`).not.toBeNull();

        // A stretched gallery would match .cos-pdp-info's full (often much
        // taller, copy-driven) height. A correctly aspect-ratio-boxed gallery
        // stays close to its own 4:5 ratio regardless of how tall the info
        // column next to it grows.
        expect(
          galleryBox!.height,
          `${slug} gallery height at ${width}px should track its own aspect ratio, not .cos-pdp-info's height`
        ).toBeLessThanOrEqual(galleryBox!.width * 1.35);
      }
    }
  });

  test('mobile cart item image matches its narrowed grid track with a real gap to the details', async ({ page }) => {
    await page.setViewportSize({ width: 390, height: 844 });
    await addFirstProductToBag(page);

    const imgBox = await page.locator('.cos-cp-item-img').first().boundingBox();
    const nameBox = await page.locator('.cos-cp-item-name').first().boundingBox();
    expect(imgBox, 'Cart item image box').not.toBeNull();
    expect(nameBox, 'Cart item name box').not.toBeNull();

    expect(imgBox!.width, 'Cart item image width should match the 72px mobile track, not overflow it').toBeLessThanOrEqual(76);
    expect(
      nameBox!.x - (imgBox!.x + imgBox!.width),
      'Gap between cart item image and product details'
    ).toBeGreaterThanOrEqual(16);
  });

  test('checkout form children never exceed the form width, and billing fields stay usable at 320px', async ({ page }) => {
    await page.setViewportSize({ width: 320, height: 844 });
    await addFirstProductToBag(page);
    await gotoAndCheck(page, '/checkout/');

    const overflowByChild = await page.evaluate(() => {
      const form = document.querySelector('form.checkout');
      if (!form) return null;
      const formRight = form.getBoundingClientRect().right;
      return Array.from(form.children).map(child => {
        const rect = (child as HTMLElement).getBoundingClientRect();
        return Math.round(rect.right - formRight);
      });
    });
    expect(overflowByChild, 'form.checkout should render its direct children').not.toBeNull();
    for (const overflow of overflowByChild ?? []) {
      expect(overflow, 'form.checkout child should not exceed the form width').toBeLessThanOrEqual(2);
    }

    // Scoped to #customer_details specifically, not just "the first
    // input.input-text on the page" — the checkout coupon form (collapsed
    // behind a toggle by default, WooCommerce's own correct behavior)
    // renders before the billing fields in DOM order, so an unscoped
    // .first() here would grab the hidden, width:0 coupon field instead.
    const billingField = page.locator('#customer_details input.input-text').first();
    const billingBox = await billingField.boundingBox();
    expect(billingBox?.width ?? 0, 'Billing field width at 320px').toBeGreaterThanOrEqual(220);
  });
});
