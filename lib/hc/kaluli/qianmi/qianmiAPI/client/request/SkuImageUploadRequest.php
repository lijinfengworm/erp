<?php
/**
 * API: qianmi.cloudshop.sku.image.upload request
 * 
 * @author auto create
 * @since 1.0
 */
class SkuImageUploadRequest
{
	private $apiParas = array();

	/** 
	 * ItemImg结构中的所有字段均可返回，多个字段用”,”分隔。
	 */
	private $fields;

	/** 
	 * sku图片，最大:1M ，支持的文件类型：gif,jpg,jpeg,png；使用BASE64将图片文件进行编码，得到字符串，然后用“@”字符连接字符串（例：contentStr）和图片文件的格式（例：jpg）；示例：“jpg@contentStr”。
	 */
	private $image;

	/** 
	 * 图片顺序,默认0；取值范围：0-4。
	 */
	private $imgId;

	/** 
	 * 商品编号
	 */
	private $numIid;

	/** 
	 * 图片顺序，默认0。
	 */
	private $position;

	/** 
	 * sku编号
	 */
	private $skuId;

	public function setFields($fields)
	{
		$this->fields = $fields;
		$this->apiParas["fields"] = $fields;
	}
	public function getFields() {
		return $this->fields;
	}

	public function setImage($image)
	{
		$this->image = $image;
		$this->apiParas["image"] = $image;
	}
	public function getImage() {
		return $this->image;
	}

	public function setImgId($imgId)
	{
		$this->imgId = $imgId;
		$this->apiParas["img_id"] = $imgId;
	}
	public function getImgId() {
		return $this->imgId;
	}

	public function setNumIid($numIid)
	{
		$this->numIid = $numIid;
		$this->apiParas["num_iid"] = $numIid;
	}
	public function getNumIid() {
		return $this->numIid;
	}

	public function setPosition($position)
	{
		$this->position = $position;
		$this->apiParas["position"] = $position;
	}
	public function getPosition() {
		return $this->position;
	}

	public function setSkuId($skuId)
	{
		$this->skuId = $skuId;
		$this->apiParas["sku_id"] = $skuId;
	}
	public function getSkuId() {
		return $this->skuId;
	}

	public function getApiMethodName()
	{
		return "qianmi.cloudshop.sku.image.upload";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		RequestCheckUtil::checkNotNull($this->fields, "fields");
		RequestCheckUtil::checkNotNull($this->numIid, "numIid");
		RequestCheckUtil::checkNotNull($this->skuId, "skuId");
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}
