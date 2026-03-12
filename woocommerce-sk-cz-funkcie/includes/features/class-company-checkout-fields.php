<?php
/**
 * Company checkout fields feature.
 *
 * @package WooCommerce_SK_CZ_Funkcie
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WSCZF_Company_Checkout_Fields {

	/**
	 * Register feature hooks.
	 *
	 * @return void
	 */
	public function register_hooks() {
		// Phase 1 TODO:
		// - Add "Nakup na firmu?" toggle at checkout.
		// - Show/hide ICO, DIC, IC DPH, company name, company address.
		// - Validate and store values in order meta.
	}
}
