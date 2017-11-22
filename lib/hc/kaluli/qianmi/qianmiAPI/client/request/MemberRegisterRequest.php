<?php
/**
 * API: qianmi.cloudshop.member.register request
 * 
 * @author auto create
 * @since 1.0
 */
class MemberRegisterRequest
{
	private $apiParas = array();

	/** 
	 * 会员类型,1:个人会员，4:网点会员
	 */
	private $memberType;

	/** 
	 * 会员手机号
	 */
	private $mobile;

	/** 
	 * 密码,不能明文传输，需要AES对称加密
	 */
	private $password;

	public function setMemberType($memberType)
	{
		$this->memberType = $memberType;
		$this->apiParas["member_type"] = $memberType;
	}
	public function getMemberType() {
		return $this->memberType;
	}

	public function setMobile($mobile)
	{
		$this->mobile = $mobile;
		$this->apiParas["mobile"] = $mobile;
	}
	public function getMobile() {
		return $this->mobile;
	}

	public function setPassword($password)
	{
		$this->password = $password;
		$this->apiParas["password"] = $password;
	}
	public function getPassword() {
		return $this->password;
	}

	public function getApiMethodName()
	{
		return "qianmi.cloudshop.member.register";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		RequestCheckUtil::checkNotNull($this->memberType, "memberType");
		RequestCheckUtil::checkNotNull($this->mobile, "mobile");
		RequestCheckUtil::checkNotNull($this->password, "password");
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}
