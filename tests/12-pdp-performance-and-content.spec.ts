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

  test('advancing the gallery actually changes the displayed photo (not just an attribute)', async ({ page }) => {
    // Pre-existing bug, found 2026-08-18 while investigating an unrelated AVIF/WebP
    // change: #cos-gallery-main keeps its srcset/sizes from the initial (first
    // photo) render, and per spec a valid srcset always wins over a plain src —
    // so cosGallerySet() was setting img.src to the clicked photo's URL but the
    // browser kept resolving img.currentSrc (what's actually displayed) against
    // the FIRST photo's srcset regardless of which dot/thumbnail was clicked.
    // Fixed by having cosGallerySet() clear srcset/sizes before setting src. If
    // this regresses, the gallery is silently showing the wrong product photo.
    // Uses an individual product rather than the bundle: the bundle's
    // editorial redesign (2026-09-20) replaced its multi-image dot/thumbnail
    // gallery with a single calm hero image, so it no longer has dots to
    // click at all -- the underlying cosGallerySet() fix this test guards is
    // still shared code, still exercised here via a product that still has
    // a multi-image gallery.
    await gotoAndCheck(page, '/products/advanced-repair-serum/');

    // 2026-09-27: the gallery now renders every photo as its own stacked
    // slide (each in a <picture>) and switches the visible one by class,
    // instead of rewriting a single <img>'s src -- so "the displayed photo"
    // is the active slide's image. Uses the Next arrow, which is visible at
    // every viewport (dots are mobile/tablet only, thumbnails desktop only).
    const activeSrc = () => page.locator('.cos-pdp-slide.is-active img').evaluate((img: HTMLImageElement) => img.currentSrc);
    const initialSrc = await activeSrc();

    await page.locator('.cos-pdp-gallery-arrow.is-next').click();
    await page.waitForTimeout(450); // cross-fade + lazy image fetch

    const swappedCurrentSrc = await activeSrc();
    expect(swappedCurrentSrc, 'advancing the gallery should change the actually-rendered photo').not.toBe(initialSrc);
    await expect(page.locator('.cos-pdp-slide').nth(1)).toHaveClass(/is-active/);
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

  test('the bundle hero is a static full-bleed image, with no video, no video bytes fetched, and no pause/play toggle', async ({ page }) => {
    // 2026-09-26: the brief 2026-09-22 background-video treatment (autoplay
    // muted loop + a WCAG 2.2.2 pause/play toggle) was explicitly reverted
    // back to a static poster image (attachment 23789, a black-background
    // product shot) -- confirms the revert is complete, not just that a new
    // image renders, since a half-removed video feature would be worse than
    // either state on its own.
    const requests: string[] = [];
    page.on('request', req => {
      if (/\.mp4(\?|$)/i.test(req.url())) requests.push(req.url());
    });

    await gotoAndCheck(page, '/products/the-complete-ritual/');

    const heroImg = page.locator('.cos-pdp-hero .cos-pdp-gallery-main img');
    await expect(heroImg).toHaveCount(1);
    await expect(heroImg).toHaveAttribute('fetchpriority', 'high');
    await expect(heroImg).toHaveAttribute('alt', /all four/i);
    await expect(page.locator('#cos-pdp-hero-video')).toHaveCount(0);
    await expect(page.locator('#cos-pdp-hero-video-toggle')).toHaveCount(0);
    expect(requests, 'no video bytes should ever be requested on the bundle hero').toEqual([]);
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
    // The SEO correction pass (2026-09-19, Task 5) briefly changed this to a
    // search-relevant phrase; a later UI/UX audit (2026-09-19) restored the
    // premium editorial subtitle and moved SEO keyword placement to page
    // titles/metadata/schema instead (which still carry it — see inc/seo.php).
    // 2026-09-27: the owner then asked for the subtitle (and the divider under
    // it) to be removed from individual product pages entirely.
    await expect(page.locator('.cos-pdp-subtitle')).toHaveCount(0);
    await expect(page.getByRole('heading', { name: /questions about this formula/i })).toBeVisible();
    // exact: true -- the SEO pass (2026-09-17) added a price-justification
    // FAQ answer that also mentions this active by name, so the plain
    // substring match now resolves to two elements (the Key Actives card
    // name and that FAQ answer). This assertion is about the Key Actives
    // card specifically, so pin it to the exact card-name text.
    await expect(page.getByText('SYN®-COLL Peptide', { exact: true })).toBeVisible();

    await gotoAndCheck(page, '/products/the-complete-ritual/');
    // "Four products. Four roles. One ritual." was the old card-grid
    // heading; the editorial redesign (2026-09-20) replaced that section
    // with "Four Acts of Care" as an alternating image/copy sequence.
    await expect(page.getByRole('heading', { name: /four acts of care\./i })).toBeVisible();
    await expect(page.getByRole('heading', { name: /questions about the complete ritual/i })).toBeVisible();
  });
});
