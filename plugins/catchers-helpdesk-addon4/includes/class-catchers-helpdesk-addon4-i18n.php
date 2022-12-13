<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://mycatchers.com
 * @since      1.0.0
 *
 * @package    Catchers_Helpdesk_Addon4
 * @subpackage Catchers_Helpdesk_Addon4/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Catchers_Helpdesk_Addon4
 * @subpackage Catchers_Helpdesk_Addon4/includes
  */
class Catchers_Helpdesk_Addon4_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {
		load_plugin_textdomain(
			STG_CATCHERS_HELPDESK_ADDON4_TEXT_DOMAIN_NAME,
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);
	}



}
