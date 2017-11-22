<?php 
/**
 * author kworm
 * 2017-03-02
 */
class KaluliBBService
{
	public static  $_order_file_status = [
		1 => '未导入',
		2 => '已导入'
	];
	//身份证类型
	public static  $_card_type = [
		1 => '身份证',
	];
	//订单渠道
	public static $_order_source = [
		'JDQT' 		=> '京东Quest全球购旗舰店',
		'JDRK' 		=> '京东燃卡全球购专营店',
		'XSQD' 		=> '线上渠道',
		'QSTM'		=> 'Quest天猫旗舰店',
		'KL'		=> '考拉卡路里',
		'KLL'		=> '卡路里官网',
		'YPJS'		=>	'硬派健身',
		'QMFX'		=>	'千米销渠道',
		'JDBPI'		=>	'京东BPI'
	];
	//支付方式
	public static $_pay_type = [
		'支付宝'			=>  1,
		'微信'			=>	2,
		'银行卡'			=>	3,
		'考拉网易宝'		=>	4,
		'考拉支付宝'		=>	5,
		'考拉微信'		=>	6,
		'京东微信'		=>	7,
		
	];
	//订单状态
	public static $_order_status = [
		1	=> '待审核',
		2	=> '待付款',
		3	=> '待完成',
		4	=> '已完成',
		5	=> '异常',
		8	=>	'订单关闭',
		9	=>	'卡路里取消',
		10	=>	'外部订单完成'
	];
	public static $_push = [
		'_gj'		=> '国检',
		'_hg'		=>'海关',
		'_zz'		=> '卓志',
		'_yb_gj'	=> '国检支付',
		'_yb_hg'	=> '海关支付',
		'_nr'		=>	'能容',
		'_jd_pay'	=>	'京东支付单'
	];
	private $merchantId = '120142646';
	static public function getInstance() {
        static $handier = NULL;
        if (empty($handier)) {
            $handier = new self();
        }
        return $handier;
    }
	public static function AddOrderFile($post)
	{
		try {
			$fileObj = new KllBBOrderFile();
			$fileObj->setFile($post['file'])
			->setSource(trim($post['source']))
			->setUid(sfContext::getInstance()->getUser()->getTrdUserHuPuId())
			->setNumber(0)
			->setStatus(1)
			->setBatch(time())
			->setCreatTime(time())
			->setUpdateTime(time())
			->save();
			return true;
		} catch (Exception $e) {
			return false;
		}
		return true;

	}
	public static function saveImportOrder($line, $i, $type=1){
		//这里应该有个生成身份证的函数
		if(!isset($line['card_code'])){
			$card = self::getCardCode();
			$line['card_code'] = $card['card_code'];
			$line['real_name'] = $card['real_name'];
		}
		!isset($line['source']) && $line['source'] = 'qc';
		!isset($line['flow_number']) && $line['flow_number'] = 0;
		$line['order_number'] = isset($line['order_number']) ? $line['order_number'] : $line['source'].$line['origin_order_number'];
		!isset($line['order_type']) && $line['order_type'] = 1;
		!isset($line['card_type']) && $line['card_type'] = 1;
		!isset($line['pay_type']) && $line['pay_type'] = 7;
		!isset($line['batch']) && $line['batch'] = '0000000';
		$line['creat_time'] = isset($line['creat_time']) ? strtotime($line['creat_time']) : time();
		$line['update_time'] = isset($line['update_time']) ? strtotime($line['update_time']) : time();
		
		//主订单处理
		if($line['order_type'] == 1){
			$status = isset($line['status']) ? $line['status'] : 2;
			/*
			!preg_match("/^[\x{4e00}-\x{9fa5}A-Za-z0-9_]+$/u",$line['address']) && $status = 5;
			!preg_match("/^[\x{4e00}-\x{9fa5}A-Za-z0-9_]+$/u",$line['receiver']) && $status = 5;
			!preg_match("/^0?(13|14|15|17|18)[0-9]{9}$/", $line['mobile']) && $status = 5;
			!preg_match("/^(\d{15}$|^\d{18}$|^\d{17}(\d|X|x))$/", $line['card_code']) && $status = 5;
			*/
			//洗数据
			self::clearData($line);
			$db = Doctrine_Manager::getInstance()->getConnection('kaluli');
            $db->beginTransaction();
            try {
            	$check = KllBBMainOrderTable::getInstance()->findOneByOrderNumber($line['order_number']);
            	if(empty($check)){
					$mainOrderObj = new KllBBMainOrder();
					$line['card_code'] = str_replace("\n","",$line['card_code']);
					//主订单
					$mainOrderObj->setOrderNumber($line['order_number'])->setOriginOrderNumber($line['origin_order_number'])->setCount($line['count'])
						->setTotalPrice($line['total_price'])->setRealPrice($line['real_price'])->setPushPrice($line['push_price'])->setPayer($line['payer'])
						->setExpressFee($line['express_fee'])->setDutyFee($line['duty_fee'])->setBatch($line['batch'])->setUid(sfContext::getInstance()->getUser()->getTrdUserHuPuId())
						->setPayStatus(1)->setPayType($line['pay_type'])->setPayTime(strtotime($line['pay_time']))->setStatus($status)->setFlowNumber($line['flow_number'])
						->setSource($line['source'])->setCreatTime($line['creat_time'])->setUpdateTime($line['update_time'])
						->save();
					//主订单附件表
					$mainOrderAttrObj = new KllBBMainOrderAttr();
					$mainOrderAttrObj->setOrderNumber($line['order_number'])->setProvince($line['province'])
						->setCity($line['city'])->setArea($line['area'])->setAddress($line['address'])
						->setReceiver($line['receiver'])->setAccount($line['account'])->setRealName($line['real_name'])->setMobile($line['mobile'])
						->setPostalCode($line['postal_code'])->setCardType($line['card_type'])->setCardCode($line['card_code'])
						->setCreatTime($line['creat_time'])->setUpdateTime($line['update_time'])
						->save();
					//流程表
					bbKaluliService::insertBBProcess($line['order_number'], 1, '订单生成', time());
					bbKaluliService::insertBBProcess($line['order_number'], 2, '财务审核', time()+2*60);
					bbKaluliService::insertBBProcess($line['order_number'], 3, '订单支付', time()+3*60);
					$db->commit();
				}
            }catch(Exception $e) {
                $db->rollback();
            }
			
			
		}elseif($line['order_type'] == 2){

			self::clearData($line);
			//仓库默认为宁波仓
			!isset($line['ware_house']) && $line['ware_house'] = 19;
			$db = Doctrine_Manager::getInstance()->getConnection('kaluli');
            $db->beginTransaction();
			try {
				$total_price = number_format(intval($line['price'])*2, 2,'.','');
				//子订单处理
				$orderObj = new KllBBOrder();
				$line['count'] = isset($line['count']) ? $line['count'] : $line['number'];
				$check = KllBBOrderTable::getInstance()->findOneByChildOrderNumber($line['child_order_number']);
				if(empty($check)){
					$orderObj->setOrderNumber($line['order_number'])->setProductId($line['product_id'])->setGoodsId($line['goods_id'])->setPayTime(strtotime($line['pay_time']))
						->setPayStatus(1)->setNumber($line['count'])->setCreatTime($line['creat_time'])->setUpdateTime($line['update_time'])->setChildOrderNumber($line['child_order_number'])
						->setName($line['name'])->setDescription($line['description'])->setProductCode($line['product_code'])->setPrice($line['price'])->setTotalPrice($total_price)
						->setReceiver($line['cashier'])->setBatch($line['batch'])->setWareHouse($line['ware_house'])
						->save();
				}
				$db->commit();
			}catch(Exception $e) {
				$db->rollback();
			}
			
		}
	}
	/**
	 * 清空特殊字符
	 */
	public static function clearData($data){
		if(!empty($data)){
			foreach ($data as &$item) {
				$item = trim(str_replace("\n","",$item));
				//清除特殊字符
				$regex = "/\/|\~|\!|\@|\#|\\$|\%|\^|\&|\*|\(|\)|\+|\{|\}|\:|\<|\>|\?|\[|\]|\,|\.|\/|\;|\'|\`|\=|\\\|\|/";
				$item = preg_replace($regex,"",$item);
			}
		}

	}
	/**
	 * 获取mq消息
	 */
	public static function getMqMessage($order_number, $channel){
		if(!empty($order_number)){
			$channel = strtoupper(str_replace('_', '', $channel));
			$dataObj = KllBBMqTable::getByOrderNumber($order_number, $channel);
			if(!empty($dataObj)){
				$response = $dataObj->getMsgResponse();
				if(!empty($response)){
					return $response;
				}
			}
		}
		return '';
	}
	/**
	 * 根据用户获得渠道
	 */
	public function getBBChannel($uid=0){
		$channel = self::$_order_source;
		$channel_string = sfContext::getInstance()->getUser()->getTrdChannel();
		if(!empty($channel_string)){
			$ch = explode('-', $channel_string);
			foreach (self::$_order_source as $k => $source) {
				if(!in_array($k, $ch)){
					unset($channel[$k]);
				}
			}
			return $channel;
		}
		return [];
	}
	/**
	 * 获取身份证信息和名字
	 * @return card_code
	 * 应该是一个复杂的算法
	 */
	public static function getCardCode(){
		$purchar = KllPurchaserAuthTable::getInstance()->getOneRandAuth();
		if(!empty($purchar)){
			return ['real_name' => $purchar[0]['purchaser'], 'card_code' => $purchar[0]['card_number'] ];
		}
		return ['real_name' => '', 'card_code' => '000000000000'];
	}
	
	
}