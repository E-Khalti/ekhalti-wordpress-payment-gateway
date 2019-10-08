<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              #
 * @since             1.0.0
 * @package           Ekhalti_payment_gateway
 *
 * @wordpress-plugin
 * Plugin Name:       e-khalti payment gateway wordpress
 * Plugin URI:        https://e-khalti.com/
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            e-Khalti.com
 * Author URI:        #
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       ekhalti_payment_gateway
 * Domain Path:       /languages
 */
// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define('EKHALTI_PAYMENT_GATEWAY_VERSION', '1.0.0');

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-ekhalti_payment_gateway-activator.php
 */
function activate_ekhalti_payment_gateway() {
    require_once plugin_dir_path(__FILE__) . 'includes/class-ekhalti_payment_gateway-activator.php';
    Ekhalti_payment_gateway_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-ekhalti_payment_gateway-deactivator.php
 */
function deactivate_ekhalti_payment_gateway() {
    require_once plugin_dir_path(__FILE__) . 'includes/class-ekhalti_payment_gateway-deactivator.php';
    Ekhalti_payment_gateway_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_ekhalti_payment_gateway');
register_deactivation_hook(__FILE__, 'deactivate_ekhalti_payment_gateway');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-ekhalti_payment_gateway.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
add_filter('plugin_action_links_' . plugin_basename(__FILE__), function($links) {

    array_unshift($links,'<a href="' .
            admin_url('admin.php?page=ekhalti-settings-page') .
            '">' . __('Settings') . '</a>');
//    $links[] = '<a href="' .
//            admin_url('admin.php?page=ekhalti-settings-page') .
//            '">' . __('Settings') . '</a>';
    return $links;
}, 10, 2);

function run_ekhalti_payment_gateway() {

    $plugin = new Ekhalti_payment_gateway();
    $plugin->run();
}

run_ekhalti_payment_gateway();
