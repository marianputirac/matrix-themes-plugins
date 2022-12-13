<?php

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
    exit;
}
require_once('header/plugin-header.php');
?>

<?php
if ( isset($_POST['submitFee']) && !empty($_POST['submitFee']) ) {        
    $post = $_POST;
    $this->afrsm_pro_fees_conditions_save($post);
}
if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit') {
    $post_id                = $_REQUEST['id'];
    $sm_status              = get_post_meta($post_id, 'sm_status', true);
    $sm_title               = __(get_the_title($post_id), AFRSM_PRO_TEXT_DOMAIN);
    $sm_cost                = get_post_meta($post_id, 'sm_product_cost', true);
    $getFeesPerQtyFlag      = get_post_meta($post_id, 'sm_fee_chk_qty_price', true);
    $getFeesPerSqmFlag      = get_post_meta($post_id, 'sm_fee_chk_sqm_price', true);
    $getFeesPerQty          = get_post_meta($post_id, 'sm_fee_per_qty', true);
    $extraProductCost       = get_post_meta($post_id, 'sm_extra_product_cost', true);
    $sm_tooltip_desc        = get_post_meta($post_id, 'sm_tooltip_desc', true);
    $sm_is_taxable          = get_post_meta($post_id, 'sm_select_taxable', true);
    $sm_estimation_delivery = get_post_meta($post_id, 'sm_estimation_delivery', true);
    $sm_start_date          = get_post_meta($post_id, 'sm_start_date', true);
    $sm_end_date            = get_post_meta($post_id, 'sm_end_date', true);
    $sm_extra_cost          = get_post_meta($post_id, 'sm_extra_cost', true);
    $sm_extra_cost_calc_type = get_post_meta($post_id, 'sm_extra_cost_calculation_type', true);
    $sm_metabox             = get_post_meta($post_id, 'sm_metabox', true);
} else {
    $post_id                = '';
    $sm_status              = '';
    $sm_title               = '';
    $sm_cost                = '';
    $getFeesPerQtyFlag      = '';
    $getFeesPerSqmFlag      = '';
	$getFeesPerQty          = '';
	$extraProductCost       = '';
    $sm_tooltip_desc        = '';
    $sm_is_taxable          = '';
    $sm_estimation_delivery = '';
    $sm_start_date          = '';
    $sm_end_date            = '';
    $sm_extra_cost          = array();
    $sm_extra_cost_calc_type = '';
    $sm_metabox             = array();
}
$sm_status              = ((!empty($sm_status) && $sm_status == 'on') || empty($sm_status)) ? 'checked' : '';
$sm_title               = !empty($sm_title) ? esc_attr( stripslashes( $sm_title )) : '';
$sm_cost                = ($sm_cost !== '') ? esc_attr( stripslashes( $sm_cost )) : '';
$sm_tooltip_desc        = !empty($sm_tooltip_desc) ? $sm_tooltip_desc : '';
$sm_estimation_delivery = !empty($sm_estimation_delivery) ? esc_attr( stripslashes( $sm_estimation_delivery )) : '';
$sm_start_date          = !empty($sm_start_date) ? esc_attr( stripslashes( $sm_start_date )) : '';
$sm_end_date            = !empty($sm_end_date) ? esc_attr( stripslashes( $sm_end_date )) : '';
$submit_text            = __('Save changes', AFRSM_PRO_TEXT_DOMAIN);
?>
<div class="text-condtion-is" style="display:none;">
    <select class="text-condition">
        <option value="is_equal_to"><?php _e('Equal to ( = )', AFRSM_PRO_TEXT_DOMAIN); ?></option>
        <option value="less_equal_to"><?php _e('Less or Equal to ( <= )', AFRSM_PRO_TEXT_DOMAIN); ?></option>
        <option value="less_then"><?php _e('Less than ( < )', AFRSM_PRO_TEXT_DOMAIN); ?></option>
        <option value="greater_equal_to"><?php _e('Greater or Equal to ( >= )', AFRSM_PRO_TEXT_DOMAIN); ?></option>
        <option value="greater_then"><?php _e('Greater than ( > )', AFRSM_PRO_TEXT_DOMAIN); ?></option>
        <option value="not_in"><?php _e('Not Equal to ( != )', AFRSM_PRO_TEXT_DOMAIN); ?></option>	
    </select>
    <select class="select-condition">
        <option value="is_equal_to"><?php _e('Equal to ( = )', AFRSM_PRO_TEXT_DOMAIN); ?></option>
        <option value="not_in"><?php _e('Not Equal to ( != )', AFRSM_PRO_TEXT_DOMAIN); ?></option>	
    </select>
</div>
<div class="default-country-box" style="display:none;">
    <?php echo $this->afrsm_pro_get_country_list(); ?>
</div>
<div class="afrsm-section-left">
    <div class="afrsm-main-table res-cl">
        <h2><?php _e('Shipping Method Configuration', AFRSM_PRO_TEXT_DOMAIN); ?></h2>
        <form method="POST" name="feefrm" action="">
            <input type="hidden" name="post_type" value="wc_afrsm">
            <input type="hidden" name="fee_post_id" value="<?php echo $post_id ?>">
            <table class="form-table table-outer shipping-method-table">
                <tbody>
                    <tr valign="top">
                        <th class="titledesc" scope="row">
                            <label for="onoffswitch"><?php _e('Status', AFRSM_PRO_TEXT_DOMAIN); ?></label>
                        </th>
                        <td class="forminp">
                            <label class="switch">
                                <input type="checkbox" name="sm_status" value="on" <?php echo $sm_status; ?>>
                                <div class="slider round"></div>
                            </label>
                            <span class="advanced_flat_rate_shipping_for_woocommerce_tab_description"></span>
                            <p class="description" style="display:none;"><?php _e('Enable this shipping method (This method will be visible to customers only if it is enabled).', AFRSM_PRO_TEXT_DOMAIN); ?></p>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th class="titledesc" scope="row">
                            <label for="fee_settings_product_fee_title"><?php _e('Shipping Method Name', AFRSM_PRO_TEXT_DOMAIN); ?> <span class="required-star">*</span></label>
                        </th>
                        <td class="forminp">
                            <input type="text" name="fee_settings_product_fee_title" class="text-class" id="fee_settings_product_fee_title" value="<?php echo $sm_title; ?>" required="1" placeholder="<?php _e('Enter product fees title', AFRSM_PRO_TEXT_DOMAIN); ?>">
                            <span class="advanced_flat_rate_shipping_for_woocommerce_tab_description"></span>
                            <p class="description" style="display:none;"><?php _e('This name will be visible to the customer at the time of checkout. This should convey the purpose of the charges you are applying to the order. For example "Ground Shipping", "Express Shipping Flat Rate", "Christmas Next Day Shipping" etc', AFRSM_PRO_TEXT_DOMAIN); ?></p>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th class="titledesc" scope="row">
                            <label for="sm_product_cost"><?php _e('Shipping Charge', AFRSM_PRO_TEXT_DOMAIN); ?> <?php echo '(' . get_woocommerce_currency_symbol() . ')' ?> <span class="required-star">*</span></label>
                        </th>
                        <td class="forminp">
                            <div class="product_cost_left_div">
                            <input type="text" name="sm_product_cost" required="1" class="text-class" id="sm_product_cost" value="<?php echo $sm_cost; ?>" placeholder="<?php echo get_woocommerce_currency_symbol(); ?>">
                            <span class="advanced_flat_rate_shipping_for_woocommerce_tab_description"></span>
                            <p class="description" style="display:none;">
                                <?php _e('When customer select this shipping method the amount will be added to the cart subtotal.'
                                    . ' You can enter fixed amount or make it dynamic using below parameters:<br>'
                                    . '[qty] -> total number of items in cart,<br>'
                                    . '[cost] -> cost of items,<br>'
                                    . '[fee percent=10 min_fee=20] -> Percentage based fee.<br><br>'
                                    . 'Below are some examples: <br>'
                                    . 'i. 10.00  -> To add flat 10.00 shipping charge'."<br>"
                                    . 'ii. 10.00 * [qty] -> To charge 10.00 per quantity in the cart. It will be 50.00 if the cart has 5 quantity.'."<br>"
                                    . 'iii. [fee percent=10 min_fee=20] -> This means charge 10 percent of cart subtotal, minimum 20 charge will be applicable.', AFRSM_PRO_TEXT_DOMAIN); ?>
                            </p>
                            </div>
                            <div class="product_cost_right_div">
                                <div class="applyperqty-boxone">
                                    <label for="fee_chk_sqm_price"><?php _e('Apply Per SQM', AFRSM_PRO_TEXT_DOMAIN); ?></label>
                                    <input type="checkbox" name="sm_fee_chk_sqm_price" id="fee_chk_sqm_price" class="chk_sqm_price_class" value="on" <?php checked( $getFeesPerSqmFlag, 'on' ); ?>>
                                    <span class="advanced_flat_rate_shipping_for_woocommerce_tab_description"></span>
                                    <p class="description" style="display:none;"><?php _e('Apply this fee per SQM of products.', AFRSM_PRO_TEXT_DOMAIN); ?></p>
                                    <br>
                                    <label for="fee_chk_qty_price"><?php _e('Apply Per Quantity', AFRSM_PRO_TEXT_DOMAIN); ?></label>
                                    <input type="checkbox" name="sm_fee_chk_qty_price" id="fee_chk_qty_price" class="chk_qty_price_class" value="on" <?php checked( $getFeesPerQtyFlag, 'on' ); ?>>
                                    <span class="advanced_flat_rate_shipping_for_woocommerce_tab_description"></span>
                                    <p class="description" style="display:none;"><?php _e('Apply this fee per quantity of products.', AFRSM_PRO_TEXT_DOMAIN); ?></p>
                                </div>
                                <div class="applyperqty-boxtwo">
                                    <label for="apply_per_qty_type"><?php _e('Calculate Quantity Based On', AFRSM_PRO_TEXT_DOMAIN); ?></label>
                                    <select name="sm_fee_per_qty" id="price_cartqty_based" class="chk_qty_price_class" id="apply_per_qty_type">
                                        <option value="qty_cart_based" <?php selected($getFeesPerQty, 'qty_cart_based'); ?>><?php _e('Cart Based', AFRSM_PRO_TEXT_DOMAIN); ?></option>
                                        <option value="qty_product_based" <?php selected($getFeesPerQty, 'qty_product_based'); ?>><?php _e('Product Based', AFRSM_PRO_TEXT_DOMAIN); ?></option>
                                    </select>
                                    <span class="advanced_flat_rate_shipping_for_woocommerce_tab_description"></span>
                                    <p class="description" style="display:none;"><?php _e('If you want to apply the fee for each quantity - where quantity should calculated based on product/category/tag conditions, then select the "Product Based" option.<br> If you want to apply the fee for each quantity in the customer\'s cart, then select the "Cart Based" option.', AFRSM_PRO_TEXT_DOMAIN); ?></p>
                                </div>

	                            <?php
	                            $additionalfee_desc = __('You can add fee here to be charged for each additional quantity. E.g. if user has added 3 quantities and you have set fee=$10 and fee per additional quantity=$5, then total extra fee=$10+$5+$5=$20.<br>'
	                                                     . 'The quantity will be calculated based on the option chosen in the "Calculate Quantity Based On" above dropdown. That means, if you have chosen "Product Based" option - quantities will be calculated based on the products which are meeting the conditions set for this fee, and if they are more than 1, fee will be calculated considering only its additional quantities. e.g. 5 items in cart, and 3 are meeting the condition set, then additional fee of $5 will be charged on 2 quantities only, and not on 4 quantities.', AFRSM_PRO_TEXT_DOMAIN);
	                            ?>
                                <div class="applyperqty-boxthree">
                                    <label for="extra_product_cost"><?php _e('Fee per Additional Quantity&nbsp;(' . get_woocommerce_currency_symbol() . ') ', AFRSM_PRO_TEXT_DOMAIN); ?><span class="required-star">*</span></label>
                                    <input type="text" name="sm_extra_product_cost" class="text-class" id="extra_product_cost" required value="<?php echo isset($extraProductCost) ? $extraProductCost : ''; ?>" placeholder="<?php echo get_woocommerce_currency_symbol(); ?>">
                                    <span class="advanced_flat_rate_shipping_for_woocommerce_tab_description"></span>
                                    <p class="description" style="display:none;"><?php echo $additionalfee_desc; ?></p>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th class="titledesc" scope="row">
                            <label for="sm_tooltip_desc"><?php _e('Tooltip Description', AFRSM_PRO_TEXT_DOMAIN); ?></label>
                        </th>
                        <td class="forminp">
                            <textarea name="sm_tooltip_desc" rows="3" cols="70" id="sm_tooltip_desc" placeholder="<?php _e('Enter tooltip short description', AFRSM_PRO_TEXT_DOMAIN); ?>"><?php echo $sm_tooltip_desc; ?></textarea>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th class="titledesc" scope="row">	
                            <label for="sm_select_taxable"><?php _e('Is Amount Taxable?', AFRSM_PRO_TEXT_DOMAIN); ?></label>
                        </th>
                        <td class="forminp">
                            <select name="sm_select_taxable" id="sm_select_taxable" class="">
                                <option value="no" <?php echo isset($sm_is_taxable) && $sm_is_taxable == 'no' ? 'selected="selected"' : '' ?>><?php _e('No', AFRSM_PRO_TEXT_DOMAIN); ?></option>
                                <option value="yes" <?php echo isset($sm_is_taxable) && $sm_is_taxable == 'yes' ? 'selected="selected"' : '' ?>><?php _e('Yes', AFRSM_PRO_TEXT_DOMAIN); ?></option>
                            </select>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th class="titledesc" scope="row">
                            <label for="sm_estimation_delivery"><?php _e('Estimated Delivery Time', AFRSM_PRO_TEXT_DOMAIN); ?></label>
                        </th>
                        <td class="forminp">
                            <input type="text" name="sm_estimation_delivery" class="text-class" id="sm_estimation_delivery" placeholder="<?php _e('e.g. (2-5 days)', AFRSM_PRO_TEXT_DOMAIN); ?>" value="<?php echo $sm_estimation_delivery; ?>">
                            <span class="advanced_flat_rate_shipping_for_woocommerce_tab_description"></span>
                            <p class="description" style="display:none;">
                                <?php _e('With this feature, you can specify approximately days or time to deliver the order to the customers. It will increase your conversion ratio.', AFRSM_PRO_TEXT_DOMAIN); ?>
                            </p>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th class="titledesc" scope="row">
                            <label for="sm_start_date"><?php _e('Start Date', AFRSM_PRO_TEXT_DOMAIN); ?></label>
                        </th>
                        <td class="forminp">
                            <input type="text" name="sm_start_date" class="text-class" id="sm_start_date" value="<?php echo $sm_start_date; ?>" placeholder="<?php _e('Select start date', AFRSM_PRO_TEXT_DOMAIN); ?>">
                            <span class="advanced_flat_rate_shipping_for_woocommerce_tab_description"></span>
                            <p class="description" style="display:none;"><?php _e('Select start date on which date shipping method will enable on the website.', AFRSM_PRO_TEXT_DOMAIN); ?></p>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th class="titledesc" scope="row">
                            <label for="sm_end_date"><?php _e('End Date', AFRSM_PRO_TEXT_DOMAIN); ?></label>
                        </th>
                        <td class="forminp">
                            <input type="text" name="sm_end_date" class="text-class" id="sm_end_date" value="<?php echo $sm_end_date; ?>" placeholder="<?php _e('Select end date', AFRSM_PRO_TEXT_DOMAIN); ?>">
                            <span class="advanced_flat_rate_shipping_for_woocommerce_tab_description"></span>
                            <p class="description" style="display:none;"><?php _e('Select end date on which date shipping method will disable on the website.', AFRSM_PRO_TEXT_DOMAIN); ?></p>
                        </td>
                    </tr>
                </tbody>
            </table>
            
            <?php
                $all_shipping_classes = WC()->shipping->get_shipping_classes();
                if(!empty($all_shipping_classes)) {
            ?>
                <div class="sub-title">
                    <h2><?php _e('Additional Shipping Charges Based on Shipping Class', AFRSM_PRO_TEXT_DOMAIN); ?></h2>
                </div>
                <div class="tap">
                    <table class="form-table table-outer shipping-method-table">
                        <tbody>
                            <tr valign="top">
                                <td class="forminp" colspan="2">
                                    <?php echo sprintf(__('These costs can optionally be added based on the %sproduct shipping class%s.', AFRSM_PRO_TEXT_DOMAIN), '<a href="' . admin_url('admin.php?page=wc-settings&tab=shipping&section=classes') . '">', '</a>'); ?>
                                </td>
                            </tr>
                            <?php
                                foreach ($all_shipping_classes as $key => $shipping_class) {
                                    $shipping_extra_cost = isset($sm_extra_cost["$shipping_class->term_id"]) && ($sm_extra_cost["$shipping_class->term_id"] !== '') ? $sm_extra_cost["$shipping_class->term_id"] : "";
                            ?>
                                <tr valign="top">
                                    <th class="titledesc" scope="row">
                                        <label for="extra_cost_<?php echo $shipping_class->term_id; ?>"><?php echo sprintf(__('"%s" shipping class cost', AFRSM_PRO_TEXT_DOMAIN), esc_html($shipping_class->name)); ?></label>
                                    </th>
                                    <td class="forminp">
                                        <input type="text" name="sm_extra_cost[<?php echo $shipping_class->term_id; ?>]" class="text-class" id="extra_cost_<?php echo $shipping_class->term_id; ?>" value="<?php echo htmlentities($shipping_extra_cost); ?>" placeholder="<?php echo get_woocommerce_currency_symbol(); ?>">
                                    </td>
                                </tr>
                            <?php } ?>
                            <tr valign="top">
                                <th class="titledesc" scope="row">
                                    <label for="sm_extra_cost_calculation_type"><?php _e('Calculation type', AFRSM_PRO_TEXT_DOMAIN) ?></label>
                                </th>
                                <td class="forminp">
                                    <select name="sm_extra_cost_calculation_type" id="sm_extra_cost_calculation_type">
                                        <option value="per_class" <?php selected( $sm_extra_cost_calc_type, 'per_class' ); ?>><?php _e('Per class: Charge shipping for each shipping class individually', AFRSM_PRO_TEXT_DOMAIN); ?></option>
                                        <option value="per_order" <?php selected( $sm_extra_cost_calc_type, 'per_order' ); ?>><?php _e('Per order: Charge shipping for the most expensive shipping class', AFRSM_PRO_TEXT_DOMAIN); ?></option>
                                    </select>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            <?php } ?>
            
            <div class="sub-title">
                <h2><?php _e('Shipping Method Rules', AFRSM_PRO_TEXT_DOMAIN); ?></h2>
                <div class="tap">
                    <a id="fee-add-field" class="button button-primary button-large" href="javascript:;"><?php _e('+ Add Rule', AFRSM_PRO_TEXT_DOMAIN); ?></a>
                </div>
            </div>
            <div class="tap">
                <table id="tbl-shipping-method" class="tbl_product_fee table-outer tap-cas form-table shipping-method-table">
                    <tbody>
                        <?php
                        if (isset($sm_metabox) && !empty($sm_metabox)) {
                            $i = 2;
                            foreach ($sm_metabox as $key => $productfees) {
                                $fees_conditions = isset($productfees['product_fees_conditions_condition']) ? $productfees['product_fees_conditions_condition'] : '';
                                $condition_is = isset($productfees['product_fees_conditions_is']) ? $productfees['product_fees_conditions_is'] : '';
                                $condtion_value = isset($productfees['product_fees_conditions_values']) ? $productfees['product_fees_conditions_values'] : array();
                                ?>
                                <tr id="row_<?php echo $i; ?>" valign="top">
                                    <th class="titledesc th_product_fees_conditions_condition" scope="row">
                                        <select rel-id="<?php echo $i; ?>" id="product_fees_conditions_condition_<?php echo $i; ?>" name="fees[product_fees_conditions_condition][]" id="product_fees_conditions_condition" class="product_fees_conditions_condition">
                                            <optgroup label="<?php _e('Location Specific', AFRSM_PRO_TEXT_DOMAIN); ?>">
                                                <option value="country" <?php echo ($fees_conditions == 'country' ) ? 'selected' : '' ?>><?php _e('Country', AFRSM_PRO_TEXT_DOMAIN); ?></option>
                                                <option value="state" <?php echo ($fees_conditions == 'state' ) ? 'selected' : '' ?>><?php _e('State', AFRSM_PRO_TEXT_DOMAIN); ?></option>
                                                <option value="postcode" <?php echo ($fees_conditions == 'postcode' ) ? 'selected' : '' ?>><?php _e('Postcode', AFRSM_PRO_TEXT_DOMAIN); ?></option>
                                                <option value="zone" <?php echo ($fees_conditions == 'zone' ) ? 'selected' : '' ?>><?php _e('Zone', AFRSM_PRO_TEXT_DOMAIN); ?></option>
                                            </optgroup>
                                            <optgroup label="<?php _e('Product Specific', AFRSM_PRO_TEXT_DOMAIN); ?>">
                                                <option value="product" <?php echo ($fees_conditions == 'product' ) ? 'selected' : '' ?>><?php _e('Cart contains product', AFRSM_PRO_TEXT_DOMAIN); ?></option>
                                                <option value="variableproduct" <?php echo ($fees_conditions == 'variableproduct' ) ? 'selected' : '' ?>><?php _e('Cart contains variable product', AFRSM_PRO_TEXT_DOMAIN); ?></option>
                                                <option value="category" <?php echo ($fees_conditions == 'category' ) ? 'selected' : '' ?>><?php _e('Cart contains category\'s product', AFRSM_PRO_TEXT_DOMAIN); ?></option>
                                                <option value="tag" <?php echo ($fees_conditions == 'tag' ) ? 'selected' : '' ?>><?php _e('Cart contains tag\'s product', AFRSM_PRO_TEXT_DOMAIN); ?></option>
                                                <option value="sku" <?php echo ($fees_conditions == 'sku' ) ? 'selected' : '' ?>><?php _e('Cart contains SKU\'s product', AFRSM_PRO_TEXT_DOMAIN); ?></option>
                                            </optgroup>	
                                            <optgroup label="<?php _e('User Specific', AFRSM_PRO_TEXT_DOMAIN); ?>">
                                                <option value="user" <?php echo ($fees_conditions == 'user' ) ? 'selected' : '' ?>><?php _e('User', AFRSM_PRO_TEXT_DOMAIN); ?></option>
                                                <option value="user_role" <?php echo ($fees_conditions == 'user_role' ) ? 'selected' : '' ?>><?php _e('User Role', AFRSM_PRO_TEXT_DOMAIN); ?></option>
                                            </optgroup>
                                            <optgroup label="<?php _e('Cart Specific', AFRSM_PRO_TEXT_DOMAIN); ?>">
                                                <?php
                                                $currency_symbol = get_woocommerce_currency_symbol();
                                                $currency_symbol = !empty($currency_symbol) ? '(' . $currency_symbol . ')' : '';

                                                $weight_unit = get_option('woocommerce_weight_unit');
                                                $weight_unit = !empty($weight_unit) ? '(' . $weight_unit . ')' : '';
                                                ?>	
                                                <option value="cart_total" <?php echo ($fees_conditions == 'cart_total' ) ? 'selected' : '' ?>><?php _e('Cart Subtotal (Before Discount) ', AFRSM_PRO_TEXT_DOMAIN); ?><?php echo $currency_symbol; ?></option>
                                                <option value="cart_totalafter" <?php echo ($fees_conditions == 'cart_totalafter' ) ? 'selected' : '' ?>><?php _e('Cart Subtotal (After Discount) ', AFRSM_PRO_TEXT_DOMAIN); ?><?php echo $currency_symbol; ?></option>
                                                <option value="quantity" <?php echo ($fees_conditions == 'quantity' ) ? 'selected' : '' ?>><?php _e('Quantity', AFRSM_PRO_TEXT_DOMAIN); ?></option>
                                                <option value="weight" <?php echo ($fees_conditions == 'weight' ) ? 'selected' : '' ?>><?php _e('Weight ', AFRSM_PRO_TEXT_DOMAIN); ?><?php echo $weight_unit; ?></option>
                                                <option value="coupon" <?php echo ($fees_conditions == 'coupon' ) ? 'selected' : '' ?>><?php _e('Coupon', AFRSM_PRO_TEXT_DOMAIN); ?></option>
                                                <option value="shipping_class" <?php echo ($fees_conditions == 'shipping_class' ) ? 'selected' : '' ?>><?php _e('Shipping Class', AFRSM_PRO_TEXT_DOMAIN); ?></option>
                                            </optgroup>
                                        </select>
                                    </th>	
                                    <td class="select_condition_for_in_notin">
                                        <?php if ($fees_conditions == 'cart_total' || $fees_conditions == 'cart_totalafter' || $fees_conditions == 'quantity' || $fees_conditions == 'weight') { ?>
                                            <select name="fees[product_fees_conditions_is][]" class="product_fees_conditions_is_<?php echo $i; ?>">
                                                <option value="is_equal_to" <?php echo ($condition_is == 'is_equal_to' ) ? 'selected' : '' ?>><?php _e('Equal to ( = )', AFRSM_PRO_TEXT_DOMAIN); ?></option>
                                                <option value="less_equal_to" <?php echo ($condition_is == 'less_equal_to' ) ? 'selected' : '' ?>><?php _e('Less or Equal to ( <= )', AFRSM_PRO_TEXT_DOMAIN); ?></option>
                                                <option value="less_then" <?php echo ($condition_is == 'less_then' ) ? 'selected' : '' ?>><?php _e('Less than ( < )', AFRSM_PRO_TEXT_DOMAIN); ?></option>
                                                <option value="greater_equal_to" <?php echo ($condition_is === 'greater_equal_to' ) ? 'selected' : '' ?>><?php _e('Greater or Equal to ( >= )', AFRSM_PRO_TEXT_DOMAIN); ?></option>
                                                <option value="greater_then" <?php echo ($condition_is == 'greater_then' ) ? 'selected' : '' ?>><?php _e('Greater than ( > )', AFRSM_PRO_TEXT_DOMAIN); ?></option>
                                                <option value="not_in" <?php echo ($condition_is == 'not_in' ) ? 'selected' : '' ?>><?php _e('Not Equal to ( != )', AFRSM_PRO_TEXT_DOMAIN); ?></option>
                                            </select>
                                        <?php } else { ?>
                                            <select name="fees[product_fees_conditions_is][]" class="product_fees_conditions_is_<?php echo $i; ?>">
                                                <option value="is_equal_to" <?php echo ($condition_is == 'is_equal_to' ) ? 'selected' : '' ?>><?php _e('Equal to ( = )', AFRSM_PRO_TEXT_DOMAIN); ?></option>
                                                <option value="not_in" <?php echo ($condition_is == 'not_in' ) ? 'selected' : '' ?>><?php _e('Not Equal to ( != )', AFRSM_PRO_TEXT_DOMAIN); ?> </option>
                                            </select>
                                        <?php } ?>
                                    </td>
                                    <td class="condition-value" id="column_<?php echo $i; ?>">
                                        <?php
                                        $html = '';
                                        if ($fees_conditions == 'country') {
                                            $html .= $this->afrsm_pro_get_country_list($i, $condtion_value);
                                        } elseif ($fees_conditions == 'state') {
                                            $html .= $this->afrsm_pro_get_states_list($i, $condtion_value);
                                        } elseif ($fees_conditions == 'postcode') {
                                            $html .= '<textarea name = "fees[product_fees_conditions_values][value_' . $i . ']">' . $condtion_value . '</textarea>';
                                        } elseif ($fees_conditions == 'zone') {
                                            $html .= $this->afrsm_pro_get_zones_list($i, $condtion_value);
                                        } elseif ($fees_conditions == 'product') {
                                            $html .= $this->afrsm_pro_get_product_list($i, $condtion_value);
                                        }elseif ($fees_conditions == 'variableproduct') {
	                                        $html .= $this->afrsm_pro_get_varible_product_list($i, $condtion_value);
                                        }elseif ($fees_conditions == 'category') {
                                            $html .= $this->afrsm_pro_get_category_list($i, $condtion_value);
                                        } elseif ($fees_conditions == 'tag') {
                                            $html .= $this->afrsm_pro_get_tag_list($i, $condtion_value);
                                        } elseif ($fees_conditions == 'sku') {
                                            $html .= $this->afrsm_pro_get_sku_list($i, $condtion_value);
                                        } elseif ($fees_conditions == 'user') {
                                            $html .= $this->afrsm_pro_get_user_list($i, $condtion_value);
                                        } elseif ($fees_conditions == 'user_role') {
                                            $html .= $this->afrsm_pro_get_user_role_list($i, $condtion_value);
                                        } elseif ($fees_conditions == 'cart_total') {
                                            $html .= '<input type = "text" name = "fees[product_fees_conditions_values][value_' . $i . ']" id = "product_fees_conditions_values" class = "product_fees_conditions_values" value = "' . $condtion_value . '">';
                                        } elseif ($fees_conditions == 'cart_totalafter') {
                                            $html .= '<input type="text" name="fees[product_fees_conditions_values][value_' . $i . ']" id="product_fees_conditions_values" class="product_fees_conditions_values" value="' . $condtion_value . '">';
                                        } elseif ($fees_conditions == 'quantity') {
                                            $html .= '<input type = "text" name = "fees[product_fees_conditions_values][value_' . $i . ']" id = "product_fees_conditions_values" class = "product_fees_conditions_values" value = "' . $condtion_value . '">';
                                        } elseif ($fees_conditions == 'weight') {
                                            $html .= '<input type = "text" name = "fees[product_fees_conditions_values][value_' . $i . ']" id = "product_fees_conditions_values" class = "product_fees_conditions_values" value = "' . $condtion_value . '">';
                                        } elseif ($fees_conditions == 'coupon') {
                                            $html .= $this->afrsm_pro_get_coupon_list($i, $condtion_value);
                                        } elseif ($fees_conditions == 'shipping_class') {
                                            $html .= $this->afrsm_pro_get_advance_flat_rate_class($i, $condtion_value);
                                        }
                                        echo $html;
                                        ?>
                                        <input type="hidden" name="condition_key[<?php echo 'value_' . $i; ?>]" value="">
                                    </td>
                                    <td><a id="fee-delete-field" rel-id="<?php echo $i; ?>" class="delete-row" href="javascript:;" title="Delete"><i class="fa fa-trash"></i></a></td>
                                </tr>
                                <?php
                                $i++;
                            }
                            ?>
                            <?php
                        } else {
                            $i = 1;
                            ?>	
                            <tr id="row_1" valign="top">
                                <th class="titledesc th_product_fees_conditions_condition" scope="row">
                                    <select rel-id="1" id="product_fees_conditions_condition_1" name="fees[product_fees_conditions_condition][]" id="product_fees_conditions_condition" class="product_fees_conditions_condition">
                                        <optgroup label="<?php _e('Location Specific', AFRSM_PRO_TEXT_DOMAIN); ?>">
                                            <option value="country"><?php _e('Country', AFRSM_PRO_TEXT_DOMAIN); ?></option>
                                            <option value="state"><?php _e('State', AFRSM_PRO_TEXT_DOMAIN); ?></option>
                                            <option value="postcode"><?php _e('Postcode', AFRSM_PRO_TEXT_DOMAIN); ?></option>
                                            <option value="zone"><?php _e('Zone', AFRSM_PRO_TEXT_DOMAIN); ?></option>
                                        </optgroup>
                                        <optgroup label="<?php _e('Product Specific', AFRSM_PRO_TEXT_DOMAIN); ?>">
                                            <option value="product"><?php _e('Cart contains product', AFRSM_PRO_TEXT_DOMAIN); ?></option>
                                            <option value="product"><?php _e('Cart contains variable product', AFRSM_PRO_TEXT_DOMAIN); ?></option>
                                            <option value="category"><?php _e('Cart contains category\'s product', AFRSM_PRO_TEXT_DOMAIN); ?></option>
                                            <option value="tag"><?php _e('Cart contains tag\'s product', AFRSM_PRO_TEXT_DOMAIN); ?></option>
                                            <option value="sku"><?php _e('Cart contains SKU\'s product', AFRSM_PRO_TEXT_DOMAIN); ?></option>
                                        </optgroup>	
                                        <optgroup label="<?php _e('User Specific', AFRSM_PRO_TEXT_DOMAIN); ?>">
                                            <option value="user"><?php _e('User', AFRSM_PRO_TEXT_DOMAIN); ?></option>
                                            <option value="user_role"><?php _e('User Role', AFRSM_PRO_TEXT_DOMAIN); ?></option>
                                        </optgroup>
                                        <optgroup label="<?php _e('Cart Specific', AFRSM_PRO_TEXT_DOMAIN); ?>">
                                            <?php
                                                $get_woocommerce_currency_symbol = get_woocommerce_currency_symbol();
                                                $woocommerce_weight_unit = get_option('woocommerce_weight_unit');
                                                $currency_symbol    = !empty( $get_woocommerce_currency_symbol ) ? '(' . $get_woocommerce_currency_symbol . ')' : '';
                                                $weight_unit        = !empty( $woocommerce_weight_unit ) ? '(' . $woocommerce_weight_unit . ')' : '';
                                            ?>
                                            <option value="cart_total"><?php _e('Cart Subtotal (Before Discount) ', AFRSM_PRO_TEXT_DOMAIN); ?><?php echo $currency_symbol; ?></option>
                                            <option value="cart_totalafter"><?php _e('Cart Subtotal (After Discount) ', AFRSM_PRO_TEXT_DOMAIN); ?><?php echo $currency_symbol; ?></option>
                                            <option value="quantity"><?php _e('Quantity', AFRSM_PRO_TEXT_DOMAIN); ?></option>
                                            <option value="weight"><?php _e('Weight', AFRSM_PRO_TEXT_DOMAIN); ?> <?php echo $weight_unit; ?></option>
                                            <option value="coupon"><?php _e('Coupon', AFRSM_PRO_TEXT_DOMAIN); ?></option>
                                            <option value="shipping_class"><?php _e('Shipping Class', AFRSM_PRO_TEXT_DOMAIN); ?></option>
                                        </optgroup>
                                    </select>		
                                <td class="select_condition_for_in_notin">
                                    <select name="fees[product_fees_conditions_is][]" class="product_fees_conditions_is product_fees_conditions_is_1">
                                        <option value="is_equal_to"><?php _e('Equal to ( = )', AFRSM_PRO_TEXT_DOMAIN); ?></option>
                                        <option value="not_in"><?php _e('Not Equal to ( != )', AFRSM_PRO_TEXT_DOMAIN); ?></option>
                                    </select>
                                </td>
                                <td id="column_1" class="condition-value">
                                    <?php echo $this->afrsm_pro_get_country_list(1); ?>
                                    <input type="hidden" name="condition_key[value_1][]" value="">
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <input type="hidden" name="total_row" id="total_row" value="<?php echo $i; ?>">
            </div>
            <p class="submit">
                <input type="submit" name="submitFee" class="button button-primary button-large" value="<?php echo $submit_text; ?>">
            </p>
        </form>
    </div>
</div>

<?php require_once('header/plugin-sidebar.php'); 