<?php
/**
 * API: qianmi.cloudshop.delivery.templates.list request
 * 
 * @author auto create
 * @since 1.0
 */
class DeliveryTemplatesListRequest
{
	private $apiParas = array();

	/** 
	 * 需要返回的字段，多个字段之间以逗号隔开
	 */
	private $fields;

	/** 
	 * true   只查询默认模板，false  则查询所有包括默认模板
	 */
	private $getDef;

	public function setFields($fields)
	{
		$this->fields = $fields;
		$this->apiParas["fields"] = $fields;
	}
	public function getFields() {
		return $this->fields;
	}

	public function setGetDef($getDef)
	{
		$this->getDef = $getDef;
		$this->apiParas["get_def"] = $getDef;
	}
	public function getGetDef() {
		return $this->getDef;
	}

	public function getApiMethodName()
	{
		return "qianmi.cloudshop.delivery.templates.list";
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
