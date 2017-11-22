<?php
/**
 * 卡路里订单服务
 * 梁天  2015-05-26
 */
class KllOrderService  {
    //主订单
    private $mainOrder = NULL;
    //主订单attr
    private $mainOrderAttr = NULL;



    /**
     * 连接后台用户服务
     * @staticvar \Admin\Service\Cache $systemHandier
     */
    static public function getInstance() {
        static $handier = NULL;
        if (empty($handier)) {
            $handier = new self();
        }
        return $handier;
    }


    //设置主订单
    public function  setMainOrder($mainOrder) {
        if($mainOrder instanceof KaluliMainOrder) {
            $this->mainOrder = $mainOrder;
        }
    }

    //设置主订单
    public function  setMainOrderAttr($mainOrderAttr) {
        if($mainOrderAttr instanceof KaluliMainOrderAttr) {
            $this->mainOrderAttr = $mainOrderAttr;
        }
    }

    //计算除单价后的费用
    private static function getAnotherFee($item){
      $fee = 0;
      foreach ($item as $k => $val) {
        $fee += $val['express'];
        $fee += $val['duty'];
      }
      return $fee;

    }
    /*
     * 退款服务
     */
    public function cancelOrder($request) {
        /* 主订单id  */
        $order_number = $request->getParameter('order_number');
        /* 子订单id  */
        $order_id = $request->getParameter('order_id');
        /*  邮费  */
        $express_fee = $request->getParameter('express_fee');
        //总价
        $totalPrice = $request->getParameter('total_price');

        //$fee = $request->getParameter();
        
        /*  税费  */
        $dutyFee = $request->getParameter('duty_fee');
        /* 退款商品集合  */
        $item = $request->getParameter('item');


        $anotherFee = self::getAnotherFee($item);
        
        /*  礼品卡  */
        $refund_coupon = $request->getParameter('refund_coupon');

        /* 退款原因 */
        $refund_remark = $request->getParameter('refund_remark');
        $is_refund_express = $is_order_price = $is_refund_lipinka = false;
        if(empty($this->mainOrder)) throw new sfException('未知主订单！');
        
        if(empty($this->mainOrderAttr)) throw new sfException('未知主订单！');

        /* 历史退款金额 (商品 + 运费)  0.00 */
        $old_refund_price = FunBase::price_format_all($this->mainOrderAttr->getRefundPrice() + $this->mainOrderAttr->getRefundExpressFee());
        
        //退礼品卡
        if ($refund_coupon == 1){
            $is_refund_lipinka = true;
            $couponObj = KllOrderActivityDetailTable::getInstance()->createQuery()->select()->where('order_number=?', $order_number)->andWhere('type = ?', 0)->fetchOne();
            $couponObj->set('refund_type', 1);
            $couponObj->save();
            //礼品卡返回
            $serviceRequest = new kaluliServiceClient();
            $serviceRequest->setMethod('lipinka.rollback');
            $serviceRequest->setVersion('1.0');
            $serviceRequest->setApiParam('user_id', $this->mainOrder->getHupuUid());
            $attr = json_decode($couponObj->getAttr(), true);
            $serviceRequest->setApiParam('card', $attr['code']);
            $response = $serviceRequest->execute();
        }
        //最新退款（临时的）
        if(!empty($totalPrice)) {
            $is_refund_express = true;

            $express_fee_tmp = $anotherFee;
            //判断是否超过退款总额
            $_tmp_refund_1 = FunBase::price_format_all($old_refund_price + $totalPrice);
            if($_tmp_refund_1 > $this->mainOrder->getTotalPrice()) {
                //如果超过  计算最终 运费退款价格
                $_now_express_fee = $express_fee_tmp - $this->mainOrder->getCouponFee();
                $totalPrice = $this->mainOrder->getTotalPrice()-$old_refund_price;
            } else {
                $_now_express_fee = $express_fee_tmp;
            }
            //主订单退运费  写入
            $this->mainOrderAttr->setRefundExpressFee($express_fee_tmp);
            $this->mainOrderAttr->save();

            //主订单退运费  写入
            $this->mainOrder->setRefund($totalPrice);
            $this->mainOrder->save();

            //更新子订单的退款额
           /*foreach($item as $k=>$v) {
               $_orderAttr = KaluliOrderAttrTable::getInstance()->findOneByOrderId($v['id']);
               $_orderAttr->setRefund(FunBase::price_format_all($_orderAttr->getRefund() + $express_fee));  //退款额
               $_orderAttr->save();
           }*/

            //写日志
            $orderHistory = new KaluliOrderHistory();
            $orderHistory->setOrderNumber($this->mainOrder->getOrderNumber());
            $orderHistory->setHupuUid($this->mainOrder->getHupuUid());
            $orderHistory->setHupuUsername($this->mainOrder->getHupuUsername());
            $orderHistory->setType(KaluliOrderHistory::$ORDER_CANCEL);
            $orderHistory->setExplanation("后台取消订单，退运费和税费的值 ￥".$express_fee.' 实际计算退邮费 ￥'.$express_fee_tmp.' 订单号='.$this->mainOrder->getOrderNumber() );
            //$orderHistory->setExplanation("后台取消订单，退运费 ￥".$express_fee.' 实际计算退邮费 ￥'.$_now_express_fee.' 订单号='.$this->mainOrder->getOrderNumber() );
            $orderHistory->setGrantUid(sfContext::getInstance()->getUser()->getTrdUserHuPuId());
            $orderHistory->setGrantUsername( sfContext::getInstance()->getUser()->getTrdUsername());
            $orderHistory->save();
            //这个就不需要写入退款里了
            /*$refundDetail = new KaluliRefundDetail();
            $refundDetail->setOrderNumber($this->mainOrder->getOrderNumber());
            $refundDetail->setIbillingNumber($this->mainOrder->getIbillingNumber());
            $refundDetail->setRefund($totalPrice);
            $refundDetail->setPayType($this->mainOrder->getPayType());
            $refundDetail->setRefundRemark($refund_remark);
            $refundDetail->setType(KaluliRefundDetail::$ORDER_REFUND_TYPE);
            if($_now_express_fee <= 0 ) {
                $refundDetail->setStatus(KaluliRefundDetail::$SUCC_REFUND_STATUS);
            } else {
                $refundDetail->setStatus(KaluliRefundDetail::$WAIT_REFUND_STATUS);
            }
            $refundDetail->setGrantUid(sfContext::getInstance()->getUser()->getTrdUserHuPuId());
            $refundDetail->setGrantUsername( sfContext::getInstance()->getUser()->getTrdUsername());
            $refundDetail->save();*/
            $now_refund_price += $express_fee;  //当前退款总金额加
            $old_refund_price += $express_fee;  //当前退款总金额加
        }
        //大于付款
        /* 当次退款金额 (商品 + 运费)*/
        $now_refund_price = 0;
        /* 判断是否退运费  */
        if(!empty($express_fee)) {

            $is_refund_express = true;

            //判断是否超过退款总额
            $_tmp_refund_1 = FunBase::price_format_all($old_refund_price + $express_fee);
            if($_tmp_refund_1 > $this->mainOrder->getTotalPrice()) {
                //如果超过  计算最终 运费退款价格
                $_now_express_fee = $express_fee - $this->mainOrder->getCouponFee();
            } else {
                $_now_express_fee = $express_fee;
            }
            //主订单退运费  写入
            $this->mainOrderAttr->setRefundExpressFee($express_fee);
            $this->mainOrderAttr->save();

            //主订单退运费  写入
            $this->mainOrder->setRefund($this->mainOrder->getRefund() + $express_fee);
            $this->mainOrder->save();

            //更新子订单的退款额
           /*foreach($item as $k=>$v) {
               $_orderAttr = KaluliOrderAttrTable::getInstance()->findOneByOrderId($v['id']);
               $_orderAttr->setRefund(FunBase::price_format_all($_orderAttr->getRefund() + $express_fee));  //退款额
               $_orderAttr->save();
           }*/

            //写日志
            $orderHistory = new KaluliOrderHistory();
            $orderHistory->setOrderNumber($this->mainOrder->getOrderNumber());
            $orderHistory->setHupuUid($this->mainOrder->getHupuUid());
            $orderHistory->setHupuUsername($this->mainOrder->getHupuUsername());
            $orderHistory->setType(KaluliOrderHistory::$ORDER_CANCEL);
            $orderHistory->setExplanation("后台取消订单，退运费 ￥".$express_fee.' 实际计算退邮费 ￥'.$_now_express_fee.' 订单号='.$this->mainOrder->getOrderNumber() );
            $orderHistory->setGrantUid(sfContext::getInstance()->getUser()->getTrdUserHuPuId());
            $orderHistory->setGrantUsername( sfContext::getInstance()->getUser()->getTrdUsername());
            $orderHistory->save();
            //写入退款表
            $refundDetail = new KaluliRefundDetail();
            $refundDetail->setOrderNumber($this->mainOrder->getOrderNumber());
            $refundDetail->setIbillingNumber($this->mainOrder->getIbillingNumber());
            $refundDetail->setRefund($_now_express_fee);
            $refundDetail->setPayType($this->mainOrder->getPayType());
            $refundDetail->setRefundRemark($refund_remark);
            $refundDetail->setType(KaluliRefundDetail::$ORDER_REFUND_TYPE);
            if($_now_express_fee <= 0 ) {
                $refundDetail->setStatus(KaluliRefundDetail::$SUCC_REFUND_STATUS);
            } else {
                $refundDetail->setStatus(KaluliRefundDetail::$WAIT_REFUND_STATUS);
            }
            $refundDetail->setGrantUid(sfContext::getInstance()->getUser()->getTrdUserHuPuId());
            $refundDetail->setGrantUsername( sfContext::getInstance()->getUser()->getTrdUsername());
            $refundDetail->save();
            $now_refund_price += $express_fee;  //当前退款总金额加
            $old_refund_price += $express_fee;  //当前退款总金额加
        }


        /* 判断是否退商品 */
        $_mainPrice = 0;
        foreach($item as $k=>$v) {
            $_tmp_another_fee = 0;
            $_tmp_another_fee += $v['express'];
            $_tmp_another_fee += $v['duty'];
            // if(!isset($v['price']) || !isset($v['number']) || empty($v['number'])) continue;
            $is_order_price = true;

            $_order = KaluliOrderTable::getInstance()->find($v['id']);

            $_now_price = $_order->getTotalPrice() - $_order->getExpressFee() - $_order->getMarketingFee();
            //计算真实退款商品价格  历史退款记录 加上 这次商品退款
            $_tmp_refund_2 = FunBase::price_format_all($this->mainOrder->getRefund() + $_now_price );

            //如果超过  计算最终 运费退款价格
            if($_tmp_refund_2 > $this->mainOrder->getTotalPrice()) {
                $_new_now_price = $_now_price - $this->mainOrder->getCouponFee();
            } else {
                $_new_now_price = $_now_price;
            }

            //主订单  写入
            $this->mainOrderAttr->setRefundPrice($this->mainOrderAttr->getRefundPrice() + $_new_now_price);
            $this->mainOrderAttr->save();

            //主订单退运费  写入
            $this->mainOrder->setRefund($this->mainOrder->getRefund() + $_new_now_price);
            $this->mainOrder->save();

            //修改当前订单为退款

            $_order->setStatus(KaluliOrder::$BACKEND_CANCEL_STATUS);
            $_order->setPayStatus(KaluliOrder::$WAIT_REFUND_PAY_STATUS);
            $_order->save();
            //修改订单附表
            $_orderAttr = KaluliOrderAttrTable::getInstance()->findOneByOrderId($v['id']);
            $_orderAttr->setRefundPrice($_new_now_price);  //退款商品额
            $_orderAttr->setRefund(FunBase::price_format_all($_orderAttr->getRefund() + $_new_now_price + $_tmp_another_fee));  //退款额

            $_orderAttr->save();
            //写入日志
            $orderHistory = new KaluliOrderHistory();
            $orderHistory->setOrderNumber($this->mainOrder->getOrderNumber());
            $orderHistory->setHupuUid($this->mainOrder->getHupuUid());
            $orderHistory->setHupuUsername($this->mainOrder->getHupuUsername());
            $orderHistory->setType(KaluliOrderHistory::$ORDER_CANCEL);
            $orderHistory->setExplanation("后台取消订单，   单个商品费用 ￥".$v['price'].'商品数量'.$v['number'].'  总计 '.FunBase::price_format_all($v['price'] * $v['number']).'  实际退款 ￥'.$_new_now_price.' 订单号='.$v['id'] );
            $orderHistory->setGrantUid(sfContext::getInstance()->getUser()->getTrdUserHuPuId());
            $orderHistory->setGrantUsername( sfContext::getInstance()->getUser()->getTrdUsername());
            $orderHistory->save();


            //写入退款表
            $refundDetail = new KaluliRefundDetail();
            $refundDetail->setOrderNumber($this->mainOrder->getOrderNumber());
            $refundDetail->setOrderId($_order->getId());
            $refundDetail->setPayType($this->mainOrder->getPayType());
            $refundDetail->setIbillingNumber($this->mainOrder->getIbillingNumber());
            $refundDetail->setRefund($totalPrice);
            // $refundDetail->setRefund($_new_now_price);

            $refundDetail->setRefundRemark($refund_remark);
            $refundDetail->setType(KaluliRefundDetail::$ORDER_REFUND_TYPE);

            if($_new_now_price <= 0 ) {
                $refundDetail->setStatus(KaluliRefundDetail::$SUCC_REFUND_STATUS);
            } else {
                $refundDetail->setStatus(KaluliRefundDetail::$WAIT_REFUND_STATUS);
            }

            $refundDetail->setGrantUid(sfContext::getInstance()->getUser()->getTrdUserHuPuId());
            $refundDetail->setGrantUsername( sfContext::getInstance()->getUser()->getTrdUsername());
            $refundDetail->save();

            //如果当前商品退款总额小于等于0 改商品状态
            if($_new_now_price <= 0 ) {
                $_order->setPayStatus(KaluliOrder::$SUCC_REFUND_PAY_STATUS);
                $_order->save();
            }

            //库存变更
            $serviceRequest = new kaluliServiceClient();
            $serviceRequest->setMethod('item.skuStock');
            $serviceRequest->setVersion('1.0');
            $serviceRequest->setApiParam('id',$_order->getGoodsId());
            $serviceRequest->setApiParam('num',$_order->getNumber());
            $serviceRequest->setApiParam('type',3);//取消订单
            $serviceRequest->execute();
        }
        if(!$is_order_price && !$is_refund_express && !$is_refund_lipinka) throw new sfException('您没有修改任何数据。');
        return true;
    }


    //取消取消退款   （没错 有2个取消）
    public function cancelRefund($id)
    {
        $refundData = KaluliRefundDetailTable::getInstance()->find($id);
        if (empty($refundData)) throw new sfException('未知退款记录！');

        /*  修改退款记录表状态为 取消退款  */
        $refundData->setStatus(2);
        $refundData->save();

        /* 判断是否运费退款 */
        $_order_id = $refundData->getOrderId();
        /* 主订单  */
        $_mainOrder = KaluliMainOrderTable::getInstance()->findOneByOrderNumber($refundData->getOrderNumber());
        if (empty($_order_id)) {  //如果没有ORDERID表示退运费
            //更新 订单主表 attr
            $_mainOrderAttr = KaluliMainOrderAttrTable::getInstance()->findOneByOrderNumber($refundData->getOrderNumber());
            $_mainOrderAttr->setRefundExpressFee(NULL);
            $_mainOrderAttr->save();
            //更新主表
            $_mainOrder->setRefund($_mainOrder->getRefund() - $_mainOrder->getExpressFee());
            $_mainOrder->save();
            //写日志
            $orderHistory = new KaluliOrderHistory();
            $orderHistory->setOrderNumber($_mainOrder->getOrderNumber());
            $orderHistory->setHupuUid($_mainOrder->getHupuUid());
            $orderHistory->setHupuUsername($_mainOrder->getHupuUsername());
            $orderHistory->setType(KaluliOrderHistory::$ORDER_PAYMENT);
            $orderHistory->setExplanation("后台取消退款，取消退运费 。 订单号=".$_mainOrder->getOrderNumber() );
            $orderHistory->setGrantUid(sfContext::getInstance()->getUser()->getTrdUserHuPuId());
            $orderHistory->setGrantUsername( sfContext::getInstance()->getUser()->getTrdUsername());
            $orderHistory->save();
        } else {
            //退商品 更新子订单
            $_orderData = KaluliOrderTable::getInstance()->find($_order_id);

            if($_orderData->getStatus() == KaluliOrder::$RETURN_GOODS_STATUS) {
                $_orderData->setStatus(KaluliOrder::$WAIT_KALULI_GOODS_STATUS);
                $_orderData->setPayStatus(KaluliOrder::$SUCCESS_PAY_STATUS);
            } else {
                $_orderData->setStatus(KaluliOrder::$CREATE_STATUS);
                $_orderData->setPayStatus(KaluliOrder::$SUCCESS_PAY_STATUS);
            }

            $_orderData->save();
            //修改订单附表
            $_orderAttr = KaluliOrderAttrTable::getInstance()->findOneByOrderId($_order_id);
            //先保存一份
            $_refund = $_orderAttr->getRefund();
            $_orderAttr->setRefundPrice(NULL);
            $_orderAttr->setRefund(NULL);
            $_orderAttr->save();
            //更新主表
            $_mainOrder->setRefund($_mainOrder->getRefund() - $_refund);
            $_mainOrder->save();
            /*  更新主订单附表  */
            $_orderMainAttr = KaluliMainOrderAttrTable::getInstance()->findOneByOrderNumber($_mainOrder->getOrderNumber());
            $_orderMainAttr->setRefundPrice($_orderMainAttr->getRefundPrice() - $_refund);
            $_orderMainAttr->save();

            //写日志
            $orderHistory = new KaluliOrderHistory();
            $orderHistory->setOrderNumber($_mainOrder->getOrderNumber());
            $orderHistory->setHupuUid($_mainOrder->getHupuUid());
            $orderHistory->setHupuUsername($_mainOrder->getHupuUsername());
            $orderHistory->setType(KaluliOrderHistory::$ORDER_PAYMENT);
            $orderHistory->setExplanation("后台取消退款，取消退商品订单号=".$_orderData->getId() );
            $orderHistory->setGrantUid(sfContext::getInstance()->getUser()->getTrdUserHuPuId());
            $orderHistory->setGrantUsername( sfContext::getInstance()->getUser()->getTrdUsername());
            $orderHistory->save();
        }

        //修改库存
        if (!empty($_order_id)) {
            $serviceRequest = new kaluliServiceClient();
            $serviceRequest->setMethod('item.skuStock');
            $serviceRequest->setVersion('1.0');
            $serviceRequest->setApiParam('id', $_orderData->getGoodsId());
            $serviceRequest->setApiParam('num', (int)FunBase::price_format_all($_refund / $_orderData->getPrice()));
            $serviceRequest->setApiParam('type', 5);//取消订单
            $serviceRequest->execute();
        }
        return true;
    }






    /**
     * 静态方法 处理订单
     */
    public static function saveImportOrder($line,$_i = 0) {
        $order_numbers = array();
        //判断订单是否合并订单
        if(strpos($line['order_number'],';') !== false) {
            $order_numbers = explode(';',$line['order_number']);
        } else {
            $order_numbers[] = $line['order_number'];
        }
        if(count($order_numbers) > 0) {
            foreach($order_numbers as $k=>$v) {
                $order_ids = explode('-',$v);
                if(empty($order_ids[1])) {
                    $_order_number = !empty($line['erp_order']) ? $line['erp_order'] : '';
                    print('<span style="text-align:center;color:red;">'.$_i.'&nbsp;&nbsp;订单号：'.$_order_number.' 格式错误，未导入成功！</span><br />');
                    continue;
                }
                $_orderData = KaluliOrderTable::getInstance()->find($order_ids[1]);
                if(empty($_orderData)){
                    print('<span style="text-align:center;color:#777;">'.$_i.'&nbsp;&nbsp;订单号：'.$v.' 不存在系统中，跳过！</span><br />');
                    continue;
                }
                //判断订单状态是不是未发货 否则不做修改
                if($_orderData->getStatus() != KaluliOrder::$CREATE_STATUS) {
                    print('<span style="text-align:center;color:#fff;">'.$_i.'&nbsp;&nbsp;订单号：'.$v.' 不是未发货状态，跳过！</span><br />');
                    continue;
                }
                $mainOrderAttr = KaluliMainOrderAttrTable::getOne($_orderData->getOrderNumber(), true);
                $_orderData->setDomesticExpressType($line['express_cpmpany_type']);
                $_orderData->setDomesticOrderNumber($line['express_number']);
                $_orderData->setDomesticExpressTime(strtotime($line['set_time']));
                //0订单生成 1已发货 2订单完成 3退货处理中 4待用户发货 5待卡路里收货 6已退货 7订单关闭 8用户取消 9识货取消 10拒绝退货
                $_orderData->setStatus(KaluliOrder::$DELIVERY_STATUS);
                $_orderData->setWareStatus(KaluliOrderWarelog::$SYNC_STATUS_EXPRESS);
                $_orderData->save();
                //写入到订单日志里面
                //写日志
                $orderHistory = new KaluliOrderHistory();
                $orderHistory->setOrderNumber($_orderData->getOrderNumber());
                $orderHistory->setHupuUid($_orderData->getHupuUid());
                $orderHistory->setHupuUsername($_orderData->getHupuUsername());
                $orderHistory->setType(KaluliOrder::$DELIVERY_STATUS);
                $orderHistory->setExplanation($_orderData->getId()." 订到已发货， 快递类型：".KaluliOrder::$EXPRESS_TYPE[$line['express_cpmpany_type']].' 快递单号：'.$line['express_number'].'  订单号='.$_orderData->getOrderNumber() );
                $orderHistory->setGrantUid(sfContext::getInstance()->getUser()->getTrdUserHuPuId());
                $orderHistory->setGrantUsername( sfContext::getInstance()->getUser()->getTrdUsername());
                $orderHistory->save();
                //发短信
                $kllMessage = new kllSendMessage();
                $kllMessage->send(array (
                    'phone' => $mainOrderAttr['address_attr']['mobile'],
                    'var' => array('express_name'=>KaluliOrder::$EXPRESS_TYPE[$line['express_cpmpany_type']],'express_number'=>strval($line['express_number'])),
                    'tpl_id' => kllSendMessage::$_ORDER_EXPRESS,
                    'user_id'=> $_orderData->getHupuUid()
                ));
                echo '<span style="text-align:center;color:green;">'.$_i.'&nbsp;&nbsp;订单号：'.$v.' 导入成功！</span><br />';
            }
        }
    }











}