<?php
/**
 * API: qianmi.cloudshop.trade.postage.update request
 * 
 * @author auto create
 * @since 1.0
 */
class TradePostageUpdateRequest
{
	private $apiParas = array();

	/** 
	 * 物流配送费用
	 */
	private $postFee;

	/** 
	 * 交易订单编号
	 */
	private $tid;

	public function setPostFee($postFee)
	{
		$this->postFee = $postFee;
		$this->apiParas["post_fee"] = $postFee;
	}
	public function getPostFee() {
		return $this->postFee;
	}

	public function setTid($tid)
	{
		$this->tid = $tid;
		$this->apiParas["tid"] = $tid;
	}
	public function getTid() {
		return $this->tid;
	}

	public function getApiMethodName()
	{
		return "qianmi.cloudshop.trade.postage.update";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		RequestCheckUtil::checkNotNull($this->postFee, "postFee");
		RequestCheckUtil::checkNotNull($this->tid, "tid");
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}
