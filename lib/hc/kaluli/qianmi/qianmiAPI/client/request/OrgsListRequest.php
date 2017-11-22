<?php
/**
 * API: qianmi.cloudshop.orgs.list request
 * 
 * @author auto create
 * @since 1.0
 */
class OrgsListRequest
{
	private $apiParas = array();

	/** 
	 * 需要返回的字段，多个字段之间以逗号隔开,可返回admin_id,org_id,p_org_id,org_name,depth,sale_areas,emp_num
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
		return "qianmi.cloudshop.orgs.list";
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
