<?php
/**
 * API: qianmi.cloudshop.member.get request
 * 
 * @author auto create
 * @since 1.0
 */
class MemberGetRequest
{
	private $apiParas = array();

	/** 
	 * 返回的字段列表
	 */
	private $fields;

	/** 
	 * 会员编号
	 */
	private $memberId;

	public function setFields($fields)
	{
		$this->fields = $fields;
		$this->apiParas["fields"] = $fields;
	}
	public function getFields() {
		return $this->fields;
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
		return "qianmi.cloudshop.member.get";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		RequestCheckUtil::checkNotNull($this->fields, "fields");
		RequestCheckUtil::checkNotNull($this->memberId, "memberId");
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}
