<?php
/**
 * WooCommerce settings tab integration.
 *
 * @package WooCommerce_SK_CZ_Functions
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WSCF_WC_Settings_Tab {

	/**
	 * Register WooCommerce settings tab hooks.
	 *
	 * @return void
	 */
	public function register_hooks() {
		add_filter( 'woocommerce_settings_tabs_array', array( $this, 'add_tab' ), 50 );
		add_action( 'woocommerce_settings_tabs_wscf', array( $this, 'render_tab' ) );
		add_action( 'woocommerce_update_options_wscf', array( $this, 'save_tab' ) );
	}

	/**
	 * Add plugin tab under WooCommerce settings.
	 *
	 * @param array<string, string> $tabs Existing WooCommerce tabs.
	 * @return array<string, string>
	 */
	public function add_tab( $tabs ) {
		$tabs['wscf'] = __( 'WooCommerce SK/CZ', 'woocommerce-sk-cz-functions' );
		return $tabs;
	}

	/**
	 * Render tab fields.
	 *
	 * @return void
	 */
	public function render_tab() {
		woocommerce_admin_fields( $this->get_settings() );
	}

	/**
	 * Save settings to plugin array option.
	 *
	 * @return void
	 */
	public function save_tab() {
		$settings_service = new WSCF_Settings();
		$raw_settings     = array();

		if ( isset( $_POST[ WSCF_OPTION_KEY ] ) && is_array( $_POST[ WSCF_OPTION_KEY ] ) ) {
			$raw_settings = wp_unslash( $_POST[ WSCF_OPTION_KEY ] );
		}

		$clean_settings = $settings_service->sanitize_settings( $raw_settings );
		update_option( WSCF_OPTION_KEY, $clean_settings );
	}

	/**
	 * WooCommerce settings field definitions.
	 *
	 * @return array<int, array<string, mixed>>
	 */
	public function get_settings() {
		return array(
			array(
				'title' => __( 'WooCommerce SK/CZ', 'woocommerce-sk-cz-functions' ),
				'type'  => 'title',
				'id'    => 'wscf_settings_section',
			),
			array(
				'title'   => __( 'Enable company checkout fields', 'woocommerce-sk-cz-functions' ),
				'desc'    => __( 'Adds company purchase checkbox and business fields at checkout.', 'woocommerce-sk-cz-functions' ),
				'id'      => WSCF_OPTION_KEY . '[company_checkout_fields]',
				'type'    => 'checkbox',
				'default' => 'yes',
			),
			array(
				'title'   => __( 'Enable GDPR checkbox in checkout', 'woocommerce-sk-cz-functions' ),
				'desc'    => __( 'Adds a required privacy-policy consent checkbox at checkout.', 'woocommerce-sk-cz-functions' ),
				'id'      => WSCF_OPTION_KEY . '[gdpr_checkbox]',
				'type'    => 'checkbox',
				'default' => 'yes',
			),
			array(
				'title'   => __( 'Enable child category row on archives', 'woocommerce-sk-cz-functions' ),
				'desc'    => __( 'Displays child categories above products on category archive pages.', 'woocommerce-sk-cz-functions' ),
				'id'      => WSCF_OPTION_KEY . '[category_row]',
				'type'    => 'checkbox',
				'default' => 'yes',
			),
			array(
				'title'   => __( 'Hide paid shipping when free shipping exists', 'woocommerce-sk-cz-functions' ),
				'desc'    => __( 'Shows only free shipping rates when free shipping is available.', 'woocommerce-sk-cz-functions' ),
				'id'      => WSCF_OPTION_KEY . '[hide_shipping_when_free]',
				'type'    => 'checkbox',
				'default' => 'yes',
			),
			array(
				'title'   => __( 'Hide Additional Information tab on products', 'woocommerce-sk-cz-functions' ),
				'desc'    => __( 'Removes the Additional Information tab from single product pages.', 'woocommerce-sk-cz-functions' ),
				'id'      => WSCF_OPTION_KEY . '[remove_additional_information_tab]',
				'type'    => 'checkbox',
				'default' => 'yes',
			),
			array(
				'type' => 'sectionend',
				'id'   => 'wscf_settings_section',
			),
		);
	}
}
