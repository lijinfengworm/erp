<?php
/**
 * API: qianmi.cloudshop.d2p.trade.pack request
 * 
 * @author auto create
 * @since 1.0
 */
class D2pTradePackRequest
{
	private $apiParas = array();

	/** 
	 * 发货包裹清单，包含商品单编号和商品出库数量，格式：oid:num;oid:num。商品出库数量为整数，且出库总数量不能大于商品的购买数量
	 */
	private $packItems;

	/** 
	 * 订单编号
	 */
	private $tid;

	public function setPackItems($packItems)
	{
		$this->packItems = $packItems;
		$this->apiParas["pack_items"] = $packItems;
	}
	public function getPackItems() {
		return $this->packItems;
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
		return "qianmi.cloudshop.d2p.trade.pack";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		RequestCheckUtil::checkNotNull($this->packItems, "packItems");
		RequestCheckUtil::checkNotNull($this->tid, "tid");
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}
