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

      const h1 = page.locator('main h1');
      const h2s = page.locator('main h2');
      await expect(h1).toHaveCount(1);
      await expect(h2s).toHaveCount(6);

      for (const heading of await page.locator('main h1, main h2').all()) {
        await expect(heading).toHaveCSS('font-family', /Source Serif 4|Georgia|Times New Roman/);
        await expect(heading).toHaveCSS('font-weight', '400');
      }

      const sizes = await page.locator('main h1, main h2').evaluateAll(nodes =>
        nodes.map(node => Number.parseFloat(getComputedStyle(node).fontSize))
      );
      expect(sizes[0], 'The hero heading should remain the strongest type role').toBeGreaterThan(sizes[1]);
      expect(Math.min(...sizes), 'Section headings should never become cramped').toBeGreaterThanOrEqual(22);

      for (const copy of await page.locator('.hxh__lead, .hxm__frame-line, .hxp__lede, .hxr__lede').all()) {
        const fontSize = await copy.evaluate(node => Number.parseFloat(getComputedStyle(node).fontSize));
        expect(fontSize, 'Editorial support copy should remain at least 14px').toBeGreaterThanOrEqual(14);
      }

      const clipped = await page.locator('main h1, main h2, main h3, main p, .hx-btn, .hx-link, .hxc__name').evaluateAll(nodes =>
        nodes.filter(node => {
          const rect = node.getBoundingClientRect();
          return rect.width > 1 && rect.height > 1 && node.scrollWidth > node.clientWidth + 2;
        }).map(node => node.textContent?.trim())
      );
      expect(clipped, 'Homepage text should not be horizontally clipped').toEqual([]);

      const collectionItems = await page.locator('.hxc__item').evaluateAll(items => items.map(item => ({
        itemTop: Math.round(item.getBoundingClientRect().top),
        priceTop: Math.round(item.querySelector('.hxc__price')!.getBoundingClientRect().top)
      })));
      expect(collectionItems).toHaveLength(4);
      const rows = collectionItems.reduce((groups, item) => {
        const row = groups.get(item.itemTop) || [];
        row.push(item);
        groups.set(item.itemTop, row);
        return groups;
      }, new Map<number, typeof collectionItems>());
      for (const row of rows.values()) {
        const priceTops = row.map(item => item.priceTop);
        expect(Math.max(...priceTops) - Math.min(...priceTops), 'Collection prices should align within each row').toBeLessThanOrEqual(3);
      }

      await expectNoHorizontalOverflow(page);
    });
  }

  test('keeps clinical study context precise and the newsletter treatment consistent', async ({ page }) => {
    await gotoAndCheck(page, '/');

    await expect(page.locator('.hxm__fig')).toHaveText(['+47%', '−38%']);
    await expect(page.locator('.hxm__results > li')).toHaveCount(3);
    await expect(page.locator('.hxm__method')).toContainText([
      'Cutometry',
      'Optical profilometry',
      'Self-assessment'
    ]);
    await expect(page.locator('.hxm__note').last()).toContainText('100% panellist-reported satisfaction');
    await expect(page.locator('.hxm__frame-line')).toContainText('Separate 28-day studies');

    await expect(page.locator('#cos-nl-status')).toHaveAttribute('role', 'status');
    await expect(page.locator('.cos-nl-input')).toHaveAttribute('aria-describedby', 'cos-nl-status');
    await expect(page.locator('.cos-nl-headline')).toHaveText('The Caviar Circle');

    await gotoAndCheck(page, '/shop/');
    await expect(page.locator('body')).not.toHaveClass(/cos-h26/);
    await expect(page.locator('.cos-nl-headline')).toHaveText('The Caviar Circle');
    await expect(page.locator('#cos-nl-status')).toHaveAttribute('role', 'status');
  });
});
