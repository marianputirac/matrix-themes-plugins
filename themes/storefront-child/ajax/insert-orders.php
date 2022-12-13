<?php
    $path = preg_replace('/wp-content(?!.*wp-content).*/', '', __DIR__);
    include($path . 'wp-load.php');

    $id_ord_original = $_POST['id_ord_original'];

    if (!empty($id_ord_original)) {
        $order = wc_get_order($id_ord_original);

        $items = $order->get_items();
        $order_data = $order->get_data();
        $order_status = $order_data['status'];

        $suma = $order->get_total();
        $order_data['date_created']->date('Y-m-d H:i:s');

        $text = $order_data['date_created']->date('Y-m-d H:i:s');
        $texty = $order_data['date_created']->date('Y-m-d H:i:s');
        preg_match('/-(.*?)-/', $text, $match);
        preg_match('/(.*?)-/', $text, $match_year);
        $month = $match[1];
        $year = $match_year[1];

        preg_match('/(.*?)-/', $texty, $match2);
        $pieces = explode("-", $match2[0]);
        $pieces[0]; // piece1
        $year = $pieces[0];

        $property_total_gbp = $order->get_total();
        $property_subtotal_gbp = $order->get_subtotal();

        $order_shipping_total = $order->shipping_total;

        // $property_total_gbp = floatval(get_post_meta($order->id, '_order_total', true));

        $total_dolar = 0;
        $sum_total_dolar = 0;
        $totaly_sqm = 0;
        foreach ($items as $item_id => $item_data) {

            $prod_id = $item_id;

            // USD price
            $prod_qty = $item_data['quantity'];
            $product_id = $item_data['product_id'];
            $dolar_price = get_post_meta($product_id, 'dolar_price', true);

            $sum_total_dolar = floatval($sum_total_dolar) + floatval($dolar_price) * floatval($prod_qty);

            $_product = wc_get_product($product_id);
            $shipclass = $_product->get_shipping_class();

            $sqm = get_post_meta($product_id, 'property_total', true);
            $property_total_sqm = floatval($sqm) * floatval($prod_qty);
            $totaly_sqm = $totaly_sqm + $property_total_sqm;

        }

        global $wpdb;
        $tablename = $wpdb->prefix . 'custom_orders';

        $data = array(
            'idOrder' => $order->get_id(),
            'reference' => 'LF0' . $order->get_order_number() . ' - ' . get_post_meta($order->get_id(), 'cart_name', true),
            'usd_price' => $sum_total_dolar,
            'gbp_price' => $property_total_gbp,
            'subtotal_price' => $property_subtotal_gbp,
            'sqm' => $totaly_sqm,
            'shipping_cost' => $order_shipping_total,
            'delivery' => $shipclass,
            'status' => $order_status,
            'createTime' => $order_data['date_created']->date('Y-m-d H:i:s'),
        );
        $wpdb->insert($tablename, $data);

    } else {

        //Get all orders
        $orders = wc_get_orders(array(
            'limit' => -1,
            'orderby' => 'date',
            'order' => 'ASC',
        ));

        foreach ($orders as $order) {

            $order = wc_get_order($id_ord_original);

            $items = $order->get_items();
            $order_data = $order->get_data();
            $order_status = $order_data['status'];

            $suma = $order->get_total();
            $order_data['date_created']->date('Y-m-d H:i:s');

            $text = $order_data['date_created']->date('Y-m-d H:i:s');
            $texty = $order_data['date_created']->date('Y-m-d H:i:s');
            preg_match('/-(.*?)-/', $text, $match);
            preg_match('/(.*?)-/', $text, $match_year);
            $month = $match[1];
            $year = $match_year[1];

            preg_match('/(.*?)-/', $texty, $match2);
            $pieces = explode("-", $match2[0]);
            $pieces[0]; // piece1
            $year = $pieces[0];

            $property_total_gbp = $order->get_total();
            $property_subtotal_gbp = $order->get_subtotal();

            $order_shipping_total = $order->shipping_total;

            // $property_total_gbp = floatval(get_post_meta($order->id, '_order_total', true));

            $total_dolar = 0;
            $sum_total_dolar = 0;
            $totaly_sqm = 0;
            foreach ($items as $item_id => $item_data) {

                // USD price
                $prod_qty = $item_data['quantity'];
                $product_id = $item_data['product_id'];
                $dolar_price = get_post_meta($product_id, 'dolar_price', true);

                $sum_total_dolar = floatval($sum_total_dolar) + floatval($dolar_price) * floatval($prod_qty);

                $_product = wc_get_product($product_id);
                $shipclass = $_product->get_shipping_class();

                $sqm = get_post_meta($product_id, 'property_total', true);
                $property_total_sqm = floatval($sqm) * floatval($prod_qty);
                $totaly_sqm = $totaly_sqm + number_format($property_total_sqm, 2);

            }

            global $wpdb;
            $tablename = $wpdb->prefix . 'custom_orders';

            $data = array(
                'idOrder' => $order->get_id(),
                'reference' => 'LF0' . $order->get_order_number() . ' - ' . get_post_meta($order->get_id(), 'cart_name', true),
                'usd_price' => $sum_total_dolar,
                'gbp_price' => $property_total_gbp,
                'subtotal_price' => $property_subtotal_gbp,
                'sqm' => $totaly_sqm,
                'shipping_cost' => number_format($order_shipping_total, 2),
                'delivery' => $shipclass,
                'status' => $order_status,
                'createTime' => $order_data['date_created']->date('Y-m-d H:i:s'),
            );
            $wpdb->insert($tablename, $data);

        }
    }

?>
