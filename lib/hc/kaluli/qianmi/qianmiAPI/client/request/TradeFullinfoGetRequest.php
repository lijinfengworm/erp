<?php
/**
 * API: qianmi.cloudshop.trade.fullinfo.get request
 * 
 * @author auto create
 * @since 1.0
 */
class TradeFullinfoGetRequest
{
	private $apiParas = array();

	/** 
	 * 需要返回的字段
	 */
	private $fields;

	/** 
	 * 交易编号
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
		return "qianmi.cloudshop.trade.fullinfo.get";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		RequestCheckUtil::checkNotNull($this->fields, "fields");
		RequestCheckUtil::checkNotNull($this->tid, "tid");
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}
