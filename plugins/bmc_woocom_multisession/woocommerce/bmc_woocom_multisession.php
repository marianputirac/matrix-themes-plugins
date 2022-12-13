<?php
/**
 * @package BurningMoth \ WooCommerce \ Multisession
 * @version 1.8.2
 * @author Tarraccas Obremski <tarraccas@burningmoth.com>
 * @copyright 2015-2016 Burning Moth Creations, Inc.
 */

/*-- INFO --
Plugin Name: WooCommerce Multiple Carts Per User
Description: Modifies WooCommerce's one user account to one shopping cart paradigm to provide for different people logging into the same user account to have multiple shopping carts, shipping & billing addresses and order histories separate from each other.
Version: 1.8.2
Author: Burning Moth Creations
Author URI: http://www.burningmoth.com/
License: Envato Tools License
License URI: http://codecanyon.net/licenses/terms/tools
Plugin URI: http://codecanyon.net/item/woocommerce-multiple-carts-per-user/14274432
Text Domain: bmc-woocom-multisession
--*/


/*-- FINALIZING CHECKLIST --
1. Update all version numbers here and in README.html
2. Update changelog here and in README.html
3. Remove all calls to console() and other debugging tools.
4. Turn off/on all ancillary plugins, make sure nothing breaks.
*/


/*-- KNOWN ISSUES --
* Bug (maybe?): Payment methods might be at the user level in WooCom 2.6+
* Bug: Carts are keyed to the latest session that uses them though they may belong to more than one session.
--*/


/*-- CHANGELOG --
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

--*/

### PLUGIN NAMESPACE ###
namespace BMC\WooCommerce\MultiSession {

    /**
     * Current plugin version used w/enqueue_scripts.
     * @since 1.3
     * @var string VERSION
     */
    const VERSION = '1.8.2';


    /**
     * Used to prefix option names.
     * @since 1.4
     * @var string OPTION
     */
    const OPTION = 'bmc_woocom_multisession';


    /**
     * Text domain.
     * @since 1.4
     * @var string DOMAIN
     */
    const DOMAIN = 'bmc-woocom-multisession';


    /**
     * Manage session cookie.
     *
     * @param mixed $action
     * @return mixed
     * @since 1.2
     * @since 1.7
     *    - Updated cookie name with user id, migrating old cookie.
     *    - Added plugin version, user id values to session.
     * @since 1.8
     *  - namespace\cookie('user_id') action returns user id.
     *
     * Return array of values.
     * @example namespace\cookie();
     *
     * Return a single value by key or NULL if doesn't exist.
     * @example namespace\cookie('key');
     *
     * Update cookie expire time.
     * @example namespace\cookie(TRUE);
     *
     * Update a single value.
     * @example namespace\cookie('key', 'value');
     *
     * Update an array of values.
     * @example namespace\cookie(array('key' => 'value'));
     *
     * Delete cookie.
     * @example namespace\cookie(FALSE);
     *
     */
    function cookie($action = null)
    {

        // current ACTUAL user id
        // @note get_current_user_id() can return bogus id when WC functions are in the backtrace when this function is called! Setting user id early appears to fix this.
        static $user_id;
        if (!isset($user_id)) $user_id = get_current_user_id();

        // return user id ...
        if ($action == 'user_id') return $user_id;

        // cookie name 1.7+
        $name = 'bmc_woocom_multisession_' . $user_id . '_' . COOKIEHASH;

        // old cookie name 1.6.1-
        $old_name = 'bmc_woocom_multisession_' . COOKIEHASH;

        // cookie values ...
        static $values;
        if (!isset($values)) {

            $values = array();

            // updated 1.7+ cookie not set ? set from old cookie if one exists ...
            if (isset($_COOKIE[$old_name]) && !isset($_COOKIE[$name])) {
                $_COOKIE[$name] = $_COOKIE[$old_name];
            }

            // derive session values from cookie ...
            if (isset($_COOKIE[$name])) {
                $values = json_decode(base64_decode($_COOKIE[$name]), true);
            }

            // ensure array ...
            if (!is_array($values)) {
                $values = array();
            }

            // required defaults ...
            $values = array_merge(array(
                'customer_id' => 0,
                'key' => '',
                'modified' => 0,
                'carts' => array()
            ), $values);

            // update with stored session if newer ...
            if (
                // retrieve stored session ...
                ($session = namespace\get_session($values['key'], $user_id))

                // stored session is newer than current session ...
                && $values['modified'] < $session['modified']
            ) {
                $values = $session;
            }

        }

        // null? return array of values ...
        if (is_null($action)) {
            return $values;
        } // array? update values ...
        elseif (is_array($action)) {
            $values = array_merge($values, $action);
        } // string? read or write value ...
        elseif (is_string($action)) {

            // two arguments? update value with second argument ...
            if (func_num_args() > 1) {

                $value = func_get_arg(1);

                // delete if value is null ...
                if (is_null($value)) unset($values[$action]);

                // set value to anything but null ...
                else $values[$action] = $value;
            } // one argument? return value or null if non-existent ...
            else {
                return array_key_exists($action, $values) ? $values[$action] : null;
            }

        }

        // can't set the cookie ? return false
        if (headers_sent()) return false;

        // delete cookie and session if action is explicit false ...
        if ($action === false) {

            $expires = time() - YEAR_IN_SECONDS;

            // delete session from user meta ...
            if (namespace\option('sync_per_user')) delete_user_meta($user_id, '_woocom_multisession');

            // delete session from transient ...
            if ($values['key']) delete_transient(namespace\session_key($values['key']));

        } // set expires to a year and save session ...
        else {

            $values['modified'] = time();
            $values['version'] = namespace\VERSION;
            $values['user_id'] = $user_id;

            $expires = time() + YEAR_IN_SECONDS;

            namespace\set_session($values, $values['key'], $user_id);

        }

        // delete old cookie if one exists ...
        if (isset($_COOKIE[$old_name])) {
            unset($_COOKIE[$old_name]);
            setcookie($old_name, '', -1, '/');
        }

        // set session to cookie ...
        return setcookie($name, base64_encode(json_encode($values)), $expires, '/');

    }


    /**
     * Returns session from transient or user meta.
     *
     * @param string $key
     * @param integer $user_id
     * @return bool|array Session array on success, false otherwise.
     * @since 1.6
     * @since 1.7
     *    - return false if empty key is passed.
     *    - migrate 1.6.1- transients to 1.7+ transients containing user id.
     * @since 1.8
     *    - updated $key argument to optional.
     *    - added $user_id optional argument (passed to session_key()).
     *    - return session from user meta when sync_per_user = true
     *
     */
    function get_session($key = '', $user_id = null)
    {

        // return session from user meta ...
        if (namespace\option('sync_per_user')) {

            if (!$user_id) {
                $user_id = namespace\cookie('user_id');
            }

            return get_user_meta($user_id, '_woocom_multisession', true);

        }

        // no key, return false ...
        if (empty($key)) return false;

        // migrate 1.6.1- transient to 1.7+ transient ...
        $old_key = 'carts' . md5(trim((string)$key));
        if (
            ($session = get_transient($old_key))
            && set_transient(namespace\session_key($key, $user_id), $session, YEAR_IN_SECONDS)
        ) {
            delete_transient($old_key);
            return $session;
        }

        return get_transient(namespace\session_key($key, $user_id));
    }


    /**
     * Update a saved multicart session.
     *
     * @param array $session
     *    - assoc. array created by cookie()
     * @param string $key (optional)
     *    - session key when sync'ing via transients
     * @param integer $user_id
     *    - user id the session belongs to
     * @return bool
     * @since 1.8
     *
     */
    function set_session($session, $key = '', $user_id = null)
    {

        // empty session ? return false ...
        if (empty($session) || !is_array($session)) return false;

        // update modified time ...
        $session['modified'] = time();

        // save session to user meta ...
        if (namespace\option('sync_per_user')) {

            // require user id ...
            if (!$user_id) {
                $user_id = namespace\cookie('user_id');
            }

            return update_user_meta($user_id, '_woocom_multisession', $session);

        } // save session to transient ...
        elseif ($key) {
            return set_transient(namespace\session_key($key, $user_id), $session, YEAR_IN_SECONDS);
        }

        // fail by default ...
        return false;
    }


    /**
     * Returns key suitable for setting transients.
     *
     * @return string
     * @since 1.7
     *    - added user_id to the key mix.
     * @since 1.8
     *    - added $user_id optional argument.
     *
     * @since 1.6
     */
    function session_key($key, $user_id = null)
    {
        return 'carts' . md5(trim((string)$key . (empty($user_id) ? namespace\cookie('user_id') : $user_id)));
    }


    /**
     * Generate customer id from time.
     *
     * @return integer
     *    - customer id (also cart / session id)
     * @since 1.6.1
     *    - numbers are getting too large to manage, return unix timestamp, should be more than sufficient
     *    - must be less than PHP_INT_MAX
     *
     * @since 1.3
     */
    function generate_customer_id()
    {
        return time();
    }


    /**
     * Generate bogus customer id from user ip address.
     * Used to force unique cart/user info for several people using the same login to place orders.
     *
     * Note: This id will be recorded in the user meta table (updated to custom sessions table as of WooCommerce 2.5.x). In the unlikely event that it corresponds with an actual user id, it will set the cart for that user. However, it is assumed that by opting to using this plugin that the Wordpress installation has few registered users.
     *
     * @return integer
     * @since 1.7
     *    - Added $id parameter to update customer id.
     *
     */
    function customer_id($id = null)
    {

        static $customer_id;

        // id passed ? set here and update cookie ...
        if ($id) {
            $customer_id = absint($id);
            namespace\cookie('customer_id', $customer_id);
        } // not set ? set now ...
        elseif (!isset($customer_id)) {

            // get bogus id ...
            if (is_user_logged_in()) {

                $customer_id = namespace\cookie('customer_id');

                // no valid customer id retrieved ...
                if (
                    !is_numeric($customer_id)
                    || $customer_id < 1
                    || $customer_id >= PHP_INT_MAX
                ) {

                    /**
                     * @fix Reassign broken carts new, acceptable ids.
                     *
                     * Version 1.6- generate_customer_id() could create carts with impossibly huge integer ids that PHP would cast as floats and return the upper limit set by PHP_INT_MAX or a negative number when converted to integer which causes broken, unpredictable, terrible behavior!!!
                     *
                     * @since 1.7
                     * @since 1.8
                     *    - moved this fix functionality from action_init()
                     */
                    $carts = namespace\get_carts();
                    if (isset($carts[$customer_id])) {

                        // old cart id ...
                        $old_cart_id = $customer_id;

                        // generate new cart id ...
                        $new_cart_id = namespace\generate_customer_id();

                        // add new cart from old cart ...
                        $carts[$new_cart_id] = namespace\create_cart($carts[$old_cart_id]);

                        // delete old cart / user meta ...
                        unset($carts[$old_cart_id]);
                        namespace\delete_cart($old_cart_id, $new_cart_id);

                        // update carts ...
                        namespace\cookie('carts', $carts);

                        // set customer id from new cart id ...
                        $customer_id = $new_cart_id;

                    } // just generate new customer id ...
                    else {
                        $customer_id = namespace\generate_customer_id();
                    }

                    // store the customer id ...
                    namespace\cookie('customer_id', $customer_id);
                }

                $customer_id = absint($customer_id);

            } // shouldn't be called if user isn't logged in, but if it is, return zero ...
            else {
                $customer_id = 0;
            }

        }

        return $customer_id;
    }


    /**
     * Return true if user is logged in as a basic customer|subscriber.
     *
     * @return bool
     * @since 1.4
     *
     */
    function is_customer()
    {
        return (is_user_logged_in() && !current_user_can('edit_posts'));
    }


    /**
     * Return a valid cart name.
     *
     * @return string Valid cart name.
     * @since 1.8
     *    - updated default cart name with appended cart #
     *
     * @since 1.3
     */
    function format_cart_name($name)
    {
        $name = mb_substr(trim(wp_strip_all_tags(stripslashes($name), true)), 0, 24);
        if (empty($name)) {
            $name = __('Order', 'woocommerce') . ' #' . (count(namespace\cookie('carts')) + 1);
        }
        return $name;
    }


    /**
     * Return the name of the current cart.
     *
     * @return string Current cart name.
     * @since 1.6
     *
     */
    function current_cart_name()
    {
        $cart = namespace\current_cart();
        return format_cart_name($cart['name']);
    }


    /**
     * Return current cart info array.
     *
     * @return array
     * @since 1.8
     *
     */
    function current_cart()
    {

        // retrieve carts ...
        $carts = namespace\get_carts();

        // return current cart if exists ...
        if (
            array_key_exists(namespace\customer_id(), $carts)
            && ($cart = $carts[namespace\customer_id()])
            && is_array($cart)
        ) {
            return $cart;
        }

        // return new cart if cart does not exist ...
        return namespace\create_cart();
    }


    /**
     * Return multi-dim array of carts.
     *
     * @return array
     * @since 1.3
     *
     */
    function get_carts()
    {

        // retrieve carts from cookie/session ...
        $carts = namespace\cookie('carts');

        // ensure carts array ...
        if (!is_array($carts)) {
            $carts = array();
        }

        // ensure a cart exists for current id ...
        if (!isset($carts[namespace\customer_id()])) {
            $carts[namespace\customer_id()] = namespace\create_cart();
        }

        return $carts;

    }


    /**
     * uksort() callback to move the current cart to the top of the cart list and sort the rest from newest to oldest.
     *
     * @since 1.7
     */
    function uksort_carts($a, $b)
    {
        if ($a == namespace\customer_id()) return -1;
        elseif ($b == namespace\customer_id()) return 1;
        elseif ($a == $b) return 0;
        return ($a > $b) ? -1 : 1;
    }


    /**
     * Return items from a persistent cart.
     *
     * @param integer|null $cart_id
     *    - Bogus customer id for the cart desired.
     * @return array
     * @since 1.4
     *
     */
    function get_cart($cart_id = null)
    {

        // default to current customer id ...
        if (empty($cart_id)) $cart_id = namespace\customer_id();

        // get cart items ...
        if (
            ($session = get_user_meta($cart_id, '_woocommerce_persistent_cart', true))
            && isset($session['cart'])
        ) {
            return $items = maybe_unserialize($session['cart']);    // @note woocom double serializes this?!
        }

        // return empty array ...
        return array();
    }


    /**
     * Delete all bogus user meta including persistent cart.
     *
     * @param integer $cart_id
     *    - Bogus user id.
     * @param integer|bool $customer_id (optional)
     *    - customer id to move any associated data to
     *    - null (default) resolves to current customer id
     *    - integer resolves to another customer id
     *    - false resolves to no customer id ( deletes all associated data)
     * @return bool
     * @since 1.5
     * @since 1.6.1
     *    - moves orders from deleted cart to current cart ...
     * @since 1.8
     *    - added optional $customer_id parameter
     *
     */
    function delete_cart($cart_id, $customer_id = null)
    {

        // if the cart is associated with an actual user, return false ...
        if (get_user_by('id', $cart_id)) return false;

        global $wpdb;

        // no customer id passed ? get current customer id ...
        if (is_null($customer_id)) $customer_id = namespace\customer_id();

        // has a customer id ? move associated data to it ...
        if ($customer_id) {

            // move any associated orders to another cart ...
            $wpdb->update(
                $wpdb->prefix . 'postmeta',
                array('meta_value' => $customer_id),
                array('meta_key' => '_cart_id', 'meta_value' => $cart_id),
                array('%d'),
                array('%s', '%d')
            );

            // copy bogus user meta data to new bogus user if not already set ...
            if ($meta = get_user_meta($cart_id)) {
                foreach ($meta as $meta_key => $meta_value) {
                    add_user_meta($customer_id, $meta_key, maybe_unserialize(current($meta_value)), true);
                }
            }

        } // no customer id ? simply delete associated data ...
        else {

            $wpdb->delete(
                $wpdb->prefix . 'postmeta',
                array('meta_key' => '_cart_id', 'meta_value' => $cart_id),
                array('%s', '%d')
            );

        }

        // cart is bogus user, remove user meta, return true ...
        return $wpdb->delete(
            $wpdb->prefix . 'usermeta',
            array('user_id' => $cart_id),
            array('%d')
        );
    }


    /**
     * Return new or updated cart parameters array.
     *
     * @param array $params (optional)
     *    - array of cart parameters.
     * @since 1.8
     *
     */
    function create_cart($params = array())
    {

        // default cart parameters ...
        $cart = array(
            'name' => '',
            'mode' => 'cart'
        );

        // additional parameters ? merge them ...
        if ($params) {

            // ensure array ...
            $params = (array)$params;

            // remove any parameters for which there are no defaults ...
            foreach (array_keys($params) as $key) {
                if (!isset($cart[$key])) {
                    unset($params[$key]);
                }
            }

            // add parameters ...
            $cart = array_merge($cart, (array)$params);
        }

        // format valid name ...
        $cart['name'] = namespace\format_cart_name($cart['name']);

        // return cart ...
        return $cart;
    }


    /**
     * Formats an option name with plugin prefix to pass as form names and to [get|add|delete]_option() functions.
     *
     * @param string $key Option name suffix.
     * @return string Prefixed option name suitable for Wordpress options table.
     * @since 1.4
     *
     */
    function option_name($key)
    {
        return namespace\OPTION . '_' . $key;
    }


    /**
     * Default value (or values) for options.
     *
     * @param null|string $key Option name suffix.
     * @return mixed Array of defaults if no key is passed, default value if it exists or null of it does not exist.
     * @since 1.4
     * @since 1.8
     *    - added sort_carts option.
     *
     */
    function option_default($key = null)
    {

        // woocom uses yes|no for true|false, 1|0
        $defaults = array(
            // enable admin toolbar for customers ...
            'customer_toolbar' => 'yes',

            // enable carts menu on admin bar ...
            'carts_menu' => 'yes',

            // sort current cart to the top ...
            'sort_carts' => 'yes',

            // auto sync per user account ? if (false, default), users are permitted to manually sync their carts to pass phrases ...
            'sync_per_user' => 'no',

            // show account features per user, rather than per cart ? affects things like orders, downloads, payments, etc.
            'account_per_user' => 'no',

            // allow customer to update user account information ?
            'account_access' => 'yes',

            // delete all settings, carts data when uninstalling the plugin ?
            'uninstall_data' => 'no',
        );

        // no key? return array of defaults ...
        if (is_null($key)) return $defaults;

        // return a default value or null ...
        return array_key_exists($key, $defaults) ? $defaults[$key] : null;

    }


    /**
     * Return value for an option or null if no value exists.
     *
     * @param string $key Option name suffix.
     *
     * @return mixed
     * @since 1.4
     * @since 1.7
     *    - added switch for keys, return true for 'woocom_multiaddr' if plugin installed.
     *
     */
    function option($key)
    {

        switch ($key) {
            // true if WooCommerce Multiple Addresses plugin is installed.
            case 'woocom_multiaddr':
                $value = class_exists('WC_Multiple_addresses');
                break;

            // get value from options ...
            default:
                $value = get_option(namespace\option_name($key), namespace\option_default($key));
                break;
        }

        // convert WooCommerce "bool" values to proper bool values ...
        switch ($value) {
            case 'yes':
            case 'true':
            case 'on':
                $value = true;
                break;

            case 'no':
            case 'false':
            case 'off':
                $value = false;
                break;
        }

        return $value;
    }


    /**
     * Flattens backtrace stack.
     *
     * @param null|string $regexp Regular expression.
     * @return array|integer Returns flattened stack trace if no regular expression is passed, number of matches otherwise.
     */
    function caller($regexp = null)
    {

        // get backtrace stack - IGNORE ARGS to be more efficient (we need all we can get here!)
        $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);

        // remove 'caller' from the stack ...
        $trace = array_slice($trace, 1);

        // flatten the stack into a single dimension array ...
        $trace = array_map(function ($data) {

            // return include path ...
            if (in_array($data['function'], array('require', 'require_once', 'include', 'include_once'))) {
                return $data['args'][0];
            }

            $value = '';

            // prefix with class name ...
            if (array_key_exists('class', $data)) {
                $value = $data['class'] . '::';
            }

            // add function name ...
            $value .= $data['function'];

            // return value ...
            return $value;

        }, $trace);

        // return full stack (array) if no search is passed ...
        if (is_null($regexp)) return $trace;

        // return number of matches ...
        $trace = preg_grep(sprintf('/%s/i', $regexp), $trace);
        return count($trace);

    }


    /**
     * Array Merge Recursive (correctly!!!)
     * Merges multidim arrays into one.
     *
     * @param array, ... $arr
     * @return array
     * @since 1.6
     *
     */
    function array_merge_recursive($arr = array())
    {

        $arrs = func_get_args();
        $merged = (array)array_shift($arrs);

        while ($arrs) {

            $arr = (array)array_shift($arrs);
            if (empty($arr)) continue;

            while (list($key, $value) = each($arr)) {

                // numeric? append ...
                /*
                if ( is_int( $key ) ) {
                    $merged[] = $value;
                }
                */

                // matching keys? merge ...
                if (array_key_exists($key, $merged)) {
                    $merged[$key] = (is_array($merged[$key]) && is_array($value) ? namespace\array_merge_recursive($merged[$key], $value) : $value);
                } // append ...
                else {
                    $merged[$key] = $value;
                }

            }

        }

        return $merged;
    }


    /**
     * Compare version to WooCom version.
     *
     * @param string|float|int $version Version to compare against.
     * @param string $op Comparison operator. @see version_compare()
     * @return bool
     * @since 1.6
     *
     */
    function wc_version($version, $op = '=')
    {
        return version_compare(WC_VERSION, (string)$version, $op);
    }


    /**
     * Compare returned array on keys rather than values.
     *
     * @param string $regexp
     * @param array $arr
     * @param int $flags
     *    - constants consistent with preg_grep()
     * @return array
     * @since 1.7
     *
     */
    function preg_grep_keys($regexp, $arr, $flags = 0)
    {
        return array_intersect_key($arr, array_flip(preg_grep($regexp, array_keys($arr), $flags)));
    }


### HOOKS ###

    /**
     * Cleanup upon uninstallation.
     * Best to leave things as good if not better than we found them.
     *
     * @return void
     * @since 1.5
     * - register_deactivation_hook to register_uninstall_hook
     * - action_deactivate to action_uninstall
     *
     * @since 1.6
     * - run only if uninstall_data option is checked
     * - remove _cart_id keys from orders (post meta)
     * - remove _woocom_multisession keys from user meta
     *
     * @since 1.8
     *    - moved a lot of delete associated data functionality to delete_cart()
     *
     * @since 1.3.1
     *
     */
    register_uninstall_hook(__FILE__, __NAMESPACE__ . '\action_uninstall');
    function action_uninstall()
    {

        if (namespace\option('uninstall_data')) {

            global $wpdb;

            // remove all the bogus carts from the usermeta table that don't correspond with actual users ...
            $cart_ids = $wpdb->get_col("
				SELECT
					_umeta.user_id
				FROM
					{$wpdb->usermeta} _umeta
					LEFT JOIN {$wpdb->users} _u ON (_u.ID = _umeta.user_id)
				WHERE
					/* has a cart set by woocom */
					_umeta.meta_key = '_woocommerce_persistent_cart'

					/* bogus user! no user record associated with user meta */
					AND ISNULL(_u.ID)
			");
            foreach ($cart_ids as $cart_id) {
                namespace\delete_cart($cart_id, false);
            }

            // remove options ...
            $keys = array_keys(namespace\option_default());
            foreach ($keys as $key) {
                delete_option(namespace\option_name($key));
            }

        }

    }


    /**
     * Trigger on plugin deactivation.
     *
     * @since 1.6.1
     * @since 1.8
     * - Clear cron event hook.
     * - removed 1.6.1 cart fixes for delete_cart() critical error - should be fixed by most if not all customers!
     */
    register_deactivation_hook(__FILE__, __NAMESPACE__ . '\action_deactivate');
    function action_deactivate()
    {
        // clear cron event ...
        wp_clear_scheduled_hook(__NAMESPACE__ . '\cron');
    }


    /**
     * Plugin activation.
     *
     * @since 1.8
     */
    register_activation_hook(__FILE__, __NAMESPACE__ . '\action_activation');
    function action_activation()
    {
        // add event hook - cron action added in action_init()
        if (!wp_next_scheduled(__NAMESPACE__ . '\cron')) {
            wp_schedule_event(time(), 'daily', __NAMESPACE__ . '\cron');
        }
    }


    /**
     * Deactivate this plugin on the admin side if WooCommerce plugin isn't activated.
     * Note: must be admin_init hook for deactivate_plugins() to exist!
     *
     * @return void
     * @since 1.3
     *
     */
    add_action('admin_init', __NAMESPACE__ . '\action_init_admin', 99);
    function action_init_admin()
    {

        if (!function_exists('WC')) {
            deactivate_plugins(plugin_basename(__FILE__));
            return;
        }

        // add plugin links ...
        add_filter('plugin_action_links_' . plugin_basename(__FILE__), __NAMESPACE__ . '\filter_plugin_action_links');
        add_filter('plugin_row_meta', __NAMESPACE__ . '\filter_plugin_links', 10, 2);

        // integrate settings w/WooCommerce ...
        add_filter('woocommerce_get_settings_general', __NAMESPACE__ . '\filter_woocom_settings');

        // add admin assets ...
        add_action('customize_controls_enqueue_scripts', __NAMESPACE__ . '\action_admin_assets');

    }


    /**
     * Check environment before hooking UI callbacks.
     *
     * @return void
     * @since 1.3
     *
     */
    add_action('init', __NAMESPACE__ . '\action_init', 99);
    function action_init()
    {

        // exit now if ...
        if (
            // not logged in ?
            !is_user_logged_in()

            // no woocom !
            || !function_exists('WC')
        ) return;

        // AJAX hook ...
        add_action('wp_ajax_bmc_woocom_multisession', __NAMESPACE__ . '\action_ajax');

        // cron hook ...
        add_action(__NAMESPACE__ . '\cron', __NAMESPACE__ . '\action_cron');

        // update current cart meta info when user meta is updated ...
        add_action('added_user_meta', __NAMESPACE__ . '\action_updated_user_meta', 11, 4);
        add_action('updated_user_meta', __NAMESPACE__ . '\action_updated_user_meta', 11, 4);

        // integrate with WooCommerce Multiple Addresses plugin.
        if (namespace\option('woocom_multiaddr')) {
            add_filter('get_user_metadata', __NAMESPACE__ . '\filter_get_user_meta', 11, 4);
        }

        // no UI on admin screen ...
        if (is_admin()) return;

        // UI hooks ...
        add_action('wp_enqueue_scripts', __NAMESPACE__ . '\action_assets');

        // update title on certain woocom pages to identify them with the current cart name ...
        add_filter('the_title', __NAMESPACE__ . '\filter_page_title_append_cart_name', 99, 2);

        // update session (and store items when in 'bin' mode) when cart is updated ...
        add_action('woocommerce_cart_updated', __NAMESPACE__ . '\action_update');

        // tag orders with the cart id they're checked out with (for query purposes) ...
        add_action('woocommerce_checkout_update_order_meta', __NAMESPACE__ . '\action_order_meta', 10, 2);

        // update bogus user (cart) meta ...
        add_filter('woocommerce_checkout_update_customer_data', __NAMESPACE__ . '\filter_checkout_cart_meta', 99, 2);

        // save and restore carts in storage bin mode before and after cart is emptied ...
        //add_action('woocommerce_before_checkout_form', __NAMESPACE__.'\action_save_bin'); // called by other actions now ...
        add_action('woocommerce_cart_emptied', __NAMESPACE__ . '\action_restore_bin');

        // display account features (orders) per cart (rather than per user) ...
        if (
        !namespace\option('account_per_user')
        ) {

            // modifies the WP_Query values ...
            if (namespace\wc_version('2.6', '<')) {
                add_filter('woocommerce_my_account_my_orders_query', __NAMESPACE__ . '\filter_order_query', 11, 1);
            } // above (depreciated) filter functionality has been silently changed in WooCom 2.6, modify the query at the WP core level to circumvent ...
            else {
                add_filter('get_meta_sql', __NAMESPACE__ . '\filter_meta_sql', 11, 6);
            }

            // filter results for downloads ...
            add_filter('woocommerce_permission_list', __NAMESPACE__ . '\filter_download_results', 99, 1);

            // add cart switching control to orders list ...
            add_filter('woocommerce_my_account_my_orders_actions', __NAMESPACE__ . '\filter_order_actions', 11, 2);

            // add cart switching control to order view ...
            add_action('woocommerce_view_order', __NAMESPACE__ . '\action_order_view', 11, 1);

        }

        // show adminbar for customers ?
        if (
            namespace\is_customer()
            && namespace\option('customer_toolbar')
        ) add_filter('show_admin_bar', __NAMESPACE__ . '\filter_admin_bar', time());

        // modify admin bar ...
        if (
            (namespace\is_customer() && namespace\option('customer_toolbar'))
            || namespace\option('carts_menu')
        ) add_action('admin_bar_menu', __NAMESPACE__ . '\action_admin_bar', 99, 1);

        // restrict account access ...
        if (
        !namespace\option('account_access')
        ) {
            // call these filters late to catch what themes or other plugins might modify ...
            add_filter('wc_get_template', __NAMESPACE__ . '\filter_wc_template', 99, 3);
            add_filter('woocommerce_account_menu_items', __NAMESPACE__ . '\filter_account_menu_items', 99, 1);
        }

    }


    /**
     * Load plugin widgets.
     *
     * @return void
     * @since 1.4
     *
     */
    add_action('widgets_init', __NAMESPACE__ . '\action_widgets');
    function action_widgets()
    {
        // load multicart widget ...
        include_once 'class-bmc-woocom-multisession-widget.php';
        register_widget(__NAMESPACE__ . '\BMC_WooCom_MultiSession_Widget');
    }


    /**
     * Load the namespaced WC_Session_Handler class override.
     *
     * @return void
     */
    add_action('before_woocommerce_init', __NAMESPACE__ . '\action_before_woocom_init');
    function action_before_woocom_init()
    {
        include_once 'class-wc-session-handler.php';
    }


    /**
     * Return our customized namespaced session handler class name.
     *
     * @return WC_Session_Handler
     */
    add_filter('woocommerce_session_handler', __NAMESPACE__ . '\filter_woocom_session_handler');
    function filter_woocom_session_handler($session_class)
    {
        $session_override = __NAMESPACE__ . '\WC_Session_Handler';
        if (class_exists($session_override)) return $session_override;
        return $session_class;
    }


    /**
     * Cron hook. Run maintenance.
     *
     * @since 1.8
     */
    function action_cron()
    {

        global $wpdb;

        // expire carts ...
        $results = $wpdb->get_results("
			SELECT
				/* cart id */
				_umeta.user_id AS cart_id,

				/* user id */
				_umeta3.meta_value AS user_id,

				/* session key */
				_umeta4.meta_value AS session_key

			FROM
				/* contains woocom shopping cart */
				{$wpdb->usermeta} _umeta

				/* if a user table connects then it's NOT one of our bogus users / carts! */
				LEFT JOIN {$wpdb->users} _u ON ( _umeta.user_id = _u.ID )

				/* contains cart modified timestamp */
				LEFT JOIN {$wpdb->usermeta} _umeta2 ON ( _umeta2.user_id = _umeta.user_id AND _umeta2.meta_key = '_cart_modified' )

				/* contains user id */
				LEFT JOIN {$wpdb->usermeta} _umeta3 ON ( _umeta3.user_id = _umeta.user_id AND _umeta3.meta_key = '_cart_user_id' )

				/* contains session key */
				LEFT JOIN {$wpdb->usermeta} _umeta4 ON ( _umeta4.user_id = _umeta.user_id AND _umeta4.meta_key = '_cart_session_key' )

			WHERE
				/* has a shopping cart */
				_umeta.meta_key = '_woocommerce_persistent_cart'

				/* no main user record - bogus user we're using for a cart! */
				AND ISNULL(_u.ID)

				/* cart does not have orders associated with it */
				AND NOT EXISTS( SELECT * FROM {$wpdb->postmeta} WHERE meta_key = '_cart_id' AND meta_value = _umeta.user_id )

				/* cart hasn't been modified in a year - the timestamp is not set for carts last used before v1.7 so use release date for that version */
				AND CURDATE() > DATE_ADD( IF( ISNULL(_umeta2.meta_value), DATE('2016-05-09'), FROM_UNIXTIME(_umeta2.meta_value) ), INTERVAL 1 YEAR )

			LIMIT 100
		");

        foreach ($results as $result) {

            // get session if result has user id ...
            $session = ($result->user_id ? namespace\get_session($result->session_key, $result->user_id) : false);

            // cart belongs to an existing session ? keep it and update the cart modified time ...
            if (
                $session
                && isset($session['carts'][$result->cart_id])
            ) {
                update_user_meta($result->cart_id, '_cart_modified', time());
            } // no session ? simply delete the cart w/all associated data ...
            else {
                namespace\delete_cart($result->cart_id, false);
            }

        }

    }


    /**
     * Update session cookie.
     *
     * @return void
     * @since 1.8
     * - trigger save storage bin action.
     *
     * @since 1.2
     */
    function action_update()
    {
        namespace\cookie(TRUE);
        namespace\action_save_bin();
    }


    /**
     * Load UI scripts, styles and localization.
     *
     * @return void
     * @since 1.7
     * - added woocom_multiaddr, true if WooCommerce Multiple Addresses plugin exists.
     *
     * @since 1.3
     */
    function action_assets()
    {

        // load styles ...
        wp_enqueue_style('bmc-woocom-multisession', plugins_url('bmc_woocom_multisession.css', __FILE__), array('dashicons'), namespace\VERSION);

        // load scripts ...
        wp_enqueue_script('bmc-woocom-multisession', plugins_url('bmc_woocom_multisession.js', __FILE__), array('jquery'), namespace\VERSION);

        // localize scripts ...
        wp_localize_script('bmc-woocom-multisession', 'BMCWcMs', array(

            'ajaxurl' => admin_url('admin-ajax.php'),

            'nonce' => wp_create_nonce('ajax'),

            'session_key' => (string)namespace\cookie('key'),

            'localize' => array(

                'insert_prompt' => __('Name', 'woocommerce'),

                'insert_prompt_value' => __('New Cart', namespace\DOMAIN),

                'delete_confirm' => __('Are you sure you want to delete cart, %cart-name? Any associated addresses will be lost. This cannot be undone.', namespace\DOMAIN),

                'sync_prompt' => sprintf(__('Save or load carts to or from %s with a CaSe SeNsItIvE key phrase.', namespace\DOMAIN), get_bloginfo('name')),

                'sync_confirm' => __('Carts for key phrase "%session-name" exist. Do you wish to load these carts?', namespace\DOMAIN),

                'unsync_confirm' => __('This will NOT delete your carts. Carts saved on key phrase "%session-name" will remain available to load again for up to one year. Do you wish to proceed?', namespace\DOMAIN)

            ),

            'woocom_multiaddr' => namespace\option('woocom_multiaddr'),

        ));

    }


    /**
     * Load admin assets.
     *
     * @return void
     * @since 1.4
     *
     */
    function action_admin_assets()
    {

        // load styles ...
        wp_enqueue_style('bmc-woocom-multisession-admin', plugins_url('bmc_woocom_multisession-admin.css', __FILE__), array(), namespace\VERSION);

    }


    /**
     * Display admin bar for all logged in users.
     * WooCom disables this for subscribers - we're reversing their meddling and removing everything except for WooCom account and carts menus for subscribers in action_admin_bar().
     *
     * @return bool
     * @since 1.3.1
     *
     */
    function filter_admin_bar()
    {
        return is_user_logged_in();
    }


    /**
     * Modify admin bar with carts UI.
     *
     * @return void
     * @since 1.3
     *
     */
    function action_admin_bar($wp_admin_bar)
    {

        // subscriber|customer only?
        if (namespace\is_customer()) {

            // reduce admin bar options ...
            $wp_admin_bar->remove_menu('my-sites');
            $wp_admin_bar->remove_menu('wp-logo');
            $wp_admin_bar->remove_menu('site-name');
            $wp_admin_bar->remove_menu('my-account');

            // add woocom "my account" ...
            $wp_admin_bar->add_node(array(
                'id' => 'woocom-account',
                'title' => __('My Account', 'woocommerce'),
                'href' => get_permalink(get_option('woocommerce_myaccount_page_id'))
            ));

        }

        // add carts menu ...
        $wp_admin_bar->add_node(array(
            'id' => 'carts',
            'title' => __('Carts', namespace\DOMAIN)
        ));

        // retrieve carts ...
        $carts = namespace\get_carts();

        // sort carts ...
        if (namespace\option('sort_carts')) uksort($carts, __NAMESPACE__ . '\uksort_carts');

        // render carts ...
        foreach ($carts as $cart_id => $cart) {

            // cart mode ...
            $mode = array_key_exists('mode', $cart) ? $cart['mode'] : 'cart';
            if ($mode !== 'bin') $mode = 'cart';

            // is current cart ?
            $current_cart = ($cart_id == namespace\customer_id());

            // current cart ? link current cart to woocom cart page ...
            if ($current_cart) {

                $wp_admin_bar->add_node(array(
                    'parent' => 'carts',
                    'id' => 'cart-' . $cart_id,
                    'title' => namespace\format_cart_name($cart['name']),
                    'href' => WC()->cart->get_cart_url(),
                    'meta' => array(
                        'class' => 'cart-select cart-current cart-mode-' . $mode,
                        'onclick' => 'return BMCWcMs.command("select", this);',
                        'target' => $cart_id,
                        'title' => __('Cart Page', 'woocommerce')
                    )
                ));

            } // set select cart top level option ...
            else {

                $wp_admin_bar->add_node(array(
                    'parent' => 'carts',
                    'id' => 'cart-' . $cart_id,
                    'title' => namespace\format_cart_name($cart['name']),
                    'href' => '#select',
                    'meta' => array(
                        'class' => 'cart-select cart-mode-' . $mode,
                        'onclick' => 'return BMCWcMs.command("select", this);',
                        'target' => $cart_id,
                        'title' => __('Select Cart', namespace\DOMAIN)
                    )
                ));

            }

            // update / edit name option ...
            $wp_admin_bar->add_node(array(
                'parent' => 'cart-' . $cart_id,
                'id' => 'cart-edit-' . $cart_id,
                'title' => __('Edit Name', namespace\DOMAIN),
                'href' => '#edit',
                'meta' => array(
                    'class' => 'cart-edit',
                    'onclick' => 'return BMCWcMs.command("update", this);',
                    'target' => $cart_id,
                )
            ));

            // switch to shopping cart mode ...
            $wp_admin_bar->add_node(array(
                'parent' => 'cart-' . $cart_id,
                'id' => 'cart-mode-cart-' . $cart_id,
                'title' => __('Shopping Cart', namespace\DOMAIN),
                'href' => '#mode',
                'meta' => array(
                    'class' => 'cart-mode-cart',
                    'onclick' => 'return BMCWcMs.command("mode", this);',
                    'target' => $cart_id,
                    'title' => __('Switch to Shopping Cart mode. Shopping carts are emptied upon checkout.', namespace\DOMAIN)
                )
            ));

            // switch to storage bin mode ...
            $wp_admin_bar->add_node(array(
                'parent' => 'cart-' . $cart_id,
                'id' => 'cart-mode-bin-' . $cart_id,
                'title' => __('Storage Bin', namespace\DOMAIN),
                'href' => '#mode',
                'meta' => array(
                    'class' => 'cart-mode-bin',
                    'onclick' => 'return BMCWcMs.command("mode", this);',
                    'target' => $cart_id,
                    'title' => __('Switch to Storage Bin mode. Storage bins retain items after checkout.', namespace\DOMAIN)
                )
            ));

            // clone cart option if there are less than 10 carts ...
            if (count($carts) < 10) {

                $wp_admin_bar->add_node(array(
                    'parent' => 'cart-' . $cart_id,
                    'id' => 'cart-clone-' . $cart_id,
                    'title' => __('Clone Cart', namespace\DOMAIN),
                    'href' => '#clone',
                    'meta' => array(
                        'class' => 'cart-clone',
                        'onclick' => 'return BMCWcMs.command("clone", this);',
                        'target' => $cart_id,
                        'title' => __('Create a new cart with the items and addresses of this cart.', namespace\DOMAIN)
                    )
                ));

            }

            // set options for carts other than current ...
            if (!$current_cart) {

                // copy option ...
                $wp_admin_bar->add_node(array(
                    'parent' => 'cart-' . $cart_id,
                    'id' => 'cart-copy-' . $cart_id,
                    'title' => __('Copy Items', namespace\DOMAIN),
                    'href' => '#copy',
                    'meta' => array(
                        'class' => 'cart-copy',
                        'onclick' => 'return BMCWcMs.command("copy", this);',
                        'target' => $cart_id,
                        'title' => __('Copy items from this cart to your current cart.', namespace\DOMAIN)
                    )
                ));

                // delete option ...
                $wp_admin_bar->add_node(array(
                    'parent' => 'cart-' . $cart_id,
                    'id' => 'cart-delete-' . $cart_id,
                    'title' => __('Delete Cart', namespace\DOMAIN),
                    'href' => '#delete',
                    'meta' => array(
                        'class' => 'cart-delete',
                        'onclick' => 'return BMCWcMs.command("delete", this);',
                        'target' => $cart_id,
                    )
                ));

            }

            // shopping cart items ...
            $wp_admin_bar->add_node(array(
                'parent' => 'cart-' . $cart_id,
                'id' => 'cart-items-' . $cart_id,
                'title' => '<div class="cart-items-loading"></div>',
                'meta' => array(
                    'class' => 'cart-items'
                )
            ));

        }

        // allow up to 10 carts ...
        if (count($carts) < 10) {

            $wp_admin_bar->add_node(array(
                'parent' => 'carts',
                'id' => 'cart-new',
                'title' => __('New Cart', namespace\DOMAIN),
                'href' => '#new',
                'meta' => array(
                    'onclick' => 'return BMCWcMs.command("insert", this);'
                )
            ));

        }

        // not sync'd to user account ? permit manual sync'ing on key ...
        if (!namespace\option('sync_per_user')) {

            // session key ...
            $session_key = namespace\cookie('key');

            // save session option ...
            $wp_admin_bar->add_node(array(
                'parent' => 'carts',
                'id' => 'cart-' . ($session_key ? 'syncd' : 'sync'),
                'title' => ($session_key ? __('Carts Saved', namespace\DOMAIN) : __('Save / Load Carts', namespace\DOMAIN)),
                'href' => '#sync',
                'meta' => array(
                    'title' => sprintf(
                        ($session_key ? __('Carts saved to %s.', namespace\DOMAIN) : __('Save or load carts to or from %s with a key phrase.', namespace\DOMAIN)),
                        get_bloginfo('name')
                    ),
                    'onclick' => 'return BMCWcMs.command("sync", this);'
                )
            ));

            // saved session ? delete session option ...
            if ($session_key) {

                $wp_admin_bar->add_node(array(
                    'parent' => 'cart-syncd',
                    'id' => 'cart-unsync',
                    'title' => __('Unsave Carts', namespace\DOMAIN),
                    'href' => '#unsync',
                    'meta' => array(
                        'title' => sprintf(
                            esc_attr__('Stop saving carts to %s?', namespace\DOMAIN),
                            get_bloginfo('name')
                        ),
                        'onclick' => 'return BMCWcMs.command("unsync", this);'
                    )
                ));

            }

        }

    }


    /**
     * Outputs JSON in response to AJAX commands posted from UI.
     *
     * @return void
     * @since 1.4
     *    - Added copy command.
     * @since 1.6
     *    - Added sync, unsync, load commands.
     * @since 1.7
     *    - Added order_cart_update_html, order_cart_update, clone commands.
     * @since 1.8
     *  - Added mode command.
     *
     * @since 1.3
     */
    function action_ajax()
    {

        // data object to return ...
        $data = array('success' => false);

        // verify nonce ...
        if (!wp_verify_nonce($_POST['nonce'], 'ajax')) wp_send_json($data);

        // get carts ...
        $carts = namespace\get_carts();

        // execute command ...
        switch ($_POST['command']) {

            // new cart - selects new cart upon creation ...
            case 'insert':

                // sets post id to select to (below) - needs to be string for consistently ...
                $_POST['id'] = (string)namespace\generate_customer_id();

                // create new cart ...
                $carts[$_POST['id']] = namespace\create_cart(array('name' => $_POST['name']));

            // new cart - selects new cart upon creation ...
            case 'insertpos':

                // sets post id to select to (below) - needs to be string for consistently ...
                $_POST['id'] = (string)namespace\generate_customer_id();

                // create new cart ...
                $carts[$_POST['id']] = namespace\create_cart(array('name' => $_POST['name'] . '-pos'));

            // new cart - selects new cart upon creation ...
            case 'insertcomponent':

                // sets post id to select to (below) - needs to be string for consistently ...
                $_POST['id'] = (string)namespace\generate_customer_id();

                // create new cart ...
                $carts[$_POST['id']] = namespace\create_cart(array('name' => $_POST['name'] . '-component'));

            // pick cart ...
            case 'select':

                // set up customer ids ...
                $data['old_customer_id'] = namespace\customer_id();
                $data['customer_id'] = $_POST['id'];

                // if the old customer id isn't among the carts, save it now ...
                if (!isset($carts[$data['old_customer_id']])) {
                    $carts[$data['old_customer_id']] = namespace\create_cart();
                }

                // update cookie ...
                namespace\cookie(array(
                    'customer_id' => absint($_POST['id']),    // always save customer id as integer ...
                    'carts' => $carts
                ));
                break;

            // edit cart ...
            case 'update':
                // get cart ...
                if ($cart = $carts[($data['cart_id'] = $_POST['id'])]) {

                    // update name ...
                    $cart['name'] = $data['cart_name'] = namespace\format_cart_name($_POST['name']);

                    // update cart ...
                    $carts[$data['cart_id']] = namespace\create_cart($cart);

                    // save the carts ...
                    namespace\cookie('carts', $carts);

                }
                break;

            // remove cart ...
            case 'delete':

                // pass through the cart id to delete from ui ...
                $data['cart_id'] = $_POST['id'];

                // remove the persistent cart from user meta ...
                namespace\delete_cart($data['cart_id']);

                // remove from carts ...
                unset($carts[$data['cart_id']]);

                // save the carts ...
                namespace\cookie('carts', $carts);
                break;

            /**
             * copy cart ...
             * @since 1.4
             */
            case 'copy':
                // get cart data ...
                if ($items = namespace\get_cart($_POST['id'])) {

                    // add each item to the current cart ...
                    foreach ($items as $item_id => $item) {

                        $quantity = get_post_meta($item['product_id'], 'quantity', true);
                        WC()->cart->add_to_cart(
                            $item['product_id'],
                            $quantity,
                            $item['variation_id'],
                            $item['variation']
                        );
                    }

                }
                break;

            /**
             * sync session ...
             * @since 1.6
             *
             * @var string $key
             *    - session key to sync on.
             * @var bool $confirmed
             *    - whether or not the user has confirmed the decision to override current session wih saved one.
             */
            case 'sync':

                // session key ...
                $key = trim((string)$_POST['key']);

                // whether session needs to be confirmed first ...
                $data['confirm'] = false;

                // no key or user sync'd ? disable sync'd session ...
                if (empty($key) || namespace\option('sync_per_user')) {

                    /**
                     * @note By leaving the carts intact, the user can still affect the carts individually, meaning that deleting them from one session deletes them from all sessions.
                     */
                    namespace\cookie('key', '');

                } // session exists on key ? confirm !
                elseif ($session = namespace\get_session($key)) {

                    // confirmed ? load cookie w/session ...
                    if ($_POST['confirmed']) {

                        // merge so that the user doesn't lose carts ...
                        // update older current with newer session ...
                        if ($session['modified'] > namespace\cookie('modified')) {
                            $session = namespace\array_merge_recursive(namespace\cookie(), $session);
                        } // update older session with newer current ...
                        else {
                            $session = namespace\array_merge_recursive($session, namespace\cookie());
                        }

                        // ensure session key ...
                        $session['key'] = $key;

                        // replace current session with merged session ...
                        namespace\cookie($session);
                    } // request confirmation ...
                    else {
                        $data['confirm'] = true;
                    }

                } // no session exists ? create it now by setting key ...
                else {
                    namespace\cookie('key', $key);
                }

                break;


            /**
             * Load cart items.
             * @return array
             *    - string $html Table markup.
             * @var integer $id cart / customer id
             *
             * @since 1.6
             *
             */
            case 'load':

                $html = sprintf('<table><thead><th colspan="2">%s</th><th>%s</th></thead><tbody>',
                    __('Item Name', namespace\DOMAIN),
                    __('Qty', namespace\DOMAIN)
                );

                if ($items = namespace\get_cart($_POST['id'])) {

                    foreach ($items as $item) {

                        // just get normal post data for the product - WooCom ends up doing this anyway, we just cut out the middleman ...
                        $product = get_post($item['product_id']);

                        $html .= sprintf('<tr><td><a href="%s">%s</a></td><td></td><td>%d</td></tr>',
                            get_permalink($product->ID),
                            $product->post_title,
                            get_post_meta($product->ID, 'quantity', true)
                        );

                    }

                } else {

                    // $html .= sprintf('<td colspan="3" align="center">%s</td>',
                    // 	__('Your cart is currently empty.', 'woocommerce')
                    // );

                }

                $html .= '</tbody></html>';

                $data['html'] = $html;
                break;


            /**
             * Return html for select list of carts to replace switch cart order action links with.
             * @since 1.7
             */
            case 'order_cart_update_html':

                $html = '';

                $carts = namespace\get_carts();
                if (count($carts) > 1) {

                    $html .= '<select class="cart-switch" data-order-id="%order-id" onchange="return BMCWcMs.command(\'order_cart_update\', this);">'
                        . sprintf('<option value="%d">- %s -</option>', namespace\customer_id(), __('Move To Cart', namespace\DOMAIN));

                    foreach ($carts as $cart_id => $cart) {
                        if ($cart_id == namespace\customer_id()) continue;
                        $html .= sprintf('<option value="%d">%s</option>', $cart_id, $cart['name']);
                    }

                    $html .= '</select>';

                }

                $data['html'] = $html;
                break;


            /**
             * Update order with new cart id.
             * @since 1.7
             *
             * @var integer $order_id
             * @var integer $cart_id
             */
            case 'order_cart_update':

                // update meta record ...
                $order_id = (integer)$_POST['order_id'];
                $cart_id = (integer)$_POST['cart_id'];
                update_post_meta($order_id, '_cart_id', $cart_id);

                // pass the account orders url to return to ...
                $data['url'] = wc_get_endpoint_url('orders', '', wc_get_page_permalink('myaccount'));
                break;


            /**
             * Clone a cart.
             * @since 1.7
             *
             * @var integer $cart_id
             */
            case 'clone':
                if ($cart = $carts[$cart_id = $_POST['cart_id']]) {

                    // new cart id ...
                    $clone_id = namespace\generate_customer_id();

                    // copy meta data ...
                    // @note Unserialize the value
                    if ($meta = get_user_meta($cart_id)) {
                        foreach ($meta as $meta_key => $meta_value) {
                            add_user_meta($clone_id, $meta_key, maybe_unserialize(current($meta_value)), true);
                        }
                    }

                    // clone cart ...
                    $clone = $cart;

                    // set clone name ...
                    $clone['name'] = $_POST['name'];

                    // add clone to carts ...
                    $carts[$clone_id] = namespace\create_cart($clone);

                    // update session ...
                    namespace\cookie(array(
                        'customer_id' => $clone_id,
                        'carts' => $carts
                    ));

                }
                break;


            /**
             * Switch cart mode.
             * @since 1.8
             *
             * @var integer $cart_id
             */
            case 'mode':

                // cart id ...
                $cart_id = $data['cart_id'] = $_POST['cart_id'];

                // mode to switch to ...
                $mode = $_POST['mode'];
                if ($mode !== 'bin') $mode = 'cart';
                $data['mode'] = $mode;

                // cart exists ?
                if (isset($carts[$cart_id])) {

                    // set mode ...
                    $carts[$cart_id]['mode'] = $mode;

                    // save carts ...
                    namespace\cookie('carts', $carts);

                    // trigger storage bin action ...
                    namespace\action_save_bin();
                }
                break;

        }

        $data['success'] = true;
        wp_send_json($data);
    }


    /**
     * Add plugin links to documentation and settings integrated into WooCommerce.
     *
     * @param array $links
     * @param string $file
     *    - Basename of plugin file.
     * @return array
     * @since 1.4
     *
     */
    function filter_plugin_links($links, $file)
    {

        if (plugin_basename(__FILE__) == $file) {

            $links = array_merge($links, array(
                'documentation' => sprintf(
                    '<a class="thickbox" href="%s">%s</a>',
                    plugins_url('README.html?TB_iframe=true&version=' . namespace\VERSION, __FILE__),
                    __('View details')
                )
            ));

        }

        return $links;

    }


    /**
     * Adds Settings link to the settings interface under the plugin name in the plugins interface.
     *
     * @since 1.8
     */
    function filter_plugin_action_links($links)
    {

        array_unshift($links, sprintf(
            '<a id="%s" href="%s#%s">%s</a>',
            namespace\OPTION,
            admin_url('admin.php?page=wc-settings&tab=general'),
            namespace\OPTION,
            __('Settings', 'woocommerce')
        ));

        return $links;
    }


    /**
     * Integrate plugin settings into WooCommerce General options.
     * @see https://docs.woothemes.com/document/adding-a-section-to-a-settings-tab/
     *
     * @since 1.4
     *
     * @param array $settings WooCommerce settings.
     * @return array
     */
    function filter_woocom_settings($settings)
    {

        $settings[] = array(
            'id' => namespace\OPTION,
            'type' => 'title',
            'name' => __('Multiple Carts Per User Options', namespace\DOMAIN),
            'desc' => sprintf(
                __('Settings for the <a id="%s" href="%s">Multiple Carts Per User</a> plugin.', namespace\DOMAIN),
                namespace\OPTION,
                admin_url('plugins.php#' . namespace\OPTION)
            )
        );

        // carts menu ...
        $settings[] = array(
            'id' => namespace\option_name('carts_menu'),
            'default' => namespace\option_default('carts_menu'),
            'type' => 'checkbox',
            'name' => __('Carts Menu', namespace\DOMAIN),
            'desc' => __('Add Carts menu to the Admin Toolbar.', namespace\DOMAIN)
        );

        // customer toolbar ...
        $settings[] = array(
            'id' => namespace\option_name('customer_toolbar'),
            'default' => namespace\option_default('customer_toolbar'),
            'type' => 'checkbox',
            'name' => __('Customer Toolbar', namespace\DOMAIN),
            'desc' => __('Display the Admin Toolbar for Customers with Carts menu and link to WooCommerce account page.', namespace\DOMAIN)
        );

        // sort current cart to the top ...
        $settings[] = array(
            'id' => namespace\option_name('sort_carts'),
            'default' => namespace\option_default('sort_carts'),
            'type' => 'checkbox',
            'name' => __('Current Cart On Top', namespace\DOMAIN),
            'desc' => __('Always display the current cart at the top of the Carts menu or widget.', namespace\DOMAIN)
        );

        // sync settings ...
        $settings[] = array(
            'id' => namespace\option_name('sync_per_user'),
            'default' => namespace\option_default('sync_per_user'),
            'type' => 'checkbox',
            'name' => __('Link Carts per User', namespace\DOMAIN),
            'desc' => __('Automatically link carts sessions to user accounts rather than optionally by key phrase. Customers will use the same carts as other customers logged into the same user account.', namespace\DOMAIN)
        );

        // account display per user (true), cart (false) ...
        $settings[] = array(
            'id' => namespace\option_name('account_per_user'),
            'default' => namespace\option_default('account_per_user'),
            'type' => 'checkbox',
            'name' => __('Account Features per User', namespace\DOMAIN),
            'desc' => __('Display account features (orders, downloads) by user rather than by cart. Customers will see the same orders and downloads as other customers logged into the same user account.', namespace\DOMAIN)
        );

        // account access ...
        $settings[] = array(
            'id' => namespace\option_name('account_access'),
            'default' => namespace\option_default('account_access'),
            'type' => 'checkbox',
            'name' => __('Customer Account Access', namespace\DOMAIN),
            'desc' => __('Allow customers to update the user account they\'re logged into.', namespace\DOMAIN)
        );

        // delete data on uninstall ...
        $settings[] = array(
            'id' => namespace\option_name('uninstall_data'),
            'default' => namespace\option_default('uninstall_data'),
            'type' => 'checkbox',
            'name' => __('Delete Data On Uninstall', namespace\DOMAIN),
            'desc' => __('Remove all settings and carts data when uninstalling the plugin.', namespace\DOMAIN)
        );

        $settings[] = array(
            'id' => namespace\OPTION,
            'type' => 'sectionend'
        );

        return $settings;
    }


    /**
     * Update order meta w/cart id to show only orders from carts on the account page vs. user.
     *
     * @param int $order_id Order post id in posts table.
     * @param array $form Posted form data.
     * @return void
     * @since 1.5
     *
     */
    function action_order_meta($order_id, $form)
    {
        update_post_meta($order_id, '_cart_id', namespace\customer_id());
    }


    /**
     * Updates cart bogus user account with billing/shipping data, bypasses updating user account (if not sync'd to user account), during checkout.
     *
     * @since 1.7
     *
     * @see woocommerce/includes/class-wc-checkout.php WC_Checkout::create_order()
     */
    function filter_checkout_cart_meta($update, $wc_checkout)
    {

        if (
            // no woocom multi address plugin ?
            !namespace\option('woocom_multiaddr')

            // not using a predefined billing addr. ?
            || $wc_checkout->posted['billing_alt'] == 0
        ) {

            // billing meta ...
            $meta = namespace\preg_grep_keys('/^billing_/', $wc_checkout->posted);
            foreach ($meta as $key => $value) update_user_meta(namespace\customer_id(), $key, $value);

        }

        // shipping meta ...
        if (
            WC()->cart->needs_shipping()
            && (
                !namespace\option('woocom_multiaddr')
                || $wc_checkout->posted['shipping_alt'] == 0 // not using a predefined shipping addr.
            )
        ) {

            $meta = namespace\preg_grep_keys('/^shipping_/', $wc_checkout->posted);
            foreach ($meta as $key => $value) update_user_meta(namespace\customer_id(), $key, $value);

        }

        // update user account meta if sync'ing per user account ...
        return ($update && namespace\option('sync_per_user'));
    }


    /**
     * Alter the orders query to match against _cart_id meta_key with current customer id.
     *
     * @param array $query Posts query
     * @return array
     * @since 1.5
     * @depreciated 1.6
     *    - this no longer appears to work after WooCom 2.5!!! See filter_meta_sql()
     *
     */
    function filter_order_query($query)
    {
        $query['meta_key'] = '_cart_id';
        $query['meta_value'] = namespace\customer_id();
        return $query;
    }


    /**
     * Since WooCom 2.6+ seem to have altered the way their woocommerce_my_account_orders_query filter works, no longer allowing direct modification of the WP_Query values, it is now modified at a more core WP level.
     *
     * @since 1.6
     *
     * @see woocommerce/includes/wc-order-functions.php wc_get_orders()
     * @see wp-includes/class-wp-meta-query.php WP_Meta_Query::get_sql()
     */
    function filter_meta_sql($sql, $queries, $type, $table, $column, $wp_query)
    {

        if (
            is_object($wp_query)
            && $wp_query instanceof \WP_Query
            && isset($wp_query->query['post_type'])
            && is_array($wp_query->query['post_type'])
            && in_array('shop_order', $wp_query->query['post_type'])
        ) {

            global $wpdb;

            $sql['where'] = $wpdb->prepare("AND ( {$wpdb->postmeta}.meta_key = '_cart_id' AND CAST({$wpdb->postmeta}.meta_value AS CHAR) IN (%s) )", namespace\customer_id());

        }

        return $sql;
    }


    /**
     * Filter out download results where cart:customer_id doesn't match the order:cart_id.
     *
     * @since 1.6
     *
     * @see woocommerce/includes/wc-user-functions.php wc_get_customer_available_downloads()
     */
    function filter_download_results($results)
    {

        foreach ($results as $i => $result) {
            if (get_post_meta($result->order_id, '_cart_id', true) != namespace\customer_id()) {
                unset($results[$i]);
            }
        }

        return $results;
    }


    /**
     * Appends cart name to My Account, Cart and Checkout pages if there is more than one cart.
     *
     * @param string $title
     * @param integer $post_id
     * @return string
     * @since 1.5
     *
     */
    function filter_page_title_append_cart_name($title, $post_id)
    {

        $carts = namespace\get_carts();

        if (
            // more than one cart ...
            count($carts) > 1

            && (
                (
                    $post_id == wc_get_page_id('myaccount')
                    && (
                        !is_wc_endpoint_url()
                        || is_wc_endpoint_url('edit-address')
                        || (!namespace\option('account_per_user') && is_wc_endpoint_url('downloads'))
                        || (!namespace\option('account_per_user') && is_wc_endpoint_url('orders'))
                        || (!namespace\option('account_per_user') && is_wc_endpoint_url('view-order'))
                    )
                )
                || $post_id == wc_get_page_id('checkout')
                || $post_id == wc_get_page_id('cart')
            )

        ) {
            $title .= sprintf(' (%s)', namespace\current_cart_name());
        }

        return $title;
    }


    /**
     * Override account templates, preventing customers from accessing/updating account details.
     *
     * @param string $template_path
     * @param string $template_name
     * @param array $args
     * @return string full path to template
     * @since 1.6
     * @since 1.8
     *    - added myaccount/dashboard.php template override, removing edit account link.
     *
     */
    function filter_wc_template($template_path, $template_name, $args)
    {

        switch ($template_name) {

            // replace my templates only if generic is used (not themed) ...
            case 'myaccount/my-account.php':
                // use woocom's template for 2.6+ ...
                if (namespace\wc_version('2.6', '>=')) break;

            case 'myaccount/dashboard.php':
                // generic woocommerce template NOT used ? don't override ...
                if (!preg_match('#plugins/woocommerce/templates/#', str_replace('\\', '/', $template_path))) break;

            // replace edit account form ...
            case 'myaccount/form-edit-account.php':
                $template_path = plugin_dir_path(__FILE__) . 'woocommerce/' . $template_name;
                break;

        }

        return $template_path;

    }

    /**
     * Remove edit account link from myaccount/navigation.php template.
     * @param array $items
     * @return array
     * @see wc-account-functions.php wc_get_account_menu_items()
     *
     * @since 1.6
     *
     */
    function filter_account_menu_items($items)
    {
        unset($items['edit-account']);
        return $items;
    }


    /**
     * Integrate addresses associated with carts with WooCommerce Multiple Addresses plugin via bypassing the plugin's get_user_meta() calls.
     * @return null|array
     * @see wp-content/plugins/woocommerce-multiple-addresses/class-woocommerce-multiple-addresses.php
     *
     * @since 1.7
     *
     * @see wp-includes/meta.php get_metadata()
     */
    function filter_get_user_meta($value, $user_id, $meta_key, $single)
    {

        if (
            // woocom multiaddr uses this key to save a numeric array of addresses ...
            $meta_key == 'wc_multiple_shipping_addresses'

            // must be called from checkout / ajax context to bypass !
            // otherwise this will add the cart addresses to the woocom multiaddr record itself when configured which we do not want ...
            && namespace\caller('WC_Multiple_addresses::add_dd_to_checkout_fields|WC_Multiple_addresses::ajax_checkout_change_shipping_address')
        ) {

            // retrieve addresses via database as using get_user_meta here or will result in an infinity loop!
            global $wpdb;
            if ($addresses = $wpdb->get_var($wpdb->prepare("SELECT meta_value FROM {$wpdb->usermeta} WHERE user_id = %d AND meta_key = %s", $user_id, $meta_key))) {
                $addresses = maybe_unserialize($addresses);
            } else {
                $addresses = array();
            }

            // billing / shipping fields map ...
            $billing_shipping = array(
                'billing_first_name' => 'shipping_first_name',
                'billing_last_name' => 'shipping_last_name',
                'billing_company' => 'shipping_company',
                'billing_address_1' => 'shipping_address_1',
                'billing_address_2' => 'shipping_address_2',
                'billing_city' => 'shipping_city',
                'billing_state' => 'shipping_state',
                'billing_postcode' => 'shipping_postcode',
                'billing_country' => 'shipping_country'
            );

            // add cart addresses ...
            $carts = namespace\get_carts();
            foreach ($carts as $cart_id => $cart) {

                // get all meta fields for cart ...
                if (!($meta = get_user_meta($cart_id))) continue;

                // process billing address ...
                if ($keys = array_intersect_key($meta, $billing_shipping)) {

                    $address = array(
                        // label from cart name ...
                        'label' => $cart['name'],

                        // default if current cart ...
                        'shipping_address_is_default' => (namespace\customer_id() == $cart_id),
                    );

                    foreach ($keys as $key => $value) {
                        $address[$billing_shipping[$key]] = current($value);
                    }

                    // add email ...
                    if ($value = $meta['billing_email']) {
                        $address['email'] = current($value);
                    }

                    // add phone ...
                    if ($value = $meta['billing_phone']) {
                        $address['phone'] = current($value);
                    }

                    // append to addresses ...
                    $addresses[] = $address;

                }

                // process shipping address ...
                if ($keys = array_intersect_key($meta, array_flip($billing_shipping))) {

                    $address = array(
                        // label from cart name ...
                        'label' => $cart['name'],

                        // default if current cart ...
                        'shipping_address_is_default' => (namespace\customer_id() == $cart_id),
                    );

                    foreach ($keys as $key => $value) {
                        $address[$key] = current($value);
                    }

                    // append to addresses ...
                    $addresses[] = $address;

                }

            }

            // if single is true (and it will be in this case), nest results in another array so WP behaves properly ...
            if ($single) {
                $addresses = array($addresses);
            }

            return $addresses;

        }

        return $value;
    }


    /**
     * Updates current cart meta info whenever user meta is updated/added (cart items, addresses, etc.).
     * This is useful connective tissue for reigning in possible wayward carts and maintenance in the future.
     *
     * @since 1.7
     */
    function action_updated_user_meta($meta_id, $user_id, $meta_key, $_meta_value)
    {

        if (
            // not prefixed with "_cart" - otherwise this will trigger an infinity loop!
            strpos($meta_key, '_cart_') !== 0

            // updated current cart bogus user ...
            && $user_id == namespace\customer_id()
        ) {

            // associate with current actual user ...
            update_user_meta($user_id, '_cart_user_id', namespace\cookie('user_id'));

            // modified time ...
            update_user_meta($user_id, '_cart_modified', time());

            // cart name ...
            update_user_meta($user_id, '_cart_name', namespace\current_cart_name());

            // last version of this plugin the cart was updated by ...
            update_user_meta($user_id, '_cart_version', namespace\VERSION);

            // cart session key ...
            update_user_meta($user_id, '_cart_session_key', namespace\cookie('key'));

        }

    }


    /**
     * Add switch cart action to orders list.
     * This creates a link that jquery converts to a list.
     *
     * @since 1.7
     *
     * @see woocommerce/templates/myaccount/orders.php
     */
    function filter_order_actions($actions, $wc_order)
    {
        $actions['cart-switch'] = array(
            'url' => '#',
            'name' => $wc_order->id
        );
        return $actions;
    }


    /**
     * Add switch list like the one output by filter_order_actions().
     *
     * @since 1.7
     *
     * @see woocommerce/templates/myaccount/view-order.php
     */
    function action_order_view($order_id)
    {
        printf('<div class="order-actions"><a class="cart-switch" href="#">%d</a></div>', $order_id);
    }


    /**
     * Saves cart items in storage bin mode.
     *
     * @since 1.8
     *
     * @see action_update()
     * @see action_ajax()
     */
    function action_save_bin()
    {

        // require storage bin mode ...
        $cart = namespace\current_cart();
        if ($cart['mode'] !== 'bin') return;

        // get cart items ...
        $items = namespace\get_cart();

        // backup items in storage bin meta ...
        update_user_meta(namespace\customer_id(), '_cart_bin', $items);

    }


    /**
     * Restores cart items in storage bin mode after the cart has been emptied during checkout.
     *
     * @since 1.8
     *
     * @see woocommerce/includes/class-wc-cart.php WC_Cart::empty_cart();
     */
    function action_restore_bin()
    {

        // require storage bin mode ...
        $cart = namespace\current_cart();
        if ($cart['mode'] !== 'bin') return;

        // retrieve items from storage bin meta ...
        $items = get_user_meta(namespace\customer_id(), '_cart_bin', true);
        if (empty($items) || !is_array($items)) return;

        // copy stored items back to cart ...
        foreach ($items as $item_id => $item) {
            $quantity = get_post_meta($item['product_id'], 'quantity', true);
            WC()->cart->add_to_cart(
                $item['product_id'],
                $quantity,
                $item['variation_id'],
                $item['variation']
            );
        }

    }


}

### GLOBAL NAMESPACE ###
namespace {

    use BMC\WooCommerce\Multisession as BmcWcMs;

    if (
        // not admin interface (the wp_get_current_user() override breaks the admin panel! And it's unnecessary in admin context, still works w/ajax)
        !is_admin()
        // make sure this pluggable function hasn't already been 'plugged' yet - @see /wp-includes/pluggable.php
        && !function_exists('wp_get_current_user')
    ) {

        /**
         * Override wp_get_current_user() function to insert conditions when called by get_current_user_id().
         * @return WP_User
         */
        function wp_get_current_user()
        {

            // wp 4.5+
            if (function_exists('_wp_get_current_user')) {
                $current_user = _wp_get_current_user();
            } // wp 4.4-
            else {
                global $current_user;
                get_currentuserinfo();
            }

            // regular expressions ...
            static $rex_wp_get_current_user;
            static $rex_get_current_user_id;
            if (!isset($rex_wp_get_current_user)) {

                $rex_wp_get_current_user = sprintf('/%s/Si', implode('|', array(
                    'WC_Checkout::get_value',
                )));

                $rex_get_current_user_id = sprintf('/%s/Si', implode('|', array(
                    // cart related ...
                    'WC_Cart::get_cart_from_session',
                    'WC_Cart::persistent_cart_',
                    'WC_Cart::set_session',
                    'WC_Cart::empty_cart',

                    // address related ...
                    'WC_Checkout::__construct',
                    'WC_Shortcode_My_Account::edit_address',
                    'WC_Customer::set_default_data',
                    'my-address.php',
                    'WC_Form_Handler::save_address',
                )));

            }

            // stack trace ...
            $trace = BmcWcMs\caller();

            /**
             * Intercept certain functions and templates.
             */
            if (
                // getting user object via wp_get_current_user()
                count(preg_grep($rex_wp_get_current_user, $trace))

                // getting user id via get_current_user_id()
                || (
                    count(preg_grep('/get_current_user_id/', $trace))
                    && count(preg_grep($rex_get_current_user_id, $trace))
                )
            ) {

                static $bogus_user;
                if (!isset($bogus_user)) {
                    $bogus_user = clone $current_user;
                    $bogus_user->ID = $bogus_user->data->ID = BmcWcMs\customer_id();
                }

                return $bogus_user;

            }

            return $current_user;
        }

    }

}
