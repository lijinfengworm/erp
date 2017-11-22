<?php
/**
 * API: qianmi.cloudshop.member.integral.update request
 * 
 * @author auto create
 * @since 1.0
 */
class MemberIntegralUpdateRequest
{
	private $apiParas = array();

	/** 
	 * 积分的修改值，两位小数，不能为0。值为正数时，添加积分。值为负数时，减少积分
	 */
	private $integral;

	/** 
	 * 会员编号
	 */
	private $memberId;

	public function setIntegral($integral)
	{
		$this->integral = $integral;
		$this->apiParas["integral"] = $integral;
	}
	public function getIntegral() {
		return $this->integral;
	}

	public function setMemberId($memberId)
	{
		$this->memberId = $memberId;
		$this->apiParas["member_id"] = $memberId;
	}
	public function getMemberId() {
		return $this->memberId;
	}

	public function getApiMethodName()
	{
		return "qianmi.cloudshop.member.integral.update";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		RequestCheckUtil::checkNotNull($this->integral, "integral");
		RequestCheckUtil::checkNotNull($this->memberId, "memberId");
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}
