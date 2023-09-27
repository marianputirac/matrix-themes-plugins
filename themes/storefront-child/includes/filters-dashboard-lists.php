<?php

add_filter('parse_query', 'order_components_type_filter_request_query', 10);
function order_components_type_filter_request_query($query)
{
  global $pagenow;
  // Get the post type
  $post_type = isset($_GET['post_type']) ? $_GET['post_type'] : '';
  if (is_admin() && $pagenow == 'edit.php' && $post_type == 'shop_order' && isset($_GET['order_components_type']) && !empty($_GET['order_components_type'])) {
    $query->query_vars['meta_query'][] = array(
      'key' => 'order_components_type',
      'value' => $_GET['order_components_type'],
      'compare' => 'LIKE',
    );
  }
  if (is_admin() && $pagenow == 'edit.php' && $post_type == 'shop_order' && isset($_GET['company_name']) && $_GET['company_name'] != '') {
    // Add meta query to fetch orders by _billing_company meta key
    $query->query_vars['meta_query'][] = array(
      'key' => '_billing_company',
      'value' => $_GET['company_name'],
      'compare' => 'LIKE',
    );
  }
}

// this action brings up a dropdown select box over the posts list in the dashboard
//add_action('restrict_manage_posts', 'my_custom_restrict_manage_posts', 50);
function my_custom_restrict_manage_posts($post_type)
{
  if (current_user_can('manage_options')) {
    if ($post_type == 'shop_order') {
      $selected = '';
      $request_attr = 'order_components_type';
      if (isset($_REQUEST[$request_attr])) {
        $selected = $_REQUEST[$request_attr];
      }
      //get unique values of the meta field to filer by.
      $results = array('FOB', 'UK');
      //build a custom dropdown list of values to filter by
      echo '<select id="order_components_type" name="order_components_type">';
      echo '<option value="">' . __('Component type', 'my-custom-domain') . ' </option>';
      foreach ($results as $type) {
        $select = ($type == $selected) ? ' selected="selected"' : '';
        echo '<option value="' . $type . '"' . $select . '>' . $type . ' </option>';
      }
      echo '</select>';
    }
  }
}


// Add custom dropdown filter to WooCommerce orders list
add_action('restrict_manage_posts', 'add_company_filter_to_orders_list', 40);

function add_company_filter_to_orders_list()
{
  global $wpdb, $post_type;
  if ('shop_order' === $post_type) {

    // Define the input field ID
    $input_id = 'company_name';

// Define the placeholder text for the input field
    $placeholder = 'Search by company name';

    $user_query = get_users();
    $users_data = array();
    foreach ($user_query as $user) {
      $newObject = new stdClass();
      $newObject->id = $user->ID;
      $newObject->billingCompany = get_user_meta($user->ID, 'billing_company', true);

      $users_data[] = $newObject;
    }

    // Initialize the dropdown filter with a default value of 'All companies'
    $filter_value = 'All companies';

    // Check if the filter value is set in the URL query string
    if (isset($_GET['company_name']) && !empty($_GET['company_name'])) {
      $filter_value = $_GET['company_name'];
    }

    // Output the company name filter dropdown
    echo '<select name="company_name" id="company_name_filter">
          <option value="">Filter By companies</option>';
    foreach ($users_data as $user) {
      $selected = ($filter_value == $user->id) ? 'selected' : '';
      echo '<option value="' . $user->id . '" ' . $selected . '>' . $user->billingCompany . '</option>';
    }
    echo '</select>';
    ?>
    <script>
      jQuery(document).ready(function ($) {
        $('#company_name_filter').select2({
          ajax: {
            url: '<?php echo admin_url('admin-ajax.php'); ?>',
            dataType: 'json',
            delay: 500,
            data: function (params) {
              console.log(params)
              return {
                search: params.term,
                action: 'search_company_name'
              };
            },
            processResults: function (data) {
              console.log('data ajax return:', data);
              var options = [];
              if (data) {
                // data is the array of arrays, and each of them contains ID and the Label of the option
                $.each(data, function (index, user) { // do not forget that "index" is just auto incremented value
                  options.push({id: user.id, text: user.billingCompany});
                });

              }
              return {
                results: options
              };
            },
            cache: true
          },
          minimumInputLength: 3,
        });
      });
    </script>

    <?php
  }
}


// Handle search requests from the dropdown filter
add_action('wp_ajax_search_company_name', 'search_company_name');
add_action('wp_ajax_nopriv_search_company_name', 'search_company_name');

function search_company_name()
{
  // Define the meta key used to store the company name
  $meta_key = 'billing_company';
  $company_name = $_GET['search'];

  $user_query = get_users(array(
    'meta_query' => array(
      array(
        'key' => $meta_key,
        'value' => $company_name,
        'compare' => 'LIKE',
      ),
    ),
  ));
  $users_data = array();
  foreach ($user_query as $user) {
    $newObject = new stdClass();
    $newObject->id = get_user_meta($user->ID, 'billing_company', true);
    $newObject->billingCompany = get_user_meta($user->ID, 'billing_company', true);;
    $users_data[] = $newObject;
  }

  // Create an empty array to store unique objects
  $unique_users_data = array();

// Loop through each object in $users_data array
  foreach ($users_data as $user) {
    // Check if the id property of the current object already exists in $unique_users_data
    $existing_user = array_reduce($unique_users_data, function ($carry, $item) use ($user) {
      return $carry || ($item->id == $user->id);
    }, false);

    // If the current object is not already in $unique_users_data, add it to the array
    if (!$existing_user) {
      $unique_users_data[] = $user;
    }
  }

  echo json_encode($unique_users_data);
  die;
}



function select_containers()
{
  global $wpdb, $post_type;
  if ('shop_order' === $post_type) {
    $rand_posts = get_posts(array(
      'post_type' => 'container',
      'posts_per_page' => 10,
    ));

    echo '<select name="container" id="containers_ids">
                <option value="">Select container</option>';
    foreach ($rand_posts as $post) :
      setup_postdata($post);
      echo '<option value="' . $post->ID . '">' . get_the_title($post->ID) . '</option>';
    endforeach;
    echo '</select>';

    ?>

    <script>
      jQuery(document).ready(function ($) {
        var $customPostSelect = $('#containers_ids');

        $customPostSelect.select2({
          allowClear: false,
          placeholder: 'Containers',
          ajax: {
            url: '<?php echo admin_url('admin-ajax.php'); ?>',
            dataType: 'json',
            delay: 500,
            data: function (params) {
              return {
                action: 'select_containers_name',
                search: params.term,
                page: params.page
              };
            },
            processResults: function (response) {
              if (response && response.length > 0) {
                var results = $.map(response, function (item) {
                  return {
                    id: item.ID,
                    text: item.title
                  };
                });

                return {
                  results: results
                };
              } else {
                return {
                  results: []
                };
              }
            }
          }
        });
      });
    </script>

    <?php
    wp_reset_postdata();
  }
}


add_action('restrict_manage_posts', 'select_containers', 20);

// Handle search requests from the dropdown filter
add_action('wp_ajax_select_containers_name', 'select_containers_ajax');
add_action('wp_ajax_nopriv_select_containers_name', 'select_containers_ajax');

function select_containers_ajax()
{
  $containers_posts = get_posts(array(
    'post_type' => 'container',
    'posts_per_page' => 10,
  ));

  // Create an empty array to store unique objects
  $results = array();

  if ($containers_posts) {
    foreach ($containers_posts as $post) {
      $results[] = array(
        'ID' => $post->ID,
        'title' => $post->post_title,
      );
    }
  }

  wp_send_json($results);
}