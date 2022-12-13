<?php
use StgHelpdesk\Ticket\Stg_Helpdesk_MetaBoxes;
use StgHelpdesk\Ticket\Stg_Helpdesk_Ticket;
use StgHelpdesk\Ticket\Stg_Helpdesk_TicketComments;
use StgHelpdesk\Helpers\Stg_Helper_UploadFiles;
use StgHelpdesk\Helpers\Stg_Helper_Email;
use StgHelpdesk\Admin\Stg_Helpdesk_Help_Catcher;
use StgHelpdesk\Helpers\Stg_Helper_Custom_Forms;

if (!function_exists('stgh_get_statuses')) {
    function stgh_get_statuses()
    {
        return \StgHelpdesk\Core\PostType\Stg_Helpdesk_Post_Type_Statuses::get();
    }
}

if (!function_exists('stgh_get_priorities')) {
    function stgh_get_priorities()
    {
        return \StgHelpdesk\Core\PostType\Stg_Helpdesk_Post_Type_Priority::get();
    }
}

if (!function_exists('stgh_ticket_get_priority')) {
    function stgh_ticket_get_priority()
    {
        return Stg_Helpdesk_Ticket::getInstance()->getPriority();
    }
}

if (!function_exists('stgh_ticket_get_status')) {
    function stgh_ticket_get_status($postId = null)
    {
        return Stg_Helpdesk_Ticket::getInstance($postId)->getStatus();
    }
}

if (!function_exists('stgh_ticket_get_user')) {
    function stgh_ticket_get_user($postId = null)
    {
        return Stg_Helpdesk_Ticket::getInstance($postId)->getUser();
    }
}

if (!function_exists('stgh_ticket_get_human_creation_time')) {
    function stgh_ticket_get_human_creation_time()
    {
        return human_time_diff(get_the_time('U'), current_time('timestamp'));
    }
}

if (!function_exists('stgh_ticket_get_comments')) {
    function stgh_ticket_get_comments($params = array())
    {
        return Stg_Helpdesk_TicketComments::instance()->get($params);
    }
}

if (!function_exists('stgh_ticket_get_comments_count')) {
    function stgh_ticket_get_comments_count($params = array())
    {
        return Stg_Helpdesk_TicketComments::instance()->getCount($params);
    }
}

if (!function_exists('stgh_ticket_is_opened')) {
    function stgh_ticket_is_opened($postId = null)
    {
        return Stg_Helpdesk_Ticket::getInstance($postId)->isOpened();
    }
}

if (!function_exists('stgh_ticket_is_closed')) {
    function stgh_ticket_is_closed($postId = null)
    {
        return Stg_Helpdesk_Ticket::getInstance($postId)->isClosed();
    }
}

if (!function_exists('stgh_is_called_directly')) {
    function stgh_is_called_directly()
    {
        if (!defined('WPINC')) {
            die;
        }
    }
}

if (!function_exists('stgh_save_custom_fields')) {
    function stgh_save_custom_fields($postId, $post, $update)
    {
        Stg_Helpdesk_MetaBoxes::saveMetaBoxFields($postId, $post, $update);
    }
}

if (!function_exists('stgh_save_custom_fields_3_6')) {
    function stgh_save_custom_fields_3_6($postId, $post)
    {
        remove_action('save_post', 'stgh_save_custom_fields_3_6');
        if ($post->post_type == STG_HELPDESK_POST_TYPE) {
            Stg_Helpdesk_MetaBoxes::saveMetaBoxFields($postId);
        }
    }
}

if (!function_exists('stgh_ticket_get_named_status')) {
    function stgh_ticket_get_named_status($postId = null)
    {
        $status = stgh_ticket_get_status($postId);
        $statuses = stgh_get_statuses();

        if (array_key_exists($status, $statuses)) {
            return $statuses[$status];
        }

        return '';
    }
}

if (!function_exists('stgh_ticket_assigned_to')) {
    function stgh_ticket_assigned_to($postId)
    {
        return Stg_Helpdesk_Ticket::getAssignedTo($postId);
    }
}

if (!function_exists('stgh_ticket_admin_link')) {
    function stgh_ticket_admin_link($id)
    {
        return admin_url('post.php?post=' . $id) . '&action=edit&post_type=' . STG_HELPDESK_POST_TYPE;
    }
}

if (!function_exists('stgh_link_to_admin_panel')) {
    function stgh_link_to_admin_panel()
    {
        return admin_url('edit.php?post_type=' . STG_HELPDESK_POST_TYPE);
    }
}

if (!function_exists('stgh_link_to_all_user_tickets')) {
    function stgh_link_to_all_user_tickets($data, $byCompany = false)
    {
        $path = 'edit.php?post_type=' . STG_HELPDESK_POST_TYPE;
        if ($byCompany) {
            $path .= '&crmCompany=' . urlencode($data);
        } else {
            $path .= '&authorId=' . $data;
        }
        return admin_url($path);
    }
}

if (!function_exists('stgh_get_qa_topics();')) {
    function stgh_get_qa_topics()
    {
        $result = array();

        $savedreplies = get_terms('savedreply', array('hide_empty' => false));

        foreach($savedreplies as $savedreply)
        {
            $values = Stg_Helper_Custom_Forms::getTermMetaValue($savedreply->term_id);
            if(!empty($values['custom_field_topic_meta']))
                $result[$values['custom_field_topic_meta']] = $values['custom_field_topic_meta'];
        }

        return $result;
    }
}


if (!function_exists('stgh_ticket_get_categories')) {
    function stgh_ticket_get_categories()
    {
        return get_terms(STG_HELPDESK_POST_TYPE_CATEGORY, array('hide_empty' => false));
    }
}


if (!function_exists('stgh_ticket_get_category')) {
    function stgh_ticket_get_category($postId)
    {
        $res = wp_get_post_terms($postId, STG_HELPDESK_POST_TYPE_CATEGORY, array());

        if(is_wp_error($res))
            return null;

        if (!empty($res)) {
            return $res[0];
        }

        return null;
    }
}

if (!function_exists('stgh_ticket_get_related_tickets')) {
    function stgh_ticket_get_related_tickets($userId, $postId, $page = 1)
    {
        $userCompany = stgh_crm_get_user_company($userId);

        if (false !== $userCompany) {
            $userIds = stgh_crm_get_users_by_company($userCompany);
            $metaQuery = array('relation' => 'OR');
            foreach($userIds as $userId)
            {
                $metaQuery[] = array('key' => '_stgh_contact','value' => $userId);
            }
        }else{
            $metaQuery = array(
                array(
                    'key' => '_stgh_contact',
                    'value' => $userId
                )
            );
        }

        return new \WP_Query(
            array(
                'post_type' => STG_HELPDESK_POST_TYPE,
                'post_status' => 'any',
                'paged' => $page,
                'posts_per_page' => 5,
                'post__not_in' => array($postId),
                'orderby' => 'post_date',
                'order' => 'DESC',
                'cache_results' => false,
                'update_post_term_cache' => false,
                'update_post_meta_cache' => false,
                'meta_query' => $metaQuery
            )
        );
    }
}

if (!function_exists('stgh_ticket_get_tags_all')) {
    function stgh_ticket_get_tags_all()
    {
        return get_terms(STG_HELPDESK_POST_TYPE_TAG, array('hide_empty' => false));
    }
}


if (!function_exists('stgh_ticket_get_tags')) {
    function stgh_ticket_get_tags($postId, $count = 3, $all = false)
    {
        //$tags = wp_get_post_tags($postId, array('fields' => 'names'));

        $args = array(
            "fields" => ($all === false)? 'names':'all'
        );

        $tags = wp_get_object_terms($postId, STG_HELPDESK_POST_TYPE_TAG, $args);

        return array_slice($tags, 0, $count);
    }
}

if (!function_exists('stgh_ticket_count_answers')) {
    function stgh_ticket_count_answers($postId)
    {
        return Stg_Helpdesk_TicketComments::instance($postId)->getCount(array('extra_params' => array('filter_key' => 'all')));

        /*
        global $wpdb;

        $query = 'SELECT COUNT(`ID`) FROM `' . $wpdb->posts . '`
                      WHERE `post_parent`= ' . $postId . '
                      AND `post_status` NOT IN ("trash")
                      AND `post_type`="' . STG_HELPDESK_COMMENTS_POST_TYPE . '"';

        return intval($wpdb->get_var($query));
        */
    }
}

if (!function_exists('stgh_ticket_get_last_answer')) {
    /**
     * @param int $postId
     * @return \WP_Post
     */
    function stgh_ticket_get_last_answer($postId)
    {
        $comments = Stg_Helpdesk_TicketComments::instance($postId)->get();

        return (!empty($comments))? reset($comments):null;
    /*
        $args = array(
            'post_parent' => $postId,
            'post_type' => STG_HELPDESK_COMMENTS_POST_TYPE,
            'orderby' => 'date',
            'order' => 'DESC',
            'posts_per_page' => 1

        );
        $my_query = new \WP_Query($args);
        if ($my_query->have_posts()) {
            return reset($my_query->posts);
        }

        return null;
  */
    }
}

if (!function_exists('stgh_ticket_get_first_answer')) {
    /**
     * @param int $postId
     * @return \WP_Post
     */
    function stgh_ticket_get_first_answer($postId)
    {
        $args = array(
            'post_parent' => $postId,
            'post_type' => STG_HELPDESK_COMMENTS_POST_TYPE,
            'orderby' => 'date',
            'order' => 'ASC',
            'posts_per_page' => 1

        );
        $my_query = new \WP_Query($args);
        if ($my_query->have_posts()) {
            return reset($my_query->posts);
        }

        return null;
    }
}

if (!function_exists('stgh_update_taxonomy_count')) {
    function stgh_update_taxonomy_count($terms, $taxonomy)
    {
        $object_types = (array)$taxonomy->object_type;
        foreach ($object_types as &$object_type) {
            if (0 === strpos($object_type, 'attachment:'))
                list($object_type) = explode(':', $object_type);
        }

        if ($object_types == array_filter($object_types, 'post_type_exists')) {
            // Only post types are attached to this taxonomy
            stgh_update_taxonomy_count_now($terms, $taxonomy);
        } else {
            // Default count updater
            _update_generic_term_count($terms, $taxonomy);
        }
    }
}


if (!function_exists('stgh_update_taxonomy_count_now')) {
    function stgh_update_taxonomy_count_now($terms, $taxonomy)
    {
        global $wpdb;

        $object_types = (array)$taxonomy->object_type;

        foreach ($object_types as &$object_type)
            list($object_type) = explode(':', $object_type);

        $object_types = array_unique($object_types);

        if (false !== ($check_attachments = array_search('attachment', $object_types))) {
            unset($object_types[$check_attachments]);
            $check_attachments = true;
        }

        if ($object_types)
            $object_types = esc_sql(array_filter($object_types, 'post_type_exists'));

        $stghStatuses = array_keys(stgh_get_statuses());

        foreach ((array)$terms as $term) {
            $count = 0;

            // Attachments can be 'inherit' status, we need to base count off the parent's status if so.
            if ($check_attachments)
                $count += (int)$wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $wpdb->term_relationships, $wpdb->posts p1 WHERE p1.ID = $wpdb->term_relationships.object_id AND ( post_status = 'publish' OR ( post_status = 'inherit' AND post_parent > 0 AND ( SELECT post_status FROM $wpdb->posts WHERE ID = p1.post_parent ) = 'publish' ) ) AND post_type = 'attachment' AND term_taxonomy_id = %d", $term));

            if ($object_types)
                $count += (int)$wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $wpdb->term_relationships, $wpdb->posts WHERE $wpdb->posts.ID = $wpdb->term_relationships.object_id AND post_status IN ('" . implode("', '", $stghStatuses) . "') AND post_type IN ('" . implode("', '", $object_types) . "') AND term_taxonomy_id = %d", $term));


            /** This action is documented in wp-includes/taxonomy.php */
            do_action('edit_term_taxonomy', $term, $taxonomy->name);
            $wpdb->update($wpdb->term_taxonomy, compact('count'), array('term_taxonomy_id' => $term));

            /** This action is documented in wp-includes/taxonomy.php */
            do_action('edited_term_taxonomy', $term, $taxonomy->name);
        }
    }
}


if (!function_exists('stgh_menu_count_tickets')) {
    function stgh_menu_count_tickets()
    {
        $current_user = stgh_get_current_user();
        $statuses = stgh_get_statuses();

        unset($statuses['stgh_closed']);

        $params = array(
            'post_status' => array_keys($statuses)
        );

        if (!stgh_current_user_can('administrator') && stgh_current_user_can('edit_ticket')) {
            $params['meta_query'][] = array(
                'key' => '_stgh_assignee',
                'value' => $current_user->ID,
                'compare' => '=',
            );
        }

        return Stg_Helpdesk_Ticket::getCount($params);
    }
}

if( !function_exists('apache_request_headers') ) {
    function apache_request_headers() {
        $arh = array();
        $rx_http = '/\AHTTP_/';
        foreach($_SERVER as $key => $val) {
            if( preg_match($rx_http, $key) ) {
                $arh_key = preg_replace($rx_http, '', $key);
                $rx_matches = array();

                $rx_matches = explode('_', $arh_key);
                if( count($rx_matches) > 0 and strlen($arh_key) > 2 ) {
                    foreach($rx_matches as $ak_key => $ak_val) $rx_matches[$ak_key] = ucfirst($ak_val);
                    $arh_key = implode('-', $rx_matches);
                }
                $arh[$arh_key] = $val;
            }
        }
        return( $arh );
    }
}

if (!function_exists('stgh_ticket_filter_create_params')) {
    /**
     * Request params for filtration
     * @param $params
     * @return mixed
     */
    function stgh_ticket_filter_create_params($params)
    {
        if (empty($params['extra_params']['filter_key'])) {
            return $params;
        }

        $type = trim($params['extra_params']['filter_key']);
        if ($type == 'replies') {
            $params['meta_query'] = array(
                'relation' => 'OR',
                array(
                    'key' => '_stgh_type_color',
                    'value' => 'agent'
                ),
                array(
                    'key' => '_stgh_type_color',
                    'value' => 'user'
                ),
                array(
                    'key' => '_stgh_type_color',
                    'value' => 'agent-to-other-person'
                )
            );
        } else if ($type == 'history') {
            $params['meta_query'] = array(
                array(
                    'key' => '_stgh_additional_type',
                    'value' => $type
                )
            );
        } else if ($type == 'private' || $type == 'agent' || $type == 'user') {
            $params['meta_query'] = array(
                array(
                    'key' => '_stgh_type_color',
                    'value' => $type
                )
            );
        }else if ($type == 'vote') {
            $params['meta_query'] = array(
                array(
                    'key' => 'stgh_reply_vote',
                    'compare' => 'EXISTS',
                )
            );
        }


        unset($params['extra_params']);
        return $params;
    }
}


if (!function_exists('stgh_add_event_ticket_to_reply')) {
    /**
     * Add value of event to comment if the old value changed
     * @param $postId
     * @param $fields
     * @param $values
     * @param bool $replyId
     * @param bool $userId
     */
    function stgh_add_event_ticket_to_reply($postId, $fields, $values, $replyId = false, $userId = false)
    {
        $isCreate = false;

        $assigned = stgh_ticket_assigned_to($postId);
        $category = stgh_ticket_get_category($postId);

        $assignedID = ($assigned)? $assigned->ID : null;
        $categoryTermId =  ($category)?$category->term_id : null;

        foreach ($fields as $field) {

            if (isset($values[$field]) && strlen($values[$field])) {
                $value = sanitize_text_field($values[$field]);
                $historyValue = addslashes(get_post_meta($postId, $field . '_history', true));

                if (($historyValue != $value) && (!empty($value) || !empty($historyValue))) {
                    if (!$replyId) {
                        if (!$userId) {
                            $current_user = stgh_get_current_user();
                            if(!$current_user) {
                                $userId = null;
                            }
                            else {
                                $userId = $current_user->ID;
                            }
                        }

                        $data = apply_filters('stgh_admin_ticket_comment_args', array(
                            'post_content' => '',
                            'post_type' => STG_HELPDESK_COMMENTS_POST_TYPE,
                            'post_author' => $userId,
                            'post_parent' => $postId,
                            'post_status' => 'publish',
                            'ping_status' => 'closed',
                            'comment_status' => 'closed',
                        ));

                        if($field == 'stgh_crm_contact_value'){
                            Stg_Helper_Email::sendEventTicket('ticket_open', $postId);
                        }

                        do_action('stgh_admin_before_comment_on_ticket', $postId, $data);

                        $replyId = Stg_Helpdesk_TicketComments::addToPost($data, $postId);
                        $isCreate = true;
                    }

                    add_post_meta($replyId, '_' . $field . '_ticket_event_old', $historyValue, true);
                    add_post_meta($replyId, '_' . $field . '_ticket_event_new', $value, true);

                    $refsArray = array(
                        'stgh_assignee'  =>  'assignee',
                        'post_status_override'  =>  'status',
                        'stgh_category'  =>  'category',
                        'stgh_crm_contact_value'  =>  'contact',
                        'post_title' => 'title'
                    );

                    if($value != $historyValue) {
                        $params = array(
                            'id' => $postId,
                            'commentId' => $replyId,
                            $refsArray[$field] => $value,
                        );

                        $params['assignee'] = ($field !== 'stgh_assignee')? $assignedID : $value;
                        $params['category'] = ($field !== 'stgh_category')?$categoryTermId : $value;

                        do_action('stgh_ticket_' . $refsArray[$field] . '_saved', $params);
                    }
                    //update history new value
                    update_post_meta($postId, $field . '_history', $value);

                    //custom type for filtration
                    add_post_meta($replyId, '_stgh_additional_type', 'history', true);
                }
            }
        }

        if ($isCreate) {
            add_post_meta($replyId, '_stgh_type_color', 'history', true);
        }
    }
}

if (!function_exists('stgh_help_catcher_ajax_handler')) {
    /**
     * Ajax handler form
     */
    function stgh_help_catcher_ajax_handler()
    {
        //new user
        $currentUser = stgh_get_current_user_id();
        $result = array('status' => false, 'msg' => __('An error occured try again later', STG_HELPDESK_TEXT_DOMAIN_NAME));

        $user_reg = false;

        $headers = array_change_key_case(apache_request_headers(), CASE_LOWER);

        if (isset($headers['origin'])) {
            header('Access-Control-Allow-Origin: ' . $headers['origin']);
            header('Access-Control-Allow-Credentials: true');
        }


        if (!Stg_Helpdesk_Help_Catcher::isEnabled()) {
            wp_send_json($result);
        }


        if (!$currentUser || (!empty($_POST['stg_ticket_email']) && !get_user_by('email', trim($_POST['stg_ticket_email'])))) {
            if (isset($_POST['stg_ticket_name'])) {
                $user_reg = stgh_register_user($_POST['stg_ticket_email'], $_POST['stg_ticket_name']);

                if (!empty($_REQUEST['stgh_message'])) {
                    $result = array('status' => false, 'msg' => strip_tags($_REQUEST['stgh_message']));
                }

                if (!$user_reg) {
                    wp_send_json($result);
                }
            }
        }

        //ticket

        if (isset($_POST['stg_saveTicket']) && $_POST['stg_saveTicket'] == 2) {


            if (isset($_FILES['stgh_attachment']) && ($_FILES['stgh_attachment']['error'] != 0 && $_FILES['stgh_attachment']['error'] != 4)) {
                wp_send_json(array('status' => false, 'msg' => __('An error has occurred during the file upload', STG_HELPDESK_TEXT_DOMAIN_NAME)));
            }

            if (!empty($headers['origin'])) {
                $source = 'HelpCatcher ' . trim($headers['origin'], '/');
            } else {
                $source = 'HelpCatcher';
            }


            $post_id = Stg_Helpdesk_Ticket::saveTicket($_POST, $user_reg, false, $source);

            if ($post_id) {
                Stg_Helper_UploadFiles::handleUploadsForm($post_id);

                //wp_set_post_tags( $post_id, 'HelpCatcher', true );

                //email
                Stg_Helper_Email::sendEventTicket('ticket_open', $post_id);
                Stg_Helper_Email::sendEventTicket('ticket_assign', $post_id);
            }
        }

        if (!empty($_REQUEST['stgh_message_success'])) {
            $result = array('status' => true, 'msg' => __('<p><b>Message sent!</b><br> We just got your request! And do our best to answer emails as soon as possible</p>', STG_HELPDESK_TEXT_DOMAIN_NAME));
            //'msg' => strip_tags($_REQUEST['stgh_message_success'])
        }

        if (!empty($_REQUEST['stgh_message'])) {
            $result = array('status' => false, 'msg' => strip_tags($_REQUEST['stgh_message']));
        }

        wp_send_json($result);
    }
}

if (!function_exists('stgh_help_catcher_params_ajax_handler')) {
    /**
     * Ajax get params help catcher
     */
    function stgh_help_catcher_params_ajax_handler()
    {
        $headers = array_change_key_case(apache_request_headers(), CASE_LOWER);

        $result = array(
            'params' => array(
                'enable' => Stg_Helpdesk_Help_Catcher::isEnabled(),
                'enableUpload' => Stg_Helpdesk_Help_Catcher::isFileUploadEnabled(),
                'gdprEnable' => Stg_Helpdesk_Help_Catcher::getGDPREnable(),
                'gdprUrl' => Stg_Helpdesk_Help_Catcher::getGDPRUrl(),
                'buttonColor' => Stg_Helpdesk_Help_Catcher::getButtonColor(),
                'headerText' => Stg_Helpdesk_Help_Catcher::getStartConversation(),
                'hideName' => Stg_Helpdesk_Help_Catcher::getHideName(),
                'hideSubject' => Stg_Helpdesk_Help_Catcher::getHideSubject(),
            ),
            'customField' => array(),
        );

        if (isset($headers['origin'])) {
            header('Access-Control-Allow-Origin: ' . $headers['origin']);
            header('Access-Control-Allow-Credentials: true');
        }
        wp_send_json($result);
    }
}

if (!function_exists('stgh_ticket_close_handler')) {

    function stgh_ticket_close_handler()
    {
        $interval = $interval = stgh_get_option('stg_ticket_close_interval');
        $allNeedleTickets = new WP_Query;
        $results = $allNeedleTickets->query(
            array(
                'post_type' => STG_HELPDESK_POST_TYPE,
                'post_status' => 'stgh_answered',
                'posts_per_page' => -1,
                'date_query' => array(
                    'column' => 'post_modified',
                    'before' => '- ' . $interval . ' seconds'
                )
            )
        );

        foreach( $results as $result ){
            Stg_Helpdesk_Ticket::changeStatus($result->ID, 'stgh_closed');
        }

    }
}


if (!function_exists('stgh_save_ticket_reply_vote')) {

    function stgh_save_ticket_reply_vote($postId, $post, $commentId)
    {
        update_post_meta( $postId, 'stgh_post_vote', $post["stgh-ticket-reply-vote"] );
        add_post_meta( $commentId, 'stgh_reply_vote', $post["stgh-ticket-reply-vote"] );
        update_post_meta($commentId, '_stgh_type_color', 'vote');

        $assigned = stgh_ticket_assigned_to($postId);
        $category = stgh_ticket_get_category($postId);

        $params = array(
            'id' => $postId,
            'vote' =>$post["stgh-ticket-reply-vote"],
            'assignee' => (!empty($assigned))? $assigned->ID:null,
            'category' => (!empty($category))? $category->term_id:null
        );

        do_action('stgh_ticket_vote_saved', $params);

        $_REQUEST['stgh_message'] = __('Thank you for voting!',STG_HELPDESK_TEXT_DOMAIN_NAME);
    }
}

if (!function_exists('stgh_ticket_exporter')) {

	function stgh_ticket_exporter( $email_address, $page = 1 )
	{

		$number = 10;
		if ( empty( $email_address ) ) {
			return array(
				'data'  => array(),
				'done'  => true,
			);
		}
		$user         = get_user_by( 'email', $email_address );
		$data = array();

		if ( $user && $user->ID ) {
			// Export tickets
			$args = array(
				'_author_or_stgh_contact' => $user->ID,
				'post_type' => array(STG_HELPDESK_POST_TYPE,STG_HELPDESK_COMMENTS_POST_TYPE),
				'post_status' => 'any',
				'posts_per_page' => $number,
				'no_found_rows' => true,
				'cache_results' => false,
				'paged' => $page,
				'update_post_term_cache' => false,
				'update_post_meta_cache' => false,
				'meta_query' => array(
					array(
						'key' => '_stgh_contact',
						'value' => $user->ID
					)
				)
			);

			$userTickets = get_posts($args);

			if ($userTickets) {
				foreach ($userTickets as $ticketData) {

					$attachments = Stg_Helper_UploadFiles::getAttachments($ticketData->ID);


					switch($ticketData->post_type){
						case STG_HELPDESK_POST_TYPE:
							$data[] = array(
								'group_id' => 'stgh_tickets',
								'group_label' => 'Tickets',
								'item_id' => $ticketData->ID,
								'data' => stgh_prepare_data_for_export($ticketData)
							);
							break;
						case STG_HELPDESK_COMMENTS_POST_TYPE:
							$data[] = array(
								'group_id' => 'stgh_replies',
								'group_label' => 'Tickets Replies',
								'item_id' => $ticketData->ID,
								'data' => stgh_prepare_data_for_export($ticketData)
							);
							break;
					}

					foreach ($attachments as $attachment_id => $attachment) {
						$link = add_query_arg(array('ticket-attachment' => $attachment['id']), home_url());

						//link key

						$key = md5($user->user_email . STG_HELPDESK_SALT_USER);
						$link = add_query_arg(array('uid' => $user->ID, 'key' => $key), $link);

						$attachment['url'] = $link;

						$data[] = array(
							'group_id' => 'stgh_attachs',
							'group_label' => 'Tickets Attachments',
							'item_id' => $attachment_id,
							'data' => stgh_prepare_data_for_export($attachment)
						);
					}
				}
			}

			$done = count( $userTickets ) < $number;
			if($done) {
				// Export user's meta
				$tmp = get_user_meta( $user->ID, '_stgh_crm_company' );
				if ( ! empty( $tmp ) ) {
					$data[] = array(
						'group_id'    => 'stgh_usermeta',
						'group_label' => 'Tickets User Metadata ',
						'item_id'     => '_stgh_crm_company',
						'data'        => array( array( 'name' => '_stgh_crm_company', 'value' => $tmp[0] ) )
					);
				}


				$tmp = get_user_meta( $user->ID, '_stgh_crm_skype' );
				if ( ! empty( $tmp ) ) {
					$data[] = array(
						'group_id'    => 'stgh_usermeta',
						'group_label' => 'Tickets User Metadata ',
						'item_id'     => '_stgh_crm_skype',
						'data'        => array( array( 'name' => '_stgh_crm_skype', 'value' => $tmp[0] ) )
					);
				}

				$tmp = get_user_meta( $user->ID, '_stgh_crm_phone' );
				if ( ! empty( $tmp ) ) {
					$data[] = array(
						'group_id'    => 'stgh_usermeta',
						'group_label' => 'Tickets User Metadata ',
						'item_id'     => '_stgh_crm_phone',
						'data'        => array( array( 'name' => '_stgh_crm_phone', 'value' => $tmp[0] ) )
					);
				}

				$tmp = get_user_meta( $user->ID, '_stgh_crm_position' );
				if ( ! empty( $tmp ) ) {
					$data[] = array(
						'group_id'    => 'stgh_usermeta',
						'group_label' => 'Tickets User Metadata ',
						'item_id'     => '_stgh_crm_position',
						'data'        => array( array( 'name' => '_stgh_crm_position', 'value' => $tmp[0] ) )
					);
				}


				$tmp = get_user_meta( $user->ID, '_stgh_crm_site' );
				if ( ! empty( $tmp ) ) {
					$data[] = array(
						'group_id'    => 'stgh_usermeta',
						'group_label' => 'Tickets User Metadata ',
						'item_id'     => '_stgh_crm_site',
						'data'        => array( array( 'name' => '_stgh_crm_site', 'value' => $tmp[0] ) )
					);
				}
			}
		}

		return array(
			'data'  => $data,
			'done'  => $done,
		);
	}
}

if (!function_exists('stgh_ticket_eraser')) {

	function stgh_ticket_eraser($email_address)
	{

		if ( empty( $email_address ) ) {
			return array(
				'items_removed'  => false,
				'items_retained' => false,
				'messages'       => array(),
				'done'           => true,
			);
		}
		$user         = get_user_by( 'email', $email_address );
		$messages = array();
		$items_removed  = false;
		$items_retained = false;
		$userTickets = array();

		if ( $user && $user->ID ) {
			// Delete tickets & ticket's meta
			$args = array(
				'fields' => 'ids',
				'_author_or_stgh_contact' => $user->ID,
				'post_type' => STG_HELPDESK_POST_TYPE,
				'post_status' => 'any',
				'posts_per_page' => 10,
				'no_found_rows' => true,
				'cache_results' => false,
				'paged' => false,
				'update_post_term_cache' => false,
				'update_post_meta_cache' => false,
				'meta_query' => array(
					array(
						'key' => '_stgh_contact',
						'value' => $user->ID
					)
				)
			);

			$userTickets = get_posts($args);

			if ($userTickets) {
				foreach ($userTickets as $userTicketid) {
					if(wp_delete_post($userTicketid, true)) {
						Stg_Helpdesk_Ticket::removeTicketRepliesAndAttachs($userTicketid);
						$items_removed = true;
					}
				}
			}

			// Delete user's meta
			$tmp = get_user_meta( $user->ID, '_stgh_crm_company' );
			if(!empty($tmp)) {
				$deleted_crm_company = delete_user_meta( $user->ID, '_stgh_crm_company' );
				if ( $deleted_crm_company ) {
					$items_removed = true;
				} else {
					$messages[]     = __( 'Your CRM Profile Company was unable to be removed at this time.', STG_CATCHERS_CRM_TEXT_DOMAIN_NAME );
					$items_retained = true;
				}
			}


			$tmp = get_user_meta( $user->ID, '_stgh_crm_skype' );
			if(!empty($tmp)) {
				$deleted_crm_company = delete_user_meta( $user->ID, '_stgh_crm_skype' );
				if ( $deleted_crm_company ) {
					$items_removed = true;
				} else {
					$messages[]     = __( 'Your CRM Profile Skype was unable to be removed at this time.', STG_CATCHERS_CRM_TEXT_DOMAIN_NAME );
					$items_retained = true;
				}
			}

			$tmp = get_user_meta( $user->ID, '_stgh_crm_phone' );
			if(!empty($tmp)) {
				$deleted_crm_company = delete_user_meta( $user->ID, '_stgh_crm_phone' );
				if ( $deleted_crm_company ) {
					$items_removed = true;
				} else {
					$messages[]     = __( 'Your CRM Profile Phone was unable to be removed at this time.', STG_CATCHERS_CRM_TEXT_DOMAIN_NAME );
					$items_retained = true;
				}
			}

			$tmp = get_user_meta( $user->ID, '_stgh_crm_position' );
			if(!empty($tmp)) {
					$deleted_crm_company = delete_user_meta( $user->ID, '_stgh_crm_position' );
				if ( $deleted_crm_company ) {
					$items_removed = true;
				} else {
					$messages[]     = __( 'Your CRM Profile Position was unable to be removed at this time.', STG_CATCHERS_CRM_TEXT_DOMAIN_NAME );
					$items_retained = true;
				}
			}


			$tmp = get_user_meta( $user->ID, '_stgh_crm_site' );
			if(!empty($tmp)) {
					$deleted_crm_company = delete_user_meta( $user->ID, '_stgh_crm_site' );
				if ( $deleted_crm_company ) {
					$items_removed = true;
				} else {
					$messages[]     = __( 'Your CRM Profile Website was unable to be removed at this time.', STG_CATCHERS_CRM_TEXT_DOMAIN_NAME );
					$items_retained = true;
				}
			}
		}

		$done = count($userTickets) == 0;

		return array(
			'items_removed'  => $items_removed,
			'items_retained' => $items_retained,
			'messages'       => $messages,
			'done'           => $done,
		);
	}
}

if (!function_exists('stgh_prepare_data_for_export')) {
	function stgh_prepare_data_for_export( $inputObj ) {
		$out = array();
		foreach ($inputObj as $key => $value) {
			$out[] = array('name' => $key,'value' => $value);
		}
		return $out;
	}
}

