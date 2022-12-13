<!-- Latest compiled and minified CSS -->
<link rel="stylesheet"
      href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

<!-- Latest compiled JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<!-- Use Font Awesome Free CDN modified-->
<link rel="stylesheet"
      href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

<script type='text/javascript'
        src='/wp-content/themes/storefront-child/js/highcharts.js'></script>
<style>#container {
        min-width: 310px;
        max-width: 1024px;
        height: 400px;
        margin: 0 auto;
    }</style>
<div id="primary"
     class="content-area container">
    <main id="main"
          class="site-main"
          role="main">

        <h2>Users Group</h2>

        <!-- Multi Cart Template -->
        <?php if (is_active_sidebar('multicart_widgett')) : ?>
            <div id="bmc-woocom-multisession-2"
                 class="widget bmc-woocom-multisession-widget">
                <?php //dynamic_sidebar( 'multicart_widgett' ); ?>
            </div>
            <!-- #primary-sidebar -->
        <?php endif;


        $user_id = get_current_user_id();
        $meta_key = 'wc_multiple_shipping_addresses';

        global $wpdb;
        if ($addresses = $wpdb->get_var($wpdb->prepare("SELECT meta_value FROM {$wpdb->usermeta} WHERE user_id = %d AND meta_key = %s", $user_id, $meta_key))) {
            $addresses = maybe_unserialize($addresses);
        }

        $addresses = $wpdb->get_results("SELECT meta_value FROM `wp_usermeta` WHERE user_id = $user_id AND meta_key = '_woocom_multisession'");

        /*
         * Create Group
         * Delete Group
         * Rename Group
         */
      //  include_once(get_stylesheet_directory() . '/views/grup-users/grup-malipulation.php');

        /*
         * Search Group
         */
        include_once(get_stylesheet_directory() . '/views/grup-users/grup-search.php');

        ?>


        <!--
        **
        **
        Old display chart from user grafic
        **
        **
        -->

        <ul class="nav nav-tabs">
            <li class="active">
                <a data-toggle="tab" href="#grup-sqm">Year SQM</a>
            </li>
            <li>
                <a data-toggle="tab" href="#grup-month">Month SQM</a>
            </li>
<!--            <li>-->
<!--                <a data-toggle="tab" href="#grup-all">All Users SQM</a>-->
<!--            </li>-->
        </ul>

        <div class="tab-content" style="display: block;">

            <div id="grup-sqm" class="tab-pane fade in active">

                <?php
                /*
               * SQM Group Tab
               */
                include_once(get_stylesheet_directory() . '/views/grup-users/grup-sqm.php');
                ?>
            </div>
            <div id="grup-month" class="tab-pane fade">
                <?php
                /*
               * SQM Group Tab
               */
                include_once(get_stylesheet_directory() . '/views/grup-users/grup-month.php');
                ?>
            </div>
<!--            <div id="grup-all" class="tab-pane fade">-->
<!--                --><?php
//                /*
//               * SQM Group Tab
//               */
//                include_once(get_stylesheet_directory() . '/views/grup-users/grup-allusers.php');
//                ?>
<!--            </div>-->
        </div>

    </main>
    <!-- #main -->
</div>
<!-- #primary -->


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

</script>
