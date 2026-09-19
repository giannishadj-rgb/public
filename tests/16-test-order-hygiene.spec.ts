import { expect, test } from '@playwright/test';
import { readFileSync } from 'node:fs';
import { join } from 'node:path';

/**
 * Phase 8: the order-creating purchase test (tests/10) must never run
 * against a non-local environment, and any order it does create locally
 * must be tagged, silenced, and auto-cancelled rather than left sitting in
 * the real fulfilment queue. This suite verifies the server-side machinery
 * for that via static source checks — a live end-to-end proof already
 * exists in tests/10's own "is_test_order" assertion (right after it
 * places a real order), so this doesn't duplicate placing another one.
 */
test.describe('Test-order hygiene', () => {
  test('test orders are tagged, never emailed, and auto-cancelled — all local-only', () => {
    const source = readFileSync(
      join(__dirname, '..', 'wp-content', 'themes', 'cos-child', 'inc', 'commerce.php'),
      'utf8'
    );

    // Tagging is keyed off the reserved example.test domain, gated to local.
    expect(source).toMatch(/function cos_child_is_test_order/);
    expect(source).toMatch(/@example\.test/);
    expect(source).toMatch(/'local' === wp_get_environment_type\(\)/);
    expect(source).toMatch(/_cos_test_order/);

    // Every standard WooCommerce order email is suppressed for tagged orders.
    for (const emailId of [
      'new_order', 'customer_processing_order', 'customer_completed_order',
      'customer_invoice', 'customer_note', 'cancelled_order', 'failed_order'
    ]) {
      expect(source).toContain(`'${emailId}'`);
    }
    expect(source).toMatch(/woocommerce_email_enabled_/);

    // Automatic, local-only cleanup via cron — a fallback safety net only
    // (see the immediate-teardown check below for the primary mechanism),
    // and re-verifies the test-order signature before acting rather than
    // trusting the meta flag alone.
    expect(source).toMatch(/cos_cleanup_test_orders/);
    expect(source).toMatch(/wp_schedule_event/);
    expect(source).toMatch(/update_status\(\s*'cancelled'/);
    // No public order-deletion action registered anywhere in the theme.
    expect(source).not.toMatch(/wp_ajax(_nopriv)?_cos_(delete|remove)_order/);
  });

  test('test orders are tagged at the earliest possible point — before the order\'s first save, not after', () => {
    const source = readFileSync(
      join(__dirname, '..', 'wp-content', 'themes', 'cos-child', 'inc', 'commerce.php'),
      'utf8'
    );
    // woocommerce_checkout_create_order fires on the in-memory order object
    // before $order->save() — the earliest point at which billing_email is
    // already set, so there is no gap between "order exists" and "order is
    // tagged" for any notification to land in.
    expect(source).toMatch(/add_action\(\s*'woocommerce_checkout_create_order'/);
    const earlyHookBlock = source.slice(
      source.indexOf("'woocommerce_checkout_create_order'"),
      source.indexOf("'woocommerce_checkout_order_processed'")
    );
    expect(earlyHookBlock).toMatch(/cos_child_is_test_order\(\s*\$order\s*\)/);
    expect(earlyHookBlock).toMatch(/update_meta_data\(\s*'_cos_test_order'/);
  });

  test('suppressed emails are logged per-order so the suite can prove the suppression code path was actually reached', () => {
    const source = readFileSync(
      join(__dirname, '..', 'wp-content', 'themes', 'cos-child', 'inc', 'commerce.php'),
      'utf8'
    );
    expect(source).toMatch(/cos_test_suppressed_emails_/);
    expect(source).toMatch(/was_enabled/);
    // The visibility endpoint returns this log, not just the tag boolean.
    expect(source).toMatch(/'suppressed_emails'\s*=>/);
  });

  test('a dedicated, gated endpoint lets the suite cancel the specific test order it created immediately (real teardown, not cron-only)', () => {
    const source = readFileSync(
      join(__dirname, '..', 'wp-content', 'themes', 'cos-child', 'inc', 'commerce.php'),
      'utf8'
    );
    expect(source).toMatch(/function cos_child_test_cancel_order/);
    const cancelBlock = source.slice(source.indexOf('function cos_child_test_cancel_order'));
    // Gated behind the same three-factor local-only test-hook guard as
    // every other test hook in this project.
    expect(cancelBlock).toMatch(/cos_contact_test_hooks_enabled\(\)/);
    // Never trusts order_id/meta alone — re-verifies both the order key
    // (proves ownership, same pattern as cos_child_confirm_purchase_tracked
    // in inc/analytics.php) and cos_child_is_test_order() itself before
    // acting, so this can never be used to cancel a real/arbitrary order
    // even though the whole route is already local-test-only.
    expect(cancelBlock).toMatch(/hash_equals\(\s*\$order->get_order_key\(\)/);
    expect(cancelBlock).toMatch(/cos_child_is_test_order\(\s*\$order\s*\)/);
    expect(cancelBlock).toMatch(/update_status\(\s*'cancelled'/);

    const testSource = readFileSync(join(__dirname, '10-consent-and-analytics.spec.ts'), 'utf8');
    expect(testSource).toMatch(/cos_test_cancel_order/);
    // Runs in a finally block, so it executes even if an earlier assertion
    // in the test throws.
    expect(testSource).toMatch(/}\s*finally\s*{[\s\S]*cos_test_cancel_order/);
  });

  test('the order-creating purchase test is guarded to local/dedicated test environments only', () => {
    const source = readFileSync(join(__dirname, '10-consent-and-analytics.spec.ts'), 'utf8');
    expect(source).toMatch(/isProductionLikeBaseUrl/);
    expect(source).toMatch(/test\.skip\(isProductionLikeBaseUrl\(baseURL\)/);
  });
});
