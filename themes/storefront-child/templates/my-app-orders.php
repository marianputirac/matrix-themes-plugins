<?php

//if (current_user_can('administrator') && get_current_user_id() == 1) {
//                                             echo '<pre>';
//                                             print_r($order_id);
//                                             echo '</pre>';

$user_id = get_current_user_id();
$meta_key = 'wc_multiple_shipping_addresses';

?>
    <h2>My App Orders</h2>

    <br>
    <div class="show-table2"></div>

    <p></p>

    <table class="table home app-table">
        <thead>
        <tr>
            <th>Title</th>
            <th>Date</th>
            <th>View</th>
            <th>Import</th>
            <th>Delete</th>
        </tr>
        </thead>
        <tbody>
		<?php
		// Query for appCart posts
		$args = array(
		  'post_type' => 'appcart',
		  'post_status' => 'publish',
		  'posts_per_page' => -1, // Show all appCart posts
		  'author' => $user_id,
		);

		$appcarts = new WP_Query($args);

		if ($appcarts->have_posts()) :
			while ($appcarts->have_posts()) : $appcarts->the_post();

				$prods = get_post_meta(get_the_ID(), 'attached_product_ids', true);

// Verifică dacă $prods este un array și transformă-l în string
				if (is_array($prods)) {
					$prods_string = implode(',', $prods);
				} else {
					$prods_string = '';
				}

				?>
                <tr data-post-id="<?php echo get_the_ID(); ?>">
                    <td><?php the_title(); ?></td>
                    <td><?php echo get_the_date(); ?></td>
                    <td><a href="<?php echo get_permalink(); ?>" class="btn btn-primary">View</a></td>
                    <td>
                        <button class="import-appcart btn btn-primary"
                                data-post-id="<?php echo get_the_ID(); ?>"
                                data-title="<?php echo get_the_title(); ?>"
                                data-prods="<?php echo $prods_string; ?>"
                                onclick="return BMCWcMs.command('insertAppOrder', this);"
                        >Import
                        </button>
						<?php
//						if (get_current_user_id() === 1) {
//							?>
<!--                            <button class="import-appcart btn btn-primary"-->
<!--                                    data-post-id="--><?php //echo get_the_ID(); ?><!--"-->
<!--                                    data-title="--><?php //echo get_the_title(); ?><!--"-->
<!--                                    data-prods="--><?php //echo $prods_string; ?><!--"-->
<!--                                    onclick="return BMCWcMs.command('insertAppOrder', this);"-->
<!--                            >Import-->
<!--                            </button>-->
<!--							--><?php
//						}
						?>

                    </td>
                    <td>
                        <button class="delete-appcart btn btn-danger delete-btn" data-post-id="<?php echo get_the_ID(); ?>">Delete</button>
                    </td>
                </tr>
			<?php
			endwhile;
			wp_reset_postdata();
		else :
			?>
            <tr>
                <td colspan="5">No App Carts found.</td>
            </tr>
		<?php
		endif;
		?>
        </tbody>
    </table>


    <script type="text/javascript">
        (function ($) {
            jQuery(document).on('click', '.delete-appcart', function () {
                var postId = jQuery(this).data('post-id');
                var row = jQuery(this).closest('tr');

                if (confirm('Are you sure you want to delete this appCart?')) {
                    $.ajax({
                        url: '<?php echo admin_url("admin-ajax.php"); ?>',
                        type: 'POST',
                        data: {
                            action: 'delete_appcart_post',
                            post_id: postId,
                            security: '<?php echo wp_create_nonce("delete_appcart_post_nonce"); ?>'
                        },
                        success: function (response) {
                            if (response.success) {
                                row.remove(); // Remove the row from the table
                            } else {
                                alert('Failed to delete the post.');
                            }
                        }
                    });
                }
            });
        })(jQuery);
    </script>


<?php
// ksort($carts_sort);
// echo '<pre>';
//print_r($carts_sort);
// echo '</pre>';
//
//}