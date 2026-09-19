import { expect, test } from '@playwright/test';
import { gotoAndCheck } from './helpers/cos-helpers';

test.describe('PDP image/video performance and content integrity', () => {
  test('individual product images have intrinsic width/height and the gallery hero is eager/high-priority', async ({ page }) => {
    await gotoAndCheck(page, '/products/micellar-water-cleanser/');

    const main = page.locator('#cos-gallery-main');
    await expect(main).toHaveAttribute('width', /\d+/);
    await expect(main).toHaveAttribute('height', /\d+/);
    await expect(main).toHaveAttribute('fetchpriority', 'high');

    const dims = await page.evaluate(() => {
      const imgs = Array.from(document.querySelectorAll('#cos-pdp img'));
      return imgs.map(img => ({
        src: img.getAttribute('src'),
        hasWidth: img.hasAttribute('width'),
        hasHeight: img.hasAttribute('height')
      }));
    });
    expect(dims.length).toBeGreaterThan(0);
    const missing = dims.filter(d => !d.hasWidth || !d.hasHeight);
    expect(missing, `Images missing intrinsic dimensions: ${JSON.stringify(missing)}`).toEqual([]);
  });

  test('the gallery main image actually swaps photo on dot click (not just its src attribute)', async ({ page }) => {
    // Pre-existing bug, found 2026-08-18 while investigating an unrelated AVIF/WebP
    // change: #cos-gallery-main keeps its srcset/sizes from the initial (first
    // photo) render, and per spec a valid srcset always wins over a plain src —
    // so cosGallerySet() was setting img.src to the clicked photo's URL but the
    // browser kept resolving img.currentSrc (what's actually displayed) against
    // the FIRST photo's srcset regardless of which dot/thumbnail was clicked.
    // Fixed by having cosGallerySet() clear srcset/sizes before setting src. If
    // this regresses, the gallery is silently showing the wrong product photo.
    await gotoAndCheck(page, '/products/the-complete-ritual/');

    const main = page.locator('#cos-gallery-main');
    const initialSrc = await main.evaluate((img: HTMLImageElement) => img.currentSrc);

    await page.locator('.cos-pdp-dot').nth(1).click();
    await page.waitForTimeout(400); // matches cosGallerySet()'s 240ms fade + buffer

    const swappedCurrentSrc = await main.evaluate((img: HTMLImageElement) => img.currentSrc);
    expect(swappedCurrentSrc, 'clicking a gallery dot should change the actually-rendered photo').not.toBe(initialSrc);
  });

  test('PDP gallery hero and shop hero serve responsive srcset/sizes, and only the true LCP image is high priority', async ({ page }) => {
    await gotoAndCheck(page, '/products/advanced-repair-serum/');
    const main = page.locator('#cos-gallery-main');
    await expect(main).toHaveAttribute('srcset', /\d+w/);
    await expect(main).toHaveAttribute('sizes', /vw/);

    // Only the gallery hero should be eager/high-priority — every other PDP
    // image (thumbnails, below-the-fold editorial images) must not compete
    // with it for bandwidth on the critical rendering path.
    const highPriorityCount = await page.locator('#cos-pdp img[fetchpriority="high"]').count();
    expect(highPriorityCount, 'Exactly one image (the LCP gallery hero) should be fetchpriority=high').toBe(1);
    const lazyBelowFold = await page.locator('#cos-pdp img:not(#cos-gallery-main)').evaluateAll(
      nodes => nodes.filter(n => n.getAttribute('loading') !== 'lazy').length
    );
    expect(lazyBelowFold, 'Every PDP image other than the gallery hero should be lazy-loaded').toBe(0);

    await gotoAndCheck(page, '/shop/');
    const hero = page.locator('.cos-shop-hero-img');
    await expect(hero).toHaveAttribute('srcset', /\d+w/);
    await expect(hero).toHaveAttribute('sizes', /.+/);
    await expect(hero).toHaveAttribute('fetchpriority', 'high');
  });

  test('the bundle product video is not fetched until the visitor activates it', async ({ page }) => {
    const requests: string[] = [];
    page.on('request', req => {
      if (/the-complete-ritual-video|\.mp4(\?|$)/i.test(req.url())) requests.push(req.url());
    });

    await gotoAndCheck(page, '/products/the-complete-ritual/');

    const video = page.locator('#cos-gallery-video');
    await expect(video).toHaveAttribute('preload', 'none');
    await expect(video).toHaveAttribute('data-src', /.+/);
    const srcAttr = await video.getAttribute('src');
    expect(srcAttr, 'Video should have no eager src before activation').toBeFalsy();
    expect(requests, 'No video bytes should be requested before activation').toEqual([]);

    // Activate the video slide via the (visible) dot control — the thumbnail
    // strip is display:none by design on this site, dots are the real nav.
    const videoDot = page.locator('.cos-pdp-dot[aria-label="Product video"]');
    if (await videoDot.count()) {
      await videoDot.click();
      await page.waitForTimeout(500);
      expect(requests.length, 'Video should be requested after activation').toBeGreaterThan(0);
    }
  });

  test('bundle no longer shows undisclosed clinical figures, and backed figures link to methodology', async ({ page }) => {
    await gotoAndCheck(page, '/products/the-complete-ritual/');
    const body = await page.locator('body').textContent();
    expect(body).not.toMatch(/99\.8%/);
    expect(body).not.toMatch(/−24%|-24%/);

    const methodologyLink = page.locator('a[href="/science/clinical-studies/"]', { hasText: /methodology/i });
    await expect(methodologyLink.first()).toBeVisible();
  });

  test('COS-001 (no backed clinical figures) omits the Clinical Proof section entirely', async ({ page }) => {
    await gotoAndCheck(page, '/products/micellar-water-cleanser/');
    await expect(page.getByText('Clinical Proof')).toHaveCount(0);
  });

  test('individual and bundle PDPs still render benefits, FAQ, and specs content after the data-provider refactor', async ({ page }) => {
    await gotoAndCheck(page, '/products/advanced-repair-serum/');
    await expect(page.getByText('The Flagship Serum')).toBeVisible();
    await expect(page.getByRole('heading', { name: /questions about this formula/i })).toBeVisible();
    // exact: true -- the SEO pass (2026-09-17) added a price-justification
    // FAQ answer that also mentions this active by name, so the plain
    // substring match now resolves to two elements (the Key Actives card
    // name and that FAQ answer). This assertion is about the Key Actives
    // card specifically, so pin it to the exact card-name text.
    await expect(page.getByText('SYN®-COLL Peptide', { exact: true })).toBeVisible();

    await gotoAndCheck(page, '/products/the-complete-ritual/');
    await expect(page.getByRole('heading', { name: /four products\. four roles\. one ritual\./i })).toBeVisible();
    await expect(page.getByRole('heading', { name: /questions about the complete ritual/i })).toBeVisible();
  });
});
