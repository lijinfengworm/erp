<?php
/**
 * API: qianmi.cloudshop.trades.sold.get request
 * 
 * @author auto create
 * @since 1.0
 */
class TradesSoldGetRequest
{
	private $apiParas = array();

	/** 
	 * 买家会员编号
	 */
	private $buyerNick;

	/** 
	 * 订单完成状态, -1:全部, 0:进行中, 1:已完成, 2:已作废
	 */
	private $completeStatus;

	/** 
	 * 订单发货状态, -1:全部, 0:未发货, 1:已发货, 2:已退货
	 */
	private $deliverStatus;

	/** 
	 *  查询交易创建的结束时间, 格式: yyyy-MM-dd HH:mm:ss
	 */
	private $endCreated;

	/** 
	 * 需要返回的字段
	 */
	private $fields;

	/** 
	 *  页码, 取值范围: 大于等于0的整数, 默认0
	 */
	private $pageNo;

	/** 
	 *  每页条数, 取值范围: 大于0的整数, 最大100, 默认50
	 */
	private $pageSize;

	/** 
	 * 订单支付状态, -1:全部, 0:未支付, 1:已支付, 2:已退款
	 */
	private $payStatus;

	/** 
	 *  查询三个月内交易创建开始时间, 格式: yyyy-MM-dd HH:mm:ss
	 */
	private $startCreated;

	/** 
	 * 订单类型:0自营 ，1代销，不指定会查询所有
	 */
	private $tradeFlag;

	public function setBuyerNick($buyerNick)
	{
		$this->buyerNick = $buyerNick;
		$this->apiParas["buyer_nick"] = $buyerNick;
	}
	public function getBuyerNick() {
		return $this->buyerNick;
	}

	public function setCompleteStatus($completeStatus)
	{
		$this->completeStatus = $completeStatus;
		$this->apiParas["complete_status"] = $completeStatus;
	}
	public function getCompleteStatus() {
		return $this->completeStatus;
	}

	public function setDeliverStatus($deliverStatus)
	{
		$this->deliverStatus = $deliverStatus;
		$this->apiParas["deliver_status"] = $deliverStatus;
	}
	public function getDeliverStatus() {
		return $this->deliverStatus;
	}

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

	public function setStartCreated($startCreated)
	{
		$this->startCreated = $startCreated;
		$this->apiParas["start_created"] = $startCreated;
	}
	public function getStartCreated() {
		return $this->startCreated;
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
		return "qianmi.cloudshop.trades.sold.get";
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
