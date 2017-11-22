<?php
/**
 * API: qianmi.cloudshop.logistics.company.remove request
 * 
 * @author auto create
 * @since 1.0
 */
class LogisticsCompanyRemoveRequest
{
	private $apiParas = array();

	/** 
	 * 卖家常用列表中物流公司编号
	 */
	private $id;

	public function setId($id)
	{
		$this->id = $id;
		$this->apiParas["id"] = $id;
	}
	public function getId() {
		return $this->id;
	}

	public function getApiMethodName()
	{
		return "qianmi.cloudshop.logistics.company.remove";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		RequestCheckUtil::checkNotNull($this->id, "id");
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}
