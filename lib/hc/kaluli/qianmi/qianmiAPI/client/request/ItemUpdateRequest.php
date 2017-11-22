<?php
/**
 * API: qianmi.cloudshop.item.update request
 * 
 * @author auto create
 * @since 1.0
 */
class ItemUpdateRequest
{
	private $apiParas = array();

	/** 
	 * 品牌编号，当商品为自定义商品时可以修改品牌
	 */
	private $brandId;

	/** 
	 * 商品类目id
	 */
	private $cid;

	/** 
	 * 关键词，多个关键词用逗号“，”分开，且最多只能输入5个关键字
	 */
	private $keywords;

	/** 
	 * 商品编号ID
	 */
	private $numIid;

	/** 
	 * 商家的外部编码，当商品为自定义商品时可以修改商家外部编码
	 */
	private $outerId;

	/** 
	 * 商品关联销售渠道，0：关联所有已开通渠道，1：仅云订货，2：仅云商城，3 取消关联所有销售渠道
	 */
	private $site;

	/** 
	 * 商品名称(货柜名称)
	 */
	private $title;

	/** 
	 * 计量单位，当商品为自定义商品时可以修改计量单位
	 */
	private $unit;

	public function setBrandId($brandId)
	{
		$this->brandId = $brandId;
		$this->apiParas["brand_id"] = $brandId;
	}
	public function getBrandId() {
		return $this->brandId;
	}

	public function setCid($cid)
	{
		$this->cid = $cid;
		$this->apiParas["cid"] = $cid;
	}
	public function getCid() {
		return $this->cid;
	}

	public function setKeywords($keywords)
	{
		$this->keywords = $keywords;
		$this->apiParas["keywords"] = $keywords;
	}
	public function getKeywords() {
		return $this->keywords;
	}

	public function setNumIid($numIid)
	{
		$this->numIid = $numIid;
		$this->apiParas["num_iid"] = $numIid;
	}
	public function getNumIid() {
		return $this->numIid;
	}

	public function setOuterId($outerId)
	{
		$this->outerId = $outerId;
		$this->apiParas["outer_id"] = $outerId;
	}
	public function getOuterId() {
		return $this->outerId;
	}

	public function setSite($site)
	{
		$this->site = $site;
		$this->apiParas["site"] = $site;
	}
	public function getSite() {
		return $this->site;
	}

	public function setTitle($title)
	{
		$this->title = $title;
		$this->apiParas["title"] = $title;
	}
	public function getTitle() {
		return $this->title;
	}

	public function setUnit($unit)
	{
		$this->unit = $unit;
		$this->apiParas["unit"] = $unit;
	}
	public function getUnit() {
		return $this->unit;
	}

	public function getApiMethodName()
	{
		return "qianmi.cloudshop.item.update";
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
