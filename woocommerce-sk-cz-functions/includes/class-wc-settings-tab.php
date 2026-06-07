<?php
/**
 * WooCommerce settings tab integration.
 *
 * @package WooCommerce_SK_CZ_Functions
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WSCF_WC_Settings_Tab {

	/**
	 * Register WooCommerce settings tab hooks.
	 *
	 * @return void
	 */
	public function register_hooks() {
		add_filter( 'woocommerce_settings_tabs_array', array( $this, 'add_tab' ), 50 );
		add_action( 'woocommerce_settings_tabs_wscf', array( $this, 'render_tab' ) );
		add_action( 'woocommerce_update_options_wscf', array( $this, 'save_tab' ) );
	}

	/**
	 * Add plugin tab under WooCommerce settings.
	 *
	 * @param array<string, string> $tabs Existing WooCommerce tabs.
	 * @return array<string, string>
	 */
	public function add_tab( $tabs ) {
		$tabs['wscf'] = __( 'WooCommerce SK/CZ', 'woocommerce-sk-cz-functions' );
		return $tabs;
	}

	/**
	 * Render tab fields.
	 *
	 * @return void
	 */
	public function render_tab() {
		$this->delete_default_checkout_button_text_option();
		$this->render_settings_styles();
		woocommerce_admin_fields( $this->get_settings() );
	}

	/**
	 * Render lightweight settings UI styles.
	 *
	 * @return void
	 */
	private function render_settings_styles() {
		echo '<style>';
		echo 'tr.wscf-child-setting-row th{padding:0;}';
		echo 'tr.wscf-child-setting-row td{padding-top:0;padding-left:24px;border-left:2px solid #dcdcde;}';
		echo '.wscf-settings-preview-link{display:inline-block;margin-top:8px;}';
		echo '.wscf-settings-preview-image{display:block;width:180px;max-width:100%;height:auto;border:1px solid #dcdcde;border-radius:4px;}';
		echo '</style>';
		echo '<script>';
		echo 'document.addEventListener("DOMContentLoaded",function(){document.querySelectorAll(\'#mainform input[data-wscf-child-setting]\').forEach(function(input){var row=input.closest("tr");if(row){row.classList.add("wscf-child-setting-row");}});});';
		echo '</script>';
	}

	/**
	 * Save settings with WooCommerce standard option handling
	 *
	 * @return void
	 */
	public function save_tab() {
		woocommerce_update_options( $this->get_settings() );
		$this->normalize_checkout_button_text_setting();
		$this->normalize_cod_fee_settings();
	}

	/**
	 * WooCommerce settings field definitions.
	 *
	 * @return array<int, array<string, mixed>>
	 */
	public function get_settings() {
		$settings_service = new WSCF_Settings();
		$option_names     = $settings_service->get_option_names();

		$settings = array(
			array(
				'title' => __( 'WooCommerce SK/CZ', 'woocommerce-sk-cz-functions' ),
				'type'  => 'title',
				'id'    => 'wscf_settings_section',
			),
			array(
				'title'   => __( 'Checkout button text', 'woocommerce-sk-cz-functions' ),
				'desc'    => __( 'Leave the predefined text unchanged to use the translated default for the current site language. Clear the field to return to the default after saving custom text.', 'woocommerce-sk-cz-functions' ),
				'id'      => $option_names['checkout_button_text'],
				'type'    => 'text',
				'default' => $settings_service->get_default_checkout_button_text(),
				'css'     => 'min-width:320px;',
				'custom_attributes' => $this->get_feature_field_attributes( $settings_service, 'checkout_button_text' ),
				'desc_tip' => false,
			),
			array(
				'title'   => __( 'Enable company checkout fields', 'woocommerce-sk-cz-functions' ),
				'desc'    => $this->get_feature_description( $settings_service, 'company_checkout_fields', __( 'Adds company purchase checkbox and business fields at checkout.', 'woocommerce-sk-cz-functions' ) ),
				'id'      => $option_names['company_checkout_fields'],
				'type'    => 'checkbox',
				'default' => 'no',
				'custom_attributes' => $this->get_feature_field_attributes( $settings_service, 'company_checkout_fields' ),
				'desc_tip' => false,
			),
			array(
				'title'   => __( 'Enable GDPR checkbox in checkout', 'woocommerce-sk-cz-functions' ),
				'desc'    => $this->get_gdpr_checkbox_description( $settings_service ),
				'id'      => $option_names['gdpr_checkbox'],
				'type'    => 'checkbox',
				'default' => 'no',
				'custom_attributes' => $this->get_feature_field_attributes( $settings_service, 'gdpr_checkbox' ),
				'desc_tip' => false,
			),
			array(
				'title'   => __( 'Enable child category row on archives', 'woocommerce-sk-cz-functions' ),
				'desc'    => $this->get_category_row_description( $settings_service ),
				'id'      => $option_names['category_row'],
				'type'    => 'checkbox',
				'default' => 'no',
				'custom_attributes' => $this->get_feature_field_attributes( $settings_service, 'category_row' ),
				'desc_tip' => false,
			),
			array(
				'title'   => __( 'Hide paid shipping when free shipping exists', 'woocommerce-sk-cz-functions' ),
				'desc'    => $this->get_feature_description( $settings_service, 'hide_shipping_when_free', __( 'Shows only free shipping rates when free shipping is available.', 'woocommerce-sk-cz-functions' ) ),
				'id'      => $option_names['hide_shipping_when_free'],
				'type'    => 'checkbox',
				'default' => 'no',
				'custom_attributes' => $this->get_feature_field_attributes( $settings_service, 'hide_shipping_when_free' ),
				'desc_tip' => false,
			),
			array(
				'title'   => __( 'Hide Additional Information tab on products', 'woocommerce-sk-cz-functions' ),
				'desc'    => $this->get_feature_description( $settings_service, 'remove_additional_information_tab', __( 'Removes the Additional Information tab from single product pages.', 'woocommerce-sk-cz-functions' ) ),
				'id'      => $option_names['remove_additional_information_tab'],
				'type'    => 'checkbox',
				'default' => 'no',
				'custom_attributes' => $this->get_feature_field_attributes( $settings_service, 'remove_additional_information_tab' ),
				'desc_tip' => false,
			),
			array(
				'title'   => '',
				'desc'    => $this->get_feature_description( $settings_service, 'move_additional_information_to_description', __( 'When enabled, show the hidden Additional Information content directly in the long product description instead of hiding it completely.', 'woocommerce-sk-cz-functions' ) ),
				'id'      => $option_names['move_additional_information_to_description'],
				'type'    => 'checkbox',
				'default' => 'no',
				'custom_attributes' => $this->get_child_setting_field_attributes( $settings_service, 'move_additional_information_to_description', 'remove_additional_information_tab' ),
				'desc_tip' => false,
			),
			array(
				'title'   => __( 'Enable COD fee', 'woocommerce-sk-cz-functions' ),
				'desc'    => $this->get_feature_description( $settings_service, 'cod_fee', __( 'Adds an extra fee when the customer selects the Cash on Delivery payment gateway.', 'woocommerce-sk-cz-functions' ) ),
				'id'      => $option_names['cod_fee'],
				'type'    => 'checkbox',
				'default' => 'no',
				'custom_attributes' => $this->get_feature_field_attributes( $settings_service, 'cod_fee' ),
				'desc_tip' => false,
			),
			array(
				'title'   => __( 'Default COD fee amount', 'woocommerce-sk-cz-functions' ),
				'desc'    => $this->get_feature_description( $settings_service, 'cod_fee_amount', __( 'Used when no configured cart-total band matches. Based on cart total after shipping, tax, and coupons.', 'woocommerce-sk-cz-functions' ) ),
				'id'      => $option_names['cod_fee_amount'],
				'type'    => 'text',
				'default' => '0',
				'css'     => 'min-width:120px;',
				'custom_attributes' => $this->get_child_setting_field_attributes( $settings_service, 'cod_fee_amount', 'cod_fee' ),
				'desc_tip' => false,
			),
			array(
				'title'   => __( 'COD fee label', 'woocommerce-sk-cz-functions' ),
				'desc'    => $this->get_feature_description( $settings_service, 'cod_fee_label', __( 'Frontend fee label. Leave empty to use the default plugin label.', 'woocommerce-sk-cz-functions' ) ),
				'id'      => $option_names['cod_fee_label'],
				'type'    => 'text',
				'default' => '',
				'css'     => 'min-width:280px;',
				'custom_attributes' => $this->get_child_setting_field_attributes( $settings_service, 'cod_fee_label', 'cod_fee' ),
				'desc_tip' => false,
			),
		);

		$settings[] = array(
			'type' => 'sectionend',
			'id'   => 'wscf_settings_section',
		);

		return $settings;
	}

	/**
	 * Get field attributes for a child setting row.
	 *
	 * @param WSCF_Settings $settings_service Settings service.
	 * @param string        $feature_key      Feature key.
	 * @param string        $parent_key       Parent feature key.
	 * @return array<string, string>
	 */
	private function get_child_setting_field_attributes( $settings_service, $feature_key, $parent_key ) {
		$attributes = $this->get_feature_field_attributes( $settings_service, $feature_key );
		$attributes['data-wscf-child-setting'] = $parent_key;

		return $attributes;
	}

	/**
	 * Get field attributes for feature availability.
	 *
	 * @param WSCF_Settings $settings_service Settings service.
	 * @param string        $feature_key      Feature key.
	 * @return array<string, string>
	 */
	private function get_feature_field_attributes( $settings_service, $feature_key ) {
		if ( $settings_service->is_feature_available( $feature_key ) ) {
			return array();
		}

		return array(
			'disabled' => 'disabled',
		);
	}

	/**
	 * Get feature description with readiness note when unavailable.
	 *
	 * @param WSCF_Settings $settings_service Settings service.
	 * @param string        $feature_key      Feature key.
	 * @param string        $description      Base description.
	 * @return string
	 */
	private function get_feature_description( $settings_service, $feature_key, $description ) {
		if ( $settings_service->is_feature_available( $feature_key ) ) {
			return $description;
		}

		return sprintf(
			'%1$s <span class="description">%2$s</span>',
			esc_html( $description ),
			esc_html__( 'Not ready yet.', 'woocommerce-sk-cz-functions' )
		);
	}

	/**
	 * Get GDPR checkbox description with a block-checkout note.
	 *
	 * @param WSCF_Settings $settings_service Settings service.
	 * @return string
	 */
	private function get_gdpr_checkbox_description( $settings_service ) {
		$description = sprintf(
			'%1$s<br /><span class="description">%2$s</span>',
			esc_html__( 'Classic checkout only: Adds a required privacy-policy consent checkbox at checkout.', 'woocommerce-sk-cz-functions' ),
			esc_html__( 'In block checkout, the GDPR checkbox is combined with the terms-and-conditions checkbox.', 'woocommerce-sk-cz-functions' )
		);

		return $this->get_feature_description( $settings_service, 'gdpr_checkbox', $description );
	}

	/**
	 * Get child category row description with linked screenshot preview.
	 *
	 * @param WSCF_Settings $settings_service Settings service.
	 * @return string
	 */
	private function get_category_row_description( $settings_service ) {
		$description = $this->get_feature_description(
			$settings_service,
			'category_row',
			esc_html__( 'Displays clickable, responsive boxes for child categories above products on category archive pages.', 'woocommerce-sk-cz-functions' )
		);

		$image_path = WSCF_PLUGIN_PATH . 'assets/img/child_cat.jpeg';

		if ( ! file_exists( $image_path ) ) {
			return $description;
		}

		$image_url = WSCF_PLUGIN_URL . 'assets/img/child_cat.jpeg';
		$image_alt = __( 'Child category row preview', 'woocommerce-sk-cz-functions' );

		return sprintf(
			'%1$s<br /><a class="wscf-settings-preview-link" href="%2$s" target="_blank" rel="noopener noreferrer"><img class="wscf-settings-preview-image" src="%2$s" alt="%3$s" /></a>',
			$description,
			esc_url( $image_url ),
			esc_attr( $image_alt )
		);
	}

	/**
	 * Normalize saved COD fee settings after WooCommerce stores raw values.
	 *
	 * @return void
	 */
	private function normalize_cod_fee_settings() {
		$settings_service = new WSCF_Settings();
		$option_names     = $settings_service->get_option_names();

		update_option(
			$option_names['cod_fee_amount'],
			wc_format_decimal( $settings_service->get_setting_value( 'cod_fee_amount' ), wc_get_price_decimals() )
		);

		update_option(
			$option_names['cod_fee_label'],
			sanitize_text_field( $settings_service->get_setting_value( 'cod_fee_label' ) )
		);
	}

	/**
	 * Normalize saved checkout button text after WooCommerce stores raw values.
	 *
	 * @return void
	 */
	private function normalize_checkout_button_text_setting() {
		$settings_service = new WSCF_Settings();
		$option_names     = $settings_service->get_option_names();
		$button_text      = sanitize_text_field( get_option( $option_names['checkout_button_text'], '' ) );
		$customized_name  = $settings_service->get_checkout_button_text_customized_option_name();
		$was_customized   = $settings_service->is_checkout_button_text_customized();

		if ( '' === $button_text || ( ! $was_customized && $settings_service->is_current_checkout_button_text_default_value( $button_text ) ) ) {
			delete_option( $option_names['checkout_button_text'] );
			update_option( $customized_name, 'no' );
			return;
		}

		update_option( $option_names['checkout_button_text'], $button_text );
		update_option( $customized_name, 'yes' );
	}

	/**
	 * Delete stored locale defaults so the settings field follows the active language.
	 *
	 * @return void
	 */
	private function delete_default_checkout_button_text_option() {
		$settings_service = new WSCF_Settings();
		$option_names     = $settings_service->get_option_names();
		$button_text      = sanitize_text_field( get_option( $option_names['checkout_button_text'], '' ) );
		$customized_name  = $settings_service->get_checkout_button_text_customized_option_name();

		if ( $settings_service->is_checkout_button_text_customized() ) {
			return;
		}

		if ( '' !== $button_text && $settings_service->is_checkout_button_text_default_value( $button_text ) ) {
			delete_option( $option_names['checkout_button_text'] );
			update_option( $customized_name, 'no' );
			return;
		}

		if ( '' !== $button_text ) {
			update_option( $customized_name, 'yes' );
		}
	}
}
