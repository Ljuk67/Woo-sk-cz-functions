<?php
/**
 * Basic settings helper for feature flags.
 *
 * @package WooCommerce_SK_CZ_Functions
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WSCF_Settings {

	/**
	 * Map feature keys to individual option names.
	 *
	 * @return array<string, string>
	 */
	public function get_option_names() {
		return array(
			'company_checkout_fields'           => 'wscf_company_checkout_fields',
			'gdpr_checkbox'                     => 'wscf_gdpr_checkbox',
			'category_row'                      => 'wscf_category_row',
			'hide_shipping_when_free'           => 'wscf_hide_shipping_when_free',
			'remove_additional_information_tab' => 'wscf_remove_additional_information_tab',
		);
	}


	/**
	 * Default settings.
	 *
	 * @return array<string, string>
	 */
	public function get_defaults() {
		return array(
			'company_checkout_fields'           => 'no',
			'gdpr_checkbox'                     => 'no',
			'category_row'                      => 'no',
			'hide_shipping_when_free'           => 'no',
			'remove_additional_information_tab' => 'no',
		);
	}
	/**
	 * Migrate the legacy array option to individual WooCommerce options.
	 *
	 * @return void
	 */
	public function maybe_migrate_legacy_settings() {
		$legacy_settings = get_option( WSCF_OPTION_KEY, null );

		if ( ! is_array( $legacy_settings ) ) {
			return;
		}

		$option_names = $this->get_option_names();
		$defaults     = $this->get_defaults();

		foreach ( $option_names as $feature_key => $option_name ) {
			if ( false !== get_option( $option_name, false ) ) {
				continue;
			}

			$legacy_value = ! empty( $legacy_settings[ $feature_key ] ) ? 'yes' : $defaults[ $feature_key ];
			update_option( $option_name, $legacy_value );
		}

		delete_option( WSCF_OPTION_KEY );
	}

	/**
	 * Register option key for future admin settings page.
	 *
	 * @return void
	 */
	public function register_settings() {
		register_setting(
			'wscf_settings_group',
			WSCF_OPTION_KEY,
			array(
				'type'              => 'array',
				'sanitize_callback' => array( $this, 'sanitize_settings' ),
				'default'           => $this->get_defaults(),
			)
		);
	}

	/**
	 * Sanitize settings values.
	 *
	 * @param array<string, mixed> $settings Raw settings values.
	 * @return array<string, int>
	 */
	public function sanitize_settings( $settings ) {
		$defaults = $this->get_defaults();
		$clean    = array();

		foreach ( $defaults as $feature_key => $default_value ) {
			$clean[ $feature_key ] = isset( $settings[ $feature_key ] ) ? 1 : 0;
		}

		return $clean;
	}


	/**
	 * Get merged settings (saved + defaults).
	 *
	 * @return array<string, int>
	 */
	public function get_settings() {
		$saved_settings = get_option( WSCF_OPTION_KEY, array() );
		$defaults       = $this->get_defaults();

		if ( ! is_array( $saved_settings ) ) {
			$saved_settings = array();
		}

		return wp_parse_args( $saved_settings, $defaults );
	}

	/**
	 * Check if feature is enabled.
	 *
	 * @param string $feature_key Feature key.
	 * @return bool
	 */
	public function is_feature_enabled( $feature_key ) {
		$option_names = $this->get_option_names();
		$defaults     = $this->get_defaults();

		if ( ! isset( $option_names[ $feature_key ], $defaults[ $feature_key ] ) ) {
			return false;
		}

		return 'yes' === get_option( $option_names[ $feature_key ], $defaults[ $feature_key ] );
	}

}
