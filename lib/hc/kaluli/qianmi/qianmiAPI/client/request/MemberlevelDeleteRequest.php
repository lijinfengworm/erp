<?php
/**
 * API: qianmi.cloudshop.memberlevel.delete request
 * 
 * @author auto create
 * @since 1.0
 */
class MemberlevelDeleteRequest
{
	private $apiParas = array();

	/** 
	 * 等级id
	 */
	private $levelIds;

	public function setLevelIds($levelIds)
	{
		$this->levelIds = $levelIds;
		$this->apiParas["level_ids"] = $levelIds;
	}
	public function getLevelIds() {
		return $this->levelIds;
	}

	public function getApiMethodName()
	{
		return "qianmi.cloudshop.memberlevel.delete";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		RequestCheckUtil::checkNotNull($this->levelIds, "levelIds");
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}
