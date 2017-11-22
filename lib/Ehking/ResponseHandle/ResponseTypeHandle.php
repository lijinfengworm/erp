<?php
/**
 *
 *
 * PHP Version 5
 *
 * @category  Class
 * @file      ResponseTypeHandle.php
 * @package Ehking/ResponseType
 * @author    chao.ma <chao.ma@ehking.com>
 */

//namespace Ehking/ResponseHandle;


//use Ehking/Excation/InvalidResponseException;
require_once 'HandleInterface.php';
require_once dirname(__FILE__).'/../Excation/InvalidResponseException.php';

abstract class ResponseTypeHandle implements HandleInterface {

    public function handle($data = array())
    {
        if (isset($data['status']) && $data['status'] == 'REDIRECT'){
            header("Location: {$data['redirectUrl']}");
            exit;
        } else if(isset($data['status']) && $data['status'] == 'SUCCESS'){

            return $data;
        }else{
            throw new InvalidResponseException(array(
                'error_description'=>'Response Error',
                'responseData'=>$data
            ));
        }
    }
}