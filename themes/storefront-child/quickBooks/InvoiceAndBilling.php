<?php

$path = preg_replace('/wp-content(?!.*wp-content).*/', '', __DIR__);
include($path . 'wp-load.php');
$config = include('config.php');

require_once(__DIR__ . '/vendor/autoload.php');

session_start();

use QuickBooksOnline\API\DataService\DataService;
use QuickBooksOnline\API\Facades\Account;
use QuickBooksOnline\API\Facades\Customer;
use QuickBooksOnline\API\Facades\Invoice;
use QuickBooksOnline\API\Facades\Item;

$dataService = DataService::Configure(array(
    'auth_mode' => 'oauth2',
    'ClientID' => $config['client_id'],
    'ClientSecret' => $config['client_secret'],
    'RedirectURI' => $config['oauth_redirect_uri'],
    'scope' => $config['oauth_scope'],
    'baseUrl' => "production"
));

$OAuth2LoginHelper = $dataService->getOAuth2LoginHelper();
//    $refreshedAccessTokenObj = $OAuth2LoginHelper->refreshToken();
//    $dataService->updateOAuth2Token($refreshedAccessTokenObj);
//
//    $_SESSION['sessionAccessToken'] = $refreshedAccessTokenObj;
//    if (!empty($_SESSION['sessionAccessToken'])) {
//
//        $accessToken = $_SESSION['sessionAccessToken'];
//
//        update_post_meta(1, 'accessTokenQB', $accessToken->getAccessToken());
//        update_post_meta(1, 'sessionAccessToken', array('session' => $_SESSION['sessionAccessToken']));
//
//    }

//Import Facade classes you are going to use here
//For example, if you need to use Customer, add

//    function invoiceAndBilling()
//{
/*  This sample performs the folowing functions:
 1.   Add a customer
 2.   Add an item
 3    Create invoice using the information above
 4.   Email invoice to customer
 5.   Receive payments for the invoice created above
*/

$sessionAccessToken = get_post_meta(1, 'sessionAccessToken', true);
print_r($sessionAccessToken);

if (!empty($sessionAccessToken)) {
    $accessToken = $sessionAccessToken['session'];
} else {
    $accessToken = $_SESSION['sessionAccessToken'];
}
//$accessToken = $_SESSION['sessionAccessToken'];

// $accessToken = $_SESSION['sessionAccessToken'];
// print_r($accessToken);
$dataService->throwExceptionOnError(true);
/*
 * Update the OAuth2Token of the dataService object
 */
$dataService->updateOAuth2Token($accessToken);

$dataService->setLogLocation("/Users/ksubramanian3/Desktop/HackathonLogs");

/*
 * 1. Get Order info and set lines for invoice qquickbooks transform
 */

print_r($_POST);

$order_id = $_POST['id_ord_original'];
$user_id = get_current_user_id();
$user_id_customer = get_post_meta($order_id, '_customer_user', true);
$delivery_type = '';

$QB_invoice = get_post_meta($order_id, 'QB_invoice', true);
$billing_company = get_post_meta($post_id, '_billing_company', true);

if ($QB_invoice == true) {
    echo 'already exists';
} else {

    $order = wc_get_order($order_id);
    $order_data = $order->get_data();
    $items = $order->get_items();
    $_product = '';
//    $order_total = wc_format_decimal($order->get_total(), 2);
//    $vat = $order_data['total_tax'];
    $order_total = number_format((double)$order->get_total(), 2);
    $vat = number_format($order_data['total_tax'], 2);

    $country_code = WC()->countries->countries[$order->get_shipping_country()];
//    print_r($country_code);
    if ($country_code == 'United Kingdom (UK)' || $country_code == 'Ireland') {
        $tax_rate = 15;
    } else {
        $tax_rate = 26;
    }

    $json = array();
    $lines = array();
    $line_color = array();

    $earth_array = array();
    $green_array = array();
    $biowood_array = array();
    $supreme_array = array();
    $ecowood_array = array();

    foreach ($items as $item_id => $item_data) {

        $product_id = $item_data['product_id'];
        $property_total = get_post_meta($product_id, 'property_total', true);
        $property_material = get_post_meta($product_id, 'property_material', true);
        $property_category = get_post_meta($product_id, 'shutter_category', true);
        $price = get_post_meta($product_id, '_price', true);
        $material_price = '';

        if ($property_material == 187) {
            if (!empty(get_user_meta($user_id, 'Earth', true)) || (get_user_meta($user_id, 'Earth', true) > 0)) {
                $material_price = get_user_meta($user_id, 'Earth', true);
            } else {
                $material_price = get_post_meta(1, 'Earth', true);
            } /*teo Earth price*/
            $base_price_earth = get_post_meta($product_id, 'basic_earth_price', true);
            if (!empty($base_price_earth) || $base_price_earth !== '') {
                $material_price = $base_price_earth;
            }
            $earth_array[$product_id] = $item_data;

        }
        if ($property_material == 137) {
            if (!empty(get_user_meta($user_id, 'Green', true)) || (get_user_meta($user_id, 'Green', true) > 0)) {
                $material_price = get_user_meta($user_id, 'Green', true);
            } else {
                $material_price = get_post_meta(1, 'Green', true);
            } /*teo Earth price*/
            $green_array[$product_id] = $item_data;
        }
        if ($property_material == 138) {
            if (!empty(get_user_meta($user_id, 'Biowood', true)) || (get_user_meta($user_id, 'Biowood', true) > 0)) {
                $material_price = get_user_meta($user_id, 'Biowood', true);
            } else {
                $material_price = get_post_meta(1, 'Biowood', true);
            } /*teo Earth price*/
            $biowood_array[$product_id] = $item_data;
        }
        if ($property_material == 139) {
            if (!empty(get_user_meta($user_id, 'Supreme', true)) || (get_user_meta($user_id, 'Supreme', true) > 0)) {
                $material_price = get_user_meta($user_id, 'Supreme', true);
            } else {
                $material_price = get_post_meta(1, 'Supreme', true);
            } /*teo Earth price*/
            $supreme_array[$product_id] = $item_data;
        }
        if ($property_material == 188) {
            if (!empty(get_user_meta($user_id, 'Ecowood', true)) || (get_user_meta($user_id, 'Ecowood', true) > 0)) {
                $material_price = get_user_meta($user_id, 'Ecowood', true);
            } else {
                $material_price = get_post_meta(1, 'Ecowood', true);
            } /*teo Earth price*/
            $ecowood_array[$product_id] = $item_data;
        }

//        green 137 - biowood 138 - supreme 139 - earth 187 - ecowood 188

        // Make an array with materials array( material => id material from QuickBooks )
        //$qb_materials = array('137' => 24, '138' => 8, '139' => 13, '187' => 71, '188' => 67);

        // If order have Other colour item  - QB id - 42
        if ($product_id == 337 || $product_id == 72951) {
            $SalesItemLineDetail = array();

            $line_color['Amount'] = 131.25;
            $line_color['DetailType'] = "SalesItemLineDetail";
            $line_color['SalesItemLineDetail']['Qty'] = 1;
            $line_color['SalesItemLineDetail']['UnitPrice'] = 131.25;
            $line_color['SalesItemLineDetail']['ItemRef'] = array('value' => 60);
            $line_color['SalesItemLineDetail']['TaxCodeRef'] = array('value' => $tax_rate);
        }

        // sandbox 4620816365021649600
        // MultiPanel Display 3098 - Twin Sample Bags 1020 - Spare Parts Box 1026  - Painted Color Swatches 1030 - Stained Color Samples 1032
        // OLD $qb_pos = array('3098' => 73, '1020' => 57, '1026' => 74, '1030' => 76, '1032' => 75);
        //ID: 74874 - Biowood Large Sample Panel
        //ID: 17065 - Ecowood Large Sample Panel
        //ID: 74880 - Earth Large Aluminium Sample Panel
        //ID: 74886 - Earth Large Aluminium Sample Panel

        $qb_pos = array('3098' => 73, '1020' => 57, '17069' => 72, '1026' => 74, '17051' => 81, '1032' => 75, '1030' => 76, '17057' => 79, '17061' => 77, '17059' => 78, '17065 ' => 82, '17063 ' => 83, '17067' => 80, '17277' => 58);

        // If order have POS items - QB id - 42
        if (array_key_exists($product_id, $qb_pos)) {

            $SalesItemLineDetail = array();

            $line['Amount'] = number_format($price, 2, '.', '') * $item_data['quantity'];
            $line['DetailType'] = "SalesItemLineDetail";
            $line['SalesItemLineDetail']['Qty'] = 1;
            $line['SalesItemLineDetail']['UnitPrice'] = number_format($price, 2, '.', '') * $item_data['quantity'];
            $line['SalesItemLineDetail']['ItemRef'] = array('value' => $qb_pos[$product_id]);
            $line['SalesItemLineDetail']['TaxCodeRef'] = array('value' => $tax_rate);

            $lines[] = $line;

        }

        // Set delivery type
        $_product = wc_get_product($product_id);

    }

    $nr_black = array('24' => 0, '8' => 0, '13' => 0, '71' => 0, '67' => 0);
    $nr_batten = array('24' => 0, '8' => 0, '13' => 0, '71' => 0, '67' => 0);
    $nr_shutter = array('24' => 0, '8' => 0, '13' => 0, '71' => 0, '67' => 0);

    $materials_array = array($earth_array, $green_array, $biowood_array, $supreme_array, $ecowood_array);
    // sandbox 4620816365021649600
    $array_materials = array('24' => 'Green', '8' => 'Biowood', '13' => 'Supreme', '71' => 'Earth', '67' => 'Ecowood');
    //        green 137 - biowood 138 - supreme 139 - earth 187 - ecowood 188
    //$qb_materials = array('137' => 35, '138' => 31, '139' => 36, '187' => 33, '188' => 34, '3098' => 37, '1020' => 38, '1026' => 39, '1032' => 40, '1030' => 41, '337'=>42);
    $qb_materials = array('137' => 24, '138' => 8, '139' => 13, '187' => 71, '188' => 67, '3098' => 73, '1020' => 57, '1026' => 74, '1030' => 76, '1032' => 75, '337' => 60);
    foreach ($materials_array as $material_items) {
        $amount = 0;
        $amount_cat_black = 0;
        $amount_cat_batten = 0;
        $property_material = '';
        // [$product_id] = $item_data;

        // Aici se calculeaza sumele
        foreach ($material_items as $product_id => $item_data) {
            $price = get_post_meta($product_id, '_price', true);
            $property_material = get_post_meta($product_id, 'property_material', true);

            $property_category = get_post_meta($product_id, 'shutter_category', true);

            $sqm = get_post_meta($product_id, 'property_total', true);
            $quantity = get_post_meta($product_id, 'quantity', true);
            if ($sqm > 0) {
                $train_price = get_user_meta($user_id_customer, 'train_price', true);
                if (empty($train_price) && !is_numeric($train_price)) {
                    $train_price = get_post_meta(1, 'train_price', true);
                }
                $new_price = floatval($sqm * 1) * floatval($train_price);
            } else {
                $new_price = 0;
            }
            // $new_price = 0;
            $price = number_format($price + $new_price, 2, '.', '');


            if ($property_category == 'Shutter & Blackout Blind') {
                $amount_cat_black = $amount_cat_black + floatval($price) * $item_data['quantity'];
            } elseif ($property_category == 'Batten') {
                $amount_cat_batten = $amount_cat_batten + floatval($price) * $item_data['quantity'];
            } else {
                $amount = $amount + (floatval($price) * $item_data['quantity']);
            }
        }

//            if (array_key_exists($property_material, $qb_materials)) {
//                $material_id = $qb_materials[$property_material];
//
//                $SalesItemLineDetail = array();
//
//                if ($property_category != 'Shutter & Blackout Blind' || $property_category != 'Batten') {
//                    $line['Amount'] = number_format($amount, 2, '.', '');
//                    $line['Description'] = "";
//                    $line['DetailType'] = "SalesItemLineDetail";
//                    $line['SalesItemLineDetail']['Qty'] = 1;
//                    $line['SalesItemLineDetail']['UnitPrice'] = number_format($amount, 2, '.', '');
//                    $line['SalesItemLineDetail']['ItemRef'] = array('value' => $material_id);
//                    $line['SalesItemLineDetail']['TaxCodeRef'] = array('value' => 15);
//
//                    $lines[] = $line;
//                }
//            }

        // Aici se printeaza pentru QB
        foreach ($material_items as $product_id => $item_data) {
            $price = get_post_meta($product_id, '_price', true);
            $property_material = get_post_meta($product_id, 'property_material', true);

            $property_category = get_post_meta($product_id, 'shutter_category', true);

            $sqm = get_post_meta($product_id, 'property_total', true);
            $quantity = get_post_meta($product_id, 'quantity', true);
            if ($sqm > 0) {
                $train_price = get_user_meta($user_id_customer, 'train_price', true);
                if (empty($train_price) && !is_numeric($train_price)) {
                    $train_price = get_post_meta(1, 'train_price', true);
                }
                $new_price = floatval($sqm * $quantity) * floatval($train_price);
            } else {
                $new_price = 0;
            }
            // $new_price = 0;
            $price = number_format($price + $new_price, 2, '.', '');

            if ($property_category == 'Shutter & Blackout Blind') {
                if (array_key_exists($property_material, $qb_materials)) {
                    $material_id = $qb_materials[$property_material];
                    if ($nr_black[$material_id] != 1) {
                        $line['Amount'] = number_format($amount_cat_black, 2, '.', '');
                        $line['Description'] = "Shutter & Blackout Blind";
                        $line['DetailType'] = "SalesItemLineDetail";
                        $line['SalesItemLineDetail']['Qty'] = 1;
                        $line['SalesItemLineDetail']['UnitPrice'] = number_format($amount_cat_black, 2, '.', '');
                        $line['SalesItemLineDetail']['ItemRef'] = array('value' => $material_id);
                        $line['SalesItemLineDetail']['TaxCodeRef'] = array('value' => $tax_rate);

                        $lines[] = $line;

                        $nr_black[$material_id] = 1;
                    }
                }
            } elseif ($property_category == 'Batten') {
                if (array_key_exists($property_material, $qb_materials)) {
                    $material_id = $qb_materials[$property_material];
                    if ($nr_batten[$material_id] != 1) {
                        $line['Amount'] = number_format($amount_cat_batten, 2, '.', '');
                        $line['Description'] = $array_materials[$material_id];
                        $line['DetailType'] = "SalesItemLineDetail";
                        $line['SalesItemLineDetail']['Qty'] = 1;
                        $line['SalesItemLineDetail']['UnitPrice'] = number_format($amount_cat_batten, 2, '.', '');
                        $line['SalesItemLineDetail']['ItemRef'] = array('value' => 85);
                        $line['SalesItemLineDetail']['TaxCodeRef'] = array('value' => $tax_rate);

                        $lines[] = $line;

                        $nr_batten[$material_id] = 1;
                    }
                }
            } elseif ($property_category != 'Shutter & Blackout Blind' || $property_category != 'Batten') {
                if (array_key_exists($property_material, $qb_materials)) {
                    $material_id = $qb_materials[$property_material];
                    if ($nr_shutter[$material_id] != 1) {
                        $line['Amount'] = number_format($amount, 2, '.', '');
                        $line['Description'] = "";
                        $line['DetailType'] = "SalesItemLineDetail";
                        $line['SalesItemLineDetail']['Qty'] = 1;
                        $line['SalesItemLineDetail']['UnitPrice'] = number_format($amount, 2, '.', '');
                        $line['SalesItemLineDetail']['ItemRef'] = array('value' => $material_id);
                        $line['SalesItemLineDetail']['TaxCodeRef'] = array('value' => $tax_rate);

                        $lines[] = $line;

                        $nr_shutter[$material_id] = 1;
                    }
                }
            }
        }
    }

    if (!empty($line_color)) {
        $lines[] = $line_color;
    }

    // Set delivery type
    $shipclass = $_product->get_shipping_class();
    if ($shipclass === 'air') {
        echo 'Air Delivery fee';
        $delivery_type = 51;
        //$delivery_type = 29;

    } else {
        echo 'Delivery';
        $delivery_type = 16;
        //$delivery_type = 32;

    }
    $delivery_price = $order_data['shipping_total'];

    $line_delivery = array();

    $SalesItemLineDetail = array();

    $line_delivery['Amount'] = number_format($delivery_price, 2, '.', '');
    $line_delivery['DetailType'] = "SalesItemLineDetail";
    $line_delivery['SalesItemLineDetail']['Qty'] = 1;
    $line_delivery['SalesItemLineDetail']['UnitPrice'] = number_format($delivery_price, 2, '.', '');
    $line_delivery['SalesItemLineDetail']['ItemRef'] = array('value' => $delivery_type);
    $line_delivery['SalesItemLineDetail']['TaxCodeRef'] = array('value' => $tax_rate);

    $lines[] = $line_delivery;
    //print_r($lines);

    /* -------------------------------------------------------------- */

    /*
     * 1. Get CustomerRef and ItemRef
     */
    $customerRef = getCustomerObj($dataService, $order_data);
    $itemRef = getItemObj($dataService);

    /*
     * 2. Create Invoice using the CustomerRef and ItemRef
     */

    $total = array(
        "Amount" => $order_total,
        "DetailType" => "SubTotalLineDetail"
    );
    $lines[] = $total;
    $json['DepartmentRef'] = array("value" => "6");
    $json['Line'] = $lines;
    $json['CustomerRef'] = array("value" => $customerRef->Id);
    $json['CustomerMemo'] = array("value" => 'LF0' . $order->get_order_number() . ' - ' . get_post_meta($order_id, 'cart_name', true) . '');
    $json['SalesTermRef'] = array("value" => "8");
    $customer_id = (int)$order->user_id;
    $user_info = get_userdata($customer_id);
    $user_email = $user_info->user_email;
    $json['BillEmail'] = array("Address" => $user_email);
    $json['BillEmailBcc'] = array("Address" => "accounts@lifetimeshutters.com");

    print_r($json);

    $invoiceObj = Invoice::create($json);
    $resultingInvoiceObj = $dataService->Add($invoiceObj);
    $invoiceId = $resultingInvoiceObj->Id;   // This needs to be passed in the Payment creation later
    echo "Created invoice Id={$invoiceId}. Reconstructed response body below:\n";
    $result = json_encode($resultingInvoiceObj, JSON_PRETTY_PRINT);
    print_r($result . "\n\n\n");

    if ($result) {
        update_post_meta($order_id, 'QB_invoice', true);
    }

    /*
     * 3. Email Invoice to customer
     */
    $resultingMailObj = $dataService->sendEmail($resultingInvoiceObj,
        $resultingInvoiceObj->BillEmail->Address);
    echo "Sent mail. Reconstructed response body below:\n";
    $result = json_encode($resultingMailObj, JSON_PRETTY_PRINT);
    print_r($result . "\n\n\n");

}

/*
 * 4. Receive payments for the invoice created above
 */
//    $paymentObj = Payment::create([
//        "CustomerRef" => [
//            "value" => $customerRef->Id
//        ],
//        "TotalAmt" => 100.00,
//        "Line" => [
//            "Amount" => 100.00,
//            "LinkedTxn" => [
//                "TxnId" => $invoiceId,
//                "TxnType" => "Invoice"
//            ]
//        ]
//    ]);
//    $resultingPaymentObj = $dataService->Add($paymentObj);
//    $paymentId = $resultingPaymentObj->Id;
//    echo "Created payment Id={$paymentId}. Reconstructed response body below:\n";
//    $result = json_encode($resultingPaymentObj, JSON_PRETTY_PRINT);
//    print_r($result . "\n\n\n");

//}

/*
   Generate GUID to associate with the sample account names
 */
function getGUID()
{
    if (function_exists('com_create_guid')) {
        return com_create_guid();
    } else {
        mt_srand((double)microtime() * 10000);//optional for php 4.2.0 and up.
        $charid = strtoupper(md5(uniqid(rand(), true)));
        $hyphen = chr(45);// "-"
        $uuid = // "{"
            $hyphen . substr($charid, 0, 8);
        return $uuid;
    }
}

/*
   Find if a customer with DisplayName if not, create one and return
 */
function getCustomerObj($dataService, $order_data)
{

    $customerCompany = $order_data['billing']['company'];
    $customerArray = $dataService->Query("select * from Customer where CompanyName='" . $customerCompany . "'");
    $error = $dataService->getLastError();
    if ($error) {
        logError($error);
    } else {
        if (sizeof($customerArray) > 0) {
            return current($customerArray);
        }
    }

    // Create Customer
    $customerRequestObj = Customer::create([
        "CompanyName" => $customerCompany . getGUID()
    ]);
    $customerResponseObj = $dataService->Add($customerRequestObj);
    $error = $dataService->getLastError();
    if ($error) {
        logError($error);
    } else {
        echo "Created Customer with CompanyName={$customerCompany}.\n\n";
        return $customerResponseObj;
    }
}

/*
   Find if an Item is present , if not create new Item
 */
function getItemObj($dataService)
{

    $itemName = 'Aluminium Shutters';
    $itemArray = $dataService->Query("select * from Item WHERE Name='" . $itemName . "'");
//    $itemId = 'Aluminium Shutters';
//    $itemArray = $dataService->Query("select * from Item WHERE Id='" . $itemId . "'");
    $error = $dataService->getLastError();
    if ($error) {
        logError($error);
    } else {
        if (sizeof($itemArray) > 0) {
            return current($itemArray);
        }
    }

    // Fetch IncomeAccount, ExoenseAccount and AssetAccount Refs needed to create an Item
    $incomeAccount = getIncomeAccountObj($dataService);
    $expenseAccount = getExpenseAccountObj($dataService);
    $assetAccount = getAssetAccountObj($dataService);

    // Create Item
    $dateTime = new \DateTime('NOW');
    $ItemObj = Item::create([
        "Name" => $itemName,
        "Description" => "This is the sales description.",
        "Active" => true,
        "FullyQualifiedName" => "Office Supplies",
        "Taxable" => true,
        "UnitPrice" => 25,
        "Type" => "Inventory",
        "IncomeAccountRef" => [
            "value" => $incomeAccount->Id
        ],
        "PurchaseDesc" => "This is the purchasing description.",
        "PurchaseCost" => 35,
        "ExpenseAccountRef" => [
            "value" => $expenseAccount->Id
        ],
        "AssetAccountRef" => [
            "value" => $assetAccount->Id
        ],
        "TrackQtyOnHand" => true,
        "QtyOnHand" => 100,
        "InvStartDate" => $dateTime
    ]);
    $resultingItemObj = $dataService->Add($ItemObj);
    $itemId = $resultingItemObj->Id;  // This needs to be passed in the Invoice creation later
    echo "Created item Id={$itemId}. Reconstructed response body below:\n";
    $result = json_encode($resultingItemObj, JSON_PRETTY_PRINT);
    print_r($result . "\n\n\n");
    return $resultingItemObj;
}

/*
  Find if an account of Income type exists, if not, create one
*/
function getIncomeAccountObj($dataService)
{

    $accountArray = $dataService->Query("select * from Account where AccountType='" . INCOME_ACCOUNT_TYPE . "' and AccountSubType='" . INCOME_ACCOUNT_SUBTYPE . "'");
    $error = $dataService->getLastError();
    if ($error) {
        logError($error);
    } else {
        if (sizeof($accountArray) > 0) {
            return current($accountArray);
        }
    }

    // Create Income Account
    $incomeAccountRequestObj = Account::create([
        "AccountType" => INCOME_ACCOUNT_TYPE,
        "AccountSubType" => INCOME_ACCOUNT_SUBTYPE,
        "Name" => "IncomeAccount-" . getGUID()
    ]);
    $incomeAccountObject = $dataService->Add($incomeAccountRequestObj);
    $error = $dataService->getLastError();
    if ($error) {
        logError($error);
    } else {
        echo "Created Income Account with Id={$incomeAccountObject->Id}.\n\n";
        return $incomeAccountObject;
    }

}

/*
  Find if an account of "Cost of Goods Sold" type exists, if not, create one
*/
function getExpenseAccountObj($dataService)
{

    $accountArray = $dataService->Query("select * from Account where AccountType='" . EXPENSE_ACCOUNT_TYPE . "' and AccountSubType='" . EXPENSE_ACCOUNT_SUBTYPE . "'");
    $error = $dataService->getLastError();
    if ($error) {
        logError($error);
    } else {
        if (sizeof($accountArray) > 0) {
            return current($accountArray);
        }
    }

    // Create Expense Account
    $expenseAccountRequestObj = Account::create([
        "AccountType" => EXPENSE_ACCOUNT_TYPE,
        "AccountSubType" => EXPENSE_ACCOUNT_SUBTYPE,
        "Name" => "ExpenseAccount-" . getGUID()
    ]);
    $expenseAccountObj = $dataService->Add($expenseAccountRequestObj);
    $error = $dataService->getLastError();
    if ($error) {
        logError($error);
    } else {
        echo "Created Expense Account with Id={$expenseAccountObj->Id}.\n\n";
        return $expenseAccountObj;
    }

}

/*
  Find if an account of "Other Current Asset" type exists, if not, create one
*/
function getAssetAccountObj($dataService)
{

    $accountArray = $dataService->Query("select * from Account where AccountType='" . ASSET_ACCOUNT_TYPE . "' and AccountSubType='" . ASSET_ACCOUNT_SUBTYPE . "'");
    $error = $dataService->getLastError();
    if ($error) {
        logError($error);
    } else {
        if (sizeof($accountArray) > 0) {
            return current($accountArray);
        }
    }

    // Create Asset Account
    $assetAccountRequestObj = Account::create([
        "AccountType" => ASSET_ACCOUNT_TYPE,
        "AccountSubType" => ASSET_ACCOUNT_SUBTYPE,
        "Name" => "AssetAccount-" . getGUID()
    ]);
    $assetAccountObj = $dataService->Add($assetAccountRequestObj);
    $error = $dataService->getLastError();
    if ($error) {
        logError($error);
    } else {
        echo "Created Asset Account with Id={$assetAccountObj->Id}.\n\n";
        return $assetAccountObj;
    }

}

//    $result = invoiceAndBilling();
?>
