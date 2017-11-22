<?php
/**
 * API: qianmi.cloudshop.item.quantity.update request
 * 
 * @author auto create
 * @since 1.0
 */
class ItemQuantityUpdateRequest
{
	private $apiParas = array();

	/** 
	 * 商品编号
	 */
	private $numIid;

	/** 
	 * 库存修改值，当全量更新库存时，quantity必须为大于等于0的正整数；当增量更新库存时，quantity为整数，可小于等于0。若增量更新时传入的库存为负数，则负数与实际库存之和不能小于0
	 */
	private $quantity;

	/** 
	 * 货品编号
	 */
	private $skuId;

	/** 
	 * 库存更新方式，1全量更新 2增量更新。默认全量更新
	 */
	private $type;

	public function setNumIid($numIid)
	{
		$this->numIid = $numIid;
		$this->apiParas["num_iid"] = $numIid;
	}
	public function getNumIid() {
		return $this->numIid;
	}

	public function setQuantity($quantity)
	{
		$this->quantity = $quantity;
		$this->apiParas["quantity"] = $quantity;
	}
	public function getQuantity() {
		return $this->quantity;
	}

	public function setSkuId($skuId)
	{
		$this->skuId = $skuId;
		$this->apiParas["sku_id"] = $skuId;
	}
	public function getSkuId() {
		return $this->skuId;
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
		return "qianmi.cloudshop.item.quantity.update";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		RequestCheckUtil::checkNotNull($this->numIid, "numIid");
		RequestCheckUtil::checkNotNull($this->quantity, "quantity");
		RequestCheckUtil::checkNotNull($this->skuId, "skuId");
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}
