<?php

namespace StgHelpdesk\Ticket;

class Stg_Helpdesk_Ticket_Query
{

    /**
     * @param $query
     * @return bool
     */
    public static function limitAny($query)
    {
        if (!self::checks($query)) {
            return false;
        }

        if (!isset($_GET['post_status'])) {
            $query->set('post_status', array('stgh_new', 'stgh_answered', 'stgh_notanswered', 'stgh_pending'));
        }
    }

    /**
     * @param $query
     * @return bool
     */
    public static function checks($query)
    {
        if (!$query->is_main_query()) {
            return false;
        }

        if (!is_admin()) {
            return false;
        }

        if (!isset($_GET['post_type']) || !stgh_is_our_post_type($_GET['post_type'])) {
            return false;
        }

        return true;
    }

    /**
     * @param $query WP_Query
     * @return bool
     */
    public static function ordering($query)
    {
        if (!self::checks($query)) {
            return false;
        }

        $orderby = $query->get('orderby');
        if( 'Type' == $orderby ) {
            $query->set('meta_key','custom_field_type_meta_type');
            $query->set('orderby','meta_value');
        }elseif ('assignedTo' == $orderby) {
            $query->set('meta_key', '_stgh_assignee');
            $query->set('orderby', 'meta_value_num');
        } elseif ('contact' == $orderby) {
            $query->set('meta_key', 'first_name');
            $query->set('orderby', 'meta_value_num');
        }
    }


    public static function qa_kb($selects, $args, $taxonomies)
    {
    	global $wpdb;

	    if($args['fields'] == 'stgh_with_meta') {
		    $selects[] = $wpdb->termmeta.".*";
            $selects[] = "t.*";
            $selects[] = "tt.*";
        }
        return $selects;
    }


    public static function authorOrContact($query)
    {

        if( $author = $query->get( '_author_or_stgh_contact' ) )
        {
            add_filter( 'get_meta_sql', function( $sql ) use ( $author )
            {
                global $wpdb;


                static $ct = 0;
                if( 0 != $ct++ ) return $sql;


                $sql['where'] = sprintf(
                    " AND ( %s OR %s ) ",
                    $wpdb->prepare( "{$wpdb->posts}.post_author = '%s'", $author ),
                    mb_substr( $sql['where'], 5, mb_strlen( $sql['where'] ) )
                );
                return $sql;
            });
        }
    }


    /**
     * @param $query
     * @return bool
     */
    public static function assignedTo($query)
    {
        if (!self::checks($query)) {
            return false;
        }

        $id = filter_input(INPUT_GET, 'assignedTo', FILTER_SANITIZE_NUMBER_INT);

        if (0 != $id) {
            $query->set('meta_key', '_stgh_assignee');
            $query->set('meta_value', $id);
        } else {
            unset($_GET['assignedTo']);
        }
    }

    /**
     * Adding condition of having a category to post list
     *
     * @param $query
     * @return bool
     */
    public static function ticketHasCategory($query)
    {
        if (!self::checks($query)) {
            return false;
        }
        $id = filter_input(INPUT_GET, 'stgh_category', FILTER_SANITIZE_NUMBER_INT);

        if (0 != $id) {
            $taxquery = array(
                //'relation' => 'OR',
                array(
                    'taxonomy' => 'ticket_category',
                    'field' => 'term_id',
                    'terms' => array($id),
                ),
            );
            $query->set('tax_query', $taxquery);
        } else {
            unset($_GET['stgh_category']);
        }
    }

    public static function ticketHasTag($query)
    {

        if (!self::checks($query)) {
            return false;
        }
        $id = filter_input(INPUT_GET, 'stgh_tag', FILTER_SANITIZE_NUMBER_INT);

        if (0 != $id) {
            $taxquery = array(
                array(
                    'taxonomy' => 'ticket_tag',
                    'field' => 'term_id',
                    'terms' => array($id),
                ),
            );
            if(empty($query->query_vars['tax_query']))
            {
                $query->set('tax_query', $taxquery);
            }else {
                $query->set('tax_query', array_merge($query->query_vars['tax_query'], $taxquery));
            }

        } else {
            unset($_GET['stgh_tag']);
        }
    }

    /**
     * @param $query
     * @return bool
     */
    public static function authorId($query)
    {
        if (!self::checks($query)) {
            return false;
        }

        $id = filter_input(INPUT_GET, 'authorId', FILTER_SANITIZE_NUMBER_INT);

        if (0 != $id) {
            $query->set('author', $id);
        } else {
            unset($_GET['authorId']);
        }
    }

    /**
     * @param $query
     * @return bool
     */
    public static function filter($query)
    {
        if (!self::checks($query)) {
            return false;
        }

        if (!empty($_GET['crmCompany'])) {
            $crmCompany = html_entity_decode(filter_input(INPUT_GET, 'crmCompany', FILTER_SANITIZE_STRING));
            if (!empty($crmCompany)) {
                $ids = self::getUserIdsByCrmCompany($crmCompany);
                $query->set('author', $ids);
            } else {
                unset($_GET['crmCompany']);
            }
        }

        return $query;
    }

    /**
     * Get users id by company name
     *
     * @param $company
     * @return string
     */
    protected static function getUserIdsByCrmCompany($company)
    {
        global $wpdb;
        $users = $wpdb->get_results(
            $wpdb->prepare("SELECT DISTINCT user_id
                    FROM $wpdb->usermeta
                    WHERE meta_key = '_stgh_crm_company'
                    AND meta_value = %s", $company)
        );

        $authorIds = array();
        foreach ($users as $user) {
            $authorIds[] = $user->user_id;
        }

        return implode(',', $authorIds);
    }
}