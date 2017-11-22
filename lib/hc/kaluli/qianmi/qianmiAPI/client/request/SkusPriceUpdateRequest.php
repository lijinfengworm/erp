<?php
/**
 * API: qianmi.cloudshop.skus.price.update request
 * 
 * @author auto create
 * @since 1.0
 */
class SkusPriceUpdateRequest
{
	private $apiParas = array();

	/** 
	 * 商品编号
	 */
	private $numIid;

	/** 
	 * 更新的sku价格属性，格式为：sku_id:sku_price;sku_id:sku_price。一次最多只能更新10个sku的价格
	 */
	private $skuPrices;

	public function setNumIid($numIid)
	{
		$this->numIid = $numIid;
		$this->apiParas["num_iid"] = $numIid;
	}
	public function getNumIid() {
		return $this->numIid;
	}

	public function setSkuPrices($skuPrices)
	{
		$this->skuPrices = $skuPrices;
		$this->apiParas["sku_prices"] = $skuPrices;
	}
	public function getSkuPrices() {
		return $this->skuPrices;
	}

	public function getApiMethodName()
	{
		return "qianmi.cloudshop.skus.price.update";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		RequestCheckUtil::checkNotNull($this->skuPrices, "skuPrices");
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}
