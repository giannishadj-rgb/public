import { expect, test } from '@playwright/test';
import {
  expectMinimumTapTargets,
  expectNoHorizontalOverflow,
  expectPreferredTapTargets,
  gotoAndCheck,
} from './helpers/cos-helpers';

/**
 * Coverage for the Complete Ritual (COS-BUNDLE) page, editorial redesign
 * 2026-09-20, revised 2026-09-22 — rebuilds the page as a story about the
 * complete four-step ritual (Desire → Discovery → Understanding → Proof →
 * Purchase): a poster hero, a footnoted brand statement immediately
 * followed by a "Discover the Range" grid of the four individual products,
 * a 3-panel Discovery Strip, a cinematic visual break, an alternating
 * "Four Acts of Care" sequence, a merged "Designed to Work Together" +
 * Who It's For section, a quiet Value Moment in place of the old
 * comparison table, the navy clinical-proof section, and a composed Final
 * Purchase Moment before the FAQ. The 2026-09-20 packaging gallery
 * ("Considered Down to the Box") was removed 2026-09-22, and no section
 * anywhere on this page carries "Chapter" labelling any more -- eyebrows
 * are either a plain category label or omitted. This suite guards the
 * current structure, and that non-bundle PDPs were left completely
 * untouched.
 */

const BUNDLE_URL = '/products/the-complete-ritual/';

test.describe('Bundle page: structure and narrative', () => {
  test('sections appear in the required order: acts, how it works, clinical, faq', async ({ page }) => {
    await gotoAndCheck(page, BUNDLE_URL);
    const ids = ['pdp-acts', 'pdp-how-it-works', 'pdp-clinical', 'pdp-faq'];
    const positions = await Promise.all(ids.map(id => page.locator('#' + id).evaluate(el => el.getBoundingClientRect().top + window.scrollY)));
    for (let i = 1; i < positions.length; i++) {
      expect(positions[i], `#${ids[i]} should come after #${ids[i - 1]}`).toBeGreaterThan(positions[i - 1]);
    }
    // Value Moment and the Final Purchase Moment don't carry ids (nothing
    // links to them), but should still sit between Designed to Work
    // Together and the FAQ in source order.
    const classOrder = await page.evaluate(() => {
      const sel = ['.cos-pdp-value', '.cos-pdp-final'];
      return sel.map(s => document.querySelector(s)!.getBoundingClientRect().top + window.scrollY);
    });
    expect(classOrder[0]).toBeGreaterThan(positions[1]); // value moment after how-it-works
    expect(classOrder[1]).toBeGreaterThan(classOrder[0]); // final after value moment
    expect(classOrder[1]).toBeGreaterThan(positions[2]); // final after clinical
    expect(positions[3]).toBeGreaterThan(classOrder[1]); // faq after final
  });

  test('no leftover packaging-gallery section or "Chapter" labelling exists anywhere on the page', async ({ page }) => {
    await gotoAndCheck(page, BUNDLE_URL);
    await expect(page.locator('.cos-pdp-bundle-presentation')).toHaveCount(0);
    await expect(page.locator('#cos-pres-track')).toHaveCount(0);
    const body = await page.locator('body').textContent();
    expect(body).not.toMatch(/Chapter (One|Two|Three|Four)/i);
  });

  test('the SKU never appears as a customer-facing bullet, and the page has no leftover comparison table or inclusion list', async ({ page }) => {
    await gotoAndCheck(page, BUNDLE_URL);
    const body = await page.locator('body').textContent();
    expect(body).not.toMatch(/SKU:\s*COS-BUNDLE/i);
    await expect(page.locator('.cos-pdp-compare-table')).toHaveCount(0);
    await expect(page.locator('.cos-pdp-bundle-includes')).toHaveCount(0);
    await expect(page.locator('.cos-pdp-bundle-nav')).toHaveCount(0);
  });

  test('the Value Moment states the bundle price, the individual total, and the saving, plus a direct add-to-bag CTA', async ({ page }) => {
    await gotoAndCheck(page, BUNDLE_URL);
    const value = page.locator('.cos-pdp-value');
    await expect(value).toContainText('€450');
    await expect(value).toContainText('€500');
    await expect(value).toContainText('€50');
    await expect(value.locator('.cos-pdp-value-cta')).toHaveText('Add Complete Ritual to Bag');
  });

  test('clinical results show exactly three stats in a three-column desktop grid with no empty fourth block', async ({ page }) => {
    await page.setViewportSize({ width: 1440, height: 900 });
    await gotoAndCheck(page, BUNDLE_URL);
    const stats = page.locator('#pdp-clinical .cos-pdp-stat');
    await expect(stats).toHaveCount(3);
    await expect(page.locator('#pdp-clinical .cos-pdp-stats')).not.toHaveClass(/cos-pdp-stats-4/);
  });

  test('Four Acts of Care shows all four products, each with a large image, an evocative line, a practical benefit, and a working product link', async ({ page }) => {
    await gotoAndCheck(page, BUNDLE_URL);
    const rows = page.locator('#pdp-acts .cos-pdp-acts-row');
    await expect(rows).toHaveCount(4);
    const names = await page.locator('#pdp-acts .cos-pdp-acts-name').allTextContents();
    expect(names).toEqual(['Micellar Water Cleanser', 'Advanced Repair Serum', 'Revitalizing Eye Cream', '24h Regeneration Cream']);
    const acts = await page.locator('#pdp-acts .cos-pdp-acts-step').allTextContents();
    expect(acts.map(a => a.trim())).toEqual(['01 — Cleanse', '02 — Repair', '03 — Restore', '04 — Seal']);
    for (const row of await rows.all()) {
      await expect(row.locator('.cos-pdp-acts-media img')).toHaveCount(1);
      await expect(row.locator('.cos-pdp-acts-evocative')).toBeVisible();
      await expect(row.locator('.cos-pdp-acts-benefit')).toBeVisible();
      await expect(row.locator('.cos-pdp-acts-link')).toHaveText('View product');
      await expect(row.locator('.cos-pdp-acts-link')).toHaveAttribute('href', /\/products\//);
    }
  });

  test('Four Acts of Care rows alternate media side on desktop', async ({ page }) => {
    await page.setViewportSize({ width: 1440, height: 900 });
    await gotoAndCheck(page, BUNDLE_URL);
    const orders = await page.locator('#pdp-acts .cos-pdp-acts-media').evaluateAll(els =>
      els.map(el => getComputedStyle(el).order)
    );
    // nth-child(even) rows swap their media to the right via order:2; odd
    // rows keep the initial (unset/"0") order, so the sequence should
    // alternate rather than be uniform.
    expect(new Set(orders).size).toBeGreaterThan(1);
  });

  test('Designed to Work Together merges in Who It\'s For as one paragraph plus a plain-text concerns line, not pill tags', async ({ page }) => {
    await gotoAndCheck(page, BUNDLE_URL);
    const howItWorks = page.locator('#pdp-how-it-works');
    await expect(howItWorks.locator('img')).toHaveCount(1);
    await expect(howItWorks.locator('.cos-pdp-howworks-step')).toHaveCount(4);
    await expect(howItWorks.locator('.cos-pdp-howworks-who-text')).toBeVisible();
    await expect(howItWorks.locator('.cos-pdp-concern-tag')).toHaveCount(0);
    await expect(howItWorks.locator('.cos-pdp-howworks-concerns')).toContainText('Daily impurities');
    // The old separate "who it's for" section and price-comparison table no
    // longer exist as distinct sections on the bundle page.
    await expect(page.locator('#pdp-who')).toHaveCount(0);
    await expect(page.locator('#pdp-compare')).toHaveCount(0);
  });

  test('the Final Purchase Moment states the name, price, saving, and a working Add to Bag, on a navy background with no floating card', async ({ page }) => {
    await gotoAndCheck(page, BUNDLE_URL);
    const final = page.locator('.cos-pdp-final');
    await expect(final).toContainText('The Complete Ritual');
    await expect(final).toContainText('€450');
    await expect(final).toContainText('€50');
    await expect(final.locator('.cos-pdp-final-cta')).toHaveText('Add to Bag');
    // Compare against the same --cos-navy custom property Proven Results
    // uses, rather than a hardcoded hex, so this never drifts from the
    // site's real navy token.
    const [finalBg, clinicalBg] = await Promise.all([
      final.evaluate(el => getComputedStyle(el).backgroundColor),
      page.locator('#pdp-clinical').evaluate(el => getComputedStyle(el).backgroundColor),
    ]);
    expect(finalBg).toBe(clinicalBg);
  });

});

test.describe('Bundle page: Discover the Range grid', () => {
  test('shows all four individual products with image, name, price, and a working link, appended directly onto the Statement section (before the Discovery Strip)', async ({ page }) => {
    await gotoAndCheck(page, BUNDLE_URL);
    const statementTop = await page.locator('#pdp-statement').evaluate(el => el.getBoundingClientRect().top + window.scrollY);
    const discoverTop = await page.locator('#pdp-discover').evaluate(el => el.getBoundingClientRect().top + window.scrollY);
    const discoveryStripTop = await page.locator('.cos-pdp-discovery').evaluate(el => el.getBoundingClientRect().top + window.scrollY);
    expect(discoverTop).toBeGreaterThan(statementTop);
    expect(discoveryStripTop).toBeGreaterThan(discoverTop);

    const cards = page.locator('#pdp-discover .cos-pdp-discover-card');
    await expect(cards).toHaveCount(4);
    const names = await page.locator('#pdp-discover .cos-pdp-discover-name').allTextContents();
    expect(names).toEqual(['Micellar Water Cleanser', 'Advanced Repair Serum', 'Revitalizing Eye Cream', '24h Regeneration Cream']);
    for (const card of await cards.all()) {
      await expect(card.locator('img')).toHaveCount(1);
      await expect(card).toHaveAttribute('href', /\/products\//);
    }
  });
});

test.describe('Bundle page: hero and purchase area', () => {
  test('the hero is a full-bleed poster image with no purchase chrome overlaid on it, using object-fit:cover edge-to-edge', async ({ page }) => {
    await gotoAndCheck(page, BUNDLE_URL);
    const heroImg = page.locator('.cos-pdp-hero .cos-pdp-gallery-main img');
    await expect(heroImg).toHaveAttribute('alt', /all four/i);
    await expect(heroImg).toHaveCSS('object-fit', 'cover');
    await expect(page.locator('.cos-pdp-hero video')).toHaveCount(0);
    await expect(page.locator('.cos-pdp-hero #cos-pdp-atc-btn')).toHaveCount(0);
    await expect(page.locator('.cos-pdp-hero .cos-pdp-price-row')).toHaveCount(0);
  });

  test('desktop: the poster hero spans the full width, and the purchase panel sits in its own centred section directly below it, not split beside the image', async ({ page }) => {
    await page.setViewportSize({ width: 1440, height: 900 });
    await gotoAndCheck(page, BUNDLE_URL);
    const [heroBox, purchaseBox, imgBox] = await Promise.all([
      page.locator('.cos-pdp-hero').boundingBox(),
      page.locator('#pdp-purchase').boundingBox(),
      page.locator('.cos-pdp-img-col').boundingBox(),
    ]);
    expect(imgBox!.width).toBeGreaterThanOrEqual(1440 * 0.95);
    expect(purchaseBox!.y).toBeGreaterThanOrEqual(heroBox!.y + heroBox!.height - 1);
  });

  test('Add to Bag sits below the poster hero on desktop (not forced into the first viewport), and the sticky Add to Bag bar reveals itself on desktop once scrolled past it', async ({ page }) => {
    await page.setViewportSize({ width: 1440, height: 900 });
    await gotoAndCheck(page, BUNDLE_URL);
    const atc = page.locator('#cos-pdp-atc-btn');
    const heroBox = await page.locator('.cos-pdp-hero').boundingBox();
    const atcBox = await atc.boundingBox();
    expect(atcBox, 'Add to Bag button should be present').not.toBeNull();
    expect(atcBox!.y).toBeGreaterThanOrEqual(heroBox!.y + heroBox!.height - 1);

    const sticky = page.locator('#cos-pdp-sticky-atc');
    await expect(sticky).not.toHaveClass(/is-visible/);
    await atc.scrollIntoViewIfNeeded();
    await page.mouse.wheel(0, 400);
    await page.waitForTimeout(300);
    await expect(sticky).toHaveClass(/is-visible/);
  });

  test('mobile order is gallery, title, price, summary, quantity, Add to Bag, reassurance', async ({ page }) => {
    await page.setViewportSize({ width: 390, height: 844 });
    await gotoAndCheck(page, BUNDLE_URL);
    const order = await page.evaluate(() => {
      const sel = [
        ['.cos-pdp-gallery-main', 'gallery'],
        ['.cos-pdp-name', 'title'],
        ['.cos-pdp-price-row', 'price'],
        ['.cos-pdp-short-desc', 'summary'],
        ['.cos-pdp-qty-row', 'quantity'],
        ['#cos-pdp-atc-btn', 'atc'],
        ['.cos-pdp-trust', 'reassurance'],
      ] as const;
      return sel.map(([selector, label]) => {
        const el = document.querySelector(selector);
        return { label, top: el ? el.getBoundingClientRect().top + window.scrollY : Infinity };
      });
    });
    for (let i = 1; i < order.length; i++) {
      expect(order[i].top, `${order[i].label} should come after ${order[i - 1].label}`).toBeGreaterThan(order[i - 1].top);
    }
  });

  test('price, strikethrough original, and saving render together in one row, as plain text with no bordered tag; the short description is a concise two-sentence summary', async ({ page }) => {
    await gotoAndCheck(page, BUNDLE_URL);
    const priceRow = page.locator('.cos-pdp-price-row');
    await expect(priceRow.locator('.cos-pdp-price')).toContainText('€450');
    await expect(priceRow.locator('.cos-pdp-sale-original')).toContainText('€500');
    await expect(priceRow.locator('.cos-pdp-save')).toContainText('€50');
    const saveBorder = await priceRow.locator('.cos-pdp-save').evaluate(el => getComputedStyle(el).borderStyle);
    expect(saveBorder).toBe('none');

    const summary = await page.locator('.cos-pdp-short-desc').textContent();
    const sentenceCount = (summary ?? '').split(/(?<=[.!?])\s+/).filter(Boolean).length;
    expect(sentenceCount, 'Short description should be a concise two-sentence summary').toBeLessThanOrEqual(2);
    expect(summary).not.toMatch(/SKU/i);
  });

  test('the four-row inclusion table no longer appears in the hero', async ({ page }) => {
    await gotoAndCheck(page, BUNDLE_URL);
    await expect(page.locator('.cos-pdp-bundle-includes')).toHaveCount(0);
  });

  test('reassurance (shipping, returns, payment, authenticity) sits immediately below the CTA', async ({ page }) => {
    await gotoAndCheck(page, BUNDLE_URL);
    const order = await page.locator('.cos-pdp-info > *').evaluateAll(els =>
      els.map(e => e.className.split(' ')[0]).filter(c => ['cos-pdp-form', 'cos-pdp-trust'].includes(c))
    );
    expect(order).toEqual(['cos-pdp-form', 'cos-pdp-trust']);
  });
});

test.describe('Bundle page: no section navigation', () => {
  test('the section-navigation bar has been removed entirely', async ({ page }) => {
    await gotoAndCheck(page, BUNDLE_URL);
    await expect(page.locator('.cos-pdp-bundle-nav')).toHaveCount(0);
    await expect(page.locator('.cos-pdp-bundle-nav-link')).toHaveCount(0);
    await expect(page.locator('#cos-bundle-nav')).toHaveCount(0);
  });
});

test.describe('Bundle page: functionality and safety', () => {
  test('quantity stepper and Add to Bag work', async ({ page }) => {
    await gotoAndCheck(page, BUNDLE_URL);
    const qty = page.locator('#cos-qty');
    await expect(qty).toHaveValue('1');
    await page.locator('.cos-pdp-qty-btn[aria-label="Increase quantity"]').click();
    await expect(qty).toHaveValue('2');
    await page.locator('.cos-pdp-qty-btn[aria-label="Decrease quantity"]').click();
    await expect(qty).toHaveValue('1');
  });

  test('the Value Moment CTA actually adds the bundle to the cart', async ({ page }) => {
    await gotoAndCheck(page, BUNDLE_URL);
    const addToCartResponse = page.waitForResponse(resp => resp.url().includes('wc-ajax=add_to_cart') && resp.status() === 200);
    await page.locator('.cos-pdp-value-cta').click();
    await addToCartResponse;
    await expect(page.locator('.cos-pdp-value-cta')).toHaveClass(/added/);
    await gotoAndCheck(page, '/cart/');
    await expect(page.locator('body')).toContainText(/Complete Ritual/i);
  });

  test('the Final Purchase Moment CTA actually adds the bundle to the cart', async ({ page }) => {
    await gotoAndCheck(page, BUNDLE_URL);
    const addToCartResponse = page.waitForResponse(resp => resp.url().includes('wc-ajax=add_to_cart') && resp.status() === 200);
    await page.locator('.cos-pdp-final-cta').click();
    await addToCartResponse;
    await expect(page.locator('.cos-pdp-final-cta')).toHaveClass(/added/);
  });

  test('the FAQ accordions work', async ({ page }) => {
    await gotoAndCheck(page, BUNDLE_URL);
    const firstFaq = page.locator('#pdp-faq .cos-pdp-faq-item').first();
    await firstFaq.locator('summary').click();
    await expect(firstFaq).toHaveAttribute('open', '');
  });

  test('mobile sticky Add to Bag bar includes the price, stays hidden until scrolled past the CTA, and never covers content underneath', async ({ page }) => {
    await page.setViewportSize({ width: 390, height: 844 });
    await gotoAndCheck(page, BUNDLE_URL);
    const sticky = page.locator('#cos-pdp-sticky-atc');
    await expect(sticky).not.toHaveClass(/is-visible/);
    await expect(sticky.locator('.cos-pdp-sticky-price')).toContainText('€450');

    const atcBtn = page.locator('#cos-pdp-atc-btn');
    await atcBtn.scrollIntoViewIfNeeded();
    const atcBox = await atcBtn.boundingBox();
    const navH = await page.evaluate(() =>
      parseInt(getComputedStyle(document.documentElement).getPropertyValue('--cos-nav-h')) || 80
    );
    const scrollAmount = Math.max(atcBox!.y - navH + 60, 200);
    await page.mouse.wheel(0, scrollAmount);
    await page.waitForTimeout(300);
    await expect(sticky).toHaveClass(/is-visible/);

    const stickyBox = await sticky.boundingBox();
    const actsHeading = page.locator('#pdp-acts .cos-pdp-section-title');
    const headingBox = await actsHeading.boundingBox();
    if (stickyBox && headingBox) {
      expect(headingBox.y + headingBox.height <= stickyBox.y || headingBox.y >= stickyBox.y + stickyBox.height).toBe(true);
    }
  });

  test('the footer never sits underneath the sticky Add to Bag bar', async ({ page }) => {
    await page.setViewportSize({ width: 390, height: 844 });
    await gotoAndCheck(page, BUNDLE_URL);
    await page.evaluate(() => window.scrollTo(0, document.documentElement.scrollHeight));
    await page.waitForTimeout(300);
    const sticky = page.locator('#cos-pdp-sticky-atc');
    const footer = page.locator('#cos-footer');
    const stickyBox = await sticky.boundingBox();
    const footerBox = await footer.boundingBox();
    if (stickyBox && footerBox) {
      // The sticky bar is fixed to the viewport bottom; the footer must not
      // render underneath it once scrolled fully to the end of the page.
      expect(footerBox.y).toBeLessThan(stickyBox.y + stickyBox.height);
    }
  });

  test('every interactive control on the bundle page meets the 44x44px minimum', async ({ page }) => {
    await page.setViewportSize({ width: 390, height: 844 });
    await gotoAndCheck(page, BUNDLE_URL);
    await expectMinimumTapTargets(page, '.cos-pdp-qty-btn, .cos-pdp-acts-link, .cos-pdp-value-cta, .cos-pdp-final-cta');
    await expectPreferredTapTargets(page, '#cos-pdp-atc-btn');
  });

  for (const width of [390, 768, 1024, 1440]) {
    test(`no horizontal overflow at ${width}px`, async ({ page }) => {
      await page.setViewportSize({ width, height: 900 });
      await gotoAndCheck(page, BUNDLE_URL);
      await expectNoHorizontalOverflow(page);
    });
  }
});

test.describe('Bundle redesign: non-bundle pages are unaffected', () => {
  const individualProducts = [
    '/products/micellar-water-cleanser/',
    '/products/advanced-repair-serum/',
    '/products/revitalizing-eye-cream/',
    '/products/24h-regeneration-cream/',
  ];

  for (const path of individualProducts) {
    test(`${path} keeps the original pill-style jump nav and has none of the bundle's classes`, async ({ page }) => {
      await gotoAndCheck(page, path);
      await expect(page.locator('#cos-pdp.cos-pdp-bundle')).toHaveCount(0);
      await expect(page.locator('.cos-pdp-acts')).toHaveCount(0);
      await expect(page.locator('.cos-pdp-value')).toHaveCount(0);
      await expect(page.locator('.cos-pdp-final')).toHaveCount(0);
      await expect(page.locator('.cos-pdp-jumpnav')).toHaveCount(1);
    });
  }

  test('the global header and footer are unchanged on the bundle page', async ({ page }) => {
    await gotoAndCheck(page, BUNDLE_URL);
    await expect(page.locator('#cos-nav-wrap')).toBeVisible();
    await expect(page.locator('#cos-footer')).toBeVisible();
    await expect(page.locator('#cos-footer .cos-nl-headline')).toHaveText(/Enter the world of Caviar of Switzerland/);
  });
});
