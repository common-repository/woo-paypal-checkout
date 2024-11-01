<?php

/**
 * @since      1.0.0
 * @package    Woo_Paypal_Checkout
 * @subpackage Woo_Paypal_Checkout/includes
 * @author     paltechwpdev <paltechwpdev@gmail.com>
 */
class Woo_Paypal_Checkout_i18n {

    public function load_plugin_textdomain() {

        load_plugin_textdomain(
                'woo-paypal-checkout', false, dirname(dirname(plugin_basename(__FILE__))) . '/languages/'
        );
    }

}
