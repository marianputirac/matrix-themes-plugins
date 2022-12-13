<?php
use StgHelpdesk\Helpers\Stg_Helper_Template;

add_shortcode(STG_HELPDESK_SHORTCODE_KB, 'stg_sc_kb');
/**
 * @param $atts
 * @param $content
 * @param $tag
 * @return string
 */
function stg_sc_kb()
{
    ob_start();
    Stg_Helper_Template::getTemplate('stgh-kb');
    $content = ob_get_contents();
    ob_end_clean();
    return $content;
}

function stg_sc_kb_settings()
{
	ob_start();
	Stg_Helper_Template::getTemplate('stgh-kb',array('is_settings' => true));
	$content = ob_get_contents();
	ob_end_clean();
	return $content;
}