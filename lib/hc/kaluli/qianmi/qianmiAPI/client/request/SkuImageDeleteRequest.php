<?php
/**
 * API: qianmi.cloudshop.sku.image.delete request
 * 
 * @author auto create
 * @since 1.0
 */
class SkuImageDeleteRequest
{
	private $apiParas = array();

	/** 
	 * 图片编号
	 */
	private $imgId;

	/** 
	 * 商品编号
	 */
	private $numIid;

	/** 
	 * sku编号
	 */
	private $skuId;

	public function setImgId($imgId)
	{
		$this->imgId = $imgId;
		$this->apiParas["img_id"] = $imgId;
	}
	public function getImgId() {
		return $this->imgId;
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
		return "qianmi.cloudshop.sku.image.delete";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		RequestCheckUtil::checkNotNull($this->imgId, "imgId");
		RequestCheckUtil::checkNotNull($this->numIid, "numIid");
		RequestCheckUtil::checkNotNull($this->skuId, "skuId");
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}
