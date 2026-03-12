<?php
/**
 * Plugin Name:       WooCommerce SK/CZ Functions
 * Plugin URI:        https://nimble.help
 * Description:       Useful WooCommerce features for Slovak/Czech stores - company checkout fields, GDPR checkbox, and UX improvements.
 * Version:           0.1.0
 * Requires at least: 6.4
 * Requires PHP:      7.4
 * Author:            Lukas Cech - www.nimble.help
 * Author URI:        https://nimble.help
 * Text Domain:       woocommerce-sk-cz-functions
 * Domain Path:       /languages
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 *
 * @package WooCommerce_SK_CZ_Functions
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'WSCF_VERSION', '0.1.0' );
define( 'WSCF_PLUGIN_FILE', __FILE__ );
define( 'WSCF_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'WSCF_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'WSCF_OPTION_KEY', 'wscf_settings' );

require_once WSCF_PLUGIN_PATH . 'includes/class-settings.php';
require_once WSCF_PLUGIN_PATH . 'includes/class-wc-settings-tab.php';
require_once WSCF_PLUGIN_PATH . 'includes/features/class-company-checkout-fields.php';
require_once WSCF_PLUGIN_PATH . 'includes/features/class-gdpr-checkbox.php';
require_once WSCF_PLUGIN_PATH . 'includes/features/class-category-row.php';
require_once WSCF_PLUGIN_PATH . 'includes/features/class-hide-shipping-when-free.php';
require_once WSCF_PLUGIN_PATH . 'includes/features/class-remove-additional-information-tab.php';
require_once WSCF_PLUGIN_PATH . 'includes/class-plugin.php';


function wscf_run_plugin() {
	$plugin = new WSCF_Plugin();
	$plugin->init();
}

wscf_run_plugin();
