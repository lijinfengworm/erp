<?php
/**
 * API: qianmi.cloudshop.refunds.apply.list request
 * 
 * @author auto create
 * @since 1.0
 */
class RefundsApplyListRequest
{
	private $apiParas = array();

	/** 
	 * 申请退货/退款编号
	 */
	private $applyId;

	/** 
	 * 申请状态 1-待审核 2-已审核通过 3-已收到退货 4-已退款 5-已完成 6-审核未通过
	 */
	private $applyState;

	/** 
	 * 查询截止时间_申请单创建时间
	 */
	private $endCreated;

	/** 
	 * 需返回字段列表，返回多个字段时，以逗号分隔。
	 */
	private $fields;

	/** 
	 * 商品名称
	 */
	private $itemName;

	/** 
	 * 会员昵称
	 */
	private $memberNick;

	/** 
	 * 用户类型 1个人 4分销商
	 */
	private $memberType;

	/** 
	 * 是否需要退款 1需要，0不需要
	 */
	private $needRefund;

	/** 
	 * 是否需要退货 1需要，0不需要
	 */
	private $needReturn;

	/** 
	 * 页码，大于等于0的整数，默认值0
	 */
	private $pageNo;

	/** 
	 * 每页条数，最大支持100，默认50
	 */
	private $pageSize;

	/** 
	 * 查询开始时间_申请单创建时间
	 */
	private $startCreated;

	/** 
	 * 订单编号
	 */
	private $tid;

	public function setApplyId($applyId)
	{
		$this->applyId = $applyId;
		$this->apiParas["apply_id"] = $applyId;
	}
	public function getApplyId() {
		return $this->applyId;
	}

	public function setApplyState($applyState)
	{
		$this->applyState = $applyState;
		$this->apiParas["apply_state"] = $applyState;
	}
	public function getApplyState() {
		return $this->applyState;
	}

	public function setEndCreated($endCreated)
	{
		$this->endCreated = $endCreated;
		$this->apiParas["end_created"] = $endCreated;
	}
	public function getEndCreated() {
		return $this->endCreated;
	}

	public function setFields($fields)
	{
		$this->fields = $fields;
		$this->apiParas["fields"] = $fields;
	}
	public function getFields() {
		return $this->fields;
	}

	public function setItemName($itemName)
	{
		$this->itemName = $itemName;
		$this->apiParas["item_name"] = $itemName;
	}
	public function getItemName() {
		return $this->itemName;
	}

	public function setMemberNick($memberNick)
	{
		$this->memberNick = $memberNick;
		$this->apiParas["member_nick"] = $memberNick;
	}
	public function getMemberNick() {
		return $this->memberNick;
	}

	public function setMemberType($memberType)
	{
		$this->memberType = $memberType;
		$this->apiParas["member_type"] = $memberType;
	}
	public function getMemberType() {
		return $this->memberType;
	}

	public function setNeedRefund($needRefund)
	{
		$this->needRefund = $needRefund;
		$this->apiParas["need_refund"] = $needRefund;
	}
	public function getNeedRefund() {
		return $this->needRefund;
	}

	public function setNeedReturn($needReturn)
	{
		$this->needReturn = $needReturn;
		$this->apiParas["need_return"] = $needReturn;
	}
	public function getNeedReturn() {
		return $this->needReturn;
	}

	public function setPageNo($pageNo)
	{
		$this->pageNo = $pageNo;
		$this->apiParas["page_no"] = $pageNo;
	}
	public function getPageNo() {
		return $this->pageNo;
	}

	public function setPageSize($pageSize)
	{
		$this->pageSize = $pageSize;
		$this->apiParas["page_size"] = $pageSize;
	}
	public function getPageSize() {
		return $this->pageSize;
	}

	public function setStartCreated($startCreated)
	{
		$this->startCreated = $startCreated;
		$this->apiParas["start_created"] = $startCreated;
	}
	public function getStartCreated() {
		return $this->startCreated;
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
		return "qianmi.cloudshop.refunds.apply.list";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		RequestCheckUtil::checkNotNull($this->fields, "fields");
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}
