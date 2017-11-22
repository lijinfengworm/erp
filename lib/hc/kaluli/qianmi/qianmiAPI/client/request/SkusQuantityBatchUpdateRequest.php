<?php
/**
 * API: qianmi.cloudshop.skus.quantity.batch.update request
 * 
 * @author auto create
 * @since 1.0
 */
class SkusQuantityBatchUpdateRequest
{
	private $apiParas = array();

	/** 
	 * sku库存批量修改入参，用于指定一批sku和每个sku的库存修改值。格式为skuId:quantity;skuId:quantity。当全量更新库存时，quantity必须为大于等于0的正整数；当增量更新库存时，quantity为整数，可小于等于0。若增量更新时传入的库存为负数，则负数与实际库存之和不能小于0
	 */
	private $skuidQuantity;

	/** 
	 * 库存更新方式，1全量更新 2增量更新。默认全量更新
	 */
	private $type;

	public function setSkuidQuantity($skuidQuantity)
	{
		$this->skuidQuantity = $skuidQuantity;
		$this->apiParas["skuid_quantity"] = $skuidQuantity;
	}
	public function getSkuidQuantity() {
		return $this->skuidQuantity;
	}

	public function setType($type)
	{
		$this->type = $type;
		$this->apiParas["type"] = $type;
	}
	public function getType() {
		return $this->type;
	}

	public function getApiMethodName()
	{
		return "qianmi.cloudshop.skus.quantity.batch.update";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		RequestCheckUtil::checkNotNull($this->skuidQuantity, "skuidQuantity");
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}
