<?php
/**
 * TOP API: taobao.increment.open.message.get request
 * 
 * @author auto create
 * @since 1.0, 2012-12-19 11:36:47
 */
class IncrementOpenMessageGetRequest
{
	/** 
	 * 消息的结束时间，消息所对应的操作时间的最大值。和start_modified搭配使用能过滤消通知消息的时间段。不传时：如果设置了start_modified，默认为与start_modified同一天的23:59:59；否则默认为调用接口当天的23:59:59。（格式：yyyy-MM-dd HH:mm:ss）
注意：start_modified和end_modified的日期必须在必须在同一天内，比如：start_modified设置2000-01-01 00:00:00，则end_modified必须设置为2000-01-01这个日期
	 **/
	private $endModified;
	
	/** 
	 * 页码，取值范围:大于零的整数; 默认值:1,即返回第一页数据。
	 **/
	private $pageNo;
	
	/** 
	 * 每页条数，取值范围:大于零的整数;最大值:200;默认值:40。
	 **/
	private $pageSize;
	
	/** 
	 * 消息的开始时间，消息所对应的操作时间的最小值和end_modified搭配使用能过滤消通知消息的时间段。不传时：如果设置了end_modified，默认为与 end_modified同一天的00:00:00，否则默认为调用接口当天的00:00:00。（格式：yyyy-MM-dd HH:mm:ss）
注意：start_modified和end_modified的日期必须在必须在同一天内，比如：start_modified设置2000-01-01 00:00:00，则end_modified必须设置为2000-01-01这个日期
	 **/
	private $startModified;
	
	/** 
	 * 订阅的field
	 **/
	private $subscribeKey;
	
	/** 
	 * 订阅的field对应的具体的值，比如：具体的某个商品id
	 **/
	private $subscribeValue;
	
	/** 
	 * 消息类型，比如：item
	 **/
	private $topic;
	
	/** 
	 * 如果subscribe_key 为 num_iid并且你只有track_iid,则在track_iid中填写，subscribe_value 中不需要填写任何值
	 **/
	private $trackIid;
	
	private $apiParas = array();
	
	public function setEndModified($endModified)
	{
		$this->endModified = $endModified;
		$this->apiParas["end_modified"] = $endModified;
	}

	public function getEndModified()
	{
		return $this->endModified;
	}

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

	public function setStartModified($startModified)
	{
		$this->startModified = $startModified;
		$this->apiParas["start_modified"] = $startModified;
	}

	public function getStartModified()
	{
		return $this->startModified;
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

	public function setSubscribeValue($subscribeValue)
	{
		$this->subscribeValue = $subscribeValue;
		$this->apiParas["subscribe_value"] = $subscribeValue;
	}

	public function getSubscribeValue()
	{
		return $this->subscribeValue;
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

	public function setTrackIid($trackIid)
	{
		$this->trackIid = $trackIid;
		$this->apiParas["track_iid"] = $trackIid;
	}

	public function getTrackIid()
	{
		return $this->trackIid;
	}

	public function getApiMethodName()
	{
		return "taobao.increment.open.message.get";
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
		RequestCheckUtil::checkNotNull($this->topic,"topic");
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}
