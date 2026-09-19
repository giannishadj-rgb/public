import { expect, test } from '@playwright/test';
import { expectNoHorizontalOverflow, gotoAndCheck } from './helpers/cos-helpers';

test.describe('Account and form scenarios', () => {
  test('logged-out account page toggles between sign-in and registration panels', async ({ page }) => {
    await gotoAndCheck(page, '/account/');

    const signInTab = page.getByRole('tab', { name: /sign in/i });
    const registerTab = page.getByRole('tab', { name: /register/i });

    await expect(signInTab).toHaveAttribute('aria-selected', 'true');
    await expect(page.locator('#cos-p-login')).toHaveClass(/is-active/);
    await expect(page.getByLabel(/email address/i).first()).toBeVisible();
    await expect(page.getByLabel(/^password$/i).first()).toBeVisible();
    await expect(page.getByRole('link', { name: /forgot your password/i })).toBeVisible();

    await registerTab.click();
    await expect(registerTab).toHaveAttribute('aria-selected', 'true');
    await expect(signInTab).toHaveAttribute('aria-selected', 'false');
    await expect(page.locator('#cos-p-register')).toHaveClass(/is-active/);
    await expect(page.getByLabel(/first name/i)).toBeVisible();
    await expect(page.getByLabel(/last name/i)).toBeVisible();
    await expect(page.getByLabel(/terms/i)).toBeVisible();
    await expectNoHorizontalOverflow(page);
  });

  test('invalid sign-in scenario returns a visible WooCommerce notice', async ({ page }) => {
    await gotoAndCheck(page, '/account/');

    await page.locator('#cos-l-email, input[name="username"]').first().fill(`missing-${Date.now()}@example.test`);
    await page.locator('#cos-l-pass, input[name="password"]').first().fill('not-the-password');
    await page.getByRole('button', { name: /^Sign in$/i }).click();
    await page.waitForLoadState('domcontentloaded').catch(() => undefined);

    // The site deliberately returns a single generic message for any failed
    // login attempt (wp-content/mu-plugins/cos-security.php) so the response
    // can't be used to enumerate whether a username or email is registered.
    await expect(page.locator('.woocommerce-error, .cos-acc-notices').first()).toContainText(
      /Login unsuccessful/i
    );
  });

  test('registration scenario keeps consent, password, and identity fields discoverable', async ({ page }) => {
    await gotoAndCheck(page, '/account/?action=register');

    await expect(page.getByRole('tab', { name: /register/i })).toHaveAttribute('aria-selected', 'true');
    await expect(page.locator('#cos-r-fn')).toHaveAttribute('autocomplete', 'given-name');
    await expect(page.locator('#cos-r-ln')).toHaveAttribute('autocomplete', 'family-name');
    await expect(page.locator('#cos-r-email')).toHaveAttribute('autocomplete', 'email');
    await expect(page.locator('#cos-r-pass')).toHaveAttribute('autocomplete', 'new-password');
    await expect(page.locator('#cos-r-pass')).toHaveAttribute('minlength', '8');
    await expect(page.locator('input[name="gdpr_consent"]')).toBeVisible();
  });
});
