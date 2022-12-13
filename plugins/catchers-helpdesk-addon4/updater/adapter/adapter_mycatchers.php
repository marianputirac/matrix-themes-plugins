<?php

namespace StgHelpdeskAddon4\Updater\Adapter;


class AdapterMycatchers
{
    private $_license;
    private $_pluginInfo;
    private $_pluginName;

    public function __construct($license,$currentVersion,$pluginSlug)
    {
        $this->_license = $license;
        $this->_pluginInfo = null;
        $this->_currentVersion = $currentVersion;
        $this->_pluginName = $pluginSlug;
    }

    public function getPluginInfo($version = false){

        if(!empty($this->_pluginInfo))
        {
            return $this->_pluginInfo;
        }
        else {

            $action = 'get details';

            if($version === true)
            {
                $action = 'get version';
            }

            $args = array(
                'headers' => array(
                    'Authorization' => 'Bearer ' . $this->_license,
                ),
                'timeout' => 30,
                'body' => array('unique_id' => false,'type' => false,'action' => $action, 'version' => $this->_currentVersion, 'plugin_name' => $this->_pluginName),
            );
            $url  = 'https://mycatchers.com/?stgh-updater-do-new=get_bundle_plugin_info';

            $response = wp_remote_post( esc_url_raw( $url ), $args );
            $response_code    = wp_remote_retrieve_response_code( $response );
            $response_message = wp_remote_retrieve_response_message( $response );

            if ( 200 !== $response_code && ! empty( $response_message ) ) {
                return new \WP_Error( $response_code, $response_message );
            } elseif ( 200 !== $response_code ) {
                return new \WP_Error( $response_code, __( 'An unknown API error occurred.', STG_CATCHERS_HELPDESK_ADDON4_TEXT_DOMAIN_NAME ) );
            } else {
                $out = json_decode( wp_remote_retrieve_body( $response ), true );
                if ( null === $out ) {
                    return new \WP_Error( 'api_error', __( 'An unknown API error occurred.', STG_CATCHERS_HELPDESK_ADDON4_TEXT_DOMAIN_NAME ) );
                }

                $this->_pluginInfo = $out;
                return $out;
            }
        }

    }

    public function getPluginPackageLink(){

        $url  = 'https://mycatchers.com/?stgh-updater-do-new=get_bundle_plugin&stgh-updater-license='.$this->_license.'&plugin_name='.$this->_pluginName;

        return $url;

    }

    public function getRemoteVersion(){
        $pluginInfo = $this->getPluginInfo(true);
        if(!is_wp_error($pluginInfo))
            return $pluginInfo['Version'];

        return false;
    }
}