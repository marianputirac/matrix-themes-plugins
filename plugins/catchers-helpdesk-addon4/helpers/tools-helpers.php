<?php

if (!function_exists('stgh_wc_updater_get_plugin_data')) {
    function stgh_wc_updater_get_plugin_data($pluginFile, $readmeFile = false)
    {
        if (!function_exists('get_plugin_data')) {
            require_once(ABSPATH . 'wp-admin/includes/plugin.php');
        }

        $pluginData = get_plugin_data($pluginFile);
        if($readmeFile === false)
            $pluginData['sections'] = "";
        else
            $pluginData['sections'] = stgh_wc_updater_parse_readme($readmeFile);


        return $pluginData;

    }
}


if (!function_exists('stgh_wc_updater_improve_readme')) {
    function stgh_wc_updater_improve_readme($string)
    {
        $string = nl2br(esc_html($string));
        $string = preg_replace('/`(.*?)`/', '<code>\\1</code>', $string);
        $string = preg_replace('/[\040]\*\*(.*?)\*\*/', ' <strong>\\1</strong>', $string);
        $string = preg_replace('/[\040]\*(.*?)\*/', ' <em>\\1</em>', $string);
        $string = preg_replace('/= (.*?) =/', '<h4>\\1</h4>', $string);
        $string = preg_replace('/\*(.*?)/', '<li>\\1', $string);
        $string = preg_replace('/<\/h4>(<br \/>\n<br \/>)*/s', '</h4>', $string);
        $string = preg_replace('/<br \/>\n<h4>/s', '<h4>', $string);

        $string = preg_replace('/\[(.*)\]\((.*)\)/s', '<a href="\\2">\\1</a>', $string);

        return $string;
    }
}


if (!function_exists('stgh_wc_updater_parse_readme')) {

    function stgh_wc_updater_parse_readme($filePath)
    {

        $source = file_get_contents($filePath);

        if (!$source) {
            return false;
        }

        $sections = array();
        $sectionTags = array(
            'description' => '== Description ==',
            'screenshots' => '== Screenshots ==',
            'translation' => '== Translations ==',
            'installation' => '== Installation ==',
            'FAQ' => '== Frequently Asked Questions ==',
            'changelog' => '== Changelog ==');


        $syntaxOk = preg_match('/(' . PHP_EOL . ')*' . $sectionTags['description'] . '(' . PHP_EOL . ')*(.+)(' . PHP_EOL . ')*' . $sectionTags['screenshots'] . '/s', $source, $matches);
        if (!$syntaxOk) {
            return false;
        }
        $sections['description'] = stgh_wc_updater_improve_readme($matches[3]);

        $syntaxOk = preg_match('/(' . PHP_EOL . ')*' . $sectionTags['translation'] . '(' . PHP_EOL . ')*(.+)(' . PHP_EOL . ')*' . $sectionTags['installation'] . '/s', $source, $matches);
        if (!$syntaxOk) {
            return false;
        }
        $sections['translation'] = stgh_wc_updater_improve_readme($matches[3]);

        $syntaxOk = preg_match('/(' . PHP_EOL . ')*' . $sectionTags['installation'] . '(' . PHP_EOL . ')*(.+)(' . PHP_EOL . ')*' . $sectionTags['FAQ'] . '/s', $source, $matches);
        if (!$syntaxOk) {
            return false;
        }
        $sections['installation'] = stgh_wc_updater_improve_readme($matches[3]);

        $syntaxOk = preg_match('/(' . PHP_EOL . ')*' . $sectionTags['FAQ'] . '(' . PHP_EOL . ')*(.+)(' . PHP_EOL . ')*' . $sectionTags['changelog'] . '/s', $source, $matches);
        if (!$syntaxOk) {
            return false;
        }
        $sections['FAQ'] = stgh_wc_updater_improve_readme($matches[3]);


        $syntaxOk = preg_match('/(' . PHP_EOL . ')*' . $sectionTags['changelog'] . '(' . PHP_EOL . ')*(.+)/s', $source, $matches);
        if (!$syntaxOk) {
            return false;
        }
        $sections['changelog'] = stgh_wc_updater_improve_readme($matches[3]);

        return $sections;

    }
}
