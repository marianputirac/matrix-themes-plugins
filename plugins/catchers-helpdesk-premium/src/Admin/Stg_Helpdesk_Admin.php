<?php

namespace StgHelpdesk\Admin;

use StgHelpdesk\Core\PostType\Stg_Helpdesk_Post_Type_Statuses;
use StgHelpdesk\Core\Stg_Helpdesk_Activator;
use StgHelpdesk\Helpers\Stg_Helper_Email;
use StgHelpdesk\Helpers\Stg_Helper_Logger;
use StgHelpdesk\Helpers\Stg_Helper_Saved_Replies;
use StgHelpdesk\Helpers\Stg_Helper_Custom_Forms;
use StgHelpdesk\Helpers\Stg_Helper_UploadFiles;
use StgHelpdesk\Ticket\Stg_Helpdesk_Admin_Ticket_Layout;
use StgHelpdesk\Ticket\Stg_Helpdesk_Filter;
use StgHelpdesk\Ticket\Stg_Helpdesk_MetaBoxes;
use StgHelpdesk\Ticket\Stg_Helpdesk_Ticket;
use StgHelpdesk\Ticket\Stg_Helpdesk_Ticket_Query;
use StgHelpdesk\Ticket\Stg_Helpdesk_TicketList;

use StgHelpdesk\Ticket\Stg_Helpdesk_TicketCategory;
use StgHelpdesk\Helpers\Stg_Helper_Export_Csv;


/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Plugin_Name
 * @subpackage Plugin_Name/admin
 */
class Stg_Helpdesk_Admin
{

    public static $nonceName = 'stgh_nonce';

    public static $customNonceAction = 'stgh_custom_action_nonce';

    public static $nonceAction = 'stgh_update_nonce';

    /**
     * Initialize the class and set its properties.
     */
    public function __construct()
    {
        if (stgh_request_is_not_ajax()) {
            add_action('admin_menu', array($this, 'registerMenu'));
            $this->manageRedirect();
            $this->registerCustomAction();
            $this->registerSettingsPage();
            $this->registerTicketLayout();
            $this->registerScripts();
            $this->registerStyles();
            $this->registerSaveTicketFields();
            $this->registerPostDataFilter();
            $this->registerFlushCacheUsersLists();
            $this->getTicketsCount();
            $this->registerBulkEditCustomBox();

            // Adds "Support Forum" link to the plugin action page
            add_filter('plugin_action_links_' . plugin_basename(STG_HELPDESK_ROOT_PLUGIN_FILENAME_AND_PATH), array($this, 'add_action_links'));

            if ('list' == $this->getMode()) {
                $this->registerAdminTicketListActions();
            } else {
                $this->registerAdminTicketExcerptActions();
            }

            add_filter('views_edit-' . STG_HELPDESK_POST_TYPE, array($this, 'stgh_filter_link'));

            add_filter('stgh_ticket_comment_actions', 'stgh_ticket_comment_actions', 10, 3);
            add_filter('stgh_ticket_actions', 'stgh_ticket_actions', 10, 3);

            if ($this->justActivated()) {
                add_action('admin_init', array($this, 'redirectToWelcomePage'), 12, 0);
            }
        } else {
            add_action('wp_ajax_send_test_email', array($this, 'ajaxSendTestEmail'));
        }
        $this->registerTicketQuery();

        if(!stgh_current_user_can('administrator')) {
            add_filter('editable_roles', array($this, 'remove_admin_role'));
            add_filter('views_users', array($this, 'remove_admins_view'));
            add_action('pre_user_query', array($this, 'remove_administrators'));
            add_filter('user_has_cap', array($this, 'not_edit_admin'), 10, 3);
        }

        // Show messages
        $status = get_option('stgh_mail_test_connection', null);
        if (!is_null($status)) {
            add_action('admin_notices', array($this, 'mailboxNotice'));
        }
        $status = get_option('stgh_get_mails_from_support', null);
        if (!is_null($status)) {
            add_action('admin_notices', array($this, 'getMailsFromSupportNotice'));
        }
        $status = get_option('stgh_send_gethelp_message', null);
        if (!is_null($status)) {
            add_action('admin_notices', array($this, 'getGetHelpMessageNotice'));
        }
        // End show messages

        add_action('post_edit_form_tag', array($this, 'addPostEnctype'));

        // Ajax actions
        add_action('wp_ajax_stgh-autocomplete', array($this, 'registerAutocomplete'));
	    add_action('wp_ajax_stgh-get-orders', array($this, 'getOrders'));
	    add_action('wp_ajax_stgh-kbautocomplete', array($this, 'kbAutocomplete'));
	    add_action('wp_ajax_nopriv_stgh-kbautocomplete', array($this, 'kbAutocomplete'));

	    add_action('wp_ajax_stgh-kbsaverange', array($this, 'kbSaverange'));
        add_action('wp_ajax_stgh_save_support_email_option', array($this, 'saveSupportEmail'));


        add_action('tf_pre_save_admin_stgh', array($this, 'tfSaveAdmin'), 10, 3);

        add_action('wp_ajax_stgh-get-savedreplies', array($this, 'getSavedReplies'));
        add_action('wp_ajax_stgh-get-savedrepliesmacros', array($this, 'getSavedRepliesMacros'));

        add_action('wp_ajax_stgh-get-qa_topics_list', array($this, 'getQAtopicsList'));

        add_action('wp_ajax_stgh-get-customfields', array($this, 'getCustomFields'));
        add_action('wp_ajax_stgh-get-savedreplytext', array($this, 'getSavedReplyText'));

        // Rating
        add_action('wp_ajax_stgh-rating-click', array($this, 'clickRating'));
        add_action('before_delete_post', array($this, 'deleteTicket'));
        add_filter('admin_footer_text', array($this, 'stgh_admin_footer_text'), 11);

        //add id label ticket
        add_filter('stgh_ticket_type_labels', function ($labels) {
            $labels['edit_item'] = !empty($_GET['post']) ? $labels['edit_item'] . ' #' . intval($_GET['post']) : $labels['edit_item'];
            return $labels;
        });


        add_action( 'delete_user', array($this, 'custom_remove_user'), 10, 2);


        add_filter( 'user_has_cap', array($this,'stgh_enable_ticket_create'), 10, 2);

    }

    function custom_remove_user($id, $reassign)
    {
        if ($id > 0 && $reassign > 0) {
            $userTickets = Stg_Helpdesk_Ticket::userTickets(-1, false, $id);

            if ($userTickets->have_posts()) {
                while ($userTickets->have_posts()) {
                    $userTickets->the_post();
                    $ticketId = get_the_ID();
                    update_post_meta($ticketId,'_stgh_contact',$reassign);
                }
            }
        }
    }

    function stgh_enable_ticket_create($allcaps, $caps){
        if(in_array('create_ticket',$caps) && isset($allcaps['create_ticket'])){
            unset($allcaps['create_ticket']);
        }
        return $allcaps;
    }

    public function deleteTicket($ticketId){
        global $post_type;

        if ( $post_type != STG_HELPDESK_POST_TYPE )
            return;

        Stg_Helpdesk_Ticket::removeTicketRepliesAndAttachs($ticketId);
    }


    protected function registerFlushCacheUsersLists()
    {
        add_action('set_user_role', function ($user_id) {
            // flushCacheUsersLists
            if ($hashes = get_option('stgh_list_users_cache_hashes', false)) {
                $hashes = explode(' ', $hashes);
                if (!empty($hashes)) {
                    foreach ($hashes as $hash) {
                        delete_transient("stgh_list_users_$hash");
                    }
                    delete_option('stgh_list_users_cache_hashes');
                }
            }
        });
    }


    protected function registerBulkEditCustomBox()
    {
        add_filter('manage_' . STG_HELPDESK_POST_TYPE . '_posts_columns', function ($posts_columns) {
            return Stg_Helpdesk_MetaBoxes::addBulkEditCustomColumns($posts_columns);
        }, 11);

        add_action('bulk_edit_custom_box', function ($column_name, $post_type) {
            return Stg_Helpdesk_MetaBoxes::showBulkEditCustomColumns($column_name, $post_type);
        }, 10, 2);
    }


    /**
     * Registers hooks for adding and saving of a custom field when editing a ticket categories
     */
    protected function registerCategoryCustomFields()
    {
        // Add the fields to the "ticket_category" taxonomy, using our callback function
        add_action('ticket_category_edit_form_fields', function ($tag) {
            return Stg_Helpdesk_TicketCategory::addCustomFields($tag);
        }, 10, 2);

        // Save the changes made on the "ticket_category" taxonomy, using our callback function
        add_action('edited_ticket_category', function ($term_id) {
            return Stg_Helpdesk_TicketCategory::saveCustomFieldsValue($term_id);
        }, 10, 2);
    }


    /**
     * Save support email option from Wizard
     */
    public function saveSupportEmail()
    {
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        if ($email)
            $result = stgh_update_option('stg_mail_login', $email);
        else
            $result = stgh_set_option('stg_mail_login', $email);
        echo $result;
        exit;
    }

    /**
     * Save incoming mail settings from Wizard
     */
    public function saveIncomingMailSettings()
    {
        $res = stgh_update_option('stg_mail_login', isset($_POST['stgh_stg_mail_login']) ? $_POST['stgh_stg_mail_login'] : false);
        if (!$res)
            stgh_set_option('stg_mail_login', isset($_POST['stgh_stg_mail_login']) ? $_POST['stgh_stg_mail_login'] : false);

        $res = stgh_update_option('mail_pwd', isset($_POST['stgh_mail_pwd']) ? $_POST['stgh_mail_pwd'] : false);
        if (!$res)
            stgh_set_option('mail_pwd', isset($_POST['stgh_mail_pwd']) ? $_POST['stgh_mail_pwd'] : false);

        $res = stgh_update_option('mail_protocol', isset($_POST['stgh_mail_protocol']) ? $_POST['stgh_mail_protocol'] : false);
        if (!$res)
            stgh_set_option('mail_protocol', isset($_POST['stgh_mail_protocol']) ? $_POST['stgh_mail_protocol'] : false);

        $res = stgh_update_option('mail_protocol_visible', isset($_POST['stgh_mail_protocol_visible']) ? $_POST['stgh_mail_protocol_visible'] : false);
        if (!$res)
            stgh_set_option('mail_protocol_visible', isset($_POST['stgh_mail_protocol_visible']) ? $_POST['stgh_mail_protocol_visible'] : false);

        $res = stgh_update_option('mail_server', isset($_POST['stgh_mail_server']) ? $_POST['stgh_mail_server'] : false);
        if (!$res)
            stgh_set_option('mail_server', isset($_POST['stgh_mail_server']) ? $_POST['stgh_mail_server'] : false);

        $res = stgh_update_option('mail_port', isset($_POST['stgh_mail_port']) ? $_POST['stgh_mail_port'] : false);
        if (!$res)
            stgh_set_option('mail_port', isset($_POST['stgh_mail_port']) ? $_POST['stgh_mail_port'] : false);

        $res = stgh_update_option('mail_encryption', isset($_POST['stgh_mail_encryption']) ? $_POST['stgh_mail_encryption'] : 'SSL');
        if (!$res)
            stgh_set_option('mail_encryption', isset($_POST['stgh_mail_encryption']) ? $_POST['stgh_mail_encryption'] : 'SSL');
    }


    public function exportCsv($query)
    {
        if (!Stg_Helpdesk_Ticket_Query::checks($query)) {
            return false;
        }

        $isExport = filter_input(INPUT_GET, 'stgh-export-scv', FILTER_SANITIZE_NUMBER_INT);


        if (isset($isExport)) {

            $csvArray = array(
                array(
                    __('Status', STG_HELPDESK_TEXT_DOMAIN_NAME),
                    __('#', STG_HELPDESK_TEXT_DOMAIN_NAME),
                    __('Title', STG_HELPDESK_TEXT_DOMAIN_NAME),
                    __('Tags', STG_HELPDESK_TEXT_DOMAIN_NAME),
                    __('Category', STG_HELPDESK_TEXT_DOMAIN_NAME),
                    __('Ticket', STG_HELPDESK_TEXT_DOMAIN_NAME),
                    __('Author', STG_HELPDESK_TEXT_DOMAIN_NAME),
                    __('Assignee', STG_HELPDESK_TEXT_DOMAIN_NAME),
                    __('Create Date', STG_HELPDESK_TEXT_DOMAIN_NAME),
                )
            );

            $qw = $query->query_vars;
            $qw["posts_per_page"] = -1;
            //wp_reset_query();
            //wp_reset_postdata();
            $query = new \WP_Query($qw);
            $posts = $query->get_posts();
            foreach ($posts as $post) {
                $rowArray = Stg_Helper_Export_Csv::makeScvArray($post);
                if (count($rowArray) > 0)
                    $csvArray[] = $rowArray;

                $replies = Stg_Helpdesk_Ticket::getTicketReplies($post->ID);
                if (count($replies) > 0) {
                    foreach ($replies as $reply) {
                        $rowArray = Stg_Helper_Export_Csv::makeScvArray($reply, true);
                        if (count($rowArray) > 0)
                            $csvArray[] = $rowArray;
                    }
                }
            }

            Stg_Helper_Export_Csv::setHeader('export_tikets.csv');
            echo Stg_Helper_Export_Csv::arr2scv($csvArray);
            exit;
        }
    }

    public function getSavedReplies()
    {
        $savedReplies = Stg_Helper_Saved_Replies::getSavedReplies();
        wp_send_json($savedReplies);
    }

    public function getCustomFields()
    {
        $customFields = Stg_Helper_Custom_Forms::getCustomFields();
        wp_send_json($customFields);
    }

    public function getQAtopicsList()
    {
        $topics = stgh_get_qa_topics();

        wp_send_json($topics);
    }


    public function getSavedRepliesMacros()
    {
        $savedRepliesMacros = Stg_Helper_Saved_Replies::getSavedRepliesMacros();
        wp_send_json($savedRepliesMacros);
    }

    public function getSavedReplyText()
    {

        if (!isset($_GET['rid']))
            exit;

        $replyId = sanitize_text_field($_GET['rid']);
        $ticketId = sanitize_text_field($_GET['tid']);
        $userId = sanitize_text_field($_GET['uid']);
        //$authorId = sanitize_text_field($_GET['aid']);

        //author now can be  agent - change to contact
        $authorId = $userIdContact = get_post_meta($ticketId, '_stgh_contact', true);

        $replyText = Stg_Helper_Saved_Replies::getReplyText($replyId, $ticketId, $userId, $authorId);
        wp_send_json($replyText);
    }

	public function getOrders(){
		$userId = sanitize_text_field($_GET['item']['value']);

		$result = array();
		if (function_exists('stg_wc_get_customer_orders')
        && is_plugin_active('woocommerce/woocommerce.php')) {
			//function stg_wc_get_customer_orders( $customerId, $limit = false )
			$orders = stg_wc_get_customer_orders( $userId, false );
			$item = array();
			/**
			 * @var $order \WC_Order
			 */
			foreach($orders as $order) {
				$item['id'] = stg_wc_get_order_id($order);
				$item['text'] = $item['id']." (".$order->get_status().")";
				$result['woo'][] = $item;
			}
		}

		if(function_exists('stg_edd_get_customer_orders')
        && is_plugin_active('easy-digital-downloads/easy-digital-downloads.php')){
			$orders = stg_edd_get_customer_orders( $userId, false );
			$item = array();
			/**
			 * @var $order \WC_Order
			 */
			foreach($orders as $order) {
				$item['id'] = stg_edd_get_order_id($order);
				$item['text'] = $item['id']." (".$order->status_nicename.")";
				$result['edd'][] = $item;
			}
        }


		$response = $_GET["callback"] . "(" . json_encode($result) . ")";
		echo $response;
		exit;
	}

    public function registerAutocomplete()
    {

        global $wpdb;

        $searchText = sanitize_text_field($_GET['term']);
        $sqlParts = array();

	    foreach (explode(" ", $searchText) as $search) {

		    $query = "SELECT m1.user_id as ID
            FROM ($wpdb->usermeta as m1 INNER JOIN $wpdb->usermeta as m2 
            ON (m1.user_id = m2.user_id and m1.meta_key='{$wpdb->prefix}capabilities' and  m1.meta_value like %s 
            and (m2.meta_key in ('last_name','first_name','_stgh_crm_company'))))
            INNER JOIN $wpdb->users as u ON (u.ID = m1.user_id)            
            WHERE (m2.meta_value like %s OR u.display_name like %s or u.user_email like %s) 
            GROUP BY  m1.user_id 
            LIMIT 5";

		    $preparedQuery = $wpdb->prepare($query,'%s:11:"stgh_client"%' ,'%'.$search.'%','%'.$search.'%','%'.$search.'%');

		    $tmp = $wpdb->get_results($preparedQuery);

		    $usersMeta = $sqlParts[$search] = array();
		    foreach ($tmp as $user) {
			    $sqlParts[$search][$user->ID] = $user;
			    $usersMeta[$user->ID] = get_user_meta($user->ID);
		    }
	    }


	    $result = array_pop($sqlParts);
        foreach ($sqlParts as $sqlPart) {
            $result = array_intersect_key($result, $sqlPart);
        }

        if (count(array_keys($result)) > 0) {
            $args = array(
                'role' => 'stgh_client',
                'include' => array_keys($result));
            $users = get_users($args);
        } else
            $users = array();


        $result = array();
        foreach ($users as $user) {
            $result[] = array('label' => $user->display_name, 'id' => $user->ID, 'email' => $user->user_email, 'first_name' => $usersMeta[$user->ID]['first_name'], 'last_name' => $usersMeta[$user->ID]['last_name']);
        }

        $response = $_GET["callback"] . "(" . json_encode($result) . ")";
        echo $response;
        exit;
    }

	public function kbSaverange(){

		$ids = isset( $_GET['ids'] ) ? (array) $_GET['ids'] : array();
		$ids = array_map( 'esc_attr', $ids );

		$result =  stgh_set_option('stgh_kbrange',$ids);

		$response = $_GET["callback"] . "(" . json_encode($result) . ")";
		echo $response;
		exit;
    }

	public function kbAutocomplete()
	{
		$searchText = sanitize_text_field($_GET['term']);

		$args1 = array(
        'name__like' => $searchText,
        'meta_query' => array(
			'relation' => 'AND',
			array(
				'key' => 'stgh_term_meta',
				'value' => 'custom_field_toqa_meta',
				'compare' => 'LIKE'
			),
			array(
				'key'       => 'stgh_term_meta',
				'compare'   => 'EXISTS'
			)

		));

		$args2 = array(
			'description__like' => $searchText,
			'meta_query' => array(
				'relation' => 'AND',
				array(
					'key' => 'stgh_term_meta',
					'value' => 'custom_field_toqa_meta',
					'compare' => 'LIKE'
				),
				array(
					'key'       => 'stgh_term_meta',
					'compare'   => 'EXISTS'
				)

			));


		if(function_exists('get_term_meta')) {
			$articles1 = Stg_Helper_Saved_Replies::getSavedRepliesKBAll( $args1 );
			$articles2 = Stg_Helper_Saved_Replies::getSavedRepliesKBAll( $args2 );
		}else {
			$articles1 = Stg_Helper_Saved_Replies::getSavedRepliesKBAllOld( $args1 );
			$articles2 = Stg_Helper_Saved_Replies::getSavedRepliesKBAllOld( $args2 );
		}


		$result = array();
		$kbUrl = getKbPage();

		foreach ($articles1 as $article) {
		    $url = add_query_arg(array('kb'=>$article->term_id),$kbUrl);
			$result[$article->term_id] = array('label' => $article->name, 'id' => $article->term_id, 'url' => $url);
		}

		foreach ($articles2 as $article) {
			$url = add_query_arg(array('kb'=>$article->term_id),$kbUrl);
			$result[$article->term_id] = array('label' => $article->name, 'id' => $article->term_id, 'url' => $url);
		}

		$response = $_GET["callback"] . "(" . json_encode($result) . ")";
		header('Content-Type: application/x-javascript');
		echo $response;
		exit;
	}

    protected function getTicketsCount()
    {
        add_action('admin_menu', array($this, 'countTickets'));
    }

    /**
     * Add enctype to admin page ticket
     */
    function addPostEnctype()
    {
        echo ' enctype="multipart/form-data"';
    }

    public function countTickets()
    {
        global $menu;

        $count = stgh_menu_count_tickets();

        if (0 === $count) {
            return false;
        }

        foreach ($menu as $key => $value) {
            if ($menu[$key][2] == 'edit.php?post_type=' . STG_HELPDESK_POST_TYPE) {
                $menu[$key][0] .= ' <span class="awaiting-mod count-' . $count . '"><span class="pending-count">' . $count . '</span></span>';
            }
        }

        return true;

    }

    /**
     * Registering hooks to display the posts list
     */
    protected function registerTicketQuery()
    {
        add_action('pre_get_posts', function ($query) {
            Stg_Helpdesk_Ticket_Query::limitAny($query);
        }, 10, 1);

        add_action('pre_get_posts', function ($query) {
            Stg_Helpdesk_Ticket_Query::ordering($query);
        });

        if (isset($_GET['assignedTo'])) {
            add_action('pre_get_posts', function ($query) {
                Stg_Helpdesk_Ticket_Query::assignedTo($query);
            });
        }

        if (isset($_GET['stgh_category'])) {
            add_action('pre_get_posts', function ($query) {
                Stg_Helpdesk_Ticket_Query::ticketHasCategory($query);
            });
        }

        if (isset($_GET['stgh_tag'])) {
            add_action('pre_get_posts', function ($query) {
                Stg_Helpdesk_Ticket_Query::ticketHasTag($query);
            });
        }


        add_action('pre_get_posts', array($this, 'exportCsv'));

        if (isset($_GET['authorId'])) {
            add_action('pre_get_posts', function ($query) {
                Stg_Helpdesk_Ticket_Query::authorId($query);
            });
        }
    }

    /**
     * Adding custom options to a table
     */
    protected function registerAdminTicketListActions()
    {
        add_filter('post_row_actions', function ($actions, $post) {
            return Stg_Helpdesk_TicketList::actionRows($actions, $post);
        }, 10, 2);
        add_filter('manage_' . STG_HELPDESK_POST_TYPE . '_posts_columns', function ($columns) {
            return Stg_Helpdesk_TicketList::tableColumns($columns);
        }, 10, 2);
        add_filter('manage_' . STG_HELPDESK_POST_TYPE . '_posts_custom_column', function ($columnName, $postId) {
            Stg_Helpdesk_TicketList::columnsContent($columnName, $postId);
        }, 10, 2);

        add_filter('manage_edit-' . STG_HELPDESK_POST_TYPE . '_sortable_columns', function ($columns) {
            return Stg_Helpdesk_TicketList::columnsOrdering($columns);
        });

	    add_filter('hidden_columns', function ($hidden, $screen, $use_defaults) {
		    if($screen->id == "edit-stgh_ticket")
		    {
			    foreach($hidden as $key => $value)
			    {
				    if ($value == 'cb')
					    unset($hidden[$key]);
			    }
		    }

		    return $hidden;
	    }, 10, 3);

        add_action('restrict_manage_posts', function () {
            Stg_Helpdesk_TicketList::assignToFilter();
        }, 9, 0);
        add_filter('parse_query', function ($query) {
            return Stg_Helpdesk_Ticket_Query::filter($query);
        });
        add_action('restrict_manage_posts', function () {
            Stg_Helpdesk_TicketList::crmCompanyFilter();
        }, 10, 0);

        add_filter('list_table_primary_column', function ($default, $screenId) {
            return Stg_Helpdesk_TicketList::getPrimaryKey($default, $screenId);
        }, 10, 2);
    }

    /**
     * Adding custom options to a table, Excerpt mode
     */
    protected function registerAdminTicketExcerptActions()
    {
        add_filter('post_row_actions', function ($actions, $post) {
            return Stg_Helpdesk_TicketList::actionRows($actions, $post);
        }, 10, 2);

        add_filter('manage_' . STG_HELPDESK_POST_TYPE . '_posts_columns', function ($columns) {
            return Stg_Helpdesk_TicketList::tableColumnsExcerpt($columns);
        }, 10, 2);

        add_filter('manage_' . STG_HELPDESK_POST_TYPE . '_posts_custom_column', function ($columnName, $postId) {
            Stg_Helpdesk_TicketList::columnsContentExcerpt($columnName, $postId);
        }, 10, 2);

        add_filter('manage_edit-' . STG_HELPDESK_POST_TYPE . '_sortable_columns', function ($columns) {
            return Stg_Helpdesk_TicketList::columnsOrdering($columns);
        });
        add_action('restrict_manage_posts', function () {
            Stg_Helpdesk_TicketList::assignToFilter();
        }, 9, 0);
        add_filter('parse_query', function ($query) {
            return Stg_Helpdesk_Ticket_Query::filter($query);
        });
        add_action('restrict_manage_posts', function () {
            Stg_Helpdesk_TicketList::crmCompanyFilter();
        }, 10, 0);

    }

    /**
     *    Filtering the data before inserting it into the database
     */
    protected function registerPostDataFilter()
    {
        add_filter('wp_insert_post_data', function ($data, $postarr) {
            return Stg_Helpdesk_Filter::filterData($data, $postarr);
        }, 99, 2);
    }

    /**
     * Redirect
     */
    protected function manageRedirect()
    {
        if (isset($_SESSION['stgh_redirect'])) {
            $redirect = esc_url($_SESSION['stgh_redirect']);
            unset($_SESSION['stgh_redirect']);
            wp_redirect($redirect);
            exit;
        }
    }

    protected function registerSaveTicketFields()
    {
        $version_object = stgh_get_current_version_object();
        $version_object->registerSaveTicketFields();

        // add_action('save_post_'.STG_HELPDESK_POST_TYPE, 'stgh_save_custom_fields');
    }


    /**
     * Register hook for styles
     */
    protected function registerStyles()
    {
        add_action('admin_enqueue_scripts', array($this, 'enqueueStyles'));
        add_action('admin_head', array($this, 'customEnqueueStyles'));
    }

    /**
     * Register styles
     */
    public function enqueueStyles()
    {
        global $current_screen;

        if (stgh_is_plugin_page()) {
            wp_enqueue_style('stgh-stgselect2', STG_HELPDESK_URL . 'css/vendor/stgselect2/stgselect2.min.css', null, '4.0.0', 'all');
            wp_enqueue_style('stgh-admin-styles', STG_HELPDESK_URL . 'css/admin/admin.css', array('stgh-stgselect2'),
                STG_HELPDESK_VERSION);

            if($current_screen->id == 'stgh_ticket'){
                wp_enqueue_style('stgh-admin-ticket', STG_HELPDESK_URL . 'css/admin/ticket.css', array(),
                    STG_HELPDESK_VERSION);
            }
        }

        wp_enqueue_style('stgh-admin-dashboard', STG_HELPDESK_URL . 'css/admin/dashboard.css', array(), STG_HELPDESK_VERSION);


    }

    /**
     * Register custom styles for pages
     */
    public function customEnqueueStyles()
    {
        $version_object = stgh_get_current_version_object();
        $version_object->customEnqueueStyles();
    }

    /**
     * Register hook fro scripts
     */
    protected function registerScripts()
    {
        add_action('admin_enqueue_scripts', array($this, 'enqueueScripts'));
        add_action( 'wp_print_scripts', array($this, 'removeSBScripts'), 100 );

    }


    /**
     * Remove SB scripts
     */
    public function removeSBScripts()
    {
        if (stgh_is_plugin_page()) {
            $scriptNames = array('select2','woocomposer-admin-script','acf-input');

            foreach($scriptNames as $scriptName)
            {
                if (wp_script_is( $scriptName, 'enqueued' ) or wp_script_is( $scriptName, 'queue' )) {

                    wp_deregister_script(  $scriptName );
                    wp_dequeue_script(  $scriptName );
                }
            }

        }
    }


    /**
     * Register scripts
     */
    public function enqueueScripts()
    {
        global $current_screen;

        if (stgh_is_plugin_page()) {


                        if ($current_screen->id == "stgh_ticket") {

                wp_enqueue_script('stgh-admin-sr-script', STG_HELPDESK_URL . 'js/admin/savedreplies.js',
                    array('jquery'),
                    STG_HELPDESK_VERSION);

                wp_localize_script('stgh-admin-sr-script', 'savedrepliesLocale', \StgHelpdesk\Helpers\Stg_Helper_Saved_Replies::getLocalizationArray());

                wp_enqueue_style('srcss', STG_HELPDESK_URL . 'css/admin/savedreplies.css');

            }

            if ($current_screen->id == "stgh_ticket_page_settings" && (!isset($_GET['tab']) || (isset($_GET['tab']) && $_GET['tab'] == 'core'))) {
                wp_enqueue_script('stgh-admin-srmacros-script', STG_HELPDESK_URL . 'js/admin/generalsettingsmacros.js',
                    array('jquery'),
                    STG_HELPDESK_VERSION);

                wp_localize_script('stgh-admin-srmacros-script', 'savedrepliesLocale', \StgHelpdesk\Helpers\Stg_Helper_Saved_Replies::getLocalizationArray());
                wp_enqueue_style('srmacroscss', STG_HELPDESK_URL . 'css/admin/savedrepliesmacros.css');
            }
            

            if ( wp_script_is( 'select2', 'registered' ) ) {
                wp_deregister_script( 'select2' );
            }

            wp_enqueue_script('stgh-stgselect2', STG_HELPDESK_URL . 'js/vendor/stgselect2.min.js', array('jquery'), '4.0.0',true);
            wp_enqueue_script('jquery-ui-autocomplete');
            wp_enqueue_script('stgh-admin-script', STG_HELPDESK_URL . 'js/admin/admin.js',
                array('jquery', 'stgh-stgselect2', 'jquery-ui-autocomplete'),
                STG_HELPDESK_VERSION);

            $version_object = stgh_get_current_version_object();
            $version_object->enqueueAdminScripts();

            wp_enqueue_script('stgh-admin-wiz-script', STG_HELPDESK_URL . 'js/admin/wizard.js',
                array('jquery', 'stgh-stgselect2'),
                STG_HELPDESK_VERSION);

            if ($current_screen->id == "edit-savedreply") {
                wp_enqueue_script('stgh-admin-srmacros-script', STG_HELPDESK_URL . 'js/admin/savedrepliesmacros.js',
                    array('jquery'),
                    STG_HELPDESK_VERSION);

                wp_localize_script('stgh-admin-srmacros-script', 'savedrepliesLocale', Stg_Helper_Saved_Replies::getLocalizationArray());
                wp_enqueue_style('srmacroscss', STG_HELPDESK_URL . 'css/admin/savedrepliesmacros.css');
            }

            if ($current_screen->id == "edit-customfields") {
                wp_enqueue_script('stgh-admin-customfields-script', STG_HELPDESK_URL . 'js/admin/customfields.js',
                    array('jquery'),
                    STG_HELPDESK_VERSION);

                wp_enqueue_style('stgh-admin-customfields-style', STG_HELPDESK_URL . 'css/admin/customfields.css');
            }

            if ($current_screen->id == "edit-customforms") {
                wp_enqueue_script('stgh-admin-customforms-script', STG_HELPDESK_URL . 'js/admin/customforms.js',
                    array('jquery'),
                    STG_HELPDESK_VERSION);

                wp_localize_script('stgh-admin-customforms-script', 'customFormsLocale', Stg_Helper_Custom_Forms::getLocalizationArray());
                wp_enqueue_style('stgh-admin-customforms-style', STG_HELPDESK_URL . 'css/admin/customforms.css');
            }


            if ($current_screen->id == "stgh_ticket") {

                wp_enqueue_script('jquery-ui-datepicker');
                wp_enqueue_style('jqueryui', '//ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css', false, null );
            }
            if ($current_screen->id == "stgh_ticket_page_settings") {
                if (isset($_REQUEST["tab"]) && $_REQUEST["tab"] == "helpcatcher") {
                    $version_object = stgh_get_current_version_object();
                    $version_object->enqueueHelpcatcherScript();
                }
            }

            wp_dequeue_script('autosave');
            $action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING);

            if (stgh_is_our_post_type()) {
                wp_enqueue_script('stgh-admin-comment', STG_HELPDESK_URL . 'js/admin/ticket-comments.js', array('jquery'),
                    STG_HELPDESK_VERSION);
                wp_localize_script('stgh-admin-comment', 'stghLocale', array(
                    'alertDelete' => __('Are you sure you want to delete this comment?', STG_HELPDESK_TEXT_DOMAIN_NAME),
                    'alertSpam' => __('This ticket spam? Comments and contact will be deleted.', STG_HELPDESK_TEXT_DOMAIN_NAME),
                    'alertNoTinyMCE' => __('No instance of TinyMCE found. Please use wp_editor on this page at least once: http://codex.wordpress.org/Function_Reference/wp_editor',
                        STG_HELPDESK_TEXT_DOMAIN_NAME),
                    'alertNoContent' => __("You can't submit an empty comment", STG_HELPDESK_TEXT_DOMAIN_NAME),
                    'alertEmptyEdit' => __("You can't save an empty comment", STG_HELPDESK_TEXT_DOMAIN_NAME),
                    'alertBademail' => __("Wrong email", STG_HELPDESK_TEXT_DOMAIN_NAME),
                    'alertEmptyName' => __("You can't add an empty field name", STG_HELPDESK_TEXT_DOMAIN_NAME),
                    'contactLabel' => __("Contact", STG_HELPDESK_TEXT_DOMAIN_NAME),
                    'selectContactLabel' => __("Select contact", STG_HELPDESK_TEXT_DOMAIN_NAME),
                    'addContactLabel' => __("Add new contact", STG_HELPDESK_TEXT_DOMAIN_NAME),
                    'contactEmpty' => __("Please select contact", STG_HELPDESK_TEXT_DOMAIN_NAME),
                    'replyToEmpty' => __("Cc and Bcc options available only if \"Reply To\" address specified", STG_HELPDESK_TEXT_DOMAIN_NAME),
                ));
            }

            if ($current_screen->id == "stgh_ticket") {
                add_action('admin_print_scripts', array($this, 'addStatusBenjiInlineScript'));
            }
        }
    }

    /**
     * Add Benji
     */
    public function addStatusBenjiInlineScript()
    {
        global $post;

        if (isset($post->ID)) {
            $statuses = Stg_Helpdesk_Post_Type_Statuses::get();
            if(isset($statuses[$post->post_status]))
                $label = $statuses[$post->post_status];
            else
                $label = false;
            $class = $post->post_status . '_color';
            $version_object = stgh_get_current_version_object();

            $selector = $version_object->getTitleSelector();
            ?>
            <script type="text/javascript">
                document.addEventListener('DOMContentLoaded', function () {
                    document.querySelector(<?php echo $selector; ?>).innerHTML += '<span class="stgh_status_header <?php echo $class; ?>"><?php echo $label; ?></span>';
                }, false);
            </script>
            <?php
        }
    }

    protected function registerCustomAction()
    {
        if (isset($_GET['stgh-do'])) {
            add_action('init', array($this, 'doCustomAction'));
        }
        if (isset($_POST['stgh-do'])) {
            add_action('init', array($this, 'doCustomPostAction'));
        }

        if((isset($_REQUEST["post_type"]) && $_REQUEST["post_type"] =="stgh_ticket") &&
            (isset($_REQUEST["page"]) && $_REQUEST["page"]=="settings") &&
            (isset($_REQUEST["tab"]) && $_REQUEST["tab"]=="email") &&
            (isset($_REQUEST["action"]) && $_REQUEST["action"]=="save")){

            stgh_set_option('enable_reply_client', false);
            stgh_set_option('enable_ticket_assign', false);

            if(isset($_REQUEST['stgh_agent_notifications']))
            {
                foreach ($_REQUEST['stgh_agent_notifications'] as $optionsName) {
                    stgh_set_option($optionsName, true);
                }
            }

        }

    }

    /**
     * Get current mode
     * @return string
     */
    public function getMode()
    {
        global $mode;

        if (!empty($_REQUEST['mode'])) {
            $mode = $_REQUEST['mode'] == 'excerpt' ? 'excerpt' : 'list';
            set_user_setting('posts_list_mode', $mode);
        } else {
            $mode = get_user_setting('posts_list_mode', 'list');
        }

        return $mode;
    }

    public function doCustomAction()
    {

        if (isset($_GET['stgh-do']) &&  $_GET['stgh-do'] == 'create-user-pages') {
            Stg_Helpdesk_Activator::insertUserPages();
            stgh_redirect('stgh_pages_created', admin_url('edit.php?post_type=page'));
            exit;
        }

        if (isset($_GET['stgh-do']) &&  $_GET['stgh-do'] == 'remove-trash-reply') {
            Stg_Helpdesk_Ticket::removeTrashReply();
            $siteUrl = get_site_url();
            stgh_redirect('stgh_pages_created', get_admin_url());
            exit;
        }


        if (isset($_GET['tconnection'])) {
            if (isset($_GET['stgh_save_first'])) {
                $this->saveIncomingMailSettings();
            }
            $check_connection = stgh_mailbox_check_connection();
            add_option('stgh_mail_test_connection', $check_connection);
            if (isset($_GET['stgh_back'])) {
                wp_redirect($_SERVER['HTTP_REFERER']);
            } else {
                wp_redirect(get_mailserver_link());
            }
            exit;
        }
        if (isset($_GET['takeemails'])) {
            $result = stgh_letters_handler();
            if (isset($result['msg'])) {
                add_option('stgh_get_mails_from_support_message', $result['msg']);
            }
            if (isset($result['status'])) {
                add_option('stgh_get_mails_from_support', $result['status']);
            }
            if (isset($result['amount'])) {
                add_option('stgh_amount_mails_from_support', $result['amount']);
            }
            wp_redirect(get_mailserver_link());
            exit;
        }

        if (!isset($_GET[self::$nonceName]) || !wp_verify_nonce($_GET[self::$nonceName], self::$customNonceAction)) {
            return;
        }

        $action = sanitize_text_field($_GET['stgh-do']);

        switch ($action) {
            case 'open':
                $id = intval($_GET['post']);
                Stg_Helpdesk_Ticket::open($id);

                stgh_add_event_ticket_to_reply($id, array('post_status_override'), array('post_status_override' => 'stgh_new'));

                $url = wp_sanitize_redirect(add_query_arg(array('post' => $_GET['post'], 'action' => 'edit'),
                    admin_url('post.php')));

                stgh_redirect('open_ticket', $url);
                exit;
                break;

            case 'spam_ticket':
                //case 'spam_comment':
                require_once(ABSPATH . 'wp-admin/includes/user.php');

                if (isset($_GET['del_id'])) {

                    $delId = intval($_GET['del_id']);
                    $post = get_post($delId);

                    if ($post && isset($post->post_author)) {
                        $user = get_userdata($post->post_author);
                        if ($user && !in_array('administrator', $user->roles) && count($user->roles) <= 1) {
                            wp_delete_user($post->post_author);
                        }

                        // Is ticket
                        if ($post->post_type == STG_HELPDESK_POST_TYPE) {
                            // For redirect
                            $post_type = STG_HELPDESK_POST_TYPE;
                        }
                    }
                }

            case 'trash_comment':
                if (isset($_GET['del_id']) && stgh_current_user_can('delete_reply')) {
                    Stg_Helpdesk_Ticket::removeTicketReply($_GET['del_id'],$_GET['post']);

                    /* Redirect with clean URL */
                    if (!isset($post_type)) {
                        $url = wp_sanitize_redirect(add_query_arg(array('post' => $_GET['post'], 'action' => 'edit'),
                            admin_url('post.php') . "#stgh-post-$delId"));
                    } else {
                        $url = wp_sanitize_redirect(add_query_arg(array('post_type' => STG_HELPDESK_POST_TYPE), admin_url('edit.php')));
                    }

                    stgh_redirect('trashed_comment', $url);
                    exit;
                }
                break;

            case 'eml_reply':
                if (isset($_GET['del_id']) && stgh_current_user_can('view_ticket')) {

                    if (!stgh_check_user_access($_GET['post'])) {
                        wp_die(__('You are not allowed to view this attachment', STG_HELPDESK_TEXT_DOMAIN_NAME));
                    }

                    if($_GET['del_id'] === $_GET['post']){
                        $letterSrc = stgh_get_eml_src($_GET['post']);
                    }else{
                        $letterSrc = stgh_get_eml_src($_GET['post'],$_GET['del_id']);
                    }


                    if(!is_readable($letterSrc)){
                        status_header(404);
                        include(get_query_template('404'));
                        die();
                    }

                    header("Content-Type: message/rfc822");
                    header("Content-Disposition: attachment; filename=\"" . basename($letterSrc) . "\"");
                    readfile($letterSrc);

                    exit;
                }
                break;
        }

        do_action('stgh_do_custom_action', $action);

        $args = $_GET;

        /* Remove custom action and nonce */
        unset($_GET['stgh-do']);
        unset($_GET['stgh-nonce']);

        exit;
    }

    public function doCustomPostAction()
    {
        $action = sanitize_text_field($_POST['stgh-do']);
        switch ($action) {
            case 'send_gethelp_message':
                $sender['from_name'] = $sender['reply_name'] = isset($_POST['stgh_gethelp_form_name']) ? $_POST['stgh_gethelp_form_name'] : '';
                $sender['from_email'] = $sender['reply_email'] = isset($_POST['stgh_gethelp_form_email']) ? $_POST['stgh_gethelp_form_email'] : '';
                $headers = Stg_Helper_Email::getHeaders($sender);

                $result = Stg_Helper_Email::send(array(
                    'to' => 'support@mycatchers.com',
                    'subject' => isset($_POST['stgh_gethelp_form_subject']) ? $_POST['stgh_gethelp_form_subject'] : __('Get help!', STG_HELPDESK_TEXT_DOMAIN_NAME),
                    'body' => isset($_POST['stgh_gethelp_form_msg']) ? $_POST['stgh_gethelp_form_msg'] : '',
                    'headers' => $headers,
                    'attachments' => ''
                ));
                add_option('stgh_send_gethelp_message', $result['status']);
                wp_redirect($_SERVER['HTTP_REFERER']);
                exit;
        }
    }

    /**
     * Register options for tickets
     */
    protected function registerTicketLayout()
    {
        add_action('add_meta_boxes', function () {
            global $post_type;

            if ($post_type == STG_HELPDESK_POST_TYPE or $post_type == STG_HELPDESK_COMMENTS_POST_TYPE)
                Stg_Helpdesk_Admin_Ticket_Layout::instance();
        });
    }

    /**
     * Loading settings page
     */
    protected function registerSettingsPage()
    {
        require_once(STG_HELPDESK_PATH . 'Admin/settings/settings-core.php');
        require_once(STG_HELPDESK_PATH . 'Admin/settings/settings-email.php');
        require_once(STG_HELPDESK_PATH . 'Admin/settings/settings-style.php');
        // require_once(STG_HELPDESK_PATH.'Admin/settings/settings-fileupload.php');
        require_once(STG_HELPDESK_PATH . 'Admin/settings/settings-mailserver.php');
        require_once(STG_HELPDESK_PATH . 'Admin/settings/settings-outgoing.php');

        //require_once(STG_HELPDESK_PATH . 'Admin/settings/settings-contactform.php');
        require_once(STG_HELPDESK_PATH . 'Admin/settings/settings-helpcatcher.php');

        require_once(STG_HELPDESK_PATH . 'Admin/settings/integrations.php');
        require_once(STG_HELPDESK_PATH . 'Admin/settings/settings-gethelp.php');

        require_once(STG_HELPDESK_PATH . 'Admin/settings/settings-pro-version.php');


        add_action('plugins_loaded', function () {
            Stg_Helpdesk_Settings_Page::instance();
        }, 11, 0);
    }

    /**
     * Check activation status
     *
     * @return bool
     */
    protected function justActivated()
    {
        return true === (bool)get_option('stgh_redirect_to_welcome_page', false);
    }


    protected function is_protection_applicable() {
        global $pagenow;

        $result = false;
        $pages_to_block = array('profile.php', 'users.php', 'user-new.php', 'user-edit.php');
        if (in_array($pagenow, $pages_to_block)) {
            $result = true;
        }

        return $result;
    }

    public function remove_admin_role($roles) {

        if ($this->is_protection_applicable() && isset($roles['administrator'])) {
            unset($roles['administrator']);
        }

        return $roles;
    }

    private function extract_view_quantity($text) {
        $match = array();
        $result = preg_match('#\((.*?)\)#', $text, $match);
        if ($result) {
            $quantity = $match[1];
        } else {
            $quantity = 0;
        }

        return $quantity;
    }

    public function remove_admins_view($views) {

        if (!isset($views['administrator'])) {
            return $views;
        }

        if (isset($views['all'])) {
            $admins_orig = $this->extract_view_quantity($views['administrator']);
            $admins_int = str_replace(',', '', $admins_orig);
            $all_orig = $this->extract_view_quantity($views['all']);
            $all_orig_int = str_replace(',', '', $all_orig);
            $all_new = $all_orig_int - $admins_int;
            $views['all'] = str_replace($all_orig, $all_new, $views['all']);
        }

        unset($views['administrator']);

        return $views;
    }

    public function remove_administrators($user_query) {
        global $wpdb;

        if (!$this->is_protection_applicable()) {
            return;
        }

        $current_user_id = get_current_user_id();
        $tableName = $wpdb->usermeta;
        $meta_key = $wpdb->prefix . 'capabilities';
        $admin_role_key = '%"administrator"%';
        $query = "SELECT user_id
              FROM $tableName
              WHERE user_id!={$current_user_id} AND meta_key='{$meta_key}' AND meta_value like '{$admin_role_key}'";
        $ids_arr = $wpdb->get_col($query);
        if (is_array($ids_arr) && count($ids_arr) > 0) {
            $ids = implode(',', $ids_arr);
            $user_query->query_where .= " AND ( $wpdb->users.ID NOT IN ( $ids ) )";
        }
    }

    public function not_edit_admin($allcaps, $caps) {
        global $pagenow;

        if (in_array($pagenow, array('users.php', 'user-edit.php'))) {

            $cap = (is_array($caps) & count($caps) > 0) ? $caps[0] : $caps;
            $checked_caps = array('edit_users', 'delete_users', 'remove_users');

            if (!in_array($cap, $checked_caps)) {
                return $allcaps;
            }

            $user_keys = array('user_id', 'user');
            foreach ($user_keys as $user_key) {

                $user_id = (isset($_GET[$user_key]))? filter_var($_GET[$user_key], FILTER_SANITIZE_STRING):false;

                if (empty($user_id)) {
                    continue;
                }
                if ($user_id == 1) {
                    $access_deny = true;
                } else {
                    $access_deny = stgh_user_can($user_id,'administrator');
                }
                if ($access_deny && isset($allcaps[$cap])) {
                    unset($allcaps[$cap]);

                }
                break;
            }
        }
        return $allcaps;
    }



    public function redirectToWelcomePage()
    {
        delete_option('stgh_redirect_to_welcome_page');
        wp_redirect(stgh_link_to_welcome_page());
        exit;
    }

    /**
     *    Register menu items
     */
    public function registerMenu()
    {
        global $menu, $submenu;

        if(!isset($submenu['edit.php?post_type=' . STG_HELPDESK_POST_TYPE]))
            return;

        $current_user = stgh_get_current_user();

        add_submenu_page('edit.php?post_type=' . STG_HELPDESK_POST_TYPE,
            __('StudioTG Helpdesk Welcome page', STG_HELPDESK_TEXT_DOMAIN_NAME), __('Welcome', STG_HELPDESK_TEXT_DOMAIN_NAME), 'view_ticket',
            'stgh-welcome', array($this, 'showWelcomePage'));
        remove_submenu_page('edit.php?post_type=' . STG_HELPDESK_POST_TYPE, 'stgh-welcome');


        foreach ($submenu['edit.php?post_type=' . STG_HELPDESK_POST_TYPE] as $key => $current) {
            if ($current[2] == "edit-tags.php?taxonomy=post_tag&amp;post_type=" . STG_HELPDESK_POST_TYPE) {
                unset($submenu['edit.php?post_type=' . STG_HELPDESK_POST_TYPE][$key]);
            }
        }


        // Wizard
        add_submenu_page('edit.php?post_type=' . STG_HELPDESK_POST_TYPE,
            __('StudioTG Helpdesk Wizard page', STG_HELPDESK_TEXT_DOMAIN_NAME), __('Wizard', STG_HELPDESK_TEXT_DOMAIN_NAME), 'view_ticket',
            'stgh-wizard', array($this, 'showWizardPage'));
        remove_submenu_page('edit.php?post_type=' . STG_HELPDESK_POST_TYPE, 'stgh-wizard');



        // Pro-version page
        add_submenu_page('edit.php?post_type=' . STG_HELPDESK_POST_TYPE, null, "<span style=\"margin-top:-12px;color:#168de2;\">" . __('Addons', STG_HELPDESK_TEXT_DOMAIN_NAME) . "</span>", 'settings_tickets', 'stgh-pro', null);

        add_action('admin_menu', array($this, 'add_external_link_admin_submenu'), 100);
        add_action('admin_menu', array($this, 'remove_add_new_ticket_submenu'), 100);


        //add_submenu_page('edit.php?post_type=' . STG_HELPDESK_POST_TYPE, null, __('Addons', STG_HELPDESK_TEXT_DOMAIN_NAME), 'edit_posts', 'stgh-addons', array($this, 'showAddonPage'));

        foreach ($menu as $key => $value) {
            if ($menu[$key][2] == 'edit.php?post_type=' . STG_HELPDESK_POST_TYPE) {
                $oldSubMenu = $submenu['edit.php?post_type=' . STG_HELPDESK_POST_TYPE];
                $cnt = count($oldSubMenu);
                $insertPosition = 1;
                $newSubMenu = array();
                for ($i = 0; $i < $cnt; $i++) {
                    if ($i == $insertPosition) {
                        $newSubMenu[] = array(
                            __('Factory Queries', STG_HELPDESK_TEXT_DOMAIN_NAME),
                            'view_ticket',
                            'edit.php?assignedTo=' . $current_user->ID . '&post_type=' . STG_HELPDESK_POST_TYPE
                        );
                    }

                    array_push($newSubMenu, array_shift($oldSubMenu));
                }
                $submenu['edit.php?post_type=' . STG_HELPDESK_POST_TYPE] = $newSubMenu;
            }
        }

    }

    public function add_external_link_admin_submenu() {
        global $submenu;

        //$permalink = 'https://mycatchers.com/pro/?source=light\' target=\'_blank;';
        $permalink = 'edit.php?post_type=stgh_ticket&page=settings&tab=addons';

        foreach($submenu["edit.php?post_type=stgh_ticket"] as &$current)
        {
            if($current[2] == 'stgh-pro')
            {
                $current[2] = $permalink;
            }
        }
    }

    public function remove_add_new_ticket_submenu() {
        global $submenu;

        foreach($submenu["edit.php?post_type=stgh_ticket"] as $key => $current)
        {
            if($current[2] == 'post-new.php?post_type=stgh_ticket')
            {
                unset($submenu["edit.php?post_type=stgh_ticket"][$key]);
            }
        }
    }



    /**
     * Show welcome page
     */
    public function showWelcomePage()
    {
        include_once(STG_HELPDESK_PATH . 'Admin/views/welcome.php');
    }

    /**
     * Wizard page
     */
    public function showWizardPage()
    {
        include_once(STG_HELPDESK_PATH . 'Admin/views/wizard.php');
    }

//    public function showAddonPage()
//    {
//        include_once(STG_HELPDESK_PATH . 'Admin/views/addons.php');
//    }

    public function mailboxNotice()
    {
        $status = get_option('stgh_mail_test_connection', true);
        $server = stgh_get_option('mail_server', false);
        /*$enabled = stgh_get_option('stg_enabled_mail_feedback', false);
        if (! $enabled) {
            $msg = __('You are not activate feedback via email.', STG_HELPDESK_TEXT_DOMAIN_NAME);
            $p = '';
            $class = 'error';
        } else*/
        if (1 == $status) {
            $msg = __(sprintf('Connection to the server: %s established successfully.', $server), STG_HELPDESK_TEXT_DOMAIN_NAME);
            $p = '';
            $class = 'updated';
        } else {
            $msg = __(sprintf('Can not connect to the server: %s. Please, check your setting.', $server),
                STG_HELPDESK_TEXT_DOMAIN_NAME);
            $p = $status;
            $class = 'error';

            if(stgh_get_option('mail_protocol_visible') == 'Gmail'){
                $gmailHelpMsg = __('See also: <a href="https://support.google.com/accounts/answer/6009563" target="_blank">https://support.google.com/accounts/answer/6009563</a>', STG_HELPDESK_TEXT_DOMAIN_NAME);
                $p = "<p>{$p}</p><p>{$gmailHelpMsg}</p>";

            }
        }

        echo "<div id='stgh-mailbox-info-warning' class='{$class} fade'><p><strong>{$msg}</strong></p>" . $p . "</div>";

        delete_option('stgh_mail_test_connection');
    }

    public function getMailsFromSupportNotice()
    {
        $status = get_option('stgh_get_mails_from_support', false);
        $amount = get_option('stgh_amount_mails_from_support', false);
        if ($status) {
            if ($amount > 0) {
                $msg = __(sprintf('Processed %d e-mail(s)', $amount), STG_HELPDESK_TEXT_DOMAIN_NAME);
            } else {
                $msg = __('No new messages for processing', STG_HELPDESK_TEXT_DOMAIN_NAME);
            }

            $class = 'updated';
        } else {
            $message = get_option('stgh_get_mails_from_support_message', '');
            $msg = __('Can not connect to the server. Please, check your setting.', STG_HELPDESK_TEXT_DOMAIN_NAME) . $message;
            $class = 'error';
        }
        echo "<div class='$class fade'><p><strong>$msg</strong></p></div>";

        delete_option('stgh_get_mails_from_support');
        delete_option('stgh_get_mails_from_support_message');
        delete_option('stgh_amount_mails_from_support');
    }

    public function getGetHelpMessageNotice()
    {
        $status = get_option('stgh_send_gethelp_message', false);
        if ($status) {
            $msg = __('Thank you for contacting us. Your message has been successfully sent. We will contact you very soon!', STG_HELPDESK_TEXT_DOMAIN_NAME);
            $class = 'updated';
        } else {
            $msg = __('An error occurred when send message.', STG_HELPDESK_TEXT_DOMAIN_NAME);
            $class = 'error';
        }
        echo "<div class='$class fade'><p><strong>$msg</strong></p></div>";
        delete_option('stgh_send_gethelp_message');
    }

    public function ajaxSendTestEmail()
    {

        $headers = Stg_Helper_Email::getHeaders();

        $result = Stg_Helper_Email::send(array(
            'to' => stgh_get_option('stgh_test_to_email'),
            'subject' => __('Check connection from Tickets', STG_HELPDESK_TEXT_DOMAIN_NAME) . ' (' . home_url() . ')',
            'body' => __('Connection successful!', STG_HELPDESK_TEXT_DOMAIN_NAME),
            'headers' => $headers,
            'attachments' => ''
        ));
        if ($result['status'])
            wp_send_json_success();
        else
        {
            global $ts_mail_errors;
            global $phpmailer;

            if (!isset($ts_mail_errors)) $ts_mail_errors = array();

            if (isset($phpmailer)) {
                $ts_mail_errors[] = $phpmailer->ErrorInfo;
            }

            wp_send_json_error($ts_mail_errors);
        }

    }

    public function add_action_links($links)
    {
        $stgh_links = array(
            '<a target="blank" href="' . stgh_link_to_settings('gethelp') . '">Support Forum</a>',
        );
        return array_merge($links, $stgh_links);
    }

    public function stgh_filter_link($views)
    {
        $statuses = stgh_get_statuses();
        foreach ($statuses as $key => $status) {
            if ($key == 'stgh_closed') {
                unset($statuses[$key]);
            }
        }
        $current_user = stgh_get_current_user();
        $params = array(
            'post_status' => array_keys($statuses),
            'meta_key' => '_stgh_assignee',
            'meta_value' => $current_user->ID
        );
        $count = Stg_Helpdesk_Ticket::getCount($params);
        $class = (isset($_GET['tp']) && $_GET['tp'] == 'my') ? ' class="current" ' : '';
        $views['mine'] = sprintf('<a href="%s" ' . $class . ' >'.__('My',STG_HELPDESK_TEXT_DOMAIN_NAME).' <span class="count">(%d)</span></a>',
            admin_url('edit.php?post_type=stgh_ticket&assignedTo=' . $current_user->ID . '&tp=my'),
            $count);


        $params = array('post_status' => array_keys($statuses));
        $count = Stg_Helpdesk_Ticket::getCount($params);
        $class = (strpos($views['all'], 'current') === false) ? '' : ' class="current" ';
        $views['all'] = sprintf('<a href="%s" ' . $class . ' >'.__('All',STG_HELPDESK_TEXT_DOMAIN_NAME).' <span class="count">(%d)</span></a>',
            admin_url('edit.php?post_type=stgh_ticket&all_posts=1'),
            $count);
        return $views;
    }

    /**
     * Handler rating-click event
     */
    public function clickRating()
    {
        stgh_set_option('stgh_admin_footer_text_rated', 1);
    }

    /**
     * @param $footer_text
     * @return string|void
     */
    public function stgh_admin_footer_text($footer_text)
    {
        global $post_type, $current_screen, $TitanFrameworkAdminPage;

        if ($post_type == STG_HELPDESK_POST_TYPE || $current_screen->id == 'stgh_ticket_page_settings' || $current_screen->id == 'stgh_ticket_page_stgh-addons') {
            echo "<style>#footer-left em {display:none;}</style>";
            if (!stgh_get_option('stgh_admin_footer_text_rated')) {
                $footer_text = sprintf(__('If you like <b>Catchers Helpdesk</b> please leave us a <a id="stg-rating-anchor" data-msg-click="%s" target="_blank" href="%s">&#9733;&#9733;&#9733;&#9733;&#9733;</a> rating. A huge thank you from Catchers in advance!', STG_HELPDESK_TEXT_DOMAIN_NAME), __('Thanks!', STG_HELPDESK_TEXT_DOMAIN_NAME), 'https://wordpress.org/support/view/plugin-reviews/catchers-helpdesk?filter=5#postform');
            } else {
                $footer_text = __('Thank you for using our plugin!', STG_HELPDESK_TEXT_DOMAIN_NAME);
            }
            return $footer_text;
        }
        return $footer_text;
    }


    /**
     * @param TitanFrameworkAdminPage $container
     * @param TitanFrameworkAdminTab $activeTab
     * @param array $options
     */
    public function tfSaveAdmin($container, $activeTab, $options)
    {


        $tmp = array();
        if ($container->owner->getOption('helpcatcher_button_color'))
            $tmp[] = '"buttonColor":"' . $container->owner->getOption('helpcatcher_button_color') . '"';

        if ($container->owner->getOption('helpcatcher_enable_attachment'))
            $tmp[] = '"enableUpload":"true"';
        else
            $tmp[] = '"enableUpload":"false"';

	    if ($container->owner->getOption('helpcatcher_letter_start'))
            $tmp[] = '"headerText":"' . addslashes(rtrim($container->owner->getOption('helpcatcher_letter_start'))) . '"';

        if ($container->owner->getOption('helpcatcher_result_msg'))
            $tmp[] = '"resultMessage":"' . addslashes(rtrim($container->owner->getOption('helpcatcher_result_msg'))) . '"';

	    if ($container->owner->getOption('helpcatcher_position'))
            $tmp[] = '"align":"' . $container->owner->getOption('helpcatcher_position') . '"';

	    if ($container->owner->getOption('helpcatcher_gdpr_url'))
		    $tmp[] = '"gdprUrl":"' . addslashes(rtrim($container->owner->getOption('helpcatcher_gdpr_url'))) . '"';

	    if ($container->owner->getOption('helpcatcher_gdpr_enable'))
		    $tmp[] = '"gdprEnable":"true"';
	    else
		    $tmp[] = '"gdprEnable":"false"';

	    if ($container->owner->getOption('helpcatcher_hide_name'))
		    $tmp[] = '"hideName":"true"';
	    else
		    $tmp[] = '"hideName":"false"';

	    if ($container->owner->getOption('helpcatcher_hide_subject'))
		    $tmp[] = '"hideSubject":"true"';
	    else
		    $tmp[] = '"hideSubject":"false"';



	    $paramsString = implode(',', $tmp);
        $newValue = str_ireplace('WidgetHelpCatcher.load();', 'WidgetHelpCatcher.load({' . $paramsString . '});', Stg_Helpdesk_Help_Catcher::getCode());



	    $newValue =  preg_replace('/\r\n|\r|\n/u', '', $newValue);
        $container->owner->setOption('helpcatcher_embed_code', $newValue);
    }

}
