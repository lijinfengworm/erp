<?php
/**
 * 考拉服务逻辑
 * Author by kworm
 */
class KaolaSynService  {

    private $error_flag = false;
    const APPKEY = '06cff893b070765734ad33e40a28ba3c';
    const APPSECRET = '0291af88ca6da8ec45e0524f46e1c69707eb060b';
    public static $access_token = '4522de31-ee2a-48cc-afb6-eb38a1d03fb0';
    // const APPKEY = 'edb6c3b9ac4847e7584c38e2b630b14f';
    // const APPSECRET = '8200ee92ec22fcae76e2f00bc5c79247188e0593';
    // public static $access_token = 'b6ee443b-cba6-4327-83ae-0de0afd58c95';
    private $bind = array();   //最终入库数据

   
    //https://oauth.kaola.com/oauth/authorize?response_type=token&client_id=06cff893b070765734ad33e40a28ba3c&redirect_uri=https://partner.kaola.com&state=mycode
    //https://www.kaola.com/#access_token=4522de31-ee2a-48cc-afb6-eb38a1d03fb0&token_type=bearer&state=mycode&expires_in=31535999&scope=read
    /**
     * 考拉服务列表
     * @staticvar \Admin\Service\Cache $systemHandier
     */
    static public function getInstance() {
        static $handier = NULL;
        
        if (empty($handier)) {
            $handier = new self();
        }
        return $handier;
    }
    /**
     * 同步考拉订单
     * 考拉订单不需要推送支付单，所以订单状态直接是3
     */
    public function sync($main_order, $options = ['shop' => 'KL']){
    	$order_number = $main_order->order_id;
    	$shop = $options['shop'];
    	$string = $this->packagingXml($main_order, $shop);
    	$return = KaluliFun::requestUrl('http://erp.kaluli.com/kaluli_api/getData', 'POST', ['data' => $string, 'type' => 'push', 'is_pay' => 0 ]);
		$xml = new XMLParser();
        $res = $xml->loadXmlString($return);
		//写入日志
		$message = [
			'order_number'	=>	$order_number,
			'body'			=>	['京东同步订单操作', $main_order]
		];
		$mq = new KllAmqpMQ();
        $mq->setExchangeMqTast('kaluli_erp_log', $message);
		return true;
    }
    /**
     * 同步订单
     */
    /*public function sync($main_order){
    	$mq = new KllAmqpMQ();
    	if(!empty($main_order)){
			$order_number = $main_order->order_id;
			// $pay_method_name = mb_convert_encoding($main_order->pay_method_name, "UTF-8");
			$pay_type = self::getPayType($main_order->pay_method_name);

			$db = Doctrine_Manager::getInstance()->getConnection('kaluli');
	        $db->beginTransaction();
	        try {
	        	//主订单
	            $main = KllBBMainOrderTable::getInstance()->findOneByOrderNumber($order_number);
	            //不存在
	            if(empty($main)){
	            	
	            	$main_order_data = [
	            		'order_number' 		=>  $order_number,
	            		'origin_order_num'	=>	$order_number,
	            		'total_price'		=>	$main_order->order_real_price,
	            		'push_price'		=>	$main_order->order_real_price,
	            		'real_price'		=>	$main_order->order_origin_price,
	            		'express_fee'		=>	$main_order->express_fee,
	            		'coupon_fee'		=>	$main_order->coupon_amount,
	            		'duty_fee'			=>	$main_order->tax_fee,
	            		'pay_status'		=> 	1,
	            		'pay_type'			=>	$pay_type,
	            		'pay_time'			=>	strtotime($main_order->pay_success_time),
	            		'creat_time'		=>	strtotime($main_order->order_time),
	            		'update_time'		=>	time(),
	            		'status'			=>  3,
	            		'source'			=>	'KL',
	            		'payer'				=> 'kaola',
	            		'flow_number'		=>  $main_order->trade_no,
	            	];
	            	//先插入子订单，然后再计算优惠值
	            	if(!empty($main_order->order_skus)){
	            		$activity_totle_amount = 0;
	            		foreach ($main_order->order_skus as $key => $sku) {
	            			$order_data = [
	            				'receiver'				=>	'q',
	            				'child_order_number' 	=> $order_number.'_'.$key,
	            				'order_number'			=> $order_number,
	            				'description'			=> $sku->product_name,
	            				'name'					=> $sku->product_name,
	            				'product_id'			=> $sku->sku_key,
	            				'product_code'			=> $sku->goods_no,
	            				'goods_id'				=> $sku->goods_no,
	            				'total_price'			=> $sku->real_totle_price,
	            				'price'					=> $sku->origin_price,
	            				'number'				=> $sku->count,
	            				'pay_time'				=> strtotime($main_order->pay_success_time),
	            				'update_time'			=> time(),
	            				'creat_time'			=> time()
	            			];
	            			$activity_totle_amount = $activity_totle_amount + floatval($sku->activity_totle_amount);
	            			$orderObj = new KllBBOrder();
	            			$item = [];
	            			$item['order_number'] = $order_number;
	            			$item['goods_id'] = $sku->goods_no;
	            			$orderObj->setOrderNumber($order_number)
	            				->setReceiver($order_data['receiver'])
	            				->setChildOrderNumber($order_data['child_order_number'])
	            				->setDescription($order_data['description'])
	            				->setName($order_data['name'])
	            				->setProductId($order_data['product_id'])
	            				->setProductCode($order_data['product_code'])
	            				->setGoodsId($order_data['goods_id'])
	            				->setTotalPrice($order_data['total_price'])
	            				->setPrice($order_data['price'])
	            				->setNumber($order_data['number'])
	            				->setPayTime($order_data['pay_time'])
	            				->setPayStatus(1)
	            				->setCreatTime($order_data['creat_time'])
	            				->save();
	            		}
	            	}
	            	$activity_totle_amount = $activity_totle_amount + floatval($main_order_data['coupon_fee']);

	            	// 主订单
	            	$mainOrderObj = new KllBBMainOrder();
	            	$mainOrderObj->setOrderNumber($order_number)
	            			->setOriginOrderNumber($main_order_data['order_number'])
	            			->setTotalPrice($main_order_data['total_price'])
	            			->setPushPrice($main_order_data['push_price'])
	            			->setRealPrice($main_order_data['real_price'])
	            			->setExpressFee($main_order_data['express_fee'])
	            			->setCouponFee($activity_totle_amount)
	            			->setDutyFee($main_order_data['duty_fee'])
	            			->setPayStatus($main_order_data['pay_status'])
	            			->setPayTime($main_order_data['pay_time'])
	            			->setPayType($main_order_data['pay_type'])
	            			->setCreatTime($main_order_data['creat_time'])
	            			->setUpdateTime($main_order_data['update_time'])
	            			->setStatus($main_order_data['status'])
	            			->setSource($main_order_data['source'])
	            			->setPayer($main_order_data['payer'])
	            			->setFlowNumber($main_order_data['flow_number'])
	            			->save();
	            	$main_order_attr_data = [
	            		'order_number' 		=>  $order_number,
	            		'province'			=>  $main_order->receiver_province_name,
	            		'city'				=>	$main_order->receiver_city_name,
	            		'area'				=>	$main_order->receiver_district_name,
	            		'address'			=>	$main_order->receiver_address_detail,
	            		'receiver'			=>	$main_order->receiver_name,
	            		'account'			=>	$main_order->buyer_account,
	            		'real_name'			=>	$main_order->cert_name,
	            		'mobile'			=>	$main_order->receiver_phone,
	            		'postal_code'		=>	$main_order->receiver_post_code,
	            		'card_type'			=> 	1,
	            		'card_code'			=> 	$main_order->cert_id_no,
	            		'creat_time'		=>	time(),
	            		'update_time'		=>	time()

	            	];
	            	
	            	//主订单附件表
	            	$mainOrderAttrObj = new KllBBMainOrderAttr();
	            	$mainOrderAttrObj->setOrderNumber($order_number)
	            			->setProvince($main_order_attr_data['province'])
	            			->setCity($main_order_attr_data['city'])
	            			->setArea($main_order_attr_data['area'])
	            			->setAddress($main_order_attr_data['address'])
	            			->setReceiver($main_order_attr_data['receiver'])
	            			->setAccount($main_order_attr_data['account'])
	            			->setRealName($main_order_attr_data['real_name'])
	            			->setMobile($main_order_attr_data['mobile'])
	            			->setPostalCode($main_order_attr_data['postal_code'])
	            			->setCardType($main_order_attr_data['card_type'])
	            			->setCardCode($main_order_attr_data['card_code'])
	            			->setCreatTime($main_order_attr_data['creat_time'])
	            			->setUpdateTime($main_order_attr_data['update_time'])
            			->save();

	            }else{
	            	$order_status = $main_order->order_status;
	            	if($order_status == 5){
	            		$main->setStatus($order_status)->save();
	            	}
	            	
	            }
	            
	        	$db->commit();
	        	
	        } catch (Exception $e) {
	        	$db->rollback();
	        }
	        //写入日志
			$message = [
				'order_number'	=>	$order_number,
				'body'			=>	['考拉订单同步操作', $main_order]
			];
			$mq->setExchangeMqTast("kaluli.erp.log", ['msg' => $message]);
		}
		
		return true;
    }
    */
    /**
     * 生成access_token
     */
    public function createAccessToken(){

    }
    /**
     * 生成sign
     */
    public function createSign($data){
		$string = '';
		if(is_array($data)){
			ksort($data);
			foreach ($data as $k => $item) {
				$string .= $k.$item;
			}
		}
		$string = self::APPSECRET.$string.self::APPSECRET;
		return strtoupper(md5($string));
		// $sign = strtoupper(md5('8200ee92ec22fcae76e2f00bc5c79247188e0593access_tokenb6ee443b-cba6-4327-83ae-0de0afd58c95app_keyedb6c3b9ac4847e7584c38e2b630b14fdate_type1end_time2017-04-29methodkaola.order.searchorder_id2017042816211084530077241order_status1page_no1page_size50start_time2017-04-28timestamp'.$current_time.'8200ee92ec22fcae76e2f00bc5c79247188e0593'));
    }
    /**
     * 同步物流
     */
    public function synLogistics($order){
    	if(!empty($order)){
    		$order_number = $order['order_number'];
    		$express_company_code = 'ZTO'; //$order['logistic_type'];
    		$express_no = $order['logistic_number'];
    		if(!empty($express_no)){
    			$order_list = KllBBOrderTable::getInstance()->findByOrderNumber($order_number);
	    		$sku_info = '';
	    		if(!empty($order_list)){
	    			foreach ($order_list as $ok => $ord) {
	    				$sku_key = $ord->getProductId();
	    				$count = $ord->getNumber();
	    				$sku_info .= $sku_key.':'.$count."|";
	    			}
	    		}
	    		if(!empty($sku_info)){
	    			$sku_info = substr($sku_info,0,strlen($sku_info)-1);
		    		$sign_data = [
						'order_id'				=> $order_number,
						'express_company_code'	=> $express_company_code,
						'express_no'			=> $express_no, 
						'sku_info'				=> $sku_info,
						'access_token'			=> self::$access_token,
						'timestamp' 			=> date("Y-m-d H:i:s"),
						'method'				=> 'kaola.logistics.deliver',
						'app_key'				=>  self::APPKEY,
					];
					$sign = $this->createSign($sign_data);
					$postfield = [
						'sign' 					=> $sign,
						'timestamp' 			=> date("Y-m-d%20H:i:s"),
						'method'				=> 'kaola.logistics.deliver',
						'app_key'				=> self::APPKEY,
						'access_token'			=> self::$access_token,
						'order_id'				=> $order_number,
						'express_company_code'	=> $express_company_code,
						'express_no'			=> $express_no,
						'sku_info'				=> $sku_info,
					];
					
					$string = self::getUrlString($postfield);
					$api_url = sfConfig::get('app_kaola_open_api_url').'/router?'.$string;
					
					$res = FunBase::getcurl($api_url);
					$orderObj = json_decode($res);
					$log = new KllBBOrderLog();
					$mainOrderObj = KllBBMainOrderTable::getInstance()->findOneByOrderNumber($order_number);
					if(!isset($orderObj->kaola_logistics_deliver_response)){
						if(isset($orderObj->error_response) && isset($orderObj->error_response->subErrors[0]->code)){
                           if($orderObj->error_response->subErrors[0]->code == 'kaola.logistics.deliver.status_error'){
	                            $mainOrderObj->setStatus(10)->setSynApi(3)->save();
	                        }
					}else{
						$logistics = $orderObj->kaola_logistics_deliver_response;
						if(!empty($logistics) && !empty($mainOrderObj)){
							$mainOrderObj->setStatus(10)->setSynApi(3)->save();
						}
					}
					//写入日志
					$message = [
						'order_number'	=>	$order_number,
						'body'			=>	['考拉同步物流单号操作', $orderObj]
					];
					$mq = new KllAmqpMQ();
                	$mq->setExchangeMqTast('kaluli_erp_log', $message);
	    		}
	    	}
    		}
    	}
    }
    public static function getUrlString($postfield){
		$string = '';
		if(is_array($postfield)){
			foreach ($postfield as $key => $field) {
				$string .= $key.'='.$field.'&';
			}
		}
		return $string;
	}
	public static function getPayType($pay_type){
		//由于编码是utf8可以直接用strpos直接搜索字符串
		if(!empty($pay_type)){
			if(strpos($pay_type, '支付宝') === false){
				if(strpos($pay_type, '微信') === false){
					return 4;
				}else{
					return 6;
				}
			}else{
				return 5;
			}
		}
	}
	/**
	 * 组装xml格式代码
	 */
	private function packagingXml($main_order, $shop){

		//运费：$main_order->freight_price
		$string = '<?xml version="1.0" encoding="utf-8"?><xmlroot>';
		$string .= '<PACKAGE>';
		$main_order_data = [
    		'order_number' 		=>  $main_order->order_id,
    		'origin_order_number'	=>	isset($main_order->origin_order_id) ? $main_order->origin_order_id : $main_order->order_id,
    		'total_price'		=>	$main_order->order_real_price,
    		'push_price'		=>	$main_order->order_real_price,
    		'real_price'		=>	$main_order->order_origin_price,
    		'express_fee'		=>	'0.00',
    		'coupon_fee'		=>	'0.00',
    		'duty_fee'			=>	'0.00',
    		'pay_status'		=> 	1,
    		'pay_type'			=>	self::getPayType($main_order->pay_method_name),
    		'pay_time'			=>	$main_order->pay_success_time,
    		'creat_time'		=>	date("Y-m-d H:i:s", strtotime($main_order->order_time) - 100),
    		'update_time'		=>	date("Y-m-d H:i:s", strtotime($main_order->order_time) - 100),
    		'status'			=>  isset($main_order->status) ? $main_order->status : 3,
    		'source'			=>	$shop,
    		'payer'				=>  $shop,
    		'uid'				=>  '000000',
    		'count'				=>	count($main_order->order_skus),
    		'audit_time'		=>  date("Y-m-d H:i:s", strtotime($main_order->order_time) - 100),
    		'province'			=>	$main_order->receiver_province_name,
    		'city'				=>	$main_order->receiver_city_name,
    		'area'				=>	$main_order->receiver_district_name,
    		'address'			=>	$main_order->receiver_address_detail,
    		'Receiver'			=>	$main_order->receiver_name,
    		'mobile'			=>	$main_order->receiver_phone,
    		'account'			=>	'000000',
    		'real_name'			=>	$main_order->cert_name,
    		'card_type'			=> 	1,
	        'card_code'			=> 	$main_order->cert_id_no,
	        'flow_number'		=>  $main_order->trade_no,
    	];
    	//拼接主订单
    	foreach ($main_order_data as $key => $item) {
    		$string .= '<' .FunBase::camelize($key). '>'. $item .'</'.FunBase::camelize($key).'>';
    	}
    	//拼接子订单
    	$string .= '<Order>';
    	$string .= '<Cot>'.count($main_order->order_skus).'</Cot>';
    	if(!empty($main_order->order_skus)){
    		$i = 1;
    		foreach ($main_order->order_skus as $key => $sku_info) {
    			$string .= '<Child>';
    			$sku = $sku_info;
    			$order_data = [
    				'receiver'				=> $shop,
    				'child_order_number' 	=> $main_order->order_id.'_'.$i,
    				'origin_order_number'	=> $main_order->order_id,
    				'order_number'			=> $main_order->order_id,
    				'description'			=> $sku->product_name,
    				'name'					=> $sku->product_name,
    				'product_id'			=> $sku->sku_key,
	            	'product_code'			=> $sku->goods_no,
	            	'goods_id'				=> $sku->goods_no,
    				'total_price'			=> $sku->real_totle_price,
    				'price'					=> $sku->origin_price,
    				'number'				=> $sku->count,
    				'source'				=>	$shop,
    				'pay_time'				=> $main_order->order_time,
    				'update_time'			=> date("Y-m-d H:i:s", strtotime($main_order->order_time) - 100),
    				'creat_time'			=> date("Y-m-d H:i:s", strtotime($main_order->order_time) - 100),
    				'ware_house'			=> 19
    			];
    			foreach ($order_data as $key => $item) {
    				$string .= '<' .FunBase::camelize($key). '>'. $item .'</'.FunBase::camelize($key).'>';
    			}
    			$string .= '</Child>';
    			$i++;
        	}
        }
    	$string .= '</Order>';
		$string .= '</PACKAGE>';
		$string .= '</xmlroot>';
		
		$length = sprintf('%06s', strlen(base64_encode($string)));
		return $length . base64_encode($string);

	}

}