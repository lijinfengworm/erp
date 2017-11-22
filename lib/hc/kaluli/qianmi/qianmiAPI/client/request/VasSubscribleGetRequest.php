<?php
/**
 * API: qianmi.cloudshop.vas.subscrible.get request
 * 
 * @author auto create
 * @since 1.0
 */
class VasSubscribleGetRequest
{
	private $apiParas = array();

	/** 
	 * 应用收费代码（服务编码），从控制台 服务管理-收费管理-收费项目列表 能够获得该服务的应用收费代码
	 */
	private $articleCode;

	/** 
	 * 千米商家编码
	 */
	private $buyerId;

	public function setArticleCode($articleCode)
	{
		$this->articleCode = $articleCode;
		$this->apiParas["article_code"] = $articleCode;
	}
	public function getArticleCode() {
		return $this->articleCode;
	}

	public function setBuyerId($buyerId)
	{
		$this->buyerId = $buyerId;
		$this->apiParas["buyer_id"] = $buyerId;
	}
	public function getBuyerId() {
		return $this->buyerId;
	}

	public function getApiMethodName()
	{
		return "qianmi.cloudshop.vas.subscrible.get";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		RequestCheckUtil::checkNotNull($this->articleCode, "articleCode");
		RequestCheckUtil::checkNotNull($this->buyerId, "buyerId");
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}
