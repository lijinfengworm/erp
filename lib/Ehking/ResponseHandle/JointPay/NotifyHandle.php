<?php
/**
 * 
 *  
 * PHP Version 5
 *
 * @category  Class
 * @file      NotifyHandle.php
 * @package Ehking/ResponseHandle/Onlinepay
 * @author    chao.ma <chao.ma@ehking.com>
 */

//namespace Ehking/ResponseHandle/JointPay;


//use Ehking/Excation/InvalidResponseException;
//use Ehking/ResponseHandle/ResponseTypeHandle;

$dir=dirname(__FILE__);
require_once dirname(__FILE__).'/../../Excation/InvalidResponseException.php';
require_once dirname(__FILE__).'/../ResponseTypeHandle.php';

class NotifyHandle extends ResponseTypeHandle{

    public function handle($data = array())
    {
        $aa=$data['customsChannel'];
        echo $aa;
        if($data['status']== 'SUCCESS'){
            //成功时相关处理代码
            //echo "SUCCESS"; //打印出 SUCCESS 表示收到通知
        }elseif($data['status'] == 'FAILED'){
            //失败时相关处理代码
        }elseif($data['status'] == 'PROCESSING'){
            //待处理状态下相关处理代码
        }else{
            throw new InvalidResponseException(array(
                'error_description'=> 'notify response error',
                'responseData'=>$data
            ));
        }
    }


}