<?php
/**
 * API: qianmi.cloudshop.itemcats.get request
 * 
 * @author auto create
 * @since 1.0
 */
class ItemcatsGetRequest
{
	private $apiParas = array();

	/** 
	 * 展示类目编号列表，多个编号之间以“，”隔开
	 */
	private $cids;

	/** 
	 * 需要返回的字段
	 */
	private $fields;

	/** 
	 * 父类目编号，当cids为空时，才会用此字段进行查询
	 */
	private $parentCid;

	public function setCids($cids)
	{
		$this->cids = $cids;
		$this->apiParas["cids"] = $cids;
	}
	public function getCids() {
		return $this->cids;
	}

	public function setFields($fields)
	{
		$this->fields = $fields;
		$this->apiParas["fields"] = $fields;
	}
	public function getFields() {
		return $this->fields;
	}

	public function setParentCid($parentCid)
	{
		$this->parentCid = $parentCid;
		$this->apiParas["parent_cid"] = $parentCid;
	}
	public function getParentCid() {
		return $this->parentCid;
	}

	public function getApiMethodName()
	{
		return "qianmi.cloudshop.itemcats.get";
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
