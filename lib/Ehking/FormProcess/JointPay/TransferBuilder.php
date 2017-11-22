<?php



$dir=dirname(__FILE__);
require_once $dir.'/../../Configuration/ConfigurationUtils.php';
require_once $dir.'/../../FormProcess/Process.php';
require_once $dir.'/../../ResponseHandle/JointPay/OrderHandle.php';

class TransferBuilder extends Process{

    public $merchantId;
    public $requestId;
    public $fromMember;
    public $toMember;
    public $amount;
    public $currency;


    public function builder($params)
    {
        $this->merchantId = $params['merchantId'];
        $this->requestId = $params['requestId'];
        $this->fromMember = $params['fromMember'];
        $this->toMember = $params['toMember'];
        $this->amount = $params['amount'];
        $this->currency = $params['currency'];

        $handle = new OrderHandle();
        return $this->execute(
            ConfigurationUtils::getInstance()->getJointPayTransferUrl(),
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
        $hmacSource .= $this->fromMember;
        $hmacSource .= $this->toMember;
        $hmacSource .= $this->amount;
        $hmacSource .= $this->currency;
        return $this->encipher( $hmacSource, ConfigurationUtils::getInstance()->getHmacKey($this->merchantId));
    }

} 