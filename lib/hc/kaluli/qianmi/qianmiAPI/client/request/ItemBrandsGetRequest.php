<?php
/**
 * API: qianmi.cloudshop.item.brands.get request
 * 
 * @author auto create
 * @since 1.0
 */
class ItemBrandsGetRequest
{
	private $apiParas = array();

	/** 
	 * 需要返回的字段
	 */
	private $fields;

	public function setFields($fields)
	{
		$this->fields = $fields;
		$this->apiParas["fields"] = $fields;
	}
	public function getFields() {
		return $this->fields;
	}

	public function getApiMethodName()
	{
		return "qianmi.cloudshop.item.brands.get";
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
