<?php
/**
 * Core plugin bootstrap.
 *
 * @package WooCommerce_SK_CZ_Functions
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WSCF_Plugin {

	/**
	 * Settings service.
	 *
	 * @var WSCF_Settings
	 */
	private $settings;

	/**
	 * Initialize plugin hooks.
	 *
	 * @return void
	 */
	public function init() {
		$this->settings = new WSCF_Settings();
		$wc_settings_tab = new WSCF_WC_Settings_Tab();
		$wc_settings_tab->register_hooks();

		add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );
		add_action( 'plugins_loaded', array( $this, 'bootstrap_features' ), 20 );
		add_filter(
			'plugin_action_links_' . plugin_basename( WSCF_PLUGIN_FILE ),
			array( $this, 'add_plugin_settings_link' )
		);
	}

	/**
	 * Add a settings shortcut to the plugins page.
	 *
	 * @param array<int, string> $links Plugin action links.
	 * @return array<int, string>
	 */
	public function add_plugin_settings_link( $links ) {
		$settings_url = admin_url( 'admin.php?page=wc-settings&tab=wscf' );

		$settings_link = '<a href="' . esc_url( $settings_url ) . '">' .
			esc_html__( 'Settings', 'woocommerce-sk-cz-functions' ) .
		'</a>';

		array_unshift( $links, $settings_link );

		return $links;
	}

	/**
	 * Initialize feature layer after plugins are loaded.
	 *
	 * @return void
	 */
	public function bootstrap_features() {
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
			'woocommerce-sk-cz-functions',
			false,
			dirname( plugin_basename( WSCF_PLUGIN_FILE ) ) . '/languages'
		);
	}

	/**
	 * Register feature modules based on settings.
	 *
	 * @return void
	 */
	private function register_features() {
		( new WSCF_Checkout_Button_Text( $this->settings ) )->register_hooks();

		if ( $this->settings->is_feature_enabled( 'company_checkout_fields' ) ) {
			( new WSCF_Company_Checkout_Fields() )->register_hooks();
		}

		if ( $this->settings->is_feature_enabled( 'gdpr_checkbox' ) ) {
			( new WSCF_GDPR_Checkbox() )->register_hooks();
		}

		if ( $this->settings->is_feature_enabled( 'category_row' ) ) {
			( new WSCF_Category_Row() )->register_hooks();
		}

		if ( $this->settings->is_feature_enabled( 'hide_shipping_when_free' ) ) {
			( new WSCF_Hide_Shipping_When_Free() )->register_hooks();
		}

		if ( $this->settings->is_feature_enabled( 'cod_fee' ) ) {
			( new WSCF_COD_Fee( $this->settings ) )->register_hooks();
		}

		if ( $this->settings->is_feature_enabled( 'remove_additional_information_tab' ) ) {
			( new WSCF_Remove_Additional_Information_Tab( $this->settings ) )->register_hooks();
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
		echo esc_html__( 'WooCommerce SK/CZ Functions requires the WooCommerce plugin to be active.', 'woocommerce-sk-cz-functions' );
		echo '</p></div>';
	}
}
