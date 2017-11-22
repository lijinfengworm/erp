<?php
/**
 * API: qianmi.cloudshop.sku.custom.get request
 * 
 * @author auto create
 * @since 1.0
 */
class SkuCustomGetRequest
{
	private $apiParas = array();

	/** 
	 * 返回字段
	 */
	private $fields;

	/** 
	 * 商家自定义的SKU外部编码
	 */
	private $outerId;

	public function setFields($fields)
	{
		$this->fields = $fields;
		$this->apiParas["fields"] = $fields;
	}
	public function getFields() {
		return $this->fields;
	}

	public function setOuterId($outerId)
	{
		$this->outerId = $outerId;
		$this->apiParas["outer_id"] = $outerId;
	}
	public function getOuterId() {
		return $this->outerId;
	}

	public function getApiMethodName()
	{
		return "qianmi.cloudshop.sku.custom.get";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		RequestCheckUtil::checkNotNull($this->fields, "fields");
		RequestCheckUtil::checkNotNull($this->outerId, "outerId");
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}
