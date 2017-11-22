<?php
/**
 * 卡路里 管易同步类
 * 梁天  2015-08-31
 */
class KllEdbSyncService  {
    //同步方法白名单
    public static $_action_name = array('cangku','spkucun',"sku_inventory",'itemsSku','order_create','order_express');
    //验证签名
    public static $_sign = array('887A5993201DCEAF04C0719238561F8F', '24F1F2331D6B9A21317FCC14FC7FF712');
    public static $_redis_key = "kaluli.erp.edb.sync.";
    // 店铺编号
    public static  $KALULI_SHOP = 4;

    //卡路里仓库编码
    public static $KALULI_WARE = 1;

    //默认快递公司名称
    public static $KALULI_DEFAULT_EXPRESS_TEXT = '圆通';

    public static $ERP_NAME = 'Edb';

    //地址过滤的直辖市
    public static $_modus = array("北京","上海","天津","重庆");

    //单例
    static public function getInstance() {
        static $handier = NULL;
        if (empty($handier)) {
            $handier = new self();
        }
        return $handier;
    }


    //更新
    public function sync($_type,$args = array()) {
        if(!in_array($_type,self::$_action_name)) {
            throw new sfException('更新失败：未知的更新1！');
        }
        $_fun = $_type.'_action';
        if(method_exists($this,$_fun)) {
           $res =  $this->$_fun($args);
        } else {
            throw new sfException('更新失败：'.$_fun.'不存在！');
        }
        //记录 最后更新日志
        $_time = $this->uploadTime($_type);
        $_return = array();
        $_return['type'] = $_type;
        $_return['update_time'] = date('Y-m-d H:i:s',$_time);
        $_return['humanize_time'] = '刚刚';
        $_return['data'] = $res;
        return $_return;
    }

    /**
     * 组装EDB的数据
     * author kworm
     */
    protected function getWareData(){
        $wareData = KaluliWarehousesTable::getInstance()->getWare();
        return $wareData;
    }
    protected function getOrderData($_id, $_source){
        if($_source == 2){
            $_order = KllBBOrderTable::getInstance()->getOne($_id,false);
            if(empty($_order))  throw new sfException("错误：订单不存在！");

            $_order = $_order->toArray();
            $_order['depot_type'] = 19;
            $_order['hupu_uid'] = 0;
            $_order['marketing_fee'] = $_order['express_fee'] = $_order['domestic_express_type']= 0;
            
            

        }else{
            $_order = KaluliOrderTable::getInstance()->getOne($_id,false);
            if(empty($_order))  throw new sfException("错误：订单不存在！");
            $_order = $_order->toArray();
        }
        
        return $_order;
    }
    protected function getGoodsData($_goods_id, $_source){
        if($_source == 2){
            $_goodsData = KaluliItemSkuTable::getInstance()->findByGoodsNo($_goods_id);
        }else{
            $_goodsData = KaluliItemSkuTable::getInstance()->findById($_goods_id);
        }
        
        $_goodsData = $_goodsData->toArray();
        $_goodsData = $_goodsData[0];
        $_goodsData['attr'] = unserialize($_goodsData['attr']);
        return $_goodsData;   
    }
    protected function getGoodsInfoData($_item_id, $_source){

        $_goods = KaluliItemTable::getInstance()->findById($_item_id);
        $_goods = $_goods->toArray();
        $_goods = $_goods[0];
        return $_goods;
    }
    protected function getMainOrderData($_order_number, $_source){
        if($_source == 2){
            $_mainOrder = KllBBMainOrderTable::getInstance()->findByOrderNumber($_order_number);
            $_mainOrder = $_mainOrder->toArray();
            $_mainOrder = $_mainOrder[0];
            $_mainOrder['ibilling_number'] = $_mainOrder['flow_number'];
        }else{
            $_mainOrder = KaluliMainOrderTable::getInstance()->findByOrderNumber($_order_number);
            $_mainOrder = $_mainOrder->toArray();
            $_mainOrder = $_mainOrder[0];

        }
        // $_mainOrder = KaluliMainOrderTable::getInstance()->findByOrderNumber($_order_number);
        
        return $_mainOrder;
    }
    protected function getMainOrderAttrData($_order_number, $_source){
        if($_source == 2){
            $_mainOrderAttr = KllBBMainOrderAttrTable::getInstance()->findByOrderNumber($_order_number);
            $_mainOrderAttr = $_mainOrderAttr->toArray();
            $_mainOrderAttr = $_mainOrderAttr[0];
            $_mainOrderAttr['name'] = $_mainOrderAttr['real_name'];
            $_mainOrderAttr['postcode'] = $_mainOrderAttr['postal_code'];
            $_mainOrderAttr['remark'] = '';
            $_mainOrderAttr['address_attr'] = [
                "name" => $_mainOrderAttr['real_name'], 
                "postcode" =>  $_mainOrderAttr['postal_code'], 
                "province" => $_mainOrderAttr['province'], 
                "city" => $_mainOrderAttr['city'], 
                "area" =>  $_mainOrderAttr['area'], 
                "mobile" =>  $_mainOrderAttr['mobile'], 
                "region" => $_mainOrderAttr['province'].$_mainOrderAttr['city'].$_mainOrderAttr['area'], 
                "street"=> $_mainOrderAttr['address'], 
                "identity_number"=> $_mainOrderAttr['card_code']
            ];
        }else{
            $_mainOrderAttr = KaluliMainOrderAttrTable::getInstance()->findByOrderNumber($_order_number);
            $_mainOrderAttr = $_mainOrderAttr->toArray();
            $_mainOrderAttr = $_mainOrderAttr[0];
            $_mainOrderAttr['address_attr'] = json_decode($_mainOrderAttr['address_attr'],true);
        }
        // $_mainOrderAttr = KaluliMainOrderAttrTable::getInstance()->findByOrderNumber($_order_number);

        return $_mainOrderAttr;
    }
    protected function setWareData($_id, $_source){
        if($_source == 2){
            $_orderObj = KllBBOrderTable::getInstance()->getOne($_id,false);
            $_orderObj->setWareStatus(KaluliOrderWarelog::$SYNC_STATUS_CREATE);
            $_orderObj->save();
        }else{
            $_orderObj = KaluliOrderTable::getInstance()->getOne($_id,false);
            $_orderObj->setWareStatus(KaluliOrderWarelog::$SYNC_STATUS_CREATE);
            $_orderObj->save();
        }
        
    }


    /**
     * 添加新订单  1578
     */
    public function order_create_action($args = array()) {
        //来源，BB项目是2.平台是1；
        $_source = $args['source'];
        $_id = (int)$args['_id'];
        if(empty($_id)) throw new sfException("添加订单错误：订单ID不存在！");
        //添加签名机制
        $_sign = $args['sign'];
        if(in_array($_sign, self::$_sign)){
            $message = array(
                'message'=>'edb文件',
                'param'=>$_sign.'--'.$_id,
                'res'=>array(),
            );
            kaluliLog::info('kaluli-edb-request',$message);
        }else{
             throw new sfException("非法请求");
        }
        //获取仓库代码
        $wareData = $this->getWareData();
        //子订单
        $_order = $this->getOrderData($_id, $_source);

        if(empty($_order))  throw new sfException("错误：订单不存在！");
        //判断订单是否可以同步
        if($_order['ware_status'] !=  KaluliOrderWarelog::$SYNC_STATUS_NO) {
            throw new sfException("错误：订单已经同步过了！");
        }
        if($_order['status'] !=  KaluliOrder::$CREATE_STATUS) {
            throw new sfException("错误：订单不是未发货状态！");
        }
        //BB项目的goods_id 应该是商品条形码
        $goods_id = $_order['goods_id'];
        if($_source == 2){
            $goods_id = $_order['product_code'];
        }
        //获取商品SKU信息
        $_goodsData = $this->getGoodsData($goods_id, $_source);
       
        //获取商品信息
        $_goods = $this->getGoodsInfoData($_goodsData['item_id'], $_source);
        
        $_order_number = $_order['order_number'];

        //主订单
        $_mainOrder = $this->getMainOrderData($_order_number, $_source);
       

        //主订单attr
        $_mainOrderAttr = $this->getMainOrderAttrData($_order_number, $_source);
       
        //判断仓库
         if(isset($wareData[$_order['depot_type']]['code']) && !empty($wareData[$_order['depot_type']]['code'])) {
             $_ware_code =  $wareData[$_order['depot_type']]['code'];
         } else {
            $_ware_code = self::$KALULI_WARE;
        }
        $_time  = time();
        if(!empty($args['date'])) {
            $_time = $args['date'];
            if (time() >= $args['date']) $_time = time();
        }
        $_time = date('YmdHis',$_time);
        $_time = substr($_time,0,12);
        //发送到E店宝
        try {
            $edb = KaluliFun::getObject('KllErpDriver'.self::$ERP_NAME);
            $edb->setConfig('timestamp',$_time);
            $api = $edb->builder('edbTradeAdd');
            $api->setOutTid( $this->_setOption($_mainOrder['order_number'].'-'.$_order['id']) );  //外部订单号
            $store = self::getEdbStore($_source);
            $api->setShopId($store);  //店铺ID

            $api->setStorageId($_ware_code);  //仓库ID

            $api->setBuyerId($this->_setOption($_order['hupu_uid']));  //买家ID

            $api->setConsignee($this->_setOption($_mainOrderAttr['name']));  //收货人姓名
            $api->setPostcode($this->_setOption($_mainOrderAttr['postcode']));  //收货人邮编
            $api->setMobilPhone($this->_setOption($_mainOrderAttr['mobile']));  //收货人手机
            $api->setPrivince($this->_setOption($_mainOrderAttr['province']));  //收货人省份
            $api->setCity($this->_setOption($_mainOrderAttr['city']));  //收货人城市
            $_address = $_mainOrderAttr['address_attr']['province'].' '.$this->fixAddress($_mainOrderAttr['address_attr']['city'],"city").' '.$_mainOrderAttr['address_attr']['area'].' '.$_mainOrderAttr['address_attr']['street'];
            $api->setAddress($this->_setOption( $_address));  //收货人地址


            $api->setOrderType("正常订单");  //默认订单类型
            $api->setProcessStatus('未确认');  //默认处理状态
            $api->setDeliverStatus('未发货');  //默认发货状态
            $api->setDeliverStatus('未发货');  //默认发货状态
            $api->setPayStatus('已付款');  //默认付款状态

            $api->setOrderTotalMoney($this->_setOption($_order['total_price']-$_order['marketing_fee']));  //订单总金额
            $api->setProductTotalMoney($this->_setOption($_order['total_price']-$_order['express_fee']-$_order['marketing_fee']));  //产品总金额
            $api->setCostPrice($this->_setOption(number_format($_order['price'] - $_order['marketing_fee']/$_order['number'],2,'.','')));   //成交价格

            $api->setOutPayNo($this->_setOption($_mainOrder['ibilling_number']));  //支付宝流水号
            $api->setOrderDate(date('Y-m-d H:i:s',time()));   //订货日期
            $api->setPayDate($this->_setOption($_mainOrder['pay_time']));  //付款日期


            $api->setBuyerMsg($_mainOrderAttr['remark'].'   【身份证号】'.$_mainOrderAttr['address_attr']['identity_number']);  //买家留言
            //$api->setSellerRemark('身份证号'.$_mainOrderAttr['address_attr']['identity_number']);  //客服备注 身份证号


            $api->setShipMethod($this->_setOption('快递'));  //配送类型
            $_express = isset(KaluliOrder::$EXPRESS_TYPE[$_order['domestic_express_type']]) ? KaluliOrder::$EXPRESS_TYPE[$_order['domestic_express_type']] : self::$KALULI_DEFAULT_EXPRESS_TEXT;
            $api->setExpress($_express);  //默认快递文字
            $api->setSellerRemark($_express);  //客服备注 快递公司

            $api->setActualFreightPay($this->_setOption($_order['express_fee']));  //实际付运费
            $api->setActualFreightGet($this->_setOption($_order['express_fee']));  //实收运费
            $api->setProductFreight($this->_setOption($_order['express_fee']));  //产品运费


            $api->setBarCode($_goodsData['code']);  //条形码
            $api->setProductTitle($_goods['title']);  //网店名称
            $api->setStandard($_goodsData['ware_sku']);   //网店规格
            $api->setOrderGoodsNum($_order['number']);   //订货数量

            $api->setXmlValues();
            $message = array(
                'message'=>'edb文件',
                'param'=>$api->xmlValues,
                'res'=>array(),
            );
            kaluliLog::info('kaluli-edbXmlSend',$message);

            $res  = $edb->exec($api,'post');
            $result = json_decode($res['result'],true);
            kaluliLog::info("kaluli-edbResponse",$result);
            if(empty($result)) throw new sfException("参数传递不完整！");
            if(isset($result['error_code']) || isset($result['error_msg'])) {
                $_msg = ' 错误代码：';
                $_msg .= isset($result['error_code']) ? $result['error_code'] : '【没有错误代码】 错误信息：';
                $_msg .= ' 错误消息：';
                $_msg .= isset($result['error_msg']) ? $result['error_msg'] : '【没有错误信息】';
                throw new sfException($_msg);
            }
            if(empty($result['Success']))  throw new sfException("未知错误！");
            //增加错误判断逻辑，记录发货错误原因
            if($result['Success']) {
                if($result['Success']["items"]['item'][0]['is_success'] == "false" || $result['Success']["items"]['item'][0]['is_success'] == "False") {
                    throw new sfException($result['Success']["items"]['item'][0]['response_Msg']);
                }
            }
            $this->setWareData($_order['id'], $_source);
            //写入财审信息
            $_mainOrderFinance = KaluliMainOrderTable::getInstance()->findByOrderNumber($_order_number);
            $_mainOrderFinance->setFinanceAudit(2)->save();
            //写入订单order
            if($_source == 1){
                $_orderAttr = KaluliOrderAttrTable::getInstance()->getOneByOrderId($_order['id'],true);
                $_orderAttr->setWareType(1);
                $_orderAttr->setWareId(isset($res['id']) ? $res['id'] : '');
                $_orderAttr->setWareCode(isset($res['code']) ? $res['code'] : '');
                $_orderAttr->save();
            }
            //写入BB项目的订单
            if($_source == 2){
                $_orderObj = KllBBOrderTable::getInstance()->findOneById($_order['id']);
                $_orderObj->setWareStatus(1)->save();
            }
            //写入订单log
            $_orderWareLog = new KaluliOrderWarelog();
            $_orderWareLog->setOrderNumber($_order_number);
            $_orderWareLog->setOrderId($_order['id']);
            $_orderWareLog->setMsg("【系统】订单自动同步到E店保平台成功！");
            $_orderWareLog->setWareType(1);
            $_orderWareLog->setStatus(KaluliOrderWarelog::$SYNC_OK);
            $_orderWareLog->setWareOrderType(KaluliOrderWarelog::$SYNC_STATUS_CREATE);
            $_orderWareLog->save();
        }catch(sfException $e) {
            $_orderWareLog = new KaluliOrderWarelog();
            $_orderWareLog->setOrderNumber($_order_number);
            $_orderWareLog->setOrderId($_order['id']);
            $_orderWareLog->setMsg("【系统】同步失败，原因：".$e->getMessage());
            $_orderWareLog->setWareType(1);
            $_orderWareLog->setStatus(KaluliOrderWarelog::$SYNC_FAIL);
            $_orderWareLog->setWareOrderType(KaluliOrderWarelog::$SYNC_STATUS_CREATE);
            $_orderWareLog->save();
            throw new sfException($e->getMessage());
        }
        return $res;
    }
    /**
     * 获取单个店铺的所有订单
     * @return [type] [description]
     */
    public function getAllOrder($shop_id = 30){
        
        try {
            $edb = KaluliFun::getObject('KllErpDriver'.self::$ERP_NAME);
            $api = $edb->builder('edbTradeGet');
            
            // $api->setOutTid('62271864796');
            $api->setBeginTime(date('Y-m-d H:i:s',time()-86400*300));
            $api->setEndTime(date('Y-m-d H:i:s',time()));
            $api->setShopId($shop_id); //店铺ID
            $api->setStorageId('31');  //仓库ID
            $api->setPaymentStatus('已付款');
            $api->setOrderStatus('未发货');
            $api->setProceStatus('已确认');
            $api->setPageSize('100');
            $res  = $edb->exec($api,'post');
            $result = json_decode($res['result'],true);
            return $result;
        } catch(sfException $e) {
            throw new sfException($e->getMessage());
        }
    }
    /**
     * 卡路里把物流单号同步到EDB
     *  
     */
    public function syncOrderExpress($tid, $express, $express_no) {
        // var_dump($tid.'---'.$express.'--'.$express_no);exit;
        $mq = new KllAmqpMQ();
       
        if(empty($tid) || empty($express) || empty($express_no)) throw new sfException("参数错误");
        try {
            $edb = KaluliFun::getObject('KllErpDriver'.self::$ERP_NAME);
            $api = $edb->builder('edbTradeDeliveryBatch');
            $api->setOrderCode($tid);
            $express = Funbase::getDomesticExpress($express);
            $api->setExpress($express);
            $api->setExpressNo($express_no);
            $api->setXmlValues();
            $res  = $edb->exec($api,'post');
            $result = json_decode($res['result'],true);
        } catch(sfException $e) {
            throw new sfException($e->getMessage());
        }
        if(empty($result)) throw new sfException("参数传递不完整！");
        if(isset($result['error_code']) || isset($result['error_msg'])) {
            $_msg = '错误代码：';
            $_msg .= isset($result['error_code']) ? $result['error_code'] : '【没有错误代码】 错误信息：';
            $_msg .= isset($result['error_msg']) ? $result['error_msg'] : '【没有错误信息】';
           
            throw new sfException($_msg);
        }
        if(empty($result['Success']))  throw new sfException("未知错误！");
        if(isset($result['Success']['items']['item'][0]['response_Code']) && $result['Success']['items']['item'][0]['response_Code'] == 200) {
            return true;
        }
        throw new sfException('未知错误！');
    }
    /**
     * 获取订单
     */
    public function getOrder($out_id = '') {
        if(empty($out_id)) throw new sfException("订单ID不存在！");
        try {
            $edb = KaluliFun::getObject('KllErpDriver'.self::$ERP_NAME);
            $api = $edb->builder('edbTradeGet');
            $api->setOutTid($out_id);
            $api->setBeginTime(date('Y-m-d H:i:s',time()-86400*300));
            $api->setEndTime(date('Y-m-d H:i:s',time()));
            $res  = $edb->exec($api,'post');
            $result = json_decode($res['result'],true);
        } catch(sfException $e) {
            throw new sfException($e->getMessage());
        }
        if(empty($result)) throw new sfException("参数传递不完整！");
        if(isset($result['error_code']) || isset($result['error_msg'])) {
            $_msg = '错误代码：';
            $_msg .= isset($result['error_code']) ? $result['error_code'] : '【没有错误代码】 错误信息：';
            $_msg .= isset($result['error_msg']) ? $result['error_msg'] : '【没有错误信息】';
            throw new sfException($_msg);
        }
        if(empty($result['Success']))  throw new sfException("未知错误！");

        $_sync_data =  $result['Success']['items']['item'][0];
        if(isset($_sync_data)) {
            //2016 01 29 梁天 补充 不管外部订单号是否一致 查到就返回
            if(!empty($_sync_data['out_tid'])) return  $_sync_data;
            /*
            if($_sync_data['out_tid'] == $out_id) return $_sync_data;
            if(empty($_sync_data['tid_item'])) throw new sfException("没有找到该订单！");
            foreach($_sync_data['tid_item'] as $k=>$v) {
                if($v['out_tid'] == $out_id) return $_sync_data;
            }
            */
        }
        throw new sfException("没有查到此订单！");
    }

    /**
     * 撤单
     */
    public function setCancelOrder($tid) {
        if(empty($tid)) throw new sfException("订单ID不存在！");
        try {
            $edb = KaluliFun::getObject('KllErpDriver'.self::$ERP_NAME);
            $api = $edb->builder('edbTradeCancel');
            $api->setTid($tid);
            $api->setXmlValues();
            $res  = $edb->exec($api,'post');
            $result = json_decode($res['result'],true);
        } catch(sfException $e) {
            throw new sfException($e->getMessage());
        }
        if(empty($result)) throw new sfException("参数传递不完整！");
        if(isset($result['error_code']) || isset($result['error_msg'])) {
            $_msg = '错误代码：';
            $_msg .= isset($result['error_code']) ? $result['error_code'] : '【没有错误代码】 错误信息：';
            $_msg .= isset($result['error_msg']) ? $result['error_msg'] : '【没有错误信息】';
            throw new sfException($_msg);
        }
        if(empty($result['Success']))  throw new sfException("未知错误！");
        if(isset($result['Success']['items']['item'][0]['response_Code']) && $result['Success']['items']['item'][0]['response_Code'] == 200) return true;
        throw new sfException('未知错误！');
    }




    /**
     * @param string $var
     * @return null|string
     * 订单快递回调
     */
    public function order_express_action($args = array()) {
        $_id = (int)$args['id'];
        if(empty($_id)) throw new sfException("订单回调失败：订单ID不存在！");
        //获取order内容
        $_order = KaluliOrderTable::getInstance()->getOne($_id,false);
        if(empty($_order)) throw new sfException("订单回调失败：订单不存在");

        //获取订单附表
        //$_orderAttr = KaluliOrderAttrTable::getInstance()->getByOrderIdOne($_order->getId(),false);
        //获取主订单副表
        //$_mainOrderAttr = KaluliMainOrderAttrTable::getInstance()->findByOrderNumber($_order->getOrderNumber());
        $_mainOrderAttr = KaluliMainOrderAttrTable::getInstance()->getOne($_order->getOrderNumber(),false);

        //获取仓库代码
        //$wareData = KaluliWarehousesTable::getInstance()->getWare();

        $out_id = $_order['order_number'].'-'.$_order['id'];

        try {
            $_sync_data = $this->getOrder($out_id);
        } catch(sfException $e) {
            throw new sfException($e->getMessage());
        }
        if(empty($_sync_data)) throw new sfException('没有查到数据！');

        if($_sync_data['type'] != '正常订单' || $_sync_data['status'] != '已确认' || $_sync_data['delivery_status'] != '已发货') {
            throw new sfException('订单未发货！');
        }
        if(empty($_sync_data['express_no']))   throw new sfException('没有快递号！');
        //更新订单状态
        //判断订单状态是不是未发货 否则不做修改
        if($_order->getStatus() != KaluliOrder::$CREATE_STATUS) {
            throw new sfException("错误：该订单已经同步过了！");
        }
        //解析地址
        $_address = json_decode($_mainOrderAttr->getAddressAttr(),true);
        if(count(explode(',',$_sync_data['address'])) == 4) {
            list($_province,$_city,$_area,$_street) = explode(',',$_sync_data['address']);
            $_address['province'] = $_province;
            $_address['city'] = $_city;
            $_address['area'] = $_area;
            $_address['region'] = $_province.' '.$_city.' '.$_area;
            $_address['street'] = $_street;
            $_mainOrderAttr->setAddressAttr(json_encode($_address));
            $_mainOrderAttr->save();
        }
        $_order->setDomesticExpressType(KaluliOrder::setExpressType($_sync_data['express']));
        $_order->setDomesticOrderNumber($_sync_data['express_no']);
        $_order->setDomesticExpressTime(strtotime(!empty($_sync_data['delivery_time']) ? $_sync_data['delivery_time'] : date('Y-m-d H:i:s',time())));
        //0订单生成 1已发货 2订单完成 3退货处理中 4待用户发货 5待卡路里收货 6已退货 7订单关闭 8用户取消 9识货取消 10拒绝退货
        $_order->setStatus(KaluliOrder::$DELIVERY_STATUS);
        $_order->setWareStatus(KaluliOrderWarelog::$SYNC_STATUS_EXPRESS);
        $_order->save();
        //写订单日志
        $orderHistory = new KaluliOrderHistory();
        $orderHistory->setOrderNumber($_order->getOrderNumber());
        $orderHistory->setHupuUid($_order->getHupuUid());
        $orderHistory->setHupuUsername($_order->getHupuUsername());
        $orderHistory->setType(KaluliOrder::$DELIVERY_STATUS);
        $orderHistory->setExplanation($_order->getId()." 订到已发货， 快递类型：".KaluliOrder::$EXPRESS_TYPE[KaluliOrder::setExpressType($_sync_data['express'])].' 快递单号：'.$_sync_data['express_no'].'  订单号='.$_order->getOrderNumber() );
        //脚本同步哪里会有用户名？
        // $orderHistory->setGrantUid(sfContext::getInstance()->getUser()->getTrdUserHuPuId());
        // $orderHistory->setGrantUsername( sfContext::getInstance()->getUser()->getTrdUsername());
        $orderHistory->save();
        //写入同步日志
        $_orderWareLog = new KaluliOrderWarelog();
        $_orderWareLog->setOrderNumber($_order->getOrderNumber());
        $_orderWareLog->setOrderId($_order->getId());
        $_orderWareLog->setMsg("【系统】订单物流自动同步成功！");
        $_orderWareLog->setWareType(1);
        $_orderWareLog->setStatus(KaluliOrderWarelog::$SYNC_OK);
        $_orderWareLog->setWareOrderType(KaluliOrderWarelog::$SYNC_STATUS_EXPRESS);
        $_orderWareLog->save();
        //加入微信消息发送队列
        $wxData = ['delivername'=>$_sync_data['express'],'ordername'=>$_sync_data['express_no'],'orderNumber'=>$_order->getOrderNumber()];
        kaluliFun::sendMqMessage("kaluli.wxSend.send",['userId'=>$_order->getHupuUid(),'type'=>kaluliWxTemplate::$_DELIVERY,'data'=>json_encode($wxData)],'kaluli_weixin_send');
    }


        //解析地址
    private function _parseAddress($sync_data) {
        if(empty($sync_data['address']) ||  strpos($sync_data['address'],',')) return false;


    }



    private function _setOption($var = '') {
        if(!empty($var)) {
            return $var;
        }
        return null;
    }


    /*
     * 获取库存
     */
    public function sku_inventory_action($args = array()) {

        //获取商品
        $_sku = KaluliItemSkuTable::getInstance()->getOne($args['id'],true);
        kaluliLog::info("sku_inventroy",$args);
        try {
            $edb = KaluliFun::getObject('KllErpDriver'.self::$ERP_NAME);
            $api = $edb->builder('edbProductGet');
            $api->setBarCode($_sku['code']);
            $api->setStandard($_sku['ware_sku']);
            if($args['sku'] == "产品组合"){
                $api->setIsuit("1");
            }

            $res  = $edb->exec($api,'post');

            $result = json_decode($res['result'],true);

        }catch(sfException $e) {
            throw new sfException($e->getMessage());
        }
        if(empty($result)) throw new sfException("参数传递不完整！");
        if(isset($result['error_code']) || isset($result['error_msg'])) {
            $_msg = '错误代码：';
            $_msg .= isset($result['error_code']) ? $result['error_code'] : '【没有错误代码】 错误信息：';
            $_msg .= isset($result['error_msg']) ? $result['error_msg'] : '【没有错误信息】';
            throw new sfException($_msg);
        }
        if(empty($result['Success']))  throw new sfException("未知错误！");

        if(empty($result['Success']['items']) || !isset($result['Success']['items']['item'][0]['entity_stock'])) throw new sfException("没有查到该商品！");
        return array('entity_stock'=>$result['Success']['items']['item'][0]['entity_stock'],'sell_stock'=>$result['Success']['items']['item'][0]['sell_stock']);
    }

    //商品sku更新
    private function itemsSku_action($args = array()) {
        $_id = (int)$args['id'];
        if(empty($_id)) throw new sfException('ID不存在！');
        //获取sku
        $_data = KaluliItemSkuTable::getInstance()->getOne($_id,false);
        if(empty($_data)) throw new sfException("商品不存在！");

        try {
            $edb = KaluliFun::getObject('KllErpDriver'.self::$ERP_NAME);
            $api = $edb->builder('edbProductBaseInfoGet');
            $api->setBarCode($_data->getCode());
            $res  = $edb->exec($api,'post');
            $result = json_decode($res['result'],true);
        } catch(sfException $e) {
            throw new sfException($e->getMessage());
        }

        if(empty($result)) throw new sfException("参数传递不完整！");
        if(isset($result['error_code']) || isset($result['error_msg'])) {
            $_msg = '错误代码：';
            $_msg .= isset($result['error_code']) ? $result['error_code'] : '【没有错误代码】 错误信息：';
            $_msg .= isset($result['error_msg']) ? $result['error_msg'] : '【没有错误信息】';
            throw new sfException($_msg);
        }
        if(empty($result['Success']))  throw new sfException("未知错误！");

        //没有报错 那么继续执行
        $_items = isset($result['Success']['items']['item'][0]) ? $result['Success']['items']['item'][0] : '';
        if(empty($_items)) throw new sfException("没有在E店宝查到该商品！");
        //执行更新
        $_data->setWareSku(isset($_items['standard']) ? $_items['standard'] : '');
        $_data->setWupdateTime(time());
        $_data->save();
        return true;
    }





    //仓库更新
    /*
    private function cangku_action($args = array()) {
        $args['data']['method'] = 'gy.erp.warehouse.get';
        $res = $this->post($args);
        if(empty($res['warehouses'])) throw new sfException('更新失败：没有仓库！');
        foreach($res['warehouses'] as $k=>$v) {
            $find = KaluliWarehousesTable::getInstance()->getOneByCode($v['code']);
            if(!empty($find)) {
                $find->setCode($v['code']);
                $find->setName($v['name']);
                $find->setAddress($v['address']);
                $find->setNote($v['note']);
                $find->setCreateDate($v['create_date']);
                $find->setContactName($v['contact_name']);
                $find->setContactPhone($v['contact_phone']);
                $find->setContactMobile($v['contact_mobile']);
                $find->setTypeName($v['type_name']);
                $find->setAreaName($v['area_name']);
                $find->save();
            } else {
                $_ware = new KaluliWarehouses();
                $_ware->setCode($v['code']);
                $_ware->setName($v['name']);
                $_ware->setAddress($v['address']);
                $_ware->setNote($v['note']);
                $_ware->setCreateDate($v['create_date']);
                $_ware->setContactName($v['contact_name']);
                $_ware->setContactPhone($v['contact_phone']);
                $_ware->setContactMobile($v['contact_mobile']);
                $_ware->setTypeName($v['type_name']);
                $_ware->setAreaName($v['area_name']);
                $_ware->save();
            }
        }
        //删除卡路里仓库缓存
        $_redis = sfContext::getInstance()->getDatabaseConnection('kaluliRedis');
        $_redis->select(1);
        $_redis->del("kaluli.ware.list");
        return true;
    }
    */






    /**
     * 更新最后更新时间
     */
    public function uploadTime($type) {
        $persistenceRedis = sfContext::getInstance()->getDatabaseConnection('tradePersistenceRedis');
        $_key = self::$_redis_key.$type;
        $_time = time();
        $persistenceRedis->set($_key,$_time);
        return $_time;
    }






   private function JSON($array) {
        $this->arrayRecursive($array, 'urlencode', true);
        $json = json_encode($array);
        return urldecode($json);
    }



  private  function arrayRecursive(&$array, $function, $apply_to_keys_also = false)
    {
        static $recursive_counter = 0;
        if (++$recursive_counter > 1000) {
            die('possible deep recursion attack');
        }
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $this->arrayRecursive($array[$key], $function, $apply_to_keys_also);
            } else {
                $array[$key] = $function($value);
            }
            if ($apply_to_keys_also && is_string($key)) {
                $new_key = $function($key);
                if ($new_key != $key) {
                    $array[$new_key] = $array[$key];
                    unset($array[$key]);
                }
            }
        }
        $recursive_counter--;
    }

    /**
     * 地址信息补全方法
     * @param $param
     * @param $type province:省.city:市
     */
  private function fixAddress($param,$type) {
        if(empty($param)) return "其他";
        if($type == "province") {
            //假设已经包含省市字符，返回信息
            if(strpos($param,"省") || strpos($param,"市")) {
                return $param;
            } else {
                return (in_array($param,self::$_modus))? $param."市" : $param."省";
            }
        } elseif($type == "city") {
            //假设已经包含市的字符，返回信息
            if(strpos($param,"市") ){
                return $param;
            } else {
                return $param . "市";
            }
        }

  }
  /**
   * EDB店铺的搜索
   * 考拉店铺是2=>30,  
   * 京东 3=>22
   */
  private function getEdbStore($source){
        if($source == 2){
            return  29;
        }elseif($source == 3){
            return 21;
        }else{
            return 4;
        }
  }



















}