<?php
?>
<form action="" method="POST" style="display:block;">
    <div class="row">

        <div class="col-sm-3 col-lg-2 form-group">
            <br>
            <input type="submit"
                   name="submit"
                   value="Select"
                   class="btn btn-info">
        </div>


        <?php
        $transdate = date('m-d-Y', time());
        // $month = date("m", strtotime("-1 month"));
        $luni = array('01' => 'January', '02' => 'February', '03' => 'March', '04' => 'April', '05' => 'May', '06' => 'June', '07' => 'July', '08' => 'August', '09' => 'September', '10' => 'October', '11' => 'November', '12' => 'December');
        if (isset($_POST['perioada'])) {
            $period_selected = $_POST['perioada'];
        }
        ?>
        <div class="col-sm-4 col-lg-4 form-group">
            <label for="select-luna">Select Month:</label>
            <br>
            <select id="select-luna" name="luna">
                <?php
                foreach ($luni as $lunaNr => $luna) { ?>
                    <option value="<?php echo $lunaNr; ?>" <?php if ($_POST['luna'] == $lunaNr) echo 'selected'; ?>><?php echo $luna; ?></option>
                <?php } ?>
            </select>
        </div>
    </div>
</form>

<?php

global $wpdb;

$groups_created = get_post_meta(1, 'groups_created', true);
$group = get_post_meta(1, end($groups_created), true);

$i = 1;

if (isset($_POST['luna'])) {
    $luna = $_POST['luna'];
    echo '<pre>';
    print_r($luna);
    echo '</pre>';
    $args = queryArgsForOrdersFilteredMonth($luna);
    $args_l_y = queryArgsForOrdersFilteredMonth($luna, date("Y", strtotime("-1 year")));
} else {
    $args = queryArgsForOrdersFilteredMonth();
    $args_l_y = queryArgsForOrdersFilteredMonth(null, date("Y", strtotime("-1 year")));
}
//    echo '<pre>';
//    print_r($args);
//    print_r($args_l_y);
//    echo '</pre>';

$orders_years = array();
$orders = wc_get_orders($args);
$orders_l_y = wc_get_orders($args_l_y);
// echo ' count:' . count($orders);


$users_sqm = $users_gbp = array();
// print_r($orders);

$users_current_year = getOrdersUsersByYears($orders);
$users_last_year = getOrdersUsersByYears($orders_l_y);
// print_r($users_sqm);
?>
<br>

<div class="row">
    <div class="col-md-12">
        <div id="users_month">
            <table class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th colspan="4"
                        class="center-align text-center"><?php echo date("Y", strtotime("-1 year")); ?></th>
                    <th colspan="3" class="center-align text-center"><?php echo date("Y"); ?></th>
                </tr>
                <tr>
                    <th>Nr.</th>
                    <th>User</th>
                    <th>Toal SQM</th>
                    <th>Toal GBP</th>
                    <th>User</th>
                    <th>Toal SQM</th>
                    <th>Toal GBP</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $i = 1;
                arsort($users_last_year[0]);
                arsort($users_current_year[0]);
                foreach ($users_last_year[0] as $user => $sqm) {
                    $user_info = get_userdata($user);

                    ?>
                    <tr>
                        <td><?php echo $i; ?></td>
                        <td><?php echo $user_info->display_name . " - " . $user_info->first_name . " " . $user_info->last_name; ?></td>
                        <td>
                            <?php echo number_format($users_last_year[0][$user], 2); ?>
                        </td>
                        <td>
                            <?php echo number_format($users_last_year[1][$user], 2); ?>
                        </td>
                        <?php

                        if (array_key_exists($user, $users_current_year[0])) {
                            ?>
                            <td><?php echo $user_info->display_name . " - " . $user_info->first_name . " " . $user_info->last_name; ?></td>
                            <td>
                                <?php echo number_format($users_current_year[0][$user], 2); ?>
                            </td>
                            <td>
                                <?php echo number_format($users_current_year[1][$user], 2); ?>
                            </td>
                            <?php
                            unset($users_current_year[0][$user]);
                            unset($users_current_year[1][$user]);
                        } else {
                            ?>
                            <td></td>
                            <td></td>
                            <td></td>
                            <?php
                        }

                        ?>
                    </tr>
                    <?php $i++;
                }
                foreach ($users_current_year[0] as $user => $sqm) {
                    $user_info = get_userdata($user);

                    ?>
                    <tr>
                        <td><?php echo $i; ?></td>
                        <td></td>
                        <td></td>
                        <td></td>

                        <td><?php echo $user_info->display_name . " - " . $user_info->first_name . " " . $user_info->last_name; ?></td>
                        <td>
                            <?php echo number_format($users_current_year[0][$user], 2); ?>
                        </td>
                        <td>
                            <?php echo number_format($users_current_year[1][$user], 2); ?>
                        </td>
                    </tr>
                    <?php $i++;
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>