<?php
/**
 * API: qianmi.cloudshop.item.sku.price.update request
 * 
 * @author auto create
 * @since 1.0
 */
class ItemSkuPriceUpdateRequest
{
	private $apiParas = array();

	/** 
	 * sku所属商品编号
	 */
	private $numIid;

	/** 
	 * sku售价, 单位: 元, 两位小数
	 */
	private $price;

	/** 
	 * sku编号
	 */
	private $skuId;

	public function setNumIid($numIid)
	{
		$this->numIid = $numIid;
		$this->apiParas["num_iid"] = $numIid;
	}
	public function getNumIid() {
		return $this->numIid;
	}

	public function setPrice($price)
	{
		$this->price = $price;
		$this->apiParas["price"] = $price;
	}
	public function getPrice() {
		return $this->price;
	}

	public function setSkuId($skuId)
	{
		$this->skuId = $skuId;
		$this->apiParas["sku_id"] = $skuId;
	}
	public function getSkuId() {
		return $this->skuId;
	}

	public function getApiMethodName()
	{
		return "qianmi.cloudshop.item.sku.price.update";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		RequestCheckUtil::checkNotNull($this->numIid, "numIid");
		RequestCheckUtil::checkNotNull($this->price, "price");
		RequestCheckUtil::checkNotNull($this->skuId, "skuId");
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}
