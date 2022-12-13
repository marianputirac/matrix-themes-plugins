<?php
?>
<br>
<h2>Search Group Info</h2>

<form action="" method="POST" style="display:block;">
    <div class="row">

        <div class="col-sm-3 col-lg-2 form-group">
            <br>
            <input type="submit"
                   name="submit"
                   value="Select"
                   class="btn btn-info">
        </div>

        <div class="col-sm-4 col-lg-4 form-grup">
            <label for="q_Status">Select group to show chart info</label>
            <br>
            <select id="q_status_order_id_eq"
                    name="group_name"
                    class="form-control">
                <option value="">Please select...</option>
                <?php
                $groups_created = get_post_meta(1, 'groups_created', true);
                // Array of WP_User objects.
                foreach ($groups_created as $key => $group_name) {
                    if ($_POST['group_name'] == $group_name) {
                        echo '<option value="' . $group_name . '" selected>' . $group_name . '</option>';
                    } else {
                        echo '<option value="' . $group_name . '">' . $group_name . '</option>';
                    }
                } ?>
                <option value="all_users">All Users</option>
            </select>
        </div>

        <?php
        $month = date("m");
        $luni = array('01' => 'January', '02' => 'February', '03' => 'March', '04' => 'April', '05' => 'May', '06' => 'June', '07' => 'July', '08' => 'August', '09' => 'September', '10' => 'October', '11' => 'November', '12' => 'December'); ?>
        <div class="col-sm-4 col-lg-3 form-group">
            <label for="select-luna">Select month:</label>
            <br>
            <select id="select-luna" name="luna_select">
                <?php foreach ($luni as $luna => $name_month) {
                    if (!empty($_POST['luna_select'])) {
                        $selected = ($_POST['luna_select'] == $luna) ? 'selected' : '';
                    } else {
                        $selected = ($month == $luna && empty($_POST['luna_select'])) ? 'selected' : '';
                    } ?>
                    <option value="<?php echo $luna; ?>" <?php echo $selected; ?>>
                        <?php echo $name_month; ?>
                    </option>
                <?php } ?>
            </select>
        </div>
        <div class="col-sm-4 col-lg-3 form-group">
            <label for="select-luna">Select year:</label>
            <br>
            <select id="select-an" name="an_select">
                <?php
                $ani = array(2016, 2017, 2018, 2019, 2020, 2021, 2022, 2023);
                $current_an = date("Y");
                foreach (array_reverse($ani) as $an) {
                    if (!empty($_POST['an_select'])) {
                        $selected = ($_POST['an_select'] == $an) ? 'selected' : '';
                    } else {
                        $selected = ($current_an == $an) ? 'selected' : '';
                    } ?>
                    <option value="<?php echo $an; ?>" <?php echo $selected; ?>>
                        <?php echo $an; ?>
                    </option>
                    <?php
                } ?>
            </select>
        </div>
    </div>
</form>