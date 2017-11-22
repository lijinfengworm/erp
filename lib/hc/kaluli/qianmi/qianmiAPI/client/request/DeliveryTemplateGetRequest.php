<?php
/**
 * API: qianmi.cloudshop.delivery.template.get request
 * 
 * @author auto create
 * @since 1.0
 */
class DeliveryTemplateGetRequest
{
	private $apiParas = array();

	/** 
	 * 需要返回的字段，多个字段之间以逗号隔开
	 */
	private $fields;

	/** 
	 * 模板编号
	 */
	private $templateId;

	public function setFields($fields)
	{
		$this->fields = $fields;
		$this->apiParas["fields"] = $fields;
	}
	public function getFields() {
		return $this->fields;
	}

	public function setTemplateId($templateId)
	{
		$this->templateId = $templateId;
		$this->apiParas["template_id"] = $templateId;
	}
	public function getTemplateId() {
		return $this->templateId;
	}

	public function getApiMethodName()
	{
		return "qianmi.cloudshop.delivery.template.get";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		RequestCheckUtil::checkNotNull($this->fields, "fields");
		RequestCheckUtil::checkNotNull($this->templateId, "templateId");
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}
