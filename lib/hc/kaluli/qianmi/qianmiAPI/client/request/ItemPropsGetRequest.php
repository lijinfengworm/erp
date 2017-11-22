<?php
/**
 * API: qianmi.cloudshop.item.props.get request
 * 
 * @author auto create
 * @since 1.0
 */
class ItemPropsGetRequest
{
	private $apiParas = array();

	/** 
	 * ItemProp结构中的所有字段均可返回，多个字段用”,”分隔; 默认值：pid,pname,prop_vals。
	 */
	private $fields;

	/** 
	 * 规格的id，可选
	 */
	private $pid;

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

	public function getApiMethodName()
	{
		return "qianmi.cloudshop.item.props.get";
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
