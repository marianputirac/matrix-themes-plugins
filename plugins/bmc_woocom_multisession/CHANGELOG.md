= 2018 Jan 14 / Version 1.12 / Build 64
* Fixed: Some WooCommerce *_user_meta() calls occur before WC_User override, accessing account user instead of cart user and resulting in carts being overwritten w/wrong items from single account user persistent cart.
* Updated: get_user_metadata filter callback now routes woocom persistent cart requests from account user to the cart user.
* Added: add_user_metadata and update_user_metadata filter callbacks to correctly route woocom persistent cart inserts and updates from the account user to the cart user.
* Added: Debugging sandbox.

= 2017 Nov 02 / Version 1.11.2 / Build 62
* Fixed: Items in Storage Bins weren't being retained after logging out/back in. Updated action_restore_bin() to NOT restore items when cart is emptied / session destroyed during log out because a session w/an empty cart would be created causing this issue.

= 2017 Oct 24 / Version 1.11.1 / Build 61
* Updated: Updated _woocommerce_persistent_cart_[blog id] meta key in various queries.
* Fixed: New carts were being created prepopulated w/items from the previous carts. WooCom moved some user-overridden WC_Cart methods to new WC_Cart_Session, now also added to user-override.

= 2017 Oct 17 / Version 1.11 / Build 60
* Updated: Deprecated WC()->cart->get_cart_url() updated to wc_get_cart_url() for WooComm 2.5+.
* Fixed: get_cart() was failing on, returning items from persistent cart user meta in WooComm 3.1+?. Now attempts to return items from WooComm session, updated persistent cart + blog id user meta before falling back to old style persistent cart user meta.
* Fixed: Page urls containing hashes # weren't refreshing on commands.
* Added: BMCWcMs.reload([url]) to resolve by reloading urls containing hashes.
* Fixed: Addresses not displaying under My Account in multisite mode (even though loading in the edit form).
* Updated: filter_get_user_meta() to user meta data required for bogus users to be verified in multisite mode and display cart specific addresses.
* Updated: WP_User::__construct() preemptively caches our cart user object so it validates when called via get_user_by().
* Added: is_user() checks if a cart/customer/user id is an actual user (has a record) or not. Replaces WP's get_user_by() that we've since had to compromise.
* Added: Global AJAX listener to intercept WooComm cart ajax data and update any open multicarts with it.
* Updated: cookie() now compresses data when JSON is over 1kb. Also, now returns cookie('name') and cookie('size') actions respectively.
* Added: max_carts option and setting limiting number of carts a user can have or 0 for no limit within 2kb cookie data size.
* Updated: option('can_add_cart') returns true if user is permitted to add another cart.
* Fixed: Cart and User addresses were being merged in the checkout.
* Updated: WP_User::__get('ID') now overrides WC()->customer->ID. Fixes the address merging issue by pointing to one consistent session.
* Added: update_cart_address() updates cart meta from checkout post data when it doesn't match the default user address data.
* Added: Cart 'time' parameter recording last selection time. uasort_cart_by_time() to sort carts by time if sort_carts option.
* Updated: uasort_cart_by_name() to sort carts alphabetically by name by default.
* Updated: Moved cart sorting from action_admin_bar() and BMC_WooCom_MultiSession_Widget::widget() to cookie() just before saving. Carts may appear out of order for some users until another is selected.

= 2017 Apr 19 / Version 1.10 / Build 55
* Removed: wp_get_current_user() pluggable function, was causing conflicts w/other plugins, notably WordFence.
* Added: class-wp-user.php defining WP_User class to modify and masquerade as $current_user object that returns dynamic ID property depending on caller context.
* Added: set_current_user action callback to modify the $current_user object with our WP_User class.
* Fixed: Changed orders cart switch classes/selectors, addressing ongoing changes in WooCommerce's markup and restoring functionality to this feature.
* Added: num_carts() to return number of carts.
* Updated: $wc_order->id deprecated in favor of $wp_order->get_id() for WooCommerce 3.0+
* Updated: after_setup_theme callback changed to plugins_loaded action callback, called earlier.
* Updated: Moved constant definitions to plugins_loaded action callback.
* Added: deprecate() function.
* Updated: README.html Header colors and background image w/new branding.

= 2017 Apr 08 / Version 1.9 / Build 49
* Added: plugin() function to retrieve plugin values.
* Deprecated: VERSION constant. Use plugin('Version') from now on.
* Deprecated: DOMAIN constant. Domain string added to translation functions as recommended by documentation. Use plugin('TextDomain') from now on for other purposes.
* Updated: cookie() function, can now explicitly set user_id.
* Added: wp_login action callback to merge any items from anonymous cart to current cart upon login.
* Fixed: All anonymous carts were being saved to session id 0 in sessions table because WooCom session handler override was mistakenly enabled for anonymous users. Moved WooCom session handler override hooks to after_setup_theme callback to be triggered before init hook if user is logged in.
* Added: PHP and WooCommerce version checks.

= 2017 Mar 21 / Version 1.8.3 / Build 47
* Updated: Added action and filter hook callback default argument values and checks in case another application calls a hook without passing additional arguments documented by WordPress, triggering an error.

= 2017 Jan 12 / Version 1.8.2 / Build 45
* Fixed: Sometimes get_user_meta() for all fields can fail to return empty array it's supposed to, additional check added.

= 2016 Dec 20 / Version 1.8.1 / Build 44
* Fixed: Remove wp_get_current_user() pluggable function override in in_admin() context. WP 4.7 introduced a bug that in which this override breaks the admin panel (but not the visitor side of the site) and it's unnecessary on the admin side anyway.

= 2016 Oct 16 / Version 1.8 / Build 43
* Added: Storage bin ("bin") vs. shopping cart ("cart", default) "mode" command for retaining (via "_cart_bin" user meta) vs. emptying cart items upon checkout respectively - action_save_bin(), action_restore_bin().
* Added: "sort_carts" option to sort the current cart to the top of the Carts menu or widget (or not).
* Added: Maintenance routines to run daily, deletes old carts data via action_cron().
* Updated: delete_cart() can now reassign deleted carts data to passed customer id (defaults to current customer id) or delete associated data.
* Updated: get_session() can now retrieve user meta sessions from passed user id (defaults to current user).
* Added: set_session() stores either user meta or transient sessions.
* Added: create_cart() updates from passed array or creates a cart data array.
* Updated: Moved "Settings" link from under plugin description to plugin title.
* Fixed: Implemented my-account.php template changes by WooCom they did w/o updating the template version.
* Added: myaccount/dashboard.php template override, removing edit-account link.
* Updated: moved documentation to README.html 2.0 template.

= 2016 Jun 27 / Version 1.7.1 / Build 35
* Updated: Removed "My Sites" multisite menu from admin toolbar for customer.
* Fixed: Notice level error. Check if post_type is set BEFORE checking if it's an array in filter_meta_sql().

= 2016 May 09 / Version 1.7 / Build 34
* Added: Integration for cart level addresses (+ email, phone) with WooCommerce Multiple Addresses plugin.
* Added: List of carts on orders listing and view UI to move the order to.
* Added: Update cart meta info (user id, modified time, etc.) when user meta is updated/added on cart_id.
* Added: Clone Cart command, creates new cart w/meta data of cloned cart.
* Updated: Current cart again appears at the top of the widget / toolbar carts menu.
* Updated: Migrated cart sessions from old ambiguous session keys to user account specific session keys.
* Updated: Changed 'sychronize' language to easier to understand 'save' and 'load' parlance.
* Updated: Sync command icons to indicate that the cart session is sync'd or not.
* Updated: Customer id can now be set by passing it to the customer_id() function.
* Fixed: action_init() now checks carts for faulty ids (possible in 1.6-, fixed in 1.6.1+), reassigns if necessary by cloning cart with an updated id and deleting the old, broken cart.
* Fixed: Load cart items mouseover event executes AJAX command multiple times. Now removes event on event so it only fires the one time required.
* Fixed: Shipping / billing information now saves to cart rather than user level on checkout.
* Fixed: Deleted carts could be resurrected when update merging sessions. Now newer session always overwrites the current session.

= 2016 Apr 30 / Version 1.6.1 / Build 26
* Fixed: Clicking current cart opened cart page in new window.
* Updated: generate_customer_id() now returns current timestamp. Numbers were growing unmanageably large, resulting in negative ids, broken carts!
* Notice: broken carts (w/negative ids) will generate new carts on selection, delete the broken carts to resolve.
* Fixed: Edit cart name command failed to update name in widget.
* Fixed: Critical! Delete cart command was reassigning ALL orders to current cart!
* Added: action_deactive() deactivation hook, attempts to correct wayward carts caused by critical 1.6 delete_cart bug.

= 2016 Apr 26 / Version 1.6 / Build 24
* Added: Stored session (sync) functionality, settings, UI.
* Added: Settings to display account features (downloads, orders) at the user or cart level.
* Added: Filters to display downloads at the cart level.
* Fixed: Filters to display orders at cart level in WC 2.5 no longer work in WooCom 2.6. Added new filter for WooCom 2.6+ to restore functionality.
* Updated: wp_get_current_user() override no longer uses depreciated get_currentuserinfo() function as of Wordpress 4.5. Implemented new functionality w/regression for older versions.
* Added: Account template overrides and settings to remove account access from customer.
* Fixed: Many action/filter callbacks triggered when user not logged in. Hooks moved to init hook.
* Updated: Admin Toolbar and Widget UI overhaul.
* Updated: Delete cart command now moves any associated orders with the deleted cart to the current cart.

= 2016 Mar 03 / Version 1.5 / Build 15
* Changed: action_deactivate() register_deactivation_hook callback changed to action_uninstall() register_uninstall_hook callback.
* Added: delete_cart() to remove user meta data WooCom associates with cart.
* Added: action_order_meta(), filter_order_query() to associate carts with orders and display just those orders on My Account page per active cart.
* Added: filter_page_title_append_cart_name() to distinguish certain WooCom pages by appending the active cart name to the title when user has more than one cart.
* Updated: wp_get_current_user() override optimized to call stack trace only once per call, compiles regular expressions, added additional address related functions/templates to intercept to associate shipping/billing info per cart rather than per user.

= 2016 Feb 29 / Version 1.4 / Build 13
* Added: option(), option_name(), option_default(), filter_woocom_settings() to integrate plugin options with WooCommerce settings API.
* Added: filter_plugin_links() to add Documentation and Settings links to plugins record.
* Added: is_customer() to indicate when a basic customer/subscriber user is logged in.
* Added: get_cart() to return array of cart items from persistent cart.
* Added: 'copy' command/icon to copy items from one cart to the current cart.
* Update: Confirmation dialog preceding the deletion of a cart.
* Update: Deleting a cart removes the persistent cart data from user meta.
* Added: bmc_woocom_multisession-admin.css styles for admin and customize interfaces.
* Added: action_admin_assets() callback for admin and customize control enqueue scripts hooks.
* Added: class-bmc-woocom-multisession-widget.php / BMC_WooCom_Multisession_Widget extension of WP_Widget class, action_widgets() hook callback to register widget.
* Update: bmc_woocom_multisession.[css|js] files to incorporate widget.
* Update: README.html with images, configuration instructions.

= 2016 Feb 09 / Version 1.3.1 / Build 10
* Added|Fixed: filter_admin_bar() reverses WooCom, displays admin bar for all logged in users.
* Updated: action_admin_bar() adds WooCommerce "My Account" link before "Carts", removes all other items from admin bar (except search) for users who cannot edit_posts (subscribers|customers).
* Removed: WooCommerce version 2.5+ restriction in admin_init().
* Fixed: Carts menu is formatted to and displays in small-screen mode properly.
* Fixed: Retrieve persistent carts from usermeta table rather than woocommerce_sessions where they expire.
* Update: Set cookie expire lifetime to one year.
* Added: action_deactivation() plugin deactivation hook, removes all persistent carts from usermeta table that don't correspond with actual users.

= 2016 Feb 07 / Version 1.3 / Build 9
* Fixed: cookie() returns false, no longer throws warning after headers have been sent.
* Removed: action_delete() hook callback. No longer explicitely deleting the session cookie.
* Added: action_assets() hook call back to enqueue bmc_woocom_multisession.css, bmc_woocom_multisession.js and localization.
* Added: action_admin_bar() hook callback to add multi carts UI to Wordpress admin bar.
* Added: format_cart_name() to return valid cart name.
* Added: get_carts() to return array of carts.
* Added: action_ajax() hook callback to process ajax commands from UI.
* Added: generate_customer_id() to separate id generation from customer_id().
* Added: action_init_admin() hook callback to check for WooCommerce, deactivate plugin if false.
* Added: action_init() hook callback to hook UI features.
* Update: README.html to include screenshots, additional considerations, requirements and features.

= 2016 Feb 04 / Version 1.2 / Build 6
* Update: Plugin URI points to Envato page.
* Update: plugin description reflects that users are no longer restricted by ip address.
* Update: README.html reflects that users are no longer restricted by ip address.
* Fixed: customer_id() can now generate ids from IPv4 or IPv6 addresses.
* Added: cookie() to manage session cookie now storing the generated customer id.
* Added: action_delete(), action_update() hook callbacks deleting or updating the session cookie respectively.

= 2016 Jan 23 / Version 1.1 / Build 4
* Added WP_Session_Handler::get_session_cookie() override to intercept/ensure ip-based customer id from session cookie alleviating the need to delete cookies before this plugin will work.
* Added README.html documentation file ported from Evanto's documentation file.

= 2015 Dec 28 / Version 1.0 / Build 1
* Initial version.