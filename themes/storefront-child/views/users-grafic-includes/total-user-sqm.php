<!-- *********************************************************************************************
    Start Total user sqm
*********************************************************************************************	-->

<br>

<hr>
<h3>Total Users SQM</h3>
<br>
<div class="row">
    <div class="col-md-12">
        <div id="container"></div>
    </div>
    <!--div class="col-md-6">
        <div id="container2"></div>
    </div-->
</div>

<?php
$start = microtime(true);

$total = $sum_total = $sum_total_train = $total_order_shipping = array();
$total_dolar = $total_gbp = $total_gbp_train = array();
$months = array('01' => 'Jan.', '02' => 'Feb.', '03' => 'Mar.', '04' => 'Apr.', '05' => 'May', '06' => 'Jun.', '07' => 'Jul.', '08' => 'Aug.', '09' => 'Sep.', '10' => 'Oct.', '11' => 'Nov.', '12' => 'Dec.');
$months_sum = array('01' => 0, '02' => 0, '03' => 0, '04' => 0, '05' => 0, '06' => 0, '07' => 0, '08' => 0, '09' => 0, '10' => 0, '11' => 0, '12' => 0);
$i = 1;

$orders = get_posts(
    array(
        'post_type' => 'shop_order',
        'posts_per_page' => -1,
        'post_status' => array('wc-on-hold', 'wc-completed', 'wc-pending', 'wc-processing', 'wc-inproduction', 'wc-paid', 'wc-waiting', 'wc-revised', 'wc-inrevision'),
        'date_query' => array(
            'after' => date('Y-m-d', mktime(0, 0, 0, 1, 1, date('Y') - 1)),
        ),
        'fields' => 'ids'
    )
);

global $wpdb;
$tablename = $wpdb->prefix . 'custom_orders';
//$sum_total[date('Y')] = $months_sum;
$myOrders = array();
$myResult = $wpdb->get_results("SELECT idOrder, sqm, usd_price FROM $tablename WHERE idOrder IN (" .
    implode(',', array_map('intval', $orders)) . ")", ARRAY_A);
foreach ($myResult as $line) {
    $myOrders[$line['idOrder']] = array('sqm' => $line['sqm'], 'usd_price' => $line['usd_price']);
}

foreach ($orders as $order_id) {
    $order = wc_get_order($order_id);
    $order_data = $order->get_data();
    $myOrder = $myOrders[$order_id];
    $user_id_customer = get_post_meta($order_id, '_customer_user', true);
    $average_train = get_post_meta($order_id, 'average_train', true);
    $average_truck = get_post_meta($order_id, 'average_truck', true);
    if(empty($average_train)) $average_train = 0;
    if(empty($average_truck)) $average_truck = 0;
    // sqm total order
    $property_total = (float)$myOrder['sqm'];

    $text = $order_data['date_created']->date('Y-m-d H:i:s');
    preg_match('/-(.*?)-/', $text, $match);
    preg_match('/(.*?)-/', $text, $match_year);
    $month = $match[1];
    $year = $match_year[1];

    if (!array_key_exists($year, $sum_total)) {
        $sum_total[$year] = $months_sum;
        $sum_total_train[$year] = $months_sum;
        $total_dolar[$year] = $months_sum;
        $total[$year] = $months_sum;
        $total_gbp[$year] = $months_sum;
        $total_gbp_train[$year] = $months_sum;
        $total_train_calc[$year] = $months_sum;
        $total_truck_calc[$year] = $months_sum;
    }


    $train_price = 0;
    if ($property_total > 0 && $year >= 2021) {
//        $train_price = get_user_meta($user_id_customer, 'train_price', true);
//        if (empty($train_price) || $train_price < 10) {
//            $train_price = get_post_meta(1, 'train_price', true);
//        }

        $train_price = get_post_meta($order_id, 'order_train', true);
        if (empty($train_price) || $train_price < 10) {
            $train_price = 10 * $property_total;
        }
    }

    $sum_total_train[$year][$month] = $sum_total_train[$year][$month] + $train_price;

    $suma = $order->get_total();
    $sum_total[$year][$month] = $sum_total[$year][$month] + $suma;

    // USD price
    $property_total_dolar = (float)$myOrder['usd_price'];
    $total_dolar[$year][$month] = $total_dolar[$year][$month] + $property_total_dolar;

    // GBP price
    $property_total_gbp = $order->get_subtotal();
    $total_gbp[$year][$month] = $total_gbp[$year][$month] + $property_total_gbp - $train_price;
    $total_gbp_train[$year][$month] = $total_gbp[$year][$month] + $property_total_gbp;

    // SQM
    $total[$year][$month] = $total[$year][$month] + $property_total;

    $order_transport_train = (float)$average_train * $property_total;
    $total_train_calc[$year][$month] = $total_train_calc[$year][$month] + $order_transport_train;
    $order_transport_truck = (float)$average_truck * $property_total;
    $total_truck_calc[$year][$month] = $total_truck_calc[$year][$month] + $order_transport_truck;


    $order_shipping_total = (float)$order_data['shipping_total'];

    if (is_array($order_shipping_total) || empty($order_shipping_total) || $order_shipping_total == '') {
        $order_shipping_total = 0;
        $total_order_shipping[$year][$month] = $total_order_shipping[$year][$month] + $order_shipping_total;
    } elseif (!empty($order_shipping_total) || !is_array($order_shipping_total) || $order_shipping_total != '') {
        $total_order_shipping[$year][$month] = $total_order_shipping[$year][$month] + $order_shipping_total;
    }
}
?>
<input type="hidden" name="totalcart" value="<?php print_r($total); ?>">

<?php
//$new = array('01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12');
$luni = array('01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12');
$newlunian = array();
$new = array();
for ($i = 11; $i >= 0; $i--) {
//teo				$newlunian[] = date('Y-m', strtotime('-'.$i.' months'));
    $newlunian[] = date('Y-m', mktime(0, 0, 0, date('m') - $i, 1, date('Y')));
}

$current_year = date("y");
foreach ($newlunian as $k => $anluna) {
    $pieces = explode("-", $anluna);
    $an = $pieces[0]; // piece1
    $luna = $pieces[1]; // piece2
    if (!empty($total[$an][$luna])) {
        $new[$luna] = round($total[$an][$luna], 0);
        //print_r($t);
    } else {
        if ($new[$luna] != 0) {
        } else {
            $new[$luna] = 0;
            //print_r($new[$luna]);
        }
    }

}

//echo '<pre>';
//print_r($sum_total_train);
//echo '</pre>';

?>

<br>
<div class="row">
    <div class="col-sm-12">
        <table class="table table-bordered table-striped avg-table">
            <tbody>

            <tr>
                <th>Year</th>
                <th>Month</th>
                <th style="text-align:right">m2</th>
                <th style="text-align:right">USD</th>
                <th style="text-align:right">Total.Train $/m2</th>
                <th style="text-align:right">Total.Truck €/m2</th>
                <th style="text-align:right"></th>
                <th style="text-align:right">GBP</th>
                <th style="text-align:right">Delivery</th>
                <th style="text-align:right">Train</th>
                <th style="text-align:right">GBP Total</th>
            </tr>
            <?php
            $current_year = date("Y");
            $sum_tot_sqm = 0;
            $sum_tot_dolar = 0;
            $sum_tot_gbp = 0;
            $sum_tot_train = 0;
            $sum_tot_prices = 0;
            $sum_shipping = 0;
            foreach ($sum_total as $year => $sum_ty) {
                foreach ($luni as $luna) {
                    if ($sum_ty[$luna] || array_key_exists()) {
                        $sqm = $total[$year][$luna];
                        $new_price = 0;
                        if ($sqm > 0 && $year >= 2021) {
                            $train_price = 10;
                            $new_price = (float)$sqm * (float)$train_price;
                        }

                        echo '<tr>
                            <td>' . $year . '</td>
                            <td>' . $months[$luna] . '</td>
                            <td style="text-align:right">' . number_format($total[$year][$luna], 2) . '</td>
                            <td style="text-align:right">' . number_format($total_dolar[$year][$luna], 2) . '</td>
                            <td style="text-align:right">'.number_format($total_train_calc[$year][$luna], 2).'</td>
                            <td style="text-align:right">'.number_format($total_truck_calc[$year][$luna], 2).'</td>
                            <td></td>
                            <td style="text-align:right">' . number_format($total_gbp[$year][$luna], 2) . '</td>
                            <td style="text-align:right">' . number_format($total_order_shipping[$year][$luna], 2) . '</td>
                            <td style="text-align:right">' . number_format($sum_total_train[$year][$luna], 2) . '</td>
                            <td style="text-align:right">' . number_format($sum_total[$year][$luna]/1.2, 2) . '</td>
                        </tr>';
                        $sum_tot_sqm = $sum_tot_sqm + $total[$year][$luna];
                        $sum_tot_dolar = $sum_tot_dolar + $total_dolar[$year][$luna];
                        $sum_tot_gbp = $sum_tot_gbp + $total_gbp[$year][$luna];
                        $sum_tot_prices = $sum_tot_prices + $sum_ty[$luna];
                        $sum_shipping = $sum_shipping + $total_order_shipping[$year][$luna];
                        $sum_tot_train = $sum_tot_train + $sum_total_train[$year][$luna];
                    } else {
                        echo '<tr>
                            <td>' . $year . '</td>
                            <td>' . $months[$luna] . '</td>
                            <td style="text-align:right"></td>
                            <td style="text-align:right"></td>
                            <td style="text-align:right"></td>
                             <td style="text-align:right"></td>
                            <td style="text-align:right"></td>
                            <td style="text-align:right"></td>
                            <td style="text-align:right"></td>
                            <td style="text-align:right"></td>
                            <td style="text-align:right"></td>
                        </tr>';
                    }
                }
            }
            ?>

            <tr>
                <td colspan="2"
                    style="text-align:right">
                    <strong>Totals</strong>
                </td>
                <td style="text-align:right">
                    <?php echo number_format($sum_tot_sqm, 2); ?>
                </td>
                <td style="text-align:right">
                    <?php echo number_format($sum_tot_dolar, 2); ?>
                </td>
                <td style="text-align:right"></td>
                <td style="text-align:right"></td>
                <td style="text-align:right"></td>
                <td style="text-align:right">
                    <?php echo number_format($sum_tot_gbp, 2); ?>
                </td>
                <td style="text-align:right">
                    <?php echo number_format($sum_shipping, 2); ?>
                </td>
                <td style="text-align:right">
                    <?php echo number_format($sum_tot_train, 2); ?>
                </td>
                <td style="text-align:right">
                    <?php echo number_format($sum_tot_prices/1.2, 2); ?>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</div>


<?php
$months_cart = array();
for ($i = 11; $i >= 0; $i--) {
    $month = date('M y', mktime(0, 0, 0, date('m') - $i, 1, date('Y')));
    $months_cart[] = $month;
}
?>

<script>
    jQuery(document).ready(function () {
        var sqm = jQuery('input[name="totalcart"]').val();
        var js_array = [<?php echo '"' . implode('","', $new) . '"' ?>];
        console.log(sqm);
        console.log(js_array);
        var ints = js_array.map(parseFloat);

        Highcharts.chart('container', {
            "chart": {
                "type": 'column'
            },
            "title": {
                "text": null
            },
            "legend": {
                "align": "right",
                "verticalAlign": "top",
                "y": 75,
                "x": -50,
                "layout": "vertical"
            },
            "xAxis": {
                "categories": <?php echo json_encode($months_cart); ?>
            },
            "yAxis": [{
                "title": {
                    "text": "m2",
                    "margin": 70
                },
                "min": 0
            }],
            "tooltip": {
                "enabled": true,
                "valueDecimals": 0,
                "valueSuffix": " sqm"
            },

            "plotOptions": {
                "column": {
                    "dataLabels": {
                        "enabled": true,
                        "valueDecimals": 0
                    },
                    enableMouseTracking: true
                }
            },

            "credits": {
                "enabled": true
            },

            "subtitle": {},
            "series": [{
                "name": "m2",
                "yAxis": 0,
                "data": ints
            }]

        });



    });


    var timer = null;
    jQuery('input.avg').keyup(function(){
        clearTimeout(timer);
        timer = setTimeout(sendData, 2000)
    });
    function sendData() {
        var values = {};
        jQuery('input.avg').each(function() {
            if(this.value > 0) { values[this.name] = parseFloat(this.value); }
            else { values[this.name] = parseFloat(0); }

        });

        console.log(values);
        var data = {
            'action': 'average_per_month_train',
            'avgs': values
        };
        // We can also pass the url value separately from ajaxurl for front end AJAX implementations
        jQuery.post('/wp-admin/admin-ajax.php', data, function(response) {
            console.log(response);
        });
    }


</script>

<!-- *********************************************************************************************
    End Total user sqm
*********************************************************************************************	-->

<?php
$stop = microtime(true);
print_r($stop - $start);
?>