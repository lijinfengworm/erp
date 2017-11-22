<?php
/**
 * API: qianmi.cloudshop.member.levels.list request
 * 
 * @author auto create
 * @since 1.0
 */
class MemberLevelsListRequest
{
	private $apiParas = array();

	/** 
	 * 会员等级类型，1：个人会员等级，2：分销商等级，不传则查询所有
	 */
	private $type;

	public function setType($type)
	{
		$this->type = $type;
		$this->apiParas["type"] = $type;
	}
	public function getType() {
		return $this->type;
	}

	public function getApiMethodName()
	{
		return "qianmi.cloudshop.member.levels.list";
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
