<?php
use PhpAmqpLib\Connection\AMQPConnection;
class tradeAmqpHaiTaoOrderTask extends sfBaseTask
{
    public $killtag =  true;

    //分类信息
    static $_cate_info = array(
        '1' => array('name'=>'运动户外', 'rate'=>'0.03'),
        '2' => array('name'=>'服装服饰', 'rate'=>'0.05'),
        '3' => array('name'=>'电脑数码', 'rate'=>'0.01'),
        '4' => array('name'=>'家居生活', 'rate'=>'0.04'),
        '5' => array('name'=>'食品保健', 'rate'=>'0.04'),
        '7' => array('name'=>'其他分类', 'rate'=>'0.03'),
        '49' => array('name'=>'鞋靴', 'rate'=>'0.03'),
        '50' => array('name'=>'箱包手袋', 'rate'=>'0.03'),
        '51' => array('name'=>'饰品手表', 'rate'=>'0.01'),
        '52' => array('name'=>'办公设备', 'rate'=>'0.04'),
        '53' => array('name'=>'图书音像', 'rate'=>'0.03'),
    );

    protected function configure()
    {
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'trade'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'trade'),
        ));

        $this->namespace        = 'trade';
        $this->name             = 'AmqpHaiTaoOrder';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [trade:AmqpHaiTaoOrder|INFO] task does things.
Call it with:

  [php symfony trade:AmqpHaiTaoOrder|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array()){
        sfContext::createInstance($this->configuration);

        ini_set('memory_limit','128M');

        $amqpParams = sfConfig::get("app_mabbitmq_options_shihuo");
        $connection = new AMQPConnection($amqpParams['params']['host'], $amqpParams['params']['port'],$amqpParams['params']['user'], $amqpParams['params']['pass'], $amqpParams['params']['vhost']);

        $channel = $connection->channel();
        $channel->queue_declare('HaitaoMessage', false, true, false, false, false);
        $channel->queue_bind('HaitaoMessage', "amq.topic","order.*");
        $channel->basic_consume('HaitaoMessage', '', false, false, false, false, 'tradeAmqpHaiTaoOrderTask::callback');

        while(count($channel->callbacks) ) {
            $nowmem = memory_get_usage()/1024/1024;
            if($nowmem <60){
                $channel->wait();
            }else{
                break;
            }
        }
    }

    public static function callback($msg)
    {
        $msgBody = json_decode($msg->body, true);

        $order_number = $msgBody['order_number'];
        $type = $msgBody['type'];

        echo 'order_number: ' . $order_number . ';type: ' . $type . PHP_EOL . PHP_EOL;
        switch ($type) {
            case 'refund'://退款成功发消息
                $refund = $msgBody['refund'];
                self::_deliveryOrderRefund($order_number, $refund, '', $msg);
                break;
            case 'cancelPay'://后台取消订单
                $refund = $msgBody['refund'];
                $remark = $msgBody['remark'];
                self::_deliveryOrderRefund($order_number, $refund, $remark, $msg);
                break;
            case 'create'://订单创建
                $cookie = isset($msgBody['cookie']) && !empty($msgBody['cookie']) ? $msgBody['cookie'] : '';
                self::_deliveryOrderCreate($order_number, $cookie, $msg);
                break;
            case 'paySuccess'://订单支付成功
                self::_deliveryOrderSendMsg($order_number, $msg); // 发送短信通知
                self::_deliveryOrderUpdate($order_number, '', $type, $msg);
                break;
            case 'cancel'://取消订单
                self::_deliveryOrderUpdate($order_number, '', $type, $msg);
                break;
            case 'delivery'://发货 订单结算
                $sub_order_number = $msgBody['sub_order_number'];
                self::_deliveryOrderUpdate($order_number, $sub_order_number, $type, $msg);
                break;
            case 'martOrder'://在商家下单或者退款额变化 同步到商家信息表
                $mart_order_number = $msgBody['mart_order_number'];
                self::_martOrderUpdate($order_number, $mart_order_number, $type, $msg);
                break;
            default:
                break;
        }
    }

    //发送消息
    public static function _deliveryOrderRefund($order_number, $refund, $remark = '', $msg)
    {
        $mainOrder = TrdMainOrderTable::getInstance()->findOneByOrderNumber($order_number);
        if ($mainOrder) {
            if ($mainOrder->getAddressAttr()) {
                $addr = json_decode($mainOrder->getAddressAttr(), true);
                $message = new tradeSendMessage();
                $sm = "亲爱的识货用户，您的海淘代购订单" . $order_number . "，已经退款成功，金额为：".$refund.'。请注意查收！';
                if ($remark) {
                    $sm = "亲爱的识货用户，您的海淘代购订单" . $order_number . "由于\"" . $remark . "\"原因无法下单，识货已帮您执行退款操作，退款金额：" . $refund . "元。预计7个工作日内到账！";
                }
                $message->send($addr['mobile'], $sm);
            }
        }
        $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);//清除消息
    }

    //订单支付成功发送短信
    public static function _deliveryOrderSendMsg($order_number, $msg)
    {
        $order = TrdOrderTable::getInstance()->findOneByOrderNumber($order_number);
        if ($order) {
            if ($mobile = $order->getMobile()) {
                $mainOrder = TrdMainOrderTable::getInstance()->findOneByOrderNumber($order_number);
                if ($mainOrder) {
                    $number = $mainOrder->getNumber();
                    $title = $order->getTitle();
                    $message = new tradeSendMessage();
                    $sm = '您订购的 ' . $title . ' ，数量: ' . $number . '，订单已支付成功,请记住您的订单号 ' . $order_number . ' ，可通过手机登陆 http://m.shihuo.cn/，查看您的订单信息';
                    $message->send($mobile, $sm);
                }
            }
        }
    }

    //订单创建
    public static function _deliveryOrderCreate($order_number, $cookie, $msg)
    {
        if ($cookie && $order_number) {

            $cpsClickUserTable = CpsClickUserTable::getInstance();
            $trdMainOrderTable = TrdMainOrderTable::getInstance();
            $trdOrderTable = TrdOrderTable::getInstance();
            $trdProductAttrTable = TrdProductAttrTable::getInstance();
            $cpsOrderTable = CpsOrderTable::getInstance();
            $cpsUserTable = CpsUserTable::getInstance();
            //获取推广用户详情
            $cpsUser = $cpsClickUserTable->findOneByCookie($cookie);
            if (!$cpsUser) {
                $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);//清除消息
                return ;
            }
            //获取推广用户基础数据
            $cpsBaseUser = $cpsUserTable->findOneByUnionId($cpsUser->getUnionId());
            if ($cpsUser) {
                $cateInfo = self::$_cate_info;
                //获取订单详情
                $mainOrder = $trdMainOrderTable->findOneByOrderNumber($order_number);
                $subOrder = $trdOrderTable->createQuery()->where('order_number = ?', $order_number)->execute();
                if ($mainOrder && (!$cpsUser->getHupuUid() || $cpsUser->getHupuUid() == $mainOrder->getHupuUid())) {
                    if ($mainOrder->getCouponFee() > 0) $total_product_price = $mainOrder->getOriginalPrice() - $mainOrder->getExpressFee() - $mainOrder->getMarketingFee();
                    foreach ($subOrder as $k => $v) {//循环保存订单到cps表
                        $valid = 0;
                        //if ($v->getMarketingFee() > 0) continue;//活动商品不参与推广
                        if ($v->getMarketingFee() > 0) $valid = 1;//活动商品不参与推广
                        $cpsOrder = null;
                        $cpsOrder = $cpsOrderTable->createQuery()->where('order_number = ?', $order_number)->andWhere('sub_order_number = ?', $v->getId())->fetchOne();
                        if (!$cpsOrder) {
                            $cpsOrder = new CpsOrder();
                        }
                        $cpsOrder->set('order_number', $order_number);
                        $cpsOrder->set('sub_order_number', $v->getId());
                        $cpsOrder->set('order_time', strtotime($v->getOrderTime()));
                        $cpsOrder->set('click_time', $cpsUser->getClickTime());
                        $cpsOrder->set('orders_price', $v->getPrice());

                        $cpsOrder->set('channel', $v->getSource() == 2 ? 0 : $v->getSource());
                        $cpsOrder->set('goods_id', $v->getGid());
                        $cpsOrder->set('title', $v->getTitle());
                        $cpsOrder->set('goods_price', $v->getPrice());
                        $product = $trdProductAttrTable->find($v->getProductId());
                        $cpsOrder->set('goods_ta', 1);
                        $cpsOrder->set('goods_cate', $product ? $product->getRootId() : 0);
                        $cpsOrder->set('goods_cate_name', $product ? $cateInfo[$product->getRootId()]['name'] : '');
                        $cpsOrder->set('rate', $product ? $cateInfo[$product->getRootId()]['rate'] : '');
                        $coupon_fee = 0;
                        if ($mainOrder->getCouponFee() > 0) {
                            $per_coupon = ceil($mainOrder->getCouponFee() * 1000000/ $total_product_price ) / 1000000;
                            $coupon_fee = ceil($per_coupon * ($v->getPrice() - $v->getMarketingFee() ) * 100 ) / 100;
                        }
                        $totalprice = $v->getPrice() - $coupon_fee;
                        $discount_amount = $coupon_fee + $v->getMarketingFee();
                        $cpsOrder->set('total_price', $totalprice);
                        //计算佣金
                        if ((!$cpsBaseUser || ($cpsBaseUser && $cpsBaseUser->getType() == 1))){
                            if ($totalprice <= 100) {
                                $commission = 1;
                            } elseif ($totalprice > 100 && $totalprice <= 300) {
                                $commission = 4;
                            } elseif ($totalprice > 300 && $totalprice <= 500) {
                                $commission = 10;
                            } elseif ($totalprice > 500 && $totalprice <= 800) {
                                $commission = 18;
                            } else {
                                $commission = 25;
                            }
                        } else if($cpsBaseUser && $cpsBaseUser->getType() == 2) {
                            if ($totalprice <= 100) {
                                $commission = 3;
                            } elseif ($totalprice > 100 && $totalprice <= 300) {
                                $commission = 10;
                            } elseif ($totalprice > 300 && $totalprice <= 500) {
                                $commission = 30;
                            } elseif ($totalprice > 500 && $totalprice <= 800) {
                                $commission = 50;
                            } else {
                                $commission = 60;
                            }
                        }

                        $cpsOrder->set('commission', $commission);
                        $cpsOrder->set('discount_amount', $discount_amount);
                        $cpsOrder->set('union_id', $cpsUser->getUnionId());
                        $cpsOrder->set('mid', $cpsUser->getMid());
                        $cpsOrder->set('euid', $cpsUser->getEuid());
                        $cpsOrder->set('referer', $cpsUser->getReferer());
                        $cpsOrder->set('hupu_uid', $v->getHupuUid());
                        //$cpsOrder->set('test', 1);
                        $cpsOrder->set('valid', $valid);
                        $cpsOrder->save();

                        if (!$cpsUser->getHupuUid()) {
                            $cpsUser->setHupuUid($mainOrder->getHupuUid());
                            $cpsUser->save();
                        }

                        if ($cpsUser->getUnionId() == 'duomai' && $valid == 0){
                            //推送
                            $push = self::pushAllianceDuoMai($cpsOrder);
                            if (!$push) {
                                $msg->delivery_info['channel']->basic_nack($msg->delivery_info['delivery_tag']);
                                $message = array('order_number'=>$order_number, 'cookie'=>$cookie, 'type'=>'create');
                                tradeCommon::sendMqMessage('order.detail',$message,'order_detail_deferred1',30000);
                                //循环关闭链接
//                                foreach (Doctrine_Manager::getInstance()->getConnections() as $con ){
//                                    $con->close();
//                                }
                                return ;
                            }
                        }
                    }
                }
            }

            //循环关闭链接
//            foreach (Doctrine_Manager::getInstance()->getConnections() as $con ){
//                $con->close();
//            }
        }
        $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);//清除消息
    }

    //订单更新
    public static function _deliveryOrderUpdate($order_number, $sub_order_number = '', $type, $msg)
    {
        if ($type && $order_number) {

            $cpsOrderTable = CpsOrderTable::getInstance();
            $cpsOrderObj = $cpsOrderTable->createQuery()->where('order_number = ?', $order_number);
            if ($sub_order_number) $cpsOrderObj->andWhere('sub_order_number = ?', $sub_order_number);
            $cpsOrder = $cpsOrderObj->execute();
                if (count($cpsOrder) > 0) {
                    foreach ($cpsOrder as $k => $v) {
                        if ($type == 'paySuccess') {
                            $v->setStatus(1);
                        } elseif ($type == 'cancel') {
                            $v->setStatus(4);
                        } elseif ($type == 'delivery') {
                            $v->setStatus(3);//已发货 有效
                        }
                        $v->save();

                        if ($v->getUnionId() == 'duomai'){
                            //推送
                            $push = self::pushAllianceDuoMai($v);
                            if (!$push) {
                                $msg->delivery_info['channel']->basic_nack($msg->delivery_info['delivery_tag']);
                                $message = array('order_number'=>$order_number,  'type'=>$type);
                                tradeCommon::sendMqMessage('order.detail',$message,'order_detail_deferred1',30000);
                                //循环关闭链接
//                                foreach (Doctrine_Manager::getInstance()->getConnections() as $con ){
//                                    $con->close();
//                                }
                                return ;
                            }
                        }
                    }
                }

            //循环关闭链接
//            foreach (Doctrine_Manager::getInstance()->getConnections() as $con ){
//                $con->close();
//            }
        }
        $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);//清除消息
    }

    //推送给多麦
    private static function pushAllianceDuoMai($cpsOrder){
        if (!$cpsOrder) return false;
        $push_url = "http://www.duomai.com/api/push/shihuo.php?hash=ff0f87f6901fd54680fac01b1a8df4e8&";
        if ($cpsOrder->get('status') == 0) {
            $status = 0;
        } elseif ($cpsOrder->get('status') == 1) {
            $status = '已支付';
        } elseif ($cpsOrder->get('status') == 2) {
            $status = '已发货';
        } elseif ($cpsOrder->get('status') == 3) {
            $status = '有效';
        } elseif ($cpsOrder->get('status') == 4) {
            $status = -1;
        }
        $param = array(
            'euid'=>$cpsOrder->get('euid'),
            'order_sn'=>$cpsOrder->get('order_number'),
            'suborder_sn'=>$cpsOrder->get('sub_order_number'),
            'order_time'=>date('Y-m-d H:i:s', $cpsOrder->get('order_time')),
            'click_time'=>date('Y-m-d H:i:s', $cpsOrder->get('click_time')),
            'orders_price'=>$cpsOrder->get('orders_price'),
            'discount_amount'=>$cpsOrder->get('discount_amount'),
            'is_new_custom'=>$cpsOrder->get('discount_amount'),
            'channel'=>$cpsOrder->get('discount_amount'),
            'order_status'=>$status,
            'referer'=>$cpsOrder->get('referer'),
            'goods_id'=>$cpsOrder->get('goods_id'),
            'goods_name'=>$cpsOrder->get('title'),
            'goods_price'=>$cpsOrder->get('goods_price'),
            'goods_ta'=>$cpsOrder->get('goods_ta'),
            'goods_cate'=>$cpsOrder->get('goods_cate'),
            'goods_cate_name'=>$cpsOrder->get('goods_cate_name'),
            'totalPrice'=>$cpsOrder->get('total_price'),
            'commission'=>$cpsOrder->get('commission'),
            //'test'=>1,
        );
        $res = tradeCommon::getContents($push_url.http_build_query($param));
        if ($res == 'success'){
            return true;
        }
        return false;
    }

    //在商家下单或者退款额变化 同步到商家信息表
    public static function _martOrderUpdate($order_number, $mart_order_number = '', $type, $msg)
    {
        if ($order_number && $mart_order_number) {

            $orderInfo = TrdOrderTable::getInstance()->createQuery()->select('order_number,order_time,business,business_account,sum(express_fee) as fee ,sum(price) as price,sum(marketing_fee) as marketing_fee,sum(refund_price) refund_price,sum(refund_express_fee) refund_express_fee')
                ->andWhere('mart_order_number = ?', $mart_order_number)
                ->groupBy('mart_order_number')
                ->fetchArray();
            if (count($orderInfo) > 0){
                $mainOrderInfo = TrdMainOrderTable::getInstance()->createQuery()->select('coupon_fee')
                    ->andWhere('order_number = ?', $order_number)
                    ->fetchArray();

                $mart_order = TrdMartOrderInfoTable::getInstance()->createQuery()->select()->where('mart_order_id = ?',$mart_order_number)->andWhere('business = ?',$orderInfo[0]['business'])->fetchOne();
                if(!$mart_order)
                {
                    $mart_order = new TrdMartOrderInfo();
                }
                $refund_express_fee = $orderInfo[0]['refund_express_fee'] ? $orderInfo[0]['refund_express_fee'] : 0;
                $refund_price = $orderInfo[0]['refund_price'] ? $orderInfo[0]['refund_price'] : 0;
                $mart_order->setShOrderId($order_number);
                $mart_order->setMartOrderId($mart_order_number);
                $mart_order->setShOrderTime($orderInfo[0]['order_time']);
                $mart_order->setShPrice($orderInfo[0]['price']);
                $mart_order->setAccount($orderInfo[0]['business_account']);
                $mart_order->setBusiness($orderInfo[0]['business']);
                $mart_order->setShShippingPrice($orderInfo[0]['fee']);
                $mart_order->setShMarketingFee($orderInfo[0]['marketing_fee']);
                $mart_order->setShRefundExpressFee($refund_express_fee);
                $mart_order->setShRefundPrice($refund_price);
                $mart_order->setShCouponFee($mainOrderInfo[0]['coupon_fee']);
                $mart_order->save();
            }

            //循环关闭链接
//            foreach (Doctrine_Manager::getInstance()->getConnections() as $con ){
//                $con->close();
//            }
        }
        $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);//清除消息
    }
}
