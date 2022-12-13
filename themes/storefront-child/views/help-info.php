<?php
global $wpdb;

$users = get_users(array('fields' => 'ID'));

foreach($users as $user_id){
    echo '<br><b>User</b>: ' .get_user_meta($user_id,'nickname',true); 
    echo '<br><b>ByTrain</b>: ' . get_user_meta($user_id,'train_price',true);
    echo '<br><b>Biowood</b>: ' . get_user_meta($user_id,'Biowood',true);
    echo '<br><b>Ecowood</b>: ' . get_user_meta($user_id,'Ecowood',true);
    echo '<br><b>Green</b>: ' . get_user_meta($user_id,'Green',true);
    echo '<br><b>Supreme</b>: ' . get_user_meta($user_id,'Supreme',true);
    echo '<br><b>Earth</b>: ' . get_user_meta($user_id,'Earth',true);
    echo '<hr><br>';
}
// print_r($users);




?>
<style>
    .hide {
        display: none;
    }
</style>

<div class="container-fluid hide">
    <h3>Update All SVG format</h3>


    <div id="update-panel">
        <div class="center">
            <h3>Update orders for new features of Matrix platform!</h3>
            <p id="update-user-orders" class="btn btn-primary border-color-accent">
                UPDATE
            </p>
            <br>
        </div>
        <hr>
        <br>
        <div class="center">
            <h3>Optimize svg files for new features of Matrix platform!</h3>
            <p id="update-user-svg" class="btn btn-primary border-color-accent">
                UPDATE SVG's
            </p>
            <br>
        </div>
        <hr>
        <br>
        <div class="center">
            <h3>Update orders Metas !</h3>
            <p id="update-orders-metas" class="btn btn-primary border-color-accent">
                UPDATE ORDERS METAS
            </p>
            <br>
        </div>
        <hr>
        <br>
        <div class="center">
            <h3>Update Items Price value from floatvalue to numver_format !</h3>
            <p>
                <samp>
                    $myNonFormatedFloat = 1817.04452;
                    number_format($myNonFormatedFloat, 2, '.', ''); // -> 1817.04
                </samp>
            </p>
            <p id="update-items-prices" class="btn btn-primary border-color-accent">
                UPDATE ITEMS PRICES FORMAT
            </p>
            <br>
        </div>
    </div>

    <?php

    $users = get_users(array('fields' => 'ID'));

    // ============ GET ALL ORDERS AND PREPARE FOR AJAX UPDATES ============

    $orders_for_ajax = array();
    $i = 1;
    //foreach ($users as $user_id) {
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
    // print_r($orders);

    $limit_orders = 20;
    foreach ($orders as $key => $order_id) {

        if ($key < $limit_orders) {
            $orders_for_ajax[$i][$key] = $order_id;
        } else {
            $limit_orders = $limit_orders + 20;
            $orders_for_ajax[$i][$key] = $order_id;
            $i++;
        }
    }
    //  $i++;
    //}
            echo '<pre>';
            print_r($orders_for_ajax);
            echo '<pre>';


    //  ============= GET ALL PRODUCTS AND PREPARE FOR AJAX UPDATES (PRICE) ============

    $products_for_ajax = array();
    $i = 1;
    //foreach ($users as $user_id) {
    $all_ids = get_posts( array(
        'post_type' => 'product',
        'numberposts' => -1,
        'post_status' => 'publish',
        'fields' => 'ids',
    ) );

    $limit_prods = 100;
    foreach ($all_ids  as $key => $prod_id) {

        if ($key < $limit_prods) {
            $products_for_ajax[$i][$key] = $prod_id;
        } else {
            $limit_prods = $limit_prods + 100;
            $products_for_ajax[$i][$key] = $prod_id;
            $i++;
        }
    }

    //            echo '<pre>';
    //            print_r($products_for_ajax);
    //            echo '<pre>';

    ?>

    <input type="hidden" name="orders_for_ajax" value="<?php echo json_encode($orders_for_ajax); ?>">
    <input type="hidden" name="products_for_ajax" value="<?php echo json_encode($products_for_ajax); ?>">

    <script>

        jQuery('#update-items-prices').click(function (e) {
            e.preventDefault();
            console.log('button items press');
            jQuery('#loader-bkg').show();

            var loading_progression = 0;
            var orders_for_ajax = <?php echo json_encode($orders_for_ajax); ?>;
            var getLength = function (obj) {
                var i = 0, key;
                for (key in obj) {
                    if (obj.hasOwnProperty(key)) {
                        i++;
                    }
                }
                return i;
            };
            var orders_count = getLength(orders_for_ajax);
            //console.log(getLength(orders_for_ajax));
            for (var part in orders_for_ajax) {
                // console.log(orders_for_ajax[part]);
                jQuery.ajax({
                    method: "POST",
                    url: '/wp-content/themes/storefront-child/ajax/meta-boxes/update-items-price.php',
                    data: {
                        orders: orders_for_ajax[part],
                    }
                })
                    .done(function (msg) {
                        console.log(msg);
                        console.log("Account Updated!");
                        loading_progression++;
                        if (loading_progression == orders_count) {
                            console.log("Loading... " + loading_progression + ' remaining ' + orders_count);
                            console.log("Price Items Updated!");
                            setTimeout(function () {
                                jQuery('#loader-bkg').hide();
                                // location.reload();
                            }, 1000);
                        } else {
                            console.log("Loading... " + loading_progression + ' remaining ' + orders_count);
                            console.log("Price Items Updated!");
                        }
                    })
                    .fail(function (msgError) {
                        console.log(msgError);
                        setTimeout(function () {
                            jQuery('#loader-bkg').hide();
                        }, 500);
                        jQuery('#update-user-orders').text('Retry to Update!');
                        alert("Error Price Items Updated!");
                    });
            }

        });
    </script>


    <div id="loader-bkg" style="display: none;">
        <h1>Please Wait!!!</h1>
        <br>
        <h2>This can take several minutes!</h2>
        <div id="loader"></div>
    </div>


    <style>
        /*header#masthead {*/
        /*    display: none;*/
        /*}*/

        div#update-panel {
            background: #fff;
            display: block;
            text-align: center;
        }

        button#update-user-orders, button#update-user-svg, button#update-orders-metas, button#update-items-prices {
            font-size: 18px;
            letter-spacing: 2px;
            font-weight: bolder;
        }

        p#update-user-orders, p#update-user-svg, p#update-orders-metas, p#update-items-prices {
            letter-spacing: 2px;
            font-weight: bolder;
            width: auto;
            text-align: center;
            margin: 0 auto;
            border: 1px solid #000;
            border-radius: 4px;
            font-size: 18px;
            padding: 6px;
            cursor: pointer;
        }

        div#update-panel * {
            font-weight: 500;
        }

        /* Center the loader */
        #loader-bkg {
            position: fixed;
            left: 5vw;
            top: 0;
            z-index: 999999;
            width: 100vw;
            height: 100vh;
            background-color: rgba(255, 255, 255, 0.97);
        }

        #loader-bkg > h1, #loader-bkg > h2 {
            position: absolute;
            top: 41%;
            z-index: 1;
            margin: -75px 0 0 -75px;
            display: block;
            width: 100%;
            text-align: center;
        }

        #loader-bkg > h2 {
            top: 35%;
        }

        #loader {
            position: absolute;
            left: calc(50% - 60px);
            top: 50%;
            z-index: 1;
            width: 150px;
            height: 150px;
            margin: -75px 0 0 -75px;
            border: 16px solid #f3f3f3;
            border-radius: 50%;
            border-top: 16px solid #3498db;
            width: 120px;
            height: 120px;
            -webkit-animation: spin 2s linear infinite;
            animation: spin 2s linear infinite;
        }

        @-webkit-keyframes spin {
            0% {
                -webkit-transform: rotate(0deg);
            }
            100% {
                -webkit-transform: rotate(360deg);
            }
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }
            100% {
                transform: rotate(360deg);
            }
        }

    </style>


</div>