<?php
/**
 * API: qianmi.cloudshop.logistics.address.add request
 * 
 * @author auto create
 * @since 1.0
 */
class LogisticsAddressAddRequest
{
	private $apiParas = array();

	/** 
	 * 详细地址
	 */
	private $addr;

	/** 
	 * 是否默认退货地址，只有admin卖家有该字段，1-是，0否，选择此项(1)，将当前地址设为默认地址，撤消原默认地址
	 */
	private $cancelDef;

	/** 
	 * 市
	 */
	private $city;

	/** 
	 * 联系人姓名
	 */
	private $contactName;

	/** 
	 * 区
	 */
	private $country;

	/** 
	 * 是否是收货地址 1-是，0-否，选择此项(1)，将当前地址设为默认地址，撤消原默认地址
	 */
	private $deliveryDef;

	/** 
	 * 卖家：表示是否默认提货地址，买家：是否默认收货地址，1-是，0-否，选择此项(1)，将当前地址设为默认地址，撤消原默认地址
	 */
	private $getDef;

	/** 
	 * 备注
	 */
	private $memo;

	/** 
	 * 手机号码（手机、固话必须有一个）
	 */
	private $mobile;

	/** 
	 * 固定电话（固话、手机必须有一个）
	 */
	private $phone;

	/** 
	 * 省
	 */
	private $province;

	/** 
	 * 是否默认发货地址，只有admin卖家有该字段，1-是，0-否，选择此项(1)，将当前地址设为默认地址，撤消原默认地址
	 */
	private $sendDef;

	/** 
	 * 邮政编码
	 */
	private $zip;

	public function setAddr($addr)
	{
		$this->addr = $addr;
		$this->apiParas["addr"] = $addr;
	}
	public function getAddr() {
		return $this->addr;
	}

	public function setCancelDef($cancelDef)
	{
		$this->cancelDef = $cancelDef;
		$this->apiParas["cancel_def"] = $cancelDef;
	}
	public function getCancelDef() {
		return $this->cancelDef;
	}

	public function setCity($city)
	{
		$this->city = $city;
		$this->apiParas["city"] = $city;
	}
	public function getCity() {
		return $this->city;
	}

	public function setContactName($contactName)
	{
		$this->contactName = $contactName;
		$this->apiParas["contact_name"] = $contactName;
	}
	public function getContactName() {
		return $this->contactName;
	}

	public function setCountry($country)
	{
		$this->country = $country;
		$this->apiParas["country"] = $country;
	}
	public function getCountry() {
		return $this->country;
	}

	public function setDeliveryDef($deliveryDef)
	{
		$this->deliveryDef = $deliveryDef;
		$this->apiParas["delivery_def"] = $deliveryDef;
	}
	public function getDeliveryDef() {
		return $this->deliveryDef;
	}

	public function setGetDef($getDef)
	{
		$this->getDef = $getDef;
		$this->apiParas["get_def"] = $getDef;
	}
	public function getGetDef() {
		return $this->getDef;
	}

	public function setMemo($memo)
	{
		$this->memo = $memo;
		$this->apiParas["memo"] = $memo;
	}
	public function getMemo() {
		return $this->memo;
	}

	public function setMobile($mobile)
	{
		$this->mobile = $mobile;
		$this->apiParas["mobile"] = $mobile;
	}
	public function getMobile() {
		return $this->mobile;
	}

	public function setPhone($phone)
	{
		$this->phone = $phone;
		$this->apiParas["phone"] = $phone;
	}
	public function getPhone() {
		return $this->phone;
	}

	public function setProvince($province)
	{
		$this->province = $province;
		$this->apiParas["province"] = $province;
	}
	public function getProvince() {
		return $this->province;
	}

	public function setSendDef($sendDef)
	{
		$this->sendDef = $sendDef;
		$this->apiParas["send_def"] = $sendDef;
	}
	public function getSendDef() {
		return $this->sendDef;
	}

	public function setZip($zip)
	{
		$this->zip = $zip;
		$this->apiParas["zip"] = $zip;
	}
	public function getZip() {
		return $this->zip;
	}

	public function getApiMethodName()
	{
		return "qianmi.cloudshop.logistics.address.add";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		RequestCheckUtil::checkNotNull($this->addr, "addr");
		RequestCheckUtil::checkNotNull($this->city, "city");
		RequestCheckUtil::checkNotNull($this->contactName, "contactName");
		RequestCheckUtil::checkNotNull($this->country, "country");
		RequestCheckUtil::checkNotNull($this->province, "province");
		RequestCheckUtil::checkNotNull($this->zip, "zip");
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}
