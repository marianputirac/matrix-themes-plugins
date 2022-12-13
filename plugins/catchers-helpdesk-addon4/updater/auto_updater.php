<?php

namespace StgHelpdeskAddon4\Updater;

use StgHelpdeskAddon4\Updater\Adapter\AdapterFactory;

class AutoUpdater
{
    private $_adapterName;
    private $_currentVersion;
    private $_pluginSlug;
    private $_slug;
    private $_adapterData;
    private $_timeout;

    public function __construct($adapterName, $currentVersion, $data, $pluginName){

        /** @noinspection PhpIncludeInspection */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'updater/adapter/adapter_envato.php';
        /** @noinspection PhpIncludeInspection */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'updater/adapter/adapter_factory.php';
        /** @noinspection PhpIncludeInspection */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'updater/adapter/adapter_mycatchers.php';


        $this->_adapterName = $adapterName;
        $this->_currentVersion = $currentVersion;
        $this->_pluginSlug = $pluginName;
        $this->_timeout = 12*60*60;

        //list ($temp1, $temp2) = explode('/', $this->_pluginSlug);
        $temp = explode('/', $this->_pluginSlug);
        $this->_slug = str_replace('.php', '', $temp[1]);

        $this->_adapterData = $data[$adapterName];
        add_filter('https_ssl_verify', '__return_false');
        //add_filter( 'pre_set_site_transient_update_plugins', array( &$this, 'checkPluginUpdate' ) );
        add_filter( 'site_transient_update_plugins', array( &$this, 'checkPluginUpdate' ) );

        add_filter( 'plugins_api', array( &$this, 'getPluginInfo' ), 11, 3 );

        add_filter( 'upgrader_package_options', array( &$this, 'setPluginPackage' ) );

    }


    public function checkPluginUpdate($transient){

        $prev = stgh_get_option('prev_check_u'.$this->_slug, false);

        $remoteVersion = stgh_get_option('prev_check_version'.$this->_slug,false);

        if(!$prev || (time() > $prev + $this->_timeout) || (isset($_GET['force-check']) && $_GET['force-check'] == '1') ) {
            $updateAdapter = AdapterFactory::getAdapter($this->_adapterName, $this->_adapterData, $this->_currentVersion, $this->_pluginSlug);

            $remoteVersion = $updateAdapter->getRemoteVersion();

            stgh_set_option('prev_check_u'.$this->_slug,time());
            stgh_set_option('prev_check_version'.$this->_slug,$remoteVersion);
        }


        if($remoteVersion === false)
            return $transient;

        if (version_compare($this->_currentVersion, $remoteVersion, '<')) {
            $obj = new \stdClass();
            $obj->slug                             = $this->_slug;
            $obj->new_version                      = $remoteVersion;
            $obj->url                              = '';
            $obj->package                          = $this->_pluginSlug;
            $obj->name                             = $this->_slug;
            $obj->plugin                           = $this->_pluginSlug;
            $transient->response[$this->_pluginSlug] = $obj;
        }

        return $transient;

    }

    public function getPluginInfo( $result, /** @noinspection PhpUnusedParameterInspection */ $action, $args ){

        if (isset($args->slug) && $args->slug === $this->_slug) {

            $updateAdapter = AdapterFactory::getAdapter($this->_adapterName,$this->_adapterData,$this->_currentVersion, $this->_pluginSlug);
            $pluginInfo = $updateAdapter->getPluginInfo();

            if(is_wp_error($pluginInfo))
                return $result;

            if ( isset( $pluginInfo['sections'] ) ) {
                $plugin                  = new \stdClass();
                $plugin->name            = $pluginInfo['Name'];
                $plugin->author          = $pluginInfo['Author'];
                $plugin->slug            = $this->_slug;
                $plugin->version         = $pluginInfo['Version'];

                $plugin->sections        = $pluginInfo['sections'];

                $plugin->download_link   = $this->_slug;
                $plugin->banners         = array(
                    'high' => 'http://ps.w.org/catchers-helpdesk/assets/banner-772x250.png?rev=1381971'
                );

                $plugin->homepage        = "https://www.mycatchers.com";

                return $plugin;

            }else {
                return $result;
            }

        } else {
            return $result;
        }
    }

    public function setPluginPackage($options){
        $package = $options['package'];

        if ( $package === $this->_pluginSlug ) {

            $updateAdapter = AdapterFactory::getAdapter($this->_adapterName,$this->_adapterData,$this->_currentVersion, $this->_pluginSlug);
            $pluginPackageLink = $updateAdapter->getPluginPackageLink();

            if(is_wp_error( $pluginPackageLink ))
            {
                $options['package'] = '';
            }
            else{
                $options['package'] = $pluginPackageLink;
            }
        }

        return $options;
    }

}