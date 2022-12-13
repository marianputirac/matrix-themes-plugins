<?php
/**
 * @package BurningMoth \ WooCommerce \ Multisession
 * @since 1.0
 * @author Tarraccas Obremski <tarraccas@burningmoth.com>
 * @copyright 2015-2016 Burning Moth Creations, Inc.
 */
namespace BMC\WooCommerce\MultiSession;

/**
 * Make sure WC_Session exists - blows up on the admin side for some ungodly reason!
 * Thoughts: One would think that Woocom would've loaded this aforementioned class by the time it fires the hook that loads this file but that would only make sense in a good and just world!
 */
if ( !class_exists('WC_Session') ) {
	if ( !include_once WP_PLUGIN_DIR . '/woocommerce/includes/abstracts/abstract-wc-session.php' ) {
		return false;
	}
}

/**
 * Override session handler class for Woocom's session handler.
 */
class WC_Session_Handler extends \WC_Session_Handler {

	/**
	 * Return bogus customer id - short circuits any attempts by Woocom to save session data to user meta / no persistence.
	 *
	 * @return integer Bogus user id.
	 */
	public function generate_customer_id () {
		return namespace\customer_id();
	}

	/**
	 * Intercept cookie values to ensure bogus user id.
	 *
	 * @since 1.1
	 *
	 * @return array|bool Values list or false if not set or invalid.
  	 */
	public function get_session_cookie () {
		$cookie = parent::get_session_cookie();
		if ( $cookie ) $cookie[0] = $this->generate_customer_id();
		return $cookie;
	}

}
