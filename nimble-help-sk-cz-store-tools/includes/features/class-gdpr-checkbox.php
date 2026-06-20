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

		// phpcs:ignore WordPress.Security.NonceVerification.Missing -- Checkout POST data is read only after the WooCommerce checkout nonce is verified in the same condition.
		if ( ! $this->has_checkout_post_nonce() || empty( $_POST['privacy_policy'] ) ) {
			wc_add_notice(
				$this->get_validation_message(),
				'error'
			);
		}
	}

	/**
	 * Get the classic checkout label with privacy policy link when available.
	 *
	 * @return string
	 */
	private function get_classic_checkbox_label() {
		$privacy_policy_url = $this->get_privacy_policy_url();

		if ( '' === $privacy_policy_url ) {
			return __( 'I have read the privacy policy and agree with it.', 'nimble-help-sk-cz-store-tools' );
		}

		return sprintf(
			'%1$s <a href="%2$s" target="_blank" rel="noopener">%3$s</a>',
			esc_html__( 'I have read the privacy policy and agree with it.', 'nimble-help-sk-cz-store-tools' ),
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
		return __( 'You must agree with the privacy policy to complete the order.', 'nimble-help-sk-cz-store-tools' );
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

		return __( 'Privacy Policy', 'nimble-help-sk-cz-store-tools' );
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

		$request_uri = sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) );

		return false !== strpos( $request_uri, '/wc/store/' ) && false !== strpos( $request_uri, '/checkout' );
	}

	/**
	 * Verify the WooCommerce checkout nonce before reading classic checkout POST data.
	 *
	 * @return bool
	 */
	private function has_checkout_post_nonce() {
		$nonce = '';

		// phpcs:ignore WordPress.Security.NonceVerification.Missing -- This branch checks whether a nonce value is available for verification.
		if ( isset( $_POST['woocommerce-process-checkout-nonce'] ) ) {
			// phpcs:ignore WordPress.Security.NonceVerification.Missing -- This line reads the nonce value for verification.
			$nonce = sanitize_text_field( wp_unslash( $_POST['woocommerce-process-checkout-nonce'] ) );
		// phpcs:ignore WordPress.Security.NonceVerification.Missing -- This branch checks whether a fallback nonce value is available for verification.
		} elseif ( isset( $_POST['_wpnonce'] ) ) {
			// phpcs:ignore WordPress.Security.NonceVerification.Missing -- This line reads the nonce value for verification.
			$nonce = sanitize_text_field( wp_unslash( $_POST['_wpnonce'] ) );
		}

		return '' !== $nonce && wp_verify_nonce( $nonce, 'woocommerce-process_checkout' );
	}
}
