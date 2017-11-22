<?php
/**
 * API: qianmi.cloudshop.refund.get request
 * 
 * @author auto create
 * @since 1.0
 */
class RefundGetRequest
{
	private $apiParas = array();

	/** 
	 * 退款单号
	 */
	private $refundId;

	public function setRefundId($refundId)
	{
		$this->refundId = $refundId;
		$this->apiParas["refund_id"] = $refundId;
	}
	public function getRefundId() {
		return $this->refundId;
	}

	public function getApiMethodName()
	{
		return "qianmi.cloudshop.refund.get";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}
