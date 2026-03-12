<?php
/**
 * Core plugin bootstrap.
 *
 * @package WooCommerce_SK_CZ_Funkcie
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WSCZF_Plugin {

	/**
	 * Settings service.
	 *
	 * @var WSCZF_Settings
	 */
	private $settings;

	/**
	 * Initialize plugin hooks.
	 *
	 * @return void
	 */
	public function init() {
		$this->settings = new WSCZF_Settings();

		add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );
		add_action( 'admin_init', array( $this->settings, 'register_settings' ) );

		if ( ! $this->is_woocommerce_active() ) {
			add_action( 'admin_notices', array( $this, 'show_woocommerce_required_notice' ) );
			return;
		}

		$this->register_features();
	}

	/**
	 * Load translation files.
	 *
	 * @return void
	 */
	public function load_textdomain() {
		load_plugin_textdomain(
			'woocommerce-sk-cz-funkcie',
			false,
			dirname( plugin_basename( WSCZF_PLUGIN_FILE ) ) . '/languages'
		);
	}

	/**
	 * Register feature modules based on settings.
	 *
	 * @return void
	 */
	private function register_features() {
		if ( $this->settings->is_feature_enabled( 'company_checkout_fields' ) ) {
			( new WSCZF_Company_Checkout_Fields() )->register_hooks();
		}

		if ( $this->settings->is_feature_enabled( 'gdpr_checkbox' ) ) {
			( new WSCZF_GDPR_Checkbox() )->register_hooks();
		}

		if ( $this->settings->is_feature_enabled( 'category_row' ) ) {
			( new WSCZF_Category_Row() )->register_hooks();
		}
	}

	/**
	 * Check if WooCommerce plugin is active.
	 *
	 * @return bool
	 */
	private function is_woocommerce_active() {
		return class_exists( 'WooCommerce' );
	}

	/**
	 * Admin notice when WooCommerce is missing.
	 *
	 * @return void
	 */
	public function show_woocommerce_required_notice() {
		if ( ! current_user_can( 'activate_plugins' ) ) {
			return;
		}

		echo '<div class="notice notice-error"><p>';
		echo esc_html__( 'WooCommerce SK/CZ Funkcie vyžaduje aktívny plugin WooCommerce.', 'woocommerce-sk-cz-funkcie' );
		echo '</p></div>';
	}
}
