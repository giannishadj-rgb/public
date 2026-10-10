import { expect, test } from '@playwright/test';
import { expectHealthyPageShell, expectNoHorizontalOverflow, gotoAndCheck } from './helpers/cos-helpers';

const publicPaths = [
  '/',
  '/the-ritual/',
  '/science/ingredients/',
  '/heritage/',
  '/shop/',
  '/journal/',
  '/contact/',
  '/science/',
  '/science/why-caviar/',
  '/science/high-tech-complex/',
  '/science/clinical-studies/',
  '/science/swiss-formulation/',
  '/science/ingredients/caviar-extract/',
  '/science/ingredients/syn-coll-peptide/',
  '/science/ingredients/hyaluronic-acid/',
  '/science/ingredients/superoxide-dismutase/',
  '/science/ingredients/resveratrol/',
  '/faq/',
  '/shipping-returns/',
  '/heritage/craftsmanship/',
  '/heritage/sustainability/',
  '/help/',
  '/sitemap/',
  '/journal/why-caviar-extract-works/',
  '/journal/five-ingredients-that-earn-anti-aging/',
  '/journal/luxury-ingredients-worth-it/',
  '/journal/case-for-overnight-regeneration/',
  '/journal/skincare-routine-after-40/',
  '/journal/back-to-basics-routine/',
  '/journal/get-the-most-from-your-ritual/',
  '/journal/single-step-cleanser/',
  '/journal/identify-your-skin-type/',
  '/journal/caring-for-sensitive-skin/',
  '/journal/adult-acne-breakouts/',
  '/journal/truth-about-under-eye-aging/',
  '/journal/year-round-hydration/',
  '/journal/swiss-precision-vs-k-beauty/',
  '/journal/luxury-skincare-trends-2025/',
  '/journal/ingredients-science/',
  '/journal/routines-how-to/',
  '/journal/skin-concerns/',
  '/journal/editorial/',
  '/skin-concerns/',
  '/skin-concerns/fine-lines-wrinkles/',
  '/skin-concerns/loss-of-firmness/',
  '/skin-concerns/dark-circles/',
  '/skin-concerns/dry-dehydrated-skin/',
  '/skin-concerns/dull-skin/',
  '/products/micellar-water-cleanser/',
  '/products/advanced-repair-serum/',
  '/products/revitalizing-eye-cream/',
  '/products/24h-regeneration-cream/',
  '/products/the-complete-ritual/',
  '/cart/',
  '/account/',
  '/privacy/',
  '/terms/'
] as const;

test.describe('Sitewide visual system', () => {
  for (const path of publicPaths) {
    test(`${path} uses the shared chrome, palette, and typography`, async ({ page }) => {
      await page.setViewportSize({ width: 390, height: 844 });
      const response = await gotoAndCheck(page, path);
      expect(response?.status()).toBe(200);
      await expectHealthyPageShell(page);

      await expect(page.locator('#cos-nav-wrap')).toHaveCSS('background-color', 'rgba(242, 242, 239, 0.93)');
      await expect(page.locator('#cos-footer')).toHaveCSS('background-color', 'rgb(13, 17, 23)');
      await expect(page.locator('.cos-nav-logo img')).toHaveCSS('filter', 'brightness(0.6) contrast(1.15)');
      await expect(page.locator('body')).toHaveCSS('font-family', /Inter/);
      await expect(page.locator('body')).toHaveCSS('color', 'rgb(17, 17, 17)');
      await expect(page.locator('body')).toHaveCSS('background-color', 'rgb(242, 242, 239)');

      const visibleTimesNewRoman = await page.locator('main h1, main h2, main h3, main p, main li, main a, main button').evaluateAll(nodes =>
        nodes
          .filter(node => {
            const rect = node.getBoundingClientRect();
            const styles = getComputedStyle(node);
            return rect.width > 0 && rect.height > 0 && styles.display !== 'none' && styles.visibility !== 'hidden';
          })
          .filter(node => /Times New Roman/i.test(getComputedStyle(node).fontFamily))
          .map(node => node.textContent?.trim().replace(/\s+/g, ' ').slice(0, 80))
      );
      expect(visibleTimesNewRoman, `Browser-default typography leaked into ${path}`).toEqual([]);
      await expectNoHorizontalOverflow(page);
    });
  }

  test('unknown routes keep the same branded shell', async ({ page }) => {
    const response = await gotoAndCheck(page, '/not-a-real-cos-design-page/', {
      allowedSevereErrors: [/Failed to load resource: the server responded with a status of 404/i]
    });
    expect(response?.status()).toBe(404);
    await expectHealthyPageShell(page);
    await expect(page.locator('body')).toHaveCSS('font-family', /Inter/);
    await expect(page.locator('#cos-footer')).toHaveCSS('background-color', 'rgb(13, 17, 23)');
  });
});
