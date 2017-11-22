<?php
/**
 * API: qianmi.cloudshop.trade.receive.get request
 * 
 * @author auto create
 * @since 1.0
 */
class TradeReceiveGetRequest
{
	private $apiParas = array();

	/** 
	 * 需要返回的字段
	 */
	private $fields;

	/** 
	 * 收款单编号
	 */
	private $receiveId;

	/** 
	 * 订单编号
	 */
	private $tid;

	public function setFields($fields)
	{
		$this->fields = $fields;
		$this->apiParas["fields"] = $fields;
	}
	public function getFields() {
		return $this->fields;
	}

	public function setReceiveId($receiveId)
	{
		$this->receiveId = $receiveId;
		$this->apiParas["receive_id"] = $receiveId;
	}
	public function getReceiveId() {
		return $this->receiveId;
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
		return "qianmi.cloudshop.trade.receive.get";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		RequestCheckUtil::checkNotNull($this->fields, "fields");
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}
