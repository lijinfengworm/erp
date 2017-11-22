<?php
/**
 * API: qianmi.cloudshop.org.employees.list request
 * 
 * @author auto create
 * @since 1.0
 */
class OrgEmployeesListRequest
{
	private $apiParas = array();

	/** 
	 * 查询员工创建的结束时间，格式：yyyy-MM-dd HH:mm:ss
	 */
	private $endCreated;

	/** 
	 * 需要返回的员工信息字段，可返回emp_id,emp_nick,name,mobile,role_name,status,org_name,org_id,created,sales_num,member_num
	 */
	private $fields;

	/** 
	 * 是否销售员 1-是 0-否
	 */
	private $isSaleUser;

	/** 
	 * 部门编号
	 */
	private $orgId;

	/** 
	 * 页码，取值范围：大于等于0的整数，默认0
	 */
	private $pageNo;

	/** 
	 * 每页条数，取值范围：大于0的整数，最大100，默认50
	 */
	private $pageSize;

	/** 
	 * 岗位编号
	 */
	private $roleId;

	/** 
	 * 查询员工的创建开始时间，格式：yyyy-MM-dd HH:mm:ss
	 */
	private $startCreated;

	/** 
	 * 锁定状态， 0-锁定，1-正常
	 */
	private $status;

	public function setEndCreated($endCreated)
	{
		$this->endCreated = $endCreated;
		$this->apiParas["end_created"] = $endCreated;
	}
	public function getEndCreated() {
		return $this->endCreated;
	}

	public function setFields($fields)
	{
		$this->fields = $fields;
		$this->apiParas["fields"] = $fields;
	}
	public function getFields() {
		return $this->fields;
	}

	public function setIsSaleUser($isSaleUser)
	{
		$this->isSaleUser = $isSaleUser;
		$this->apiParas["is_sale_user"] = $isSaleUser;
	}
	public function getIsSaleUser() {
		return $this->isSaleUser;
	}

	public function setOrgId($orgId)
	{
		$this->orgId = $orgId;
		$this->apiParas["org_id"] = $orgId;
	}
	public function getOrgId() {
		return $this->orgId;
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

	public function setRoleId($roleId)
	{
		$this->roleId = $roleId;
		$this->apiParas["role_id"] = $roleId;
	}
	public function getRoleId() {
		return $this->roleId;
	}

	public function setStartCreated($startCreated)
	{
		$this->startCreated = $startCreated;
		$this->apiParas["start_created"] = $startCreated;
	}
	public function getStartCreated() {
		return $this->startCreated;
	}

	public function setStatus($status)
	{
		$this->status = $status;
		$this->apiParas["status"] = $status;
	}
	public function getStatus() {
		return $this->status;
	}

	public function getApiMethodName()
	{
		return "qianmi.cloudshop.org.employees.list";
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
