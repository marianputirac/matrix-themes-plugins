<div class="wrap about-wrap">
    <h1><?php _e('Plugin problem detected!',STG_CATCHERS_HELPDESK_ADDON4_TEXT_DOMAIN_NAME);?></h1>
    <div>
        <p><?php _e('Sorry, but for the correct work of the plugin you should check following issues below: ',STG_CATCHERS_HELPDESK_ADDON4_TEXT_DOMAIN_NAME);?></p>
    </div>
    <?php

    $freeHDActivated = is_plugin_active(STG_CATCHERS_HELPDESK_NAME.'/'.STG_CATCHERS_HELPDESK_NAME.'.php');
    $proHDActivated = is_plugin_active(STG_CATCHERS_HELPDESK_NAME_PRO . '/' . STG_CATCHERS_HELPDESK_NAME_PRO . '.php');

    if($proHDActivated)
	    $plugin_name = STG_CATCHERS_HELPDESK_NAME_PRO;
    else
	    $plugin_name = STG_CATCHERS_HELPDESK_NAME;

    $addonDeps = Catchers_Helpdesk_Addon4::checkPluginDeps();

    include_once(ABSPATH.'wp-admin/includes/plugin-install.php');

    $api = plugins_api( 'plugin_information', array(
        'slug' => wp_unslash( $plugin_name ),
        'is_ssl' => is_ssl(),
        'fields' => array()
    ) );
    $status = install_plugin_install_status( $api );
    $url = $status['url'];


    if(!$freeHDActivated && !$proHDActivated){
        if($url === false)
        {

            $plugin_file = $status['file'];
            $url = wp_nonce_url( 'plugins.php?action=activate&amp;plugin=' . $plugin_file . '&amp;plugin_status=&amp;paged=&amp;s=', 'activate-plugin_' . $plugin_file );

            /** @noinspection PhpIncludeInspection */
            echo __('Attention, please! Catchers Helpdesk plugin is not activated!', STG_CATCHERS_HELPDESK_ADDON4_TEXT_DOMAIN_NAME);
            echo "<br/><br/>";
            echo sprintf('<!--suppress HtmlUnknownTarget --><a href="%s" class="button button-primary button-large">%s</a>', $url, __('Activate Catchers Helpdesk plugin',STG_CATCHERS_HELPDESK_ADDON4_TEXT_DOMAIN_NAME), false);

        }
        else{
            /** @noinspection PhpIncludeInspection */
            echo __('Attention, please! Catchers Helpdesk plugin is not installed or not activated!', STG_CATCHERS_HELPDESK_ADDON4_TEXT_DOMAIN_NAME);
            echo "<br/><br/>";
            echo sprintf('<!--suppress HtmlUnknownTarget --><a href="%s" class="button button-primary button-large">%s</a>', $url, __('Install and activate Catchers Helpdesk plugin',STG_CATCHERS_HELPDESK_ADDON4_TEXT_DOMAIN_NAME), false);
        }

    }else{

        $pluginFile = STG_HELPDESK_ROOT.STG_HELPDESK_NAME.".php";
        $readmeFile = STG_HELPDESK_ROOT."README.txt";
        $pluginData = stgh_wc_updater_get_plugin_data($pluginFile, $readmeFile);

        if (!$addonDeps) {
            _e(sprintf('Invalid version (%s) Catchers Helpdesk plugin. Must be more than %s',$pluginData["Version"],STG_CATCHERS_HELPDESK_ADDON4_VERSION), STG_CATCHERS_HELPDESK_ADDON4_TEXT_DOMAIN_NAME);
            echo "<br/><br/>";
            echo sprintf('<!--suppress HtmlUnknownTarget --><a href="%s" class="thickbox button button-primary button-large">%s</a>', $url, __('Update Catchers Helpdesk plugin',STG_CATCHERS_HELPDESK_ADDON4_TEXT_DOMAIN_NAME), false);
        }

    }
    ?>
</div>