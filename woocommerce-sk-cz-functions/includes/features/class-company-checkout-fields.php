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
		add_action( 'woocommerce_after_checkout_form', array( $this, 'render_toggle_script' ), 20 );
		add_action( 'woocommerce_checkout_process', array( $this, 'validate_company_checkout_fields' ) );
		add_action( 'woocommerce_checkout_create_order', array( $this, 'save_company_checkout_fields' ), 20, 2 );
		add_action( 'woocommerce_admin_order_data_after_billing_address', array( $this, 'show_company_fields_in_admin_order' ), 10, 1 );
		add_filter( 'woocommerce_email_order_meta_fields', array( $this, 'add_company_fields_to_emails' ), 10, 3 );
	}

	/**
	 * Add company-related fields to checkout.
	 *
	 * @param array<string, array<string, mixed>> $fields Checkout fields.
	 * @return array<string, array<string, mixed>>
	 */
	public function register_checkout_fields( $fields ) {
		if ( ! isset( $fields['billing'] ) || ! is_array( $fields['billing'] ) ) {
			return $fields;
		}

		$fields['billing']['billing_buying_as_company'] = array(
			'type'     => 'checkbox',
			'label'    => __( 'Company purchase - Company ID, Tax ID', 'woocommerce-sk-cz-functions' ),
			'required' => false,
			'class'    => array( 'form-row-wide' ),
			'clear'    => true,
			'priority' => 5,
		);

		if ( isset( $fields['billing']['billing_company'] ) && is_array( $fields['billing']['billing_company'] ) ) {
			$fields['billing']['billing_company']['required'] = false;
			$fields['billing']['billing_company']['priority'] = 6;
			$fields['billing']['billing_company']['class']    = array( 'form-row-wide', 'nimble-company-field' );
		}

		$fields['billing']['billing_ico'] = array(
			'type'        => 'text',
			'label'       => __( 'Company ID', 'woocommerce-sk-cz-functions' ),
			'placeholder' => __( 'Company ID', 'woocommerce-sk-cz-functions' ),
			'required'    => false,
			'class'       => array( 'form-row-first', 'nimble-company-field' ),
			'clear'       => false,
			'priority'    => 7,
		);

		$fields['billing']['billing_dic'] = array(
			'type'        => 'text',
			'label'       => __( 'Tax ID', 'woocommerce-sk-cz-functions' ),
			'placeholder' => __( 'Tax ID', 'woocommerce-sk-cz-functions' ),
			'required'    => false,
			'class'       => array( 'form-row-last', 'nimble-company-field' ),
			'clear'       => true,
			'priority'    => 8,
		);

		$fields['billing']['billing_ic_dph'] = array(
			'type'        => 'text',
			'label'       => __( 'VAT ID', 'woocommerce-sk-cz-functions' ),
			'placeholder' => __( 'VAT ID', 'woocommerce-sk-cz-functions' ),
			'required'    => false,
			'class'       => array( 'form-row-wide', 'nimble-company-field' ),
			'clear'       => true,
			'priority'    => 9,
		);

		return $fields;
	}

	/**
	 * Render inline checkout script for company field visibility.
	 *
	 * @return void
	 */
	public function render_toggle_script() {
		if ( ! function_exists( 'is_checkout' ) || ! is_checkout() || is_order_received_page() ) {
			return;
		}
		?>
		<script type="text/javascript">
			jQuery(function($) {
				function nimbleToggleCompanyFields() {
					var isCompany = $('#billing_buying_as_company').is(':checked');

					if (isCompany) {
						$('#billing_company_field').show();
						$('#billing_ico_field').show();
						$('#billing_dic_field').show();
						$('#billing_ic_dph_field').show();
					} else {
						$('#billing_company_field').hide();
						$('#billing_ico_field').hide();
						$('#billing_dic_field').hide();
						$('#billing_ic_dph_field').hide();
					}
				}

				nimbleToggleCompanyFields();

				$(document.body).on('change', '#billing_buying_as_company', function() {
					nimbleToggleCompanyFields();
				});

				$(document.body).on('updated_checkout', function() {
					nimbleToggleCompanyFields();
				});
			});
		</script>
		<?php
	}

	/**
	 * Validate company fields when company purchase is checked.
	 *
	 * @return void
	 */
	public function validate_company_checkout_fields() {
		$is_company = ! empty( $_POST['billing_buying_as_company'] );

		if ( ! $is_company ) {
			return;
		}

		$company = isset( $_POST['billing_company'] ) ? trim( wp_unslash( $_POST['billing_company'] ) ) : '';
		$ico     = isset( $_POST['billing_ico'] ) ? trim( wp_unslash( $_POST['billing_ico'] ) ) : '';
		$dic     = isset( $_POST['billing_dic'] ) ? trim( wp_unslash( $_POST['billing_dic'] ) ) : '';

		if ( '' === $company ) {
			wc_add_notice( __( 'Please enter the company name.', 'woocommerce-sk-cz-functions' ), 'error' );
		}

		if ( '' === $ico ) {
			wc_add_notice( __( 'Please enter Company ID.', 'woocommerce-sk-cz-functions' ), 'error' );
		}

		if ( '' === $dic ) {
			wc_add_notice( __( 'Please enter Tax ID.', 'woocommerce-sk-cz-functions' ), 'error' );
		}
	}

	/**
	 * Save company checkout fields to order meta.
	 *
	 * @param WC_Order             $order Order object.
	 * @param array<string, mixed> $data  Posted checkout data.
	 * @return void
	 */
	public function save_company_checkout_fields( $order, $data ) {
		unset( $data );

		$order->update_meta_data(
			'_billing_buying_as_company',
			! empty( $_POST['billing_buying_as_company'] ) ? '1' : '0'
		);

		if ( isset( $_POST['billing_ico'] ) ) {
			$order->update_meta_data( '_billing_ico', sanitize_text_field( wp_unslash( $_POST['billing_ico'] ) ) );
		}

		if ( isset( $_POST['billing_dic'] ) ) {
			$order->update_meta_data( '_billing_dic', sanitize_text_field( wp_unslash( $_POST['billing_dic'] ) ) );
		}

		if ( isset( $_POST['billing_ic_dph'] ) ) {
			$order->update_meta_data( '_billing_ic_dph', sanitize_text_field( wp_unslash( $_POST['billing_ic_dph'] ) ) );
		}
	}

	/**
	 * Show company data in admin order screen.
	 *
	 * @param WC_Order $order Order object.
	 * @return void
	 */
	public function show_company_fields_in_admin_order( $order ) {
		$is_company = $order->get_meta( '_billing_buying_as_company' );
		$ico        = $order->get_meta( '_billing_ico' );
		$dic        = $order->get_meta( '_billing_dic' );
		$ic_dph     = $order->get_meta( '_billing_ic_dph' );

		if ( '1' !== $is_company ) {
			return;
		}

		echo '<p><strong>' . esc_html__( 'Company data', 'woocommerce-sk-cz-functions' ) . '</strong></p>';

		if ( ! empty( $ico ) ) {
			echo '<p><strong>' . esc_html__( 'Company ID:', 'woocommerce-sk-cz-functions' ) . '</strong> ' . esc_html( $ico ) . '</p>';
		}

		if ( ! empty( $dic ) ) {
			echo '<p><strong>' . esc_html__( 'Tax ID:', 'woocommerce-sk-cz-functions' ) . '</strong> ' . esc_html( $dic ) . '</p>';
		}

		if ( ! empty( $ic_dph ) ) {
			echo '<p><strong>' . esc_html__( 'VAT ID:', 'woocommerce-sk-cz-functions' ) . '</strong> ' . esc_html( $ic_dph ) . '</p>';
		}
	}

	/**
	 * Add company data to WooCommerce emails.
	 *
	 * @param array<string, array<string, string>> $fields        Existing email fields.
	 * @param bool                                 $sent_to_admin Whether the email is sent to admin.
	 * @param WC_Order                             $order         Order object.
	 * @return array<string, array<string, string>>
	 */
	public function add_company_fields_to_emails( $fields, $sent_to_admin, $order ) {
		unset( $sent_to_admin );

		if ( '1' !== $order->get_meta( '_billing_buying_as_company' ) ) {
			return $fields;
		}

		$ico    = $order->get_meta( '_billing_ico' );
		$dic    = $order->get_meta( '_billing_dic' );
		$ic_dph = $order->get_meta( '_billing_ic_dph' );

		if ( ! empty( $ico ) ) {
			$fields['billing_ico'] = array(
				'label' => __( 'Company ID', 'woocommerce-sk-cz-functions' ),
				'value' => $ico,
			);
		}

		if ( ! empty( $dic ) ) {
			$fields['billing_dic'] = array(
				'label' => __( 'Tax ID', 'woocommerce-sk-cz-functions' ),
				'value' => $dic,
			);
		}

		if ( ! empty( $ic_dph ) ) {
			$fields['billing_ic_dph'] = array(
				'label' => __( 'VAT ID', 'woocommerce-sk-cz-functions' ),
				'value' => $ic_dph,
			);
		}

		return $fields;
	}
}
