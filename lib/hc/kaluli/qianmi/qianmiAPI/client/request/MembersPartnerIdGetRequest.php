<?php
/**
 * API: qianmi.cloudshop.members.partner.id.get request
 * 
 * @author auto create
 * @since 1.0
 */
class MembersPartnerIdGetRequest
{
	private $apiParas = array();

	/** 
	 * 会员编号，用逗号隔开
	 */
	private $memberIds;

	/** 
	 * 第三方平台标识
	 */
	private $partner;

	public function setMemberIds($memberIds)
	{
		$this->memberIds = $memberIds;
		$this->apiParas["member_ids"] = $memberIds;
	}
	public function getMemberIds() {
		return $this->memberIds;
	}

	public function setPartner($partner)
	{
		$this->partner = $partner;
		$this->apiParas["partner"] = $partner;
	}
	public function getPartner() {
		return $this->partner;
	}

	public function getApiMethodName()
	{
		return "qianmi.cloudshop.members.partner.id.get";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		RequestCheckUtil::checkNotNull($this->memberIds, "memberIds");
		RequestCheckUtil::checkNotNull($this->partner, "partner");
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}
