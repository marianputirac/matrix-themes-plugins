<?php

namespace StgHelpdesk\Core\PostType;

class Stg_Helpdesk_Post_Type_Statuses
{
    public static function get()
    {
        $status = array(
            'stgh_answered' => _x('Not answered', 'Ticket status', STG_HELPDESK_TEXT_DOMAIN_NAME),
            'stgh_notanswered' => _x('Answered', 'Ticket status', STG_HELPDESK_TEXT_DOMAIN_NAME),
            'stgh_new' => _x('Not opened', 'Ticket status', STG_HELPDESK_TEXT_DOMAIN_NAME),
            'stgh_closed' => _x('Closed', 'Ticket status', STG_HELPDESK_TEXT_DOMAIN_NAME),
            'stgh_pending' => _x('Pending', 'Ticket status', STG_HELPDESK_TEXT_DOMAIN_NAME),
        );

        return apply_filters('stgh_ticket_statuses', $status);
    }
}
