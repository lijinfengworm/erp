<?php
/**
 * TOP API: taobao.increment.subscription.get request
 * 
 * @author auto create
 * @since 1.0, 2012-12-19 11:36:47
 */
class IncrementSubscriptionGetRequest
{
	/** 
	 * 页码。取值范围:大于零的整数; 默认值:1,即返回第一页数据。
	 **/
	private $pageNo;
	
	/** 
	 * 每页条数。取值范围:大于零的整数;最大值:200;默认值:40。
	 **/
	private $pageSize;
	
	/** 
	 * 订阅的属性名。为空时，查询指定topic下所有的非授权订阅属性的订阅。
	 **/
	private $subscribeKey;
	
	/** 
	 * 查询订阅的消息属性值。和subscribe_key确定消息的订阅。用‘，’分隔的多个属性值; 最多传入20个。为空时查询订阅subscribe_key的所有属性值。
	 **/
	private $subscribeValues;
	
	/** 
	 * 指定订阅消息的类别，比如：商品(item)。目前只能为"item"。
	 **/
	private $topic;
	
	/** 
	 * 如果subscribe_key 为 num_iid并且你只有track_iid,则在track_iids中填写，subscribe_values 中不需要填写任何值
	 **/
	private $trackIids;
	
	private $apiParas = array();
	
	public function setPageNo($pageNo)
	{
		$this->pageNo = $pageNo;
		$this->apiParas["page_no"] = $pageNo;
	}

	public function getPageNo()
	{
		return $this->pageNo;
	}

	public function setPageSize($pageSize)
	{
		$this->pageSize = $pageSize;
		$this->apiParas["page_size"] = $pageSize;
	}

	public function getPageSize()
	{
		return $this->pageSize;
	}

	public function setSubscribeKey($subscribeKey)
	{
		$this->subscribeKey = $subscribeKey;
		$this->apiParas["subscribe_key"] = $subscribeKey;
	}

	public function getSubscribeKey()
	{
		return $this->subscribeKey;
	}

	public function setSubscribeValues($subscribeValues)
	{
		$this->subscribeValues = $subscribeValues;
		$this->apiParas["subscribe_values"] = $subscribeValues;
	}

	public function getSubscribeValues()
	{
		return $this->subscribeValues;
	}

	public function setTopic($topic)
	{
		$this->topic = $topic;
		$this->apiParas["topic"] = $topic;
	}

	public function getTopic()
	{
		return $this->topic;
	}

	public function setTrackIids($trackIids)
	{
		$this->trackIids = $trackIids;
		$this->apiParas["track_iids"] = $trackIids;
	}

	public function getTrackIids()
	{
		return $this->trackIids;
	}

	public function getApiMethodName()
	{
		return "taobao.increment.subscription.get";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		
		RequestCheckUtil::checkMinValue($this->pageNo,1,"pageNo");
		RequestCheckUtil::checkMaxValue($this->pageSize,200,"pageSize");
		RequestCheckUtil::checkMinValue($this->pageSize,1,"pageSize");
		RequestCheckUtil::checkNotNull($this->subscribeKey,"subscribeKey");
		RequestCheckUtil::checkMaxListSize($this->subscribeValues,20,"subscribeValues");
		RequestCheckUtil::checkNotNull($this->topic,"topic");
		RequestCheckUtil::checkMaxListSize($this->trackIids,20,"trackIids");
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}
