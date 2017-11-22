<?php
/**
 * TOP API: taobao.increment.subscription.add request
 * 
 * @author auto create
 * @since 1.0, 2012-12-19 11:36:47
 */
class IncrementSubscriptionAddRequest
{
	/** 
	 * 订阅消息的字段名称，如num_iid。此字段表示只能接收按消息中subscribe_key字段的值为subscribe_values中指定值的消息。目前，此字段只能为"num_iid"。
	 **/
	private $subscribeKey;
	
	/** 
	 * 订阅的消息属性值。和subscribe_key确定消息的订阅。用‘，’分隔的多个属性值; 最多传入20个。<br>当传入的属性值已经订阅时，会跳过此值的订阅。<br>每个属性值的长度不能超过32。
	 **/
	private $subscribeValues;
	
	/** 
	 * 指定订阅消息的类别，比如：商品(item)。目前只能为"item"。
	 **/
	private $topic;
	
	/** 
	 * 如果subscribe_key 为 num_iid并且只有track_iid,则在track_iids中填写，subscribe_values 中不需要任何值
	 **/
	private $trackIids;
	
	private $apiParas = array();
	
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
		return "taobao.increment.subscription.add";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		
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
