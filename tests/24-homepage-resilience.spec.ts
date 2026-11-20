import { expect, test } from '@playwright/test';
import { collectSevereBrowserErrors, gotoAndCheck } from './helpers/cos-helpers';

const expectedSections = ['hxh', 'hxc', 'hxv', 'hxm', 'hxp', 'hxr', 'hxz'];

test.describe('Homepage resilience and progressive enhancement', () => {
  test('renders the complete component sequence with valid landmark relationships', async ({ page }) => {
    await gotoAndCheck(page, '/');

    await expect(page.locator('body')).toHaveClass(/cos-h26/);
    await expect(page.locator('main#cos-main-content.h26')).toHaveCount(1);
    await expect(page.locator('main h1')).toHaveCount(1);
    await expect(page.locator('main h2')).toHaveCount(6);

    const structure = await page.locator('main > section').evaluateAll(sections => sections.map(section => ({
      className: section.classList[0],
      id: section.id,
      labelledBy: section.getAttribute('aria-labelledby')
    })));
    expect(structure.map(section => section.className)).toEqual(expectedSections);

    const semanticProblems = await page.locator('main').evaluate(main => {
      const ids = Array.from(main.querySelectorAll<HTMLElement>('[id]')).map(element => element.id);
      const duplicateIds = ids.filter((id, index) => ids.indexOf(id) !== index);
      const brokenLabels = Array.from(main.querySelectorAll<HTMLElement>('section[aria-labelledby]'))
        .filter(section => !document.getElementById(section.getAttribute('aria-labelledby') || ''))
        .map(section => section.className);
      const nestedControls = main.querySelectorAll('a a, a button, button a, button button').length;
      return { duplicateIds, brokenLabels, nestedControls };
    });
    expect(semanticProblems).toEqual({ duplicateIds: [], brokenLabels: [], nestedControls: 0 });
  });

  test('selects the correct hero art direction and preserves image loading metadata', async ({ page }) => {
    const responseFailures: string[] = [];
    page.on('response', response => {
      if (/\/assets\/images\/home-2026\//.test(response.url()) && response.status() >= 400) {
        responseFailures.push(`${response.status()} ${response.url()}`);
      }
    });

    await gotoAndCheck(page, '/');
    const viewport = page.viewportSize();
    const landscape = (viewport?.width || 0) >= (viewport?.height || 0);
    const hero = page.locator('.hxh__picture img');
    await expect(hero).toHaveAttribute('loading', 'eager');
    await expect(hero).toHaveAttribute('fetchpriority', 'high');
    await expect(hero).toHaveAttribute('width', /\d+/);
    await expect(hero).toHaveAttribute('height', /\d+/);
    await expect(hero).toHaveAttribute('alt', /four Caviar of Switzerland formulas/i);
    await expect.poll(() => hero.evaluate(image => (image as HTMLImageElement).naturalWidth)).toBeGreaterThan(0);

    const currentSource = await hero.evaluate(image => (image as HTMLImageElement).currentSrc);
    expect(currentSource).toContain(landscape ? 'wc-hero-set-wide-' : 'wc-hero-set-tall-');

    const preloads = page.locator('link[rel="preload"][as="image"][imagesrcset]');
    await expect(preloads).toHaveCount(2);
    await expect(preloads.first()).toHaveAttribute('media', /min-aspect-ratio/);
    await expect(preloads.last()).toHaveAttribute('media', /not all.*min-aspect-ratio/);

    const imageMetadataProblems = await page.locator('main img').evaluateAll(images => images.flatMap(image => {
      const element = image as HTMLImageElement;
      const width = Number(element.getAttribute('width'));
      const height = Number(element.getAttribute('height'));
      const problems: string[] = [];
      if (!element.hasAttribute('alt')) problems.push(`${element.className}: missing alt`);
      if (!(width > 0) || !(height > 0)) problems.push(`${element.className}: missing intrinsic size`);
      if (!element.closest('.hxh') && element.loading !== 'lazy') problems.push(`${element.className}: non-hero image is not lazy`);
      return problems;
    }));
    expect(imageMetadataProblems).toEqual([]);
    expect(responseFailures).toEqual([]);
  });

  test('keeps every homepage destination concrete and same-origin', async ({ page, request, baseURL }) => {
    await gotoAndCheck(page, '/');

    const links = await page.locator('main a[href]').evaluateAll(anchors => Array.from(new Set(anchors.map(anchor => {
      const url = new URL((anchor as HTMLAnchorElement).href);
      return url.origin === window.location.origin ? url.pathname : '';
    }).filter(Boolean))));
    expect(links.length).toBeGreaterThanOrEqual(10);

    const failures: string[] = [];
    for (const path of links) {
      const response = await request.get(new URL(path, baseURL).href, { timeout: 15_000 });
      if (response.status() >= 400) failures.push(`${path} -> ${response.status()}`);
    }
    expect(failures, 'Homepage links should not lead to errors').toEqual([]);
  });

  test('remains readable and actionable when JavaScript is disabled', async ({ browser, baseURL }) => {
    const context = await browser.newContext({ javaScriptEnabled: false, baseURL });
    const page = await context.newPage();
    try {
      const response = await page.goto('/', { waitUntil: 'domcontentloaded' });
      expect(response?.status()).toBeLessThan(400);
      await expect(page.locator('main h1')).toBeVisible();
      await expect(page.locator('main h2')).toHaveCount(6);
      await expect(page.getByRole('link', { name: /Shop the Complete Ritual/i }).first()).toBeVisible();
      await expect(page.locator('html')).not.toHaveClass(/hx-motion/);

      const hiddenContent = await page.locator('[data-hx-reveal]').evaluateAll(elements => elements.filter(element => {
        const styles = getComputedStyle(element);
        return styles.display === 'none' || styles.visibility === 'hidden' || Number(styles.opacity) < 0.95;
      }).map(element => element.className));
      expect(hiddenContent, 'Progressive enhancement must not hide content without JavaScript').toEqual([]);
    } finally {
      await context.close();
    }
  });

  test('honours reduced motion without hiding reveal content or enabling drift', async ({ page }) => {
    await page.emulateMedia({ reducedMotion: 'reduce' });
    await gotoAndCheck(page, '/');

    await expect(page.locator('html')).not.toHaveClass(/hx-motion/);
    const state = await page.locator('[data-hx-reveal]').evaluateAll(elements => elements.map(element => ({
      opacity: Number(getComputedStyle(element).opacity),
      visible: getComputedStyle(element).visibility,
      revealed: element.classList.contains('is-in')
    })));
    expect(state.length).toBeGreaterThan(20);
    expect(state.every(item => item.opacity >= 0.95 && item.visible === 'visible' && item.revealed)).toBe(true);
    await expect(page.locator('[data-hx-drift]').first()).not.toHaveCSS('--hx-dy', /px/);
  });

  test('can reveal all motion content safely and leaves no element stranded', async ({ page }) => {
    const errors = collectSevereBrowserErrors(page);
    try {
      await page.goto('/', { waitUntil: 'domcontentloaded' });
      await expect(page.locator('html')).toHaveClass(/hx-motion/);
      await expect.poll(() => page.evaluate(() => typeof window.hxMotion?.revealAll)).toBe('function');
      await page.evaluate(() => window.hxMotion.revealAll());
      await expect(page.locator('[data-hx-reveal]:not(.is-in)')).toHaveCount(0);

      const hidden = await page.locator('[data-hx-reveal]').evaluateAll(elements => elements.filter(element => {
        const styles = getComputedStyle(element);
        return Number(styles.opacity) < 0.95 || styles.visibility === 'hidden';
      }).map(element => element.className));
      expect(hidden).toEqual([]);
      expect(errors.severe).toEqual([]);
    } finally {
      errors.dispose();
    }
  });

  test('loads homepage-only presentation assets only on the homepage', async ({ page }) => {
    await gotoAndCheck(page, '/');
    // 8 Nov 2026: home-2026.css + nocturne were pruned into home-2026-base.css; home-2026.js retired.
    await expect(page.locator('link#cos-home-2026-base-css')).toHaveCount(1);
    await expect(page.locator('link#cos-hx-motion-js, script#cos-hx-motion-js')).toHaveCount(1);

    await gotoAndCheck(page, '/shop/');
    await expect(page.locator('body')).not.toHaveClass(/cos-h26/);
    await expect(page.locator('link#cos-home-2026-base-css')).toHaveCount(0);
    await expect(page.locator('script#cos-home-2026-js')).toHaveCount(0);
  });
});

declare global {
  interface Window {
    hxMotion: { revealAll: () => void };
  }
}
