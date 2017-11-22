<?php
/**
 * API: qianmi.cloudshop.member.add request
 * 
 * @author auto create
 * @since 1.0
 */
class MemberAddRequest
{
	private $apiParas = array();

	/** 
	 * 会员等级编号 ，读取会员等级设置
	 */
	private $memberLevel;

	/** 
	 * 用户名长度在4-20个字符之间
	 */
	private $memberNick;

	/** 
	 * 第三方会员编号
	 */
	private $memberNo;

	/** 
	 * 会员类型,1:个人会员，4:网点会员
	 */
	private $memberType;

	/** 
	 * 密码,不能明文传输，需要AES对称加密
	 */
	private $password;

	/** 
	 * 推荐人编号
	 */
	private $referUserId;

	public function setMemberLevel($memberLevel)
	{
		$this->memberLevel = $memberLevel;
		$this->apiParas["member_level"] = $memberLevel;
	}
	public function getMemberLevel() {
		return $this->memberLevel;
	}

	public function setMemberNick($memberNick)
	{
		$this->memberNick = $memberNick;
		$this->apiParas["member_nick"] = $memberNick;
	}
	public function getMemberNick() {
		return $this->memberNick;
	}

	public function setMemberNo($memberNo)
	{
		$this->memberNo = $memberNo;
		$this->apiParas["member_no"] = $memberNo;
	}
	public function getMemberNo() {
		return $this->memberNo;
	}

	public function setMemberType($memberType)
	{
		$this->memberType = $memberType;
		$this->apiParas["member_type"] = $memberType;
	}
	public function getMemberType() {
		return $this->memberType;
	}

	public function setPassword($password)
	{
		$this->password = $password;
		$this->apiParas["password"] = $password;
	}
	public function getPassword() {
		return $this->password;
	}

	public function setReferUserId($referUserId)
	{
		$this->referUserId = $referUserId;
		$this->apiParas["refer_user_id"] = $referUserId;
	}
	public function getReferUserId() {
		return $this->referUserId;
	}

	public function getApiMethodName()
	{
		return "qianmi.cloudshop.member.add";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		RequestCheckUtil::checkNotNull($this->memberLevel, "memberLevel");
		RequestCheckUtil::checkNotNull($this->memberNick, "memberNick");
		RequestCheckUtil::checkNotNull($this->memberType, "memberType");
		RequestCheckUtil::checkNotNull($this->password, "password");
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}
