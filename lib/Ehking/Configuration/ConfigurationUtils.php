<?php
/**
 *
 *
 * PHP Version 5
 *
 * @category  Class
 * @file      ConfigurationUtils.php
 * @package ${NAMESPACE}
 * @author    chao.ma <chao.ma@ehking.com>

 */
//namespace Ehking/Configuration;

class ConfigurationUtils{

    private static $that;
    private static $configuration;

    private function __construct($config)
    {
        if(self::$configuration == null){
            if(is_file($config))
                self::$configuration = include $config;
            else if (is_array($config)){
                self::$configuration = $config;
            }
        }
    }

    public static function getInstance($config=null)
    {
        if(self::$that)
            return self::$that;

        if($config==null)
            $dir=dirname(__FILE__);
            $config = $dir.'/../Resources/config/parameters.php';
        self::$that = new ConfigurationUtils($config);

        return self::$that;
    }

    public function getHmacKey($merchantId)
    {
        if (isset(self::$configuration['merchant'][$merchantId]))
            return self::$configuration['merchant'][$merchantId];

        return null;
    }

    public function gethgOrderUrl()
    {
        if(isset(self::$configuration['hg.member.url'])){
            return self::$configuration['hg.member.url'];
        }
        return null;
    }
    public function gethgTransferUrl()
    {
        if(isset(self::$configuration['hg.transfer.url'])){
            return self::$configuration['hg.transfer.url'];
        }
        return null;
    }
    public function gethgRechargeUrl()
    {
        if(isset(self::$configuration['hg.recharge.url'])){
            return self::$configuration['hg.recharge.url'];
        }
        return null;
    }
    public function gethgPayUrl()
    {
        if(isset(self::$configuration['hg.pay.url'])){
            return self::$configuration['hg.pay.url'];
        }
        return null;
    }
    public function gethgQueryUrl()
    {
        if(isset(self::$configuration['hg.query.url'])){
            return self::$configuration['hg.query.url'];
        }
        return null;
    }
    public function gethgPayQueryUrl()
    {
        if(isset(self::$configuration['hg.payquery.url'])){
            return self::$configuration['hg.payquery.url'];
        }
        return null;
    }

    public function gethgMemberQueryUrl()
    {
        if(isset(self::$configuration['hg.memberquery.url'])){
            return self::$configuration['hg.memberquery.url'];
        }
        return null;
    }


}