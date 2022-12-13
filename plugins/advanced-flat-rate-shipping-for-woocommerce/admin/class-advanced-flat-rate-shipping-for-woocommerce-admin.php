<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://www.multidots.com
 * @since      1.0.0
 *
 * @package    Advanced_Flat_Rate_Shipping_For_WooCommerce_Pro
 * @subpackage Advanced_Flat_Rate_Shipping_For_WooCommerce_Pro/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Advanced_Flat_Rate_Shipping_For_WooCommerce_Pro
 * @subpackage Advanced_Flat_Rate_Shipping_For_WooCommerce_Pro/admin
 * @author     Multidots <inquiry@multidots.in>
 */
class Advanced_Flat_Rate_Shipping_For_WooCommerce_Pro_Admin {

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

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name       The name of this plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct($plugin_name, $version) {

        $this->plugin_name = $plugin_name;
        $this->version = $version;
        $this->afrsm_pro_load_dependencies();
    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function afrsm_pro_enqueue_styles() {
        global $post;
        /* @var $_GET type */
        if (isset($_GET['page']) && !empty($_GET['page']) && ($_GET['page'] == 'afrsm-pro-list' || $_GET['page'] == 'afrsm-pro-add-shipping' || $_GET['page'] == 'wc-shipping-zones' || $_GET['page'] == 'afrsm-pro-get-started' || $_GET['page'] == 'afrsm-pro-information' || $_GET['page'] == 'afrsm-pro-edit-shipping')) {
            wp_enqueue_style($this->plugin_name . '-choose-css', plugin_dir_url(__FILE__) . 'css/chosen.min.css', array(), $this->version, 'all');
            wp_enqueue_style($this->plugin_name . '-jquery-ui-css', plugin_dir_url(__FILE__) . 'css/jquery-ui.min.css', array(), $this->version, 'all');
            wp_enqueue_style($this->plugin_name . 'font-awesome', plugin_dir_url(__FILE__) . 'css/font-awesome.min.css', array(), $this->version, 'all');
            wp_enqueue_style($this->plugin_name . 'main-style', plugin_dir_url(__FILE__) . 'css/style.css', array(), 'all');
            wp_enqueue_style($this->plugin_name . 'media-css', plugin_dir_url(__FILE__) . 'css/media.css', array(), 'all');
        }
    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function afrsm_pro_enqueue_scripts() {
        global $post;
        wp_enqueue_style('wp-jquery-ui-dialog');
        wp_enqueue_script('jquery-ui-accordion');
        if (isset($_GET['page']) && !empty($_GET['page']) && ($_GET['page'] == 'afrsm-pro-list' || $_GET['page'] == 'afrsm-pro-add-shipping' || $_GET['page'] == 'wc-shipping-zones' || $_GET['page'] == 'afrsm-pro-get-started' || $_GET['page'] == 'afrsm-pro-information' || $_GET['page'] == 'afrsm-pro-edit-shipping')) {
            wp_enqueue_script($this->plugin_name . '-choose-js', plugin_dir_url(__FILE__) . 'js/chosen.jquery.min.js', array('jquery', 'jquery-ui-datepicker'), $this->version, false);
            wp_enqueue_script($this->plugin_name . '-tablesorter-js', plugin_dir_url(__FILE__) . 'js/jquery.tablesorter.js', array('jquery'), $this->version, false);
            wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/advanced-flat-rate-shipping-for-woocommerce-admin.js', array('jquery', 'jquery-ui-dialog', 'jquery-ui-accordion', 'jquery-ui-sortable'), $this->version, false);
            wp_localize_script($this->plugin_name, 'coditional_vars', array('plugin_url' => plugin_dir_url(__FILE__)));
        }
    }
    
    private function afrsm_pro_load_dependencies() {
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/partials/afrsm-pro-shipping-zone-page.php';
    }
    
    /*
     * Shipping method Pro Menu
     * 
     * @since 3.0.0
     */
    public function dot_store_menu_shipping_method_pro() {
        global $GLOBALS;
        if (empty($GLOBALS['admin_page_hooks']['dots_store'])) {
            add_menu_page('Delivery Section', __('Delivery Section'), 'null', 'dots_store', array($this, 'dot_store_menu_page'), AFRSM_PRO_PLUGIN_URL . 'admin/images/menu-icon.png', 25);
        }
        
        add_submenu_page('dots_store', 'Advanced Flat Rate Shipping For WooCommerce Pro', 'Advanced Flat Rate Shipping For WooCommerce Pro', 'manage_options', 'afrsm-pro-list', array($this, 'afrsm_pro_fee_list_page'));
        add_submenu_page('dots_store', 'Add Shipping Method', 'Add Shipping Method', 'manage_options', 'afrsm-pro-add-shipping', array($this, 'afrsm_pro_add_new_fee_page'));
        add_submenu_page('dots_store', 'Edit Shipping Method', 'Edit Shipping Method', 'manage_options', 'afrsm-pro-edit-shipping', array($this, 'afrsm_pro_edit_fee_page'));
        add_submenu_page('dots_store', 'Manage Shipping Zones', 'Manage Shipping Zones', 'manage_options', 'wc-shipping-zones', array(__CLASS__, 'afrsm_pro_shipping_zone_page'));
        add_submenu_page('dots_store', 'Getting Started', 'Getting Started', 'manage_options', 'afrsm-pro-get-started', array($this, 'afrsm_pro_get_started_page'));
        add_submenu_page('dots_store', 'Quick info', 'Quick info', 'manage_options', 'afrsm-pro-information', array($this, 'afrsm_pro_information_page'));
    }

    public function dot_store_menu_page() {
    }

    public function afrsm_pro_fee_list_page() {
        require_once('partials/afrsm-pro-list-page.php');
    }

    public function afrsm_pro_add_new_fee_page() {
        require_once('partials/afrsm-pro-add-new-page.php');
    }

    public function afrsm_pro_edit_fee_page() {
        require_once('partials/afrsm-pro-add-new-page.php');
    }

    public static function afrsm_pro_shipping_zone_page() {
        $shipping_zone_obj = new AFRSM_Shipping_Zone();
        $shipping_zone_obj->output();
    }

    public function afrsm_pro_get_started_page() {
        require_once('partials/afrsm-pro-get-started-page.php');
    }
    
    public function afrsm_pro_information_page() {
        require_once('partials/afrsm-pro-information-page.php');
    }
    
    /**
     * Function for redirect to shipping list
     */
    public function afrsm_pro_redirect_shipping_function() {
        $afrsm_menu_url = admin_url('/admin.php?page=afrsm-pro-list');
        if (!empty($_GET['section']) && $_GET['section'] == 'advanced_flat_rate_shipping') {
            wp_redirect($afrsm_menu_url);
            exit;
        }
    }
    
    /**
     * Function for redirect to welcome screen to activation
     */
    public function afrsm_pro_welcome_shipping_method_screen_do_activation_redirect() {
        // if no activation redirect
        if (!get_transient('_welcome_screen_afrsm_pro_mode_activation_redirect_data')) {
            return;
        }

        // Delete the redirect transient
        delete_transient('_welcome_screen_afrsm_pro_mode_activation_redirect_data');

        // if activating from network, or bulk
        if (is_network_admin() || isset($_GET['activate-multi'])) {
            return;
        }
        // Redirect to extra cost welcome  page
        wp_safe_redirect(add_query_arg(array('page' => 'afrsm-pro-get-started'), admin_url('admin.php')));
    }
    
    /**
     * Set Active menu 
     */
    public function afrsm_pro_active_menu() {
        $screen = get_current_screen();
        
        //DotStore Menu Submenu based conditions
        if( isset($screen->id) && !empty($screen->id) ) {
            if ($screen->id == 'dotstore-plugins_page_afrsm-pro-add-shipping' || $screen->id == 'dotstore-plugins_page_afrsm-pro-edit-shipping' ||
                $screen->id == 'dotstore-plugins_page_afrsm-pro-get-started' || $screen->id == 'dotstore-plugins_page_afrsm-pro-information' ||
                $screen->id == 'dotstore-plugins_page_wc-shipping-zones') {
        ?>
                <script type="text/javascript">
                    jQuery(document).ready(function($) {
                        $('a[href="admin.php?page=afrsm-pro-list"]').parent().addClass('current');
                        $('a[href="admin.php?page=afrsm-pro-list"]').addClass('current');
                    });
                </script>
        <?php
            }
            //DotStore shipping tabs based conditions
            if ($screen->id == 'woocommerce_page_wc-settings') {

                $request_page       = isset($_REQUEST['page']) && !empty($_REQUEST['page']) ? $_REQUEST['page'] : '';
                $request_tab        = isset($_REQUEST['tab']) && !empty($_REQUEST['tab']) ? $_REQUEST['tab'] : '';
                $request_section    = isset($_REQUEST['section']) && !empty($_REQUEST['section']) ? $_REQUEST['section'] : '';

                if ($request_page == 'wc-settings' && $request_tab == 'shipping' && ($request_section == 'advanced_flat_rate_shipping' || $request_section == 'forceall' ) ) {
            ?>
                <script type="text/javascript">
                    jQuery(document).ready(function ($) {
                        $('#toplevel_page_woocommerce').removeClass('wp-menu-open wp-has-current-submenu').addClass('wp-not-current-submenu');
                        $('#toplevel_page_woocommerce > ul li a').removeClass('current');
                        $('#toplevel_page_woocommerce > ul li').removeClass('current');
                        $('#toplevel_page_dots_store').addClass('wp-has-current-submenu').removeClass('wp-not-current-submenu');
                        $('#toplevel_page_dots_store a.toplevel_page_dots_store').addClass('wp-has-current-submenu').removeClass('wp-not-current-submenu');
                        $('a[href="admin.php?page=afrsm-pro-list"]').parent().addClass('current');
                        $('a[href="admin.php?page=afrsm-pro-list"]').addClass('current');
                    });
                </script>
            <?php
                }
            }
        }
    }

    public function afrsm_pro_remove_admin_submenus() {
        remove_submenu_page('dots_store', 'afrsm-pro-add-shipping');
        remove_submenu_page('dots_store', 'afrsm-pro-edit-shipping');
        remove_submenu_page('dots_store', 'wc-shipping-zones');
        remove_submenu_page('dots_store', 'afrsm-pro-get-started');
        remove_submenu_page('dots_store', 'afrsm-pro-information');
    }
    
    /*
     * Is Available
     */
    public function afrsm_pro_condition_match_rules($sm_post_data = array(), $package = array()) {
        $final_condition_flag = 0;
        global $woocommerce, $sitepress;
        
        if (!empty($sitepress)) {
            $default_lang = $sitepress->get_default_language();
        }

        $is_passed = array();
        $cart_array = $woocommerce->cart->get_cart();
        
        $sm_status          = get_post_meta($sm_post_data->ID, 'sm_status', true);
        $sm_start_date      = get_post_meta($sm_post_data->ID, 'sm_start_date', true);
        $sm_end_date        = get_post_meta($sm_post_data->ID, 'sm_end_date', true);
        $get_condition_array = get_post_meta($sm_post_data->ID, 'sm_metabox', true);
        if (isset($sm_status) && $sm_status == 'off') {
            return false;
        }
        
        if (!empty($get_condition_array)) {

            $country_array = array();
            $state_array = array();
            $postcode_array = array();
            $zone_array = array();
            $product_array = array();
	    $variableproduct_array = array();
            $category_array = array();
            $tag_array = array();
            $sku_array = array();
            $user_array = array();
            $user_role_array = array();
            $cart_total_array = array();
            $cart_totalafter_array = array();
            $quantity_array = array();
            $weight_array = array();
            $coupon_array = array();
            $shipping_class_array = array();
            $getFeesPerQty = 10;
            foreach ($get_condition_array as $key => $value) {
                if (array_search('country', $value)) {
                    $country_array[$key] = $value;
                }
                if (array_search('state', $value)) {
                    $state_array[$key] = $value;
                }
                if (array_search('postcode', $value)) {
                    $postcode_array[$key] = $value;
                }
                if (array_search('zone', $value)) {
                    $zone_array[$key] = $value;
                }
                if (array_search('product', $value)) {
                    $product_array[$key] = $value;
                }
                if (array_search('variableproduct', $value)) {
                    $variableproduct_array[$key] = $value;
                }
                if (array_search('category', $value)) {
                    $category_array[$key] = $value;
                }
                if (array_search('tag', $value)) {
                    $tag_array[$key] = $value;
                }
                if (array_search('sku', $value)) {
                    $sku_array[$key] = $value;
                }
                if (array_search('user', $value)) {
                    $user_array[$key] = $value;
                }
                if (array_search('user_role', $value)) {
                    $user_role_array[$key] = $value;
                }
                if (array_search('cart_total', $value)) {
                    $cart_total_array[$key] = $value;
                }
                if (array_search('cart_totalafter', $value)) {
                    $cart_totalafter_array[$key] = $value;
                }
                if (array_search('quantity', $value)) {
                    $quantity_array[$key] = $value;
                }
                if (array_search('weight', $value)) {
                    $weight_array[$key] = $value;
                }
                if (array_search('coupon', $value)) {
                    $coupon_array[$key] = $value;
                }
                if (array_search('shipping_class', $value)) {
                    $shipping_class_array[$key] = $value;
                }
                
                //Check if is country exist
                if (is_array($country_array) && isset($country_array) && !empty($country_array) && !empty($cart_array)) {
                    $selected_country = $woocommerce->customer->get_shipping_country();
                    $is_passed['has_fee_based_on_country'] = '';
                    $passed_country = array();
                    $notpassed_country = array();
                    foreach ($country_array as $country) {
                        if ($country['product_fees_conditions_is'] == 'is_equal_to') {
                            if (!empty($country['product_fees_conditions_values'])) {
                                foreach ($country['product_fees_conditions_values'] as $country_id) {
                                    $passed_country[] = $country_id;
                                    if ($country_id == $selected_country) {
                                        $is_passed['has_fee_based_on_country'] = 'yes';
                                    }
                                }
                            }
                            if (empty($country['product_fees_conditions_values'])) {
                                $is_passed['has_fee_based_on_country'] = 'yes';
                            }
                        }
                        if ($country['product_fees_conditions_is'] == 'not_in') {
                            if (!empty($country['product_fees_conditions_values'])) {
                                foreach ($country['product_fees_conditions_values'] as $country_id) {
                                    $notpassed_country[] = $country_id;
                                    if ($country_id == 'all' || $country_id == $selected_country) {
                                        $is_passed['has_fee_based_on_country'] = 'no';
                                    }
                                }
                            }
                        }
                    }
                    if (empty($is_passed['has_fee_based_on_country']) && empty($passed_country)) {
                        $is_passed['has_fee_based_on_country'] = 'yes';
                    } elseif (empty($is_passed['has_fee_based_on_country']) && !empty($passed_country)) {
                        $is_passed['has_fee_based_on_country'] = 'no';
                    }
                }

                //Check if is state exist
                if (is_array($state_array) && isset($state_array) && !empty($state_array) && !empty($cart_array)) {
                    $country = $woocommerce->customer->get_shipping_country();
                    $state = $woocommerce->customer->get_shipping_state();
                    $selected_state = $country . ':' . $state;
                    $is_passed['has_fee_based_on_state'] = '';
                    $passed_state = array();
                    $notpassed_state = array();
                    foreach ($state_array as $state) {
                        if ($state['product_fees_conditions_is'] == 'is_equal_to') {
                            if (!empty($state['product_fees_conditions_values'])) {
                                foreach ($state['product_fees_conditions_values'] as $state_id) {
                                    $passed_state[] = $state_id;
                                    if ($state_id == $selected_state) {
                                        $is_passed['has_fee_based_on_state'] = 'yes';
                                    }
                                }
                            }
                        }
                        if ($state['product_fees_conditions_is'] == 'not_in') {
                            if (!empty($state['product_fees_conditions_values'])) {
                                foreach ($state['product_fees_conditions_values'] as $state_id) {
                                    $notpassed_state[] = $state_id;
                                    if ($state_id == $selected_state) {
                                        $is_passed['has_fee_based_on_state'] = 'no';
                                    }
                                }
                            }
                        }
                    }
                    if (empty($is_passed['has_fee_based_on_state']) && empty($passed_state)) {
                        $is_passed['has_fee_based_on_state'] = 'yes';
                    } elseif (empty($is_passed['has_fee_based_on_state']) && !empty($passed_state)) {
                        $is_passed['has_fee_based_on_state'] = 'no';
                    }
                }

                //Check if is postcode exist
                if (is_array($postcode_array) && isset($postcode_array) && !empty($postcode_array) && !empty($cart_array)) {
                    $selected_postcode = $woocommerce->customer->get_shipping_postcode();
                    $is_passed['has_fee_based_on_postcode'] = '';
                    $passed_postcode = array();
                    $notpassed_postcode = array();
                    foreach ($postcode_array as $postcode) {
                        if ($postcode['product_fees_conditions_is'] == 'is_equal_to') {
                            if (!empty($postcode['product_fees_conditions_values'])) {
                                $postcodestr = str_replace(PHP_EOL, "<br/>", $postcode['product_fees_conditions_values']);
                                $postcode_val_array = explode('<br/>', $postcodestr);
                                foreach ($postcode_val_array as $postcode_id) {
                                    $postcodeId = trim($postcode_id);
                                    $passed_postcode[] = $postcodeId;
                                    if ($postcodeId == $selected_postcode) {
                                        $is_passed['has_fee_based_on_postcode'] = 'yes';
                                    }
                                }
                            }
                        }
                        if ($postcode['product_fees_conditions_is'] == 'not_in') {
                            if (!empty($postcode['product_fees_conditions_values'])) {
                                $postcodestr = str_replace(PHP_EOL, "<br/>", $postcode['product_fees_conditions_values']);
                                $postcode_val_array = explode('<br/>', $postcodestr);
                                foreach ($postcode_val_array as $postcode_id) {
                                    $postcodeId = trim($postcode_id);
                                    $notpassed_postcode[] = $postcodeId;
                                    if ($postcodeId == $selected_postcode) {
                                        $is_passed['has_fee_based_on_postcode'] = 'no';
                                    }
                                }
                            }
                        }
                    }
                    if (empty($is_passed['has_fee_based_on_postcode']) && empty($passed_postcode)) {
                        $is_passed['has_fee_based_on_postcode'] = 'yes';
                    } elseif (empty($is_passed['has_fee_based_on_postcode']) && !empty($passed_postcode)) {
                        $is_passed['has_fee_based_on_postcode'] = 'no';
                    }
                }

                //Check if is zone exist
                if (is_array($zone_array) && isset($zone_array) && !empty($zone_array) && !empty($cart_array)) {
                    $get_zonelist = $this->wc_get_shipping_zone($package);
                    $is_passed['has_fee_based_on_zone'] = '';
                    $passed_zone = array();
                    $notpassed_zone = array();
                    foreach ($zone_array as $zone) {
                        if ($zone['product_fees_conditions_is'] == 'is_equal_to') {
                            if (!empty($zone['product_fees_conditions_values'])) {
                                if (in_array($get_zonelist, $zone['product_fees_conditions_values'])) {
                                    $is_passed['has_fee_based_on_zone'] = 'yes';
                                } else {
                                    $is_passed['has_fee_based_on_zone'] = 'no';
                                }
                            }
                        }
                        if ($zone['product_fees_conditions_is'] == 'not_in') {
                            if (!empty($zone['product_fees_conditions_values'])) {
                                if (in_array($get_zonelist, $zone['product_fees_conditions_values'])) {
                                    $is_passed['has_fee_based_on_zone'] = 'no';
                                } else {
                                    $is_passed['has_fee_based_on_zone'] = 'yes';
                                }
                            }
                        }
                    }
                }

                //Check if is variable product exist
                if (is_array($variableproduct_array) && isset($variableproduct_array) && !empty($variableproduct_array) && !empty($cart_array)) {

                        $cart_products_array = array();
                        $cart_product = $this->fee_array_column($cart_array, 'variation_id');
                        if( isset($cart_product) && !empty($cart_product) ) {

                                foreach ($cart_product as $key => $cart_product_id) {

                                        if( !empty($sitepress) ) {
                                                $cart_products_array[] = apply_filters( 'wpml_object_id', $cart_product_id, 'product', TRUE, $default_lang );
                                        } else {
                                                $cart_products_array[] = $cart_product_id;
                                        }
                                }
                        }
                        $is_passed['has_fee_based_on_product'] = '';
                        $passed_product = array();
                        $notpassed_product = array();
                        foreach ($variableproduct_array as $product) {
                                if ($product['product_fees_conditions_is'] == 'is_equal_to') {
                                        if (!empty($product['product_fees_conditions_values'])) {
                                                foreach ($product['product_fees_conditions_values'] as $product_id) {

                                                        $passed_product[] = $product_id;
                                                        if (in_array($product_id, $cart_products_array)) {
                                                                $is_passed['has_fee_based_on_product'] = 'yes';
                                                        }
                                                }
                                        }
                                }
                                if ($product['product_fees_conditions_is'] == 'not_in') {
                                        if (!empty($product['product_fees_conditions_values'])) {
                                                foreach ($product['product_fees_conditions_values'] as $product_id) {
                                                        $notpassed_product = $product_id;
                                                        if (in_array($product_id, $cart_product)) {
                                                                $is_passed['has_fee_based_on_product'] = 'no';
                                                        }
                                                }
                                        }
                                }
                        }

                        if (empty($is_passed['has_fee_based_on_product']) && empty($passed_product)) {
                                $is_passed['has_fee_based_on_product'] = 'yes';
                        } elseif (empty($is_passed['has_fee_based_on_product']) && !empty($passed_product)) {
                                $is_passed['has_fee_based_on_product'] = 'no';
                        }
                        
                }
                
                //Check if is product exist
                if (is_array($product_array) && isset($product_array) && !empty($product_array) && !empty($cart_array)) {

                    $cart_products_array = array();
                    $cart_product = $this->fee_array_column($cart_array, 'product_id');
                    $cart_final_products_array = array();
                    if (isset($cart_product) && !empty($cart_product)) {
                        foreach ($cart_product as $key => $cart_product_id) {
                            if (!empty($sitepress)) {
                                $cart_products_array[] = apply_filters('wpml_object_id', $cart_product_id, 'product', TRUE, $default_lang);
                            } else {
                                $cart_products_array[] = $cart_product_id;
                            }
                        }
                    }

                    $is_passed['has_fee_based_on_product'] = '';
                    $passed_product = array();
                    $notpassed_product = array();
                    foreach ($product_array as $product) {
                        if ($product['product_fees_conditions_is'] == 'is_equal_to') {
                            if (!empty($product['product_fees_conditions_values'])) {
                                foreach ($product['product_fees_conditions_values'] as $product_id) {
                                    $passed_product[] = $product_id;
                                    if (in_array($product_id, $cart_products_array)) {
                                        $is_passed['has_fee_based_on_product'] = 'yes';
                                    }
                                }
                            }
                        }
                        if ($product['product_fees_conditions_is'] == 'not_in') {
                            if (!empty($product['product_fees_conditions_values'])) {
                                foreach ($product['product_fees_conditions_values'] as $product_id) {
                                    $notpassed_product = $product_id;
                                    if (in_array($product_id, $cart_product)) {
                                        $is_passed['has_fee_based_on_product'] = 'no';
                                    }
                                }
                            }
                        }
                    }
                    if (empty($is_passed['has_fee_based_on_product']) && empty($passed_product)) {
                        $is_passed['has_fee_based_on_product'] = 'yes';
                    } elseif (empty($is_passed['has_fee_based_on_product']) && !empty($passed_product)) {
                        $is_passed['has_fee_based_on_product'] = 'no';
                    }
                }

                //Check if is Category exist
                if (is_array($category_array) && isset($category_array) && !empty($category_array) && !empty($cart_array)) {

                    $cart_product = $this->fee_array_column($cart_array, 'product_id');
                    $cart_category_id_array = array();
                    $cart_products_array = array();

                    if (isset($cart_product) && !empty($cart_product)) {
                        foreach ($cart_product as $key => $cart_product_id) {
                            if (!empty($sitepress)) {
                                $cart_products_array[] = apply_filters('wpml_object_id', $cart_product_id, 'product', TRUE, $default_lang);
                            } else {
                                $cart_products_array[] = $cart_product_id;
                            }
                        }
                    }

                    if (!empty($cart_products_array)) {
                        foreach ($cart_products_array as $product) {
                            $product_cart_array = new WC_Product($product);
                            if ( !( $product_cart_array->is_virtual('yes') ) ) {
                                $cart_product_category = wp_get_post_terms($product, 'product_cat', array('fields' => 'ids'));
                                if (isset($cart_product_category) && !empty($cart_product_category) && is_array($cart_product_category)) {
                                    $cart_category_id_array[] = $cart_product_category;
                                }
                            }
                        }
                        $get_cat_all = array_unique($this->array_flatten($cart_category_id_array));
                        $is_passed['has_fee_based_on_category'] = '';
                        $passed_category = array();
                        $notpassed_category = array();
                        foreach ($category_array as $category) {
                            if ($category['product_fees_conditions_is'] == 'is_equal_to') {
                                if (!empty($category['product_fees_conditions_values'])) {
                                    foreach ($category['product_fees_conditions_values'] as $category_id) {
                                        $passed_category[] = $category_id;
                                        if (in_array($category_id, $get_cat_all)) {
                                            $is_passed['has_fee_based_on_category'] = 'yes';
                                        }
                                    }
                                }
                            }
                            if ($category['product_fees_conditions_is'] == 'not_in') {
                                if (!empty($category['product_fees_conditions_values'])) {
                                    foreach ($category['product_fees_conditions_values'] as $category_id) {
                                        $notpassed_category[] = $category_id;
                                        if (in_array($category_id, $get_cat_all)) {
                                            $is_passed['has_fee_based_on_category'] = 'no';
                                        }
                                    }
                                }
                            }
                        }
                        if (empty($is_passed['has_fee_based_on_category']) && empty($passed_category)) {
                            $is_passed['has_fee_based_on_category'] = 'yes';
                        } elseif (empty($is_passed['has_fee_based_on_category']) && !empty($passed_category)) {
                            $is_passed['has_fee_based_on_category'] = 'no';
                        }
                    }
                }

                //Check if is tag exist
                if (is_array($tag_array) && isset($tag_array) && !empty($tag_array) && !empty($cart_array)) {
                    $cart_product = $this->fee_array_column($cart_array, 'product_id');
                    $tagid = array();
                    $is_passed['has_fee_based_on_tag'] = '';
                    $passed_tag = array();
                    $notpassed_tag = array();
                    $cart_products_array = array();

                    if (isset($cart_product) && !empty($cart_product)) {
                        foreach ($cart_product as $key => $cart_product_id) {
                            if (!empty($sitepress)) {
                                $cart_products_array[] = apply_filters('wpml_object_id', $cart_product_id, 'product', TRUE, $default_lang);
                            } else {
                                $cart_products_array[] = $cart_product_id;
                            }
                        }
                    }

                    foreach ($cart_products_array as $product) {
	                    $product_cart_array = new WC_Product($product);
                        if ( !( $product_cart_array->is_virtual('yes') ) ) {
                            $cart_product_tag = wp_get_post_terms($product, 'product_tag', array('fields' => 'ids'));
                            if (isset($cart_product_tag) && !empty($cart_product_tag) && is_array($cart_product_tag)) {
                                $tagid[] = $cart_product_tag;
                            }
                        }
                    }
                    
                    $get_tag_all = array_unique($this->array_flatten($tagid));
                    foreach ($tag_array as $tag) {
                        if ($tag['product_fees_conditions_is'] == 'is_equal_to') {
                            if (!empty($tag['product_fees_conditions_values'])) {
                                foreach ($tag['product_fees_conditions_values'] as $tag_id) {
                                    $passed_tag[] = $tag_id;
                                    if (in_array($tag_id, $get_tag_all)) {
                                        $is_passed['has_fee_based_on_tag'] = 'yes';
                                    }
                                }
                            }
                        }
                        if ($tag['product_fees_conditions_is'] == 'not_in') {
                            if (!empty($tag['product_fees_conditions_values'])) {
                                foreach ($tag['product_fees_conditions_values'] as $tag_id) {
                                    $notpassed_tag[] = $tag_id;
                                    if (in_array($tag_id, $get_tag_all)) {
                                        $is_passed['has_fee_based_on_tag'] = 'no';
                                    }
                                }
                            }
                        }
                    }
                    if (empty($is_passed['has_fee_based_on_tag']) && empty($passed_tag)) {
                        $is_passed['has_fee_based_on_tag'] = 'yes';
                    } elseif (empty($is_passed['has_fee_based_on_tag']) && !empty($passed_tag)) {
                        $is_passed['has_fee_based_on_tag'] = 'no';
                    }
                }
                
                //Check if sku exist
                if (is_array($sku_array) && isset($sku_array) && !empty($sku_array) && !empty($cart_array)) {
                    $cart_product = $this->fee_array_column($cart_array, 'product_id');
                    $sku_ids = array();
                    $is_passed['has_fee_based_on_sku'] = '';
                    $passed_sku = array();
                    $notpassed_sku = array();
                    $cart_products_array = array();
                    
                    if (isset($cart_product) && !empty($cart_product)) {
                        foreach ($cart_product as $key => $cart_product_id) {
                            if (!empty($sitepress)) {
                                $cart_products_array[] = apply_filters('wpml_object_id', $cart_product_id, 'product', TRUE, $default_lang);
                            } else {
                                $cart_products_array[] = $cart_product_id;
                            }
                        }
                    }
                    
                    if(!empty($cart_products_array)) {
                        foreach ($cart_products_array as $product_id) {
	                        $product_cart_array = new WC_Product($product_id);
                            if ( !( $product_cart_array->is_virtual('yes') ) ) {
                                $product_sku = $product_cart_array->get_sku();
                                if(isset($product_sku) && !empty($product_sku)) {
                                    $sku_ids[] = $product_sku;
                                }
                            }
                        }
                    }
                    
                    $get_all_unique_sku = array_unique($this->array_flatten($sku_ids));
                    foreach ($sku_array as $sku) {
                        if ($sku['product_fees_conditions_is'] == 'is_equal_to') {
                            if (!empty($sku['product_fees_conditions_values'])) {
                                foreach ($sku['product_fees_conditions_values'] as $sku_name) {
                                    $passed_sku[] = $sku_name;
                                    if (in_array($sku_name, $get_all_unique_sku)) {
                                        $is_passed['has_fee_based_on_sku'] = 'yes';
                                    }
                                }
                            }
                        }
                        if ($sku['product_fees_conditions_is'] == 'not_in') {
                            if (!empty($sku['product_fees_conditions_values'])) {
                                foreach ($sku['product_fees_conditions_values'] as $sku_name) {
                                    $notpassed_sku[] = $sku_name;
                                    if (in_array($sku_name, $get_all_unique_sku)) {
                                        $is_passed['has_fee_based_on_sku'] = 'no';
                                    }
                                }
                            }
                        }
                    }
                    if (empty($is_passed['has_fee_based_on_sku']) && empty($passed_sku)) {
                        $is_passed['has_fee_based_on_sku'] = 'yes';
                    } elseif (empty($is_passed['has_fee_based_on_sku']) && !empty($passed_sku)) {
                        $is_passed['has_fee_based_on_sku'] = 'no';
                    }
                }
                
                //Check if is user exist
                if (is_array($user_array) && isset($user_array) && !empty($user_array) && !empty($cart_array)) {
                    if (!is_user_logged_in()) {
                        return false;
                    }
                    $current_user_id = get_current_user_id();
                    foreach ($user_array as $user) {
                        if ($user['product_fees_conditions_is'] == 'is_equal_to') {
                            if (in_array($current_user_id, $user['product_fees_conditions_values'])) {
                                $is_passed['has_fee_based_on_user'] = 'yes';
                            } else {
                                $is_passed['has_fee_based_on_user'] = 'no';
                            }
                        }
                        if ($user['product_fees_conditions_is'] == 'not_in') {
                            if (in_array($current_user_id, $user['product_fees_conditions_values'])) {
                                $is_passed['has_fee_based_on_user'] = 'no';
                            } else {
                                $is_passed['has_fee_based_on_user'] = 'yes';
                            }
                        }
                    }
                }

                //Check if is user role exist
                if (is_array($user_role_array) && isset($user_role_array) && !empty($user_role_array) && !empty($cart_array)) {
                    /**
                     * check user loggedin or not
                     */
                    global $current_user;
                    if (is_user_logged_in()) {
                        $current_user_role = $current_user->roles[0];
                    } else {
                        $current_user_role = 'guest';
                    }
                    foreach ($user_role_array as $user_role) {
                        if ($user_role['product_fees_conditions_is'] == 'is_equal_to') {
                            if (in_array($current_user_role, $user_role['product_fees_conditions_values'])) {
                                $is_passed['has_fee_based_on_user_role'] = 'yes';
                            } else {
                                $is_passed['has_fee_based_on_user_role'] = 'no';
                            }
                        }
                        if ($user_role['product_fees_conditions_is'] == 'not_in') {
                            if (in_array($current_user_role, $user_role['product_fees_conditions_values'])) {
                                $is_passed['has_fee_based_on_user_role'] = 'no';
                            } else {
                                $is_passed['has_fee_based_on_user_role'] = 'yes';
                            }
                        }
                    }
                }

                //Check if is coupon exist
                if (is_array($coupon_array) && isset($coupon_array) && !empty($coupon_array) && !empty($cart_array)) {

                    $cart_coupon = '';
                    $couponId = array();
                    $wc_curr_version = $this->afrsm_get_woo_version_number();
                    if ($wc_curr_version >= 3.0) {
                        $cart_coupon = WC()->cart->get_coupons();
                    } else {
                        $cart_coupon = isset($woocommerce->cart->coupons) && !empty($woocommerce->cart->coupons) ? $woocommerce->cart->coupons : array();
                    }
                    if (!empty($cart_coupon)) {
                        foreach ($cart_coupon as $cartCoupon) {
                            if ($cartCoupon->is_valid() && isset($cartCoupon) && !empty($cartCoupon)) {
                                if ($wc_curr_version >= 3.0) {
                                    $couponId[] = $cartCoupon->get_id();
                                } else {
                                    $couponId[] = $cartCoupon->id;
                                }
                            }
                        }
                        $is_passed['has_fee_based_on_coupon'] = '';
                        $passed_coupon = array();
                        $notpassed_coupon = array();
                        foreach ($coupon_array as $coupon) {
                            if ($coupon['product_fees_conditions_is'] == 'is_equal_to') {
                                if (!empty($coupon['product_fees_conditions_values'])) {
                                    foreach ($coupon['product_fees_conditions_values'] as $coupon_id) {
                                        $passed_coupon[] = $coupon_id;
                                        if (in_array($coupon_id, $couponId)) {
                                            $is_passed['has_fee_based_on_coupon'] = 'yes';
                                        }
                                    }
                                }
                            }
                            if ($coupon['product_fees_conditions_is'] == 'not_in') {
                                if (!empty($coupon['product_fees_conditions_values'])) {
                                    foreach ($coupon['product_fees_conditions_values'] as $coupon_id) {
                                        $notpassed_coupon[] = $coupon_id;
                                        if (in_array($coupon_id, $couponId)) {
                                            $notpassed_coupon_flag = 1;
                                            $is_passed['has_fee_based_on_coupon'] = 'no';
                                        }
                                    }
                                }
                            }
                        }

                        if (empty($is_passed['has_fee_based_on_coupon']) && empty($passed_coupon)) {
                            $is_passed['has_fee_based_on_coupon'] = 'yes';
                        } elseif (empty($is_passed['has_fee_based_on_coupon']) && !empty($passed_coupon)) {
                            $is_passed['has_fee_based_on_coupon'] = 'no';
                        }
                    }
                }

                //Check if is Cart Subtotal (Before Discount) exist
                if (is_array($cart_total_array) && isset($cart_total_array) && !empty($cart_total_array) && !empty($cart_array)) {
                    $total = $woocommerce->cart->subtotal;
                    if (isset($woocommerce_wpml) && !empty($woocommerce_wpml->multi_currency)) {
                        $new_total = $woocommerce_wpml->multi_currency->prices->unconvert_price_amount($total);
                    } else {
                        $new_total = $total;
                    }

                    foreach ($cart_total_array as $cart_total) {
                        if ($cart_total['product_fees_conditions_is'] == 'is_equal_to') {
                            if (!empty($cart_total['product_fees_conditions_values'])) {
                                if ($cart_total['product_fees_conditions_values'] == $new_total) {
                                    $is_passed['has_fee_based_on_cart_total'] = 'yes';
                                } else {
                                    $is_passed['has_fee_based_on_cart_total'] = 'no';
                                    break;
                                }
                            }
                        }
                        if ($cart_total['product_fees_conditions_is'] == 'less_equal_to') {
                            if (!empty($cart_total['product_fees_conditions_values'])) {
                                if ($cart_total['product_fees_conditions_values'] >= $new_total) {
                                    $is_passed['has_fee_based_on_cart_total'] = 'yes';
                                } else {
                                    $is_passed['has_fee_based_on_cart_total'] = 'no';
                                    break;
                                }
                            }
                        }
                        if ($cart_total['product_fees_conditions_is'] == 'less_then') {
                            if (!empty($cart_total['product_fees_conditions_values'])) {
                                if ($cart_total['product_fees_conditions_values'] > $new_total) {
                                    $is_passed['has_fee_based_on_cart_total'] = 'yes';
                                } else {
                                    $is_passed['has_fee_based_on_cart_total'] = 'no';
                                    break;
                                }
                            }
                        }
                        if ($cart_total['product_fees_conditions_is'] == 'greater_equal_to') {
                            if (!empty($cart_total['product_fees_conditions_values'])) {
                                if ($cart_total['product_fees_conditions_values'] <= $new_total) {
                                    $is_passed['has_fee_based_on_cart_total'] = 'yes';
                                } else {
                                    $is_passed['has_fee_based_on_cart_total'] = 'no';
                                    break;
                                }
                            }
                        }
                        if ($cart_total['product_fees_conditions_is'] == 'greater_then') {
                            if (!empty($cart_total['product_fees_conditions_values'])) {
                                if ($cart_total['product_fees_conditions_values'] < $new_total) {
                                    $is_passed['has_fee_based_on_cart_total'] = 'yes';
                                } else {
                                    $is_passed['has_fee_based_on_cart_total'] = 'no';
                                    break;
                                }
                            }
                        }
                        if ($cart_total['product_fees_conditions_is'] == 'not_in') {
                            if (!empty($cart_total['product_fees_conditions_values'])) {
                                if ($new_total == $cart_total['product_fees_conditions_values']) {
                                    $is_passed['has_fee_based_on_cart_total'] = 'no';
                                    break;
                                } else {
                                    $is_passed['has_fee_based_on_cart_total'] = 'yes';
                                }
                            }
                        }
                    }
                }

                //Check if is Cart Subtotal (After Discount) exist
                if (is_array($cart_totalafter_array) && isset($cart_totalafter_array) && !empty($cart_totalafter_array) && !empty($cart_array)) {
                    $totalprice = $this->afrsm_pro_remove_currency_symbol($woocommerce->cart->get_cart_subtotal());
                    $totaldisc = $this->afrsm_pro_remove_currency_symbol($woocommerce->cart->get_total_discount());
                    $resultprice = $totalprice - $totaldisc;
                    if (isset($woocommerce_wpml) && !empty($woocommerce_wpml->multi_currency)) {
                        $new_resultprice = $woocommerce_wpml->multi_currency->prices->unconvert_price_amount($resultprice);
                    } else {
                        $new_resultprice = $resultprice;
                    }

                    foreach ($cart_totalafter_array as $cart_totalafter) {
                        if ($cart_totalafter['product_fees_conditions_is'] == 'is_equal_to') {
                            if (!empty($cart_totalafter['product_fees_conditions_values'])) {
                                if ($cart_totalafter['product_fees_conditions_values'] == $new_resultprice) {
                                    $is_passed['has_fee_based_on_cart_totalafter'] = 'yes';
                                } else {
                                    $is_passed['has_fee_based_on_cart_totalafter'] = 'no';
                                    break;
                                }
                            }
                        }
                        if ($cart_totalafter['product_fees_conditions_is'] == 'less_equal_to') {
                            if (!empty($cart_totalafter['product_fees_conditions_values'])) {
                                if ($cart_totalafter['product_fees_conditions_values'] >= $new_resultprice) {
                                    $is_passed['has_fee_based_on_cart_totalafter'] = 'yes';
                                } else {
                                    $is_passed['has_fee_based_on_cart_totalafter'] = 'no';
                                    break;
                                }
                            }
                        }
                        if ($cart_totalafter['product_fees_conditions_is'] == 'less_then') {
                            if (!empty($cart_totalafter['product_fees_conditions_values'])) {
                                if ($cart_totalafter['product_fees_conditions_values'] > $new_resultprice) {
                                    $is_passed['has_fee_based_on_cart_totalafter'] = 'yes';
                                } else {
                                    $is_passed['has_fee_based_on_cart_totalafter'] = 'no';
                                    break;
                                }
                            }
                        }
                        if ($cart_totalafter['product_fees_conditions_is'] == 'greater_equal_to') {
                            if (!empty($cart_totalafter['product_fees_conditions_values'])) {
                                if ($cart_totalafter['product_fees_conditions_values'] <= $new_resultprice) {
                                    $is_passed['has_fee_based_on_cart_totalafter'] = 'yes';
                                } else {
                                    $is_passed['has_fee_based_on_cart_totalafter'] = 'no';
                                    break;
                                }
                            }
                        }
                        if ($cart_totalafter['product_fees_conditions_is'] == 'greater_then') {
                            if (!empty($cart_totalafter['product_fees_conditions_values'])) {
                                if ($cart_totalafter['product_fees_conditions_values'] < $new_resultprice) {
                                    $is_passed['has_fee_based_on_cart_totalafter'] = 'yes';
                                } else {
                                    $is_passed['has_fee_based_on_cart_totalafter'] = 'no';
                                    break;
                                }
                            }
                        }
                        if ($cart_totalafter['product_fees_conditions_is'] == 'not_in') {
                            if (!empty($cart_totalafter['product_fees_conditions_values'])) {
                                if ($new_resultprice == $cart_totalafter['product_fees_conditions_values']) {
                                    $is_passed['has_fee_based_on_cart_totalafter'] = 'no';
                                    break;
                                } else {
                                    $is_passed['has_fee_based_on_cart_totalafter'] = 'yes';
                                }
                            }
                        }
                    }
                }

                //Check if is quantity exist
                if (is_array($quantity_array) && isset($quantity_array) && !empty($quantity_array) && !empty($cart_array)) {
                    $woo_cart_array = WC()->cart->get_cart();
                    $quantity_total = 0;

                    foreach ($woo_cart_array as $woo_cart_item_key => $woo_cart_item) {
                        $quantity_total += $woo_cart_item['quantity'];
                    }
                    foreach ($quantity_array as $quantity) {
                        if ($quantity['product_fees_conditions_is'] == 'is_equal_to') {
                            if (!empty($quantity['product_fees_conditions_values'])) {
                                if ($quantity_total == $quantity['product_fees_conditions_values']) {
                                    $is_passed['has_fee_based_on_quantity'] = 'yes';
                                } else {
                                    $is_passed['has_fee_based_on_quantity'] = 'no';
                                    break;
                                }
                            }
                        }
                        if ($quantity['product_fees_conditions_is'] == 'less_equal_to') {
                            if (!empty($quantity['product_fees_conditions_values'])) {
                                if ($quantity['product_fees_conditions_values'] >= $quantity_total) {
                                    $is_passed['has_fee_based_on_quantity'] = 'yes';
                                } else {
                                    $is_passed['has_fee_based_on_quantity'] = 'no';
                                    break;
                                }
                            }
                        }
                        if ($quantity['product_fees_conditions_is'] == 'less_then') {
                            if (!empty($quantity['product_fees_conditions_values'])) {
                                if ($quantity['product_fees_conditions_values'] > $quantity_total) {
                                    $is_passed['has_fee_based_on_quantity'] = 'yes';
                                } else {
                                    $is_passed['has_fee_based_on_quantity'] = 'no';
                                    break;
                                }
                            }
                        }
                        if ($quantity['product_fees_conditions_is'] == 'greater_equal_to') {
                            if (!empty($quantity['product_fees_conditions_values'])) {
                                if ($quantity['product_fees_conditions_values'] <= $quantity_total) {
                                    $is_passed['has_fee_based_on_quantity'] = 'yes';
                                } else {
                                    $is_passed['has_fee_based_on_quantity'] = 'no';
                                    break;
                                }
                            }
                        }
                        if ($quantity['product_fees_conditions_is'] == 'greater_then') {
                            if (!empty($quantity['product_fees_conditions_values'])) {
                                if ($quantity['product_fees_conditions_values'] < $quantity_total) {
                                    $is_passed['has_fee_based_on_quantity'] = 'yes';
                                } else {
                                    $is_passed['has_fee_based_on_quantity'] = 'no';
                                    break;
                                }
                            }
                        }
                        if ($quantity['product_fees_conditions_is'] == 'not_in') {
                            if (!empty($quantity['product_fees_conditions_values'])) {
                                if ($quantity_total == $quantity['product_fees_conditions_values']) {
                                    $is_passed['has_fee_based_on_quantity'] = 'no';
                                    break;
                                } else {
                                    $is_passed['has_fee_based_on_quantity'] = 'yes';
                                }
                            }
                        }
                    }
                }

                //Check if is weight exist
                if (is_array($weight_array) && isset($weight_array) && !empty($weight_array) && !empty($cart_array)) {
                    $woo_cart_array = WC()->cart->get_cart();
                    $woo_cart_item_quantity = 0;
                    $weight_total = 0;

                    foreach ($woo_cart_array as $woo_cart_item_key => $woo_cart_item) {
	                    $product_cart_array = new WC_Product($woo_cart_item['product_id']);
                        if ( !( $product_cart_array->is_virtual('yes') ) ) {
                            $product_weight = $woo_cart_item['data']->get_weight();
                            if ($product_weight != 0) {
                                $woo_cart_item_quantity = $woo_cart_item['quantity'];
                                $weight_total += $product_weight * $woo_cart_item_quantity;
                            }
                        }
                    }
                    
                    foreach ($weight_array as $weight) {
                        if ($weight['product_fees_conditions_is'] == 'is_equal_to') {
                            if (!empty($weight['product_fees_conditions_values'])) {
                                if ($weight_total == $weight['product_fees_conditions_values']) {
                                    $is_passed['has_fee_based_on_weight'] = 'yes';
                                } else {
                                    $is_passed['has_fee_based_on_weight'] = 'no';
                                    break;
                                }
                            }
                        }
                        if ($weight['product_fees_conditions_is'] == 'less_equal_to') {
                            if (!empty($weight['product_fees_conditions_values'])) {
                                if ($weight['product_fees_conditions_values'] >= $weight_total) {
                                    $is_passed['has_fee_based_on_weight'] = 'yes';
                                } else {
                                    $is_passed['has_fee_based_on_weight'] = 'no';
                                    break;
                                }
                            }
                        }
                        if ($weight['product_fees_conditions_is'] == 'less_then') {
                            if (!empty($weight['product_fees_conditions_values'])) {
                                if ($weight['product_fees_conditions_values'] > $weight_total) {
                                    $is_passed['has_fee_based_on_weight'] = 'yes';
                                } else {
                                    $is_passed['has_fee_based_on_weight'] = 'no';
                                    break;
                                }
                            }
                        }
                        if ($weight['product_fees_conditions_is'] == 'greater_equal_to') {
                            if (!empty($weight['product_fees_conditions_values'])) {
                                if ($weight['product_fees_conditions_values'] <= $weight_total) {
                                    $is_passed['has_fee_based_on_weight'] = 'yes';
                                } else {
                                    $is_passed['has_fee_based_on_weight'] = 'no';
                                    break;
                                }
                            }
                        }
                        if ($weight['product_fees_conditions_is'] == 'greater_then') {
                            if (!empty($weight['product_fees_conditions_values'])) {
                                if ($weight_total > $weight['product_fees_conditions_values']) {
                                    $is_passed['has_fee_based_on_weight'] = 'yes';
                                } else {
                                    $is_passed['has_fee_based_on_weight'] = 'no';
                                    break;
                                }
                            }
                        }
                        if ($weight['product_fees_conditions_is'] == 'not_in') {
                            if (!empty($weight['product_fees_conditions_values'])) {
                                if ($weight_total == $weight['product_fees_conditions_values']) {
                                    $is_passed['has_fee_based_on_weight'] = 'no';
                                    break;
                                } else {
                                    $is_passed['has_fee_based_on_weight'] = 'yes';
                                }
                            }
                        }
                    }
                }

                //Check if is shipping class exist
                if (is_array($shipping_class_array) && isset($shipping_class_array) && !empty($shipping_class_array) && !empty($cart_array)) {
                    $shippingclass = array();
                    $passed_shipping_class = array();
                    $notpassed_shipping_class = array();
                    
                    foreach ($cart_array as $cart_item_key => $woo_cart_item) {
	                    $product_cart_array = new WC_Product($woo_cart_item['product_id']);
                        if ( !( $product_cart_array->is_virtual('yes') ) ) {
                            $shipping_classes = get_the_terms($woo_cart_item['product_id'], 'product_shipping_class');
                            if ($shipping_classes) {
                                foreach ($shipping_classes as $shipping_class) {
                                    $shippingclass[] = $shipping_class->slug;
                                }
                            }
                        }
                    }
                    
                    $get_shipping_class_all = array_unique($this->array_flatten($shippingclass));
                    foreach ($shipping_class_array as $shipping_class) {
                        if ($shipping_class['product_fees_conditions_is'] == 'is_equal_to') {
                            if (!empty($shipping_class['product_fees_conditions_values'])) {
                                foreach ($shipping_class['product_fees_conditions_values'] as $shipping_class_id) {
                                    $passed_shipping_class[] = $shipping_class_id;
                                    if (in_array($shipping_class_id, $get_shipping_class_all)) {
                                        $is_passed['has_fee_based_on_shipping_class'] = 'yes';
                                    }
                                }
                            }
                        }
                        if ($shipping_class['product_fees_conditions_is'] == 'not_in') {
                            if (!empty($shipping_class['product_fees_conditions_values'])) {
                                foreach ($shipping_class['product_fees_conditions_values'] as $shipping_class_id) {
                                    $notpassed_shipping_class[] = $shipping_class_id;
                                    if (in_array($shipping_class_id, $get_shipping_class_all)) {
                                        $is_passed['has_fee_based_on_shipping_class'] = 'no';
                                    }
                                }
                            }
                        }
                    }
                    if (empty($is_passed['has_fee_based_on_shipping_class']) && empty($passed_shipping_class)) {
                        $is_passed['has_fee_based_on_shipping_class'] = 'yes';
                    } elseif (empty($is_passed['has_fee_based_on_shipping_class']) && !empty($passed_shipping_class)) {
                        $is_passed['has_fee_based_on_shipping_class'] = 'no';
                    }
                }
            }
        }
        
        if (isset($is_passed) && !empty($is_passed) && is_array($is_passed)) {
            if (!in_array('no', $is_passed)) {
                
                $current_date = strtotime(date('d-m-Y'));
                $sm_start_date = (isset($sm_start_date) && !empty($sm_start_date)) ? strtotime($sm_start_date) : '';
                $sm_end_date = (isset($sm_end_date) && !empty($sm_end_date)) ? strtotime($sm_end_date) : '';
                if (($current_date >= $sm_start_date || $sm_start_date == '') && ($current_date <= $sm_end_date || $sm_end_date == '')) {
                    $final_condition_flag = 1;
                }
            }
        }
        
        if( $final_condition_flag == 1 ) {
            return true;
        } else {
            return false;
        }
        
    }

    public function array_flatten($array) {
        if (!is_array($array)) {
            return FALSE;
        }
        $result = array();
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $result = array_merge($result, $this->array_flatten($value));
            } else {
                $result[$key] = $value;
            }
        }
        return $result;
    }

    /**
     * Find a matching zone for a given package.
     * @since  3.0.0
     * @uses   wc_make_numeric_postcode()
     * @param  object $package
     * @return WC_Shipping_Zone
     */
    public function wc_get_shipping_zone($package) {
        global $wpdb;
        
        $country = $package['destination']['country'];
        $state = $country . ':' . $package['destination']['state'];
        $postcode = $package['destination']['postcode'];
        $valid_postcodes = array('*', $postcode);
        $valid_zone_ids = array();

        // Work out possible valid wildcard postcodes
        $postcode_length = strlen($postcode);
        $wildcard_postcode = $postcode;

        for ($i = 0; $i < $postcode_length; $i ++) {
            $wildcard_postcode = substr($wildcard_postcode, 0, -1);
            $valid_postcodes[] = $wildcard_postcode . '*';
        }

        // Query range based postcodes to find matches
        if ($postcode) {
            $postcode_ranges = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}wcextraflatrate_shipping_zone_locations
                                                    WHERE location_type = 'postcode' AND location_code LIKE '%-%'");

            if ($postcode_ranges) {
                $encoded_postcode = $this->wc_make_numeric_postcode($postcode);
                $encoded_postcode_len = strlen($encoded_postcode);

                foreach ($postcode_ranges as $postcode_range) {
                    $range = array_map('trim', explode('-', $postcode_range->location_code));

                    if (sizeof($range) != 2) {
                        continue;
                    }

                    if (is_numeric($range[0]) && is_numeric($range[1])) {
                        $encoded_postcode = $postcode;
                        $min = $range[0];
                        $max = $range[1];
                    } else {
                        $min = $this->wc_make_numeric_postcode($range[0]);
                        $max = $this->wc_make_numeric_postcode($range[1]);
                        $min = str_pad($min, $encoded_postcode_len, '0');
                        $max = str_pad($max, $encoded_postcode_len, '9');
                    }

                    if ($encoded_postcode >= $min && $encoded_postcode <= $max) {
                        $valid_zone_ids[] = $postcode_range->zone_id;
                    }
                }
            }
        }

        // Get matching zones
        $matching_zone = $wpdb->get_var($wpdb->prepare("SELECT zones.zone_id FROM {$wpdb->prefix}wcextraflatrate_shipping_zones as zones
                                                        LEFT JOIN {$wpdb->prefix}wcextraflatrate_shipping_zone_locations as locations ON zones.zone_id = locations.zone_id
                                                        WHERE (
                                                                (
                                                                    zone_type = 'countries'
                                                                    AND location_type = 'country'
                                                                    AND location_code = %s
                                                                )
                                                                OR
                                                                (
                                                                    zone_type = 'states'
                                                                    AND
                                                                    (
                                                                        ( location_type = 'state' AND location_code = %s )
                                                                        OR
                                                                        ( location_type = 'country' AND location_code = %s )
                                                                    )
                                                                )
                                                                OR
                                                                (
                                                                    zone_type = 'postcodes'
                                                                    AND
                                                                    (
                                                                        ( location_type = 'state' AND location_code = %s )
                                                                        OR
                                                                        ( location_type = 'country' AND location_code = %s )
                                                                    )
                                                                    AND
                                                                    (
                                                                        zones.zone_id IN (
                                                                                SELECT zone_id FROM {$wpdb->prefix}wcextraflatrate_shipping_zone_locations
                                                                                WHERE location_type = 'postcode'
                                                                                AND location_code IN ('" . implode("','", $valid_postcodes) . "')
                                                                                )
                                                                        OR zones.zone_id IN ('" . implode("','", $valid_zone_ids) . "')
                                                                    )
                                                                )
                                                        ) AND zone_enabled = 1
                                                        ORDER BY zone_order ASC
                                                        LIMIT 1", $country, $state, $country, $state, $country));

        return $matching_zone ? $matching_zone : 0;
    }
    
    /**
     * make_numeric_postcode function.
     *
     * Converts letters to numbers so we can do a simple range check on postcodes.
     *
     * E.g. PE30 becomes 16050300 (P = 16, E = 05, 3 = 03, 0 = 00)
     *
     * @access public
     * @param mixed $postcode
     * @return void
     */
    function wc_make_numeric_postcode($postcode) {
        $postcode_length = strlen($postcode);
        $letters_to_numbers = array_merge(array(0), range('A', 'Z'));
        $letters_to_numbers = array_flip($letters_to_numbers);
        $numeric_postcode = '';

        for ($i = 0; $i < $postcode_length; $i ++) {
            if (is_numeric($postcode[$i])) {
                $numeric_postcode .= str_pad($postcode[$i], 2, '0', STR_PAD_LEFT);
            } elseif (isset($letters_to_numbers[$postcode[$i]])) {
                $numeric_postcode .= str_pad($letters_to_numbers[$postcode[$i]], 2, '0', STR_PAD_LEFT);
            } else {
                $numeric_postcode .= '00';
            }
        }

        return $numeric_postcode;
    }
    
    public function fee_array_column(array $input, $columnKey, $indexKey = null) {
        $array = array();
        foreach ($input as $value) {
            if (!isset($value[$columnKey])) {
                trigger_error("Key \"$columnKey\" does not exist in array");
                return false;
            }
            if (is_null($indexKey)) {
                $array[] = $value[$columnKey];
            } else {
                if (!isset($value[$indexKey])) {
                    trigger_error("Key \"$indexKey\" does not exist in array");
                    return false;
                }
                if (!is_scalar($value[$indexKey])) {
                    trigger_error("Key \"$indexKey\" does not contain scalar value");
                    return false;
                }
                $array[$value[$indexKey]] = $value[$columnKey];
            }
        }
        return $array;
    }
    
    /*
     * Remove WooCommerce currency symbol
     * 
     * @since 1.0.0
     */
    public function afrsm_pro_remove_currency_symbol($price) {
        $wc_currency_symbol = get_woocommerce_currency_symbol();
        $new_price  = str_replace($wc_currency_symbol, '', $price);
        $new_price2 = (double) preg_replace('/[^.\d]/', '', $new_price);
        return $new_price2;
    }
    
    /*
     * Get WooCommerce version number
     * 
     * @since 1.0.0
     */
    function afrsm_get_woo_version_number() {
        // If get_plugins() isn't available, require it
	if ( ! function_exists( 'get_plugins' ) )
		require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	
        // Create the plugins folder and file variables
	$plugin_folder = get_plugins( '/' . 'woocommerce' );
	$plugin_file = 'woocommerce.php';
	
	// If the plugin version number is set, return it 
	if ( isset( $plugin_folder[$plugin_file]['Version'] ) ) {
		return $plugin_folder[$plugin_file]['Version'];

	} else {
	// Otherwise return null
		return NULL;
	}
    }
    
    /**
     * Function for save master settings data
     * 
     * @since 1.0.0
     */
    public function afrsm_pro_sm_sort_order() {
        global $wpdb;
        $smOrderArray = !empty($_REQUEST['smOrderArray']) ? $_REQUEST['smOrderArray'] : '';
        if (isset($smOrderArray) && !empty($smOrderArray)) {
            update_option('sm_sortable_order', $smOrderArray);
        }
        wp_die();
    }
    
    /**
     * Function for save master settings data
     * 
     * @since 1.0.0
     */
    public function afrsm_pro_save_master_settings() {
        $what_to_do = !empty($_REQUEST['what_to_do']) ? $_REQUEST['what_to_do'] : '';
        $shipping_display_mode = !empty($_REQUEST['shipping_display_mode']) ? $_REQUEST['shipping_display_mode'] : '';
        
        if (isset($what_to_do) && !empty($what_to_do)) {
            update_option('what_to_do_method', $what_to_do);
        }
        if (isset($shipping_display_mode) && !empty($shipping_display_mode)) {
            update_option('md_woocommerce_shipping_method_format', $shipping_display_mode);
        }
        wp_die();
    }
    
    public function afrsm_pro_product_fees_conditions_values_ajax() {
        
        $condition = isset($_POST['condition']) ? $_POST['condition'] : '';
        $count = isset($_POST['count']) ? $_POST['count'] : '';
        $html = '';
        
        if ($condition == 'country') {
            $html .= $this->afrsm_pro_get_country_list($count);
        } elseif ($condition == 'state') {
            $html .= $this->afrsm_pro_get_states_list($count);
        } elseif ($condition == 'postcode') {
            $html .= '<textarea name="fees[product_fees_conditions_values][value_' . $count . ']"></textarea>';
        } elseif ($condition == 'zone') {
            $html .= $this->afrsm_pro_get_zones_list($count);
        } elseif ($condition == 'product') {
            $html .= $this->afrsm_pro_get_product_list($count);
        }elseif ($condition == 'variableproduct') {
	        $html .= $this->afrsm_pro_get_varible_product_list($count);
        }elseif ($condition == 'category') {
            $html .= $this->afrsm_pro_get_category_list($count);
        } elseif ($condition == 'tag') {
            $html .= $this->afrsm_pro_get_tag_list($count);
        } elseif ($condition == 'sku') {
            $html .= $this->afrsm_pro_get_sku_list($count);
        } elseif ($condition == 'user') {
            $html .= $this->afrsm_pro_get_user_list($count);
        } elseif ($condition == 'user_role') {
            $html .= $this->afrsm_pro_get_user_role_list($count);
        } elseif ($condition == 'cart_total') {
            $html .= '<input type="text" name="fees[product_fees_conditions_values][value_' . $count . ']" id="product_fees_conditions_values" class="product_fees_conditions_values" value="">';
        } elseif ($condition == 'cart_totalafter') {
            $html .= '<input type="text" name="fees[product_fees_conditions_values][value_' . $count . ']" id="product_fees_conditions_values" class="product_fees_conditions_values" value="">';
        } elseif ($condition == 'quantity') {
            $html .= '<input type="text" name="fees[product_fees_conditions_values][value_' . $count . ']" id="product_fees_conditions_values" class="product_fees_conditions_values" value="">';
        } elseif ($condition == 'weight') {
            $html .= '<input type="text" name="fees[product_fees_conditions_values][value_' . $count . ']" id="product_fees_conditions_values" class="product_fees_conditions_values" value="">';
        } elseif ($condition == 'coupon') {
            $html .= $this->afrsm_pro_get_coupon_list($count);
        } elseif ($condition == 'shipping_class') {
            $html .= $this->afrsm_pro_get_advance_flat_rate_class($count);
        }
        echo $html;
        wp_die(); // this is required to terminate immediately and return a proper response
    }

	/**
	 * Function for select country list
	 *
	 * @param string $count
	 * @param array $selected
	 *
	 * @return string
	 */
    public function afrsm_pro_get_country_list($count = '', $selected = array()) {
        
        $countries_obj = new WC_Countries();
        $getCountries = $countries_obj->__get('countries');
        $html = '<select name="fees[product_fees_conditions_values][value_' . $count . '][]" class="product_fees_conditions_values multiselect2 product_fees_conditions_values_country" multiple="multiple">';
        if (!empty($getCountries)) {
            foreach ($getCountries as $code => $country) {
                $selectedVal = is_array($selected) && !empty($selected) && in_array($code, $selected) ? 'selected=selected' : '';
                $html .= '<option value="' . $code . '" ' . $selectedVal . '>' . $country . '</option>';
            }
        }

        $html .= '</select>';
        return $html;
    }

	/**
	 * Get the states for a country.
	 *
	 * @param string $count
	 * @param array $selected
	 *
	 * @return string of states
	 */
    public function afrsm_pro_get_states_list($count = '', $selected = array()) {
        
        $countries = WC()->countries->get_allowed_countries();
        $html = '<select name="fees[product_fees_conditions_values][value_' . $count . '][]" class="product_fees_conditions_values multiselect2 product_fees_conditions_values_state" multiple="multiple">';
        foreach ($countries as $key => $val) {
            if ($states = WC()->countries->get_states($key)) {
                foreach ($states as $state_key => $state_value) {
                    $selectedVal = is_array($selected) && !empty($selected) && in_array(esc_attr($key . ':' . $state_key), $selected) ? 'selected=selected' : '';
                    $html .= '<option value="' . esc_attr($key . ':' . $state_key) . '" ' . $selectedVal . '>' . esc_html($val . ' -> ' . $state_value) . '</option>';
                }
            }
        }
        $html .= '</select>';
        return $html;
    }

    /**
     * Function to get all zones list
     *
     */
    public static function afrsm_pro_get_zones_list($count = '', $selected = array()) {
        global $wpdb;

        $raw_zones = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}wcextraflatrate_shipping_zones ORDER BY zone_order ASC");
        $html = '<select rel-id="' . $count . '" name="fees[product_fees_conditions_values][value_' . $count . '][]" class="product_fees_conditions_values multiselect2" multiple="multiple">';
        if (isset($raw_zones) && !empty($raw_zones)) {
            foreach ($raw_zones as $zone) {
                $selectedVal = is_array($selected) && !empty($selected) && in_array($zone->zone_id, $selected) ? 'selected=selected' : '';
                $html .= '<option value="' . $zone->zone_id . '" ' . $selectedVal . '>' . $zone->zone_name . '</option>';
            }
        }
        $html .= '</select>';
        return $html;
    }

    /**
     * Function for select product list
     *
     */
    public function afrsm_pro_get_product_list($count = '', $selected = array()) {
        global $sitepress;

        if (!empty($sitepress)) {
            $default_lang = $sitepress->get_default_language();
        }

        $get_all_products = new WP_Query(array(
            'post_type' => 'product',
            'post_status' => 'publish',
            'posts_per_page' => -1
        ));

        $html = '<select id="product-filter" rel-id="' . $count . '" name="fees[product_fees_conditions_values][value_' . $count . '][]" class="product_fees_conditions_values multiselect2" multiple="multiple">';
        if (isset($get_all_products->posts) && !empty($get_all_products->posts)) {

            foreach ($get_all_products->posts as $get_all_product) {

                if (!empty($sitepress)) {
                    $new_product_id = apply_filters('wpml_object_id', $get_all_product->ID, 'product', TRUE, $default_lang);
                } else {
                    $new_product_id = $get_all_product->ID;
                }

                $selectedVal = is_array($selected) && !empty($selected) && in_array($new_product_id, $selected) ? 'selected=selected' : '';
                if ($selectedVal != '') {
                    $html .= '<option value="' . $new_product_id . '" ' . $selectedVal . '>' . '#' . $new_product_id . ' - ' . get_the_title($new_product_id) . '</option>';
                }
            }
        }
        $html .= '</select>';
        return $html;
    }
	/**
	 * Function for select product list
	 *
	 */
	public function afrsm_pro_get_varible_product_list($count = '', $selected = array()) {
		global $sitepress;

		if (!empty($sitepress)) {
			$default_lang = $sitepress->get_default_language();
		}
		if (!empty($sitepress)) {
			$default_lang = $sitepress->get_default_language();
		}
		$get_all_products = new WP_Query(array(
			'post_type' => 'product',
			'post_status' => 'publish',
			'posts_per_page' => -1
		));
		$html = '<select id="var-product-filter" rel-id="' . $count . '" name="fees[product_fees_conditions_values][value_' . $count . '][]" class="product_fees_conditions_values multiselect2" multiple="multiple">';
		if (!empty($get_all_products->posts)) {
			foreach ($get_all_products->posts as $post) {

				$_product = wc_get_product($post->ID);

				if ($_product->is_type('variable')) {
					$args = array(
						'post_parent' => $post->ID,
						'post_type' => 'product_variation',
						'numberposts' => -1,
					);
					$variations = $_product->get_available_variations();
					foreach ($variations as $key => $value) {


						if (!empty($sitepress)) {
							$new_product_id = apply_filters('wpml_object_id', $value['variation_id'], 'product', TRUE, $default_lang);
						} else {
							$new_product_id = $value['variation_id'];
						}
						$selectedVal = is_array($selected) && !empty($selected) && in_array($new_product_id, $selected) ? 'selected=selected' : '';
						if ($selectedVal != '') {
							$html .= '<option value="' . $new_product_id . '" ' . $selectedVal . '>' . '#' . $new_product_id . ' - ' . get_the_title($new_product_id) . '</option>';
						}
					}
				}
			}
		}
		$html .= '</select>';
		return $html;
	}


	/**
     * Function for select cat list
     *
     */
    public function afrsm_pro_get_category_list($count = '', $selected = array()) {

        global $sitepress;

        $taxonomy = 'product_cat';
        $post_status = 'publish';
        $orderby = 'name';
        $hierarchical = 1;      // 1 for yes, 0 for no
        $empty = 0;

        if (!empty($sitepress)) {
            $default_lang = $sitepress->get_default_language();
        }

        $args = array(
            'post_type' => 'product',
            'post_status' => $post_status,
            'taxonomy' => $taxonomy,
            'orderby' => $orderby,
            'hierarchical' => $hierarchical,
            'hide_empty' => $empty,
            'posts_per_page' => -1
        );
        $get_all_categories = get_categories($args);
        $html = '<select rel-id="' . $count . '" name="fees[product_fees_conditions_values][value_' . $count . '][]" class="product_fees_conditions_values multiselect2" multiple="multiple">';
        if (isset($get_all_categories) && !empty($get_all_categories)) {
            foreach ($get_all_categories as $get_all_category) {

                if (!empty($sitepress)) {
                    $new_cat_id = apply_filters('wpml_object_id', $get_all_category->term_id, 'product_cat', TRUE, $default_lang);
                } else {
                    $new_cat_id = $get_all_category->term_id;
                }

                $selectedVal = is_array($selected) && !empty($selected) && in_array($new_cat_id, $selected) ? 'selected=selected' : '';
                $category = get_term_by('id', $new_cat_id, 'product_cat');
                $parent_category = get_term_by('id', $category->parent, 'product_cat');

                if ($category->parent > 0) {
                    $html .= '<option value=' . $category->term_id . ' ' . $selectedVal . '>' . '#' . $parent_category->name . '->' . $category->name . '</option>';
                } else {
                    $html .= '<option value=' . $category->term_id . ' ' . $selectedVal . '>' . $category->name . '</option>';
                }
            }
        }
        $html .= '</select>';
        return $html;
    }

    /**
     * Function for select tag list
     *
     */
    public function afrsm_pro_get_tag_list($count = '', $selected = array()) {

        global $sitepress;

        $taxonomy = 'product_tag';
        $orderby = 'name';
        $hierarchical = 1;      // 1 for yes, 0 for no
        $empty = 0;

        if (!empty($sitepress)) {
            $default_lang = $sitepress->get_default_language();
        }

        $args = array(
            'post_type' => 'product',
            'post_status' => 'publish',
            'taxonomy' => $taxonomy,
            'orderby' => $orderby,
            'hierarchical' => $hierarchical,
            'hide_empty' => $empty,
            'posts_per_page' => -1
        );

        $get_all_tags = get_categories($args);

        $html = '<select rel-id="' . $count . '" name="fees[product_fees_conditions_values][value_' . $count . '][]" class="product_fees_conditions_values multiselect2" multiple="multiple">';
        if (isset($get_all_tags) && !empty($get_all_tags)) {
            foreach ($get_all_tags as $get_all_tag) {

                if (!empty($sitepress)) {
                    $new_tag_id = apply_filters('wpml_object_id', $get_all_tag->term_id, 'product_tag', TRUE, $default_lang);
                } else {
                    $new_tag_id = $get_all_tag->term_id;
                }

                $selectedVal = is_array($selected) && !empty($selected) && in_array($new_tag_id, $selected) ? 'selected=selected' : '';
                $tag = get_term_by('id', $new_tag_id, 'product_tag');

                $html .= '<option value="' . $tag->term_id . '" ' . $selectedVal . '>' . $tag->name . '</option>';
            }
        }
        $html .= '</select>';
        return $html;
    }

    /**
     * Function for select SKU list
     */
    public function afrsm_pro_get_sku_list($count = '', $selected = array()) {

        global $product;
        $product_args = array(
            'post_type' => 'product',
            'post_status' => 'publish',
            'posts_per_page' => -1
        );
        $products_array = get_posts($product_args);
        
        $html = '<select rel-id="' . $count . '" name="fees[product_fees_conditions_values][value_' . $count . '][]" class="product_fees_conditions_values multiselect2" multiple="multiple">';
        if (!empty($products_array)) {
            foreach ($products_array as $product) {
                $product_sku = get_post_meta($product->ID, '_sku', true);
                
                $selectedVal = is_array($selected) && !empty($selected) && in_array($product_sku, $selected) ? 'selected=selected' : '';
                $html .= '<option value="' . $product_sku . '" ' . $selectedVal . '>' . $product_sku . '</option>';
            }
        }
        $html .= '</select>';
        return $html;
    }

    /**
     * Function for select user list
     *
     */
    public function afrsm_pro_get_user_list($count = '', $selected = array()) {

        $get_all_users = get_users();

        $html = '<select rel-id="' . $count . '" name="fees[product_fees_conditions_values][value_' . $count . '][]" class="product_fees_conditions_values multiselect2" multiple="multiple">';
        if (isset($get_all_users) && !empty($get_all_users)) {
            foreach ($get_all_users as $get_all_user) {
                $selectedVal = is_array($selected) && !empty($selected) && in_array($get_all_user->data->ID, $selected) ? 'selected=selected' : '';
                $html .= '<option value="' . $get_all_user->data->ID . '" ' . $selectedVal . '>' . $get_all_user->data->user_login . '</option>';
            }
        }
        $html .= '</select>';
        return $html;
    }

    /**
     * Get User role list
     *
     * @return unknown
     */
    public function afrsm_pro_get_user_role_list($count = '', $selected = array()) {

        global $wp_roles;

        $html = '<select rel-id="' . $count . '" name="fees[product_fees_conditions_values][value_' . $count . '][]" class="product_fees_conditions_values multiselect2" multiple="multiple">';
        if (isset($wp_roles->roles) && !empty($wp_roles->roles)) {
            $defaultSel = !empty($selected) && in_array('guest', $selected) ? 'selected=selected' : '';
            $html .= '<option value="guest" ' . $defaultSel . '>Guest</option>';
            foreach ($wp_roles->roles as $user_role_key => $get_all_role) {
                $selectedVal = is_array($selected) && !empty($selected) && in_array($user_role_key, $selected) ? 'selected=selected' : '';
                $html .= '<option value="' . $user_role_key . '" ' . $selectedVal . '>' . $get_all_role['name'] . '</option>';
            }
        }
        $html .= '</select>';
        return $html;
    }

    /**
     * Function for get Coupon list
     *
     */
    public function afrsm_pro_get_coupon_list($count = '', $selected = array()) {

        $get_all_coupon = new WP_Query(array(
            'post_type' => 'shop_coupon',
            'post_status' => 'publish',
            'posts_per_page' => -1
        ));
        $html = '<select rel-id="' . $count . '" name="fees[product_fees_conditions_values][value_' . $count . '][]" class="product_fees_conditions_values multiselect2" multiple="multiple">';
        if (isset($get_all_coupon->posts) && !empty($get_all_coupon->posts)) {
            foreach ($get_all_coupon->posts as $get_all_coupon) {
                $selectedVal = is_array($selected) && !empty($selected) && in_array($get_all_coupon->ID, $selected) ? 'selected=selected' : '';
                $html .= '<option value="' . $get_all_coupon->ID . '" ' . $selectedVal . '>' . $get_all_coupon->post_title . '</option>';
            }
        }
        $html .= '</select>';
        return $html;
    }

    /**
     * Function to get all shipping class name
     *
     */
    public function afrsm_pro_get_advance_flat_rate_class($count = '', $selected = array()) {

        $shipping_classes = WC()->shipping->get_shipping_classes();
        $html = '<select rel-id="' . $count . '" name="fees[product_fees_conditions_values][value_' . $count . '][]" class="product_fees_conditions_values multiselect2">';
        $html .= '<option value="">Select Class</option>';
        if (isset($shipping_classes) && !empty($shipping_classes)) {
            foreach ($shipping_classes as $shipping_classes_key) {
                $selectedVal = !empty($selected) && in_array($shipping_classes_key->slug, $selected) ? 'selected=selected' : '';
                $html .= '<option value="' . $shipping_classes_key->slug . '" ' . $selectedVal . '>' . $shipping_classes_key->name . '</option>';
            }
        }
        $html .= '</select>';
        return $html;
    }
    
    public function afrsm_pro_product_fees_conditions_values_product_ajax() {
        global $sitepress;
        $post_value = isset($_REQUEST['value']) ? $_REQUEST['value'] : '';
        
        $baselang_product_ids = array();

        if (!empty($sitepress)) {
            $default_lang = $sitepress->get_default_language();
        }

        function afrsm_posts_where($where, &$wp_query) {
            global $wpdb;
            if ($search_term = $wp_query->get('search_pro_title')) {
                $search_term_like = $wpdb->esc_like($search_term);
                $where .= ' AND ' . $wpdb->posts . '.post_title LIKE \'%' . esc_sql($search_term_like) . '%\'';
            }
            return $where;
        }

        $product_args = array(
            'post_type' => 'product',
            'posts_per_page' => -1,
            'search_pro_title' => $post_value,
            'post_status' => 'publish',
            'orderby' => 'title',
            'order' => 'ASC'
        );

        add_filter('posts_where', 'afrsm_posts_where', 10, 2);
        $wp_query = new WP_Query($product_args);
        remove_filter('posts_where', 'afrsm_posts_where', 10, 2);

        $get_all_products = $wp_query->posts;

        if (isset($get_all_products) && !empty($get_all_products)) {
            foreach ($get_all_products as $get_all_product) {
                if (!empty($sitepress)) {
                    $defaultlang_product_id = apply_filters('wpml_object_id', $get_all_product->ID, 'product', TRUE, $default_lang);
                } else {
                    $defaultlang_product_id = $get_all_product->ID;
                }
                $baselang_product_ids[] = $defaultlang_product_id;
            }
        }

        $html = '';
        if (isset($baselang_product_ids) && !empty($baselang_product_ids)) {
            foreach ($baselang_product_ids as $baselang_product_id) {
                $html .= '<option value="' . $baselang_product_id . '">' . '#' . $baselang_product_id . ' - ' . get_the_title($baselang_product_id) . '</option>';
            }
        }
        echo $html;
        wp_die();
    }

	public function afrsm_pro_product_fees_conditions_varible_values_product_ajax() {

		global $wpdb, $post, $sitepress;
		$post_value = isset($_REQUEST['value']) ? $_REQUEST['value'] : '';

		$baselang_product_ids = array();

		if (!empty($sitepress)) {
			$default_lang = $sitepress->get_default_language();
		}

		function wcpfc_posts_wheres($where, &$wp_query) {
			global $wpdb;
			if ($search_term = $wp_query->get('search_pro_title')) {
                            $search_term_like = $wpdb->esc_like($search_term);
			    $where .= ' AND ' . $wpdb->posts . '.post_title LIKE \'%' . esc_sql($search_term_like) . '%\'';
			}
			return $where;
		}

		$product_args = array(
			'post_type' => 'product',
			'posts_per_page' => -1,
			'search_pro_title' => $post_value,
			'post_status' => 'publish',
			'orderby' => 'title',
			'order' => 'ASC'
		);


		$get_all_products = new WP_Query($product_args);

		if (!empty($get_all_products)){
			foreach ($get_all_products->posts as $get_all_product){
				$_product = wc_get_product($get_all_product->ID);
				if ($_product->is_type('variable')) {
					$args = array(
						'post_parent' => $get_all_product->ID,
						'post_type'   => 'product_variation',
						'numberposts' => -1,
					);
					$variations = $_product->get_available_variations();
					foreach ($variations as $key => $value) {
						if( !empty($sitepress) ) {
							$defaultlang_product_id = apply_filters( 'wpml_object_id', $value['variation_id'], 'product', TRUE, $default_lang );
						} else {
							$defaultlang_product_id = $value['variation_id'];
						}
						$baselang_product_ids[]    = $defaultlang_product_id;
					}
				}
			}
		}
		$html = '';
		if (isset($baselang_product_ids) && !empty($baselang_product_ids)) {
			foreach ($baselang_product_ids as $baselang_product_id) {
				$html .= '<option value="' . $baselang_product_id . '">' . '#' . $baselang_product_id . ' - ' . get_the_title($baselang_product_id) . '</option>';
			}
		}
		echo $html;
		wp_die();
	}
    /**
     * Function for delete multiple shipping method
     */
    public function afrsm_pro_wc_multiple_delete_shipping_method() {
        $result = 0;
        $allVals = !empty($_POST['allVals']) ? $_POST['allVals'] : array();
        if (!empty($allVals)) {
            foreach ($allVals as $val) {
                wp_delete_post($val);
                $result = 1;
            }
        }
        echo $result;
        wp_die();
    }

    function afrsm_pro_fees_conditions_save($post) {
        if (empty($post)) {
            return false;
        }
        if (isset($_POST['post_type']) && $_POST['post_type'] == 'wc_afrsm') {
            if ($post['fee_post_id'] == '') {
                $fee_post = array(
                    'post_title' => $post['fee_settings_product_fee_title'],
                    'post_status' => 'publish',
                    'post_type' => 'wc_afrsm',
                );
                $post_id = wp_insert_post($fee_post);
            } else {
                $fee_post = array(
                    'ID' => $post['fee_post_id'],
                    'post_title' => $post['fee_settings_product_fee_title'],
                    'post_status' => 'publish'
                );
                $post_id = wp_update_post($fee_post);
            }
            
            if (isset($post['sm_status'])) {
                update_post_meta($post_id, 'sm_status', 'on');
            } else {
                update_post_meta($post_id, 'sm_status', 'off');
            }
            if (isset($post['sm_product_cost'])) {
                update_post_meta($post_id, 'sm_product_cost', esc_attr($post['sm_product_cost']));
            }
	        /* Apply per quantity postmeta start */
	        if (isset($post['sm_fee_chk_qty_price'])) {
		        update_post_meta($post_id, 'sm_fee_chk_qty_price', 'on');
	        } else {
		        update_post_meta($post_id, 'sm_fee_chk_qty_price', 'off');
            }
            /* Apply per Sqm postmeta start */
	        if (isset($post['sm_fee_chk_sqm_price'])) {
		        update_post_meta($post_id, 'sm_fee_chk_sqm_price', 'on');
	        } else {
		        update_post_meta($post_id, 'sm_fee_chk_sqm_price', 'off');
	        }
	        if (isset($post['sm_fee_per_qty'])) {
		        update_post_meta( $post_id, 'sm_fee_per_qty', esc_attr($post['sm_fee_per_qty']) );
	        }
	        if (isset($post['sm_extra_product_cost'])) {
		        update_post_meta( $post_id, 'sm_extra_product_cost', esc_attr($post['sm_extra_product_cost']) );
	        }
	        /* Apply per quantity postmeta end */

	        if (isset($post['sm_tooltip_desc'])) {
                update_post_meta($post_id, 'sm_tooltip_desc', esc_attr($post['sm_tooltip_desc']));
            }
            if (isset($post['sm_select_taxable'])) {
                update_post_meta($post_id, 'sm_select_taxable', esc_attr($post['sm_select_taxable']));
            }
            if (isset($post['sm_estimation_delivery'])) {
                update_post_meta($post_id, 'sm_estimation_delivery', esc_attr($post['sm_estimation_delivery']));
            }
            if (isset($post['sm_start_date'])) {
                update_post_meta($post_id, 'sm_start_date', esc_attr($post['sm_start_date']));
            }
            if (isset($post['sm_end_date'])) {
                update_post_meta($post_id, 'sm_end_date', esc_attr($post['sm_end_date']));
            }
            if (isset($post['sm_extra_cost'])) {
                update_post_meta($post_id, 'sm_extra_cost', $post['sm_extra_cost']);
            }
            if (isset($post['sm_extra_cost_calculation_type'])) {
                update_post_meta($post_id, 'sm_extra_cost_calculation_type', esc_attr($post['sm_extra_cost_calculation_type']));
            }
            
            $feesArray = array();
            $fees = isset($post['fees']) ? $post['fees'] : array();
            $condition_key = isset($post['condition_key']) ? $post['condition_key'] : array();
            $fees_conditions = $fees['product_fees_conditions_condition'];
            $conditions_is = $fees['product_fees_conditions_is'];
            $conditions_values = isset($fees['product_fees_conditions_values']) && !empty($fees['product_fees_conditions_values']) ? $fees['product_fees_conditions_values'] : array();
            $size = count($fees_conditions);
            foreach ($condition_key as $key => $value) {
                if (!array_key_exists($key, $conditions_values)) {
                    $conditions_values[$key] = array();
                }
            }
            uksort($conditions_values, 'strnatcmp');
            foreach ($conditions_values as $k => $v) {
                $conditionsValuesArray[] = $v;
            }
            for ($i = 0; $i < $size; $i++) {
                $feesArray[] = array(
                    'product_fees_conditions_condition' => $fees_conditions[$i],
                    'product_fees_conditions_is' => $conditions_is[$i],
                    'product_fees_conditions_values' => $conditionsValuesArray[$i]
                );
            }
            update_post_meta($post_id, 'sm_metabox', $feesArray);
            wp_redirect(admin_url('/admin.php?page=afrsm-pro-list'));
            exit();
        }
    }

    /* Mailchimp Script */
    public function afrsm_pro_subscribe_newsletter() {
        $email_id = (isset($_POST["email_id"]) && !empty($_POST["email_id"])) ? $_POST["email_id"] : '';
        $log_url = !empty($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '';
        $cur_date = date('Y-m-d');
        $request_url = 'https://store.multidots.com/wp-content/themes/business-hub-child/API/wp-add-plugin-users.php';
        if (!empty($email_id)) {
            $response_args = array(
                                    'method' => 'POST',
                                    'timeout' => 45,
                                    'redirection' => 5,
                                    'httpversion' => '1.0',
                                    'blocking' => true,
                                    'headers' => array(),
                                    'body' => array('user' => array('plugin_id' => '1', 'user_email' => $email_id, 'plugin_site' => $log_url, 'status' => 1, 'activation_date' => $cur_date)),
                                    'cookies' => array()
                            );
            $request_response = wp_remote_post($request_url, $response_args);
            if ( !is_wp_error( $request_response ) ) {
                update_option('afrsm_plugin_notice_shown', 'true');
            }
        }
        wp_die();
    }
    
    public function afrsm_pro_admin_footer_review() {
        echo 'If you like <strong>Advanced Flat Rate Shipping For WooCommerce</strong> plugin, please leave us ★★★★★ ratings on <a href="' . esc_url('store.multidots.com/advanced-flat-rate-shipping-method-for-woocommerce') . '" target="_blank">DotStore</a> or <a href="' . esc_url('codecanyon.net/item/advance-flat-rate-shipping-method-for-woocommerce/reviews/15831725') . '" target="_blank">CodeCanyon</a>.';
    }
}
