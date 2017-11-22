<?php
/**
 * API: qianmi.cloudshop.items.list.get request
 * 
 * @author auto create
 * @since 1.0
 */
class ItemsListGetRequest
{
	private $apiParas = array();

	/** 
	 * 需返回字段列表，如商品名称、价格等。返回多个字段时，以逗号分隔。
	 */
	private $fields;

	/** 
	 * 商品编号id列表，以逗号隔开
	 */
	private $numIids;

	public function setFields($fields)
	{
		$this->fields = $fields;
		$this->apiParas["fields"] = $fields;
	}
	public function getFields() {
		return $this->fields;
	}

	public function setNumIids($numIids)
	{
		$this->numIids = $numIids;
		$this->apiParas["num_iids"] = $numIids;
	}
	public function getNumIids() {
		return $this->numIids;
	}

	public function getApiMethodName()
	{
		return "qianmi.cloudshop.items.list.get";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		RequestCheckUtil::checkNotNull($this->fields, "fields");
		RequestCheckUtil::checkNotNull($this->numIids, "numIids");
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}
