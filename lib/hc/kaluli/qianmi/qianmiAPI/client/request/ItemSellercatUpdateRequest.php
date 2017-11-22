<?php
/**
 * API: qianmi.cloudshop.item.sellercat.update request
 * 
 * @author auto create
 * @since 1.0
 */
class ItemSellercatUpdateRequest
{
	private $apiParas = array();

	/** 
	 * 商品编号
	 */
	private $numIid;

	/** 
	 * 商品所属展示类目id列表，多个id之间以逗号隔开
	 */
	private $sellerCids;

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

	public function setSellerCids($sellerCids)
	{
		$this->sellerCids = $sellerCids;
		$this->apiParas["seller_cids"] = $sellerCids;
	}
	public function getSellerCids() {
		return $this->sellerCids;
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
		return "qianmi.cloudshop.item.sellercat.update";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		RequestCheckUtil::checkNotNull($this->numIid, "numIid");
		RequestCheckUtil::checkNotNull($this->sellerCids, "sellerCids");
		RequestCheckUtil::checkNotNull($this->site, "site");
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}
