<?php
/**
 * API: qianmi.cloudshop.item.sellercats.list request
 * 
 * @author auto create
 * @since 1.0
 */
class ItemSellercatsListRequest
{
	private $apiParas = array();

	/** 
	 * 需返回字段列表。返回多个字段时，以逗号分隔。
	 */
	private $fields;

	/** 
	 * 商品编号，多个商品以”,“号分隔,每次不超过50条商品
	 */
	private $numIids;

	/** 
	 * 商品挂靠的销售渠道， 1:云订货(D2P)，2：云商城(D2C)  
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

	public function setNumIids($numIids)
	{
		$this->numIids = $numIids;
		$this->apiParas["num_iids"] = $numIids;
	}
	public function getNumIids() {
		return $this->numIids;
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
		return "qianmi.cloudshop.item.sellercats.list";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		RequestCheckUtil::checkNotNull($this->fields, "fields");
		RequestCheckUtil::checkNotNull($this->numIids, "numIids");
		RequestCheckUtil::checkNotNull($this->site, "site");
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}
