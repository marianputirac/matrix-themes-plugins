<?php

namespace StgHelpdesk\Ticket;

use StgHelpdesk\Helpers\Stg_Helper_Email;
use StgHelpdesk\Helpers\Stg_Helper_Logger;
use StgHelpdesk\Helpers\Stg_Helper_Saved_Replies;
use StgHelpdesk\Helpers\Stg_Helper_UploadFiles;

/**
 * Class Stg_Helpdesk_Ticket
 * @package StgHelpdesk\Core
 */
class Stg_Helpdesk_Ticket
{
    public static $posts = array();

    /**
     * @var array|null|\WP_Post
     */
    protected $post;


    protected $postMeta;

    /**
     * Instance of this class.
     */
    protected static $instance = null;

    /**
     * Initialize the plugin
     * @param $postId
     */
    private function __construct($postId = null)
    {
        $this->post = get_post($postId);
        $this->postMeta = get_post_meta($postId);
    }


    /**
     * @return \WP_Query
     */
    public static function userTickets($postPerPage = -1, $paged = false, $userId = false)
    {
        $current_user = stgh_get_current_user();
        $currentUserId = isset($current_user->ID) ? $current_user->ID : '';

        if($userId !== false && $userId>0)
            $currentUserId = $userId;

        $args = array(
            //'author' => isset($current_user->ID) ? $current_user->ID : '',
            '_author_or_stgh_contact' => $currentUserId,
            'post_type' => STG_HELPDESK_POST_TYPE,
            'post_status' => 'any',
            'order' => 'DESC',
            'orderby' => 'date',
            'posts_per_page' => $postPerPage,
            'no_found_rows' => false,
            'cache_results' => false,
            'paged' => $paged,
            'update_post_term_cache' => false,
            'update_post_meta_cache' => false,
            'meta_query' => array(
                array(
                    'key' => '_stgh_contact',
                    'value' => $currentUserId
                )
            )
        );
        return new \WP_Query($args);
    }


    /**
     * @param array $params
     * @return array
     */
    public static function get($params = array(), $returnQuery = false)
    {
        $defaults = array(
            'post_type' => STG_HELPDESK_POST_TYPE,
            'post_status' => 'any',
            'posts_per_page' => -1,
            'no_found_rows' => false,
            'cache_results' => true,
            'update_post_term_cache' => true,
            'update_post_meta_cache' => true,
        );

        $args = wp_parse_args($params, $defaults);

        $query = new \WP_Query($args);

        if($returnQuery === true)
            return $query;

        if (empty($query->posts)) {
            return array();
        } else {
            return $query->posts;
        }
    }

    public static function getCount($params = array())
    {
        $defaults = array(
            'post_type' => STG_HELPDESK_POST_TYPE,
            'post_status' => 'any',
            'posts_per_page' => -1,
            'nopaging' => true,
            'fields' => 'ids',
            'cache_results' => true,
            'update_post_term_cache' => false,
            'update_post_meta_cache' => false,
        );


        $args = wp_parse_args($params, $defaults);

        $query = new \WP_Query($args);


        return $query->found_posts;
    }

    // TO DO
    /**
     * @param $post_id
     * @param string $status
     * @param array $args
     * @param string $output
     * @return array|\WP_Query
     */
    public static function getTicketReplies($post_id, $status = 'any', $args = array(), $output = 'replies')
    {
        $allowed_status = array(
            'any',
            'read',
            'unread'
        );

        if (!is_array($status)) {
            $status = (array)$status;
        }

        foreach ($status as $key => $reply_status) {
            if (!in_array($reply_status, $allowed_status)) {
                unset($status[$key]);
            }
        }

        if (empty($status)) {
            $status = 'any';
        }

        $defaults = array(
            'post_parent' => $post_id,
            'post_type' => STG_HELPDESK_COMMENTS_POST_TYPE,
            'post_status' => $status,
            'order' => 'ASC',
            'orderby' => 'date',
            'posts_per_page' => -1,
            'no_found_rows' => true,
            'cache_results' => false,
            'update_post_term_cache' => false,
            'update_post_meta_cache' => false,
        );

        $args = wp_parse_args($args, $defaults);

        $replies = new \WP_Query($args);

        if (is_wp_error($replies)) {
            return $replies;
        }

        return 'wp_query' === $output ? $replies : $replies->posts;

    }

    /**
     * TO DO
     *
     * Get the link to a ticket reply
     *
     * @since 3.2
     *
     * @param int $reply_id ID of the reply to get the link to
     *
     * @return string|bool Reply link or false if the reply doesn't exist
     */
    public static function getReplyLink($reply_id)
    {
        $reply = get_post($reply_id);
        if (empty($reply)) {
            return false;
        }

        if (STG_HELPDESK_COMMENTS_POST_TYPE !== $reply->post_type || 0 === (int)$reply->post_parent) {
            return false;
        }

        $parent_id = $reply->post_parent;
        $comments = Stg_Helpdesk_TicketComments::instance()->get();

        if (empty($comments)) {
            return false;
        }

        $position = 0;

        foreach ($comments as $key => $post) {

            if ($reply_id === $post->ID) {
                $position = $key + 1;
            }
        }

        if (0 === $position) {
            return false;
        }

        $page = ceil($position / 10);
        $base = 1 !== (int)$page ? add_query_arg('as-page', $page,
            get_permalink($parent_id)) : get_permalink($parent_id);
        $link = $base . "#reply-$reply_id";

        return esc_url($link);
    }

    /**
     * Insert a new ticket in the database
     *
     * This function is a wrapper function for wp_insert_post
     * with additional checks specific to the ticketing system
     *
     * @param array $post Ticket (post) data
     * @param bool|int $user_id ID register user
     *
     * @return bool|int|\WP_Error
     */
    public static function saveTicket($post = array(), $user_id = false, $fromMail = false, $source)
    {
        $recaptchaRequired = stg_recaptcha_enabled();

        if(!$fromMail && $recaptchaRequired == 1){
            if(isset($post['g-recaptcha-response'])) {
                $response = wp_remote_post('https://www.google.com/recaptcha/api/siteverify', array(
                    'method' => 'POST',
                    'timeout' => 50,
                    'redirection' => 5,
                    'httpversion' => '1.0',
                    'blocking' => true,
                    'headers' => array(),
                    'body' => array('secret' => stgh_get_option('recaptcha_secret_key'), 'response' => $post['g-recaptcha-response']),
                    'cookies' => array()
                ));
                $result = json_decode($response['body']);
                if($result->success === false) {
                    $_REQUEST['stgh_message'] = getNotificationMarkup('failure',
                        __('reCAPTCHA failed', STG_HELPDESK_TEXT_DOMAIN_NAME));
                    return false;
                }
            }else{
                $_REQUEST['stgh_message'] = getNotificationMarkup('failure',
                    __('reCAPTCHA failed', STG_HELPDESK_TEXT_DOMAIN_NAME));
                return false;
            }
        }



        $current_user = stgh_get_current_user();

        if (!$user_id && !$current_user) {
            $_REQUEST['stgh_message'] = getNotificationMarkup('failure',
                __('Sign in before you can post a message. <a href="'.wp_login_url().'">Sign in</a>', STG_HELPDESK_TEXT_DOMAIN_NAME));

            return false;
        }

        if (empty($post['stg_ticket_subject'])) {
            $post['stg_ticket_subject'] = __('No subject', STG_HELPDESK_TEXT_DOMAIN_NAME);
        }

        if (!isset($post['stg_ticket_message'])) {

	        $post['stg_ticket_message'] = __('No message', STG_HELPDESK_TEXT_DOMAIN_NAME);
        }

        /**
         * If not user id, check user from email in db or get current user
         */
        if (!$user_id) {
            if (isset($post['stg_ticket_email']) && trim($post['stg_ticket_email']) != $current_user->user_email) {
                $user = get_user_by('email', trim($post['stg_ticket_email']));
                $user_id = $user->ID;
            } else {
                $user_id = $current_user->ID;
            }
        }
        $_POST['post_status_override'] = 'stgh_new';

        $data = array(
            'post_content' => '',
            'post_name' => '',
            'post_title' => '',
            'post_status' => $_POST['post_status_override'],
            'post_type' => STG_HELPDESK_POST_TYPE,
            'post_author' => '',
            'ping_status' => 'closed',
            'comment_status' => 'closed',
        );

        /* Sanitize the data */
        if (isset($post['stg_ticket_subject']) && !empty($post['stg_ticket_subject'])) {
            $data['post_title'] = sanitize_text_field($post['stg_ticket_subject']);
        }

        if (isset($post['stg_ticket_message']) && !empty($post['stg_ticket_message'])) {
            $data['post_content'] = htmlentities2(wp_kses_post($post['stg_ticket_message']));
        }
        if (isset($post['stg_ticket_author']) && !empty($post['stg_ticket_author'])) {
            $data['post_author'] = sanitize_text_field($post['stg_ticket_author']);
        }

        /* Set the current user as author if the field is empty. */
        if (empty($data['post_author'])) {
            $data['post_author'] = $user_id;
        }

        /**
         * Insert the post in database using the regular WordPress wp_insert_post
         * function with default values corresponding to our post type structure.
         *
         * @var boolean
         */

        $data['post_content'] = strip_shortcodes($data['post_content']);

        $ticket_id = wp_insert_post($data, false);

        if (false === $ticket_id) {
            $_REQUEST['stgh_message'] = getNotificationMarkup('failure',
                __('An error occurred. Can`t add ticket', STG_HELPDESK_TEXT_DOMAIN_NAME));
            return false;
        }

        if (!$current_user) {
            $successMessage = __('Thank you for contacting us. Your message has been successfully sent. We will contact you very soon!',
                STG_HELPDESK_TEXT_DOMAIN_NAME);
            $_REQUEST['stgh_message_success'] = $successMessage;
            $_REQUEST['ticket_id'] = $ticket_id;
        } else {
            if(!(isset($_REQUEST["stg_saveTicket"]) && $_REQUEST["stg_saveTicket"] == "1" && $current_user->ID == $user_id)){
                $successMessage = __('Thank you for contacting us. Your message has been successfully sent. We will contact you very soon!',
                    STG_HELPDESK_TEXT_DOMAIN_NAME);
                $_REQUEST['stgh_message_success'] = $successMessage;
                $_REQUEST['ticket_id'] = $ticket_id;
            }
        }



        // set default ticket priority
        add_post_meta($ticket_id, '_stgh_priority', 'normal', true);
        add_post_meta($ticket_id, '_stgh_contact', $data['post_author'], true);

        self::addTicketUserMeta($ticket_id, $data['post_author']);

        // set default assigment for the ticket
        $defaultAssign = stgh_get_option('assignee_default', 0);
        if (0 != $defaultAssign) {
            add_post_meta($ticket_id, '_stgh_assignee', $defaultAssign, true);
            $_POST['stgh_assignee'] = $defaultAssign; // for history
        }


        if(isset($post['stgh_custom_fields']))
            Stg_Helpdesk_MetaBoxes::saveCustomFields($ticket_id, $post['stgh_custom_fields']);


        if (isset($post['stgh_tags'])) {
            Stg_Helpdesk_MetaBoxes::saveTag($ticket_id, $post['stgh_tags']);
            $term = get_term($post['stgh_tags'], STG_HELPDESK_POST_TYPE_TAG);
            if(!is_wp_error($term))
                $_POST['stgh_tags'] = $term->name;
        }


        //default event history meta
        add_post_meta($ticket_id, 'post_title_history', $data['post_title'], true);
        //add_post_meta($ticket_id, 'post_status_override_history', $data['post_status'], true);
        add_post_meta($ticket_id, 'stgh_crm_contact_value_history', $data['post_author'], true);


        // set ticketID
        if (isset($post['stg_messsageId']) && $post['stg_messsageId']) {
            $last_reply_message_id = $post['stg_messsageId'];
            self::setLastMessageId($ticket_id, $last_reply_message_id);
        } else {
            $last_reply_message_id = Stg_Helper_Email::getNewMessageId($data['post_author']);
        }
        self::addReference($ticket_id, $last_reply_message_id);


        if ($ticket_id) {
            add_post_meta($ticket_id, '_stgh_type_source', $source, true);
        }

        $params = array(
            'id' => $ticket_id,
            'source' =>$source,
        );

        do_action('stgh_ticket_saved', $params);

        // Save category
        if (isset($post['stgh_category'])) {
            Stg_Helpdesk_MetaBoxes::saveCategory($ticket_id, $post['stgh_category']);
        }

        Stg_Helpdesk_MetaBoxes::addEventTicketToReply($ticket_id);
        return $ticket_id;
    }

    /**
     * Save in ticket metadata author name, email, role
     *
     * @param $ticket_id
     * @param $author_id
     */
    public static function addTicketUserMeta($ticket_id, $author_id)
    {
        // set author nickname, roles and email
        $author = get_userdata($author_id);

        // CRM name
        $name = stgh_crm_get_user_full_name($author_id);
        if (empty($name))
            $name = $author->user_nicename;

        // Save ticket meta
        add_post_meta($ticket_id, '_stgh_ticket_author_name', $name, true);
        add_post_meta($ticket_id, '_stgh_ticket_author_email', $author->user_email, true);
        add_post_meta($ticket_id, '_stgh_ticket_author_roles', implode(' ', $author->roles), true);

    }

    public static function getTicketUserMeta($ticket_id)
    {
        return array(
            'name' => get_post_meta($ticket_id, '_stgh_ticket_author_name', true),
            'email' => get_post_meta($ticket_id, '_stgh_ticket_author_email', true),
            'roles' => get_post_meta($ticket_id, '_stgh_ticket_author_roles', true),
        );
    }


    /**
     * Get ticket changes
     * @param $ticket_id
     * @return array
     */
    public static function getTicketEventMeta($ticket_id)
    {
        $events = array();

        $assigneeOld = get_post_meta($ticket_id, '_stgh_assignee_ticket_event_old', true);
        $assigneeNew = get_post_meta($ticket_id, '_stgh_assignee_ticket_event_new', true);

        if (strlen($assigneeOld) || strlen($assigneeNew)) {
            $events['assignee'] = self::createEventMessage(
                stgh_crm_get_user_full_name($assigneeOld),
                stgh_crm_get_user_full_name($assigneeNew)
            );
        }

        $statusOld = get_post_meta($ticket_id, '_post_status_override_ticket_event_old', true);
        $statusNew = get_post_meta($ticket_id, '_post_status_override_ticket_event_new', true);
        if (strlen($statusOld) || strlen($statusNew)) {
            $statusEventArray = stgh_get_statuses();
            $events['status'] = self::createEventMessage(
                isset($statusEventArray[$statusOld]) ? $statusEventArray[$statusOld] : '',
                isset($statusEventArray[$statusNew]) ? $statusEventArray[$statusNew] : ''
            );
        }

        $subjectOld = get_post_meta($ticket_id, '_post_title_ticket_event_old', true);
        $subjectNew = get_post_meta($ticket_id, '_post_title_ticket_event_new', true);
        if (strlen($subjectOld) || strlen($subjectOld)) {
            $events['subject'] = self::createEventMessage($subjectOld, $subjectNew);
        }

        $categoryOld = get_post_meta($ticket_id, '_stgh_category_ticket_event_old', true);
        $categoryNew = get_post_meta($ticket_id, '_stgh_category_ticket_event_new', true);
        if (strlen($categoryOld) || strlen($categoryNew)) {
            $categoryOldInfo = get_term_by('id', $categoryOld, STG_HELPDESK_POST_TYPE_CATEGORY);
            $categoryNewInfo = get_term_by('id', $categoryNew, STG_HELPDESK_POST_TYPE_CATEGORY);

            $events['category'] = self::createEventMessage(
                (is_object($categoryOldInfo) && isset($categoryOldInfo->name)) ? $categoryOldInfo->name : '',
                (is_object($categoryNewInfo) && isset($categoryNewInfo->name)) ? $categoryNewInfo->name : ''
            );
        }

        $contactOld = get_post_meta($ticket_id, '_stgh_crm_contact_value_ticket_event_old', true);
        $contactNew = get_post_meta($ticket_id, '_stgh_crm_contact_value_ticket_event_new', true);

        if (strlen($contactOld) || strlen($contactNew)) {
            $events['contact'] = self::createEventMessage(
                stgh_crm_get_user_full_name($contactOld),
                stgh_crm_get_user_full_name($contactNew)
            );
        }

        $tagsOld = get_post_meta($ticket_id, '_stgh_tags_ticket_event_old', true);
        $tagsNew = get_post_meta($ticket_id, '_stgh_tags_ticket_event_new', true);
        if (strlen($tagsOld) || strlen($tagsNew)) {
            $events['tags'] = self::createEventMessage(
                $tagsOld,
                $tagsNew
            );
        }

        $voteArray = Stg_Helper_Saved_Replies::getVoteArray();
        $vote =  get_post_meta($ticket_id,'stgh_reply_vote',true);
        if(isset($voteArray[$vote]['name'])){
            $events['vote'] = self::createEventMessage(
                false,
                $voteArray[$vote]['name']
            );
        }


        return $events;
    }

    /**
     * Create mevent message
     * @param $oldValue
     * @param $newValue
     * @return string
     */
    protected static function createEventMessage($oldValue, $newValue)
    {
        $oldValue = trim($oldValue);
        $newValue = trim($newValue);

        if (!empty($oldValue) && !empty($newValue)) {
            return sprintf(__('changed from %1$s to %2$s', STG_HELPDESK_TEXT_DOMAIN_NAME), $oldValue, $newValue);
        } else if (empty($oldValue) && !empty($newValue)) {
            return sprintf(__('set to %1$s ', STG_HELPDESK_TEXT_DOMAIN_NAME), $newValue);
        } else if (!empty($oldValue) && empty($newValue)) {
            return sprintf(__('deleted (<s> %1$s </s>)', STG_HELPDESK_TEXT_DOMAIN_NAME), $oldValue);
        } else {
            return '';
        }
    }

    public static function addReference($ticket_id, $reference)
    {
        $references = self::getReferences($ticket_id);
        if ($references) {
            return update_post_meta($ticket_id, '_stgh_references', $references . ' ' . $reference);
        } else {
            return add_post_meta($ticket_id, '_stgh_references', $reference, true);
        }
    }

    public static function getReferences($ticket_id)
    {
        return get_post_meta($ticket_id, '_stgh_references', true);
    }

    public static function setLastMessageId($ticket_id, $value)
    {
        return add_post_meta($ticket_id, '_stgh_msg_lastid', $value, true) ? true :
            update_post_meta($ticket_id, '_stgh_msg_lastid', $value);
    }

    public static function setMetaMessageId($ticket_id, $messageId)
    {
        add_post_meta($ticket_id, '_stgh_mid', $messageId, true);
    }

    /**
     * Ticket open
     *
     * @param $postId
     * @return int|\WP_Error
     */
    public static function open($postId)
    {
        return self::changeStatus($postId, 'stgh_new');
    }

    /**
     * Ticket close
     *
     * @param null $postId
     * @return int|void|\WP_Error
     */
    public static function close($postId)
    {
        return self::changeStatus($postId, 'stgh_closed');
    }

    /**
     * Note ticket as answered
     *
     * @param $postId
     * @return int|\WP_Error
     */
    public static function setInAnswered($postId)
    {
        return self::changeStatus($postId, 'stgh_answered');
    }

    /**
     * Note ticket as unanswered
     *
     * @param $postId
     * @return int|\WP_Error
     */
    public static function setInNotAnswered($postId)
    {
        return self::changeStatus($postId, 'stgh_notanswered');
    }



    /**
     * change ticket status
     *
     * @param $postId
     * @param $status
     * @return int|\WP_Error
     */
    public static function changeStatus($postId, $status)
    {
        if($status == 'stgh_closed' && stg_vote_for_close_enabled()){
            $commentId = Stg_Helper_Saved_Replies::insertDefaultVotingForm($postId);
            if($commentId)
                Stg_Helper_Email::sendEventTicket('agent_reply', $commentId);

        }

        unset($_POST['post_status_override']);
        $my_post = array(
            'ID' => $postId,
            'post_status' => $status
        );

        remove_action('save_post_' . STG_HELPDESK_POST_TYPE, 'stgh_save_custom_fields');
        $updated = wp_update_post($my_post);

        $version_object = stgh_get_current_version_object();
        $version_object->registerSaveTicketFields();

        do_action('stgh_ticket_status_updated', $postId, 'closed', $updated);

        if (array_key_exists($postId, static::$posts)) {
            unset(static::$posts[$postId]);
        }

        return $updated;
    }

    /**
     * @param $postId
     * @param $title
     * @return int|\WP_Error
     */
    public static function updateTitle($postId, $title)
    {
        $my_post = array(
            'ID' => $postId,
            'post_title' => $title
        );

	    $updated = wp_update_post($my_post);

	    do_action('stgh_ticket_title_updated', $postId, $updated);

	    if (array_key_exists($postId, static::$posts)) {
            unset(static::$posts[$postId]);
        }

        return $updated;
    }

    /**
     * Get ticket priority
     *
     * @return mixed
     */
    public function getPriority()
    {
        return get_post_meta($this->post->ID, '_stgh_priority', true);
    }

    /**
     * Get ticket current status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->post->post_status;
    }

    /**
     * @param $postId
     * @return false|null|\WP_User
     */
    public static function getAssignedTo($postId)
    {
        $user = get_user_by('id', intval(get_post_meta($postId, '_stgh_assignee', true)));

        if (false !== $user) {
            return $user;
        }

        return null;
    }

    /**
     * Check ticket is opened
     *
     * @return bool
     */
    public function isOpened()
    {
        return !$this->isClosed();
    }

    /**
     * Check ticket is closed
     *
     * @return bool
     */
    public function isClosed()
    {
        return 'stgh_closed' == $this->getStatus();
    }

    /**
     * Get ticket author
     *
     * @return false|null|\WP_User
     */
    public function getUser()
    {
        $user = get_user_by('id', $this->post->post_author);

        if (false !== $user) {
            return $user;
        }

        return null;
    }

    /**
     * Get ticket contact
     *
     * @return false|null|\WP_User
     */
    public function getContact()
    {
        if(!is_object($this->post))
            return null;

        $contactId = (isset($this->postMeta['_stgh_contact'][0]))? $this->postMeta['_stgh_contact'][0]:null;

        $user = get_user_by('id', $contactId);

        if (false !== $user) {
            return $user;
        }

        return null;
    }

    public function getPost()
    {
        return $this->post;
    }


    public function getPostMeta()
    {
        return $this->postMeta;
    }

    /**
     * @param null $postId
     * @return Stg_Helpdesk_Ticket
     */
    public static function getInstance($postId = null)
    {
        if (!array_key_exists($postId, static::$posts)) {
            $post = new static($postId);

            static::$posts[$postId] = $post;

            return $post;
        }

        return static::$posts[$postId];
    }


    /**
     * @param $ticket_id
     * @return bool|false|\WP_User
     */
    public static function getAuthor($ticket_id)
    {
        if (!$ticket_id) {
            return false;
        }
        $ticket = get_post($ticket_id, ARRAY_A);
        if (!$ticket || $ticket['post_type'] != STG_HELPDESK_POST_TYPE) {
            return false;
        }
        return get_userdata($ticket['post_author']);
    }

    /**
     * @param $replyId
     */
    public static function removeTicketReply($replyId, $ticketId){
        $replyId = intval($replyId);
        $ticketId = intval($ticketId);

        $letterSrc = stgh_get_eml_src($ticketId,$replyId);

        if(is_readable($letterSrc))
        {
            unlink($letterSrc);
        }

        $attachs = Stg_Helper_UploadFiles::getAttachments($replyId);

        foreach($attachs as $key => $attach){
            wp_delete_attachment($key, true);

            $homePath = Stg_Helper_UploadFiles::get_home_path();
            $siteUrl = get_site_url();
            $newUrl = str_replace($siteUrl,$homePath,$attach['url']);

            unlink($newUrl);
        }

        wp_delete_post($replyId, true);
    }

    public static function removeTicketRepliesAndAttachs($ticketId){
        $ticketId = intval($ticketId);

        $replies = self::getTicketReplies($ticketId);

        foreach($replies as $reply)
        {
            self::removeTicketReply($reply->ID,$ticketId);
        }

        $attachs = Stg_Helper_UploadFiles::getAttachments($ticketId);

        foreach($attachs as $attKey => $attach){
            wp_delete_attachment($attKey, true);

            $homePath = Stg_Helper_UploadFiles::get_home_path();
            $siteUrl = get_site_url();
            $newUrl = str_replace($siteUrl,$homePath,$attach['url']);

            unlink($newUrl);
        }

        self::removeTicketDir($ticketId);
    }

    public static function getTicketDirPath($ticketId){
        $uploadDir = wp_upload_dir();
        $subdir = "/stg-helpdesk/ticket_".$ticketId;
        $ticketDirPath = $uploadDir["basedir"].$subdir;

        return $ticketDirPath;
    }

    public static function removeTicketDir($ticketId){
        $path = self::getTicketDirPath($ticketId);

        if (is_dir($path)) {
            if ($dh = opendir($path)) {
                while (($file = readdir($dh)) !== false) {
                    if(is_file($path.'/'.$file))
                    {
                        unlink($path.'/'.$file);
                    }
                }
                closedir($dh);
            }

            return rmdir($path);
        }

    }


    public static function removeTrashReply(){
        global $wpdb;

        if($_GET['ss'] != 'dFgdb_Qws12!')
            return;

        set_time_limit(0);
        ignore_user_abort(true);

        //Remove from DB
        $wpdb->query($wpdb->prepare("delete attach, existing, pm from  $wpdb->posts as attach inner join  $wpdb->posts as existing ON attach.post_parent = existing.ID left join  $wpdb->posts as deleted ON existing.post_parent = deleted.ID LEFT JOIN $wpdb->postmeta as pm ON attach.ID = pm.post_id WHERE deleted.ID IS NULL and existing.post_type=%s and attach.post_type = %s",STG_HELPDESK_COMMENTS_POST_TYPE,'attachment'));
        $wpdb->query($wpdb->prepare("delete attach, pm from $wpdb->posts as attach left join $wpdb->posts as deleted ON attach.post_parent = deleted.ID LEFT JOIN $wpdb->postmeta as pm ON attach.ID = pm.post_id WHERE deleted.ID IS NULL and attach.post_type = %s and attach.guid like %s",'attachment','%' . $wpdb->esc_like('stg-helpdesk') . '%'));
        $wpdb->query($wpdb->prepare("delete reply, pm from $wpdb->posts as reply left join $wpdb->posts as deleted ON reply.post_parent = deleted.ID LEFT JOIN $wpdb->postmeta as pm ON reply.ID = pm.post_id WHERE deleted.ID IS NULL and reply.post_type = %s", STG_HELPDESK_COMMENTS_POST_TYPE));
        $wpdb->query($wpdb->prepare("delete pm from $wpdb->postmeta as pm left join $wpdb->posts as deleted ON pm.post_id = deleted.ID WHERE deleted.ID IS NULL and pm.meta_key like %s", '%' . $wpdb->esc_like('_stgh_') . '%'));
        $wpdb->query($wpdb->prepare("delete draft, pm from $wpdb->posts as draft left join $wpdb->postmeta as pm ON pm.post_id = draft.ID WHERE draft.post_type = %s and draft.post_status = %s", STG_HELPDESK_POST_TYPE, 'auto-draft'));



        //Remove from stg-helpdesk
        $existing = $wpdb->get_col($wpdb->prepare("select ID from $wpdb->posts WHERE post_type = %s", STG_HELPDESK_POST_TYPE));


        $uploadDir = wp_upload_dir();
        $subdir = "/stg-helpdesk/";
        $path = $uploadDir["basedir"].$subdir;

        if (is_dir($path)) {
            if ($dh = opendir($path)) {
                while (($file = readdir($dh)) !== false) {
                    if(is_dir($path.'/'.$file) && $file != '.' && $file != '..')
                    {
                        $ticketNumber = str_replace('ticket_','',$file);
                        if(!in_array($ticketNumber,$existing)) {
                            self::removeTicketDir($ticketNumber);
                        }
                    }
                }
                closedir($dh);
            }
        }

    }

}
