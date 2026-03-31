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
