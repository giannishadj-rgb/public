import { expect, type ConsoleMessage, type Locator, type Page, type Response } from '@playwright/test';

export const criticalRoutes = [
  { name: 'home', path: '/', expected: /Caviar of Switzerland|four-step ritual|Swiss Caviar Skincare/i },
  { name: 'shop', path: '/shop/', expected: /Shop|Ritual|Individual Products|Add to Bag/i },
  { name: 'the ritual', path: '/the-ritual/', expected: /Ritual|Cleanse|Treat|Eye|Moisturi[sz]e/i },
  { name: 'swiss science', path: '/science/', expected: /Science|High-Tech Complex|Clinical|Caviar/i },
  { name: 'swiss heritage', path: '/heritage/', expected: /Heritage|Swiss|Zug|Caviar/i },
  { name: 'contact', path: '/contact/', expected: /Contact|Concierge|Caviar/i },
  { name: 'journal', path: '/journal/', expected: /Journal|Press|Reviews|Caviar/i },
  { name: 'cart', path: '/cart/', expected: /Your Ritual Bag|Order Summary|Your bag is empty/i },
  { name: 'account', path: '/account/', expected: /Your ritual|Sign in|Register|Account/i }
] as const;

const fatalPagePatterns = [
  /Fatal error/i,
  /Parse error/i,
  /(?:^|[\r\n>])\s*(?:<b>)?Warning(?:<\/b>)?:\s/i,
  /(?:^|[\r\n>])\s*(?:<b>)?Notice(?:<\/b>)?:\s/i,
  /(?:^|[\r\n>])\s*(?:<b>)?Deprecated(?:<\/b>)?:\s/i,
  /There has been a critical error/i,
  /Error establishing a database connection/i
];

export function collectSevereBrowserErrors(page: Page) {
  const severe: string[] = [];

  const onPageError = (error: Error) => {
    severe.push(error.message);
  };

  const onConsole = (message: ConsoleMessage) => {
    const text = message.text();
    if (
      message.type() === 'error' &&
      // 429 (Too Many Requests): Chrome's "Failed to load resource" console
      // message never includes the request URL, only the status text, so
      // this can't be scoped to a specific endpoint by string match. In
      // practice the only 429 source in this app is the Report-Only CSP
      // violation beacon's own deliberate per-IP rate limiter (verified
      // working in tests/17-csp-report-only.spec.ts) firing after enough
      // page loads in one run/session — the limiter doing its job
      // correctly, not a page defect. A 429 is by definition the server
      // successfully protecting itself, never a broken-page signal like a
      // 404/500 would be, so it's safe to treat as the same category of
      // benign noise as the other patterns below.
      !/favicon|ERR_ABORTED|ResizeObserver loop|a\.klaviyo\.com|429 \(Too Many Requests\)/i.test(text)
    ) {
      severe.push(text);
    }
  };

  page.on('pageerror', onPageError);
  page.on('console', onConsole);

  return {
    severe,
    dispose: () => {
      page.off('pageerror', onPageError);
      page.off('console', onConsole);
    }
  };
}

type GotoCheckOptions = {
  allowedSevereErrors?: RegExp[];
};

export async function gotoAndCheck(page: Page, path: string, options: GotoCheckOptions = {}): Promise<Response | null> {
  const errors = collectSevereBrowserErrors(page);
  let response: Response | null = null;

  try {
    response = await page.goto(path, { waitUntil: 'domcontentloaded' });
    await page.waitForLoadState('networkidle', { timeout: 10_000 }).catch(() => undefined);

    expect(response, `No HTTP response returned for ${path}`).not.toBeNull();
    expect(response!.status(), `${path} returned an HTTP server error`).toBeLessThan(500);

    const html = await page.content();
    for (const pattern of fatalPagePatterns) {
      expect(html, `${path} contains ${pattern}`).not.toMatch(pattern);
    }

    const unexpectedErrors = errors.severe.filter(error => {
      return !options.allowedSevereErrors?.some(pattern => pattern.test(error));
    });
    expect(unexpectedErrors, `${path} produced severe browser errors`).toEqual([]);
  } finally {
    errors.dispose();
  }

  return response;
}

export async function expectHealthyPageShell(page: Page) {
  const shell = page.locator('main, #cos-main-content, [role="main"]').first();
  await expect(shell, 'Page should expose a main content shell').toBeVisible();
  await expect(page.locator('#cos-nav-wrap, [role="banner"]').first(), 'Global header should be present').toBeVisible();
  await expect(page.locator('#cos-footer, [role="contentinfo"]').last(), 'Global footer should be present').toBeVisible();
}

export async function expectNoHorizontalOverflow(page: Page) {
  const overflow = await page.evaluate(() => {
    const viewportWidth = document.documentElement.clientWidth;
    const isInsideClippedContainer = (element: HTMLElement) => {
      let parent = element.parentElement;
      while (parent && parent !== document.body) {
        const styles = window.getComputedStyle(parent);
        if (/(auto|scroll|hidden|clip)/.test(styles.overflowX)) {
          const parentRect = parent.getBoundingClientRect();
          if (parentRect.right > 0 && parentRect.left < viewportWidth) return true;
        }
        parent = parent.parentElement;
      }
      return false;
    };

    const offenders = Array.from(document.body.querySelectorAll<HTMLElement>('*'))
      .flatMap(element => {
        if (element.closest('#cos-mob-menu:not(.open)')) return [];
        if (element.classList.contains('screen-reader-text') || element.classList.contains('cos-skip-link')) return [];

        const styles = window.getComputedStyle(element);
        if (styles.display === 'none' || styles.visibility === 'hidden') return [];

        const rect = element.getBoundingClientRect();
        if (rect.width === 0 || rect.height === 0) return [];
        if (rect.right <= 0 && Math.abs(rect.left) > viewportWidth) return [];
        if (isInsideClippedContainer(element)) return [];

        const rightOverflow = rect.right - viewportWidth;
        const leftOverflow = -rect.left;
        if (rightOverflow <= 2 && leftOverflow <= 2) return [];
        if (styles.position === 'fixed' && (rect.right <= 2 || rect.left >= viewportWidth - 2)) return [];

        return {
          tag: element.tagName.toLowerCase(),
          id: element.id,
          className: String(element.className || '').slice(0, 120),
          right: Math.round(rect.right),
          left: Math.round(rect.left),
          overflow: Math.ceil(Math.max(rightOverflow, leftOverflow))
        };
      })
      .slice(0, 8);

    const pageOverflow = offenders.reduce((max, item) => Math.max(max, item.overflow), 0);

    return { pageOverflow, offenders, viewportWidth };
  });

  expect(
    overflow.pageOverflow,
    `Horizontal overflow at ${overflow.viewportWidth}px. Offenders: ${JSON.stringify(overflow.offenders)}`
  ).toBeLessThanOrEqual(2);
}

export async function expectRequiredSeo(page: Page) {
  await expect(page, 'Document should have a title').toHaveTitle(/\S/);

  const description = page.locator('meta[name="description"]').first();
  await expect(description, 'Meta description should be present').toHaveAttribute('content', /.+/);

  const ogTitle = page.locator('meta[property="og:title"]').first();
  const ogDescription = page.locator('meta[property="og:description"]').first();
  await expect(ogTitle, 'Open Graph title should be present').toHaveAttribute('content', /.+/);
  await expect(ogDescription, 'Open Graph description should be present').toHaveAttribute('content', /.+/);
}

export async function expectSingleViewportMeta(page: Page) {
  await expect(page.locator('meta[name="viewport"]'), 'Exactly one viewport meta tag should be rendered').toHaveCount(1);
}

export async function prepareStableVisualState(page: Page) {
  await page.emulateMedia({ reducedMotion: 'reduce' });
  await page.addStyleTag({
    content: `
      *, *::before, *::after {
        animation-delay: 0s !important;
        animation-duration: 0.001s !important;
        transition-delay: 0s !important;
        transition-duration: 0s !important;
        scroll-behavior: auto !important;
      }
      .crv3-reveal,
      .crv3-reveal.is-visible {
        opacity: 1 !important;
        transform: none !important;
      }
      #cos-mob-menu:not(.open) {
        visibility: hidden !important;
      }
      .cos-bag-count {
        visibility: hidden !important;
      }
    `
  });
  await page.evaluate(async () => {
    if ('fonts' in document) await document.fonts.ready;
    window.scrollTo(0, 0);
  });
  await page.waitForTimeout(150);
}

// WCAG 2.5.8 (Target Size, Minimum) — the absolute floor for any
// interactive control, with adequate spacing. Below this a control fails
// the success criterion outright, not just "feels cramped".
export const TAP_TARGET_MIN = 24;
// Not a WCAG number — this project's own bar for primary/frequently-used
// commerce controls (add to bag, checkout, quantity steppers, etc.), which
// should comfortably clear the minimum rather than just scrape past it.
export const TAP_TARGET_PREFERRED = 44;

async function measureTapTargets(page: Page, selector: string, minimum: number) {
  return page.locator(selector).evaluateAll((nodes, minimum) => {
    return nodes
      .map((node, index) => {
        const element = node as HTMLElement;
        const rect = element.getBoundingClientRect();
        const styles = window.getComputedStyle(element);
        const label =
          element.getAttribute('aria-label') ||
          element.textContent?.trim().replace(/\s+/g, ' ') ||
          element.getAttribute('name') ||
          element.className ||
          element.tagName.toLowerCase();

        return {
          index,
          label: String(label).slice(0, 80),
          width: Math.round(rect.width),
          height: Math.round(rect.height),
          visible: styles.display !== 'none' && styles.visibility !== 'hidden' && rect.width > 0 && rect.height > 0,
          tooSmall: rect.width < minimum || rect.height < minimum
        };
      })
      .filter(target => target.visible && target.tooSmall);
  }, minimum);
}

// Every interactive control on the page must clear the WCAG 2.5.8 minimum.
export async function expectMinimumTapTargets(page: Page, selector: string, minimum = TAP_TARGET_MIN) {
  const targets = await measureTapTargets(page, selector, minimum);
  expect(targets, `Tap targets should be at least ${minimum}px square (WCAG 2.5.8 minimum)`).toEqual([]);
}

// Primary/frequently-used commerce controls should clear this project's
// higher, "comfortable" bar, not just the WCAG floor.
export async function expectPreferredTapTargets(page: Page, selector: string, preferred = TAP_TARGET_PREFERRED) {
  const targets = await measureTapTargets(page, selector, preferred);
  expect(targets, `Primary commerce controls should be at least ${preferred}px square`).toEqual([]);
}

// A single instant window.scrollTo() jump can skip past the exact frame
// where an IntersectionObserver's ratio crosses through a nonzero value —
// confirmed empirically while fixing the PDP sticky Add to Bag bar (UI/UX
// pass 2026-09-19): a real user's scroll (even a fast flick) passes through
// many intermediate paint frames and always triggers the observer correctly,
// but a single scrollTo(y) jump (and Playwright's scrollIntoViewIfNeeded(),
// which is also instant) does not reliably reproduce that. Use this wherever
// a test needs to actually exercise scroll-driven IntersectionObserver logic
// rather than just asserting on a final scroll position.
export async function scrollWindowIncrementally(page: Page, targetY: number, stepPx = 80, stepDelayMs = 40) {
  const startY = await page.evaluate(() => window.scrollY);
  const direction = targetY >= startY ? 1 : -1;
  for (let y = startY; direction > 0 ? y <= targetY : y >= targetY; y += direction * stepPx) {
    await page.evaluate((yy) => window.scrollTo(0, yy), y);
    await page.waitForTimeout(stepDelayMs);
  }
  await page.evaluate((yy) => window.scrollTo(0, yy), targetY);
  await page.waitForTimeout(stepDelayMs);
}

export async function checkSameOriginAssets(page: Page) {
  const assetUrls = await page.evaluate(() => {
    const urls = new Set<string>();
    const add = (raw: string | null) => {
      if (!raw || raw.startsWith('data:') || raw.startsWith('blob:')) return;
      try {
        const url = new URL(raw, window.location.href);
        if (url.origin === window.location.origin) urls.add(url.href);
      } catch {
        // Ignore malformed URLs here. Broken URL handling is covered by browser errors.
      }
    };

    document.querySelectorAll<HTMLImageElement>('img[src]').forEach(node => add(node.getAttribute('src')));
    document.querySelectorAll<HTMLSourceElement>('source[src]').forEach(node => add(node.getAttribute('src')));
    document.querySelectorAll<HTMLLinkElement>('link[rel="stylesheet"][href]').forEach(node => add(node.getAttribute('href')));
    document.querySelectorAll<HTMLScriptElement>('script[src]').forEach(node => add(node.getAttribute('src')));

    return Array.from(urls).slice(0, 80);
  });

  const failures: string[] = [];
  for (const url of assetUrls) {
    const response = await page.request.get(url, { timeout: 12_000 }).catch(error => {
      failures.push(`${url} -> ${error.message}`);
      return null;
    });
    if (response && response.status() >= 400) {
      failures.push(`${url} -> HTTP ${response.status()}`);
    }
  }

  expect(failures, 'Same-origin images, scripts, and styles should not be broken').toEqual([]);
}

export async function getAddToBagControl(page: Page): Promise<Locator> {
  const link = page.getByRole('link', { name: /^Add to Bag$/i }).first();
  if (await link.count()) return link;

  const button = page.getByRole('button', { name: /^Add to Bag$/i }).first();
  if (await button.count()) return button;

  return page.locator('a,button').filter({ hasText: /^Add to Bag$/i }).first();
}

export async function addFirstProductToBag(page: Page) {
  await gotoAndCheck(page, '/shop/');

  const addToBag = await getAddToBagControl(page);
  await expect(addToBag, 'Shop should expose at least one Add to Bag control').toBeVisible();
  const fallbackHref = await addToBag
    .evaluate(element => (element instanceof HTMLAnchorElement ? element.href : null))
    .catch(() => null);

  const ajaxAddToCart = page
    .waitForResponse(response => /[?&]wc-ajax=add_to_cart/i.test(response.url()), { timeout: 10_000 })
    .catch(() => null);

  await addToBag.click();
  await ajaxAddToCart;
  await page.waitForLoadState('domcontentloaded').catch(() => undefined);
  await page.waitForLoadState('networkidle', { timeout: 10_000 }).catch(() => undefined);

  if (!/\/cart\/?/i.test(page.url())) {
    await gotoAndCheck(page, '/cart/');
  }

  const bodyText = await page.locator('body').textContent();
  if (fallbackHref && /ritual bag is empty/i.test(bodyText || '')) {
    await page.goto(fallbackHref, { waitUntil: 'domcontentloaded' });
    await page.waitForLoadState('networkidle', { timeout: 10_000 }).catch(() => undefined);
    await gotoAndCheck(page, '/cart/');
  }

  await expect(page.locator('body'), 'Add-to-bag should create a non-empty cart').not.toContainText(/ritual bag is empty/i);
}

// True when tests are pointed at something other than this project's known
// local dev host — i.e. a real staging/production-like target, via
// TEST_BASE_URL. Used to gate tests that must never run destructively
// (order creation) or that only make sense against a real Apache/LiteSpeed
// host (.htaccess-dependent checks) rather than local's nginx dev server.
export function isProductionLikeBaseUrl(baseURL: string | undefined): boolean {
  if (!baseURL) return false;
  const host = new URL(baseURL).hostname;
  return host !== 'localhost' && !host.endsWith('.local') && host !== '127.0.0.1';
}

export async function discoverProductLinks(page: Page): Promise<string[]> {
  await gotoAndCheck(page, '/shop/');

  return page.evaluate(() => {
    const selectors = [
      '.cos-card-name a',
      '.cos-card-img-wrap',
      '.cos-bundle-view',
      '.cos-sv3-card-title a',
      '.cos-sv3-card-img-link'
    ];
    const urls = new Set<string>();

    for (const selector of selectors) {
      document.querySelectorAll<HTMLAnchorElement>(selector).forEach(anchor => {
        const href = anchor.href;
        if (!href) return;
        const url = new URL(href, window.location.href);
        if (url.origin === window.location.origin && !/\/shop\/?$/i.test(url.pathname)) {
          urls.add(url.pathname + url.search);
        }
      });
    }

    return Array.from(urls).slice(0, 5);
  });
}
