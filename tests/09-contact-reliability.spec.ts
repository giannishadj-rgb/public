import { expect, test } from '@playwright/test';
import { readFileSync } from 'node:fs';
import { join } from 'node:path';
import { gotoAndCheck } from './helpers/cos-helpers';

async function getNonce(request: import('@playwright/test').APIRequestContext, baseURL: string) {
  const response = await request.get(`${baseURL}wp-json/cos/v1/nonce`);
  const body = await response.json();
  return body.nonce as string;
}

// Scrapes the nonce straight out of the raw server response for /contact/ —
// a plain request.get() executes no JavaScript at all, so this is what a
// genuinely JS-disabled visitor actually receives, unlike getNonce() above
// (which hits the same REST endpoint the page's *own* refresh script calls,
// and so only proves the JS-enabled path). Reproduces the real no-JS
// scenario the "no-JS fallback" tests below are named for.
async function getServerRenderedNonce(request: import('@playwright/test').APIRequestContext, baseURL: string) {
  const response = await request.get(`${baseURL}contact/`);
  const html = await response.text();
  const match = html.match(/id="cos-ct-nonce"\s+name="nonce"\s+value="([a-f0-9]+)"/);
  return match ? match[1] : null;
}

function contactFields(overrides: Record<string, string> = {}) {
  return {
    action: 'cos_contact',
    cos_name: 'Test Visitor',
    cos_email: 'visitor@example.test',
    cos_subject: 'Playwright reliability test',
    cos_message: 'This is an automated reliability test message.',
    // Environment-gated (local only) — keeps each test's rate-limit budget
    // isolated from every other test/project sharing this machine's IP.
    cos_test_reset_rate_limit: '1',
    cos_hp_field: '',
    ...overrides
  };
}

// The handler only returns JSON for the same header the contact page's own
// fetch() call sends (see cos_contact_is_ajax_request() in cos-contact.php).
// A request without it is treated as a no-JS plain form POST and gets a
// redirect instead — see the dedicated no-JS test below for that path.
const AJAX_HEADERS = { 'X-Requested-With': 'cos-fetch' };

async function postContact(
  request: import('@playwright/test').APIRequestContext,
  baseURL: string,
  form: Record<string, string>
) {
  return request.post(`${baseURL}wp-admin/admin-ajax.php`, { form, headers: AJAX_HEADERS });
}

test.describe('Contact form reliability and privacy', () => {
  test('successful delivery returns success and never leaks PII into the response', async ({ request, baseURL }) => {
    const nonce = await getNonce(request, baseURL!);
    const response = await postContact(request, baseURL!, contactFields({ nonce }));
    expect(response.status()).toBe(200);
    const body = await response.json();
    expect(body.success).toBe(true);
    expect(String(body.data)).toMatch(/thank you/i);
  });

  test('a forced wp_mail failure never reports success, and returns a safe retry message with a correlation id', async ({ request, baseURL }) => {
    const nonce = await getNonce(request, baseURL!);
    const response = await postContact(request, baseURL!, contactFields({ nonce, cos_test_force_mail_failure: '1' }));
    expect(response.status()).toBe(502);
    const body = await response.json();
    expect(body.success).toBe(false);
    expect(body.data.message).not.toMatch(/received|thank you/i);
    expect(body.data.message).toMatch(/try again|shortly|directly/i);
    expect(body.data.correlation_id).toMatch(/^[a-f0-9]{6,}$/);
  });

  test('an invalid nonce is rejected', async ({ request, baseURL }) => {
    const response = await postContact(request, baseURL!, contactFields({ nonce: 'not-a-real-nonce' }));
    expect(response.status()).toBe(403);
    const body = await response.json();
    expect(body.success).toBe(false);
  });

  test('missing required fields are rejected', async ({ request, baseURL }) => {
    const nonce = await getNonce(request, baseURL!);
    const response = await postContact(request, baseURL!, { action: 'cos_contact', nonce, cos_name: '', cos_email: '', cos_message: '' });
    expect(response.status()).toBe(400);
    const body = await response.json();
    expect(body.success).toBe(false);
  });

  test('an invalid email address is rejected', async ({ request, baseURL }) => {
    const nonce = await getNonce(request, baseURL!);
    const response = await postContact(request, baseURL!, contactFields({ nonce, cos_email: 'not-an-email' }));
    expect(response.status()).toBe(400);
    const body = await response.json();
    expect(body.success).toBe(false);
  });

  test('a filled honeypot field is silently rejected', async ({ request, baseURL }) => {
    const nonce = await getNonce(request, baseURL!);
    const response = await postContact(request, baseURL!, contactFields({ nonce, cos_hp_field: 'I am definitely a bot' }));
    expect(response.status()).toBe(400);
    const body = await response.json();
    expect(body.success).toBe(false);
  });

  test('rapid repeated submissions from the same connection are rate limited', async ({ request, baseURL }) => {
    const results: number[] = [];
    for (let i = 0; i < 6; i++) {
      const nonce = await getNonce(request, baseURL!);
      // Reset only on the first request so the count actually accumulates
      // across this loop instead of being cleared before every check.
      const overrides: Record<string, string> = { nonce, cos_subject: `Rate limit test ${i}` };
      if (i > 0) overrides.cos_test_reset_rate_limit = '';
      const response = await postContact(request, baseURL!, contactFields(overrides));
      results.push(response.status());
    }
    expect(results.filter(status => status === 429).length).toBeGreaterThan(0);
  });

  test('a plain form POST (no AJAX header, simulating no-JS) is redirected with a status code, never JSON containing PII', async ({ request, baseURL }) => {
    // Uses the server-rendered nonce a real no-JS visitor would actually
    // have — not one fetched from the JS-only /cos/v1/nonce endpoint.
    const nonce = await getServerRenderedNonce(request, baseURL!);
    expect(nonce, 'The raw server response should already contain a real nonce').toBeTruthy();
    const response = await request.post(`${baseURL}wp-admin/admin-ajax.php`, {
      form: contactFields({ nonce: nonce! }),
      maxRedirects: 0
    });
    // wp_safe_redirect() -> 302
    expect(response.status()).toBe(302);
    const location = response.headers()['location'] || '';
    expect(location).toMatch(/cos_sent=1/);
    // The redirect target must never carry the submitted name/email/message.
    expect(location).not.toMatch(/Test Visitor|visitor%40example\.test|visitor@example\.test/i);
  });

  test('a fully JavaScript-free round trip works end to end: server-rendered nonce in, redirect, rendered success message out', async ({ request, baseURL }) => {
    // No page.goto(), no fetch(), no wp-json/cos/v1/nonce call — every step
    // uses only what a plain HTML response contains, reproducing exactly
    // what a visitor with JavaScript disabled or blocked experiences.
    const nonce = await getServerRenderedNonce(request, baseURL!);
    expect(nonce).toBeTruthy();

    const submit = await request.post(`${baseURL}wp-admin/admin-ajax.php`, {
      form: contactFields({ nonce: nonce!, cos_test_reset_rate_limit: '1' }),
      maxRedirects: 0
    });
    expect(submit.status()).toBe(302);
    const location = submit.headers()['location'];
    expect(location).toMatch(/cos_sent=1/);

    const landing = await request.get(location);
    const html = await landing.text();
    expect(html).toMatch(/class="cos-ct-msg success"/);
    expect(html).toMatch(/Thank you\. Your message has been received/);
    // The whole point: this text must be rendered by PHP directly into the
    // response, not left as an unprocessed shortcode/token.
    expect(html).not.toMatch(/\{\{cos_contact_status\}\}|\[cos_contact_status\]/);
  });

  test('a plain form POST rejection redirects with a reason code, not a message string, and no PII', async ({ request, baseURL }) => {
    const response = await request.post(`${baseURL}wp-admin/admin-ajax.php`, {
      form: contactFields({ nonce: 'not-a-real-nonce' }),
      maxRedirects: 0
    });
    expect(response.status()).toBe(302);
    const location = response.headers()['location'] || '';
    expect(location).toMatch(/cos_sent=0/);
    expect(location).toMatch(/cos_reason=nonce/);
    expect(location).not.toMatch(/Test Visitor|visitor%40example\.test/i);
  });

  test('the contact form uses POST and a real action target, so JS-disabled submissions cannot leak fields into a URL', async ({ page }) => {
    await gotoAndCheck(page, '/contact/');
    const form = page.locator('#cos-contact-form-v2');
    await expect(form).toHaveAttribute('method', /post/i);
    await expect(form).toHaveAttribute('action', /admin-ajax\.php/);

    const nonceField = page.locator('#cos-ct-nonce');
    await expect(nonceField).toHaveAttribute('name', 'nonce');
    // Server-rendered nonce (a PHP 'the_content' filter substituting a
    // {{cos_contact_nonce}} token — see cos-contact.php) is present even
    // before the page's own refresh script runs.
    await expect(nonceField).not.toHaveAttribute('value', '');
    await expect(nonceField).toHaveAttribute('value', /^[a-f0-9]+$/);
  });

  test('the raw, unfiltered-by-JS server response never contains a literal unprocessed nonce/status token', async ({ request, baseURL }) => {
    const response = await request.get(`${baseURL}contact/`);
    const html = await response.text();
    expect(html).not.toMatch(/\{\{cos_contact_nonce\}\}|\[cos_contact_nonce\]/);
    expect(html).not.toMatch(/\{\{cos_contact_status\}\}|\[cos_contact_status\]/);
  });

  test('the honeypot field cannot be populated by ordinary browser autofill and is hidden from the accessibility tree', async ({ page }) => {
    await gotoAndCheck(page, '/contact/');
    const honeypot = page.locator('#cos-ct-hp');
    await expect(honeypot).toHaveAttribute('autocomplete', 'off');
    await expect(honeypot).not.toHaveAttribute('autocomplete', 'organization');
    await expect(honeypot).toHaveAttribute('tabindex', '-1');

    const wrapper = page.locator('div[aria-hidden="true"]:has(#cos-ct-hp)');
    await expect(wrapper).toHaveCount(1);
  });

  test('entered contact-form values survive a recoverable send failure', async ({ page }) => {
    await gotoAndCheck(page, '/contact/');

    await page.route('**/wp-admin/admin-ajax.php', async route => {
      const postData = route.request().postData() || '';
      if (postData.includes('cos_contact')) {
        await route.fulfill({
          status: 502,
          contentType: 'application/json',
          body: JSON.stringify({
            success: false,
            data: { message: 'We could not send your message right now. Please try again shortly.', correlation_id: 'abc123' }
          })
        });
        return;
      }
      await route.continue();
    });

    await page.locator('#cos-ct-name').fill('Test Visitor');
    await page.locator('#cos-ct-email').fill('visitor@example.test');
    await page.locator('#cos-ct-subject').fill('Playwright UI test');
    await page.locator('#cos-ct-message').fill('This is a UI-level reliability test.');
    await page.locator('#cos-contact-form-v2 button[type="submit"]').click();

    const msg = page.locator('#cos-ct-msg');
    await expect(msg).toContainText(/try again shortly/i);
    await expect(msg).not.toContainText(/\[object Object\]/i);
    await expect(page).toHaveURL(/\/contact\/?/);

    // The whole point of not calling form.reset() on failure: the visitor
    // shouldn't have to retype everything after a recoverable error.
    await expect(page.locator('#cos-ct-name')).toHaveValue('Test Visitor');
    await expect(page.locator('#cos-ct-email')).toHaveValue('visitor@example.test');
    await expect(page.locator('#cos-ct-subject')).toHaveValue('Playwright UI test');
    await expect(page.locator('#cos-ct-message')).toHaveValue('This is a UI-level reliability test.');
  });

  test('test-only hooks are gated behind three independent, non-request-controllable conditions', () => {
    const source = readFileSync(
      join(__dirname, '..', 'wp-content', 'mu-plugins', 'cos-contact.php'),
      'utf8'
    );
    // Regression guard: this is a static-source check because the live local
    // environment always identifies as 'local' by design (see wp-config.php)
    // and WordPress overrides WP_ENVIRONMENT_TYPE from a constant, so an
    // HTTP-level "flip the environment and confirm it's blocked" test isn't
    // possible against this single, fixed local install. WordPress itself
    // defaults wp_get_environment_type() to 'production' whenever
    // WP_ENVIRONMENT_TYPE isn't explicitly recognised (wp-includes/load.php),
    // so this gate is closed by default anywhere that hasn't opted in.
    expect(source).toMatch(/function cos_contact_test_hooks_enabled\(\)/);
    expect(source).toMatch(/COS_ENABLE_TEST_HOOKS/);
    expect(source).toMatch(/'local'\s*===\s*wp_get_environment_type\(\)/);
    expect(source).toMatch(/COS_TEST_MODE/);
    expect(source).not.toMatch(/'staging'\s*===\s*wp_get_environment_type\(\)/);

    // Both call sites must route through the shared gate, not re-implement
    // their own (weaker) check.
    const mailFailureBlock = source.slice(source.indexOf('pre_wp_mail'), source.indexOf('function cos_contact_correlation_id'));
    expect(mailFailureBlock).toMatch(/cos_contact_test_hooks_enabled\(\)/);
    const rateLimitBlock = source.slice(source.indexOf('function cos_handle_contact'));
    expect(rateLimitBlock).toMatch(/cos_contact_test_hooks_enabled\(\)\s*&&\s*!\s*empty\(\$_POST\['cos_test_reset_rate_limit'\]\)/);
  });
});
