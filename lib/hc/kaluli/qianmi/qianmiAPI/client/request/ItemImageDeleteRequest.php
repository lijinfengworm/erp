<?php
/**
 * API: qianmi.cloudshop.item.image.delete request
 * 
 * @author auto create
 * @since 1.0
 */
class ItemImageDeleteRequest
{
	private $apiParas = array();

	/** 
	 * 图片编号
	 */
	private $imgId;

	/** 
	 * 商品编号
	 */
	private $numIid;

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

	public function getApiMethodName()
	{
		return "qianmi.cloudshop.item.image.delete";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		RequestCheckUtil::checkNotNull($this->imgId, "imgId");
		RequestCheckUtil::checkNotNull($this->numIid, "numIid");
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}
