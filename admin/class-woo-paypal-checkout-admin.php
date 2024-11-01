<?php

/**
 * @package    Woo_Paypal_Checkout
 * @subpackage Woo_Paypal_Checkout/admin
 * @author     paltechwpdev <paltechwpdev@gmail.com>
 */
class Woo_Paypal_Checkout_Admin {

    private $plugin_name;
    private $version;
    public $suffix;

    public function __construct($plugin_name, $version) {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    public function paltechwpdevwpc_init_paypal_checkout() {
        require_once PALTECHWPDEVWPC_PLUGIN_DIR . '/includes/gateway/class-wc-gateway-paltechwpdevwpc-paypal-checkout.php';
    }

    public function paltechwpdevwpc_add_paypal_checkout_gateway_class($methods) {
        $methods[] = 'WC_Gateway_PALTECHWPDEVWPC_PayPal_Checkout';
        return $methods;
    }

}
