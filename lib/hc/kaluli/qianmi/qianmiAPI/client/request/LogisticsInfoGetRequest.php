<?php
/**
 * API: qianmi.cloudshop.logistics.info.get request
 * 
 * @author auto create
 * @since 1.0
 */
class LogisticsInfoGetRequest
{
	private $apiParas = array();

	/** 
	 * 物流公司编号
	 */
	private $code;

	/** 
	 * 物流单号
	 */
	private $expNo;

	public function setCode($code)
	{
		$this->code = $code;
		$this->apiParas["code"] = $code;
	}
	public function getCode() {
		return $this->code;
	}

	public function setExpNo($expNo)
	{
		$this->expNo = $expNo;
		$this->apiParas["exp_no"] = $expNo;
	}
	public function getExpNo() {
		return $this->expNo;
	}

	public function getApiMethodName()
	{
		return "qianmi.cloudshop.logistics.info.get";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		RequestCheckUtil::checkNotNull($this->code, "code");
		RequestCheckUtil::checkNotNull($this->expNo, "expNo");
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}
