<?php
/**
 * API: qianmi.cloudshop.item.delete request
 * 
 * @author auto create
 * @since 1.0
 */
class ItemDeleteRequest
{
	private $apiParas = array();

	/** 
	 * 商品编号ID
	 */
	private $numIid;

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
		return "qianmi.cloudshop.item.delete";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		RequestCheckUtil::checkNotNull($this->numIid, "numIid");
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}
