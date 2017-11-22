<?php
/**
 * API: qianmi.cloudshop.d2p.logistics.send request
 * 
 * @author auto create
 * @since 1.0
 */
class D2pLogisticsSendRequest
{
	private $apiParas = array();

	/** 
	 * admin卖家物流公司编号
	 */
	private $companyId;

	/** 
	 * 物流公司名称
	 */
	private $companyName;

	/** 
	 * 发货时间
	 */
	private $deliverTime;

	/** 
	 * 运单号，具体一个物流公司真实的运单号码
	 */
	private $outSid;

	/** 
	 * 包裹编号
	 */
	private $packId;

	/** 
	 * 发货包裹清单，包含商品单编号和商品实际发货数量，格式：oid:num;oid:num
	 */
	private $packItems;

	/** 
	 * 物流费用，单位 元，整数部分不超过999999，精确到2位小数
	 */
	private $postFee;

	/** 
	 * 交易中ship_type_id 卖家发货方式编号 self:自提 express：快递
	 */
	private $shipTypeId;

	/** 
	 * 订单编号
	 */
	private $tid;

	public function setCompanyId($companyId)
	{
		$this->companyId = $companyId;
		$this->apiParas["company_id"] = $companyId;
	}
	public function getCompanyId() {
		return $this->companyId;
	}

	public function setCompanyName($companyName)
	{
		$this->companyName = $companyName;
		$this->apiParas["company_name"] = $companyName;
	}
	public function getCompanyName() {
		return $this->companyName;
	}

	public function setDeliverTime($deliverTime)
	{
		$this->deliverTime = $deliverTime;
		$this->apiParas["deliver_time"] = $deliverTime;
	}
	public function getDeliverTime() {
		return $this->deliverTime;
	}

	public function setOutSid($outSid)
	{
		$this->outSid = $outSid;
		$this->apiParas["out_sid"] = $outSid;
	}
	public function getOutSid() {
		return $this->outSid;
	}

	public function setPackId($packId)
	{
		$this->packId = $packId;
		$this->apiParas["pack_id"] = $packId;
	}
	public function getPackId() {
		return $this->packId;
	}

	public function setPackItems($packItems)
	{
		$this->packItems = $packItems;
		$this->apiParas["pack_items"] = $packItems;
	}
	public function getPackItems() {
		return $this->packItems;
	}

	public function setPostFee($postFee)
	{
		$this->postFee = $postFee;
		$this->apiParas["post_fee"] = $postFee;
	}
	public function getPostFee() {
		return $this->postFee;
	}

	public function setShipTypeId($shipTypeId)
	{
		$this->shipTypeId = $shipTypeId;
		$this->apiParas["ship_type_id"] = $shipTypeId;
	}
	public function getShipTypeId() {
		return $this->shipTypeId;
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
		return "qianmi.cloudshop.d2p.logistics.send";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		RequestCheckUtil::checkNotNull($this->shipTypeId, "shipTypeId");
		RequestCheckUtil::checkNotNull($this->tid, "tid");
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}
