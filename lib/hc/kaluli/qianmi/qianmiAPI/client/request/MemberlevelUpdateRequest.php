<?php
/**
 * API: qianmi.cloudshop.memberlevel.update request
 * 
 * @author auto create
 * @since 1.0
 */
class MemberlevelUpdateRequest
{
	private $apiParas = array();

	/** 
	 * admin编号
	 */
	private $adminId;

	/** 
	 * 是否默认等级，1.默认的true,2.非默认会员false,默认false
	 */
	private $isDefault;

	/** 
	 * 等级id
	 */
	private $levelId;

	/** 
	 * 等级名称
	 */
	private $levelName;

	/** 
	 * 等级类型：1:个人会员等级 2:分销商等级，默认值1
	 */
	private $type;

	/** 
	 * 升级方式：手动/自动
	 */
	private $upgradeMode;

	/** 
	 *  满此额，则升级
	 */
	private $upgradeMoney;

	public function setAdminId($adminId)
	{
		$this->adminId = $adminId;
		$this->apiParas["admin_id"] = $adminId;
	}
	public function getAdminId() {
		return $this->adminId;
	}

	public function setIsDefault($isDefault)
	{
		$this->isDefault = $isDefault;
		$this->apiParas["is_default"] = $isDefault;
	}
	public function getIsDefault() {
		return $this->isDefault;
	}

	public function setLevelId($levelId)
	{
		$this->levelId = $levelId;
		$this->apiParas["level_id"] = $levelId;
	}
	public function getLevelId() {
		return $this->levelId;
	}

	public function setLevelName($levelName)
	{
		$this->levelName = $levelName;
		$this->apiParas["level_name"] = $levelName;
	}
	public function getLevelName() {
		return $this->levelName;
	}

	public function setType($type)
	{
		$this->type = $type;
		$this->apiParas["type"] = $type;
	}
	public function getType() {
		return $this->type;
	}

	public function setUpgradeMode($upgradeMode)
	{
		$this->upgradeMode = $upgradeMode;
		$this->apiParas["upgrade_mode"] = $upgradeMode;
	}
	public function getUpgradeMode() {
		return $this->upgradeMode;
	}

	public function setUpgradeMoney($upgradeMoney)
	{
		$this->upgradeMoney = $upgradeMoney;
		$this->apiParas["upgrade_money"] = $upgradeMoney;
	}
	public function getUpgradeMoney() {
		return $this->upgradeMoney;
	}

	public function getApiMethodName()
	{
		return "qianmi.cloudshop.memberlevel.update";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		RequestCheckUtil::checkNotNull($this->upgradeMoney, "upgradeMoney");
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}
