import { expect, test } from '@playwright/test';
import { expectNoHorizontalOverflow, gotoAndCheck } from './helpers/cos-helpers';

const viewports = [
  { width: 320, height: 568 },
  { width: 375, height: 667 },
  { width: 390, height: 844 },
  { width: 768, height: 1024 },
  { width: 1024, height: 768 },
  { width: 1440, height: 900 },
  { width: 1920, height: 1080 }
];

test.describe('Homepage editorial typography', () => {
  for (const viewport of viewports) {
    test(`consistent hierarchy at ${viewport.width}px`, async ({ page }) => {
      await page.setViewportSize(viewport);
      await gotoAndCheck(page, '/');
      await page.evaluate(() => document.fonts.ready.then(() => undefined));

      const headings = page.locator('main h2');
      await expect(headings).toHaveCount(6);
      for (const heading of await headings.all()) {
        await expect(heading).toHaveCSS('font-family', /Cormorant Garamond/);
        await expect(heading).toHaveCSS('font-style', 'normal');
        await expect(heading).toHaveCSS('font-weight', '400');
      }
      const desktop = viewport.width >= 900;
      for (const heading of await page.locator('.h26-final__title, .h26-caviar__title, .h26-provenance__title').all()) {
        await expect(heading).toHaveCSS('font-size', desktop ? '40px' : '32px');
      }
      for (const heading of await page.locator('.h26-h2').all()) {
        await expect(heading).toHaveCSS('font-size', desktop ? '32px' : '28px');
      }
      await expect(page.locator('.h26-gestures__title')).toHaveCSS('font-size', desktop ? '28px' : '24px');
      const heroSize = viewport.width >= 1440 ? '60px'
        : viewport.width >= 1100 ? '56px'
        : viewport.width >= 700 ? '48px'
        : viewport.width >= 360 ? '40px' : '38px';
      await expect(page.locator('main h1')).toHaveCSS('font-size', heroSize);
      await expect(page.locator('main h1')).toHaveCSS('font-style', 'italic');
      await expect(page.locator('main h1')).toHaveCSS('font-weight', '300');
      await expect(page.locator('.cos-nl-headline')).toHaveCSS('font-style', 'normal');
      await expect(page.locator('.cos-nl-headline')).toHaveCSS('font-size', desktop ? '30px' : '28px');
      await expect(page.locator('.cos-nl-input')).toHaveCSS('font-size', '16px');

      for (const name of await page.locator('.h26-pack__name').all()) {
        await expect(name).toHaveCSS('font-family', /Cormorant Garamond/);
        await expect(name).toHaveCSS('font-size', viewport.width < 360 ? '20px' : '22px');
        await expect(name).toHaveCSS('font-weight', '400');
      }
      await expect(page.locator('.h26-pack__btn')).toHaveText(Array(4).fill('View formula'));
      for (const link of await page.locator('.h26-pack__btn').all()) {
        await expect(link).toHaveCSS('border-top-width', '0px');
        await expect(link).toHaveCSS('min-height', '44px');
      }
      for (const figure of await page.locator('.h26-stat__fig').all()) {
        await expect(figure).toHaveCSS('font-size', desktop ? '56px' : '48px');
      }

      const copy = page.locator('.h26-hero__lines, .h26-lede, .h26-gesture__text, .h26-caviar__text, .h26-provenance__text, .h26-fact dd, .h26-pack__benefit');
      for (const paragraph of await copy.all()) {
        await expect(paragraph).toHaveCSS('font-family', /Inter/);
        await expect(paragraph).toHaveCSS('font-size', '16px');
        await expect(paragraph).toHaveCSS('font-weight', '400');
      }

      const textIssues = await page.locator('main h1, main h2, main h3, main p, .h26-pack__name, .h26-btn, .h26-pack__btn, .h26-link, .cos-nl-headline, .cos-nl-sub, .cos-nl-btn').evaluateAll(nodes =>
        nodes.filter(node => {
          const rect = node.getBoundingClientRect();
          return rect.width > 1 && rect.height > 1;
        }).filter(node => {
          const spacing = getComputedStyle(node).letterSpacing;
          return node.scrollWidth > node.clientWidth + 2 || (spacing !== 'normal' && parseFloat(spacing) !== 0);
        }).map(node => node.textContent?.trim())
      );
      expect(textIssues).toEqual([]);

      const priceOffsets = await page.locator('.h26-pack').evaluateAll(cards =>
        cards.map(card => card.querySelector('.h26-pack__meta')!.getBoundingClientRect().top - card.getBoundingClientRect().top)
      );
      expect(Math.max(...priceOffsets) - Math.min(...priceOffsets)).toBeLessThanOrEqual(2);
      await expectNoHorizontalOverflow(page);
    });
  }

  test('retains study context and scopes the newsletter copy to the homepage', async ({ page }) => {
    await gotoAndCheck(page, '/');
    await expect(page.locator('.h26-stat__fig')).toHaveText(['+47%', '\u221238%', '100%']);
    await expect(page.locator('.h26-stat--reported')).toContainText('Self-reported');
    await expect(page.locator('.h26-stat--reported')).toContainText('Panellist self-assessment');
    await expect(page.locator('.cos-nl-headline')).toHaveText('The Caviar Circle');

    await gotoAndCheck(page, '/shop/');
    await expect(page.locator('body')).not.toHaveClass(/cos-h26/);
    await expect(page.locator('.cos-nl-headline')).toHaveText('Enter the world of Caviar of Switzerland.');
    await expect(page.locator('.cos-nl-headline')).toHaveCSS('font-style', 'italic');
  });
});
