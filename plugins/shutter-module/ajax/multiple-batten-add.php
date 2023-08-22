<?php

// Check if all necessary data is set
if (!isset($_POST['prod']) || !isset($_POST['prod'])) {
	wp_send_json_error('Missing product data', 400);
}

$atribute = get_post_meta(1, 'attributes_array', true);

// Ensure prod data is an array
if (!is_array($_POST['prod'])) {
	$_POST['prod'] = array($_POST['prod']);
}

// Loop through each serialized product string
foreach ($_POST['prod'] as $product_serialize) {
	// Parse serialized product string into array
	parse_str($product_serialize, $products);

// Initialize new array for cleaned product names
	$cleaned_products = [];

// Loop through each product in array
	foreach ($products as $key => $value) {
		// Check if the product key ends with '_1', '_2', etc.
		if (preg_match('/_[0-9]+$/', $key)) {
			// If so, remove the trailing '_1', '_2', etc. and save the product to the new array
			$cleaned_key = preg_replace('/_[0-9]+$/', '', $key);
			$cleaned_products[$cleaned_key] = $value;
		} else {
			// If not, simply save the product to the new array as is
			$cleaned_products[$key] = $value;
		}
	}

//WC()->cart->calculate_totals(); // Force cart update

// Replace the original $products array with the cleaned array
	$products = $cleaned_products;

	echo "\n";
	print_r($products); // Only for print array
	echo "\n";

	echo "\n--------------------" . $products['page_title'];

	$user_id = get_current_user_id();
	$dealer_id = get_user_meta($user_id, 'company_parent', true);

	$post = array(
		'post_author' => $user_id,
		'post_content' => '',
		'post_status' => "publish",
		'post_title' => $products['page_title'] . '-' . $products['property_room_other'],
		'post_parent' => '',
		'post_type' => "product",
	);

//Create post product

	$post_id = wp_insert_post($post);
//$post_id = 3;  // test id

	echo "\nVOLUM:";
	print_r($products['property_total']);

	wp_set_object_terms($post_id, 'simple', 'product_type');
	update_post_meta($post_id, 'shutter_category', $products['page_title']);

	update_post_meta($post_id, '_visibility', 'visible');
	update_post_meta($post_id, '_stock_status', 'instock');

	update_post_meta($post_id, '_purchase_note', "");
	update_post_meta($post_id, '_featured', "no");
	update_post_meta($post_id, '_sku', "");
	update_post_meta($post_id, '_sale_price_dates_from', "");
	update_post_meta($post_id, '_sale_price_dates_to', "");

	update_post_meta($post_id, '_sold_individually', "");
	update_post_meta($post_id, '_manage_stock', "no");
	update_post_meta($post_id, '_backorders', "no");
	update_post_meta($post_id, '_stock', "50");
	update_post_meta($post_id, '_stock_status', 'instock'); // stock status
	update_post_meta($post_id, 'batten_type', $products['batten_type']);

	update_post_meta($post_id, 'property_volume', $products['property_total']);

	if (!empty($products['order_item_id'])) {
		wc_update_order_item_meta($products['order_item_id'], '_qty', $products['quantity']);
	}

	$sum = 0;
	$i = 0;

	$discount_custom = get_user_meta($user_id, 'discount_custom', true);

	$batten_price = get_post_meta(1, 'BattenStandard', true);
	$battenCustom_price = get_post_meta(1, 'BattenCustom', true);

	$batten_type = get_post_meta($post_id, 'batten_type', true);

// Calculate price

//  if ($batten_type == 'custom') {
	if (!empty(get_user_meta($user_id, 'BattenCustom', true)) || (get_user_meta($user_id, 'BattenCustom', true) > 0)) {
		if ($user_id == 18) {
			$sum = ($products['property_total'] * get_user_meta($user_id, 'BattenCustom', true)) + (($products['property_total'] * get_user_meta($user_id, 'Batten', true)) * get_user_meta($user_id, 'Earth_tax', true)) / 100;
			echo "\n SUM BattenCustom: " . $sum . "<br>";
			$basic = $products['property_total'] * get_user_meta($user_id, 'BattenCustom', true);
			echo "\n BASIC 1: " . $basic . "<br>";
		} else {
			$sum = $products['property_total'] * get_user_meta($user_id, 'BattenCustom', true);
			echo "\n SUM BattenCustom: " . $sum . "<br>";
			$basic = $products['property_total'] * get_user_meta($user_id, 'BattenCustom', true);
			echo "\n BASIC 1: " . $basic . "<br>";
		}
	} else {
		$sum = $products['property_total'] * get_post_meta(1, 'BattenCustom', true);
		echo "\n SUM BattenCustom: " . $sum . "<br>";
		$basic = $products['property_total'] * get_post_meta(1, 'BattenCustom', true);
		echo "\n BASIC 1: " . $basic . "<br>";
	}

// ======== Start - special price for gren colors for user Perfect Shutters =========

// Colors 20%
	if ($user_id == 274 || $dealer_id == 274) {
		if (($products['property_shuttercolour'] == 101) || ($products['property_shuttercolour'] == 103) || ($products['property_shuttercolour'] == 104) || ($products['property_shuttercolour'] == 105) || ($products['property_shuttercolour'] == 106) || ($products['property_shuttercolour'] == 107) || ($products['property_shuttercolour'] == 108) || ($products['property_shuttercolour'] == 109) || ($products['property_shuttercolour'] == 110) || ($products['property_shuttercolour'] == 111) || ($products['property_shuttercolour'] == 112) || ($products['property_shuttercolour'] == 113) || ($products['property_shuttercolour'] == 114) || ($products['property_shuttercolour'] == 115) || ($products['property_shuttercolour'] == 116) || ($products['property_shuttercolour'] == 117) || ($products['property_shuttercolour'] == 118) || ($products['property_shuttercolour'] == 119) || ($products['property_shuttercolour'] == 120) || ($products['property_shuttercolour'] == 121)) {
			if (!empty(get_user_meta($user_id, 'Colors', true)) || (get_user_meta($user_id, 'Colors', true) > 0)) {
				$sum = $sum + (get_user_meta($user_id, 'Colors', true) * $basic) / 100;
				echo 'SUM Colors: ' . $sum . "<br>";
				echo 'BASIC 11: ' . $basic . "<br>";
			} else {
				$sum = $sum + (get_post_meta(1, 'Colors', true) * $basic) / 100;
				echo 'SUM Colors: ' . $sum . "<br>";
				echo 'BASIC 11: ' . $basic . "<br>";
			}
		}
	}
// ======== END - special price for gren colors for user Perfect Shutters =========

// Colors 20%
	if (($products['property_shuttercolour'] == 264) || ($products['property_shuttercolour'] == 265) || ($products['property_shuttercolour'] == 266) || ($products['property_shuttercolour'] == 267) || ($products['property_shuttercolour'] == 268) || ($products['property_shuttercolour'] == 269) || ($products['property_shuttercolour'] == 270) || ($products['property_shuttercolour'] == 271) || ($products['property_shuttercolour'] == 272) || ($products['property_shuttercolour'] == 273) || ($products['property_shuttercolour'] == 128) || ($products['property_shuttercolour'] == 257) || ($products['property_shuttercolour'] == 127) || ($products['property_shuttercolour'] == 126) || ($products['property_shuttercolour'] == 220) || ($products['property_shuttercolour'] == 130) || ($products['property_shuttercolour'] == 253) || ($products['property_shuttercolour'] == 131) || ($products['property_shuttercolour'] == 129) || ($products['property_shuttercolour'] == 254) || ($products['property_shuttercolour'] == 132) || ($products['property_shuttercolour'] == 255) || ($products['property_shuttercolour'] == 134) || ($products['property_shuttercolour'] == 122) || ($products['property_shuttercolour'] == 123) || ($products['property_shuttercolour'] == 133) || ($products['property_shuttercolour'] == 256) || ($products['property_shuttercolour'] == 166) || ($products['property_shuttercolour'] == 124) || ($products['property_shuttercolour'] == 125) || ($products['property_shuttercolour'] == 111)) {
		if (!empty(get_user_meta($user_id, 'Colors', true)) || (get_user_meta($user_id, 'Colors', true) > 0)) {
			$sum = $sum + (get_user_meta($user_id, 'Colors', true) * $basic) / 100;
			echo 'SUM Colors: ' . $sum . "<br>";
			echo 'BASIC 11: ' . $basic . "<br>";
		} else {
			$sum = $sum + (get_post_meta(1, 'Colors', true) * $basic) / 100;
			echo 'SUM Colors: ' . $sum . "<br>";
			echo 'BASIC 11: ' . $basic . "<br>";
		}
	}
// Colors 10%
	if (($products['property_shuttercolour'] == 262) || ($products['property_shuttercolour'] == 263) || ($products['property_shuttercolour'] == 274)) {
		$sum = $sum + (10 * $basic) / 100;
		echo 'SUM Colors: ' . $sum . "<br>";
		echo 'BASIC 11: ' . $basic . "<br>";
	}

//    } elseif ($batten_type == 'standard') {
//
//        if (!empty(get_user_meta($user_id, 'BattenStandard', true)) || (get_user_meta($user_id, 'BattenStandard', true) > 0)) {
//            if ($user_id == 18) {
//                $sum = ($products['property_total'] * get_user_meta($user_id, 'BattenStandard', true)) + (($products['property_total'] * get_user_meta($user_id, 'BattenStandard', true)) * get_user_meta($user_id, 'Earth_tax', true)) / 100;
//                echo 'SUM batten: ' . $sum . "<br>";
//                $basic = $products['property_total'] * get_user_meta($user_id, 'BattenStandard', true);
//                echo "BASIC 1: " . $basic . "<br>";
//            } else {
//                $sum = $products['property_total'] * get_user_meta($user_id, 'BattenStandard', true);
//                echo 'SUM batten: ' . $sum . "<br>";
//                $basic = $products['property_total'] * get_user_meta($user_id, 'BattenStandard', true);
//                echo "BASIC 1: " . $basic . "<br>";
//            }
//        } else {
//            $sum = $products['property_total'] * get_post_meta(1, 'BattenStandard', true);
//            echo 'SUM batten: ' . $sum . "<br>";
//            $basic = $products['property_total'] * get_post_meta(1, 'BattenStandard', true);
//            echo "BASIC 1: " . $basic . "<br>";
//        }
//
//    }

//    $sum = $products['property_total'] * 5000;
//
//    echo 'SUM 1: ' . $sum . "<br>";
//    $basic = $products['property_total'] * 5000;
//    echo "BASIC 1: " . $basic . "<br>";

	echo "Suma_total: " . $sum;

	$product_attributes = array();
	foreach ($products as $name_attr => $id) {
		update_post_meta($post_id, $name_attr, $id);
		if (!is_array($id)) {
			if ($name_attr == "property_width") {
				$width = $id;
				echo "\ngasit width";
				echo '<br>' . $width . "<br>";
			}
			if ($name_attr == "property_height") {
				$height = $id;
				echo "\ngasit height";
				echo '<br>' . $height . "<br>";
			}

			$name_attr_parts = explode("_", $name_attr);
			$name_attr_string = '';

			for ($i = 0; $i < 3; $i++) {
				if (isset($name_attr_parts[$i])) {
					$name_attr_string .= ' ' . $name_attr_parts[$i];
				}
			}

			$name_attr_string = trim($name_attr_string);

			if (array_key_exists($id, $atribute)) {
				if ($name_attr == "product_id" || $name_attr == "property_width" || $name_attr == "property_height" || $name_attr == "property_midrailheight" || $name_attr == "property_builtout" || $name_attr == "property_layoutcode" || $name_attr == "quantity") {
					$product_attributes[$name_attr_string] = array(
						'name' => wc_clean($name_attr_string),
						'value' => $id,
						'position' => $i,
						'is_visible' => 1,
						'is_variation' => 0,
						'is_taxonomy' => 0,
					);
					$i++;
				} else {
					$product_attributes[$name_attr_string] = array(
						'name' => wc_clean($name_attr_string),
						'value' => $atribute[$id],
						'position' => $i,
						'is_visible' => 1,
						'is_variation' => 0,
						'is_taxonomy' => 0,
					);
					$i++;
				}
			} else {
				$product_attributes[$name_attr_string] = array(
					'name' => wc_clean($name_attr_string),
					'value' => $id,
					'position' => $i,
					'is_visible' => 1,
					'is_variation' => 0,
					'is_taxonomy' => 0,
				);
				$i++;
			}
		}
	}

	unset($product_attributes['shutter_svg']);
	update_post_meta($post_id, '_product_attributes', $product_attributes);
//echo "<pre>";
//print_r($product_attributes);
//echo "</pre>";
	echo "\n Total product price: " . $sum . " Euro";

	update_post_meta($post_id, '_price', floatval($sum));
	update_post_meta($post_id, '_regular_price', floatval($sum));
	update_post_meta($post_id, '_sale_price', floatval($sum));

	/*  **********************************************************************
	--------------------------   START - Calcul Suma Dolari --------------------------
	**********************************************************************  */

	$sum_dolar = 0;
	$i = 0;

	$batten_price_dolar = get_post_meta(1, 'BattenStandard-dolar', true);

	$discount_custom = get_user_meta($user_id, 'discount_custom', true);

// Calculate price
	if (!empty(get_user_meta($user_id, 'BattenStandard-dolar', true)) || (get_user_meta($user_id, 'BattenStandard-dolar', true) > 0)) {
		if ($user_id == 18) {
			$sum_dolar = ($products['property_total'] * get_user_meta($user_id, 'BattenStandard-dolar', true)) + (($products['property_total'] * get_user_meta($user_id, 'BattenStandard-dolar', true)) * get_user_meta($user_id, 'Earth_tax', true)) / 100;
			echo "\n SUM Earth: " . $sum_dolar . "<br>";
			$basic = $products['property_total'] * get_user_meta($user_id, 'BattenStandard-dolar', true);
			echo "\n BASIC 1: " . $basic . "<br>";
		} else {
			$sum_dolar = $products['property_total'] * get_user_meta($user_id, 'BattenStandard-dolar', true);
			echo "\n SUM Earth: " . $sum_dolar . "<br>";
			$basic = $products['property_total'] * get_user_meta($user_id, 'BattenStandard-dolar', true);
			echo "\n BASIC 1: " . $basic . "<br>";
		}
	} else {
		$sum_dolar = $products['property_total'] * get_post_meta(1, 'BattenStandard-dolar', true);
		echo "\n SUM Earth: " . $sum_dolar . "<br>";
		$basic = $products['property_total'] * get_post_meta(1, 'BattenStandard-dolar', true);
		echo "\n BASIC 1: " . $basic . "<br>";
	}

//    $sum_dolar = $products['property_total'] * 4300;
//    echo 'SUM 1: ' . $sum . "<br>";
//    $basic = $products['property_total'] * 4300;
//    echo "BASIC 1: " . $basic . "<br>";

	echo "\n Suma_total: " . $sum_dolar;

	echo "\n Total product price: " . $sum_dolar . " Euro";

	update_post_meta($post_id, 'dolar_price', $sum_dolar);

	/*  **********************************************************************
	--------------------------   END - Calcul Suma Dolari --------------------
	**********************************************************************  */

	global $woocommerce;

	echo "\n";
	echo ' -add to cart batten- ';
	echo "\n";

	/**
	 * Add a delay or a callback after the add_to_cart function. Depending on how the Ajax call is implemented, you might add a delay or callback function to ensure the cart is updated before the next operation is performed.
	 *
	 * Update the cart manually. You can call WC()->cart->calculate_totals(); right after WC()->cart->add_to_cart();. This will force an update on the cart, and should then reflect the added product(s) when you call WC()->cart->get_cart();
	 */

//WC()->cart->calculate_totals(); // Force cart update
//sleep(2);  // Add a 2 second delay

	WC()->cart->add_to_cart($post_id, $products['quantity']);
	echo "\nAdded to Cart - " . $post_id;

	$color = 0;

	$cart = WC()->cart->get_cart();

	echo "\n specific_id id: " . $post_id;

	if (!empty($cart)) {
		$products_cart = array();
		foreach ($cart as $cart_item_key => $cart_item) {
			$product_id = $cart_item['product_id'];
			$products_cart[] = $product_id;
			// Check for specific product IDs and change quantity

			$col = get_post_meta($post_id, 'other_color', true);
			$color = intval($color) + intval($col);
		}
// add custom color as separate product
		$property_shuttercolour_other = get_post_meta($post_id, 'property_shuttercolour_other', true);
		if (!empty($property_shuttercolour_other)) {

			$other_color_prod_id = 337;
			if (!in_array($other_color_prod_id, $products_cart)) {
				echo "\n";
				echo ' -add other color- create';
				echo "\n";

				WC()->cart->add_to_cart($other_color_prod_id, 1);
			}
		}
		// set shipping class default for product
		$product = wc_get_product($product_id);
		$int_shipping = get_term_by('slug', 'ship', 'product_shipping_class');
		$product->set_shipping_class_id($int_shipping->term_id);
		$product->save();
	}
}