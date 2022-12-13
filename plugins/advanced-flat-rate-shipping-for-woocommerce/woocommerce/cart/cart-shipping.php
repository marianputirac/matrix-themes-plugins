<?php
/**
 * Shipping Methods Display
 */
if (!defined('ABSPATH')) {
    exit;
}
?>
<tr class="shipping">
    <th><?php echo wp_kses_post($package_name); ?></th>
    <td data-title="<?php echo esc_attr($package_name); ?>">
        <?php
            $get_what_to_do_method = get_option('what_to_do_method');
            $get_what_to_do_method = !empty( $get_what_to_do_method ) ? $get_what_to_do_method : 'allow_customer';
            
            $shipping_method_format = get_option('md_woocommerce_shipping_method_format');
            $shipping_method_format = !empty( $shipping_method_format ) ? $shipping_method_format : 'radio_button_mode';
        ?>
        <?php
            if (!empty($available_methods)) {
                if (!empty($get_what_to_do_method)) {
                    if ($get_what_to_do_method == 'allow_customer') {

                        unset($available_methods['forceall']);

                        $available_methods = $available_methods;
                    } elseif ($get_what_to_do_method == 'apply_highest') {

                        unset($available_methods['forceall']);

                        $max = -9999999;
                        $apply_highest = array();
                        $key = '';
                        foreach ($available_methods as $k => $v) {
                            if ($v->cost > $max) {
                                $max = $v->cost;
                                $key = $k;
                                $apply_highest = $v;
                            }
                        }
                        $apply_highest_method[$key] = $apply_highest;
                        $available_methods = $apply_highest_method;
                    } elseif ($get_what_to_do_method == 'apply_smallest') {

                        unset($available_methods['forceall']);

                        $min = 9999999;
                        $apply_smallest = array();
                        $key = '';
                        foreach ($available_methods as $k => $v) {
                            if ($v->cost < $min) {
                                $min = $v->cost;
                                $key = $k;
                                $apply_smallest = $v;
                            }
                        }
                        $apply_smallest_method[$key] = $apply_smallest;
                        $available_methods = $apply_smallest_method;
                    } elseif ($get_what_to_do_method == 'force_all') {
                        
                        $forceall = array();
                        foreach ($available_methods as $k => $v) {
                            if ($k == 'forceall') {
                                $key = $k;
                                $forceall = $v;
                            }
                        }
                        $forceall_method[$key] = $forceall;
                        $available_methods = $forceall_method;
                    }
                }
            }
        ?>
        
        <?php /** @var TYPE_NAME $available_methods */
        if ( 1 < count($available_methods)) { ?>
            <?php
                $sort_order = array();
                $getSortOrder = get_option('sm_sortable_order');
                
                if (isset($getSortOrder) && !empty($getSortOrder)) {
                    foreach ($getSortOrder as $sort) {
                        $sort_order[$sort] = array();
                    }
                }
                
                foreach ($available_methods as $carrier_id => $carrier) {
                    $carrier_name = $carrier->id;

                    if (array_key_exists($carrier_name, $sort_order)) {
                        $sort_order[$carrier_name][$carrier_id] = $available_methods[$carrier_id];
                        unset($available_methods[$carrier_id]);
                    }
                }
                foreach ($sort_order as $carriers) {
                    $available_methods = array_merge($available_methods, $carriers);
                }
                
                $chosen_shipping_methods = WC()->session->get('chosen_shipping_methods');
                if ( in_array("forceall", $chosen_shipping_methods) ) {

                    $method = current($available_methods);
                    $chosen_shipping_methods_array = explode(' ', $method->id);
                    WC()->session->set('chosen_shipping_methods', $chosen_shipping_methods_array);
                }
            ?>
            <?php if( $shipping_method_format === 'dropdown_mode' ) { ?>
                <select name="shipping_method[<?php echo $index; ?>]" data-index="<?php echo $index; ?>" id="shipping_method_<?php echo $index; ?>" class="shipping_method">
                    <?php foreach ($available_methods as $method) { ?>
                        <option value="<?php echo esc_attr($method->id); ?>" <?php selected($method->id, $chosen_method); ?>><?php echo wp_kses_post(wc_cart_totals_shipping_method_label($method)); ?></option>
                    <?php } ?>
                </select>
            <?php } else { ?>
                <ul id="shipping_method">
                    <?php foreach ($available_methods as $method) { ?>
                        <li>
                            <?php
                                $tool_tip_html = '';
                                $final_shipping_label = '';

                                $sm_tooltip_desc = get_post_meta($method->id, 'sm_tooltip_desc', true);
                                $sm_tooltip_desc = ( isset($sm_tooltip_desc) && !empty($sm_tooltip_desc) ) ? $sm_tooltip_desc : '';

                                $sm_estimation_delivery = get_post_meta($method->id, 'sm_estimation_delivery', true);
                                $sm_estimation_delivery = ( isset($sm_estimation_delivery) && !empty($sm_estimation_delivery) ) ? (" (".$sm_estimation_delivery.")" ) : '';

                                $final_shipping_label  .= $sm_tooltip_desc . $sm_estimation_delivery;
                                
                                if(!empty($final_shipping_label)) {
                                    $tool_tip_html .= '<div class="extra-flate-tool-tip"><a data-tooltip="' . $final_shipping_label . '"><i class="fa fa-question-circle fa-lg"></i></a></div>';
                                }
                                
                                printf($tool_tip_html . '<label class="shipping_method_tooltip" id="hover-tips" for="shipping_method_%1$d_%2$s"><input type="radio" name="shipping_method_[%1$d]" data-index="%1$d" id="shipping_method_%1$d_%2$s" value="%3$s" class="shipping_method" %4$s />
                                                                            %5$s <br><span>%6$s</span></label>', $index, sanitize_title( $method->id ), esc_attr( $method->id ), checked( $method->id, $chosen_method, false ), wc_cart_totals_shipping_method_label( $method ), $sm_estimation_delivery);
                                do_action('woocommerce_after_shipping_rate', $method, $index);
                            ?>
                        </li>
                    <?php } ?>
                </ul>
            <?php } ?>
        
        <?php } elseif (1 === count($available_methods)) { ?>
        
            <?php
                /* Tool tip html start */
                $tool_tip_html = '';
                $final_shipping_label = '';

                $method = current($available_methods);
                $chosen_shipping_methods_array = explode(' ', $method->id);
                WC()->session->set('chosen_shipping_methods', $chosen_shipping_methods_array);
                
                $sm_tooltip_desc = get_post_meta($method->id, 'sm_tooltip_desc', true);
                $sm_tooltip_desc = ( isset($sm_tooltip_desc) && !empty($sm_tooltip_desc) ) ? $sm_tooltip_desc : '';

                $sm_estimation_delivery = get_post_meta($method->id, 'sm_estimation_delivery', true);
                $sm_estimation_delivery = ( isset($sm_estimation_delivery) && !empty($sm_estimation_delivery) ) ? (" (".$sm_estimation_delivery.")" ) : '';

                $final_shipping_label .= $sm_tooltip_desc . $sm_estimation_delivery;

                if (!empty($sm_tooltip_desc)) {
                    $tool_tip_html .= '<div class="extra-flate-tool-tip"><a data-tooltip="' . $final_shipping_label . '"><i class="fa fa-question-circle fa-lg"></i></a></div>';
                }
                
                printf($tool_tip_html . ' %3$s <br><span>%4$s</span><input type="hidden" name="shipping_method[%1$d]" data-index="%1$d" id="shipping_method_%1$d" value="%2$s" class="shipping_method" />', $index, esc_attr($method->id), wc_cart_totals_shipping_method_label($method), $sm_estimation_delivery);
                do_action('woocommerce_after_shipping_rate', $method, $index);
                /* Tool tip html end */
            ?>
        
        <?php } elseif (!WC()->customer->has_calculated_shipping()) { ?>
            <?php echo wpautop(__('Shipping costs will be calculated once you have provided your address.', 'woocommerce')); ?>
        <?php } else { ?>
            <?php echo apply_filters(is_cart() ? 'woocommerce_cart_no_shipping_available_html' : 'woocommerce_no_shipping_available_html', wpautop(__('There are no shipping methods available. Please double check your address, or contact us if you need any help.', 'woocommerce'))); ?>
        <?php } ?>

        <?php if ($show_package_details) { ?>
            <?php echo '<p class="woocommerce-shipping-contents"><small>' . esc_html($package_details) . '</small></p>'; ?>
        <?php } ?>

        
        <?php if (!empty($show_shipping_calculator)) { ?>
            <?php woocommerce_shipping_calculator(); ?>
        <?php } ?>
    </td>
</tr>