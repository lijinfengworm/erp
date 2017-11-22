<?php
/**
 * API: qianmi.cloudshop.org.employee.get request
 * 
 * @author auto create
 * @since 1.0
 */
class OrgEmployeeGetRequest
{
	private $apiParas = array();

	/** 
	 * 员工编号
	 */
	private $empId;

	/** 
	 * 需要返回的员工信息字段，可返回emp_id,emp_nick,name,mobile,role_name,status,org_name,org_id,created,sales_num,member_num,sales,members
	 */
	private $fields;

	public function setEmpId($empId)
	{
		$this->empId = $empId;
		$this->apiParas["emp_id"] = $empId;
	}
	public function getEmpId() {
		return $this->empId;
	}

	public function setFields($fields)
	{
		$this->fields = $fields;
		$this->apiParas["fields"] = $fields;
	}
	public function getFields() {
		return $this->fields;
	}

	public function getApiMethodName()
	{
		return "qianmi.cloudshop.org.employee.get";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		RequestCheckUtil::checkNotNull($this->empId, "empId");
		RequestCheckUtil::checkNotNull($this->fields, "fields");
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}
