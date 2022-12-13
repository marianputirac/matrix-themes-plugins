<?php
use StgHelpdesk\Helpers\Stg_Helper_Template;
use StgHelpdesk\Helpers\Stg_Helper_Custom_Forms;
use StgHelpdesk\Helpers\Stg_Helper_Email;

global $post;

$rand = rand();

wp_enqueue_script('jquery-ui-datepicker');
wp_enqueue_style('jqueryui', '//ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css', false, null );



if (!wp_script_is( 'stgh-custom-form-script', 'enqueued' )) {
	wp_enqueue_script('stgh-custom-form-script', STG_HELPDESK_URL . 'js/public_custom_form.js',
		array('jquery'), STG_HELPDESK_VERSION);
}

$formContent = Stg_Helper_Custom_Forms::getFormText($formId);
$recaptchaContent = "";

$isRecaptcha = stg_recaptcha_enabled();

$values = Stg_Helper_Custom_Forms::getTermMetaValue($formId);
$confirmUrl = !empty($values["custom_form_confirm_url_meta"])? $values["custom_form_confirm_url_meta"]:false;

$confirmUrl = isset($atts['confirmation-url'])? $atts['confirmation-url']:$confirmUrl;

if($isRecaptcha)
{
        $grecaptchaKey = stgh_get_option('recaptcha_key');
        $grecaptchaSKey = stgh_get_option('recaptcha_secret_key');
        $grecaptchaHL = stgh_get_option('recaptcha_hl');
        $recaptchaContent = Stg_Helper_Custom_Forms::getStandartFieldReCAPTCHA('g-recaptcha'.$rand);

        if($grecaptchaKey && $grecaptchaSKey) {
            wp_enqueue_script('grecaptcha', '//www.google.com/recaptcha/api.js?hl='.$grecaptchaHL.'onload=onloadCallbackIn&render=explicit', false, null);
            $keyValue = 'key';
        }
        else {
            $keyValue = 'nokey';
        }
}else{
    $keyValue = 'nocaptcha';
}
echo "<script type=\"text/javascript\">
            /* &lt;![CDATA[ */
            var captcha = '{$keyValue}';
            /* ]]&gt; */
            </script>";


$user = get_user_by('id', stgh_get_current_user_id());
$disabled = false; //is_user_logged_in()

if (isset($_REQUEST['stgh_message']) && $_REQUEST['stgh_message']) {
    echo '<div id="message">' . $_REQUEST['stgh_message'] . '</div>';
}
if (isset($_REQUEST['stgh_message_success']) && $_REQUEST['stgh_message_success']) {
    echo '<div id="message">' . Stg_Helper_Email::replaceTemplateTags($_REQUEST['stgh_message_success'],$_REQUEST['ticket_id'],$_REQUEST['ticket_id'])  . '</div>';
} else {

    ?>
    <div id="stg-ticket-form-block">
        <form enctype="multipart/form-data" id="stg-ticket-form<?=$rand?>"
              action="<?php echo(isset($post->ID) ? get_permalink($post->ID) : ''); ?>" method="post" role="form"
              class="stg-form">

            <input type="hidden" name="stg_saveTicket" value="1">

            <?php

                if($confirmUrl !== false)
                {
                    echo '<input type="hidden" name="stg_confirm_url" value="'.$confirmUrl.'">';
                }

                echo $formContent;
                echo $recaptchaContent;
            ?>
            <br/>
            <button value="" name="stgsubmit" id="stgsubmit" type="submit"><?php _e('Submit', STG_HELPDESK_TEXT_DOMAIN_NAME) ?></button>
        </form>
    </div>
<?php } ?>