<?php
// If this file is called directly, abort.
if (! defined('WPINC')) {
    die;
}

/*----------------------------------------------------------------------------*
 * Shortcuts
 *----------------------------------------------------------------------------*/

define('STG_CATCHERS_HELPDESK_ADDON4_VERSION', '2.6.1');

define('STG_CATCHERS_HELPDESK_ADDON4_NAME', 'catchers-helpdesk-addon4');

define('STG_CATCHERS_HELPDESK_ADDON4_URL', trailingslashit(plugin_dir_url(__FILE__)));
define('STG_CATCHERS_HELPDESK_ADDON4_ROOT', trailingslashit(plugin_dir_path(__FILE__)));
define('STG_CATCHERS_HELPDESK_ADDON4_SALT_USER', '612hff54gf5gfff$44SAeehnm5G89jfF');

if(!defined('STG_CATCHERS_HELPDESK_NAME'))
    define('STG_CATCHERS_HELPDESK_NAME', 'catchers-helpdesk');

if(!defined('STG_CATCHERS_HELPDESK_NAME_PRO'))
    define('STG_CATCHERS_HELPDESK_NAME_PRO', 'catchers-helpdesk-premium');


/*----------------------------------------------------------------------------*
 * Shared functionality
 *----------------------------------------------------------------------------*/
/** @noinspection PhpIncludeInspection */
require_once STG_CATCHERS_HELPDESK_ADDON4_ROOT . '/helpers/tools-helpers.php';
