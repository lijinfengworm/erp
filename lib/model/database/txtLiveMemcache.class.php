<?php
class txtLiveMemcache {

    protected static $instance;

    public static function getInstance() {
        if (!isset(self::$instance)) {
            self::$instance = sfContext::getInstance()->getDatabaseConnection('txtLiveMemcache');
        }
        return self::$instance;
    }
}