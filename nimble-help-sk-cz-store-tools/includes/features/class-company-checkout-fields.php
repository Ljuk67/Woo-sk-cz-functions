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
	 * Block checkout field IDs.
	 *
	 * @var array<string, string>
	 */
	private $block_field_ids = array(
		'buying_as_company' => 'nimble-help-sk-cz-store-tools/buying-as-company',
		'company_name'      => 'nimble-help-sk-cz-store-tools/company-name',
		'company_id'        => 'nimble-help-sk-cz-store-tools/company-id',
		'tax_id'            => 'nimble-help-sk-cz-store-tools/tax-id',
		'vat_id'            => 'nimble-help-sk-cz-store-tools/vat-id',
	);

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
		add_action( 'woocommerce_init', array( $this, 'register_block_checkout_fields' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_block_checkout_assets' ) );
		add_action( 'woocommerce_blocks_validate_location_other_fields', array( $this, 'validate_block_company_checkout_fields' ), 10, 3 );
		add_action( 'woocommerce_store_api_checkout_update_order_from_request', array( $this, 'sync_block_company_order_meta_from_request' ), 20, 2 );
		add_action( 'woocommerce_store_api_checkout_update_order_meta', array( $this, 'cleanup_block_company_order_meta' ), 20, 2 );
		add_action( 'woocommerce_set_additional_field_value', array( $this, 'sync_block_field_to_order_meta' ), 10, 4 );
		add_action( 'woocommerce_admin_order_data_after_billing_address', array( $this, 'show_company_fields_in_admin_order' ), 10, 1 );
		add_action( 'woocommerce_order_details_after_order_table', array( $this, 'show_company_fields_in_order_details' ), 20, 1 );
		add_action( 'woocommerce_email_customer_details', array( $this, 'show_company_fields_in_email_additional_information' ), 31, 4 );
	}

	/**
	 * Register company fields for the Checkout Block.
	 *
	 * @return void
	 */
	public function register_block_checkout_fields() {
		if ( ! function_exists( 'woocommerce_register_additional_checkout_field' ) ) {
			return;
		}

		woocommerce_register_additional_checkout_field(
			array(
				'id'       => $this->block_field_ids['buying_as_company'],
				'label'    => __( 'Company purchase - Company ID, Tax ID', 'nimble-help-sk-cz-store-tools' ),
				'location' => 'order',
				'type'     => 'checkbox',
				'attributes' => array(
					'data-wscf-company-toggle' => '1',
				),
			)
		);

		woocommerce_register_additional_checkout_field(
			array(
				'id'          => $this->block_field_ids['company_name'],
				'label'       => __( 'Company name', 'nimble-help-sk-cz-store-tools' ),
				'location'    => 'order',
				'type'        => 'text',
				'attributes'  => $this->get_block_company_field_attributes( true ),
			)
		);

		woocommerce_register_additional_checkout_field(
			array(
				'id'          => $this->block_field_ids['company_id'],
				'label'       => __( 'Company ID', 'nimble-help-sk-cz-store-tools' ),
				'location'    => 'order',
				'type'        => 'text',
				'attributes'  => $this->get_block_company_field_attributes( true ),
			)
		);

		woocommerce_register_additional_checkout_field(
			array(
				'id'          => $this->block_field_ids['tax_id'],
				'label'       => __( 'Tax ID', 'nimble-help-sk-cz-store-tools' ),
				'location'    => 'order',
				'type'        => 'text',
				'attributes'  => $this->get_block_company_field_attributes( true ),
			)
		);

		woocommerce_register_additional_checkout_field(
			array(
				'id'          => $this->block_field_ids['vat_id'],
				'label'       => __( 'VAT ID', 'nimble-help-sk-cz-store-tools' ),
				'location'    => 'order',
				'type'        => 'text',
				'attributes'  => $this->get_block_company_field_attributes( false ),
			)
		);
	}

	/**
	 * Enqueue block checkout enhancement script for company field visibility.
	 *
	 * @return void
	 */
	public function enqueue_block_checkout_assets() {
		$script_path = WSCF_PLUGIN_PATH . 'assets/js/company-checkout-fields-block.js';

		if ( ! function_exists( 'is_checkout' ) || ! is_checkout() || is_order_received_page() || ! file_exists( $script_path ) ) {
			return;
		}

		wp_enqueue_script(
			'wscf-company-checkout-fields-block',
			WSCF_PLUGIN_URL . 'assets/js/company-checkout-fields-block.js',
			array(),
			(string) filemtime( $script_path ),
			true
		);
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
			'label'    => __( 'Company purchase - Company ID, Tax ID', 'nimble-help-sk-cz-store-tools' ),
			'required' => false,
			'class'    => array( 'form-row-wide' ),
			'clear'    => true,
			'priority' => 5,
		);

		$fields['billing']['billing_company'] = wp_parse_args(
			isset( $fields['billing']['billing_company'] ) && is_array( $fields['billing']['billing_company'] )
				? $fields['billing']['billing_company']
				: array(),
			array(
				'type'        => 'text',
				'label'       => __( 'Company name', 'nimble-help-sk-cz-store-tools' ),
				'placeholder' => __( 'Company name', 'nimble-help-sk-cz-store-tools' ),
				'required'    => false,
				'class'       => array( 'form-row-wide', 'nimble-company-field' ),
				'clear'       => true,
				'priority'    => 6,
			)
		);

		$fields['billing']['billing_ico'] = array(
			'type'        => 'text',
			'label'       => __( 'Company ID', 'nimble-help-sk-cz-store-tools' ),
			'placeholder' => __( 'Company ID', 'nimble-help-sk-cz-store-tools' ),
			'required'    => false,
			'class'       => array( 'form-row-first', 'nimble-company-field' ),
			'clear'       => false,
			'priority'    => 7,
		);

		$fields['billing']['billing_dic'] = array(
			'type'        => 'text',
			'label'       => __( 'Tax ID', 'nimble-help-sk-cz-store-tools' ),
			'placeholder' => __( 'Tax ID', 'nimble-help-sk-cz-store-tools' ),
			'required'    => false,
			'class'       => array( 'form-row-last', 'nimble-company-field' ),
			'clear'       => true,
			'priority'    => 8,
		);

		$fields['billing']['billing_ic_dph'] = array(
			'type'        => 'text',
			'label'       => __( 'VAT ID', 'nimble-help-sk-cz-store-tools' ),
			'placeholder' => __( 'VAT ID', 'nimble-help-sk-cz-store-tools' ),
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
		if ( $this->is_store_api_checkout_request() ) {
			return;
		}

		$is_company = $this->get_posted_bool( 'billing_buying_as_company' );

		if ( ! $is_company ) {
			return;
		}

		$company = $this->get_posted_text( 'billing_company' );
		$ico     = $this->get_posted_text( 'billing_ico' );
		$dic     = $this->get_posted_text( 'billing_dic' );

		if ( '' === $company ) {
			wc_add_notice( __( 'Please enter the company name.', 'nimble-help-sk-cz-store-tools' ), 'error' );
		}

		if ( '' === $ico ) {
			wc_add_notice( __( 'Please enter Company ID.', 'nimble-help-sk-cz-store-tools' ), 'error' );
		}

		if ( '' === $dic ) {
			wc_add_notice( __( 'Please enter Tax ID.', 'nimble-help-sk-cz-store-tools' ), 'error' );
		}
	}

	/**
	 * Validate block checkout company fields when company purchase is checked.
	 *
	 * @param WP_Error             $errors Validation errors.
	 * @param array<string, mixed> $fields Submitted order-location block fields.
	 * @param string              $group  Additional field group.
	 * @return void
	 */
	public function validate_block_company_checkout_fields( $errors, $fields, $group ) {
		if ( 'other' !== $group ) {
			return;
		}

		$is_company = isset( $fields[ $this->block_field_ids['buying_as_company'] ] )
			&& $this->get_bool_value( $fields[ $this->block_field_ids['buying_as_company'] ] );

		if ( ! $is_company ) {
			return;
		}

		$company = $this->get_block_field_value( $fields, 'company_name' );
		$ico     = $this->get_block_field_value( $fields, 'company_id' );
		$dic     = $this->get_block_field_value( $fields, 'tax_id' );

		if ( '' === $company ) {
			$errors->add( 'wscf_missing_company_name', __( 'Please enter the company name.', 'nimble-help-sk-cz-store-tools' ) );
		}

		if ( '' === $ico ) {
			$errors->add( 'wscf_missing_company_id', __( 'Please enter Company ID.', 'nimble-help-sk-cz-store-tools' ) );
		}

		if ( '' === $dic ) {
			$errors->add( 'wscf_missing_tax_id', __( 'Please enter Tax ID.', 'nimble-help-sk-cz-store-tools' ) );
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

		if ( $this->is_store_api_checkout_request() ) {
			return;
		}

		$order->update_meta_data(
			'_billing_buying_as_company',
			$this->get_posted_bool( 'billing_buying_as_company' ) ? '1' : '0'
		);

		if ( ! $this->get_posted_bool( 'billing_buying_as_company' ) ) {
			$order->update_meta_data( '_billing_ico', '' );
			$order->update_meta_data( '_billing_dic', '' );
			$order->update_meta_data( '_billing_ic_dph', '' );
			return;
		}

		$order->update_meta_data( '_billing_ico', $this->get_posted_text( 'billing_ico' ) );
		$order->update_meta_data( '_billing_dic', $this->get_posted_text( 'billing_dic' ) );
		$order->update_meta_data( '_billing_ic_dph', $this->get_posted_text( 'billing_ic_dph' ) );
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

		echo '<p><strong>' . esc_html__( 'Company data', 'nimble-help-sk-cz-store-tools' ) . '</strong></p>';

		if ( ! empty( $ico ) ) {
			echo '<p><strong>' . esc_html__( 'Company ID:', 'nimble-help-sk-cz-store-tools' ) . '</strong> ' . esc_html( $ico ) . '</p>';
		}

		if ( ! empty( $dic ) ) {
			echo '<p><strong>' . esc_html__( 'Tax ID:', 'nimble-help-sk-cz-store-tools' ) . '</strong> ' . esc_html( $dic ) . '</p>';
		}

		if ( ! empty( $ic_dph ) ) {
			echo '<p><strong>' . esc_html__( 'VAT ID:', 'nimble-help-sk-cz-store-tools' ) . '</strong> ' . esc_html( $ic_dph ) . '</p>';
		}
	}

	/**
	 * Show company data on customer order details and order confirmation pages.
	 *
	 * @param WC_Order $order Order object.
	 * @return void
	 */
	public function show_company_fields_in_order_details( $order ) {
		if ( ! $order instanceof WC_Order || '1' !== $order->get_meta( '_billing_buying_as_company' ) ) {
			return;
		}

		$company_details = $this->get_company_order_details( $order );

		if ( empty( $company_details ) ) {
			return;
		}

		echo '<section class="woocommerce-customer-details wscf-company-details">';
		echo '<h2 class="woocommerce-column__title">' . esc_html__( 'Company data', 'nimble-help-sk-cz-store-tools' ) . '</h2>';
		echo '<table class="woocommerce-table woocommerce-table--custom-fields shop_table custom-fields">';
		echo '<tbody>';

		foreach ( $company_details as $company_detail ) {
			echo '<tr>';
			echo '<th>' . esc_html( $company_detail['label'] ) . '</th>';
			echo '<td>' . esc_html( $company_detail['value'] ) . '</td>';
			echo '</tr>';
		}

		echo '</tbody>';
		echo '</table>';
		echo '</section>';
	}

	/**
	 * Show company tax data in the email additional information area.
	 *
	 * @param WC_Order $order         Order object.
	 * @param bool     $sent_to_admin Whether the email is sent to admin.
	 * @param bool     $plain_text    Whether this is a plain text email.
	 * @param WC_Email $email         Email object.
	 * @return void
	 */
	public function show_company_fields_in_email_additional_information( $order, $sent_to_admin = false, $plain_text = false, $email = null ) {
		unset( $sent_to_admin, $email );

		if ( ! $order instanceof WC_Order || '1' !== $order->get_meta( '_billing_buying_as_company' ) ) {
			return;
		}

		$company_details = $this->get_company_email_details( $order );

		if ( empty( $company_details ) ) {
			return;
		}

		$show_heading = ! $this->has_block_company_purchase_email_section( $order );

		if ( $plain_text ) {
			if ( $show_heading ) {
				echo "\n" . esc_html( wc_strtoupper( __( 'Additional information', 'nimble-help-sk-cz-store-tools' ) ) ) . "\n\n";
			}

			foreach ( $company_details as $company_detail ) {
				printf( "%s: %s\n", wp_kses_post( $company_detail['label'] ), wp_kses_post( $company_detail['value'] ) );
			}

			return;
		}

		if ( $show_heading ) {
			echo '<h2>' . esc_html__( 'Additional information', 'nimble-help-sk-cz-store-tools' ) . '</h2>';
		}

		echo '<ul class="additional-fields" style="margin-bottom: 40px;">';

		foreach ( $company_details as $company_detail ) {
			printf( '<li><strong>%s</strong>: %s</li>', esc_html( $company_detail['label'] ), esc_html( $company_detail['value'] ) );
		}

		echo '</ul>';
	}

	/**
	 * Get company order details for customer-facing order output.
	 *
	 * @param WC_Order $order Order object.
	 * @return array<int, array{label: string, value: string}>
	 */
	private function get_company_order_details( $order ) {
		$details = array();
		$company = $order->get_billing_company();
		$ico     = $order->get_meta( '_billing_ico' );
		$dic     = $order->get_meta( '_billing_dic' );
		$ic_dph  = $order->get_meta( '_billing_ic_dph' );

		if ( ! empty( $company ) ) {
			$details[] = array(
				'label' => __( 'Company name', 'nimble-help-sk-cz-store-tools' ),
				'value' => $company,
			);
		}

		if ( ! empty( $ico ) ) {
			$details[] = array(
				'label' => __( 'Company ID', 'nimble-help-sk-cz-store-tools' ),
				'value' => $ico,
			);
		}

		if ( ! empty( $dic ) ) {
			$details[] = array(
				'label' => __( 'Tax ID', 'nimble-help-sk-cz-store-tools' ),
				'value' => $dic,
			);
		}

		if ( ! empty( $ic_dph ) ) {
			$details[] = array(
				'label' => __( 'VAT ID', 'nimble-help-sk-cz-store-tools' ),
				'value' => $ic_dph,
			);
		}

		return $details;
	}

	/**
	 * Get company fields for customer emails.
	 *
	 * @param WC_Order $order Order object.
	 * @return array<int, array{label: string, value: string}>
	 */
	private function get_company_email_details( $order ) {
		$details = array();
		$company = $order->get_billing_company();
		$ico     = $order->get_meta( '_billing_ico' );
		$dic     = $order->get_meta( '_billing_dic' );
		$ic_dph  = $order->get_meta( '_billing_ic_dph' );

		if ( ! empty( $company ) ) {
			$details[] = array(
				'label' => __( 'Company name', 'nimble-help-sk-cz-store-tools' ),
				'value' => $company,
			);
		}

		if ( ! empty( $ico ) ) {
			$details[] = array(
				'label' => __( 'Company ID', 'nimble-help-sk-cz-store-tools' ),
				'value' => $ico,
			);
		}

		if ( ! empty( $dic ) ) {
			$details[] = array(
				'label' => __( 'Tax ID', 'nimble-help-sk-cz-store-tools' ),
				'value' => $dic,
			);
		}

		if ( ! empty( $ic_dph ) ) {
			$details[] = array(
				'label' => __( 'VAT ID', 'nimble-help-sk-cz-store-tools' ),
				'value' => $ic_dph,
			);
		}

		return $details;
	}

	/**
	 * Mirror block checkout fields into existing order data keys.
	 *
	 * @param string           $key       Additional field key.
	 * @param mixed            $value     Additional field value.
	 * @param string           $group     Additional field group.
	 * @param WC_Data|WC_Order $wc_object WooCommerce object being updated.
	 * @return void
	 */
	public function sync_block_field_to_order_meta( $key, $value, $group, $wc_object ) {
		if ( 'other' !== $group || ! $wc_object instanceof WC_Order ) {
			return;
		}

		if ( ! in_array( $key, $this->block_field_ids, true ) ) {
			return;
		}

		if ( $this->block_field_ids['buying_as_company'] === $key ) {
			$is_company = $this->get_bool_value( $value ) ? '1' : '0';

			$wc_object->update_meta_data( '_billing_buying_as_company', $is_company );

			if ( '0' === $is_company ) {
				$wc_object->set_billing_company( '' );
				$wc_object->update_meta_data( '_billing_ico', '' );
				$wc_object->update_meta_data( '_billing_dic', '' );
				$wc_object->update_meta_data( '_billing_ic_dph', '' );
			}

			return;
		}

		if ( $this->block_field_ids['company_name'] === $key ) {
			if ( '1' !== $wc_object->get_meta( '_billing_buying_as_company' ) ) {
				$this->delete_block_additional_field_meta( $wc_object, $key );
				return;
			}

			$wc_object->set_billing_company( $this->sanitize_scalar_text( $value ) );
			$this->delete_block_additional_field_meta( $wc_object, $key );
			return;
		}

		if ( $this->block_field_ids['company_id'] === $key ) {
			if ( '1' !== $wc_object->get_meta( '_billing_buying_as_company' ) ) {
				$this->delete_block_additional_field_meta( $wc_object, $key );
				return;
			}

			$wc_object->update_meta_data( '_billing_ico', $this->sanitize_scalar_text( $value ) );
			$this->delete_block_additional_field_meta( $wc_object, $key );
			return;
		}

		if ( $this->block_field_ids['tax_id'] === $key ) {
			if ( '1' !== $wc_object->get_meta( '_billing_buying_as_company' ) ) {
				$this->delete_block_additional_field_meta( $wc_object, $key );
				return;
			}

			$wc_object->update_meta_data( '_billing_dic', $this->sanitize_scalar_text( $value ) );
			$this->delete_block_additional_field_meta( $wc_object, $key );
			return;
		}

		if ( $this->block_field_ids['vat_id'] === $key ) {
			if ( '1' !== $wc_object->get_meta( '_billing_buying_as_company' ) ) {
				$this->delete_block_additional_field_meta( $wc_object, $key );
				return;
			}

			$wc_object->update_meta_data( '_billing_ic_dph', $this->sanitize_scalar_text( $value ) );
			$this->delete_block_additional_field_meta( $wc_object, $key );
		}
	}

	/**
	 * Make the final Store API checkout payload authoritative for mirrored meta.
	 *
	 * Additional checkout fields are saved independently by WooCommerce Blocks.
	 * This pass prevents stale hidden company values from being re-saved after
	 * the shopper unchecks the company-purchase toggle.
	 *
	 * @param WC_Order        $order   Order object.
	 * @param WP_REST_Request $request Store API request.
	 * @return void
	 */
	public function sync_block_company_order_meta_from_request( $order, $request ) {
		if ( ! $order instanceof WC_Order || ! $request instanceof WP_REST_Request ) {
			return;
		}

		$additional_fields = $request->get_param( 'additional_fields' );

		if ( ! is_array( $additional_fields ) ) {
			return;
		}

		$is_company = isset( $additional_fields[ $this->block_field_ids['buying_as_company'] ] )
			&& $this->get_bool_value( $additional_fields[ $this->block_field_ids['buying_as_company'] ] );

		$order->update_meta_data( '_billing_buying_as_company', $is_company ? '1' : '0' );

		if ( ! $is_company ) {
			$order->set_billing_company( '' );
			$order->update_meta_data( '_billing_ico', '' );
			$order->update_meta_data( '_billing_dic', '' );
			$order->update_meta_data( '_billing_ic_dph', '' );
			$this->delete_block_company_text_field_meta( $order );
			return;
		}

		$order->set_billing_company( $this->get_block_request_field_value( $additional_fields, 'company_name' ) );
		$order->update_meta_data( '_billing_ico', $this->get_block_request_field_value( $additional_fields, 'company_id' ) );
		$order->update_meta_data( '_billing_dic', $this->get_block_request_field_value( $additional_fields, 'tax_id' ) );
		$order->update_meta_data( '_billing_ic_dph', $this->get_block_request_field_value( $additional_fields, 'vat_id' ) );
		$this->delete_block_company_text_field_meta( $order );
	}

	/**
	 * Remove duplicate block additional text-field meta after Store API checkout updates.
	 *
	 * WooCommerce renders block additional fields from its own `_wc_other/...`
	 * meta keys in some admin/customer order views. Keep the native checkbox
	 * meta so WooCommerce can display the selected company-purchase state, but
	 * remove text-field duplicates because those are mirrored into billing meta.
	 *
	 * @param WC_Order        $order   Order object.
	 * @param WP_REST_Request $request Store API request.
	 * @return void
	 */
	public function cleanup_block_company_order_meta( $order, $request = null ) {
		unset( $request );

		if ( ! $order instanceof WC_Order ) {
			return;
		}

		$this->delete_block_company_text_field_meta( $order );

		$order->save();
	}

	/**
	 * Get a sanitized classic checkout posted text value.
	 *
	 * @param string $key Posted field key.
	 * @return string
	 */
	private function get_posted_text( $key ) {
		// phpcs:ignore WordPress.Security.NonceVerification.Missing -- Checkout POST data is read only after the WooCommerce checkout nonce is verified in the same condition.
		if ( ! $this->has_checkout_post_nonce() || ! isset( $_POST[ $key ] ) ) {
			return '';
		}

		// phpcs:ignore WordPress.Security.NonceVerification.Missing, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Checkout POST data is read only after the WooCommerce checkout nonce is verified above and sanitized before return.
		$value = wp_unslash( $_POST[ $key ] );

		if ( is_array( $value ) ) {
			return '';
		}

		return trim( sanitize_text_field( (string) $value ) );
	}

	/**
	 * Get a classic checkout posted boolean value.
	 *
	 * @param string $key Posted field key.
	 * @return bool
	 */
	private function get_posted_bool( $key ) {
		// phpcs:ignore WordPress.Security.NonceVerification.Missing -- Checkout POST data is read only after the WooCommerce checkout nonce is verified in the same condition.
		if ( ! $this->has_checkout_post_nonce() || ! isset( $_POST[ $key ] ) ) {
			return false;
		}

		// phpcs:ignore WordPress.Security.NonceVerification.Missing, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Checkout POST data is read only after the WooCommerce checkout nonce is verified above and normalized before return.
		$value = wp_unslash( $_POST[ $key ] );

		if ( is_array( $value ) ) {
			return false;
		}

		return $this->get_bool_value( $value );
	}

	/**
	 * Get custom attributes for block company fields.
	 *
	 * @param bool $required Whether the field should be required when shown.
	 * @return array<string, string>
	 */
	private function get_block_company_field_attributes( $required ) {
		return array(
			'data-wscf-company-dependent' => '1',
			'data-wscf-company-required'  => $required ? '1' : '0',
		);
	}

	/**
	 * Get a trimmed string value from block checkout fields.
	 *
	 * @param array<string, mixed> $fields    Submitted order-location block fields.
	 * @param string               $field_key Internal block field key.
	 * @return string
	 */
	private function get_block_field_value( $fields, $field_key ) {
		if ( ! isset( $this->block_field_ids[ $field_key ], $fields[ $this->block_field_ids[ $field_key ] ] ) ) {
			return '';
		}

		$value = $fields[ $this->block_field_ids[ $field_key ] ];

		if ( is_array( $value ) ) {
			return '';
		}

		return trim( sanitize_text_field( (string) $value ) );
	}

	/**
	 * Get a boolean value from a scalar input.
	 *
	 * @param mixed $value Raw value.
	 * @return bool
	 */
	private function get_bool_value( $value ) {
		if ( is_array( $value ) ) {
			return false;
		}

		return wc_string_to_bool( $value );
	}

	/**
	 * Sanitize scalar text and reject arrays.
	 *
	 * @param mixed $value Raw value.
	 * @return string
	 */
	private function sanitize_scalar_text( $value ) {
		if ( is_array( $value ) ) {
			return '';
		}

		return trim( sanitize_text_field( (string) $value ) );
	}

	/**
	 * Get a sanitized Store API additional-field value.
	 *
	 * @param array<string, mixed> $additional_fields Store API additional fields.
	 * @param string               $field_key         Internal block field key.
	 * @return string
	 */
	private function get_block_request_field_value( $additional_fields, $field_key ) {
		if ( ! isset( $this->block_field_ids[ $field_key ], $additional_fields[ $this->block_field_ids[ $field_key ] ] ) ) {
			return '';
		}

		$value = $additional_fields[ $this->block_field_ids[ $field_key ] ];

		if ( is_array( $value ) ) {
			return '';
		}

		return trim( sanitize_text_field( (string) $value ) );
	}

	/**
	 * Remove the duplicate WooCommerce additional-field order meta for block orders.
	 *
	 * The company data is already mirrored into the billing/company meta keys
	 * that we use for admin output, emails, and customer order details.
	 *
	 * @param WC_Order $order    Order object.
	 * @param string   $field_id Registered block field ID.
	 * @return void
	 */
	private function delete_block_additional_field_meta( $order, $field_id ) {
		$order->delete_meta_data( '_wc_other/' . $field_id );
	}

	/**
	 * Remove WooCommerce additional-field meta for company text fields.
	 *
	 * @param WC_Order $order Order object.
	 * @return void
	 */
	private function delete_block_company_text_field_meta( $order ) {
		$this->delete_block_additional_field_meta( $order, $this->block_field_ids['company_name'] );
		$this->delete_block_additional_field_meta( $order, $this->block_field_ids['company_id'] );
		$this->delete_block_additional_field_meta( $order, $this->block_field_ids['tax_id'] );
		$this->delete_block_additional_field_meta( $order, $this->block_field_ids['vat_id'] );
	}

	/**
	 * Check whether WooCommerce will already render the block additional-info section.
	 *
	 * @param WC_Order $order Order object.
	 * @return bool
	 */
	private function has_block_company_purchase_email_section( $order ) {
		return '' !== (string) $order->get_meta( '_wc_other/' . $this->block_field_ids['buying_as_company'] );
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
