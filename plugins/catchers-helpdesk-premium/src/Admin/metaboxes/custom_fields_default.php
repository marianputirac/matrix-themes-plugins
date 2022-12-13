<?php
global $post;

$fields = get_terms( array(
    'taxonomy' => 'customfields',
    'hide_empty' => false,
));

if(!is_wp_error($fields)):
?>
<div class="stgh-customfields" data-authorId="<?php echo $post->post_author ?>" data-postId="<?php echo $post->ID ?>"
     id="stgh-customfields">
    <div id="stgh-customfields-content">
        <?php
            foreach($fields as $field):
	            $fieldValues = \StgHelpdesk\Helpers\Stg_Helper_Custom_Forms::getCustomFieldValue($post->ID,$field->term_id);

                $fieldNameForId = 'stgh_custom_'.$field->term_id;

                ?>
                <div class="stgh-metabox-inner-item stgh-metabox-customfields-item">
                    <label for="<?=$fieldNameForId?>">
                        <b><?=$field->name?>:</b>
                        <span id="stgh-metabox-customfields-<?=$fieldNameForId?>-selected">
                            <?=$fieldValues['t']?>
                            <a class="stgh-metabox-customfields-click"
                               data-block="stgh-metabox-customfields-<?=$fieldNameForId?>"><?= __('Change', STG_HELPDESK_TEXT_DOMAIN_NAME) ?></a>
                        </span>

                        <span id="stgh-metabox-customfields-<?=$fieldNameForId?>-select" class="stgh_customfield_metabox_edit">
                            <?=\StgHelpdesk\Helpers\Stg_Helper_Custom_Forms::getCustomField($field->term_id,$field,$fieldValues['s'],false,true);?>
                        </span>
                    </label>
                </div>
            <?php endforeach;
        ?>
    </div>
</div>
<?php endif;?>