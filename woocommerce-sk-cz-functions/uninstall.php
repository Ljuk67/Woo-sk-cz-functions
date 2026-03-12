<?php
/**
 * Uninstall handler.
 *
 * @package WooCommerce_SK_CZ_Funkcie
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

delete_option( 'wsczf_settings' );
