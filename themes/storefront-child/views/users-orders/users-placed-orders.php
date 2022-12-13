<!-- *********************************************************************************************
    Start Total user sqm
*********************************************************************************************	-->

<br>

<?php
$start = microtime(true);

$orders_by_user = array();
foreach ($users as $key => $user) {
    $numorders = wc_get_customer_order_count($user->ID);
    $orders_by_user[$user->ID] = $numorders;
}

asort($orders_by_user);
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
        <th>Placed Orders All time</th>
    </tr>
    </thead>
    <tbody>
    <?php
    $i = 1;
    foreach ($orders_by_user as $user => $nr_orders) {
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
                <?php echo $nr_orders; ?>
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