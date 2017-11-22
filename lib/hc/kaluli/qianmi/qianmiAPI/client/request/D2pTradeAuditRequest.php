<?php
/**
 * API: qianmi.cloudshop.d2p.trade.audit request
 * 
 * @author auto create
 * @since 1.0
 */
class D2pTradeAuditRequest
{
	private $apiParas = array();

	/** 
	 * 订单号
	 */
	private $tid;

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
		return "qianmi.cloudshop.d2p.trade.audit";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		RequestCheckUtil::checkNotNull($this->tid, "tid");
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}
