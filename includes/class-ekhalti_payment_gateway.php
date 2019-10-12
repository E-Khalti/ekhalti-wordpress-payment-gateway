<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       #
 * @since      1.0.0
 *
 * @package    Ekhalti_payment_gateway
 * @subpackage Ekhalti_payment_gateway/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Ekhalti_payment_gateway
 * @subpackage Ekhalti_payment_gateway/includes
 * @author     shashikant <shashikant.marskole@gmail.com>
 */
class Ekhalti_payment_gateway {

    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      Ekhalti_payment_gateway_Loader    $loader    Maintains and registers all hooks for the plugin.
     */
    protected $loader;

    /**
     * The unique identifier of this plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $plugin_name    The string used to uniquely identify this plugin.
     */
    protected $plugin_name;

    /**
     * The current version of the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $version    The current version of the plugin.
     */
    protected $version;

    /**
     * Define the core functionality of the plugin.
     *
     * Set the plugin name and the plugin version that can be used throughout the plugin.
     * Load the dependencies, define the locale, and set the hooks for the admin area and
     * the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function __construct() {
        if (defined('EKHALTI_PAYMENT_GATEWAY_VERSION')) {
            $this->version = EKHALTI_PAYMENT_GATEWAY_VERSION;
        } else {
            $this->version = '1.0.0';
        }
        $this->plugin_name = 'ekhalti_payment_gateway';

        $this->load_dependencies();
        $this->set_locale();
        $this->define_admin_hooks();
        $this->define_public_hooks();
    }

    /**
     * Load the required dependencies for this plugin.
     *
     * Include the following files that make up the plugin:
     *
     * - Ekhalti_payment_gateway_Loader. Orchestrates the hooks of the plugin.
     * - Ekhalti_payment_gateway_i18n. Defines internationalization functionality.
     * - Ekhalti_payment_gateway_Admin. Defines all hooks for the admin area.
     * - Ekhalti_payment_gateway_Public. Defines all hooks for the public side of the site.
     *
     * Create an instance of the loader which will be used to register the hooks
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function load_dependencies() {

        /**
         * The class responsible for orchestrating the actions and filters of the
         * core plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-ekhalti_payment_gateway-loader.php';

        /**
         * The class responsible for defining internationalization functionality
         * of the plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-ekhalti_payment_gateway-i18n.php';
        /**
         * Ekhalti order
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-ekhalti_payment_order.php';
        /**
         * Ekhalti Contract
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-ekhalti_payment_contract.php';



        /**
         * The class responsible for defining all actions that occur in the admin area.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-ekhalti_payment_gateway-admin.php';

        /**
         * The class responsible for defining all actions that occur in the public-facing
         * side of the site.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/cart/vendor/autoload.php';
        require_once plugin_dir_path(dirname(__FILE__)) . 'public/class-ekhalti_payment_gateway-public.php';

        $this->loader = new Ekhalti_payment_gateway_Loader();
    }

    /**
     * Define the locale for this plugin for internationalization.
     *
     * Uses the Ekhalti_payment_gateway_i18n class in order to set the domain and to register the hook
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function set_locale() {

        $plugin_i18n = new Ekhalti_payment_gateway_i18n();

        $this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');
    }

    /**
     * Register all of the hooks related to the admin area functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_admin_hooks() {

        $plugin_admin = new Ekhalti_payment_gateway_Admin($this->get_plugin_name(), $this->get_version());

        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');
        /**
         * settings
         * 
         */
        $this->loader->add_action('init', $plugin_admin, 'init');
        $this->loader->add_action('admin_menu', $plugin_admin, 'admin_menu');
        $this->loader->add_action('admin_init', $plugin_admin, 'admin_setting_init');
//        $this->loader->add_action('edit_form_top', $plugin_admin, 'edit_form_top');
//        $this->loader->add_action('admin_head-post.php', $plugin_admin, 'edit_form_top');
    }

    /**
     * Register all of the hooks related to the public-facing functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_public_hooks() {

        $plugin_public = new Ekhalti_payment_gateway_Public($this->get_plugin_name(), $this->get_version());

        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');
        $this->loader->add_action('init', $plugin_public, 'register_shortcodes');
        /**
         * Ajax
         */
        $this->loader->add_action('wp_ajax_ek_buy_button', $plugin_public, 'ekhalti_buy_button_handle');
        $this->loader->add_action('wp_ajax_nopriv_ek_buy_button', $plugin_public, 'ekhalti_buy_button_handle');
        $this->loader->add_action('wp_ajax_ek_addtocart_button', $plugin_public, 'ekhalti_addtocart_button_handle');
        $this->loader->add_action('wp_ajax_nopriv_ek_addtocart_button', $plugin_public, 'ekhalti_addtocart_button_handle');
        $this->loader->add_action('wp_ajax_nopriv_ek_addtocart_checkout', $plugin_public, 'ekhalti_addtocart_checkout_handle');
        $this->loader->add_action('wp_ajax_nopriv_ek_get_cart', $plugin_public, 'ekhalti_get_cart_handle');
        $this->loader->add_action('wp_ajax_nopriv_ek_remove_from_cart', $plugin_public, 'ekhalti_remove_from_cart_handle');
        $this->loader->add_action('wp_ajax_nopriv_ek_clear_cart', $plugin_public, 'ekhalti_clear_cart_handle');
        

        



        /**
         * 
         * 
         */
        $this->loader->add_action('init', $plugin_public, 'wp_ekhalti_gateway_response');
        $this->loader->add_action('wp_ajax_ekhalti_gateway_response', $plugin_public, 'handle_ekhalti_gateway_response');
        $this->loader->add_action('wp_ajax_nopriv_ekhalti_gateway_response', $plugin_public, 'handle_ekhalti_gateway_response');
    }

    /**
     * Run the loader to execute all of the hooks with WordPress.
     *
     * @since    1.0.0
     */
    public function run() {
        $this->loader->run();
    }

    /**
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     *
     * @since     1.0.0
     * @return    string    The name of the plugin.
     */
    public function get_plugin_name() {
        return $this->plugin_name;
    }

    /**
     * The reference to the class that orchestrates the hooks with the plugin.
     *
     * @since     1.0.0
     * @return    Ekhalti_payment_gateway_Loader    Orchestrates the hooks of the plugin.
     */
    public function get_loader() {
        return $this->loader;
    }

    /**
     * Retrieve the version number of the plugin.
     *
     * @since     1.0.0
     * @return    string    The version number of the plugin.
     */
    public function get_version() {
        return $this->version;
    }

}
