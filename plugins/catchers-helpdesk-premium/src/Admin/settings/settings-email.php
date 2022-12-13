<?php
add_filter('stgh_plugin_settings', 'stgh_core_email', 98, 1);

/**
 * @param $def
 * @return array
 */
function stgh_core_email($def)
{

    $settings = array(
        'email' => array(
            'name' => __('Notifications', STG_HELPDESK_TEXT_DOMAIN_NAME),
            'options' => array(
                array(
                    'hidden' => true,
                    'name' => __('Ticket subject', STG_HELPDESK_TEXT_DOMAIN_NAME),
                    'id' => 'ticket_subject',
                    'type' => 'text',
                    'default' => __('Action required for On Hold Order - ', STG_HELPDESK_TEXT_DOMAIN_NAME).'{ticket_title}',
                    'desc' => __('Each e-mail notification will have this subject.', STG_HELPDESK_TEXT_DOMAIN_NAME)
                ),
                array(
                    'id' => 'agent_notifications',
                    'type' => 'multicheck',
                    'name' => __('Agent notifications', STG_HELPDESK_TEXT_DOMAIN_NAME),
                    'desc' => '',
                    'options' => array(

                        'enable_reply_client' => __('New reply from client', STG_HELPDESK_TEXT_DOMAIN_NAME),
                        'enable_ticket_assign' => __('Ticket assigned to agent', STG_HELPDESK_TEXT_DOMAIN_NAME)

                    ),

                    'default' => array(true, true)
                ),
                array(
                    'name' => __('Add a  ticket link to notification letter', STG_HELPDESK_TEXT_DOMAIN_NAME),
                    'id' => 'show_ticket_link',
                    'type' => 'checkbox',
                    'default' => stgh_get_option('show_ticket_link', false),
                ),
                                array(
                    'name' => __('Global Cc for all notifications', STG_HELPDESK_TEXT_DOMAIN_NAME),
                    'type' => 'text',
                    'id' => 'stg_global_cc',
                    'desc' => __('e-mail address for set Cc header for all notification', STG_HELPDESK_TEXT_DOMAIN_NAME)

                ),
                array(
                    'name' => __('Global Bcc for all notifications', STG_HELPDESK_TEXT_DOMAIN_NAME),
                    'type' => 'text',
                    'id' => 'stg_global_bcc',
                    'desc' => __('e-mail address for set Bcc header for all notification', STG_HELPDESK_TEXT_DOMAIN_NAME)

                ),
                
            )
        ),
    );

    return array_merge($def, $settings);

}