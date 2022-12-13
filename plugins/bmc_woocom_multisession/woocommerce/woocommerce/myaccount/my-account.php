<?php
/**
 * My Account page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/my-account.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you (the theme developer).
 * will need to copy the new files to your theme to maintain compatibility. We try to do this.
 * as little as possible, but it does happen. When this occurs the version of the template file will.
 * be bumped and the readme will list any important changes.
 *
 * @see 	    http://docs.woothemes.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

wc_print_notices(); ?>

<?php
// WC 2.5- dashboard account template.
if ( BMC\WooCommerce\MultiSession\wc_version('2.6', '<') ):

	do_action( 'woocommerce_before_my_account' );

	wc_get_template( 'myaccount/my-downloads.php' );

	wc_get_template( 'myaccount/my-orders.php', array( 'order_count' => $order_count ) );

	wc_get_template( 'myaccount/my-address.php' );

	do_action( 'woocommerce_after_my_account' );

// WC 2.6+ dashboard account template.
else: ?>

	<?php
		wc_get_template( 'myaccount/navigation.php' );
	?>

	<div class="woocommerce-MyAccount-content">
	<?php

		/**
		 * My Account content.
		 * @since 2.6.0
		 */
		do_action( 'woocommerce_account_content' );

	?>
	</div>

<?php
endif; ?>