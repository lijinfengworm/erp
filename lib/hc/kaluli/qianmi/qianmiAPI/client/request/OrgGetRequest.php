<?php
/**
 * API: qianmi.cloudshop.org.get request
 * 
 * @author auto create
 * @since 1.0
 */
class OrgGetRequest
{
	private $apiParas = array();

	/** 
	 * 需要返回的字段，多个字段之间以逗号隔开,可返回admin_id,org_id,p_org_id,org_name...
	 */
	private $fields;

	/** 
	 * 部门编号
	 */
	private $orgId;

	public function setFields($fields)
	{
		$this->fields = $fields;
		$this->apiParas["fields"] = $fields;
	}
	public function getFields() {
		return $this->fields;
	}

	public function setOrgId($orgId)
	{
		$this->orgId = $orgId;
		$this->apiParas["org_id"] = $orgId;
	}
	public function getOrgId() {
		return $this->orgId;
	}

	public function getApiMethodName()
	{
		return "qianmi.cloudshop.org.get";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		RequestCheckUtil::checkNotNull($this->fields, "fields");
		RequestCheckUtil::checkNotNull($this->orgId, "orgId");
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}
