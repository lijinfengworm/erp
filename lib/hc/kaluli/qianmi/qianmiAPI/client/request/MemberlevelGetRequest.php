<?php
/**
 * API: qianmi.cloudshop.memberlevel.get request
 * 
 * @author auto create
 * @since 1.0
 */
class MemberlevelGetRequest
{
	private $apiParas = array();

	/** 
	 * 等级编号
	 */
	private $levelId;

	public function setLevelId($levelId)
	{
		$this->levelId = $levelId;
		$this->apiParas["level_id"] = $levelId;
	}
	public function getLevelId() {
		return $this->levelId;
	}

	public function getApiMethodName()
	{
		return "qianmi.cloudshop.memberlevel.get";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		RequestCheckUtil::checkNotNull($this->levelId, "levelId");
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}
