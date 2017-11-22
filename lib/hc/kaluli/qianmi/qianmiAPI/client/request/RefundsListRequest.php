<?php
/**
 * API: qianmi.cloudshop.refunds.list request
 * 
 * @author auto create
 * @since 1.0
 */
class RefundsListRequest
{
	private $apiParas = array();

	/** 
	 * 退款单创建时间_结束时间, 格式: yyyy-MM-dd HH:mm:ss
	 */
	private $endCreated;

	/** 
	 * 需返回字段列表，返回多个字段时，以逗号分隔。
	 */
	private $fields;

	/** 
	 * 会员编号
	 */
	private $memberId;

	/** 
	 * 页码，大于等于0的整数，默认值0
	 */
	private $pageNo;

	/** 
	 * 每页条数，取大于0的整数，最大值100，默认值50
	 */
	private $pageSize;

	/** 
	 * 退款单号
	 */
	private $refundId;

	/** 
	 * 退款类型 0-售中 1-售后
	 */
	private $refundType;

	/** 
	 * 退款方式编号  OLP-在线支付 BTP-转账汇款  GRP-现金  OBP-余额支付 YX_OSP-积分支付
	 */
	private $refundTypeId;

	/** 
	 * 退款单创建时间_开始时间, 格式: yyyy-MM-dd HH:mm:ss
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

	public function setRefundId($refundId)
	{
		$this->refundId = $refundId;
		$this->apiParas["refund_id"] = $refundId;
	}
	public function getRefundId() {
		return $this->refundId;
	}

	public function setRefundType($refundType)
	{
		$this->refundType = $refundType;
		$this->apiParas["refund_type"] = $refundType;
	}
	public function getRefundType() {
		return $this->refundType;
	}

	public function setRefundTypeId($refundTypeId)
	{
		$this->refundTypeId = $refundTypeId;
		$this->apiParas["refund_type_id"] = $refundTypeId;
	}
	public function getRefundTypeId() {
		return $this->refundTypeId;
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
		return "qianmi.cloudshop.refunds.list";
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
