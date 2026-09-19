<?php
/**
 * COS Child Theme — Shipping
 *
 * Single source of truth for which countries this store can actually ship
 * to, and every "shipping message" derived from that fact. See
 * OWNER-ACTIONS.md §10 — as of the 2026-09-11 owner decision, fulfilment
 * has genuinely expanded beyond Switzerland: a real WooCommerce
 * "Rest of World" zone with an unconditional zero-cost shipping method now
 * exists alongside the Switzerland zone, so every country WooCommerce
 * offers can actually complete checkout. This file is the one place that
 * fact is declared, so homepage/shop/PDP/cart/checkout/footer/SEO/
 * structured-data copy can't drift out of sync with each other. If
 * fulfilment is ever restricted again (e.g. a country excluded for
 * sanctions/export-control reasons), update this file — nothing else.
 */

/**
 * Countries this store can currently fulfil an order to, keyed by ISO
 * 3166-1 alpha-2 code. `null` means "every country WooCommerce lists" (the
 * current, genuinely-worldwide state) rather than a specific short list.
 * Every other function in this file — and every "worldwide shipping" claim
 * sitewide — reads from this.
 */
function cos_child_operational_ship_to_countries(): ?array {
	return null;
}

// Worldwide fulfilment now has a real WooCommerce zone/method behind it
// (the "Rest of World" zero-cost zone added alongside the Switzerland
// zone), so the country selector no longer needs to be restricted — every
// country WooCommerce lists is a genuine, completable checkout option.
// cos_child_operational_ship_to_countries() stays as the single place to
// reintroduce a restriction (e.g. a legally-excluded country) if that ever
// becomes necessary again; today it returns null, so no filter is applied.
add_filter( 'woocommerce_countries', function ( array $countries ): array {
	$restricted = cos_child_operational_ship_to_countries();
	if ( null === $restricted || is_admin() ) return $countries;
	return $restricted;
} );

/**
 * The one shipping claim sitewide, in the couple of lengths different
 * placements need. Only ever describes destinations we can genuinely
 * fulfil — see cos_child_operational_ship_to_countries().
 *
 * @param string $context 'badge' (PDP/shop trust badge, very short),
 *                         'long' (hero/cart sub-copy, full sentence), or
 *                         'short' (default, footer/trust-strip length).
 */
function cos_child_shipping_message( string $context = 'short' ): string {
	$countries       = cos_child_operational_ship_to_countries();
	$single_country  = is_array( $countries ) && 1 === count( $countries ) ? reset( $countries ) : null;

	switch ( $context ) {
		case 'badge':
			return $single_country ? "Free Shipping in {$single_country}" : 'Free Worldwide Shipping';
		case 'long':
			return $single_country
				? "Free shipping within {$single_country} on every order."
				: 'Free shipping on every order, worldwide.';
		case 'short':
		default:
			return $single_country
				? "Free shipping — {$single_country} only"
				: 'Free worldwide shipping';
	}
}

/**
 * The actual ISO 3166-1 alpha-2 codes checkout currently supports, for
 * anything (structured data, admin tooling) that needs the real list rather
 * than the copy-facing single/plural distinction above. Reads from
 * WooCommerce's own country list, which the woocommerce_countries filter
 * above already keeps in sync with cos_child_operational_ship_to_countries().
 */
function cos_child_ship_to_country_codes(): array {
	$restricted = cos_child_operational_ship_to_countries();
	if ( null !== $restricted ) return array_keys( $restricted );
	return array_keys( WC()->countries->get_countries() );
}
