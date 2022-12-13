<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) {
    exit;
}

require_once('header/plugin-header.php');

if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete') {
    $post_id = $_REQUEST['id'];
    wp_delete_post($post_id);
    wp_redirect(admin_url('/admin.php?page=afrsm-pro-list'));
    exit;
}

$sm_args = array(
                'post_type'         => 'wc_afrsm',
                'post_status'       => 'publish',
                'posts_per_page'    => -1,
            );
$get_all_sm = get_posts( $sm_args );
?>
<div class="afrsm-section-left">
    <div class="afrsm-main-table res-cl">
        <div class="product_header_title">
            <h2>
                <?php _e('Shipping Methods', AFRSM_PRO_TEXT_DOMAIN); ?>
                <a class="add-new-btn" href="<?php echo admin_url('/admin.php?page=afrsm-pro-add-shipping'); ?>"><?php _e('Add New Shipping Method', AFRSM_PRO_TEXT_DOMAIN); ?></a>
                <a id="delete-shipping-method" class="delete-shipping-method"><?php _e('Delete (Selected)', AFRSM_PRO_TEXT_DOMAIN); ?></a>
                <a class="shipping-methods-order"><?php _e('Save Order', AFRSM_PRO_TEXT_DOMAIN); ?></a>
            </h2>
        </div>
        <table id="shipping-methods-listing" class="table-outer form-table shipping-methods-listing tablesorter">
            <thead>
                <tr class="afrsm-head">
                    <th><input type="checkbox" name="check_all" class="condition-check-all"></th>
                    <th><?php _e('Shipping Method Name', AFRSM_PRO_TEXT_DOMAIN); ?></th>
                    <th><?php _e('Amount', AFRSM_PRO_TEXT_DOMAIN); ?></th>
                    <th><?php _e('Status', AFRSM_PRO_TEXT_DOMAIN); ?></th>
                    <th><?php _e('Actions', AFRSM_PRO_TEXT_DOMAIN); ?></th>
                </tr>
            </thead>

            <?php if (!empty($get_all_sm)) { ?>
                <tbody>
                    <?php
                        $sort_order = array();
                        $getSortOrder = get_option('sm_sortable_order');
                        
                        if (isset($getSortOrder) && !empty($getSortOrder)) {
                            foreach ($getSortOrder as $sort) {
                                $sort_order[$sort] = array();
                            }
                        }
                        
                        foreach ($get_all_sm as $carrier_id => $carrier) {
                            $carrier_name = $carrier->ID;

                            if (array_key_exists($carrier_name, $sort_order)) {
                                $sort_order[$carrier_name][$carrier_id] = $get_all_sm[$carrier_id];
                                unset($get_all_sm[$carrier_id]);
                            }
                        }
                        
                        foreach ($sort_order as $carriers) {
                            $get_all_sm = array_merge($get_all_sm, $carriers);
                        }
                        
                        foreach ($get_all_sm as $sm) {
                            $shipping_title     = get_the_title($sm->ID) ? get_the_title($sm->ID) : 'Fee';
                            $shipping_cost      = get_post_meta($sm->ID, 'sm_product_cost', true);
                            $shipping_status    = get_post_meta($sm->ID, 'sm_status', true);
                        ?>
                            <tr id="<?php echo $sm->ID ?>">
                                <td width="10%">
                                    <input type="checkbox" name="multiple_delete_fee[]" class="multiple_delete_fee" value="<?php echo $sm->ID ?>">
                                </td>
                                <td>
                                    <a href="<?php echo admin_url('/admin.php?page=afrsm-pro-edit-shipping&id=' . $sm->ID . '&action=edit'); ?>"><?php _e($shipping_title, AFRSM_PRO_TEXT_DOMAIN); ?></a>
                                </td>
                                <td>
                                    <?php
                                        if ($shipping_cost > 0) {
                                            echo get_woocommerce_currency_symbol() . '&nbsp;' . esc_attr($shipping_cost);
                                        } else {
                                            echo esc_attr($shipping_cost);
                                        }
                                    ?>
                                </td>
                                <td>
                                    <?php echo (isset($shipping_status) && $shipping_status == 'on') ? '<span class="active-status">' . _e('Enabled', AFRSM_PRO_TEXT_DOMAIN) . '</span>' : '<span class="inactive-status">' . _e('Disabled', AFRSM_PRO_TEXT_DOMAIN) . '</span>'; ?>
                                </td>
                                <td>
                                    <a class="fee-action-button button-primary" href="<?php echo admin_url('/admin.php?page=afrsm-pro-edit-shipping&id=' . $sm->ID . '&action=edit'); ?>"><?php _e('Edit', AFRSM_PRO_TEXT_DOMAIN); ?></a>
                                    <a class="fee-action-button button-primary" href="<?php echo admin_url('/admin.php?page=afrsm-pro-list&id=' . $sm->ID . '&action=delete'); ?>" onclick="return confirm('<?php _e('Are you sure you want to delete this shipping method?', AFRSM_PRO_TEXT_DOMAIN) ?>');"><?php _e('Delete', AFRSM_PRO_TEXT_DOMAIN); ?></a>
                                </td>
                            </tr>
                    <?php } ?>
                </tbody>
            <?php } ?>
        </table>
    </div>
    <div class="afrsm-mastersettings">
        <div class="mastersettings-title">
            <h2><?php _e('Master Settings', AFRSM_PRO_TEXT_DOMAIN); ?></h2>
        </div>
        <?php
            $what_to_do_method = get_option('what_to_do_method');
            $shipping_method_format = get_option('md_woocommerce_shipping_method_format');
        ?>
        <table class="table-mastersettings table-outer" cellpadding="0" cellspacing="0">
            <tbody>
                <tr class="mastersettings-raw">
                    <td class="table-whattodo"><?php _e('What to do when multiple shipping methods are available', AFRSM_PRO_TEXT_DOMAIN); ?></td>
                    <td>
                        <select name="what_to_do_method" id="what_to_do_method">
                            <option value="allow_customer"<?php echo (isset($what_to_do_method) && $what_to_do_method == 'allow_customer') ? ' selected=selected' : ''; ?>><?php _e('Allow customer to choose', AFRSM_PRO_TEXT_DOMAIN); ?></option>
                            <option value="apply_highest"<?php echo (isset($what_to_do_method) && $what_to_do_method == 'apply_highest') ? ' selected=selected' : ''; ?>><?php _e('Apply Highest', AFRSM_PRO_TEXT_DOMAIN); ?></option>
                            <option value="apply_smallest"<?php echo (isset($what_to_do_method) && $what_to_do_method == 'apply_smallest') ? ' selected=selected' : ''; ?>><?php _e('Apply Smallest', AFRSM_PRO_TEXT_DOMAIN); ?></option>
                            <option value="force_all"<?php echo (isset($what_to_do_method) && $what_to_do_method == 'force_all') ? ' selected=selected' : ''; ?>><?php _e('Force all shipping methods', AFRSM_PRO_TEXT_DOMAIN); ?></option>
                        </select>
                    </td>
                </tr>
                <tr valign="top">
                    <td class="table-whattodo"><?php _e('Shipping Display Mode', AFRSM_PRO_TEXT_DOMAIN); ?></td>
                    <td>
                        <select name="shipping_display_mode" id="shipping_display_mode">
                            <option value="radio_button_mode"<?php echo (isset($shipping_method_format) && $shipping_method_format == 'radio_button_mode') ? ' selected=selected' : ''; ?>><?php _e('Display shipping methods with radio buttons', AFRSM_PRO_TEXT_DOMAIN); ?></option>
                            <option value="dropdown_mode"<?php echo (isset($shipping_method_format) && $shipping_method_format == 'dropdown_mode') ? ' selected=selected' : ''; ?>><?php _e('Display shipping methods in a dropdown', AFRSM_PRO_TEXT_DOMAIN); ?></option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <span class="button-primary" id="save_master_settings" name="save_master_settings"><?php _e('Save Master Settings', AFRSM_PRO_TEXT_DOMAIN); ?></span>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<?php require_once('header/plugin-sidebar.php'); ?>