<?php
/**
 * API: qianmi.cloudshop.return.get request
 * 
 * @author auto create
 * @since 1.0
 */
class ReturnGetRequest
{
	private $apiParas = array();

	/** 
	 * 退货单编号
	 */
	private $returnId;

	public function setReturnId($returnId)
	{
		$this->returnId = $returnId;
		$this->apiParas["return_id"] = $returnId;
	}
	public function getReturnId() {
		return $this->returnId;
	}

	public function getApiMethodName()
	{
		return "qianmi.cloudshop.return.get";
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
