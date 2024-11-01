<?php

/**
 * @since      1.0.0
 * @package    Woo_Paypal_Checkout
 * @subpackage Woo_Paypal_Checkout/includes
 * @author     paltechwpdev <paltechwpdev@gmail.com>
 */
class Woo_Paypal_Checkout {

    protected $loader;
    protected $plugin_name;
    protected $version;

    public function __construct() {
        if (defined('WOO_PAYPAL_CHECKOUT_VERSION')) {
            $this->version = WOO_PAYPAL_CHECKOUT_VERSION;
        } else {
            $this->version = '1.0.0';
        }
        $this->plugin_name = 'woo-paypal-checkout';
        if (!defined('WOO_PAYPAL_CHECKOUT_PLUGIN_NAME')) {
            define('WOO_PAYPAL_CHECKOUT_PLUGIN_NAME', $this->plugin_name);
        }

        $this->load_dependencies();
        $this->set_locale();
        $this->define_admin_hooks();
        $this->define_public_hooks();
        add_action('woocommerce_cart_emptied', array($this, 'paltechwpdevwpc_clear_session'), 1);
        add_action('woocommerce_cart_item_removed', array($this, 'paltechwpdevwpc_clear_session'), 1);
        add_action('woocommerce_update_cart_action_cart_updated', array($this, 'paltechwpdevwpc_clear_session'), 1);
    }

    private function load_dependencies() {

        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-woo-paypal-checkout-functions.php';
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-woo-paypal-checkout-loader.php';
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-woo-paypal-checkout-i18n.php';
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-woo-paypal-checkout-admin.php';
        require_once plugin_dir_path(dirname(__FILE__)) . 'public/class-woo-paypal-checkout-public.php';
        $this->loader = new Woo_Paypal_Checkout_Loader();
    }

    private function set_locale() {
        $plugin_i18n = new Woo_Paypal_Checkout_i18n();
        $this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');
    }

    private function define_admin_hooks() {
        $plugin_admin = new Woo_Paypal_Checkout_Admin($this->get_plugin_name(), $this->get_version());
        $this->loader->add_action('plugins_loaded', $plugin_admin, 'paltechwpdevwpc_init_paypal_checkout');
        $this->loader->add_filter('woocommerce_payment_gateways', $plugin_admin, 'paltechwpdevwpc_add_paypal_checkout_gateway_class');
    }

    private function define_public_hooks() {
        $plugin_public = new Woo_Paypal_Checkout_Public($this->get_plugin_name(), $this->get_version());
        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles', 0);
        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');
        $this->loader->add_action('woocommerce_after_add_to_cart_form', $plugin_public, 'paltechwpdevwpc_display_paypal_button_on_product_page', 1);
        $this->loader->add_action('woocommerce_proceed_to_checkout', $plugin_public, 'paltechwpdevwpc_display_paypal_button_on_cart_page', 19);
        $this->loader->add_action('woocommerce_review_order_after_submit', $plugin_public, 'paltechwpdevwpc_display_paypal_button_on_checkout_page');
        $this->loader->add_action('wp_head', $plugin_public, 'paltechwpdevwpc_add_header_meta', 0);
        $this->loader->add_action('wc_ajax_paltechwpdevwpc_ajax_generate_cart', $plugin_public, 'paltechwpdevwpc_ajax_generate_cart');
        $this->loader->add_filter('body_class', $plugin_public, 'paltechwpdevwpc_add_body_classes');
        $this->loader->add_filter('the_title', $plugin_public, 'paltechwpdevwpc_endpoint_page_titles');
        $this->loader->add_action('woocommerce_available_payment_gateways', $plugin_public, 'paltechwpdevwpc_maybe_disable_other_gateways');
        $this->loader->add_action('woocommerce_checkout_billing', $plugin_public, 'paltechwpdevwpc_checkout_details_to_post', 0);
        $this->loader->add_action('woocommerce_cart_shipping_packages', $plugin_public, 'paltechwpdevwpc_maybe_add_shipping_information', 9);
        $this->loader->add_action('woocommerce_checkout_fields', $plugin_public, 'paltechwpdevwpc_checkout_fields', 9);
        $this->loader->add_action('woocommerce_before_checkout_billing_form', $plugin_public, 'paltechwpdevwpc_formatted_billing_address', 9);
        $this->loader->add_action('woocommerce_before_checkout_shipping_form', $plugin_public, 'paltechwpdevwpc_formatted_shipping_address', 9);
        $this->loader->add_filter('woocommerce_checkout_get_value', $plugin_public, 'paltechwpdevwpc_woocommerce_checkout_get_value', 99, 2);
    }

    public function run() {
        $this->loader->run();
    }

    public function get_plugin_name() {
        return $this->plugin_name;
    }

    public function get_loader() {
        return $this->loader;
    }

    public function get_version() {
        return $this->version;
    }

    public function paltechwpdevwpc_clear_session() {
        paltechwpdevwpc_clear_session_data();
    }

}
