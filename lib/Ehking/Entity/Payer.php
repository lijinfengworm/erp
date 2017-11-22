<?php
/**
 * 
 *  
 * PHP Version 5
 *
 * @category  Class
 * @file      Payer.php
 * @package Ehking/Entity
 * @author    chao.ma <chao.ma@ehking.com>

 */

//namespace Ehking/Entity;


require_once dirname(__FILE__).'/AbstractModel.php';
class Payer extends AbstractModel{

    /**
     * 付款人
     */
    public $payerName;


    /**
     * 证件号码
     */
    public $idNum;

    /**
     * 电话
     */
    public $phoneNum;



    /**
     * @param mixed $idNum
     */
    public function setIdNum($idNum)
    {
        $this->idNum = $idNum;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIdNum()
    {
        return $this->idNum;
    }



    /**
     * @param mixed $name
     */
    public function setpayerName($payerName)
    {
        $this->payerName = $payerName;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getpayerName()
    {
        return $this->payerName;
    }


    /**
     * @param mixed $phoneNum
     */
    public function setPhoneNum($phoneNum)
    {
        $this->phoneNum = $phoneNum;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPhoneNum()
    {
        return $this->phoneNum;
    }



} 