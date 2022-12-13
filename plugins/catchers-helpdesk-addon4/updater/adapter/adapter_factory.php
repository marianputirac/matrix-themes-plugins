<?php

namespace StgHelpdeskAddon4\Updater\Adapter;


class AdapterFactory
{

    protected static $instances = array();
    private static $_envatoName = 'Envato';
    private static $_mycatchersName = 'Mycatchers';

    private function __construct()
    {
    }

    /**
     * @param $token
     * @param $itemId
     * @param $license
     * @param $currentVersion
     * @param $pluginSlug
     *
     * @return bool
     */
    public static function getEnvatoAdapter($token, $itemId, $license,$currentVersion,$pluginSlug){
        if (isset(self::$instances[self::$_envatoName])) {
            return self::$instances[self::$_envatoName];
        } else {
            $className = __NAMESPACE__ . '\\' . 'adapter'.ucfirst(strtolower(self::$_envatoName));

            if (class_exists($className)) {
                return self::$instances[self::$_envatoName] = new $className($token,$itemId,$license,$currentVersion,$pluginSlug);
            }
            else
                return false;
        }
    }


    /**
     * @param $license
     * @param $currentVersion
     * @param $pluginSlug
     *
     * @return bool
     */
    public static function getMycatchersAdapter($license,$currentVersion,$pluginSlug){
        if (isset(self::$instances[self::$_mycatchersName])) {
            return self::$instances[self::$_mycatchersName];
        } else {
            $className = __NAMESPACE__ . '\\' . 'adapter'.ucfirst(strtolower(self::$_mycatchersName));

            if (class_exists($className)) {
                return self::$instances[self::$_mycatchersName] = new $className($license,$currentVersion,$pluginSlug);
            }
            else
                return false;
        }
    }

    /**
     * @param $name
     * @param $data
     * @param $currentVersion
     * @param $pluginSlug
     *
     * @return bool|AdapterMycatchers|AdapterEnvato
     */
    public static function getAdapter($name,$data,$currentVersion,$pluginSlug){
        switch($name){
            case self::$_envatoName:
                return self::getEnvatoAdapter($data['token'],$data['itemId'],$data['license'],$currentVersion,$pluginSlug);
                break;
            case self::$_mycatchersName:
                return self::getMycatchersAdapter($data['license'],$currentVersion,$pluginSlug);
                break;
        }
        return false;
    }

}