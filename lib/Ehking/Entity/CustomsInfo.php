<?php
/**
 * 
 *  
 * PHP Version 5
 *
 * @category  Class
 * @file      CustomsInfo.php
 * @package Ehking/Entity
 * @author    chao.ma <chao.ma@ehking.com>

 */

//namespace Ehking/Entity;

require_once dirname(__FILE__).'/AbstractModel.php';


class CustomsInfo extends AbstractModel{

    /**
     * 申报海关
     */
    public $customsChannel;

    /**
     * 报关金额
     */
    public $amount;

    /**
     * 报关金额
     */
    public $goodsAmount;

    /**
     * 支付税款
     */
    public $tax;

    /**
     * 支付运费
     */
    public $freight;
    public $merchantCommerceCode;
    public $merchantCommerceName;
    /**
     * 购汇订单ID
     */
  //  public $orderId;

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
     * @param mixed $customsChannel
     */
    public function setCustomsChannel($customsChannel)
    {
        $this->customsChannel = $customsChannel;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCustomsChannel()
    {
        return $this->customsChannel;
    }

    /**
     * @param mixed $freight
     */
    public function setFreight($freight)
    {
        $this->freight = $freight;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFreight()
    {
        return $this->freight;
    }

    /**
     * @param mixed $goodsAmount
     */
    public function setGoodsAmount($goodsAmount)
    {
        $this->goodsAmount = $goodsAmount;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getGoodsAmount()
    {
        return $this->goodsAmount;
    }

//    /**
//     * @param mixed $orderId
//     */
//    public function setOrderId($orderId)
//    {
//        $this->orderId = $orderId;
//        return $this;
//    }

    /**
     * @return mixed
     */
//    public function getOrderId()
//    {
//        return $this->orderId;
//    }

    /**
     * @param mixed $tax
     */
    public function setTax($tax)
    {
        $this->tax = $tax;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTax()
    {
        return $this->tax;
    }

    public function setMerchantCommerceCode($merchantCommerceCode)
    {
        $this->merchantCommerceCode = $merchantCommerceCode;
        return $this;
    }
    public function getMerchantCommerceCode()
    {
        return $this->merchantCommerceCode;
    }

    public function setMerchantCommerceName($merchantCommerceName)
    {
        $this->merchantCommerceName = $merchantCommerceName;
        return $this;
    }
    public function getMerchantCommerceName()
    {
        return $this->merchantCommerceName;
    }

} 