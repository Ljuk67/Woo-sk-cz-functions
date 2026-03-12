<?php
/**
 * Hide paid shipping methods when free shipping is available.
 *
 * @package WooCommerce_SK_CZ_Functions
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WSCF_Hide_Shipping_When_Free {

	/**
	 * Register feature hooks.
	 *
	 * @return void
	 */
	public function register_hooks() {
		add_filter( 'woocommerce_package_rates', array( $this, 'filter_package_rates' ), 100, 2 );
	}

	/**
	 * Keep only free shipping rates when at least one free rate is available.
	 *
	 * @param array<string, WC_Shipping_Rate> $rates   Shipping rates for the package.
	 * @param array<string, mixed>            $package Current shipping package.
	 * @return array<string, WC_Shipping_Rate>
	 */
	public function filter_package_rates( $rates, $package ) {
		unset( $package );

		if ( ! is_array( $rates ) || empty( $rates ) ) {
			return $rates;
		}

		$free_rates = array();

		foreach ( $rates as $rate_id => $rate ) {
			if ( ! is_object( $rate ) || empty( $rate->method_id ) ) {
				continue;
			}

			if ( 'free_shipping' === $rate->method_id ) {
				$free_rates[ $rate_id ] = $rate;
			}
		}

		return ! empty( $free_rates ) ? $free_rates : $rates;
	}
}
