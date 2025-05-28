<?php

if (!function_exists('conditional_debug_display')) {
	function conditional_debug_display()
	{
		// Get the current user
		$current_user = wp_get_current_user();

		// Check if the user is logged in and is an administrator
		if ($current_user->ID === 1 && in_array('administrator', $current_user->roles)) {
			// Enable WP_DEBUG_DISPLAY
			@ini_set('display_errors', 1);
		} else {
			// Disable WP_DEBUG_DISPLAY
			@ini_set('display_errors', 0);
		}
	}
}
add_action('init', 'conditional_debug_display');

add_action('wp_enqueue_scripts', 'theme_enqueue_styles');
function theme_enqueue_styles()
{
	wp_enqueue_style('parent-style_custom', get_template_directory_uri() . '/style.css');
	wp_enqueue_style('dev-style_custom', get_stylesheet_directory_uri() . '/style-custom.css');
	wp_enqueue_style('fonts-custom-style_custom', get_stylesheet_directory_uri() . '/fonts/font.css');

	// datatable
	wp_enqueue_style('datatable-style_custom', get_stylesheet_directory_uri() . '/css/jquery.dataTables.min.css');
	wp_enqueue_script('script-sweetalert', get_stylesheet_directory_uri() . '/js/sweetalert.min.js', array(), '1.1.1', false);
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
	wp_enqueue_style('bootstrap5-style', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css');

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

	//teo for ticketing module edit ticket page
	wp_enqueue_style('stgh_customstyle', get_stylesheet_directory_uri() . '/stgh_customstyle.css');

	wp_enqueue_style('jquery-ui', '//code.jquery.com/ui/1.13.2/themes/smoothness/jquery-ui.min.css');
	wp_enqueue_script('jquery-ui-datepicker');

	wp_enqueue_script('bootstrap5-js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js');

	// Only enqueue on the 'order_repair' post type admin page
	global $typenow;
	if ($typenow == 'order_repair') {
		$screen = get_current_screen();

		// Check if the current screen is the list table for the custom post type
		if ($screen && $screen->base == 'edit') {
			wp_enqueue_style('select2-css', 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css');
			wp_enqueue_script('select2-js', 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js', array('jquery'), '4.1.0-rc.0', true);
		}
	}
}


// Include custom functions to Theme Functions
include_once(get_stylesheet_directory() . '/includes/admin_menu.php');
include_once(get_stylesheet_directory() . '/includes/custom-posts-functions.php');
include_once(get_stylesheet_directory() . '/includes/filters-dashboard-lists.php');
include_once(get_stylesheet_directory() . '/includes/woocommerce-functions.php');
include_once(get_stylesheet_directory() . '/includes/login_redirects.php');
include_once(get_stylesheet_directory() . '/includes/add-meta-boxes.php');
include_once(get_stylesheet_directory() . '/includes/user-functions.php');
include_once(get_stylesheet_directory() . '/includes/awning-functions.php');

include_once(get_stylesheet_directory() . '/includes/dashboard_footer.php');
include_once(get_stylesheet_directory() . '/includes/class_quickbooks.php');
include_once(get_stylesheet_directory() . '/includes/class-orders.php');
include_once(get_stylesheet_directory() . '/includes/ajax.php');
/**
 * Mail custom settings
 */
include_once(get_stylesheet_directory() . '/includes/mail-settings.php');
/**
 * Grup functions
 */
include_once(get_stylesheet_directory() . '/includes/grup-functions.php');
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
			  'caroline@anyhooshutter.com', 'july@anyhooshutter.com', 'tudor@lifetimeshutters.com',
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
	if (isset($_GET['s']) && is_numeric($_GET['s'])) {
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

// Hook the function to the 'views_edit-container' filter
add_filter('views_edit-container', 'modify_dashboard_container_html');

/**
 * Add a script to the dashboard page to fetch container data and modify HTML
 */
function modify_dashboard_container_html($views)
{
	// Start script tag
	echo "<script type='text/javascript'>
        jQuery(document).ready(function($) {
            var start = new Date().getTime();
            var arrayIds = [];

            // Collect container IDs from the HTML
            $('span[data-id]').each(function() {
                var dataLayer = $(this).data('id');
                arrayIds.push(dataLayer);
            });

            // Set up AJAX data
            var data_sqm = {
                'action': 'get_container_sqm',
                'conatiner_orders': arrayIds
            };

            var data_price = {
                'action': 'get_container_price',
                'conatiner_orders': arrayIds
            };

            var ajaxurl = '" . admin_url('admin-ajax.php') . "';

            // Fetch container price data and update the HTML
            jQuery.post(ajaxurl, data_price, function(response) {
                console.log(response);
                var containers_price = JSON.parse(response);
                for (const [key, value] of Object.entries(containers_price)) {
                    jQuery('#container-price-' + key).text(value);
                }
                var end = new Date().getTime();
                console.log('price milliseconds passed', end - start);
            });

            // Fetch container sqm data and update the HTML
            jQuery.post(ajaxurl, data_sqm, function(response) {
                var containers_sqm = JSON.parse(response);
                for (const [key, value] of Object.entries(containers_sqm)) {
                    jQuery('#container-sqm-' + key).text(value);
                }
                var end = new Date().getTime();
                console.log('sqm milliseconds passed', end - start);
            });
        });
    </script>";

	// Return the original views
	return $views;
}


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

	// Step 1: Check if 'cost_repair' is set and not empty in the POST request
	if (isset($_POST['cost_repair']) && !empty($_POST['cost_repair'])) {

		// Step 2: Retrieve the existing 'cost_repair' and 'warranty' meta values for the post
		$existing_cost = get_post_meta($post_id, 'cost_repair', true);
		$existing_warranty = get_post_meta($post_id, 'warranty', true);

		// Step 3: Initialize a variable to track warranty status
		$no_warranty = false;

		// Step 4: Retrieve the 'type_cost' from the POST request and update its post meta
		$type_cost = $_POST['type_cost'];
		update_post_meta($post_id, 'type_cost', $type_cost);

		// Step 5: Compare the existing 'cost_repair' meta value with the new value from POST
		if ($existing_cost !== $_POST['cost_repair']) {
			// The code to execute if the existing cost is different from the new cost
			// This is where you would typically update the 'cost_repair' meta value
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
			  'order@lifetimeshutters.com', 'tudor@lifetimeshutters.com', $user_mail,
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

// Hook the function to a custom WordPress cron event
add_action('ticketing_cron_custom', 'ticket_cron_mail');

/**
 * Send reminder emails for unanswered tickets
 */
function ticket_cron_mail()
{
	// Query unanswered tickets
	$loop = new WP_Query(array(
	  'post_type' => 'stgh_ticket',
	  'post_status' => array('stgh_answered', 'stgh_new'),
	));

	// Loop through the tickets
	if ($loop->have_posts()) :
		while ($loop->have_posts()) : $loop->the_post();
			$ticket_id = get_the_id();
			$post = get_post($ticket_id);
			$title = $post->post_title;

			// Get ticket owner information
			$user_id_ticket = get_post_meta($ticket_id, '_stgh_contact', true);
			$user_info = get_userdata($user_id_ticket);
			$email = $user_info->user_email;
			$first_name = get_user_meta($user_id_ticket, 'billing_first_name', true);
			$last_name = get_user_meta($user_id_ticket, 'billing_last_name', true);

			// Set up email content
			$permalink = get_site_url() . '/factory-queries/';
			$multiple_recipients = array($email, 'tudor@lifetimeshutters.com');
			$body = 'Dear ' . $first_name . ' ' . $last_name . ',
                <br><br>
                The above order is still on hold at the factory as they have questions they need you to answer,
                <br><br>
                Please log on to the Matrix Ordering System at Factory Querries Page to address the question.
                <br><br>
                In case of any queries please contact Customer Support on 0777 246 0159
                <br><br>
                Best Regards<br>
                Matrix Ordering System';
			$subject = 'Kind reminder: Not-Answered Querry for Order ' . $title;
			$headers = array('Content-Type: text/html; charset=UTF-8');

			// Send email
			wp_mail($multiple_recipients, $subject, $body, $headers);
		endwhile;
	endif;

	// Reset the post data
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

/**
 * Hook principal declanșat la salvarea unei comenzi ('shop_order').
 * Gestionează recalcularea custom a totalurilor comenzii și actualizarea tabelei `wp_custom_orders`.
 *
 * @param int $order_id ID-ul comenzii salvate.
 */
function matrix_trigger_order_recalculation_on_save($order_id)
{
	/*
	 * Verificări de securitate și validare inițială.
	 */

	// Verifică dacă postul salvat este într-adevăr o comandă (verificare suplimentară)
	if (get_post_type($order_id) !== 'shop_order') {
		return;
	} elseif (get_post_type($order_id) === 'shop_order') {

		// Rulează funcția principală de recalculare
		matrix_recalculate_order_totals_and_update_custom_table($order_id);
	}
}


// Înlocuiește hook-ul vechi cu cel specific pentru comenzi
add_action('save_post', 'matrix_trigger_order_recalculation_on_save', 100, 1);

/**
 * Funcția principală pentru recalcularea totalurilor comenzii și actualizarea tabelei custom.
 * ATENȚIE: Această funcție suprascrie calculele standard WooCommerce cu o logică custom.
 *
 * @param int $order_id ID-ul comenzii.
 */
function matrix_recalculate_order_totals_and_update_custom_table($order_id)
{
	error_log("--- Starting Custom Order Recalculation for Order ID: {$order_id} ---");

	$order = wc_get_order($order_id);
	if (!$order) {
		error_log("Recalculation Error: Could not get order object for ID: {$order_id}");
		return;
	}

	// Obține datele necesare comenzii
	$user_id_customer = $order->get_customer_id(); // Folosim ID-ul clientului comenzii
	$items = $order->get_items(); // Obține itemii 'line_item'
	$shipping_country = $order->get_shipping_country();

	// Inițializează totalurile calculate
	$calculated_items_subtotal_gbp = 0; // Suma (Preț item * Cantitate + Preț tren) pentru itemii procesați
	$calculated_items_tax_gbp = 0;      // Suma taxelor pentru itemii procesați
	$calculated_total_usd = 0;          // Suma (Preț USD * Cantitate) pentru itemii procesați
	$calculated_total_sqm = 0;          // Suma (SQM * Cantitate) pentru itemii procesați
	$calculated_order_train_price = 0;  // Suma totală a prețului de transport 'tren'

	$order_contains_pos = false; // Flag pentru a detecta produse din categorii speciale (POS)

	// Determină rata de taxare (Logica originală specifică UK/IE)
	// @TODO: Ar trebui ideal să folosească sistemul de taxe WC bazat pe clasele de taxe ale produselor/transportului.
	$tax_rate_percent = 0;
	if (in_array($shipping_country, ['GB', 'IE'])) {
		$tax_rate_percent = 20;
	}
	error_log("Order {$order_id}: Shipping Country={$shipping_country}, Custom Tax Rate Used={$tax_rate_percent}%");

	// --- Iterează prin itemii comenzii ---
	foreach ($items as $item_id => $item_data) {
		$product_id = $item_data->get_product_id();
		$product = $item_data->get_product(); // Obține obiectul produs asociat itemului

		if (!$product) {
			error_log("Recalculation Warning for Order {$order_id}: Could not get product object for Product ID: {$product_id}, Item ID: {$item_id}");
			continue; // Treci la următorul item dacă produsul nu poate fi încărcat
		}

		// Verifică dacă produsul aparține categoriilor POS definite
		if (has_term([20, 34, 26], 'product_cat', $product_id)) {
			$order_contains_pos = true;
			error_log("Order {$order_id}: Item ID {$item_id} (Product ID {$product_id}) is POS category. Skipping custom calculation.");
			// Logica originală oprea complet actualizarea finală dacă existau itemi POS.
			// Păstrăm acest comportament. Alternativ, am putea aduna totalurile existente ale acestor itemi.
			continue; // Treci la următorul item
		}

		// Inițializează variabilele pentru linia curentă
		$line_subtotal_gbp = 0;
		$line_tax_gbp = 0;
		$line_total_usd = 0;
		$line_total_sqm = 0;
		$item_train_price_total = 0;
		$item_quantity = $item_data->get_quantity(); // Folosim cantitatea din comandă
		$item_quantity = (empty($item_quantity) || $item_quantity == 0) ? 1 : $item_quantity;

		// --- Logică specifică pentru categoria 'awning' ---
		if (has_term('awning', 'product_cat', $product_id)) {
			error_log("Order {$order_id}, Item ID {$item_id}: Detected 'awning' category.");

			// Prețul GBP este citit din meta itemului 'final_price_uk'
			$base_price_gbp = floatval($item_data->get_meta('final_price_uk', true));
			// SQM este citit din meta itemului 'awning_sqm'
			$sqm = floatval($item_data->get_meta('awning_sqm', true));
			// Prețul USD este citit din meta itemului 'final_price_china'
			$price_usd = floatval($item_data->get_meta('final_price_china', true));

			// Calculează prețul 'tren'
			if ($sqm > 0) {
				// Folosim ID-ul clientului comenzii pentru a căuta prețul tren specific lui
				$train_price_per_sqm = matrix_get_train_price_for_customer($user_id_customer);
				if ($train_price_per_sqm > 0) {
					$item_train_price_total = $sqm * $item_quantity * $train_price_per_sqm;
				}
			}

			// Calculează valorile liniei pentru awning
			$line_subtotal_gbp = ($base_price_gbp * $item_quantity) + $item_train_price_total;
			$line_tax_gbp = ($line_subtotal_gbp * $tax_rate_percent) / 100;
			$line_total_usd = $price_usd * $item_quantity;
			$line_total_sqm = $sqm * $item_quantity;

			// Log specific pentru awning
			error_log("Order {$order_id}, Awning Item {$item_id}: Qty={$item_quantity}, BaseGBP={$base_price_gbp}, SQM={$sqm}, TrainPriceTotal={$item_train_price_total}, USDPrice={$price_usd}");
		} // --- Logică pentru itemii standard (nu sunt POS și nu sunt awning) ---
		else {
			error_log("Order {$order_id}, Item ID {$item_id}: Processing as standard item.");

			// Prețul GBP este prețul standard al produsului
			$base_price_gbp = floatval($product->get_price());
			// SQM este citit din meta produsului 'property_total'
			$sqm = floatval($product->get_meta('property_total', true));
			// Prețul USD este citit din meta produsului 'dolar_price'
			$price_usd = floatval($product->get_meta('dolar_price', true));

			// Calculează prețul 'tren'
			if ($sqm > 0) {
				$train_price_per_sqm = matrix_get_train_price_for_customer($user_id_customer);
				if ($train_price_per_sqm > 0) {
					$item_train_price_total = $sqm * $item_quantity * $train_price_per_sqm;
					// Actualizăm meta produsului (ca în logica originală - deși poate nu e ideal)
					// @TODO: Revizuiește dacă actualizarea meta *produsului* e necesară aici.
					update_post_meta($product_id, 'train_delivery', $item_train_price_total);
				}
			}

			// Calculează valorile liniei pentru item standard
			$line_subtotal_gbp = ($base_price_gbp * $item_quantity) + $item_train_price_total;
			$line_tax_gbp = ($line_subtotal_gbp * $tax_rate_percent) / 100;
			$line_total_usd = $price_usd * $item_quantity;
			$line_total_sqm = $sqm * $item_quantity;

			// Log specific pentru standard
			error_log("Order {$order_id}, Standard Item {$item_id}: Qty={$item_quantity}, BaseGBP={$base_price_gbp}, SQM={$sqm}, TrainPriceTotal={$item_train_price_total}, USDPrice={$price_usd}");
		}

		// --- Actualizează meta itemului în comandă (comun pentru awning și standard) ---
		// Asigură că actualizăm cantitatea corectă (cea din comandă)
		wc_update_order_item_meta($item_id, '_qty', $item_quantity);
		// Actualizează subtotalul și taxa calculate
		wc_update_order_item_meta($item_id, '_line_subtotal', $line_subtotal_gbp);
		wc_update_order_item_meta($item_id, '_line_tax', $line_tax_gbp);
		// Actualizează totalul liniei (WC îl calculează de obicei, dar îl setăm explicit pentru consistență cu calculul nostru)
		// Total linie = Subtotal linie (înainte de taxe)
		wc_update_order_item_meta($item_id, '_line_total', $line_subtotal_gbp);
		wc_update_order_item_meta($item_id, '_line_subtotal_tax', $line_tax_gbp);
		// Setează datele taxei (presupunând ID rată taxă = 1, ca în original)
		// @TODO: Folosește un ID de taxă real sau lasă WC să calculeze dacă e posibil.
		$line_tax_data_array = ($line_tax_gbp > 0) ? ['total' => [1 => $line_tax_gbp], 'subtotal' => [1 => $line_tax_gbp]] : [];
		wc_update_order_item_meta($item_id, '_line_tax_data', $line_tax_data_array);

		// --- Adaugă la totalurile calculate ale comenzii ---
		$calculated_items_subtotal_gbp += $line_subtotal_gbp;
		$calculated_items_tax_gbp += $line_tax_gbp;
		$calculated_total_usd += $line_total_usd;
		$calculated_total_sqm += $line_total_sqm;
		// Corectăm calculul pentru prețul total de tren al comenzii
		$calculated_order_train_price += $item_train_price_total;

		error_log("Order {$order_id}, Item ID {$item_id} Processed: SubtotalGBP={$line_subtotal_gbp}, TaxGBP={$line_tax_gbp}, TotalUSD={$line_total_usd}, TotalSQM={$line_total_sqm}");
	} // Sfârșit foreach items

	// --- Actualizează meta și totalurile comenzii (doar dacă nu conține POS) ---
	if ($order_contains_pos) {
		error_log("Order {$order_id} contains POS items. Skipping final order total update and custom table update.");
		return; // Oprește execuția conform logicii originale
	}

	// Salvează prețul total 'tren' pe meta comenzii dacă e mai mare ca zero
	if ($calculated_order_train_price > 0) {
		update_post_meta($order_id, 'order_train', number_format($calculated_order_train_price, 2, '.', ''));
		error_log("Order {$order_id}: Updated 'order_train' meta: " . number_format($calculated_order_train_price, 2, '.', ''));
	} else {
		// Șterge meta dacă totalul tren este zero (opțional, pentru curățenie)
		delete_post_meta($order_id, 'order_train');
	}

	// Obține costul de transport și taxa pe transport direct din obiectul comenzii
	$order_shipping_total = $order->get_shipping_total();
	$order_shipping_tax = $order->get_shipping_tax();

	// Calculează totalul final GBP al comenzii
	// Total = Subtotal Itemi + Taxa Itemi + Total Transport + Taxa Transport
	$final_order_total_gbp = $calculated_items_subtotal_gbp + $calculated_items_tax_gbp + $order_shipping_total + $order_shipping_tax;
	$final_total_tax_gbp = $calculated_items_tax_gbp + $order_shipping_tax;

	error_log("Order {$order_id}: Calculated Finals: SubtotalItemsGBP={$calculated_items_subtotal_gbp}, TaxItemsGBP={$calculated_items_tax_gbp}, ShippingGBP={$order_shipping_total}, ShippingTaxGBP={$order_shipping_tax}, FinalTotalGBP={$final_order_total_gbp}");

	// Actualizează totalurile comenzii folosind metodele WC

	$order->set_props([
	  'total' => $final_order_total_gbp,       // Totalul general
	  'total_tax' => $final_total_tax_gbp,       // Taxa totală (itemi + transport)
	  'cart_tax' => $calculated_items_tax_gbp,  // Taxa doar pe itemi
		// Nota: Setarea subtotalului aici poate fi problematică. WC calculează subtotalul
		// ca suma prețurilor itemilor *înainte* de discounturi. Calculul nostru custom
		// ($calculated_items_subtotal_gbp) include prețul tren. Lăsăm WC să-l calculeze.
		// 'subtotal' => wc_format_decimal($calculated_items_subtotal_gbp),
	]);
	$save_result = $order->save(); // Salvează modificările în obiect și, teoretic, în DB/meta

	if ($save_result === false || $save_result === 0 || is_wp_error($save_result)) {
		$error_message = is_wp_error($save_result) ? $save_result->get_error_message() : 'Save returned false/0.';
		error_log("Order {$order_id}: ERROR - Order save via object FAILED! Reason: " . $error_message);
		// Chiar dacă save() eșuează, putem forța update-ul meta mai jos
	} else {
		error_log("Order {$order_id}: Order save via object SUCCEEDED.");
	}

	// 2. Forțează actualizarea directă a câmpurilor postmeta (pentru compatibilitate cu versiuni vechi)
	// Folosim valorile calculate, formatate corespunzător.
	error_log("Order {$order_id}: Forcing update of legacy postmeta totals...");

	update_post_meta($order_id, '_order_total', wc_format_decimal($final_order_total_gbp));
	update_post_meta($order_id, '_order_tax', wc_format_decimal($final_total_tax_gbp));
	update_post_meta($order_id, '_cart_tax', wc_format_decimal($calculated_items_tax_gbp));
	update_post_meta($order_id, '_order_shipping_tax', wc_format_decimal($order_shipping_tax));
	// Poți actualiza și alte meta-uri dacă este necesar (ex: _order_shipping)
	// update_post_meta($order_id, '_order_shipping', wc_format_decimal($order_shipping_total));

	error_log("Order {$order_id}: Legacy postmeta totals updated via update_post_meta.");

	// După salvare, putem apela funcția de update a tabelei custom
	matrix_update_custom_orders_table(
	  $order, // Trimitem obiectul order actualizat
	  $calculated_total_usd,
	  $calculated_total_sqm,
	  $calculated_items_subtotal_gbp // Trimitem subtotalul calculat custom
	);

	error_log("--- Finished Custom Order Recalculation for Order ID: {$order_id} ---");
}


/**
 * Funcție helper pentru a obține prețul 'train' pentru un client.
 * Caută în user meta, apoi recurge la un default global.
 *
 * @param int|null $customer_id ID-ul clientului (poate fi 0 pentru guest).
 * @return float Prețul tren per SQM.
 */
function matrix_get_train_price_for_customer($customer_id)
{
	$train_price = null;

	// Obține prețul specific clientului dacă există și clientul e valid
	if ($customer_id > 0) {

		$train_price = get_user_meta($customer_id, 'train_price', true);
		if ($train_price === null || $train_price === '') {
			$train_price = get_post_meta(1, 'train_price', true);
		}
	}

	// Asigurăm că nu e negativ
	return max(0, $train_price);
}


/**
 * Actualizează sau inserează date într-o tabelă custom `wp_custom_orders`.
 *
 * @param WC_Order $order Obiectul comenzii WooCommerce actualizat.
 * @param float $calculated_total_usd Totalul calculat în USD.
 * @param float $calculated_total_sqm Totalul calculat în SQM.
 * @param float $calculated_subtotal_gbp Subtotalul GBP calculat custom (itemi + tren).
 */
function matrix_update_custom_orders_table($order, $calculated_total_usd, $calculated_total_sqm, $calculated_subtotal_gbp)
{
	if (!$order instanceof WC_Order) {
		return;
	}

	global $wpdb;
	$table_name = $wpdb->prefix . 'custom_orders'; // Asigură prefix corect
	$order_id = $order->get_id();

	error_log("Order {$order_id}: Updating/Inserting into custom table `{$table_name}`.");

	// Obține date actualizate din obiectul order *după* ce a fost salvat
	$total_gbp_final = $order->get_total(); // Totalul final GBP din comanda salvată
	$shipping_total = $order->get_shipping_total();
	$order_status = $order->get_status();
	$date_created = $order->get_date_created() ? $order->get_date_created()->date('Y-m-d H:i:s') : current_time('mysql', 1);
	// Obține metoda de livrare principală (poate fi mai complex dacă sunt multiple)
	$shipping_methods = $order->get_shipping_methods();
	$shipping_delivery = !empty($shipping_methods) ? current($shipping_methods)->get_method_title() : ''; // Ia titlul primei metode

	// Date comune pentru insert/update
	$custom_data = [
	  'usd_price' => number_format($calculated_total_usd, 2, '.', ''),
	  'gbp_price' => number_format($total_gbp_final, 2, '.', ''), // Folosim totalul final salvat
	  'subtotal_price' => number_format($calculated_subtotal_gbp, 2, '.', ''), // Folosim subtotalul custom calculat
	  'sqm' => number_format($calculated_total_sqm, 2, '.', ''),
	  'shipping_cost' => number_format($shipping_total, 2, '.', ''),
	  'delivery' => $shipping_delivery, // Folosim metoda de livrare
	  'status' => $order_status,
		// 'reference' și 'createTime' sunt gestionate mai jos
	];

	// Verifică existența înregistrării
	$order_exists_count = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM `{$table_name}` WHERE `idOrder` = %d", $order_id));

	if ($order_exists_count > 0) {
		// Update
		$where = ['idOrder' => $order_id];
		$updated = $wpdb->update($table_name, $custom_data, $where);

		if ($updated !== false) {
			error_log("Order {$order_id}: Updated record successfully in `{$table_name}`.");
		} else {
			error_log("Order {$order_id}: Failed to update record in `{$table_name}`. WPDB Error: " . $wpdb->last_error);
		}
	} else {
		// Insert
		$custom_data['idOrder'] = $order_id;
		// Construiește referința folosind cart_name din meta (dacă există)
		$cart_name = $order->get_meta('cart_name', true); // Obține meta direct din obiectul order
		$custom_data['reference'] = 'LF0' . $order->get_order_number() . ($cart_name ? ' - ' . $cart_name : '');
		$custom_data['createTime'] = $date_created;

		$inserted = $wpdb->insert($table_name, $custom_data);

		if ($inserted) {
			error_log("Order {$order_id}: Inserted new record successfully into `{$table_name}`.");
		} else {
			error_log("Order {$order_id}: Failed to insert record into `{$table_name}`. WPDB Error: " . $wpdb->last_error);
		}
	}
}


/*
 * POS - add to cart redirect to checkout
 */

add_filter('woocommerce_add_to_cart_redirect', 'redirect_to_checkout');

function redirect_to_checkout()
{
	global $woocommerce;
	$checkout_url = wc_get_checkout_url();
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

//This code snippet will remove the postcode validation that WooCommerce performs during the checkout process.
add_action('woocommerce_checkout_process', 'custom_disable_postcode_validation', 10, 0);

function custom_disable_postcode_validation()
{
	remove_action('woocommerce_checkout_process', 'woocommerce_checkout_postcode_validation', 10);
}


add_filter('woocommerce_default_address_fields', 'custom_override_default_address_fields');

function custom_override_default_address_fields($address_fields)
{
	$address_fields['postcode']['required'] = false;
	return $address_fields;
}


//
add_action('wp_ajax_addToCartAppOrder', 'add_to_cart_app_order_handler');
add_action('wp_ajax_nopriv_addToCartAppOrder', 'add_to_cart_app_order_handler');

function add_to_cart_app_order_handler()
{
	if (!isset($_POST['product_ids']) || empty($_POST['product_ids'])) {
		wp_send_json_error(['message' => 'Missing required parameters.']);
		wp_die();
	}

	$cart_id = $_POST['cartID'];

	// Retrieve and process product IDs
	$product_ids_string = sanitize_text_field($_POST['product_ids']);
	// Check if the string contains a comma
	if (strpos($product_ids_string, ',') === false) {
		$product_ids = array(intval($product_ids_string));
	} else {
		$product_ids = array_map('intval', explode(',', $product_ids_string));
	}
	$quantity = 1;

	if (empty($product_ids)) {
		wp_send_json_error(['message' => 'The list of IDs is empty.']);
		wp_die();
	}

	// Add each product to cart
	foreach ($product_ids as $product_id) {
		WC()->cart->add_to_cart($product_id, $quantity);
	}

	// Set a flag in the cart session indicating that this cart was created from a mobile app
	WC()->session->set('app_order', true);

	WC()->session->set_customer_session_cookie(true);

	// Optionally update cart totals and refresh session cookie
	WC()->cart->calculate_totals();

	$post = array('ID' => $cart_id, 'post_status' => 'draft');
	wp_update_post($post);

	wp_send_json_success(['message' => 'Products have been added to the cart.']);
	wp_die();
}


/* Afisare dealeri pe harta cu Google Maps >>>*/

function get_user_addresses()
{
	global $wpdb;
	$users = get_users();
	$addresses = [];

	foreach ($users as $user) {
		$postcode = get_user_meta($user->ID, 'billing_postcode', true);
		$phone = get_user_meta($user->ID, 'billing_phone', true);
		$fname = get_user_meta($user->ID, 'first_name', true);
		$lname = get_user_meta($user->ID, 'last_name', true);
		$webst = get_the_author_meta('user_url', $user->ID);
		$group = get_user_meta($user->ID, 'current_selected_group', true);

		if (!empty($postcode)) {
			$coordinates = get_lat_long($postcode);

			if ($coordinates) {
				$addresses[] = [
				  'name' => $user->billing_company,
				  'lat' => $coordinates['lat'],
				  'lng' => $coordinates['lng'],
				  'phone' => $phone ?: 'unknown',
				  'fname' => $fname ?: 'unknown',
				  'lname' => $lname ?: 'unknown',
				  'webst' => $webst ?: 'unknown',
				  'group' => $group ?: 'unknown',
				];
			}
		}
	}
//var_dump($group);
	wp_send_json($addresses);
}


add_action('wp_ajax_get_user_addresses', 'get_user_addresses');
add_action('wp_ajax_nopriv_get_user_addresses', 'get_user_addresses');

function get_lat_long($postcode)
{
	$api_key = 'AIzaSyBPPeizcEJ5ZmoW9l6bS08xjme6BdJXGtw';
	$postcode = urlencode($postcode);
	$url = "https://maps.googleapis.com/maps/api/geocode/json?address={$postcode}&key={$api_key}";

	$response = wp_remote_get($url);
	$body = wp_remote_retrieve_body($response);
	$json = json_decode($body, true);

	if ($json['status'] == 'OK') {
		return $json['results'][0]['geometry']['location'];
	}
	return null;
}


function load_google_maps_script()
{
	?>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBPPeizcEJ5ZmoW9l6bS08xjme6BdJXGtw&libraries=marker"></script>
    <script>
        var ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";
    </script>
    <script src="<?php echo get_template_directory_uri(); ?>-child/js/th_map.js"></script>
	<?php
}


add_action('wp_footer', 'load_google_maps_script');

function dealers_on_map_page()
{
	$ze_map_page = home_url('/show-dealers-on-map/');

	add_menu_page(
	  'Dealers On Map',
	  'Dealers On Map',
	  'edit_posts', // Change capability here if needed.
	  'dealers-on-map',
	  '',
	  'dashicons-location-alt',
	  69
	);

	// Adăugăm JavaScript pentru a forța deschiderea într-un tab nou
	add_action('admin_footer', function () use ($ze_map_page) {
		?>
        <script type="text/javascript">
            document.addEventListener("DOMContentLoaded", function () {
                let link = document.querySelector('a.toplevel_page_dealers-on-map');
                if (link) {
                    link.setAttribute("href", "<?php echo esc_url($ze_map_page); ?>");
                    link.setAttribute("target", "_blank"); // Deschide într-un tab nou
                }
            });
        </script>
		<?php
	});
}


add_action('admin_menu', 'dealers_on_map_page');

function restrict_dealers_on_map_page()
{
	// Verifică dacă utilizatorul este autentificat și are rolul de administrator sau employee
	if (is_page('show-dealers-on-map') && !current_user_can('administrator') && !current_user_can('emplimited')) {
		// Redirecționează utilizatorii neautorizați către o altă pagină sau afișează un mesaj de eroare
		wp_redirect(home_url()); // Redirecționează către pagina principală
		exit;
	}
}


add_action('template_redirect', 'restrict_dealers_on_map_page');
/* <<< Afisare dealeri pe harta cu Google Maps*/

add_action('woocommerce_checkout_create_order', 'add_type_order_meta_to_awning_order', 20, 2);
function add_type_order_meta_to_awning_order($order, $data)
{
	// Parcurge fiecare item din comandă
	foreach ($order->get_items() as $item) {
		// Obține produsul (dacă există)
		$product = $item->get_product();
		$product_name = $product ? $product->get_name() : $item->get_name();

		// Verifică dacă numele produsului conține "awning product"
		if (stripos($product_name, 'awning product') !== false) {
			// Adaugă meta-ul la comandă
			$order->update_meta_data('type_order', 'awning');
			break; // Nu mai este nevoie să verificăm alte item-uri
		}
	}
}
