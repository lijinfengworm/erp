<?php
/**
 * API: qianmi.cloudshop.item.brand.update request
 * 
 * @author auto create
 * @since 1.0
 */
class ItemBrandUpdateRequest
{
	private $apiParas = array();

	/** 
	 * 品牌描述
	 */
	private $brandDesc;

	/** 
	 * 品牌编号
	 */
	private $brandId;

	/** 
	 * 品牌名称
	 */
	private $brandName;

	/** 
	 * 新增品牌的信息，返回字段参照ItemBrand结构，多个字段用”,”分隔；
	 */
	private $fields;

	/** 
	 * 品牌图片，最大:1M ，支持的文件类型：gif,jpg,jpeg,png；注：使用BASE64将图片文件进行编码，得到字符串，然后用“@”字符连接字符串（例：contentStr）和图片文件的格式（例
	 */
	private $logo;

	/** 
	 * 排序
	 */
	private $position;

	public function setBrandDesc($brandDesc)
	{
		$this->brandDesc = $brandDesc;
		$this->apiParas["brand_desc"] = $brandDesc;
	}
	public function getBrandDesc() {
		return $this->brandDesc;
	}

	public function setBrandId($brandId)
	{
		$this->brandId = $brandId;
		$this->apiParas["brand_id"] = $brandId;
	}
	public function getBrandId() {
		return $this->brandId;
	}

	public function setBrandName($brandName)
	{
		$this->brandName = $brandName;
		$this->apiParas["brand_name"] = $brandName;
	}
	public function getBrandName() {
		return $this->brandName;
	}

	public function setFields($fields)
	{
		$this->fields = $fields;
		$this->apiParas["fields"] = $fields;
	}
	public function getFields() {
		return $this->fields;
	}

	public function setLogo($logo)
	{
		$this->logo = $logo;
		$this->apiParas["logo"] = $logo;
	}
	public function getLogo() {
		return $this->logo;
	}

	public function setPosition($position)
	{
		$this->position = $position;
		$this->apiParas["position"] = $position;
	}
	public function getPosition() {
		return $this->position;
	}

	public function getApiMethodName()
	{
		return "qianmi.cloudshop.item.brand.update";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		RequestCheckUtil::checkNotNull($this->brandId, "brandId");
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}
