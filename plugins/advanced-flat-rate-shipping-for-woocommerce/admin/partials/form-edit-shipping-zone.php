<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) {
    exit;
}

require_once('header/plugin-header.php');
?>

<div class="afrsm-section-left afrsm-pro-list-shipping-zones">
    <div class="edit-inner">
        <div class="right_button_add_zone">
            <a href="<?php echo admin_url('/admin.php?page=wc-shipping-zones'); ?>" class="button-primary"><?php _e('Go to Shipping Zone', AFRSM_PRO_TEXT_DOMAIN); ?></a>
        </div>
        <div class="afrsm-pro-zone-table res-cl">
            <h2><?php _e('Edit Shipping Zone', AFRSM_PRO_TEXT_DOMAIN); ?> &mdash; <?php echo esc_html($zone->zone_name) ?></h2>
        </div>
        <div class="form-wrap">
            <form id="add-zone" class="afrsm-shipping-zone" method="post">
                <table class="form-table">
                    <tr>
                        <th>
                            <label for="zone_name"><?php _e('Name', AFRSM_PRO_TEXT_DOMAIN); ?></label>
                        </th>
                        <td>
                            <input type="text" name="zone_name" id="zone_name" class="input-text" placeholder="<?php _e('Enter a name which describes this zone', AFRSM_PRO_TEXT_DOMAIN); ?>" value="<?php echo esc_attr($zone->zone_name) ?>" />
                        </td>
                    </tr>
                    <tr>
                        <th>
                            <label for="zone_name"><?php _e('Enable', AFRSM_PRO_TEXT_DOMAIN); ?></label>
                        </th>
                        <td>
                            <label><input type="checkbox" name="zone_enabled" value="1" id="zone_enabled" class="input-checkbox" <?php checked($zone->zone_enabled, 1); ?> /> <?php _e('Enable this zone', AFRSM_PRO_TEXT_DOMAIN); ?></label>
                        </td>
                    </tr>
                    <tr>
                        <th><?php _e('Zone Type', AFRSM_PRO_TEXT_DOMAIN); ?></th>
                        <td>
                            <fieldset>
                                <legend class="screen-reader-text"><span><?php _e('Zone Type', AFRSM_PRO_TEXT_DOMAIN); ?></span></legend>
                                <div class="zone_type_options zone_type_countries">
                                    <p><label><input type="radio" name="zone_type" value="countries" id="zone_type" class="input-radio" <?php checked($zone->zone_type, 'countries'); ?> /> <?php _e('This shipping zone is based on one or more countries', AFRSM_PRO_TEXT_DOMAIN); ?></label></p>
                                    <select multiple="multiple" name="zone_type_countries[]" style="width:450px;" data-placeholder="<?php _e('Choose countries&hellip;', AFRSM_PRO_TEXT_DOMAIN); ?>" class="chosen-select">
                                        <?php
                                            foreach ($countries as $key => $val) {
                                                echo '<option value="' . esc_attr($key) . '" ' . selected(in_array($key, $location_counties)) . '>' . esc_html($val) . '</option>';
                                            }
                                        ?>
                                    </select>
                                    <p class="btngrp">
                                        <button class="select_all button"><?php _e('All', AFRSM_PRO_TEXT_DOMAIN); ?></button>
                                        <button class="select_none button"><?php _e('None', AFRSM_PRO_TEXT_DOMAIN); ?></button>
                                        <button class="button select_africa"><?php _e('Africa Country', AFRSM_PRO_TEXT_DOMAIN); ?></button>
                                        <button class="button select_antarctica"><?php _e('Antarctica Country', AFRSM_PRO_TEXT_DOMAIN); ?></button>
                                        <button class="button select_asia"><?php _e('Asia Country', AFRSM_PRO_TEXT_DOMAIN); ?></button>
                                        <button class="button select_europe"><?php _e('EU States', AFRSM_PRO_TEXT_DOMAIN); ?></button>
                                        <button class="button select_northamerica"><?php _e('North America', AFRSM_PRO_TEXT_DOMAIN); ?></button>
                                        <button class="button select_oceania"><?php _e('Oceania', AFRSM_PRO_TEXT_DOMAIN); ?></button>
                                        <button class="button select_southamerica"><?php _e('South America', AFRSM_PRO_TEXT_DOMAIN); ?></button>
                                    </p>
                                </div>
                                <div class="zone_type_options zone_type_states">
                                    <p><label><input type="radio" name="zone_type" value="states" id="zone_type" class="input-radio" <?php checked($zone->zone_type, 'states'); ?> /> <?php _e('This shipping zone is based on one of more states/counties', AFRSM_PRO_TEXT_DOMAIN); ?></label></p>
                                    <select multiple="multiple" name="zone_type_states[]" style="width:450px;" data-placeholder="<?php _e('Choose states/counties&hellip;', AFRSM_PRO_TEXT_DOMAIN); ?>"  class="chosen-select  wp-enhanced-select">
                                        <?php
                                        foreach ($countries as $key => $val) {
                                            echo '<option value="' . esc_attr($key) . '" ' . selected(in_array($key, $selected_states), true, false) . '>' . esc_html($val) . '</option>';

                                            if ($states = WC()->countries->get_states($key)) {
                                                foreach ($states as $state_key => $state_value) {
                                                    echo '<option value="' . esc_attr($key . ':' . $state_key) . '" ' . selected(in_array($key . ':' . $state_key, $selected_states), true, false) . '>' . esc_html($val . ' &gt; ' . $state_value) . '</option>';
                                                }
                                            }
                                        }
                                        ?>
                                    </select>
                                    <p class="btngrp">
                                        <button class="select_all button"><?php _e('All', AFRSM_PRO_TEXT_DOMAIN); ?></button>
                                        <button class="select_none button"><?php _e('None', AFRSM_PRO_TEXT_DOMAIN); ?></button>
                                        <button class="button select_africa_states"><?php _e('Africa States', AFRSM_PRO_TEXT_DOMAIN); ?></button>
                                        <button class="button select_asia_states"><?php _e('Asia States', AFRSM_PRO_TEXT_DOMAIN); ?></button>
                                        <button class="button select_europe"><?php _e('EU States', AFRSM_PRO_TEXT_DOMAIN); ?></button>
                                        <button class="button select_us_states"><?php _e('US States', AFRSM_PRO_TEXT_DOMAIN); ?></button>
                                        <button class="button select_oceania_states"><?php _e('Oceania States', AFRSM_PRO_TEXT_DOMAIN); ?></button>
                                    </p>
                                </div>
                                <div class="zone_type_options zone_type_postcodes">
                                    <p><label><input type="radio" name="zone_type" value="postcodes" id="zone_type" class="input-radio" <?php checked($zone->zone_type, 'postcodes'); ?> /> <?php _e('This shipping zone is based on one of more postcodes/zips', AFRSM_PRO_TEXT_DOMAIN); ?></label></p>
                                    <select name="zone_type_postcodes" style="width:450px;" data-placeholder="<?php _e('Choose countries&hellip;', AFRSM_PRO_TEXT_DOMAIN); ?>" title="Country" class="chosen-select">
                                        <?php
                                            foreach ($countries as $key => $val) {
                                                echo '<option value="' . esc_attr($key) . '" ' . selected(in_array($key, $selected_states), true, false) . '>' . esc_html($val) . '</option>';
                                                if ($states = WC()->countries->get_states($key)) {
                                                    foreach ($states as $state_key => $state_value) {
                                                        echo '<option value="' . esc_attr($key . ':' . $state_key) . '" ' . selected(in_array($key . ':' . $state_key, $selected_states), true, false) . '>' . esc_html($val . ' &gt; ' . $state_value) . '</option>';
                                                    }
                                                }
                                            }
                                        ?>
                                    </select>

                                    <p>
                                        <label for="postcodes" class="postcodes"><?php _e('Postcodes', AFRSM_PRO_TEXT_DOMAIN); ?> 
                                            <span class="tooltip_con"><img class="help_tip" width="16" data-tip="List 1 postcode per line. Wildcards (*) and ranges (for numeric postcodes) are supported" src="<?php echo WC()->plugin_url(); ?>/assets/images/help.png" />
                                                <i>List 1 postcode per line. Wildcards (*) and ranges (for numeric postcodes) are supported</i>
                                            </span>
                                        </label>

                                        <textarea name="postcodes" id="postcodes" class="input-text large-text" cols="25" rows="5"><?php foreach ($location_postcodes as $location) { echo esc_textarea($location) . "\n"; } ?></textarea>
                                    </p>
                                </div>

                            </fieldset>
                        </td>
                    </tr>
                </table>
                <p class="submit">
                    <input type="submit" class="button button button-primary" name="edit_zone" value="<?php _e('Save changes', AFRSM_PRO_TEXT_DOMAIN); ?>" />
                    <?php wp_nonce_field('woocommerce_save_zone', 'woocommerce_save_zone_nonce'); ?>
                </p>
            </form>
        </div>
    </div>
</div>

<?php require_once('header/plugin-sidebar.php'); ?>