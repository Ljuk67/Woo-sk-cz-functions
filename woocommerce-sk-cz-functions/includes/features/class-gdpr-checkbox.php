<?php
/**
 * GDPR checkbox feature.
 *
 * @package WooCommerce_SK_CZ_Functions
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WSCF_GDPR_Checkbox {

	/**
	 * Register feature hooks.
	 *
	 * @return void
	 */
	public function register_hooks() {
		add_action( 'woocommerce_review_order_before_submit', array( $this, 'render_privacy_checkbox' ), 9 );
		add_action( 'woocommerce_checkout_process', array( $this, 'validate_privacy_checkbox' ) );
	}

	/**
	 * Render GDPR consent checkbox on checkout.
	 *
	 * @return void
	 */
	public function render_privacy_checkbox() {
		woocommerce_form_field(
			'privacy_policy',
			array(
				'type'        => 'checkbox',
				'class'       => array( 'form-row privacy' ),
				'label_class' => array( 'woocommerce-form__label woocommerce-form__label-for-checkbox checkbox' ),
				'input_class' => array( 'woocommerce-form__input woocommerce-form__input-checkbox input-checkbox' ),
				'required'    => true,
				'label'       => __( 'I have read the privacy policy and agree with it.', 'woocommerce-sk-cz-functions' ),
			)
		);
	}

	/**
	 * Validate GDPR consent checkbox.
	 *
	 * @return void
	 */
	public function validate_privacy_checkbox() {
		if ( empty( $_POST['privacy_policy'] ) ) {
			wc_add_notice(
				__( 'You must agree with the privacy policy to complete the order.', 'woocommerce-sk-cz-functions' ),
				'error'
			);
		}
	}
}
