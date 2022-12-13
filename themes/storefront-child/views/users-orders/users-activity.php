<!-- *********************************************************************************************
    Start Total user sqm
*********************************************************************************************	-->

<br>

<form action="" method="POST" style="display:block;">
    <div class="row">

        <div class="col-sm-3 col-lg-2 form-group">
            <br>
            <input type="submit"
                   name="submit"
                   value="Select"
                   class="btn btn-info">
        </div>

        <div class="col-sm-4 col-lg-4 form-group">
            <label for="select-luna">Select Year:</label>
            <br>
            <?php
            $theDate = new DateTime('Y');
            $current_year = $theDate->format('Y');
            ?>
            <select id="select-luna" name="an">
                <?php
                for ($an = $current_year; $an >= $current_year - 3; $an--) {
                    ?>
                    <option value="<?php echo $an; ?>" <?php if ($_POST['an'] == $an) echo 'selected'; ?>>
                        <?php echo $an; ?>
                    </option>
                <?php } ?>
            </select>
        </div>
    </div>
</form>

<?php
$start = microtime(true);

$year = date('Y');
if (isset($_POST['an'])) $year = $_POST['an'];
$after = $year . '-01-1 00:00:00';
$before = $year . '-12-31 23:59:59';

global $wpdb;
$tablename = $wpdb->prefix . 'custom_orders';
//$sum_total[date('Y')] = $months_sum;
$myOrders = array();
$myResult = $wpdb->get_results("SELECT idOrder, sqm, gbp_price, subtotal_price FROM $tablename WHERE createTime BETWEEN '" . $after . "' AND '" . $before . "'", ARRAY_A);


$users = get_users(array('fields' => array('ID')));
$users_year = array();
foreach ($myResult as $line) {
    $user_id = get_post_meta($line['idOrder'], '_customer_user', true);
    if (array_key_exists($user_id, $users_year)) {
        $users_year[$user_id]['users_sqm'] = $users_year[$user_id]['users_sqm'] + $line['sqm'];
        $users_year[$user_id]['users_gbp'] = $users_year[$user_id]['users_gbp'] + $line['subtotal_price'];
    } else {
        $users_year[$user_id]['users_sqm'] = $line['sqm'];
        $users_year[$user_id]['users_gbp'] = $line['subtotal_price'];
    }
    // if(in_array($user_id,$users_ids)) unset();
}

echo 'Useri cu activitate: ';
print_r(count($users_year));

$useless_users = array();
foreach ($users as $key => $user) {
    if (!array_key_exists($user->ID, $users_year)) {
        $users_year[$user->ID]['users_sqm'] = 0;
        $users_year[$user->ID]['users_gbp'] = 0;
        $useless_users[] = $user->ID;
    }
}
echo '    <<>>    Useri fara activitate: ';
print_r(count($useless_users));
asort($users_year);
//$keys = array_column($users_year, 'users_sqm');
//array_multisort($keys, SORT_ASC, $users_year);
//echo '<pre>';
//print_r($users_year);
//echo '</pre>';

?>

<table class="table table-bordered table-striped">
    <thead>
    <tr>
        <th>Nr.</th>
        <th>User</th>
        <th>Phone</th>
        <th>SQM</th>
        <th>GBP</th>
    </tr>
    </thead>
    <tbody>
    <?php
    $i = 1;
    foreach ($users_year as $user => $values) {
        $user_info = get_userdata($user);
        $company = get_user_meta($user, 'shipping_company', true);
        $phone = get_user_meta($user, 'billing_phone', true);
        ?>
        <tr>
            <td><?php echo $i; ?></td>
            <td>
                <?php
                echo $company . " - " . $user_info->first_name . " " . $user_info->last_name;
                ?>
            </td>
            <td>
                <?php echo $phone; ?>
            </td>
            <td>
                <?php echo number_format($values['users_sqm'], 2); ?>
            </td>
            <td>
                <?php echo number_format($values['users_gbp'], 2); ?>
            </td>
        </tr>
        <?php $i++;
    }
    ?>
    </tbody>
</table>

<!-- *********************************************************************************************
    End Total user sqm
*********************************************************************************************	-->

<?php

$stop = microtime(true);
print_r($stop - $start);
?>