<?php
/**
 * API: qianmi.cloudshop.item.props.delete request
 * 
 * @author auto create
 * @since 1.0
 */
class ItemPropsDeleteRequest
{
	private $apiParas = array();

	/** 
	 * 要删除的商品规格项id
	 */
	private $pid;

	public function setPid($pid)
	{
		$this->pid = $pid;
		$this->apiParas["pid"] = $pid;
	}
	public function getPid() {
		return $this->pid;
	}

	public function getApiMethodName()
	{
		return "qianmi.cloudshop.item.props.delete";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		RequestCheckUtil::checkNotNull($this->pid, "pid");
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}
