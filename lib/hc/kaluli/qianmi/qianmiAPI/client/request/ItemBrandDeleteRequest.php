<?php
/**
 * API: qianmi.cloudshop.item.brand.delete request
 * 
 * @author auto create
 * @since 1.0
 */
class ItemBrandDeleteRequest
{
	private $apiParas = array();

	/** 
	 * 品牌编号
	 */
	private $brandId;

	public function setBrandId($brandId)
	{
		$this->brandId = $brandId;
		$this->apiParas["brand_id"] = $brandId;
	}
	public function getBrandId() {
		return $this->brandId;
	}

	public function getApiMethodName()
	{
		return "qianmi.cloudshop.item.brand.delete";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		RequestCheckUtil::checkNotNull($this->brandId, "brandId");
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}
