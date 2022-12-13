<?php

if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('WP_List_Table')) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

/**
 * WC_Shipping_Zones_Table class.
 *
 * @extends WP_List_Table
 */
if (!class_exists('WC_Shipping_Zones_Table')) {

    class WC_Shipping_Zones_Table extends WP_List_Table {

        public $index = 0;
        private static $active_plugins;

        /**
         * Constructor
         */
        public function __construct() {
            parent::__construct(array(
                'singular' => 'Shipping Zone',
                'plural' => 'Shipping Zones',
                'ajax' => false
            ));
        }

        /**
         * Output the zone name column.
         * @param  object $item
         * @return string
         */
        public function column_zone_name($item) {
            $nonce = wp_create_nonce('edit_zone');
            $editurl = esc_url(add_query_arg('edit_zone', $item->zone_id, admin_url('admin.php?page=wc-shipping-zones')));
            $editurl = str_replace('#038;', '&', $editurl);

            $delurl = esc_url(add_query_arg('delete_zone', $item->zone_id, admin_url('admin.php?page=wc-shipping-zones')));
            $delurl = str_replace('#038;', '&', $delurl);
            $zone_name = '<strong>
                            <a href="' . wp_nonce_url($editurl, 'edit_' . $item->zone_id, 'cust_nonce') . '" class="configure_methods">' . esc_html($item->zone_name) . '</a>
                        </strong>
                        <input type="hidden" class="zone_id" name="zone_id[]" value="' . esc_attr($item->zone_id) . '" />
                        <div class="row-actions">';

            if ($item->zone_id > 0) {

                $zone_name .= '<a href="' . wp_nonce_url($editurl, 'edit_' . $item->zone_id, 'cust_nonce') . '">' . __('Edit', AFRSM_PRO_TEXT_DOMAIN) . '</a>';
            }
            if ($item->zone_id > 0) {

                $zone_name .= '&nbsp;|&nbsp;<a href="' . wp_nonce_url($delurl, 'del_' . $item->zone_id, 'cust_nonce') . '">' . __('Delete', AFRSM_PRO_TEXT_DOMAIN) . '</a>';
            }
            $zone_name .= '</div>';
            return $zone_name;
        }

        /**
         * Output the zone id column.
         * @param  object $item
         * @return string
         */
        public function column_zone_id($item) {
            $zone_id = $item->zone_id;
            return $zone_id;
        }

        /**
         * Output the zone type column.
         * @param  object $item
         * @return string
         */
        public function column_zone_type($item) {
            global $wpdb;

            if ($item->zone_id == 0) {
                return __('Everywhere', AFRSM_PRO_TEXT_DOMAIN);
            }

            $locations = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->prefix}wcextraflatrate_shipping_zone_locations WHERE zone_id = %s;", $item->zone_id));

            $count = sizeof($locations);

            if ('postcodes' === $item->zone_type) {
                $count = $count - 1;
            }

            $locations_prepend = "";
            $locations_append = "";
            $locations_list = array();

            foreach ($locations as $location) {
                if (sizeof($locations_list) >= 8) {
                    $locations_append = ' ' . sprintf(__('and %s others', AFRSM_PRO_TEXT_DOMAIN), ( $count - 8));
                    break;
                }
                switch ($location->location_type) {
                    case "country" :
                    case "state" :

                        if (strstr($location->location_code, ':')) {
                            $split_code = explode(':', $location->location_code);
                            if (!isset(WC()->countries->states[$split_code[0]][$split_code[1]])) {
                                continue;
                            }
                            $location_name = WC()->countries->states[$split_code[0]][$split_code[1]];
                        } else {
                            if (!isset(WC()->countries->countries[$location->location_code])) {
                                continue;
                            }
                            $location_name = WC()->countries->countries[$location->location_code];
                        }

                        if ($item->zone_type == 'postcodes') {
                            $locations_prepend = sprintf(__('Within %s:', AFRSM_PRO_TEXT_DOMAIN), $location_name) . ' ';
                        } else {
                            $locations_list[] = $location_name;
                        }
                        break;
                    case "postcode" :
                        $locations_list[] = $location->location_code;
                }
            }

            switch ($item->zone_type) {
                case "countries" :
                    return '<strong>' . __('Countries', AFRSM_PRO_TEXT_DOMAIN) . '</strong><br/>' . $locations_prepend . implode(', ', $locations_list) . $locations_append;
                case "states" :
                    return '<strong>' . __('Countries and states', AFRSM_PRO_TEXT_DOMAIN) . '</strong><br/>' . $locations_prepend . implode(', ', $locations_list) . $locations_append;
                case "postcodes" :
                    return '<strong>' . __('Postcodes', AFRSM_PRO_TEXT_DOMAIN) . '</strong><br/>' . $locations_prepend . implode(', ', $locations_list) . $locations_append;
            }
        }

        /**
         * Output the zone enabled column.
         * @param  object $item
         * @return string
         */
        public function column_enabled($item) {
            return $item->zone_enabled ? '&#10004;' : '&ndash;';
        }

        /**
         * Output the zone methods column.
         * @param  object $item
         * @return string
         */
        public function column_methods($item) {
            global $wpdb;

            $output_methods = array();

            $shipping_methods = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->prefix}wcextraflatrate_shipping_zone_shipping_methods
                                                                    WHERE zone_id = %s
                                                                    ORDER BY `shipping_method_order` ASC", $item->zone_id));

            if ($shipping_methods) {
                foreach ($shipping_methods as $method) {
                    $class_callback = 'woocommerce_get_shipping_method_' . $method->shipping_method_type;

                    if (function_exists($class_callback)) {
                        $this_method = call_user_func($class_callback, $method->shipping_method_id);
                        $output_methods[] = '<a href="' . esc_url(add_query_arg('method', $method->shipping_method_id, add_query_arg('zone', $item->zone_id, admin_url('admin.php?page=wc-shipping-zones')))) . '">' . esc_html($this_method->title ? $this_method->title : $this_method->id ) . '</a>';
                    }
                }

                return implode(', ', $output_methods);
            } else {
                return __('None', AFRSM_PRO_TEXT_DOMAIN);
            }
        }

        /**
         * Checkbox column
         * @param string
         */
        public function column_cb($item) {
            if (!$item->zone_id) {
                return;
            }
            return sprintf(
                    '<input type="checkbox" name="%1$s[]" value="%2$s" />', 'zone_id_cb', $item->zone_id
            );
        }

        /**
         * get_columns function.
         * @return  array
         */
        public function get_columns() {
            return array(
                'cb' => '<input type="checkbox" />',
                'zone_id' => __('Zone ID', AFRSM_PRO_TEXT_DOMAIN),
                'zone_name' => __('Zone name', AFRSM_PRO_TEXT_DOMAIN),
                'zone_type' => __('Zone type', AFRSM_PRO_TEXT_DOMAIN),
                'enabled' => __('Enabled', AFRSM_PRO_TEXT_DOMAIN)
            );
        }

        /**
         * Get bulk actions
         */
        public function get_bulk_actions() {
            $actions = array(
                'disable' => __('Disable', AFRSM_PRO_TEXT_DOMAIN),
                'enable' => __('Enable', AFRSM_PRO_TEXT_DOMAIN),
                '' => '------',
                'delete' => __('Delete', AFRSM_PRO_TEXT_DOMAIN)
            );
            return $actions;
        }

        /**
         * Process bulk actions
         */
        public function process_bulk_action() {
            global $wpdb;

            if (!isset($_POST['zone_id_cb'])) {
                return;
            }

            $items = array_filter(array_map('absint', $_POST['zone_id_cb']));

            if (!$items) {
                return;
            }

            if ('delete' === $this->current_action()) {

                foreach ($items as $id) {

                    $wpdb->delete($wpdb->prefix . 'wcextraflatrate_shipping_zone_locations', array('zone_id' => $id));
                    $wpdb->delete($wpdb->prefix . 'wcextraflatrate_shipping_zones', array('zone_id' => $id));
                }

                echo '<div class="updated success"><p>' . __('Shipping zones deleted', AFRSM_PRO_TEXT_DOMAIN) . '</p></div>';
            } elseif ('enable' === $this->current_action()) {

                foreach ($items as $id) {
                    $wpdb->update( $wpdb->prefix . 'wcextraflatrate_shipping_zones', array( 'zone_enabled' => 1 ), array('zone_id' => $id), array('%d'), array('%d') );
                }

                echo '<div class="updated success"><p>' . __('Shipping zones enabled', AFRSM_PRO_TEXT_DOMAIN) . '</p></div>';
            } elseif ('disable' === $this->current_action()) {

                foreach ($items as $id) {
                    $wpdb->update( $wpdb->prefix . 'wcextraflatrate_shipping_zones', array( 'zone_enabled' => 0 ), array('zone_id' => $id), array('%d'), array('%d') );
                }

                echo '<div class="updated success"><p>' . __('Shipping zones disabled', AFRSM_PRO_TEXT_DOMAIN) . '</p></div>';
            }
        }

        /**
         * Get Zones to display
         */
        public function prepare_items() {
            global $wpdb;
            $current_user = wp_get_current_user();
            $userid = $current_user->ID;
            $this->_column_headers = array($this->get_columns(), array(), array());
            $this->process_bulk_action();
            
            $this->items = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}wcextraflatrate_shipping_zones ORDER BY zone_id ASC");
        }

    }

}
?>