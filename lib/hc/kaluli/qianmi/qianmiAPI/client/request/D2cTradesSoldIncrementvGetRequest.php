<?php
/**
 * API: qianmi.cloudshop.d2c.trades.sold.incrementv.get request
 * 
 * @author auto create
 * @since 1.0
 */
class D2cTradesSoldIncrementvGetRequest
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
	 * 查询入库结束时间，必须大于入库开始时间(修改时间跨度不能大于一天)
	 */
	private $endCreate;

	/** 
	 * trade交易结构中的所有字段均可返回，多个字段用”,”分隔，请按需获取
	 */
	private $fields;

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
	 * 查询入库开始时间(修改时间跨度不能大于一天)。
	 */
	private $startCreate;

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

	public function setEndCreate($endCreate)
	{
		$this->endCreate = $endCreate;
		$this->apiParas["end_create"] = $endCreate;
	}
	public function getEndCreate() {
		return $this->endCreate;
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

	public function setStartCreate($startCreate)
	{
		$this->startCreate = $startCreate;
		$this->apiParas["start_create"] = $startCreate;
	}
	public function getStartCreate() {
		return $this->startCreate;
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
		return "qianmi.cloudshop.d2c.trades.sold.incrementv.get";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		RequestCheckUtil::checkNotNull($this->endCreate, "endCreate");
		RequestCheckUtil::checkNotNull($this->fields, "fields");
		RequestCheckUtil::checkNotNull($this->startCreate, "startCreate");
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}
