<?php
/**
 * API: qianmi.cloudshop.shop.get request
 * 
 * @author auto create
 * @since 1.0
 */
class ShopGetRequest
{
	private $apiParas = array();

	public function getApiMethodName()
	{
		return "qianmi.cloudshop.shop.get";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}
