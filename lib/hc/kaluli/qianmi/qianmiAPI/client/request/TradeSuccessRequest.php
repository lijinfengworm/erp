<?php
/**
 * API: qianmi.cloudshop.trade.success request
 * 
 * @author auto create
 * @since 1.0
 */
class TradeSuccessRequest
{
	private $apiParas = array();

	/** 
	 * 订单编号
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
		return "qianmi.cloudshop.trade.success";
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
