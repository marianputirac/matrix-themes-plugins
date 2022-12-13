<?php
/**
 * @package BurningMoth \ WooCommerce \ Multisession
 * @author Tarraccas Obremski <tarraccas@burningmoth.com>
 * @copyright 2015-2017 Burning Moth Creations, Inc.
 */
namespace BMC\WooCommerce\MultiSession;

/**
 * Imposter WP_User class to replace global $current_user with.
 * Extends \WP_User class to pass WordPress' "instanceof" checks.
 * Interfaces with a cloned version of the $current_user WP_User object set by WordPress.
 * Returns $current_user->ID dynamically depending on the chain of callers.
 *
 * @since 1.10
 */
class WP_User extends \WP_User {


	/**
	 * Cloned current user object set by WordPress.
	 * @var WP_User
	 */
	public $wp_user = null;


	/**
	 * Returns true if the key is some variance of 'ID'.
	 *
	 * @param string $key
	 * @return bool
	 */
	public function key_is_id( $key = '' ) {
		return ( is_string($key) && strcasecmp($key, 'id') === 0 );
	}


	/**
	 * __construct override.
	 *
	 * @param WP_User $id
	 * 	- cloned $current_user object, passed from set_current_user action callback.
	 */
	public function __construct( $id = 0, $name = '', $blog_id = '' ) {

		// passing current cloned global current user as $id ...
		$this->wp_user = $id;

		// process our properties ...
		$keys = array_keys(get_object_vars($this));
		foreach( $keys as $key ) {
			// skip our nested user object ...
			if ( $key == 'wp_user' ) continue;

			// unset the ID so we can handle it dynamically via __get() magic method ...
			elseif ( $key == 'ID' ) unset( $this->$key );

			// reference properties from our nested user object ...
			else $this->$key =& $this->wp_user->$key;
		}

		/**
		 * Preemptively cache this object so our bogus cart user will be validated by get_user_by() and maybe other functions.
		 * Necessary for WooCommerce's WC_Customer object to validate user data.
		 * @note this compromises get_user_by(), use namespace\is_user() to validate users by id.
		 * @note caching the data property object, not WP_User itself.
		 * @todo If WooCom starts getting tricky with this data object, it's possible to extend an imposter class (like this one) from stdClass to dynamically serve values from and pass checkpoints.
		 *
		 * @since 1.11
		 *
		 * @see woocommerce/includes/class-wc-customer.php WC_Customer::__construct()
		 * @see woocommerce/includes/class-wc-data-store.php calls WC_Data_Store::load();
		 * @see woocommerce/data-stores/class-wc-customer-data-store.php WC_Customer_Data_Store::read()
		 * @see get_user_by()
		 * @see update_user_caches()
		 */
		wp_cache_set( namespace\customer_id(), $this->data, 'users' );

		// cache the original object (same data) for good measure ...
		update_user_caches( $this->wp_user );

	}


	/**
	 * __get override to return dynamic ID.
	 */
	public function __get( $key ) {

		// dynamically return the id ...
		if ( $this->key_is_id($key) ) {

			// regular expressions ...
			static $rex_wp_get_current_user;
			static $rex_get_current_user_id;
			if ( !isset($rex_wp_get_current_user) ) {

				$rex_wp_get_current_user = sprintf('/%s/Si', implode('|', array(
					'WC_Checkout::get_value',
				)));

				$rex_get_current_user_id = sprintf('/%s/Si', implode('|', array(
					// cart related ...
					'WC_Cart::get_cart_from_session',					
					'WC_Cart::persistent_cart_',					
					'WC_Cart::set_session',
					'WC_Cart::empty_cart',
					
					/**					 
					 * WooCom 3.2 moved a lot of functionality to a new WC_Cart_Session object.
					 * This fixes the contents of the previous cart showing up in new carts.
					 * @since 1.11.1
					 */
					'WC_Cart_Session::get_cart_from_session',
					'WC_Cart_Session::persistent_cart_',
					

					// address related ...
					'WC_Checkout::__construct',
					'WC_Shortcode_My_Account::edit_address',
					'WC_Customer::set_default_data',
					'my-address.php',
					'WC_Form_Handler::save_address',

					/**
					 * sets the WC()->customer ( WC_Customer ) w/cart id, shortly after the session handler override.
					 * @note WooCom merges cart and user addresses if this isn't set.
					 * @note As of WooCom 3.2 orders still process under the correct user id, do not create new users!
					 * @since 1.11
					 */
					'WooCommerce::init',

				)));

			}

			// stack trace ...
			$trace = namespace\caller();

			// intercept certain functions and templates to return cart/customer id ...
			if (
				// getting user object via wp_get_current_user()
				count(preg_grep($rex_wp_get_current_user, $trace))

				// getting user id via get_current_user_id()
				|| (
					count(preg_grep('/get_current_user_id/', $trace))
					&& count(preg_grep($rex_get_current_user_id, $trace))
				)
			) return namespace\customer_id();

			// return actual user id ...
			return $this->wp_user->ID;

		}

		// let parent handle it ...
		return parent::__get($key);
		//return $this->wp_user->$key;

	}


	/**
	 * __set override.
	 */
	public function __set( $key, $value ) {

		// NEVER set ID ... must remain dynamic ...
		if ( $this->key_is_id($key) ) {
			$this->wp_user->ID = $value;
		}

		// let parent handle it ...
		parent::__set($key, $value);
		//$this->wp_user->$key = $value;

	}


	/**
	 * __isset override.
	 */
	public function __isset( $key ) {

		// ID is ALWAYS set ... dynamic ...
		if ( $this->key_is_id($key) ) {
			return true;
		}

		// let parent handle it ...
		return parent::__isset($key);

	}


}