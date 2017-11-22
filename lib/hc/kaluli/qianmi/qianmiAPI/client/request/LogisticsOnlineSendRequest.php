<?php
/**
 * API: qianmi.cloudshop.logistics.online.send request
 * 
 * @author auto create
 * @since 1.0
 */
class LogisticsOnlineSendRequest
{
	private $apiParas = array();

	/** 
	 * 退货地址编号
	 */
	private $cancelId;

	/** 
	 * 卖家物流公司编号
	 */
	private $companyId;

	/** 
	 * 运单号
	 */
	private $outSid;

	/** 
	 * 物流费用
	 */
	private $postFee;

	/** 
	 * 详细地址
	 */
	private $reciverAddress;

	/** 
	 * 收件人城市
	 */
	private $reciverCity;

	/** 
	 * 收件人所在区县
	 */
	private $reciverDistrict;

	/** 
	 * 收件人手机, 手机、电话至少有一个
	 */
	private $reciverMobile;

	/** 
	 * 收件人姓名
	 */
	private $reciverName;

	/** 
	 * 收件人电话, 手机、电话至少有一个
	 */
	private $reciverPhone;

	/** 
	 * 收件人省份
	 */
	private $reciverState;

	/** 
	 * 收件地址邮编
	 */
	private $reciverZip;

	/** 
	 * 卖家编号，A开头
	 */
	private $sellerNick;

	/** 
	 * 卖家发货备注
	 */
	private $sellerRemark;

	/** 
	 * 发货地址编号
	 */
	private $senderId;

	/** 
	 * 发货方式编号
	 */
	private $shipTypeId;

	/** 
	 * 发货方式名称
	 */
	private $shipTypeName;

	/** 
	 * 千米网交易id
	 */
	private $tid;

	public function setCancelId($cancelId)
	{
		$this->cancelId = $cancelId;
		$this->apiParas["cancel_id"] = $cancelId;
	}
	public function getCancelId() {
		return $this->cancelId;
	}

	public function setCompanyId($companyId)
	{
		$this->companyId = $companyId;
		$this->apiParas["company_id"] = $companyId;
	}
	public function getCompanyId() {
		return $this->companyId;
	}

	public function setOutSid($outSid)
	{
		$this->outSid = $outSid;
		$this->apiParas["out_sid"] = $outSid;
	}
	public function getOutSid() {
		return $this->outSid;
	}

	public function setPostFee($postFee)
	{
		$this->postFee = $postFee;
		$this->apiParas["post_fee"] = $postFee;
	}
	public function getPostFee() {
		return $this->postFee;
	}

	public function setReciverAddress($reciverAddress)
	{
		$this->reciverAddress = $reciverAddress;
		$this->apiParas["reciver_address"] = $reciverAddress;
	}
	public function getReciverAddress() {
		return $this->reciverAddress;
	}

	public function setReciverCity($reciverCity)
	{
		$this->reciverCity = $reciverCity;
		$this->apiParas["reciver_city"] = $reciverCity;
	}
	public function getReciverCity() {
		return $this->reciverCity;
	}

	public function setReciverDistrict($reciverDistrict)
	{
		$this->reciverDistrict = $reciverDistrict;
		$this->apiParas["reciver_district"] = $reciverDistrict;
	}
	public function getReciverDistrict() {
		return $this->reciverDistrict;
	}

	public function setReciverMobile($reciverMobile)
	{
		$this->reciverMobile = $reciverMobile;
		$this->apiParas["reciver_mobile"] = $reciverMobile;
	}
	public function getReciverMobile() {
		return $this->reciverMobile;
	}

	public function setReciverName($reciverName)
	{
		$this->reciverName = $reciverName;
		$this->apiParas["reciver_name"] = $reciverName;
	}
	public function getReciverName() {
		return $this->reciverName;
	}

	public function setReciverPhone($reciverPhone)
	{
		$this->reciverPhone = $reciverPhone;
		$this->apiParas["reciver_phone"] = $reciverPhone;
	}
	public function getReciverPhone() {
		return $this->reciverPhone;
	}

	public function setReciverState($reciverState)
	{
		$this->reciverState = $reciverState;
		$this->apiParas["reciver_state"] = $reciverState;
	}
	public function getReciverState() {
		return $this->reciverState;
	}

	public function setReciverZip($reciverZip)
	{
		$this->reciverZip = $reciverZip;
		$this->apiParas["reciver_zip"] = $reciverZip;
	}
	public function getReciverZip() {
		return $this->reciverZip;
	}

	public function setSellerNick($sellerNick)
	{
		$this->sellerNick = $sellerNick;
		$this->apiParas["seller_nick"] = $sellerNick;
	}
	public function getSellerNick() {
		return $this->sellerNick;
	}

	public function setSellerRemark($sellerRemark)
	{
		$this->sellerRemark = $sellerRemark;
		$this->apiParas["seller_remark"] = $sellerRemark;
	}
	public function getSellerRemark() {
		return $this->sellerRemark;
	}

	public function setSenderId($senderId)
	{
		$this->senderId = $senderId;
		$this->apiParas["sender_id"] = $senderId;
	}
	public function getSenderId() {
		return $this->senderId;
	}

	public function setShipTypeId($shipTypeId)
	{
		$this->shipTypeId = $shipTypeId;
		$this->apiParas["ship_type_id"] = $shipTypeId;
	}
	public function getShipTypeId() {
		return $this->shipTypeId;
	}

	public function setShipTypeName($shipTypeName)
	{
		$this->shipTypeName = $shipTypeName;
		$this->apiParas["ship_type_name"] = $shipTypeName;
	}
	public function getShipTypeName() {
		return $this->shipTypeName;
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
		return "qianmi.cloudshop.logistics.online.send";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		RequestCheckUtil::checkNotNull($this->sellerNick, "sellerNick");
		RequestCheckUtil::checkNotNull($this->shipTypeId, "shipTypeId");
		RequestCheckUtil::checkNotNull($this->tid, "tid");
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}
