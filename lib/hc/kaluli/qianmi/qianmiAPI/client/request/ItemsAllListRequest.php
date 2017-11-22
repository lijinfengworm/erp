<?php
/**
 * API: qianmi.cloudshop.items.all.list request
 * 
 * @author auto create
 * @since 1.0
 */
class ItemsAllListRequest
{
	private $apiParas = array();

	/** 
	 * 商品品牌ID
	 */
	private $brandId;

	/** 
	 * 商品类目ID
	 */
	private $cid;

	/** 
	 * 需返回字段列表，如商品名称、价格等。返回多个字段时，以逗号分隔。
	 */
	private $fields;

	/** 
	 * 排序格式：column:asc/desc，column可选值：cid(标准类目编号)、num(商品数量)，brand_id(品牌编号)，type_id(商品类型编号)
	 */
	private $orderBy;

	/** 
	 * 页码，大于等于0的整数，默认值0
	 */
	private $pageNo;

	/** 
	 * 每页条数，取大于0的整数，最大值50，默认值50
	 */
	private $pageSize;

	/** 
	 * 库存预警状态
	 */
	private $stockWarn;

	/** 
	 * 商品类型ID
	 */
	private $typeId;

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

	public function setFields($fields)
	{
		$this->fields = $fields;
		$this->apiParas["fields"] = $fields;
	}
	public function getFields() {
		return $this->fields;
	}

	public function setOrderBy($orderBy)
	{
		$this->orderBy = $orderBy;
		$this->apiParas["order_by"] = $orderBy;
	}
	public function getOrderBy() {
		return $this->orderBy;
	}

	public function setPageNo($pageNo)
	{
		$this->pageNo = $pageNo;
		$this->apiParas["page_no"] = $pageNo;
	}
	public function getPageNo() {
		return $this->pageNo;
	}

	public function setPageSize($pageSize)
	{
		$this->pageSize = $pageSize;
		$this->apiParas["page_size"] = $pageSize;
	}
	public function getPageSize() {
		return $this->pageSize;
	}

	public function setStockWarn($stockWarn)
	{
		$this->stockWarn = $stockWarn;
		$this->apiParas["stock_warn"] = $stockWarn;
	}
	public function getStockWarn() {
		return $this->stockWarn;
	}

	public function setTypeId($typeId)
	{
		$this->typeId = $typeId;
		$this->apiParas["type_id"] = $typeId;
	}
	public function getTypeId() {
		return $this->typeId;
	}

	public function getApiMethodName()
	{
		return "qianmi.cloudshop.items.all.list";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		RequestCheckUtil::checkNotNull($this->fields, "fields");
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}
