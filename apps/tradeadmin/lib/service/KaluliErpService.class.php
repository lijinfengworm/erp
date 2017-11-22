<?php 
/**
 * author kworm
 * 2017-08-02
 */
class KaluliErpService 
{

	public static $_status = [
		1	=>	'通过',
		2	=> 	'拒绝',
	];
	//商品标准价审核的状态
	public static $_sku_status = [
		0	=>	'待审核',
		1	=>	'审核通过',
		2	=>	'拒绝'
	];
	//需要财审订单的状态
	public static $_order_status = [
		0	=>	'待审核',
		1	=>	'审核通过',
		2	=>	'拒绝'
	];
	static public function getInstance() {
        static $handier = NULL;
        if (empty($handier)) {
            $handier = new self();
        }
        return $handier;
    }
	/**
	 * 根据订单获得子订单的信息
	 */
	public function getOrderInfo($order_number){
		$order_info = [];
		$bind['where']['order_number'] = 'order_number = '.$order_number;
		$main_order = KaluliMainOrderTable::getAll($bind);
		if(!empty($main_order)){
			$order_info['order_number'] = $main_order[0]['order_number'];
			$order_info['pay_time'] = $main_order[0]['pay_time'];
			$order_info['total_price'] = $main_order[0]['total_price'];
			$order_info['coupon_fee'] = $main_order[0]['coupon_fee'];
			$order_list = KaluliOrderTable::getOrdersByOrderNubmer($main_order[0]['order_number']);
			$order_info['list'] = $order_list;
		}
		return $order_info;
	}
	/** 
	 * 根据id获得订单列表
	 */
	public function getOrderInfoByErpId($id){
		try {
			$erp = KllErpOrderTable::getInstance()->findOneById($id);
			if(!empty($erp)){
				$erp_array = $erp->toArray();
				$erp_array['list'] = $this->getOrderInfo($erp_array['order_number']);
			}
		} catch (Exception $e) {
			return $e->getMessage();
		}
		return $erp_array;
	}
	/**
	 * 根据订单号获得接受
	 */
	public function getExplainByOrderNumber($order_number){
		try {
			$explain_obj = KllErpOrderExplainTable::getInstance()->findByOrderNumber($order_number);
			if(!empty($explain_obj)) $explain = $explain_obj->toArray();
			return $explain;
		} catch (Exception $e) {
			return [];
		}
	}
	/**
	 * 插入sku表
	 */
	public function insertSkuPrice($line){
		if(!empty($line)){
			foreach ($line as $key => $item) {
				$bind = [];
				//货号、渠道、仓库能确认一个sku
				$bind['where']['code_num'] = 'code_num = "'.$item['code_num'].'"';
				$bind['where']['channel'] = 'channel = "'.$item['channel'].'"';
				$bind['where']['depot'] = 'depot = "'.$item['depot'].'"';
				$res = KllErpSkuPriceTable::getAll($bind);
				if(!empty($res) && isset($item['goods_id'])){
					$sku = KllErpSkuPriceTable::getInstance()->findOneById($res[0]['id']);
					$sku->setSkuId($item['sku_id'])
						->setCodeNum($item['code_num'])
						->setGoodsTitle($item['goods_title'])
						->setDepot($item['depot'])
						->setStandardPrice($item['standard_price'])
						->setCostPrice($item['cost_price'])
						->setAddUser($item['add_user'])
						->setUpdateTime(time())
						->setChannel($item['channel'])
						->setGoodsId($item['goods_id'])
						->setAuditUser(0);
					$sku->save();

				}else{
					$sku = new KllErpSkuPrice();
					$sku->setSkuId($item['sku_id'])
						->setCodeNum($item['code_num'])
						->setGoodsTitle($item['goods_title'])
						->setDepot($item['depot'])
						->setStandardPrice($item['standard_price'])
						->setCostPrice($item['cost_price'])
						->setAddUser($item['add_user'])
						->setUpdateTime(time())
						->setCreateTime(time())
						->setChannel($item['channel'])
						->setGoodsId($item['goods_id'])
						->setAuditUser(0);
					$sku->save();
				}
				//写入日志
				$message = [
					'author'		=>  $item['add_user'],
					'order_number'	=>	'',
					'type'			=>	1,
					'body'			=>	['财务导入sku操作',$item]
				];
				$mq = new KllAmqpMQ();
				$mq->setExchangeMqTast('kaluli_erp_log', $message);
			}
		}
		return true;
	}
	//获得官网标准价 $goods_id  是订单的goods_id
	public function getStandardPrice($goods_id, $channel, $depot, $code_num=0){
		$standard_price = 0;
		if(!$code_num){
			$goods = KaluliItemSkuTable::getInstance()->findOneById($goods_id);
			$goods_no = $goods->getCode();
		}else{
			$goods_no = $code_num;
		}
		$bind['where']['code_num'] = 'code_num = "'.$goods_no.'"';
		$bind['where']['channel'] = 'channel = "'.$channel.'"';
		$bind['where']['depot'] = 'depot = "'.$depot.'"';
		$bind['where']['audit_status'] = 'audit_status = 1';
		$erp = KllErpSkuPriceTable::getAll($bind);
		if(!empty($erp)){
			$standard_price = $erp[0]['standard_price'];
		}
		return $standard_price;
	}
	


}