<?php
//include_once( STG_HELPDESK_PUBLIC . 'template/pro-version.php' );


add_filter('stgh_plugin_settings', 'stgh_core_pro_version', 101, 1);

/**
 * @param $def
 * @return array
 */
function stgh_core_pro_version($def)
{
    ob_start();
    include STG_HELPDESK_PATH . 'Admin/views/addons.php';
    $page =  ob_get_clean();


    $settings = array(
        'addons' => array(
            'name' => __('Addons', STG_HELPDESK_TEXT_DOMAIN_NAME),
            'stgh-no-save-button' => true,
            'options' => array(
                array(
                    'id' => 'stgh_addons',
                    'type' => 'custom',
                    'custom' => $page,
                    'default' => '',
                ),
            )
        ),
    );

    return array_merge($def, $settings);

}

