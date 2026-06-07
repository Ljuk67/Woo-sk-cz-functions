<?php
/**
 * Checkout button text feature.
 *
 * @package WooCommerce_SK_CZ_Functions
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WSCF_Checkout_Button_Text {

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
		add_filter( 'woocommerce_order_button_text', array( $this, 'filter_classic_checkout_button_text' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_block_checkout_script' ) );
	}

	/**
	 * Replace the classic checkout order button text.
	 *
	 * @param string $button_text Default button text.
	 * @return string
	 */
	public function filter_classic_checkout_button_text( $button_text ) {
		unset( $button_text );

		return $this->get_checkout_button_text();
	}

	/**
	 * Enqueue Checkout Block button label filter.
	 *
	 * @return void
	 */
	public function enqueue_block_checkout_script() {
		$script_path = WSCF_PLUGIN_PATH . 'assets/js/checkout-button-text-block.js';

		if ( ! function_exists( 'is_checkout' ) || ! is_checkout() || is_order_received_page() || ! file_exists( $script_path ) ) {
			return;
		}

		wp_enqueue_script(
			'wscf-checkout-button-text-block',
			WSCF_PLUGIN_URL . 'assets/js/checkout-button-text-block.js',
			array(),
			(string) filemtime( $script_path ),
			true
		);

		wp_add_inline_script(
			'wscf-checkout-button-text-block',
			'window.wscfCheckoutButtonText=' . wp_json_encode(
				array(
					'text' => $this->get_checkout_button_text(),
				)
			) . ';',
			'before'
		);
	}

	/**
	 * Get sanitized checkout button text with default fallback.
	 *
	 * @return string
	 */
	private function get_checkout_button_text() {
		return $this->settings->get_checkout_button_text();
	}
}
