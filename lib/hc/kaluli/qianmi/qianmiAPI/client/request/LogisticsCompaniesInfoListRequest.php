<?php
/**
 * API: qianmi.cloudshop.logistics.companies.info.list request
 * 
 * @author auto create
 * @since 1.0
 */
class LogisticsCompaniesInfoListRequest
{
	private $apiParas = array();

	public function getApiMethodName()
	{
		return "qianmi.cloudshop.logistics.companies.info.list";
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
