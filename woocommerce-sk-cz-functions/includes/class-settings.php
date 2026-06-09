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
	 * Internal feature readiness flags.
	 *
	 * Set a feature to false to keep it unavailable in runtime and gray it out in settings.
	 *
	 * @return array<string, bool>
	 */
	public function get_feature_availability() {
		return array(
			'company_checkout_fields'           => true,
			'gdpr_checkbox'                     => true,
			'category_row'                      => true,
			'hide_shipping_when_free'           => true,
			'remove_additional_information_tab' => true,
			'move_additional_information_to_description' => true,
			'cod_fee'                           => true,
			'cod_fee_amount'                    => true,
			'cod_fee_label'                     => true,
			'checkout_button_text'              => true,
		);
	}

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
			'move_additional_information_to_description' => 'wscf_move_additional_information_to_description',
			'cod_fee'                           => 'wscf_cod_fee',
			'cod_fee_amount'                    => 'wscf_cod_fee_amount',
			'cod_fee_label'                     => 'wscf_cod_fee_label',
			'checkout_button_text'              => 'wscf_checkout_button_text',
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
			'move_additional_information_to_description' => 'no',
			'cod_fee'                           => 'no',
			'cod_fee_amount'                    => '0',
			'cod_fee_label'                     => '',
			'checkout_button_text'              => '',
		);
	}

	/**
	 * Get the default checkout button text.
	 *
	 * @return string
	 */
	public function get_default_checkout_button_text() {
		return __( 'Objednať s povinnosťou platby', 'woocommerce-sk-cz-functions' );
	}

	/**
	 * Get the option name used to mark custom checkout button text.
	 *
	 * @return string
	 */
	public function get_checkout_button_text_customized_option_name() {
		return 'wscf_checkout_button_text_customized';
	}

	/**
	 * Get the default COD fee label.
	 *
	 * @return string
	 */
	public function get_default_cod_fee_label() {
		return __( 'Cash on delivery fee', 'woocommerce-sk-cz-functions' );
	}

	/**
	 * Get the option name used to mark custom COD fee label text.
	 *
	 * @return string
	 */
	public function get_cod_fee_label_customized_option_name() {
		return 'wscf_cod_fee_label_customized';
	}

	/**
	 * Get all locale defaults that should not be treated as custom merchant text.
	 *
	 * @return array<int, string>
	 */
	public function get_cod_fee_label_default_values() {
		return array_unique(
			array(
				$this->get_default_cod_fee_label(),
				'Cash on delivery fee',
				'Poplatok za dobierku',
				'Poplatek za dobírku',
			)
		);
	}

	/**
	 * Check whether a COD fee label value is one of the translated defaults.
	 *
	 * @param string $fee_label Fee label.
	 * @return bool
	 */
	public function is_cod_fee_label_default_value( $fee_label ) {
		return in_array( trim( $fee_label ), $this->get_cod_fee_label_default_values(), true );
	}

	/**
	 * Check whether a COD fee label matches the active locale default.
	 *
	 * @param string $fee_label Fee label.
	 * @return bool
	 */
	public function is_current_cod_fee_label_default_value( $fee_label ) {
		return trim( $fee_label ) === $this->get_default_cod_fee_label();
	}

	/**
	 * Check whether the merchant saved custom COD fee label text.
	 *
	 * @return bool
	 */
	public function is_cod_fee_label_customized() {
		return 'yes' === get_option( $this->get_cod_fee_label_customized_option_name(), 'no' );
	}

	/**
	 * Get all locale defaults that should not be treated as custom merchant text.
	 *
	 * @return array<int, string>
	 */
	public function get_checkout_button_text_default_values() {
		return array_unique(
			array(
				$this->get_default_checkout_button_text(),
				'Objednať s povinnosťou platby',
				'Objednat s povinností platby',
			)
		);
	}

	/**
	 * Check whether a checkout button text value is one of the translated defaults.
	 *
	 * @param string $button_text Button text.
	 * @return bool
	 */
	public function is_checkout_button_text_default_value( $button_text ) {
		return in_array( trim( $button_text ), $this->get_checkout_button_text_default_values(), true );
	}

	/**
	 * Check whether a checkout button text value matches the active locale default.
	 *
	 * @param string $button_text Button text.
	 * @return bool
	 */
	public function is_current_checkout_button_text_default_value( $button_text ) {
		return trim( $button_text ) === $this->get_default_checkout_button_text();
	}

	/**
	 * Check whether the merchant saved custom checkout button text.
	 *
	 * @return bool
	 */
	public function is_checkout_button_text_customized() {
		return 'yes' === get_option( $this->get_checkout_button_text_customized_option_name(), 'no' );
	}

	/**
	 * Get checkout button text with translated default fallback.
	 *
	 * @return string
	 */
	public function get_checkout_button_text() {
		$option_names = $this->get_option_names();
		$button_text  = sanitize_text_field( get_option( $option_names['checkout_button_text'], '' ) );

		if ( '' === $button_text ) {
			return $this->get_default_checkout_button_text();
		}

		if ( $this->is_checkout_button_text_customized() ) {
			return $button_text;
		}

		if ( $this->is_checkout_button_text_default_value( $button_text ) ) {
			return $this->get_default_checkout_button_text();
		}

		return $button_text;
	}

	/**
	 * Get COD fee label with translated default fallback.
	 *
	 * @return string
	 */
	public function get_cod_fee_label() {
		$option_names = $this->get_option_names();
		$fee_label    = sanitize_text_field( get_option( $option_names['cod_fee_label'], '' ) );

		if ( '' === $fee_label ) {
			return $this->get_default_cod_fee_label();
		}

		if ( $this->is_cod_fee_label_customized() ) {
			return $fee_label;
		}

		if ( $this->is_cod_fee_label_default_value( $fee_label ) ) {
			return $this->get_default_cod_fee_label();
		}

		return $fee_label;
	}

	/**
	 * Check if a feature is internally available.
	 *
	 * @param string $feature_key Feature key.
	 * @return bool
	 */
	public function is_feature_available( $feature_key ) {
		$availability = $this->get_feature_availability();

		return isset( $availability[ $feature_key ] ) ? (bool) $availability[ $feature_key ] : false;
	}

	/**
	 * Check if feature is enabled.
	 *
	 * @param string $feature_key Feature key.
	 * @return bool
	 */
	public function is_setting_enabled( $feature_key ) {
		$option_names = $this->get_option_names();
		$defaults     = $this->get_defaults();

		if ( ! isset( $option_names[ $feature_key ], $defaults[ $feature_key ] ) ) {
			return false;
		}

		if ( ! $this->is_feature_available( $feature_key ) ) {
			return false;
		}

		return 'yes' === get_option( $option_names[ $feature_key ], $defaults[ $feature_key ] );
	}

	/**
	 * Check if a top-level feature is enabled.
	 *
	 * @param string $feature_key Feature key.
	 * @return bool
	 */
	public function is_feature_enabled( $feature_key ) {
		return $this->is_setting_enabled( $feature_key );
	}

	/**
	 * Get a stored setting value with default fallback.
	 *
	 * @param string $setting_key Setting key.
	 * @return string
	 */
	public function get_setting_value( $setting_key ) {
		if ( 'checkout_button_text' === $setting_key ) {
			return $this->get_checkout_button_text();
		}

		if ( 'cod_fee_label' === $setting_key ) {
			return $this->get_cod_fee_label();
		}

		$option_names = $this->get_option_names();
		$defaults     = $this->get_defaults();

		if ( ! isset( $option_names[ $setting_key ], $defaults[ $setting_key ] ) ) {
			return '';
		}

		$value = get_option( $option_names[ $setting_key ], $defaults[ $setting_key ] );

		return is_scalar( $value ) ? (string) $value : '';
	}

}
