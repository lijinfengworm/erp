<?php
/**
 * 
 *  
 * PHP Version 5
 *
 * @category  Class
 * @file      QueryBuilder.php
 * @package Ehking/FormProcess/Onlinepay
 * @author    chao.ma <chao.ma@ehking.com>
 */

//namespace Ehking/FormProcess/JointPay;


//use Ehking/Configuration/ConfigurationUtils;
//use Ehking/FormProcess/Process;
//use Ehking/ResponseHandle/JointPay/QueryHandle;


$dir=dirname(__FILE__);
require_once $dir.'/../../Configuration/ConfigurationUtils.php';
require_once $dir.'/../../FormProcess/Process.php';
require_once $dir.'/../../ResponseHandle/JointPay/QueryHandle.php';

class QueryBuilder extends Process{

    public $merchantId;
    public $requestId;


    public function builder($params)
    {
        $this->merchantId = $params['merchantId'];
        $this->requestId = $params['requestId'];

        $handle = new QueryHandle();
        return $this->execute(
            ConfigurationUtils::getInstance()->getJointPayQueryUrl(),
            $this->buildJson(),
            $handle
        );
    }

    /**
     * 生成认证串
     * @return mixed
     */
    function generateHmac()
    {
        $hmacSource = "";
        $hmacSource .= $this->merchantId;
        $hmacSource .= $this->requestId;
        return $this->encipher( $hmacSource, ConfigurationUtils::getInstance()->getHmacKey($this->merchantId));
    }

} 