<?php
/**
 * @package BurningMoth \ WooCommerce \ Multisession
 * @since 1.4
 * @author Tarraccas Obremski <tarraccas@burningmoth.com>
 * @copyright 2015-2017 Burning Moth Creations, Inc.
 */
namespace BMC\WooCommerce\MultiSession;
class BMC_WooCom_MultiSession_Widget extends \WP_Widget {

	public function __construct() {
		parent::__construct(
			// base id ...
			'bmc-woocom-multisession',

			// name ...
			__('WooCommerce Multiple Carts', 'bmc-woocom-multisession'),

			// options ...
			array(
				'classname'		=> 'bmc-woocom-multisession-widget',
				'description' 	=> __('Manage and switch between multiple shopping carts.', 'bmc-woocom-multisession')
			)
		);
	}


	private static function defaults( $instance ) {
		return array_merge(array(
			'title' => ''
		), $instance);
	}


	public function form( $instance ) {

		$instance = self::defaults($instance);

		printf(
			'<p><label for="%s">%s</label><input type="text" class="widefat" id="%s" name="%s" value="%s" placeholder="%s"/></p>',
			$this->get_field_id('title'),
			__('Title', 'woocommerce'),
			$this->get_field_id('title'),
			$this->get_field_name('title'),
			esc_attr($instance['title']),
			esc_attr__('Title', 'woocommerce')
		);

	}


	/**
	 * @since 1.11
	 *	- Updated method that returns the cart page url, depreciated WooComm 2.5.
	 *	- Move carts sorting to cookie.
	 */
	public function widget( $args, $instance ) {

		// not logged in? exit stage left!
		if ( !is_user_logged_in() ) return;

		// load defaults ...
		$instance = self::defaults($instance);

		// start widget markup ...
		echo $args['before_widget'],
		$args['before_title'],
		$instance['title'],
		$args['after_title'];

		// retrieve carts ...
		$carts = namespace\get_carts();

		print('<nav><ul>');

		foreach ( $carts as $cart_id => $cart ) {

			$mode = array_key_exists('mode', $cart) ? $cart['mode'] : 'cart';
			if ( $mode !== 'bin' ) $mode = 'cart';

			$current_cart = ( $cart_id == namespace\customer_id() );

			if ( $current_cart ) {

				printf(
					'<li id="cart-%s" class="cart-select cart-current cart-mode-%s"><a href="#expand" class="cart-expand"></a><a href="%s" title="%s" target="%s" onclick="return BMCWcMs.command(\'select\', this);">%s</a>',
					$cart_id,
					$mode,
					( namespace\wc_version('2.5', '>=') ? wc_get_cart_url() : WC()->cart->get_cart_url() ),
					esc_attr__('Cart Page', 'woocommerce'),
					$cart_id,
					namespace\format_cart_name($cart['name'])
				);

			}

			else {

				printf(
					'<li id="cart-%s" class="cart-select cart-mode-%s"><a href="#expand" class="cart-expand"></a><a href="#select" target="%s" onclick="return BMCWcMs.command(\'select\', this);" title="%s">%s</a>',
					$cart_id,
					$mode,
					$cart_id,
					esc_attr__('Select Cart', 'bmc-woocom-multisession'),
					namespace\format_cart_name($cart['name'])
				);

			}

			print('<ul>');

			// update command ...
			printf(
				'<li id="cart-edit-%s" class="cart-edit"><a href="#edit" target="%s" onclick="return BMCWcMs.command(\'update\', this);">%s</a></li>',
				$cart_id,
				$cart_id,
				__('Edit Name', 'bmc-woocom-multisession')
			);

			// switch to shopping cart mode ...
			printf(
				'<li id="cart-mode-cart-%s" class="cart-mode-cart"><a href="#mode" target="%s" title="%s"  onclick="return BMCWcMs.command(\'mode\', this);">%s</a></li>',
				$cart_id,
				$cart_id,
				esc_attr__('Switch to Shopping Cart mode. Shopping carts are emptied upon checkout.', 'bmc-woocom-multisession'),
				__('Shopping Cart', 'bmc-woocom-multisession')
			);

			// switch to storage bin mode ...
			printf(
				'<li id="cart-mode-bin-%s" class="cart-mode-bin"><a href="#mode" target="%s" title="%s"  onclick="return BMCWcMs.command(\'mode\', this);">%s</a></li>',
				$cart_id,
				$cart_id,
				esc_attr__('Switch to Storage Bin mode. Storage bins retain items after checkout.', 'bmc-woocom-multisession'),
				__('Storage Bin', 'bmc-woocom-multisession')
			);

			// clone command ...
			if ( count($carts) < 10 ) {

				// update command ...
				printf(
					'<li id="cart-clone-%s" class="cart-clone"><a href="#clone" target="%s" onclick="return BMCWcMs.command(\'clone\', this);" title="%s">%s</a></li>',
					$cart_id,
					$cart_id,
					esc_attr__('Create a new cart with the items and addresses of this cart.', 'bmc-woocom-multisession'),
					__('Clone Cart', 'bmc-woocom-multisession')
				);

			}

			if ( !$current_cart ) {

				// copy command ...
				printf(
					'<li id="cart-copy-%s" class="cart-copy"><a href="#copy" target="%s" onclick="return BMCWcMs.command(\'copy\', this);" title="%s">%s</a></li>',
					$cart_id,
					$cart_id,
					esc_attr__('Copy items from this cart to your current cart.', 'bmc-woocom-multisession'),
					__('Copy Items', 'bmc-woocom-multisession')
				);

				// delete command ...
				printf(
					'<li id="cart-delete-%s" class="cart-delete"><a href="#delete" target="%s" onclick="return BMCWcMs.command(\'delete\', this);">%s</a></li>',
					$cart_id,
					$cart_id,
					__('Delete Cart', 'bmc-woocom-multisession')
				);

			}

			// cart items ...
			printf(
				'<li id="cart-items-%s" class="cart-items"><div class="cart-items-loading"></div></li>',
				$cart_id,
				$cart_id
			);

			print('</ul></li>');

		}


		// new cart if within max # of carts and session size (2kb) allowed ...
		if ( namespace\option('can_add_cart') ) {

			// new cart command ...
			printf(
				'<li class="cart-new"><a href="#new" onclick="return BMCWcMs.command(\'insert\', this);">%s</a></li>',
				__('New Cart', 'bmc-woocom-multisession')
			);

		}

		// not sync'd to user account ? permit manual sync'ing on key ...
		if ( !namespace\option('sync_per_user') ) {

			$session_key = namespace\cookie('key');

			printf(
				'<li class="cart-%s">',
				( $session_key ? 'syncd' : 'sync' )
			);

			if ( $session_key ) {
				print('<a class="cart-expand"></a>');
			}

			// sync command ...
			printf(
				'<a href="#sync" title="%s" onclick="return BMCWcMs.command(\'sync\', this);">%s</a>',
				sprintf(
					( $session_key ? esc_attr__('Carts saved to %s.', 'bmc-woocom-multisession') : esc_attr__('Save or load carts to or from %s with a key phrase.', 'bmc-woocom-multisession') ),
					get_bloginfo('name')
				),
				( $session_key ? __('Carts Saved', 'bmc-woocom-multisession') : __('Save / Load Carts', 'bmc-woocom-multisession') )
			);

			if ( $session_key ) {

				// unsync command ...
				printf(
					'<ul><li class="cart-unsync"><a href="#unsync" title="%s" onclick="return BMCWcMs.command(\'unsync\', this);">%s</a></li></ul>',
					sprintf(
						esc_attr__('Stop saving carts to %s?' , 'bmc-woocom-multisession'),
						get_bloginfo('name')
					),
					__('Unsave Carts', 'bmc-woocom-multisession')
				);

			}

			print('</li>');

		}


		print('</ul></nav>');


		// end widget markup ...
		echo $args['after_widget'];

	}

}