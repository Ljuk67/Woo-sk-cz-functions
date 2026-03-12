<?php
/**
 * Company checkout fields feature.
 *
 * @package WooCommerce_SK_CZ_Functions
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WSCF_Company_Checkout_Fields {

	/**
	 * Register feature hooks.
	 *
	 * @return void
	 */
	public function register_hooks() {
		add_filter( 'woocommerce_checkout_fields', array( $this, 'register_checkout_fields' ), 20 );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'woocommerce_after_checkout_validation', array( $this, 'validate_company_fields' ), 10, 2 );
		add_action( 'woocommerce_checkout_create_order', array( $this, 'save_order_meta' ), 10, 2 );
	}

	/**
	 * Register company-related checkout fields.
	 *
	 * @param array<string, array<string, mixed>> $fields Checkout fields.
	 * @return array<string, array<string, mixed>>
	 */
	public function register_checkout_fields( $fields ) {
		if ( ! isset( $fields['billing'] ) || ! is_array( $fields['billing'] ) ) {
			return $fields;
		}

		$fields['billing']['billing_wscf_is_company'] = array(
			'type'     => 'checkbox',
			'label'    => __( 'Company purchase (Company ID, Tax ID)', 'woocommerce-sk-cz-functions' ),
			'class'    => array( 'form-row-wide' ),
			'priority' => 25,
			'clear'    => false,
		);

		$fields['billing']['billing_ico'] = array(
			'type'        => 'text',
			'label'       => __( 'Company ID', 'woocommerce-sk-cz-functions' ),
			'required'    => false,
			'class'       => array( 'form-row-first', 'wsczf-company-field' ),
			'priority'    => 31,
			'autocomplete' => 'organization-title',
		);

		$fields['billing']['billing_dic'] = array(
			'type'        => 'text',
			'label'       => __( 'Tax ID', 'woocommerce-sk-cz-functions' ),
			'required'    => false,
			'class'       => array( 'form-row-last', 'wsczf-company-field' ),
			'priority'    => 32,
			'autocomplete' => 'off',
		);

		$fields['billing']['billing_ic_dph'] = array(
			'type'        => 'text',
			'label'       => __( 'VAT ID', 'woocommerce-sk-cz-functions' ),
			'required'    => false,
			'class'       => array( 'form-row-wide', 'wsczf-company-field' ),
			'priority'    => 33,
			'autocomplete' => 'off',
		);

		if ( isset( $fields['billing']['billing_company'] ) && is_array( $fields['billing']['billing_company'] ) ) {
			$fields['billing']['billing_company']['class'] = $this->append_css_class(
				$fields['billing']['billing_company'],
				'wsczf-company-field'
			);
		}

		return $fields;
	}

	/**
	 * Append class name to checkout field configuration.
	 *
	 * @param array<string, mixed> $field Field config.
	 * @param string               $class Class name.
	 * @return array<int, string>
	 */
	private function append_css_class( $field, $class ) {
		$current_classes = array();

		if ( isset( $field['class'] ) && is_array( $field['class'] ) ) {
			$current_classes = $field['class'];
		}

		$current_classes[] = $class;

		return array_values( array_unique( $current_classes ) );
	}

	/**
	 * Load checkout script for show/hide behavior.
	 *
	 * @return void
	 */
	public function enqueue_scripts() {
		if ( ! function_exists( 'is_checkout' ) || ! is_checkout() || is_order_received_page() ) {
			return;
		}

		wp_enqueue_script(
			'wsczf-company-checkout-fields',
			WSCF_PLUGIN_URL . 'assets/js/company-checkout-fields.js',
			array( 'jquery', 'wc-checkout' ),
			WSCF_VERSION,
			true
		);
	}

	/**
	 * Validate required company fields when company purchase is checked.
	 *
	 * @param array<string, mixed> $data   Posted checkout data.
	 * @param WP_Error             $errors Checkout errors object.
	 * @return void
	 */
	public function validate_company_fields( $data, $errors ) {
		if ( empty( $data['billing_wscf_is_company'] ) ) {
			return;
		}

		$company = isset( $data['billing_company'] ) ? trim( (string) $data['billing_company'] ) : '';
		$ico     = isset( $data['billing_ico'] ) ? trim( (string) $data['billing_ico'] ) : '';
		$dic     = isset( $data['billing_dic'] ) ? trim( (string) $data['billing_dic'] ) : '';

		if ( '' === $company ) {
				$errors->add(
					'wscf_company_required',
					__( 'For company purchases, enter the company name.', 'woocommerce-sk-cz-functions' )
				);
			}

		if ( '' === $ico ) {
				$errors->add(
					'wscf_ico_required',
					__( 'For company purchases, enter Company ID.', 'woocommerce-sk-cz-functions' )
				);
			}

		if ( '' === $dic ) {
				$errors->add(
					'wscf_dic_required',
					__( 'For company purchases, enter Tax ID.', 'woocommerce-sk-cz-functions' )
				);
			}
	}

	/**
	 * Save company fields into order meta.
	 *
	 * @param WC_Order             $order Order object.
	 * @param array<string, mixed> $data  Posted checkout data.
	 * @return void
	 */
	public function save_order_meta( $order, $data ) {
		$is_company = ! empty( $data['billing_wscf_is_company'] ) ? 'yes' : 'no';
		$ico        = '';
		$dic        = '';
		$ic_dph     = '';

		if ( 'yes' === $is_company ) {
			$ico    = $this->clean_text_value( $data, 'billing_ico' );
			$dic    = $this->clean_text_value( $data, 'billing_dic' );
			$ic_dph = $this->clean_text_value( $data, 'billing_ic_dph' );
		}

		$order->update_meta_data( '_wscf_is_company', $is_company );
		$order->update_meta_data( '_wscf_billing_ico', $ico );
		$order->update_meta_data( '_wscf_billing_dic', $dic );
		$order->update_meta_data( '_wscf_billing_ic_dph', $ic_dph );
	}

	/**
	 * Sanitize string value from checkout data.
	 *
	 * @param array<string, mixed> $data Posted checkout data.
	 * @param string               $key  Field key.
	 * @return string
	 */
	private function clean_text_value( $data, $key ) {
		if ( ! isset( $data[ $key ] ) ) {
			return '';
		}

		return sanitize_text_field( wp_unslash( (string) $data[ $key ] ) );
	}
}
