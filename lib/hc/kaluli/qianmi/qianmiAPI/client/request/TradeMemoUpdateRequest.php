<?php
/**
 * API: qianmi.cloudshop.trade.memo.update request
 * 
 * @author auto create
 * @since 1.0
 */
class TradeMemoUpdateRequest
{
	private $apiParas = array();

	/** 
	 * 订单备注，reset为false时必传
	 */
	private $memo;

	/** 
	 * 是否需要对memo的值清空， 若传true，则忽略新传入的memo值，直接清空原有的memo，若为false，新传入的memo会覆盖原有的memo值
	 */
	private $reset;

	/** 
	 * 订单编号
	 */
	private $tid;

	public function setMemo($memo)
	{
		$this->memo = $memo;
		$this->apiParas["memo"] = $memo;
	}
	public function getMemo() {
		return $this->memo;
	}

	public function setReset($reset)
	{
		$this->reset = $reset;
		$this->apiParas["reset"] = $reset;
	}
	public function getReset() {
		return $this->reset;
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
		return "qianmi.cloudshop.trade.memo.update";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		RequestCheckUtil::checkNotNull($this->reset, "reset");
		RequestCheckUtil::checkNotNull($this->tid, "tid");
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}
