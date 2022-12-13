<?php
namespace StgHelpdesk\Helpers;

use StgHelpdesk\Admin\Stg_Helpdesk_Admin;
use StgHelpdesk\Core\PostType\Stg_Helpdesk_Post_Type_Statuses;
use StgHelpdesk\Ticket\Stg_Helpdesk_Ticket;
use StgHelpdesk\Ticket\Stg_Helpdesk_TicketComments;

/**
 * Class Stg_Helper_Saved_Replies
 * @package StgHelpdesk\Helpers
 */
class Stg_Helper_Saved_Replies
{

    public static function getLocalizationArray()
    {
        return array(
            'selectMacros' => __('Select macros', STG_HELPDESK_TEXT_DOMAIN_NAME),
            'selectSavedReply' => __('Select saved reply', STG_HELPDESK_TEXT_DOMAIN_NAME)
        );
    }

    public static function getVoteArray(){
        return array(
          '2' => array('name' => _x("Good", "Client vote", STG_HELPDESK_TEXT_DOMAIN_NAME),'color'=>'green'),
          '1' => array('name' => _x("Just ok", "Client vote", STG_HELPDESK_TEXT_DOMAIN_NAME),'color'=>'black'),
          '0' => array('name' => _x("Not good", "Client vote", STG_HELPDESK_TEXT_DOMAIN_NAME),'color'=>'red')
        );
    }

    public static function getSavedRepliesMacros()
    {
	    $fields = get_terms( array(
		    'taxonomy' => 'customfields',
		    'hide_empty' => false,
	    ));

        $macros = array(
            array('id' => 'customer', 'text' => __('Customer', STG_HELPDESK_TEXT_DOMAIN_NAME),
                'children' => array(
                    array('text' => __('First Name', STG_HELPDESK_TEXT_DOMAIN_NAME), 'id' => '{%contact.first_name%}'),
                    array('text' => __('Last Name', STG_HELPDESK_TEXT_DOMAIN_NAME), 'id' => '{%contact.last_name%}'),
                    array('text' => __('Full Name', STG_HELPDESK_TEXT_DOMAIN_NAME), 'id' => '{%contact.full_name%}')
                )),
            array('id' => 'agent', 'text' => __('Agent', STG_HELPDESK_TEXT_DOMAIN_NAME),
                'children' => array(
                    array('text' => __('First Name', STG_HELPDESK_TEXT_DOMAIN_NAME), 'id' => '{%response.author.first_name%}'),
                    array('text' => __('Last Name', STG_HELPDESK_TEXT_DOMAIN_NAME), 'id' => '{%response.author.last_name%}'),
                    array('text' => __('Full Name', STG_HELPDESK_TEXT_DOMAIN_NAME), 'id' => '{%response.author%}')
                )),
            array('id' => 'ticket', 'text' => __('Ticket', STG_HELPDESK_TEXT_DOMAIN_NAME),
                'children' => array(
                    array('text' => __('Ticket Assigned', STG_HELPDESK_TEXT_DOMAIN_NAME), 'id' => '{%ticket.assigned_to%}'),
                    array('text' => __('Ticket ID', STG_HELPDESK_TEXT_DOMAIN_NAME), 'id' => '{%ticket.id%}'),
                    array('text' => __('Ticket Subject', STG_HELPDESK_TEXT_DOMAIN_NAME), 'id' => '{%ticket.subject%}'),
                    array('text' => __('Ticket Quoted Description', STG_HELPDESK_TEXT_DOMAIN_NAME), 'id' => '{%ticket.quoted_description%}'),
                    array('text' => __('Ticket History', STG_HELPDESK_TEXT_DOMAIN_NAME), 'id' => '{%ticket.history%}'),
                    array('text' => __('Ticket Status', STG_HELPDESK_TEXT_DOMAIN_NAME), 'id' => '{%ticket.status%}'),
                    array('text' => __('Ticket Public URL', STG_HELPDESK_TEXT_DOMAIN_NAME), 'id' => '{%ticket.public_url%}'),
                    array('text' => __('Ticket Public URL (without hash)', STG_HELPDESK_TEXT_DOMAIN_NAME), 'id' => '{%ticket.public_url_without_hash%}'),
                    array('text' => __('Ticket Date', STG_HELPDESK_TEXT_DOMAIN_NAME), 'id' => '{%ticket.date%}'),
                                        array('text' => __('Reply Voting Form', STG_HELPDESK_TEXT_DOMAIN_NAME), 'id' => '{%ticket.voting_form%}'),
                                    )));

        if(!is_wp_error($fields) && is_array($fields)) {
	        foreach ( $fields as $field ) {
		        $tmp['text']             = 'Custom field [' . $field->name . ']';
		        $tmp['id']               = '{%customfields.' . $field->term_id . '%}';
		        $macros[2]['children'][] = $tmp;
	        }
        }


        return apply_filters('stgh_saved_replies_macros', $macros);
    }

    public static function getSavedReplies()
    {
        $args = array(
            'orderby' => 'name',
            'order' => 'ASC',
            'hide_empty' => false,
            'exclude' => array(),
            'exclude_tree' => array(),
            'include' => array(),
            'number' => '',
            'fields' => 'all',
            'slug' => '',
            'parent' => '',
            'hierarchical' => true,
            'child_of' => 0,
            'get' => '',
            'name__like' => '',
            'pad_counts' => false,
            'offset' => '',
            'search' => '',
            'cache_domain' => 'core',
            'name' => '',
            'childless' => false,
            'update_term_meta_cache' => true,
            'meta_query' => '',
        );


        $savedRelies = get_terms('savedreply', $args);

        $result = $tmp = array();
        foreach ($savedRelies as $savedRely) {
            $tmp = array('text' => $savedRely->name, 'id' => $savedRely->term_id);
            $result[] = $tmp;
        }

        return $result;
    }

	public static function getSavedRepliesKBAll($args = array())
	{
		$default = array(
			'orderby' => 'name',
			'order' => 'ASC',
			'hide_empty' => false,
			'exclude' => array(),
			'exclude_tree' => array(),
			'include' => array(),
			'number' => '',
			'fields' => 'stgh_with_meta',
			'slug' => '',
			'parent' => '',
			'hierarchical' => true,
			'child_of' => 0,
			'get' => '',
			'name__like' => '',
			'pad_counts' => false,
			'offset' => '',
			'search' => '',
			'cache_domain' => 'core',
			'name' => '',
			'childless' => false,
			'update_term_meta_cache' => true,
			'meta_query' => '',
		);

		$args = array_merge($default,$args);

		$savedRelies = get_terms('savedreply', $args);

		$result = array();
		foreach ($savedRelies as $savedReply) {
			$savedReply->meta_value = unserialize($savedReply->meta_value);
			$result[] = $savedReply;
		}

		return $result;
	}

	public static function getSavedRepliesKBAllOld($args = array())
	{
		$default = array(
			'orderby' => 'name',
			'order' => 'ASC',
			'hide_empty' => false,
			'exclude' => array(),
			'exclude_tree' => array(),
			'include' => array(),
			'number' => '',
			'fields' => 'all',
			'slug' => '',
			'parent' => '',
			'hierarchical' => true,
			'child_of' => 0,
			'get' => '',
			'name__like' => '',
			'pad_counts' => false,
			'offset' => '',
			'search' => '',
			'cache_domain' => 'core',
			'name' => '',
			'childless' => false,
			'update_term_meta_cache' => true,
			'meta_query' => '',
		);
		$args = array_merge($default,$args);
		$savedRelies = get_terms('savedreply', $args);

		$result = $tmp = array();
		foreach ($savedRelies as $savedReply) {
			$meta = get_option("stgh_taxonomy_".$savedReply->term_id);
			if($meta) {
				$savedReply->meta_value = $meta;
				$result[]               = $savedReply;
			}
		}

		return $result;
	}

    public static function getReplyText($replyId = null, $ticketId, $userId, $authorId, $replyTemplate = '')
    {
        global $post;

        $contact = Stg_Helpdesk_Ticket::getInstance($ticketId)->getContact();

        $contactMetas = get_user_meta($userId);
        $responceAuthorMetas = get_user_meta($userId);
        $postMetas = get_post_meta($ticketId);
        $post = get_post($ticketId);
        if ($replyId) {
            $replyTemplate = get_term($replyId, 'savedreply')->description;
        }
        $postStatuses = Stg_Helpdesk_Post_Type_Statuses::get();

        $quotedDescription = "> " . $post->post_content;

        $comments = stgh_ticket_get_comments(array('extra_params' => $_GET));

        $ticketHistory = $quotedDescription;
        $gt = ">>";
        foreach (array_reverse($comments) as $comment) {
            $commentMeta = get_post_meta($comment->ID);
            $commentType = $commentMeta["_stgh_type_color"][0];
            if ($commentType == "private")
                continue;

            $comment->post_content = str_ireplace("<p>", "<p>" . $gt . " ", wpautop($comment->post_content));
            $ticketHistory = $ticketHistory . $comment->post_content;
            $gt .= ">";
        }

        if (isset($postMetas["_stgh_assignee"]))
            $ticketAssignedMeta = get_user_meta($postMetas["_stgh_assignee"]['0']);
        else
            $ticketAssignedMeta = false;

        $publiclink = get_post_permalink($post->ID);

        $publiclink = '<a href="'.get_post_permalink($post->ID).'" target="_blank"> Ticket Page </a>';

        //author now can be  agent - change to contact
        $authorId = $userIdContact = get_post_meta($ticketId, '_stgh_contact', true);
        $clientEmail = get_the_author_meta('email', $authorId);

        $key = md5($clientEmail . STG_HELPDESK_SALT_USER);
        $permalink = add_query_arg(array('uid' => $authorId, 'key' => $key), $publiclink);

        $macrosFull = self::getSavedRepliesMacros();

        $replaces = array();
        foreach ($macrosFull as $macro) {
            foreach ($macro['children'] as $child) {
                $tmp = array();
                $tmp['search'] = $child['id'];

                switch ($child['id']) {
                    case '{%contact.first_name%}':
                        $tmp['replace'] = $contactMetas["first_name"]['0'];
                        break;
                    case '{%contact.last_name%}':
                        $tmp['replace'] = $contactMetas["last_name"]['0'];
                        break;
                    case '{%contact.full_name%}':
                        //$tmp['replace'] = trim($contactMetas["first_name"]['0'] . " " . $contactMetas["last_name"]['0']);
                        $tmp['replace'] = stgh_crm_get_user_full_name($contact->ID);
                        break;
                    case '{%response.author.first_name%}':
                        $tmp['replace'] = $responceAuthorMetas["first_name"]['0'];
                        break;
                    case '{%response.author.last_name%}':
                        $tmp['replace'] = $responceAuthorMetas["last_name"]['0'];
                        break;
                    case '{%response.author%}':
                        $tmp['replace'] = trim($responceAuthorMetas["first_name"]['0'] . " " . $responceAuthorMetas["last_name"]['0']);
                        break;
                    case '{%ticket.assigned_to%}':
                        if ($ticketAssignedMeta === false)
                            $tmp['replace'] = NULL;
                        else
                            $tmp['replace'] = trim($ticketAssignedMeta["first_name"]['0'] . " " . $ticketAssignedMeta["last_name"]['0']);
                        break;
                    case '{%ticket.id%}':
                        $tmp['replace'] = $post->ID;
                        break;
                    case '{%ticket.subject%}':
                        $tmp['replace'] = $post->post_title;
                        break;
                    case '{%ticket.quoted_description%}':
                        $tmp['replace'] = $quotedDescription;
                        break;
                    case '{%ticket.history%}':
                        $tmp['replace'] = $ticketHistory;
                        break;
                    case '{%ticket.status%}':
                        if(isset($postStatuses[$post->post_status]))
                            $tmp['replace'] = $postStatuses[$post->post_status];
                        else
                            $tmp['replace'] = '';
                        break;
                    case '{%ticket.public_url%}':
                        $tmp['replace'] = $permalink;
                        break;
                    case '{%ticket.public_url_without_hash%}':
                        $tmp['replace'] = $publiclink;
                        break;
                    case '{%ticket.date%}':
                        $tmp['replace'] = $post->post_date;
                        break;
                    case '{%ticket.voting_form%}':
                        $tmp['replace'] = self::getVotingForm($permalink);
                        break;
	                default:
		                $temp = explode('.',$child['id']);

		                if($temp[0] == "{%customfields"){
		                	$termId = str_replace('%}','',$temp[1]);
			                $fieldValues = Stg_Helper_Custom_Forms::getCustomFieldValue($ticketId,$termId);
			                $tmp['replace'] = $fieldValues['t'];
		                }

                }
                $replaces[] = $tmp;
            }

        }


        foreach ($replaces as $item) {
            $replyTemplate = str_ireplace($item['search'], $item['replace'], $replyTemplate);
        }

        return $replyTemplate;
    }

    public static function getVotingForm($ticketLink){

        $voteArray = self::getVoteArray();

        $votingForm = "<div class=\"stgh_ticket_voting_form\">";
        foreach($voteArray as $vote => $desc)
        {
            $votingForm .= "<span id=\"stgh_ticket_vote_{$vote}\" ><a style=\"color:{$desc['color']};\" href=\"{$ticketLink}&v={$vote}\">{$desc['name']}</a></span>&nbsp;";
        }
        $votingForm .= "</div>";

        return $votingForm;
    }

    public static function insertDefaultVotingForm($postId){

        $votingTerm = get_term_by('name', 'DefaultVotingForm','savedreply');
        $author = Stg_Helpdesk_Ticket::getAuthor($postId);

        $contact = get_post_meta($postId, '_stgh_contact', true);
        $contactId = $contact ? $contact : false;

        $defaultVoteForm = self::getReplyText($votingTerm->term_id, $postId,$author->data->ID, $contactId);

        $data = array (
            'post_content' => $defaultVoteForm,
            'post_type' => 'stgh_ticket_comments',
            'post_author' => stgh_get_current_user_id(),
            'post_parent' => $postId,
            'post_status' => 'publish',
            'ping_status' => 'closed',
            'comment_status' => 'closed' );

        return Stg_Helpdesk_TicketComments::addToPost($data,$postId);
    }

}