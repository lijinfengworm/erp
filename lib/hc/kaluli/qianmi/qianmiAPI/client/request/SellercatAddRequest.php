<?php
/**
 * API: qianmi.cloudshop.sellercat.add request
 * 
 * @author auto create
 * @since 1.0
 */
class SellercatAddRequest
{
	private $apiParas = array();

	/** 
	 * 类目名
	 */
	private $name;

	/** 
	 * 父目录编号,不传则默认顶级目录
	 */
	private $pSellerCid;

	/** 
	 * 产品线 1: 云订货(D2P) 2: 云商城(D2C) 默认 1
	 */
	private $site;

	public function setName($name)
	{
		$this->name = $name;
		$this->apiParas["name"] = $name;
	}
	public function getName() {
		return $this->name;
	}

	public function setpSellerCid($pSellerCid)
	{
		$this->pSellerCid = $pSellerCid;
		$this->apiParas["p_seller_cid"] = $pSellerCid;
	}
	public function getpSellerCid() {
		return $this->pSellerCid;
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
		return "qianmi.cloudshop.sellercat.add";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		RequestCheckUtil::checkNotNull($this->name, "name");
		RequestCheckUtil::checkNotNull($this->site, "site");
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}
