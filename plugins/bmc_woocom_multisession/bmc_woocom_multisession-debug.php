<?php
/**
 * Debugging sandbox.
 * @since 1.12
 */
namespace BMC\WooCommerce\MultiSession\Debug;
use BMC\WooCommerce\Multisession as MultiCart;

//add_action('updated_user_meta', __NAMESPACE__.'\action_updated_user_meta', 10, 4);
function action_updated_user_meta( $meta_id = null, $user_id = null, $meta_key = null, $meta_value = null ) {
	
	if ( $meta_key == '_woocommerce_persistent_cart_' . get_current_blog_id() ) {
		
		//console( 'Updating cart #' . $user_id );
		//if ( $user_id == 1 ) console( MultiCart\cookie() );
		
	}
	
}

//add_filter('get_user_metadata', __NAMESPACE__.'\filter_get_user_metadata', 10, 3);
function filter_get_user_metadata( $value = null, $user_id = null, $meta_key = null ) {
	
	if ( $meta_key == '_woocommerce_persistent_cart_' . get_current_blog_id() ) {
		//console( 'Retrieving cart #' . $user_id );	
		//if ( $user_id == 1 ) console( MultiCart\cookie() );
	}
	
	return $value;
}

// kill problematic woocommerce sessions, force it to rebuild !
/*
$wpdb->query($wpdb->prepare(
	"DELETE FROM {$wpdb->prefix}woocommerce_sessions WHERE session_key IN (%d, %d)",			
	$data['old_customer_id'], 
	$data['customer_id']				
));
*/

/*
// kill any primary user persistent cart ... this is added somewhere in the checkout, die!!!
update_user_meta(
	namespace\cookie('user_id'), 
	'_woocommerce_persistent_cart_' . get_current_blog_id(), 
	array()
);
*/
