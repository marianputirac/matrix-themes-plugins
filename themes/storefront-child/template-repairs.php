<?php
/**
 * The template for displaying full width pages.
 *
 * Template Name: Order Repairs Template
 *
 */

get_header(); ?>

    <div id="primary"
         class="content-area">
        <main id="main"
              class="site-main"
              role="main">

            <!-- Multi Cart Template -->
            <?php if (is_active_sidebar('multicart_widgett')) : ?>
                <div id="bmc-woocom-multisession-2"
                     class="widget bmc-woocom-multisession-widget">
                    <?php //dynamic_sidebar( 'multicart_widgett' ); ?>
                </div>
                <!-- #primary-sidebar -->
            <?php endif; ?>

            <?php
            if (is_user_logged_in()) {
                $user_id = get_current_user_id();
                $meta_key = 'wc_multiple_shipping_addresses';

                global $wpdb;
                if ($addresses = $wpdb->get_var($wpdb->prepare("SELECT meta_value FROM {$wpdb->usermeta} WHERE user_id = %d AND meta_key = %s", $user_id, $meta_key))) {
                    $addresses = maybe_unserialize($addresses);
                }

                $addresses = $wpdb->get_results("SELECT meta_value FROM `wp_usermeta` WHERE user_id = $user_id AND meta_key = '_woocom_multisession'");


                ?>
                <h2>
                    Order Repairs
                </h2>
                <?php
            }
            ?>

            <!--            <form action="/add-order-repair"-->
            <!--                  method="post"-->
            <!--                  style="margin: 20px 0;">-->
            <!--                -->
            <!--                <label for="sc_ord"><strong>Insert original LF number</strong></label>-->
            <!--                <input id="sc_ord"-->
            <!--                       type="number"-->
            <!--                       value=""-->
            <!--                       name="order_number"-->
            <!--                >-->
            <!--                <button type="submit"-->
            <!--                        class="btn btn-primary disabled">Apply-->
            <!--                </button>-->
            <!--            </form>-->


            <form action="/order-repairs/"
                  method="GET">
                <div class="row">
                    <div class="col-sm-3 col-lg-3 form-group hide">
                        <label for="q_Deliveries start">Deliveries start</label>
                        <br>
                        <input id="q_container_contents_container_delivery_week_at_casted_eq"
                               name="deliveries"
                               type="text"
                               class="form-control">
                    </div>
                    <div class="col-sm-3 col-lg-3 form-group hide">
                        <label for="q_Status">Status</label>
                        <br>
                        <select id="q_status_order_id_eq"
                                name="status_order"
                                class="form-control">
                            <option value="">Please select...</option>
                            <option value="processing">Ordered - Pending</option>
                            <option value="on-hold">on-hold</option>
                            <option value="completed">completed</option>
                            <option value="refunded">refunded</option>
                            <option value="failed">failed</option>
                        </select>
                    </div>
                    <div class="col-sm-6 col-lg-3 form-group">
                        <label for="q_Order reference">Order reference</label>
                        <!--                            <br>-->
                        <input data-autocomplete="/orders/autocomplete_order_customer_order_reference"
                               id="search"
                               name="customer_order"
                               placeholder="SEARCH"
                               size="15"
                               type="text"
                               value=""
                               autocomplete="off"
                               class="form-control search-order-repair">

                    </div>
                    <div class="col-sm-3 col-lg-3 form-group">
                        <label for=""></label>
                        <br>
                        <button type="submit"
                                style="position: relative; bottom: -6px;"
                                class="btn btn-primary disabled">Search
                        </button>

                    </div>
                </div>


            </form>
            <a href="/order-repairs/"
               class="btn btn-info">Reset Search</a>
            <br>
            <br>


            <!--            List of searched orders for user-->

            <?php
            if (!empty($_GET['status_order'])) {
                $status = $_GET['status_order'];
            } else {
                $status = 'any';
            }

            if (isset($_GET['customer_order'])) {

                $string = strtoupper($_GET['customer_order']);
                $pre = 'LF0';

                if (strpos($string, $pre) !== false) {
                    $cust_order = $string;
                    $customer_order = substr($cust_order, 3);
                } else {
                    $customer_order = strtoupper($_GET['customer_order']);
                }

                if ($customer_order) {
                    $articles = get_posts(
                        array(
                            'numberposts' => -1,
                            'post_status' => $status,
                            'post_type' => 'shop_order',
                            'meta_query' => array(
                                array(
                                    'key' => '_order_number',
                                    'value' => $customer_order,
                                    'compare' => 'LIKE',
                                ),
                            ),
                        )
                    );
                } else {
                    echo '3';
                    $articles = get_posts(
                        array(
                            'numberposts' => -1,
                            'post_status' => $status,
                            'post_type' => 'shop_order',
                            'meta_query' => array(
                                array(
                                    'key' => 'cart_name',
                                    'value' => $_GET['customer_order'],
                                    'compare' => 'LIKE',
                                ),
                            )
                        )
                    );

                }
                ?>
                <table class="table">
                    <thead style="background-color: #f2dede;">
                    <th></th>
                    <th>Order ID</th>
                    <th>Date</th>
                    <th>Total</th>
                    <th>Last Update</th>
                    <th></th>
                    </thead>
                    <tbody>
                    <?php
                    foreach ($articles as $article) {

                        $user = get_userdata($user_id);
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

                        $article_id = $article->ID;
                        $order = new WC_Order($article_id);
//                        echo $order->customer_id;
                        if (in_array($order->customer_id, $users_orders) || current_user_can('administrator')) {
                            $total = array();
                            $i = 1;

                            $items = $order->get_items();
                            $order_data = $order->get_data();
                            $order_status = $order_data['status'];
                            $culori_status = array('pending' => '#ef8181', 'on-hold' => '#f8dda7', 'processing' => '#c6e1c6', 'completed' => '#c8d7e1', 'cancelled' => '#e5e5e5', 'refound' => '#5BC0DE', 'inproduction' => '#7878e2', 'transit' => '#85bb65', 'waiting' => '#3ba000');

                            ?>
                            <tr class=" ">
                                <td>
                                    <?php echo $i; ?>.
                                </td>
                                <td>
                                    <?php echo 'LF0' . $order->get_order_number() . ' - <i>' . get_post_meta($order->ID, 'cart_name', true) . '</i>'; ?>
                                </td>
                                <td>
                                    <?php
                                    echo $order_data['date_created']->date('Y-m-d H:i:s');

                                    $text = $order_data['date_created']->date('Y-m-d H:i:s');
                                    preg_match('/-(.*?)-/', $text, $match);
                                    $month = $match[1];
                                    ?>
                                </td>
                                <td>
                                    <?php

                                    echo $order->get_total();

                                    foreach ($items as $item) {

                                        $prod_id = $item->get_product_id();
                                        $name = $item->get_name();
                                        //echo '<a href="/product-edit/?id='.($prod_id*1498765*33).'" target="_blank">'.$name.'</a>';
                                        //echo '<br>';

                                        $property_total = get_post_meta($prod_id, 'property_total', true);
                                        $total[$month] = $total[$month] + $property_total;
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?php echo $order_data['date_modified']->date('Y-m-d H:i:s'); ?>
                                </td>
                                <td>
                                    <a href="/add-order-repair/?order_number=<?php echo $order->get_order_number(); ?>"
                                       class="btn btn-primary">Add Repair</a>
                                </td>
                            </tr>

                            <?php
                            $i++;


                        }


                    }

                    ?>

                    </tbody>
                </table>
                <?php
            }
            ?>

            <!--            End of searched orders for user-->


            <div>
                <table class="table repair">
                    <thead style="background-color: #f2dede;">
                    <th></th>
                    <th>
                        Repair Order
                    </th>
                    <th>
                        Issued Date
                    </th>
                    <th>
                        For Original Order
                    </th>
                    <th>
                        Delivery Start
                    </th>
                    <th>
                        Current Status
                    </th>
                    <th style="text-align: center">
                        Under Warranty
                    </th>
                    <th style="text-align: center">
                        Repair Cost
                    </th>
                    <th>
                        View
                    </th>
                    </thead>
                    <tbody>
                    <?php

                    $i = 0;
                    $user_id = get_current_user_id();
                    $user = get_userdata($user_id);
                    if (in_array('dealer', $user->roles)) {
                        $users_orders = get_user_meta($user_id, 'employees', true);
                    }
                    // if dealer if is not empty and current user have employe role and not dealer_employe add dealer orders
                    if (in_array('employe', $user->roles) || in_array('senior_salesman', $user->roles)) {
                        // get user company_parent id from meta
                        $deler_id = get_user_meta($user_id, 'company_parent', true);
                        // initiate users_orders array with logged user id
                        $users_orders = array($user_id);

                        if (!empty($deler_id)) {
                            $users_orders[] = $deler_id;
                        }
                    }

                    if (in_array('salesman', $user->roles) || in_array('subscriber', $user->roles)) {
                        $users_orders = array($user_id);
                    }

                    foreach ($users_orders as $user_order) {
                        $args = array('post_type' => 'order_repair', 'posts_per_page' => -1, 'author' => $user_order);

                        $loop = new WP_Query($args);
                        if ($loop->have_posts()) {
                            while ($loop->have_posts()) : $loop->the_post();
                                $i++;
                                $order_id_scv = get_post_meta(get_the_ID(), 'order-id-scv', true);
                                $order_id = get_post_meta(get_the_ID(), 'order-id-original', true);
                                $order = wc_get_order($order_id);
                                $order_data = $order->get_data();
                                $delivereis_start = get_post_meta(get_the_ID(), 'delivereis_start', true);
                                $order_status = get_post_meta(get_the_ID(), 'order_status', true);
                                $warranty = get_post_meta(get_the_ID(), 'warranty', true);
                                $culori_status = array('pending' => '#ef8181', 'on-hold' => '#f8dda7', 'processing' => '#c6e1c6', 'completed' => '#c8d7e1', 'cancelled' => '#e5e5e5', 'refound' => '#5BC0DE', 'inproduction' => '#7878e2', 'transit' => '#85bb65', 'waiting' => '#3ba000', 'account-on-hold' => 'red');
                                $cost_field = get_post_meta(get_the_ID(), 'cost_repair', true);
                                $no_warranty = false;
                                $items = $order->get_items();
                                foreach ($items as $item_id => $item_data) {
                                    if ($warranty[$item_id] == 'No') {
                                        $no_warranty = true;
                                    }
                                }

                                ?>
                                <tr>
                                    <td><?php echo $i; ?></td>
                                    <td>
                                        <?php echo 'LFR' . $order_id_scv . '-' . get_post_meta($order_id, 'cart_name', true); ?>
                                    </td>
                                    <td>
                                        <?php echo get_the_date('Y-m-d', $post_id); ?>
                                    </td>
                                    <td>
                                        <?php echo 'LF' . $order_id_scv . '-' . get_post_meta($order_id, 'cart_name', true); ?>
                                    </td>
                                    <td>
                                        <?php echo $delivereis_start; ?>
                                    </td>
                                    <td>
                                        <p class="statusOrder"
                                           style="<?php
                                           foreach ($culori_status as $stat => $cul) {
                                               if ($stat == $order_status) {
                                                   echo 'background-color:' . $cul . ';';
                                               }
                                           }
                                           ?> color: #fff; text-align:center;">
                                            Ordered
                                            -
                                            <?php
                                            if ($order_status == 'pending') {
                                                echo 'pending payment';
                                            } //teo>
                                            elseif ($order_status == 'waiting') {
                                                echo 'waiting delivery';
                                            } //teo<
                                            else {
                                                echo $order_status;
                                            }
                                            ?>
                                        </p>
                                    </td>
                                    <td style="text-align: center"><?php
                                        if ($no_warranty) {
                                            echo 'No';
                                        } else {
                                            echo 'Yes';
                                        }
                                        ?>
                                    </td>
                                    <td style="text-align: center">
                                        <?php if ($cost_field) {
                                            echo '£' . $cost_field;
                                        } ?>
                                    </td>
                                    <td>
                                        <a href="<?php the_permalink(); ?>"
                                           class="btn btn-primary">View</a>
                                    </td>
                                </tr>
                            <?php
                            endwhile;
                        }
                        wp_reset_postdata();
                    }
                    ?>

                    </tbody>
                </table>
            </div>

        </main>
        <!-- #main -->
    </div>
    <!-- #primary -->

    <script>
        jQuery('input.search-order-repair').keyup(function () {
            var nr = jQuery('input.search-order-repair').val();
            //console.log(nr.length);
            if (nr.length > 2) {
                jQuery('main button[type="submit"]').removeClass('disabled');
            } else {
                jQuery('main button[type="submit"]').addClass('disabled');
            }
        });
    </script>

    <style>
        input[type="number"]::-webkit-outer-spin-button, input[type="number"]::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        input[type="number"] {
            -moz-appearance: textfield;
        }

        button.btn.btn-primary.disabled {
            pointer-events: none;
        }

        table.table.repair td, table td, table th {
            text-align: center !important;
        }
    </style>

<?php
get_footer();
