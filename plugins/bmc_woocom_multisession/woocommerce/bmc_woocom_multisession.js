/**
 * @package BurningMoth \ WooCommerce \ Multisession
 * @since 1.3
 */

/**
 * Send formatted AJAX command / receive results.
 *
 * @param object $data
 * @param function $callback
 * @return void
 */
BMCWcMs.ajax = function (data, callback) {

	jQuery.post(
		this.ajaxurl,
		jQuery.extend(data, {
			'action': 'bmc_woocom_multisession',
			'nonce': this.nonce
		}),
		function(data, status, http) {
			if (data.success) callback(data);
		},
		'json'
	);

}

/**
 * Execute command from an onclick event.
 * @since 1.6
 *
 * @param string $command
 * @return bool
 */
BMCWcMs.command = function ( command, element ) {

	var id = element.target;

	switch ( command ) {

		// select a different cart ...
		case 'select':
			// current cart ? go to link ( cart page ) ...
			if ( jQuery(element).parent('li').hasClass('cart-current') ) {
				location = element.href;
			}

			// issue select command ...
			else {
				this.ajax({
					'id': id,
					'command': command
				}, function(data){
					location.replace( location.href.toString() );
				});
			}
			break;


		// update cart name ...
		case 'update':
			if ( name = prompt( this.localize.insert_prompt, jQuery('li.cart-select > a[target='+id+']').first().text() ) ) {

				this.ajax({
					'id': id,
					'name': name,
					'command': command
				}, function(data){
					if ( data.cart_name ) {
						jQuery('li.cart-select > a[target='+data.cart_id+']').text( data.cart_name );
					}
				});

			}
			break;


		// add new cart ...
		case 'insert':
			if ( name = prompt( this.localize.insert_prompt, this.localize.insert_prompt_value ) ) {

				this.ajax({
					'name': name,
					'command': command
				}, function(data){
					location.replace( location.href.toString() );
				});

			}
			break;

			// add new cart ...
		case 'insertpos':
		if ( name = prompt( this.localize.insert_prompt, this.localize.insert_prompt_value ) ) {

			this.ajax({
				'name': name,
				'command': command
			}, function(data){
				location.replace( location.href.toString() );
			});

		}
		break;


		// delete cart ...
		case 'delete':
			if ( confirm( this.localize.delete_confirm.replace(/%cart-name/g, jQuery('li.cart-select > a[target='+id+']').first().text()) ) ) {

				this.ajax({
					'id': id,
					'command': command
				}, function(data){
					jQuery('li.cart-select > a[target='+data.cart_id+']').parent().remove();
				});

			}
			break;


		/**
		 * Copy cart on click. Reload to take effect.
		 * @since 1.4
		 */
		case 'copy':
			this.ajax({
				'id': id,
				'command': command
			}, function(data){
				location.replace( location.href.toString() );
			});
			break;


		/**
		 * Clone cart items, addresses.
		 * @since 1.7
		 */
		case 'clone':
			if ( name = prompt( this.localize.insert_prompt, this.localize.insert_prompt_value ) ) {

				this.ajax({
					'name': name,
					'cart_id': id,
					'command': command
				}, function(data){
					location.replace( location.href.toString() );
				});

			}
			break;


		/**
		 * Sync cart session.
		 * @since 1.6
		 */
		case 'sync':
			// prompt for session key ...
			var key = jQuery.trim(prompt( this.localize.sync_prompt, this.session_key ));

			// key not empty and isn't current key ? continue ...
			if ( key && key != this.session_key ) {

				this.ajax({
					'command': command,
					'key': key,
					'confirmed': 0
				}, function(data){

					// session exists, confirm ...
					if ( data.confirm ) {
						if ( confirm( BMCWcMs.localize.sync_confirm.replace(/%session-name/g, key) ) ) {

							BMCWcMs.ajax({
								'command': command,
								'key': key,
								'confirmed': 1
							}, function(data){
								location.replace( location.href.toString() );
							});

						}
					}

					// session created, no need to confirm ...
					else {
						location.replace( location.href.toString() );
					}

				});

			}
			break;

		/**
		 * Unsync cart session.
		 * @since 1.6
		 */
		case 'unsync':

			if ( confirm( this.localize.unsync_confirm.replace(/%session-name/g, this.session_key) ) ) {

				this.ajax({
					'command': 'sync',
					'key': ''
				}, function(data){
					location.replace( location.href.toString() );
				});

			}

			break;


		/**
		 * Update order cart id and return to account orders list.
		 * @since 1.7
		 */
		case 'order_cart_update':

			this.ajax({
				'command': 'order_cart_update',
				'order_id': jQuery(element).data('order-id'),
				'cart_id': jQuery(element).val()
			}, function(data){
				location.replace( data.url );
			});

			break;


		/**
		 * Switch cart mode.
		 * @since 1.8
		 */
		case 'mode':

			this.ajax({
				'command': 'mode',
				'cart_id': id,
				'mode': ( jQuery(element).parent().hasClass('cart-mode-bin') ? 'bin' : 'cart' )
			}, function(data){

				jQuery('li.cart-select > a[target='+data.cart_id+']').parent().removeClass('cart-mode-'+( data.mode == 'bin' ? 'cart' : 'bin' )).addClass('cart-mode-'+data.mode);

			});

			break;

	}

	// suppress default ...
	return false;
}



/**
 * Bind UI events.
 */
jQuery(function($){

	/**
	 * Replace item loading indicators with items on event.
	 * @since 1.6
	 * @since 1.7
	 *	- Remove event on mouseover so it only fires once.
	 */
	$('.cart-items-loading').each(function(i){

		var c = $(this).closest('li.cart-select'),
			id = $('> a[target]', c).attr('target');

		c.mouseover(function(event){

			var l = c.find('.cart-items-loading');

			// replace loader with items html ...
			if ( l.length ) {

				BMCWcMs.ajax({
					'command': 'load',
					'id': id
				}, function(data){
					l.replaceWith( data.html );
				});

			}

			// remove this event so it doesn't fire again ...
			c.off(event);

		});

	});


	/**
	 * Expand cart on click.
	 * @since 1.4
	 */
	$('.bmc-woocom-multisession-widget a.cart-expand').click(function(event){

		event.preventDefault();

		var c = $(this).parent('li'),
			m = $('ul', c);

		if ( c.hasClass('expanded') ) {
			c.removeClass('expanded');
			m.slideUp(200);
		}

		else {
			c.addClass('expanded');
			m.slideDown(200, function(){ $(this).css('height', 'auto'); });
		}

	});


	/**
	 * Add global AJAX handler to augment WooCommerce Multiple Addresses ajax handler with additional fields made present in cart addresses.
	 *
	 * Note: trigger change() events on required fields to validate them in the WooCom UI.
	 *
	 * @since 1.7
	 */
	if ( BMCWcMs.woocom_multiaddr ) {
		$(document).ajaxSuccess(function(event, https, opts, data) {
			if (
				opts.url.indexOf('admin-ajax.php') != -1
				&& opts.data
				&& opts.data.indexOf('action=alt_change') != -1
			) {
				// update email ...
				if ( value = data.email ) {
					$('#billing_email').val(value).change();
				}

				// update phone number ...
				if ( value = data.phone ) {
					$('#billing_phone').val(value).change();
				}
			}
		});
	}


	/**
	 * Update links generated by filter_order_actions() to lists of carts to move the order to.
	 *
	 * @since 1.7
	 */
	var cartSwitches = $('.order-actions .cart-switch');
	if ( cartSwitches.length ) {

		BMCWcMs.ajax({'command':'order_cart_update_html'}, function(data, status, http){

			// carts to switch to, replace elements with lists ...
			if ( data.html ) {

				cartSwitches.each(function(i){

					var order_id = $(this).text();
					$(this).replaceWith( data.html.replace(/%order-id/, order_id) );

				});

			}

			// no carts to switch to ... remove elements ...
			else cartSwitches.remove();

		});

	}

});
