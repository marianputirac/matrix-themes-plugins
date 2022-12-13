<?php
use StgHelpdesk\Helpers;
use StgHelpdesk\Ticket;

?>

<div id="stg-all-tickets-block">
    <h2><?php _e('My tickets', STG_HELPDESK_TEXT_DOMAIN_NAME); ?></h2>

    <?php

    global $paged, $post;
    $myAccountId = get_option('woocommerce_myaccount_page_id');

    $stghTickets = get_query_var('my_tickets');
    if(!empty($stghTickets))
    {
        $tmp = explode('/',$stghTickets);
        if(isset($tmp[1]) && is_numeric($tmp[1]))
        {
            $paged = (int) $tmp[1];
            set_query_var('paged',(int) $tmp[1]);
        }
    }

    $paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;
    $ppp = 20;

    $user_tickets = Ticket\Stg_Helpdesk_Ticket::userTickets($ppp,$paged);

    if ($user_tickets->have_posts()):
        $columns = Helpers\Stg_Helper_Template::getTicketsListColumns();
        ?>
        <div class="stgh stgh-ticket-list">
            <?php
            if ($user_tickets->max_num_pages > 1) {
                echo '<nav class="prev-next-posts">';
                echo '<div class="prev-posts-link stgh-div-block-m10 stgh-div-block-float">';
                echo get_next_posts_link( __('next tickets', STG_HELPDESK_TEXT_DOMAIN_NAME), $user_tickets->max_num_pages );
                echo '</div>';
                echo '<div class="next-posts-link stgh-div-block-m10 stgh-div-block-float">';
                echo get_previous_posts_link( __('previous tickets', STG_HELPDESK_TEXT_DOMAIN_NAME) );
                echo '</div>';
                echo '</nav>';
            }
            ?>
	        <?php if(defined('STG_HELPDESK_WC_SUPPORT_TEXT_DOMAIN_NAME') && $myAccountId==$post->ID):?>
            <span class="order">
                <a href="#" class="woocommerce-button button stg_order_get_help_NOORDER">
                    <?php
                    _e('Open Ticket',STG_HELPDESK_WC_SUPPORT_TEXT_DOMAIN_NAME)
                    ?>
                </a>
            </span>
            <?php endif;?>

            <table id="stgh_ticketlist" class="stgh-table stgh-table-hover">
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
                        if ('status' === $column_id) {
                            echo ' class="' . get_post_status(get_the_ID()) . '_color"';
                        }
                        echo '>';

                        /* Display the content for this column */
                        Helpers\Stg_Helper_Template::getTicketsListColumnContent($column_id, $column);

                        echo '</td>';

                    }

                    echo '</tr>';

                endwhile;



                //wp_reset_query(); ?>
                </tbody>
            </table>
        </div>
        <?php
        if ($user_tickets->max_num_pages > 1) {
            echo '<nav class="prev-next-posts">';
            echo '<div class="prev-posts-link stgh-div-block-m10 stgh-div-block-float">';
            echo get_next_posts_link( __('next tickets', STG_HELPDESK_TEXT_DOMAIN_NAME), $user_tickets->max_num_pages );
            echo '</div>';
            echo '<div class="next-posts-link stgh-div-block-m10 stgh-div-block-float">';
            echo get_previous_posts_link( __('previous tickets', STG_HELPDESK_TEXT_DOMAIN_NAME) );
            echo '</div>';
            echo '</nav>';
        }
        ?>
        <?php
    else:
	    ?>
	    <?php echo getNotificationMarkup('info', __('Not found', STG_HELPDESK_TEXT_DOMAIN_NAME)); ?>

        <?php if(defined('STG_HELPDESK_WC_SUPPORT_TEXT_DOMAIN_NAME') && $myAccountId == $post->ID):?>
        <span class="order">
                <a href="#" class="woocommerce-button button stg_order_get_help_NOORDER">
                    <?php
                    _e('Open Ticket',STG_HELPDESK_WC_SUPPORT_TEXT_DOMAIN_NAME)
                    ?>
        </a>
        </span>
        <?php endif;?>
    <?php endif;

    $url = stgh_get_submit_page_url();
    if (!empty($url)) :
        ?>
        <a href="<?php echo esc_url($url); ?>"><?php _e('Open new ticket', STG_HELPDESK_TEXT_DOMAIN_NAME); ?></a>
    <?php endif; ?>


</div>