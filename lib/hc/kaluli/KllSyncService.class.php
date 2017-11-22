<?php
/**
 * 卡路里 管易同步类
 * 梁天  2015-08-31
 */
class KllSyncService  {
    //同步方法白名单
    public static $_action_name = array('cangku','spkucun',"sku_inventory",'itemsSku','order_create','order_express');
    public static $_redis_key = "kaluli.guanyi.sync.";
    //管易配置
    const APPKEY = "100514";
    const SECRET = "36d7fd661cdc4ea3af87235dbd854675";
    const SESSIONKEY = "ebc4170eb9a640ea95a72a750a784385";
    const OPENURL = "http://v2.api.guanyierp.com/rest/erp_open";


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
     * 添加新订单
     */
    public function order_create_action($args = array()) {
        $_id = (int)$args['_id'];
        if(empty($_id)) throw new sfException("添加订单错误：订单ID不存在！");
        //获取仓库代码
        $wareData = KaluliWarehousesTable::getInstance()->getWare();
        //子订单
        $_order = KaluliOrderTable::getInstance()->getOne($_id,false);
        //判断订单是否可以同步
        if($_order->getWareStatus() !=  KaluliOrderWarelog::$SYNC_STATUS_NO) {
            throw new sfException("错误：订单已经同步过了！");
        }
        if($_order->getStatus() !=  KaluliOrder::$CREATE_STATUS) {
            throw new sfException("错误：订单不是未发货状态！");
        }

        //获取商品SKU信息
        $_goodsData = KaluliItemSkuTable::getInstance()->findById($_order->getGoodsId());
        $_goodsData = $_goodsData->toArray();
        $_goodsData = $_goodsData[0];
        $_goodsData['attr'] = unserialize($_goodsData['attr']);


        //主订单
        $_mainOrder = KaluliMainOrderTable::getInstance()->findByOrderNumber($_order->getOrderNumber());
        $_mainOrder = $_mainOrder->toArray();
        $_mainOrder = $_mainOrder[0];

        //主订单attr
        $_mainOrderAttr = KaluliMainOrderAttrTable::getInstance()->findByOrderNumber($_order->getOrderNumber());
        $_mainOrderAttr = $_mainOrderAttr->toArray();
        $_mainOrderAttr = $_mainOrderAttr[0];
        $_mainOrderAttr['address_attr'] = json_decode($_mainOrderAttr['address_attr'],true);


        $args['data']['method'] = 'gy.erp.trade.add';

        $args['data']['cod'] = false;            //货到付款
        $args['data']['refund'] = 0;            //退款状态
        $args['data']['order_type_code'] = null;          //订单类型code

        $args['data']['order_settlement_code'] = null;    //结算方式code
        $args['data']['platform_code'] = $this->_setOption($_mainOrder['order_number'].'-'.$_order->getId());         //平台单号
        $args['data']['shop_code'] = '002';       //1 店铺code
        $args['data']['express_code'] = isset(KaluliOrderTable::$guanyi_express_type[$_order->getDomesticExpressType()]) ?
            KaluliOrderTable::$guanyi_express_type[$_order->getDomesticExpressType()] :
            KaluliOrderTable::$guanyi_express_type['default'];             //1 物流公司code
        $args['data']['warehouse_code'] = $this->_setOption($wareData[$_order->getDepotType()]['code']);             //1   仓库code
        $args['data']['vip_code'] = $this->_setOption($_order->getHupuUid());                  //1    会员code
        $args['data']['vip_name'] = $this->_setOption($_order->getHupuUsername());                  //1    会员code
        $args['data']['receiver_name'] = $this->_setOption($_mainOrderAttr['name']);              //收货人code
        $args['data']['receiver_address'] = $this->_setOption( $_mainOrderAttr['address_attr']['region'].$_mainOrderAttr['address_attr']['street']);           //收货地址

        $args['data']['receiver_zip'] = $this->_setOption($_mainOrderAttr['postcode']);           //收货邮编
        $args['data']['receiver_mobile'] = null;               //1    收货人电话
        $args['data']['receiver_phone'] = $this->_setOption($_mainOrderAttr['mobile']);               //1 收货人手机
        $args['data']['receiver_province'] = $this->_setOption($_mainOrderAttr['province']);               //收货人省份
        $args['data']['receiver_city'] = $this->_setOption($_mainOrderAttr['city']);               //收货人城市
        $args['data']['receiver_district'] = null;               //收货人区域
        $args['data']['tag_code'] = null;               //标记code
        $args['data']['deal_datetime'] = $this->_setOption($_mainOrder['created_at']);               //1  拍单时间
        $args['data']['pay_datetime'] = $this->_setOption($_mainOrder['pay_time']);               //1   付款时间
        $args['data']['business_man_code'] = null;               //业务员code
        $args['data']['post_fee'] = 0;               //物流费用
        $args['data']['cod_fee'] = 0;               //到付服务费
        $args['data']['discount_fee'] = 0;               //让利金额
        $args['data']['plan_delivery_date'] = null;               //预计发货日期
        $args['data']['buyer_memo'] = $this->_setOption($_mainOrderAttr['remark']);               //买家留言
        $args['data']['seller_memo'] = "卡路里销售仓";                //卖家备注
        $args['data']['seller_memo_late'] = $this->_setOption($_order->getTitle());             //二次备注


        //商品信息数组
        $args['data']['details'][] = array(
            'item_code' =>  $_goodsData['code'],     //商品代码
            'sku_code' => $_goodsData['ware_sku'],    //sku规格代码
            'price' => $_goodsData['price'],       //实际单价
            'qty' => $_order->getNumber(),         //数量
            'refund' => 0,      //是否退款  0非退款 ，1--退款（退款中）；
            'note' => $this->_setOption($_goodsData['id'].'-'.$_order->getTitle()),        //备注
            'oid' => null,        //子订单id
        );



        //发票信息
        $args['data']['invoices'][] = array(
            'invoice_type' => 1,   //1  发票类型  1-普通发票；2-增值发票
            'invoice_title' => '',   //发票抬头
            'invoice_content' => '',   //发票内容
            'invoice_amount' => '',     //发票金额
            //'bill_amount' => '',        //位置
        );

        //支付信息
        $args['data']['payments'][] = array(
            'pay_type_code' => $_mainOrder['ibilling_number'],        //1支付类型code
            'payment' => $_order->getTotalPrice(),              //1支付金额
            'pay_code' =>null,       //支付交易号
            'account' => '',              //支付账户
        );

        //发送到管易
        try {
            $res = $this->post($args);

            $_order->setWareStatus(KaluliOrderWarelog::$SYNC_STATUS_CREATE);
            $_order->save();
            //写入订单order
            $_orderAttr = KaluliOrderAttrTable::getInstance()->getOneByOrderId($_order->getId(),true);
            $_orderAttr->setWareType(1);
            $_orderAttr->setWareId(isset($res['id']) ? $res['id'] : '');
            $_orderAttr->setWareCode(isset($res['code']) ? $res['code'] : '');
            $_orderAttr->save();
            //写入订单log
            $_orderWareLog = new KaluliOrderWarelog();
            $_orderWareLog->setOrderNumber($_order->getOrderNumber());
            $_orderWareLog->setOrderId($_order->getId());
            $_orderWareLog->setMsg("【系统】订单自动同步到管易平台成功！");
            $_orderWareLog->setWareType(1);
            $_orderWareLog->setStatus(KaluliOrderWarelog::$SYNC_OK);
            $_orderWareLog->setWareOrderType(KaluliOrderWarelog::$SYNC_STATUS_CREATE);
            $_orderWareLog->save();
        }catch(sfException $e) {
            $_orderWareLog = new KaluliOrderWarelog();
            $_orderWareLog->setOrderNumber($_order->getOrderNumber());
            $_orderWareLog->setOrderId($_order->getId());
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
       // $_orderAttr = KaluliOrderAttrTable::getInstance()->getByOrderIdOne($_order->getId(),true);

        //获取仓库代码
        $wareData = KaluliWarehousesTable::getInstance()->getWare();
        //560164542230
        //发送
        $args['data']['method'] = 'gy.erp.trade.deliverys.get';
        $args['data']['warehouse_code'] = $this->_setOption($wareData[$_order->getDepotType()]['code']);
        $args['data']['shop_code'] = KaluliOrderWarelog::$GUANYI_KALULI_ID;
        $args['data']['outer_code'] = $_order->getOrderNumber().'-'.$_order->getId();
        //$args['data']['outer_code'] = '1509150558147356-2254';
        try {
            $res = $this->post($args);
        } catch(sfException $e) {
            throw new sfException($e->getMessage());
        }
        if(empty($res['deliverys']) || empty($res['deliverys'][0]['express_no'])) {
            throw new sfException("错误：管易没有查到该订单！");
        }
        $express_time = !empty($res['deliverys'][0]['delivery_statusInfo']['delivery_date']) ?
            $res['deliverys'][0]['delivery_statusInfo']['delivery_date'] :
            $res['deliverys'][0]['delivery_statusInfo']['scan_date'];
        //更新订单状态
        //判断订单状态是不是未发货 否则不做修改
        if($_order->getStatus() != KaluliOrder::$CREATE_STATUS) {
            throw new sfException("错误：该订单已经同步过了！");
        }
        $_order->setDomesticExpressType(KaluliOrder::setExpressType($res['deliverys'][0]['express_name']));
        $_order->setDomesticOrderNumber($res['deliverys'][0]['express_no']);
        $_order->setDomesticExpressTime(strtotime(!empty($express_time) ? $express_time : date('Y-m-d H:i:s',time())));
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
        $orderHistory->setExplanation($_order->getId()." 订到已发货， 快递类型：".KaluliOrder::$EXPRESS_TYPE[KaluliOrder::setExpressType($res['deliverys'][0]['express_name'])].' 快递单号：'.$res['deliverys'][0]['express_no'].'  订单号='.$_order->getOrderNumber() );
        $orderHistory->setGrantUid(sfContext::getInstance()->getUser()->getTrdUserHuPuId());
        $orderHistory->setGrantUsername( sfContext::getInstance()->getUser()->getTrdUsername());
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
        //获取仓库
        $wareData = KaluliWarehousesTable::getInstance()->getWare();
        $_warehouse_code =  isset($wareData[$_sku['storehouse_id']]['code']) ? $wareData[$_sku['storehouse_id']]['code']  : null;
        if(empty($_warehouse_code)) throw new sfException("未知仓库！");

        $args['data']['method'] = 'gy.erp.stock.get';
        $args['data']['warehouse_code'] = $_warehouse_code;
        $args['data']['item_code'] = $args['code'];
        $res = $this->post($args);
        if(empty($res['stocks']) || !isset($res['stocks'][0]['qty'])) throw new sfException("没有查到该商品！");
        return array('qty'=>$res['stocks'][0]['qty'],'salable_qty'=>$res['stocks'][0]['salable_qty']);
    }



    public function aaa() {
        $args['data']['method'] = 'gy.erp.trade.get';
        $args['data']['warehouse_code'] = 'KLLOO1';
        $args['data']['shop_code'] = '002';
        $args['data']['platform_code'] = '1509158373888973';
        $res = $this->post($args);
       FunBase::myDebug($res);

    }



    //商品sku更新
    private function itemsSku_action($args = array()) {
        $_id = (int)$args['id'];
        if(empty($_id)) throw new sfException('更新失败：ID不存在！');
        //获取sku
        $_data = KaluliItemSkuTable::getInstance()->getOne($_id,false);
        if(empty($_data)) throw new sfException("更新失败：商品不存在！");
        $args['data']['method'] = 'gy.erp.items.get';
        $args['data']['page_no'] = '0';
        $args['data']['page_size'] = '1';
        $args['data']['code'] = $_data->getCode();
        $res = $this->post($args);
        //没有报错 那么继续执行
        $_items = isset($res['items'][0]) ? $res['items'][0] : '';
        if(empty($_items)) throw new sfException("更新失败：没有在管易查到该商品！");
        $_sku = isset($_items['skus'][0]) ? $_items['skus'][0] : '';
        //执行更新  151  146
        $_data->setWareSku(isset($_sku['code']) ? $_sku['code'] : '');
        $_data->setWupdateTime(time());
        $_data->save();
        return true;
    }





    //仓库更新
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


    //库存更新
    private function spkucun_action()
    {


        //入库
        /*
        $args['data']['method'] = 'gy.erp.purchase.arrive.get';
        $args['data']['page_no'] = '0';
        $args['data']['page_size'] = '50';
        $args['data']['start_create'] = '2015-08-20 00:00:00';
        $args['data']['end_create'] = date('Y-m-d H:i:s',time());
        $args['data']['warehouse_code'] = 'KLLOO1';
        */
        //出库

        $args['data']['method'] = 'gy.erp.purchase.return.get';
        $args['data']['page_no'] = '1';
        $args['data']['page_size'] = '50';
        $args['data']['start_create'] = '2015-08-1 00:00:00';
        $args['data']['end_create'] = date('Y-m-d H:i:s',time());
        $args['data']['warehouse_code'] = 'KLLOO1';
        $args['data']['status'] = '1';



        $res = $this->post($args);

        FunBase::myDebug($res);



    }




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


    /**
     * @param array $args
     * 发送到管易
     */
    private function post($args = array()) {
        //拼装key
        $args['data']['appkey'] = self::APPKEY;
        $args['data']['sessionkey'] = self::SESSIONKEY;
       // $_jsonData = json_encode($args['data']);
        $_jsonData = $this->JSON($args['data']);
        $_singJson = self::SECRET.$_jsonData.self::SECRET;
        $sign = strtoupper(md5($_singJson));
        $args['data']['sign'] = $sign;
        //$args['data'] = json_encode($args['data']);
        $args['data'] = $this->JSON($args['data']);

        $res =  tradeCommon::getContents(self::OPENURL,$args['data'],10,'POST','application/json');
        /* 判断是否成功 */
        if(strcmp($res,'null') == 0) throw new sfException("和管易开放平台通信失败！");
        $res = json_decode($res,true);
        if(empty($res) || empty($res['success'])) {
            throw new sfException(empty($res['errorDesc']) ? "和管易开放平台通信失败！" : $res['errorDesc']);
        }
        return $res;
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



















}