<?php
/**
 * API: qianmi.cloudshop.member.level.modify request
 * 
 * @author auto create
 * @since 1.0
 */
class MemberLevelModifyRequest
{
	private $apiParas = array();

	/** 
	 * 会员级别编号
	 */
	private $levelId;

	/** 
	 * 会员编号
	 */
	private $memberId;

	/** 
	 * 会员类型: 1-个人会员, 4-分销商
	 */
	private $memberType;

	public function setLevelId($levelId)
	{
		$this->levelId = $levelId;
		$this->apiParas["level_id"] = $levelId;
	}
	public function getLevelId() {
		return $this->levelId;
	}

	public function setMemberId($memberId)
	{
		$this->memberId = $memberId;
		$this->apiParas["member_id"] = $memberId;
	}
	public function getMemberId() {
		return $this->memberId;
	}

	public function setMemberType($memberType)
	{
		$this->memberType = $memberType;
		$this->apiParas["member_type"] = $memberType;
	}
	public function getMemberType() {
		return $this->memberType;
	}

	public function getApiMethodName()
	{
		return "qianmi.cloudshop.member.level.modify";
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
