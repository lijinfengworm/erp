<?php

/**
 * 新声评论信息进行反垃圾信息过滤 
**/

class replyFilter{

    public function __construct(){
        //类的初始工作在这里
    }
    
    public static function infoFilter($apiconfig, $parameters){
        $result = SnsInterface::getContents($apiconfig['apiname'], $apiconfig['appid'], $apiconfig['key'], $parameters, 'POST');
        
        //if(($result < 0) || ($result !=1) || empty($result))
        if($result != 1)
        {
            if(is_array($result) && !empty($result['badwords']))
            {
                //有违禁词
                return -2;
            }else if(is_array($result) && !empty($result['recent'])){
                //发言太频繁了
                return -3;
            }else{
                //其他原因
                return -1;
            }
        }else{
            return 1;
        }
        return (($result < 0) || ($result !=1) || empty($result)) ? false : true;
        
        /*
        ** $result 说明
        *  1、$result < 0 ：接口调用失败；
        *  2、$result =1  : 非垃圾信息；
        **/
    }

}