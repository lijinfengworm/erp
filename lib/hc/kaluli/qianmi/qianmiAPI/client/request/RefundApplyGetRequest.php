<?php
/**
 * API: qianmi.cloudshop.refund.apply.get request
 * 
 * @author auto create
 * @since 1.0
 */
class RefundApplyGetRequest
{
	private $apiParas = array();

	/** 
	 * 申请退货/退款编号
	 */
	private $applyId;

	public function setApplyId($applyId)
	{
		$this->applyId = $applyId;
		$this->apiParas["apply_id"] = $applyId;
	}
	public function getApplyId() {
		return $this->applyId;
	}

	public function getApiMethodName()
	{
		return "qianmi.cloudshop.refund.apply.get";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		RequestCheckUtil::checkNotNull($this->applyId, "applyId");
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}
