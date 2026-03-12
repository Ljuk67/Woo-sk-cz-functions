<?php
/**
 * Basic settings helper for feature flags.
 *
 * @package WooCommerce_SK_CZ_Funkcie
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WSCZF_Settings {

	/**
	 * Default settings.
	 *
	 * @return array<string, int>
	 */
	public function get_defaults() {
		return array(
			'company_checkout_fields' => 1,
			'gdpr_checkbox'           => 1,
			'category_row'            => 1,
		);
	}

	/**
	 * Register option key for future admin settings page.
	 *
	 * @return void
	 */
	public function register_settings() {
		register_setting(
			'wsczf_settings_group',
			WSCZF_OPTION_KEY,
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
		$saved_settings = get_option( WSCZF_OPTION_KEY, array() );
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
		$settings = $this->get_settings();

		return ! empty( $settings[ $feature_key ] );
	}
}
