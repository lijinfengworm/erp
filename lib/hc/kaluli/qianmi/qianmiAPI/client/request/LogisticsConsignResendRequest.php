<?php
/**
 * API: qianmi.cloudshop.logistics.consign.resend request
 * 
 * @author auto create
 * @since 1.0
 */
class LogisticsConsignResendRequest
{
	private $apiParas = array();

	/** 
	 * 商家物流公司编号
	 */
	private $companyId;

	/** 
	 * 默认当前时间，时间格式：yyyy-MM-dd HH:mm:ss
	 */
	private $deliverTime;

	/** 
	 * 运单号
	 */
	private $outSid;

	/** 
	 * 包裹单号，site为1（云订货）时必填
	 */
	private $packId;

	/** 
	 * 物流费，默认0元
	 */
	private $postFee;

	/** 
	 * 订单渠道来源：1云订货   2云商城 
	 */
	private $site;

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

	public function setPostFee($postFee)
	{
		$this->postFee = $postFee;
		$this->apiParas["post_fee"] = $postFee;
	}
	public function getPostFee() {
		return $this->postFee;
	}

	public function setSite($site)
	{
		$this->site = $site;
		$this->apiParas["site"] = $site;
	}
	public function getSite() {
		return $this->site;
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
		return "qianmi.cloudshop.logistics.consign.resend";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		RequestCheckUtil::checkNotNull($this->companyId, "companyId");
		RequestCheckUtil::checkNotNull($this->outSid, "outSid");
		RequestCheckUtil::checkNotNull($this->tid, "tid");
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}
