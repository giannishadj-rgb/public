import { expect, test } from '@playwright/test';
import { expectNoHorizontalOverflow, gotoAndCheck } from './helpers/cos-helpers';

// Homepage type roles (typeset, 11 Oct 2026): one size per role per breakpoint,
// driven by the --t-* tokens on .h26 (end of home-2026.css, documented in DESIGN.md).
const viewports = [
  { width: 320, height: 568 },
  { width: 375, height: 667 },
  { width: 390, height: 844 },
  { width: 768, height: 1024 },
  { width: 1024, height: 768 },
  { width: 1440, height: 900 },
  { width: 1920, height: 1080 }
];

const px = (n: number) => `${n}px`;

test.describe('Homepage editorial typography', () => {
  for (const viewport of viewports) {
    test(`consistent hierarchy at ${viewport.width}px`, async ({ page }) => {
      await page.setViewportSize(viewport);
      await gotoAndCheck(page, '/');
      await page.evaluate(() => document.fonts.ready.then(() => undefined));
      const w = viewport.width;

      // Five sections under the hero, every heading in the display serif.
      const headings = page.locator('main h2');
      await expect(headings).toHaveCount(5);
      for (const heading of await headings.all()) {
        await expect(heading).toHaveCSS('font-family', /Cormorant Garamond/);
      }

      // Hero display line.
      const hero = page.locator('main h1');
      await expect(hero).toHaveCSS('font-size', px(w >= 1440 ? 60 : w >= 700 ? 48 : w >= 360 ? 40 : 38));
      await expect(hero).toHaveCSS('font-weight', '300');

      // Shared roles: the same job renders at the same size wherever it appears.
      // The four section headings share one size, weight and case; the hero and
      // The Ritual are deliberate editorial exceptions.
      const heading = px(w >= 1100 ? 48 : w >= 700 ? 40 : 34);
      for (const el of await page.locator('.h26c__title, .h26-caviar__title, .h26e__title, .h26-provenance__title').all()) {
        await expect(el).toHaveCSS('font-size', heading);
        await expect(el).toHaveCSS('font-weight', '300');
        await expect(el).toHaveCSS('text-transform', 'none');
      }
      const title = px(w >= 700 ? 22 : 20);
      for (const el of await page.locator('.h26c__name, .h26r__verb').all()) {
        await expect(el).toHaveCSS('font-size', title);
      }
      const numeral = px(w >= 700 ? 40 : 32);
      for (const el of await page.locator('.h26r__num').all()) await expect(el).toHaveCSS('font-size', numeral);
      const figure = px(w >= 1100 ? 56 : 48);
      for (const el of await page.locator('.h26e__fig').all()) await expect(el).toHaveCSS('font-size', figure);

      // Reading copy never drops below 16px; purchase details never below 15px.
      const reading = page.locator('.h26-hero__lines, .h26c__lede, .h26e__lede, .h26c__benefit, .h26v__lede, .h26v__fact-v, .h26e__panel, .h26e__method, .h26h__lede, .h26h__fact dd, .h26r__lede, .h26r__how');
      for (const p of await reading.all()) {
        await expect(p).toHaveCSS('font-family', /Inter/);
        await expect(p).toHaveCSS('font-size', '16px');
      }
      for (const p of await page.locator('.h26c__price, .h26r__fact').all()) {
        await expect(p).toHaveCSS('font-size', '15px');
      }
      for (const btn of await page.locator('.h26c__btn').all()) await expect(btn).toHaveCSS('min-height', '44px');
      await expect(page.locator('.cos-nl-input')).toHaveCSS('font-size', '16px');

      // Nothing clips its own text, and the page never scrolls sideways.
      const clipped = await page.locator('main h1, main h2, main h3, main p, .h26-btn, .h26-link, .h26c__btn').evaluateAll(nodes =>
        nodes.filter(node => {
          const r = node.getBoundingClientRect();
          return r.width > 1 && r.height > 1 && node.scrollWidth > node.clientWidth + 2;
        }).map(node => node.textContent?.trim())
      );
      expect(clipped).toEqual([]);

      // Collection: prices and buttons line up across the four cards in each row.
      const btnTops = await page.locator('.h26c__btn').evaluateAll(els => els.map(e => Math.round(e.getBoundingClientRect().top)));
      expect(btnTops.length).toBe(4);
      const perRow = w >= 1100 ? [btnTops] : [btnTops.slice(0, 2), btnTops.slice(2)];
      for (const row of perRow) expect(Math.max(...row) - Math.min(...row)).toBeLessThanOrEqual(2);
      await expectNoHorizontalOverflow(page);
    });
  }

  test('keeps study context precise and scopes the newsletter copy to the homepage', async ({ page }) => {
    await gotoAndCheck(page, '/');
    // Only the two instrument results are pinned to the face.
    await expect(page.locator('.h26e__fig')).toHaveText(['+47%', '−38%']);
    await expect(page.locator('.h26e__call')).toHaveCount(2);
    for (const call of await page.locator('.h26e__call').all()) await expect(call).toContainText('Instrument-measured');
    // The self-reported result is a separate, labelled caption.
    await expect(page.locator('.h26e__note')).toContainText('Self-reported');
    await expect(page.locator('.h26e__note')).toContainText('100%');
    await expect(page.locator('.h26e__credit')).toContainText('not a study participant');
    await expect(page.locator('.h26e__lede')).toContainText('Evaluated over 28 days');
    // Newsletter outcomes are announced through a persistent live region.
    await expect(page.locator('#cos-nl-status')).toHaveAttribute('role', 'status');
    await expect(page.locator('.cos-nl-input')).toHaveAttribute('aria-describedby', 'cos-nl-status');
    await expect(page.locator('.cos-nl-headline')).toHaveText('The Caviar Circle');

    await gotoAndCheck(page, '/shop/');
    await expect(page.locator('body')).not.toHaveClass(/cos-h26/);
    await expect(page.locator('.cos-nl-headline')).toHaveText('Enter the world of Caviar of Switzerland.');
    await expect(page.locator('.cos-nl-headline')).toHaveCSS('font-style', 'italic');
  });
});
