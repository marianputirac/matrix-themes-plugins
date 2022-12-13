<?php

use StgHelpdesk\Helpers\Stg_Helper_Template;

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://mycatchers.com
 * @since      1.0.0
 *
 * @package    Catchers_Helpdesk_Addon4
 * @subpackage Catchers_Helpdesk_Addon4/includes
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
 * @package    Catchers_Helpdesk_Addon4
 * @subpackage Catchers_Helpdesk_Addon4/includes
 * @author     mycatchers <support@mycatchers.com>
 */
class Catchers_Helpdesk_Addon4 {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Catchers_Helpdesk_Addon4_Loader    $loader    Maintains and registers all hooks for the plugin.
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
	public function __construct($pluginName) {

		$this->plugin_name = $pluginName;
		$this->version = STG_CATCHERS_HELPDESK_ADDON4_VERSION;


		$this->load_dependencies();
		$this->set_locale();

		$depStatus = self::checkPluginDeps();

		if(!$depStatus) {
            define('STG_CATCHERS_HELPDESK_ADDON4_TEXT_DOMAIN_NAME', 'catchers-helpdesk');
			add_filter('plugin_row_meta', array($this, 'pluginRowMeta'), 10, 2);
			add_action('admin_init', array($this, 'redirectToWelcomePage'), 12, 0);
			add_action('admin_menu', array($this, 'registerMenu'));
		}else{
            if(defined('STG_HELPDESK_POST_TYPE') && $depStatus){
                define('STG_CATCHERS_HELPDESK_ADDON4_TEXT_DOMAIN_NAME', STG_HELPDESK_TEXT_DOMAIN_NAME);
                $this->define_admin_hooks();
                $this->define_public_hooks();
            }
        }
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Catchers_Helpdesk_Addon4_Loader. Orchestrates the hooks of the plugin.
	 * - Catchers_Helpdesk_Addon4_i18n. Defines internationalization functionality.
	 * - Catchers_Helpdesk_Addon4_Admin. Defines all hooks for the admin area.
	 * - Catchers_Helpdesk_Addon4_Public. Defines all hooks for the public side of the site.
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
		/** @noinspection PhpIncludeInspection */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-catchers-helpdesk-addon4-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		/** @noinspection PhpIncludeInspection */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-catchers-helpdesk-addon4-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		/** @noinspection PhpIncludeInspection */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-catchers-helpdesk-addon4-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		/** @noinspection PhpIncludeInspection */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-catchers-helpdesk-addon4-public.php';

		$this->loader = new Catchers_Helpdesk_Addon4_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Catchers_Helpdesk_Addon4_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Catchers_Helpdesk_Addon4_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
    private function define_admin_hooks() {

        $plugin_admin = new Catchers_Helpdesk_Addon4_Admin( $this->get_plugin_name(), $this->get_version() );

        $this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
        $this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

        add_filter( 'user_has_cap', array($this,'stgh_enable_ticket_create'), 11, 2);

        add_filter('stgh_plugin_settings', array($this,'stgh_recaptcha_integrations'), 100, 1);
        add_filter('stgh_plugin_settings', array($this,'stgh_updates_integrations'), 101, 1);
        add_filter('stgh_plugin_settings', array($this,'stgh_ticket_close_core'), 102, 1);

        add_filter('catcher-helpdesk-comments-title-menu',array($this,'stgh_ticket_filter_menu_comment'));

        $helpdeskInit = StgHelpdesk\Core\Stg_Helpdesk_Init::getInstance();
        add_action('stgh_ticket_close_hook', array($helpdeskInit, 'cronTicketCloseTask'));

        \StgHelpdesk\Core\Stg_Helpdesk_Activator::setCronTicketCloseTasks();

        //run updater
        add_action( 'init', array( &$this, 'run_updater' ) );


        //dashboard widget
        add_action('wp_dashboard_setup', array($this, 'add_dashboard_widgets') );

    }

    function add_dashboard_widgets() {
        wp_add_dashboard_widget('dashboard_widget', __('Last Modified Helpdesk Tickets',STG_HELPDESK_TEXT_DOMAIN_NAME), array($this,'dashboard_widget_function'));
    }

    function dashboard_widget_function() {
        ob_start();
        Stg_Helper_Template::getTemplate('stg-last-tickets');
        $content = ob_get_contents();
        ob_end_clean();
        echo $content;
    }


    function stgh_enable_ticket_create($allcaps, $caps){
        if(in_array('create_ticket',$caps)){
            $allcaps['create_ticket'] = true;
        }
        return $allcaps;
    }

	function stgh_ticket_filter_menu_comment()
	{
		global $post;

		$items = array('all' => __('All data', STG_CATCHERS_HELPDESK_ADDON4_TEXT_DOMAIN_NAME), 'replies' => __('Replies', STG_CATCHERS_HELPDESK_ADDON4_TEXT_DOMAIN_NAME), 'user' => __('Contact replies', STG_CATCHERS_HELPDESK_ADDON4_TEXT_DOMAIN_NAME), 'agent' => __('Agent replies', STG_CATCHERS_HELPDESK_ADDON4_TEXT_DOMAIN_NAME));
		$items = apply_filters('catcher-helpdesk-comments-filter-menu',$items);
		$result = '<div id="stgh-filter-comment"><ul class="stgh-filter-comment-ul">';

		$commentCounts = array();
		foreach ($items as $k => $item) {
			$commentCount = stgh_ticket_get_comments_count(array('extra_params' => array('filter_key' => $k)));

			$agentTicket = get_post_meta( $post->ID, '_stgh_ticket_author_agent', true);

			if($agentTicket == 1){
				$commentCount += (!in_array($k, array('private', 'history', 'user', 'vote'))) ? 1 : 0;
			}
			else{
				$commentCount += (!in_array($k, array('private', 'history', 'agent', 'vote'))) ? 1 : 0;
			}

			if ($commentCount > 0) {
				$commentCounts[$k] = $commentCount;
			}
		}

		$i = 1;
		$count = count($commentCounts);

		foreach ($items as $k => $item) {
			if (isset($commentCounts[$k])) {
				$result .= '<li><a class="stgh_filter_dialog' . ($i == 1 ? ' stgh_filter_comments_cur' : '') . '" data-filter="' . $k . '" href="#">' . $item .
					' <span class="stgh_count_comment">(' . $commentCounts[$k] . ')</span>
                </a>' . (($i < $count) ? ' | ' : '') . '</li>';

				++$i;
			}
		}
		$result .= '</ul></div>';

		return $result;
	}



	function stgh_recaptcha_integrations($def)
	{

		list($code1) = explode("_",get_locale());
		$settings = array(
			'integrations' => array(
				'name' => __('Integrations', STG_CATCHERS_HELPDESK_ADDON4_TEXT_DOMAIN_NAME),
				'options' => array(
					array(
						'type' => 'heading',
						'name' => __('Google Invisible reCAPTCHA, ', STG_CATCHERS_HELPDESK_ADDON4_TEXT_DOMAIN_NAME)."<a href='https://www.google.com/recaptcha/admin#list'>get the keys</a>"
					),
					array(
						'name' => __('Site key', STG_CATCHERS_HELPDESK_ADDON4_TEXT_DOMAIN_NAME),
						'id' => 'recaptcha_key',
						'type' => 'text'
					),
					array(
						'name' => __('Secret key', STG_CATCHERS_HELPDESK_ADDON4_TEXT_DOMAIN_NAME),
						'id' => 'recaptcha_secret_key',
						'type' => 'text'
					),
					array(
						'name' => __('reCAPTCHA settings result', STG_CATCHERS_HELPDESK_ADDON4_TEXT_DOMAIN_NAME),
						'type' => 'custom',
						'custom' =>  "<script src=\"https://www.google.com/recaptcha/api.js?hl=".stgh_get_option('recaptcha_hl','en')."\" async defer></script>".StgHelpdesk\Helpers\Stg_Helper_Custom_Forms::getStandartFieldReCAPTCHA()
					),
					array(
						'name' => __('Language code', STG_CATCHERS_HELPDESK_ADDON4_TEXT_DOMAIN_NAME),
						'id' => 'recaptcha_hl',
						'type' => 'text',
						'default' => $code1,
						'desc' => __('Language settings, see more about ', STG_CATCHERS_HELPDESK_ADDON4_TEXT_DOMAIN_NAME)."<a href='https://developers.google.com/recaptcha/docs/language'>".__('language codes', STG_CATCHERS_HELPDESK_ADDON4_TEXT_DOMAIN_NAME)."</a>"
					),
					array(
						'name' => __('Enable form protection', STG_CATCHERS_HELPDESK_ADDON4_TEXT_DOMAIN_NAME),
						'id' => 'recaptcha_enable',
						'type' => 'checkbox',
						'default' => stgh_get_option('recaptcha_enable', false)
					)
				)
			),
		);

		return array_merge($def, $settings);

	}

	function stgh_ticket_close_core($def){

		$autoCloseTicketIntervals = array();

		$indexArray = array(1,3,5,10,30);
		foreach($indexArray as $index)
		{
			$arrayValue = sprintf(
				esc_attr(
					_n(
						'%s day',
						'%s days',
						$index,
						STG_HELPDESK_TEXT_DOMAIN_NAME
					)
				),
				number_format_i18n( $index )
			);


			$autoCloseTicketIntervals[$index*60*60*24] = $arrayValue;
		}


		$autoCloseTicketIntervals['never'] = __('never', STG_HELPDESK_TEXT_DOMAIN_NAME);


		$settings = array(
			array(
				'name' => __('Autoclose ticket after', STG_HELPDESK_TEXT_DOMAIN_NAME),
				'id' => 'stg_ticket_close_interval',
				'type' => 'select',
				'options' => $autoCloseTicketIntervals,
				'desc' =>  __('From status "Answered" to status "Сlosed"', STG_HELPDESK_TEXT_DOMAIN_NAME),
				'default' => stgh_get_option('stg_ticket_close_interval', 'never'),
			));

		$def['core']['options'] = array_merge(
			array_slice($def['core']['options'], 0, 2),
			$settings,
			array_slice($def['core']['options'], 2)
		);

		return $def;
	}


	function stgh_updates_integrations($def)
	{

		$settings = array(
			'integrations' => array(
				'name' => __('Integrations', STG_CATCHERS_HELPDESK_ADDON4_TEXT_DOMAIN_NAME),
				'options' => array(
					array(
						'type' => 'heading',
						'name' => __('Addon\'s update', STG_CATCHERS_HELPDESK_ADDON4_TEXT_DOMAIN_NAME)
					),
					array(
						'name' => __('Envato Personal Token', STG_CATCHERS_HELPDESK_ADDON4_TEXT_DOMAIN_NAME),
						'id' => 'envato_token',
						'type' => 'text',
					),
					array(
						'name' => __('Plugin license', STG_CATCHERS_HELPDESK_ADDON4_TEXT_DOMAIN_NAME),
						'id' => 'plugin_license',
						'type' => 'text',
					),
				)
			),
		);

		$res = array_merge($def, $settings);

		if(isset($def['integrations']['options']))
		{
			$res['integrations']['options'] = array_unique(array_merge($def['integrations']['options'], $settings['integrations']['options']), SORT_REGULAR);
		}

		return $res;

	}



	function registerMenu(){
			add_submenu_page('index.php',
				__('Catchers Helpdesk  Add-on starting page.', STG_CATCHERS_HELPDESK_ADDON4_TEXT_DOMAIN_NAME), __('Welcome', STG_CATCHERS_HELPDESK_ADDON4_TEXT_DOMAIN_NAME), 'activate_plugins',
				'stgh-addon4-start', array($this, 'showWelcomePage'));

			remove_submenu_page('index.php', 'stgh-addon4-start');
	}

	function redirectToWelcomePage(){
		if(get_option('stgh_redirect_to_welcome_page_addon'))
		{
			wp_redirect( add_query_arg(array('page' => 'stgh-addon4-start'),admin_url('index.php')));
			delete_option('stgh_redirect_to_welcome_page_addon');
		}
	}

	public function showWelcomePage()
	{
		/** @noinspection PhpIncludeInspection */
		include_once(STG_CATCHERS_HELPDESK_ADDON4_ROOT . 'admin/partials/welcome.php');
	}

	public function pluginRowMeta( $links, $file ) {
		list($part1,$part2) = explode('/',$file);

		if ( STG_CATCHERS_HELPDESK_ADDON4_NAME == $part1 &&  STG_CATCHERS_HELPDESK_ADDON4_NAME.".php" == $part2) {

			$freeHDActivated = is_plugin_active(STG_CATCHERS_HELPDESK_NAME.'/'.STG_CATCHERS_HELPDESK_NAME.'.php');
			$proHDActivated = is_plugin_active(STG_CATCHERS_HELPDESK_NAME_PRO . '/' . STG_CATCHERS_HELPDESK_NAME_PRO . '.php');

			$addonDeps = Catchers_Helpdesk_Addon4::checkPluginDeps();

			$welcomePage = add_query_arg(array('page' => 'stgh-addon4-start'),admin_url('index.php'));
			$row_meta = array();

			if(!$freeHDActivated && !$proHDActivated){
				$row_meta = array(
					'welcome'    => '<a class="error-message" href="' . esc_url($welcomePage) . '">' . esc_html__( 'Attention, please! Catchers Helpdesk plugin is not installed or not activated!', STG_CATCHERS_HELPDESK_ADDON4_TEXT_DOMAIN_NAME ) . '</a>'
				);
			}else{
				if (!$addonDeps) {
					$row_meta = array(
						'welcome'    => '<a class="error-message" href="' . esc_url($welcomePage) . '">' . esc_html__( 'Attention, please! You have the old version of Catchers Helpdesk!', STG_CATCHERS_HELPDESK_ADDON4_TEXT_DOMAIN_NAME ) . '</a>'
					);
				}
			}



			return array_merge( $links, $row_meta );
		}

		return (array) $links;
	}


	function run_updater(){
		$pluginPath = STG_CATCHERS_HELPDESK_ADDON4_ROOT."".STG_CATCHERS_HELPDESK_ADDON4_NAME.".php";
		$pluginData = stgh_wc_updater_get_plugin_data($pluginPath);
		$pluginCurrentVersion = $pluginData['Version'];

		$adapterName = 'Mycatchers';
		$data = array(
			'Mycatchers' => array(
				'license' => 'none',
			)
		);


		$pluginLicense = stgh_get_option('plugin_license');
		$envatoToken = stgh_get_option('envato_token');


		if(strlen($pluginLicense) == 34){
			$adapterName = 'Mycatchers';
			$data = array(
				'Mycatchers' => array(
					'license' => $pluginLicense
				)
			);
		}else{
			if(!empty($envatoToken))
			{
				$adapterName = 'Envato';
				$data = array(
					'Envato' => array(
						'license' => 'envato',
						'token' => $envatoToken,
						//@todo
						'itemId' => ''
					)
				);
			}
		}
		if($adapterName != false){
			if(!class_exists('\StgHelpdeskAddon4\Updater\AutoUpdater'))
			{
				/** @noinspection PhpIncludeInspection */
				require_once plugin_dir_path( dirname( __FILE__ ) ) . 'updater/auto_updater.php';
			}
			new \StgHelpdeskAddon4\Updater\AutoUpdater($adapterName, $pluginCurrentVersion, $data, $this->get_plugin_name());
		}

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Catchers_Helpdesk_Addon4_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
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
	 * @return    Catchers_Helpdesk_Addon4_Loader    Orchestrates the hooks of the plugin.
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

	public static function checkPluginDeps()
	{
		include_once(ABSPATH.'wp-admin/includes/plugin.php');

		$freeHDActivated = is_plugin_active(STG_CATCHERS_HELPDESK_NAME . '/' . STG_CATCHERS_HELPDESK_NAME . '.php');
		$proHDActivated = is_plugin_active(STG_CATCHERS_HELPDESK_NAME_PRO . '/' . STG_CATCHERS_HELPDESK_NAME_PRO . '.php');

		if (!$freeHDActivated && !$proHDActivated) {

			return false;
		} else {
			$pluginFile = STG_HELPDESK_ROOT.STG_HELPDESK_NAME.".php";
			$readmeFile = STG_HELPDESK_ROOT."README.txt";
			$pluginData = stgh_wc_updater_get_plugin_data($pluginFile, $readmeFile);

			if (version_compare($pluginData["Version"], STG_CATCHERS_HELPDESK_ADDON4_VERSION, '<')) {
				return false;
			}

		}

		return true;
	}

}
