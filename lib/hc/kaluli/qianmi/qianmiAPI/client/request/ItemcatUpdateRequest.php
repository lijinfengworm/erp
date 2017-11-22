<?php
/**
 * API: qianmi.cloudshop.itemcat.update request
 * 
 * @author auto create
 * @since 1.0
 */
class ItemcatUpdateRequest
{
	private $apiParas = array();

	/** 
	 * 需要修改的类目id
	 */
	private $cid;

	/** 
	 * 修改的类目名称
	 */
	private $name;

	public function setCid($cid)
	{
		$this->cid = $cid;
		$this->apiParas["cid"] = $cid;
	}
	public function getCid() {
		return $this->cid;
	}

	public function setName($name)
	{
		$this->name = $name;
		$this->apiParas["name"] = $name;
	}
	public function getName() {
		return $this->name;
	}

	public function getApiMethodName()
	{
		return "qianmi.cloudshop.itemcat.update";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		RequestCheckUtil::checkNotNull($this->cid, "cid");
		RequestCheckUtil::checkNotNull($this->name, "name");
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}
