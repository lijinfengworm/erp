<?php
/**
 * API: qianmi.cloudshop.logistics.address.search request
 * 
 * @author auto create
 * @since 1.0
 */
class LogisticsAddressSearchRequest
{
	private $apiParas = array();

	/** 
	 * 需要返回的字段
	 */
	private $fields;

	/** 
	 * 地址类型，默认获取所有地址
	 */
	private $rdef;

	public function setFields($fields)
	{
		$this->fields = $fields;
		$this->apiParas["fields"] = $fields;
	}
	public function getFields() {
		return $this->fields;
	}

	public function setRdef($rdef)
	{
		$this->rdef = $rdef;
		$this->apiParas["rdef"] = $rdef;
	}
	public function getRdef() {
		return $this->rdef;
	}

	public function getApiMethodName()
	{
		return "qianmi.cloudshop.logistics.address.search";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		RequestCheckUtil::checkNotNull($this->fields, "fields");
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}
