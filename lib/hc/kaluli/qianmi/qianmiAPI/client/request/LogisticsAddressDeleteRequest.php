<?php
/**
 * API: qianmi.cloudshop.logistics.address.delete request
 * 
 * @author auto create
 * @since 1.0
 */
class LogisticsAddressDeleteRequest
{
	private $apiParas = array();

	/** 
	 * 地址库ID
	 */
	private $contactId;

	public function setContactId($contactId)
	{
		$this->contactId = $contactId;
		$this->apiParas["contact_id"] = $contactId;
	}
	public function getContactId() {
		return $this->contactId;
	}

	public function getApiMethodName()
	{
		return "qianmi.cloudshop.logistics.address.delete";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		RequestCheckUtil::checkNotNull($this->contactId, "contactId");
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}
