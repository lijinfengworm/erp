<?php
/**
 * API: qianmi.cloudshop.d2p.trades.sold.increment.get request
 * 
 * @author auto create
 * @since 1.0
 */
class D2pTradesSoldIncrementGetRequest
{
	private $apiParas = array();

	/** 
	 * 订单完成状态, -1:全部, 0:进行中, 1:已完成, 2:已作废
	 */
	private $completeStatus;

	/** 
	 * 查询订单修改结束时间，必须大于修改开始时间(查询时间跨度不能大于一天)，格式:yyyy-MM-dd HH:mm:ss。建议使用30分钟以内的时间跨度，能大大提高响应速度和成功率。
	 */
	private $endModified;

	/** 
	 * trade交易结构中的所有字段均可返回，多个字段用”,”分隔，请按需获取,获取order所有字段只需要传orders,如只需要部分字段，请按以下格式:order.oid,order.price
	 */
	private $fields;

	/** 
	 * 订单流程状态，只能是以下几种状态中的一种，不传则查询全部。pending_audit_trade：待订单审核，pending_audit_finance：待财务审核，pending_pack：待出库，pending_deliver：待发货，pending_receive：待收货确认，received：已收货。
	 */
	private $flowStatus;

	/** 
	 * 页码，取值范围：大于等于0的整数，默认0
	 */
	private $pageNo;

	/** 
	 * 每页条数，取值范围：大于0的整数，最大100，默认50
	 */
	private $pageSize;

	/** 
	 * 订单支付状态, -1:全部, 0:未支付, 1:已支付, 2:已退款
	 */
	private $payStatus;

	/** 
	 * 查询订单修改开始时间(查询时间跨度不能大于一天)，格式:yyyy-MM-dd HH:mm:ss。
	 */
	private $startModified;

	/** 
	 * 订单类型:0自营 ，1代销，不指定会查询所有
	 */
	private $tradeFlag;

	public function setCompleteStatus($completeStatus)
	{
		$this->completeStatus = $completeStatus;
		$this->apiParas["complete_status"] = $completeStatus;
	}
	public function getCompleteStatus() {
		return $this->completeStatus;
	}

	public function setEndModified($endModified)
	{
		$this->endModified = $endModified;
		$this->apiParas["end_modified"] = $endModified;
	}
	public function getEndModified() {
		return $this->endModified;
	}

	public function setFields($fields)
	{
		$this->fields = $fields;
		$this->apiParas["fields"] = $fields;
	}
	public function getFields() {
		return $this->fields;
	}

	public function setFlowStatus($flowStatus)
	{
		$this->flowStatus = $flowStatus;
		$this->apiParas["flow_status"] = $flowStatus;
	}
	public function getFlowStatus() {
		return $this->flowStatus;
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

	public function setPayStatus($payStatus)
	{
		$this->payStatus = $payStatus;
		$this->apiParas["pay_status"] = $payStatus;
	}
	public function getPayStatus() {
		return $this->payStatus;
	}

	public function setStartModified($startModified)
	{
		$this->startModified = $startModified;
		$this->apiParas["start_modified"] = $startModified;
	}
	public function getStartModified() {
		return $this->startModified;
	}

	public function setTradeFlag($tradeFlag)
	{
		$this->tradeFlag = $tradeFlag;
		$this->apiParas["trade_flag"] = $tradeFlag;
	}
	public function getTradeFlag() {
		return $this->tradeFlag;
	}

	public function getApiMethodName()
	{
		return "qianmi.cloudshop.d2p.trades.sold.increment.get";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		RequestCheckUtil::checkNotNull($this->endModified, "endModified");
		RequestCheckUtil::checkNotNull($this->fields, "fields");
		RequestCheckUtil::checkNotNull($this->startModified, "startModified");
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}
