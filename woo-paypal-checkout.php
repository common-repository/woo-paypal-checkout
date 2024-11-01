<?php

/**
 * @wordpress-plugin
 * Plugin Name:       PayPal Checkout for WooCommerce
 * Plugin URI:        https://profiles.wordpress.org/paltechwpdev/#content-plugins
 * Description:       PayPal Checkout with Smart Payment Buttons gives your buyers a simplified and secure checkout experience.
 * Version:           1.0.2
 * Author:            paltechwpdev
 * Author URI:        https://profiles.wordpress.org/paltechwpdev
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       woo-paypal-checkout
 * Domain Path:       /languages
 * Requires at least: 3.8
 * Tested up to: 5.0.3
 * WC requires at least: 3.0.0
 * WC tested up to: 3.5.3
 */
if (!defined('WPINC')) {
    die;
}
if (!defined('PALTECHWPDEVWPC_PLUGIN_DIR')) {
    define('PALTECHWPDEVWPC_PLUGIN_DIR', dirname(__FILE__));
}
if (!defined('PALTECHWPDEVWPC_ASSET_URL')) {
    define('PALTECHWPDEVWPC_ASSET_URL', plugin_dir_url(__FILE__));
}

define('WOO_PAYPAL_CHECKOUT_VERSION', '1.0.2');

function activate_woo_paypal_checkout() {
    require_once plugin_dir_path(__FILE__) . 'includes/class-woo-paypal-checkout-activator.php';
    Woo_Paypal_Checkout_Activator::activate();
}

function deactivate_woo_paypal_checkout() {
    require_once plugin_dir_path(__FILE__) . 'includes/class-woo-paypal-checkout-deactivator.php';
    Woo_Paypal_Checkout_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_woo_paypal_checkout');
register_deactivation_hook(__FILE__, 'deactivate_woo_paypal_checkout');
require plugin_dir_path(__FILE__) . 'includes/class-woo-paypal-checkout.php';

function run_woo_paypal_checkout() {
    $plugin = new Woo_Paypal_Checkout();
    $plugin->run();
}

run_woo_paypal_checkout();