<?php
/**
 * 
 *  
 * PHP Version 5
 *
 * @category  Class
 * @file      ProductDetail.php
 * @package Ehking/Entity
 * @author    chao.ma <chao.ma@ehking.com>

 */

//namespace Ehking/Entity;

require_once dirname(__FILE__).'/AbstractModel.php';

class ProductDetail extends AbstractModel{

    /**
     * 产品名称
     */
    public $name;

    /**
     * 数量
     */
    public $quantity;

    /**
     * 金额
     */
    public $amount;

    /**
     * 收款人
     */
    public $receiver;

    /**
     * 产品描述
     */
    public $description;

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
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $quantity
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @param mixed $receiver
     */
    public function setReceiver($receiver)
    {
        $this->receiver = $receiver;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getReceiver()
    {
        return $this->receiver;
    }



} 