<!-- Bootstrap3 -->
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

<!-- jQuery library -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

<!-- Latest compiled JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

<br>
<h2>Search Info</h2>
<div class="container">


    <ul class="nav nav-tabs">
        <li class="active">
            <a data-toggle="tab" href="#menu1">Compare Users by Years</a>
        </li>
        <li>
            <a data-toggle="tab" href="#home">Total User Activity</a>
        </li>
        <li>
            <a data-toggle="tab" href="#battens">Users Placed Orders</a>
        </li>
    </ul>

    <div class="tab-content">

        <div id="menu1" class="tab-pane fade in active">
            <?php
            include_once __DIR__ . '/users-orders/users-compare.php';
            ?>
        </div>
        <div id="home" class="tab-pane fade">
            <?php
            include_once __DIR__ . '/users-orders/users-activity.php';
            ?>
        </div>
        <div id="battens" class="tab-pane fade">
            <?php
            include_once __DIR__ . '/users-orders/users-placed-orders.php';
            ?>
        </div>
    </div>
</div>

<!-- *********************************************************************************************
End Total user sqm
*********************************************************************************************	-->


<?php

function queryArgsForOrdersFilteredMonth($month = null, $year = null)
{
    if (empty($month)) $month = date('m');
    if (empty($year)) $year = date('Y');

    $luna = $month;
    $bf = new DateTime(date($year . '-' . $luna . '-d'));
    $af = new DateTime(date($year . '-' . $luna . '-d 00:00:00'));
    $after = $af->format('Y-m-1 00:00:00');
    $before = $bf->format('Y-m-t 23:59:59');

    $args = array(
        //'customer_id' => $group,
        'limit' => -1,
        'type' => 'shop_order',
        'status' => array('wc-on-hold', 'wc-completed', 'wc-pending', 'wc-processing', 'wc-inproduction', 'wc-paid', 'wc-waiting', 'wc-revised', 'wc-inrevision'),
        'orderby' => 'date',
        'date_query' => array(
            'after' => $after,
            'before' => $before,
        ),
        'return' => 'ids',
    );

    return $args;
}

function getOrdersUsersByYears($orders, $year = null)
{
    if (empty($year)) $year = date('Y');
    $users_year = $users_sqm = $users_gbp = array();
    foreach ($orders as $id_order) {
        $order = wc_get_order($id_order);

        global $wpdb;
        $tablename = $wpdb->prefix . 'custom_orders';
        $myOrder = $wpdb->get_row("SELECT sqm FROM $tablename WHERE idOrder = $id_order", ARRAY_A);

        // SQM
        $property_total = $myOrder['sqm'];
        // GBP price
        $property_total_gbp = $order->get_subtotal();
        $user_id = get_post_meta($id_order, '_customer_user', true);
        if (array_key_exists($user_id, $users_sqm)) {
            $users_sqm[$user_id] = $users_sqm[$user_id] + $property_total;
            $users_gbp[$user_id] = $users_gbp[$user_id] + $property_total_gbp;
        } else {
            $users_sqm[$user_id] = $property_total;
            $users_gbp[$user_id] = $property_total_gbp;
        }

    }
    return array($users_sqm, $users_gbp);

}

?>