<?php
/**
 * API: qianmi.cloudshop.item.detail.update request
 * 
 * @author auto create
 * @since 1.0
 */
class ItemDetailUpdateRequest
{
	private $apiParas = array();

	/** 
	 * 商品详情介绍
	 */
	private $desc;

	/** 
	 * 商品编号
	 */
	private $numIid;

	public function setDesc($desc)
	{
		$this->desc = $desc;
		$this->apiParas["desc"] = $desc;
	}
	public function getDesc() {
		return $this->desc;
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
		return "qianmi.cloudshop.item.detail.update";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		RequestCheckUtil::checkNotNull($this->desc, "desc");
		RequestCheckUtil::checkNotNull($this->numIid, "numIid");
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}
