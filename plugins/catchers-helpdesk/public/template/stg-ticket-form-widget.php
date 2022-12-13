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

$user = get_user_by('id', stgh_get_current_user_id());
$disabled = false; //is_user_logged_in();
$recaptchaContent='';

$isRecaptcha = stg_recaptcha_enabled();

if($isRecaptcha)
{
    $grecaptchaKey = stgh_get_option('recaptcha_key');
    $grecaptchaSKey = stgh_get_option('recaptcha_secret_key');
    $grecaptchaHL = stgh_get_option('recaptcha_hl');
    $recaptchaContent = Stg_Helper_Custom_Forms::getStandartFieldReCAPTCHA('g-recaptcha-widget');

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



if (isset($_REQUEST['stgh_message']) && $_REQUEST['stgh_message']) {
    echo '<div id="message">' . $_REQUEST['stgh_message'] . '</div>';
}
if (isset($_REQUEST['stgh_message_success']) && $_REQUEST['stgh_message_success']) {
    echo '<div id="message">' . Stg_Helper_Email::replaceTemplateTags($_REQUEST['stgh_message_success'],$_REQUEST['ticket_id'],$_REQUEST['ticket_id'])  . '</div>';
} else {

    ?>
    <div id="stg-ticket-form-block">
        <form enctype="multipart/form-data" id="stg-ticket-form-widget"
              action="<?php echo(isset($post->ID) ? get_permalink($post->ID) : ''); ?>" method="post" role="form"
              class="stg-form">

            <input type="hidden" name="stg_saveTicket" value="1">

            <?php //if(!is_user_logged_in()): ?>
            <label for="stg_ticket_name"><?php _e('Your name', STG_HELPDESK_TEXT_DOMAIN_NAME); ?><span
                    class="red">*</span></label>
            <input required="required" type="text" name="stg_ticket_name" class="stgh_width100pro"
                <?php if ($disabled) echo 'disabled' ?>
                   value="<?php echo !empty($user->user_nicename) ? $user->user_nicename : ''; ?>">

            <?php
                do_action('stg_free_form_template_include');
            ?>

            <label for="stg_ticket_email"><?php _e('Email', STG_HELPDESK_TEXT_DOMAIN_NAME); ?><span class="red">*</span></label>
            <input required="required"  type="text" name="stg_ticket_email" class="stgh_width100pro"
                <?php if ($disabled) echo 'disabled' ?>
                   value="<?php echo !empty($user->user_email) ? $user->user_email : '' ?>">
            <?php //endif; ?>

            <?php
            if (stgh_get_option('stgh_contactform_ticket_category', false) && \StgHelpdesk\Ticket\Stg_Helpdesk_TicketCategory::hasCategories()) : ?>
                <label for="stg_category"><?php _e('Category', STG_HELPDESK_TEXT_DOMAIN_NAME); ?><span
                        class="red">*</span></label>

                <?php echo \StgHelpdesk\Ticket\Stg_Helpdesk_TicketCategory::getSelectList(null, null, 'stgh_width100pro'); endif;
            ?>

            <label for="stg_ticket_subject"><?php _e('Subject', STG_HELPDESK_TEXT_DOMAIN_NAME); ?><span
                    class="red">*</span></label>
            <input required="required"  type="text" id="stg_ticket_subject" name="stg_ticket_subject" value="" class="stgh_width100pro">

            <label for="stg_ticket_message"><?php _e('Description', STG_HELPDESK_TEXT_DOMAIN_NAME); ?><span class="red">*</span></label>
            <textarea required="required"  name="stg_ticket_message" class="stgh_width100pro"></textarea>
            <p></p>

            <?php Stg_Helper_Template::getTemplate('stg-upload-file-field'); ?>

            <?php echo $recaptchaContent;?>

            <button value="" name="stgsubmit" type="stgsubmit"><?php _e('Submit', STG_HELPDESK_TEXT_DOMAIN_NAME) ?></button>
        </form>
    </div>
<?php } ?>