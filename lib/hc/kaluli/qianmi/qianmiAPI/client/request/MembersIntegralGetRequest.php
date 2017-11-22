<?php
/**
 * API: qianmi.cloudshop.members.integral.get request
 * 
 * @author auto create
 * @since 1.0
 */
class MembersIntegralGetRequest
{
	private $apiParas = array();

	/** 
	 * 会员编号，用英文半角逗号隔开
	 */
	private $memberIds;

	public function setMemberIds($memberIds)
	{
		$this->memberIds = $memberIds;
		$this->apiParas["member_ids"] = $memberIds;
	}
	public function getMemberIds() {
		return $this->memberIds;
	}

	public function getApiMethodName()
	{
		return "qianmi.cloudshop.members.integral.get";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		RequestCheckUtil::checkNotNull($this->memberIds, "memberIds");
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}
