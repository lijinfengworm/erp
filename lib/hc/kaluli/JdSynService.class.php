<?php
/**
 * 京东服务逻辑
 * Author by kworm
 */
class JdSynService  {

    private $error_flag = false;
    const APPKEY = '25423956E2A598F18BEABDE817BE99FA';
    const APPSECRET = 'dc787c0164ea4366b963e0d8c04c94a9';
    public static $access_token = '3bf2141b-bd18-4a57-8fa9-e902b61c338d';
    private $bind = array();   //最终入库数据

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
     * 同步京东订单
     * Quest店，从京东后台拉取数据
     */
    public function sync($main_order, $options){
    	$order_number = $main_order->order_id;
    	$shop = $options['shop'];
    	$string = $this->packagingXml($main_order, $shop);

    	$return = KaluliFun::requestUrl('http://erp.kaluli.com/kaluli_api/getData', 'POST', ['data' => $string, 'type' => 'push', 'is_pay' => 1 ]);
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
     * 从EDB拉取数据到BB项目
     * 接口还是走sync
     * 拼接main_order
     */
    public function syncRk($options){
 		try {
            $shop_id = 30;
            if(empty($options) || !isset($options['shop'])){
                throw new Exception("店铺不能为空");
            }else{
                $options['shop'] == 'JDRK' && $shop_id = 30;
                $options['shop'] == 'JDBPI' && $shop_id = 37;
            }
            $res = KllEdbSyncService::getInstance()->getAllOrder($shop_id);  
            if(isset($res['Success']) && !empty($res['Success']['items']['item'])){
                $list = $res['Success']['items']['item'];
                foreach ($list as $key => $item) {
                    $main_order = [
                        'order_id'          	=>  $item['tid'],
                        'origin_order_id'       =>  $item['out_tid'],
                        'order_payment'     	=>  $item['order_totalfee'],
                        'order_seller_price'    =>  $item['order_totalfee'],
                        'pay_type'      		=>  $item['pay_mothed'],
                        'order_start_time'      =>  $item['pay_time'],
                        'status'                =>  2,

                    ];
                     $main_order['consignee_info'] = [
                        'province'      =>  $item['province'],
                        'city'      	=>  $item['city'],
                        'county'      	=>  $item['district'],
                        'full_address'  =>  $item['address'],
                        'fullname'      =>  $item['receiver_name'],
                        'mobile'      	=>  $item['receiver_mobile'],
                    ];
                    $child = [];
                    if(!empty($item['tid_item'])){
                        $main_order['child_cot'] = count($item['tid_item']);
                        foreach ($item['tid_item'] as $k => $it) {
                            if(!strstr($it['barcode'], "NB")){
                                continue;
                            } 
                            $it['barcode'] = !empty($it['barcode']) ? $it['barcode'] : '000000000';
                            $it['barcode'] = substr($it['barcode'], 2);
                            $it['product_no'] = !empty($it['product_no']) ? $it['product_no'] : '000000000';
                            $it['product_no'] = substr($it['product_no'], 2);
                            $child[$k] = [
                                'order_id'          =>  $it['tid'],
                                'sku_name'          =>  $it['pro_name'],
                                'outer_sku_id'      =>  $it['barcode'],
                                'sku_id'          	=>  $it['barcode'],
                                'ware_id'          	=>  $it['product_no'],
                                'order_seller_price'=>  $it['sell_price'],
                                'item_total'        =>  $it['pro_num'],
                                'order_start_time' 	=>  $item['pay_time'],
                            ];
                        }
                    }
                    $main_order['item_info_list'] = $child;
                    $obj = $this->array_to_object($main_order);
                    $this->sync($obj, $options);
                }

            }
        } catch(sfException $e) {
            throw new sfException($e->getMessage());
        } 

    }
    public function syncRkExpress(){
        //开始轮播订单待同步的状态
        $list = KllBBMainOrderTable::getAllOrderByStatusAndSource(4, 'JDRK');
        if(!empty($list)){
            foreach ($list as $key => $item) {
                $message['order_number'] = $item['order_number'];
                try {
                    $res = KllEdbSyncService::getInstance()->syncOrderExpress($item['order_number'], $item['logistic_type'], $item['logistic_number']);
                    if($res){
                        $obj = KllBBMainOrderTable::getInstance()->findOneByOrderNumber($item['order_number']);
                        $obj->setSynApi(3)->save();
                        $message['body']    =  ['京东同步物流单号操作', ['data' => '更新成功']];
                    }
                } catch (Exception $e) {
                    $message['body']          =  ['京东同步物流单号操作', ['data' => $e->getMessage()]];
                }
                $mq = new KllAmqpMQ();
                $mq->setExchangeMqTast('kaluli_erp_log', $message);
                
            }
        }
        
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
    }
    /**
     * 京东
     * 同步物流
     */
    public function synLogistics($order){
    	if(!empty($order)){

    		$order_number = $order['order_number'];
    		$jd_order_number = $order['origin_order_number'];
    		$express_company_code = $this->toJdLogistics($order['logistic_type']);
    		$express_no = $order['logistic_number'];
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
	    		$buy_param_json_data = [
					'logistics_id'		=> $express_company_code,
					'waybill'			=> $express_no,
					'order_id'			=> $jd_order_number,
				];

				$buy_param_json = json_encode($buy_param_json_data);
				$sign_data = [
					'360buy_param_json' => 	$buy_param_json,
					'app_key'			=> 	JdSynService::APPKEY,
					'access_token'		=>	JdSynService::$access_token,
					'method'			=>	'360buy.order.sop.outstorage',
					'timestamp' 		=> 	date("Y-m-d H:i:s"),
					'v'					=>	'2.0'
				];
				$sign = $this->createSign($sign_data);
				$postfield = [
					'v'						=> '2.0',
					'sign' 					=> $sign,
					'timestamp' 			=> date("Y-m-d%20H:i:s"),
					'method'				=> '360buy.order.sop.outstorage',
					'app_key'				=> JdSynService::APPKEY,
					'access_token'			=> JdSynService::$access_token,
					'360buy_param_json' 	=> urlencode($buy_param_json),
				];
				
				$string = self::getUrlString($postfield);
				$api_url = sfConfig::get('app_jd_open_api_url').'/routerjson?'.$string;
				$res = FunBase::getcurl($api_url);
				$orderObj = json_decode($res);
				$logistics_response = $orderObj->order_sop_outstorage_response;
				if(!empty($logistics_response)){
					$code = $logistics_response->code;
					$mainOrderObj = KllBBMainOrderTable::getInstance()->findOneByOrderNumber($order_number);
					if($code == '0'  && !empty($mainOrderObj)){
						$mainOrderObj->setStatus(10)->setSynApi(3)->save();
					}
				}
				$logistics_error = $orderObj->error_response;
                if(!empty($logistics_error)){
                    $code = $logistics_error->code;
                    $mainOrderObj = KllBBMainOrderTable::getInstance()->findOneByOrderNumber($order_number);
                    if($code == '10400001'  && !empty($mainOrderObj)){
                        $mainOrderObj->setStatus(10)->setSynApi(3)->save();
                    }
                }
				$order_respond = $logistics_response->orderSopOutstorageResponse;
				//写入日志
				$message = [
					'order_number'	=>	$order_number,
					'body'			=>	['京东同步物流单号操作', $logistics_response, $order_respond]
				];
				$mq = new KllAmqpMQ();
                $mq->setExchangeMqTast('kaluli_erp_log', $message);
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
			if(strpos($pay_type, '银行转账') === false){
				if(strpos($pay_type, '在线支付') === false){
					return 7;
				}else{
					return 8;
				}
			}else{
				return 9;
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
    		'total_price'		=>	$main_order->order_payment,
    		'push_price'		=>	$main_order->order_seller_price,
    		'real_price'		=>	$main_order->order_seller_price,
    		'express_fee'		=>	'0.00',
    		'coupon_fee'		=>	'0.00',
    		'duty_fee'			=>	'0.00',
    		'pay_status'		=> 	1,
    		'pay_type'			=>	$this->toJdPayType($main_order->pay_type),
    		'pay_time'			=>	$main_order->order_start_time,
    		'creat_time'		=>	date("Y-m-d H:i:s", strtotime($main_order->order_start_time) - 100),
    		'update_time'		=>	date("Y-m-d H:i:s", strtotime($main_order->order_start_time) - 100),
    		'status'			=>  isset($main_order->status) ? $main_order->status : 1,
    		'source'			=>	$shop,
    		'payer'				=>  $shop,
    		'uid'				=>  '000000',
    		'count'				=>	isset($main_order->child_cot) ? $main_order->child_cot : count($main_order->item_info_list),
    		'audit_time'		=>  date("Y-m-d H:i:s", strtotime($main_order->order_start_time) - 100),
    		'province'			=>	$main_order->consignee_info->province,
    		'city'				=>	$main_order->consignee_info->city,
    		'area'				=>	$main_order->consignee_info->county,
    		'address'			=>	$main_order->consignee_info->full_address,
    		'Receiver'			=>	$main_order->consignee_info->fullname,
    		'mobile'			=>	$main_order->consignee_info->mobile,
    		'account'			=>	'000000',
    		'real_name'			=>	$main_order->consignee_info->fullname,
    	];
    	//拼接主订单
    	foreach ($main_order_data as $key => $item) {
    		$string .= '<' .FunBase::camelize($key). '>'. $item .'</'.FunBase::camelize($key).'>';
    	}
    	//拼接子订单
    	$string .= '<Order>';
        $child_cot = ( isset($main_order->child_cot) ? $main_order->child_cot : count($main_order->item_info_list) ); 
    	$string .= '<Cot>'.$child_cot.'</Cot>';
    	if(!empty($main_order->item_info_list)){
    		$i = 1;
    		foreach ($main_order->item_info_list as $key => $sku_info) {
    			$string .= '<Child>';
    			$sku = $sku_info;
    			$order_data = [
    				'receiver'				=> $shop,
    				'child_order_number' 	=> $main_order->order_id.'_'.$i,
    				'origin_order_number'	=> $main_order->order_id,
    				'order_number'			=> $main_order->order_id,
    				'description'			=> $sku->sku_name,
    				'name'					=> $sku->sku_name,
    				'product_id'			=> $sku->sku_id,
    				'product_code'			=> $sku->outer_sku_id,
    				'goods_id'				=> $sku->ware_id,
    				'total_price'			=> $main_order->order_seller_price,
    				'price'					=> $main_order->order_seller_price,
    				'number'				=> $sku->item_total,
    				'source'				=>	$shop,
    				'pay_time'				=> $main_order->order_start_time,
    				'update_time'			=> date("Y-m-d H:i:s", strtotime($main_order->order_start_time) - 100),
    				'creat_time'			=> date("Y-m-d H:i:s", strtotime($main_order->order_start_time) - 100),
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
	/**
	 * 物流对应表
	 * BB中通对应的物流是6.京东自己对应的是1499，为了以后多物流
	 * 
	 */
	private function toJdLogistics($logistics){
		switch ($logistics) {
			case 6:
				return 1499;
				break;
			default:
				return 1499;
				break;
		}
	}
	/**
	 * 京东对应的支付类型
	 * 4\5\6是考拉的支付方式
	 * 由于要走银联支付，所以所有的支付类型都是7
	 */
	private function toJdPayType($pay_type){
		return 7;
	}
	public  function  array_to_object($arr) {
        if (gettype($arr) != 'array') {
            return;
        }
        foreach ($arr as $k => $v) {
            if (gettype($v) == 'array' || getType($v) == 'object') {
                $arr[$k] = (object)$this->array_to_object($v);
            }
        }
     
        return (object)$arr;
    }
}