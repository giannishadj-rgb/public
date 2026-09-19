import { expect, test } from '@playwright/test';
import { existsSync, readFileSync } from 'node:fs';
import { join } from 'node:path';
import { isProductionLikeBaseUrl } from './helpers/cos-helpers';

/**
 * Phase 1 hardening: development-only diagnostic files must never be
 * publicly reachable outside local development.
 *
 * Two different protection mechanisms are in play here, and this suite
 * verifies each the way it can actually be verified:
 *
 * - local-xdebuginfo.php was deleted outright 2026-08-17 (a security review
 *   flagged it as unguarded; deleting it — rather than adding a
 *   wp_get_environment_type() guard — was the option chosen, since it was
 *   Local-app-specific debug helper with no legitimate reason to exist in
 *   the deployable webroot at all). We assert it stays gone: absent from
 *   the filesystem, and 404 over HTTP.
 * - readme.html / license.txt are static files served directly by the web
 *   server, never reaching PHP, so they're blocked via .htaccess
 *   (FilesMatch + Require all denied). This site's local dev environment
 *   runs on nginx (Local by Flywheel), which does not read .htaccess at
 *   all — so a request made against the local baseURL cannot exercise this
 *   rule the way a real Apache/LiteSpeed host would. Rather than assert a
 *   passing check that doesn't actually test anything, this suite asserts
 *   the .htaccess rule itself is present and correctly scoped (a
 *   regression guard against the rule being accidentally removed), and
 *   additionally enforces the real HTTP-level check whenever tests are
 *   pointed at a non-local environment (TEST_BASE_URL not ending in
 *   `.local`/localhost) — i.e. exactly the "production-like environment"
 *   the acceptance criteria describe. See OWNER-ACTIONS.md for the nginx
 *   equivalent, needed if the real host doesn't run Apache/LiteSpeed.
 */

const root = join(__dirname, '..');

test.describe('Diagnostic and dev-only file protection', () => {
  test('local-xdebuginfo.php is absent from the webroot', () => {
    expect(existsSync(join(root, 'local-xdebuginfo.php'))).toBe(false);
  });

  test('local-xdebuginfo.php returns 404 over HTTP', async ({ page }) => {
    const response = await page.goto('/local-xdebuginfo.php');
    expect(response?.status()).toBe(404);
  });

  test('.htaccess denies direct access to readme.html, license.txt, and other diagnostic artifacts', () => {
    const htaccess = readFileSync(join(root, '.htaccess'), 'utf8');
    expect(htaccess).toMatch(/readme\\\.html/);
    expect(htaccess).toMatch(/license\\\.txt/);
    expect(htaccess).toMatch(/Require all denied/);
  });

  for (const path of ['/readme.html', '/license.txt']) {
    test(`${path} is not publicly served in a production-like environment`, async ({ page, baseURL }) => {
      test.skip(!isProductionLikeBaseUrl(baseURL), 'Local dev runs on nginx, which does not honour .htaccess — see file header comment.');

      const response = await page.goto(path);
      expect(response?.status()).toBeGreaterThanOrEqual(400);
    });
  }
});
