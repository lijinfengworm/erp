<?php
/**
 * 
 *  
 * PHP Version 5
 *
 * @category  Class
 * @file      Payee.php
 * @package Ehking/Entity
 * @author    chao.ma <chao.ma@ehking.com>
 */

//namespace Ehking/Entity;


require_once dirname(__FILE__).'/AbstractModel.php';
class Payee extends AbstractModel {

    public $recName;

    public $accountNumber;

    public $recAddress;

    public $countryCode;

    public $ibanCode;

    public $bankName;

    public $swiftCode;

    public $routingCode;

    public $bsbCode;

    public $bankAddress;

    public $postScript;

    public $proxyBankAccountNumber;

    public $proxyBankName;

    public $proxySwiftCode;

    public $proxyBankAddress;

    /**
     * @param mixed $accountNumber
     */
    public function setAccountNumber($accountNumber)
    {
        $this->accountNumber = $accountNumber;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAccountNumber()
    {
        return $this->accountNumber;
    }

    /**
     * @param mixed $bankAddress
     */
    public function setBankAddress($bankAddress)
    {
        $this->bankAddress = $bankAddress;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getBankAddress()
    {
        return $this->bankAddress;
    }

    /**
     * @param mixed $bankName
     */
    public function setBankName($bankName)
    {
        $this->bankName = $bankName;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getBankName()
    {
        return $this->bankName;
    }

    /**
     * @param mixed $bsbCode
     */
    public function setBsbCode($bsbCode)
    {
        $this->bsbCode = $bsbCode;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getBsbCode()
    {
        return $this->bsbCode;
    }

    /**
     * @param mixed $countryCode
     */
    public function setCountryCode($countryCode)
    {
        $this->countryCode = $countryCode;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCountryCode()
    {
        return $this->countryCode;
    }

    /**
     * @param mixed $ibanCode
     */
    public function setIbanCode($ibanCode)
    {
        $this->ibanCode = $ibanCode;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIbanCode()
    {
        return $this->ibanCode;
    }

    /**
     * @param mixed $postScript
     */
    public function setPostScript($postScript)
    {
        $this->postScript = $postScript;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPostScript()
    {
        return $this->postScript;
    }

    /**
     * @param mixed $proxyBankAccountNumber
     */
    public function setProxyBankAccountNumber($proxyBankAccountNumber)
    {
        $this->proxyBankAccountNumber = $proxyBankAccountNumber;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getProxyBankAccountNumber()
    {
        return $this->proxyBankAccountNumber;
    }

    /**
     * @param mixed $proxyBankAddress
     */
    public function setProxyBankAddress($proxyBankAddress)
    {
        $this->proxyBankAddress = $proxyBankAddress;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getProxyBankAddress()
    {
        return $this->proxyBankAddress;
    }

    /**
     * @param mixed $proxyBankName
     */
    public function setProxyBankName($proxyBankName)
    {
        $this->proxyBankName = $proxyBankName;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getProxyBankName()
    {
        return $this->proxyBankName;
    }

    /**
     * @param mixed $proxySwiftCode
     */
    public function setProxySwiftCode($proxySwiftCode)
    {
        $this->proxySwiftCode = $proxySwiftCode;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getProxySwiftCode()
    {
        return $this->proxySwiftCode;
    }

    /**
     * @param mixed $recAddress
     */
    public function setRecAddress($recAddress)
    {
        $this->recAddress = $recAddress;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRecAddress()
    {
        return $this->recAddress;
    }

    /**
     * @param mixed $recName
     */
    public function setRecName($recName)
    {
        $this->recName = $recName;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRecName()
    {
        return $this->recName;
    }

    /**
     * @param mixed $routingCode
     */
    public function setRoutingCode($routingCode)
    {
        $this->routingCode = $routingCode;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRoutingCode()
    {
        return $this->routingCode;
    }

    /**
     * @param mixed $swiftCode
     */
    public function setSwiftCode($swiftCode)
    {
        $this->swiftCode = $swiftCode;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSwiftCode()
    {
        return $this->swiftCode;
    }



} 