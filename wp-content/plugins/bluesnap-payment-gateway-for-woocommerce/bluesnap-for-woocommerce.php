<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://saucal.com
 * @since             1.0.0
 * @package           Woocommerce_Bluesnap_Gateway
 *
 * @wordpress-plugin
 * Plugin Name:       BlueSnap Payment Gateway for WooCommerce
 * Plugin URI:        https://bluesnap.com/
 * Description:       WooCommerce gateway module to accept credit/debit card payments worldwide
 * Version:           1.3.4.1
 * Author:            Bluesnap
 * Author URI:        https://home.bluesnap.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       woocommerce-bluesnap-gateway
 * Domain Path:       /i18n/languages
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'WC_BLUESNAP_PLUGIN_FILE', __FILE__ );
require_once 'class-woocommerce-bluesnap-gateway.php';

/**
 * Main instance of Woocommerce_Bluesnap_Gateway.
 *
 * Returns the main instance of WC_Bluesnap to prevent the need to use globals.
 *
 * @since  1.0.0
 * @return Woocommerce_Bluesnap_Gateway
 */

// phpcs:ignore WordPress.NamingConventions.ValidFunctionName
function WC_Bluesnap() {
	return Woocommerce_Bluesnap_Gateway::instance();
}

// Global for backwards compatibility.
$GLOBALS['woocommerce_bluesnap_gateway'] = WC_Bluesnap();
