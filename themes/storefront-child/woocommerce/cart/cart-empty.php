<?php
/**
 * Empty cart page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart-empty.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see        https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 3.5.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

wc_print_notices();

/**
 * @hooked wc_empty_cart_message - 10
 */
do_action('woocommerce_cart_is_empty');

if (wc_get_page_id('shop') > 0) : ?>

<?php
$mystring = get_the_title();
$findmePos = '-pos';
$findmeCompoenent = 'component';
$pos = strpos($mystring, $findme);

if (strpos($mystring, $findmePos) !== false) { ?>
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
            <th>Price</th>
            <th></th>
            <th>Quantity</th>
            <th>Total</th>
            <th></th>

        </tr>
        </thead>
        <tbody>
        </tbody>
    </table>


    <?php }
    elseif (strpos($mystring, $findmeCompoenent) !== false) {

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
                <th>Price</th>
                <th></th>
                <th>Quantity</th>
                <th>Total</th>
                <th></th>

            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>


        <?php }
        else {
            ?>

            <a href="/prod1-all/" class="btn btn-primary blue"> + Add New Shutter</a>
            <a href="/prod-individual/" class="btn btn-primary blue"> + Add Individual Bay Shutter</a>
            <a href="/prod2-all/" class="btn btn-primary blue"> + Add New Shutter & Blackout Blind</a>
            <a href="/prod5/" class="btn btn-primary blue"> + Add Batten</a>

            <br><br>

            <!-- <p class="return-to-shop">
		<a class="button wc-backward" href="/">
			<?php
            // _e( 'Return to Progress Orders', 'woocommerce' )
            ?>
		</a>
	</p> -->
            <?php
        }
        endif; ?>
