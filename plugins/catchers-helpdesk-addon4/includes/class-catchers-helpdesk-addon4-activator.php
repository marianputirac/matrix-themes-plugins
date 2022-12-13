<?php

/**
 * Fired during plugin activation
 *
 * @link       https://mycatchers.com
 * @since      1.0.0
 *
 * @package    Catchers_Helpdesk_Addon4
 * @subpackage Catchers_Helpdesk_Addon4/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Catchers_Helpdesk_Addon4
 * @subpackage Catchers_Helpdesk_Addon4/includes
  */
class Catchers_Helpdesk_Addon4_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		$addonDeps = Catchers_Helpdesk_Addon4::checkPluginDeps();

		if(!$addonDeps) {
			update_option('stgh_redirect_to_welcome_page_addon', true);
		}

	}

}
