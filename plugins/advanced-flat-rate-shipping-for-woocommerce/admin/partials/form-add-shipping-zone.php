<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) {
    exit;
}

require_once('header/plugin-header.php');
?>

<div class="afrsm-section-left afrsm-pro-list-shipping-zones">
    <div class="right_button_add_zone">
        <a href="<?php echo admin_url('/admin.php?page=wc-shipping-zones'); ?>" class="button-primary"><?php _e('Go to Shipping Zone', AFRSM_PRO_TEXT_DOMAIN); ?></a>
    </div>
    <div class="afrsm-pro-zone-table res-cl">
        <h2><?php _e('Add Shipping Zone', AFRSM_PRO_TEXT_DOMAIN); ?></h2>
    </div>
    <div class="form-wrap">
        <form id="add-zone" class="afrsm-shipping-zone" method="post">
            <table class="form-table">
                <tr>
                    <th>
                        <label for="zone_name"><?php _e('Name', AFRSM_PRO_TEXT_DOMAIN); ?></label>
                    </th>
                    <td>
                        <input type="text" name="zone_name" id="zone_name" class="input-text">
                    </td>
                </tr>
                <tr>
                    <th><?php _e('Zone Type', AFRSM_PRO_TEXT_DOMAIN); ?></th>
                    <td>
                        <fieldset>
                            <legend class="screen-reader-text">
                                <span><?php _e('Zone Type', AFRSM_PRO_TEXT_DOMAIN); ?></span>
                            </legend>

                            <div class="zone_type_options zone_type_countries">
                                <p class="chck"><label><input type="radio" name="zone_type" value="countries" id="zone_type" class="input-radio" checked="checked" /> 
                                <?php _e('This shipping zone is based on one or more countries', AFRSM_PRO_TEXT_DOMAIN); ?></label></p>
                                <select multiple="multiple" name="zone_type_countries[]" data-placeholder="<?php _e('Choose countries&hellip;', AFRSM_PRO_TEXT_DOMAIN); ?>" class="chosen-select zone_type_country_cls">
                                    <?php
                                        foreach ($countries as $key => $val) {
                                            echo '<option value="' . esc_attr($key) . '">' . esc_html($val) . '</option>';
                                        }
                                    ?>
                                </select>
                                <p><button class="select_all button"><?php _e('All', AFRSM_PRO_TEXT_DOMAIN); ?></button><button class="select_none button"><?php _e('None', AFRSM_PRO_TEXT_DOMAIN); ?>
                                    </button>
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
                                <p class="chck">
                                    <label>
                                        <input type="radio" name="zone_type" value="states" id="zone_type" class="input-radio"><?php _e('This shipping zone is based on one of more states and counties', AFRSM_PRO_TEXT_DOMAIN); ?>
                                    </label>
                                </p>
                                <select multiple="multiple" name="zone_type_states[]" data-placeholder="<?php _e('Choose states/counties&hellip;', AFRSM_PRO_TEXT_DOMAIN); ?>"  class="chosen-select zone_type_states_cls">
                                    <?php
                                    foreach ($countries as $key => $val) {
                                        echo '<option value="' . esc_attr($key) . '">' . esc_html($val) . '</option>';

                                        if ($states = WC()->countries->get_states($key)) {
                                            foreach ($states as $state_key => $state_value) {
                                                echo '<option value="' . esc_attr($key . ':' . $state_key) . '">' . esc_html($val . ' &gt; ' . $state_value) . '</option>';
                                            }
                                        }
                                    }
                                    ?>
                                </select>
                                <p>
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
                                <p class="chck"><label><input type="radio" name="zone_type" value="postcodes" id="zone_type" class="input-radio" /> <?php _e('This shipping zone is based on one of more postcodes/zips', AFRSM_PRO_TEXT_DOMAIN); ?></label></p>
                                <select name="zone_type_postcodes" data-placeholder="<?php _e('Choose countries&hellip;', AFRSM_PRO_TEXT_DOMAIN); ?>" title="Country" class="chosen_select zone_type_postcode_cls">
                                    <?php
                                        foreach ($countries as $key => $val) {
                                            echo '<option value="' . esc_attr($key) . '" ' . selected($key, $base, false) . '>' . esc_html($val) . '</option>';

                                            if ($states = WC()->countries->get_states($key)) {
                                                foreach ($states as $state_key => $state_value) {
                                                    echo '<option value="' . esc_attr($key . ':' . $state_key) . '">' . esc_html($val . ' &gt; ' . $state_value) . '</option>';
                                                }
                                            }
                                        }
                                    ?>
                                </select>
                                <br>
                                <label for="postcodes" class="postcodes"><?php _e('Postcodes', AFRSM_PRO_TEXT_DOMAIN); ?> 
                                    <span class="tooltip_con"><img class="help_tip" width="16" data-tip="List 1 postcode per line. Wildcards (*) and ranges (for numeric postcodes) are supported" src="<?php echo WC()->plugin_url(); ?>/assets/images/help.png" />
                                        <i>List 1 postcode per line. Wildcards (*) and ranges (for numeric postcodes) are supported</i>
                                    </span>
                                </label>
                                <textarea name="postcodes" id="postcodes" class="input-text large-text" cols="25" rows="5"></textarea>
                            </div>
                        </fieldset>
                    </td>
                </tr>
            </table>
            <p class="submit">
                <input type="submit" class="button button-primary" name="add_zone" value="<?php _e('Add shipping zone', AFRSM_PRO_TEXT_DOMAIN); ?>">
            </p>
            <?php wp_nonce_field('woocommerce_save_zone', 'woocommerce_save_zone_nonce'); ?>
        </form>
    </div>
</div>

<?php require_once('header/plugin-sidebar.php'); ?>