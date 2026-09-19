import { expect, test } from '@playwright/test';
import {
  expectMinimumTapTargets,
  expectNoHorizontalOverflow,
  expectPreferredTapTargets,
  gotoAndCheck
} from './helpers/cos-helpers';

test.describe('UI, UX, and responsive behavior scenarios', () => {
  test('mobile menu locks background scroll and restores the page after closing', async ({ page }) => {
    await page.setViewportSize({ width: 390, height: 844 });
    await gotoAndCheck(page, '/');

    await page.evaluate(() => window.scrollTo(0, 420));
    await page.waitForTimeout(100);

    const button = page.getByRole('button', { name: /open navigation menu/i });
    await button.click();
    await expect(page.locator('#cos-mob-menu')).toHaveClass(/open/);
    await expect(page.locator('body')).toHaveCSS('overflow', 'hidden');
    await expect(page.locator('body')).toHaveCSS('position', 'fixed');

    const lockedTop = await page.locator('body').evaluate(body => window.getComputedStyle(body).top);

    await page.mouse.wheel(0, 600);
    await expect.poll(() => page.locator('body').evaluate(body => window.getComputedStyle(body).top)).toBe(lockedTop);

    await page.keyboard.press('Escape');
    await expect(page.locator('#cos-mob-menu')).not.toHaveClass(/open/);
    await expect(page.locator('body')).not.toHaveCSS('overflow', 'hidden');
    await expect(page.locator('body')).not.toHaveCSS('position', 'fixed');
    await expect.poll(() => page.evaluate(() => window.scrollY)).toBeGreaterThanOrEqual(0);
    await expectNoHorizontalOverflow(page);
  });

  test('header and mobile menu controls meet the WCAG minimum tap-target size', async ({ page }) => {
    await gotoAndCheck(page, '/');
    await expectMinimumTapTargets(page, '.cos-nav-utils a, .cos-mob-btn, .cos-footer-cookie-link button');
    // Header icons and the mobile menu toggle are frequently-used
    // navigation, not incidental links — hold them to the higher bar.
    await expectPreferredTapTargets(page, '.cos-nav-utils a, .cos-mob-btn');
  });

  test('shop grid and bundle "Add to Bag" controls meet the preferred commerce tap-target size', async ({ page }) => {
    await gotoAndCheck(page, '/shop/');
    await expectMinimumTapTargets(page, '.cos-card-btn, .cos-bundle-btn-primary');
    await expectPreferredTapTargets(page, '.cos-card-btn, .cos-bundle-btn-primary');
  });

  test('PDP gallery dots, quantity stepper, and secondary controls meet tap-target minimums', async ({ page }) => {
    await gotoAndCheck(page, '/products/advanced-repair-serum/');
    // Gallery dots are the primary way to browse product photos on the page
    // most responsible for building purchase confidence — held to the
    // preferred bar, not just the WCAG floor. The visual dot itself stays a
    // small 6px mark; only the tap target grew.
    await expectPreferredTapTargets(page, '.cos-pdp-dot');
    // Quantity stepper and Add to Bag are primary, frequently-used commerce
    // controls.
    await expectPreferredTapTargets(page, '.cos-pdp-qty-btn, .cos-pdp-atc-btn');
  });

  test('footer newsletter button/checkbox meet tap-target minimums', async ({ page }) => {
    // The newsletter button bug specifically only showed up in the
    // mobile (flex-direction: column) layout — align-items: stretch on
    // .cos-nl-row only rescues cross-axis (height) sizing in the row
    // layout used at >=768px, not the column layout used below it. Force
    // the narrow viewport so this test reliably reproduces/guards that,
    // regardless of which Playwright project (desktop/mobile) runs it.
    //
    // The homepage step-spotlight carousel (.cos-spcar-dot) this test used
    // to also cover was removed in the 2026-09-19 UI/UX pass (#cos-ritual-rail
    // already communicates the four-step system) -- nothing replaces that
    // assertion, there is no longer a homepage carousel to check.
    await page.setViewportSize({ width: 390, height: 844 });
    await gotoAndCheck(page, '/');

    // Shared footer newsletter signup: primary conversion control + its
    // required consent checkbox.
    await expectPreferredTapTargets(page, '.cos-nl-btn');
    await expectMinimumTapTargets(page, '.cos-nl-consent input[type="checkbox"]');
  });

  test('cart quantity, removal, checkout, and account controls meet tap-target minimums', async ({ page }) => {
    await gotoAndCheck(page, '/cart/');
    await expectMinimumTapTargets(page, '.cos-cp-remove, .cos-cp-checkout-btn, .cos-cp-continue, .cos-cp-empty-btn');
    await expectPreferredTapTargets(page, '.cos-cp-qty-btn, .cos-cp-checkout-btn');

    await gotoAndCheck(page, '/account/');
    await expectMinimumTapTargets(page, '.cos-acc-tab, .cos-acc-forgot, .cos-acc-dc-link, .cos-acc-logout');
    await expectPreferredTapTargets(page, '.cos-acc-submit');
  });

  test('contact form exposes native validation without losing entered data', async ({ page }) => {
    await gotoAndCheck(page, '/contact/');

    const email = page.locator('main input[type="email"]').first();
    const form = email.locator('xpath=ancestor::form[1]');
    const submit = form.locator('button[type="submit"], input[type="submit"], button').last();

    await expect(email, 'Contact page should expose an email field').toBeVisible();
    await expect(submit, 'Contact page should expose a submit control').toBeVisible();

    await email.fill('not-an-email');
    await submit.click();

    await expect(email).toHaveValue('not-an-email');
    await expect
      .poll(async () => email.evaluate(input => (input as HTMLInputElement).validity.valid))
      .toBe(false);
    await expectNoHorizontalOverflow(page);
  });

  test('Science page section nav meets the preferred commerce tap-target size', async ({ page }) => {
    await gotoAndCheck(page, '/science/');
    await expectPreferredTapTargets(page, '.cos-sci-nav a');
    await expectNoHorizontalOverflow(page);
  });

  test('sticky header does not cover key content after keyboard skip navigation', async ({ page }) => {
    await gotoAndCheck(page, '/shop/');

    await page.keyboard.press('Tab');
    await page.keyboard.press('Enter');

    const layout = await page.evaluate(() => {
      const header = document.querySelector('#cos-nav-wrap')?.getBoundingClientRect();
      const content = document.querySelector('#cos-main-content h1, main h1, #cos-main-content [role="heading"], main [role="heading"]')?.getBoundingClientRect();
      return {
        headerBottom: Math.round(header?.bottom || 0),
        contentTop: Math.round(content?.top || 0)
      };
    });

    expect(layout.contentTop, 'Skipped-to content should not sit behind the sticky header').toBeGreaterThanOrEqual(
      layout.headerBottom - 2
    );
  });

  test('footer column groups collapse on mobile and stay open on desktop, natively keyboard-operable', async ({ page }) => {
    // Mobile: collapsed by default (progressive enhancement closes them once
    // JS has run; a no-JS visitor would instead see them all open, which is
    // the safe direction to fail in — never worse than always-visible).
    await page.setViewportSize({ width: 390, height: 844 });
    await gotoAndCheck(page, '/');
    const groups = page.locator('.cos-footer-details');
    await expect(groups).toHaveCount(3);
    for (const openAttr of await groups.evaluateAll(els => els.map(e => e.hasAttribute('open')))) {
      expect(openAttr, 'Footer groups should start collapsed on mobile').toBe(false);
    }
    await expectMinimumTapTargets(page, '.cos-footer-col-summary');

    // Keyboard: Tab to a summary, Enter opens it, Enter again closes it —
    // native <details>/<summary> behavior, no custom JS required for this part.
    const firstSummary = groups.first().locator('summary');
    await firstSummary.focus();
    await page.keyboard.press('Enter');
    await expect(groups.first()).toHaveAttribute('open', '');
    const socialLinks = groups.last().locator('a[aria-label="Instagram"]');
    // Social links live inside the Zug column body -- confirm they're
    // reachable once that specific group is open.
    await groups.last().locator('summary').focus();
    await page.keyboard.press('Enter');
    await expect(socialLinks).toBeVisible();
    await page.keyboard.press('Enter');
    await expect(groups.last()).not.toHaveAttribute('open', '');

    // Desktop: always open, and the trigger itself is inert (no visible/
    // focusable summary to accidentally close it with).
    await page.setViewportSize({ width: 1440, height: 900 });
    await gotoAndCheck(page, '/');
    const desktopGroups = page.locator('.cos-footer-details');
    for (const openAttr of await desktopGroups.evaluateAll(els => els.map(e => e.hasAttribute('open')))) {
      expect(openAttr, 'Footer groups should stay open on desktop').toBe(true);
    }
    await expect(desktopGroups.first().locator('summary')).toBeHidden();
    await expect(page.locator('.cos-footer-details').last().locator('a[aria-label="Instagram"]')).toBeVisible();
    await expectNoHorizontalOverflow(page);
  });
});
