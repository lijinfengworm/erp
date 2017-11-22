<?php
/**
 * 
 *  
 * PHP Version 5
 *
 * @category  Class
 * @file      QueryHandle.php
 * @package Ehking/ResponseHandle/Onlinepay
 * @author    chao.ma <chao.ma@ehking.com>
 */

//namespace Ehking/ResponseHandle/JointPay;


//use Ehking/ResponseHandle/ResponseTypeHandle;

$dir=dirname(__FILE__);
require_once dirname(__FILE__).'/../ResponseTypeHandle.php';

class QueryHandle extends ResponseTypeHandle {

    public function handle($data = array())
   {
        if (isset($data['status']) && $data['status'] == 'REDIRECT'){
            header("Location: {$data['redirectUrl']}");
            exit;
        } else if(isset($data['status']) && $data['status'] == 'SUCCESS'){
            echo "成功！";
           return $data;
        }else if(isset($data['status']) && $data['status'] == 'PROCESSING'){
            echo "处理中！";
           return $data;
       }else if(isset($data['status']) && $data['status'] == 'INIT'){
            echo "未支付！";
            return $data;
        }else if(isset($data['status']) && $data['status'] == 'FAILED'){
            echo "失败！";
            return $data;
        }
    }

} 