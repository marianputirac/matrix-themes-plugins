<?php

namespace StgHelpdesk\Admin;

/**
 *
 * Class Stg_Helpdesk_Help_Catcher
 * @package StgHelpdesk\Admin
 */
class Stg_Helpdesk_Help_Catcher
{

    private function __construct()
    {
    }

    /**
     * Check HelpCatcher enabled
     * @return bool
     */
    public static function isEnabled()
    {
        include_once(ABSPATH.'wp-admin/includes/plugin.php');
        if(!\is_plugin_active('catchers-helpdesk-addon3/catchers-helpdesk-addon3.php'))
            return false;

        return (bool)stgh_get_option('helpcatcher_enable', false);
    }

    /**
     * Check file upload enabled
     * @return bool
     */
    public static function isFileUploadEnabled()
    {
        return (bool)stgh_get_option('helpcatcher_enable_attachment', false);
    }

    /**
     * @return mixed|void
     */
    public static function getStartConversation()
    {
        return stgh_get_option('helpcatcher_letter_start', __('What we can help you with?', STG_HELPDESK_TEXT_DOMAIN_NAME));
    }

    /**
     * @return mixed|void
     */
    public static function getResultMsg()
    {
        return stgh_get_option('helpcatcher_result_msg', __('<p><b>Message sent!</b><br /> We just got your request! And do our best to answer emails as soon as possible</p>', STG_HELPDESK_TEXT_DOMAIN_NAME));
    }

    /**
     * @return mixed|void
     */
    public static function getButtonColor()
    {
        return stgh_get_option('helpcatcher_button_color', '#f9c3a7');
    }

	public static function getGDPREnable()
	{
		return (bool)stgh_get_option('helpcatcher_gdpr_enable', false);
	}

	public static function getGDPRUrl()
	{
		return stgh_get_option('helpcatcher_gdpr_url', '');
	}

	public static function getHideName()
	{
		return stgh_get_option('helpcatcher_hide_name', false);
	}

	public static function getHideSubject()
	{
		return stgh_get_option('helpcatcher_hide_subject', false);
	}

    /**
     * @return mixed|void
     */
	public static function getCode()
	{
		return '<div id="stgh-help-catcher-widget-block"></div>
<script defer type="text/javascript" src="' . get_site_url() . '/?widget_help_catcher=js"></script>
<script type="text/javascript">window.onload = function () { WidgetHelpCatcher.load();}</script>';

	}

}
