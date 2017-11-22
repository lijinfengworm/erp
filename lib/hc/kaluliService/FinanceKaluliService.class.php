<?php 
use Nette\Mail\SmtpMailer;

/**
 * author kworm
 * 2017-08-02
 */
class FinanceKaluliService extends kaluliService {
	
	/**
	 * 订单审核
	 */
	public function executeAudit(){
		$type = $this->getRequest()->getParameter('type');
		$main_order = $this->getRequest()->getParameter('main_order');
		$_fun = 'execute'.$type;
        if(method_exists(__CLASS__,$_fun)) {
           $this->$_fun($main_order);
        }
	}
	//推送财审订单
	public function executePushOrder(){
		$type = $this->getRequest()->getParameter('type');
		$main_order = $this->getRequest()->getParameter('main_order');
		$sign = '24F1F2331D6B9A21317FCC14FC7FF712';
        $source = 1;
        $orderNumber = $main_order["order_number"];

        if (empty($orderNumber) || empty($sign)) {
            return $this->error(400, json_encode(['status' => 0, 'msg' => "参数错误"]));
        }
        
        $orders = KaluliOrderTable::getInstance()->getOrdersByOrderNubmer($orderNumber);

        try {
            foreach ($orders as $v) {
            	if($v['ware_status'] == 0){
	            	if((int)$v['depot_type'] != 10 && (int)$v['depot_type'] != 19 && (int)$v['depot_type'] != 16 && (int)$v['depot_type'] != 17 && (int)$v['depot_type'] != 5){
	                    try {
	                        KllEdbSyncService::getInstance()->sync('order_create', array('_id' => $v['id'], 'date' => time(), 'sign' => $sign, 'source' => $source ));
	                    }catch(sfException $e) {
	                        $message = array(
	                            'message'=>'卡路里订单自动同步到E店宝失败',
	                            'param'=>array('error_msg'=>$e->getMessage(),'order_id' => $args['id'])
	                        );
	                        tradeLog::error('kaluliOrderSync',$message);
	                    }
	                }
                }
            }
        } catch (sfException $e) {
            return $this->error(400, json_encode(['status' => 0, 'msg' => $e->getMessage()]));
        }
        return $this->success();
	}
	/**
	 * 官网订单审核
	 */
	public function executeKLL($order)
	{
		try {
			$order_number = $order['order_number'];
			$bind['where']['order_number'] = 'order_number = "'.$order_number.'"';
			$list = KaluliOrderTable::getAll($bind);
			foreach ($list as $key => $ord) {
				if($ord['pay_status'] != 1){
					return $this->error(400, json_encode("订单异常"));
				}
				
				$standard_price = $this->executeGetStandardPrice($ord['goods_id'], 'KLL', $ord['depot_type']);
				
				$kll_real_price = FormatPrice::getInstance()->getOrderSubtotal($ord);

				if($kll_real_price < (float)$standard_price){
					//财审不通过的订单
					//写入财审表
					$id = $this->insertErpOrder($ord['order_number'],'KLL');
					if($id) $this->sendMail($id);
				}else{
					$mainObj = KaluliMainOrderTable::getInstance()->findOneByOrderNumber($ord['order_number']);
					$mainObj->setFinanceAudit(1)->save();
				}
			}
			return $this->success();
		} catch (Exception $e) {
			return $this->error(400,json_encode($e->getMessage()));
		}
		
	}
	//发送邮件
	public function sendMail($id){
		$msg = KaluliMail::to(array("jiangyan@kaluli.com"))->cc(array("wangyang@kaluli.com"));
        //拼接发送内容
        $html = '财务未审核订单';
        $html .= "<a href='https://erp.kaluli.com'>点击查看</a>";
        // $html .= "<a href='https://erp.kaluli.com/tradeadmin.php/kaluli_erp/terract?id=".$id.">点击查看</a>";
        $msg->content($html)->title("财务审核订单");

        $smtp = new SmtpMailer(['host'=>'smtp.exmail.qq.com','username'=>'remind@kaluli.com','password'=>'Kaluli2017','secure'=>'ssl']);
        $smtp->send($msg);
	}
	//获得官网标准价 $goods_id  是订单的goods_id
	public function executeGetStandardPrice($goods_id, $channel, $depot, $code_num=0){
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
    //插入审核表
    public function insertErpOrder($order_number, $channel){
    	try {
    		$order = KllErpOrderTable::getInstance()->findOneByOrderNumber($order_number);
	        if(empty($order)){
	        	$ord = new KllErpOrder();
	        	$ord->setOrderNumber($order_number)->setChannel($channel)->setCreateTime(time())->setUpdateTime(time());
	        	$ord->save();
	        	return $ord->getId();
	        }
	        return 0;
    	} catch (Exception $e) {
    		return 0;
    	}
    }
    
}