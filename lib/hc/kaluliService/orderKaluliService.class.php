<?php

/**
 * Class orderKaluliService
 * version: 1.0
 */
class orderKaluliService extends kaluliService {

    //支付类型
    private $pay_type = array(
        1=>'alipay',
        2=>'weixinpay',
        3=>'alipayApp',
        'default'=>1
    );

    private static $duty = 0.4;

    /**
     * 我的订单数量
     * @param string type 订单类型（all：全部  pendpay：待付款  pendsend：待发货  pendreceipt：待收货  pendcomment：待评价）
     */
    public function executeMyorderCount()
    {
        $v = $this->getRequest()->getParameter('v');
        $type = $this->getRequest()->getParameter('type', 'all');

        $hupuUid = $this->getRequest()->getParameter('hupu_uid');
        if(empty($hupuUid))
            $hupuUid = $this->getUser()->getAttribute('uid');

        $hupuUname = $this->getUser()->getAttribute('username');
        if (empty($hupuUid) || !is_numeric($hupuUid)) {
            return $this->error(501, '未登录');
        }
        if (!in_array($type, array('all', 'pendpay', 'pendsend', 'pendreceipt', 'pendcomment'))) {
            return $this->error(400, '参数错误');
        }

        $return = array();
        if ($type == 'pendpay' || $type == 'all') {
            //如果类型等于all 或者 代付款  那么查询所有的count
            $info = KaluliMainOrderTable::getInstance()
                ->createQuery()
                ->select('count(1) AS total')
                ->andWhere('hupu_uid = ?', $hupuUid)
                ->andWhere('status = ?', 0)
                ->fetchOne()
                ->toArray();
            $return['pendpay'] = $info['total'];
        }
        if ($type == 'pendsend' || $type == 'all') {
            $info = KaluliMainOrderTable::getInstance()
                ->createQuery()
                ->select('count(1) AS total')
                ->andWhere('hupu_uid = ?', $hupuUid)
                ->andWhere('status = ?', 1)
                ->fetchOne()
                ->toArray();
            $return['pendsend'] = $info['total'];
        }
        if ($type == 'pendreceipt' || $type == 'all') {
            $info = KaluliMainOrderTable::getInstance()
                ->createQuery()
                ->select('count(1) AS total')
                ->andWhere('hupu_uid = ?', $hupuUid)
                ->andWhere('status = ?', 2)
                ->fetchOne()
                ->toArray();
            $return['pendreceipt'] = $info['total'];
        }
        if ($type == 'pendcomment' || $type == 'all') {
            $info = KaluliMainOrderTable::getInstance()
                ->createQuery()
                ->select('count(1) AS total')
                ->andWhere('hupu_uid = ?', $hupuUid)
                ->andWhere('status = ?', 3)
                ->fetchOne()
                ->toArray();
            $return['pendcomment'] = $info['total'];
        }
        if ($type == 'all') {  //查询所有记录
            $info = KaluliMainOrderTable::getInstance()
                ->createQuery()
                ->select('count(1) AS total')
                ->andWhere('hupu_uid = ?', $hupuUid)
                ->fetchOne()
                ->toArray();
            $return['all'] = $info['total'];
        }
        return $this->success(array('myorder_num' => $return));
    }

    /**
     * 我的订列表
     * @param string type 订单类型（all：全部  pendpay：待付款  pendsend：待发货  pendreceipt：待收货  pendcomment：待评价）
     */
    public function executeMyorderList()
    {
        $v = $this->getRequest()->getParameter('v');
        $type = $this->getRequest()->getParameter('type', 'all');
        $page = $this->getRequest()->getParameter('page', 1);
        //是否按照仓库排序
        $is_house_sort = $this->getRequest()->getParameter('is_house_sort', 0);
        $pageSize = $this->getRequest()->getParameter('pageSize', 10);


        $hupuUid = $this->getRequest()->getParameter('hupu_uid');
        if(empty($hupuUid))
            $hupuUid = $this->getUser()->getAttribute('uid');

        $hupuUname = $this->getUser()->getAttribute('username');
        if (empty($hupuUid) || !is_numeric($hupuUid)) {
            return $this->error(501, '未登录');
        }
        if (!in_array($type, array('all', 'pendpay', 'pendsend', 'pendreceipt', 'pendcomment'))) {
            return $this->error(400, '参数错误');
        }
        if ($pageSize > 100) $pageSize = 100;
        if (!is_numeric($page) || (int) $page < 1) {
            return $this->error(400, '参数错误');
        }
        if (!is_numeric($pageSize) || (int) $pageSize < 1) {
            return $this->error(400, '参数错误');
        }

        //查询所有主订单
        $offset = ($page - 1) * $pageSize;
        $mainOrderObj = KaluliMainOrderTable::getInstance()->createQuery()->select()
            ->where('hupu_uid = ?', $hupuUid)
            ->offset($offset)
            ->limit($pageSize)
            ->orderBy('order_time desc');

        //各种类型 添加 where
        if ($type == 'pendpay') {
            $mainOrderObj->andWhere('status = ?', 0);
        }
        if ($type == 'pendsend') {
            $mainOrderObj->andWhere('status = ?', 1);
        }
        if ($type == 'pendreceipt') {
            $mainOrderObj->andWhere('status = ?', 2);
        }
        if ($type == 'pendcomment') {
            $mainOrderObj->andWhere('status = ?', 3);
        }
        //获取总数
        $mainOrder = $mainOrderObj->execute();
        $order_number = array();
        $new_main_order = array();
        $data_count =  $data = $return = array();
        //循环所有数据
        if ($mainOrder->getData()) {
            foreach ($mainOrder as $k => $v) {
                $order_number[] = $v->getOrderNumber();
                $new_main_order[$v->getOrderNumber()] = array(
                    'order_number' => $v->getOrderNumber(),
                    'order_time' => $v->getOrderTime(),
                    'number' => $v->getNumber(),
                    'status' => $v->getStatus(),
                    'total_price' => $v->getTotalPrice(),
                    'express_fee' => $v->getExpressFee(),
                    'duty_fee'=>$v->getDutyFee()
                );
            }
            //获取子订单
            $orderObj = KaluliOrderTable::getInstance()->createQuery()->select()
                ->where("order_number in (" . join(",", $order_number) . ") ")
                ->orderBy('order_time desc')
                ->execute();

            //获取子订单的attr
            $orderAttrObj = KaluliOrderAttrTable::getInstance()->createQuery()->select()
                ->where("order_number in (" . join(",", $order_number) . ") ")
                ->orderBy('id desc')
                ->execute();

            //循环子订单attr
            $orderAttrObj_arr = array();
            foreach($orderAttrObj as $m=>$n){
                $orderAttrObj_arr[$n->getOrderId()] = $n;
            }




            $i= 0;
            //循环子订单
            foreach ($orderObj as $k => $v) {
                if (!isset($data[$v->getOrderNumber()])) {
                    $i=0;
                } else {
                    $i = $data[$v->getOrderNumber()]['count'];
                }
                $attr = json_decode($orderAttrObj_arr[$v->getId()]->getAttr(), true);
                $img = isset($attr['img']) ? $attr['img'] : '';
                if(isset($attr['img'])) unset($attr['img']);

                # 是否可以发布评论
                if($v->getStatus() == 2 && $v->getPayStatus() == 1 && $v->getIsComment()==0) {
                    $commentUrl = '/ucenter/orderComment?order_number='. $v->getOrderNumber().'&product_id='.$v->getProductId().'&goods_id='.$v->getGoodsId();
                } else {
                    $commentUrl = '';
                }

                $_tmp = array(
                    'id' => $v->getId(),
                    'status' => $v->getStatus(),
                    'is_gift' => $v->getIsGift(),
                    'order_number'=> $v->getOrderNumber(),
                    'format_status' => $v->getFormatOrderStatus(),
                    'is_comment' => $v->getIsComment() ? true : false,
                    'pay_time' => $v->getPayTime(),
                    'pay_status' => $v->getPayStatus(),
                    'receive_time' => $v->getReceiveTime(),
                    'product_id' => $v->getProductId(),
                    'goods_id' => $v->getGoodsId(),
                    'title' => $v->getTitle(),
                    'price' => $v->getPrice(),
                    'number' => $v->getNumber(),
                    'express_fee' => $v->getExpressFee(),
                    'total_price' => $v->getTotalPrice(),
                    'domestic_express_type' => $v->getFormatDomesticExpress(),
                    'domestic_order_number' => $v->getDomesticOrderNumber(),
                    'domestic_express_time' => $v->getDomesticExpressTime(),
                    'depot_type' => $v->getDepotType(),
                    'attr' => $attr,
                    'img' => $img,
                    'comment_url'=>$commentUrl,
                );
                //判断是不是以仓库分割的
                if($is_house_sort) {
                    $data[$v->getOrderNumber()]['item'][$v->getDepotType()][$i] = $_tmp;
                    $data[$v->getOrderNumber()]['status'][$v->getDepotType()][] = $_tmp['status'];
                    $data[$v->getOrderNumber()]['pay_status'][$v->getDepotType()][] = $_tmp['pay_status'];
                } else {
                    $data[$v->getOrderNumber()]['item'][$i] = $_tmp;
                }
                $i++;
                $data[$v->getOrderNumber()]['count'] = $i;
            }
            $j=0;
            foreach ($data as $k=>$v) {
                $return[$j]['main_order'] = $new_main_order[$k];
                $return[$j]['order'] = $v;

                $j++;
            }
        }

        return $this->success(array('list' => $return));
    }

    /**
     * 提交订单
     *2016.3.4增加0元购相关代码    --by 李斌
     * $serviceRequest = new kaluliServiceClient();
    $serviceRequest->setMethod('order.add');
    $serviceRequest->setVersion('1.0');
    $serviceRequest->setApiParam('data', array());
    $serviceRequest->setUserToken($request->getCookie('u'));
    $response = $serviceRequest->execute();
     *
     *
     */
    public function executeAdd()
    {
        
        $v = $this->getRequest()->getParameter('v');
        $hupuUid = $this->getUser()->getAttribute('uid');
        $uname = $this->getUser()->getAttribute('username');
        $data = $this->getRequest()->getParameter('data',array());
        //默认情况下，1: 原来的逻辑，2:排它活动(会员权益)
        $activity_type = isset($data['card_type']) ? $data['card_type'] : 1;

        $pay_data = $this->getRequest()->getParameter('pay_data',array());  //支付参数
        $kll_union = $this->getRequest()->getParameter('kll_union'); // cps推广标志
        $is_wap = $this->getRequest()->getParameter('is_wap',0);
        $is_zero = $this->getRequest()->getParameter('is_zero',0); //0元购标志位
        $is_app = $this->getRequest()->getParameter("is_app",0);//标识app支付位

        if(!isset($data['product_id'])) $data['product_id'] = '';//主商品id
        if(!isset($data['goods_id'])) $data['goods_id'] = '';//子商品id
        if(!isset($data['address_id'])) $data['address_id'] = '';//地址id
        if(empty($data['number'])) $data['number'] = '1';//数量
        if(!isset($data['number'])) $data['number'] = '3';//数量
        if(!isset($data['remark'])) $data['remark'] = '';//备注
        if(!isset($data['linkFlag'])) $data['linkFlag'] = true;//是否生成支付宝链接
        if(!isset($data['card'])) $data['card'] = '';//优惠码
        if(!isset($data['card_id'])) $data['card_id'] = '';//优惠码id
        if(empty($data['express_type'])) $data['express_type'] = KaluliOrder::$_DEFAULT_EXPRESS_TYPE;//快递类型 2顺丰 4圆通

        $is_ht = false;


        if(!$hupuUid){
            return $this->error(501, '未登录');
        }
        if (!is_numeric($data['number']))
            return $this->error(400, '购买数量不合法');

        if (!is_numeric($data['address_id']))
            return $this->error(401, '收货地址不合法');

        $address = TrdUserDeliveryAddressTable::getInstance()->find($data['address_id']);
        if (!$address){
            return $this->error(401, '收货地址不合法');
        }
        //校验收货地址是否为当前用户
        if($address->hupu_uid != $hupuUid) {
            kaluliLog::info("address",['hupuId'=>$hupuUid,'addressId'=>$address->hupu_uid]);
            return $this->error(401,'收货地址不合法');
        }


        $region_id = $address->get('province');
        $serviceRequest = new kaluliServiceClient();


        //判断活动商品
        $serviceRequest->setMethod("activity.check.activity");
        $serviceRequest->setVersion("1.0");
        $serviceRequest->setApiParam("uid",$hupuUid);
        $serviceRequest->setApiParam("itemId",$data['product_id']);
        $response = $serviceRequest->execute();
        if($response->getStatusCode() == 200) {
            $itemActivity = $response->getValue('itemActivity');
            $activityPrice = $itemActivity['price'];
        }

        //判断主商品
        $serviceRequest->setMethod('item.itemGet');
        $serviceRequest->setVersion('1.0');
        $serviceRequest->setApiParam('id',$data['product_id']);
        //增加0元购参数设置
        if($is_zero){
            $serviceRequest->setApiParam("isZero",true);
        }
        $response = $serviceRequest->execute();
        if($response->hasError()){
            kaluliLog::info('kaluli_item_mainItem', array(
                'item_title' =>"错误",
                'msg' => $response->getMsg(),
                'admin_name' =>"admin",
            ));
            return $this->error(400, '非法的参数');
        }
        $main_data = $response->getData();

        //判断子商品
        $serviceRequest->setMethod('item.itemSkuGet');
        $serviceRequest->setVersion('1.0');
        $serviceRequest->setApiParam('id',$data['goods_id']);
        $response = $serviceRequest->execute();

        if($response->hasError()){
            kaluliLog::info('kaluli_item_skuItem', array(
                'item_title' =>"错误",
                'msg' => $response->getMsg(),
                'admin_name' =>"admin",
            ));
            return $this->error(400, '非法的参数');
        }
        $goods_data = $response->getData();

        //对子商品加锁，防止库存不足
        $status = kaluliFun::getLock('kaluli.product.goods'.$data['goods_id'],5);//获取锁
        if ($status[0]['status'] < 1){
            return $this->error(502, '系统繁忙，请稍后再试');
        }
        //库存判断
        $stock = $goods_data['data']['sku']['total_num'];
        if($stock < 1 || $stock < $data['number']){
            kaluliFun::releaseLock('kaluli.product.goods'.$data['goods_id']);//释放锁
            return $this->error(403,'库存不足');
        }

        if($goods_data['data']['sku']['storehouse_id'] ==10 || $goods_data['data']['sku']['storehouse_id'] ==16 || $goods_data['data']['sku']['storehouse_id'] ==5 || $goods_data['data']['sku']['storehouse_id'] ==20) {
            if (!$address->getIdentityNumber()){
                return $this->error(402, '收货地址必须要身份证号码');
            }
        }
        //为会员权益排它活动单独拉出来。
        // $activity_type = isset($data['activity_type']) ? 1 : 1;
        // $drop_out = $this->dropOutActivity($data['product_id'], $activity_type);
        //排它活动为真时。其他活动取消
        // if ($activity_type) {
        //     $activityPrice = 0;
        // }
        //下单
        if($activity_type != 2 && isset($activityPrice)) {
            $price = $activityPrice;
        } else {
            $price = $goods_data['data']['sku']['discount_price'];
        }
        $express_fee = 0;

        //计算运费 by 李斌
        $weight  = $goods_data['data']['sku']['weight']*$data['number'];
        $serviceRequest->setMethod('warehouse.GetByWarehouseExpress');
        $serviceRequest->setVersion('1.0');
        //有省id根据你当前省id进行计算,没有拿默认区域算运费

        if($region_id) {
            $serviceRequest->setApiParam("provinceId",$region_id);
        } else {
            $serviceRequest->setApiParam("isDefault",1);
        }
        $serviceRequest->setApiParam("weight",$weight);
        $serviceRequest->setApiParam("expressType",$data['express_type']);
        $serviceRequest->setApiParam("wareHouseId",$goods_data['data']['sku']['storehouse_id']);
        $response = $serviceRequest->execute();
        if($response->hasError()) {
            return $this->error($response->getStatusCode(),$response->getMsg());
        }
        $express_fee = $response->getValue("expressFee");

        $original_price = $total_price = $price*$data['number']+$express_fee;
        $total_product_price = $price*$data['number'];

        //拼装活动需要的数据
        $marketing_list[$data['goods_id']]['product_id'] = $data['product_id'];
        $marketing_list[$data['goods_id']]['goods_id'] = $data['goods_id'];
        $marketing_list[$data['goods_id']]['number'] = $data['number'];
        $marketing_list[$data['goods_id']]['price'] = $price*$data['number'];

        //生成订单号
        $order_sn = date('ymd').substr(time(),-5).substr(microtime(),2,5);
        $child_total_price = $total_price;

        $activity_save = $coupon_fee = 0;

        if(!isset($activityPrice) && $activity_type != 2) { //存在抢购价不用促销活动,并且不属于排它活动
            $serviceRequest = new kaluliServiceClient();
            $serviceRequest->setMethod('marketing.getActivity');
            $serviceRequest->setVersion('1.0');
            $serviceRequest->setApiParam('marketing_list', $marketing_list);
            $response = $serviceRequest->execute();
            if (!$response->hasError()) {
                $act_data = $response->getData();
                $goods_info = $act_data['data']['data']['goods_info'];
                $activity_save = $act_data['data']['data']['activity_save'];
                $total_price -= $activity_save;
                $total_product_price -= $activity_save;
                if ($total_price < 0) {
                    $total_price = 0;
                    $activity_save = $price * $data['number'] + $express_fee;
                }

                //插入营销活动记录
                foreach ($act_data['data']['data']['activity'] as $k => $v) {
                    foreach ($v['list'] as $kk => $vv) {
                        if ($vv['flag']) {
                            $marketingDetailObi = new KaluliOrderMarketingDetail();
                            $marketingDetailObi->set('order_number', $order_sn);
                            $marketingDetailObi->set('marketing_id', $vv['id']);
                            $marketingDetailObi->set('attr', json_encode($vv));
                            $marketingDetailObi->save();
                        }
                    }
                }
            }
        }else{
            //排它的活动
            $serviceRequest = new kaluliServiceClient();
            $serviceRequest->setMethod('benefits.get.activity');
            $serviceRequest->setVersion('1.0');
            $serviceRequest->setApiParam('card_id',$data['card_id']);
            $serviceRequest->setApiParam('goods', $marketing_list);
            $response = $serviceRequest->execute();
            if (!$response->hasError()) {
                $act_data = $response->getData();
                $goods_info = $act_data['data']['data']['goods_info'];
                $activity_save = $act_data['data']['data']['activity_save'];
                $total_price -= $activity_save;
                $total_product_price -= $activity_save;
                if ($total_price < 0) {
                    $total_price = 0;
                    $activity_save = $price * $data['number'] + $express_fee;
                }
            }
        }


        //计算税费
        $serviceRequest->setMethod("warehouse.GetTax");
        $serviceRequest->setApiParam("wareHouseId",$goods_data['data']['sku']['storehouse_id']);
        $serviceRequest->setApiParam("price",$total_price);
        $serviceRequest->setVersion("1.0");

        $response = $serviceRequest->execute();
        if($response->hasError()) {
            return $this->error($response->getStatusCode(),$response->getMsg());
        }
        $dutyFee = $response->getValue("dutyFee");




        $total_price += $dutyFee;

        if ($data['card_id'] && $data['card_type'] == 1){
            //优惠码验证
            $serviceRequest->setMethod('lipinka.use');
            $serviceRequest->setVersion('1.0');
            $serviceRequest->setApiParam('user_id', $hupuUid);
            $serviceRequest->setApiParam('id', $data['card_id']);
            $serviceRequest->setApiParam('card_limit', array('order_money'=>$total_product_price));
            $response = $serviceRequest->execute();
            if (!$response->hasError()) {
                $_lipinka_data = $response->getData();
                $coupon_fee = $_lipinka_data['data']['amount'];
                $total_price -= $coupon_fee;
                if ($total_price < 0) {
                    return $this->error(403,'礼品卡金额不能大于订单金额！');
                }
                //插入礼品卡使用记录
                $activityDetailObi = new KllOrderActivityDetail();
                $activityDetailObi->set('order_number', $order_sn);
                $activityDetailObi->set('activity_id', $data['card_id']);
                $activityDetai_attr = array('code' => $_lipinka_data['data']['account'], 'price' => $coupon_fee);
                $activityDetailObi->set('attr', json_encode($activityDetai_attr));
                $activityDetailObi->save();
            } else {
                return $this->error(403,$response->getError());
            }
        }

        try {
            $time = date('Y-m-d H:i:s');
            //保存订单
            //插入主表 kll_main_order
            $mainOrderObj = new KaluliMainOrder();
            $mainOrderObj->setOrderNumber($order_sn);
            $mainOrderObj->setHupuUid($hupuUid);
            $mainOrderObj->setHupuUsername($uname);
            $mainOrderObj->setExpressFee($express_fee);
            $mainOrderObj->setTotalPrice($total_price);
            $mainOrderObj->setOriginalPrice($original_price);
            $mainOrderObj->setCouponFee($coupon_fee);
            $mainOrderObj->setMarketingFee($activity_save);
            $mainOrderObj->setNumber($data['number']);
            $mainOrderObj->setPayType(empty($pay_data['pay_type']) ? $this->pay_type['default'] : $pay_data['pay_type']);
            $mainOrderObj->setOrderTime($time);
            if($is_wap) {
                $mainOrderObj->setSource(1);
            }
            //0元购专用
            if($is_zero) {
                $mainOrderObj->setStatus(1);//0元购下单后直接变成待发货
            }
            //海淘税费
            if(isset($dutyFee)) {
                $mainOrderObj->setDutyFee($dutyFee);
            }
            if(isset($activityPrice)) {
                $mainOrderObj->setIsActivity($itemActivity['activity_id']);
            }
            $mainOrderObj->save();

            //保存主订单副表
            $mainOrderAttrObj = new KaluliMainOrderAttr();
            $mainOrderAttrObj->setOrderNumber($order_sn);
            //拼接收货地址
            $street = explode(' ',trim($address->getRegion()));
            $address_arr = array(
                'name'=>$address->getName(),
                'postcode'=>$address->getPostcode(),
                'province'=>$street[0],
                'city'=>$street[1],
                'area'=>isset($street[2]) ? $street[2] : '',
                'mobile'=>$address->getMobile(),
                'region'=>$address->getRegion(),
                'street'=>$address->getStreet(),
                'identity_number'=>$address->getIdentityNumber()
            );
            $mainOrderAttrObj->setAddressAttr(json_encode($address_arr));
            $mainOrderAttrObj->setRemark($data['remark']);
            if($is_zero) {
                $mainOrderAttrObj->setIsRemind(1);
            } else {
                $mainOrderAttrObj->setIsRemind(0);
            }
            //使用优惠券,存入
            if($data['card_type'] == 1 && !empty($data['card_id'])) {
                $mainOrderAttrObj->setCouponId($data['card_id']);
            }

            $mainOrderAttrObj->save();

            //会员权益
            if(isset($data['card_type']) && $data['card_type'] == 2){
                $this->_useBenefits($order_sn, $data['card_id'] );
            }

            /*
             * todo 子订单税费存入
             *
             */
            $orderObj = new KaluliOrder();
            $orderObj->setOrderNumber($order_sn);
            $orderObj->setTitle($main_data['data']['item']['title']);
            $orderObj->setProductId($data['product_id']);
            $orderObj->setHupuUid($hupuUid);
            $orderObj->setHupuUsername($uname);
            $orderObj->setGoodsId($data['goods_id']);
            $orderObj->setPrice($price);
            $orderObj->setNumber($data['number']);
            $orderObj->setDomesticExpressType($data['express_type']);
            $orderObj->setExpressFee($express_fee);
            $marketing_fee = 0;
            if(isset($goods_info[$data['goods_id']]['marketing_fee'])) {
                $marketing_fee = empty($goods_info[$data['goods_id']]['marketing_fee']) ? 0 : $goods_info[$data['goods_id']]['marketing_fee'];
            }else if(isset($goods_info[$data['goods_id']]['save'])) {
                $marketing_fee = empty($goods_info[$data['goods_id']]['save']) ? 0 : $goods_info[$data['goods_id']]['save'];
            }
            $orderObj->setMarketingFee($marketing_fee);
            $orderObj->setTotalPrice($child_total_price);
            $orderObj->setDepotType($goods_data['data']['sku']['storehouse_id']);//发货仓库
            $orderObj->setOrderTime($time);
            if($is_wap) {
                $orderObj->setSource(1);
            }
            if($is_zero) {
                $orderObj->setPayStatus(1); //0元购项目下单完成直接已支付
            }

            if($dutyFee) {
                $orderObj->setDutyFee($dutyFee);
            }
            if(isset($activityPrice)) {
                $orderObj->setIsActivity($itemActivity['activity_id']); //存在活动价定义为活动单
            }
            $orderObj->save();

            //保存子订单 副表
            $orderAttrObj = new KaluliOrderAttr();
            $orderAttrObj->setOrderNumber($order_sn);
            $orderAttrObj->setOrderId($orderObj->getId());
            $orderAttrObj->setCode($goods_data['data']['sku']['code']);

            if($goods_data['data']['sku']['attr']){
                $goods_attr = unserialize($goods_data['data']['sku']['attr']);
                $attr = $goods_attr['attr'];
            }
            $attr['img'] = $main_data['data']['item']['pic'];
            $orderAttrObj->setAttr(json_encode($attr));
            $orderAttrObj->save();

            //减库存
            $serviceRequest->setMethod('item.skuStock');
            $serviceRequest->setVersion('1.0');
            $serviceRequest->setApiParam('id',$data['goods_id']);
            $serviceRequest->setApiParam('num',$data['number']);
            $serviceRequest->setApiParam('type',1);//下单情况
            $serviceRequest->execute();

            kaluliFun::releaseLock('kaluli.product.goods'.$data['goods_id']);//释放锁
        }catch(Exception $e) {
            kaluliFun::releaseLock('kaluli.product.goods'.$data['goods_id']);//释放锁
            return $this->error(502,'系统繁忙，请稍后再试');
        }

        //记录到订单日志表
        $log = array(
            'status' =>0,
            'order_number' =>$order_sn,
            'hupu_uid' =>$hupuUid,
            'hupu_username' =>$uname,
            'explanation' =>'购买了：'.$main_data['data']['item']['title'],
        );
        $this->saveLog($log);

        


        $data['hupu_uid'] = $hupuUid;
        $data['hupu_username'] = $uname;
        $message = array(
            'message'=>'卡路里下单成功',
            'param'=>$data,
            'res'=>array(),
            'order_number'=>$order_sn
        );
        kaluliLog::info('kaluli-place',$message);

        //cps推广.卡路里kol达人使用
        $message = array('order_number'=>$order_sn, 'cookie'=>$kll_union, 'type'=>'create');
        kaluliFun::sendMqMessage('kalulicps.order.detail',$message,'kaluli_order_detail_deferred');


        //去支付 下个页面
        if($is_wap) {
            $url = 'http://m.kaluli.com/auction/orderResult?order_number=' . $order_sn;
        } else {
            $url = 'http://www.kaluli.com/auction/orderResult?order_number=' . $order_sn;
        }
        //是否生成购买链接
        if($data['linkFlag']){
            $serviceRequest->setMethod('order.getPayLink');
            $serviceRequest->setVersion('1.0');
            $serviceRequest->setApiParam('uid', $hupuUid);
            $serviceRequest->setApiParam('pay_data', $pay_data);
            $serviceRequest->setApiParam('is_wap', $is_wap);
            $serviceRequest->setApiParam("is_app",$is_app);
            $serviceRequest->setApiParam('order_number', $order_sn);
            $response = $serviceRequest->execute();
            $res = $response->getData();
            if(!$res){
                return $this->error(502,'系统繁忙，请稍后再试');
            }
            // $url = $res['data']['pay_url'];
            return $this->success($res['data']);
        }
         if($data['card_type'] == 2){
            //在此记录订单
            $benefits_activity = new KllMemberBenefitsOrder();
            $benefits_activity->setMbId($card_id)->setOrderNumber($order_sn)->save();
        }

        return $this->success(array("orderId"=>$orderObj->getId()));

    }
    /**
     * 排它活动
     * kworm
     */
    private function dropOutActivity($product_id, $type){
        return 1;
    }
    /**
     * 订单详情
     *
     * $serviceRequest = new kaluliServiceClient();
    $serviceRequest->setMethod('order.getDetail');
    $serviceRequest->setVersion('1.0');
    $serviceRequest->setApiParam('order_number', 12333222);
    $serviceRequest->setApiParam('order_id', 12);
    $serviceRequest->setUserToken($request->getCookie('u'));
    $response = $serviceRequest->execute();
     *
     */
    public function executeGetDetail()
    {
        $hupuUid = $this->getUser()->getAttribute('uid');
        $order_number = $this->getRequest()->getParameter('order_number',0);
        $is_house_sort = $this->getRequest()->getParameter('is_house_sort',0); //是否按照仓库排序
        $order_id = $this->getRequest()->getParameter('order_id');
        try{
            if(empty($hupuUid)){
                throw new Exception('未登录',500);
            }
            if(empty($order_number)){
                throw new Exception('参数有误',400);
            }
            $orderMainObj = KaluliMainOrderTable::getInstance()->createQuery()->select('*')->where('order_number = ?',$order_number)->andWhere('hupu_uid = ?',$hupuUid)->limit(1)->fetchOne();
            if(!$orderMainObj){
                throw new Exception('参数有误',400);
            }
            $orderMainAttrObj = KaluliMainOrderAttrTable::getInstance()->findOneByOrderNumber($order_number);
            if($order_id){
                $orderObj = KaluliOrderTable::getInstance()->createQuery()->select('*')->where('id = ?',$order_id)->execute();
                $orderAttrObj = KaluliOrderAttrTable::getInstance()->createQuery()->select('*')->where('order_id = ?',$order_id)->execute();
            } else {
                $orderObj = KaluliOrderTable::getInstance()->createQuery()->select('*')->where('order_number = ?',$order_number)->execute();
                $orderAttrObj = KaluliOrderAttrTable::getInstance()->createQuery()->select('*')->where('order_number = ?',$order_number)->execute();
            }
            $return = array();

            //组装主订单
            $return['mainOrder']['id'] = $orderMainObj->getId();
            $return['mainOrder']['order_number'] = $orderMainObj->getOrderNumber();
            $return['mainOrder']['hupu_uid'] = $orderMainObj->getHupuUid();
            $return['mainOrder']['hupu_username'] = $orderMainObj->getHupuUsername();
            $return['mainOrder']['express_fee'] = $orderMainObj->getExpressFee();
            $return['mainOrder']['total_price'] = $orderMainObj->getTotalPrice();
            $return['mainOrder']['original_price'] = $orderMainObj->getOriginalPrice();
            $return['mainOrder']['coupon_fee'] = $orderMainObj->getCouponFee();
            $return['mainOrder']['number'] = $orderMainObj->getNumber();
            $return['mainOrder']['order_time'] = $orderMainObj->getOrderTime();
            $return['mainOrder']['pay_time'] = $orderMainObj->getPayTime();
            $return['mainOrder']['refund'] = $orderMainObj->getRefund();
            $return['mainOrder']['status'] = $orderMainObj->getStatus();
            $return['mainOrder']['remark'] = $orderMainAttrObj->getRemark();
            $return['mainOrder']['address_attr'] = json_decode($orderMainAttrObj->getAddressAttr(),1);
            $return['mainOrder']['duty_fee'] = $orderMainObj->getDutyFee();
            $return['mainOrder']['marketing_fee'] = $orderMainObj->getMarketingFee();
            $return['mainOrder']['is_activity'] = $orderMainObj->getIsActivity();

            $orderAttrObj_arr = array();
            foreach ($orderAttrObj as $m=>$n){
                $orderAttrObj_arr[$n->getOrderId()] = $n;
            }
            $order_arr  = array();
            //组装子订单

            foreach($orderObj as $k=>$v){
                $attr = json_decode($orderAttrObj_arr[$v->getId()]->getAttr(),true);
                $img = isset($attr['img']) ? $attr['img'] : '';
                if(isset($attr['img'])) unset($attr['img']);
                $order_arr['id'] = $v->getId();
                $order_arr['title'] = $v->get('title');
                $order_arr['order_number'] = $v->get('order_number');
                $order_arr['product_id'] = $v->get('product_id');
                $order_arr['goods_id'] = $v->get('goods_id');
                $order_arr['express_type'] = $v->get('domestic_express_type');
                $order_arr['domestic_express_type'] = $v->getFormatDomesticExpress();
                $order_arr['domestic_order_number'] = $v->get('domestic_order_number');
                $order_arr['domestic_express_time'] = $v->get('domestic_express_time');
                $order_arr['depot_type'] = $v->get('depot_type');
                $order_arr['express_fee'] = $v->get('express_fee');
                $order_arr['marketing_fee'] = $v->get('marketing_fee');
                $order_arr['total_price'] = $v->get('total_price');
                $order_arr['price'] = $v->get('price');
                $order_arr['number'] = $v->get('number');
                $order_arr['status'] = $v->get('status');
                $order_arr['pay_status'] = $v->get('pay_status');
                $order_arr['format_status'] = $v->getFormatOrderStatus();
                $order_arr['order_time'] = $v->get('order_time');
                $order_arr['receive_time'] = $v->get('receive_time');
                $order_arr['is_comment'] = $v->get('is_comment');
                $order_arr['attr'] = $attr;
                $order_arr['img'] = $img;
                $order_arr['refund_price'] = $orderAttrObj_arr[$v->getId()]->get('refund_price');
                $order_arr['refund_express_fee'] = $orderAttrObj_arr[$v->getId()]->get('refund_express_fee');
                $order_arr['refund'] = $orderAttrObj_arr[$v->getId()]->get('refund');
                # 是否可以发布评论
                if($v->getStatus() == 2 && $v->getPayStatus() == 1 && $v->getIsComment()==0)
                {
                    $commentUrl = '/ucenter/orderComment?order_number='. $v->getOrderNumber().'&product_id='.$v->getProductId().'&goods_id='.$v->getGoodsId();
                }
                else
                {
                    $commentUrl = '';
                }
                $order_arr['comment_url'] = $commentUrl;

                if($is_house_sort) {
                    $return['order'][$v['depot_type']][] = $order_arr;
                    $return['pay_status'][$v['depot_type']][] = $v->get('pay_status');
                    $return['status'][$v['depot_type']][] = $v->get('status');
                } else {
                    $return['order'][] = $order_arr;
                }
            }
        }catch (Exception $e){
            return $this->error($e->getCode(),$e->getMessage());
        }
        return $this->success($return);
    }

    /**
     * 计算订单价格
     *
     * $serviceRequest = new kaluliServiceClient();
    $serviceRequest->setMethod('order.getOrderTotalPrice');
    $serviceRequest->setVersion('1.0');
    $serviceRequest->setApiParam('data', array());
    $serviceRequest->setUserToken($request->getCookie('u'));
    $response = $serviceRequest->execute();
     *
     */
    public function executeGetOrderTotalPrice()
    {
        $v = $this->getRequest()->getParameter('v');
        $hupuUid = $this->getUser()->getAttribute('uid',$this->getUser()->getAttribute('uid'));
        $uname = $this->getUser()->getAttribute('username');//当前操作用户名
        $no_login = $this->getRequest()->getParameter('no_login',0);//是否不登陆
        $data = $this->getRequest()->getParameter('data',array());

        if(!isset($data['product_id'])) $data['product_id'] = '';//主商品id
        if(!isset($data['goods_id'])) $data['goods_id'] = '';//子商品id
        if(!isset($data['address_id'])) $data['address_id'] = '';//地址id
        if(!isset($data['number'])) $data['number'] = '';//数量
        if(!isset($data['express_type'])) $data['express_type'] = 0;
        if(!isset($data['province_id'])) $data['province_id'] = '';//省份ID
        if(!isset($data['card_type'])) $data['card_type'] = 1;//优惠卡类型
        if(!isset($data['card_id'])) $data['card_id'] = 1;//优惠卡类型



        if(empty($no_login) && !$hupuUid){
            return $this->error(501, '未登录');
        }
        if (!is_numeric($data['number']) || !is_numeric($data['product_id']) || !is_numeric($data['goods_id']))
            return $this->error(400, '参数非法');

        if(empty($data['product_id']) || empty($data['goods_id'])){
            return $this->error(400, '参数非法');
        }

        if (!empty($data['address_id']) && !is_numeric($data['address_id']))
            return $this->error(401, '收货地址不合法');

        $region_id = 0;
        if (!empty($data['address_id'])){
            $address = TrdUserDeliveryAddressTable::getInstance()->find($data['address_id']);
            if (!$address){
                return $this->error(401, '收货地址不合法');
            }
            $region_id = $address->get('province');
        } else if(!empty($data['province_id'])) {
            $data['province_id'] = (int)$data['province_id'];
            if(empty($data['province_id']))  return $this->error(401, '请填写完成收货地址！');
            $region_id = $data['province_id'];
        }
        //海淘计算税费参数
        $is_ht = false;




        //获取
        $express_fee  = 0;

        $serviceRequest = new kaluliServiceClient();
        $serviceRequest->setMethod('item.itemSkuGet');
        $serviceRequest->setVersion('1.0');
        $serviceRequest->setApiParam('id',$data['goods_id']);
        $response = $serviceRequest->execute();
        if(!$response->hasError()){
            $res_data = $response->getData();
            //存在活动,价格按活动价格计算
            if(isset($data['activityPrice']) && $data['card_type'] != 2 ) {
                $price = $data['activityPrice'];
            } else {
                $price = $res_data['data']['sku']['discount_price'];
            }
            //计算运费新规则 by 李斌

            $weight =  $res_data['data']['sku']['weight']*$data['number'];
            $serviceRequest->setMethod('warehouse.GetExpressFee');
            $serviceRequest->setVersion('1.0');
            //有省id根据你当前省id进行计算,没有拿默认区域算运费

            if($region_id) {
                $serviceRequest->setApiParam("provinceId",$region_id);
            } else {
                $serviceRequest->setApiParam("isDefault",1);
            }
            $serviceRequest->setApiParam("weight",$weight);
            $serviceRequest->setApiParam("expressType",$data['express_type']);
            $serviceRequest->setApiParam("wareHouseId",$res_data['data']['sku']['storehouse_id']);
            $response = $serviceRequest->execute();
            if($response->hasError()) {
                return $this->error(401,$response->getMsg());
            }
            $expressData = $response->getData();
            $expressList =$expressData['data'];
            foreach($expressList as $v) {
                if($v['isCheck'] == 1) {
                    $express_fee = $v['fee'];
                }
            }

            //库存判断
            $stock = $res_data['data']['sku']['total_num'];
            if($stock < 1 || $stock < $data['number']){
                return $this->error(403,'库存不足',array('stock'=>$stock));
            }

            /*  仓库判断  */
            $ware_data = KaluliWarehousesTable::getOneWareById($res_data['data']['sku']['storehouse_id']);


        } else {
            return $this->error(402, '非法的sku');
        }
        $original_price = $total_price = $price*$data['number']+$express_fee;



        //获取仓库税费信息
        //获取税费信息
        $serviceRequest->setMethod("warehouse.GetTaxInfo");
        $serviceRequest->setApiParam("wareHouseId",$res_data['data']['sku']['storehouse_id']);
        $serviceRequest->setVersion("1.0");
        $response = $serviceRequest->execute();
        if(!$response->hasError()) {
            $taxInfo = $response->getData();
            $taxInfo = $taxInfo['data']->toArray();
        }




        //拼装活动需要的数据
        $marketing_list[$data['goods_id']]['product_id'] = $data['product_id'];
        $marketing_list[$data['goods_id']]['goods_id'] = $data['goods_id'];
        $marketing_list[$data['goods_id']]['number'] = $data['number'];
        $marketing_list[$data['goods_id']]['price'] = $price*$data['number'];

        //获取活动详情
        $coupon_fee = 0;
        $activity = array();
        //存在活动价格不计算市场活动
        if(!isset($data['activityPrice']) && $data['card_type'] != 2) {
            $serviceRequest = new kaluliServiceClient();
            $serviceRequest->setMethod('marketing.getActivity');
            $serviceRequest->setVersion('1.0');
            $serviceRequest->setApiParam('marketing_list', $marketing_list);
            $response = $serviceRequest->execute();
            if (!$response->hasError()) {
                $marketing_data = $response->getData();
                $coupon_fee = $marketing_data['data']['data']['activity_save'];
                $activity = $marketing_data['data']['data'];
            }
        }else if($data['card_type'] == 2){
            $serviceRequest = new kaluliServiceClient();
            $serviceRequest->setMethod('benefits.get.activity');
            $serviceRequest->setVersion('1.0');
            $serviceRequest->setApiParam('goods',$marketing_list);
            $serviceRequest->setApiParam('card_id',$data['card_id']);
            
            if (empty($goodsIds)) $serviceRequest->setApiParam('type',1 );
            $response = $serviceRequest->execute();

            if(!$response->hasError()){
                $dis_data = $response->getData();
                $coupon_fee = $dis_data['data']['data']['activity_save'];
                $activity = $dis_data['data']['data'];
            }
        }else{
            $activity['activity_save'] = 0;
        }

        //$coupon_fee = $this->getActivityCouponFee($total_price);//全场减
        //$coupon_fee = $this->getActivityBuyGiveCouponFee($data['goods_id'], $data['number']);//每日爆款
        //$coupon_fee = $this->getActivityAnniversary(['20160512' => [ '213', '36']]);

        $total_price = $total_price  - $coupon_fee;

        //计算税费
        $serviceRequest->setMethod("warehouse.GetTax");
        $serviceRequest->setApiParam("wareHouseId",$res_data['data']['sku']['storehouse_id']);
        $serviceRequest->setApiParam("price",$total_price);
        $serviceRequest->setVersion("1.0");

        $response = $serviceRequest->execute();
        if($response->hasError()) {
            return $this->error($response->getStatusCode(),$response->getMsg());
        }
        $dutyFee = $response->getValue("dutyFee");
        $total_price += $dutyFee;

        //活动
        //$activity = $this->getActivityList($original_price);
        //$activity = $this->getBuyGiveActivityList($coupon_fee, $data['number'], $data['goods_id']);




        //判断是否需要身份证
        $is_identify = 0;
        $serviceRequest->setMethod("item.is.haitao");
        $serviceRequest->setVersion("1.0");
        $serviceRequest->setApiParam("skuId",$data['goods_id']);
        $response = $serviceRequest->execute();
        if(!$response->hasError()) {
            $isHaitao = $response->getData();
            $is_identify = $isHaitao['data'];
        }


        $return = array(
            'number'=>$data['number'],
            'price'=>$price,
            'product_price'=>$price*$data['number'],
            'total_product_price'=>($price*$data['number'] - $coupon_fee),
            'express_fee'=>$express_fee,
            'total_price'=>$total_price,
            'original_price' => $original_price,
            'stock'=>$stock,
            'activity'=>$activity,
            'ware'=>$ware_data,
            'duty_fee'=>isset($dutyFee)?$dutyFee : 0,
            'is_identify' => $is_identify,  //判断是否需要填身份证
            'express_list'=>$expressList,    //传可选择快递列表
            'tax_info'=>$taxInfo,
            'total_original_price'=>($price*$data['number']),
            'total_tax_fee' =>isset($dutyFee)?$dutyFee : 0,
            'total_express_fee'=>$express_fee
        );
        return $this->success($return);
    }
    /**
     * 周年庆活动
     */
    private function getActivityAnniversary($data){

        $date = date('Ymd');
        foreach($data as $k => $val){
            if($k == $date){
                return 60;
            }
        }
    }
    /**
     * 查看物流
     *
     * $serviceRequest = new kaluliServiceClient();
    $serviceRequest->setMethod('order.getOrderLogistics');
    $serviceRequest->setVersion('1.0');
    $serviceRequest->setApiParam('express_number', 'awdawd');
    $serviceRequest->setApiParam('order_number', '12121211221');
    $serviceRequest->setApiParam('sort', 'asc');
    $serviceRequest->setUserToken($request->getCookie('u'));
    $response = $serviceRequest->execute();
     *
     */
    public function executeGetOrderLogistics()
    {
        $hupuUid = $this->getUser()->getAttribute('uid');
        $order_number = $this->getRequest()->getParameter('order_number');
        $sort = $this->getRequest()->getParameter('sort','asc');
        $express_number = $this->getRequest()->getParameter('express_number',0);

        try {
            if (empty($hupuUid)) {
                throw new Exception('未登录', 500);
            }
            if (empty($order_number)) {
                throw new Exception('参数有误', 400);
            }
            $return = array();
            $abroad = []; //存储海外物流信息
            $redis = sfContext::getInstance()->getDatabaseConnection('kaluliRedis');
            $redis->select(1);
            // $data = $redis->get('kaluli_'.$express_number);
            $data = [];
            if ($data){
                $return = unserialize($data);
            } else {
                //获取订单信息
                $orderObj = KaluliOrderTable::getInstance()->createQuery()->select()->where('order_number = ?',
                    $order_number)->andWhere('domestic_order_number = ?', $express_number)->fetchOne();

                if (!$orderObj || empty($express_number)) {
                    //获得国外的物流信息
                    $logisticsObj = KaluliOrderLogisticsTable::getInstance()->findOneByOrderNumber($order_number);
                    if(!empty($logisticsObj)){
                        $abroad_content = $logisticsObj->getAbroad();
                        if(!empty($abroad_content)){
                            $logistics_content = json_decode($abroad_content, 1);
                            $return = $abroad = $this->formatDomesticLogistics($logistics_content, $sort);
                        }

                        $redis->set('kaluli_'.$express_number, serialize($return), 3600);
                    }else{
                        throw new Exception('参数有误', 400);
                    }
                }else{

                    //获得国外的物流信息
                    $logisticsObj = KaluliOrderLogisticsTable::getInstance()->findOneByOrderNumber($order_number);
                    if(!empty($logisticsObj)){
                        $abroad_content = $logisticsObj->getAbroad();
                        if(!empty($abroad_content)){
                            $logistics_content = json_decode($abroad_content, 1);
                            $abroad = $this->formatDomesticLogistics($logistics_content, $sort);
                        }
                    }

                    //获取国内的物流
                    $domesticObj = KaluliOrderLogisticsTable::getInstance()->findOneByExpressNumber($express_number);
                    if ($domesticObj) {//已经存在了
                        $domestic_content = json_decode($domesticObj->getContent(), 1);

                        $return_res = $this->formatDomesticLogistics($domestic_content, $sort);
                        if(!$return_res){
                            $return = $abroad;
                        }else{
                            $return  = array_merge($abroad, $return_res);
                        }

                        $redis->set('kaluli_'.$express_number, serialize($return), 3600);
                    } else {//通知快递100
                        //获取主订单副表
                        //自主同步快递100接口关闭
                        // $mainOrderAttrObj = KaluliMainOrderAttrTable::getInstance()->findOneByOrderNumber($order_number);
                        // $address = json_decode($mainOrderAttrObj->getAddressAttr(), true);
                        // $tocity = $address['province'] . $address['city'];
                        // $this->submitKuaidi100($orderObj->get('domestic_express_type'), $express_number, $tocity,$order_number);//提交到快递100
                    }
                }



            }
        } catch (Exception $e){
            return $this->error($e->getCode(),$e->getMessage());
        }
        return $this->success(array('data'=>$return));
    }

    /**
     * 提交给快递100
     *
     * $serviceRequest = new kaluliServiceClient();
    $serviceRequest->setMethod('order.submitKuaidi');
    $serviceRequest->setVersion('1.0');
    $serviceRequest->setApiParam('express_number', 'awdawd');
    $serviceRequest->setApiParam('order_number', '12121211221');
    $serviceRequest->setApiParam('city', 'asc');
    $serviceRequest->setApiParam('company', 'asc');
    $response = $serviceRequest->execute();
     *
     */
    public function executeSubmitKuaidi()
    {
        $order_number = $this->getRequest()->getParameter('order_number');
        $city = $this->getRequest()->getParameter('city');
        $company = $this->getRequest()->getParameter('company');
        $express_number = $this->getRequest()->getParameter('express_number',0);
        try {
            if (empty($order_number) || empty($express_number) || empty($company) || empty($city)) {
                throw new Exception('参数有误', 400);
            }

            $this->submitKuaidi100($company,$express_number,$city,$order_number);//提交到快递100
        } catch (Exception $e){
            return $this->error($e->getCode(),$e->getMessage());
        }
    }

    /**
     * 取消订单
     *
     * $serviceRequest = new kaluliServiceClient();
    $serviceRequest->setMethod('order.cancelOrder');
    $serviceRequest->setVersion('1.0');
    $serviceRequest->setApiParam('order_number', 'awdawd');
    $serviceRequest->setUserToken($request->getCookie('u'));
    $response = $serviceRequest->execute();
     *
    lib/hc/kaluliService/warehouseKaluliService.class.php     */
    public function executeCancelOrder()
    {
        $hupuUid = $this->getUser()->getAttribute('uid');
        $order_number = $this->getRequest()->getParameter('order_number');
        try {
            if (empty($hupuUid)) {
                throw new Exception('未登录', 500);
            }
            if (empty($order_number)) {
                throw new Exception('参数有误', 400);
            }

            $mainOrderObj = KaluliMainOrderTable::getInstance()->createQuery()->where('order_number = ?',$order_number)->limit(1)->fetchOne();
            if (!$mainOrderObj){
                throw new Exception('参数有误', 400);
            }
            $exp = '未付款用户自主取消主订单';
            if ($mainOrderObj->getStatus() == 1){
//                $exp = '已付款用户自主取消主订单';
//                $mainOrderObj->setRefund($mainOrderObj->getTotalPrice());//退款
//
//                //插入退款记录
//                $refundDetail = new KaluliRefundDetail();
//                $refundDetail->set('order_number',$mainOrderObj->getOrderNumber());
//                $refundDetail->set('ibilling_number',$mainOrderObj->getIbillingNumber());
//                $refundDetail->set('refund',$mainOrderObj->getTotalPrice());
//                $refundDetail->set('pay_type',$mainOrderObj->getPayType());
//                $refundDetail->set('refund_remark',$exp);
//                $refundDetail->set('grant_username','用户自己');
//                $refundDetail->save();
                throw new Exception("已付款用户不能取消订单",400);
            }
            $mainOrderObj->setStatus(4);//取消
            $mainOrderObj->save();

            $couponObj = KllOrderActivityDetailTable::getInstance()->createQuery()->select()->where('order_number=?',$mainOrderObj->getOrderNumber())->andWhere('type = ?',0)->fetchOne();
            if($couponObj){
                $couponObj->set('refund_type',1);
                $couponObj->save();
                //礼品卡返回
                $serviceRequest = new kaluliServiceClient();
                $serviceRequest->setMethod('lipinka.rollback');
                $serviceRequest->setVersion('1.0');
                $serviceRequest->setApiParam('user_id', $mainOrderObj->getHupuUid());
                $attr = json_decode($couponObj->getAttr(),true);
                $serviceRequest->setApiParam('card', $attr['code']);
                $response = $serviceRequest->execute();
            }

            //取消日志
            $history = new KaluliOrderHistory();
            $history->setOrderNumber($mainOrderObj->getOrderNumber());
            $history->setHupuUid($mainOrderObj->getHupuUid());
            $history->setHupuUsername($mainOrderObj->getHupuUsername());
            $history->setType(4);
            $history->setExplanation($exp);
            $history->save();

            //记录到log文件
            $message = array(
                'message'=>'卡路里用户取消订单',
                'param'=>array(),
                'res'=>array(),
                'order_number'=>$order_number
            );
            kaluliLog::info('kaluli-cancel',$message);

            $serviceRequest = new kaluliServiceClient();
            //循环保存子订单
            $orderObj = KaluliOrderTable::getInstance()->createQuery()->where('order_number = ?', $order_number)->execute();
            foreach ($orderObj as $k => $v) {
                $v->setStatus(8);//用户取消
                if ($v->getPayStatus() == 1) {// 已付款
                    $v->setPayStatus(3);//退款中
                    $orderAttr = KaluliOrderAttrTable::getInstance()->findOneByOrderId($v->getId());
                    $orderAttr->setRefund($v->getTotalPrice());
                    $orderAttr->setRefundExpressFee($v->getExpressFee());
                    $orderAttr->setRefundPrice($v->getPrice());
                    $orderAttr->setRefundRemark($exp);
                    $orderAttr->save();
                }
                $v->save();
                $serviceRequest->setMethod('item.skuStock');
                $serviceRequest->setVersion('1.0');
                $serviceRequest->setApiParam('id',$v->getGoodsId());
                $serviceRequest->setApiParam('num',$v->getNumber());
                $item_type = $v->getPayStatus() == 1 ? 3 : 4;
                $serviceRequest->setApiParam('type',$item_type);//取消订单
                $serviceRequest->execute();
            }

        } catch (Exception $e){
            return $this->error($e->getCode(),$e->getMessage());
        }
        return $this->success();
    }

    /**
     * 确认收货
     *
     * $serviceRequest = new kaluliServiceClient();
    $serviceRequest->setMethod('order.confirmReceive');
    $serviceRequest->setVersion('1.0');
    $serviceRequest->setApiParam('order_id', '12121212');
    $serviceRequest->setApiParam('order_number', '12121212');
    $serviceRequest->setUserToken($request->getCookie('u'));
    $response = $serviceRequest->execute();
     *
     */
    public function executeConfirmReceive()
    {
        $hupuUid = $this->getUser()->getAttribute('uid');
        $order_number = $this->getRequest()->getParameter('order_number');
        $domestic_order_number = $this->getRequest()->getParameter('domestic_order_number');
        try {
            if (empty($hupuUid)) {
                throw new Exception('未登录', 500);
            }
            if (empty($domestic_order_number) || empty($order_number)) {
                throw new Exception('参数有误', 400);
            }

            $orderObj = KaluliOrderTable::getInstance()->createQuery()->select()
                ->where('order_number = ?',$order_number)
                ->andWhere('domestic_order_number = ?',$domestic_order_number)
                ->andWhere('hupu_uid = ?',$hupuUid)
                ->andWhere('status = ?',1)
                ->andWhere('pay_status = ?',1)
                ->execute();

            foreach($orderObj as $k=>$v) {
                $v->setStatus(2);
                $v->setReceiveTime(date('Y-m-d H:i:s'));
                $v->save();
                //确认收货日志
                $history = new KaluliOrderHistory();
                $history->setOrderNumber($v->getOrderNumber());
                $history->setHupuUid($v->getHupuUid());
                $history->setHupuUsername($v->getHupuUsername());
                $history->setType(3);
                $history->setExplanation('确认收货 (子订单id='.$v->getId().')');
                $history->save();
                //记录到log文件
                $message = array(
                    'message'=>'卡路里用户确认订单',
                    'param'=>array('domestic_order_number'=>$domestic_order_number,'order_number'=>$order_number),
                    'res'=>array(),
                    'order_number'=>$v->getOrderNumber()
                );
                kaluliLog::info('kaluli-receive',$message);
                //发送确认收货消息
                $message = array('order_number'=>$order_number, 'type'=>'finish','sub_order_number'=>$v->getId());
                kaluliFun::sendMqMessage('kalulicps.order.detail',$message,'kaluli_order_detail_deferred');
                //新人任务消息
                $taskMessage = array("userId"=>intval($hupuUid),"type"=>'confirm',"orderNumber"=>$order_number);
                kaluliFun::sendMqMessage("kaluli.newUser.regiter",$taskMessage,'kaluli_new_user');
            }
        } catch (Exception $e){
            return $this->error($e->getCode(),$e->getMessage());
        }
        return $this->success();
    }

    /**
     * 生成支付链接
     *
     * $serviceRequest = new kaluliServiceClient();
    $serviceRequest->setMethod('order.getPayLink');
    $serviceRequest->setVersion('1.0');
    $serviceRequest->setApiParam('uid', '12121212');//可以不传
    $serviceRequest->setApiParam('order_number', '12121212');
    $serviceRequest->setUserToken($request->getCookie('u'));//可以不传
    $response = $serviceRequest->execute();
     *
     */
    public function executeGetPayLink()
    {

        $hupuUid = $this->getRequest()->getParameter('uid',$this->getUser()->getAttribute('uid'));
        $order_number = $this->getRequest()->getParameter('order_number');
        $is_wap = $this->getRequest()->getParameter('is_wap',0);
        $is_app = $this->getRequest()->getParameter("is_app",0);
        $pay_data = $this->getRequest()->getParameter('pay_data',array());  //支付方式
        try {
            if (empty($hupuUid)) {
                throw new Exception('未登录', 500);
            }
            if (empty($order_number)) {
                throw new Exception('参数有误', 400);
            }
            $mainOrderObj = KaluliMainOrderTable::getInstance()->createQuery()->where('order_number = ?',$order_number)->andWhere('hupu_uid = ?',$hupuUid)->andWhere('status = ?',0)->limit(1)->fetchOne();
            $orderObj = KaluliOrderTable::getInstance()->createQuery()->where('order_number = ?',$order_number)->andWhere('hupu_uid = ?',$hupuUid)->andWhere('status = ?',0)->limit(1)->fetchOne();
            if (!$mainOrderObj){
                throw new Exception('参数有误', 400);
            }
            if($is_wap && empty($is_app)) { //wap客户端支付
                $_channelId = 'alipay_intl_wap';
                $_callbackurl = 'http://m.kaluli.com/order/paySuccess/'.$order_number;
            } elseif($is_wap && $is_app){
                $_channelId = 'alipay_intl_app';
                $_callbackurl = 'http://m.kaluli.com/order/paySuccess/'.$order_number;
            } else {
                $_channelId = 'alipay_intl_www';
                $_callbackurl = 'http://www.kaluli.com/order/paySuccess/'.$order_number;
            }

            //判断到底是什么付款类型
            if(empty($pay_data['pay_type']) || !array_key_exists($pay_data['pay_type'],$this->pay_type)) {
                $pay_data['pay_type'] = $this->pay_type['default'];
            }

            //微信支付判断
            if($pay_data['pay_type'] == 2) {
                if(empty($pay_data['wx_openid']) || empty($pay_data['wx_code'])) {
                    throw new Exception('微信支付，缺少参数', 503);
                }
               
            }


            $splitFundInfo = array(
                              'transIn'=>2088122696107222,//原账号：2088801432293851
                //'transIn'=>2088801432293851,//原账号：2088801432293851
                'amount'=>(sfConfig::get('sf_environment')!='dev')?number_format($mainOrderObj->getTotalPrice()*0.01,2,'.','') : 0.1,
                'currency'=>"CNY",
                'desc'=>"支付"
            );

            //增加逻辑,以最终付款方式确定下单方式
            $mainOrderObj->setPayType($pay_data['pay_type']);
            $mainOrderObj->save();
//            支付宝付款
            if($pay_data['pay_type'] == 1 || $pay_data['pay_type'] == 3) {
                $param = array(
                    'title' => str_replace('*','',$orderObj->getTitle()),
                    'userId' => $mainOrderObj->getHupuUid(),
                    'bizOrderNo' => $order_number,
                    'rmbAmount' => $mainOrderObj->getTotalPrice(),

                    'splitFundInfo'=>json_encode(array($splitFundInfo)),
                    'channelId' =>  $_channelId,
                    'callBackUrl' => $_callbackurl,
                    'notifyUrl' =>  (sfConfig::get('sf_environment')!='stg')?'http://www.kaluli.com/api/orderCallback/'.$order_number :'http://test.kaluli.com/api/orderCallback/'.$order_number ,
                    //TODO 预发布测试用
                    //'notifyUrl' => 'http://60.12.156.242/kaluli.php/api/orderCallback/'.$order_number,
                    'orderPrefix' => 'KLL',
                );
                kaluliLog::info("pay_param",$param);
                $pay_api = new kaluliPayApi();
                $json  = $pay_api->post('/api/order/createSplitRechargeOrder', $param);
                kaluliLog::info("pay_return_log",$json);
                if ($json){
                    return $this->success(array('pay_type'=>$pay_data['pay_type'],'pay_type_name'=>$this->pay_type[$pay_data['pay_type']],'pay_url'=>$json->url,'callBackUrl'=>$_callbackurl));
                } else {
                    throw new Exception('系统繁忙，请稍后再试', 502);
                }
            } else if ($pay_data['pay_type'] == 2) {
                //微信付款
                $param = array(
                    'title' => str_replace('*', '', $orderObj->getTitle()),
                    'userId' => $mainOrderObj->getHupuUid(),
                    'amount' => (sfConfig::get('sf_environment')!='dev')? ($mainOrderObj->getTotalPrice() * 100) : 10, //微信支付 是 long 类型 所以要乘以100
                    'ip' => FunBase::get_client_ip(),
                    'openId' => $pay_data['wx_openid'],
                    'tradeType' => 'JSAPI',
                    'channelId' => 'weixin',
                    'orderPrefix' => 'KLL',
                    'notifyUrl' =>  (sfConfig::get('sf_environment')!='stg')?'http://www.kaluli.com/api/orderCallback/'.$order_number :'http://test.kaluli.com/api/orderCallback/'.$order_number ,
                );
                $pay_api = new kaluliWeixinPayApi();
                $res = $pay_api->post('/api/order/createShiHuoRechargeTradeOrderByWechatMP', $param);
                kaluliLog::info("pay_return_log",$res);
                if (isset($res['returnCode']) && $res['returnCode'] == 'SUCCESS') {

                    $jsApi = new Kll_JsApi_pub();
                    $jsApi->setCode($pay_data['wx_code']);
                    $jsApi->setPrepayId($res['prepayId']);
                    $jsApiParameters = $jsApi->getParameters();
                    $_callbackurl = 'http://m.kaluli.com/order/paySuccess/'.$order_number;
                    return  $this->success(array('pay_type'=>2,'order_number'=>$order_number,'callbackurl'=>$_callbackurl, 'pay_type_name'=>$this->pay_type[$pay_data['pay_type']],'parame'=>$jsApiParameters));
                } else {
                    throw new Exception('系统繁忙，请稍后再试', 502);
                }
            }
        } catch (Exception $e){
            return $this->error($e->getCode(),$e->getMessage());
        }
    }











    /**
     * 申请退货退款
     *
     * $serviceRequest = new kaluliServiceClient();
    $serviceRequest->setMethod('order.applyReturn');
    $serviceRequest->setVersion('1.0');
    $serviceRequest->setApiParam('data', array());
    $serviceRequest->setUserToken($request->getCookie('u'));
    $response = $serviceRequest->execute();
     *
     */
    public function executeApplyReturn()
    {
        $v = $this->getRequest()->getParameter('v');
        $hupuUid = $this->getUser()->getAttribute('uid');
        $uname = $this->getUser()->getAttribute('username');//当前操作用户名
        $data = $this->getRequest()->getParameter('data',array());

        if(!isset($data['id'])) $data['id'] = '';//id
        if(!isset($data['order_id'])) $data['order_id'] = '';//订单id
        if(!isset($data['refund'])) $data['refund'] = '';//金额
        if(!isset($data['refund_remark'])) $data['refund_remark'] = '';//备注
        if(!isset($data['type'])) $data['type'] = '';//退款原因
        if(!isset($data['pic_attr'])) $data['pic_attr'] = '';//图片集合
        if(!isset($data['express_type'])) $data['express_type'] = '';//快递类型
        if(!isset($data['express_number'])) $data['express_number'] = '';//快递单号
        try {
            if (empty($hupuUid)) {
                throw new Exception('未登录', 500);
            }
            if (empty($data['order_id'])) {
                throw new Exception('参数有误', 400);
            }
            $orderObj = KaluliOrderTable::getInstance()->createQuery()->where('id = ?',$data['order_id'])->andWhere('hupu_uid = ?',$hupuUid)->andWhereIn('status',array(2,3,4))->andWhere('pay_status = ?',1)->fetchOne();
            if(!$orderObj){
                throw new Exception('参数有误', 400);
            }
            $refundObj = '';
            if($data['id']){//提交发货信息
                $refundObj = KaluliRefundApplyTable::getInstance()->createQuery()->where('id = ?',$data['id'])->andWhere('order_id = ?',$data['order_id'])->andWhere('status = ?',1)->limit(1)->fetchOne();
                if(!$refundObj){
                    throw new Exception('参数有误', 400);
                }
            } else {//提交申请退货
                if(time() - strtotime($orderObj->getReceiveTime())>3600*24*7) {
                    throw new Exception('包裹签收超过7天后不可申请退货', 402);
                }
                if(($orderObj->getPrice())*($orderObj->getNumber())<$data['refund'] || $orderObj->getTotalPrice()<$data['refund']){
                    throw new Exception('只能退商品价格且退款额不能大于订单总额', 401);
                }
            }

            if($refundObj){//申请通过后填写退货快递信息
                $refundObj->set('express_type',$data['express_type']);
                $refundObj->set('express_number',$data['express_number']);
                $refundObj->set('status',2);
                $refundObj->save();

                $orderObj->setStatus(5);//待卡路里收货
                $orderObj->save();

                //审核日志
                $history = new KaluliOrderHistory();
                $history->setOrderNumber($refundObj->getOrderNumber());
                $history->setHupuUid($orderObj->getHupuUid());
                $history->setHupuUsername($orderObj->getHupuUsername());
                $history->setType(8);
                $history->setExplanation('填写退货物流信息');
                $history->save();

                //记录到log文件
                $message = array(
                    'message'=>'填写退货物流信息',
                    'param'=>array(),
                    'res'=>array(),
                    'order_number'=>$refundObj->getOrderNumber()
                );
                kaluliLog::info('kaluli-applyReturn-express',$message);
            } else {//提出退货申请
                $refundObj = new KaluliRefundApply();
                $refundObj->set('order_number',$orderObj->getOrderNumber());
                $refundObj->set('order_id',$data['order_id']);
                $refundObj->set('refund',$data['refund']);
                $refundObj->set('refund_remark',$data['refund_remark']);
                if($data['pic_attr']) $refundObj->set('pic_attr',json_encode($data['pic_attr']));
                $refundObj->set('type',$data['type']);
                $refundObj->save();

                $orderObj->setStatus(3);//申请退货
                $orderObj->save();

                //审核日志
                $history = new KaluliOrderHistory();
                $history->setOrderNumber($refundObj->getOrderNumber());
                $history->setHupuUid($orderObj->getHupuUid());
                $history->setHupuUsername($orderObj->getHupuUsername());
                $history->setType(6);
                $history->setExplanation('申请退货');
                $history->save();

                //记录到log文件
                $message = array(
                    'message'=>'申请退货',
                    'param'=>array(),
                    'res'=>array(),
                    'order_number'=>$refundObj->getOrderNumber()
                );
                kaluliLog::info('kaluli-applyReturn',$message);
            }

        } catch (Exception $e){
            return $this->error($e->getCode(),$e->getMessage());
        }
        $num = 'KR'.sprintf('%010s', $refundObj->getId());
        return $this->success(array('num'=>$num));
    }


    /**
     * 判断用户是否真是下过单
     */
    public function executeCheckUserOrders() {
        $hupuUid =  $this->getRequest()->getParameter('uid',NULL);
        //0待付款 1待发货 2待收货 3待评价 4取消 5退款成功 6交易成功
        try {
            if (empty($hupuUid)) throw new Exception('未登录', 500);
            //0订单生成 1已发货 2订单完成 3退货处理中 4待用户发货 5待卡路里收货 6已退货 7订单关闭 8用户取消 9识货取消 10拒绝退货
            // 0待付款 1已支付 2待退款 3退款中 4退款完成 5退款失败
            $info = KaluliOrderTable::getInstance()
                ->createQuery()
                ->select('id')
                ->andWhere('hupu_uid = ?',$hupuUid)
                ->andWhere('pay_status <> 0')
                ->WhereIn('status',array(0,1,2,3,4,5,6,7,9,10))
                ->limit(1)
                ->fetchOne();



            if($info)  {
                $isemptyorder = 0;
            } else {
                $isemptyorder = 1;
            }
        } catch (Exception $e){
            return $this->error($e->getCode(),$e->getMessage());
        }
        return $this->success(array('isemptyorder'=>$isemptyorder));

    }





    /**
     * 获取订单退货详情
     *
     * $serviceRequest = new kaluliServiceClient();
    $serviceRequest->setMethod('order.applyReturnDetail');
    $serviceRequest->setVersion('1.0');
    $serviceRequest->setApiParam('order_id', 12);
    $serviceRequest->setUserToken($request->getCookie('u'));
    $response = $serviceRequest->execute();
     *
     */
    public function executeApplyReturnDetail()
    {
        $hupuUid = $this->getUser()->getAttribute('uid');
        $order_id = $this->getRequest()->getParameter('order_id');
        try {
            if (empty($hupuUid)) {
                throw new Exception('未登录', 500);
            }
            $orderObj = KaluliOrderTable::getInstance()->createQuery()->where('id = ?',$order_id)->andWhere('hupu_uid = ?',$hupuUid)->fetchOne();
            if(!$orderObj){
                throw new Exception('参数有误', 400);
            }
            $refundObj = KaluliRefundApplyTable::getInstance()->createQuery()->andWhere('order_id = ?',$order_id)->limit(1)->fetchOne();
            if(!$refundObj){
                throw new Exception('没有数据', 502);
            }
            $return['id'] = $refundObj->getId();
            $num = 'KR'.sprintf('%010s', $refundObj->getId());
            $return['num'] = $num;
            $return['order_number'] = $refundObj->getOrderNumber();
            $return['order_id'] = $refundObj->getOrderId();
            $return['refund'] = $refundObj->getRefund();
            $return['refund_remark'] = $refundObj->getRefundRemark();
            $return['check_remark'] = $refundObj->getCheckRemark();
            $return['express_type'] = $refundObj->getExpressType();
            $return['express_number'] = $refundObj->getExpressNumber();
            $return['check_remark'] = $refundObj->getCheckRemark();
            $return['pic_attr'] = $refundObj->get('pic_attr');
            $return['type'] = $refundObj->getType();
            $return['status'] = $refundObj->getStatus();
        } catch (Exception $e){
            return $this->error($e->getCode(),$e->getMessage());
        }
        return $this->success(array('data'=>$return));
    }

    /**
     * 提交给快递100 (后期接收消息那里加上安全验证)
     * @param $companyType
     * @param $number
     * @param $city
     * @param $order_number
     * @return bool
     */
    public function submitKuaidi100($company,$number,$city,$order_number){
        $url = 'http://www.kuaidi100.com/poll';
        $param['company'] = FunBase::getLogisticsCode($company);
        $param['number'] = $number;
        $param['to'] = $city;
        $param['key'] = 'aPScHmND5040';
        $param['parameters']['callbackurl'] = 'https://www.kaluli.com/api/push?number='.$param['number'].'&company='.$param['company'].'&city='.$param['to'].'&ordsn='.$order_number;
        $param['parameters']['salt'] = '';
        $param['parameters']['resultv2'] = 0;
        $data['schema'] = 'json';
        $data['param'] = json_encode($param);
        $result = tradeCommon::getContents($url,$data,10,'post');
        $res = json_decode($result,1);
        //记录到log文件
        $message = array(
            'message'=>'卡路里订单物流提交快递100',
            'param'=>$param,
            'res'=>$res,
            'order_number'=>$order_number
        );
        kaluliLog::info('kaluli-logistics',$message);
        return true;
    }
    

    //获取物流公司对应的编码
    private function getLogisticsCode($company){
        if(!$company) return false;
        switch ($company) {
            case 1:
                return 'shentong';
                break;
            case 2:
                return 'shunfeng';
                break;
            case 3:
                return 'ems';
                break;
            case 4:
                return 'yuantong';
                break;
            case 5:
                return 'yunda';
                break;
            case 6:
                return 'zhongtong';
                break;
            case 7:
                return 'tiantian';
                break;
            case 8:
                return 'huitongkuaidi';
                break;
            case 9:
                return 'zhaijisong';
                break;
            case 33:
                return 'suer';
                break;
            default:
                return '';
                break;
        }
    }

    //拼接快递100的物流信息
    private function formatDomesticLogistics($data,$sort){
        $return  = array();
        if(!$data) return false;
        foreach($data as $k=>$v){
            if($v['time']){
                $return[$k]['event'] = $v['context'];
                $return[$k]['time'] = $v['time'];
            }
        }
        if(strtolower($sort) == 'desc') assort($return);
        return $return;
    }



    //保存日志
    private function saveLog($data){
        $historyObj = new KaluliOrderHistory();
        if (isset($data['status'])) $historyObj->setType($data['status']);
        if (isset($data['order_number'])) $historyObj->setOrderNumber($data['order_number']);
        if (isset($data['hupu_uid'])) $historyObj->setHupuUid($data['hupu_uid']);
        if (isset($data['hupu_username'])) $historyObj->setHupuUsername($data['hupu_username']);
        if (isset($data['explanation'])) $historyObj->setExplanation($data['explanation']);
        if (isset($data['grant_uid'])) $historyObj->setGrantUid($data['grant_uid']);
        if (isset($data['grant_username'])) $historyObj->setGrantUsername($data['grant_username']);;
        $historyObj->save();
        return $historyObj->getId();
    }

    //判断活动时间<
    private function getActivityTimeFlag()
    {
        $time = time();
        if ($time > strtotime('2015-07-29 00:00:00') && $time < strtotime('2015-08-10 00:00:00')) {
            return true;
        }
        return false;
    }

    //判断可以优惠多少钱
    private function getActivityCouponFee($total_price)
    {
        if ($this->getActivityTimeFlag()){
            if ($total_price >= 599) return 60;
            if ($total_price >= 499) return 50;
            if ($total_price >= 399) return 40;
            if ($total_price >= 299) return 30;
        }
        return 0;
    }

    //判断满足哪些活动
    private function getActivityList($total_price)
    {
        $return = array();
        if ($this->getActivityTimeFlag()){
            if ($total_price >= 599) {
                $return['list'][0]['title'] = '满￥299减￥30';
                $return['list'][0]['flag'] = false;
                $return['list'][1]['title'] = '满￥399减￥40';
                $return['list'][1]['flag'] = false;
                $return['list'][2]['title'] = '满￥499减￥50';
                $return['list'][2]['flag'] = false;
                $return['list'][3]['title'] = '满￥599减￥60';
                $return['list'][3]['flag'] = true;

                $return['now']['title'] = '满￥599减￥60';
                $return['now']['price'] = 60;
            } elseif ($total_price >= 499){
                $return['list'][0]['title'] = '满￥299减￥30';
                $return['list'][0]['flag'] = false;
                $return['list'][1]['title'] = '满￥399减￥40';
                $return['list'][1]['flag'] = false;
                $return['list'][2]['title'] = '满￥499减￥50';
                $return['list'][2]['flag'] = true;
                $return['list'][3]['title'] = '满￥599减￥60';
                $return['list'][3]['flag'] = false;

                $return['now']['title'] = '满￥499减￥50';
                $return['now']['price'] = 50;
            } elseif ($total_price >= 399) {
                $return['list'][0]['title'] = '满￥299减￥30';
                $return['list'][0]['flag'] = false;
                $return['list'][1]['title'] = '满￥399减￥40';
                $return['list'][1]['flag'] = true;
                $return['list'][2]['title'] = '满￥499减￥50';
                $return['list'][2]['flag'] = false;
                $return['list'][3]['title'] = '满￥599减￥60';
                $return['list'][3]['flag'] = false;

                $return['now']['title'] = '满￥399减￥40';
                $return['now']['price'] = 40;
            } elseif ($total_price >= 299) {
                $return['list'][0]['title'] = '满￥299减￥30';
                $return['list'][0]['flag'] = true;
                $return['list'][1]['title'] = '满￥399减￥40';
                $return['list'][1]['flag'] = false;
                $return['list'][2]['title'] = '满￥499减￥50';
                $return['list'][2]['flag'] = false;
                $return['list'][3]['title'] = '满￥599减￥60';
                $return['list'][3]['flag'] = false;

                $return['now']['title'] = '满￥299减￥30';
                $return['now']['price'] = 30;
            } else {
                $return['list'][0]['title'] = '满￥299减￥30';
                $return['list'][0]['flag'] = false;
                $return['list'][1]['title'] = '满￥399减￥40';
                $return['list'][1]['flag'] = false;
                $return['list'][2]['title'] = '满￥499减￥50';
                $return['list'][2]['flag'] = false;
                $return['list'][3]['title'] = '满￥599减￥60';
                $return['list'][3]['flag'] = false;

                $return['now'] = array();
            }
        }
        return $return;
    }


    //判断每日爆款活动时间
    private function getDailyActivityTimeFlag()
    {
        $time = time();
        if ($time > strtotime('2015-06-22 00:00:00') && $time < strtotime('2015-06-23 00:00:00')) {
            return 1;
        }
        if ($time > strtotime('2015-06-23 00:00:00') && $time < strtotime('2015-06-24 00:00:00')) {
            return 2;
        }
        if ($time > strtotime('2015-06-24 00:00:00') && $time < strtotime('2015-06-25 00:00:00')) {
            return 3;
        }
        if ($time > strtotime('2015-06-25 00:00:00') && $time < strtotime('2015-06-26 00:00:00')) {
            return 4;
        }
        if ($time > strtotime('2015-06-26 00:00:00') && $time < strtotime('2015-06-27 00:00:00')) {
            return 5;
        }
        return 0;
    }

    //判断每日爆款可以优惠多少钱
    private function getDailyActivityCouponFee($pid, $gid, $num)
    {
        $type = $this->getDailyActivityTimeFlag();

        if ($type == 0) return 0;

        switch ($type) {
            case 1 :
                if ($pid == 63) {
                    return 15*$num;
                }
                break;
            case 2 :
                if ($pid == 64) {
                    return 20*$num;
                }
                break;
            case 3 :
                if ($pid == 37) {
                    return 30*$num;
                }
                break;
            case 4 :
                if ($pid == 1) {
                    if (in_array($gid, array(75))) {
                        return 30*$num;
                    } elseif (in_array($gid, array(76, 77, 78))){
                        return 20*$num;
                    }
                }
                break;
            case 5 :
                if ($pid == 28) {
                    if (in_array($gid, array(122))) {
                        return 15*$num;
                    } elseif (in_array($gid, array(123))){
                        return 10*$num;
                    }
                }
                break;
            default :
                return 0;
        }
        return 0;
    }

    //判断满足哪些活动
    private function getDailyActivityList($coupons_fee, $num)
    {
        $return = array();
        if ($this->getDailyActivityTimeFlag() && $coupons_fee > 0){
            $return['list'][0]['title'] = '每日爆款商品';
            $return['list'][0]['flag'] = true;
            $return['now']['title'] = '指定商品减￥'.($coupons_fee / $num);
            $return['now']['price'] = $coupons_fee;
        }
        return $return;
    }

    //指定商品买几送几
    private function getActivityBuyGiveCouponFee($gid, $num)
    {
        if ($this->getActivityTimeFlag()){
            if ($gid == 122 || $gid == 145) {
                if ($num == 1) {
                    return 10;
                } elseif ($num == 2) {
                    return 25;
                } else {
                    return 40;
                }
            } elseif ($gid == 130) {
                if ($num == 1) {
                    return 15;
                } elseif ($num == 2) {
                    return 35;
                } else {
                    return 55;
                }
            }
        }
        return 0;
    }

    //判断满足哪些活动
    private function getBuyGiveActivityList($coupons_fee, $num, $gid)
    {
        $return = array();
        if ($this->getActivityTimeFlag() && $coupons_fee > 0){
            if ($gid == 122 || $gid == 145) {
                if ($num == 1) {
                    $return['list'][0]['title'] = '爆款商品买一件减'.$coupons_fee;
                    $return['list'][0]['flag'] = true;
                    $return['list'][1]['title'] = '爆款商品买二件减25';
                    $return['list'][1]['flag'] = false;
                    $return['list'][2]['title'] = '爆款商品买三件减40';
                    $return['list'][2]['flag'] = false;
                    $return['now']['title'] = '买一件减'.$coupons_fee;
                    $return['now']['price'] = $coupons_fee;
                } elseif ($num == 2) {
                    $return['list'][0]['title'] = '爆款商品买一件减'.$coupons_fee;
                    $return['list'][0]['flag'] = false;
                    $return['list'][1]['title'] = '爆款商品买二件减25';
                    $return['list'][1]['flag'] = true;
                    $return['list'][2]['title'] = '爆款商品买三件减40';
                    $return['list'][2]['flag'] = false;
                    $return['now']['title'] = '买二件减'.$coupons_fee;
                    $return['now']['price'] = $coupons_fee;
                } else {
                    $return['list'][0]['title'] = '爆款商品买一件减'.$coupons_fee;
                    $return['list'][0]['flag'] = false;
                    $return['list'][1]['title'] = '爆款商品买二件减25';
                    $return['list'][1]['flag'] = false;
                    $return['list'][2]['title'] = '爆款商品买三件减40';
                    $return['list'][2]['flag'] = true;
                    $return['now']['title'] = '买三件减'.$coupons_fee;
                    $return['now']['price'] = $coupons_fee;
                }
            } elseif ($gid == 130) {
                if ($num == 1) {
                    $return['list'][0]['title'] = '爆款商品买一件减'.$coupons_fee;
                    $return['list'][0]['flag'] = true;
                    $return['list'][1]['title'] = '爆款商品买二件减35';
                    $return['list'][1]['flag'] = false;
                    $return['list'][2]['title'] = '爆款商品买三件减55';
                    $return['list'][2]['flag'] = false;
                    $return['now']['title'] = '买一件减'.$coupons_fee;
                    $return['now']['price'] = $coupons_fee;
                } elseif ($num == 2) {
                    $return['list'][0]['title'] = '爆款商品买一件减'.$coupons_fee;
                    $return['list'][0]['flag'] = false;
                    $return['list'][1]['title'] = '爆款商品买二件减35';
                    $return['list'][1]['flag'] = true;
                    $return['list'][2]['title'] = '爆款商品买三件减55';
                    $return['list'][2]['flag'] = false;
                    $return['now']['title'] = '买二件减'.$coupons_fee;
                    $return['now']['price'] = $coupons_fee;
                } else {
                    $return['list'][0]['title'] = '爆款商品买一件减'.$coupons_fee;
                    $return['list'][0]['flag'] = false;
                    $return['list'][1]['title'] = '爆款商品买二件减35';
                    $return['list'][1]['flag'] = false;
                    $return['list'][2]['title'] = '爆款商品买三件减55';
                    $return['list'][2]['flag'] = true;
                    $return['now']['title'] = '买三件减'.$coupons_fee;
                    $return['now']['price'] = $coupons_fee;
                }
            }
        }
        return $return;
    }
    private function _useBenefits($order_number, $card_id = 0){

        if($order_number && $card_id){
            $benefits = KllMemberBenefitsTable::getInstance()->findOneById($card_id);
            if(!empty($benefits)){
                $times = $benefits->getTimes();
                if($times != 0 ){
                    $times = $times-1;
                    $benefits->setTimes($times)->save();
                    $benefits_activity = new KllMemberBenefitsOrder();
                    $benefits_activity->setMbId($card_id)->setOrderNumber($order_number)->setStatus(1)->save();
                }
            }
            $message = array(
                'message' => '会员权益的使用',
                'param' => '',
                'res' => array(),
                'order_number' => $order_number
            );
            kaluliLog::info('kaluli-benefit', $message);
        }
    }
}