<?php
if ($_POST['group_name']) {
	echo '<h3>' . $_POST['group_name'] . ' SQM</h3>';
} else {
	$groups_created = get_post_meta(1, 'groups_created', true);
	echo '<h3>All Groups SQM</h3>';
}
?>

<!-- *********************************************************************************************
                   Start single user sqm
*********************************************************************************************	-->
<hr>
<br>
<div class="row">
  <div class="col-md-12">
    <div id="container"></div>
  </div>
</div>
<?php

function starts_with($s, $prefix)
{
	// returns a bool
	return strpos($s, $prefix) === 0;
}


function order_items_materials_sqm($order_id)
{
	$materials = array(187 => 'Earth', 137 => 'Green', 138 => 'Biowood', 139 => 'Supreme', 188 => 'Ecowood');
	$order_material_sqm = array();
	$order = wc_get_order($order_id);
	$items = $order->get_items();

	$attributes_array = get_post_meta(1, 'attributes_array', true);

	foreach ($items as $item_id => $item) {
		$product_id = $item['product_id'];
		$material_id = get_post_meta($product_id, 'property_material', true);
		$property_frametype = get_post_meta($product_id, 'property_frametype', true);
		$material = $materials[$material_id];
		$sqm = get_post_meta($product_id, 'property_total', true);
		// do something with the material value
		if (array_key_exists($material, $order_material_sqm)) {

			if ($material_id == 138 && starts_with($attributes_array[$property_frametype], "P")) {
				$prev_value = $order_material_sqm['BiowoodP'];
				$order_material_sqm['BiowoodP'] = $sqm + $prev_value;
			} else if ($material_id == 138 && !starts_with($attributes_array[$property_frametype], "P")) {
				$prev_value = $order_material_sqm['BiowoodNonP'];
				$order_material_sqm['BiowoodNonP'] = $sqm + $prev_value;
			}
			$prev_value = $order_material_sqm[$material];
			$order_material_sqm[$material] = $sqm + $prev_value;
		} else {
			$order_material_sqm[$material] = $sqm;
			if ($material_id == 138 && starts_with($attributes_array[$property_frametype], "P")) {
				$order_material_sqm['BiowoodP'] = $sqm;
			} else if ($material_id == 138 && !starts_with($attributes_array[$property_frametype], "P")) {
				$order_material_sqm['BiowoodNonP'] = $sqm;
			}
		}
	}
	return $order_material_sqm;
}


if ($_POST['group_name']) {
	if ($_POST['group_name'] === 'all_users') {
		$users = get_users(array('fields' => array('ID')));
		$group = array();
		foreach ($users as $user) {
			$group[] = $user->ID;
		}
	} else {
		$group = get_post_meta(1, $_POST['group_name'], true);
	}
} else {
	$groups_created = get_post_meta(1, 'groups_created', true);
	$group = array();
	for ($i = 0; $i < count($groups_created); $i++) {
		$group_single = get_post_meta(1, $groups_created[$i], true);
		$group = array_merge($group, $group_single);
	}
}
////print_r($groups_created);
//print_r(count($group));

$total = array();
$total2 = array();
$totaly = array();

$total_dolar = array();
$total2_dolar = array();
$totaly_dolar = array();

$total_gbp = array();
$total2_gbp = array();
$totaly_gbp = array();

$sum_total = array();
$sum_totaly = array();
$total_order_shipping = array();
$total2_order_shipping = array();
$totaly_order_shipping = array();
$i = 1;

$orders = array();
//foreach ($group as $key => $user_id) {

//$users = get_users( array( 'fields' => array( 'ID' ) ) );

//print_r($users);
//foreach( $users as $user ){

//print_r('User foreach id: '.$user->ID);
// an selectat
if (isset($_POST['an_select'])) {
	$selected_year = $_POST['an_select'];
} else {
	$selected_year = date("Y");
}
$start = microtime(true);

$args = array(
	'customer_id' => $group,
	'limit' => -1,
	'type' => 'shop_order',
	'status' => array('wc-on-hold', 'wc-completed', 'wc-pending', 'wc-processing', 'wc-inproduction', 'wc-paid', 'wc-waiting', 'wc-revised', 'wc-inrevision'),
	'orderby' => 'date',
	'date_query' => array(
		'after' => date('Y-m-d', mktime(0, 0, 0, 1, 0, $selected_year)),
	),
	'return' => 'ids',
);
// echo date('Y-m-d', mktime(0, 0, 0, 1, 1, $selected_year));
//echo 'User id: '.$user_id;
$orders = wc_get_orders($args);
// $orders = array_merge($orders, $orders_user);
//}
// print_r($orders);
// echo '<pre>';
// 	print_r($orders);
// echo '</pre>';

global $wpdb;
$tablename = $wpdb->prefix . 'custom_orders';
//$sum_total[date('Y')] = $months_sum;
$myOrders = array();
$myResult = $wpdb->get_results("SELECT idOrder, sqm, usd_price FROM $tablename WHERE idOrder IN (" .
	implode(',', array_map('intval', $orders)) . ")", ARRAY_A);
foreach ($myResult as $line) {
	$myOrders[$line['idOrder']] = array('sqm' => $line['sqm'], 'usd_price' => $line['usd_price']);
}

// print_r($myOrders);

$total = array();
$totaly = array();
$sum_total = array();
$sum_totaly = array();
$sum_totaly_train = array();
$total_order_shipping = array();
$total2_order_shipping = array();
$totaly_order_shipping = array();
$total_dolar = array();
$total_gbp = array();
$total_gbp_train = array();
$total_materials_sqm = array();
$months = array('01' => 'Jan.', '02' => 'Feb.', '03' => 'Mar.', '04' => 'Apr.', '05' => 'May', '06' => 'Jun.', '07' => 'Jul.', '08' => 'Aug.', '09' => 'Sep.', '10' => 'Oct.', '11' => 'Nov.', '12' => 'Dec.');
$months_sum = array('01' => 0, '02' => 0, '03' => 0, '04' => 0, '05' => 0, '06' => 0, '07' => 0, '08' => 0, '09' => 0, '10' => 0, '11' => 0, '12' => 0);
$i = 1;

// Get all orders and find date and and calculate total payment for each month
$count_month = 0;
foreach ($orders as $order_id) {
	$materials = array('Earth' => 0, 'Green' => 0, 'Biowood' => 0, 'Supreme' => 0, 'Ecowood' => 0);

	$order = wc_get_order($order_id);

	$order_data = $order->get_data();
	$myOrder = $myOrders[$order_id];
	$user_id_customer = get_post_meta($order_id, '_customer_user', true);
	// sqm total order
	$property_total = floatval($myOrder['sqm']);

	$text = $order_data['date_created']->date('Y-m-d H:i:s');
	preg_match('/-(.*?)-/', $text, $match);
	preg_match('/(.*?)-/', $text, $match_year);
	$month = $match[1];
	$year = $match_year[1];

	if (!array_key_exists($year, $sum_total)) {
		$sum_total[$year] = $months_sum;
		$sum_totaly[$year] = $months_sum;
		$sum_totaly_train[$year] = $months_sum;
		$total_dolar[$year] = $months_sum;
		$total2_dolar[$year] = $months_sum;
		$totaly_dolar[$year] = $months_sum;
		$total[$year] = $months_sum;
		$total2[$year] = $months_sum;
		$totaly[$year] = $months_sum;
		$total_gbp[$year] = $months_sum;
		$total_gbp_train[$year] = $months_sum;
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
	$sum_totaly_train[$year][$month] = $sum_totaly_train[$year][$month] + $train_price;

	$suma = floatval($order->get_total());

// materials
	$items_material = order_items_materials_sqm($order_id);
//    echo '<pre>';
//    print_r($items_material);
//    echo '</pre>';
	foreach ($items_material as $material => $material_sqm) {
		$prev_sqm = $materials[$material];
		$materials[$material] = $material_sqm + $prev_sqm;

		$total_materials_sqm[$year][$month][$material] = $total_materials_sqm[$year][$month][$material] + $material_sqm + $prev_sqm;
	}

	// sqm's
	$sum_total[$year][$month] = $sum_total[$year][$month] + $suma;
	$sum_totaly[$year][$month] = $sum_totaly[$year][$month] + $suma;

	// USD price
	$property_total_dolar = floatval($myOrder['usd_price']);
	$total_dolar[$year][$month] = $total_dolar[$year][$month] + $property_total_dolar;
	$total2_dolar[$year][$month] = $total2_dolar[$year][$month] + $property_total_dolar;
	$totaly_dolar[$year][$month] = $totaly_dolar[$year][$month] + $property_total_dolar;

	// GBP price
	$property_total_gbp = $order->get_subtotal();
	$total_gbp[$year][$month] = $total_gbp[$year][$month] + $property_total_gbp - $train_price;
	$total_gbp_train[$year][$month] = $total_gbp[$year][$month] + $property_total_gbp;

	// SQM
	$total[$year][$month] = $total[$year][$month] + $property_total;
	$total2[$year][$month] = $total2[$year][$month] + $property_total;
	$totaly[$year][$month] = $totaly[$year][$month] + $property_total;

//    if($month == 11) {
//        $count_month++;
//        echo '<pre>';
//        $user_info = get_userdata($user_id_customer);
//        echo 'order id '.$order_id.'<br>';
//        echo "user name ". $user_info->display_name . " - " . $user_info->first_name . " " . $user_info->last_name."<br>";
//        echo 'sqm '.$property_total.'<br>';
//        print_r($count_month);
//        echo '</pre>';
//    }

	$order_shipping_total = floatval($order_data['shipping_total']);

	if (is_array($order_shipping_total) || empty($order_shipping_total) || $order_shipping_total == '') {
		$order_shipping_total = 0;
		$total_order_shipping[$year][$month] = $total_order_shipping[$year][$month] + $order_shipping_total;
		$total2_order_shipping[$year][$month] = $total2_order_shipping[$year][$month] + $order_shipping_total;
		$totaly_order_shipping[$year][$month] = $totaly_order_shipping[$year][$month] + $order_shipping_total;
	} elseif (!empty($order_shipping_total) || !is_array($order_shipping_total) || $order_shipping_total != '') {
		$total_order_shipping[$year][$month] = $total_order_shipping[$year][$month] + $order_shipping_total;
		$total2_order_shipping[$year][$month] = $total2_order_shipping[$year][$month] + $order_shipping_total;
		$totaly_order_shipping[$year][$month] = $totaly_order_shipping[$year][$month] + $order_shipping_total;
	}
}
?>
<input type="hidden" name="totalcart" value="<?php print_r($total); ?>">

<?php
//$new = array('01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12');
$luni = array('01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12');
$newlunian = array();
$new = array();
$news = array();
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
//print_r($sum_totaly_train);
//echo '</pre>';

?>

<br>
<div class="row">
  <div class="col-sm-8">
    <table class="table table-bordered table-striped">
      <tbody>

      <tr>
        <th>Year</th>
        <th>Month</th>
        <th style="text-align:right">m2</th>
        <th style="text-align:right">USD</th>
        <th style="text-align:right">GBP</th>
        <th style="text-align:right">Delivery</th>
        <th style="text-align:right">Train</th>
        <th style="text-align:right">GBP Total</th>
        <th style="text-align:right">Earth</th>
        <th style="text-align:right">Ecowood</th>
        <th style="text-align:right">Green</th>
        <th style="text-align:right">Biowood</th>
        <th style="text-align:right">Supreme</th>
      </tr>
			<?php
			$current_year = date("Y");

			$sum_tot_sqm = 0;
			$sum_tot_dolar = 0;
			$sum_tot_gbp = 0;
			$sum_tot_train = 0;
			$sum_tot_prices = 0;
			$sum_tot_dolar = 0;
			$sum_tot_dolar = 0;
			$sum_shipping = 0;
			foreach ($sum_totaly as $year => $sum_ty) {
				foreach ($luni as $luna) {
					if ($sum_ty[$luna] || array_key_exists()) {
						$sqm = $totaly[$year][$luna];
						$new_price = 0;
						if ($sqm > 0 && $year >= 2021) {
							$train_price = 10;
							$new_price = floatval($sqm) * floatval($train_price);
						}
						echo '<tr>
                            <th>' . $year . '</th>
                            <th>' . $months[$luna] . '</th>
                            <th style="text-align:right">' . number_format($totaly[$year][$luna], 2) . '</th>
                            <th style="text-align:right">' . number_format($total_dolar[$year][$luna], 2) . '</th>
                            <th style="text-align:right">' . number_format($total_gbp[$year][$luna], 2) . '</th>
                            <th style="text-align:right">' . number_format($total_order_shipping[$year][$luna], 2) . '</th>
                            <th style="text-align:right">' . number_format($sum_totaly_train[$year][$luna], 2) . '</th>
                            <th style="text-align:right">' . number_format($sum_totaly[$year][$luna], 2) . '</th>
                            <th style="text-align:right">' . number_format($total_materials_sqm[$year][$luna]['Earth'], 2) . '</th>
                            <th style="text-align:right">' . number_format($total_materials_sqm[$year][$luna]['Ecowood'], 2) . '</th>
                            <th style="text-align:right">' . number_format($total_materials_sqm[$year][$luna]['Green'], 2) . '</th>
                            <th style="text-align:right">' . number_format($total_materials_sqm[$year][$luna]['Biowood'], 2) . '</th>
                            <th style="text-align:right">' . number_format($total_materials_sqm[$year][$luna]['Supreme'], 2) . '</th>
                        </tr>';
						$sum_tot_sqm = $sum_tot_sqm + $total[$year][$luna];
						$sum_tot_dolar = $sum_tot_dolar + $total_dolar[$year][$luna];
						$sum_tot_gbp = $sum_tot_gbp + $total_gbp[$year][$luna];
						$sum_tot_prices = $sum_tot_prices + $sum_ty[$luna];
						$sum_shipping = $sum_shipping + $total_order_shipping[$year][$luna];
						$sum_tot_train = $sum_tot_train + $sum_totaly_train[$year][$luna];
					} else {
						echo '<tr>
                            <th>' . $year . '</th>
                            <th>' . $months[$luna] . '</th>
                            <th style="text-align:right"></th>
                            <th style="text-align:right"></th>
                            <th style="text-align:right"></th>
                            <th style="text-align:right"></th>
                            <th style="text-align:right"></th>
                            <th style="text-align:right"></th>
                            <th style="text-align:right"></th>
                            <th style="text-align:right"></th>
                            <th style="text-align:right"></th>
                            <th style="text-align:right"></th>
                            <th style="text-align:right"></th>
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
					<?php echo number_format($sum_tot_prices, 2); ?>
        </td>
      </tr>
      </tbody>
    </table>
  </div>
</div>

<!-- *********************************************************************************************
    End Total user sqm
*********************************************************************************************	-->

<?php
$stop = microtime(true);
print_r($stop - $start);
?>
<!-- *********************************************************************************************
End single user sqm
*********************************************************************************************	-->

