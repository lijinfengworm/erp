<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of tradeGetUserInfo
 *
 * @author Administrator
 */
class kaluliLog {

    static function debug($key,$attributes = array())
    {
        self::log('debug',$key,$attributes);
    }
    static function info($key,$attributes = array())
    {
        self::log('info',$key,$attributes);
    }
    static function waring($key,$attributes = array())
    {
        self::log('waring',$key,$attributes);
    }
    static function error($key,$attributes = array())
    {
        self::log('error',$key,$attributes);
    }
    static function log($level,$key,$attributes = array())
    {
        $time = time();
        $res = array(
            'evt'=>'kaluli',
            'vtm'=>$time,
            'body'=>array(
                'body.level'=>$level,
                'body.group_key'=>$key,
            ),
        );
        if(!empty($attributes)){
            foreach($attributes as $key=>$val)
            {
                $res['body']['body.'.$key] = $val;
            }
        }
        $res['body']['body.called_at'] = $time;
        if (!is_dir('/data0/log-data/')) mkdir('/data0/log-data/', 0777);
        file_put_contents("/data0/log-data/kaluli.log", json_encode($res)."\n", FILE_APPEND);
    }
}

?>
