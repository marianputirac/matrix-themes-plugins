<?php

namespace StgHelpdesk\Versionobject;


use StgHelpdesk\Admin\Stg_Helpdesk_Help_Catcher;

class Version_Object implements iVersion_Object
{
    private $version;

    public function __construct()
    {
        $this->version = get_bloginfo('version');
    }

    public function getVersion()
    {
        return $this->version;
    }

    public function register_new_user($user_login, $user_email)
    {
        return register_new_user($user_login, $user_email);
    }

    public function registerSaveTicketFields()
    {
        add_action('save_post_' . STG_HELPDESK_POST_TYPE, 'stgh_save_custom_fields', 10, 3);
    }

    /**
     * Plugins thumbnail in adminbar
     */
    public function customEnqueueStyles()
    {
        echo '<style type="text/css">
                #wp-admin-bar-stgh-helpdesk .ab-icon:before {
                    content:\'\f468\';
                    top:3px;
                 }
             </style>';
    }

    public function getPluginIcon()
    {
        return 'dashicons-sos';
    }

    public function getProVerMenuStyle()
    {
        return 'margin-top:-12px;';
    }

    public function getTitleSelector()
    {
        return '".wrap > h1"';
    }

    public function enqueueAdminScripts()
    {
        wp_enqueue_script('stgh-admin-autocomplete-script', STG_HELPDESK_URL . 'js/admin/admin_autocomplete.js',
            array('jquery', 'stgh-stgselect2', 'jquery-ui-autocomplete'),
            STG_HELPDESK_VERSION);

	 }

	public function enqueuePublicScripts()
	{

		if(defined('STG_CATCHERS_HELPDESK_ADDON6_URL')) {

			wp_enqueue_script( 'stgh_kb-auto', STG_CATCHERS_HELPDESK_ADDON6_URL . 'public/js/autocomplete.js',
				array( 'jquery', 'jquery-ui-autocomplete' ),
				STG_HELPDESK_VERSION );

			wp_localize_script('stgh_kb-auto', 'stghKbLocal', array(
				'ajaxurl' => admin_url('admin-ajax.php'),
				'sresult' => __('Search result for', STG_HELPDESK_TEXT_DOMAIN_NAME),
			));

		}
	}

    public function enqueueHelpcatcherScript()
    {

        wp_enqueue_script('stgh-helpcatcher-admin-script', STG_HELPDESK_URL . 'js/admin/helpcatcher.js',
            array('jquery'),
            STG_HELPDESK_VERSION);

        wp_localize_script('stgh-helpcatcher-admin-script', 'stghHcLocal', array(
            'widgetCodeDefault' => Stg_Helpdesk_Help_Catcher::getCode(),
        ));
    }

    public function getHelpcatcherSettings()
    {
        $settings = array(
            'helpcatcher' => array(
                'name' => __('Help Catcher', STG_HELPDESK_TEXT_DOMAIN_NAME),
                'options' => array(
                    array(
                        'name' => __('Enabled Help Catcher', STG_HELPDESK_TEXT_DOMAIN_NAME),
                        'id' => 'helpcatcher_enable',
                        'type' => 'checkbox',
                        'default' => false,
                    ),

                    array(
                        'name' => __('File Upload Configuration', STG_HELPDESK_TEXT_DOMAIN_NAME),
                        'id' => 'helpcatcher_enable_attachment',
                        'type' => 'checkbox',
                        'default' => false,
                        'desc' => '<p class="description">' . __('Do you want to allow your users to upload attachments in Help Catcher?', STG_HELPDESK_TEXT_DOMAIN_NAME) . "</p>"
                    ),

	                array(
                        'name' => __('Start conversation', STG_HELPDESK_TEXT_DOMAIN_NAME),
                        'id' => 'helpcatcher_letter_start',
                        'type' => 'text',
                        'desc' => 'What we can help you with?',
                        'default' => __('What we can help you with?', STG_HELPDESK_TEXT_DOMAIN_NAME)
                    ),

                    array(
                        'name' => __('Result message', STG_HELPDESK_TEXT_DOMAIN_NAME),
                        'id' => 'helpcatcher_result_msg',
                        'type' => 'editor',
                        'default' => __('<p><b>Message sent!</b><br /> We just got your request! And do our best to answer emails as soon as possible</p>', STG_HELPDESK_TEXT_DOMAIN_NAME),
                        'editor_settings' => array('quicktags' => true, 'textarea_rows' => 10, 'wpautop' => false, 'teeny' => true, 'media_buttons' => false),
                    ),


                    array(
                        'name' => __('Button color', STG_HELPDESK_TEXT_DOMAIN_NAME),
                        'id' => 'helpcatcher_button_color',
                        'type' => 'color',
                        'default' => '#f9c3a7',
                    ),

                    array(
                        'name' => _x('Position','HC Position', STG_HELPDESK_TEXT_DOMAIN_NAME),
                        'id' => 'helpcatcher_position',
                        'type' => 'select',
                        'options' => array(
                            "right" => __('Bottom right', STG_HELPDESK_TEXT_DOMAIN_NAME),
                            "left" => __('Bottom left', STG_HELPDESK_TEXT_DOMAIN_NAME),
                        ),
                        'default' => 'right',
                    ),

	                array(
		                'name' => __('GDPR options', STG_HELPDESK_TEXT_DOMAIN_NAME),
		                'type' => 'heading',
	                ),

	                array(
		                'name' => __('Add GDPR checkbox', STG_HELPDESK_TEXT_DOMAIN_NAME),
		                'id' => 'helpcatcher_gdpr_enable',
		                'type' => 'checkbox',
		                'default' => false
	                ),

	                array(
		                'name' => __('GDPR label', STG_HELPDESK_TEXT_DOMAIN_NAME),
		                'id' => 'helpcatcher_gdpr_url',
		                'type' => 'editor',
		                'editor_settings' => array('tinymce' => false, 'teeny' => false),
		                'media_buttons' =>false,
		                'default' => sprintf(__('I read and agree with <a href=\'//%s\' target=\'_blank\' rel=\'noopener\'>terms&conditions</a>', STG_HELPDESK_TEXT_DOMAIN_NAME), 'google.com')
	                ),
	                array(
		                'name' => __('Hide Name field', STG_HELPDESK_TEXT_DOMAIN_NAME),
		                'id' => 'helpcatcher_hide_name',
		                'type' => 'checkbox',
		                'default' => false
	                ),
	                array(
		                'name' => __('Hide Subject field', STG_HELPDESK_TEXT_DOMAIN_NAME),
		                'id' => 'helpcatcher_hide_subject',
		                'type' => 'checkbox',
		                'default' => false
	                ),
	                array(
		                'name' => '',
		                'type' => 'heading',
	                ),

	                array(
		                'name' => __('Widget code', STG_HELPDESK_TEXT_DOMAIN_NAME),
		                'id' => 'helpcatcher_embed_code',
		                'type' => 'textarea',
		                'desc' => __('Below code needs to add on page for widget activation (save changes first)', STG_HELPDESK_TEXT_DOMAIN_NAME),
		                'default' => Stg_Helpdesk_Help_Catcher::getCode(),
		                'is_code' => true
	                ),

                )
            ),
        );

        return $settings;
    }

	public function getKbSettings(){
		return stgh_get_kb_settings();
	}
} 