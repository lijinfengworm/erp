<?php
/**
 * API: qianmi.cloudshop.item.sku.delete request
 * 
 * @author auto create
 * @since 1.0
 */
class ItemSkuDeleteRequest
{
	private $apiParas = array();

	/** 
	 * 商品编号ID
	 */
	private $numIid;

	/** 
	 * 规格项的key:value
	 */
	private $properties;

	public function setNumIid($numIid)
	{
		$this->numIid = $numIid;
		$this->apiParas["num_iid"] = $numIid;
	}
	public function getNumIid() {
		return $this->numIid;
	}

	public function setProperties($properties)
	{
		$this->properties = $properties;
		$this->apiParas["properties"] = $properties;
	}
	public function getProperties() {
		return $this->properties;
	}

	public function getApiMethodName()
	{
		return "qianmi.cloudshop.item.sku.delete";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		RequestCheckUtil::checkNotNull($this->numIid, "numIid");
		RequestCheckUtil::checkNotNull($this->properties, "properties");
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}
