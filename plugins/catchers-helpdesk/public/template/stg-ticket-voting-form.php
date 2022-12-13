<?php
use StgHelpdesk\Ticket\Stg_Helpdesk_TicketComments;
use StgHelpdesk\Helpers\Stg_Helper_UploadFiles;
use StgHelpdesk\Helpers\Stg_Helper_Template;

if (!defined('ABSPATH')) {
    exit;
}
global $post;

$voteArray = \StgHelpdesk\Helpers\Stg_Helper_Saved_Replies::getVoteArray();
$voteChecks = "";
foreach($voteArray as $vote => $desc)
{
    $checked = ($_GET['v'] == $vote)? 'checked':'';
    $voteChecks .= "&nbsp;&nbsp;<input {$checked} type=\"radio\" name=\"stgh-ticket-reply-vote\" value=\"{$vote}\" id=\"stgh-ticket-reply-vote{$vote}\">&nbsp;<label for=\"stgh-ticket-reply-vote{$vote}\">{$desc['name']}</label>";
}
?>

<div class="stg-single-ticket">

    <?php if (isset($_REQUEST['stgh_message'])) : ?>
        <div class="stgh_clearleft" ><?php echo $_REQUEST['stgh_message']; ?></div>
    <?php else: ?>
        <div class="stgh-div-block"><h3><?php _e('Please rate our work!', STG_HELPDESK_TEXT_DOMAIN_NAME); ?></h3></div>

        <div class="stgh-div-block stgh_width100pro">
            <form id="stgh-new-reply" class="stgh-form" enctype="multipart/form-data"
                  action="<?php //echo get_permalink($post->ID); ?>" method="post">
                <?=$voteChecks?>
                <div id="stgh-reply-box" class="stgh-form-group stgh-textarea">
                <textarea id="stgh-reply-textarea" class="form-control"
                          placeholder="<?php _e('Leave a comment', STG_HELPDESK_TEXT_DOMAIN_NAME); ?>" name="stgh_user_comment" rows="5"></textarea>
                </div>

                <input type="hidden" value="<?php echo $post->ID; ?>" name="ticket_id">
                <button class="stgh-btn stgh-btn-default" data-onsubmit="<?php _e('Please Wait...', STG_HELPDESK_TEXT_DOMAIN_NAME); ?>" value="" name="stgh-submit"
                        type="submit"><?php _e('Submit', STG_HELPDESK_TEXT_DOMAIN_NAME); ?>
                </button>
            </form>
        </div>
    <?php endif;?>
</div>

