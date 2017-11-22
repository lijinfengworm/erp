<?php
/**
 * API: qianmi.cloudshop.returns.list request
 * 
 * @author auto create
 * @since 1.0
 */
class ReturnsListRequest
{
	private $apiParas = array();

	/** 
	 * 退货单创建结束时间 格式yyyy-MM-dd HH：mm:ss
	 */
	private $endCreated;

	/** 
	 * 需要返回的字段
	 */
	private $fields;

	/** 
	 * 买家编号
	 */
	private $memberId;

	/** 
	 * 页码，从0开始
	 */
	private $pageNo;

	/** 
	 * 每页条数，最大支持100
	 */
	private $pageSize;

	/** 
	 * 退货类型 0 -- 售中 1 -- 售后
	 */
	private $returnType;

	/** 
	 * 退货方式
	 */
	private $shipTypeId;

	/** 
	 * 退货单创建开始时间 格式yyyy-MM-dd HH：mm:ss
	 */
	private $startCreated;

	/** 
	 * 订单编号
	 */
	private $tid;

	public function setEndCreated($endCreated)
	{
		$this->endCreated = $endCreated;
		$this->apiParas["end_created"] = $endCreated;
	}
	public function getEndCreated() {
		return $this->endCreated;
	}

	public function setFields($fields)
	{
		$this->fields = $fields;
		$this->apiParas["fields"] = $fields;
	}
	public function getFields() {
		return $this->fields;
	}

	public function setMemberId($memberId)
	{
		$this->memberId = $memberId;
		$this->apiParas["member_id"] = $memberId;
	}
	public function getMemberId() {
		return $this->memberId;
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

	public function setReturnType($returnType)
	{
		$this->returnType = $returnType;
		$this->apiParas["return_type"] = $returnType;
	}
	public function getReturnType() {
		return $this->returnType;
	}

	public function setShipTypeId($shipTypeId)
	{
		$this->shipTypeId = $shipTypeId;
		$this->apiParas["ship_type_id"] = $shipTypeId;
	}
	public function getShipTypeId() {
		return $this->shipTypeId;
	}

	public function setStartCreated($startCreated)
	{
		$this->startCreated = $startCreated;
		$this->apiParas["start_created"] = $startCreated;
	}
	public function getStartCreated() {
		return $this->startCreated;
	}

	public function setTid($tid)
	{
		$this->tid = $tid;
		$this->apiParas["tid"] = $tid;
	}
	public function getTid() {
		return $this->tid;
	}

	public function getApiMethodName()
	{
		return "qianmi.cloudshop.returns.list";
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
