<?php
/**
 * 
 *  
 * PHP Version 5
 *
 * @category  Class
 * @file      BankCard.php
 * @package Ehking/Entity
 * @author    chao.ma <chao.ma@ehking.com>

 */

//namespace Ehking/Entity;


require_once dirname(__FILE__).'/AbstractModel.php';
class BankCard extends AbstractModel{

    /**
     * 开户名
     */
    public $name;

    /**
     * 卡号
     */
    public $cardNo;

    /**
     * cvv2
     */
    public $cvv2;

    /**
     * 身份证号
     */
    public $idNo;

    /**
     * 有效期yyyy-MM
     */
    public $expiryDate;

    /**
     * 手机号
     */
    public $mobileNo;

    /**
     * @param mixed $cardNo
     */
    public function setCardNo($cardNo)
    {
        $this->cardNo = $cardNo;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCardNo()
    {
        return $this->cardNo;
    }

    /**
     * @param mixed $cvv2
     */
    public function setCvv2($cvv2)
    {
        $this->cvv2 = $cvv2;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCvv2()
    {
        return $this->cvv2;
    }

    /**
     * @param mixed $expiryDate
     */
    public function setExpiryDate($expiryDate)
    {
        $this->expiryDate = $expiryDate;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getExpiryDate()
    {
        return $this->expiryDate;
    }

    /**
     * @param mixed $idNo
     */
    public function setIdNo($idNo)
    {
        $this->idNo = $idNo;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIdNo()
    {
        return $this->idNo;
    }

    /**
     * @param mixed $mobileNo
     */
    public function setMobileNo($mobileNo)
    {
        $this->mobileNo = $mobileNo;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMobileNo()
    {
        return $this->mobileNo;
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


} 