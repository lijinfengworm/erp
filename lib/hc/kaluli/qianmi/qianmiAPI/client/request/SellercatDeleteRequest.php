<?php
/**
 * API: qianmi.cloudshop.sellercat.delete request
 * 
 * @author auto create
 * @since 1.0
 */
class SellercatDeleteRequest
{
	private $apiParas = array();

	/** 
	 * 需要删除的类目id
	 */
	private $sellerCid;

	/** 
	 * 产品线 1: 云订货(D2P) 2: 云商城(D2C)
	 */
	private $site;

	public function setSellerCid($sellerCid)
	{
		$this->sellerCid = $sellerCid;
		$this->apiParas["seller_cid"] = $sellerCid;
	}
	public function getSellerCid() {
		return $this->sellerCid;
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
		return "qianmi.cloudshop.sellercat.delete";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		RequestCheckUtil::checkNotNull($this->sellerCid, "sellerCid");
		RequestCheckUtil::checkNotNull($this->site, "site");
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}
