<?php

/**
 * Fired during plugin activation
 *
 * @link       http://www.multidots.com
 * @since      1.0.0
 *
 * @package    Advanced_Flat_Rate_Shipping_For_WooCommerce_Pro
 * @subpackage Advanced_Flat_Rate_Shipping_For_WooCommerce_Pro/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Advanced_Flat_Rate_Shipping_For_WooCommerce_Pro
 * @subpackage Advanced_Flat_Rate_Shipping_For_WooCommerce_Pro/includes
 * @author     Multidots <inquiry@multidots.in>
 */
class Advanced_Flat_Rate_Shipping_For_WooCommerce_Pro_Activator {

    /**
     * Short Description. (use period)
     *
     * Long Description.
     *
     * @since    1.0.0
     */
    public static function activate() {
        global $wpdb, $woocommerce;
        set_transient('_welcome_screen_afrsm_pro_mode_activation_redirect_data', true, 30);
        add_option('afrsm_version', Advanced_Flat_Rate_Shipping_For_WooCommerce_Pro::WCPFC_VERSION);

        if (!in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins'))) && !is_plugin_active_for_network('woocommerce/woocommerce.php')) {
            wp_die("<strong>Advanced Flat Rate Shipping For WooCommerce</strong> plugin requires <strong>WooCommerce</strong>. Return to <a href='" . get_admin_url(null, 'plugins.php') . "'>Plugins page</a>.");
        } else {
            
            /* Data Migration Script Start */
            $afrsm_db_upgrade = get_option('afrsm_db_upgrade');

            if( empty($afrsm_db_upgrade) ) {
                $db_upgrade_flag = self::afrsm_data_migration_script();
                if( $db_upgrade_flag == 1 ) {
                    update_option( 'afrsm_db_upgrade', 'required' );
                }
            }
            /* Data Migration Script End */
            
            $wpdb->hide_errors();
            $collate = '';

            if ($wpdb->has_cap('collation')) {
                if (!empty($wpdb->charset)) {
                    $collate .= "DEFAULT CHARACTER SET $wpdb->charset";
                }
                if (!empty($wpdb->collate)) {
                    $collate .= " COLLATE $wpdb->collate";
                }
            }

            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

            // Table for storing shipping zones
            $zones_sql = "CREATE TABLE {$wpdb->prefix}wcextraflatrate_shipping_zones (
                      zone_id bigint(20) NOT NULL auto_increment,
                      zone_name varchar(255) NOT NULL,
                      zone_enabled int(1) NOT NULL,
                      zone_type varchar(40) NOT NULL,
                      zone_order bigint(20) NOT NULL,
                      PRIMARY KEY  (zone_id) ) $collate;";
            dbDelta($zones_sql);

            // Table for storing a shipping zones locations which it applies to. Type can be postcode, state, or country.
            $zone_locations_sql = "CREATE TABLE {$wpdb->prefix}wcextraflatrate_shipping_zone_locations (
                      location_id bigint(20) NOT NULL auto_increment,
                      location_code varchar(255) NOT NULL,
                      zone_id bigint(20) NOT NULL,
                      location_type varchar(40) NOT NULL,
                      PRIMARY KEY  (location_id) ) $collate;";
            dbDelta($zone_locations_sql);

            // Table for storing a shipping zones locations which it applies to. Type can be postcode, state, or country.
            $user_zone_sql = "CREATE TABLE {$wpdb->prefix}wcextraflatrate_user_zone (user_id bigint(20) NOT NULL, zone_id bigint(20) NOT NULL) $collate;";
            dbDelta($user_zone_sql);

        }
    }

	/**
	 * @return int
	 */
	public static function afrsm_data_migration_script() {
        global $wpdb;
        
        $db_upgrade_flag = 0;
        
        $shipping_method_format = get_option('md_woocommerce_shipping_method_format');
        if( $shipping_method_format == 'select' ) {
          update_option('md_woocommerce_shipping_method_format', 'dropdown_mode');
        } else {
          update_option('md_woocommerce_shipping_method_format', 'radio_button_mode');
        }

        $afrsm_settings_query   = "SELECT * FROM {$wpdb->prefix}options WHERE option_name LIKE 'extra_%'";
        $afrsm_settings_result  = $wpdb->get_results($afrsm_settings_query);
        
        if( !empty( $afrsm_settings_result ) ) {
            foreach ($afrsm_settings_result as $key => $afrsm_setting) {
                
                $shipping_metabox = array();
                $country_base = array();
                $state_base = array();
                $postcode_base = array();
                $zone_base = array();
                $product_base = array();
                $category_base = array();
                $tag_base = array();
                $sku_base = array();
                $user_base = array();
                $user_role_base = array();
                $coupon = array();
                $master_class = array();
                
                $afrsm_setting_unser = maybe_unserialize($afrsm_setting->option_value);
                
                $shipping_enabled               = (!empty($afrsm_setting_unser['enabled']) && $afrsm_setting_unser['enabled'] == 'yes') ? 'on' : 'off';
                $shipping_title                 = !empty($afrsm_setting_unser['title']) ? esc_attr(stripslashes($afrsm_setting_unser['title'])) : '';
                $shipping_cost                  = !empty($afrsm_setting_unser['cost']) ? esc_attr(stripslashes($afrsm_setting_unser['cost'])) : 0;
                $shipping_description           = !empty($afrsm_setting_unser['shipping_description']) ? $afrsm_setting_unser['shipping_description'] : '';
                $shipping_tax_status            = (!empty($afrsm_setting_unser['tax_status']) && $afrsm_setting_unser['tax_status'] == 'taxable') ? 'yes' : 'no';
                $shipping_estimation_delivery   = !empty($afrsm_setting_unser['estimation_delivery']) ? esc_attr(stripslashes($afrsm_setting_unser['estimation_delivery'])) : '';
                $shipping_start_date            = !empty($afrsm_setting_unser['start_date']) ? esc_attr(stripslashes($afrsm_setting_unser['start_date'])) : '';
                $shipping_end_date              = !empty($afrsm_setting_unser['end_date']) ? esc_attr(stripslashes($afrsm_setting_unser['end_date'])) : '';
                
                $shipping_availability          = !empty($afrsm_setting_unser['availability']) ? esc_attr(stripslashes($afrsm_setting_unser['availability'])) : '';
                $shipping_country_base          = !empty($afrsm_setting_unser['country_base']) ? $afrsm_setting_unser['country_base'] : array();
                $shipping_state_base            = !empty($afrsm_setting_unser['state_base']) ? $afrsm_setting_unser['state_base'] : array();
                $shipping_postcode_base         = !empty($afrsm_setting_unser['postcode_base']) ? $afrsm_setting_unser['postcode_base'] : array();
                $shipping_zone_base             = !empty($afrsm_setting_unser['zone_base']) ? $afrsm_setting_unser['zone_base'] : array();
                
                $shipping_product_base          = !empty($afrsm_setting_unser['product_base']) ? $afrsm_setting_unser['product_base'] : array();
                $shipping_category_base         = !empty($afrsm_setting_unser['category_base']) ? $afrsm_setting_unser['category_base'] : array();
                $shipping_tag_base              = !empty($afrsm_setting_unser['tag_base']) ? $afrsm_setting_unser['tag_base'] : array();
                $shipping_sku_base              = !empty($afrsm_setting_unser['sku_base']) ? $afrsm_setting_unser['sku_base'] : array();
                
                $shipping_user_base             = !empty($afrsm_setting_unser['user_base']) ? $afrsm_setting_unser['user_base'] : array();
                $shipping_user_role_base        = !empty($afrsm_setting_unser['user_role_base']) ? $afrsm_setting_unser['user_role_base'] : array();
                
                $shipping_if_order_amount       = !empty($afrsm_setting_unser['if_order_amount']) ? $afrsm_setting_unser['if_order_amount'] : '';
                $shipping_if_quantity           = !empty($afrsm_setting_unser['if_quantity']) ? $afrsm_setting_unser['if_quantity'] : '';
                $shipping_if_weight             = !empty($afrsm_setting_unser['if_weight']) ? $afrsm_setting_unser['if_weight'] : '';
                $shipping_coupon                = !empty($afrsm_setting_unser['coupon']) ? $afrsm_setting_unser['coupon'] : '';
                $shipping_master_class          = !empty($afrsm_setting_unser['master_class']) ? $afrsm_setting_unser['master_class'] : '';
                
                $shipping_type                  = !empty($afrsm_setting_unser['type']) ? $afrsm_setting_unser['type'] : '';
                
                $new_shipping_cost = self::afrsm_string_sanitize($shipping_cost);
                
                /* METABOX CONDITIONS START */
                
                /* Zone base metabox */
                if( $shipping_availability == 'specific' ) {
                    $zone_base['product_fees_conditions_condition'] = 'zone';
                    $zone_base['product_fees_conditions_is'] = 'is_equal_to';
                    $zone_base['product_fees_conditions_values'] = $shipping_zone_base;
                    $shipping_metabox[] = $zone_base;
                }
                
                /* Country base metabox */
                if( $shipping_availability == 'all' || $shipping_availability == 'Countrybase' ) {
                    $country_base['product_fees_conditions_condition'] = 'country';
                    $country_base['product_fees_conditions_is'] = 'is_equal_to';
                    $country_base['product_fees_conditions_values'] = $shipping_country_base;
                    $shipping_metabox[] = $country_base;
                    
                    if( !empty($shipping_state_base) ) {
                        $state_base['product_fees_conditions_condition'] = 'state';
                        $state_base['product_fees_conditions_is'] = 'is_equal_to';
                        $state_base['product_fees_conditions_values'] = $shipping_state_base;
                        $shipping_metabox[] = $state_base;
                    }
                    
                    if( !empty($shipping_postcode_base) ) {
                        $postcode_base['product_fees_conditions_condition'] = 'postcode';
                        $postcode_base['product_fees_conditions_is'] = 'is_equal_to';
                        $postcode_base['product_fees_conditions_values'] = $shipping_postcode_base;
                        $shipping_metabox[] = $postcode_base;
                    }
                    
                }
                
                /* Product base metabox */
                if( !empty($shipping_product_base['1']) ) {
                    $product_base['product_fees_conditions_condition'] = 'product';
                    if( $shipping_product_base['0'] == 'Yes' ) {
                        $equal_condition = 'is_equal_to';
                    } else {
                        $equal_condition = 'not_in';
                    }
                    $product_base['product_fees_conditions_is'] = $equal_condition;
                    array_shift($shipping_product_base);
                    $product_base['product_fees_conditions_values'] = $shipping_product_base;
                    
                    $shipping_metabox[] = $product_base;
                }
                
                /* Category base metabox */
                if( !empty($shipping_category_base['1']) ) {
                    $category_base['product_fees_conditions_condition'] = 'category';
                    if( $shipping_category_base['0'] == 'Yes' ) {
                        $equal_condition = 'is_equal_to';
                    } else {
                        $equal_condition = 'not_in';
                    }
                    $category_base['product_fees_conditions_is'] = $equal_condition;
                    array_shift($shipping_category_base);
                    $category_base['product_fees_conditions_values'] = $shipping_category_base;

                    $shipping_metabox[] = $category_base;
                }
                
                /* Tag base metabox */
                if( !empty($shipping_tag_base['1']) ) {
                    $tag_base['product_fees_conditions_condition'] = 'tag';
                    if( $shipping_tag_base['0'] == 'Yes' ) {
                        $equal_condition = 'is_equal_to';
                    } else {
                        $equal_condition = 'not_in';
                    }
                    $tag_base['product_fees_conditions_is'] = $equal_condition;
                    array_shift($shipping_tag_base);
                    $tag_base['product_fees_conditions_values'] = $shipping_tag_base;

                    $shipping_metabox[] = $tag_base;
                }
                
                /* SKU base metabox */
                if( !empty($shipping_sku_base['1']) ) {
                    $sku_base['product_fees_conditions_condition'] = 'sku';
                    if( $shipping_sku_base['0'] == 'Yes' ) {
                        $equal_condition = 'is_equal_to';
                    } else {
                        $equal_condition = 'not_in';
                    }
                    $sku_base['product_fees_conditions_is'] = $equal_condition;
                    array_shift($shipping_sku_base);
                    $sku_base['product_fees_conditions_values'] = $shipping_sku_base;

                    $shipping_metabox[] = $sku_base;
                }
                
                /* User base metabox */
                if( !empty($shipping_user_base['1']) ) {
                    $user_base['product_fees_conditions_condition'] = 'user';
                    if( $shipping_user_base['0'] == 'Yes' ) {
                        $equal_condition = 'is_equal_to';
                    } else {
                        $equal_condition = 'not_in';
                    }
                    $user_base['product_fees_conditions_is'] = $equal_condition;
                    array_shift($shipping_user_base);
                    $user_base['product_fees_conditions_values'] = $shipping_user_base;

                    $shipping_metabox[] = $user_base;
                }
                
                /* User role base metabox */
                if( !empty($shipping_user_role_base['1']) ) {
                    $user_role_base['product_fees_conditions_condition'] = 'user_role';
                    if( $shipping_user_role_base['0'] == 'Yes' ) {
                        $equal_condition = 'is_equal_to';
                    } else {
                        $equal_condition = 'not_in';
                    }
                    $user_role_base['product_fees_conditions_is'] = $equal_condition;
                    array_shift($shipping_user_role_base);
                    $user_role_base['product_fees_conditions_values'] = $shipping_user_role_base;

                    $shipping_metabox[] = $user_role_base;
                }
                
                /* Coupon base metabox */
                if( !empty($shipping_coupon) ) {
                    $coupon['product_fees_conditions_condition'] = 'coupon';
                    $coupon['product_fees_conditions_is'] = 'is_equal_to';
                    $coupon['product_fees_conditions_values'] = $shipping_coupon;

                    $shipping_metabox[] = $coupon;
                }
                
                /* Master class base metabox */
                if( !empty($shipping_master_class) && $shipping_master_class != '0' ) {
                    $shipping_master_class_array = explode(" ", $shipping_master_class);
                    $master_class['product_fees_conditions_condition'] = 'shipping_class';
                    $master_class['product_fees_conditions_is'] = 'is_equal_to';
                    $master_class['product_fees_conditions_values'] = $shipping_master_class_array;

                    $shipping_metabox[] = $master_class;
                }
                
                /* Order amount metabox */
                if( $shipping_if_order_amount == 'min_amount' ) {
                    $min_amount = !empty($afrsm_setting_unser['min_amount']) ? $afrsm_setting_unser['min_amount'] : '';
                    
                    $cart_total_min['product_fees_conditions_condition'] = 'cart_total';
                    $cart_total_min['product_fees_conditions_is'] = 'less_equal_to';
                    $cart_total_min['product_fees_conditions_values'] = $min_amount;
                    
                    $shipping_metabox[] = $cart_total_min;
                }
                
                if( $shipping_if_order_amount == 'max_amount' ) {
                    $max_amount = !empty($afrsm_setting_unser['max_amount']) ? $afrsm_setting_unser['max_amount'] : '';
                    
                    $cart_total_max['product_fees_conditions_condition'] = 'cart_total';
                    $cart_total_max['product_fees_conditions_is'] = 'greater_equal_to';
                    $cart_total_max['product_fees_conditions_values'] = $max_amount;
                    
                    $shipping_metabox[] = $cart_total_max;
                }
                
                if( $shipping_if_order_amount == 'in_between' ) {
                    $min_amount = !empty($afrsm_setting_unser['min_amount']) ? $afrsm_setting_unser['min_amount'] : '';
                    $max_amount = !empty($afrsm_setting_unser['max_amount']) ? $afrsm_setting_unser['max_amount'] : '';
                    
                    $cart_total_min['product_fees_conditions_condition'] = 'cart_total';
                    $cart_total_min['product_fees_conditions_is'] = 'greater_equal_to';
                    $cart_total_min['product_fees_conditions_values'] = $min_amount;
                    $shipping_metabox[] = $cart_total_min;
                    
                    $cart_total_max['product_fees_conditions_condition'] = 'cart_total';
                    $cart_total_max['product_fees_conditions_is'] = 'less_equal_to';
                    $cart_total_max['product_fees_conditions_values'] = $max_amount;
                    $shipping_metabox[] = $cart_total_max;
                }
                
                /* Quantity metabox */
                if( $shipping_if_quantity == 'min_extra_quantity' ) {
                    $min_extra_quantity = !empty($afrsm_setting_unser['min_extra_quantity']) ? $afrsm_setting_unser['min_extra_quantity'] : '';
                    
                    $cart_min_quantity['product_fees_conditions_condition'] = 'quantity';
                    $cart_min_quantity['product_fees_conditions_is'] = 'less_equal_to';
                    $cart_min_quantity['product_fees_conditions_values'] = $min_extra_quantity;
                    $shipping_metabox[] = $cart_min_quantity;
                }
                
                if( $shipping_if_quantity == 'max_quantity' ) {
                    $max_quantity = !empty($afrsm_setting_unser['max_quantity']) ? $afrsm_setting_unser['max_quantity'] : '';
                    
                    $cart_max_quantity['product_fees_conditions_condition'] = 'quantity';
                    $cart_max_quantity['product_fees_conditions_is'] = 'greater_equal_to';
                    $cart_max_quantity['product_fees_conditions_values'] = $max_quantity;
                    $shipping_metabox[] = $cart_max_quantity;
                }
                
                if( $shipping_if_quantity == 'in_between_quantity' ) {
                    $min_extra_quantity = !empty($afrsm_setting_unser['min_extra_quantity']) ? $afrsm_setting_unser['min_extra_quantity'] : '';
                    $max_quantity = !empty($afrsm_setting_unser['max_quantity']) ? $afrsm_setting_unser['max_quantity'] : '';
                    
                    
                    $cart_min_quantity['product_fees_conditions_condition'] = 'quantity';
                    $cart_min_quantity['product_fees_conditions_is'] = 'greater_equal_to';
                    $cart_min_quantity['product_fees_conditions_values'] = $min_extra_quantity;
                    $shipping_metabox[] = $cart_min_quantity;
                    
                    $cart_max_quantity['product_fees_conditions_condition'] = 'quantity';
                    $cart_max_quantity['product_fees_conditions_is'] = 'less_equal_to';
                    $cart_max_quantity['product_fees_conditions_values'] = $max_quantity;
                    $shipping_metabox[] = $cart_max_quantity;
                }
                
                /* Weight metabox */
                if( $shipping_if_weight == 'min_weight' ) {
                    $min_weight = !empty($afrsm_setting_unser['min_weight']) ? $afrsm_setting_unser['min_weight'] : '';
                    
                    $cart_min_weight['product_fees_conditions_condition'] = 'weight';
                    $cart_min_weight['product_fees_conditions_is'] = 'less_equal_to';
                    $cart_min_weight['product_fees_conditions_values'] = $min_weight;
                    $shipping_metabox[] = $cart_min_weight;
                }
                
                if( $shipping_if_weight == 'max_weight' ) {
                    $max_weight = !empty($afrsm_setting_unser['max_weight']) ? $afrsm_setting_unser['max_weight'] : '';
                    
                    $cart_max_weight['product_fees_conditions_condition'] = 'weight';
                    $cart_max_weight['product_fees_conditions_is'] = 'greater_equal_to';
                    $cart_max_weight['product_fees_conditions_values'] = $max_weight;
                    $shipping_metabox[] = $cart_max_weight;
                }
                
                if( $shipping_if_weight == 'in_between_weight' ) {
                    $min_weight = !empty($afrsm_setting_unser['min_weight']) ? $afrsm_setting_unser['min_weight'] : '';
                    $max_weight = !empty($afrsm_setting_unser['max_weight']) ? $afrsm_setting_unser['max_weight'] : '';
                    
                    $cart_min_weight['product_fees_conditions_condition'] = 'weight';
                    $cart_min_weight['product_fees_conditions_is'] = 'greater_equal_to';
                    $cart_min_weight['product_fees_conditions_values'] = $min_weight;
                    $shipping_metabox[] = $cart_min_weight;
                    
                    $cart_max_weight['product_fees_conditions_condition'] = 'weight';
                    $cart_max_weight['product_fees_conditions_is'] = 'less_equal_to';
                    $cart_max_weight['product_fees_conditions_values'] = $max_weight;
                    $shipping_metabox[] = $cart_max_weight;
                }
                /* METABOX CONDITIONS END */
                
                /* SHIPPING CLASS CONDITIONS START */
                $shipping_classes = WC()->shipping->get_shipping_classes();
                
                /* Shipping Class Cost Type */
                if (!empty($shipping_classes)) {
                    $shipping_class_cost_array = array();
                    foreach ($shipping_classes as $shipping_class) {
                        
                        if (!empty($shipping_class->term_id)) {
                            
                            $shipping_class_cost = isset($afrsm_setting_unser['class_cost_' . $shipping_class->term_id]) ? $afrsm_setting_unser['class_cost_' . $shipping_class->term_id] : '';
                            $shipping_class_cost = (isset($shipping_class_cost) && !empty($shipping_class_cost)) ? esc_attr(stripslashes($shipping_class_cost)) : '';
                            
                            $shipping_class_cost_new = self::afrsm_string_sanitize($shipping_class_cost);
                            $shipping_class_cost_array[$shipping_class->term_id] = $shipping_class_cost_new;
                        }
                    }
                }
                
                /* Cost Calculation Type */
                if( $shipping_type == 'order' ) {
                    $sm_extra_cost_calculation_type = 'per_order';
                } else {
                    $sm_extra_cost_calculation_type = 'per_class';
                }
                /* SHIPPING CLASS CONDITIONS END */
                
                // Create new shipping method
                $afrsm_post = array(
                                'post_title'    => wp_strip_all_tags( $shipping_title ),
                                'post_type'     => 'wc_afrsm',
                                'post_status'   => 'publish'
                              );
                $post_id = wp_insert_post($afrsm_post);
                //Update shipping method data
                if( !empty($post_id) ) {
                    update_post_meta($post_id, 'sm_status', esc_attr($shipping_enabled));
                    update_post_meta($post_id, 'sm_product_cost', esc_attr($new_shipping_cost));
                    update_post_meta($post_id, 'sm_tooltip_desc', esc_attr($shipping_description));
                    update_post_meta($post_id, 'sm_select_taxable', esc_attr($shipping_tax_status));
                    update_post_meta($post_id, 'sm_estimation_delivery', esc_attr($shipping_estimation_delivery));
                    update_post_meta($post_id, 'sm_start_date', esc_attr($shipping_start_date));
                    update_post_meta($post_id, 'sm_end_date', esc_attr($shipping_end_date));
                    update_post_meta($post_id, 'sm_metabox', $shipping_metabox);
                    update_post_meta($post_id, 'sm_extra_cost', $shipping_class_cost_array);
                    update_post_meta($post_id, 'sm_extra_cost_calculation_type', $sm_extra_cost_calculation_type);
                }
                
            }
            $db_upgrade_flag = 1;
        }
        return $db_upgrade_flag;
    }
    
    public static function afrsm_string_sanitize($string) {
        $result = preg_replace("/[^ A-Za-z0-9_=.*()+\-\[\]\/]+/", "", html_entity_decode($string, ENT_QUOTES));
        return $result;
    }
    
}
?>