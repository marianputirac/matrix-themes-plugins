<?php
use StgHelpdesk\Helpers;
use StgHelpdesk\Ticket;

?>

<div id="stg-all-tickets-block">
    <?php

    $params = array(
        'post_type' => STG_HELPDESK_POST_TYPE,
        'post_status' => 'any',
        'posts_per_page' => 10,
        'no_found_rows' => true,
        'cache_results' => true,
        'order' =>  'desc',
        'orderby' => 'modified',
        'update_post_term_cache' => false,
        'update_post_meta_cache' => false,
    );
    $user_tickets = Ticket\Stg_Helpdesk_Ticket::get($params, true);

    if ($user_tickets->have_posts()):
        $columns = Helpers\Stg_Helper_Template::getTicketsListColumns();
        ?>
        <div class="stgh stgh-ticket-list">
            <?php
                $link = add_query_arg(array('post_type' => STG_HELPDESK_POST_TYPE), admin_url('edit.php'));
            ?>
            <p><a href="<?=$link?>"><?php _e('All tickets',STG_HELPDESK_TEXT_DOMAIN_NAME)?></a></p>
            <table id="stgh_ticketlist" class="wp-list-table widefat fixed striped posts">
                <thead>
                <tr>
                    <?php foreach ($columns as $column_id => $column) {
                        echo "<th id='stg-ticket-$column_id'>" . $column['title'] . "</th>";
                    } ?>
                </tr>
                </thead>
                <tbody>
                <?php
                while ($user_tickets->have_posts()):

                    $user_tickets->the_post();

                    echo '<tr';
                    echo ' class=\'' . get_post_status(get_the_ID()) . '_color_row\'';
                    echo '>';

                    foreach ($columns as $column_id => $column) {

                        echo '<td';

                        if ('date' === $column_id) {
                            echo ' data-order="' . strtotime(get_the_time()) . '"';
                        }
                        echo '>';

                        if ('status' === $column_id) {
                            echo '<span class="stgh_colored_status ' . get_post_status(get_the_ID()) . '_color">';
                        }
                        /* Display the content for this column */
                        Helpers\Stg_Helper_Template::getTicketsListColumnContent($column_id, $column, true);

                        if ('status' === $column_id) {
                            echo '</span>';
                        }
                        echo '</td>';

                    }

                    echo '</tr>';

                endwhile;



                //wp_reset_query(); ?>
                </tbody>
            </table>
        </div>
        <?php
    else:
        echo getNotificationMarkup('info', __('Not found', STG_HELPDESK_TEXT_DOMAIN_NAME)); ?>
    <?php endif;?>

</div>