import { expect, test } from '@playwright/test';

async function resetCspRateLimit(request: import('@playwright/test').APIRequestContext, baseURL: string) {
  await request.post(`${baseURL}wp-json/cos/v1/csp-report?cos_test_reset_rate_limit=1`, {
    data: { 'csp-report': { 'violated-directive': 'script-src', 'blocked-uri': 'inline' } }
  });
}

async function inspectStoredReports(request: import('@playwright/test').APIRequestContext, baseURL: string) {
  const response = await request.get(`${baseURL}wp-json/cos/v1/csp-report-test-inspect`);
  expect(response.status(), 'Test-inspection endpoint should be reachable in this local environment').toBe(200);
  return response.json() as Promise<Array<Record<string, unknown>>>;
}

test.describe('Content-Security-Policy (report-only observation phase)', () => {
  test('CSP is present as report-only (never enforced) and does not break the page', async ({ page }) => {
    const response = await page.goto('/');
    const headers = response?.headers() ?? {};
    expect(headers['content-security-policy-report-only'], 'Report-only CSP header should be present').toBeTruthy();
    // Must never be the enforcing header — that would risk breaking
    // WooCommerce/Complianz/Klaviyo/payment scripts without a nonce migration first.
    expect(headers['content-security-policy']).toBeUndefined();

    const errors: string[] = [];
    page.on('console', m => { if (m.type() === 'error') errors.push(m.text()); });
    await page.waitForLoadState('networkidle');
    expect(errors.filter(e => /content security policy/i.test(e))).toEqual([]);
  });

  test('the CSP violation-report endpoint accepts a well-formed report and never exposes stored reports through the real (non-test) route', async ({ request, baseURL }) => {
    // Reset first: this endpoint is rate-limited to 30 requests/5 minutes per
    // IP (cos-security.php), and this test's own assertion of a real 204 is
    // only meaningful starting from a clean budget -- without this, repeated
    // local test runs against the same machine can exhaust the limit and
    // turn a passing test into a false-positive 429 unrelated to any bug.
    await resetCspRateLimit(request, baseURL!);
    const response = await request.post(`${baseURL}wp-json/cos/v1/csp-report`, {
      data: {
        'csp-report': {
          'blocked-uri': 'https://example.test/evil.js',
          'violated-directive': 'script-src',
          'document-uri': `${baseURL}`
        }
      }
    });
    expect(response.status()).toBe(204);

    // No public GET on the real route should expose the accumulated log.
    const getAttempt = await request.get(`${baseURL}wp-json/cos/v1/csp-report`);
    expect(getAttempt.status()).toBe(404);
  });

  test('a malformed report (missing a violated/effective directive) is rejected, not silently stored', async ({ request, baseURL }) => {
    await resetCspRateLimit(request, baseURL!);
    const before = await inspectStoredReports(request, baseURL!);

    const response = await request.post(`${baseURL}wp-json/cos/v1/csp-report`, {
      data: { 'csp-report': { 'blocked-uri': 'https://example.test/evil.js' } }
    });
    expect(response.status()).toBe(400);

    const after = await inspectStoredReports(request, baseURL!);
    expect(after.length, 'A rejected malformed report must not be stored').toBe(before.length);
  });

  test('a non-JSON / garbage body is rejected rather than crashing the endpoint', async ({ request, baseURL }) => {
    await resetCspRateLimit(request, baseURL!);
    const response = await request.post(`${baseURL}wp-json/cos/v1/csp-report`, {
      headers: { 'Content-Type': 'application/csp-report' },
      data: 'this is not json{{{'
    });
    expect(response.status()).toBe(400);
  });

  test('an oversized body is rejected with 413 before being parsed', async ({ request, baseURL }) => {
    await resetCspRateLimit(request, baseURL!);
    const oversized = {
      'csp-report': {
        'violated-directive': 'script-src',
        'blocked-uri': 'https://example.test/' + 'a'.repeat(20_000)
      }
    };
    const response = await request.post(`${baseURL}wp-json/cos/v1/csp-report`, { data: oversized });
    expect(response.status()).toBe(413);
  });

  test('query strings and fragments are stripped from stored report URLs, and fields are length-capped', async ({ request, baseURL }) => {
    await resetCspRateLimit(request, baseURL!);
    const longPath = 'a'.repeat(1000);
    await request.post(`${baseURL}wp-json/cos/v1/csp-report`, {
      data: {
        'csp-report': {
          'violated-directive': 'script-src',
          'blocked-uri': `https://tracker.example.test/pixel?order_key=wc_order_secret123&email=visitor%40example.com`,
          'document-uri': `${baseURL}checkout/order-received/123/?key=wc_order_verysecretkey#section`,
          'source-file': `https://caviarofswitzerland.local/${longPath}?token=abc`
        }
      }
    });

    const reports = await inspectStoredReports(request, baseURL!);
    const last = reports[reports.length - 1] as any;

    expect(last.blocked_uri).not.toMatch(/order_key|wc_order_secret123|email=/);
    expect(last.blocked_uri).toMatch(/^https:\/\/tracker\.example\.test\/pixel$/);

    expect(last.document_uri).not.toMatch(/key=wc_order_verysecretkey|section/);

    expect(String(last.source_file).length).toBeLessThanOrEqual(500);
  });

  test('requests are rate limited by IP after repeated submissions', async ({ request, baseURL }) => {
    await resetCspRateLimit(request, baseURL!);
    const statuses: number[] = [];
    for (let i = 0; i < 32; i++) {
      const response = await request.post(`${baseURL}wp-json/cos/v1/csp-report`, {
        data: { 'csp-report': { 'violated-directive': 'script-src', 'blocked-uri': `inline-${i}` } }
      });
      statuses.push(response.status());
    }
    expect(statuses.filter(s => s === 429).length, 'Some requests in a burst of 32 should be rate limited').toBeGreaterThan(0);
    // Reset immediately so this test doesn't consume budget from any other
    // test/project sharing this machine's IP.
    await resetCspRateLimit(request, baseURL!);
  });

  test('the test-inspection endpoint itself is gated behind the local-only test-hook guard, same as every other test hook', async () => {
    const { readFileSync } = await import('node:fs');
    const { join } = await import('node:path');
    const source = readFileSync(join(__dirname, '..', 'wp-content', 'mu-plugins', 'cos-security.php'), 'utf8');
    const inspectBlock = source.slice(source.indexOf('csp-report-test-inspect'));
    expect(inspectBlock).toMatch(/cos_contact_test_hooks_enabled\(\)/);
  });
});
