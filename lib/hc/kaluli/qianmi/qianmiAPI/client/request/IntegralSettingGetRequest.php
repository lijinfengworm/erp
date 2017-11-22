<?php
/**
 * API: qianmi.cloudshop.integral.setting.get request
 * 
 * @author auto create
 * @since 1.0
 */
class IntegralSettingGetRequest
{
	private $apiParas = array();

	public function getApiMethodName()
	{
		return "qianmi.cloudshop.integral.setting.get";
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
