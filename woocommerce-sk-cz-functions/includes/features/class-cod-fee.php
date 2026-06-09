<?php
/**
 * Cash on delivery fee feature.
 *
 * @package WooCommerce_SK_CZ_Functions
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WSCF_COD_Fee {

	/**
	 * Settings service.
	 *
	 * @var WSCF_Settings
	 */
	private $settings;

	/**
	 * Constructor.
	 *
	 * @param WSCF_Settings $settings Settings service.
	 */
	public function __construct( $settings ) {
		$this->settings = $settings;
	}

	/**
	 * Register feature hooks.
	 *
	 * @return void
	 */
	public function register_hooks() {
		add_action( 'woocommerce_cart_calculate_fees', array( $this, 'maybe_add_cod_fee' ), 20, 1 );
		add_action( 'woocommerce_review_order_before_payment', array( $this, 'enqueue_classic_checkout_refresh_script' ) );
	}

	/**
	 * Add COD fee when the COD gateway is selected.
	 *
	 * Uses cart total after shipping, taxes, and coupons but before this fee.
	 * WooCommerce stores the chosen payment method in the session for both
	 * classic checkout updates and Store API / Checkout Block updates.
	 *
	 * @param WC_Cart $cart Cart object.
	 * @return void
	 */
	public function maybe_add_cod_fee( $cart ) {
		if ( ! $cart instanceof WC_Cart ) {
			return;
		}

		$selected_payment_method = $this->get_selected_payment_method();

		if ( is_admin() && ! wp_doing_ajax() && ! ( defined( 'REST_REQUEST' ) && REST_REQUEST ) ) {
			return;
		}

		if ( 'cod' !== $selected_payment_method ) {
			return;
		}

		$base_total = $this->get_cart_total_for_cod_band_matching( $cart );
		$fee_amount = $this->get_fee_amount_for_total( $base_total );

		if ( $fee_amount <= 0 ) {
			return;
		}

		$cart->add_fee( $this->get_fee_label(), $fee_amount, false );
	}

	/**
	 * Get selected payment method from session.
	 *
	 * @return string
	 */
	private function get_selected_payment_method() {
		if ( isset( $_POST['payment_method'] ) ) {
			return sanitize_text_field( wp_unslash( $_POST['payment_method'] ) );
		}

		if ( ! WC()->session || ! is_callable( array( WC()->session, 'get' ) ) ) {
			return '';
		}

		$payment_method = WC()->session->get( 'chosen_payment_method', '' );

		if ( '' === $payment_method && WC()->payment_gateways() ) {
			$available_gateways = WC()->payment_gateways()->get_available_payment_gateways();

			if ( is_array( $available_gateways ) ) {
				foreach ( $available_gateways as $gateway_id => $gateway ) {
					if ( is_object( $gateway ) && method_exists( $gateway, 'get_current' ) && $gateway->get_current() ) {
						$payment_method = $gateway_id;
						break;
					}
				}
			}
		}

		return is_string( $payment_method ) ? $payment_method : '';
	}

	/**
	 * Get total used for COD band matching.
	 *
	 * After shipping, after tax, after coupon, before COD fee.
	 *
	 * @param WC_Cart $cart Cart object.
	 * @return float
	 */
	private function get_cart_total_for_cod_band_matching( $cart ) {
		$totals = $cart->get_totals();

		$base_total = $this->get_total_value( $totals, 'total' )
			- $this->get_total_value( $totals, 'fees_total' )
			- $this->get_total_value( $totals, 'fees_total_tax' );

		return max( 0, $base_total );
	}

	/**
	 * Safely read one total value from Woo totals arrays across checkout surfaces.
	 *
	 * @param array<string, mixed> $totals Totals array.
	 * @param string               $key    Total key.
	 * @return float
	 */
	private function get_total_value( $totals, $key ) {
		if ( ! is_array( $totals ) || ! isset( $totals[ $key ] ) ) {
			return 0.0;
		}

		return (float) $totals[ $key ];
	}

	/**
	 * Resolve fee amount for the current cart total.
	 *
	 * @param float $cart_total Cart total used for matching.
	 * @return float
	 */
	private function get_fee_amount_for_total( $cart_total ) {
		return (float) wc_format_decimal( $this->settings->get_setting_value( 'cod_fee_amount' ), wc_get_price_decimals() );
	}

	/**
	 * Get frontend fee label.
	 *
	 * @return string
	 */
	private function get_fee_label() {
		return $this->settings->get_cod_fee_label();
	}

	/**
	 * Refresh classic checkout totals when the payment method changes.
	 *
	 * @return void
	 */
	public function enqueue_classic_checkout_refresh_script() {
		if ( ! function_exists( 'is_checkout' ) || ! is_checkout() || is_order_received_page() ) {
			return;
		}

		wc_enqueue_js(
			'jQuery(function($){$("form.checkout").on("change","input[name=\"payment_method\"]",function(){$(document.body).trigger("update_checkout");});});'
		);
	}

}
