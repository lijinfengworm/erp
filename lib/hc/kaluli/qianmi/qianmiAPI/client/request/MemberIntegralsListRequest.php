<?php
/**
 * API: qianmi.cloudshop.member.integrals.list request
 * 
 * @author auto create
 * @since 1.0
 */
class MemberIntegralsListRequest
{
	private $apiParas = array();

	/** 
	 * 结束时间
	 */
	private $endTime;

	/** 
	 * 积分收入支出类型： 1- 收入  2-支出
	 */
	private $inOutType;

	/** 
	 * 会员编号
	 */
	private $memberId;

	/** 
	 * 当前页码
	 */
	private $pageNo;

	/** 
	 * 每页条数，最多支持200,默认200
	 */
	private $pageSize;

	/** 
	 * 开始时间
	 */
	private $startTime;

	public function setEndTime($endTime)
	{
		$this->endTime = $endTime;
		$this->apiParas["end_time"] = $endTime;
	}
	public function getEndTime() {
		return $this->endTime;
	}

	public function setInOutType($inOutType)
	{
		$this->inOutType = $inOutType;
		$this->apiParas["in_out_type"] = $inOutType;
	}
	public function getInOutType() {
		return $this->inOutType;
	}

	public function setMemberId($memberId)
	{
		$this->memberId = $memberId;
		$this->apiParas["member_id"] = $memberId;
	}
	public function getMemberId() {
		return $this->memberId;
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

	public function setStartTime($startTime)
	{
		$this->startTime = $startTime;
		$this->apiParas["start_time"] = $startTime;
	}
	public function getStartTime() {
		return $this->startTime;
	}

	public function getApiMethodName()
	{
		return "qianmi.cloudshop.member.integrals.list";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		RequestCheckUtil::checkNotNull($this->memberId, "memberId");
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}
