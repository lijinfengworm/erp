<?php



$dir=dirname(__FILE__);
require_once $dir.'/../../Configuration/ConfigurationUtils.php';
require_once $dir.'/../../FormProcess/Process.php';
require_once $dir.'/../../ResponseHandle/JointPay/OrderHandle.php';

class RechargeBuilder extends Process{

    public $merchantId;
    public $requestId;
    public $rechargeMemberId;
    public $orderAmount;

    public $orderCurrency;
    public $notifyUrl;
    public $callbackUrl;
    public function builder($params)
    {
        $this->merchantId = $params['merchantId'];
        $this->requestId = $params['requestId'];
        $this->rechargeMemberId = $params['rechargeMemberId'];
        $this->orderAmount = $params['orderAmount'];
        $this->orderCurrency = $params['orderCurrency'];
        $this->notifyUrl = $params['notifyUrl'];
        $this->callbackUrl = $params['callbackUrl'];
        $handle = new OrderHandle();
        return $this->execute(
            ConfigurationUtils::getInstance()->getJointPayRechargeUrl(),
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
        $hmacSource .= $this->rechargeMemberId;
        $hmacSource .= $this->orderAmount;
        $hmacSource .= $this->orderCurrency;
        $hmacSource .= $this->notifyUrl;
        $hmacSource .= $this->callbackUrl;
        return $this->encipher( $hmacSource, ConfigurationUtils::getInstance()->getHmacKey($this->merchantId));
    }

} 