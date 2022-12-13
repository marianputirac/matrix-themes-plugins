<!-- Bootstrap3 -->
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

<!-- jQuery library -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

<!-- Latest compiled JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>


<?php

if( isset($_POST['submit']) ){
    if( $_POST['notification'] ){
        update_post_meta( 1,'notification_users_message',$_POST['notification']);
    }
    else{ ?>
        <div class="alert alert-warning">
                  Your message is empty
        </div> <?php
    }
}

?>

    <div class="container-fluid">
        <h3>Notification Users</h3>

        <div class="container-fluid">

            <form  method="POST" >
                <div class="row">
                <label for="add_not">Add Notification Alert</label>
                <input type="text" class="col-md-12" name="notification" id="add_not" value="<?php echo get_post_meta( 1,'notification_users_message',true); ?>" >
                <div class="clearfix clear-fix"></div>
                <br>
                <input type="submit" class="btn btn-primary" value="Save" name="submit" >
                </div>

            </form>

        </div>


    </div>