<?php
/**
 * API: qianmi.cloudshop.members.list request
 * 
 * @author auto create
 * @since 1.0
 */
class MembersListRequest
{
	private $apiParas = array();

	/** 
	 * 审核状态，0：待审核，1：审核通过，-1：审核不通过，默认值1
	 */
	private $auditStatus;

	/** 
	 * 返回的字段列表
	 */
	private $fields;

	/** 
	 * 会员等级编号
	 */
	private $levelId;

	/** 
	 * 锁定状态，0：锁定，1：正常，默认值1
	 */
	private $lockStatus;

	/** 
	 * 会员类型，1：个人会员，4：分销商，不传则查询个人会员和分销商
	 */
	private $memberType;

	/** 
	 * 页码，取大于等于0的整数，默认值0
	 */
	private $pageNo;

	/** 
	 * 每页条数，取大于0的整数，默认20，最大值20
	 */
	private $pageSize;

	/** 
	 * 用户状态，1：正常，2：删除，默认值1
	 */
	private $status;

	public function setAuditStatus($auditStatus)
	{
		$this->auditStatus = $auditStatus;
		$this->apiParas["audit_status"] = $auditStatus;
	}
	public function getAuditStatus() {
		return $this->auditStatus;
	}

	public function setFields($fields)
	{
		$this->fields = $fields;
		$this->apiParas["fields"] = $fields;
	}
	public function getFields() {
		return $this->fields;
	}

	public function setLevelId($levelId)
	{
		$this->levelId = $levelId;
		$this->apiParas["level_id"] = $levelId;
	}
	public function getLevelId() {
		return $this->levelId;
	}

	public function setLockStatus($lockStatus)
	{
		$this->lockStatus = $lockStatus;
		$this->apiParas["lock_status"] = $lockStatus;
	}
	public function getLockStatus() {
		return $this->lockStatus;
	}

	public function setMemberType($memberType)
	{
		$this->memberType = $memberType;
		$this->apiParas["member_type"] = $memberType;
	}
	public function getMemberType() {
		return $this->memberType;
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
		return "qianmi.cloudshop.members.list";
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
