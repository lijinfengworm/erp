<?php
/**
 * API: qianmi.cloudshop.org.role.get request
 * 
 * @author auto create
 * @since 1.0
 */
class OrgRoleGetRequest
{
	private $apiParas = array();

	/** 
	 * 需要返回的字段，多个字段之间以逗号隔开，可返回role_id,role_name,created,admin_id,remark,emp_num
	 */
	private $fields;

	/** 
	 * 岗位编号
	 */
	private $roleId;

	public function setFields($fields)
	{
		$this->fields = $fields;
		$this->apiParas["fields"] = $fields;
	}
	public function getFields() {
		return $this->fields;
	}

	public function setRoleId($roleId)
	{
		$this->roleId = $roleId;
		$this->apiParas["role_id"] = $roleId;
	}
	public function getRoleId() {
		return $this->roleId;
	}

	public function getApiMethodName()
	{
		return "qianmi.cloudshop.org.role.get";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		RequestCheckUtil::checkNotNull($this->fields, "fields");
		RequestCheckUtil::checkNotNull($this->roleId, "roleId");
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}
