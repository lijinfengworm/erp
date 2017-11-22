<?php
/**
 * API: qianmi.cloudshop.d2p.logistics.packs.get request
 * 
 * @author auto create
 * @since 1.0
 */
class D2pLogisticsPacksGetRequest
{
	private $apiParas = array();

	/** 
	 * 需要返回的包裹信息字段
	 */
	private $fields;

	/** 
	 * 包裹单号
	 */
	private $packId;

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
		return "qianmi.cloudshop.d2p.logistics.packs.get";
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
