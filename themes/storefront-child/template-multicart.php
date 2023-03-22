<?php
/**
 * The template for displaying full width pages.
 *
 * Template Name: Full width Multi Cart
 *
 */

get_header(); ?>

  <div id="primary" class="content-area">
    <main id="main" class="site-main" role="main">

      <!-- Multi Cart Template -->
      <?php if (is_active_sidebar('multicart_widgett')) : ?>
        <!--                <div id="bmc-woocom-multisession-2" class="widget bmc-woocom-multisession-widget">-->
        <!--                    --><?php ////dynamic_sidebar( 'multicart_widgett' ); ?>
        <!--                </div>-->
        <!-- #primary-sidebar -->
      <?php endif;

      if (is_user_logged_in()) {

        $user_id = get_current_user_id();
        $meta_key = 'wc_multiple_shipping_addresses';

        $suspended = get_user_meta($user_id, 'suspended_user', true);

        global $wpdb;
        if ($addresses = $wpdb->get_var($wpdb->prepare("SELECT meta_value FROM {$wpdb->usermeta} WHERE user_id = %d AND meta_key = %s", $user_id, $meta_key))) {
          $addresses = maybe_unserialize($addresses);
        }

        $addresses = $wpdb->get_results("SELECT meta_value FROM `wp_usermeta` WHERE user_id = $user_id AND meta_key = '_woocom_multisession'");

        if ($suspended != 'yes') {
          ?>
          <h2>My Orders</h2>

          <br>

          <div class="alert alert-warning">
            <?php echo get_post_meta(1, 'notification_users_message', true); ?>
          </div>

          <br>
          <div class="show-table2"></div>

          <?php
//                $suspended = get_user_meta($user_id, 'suspended_user', true);
//                if ($suspended != 'yes') {
          ?>
          <a class="btn btn-primary" href="#new" onclick="return BMCWcMs.command('insert', this);">New
            Order</a>
          <a class="btn btn-primary" href="#newpos" onclick="return BMCWcMs.command('insertpos', this);">Order
            Samples</a>

          <?php
          $user = wp_get_current_user();
          $roles = $user->roles;
          if (in_array('component_buyer', $roles) || in_array('administrator', $roles) || get_current_user_id() == 23 || get_current_user_id() == 207 || get_current_user_id() == 39) {
            ?>
            <a class="btn btn-primary" href="#newcomponent" style="background-color: deeppink;"
               onclick="return BMCWcMs.command('insertcomponent', this);">
              Order Components
            </a>
          <?php }
          ?>

          <a href="/order-repairs" class="btn btn-danger">Order Repairs</a>


          <!--                    <button class="btn btn-primary" onclick="sendTestMail()">Send Test Mail</button>-->
          <!-- <a class="btn btn-primary" href="/order-pos" >Order POS</a> -->
          <p></p>
          <table class="table home">
            <thead style="background-color: #f2dede;">
            <th></th>
            <th>Unfinished Orders</th>
            <th>Date Created</th>
            <th>Is selected?</th>
            <th></th>
            <th></th>
            <th></th>
            </thead>
            <tbody>
            <?php

            $userialize_data = unserialize($addresses[0]->meta_value);
            $session_key = $userialize_data['customer_id'];

            $session = $wpdb->get_results("SELECT session_value FROM `wp_woocommerce_sessions` WHERE `session_key` LIKE $session_key ");
            //print_r($session);
            $userialize_session = unserialize($session[0]->session_value);
            // echo '<pre>';
            // print_r($userialize_session);
            // print_r($userialize_data);
            // echo '</pre>';

            $i = 1;
            $carts_sort = $userialize_data['carts'];
            ksort($carts_sort);
            $total_elements = count($carts_sort) + 1;

            foreach ($carts_sort as $key => $carts) {
              // echo '<pre>';
              // print_r($userialize_session);
              // echo '</pre>';
              $tag = '#' . $i;

              $pieces = explode("#", $carts['name']);
              $pieces[0]; // piece1
              $pieces[1]; // piece2

              if ($carts['name'] == 'Order #1') {

              } elseif ($pieces[0] == 'Order ') {

                unset($userialize_data['carts'][$key]);
              } else {
                ?>
                <tr class="<?php if ($userialize_data['customer_id'] == $key) {
                  echo " tr-selected ";
                } ?>" position="<?php echo $i; ?>">
                  <td>
                    <?php echo $total_elements - $i; //echo ' - '.$key;
                    ?>
                  </td>
                  <td>
                    <?php if ($userialize_data['customer_id'] == $key) { ?>
                      <a href="/checkout/">
                        <strong>
                          <?php echo $carts['name']; ?>
                        </strong>
                      </a>
                    <?php } else {
                      echo $carts['name'];
                    } ?>
                  </td>
                  <td>
                    <?php echo $newDate = date("d-m-Y", $carts['time']); ?>
                  </td>
                  <td>
                    <?php if ($userialize_data['customer_id'] == $key) {
                      echo "Current Order";
                    } else {
                      echo "Not Selected";
                    } ?>
                  </td>
                  <td>
                    <?php if ($userialize_data['customer_id'] != $key) { ?>
                      <a class="btn btn-primary ab-item" aria-haspopup="true" href="#select"
                         target="<?php echo $key; ?>"
                         onclick="return BMCWcMs.command('select', this);"
                         title="Select Cart">Select</a>

                    <?php } else {
                      echo "Selected";
                    } ?>
                    <?php if ($userialize_data['customer_id'] != $key) { ?>
                      <a class="btn btn-primary" href="#edit" target="<?php echo $key; ?>"
                         onclick="return BMCWcMs.command('update', this);">Edit Name</a>
                    <?php } ?>
                  </td>
                  <td>

                    <?php if ($userialize_data['customer_id'] != $key) { ?>
                      <!-- <a class="btn btn-primary" href="#clone" target="<?php echo $key; ?>" onclick="return BMCWcMs.command('clone', this);" title="Create a new cart with the items and addresses of this cart.">Clone Order</a> -->
                    <?php } ?>
                  </td>
                  <td>
                    <?php //if($userialize_data['customer_id'] != $key){
                    ?>
                    <a class="btn btn-danger delete-btn" indice="<?php echo $i; ?>" href="#delete"
                       target="<?php echo $key; ?>"
                       onclick="return BMCWcMs.command('delete', this);">Delete
                      Order</a>
                    <?php //}
                    ?>
                  </td>
                </tr>

                <?php
              }
              $i++;
            }

            // update multi carts array with custom orders
            update_user_meta($user_id, '_woocom_multisession', $userialize_data);
            //                            echo '<pre>';
            //                                print_r($userialize_data);
            //                            echo '</pre>';
            ?>

            </tbody>

          </table>

          <?php
          // ksort($carts_sort);
          // echo '<pre>';
          //print_r($carts_sort);
          // echo '</pre>';
//
//                }
        }
        ?>

        <br>
        <br>

        <?php if (!in_array('china_admin', $roles)) {
          if ($suspended != 'yes') {
            ?>

            <h2>My Placed Orders to Edit</h2>

            <p></p>
            <table class="table home">
              <thead style="background-color: #f2dede;">
              <th></th>
              <th>Editable Orders</th>
              <th>Date Created</th>
              <th></th>
              <th></th>
              </thead>
              <tbody>
              <?php
              $i = 1;
              $customer_id = get_current_user_id();
              $orders_to_edit_array = get_user_meta($customer_id, 'orders_to_edit', true);
              if ($orders_to_edit_array) {
                foreach ($orders_to_edit_array as $order_id => $editable_status) {
                  // Get an instance of the WC_Order Object from the Order ID (if required)
                  $order = wc_get_order($order_id);

                  $order_number = $order->get_order_number(); //teo for Order id below

                  $order_data = $order->get_data(); // The Order data
                  ?>
                  <tr>
                    <td>
                      <?php echo $i; //echo ' - '.$key;
                      ?>
                    </td>
                    <td>
                      <?php echo 'LF0' . $order->get_order_number() . ' - <i>' . get_post_meta($order->get_id(), 'cart_name', true) . '</i>';
                      ?>
                    </td>
                    <td>
                      <?php echo wc_format_datetime($order->get_date_created()); ?>
                    </td>
                    <td>
                      <a href="/editable-order/?id=<?php
                      echo $order->get_id() * 1498765 * 33;
                      ?>" class="btn btn-primary">View Order and Edit</a>
                    </td>
                    <td>
                    </td>
                  </tr>

                  <?php
                  $i++;
                }
              }
              ?>

              </tbody>

            </table>

          <?php }

          ?>

          <br>
          <br>

          <!-- Chart content -->
          <h3 class="text-center">
            <strong>m2 SALES PER MONTH</strong>
          </h3>
          <hr>
          <div id="container"></div>

          <br>
          <h2>My Transformed Orders</h2>

          <table class="table">
            <thead style="background-color: #f2dede;">
            <!--                        <th></th>-->
            <th>Order ID</th>
            <th>SQM</th>
            <th>Date</th>
            <th>Deliveries Start</th>
            <th>Total</th>
            <th>Status</th>
            <th>Added by</th>
            <th>Last Update</th>
            <th></th>
            </thead>
            <tbody>
            <?php
            $user = get_userdata($user_id);
            $var_view_price = get_user_meta($user_id, 'view_price', true);
            $view_price = ($var_view_price == 'yes' || $var_view_price == '') ? true : false;

            // if dealer if is not empty and current user have employe role and not dealer_employe add dealer orders

            if (in_array('salesman', $user->roles) || in_array('subscriber', $user->roles) || in_array('emplimited', $user->roles) && !in_array('dealer', $user->roles)) {
              // echo 'dealer simplu';
              $users_orders = array($user_id);
            }
            if (in_array('employe', $user->roles) || in_array('senior_salesman', $user->roles)) {
              // get user company_parent id from meta
              $deler_id = get_user_meta($user_id, 'company_parent', true);
              // initiate users_orders array with logged user id
              //$users_orders = array($user_id);

              if (!empty($deler_id)) {
                $users_orders = array();
                $users_orders = get_user_meta($deler_id, 'employees', true);
                $users_orders[] = $deler_id;
                $users_orders = array_reverse($users_orders);
              }
            }
            if (in_array('dealer', $user->roles)) {
              // echo 'dealer master';
              $users_orders = get_user_meta($user_id, 'employees', true);
              $users_orders[] = $user_id;
              $users_orders = array_reverse($users_orders);
            }
            $i = 1;
            // foreach ($users_orders as $user_id) {

            $args = array(
              'customer_id' => $users_orders,
              'limit' => -1,
            );

            //print_r($user_id);
            $orders = wc_get_orders($args);
            $total = array();
            $total2 = array();
            $months_sum = array('01' => 0, '02' => 0, '03' => 0, '04' => 0, '05' => 0, '06' => 0, '07' => 0, '08' => 0, '09' => 0, '10' => 0, '11' => 0, '12' => 0);
            for ($i = 2019; $i <= date('Y'); $i++) {
              foreach ($months_sum as $luna => $sum) {
                $total[$i][$luna] = $sum;
                $total2[$i][$luna] = $sum;
              }
            }
            $j = 1;

            // echo '<pre>';
            // 	print_r($orders);
            // echo '</pre>';
            $order_statuses = wc_get_order_statuses();

            foreach ($orders as $order) {
              $items = $order->get_items();
              $order_data = $order->get_data();
              $order_id = $order->get_id();

              $order_status = $order_data['status'];
              $status_name = $order_statuses['wc-' . $order_status];
              $previous_status = get_post_meta($order->get_id(), 'previous_status', true);
              $culori_status = array('pending' => 'background-color:#ef8181; color: #fff', 'on-hold' => 'background-color:#f8dda7; color: #fff', 'processing' => 'background-color:#c6e1c6; color: #fff', 'completed' => 'background-color:#c8d7e1; color: #000', 'cancelled' => 'background-color:#e5e5e5; color: #000; font-weight:bold;', 'refound' => 'background-color:#5BC0DE; color: #fff', 'inproduction' => 'background-color:#7878e2; color: #fff', 'transit' => 'background-color:#85bb65; color: #fff', 'waiting' => 'background-color:#3ba000; color: #fff', 'account-on-hold' => 'background-color:red; color: #fff', 'inrevision' => 'background-color:#8B4513; color: #fff', 'revised' => 'background-color:#8B4513; color: #fff');

              global $wpdb;
              $tablename = $wpdb->prefix . 'custom_orders';
              $myOrder = $wpdb->get_row("SELECT sqm FROM $tablename WHERE idOrder = $order_id", ARRAY_A);


              ?>
              <tr class=" ">
                <!--                                <td>-->
                <!--                                    --><?php //echo $j; ?><!--.-->
                <!--                                </td>-->
                <td>
                  <?php echo 'LF0' . $order->get_order_number() . ' - <i>' . get_post_meta($order->get_id(), 'cart_name', true) . '</i>';
                  ?>
                </td>
                <td>
                  <?php
                  echo number_format($myOrder['sqm'], 2);
                  ?>
                </td>
                <td>
                  <?php
                  echo $order_data['date_created']->date('Y-m-d H:i:s');
                  ?>
                </td>
                <td>
                  <?php echo get_post_meta($order->get_id(), 'delivereis_start', true); ?>
                </td>
                <td>
                  <?php
                  if ($view_price) echo $order->get_total();
                  ?>
                </td>
                <td>
                  <p class="statusOrder" style="<?php
                  // foreach ($culori_status as $stat => $cul) {
                  if ($previous_status) {
                    if (array_key_exists($order_status, $culori_status)) {
                      echo $culori_status[$previous_status] . ';';
                    }
                  } else {
                    if (array_key_exists($order_status, $culori_status)) {
                      echo $culori_status[$order_status] . ';';
                    }
                  }
                  // }
                  ?> text-align:center;">Ordered -
                    <?php
                    if ($order_status == 'pending') {
                      echo 'Pending Payment';
                    } //teo>
                    elseif ($order_status == 'waiting') {
                      echo 'Waiting Delivery';
                    } //teo<
                    else {
                      echo $status_name;
                    }
                    ?>
                  </p>
                </td>
                <td>
                  <?php
                  echo $order->billing_first_name . ' ' . $order->billing_last_name;
                  ?>
                </td>
                <td>
                  <?php echo $order_data['date_modified']->date('Y-m-d H:i:s'); ?>
                </td>
                <td>
                  <a href="/view-order/?id=<?php echo $order->get_id() * 1498765 * 33 ?>"
                     class="btn btn-primary">View</a>
                </td>
              </tr>

              <?php
              $j++;
            }
            // }
            ?>

            </tbody>

          </table>

          <?php
        }

        if ($orders) {
          foreach ($orders as $order) {
            $items = $order->get_items();
            $order_data = $order->get_data();
            $order_status = $order_data['status'];

            $text = $order_data['date_created']->date('Y-m-d H:i:s');
            preg_match('/-(.*?)-/', $text, $match);
            preg_match('/(.*?)-/', $text, $match_year);
            $month = $match[1];
            $year = $match_year[1];

            foreach ($items as $item) {

              $prod_id = $item->get_product_id();
              $name = $item->get_name();
              //echo '<a href="/product-edit/?id='.($prod_id*1498765*33).'" target="_blank">'.$name.'</a>';
              //echo '<br>';
              if ($order_status != 'cancelled') {
                $quantity = get_post_meta($prod_id, 'quantity', true);
                $property_total = get_post_meta($prod_id, 'property_total', true);
                if ($property_total) {
                  $total[$year][$month] = $total[$year][$month] + floatval($property_total) * floatval($quantity);
                  $total2[$year][$month] = $total2[$year][$month] + floatval($property_total) * floatval($quantity);
                }
              }
            }
          }
        }
        //$new = array('01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12');
        $luni = array('01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12');

        $newlunian = array();
        for ($i = 11; $i >= 0; $i--) {
//teo				$newlunian[] = date('Y-m', strtotime('-'.$i.' months'));
          $newlunian[] = date('Y-m', mktime(0, 0, 0, date('m') - $i, 1, date('Y')));
        }

        //print_r($newlunian);
        $current_year = date("y");
        //print_r($total);

        //for( $i=11; $i>=0; $i-- ){
        foreach ($newlunian as $k => $anluna) {
          $pieces = explode("-", $anluna);
          $an = $pieces[0]; // piece1
          $luna = $pieces[1]; // piece2
          foreach ($total as $key => $value) {
            if ($key == $an) {
              foreach ($total[$key] as $m => $t) {
                if ($luna == $m) {
                  $new[$luna] = round($t, 0);
                  //print_r($t);
                } else {
                  if ($new[$luna] != 0) {

                  } else {
                    $new[$luna] = 0;
                    //print_r($new[$luna]);
                  }
                }
              }
            } else {
              if (empty($new[$luna])) {
                $new[$luna] = 0;
              } else {
              }
            }
          }
        }

        //print_r(array_reverse($new));
        //$new = array_reverse($new);

        //echo '<pre>';
        //print_r($addresses[0]->meta_value);
        $userialize_data = unserialize($addresses[0]->meta_value);
        //print_r($userialize_data);
        //print_r($userialize_session['cart']);
        $unserialize_cart = $userialize_session['cart'];
        $cart_uns = unserialize($unserialize_cart);
        //print_r($cart_uns);
        $products_ids = array();
        // foreach($cart_uns as $key => $data){
        // 	$products_ids[] = $data['product_id'];

        // }
        //print_r($products_ids);
        //echo '</pre>';

      }
      ?>
      <input type="hidden" name="totalcart" value="<?php print_r($total); ?>">
    </main>
    <!-- #main -->
  </div>
  <!-- #primary -->

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
          "categories": ["<?php echo date('M y', mktime(0, 0, 0, date('m') - 11, 1, date('Y'))); ?>", "<?php echo date('M y', mktime(0, 0, 0, date('m') - 10, 1, date('Y'))); ?>", "<?php echo date('M y', mktime(0, 0, 0, date('m') - 9, 1, date('Y'))); ?>", "<?php echo date('M y', mktime(0, 0, 0, date('m') - 8, 1, date('Y'))); ?>", "<?php echo date('M y', mktime(0, 0, 0, date('m') - 7, 1, date('Y'))); ?>", "<?php echo date('M y', mktime(0, 0, 0, date('m') - 6, 1, date('Y'))); ?>", "<?php echo date('M y', mktime(0, 0, 0, date('m') - 5, 1, date('Y'))); ?>", "<?php echo date('M y', mktime(0, 0, 0, date('m') - 4, 1, date('Y'))); ?>", "<?php echo date('M y', mktime(0, 0, 0, date('m') - 3, 1, date('Y'))); ?>", "<?php echo date('M y', mktime(0, 0, 0, date('m') - 2, 1, date('Y'))); ?>", "<?php echo date('M y', mktime(0, 0, 0, date('m') - 1, 1, date('Y'))); ?>", "<?php echo date('M y'); ?>"]
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
  </script>


<?php
get_footer();
