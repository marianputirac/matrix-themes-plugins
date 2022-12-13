<?php
    
    class OrdersCustom
    {
        public function orderTablesCreate()
        {
            global $wpdb;
            global $jal_db_version;
            $table_name = $wpdb->prefix . 'custom_orders';
            $charset_collate = $wpdb->get_charset_collate();
            
            // table import quickbooks items
            // create table wp_quickbooks_items to insert items from quickbooks platform
            $query = $wpdb->prepare( 'SHOW TABLES LIKE %s', $wpdb->esc_like( $table_name ) );
            
            if ( ! $wpdb->get_var( $query ) == $table_name ) {
                $sql = "CREATE TABLE $table_name (
                    id INT NOT NULL AUTO_INCREMENT,
                    idOrder varchar(255) DEFAULT '' NOT NULL,
                    reference varchar(100) DEFAULT '' NOT NULL,
                    usd_price varchar(50) DEFAULT '0' NOT NULL,
                    gbp_price varchar(50) DEFAULT '0' NOT NULL,
                    subtotal_price varchar(50) DEFAULT '0' NOT NULL,
                    sqm varchar(10) DEFAULT '0' NOT NULL,
                    shipping_cost varchar(50) DEFAULT '0' NOT NULL,
                    delivery varchar(50) DEFAULT '' NOT NULL,
                    status varchar(50) DEFAULT '' NOT NULL,
                    createTime datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
                    PRIMARY KEY (id) ) $charset_collate";
                
                require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
                dbDelta($sql);
                
                add_option('custom_orders_table', "1.0");
            }
        }
    }
