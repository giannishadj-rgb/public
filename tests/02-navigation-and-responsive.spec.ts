import { expect, test } from '@playwright/test';
import { expectNoHorizontalOverflow, gotoAndCheck } from './helpers/cos-helpers';

test.describe('Navigation, accessibility, and responsive scenarios', () => {
  test('desktop primary navigation exposes all expected destinations', async ({ page }) => {
    await page.setViewportSize({ width: 1440, height: 950 });
    await gotoAndCheck(page, '/');

    const nav = page.getByRole('navigation', { name: /primary navigation/i });
    await expect(nav).toBeVisible();

    for (const name of ['Home', 'Shop', 'The Ritual', 'Swiss Science', 'Swiss Heritage']) {
      await expect(nav.getByRole('link', { name: new RegExp(`^${name}$`, 'i') })).toBeVisible();
    }

    // Help intentionally lives in the mobile menu, not the compact desktop bar.
    await expect(nav.getByRole('link', { name: /^Help$/i })).toHaveCount(0);
    await expect(nav.getByRole('link', { name: /^Account$/i })).toBeVisible();
    await expect(nav.getByRole('link', { name: /^Bag$/i })).toBeVisible();
    await expectNoHorizontalOverflow(page);
  });

  test('mobile menu opens, traps focus, closes with Escape, and leaves no overflow', async ({ page }) => {
    await page.setViewportSize({ width: 390, height: 844 });
    await gotoAndCheck(page, '/');

    // 8 Nov 2026: the homepage uses the sitewide header, whose toggle is renamed "Close navigation
    // menu" while open, so it is located by id rather than by its (changing) accessible name.
    const button = page.locator('#cos-mob-btn');
    const menu = page.locator('#cos-mob-menu');
    await expect(button).toBeVisible();
    await expect(button).toHaveAccessibleName(/open navigation menu/i);
    await expect(button).toHaveAttribute('aria-expanded', 'false');

    await button.click();
    await expect(button).toHaveAttribute('aria-expanded', 'true');
    await expect(button).toHaveAccessibleName(/close navigation menu/i);
    await expect(menu).toHaveClass(/open/);
    await expect(menu).toHaveAttribute('aria-hidden', 'false');
    await expect(menu.getByRole('link', { name: /^Home$/i })).toBeFocused();
    await expect(menu.getByRole('link', { name: /^Help$/i })).toBeVisible();

    // Disclosure, not a modal (17 Oct 2026 audit P1-2): the × toggle stays in the focus loop, so
    // Shift+Tab from the first link reaches it, and once more wraps to the last menu link.
    await page.keyboard.press('Shift+Tab');
    await expect(button).toBeFocused();
    await page.keyboard.press('Shift+Tab');
    await expect(menu.getByRole('link', { name: /^Contact$/i })).toBeFocused();

    await page.keyboard.press('Escape');
    await expect(button).toHaveAttribute('aria-expanded', 'false');
    await expect(menu).not.toHaveClass(/open/);
    await expect(button).toBeFocused();
    await expectNoHorizontalOverflow(page);
  });

  test('skip link can move keyboard users directly toward main content', async ({ page }) => {
    await gotoAndCheck(page, '/shop/');

    await page.keyboard.press('Tab');
    const skipLink = page.getByRole('link', { name: /skip to main content/i });
    await expect(skipLink).toBeFocused();
    const skipBounds = await skipLink.boundingBox();
    expect(skipBounds).not.toBeNull();
    expect(skipBounds!.x, 'Focused skip link must be onscreen').toBeGreaterThanOrEqual(0);
    expect(skipBounds!.height, 'Focused skip link must be a usable target').toBeGreaterThanOrEqual(44);

    await page.keyboard.press('Enter');
    await expect(page.locator('#cos-main-content, main').first()).toBeVisible();
  });

  test('newsletter success and failure scenarios are handled without leaving the page', async ({ page }) => {
    await page.route('https://a.klaviyo.com/client/subscriptions/**', route => {
      const requestBody = route.request().postData() || '';
      const status = requestBody.includes('fail@example.test') ? 500 : 202;
      route.fulfill({ status, body: '{}' });
    });

    await gotoAndCheck(page, '/');

    const successForm = page.locator('form.cos-nl-form').last();
    await successForm.locator('input[type="email"]').fill('success@example.test');
    await successForm.locator('input[type="checkbox"]').check();
    await successForm.getByRole('button', { name: /join/i }).click();
    await expect(successForm).toContainText(/thank you|welcome/i);

    await gotoAndCheck(page, '/');
    const failureForm = page.locator('form.cos-nl-form').last();
    await failureForm.locator('input[type="email"]').fill('fail@example.test');
    await failureForm.locator('input[type="checkbox"]').check();
    await failureForm.getByRole('button', { name: /join/i }).click();
    await expect(failureForm).toContainText(/something went wrong|try again/i);
  });
});
