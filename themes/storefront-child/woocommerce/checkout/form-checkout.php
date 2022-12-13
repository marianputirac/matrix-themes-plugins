<?php
/**
 * Checkout Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-checkout.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see        https://docs.woocommerce.com/document/template-structure/
 * @author        WooThemes
 * @package    WooCommerce/Templates
 * @version     3.5.0
 */

if (!defined('ABSPATH')) {
    exit;
}

wc_print_notices();
?>

<?php
$j = 0;
$i = 0;
$atributes = get_post_meta(1, 'attributes_array', true);
$user_id = get_current_user_id();

$first_element_cart = reset(WC()->cart->get_cart());
$product_id = $first_element_cart['product_id'];
$term_list = wp_get_post_terms($product_id, 'product_cat', array("fields" => "all"));
$fob_components = false;
if ($term_list[0]->slug == 'components-fob') {
    $fob_components = true;
}

$nr_code_prod = array();
foreach (WC()->cart->get_cart() as $item_id => $item_data) {
    $i++;
    // $product = $item_data->get_product();
    $product_id = $item_data['product_id'];

    $nr_t = get_post_meta($product_id, 'counter_t', true);
    $nr_b = get_post_meta($product_id, 'counter_b', true);
    $nr_c = get_post_meta($product_id, 'counter_c', true);
    $nr_g = get_post_meta($product_id, 'counter_g', true);

    if (!empty($nr_code_prod)) {
        if ($nr_code_prod['t'] < $nr_t) {
            $nr_code_prod['t'] = $nr_t;
        }
        if ($nr_code_prod['g'] < $nr_g) {
            $nr_code_prod['g'] = $nr_g;
        }
        if ($nr_code_prod['b'] < $nr_b) {
            $nr_code_prod['b'] = $nr_b;
        }
        if ($nr_code_prod['c'] < $nr_c) {
            $nr_code_prod['c'] = $nr_c;
        }
    } else {
        $nr_code_prod['g'] = $nr_g;
        $nr_code_prod['t'] = $nr_t;
        $nr_code_prod['b'] = $nr_b;
        $nr_code_prod['c'] = $nr_c;
    }

}

?>

<div class="alert alert-warning">
    <?php echo get_post_meta(1, 'notification_users_message', true); ?>
</div>

<br>

<?php

/* ***********************************************
//  Show POS order items
*********************************************** */

$mystring = get_the_title();
$findme = '-pos';
$findmeCompoenent = 'component';
$pos = strpos($mystring, $findme);

if (strpos($mystring, $findme) !== false) { ?>

    <a href="/order-pos/" class="btn btn-primary blue"> Order POS</a>
    <br>

    <div class="order-summary-table" style="overflow: auto;">
        <table id="example" style="width:100%" class="table table-striped">
            <thead>
            <tr>
                <!-- <th>item</th> -->

                <th>Item</th>
                <th></th>
                <th></th>
                <th></th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Total</th>
                <th></th>

            </tr>
            </thead>
            <tbody>

            <?php

            $array_att = array();
            foreach (WC()->cart->get_cart() as $item_id => $item_data) {

                $j++;
                //$product = $item_data->get_product();
                $_product = apply_filters('woocommerce_cart_item_product', $item_data['data'], $item_data, $item_id);
                //print_r($item_id);
                $product_id = $item_data['product_id'];

                $attribute_pa_colors = $item_data['variation']['attribute_pa_colors'];
                $attribute_pa_covered = $item_data['variation']['attribute_pa_covered'];

                $price = get_post_meta($product_id, '_price', true);
                $regular_price = get_post_meta($product_id, '_regular_price', true);
                $sale_price = get_post_meta($product_id, '_sale_price', true);

                $term_list = wp_get_post_terms($product_id, 'product_cat', array("fields" => "all"));

                if ($term_list[0]->slug == 'pos') {
                    ?>
                    <tr>
                        <td>
                            <a href="<?php the_permalink($product_id); ?>">
                                <?php echo get_the_title($product_id); ?>
                            </a>
                        </td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td><?php echo $fob_components ? '$' : '£'; ?>
                            <?php echo number_format($price, 2); ?>
                        </td>
                        <td><?php echo $item_data['quantity']; ?></td>
                        <td><?php echo $fob_components ? '$' : '£'; ?>
                            <?php echo number_format($price, 2); ?>
                        </td>
                        <td><?php echo apply_filters('woocommerce_cart_item_remove_link', sprintf(
                                '<a href="%s" class=" btn btn-danger" aria-label="%s" data-product_id="%s" data-product_sku="%s">Delete</a>',
                                esc_url(wc_get_cart_remove_url($item_id)),
                                __('Remove this item', 'woocommerce'),
                                esc_attr($product_id),
                                esc_attr($_product->get_sku())
                            ), $item_id);

                            ?></td>
                    </tr>
                    <?php
                }
            }
            ?>

            </tbody>
        </table>
    </div>
    <?php
} elseif (strpos($mystring, $findmeCompoenent) !== false) {

    $first_element_cart = reset(WC()->cart->get_cart());

    $product_id = $first_element_cart['product_id'];
    $term_list = wp_get_post_terms($product_id, 'product_cat', array("fields" => "all"));

    $user = wp_get_current_user();
    $roles = $user->roles;
    if (in_array('china_admin', $roles)) {
        // echo '<a href="/order-components-fob/" class="btn btn-primary blue"> Order Components - FOB</a>';
        echo '<a href="/order-components/" class="btn btn-primary blue"> Order Components UK</a>';
    } else {
        if ($term_list[0]->slug == 'components') {
            echo '<a href="/order-components/" class="btn btn-primary blue"> Order Components UK</a>';
            // echo '<a href="/order-components/" class="btn btn-primary blue"> Order Components FOB</a>';
        } elseif ($term_list[0]->slug == 'components-fob') {
            echo '<a href="/order-components-fob/" class="btn btn-primary blue"> Order Components - FOB</a>';
        } else {
            // echo '<a href="/order-components-fob/" class="btn btn-primary blue"> Order Components - FOB</a>';
            echo '<a href="/order-components/" class="btn btn-primary blue"> Order Components UK</a>';
            echo '<a href="/order-components-fob/" class="btn btn-primary blue"> Order Components FOB</a>';
        }
    }

    ?>


    <br>

    <div class="order-summary-table" style="overflow: auto;">
        <table id="example" style="width:100%" class="table table-striped">
            <thead>
            <tr>
                <!-- <th>item</th> -->

                <th>Item</th>
                <th></th>
                <th></th>
                <th></th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Total</th>
                <th></th>

            </tr>
            </thead>
            <tbody>

            <?php

            $array_att = array();
            //            echo '<pre>';
            //            print_r(reset(WC()->cart->get_cart()));
            //            echo '</pre>';

            foreach (WC()->cart->get_cart() as $item_id => $item_data) {

                $j++;

                // print_r($item_data);
                //$product = $item_data->get_product();
                $_product = apply_filters('woocommerce_cart_item_product', $item_data['data'], $item_data, $item_id);
//                echo '<pre>';
//                print_r($item_data['data']);
//                echo '</pre>';
                $attribute_pa_colors = $item_data['variation']['attribute_pa_colors'];
                $attribute_pa_covered = $item_data['variation']['attribute_pa_covered'];
                $product_id = $item_data['product_id'];

                $price = $item_data['data']->get_price();
                $regular_price = get_post_meta($product_id, '_regular_price', true);
                $sale_price = get_post_meta($product_id, '_sale_price', true);

                $term_list = wp_get_post_terms($product_id, 'product_cat', array("fields" => "all"));

                if ($term_list[0]->slug == 'components' || $term_list[0]->slug == 'components-fob') {
                    ?>
                    <tr>
                        <td>
                            <a href="<?php the_permalink($product_id); ?>">
                                <strong><?php echo get_the_title($product_id); ?></strong>
                            </a>
                        </td>
                        <td><?php
                            if (!empty($attribute_pa_colors)) echo '<strong>Color:</strong> ' . $attribute_pa_colors . '<br>';
                            if (!empty($attribute_pa_covered)) echo '<strong>Covered:</strong> ' . $attribute_pa_covered . '<br>';
                            if (!empty($item_data['comp_note'])) echo '<strong>Your Note:</strong> ' . $item_data['comp_note'];
                            ?>
                        </td>
                        <td></td>
                        <td></td>
                        <td><?php echo $fob_components ? '$' : '£'; ?>
                            <?php echo number_format($price, 2); ?>
                        </td>
                        <td><?php echo $item_data['quantity']; ?></td>
                        <td><?php echo $fob_components ? '$' : '£'; ?>
                            <?php echo number_format($price * $item_data['quantity'], 2); ?>
                        </td>
                        <td><?php echo apply_filters('woocommerce_cart_item_remove_link', sprintf(
                                '<a href="%s" class=" btn btn-danger" aria-label="%s" data-product_id="%s" data-product_sku="%s">Delete</a>',
                                esc_url(wc_get_cart_remove_url($item_id)),
                                __('Remove this item', 'woocommerce'),
                                esc_attr($product_id),
                                esc_attr($_product->get_sku())
                            ), $item_id);
                            ?></td>
                    </tr>
                    <?php
                }
            }
            ?>

            </tbody>
        </table>
    </div>
    <?php
} else {
    /* ***********************************************
    //  Show Shutters order items
    *********************************************** */
    ?>

    <a href="/prod1-all/" class="btn btn-primary blue"> + Add New Shutter</a>
    <a href="/prod-individual/" class="btn btn-primary blue"> + Add Individual Bay Shutter</a>
    <a href="/prod2-all/" class="btn btn-primary blue"> + Add New Shutter & Blackout Blind</a>
    <a href="/prod5/" class="btn btn-primary blue"> + Add Batten</a>
    <br>

    <div class="order-summary-table" style="overflow: auto;">
        <?php
        /*
         * Change duplicated quantity items in custom quantity set in shutter module by post meta
         */
        global $woocommerce;
        foreach ($woocommerce->cart->get_cart() as $cart_item_key => $cart_item) {
            $custom_qty = get_post_meta($cart_item['product_id'], 'quantity', true);
            $woocommerce->cart->set_quantity($cart_item_key, $custom_qty);
        }

        echo do_shortcode('[table_items_shc editable="true" admin="false" table_id="example" table_class="table table-striped" ]');
        ?>
    </div>

    <?php

}

?>

<br>

<div class="alert alert-danger hide">
    <strong>Place order canceled!</strong>
</div>

<a href="/" class="btn btn-primary transparent"> Save for Later </a>
<!--button class="btn btn-primary blue download"> Create CSV </button>
< <a href="/checkout/" class="btn brn-default"> Continue </a> -->

<div class="cart-collaterals">
    <?php
    /**
     * Cart collaterals hook.
     *
     * @hooked woocommerce_cross_sell_display
     * @hooked woocommerce_cart_totals - 10
     */
    do_action('woocommerce_cart_collaterals');
    ?>
</div>

<?php do_action('woocommerce_after_cart'); ?>

<script>
    function download_csv(csv, filename) {
        var csvFile;
        var downloadLink;

        // CSV FILE
        csvFile = new Blob([csv], {
            type: "text/csv"
        });

        // Download link
        downloadLink = document.createElement("a");

        // File name
        downloadLink.download = filename;

        // We have to create a link to the file
        downloadLink.href = window.URL.createObjectURL(csvFile);

        //console.log(downloadLink.href);
        //jQuery('.url_down').attr('href',downloadLink.href);
        // Make sure that the link is not displayed
        downloadLink.style.display = "none";

        // Add the link to your DOM
        document.body.appendChild(downloadLink);

        // Lanzamos
        downloadLink.click();
    }

    function export_table_to_csv(html, filename) {
        var csv = [];
        var rows = document.querySelectorAll("table#example tr");

        for (var i = 0; i < rows.length; i++) {
            var row = [],
                cols = rows[i].querySelectorAll("td, th");

            for (var j = 0; j < cols.length; j++)
                row.push(cols[j].innerText);

            csv.push(row.join(","));
        }

        // Download CSV
        download_csv(csv.join("\n"), filename);

        console.log(csv);
        console.log(filename);
    }


    document.querySelector("button.download").addEventListener("click", function (e) {
        e.preventDefault();
        var html = document.querySelector("table#example").outerHTML;
        export_table_to_csv(html, "table-order.csv");

        //console.log(html);

    });
</script>

<form name="checkout" method="post" class="checkout woocommerce-checkout"
      action="<?php echo esc_url(wc_get_checkout_url()); ?>" enctype="multipart/form-data">

    <?php if ($checkout->get_checkout_fields()) : ?>

        <?php do_action('woocommerce_checkout_before_customer_details'); ?>

        <div class="col2-set" id="customer_details">
            <div class="col-1">
                <?php do_action('woocommerce_checkout_billing'); ?>
            </div>

            <div class="col-2">
                <?php do_action('woocommerce_checkout_shipping'); ?>
            </div>
        </div>

        <?php do_action('woocommerce_checkout_after_customer_details'); ?>

    <?php endif; ?>


    <?php do_action('woocommerce_checkout_before_order_review'); ?>

    <div id="order_review" class="woocommerce-checkout-review-order">
        <?php do_action('woocommerce_checkout_order_review'); ?>
    </div>

    <?php do_action('woocommerce_checkout_after_order_review'); ?>

</form>

<?php do_action('woocommerce_after_checkout_form', $checkout); ?>

<script>
    // jQuery('.woocommerce-checkout-review-order-table .tax-total .woocommerce-Price-currencySymbol').hide();
    jQuery(document).ready(function () {
        // jQuery('.woocommerce-checkout-review-order-table .tax-total .woocommerce-Price-currencySymbol').hide();
        setTimeout(function () {
            var totalSqm = jQuery('#totalSqm').text();
            var findme = "$";
            console.log(totalSqm);
            if (totalSqm.indexOf(findme) > -1) {
                console.log("found it");
                var modif_tax = jQuery('.woocommerce-checkout-review-order-table .tax-total .woocommerce-Price-currencySymbol').text();
                console.log(modif_tax);
                jQuery('.woocommerce-checkout-review-order-table .tax-total .woocommerce-Price-currencySymbol').text(modif_tax.replace('£', '$'));
                // jQuery('.woocommerce-checkout-review-order-table .tax-total .woocommerce-Price-currencySymbol').show();
            }
            else {
                // jQuery('.woocommerce-checkout-review-order-table .tax-total .woocommerce-Price-currencySymbol').show();
            }
        }, 1500);
    });

</script>