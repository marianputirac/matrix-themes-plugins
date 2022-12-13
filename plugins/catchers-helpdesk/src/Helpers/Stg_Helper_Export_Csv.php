<?php
namespace StgHelpdesk\Helpers;

use StgHelpdesk\Core\PostType\Stg_Helpdesk_Post_Type_Statuses;

/**
 * Class Stg_Helper_Export_Csv
 * @package StgHelpdesk\Helpers
 */
class Stg_Helper_Export_Csv
{

    public static function setHeader($filename)
    {
        $now = gmdate("D, d M Y H:i:s");

        header("Expires: Tue, 03 Jul 2001 06:00:00 GMT");
        header("Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate");
        header("Last-Modified: {$now} GMT");


        header('Content-Encoding: UTF-8');
        header("Content-Type: text/csv; charset=UTF-8");
        //header("Content-Type: text/csv");
        header("Content-Disposition: attachment;filename={$filename}");
        echo "\xEF\xBB\xBF"; // UTF-8 BOM
    }

    private static function convertForMac($array)
    {
        $result = array();

        foreach ($array as $current) {
            //$result[] = chr(255).chr(254).mb_convert_encoding($current, 'UTF-16LE', 'UTF-8');
            $result[] = chr(255) . chr(254) . $current;
        }

        return $array;
    }

    public static function arr2scv($array)
    {

        if (count($array) == 0) {
            return null;
        }
        ob_start();
        $df = fopen("php://output", 'w');
        foreach ($array as $key => $row) {
            $row = self::convertForMac($row);
            fputcsv($df, $row, ';');
        }
        fclose($df);
        return ob_get_clean();
    }

    public static function makeScvArray($post, $reply = false)
    {
        $tmp = array();

        $statuses = Stg_Helpdesk_Post_Type_Statuses::get();
        $metas = get_post_meta($post->ID);

        if (isset($metas["_stgh_assignee"])) {
            $assignedID = array_pop($metas["_stgh_assignee"]);
            $assigned = get_user_by('ID', $assignedID);
        } else
            $assigned = false;

        if (isset($metas["_stgh_ticket_author_name"])) {
            $authorName = array_pop($metas["_stgh_ticket_author_name"]);
        } else {
            $author = get_user_by('id', $post->post_author);
            $authorName = $author->display_name;
        }


        $tags = stgh_ticket_get_tags($post->ID);
        $category = stgh_ticket_get_category($post->ID);


        if ($reply) {
            if ($post->post_content != "") {
                $tmp[] = "";
                $tmp[] = "";
                $tmp[] = "";
                $tmp[] = "";
                $tmp[] = "";
                $tmp[] = $post->post_content;
                $tmp[] = $authorName;
                $tmp[] = "";
                $tmp[] = date('m/d/Y', strtotime($post->post_date));
            }
        } else {
            $tmp[] = (isset($statuses[$post->post_status])) ? $statuses[$post->post_status] : "";
            $tmp[] = $post->ID;
            $tmp[] = $post->post_title;
            $tmp[] = implode(",", $tags);
            $tmp[] = ($category) ? $category->name : "";
            $tmp[] = $post->post_content;
            $tmp[] = $authorName;
            $tmp[] = ($assigned) ? $assigned->display_name : "";
            $tmp[] = date('m/d/Y', strtotime($post->post_date));
        }


        return $tmp;
    }


}