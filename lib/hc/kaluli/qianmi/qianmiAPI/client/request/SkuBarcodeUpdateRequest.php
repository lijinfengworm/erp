<?php
/**
 * API: qianmi.cloudshop.sku.barcode.update request
 * 
 * @author auto create
 * @since 1.0
 */
class SkuBarcodeUpdateRequest
{
	private $apiParas = array();

	/** 
	 * SKU级别的条形码
	 */
	private $barcode;

	/** 
	 * 商品编号Id
	 */
	private $numIid;

	/** 
	 * sku编号，g开头
	 */
	private $skuId;

	public function setBarcode($barcode)
	{
		$this->barcode = $barcode;
		$this->apiParas["barcode"] = $barcode;
	}
	public function getBarcode() {
		return $this->barcode;
	}

	public function setNumIid($numIid)
	{
		$this->numIid = $numIid;
		$this->apiParas["num_iid"] = $numIid;
	}
	public function getNumIid() {
		return $this->numIid;
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
		return "qianmi.cloudshop.sku.barcode.update";
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
