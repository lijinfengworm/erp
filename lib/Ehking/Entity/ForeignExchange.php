<?php
/**
 * 
 *  
 * PHP Version 5
 *
 * @category  Class
 * @file      ForeignExchange.php
 * @package Ehking/Entity
 * @author    chao.ma <chao.ma@ehking.com>

 */

//namespace Ehking/Entity;


require_once dirname(__FILE__).'/AbstractModel.php';
class ForeignExchange extends AbstractModel{

    /**
     * 商家ID
     * @var
     */
    public $merchantId;

    /**
     * 订单号
     * @var
     */
    public $requestId;

    /**
     * 订单金额
     * @var
     */
    public $orderAmount;

    /**
     * 订单币种
     * @var
     */
    public $orderCurrency;

    /**
     * 购汇金额
     * @var
     */
    public $amount;
    /**
     * 购汇币种
     * @var
     */
    public $currency;


    /**
     * 购汇用途
     * @var
     */
    public $forUse;

    /**
     * 锁定牌价标识
     * @var
     */
    public $listPriceToken;

    /**
     * 备注
     * @var
     */
    public $remark;


    public $paymentModeCode;


    /**
     * @param mixed $amount
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param mixed $currency
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @param mixed $forUse
     */
    public function setForUse($forUse)
    {
        $this->forUse = $forUse;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getForUse()
    {
        return $this->forUse;
    }

    /**
     * @param mixed $listPriceToken
     */
    public function setListPriceToken($listPriceToken)
    {
        $this->listPriceToken = $listPriceToken;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getListPriceToken()
    {
        return $this->listPriceToken;
    }

    /**
     * @param mixed $merchantId
     */
    public function setMerchantId($merchantId)
    {
        $this->merchantId = $merchantId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMerchantId()
    {
        return $this->merchantId;
    }

    /**
     * @param mixed $orderAmount
     */
    public function setOrderAmount($orderAmount)
    {
        $this->orderAmount = $orderAmount;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getOrderAmount()
    {
        return $this->orderAmount;
    }

    /**
     * @param mixed $orderCurrency
     */
    public function setOrderCurrency($orderCurrency)
    {
        $this->orderCurrency = $orderCurrency;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getOrderCurrency()
    {
        return $this->orderCurrency;
    }

    /**
     * @param mixed $remark
     */
    public function setRemark($remark)
    {
        $this->remark = $remark;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRemark()
    {
        return $this->remark;
    }

    /**
     * @param mixed $requestId
     */
    public function setRequestId($requestId)
    {
        $this->requestId = $requestId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRequestId()
    {
        return $this->requestId;
    }

    /**
     * @param mixed $paymentModeCode
     */
    public function setPaymentModeCode($paymentModeCode)
    {
        $this->paymentModeCode = $paymentModeCode;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPaymentModeCode()
    {
        return $this->paymentModeCode;
    }



}