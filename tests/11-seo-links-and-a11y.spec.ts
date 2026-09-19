import { expect, test } from '@playwright/test';
import { gotoAndCheck } from './helpers/cos-helpers';

test.describe('SEO regressions: canonical, robots, sitemap, broken links', () => {
  test('non-production environments are hard-blocked from indexing on three independent layers', async ({ page, request, baseURL }) => {
    // This local environment has WP_ENVIRONMENT_TYPE = 'local' (wp-config.php)
    // — the same non-production posture staging should have. Regression
    // guard for the reported staging finding: indexability must not depend
    // solely on the manually-toggled "Discourage search engines" option,
    // which is exactly the kind of setting a DB clone/migration silently
    // carries over in the wrong state. IMPORTANT: this protection is keyed
    // off WP_ENVIRONMENT_TYPE specifically — if a given environment's
    // wp-config.php never defines that constant, WordPress's own default is
    // 'production', and it is indistinguishable from real production here.
    const response = await gotoAndCheck(page, '/');
    const headers = response?.headers() ?? {};
    expect(headers['x-robots-tag'], 'X-Robots-Tag header').toMatch(/noindex/i);

    const robotsMeta = page.locator('meta[name="robots"]');
    await expect(robotsMeta, 'robots meta tag').toHaveAttribute('content', /noindex/i);

    const robotsTxt = await request.get(`${baseURL}robots.txt`);
    const robotsTxtBody = await robotsTxt.text();
    expect(robotsTxtBody, 'robots.txt should blanket-disallow, not list a sitemap or permissive rules').toMatch(/Disallow:\s*\/\s*$/m);
  });

  test('shop has a self-referencing canonical', async ({ page }) => {
    await gotoAndCheck(page, '/shop/');
    const canonical = page.locator('link[rel="canonical"]');
    await expect(canonical).toHaveCount(1);
    await expect(canonical).toHaveAttribute('href', /\/shop\/?$/);
  });

  test('the XML sitemap returns HTTP 200 with a well-formed body, not a 404 despite valid content', async ({ request, baseURL }) => {
    // Regression guard for a real, non-obvious bug: WP_Query correctly sets
    // is_sitemap() and the sitemap renders the right XML body, but
    // WP::handle_404() only exempts is_admin()/is_robots()/is_favicon() from
    // its "no matching posts -> 404" fallback — is_sitemap() was never added
    // to that list, so every sitemap request got marked 404 even though the
    // body was always correct. Confirmed via a request-level stack trace
    // during diagnosis. Fixed via a pre_handle_404 filter in inc/seo.php
    // (the documented, core-file-free extension point WP_Sitemaps itself
    // used to rely on for this exact scenario before that mechanism was
    // deprecated in 6.7.0) — this test guards against the filter being
    // removed or a future core change reintroducing the mismatch.
    const response = await request.get(`${baseURL}wp-sitemap.xml`);
    expect(response.status(), 'wp-sitemap.xml must return 200, not 404-with-valid-body').toBe(200);
    const body = await response.text();
    expect(body).toContain('<sitemapindex');
    expect(body).toContain('<loc>');

    // Every sub-sitemap listed in the index must also be independently 200,
    // not just the index itself.
    const subSitemapUrls = Array.from(body.matchAll(/<loc>([^<]+)<\/loc>/g)).map(m => m[1]);
    expect(subSitemapUrls.length).toBeGreaterThan(0);
    for (const url of subSitemapUrls) {
      const subResponse = await request.get(url);
      expect(subResponse.status(), `${url} must return 200`).toBe(200);
    }
  });

  test('cart, checkout, and account are noindex and excluded from the XML sitemap', async ({ page, request, baseURL }) => {
    for (const path of ['/cart/', '/account/']) {
      await gotoAndCheck(page, path);
      const robots = page.locator('meta[name="robots"]');
      await expect(robots).toHaveAttribute('content', /noindex/);
    }

    const sitemapIndex = await request.get(`${baseURL}wp-sitemap.xml`);
    expect(sitemapIndex.ok()).toBe(true);
    const indexBody = await sitemapIndex.text();
    const pageSitemapUrls = Array.from(indexBody.matchAll(/<loc>([^<]*posts-page[^<]*)<\/loc>/g)).map(m => m[1]);
    expect(pageSitemapUrls.length).toBeGreaterThan(0);

    let combined = '';
    for (const url of pageSitemapUrls) {
      const response = await request.get(url);
      combined += await response.text();
    }
    expect(combined).not.toMatch(/\/cart\/?</);
    expect(combined).not.toMatch(/\/checkout\/?</);
    expect(combined).not.toMatch(/\/account\/?</);
  });

  test('Privacy Policy and Terms are noindex and excluded from the XML sitemap, but remain fully reachable', async ({ page, request, baseURL }) => {
    // These carry a real legal/compliance purpose (still linked from the
    // footer, still a normal 200 page) but no organic-search intent, so
    // unlike cart/checkout/account this is noindex,follow — not blocked
    // from crawling entirely, just excluded from ranking consideration.
    for (const path of ['/privacy/', '/terms/']) {
      const response = await gotoAndCheck(page, path);
      expect(response?.status(), `${path} should still be a normal, reachable page`).toBeLessThan(400);
      const robots = page.locator('meta[name="robots"]');
      await expect(robots).toHaveAttribute('content', /noindex/);
      const footerLinkCount = await page.locator('#cos-footer a[href="' + path + '"]').count();
      expect(footerLinkCount, `${path} should still be linked from the footer`).toBeGreaterThanOrEqual(1);
    }

    const sitemapIndex = await request.get(`${baseURL}wp-sitemap.xml`);
    const indexBody = await sitemapIndex.text();
    const pageSitemapUrls = Array.from(indexBody.matchAll(/<loc>([^<]*posts-page[^<]*)<\/loc>/g)).map(m => m[1]);
    let combined = '';
    for (const url of pageSitemapUrls) {
      combined += await (await request.get(url)).text();
    }
    expect(combined).not.toMatch(/\/privacy\/?</);
    expect(combined).not.toMatch(/\/terms\/?</);
  });

  test('an empty product category archive redirects to /shop/ and is excluded from the XML sitemap', async ({ page, request, baseURL }) => {
    // WooCommerce auto-creates "Uncategorized" and assigns any product with
    // no other category to it. It must never be served as an indexable 200 —
    // either it's genuinely empty (nothing to show, redirect) or it silently
    // becomes a near-duplicate of /shop/ if unfiltered product queries render
    // it (see inc/commerce.php's coupon-form regression for another example
    // of what un-scoped queries produce on this theme).
    const response = await page.goto('/product-category/uncategorized/', { waitUntil: 'domcontentloaded' });
    expect(response?.status(), 'Empty category archive should redirect, not 200').toBeLessThan(400);
    await expect(page).toHaveURL(/\/shop\/?$/);

    const sitemapIndex = await request.get(`${baseURL}wp-sitemap.xml`);
    expect(sitemapIndex.ok()).toBe(true);
    const indexBody = await sitemapIndex.text();
    const taxonomySitemapUrls = Array.from(indexBody.matchAll(/<loc>([^<]*taxonomies-product_cat[^<]*)<\/loc>/g)).map(m => m[1]);

    let combined = '';
    for (const url of taxonomySitemapUrls) {
      const sitemapResponse = await request.get(url);
      combined += await sitemapResponse.text();
    }
    expect(combined).not.toMatch(/\/product-category\/uncategorized\/?</);
  });

  test('previously-generic meta descriptions are hand-written, not extracted page text', async ({ page }) => {
    // These pages used to have no entry in cos_child_seo_page_meta(), so their
    // description fell through to wp_trim_words() on raw page/post content —
    // producing 200-222 character strings that read as concatenated body copy
    // rather than a written summary. Flagged by an SEO review 2026-08-17.
    const targets = [
      '/heritage/sustainability/',
      '/science/ingredients/',
      '/science/ingredients/caviar-extract/',
      '/sitemap/',
      '/journal/why-caviar-extract-works/'
    ];
    for (const path of targets) {
      await gotoAndCheck(page, path);
      const description = page.locator('meta[name="description"]').first();
      const content = await description.getAttribute('content');
      expect(content, `${path} should have a meta description`).toBeTruthy();
      expect((content as string).length, `${path} description length`).toBeLessThan(180);
    }
  });

  test('the 404 page CTAs all resolve, and the shop fallback label accurately describes its destination', async ({ page, request, baseURL }) => {
    await page.goto('/this-page-does-not-exist-xyz/', { waitUntil: 'domcontentloaded' });

    // No actual ritual-finder/quiz exists on this site (that page is
    // trashed — see OWNER-ACTIONS.md), so this CTA must not promise one.
    await expect(page.getByText('Find My Ritual', { exact: true })).toHaveCount(0);
    const shopLink = page.locator('.cos-404-link-outline[href="/shop/"]');
    await expect(shopLink).toBeVisible();
    await expect(shopLink).toHaveText(/shop/i);

    const hrefs = await page.locator('.cos-404-links a').evaluateAll(links =>
      links.map(link => (link as HTMLAnchorElement).getAttribute('href'))
    );
    for (const href of hrefs) {
      if (!href) continue;
      const response = await request.get(`${baseURL}${href.replace(/^\//, '')}`);
      expect(response.status(), `${href} should not 404`).toBeLessThan(400);
    }
  });

  test('Product JSON-LD descriptions never concatenate list/paragraph text with no separating space', async ({ page }) => {
    for (const path of ['/products/the-complete-ritual/', '/products/advanced-repair-serum/']) {
      await gotoAndCheck(page, path);
      const scripts = await page.locator('script[type="application/ld+json"]').allTextContents();
      const parsed = scripts.map(s => JSON.parse(s));
      const productNode = parsed
        .flatMap(entry => (Array.isArray(entry['@graph']) ? entry['@graph'] : [entry]))
        .find((node: any) => node['@type'] === 'Product');

      expect(productNode, `${path} should include Product JSON-LD`).toBeTruthy();
      const description: string = productNode.description;
      expect(description, `${path} Product description should be non-empty`).toBeTruthy();
      // The concatenation bug always produced a period directly followed by
      // a letter with no space, where two HTML block elements used to meet
      // (e.g. "...individual purchase.All 4 products...").
      expect(description, `${path} description has a missing space after a period: "${description}"`)
        .not.toMatch(/\.[A-Za-z]/);
      expect(description).not.toMatch(/\s{2,}/);
    }
  });

  test('shop page publishes CollectionPage and ItemList JSON-LD, in real page order with real URLs', async ({ page, request }) => {
    await gotoAndCheck(page, '/shop/');
    const scripts = await page.locator('script[type="application/ld+json"]').allTextContents();
    const graphNodes = scripts.map(s => JSON.parse(s)).flatMap(entry => (Array.isArray(entry['@graph']) ? entry['@graph'] : [entry]));

    const collectionPage = graphNodes.find((node: any) => node['@type'] === 'CollectionPage');
    const itemList = graphNodes.find((node: any) => node['@type'] === 'ItemList');
    expect(collectionPage, 'Shop should include CollectionPage JSON-LD').toBeTruthy();
    expect(itemList, 'Shop should include ItemList JSON-LD').toBeTruthy();
    expect(collectionPage.mainEntity['@id']).toBe(itemList['@id']);

    const items = itemList.itemListElement;
    expect(items.length, 'ItemList should include the bundle plus all 4 individual products').toBe(5);

    // The Complete Ritual is featured in its own section above the grid on
    // the real page, so position 1 must be the bundle, not alphabetical or
    // database-ID order.
    expect(items[0].name).toBe('The Complete Ritual');
    expect(items[0].position).toBe(1);

    // Every position must be sequential with no gaps, and every URL a real,
    // reachable product page — not just well-formed JSON.
    for (let i = 0; i < items.length; i++) {
      expect(items[i].position).toBe(i + 1);
      expect(items[i].name, `Item ${i + 1} should have a name`).toBeTruthy();
      expect(items[i].url, `Item ${i + 1} should have a URL`).toMatch(/^https?:\/\//);
      const response = await request.get(items[i].url);
      expect(response.status(), `${items[i].url} should be reachable`).toBeLessThan(400);
    }
  });

  test('primary navigation and footer links all resolve without 404s', async ({ page, request, baseURL }) => {
    await gotoAndCheck(page, '/');

    const hrefs = await page.evaluate(() => {
      const urls = new Set<string>();
      document.querySelectorAll<HTMLAnchorElement>('#cos-nav a[href], #cos-footer a[href]').forEach(a => {
        const url = new URL(a.href, window.location.href);
        if (url.origin === window.location.origin) urls.add(url.pathname);
      });
      return Array.from(urls);
    });

    expect(hrefs.length).toBeGreaterThan(5);

    const failures: string[] = [];
    for (const path of hrefs) {
      const response = await request.get(`${baseURL}${path.replace(/^\//, '')}`);
      if (response.status() >= 400) failures.push(`${path} -> ${response.status()}`);
    }
    expect(failures).toEqual([]);
  });

  test('the-ritual page has a valid heading hierarchy (no level skipped)', async ({ page }) => {
    await gotoAndCheck(page, '/the-ritual/');

    const levels = await page.evaluate(() =>
      Array.from(document.querySelectorAll('h1,h2,h3,h4,h5,h6')).map(h => Number(h.tagName[1]))
    );

    expect(levels[0], 'Page should start at H1').toBe(1);
    for (let i = 1; i < levels.length; i++) {
      const jump = levels[i] - levels[i - 1];
      expect(jump, `Heading level jumped from H${levels[i - 1]} to H${levels[i]} at index ${i}`).toBeLessThanOrEqual(1);
    }

    const h1Count = await page.locator('h1').count();
    expect(h1Count, 'Exactly one H1 per principal page').toBe(1);
  });
});
