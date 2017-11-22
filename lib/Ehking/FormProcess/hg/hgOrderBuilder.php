<?php
/**
 *
 *
 * PHP Version 5
 *
 * @category  Class
 * @file      hgOrderBuilder.php
 * @package Ehking/FormProcess/hg
 * @author    chao.ma <chao.ma@ehking.com>
 */

//namespace Ehking/FormProcess/hg;


//use Ehking/Configuration/ConfigurationUtils;
//use Ehking/Controller/JointPayController;
//use Ehking/Entity/CustomsInfo;
//use Ehking/Entity/Payer;
//use Ehking/Entity/ProductDetail;
//use Ehking/FormProcess/Process;
//use Ehking/ResponseHandle/hg/OrderHandle;

$dir=dirname(__FILE__);
require_once $dir.'/../../Configuration/ConfigurationUtils.php';
require_once $dir . '/../../Controller/hgController.php';
require_once $dir.'/../../Entity/CustomsInfo.php';
require_once $dir.'/../../Entity/Payer.php';
require_once $dir.'/../../Entity/ProductDetail.php';
require_once $dir.'/../../FormProcess/Process.php';
require_once $dir.'/../../ResponseHandle/hg/hgOrderHandle.php';

class hgOrderBuilder extends Process{

    /**
     * 商编
     * @var
     */
    public $merchantId;
    public $mobile;
    public $email;
    public $realname;

    public $idNum;
    public $accountType;
    public $userType;
    public $bindPayment;
    public $customerId;
    /**
     * 请求号
     * @var
     */



    public function builder($params)
    {

        //申报海关信息

        $this->merchantId = $params['merchantId'];
        $this->mobile = $params['mobile'];
        $this->email = $params['email'];
        $this->realname = $params['realname'];
        $this->idNum = $params['idNum'];

        $this->userType = $params['userType'];
        $this->bindPayment = $params['bindPayment'];
        $this->accountType = $params['accountType'];
        $this->customerId=$params['customerId'];


        $handle = new hgOrderHandle();
        return $this->execute(
            ConfigurationUtils::getInstance()->gethgOrderUrl(),
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
        $hmacSource = '';
        $hmacSource .= $this->merchantId;
        $hmacSource .= $this->mobile;
        $hmacSource .= $this->email;
        $hmacSource .= $this->realname;
        $hmacSource .= $this->idNum;
        $hmacSource .= $this->userType;
        $hmacSource .= $this->bindPayment;
        $hmacSource .= $this->accountType;
        $hmacSource .= $this->customerId;

        $key = ConfigurationUtils::getInstance()->getHmacKey($this->merchantId);
        
        return $this->encipher( $hmacSource,$key);
    }


}