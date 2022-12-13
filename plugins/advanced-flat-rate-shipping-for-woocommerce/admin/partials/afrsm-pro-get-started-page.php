<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) {
    exit;
}

require_once('header/plugin-header.php');
?>

<!--Subscribe Newsletter Form Start-->
<?php
    $current_user = wp_get_current_user();
    $afrsm_plugin_notice_shown = get_option('afrsm_plugin_notice_shown');
    if (!$afrsm_plugin_notice_shown) {
?>
    <div id="dotstore_subscribe_dialog">
        <p><?php _e('Subscribe for the latest plugin update and get notified when we update our plugin and launch new products for free!', AFRSM_PRO_TEXT_DOMAIN); ?></p>
        <p><input type="text" id="txt_user_sub_afrsm" class="regular-text" name="txt_user_sub_afrsm" value="<?php echo $current_user->user_email; ?>"></p>
    </div>
<?php } ?>
<!--Subscribe Newsletter Form End-->

<div class="afrsm-section-left">
    <div class="afrsm-main-table res-cl">
        <h2><?php _e('Thanks For Installing Advanced Flat Rate Shipping For WooCommerce', AFRSM_PRO_TEXT_DOMAIN); ?></h2>
        <table class="table-outer">
            <tbody>
                <tr>
                    <td class="fr-2">
                        <p class="block gettingstarted"><strong><?php _e('Getting Started', AFRSM_PRO_TEXT_DOMAIN); ?></strong></p>
                        <p class="block textgetting"><?php _e('Create/manage multiple shipping rules as per your needs.', AFRSM_PRO_TEXT_DOMAIN); ?></p>					
                        <p class="block textgetting">
                            <strong><?php _e('Step 1:', AFRSM_PRO_TEXT_DOMAIN); ?></strong> <?php _e('Setup Shipping Method Configuration with Shipping Method Rules', AFRSM_PRO_TEXT_DOMAIN); ?>
                            <span class="gettingstarted">
                                <img src="<?php echo AFRSM_PRO_PLUGIN_URL . 'admin/images/Getting_Started_01.png'; ?>">										
                            </span>
                        </p>
                        <p class="block gettingstarted textgetting">
                            <strong><?php _e('Step 2:', AFRSM_PRO_TEXT_DOMAIN); ?></strong> <?php _e('You can see list of all shipping methods.', AFRSM_PRO_TEXT_DOMAIN); ?>
                            <span class="gettingstarted">
                                <img src="<?php echo AFRSM_PRO_PLUGIN_URL . 'admin/images/Getting_Started_02.png'; ?>">
                            </span>
                        </p>
                        <p class="block gettingstarted textgetting">
                            <strong><?php _e('Step 3:', AFRSM_PRO_TEXT_DOMAIN); ?></strong> <?php _e('Enable shipping method on the cart/checkout page if rule is satisfied', AFRSM_PRO_TEXT_DOMAIN); ?>
                            <span class="gettingstarted">
                                <img src="<?php echo AFRSM_PRO_PLUGIN_URL . 'admin/images/Getting_Started_03.png'; ?>">
                            </span>
                        </p>
                        <p class="block gettingstarted textgetting"><strong><?php _e('Important Note: ', AFRSM_PRO_TEXT_DOMAIN); ?></strong><?php _e('This plugin is only compatible with WooCommerce version 2.4.0 and more', AFRSM_PRO_TEXT_DOMAIN); ?></p>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<?php require_once('header/plugin-sidebar.php'); 