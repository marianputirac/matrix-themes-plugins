<?php
// If this file is called directly, abort.
if (!defined('ABSPATH')) {
    exit;
}

require_once('header/plugin-header.php');
?>

<div class="afrsm-section-left">
    <div class="advance_zone_listing">
        <div class="right_button_add_zone">
            <a href="<?php echo admin_url('/admin.php?page=wc-shipping-zones&add_zone'); ?>" class="button-primary"><?php _e('Add Shipping Zone', AFRSM_PRO_TEXT_DOMAIN); ?></a>
        </div>
        <div class="wc-col-wrap">
            <?php self::list_shipping_zones(); ?>
        </div>
    </div>
</div>

<?php require_once('header/plugin-sidebar.php');