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
	 * Block checkout field identifier.
	 *
	 * @var string
	 */
	private $block_field_id = 'woocommerce-sk-cz-functions/privacy-policy';

	/**
	 * Register feature hooks.
	 *
	 * @return void
	 */
	public function register_hooks() {
		add_action( 'woocommerce_review_order_before_submit', array( $this, 'render_privacy_checkbox' ), 9 );
		add_action( 'woocommerce_checkout_process', array( $this, 'validate_privacy_checkbox' ) );
		add_action( 'woocommerce_init', array( $this, 'register_block_checkout_field' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_block_checkout_assets' ) );
	}

	/**
	 * Register GDPR consent field for the Checkout Block.
	 *
	 * @return void
	 */
	public function register_block_checkout_field() {
		if ( ! function_exists( 'woocommerce_register_additional_checkout_field' ) ) {
			return;
		}

		woocommerce_register_additional_checkout_field(
			array(
				'id'            => $this->block_field_id,
				'label'         => $this->get_checkbox_label(),
				'location'      => 'order',
				'type'          => 'checkbox',
				'required'      => true,
				'error_message' => $this->get_validation_message(),
				'attributes'    => array(
					'data-wscf-privacy-policy-field'     => '1',
					'data-wscf-privacy-policy-url'       => $this->get_privacy_policy_url(),
					'data-wscf-privacy-policy-link-text' => $this->get_privacy_policy_link_text(),
				),
			)
		);
	}

	/**
	 * Enqueue block checkout enhancement script for privacy policy link.
	 *
	 * @return void
	 */
	public function enqueue_block_checkout_assets() {
		if ( ! function_exists( 'is_checkout' ) || ! is_checkout() || is_order_received_page() ) {
			return;
		}

		wp_enqueue_script(
			'wscf-gdpr-checkbox-block',
			WSCF_PLUGIN_URL . 'assets/js/gdpr-checkbox-block.js',
			array(),
			WSCF_VERSION,
			true
		);
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
				'label'       => $this->get_classic_checkbox_label(),
			)
		);
	}

	/**
	 * Validate GDPR consent checkbox.
	 *
	 * @return void
	 */
	public function validate_privacy_checkbox() {
		if ( $this->is_store_api_checkout_request() ) {
			return;
		}

		if ( empty( $_POST['privacy_policy'] ) ) {
			wc_add_notice(
				$this->get_validation_message(),
				'error'
			);
		}
	}

	/**
	 * Get the consent label shared by classic and block checkout.
	 *
	 * @return string
	 */
	private function get_checkbox_label() {
		return __( 'I have read the privacy policy and agree with it.', 'woocommerce-sk-cz-functions' );

	}

	/**
	 * Get the classic checkout label with privacy policy link when available.
	 *
	 * @return string
	 */
	private function get_classic_checkbox_label() {
		$privacy_policy_url = $this->get_privacy_policy_url();

		if ( '' === $privacy_policy_url ) {
			return $this->get_checkbox_label();
		}

		return sprintf(
			'%1$s <a href="%2$s" target="_blank" rel="noopener">%3$s</a>',
			esc_html( $this->get_checkbox_label() ),
			esc_url( $privacy_policy_url ),
			esc_html( $this->get_privacy_policy_link_text() )
		);
	}

	/**
	 * Get the validation message shared by classic and block checkout.
	 *
	 * @return string
	 */
	private function get_validation_message() {
		return __( 'You must agree with the privacy policy to complete the order.', 'woocommerce-sk-cz-functions' );
	}

	/**
	 * Get the privacy policy page URL.
	 *
	 * @return string
	 */
	private function get_privacy_policy_url() {
		if ( ! function_exists( 'get_privacy_policy_url' ) ) {
			return '';
		}

		return get_privacy_policy_url();
	}

	/**
	 * Get the privacy policy link text.
	 *
	 * @return string
	 */
	private function get_privacy_policy_link_text() {
		$privacy_policy_page_id = (int) get_option( 'wp_page_for_privacy_policy' );

		if ( $privacy_policy_page_id > 0 ) {
			$page_title = get_the_title( $privacy_policy_page_id );

			if ( is_string( $page_title ) && '' !== $page_title ) {
				return $page_title;
			}
		}

		return __( 'Privacy Policy', 'woocommerce-sk-cz-functions' );
	}

	/**
	 * Detect Store API checkout requests used by the Checkout Block.
	 *
	 * @return bool
	 */
	private function is_store_api_checkout_request() {
		if ( ! defined( 'REST_REQUEST' ) || ! REST_REQUEST ) {
			return false;
		}

		if ( empty( $_SERVER['REQUEST_URI'] ) ) {
			return false;
		}

		$request_uri = wp_unslash( $_SERVER['REQUEST_URI'] );

		return false !== strpos( $request_uri, '/wc/store/' ) && false !== strpos( $request_uri, '/checkout' );
	}
}
