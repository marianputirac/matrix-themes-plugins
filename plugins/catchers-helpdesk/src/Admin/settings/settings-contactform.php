<?php


add_filter('stgh_plugin_settings', 'stgh_core_contactform', 99, 1);

/**
 * @param $def
 * @return array
 */
function stgh_core_contactform($def)
{
    $prefix = 'stgh_contactform_';
    $settings = array(
        'contactform' => array(
            'name' => __('Contact form', STG_HELPDESK_TEXT_DOMAIN_NAME),
            'options' => array(
                array(
                    'name' => __('Contact form fields', STG_HELPDESK_TEXT_DOMAIN_NAME),
                    'type' => 'heading',
                ),
                array(
                    'name' => __('Ticket Category', STG_HELPDESK_TEXT_DOMAIN_NAME),
                    'id' => $prefix . 'ticket_category',
                    'type' => 'checkbox',
                    'default' => true,
                    'desc' => '<input disabled type="text" placeholder="Ticket Category">',
                ),

            )
        ),
    );

    return array_merge($def, $settings);

}
