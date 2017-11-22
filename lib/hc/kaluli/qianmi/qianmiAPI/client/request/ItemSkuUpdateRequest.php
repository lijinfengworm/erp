<?php
/**
 * API: qianmi.cloudshop.item.sku.update request
 * 
 * @author auto create
 * @since 1.0
 */
class ItemSkuUpdateRequest
{
	private $apiParas = array();

	/** 
	 * 商品级别的条形码
	 */
	private $barcode;

	/** 
	 * 成本价，单位元，保留2位小数
	 */
	private $costPrice;

	/** 
	 * sku的市场价，单位元，保留2位小数
	 */
	private $marketPrice;

	/** 
	 * 商品编号Id
	 */
	private $numIid;

	/** 
	 * SKU对应的商家编码
	 */
	private $outerId;

	/** 
	 * 属于这个sku的商品的售价，保留2位小数，单位元
	 */
	private $price;

	/** 
	 * 属于这个sku的商品的数量
	 */
	private $quantity;

	/** 
	 * 副标题(卖点)
	 */
	private $sellPoint;

	/** 
	 * sku编号 ，g开头
	 */
	private $skuId;

	/** 
	 * sku的重量,单位kg,最多支持3位小数
	 */
	private $weight;

	public function setBarcode($barcode)
	{
		$this->barcode = $barcode;
		$this->apiParas["barcode"] = $barcode;
	}
	public function getBarcode() {
		return $this->barcode;
	}

	public function setCostPrice($costPrice)
	{
		$this->costPrice = $costPrice;
		$this->apiParas["cost_price"] = $costPrice;
	}
	public function getCostPrice() {
		return $this->costPrice;
	}

	public function setMarketPrice($marketPrice)
	{
		$this->marketPrice = $marketPrice;
		$this->apiParas["market_price"] = $marketPrice;
	}
	public function getMarketPrice() {
		return $this->marketPrice;
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

	public function setPrice($price)
	{
		$this->price = $price;
		$this->apiParas["price"] = $price;
	}
	public function getPrice() {
		return $this->price;
	}

	public function setQuantity($quantity)
	{
		$this->quantity = $quantity;
		$this->apiParas["quantity"] = $quantity;
	}
	public function getQuantity() {
		return $this->quantity;
	}

	public function setSellPoint($sellPoint)
	{
		$this->sellPoint = $sellPoint;
		$this->apiParas["sell_point"] = $sellPoint;
	}
	public function getSellPoint() {
		return $this->sellPoint;
	}

	public function setSkuId($skuId)
	{
		$this->skuId = $skuId;
		$this->apiParas["sku_id"] = $skuId;
	}
	public function getSkuId() {
		return $this->skuId;
	}

	public function setWeight($weight)
	{
		$this->weight = $weight;
		$this->apiParas["weight"] = $weight;
	}
	public function getWeight() {
		return $this->weight;
	}

	public function getApiMethodName()
	{
		return "qianmi.cloudshop.item.sku.update";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		RequestCheckUtil::checkNotNull($this->numIid, "numIid");
		RequestCheckUtil::checkNotNull($this->skuId, "skuId");
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}
