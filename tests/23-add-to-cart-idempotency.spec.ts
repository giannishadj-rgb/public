import { expect, test, type APIRequestContext, type Browser, type BrowserContext, type Locator, type Page } from '@playwright/test';

/*
 * Add to bag must add exactly one unit per deliberate action, at every entry point:
 *   - with JavaScript: the AJAX paths (homepage collection bag, shop cards, shop ritual card,
 *     PDP form, PDP "complete the ritual" step cards). A double tap is one action.
 *   - without JavaScript (or when the AJAX call fails and falls back): the plain
 *     ?add-to-cart= links and the PDP form POST. These are processed once and then
 *     redirect to a clean /cart/ (post/redirect/get), so refresh, back and forward never
 *     add another unit.
 *
 * Every test starts from a fresh context, so a fresh, empty WooCommerce session.
 */

const PDP = {
  serum: '/products/advanced-repair-serum/',
  eye: '/products/revitalizing-eye-cream/'
};
const onPage = (path: string) => new RegExp(path.replace(/\//g, '\\/') + '$');

/** Units in the bag, read from WooCommerce's own fragments endpoint with the page's cookies. */
async function bagCount(request: APIRequestContext): Promise<number> {
  const res = await request.post('/?wc-ajax=get_refreshed_fragments');
  if (!res.ok()) return -1; // a slow local server answered badly; expect.poll asks again
  const json = await res.json().catch(() => null);
  if (!json) return -1;
  const html: string = json?.fragments?.['span.cos-bag-count'] ?? '';
  const m = html.match(/>(\d*)</);
  return m && m[1] ? parseInt(m[1], 10) : 0;
}

async function expectBag(page: Page, n: number) {
  await expect.poll(() => bagCount(page.request), { timeout: 8_000 }).toBe(n);
}

/* add_to_cart requests per page: started / finished, and when the last one started. */
const adds = new WeakMap<Page, { started: number; finished: number; last: number }>();
function trackAdds(page: Page) {
  const s = { started: 0, finished: 0, last: 0 };
  adds.set(page, s);
  const isAdd = (url: string) => /wc-ajax=add_to_cart/.test(url);
  page.on('request', r => { if (isAdd(r.url())) { s.started++; s.last = Date.now(); } });
  page.on('requestfinished', r => { if (isAdd(r.url())) s.finished++; });
  page.on('requestfailed', r => { if (isAdd(r.url())) s.finished++; });
}

/** Wait until no add_to_cart request is in flight and none has started for a full second
 *  (a second tap's request is queued behind the first, so "the bag shows 1" alone proves nothing). */
async function settle(page: Page) {
  const s = adds.get(page);
  if (!s) { await page.waitForLoadState('networkidle').catch(() => undefined); return; }
  await expect.poll(() => s.started === s.finished && Date.now() - s.last > 1000, { timeout: 10_000, intervals: [200] }).toBe(true);
}

test.beforeEach(({ page }) => trackAdds(page));

/** The mini-bag drawer opens after every add and sits over the page; close it first. */
async function closeBag(page: Page) {
  if (await page.locator('#hxn-bag.is-open').count()) {
    await page.keyboard.press('Escape');
    await expect(page.locator('#hxn-bag.is-open')).toHaveCount(0);
  }
}

async function reveal(el: Locator) {
  await closeBag(el.page());
  await el.scrollIntoViewIfNeeded();
  await el.hover().catch(() => undefined);
}

async function noJsContext(browser: Browser, baseURL: string | undefined): Promise<BrowserContext> {
  return browser.newContext({ javaScriptEnabled: false, baseURL });
}

function expectCleanCart(page: Page) {
  const u = new URL(page.url());
  expect(u.pathname.replace(/\/?$/, '/')).toBe('/cart/');
  expect(u.search, 'the cart URL must carry no add-to-cart query').toBe('');
}

/* ─── With JavaScript: one tap, one unit; double tap, one unit; refresh adds nothing ─── */

test.describe('add to bag with JavaScript', () => {
  test('homepage collection bag', async ({ page }) => {
    await page.goto('/');
    const bag = page.locator('.hxc__bag').first();
    await reveal(bag);
    await bag.click();
    await settle(page);
    await expectBag(page, 1);
    await page.reload();
    await settle(page);
    await expectBag(page, 1);

    await reveal(bag);
    await bag.dblclick();
    await settle(page);
    await expectBag(page, 2);
  });

  test('shop product card and ritual card', async ({ page }) => {
    await page.goto('/shop/');
    const card = page.locator('.hxshop-item__add').first();
    await reveal(card);
    await card.click();
    await settle(page);
    await expectBag(page, 1);
    await reveal(card);
    await card.dblclick();
    await settle(page);
    await expectBag(page, 2);

    const ritual = page.locator('.hxshop-ritual__add').first();
    await reveal(ritual);
    await ritual.dblclick();
    await settle(page);
    await expectBag(page, 3);

    await page.reload();
    await settle(page);
    await expectBag(page, 3);
  });

  test('a double Enter on a shop card or PDP step card adds once', async ({ page }) => {
    await page.goto('/shop/');
    const card = page.locator('.hxshop-item__add').first();
    await reveal(card);
    await card.focus();
    await page.keyboard.press('Enter');
    await page.keyboard.press('Enter');
    await settle(page);
    await expectBag(page, 1);

    await page.goto(PDP.serum);
    const step = page.locator('#hxpd-ritual .hxc__item:not(.is-owned) .hxc__bag').first(); // a formula already in the bag is marked .is-owned (3 Nov 2026: the step cards became the homepage tiles)
    await reveal(step);
    await step.focus();
    await page.keyboard.press('Enter');
    await page.keyboard.press('Enter');
    await settle(page);
    await expectBag(page, 2);
  });

  test('product page form, double tap and refresh', async ({ page }) => {
    await page.goto(PDP.serum);
    const btn = page.locator('#cos-pdp-atc-btn');
    await reveal(btn);
    await btn.click();
    await settle(page);
    await expectBag(page, 1);
    await expect(page).toHaveURL(onPage(PDP.serum));
    await reveal(btn);
    await btn.dblclick();
    await settle(page);
    await expectBag(page, 2);
    await page.reload();
    await settle(page);
    await expectBag(page, 2);
  });

  test('product page phone bar, double tap', async ({ page, isMobile }) => {
    test.skip(!isMobile, 'the sticky bag bar is a phone control');
    await page.goto(PDP.serum);
    await page.locator('#cos-pdp-atc-btn').scrollIntoViewIfNeeded();
    await page.evaluate(() => window.scrollBy(0, 2400));
    const bar = page.locator('[data-hxpd-bar-btn]');
    await expect(page.locator('[data-hxpd-bar].is-on')).toHaveCount(1);
    await bar.dblclick();
    await settle(page);
    await expectBag(page, 1);
  });

  test('product page "Your ritual" tiles', async ({ page }) => {
    let expected = 0;
    for (const url of [PDP.serum, PDP.eye]) {
      await page.goto(url);
      const add = page.locator('#hxpd-ritual .hxc__item:not(.is-owned) .hxc__bag').first();
      await reveal(add);
      await add.dblclick();
      await settle(page);
      expected += 1;
      await expectBag(page, expected);
      await page.reload();
      await settle(page);
      await expectBag(page, expected);
    }
  });

  test('back and forward restore a working button and add nothing', async ({ page }) => {
    await page.goto(PDP.serum);
    const btn = page.locator('#cos-pdp-atc-btn');
    await reveal(btn);
    await btn.click();
    await settle(page);
    await expectBag(page, 1);
    await page.goto('/cart/');
    await page.goBack();
    await settle(page);
    await expectBag(page, 1);
    await page.goForward();
    await settle(page);
    await expectBag(page, 1);
    await page.goBack();
    await settle(page);
    // the button still works after coming back (not stuck "Adding…")
    await reveal(btn);
    await btn.click();
    await expectBag(page, 2);

    await page.goto('/');
    const bag = page.locator('.hxc__bag').first();
    await reveal(bag);
    await bag.click();
    await expectBag(page, 3);
    await page.goto('/cart/');
    await page.goBack();
    await settle(page);
    await expectBag(page, 3);
    await reveal(bag);
    await bag.click();
    await expectBag(page, 4);
  });
});

/* ─── Without JavaScript: post/redirect/get to a clean /cart/ ─── */

test.describe('add to bag without JavaScript (post/redirect/get)', () => {
  const links: Array<[string, string, string]> = [
    ['homepage collection bag', '/', '.hxc__bag'],
    ['shop product card', '/shop/', '.hxshop-item__add'],
    ['shop ritual card', '/shop/', '.hxshop-ritual__add'],
    ['PDP step card (serum page)', PDP.serum, '#hxpd-ritual .hxc__bag'],
    ['PDP step card (eye cream page)', PDP.eye, '#hxpd-ritual .hxc__bag']
  ];

  for (const [name, from, sel] of links) {
    test(`${name}: link adds once, lands on clean /cart/, refresh/back/forward add nothing`, async ({ browser, baseURL }) => {
      const ctx = await noJsContext(browser, baseURL);
      const page = await ctx.newPage();
      await page.goto(from);
      const href = await page.locator(sel).first().getAttribute('href');
      expect(href).toContain('add-to-cart=');
      await page.goto(href!);
      expectCleanCart(page);
      await expectBag(page, 1);

      await page.reload();
      expectCleanCart(page);
      await expectBag(page, 1);

      await page.goBack();
      await expect(page).toHaveURL(onPage(from));
      await expectBag(page, 1);
      await page.goForward();
      expectCleanCart(page);
      await expectBag(page, 1);
      await ctx.close();
    });
  }

  test('PDP form POST redirects to clean /cart/; refresh does not resubmit', async ({ browser, baseURL }) => {
    const ctx = await noJsContext(browser, baseURL);
    const page = await ctx.newPage();
    await page.goto(PDP.serum);
    await page.locator('#cos-pdp-atc-btn').click();
    await page.waitForLoadState('domcontentloaded');
    expectCleanCart(page);
    await expectBag(page, 1);
    await page.reload();
    await expectBag(page, 1);
    await page.goBack();
    await expect(page).toHaveURL(onPage(PDP.serum));
    await expectBag(page, 1);
    await page.goForward();
    expectCleanCart(page);
    await expectBag(page, 1);
    await ctx.close();
  });

  test('a double-tapped link (two requests back to back) adds once', async ({ browser, baseURL }) => {
    const ctx = await noJsContext(browser, baseURL);
    const page = await ctx.newPage();
    await page.goto('/');
    const href = await page.locator('.hxc__bag').first().getAttribute('href');
    for (let i = 0; i < 2; i++) {
      const r = await page.request.get(href!, { maxRedirects: 0 });
      expect(r.status()).toBe(302);
      const to = new URL(r.headers()['location'], baseURL);
      expect(to.pathname).toBe('/cart/');
      expect(to.search).toBe('');
    }
    await expectBag(page, 1);
    await ctx.close();
  });
});
