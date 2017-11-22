<?php
/**
 * API: qianmi.cloudshop.item.props.update request
 * 
 * @author auto create
 * @since 1.0
 */
class ItemPropsUpdateRequest
{
	private $apiParas = array();

	/** 
	 * ItemProp结构中的所有字段均可返回，多个字段用”,”分隔; 默认值：pid,pname,prop_vals。
	 */
	private $fields;

	/** 
	 * 规格项，规格值被商品使用不可修改,名称不可重复。
	 */
	private $pid;

	/** 
	 * 规格项名
	 */
	private $pname;

	/** 
	 * 修改规格值.数据格式为（规格值1,规格值2,规格值3)，规格值之间用','隔开。
	 */
	private $vnames;

	public function setFields($fields)
	{
		$this->fields = $fields;
		$this->apiParas["fields"] = $fields;
	}
	public function getFields() {
		return $this->fields;
	}

	public function setPid($pid)
	{
		$this->pid = $pid;
		$this->apiParas["pid"] = $pid;
	}
	public function getPid() {
		return $this->pid;
	}

	public function setPname($pname)
	{
		$this->pname = $pname;
		$this->apiParas["pname"] = $pname;
	}
	public function getPname() {
		return $this->pname;
	}

	public function setVnames($vnames)
	{
		$this->vnames = $vnames;
		$this->apiParas["vnames"] = $vnames;
	}
	public function getVnames() {
		return $this->vnames;
	}

	public function getApiMethodName()
	{
		return "qianmi.cloudshop.item.props.update";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		RequestCheckUtil::checkNotNull($this->pid, "pid");
		RequestCheckUtil::checkNotNull($this->vnames, "vnames");
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}
