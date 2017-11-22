<?php
/**
 * API: qianmi.cloudshop.item.delisting request
 * 
 * @author auto create
 * @since 1.0
 */
class ItemDelistingRequest
{
	private $apiParas = array();

	/** 
	 * 商品编号
	 */
	private $numIid;

	/** 
	 * d2c,d2p商品标识
	 */
	private $site;

	public function setNumIid($numIid)
	{
		$this->numIid = $numIid;
		$this->apiParas["num_iid"] = $numIid;
	}
	public function getNumIid() {
		return $this->numIid;
	}

	public function setSite($site)
	{
		$this->site = $site;
		$this->apiParas["site"] = $site;
	}
	public function getSite() {
		return $this->site;
	}

	public function getApiMethodName()
	{
		return "qianmi.cloudshop.item.delisting";
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
