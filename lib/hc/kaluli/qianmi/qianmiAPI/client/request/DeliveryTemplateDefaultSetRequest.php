<?php
/**
 * API: qianmi.cloudshop.delivery.template.default.set request
 * 
 * @author auto create
 * @since 1.0
 */
class DeliveryTemplateDefaultSetRequest
{
	private $apiParas = array();

	/** 
	 * 模板编号
	 */
	private $templateId;

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
		return "qianmi.cloudshop.delivery.template.default.set";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		RequestCheckUtil::checkNotNull($this->templateId, "templateId");
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}
