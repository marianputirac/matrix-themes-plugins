<?php

namespace StgHelpdesk\Ticket;

/**
 *
 *
 * Class Stg_Helpdesk_TicketCategory
 * @package StgHelpdesk\Ticket
 */
class Stg_Helpdesk_TicketCategory
{

    private function __construct()
    {
    }

    public static function addCustomFields($tag)
    {
        $term_meta = self::getCategoryAssign($tag->term_id);

        ?>
        <tr class="form-field">
            <th scope="row" valign="top">
                <label><?php _e('Assign agent', STG_HELPDESK_TEXT_DOMAIN_NAME); ?></label>
            </th>
            <td>
                <?php echo stgh_display_assign_to_select(array(), $term_meta, 'stgh_ticket_category_assignee'); ?>
                <!--span class="description"></span-->
            </td>
        </tr>

        <?php
    }


    public static function saveCustomFieldsValue($term_id)
    {
        if (isset($_POST['stgh_ticket_category_assignee'])) {
            $term_meta = self::getCategoryAssign($term_id);
            update_option("stgh_assign_ticket_category_$term_id", $_POST['stgh_ticket_category_assignee']);
        }

    }

    /**
     * Get assigned agent id
     */
    public static function getCategoryAssign($term_id)
    {
        return get_option("stgh_assign_ticket_category_$term_id", false);
    }

    /**
     * Return ticket categories selector
     *
     * @param null $selected - selected option
     * @param null $default - default option
     *
     * @return string
     */
    public static function getSelectList($selected = null, $default = null, $class = null, $required = false)
    {
        return stgh_ticket_category_list($selected, $default, $class, $required);
    }

    /**
     * Retrieve list of ticket categories
     *
     * @return array|int|\WP_Error
     */
    public static function getCategories()
    {
        return stgh_ticket_get_categories();
    }

    public static function hasCategories()
    {
        $categories = self::getCategories();
        if (is_array($categories) && count($categories) > 0) {
            return true;
        }

        return false;
    }

    /**
     * Ticket assigned to a category manager - if before it on anyone is not assigned and if there is a manager in the category
     *
     * @param $term_value
     * @param $postId
     * @param string $term_field in slug, name, term_taxonomy_id , id
     */
    public static function assignToCategoryManager($term_value, $postId, $term_field = 'id')
    {
        if (!Stg_Helpdesk_Ticket::getAssignedTo($postId)) {

            $term = get_term_by($term_field, $term_value, STG_HELPDESK_POST_TYPE_CATEGORY);

            if (!is_a($term, 'WP_Term'))
                return false;

            $category_assign = self::getCategoryAssign($term->term_id);
            if ($category_assign) {
                update_post_meta($postId, '_stgh_assignee', $category_assign);
                $_POST['stgh_assignee'] = $category_assign; // for history
            }
        }
    }


}
