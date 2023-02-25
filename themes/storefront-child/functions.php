<?php

add_action('wp_enqueue_scripts', 'theme_enqueue_styles');
function theme_enqueue_styles()
{
    wp_enqueue_style('parent-style_custom', get_template_directory_uri() . '/style.css');
    wp_enqueue_style('dev-style_custom', get_stylesheet_directory_uri() . '/style-custom.css');
    wp_enqueue_style('fonts-custom-style_custom', get_stylesheet_directory_uri() . '/fonts/font.css');

    // datatable
    wp_enqueue_style('datatable-style_custom', get_stylesheet_directory_uri() . '/css/jquery.dataTables.min.css');
    wp_enqueue_script('script-jquery1', get_stylesheet_directory_uri() . '/js/jquery-3.3.1.min.js', array(), '1.10.19', false);
    wp_enqueue_script('script-datatable', get_stylesheet_directory_uri() . '/js/jquery.dataTables.min.js', array(), '1.10.19', true);
    wp_enqueue_script('script-datatable1', get_stylesheet_directory_uri() . '/js/datatable/dataTables.buttons.min.js', array(), '1.10.19', true);
    wp_enqueue_script('script-datatable2', get_stylesheet_directory_uri() . '/js/datatable/buttons.flash.min.js', array(), '1.10.19', true);
    wp_enqueue_script('script-datatable3', get_stylesheet_directory_uri() . '/js/datatable/jszip.min.js', array(), '1.10.19', true);
    wp_enqueue_script('script-datatable4', get_stylesheet_directory_uri() . '/js/datatable/pdfmake.min.js', array(), '1.10.19', true);
    wp_enqueue_script('script-datatable5', get_stylesheet_directory_uri() . '/js/datatable/vfs_fonts.js', array(), '1.10.19', true);
    wp_enqueue_script('script-datatable6', get_stylesheet_directory_uri() . '/js/datatable/buttons.html5.min.js', array(), '1.10.19', true);
    wp_enqueue_script('script-datatable7', get_stylesheet_directory_uri() . '/js/datatable/buttons.print.min.js', array(), '1.10.19', true);
    wp_enqueue_script('jquery-ui-min', get_stylesheet_directory_uri() . '/js/jquery-ui.min.js', array(), '1.7.0', true);

    wp_enqueue_script('script-highcharts', get_stylesheet_directory_uri() . '/js/highcharts.js', array(), '6.1.0', true);
    wp_enqueue_script('script-frontend-upload-imgs-repair', get_stylesheet_directory_uri() . '/js/frontend-repair.js', array(), '1.0.9', true);
    wp_enqueue_media();

    wp_register_script('mediaelement', plugins_url('wp-mediaelement.min.js', __FILE__), array('jquery'), '4.8.2', true);
    wp_enqueue_script('mediaelement');
}

add_action('admin_enqueue_scripts', 'custom_matrix_scripts_admin');
function custom_matrix_scripts_admin($hook)
{
  wp_enqueue_style( 'bootstrap5-style', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css' );

  wp_enqueue_script(
    'wptuts53021_script', //unique handle
    get_stylesheet_directory_uri() . '/js/jspdf.min.js'
  );
  wp_enqueue_script(
    'wptuts53087654_script', //unique handle
    get_stylesheet_directory_uri() . '/js/html2canvas.min.js'
  );
  wp_enqueue_script(
    'custom_scripts_admin', //unique handle
    get_stylesheet_directory_uri() . '/js/custom_scripts_admin.js',
    array(),
    '1.0',
    true
  );

  wp_enqueue_style('jquery-ui', '//code.jquery.com/ui/1.13.2/themes/smoothness/jquery-ui.min.css');
  wp_enqueue_script('jquery-ui-datepicker');

  wp_enqueue_script('bootstrap5-js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js');

}

// Include custom functions to Theme Functions
include_once(get_stylesheet_directory() . '/includes/admin_menu.php');
include_once(get_stylesheet_directory() . '/includes/custom-posts-functions.php');
include_once(get_stylesheet_directory() . '/includes/filters-dashboard-lists.php');
include_once(get_stylesheet_directory() . '/includes/woocommerce-functions.php');
include_once(get_stylesheet_directory() . '/includes/login_redirects.php');
include_once(get_stylesheet_directory() . '/includes/add-meta-boxes.php');
include_once(get_stylesheet_directory() . '/includes/user-functions.php');

include_once(get_stylesheet_directory() . '/includes/dashboard_footer.php');
include_once(get_stylesheet_directory() . '/includes/class_quickbooks.php');
include_once(get_stylesheet_directory() . '/includes/class-orders.php');
include_once(get_stylesheet_directory() . '/includes/ajax.php');
/**
 * Mail custom settings
 */
include_once(get_stylesheet_directory() . '/includes/mail-settings.php');
/**
 * Shortcodes
 */
include_once(get_stylesheet_directory() . '/includes/shortcodes.php');
// my orders page shortcodes
include_once(get_stylesheet_directory() . '/includes/shortcodes-my-orders.php');


$customOrder = new OrdersCustom();

// add_action('init', $customOrder->orderTablesCreate());

if (!function_exists('sendLogMatrixMail')) {
    function sendLogMatrixMail($subject, $description, $logName)
    {
        //Something to write to txt log
        $log = "Subject: " . $subject . ' - ' . date("F j, Y, g:i a") . PHP_EOL .
            $description . " " . date("F j, Y, g:i a") . PHP_EOL .
            "-------------------------" . PHP_EOL;

        $log_filename = $_SERVER['DOCUMENT_ROOT'] . "/logs_tickets_mail";
        if (!file_exists($log_filename)) {
            // create directory/folder uploads.
            if (!mkdir($log_filename, 0777, true) && !is_dir($log_filename)) {
                throw new \RuntimeException(sprintf('Directory "%s" was not created', $log_filename));
            }
        }
        $log_file_data = $log_filename . '/log_LFr' . $logName . date('d-M-Y') . '.log';
        file_put_contents($log_file_data, $log . "\n", FILE_APPEND);
    }
}


if (!function_exists('write_log')) {
    function write_log($log)
    {
        if (true === WP_DEBUG) {
            if (is_array($log) || is_object($log)) {
                error_log(print_r($log, true));
            } else {
                error_log($log);
            }
        }
    }
}


// allow filte types to upload
function cc_mime_types($mimes)
{
    // New allowed mime types.
    $mimes['mov'] = 'video/quicktime';
    $mimes['mp4'] = 'video/mp4';
    return $mimes;
}

add_filter('upload_mimes', 'cc_mime_types');


function ticket_template_chooser($template)
{
    global $wp_query;
    $post_type = get_query_var('post_type');
    if (isset($_GET['s']) && $post_type == 'stgh_ticket') {
        return locate_template('tickets-search.php');  //  redirect to archive-search.php
    }
    return $template;
}

add_filter('template_include', 'ticket_template_chooser');


/*
 * OLD ORDERS REPAIRED
 * */

# Called only in /wp-admin/edit.php pages
add_action('all_admin_notices', function () {
    add_filter('views_edit-order_repair', 'repairedOldOrders'); // talk is my custom post type
});

# echo the tabs
function repairedOldOrders($views)
{
    include get_theme_file_path('includes/old-orders-repair.php');
    echo '<br><br>';

    return $views;
}

/*
* END - OLD ORDERS REPAIRED
* */


/**
 * Register our d widgetized areas.
 */
function multicart_widget_custom()
{
    register_sidebar(array(
        'name' => 'MultiCartWidget',
        'id' => 'multicart_widgett',
        'before_widget' => '<div>',
        'after_widget' => '</div>',
        'before_title' => '<h2 class="rounded">',
        'after_title' => '</h2>',
    ));
}

add_action('widgets_init', 'multicart_widget_custom');


function deliveries_save_menu_meta_box($post_id)
{
//        $list_orders_id = array();
//        if(isset($_POST['packing_list_ids']) && $_POST['packing_list_ids'] != '' ){
//            $list_orders_id = explode(",", $_POST['packing_list_ids']);
//            update_post_meta($post_id, 'list_orders_id_checked', $list_orders_id);
//        }

    // change order ref with new cart name
    if (isset($_POST['order_ref'])) {
        //if is container get all orders and set deliveries date
        if (get_post_type($post_id) == 'shop_order') {
            //if is true
            update_post_meta($post_id, 'cart_name', $_POST['order_ref']);
        }
    }


    if (isset($_POST['deliveries'])) {

        //if is container get all orders and set deliveries date
        if (get_post_type($post_id) == 'container') {
            //if is true
            $container_orders = get_post_meta($post_id, 'container_orders', true);
            if ($container_orders) {
                foreach ($container_orders as $order_id) {
                    // update order deliveries start
                    update_post_meta($order_id, 'delivereis_start', $_POST['deliveries']);
                }
            }
        }

        // do stuff
        update_post_meta($post_id, 'delivereis_start', $_POST['deliveries']);
    }

    if (!function_exists('wp_handle_upload')) {
        require_once(ABSPATH . 'wp-admin/includes/file.php');
    }

    if ($_FILES['CSVUpload']) {
        $uploadedfile = $_FILES['CSVUpload'];

        $upload_overrides = array('test_form' => false);

        $movefile = wp_handle_upload($uploadedfile, $upload_overrides);

        if ($movefile && !isset($movefile['error'])) {
            echo "File is valid, and was successfully uploaded.\n";
            var_dump($movefile);
            update_post_meta($post_id, 'csv_upload_path', $movefile['file']);
        } else {
            /**
             * Error generated by _wp_handle_upload()
             * @see _wp_handle_upload() in wp-admin/includes/file.php
             */
            echo $movefile['error'];
        }
    }

    /*
     * Upload file CSV for compare deliveries price items order
     */
    if ($_FILES['CSVUploadInvoice']) {
        $uploadedfile = $_FILES['CSVUploadInvoice'];

        $upload_overrides = array('test_form' => false);

        $movefile = wp_handle_upload($uploadedfile, $upload_overrides);

        if ($movefile && !isset($movefile['error'])) {
            echo "File is valid, and was successfully uploaded.\n";
            var_dump($movefile);
            update_post_meta($post_id, 'csv_upload_path_invoice', $movefile['file']);
        } else {
            /**
             * Error generated by _wp_handle_upload()
             * @see _wp_handle_upload() in wp-admin/includes/file.php
             */
            echo $movefile['error'];
        }
    }

}

add_action('save_post', 'deliveries_save_menu_meta_box');

function save_order_post($post_id)
{
    if (get_post_type($post_id) == 'shop_order') {
        $order_id = $post_id;
        $order = new WC_Order($order_id);

        $order_status = $order->get_status();
        if ($order_status == 'on-hold') {
            $name = get_post_meta($order_id, 'cart_name', true);

            // $single_email = 'marian93nes@gmail.com';
            $multiple_recipients = array(
                'caroline@anyhooshutter.com', 'july@anyhooshutter.com', 'tudor@lifetimeshutters.com'
            );

            $subject = 'ON-HOLD Order LF0' . $order->get_order_number() . ' - ' . $name . ' - for REVISION';
            $headers = array('Content-Type: text/html; charset=UTF-8', 'From: Matrix-LifetimeShutters <order@lifetimeshutters.com>');

            $mess = '
        Hi July, Kevin <br />
        <br />
       Please put this order ON-HOLD until revised by dealer.<br />
The revised order will be sent to you when ready.<br />
        <br />
        Kind regards. <br />
        <br />
        ';

            wp_mail($multiple_recipients, $subject, $mess, $headers);
        }
    }
}

add_action('save_post', 'save_order_post');


// custom filter order

/**
 * Filter slugs
 * @return void
 * @since 1.1.0
 */

function wisdom_filter_tracked_plugins($post_type)
{
    if ($post_type == 'shop_order') { // Your custom post type slug
        $current_search = '';
        if (isset($_GET['cart_name'])) {
            $current_search = $_GET['cart_name']; // Check if option has been selected
        } ?>
        <input type="text" name="cart_name" id="cart_name"
               value="<?php echo esc_attr($current_search); ?>" placeholder="Order Ref">
    <?php }
}

add_action('restrict_manage_posts', 'wisdom_filter_tracked_plugins', 5);

/**
 * Update query
 * @return void
 * @since 1.1.0
 */
function wisdom_sort_plugins_by_slug($query)
{
    global $pagenow;
    // Get the post type
    $post_type = isset($_GET['post_type']) ? $_GET['post_type'] : '';
    if (is_admin() && $pagenow == 'edit.php' && $post_type == 'shop_order' && isset($_GET['cart_name']) && $_GET['cart_name'] != 'all') {
        $query->query_vars['meta_key'] = 'cart_name';
        $query->query_vars['meta_value'] = $_GET['cart_name'];
        $query->query_vars['meta_compare'] = 'LIKE';
    }
    if (is_numeric($_GET['s'])) {
        if (is_admin() && $pagenow == 'edit.php' && $post_type == 'shop_order' && isset($_GET['s']) && $_GET['s'] != '') {
            $query->query_vars['meta_key'] = '_order_number';
            $query->query_vars['meta_value'] = $_GET['s'];
            $query->query_vars['meta_compare'] = 'LIKE';
        }
    }
}

add_filter('parse_query', 'wisdom_sort_plugins_by_slug');

// Function to change email address
function wpb_sender_email($original_email_address)
{
    return 'order@lifetimeshutters.com';
}

// Function to change sender name
function wpb_sender_name($original_email_from)
{
    return 'Matrix-LifetimeShutters';
}

// Hooking up our functions to WordPress filters
add_filter('wp_mail_from', 'wpb_sender_email');
add_filter('wp_mail_from_name', 'wpb_sender_name');


function container_save_menu_meta_box($post_id)
{

    if ($_POST['item_price']) {
        $order = wc_get_order($post_id);
        $items = $order->get_items();
        $total_tax = 0;
        $subtotal_price = 0;

        foreach ($items as $item_id => $item_data) {

            if ($_POST['item_price'][$item_id]) {

                wc_update_order_item_meta($item_id, '_line_total', $_POST['item_price'][$item_id]);
                wc_update_order_item_meta($item_id, '_line_tax', $_POST['item_vat'][$item_id]);
                wc_update_order_item_meta($item_id, '_line_subtotal', $_POST['item_price'][$item_id]);
                wc_update_order_item_meta($item_id, '_line_subtotal_tax', $_POST['item_vat'][$item_id]);
                wc_update_order_item_meta($item_id, '_qty', $_POST['item_qty'][$item_id]);
                $total_tax = $total_tax + $_POST['item_vat'][$item_id] * $_POST['item_qty'][$item_id];
                $subtotal_price = $subtotal_price + $_POST['item_price'][$item_id];

            }

        }
        foreach ($order->get_items('tax') as $item_id => $item_tax) {
            // Tax shipping total
            $tax_shipping_total = $item_tax->get_shipping_tax_total();
        }
        //$total_price = $subtotal_price + $total_tax;
        $order_data = $order->get_data();
        $total_price = $subtotal_price + $total_tax + $order_data['shipping_total'] + $tax_shipping_total;
        $tax_item_meta_id = key($order_data['tax_lines']);
        wc_update_order_item_meta($tax_item_meta_id, 'tax_amount', number_format($total_tax, 2, '.', ''));
        update_post_meta($post_id, '_order_tax', number_format($total_tax, 2, '.', ''));
        update_post_meta($post_id, '_order_total', number_format($total_price, 2, '.', ''));
    }

    if (isset($_POST['container'])) {

        // if order has a container, find in container_id meta and remove from container meta 'container_orders'
        $container_id = get_post_meta($post_id, 'container_id', true);
        $old_container_orders = get_post_meta($container_id, 'container_orders', true);
        $key = array_search($post_id, $old_container_orders);
        if (false !== $key) {
            unset($old_container_orders[$key]);
            update_post_meta($container_id, 'container_orders', $old_container_orders);
        }

        $container_orders = get_post_meta($_POST['container'], 'container_orders', true);

        if (empty($container_orders)) {

            update_post_meta($_POST['container'], 'container_orders', array($post_id));

        } else {

            if (!in_array($post_id, $container_orders)) {

                $container_orders[] = $post_id;
                update_post_meta($_POST['container'], 'container_orders', $container_orders);

            }
        }

        //update current order with a meta container for realocating container
        update_post_meta($post_id, 'container_id', $_POST['container']);

        // do stuff
        // 	update_post_meta( $post_id,'container_id',$_POST['container'] );

        // 	$container_orders = get_post_meta( $_POST['container'],'container_orders',true);

        // 	if(empty($container_orders)){

        // 		update_post_meta( $_POST['container'],'container_orders',array($post_id));

        //    }
        //    else{

        // 	 if(!in_array($post_id,$container_orders)){

        // 	   $container_orders[] = $post_id;
        // 	   update_post_meta($_POST['container'],'container_orders',$container_orders);

        // 	 }
        //    }

    }
}

add_action('save_post', 'container_save_menu_meta_box');


// Modify dashboard page container html
add_filter('views_edit-container', function ($views) {
    echo "<script type='text/javascript' >
                jQuery(document).ready(function($) {
                    var start = new Date().getTime();
                     var arrayIds = [];
                     $('span[data-id]').each(function(){
                         var dataLayer = $(this).data('id');
                          arrayIds.push(dataLayer);
                     });
                    var data_sqm = {
                        'action': 'get_container_sqm',
                        'conatiner_orders': arrayIds
                    };
                    
                     var data_price = {
                        'action': 'get_container_price',
                        'conatiner_orders': arrayIds
                    };
                     
                    var ajaxurl = '" . admin_url('admin-ajax.php') . "';
                    jQuery.post(ajaxurl, data_price, function(response) {
                        console.log(response);
                      var containers_price = JSON.parse(response);
                      for (const [key, value] of Object.entries(containers_price)) {
                          jQuery('#container-price-'+key+'').text(value);
                       }
                      var end = new Date().getTime();
                       console.log('price milliseconds passed', end - start);
                    });
                    
                    jQuery.post(ajaxurl, data_sqm, function(response) {
                        var containers_sqm = JSON.parse(response);
                       for (const [key, value] of Object.entries(containers_sqm)) {
                          jQuery('#container-sqm-'+key+'').text(value);
                        }
                       var end = new Date().getTime();
                       console.log('sqm milliseconds passed', end - start);
                    });
                    
                   
                });
            </script>";
    return $views;
});


/***************************************************************
 * Dropdown container select
 ***************************************************************/

/**
 * Adds a new item into the Bulk Actions dropdown.
 */
function add_container_bulk_actions($bulk_actions)
{
    $bulk_actions['continer_orders'] = __('Add Orders to Container', 'domain');
    $bulk_actions['mark_pending'] = __('Change status to Pending payment', 'domain');
    return $bulk_actions;
}

add_filter('bulk_actions-edit-shop_order', 'add_container_bulk_actions');


/**
 * Bulk Action complete status to repair orders.
 */
function add_repair_to_complete_action($bulk_actions)
{
    $bulk_actions['status_to_complete'] = __('Status Repair to Complete', 'domain');
    return $bulk_actions;
}

add_filter('bulk_actions-edit-order_repair', 'add_repair_to_complete_action');

/**
 * Handles the bulk action.
 */
function repair_bulk_action_handler($redirect_to, $action, $post_ids)
{
    if ($action !== 'status_to_complete') {
        return $redirect_to;
    }

    if ($action == 'status_to_complete') {
        foreach ($post_ids as $order_id) {
            update_post_meta($order_id, 'order_status', 'completed');
            $order_id = get_post_meta($order_id, 'order-id-original', true);
            $order = new WC_Order($order_id);
            $order->update_status('wc-completed', 'Change status to Completed');
        }

        $redirect_to = add_query_arg('bulk_status_to_complete', count($post_ids), $redirect_to);
    }
    return $redirect_to;

}

add_filter('handle_bulk_actions-edit-order_repair', 'repair_bulk_action_handler', 10, 3);


/**
 * Filter slugs
 * @return void
 * @since 1.1.0
 */

function select_containers()
{
    global $typenow;
    global $wp_query;
    if ($typenow == 'shop_order') { // Your custom post type slug

        $rand_posts = get_posts(array(
            'post_type' => 'container',
            'posts_per_page' => 10,
        ));
        if ($rand_posts) {
            echo '   <select name="container">
				<option value="">Select container</option>';
            foreach ($rand_posts as $post) :
                setup_postdata($post);
                echo '<option value="' . $post->ID . '">' . get_the_title($post->ID) . '</option>';
            endforeach;
            echo '</select>';
            wp_reset_postdata();
        }
    }
}

add_action('restrict_manage_posts', 'select_containers');

/**
 * Update query
 * @return void
 * @since 1.1.0
 */
function insert_orders_container($query)
{
    global $pagenow;
    // Get the post type
    $post_type = isset($_GET['post_type']) ? $_GET['post_type'] : '';
    if (is_admin() && $pagenow == 'edit.php' && $post_type == 'shop_order' && isset($_GET['container'])) {
        if (isset($_GET['container'])) {
            // do stuff
            //print_r($_GET['container']);
        }
    }
}

add_filter('parse_query', 'insert_orders_container');

/**
 * Handles the bulk action.
 */
function container_bulk_action_handler($redirect_to, $action, $post_ids)
{
    write_log($action);
//    if ($action !== 'continer_orders') {
//        return $redirect_to;
//    }

    if ($action == 'continer_orders') {
        foreach ($post_ids as $post_id) {

            // if order has a container, find in container_id meta and remove from container meta 'container_orders'
            $container_id = get_post_meta($post_id, 'container_id', true);
            $old_container_orders = get_post_meta($container_id, 'container_orders', true);
            $key = array_search($post_id, $old_container_orders);
            if (false !== $key) {
                unset($old_container_orders[$key]);
                update_post_meta($container_id, 'container_orders', $old_container_orders);
            }

            $container_orders = get_post_meta($_GET['container'], 'container_orders', true);

            if (empty($container_orders)) {

                update_post_meta($_GET['container'], 'container_orders', array($post_id));

            } else {

                if (!in_array($post_id, $container_orders)) {

                    $container_orders[] = $post_id;
                    update_post_meta($_GET['container'], 'container_orders', $container_orders);

                }
            }

            //update current order with a meta container for realocating container
            update_post_meta($post_id, 'container_id', $_GET['container']);

        }

        $redirect_to = add_query_arg('bulk_continer_orders', count($post_ids), $redirect_to);
    }

    if ($action == 'mark_pending') {
        foreach ($post_ids as $order_id) {
            $order = new WC_Order($order_id);
            $order->update_status('wc-pending', 'Change status to Pending payment');
        }

        $redirect_to = add_query_arg('bulk_continer_orders', count($post_ids), $redirect_to);
    }

    $custom_order_statuses = alg_get_custom_order_statuses_from_cpt(true);
    foreach ($custom_order_statuses as $slug => $label) {
        if ($action == 'mark_' . $slug) {
            foreach ($post_ids as $order_id) {
                $order = wc_get_order($order_id);
                $order->update_status($slug, 'Change status to ' . $label);
            }
            $redirect_to = add_query_arg('bulk_continer_orders', count($post_ids), $redirect_to);
        }
    }

    return $redirect_to;

}

add_filter('handle_bulk_actions-edit-shop_order', 'container_bulk_action_handler', 10, 3);

/***************************************************************
 * END - Dropdown container select
 ***************************************************************/


function product_save_menu_meta_box($post_id)
{

    if (isset($_POST['dolar_price'])) {
        // do stuff
        update_post_meta($post_id, 'dolar_price', $_POST['dolar_price']);

    }
}

add_action('save_post', 'product_save_menu_meta_box');


// Rewrite VAT total price with shipping
function wc_cart_totals_taxes_total_html_custom()
{

    global $woocommerce;
    $tva_shipping = (WC()->cart->shipping_total * 20) / 100;
    // wc_cart_totals_taxes_total_html();
    $new_vat = $tva_shipping + WC()->cart->get_taxes_total(true, true);

    echo apply_filters('woocommerce_cart_totals_taxes_total_html', $new_vat);

}


function order_repair_save_meta($post_id)
{
    if (isset($_POST['container-repair'])) {
        // if order has a container, find in container_id meta and remove from container meta 'container_orders'
        $container_id = get_post_meta($post_id, 'container_id', true);
        $old_container_orders = get_post_meta($container_id, 'container_orders', true);
        $key = array_search($post_id, $old_container_orders);
        if (false !== $key) {
            unset($old_container_orders[$key]);
            update_post_meta($container_id, 'container_orders', $old_container_orders);
        }
        $container_orders = get_post_meta($_POST['container-repair'], 'container_orders', true);
        if (empty($container_orders)) {
            update_post_meta($_POST['container-repair'], 'container_orders', array($post_id));
        } else {
            if (!in_array($post_id, $container_orders)) {
                $container_orders[] = $post_id;
                update_post_meta($_POST['container-repair'], 'container_orders', $container_orders);
            }
        }
        //update current order with a meta container for realocating container
        update_post_meta($post_id, 'container_id', $_POST['container-repair']);
    }

    if (isset($_POST['order_status'])) {
        update_post_meta($post_id, 'order_status', $_POST['order_status']);
    }

    if (isset($_POST['deliveries-repair'])) {
        update_post_meta($post_id, 'delivereis_start', $_POST['deliveries-repair']);
    }

    if (isset($_POST['cost_repair']) && strlen($_POST['cost_repair']) > 0) {
        $cost_field = get_post_meta($post_id, 'cost_repair', true);
        $warranty = get_post_meta($post_id, 'warranty', true);
        $no_warranty = false;
        $type_cost = $_POST['type_cost'];
        update_post_meta($post_id, 'type_cost', $type_cost);
        if ($cost_field != $_POST['cost_repair']) {
            update_post_meta($post_id, 'order_status', 'pending');
            $order_id = get_post_meta($post_id, 'order-id-original', true);
            $order_id_scv = get_post_meta($post_id, 'order-id-scv', true);
            $order = new WC_Order($order_id);
            $items = $order->get_items();
            $cost = 0;
            if ($type_cost == 'sqm') {
                foreach ($items as $item_id => $item_data) {
                    if ($warranty[$item_id] == 'No') {
                        $item_cost_w = 10;
                        $product_id = $item_data['product_id'];
                        $property_total = get_post_meta($product_id, 'property_total', true);
                        if ($property_total > 1) {
                            $item_cost_w = 10 * number_format($property_total, 2);
                        }
                        $cost = $cost + $item_cost_w;
                        $no_warranty = true;
                    }
                }
            }
            $order_data = $order->get_data();

            $cost = $_POST['cost_repair'] + $cost + ($_POST['cost_repair'] + $cost) * 0.2;
            update_post_meta($post_id, 'cost_repair', $cost);

            $user_mail = $order_data['billing']['email'];

            $multiple_recipients = array(
                'order@lifetimeshutters.com', 'tudor@lifetimeshutters.com', $user_mail
            );
//$multiple_recipients = 'marian93nes@gmail.com, tudor@fiqs.ro';
            $subject = get_the_title($post_id) . ' - Not Under Warranty - Repair Order';
            $body = '<br>
<p>
Dear ' . $order_data['billing']['first_name'] . ' ' . $order_data['billing']['last_name'] . ',<br>
<br>
Thank you for your recent repair order with Matrix.<br><br>

Please quote the following details should you have any questions regarding this order at any stage.<br><br>

Lifetime Number: LFR' . $order_id_scv . '<br><br>

You can find the Repair Order Summary by following this link:<br>
<a href="' . esc_url(get_permalink($post_id)) . '">' . esc_url(get_permalink($post_id)) . '</a><br><br>

Your repair order has been sent to our factory ready for manufacture. Once payment has been received manufacturing will commence.<br><br>

Payments can be made via international bank transfer to the following account:<br><br>

Account Name: Lifetime Shutters<br><br>
Sort Code: 20-46-73<br><br>
Account Number: 13074145<br><br>
<br><br>
Amount due: £' . number_format($cost, 2) . '
<br><br>
We will contact you to advise of delivery arrangements in due course.
<br><br>
Should you have any questions please contact us at order@lifetimeshutters.com
<br><br>
Kind regards,
<br><br>
Accounts Department
</p><br>';
            $headers = array('Content-Type: text/html; charset=UTF-8', 'From: Service LifetimeShutters <service@lifetimeshutters.co.uk>');

            wp_mail($multiple_recipients, $subject, $body, $headers);
        }
    }
}

add_action('save_post', 'order_repair_save_meta');

//teo for ticketing module edit ticket page
wp_enqueue_style('stgh_customstyle', get_stylesheet_directory_uri() . '/stgh_customstyle.css');

// ticketing_cron_custom
// Get tickets and send mail to not answered
add_action('ticketing_cron_custom', 'ticket_cron_mail');

function ticket_cron_mail()
{

    $loop = new WP_Query(array('post_type' => 'stgh_ticket', 'post_status' => array('stgh_answered', 'stgh_new')));
    if ($loop->have_posts()) :
        while ($loop->have_posts()) : $loop->the_post();

            $ticket_id = get_the_id();

            $post = get_post($ticket_id);

            $title = $post->post_title;

            $user_id_ticket = get_post_meta($ticket_id, '_stgh_contact', true);

            $user_info = get_userdata($user_id_ticket);
            $email = $user_info->user_email;

            $first_name = get_user_meta($user_id_ticket, 'billing_first_name', true);
            $last_name = get_user_meta($user_id_ticket, 'billing_last_name', true);

            $permalink = get_site_url() . '/factory-queries/';

            $multiple_recipients = array(
                $email, 'tudor@lifetimeshutters.com'
            );

            $bodyth = 'Dear ' . $first_name . ' ' . $last_name . ',
                <br><br>
                The above order is still on hold at the factory as they have questions they need you to answer,
                <br><br>
                Please log on to the Matrix Ordering System at Factory Querries Page to address the question.
                <br><br>
                In case of any queries please contact Customer Support on 0777 246 0159
                <br><br>
                Best Regards<br>
                Matrix Ordering System';

            $subth = 'Kind reminder: Not-Answered Querry for Order ' . $title;
//                wp_mail( $multiple_recipients, 'Cron Ticket notanswered mail', 'Automatic email from WordPress to test cron for ticket id: '.$ticket_id.' with title: '.get_the_title($ticket_id));
            $headersth = array('Content-Type: text/html; charset=UTF-8');
            wp_mail($multiple_recipients, $subth, $bodyth, $headersth);

        endwhile;
    endif;
    wp_reset_postdata();

}


/*
 * Delete from table custom_orders order when send to trash
 */
add_action('wp_trash_post', 'delete_custom_order', 1, 1);
function delete_custom_order($post_id)
{
    global $wpdb;

    if (!did_action('trash_post')) {
        $table_name = $wpdb->prefix . 'custom_orders';
        $wpdb->query("DELETE  FROM {$table_name} WHERE idOrder = '{$post_id}'");
    }
}

/*
 * Auto complete billing info from checkout
 */
//add_filter('woocommerce_checkout_fields', 'overridefields');
//function overridefields($fields)
//{
//    $current_user_id = get_current_user_id();
//    $fname = get_user_meta($current_user_id, 'first_name', true);
//    $lname = get_user_meta($current_user_id, 'last_name', true);
//    $company = get_user_meta($current_user_id, 'billing_company', true);
//
//    $address_1 = get_user_meta($current_user_id, 'billing_address_1', true);
//    $address_2 = get_user_meta($current_user_id, 'billing_address_2', true);
//    $city = get_user_meta($current_user_id, 'billing_city', true);
//    $state = get_user_meta($current_user_id, 'billing_state', true);
//    $phone = get_user_meta($current_user_id, 'billing_phone', true);
//    $postcode = get_user_meta($current_user_id, 'billing_postcode', true);
//    //print_r($select_r);
//    $fields['billing']['billing_first_name']['default'] = $fname;
//    $fields['billing']['billing_last_name']['default'] = $lname;
//    $fields['billing']['billing_company']['default'] = $company;
//    $fields['billing']['billing_address_1']['default'] = $address_1;
//    $fields['billing']['billing_address_2']['default'] = $address_2;
//    $fields['billing']['billing_city']['default'] = $city;
//    $fields['billing']['billing_state']['default'] = $state;
//    $fields['billing']['billing_phone']['default'] = $phone;
//    $fields['billing']['billing_postcode']['default'] = $postcode;
//    //$fields['billing']['billing_delivery_date']['default']=$_SESSION['delivdate'];
//    return $fields;
//}

/*
 * Recalculate on each update for any order in dashboard all items quantity and total ( ATENTION!!!! somethimes need to do two updates to show gross total correctly )
 * Update custom table wp_custom_orders with new USD and GBP price from recalculation
 */
function recalculate_and_update_custom_shop_order_save_post($post_id)
{

    // do stuff here
    update_post_meta($post_id, 'custom_meta_save_post', 'meta_save');

    // Only for shop order
    if (!isset($_POST['post_type']) || 'shop_order' != $_POST['post_type']) {
        return;
    } else {
        $order = wc_get_order($post_id);
        $user_id_customer = get_post_meta($post_id, '_customer_user', true);
        $items = $order->get_items();
        $total_dolar = 0;
        $sum_total_dolar = 0;
        $totaly_sqm = 0;
        $new_total = 0;
        $order_train = 0;

        $country_code = WC()->countries->countries[$order->get_shipping_country()];
        if ($country_code == 'United Kingdom (UK)' || $country_code == 'Ireland') {
            $tax_rate = 20;
        } else {
            $tax_rate = 0;
        }

        $pos = false;

        foreach ($items as $item_id => $item_data) {

            if (has_term(20, 'product_cat', $item_data['product_id']) || has_term(34, 'product_cat', $item_data['product_id']) || has_term(26, 'product_cat', $item_data['product_id'])) {
                $pos = true;
                // do something if product with ID = 971 has tag with ID = 5
            } else {

                $quantity = get_post_meta($item_data['product_id'], 'quantity', true);
                $price = get_post_meta($item_data['product_id'], '_price', true);
                $total = $price * $quantity;
                $sqm = get_post_meta($item_data['product_id'], 'property_total', true);
                if ($sqm > 0) {
                    $train_price = get_user_meta($user_id_customer, 'train_price', true);
                    if (empty($train_price) && !is_numeric($train_price)) {
                        $train_price = get_post_meta(1, 'train_price', true);
                    }
                    $new_price = floatval($sqm * $quantity) * floatval($train_price);
                    update_post_meta($item_data['product_id'], 'train_delivery', $new_price);
                    $order_train = $order_train + $new_price;
                } else {
                    $new_price = 0;
                }
                $total = $total + $new_price;
                $tax = ($tax_rate * $total) / 100;

                $line_tax_data = array
                (
                    'total' => array
                    (
                        1 => $tax
                    ),
                    'subtotal' => array
                    (
                        1 => $tax
                    )
                );
                /*
                 * Doc to change order item meta: https://www.ibenic.com/manage-order-item-meta-woocommerce/
                 */
                if ($quantity == 0 || empty($quantity)) {
                    $quantity = 1;
                    $total = floatval($price) * $quantity;
                    $tax = ($tax_rate * $total) / 100;
                }


                wc_update_order_item_meta($item_id, '_qty', $quantity, $prev_value = '');
                wc_update_order_item_meta($item_id, '_line_tax', $tax, $prev_value = '');
                wc_update_order_item_meta($item_id, '_line_subtotal_tax', $tax, $prev_value = '');
                wc_update_order_item_meta($item_id, '_line_subtotal', $total, $prev_value = '');
                wc_update_order_item_meta($item_id, '_line_total', $total, $prev_value = '');
                wc_update_order_item_meta($item_id, '_line_tax_data', $line_tax_data);

                // USD price
                //$prod_qty = $item_data['quantity'];
                $product_id = $item_data['product_id'];
                $prod_qty = get_post_meta($product_id, 'quantity', true);
                $dolar_price = get_post_meta($product_id, 'dolar_price', true);

                $sum_total_dolar = floatval($sum_total_dolar) + floatval($dolar_price) * floatval($prod_qty);

                $sqm = get_post_meta($product_id, 'property_total', true);
                $property_total_sqm = floatval($sqm) * floatval($prod_qty);
                $totaly_sqm = $totaly_sqm + $property_total_sqm;

                ## -- Make your checking and calculations -- ##
                $new_total = $new_total + $total + $tax; // <== Fake calculation

                $_product = wc_get_product($product_id);
            }
        }

        if ($order_train > 0) {
            update_post_meta($post_id, 'order_train', $order_train);
        }

        if ($pos === false) {

            foreach ($order->get_items('tax') as $item_id => $item_tax) {
                // Tax shipping total
                $tax_shipping_total = $item_tax->get_shipping_tax_total();
            }
            //$total_price = $subtotal_price + $total_tax;
            $order_data = $order->get_data();
            $total_price = $new_total + $order_data['shipping_total'] + $tax_shipping_total;
            update_post_meta($post_id, '_order_total', $total_price);

            /*
             * UPDATE custom_orders table on each shop_order post update
             */
            $property_total_gbp = $order->get_total();
            $property_subtotal_gbp = $order->get_subtotal();

            $order_shipping_total = $order->shipping_total;

            // $property_total_gbp = floatval(get_post_meta($order->id, '_order_total', true));

            global $wpdb;

            $idOrder = $post_id;
            $orderExist = $wpdb->get_var("SELECT COUNT(*) FROM `wp_custom_orders` WHERE `idOrder` = $idOrder");

            if ($orderExist != 0) {
                /*
                 * Update table
                 */
                $tablename = $wpdb->prefix . 'custom_orders';

                $data = array(
                    'usd_price' => $sum_total_dolar,
                    'gbp_price' => $property_total_gbp,
                    'subtotal_price' => $property_subtotal_gbp,
                    'sqm' => $totaly_sqm,
                    'shipping_cost' => $order_shipping_total,
                );
                $where = array(
                    "idOrder" => $post_id
                );
                $wpdb->update($tablename, $data, $where);

            } else {
                /*
                 * Insert in table
                 */

                $order_status = $order_data['status'];

                $tablename = $wpdb->prefix . 'custom_orders';
                $shipclass = $_product->get_shipping_class();

                $data = array(
                    'idOrder' => $post_id,
                    'reference' => 'LF0' . $order->get_order_number() . ' - ' . get_post_meta($post_id, 'cart_name', true),
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
    }

}

add_action('save_post', 'recalculate_and_update_custom_shop_order_save_post');

/*
 * POS - add to cart redirect to checkout
 */

add_filter('add_to_cart_redirect', 'redirect_to_checkout');

function redirect_to_checkout()
{
    global $woocommerce;
    $checkout_url = $woocommerce->cart->get_checkout_url();
    return $checkout_url;
}

/**
 * Add custom taxonomies for COMPONENTS
 */
add_action('init', 'create_component_type_taxonomy', 0);

//create a custom taxonomy name it topics for your posts

function create_component_type_taxonomy()
{
// Add new taxonomy, make it hierarchical like categories
//first do the translations part for GUI

    $labels = array(
        'name' => _x('Component Type', 'taxonomy general name'),
        'singular_name' => _x('Component Type', 'taxonomy singular name'),
        'search_items' => __('Search Component Type'),
        'all_items' => __('All Component Types'),
        'parent_item' => __('Parent Component Type'),
        'parent_item_colon' => __('Parent Component Type:'),
        'edit_item' => __('Edit Component Type'),
        'update_item' => __('Update Component Type'),
        'add_new_item' => __('Add New Component Type'),
        'new_item_name' => __('New Topic Component Type'),
        'menu_name' => __('Component Types'),
    );

// Now register the taxonomy

    register_taxonomy('component_type', array('product'), array(
        'hierarchical' => true,
        'labels' => $labels,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'component_type'),
    ));

}


/**
 * Adding position
 * @return void
 */
function component_type_add_position($term)
{

    ?>
    <div class="form-field">
        <label for="position">Position</label>
        <input type="text" name="position" id="position" value="">
    </div>
    <?php
}

add_action('component_type_add_form_fields', 'component_type_add_position', 10, 2);

/**
 * Edit Image Field
 * @return void
 */
function component_type_edit_position($term)
{

    // put the term ID into a variable
    $t_id = $term->term_id;

    $position = get_term_meta($t_id, 'position', true);
    ?>
    <tr class="form-field">
        <th><label for="position">Position</label></th>

        <td>
            <input type="text" name="position" id="position"
                   value="<?php echo esc_attr($position) ? esc_attr($position) : ''; ?>">
        </td>
    </tr>
    <?php
}

add_action('component_type_edit_form_fields', 'component_type_edit_position', 10);

/**
 * Saving Image
 */
function component_type_save_position($term_id)
{
    if (isset($_POST['position'])) {
        $position = $_POST['position'];
        if ($position) {
            update_term_meta($term_id, 'position', $position);
        }
    }
}

add_action('edited_component_type', 'component_type_save_position');
add_action('create_component_type', 'component_type_save_position');

//these filters will only affect custom column, the default column will not be affected
//filter: manage_edit-{$taxonomy}_columns
function custom_column_header($columns)
{
    $columns['position'] = 'Position Order';
    return $columns;
}

add_filter("manage_edit-component_type_columns", 'custom_column_header', 10);

add_filter('auto_update_plugin', '__return_false');

/**/