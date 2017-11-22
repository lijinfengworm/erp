<?php
/**
 * API: qianmi.cloudshop.item.custom.get request
 * 
 * @author auto create
 * @since 1.0
 */
class ItemCustomGetRequest
{
	private $apiParas = array();

	/** 
	 * Item商品结构中的所有字段均可返回，多个字段用”,”分隔，如获取sku全部字段，只需要传skus,如只需要sku部分字段，请按照以下格式：sku.sku_id,sku.properties
	 */
	private $fields;

	/** 
	 * 外部商品编号
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
		return "qianmi.cloudshop.item.custom.get";
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
