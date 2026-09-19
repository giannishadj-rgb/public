<?php
/**
 * COS Child Theme — Commerce
 *
 * WooCommerce behavior: customer registration, theme support, template
 * overrides, checkout customizations, bag count, shop-page UX animations,
 * and add-to-cart fragments. Asset loading/dequeuing (what scripts and
 * styles get shipped) lives in inc/performance.php instead — this file is
 * about what WooCommerce *does*, not what gets downloaded.
 */

/* ─── Purchase-quantity sanity cap ──────────────────────────────── */
// None of the 5 catalogue products (COS-001..004, COS-BUNDLE) has
// "manage stock" turned on (confirmed: get_manage_stock() is false, and
// get_max_purchase_quantity() is -1/unlimited for all of them) — a
// customer can currently type any quantity, including an accidental
// 9999, into the qty field. This does not touch real stock levels or
// business inventory decisions; it only caps how many of one line item a
// single order can contain, which is basic accidental-order protection
// regardless of how deep the actual warehouse stock is.
const COS_MAX_LINE_ITEM_QTY = 10;

add_filter( 'woocommerce_quantity_input_max', function ( $max ) {
	// $max here is WC_Product::get_max_purchase_quantity()'s own PRE-filter
	// value (-1/unlimited, or a real stock-based cap) — this filter runs
	// *inside* that method, so calling get_max_purchase_quantity() again
	// from here would recurse into this same filter infinitely. Only
	// tighten the cap, never loosen a smaller stock-based one.
	if ( is_numeric( $max ) && (float) $max > 0 && (float) $max < COS_MAX_LINE_ITEM_QTY ) {
		return $max;
	}
	return COS_MAX_LINE_ITEM_QTY;
}, 10, 1 );

add_filter( 'woocommerce_add_to_cart_validation', function ( bool $valid, int $product_id, int $quantity ): bool {
	if ( $quantity > COS_MAX_LINE_ITEM_QTY ) {
		wc_add_notice(
			sprintf( 'Please order %d or fewer of this item per order. Contact us for larger quantities.', COS_MAX_LINE_ITEM_QTY ),
			'error'
		);
		return false;
	}
	return $valid;
}, 10, 3 );

add_action( 'woocommerce_check_cart_items', function (): void {
	if ( ! WC()->cart ) return;
	foreach ( WC()->cart->get_cart() as $cart_item ) {
		if ( $cart_item['quantity'] > COS_MAX_LINE_ITEM_QTY ) {
			wc_add_notice(
				sprintf( 'One item in your bag exceeds the %d-per-order limit. Please adjust the quantity to continue.', COS_MAX_LINE_ITEM_QTY ),
				'error'
			);
		}
	}
} );

/* ─── Complete Ritual bundle availability follows its components ─── */
// The bundle (COS-BUNDLE) is a plain simple WooCommerce product with no
// structural link to its 4 components — there is no bundle/composite
// product extension installed. This maps it by SKU (stable) rather than
// hard-coded post IDs, and keeps the bundle's own availability truthful
// relative to its components WHENEVER stock management is turned on for
// them (it currently isn't — see OWNER-ACTIONS.md for the real decision
// this is standing in for: whether/how to track per-component stock at
// all). This is intentionally read-only sync (component -> bundle), never
// invents stock data, and is a no-op today since every product is
// unmanaged/in-stock.
function cos_child_bundle_component_skus(): array {
	return apply_filters( 'cos_child_bundle_component_skus', [ 'COS-001', 'COS-002', 'COS-003', 'COS-004' ] );
}

function cos_child_bundle_components_in_stock(): bool {
	// Re-entrancy guard: is_in_stock()/get_stock_status() are used pervasively
	// throughout WooCommerce (related/upsell product loops, catalog
	// visibility sync, etc.), including on the component PDPs themselves,
	// which can re-enter this same check while it's already running. Treat
	// a re-entrant call as "in stock" (the neutral/default WooCommerce
	// answer) rather than recursing.
	static $checking = false;
	if ( $checking ) return true;
	$checking = true;

	$result = true;
	foreach ( cos_child_bundle_component_skus() as $sku ) {
		$product_id = wc_get_product_id_by_sku( $sku );
		$product    = $product_id ? wc_get_product( $product_id ) : null;
		if ( ! $product || ! $product->is_in_stock() ) {
			$result = false;
			break;
		}
	}

	$checking = false;
	return $result;
}

add_filter( 'woocommerce_product_is_in_stock', function ( bool $in_stock, WC_Product $product ): bool {
	if ( 'COS-BUNDLE' !== $product->get_sku() ) return $in_stock;
	return $in_stock && cos_child_bundle_components_in_stock();
}, 10, 2 );

add_filter( 'woocommerce_product_get_stock_status', function ( $status, $product ) {
	if ( ! $product instanceof WC_Product || 'COS-BUNDLE' !== $product->get_sku() ) return $status;
	return cos_child_bundle_components_in_stock() ? $status : 'outofstock';
}, 10, 2 );

add_filter( 'woocommerce_add_to_cart_validation', function ( bool $valid, int $product_id ): bool {
	$product = wc_get_product( $product_id );
	if ( ! $product || 'COS-BUNDLE' !== $product->get_sku() ) return $valid;
	if ( ! cos_child_bundle_components_in_stock() ) {
		wc_add_notice( 'The Complete Ritual is temporarily unavailable because one of its components is out of stock. Please shop the individual products instead.', 'error' );
		return false;
	}
	return $valid;
}, 10, 2 );

/* ─── Accepted payment methods (dynamic, for Terms/Privacy copy) ── */
// [cos_payment_methods] renders the names of the currently-ENABLED
// WooCommerce gateways, so legal/policy copy can't silently drift out of
// sync with reality the way a hardcoded "processed by Stripe" sentence did
// while Stripe was disabled and only Cash on Delivery was active.
add_shortcode( 'cos_payment_methods', function (): string {
	if ( ! function_exists( 'WC' ) || ! WC()->payment_gateways() ) {
		return 'the methods shown at checkout';
	}
	$gateways = WC()->payment_gateways()->get_available_payment_gateways();
	if ( empty( $gateways ) ) {
		return 'the methods shown at checkout';
	}
	$titles = array_map( function ( $gateway ) {
		return wp_strip_all_tags( $gateway->get_title() );
	}, $gateways );
	return esc_html( implode( ', ', $titles ) );
} );

/* ─── Gateway-disabled asset hygiene ────────────────────────────── */
// WooCommerce Payments (WCPay/Stripe-backed) registers a small inline
// script (window.wcpayAssets) on EVERY frontend page load unconditionally
// — it does not check whether any of its gateways are actually enabled
// first (see WC_Payments::enqueue_assets_script(), hooked at the default
// wp_enqueue_scripts priority). Its own per-gateway JS (checkout.js, Apple
// Pay/Google Pay express-checkout buttons, WooPay) already self-guards
// correctly and only loads once a gateway is enabled — this dequeue only
// removes the one unconditional leftover, and only when genuinely nothing
// from WooCommerce Payments is enabled, so it loads normally again the
// moment an owner turns a gateway on.
function cos_child_any_woopayments_gateway_enabled(): bool {
	if ( ! function_exists( 'WC' ) || ! WC()->payment_gateways() ) return false;
	foreach ( WC()->payment_gateways()->payment_gateways() as $id => $gateway ) {
		if ( 0 === strpos( (string) $id, 'woocommerce_payments' ) && 'yes' === $gateway->enabled ) {
			return true;
		}
	}
	return false;
}
add_action( 'wp_enqueue_scripts', function (): void {
	if ( cos_child_any_woopayments_gateway_enabled() ) return;
	wp_dequeue_script( 'WCPAY_ASSETS' );
	wp_deregister_script( 'WCPAY_ASSETS' );
}, 20 );

// WooCommerce Payments also has a deliberate self-defence mechanism
// (WC_Payments_Checkout::maybe_load_checkout_scripts(), hooked on
// woocommerce_after_checkout_form — see its own docblock: "Sometimes the
// filters can remove the payment gateway from the checkout page... This
// function ensures that these scripts are loaded [anyway]") that force-
// enqueues its real Stripe-backed checkout bundle (wcpay-upe-checkout,
// pulling in js.stripe.com) REGARDLESS of whether any of its gateways are
// actually enabled — confirmed on this site: with every
// woocommerce_payments_* gateway disabled, checkout.js still downloaded
// and threw a real console error ("Cannot read properties of undefined
// (reading 'url')"), because it initialises assuming a gateway config
// that isn't there. Separately, Woopay_Tracker::add_frontend_tracks_scripts()
// enqueues wcpay-frontend-tracks unconditionally on every 'wp_footer' at
// its default priority (10). Both fire before scripts are actually
// printed (wp_print_footer_scripts runs on 'wp_footer' at priority 20 —
// see wp-includes/script-loader.php), so dequeuing here at priority 15
// runs after both of those enqueue calls but before printing.
add_action( 'wp_footer', function (): void {
	if ( cos_child_any_woopayments_gateway_enabled() ) return;
	foreach ( [ 'wcpay-upe-checkout', 'wcpay-frontend-tracks', 'stripe' ] as $handle ) {
		wp_dequeue_script( $handle );
	}
}, 15 );

/* ─── COD must not appear where we cannot actually ship ─────────── */
// As of the 2026-09-11 owner decision (OWNER-ACTIONS.md §10), fulfilment is
// worldwide: the Switzerland zone is joined by a real "Rest of World" zone
// with its own unconditional free_shipping method, so this check now
// naturally passes for every destination rather than just CH — no change
// needed here, since it already ties COD's visibility to whether a real
// shipping rate exists for the package, not to a hardcoded country. Cash on
// Delivery's own "enable for shipping methods" restriction is empty
// (unrestricted) by default, which would otherwise let it appear as a
// selectable payment method even for a destination with no shipping rate
// at all — kept as a safety net for if fulfilment is ever restricted again.
add_filter( 'woocommerce_available_payment_gateways', function ( array $gateways ): array {
	if ( ! isset( $gateways['cod'] ) || ! function_exists( 'WC' ) || ! WC()->cart ) {
		return $gateways;
	}
	if ( ! WC()->cart->needs_shipping() ) {
		return $gateways;
	}
	$has_rate = false;
	foreach ( WC()->shipping()->get_packages() as $package ) {
		if ( ! empty( $package['rates'] ) ) {
			$has_rate = true;
			break;
		}
	}
	if ( ! $has_rate ) {
		unset( $gateways['cod'] );
	}
	return $gateways;
} );

/* ─── Payment gateway detection + dynamic checkout CTA ──────────── */
// Thin wrapper on the same WC()->payment_gateways()->get_available_payment_gateways()
// call [cos_payment_methods] above already uses — single place other code
// (checkout CTA, cart/PDP payment-method mentions) reads "what's actually
// enabled" from, rather than each caller re-deriving it.
function cos_child_enabled_payment_gateways(): array {
	if ( ! function_exists( 'WC' ) || ! WC()->payment_gateways() ) return [];
	return WC()->payment_gateways()->get_available_payment_gateways();
}

// "Place order" is WooCommerce's generic default regardless of which
// gateway is selected — misleading either direction: Cash on Delivery
// isn't "paying securely" online, and the reverse is equally true once a
// real online gateway is enabled. Server-rendered here for whichever
// gateway WooCommerce would pre-check by default on page load (its own
// session-remembered choice, or the first available gateway); the checkout
// script (page-checkout.php) updates it live if the shopper changes
// gateway. Only COD is enabled today (see OWNER-ACTIONS.md §12), so this
// currently always resolves to the COD branch — the "Pay €X securely"
// branch is exercised the moment a real online gateway is turned on, no
// further code change needed then.
function cos_child_dynamic_order_button_text( string $default_text ): string {
	$gateways = cos_child_enabled_payment_gateways();
	if ( empty( $gateways ) ) return $default_text; // handled by the no-gateway notice below instead

	$chosen = ( function_exists( 'WC' ) && WC()->session ) ? WC()->session->get( 'chosen_payment_method' ) : '';
	if ( ! $chosen || ! isset( $gateways[ $chosen ] ) ) {
		$chosen = array_key_first( $gateways );
	}

	if ( 'cod' === $chosen ) {
		return 'Place order — pay on delivery';
	}

	$total = ( function_exists( 'WC' ) && WC()->cart ) ? wp_strip_all_tags( wc_price( WC()->cart->get_total( 'edit' ) ) ) : '';
	return $total ? "Pay {$total} securely" : 'Pay securely';
}
add_filter( 'woocommerce_order_button_text', 'cos_child_dynamic_order_button_text' );

// Sitewide CTA tracking (inc/analytics.php's delegated cta_click listener)
// reads data-cro-cta off whatever was clicked — WooCommerce builds this
// specific button's HTML itself (not template markup this theme owns), so
// the attribute is added via string replacement on its stable id="place_order"
// rather than by editing a template file.
add_filter( 'woocommerce_order_button_html', function ( string $html ): string {
	return str_replace( 'id="place_order"', 'id="place_order" data-cro-cta="checkout-place-order"', $html );
} );

// WooCommerce's own checkout/payment.php template already renders this
// filter's output as a fallback notice when get_available_payment_gateways()
// is empty (e.g. a destination with a shipping rate but no gateway willing
// to serve it) — reused rather than building a separate empty-state
// mechanism, just with an actionable link instead of the generic default.
add_filter( 'woocommerce_no_available_payment_methods_message', function ( string $message ): string {
	return 'We\'re unable to offer a payment method for this order right now. Please <a href="' . esc_url( home_url( '/contact/' ) ) . '">contact our concierge team</a> and we\'ll help you complete your order.';
} );

// Reassurance line directly under the Place Order button — hooked here
// (rather than injected once via JS) so it survives every AJAX
// update_order_review refresh automatically, same as the rest of #order_review.
add_action( 'woocommerce_review_order_after_submit', function (): void {
	$gateways = cos_child_enabled_payment_gateways();
	$ids      = array_keys( $gateways );
	if ( array( 'cod' ) === $ids ) {
		$payment_bit = 'Pay on Delivery';
	} elseif ( in_array( 'cod', $ids, true ) && count( $ids ) > 1 ) {
		$payment_bit = 'Secure Payment or Pay on Delivery';
	} elseif ( $ids ) {
		$payment_bit = 'Secure Online Payment';
	} else {
		$payment_bit = '';
	}
	$parts = array_filter( array( 'Encrypted Checkout', $payment_bit, '30-Day Returns' ) );
	echo '<p class="cos-co-reassurance">' . esc_html( implode( ' · ', $parts ) ) . ' — <a href="/shipping-returns/">see our return policy</a></p>';
} );

/* ─── Automated-test order hygiene ──────────────────────────────── */
// The Playwright purchase-flow test (tests/10-consent-and-analytics.spec.ts)
// places a real Cash on Delivery order using billing email
// playwright-purchase-test@example.test — "example.test" is an IANA/RFC
// 2606 domain reserved specifically for testing, never a real customer's
// address. Orders using it, created while this site identifies as
// 'local', are tagged, kept out of the fulfilment queue, and never
// triggered a real customer-facing notification. This is read-only
// pattern-matching on data the test already produces — nothing here
// changes what the test does or exposes any new endpoint.
function cos_child_is_test_order( WC_Order $order ): bool {
	return 'local' === wp_get_environment_type()
		&& str_ends_with( strtolower( $order->get_billing_email() ), '@example.test' );
}

/**
 * Tags the order on 'woocommerce_checkout_create_order' — the earliest
 * possible point in the whole checkout flow: this fires on the in-memory
 * $order object *before its first save()*, and well before
 * WC_Checkout::process_checkout() goes on to call the payment gateway's
 * process_payment() (which is what actually transitions order status and
 * is what triggers WooCommerce's status-change emails). Tagging here means
 * the very first row ever written for this order already carries the meta
 * — there is no earlier point at which an email could fire with the tag
 * not yet in place. (The previous approach tagged on
 * woocommerce_checkout_order_processed, which fires after the order's
 * first save and only immediately before process_checkout() calls the
 * gateway — safe in practice for this site's classic-checkout-only setup,
 * but leaving a save-to-tag gap a third-party hook could theoretically
 * land in.) update_meta_data() is deliberately not followed by save() here
 * — the checkout flow's own save() a few lines later persists it.
 */
add_action( 'woocommerce_checkout_create_order', function ( $order ): void {
	if ( ! ( $order instanceof WC_Order ) || ! cos_child_is_test_order( $order ) ) return;
	$order->update_meta_data( '_cos_test_order', 1 );
	$order->add_order_note( 'Marked as an automated test order — billing email uses the reserved example.test domain (RFC 2606) on a local environment. Will be auto-cancelled; never fulfilled or emailed.' );
}, 5 );

// Redundant catch-up for any order-creation path that doesn't fire
// woocommerce_checkout_create_order (e.g. an order built programmatically
// rather than through checkout) — belt and suspenders, not the primary
// mechanism.
add_action( 'woocommerce_checkout_order_processed', function ( $order_id ): void {
	$order = wc_get_order( $order_id );
	if ( ! $order || ! cos_child_is_test_order( $order ) ) return;
	if ( $order->get_meta( '_cos_test_order' ) ) return;
	$order->update_meta_data( '_cos_test_order', 1 );
	$order->add_order_note( 'Marked as an automated test order (fallback tagging point — see cos_child_is_test_order()).' );
	$order->save();
}, 20 );

// Suppress every standard WooCommerce order email (customer- and admin-
// facing alike) for orders tagged above, so a test run never sends a real
// notification. Uses WooCommerce's own per-email "is this enabled" filter
// rather than blocking wp_mail globally, so real orders are never touched.
//
// Also records, per order, which email ids were actually evaluated and
// suppressed here — not just that no message ended up in an inbox, which
// could be true for unrelated reasons (e.g. WooCommerce emails disabled
// entirely in settings). This is local-test-only bookkeeping (guarded by
// the same three-factor gate as every other test hook), exposed via
// cos_child_test_get_order_meta() below so the automated suite can prove
// the suppression code path itself was actually reached, not merely infer
// it from an empty inbox.
add_action( 'woocommerce_init', function (): void {
	foreach ( [
		'new_order', 'cancelled_order', 'failed_order', 'customer_on_hold_order',
		'customer_processing_order', 'customer_completed_order', 'customer_refunded_order',
		'customer_partially_refunded_order', 'customer_invoice', 'customer_note',
	] as $email_id ) {
		add_filter( "woocommerce_email_enabled_{$email_id}", function ( $enabled, $object ) use ( $email_id ) {
			if ( ! ( $object instanceof WC_Order ) || ! $object->get_meta( '_cos_test_order' ) ) return $enabled;

			if ( function_exists( 'cos_contact_test_hooks_enabled' ) && cos_contact_test_hooks_enabled() ) {
				$log_key = 'cos_test_suppressed_emails_' . $object->get_id();
				$log     = get_transient( $log_key ) ?: [];
				$log[]   = [ 'email_id' => $email_id, 'was_enabled' => (bool) $enabled ];
				set_transient( $log_key, $log, HOUR_IN_SECONDS );
			}

			return false;
		}, 10, 2 );
	}
} );

// Automatic cleanup: cancel (never hard-delete — keeps a full, reversible
// audit trail) tagged test orders once they're old enough that the test
// run that created them has clearly finished. Local-only; never runs
// anywhere this constant/environment combination isn't explicitly true.
add_action( 'init', function (): void {
	if ( 'local' !== wp_get_environment_type() ) return;
	if ( ! wp_next_scheduled( 'cos_cleanup_test_orders' ) ) {
		wp_schedule_event( time(), 'hourly', 'cos_cleanup_test_orders' );
	}
} );

add_action( 'cos_cleanup_test_orders', function (): void {
	if ( 'local' !== wp_get_environment_type() ) return;
	$orders = wc_get_orders( [
		'meta_key'     => '_cos_test_order',
		'meta_value'   => 1,
		'limit'        => -1,
		'date_created' => '<' . ( time() - 10 * MINUTE_IN_SECONDS ),
	] );
	foreach ( $orders as $order ) {
		if ( ! cos_child_is_test_order( $order ) ) continue; // re-verify, never trust meta alone
		if ( 'cancelled' === $order->get_status() ) continue;
		$order->update_status( 'cancelled', 'Automatically cancelled: automated test order past its retention window.' );
	}
} );

// Read-only visibility for the automated suite to verify the tagging above
// actually happened, without exposing anything publicly: gated behind the
// exact same three-factor local-only test-hook gate as the contact form's
// test hooks (cos_contact_test_hooks_enabled(), defined in the
// cos-contact.php mu-plugin, which loads before the theme). Never reachable
// without COS_ENABLE_TEST_HOOKS + 'local' environment + COS_TEST_MODE all
// being true — i.e. never on staging/production regardless of request.
add_action( 'wp_ajax_cos_test_get_order_meta', 'cos_child_test_get_order_meta' );
add_action( 'wp_ajax_nopriv_cos_test_get_order_meta', 'cos_child_test_get_order_meta' );
function cos_child_test_get_order_meta(): void {
	if ( ! function_exists( 'cos_contact_test_hooks_enabled' ) || ! cos_contact_test_hooks_enabled() ) {
		wp_send_json_error( 'Not available.', 403 );
	}
	// Security audit finding M1: this previously trusted order_id alone, with
	// no proof the caller actually has any relationship to the order — an
	// IDOR pattern, unlike cos_child_test_cancel_order() right below it,
	// which already required this same order_key match. Same requirement
	// here now, for consistency, even though the outer three-factor gate
	// above is already local-only.
	$order_id  = absint( $_POST['order_id'] ?? 0 );
	$order_key = sanitize_text_field( wp_unslash( $_POST['order_key'] ?? '' ) );
	$order     = $order_id ? wc_get_order( $order_id ) : false;
	if ( ! $order || ! $order_key || ! hash_equals( $order->get_order_key(), $order_key ) ) {
		wp_send_json_error( 'Not found.', 404 );
	}
	wp_send_json_success( [
		'is_test_order'      => (bool) $order->get_meta( '_cos_test_order' ),
		'status'              => $order->get_status(),
		'suppressed_emails'   => get_transient( 'cos_test_suppressed_emails_' . $order->get_id() ) ?: [],
	] );
}

/**
 * Lets the automated suite cancel the specific test order it just created
 * immediately, as part of its own teardown, rather than relying solely on
 * the hourly cron's 10-minute-age window — closing the gap between "order
 * placed" and "order cancelled" down to the length of the test itself.
 * The cron stays in place as a fallback for a test run that crashes or is
 * killed before reaching its own teardown step. Re-verifies
 * cos_child_is_test_order() itself (never trusts the order_id/meta a
 * caller supplies) and requires the order key, exactly like
 * cos_child_confirm_purchase_tracked() in inc/analytics.php — this can
 * never be used to cancel an arbitrary/real order even though the whole
 * route is already gated to local test-only.
 */
add_action( 'wp_ajax_cos_test_cancel_order', 'cos_child_test_cancel_order' );
add_action( 'wp_ajax_nopriv_cos_test_cancel_order', 'cos_child_test_cancel_order' );
function cos_child_test_cancel_order(): void {
	if ( ! function_exists( 'cos_contact_test_hooks_enabled' ) || ! cos_contact_test_hooks_enabled() ) {
		wp_send_json_error( 'Not available.', 403 );
	}

	$order_id  = absint( $_POST['order_id'] ?? 0 );
	$order_key = sanitize_text_field( wp_unslash( $_POST['order_key'] ?? '' ) );
	$order     = $order_id ? wc_get_order( $order_id ) : false;

	if ( ! $order || ! $order_key || ! hash_equals( $order->get_order_key(), $order_key ) ) {
		wp_send_json_error( 'Invalid order.', 403 );
	}
	if ( ! cos_child_is_test_order( $order ) ) {
		wp_send_json_error( 'Refusing to cancel: this order is not tagged as an automated test order.', 403 );
	}

	if ( 'cancelled' !== $order->get_status() ) {
		$order->update_status( 'cancelled', 'Cancelled immediately by the automated test suite\'s own teardown step.' );
	}

	wp_send_json_success( [ 'status' => $order->get_status() ] );
}

/* ─── Save first/last name on WooCommerce registration ─────────── */
add_action( 'woocommerce_created_customer', function ( int $customer_id ): void {
	if ( ! empty( $_POST['first_name'] ) ) {
		$fn = sanitize_text_field( wp_unslash( $_POST['first_name'] ) );
		update_user_meta( $customer_id, 'first_name', $fn );
		update_user_meta( $customer_id, 'billing_first_name', $fn );
	}
	if ( ! empty( $_POST['last_name'] ) ) {
		$ln = sanitize_text_field( wp_unslash( $_POST['last_name'] ) );
		update_user_meta( $customer_id, 'last_name', $ln );
		update_user_meta( $customer_id, 'billing_last_name', $ln );
	}
} );

/* ─── WooCommerce support ───────────────────────────────────────── */

add_action( 'after_setup_theme', function (): void {
	add_theme_support( 'woocommerce' );
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );
} );

// Remove default WooCommerce styles so we can control all styling
add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );

// WooCommerce 10+ with block themes uses block templates by default and bypasses
// the classic PHP template loader. Returning false here tells WooCommerce there is
// no block template for single-product/archive-product, so it falls back to the
// classic loader which finds our woocommerce/*.php files in the theme directory.
add_filter( 'woocommerce_has_block_template', function ( bool $has, string $name ): bool {
	return in_array( $name, [ 'single-product', 'archive-product' ], true ) ? false : $has;
}, 10, 2 );

// Belt-and-suspenders: also override via template_include at the highest priority
// in case the above filter isn't reached. This is required for the shop page in
// particular — WordPress's block-theme template resolver matches page-shop.html by
// slug (a generic page template with no WooCommerce awareness) on the same
// template_include filter, and without this override it wins the race and renders
// the shop page's own (unused) post_content instead of the live product catalogue.
add_filter( 'template_include', function ( string $template ): string {
	if ( is_singular( 'product' ) ) {
		$override = get_stylesheet_directory() . '/woocommerce/single-product.php';
		return file_exists( $override ) ? $override : $template;
	}
	if ( function_exists( 'is_shop' ) && is_shop() ) {
		$override = get_stylesheet_directory() . '/woocommerce/archive-product.php';
		return file_exists( $override ) ? $override : $template;
	}
	return $template;
}, PHP_INT_MAX );

/* ─── Checkout: link to Terms instead of embedding the full page ───────── */
// By default WooCommerce dumps the entire Terms page (hero image, animations,
// its whole <style> block) into a hidden, scrollable div on checkout. Replace
// that with a plain link to the real Terms page — the checkbox stays required.
remove_action( 'woocommerce_checkout_terms_and_conditions', 'wc_terms_and_conditions_page_content', 30 );
add_filter( 'woocommerce_get_terms_and_conditions_checkbox_text', function ( string $text ): string {
	$terms_page_id = function_exists( 'wc_terms_and_conditions_page_id' ) ? wc_terms_and_conditions_page_id() : 0;
	if ( ! $terms_page_id ) return $text;
	$link = '<a href="' . esc_url( get_permalink( $terms_page_id ) ) . '" target="_blank" rel="noopener">' . esc_html__( 'terms and conditions', 'cos' ) . '</a>';
	/* translators: %s: terms and conditions link */
	return sprintf( __( 'I have read and agree to the website %s', 'woocommerce' ), $link );
} );

/* ─── Checkout: coupon form stays at WooCommerce's default position ────── */
// A previous customization here tried to move "Have a coupon?" out of the
// full-width top-of-page banner and down into the order summary, by hooking
// woocommerce_checkout_coupon_form() onto 'woocommerce_checkout_order_review'
// instead of its default 'woocommerce_before_checkout_form'. That avoided an
// earlier <table>-nesting bug (foster-parenting a coupon <form> out of a
// <tfoot>), but 'woocommerce_checkout_order_review' fires from inside
// <div id="order_review"> — itself inside the outer <form class="checkout">
// that wraps the entire [woocommerce_checkout] shortcode (form-checkout.php).
// A <form> cannot legally nest inside another <form>; per the HTML5 parsing
// algorithm browsers silently drop the inner <form> open/close tags entirely
// while keeping its children as loose markup. WooCommerce's own
// checkout.js binds coupon apply/toggle behaviour to 'form.checkout_coupon'
// and '.checkout_coupon' selectors — with the <form> tag gone, both selectors
// match nothing, so the "Click here to enter your code" toggle silently does
// nothing and "Apply coupon" has no form to submit. The coupon feature was
// fully non-functional as a result. Reverting to the stock hook position
// (before the outer <form> opens) is what actually fixes it — WooCommerce's
// own default markup already renders collapsed behind an accessible
// aria-expanded/aria-controls toggle, so nothing else needs to be rebuilt
// here. See tests/18-cro-funnel.spec.ts for the regression coverage.

// Update bag count from WooCommerce cart
function cos_woo_bag_count_script(): void {
	if ( ! function_exists( 'WC' ) ) return;
	$count = WC()->cart ? WC()->cart->get_cart_contents_count() : 0;
	?>
<script nonce="<?php echo esc_attr( cos_csp_nonce() ); ?>">
window.cosWooCount = <?php echo (int) $count; ?>;
(function(){
  var els = document.querySelectorAll('.cos-bag-count');
  var n = window.cosWooCount;
  els.forEach(function(el){ el.textContent = n > 0 ? String(n) : ''; });
})();
</script>
	<?php
}
add_action( 'wp_footer', 'cos_woo_bag_count_script', 4 );

// Product image tap/click zoom animation on shop page
add_action( 'wp_footer', function (): void {
	if ( ! is_page( 62 ) ) return;
	?>
<style nonce="<?php echo esc_attr( cos_csp_nonce() ); ?>">
@keyframes cos-img-tap{0%{transform:scale(1)}40%{transform:scale(1.07)}100%{transform:scale(1)}}
.cos-card-img-wrap.cos-tapped .cos-card-img{animation:cos-img-tap .35s cubic-bezier(.25,.46,.45,.94) forwards}
</style>
<script nonce="<?php echo esc_attr( cos_csp_nonce() ); ?>">
document.querySelectorAll('.cos-card-img-wrap').forEach(function(link){
  link.addEventListener('mousedown',function(){
    link.classList.add('cos-tapped');
    link.addEventListener('animationend',function(){
      link.classList.remove('cos-tapped');
    },{once:true});
  });
});
</script>
	<?php
}, 19 );

// Add-to-cart button feedback animation on shop page
add_action( 'wp_footer', function (): void {
	if ( ! is_page( 62 ) ) return;
	?>
<style nonce="<?php echo esc_attr( cos_csp_nonce() ); ?>">
.cos-card-btn.loading{opacity:.55;pointer-events:none;cursor:default}
.cos-card-btn.cos-btn-added{background:var(--cos-teal,#46656B)!important;pointer-events:none;transition:background .25s}
.cos-card-btn.cos-btn-reverting{transition:background .4s}
</style>
<script nonce="<?php echo esc_attr( cos_csp_nonce() ); ?>">
jQuery(function($){
  $(document.body).on('adding_to_cart',function(e,btn){
    btn.css('width', btn.outerWidth() + 'px');
    btn.text('Adding…');
  });
  $(document.body).on('added_to_cart',function(e,fragments,hash,btn){
    btn.text('Added ✓').addClass('cos-btn-added');
    setTimeout(function(){
      btn.addClass('cos-btn-reverting').removeClass('cos-btn-added');
      setTimeout(function(){
        btn.text('Add to Bag').removeClass('cos-btn-reverting').css('width','');
      },400);
    },1800);
  });
});
</script>
	<?php
}, 20 );

// Remove "View cart" link from add-to-cart notices
add_filter( 'woocommerce_add_to_cart_message_html', '__return_empty_string' );

// Update bag count via AJAX on cart change (used by add-to-cart AJAX)
add_filter( 'woocommerce_add_to_cart_fragments', function ( array $fragments ): array {
	$count = WC()->cart->get_cart_contents_count();
	$inner = $count > 0 ? (string) $count : '';
	// Must return the full outer HTML so WooCommerce replaces the element correctly
	$fragments['span.cos-bag-count'] = '<span class="cos-bag-count" aria-live="polite">' . $inner . '</span>';
	return $fragments;
} );

// Ensure WooCommerce AJAX add-to-cart JS is available on the custom shop page
add_action( 'wp_enqueue_scripts', function (): void {
	if ( function_exists( 'WC' ) && is_page( 62 ) ) {
		wp_enqueue_script( 'wc-add-to-cart' );
	}
}, 20 );

// Disable WooCommerce order attribution on non-checkout pages to prevent sourcebuster loading
add_filter( 'wc_order_attribution_enabled', function ( bool $enabled ): bool {
	if ( is_admin() ) return $enabled;
	$is_woo_page = ( function_exists( 'is_woocommerce' ) && is_woocommerce() ) || is_cart() || is_checkout() || is_account_page() || is_page( 62 );
	return $is_woo_page ? $enabled : false;
} );
