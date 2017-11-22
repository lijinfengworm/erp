<?php
/**
 * API: qianmi.cloudshop.trades.receive.list request
 * 
 * @author auto create
 * @since 1.0
 */
class TradesReceiveListRequest
{
	private $apiParas = array();

	/** 
	 * 结束时间 格式yyyy-MM-dd HH：mm:ss
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
	 * 每页条数，最大支持100，默认50
	 */
	private $pageSize;

	/** 
	 * 支付方式编号 OLP：在线支付，GRP：货到付款，BTP：转账汇款，OBP：余额支付，OCP：千米积分支付，YX_OSP：云销积分支付
	 */
	private $payTypeId;

	/** 
	 * 开始时间 格式yyyy-MM-dd HH：mm:ss
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

	public function setPayTypeId($payTypeId)
	{
		$this->payTypeId = $payTypeId;
		$this->apiParas["pay_type_id"] = $payTypeId;
	}
	public function getPayTypeId() {
		return $this->payTypeId;
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
		return "qianmi.cloudshop.trades.receive.list";
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
