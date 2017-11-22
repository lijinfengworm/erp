<?php
/**
 * API: qianmi.cloudshop.d2p.logistics.cancel request
 * 
 * @author auto create
 * @since 1.0
 */
class D2pLogisticsCancelRequest
{
	private $apiParas = array();

	/** 
	 * 取消原因
	 */
	private $cancelReason;

	/** 
	 * 包裹编号
	 */
	private $packId;

	/** 
	 * 订单编号
	 */
	private $tid;

	public function setCancelReason($cancelReason)
	{
		$this->cancelReason = $cancelReason;
		$this->apiParas["cancel_reason"] = $cancelReason;
	}
	public function getCancelReason() {
		return $this->cancelReason;
	}

	public function setPackId($packId)
	{
		$this->packId = $packId;
		$this->apiParas["pack_id"] = $packId;
	}
	public function getPackId() {
		return $this->packId;
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
		return "qianmi.cloudshop.d2p.logistics.cancel";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		RequestCheckUtil::checkNotNull($this->cancelReason, "cancelReason");
		RequestCheckUtil::checkNotNull($this->packId, "packId");
		RequestCheckUtil::checkNotNull($this->tid, "tid");
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}
