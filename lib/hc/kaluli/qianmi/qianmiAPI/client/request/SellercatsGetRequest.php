<?php
/**
 * API: qianmi.cloudshop.sellercats.get request
 * 
 * @author auto create
 * @since 1.0
 */
class SellercatsGetRequest
{
	private $apiParas = array();

	/** 
	 * 需要返回的字段，多个字段之间以逗号隔开
	 */
	private $fields;

	/** 
	 * 父类目编号
	 */
	private $pSellerCid;

	/** 
	 * 类目编号列表
	 */
	private $sellerCids;

	/** 
	 * 站点分类，1：云订货(D2P) 2：云商城(D2C)
	 */
	private $site;

	public function setFields($fields)
	{
		$this->fields = $fields;
		$this->apiParas["fields"] = $fields;
	}
	public function getFields() {
		return $this->fields;
	}

	public function setpSellerCid($pSellerCid)
	{
		$this->pSellerCid = $pSellerCid;
		$this->apiParas["p_seller_cid"] = $pSellerCid;
	}
	public function getpSellerCid() {
		return $this->pSellerCid;
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
		return "qianmi.cloudshop.sellercats.get";
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
