<?php
/**
 * Uninstall handler.
 *
 * @package WooCommerce_SK_CZ_Functions
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

delete_option( 'wscf_settings' );
