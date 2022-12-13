<?php
/**
 * @package BurningMoth \ WooCommerce \ Multisession
 * @author Tarraccas Obremski <tarraccas@burningmoth.com>
 * @copyright 2015-2017 Burning Moth Creations, Inc.
 */
namespace BMC\WooCommerce\MultiSession;

/**
 * Make sure WC_Session exists.
 */
if ( !class_exists('\WC_Session') ) {
	if ( !include_once \WP_PLUGIN_DIR . '/woocommerce/includes/abstracts/abstract-wc-session.php' ) {
		trigger_error('File ' . \WP_PLUGIN_DIR . '/woocommerce/includes/abstracts/abstract-wc-session.php does not exist! Cannot override session handler!', \E_USER_WARNING);
		return false;
	}
}

/**
 * Override session handler class for Woocom's session handler.
 *
 * @since 1.0
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
