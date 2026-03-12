<?php
/**
 * Plugin Name:       WooCommerce SK/CZ Funkcie
 * Plugin URI:        https://nimble.help
 * Description:       Zakladne SK/CZ WooCommerce funkcie pre checkout a katalog.
 * Version:           0.1.0
 * Requires at least: 6.4
 * Requires PHP:      7.4
 * Author:            nimble.help
 * Author URI:        https://nimble.help
 * Text Domain:       woocommerce-sk-cz-funkcie
 * Domain Path:       /languages
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 *
 * @package WooCommerce_SK_CZ_Funkcie
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'WSCZF_VERSION', '0.1.0' );
define( 'WSCZF_PLUGIN_FILE', __FILE__ );
define( 'WSCZF_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'WSCZF_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'WSCZF_OPTION_KEY', 'wsczf_settings' );

require_once WSCZF_PLUGIN_PATH . 'includes/class-settings.php';
require_once WSCZF_PLUGIN_PATH . 'includes/features/class-company-checkout-fields.php';
require_once WSCZF_PLUGIN_PATH . 'includes/features/class-gdpr-checkbox.php';
require_once WSCZF_PLUGIN_PATH . 'includes/features/class-category-row.php';
require_once WSCZF_PLUGIN_PATH . 'includes/class-plugin.php';

function wsczf_run_plugin() {
	$plugin = new WSCZF_Plugin();
	$plugin->init();
}

wsczf_run_plugin();
