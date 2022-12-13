<?php
namespace StgHelpdesk\Updater;

use StgHelpdesk\Updater\Adapter\AdapterFactory;

class AutoUpdater
{
    private $_adapterName;
    private $_currentVersion;
    private $_pluginSlug;
    private $_slug;
    private $_adapterData;
    private $_timeout;

    public function __construct($adapterName, $currentVersion, $data){
        $this->_adapterName = $adapterName;
        $this->_currentVersion = $currentVersion;
        $this->_pluginSlug = STG_HELPDESK_NAME."/".STG_HELPDESK_NAME.".php";
        $this->_timeout = 12*60*60;

        list ($temp1, $temp2) = explode('/', $this->_pluginSlug);
        $this->_slug = str_replace('.php', '', $temp2);

        $this->_adapterData = $data[$adapterName];

        //add_filter( 'pre_set_site_transient_update_plugins', array( &$this, 'checkPluginUpdate' ) );
        add_filter( 'site_transient_update_plugins', array( &$this, 'checkPluginUpdate' ) );

        add_filter( 'plugins_api', array( &$this, 'getPluginInfo' ), 10, 3 );

        add_filter( 'upgrader_package_options', array( &$this, 'setPluginPackage' ) );

    }


    public function checkPluginUpdate($transient){

        $prev = stgh_get_option('prev_check_u', false);

        $remoteVersion = stgh_get_option('prev_check_version',false);

        if(!$prev || (time() > $prev + $this->_timeout) || (isset($_GET['force-check']) && $_GET['force-check'] == '1') ) {
            $updateAdapter = AdapterFactory::getAdapter($this->_adapterName, $this->_adapterData, $this->_currentVersion);
            $remoteVersion = $updateAdapter->getRemoteVersion();

            stgh_set_option('prev_check_u',time());
            stgh_set_option('prev_check_version',$remoteVersion);
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


    public function getPluginInfo( $result, $action, $args ){
        if (isset($args->slug) && $args->slug === $this->_slug ) {

            $updateAdapter = AdapterFactory::getAdapter($this->_adapterName,$this->_adapterData,$this->_currentVersion);
            $pluginInfo = $updateAdapter->getPluginInfo();

            if(is_wp_error($pluginInfo))
                return $result;;

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

            $updateAdapter = AdapterFactory::getAdapter($this->_adapterName,$this->_adapterData,$this->_currentVersion);
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
