<?php

namespace StgHelpdesk\Core;


use StgHelpdesk\Helpers\Stg_Helper_Custom_Forms;
use StgHelpdesk\Ticket\Stg_Helpdesk_Ticket;
use StgHelpdesk\Ticket\Stg_Helpdesk_Ticket_Query;
use StgHelpdesk\Ticket\Stg_Helpdesk_TicketComments;
use StgHelpdesk\Helpers\Stg_Helper_UploadFiles;
use StgHelpdesk\Helpers\Stg_Helper_Template;
use StgHelpdesk\Helpers\Stg_Helper_Email;
use StgHelpdesk\Admin\Stg_Helpdesk_Help_Catcher;

use StgHelpdesk\Updater;

/**
 * Class Stg_Helpdesk_Init
 * @package StgHelpdesk\Core
 */
class Stg_Helpdesk_Init
{
    /**
     * Instance of this class.
     */
    protected static $instance = null;

    public static $isCron = false;

    /**
     * Initialize the plugin
     */
    private function __construct()
    {

        //cron tasks
        add_action('stgh_load_email_hook', array($this, 'cronTask'));
        add_filter('cron_schedules', 'stgh_cron_add_schedules');

        //cron action tasks
        if (!empty($_POST['stgh_stg_mail_interval'])) {
            Stg_Helpdesk_Activator::setCronEmailTasks(sanitize_text_field($_POST['stgh_stg_mail_interval']));
        }else
            Stg_Helpdesk_Activator::setCronEmailTasks();

        //form submitted
        add_action('init', array($this, 'init'), 11, 0);

        // Load public-facing style sheets
        add_action('wp_enqueue_scripts', array($this, 'enqueue_styles'), 10, 0);
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'), 10, 0);
        //add_action('wp_enqueue_scripts', array($this, 'init_inline_style'), 9, 0);

        //content ticket
        add_filter('the_content', 'get_page_single_ticket', 10, 1);
        add_filter( 'the_title', 'hide_title_to_voter', 10, 2 );

        //query type attachment file
        add_action('template_redirect', array($this, 'page_attachment'), 10, 0);
        add_action('pre_get_posts', array($this, 'attachment_query'), 10, 1);

        add_action('pre_get_posts', function ($query) {
            Stg_Helpdesk_Ticket_Query::authorOrContact($query);
        });

        add_filter( 'get_terms_fields', function ($selects, $args, $taxonomies) {
            return Stg_Helpdesk_Ticket_Query::qa_kb($selects, $args, $taxonomies);
        }, 10, 3 );


        add_action('init', array($this, 'attachment_endpoint'), 10, 1);

        //user profile
        add_action('show_user_profile', array($this, 'add_custom_user_profile_fields'), 10, 1);
        add_action('edit_user_profile', array($this, 'add_custom_user_profile_fields'), 10, 1);
        add_action('personal_options_update', array($this, 'save_custom_user_profile_fields'));
        add_action('edit_user_profile_update', array($this, 'save_custom_user_profile_fields'));

                //add saved replies
        add_action('init', array($this, 'create_taxonomy_savedreplies'));

        //add tinymce in saved_replies
        add_filter('savedreply_edit_form_fields', array($this, 'edit_cat_description'));
        add_action('admin_head', array($this, 'taxonomy_tinycme_hide_description'));
        remove_filter('pre_term_description', 'wp_filter_kses');
        remove_filter('term_description', 'wp_kses_data');

        add_filter('manage_savedreply_custom_column', array($this,'add_savedreply_column_content'),10,3);
        add_filter('manage_edit-savedreply_columns', array($this,'add_savedreply_column_header'), 10);
	    add_filter('savedreply_row_actions', array($this, 'remove_row_actions'), 10, 2 );

        add_action('catcher-helpdesk-ticket-ntabs', array($this,'add_private_note_tab'));
        add_action('catcher-helpdesk-ticket-ccbcc-toggle', array($this,'add_ccbcc_link'));
        add_action('catcher-helpdesk-ticket-ccbcc', array($this,'add_ccbcc'));
        add_action('catcher-helpdesk-ticket-history',array($this,'add_history'),10,1);

        add_filter('stgh_plugin_settings', array($this,'stgh_core_settings'), 96, 1);

        add_filter('catcher-helpdesk-comments-filter', array($this,'stgh_comments_filter_private'));
        add_filter('catcher-helpdesk-comments-filter-menu', array($this,'stgh_comments_filter_menu'));
        add_filter('stgh_plugin_settings', array($this,'stgh_updates_integrations'), 100, 1);

        add_action('init', array($this, 'run_updater'));
        
        //help catcher html
        if (Stg_Helpdesk_Help_Catcher::isEnabled()) {
            add_filter('wp_footer', 'stgh_add_html_help_catcher');

            add_action('wp_ajax_stgh_helpcatcher_action', 'stgh_help_catcher_ajax_handler');
            add_action('wp_ajax_nopriv_stgh_helpcatcher_action', 'stgh_help_catcher_ajax_handler');
        }

        add_action('wp_ajax_stgh_helpcatcher_params_action', 'stgh_help_catcher_params_ajax_handler');
        add_action('wp_ajax_nopriv_stgh_helpcatcher_params_action', 'stgh_help_catcher_params_ajax_handler');

        //widget help catcher url js
        add_action('template_redirect', array($this, 'page_widget_help_catcher'), 10, 0);
        add_action('init', array($this, 'widget_help_catcher_endpoint'), 10, 1);
        //add_filter( 'site_transient_update_plugins', 'stgh_remove_update_notifications' );


        add_action('admin_bar_menu', array($this, 'registerMenuToolbar'), 49);
        add_action( 'wp_before_admin_bar_render', array($this,'removeNodes') );

        $this->registerCustomAction();

        add_filter( 'posts_search' , array($this,'searchByEmail'),1,2);
        add_filter( 'posts_join' , array($this,'joinByEmail'),1,2);
        add_filter('posts_distinct', array($this,'searchByEmailDistinct'));
    }

    public function searchByEmail($search,$query){
        global $wpdb;

        if($query->is_search && $query->is_admin && $query->query['post_type'] == STG_HELPDESK_POST_TYPE)
        {
            $add = $wpdb->prepare("({$wpdb->users}.user_email like '%%%s%%' AND pm.meta_key = '_stgh_contact' AND pm.meta_value = {$wpdb->users}.ID)",$query->get('s'));

            $pat = '|\(\((.+)\)\)|';
            $search = preg_replace($pat,'(($1 OR '.$add.'))',$search);
        }

        return $search;
    }

    public function joinByEmail($joins,$query){
        global $wpdb;
        if($query->is_search && $query->is_admin && $query->query['post_type'] == STG_HELPDESK_POST_TYPE)
        {
            $joins = $joins . " INNER JOIN {$wpdb->postmeta} as pm ON ({$wpdb->posts}.ID = pm.post_id)";
            $joins = $joins . " INNER JOIN {$wpdb->users} ON (pm.meta_value = {$wpdb->users}.ID)";
        }
        return $joins;
    }

    public function searchByEmailDistinct(){
        global $wp_query;
        if($wp_query->is_search && $wp_query->is_admin && $wp_query->query['post_type'] == STG_HELPDESK_POST_TYPE)
        {
            return "DISTINCT";
        }
    }


    protected function registerCustomAction()
    {
        if (isset($_GET['stgh-do'])) {
            add_action('init', array($this, 'doCustomAction'));
        }
    }

    public function doCustomAction()
    {
        $action = sanitize_text_field($_GET['stgh-do']);

        switch ($action) {
            case 'tracking_image':
                $postId = $_REQUEST['postid'];

                if (!is_numeric($postId))
                    return false;

                $postReadHash = get_post_meta( $postId, '_stgh_post_read_hash', true );

                if(!isset($_REQUEST['sh']) || $_REQUEST['sh'] != $postReadHash)
                    return false;

                update_post_meta($postId, '_stgh_post_read', true);

                header("Content-type: image/png");
                $im = ImageCreate(1, 1);
                ImageColorAllocate($im, 255, 255, 255);
                ImagePng($im);
                break;
        }
        //exit;
    }


    public function cronTask()
    {
        Stg_Helpdesk_Init::$isCron = true;
        $result = stgh_letters_handler();
        Stg_Helpdesk_Init::$isCron = false;

        return $result;
    }

    /**
     *  Init handler form
     */
    public function init()
    {
        //new user
        $currentUser = stgh_get_current_user_id();
        $user_reg = false;

        if (!$currentUser || (!empty($_POST['stg_ticket_email']) && !get_user_by('email', trim($_POST['stg_ticket_email'])))) {
            if (isset($_POST['stg_ticket_email'])) {

                $name = (isset($_POST['stg_ticket_name']))?$_POST['stg_ticket_name']:$_POST['stg_ticket_email'];
                $user_reg = stgh_register_user($_POST['stg_ticket_email'], $name);

                if (!$user_reg) {
                    return false;
                }
            }
        }

        //ticket
        if (isset($_POST['stg_saveTicket']) && $_POST['stg_saveTicket'] == 1) {

            $post_id = Stg_Helpdesk_Ticket::saveTicket($_POST, $user_reg, false, 'website');

            if ($post_id) {
                Stg_Helper_UploadFiles::handleUploadsForm($post_id);

                //email
                Stg_Helper_Email::sendEventTicket('ticket_open', $post_id);
                Stg_Helper_Email::sendEventTicket('ticket_assign', $post_id);


                if(isset($_REQUEST['stg_confirm_url'])){
                    wp_redirect($_REQUEST['stg_confirm_url']);
                    exit;
                }

                if(!isset($_REQUEST['stgh_message_success']))
                {
                    $link = get_permalink($post_id);
                    wp_redirect($link);
                    exit;
                }

            }
        }
        //comment
        if (isset($_POST['stgh_user_comment'])) {
            $comment_id = Stg_Helpdesk_TicketComments::saveCommentPublic($_POST, 'website');

            if ($comment_id) {
                $parent_id = !empty($_POST['ticket_id']) ? (int)$_POST['ticket_id'] : 0;
                Stg_Helper_UploadFiles::handleUploadsForm($comment_id, $parent_id);

                //email                
                $agentTicket = get_post_meta( $parent_id, '_stgh_ticket_author_agent', true);

                if($agentTicket == 1){
                    $authorComment = intval(get_post($comment_id)->post_author);
                    $ticket = Stg_Helpdesk_Ticket::getInstance($parent_id);
                    $ticketContact = $ticket->getContact();

                    if ($authorComment == $ticketContact->ID) {
                        Stg_Helper_Email::sendEventTicket('client_reply', $comment_id);
                    }
                }else{
                    $authorComment = intval(get_post($comment_id)->post_author);
                    $authorTicket = intval(get_post($parent_id)->post_author);

                    if ($authorComment == $authorTicket) {
                        Stg_Helper_Email::sendEventTicket('client_reply', $comment_id);
                    }
                }

                // Get attachment from comment for mail
                global $wpdb;
                $attachments_urls = array();
                $attachments = Stg_Helper_UploadFiles::getAttachments($comment_id);
                foreach ($attachments as $attachment_id => $attachment) {
                    $link = add_query_arg(array('ticket-attachment' => $attachment['id']), home_url());
                    $attachments_urls[] = $attachment['url'];
                }

                update_post_meta($comment_id, 'list_attachment', $attachments_urls);
    
                $multiple_recipients = array(
                    'tudor@lifetimeshutters.com', 'caroline@anyhooshutter.com'
//, 'teopro@gmail.com', 'marianputirac@thecon.ro'
                );
                $title = $wpdb->get_var( $wpdb->prepare( "SELECT post_title FROM $wpdb->posts WHERE ID = %d", $parent_id));
                $subject_d = 'Reply: ' .$title;
                $body_d = get_post($comment_id)->post_content;
                $headers_d = array('Content-Type: text/html; charset=UTF-8','From: Matrix-LifetimeShutters <order@lifetimeshutters.com>');
    
                wp_mail($multiple_recipients, $subject_d, $body_d, $headers_d, $attachments_urls);

                wp_redirect(get_permalink($parent_id), 301);
                exit;
            }
        }
    }

    /**
     * Register and enqueue public-facing style sheet.
     */
    public function enqueue_styles()
    {
        wp_register_style('stgh-plugin-styles', STG_HELPDESK_URL . 'css/stg-helpdesk-public.css');
        wp_enqueue_style('stgh-plugin-styles');
	    add_action('wp_head', array($this, 'customEnqueueStyles'));
    }

	/**
	 * Register custom styles for pages
	 */
	public function customEnqueueStyles()
	{
		$version_object = stgh_get_current_version_object();
		$version_object->customEnqueueStyles();
	}

    public function enqueue_scripts()
    {
        global $post;

	    $version_object = stgh_get_current_version_object();
	    $version_object->enqueuePublicScripts();

        wp_enqueue_style( 'dashicons' );
    }

    /**
     * Register custom inline styles from admin config
     */
    public function init_inline_style()
    {
        wp_enqueue_style('custom-stgh-inline-style', STG_HELPDESK_URL . 'css/custom-stgh-inline-style.css');

        /**
         * Custom css
         */
        if (stgh_get_option('stgh_custom_style')) {
            wp_add_inline_style('custom-stgh-inline-style', stgh_get_option('stgh_custom_style'));
        }

        //style register_menu_toolbar
        wp_add_inline_style('custom-stgh-inline-style', '#wp-admin-bar-stgh-helpdesk .ab-icon:before {
                    content: \'\f468\';
                    top: 3px;
        }');

    }

    /**
     * Tumbnail in top toolbar
     */
    public function registerMenuToolbar()
    {
        global $wp_admin_bar;


        if (!is_admin_bar_showing())
            return;


        if ((!stgh_current_user_can('administrator') && !stgh_current_user_can('edit_ticket')))
            return;



        $args = array(
            'id' => 'stgh-helpdesk',
            'title' => '<span class="ab-icon"></span> ' . stgh_menu_count_tickets(),
            'href' => admin_url('edit.php?post_type=' . STG_HELPDESK_POST_TYPE),
            'parent' => null,
            'group' => null,
            'meta' => array(),
        );
        $wp_admin_bar->add_menu($args);
    }

    public function removeNodes(){
        global $wp_admin_bar;
        $node = $wp_admin_bar->get_node('view');
        if(!empty($node))
        {
            if($wp_admin_bar->get_node('view')->title == "View Custom Field"
            or $wp_admin_bar->get_node('view')->title == "View Contact Form"
            or $wp_admin_bar->get_node('view')->title == "View Reply")
               $wp_admin_bar->remove_menu('view');
        }
    }


    /**
     * Get params attachment
     * @param $query
     */
    public function attachment_query($query)
    {
        if ($query->is_main_query() && isset($_GET['ticket-attachment'])) {
            $query->set('ticket-attachment', filter_input(INPUT_GET, 'ticket-attachment', FILTER_SANITIZE_NUMBER_INT));
        }
    }

    /**
     * Add endpoint
     */
    public function attachment_endpoint()
    {
        add_rewrite_endpoint('ticket-attachment', EP_PERMALINK);
    }


    /**
     * Add custom fields on page user profile
     * @param $user
     */
    public function add_custom_user_profile_fields($user)
    {
        Stg_Helper_Template::getTemplate('stg-profile-user-fields', array('user' => $user));
    }

    /**
     * Save data user profile custom fields
     * @param $user_id
     * @return bool
     */
    public function save_custom_user_profile_fields($user_id)
    {
        if (!stgh_current_user_can('edit_user', $user_id)) {
            return FALSE;
        }
        update_user_meta($user_id, '_stgh_crm_company', sanitize_text_field($_POST['_stgh_crm_company']));
        update_user_meta($user_id, '_stgh_crm_skype', sanitize_text_field($_POST['_stgh_crm_skype']));
        update_user_meta($user_id, '_stgh_crm_phone', sanitize_text_field($_POST['_stgh_crm_phone']));
        update_user_meta($user_id, '_stgh_crm_position', sanitize_text_field($_POST['_stgh_crm_position']));
        update_user_meta($user_id, '_stgh_crm_site', sanitize_text_field($_POST['_stgh_crm_site']));
    }




    /**
     * Page ticket attachment
     */
    public function page_attachment()
    {
        Stg_Helper_UploadFiles::handlerPageAttachment();
    }


    /**
     * Add endpoint
     */
    public function widget_help_catcher_endpoint()
    {
        add_rewrite_endpoint('widget_help_catcher', EP_PERMALINK);
    }

    /**
     * Create js link for widget help catcher
     */
    public function page_widget_help_catcher()
    {
        $widgetType = get_query_var('widget_help_catcher');

        $currentUser = stgh_get_current_user();

        if (!empty($widgetType) && strtolower($widgetType == 'js')) {
            $templateParams = array(
                'host' => get_site_url(),
                'ajaxurl' => admin_url('admin-ajax.php'),
                'pluginName' => STG_HELPDESK_NAME,

                'hl' => stgh_get_option('recaptcha_hl'),
                'rkey' => stgh_get_option('recaptcha_key'),
                'rcenable' => stg_recaptcha_enabled(),

                'submit' => __('Submit', STG_HELPDESK_TEXT_DOMAIN_NAME),
                'attachfile' => __('Attach file', STG_HELPDESK_TEXT_DOMAIN_NAME),

                'name' => __('Name', STG_HELPDESK_TEXT_DOMAIN_NAME),
                'nameValue' => ($currentUser)? $currentUser->user_nicename:"",
                'email' => __('E-mail', STG_HELPDESK_TEXT_DOMAIN_NAME),
                'emailValue' => ($currentUser)? $currentUser->user_email:"",
                'subject' => __('Subject', STG_HELPDESK_TEXT_DOMAIN_NAME),
                'message' => __('Message', STG_HELPDESK_TEXT_DOMAIN_NAME),

                'loaderurl' => STG_HELPDESK_URL . "images/ajax-loader.gif",
                'requiredMsg' => __('This field is required', STG_HELPDESK_TEXT_DOMAIN_NAME),
                'emailMsg' => __('Please inter a valid email address', STG_HELPDESK_TEXT_DOMAIN_NAME),
            );


            $out = file_get_contents(STG_HELPDESK_ROOT . '/public/js/widget/widget-help-catcher.js');

            foreach ($templateParams as $k => $v) {
                $out = str_ireplace('{%' . $k . '%}', $v, $out);
            }

            header('Content-Type: application/x-javascript');
            echo $out;
            exit;
        }
    }

    function create_taxonomy_custom_fields()
    {
        // headers
        $labels = array(
            'name' => __('Custom Fields', STG_HELPDESK_TEXT_DOMAIN_NAME),
            'singular_name' => __('Custom Field', STG_HELPDESK_TEXT_DOMAIN_NAME),
            'search_items' => __('Search Custom Fields', STG_HELPDESK_TEXT_DOMAIN_NAME),
            'all_items' => __('All Custom Fields', STG_HELPDESK_TEXT_DOMAIN_NAME),
            'parent_item' => __('Parent Custom Field', STG_HELPDESK_TEXT_DOMAIN_NAME),
            'parent_item_colon' => __('Parent Custom Field:', STG_HELPDESK_TEXT_DOMAIN_NAME),
            'edit_item' => __('Edit Custom Field', STG_HELPDESK_TEXT_DOMAIN_NAME),
            'view_item' => __('View Custom Field', STG_HELPDESK_TEXT_DOMAIN_NAME),
            'update_item' => __('Update Custom Field', STG_HELPDESK_TEXT_DOMAIN_NAME),
            'add_new_item' => __('Add New Custom Field', STG_HELPDESK_TEXT_DOMAIN_NAME),
            'new_item_name' => __('New Custom Field Name', STG_HELPDESK_TEXT_DOMAIN_NAME),
            'menu_name' => __('Custom Fields', STG_HELPDESK_TEXT_DOMAIN_NAME),
        );


        $capChecker = stgh_get_option('man_rules',false);

        $capabilites = (!$capChecker)? array(
            'manage_terms'  => 'manage_customfields',
            'edit_terms'    => 'edit_customfields',
            'delete_terms'  => 'delete_customfields',
            'assign_terms'  => 'assign_customfields'
        ):array(
	        'manage_terms' => 'edit_ticket',
	        'edit_terms' => 'edit_ticket',
	        'delete_terms' => 'edit_ticket',
	        'assign_terms' => 'edit_posts'
        );
        // params
        $args = array(
            'label' => '',
            'labels' => $labels,
            'public' => true,
            'show_in_nav_menus' => false,
            'show_in_menu' => true,
            'show_admin_column' => true,
            'show_ui' => true,
            'show_tagcloud' => false,
            'update_count_callback' => '',
            'rewrite' => true,
            'capabilities' => $capabilites,
            'meta_box_cb' => null,
            '_builtin' => false,
            'show_in_quick_edit' => false,
        );
        register_taxonomy('customfields', array(STG_HELPDESK_POST_TYPE), $args);
	    self::createGDPRfield();

    }

    function customfields_taxonomy_add_new_meta_field(){
        // this will add the custom meta field to the add new term page
        ?>
        <div class="form-field">
            <label for="stgh_term_meta[custom_field_type_meta]"><?php _e( 'Type', STG_HELPDESK_TEXT_DOMAIN_NAME ); ?></label>


            <select name="stgh_term_meta[custom_field_type_meta]" id="stgh_term_meta[custom_field_type_meta]">
                <option value="text">Text</option>
                <option value="dropdown">Dropdown menu</option>
                <option value="date">Date</option>
                <option value="textarea">Textarea</option>
                <option value="radio">Radio</option>
                <option value="checkboxes">Checkboxes</option>
                <option value="hidden">Hidden</option>
            </select>
        </div>
        <?php
    }


    function customfields_taxonomy_edit_new_meta_field($term) {
        $values = Stg_Helper_Custom_Forms::getTermMetaValue($term->term_id);
        ?>

        <?php $value = (isset($values['custom_field_type_meta']))? $values['custom_field_type_meta']:'';?>
        <tr class="form-field">
            <th scope="row" valign="top"><label for="stgh_term_meta[custom_field_type_meta]"><?php _e( 'Type', STG_HELPDESK_TEXT_DOMAIN_NAME ); ?></label></th>
            <td>
                <select name="stgh_term_meta[custom_field_type_meta]" id="stgh_term_meta[custom_field_type_meta]">
                    <option value="text" <?=($value == 'text')? "selected=selected":""?>>Text</option>
                    <option value="dropdown" <?=($value == 'dropdown')? "selected=selected":""?>>Dropdown menu</option>
                    <option value="date" <?=($value == 'date')? "selected=selected":""?>>Date</option>
                    <option value="textarea" <?=($value == 'textarea')? "selected=selected":""?>>Textarea</option>
                    <option value="radio" <?=($value == 'radio')? "selected=selected":""?>>Radio</option>
                    <option value="checkboxes" <?=($value == 'checkboxes')? "selected=selected":""?>>Checkboxes</option>
                    <option value="hidden" <?=($value == 'hidden')? "selected=selected":""?>>Hidden</option>
                </select>
            </td>
        </tr>

        <?php $value = (isset($values['custom_field_required_meta']))? $values['custom_field_required_meta']:'';?>
        <tr class="form-field">
            <th scope="row" valign="top"><label for="stgh_term_meta[custom_field_required_meta]"><?php _e( 'Required', STG_HELPDESK_TEXT_DOMAIN_NAME ); ?></label></th>
            <td>
                <input <?=($value == 'on')? "checked=checked":""?> type="checkbox" name="stgh_term_meta[custom_field_required_meta]" id="stgh_term_meta[custom_field_required_meta]"/>
            </td>
        </tr>


        <?php $value = (isset($values['custom_field_options_meta']))? $values['custom_field_options_meta']:'';?>
        <tr class="form-field">
            <th scope="row" valign="top"><label for="stgh_term_meta[custom_field_options_meta]"><?php _e( 'Options', STG_HELPDESK_TEXT_DOMAIN_NAME ); ?></label></th>
            <td>
                <textarea rows="10" name="stgh_term_meta[custom_field_options_meta]" id="stgh_term_meta[custom_field_options_meta]"><?=$value?></textarea>
                <p><i><?php _e('Add each value from new line',STG_HELPDESK_TEXT_DOMAIN_NAME)?></i></p>
            </td>
        </tr>

        <?php $value = (isset($values['custom_field_dvalue_meta']))? $values['custom_field_dvalue_meta']:'';?>
        <tr class="form-field">
            <th scope="row" valign="top"><label for="stgh_term_meta[custom_field_options_meta]"><?php _e( 'Default value', STG_HELPDESK_TEXT_DOMAIN_NAME ); ?></label></th>
            <td>
                <input type="text" name="stgh_term_meta[custom_field_dvalue_meta]" id="stgh_term_meta[custom_field_dvalue_meta]" value="<?php echo $value?>">
            </td>
        </tr>

        <?php $value = (isset($values['custom_field_format_meta']))? $values['custom_field_format_meta']:'dd.mm.yy';?>
        <tr class="form-field">
            <th scope="row" valign="top"><label for="stgh_term_meta[custom_field_format_meta]"><?php _e( 'Format', STG_HELPDESK_TEXT_DOMAIN_NAME ); ?></label></th>
            <td>
                <input type="text" name="stgh_term_meta[custom_field_format_meta]" id="stgh_term_meta[custom_field_format_meta]" value="<?php echo $value?>">
            </td>
        </tr>

        <?php $value = (isset($values['custom_field_container_meta']))? $values['custom_field_container_meta']:'';?>
        <tr class="form-field stgh_noshow">
            <th scope="row" valign="top"><label for="stgh_term_meta[custom_field_container_meta]"><?php _e( 'Container', STG_HELPDESK_TEXT_DOMAIN_NAME ); ?></label></th>
            <td>
                <select name="stgh_term_meta[custom_field_container_meta]" id="stgh_term_meta[custom_field_container_meta]">
                    <option value="ticket" <?=($value == 'ticket')? "selected=selected":""?>>ticket</option>
                    <?php
                        //<option value="contact" <?=($value == 'contact')? "selected=selected":""?>>contact</option>
                    ?>
                </select>
            </td>
        </tr>

        <?php
    }

    function save_taxonomy_custom_meta( $term_id ) {
        Stg_Helper_Custom_Forms::setTermMetaValue($term_id,$_POST);
    }


    function add_customfields_column_header( $columns ){
        unset($columns['description']);
        unset($columns['slug']);
        unset($columns['posts']);
        $columns['custom_field_type_meta'] = 'Type';
        $columns['custom_field_required_meta'] = 'Required';
        $columns['customforms'] = 'Count';
        return $columns;
    }

    function add_customfields_column_content($out, $column_name, $term_id) {
        $values = Stg_Helper_Custom_Forms::getTermMetaValue($term_id);
        $value = (isset($values[$column_name]))? $values[$column_name]:"";

        if($column_name == 'customforms')
        {
            $value=Stg_Helper_Custom_Forms::getFormCountByField($term_id);
        }

        $out = $value;
        return $out;
    }

    function add_savedreply_column_header( $columns ){
        unset($columns['slug']);
        unset($columns['posts']);
        $columns['custom_field_toqa_meta'] = 'Q&A';
        $columns['custom_field_topic_meta'] = 'Q&A Topic';
        return $columns;
    }

    function add_savedreply_column_content($out, $column_name, $term_id) {
        $values = Stg_Helper_Custom_Forms::getTermMetaValue($term_id);
        $value = (isset($values[$column_name]))? $values[$column_name]:"";

        $out = $value;
        return $out;
    }

    function delete_customfields($term, $taxonomy){
        if($taxonomy == "customfields")
        {
            $formCount = Stg_Helper_Custom_Forms::getFormCountByField($term);
            if($formCount > 0)
                wp_die('Error: removing prohibited. The object is to use');
        }
        if($taxonomy == "customforms")
        {
            $formCount = Stg_Helper_Custom_Forms::getPageCountByForm($term);
            if($formCount > 0)
                wp_die('Error: removing prohibited. The object is to use');
        }
    }

    function remove_row_actions($actions, $tag){

        unset( $actions['view'] );
        unset( $actions['inline hide-if-no-js'] );
        if($tag->taxonomy == "customfields")
        {
            $formCount = Stg_Helper_Custom_Forms::getFormCountByField($tag->term_id);
            if($formCount >0)
                unset($actions['delete']);
        }

        if($tag->taxonomy == "customforms")
        {

            $formCount = Stg_Helper_Custom_Forms::getPageCountByForm($tag->term_id);
            if($formCount >0)
                unset($actions['delete']);

            if(Stg_Helper_Custom_Forms::isDefaultForm($tag->id,$tag)){
                unset($actions['delete']);
            }
        }

        return $actions;
    }

    function create_taxonomy_custom_forms()
    {
        // headers
        $labels = array(
            'name' => __('Contact Forms', STG_HELPDESK_TEXT_DOMAIN_NAME),
            'singular_name' => __('Contact Form', STG_HELPDESK_TEXT_DOMAIN_NAME),
            'search_items' => __('Search Contact Forms', STG_HELPDESK_TEXT_DOMAIN_NAME),
            'all_items' => __('All Contact Forms', STG_HELPDESK_TEXT_DOMAIN_NAME),
            'parent_item' => __('Parent Contact Form', STG_HELPDESK_TEXT_DOMAIN_NAME),
            'parent_item_colon' => __('Parent Contact Form:', STG_HELPDESK_TEXT_DOMAIN_NAME),
            'edit_item' => __('Edit Contact Form', STG_HELPDESK_TEXT_DOMAIN_NAME),
            'view_item' => __('View Contact Form', STG_HELPDESK_TEXT_DOMAIN_NAME),
            'update_item' => __('Update Contact Form', STG_HELPDESK_TEXT_DOMAIN_NAME),
            'add_new_item' => __('Add New Contact Form', STG_HELPDESK_TEXT_DOMAIN_NAME),
            'new_item_name' => __('New Contact Form Name', STG_HELPDESK_TEXT_DOMAIN_NAME),
            'menu_name' => __('Contact Forms', STG_HELPDESK_TEXT_DOMAIN_NAME),
        );
        // params
        $args = array(
            'label' => '',
            'labels' => $labels,
            'public' => true,
            'show_in_nav_menus' => true,
            'show_ui' => true,
            'show_tagcloud' => false,
            'hierarchical' => false,
            'update_count_callback' => '',
            'rewrite' => true,
            'capabilities' => array(
	            'manage_terms' => 'edit_ticket',
	            'edit_terms' => 'edit_ticket',
	            'delete_terms' => 'edit_ticket',
	            'assign_terms' => 'edit_posts'
            ),
            'meta_box_cb' => null,
            'show_admin_column' => false,
            '_builtin' => false,
            'show_in_quick_edit' => false,
        );
        register_taxonomy('customforms', array(STG_HELPDESK_POST_TYPE), $args);
        Stg_Helper_Custom_Forms::createDefaultForm();
    }

    function customforms_taxonomy_add_new_meta_field(){
        // this will add the custom meta field to the add new term page
        ?>
        <?php
    }

    function add_customforms_column_header( $columns ){
        unset($columns['description']);
        unset($columns['slug']);
        unset($columns['posts']);
        $columns['shortcode'] = 'Shortcode';
        $columns['custom_form_confirm_url_meta'] = 'Confirmation URL';
        $columns['count'] = 'Count';
        return $columns;
    }

    function add_customforms_column_content($out, $column_name, $term_id) {
        if($column_name == 'shortcode')
        {
            if(Stg_Helper_Custom_Forms::isDefaultForm($term_id))
            {
                $value = "[".STG_HELPDESK_SHORTCODE_TICKET_FORM."]";
            }else {
                $value = Stg_Helper_Custom_Forms::getFormShortCode($term_id);
            }
        }

        if($column_name == 'count')
        {
            if(Stg_Helper_Custom_Forms::isDefaultForm($term_id))
            {
                $value = '-';
            }else {
                $value = Stg_Helper_Custom_Forms::getPageCountByForm($term_id);
            }

        }

        if($column_name == 'custom_form_confirm_url_meta')
        {
            $values = Stg_Helper_Custom_Forms::getTermMetaValue($term_id);
            $value = (isset($values['custom_form_confirm_url_meta']))? $values['custom_form_confirm_url_meta']:"";

        }

        $out = $value;
        return $out;
    }




    function create_taxonomy_savedreplies()
    {
        // headers
        $labels = array(
            'name' => __('Replies', STG_HELPDESK_TEXT_DOMAIN_NAME),
            'singular_name' => __('Reply', STG_HELPDESK_TEXT_DOMAIN_NAME),
            'search_items' => __('Search Replies', STG_HELPDESK_TEXT_DOMAIN_NAME),
            'all_items' => __('All Replies', STG_HELPDESK_TEXT_DOMAIN_NAME),
            'parent_item' => __('Parent Reply', STG_HELPDESK_TEXT_DOMAIN_NAME),
            'parent_item_colon' => __('Parent Reply:', STG_HELPDESK_TEXT_DOMAIN_NAME),
            'edit_item' => __('Edit Reply', STG_HELPDESK_TEXT_DOMAIN_NAME),
            'view_item' => __('View Reply', STG_HELPDESK_TEXT_DOMAIN_NAME),
            'update_item' => __('Update Reply', STG_HELPDESK_TEXT_DOMAIN_NAME),
            'add_new_item' => __('Add New Reply', STG_HELPDESK_TEXT_DOMAIN_NAME),
            'new_item_name' => __('New Reply Name', STG_HELPDESK_TEXT_DOMAIN_NAME),
            'menu_name' => __('Saved Replies', STG_HELPDESK_TEXT_DOMAIN_NAME),
        );
        // params
        $args = array(
            'label' => '',
            'labels' => $labels,
            'public' => true,
            'show_in_nav_menus' => false,
            'show_in_menu' => true,
            'show_ui' => true,
            'show_tagcloud' => false,
            'hierarchical' => false,
            'update_count_callback' => '',
            'rewrite' => true,
            'capabilities' => array(
	            'manage_terms' => 'edit_ticket',
	            'edit_terms' => 'edit_ticket',
	            'delete_terms' => 'edit_ticket',
	            'assign_terms' => 'edit_posts'
            ),
            'meta_box_cb' => null,
            'show_admin_column' => false,
            '_builtin' => false,
            'show_in_quick_edit' => false,
        );
        register_taxonomy('savedreply', array(STG_HELPDESK_POST_TYPE), $args);
        self::createDefaultVotingForm();
    }

    public static function createDefaultVotingForm(){

        $defaultForm = "<p>".__('How do you rate my response?',STG_HELPDESK_TEXT_DOMAIN_NAME)."</p><p>{%ticket.voting_form%}</p>";

        $term = get_term_by('name', 'DefaultVotingForm','savedreply');

        if ($term === false) {
            wp_insert_term('DefaultVotingForm', 'savedreply', array('description' => $defaultForm));
        }

    }

	public static function createGDPRfield(){

		$GDPRFieldOptions = sprintf(__('I read and agree with <a href="//%s">terms&conditions</a>', STG_HELPDESK_TEXT_DOMAIN_NAME),
			'google.com');

		$term = get_term_by('name', 'GDPRField','customfields');

		if ($term === false) {
			$result = wp_insert_term('GDPRField', 'customfields', array());

			$values['stgh_term_meta'] = array(
				'custom_field_type_meta' => 'checkboxes',
				'custom_field_options_meta' => $GDPRFieldOptions,
				'custom_field_required_meta' =>'on'
            );

			Stg_Helper_Custom_Forms::setTermMetaValue($result['term_id'],$values);
		}

	}


    function savedreply_add_form_fields(){
        // this will add the custom meta field to the add new term page
        ?>
        <div class="form-field">
            <label for="stgh_term_meta[custom_field_toqa_meta]">
                <input type="checkbox" name="stgh_term_meta[custom_field_toqa_meta]" id="stgh_term_meta[custom_field_toqa_meta]"/>
                <?php _e( 'Convert into public Q&A note', STG_HELPDESK_TEXT_DOMAIN_NAME ); ?>
            </label>
        </div>
        <div class="form-field">
            <label for="stgh_term_meta[custom_field_topic_meta]">
                <?php _e( 'Topic', STG_HELPDESK_TEXT_DOMAIN_NAME ); ?>
            </label>
            <input type="text" name="stgh_term_meta[custom_field_topic_meta]" id="stgh_term_meta[custom_field_topic_meta]"/>
        </div>
        <?php
        $topicList = stgh_qa_topics_list('stgh_qa_topics_list');
        $topics = stgh_get_qa_topics();
        ?>
        <?php
        if(!empty($topics)){
            $class = '';
        }else{
            $class = 'stgh_hide';
        }
        ?>
        <div class="form-field">
            <label for="stgh_qa_topics_list" class="<?=$class?>">
                <?php _e( 'or choose from the most used:', STG_HELPDESK_TEXT_DOMAIN_NAME ); ?>
                <?=$topicList?>
            </label>
        </div>
        <?php
    }

    function edit_cat_description($tag)
    {
        $values = Stg_Helper_Custom_Forms::getTermMetaValue($tag->term_id);
        ?>
        !!!!!!!!!!!!!<table class="form-table">
            <tr>
                <th scope="row" valign="top"><label
                        for="description"><?php _ex('Description', 'Taxonomy Description'); ?></label></th>
                <td>
                    <?php
                    $settings = array('teeny' => true, 'wpautop' => false, 'media_buttons' => false, 'quicktags' => true, 'textarea_rows' => '15', 'textarea_name' => 'description');
                    wp_editor(html_entity_decode($tag->description, ENT_QUOTES, 'UTF-8'), 'stgh_savedreply_description', $settings);
                    ?>
                    <br/>
                    <span
                        class="description"><?php _e('The description is not prominent by default; however, some themes may show it.'); ?></span>
                </td>
            </tr>

            <?php do_action('stgh_qa_edit_savedreply', $values); ?>

        </table>
        <?php
    }

    function customforms_edit_cat_description($term)
    {
        $values = Stg_Helper_Custom_Forms::getTermMetaValue($term->term_id);
        ?>


        <table class="form-table">
            <tr>
                <th scope="row" valign="top"><label
                        for="description"><?php _ex('Form Constructor', 'Taxonomy Description',STG_HELPDESK_TEXT_DOMAIN_NAME); ?></label></th>
                <td>
                    <?php
                    $settings = array('teeny' => true, 'wpautop' => false, 'media_buttons' => false, 'quicktags' => true, 'textarea_rows' => '15', 'textarea_name' => 'description');
                    wp_editor(html_entity_decode($term->description, ENT_QUOTES, 'UTF-8'), 'stgh_customforms_description', $settings);
                    ?>
                </td>
            </tr>
            <tr class="form-field">
                <th scope="row" valign="top"><label for="stgh_term_meta[custom_form_confirm_url_meta]"><?php _e( 'Confirmation URL', STG_HELPDESK_TEXT_DOMAIN_NAME ); ?></label></th>
                <td>
                    <?php $value = (isset($values['custom_form_confirm_url_meta']))? $values['custom_form_confirm_url_meta']:'';
                        echo "<input type=\"text\" name=\"stgh_term_meta[custom_form_confirm_url_meta]\" id=\"stgh_term_meta[custom_form_confirm_url_meta]\" value=\"{$value}\"/>";
                    ?>

                </td>
            </tr>
             <tr class="form-field">
                <th scope="row" valign="top"><label for="stgh_term_meta[custom_form_options_meta]"><?php _e( 'Shortcode', STG_HELPDESK_TEXT_DOMAIN_NAME ); ?></label></th>
                <td>

                    <?php
                        if(Stg_Helper_Custom_Forms::isDefaultForm($term->term_id,$term))
                        {
                            echo "[".STG_HELPDESK_SHORTCODE_TICKET_FORM."]";
                        }else{
                            echo $value = Stg_Helper_Custom_Forms::getFormShortCode($term->term_id,$term);

                        }
                    ?>
                </td>
            </tr>
        </table>
        <?php
    }


    function taxonomy_tinycme_hide_description()
    {
        global $current_screen;
        if ($current_screen && $current_screen->id == 'edit-savedreply') {
            ?>
            <script type="text/javascript">
                jQuery(function ($) {
                    $('textarea#description').closest('tr.form-field').remove();
                });
            </script>
            <?php
        }
    }


    /**
     * @return Stg_Helpdesk_Init
     */
    public static function getInstance()
    {
        if (null == self::$instance) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    public function cronTicketCloseTask()
    {
        Stg_Helpdesk_Init::$isCron = true;
        $result = stgh_ticket_close_handler();
        Stg_Helpdesk_Init::$isCron = false;

        return $result;
    }

        function run_updater(){
        $pluginData = stgh_get_plugin_data();
        $pluginCurrentVersion = $pluginData['Version'];

        $envatoToken = stgh_get_option('envato_token');


        if(!empty($envatoToken))
        {
            $adapterName = 'Envato';
            $data = array(
                'Envato' => array(
                    'license' => 'envato',
                    'token' => $envatoToken,
                    'itemId' => '17938670'
                )
            );

            $updater = new \StgHelpdesk\Updater\AutoUpdater($adapterName, $pluginCurrentVersion, $data);
        }

    }

    function add_private_note_tab(){
        ?>
        <li class="stgh-ntab stgh-private-note" data-show="stgh-form-private"><?php _e('Private note', STG_HELPDESK_TEXT_DOMAIN_NAME); ?>
        <?php
    }

    function add_ccbcc_link(){
        ?>
        <a id="toggleCcBcc"><?php _e('Cc/Bcc', STG_HELPDESK_TEXT_DOMAIN_NAME); ?></a>
        <?php
    }

    function add_ccbcc(){
        ?>
        <p class="stgh-cc-bcc">
            <label for="stgh-cc"><strong><?php _e('Cc:', STG_HELPDESK_TEXT_DOMAIN_NAME); ?></strong></label>
            <input type="text" value=""  name="stgh-cc" id="stgh-cc">
        </p>

        <p class="stgh-cc-bcc">
            <label for="stgh-bcc"><strong><?php _e('Bcc:', STG_HELPDESK_TEXT_DOMAIN_NAME); ?></strong></label>
            <input type="text" value=""  name="stgh-bcc" id="stgh-bcc">
        </p>
        <?php
    }

     function add_history($ticket_event_meta){
        ?>
        <div class="stgh-ticket-event">
            <ul>
                <?php if (!empty($ticket_event_meta['assignee'])) : ?>
                    <li><label
                            class="stgh-text-bold"><?php _e('Assignee', STG_HELPDESK_TEXT_DOMAIN_NAME); ?></label> <?= $ticket_event_meta['assignee']; ?>
                    </li>
                <?php endif; ?>
                <?php if (!empty($ticket_event_meta['subject'])) : ?>
                    <li><label
                            class="stgh-text-bold"><?php _e('Subject', STG_HELPDESK_TEXT_DOMAIN_NAME); ?></label> <?= $ticket_event_meta['subject']; ?>
                    </li>
                <?php endif; ?>
                <?php if (!empty($ticket_event_meta['status'])) : ?>
                    <li><label
                            class="stgh-text-bold"><?php _e('Status', STG_HELPDESK_TEXT_DOMAIN_NAME); ?></label> <?= $ticket_event_meta['status']; ?>
                    </li>
                <?php endif; ?>
                <?php if (!empty($ticket_event_meta['contact'])) : ?>
                    <li><label
                            class="stgh-text-bold"><?php _e('Contact', STG_HELPDESK_TEXT_DOMAIN_NAME); ?></label> <?= $ticket_event_meta['contact']; ?>
                    </li>
                <?php endif; ?>
                <?php if (!empty($ticket_event_meta['category'])) : ?>
                    <li><label
                            class="stgh-text-bold"><?php _e('Category', STG_HELPDESK_TEXT_DOMAIN_NAME); ?></label> <?= $ticket_event_meta['category']; ?>
                    </li>
                <?php endif; ?>
                <?php if (!empty($ticket_event_meta['tags'])) : ?>
                    <li><label
                            class="stgh-text-bold"><?php _e('Tags', STG_HELPDESK_TEXT_DOMAIN_NAME); ?></label> <?= $ticket_event_meta['tags']; ?>
                    </li>
                <?php endif; ?>
                <?php if (!empty($ticket_event_meta['vote'])) : ?>
                    <li><label
                                class="stgh-text-bold"><?php _e('Vote', STG_HELPDESK_TEXT_DOMAIN_NAME); ?></label> <?= $ticket_event_meta['vote']; ?>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
        <?php
    }

    function stgh_core_settings($def)
    {

        $settings = array(
            'core' => array(
                'name' => __('General', STG_HELPDESK_TEXT_DOMAIN_NAME),
                'options' => array(
                    array(
                        'name' => __('Default assignee', STG_HELPDESK_TEXT_DOMAIN_NAME),
                        'id' => 'assignee_default',
                        'type' => 'select',
                        'desc' => __('New tickets will be automatically assigned to that person', STG_HELPDESK_TEXT_DOMAIN_NAME),
                        'options' => stgh_list_users_manager(),
                        'default' => ''
                    ),
                    array(
                        'name' => __('File Upload Configuration', STG_HELPDESK_TEXT_DOMAIN_NAME),
                        'id' => 'stgh_enable_attachment',
                        'type' => 'checkbox',
                        'default' => stgh_get_option('stgh_enable_attachment', false),
                        'desc' => __('Do you want to allow users and helpdesk managers to upload attachments? <br> (Option is also used for file send through the mail server)', STG_HELPDESK_TEXT_DOMAIN_NAME)
                    ),
                    array(
                        'name' => __('Check customer satisfaction', STG_HELPDESK_TEXT_DOMAIN_NAME),
                        'desc' => __('Send a voting message to the customer when the ticket is closed', STG_HELPDESK_TEXT_DOMAIN_NAME),
                        'id' => 'stg_ticket_vote_for_close',
                        'type' => 'checkbox',
                        'default' => stgh_get_option('stg_ticket_vote_for_close', false),
                    ),
                    array(
                        'name' => __('Tracking', STG_HELPDESK_TEXT_DOMAIN_NAME),
                        'type' => 'heading',
                    ),
                    array(
                        'name' => __('Open tracking', STG_HELPDESK_TEXT_DOMAIN_NAME),
                        'id' => 'open_tracking',
                        'type' => 'checkbox',
                        'default' => false,
                        'desc' => __('Enable open tracking', STG_HELPDESK_TEXT_DOMAIN_NAME)
                    ),
                    array(
                        'name' => __('Auto reply', STG_HELPDESK_TEXT_DOMAIN_NAME),
                        'type' => 'heading',
                    ),
                    array(
                        'name' => __('Status', STG_HELPDESK_TEXT_DOMAIN_NAME),
                        'id' => 'enable_open',
                        'type' => 'checkbox',
                        'default' => stgh_get_option('enable_auto_reply', false),
                        'desc' => __('Enable auto reply', STG_HELPDESK_TEXT_DOMAIN_NAME)
                    ),

                    array(
                        'name' => __('Message', STG_HELPDESK_TEXT_DOMAIN_NAME),
                        'hidden' => true,
                        'id' => 'content_auto_reply',
                        'type' => 'editor',
                        'default' => __('<br /><p>Hello, <br /> We just got your help request! And do our best to answer emails as soon as possible, with most inquiries receiving a response within about a day.</p>', STG_HELPDESK_TEXT_DOMAIN_NAME),
                        'editor_settings' => array('quicktags' => true, 'textarea_rows' => 10, 'wpautop' => false, 'teeny' => true),
                    ),
                    array(
                        'name' => __('Email footer', STG_HELPDESK_TEXT_DOMAIN_NAME),
                        'type' => 'heading',
                    ),
                    array(
                        'name' => __('Footer content', STG_HELPDESK_TEXT_DOMAIN_NAME),
                        'id' => 'stgh_email_footer_content',
                        'type' => 'editor',
                        'default' => '',
                        'editor_settings' => array('quicktags' => true, 'textarea_rows' => 10, 'wpautop' => false, 'teeny' => true),

                    ),
                    array(
                        'name' => __('Plugin pages', STG_HELPDESK_TEXT_DOMAIN_NAME),
                        'type' => 'heading',
                    ),
                    array(
                        'name' => __('Create plugin pages', STG_HELPDESK_TEXT_DOMAIN_NAME),
                        'type' => 'custom',
                        'custom' => '<a id="stgh_chk_conf" class="button" href="' .  stgh_link_to_create_user_pages() . '">' . __('Create',
                                STG_HELPDESK_TEXT_DOMAIN_NAME) . '</a> <img class="stgh_loader_image" hidden id="stgh_loader" src="' . STG_HELPDESK_URL . 'images/ajax-loader.gif" >',
                    ),
                    array(
                        'name' => __('Add headers to emails body', STG_HELPDESK_TEXT_DOMAIN_NAME),
                        'id' => 'add_header_to_ebody',
                        'type' => 'checkbox',
                        'default' => stgh_get_option('add_header_to_ebody', false)
                    ),
                )
            ),
        );

        return array_merge($def, $settings);

    }

    function stgh_comments_filter_private($params){

        foreach($params['meta_query'] as $key => $meta)
        {
            if(isset($meta["key"]) && isset($meta["value"]) && isset($meta["compare"])) {
                if ($meta["key"] == "_stgh_type_color" && ($meta["value"] == "private" || $meta["value"] == "history") && $meta["compare"] == "!=") {
                    unset($params['meta_query'][$key]);
                }
            }
        }

        return $params;
    }

    function stgh_updates_integrations($def)
    {

        $settings = array(
            'integrations' => array(
                'name' => __('Integrations', STG_HELPDESK_TEXT_DOMAIN_NAME),
                'options' => array(
                    array(
                        'type' => 'heading',
                        'name' => __('Addon\'s update', STG_HELPDESK_TEXT_DOMAIN_NAME)
                    ),
                    array(
                        'name' => __('Envato Personal Token', STG_HELPDESK_TEXT_DOMAIN_NAME),
                        'id' => 'envato_token',
                        'type' => 'text',
                    )
                )
            ),
        );

        $res = array_merge($def, $settings);

        if(isset($def['integrations']['options']))
        {
            $res['integrations']['options'] = array_unique(array_merge($def['integrations']['options'], $settings['integrations']['options']), SORT_REGULAR);
        }

        return $res;

    }

    
    function stgh_comments_filter_menu($items){
        $addItems = array(
            'private' => __('Private notes', STG_HELPDESK_TEXT_DOMAIN_NAME),
            'history' => __('History', STG_HELPDESK_TEXT_DOMAIN_NAME)
        );
                $addItems['vote'] = __('Vote', STG_HELPDESK_TEXT_DOMAIN_NAME);
                return array_merge($items,$addItems);
    }

}

