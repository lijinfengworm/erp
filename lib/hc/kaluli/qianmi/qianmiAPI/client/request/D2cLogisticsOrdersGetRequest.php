<?php
/**
 * API: qianmi.cloudshop.d2c.logistics.orders.get request
 * 
 * @author auto create
 * @since 1.0
 */
class D2cLogisticsOrdersGetRequest
{
	private $apiParas = array();

	/** 
	 * 需要返回的字段
	 */
	private $fields;

	/** 
	 * 页码
	 */
	private $pageNo;

	/** 
	 * 每页条数
	 */
	private $pageSize;

	/** 
	 * 交易ID
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

	public function setPageNo($pageNo)
	{
		$this->pageNo = $pageNo;
		$this->apiParas["page_no"] = $pageNo;
	}
	public function getPageNo() {
		return $this->pageNo;
	}

	public function setPageSize($pageSize)
	{
		$this->pageSize = $pageSize;
		$this->apiParas["page_size"] = $pageSize;
	}
	public function getPageSize() {
		return $this->pageSize;
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
		return "qianmi.cloudshop.d2c.logistics.orders.get";
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
