<?php
/**
 * API: qianmi.cloudshop.item.add request
 * 
 * @author auto create
 * @since 1.0
 */
class ItemAddRequest
{
	private $apiParas = array();

	/** 
	 * 商品的品牌编号
	 */
	private $brandId;

	/** 
	 * 商品的品牌名称
	 */
	private $brandName;

	/** 
	 * 商品类目
	 */
	private $cid;

	/** 
	 * 商品来源
	 */
	private $dataSource;

	/** 
	 * 商品描述
	 */
	private $desc;

	/** 
	 * 所发布商品的商品详情，返回字段参照Item商品结构，多个字段用”,”分隔；
	 */
	private $fields;

	/** 
	 * 规格开关，默认启用
	 */
	private $hasProps;

	/** 
	 * 商品主图，最大:1M ，支持的文件类型：gif,jpg,jpeg,png；使用BASE64将图片文件进行编码，得到字符串，然后用“@”字符连接字符串（例：contentStr）和图片文件的格式（例：jpg）；示例：“jpg@contentStr”。
	 */
	private $image;

	/** 
	 *  外部商品编号，不超过32位。
	 */
	private $outerId;

	/** 
	 * 默认0：关联所有已开通的销售渠道，1仅云订货，2仅云商城，3：不关联任何销售渠道
	 */
	private $site;

	/** 
	 * 商品的sku信息JSON字符串；其中，sku可用字段：price（价格），quantity（库存），cost_price（成本价），outer_id（外部编号），barcode（条形码），副标题（sell_point），规格（sku_props）；sku_props的price和quantity必传，cost_price默认值为0。
	 */
	private $skusJson;

	/** 
	 * 商品名称
	 */
	private $title;

	/** 
	 * 商品的计量单位
	 */
	private $unit;

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

	public function setCid($cid)
	{
		$this->cid = $cid;
		$this->apiParas["cid"] = $cid;
	}
	public function getCid() {
		return $this->cid;
	}

	public function setDataSource($dataSource)
	{
		$this->dataSource = $dataSource;
		$this->apiParas["dataSource"] = $dataSource;
	}
	public function getDataSource() {
		return $this->dataSource;
	}

	public function setDesc($desc)
	{
		$this->desc = $desc;
		$this->apiParas["desc"] = $desc;
	}
	public function getDesc() {
		return $this->desc;
	}

	public function setFields($fields)
	{
		$this->fields = $fields;
		$this->apiParas["fields"] = $fields;
	}
	public function getFields() {
		return $this->fields;
	}

	public function setHasProps($hasProps)
	{
		$this->hasProps = $hasProps;
		$this->apiParas["has_props"] = $hasProps;
	}
	public function getHasProps() {
		return $this->hasProps;
	}

	public function setImage($image)
	{
		$this->image = $image;
		$this->apiParas["image"] = $image;
	}
	public function getImage() {
		return $this->image;
	}

	public function setOuterId($outerId)
	{
		$this->outerId = $outerId;
		$this->apiParas["outer_id"] = $outerId;
	}
	public function getOuterId() {
		return $this->outerId;
	}

	public function setSite($site)
	{
		$this->site = $site;
		$this->apiParas["site"] = $site;
	}
	public function getSite() {
		return $this->site;
	}

	public function setSkusJson($skusJson)
	{
		$this->skusJson = $skusJson;
		$this->apiParas["skus_json"] = $skusJson;
	}
	public function getSkusJson() {
		return $this->skusJson;
	}

	public function setTitle($title)
	{
		$this->title = $title;
		$this->apiParas["title"] = $title;
	}
	public function getTitle() {
		return $this->title;
	}

	public function setUnit($unit)
	{
		$this->unit = $unit;
		$this->apiParas["unit"] = $unit;
	}
	public function getUnit() {
		return $this->unit;
	}

	public function getApiMethodName()
	{
		return "qianmi.cloudshop.item.add";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		RequestCheckUtil::checkNotNull($this->fields, "fields");
		RequestCheckUtil::checkNotNull($this->title, "title");
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}
