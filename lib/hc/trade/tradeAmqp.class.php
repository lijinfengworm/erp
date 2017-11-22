<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2014/12/19
 * Time: 11:50
 */
class tradeAmqp{

    private static $connection;

    public static function GetConnection()
    {
        if (!(self::$connection instanceof AMQPConnection))
        {
            $amqpParams = sfConfig::get("app_mabbitmq_options_shihuo");
            self::$connection = new AMQPConnection($amqpParams['params']['host'], $amqpParams['params']['port'],$amqpParams['params']['user'], $amqpParams['params']['pass'], $amqpParams['params']['vhost']);
        }
        return self::$connection;
    }

}



