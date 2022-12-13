<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://mycatchers.com/
 * @since             1.0.0
 * @package           Catchers_Helpdesk_Addon4
 *
 * @wordpress-plugin
 * Plugin Name:       Catchers Helpdesk Productivity Add-on
 * Plugin URI:        https://mycatchers.com/productivity/
 * Description:       A focus on process allows you to operate more efficiently, enhances the customer experience so you can make more informed scaling decisions
 * Version:           2.6.1
 * Author:            mycatchers
 * Author URI:        https://mycatchers.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       catchers-helpdesk-addon4
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}


/** @noinspection PhpIncludeInspection */
require_once plugin_dir_path( __FILE__ ) . 'bootstrap.php';


/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-catchers-helpdesk-addon4-activator.php
 */
function activate_catchers_helpdesk_addon4() {
	/** @noinspection PhpIncludeInspection */
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-catchers-helpdesk-addon4-activator.php';
	Catchers_Helpdesk_Addon4_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-catchers-helpdesk-addon4-deactivator.php
 */
function deactivate_catchers_helpdesk_addon4() {
	/** @noinspection PhpIncludeInspection */
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-catchers-helpdesk-addon4-deactivator.php';
	Catchers_Helpdesk_Addon4_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_catchers_helpdesk_addon4');
register_deactivation_hook( __FILE__, 'deactivate_catchers_helpdesk_addon4');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
/** @noinspection PhpIncludeInspection */
require plugin_dir_path( __FILE__ ) . 'includes/class-catchers-helpdesk-addon4.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_catchers_helpdesk_addon4() {

	$plugin = new Catchers_Helpdesk_Addon4(plugin_basename(__FILE__));
	$plugin->run();

}

add_action('plugins_loaded','run_catchers_helpdesk_addon4');
