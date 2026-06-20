<?php
/**
 * Uninstall handler.
 *
 * @package WooCommerce_SK_CZ_Functions
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

delete_option( 'wscf_company_checkout_fields' );
delete_option( 'wscf_gdpr_checkbox' );
delete_option( 'wscf_category_row' );
delete_option( 'wscf_hide_shipping_when_free' );
delete_option( 'wscf_cod_fee' );
delete_option( 'wscf_cod_fee_amount' );
delete_option( 'wscf_cod_fee_label' );
delete_option( 'wscf_cod_fee_label_customized' );
delete_option( 'wscf_checkout_button_text' );
delete_option( 'wscf_checkout_button_text_customized' );
delete_option( 'wscf_remove_additional_information_tab' );
delete_option( 'wscf_move_additional_information_to_description' );
