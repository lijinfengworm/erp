<?php
/**
 * API: qianmi.cloudshop.itemcat.delete request
 * 
 * @author auto create
 * @since 1.0
 */
class ItemcatDeleteRequest
{
	private $apiParas = array();

	/** 
	 * 类目id
	 */
	private $cid;

	public function setCid($cid)
	{
		$this->cid = $cid;
		$this->apiParas["cid"] = $cid;
	}
	public function getCid() {
		return $this->cid;
	}

	public function getApiMethodName()
	{
		return "qianmi.cloudshop.itemcat.delete";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		RequestCheckUtil::checkNotNull($this->cid, "cid");
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}
