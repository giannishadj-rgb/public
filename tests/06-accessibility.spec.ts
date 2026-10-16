import AxeBuilder from '@axe-core/playwright';
import { expect, test, type Page } from '@playwright/test';
import { gotoAndCheck, scrollWindowIncrementally } from './helpers/cos-helpers';

const accessibilityRoutes = [
  { name: 'home', path: '/' },
  { name: 'shop', path: '/shop/' },
  { name: 'ritual', path: '/the-ritual/' },
  { name: 'science', path: '/science/' },
  { name: 'caviar sourcing', path: '/science/why-caviar/' },
  { name: 'product studies', path: '/science/clinical-studies/' },
  { name: 'Swiss formulation', path: '/science/swiss-formulation/' },
  { name: 'heritage', path: '/heritage/' },
  { name: 'craftsmanship', path: '/heritage/craftsmanship/' },
  { name: 'sourcing', path: '/heritage/sustainability/' },
  { name: 'product', path: '/products/advanced-repair-serum/' },
  { name: 'cart', path: '/cart/' },
  { name: 'account', path: '/account/' },
  { name: 'contact', path: '/contact/' }
] as const;

async function expectNoSeriousAccessibilityViolations(page: Page) {
  const results = await new AxeBuilder({ page })
    .withTags(['wcag2a', 'wcag2aa', 'wcag21a', 'wcag21aa'])
    .analyze();

  const violations = results.violations
    .filter(violation => violation.impact === 'serious' || violation.impact === 'critical')
    .map(violation => ({
      id: violation.id,
      impact: violation.impact,
      help: violation.help,
      nodes: violation.nodes.slice(0, 4).map(node => ({
        target: node.target.join(', '),
        failureSummary: node.failureSummary
      }))
    }));

  expect(violations, 'No serious or critical WCAG violations should be present').toEqual([]);
}

test.describe('Accessibility scenarios', () => {
  for (const route of accessibilityRoutes) {
    test(`${route.name} has no serious automated accessibility violations`, async ({ page }) => {
      await gotoAndCheck(page, route.path);
      await expectNoSeriousAccessibilityViolations(page);
    });
  }

  test('mobile navigation remains accessible while open', async ({ page }) => {
    await page.setViewportSize({ width: 390, height: 844 });
    await gotoAndCheck(page, '/');

    await page.getByRole('button', { name: /open navigation menu/i }).click();
    await expect(page.locator('#cos-mob-menu')).toHaveAttribute('aria-hidden', 'false');
    await expectNoSeriousAccessibilityViolations(page);
  });

  test('product page remains accessible with the sticky mobile Add to Bag bar visible', async ({ page }) => {
    await page.setViewportSize({ width: 390, height: 844 });
    await gotoAndCheck(page, '/products/advanced-repair-serum/');
    // Bug fix (UI/UX pass 2026-09-19): the sticky bar now stays hidden until
    // the real CTA has actually scrolled above the sticky nav (it used to
    // show immediately on load, before the CTA was ever reached). Scroll
    // past it first — incrementally, since a single scrollTo() jump can
    // skip the exact frame the IntersectionObserver needs to see.
    await scrollWindowIncrementally(page, 1600);
    await expect(page.locator('#cos-pdp-sticky-atc')).toHaveClass(/is-visible/);
    await expectNoSeriousAccessibilityViolations(page);
  });

  test('checkout with a real cart (collapsed optional fields, collapsed coupon) has no serious violations', async ({ page }) => {
    await gotoAndCheck(page, '/products/advanced-repair-serum/');
    await page.locator('#cos-pdp-atc-btn').click();
    await page.waitForLoadState('networkidle');
    await gotoAndCheck(page, '/checkout/');
    await expectNoSeriousAccessibilityViolations(page);
  });
});
