<?php
/**
 * API: qianmi.cloudshop.item.get request
 * 
 * @author auto create
 * @since 1.0
 */
class ItemGetRequest
{
	private $apiParas = array();

	/** 
	 * 返回的字段列表
	 */
	private $fields;

	/** 
	 * 商品编号id
	 */
	private $numIid;

	public function setFields($fields)
	{
		$this->fields = $fields;
		$this->apiParas["fields"] = $fields;
	}
	public function getFields() {
		return $this->fields;
	}

	public function setNumIid($numIid)
	{
		$this->numIid = $numIid;
		$this->apiParas["num_iid"] = $numIid;
	}
	public function getNumIid() {
		return $this->numIid;
	}

	public function getApiMethodName()
	{
		return "qianmi.cloudshop.item.get";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		RequestCheckUtil::checkNotNull($this->fields, "fields");
		RequestCheckUtil::checkNotNull($this->numIid, "numIid");
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}
