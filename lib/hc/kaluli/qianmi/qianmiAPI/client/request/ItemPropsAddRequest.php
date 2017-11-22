<?php
/**
 * API: qianmi.cloudshop.item.props.add request
 * 
 * @author auto create
 * @since 1.0
 */
class ItemPropsAddRequest
{
	private $apiParas = array();

	/** 
	 * ItemProp结构中的所有字段均可返回，多个字段用”,”分隔； 默认值：pid,pname,prop_vals。
	 */
	private $fields;

	/** 
	 * 规格项
	 */
	private $pname;

	/** 
	 * 规格值，多个值用”,”隔开,数量不能超过50个
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
		return "qianmi.cloudshop.item.props.add";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		RequestCheckUtil::checkNotNull($this->pname, "pname");
		RequestCheckUtil::checkNotNull($this->vnames, "vnames");
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}
