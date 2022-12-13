<?php
/**
 * @package BurningMoth \ WooCommerce \ Multisession
 * @author Tarraccas Obremski <tarraccas@burningmoth.com>
 * @copyright 2015-2017 Burning Moth Creations, Inc.
 */

/*-- INFO --
Plugin Name: WooCommerce Multiple Carts Per User
Description: Modifies WooCommerce's one user account to one shopping cart paradigm to provide for different people logging into the same user account to have multiple shopping carts, shipping & billing addresses and order histories separate from each other.
Version: 1.12
Author: Burning Moth Creations
Author URI: https://www.burningmoth.com/
License: Envato Tools License
License URI: http://codecanyon.net/licenses/terms/tools
Plugin URI: http://codecanyon.net/item/woocommerce-multiple-carts-per-user/14274432
Text Domain: bmc-woocom-multisession
Domain Path: /lang/
--*/


/*-- FINALIZING CHECKLIST --
1. Observe WordPress best practices:
	a. All action callback arguments should have default values/checks.
	b. Second onward filter callback arguments should have default values/checks.
2. Update all version numbers here and in README.html
3. Update changelog here and in README.html
4. Remove all calls to console() and other debugging tools.
5. Turn off/on all ancillary plugins, make sure nothing breaks.
*/


/*-- KNOWN ISSUES --
* Bug (maybe?): Payment methods might be at the user level in WooCom 2.6+
* Bug: Carts are keyed to the latest session that uses them though they may belong to more than one session.
--*/

namespace BMC\WooCommerce\MultiSession;

### UTILITIES ###

/**
 * Get a plugin value.
 * @see https://codex.wordpress.org/Function_Reference/get_plugin_data
 *
 * @since 1.9
 *
 * @param string $key
 * @param mixed $alt
 * @return mixed
 */
function plugin($key, $alt = '')
{
    static $plugin;
    if (!isset($plugin)) {
        if (!function_exists('get_plugin_data')) {
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        }
        $plugin = get_plugin_data(__FILE__, false, true);
    }
    return (array_key_exists($key, $plugin) ? $plugin[$key] : $alt);
}


/**
 * Report deprecated functionality if WP_DEBUG is true.
 * This mimics the _deprecated_function() private WordPress function.
 *
 * @param string $feature
 *    - fair to just pass __FUNCTION__ constant
 * @param string $version_deprecated
 *    - version that the function was deprecated
 * @param string $replacement (optional)
 *    - function to use instead
 * @param string $version_removed (optional)
 *    - version that this feature is slated for removal from
 * @return void
 * @since 1.10
 *
 */
function deprecate($feature, $version_deprecated, $replacement = null, $version_removed = null)
{

    if (\WP_DEBUG) {

        $msg = (function_exists('__') ? ($replacement ? sprintf(
            __('%1$s is <strong>deprecated</strong> since version %2$s! Use %3$s instead.'),
            $feature,
            $version_deprecated,
            $replacement
        ) : sprintf(
            __('%1$s is <strong>deprecated</strong> since version %2$s with no alternative available.'),
            $feature,
            $version_deprecated
        )) : ($replacement ? sprintf(
            '%1$s is <strong>deprecated</strong> since version %2$s! Use %3$s instead.',
            $feature,
            $version_deprecated,
            $replacement
        ) : sprintf(
            '%1$s is <strong>deprecated</strong> since version %2$s with no alternative available.',
            $feature,
            $version_deprecated
        )));

        if ($version_removed) $msg .= sprintf(
            (function_exists('__') ? __(' Expect this feature to be removed from version %1$s or later.', 'bmc-woocom-multisession') : ' Expect this feature to be removed from version %1$s or later.'),
            $version_removed
        );

        trigger_error(namespace\plugin('Name') . ': ' . $msg, \E_USER_DEPRECATED);

    }

}


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
 * @since 1.9
 *    - namespace\cookie('user_id', [user id]) action sets the user id ...
 * @since 1.11
 *    - namespace\cookie('name') action returns the cookie name.
 *    - namespace\cookie('size') action returns the raw cookie size.
 *    - Added cookie compression for JSON string encoded data exceeding 1kb.
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
    // @var int
    static $user_id;

    // cookie values ...
    // @var array
    static $values;

    // @note get_current_user_id() can return bogus id when WC functions are in the backtrace when this function is called! Setting user id early appears to fix this.
    if (!isset($user_id)) $user_id = get_current_user_id();

    // cookie name 1.7+
    $name = 'bmc_woocom_multisession_' . $user_id . '_' . \COOKIEHASH;

    // old cookie name 1.6.1-
    $old_name = 'bmc_woocom_multisession_' . \COOKIEHASH;

    // preset actions ...
    switch ($action) {

        // get / set user id ...
        case 'user_id':

            // setter ...
            if (func_num_args() > 1) {

                // set user id ...
                $user_id = (integer)func_get_arg(1);

                // unset values to update from cookie ...
                $values = null;

                // set action back to default null ...
                $action = null;

            } // getter ...
            else {
                return $user_id;
            }

            break;

        // get cookie name ...
        case 'name':
            return $name;
            break;

        // get cookie size ...
        case 'size':
            return (isset($_COOKIE[$name]) ? strlen($_COOKIE[$name]) : 0);
            break;

    }

    // cookie values not set ? set now ...
    if (!isset($values)) {

        $values = array();

        // updated 1.7+ cookie not set ? set from old cookie if one exists ...
        if (isset($_COOKIE[$old_name]) && !isset($_COOKIE[$name])) {
            $_COOKIE[$name] = $_COOKIE[$old_name];
        }

        // derive session values from cookie ...
        if (isset($_COOKIE[$name])) {

            // raw data ...
            $values = $_COOKIE[$name];

            // compressed ...
            if (strpos($values, 'gz:') === 0) {
                $values = substr($values, 3);
                $values = base64_decode($values);
                $values = gzuncompress($values);
            } // uncompressed ...
            else {
                $values = base64_decode($values);
            }

            // decode JSON ...
            $values = json_decode($values, true);

        }

        // ensure array ...
        if (!is_array($values)) {
            $values = array();
        }

        // required defaults ...
        $values = array_merge(array(
            // bogus cart user id ...
            'customer_id' => 0,

            // key to sync carts under ...
            'key' => '',

            // last modified unix timestamp ...
            'modified' => 0,

            // array of cart [id] = { properties }
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

        $expires = time() - \YEAR_IN_SECONDS;

        // delete session from user meta ...
        if (namespace\option('sync_per_user')) delete_user_meta($user_id, '_woocom_multisession');

        // delete session from transient ...
        if ($values['key']) delete_transient(namespace\session_key($values['key']));

    } // set expires to a year and save session ...
    else {

        $values['modified'] = time();
        $values['version'] = namespace\plugin('Version');
        $values['user_id'] = $user_id;

        $expires = time() + \YEAR_IN_SECONDS;

        namespace\set_session($values, $values['key'], $user_id);

    }

    // delete old cookie if one exists ...
    if (isset($_COOKIE[$old_name])) {
        unset($_COOKIE[$old_name]);
        setcookie($old_name, '', -1, '/');
    }

    // sort carts by time ...
    if (namespace\option('sort_carts')) {
        uksort($values['carts'], __NAMESPACE__ . '\uksort_carts');
        uasort($values['carts'], __NAMESPACE__ . '\uasort_carts_by_time');
    } // sort carts by name ...
    else uasort($values['carts'], __NAMESPACE__ . '\uasort_carts_by_name');

    // JSON encode values ...
    $value = json_encode($values);

    // more than 1kb of data ? compress / some performance degradation ...
    if (strlen($value) > 1024) {
        $value = gzcompress($value, 9);
        $value = 'gz:' . base64_encode($value);
    } // leave uncompressed / better performance ...
    else {
        $value = base64_encode($value);
    }

    // set session to cookie ...
    return setcookie($name, $value, $expires, '/');

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
        && set_transient(namespace\session_key($key, $user_id), $session, \YEAR_IN_SECONDS)
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
 *    - customer id (also cart / session / user id)
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
 * @note: This id will be recorded in the user meta table (updated to custom sessions table as of WooCommerce 2.5.x). In the unlikely event that it corresponds with an actual user id, it will set the cart for that user. However, it is assumed that by opting to using this plugin that the Wordpress installation has few registered users.
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
        $name = __('Order', 'woocommerce') . ' #' . (namespace\num_carts() + 1);
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
 * Return the number of carts.
 *
 * @return integer
 * @since 1.10
 *
 */
function num_carts()
{
    return count(namespace\cookie('carts'));
}


/**
 * uksort() callback to move the current cart to the top of the cart list and sort the rest from newest to oldest.
 *
 * @since 1.7
 * @deprecate 1.11 / using uasort_carts_by_time() w/new cart 'time' parameter
 * @remove 2.0
 */
function uksort_carts($a, $b)
{
    if ($a == namespace\customer_id()) return -1;
    elseif ($b == namespace\customer_id()) return 1;
    elseif ($a == $b) return 0;
    return ($a > $b) ? -1 : 1;
}


/**
 * uasort() callback to sort carts according to time parameter.
 *
 * @since 1.11
 */
function uasort_carts_by_time($a, $b)
{
    $a_time = isset($a['time']) ? $a['time'] : 0;
    $b_time = isset($b['time']) ? $b['time'] : 0;
    if ($a_time == $b_time) return 0;
    return ($a_time > $b_time) ? -1 : 1;
}


/**
 * uasort() callback to sort carts according to name parameter.
 *
 * @since 1.11
 */
function uasort_carts_by_name($a, $b)
{
    $a_name = isset($a['name']) ? $a['name'] : '';
    $b_name = isset($b['name']) ? $b['name'] : '';
    return strcmp($a_name, $b_name);
}


/**
 * Return items from a persistent cart.
 *
 * @param integer|null $cart_id
 *    - Bogus customer id for the cart desired.
 * @return array
 * @since 1.4
 * @since 1.11
 *    - Attempt items retrieval from WooComm session before user meta persistent cart in WooComm 3.0+.
 *
 */
function get_cart($cart_id = null)
{

    // default to current customer id ...
    if (empty($cart_id)) $cart_id = namespace\customer_id();

    // get cart items ...
    if (
        (/**
         * Session from woocommerce's custom sessions table ...
         * @see woocommerce/includes/class-wc-session-handler.php get_session()
         */
            (
                namespace\wc_version('3.0', '>=')
                && ($session = WC()->session->get_session($cart_id))
            )

            /**
             * Session from user meta w/blog_id / since 3.x? ...
             * @see woocommerce/includes/class-wc-cart-session.php get_cart_from_session()
             */
            || ($session = get_user_meta($cart_id, '_woocommerce_persistent_cart_' . get_current_blog_id(), true))

            // session from user meta / original format ...
            || ($session = get_user_meta($cart_id, '_woocommerce_persistent_cart', true))
        )
        && isset($session['cart'])
        && ($items = maybe_unserialize($session['cart'])) // @note woocom double serializes this?!
    ) return $items;

    // no items ? return empty array ...
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
    if (namespace\is_user($cart_id)) return false;

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
 * @return array
 * @since 1.8
 * @since 1.11
 *    - Added 'time' cart parameter to record last selection/modification times, sort by.
 *
 */
function create_cart($params = array())
{

    // default cart parameters ...
    $cart = array(
        'name' => '',
        'mode' => 'cart',
        'time' => time()
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
 * @since 1.10
 *    - abandoned depreciated OPTION constant for hardcoded prefix instead.
 *
 */
function option_name($key)
{
    return 'bmc_woocom_multisession_' . $key;
}


/**
 * Default value (or values) for options.
 *
 * @param null|string $key Option name suffix.
 * @return mixed Array of defaults if no key is passed, default value if it exists or null of it does not exist.
 * @since 1.11
 *    - added max_carts option.
 *
 * @since 1.4
 * @since 1.8
 *    - added sort_carts option.
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

        // maximum number of carts a user can have / 0 = limited only by data size
        'max_carts' => 10,

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
 * @since 1.11
 *    - added 'can_add_cart' option, true if user is allowed to add another cart ...
 *
 * @since 1.4
 * @since 1.7
 *    - added switch for keys, return true for 'woocom_multiaddr' if plugin installed.
 */
function option($key)
{

    switch ($key) {
        // true if WooCommerce Multiple Addresses plugin is installed.
        case 'woocom_multiaddr':
            $value = class_exists('WC_Multiple_addresses');
            break;

        // true if user is able to add another cart ...
        case 'can_add_cart':
            $value = (
                (    // no limit ...
                    namespace\option('max_carts') == 0
                    // limit by number ...
                    || namespace\num_carts() < namespace\option('max_carts')
                )
                // limit by session size ...
                && namespace\cookie('size') < 2048
            );
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
    $trace = debug_backtrace(\DEBUG_BACKTRACE_IGNORE_ARGS);

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
    return defined('\WC_VERSION') ? version_compare(\WC_VERSION, (string)$version, $op) : false;
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


/**
 * Returns whether a user is real (has a record) or not.
 * This replaces WordPress' get_user_by() that we've compromised in order to validate against WooCommerce's user checks.
 *
 * @param integer $user_id
 * @return integer|null
 *    - the verified user id if true, null if false.
 * @since 1.11
 *
 */
function is_user($user_id)
{
    global $wpdb;
    return $wpdb->get_var($wpdb->prepare("SELECT ID FROM {$wpdb->users} WHERE ID = %d", $user_id));
}


/**
 * Returns meta data for a user by id as get_metadata() would.
 * Used by filter_get_user_meta() to bypass get_metadata().
 * @param integer $user_id
 * @param bool $flatten
 * @return keyed array of numeric arrays of values
 * @since 1.11
 *
 * @see get_metadata()
 *
 */
function user_meta($user_id, $flatten = false)
{
    if (!($meta = wp_cache_get($user_id, 'user_meta'))) {
        $meta = update_meta_cache('user', array($user_id));
        $meta = $meta[$user_id];
    }
    if ($flatten) {
        $meta = array_map(function ($value) {
            return $value[0];
        }, $meta);
    }
    return $meta;
}


/**
 * Updates the cart user address meta data from checkout data.
 * Will NOT update cart addresses that match the default user address set under profile.
 * @param array $data
 *    - array containing the address fields/values from checkout
 * @param string $address
 *    - either 'shipping' or 'billing' (default)
 * @return bool
 *    - true if cart meta was updated
 * @since 1.11
 *
 * @see action_checkout_cart_meta()
 *
 */
function update_cart_address($data, $address = 'billing')
{

    // regular expression of data set keys to retrieve ...
    $rex = '/^' . $address . '_(first_name|last_name|company|address_1|address_2|city|state|postcode|country|phone|email)$/';

    // get data sets ...
    $cart_addr = namespace\preg_grep_keys($rex, $data);
    $user_addr = namespace\preg_grep_keys($rex, namespace\user_meta(namespace\cookie('user_id'), true));

    // arrays match ? don't update ...
    if ($cart_addr == $user_addr) return false;

    // get all keys in both arrays ...
    $keys = array_unique(array_merge(array_keys($cart_addr), array_keys($user_addr)));

    // no keys for some ungodly reason ? don't update ...
    if ($key = current($keys) === false) return false;

    // assume the addresses are the same / not updating ...
    $update = false;

    // compare the data sets ...
    do {

        // addresses are different if ...
        if (
            // user is missing key ...
            !isset($user_addr[$key])
            // cart is missing key ...
            || !isset($cart_addr[$key])
            // cart and user values don't match ...
            || $user_addr[$key] != $cart_addr[$key]
        ) {
            $update = true;
            break;
        }

    } while (($key = next($keys)) !== false);

    // updating ? run against user meta ...
    if ($update)
        foreach ($cart_addr as $key => $value)
            update_user_meta(namespace\customer_id(), $key, $value);

    return $update;

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
        $cart_ids = $wpdb->get_col($wpdb->prepare("
			SELECT
				_umeta.user_id
			FROM
				{$wpdb->usermeta} _umeta
				LEFT JOIN {$wpdb->users} _u ON (_u.ID = _umeta.user_id)
			WHERE
				/* has a cart set by woocom */
				_umeta.meta_key IN ('_woocommerce_persistent_cart', '_woocommerce_persistent_cart_%d')

				/* bogus user! no user record associated with user meta */
				AND ISNULL(_u.ID)
		", get_current_blog_id()));
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
 * Merge items in anonymous cart with current cart on login.
 * Otherwise, WooCom simply replaces the contents of the current cart with the contents of the anon cart.
 *
 * @param string $user_login
 *    - this is the username or email address the user logged in with ...
 * @param WP_User $user
 *    - user object for the newly logged in user ...
 * @since 1.9
 *
 */
add_action('wp_login', __NAMESPACE__ . '\action_login', 10, 2);
function action_login($user_login = '', $user = null)
{

    // exit unless user object is set ...
    if (!(is_object($user) && $user instanceof \WP_User)) return;

    // explicitly set new user id, returns values (default action) ...
    $session = namespace\cookie('user_id', $user->ID);

    // explicitly set customer id to set current cart ...
    // this will have been set to zero initially as a non-auth user ...
    namespace\customer_id($session['customer_id']);

    // get current cart items ...
    $items = namespace\get_cart();

    /**
     * Copy items to WooCom cart - still currently anonymous.
     * WooCom will then automatically replace the items in our current cart with items from this anon cart, effectively merging them.
     * @see woocommerce/includes/class-wc-cart.php WC_Cart::persistent_cart_update()
     */
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

    // deactivate plugin if ...
    if (
        // require WooCommerce ...
        !function_exists('WC')

        // require WooCommerce 2.3+
        || namespace\wc_version('2.3', '<')

        // require PHP 5.3+
        || version_compare(phpversion(), '5.3', '<')
    ) {
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

    // require auth'd user ...
    if (!is_user_logged_in()) return;

    // AJAX hook ...
    add_action('wp_ajax_bmc_woocom_multisession', __NAMESPACE__ . '\action_ajax');

    // cron hook ...
    add_action(__NAMESPACE__ . '\cron', __NAMESPACE__ . '\action_cron');

    // update current cart meta info when user meta is updated ...
    add_action('added_user_meta', __NAMESPACE__ . '\action_updated_user_meta', 11, 4);
    add_action('updated_user_meta', __NAMESPACE__ . '\action_updated_user_meta', 11, 4);

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

    // tells WooCommerce to update primary user if sync'd w/single cart / also updates cart meta in legacy WooComm ...
    add_filter('woocommerce_checkout_update_customer_data', __NAMESPACE__ . '\filter_checkout_cart_meta', 99, 2);

    // update bogus user (cart) meta on checkout / handled by filter_checkout_cart_meta() in legacy WooComm ...
    if (namespace\wc_version('3.0', '>=')) add_action('woocommerce_checkout_update_user_meta', __NAMESPACE__ . '\action_checkout_cart_meta', 99, 2);

    // save and restore carts in storage bin mode before and after cart is emptied ...
    //add_action('woocommerce_before_checkout_form', __NAMESPACE__.'\action_save_bin'); // called by other actions now ...
    add_action('woocommerce_cart_emptied', __NAMESPACE__ . '\action_restore_bin');

    // display account features (orders) per cart (rather than per user) ...
    if (!namespace\option('account_per_user')) {

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
        (
            namespace\is_customer()
            && namespace\option('customer_toolbar')
        )
        || namespace\option('carts_menu')
    ) add_action('admin_bar_menu', __NAMESPACE__ . '\action_admin_bar', 99, 1);

    // restrict account access ...
    if (!namespace\option('account_access')) {
        // call these filters late to catch what themes or other plugins might modify ...
        add_filter('wc_get_template', __NAMESPACE__ . '\filter_wc_template', 99, 3);
        add_filter('woocommerce_account_menu_items', __NAMESPACE__ . '\filter_account_menu_items', 99, 1);
    }

}


/**
 * Hooks that need to be called before init.
 *
 * @since 1.9
 * @since 1.10
 *    - switched from after_setup_theme to plugins_loaded hook (called earlier)
 */
add_action('plugins_loaded', __NAMESPACE__ . '\action_plugins_loaded');
function action_plugins_loaded()
{

    /**
     * ChromeLogger available ? Load debug script.
     * @since 1.12
     */
    if (function_exists('BurningMoth\ChromeLogger\console')) include trailingslashit(__DIR__) . 'bmc_woocom_multisession-debug.php';

    /**
     * Current plugin version used w/enqueue_scripts.
     * @since 1.3
     * @depreciated 1.9
     *    - use plugin('Version') instead ...
     * @var string VERSION
     */
    if (!defined(__NAMESPACE__ . '\VERSION')) define(__NAMESPACE__ . '\VERSION', namespace\plugin('Version'));

    /**
     * Used to prefix option names.
     * @since 1.4
     * @depreciated 1.10
     *    - use plugin('TextDomain') instead.
     * @var string OPTION
     */
    if (!defined(__NAMESPACE__ . '\OPTION')) define(__NAMESPACE__ . '\OPTION', 'bmc_woocom_multisession');

    /**
     * Text domain.
     * @since 1.4
     * @depreciated 1.9
     *    - use plugin('TextDomain') instead.
     * @var string DOMAIN
     */
    if (!defined(__NAMESPACE__ . '\DOMAIN')) define(__NAMESPACE__ . '\DOMAIN', namespace\plugin('TextDomain'));

    // require auth'd user ...
    if (!is_user_logged_in()) return;

    // loads the handler override ...
    add_action('before_woocommerce_init', __NAMESPACE__ . '\action_before_woocom_init');

    // returns the session override class name ...
    add_filter('woocommerce_session_handler', __NAMESPACE__ . '\filter_woocom_session_handler');

    // returns additional faked meta data to validate our bogus users in multisite mode ...
    // integrate with WooCommerce Multiple Addresses plugin.
    // route woocomm cart requests from user account to cart user.
    add_filter('get_user_metadata', __NAMESPACE__ . '\filter_get_user_meta', 99, 4);

    // route woocom cart update|inserts from user account to cart user.
    add_filter('update_user_metadata', __NAMESPACE__ . '\filter_update_user_meta', 99, 5);
    add_filter('add_user_metadata', __NAMESPACE__ . '\filter_add_user_meta', 99, 5);

}


/**
 * Load the namespaced WC_Session_Handler class override.
 *
 * @return void
 */
function action_before_woocom_init()
{
    include_once 'class-wc-session-handler.php';
}


/**
 * Return our customized namespaced session handler class name.
 *
 * @return WC_Session_Handler
 */
function filter_woocom_session_handler($session_class)
{
    $session_override = __NAMESPACE__ . '\WC_Session_Handler';
    if (class_exists($session_override)) return $session_override;
    return $session_class;
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
 * Cron hook. Run maintenance.
 *
 * @since 1.8
 */
function action_cron()
{

    global $wpdb;

    // expire carts ...
    $results = $wpdb->get_results($wpdb->prepare("
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
			_umeta.meta_key IN ('_woocommerce_persistent_cart', '_woocommerce_persistent_cart_%d')

			/* no main user record - bogus user we're using for a cart! */
			AND ISNULL(_u.ID)

			/* cart does not have orders associated with it */
			AND NOT EXISTS( SELECT * FROM {$wpdb->postmeta} WHERE meta_key = '_cart_id' AND meta_value = _umeta.user_id )

			/* cart hasn't been modified in a year - the timestamp is not set for carts last used before v1.7 so use release date for that version */
			AND CURDATE() > DATE_ADD( IF( ISNULL(_umeta2.meta_value), DATE('2016-05-09'), FROM_UNIXTIME(_umeta2.meta_value) ), INTERVAL 1 YEAR )

		LIMIT 100
	", get_current_blog_id()));

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
    wp_enqueue_style('bmc-woocom-multisession', plugins_url('bmc_woocom_multisession.css', __FILE__), array('dashicons'), namespace\plugin('Version'));

    // load scripts ...
    wp_enqueue_script('bmc-woocom-multisession', plugins_url('bmc_woocom_multisession.js', __FILE__), array('jquery'), namespace\plugin('Version'));

    // localize scripts ...
    wp_localize_script('bmc-woocom-multisession', 'BMCWcMs', array(

        'ajaxurl' => admin_url('admin-ajax.php'),

        'nonce' => wp_create_nonce('ajax'),

        'session_key' => (string)namespace\cookie('key'),

        'localize' => array(

            'insert_prompt' => __('Name', 'woocommerce'),

            'insert_prompt_value' => __('New Cart', 'bmc-woocom-multisession'),

            'delete_confirm' => __('Are you sure you want to delete cart, %cart-name? Any associated addresses will be lost. This cannot be undone.', 'bmc-woocom-multisession'),

            'sync_prompt' => sprintf(__('Save or load carts to or from %s with a CaSe SeNsItIvE key phrase.', 'bmc-woocom-multisession'), get_bloginfo('name')),

            'sync_confirm' => __('Carts for key phrase "%session-name" exist. Do you wish to load these carts?', 'bmc-woocom-multisession'),

            'unsync_confirm' => __('This will NOT delete your carts. Carts saved on key phrase "%session-name" will remain available to load again for up to one year. Do you wish to proceed?', 'bmc-woocom-multisession')

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
    wp_enqueue_style('bmc-woocom-multisession-admin', plugins_url('bmc_woocom_multisession-admin.css', __FILE__), array(), namespace\plugin('Version'));

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
 * @since 1.11
 *    - Updated method that returns the cart page url, depreciated WooComm 2.5.
 *    - Moved carts sorting to cookie().
 *
 * @since 1.3
 */
function action_admin_bar($wp_admin_bar = null)
{

    if (!(is_object($wp_admin_bar) && $wp_admin_bar instanceof \WP_Admin_Bar)) return;

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
        'title' => __('Carts', 'bmc-woocom-multisession')
    ));

    // retrieve carts ...
    $carts = namespace\get_carts();

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
                'href' => (wc_version('2.5', '>=') ? wc_get_cart_url() : WC()->cart->get_cart_url()),
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
                    'title' => __('Select Cart', 'bmc-woocom-multisession')
                )
            ));

        }

        // update / edit name option ...
        $wp_admin_bar->add_node(array(
            'parent' => 'cart-' . $cart_id,
            'id' => 'cart-edit-' . $cart_id,
            'title' => __('Edit Name', 'bmc-woocom-multisession'),
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
            'title' => __('Shopping Cart', 'bmc-woocom-multisession'),
            'href' => '#mode',
            'meta' => array(
                'class' => 'cart-mode-cart',
                'onclick' => 'return BMCWcMs.command("mode", this);',
                'target' => $cart_id,
                'title' => __('Switch to Shopping Cart mode. Shopping carts are emptied upon checkout.', 'bmc-woocom-multisession')
            )
        ));

        // switch to storage bin mode ...
        $wp_admin_bar->add_node(array(
            'parent' => 'cart-' . $cart_id,
            'id' => 'cart-mode-bin-' . $cart_id,
            'title' => __('Storage Bin', 'bmc-woocom-multisession'),
            'href' => '#mode',
            'meta' => array(
                'class' => 'cart-mode-bin',
                'onclick' => 'return BMCWcMs.command("mode", this);',
                'target' => $cart_id,
                'title' => __('Switch to Storage Bin mode. Storage bins retain items after checkout.', 'bmc-woocom-multisession')
            )
        ));

        // clone cart option if there are less than 10 carts ...
        if (count($carts) < 10) {

            $wp_admin_bar->add_node(array(
                'parent' => 'cart-' . $cart_id,
                'id' => 'cart-clone-' . $cart_id,
                'title' => __('Clone Cart', 'bmc-woocom-multisession'),
                'href' => '#clone',
                'meta' => array(
                    'class' => 'cart-clone',
                    'onclick' => 'return BMCWcMs.command("clone", this);',
                    'target' => $cart_id,
                    'title' => __('Create a new cart with the items and addresses of this cart.', 'bmc-woocom-multisession')
                )
            ));

        }

        // set options for carts other than current ...
        if (!$current_cart) {

            // copy option ...
            $wp_admin_bar->add_node(array(
                'parent' => 'cart-' . $cart_id,
                'id' => 'cart-copy-' . $cart_id,
                'title' => __('Copy Items', 'bmc-woocom-multisession'),
                'href' => '#copy',
                'meta' => array(
                    'class' => 'cart-copy',
                    'onclick' => 'return BMCWcMs.command("copy", this);',
                    'target' => $cart_id,
                    'title' => __('Copy items from this cart to your current cart.', 'bmc-woocom-multisession')
                )
            ));

            // delete option ...
            $wp_admin_bar->add_node(array(
                'parent' => 'cart-' . $cart_id,
                'id' => 'cart-delete-' . $cart_id,
                'title' => __('Delete Cart', 'bmc-woocom-multisession'),
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

    // new cart if within max # of carts and session size (2kb) allowed ...
    if (namespace\option('can_add_cart')) {

        $wp_admin_bar->add_node(array(
            'parent' => 'carts',
            'id' => 'cart-new',
            'title' => __('New Cart', 'bmc-woocom-multisession'),
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
            'title' => ($session_key ? __('Carts Saved', 'bmc-woocom-multisession') : __('Save / Load Carts', 'bmc-woocom-multisession')),
            'href' => '#sync',
            'meta' => array(
                'title' => sprintf(
                    ($session_key ? __('Carts saved to %s.', 'bmc-woocom-multisession') : __('Save or load carts to or from %s with a key phrase.', 'bmc-woocom-multisession')),
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
                'title' => __('Unsave Carts', 'bmc-woocom-multisession'),
                'href' => '#unsync',
                'meta' => array(
                    'title' => sprintf(
                        esc_attr__('Stop saving carts to %s?', 'bmc-woocom-multisession'),
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

    global $wpdb;

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

            if ($_POST['command'] == 'insert') {
                $carts[$_POST['id']] = namespace\create_cart(array('name' => $_POST['name']));
            } elseif ($_POST['command'] == 'insertpos') {
                $carts[$_POST['id']] = namespace\create_cart(array('name' => $_POST['name'] . '-pos'));
            } elseif ($_POST['command'] == 'insertcomponent') {
                $carts[$_POST['id']] = namespace\create_cart(array('name' => $_POST['name'] . '-component'));
            }
        // create new cart ...


        // new cart - selects new cart upon creation ...
        case 'insertpos':

            // sets post id to select to (below) - needs to be string for consistently ...
            $_POST['id'] = (string)namespace\generate_customer_id();

            // create new cart ...
            if ($_POST['command'] == 'insertpos') {
                $carts[$_POST['id']] = namespace\create_cart(array('name' => $_POST['name'] . '-pos'));
            } elseif ($_POST['command'] == 'insert') {
                $carts[$_POST['id']] = namespace\create_cart(array('name' => $_POST['name']));
            }

        // new cart - selects new cart upon creation ...
        case 'insertcomponent':

            // sets post id to select to (below) - needs to be string for consistently ...
            $_POST['id'] = (string)namespace\generate_customer_id();

            // create new cart ...
            if ($_POST['command'] == 'insertcomponent') {
                $carts[$_POST['id']] = namespace\create_cart(array('name' => $_POST['name'] . '-component'));
            } elseif ($_POST['command'] == 'insert') {
                $carts[$_POST['id']] = namespace\create_cart(array('name' => $_POST['name']));
            }


        // pick cart ...
        case 'select':

            // set up customer ids ...
            $data['old_customer_id'] = namespace\customer_id();
            $data['customer_id'] = namespace\customer_id($_POST['id']);

            // if the old customer id isn't among the carts, save it now ...
            if (!isset($carts[$data['old_customer_id']])) {
                $carts[$data['old_customer_id']] = namespace\create_cart();
            }

            // update cart time ...
            $carts[$_POST['id']]['time'] = time();

            // update carts ...
            namespace\cookie(array('carts' => $carts));

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
         * @since 1.11
         *    - Added cart item key to table rows for auto update when WooComm updates via ajax.
         *
         * @var integer $id cart / customer id
         *
         * @since 1.6
         */
        case 'load':

            $html = sprintf('<table><thead><th colspan="2">%s</th><th>%s</th></thead><tbody>',
                __('Item Name', 'bmc-woocom-multisession'),
                __('Qty', 'bmc-woocom-multisession')
            );

            if ($items = namespace\get_cart($_POST['id'])) {

                foreach ($items as $item) {

                    // just get normal post data for the product - WooCom ends up doing this anyway, we just cut out the middleman ...
                    $product = get_post($item['product_id']);

                    $html .= sprintf('<tr class="cart-item-%s"><td><a href="%s">%s</a></td><td></td><td>%d</td></tr>',
                        $item['key'],
                        get_permalink($product->ID),
                        $product->post_title,
                        get_post_meta($product->ID, 'quantity', true)
                    );

                }

            } else {

                $html .= sprintf('<td colspan="3" align="center">%s</td>',
                    __('Your cart is currently empty.', 'woocommerce')
                );

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
                    . sprintf('<option value="%d">- %s -</option>', namespace\customer_id(), __('Move To Cart', 'bmc-woocom-multisession'));

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
function filter_plugin_links($links, $file = '')
{

    if (plugin_basename(__FILE__) == $file) {

        $links = array_merge($links, array(
            'documentation' => sprintf(
                '<a class="thickbox" href="%s">%s</a>',
                plugins_url('README.html?TB_iframe=true&version=' . namespace\plugin('Version'), __FILE__),
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
        namespace\plugin('TextDomain'),
        admin_url('admin.php?page=wc-settings&tab=general'),
        namespace\plugin('TextDomain'),
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
        'id' => namespace\plugin('TextDomain'),
        'type' => 'title',
        'name' => __('Multiple Carts Per User Options', 'bmc-woocom-multisession'),
        'desc' => sprintf(
            __('Settings for the <a id="%s" href="%s">Multiple Carts Per User</a> plugin.', 'bmc-woocom-multisession'),
            namespace\plugin('TextDomain'),
            admin_url('plugins.php#' . namespace\plugin('TextDomain'))
        )
    );

    // carts menu ...
    $settings[] = array(
        'id' => namespace\option_name('carts_menu'),
        'default' => namespace\option_default('carts_menu'),
        'type' => 'checkbox',
        'name' => __('Carts Menu', 'bmc-woocom-multisession'),
        'desc' => __('Adds Carts menu to the Admin Toolbar.', 'bmc-woocom-multisession')
    );

    // customer toolbar ...
    $settings[] = array(
        'id' => namespace\option_name('customer_toolbar'),
        'default' => namespace\option_default('customer_toolbar'),
        'type' => 'checkbox',
        'name' => __('Customer Toolbar', 'bmc-woocom-multisession'),
        'desc' => __('Displays the Admin Toolbar for Customers with Carts menu and link to WooCommerce account page.', 'bmc-woocom-multisession')
    );

    // sort current cart to the top ...
    $settings[] = array(
        'id' => namespace\option_name('sort_carts'),
        'default' => namespace\option_default('sort_carts'),
        'type' => 'checkbox',
        'name' => __('Current Cart On Top', 'bmc-woocom-multisession'),
        'desc' => __('Display the current cart at the top of the Carts menu or widget and order carts below it by last used.', 'bmc-woocom-multisession')
    );

    // maximum number of carts ...
    $settings[] = array(
        'id' => namespace\option_name('max_carts'),
        'default' => namespace\option_default('max_carts'),
        'type' => 'number',
        'css' => 'max-width:4em;',
        'name' => __('Maximum Carts', 'bmc-woocom-multisession'),
        'desc' => __('Set to zero (0) to limit only by the maximum carts session data size.', 'bmc-woocom-multisession'),
        'desc_tip' => __('Sets the maximum number of carts users are limited to.', 'bmc-woocom-multisession')
    );

    // sync settings ...
    $settings[] = array(
        'id' => namespace\option_name('sync_per_user'),
        'default' => namespace\option_default('sync_per_user'),
        'type' => 'checkbox',
        'name' => __('Link Carts per User', 'bmc-woocom-multisession'),
        'desc' => __('Automatically link carts sessions to user accounts rather than optionally by key phrase. Customers will use the same carts as other customers logged into the same user account.', 'bmc-woocom-multisession')
    );

    // account display per user (true), cart (false) ...
    $settings[] = array(
        'id' => namespace\option_name('account_per_user'),
        'default' => namespace\option_default('account_per_user'),
        'type' => 'checkbox',
        'name' => __('Account Features per User', 'bmc-woocom-multisession'),
        'desc' => __('Display account features (orders, downloads) by user rather than by cart. Customers will see the same orders and downloads as other customers logged into the same user account.', 'bmc-woocom-multisession')
    );

    // account access ...
    $settings[] = array(
        'id' => namespace\option_name('account_access'),
        'default' => namespace\option_default('account_access'),
        'type' => 'checkbox',
        'name' => __('Customer Account Access', 'bmc-woocom-multisession'),
        'desc' => __('Allow customers to update the user account they\'re logged into.', 'bmc-woocom-multisession')
    );

    // delete data on uninstall ...
    $settings[] = array(
        'id' => namespace\option_name('uninstall_data'),
        'default' => namespace\option_default('uninstall_data'),
        'type' => 'checkbox',
        'name' => __('Delete Data On Uninstall', 'bmc-woocom-multisession'),
        'desc' => __('Remove all settings and carts data when uninstalling the plugin.', 'bmc-woocom-multisession')
    );

    $settings[] = array(
        'id' => namespace\plugin('TextDomain'),
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
function action_order_meta($order_id, $form = array())
{
    update_post_meta($order_id, '_cart_id', namespace\customer_id());
}


/**
 * Bypasses updating user account if not sync'd user or more than one cart on checkout.
 * @param bool $update
 * @param WC_Checkout $wc_checkout
 * @return bool
 *    - whether or not to update primary user meta
 * @see woocommerce/includes/class-wc-checkout.php WC_Checkout::create_order() or WC_Checkout::process_customer() since 3.0
 * @filter woocommerce_checkout_update_customer_data
 *
 * @since 1.7
 * @since 1.11
 *    - moved meta update functionality to action_checkout_cart_meta()
 *    - sync'd user can't have more than one cart to return true for WooComm to update the primary user account
 *
 */
function filter_checkout_cart_meta($update, $wc_checkout = null)
{

    // not a wc_checkout object ? return now ...
    if (!$wc_checkout instanceof \WC_Checkout) return $update;

    // WooCommerce -3.0 / WC_Checkout::$posted depreciated to legacy, functionality moved to @action woocommerce_checkout_update_user_meta
    if (namespace\wc_version('3.0', '<')) namespace\action_checkout_cart_meta(namespace\cookie('user_id'), $wc_checkout->posted);

    // update user account meta if sync'ing per user account and has only a single cart ...
    return ($update && namespace\option('sync_per_user') && namespace\num_carts() == 1);
}


/**
 * Updates bogus cart user account with billing/shipping data on checkout.
 * @param integer $customer_id (this will be for the actual user)
 * @param array $data
 * @see woocommerce/includes/class-wc-checkout.php WC_Checkout::process_customer()
 * @action woocommerce_checkout_update_user_meta
 * @note hooked for WooComm 3.0+ (see action_init()), passed to directly from filter_checkout_cart_meta() for prior legacy versions
 *
 * @since 1.11
 *
 */
function action_checkout_cart_meta($customer_id, $data = array())
{

    // billing meta ...
    if (
        // no woocom multi address plugin ?
        !namespace\option('woocom_multiaddr')

        // has multiaddr, not using a predefined billing addr. ?
        || empty($data['billing_alt']) // set by multiaddr.
    ) namespace\update_cart_address($data, 'billing');

    // shipping meta ...
    if (
        // shipping to different address ?
        !empty($data['ship_to_different_address'])

        && (
            // no multiaddr ?
            !namespace\option('woocom_multiaddr')
            // has multiaddr, not using a predefined shipping addr. ?
            || empty($data['shipping_alt'])    // set by multiaddr.
        )
    ) namespace\update_cart_address($data, 'shipping');

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
 * @param array $sql
 * @param array $queries
 * @param string $type
 * @param string $table
 * @param string $column
 * @param WP_Query $wp_query
 * @return array
 * @see woocommerce/includes/wc-order-functions.php wc_get_orders()
 * @see wp-includes/class-wp-meta-query.php WP_Meta_Query::get_sql()
 *
 * @since 1.6
 *
 */
function filter_meta_sql($sql, $queries = array(), $type = '', $table = '', $column = '', $wp_query = null)
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
function filter_page_title_append_cart_name($title, $post_id = 0)
{

    if (
        // more than one cart ...
        namespace\num_carts() > 0

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
        $title .= sprintf(' - %s', namespace\current_cart_name());
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
function filter_wc_template($template_path, $template_name = '', $args = array())
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
 * Overrides/modifies certain user meta calls.
 * @return null|array
 * @since 1.7
 * - integration with WooCommerce Multiple Addresses plugin.
 * @since 1.11
 *    - fakes additional meta values required for bogus user id's to be validated in multisite mode, required by some WooCommerce functions.
 *
 * @see wp-includes/meta.php get_metadata()
 *
 */
function filter_get_user_meta($value, $user_id = 0, $meta_key = '', $single = false)
{

    global $current_user, $wpdb;

    /**
     * Integrate addresses associated with carts with WooCommerce Multiple Addresses plugin via bypassing the plugin's get_user_meta() calls.
     * @see wp-content/plugins/woocommerce-multiple-addresses/class-woocommerce-multiple-addresses.php
     */
    if (
        // woocom multiaddr uses this key to save a numeric array of addresses ...
        $meta_key == 'wc_multiple_shipping_addresses'

        // must be called from checkout / ajax context to bypass !
        // otherwise this will add the cart addresses to the woocom multiaddr record itself when configured which we do not want ...
        && namespace\caller('WC_Multiple_addresses::add_dd_to_checkout_fields|WC_Multiple_addresses::ajax_checkout_change_shipping_address')
    ) {

        // retrieve addresses via database as using get_user_meta here or will result in an infinity loop!
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
            if (!($meta = namespace\user_meta($cart_id))) continue;

            // process billing address ...
            if ($keys = array_intersect_key($meta, $billing_shipping)) {

                $address = array(
                    // label from cart name ...
                    'label' => $cart['name'] . ' (Billing)',

                    // alternate # ...
                    'shipping_alt' => strval(count($addresses) + 1)
                );

                // default if current cart ...
                if (namespace\customer_id() == $cart_id) $address['shipping_address_is_default'] = 'true';

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
                    'label' => $cart['name'] . ' (Shipping)',

                    // alternate # ...
                    'shipping_alt' => strval(count($addresses) + 1)
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

    } /**
     * Fakes additional metadata required for WooCommerce WC_Customer_Data_Store::read() (and maybe others!?) to validate our bogus cart users in multisite mode and inherit correct addresses.
     * @note This is required for the billing/shipping addresses to display under My Account
     *
     * @since 1.11
     *
     * @see woocommerce/includes/class-wc-customer.php WC_Customer::__construct()
     * @see woocommerce/includes/class-wc-data-store.php calls WC_Data_Store::load();
     * @see woocommerce/data-stores/class-wc-customer-data-store.php WC_Customer_Data_Store::read()
     * @see is_user_member_of_blog()
     */
    elseif (

        // getting all meta keys ...
        !$single
        && empty($meta_key)

        // current user is overridden ...
        && $current_user instanceof namespace\WP_User
        && (
            // user id refers to actual user ...
            ($is_user = ($user_id == $current_user->wp_user->ID))

            // user id refers to bogus cart user ...
            || $user_id == namespace\customer_id()
        )

    ) {

        // reassign id's to clarify ...
        if ($is_user) $cart_id = namespace\customer_id();
        else {
            $cart_id = $user_id;
            $user_id = $current_user->wp_user->ID;
        }

        // merge user and cart meta data ...
        $meta = array_replace(
            namespace\user_meta($user_id),
            namespace\user_meta($cart_id)
        );

        return $meta;

    } /**
     * Ensure that requests for user persistent cart route to|from bogus user meta.
     * @note Because WP_User is not necessarily overridden in some cases this meta data is called, this catches and resolves that.
     *
     * @since 1.12
     */
    elseif (

        // getting persistent cart ...
        strpos($meta_key, '_woocommerce_persistent_cart') === 0

        // passed user id matches logged in user id ...
        && namespace\cookie('user_id') === $user_id

    ) {

        // return cart from bogus user ...
        $value = get_user_meta(namespace\customer_id(), $meta_key, $single);

        // wrap in array if single ...
        if ($single) $value = array($value);

    }


    return $value;
}


/**
 * Ensure user persistent cart updates route to bogus user meta.
 * @note Because WP_User is not necessarily overridden in some cases this meta data is called, this catches and resolves that.
 *
 * @since 1.12
 */
function filter_update_user_meta($value, $user_id = 0, $meta_key = '', $meta_value = null, $prev_value = '')
{

    if (

        // updating persistent cart ...
        strpos($meta_key, '_woocommerce_persistent_cart') === 0

        // passed user id matches logged in user id ...
        && namespace\cookie('user_id') === $user_id

    ) {
        $value = update_user_meta(namespace\customer_id(), $meta_key, $meta_value, $prev_value);
    }

    return $value;
}


/**
 * Ensure user persistent cart inserts route to bogus user meta.
 * @note Because WP_User is not necessarily overridden in some cases this meta data is called, this catches and resolves that.
 *
 * @since 1.12
 */
function filter_add_user_meta($value, $user_id = 0, $meta_key = '', $meta_value = null, $unique = false)
{

    if (

        // updating persistent cart ...
        strpos($meta_key, '_woocommerce_persistent_cart') === 0

        // passed user id matches logged in user id ...
        && namespace\cookie('user_id') === $user_id

    ) {
        $value = add_user_meta(namespace\customer_id(), $meta_key, $meta_value, $unique);
    }

    return $value;

}


/**
 * Updates current cart meta info whenever user meta is updated/added (cart items, addresses, etc.).
 * This is useful connective tissue for reigning in possible wayward carts and maintenance in the future.
 *
 * @since 1.7
 */
function action_updated_user_meta($meta_id = 0, $user_id = 0, $meta_key = '', $_meta_value = '')
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
        update_user_meta($user_id, '_cart_version', namespace\plugin('Version'));

        // cart session key ...
        update_user_meta($user_id, '_cart_session_key', namespace\cookie('key'));

    }

}


/**
 * Add switch cart action to orders list.
 * This creates a link that jquery converts to a list.
 *
 * @since 1.7
 * @since 1.10
 *    - added check for more than one cart (otherwise this is a useless feature)
 *    - added wc_version 3.0+ check to resolve deprecation notice.
 *
 * @see woocommerce/templates/myaccount/orders.php
 */
function filter_order_actions($actions, $wc_order = null)
{

    if (
        // not an order object ?
        !(is_object($wc_order) && $wc_order instanceof \WC_Order)

        // less than two carts ?
        || namespace\num_carts() < 2
    ) return $actions;

    $actions[namespace\plugin('TextDomain') . '-cart-switch'] = array(
        'url' => '#',
        'name' => (namespace\wc_version('3.0', '>=') ? $wc_order->get_id() : $wc_order->id)
    );

    return $actions;
}


/**
 * Add switch list like the one output by filter_order_actions().
 *
 * @since 1.7
 * @since 1.10
 *    - added check for more than one cart (otherwise this is a useless feature)
 *    - updated markup to enclose in a section
 *    - updated cart switch class
 *
 * @see woocommerce/templates/myaccount/view-order.php
 */
function action_order_view($order_id = 0)
{

    // must have more than two carts for this feature to be useful ...
    if (namespace\num_carts() < 2) return;

    printf(
        '<section class="%s-order-view"><h2>%s</h2><a class="%s-cart-switch" href="#">%d</a></section>',
        namespace\plugin('TextDomain'),
        __('Shopping Cart', 'woocommerce'),
        namespace\plugin('TextDomain'),
        $order_id
    );

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

    /**
     * Don't restore if logging out / restoring the cart here will result in no items being retained when the user logs back in !
     * The WC_Session_Handler destroys the session on action wp_logout which is what we want!
     */
    if (did_action('wp_logout')) return;

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


/**
 * Override $current_user global object with our own imposter object that returns dynamic user IDs.
 * Effectively replaces functionality of pluggable wp_get_current_user() function, plays nicer with others!
 *
 * @note set_current_user hook called at the end of wp_set_current_user() (pluggable function!)
 * @note Considered determine_current_user filter called by _wp_get_current_user() but far too inflexible for our needs, currently.
 *
 * @since 1.10
 */
add_action('set_current_user', __NAMESPACE__ . '\action_set_current_user');
function action_set_current_user()
{

    global $current_user;

    if (
        // current user object is properly set ...
        is_object($current_user)
        && $current_user instanceof \WP_User

        // current user is logged in ...
        && $current_user->ID > 0

        // not in admin panel ...
        && !is_admin()
    ) {
        require_once 'class-wp-user.php';
        $current_user = new WP_User(clone $current_user);
    }

}


/**
 * Declare a Burning Moth Creation.
 *
 * @param array $creations
 *    - list of active Burning Moth plugin files.
 * @since 1.9
 *
 */
add_filter('burning_moth_creations', __NAMESPACE__ . '\filter_burning_moth');
function filter_burning_moth($creations)
{
    array_push($creations, __FILE__);
    return $creations;
}


