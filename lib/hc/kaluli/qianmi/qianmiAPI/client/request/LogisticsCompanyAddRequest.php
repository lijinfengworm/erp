<?php
/**
 * API: qianmi.cloudshop.logistics.company.add request
 * 
 * @author auto create
 * @since 1.0
 */
class LogisticsCompanyAddRequest
{
	private $apiParas = array();

	/** 
	 * 千米官方物流公司编码 对应卖家LogistisCompany中的code，为空时表示自定义物流公司
	 */
	private $code;

	/** 
	 * 物流公司全称
	 */
	private $name;

	/** 
	 * 自定义公司官网地址
	 */
	private $url;

	public function setCode($code)
	{
		$this->code = $code;
		$this->apiParas["code"] = $code;
	}
	public function getCode() {
		return $this->code;
	}

	public function setName($name)
	{
		$this->name = $name;
		$this->apiParas["name"] = $name;
	}
	public function getName() {
		return $this->name;
	}

	public function setUrl($url)
	{
		$this->url = $url;
		$this->apiParas["url"] = $url;
	}
	public function getUrl() {
		return $this->url;
	}

	public function getApiMethodName()
	{
		return "qianmi.cloudshop.logistics.company.add";
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
