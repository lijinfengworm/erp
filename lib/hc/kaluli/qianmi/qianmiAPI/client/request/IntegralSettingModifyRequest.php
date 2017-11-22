<?php
/**
 * API: qianmi.cloudshop.integral.setting.modify request
 * 
 * @author auto create
 * @since 1.0
 */
class IntegralSettingModifyRequest
{
	private $apiParas = array();

	/** 
	 * 积分设置，范围1-1000之间的整数 
	 */
	private $rate;

	public function setRate($rate)
	{
		$this->rate = $rate;
		$this->apiParas["rate"] = $rate;
	}
	public function getRate() {
		return $this->rate;
	}

	public function getApiMethodName()
	{
		return "qianmi.cloudshop.integral.setting.modify";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		RequestCheckUtil::checkNotNull($this->rate, "rate");
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}
