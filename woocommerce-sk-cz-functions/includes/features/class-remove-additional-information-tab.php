<?php
/**
 * Remove additional information product tab.
 *
 * @package WooCommerce_SK_CZ_Functions
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WSCF_Remove_Additional_Information_Tab {

	/**
	 * Register feature hooks.
	 *
	 * @return void
	 */
	public function register_hooks() {
		add_filter( 'woocommerce_product_tabs', array( $this, 'filter_product_tabs' ), 100, 1 );
	}

	/**
	 * Remove "Additional information" tab from single product page.
	 *
	 * @param array<string, array<string, mixed>> $tabs Product tabs array.
	 * @return array<string, array<string, mixed>>
	 */
	public function filter_product_tabs( $tabs ) {
		if ( isset( $tabs['additional_information'] ) ) {
			unset( $tabs['additional_information'] );
		}

		return $tabs;
	}
}
