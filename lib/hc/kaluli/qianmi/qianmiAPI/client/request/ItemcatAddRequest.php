<?php
/**
 * API: qianmi.cloudshop.itemcat.add request
 * 
 * @author auto create
 * @since 1.0
 */
class ItemcatAddRequest
{
	private $apiParas = array();

	/** 
	 * 目录名称
	 */
	private $name;

	/** 
	 * 父类目id，不传则默认顶级目录
	 */
	private $parentCid;

	public function setName($name)
	{
		$this->name = $name;
		$this->apiParas["name"] = $name;
	}
	public function getName() {
		return $this->name;
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
		return "qianmi.cloudshop.itemcat.add";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		RequestCheckUtil::checkNotNull($this->name, "name");
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}
