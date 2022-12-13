<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://mycatchers.com
 * @since      1.0.0
 *
 * @package    Catchers_Helpdesk_Addon4
 * @subpackage Catchers_Helpdesk_Addon4/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Catchers_Helpdesk_Addon4
 * @subpackage Catchers_Helpdesk_Addon4/admin
 */
class Catchers_Helpdesk_Addon4_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	public static $nonceAction = 'stgh_catchers_helpdesk_addon4_update_nonce';
	public static $nonceName = 'stgh_catchers_helpdesk_addon4_nonce';

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Catchers_Helpdesk_Addon4_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Catchers_Helpdesk_Addon4_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		//wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/catchers-helpdesk-addon4-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		global $current_screen;
		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Catchers_Helpdesk_Addon4_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Catchers_Helpdesk_Addon4_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		//wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/catchers-helpdesk-addon4-admin.js', array( 'jquery' ), $this->version, false );



		if ($current_screen->id == "edit-stgh_ticket") {

			wp_enqueue_script('stgh-admin-export-scv', STG_HELPDESK_URL . 'js/admin/export-scv.js',
				array('jquery'),
				STG_HELPDESK_VERSION);

			wp_localize_script('stgh-admin-export-scv', 'stghExportCsv', array(
				'submitValue' => __('Export', STG_CATCHERS_HELPDESK_ADDON4_TEXT_DOMAIN_NAME),
			));
		}

	}

}
