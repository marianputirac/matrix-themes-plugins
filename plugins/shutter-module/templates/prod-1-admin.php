<?php

if (!empty($_GET['cust_id'])) {
    echo $user_id = $_GET['cust_id'];
} else {
    $user_id = get_current_user_id();
}

$meta_key = 'wc_multiple_shipping_addresses';

global $wpdb;
if ($addresses = $wpdb->get_var($wpdb->prepare("SELECT meta_value FROM {$wpdb->usermeta} WHERE user_id = %d AND meta_key = %s", $user_id, $meta_key))) {
    $addresses = maybe_unserialize($addresses);
}

$addresses = $wpdb->get_results("SELECT meta_value FROM `wp_usermeta` WHERE user_id = $user_id AND meta_key = '_woocom_multisession'");

$userialize_data = unserialize($addresses[0]->meta_value);
$session_key = $userialize_data['customer_id'];

$session = $wpdb->get_results("SELECT session_value FROM `wp_woocommerce_sessions` WHERE `session_key` LIKE $session_key ");
//print_r($session);
$userialize_session = unserialize($session[0]->session_value);

$i = 1;
$carts_sort = $userialize_data['carts'];
ksort($carts_sort);
$total_elements = count($carts_sort) + 1;
$cart_name = '';

foreach ($carts_sort as $key => $carts) {

    if ($userialize_data['customer_id'] == $key) {

        $cart_name = $carts['name'];
    }
}

$item_id = $_GET['item_id'];
$product_id = $_GET['id'] / 1498765 / 33;
echo "ID-prod: " . $_GET['id'] / 1498765 / 33;
// echo "<br>";
$meta = get_post_meta($_GET['id'] / 1498765 / 33);
// echo "<pre>";
// echo "<pre>";
// echo print_r($meta);
// echo "</pre>";
$regular_price = get_post_meta($product_id, '_regular_price', true);
echo '  price: ' . number_format($regular_price, 2);

echo '  stile:  ' . get_post_meta($product_id, 'property_stile', true);

$property_style = get_post_meta($product_id, 'property_style', true);
$attachment = get_post_meta($product_id, 'attachment', true);
$property_frametype = get_post_meta($product_id, 'property_frametype', true);
$property_horizontaltpost = get_post_meta($product_id, 'property_horizontaltpost', true);
$property_stile = get_post_meta($product_id, 'property_stile', true);
$bay_post_type = get_post_meta($product_id, 'bay-post-type', true);

$material = get_post_meta($product_id, 'property_material', true);
$property_trackedtype = get_post_meta($product_id, 'property_trackedtype', true);

$g1 = get_post_meta($product_id, 'property_g1', true);
$g2 = get_post_meta($product_id, 'property_g2', true);
$g3 = get_post_meta($product_id, 'property_g3', true);
$g4 = get_post_meta($product_id, 'property_g4', true);
$g5 = get_post_meta($product_id, 'property_g5', true);
$g6 = get_post_meta($product_id, 'property_g6', true);
$g7 = get_post_meta($product_id, 'property_g7', true);
$g8 = get_post_meta($product_id, 'property_g8', true);
$g9 = get_post_meta($product_id, 'property_g9', true);
$g10 = get_post_meta($product_id, 'property_g10', true);
$g11 = get_post_meta($product_id, 'property_g11', true);
$g12 = get_post_meta($product_id, 'property_g12', true);
$g13 = get_post_meta($product_id, 'property_g13', true);
$g14 = get_post_meta($product_id, 'property_g14', true);
$g15 = get_post_meta($product_id, 'property_g15', true);

$t1 = get_post_meta($product_id, 'property_t1', true);
$t2 = get_post_meta($product_id, 'property_t2', true);
$t3 = get_post_meta($product_id, 'property_t3', true);
$t4 = get_post_meta($product_id, 'property_t4', true);
$t5 = get_post_meta($product_id, 'property_t5', true);
$t6 = get_post_meta($product_id, 'property_t6', true);
$t7 = get_post_meta($product_id, 'property_t7', true);
$t8 = get_post_meta($product_id, 'property_t8', true);
$t9 = get_post_meta($product_id, 'property_t9', true);
$t10 = get_post_meta($product_id, 'property_t10', true);
$t11 = get_post_meta($product_id, 'property_t11', true);
$t12 = get_post_meta($product_id, 'property_t12', true);
$t13 = get_post_meta($product_id, 'property_t13', true);
$t14 = get_post_meta($product_id, 'property_t14', true);
$t15 = get_post_meta($product_id, 'property_t15', true);

$c1 = get_post_meta($product_id, 'property_c1', true);
$c2 = get_post_meta($product_id, 'property_c2', true);
$c3 = get_post_meta($product_id, 'property_c3', true);
$c4 = get_post_meta($product_id, 'property_c4', true);
$c5 = get_post_meta($product_id, 'property_c5', true);
$c6 = get_post_meta($product_id, 'property_c6', true);
$c7 = get_post_meta($product_id, 'property_c7', true);
$c8 = get_post_meta($product_id, 'property_c8', true);
$c9 = get_post_meta($product_id, 'property_c9', true);
$c10 = get_post_meta($product_id, 'property_c10', true);
$c11 = get_post_meta($product_id, 'property_c11', true);
$c12 = get_post_meta($product_id, 'property_c12', true);
$c13 = get_post_meta($product_id, 'property_c13', true);
$c14 = get_post_meta($product_id, 'property_c14', true);
$c15 = get_post_meta($product_id, 'property_c15', true);

$bp1 = get_post_meta($product_id, 'property_bp1', true);
$bp2 = get_post_meta($product_id, 'property_bp2', true);
$bp3 = get_post_meta($product_id, 'property_bp3', true);
$bp4 = get_post_meta($product_id, 'property_bp4', true);
$bp5 = get_post_meta($product_id, 'property_bp5', true);
$bp6 = get_post_meta($product_id, 'property_bp6', true);
$bp7 = get_post_meta($product_id, 'property_bp7', true);
$bp8 = get_post_meta($product_id, 'property_bp8', true);
$bp9 = get_post_meta($product_id, 'property_bp9', true);
$bp10 = get_post_meta($product_id, 'property_bp10', true);
$bp11 = get_post_meta($product_id, 'property_bp12', true);
$bp12 = get_post_meta($product_id, 'property_bp13', true);
$bp14 = get_post_meta($product_id, 'property_bp14', true);
$bp15 = get_post_meta($product_id, 'property_bp15', true);

$ba1 = get_post_meta($product_id, 'property_ba1', true);
$ba2 = get_post_meta($product_id, 'property_ba2', true);
$ba3 = get_post_meta($product_id, 'property_ba3', true);
$ba4 = get_post_meta($product_id, 'property_ba4', true);
$ba5 = get_post_meta($product_id, 'property_ba5', true);
$ba6 = get_post_meta($product_id, 'property_ba6', true);
$ba7 = get_post_meta($product_id, 'property_ba7', true);
$ba8 = get_post_meta($product_id, 'property_ba8', true);
$ba9 = get_post_meta($product_id, 'property_ba9', true);
$ba10 = get_post_meta($product_id, 'property_ba10', true);
$ba11 = get_post_meta($product_id, 'property_ba11', true);
$ba12 = get_post_meta($product_id, 'property_ba12', true);
$ba13 = get_post_meta($product_id, 'property_ba13', true);
$ba14 = get_post_meta($product_id, 'property_ba14', true);
$ba15 = get_post_meta($product_id, 'property_ba15', true);

$nr_g = get_post_meta($product_id, 'counter_g', true);
$nr_t = get_post_meta($product_id, 'counter_t', true);
$nr_b = get_post_meta($product_id, 'counter_b', true);
$nr_c = get_post_meta($product_id, 'counter_c', true);

$nr_code_prod = array();

$nr_code_prod['g'] = $nr_g;
$nr_code_prod['t'] = $nr_t;
$nr_code_prod['b'] = $nr_b;
$nr_code_prod['c'] = $nr_c;

global $woocommerce;
$cart = WC()->cart->get_cart();

// foreach( $cart as $cart_item_key => $cart_item ) {
//     $products = $cart_item['data']->get_id();
//     //print_r($products);
//     //echo $cart_item['quantity'];
//     if( $products == $product_id ){
//       $quant = $cart_item['quantity'];
//     }
// }

echo 'QTY: ' . wc_get_order_item_meta($item_id, '_qty', true);
$quant = wc_get_order_item_meta($item_id, '_qty', true);
$cart_item = $woocommerce->cart->get_cart();
//print_r($cart_item);

?>

<!-- Latest compiled and minified CSS -->
<!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"> -->

<div class="main-content">
    <div class="page-content" style="background-color: #F7F7F7">
        <div class="page-content-area">
            <div class="page-header">
                <h1>Update Shutter - <?php echo $cart_name; ?></h1>
            </div>

            <input type="hidden" id="g1" name="g1" value="<?php echo $g1; ?>">
            <input type="hidden" id="g2" name="g2" value="<?php echo $g2; ?>">
            <input type="hidden" id="g3" name="g3" value="<?php echo $g3; ?>">
            <input type="hidden" id="g4" name="g4" value="<?php echo $g4; ?>">
            <input type="hidden" id="g5" name="g5" value="<?php echo $g5; ?>">
            <input type="hidden" id="g6" name="g6" value="<?php echo $g6; ?>">
            <input type="hidden" id="g7" name="g7" value="<?php echo $g7; ?>">
            <input type="hidden" id="g8" name="g8" value="<?php echo $g8; ?>">
            <input type="hidden" id="g9" name="g9" value="<?php echo $g9; ?>">
            <input type="hidden" id="g10" name="g10" value="<?php echo $g10; ?>">
            <input type="hidden" id="g11" name="g11" value="<?php echo $g11; ?>">
            <input type="hidden" id="g12" name="g12" value="<?php echo $g12; ?>">
            <input type="hidden" id="g13" name="g13" value="<?php echo $g13; ?>">
            <input type="hidden" id="g14" name="g14" value="<?php echo $g14; ?>">
            <input type="hidden" id="g15" name="g15" value="<?php echo $g15; ?>">

            <input type="hidden" id="t1" name="t1" value="<?php echo $t1; ?>">
            <input type="hidden" id="t2" name="t2" value="<?php echo $t2; ?>">
            <input type="hidden" id="t3" name="t3" value="<?php echo $t3; ?>">
            <input type="hidden" id="t4" name="t4" value="<?php echo $t4; ?>">
            <input type="hidden" id="t5" name="t5" value="<?php echo $t5; ?>">
            <input type="hidden" id="t6" name="t6" value="<?php echo $t6; ?>">
            <input type="hidden" id="t7" name="t7" value="<?php echo $t7; ?>">
            <input type="hidden" id="t8" name="t8" value="<?php echo $t8; ?>">
            <input type="hidden" id="t9" name="t9" value="<?php echo $t9; ?>">
            <input type="hidden" id="t10" name="t10" value="<?php echo $t10; ?>">
            <input type="hidden" id="t11" name="t11" value="<?php echo $t11; ?>">
            <input type="hidden" id="t12" name="t12" value="<?php echo $t12; ?>">
            <input type="hidden" id="t13" name="t13" value="<?php echo $t13; ?>">
            <input type="hidden" id="t14" name="t14" value="<?php echo $t14; ?>">
            <input type="hidden" id="t15" name="t15" value="<?php echo $t15; ?>">

            <input type="hidden" id="c1" name="c1" value="<?php echo $c1; ?>">
            <input type="hidden" id="c2" name="c2" value="<?php echo $c2; ?>">
            <input type="hidden" id="c3" name="c3" value="<?php echo $c3; ?>">
            <input type="hidden" id="c4" name="c4" value="<?php echo $c4; ?>">
            <input type="hidden" id="c5" name="c5" value="<?php echo $c5; ?>">
            <input type="hidden" id="c6" name="c6" value="<?php echo $c6; ?>">
            <input type="hidden" id="c7" name="c7" value="<?php echo $c7; ?>">
            <input type="hidden" id="c8" name="c8" value="<?php echo $c8; ?>">
            <input type="hidden" id="c9" name="c9" value="<?php echo $c9; ?>">
            <input type="hidden" id="c10" name="c10" value="<?php echo $c10; ?>">
            <input type="hidden" id="c11" name="c11" value="<?php echo $c11; ?>">
            <input type="hidden" id="c12" name="c12" value="<?php echo $c12; ?>">
            <input type="hidden" id="c13" name="c13" value="<?php echo $c13; ?>">
            <input type="hidden" id="c14" name="c14" value="<?php echo $c14; ?>">
            <input type="hidden" id="c15" name="c15" value="<?php echo $c15; ?>">

            <input type="hidden" id="ba1" name="ba1" value="<?php echo $ba1; ?>">
            <input type="hidden" id="ba2" name="ba2" value="<?php echo $ba2; ?>">
            <input type="hidden" id="ba3" name="ba3" value="<?php echo $ba3; ?>">
            <input type="hidden" id="ba4" name="ba4" value="<?php echo $ba4; ?>">
            <input type="hidden" id="ba5" name="ba5" value="<?php echo $ba5; ?>">
            <input type="hidden" id="ba6" name="ba6" value="<?php echo $ba6; ?>">
            <input type="hidden" id="ba7" name="ba7" value="<?php echo $ba7; ?>">
            <input type="hidden" id="ba8" name="ba8" value="<?php echo $ba8; ?>">
            <input type="hidden" id="ba9" name="ba9" value="<?php echo $ba9; ?>">
            <input type="hidden" id="ba10" name="ba10" value="<?php echo $ba10; ?>">
            <input type="hidden" id="ba11" name="ba11" value="<?php echo $ba11; ?>">
            <input type="hidden" id="ba12" name="ba12" value="<?php echo $ba12; ?>">
            <input type="hidden" id="ba13" name="ba13" value="<?php echo $ba13; ?>">
            <input type="hidden" id="ba14" name="ba14" value="<?php echo $ba14; ?>">
            <input type="hidden" id="ba15" name="ba15" value="<?php echo $ba15; ?>">

            <input type="hidden" id="bp1" name="bp1" value="<?php echo $bp1; ?>">
            <input type="hidden" id="bp2" name="bp2" value="<?php echo $bp2; ?>">
            <input type="hidden" id="bp3" name="bp3" value="<?php echo $bp3; ?>">
            <input type="hidden" id="bp4" name="bp4" value="<?php echo $bp4; ?>">
            <input type="hidden" id="bp5" name="bp5" value="<?php echo $bp5; ?>">
            <input type="hidden" id="bp6" name="bp6" value="<?php echo $bp6; ?>">
            <input type="hidden" id="bp7" name="bp7" value="<?php echo $bp7; ?>">
            <input type="hidden" id="bp8" name="bp8" value="<?php echo $bp8; ?>">
            <input type="hidden" id="bp9" name="bp9" value="<?php echo $bp9; ?>">
            <input type="hidden" id="bp10" name="bp10" value="<?php echo $bp10; ?>">
            <input type="hidden" id="bp11" name="bp11" value="<?php echo $bp11; ?>">
            <input type="hidden" id="bp12" name="bp12" value="<?php echo $bp12; ?>">
            <input type="hidden" id="bp13" name="bp13" value="<?php echo $bp13; ?>">
            <input type="hidden" id="bp14" name="bp14" value="<?php echo $bp14; ?>">
            <input type="hidden" id="bp15" name="bp15" value="<?php echo $bp15; ?>">

            <input type="hidden" id="property_frametype" name="property_frametype_hidden"
                value="<?php echo $property_frametype; ?>">

            <div class="row">
                <form edit="yes" accept-charset="UTF-8" action="/order_products/add_single_product" data-type="json"
                    enctype="multipart/form-data" id="add-product-single-form" method="post">
                    <div class="col-xs-12">
                        <div class="row">
                            <div class="col-sm-9">
                                <strong>Order Reference:</strong> <?php echo $cart_name; ?>
                                <br />
                                <br />
                            </div>
                            <div class="col-sm-3 pull-right">
                                Total Square Meters&nbsp;
                                <input class="input-small" id="property_total" name="property_total" type="text"
                                    value="" />
                            </div>
                        </div>

                        <div style="display:none">

                        </div>
                        <input type="hidden" name="product_id_updated" value="<?php echo $product_id; ?>">
                        <input type="hidden" name="customer_id" value="<?php echo $user_id; ?>">
                        <input type="hidden" id="property_frametype_hidden" name="property_frametype"
                            value="<?php echo $property_frametype; ?>">

                        <div class="row">
                            <div class="col-sm-12">
                                <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                                    <div class="panel panel-default">
                                        <div class="panel-heading" role="tab" id="headingStyle">
                                            <h4 class="panel-title">
                                                <a role="button" class="" data-toggle="collapse"
                                                    data-parent="#accordion" href="#collapseStyle" aria-expanded="true"
                                                    aria-controls="collapseStyle">
                                                    Shutters Design
                                                </a>
                                            </h4>
                                        </div>
                                        <div id="collapseStyle" class="panel-collapse collapse in" role="tabpanel"
                                            aria-labelledby="headingStyle" aria-expanded="true" style="">
                                            <div class="panel-body">
                                                <div class="row">
                                                    <div class="col-sm-3" style="display:none;">
                                                        Room:
                                                        <br />
                                                        <input class="property-select" id="property_room"
                                                            name="property_room" type="text" value="94" />
                                                    </div>
                                                    <div class="col-sm-3" id="room-other" style="display: block">
                                                        Room Name:
                                                        <br />
                                                        <input required class="input-medium required"
                                                            id="property_room_other" name="property_room_other"
                                                            style="height: 30px" type="text"
                                                            value="<?php echo get_post_meta($product_id, 'property_room_other', true); ?>" />
                                                    </div>
                                                    <div class="col-sm-3">
                                                        Material:
                                                        <br />
                                                        <input class="property-select" id="property_material"
                                                            name="property_material" type="text"
                                                            value="<?php echo get_post_meta($product_id, 'property_material', true); ?>" />
                                                        <input id="product_id" name="product_id" type="hidden"
                                                            value="" />
                                                    </div>
                                                </div>

                                                <div class="row" style="margin-top:1em;">
                                                    <div class="col-sm-12">
                                                        Installation Style:
                                                        <div id="choose-style">
                                                            <label>
                                                                <br /> Full Height
                                                                <br />
                                                                <input type="radio" name="property_style"
                                                                    data-code="fullheight" data-title="Full Height"
                                                                    value="29"
                                                                    <?php if ($property_style == '29') {
                                                                                                                                                                            echo "checked='checked'";
                                                                                                                                                                        } ?> />
                                                                <img
                                                                    src="/wp-content/plugins/shutter-module/imgs/Full-Height.png">
                                                            </label>
                                                            <label style="display:none;">
                                                                <br /> Tier-on-Tier
                                                                <br />
                                                                <input type="radio" name="property_style"
                                                                    data-code="tot" data-title="Tier-on-Tier" value="31"
                                                                    <?php if ($property_style == '31') {
                                                                                                                                                                    echo "checked='checked'";
                                                                                                                                                                } ?> />
                                                                <img
                                                                    src="/wp-content/plugins/shutter-module/imgs/Tier-On-Tier.png">
                                                            </label>
                                                            <label style="display:none;">
                                                                <br /> Café Style
                                                                <br />
                                                                <input type="radio" name="property_style"
                                                                    data-code="cafe" data-title="Café Style" value="30"
                                                                    <?php if ($property_style == '30') {
                                                                                                                                                                    echo "checked='checked'";
                                                                                                                                                                } ?> />
                                                                <img
                                                                    src="/wp-content/plugins/shutter-module/imgs/Cafe-Style.png">
                                                            </label>
                                                            <label style="display:none;">
                                                                <br /> Bay Window
                                                                <br />
                                                                <input type="radio" name="property_style"
                                                                    data-code="bay" data-title="Bay Window" value="32"
                                                                    <?php if ($property_style == '32') {
                                                                                                                                                                    echo "checked='checked'";
                                                                                                                                                                } ?> />
                                                                <img
                                                                    src="/wp-content/plugins/shutter-module/imgs/Bay-Window.png">
                                                            </label>
                                                            <label style="display:none;">
                                                                Bay Window<br />Tier-on-Tier
                                                                <br />
                                                                <input type="radio" name="property_style"
                                                                    data-code="bay-tot"
                                                                    data-title="Bay Window Tier-on-Tier" value="146"
                                                                    <?php if ($property_style == '146') {
                                                                                                                                                                                    echo "checked='checked'";
                                                                                                                                                                                } ?> />
                                                                <img
                                                                    src="/wp-content/plugins/shutter-module/imgs/Bay-Window-Tier-On-Tier.png">
                                                            </label>
                                                            <label style="display:none;">
                                                                Bay Window<br />Café Style
                                                                <br />
                                                                <input type="radio" name="property_style"
                                                                    data-code="cafe-bay"
                                                                    data-title="Café Style Bay Window" value="225"
                                                                    <?php if ($property_style == '225') {
                                                                                                                                                                                    echo "checked='checked'";
                                                                                                                                                                                } ?> />
                                                                <img
                                                                    src="/wp-content/plugins/shutter-module/imgs/Bay-Window-Cafe-Style.png">
                                                            </label>
                                                            <label>
                                                                <br /> Solid Flat Panel
                                                                <br />
                                                                <input type="radio" name="property_style"
                                                                    data-code="solid-flat" data-title="Solid Flat Panel"
                                                                    value="221"
                                                                    <?php if ($property_style == '221') {
                                                                                                                                                                                echo "checked='checked'";
                                                                                                                                                                            } ?> />
                                                                <img
                                                                    src="/wp-content/plugins/shutter-module/imgs/Solid-Flat-Panel.png">
                                                            </label>
                                                            <label style="display:none;">
                                                                Solid Flat<br />Tier-on-Tier
                                                                <br />
                                                                <input type="radio" name="property_style"
                                                                    data-code="solid-flat-tot"
                                                                    data-title="Solid Flat Tier-on-Tier" value="227"
                                                                    <?php if ($property_style == '227') {
                                                                                                                                                                                            echo "checked='checked'";
                                                                                                                                                                                        } ?> />
                                                                <img
                                                                    src="/wp-content/plugins/shutter-module/imgs/Solid-Flat-Panel-Tier-On-Tier.png">
                                                            </label>
                                                            <label style="display:none;">
                                                                Solid Raised<br />Panel
                                                                <br />
                                                                <input type="radio" name="property_style"
                                                                    data-code="solid-raised"
                                                                    data-title="Solid Raised Panel" value="222"
                                                                    <?php if ($property_style == '222') {
                                                                                                                                                                                    echo "checked='checked'";
                                                                                                                                                                                } ?> />
                                                                <img
                                                                    src="/wp-content/plugins/shutter-module/imgs/Solid-Raised-Panel.png">
                                                            </label>
                                                            <label style="display:none;">
                                                                Solid Raised<br />Tier-on-Tier
                                                                <br />
                                                                <input type="radio" name="property_style"
                                                                    data-code="solid-raised-tot"
                                                                    data-title="Solid Raised Tier-on-Tier" value="228"
                                                                    <?php if ($property_style == '228') {
                                                                                                                                                                                                echo "checked='checked'";
                                                                                                                                                                                            } ?> />
                                                                <img
                                                                    src="/wp-content/plugins/shutter-module/imgs/Solid-Rised-Panel-Tier-On-Tier.png">
                                                            </label>
                                                            <label>
                                                                <br /> Solid Combi Panel
                                                                <br />
                                                                <input type="radio" name="property_style"
                                                                    data-code="solid-combi" data-title="Combi Panel"
                                                                    value="229"
                                                                    <?php if ($property_style == '229') {
                                                                                                                                                                            echo "checked='checked'";
                                                                                                                                                                        } ?> />
                                                                <img
                                                                    src="/wp-content/plugins/shutter-module/imgs/Solid-Combi-Panel.png">
                                                            </label>
                                                            <label style="display:none;">
                                                                Solid Raised<br />Café Style
                                                                <br />
                                                                <input type="radio" name="property_style"
                                                                    data-code="solid-raised-cafe-style"
                                                                    data-title="Solid Raised Café Style" value="226"
                                                                    <?php if ($property_style == '226') {
                                                                                                                                                                                                    echo "checked='checked'";
                                                                                                                                                                                                } ?> />
                                                                <img
                                                                    src="/wp-content/plugins/shutter-module/imgs/Solid-Raised-Panel-Cafe-Style.png">
                                                            </label>
                                                            <label style="display:none;">
                                                                <br /> Arched Shaped
                                                                <br />
                                                                <input type="radio" name="property_style"
                                                                    data-code="shaped" data-title="Arched Shaped"
                                                                    value="36"
                                                                    <?php if ($property_style == '36') {
                                                                                                                                                                        echo "checked='checked'";
                                                                                                                                                                    } ?> />
                                                                <img
                                                                    src="/wp-content/plugins/shutter-module/imgs/Arched.png">
                                                            </label>
                                                            <label style="display:none;">
                                                                <br /> Special Shaped
                                                                <br />
                                                                <input type="radio" name="property_style"
                                                                    data-code="shaped" data-title="Special Shaped"
                                                                    value="33"
                                                                    <?php if ($property_style == '33') {
                                                                                                                                                                        echo "checked='checked'";
                                                                                                                                                                    } ?> />
                                                                <img
                                                                    src="/wp-content/plugins/shutter-module/imgs/Special-Shape.png">
                                                            </label>
                                                            <label style="display:none;">
                                                                <br /> French Door Cut
                                                                <br />
                                                                <input type="radio" name="property_style"
                                                                    data-code="french" data-title="French Door Cut"
                                                                    value="34"
                                                                    <?php if ($property_style == '34') {
                                                                                                                                                                            echo "checked='checked'";
                                                                                                                                                                        } ?> />
                                                                <img
                                                                    src="/wp-content/plugins/shutter-module/imgs/French-Door-Cut.png">
                                                            </label>
                                                            <label style="display:none;">
                                                                <br /> Tracked Bifold
                                                                <br />
                                                                <input type="radio" name="property_style"
                                                                    data-code="tracked" data-title="Tracked" value="35"
                                                                    <?php if ($property_style == '35') {
                                                                                                                                                                    echo "checked='checked'";
                                                                                                                                                                } ?> />
                                                                <img
                                                                    src="/wp-content/plugins/shutter-module/imgs/BiFold-Tracked.png">
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-5" id="shape-section" style="display: block">
                                                        Shape: (max size 8MB)
                                                        <br />

                                                        <div id="shape-upload-container">
                                                            <span id="provided-shape">
                                                            </span>
                                                            <!-- <input id="attachment" name="wp_custom_attachment" type="file" /> -->
                                                            <input id="frontend-button" type="button"
                                                                value="Select File to Upload" class="button"
                                                                style="position: relative; z-index: 1;">
                                                            <img id="frontend-image" src="<?php echo $attachment; ?>" />
                                                            <input type="text" id="attachment" name="attachment"
                                                                style="visibility: hidden;"
                                                                value="<?php if ($attachment) {
                                                                                                                                                        echo $attachment;
                                                                                                                                                    } ?>" />
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row" style="margin-top:1em;">
                                                    <div class="col-sm-2">
                                                        Width (mm):
                                                        <br />
                                                        <input class="required number input-medium" id="property_width"
                                                            name="property_width" type="text"
                                                            value="<?php echo get_post_meta($product_id, 'property_width', true); ?>" />
                                                    </div>
                                                    <div class="col-sm-2">
                                                        Height (mm):
                                                        <br />
                                                        <input class="required number input-medium" id="property_height"
                                                            name="property_height" type="text"
                                                            value="<?php echo get_post_meta($product_id, 'property_height', true); ?>" />
                                                    </div>

                                                    <div class="col-sm-2" id="solid-panel-height" style="display:none;">
                                                        Solid Panel Height (mm):
                                                        <br />
                                                        <input class=" number input-medium"
                                                            id="property_solidpanelheight"
                                                            name="property_solidpanelheight" type="text"
                                                            value="<?php echo get_post_meta($product_id, 'property_solidpanelheight', true); ?>" />
                                                    </div>

                                                    <div class="col-sm-2" id="midrail-height">
                                                        Midrail Height (mm):
                                                        <br />
                                                        <input class="number input-medium" id="property_midrailheight"
                                                            name="property_midrailheight" type="text"
                                                            value="<?php echo get_post_meta($product_id, 'property_midrailheight', true); ?>" />
                                                    </div>
                                                    <div class="col-sm-2" id="midrail-height2">
                                                        Midrail Height 2 (mm):
                                                        <br />
                                                        <input class=" number input-medium" id="property_midrailheight2"
                                                            name="property_midrailheight2" type="text"
                                                            value="<?php echo get_post_meta($product_id, 'property_midrailheight2', true); ?>" />
                                                    </div>
                                                    <div class="col-sm-2" id="midrail-divider">
                                                        Hidden Divider 1 (mm):
                                                        <br />
                                                        <input class=" number input-medium"
                                                            id="property_midraildivider1"
                                                            name="property_midraildivider1" type="text"
                                                            value="<?php echo get_post_meta($product_id, 'property_midraildivider1', true); ?>" />
                                                    </div>
                                                    <div class="col-sm-2" id="midrail-divider2">
                                                        Hidden Divider 2 (mm):
                                                        <br />
                                                        <input class=" number input-medium"
                                                            id="property_midraildivider2"
                                                            name="property_midraildivider2" type="text"
                                                            value="<?php echo get_post_meta($product_id, 'property_midraildivider2', true); ?>" />
                                                    </div>
                                                    <div class="col-sm-2" id="midrail-position-critical">
                                                        Position is Critical:
                                                        <br />
                                                        <div class="input-group-container">
                                                            <div class="input-group">

                                                                <input class="property-select"
                                                                    id="property_midrailpositioncritical"
                                                                    name="property_midrailpositioncritical" type="text"
                                                                    value="<?php echo get_post_meta($product_id, 'property_midrailpositioncritical', true); ?>" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-2 tot-height" style="display: none">
                                                        T-o-T Height (mm):
                                                        <br />
                                                        <input class="required number input-medium"
                                                            id="property_totheight" name="property_totheight"
                                                            type="text"
                                                            value="<?php echo get_post_meta($product_id, 'property_totheight', true); ?>" />
                                                    </div>
                                                    <div class="col-sm-2 tot-height horizontal-t-post"
                                                        style="display: none" ata-toggle="tooltip"
                                                        title="The horizontal T Post will be apllied at T-o-T height">
                                                        Horizontal T Post
                                                        <br />
                                                        <input id="property_horizontaltpost"
                                                            name="property_horizontaltpost" type="checkbox" value="Yes"
                                                            <?php if ($property_horizontaltpost == 'Yes') {
                                                                                                                                                                echo "checked='checked'";
                                                                                                                                                            } ?> />
                                                    </div>
                                                    <div class="col-sm-2">
                                                        Louvre Size:
                                                        <br />
                                                        <input class="property-select required bladesize_porperty"
                                                            id="property_bladesize" name="property_bladesize"
                                                            type="text"
                                                            value="<?php echo get_post_meta($product_id, 'property_bladesize', true); ?>" />
                                                    </div>
                                                    <div id="trackedtype" class="col-sm-2" style="display: none">
                                                        Track Installation type:
                                                        <br />
                                                        <input name="property_trackedtype" type="radio" <?php
                                                                                                        if ($property_trackedtype == 'inside mount') echo 'checked';
                                                                                                        ?>
                                                            value="inside mount" />
                                                        Inside mount
                                                        <br />
                                                        <input name="property_trackedtype" type="radio" <?php
                                                                                                        if ($property_trackedtype == 'outside mount') echo 'checked';
                                                                                                        ?>
                                                            value="outside mount" />
                                                        outside mount
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-sm-3 property_fit top10">
                                                        Fit:
                                                        <br />
                                                        <div class="input-group-container">
                                                            <div class="input-group">
                                                                <span class="input-group-addon"
                                                                    title="IMPORTANT! Default should be Outside. If you are unsure please check specification sheet under Downloads. "
                                                                    data-toggle="tooltip" data-placement="top">?</span>
                                                                <input class="property-select" id="property_fit"
                                                                    name="property_fit" type="text"
                                                                    value="<?php echo get_post_meta($product_id, 'property_fit', true); ?>" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <hr />
                                                        <button class="btn btn-info show-next-panel">
                                                            Next
                                                            <i class="fa fa-chevron-right"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="panel panel-default">
                                        <div class="panel-heading" role="tab" id="headingOne">
                                            <h4 class="panel-title">
                                                <a role="button" class="" data-toggle="collapse"
                                                    data-parent="#accordion" href="#collapseOne" aria-expanded="true"
                                                    aria-controls="collapseOne">
                                                    Frame &amp; Stile Design
                                                </a>
                                            </h4>
                                        </div>
                                        <div id="collapseOne" class="panel-collapse collapse " role="tabpanel"
                                            aria-labelledby="headingOne">
                                            <div class="panel-body">
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        Frame Type:
                                                        <p id="required-choices-frametype">
                                                            <i>Please select Material &amp; Style in order to view
                                                                available Frame Type choices</i>
                                                        </p>
                                                        <div id="choose-frametype">
                                                            <label>
                                                                <br /> 4008A
                                                                <input type="radio" name="property_frametype"
                                                                    data-code="F50" data-title="4008A" value="307"
                                                                    <?php if (
                                                                                                                                                                    $property_frametype == '307'
                                                                                                                                                                ) {
                                                                                                                                                                    echo "checked='checked'";
                                                                                                                                                                } ?> />
                                                                <img
                                                                    src="/wp-content/plugins/shutter-module/imgs/4008A.png">
                                                            </label>
                                                            <label>Basswood
                                                                <br /> 4008B
                                                                <input type="radio" name="property_frametype"
                                                                    data-code="F70" data-title="4008B" value="310"
                                                                    <?php if (
                                                                                                                                                                    $property_frametype == '310'
                                                                                                                                                                ) {
                                                                                                                                                                    echo "checked='checked'";
                                                                                                                                                                } ?> />
                                                                <img
                                                                    src="/wp-content/plugins/shutter-module/imgs/4008B.png">
                                                            </label>
                                                            <label>
                                                                <br /> 4008C
                                                                <input type="radio" name="property_frametype"
                                                                    data-code="F70" data-title="4008C" value="313"
                                                                    <?php if (
                                                                                                                                                                    $property_frametype == '313'
                                                                                                                                                                ) {
                                                                                                                                                                    echo "checked='checked'";
                                                                                                                                                                } ?> />
                                                                <img
                                                                    src="/wp-content/plugins/shutter-module/imgs/4008C.png">
                                                            </label>
                                                            <label>
                                                                <br /> 4008T
                                                                <input type="radio" name="property_frametype"
                                                                    data-code="F70" data-title="4008T" value="353"
                                                                    <?php if (
                                                                                                                                                                    $property_frametype == '353'
                                                                                                                                                                ) {
                                                                                                                                                                    echo "checked='checked'";
                                                                                                                                                                } ?> />
                                                                <img
                                                                    src="/wp-content/plugins/shutter-module/imgs/4008T.png">
                                                            </label>
                                                            <label>Basswood
                                                                <br /> 4028B
                                                                <input type="radio" name="property_frametype"
                                                                    data-code="F70" data-title="4028B" value="333"
                                                                    <?php if (
                                                                                                                                                                    $property_frametype == '333'
                                                                                                                                                                ) {
                                                                                                                                                                    echo "checked='checked'";
                                                                                                                                                                } ?> />
                                                                <img
                                                                    src="/wp-content/plugins/shutter-module/imgs/4028B.png">
                                                            </label>
                                                            <label>
                                                                <br /> 4007A
                                                                <input type="radio" name="property_frametype"
                                                                    data-code="L70" data-title="4007A" value="306"
                                                                    <?php if (
                                                                                                                                                                    $property_frametype == '306'
                                                                                                                                                                ) {
                                                                                                                                                                    echo "checked='checked'";
                                                                                                                                                                } ?> />
                                                                <img
                                                                    src="/wp-content/plugins/shutter-module/imgs/4007A.png">
                                                            </label>
                                                            <label>
                                                                <br /> 4007B
                                                                <input type="radio" name="property_frametype"
                                                                    data-code="L90" data-title="4007B" value="309"
                                                                    <?php if (
                                                                                                                                                                    $property_frametype == '309'
                                                                                                                                                                ) {
                                                                                                                                                                    echo "checked='checked'";
                                                                                                                                                                } ?> />
                                                                <img
                                                                    src="/wp-content/plugins/shutter-module/imgs/4007B.png">
                                                            </label>
                                                            <label>
                                                                <br /> 4007C
                                                                <input type="radio" name="property_frametype"
                                                                    data-code="L90" data-title="4007C" value="312"
                                                                    <?php if (
                                                                                                                                                                    $property_frametype == '312'
                                                                                                                                                                ) {
                                                                                                                                                                    echo "checked='checked'";
                                                                                                                                                                } ?> />
                                                                <img
                                                                    src="/wp-content/plugins/shutter-module/imgs/4007C.png">
                                                            </label>
                                                            <label>
                                                                <br /> 4001A
                                                                <input type="radio" name="property_frametype"
                                                                    data-code="L50" data-title="4001A" value="305"
                                                                    <?php if (
                                                                                                                                                                    $property_frametype == '305'
                                                                                                                                                                ) {
                                                                                                                                                                    echo "checked='checked'";
                                                                                                                                                                } ?> />
                                                                <img
                                                                    src="/wp-content/plugins/shutter-module/imgs/4001A.png">
                                                            </label>
                                                            <label>
                                                                <br /> 4001B
                                                                <input type="radio" name="property_frametype"
                                                                    data-code="L70" data-title="4001B" value="308"
                                                                    <?php if (
                                                                                                                                                                    $property_frametype == '308'
                                                                                                                                                                ) {
                                                                                                                                                                    echo "checked='checked'";
                                                                                                                                                                } ?> />
                                                                <img
                                                                    src="/wp-content/plugins/shutter-module/imgs/4001B.png">
                                                            </label>
                                                            <label>
                                                                <br /> 4001C
                                                                <input type="radio" name="property_frametype"
                                                                    data-code="L70" data-title="4001C" value="311"
                                                                    <?php if (
                                                                                                                                                                    $property_frametype == '311'
                                                                                                                                                                ) {
                                                                                                                                                                    echo "checked='checked'";
                                                                                                                                                                } ?> />
                                                                <img
                                                                    src="/wp-content/plugins/shutter-module/imgs/4001C.png">
                                                            </label>
                                                            <label>Basswood
                                                                <br /> 4022B
                                                                <input type="radio" name="property_frametype"
                                                                    data-code="F70" data-title="4022B" value="332"
                                                                    <?php if (
                                                                                                                                                                    $property_frametype == '332'
                                                                                                                                                                ) {
                                                                                                                                                                    echo "checked='checked'";
                                                                                                                                                                } ?> />
                                                                <img
                                                                    src="/wp-content/plugins/shutter-module/imgs/4022B.png">
                                                            </label>
                                                            <label>
                                                                <br /> P4008K
                                                                <input type="radio" name="property_frametype"
                                                                    data-code="F50" data-title="P4008K" value="323"
                                                                    <?php if (
                                                                                                                                                                    $property_frametype == '323'
                                                                                                                                                                ) {
                                                                                                                                                                    echo "checked='checked'";
                                                                                                                                                                } ?> />
                                                                <img
                                                                    src="/wp-content/plugins/shutter-module/imgs/P4008K.png">
                                                            </label>
                                                            <label>
                                                                <br /> P4008H
                                                                <input type="radio" name="property_frametype"
                                                                    data-code="F50" data-title="P4008H" value="321"
                                                                    <?php if (
                                                                                                                                                                    $property_frametype == '321'
                                                                                                                                                                ) {
                                                                                                                                                                    echo "checked='checked'";
                                                                                                                                                                } ?> />
                                                                <img
                                                                    src="/wp-content/plugins/shutter-module/imgs/P4008H.png">
                                                            </label>
                                                            <label>
                                                                <br /> P4028B
                                                                <input type="radio" name="property_frametype"
                                                                    data-code="F90" data-title="P4028B" value="318"
                                                                    <?php if (
                                                                                                                                                                    $property_frametype == '318'
                                                                                                                                                                ) {
                                                                                                                                                                    echo "checked='checked'";
                                                                                                                                                                } ?> />
                                                                <img
                                                                    src="/wp-content/plugins/shutter-module/imgs/P4028B.png">
                                                            </label>
                                                            <label>
                                                                <br /> P4008S
                                                                <input type="radio" name="property_frametype"
                                                                    data-code="F70" data-title="P4008S" value="330"
                                                                    <?php if ($property_frametype == '330') {
                                                                                                                                                                    echo "checked='checked'";
                                                                                                                                                                } ?> />
                                                                <img
                                                                    src="/wp-content/plugins/shutter-module/imgs/P4008S.png">
                                                            </label>
                                                            <label>
                                                                <br /> P4008T
                                                                <input type="radio" name="property_frametype"
                                                                    data-code="F90" data-title="P4008T" value="322"
                                                                    <?php if (
                                                                                                                                                                    $property_frametype == '322'
                                                                                                                                                                ) {
                                                                                                                                                                    echo "checked='checked'";
                                                                                                                                                                } ?> />
                                                                <img
                                                                    src="/wp-content/plugins/shutter-module/imgs/P4008T.png">
                                                            </label>
                                                            <label>
                                                                <br /> P4008W
                                                                <input type="radio" name="property_frametype"
                                                                    data-code="F90" data-title="P4008W" value="319"
                                                                    <?php if (
                                                                                                                                                                    $property_frametype == '319'
                                                                                                                                                                ) {
                                                                                                                                                                    echo "checked='checked'";
                                                                                                                                                                } ?> />
                                                                <img
                                                                    src="/wp-content/plugins/shutter-module/imgs/P4008W.png">
                                                            </label>
                                                            <label>
                                                                <br /> P4007A
                                                                <input type="radio" name="property_frametype"
                                                                    data-code="F50" data-title="P4007A" value="331"
                                                                    <?php if (
                                                                                                                                                                    $property_frametype == '331'
                                                                                                                                                                ) {
                                                                                                                                                                    echo "checked='checked'";
                                                                                                                                                                } ?> />
                                                                <img
                                                                    src="/wp-content/plugins/shutter-module/imgs/P4007A.png">
                                                            </label>
                                                            <label>
                                                                <br /> P4001N
                                                                <input type="radio" name="property_frametype"
                                                                    data-code="L50" data-title="P4001N" value="320"
                                                                    <?php if (
                                                                                                                                                                    $property_frametype == '320'
                                                                                                                                                                ) {
                                                                                                                                                                    echo "checked='checked'";
                                                                                                                                                                } ?> />
                                                                <img
                                                                    src="/wp-content/plugins/shutter-module/imgs/P4001N.png">
                                                            </label>
                                                            <label>
                                                                <br /> A4001A
                                                                <input type="radio" name="property_frametype"
                                                                    data-code="L50" data-title="A4001A" value="300"
                                                                    <?php if (
                                                                                                                                                                    $property_frametype == '300'
                                                                                                                                                                ) {
                                                                                                                                                                    echo "checked='checked'";
                                                                                                                                                                } ?> />
                                                                <img
                                                                    src="/wp-content/plugins/shutter-module/imgs/A4001A.png">
                                                            </label>
                                                            <label>
                                                                <br /> P4013
                                                                <input type="radio" name="property_frametype"
                                                                    data-code="Z40" data-title="P4013" value="325"
                                                                    <?php if (
                                                                                                                                                                    $property_frametype == '325'
                                                                                                                                                                ) {
                                                                                                                                                                    echo "checked='checked'";
                                                                                                                                                                } ?> />
                                                                <img
                                                                    src="/wp-content/plugins/shutter-module/imgs/P4013.png">
                                                            </label>
                                                            <label>
                                                                <br /> P4033
                                                                <input type="radio" name="property_frametype"
                                                                    data-code="Z50" data-title="P4033" value="327"
                                                                    <?php if (
                                                                                                                                                                    $property_frametype == '327'
                                                                                                                                                                ) {
                                                                                                                                                                    echo "checked='checked'";
                                                                                                                                                                } ?> />
                                                                <img
                                                                    src="/wp-content/plugins/shutter-module/imgs/P4033.png">
                                                            </label>
                                                            <label>
                                                                <br /> P4043
                                                                <input type="radio" name="property_frametype"
                                                                    data-code="Z40" data-title="P4043" value="328"
                                                                    <?php if (
                                                                                                                                                                    $property_frametype == '328'
                                                                                                                                                                ) {
                                                                                                                                                                    echo "checked='checked'";
                                                                                                                                                                } ?> />
                                                                <img
                                                                    src="/wp-content/plugins/shutter-module/imgs/P4043.png">
                                                            </label>
                                                            <label>
                                                                <br /> P4073
                                                                <input type="radio" name="property_frametype"
                                                                    data-code="Z50" data-title="P4073" value="324"
                                                                    <?php if (
                                                                                                                                                                    $property_frametype == '324'
                                                                                                                                                                ) {
                                                                                                                                                                    echo "checked='checked'";
                                                                                                                                                                } ?> />
                                                                <img
                                                                    src="/wp-content/plugins/shutter-module/imgs/P4073.png">
                                                            </label>
                                                            <label>
                                                                <br /> P4014
                                                                <input type="radio" name="property_frametype"
                                                                    data-code="Z3CS" data-title="P4014" value="329"
                                                                    <?php if (
                                                                                                                                                                    $property_frametype == '329'
                                                                                                                                                                ) {
                                                                                                                                                                    echo "checked='checked'";
                                                                                                                                                                } ?> />
                                                                <img
                                                                    src="/wp-content/plugins/shutter-module/imgs/P4014.png">
                                                            </label>
                                                            <label>
                                                                <br /> 4003
                                                                <input type="radio" name="property_frametype"
                                                                    data-code="Z40" data-title="4003" value="314"
                                                                    <?php if (
                                                                                                                                                                $property_frametype == '314'
                                                                                                                                                            ) {
                                                                                                                                                                echo "checked='checked'";
                                                                                                                                                            } ?> />
                                                                <img
                                                                    src="/wp-content/plugins/shutter-module/imgs/4003.png">
                                                            </label>
                                                            <label>
                                                                <br /> 4004
                                                                <input type="radio" name="property_frametype"
                                                                    data-code="Z3CS" data-title="4004" value="315"
                                                                    <?php if (
                                                                                                                                                                    $property_frametype == '315'
                                                                                                                                                                ) {
                                                                                                                                                                    echo "checked='checked'";
                                                                                                                                                                } ?> />
                                                                <img
                                                                    src="/wp-content/plugins/shutter-module/imgs/4004.png">
                                                            </label>
                                                            <label>Basswood
                                                                <br /> 4009
                                                                <input type="radio" name="property_frametype"
                                                                    data-code="D50" data-title="4009" value="142"
                                                                    <?php if (
                                                                                                                                                                $property_frametype == '142'
                                                                                                                                                            ) {
                                                                                                                                                                echo "checked='checked'";
                                                                                                                                                            } ?> />
                                                                <img
                                                                    src="/wp-content/plugins/shutter-module/imgs/4009.png">
                                                            </label>

                                                            <label>Basswood
                                                                <br /> 4013
                                                                <input type="radio" name="property_frametype"
                                                                    data-code="Z50" data-title="4013" value="316"
                                                                    <?php if (
                                                                                                                                                                $property_frametype == '316'
                                                                                                                                                            ) {
                                                                                                                                                                echo "checked='checked'";
                                                                                                                                                            } ?> />
                                                                <img
                                                                    src="/wp-content/plugins/shutter-module/imgs/4013.png">
                                                            </label>
                                                            <label>Basswood
                                                                <br /> 4014
                                                                <input type="radio" name="property_frametype"
                                                                    data-code="Z50" data-title="4014" value="317"
                                                                    <?php if (
                                                                                                                                                                $property_frametype == '317'
                                                                                                                                                            ) {
                                                                                                                                                                echo "checked='checked'";
                                                                                                                                                            } ?> />
                                                                <img
                                                                    src="/wp-content/plugins/shutter-module/imgs/4014.png">
                                                            </label>

                                                            <label>
                                                                <br /> A4027
                                                                <input type="radio" name="property_frametype"
                                                                    data-code="Z50" data-title="A4027" value="302"
                                                                    <?php if (
                                                                                                                                                                    $property_frametype == '302'
                                                                                                                                                                ) {
                                                                                                                                                                    echo "checked='checked'";
                                                                                                                                                                } ?> />
                                                                <img
                                                                    src="/wp-content/plugins/shutter-module/imgs/A4027.png">
                                                            </label>
                                                            <label>
                                                                <br /> A4028
                                                                <input type="radio" name="property_frametype"
                                                                    data-code="Z50" data-title="A4028" value="301"
                                                                    <?php if (
                                                                                                                                                                    $property_frametype == '301'
                                                                                                                                                                ) {
                                                                                                                                                                    echo "checked='checked'";
                                                                                                                                                                } ?> />
                                                                <img
                                                                    src="/wp-content/plugins/shutter-module/imgs/A4028.png">
                                                            </label>

                                                            <label style="display: block;">
                                                                <br>
                                                                Bottom M Track
                                                                <input type="radio" name="property_frametype"
                                                                    data-code="L50" data-title="Bottom M Track"
                                                                    value="144"
                                                                    <?php if (
                                                                                                                                                                            $property_frametype == '144'
                                                                                                                                                                        ) {
                                                                                                                                                                            echo "checked='checked'";
                                                                                                                                                                        } ?> />
                                                                <img
                                                                    src="/wp-content/plugins/shutter-module/imgs/Bottom_M_Track.png">
                                                            </label>

                                                            <label style="display: block;">
                                                                <br>
                                                                Track in Board
                                                                <input type="radio" name="property_frametype"
                                                                    data-code="L50" data-title="Track in Board"
                                                                    value="143"
                                                                    <?php if (
                                                                                                                                                                            $property_frametype == '143'
                                                                                                                                                                        ) {
                                                                                                                                                                            echo "checked='checked'";
                                                                                                                                                                        } ?> />
                                                                <img
                                                                    src="/wp-content/plugins/shutter-module/imgs/Track_in_Board.png">
                                                            </label>

                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row frames" style="margin-top:1em;">
                                                    <div class="col-sm-12">
                                                        <div class="pull-left" id="frame-left">
                                                            Frame Left
                                                            <i class="fa fa-arrow-left"></i>
                                                            <br />
                                                            <input class="property-select" id="property_frameleft"
                                                                name="property_frameleft" type="text"
                                                                value="<?php echo get_post_meta($product_id, 'property_frameleft', true); ?>" />
                                                        </div>
                                                        <div class="pull-left" id="frame-right">
                                                            Frame Right
                                                            <i class="fa fa-arrow-right"></i>
                                                            <br />
                                                            <input class="property-select" id="property_frameright"
                                                                name="property_frameright" type="text"
                                                                value="<?php echo get_post_meta($product_id, 'property_frameright', true); ?>" />
                                                        </div>
                                                        <div class="pull-left" id="frame-top">
                                                            Frame Top
                                                            <i class="fa fa-arrow-up"></i>
                                                            <br />
                                                            <input class="property-select" id="property_frametop"
                                                                name="property_frametop" type="text"
                                                                value="<?php echo get_post_meta($product_id, 'property_frametop', true); ?>" />
                                                        </div>
                                                        <div class="pull-left" id="frame-bottom">
                                                            Frame Bottom
                                                            <i class="fa fa-arrow-down"></i>
                                                            <br />
                                                            <input class="property-select" id="property_framebottom"
                                                                name="property_framebottom" type="text"
                                                                value="<?php echo get_post_meta($product_id, 'property_framebottom', true); ?>" />
                                                        </div>

                                                        <div class="pull-left">
                                                            <span id="add-buildout">
                                                                <br />
                                                                <button class="btn btn-info" style="padding: 0 12px">Add
                                                                    buildout</button>
                                                            </span>
                                                            <span id="buildout" style="display: none">
                                                                Buildout:
                                                                <br />
                                                                <input class="input-small" id="property_builtout"
                                                                    name="property_builtout"
                                                                    placeholder="Enter buildout" style="height: 30px;"
                                                                    type="text"
                                                                    value="<?php echo get_post_meta($product_id, 'property_builtout', true); ?>" />
                                                                <button class="btn btn-danger btn-input"
                                                                    id="remove-buildout">
                                                                    <strong>X</strong>
                                                                </button>
                                                            </span>
                                                        </div>

                                                        <!-- <div class="pull-left">
                                Stile:
                                <br/>
                                <input class="property-select" id="property_stile" name="property_stile" type="text" value="<?php echo get_post_meta($product_id, 'property_stile', true); ?>"
                                />
                              </div> -->
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-sm-12" id="stile-img-earth">
                                                        <div id="choose-frametype" class="col-sm-12 "
                                                            style="display: block;">

                                                            <label>
                                                                <br /> 51mm A1002B (Std.beaded stile)
                                                                <input type="radio" name="property_stile"
                                                                    data-code="FS 50.8"
                                                                    data-title="51mm A1002B (Std.beaded stile)"
                                                                    value="350"
                                                                    <?php if (
                                                                                                                                                                                            $property_stile == '350'
                                                                                                                                                                                        ) {
                                                                                                                                                                                            echo "checked='checked'";
                                                                                                                                                                                        } ?> />
                                                                <img class="stile-1"
                                                                    src="/wp-content/plugins/shutter-module/imgs/A1002B.png" />
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <div class="col-sm-12" id="stile-img-ecowood">
                                                        <div id="choose-frametype" class="col-sm-12 "
                                                            style="display: block;">

                                                            <label>
                                                                <br /> 51mm PVC-P1001B(plain butt)
                                                                <input type="radio" name="property_stile"
                                                                    data-code="FS 50.8"
                                                                    data-title="51mm PVC-P1001B(plain butt)" value="380"
                                                                    <?php if ($property_stile == '380') {
                                                                                                                                                                                        echo "checked='checked'";
                                                                                                                                                                                    } ?> />
                                                                <img class="stile-1"
                                                                    src="/wp-content/plugins/shutter-module/imgs/P1001B.png" />
                                                            </label>

                                                            <label>
                                                                <br /> 51mm PVC-P1005B(plain D-mould)
                                                                <input type="radio" name="property_stile"
                                                                    data-code="DFS 50.8"
                                                                    data-title="51mm PVC-P1005B(plain D-mould)"
                                                                    value="381"
                                                                    <?php if ($property_stile == '381') {
                                                                                                                                                                                            echo "checked='checked'";
                                                                                                                                                                                        } ?> />
                                                                <img class="stile-2"
                                                                    src="/wp-content/plugins/shutter-module/imgs/P1005B.png" />
                                                            </label>

                                                            <label>
                                                                <br /> 51mm PVC-P1003E(plain rebate)
                                                                <input type="radio" name="property_stile"
                                                                    data-code="RFS 50.8"
                                                                    data-title="51mm PVC-P1003E(plain rebate)"
                                                                    value="385"
                                                                    <?php if ($property_stile == '385') {
                                                                                                                                                                                            echo "checked='checked'";
                                                                                                                                                                                        } ?> />
                                                                <img class="stile-3"
                                                                    src="/wp-content/plugins/shutter-module/imgs/P1003B.png" />
                                                            </label>

                                                            <label>
                                                                <br /> 51mm PVC-P1002B(beaded butt)
                                                                <input type="radio" name="property_stile"
                                                                    data-code="BS 50.8"
                                                                    data-title="51mm PVC-P1002B(beaded butt)"
                                                                    value="382"
                                                                    <?php if ($property_stile == '382') {
                                                                                                                                                                                        echo "checked='checked'";
                                                                                                                                                                                    } ?> />
                                                                <img class="stile-4"
                                                                    src="/wp-content/plugins/shutter-module/imgs/P1002B.png" />
                                                            </label>

                                                            <label>
                                                                <br /> 51mm PVC-P1006B(beaded D-mould)
                                                                <input type="radio" name="property_stile"
                                                                    data-code="DBS 50.8"
                                                                    data-title="51mm PVC-P1006B(beaded D-mould)"
                                                                    value="383"
                                                                    <?php if ($property_stile == '383') {
                                                                                                                                                                                            echo "checked='checked'";
                                                                                                                                                                                        } ?> />
                                                                <img class="stile-5"
                                                                    src="/wp-content/plugins/shutter-module/imgs/P1006B.png" />
                                                            </label>

                                                            <label>
                                                                <br /> 51mm PVC-P1004E(beaded rebate)
                                                                <input type="radio" name="property_stile"
                                                                    data-code="RBS 50.8"
                                                                    data-title="51mm PVC-P1004E(beaded rebate)"
                                                                    value="384"
                                                                    <?php if ($property_stile == '384') {
                                                                                                                                                                                            echo "checked='checked'";
                                                                                                                                                                                        } ?> />
                                                                <img class="stile-6"
                                                                    src="/wp-content/plugins/shutter-module/imgs/P1004B.png" />
                                                            </label>

                                                        </div>
                                                    </div>

                                                    <div class="col-sm-12" id="stile-img-supreme">
                                                        <div id="choose-frametype" class="col-sm-12 "
                                                            style="display: block;">

                                                            <label>
                                                                <br /> 51mm 1001B( plain butt)
                                                                <input type="radio" name="property_stile"
                                                                    data-code="FS 50.8"
                                                                    data-title="51mm 1001B( plain butt)" value="355"
                                                                    <?php if (
                                                                                                                                                                                    $property_stile == '355'
                                                                                                                                                                                ) {
                                                                                                                                                                                    echo "checked='checked'";
                                                                                                                                                                                } ?> />
                                                                <img class="stile-1"
                                                                    src="/wp-content/plugins/shutter-module/imgs/1001B.png" />
                                                            </label>

                                                            <label>
                                                                <br /> 51mm 1005B(plain D-mould)
                                                                <input type="radio" name="property_stile"
                                                                    data-code="DFS 50.8"
                                                                    data-title="51mm 1005B(plain D-mould)" value="356"
                                                                    <?php if (
                                                                                                                                                                                        $property_stile == '356'
                                                                                                                                                                                    ) {
                                                                                                                                                                                        echo "checked='checked'";
                                                                                                                                                                                    } ?> />
                                                                <img class="stile-2"
                                                                    src="/wp-content/plugins/shutter-module/imgs/1005B.png" />
                                                            </label>

                                                            <label>
                                                                <br /> 51mm 1003B(plain rebate)
                                                                <input type="radio" name="property_stile"
                                                                    data-code="RFS 50.8"
                                                                    data-title="51mm 1003B(plain rebate)" value="360"
                                                                    <?php if (
                                                                                                                                                                                        $property_stile == '360'
                                                                                                                                                                                    ) {
                                                                                                                                                                                        echo "checked='checked'";
                                                                                                                                                                                    } ?> />
                                                                <img class="stile-3"
                                                                    src="/wp-content/plugins/shutter-module/imgs/1003B.png" />
                                                            </label>

                                                            <label>
                                                                <br /> 51mm 1002B(beaded butt)
                                                                <input type="radio" name="property_stile"
                                                                    data-code="BS 50.8"
                                                                    data-title="51mm 1002B(beaded butt)" value="357"
                                                                    <?php if (
                                                                                                                                                                                    $property_stile == '357'
                                                                                                                                                                                ) {
                                                                                                                                                                                    echo "checked='checked'";
                                                                                                                                                                                } ?> />
                                                                <img class="stile-4"
                                                                    src="/wp-content/plugins/shutter-module/imgs/1002B.png" />
                                                            </label>

                                                            <label>
                                                                <br /> 51mm 1006B(beaded D-mould)
                                                                <input type="radio" name="property_stile"
                                                                    data-code="DBS 50.8"
                                                                    data-title="51mm 1006B(beaded D-mould)" value="358"
                                                                    <?php if (
                                                                                                                                                                                        $property_stile == '358'
                                                                                                                                                                                    ) {
                                                                                                                                                                                        echo "checked='checked'";
                                                                                                                                                                                    } ?> />
                                                                <img class="stile-5"
                                                                    src="/wp-content/plugins/shutter-module/imgs/1006B.png" />
                                                            </label>

                                                            <label>
                                                                <br /> 51mm 1004B(beaded rebate)
                                                                <input type="radio" name="property_stile"
                                                                    data-code="RBS 50.8"
                                                                    data-title="51mm 1004B(beaded rebate)" value="359"
                                                                    <?php if (
                                                                                                                                                                                        $property_stile == '359'
                                                                                                                                                                                    ) {
                                                                                                                                                                                        echo "checked='checked'";
                                                                                                                                                                                    } ?> />
                                                                <img class="stile-6"
                                                                    src="/wp-content/plugins/shutter-module/imgs/1004B.png" />
                                                            </label>

                                                            <label>
                                                                <br /> 35mm 1001A(plain butt)
                                                                <input type="radio" name="property_stile"
                                                                    data-code="FS 38.1"
                                                                    data-title="35mm 1001A(plain butt)" value="361"
                                                                    <?php if (
                                                                                                                                                                                    $property_stile == '361'
                                                                                                                                                                                ) {
                                                                                                                                                                                    echo "checked='checked'";
                                                                                                                                                                                } ?> />
                                                                <img class="stile-7"
                                                                    src="/wp-content/plugins/shutter-module/imgs/1001A.png" />
                                                            </label>

                                                            <label>
                                                                <br /> 35mm 1005A(plain D-mould)
                                                                <input type="radio" name="property_stile"
                                                                    data-code="DFS 38.1"
                                                                    data-title="35mm 1005A(plain D-mould)" value="362"
                                                                    <?php if (
                                                                                                                                                                                        $property_stile == '362'
                                                                                                                                                                                    ) {
                                                                                                                                                                                        echo "checked='checked'";
                                                                                                                                                                                    } ?> />
                                                                <img class="stile-8"
                                                                    src="/wp-content/plugins/shutter-module/imgs/1005A.png" />
                                                            </label>

                                                            <label>
                                                                <br /> 35mm 1003A(plain rebate)
                                                                <input type="radio" name="property_stile"
                                                                    data-code="RFS 38.1"
                                                                    data-title="35mm 1003A(plain rebate)" value="366"
                                                                    <?php if (
                                                                                                                                                                                        $property_stile == '366'
                                                                                                                                                                                    ) {
                                                                                                                                                                                        echo "checked='checked'";
                                                                                                                                                                                    } ?> />
                                                                <img class="stile-9"
                                                                    src="/wp-content/plugins/shutter-module/imgs/1003A.png" />
                                                            </label>

                                                            <label>
                                                                <br /> 35mm 1002A(beaded butt)
                                                                <input type="radio" name="property_stile"
                                                                    data-code="BS 38.1"
                                                                    data-title="35mm 1002A(beaded butt)" value="363"
                                                                    <?php if (
                                                                                                                                                                                    $property_stile == '363'
                                                                                                                                                                                ) {
                                                                                                                                                                                    echo "checked='checked'";
                                                                                                                                                                                } ?> />
                                                                <img class="stile-10"
                                                                    src="/wp-content/plugins/shutter-module/imgs/1002A.png" />
                                                            </label>

                                                            <label>
                                                                <br /> 35mm 1006A(beaded D-mould)
                                                                <input type="radio" name="property_stile"
                                                                    data-code="DBS 38.1"
                                                                    data-title="35mm 1006A(beaded D-mould)" value="364"
                                                                    <?php if (
                                                                                                                                                                                        $property_stile == '364'
                                                                                                                                                                                    ) {
                                                                                                                                                                                        echo "checked='checked'";
                                                                                                                                                                                    } ?> />
                                                                <img class="stile-11"
                                                                    src="/wp-content/plugins/shutter-module/imgs/1006A.png" />
                                                            </label>

                                                            <label>
                                                                <br /> 35mm 1004A(beaded rebate)
                                                                <input type="radio" name="property_stile"
                                                                    data-code="RBS 38.1"
                                                                    data-title="35mm 1004A(beaded rebate)" value="365"
                                                                    <?php if (
                                                                                                                                                                                        $property_stile == '365'
                                                                                                                                                                                    ) {
                                                                                                                                                                                        echo "checked='checked'";
                                                                                                                                                                                    } ?> />
                                                                <img class="stile-12"
                                                                    src="/wp-content/plugins/shutter-module/imgs/1004A.png" />
                                                            </label>

                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12" id="stile-img-biowood">
                                                        <div id="choose-frametype" class="col-sm-12 "
                                                            style="display: block;">

                                                            <label>
                                                                <br /> 41mm T1001M(plain butt)
                                                                <input type="radio" name="property_stile"
                                                                    data-code="FS 50.8"
                                                                    data-title="41mm T1001M(plain butt)" value="376"
                                                                    <?php if (
                                                                                                                                                                                    $property_stile == '376'
                                                                                                                                                                                ) {
                                                                                                                                                                                    echo "checked='checked'";
                                                                                                                                                                                } ?> />
                                                                <img class="stile-1"
                                                                    src="/wp-content/plugins/shutter-module/imgs/T1001M.png" />
                                                            </label>

                                                            <label>
                                                                <br /> 41mm T1005M(plain D-mould)
                                                                <input type="radio" name="property_stile"
                                                                    data-code="DFS 50.8"
                                                                    data-title="41mm T1005M(plain D-mould)" value="377"
                                                                    <?php if (
                                                                                                                                                                                        $property_stile == '377'
                                                                                                                                                                                    ) {
                                                                                                                                                                                        echo "checked='checked'";
                                                                                                                                                                                    } ?> />
                                                                <img class="stile-2"
                                                                    src="/wp-content/plugins/shutter-module/imgs/T1005M.png" />
                                                            </label>

                                                            <label>
                                                                <br /> 41mm T1003M(plain rebate)
                                                                <input type="radio" name="property_stile"
                                                                    data-code="RFS 50.8"
                                                                    data-title="41mm T1003M(plain rebate)" value="378"
                                                                    <?php if (
                                                                                                                                                                                        $property_stile == '378'
                                                                                                                                                                                    ) {
                                                                                                                                                                                        echo "checked='checked'";
                                                                                                                                                                                    } ?> />
                                                                <img class="stile-3"
                                                                    src="/wp-content/plugins/shutter-module/imgs/T1003M.png" />
                                                            </label>

                                                            <label>
                                                                <br /> 51mm T1001K(plain butt)
                                                                <input type="radio" name="property_stile"
                                                                    data-code="FS 50.8"
                                                                    data-title="51mm T1001K(plain butt)" value="370"
                                                                    <?php if (
                                                                                                                                                                                    $property_stile == '370'
                                                                                                                                                                                ) {
                                                                                                                                                                                    echo "checked='checked'";
                                                                                                                                                                                } ?> />
                                                                <img class="stile-1"
                                                                    src="/wp-content/plugins/shutter-module/imgs/T1001K.png" />
                                                            </label>

                                                            <label>
                                                                <br /> 51mm T1005K(plain D-mould)
                                                                <input type="radio" name="property_stile"
                                                                    data-code="DFS 50.8"
                                                                    data-title="51mm T1005K(plain D-mould)" value="371"
                                                                    <?php if (
                                                                                                                                                                                        $property_stile == '371'
                                                                                                                                                                                    ) {
                                                                                                                                                                                        echo "checked='checked'";
                                                                                                                                                                                    } ?> />
                                                                <img class="stile-2"
                                                                    src="/wp-content/plugins/shutter-module/imgs/T1005K.png" />
                                                            </label>

                                                            <label>
                                                                <br /> 51mm T1003K(plain rebate)
                                                                <input type="radio" name="property_stile"
                                                                    data-code="RFS 50.8"
                                                                    data-title="51mm T1003K(plain rebate)" value="375"
                                                                    <?php if (
                                                                                                                                                                                        $property_stile == '375'
                                                                                                                                                                                    ) {
                                                                                                                                                                                        echo "checked='checked'";
                                                                                                                                                                                    } ?> />
                                                                <img class="stile-3"
                                                                    src="/wp-content/plugins/shutter-module/imgs/T1003K.png" />
                                                            </label>

                                                            <label>
                                                                <br /> 51mm T1002K(beaded butt)
                                                                <input type="radio" name="property_stile"
                                                                    data-code="BS 50.81"
                                                                    data-title="51mm T1002K(beaded butt)" value="372"
                                                                    <?php if (
                                                                                                                                                                                        $property_stile == '372'
                                                                                                                                                                                    ) {
                                                                                                                                                                                        echo "checked='checked'";
                                                                                                                                                                                    } ?> />
                                                                <img class="stile-4"
                                                                    src="/wp-content/plugins/shutter-module/imgs/T1002K.png" />
                                                            </label>

                                                            <label>
                                                                <br /> 51mm T1006K(beaded D-mould)
                                                                <input type="radio" name="property_stile"
                                                                    data-code="DBS 50.8"
                                                                    data-title="51mm T1006K(beaded D-mould)" value="373"
                                                                    <?php if (
                                                                                                                                                                                        $property_stile == '373'
                                                                                                                                                                                    ) {
                                                                                                                                                                                        echo "checked='checked'";
                                                                                                                                                                                    } ?> />
                                                                <img class="stile-5"
                                                                    src="/wp-content/plugins/shutter-module/imgs/T1006K.png" />
                                                            </label>

                                                            <label>
                                                                <br /> 51mm T1004K(beaded rebate)
                                                                <input type="radio" name="property_stile"
                                                                    data-code="RBS 50.8"
                                                                    data-title="51mm T1004K(beaded rebate)" value="374"
                                                                    <?php if (
                                                                                                                                                                                        $property_stile == '374'
                                                                                                                                                                                    ) {
                                                                                                                                                                                        echo "checked='checked'";
                                                                                                                                                                                    } ?> />
                                                                <img class="stile-6"
                                                                    src="/wp-content/plugins/shutter-module/imgs/T1004K.png" />
                                                            </label>

                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12" id="stile-img-green">
                                                        <div id="choose-frametype" class="col-sm-12 "
                                                            style="display: block;">

                                                            <label>
                                                                <br /> 51mm PVC-P1001B(plain butt)
                                                                <input type="radio" name="property_stile"
                                                                    data-code="FS 50.8"
                                                                    data-title="51mm PVC-P1001B(plain butt)" value="380"
                                                                    <?php if (
                                                                                                                                                                                        $property_stile == '380'
                                                                                                                                                                                    ) {
                                                                                                                                                                                        echo "checked='checked'";
                                                                                                                                                                                    } ?> />
                                                                <img class="stile-1"
                                                                    src="/wp-content/plugins/shutter-module/imgs/P1001B.png" />
                                                            </label>

                                                            <label>
                                                                <br /> 51mm PVC-P1005B(plain D-mould)
                                                                <input type="radio" name="property_stile"
                                                                    data-code="DFS 50.8"
                                                                    data-title="51mm PVC-P1005B(plain D-mould)"
                                                                    value="381"
                                                                    <?php if (
                                                                                                                                                                                            $property_stile == '381'
                                                                                                                                                                                        ) {
                                                                                                                                                                                            echo "checked='checked'";
                                                                                                                                                                                        } ?> />
                                                                <img class="stile-2"
                                                                    src="/wp-content/plugins/shutter-module/imgs/P1005B.png" />
                                                            </label>

                                                            <label>
                                                                <br /> 51mm PVC-P1003E(plain rebate)
                                                                <input type="radio" name="property_stile"
                                                                    data-code="RFS 50.8"
                                                                    data-title="51mm PVC-P1003E(plain rebate)"
                                                                    value="385"
                                                                    <?php if (
                                                                                                                                                                                            $property_stile == '385'
                                                                                                                                                                                        ) {
                                                                                                                                                                                            echo "checked='checked'";
                                                                                                                                                                                        } ?> />
                                                                <img class="stile-3"
                                                                    src="/wp-content/plugins/shutter-module/imgs/P1003B.png" />
                                                            </label>

                                                            <label>
                                                                <br /> 51mm PVC-P1002B(beaded butt)
                                                                <input type="radio" name="property_stile"
                                                                    data-code="BS 50.8"
                                                                    data-title="51mm PVC-P1002B(beaded butt)"
                                                                    value="382"
                                                                    <?php if (
                                                                                                                                                                                        $property_stile == '382'
                                                                                                                                                                                    ) {
                                                                                                                                                                                        echo "checked='checked'";
                                                                                                                                                                                    } ?> />
                                                                <img class="stile-4"
                                                                    src="/wp-content/plugins/shutter-module/imgs/P1002B.png" />
                                                            </label>

                                                            <label>
                                                                <br /> 51mm PVC-P1006B(beaded D-mould)
                                                                <input type="radio" name="property_stile"
                                                                    data-code="DBS 50.8"
                                                                    data-title="51mm PVC-P1006B(beaded D-mould)"
                                                                    value="383"
                                                                    <?php if (
                                                                                                                                                                                            $property_stile == '383'
                                                                                                                                                                                        ) {
                                                                                                                                                                                            echo "checked='checked'";
                                                                                                                                                                                        } ?> />
                                                                <img class="stile-5"
                                                                    src="/wp-content/plugins/shutter-module/imgs/P1006B.png" />
                                                            </label>

                                                            <label>
                                                                <br /> 51mm PVC-P1004E(beaded rebate)
                                                                <input type="radio" name="property_stile"
                                                                    data-code="RBS 50.8"
                                                                    data-title="51mm PVC-P1004E(beaded rebate)"
                                                                    value="384"
                                                                    <?php if (
                                                                                                                                                                                            $property_stile == '384'
                                                                                                                                                                                        ) {
                                                                                                                                                                                            echo "checked='checked'";
                                                                                                                                                                                        } ?> />
                                                                <img class="stile-6"
                                                                    src="/wp-content/plugins/shutter-module/imgs/P1004B.png" />
                                                            </label>

                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- <div class="row">
                            <div class="col-sm-4">
                                  <div class="pull-left">
                                    Stile:
                                    <br/>
                                    <input class="property-select required" id="property_stile" name="property_stile" type="text" value="<?php echo get_post_meta($product_id, 'property_stile', true); ?>" />
                                  </div>
                                  <br/>
                            </div>
                          </div> -->

                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <hr />
                                                        <button class="btn btn-info show-next-panel">
                                                            Next
                                                            <i class="fa fa-chevron-right"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="panel panel-default">
                                        <div class="panel-heading" role="tab" id="headingColour">
                                            <h4 class="panel-title">
                                                <a role="button" class="" data-toggle="collapse"
                                                    data-parent="#accordion" href="#collapseColour" aria-expanded="true"
                                                    aria-controls="collapseColour">
                                                    Colour, Hinges, Control &amp; Configuration Design
                                                </a>
                                            </h4>
                                        </div>
                                        <div id="collapseColour" class="panel-collapse collapse " role="tabpanel"
                                            aria-labelledby="headingColour">
                                            <div class="panel-body">
                                                <div class="row">
                                                    <div class="col-sm-3">
                                                        Hinge Colour:
                                                        <br />
                                                        <input class="property-select required"
                                                            id="property_hingecolour" name="property_hingecolour"
                                                            type="text"
                                                            value="<?php echo get_post_meta($product_id, 'property_hingecolour', true); ?>" />
                                                    </div>
                                                    <div class="col-sm-3">
                                                        Shutter Colour:
                                                        <br />
                                                        <input class="property-select required"
                                                            id="property_shuttercolour" name="property_shuttercolour"
                                                            type="text"
                                                            value="<?php echo get_post_meta($product_id, 'property_shuttercolour', true); ?>" />
                                                    </div>
                                                    <div class="col-sm-3" id="colour-other" style="display: none">
                                                        Other Colour:
                                                        <br />
                                                        <input id="property_shuttercolour_other"
                                                            name="property_shuttercolour_other" style="height: 30px"
                                                            type="text"
                                                            value="<?php echo get_post_meta($product_id, 'property_shuttercolour_other', true); ?>" />
                                                    </div>
                                                    <div class="col-sm-3">
                                                        Control Type:
                                                        <br />
                                                        <input class="property-select" id="property_controltype"
                                                            name="property_controltype" type="text"
                                                            value="<?php echo get_post_meta($product_id, 'property_controltype', true); ?>" />
                                                    </div>
                                                </div>
                                                <div class="row layout-row" style="margin-top:1em;">
                                                    <div class="col-sm-6" id="layoutcode-column">
                                                        Layout Configuration:
                                                        <br />
                                                        <div class="input-group-container">
                                                            <div class="input-group">
                                                                <span class="input-group-addon"
                                                                    title="Type in the layout code"
                                                                    data-toggle="tooltip" data-placement="top">?</span>
                                                                <input class="required input-medium"
                                                                    id="property_layoutcode" name="property_layoutcode"
                                                                    style="text-transform:uppercase" type="text"
                                                                    value="<?php echo strtoupper(get_post_meta($product_id, 'property_layoutcode', true)); ?>" />
                                                            </div>
                                                            <div class="note-ecowood-angle" style="<?php if ($material != 188) {
                                                                                                        echo 'display: none';
                                                                                                    } ?>;">Note: this
                                                                material allows only 90 and 135 bay angles
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row extra-columns-row">
                                                    <div class="col-sm-12">

                                                        <?php
                                                        foreach ($nr_code_prod as $key => $val) {
                                                            for ($i = 1; $i < $val + 1; $i++) {
                                                                if ($key == 'b') {
                                                        ?>
                                                        <div class="pull-left extra-column">
                                                            <span class="extra-column-label">Bay Post
                                                                <?php echo $i; ?>
                                                            </span>:
                                                            <br>
                                                            <div class="input-group">
                                                                <span data-placement="top" data-toggle="tooltip"
                                                                    title="" class="input-group-addon"
                                                                    data-original-title="Type in the layout code">?</span>
                                                                <input name="property_bp<?php echo $i; ?>"
                                                                    id="property_bp<?php echo $i; ?>"
                                                                    class="input-small required" type="text"
                                                                    value="<?php echo get_post_meta($product_id, 'property_bp' . $i, true); ?>">
                                                            </div>
                                                        </div>

                                                        <div class="pull-left extra-column">
                                                            <span class="extra-column-label">Bay Angle
                                                                <?php echo $i; ?>
                                                            </span>:
                                                            <br>

                                                            <div class="input-group">

                                                                <?
                                                                            if ($material == '188') { ?>
                                                                <select class="b-angle-select"
                                                                    id="property_ba<?php echo $i; ?>">
                                                                    <option value="90" <?php if (get_post_meta($product_id, 'property_ba' . $i, true) == '90') {
                                                                                                            echo 'selected';
                                                                                                        } ?>>90
                                                                    </option>
                                                                    <option value="135" <?php if (get_post_meta($product_id, 'property_ba' . $i, true) == '135') {
                                                                                                            echo 'selected';
                                                                                                        } ?>>135
                                                                    </option>
                                                                </select>
                                                                <?php } else {
                                                                            ?>
                                                                <input name="property_ba<?php echo $i; ?>"
                                                                    id="property_ba<?php echo $i; ?>"
                                                                    class="input-small required" type="text"
                                                                    value="<?php echo get_post_meta($product_id, 'property_ba' . $i, true); ?>">
                                                                <?php } ?>
                                                            </div>
                                                        </div>

                                                        <?php
                                                                } elseif ($key == 't') { ?>
                                                        <div class="pull-left extra-column">
                                                            <span class="extra-column-label">T-Post
                                                                <?php echo $i; ?>
                                                            </span>:
                                                            <br>

                                                            <div class="input-group">
                                                                <span data-placement="top" data-toggle="tooltip"
                                                                    title="" class="input-group-addon"
                                                                    data-original-title="Type in the layout code">?</span>
                                                                <input name="property_t<?php echo $i; ?>"
                                                                    id="property_t<?php echo $i; ?>"
                                                                    class="input-small required" type="text"
                                                                    value="<?php echo get_post_meta($product_id, 'property_t' . $i, true); ?>">
                                                            </div>
                                                        </div>

                                                        <?php
                                                                } elseif ($key == 'g') { ?>
                                                        <div class="pull-left extra-column">
                                                            <span class="extra-column-label">G-Post
                                                                <?php echo $i; ?>
                                                            </span>:
                                                            <br>

                                                            <div class="input-group">
                                                                <span data-placement="top" data-toggle="tooltip"
                                                                    title="" class="input-group-addon"
                                                                    data-original-title="Type in the layout code">?</span>
                                                                <input name="property_g<?php echo $i; ?>"
                                                                    id="property_g<?php echo $i; ?>"
                                                                    class="input-small required" type="text"
                                                                    value="<?php echo get_post_meta($product_id, 'property_g' . $i, true); ?>">
                                                            </div>
                                                        </div>

                                                        <?php
                                                                } elseif ($key == 'c') { ?>
                                                        <div class="pull-left extra-column">
                                                            <span class="extra-column-label">C-Post
                                                                <?php echo $i; ?>
                                                            </span>:
                                                            <br>

                                                            <div class="input-group">
                                                                <span data-placement="top" data-toggle="tooltip"
                                                                    title="" class="input-group-addon"
                                                                    data-original-title="Type in the layout code">?</span>
                                                                <input name="property_c<?php echo $i; ?>"
                                                                    id="property_c<?php echo $i; ?>"
                                                                    class="input-small required" type="text"
                                                                    value="<?php echo get_post_meta($product_id, 'property_c' . $i, true); ?>">
                                                            </div>
                                                        </div>

                                                        <?php
                                                                }
                                                            }
                                                        } ?>

                                                    </div>
                                                </div>

                                                <div class="row extra-columns-buildout-row">
                                                    <div class="col-sm-12">

                                                        <?php
                                                        foreach ($nr_code_prod as $key => $val) {
                                                            for ($i = 1; $i < $val + 1; $i++) {
                                                                if ($key == 'b') {
                                                        ?>

                                                        <?php if ($i == 1) { ?>
                                                        <div
                                                            class="pull-left extra-column-buildout <?php if ($material == '188') {
                                                                                                                        echo 'b-angle-select-type';
                                                                                                                    } ?>">
                                                            <span class="extra-column-label">B-Post Type
                                                                <select id="buildout-select" name="bay-post-type">
                                                                    <?php

                                                                                    if ($bay_post_type == 'normal') { ?>
                                                                    <option value="normal" selected>Normal</option>
                                                                    <option value="flexible">Flexible</option>
                                                                    <?php
                                                                                    } elseif ($bay_post_type == 'flexible') { ?>
                                                                    <option value="normal">Normal</option>
                                                                    <option value="flexible" selected>Flexible</option>
                                                                    <?php
                                                                                    }
                                                                                    ?>
                                                                </select>
                                                            </span>
                                                            <br>

                                                        </div>

                                                        <div class="pull-left extra-column-buildout property_b_buildout1"
                                                            <?php if ($bay_post_type == 'flexible') { ?>
                                                            style="display: none;" <?php } ?>>
                                                            <span class="extra-column-label">B-Post Buildout
                                                                <? //php echo $i; 
                                                                                ?>
                                                            </span>:
                                                            <br>

                                                            <div class="input-group">
                                                                <span data-placement="top" data-toggle="tooltip"
                                                                    title="" class="input-group-addon"
                                                                    data-original-title="Type in the layout code">?</span>
                                                                <input name="property_b_buildout<?php echo $i; ?>"
                                                                    id="property_b_buildout<?php echo $i; ?>"
                                                                    class="input-small" type="checkbox" value="yes"
                                                                    <?php if (get_post_meta($product_id, 'property_b_buildout' . $i, true) == 'yes') {
                                                                                                                                                                                                                                    echo 'checked';
                                                                                                                                                                                                                                } ?>>
                                                            </div>
                                                        </div>
                                                        <?php } ?>

                                                        <?php
                                                                } elseif ($key == 't') { ?>

                                                        <?php if ($i == 1) { ?>
                                                        <div class="pull-left extra-column">
                                                            <span class="extra-column-label">T-Post Buildout
                                                                <?php echo $i; ?>
                                                            </span>:
                                                            <br>

                                                            <div class="input-group">
                                                                <span data-placement="top" data-toggle="tooltip"
                                                                    title="" class="input-group-addon"
                                                                    data-original-title="Type in the layout code">?</span>
                                                                <input name="property_t_buildout<?php echo $i; ?>"
                                                                    id="property_t_buildout<?php echo $i; ?>"
                                                                    class="input-small" type="checkbox" value="yes"
                                                                    <?php if (get_post_meta($product_id, 'property_t_buildout' . $i, true) == 'yes') {
                                                                                                                                                                                                                                    echo 'checked';
                                                                                                                                                                                                                                } ?>>
                                                            </div>
                                                        </div>
                                                        <?php } ?>

                                                        <?php
                                                                } elseif ($key == 'g') { ?>

                                                        <?php if ($i == 1) { ?>
                                                        <div class="pull-left extra-column">
                                                            <span class="extra-column-label">G-Post Buildout
                                                                <?php echo $i; ?>
                                                            </span>:
                                                            <br>

                                                            <div class="input-group">
                                                                <span data-placement="top" data-toggle="tooltip"
                                                                    title="" class="input-group-addon"
                                                                    data-original-title="Type in the layout code">?</span>
                                                                <input name="property_g_buildout<?php echo $i; ?>"
                                                                    id="property_g_buildout<?php echo $i; ?>"
                                                                    class="input-small" type="checkbox" value="yes"
                                                                    <?php if (get_post_meta($product_id, 'property_g_buildout' . $i, true) == 'yes') {
                                                                                                                                                                                                                                    echo 'checked';
                                                                                                                                                                                                                                } ?>>
                                                            </div>
                                                        </div>
                                                        <?php } ?>

                                                        <?php
                                                                } elseif ($key == 'c') { ?>

                                                        <?php if ($i == 1) { ?>
                                                        <div class="pull-left extra-column">
                                                            <span class="extra-column-label">C-Post Buildout
                                                                <?php echo $i; ?>
                                                            </span>:
                                                            <br>

                                                            <div class="input-group">
                                                                <span data-placement="top" data-toggle="tooltip"
                                                                    title="" class="input-group-addon"
                                                                    data-original-title="Type in the layout code">?</span>
                                                                <input name="property_c_buildout<?php echo $i; ?>"
                                                                    id="property_c_buildout<?php echo $i; ?>"
                                                                    class="input-small" type="checkbox" value="yes"
                                                                    <?php if (get_post_meta($product_id, 'property_c_buildout' . $i, true) == 'yes') {
                                                                                                                                                                                                                                    echo 'checked';
                                                                                                                                                                                                                                } ?>>
                                                            </div>
                                                        </div>
                                                        <?php } ?>

                                                        <?php
                                                                }
                                                            }
                                                        } ?>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-sm-3 tpost-type" style="<?php if (get_post_meta($product_id, 'property_tposttype', true)) {
                                                                                                echo 'display: block';
                                                                                            } else {
                                                                                                echo 'display: none';
                                                                                            } ?>" data-toggle="tooltip"
                                                        title="">
                                                        T-Post Type:
                                                        <br />
                                                    </div>

                                                    <div class="tpost-type" style="display: none;">
                                                        <div class="col-sm-12 tpost-img type-img-earth"
                                                            style="display: none;">

                                                            <label>
                                                                <br /> A7001 - Earth Standard T-Post
                                                                <input type="radio" name="property_tposttype"
                                                                    data-code="RBS 50.8"
                                                                    data-title="A7001 - Earth Standard T-Post"
                                                                    value="442"
                                                                    <?php if (
                                                                                                                                                                                                $property_tposttype == '442'
                                                                                                                                                                                            ) {
                                                                                                                                                                                                echo "checked='checked'";
                                                                                                                                                                                            } ?> />
                                                                <img class="stile-6"
                                                                    src="/wp-content/plugins/shutter-module/imgs/A7001.png" />
                                                            </label>
                                                            <!-- <img src="/wp-content/plugins/shutter-module/imgs/T-PostTypes.png" /> -->
                                                        </div>
                                                    </div>

                                                    <div class="tpost-type" style="display: none;">
                                                        <div class="col-sm-12 tpost-img type-img-ecowood"
                                                            style="display: none;">

                                                            <label>
                                                                <br /> P7032 - Standard T-Post
                                                                <input type="radio" name="property_tposttype"
                                                                    data-code="RBS 50.8"
                                                                    data-title="P7032 - Standard T-Post" value="437"
                                                                    <?php if (
                                                                                                                                                                                        $property_tposttype == '437'
                                                                                                                                                                                    ) {
                                                                                                                                                                                        echo "checked='checked'";
                                                                                                                                                                                    } ?> />
                                                                <img class="stile-6"
                                                                    src="/wp-content/plugins/shutter-module/imgs/P7032.png" />
                                                            </label>
                                                            <!-- <img src="/wp-content/plugins/shutter-module/imgs/T-PostTypes.png" /> -->
                                                        </div>
                                                    </div>

                                                    <div class="tpost-type" style="display: none;">
                                                        <div class="col-sm-12 tpost-img type-img-supreme"
                                                            style="display: none;">
                                                            <label>
                                                                <br /> 7001 - Supreme Std. T-Post
                                                                <input type="radio" name="property_tposttype"
                                                                    data-code="RBS 50.8"
                                                                    data-title="7001 - Supreme Standard T-Post"
                                                                    value="438"
                                                                    <?php if (
                                                                                                                                                                                                $property_tposttype == '438'
                                                                                                                                                                                            ) {
                                                                                                                                                                                                echo "checked='checked'";
                                                                                                                                                                                            } ?> />
                                                                <img class="stile-6"
                                                                    src="/wp-content/plugins/shutter-module/imgs/7001.png" />
                                                            </label>
                                                            <label>
                                                                <br /> 7201 - T-Post with insert
                                                                <input type="radio" name="property_tposttype"
                                                                    data-code="RBS 50.8"
                                                                    data-title="7201 - T-Post with insert" value="439"
                                                                    <?php if (
                                                                                                                                                                                            $property_tposttype == '439'
                                                                                                                                                                                        ) {
                                                                                                                                                                                            echo "checked='checked'";
                                                                                                                                                                                        } ?> />
                                                                <img class="stile-6"
                                                                    src="/wp-content/plugins/shutter-module/imgs/7021.png" />
                                                            </label>
                                                            <label>
                                                                <br /> 7011 - Large T-Post<br />
                                                                <input type="radio" name="property_tposttype"
                                                                    data-code="RBS 50.8"
                                                                    data-title="7011 - Large T-Post" value="441"
                                                                    <?php if (
                                                                                                                                                                                    $property_tposttype == '441'
                                                                                                                                                                                ) {
                                                                                                                                                                                    echo "checked='checked'";
                                                                                                                                                                                } ?> />
                                                                <img class="stile-6"
                                                                    src="/wp-content/plugins/shutter-module/imgs/7011.png" />
                                                            </label>
                                                            <!-- <img src="/wp-content/plugins/shutter-module/imgs/T-PostTypes.png" /> -->
                                                        </div>
                                                    </div>

                                                    <div class="tpost-type" style="display: none;">
                                                        <div class="col-sm-12 tpost-img type-img-biowood"
                                                            style="display: none;">

                                                            <label>
                                                                <br /> P7032 - Standard T-Post
                                                                <input type="radio" name="property_tposttype"
                                                                    data-code="RBS 50.8"
                                                                    data-title="P7032 - Standard T-Post" value="437"
                                                                    <?php if (
                                                                                                                                                                                        $property_tposttype == '437'
                                                                                                                                                                                    ) {
                                                                                                                                                                                        echo "checked='checked'";
                                                                                                                                                                                    } ?> />
                                                                <img class="stile-6"
                                                                    src="/wp-content/plugins/shutter-module/imgs/P7032.png" />
                                                            </label>
                                                            <label>
                                                                <br /> 7001 - Supreme Std. T-Post
                                                                <input type="radio" name="property_tposttype"
                                                                    data-code="RBS 50.8"
                                                                    data-title="7001 - Supreme Standard T-Post"
                                                                    value="438"
                                                                    <?php if (
                                                                                                                                                                                                $property_tposttype == '438'
                                                                                                                                                                                            ) {
                                                                                                                                                                                                echo "checked='checked'";
                                                                                                                                                                                            } ?> />
                                                                <img class="stile-6"
                                                                    src="/wp-content/plugins/shutter-module/imgs/7001.png" />
                                                            </label>
                                                            <label>
                                                                <br /> 7011 - Large T-Post<br />
                                                                <input type="radio" name="property_tposttype"
                                                                    data-code="RBS 50.8"
                                                                    data-title="7011 - Large T-Post" value="441"
                                                                    <?php if (
                                                                                                                                                                                    $property_tposttype == '441'
                                                                                                                                                                                ) {
                                                                                                                                                                                    echo "checked='checked'";
                                                                                                                                                                                } ?> />
                                                                <img class="stile-6"
                                                                    src="/wp-content/plugins/shutter-module/imgs/7011.png" />
                                                            </label>
                                                            <label>
                                                                <br /> 7201 - T-Post with insert
                                                                <input type="radio" name="property_tposttype"
                                                                    data-code="RBS 50.8"
                                                                    data-title="7201 - T-Post with insert" value="439"
                                                                    <?php if (
                                                                                                                                                                                            $property_tposttype == '439'
                                                                                                                                                                                        ) {
                                                                                                                                                                                            echo "checked='checked'";
                                                                                                                                                                                        } ?> />
                                                                <img class="stile-6"
                                                                    src="/wp-content/plugins/shutter-module/imgs/7021.png" />
                                                            </label>
                                                            <label>
                                                                <br /> A7001 - Standard T-Post
                                                                <input type="radio" name="property_tposttype"
                                                                    data-code="RBS 50.8"
                                                                    data-title="A7011 - Standard T-Post" value="442"
                                                                    <?php if (
                                                                                                                                                                                        $property_tposttype == '442'
                                                                                                                                                                                    ) {
                                                                                                                                                                                        echo "checked='checked'";
                                                                                                                                                                                    } ?> />
                                                                <img class="stile-6"
                                                                    src="/wp-content/plugins/shutter-module/imgs/A7001.png" />
                                                            </label>
                                                            <!-- <img src="/wp-content/plugins/shutter-module/imgs/T-PostTypes.png" /> -->
                                                        </div>
                                                    </div>

                                                    <div class="tpost-type" style="display: none;">
                                                        <div class="col-sm-12 tpost-img type-img-green"
                                                            style="display: none;">

                                                            <label>
                                                                <br />
                                                                <p>P7032 - Standard T-Post</p>
                                                                <input type="radio" name="property_tposttype"
                                                                    data-code="RBS 50.8"
                                                                    data-title="P7032 - Standard T-Post" value="437"
                                                                    <?php if (
                                                                                                                                                                                        $property_tposttype == '437'
                                                                                                                                                                                    ) {
                                                                                                                                                                                        echo "checked='checked'";
                                                                                                                                                                                    } ?> />
                                                                <img class="stile-6"
                                                                    src="/wp-content/plugins/shutter-module/imgs/P7032.png" />
                                                            </label>

                                                            <label>
                                                                <br />
                                                                <p>P7032 - Standard T-Post</p>
                                                                <input type="radio" name="property_tposttype"
                                                                    data-code="RBS 50.8"
                                                                    data-title="P7032 - Standard T-Post" value="437"
                                                                    <?php if (
                                                                                                                                                                                        $property_tposttype == '437'
                                                                                                                                                                                    ) {
                                                                                                                                                                                        echo "checked='checked'";
                                                                                                                                                                                    } ?> />
                                                                <img class="stile-6"
                                                                    src="/wp-content/plugins/shutter-module/imgs/P7032.png" />
                                                            </label>
                                                            <!-- <img src="/wp-content/plugins/shutter-module/imgs/T-PostTypes.png" /> -->
                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="row" style="margin-top:1em;">
                                                    <div class="col-sm-4" id="spare-louvres">
                                                        <div>
                                                            <label>
                                                                <?php $property_sparelouvres = get_post_meta($product_id, 'property_sparelouvres', true) ?>
                                                                <select id="property_sparelouvres"
                                                                    name="property_sparelouvres">
                                                                    <option value="Yes" <?php if ($property_sparelouvres == 'Yes') {
                                                                                            echo 'selected';
                                                                                        } ?>>Yes
                                                                    </option>
                                                                    <option value="No" <?php if ($property_sparelouvres == 'No') {
                                                                                            echo 'selected';
                                                                                        } ?>>No
                                                                    </option>
                                                                </select>
                                                                <!-- <input class="ace" id="property_sparelouvres" name="property_sparelouvres" type="checkbox" <?php if ($property_sparelouvres == "Yes") {
                                                                                                                                                                    echo 'checked';
                                                                                                                                                                } ?> value="Yes"     /> -->
                                                                <span class="lbl"> Include 2 x Spare Louvres (£6 +
                                                                    VAT)</span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row" style="margin-top:1em;">
                                                    <div class="col-sm-4" id="ring-pull" style="display:none">
                                                        <div>
                                                            <label>

                                                                <?php $property_ringpull = get_post_meta($product_id, 'property_ringpull', true) ?>
                                                                <select id="property_ringpull" name="property_ringpull">
                                                                    <option value="No" <?php if ($property_ringpull == 'No') {
                                                                                            echo 'selected';
                                                                                        } ?>>No
                                                                    </option>
                                                                    <option value="Yes" <?php if ($property_ringpull == 'Yes') {
                                                                                            echo 'selected';
                                                                                        } ?>>Yes
                                                                    </option>
                                                                </select>
                                                                <!-- <input class="ace" id="property_ringpull" name="property_ringpull" type="checkbox" <?php if ($property_ringpull != '') {
                                                                                                                                                            echo 'checked';
                                                                                                                                                        } ?> value="yes"
                                  /> -->
                                                                <span class="lbl"> Ring Pull</span>
                                                            </label>
                                                        </div>
                                                        <div>
                                                            <label>

                                                                <?php $property_ringpull_volume = get_post_meta($product_id, 'property_ringpull_volume', true) ?>
                                                                <input type="number" id="property_ringpull"
                                                                    name="property_ringpull_volume"
                                                                    value="<?php echo $property_ringpull_volume; ?>">
                                                                <span class="lbl"> How Many? (please specify position on
                                                                    the comment field)</span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row" style="margin-top:1em;">
                                                    <div class="col-sm-6" id="locks" style="display:none">
                                                        <div>
                                                            <label>
                                                                <?php $property_locks = get_post_meta($product_id, 'property_locks', true) ?>
                                                                <select id="property_locks" name="property_locks">
                                                                    <option value="No" <?php if ($property_locks == 'No') {
                                                                                            echo 'selected';
                                                                                        } ?>>No
                                                                    </option>
                                                                    <option value="Yes" <?php if ($property_locks == 'Yes') {
                                                                                            echo 'selected';
                                                                                        } ?>>Yes
                                                                    </option>
                                                                </select>
                                                                <span class="lbl"> Locks</span>
                                                            </label>
                                                        </div>
                                                        <div>
                                                            <label>
                                                                <?php $property_locks_volume = get_post_meta($product_id, 'property_locks_volume', true) ?>
                                                                <span class="lbl">How Many sets? (please specify
                                                                    position on the comments field)</span>
                                                                <input type="number" id="property_locks_volume"
                                                                    name="property_locks_volume"
                                                                    value="<?php echo $property_locks_volume; ?>">
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <hr />
                                                        <button class="btn btn-info show-next-panel">
                                                            Next
                                                            <i class="fa fa-chevron-right"></i>
                                                        </button>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>

                                    <div class="panel panel-default">
                                        <div class="panel-heading" role="tab" id="headingLayout">
                                            <h4 class="panel-title">
                                                <a role="button" class="" data-toggle="collapse"
                                                    data-parent="#accordion" href="#collapseLayout" aria-expanded="true"
                                                    aria-controls="collapseLayout">
                                                    Confirm Drawing
                                                </a>
                                            </h4>
                                        </div>
                                        <div id="collapseLayout" class="panel-collapse collapse drawing-panel"
                                            role="tabpanel" aria-labelledby="headingLayout">
                                            <div class="panel-body">
                                                <!-- drawing -->
                                                <div class="row">
                                                    <div class="col-lg-8 col-md-11 col-sm-12">
                                                        <div style="display: none">
                                                            <textarea id="drawingConfig"
                                                                style="width:300px;height:150px;"></textarea>
                                                            <input id="splitHeight" type="number" name="quantity_split"
                                                                min="0" max="5000" step="10">
                                                            <button id="runButton">Run</button>
                                                            <textarea id="drawingConfigScaled"
                                                                style="width:300px;height:150px;"></textarea>
                                                            <button id="runButtonScaled">Run</button>
                                                        </div>
                                                        <div id="canvas_container1" class="canvas_container"
                                                            style="min-height: 500px;border: 1px solid #aaa;background-image: url('/wp-content/plugins/shutter-module/imgs//drawing_graph.png');">

                                                        </div>
                                                        <br />
                                                        <button class="btn btn-info print-drawing" style="z-index: 10;">
                                                            <i class="fa fa-print"></i> Print
                                                        </button>
                                                        <textarea id="shutter_svg" name="shutter_svg"
                                                            style="display:none"></textarea>
                                                    </div>
                                                    <div class="col-lg-4 col-md-6">
                                                        Comments:
                                                        <br />
                                                        <textarea id="comments_customer" name="comments_customer"
                                                            rows="5"
                                                            style="width: 100%"><?php echo get_post_meta($product_id, 'comments_customer', true); ?></textarea>
                                                        <hr />
                                                        <div id="nowarranty" style="display:none">
                                                            I accept that there is no warranty for this item
                                                            <br />
                                                            <input id="property_nowarranty" name="property_nowarranty"
                                                                type="checkbox" value="Yes" />

                                                        </div>
                                                        <div class="quantity">
                                                            Number of this
                                                            <input id="quantity" class="input-text qty text" min="1"
                                                                max="" name="quantity" value="<?php echo $quant; ?>"
                                                                title="Qty" size="4" pattern="[0-9]*"
                                                                inputmode="numeric" aria-labelledby="" type="number">
                                                            <input type="hidden" name="order_item_id"
                                                                value="<?php echo $item_id; ?>">
                                                        </div>
                                                        <input id="page_title" name="page_title" type="hidden"
                                                            value="<?php echo get_the_title(); ?>" />
                                                        <input class="" id="panels_left_right" name="panels_left_right"
                                                            type="hidden"
                                                            value="<?php echo strtoupper(get_post_meta($product_id, 'panels_left_right', true)); ?>" />
                                                        <button class="btn btn-primary update-btn-admin">Update Product
                                                            <i class="fa fa-chevron-right"></i>
                                                        </button>
                                                        <img src="/wp-content/uploads/2018/06/Spinner-1s-200px.gif"
                                                            alt="" class="spinner" style="display:none;" />
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <!-- the following is used as a prototype in order to add new fields while adding a layout code -->
                        <div class="col-sm-2" id="extra-column" style="display: none">
                            <span class="extra-column-label">Label</span>:
                            <br />

                            <div class="input-group">
                                <span data-placement="top" data-toggle="tooltip" title="" class="input-group-addon"
                                    data-original-title="Type in the layout code">?</span>
                                <input type="text" name="property_extra_column" id="property_extra_column"
                                    class="input-small">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">

                            </div>
                        </div>
                        <hr />

                        <style type="text/css">
                        .input-group-addon-container {
                            float: left
                        }

                        .input-group-addon-container .input-group-addon {
                            height: 30px;
                            border-left: 1px solid #cccccc;
                        }

                        #add-product-single-form .input-group {
                            width: 100%
                        }

                        #choose-style label>input {
                            /* HIDE RADIO */
                            /* display: none; */
                        }

                        #choose-style label {
                            display: block;
                            float: left;
                            width: 100px;
                            text-align: center;
                            border: 2px solid gray;
                            margin-right: 1em;
                            font-size: 10px;
                            font-weight: bold;
                            background-color: #ced3e4;
                            border-radius: 7px;
                            height: 170px;
                        }

                        #choose-style label>input+img {
                            /* IMAGE STYLES */
                            cursor: pointer;
                            border: 4px solid transparent;
                        }

                        #choose-style label>input:checked+img {
                            /* (CHECKED) IMAGE STYLES */
                            border: 4px solid #438EB9;
                        }

                        #choose-frametype label>input {
                            /* HIDE RADIO */
                            /* display: none; */
                        }

                        #choose-frametype label {
                            display: block;
                            float: left;
                            width: 100px;
                            text-align: center;
                            border: 2px solid gray;
                            background-color: #ced3e4;
                            border-radius: 7px;
                            margin-right: 1em;
                            font-size: 10px
                        }

                        #choose-frametype label>input+img {
                            /* IMAGE STYLES */
                            cursor: pointer;
                            border: 4px solid transparent;
                        }

                        #choose-frametype label>input:checked+img {
                            /* (CHECKED) IMAGE STYLES */
                            border: 4px solid #438EB9;
                        }

                        .tpost-type label>input+img {
                            /* IMAGE STYLES */
                            cursor: pointer;
                            border: 4px solid transparent;
                        }

                        .tpost-type label>input:checked+img {
                            /* (CHECKED) IMAGE STYLES */
                            border: 4px solid #438EB9;
                        }

                        .extra-column input {
                            width: 4em;
                        }

                        .extra-column {
                            padding-right: 0.5em;
                        }

                        /* .extra-columns-row .pull-left.extra-column {
                                display: inline-block;
                            } */

                        div.error-field {
                            display: block
                        }

                        .error-field,
                        input.error-field {
                            border: 2px solid red;
                        }

                        .error-text {
                            color: red;
                            font-weight: bold;
                        }

                        .select2-result-label img,
                        .select2-container img {
                            display: inline;
                        }

                        .select2-container#s2id_property_style .select2-choice,
                        #s2id_property_style .select2-result {
                            height: 57px;
                        }

                        .extra-column-label {
                            font-size: 0.8em
                        }

                        input.layout-code {
                            font-size: 0.8em;
                        }

                        input.property-select {
                            /* display: block !important; */
                        }

                        .collapse {
                            /* display: block; */
                        }

                        .btn.btn-info.show-next-panel {
                            /* display: none; */
                        }

                        .modal {
                            top: 20%;
                        }

                        #property_layoutcode {
                            text-transform: uppercase;
                        }

                        select.b-angle-select {
                            display: none !important;
                        }
                        </style>

                        <div class="show-prod-info">

                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Start -->
    <div id="errorModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="errorModalLabel"
        aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3 id="errorModalLabel">Width and height errors</h3>
                </div>
                <div class="modal-body">
                </div>
                <div class="modal-footer">
                    <button class="btn btn-close" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal End -->

</div>
</div>