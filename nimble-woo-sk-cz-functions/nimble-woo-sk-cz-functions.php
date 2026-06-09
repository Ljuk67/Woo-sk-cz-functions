<?php
/**
 * Plugin Name:       Nimble Woo SK/CZ Functions
 * Plugin URI:        https://github.com/Ljuk67/Woo-sk-cz-functions
 * Description:       WooCommerce tools for Slovak and Czech stores: company checkout fields, COD fee, GDPR consent, shipping, and catalog helpers.
 * Version:           1.0.0
 * Requires at least: 6.4
 * Requires PHP:      7.4
 * Author:            Nimble.help
 * Author URI:        https://nimble.help
 * Text Domain:       nimble-woo-sk-cz-functions
 * Domain Path:       /languages
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Requires Plugins:  woocommerce
 *
 * @package WooCommerce_SK_CZ_Functions
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'WSCF_VERSION', '1.0.0' );
define( 'WSCF_PLUGIN_FILE', __FILE__ );
define( 'WSCF_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'WSCF_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

require_once WSCF_PLUGIN_PATH . 'includes/class-settings.php';
require_once WSCF_PLUGIN_PATH . 'includes/class-wc-settings-tab.php';
require_once WSCF_PLUGIN_PATH . 'includes/features/class-checkout-button-text.php';
require_once WSCF_PLUGIN_PATH . 'includes/features/class-company-checkout-fields.php';
require_once WSCF_PLUGIN_PATH . 'includes/features/class-gdpr-checkbox.php';
require_once WSCF_PLUGIN_PATH . 'includes/features/class-category-row.php';
require_once WSCF_PLUGIN_PATH . 'includes/features/class-hide-shipping-when-free.php';
require_once WSCF_PLUGIN_PATH . 'includes/features/class-cod-fee.php';
require_once WSCF_PLUGIN_PATH . 'includes/features/class-remove-additional-information-tab.php';
require_once WSCF_PLUGIN_PATH . 'includes/class-plugin.php';


function wscf_run_plugin() {
	$plugin = new WSCF_Plugin();
	$plugin->init();
}

wscf_run_plugin();
