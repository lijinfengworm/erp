<?php

/*
 * 笨鸟、海带宝服务接口
 */

class tradeBirdexService {

    private static $redis = null;//redis对象 
    //private $post_url = 'http://nijing.vicp.net:8018/express_receiver.aspx';//测试提交地址
    private $post_url = 'http://partner.birdex.cn/Transport/ShiHuo/Index.aspx';//正式提交地址
    private $key = '124aa22d50c74a6ddba4d81db9a18d35';
    private $birdex_msg = array(
        'LOGISTICS_TRADE_PAID'=>'birdex.logistics.event.wms.create',//预报
        'WMS_STOCKIN_INFO'=>'birdex.logistics.event.wms.stockin',//笨鸟回传入库信息
        'LOGISTICS_SORTING_GOODS'=>'birdex.logistics.event.wms.sorting',
        'WMS_GOODS_WEIGHT'=>'birdex.logistics.event.wms.weight',//笨鸟回传仓库信息
        'WMS_INNER_EXCEPTION'=>'birdex.logistics.event.wms.exception',//笨鸟回传仓库异常信息
        'WMS_STOCKOUT_INFO'=>'birdex.logistics.event.wms.stockout',//笨鸟回传出仓信息
        'TMS_CLEAR_CUSTOMS_INFO'=>'birdex.logistics.event.tms.clearcustoms',//清关公司回传清关信息
        'TMS_CUSTOMS_DUTYS_INFO'=>'birdex.logistics.event.tms.dutys',//笨鸟回传关税信息
        'TMS_DISSENT_INFO'=>'birdex.logistics.event.tms.dissent',//确认税款
    );
    private $category = array(
        '8'=>'运动鞋',
        '9'=>'运动服饰',
        '10'=>'户外装备',
        '11'=>'体育器材',
        '12'=>'健身装备',
        '13'=>'男装',
        '14'=>'男鞋',
        '15'=>'内衣家居服',
        '16'=>'手机',
        '17'=>'电脑',
        '18'=>'电脑配件',
        '19'=>'存储设备',
        '20'=>'外设产品',
        '21'=>'数码配件',
        '22'=>'摄影摄像',
        '23'=>'影音电器',
        '24'=>'网络设备',
        '26'=>'生活电器',
        '27'=>'厨房电器',
        '28'=>'个人护理',
        '29'=>'休闲零食',
        '31'=>'生鲜食品',
        '32'=>'酒水饮料',
        '33'=>'奶类制品',
        '34'=>'其他食品',
        '37'=>'厨房用品',
        '38'=>'生活用品',
        '39'=>'成人用品',
        '41'=>'汽车用品',
        '42'=>'办公设备',
        '43'=>'男表',
        '44'=>'图书音像',
        '45'=>'旅游',
        '46'=>'金融保险',
        '47'=>'女装',
        '48'=>'日常用品',
        '54'=>'女鞋',
        '55'=>'休闲装',
        '56'=>'男包',
        '57'=>'女包',
        '58'=>'拉杆箱',
        '59'=>'户外/运动包',
        '60'=>'商务公文包',
        '61'=>'女表',
        '62'=>'智能手表',
        '63'=>'饰品',
        '64'=>'皮带',
        '65'=>'太阳眼镜',
        '66'=>'进口食品',
        '67'=>'保健品',
    );


    /*
     * 进行一些初始化工作
     */

    public function __construct()
    {
        $this->getRedis();
        if(sfConfig::get('sf_environment') == 'dev')
        {
            $this->post_url = 'http://nijing.vicp.net:8018/express_receiver.aspx';
            $this->key = '123456';
        }
    }


    /*
     * 设置redis对象
     */

    public function getRedis()
    {
        if (!self::$redis)
        {
            self::$redis = sfContext::getInstance()->getDatabaseConnection('kaluliRedis');
        }

        return self::$redis;
    }

    /**
     *
     * 预报包裹
     */
    public function forecastPackage($number = 10){
        $forcastInfo = $this->getForecastInfo($number);
        if (count($forcastInfo)<1) return false;
        $orderNumberArr =  array();
        foreach($forcastInfo as $info){
            array_push($orderNumberArr,$info->getOrderNumber());
        }
        $forcastInfo = $this->getForecastInfoByOrderNumber($orderNumberArr);
        //var_dump($forcastInfo->toArray());die;
        if (count($forcastInfo)<1) return false;
        $i = 0;
        $param = $this->getCommonParam('LOGISTICS_TRADE_PAID');
        $tradeOrders = array();
        $express_number = $fast_mode_arr = array();
        foreach($forcastInfo as $k=>$v){
            //判断是用海带宝还是笨鸟
            if($v->getDeliveryType() == 0){//海带宝 * 美国
                $this->forecastHaiDaiBao($v);
                continue;
            } elseif ($v->getDeliveryType() == 3) {//海带宝 * 日本
                $this->forecastHaiDaiBaoJP($v);
                continue;
            }
            $attr_json = json_decode($v->getAttr(),1);
            //笨鸟预报 用户下发物流包裹消息 >>包裹订单详情 》 订单详情列表
            if(isset($tradeOrders[$v->getMartExpressNumber()])){//判断是否存在了
                $tradeOrders[$v->getMartExpressNumber()]['tradeOrderId'] = $v->getOrderNumber();
                $tradeOrders[$v->getMartExpressNumber()]['tradeOrderValue'] += ($v->getDeliveryType() == 1 or $v->getDeliveryType() == 2) ? ($attr_json['price']/100)*$v['count'] : $attr_json['price']*$v['count'];
            } else {
                $tradeOrders[$v->getMartExpressNumber()]['tradeOrderId'] = $v->getOrderNumber();
                $tradeOrders[$v->getMartExpressNumber()]['tradeOrderValue'] = ($v->getDeliveryType() == 1 or $v->getDeliveryType() == 2) ? ($attr_json['price']/100)*$v['count'] : $attr_json['price']*$v['count'];
            }
            $tradeOrders[$v->getMartExpressNumber()]['tradeOrderValueUnit'] = ($v->getDeliveryType() == 1 or $v->getDeliveryType() == 2) ? "USD" : "CNY";
            $tradeOrders[$v->getMartExpressNumber()]['procurementPlatform'] = $v->getBusiness();
            $tradeOrders[$v->getMartExpressNumber()]['procurementOrderCode'] = $v->getMartOrderNumber();

            $tradeOrders[$v->getMartExpressNumber()]['checkOutMode'] = 0;//普通模式

            $logistics_flag = true;//是否预报过 标识
            $fill_forcast = false;//补报 标识
            $logistics = TrdOrderLogisticsTable::getInstance()->findOneByExpressNumber($v->getMartExpressNumber());
            if (!$logistics){
                $logistics_flag = false;
                $logistics = new TrdOrderLogistics();
                $logistics->set('express_number',$v->getMartExpressNumber());
                $logistics->set('type',2);
                $logistics->set('foreign_status',51);
                $logistics->save();
            } else{
                $fill_forcast = true;
                $logistics->set('type',2);
                $logistics->save();
            }

            //物流订单详情
            $tradeOrders[$v->getMartExpressNumber()]['logisticsOrder']['logisticsId'] = $logistics->getId();//LP物流订单的订单号 唯一的
            $tradeOrders[$v->getMartExpressNumber()]['logisticsOrder']['trackingNo'] = $v->getMartExpressNumber();//商家物流号 唯一的
            $tradeOrders[$v->getMartExpressNumber()]['logisticsOrder']['segmentCode'] = ($v->getDeliveryType() == 1 or $v->getDeliveryType() == 2) ? 'PDX' : 'HKG';//美国俄勒冈 香港仓库
            //获取商品的属性
            $productInfo = TrdProductAttrTable::getInstance()->find($v->getProductId());
            if(!$productInfo) return;
            $category_id = $productInfo->getChildrenId();
            if($category_id){
                $itemCategoryName = $category_id."-".$this->category[$category_id];
            }else{
                $itemCategoryName = '47-日常用品';
            }

            if(isset($tradeOrders[$v->getMartExpressNumber()]['Items'])){//判断是否存在商品了
                $number = count($tradeOrders[$v->getMartExpressNumber()]['Items']);
            } else{
                $number = 0;
            }
            //订单包含的商品列表
            $tradeOrders[$v->getMartExpressNumber()]['Items'][$number]['itemId'] = $v->getProductId();
            $tradeOrders[$v->getMartExpressNumber()]['Items'][$number]['itemName'] = $v->getTitle();
            $tradeOrders[$v->getMartExpressNumber()]['Items'][$number]['itemNo'] = $v->getGoodsId();
            $tradeOrders[$v->getMartExpressNumber()]['Items'][$number]['itemCategoryName'] = $itemCategoryName;
            $tradeOrders[$v->getMartExpressNumber()]['Items'][$number]['itemUnitPrice'] = ($v->getDeliveryType() == 1 or $v->getDeliveryType() == 2) ? $attr_json['price']/100 : $attr_json['price'];
            $tradeOrders[$v->getMartExpressNumber()]['Items'][$number]['itemQuantity'] = $v['count'];
            $tradeOrders[$v->getMartExpressNumber()]['Items'][$number]['itemImage'] = $attr_json['img'];
            $tradeOrders[$v->getMartExpressNumber()]['Items'][$number]['itemUrl'] = $productInfo->getUrl();

            unset($attr_json['price']);
            unset($attr_json['name']);
            unset($attr_json['img']);
            $itemRemark = '';
            if(!empty($attr_json)){
                foreach($attr_json as $key=>$val){
                    $itemRemark .= $key.':'.$val.';';
                }
                $itemRemark = rtrim($itemRemark,';');
            }
            $tradeOrders[$v->getMartExpressNumber()]['Items'][$number]['itemRemark'] = $itemRemark;

            $count = $this->getModeFlag($v->getOrderNumber(), $v->getMartExpressNumber(), $v->getDeliveryType());
            if (!$count && !$logistics_flag){//需要快转模式的订单
                $fast_mode_arr[] =array(
                    'order_number'=>$v->getOrderNumber(),
                    'express_number'=>$v->getMartExpressNumber()
                );
            }

//            $count = $this->getModeFlag($v->getOrderNumber(),$v->getMartExpressNumber());
//            if (!$count && !$logistics_flag){//快转模式 需要加收货地址
//                $tradeOrders[$v->getMartExpressNumber()]['checkOutMode'] = 1;//极速模式
//                //收货人详情
//                $receiverDetail = $this->getAddress($v->getOrderNumber());
//                $logistics->setContent(json_encode($receiverDetail['addr']));
//                $logistics->save();
//                $tradeOrders[$v->getMartExpressNumber()]['receiverDetail'] = $receiverDetail['res'];
//            }
            $i++;
            //存储 包裹号
            array_push($express_number,$v->getMartExpressNumber());
        }

        if($tradeOrders){
            sort($tradeOrders);
            $param['logisticsEventsRequest']['logisticsEvent']['eventBody']['tradeDetail']['tradeOrders'] = $tradeOrders;
        }
        if (isset($param['logisticsEventsRequest']['logisticsEvent']['eventBody']) && !empty($param['logisticsEventsRequest']['logisticsEvent']['eventBody'])){
            $result = $this->sendMessage($param,'LOGISTICS_TRADE_PAID');
            if ($result){
                $res = json_decode($result,true);
                //通过包裹号 获取所有的子商品 更新日志
                $orders = $this->getOrderInfoByExpressNumber($express_number,array(1,4));
                $logisticsOrders = $this->getLogisticsInfoByExpressNumber($express_number);
                if ($res['responses']['responseItems']['response']['success']){//预报成功
                    foreach($logisticsOrders as $m=>$n){
                        $logistics_content = array();
                        $n->set('foreign_status',0);
                        if($fill_forcast){
//                            $n->set('foreign_status',0);
//                            $logistics_content['us_in_date'] = date("Y-m-d H:i:s");
                        } else {
                            if($n->getContent()){
                                $logistics_content = json_decode($n->getContent(),1);
                            }
                        }
                        $logistics_content['fore_date'] = date("Y-m-d H:i:s");
                        $n->setContent(json_encode($logistics_content));
                        $n->save();
                    }
                    foreach($orders as $k=>$v){//记录日志
                        $v->setForecast(1);
                        $v->save();
                        $log = array(
                            'status' =>51,
                            'order_number' =>$v->getOrderNumber(),
                            'explanation' =>'笨鸟预报成功'.' (id='.$v->getId().')',
                            'grant_uid' =>0,
                            'grant_username' =>'crontab',
                        );
                        $this->saveLog($log);
                        $message = array(
                            'message'=>'笨鸟预报成功'.' (id='.$v->getId().')',
                            'param'=>$param,
                            'res'=>$res,
                            'order_number'=>$v->getOrderNumber()
                        );
                        tradeLog::info('forecast',$message);
                    }

                    //快转模式 需要提前分拣
                    if (!empty($fast_mode_arr)){
                        foreach ($fast_mode_arr as $fast_v){
                            $this->sortingPackage($fast_v['express_number']);
                        }
                    }
                }else{//失败
                    foreach($orders as $k=>$v){//记录日志
                        $log = array(
                            'status' =>51,
                            'order_number' =>$v->getOrderNumber(),
                            'explanation' =>'笨鸟预报出错:'.$res['responses']['responseItems']['response']['reason'].':'.$res['responses']['responseItems']['response']['reasonDesc'].' (id='.$v->getId().')',
                            'grant_uid' =>0,
                            'grant_username' =>'crontab',
                        );
                        $this->saveLog($log);
                        $message = array(
                            'message'=>'笨鸟预报出错'.' (id='.$v->getId().')',
                            'param'=>$param,
                            'res'=>$res,
                            'order_number'=>$v->getOrderNumber()
                        );
                        tradeLog::error('forecast',$message);
                    }
                }
            }
        }
        exit;
    }

    /**
     *
     * 用户下发分拣指令
     */
    public function sortingPackage($number = '',$flag = false, $content = array()){
        if($flag){//订单号
            //$order = TrdOrderTable::getInstance()->findOneByOrderNumber($number);
            $orderList = TrdOrderTable::getInstance()->createQuery('m')->select('*')->where('order_number = ?',$number)->andWhere('delivery_type = ?',1)->execute();
            if (count($orderList) > 0){
                $order = $orderList[0];
            }
        }else{//包裹号
            $order = TrdOrderTable::getInstance()->findOneByMartExpressNumber($number);
        }
        $orderList = TrdOrderTable::getInstance()->createQuery('m')->select('*')->where('order_number = ?',$order->getOrderNumber())->andWhere('pay_status = ?',1)->andWhere('delivery_type = ?',$order->getDeliveryType())->execute();
        $expressNumberArr = $expressNumberArr_new =  array();

        foreach($orderList as $k=>$v){
            if (!$v->getMartExpressNumber() || $v->getForecast() == 0) return false;
            array_push($expressNumberArr,$v->getMartExpressNumber());
        }

        if (empty($expressNumberArr)) return false;

        //判断是否被合包
        $orderObj = $this->getAllInfoByExpressNumber($expressNumberArr);
        if(count($orderObj)>1){//被合包
            $message = array(
                'message'=>'包裹被合包，分拣失败',
                'param'=>array_flip($expressNumberArr),
                'res'=>array(),
                'order_number'=>$number
            );
            tradeLog::error('sorting',$message);
        }

        //收货人详情
        $receiverDetail = $this->getAddress($order->getOrderNumber());

        if (count($orderObj)>1) {//被合包
            $hebao_express = '';

            //看哪个包裹被合包了
            foreach ($orderObj as $order_key=>$order_val) {
                if ($order_val->getOrderNumber() == $number) {
                    continue;
                }
                $hebao_express = $order_val->getMartExpressNumber();
            }
            $receiverDetail['res']['name'] = '徐晨';
            $receiverDetail['res']['mobile'] = '13817694356';
            $receiverDetail['res']['identityNumber'] = '321002198812045533';
            $receiverDetail['res']['country'] = '中国';
            $receiverDetail['res']['province'] = '上海';
            $receiverDetail['res']['city'] ='上海';
            $receiverDetail['res']['district'] = '虹口';
            $receiverDetail['res']['streetAddress'] = '上海市市辖区宝山区宝山工业园区金勺路1438号3号楼2楼 '.$hebao_express;
            $receiverDetail['res']['zipCode'] = '200083';

            $receiverDetail['addr']['name'] = '徐晨';
            $receiverDetail['addr']['tel'] = '13817694356';
            $receiverDetail['addr']['addr'] = '上海上海 上海 上海 宝山区 宝山工业园区金勺路1438号3号楼2楼';
        }

        //获取物流号对应的id
        $orderLogisticsList = TrdOrderLogisticsTable::getInstance()->createQuery('m')->select('*')->whereIn('express_number',$expressNumberArr)->execute();
        if(count($orderLogisticsList) >1 ){//非快转模式 包裹数大于1
            foreach($orderLogisticsList as $kk=>$vv){
                if($vv->getForeignStatus() == 0) return false;
                //$logistics_content = json_decode($vv->getContent(),1);
                //if(isset($logistics_content['name']) && !empty($logistics_content['name'])) return false;//是快转模式不需要分拣
            }
        }

        $expressNumberArray = array();
        foreach($orderLogisticsList as $m){
            $logistics_content = json_decode($m->getContent(),1);
            array_push($expressNumberArr_new,$m->getId());
            array_push($expressNumberArray,$m->getExpressNumber());
            $logistics_content_str = array_merge($logistics_content,$receiverDetail['addr']);
            $m->setContent(json_encode($logistics_content_str));
            $m->save();
        }

        //发送手机短信
//        if (!empty($content['us_in_date']) && !empty($receiverDetail['addr']['tel'])){
//            $message = array(
//                'message'=>'包裹入库，发送短信成功',
//                'param'=>$expressNumberArr,
//                'res'=>array(),
//                'order_number'=>$order->getOrderNumber()
//            );
//            tradeLog::info('sortingSendMessage',$message);
//            $message = new tradeSendMessage();
//            $sm = "您的订单".$order->getOrderNumber()."已全部入库，入库时间".$content['us_in_date']."，包裹将会在25天之内到达您手中，若超过25天，请联系识货客服进行赔付。";
//            $message->send($receiverDetail['addr']['tel'], $sm);
//        }

        //判断是否有分拣过
        $softObj = TrdOrderSoftingTable::getInstance()->createQuery('m')
            ->select('count(id) as count')
            ->where('order_number = ?', $order->getOrderNumber())
            ->andWhere('delivery_type = ?', $order->getDeliveryType())
            ->andWhere('status = ?', 0)
            ->count();
        if ($softObj > 0){
            $explanation = '笨鸟分拣出错';
            $explanation.='（已经分拣过）';
            $log = array(
                'status' =>51,
                'order_number' =>$order->getOrderNumber(),
                'explanation' =>$explanation,
                'grant_uid' =>0,
                'grant_username' =>'auto save',
            );
            $this->saveLog($log);
            $message = array(
                'message'=>$explanation,
                'param'=>$expressNumberArr,
                'res'=>array(),
                'order_number'=>$order->getOrderNumber()
            );
            tradeLog::error('sorting', $message);
            exit('笨鸟分拣（已经分拣过）');
        }

        $logisticsId = 'SH'.$order->getOrderNumber().mt_rand(100000,999999);
        foreach ($expressNumberArray as $express_v){
            $softObj = new TrdOrderSofting();
            $softObj->setSoftingId($logisticsId);
            $softObj->setOrderNumber($order->getOrderNumber());
            $softObj->setDeliveryType($order->getDeliveryType());
            $softObj->setExpressNumber($express_v);
            $softObj->save();
        }

        $param = $this->getCommonParam('LOGISTICS_SORTING_GOODS');
        $param['logisticsEventsRequest']['logisticsEvent']['eventBody']['tradeDetail']['tradeOrders'][0]['tradeOrderId'] = $order->getOrderNumber();
        $param['logisticsEventsRequest']['logisticsEvent']['eventBody']['tradeDetail']['tradeOrders'][0]['logisticsOrder']['logisticsId'] = $logisticsId;
        $param['logisticsEventsRequest']['logisticsEvent']['eventBody']['tradeDetail']['tradeOrders'][0]['logisticsOrder']['logisticsIdIncluded'] = implode(',',$expressNumberArr_new);
        $param['logisticsEventsRequest']['logisticsEvent']['eventBody']['tradeDetail']['tradeOrders'][0]['logisticsOrder']['segmentCode'] = ($order->getDeliveryType() == 1 or $order->getDeliveryType() == 2) ? 'PDX' : 'HKG';//美国俄勒冈 香港仓库
        $param['logisticsEventsRequest']['logisticsEvent']['eventBody']['tradeDetail']['tradeOrders'][0]['logisticsOrder']['routeId'] = ($order->getDeliveryType() == 1 or $order->getDeliveryType() == 2) ? 'USBDX' : 'HKBDX';
        $param['logisticsEventsRequest']['logisticsEvent']['eventBody']['tradeDetail']['tradeOrders'][0]['logisticsOrder']['occurTime'] = date("Y-m-d H:i:s");

        $param['logisticsEventsRequest']['logisticsEvent']['eventBody']['tradeDetail']['tradeOrders'][0]['receiverDetail'] = $receiverDetail['res'];
        //echo json_encode($param);die;
        $flag = true;//默认有收获地址
        if(empty($receiverDetail['res']['name']) || empty($receiverDetail['res']['identityNumber'])){
            $flag = false;
            $explanation = '笨鸟分拣出错';
            $explanation.='（没有身份证号）';
            $log = array(
                'status' =>51,
                'order_number' =>$order->getOrderNumber(),
                'explanation' =>$explanation,
                'grant_uid' =>0,
                'grant_username' =>'auto save',
            );
            $this->saveLog($log);
            $message = array(
                'message'=>$explanation,
                'param'=>$param,
                'res'=>array(),
                'order_number'=>$order->getOrderNumber()
            );
            tradeLog::error('sorting',$message);
            exit('笨鸟分拣没有身份证号');
        }
        $result = $this->sendMessage($param,'LOGISTICS_SORTING_GOODS');
        if ($result){
            $res = json_decode($result,true);
            if ($res['responses']['responseItems']['response']['success']){
                $log = array(
                    'status' =>51,
                    'order_number' =>$order->getOrderNumber(),
                    'explanation' =>'笨鸟分拣成功',
                    'grant_uid' =>0,
                    'grant_username' =>'auto save',
                );
                $this->saveLog($log);
                $message = array(
                    'message'=>'笨鸟分拣成功',
                    'param'=>$param,
                    'res'=>$res,
                    'order_number'=>$order->getOrderNumber()
                );
                tradeLog::info('sorting',$message);
            }else{
                $log = array(
                    'status' =>51,
                    'order_number' =>$order->getOrderNumber(),
                    'explanation' =>'笨鸟分拣出错:'.$res['responses']['responseItems']['response']['reason'].':'.$res['responses']['responseItems']['response']['reasonDesc'],
                    'grant_uid' =>0,
                    'grant_username' =>'auto save',
                );
                $this->saveLog($log);
                $message = array(
                    'message'=>'笨鸟分拣出错',
                    'param'=>$param,
                    'res'=>$res,
                    'order_number'=>$order->getOrderNumber()
                );
                tradeLog::error('sorting',$message);
            }
        }
    }

    /**
     *
     * 用户确认关税
     */
    public function confirmTax($order_number,$flag = false){
        //判断新的分拣里哪个被谁
        $softObj = TrdOrderSoftingTable::getInstance()->createQuery('m')
            ->select('*')
            ->where('order_number = ?', $order_number)
            ->andWhere('status = ?', 0)
            ->execute();
        $softId = $order_number;
        if (count($softObj) > 0){
            foreach($softObj as $k=>$v){
                $expressObj = TrdOrderLogisticsTable::getInstance()->createQuery('m')
                    ->select('*')
                    ->where('express_number = ?', $v->getExpressNumber())
                    ->andWhere('foreign_status > ?', 1)
                    ->fetchOne();
                if ($expressObj) {
                    $softId = $v['softing_id'];
                    break;
                }
            }
        }
        $param = $this->getCommonParam('TMS_DISSENT_INFO');
        $param['logisticsEventsRequest']['logisticsEvent']['eventBody']['tradeDetail']['tradeOrders'][0]['tradeOrderId'] = $order_number;
        $param['logisticsEventsRequest']['logisticsEvent']['eventBody']['tradeDetail']['tradeOrders'][0]['logisticsOrder']['logisticsId'] = $softId;
        $param['logisticsEventsRequest']['logisticsEvent']['eventBody']['tradeDetail']['tradeOrders'][0]['logisticsOrder']['carrierCode'] = '';
        $param['logisticsEventsRequest']['logisticsEvent']['eventBody']['tradeDetail']['tradeOrders'][0]['logisticsOrder']['trackingNo'] = '';
        $param['logisticsEventsRequest']['logisticsEvent']['eventBody']['tradeDetail']['tradeOrders'][0]['logisticsOrder']['logisticsRemark'] = '确认税款';
        $param['logisticsEventsRequest']['logisticsEvent']['eventBody']['tradeDetail']['tradeOrders'][0]['logisticsOrder']['logisticsCode'] = 'CONFIRM';
        $result = $this->sendMessage($param,'TMS_DISSENT_INFO');
        if ($result){
            $res = json_decode($result,true);
            if ($res['responses']['responseItems']['response']['success']){
                $explanation = '用户缴纳关税通知笨鸟成功';
                if($flag) $explanation = '识货代缴纳关税通知笨鸟成功';
                $log = array(
                    'status' =>51,
                    'order_number' =>$order_number,
                    'explanation' =>$explanation,
                    'grant_uid' =>0,
                    'grant_username' =>'auto save',
                );
                $this->saveLog($log);
                $message = array(
                    'message'=>$explanation,
                    'param'=>$param,
                    'res'=>$res,
                    'order_number'=>$order_number
                );
                tradeLog::info('confirmTax',$message);
            }else{
                $explanation = '用户缴纳关税通知笨鸟失败';
                if($flag) $explanation = '识货代缴纳关税通知笨鸟失败';
                $log = array(
                    'status' =>51,
                    'order_number' =>$order_number,
                    'explanation' =>$explanation,
                    'grant_uid' =>0,
                    'grant_username' =>'auto save',
                );
                $this->saveLog($log);
                $message = array(
                    'message'=>$explanation,
                    'param'=>$param,
                    'res'=>$res,
                    'order_number'=>$order_number
                );
                tradeLog::error('confirmTax',$message);
            }
        }
    }

    /**
     *
     * 预报海带宝 * 美国仓库
     * @param object $order 订单
     * @return boolean
     */
    private function forecastHaiDaiBao($order){
        $orders = $this->getOrderInfoByExpressNumber($order->getMartExpressNumber(),array(0));
        if(count($orders)<1) return true;
        $orders_format = $this->formatHaiDaiBaoData($orders->toArray());
        $packageinfo = array();
        $lastOrderId = false;
        //是否合包了
        $isHeBao = false;
        foreach($orders->toArray() as $v)
        {
            if($lastOrderId && $lastOrderId != $v['order_number'])
            {
                $isHeBao = true;
            }
            $lastOrderId = $v['order_number'];
        }
        foreach ($orders_format['data'] as $k=>$v){
            //获取商品信息
            $goodsInfo = TrdHaitaoGoodsTable::getInstance()->find($v['gid']);
            if (!$goodsInfo){
                $log = array(
                    'status' =>51,
                    'order_number' =>$v['order_number'],
                    'explanation' =>'预报时获取不到goods信息：'.$v['goods_id'],
                    'grant_uid' =>0,
                    'grant_username' =>'crontab',
                );
                $this->saveLog($log);
                $message = array(
                    'message'=>'海带宝预报时获取不到goods信息',
                    'param'=>array('goods_id'=>$v['goods_id']),
                    'order_number'=>$v['order_number']
                );
                tradeLog::error('forecast',$message);
                return true;
                break;
            }
            $attr = json_decode($goodsInfo->getAttr(),1);
            //$ProductGroup = str_replace('_',' ',$attr['ItemAttributes']['ProductGroup']);
            //记录$ProductGroup
            //self::$redis->hset('shihuo.haitao.forecast.ProductGroup',md5($attr['ItemAttributes']['ProductGroup']),$attr['ItemAttributes']['ProductGroup']);
            $Brand = $attr['ItemAttributes']['Brand'];
//            if($ProductGroup == 'Watch') {
//                $package_name = '手表';
//            } else if($ProductGroup == 'Ce') {
//                    $package_name = '配件';
//            } else if(strpos($ProductGroup, 'Computer') !== false){
//                $package_name = '电子产品';
//            } else if(strpos($ProductGroup, 'Health') !== false){
//                $package_name = '个人护肤用品';
//            } else if(strpos($ProductGroup, 'Home') !== false){
//                $package_name = '家居用品';
//            } else {
//                $ProductGroup_zh = tradeCommon::getContents('https://fanyi.youdao.com/openapi.do?keyfrom=chrome&key=1361128838&type=data&doctype=json&version=1.2&q='.$ProductGroup);
//                $package = json_decode($ProductGroup_zh,1);
//                $package_name = '日常用品';
//                if (isset($package['translation'][0]) && !empty($package['translation'][0])){
//                    if(preg_match("/^[\x{4e00}-\x{9fa5}]+$/u",$package['translation'][0])){
//                        $package_name = $package['translation'][0];
//                    }
//                }
//            }
            $package_name = $v['title'];
            $package = array();
            $package['packagename'] = $package_name;
            $package['brand'] = preg_replace('/[^a-zA-Z]/','',substr($Brand,0,20));
            if(empty($package['brand'])) $package['brand'] = 'necessities';
            $package['type'] = '';
            $package['num'] = $v['number'];
            if (strpos($goodsInfo->getGoodsId(), 'usa') !== FALSE){
                $package['unit'] = $attr['Offers']['Offer']['OfferListing']['Price']['Amount'] * 6/100;
            } else {
                $package['unit'] = ceil($attr['Offers']['Offer']['OfferListing']['Price']['Amount'] * 100/20) / 100;
            }

            $packageinfo['packageinfo'][] = $package;
        }
        if($isHeBao)
        {
            $orders_format['address']['name'] = '覃华丽';
            $orders_format['address']['tel'] = '15900506800';
            $orders_format['address']['province'] = '上海市';
            $orders_format['address']['city'] = '虹口区';
            $orders_format['address']['street'] = '上海市市辖区宝山区宝山工业园区金勺路1438号3号楼2楼 '.$order->getMartExpressNumber();
            $orders_format['address']['cardnum'] = '510922198712121104';
        }
//      print_r($packageinfo);
        $orders_format['address']['packageinfo'] = json_encode($packageinfo);
        $res = tradeCommon::getContents('http://forecast.haidaibao.com/CompanyAddInfo.aspx?key=A54E775B48187E8E&usercode=JWMST&express='.$order->getMartExpressNumber().'&'.http_build_query($orders_format['address']));
        $result = json_decode($res,1);
//            print_r($result);

        if ($result['state'] == 0){
            foreach($orders as $k=>$v){//记录日志
                $v->setForecast(1);
                $v->save();
                $log = array(
                    'status' =>51,
                    'order_number' =>$v->getOrderNumber(),
                    'explanation' =>'海带宝(美国)预报成功'.' (id='.$v->getId().')',
                    'grant_uid' =>0,
                    'grant_username' =>'crontab',
                );
                $this->saveLog($log);
            }
            $message = array(
                'message'=>'海带宝(美国)预报成功',
                'param'=>$packageinfo,
                'res'=>$result,
                'order_number'=>$v->getOrderNumber()
            );
            tradeLog::info('forecast',$message);
            return true;
        } else {//失败
            $tradeSendMessage = new tradeSendMessage();
            foreach($orders as $k=>$v){//记录日志
                $log = array(
                    'status' =>51,
                    'order_number' =>$v->getOrderNumber(),
                    'explanation' =>'海带宝(美国)预报出错：'.$result['errorMessage'].',包裹号：'.$result['expressInfo'].' (id='.$v->getId().')',
                    'grant_uid' =>0,
                    'grant_username' =>'crontab',
                );
                $this->saveLog($log);
                $message = array(
                    'message'=>'海带宝预报失败',
                    'param'=>$packageinfo,
                    'res'=>$result,
                    'order_number'=>$v->getOrderNumber()
                );
                tradeLog::error('forecast',$message);
                //发送失败短信通知
                if(mt_rand(1,5) == 3){
                    $tradeSendMessage->send('18616378597','海带宝(美国)预报出错：'.$result['errorMessage'].',包裹号：'.$result['expressInfo'].' (id '.$v->getId().')');
                }

            }
        }
        return false;
    }

    /**
     *
     * 预报海带宝 * 日本仓库
     * @param object $order 订单
     * @return boolean
     */
    private function forecastHaiDaiBaoJP($order){
        $orders = $this->getOrderInfoByExpressNumber($order->getMartExpressNumber(), array(3));
        if(count($orders) < 1) return true;
        $orders_format = $this->formatHaiDaiBaoData($orders->toArray());
        $packageinfo = array();
        $lastOrderId = false;
        //是否合包了
        $isHeBao = false;
        foreach($orders->toArray() as $v)
        {
            if($lastOrderId && $lastOrderId != $v['order_number'])
            {
                $isHeBao = true;
            }
            $lastOrderId = $v['order_number'];
        }
        foreach ($orders_format['data'] as $k=>$v){
            //获取商品信息
            $goodsInfo = TrdHaitaoGoodsTable::getInstance()->find($v['gid']);
            if (!$goodsInfo){
                $log = array(
                    'status' =>51,
                    'order_number' =>$v['order_number'],
                    'explanation' =>'预报时获取不到goods信息：'.$v['goods_id'],
                    'grant_uid' =>0,
                    'grant_username' =>'crontab',
                );
                $this->saveLog($log);
                $message = array(
                    'message'=>'海带宝预报时获取不到goods信息',
                    'param'=>array('goods_id'=>$v['goods_id']),
                    'order_number'=>$v['order_number']
                );
                tradeLog::error('forecast',$message);
                return true;
                break;
            }
            $attr = json_decode($goodsInfo->getAttr(),1);
            //$ProductGroup = str_replace('_',' ',$attr['ItemAttributes']['ProductGroup']);
            //记录$ProductGroup
            //self::$redis->hset('shihuo.haitao.forecast.ProductGroup',md5($attr['ItemAttributes']['ProductGroup']),$attr['ItemAttributes']['ProductGroup']);
            $Brand = $attr['ItemAttributes']['Brand'];
//            if($ProductGroup == 'Watch') {
//                $package_name = '手表';
//            } else if($ProductGroup == 'Ce') {
//                $package_name = '配件';
//            } else if(strpos($ProductGroup, 'Computer') !== false){
//                $package_name = '电子产品';
//            } else if(strpos($ProductGroup, 'Health') !== false){
//                $package_name = '个人护肤用品';
//            } else if(strpos($ProductGroup, 'Home') !== false){
//                $package_name = '家居用品';
//            } else {
//                $ProductGroup_zh = tradeCommon::getContents('https://fanyi.youdao.com/openapi.do?keyfrom=chrome&key=1361128838&type=data&doctype=json&version=1.2&q='.$ProductGroup);
//                $package = json_decode($ProductGroup_zh,1);
//                $package_name = '日常用品';
//                if (isset($package['translation'][0]) && !empty($package['translation'][0])){
//                    if(preg_match("/^[\x{4e00}-\x{9fa5}]+$/u",$package['translation'][0])){
//                        $package_name = $package['translation'][0];
//                    }
//                }
//            }
            $package_name = $v['title'];
            $package = array();
            $package['packagename'] = $package_name;
            $package['brand'] = preg_replace('/[^a-zA-Z]/','',substr($Brand,0,20));
            if(empty($package['brand']) && $Brand){
                $fanyi_brand = tradeCommon::getContents('https://openapi.baidu.com/public/2.0/bmt/translate?client_id=Hs18iW3px3gQ6Yfy6Za0QGg4&from=auto&to=en&q='.$Brand);
                if ($fanyi_brand) {
                    $fanyi_arr = json_decode($fanyi_brand, true);
                    $package['brand'] = preg_replace('/[^a-zA-Z]/','',substr($fanyi_arr['trans_result'][0]['dst'], 0, 20));
                }
            }
            if(empty($package['brand'])) $package['brand'] = 'necessities';
            $package['type'] = '';
            $package['num'] = $v['number'];
            if (strpos($goodsInfo->getGoodsId(), 'usa') !== FALSE){
                $package['unit'] = $attr['Offers']['Offer']['OfferListing']['Price']['Amount'] * 6 / 100;
            } else {
                $package['unit'] = ceil($attr['Offers']['Offer']['OfferListing']['Price']['Amount'] * 100/20) / 100;
            }
            $packageinfo['packageinfo'][] = $package;
        }
        if($isHeBao)
        {
            $orders_format['address']['name'] = '覃华丽';
            $orders_format['address']['tel'] = '15900506800';
            $orders_format['address']['province'] = '上海市';
            $orders_format['address']['city'] = '虹口区';
            $orders_format['address']['street'] = '上海市市辖区宝山区宝山工业园区金勺路1438号3号楼2楼 '.$order->getMartExpressNumber();
            $orders_format['address']['cardnum'] = '510922198712121104';
        }
        $orders_format['address']['packageinfo'] = json_encode($packageinfo);
        $res = tradeCommon::getContents('http://forecast.haidaibao.com/JPCompanyAddInfo.aspx?key=A54E775B48187E8E&usercode=JWMST&express='.$order->getMartExpressNumber().'&'.http_build_query($orders_format['address']));
        $result = json_decode($res,1);

        if ($result['state'] == 0){
            foreach($orders as $k=>$v){//记录日志
                $v->setForecast(1);
                $v->save();
                $log = array(
                    'status' =>51,
                    'order_number' =>$v->getOrderNumber(),
                    'explanation' =>'海带宝(日本)预报成功'.' (id='.$v->getId().')',
                    'grant_uid' =>0,
                    'grant_username' =>'crontab',
                );
                $this->saveLog($log);
            }
            $message = array(
                'message'=>'海带宝(日本)预报成功',
                'param'=>$packageinfo,
                'res'=>$result,
                'order_number'=>$v->getOrderNumber()
            );
            tradeLog::info('forecast',$message);
            return true;
        } else {//失败
            $tradeSendMessage = new tradeSendMessage();
            foreach($orders as $k=>$v){//记录日志
                $log = array(
                    'status' =>51,
                    'order_number' =>$v->getOrderNumber(),
                    'explanation' =>'海带宝(日本)预报出错：'.$result['errorMessage'].',包裹号：'.$result['expressInfo'].' (id='.$v->getId().')',
                    'grant_uid' =>0,
                    'grant_username' =>'crontab',
                );
                $this->saveLog($log);
                $message = array(
                    'message'=>'海带宝(日本)预报失败',
                    'param'=>$packageinfo,
                    'res'=>$result,
                    'order_number'=>$v->getOrderNumber()
                );
                tradeLog::error('forecast',$message);
                //发送失败短信通知
                if(mt_rand(1,5) == 3){
                    $tradeSendMessage->send('18616378597','海带宝(日本)预报出错：'.$result['errorMessage'].',包裹号：'.$result['expressInfo'].' (id '.$v->getId().')');
                }

            }
        }
        return false;
    }

    //根据包裹号获取订单
    protected function getOrderInfoByExpressNumber($express_num,$type = array())
    {
        $query = TrdOrderTable::getInstance()->createQuery('m')
            ->select('*');
        if(is_array($express_num)){
            $query = $query->whereIn('mart_express_number',$express_num);
        } else {
            $query = $query->where('mart_express_number = ?',$express_num);
        }
        $query = $query->andWhere('status = ?',1)
            ->andWhere('pay_status = ?',1)
            ->andWhere('forecast = ?',0)
            ->andWhereIn('delivery_type',$type);
        return $query->execute();
    }

    //根据包裹号获取物流记录
    protected function getLogisticsInfoByExpressNumber($express_num)
    {
        $query = TrdOrderLogisticsTable::getInstance()->createQuery('m')
            ->select('*');
        if(is_array($express_num)){
            $query = $query->whereIn('express_number',$express_num);
        } else {
            $query = $query->where('express_number = ?',$express_num);
        }
        return $query->execute();
    }
    //格式化数据
    private function formatHaiDaiBaoData($return){
        $res = array();
        foreach($return as $k=>$v){
            if (isset($res['data'][$v['goods_id']])){
                $res['data'][$v['goods_id']]['number'] += 1;
            } else {
                $res['data'][$v['goods_id']] = $v;
                $res['data'][$v['goods_id']]['number'] = 1;
            }
            if (!isset($res['mainOrder'])){
                $mainObj = TrdMainOrderTable::getInstance()->findOneByOrderNumber($v['order_number']);
                if ($mainObj->getAddressAttr()){
                    $address_attr = json_decode($mainObj->getAddressAttr(),1);
                    $res['address']['name'] = $address_attr['name'];
                    $res['address']['tel'] = trim($address_attr['mobile']);
                    $res['address']['province'] = $address_attr['province'];
                    $res['address']['city'] = $address_attr['city'];
                    $res['address']['street'] = ' '.$address_attr['province'].' '.$address_attr['city'].' '.$address_attr['area'].' '.$address_attr['street'];
                    $res['address']['cardnum'] = isset($address_attr['identity_number']) ? $address_attr['identity_number'] : '';
                } else {
                    $address = explode(' ', $mainObj->getAddress());
                    $address1 = explode('（邮编：', $mainObj->getAddress());
                    $address2 = explode('手机：', $mainObj->getAddress());
                    $rel_addr = ltrim($address1[0],$address[0]);
                    $consignee = trim($address[0]);
                    $addr = explode(' ',trim($rel_addr));
                    $res['address']['name'] = $consignee;
                    $res['address']['tel'] = trim($address2[1]);
                    $res['address']['province'] = $addr[0];
                    $res['address']['city'] = $addr[1];
                    $res['address']['street'] = $rel_addr;
                }
            }

        }
        return $res;
    }
    //保存日志
    private function saveLog($data){
        $historyObj = new TrdHaitaoOrderHistory();
        if (isset($data['status'])) $historyObj->setType($data['status']);
        if (isset($data['order_number'])) $historyObj->setOrderNumber($data['order_number']);
        if (isset($data['explanation'])) $historyObj->setExplanation($data['explanation']);
        if (isset($data['grant_uid'])) $historyObj->setGrantUid($data['grant_uid']);
        if (isset($data['grant_username'])) $historyObj->setGrantUsername($data['grant_username']);;
        $historyObj->save();
        return $historyObj->getId();
    }

    /**
     * 笨鸟参数消息 头部
     * @param type $eventType 时间类型
     * @return arary
     */
    private function getCommonParam($eventType){
        $param ['logisticsEventsRequest']['logisticsEvent']['eventHeader']['eventType'] = $eventType;
        $param ['logisticsEventsRequest']['logisticsEvent']['eventHeader']['eventTime'] = date("Y-m-d H:i:s");
        $param ['logisticsEventsRequest']['logisticsEvent']['eventHeader']['eventSource'] = 'ShiHuo';
        $param ['logisticsEventsRequest']['logisticsEvent']['eventHeader']['eventTarget'] = 'Birdex';
        return $param;
    }

    /**
     *
     * 公用发送报文
     * @param array $content
     */
    public function sendMessage($content,$msg_type){
        $msg_type = $this->birdex_msg[$msg_type];
        $logistics_interface = json_encode($content);
        $data_digest = base64_encode(md5($logistics_interface.$this->key));
        $param = array(
            'logistics_interface'=>$logistics_interface,
            'data_digest'=>$data_digest,
            'partner_code'=>'ShiHuo',
            'msg_type'=>$msg_type
        );
        return tradeCommon::getContents($this->post_url, $param, 20, 'POST');
    }

    //获取需要预报的数据
    protected function getForecastInfo($limit = 20)
    {
        $query =  TrdOrderTable::getInstance()->createQuery('m')
            ->select('mart_express_number')
            ->where('mart_express_number is not null and mart_express_number !=""')
            ->andWhere('status = ?',1)
            ->andWhere('pay_status = ?',1)
            ->andWhere('forecast = ?',0)
            ->andWhereNotIn('delivery_type', array(2, 5, 6))
            ->groupBy('mart_express_number')
            //->addGroupBy('gid')
            //->addGroupBy('delivery_type')
            ->limit($limit);
        return $query->execute();
    }

    //获取需要预报的数据
    protected function getForecastInfoByOrderNumber($data)
    {
        $query =  TrdOrderTable::getInstance()->createQuery('m')
            ->select('*,count(id) as count')
            ->whereIn('order_number',$data)
            ->andWhere('mart_express_number is not null and mart_express_number !=""')
            ->andWhere('status = ?',1)
            ->andWhere('pay_status = ?',1)
            ->andWhere('forecast = ?',0)
            ->andWhereNotIn('delivery_type', array(2, 5, 6))
            ->groupBy('mart_express_number')
            ->addGroupBy('gid')
            ->addGroupBy('delivery_type');
        return $query->execute();
    }

    //根据包裹号获取订单号
    protected function getDataInfoByExpressNumber($express_number)
    {
        $query =  TrdOrderTable::getInstance()->createQuery('m')
            ->select('*,count(id) as count')
            ->where('mart_express_number = ?',$express_number)
            ->andWhere('forecast = ?',0)
            ->groupBy('gid');
        return $query->execute();
    }

    //根据包裹号获取所有的订单号
    protected function getAllInfoByExpressNumber($express_number)
    {
        if (empty($express_number)) return false;
        $query =  TrdOrderTable::getInstance()->createQuery('m')
            ->select('count(id) as count,order_number,mart_express_number')
            ->whereIn('mart_express_number',$express_number)
            ->groupBy('order_number');
        return $query->execute();
    }

    //判断是否需要快转模式
    protected function getModeFlag($order_number, $express_number, $delivery_type = 1){
        return TrdOrderTable::getInstance()->createQuery()->where('order_number = ?',$order_number)->andWhere('mart_express_number <> ?',$express_number)->andWhere('delivery_type = ?', $delivery_type)->andWhere('pay_status = ?',1)->count();
    }

    //获取订单号
    protected function getAddress($order_number){
        $mainObj = TrdMainOrderTable::getInstance()->findOneByOrderNumber($order_number);
//        $address = explode(' ', $mainObj->getAddress());
//        $address1 = explode('（邮编：', $mainObj->getAddress());
//        $address2 = explode('手机：', $mainObj->getAddress());
//        $rel_addr = ltrim($address1[0],$address[0]);
//        $consignee = trim($address[0]);
//        $addr = explode(' ',trim($rel_addr));
//        $zipCode = explode('） 手机：',$address1[1]);
//        $res['name'] = $consignee;
//        $res['mobile'] = trim($address2[1]);
//        $res['identityNumber'] = '320821199902034543';
//        $res['country'] = '中国';
//        $res['province'] = $addr[0];
//        $res['city'] = $addr[1];
//        $res['district'] = count($addr) == 4 ? $addr[2] : $addr[1];
//        $res['streetAddress'] = $rel_addr;
//        $res['zipCode'] = $zipCode[0];

        $address_attr = $mainObj->getAddressAttr();
        $address = json_decode($address_attr,1);
        $res['name'] = $address['name'];
        $res['mobile'] = $address['mobile'];
        $res['identityNumber'] = $address['identity_number'];
        $res['country'] = '中国';
        $res['province'] = $address['province'];
        $res['city'] =$address['city'];
        $res['district'] = isset($address['area']) ? $address['area'] : '';
        $res['streetAddress'] = $address['street'];
        $res['zipCode'] = $address['postcode'];


        //拼接地址
        $area = isset($address['area']) ? $address['area'] : $address['city'];
        $address_content = array(
            'name'=>$address['name'],
            'tel'=>$address['mobile'],
            'addr'=>$address['province'].$address['city'].' '.$address['province'].' '.$address['city'].' '.$area.' '.$address['street']
        );
        return array('res'=>$res,'addr'=>$address_content);
        //return $res;

    }

    /**
     *
     * 处理笨鸟回传消息
     * @param array $content
     */
    public function receiveMessage($logistics_interface,$msg_type,$data_digest){
        if(!$msg_type || !$logistics_interface || !$data_digest) return $this->getReturn(false,'S05','非法的通知内容');
        if($data_digest == base64_encode(md5($logistics_interface.$this->key))){
            return $this->getReturn(false,'S02','非法的数字签名');
        }
        $data = json_decode($logistics_interface,1);
        if (!$data){
            return $this->getReturn(false,'S01','非法的XML/JSON');
        }
        //$content = '{"logisticsEventsRequest":{"logisticsEvent":{"eventHeader":{"eventType":"WMS_STOCKIN_INFO","eventTime":"2015-11-20 13:45:18","eventSource":"Birdex","eventTarget":"shihuocn"},"eventBody":{"tradeDetail":{"tradeOrders":[{"tradeOrderId":1511199799916693,"tradeOrderValue":null,"tradeOrderValueUnit":null,"tradeRemark":null,"procurementPlatform":null,"logisticsOrder":{"logisticsId":"182137","logisticsIdIncluded":null,"occurTime":"2015-11-20 13:45:36","logisticsRemark":"","segmentCode":null,"carrierCode":null,"trackingNo":"zws22222222","logisticsWeight":null,"volume":null,"logisticsErrorImgUrl":"","logisticsCode":"SUCCESS","logisticsCustomsDutys":null},"procurementOrderCode":null,"items":[{"itemId":null,"itemName":null,"itemNo":"usa.amazon.B00L3KO5WK","itemCategoryName":null,"itemUnitPrice":0,"itemQuantity":2,"itemImage":null,"itemUrl":null,"itemRemark":"","itemOrderDtlID":"91502","itemStatus":"SUCCESS","trackingNo":null,"itemBrand":null,"itemModel":null}],"receiverDetail":null}]},"purchasingDetail":null}}}}';
//        $content = '{"logisticsEventsRequest":{"logisticsEvent":{"eventHeader":{"eventType":"WMS_GOODS_WEIGHT","eventTime":"2015-03-27 04:31:17","eventSource":"Birdex","eventTarget":"shihuocn"},"eventBody":{"tradeDetail":{"tradeOrders":[{"tradeOrderId":1503301364304774,"tradeOrderValue":null,"tradeOrderValueUnit":null,"tradeRemark":null,"procurementPlatform":null,"logisticsOrder":{"logisticsId":"1ZA87E540315802414","logisticsIdIncluded":null,"occurTime":"2015-03-27 12:31:17","logisticsRemark":"","segmentCode":null,"carrierCode":"Birdex","trackingNo":"1ZA87E540315802414","logisticsWeight":"1590","volume":{"length":0,"width":0,"height":0},"logisticsErrorImgUrl":null,"logisticsCode":null,"logisticsCustomsDutys":null},"procurementOrderCode":null,"items":null,"receiverDetail":null}]},"purchasingDetail":null}}}}';
//        $content = '{"logisticsEventsRequest":{"logisticsEvent":{"eventHeader":{"eventType":"WMS_STOCKOUT_INFO","eventTime":"2015-03-27 14:32:50","eventSource":"Birdex","eventTarget":"shihuocn"},"eventBody":{"tradeDetail":{"tradeOrders":[{"tradeOrderId":1503301364304774,"tradeOrderValue":null,"tradeOrderValueUnit":null,"tradeRemark":null,"procurementPlatform":null,"logisticsOrder":{"logisticsId":"1503301364304774","logisticsIdIncluded":null,"occurTime":"2015-03-27 14:44:55","logisticsRemark":"","segmentCode":"","carrierCode":"Birdex","trackingNo":"1ZA87E540315802414","logisticsWeight":null,"volume":null,"logisticsErrorImgUrl":null,"logisticsCode":"ACCEPT","logisticsCustomsDutys":null},"procurementOrderCode":null,"items":null,"receiverDetail":null}]},"purchasingDetail":null}}}}';
//        $content = '{"logisticsEventsRequest":{"logisticsEvent":{"eventHeader":{"eventType":"TMS_CLEAR_CUSTOMS_INFO","eventTime":"2015-03-27 09:18:03","eventSource":"Birdex","eventTarget":"shihuocn"},"eventBody":{"tradeDetail":{"tradeOrders":[{"tradeOrderId":1503301364304774,"tradeOrderValue":null,"tradeOrderValueUnit":null,"tradeRemark":null,"procurementPlatform":null,"logisticsOrder":{"logisticsId":"1503301364304774","logisticsIdIncluded":null,"occurTime":"2015-03-27 15:14:54","logisticsRemark":"","segmentCode":"","carrierCode":"Birdex","trackingNo":"1ZA87E540315802414","logisticsWeight":null,"volume":null,"logisticsErrorImgUrl":null,"logisticsCode":"SUCCESS","logisticsCustomsDutys":null},"procurementOrderCode":null,"items":null,"receiverDetail":null}]},"purchasingDetail":null}}}}';
//        $content = '{"logisticsEventsRequest":{"logisticsEvent":{"eventHeader":{"eventType":"TMS_CUSTOMS_DUTYS_INFO","eventTime":"2015-03-27 12:16:33","eventSource":"Birdex","eventTarget":"shihuocn"},"eventBody":{"tradeDetail":{"tradeOrders":[{"tradeOrderId":1503301364304774,"tradeOrderValue":null,"tradeOrderValueUnit":null,"tradeRemark":null,"procurementPlatform":null,"logisticsOrder":{"logisticsId":"1503301364304774","logisticsIdIncluded":null,"occurTime":"2015-03-27 15:31:23","logisticsRemark":"缴纳关税","segmentCode":"","carrierCode":"","trackingNo":"","logisticsWeight":null,"volume":null,"logisticsErrorImgUrl":null,"logisticsCode":null,"logisticsCustomsDutys":"6600.00"},"procurementOrderCode":null,"items":null,"receiverDetail":null}]},"purchasingDetail":null}}}}';
//        $content = '{"logisticsEventsRequest":{"logisticsEvent":{"eventHeader":{"eventType":"TMS_DISPATCH_INFO","eventTime":"2015-03-27 09:38:54","eventSource":"Birdex","eventTarget":"shihuocn"},"eventBody":{"tradeDetail":{"tradeOrders":[{"tradeOrderId":1503301364304774,"tradeOrderValue":null,"tradeOrderValueUnit":null,"tradeRemark":null,"procurementPlatform":null,"logisticsOrder":{"logisticsId":"1503301364304774","logisticsIdIncluded":null,"occurTime":"2015-03-27 09:38:51","logisticsRemark":"","segmentCode":null,"carrierCode":"顺丰","trackingNo":"5675933651","logisticsWeight":null,"volume":null,"logisticsErrorImgUrl":null,"logisticsCode":null},"procurementOrderCode":null,"items":null,"receiverDetail":null}]}}}}}';
//        $content = '{"logisticsEventsRequest":{"logisticsEvent":{"eventHeader":{"eventType":"TMS_DISPATCH_TRACKINGINFO","eventTime":"2015-12-25 12:11:31","eventSource":"Birdex","eventTarget":"wangbo"},"eventBody":{"tradeDetail":{"tradeOrders":[{"tradeOrderId":201512211110002,"tradeOrderValue":null,"tradeOrderValueUnit":null,"tradeRemark":null,"procurementPlatform":null,"logisticsOrder":{"logisticsId":"SH1512148333267104635374","logisticsIdIncluded":null,"occurTime":"2015-12-25 12:17:14","logisticsRemark":"航空：宝贝已经到达目的口岸，等待提货","segmentCode":null,"carrierCode":null,"trackingNo":null,"logisticsWeight":null,"volume":null,"logisticsErrorImgUrl":null,"logisticsCode":null,"logisticsCustomsDutys":null,"trackingCode":"AVIATION","firstName":null},"procurementOrderCode":null,"items":null,"receiverDetail":null,"extraService":null,"checkOutMode":0}]},"purchasingDetail":null}}}}';
//        $msg_type = 'birdex.logistics.event.tms.dispatch';
//        $data = json_decode($content,1);
        $eventType = $data['logisticsEventsRequest']['logisticsEvent']['eventHeader']['eventType'];
        switch ($eventType) {
            case 'WMS_STOCKIN_INFO'://仓库回传入库信息
                return $this->WMS_STOCKIN_INFO($data['logisticsEventsRequest']['logisticsEvent']['eventBody']);
                break;
            case 'WMS_GOODS_WEIGHT'://仓库回传称重信息
                return  $this->WMS_GOODS_WEIGHT($data['logisticsEventsRequest']['logisticsEvent']['eventBody']);
                break;
            case 'WMS_INNER_EXCEPTION'://仓库回传仓内异常信息
                return  $this->WMS_INNER_EXCEPTION($data['logisticsEventsRequest']['logisticsEvent']['eventBody']);
                break;
            case 'WMS_STOCKOUT_INFO'://仓库回传出仓信息
                return  $this->WMS_STOCKOUT_INFO($data['logisticsEventsRequest']['logisticsEvent']['eventBody']);
                break;
            case 'TMS_CLEAR_CUSTOMS_INFO'://清关公司回传清关信息
                return  $this->TMS_CLEAR_CUSTOMS_INFO($data['logisticsEventsRequest']['logisticsEvent']['eventBody']);
                break;
            case 'TMS_CUSTOMS_DUTYS_INFO'://笨鸟海淘回传关税信息
                return  $this->TMS_CUSTOMS_DUTYS_INFO($data['logisticsEventsRequest']['logisticsEvent']['eventBody']);
                break;
            case 'TMS_DISPATCH_INFO'://笨鸟海淘回传派送信息
                sleep(1);//延迟一秒执行 防止笨鸟的接口清关和派送信息一起发送
                return  $this->TMS_DISPATCH_INFO($data['logisticsEventsRequest']['logisticsEvent']['eventBody']);
                break;
            case 'TMS_DISPATCH_TRACKINGINFO'://笨鸟海淘回传航空消息
                return  $this->TMS_DISPATCH_TRACKINGINFO($data['logisticsEventsRequest']['logisticsEvent']['eventBody']);
                break;
            default:
                break;
        }

        return $this->getReturn(false,'UNKNOWN','未知的错误');
    }

    /**
     *
     * 仓库回传入库信息
     */
    private function WMS_STOCKIN_INFO($data){
        if(!isset($data['tradeDetail']['tradeOrders'])) {
            $tradeOrder = array($data['tradeDetail']['tradeOrder']);
        } else {
            $tradeOrder = $data['tradeDetail']['tradeOrders'];
        }
        foreach($tradeOrder as $k=>$v){
            if ($v['logisticsOrder']['logisticsId']){
                $expressNumberObj = TrdOrderLogisticsTable::getInstance()->find($v['logisticsOrder']['logisticsId']);
            }
            if(!$expressNumberObj && isset($v['logisticsOrder']['trackingNo']) && !empty($v['logisticsOrder']['trackingNo'])){
                $expressNumberObj = TrdOrderLogisticsTable::getInstance()->findOneByExpressNumber($v['logisticsOrder']['trackingNo']);
            }
            if(!$expressNumberObj) continue;
            $expressNumberObj->set('foreign_status',1);//已入库
            if($expressNumberObj->getContent()){
                $content = json_decode($expressNumberObj->getContent(),1);
            }
            $content['us_in_date'] = $v['logisticsOrder']['occurTime'];
            if(strtolower($v['logisticsOrder']['logisticsCode']) != 'success'){//入库失败
                $content['error']['stockin'] = $v['logisticsOrder'];
            }
            $expressNumberObj->set('content',json_encode($content));
            $expressNumberObj->save();
        }
        return $this->getReturn();
    }

    /**
     *
     * 仓库回传称重信息
     */
    private function WMS_GOODS_WEIGHT($data){
        if(!isset($data['tradeDetail']['tradeOrders'])) {
            $tradeOrder = array($data['tradeDetail']['tradeOrder']);
        } else {
            $tradeOrder = $data['tradeDetail']['tradeOrders'];
        }
        foreach($tradeOrder as $k=>$v){
            $order_number = $v['logisticsOrder']['logisticsId'];
            $expressNumberArr_new = $expressNumberArr = array();
            if(strlen($order_number) == 16 && preg_match("/\d{16}/",$order_number)){
                $orderList = TrdOrderTable::getInstance()->createQuery('m')->select('*')->where('order_number = ?',$order_number)->andWhere('pay_status = ?',1)->andWhere('delivery_type = ?',1)->execute();
                foreach($orderList as $m=>$n){
                    if (!$n->getMartExpressNumber() || $n->getForecast() == 0) return false;
                    array_push($expressNumberArr,$n->getMartExpressNumber());
                }
                $expressNumberArr_new = array_unique($expressNumberArr);
            } else if(strlen($order_number) == 20 && preg_match("/SHSS\d{16}/",$order_number)){
                $order_number = str_replace("SHSS",'',$order_number);
                $orderList = TrdOrderTable::getInstance()->createQuery('m')->select('*')->where('order_number = ?',$order_number)->andWhere('pay_status = ?',1)->andWhere('delivery_type = ?',2)->execute();
                foreach($orderList as $m=>$n){
                    if (!$n->getMartExpressNumber() || $n->getForecast() == 0) return false;
                    array_push($expressNumberArr,$n->getMartExpressNumber());
                }
                $expressNumberArr_new = array_unique($expressNumberArr);
            } else if(strlen($order_number) == 20 && preg_match("/SHHK\d{16}/",$order_number)){
                $order_number = str_replace("SHHK",'',$order_number);
                $orderList = TrdOrderTable::getInstance()->createQuery('m')->select('*')->where('order_number = ?',$order_number)->andWhere('pay_status = ?',1)->andWhere('delivery_type = ?',4)->execute();
                foreach($orderList as $m=>$n){
                    if (!$n->getMartExpressNumber() || $n->getForecast() == 0) return false;
                    array_push($expressNumberArr,$n->getMartExpressNumber());
                }
                $expressNumberArr_new = array_unique($expressNumberArr);
            } else if(strlen($order_number) == 24 && preg_match("/SH\d{22}/",$order_number)){
                $softList = TrdOrderSoftingTable::getInstance()->createQuery('m')->select('*')->where('softing_id = ?',$order_number)->execute();
                foreach($softList as $m=>$n){
                    array_push($expressNumberArr,$n->getExpressNumber());
                }
                $expressNumberArr_new = array_unique($expressNumberArr);
            }
            if(empty($expressNumberArr_new)) $expressNumberArr_new = (array)$order_number;
            foreach($expressNumberArr_new as $express){
                $expressNumberObj = TrdOrderLogisticsTable::getInstance()->findOneByExpressNumber($express);
                if($expressNumberObj){
                    if($expressNumberObj->getContent()){
                        $content = json_decode($expressNumberObj->getContent(),1);
                    }
                    $content['weight_date'] = $v['logisticsOrder']['occurTime'];
                    $content['weight']['logisticsWeight'] = $v['logisticsOrder']['logisticsWeight'];
                    $content['weight']['volume'] = $v['logisticsOrder']['volume'];

                    $expressNumberObj->set('content',json_encode($content));
                    $expressNumberObj->save();
                }
            }
        }
        return $this->getReturn();
    }
    
    /**
     *
     * 仓库回传仓内异常信息
     */
    private function WMS_INNER_EXCEPTION($data){
        if(!isset($data['tradeDetail']['tradeOrders'])) {
            $tradeOrder = array($data['tradeDetail']['tradeOrder']);
        } else {
            $tradeOrder = $data['tradeDetail']['tradeOrders'];
        }
        foreach($tradeOrder as $k=>$v){
            $order_number = $v['logisticsOrder']['logisticsId'];
            $expressNumberArr_new = $expressNumberArr = array();
            if(strlen($order_number) == 16 && preg_match("/\d{16}/",$order_number)){
                $orderList = TrdOrderTable::getInstance()->createQuery('m')->select('*')->where('order_number = ?',$order_number)->andWhere('pay_status = ?',1)->andWhere('delivery_type = ?',1)->execute();
                foreach($orderList as $m=>$n){
                    if (!$n->getMartExpressNumber() || $n->getForecast() == 0) return false;
                    array_push($expressNumberArr,$n->getMartExpressNumber());
                }
                $expressNumberArr_new = array_unique($expressNumberArr);
            } else if(strlen($order_number) == 20 && preg_match("/SHSS\d{16}/",$order_number)){
                $order_number = str_replace("SHSS",'',$order_number);
                $orderList = TrdOrderTable::getInstance()->createQuery('m')->select('*')->where('order_number = ?',$order_number)->andWhere('pay_status = ?',1)->andWhere('delivery_type = ?',2)->execute();
                foreach($orderList as $m=>$n){
                    if (!$n->getMartExpressNumber() || $n->getForecast() == 0) return false;
                    array_push($expressNumberArr,$n->getMartExpressNumber());
                }
                $expressNumberArr_new = array_unique($expressNumberArr);
            } else if(strlen($order_number) == 20 && preg_match("/SHHK\d{16}/",$order_number)){
                $order_number = str_replace("SHHK",'',$order_number);
                $orderList = TrdOrderTable::getInstance()->createQuery('m')->select('*')->where('order_number = ?',$order_number)->andWhere('pay_status = ?',1)->andWhere('delivery_type = ?',4)->execute();
                foreach($orderList as $m=>$n){
                    if (!$n->getMartExpressNumber() || $n->getForecast() == 0) return false;
                    array_push($expressNumberArr,$n->getMartExpressNumber());
                }
                $expressNumberArr_new = array_unique($expressNumberArr);
            } else if(strlen($order_number) == 24 && preg_match("/SH\d{22}/",$order_number)){
                $softList = TrdOrderSoftingTable::getInstance()->createQuery('m')->select('*')->where('softing_id = ?',$order_number)->execute();
                foreach($softList as $m=>$n){
                    array_push($expressNumberArr,$n->getExpressNumber());
                }
                $expressNumberArr_new = array_unique($expressNumberArr);
            }
            if(empty($expressNumberArr_new)) $expressNumberArr_new = (array)$order_number;
            foreach($expressNumberArr_new as $express){
                $expressNumberObj = TrdOrderLogisticsTable::getInstance()->findOneByExpressNumber($express);
                if($expressNumberObj){
                    if($expressNumberObj->getContent()){
                        $content = json_decode($expressNumberObj->getContent(),1);
                    }
                    $content['exception_date'] = $v['logisticsOrder']['occurTime'];
                    $content['exception']['logisticsCode'] = $v['logisticsOrder']['logisticsCode'];
                    $content['exception']['logisticsRemark'] = $v['logisticsOrder']['logisticsRemark'];

                    $expressNumberObj->set('content',json_encode($content));
                    $expressNumberObj->set('foreign_status',31);//仓内异常
                    $expressNumberObj->save();
                }
            }
        }
        return $this->getReturn();
    }
    
    /**
     *
     * 仓库回传出仓信息
     */
    private function WMS_STOCKOUT_INFO($data){
        if(!isset($data['tradeDetail']['tradeOrders'])) {
            $tradeOrder = array($data['tradeDetail']['tradeOrder']);
        } else {
            $tradeOrder = $data['tradeDetail']['tradeOrders'];
        }
        foreach($tradeOrder as $k=>$v){
            $order_number = $v['logisticsOrder']['logisticsId'];
            $expressNumberArr_new = $expressNumberArr = array();
            if(strlen($order_number) == 16 && preg_match("/\d{16}/",$order_number)){
                $orderList = TrdOrderTable::getInstance()->createQuery('m')->select('*')->where('order_number = ?',$order_number)->andWhere('pay_status = ?',1)->andWhere('delivery_type = ?',1)->execute();
                foreach($orderList as $m=>$n){
                    if (!$n->getMartExpressNumber() || $n->getForecast() == 0) return false;
                    array_push($expressNumberArr,$n->getMartExpressNumber());
                }
                $expressNumberArr_new = array_unique($expressNumberArr);
            } else if(strlen($order_number) == 20 && preg_match("/SHSS\d{16}/",$order_number)){
                $order_number = str_replace("SHSS",'',$order_number);
                $orderList = TrdOrderTable::getInstance()->createQuery('m')->select('*')->where('order_number = ?',$order_number)->andWhere('pay_status = ?',1)->andWhere('delivery_type = ?',2)->execute();
                foreach($orderList as $m=>$n){
                    if (!$n->getMartExpressNumber() || $n->getForecast() == 0) return false;
                    array_push($expressNumberArr,$n->getMartExpressNumber());
                }
                $expressNumberArr_new = array_unique($expressNumberArr);
            } else if(strlen($order_number) == 20 && preg_match("/SHHK\d{16}/",$order_number)){
                $order_number = str_replace("SHHK",'',$order_number);
                $orderList = TrdOrderTable::getInstance()->createQuery('m')->select('*')->where('order_number = ?',$order_number)->andWhere('pay_status = ?',1)->andWhere('delivery_type = ?',4)->execute();
                foreach($orderList as $m=>$n){
                    if (!$n->getMartExpressNumber() || $n->getForecast() == 0) return false;
                    array_push($expressNumberArr,$n->getMartExpressNumber());
                }
                $expressNumberArr_new = array_unique($expressNumberArr);
            } else if(strlen($order_number) == 24 && preg_match("/SH\d{22}/",$order_number)){
                $softList = TrdOrderSoftingTable::getInstance()->createQuery('m')->select('*')->where('softing_id = ?',$order_number)->execute();
                foreach($softList as $m=>$n){
                    array_push($expressNumberArr,$n->getExpressNumber());
                }
                $expressNumberArr_new = array_unique($expressNumberArr);
            }
            if(empty($expressNumberArr_new)) $expressNumberArr_new = (array)$order_number;
            foreach($expressNumberArr_new as $express){
                $expressNumberObj = TrdOrderLogisticsTable::getInstance()->findOneByExpressNumber($express);
                if($expressNumberObj){
                    if($expressNumberObj->getContent()){
                        $content = json_decode($expressNumberObj->getContent(),1);
                    }
                    if(strtolower($v['logisticsOrder']['logisticsCode']) == 'stockout'){//出库
                        $expressNumberObj->set('foreign_status',2);//已出仓
                        $content['us_out_date'] = $v['logisticsOrder']['occurTime'];
                        $content['stockout']['stockout_trackingno'] = $v['logisticsOrder']['trackingNo'];
                        $content['stockout']['stockout_date'] = $v['logisticsOrder']['occurTime'];
                        $content['stockout']['stockout_remark'] = $v['logisticsOrder']['logisticsRemark'];
                    }else if(strtolower($v['logisticsOrder']['logisticsCode']) == 'air'){//航空干线运输中
                        $expressNumberObj->set('foreign_status',3);//航空干线运输中
                        $content['depart_date'] = $v['logisticsOrder']['occurTime'];
                        $content['stockout']['air_date'] = $v['logisticsOrder']['occurTime'];
                        $content['stockout']['air_remark'] = $v['logisticsOrder']['logisticsRemark'];
                    }else if(strtolower($v['logisticsOrder']['logisticsCode']) == 'airport'){//到达某机场
                        $expressNumberObj->set('foreign_status',4);//到达某机场
                        $content['land_date'] = $v['logisticsOrder']['occurTime'];
                        $content['stockout']['airport_date'] = $v['logisticsOrder']['occurTime'];
                        $content['stockout']['airport_remark'] = $v['logisticsOrder']['logisticsRemark'];
                    }elseif(strtolower($v['logisticsOrder']['logisticsCode']) == 'accept'){//快件中心接受
                        $expressNumberObj->set('foreign_status',21);//快件中心接受
                        $content['stockout']['accept_date'] = $v['logisticsOrder']['occurTime'];
                        $content['stockout']['accept_remark'] = $v['logisticsOrder']['logisticsRemark'];
                    }
                    $expressNumberObj->set('content',json_encode($content));
                    $expressNumberObj->save();
                }
            }
        }
        return $this->getReturn();
    }

    /**
     *
     * 清关公司回传清关信息
     */
    private function TMS_CLEAR_CUSTOMS_INFO($data){
        if(!isset($data['tradeDetail']['tradeOrders'])) {
            $tradeOrder = array($data['tradeDetail']['tradeOrder']);
        } else {
            $tradeOrder = $data['tradeDetail']['tradeOrders'];
        }
        foreach($tradeOrder as $k=>$v){
            $order_number = $v['logisticsOrder']['logisticsId'];
            $expressNumberArr_new = $expressNumberArr = array();
            if(strlen($order_number) == 16 && preg_match("/\d{16}/",$order_number)){
                $orderList = TrdOrderTable::getInstance()->createQuery('m')->select('*')->where('order_number = ?',$order_number)->andWhere('pay_status = ?',1)->andWhere('delivery_type = ?',1)->execute();
                foreach($orderList as $m=>$n){
                    if (!$n->getMartExpressNumber() || $n->getForecast() == 0) return false;
                    array_push($expressNumberArr,$n->getMartExpressNumber());
                }
                $expressNumberArr_new = array_unique($expressNumberArr);
            } else if(strlen($order_number) == 20 && preg_match("/SHSS\d{16}/",$order_number)){
                $order_number = str_replace("SHSS",'',$order_number);
                $orderList = TrdOrderTable::getInstance()->createQuery('m')->select('*')->where('order_number = ?',$order_number)->andWhere('pay_status = ?',1)->andWhere('delivery_type = ?',2)->execute();
                foreach($orderList as $m=>$n){
                    if (!$n->getMartExpressNumber() || $n->getForecast() == 0) return false;
                    array_push($expressNumberArr,$n->getMartExpressNumber());
                }
                $expressNumberArr_new = array_unique($expressNumberArr);
            } else if(strlen($order_number) == 20 && preg_match("/SHHK\d{16}/",$order_number)){
                $order_number = str_replace("SHHK",'',$order_number);
                $orderList = TrdOrderTable::getInstance()->createQuery('m')->select('*')->where('order_number = ?',$order_number)->andWhere('pay_status = ?',1)->andWhere('delivery_type = ?',4)->execute();
                foreach($orderList as $m=>$n){
                    if (!$n->getMartExpressNumber() || $n->getForecast() == 0) return false;
                    array_push($expressNumberArr,$n->getMartExpressNumber());
                }
                $expressNumberArr_new = array_unique($expressNumberArr);
            } else if(strlen($order_number) == 24 && preg_match("/SH\d{22}/",$order_number)){
                $softList = TrdOrderSoftingTable::getInstance()->createQuery('m')->select('*')->where('softing_id = ?',$order_number)->execute();
                foreach($softList as $m=>$n){
                    array_push($expressNumberArr,$n->getExpressNumber());
                }
                $expressNumberArr_new = array_unique($expressNumberArr);
            }
            if(empty($expressNumberArr_new)) $expressNumberArr_new = (array)$order_number;
            foreach($expressNumberArr_new as $express){
                $expressNumberObj = TrdOrderLogisticsTable::getInstance()->findOneByExpressNumber($express);
                if($expressNumberObj){
                    if($expressNumberObj->getContent()){
                        $content = json_decode($expressNumberObj->getContent(),1);
                    }
                    $foreign_status = $expressNumberObj->get('foreign_status');
                    if(strtolower($v['logisticsOrder']['logisticsCode']) == 'success'){//清关成功
                        if($foreign_status != 6) $expressNumberObj->set('foreign_status',5);//已清关
                        $content['clear_date'] = $v['logisticsOrder']['occurTime'];
                        $content['clear']['success_date'] = $v['logisticsOrder']['occurTime'];
                    }else if(strtolower($v['logisticsOrder']['logisticsCode']) == 'prepare'){//欲报关
                        $expressNumberObj->set('foreign_status',22);//欲报关
                        //$content['land_date'] = $v['logisticsOrder']['occurTime'];
                        $content['clear']['prepare_date'] = $v['logisticsOrder']['occurTime'];
                    }else if(strtolower($v['logisticsOrder']['logisticsCode']) == 'start'){//开始报关
                        $expressNumberObj->set('foreign_status',23);//开始报关
                        $content['clear']['start_date'] = $v['logisticsOrder']['occurTime'];
                    }else if(strtolower($v['logisticsOrder']['logisticsCode']) == 'declaring'){//清关中
                        $expressNumberObj->set('foreign_status',24);//清关中
                        $content['clear']['declaring_date'] = $v['logisticsOrder']['occurTime'];
                    }else if(strtolower($v['logisticsOrder']['logisticsCode']) == 'need_tax'){//待交关税
                        $expressNumberObj->set('foreign_status',25);//待交关税
                        $content['clear']['need_tax_date'] = $v['logisticsOrder']['occurTime'];
                    }else if(strtolower($v['logisticsOrder']['logisticsCode']) == 'policy_mismatch'){//不符合政策法规 被没收了
                        $expressNumberObj->set('foreign_status',26);//不符合政策法规 被没收了
                        $content['clear']['policy_mismatch_date'] = $v['logisticsOrder']['occurTime'];
                    }else{//其他异常原因
                        $expressNumberObj->set('foreign_status',27);//其他原因
                        $content['clear']['other_reason_date'] = $v['logisticsOrder']['occurTime'];
                    }
                    $expressNumberObj->set('content',json_encode($content));
                    $expressNumberObj->save();
                }
            }
        }
        return $this->getReturn();
    }

    /**
     *
     * 回传航空轨迹信息
     */
    private function TMS_DISPATCH_TRACKINGINFO($data){
        if(!isset($data['tradeDetail']['tradeOrders'])) {
            $tradeOrder = array($data['tradeDetail']['tradeOrder']);
        } else {
            $tradeOrder = $data['tradeDetail']['tradeOrders'];
        }
        foreach($tradeOrder as $k=>$v){
            $order_number = $v['logisticsOrder']['logisticsId'];
            $expressNumberArr_new = $expressNumberArr = array();
            if(strlen($order_number) == 16 && preg_match("/\d{16}/",$order_number)){
                $orderList = TrdOrderTable::getInstance()->createQuery('m')->select('*')->where('order_number = ?',$order_number)->andWhere('pay_status = ?',1)->andWhere('delivery_type = ?',1)->execute();
                foreach($orderList as $m=>$n){
                    if (!$n->getMartExpressNumber() || $n->getForecast() == 0) return false;
                    array_push($expressNumberArr,$n->getMartExpressNumber());
                }
                $expressNumberArr_new = array_unique($expressNumberArr);
            } else if(strlen($order_number) == 20 && preg_match("/SHSS\d{16}/",$order_number)){
                $order_number = str_replace("SHSS",'',$order_number);
                $orderList = TrdOrderTable::getInstance()->createQuery('m')->select('*')->where('order_number = ?',$order_number)->andWhere('pay_status = ?',1)->andWhere('delivery_type = ?',2)->execute();
                foreach($orderList as $m=>$n){
                    if (!$n->getMartExpressNumber() || $n->getForecast() == 0) return false;
                    array_push($expressNumberArr,$n->getMartExpressNumber());
                }
                $expressNumberArr_new = array_unique($expressNumberArr);
            } else if(strlen($order_number) == 20 && preg_match("/SHHK\d{16}/",$order_number)){
                $order_number = str_replace("SHHK",'',$order_number);
                $orderList = TrdOrderTable::getInstance()->createQuery('m')->select('*')->where('order_number = ?',$order_number)->andWhere('pay_status = ?',1)->andWhere('delivery_type = ?',4)->execute();
                foreach($orderList as $m=>$n){
                    if (!$n->getMartExpressNumber() || $n->getForecast() == 0) return false;
                    array_push($expressNumberArr,$n->getMartExpressNumber());
                }
                $expressNumberArr_new = array_unique($expressNumberArr);
            } else if(strlen($order_number) == 24 && preg_match("/SH\d{22}/",$order_number)){
                $softList = TrdOrderSoftingTable::getInstance()->createQuery('m')->select('*')->where('softing_id = ?',$order_number)->execute();
                foreach($softList as $m=>$n){
                    array_push($expressNumberArr,$n->getExpressNumber());
                }
                $expressNumberArr_new = array_unique($expressNumberArr);
            }
            if(empty($expressNumberArr_new)) $expressNumberArr_new = (array)$order_number;
            foreach($expressNumberArr_new as $express){
                $expressNumberObj = TrdOrderLogisticsTable::getInstance()->findOneByExpressNumber($express);
                if($expressNumberObj){
                    if($expressNumberObj->getContent()){
                        $content = json_decode($expressNumberObj->getContent(),1);
                    }
                    $expressNumberObj->set('foreign_status',32);//航空运输中
                    $aviation = array(
                        'time' => $v['logisticsOrder']['occurTime'],
                        'remark' => $v['logisticsOrder']['logisticsRemark'],
                    );
                    $content['aviation'][] = $aviation;
                    $expressNumberObj->set('content',json_encode($content));
                    $expressNumberObj->save();
                }
            }
        }
        return $this->getReturn();
    }

    /**
     *
     * 笨鸟海淘回传关税信息
     */
    private function TMS_CUSTOMS_DUTYS_INFO($data){
        if(!isset($data['tradeDetail']['tradeOrders'])) {
            $tradeOrder = array($data['tradeDetail']['tradeOrder']);
        } else {
            $tradeOrder = $data['tradeDetail']['tradeOrders'];
        }
        foreach($tradeOrder as $k=>$v){
            $order_number = $v['logisticsOrder']['logisticsId'];
            $expressNumberArr_new = $expressNumberArr = array();
            if(strlen($order_number) == 16 && preg_match("/\d{16}/",$order_number)){
                $orderList = TrdOrderTable::getInstance()->createQuery('m')->select('*')->where('order_number = ?',$order_number)->andWhere('pay_status = ?',1)->andWhere('delivery_type = ?',1)->execute();
                foreach($orderList as $m=>$n){
                    if (!$n->getMartExpressNumber() || $n->getForecast() == 0) return false;
                    array_push($expressNumberArr,$n->getMartExpressNumber());
                }
                $expressNumberArr_new = array_unique($expressNumberArr);
            } else if(strlen($order_number) == 20 && preg_match("/SHSS\d{16}/",$order_number)){
                $order_number = str_replace("SHSS",'',$order_number);
                $orderList = TrdOrderTable::getInstance()->createQuery('m')->select('*')->where('order_number = ?',$order_number)->andWhere('pay_status = ?',1)->andWhere('delivery_type = ?',2)->execute();
                foreach($orderList as $m=>$n){
                    if (!$n->getMartExpressNumber() || $n->getForecast() == 0) return false;
                    array_push($expressNumberArr,$n->getMartExpressNumber());
                }
                $expressNumberArr_new = array_unique($expressNumberArr);
            } else if(strlen($order_number) == 20 && preg_match("/SHHK\d{16}/",$order_number)){
                $order_number = str_replace("SHHK",'',$order_number);
                $orderList = TrdOrderTable::getInstance()->createQuery('m')->select('*')->where('order_number = ?',$order_number)->andWhere('pay_status = ?',1)->andWhere('delivery_type = ?',4)->execute();
                foreach($orderList as $m=>$n){
                    if (!$n->getMartExpressNumber() || $n->getForecast() == 0) return false;
                    array_push($expressNumberArr,$n->getMartExpressNumber());
                }
                $expressNumberArr_new = array_unique($expressNumberArr);
            } else if(strlen($order_number) == 24 && preg_match("/SH\d{22}/",$order_number)){
                $softList = TrdOrderSoftingTable::getInstance()->createQuery('m')->select('*')->where('softing_id = ?',$order_number)->execute();
                foreach($softList as $m=>$n){
                    array_push($expressNumberArr,$n->getExpressNumber());
                }
                $expressNumberArr_new = array_unique($expressNumberArr);
                $order_number = substr($order_number, 2, 16);
            }
            if(empty($expressNumberArr_new)) $expressNumberArr_new = (array)$order_number;
            foreach($expressNumberArr_new as $express){
                $expressNumberObj = TrdOrderLogisticsTable::getInstance()->findOneByExpressNumber($express);
                if($expressNumberObj){
                    if($expressNumberObj->getContent()){
                        $content = json_decode($expressNumberObj->getContent(),1);
                    }
                    $expressNumberObj->set('foreign_status',25);//待交关税
                    $content['clear']['need_tax_date'] = $v['logisticsOrder']['occurTime'];
                    $content['clear']['logisticsCustomsDutys'] = $v['logisticsOrder']['logisticsCustomsDutys'];
                    $expressNumberObj->set('content',json_encode($content));
                    $expressNumberObj->save();
                }
            }
            //保存关税信息

            $orderMainList = TrdMainOrderTable::getInstance()->findOneByOrderNumber($order_number);
            if($orderMainList){
                $orderMainList->setTaxStatus(1);//被税
                $orderMainList->setTaxTime(strtotime($v['logisticsOrder']['occurTime']));//被税时间
                $orderMainList->setTax($v['logisticsOrder']['logisticsCustomsDutys']/100);
                $orderMainList->save();
            }
        }
        return $this->getReturn();
    }

    /**
     *
     * 笨鸟海淘回传派送信息
     */
    private function TMS_DISPATCH_INFO($data){
        if(!isset($data['tradeDetail']['tradeOrders'])) {
            $tradeOrder = array($data['tradeDetail']['tradeOrder']);
        } else {
            $tradeOrder = $data['tradeDetail']['tradeOrders'];
        }
        foreach($tradeOrder as $k=>$v){
            $order_number = $v['logisticsOrder']['logisticsId'];
            $expressNumberArr_new = $expressNumberArr = array();
            if(strlen($order_number) == 16 && preg_match("/\d{16}/",$order_number)){
                $orderList = TrdOrderTable::getInstance()->createQuery('m')->select('*')->where('order_number = ?',$order_number)->andWhere('pay_status = ?',1)->andWhere('delivery_type = ?',1)->execute();
                foreach($orderList as $m=>$n){
                    if (!$n->getMartExpressNumber() || $n->getForecast() == 0) return false;
                    array_push($expressNumberArr,$n->getMartExpressNumber());
                }
                $expressNumberArr_new = array_unique($expressNumberArr);
            } else if(strlen($order_number) == 20 && preg_match("/SHSS\d{16}/",$order_number)){
                $order_number = str_replace("SHSS",'',$order_number);
                $orderList = TrdOrderTable::getInstance()->createQuery('m')->select('*')->where('order_number = ?',$order_number)->andWhere('pay_status = ?',1)->andWhere('delivery_type = ?',2)->execute();
                foreach($orderList as $m=>$n){
                    if (!$n->getMartExpressNumber() || $n->getForecast() == 0) return false;
                    array_push($expressNumberArr,$n->getMartExpressNumber());
                }
                $expressNumberArr_new = array_unique($expressNumberArr);
            } else if(strlen($order_number) == 20 && preg_match("/SHHK\d{16}/",$order_number)){
                $order_number = str_replace("SHHK",'',$order_number);
                $orderList = TrdOrderTable::getInstance()->createQuery('m')->select('*')->where('order_number = ?',$order_number)->andWhere('pay_status = ?',1)->andWhere('delivery_type = ?',4)->execute();
                foreach($orderList as $m=>$n){
                    if (!$n->getMartExpressNumber() || $n->getForecast() == 0) return false;
                    array_push($expressNumberArr,$n->getMartExpressNumber());
                }
                $expressNumberArr_new = array_unique($expressNumberArr);
            } else if(strlen($order_number) == 24 && preg_match("/SH\d{22}/",$order_number)){
                $softList = TrdOrderSoftingTable::getInstance()->createQuery('m')->select('*')->where('softing_id = ?',$order_number)->execute();
                foreach($softList as $m=>$n){
                    array_push($expressNumberArr,$n->getExpressNumber());
                }
                $expressNumberArr_new = array_unique($expressNumberArr);
            }
            if(empty($expressNumberArr_new)) $expressNumberArr_new = (array)$order_number;
            foreach($expressNumberArr_new as $express){
                $expressNumberObj = TrdOrderLogisticsTable::getInstance()->findOneByExpressNumber($express);
                if($expressNumberObj){
                    if($expressNumberObj->getContent()){
                        $content = json_decode($expressNumberObj->getContent(),1);
                    }
                    $us_in_date = strtotime($content['us_in_date']);
                    $cn_out_date = strtotime($v['logisticsOrder']['occurTime']);
                    $days = ceil(($cn_out_date - $us_in_date) / 86400);
                    $expressNumberObj->set('transit_time',$days);//此包裹转运天数

                    $expressNumberObj->set('foreign_status',6);//已发货
                    $content['cn_out_date'] = $v['logisticsOrder']['occurTime'];
                    $content['cn_expcompany'] = $v['logisticsOrder']['carrierCode'];
                    $content['cn_express'] = $v['logisticsOrder']['trackingNo'];
                    $expressNumberObj->set('content',json_encode($content));
                    $expressNumberObj->save();
                }
            }

        }
        return $this->getReturn();
    }

    /**
     *
     * @param boolen $flag 成功与否true or false
     * @param string $reason 理由编号
     * @param string $reasonDesc 理由
     * @return json
     */
    private function getReturn($flag = true,$reason = '',$reasonDesc = ''){
        $res['responses']['responseItems']['response'] = array(
            'success'=>$flag,
            'reason'=>$reason,
            'reasonDesc'=>$reasonDesc
        );
        return json_encode($res);
    }

    /**
     *
     * 手动 预报包裹
     * @param $express_number 包裹号
     * @param $type 0表示未入库 1表示已入库
     * @param $delivery_type 1表示笨鸟还未分拣 2表示该订单下已分拣
     */
    public function handForecastPackage($express_number,$type,$delivery_type){
        if(!$express_number) return array('status'=>1,'msg'=>'包裹号为空');
        $forcastInfo = $this->getDataInfoByExpressNumber($express_number);
        //var_dump($forcastInfo->toArray());die;
        if (count($forcastInfo)<0) return array('status'=>1,'msg'=>'包裹号有问题');
        $i = 0;
        $param = $this->getCommonParam('LOGISTICS_TRADE_PAID');
        $tradeOrders = array();
        $express_number = array();
        foreach($forcastInfo as $k=>$v){
            //判断是用海带宝还是笨鸟
            if($v->getDeliveryType() == 0 || $v->getDeliveryType() == 3){//海带宝
                continue;
            }
            $attr_json = json_decode($v->getAttr(),1);
            //笨鸟预报 用户下发物流包裹消息 >>包裹订单详情 》 订单详情列表
            if(isset($tradeOrders[$v->getMartExpressNumber()])){//判断是否存在了
                $tradeOrders[$v->getMartExpressNumber()]['tradeOrderId'] = $v->getOrderNumber();
                $tradeOrders[$v->getMartExpressNumber()]['tradeOrderValue'] += ($v->getDeliveryType() == 1 or $v->getDeliveryType() == 2) ? ($attr_json['price']/100)*$v['count'] : $attr_json['price']*$v['count'];
            } else {
                $tradeOrders[$v->getMartExpressNumber()]['tradeOrderId'] = $v->getOrderNumber();
                $tradeOrders[$v->getMartExpressNumber()]['tradeOrderValue'] = ($v->getDeliveryType() == 1 or $v->getDeliveryType() == 2) ? ($attr_json['price']/100)*$v['count'] : $attr_json['price']*$v['count'];
            }

            $tradeOrders[$v->getMartExpressNumber()]['tradeOrderValueUnit'] = ($v->getDeliveryType() == 1 or $v->getDeliveryType() == 2) ? "USD" : "CNY";
            $tradeOrders[$v->getMartExpressNumber()]['procurementPlatform'] = $v->getBusiness();
            $tradeOrders[$v->getMartExpressNumber()]['procurementOrderCode'] = $v->getMartOrderNumber();

            $tradeOrders[$v->getMartExpressNumber()]['checkOutMode'] = 0;//普通模式

            $logistics = TrdOrderLogisticsTable::getInstance()->findOneByExpressNumber($v->getMartExpressNumber());
            if (!$logistics){
                $logistics = new TrdOrderLogistics();
                $logistics->set('express_number',$v->getMartExpressNumber());
                $logistics->set('type',2);
                $logistics->set('foreign_status',51);
                $logistics->save();
            } else{
                $logistics->set('type',2);
                $logistics->save();
            }

            //物流订单详情
            $tradeOrders[$v->getMartExpressNumber()]['logisticsOrder']['logisticsId'] = $logistics->getId();//LP物流订单的订单号 唯一的
            $tradeOrders[$v->getMartExpressNumber()]['logisticsOrder']['trackingNo'] = $v->getMartExpressNumber();//商家物流号 唯一的
            $tradeOrders[$v->getMartExpressNumber()]['logisticsOrder']['segmentCode'] = ($v->getDeliveryType() == 1 or $v->getDeliveryType() == 2) ? 'PDX' : 'HKG';//美国俄勒冈 香港仓库

            //获取商品的属性
            $productInfo = TrdProductAttrTable::getInstance()->find($v->getProductId());
            if(!$productInfo) return;
            $category_id = $productInfo->getChildrenId();
            if($category_id){
                $itemCategoryName = $category_id."-".$this->category[$category_id];
            }else{
                $itemCategoryName = '47-日常用品';
            }

            if(isset($tradeOrders[$v->getMartExpressNumber()]['Items'])){//判断是否存在商品了
                $number = count($tradeOrders[$v->getMartExpressNumber()]['Items']);
            } else{
                $number = 0;
            }
            //订单包含的商品列表
            $tradeOrders[$v->getMartExpressNumber()]['Items'][$number]['itemId'] = $v->getProductId();
            $tradeOrders[$v->getMartExpressNumber()]['Items'][$number]['itemName'] = $v->getTitle();
            $tradeOrders[$v->getMartExpressNumber()]['Items'][$number]['itemNo'] = $v->getGoodsId();
            $tradeOrders[$v->getMartExpressNumber()]['Items'][$number]['itemCategoryName'] = $itemCategoryName;
            $tradeOrders[$v->getMartExpressNumber()]['Items'][$number]['itemUnitPrice'] = ($v->getDeliveryType() == 1 or $v->getDeliveryType() == 2) ? $attr_json['price']/100 : $attr_json['price'];
            $tradeOrders[$v->getMartExpressNumber()]['Items'][$number]['itemQuantity'] = $v['count'];
            $tradeOrders[$v->getMartExpressNumber()]['Items'][$number]['itemImage'] = $attr_json['img'];
            $tradeOrders[$v->getMartExpressNumber()]['Items'][$number]['itemUrl'] = $productInfo->getUrl();

            unset($attr_json['price']);
            unset($attr_json['name']);
            unset($attr_json['img']);
            $itemRemark = '';
            if(!empty($attr_json)){
                foreach($attr_json as $key=>$val){
                    $itemRemark .= $key.':'.$val.';';
                }
                $itemRemark = rtrim($itemRemark,';');
            }
            $tradeOrders[$v->getMartExpressNumber()]['Items'][$number]['itemRemark'] = $itemRemark;
            $i++;
            //存储 包裹号
            array_push($express_number,$v->getMartExpressNumber());
        }
        if($tradeOrders){
            sort($tradeOrders);
            $param['logisticsEventsRequest']['logisticsEvent']['eventBody']['tradeDetail']['tradeOrders'] = $tradeOrders;
        }
        if (isset($param['logisticsEventsRequest']['logisticsEvent']['eventBody']) && !empty($param['logisticsEventsRequest']['logisticsEvent']['eventBody'])){
            $result = $this->sendMessage($param,'LOGISTICS_TRADE_PAID');
            if ($result){
                $res = json_decode($result,true);
                //通过包裹号 获取所有的子商品 更新日志
                $orders = $this->getOrderInfoByExpressNumber($express_number,array(1));
                $logisticsOrders = $this->getLogisticsInfoByExpressNumber($express_number);
                if ($res['responses']['responseItems']['response']['success']){//预报成功
                    foreach($logisticsOrders as $m=>$n){
                        $logistics_content = array();
                        $n->set('foreign_status',0);
                        $logistics_content['fore_date'] = date("Y-m-d H:i:s");
                        if($type == 1){
                            $n->set('foreign_status',1);
                            $logistics_content['us_in_date'] = date("Y-m-d H:i:s");
                        }
                        $n->setContent(json_encode($logistics_content));
                        $n->save();
                    }
                    foreach($orders as $k=>$v){//记录日志
                        $v->setForecast(1);
                        $v->save();
                        $log = array(
                            'status' =>51,
                            'order_number' =>$v->getOrderNumber(),
                            'explanation' =>'backend笨鸟预报成功'.' (id='.$v->getId().')',
                            'grant_uid' =>0,
                            'grant_username' =>'backend',
                        );
                        $this->saveLog($log);
                        $message = array(
                            'message'=>'backend笨鸟预报成功'.' (id='.$v->getId().')',
                            'param'=>$param,
                            'res'=>$res,
                            'order_number'=>$v->getOrderNumber()
                        );
                        tradeLog::info('forecast',$message);
                    }
                    return array('status'=>0,'msg'=>'预报成功');
                }else{//失败
                    foreach($orders as $k=>$v){//记录日志
                        $log = array(
                            'status' =>51,
                            'order_number' =>$v->getOrderNumber(),
                            'explanation' =>'backend笨鸟预报出错:'.$res['responses']['responseItems']['response']['reason'].':'.$res['responses']['responseItems']['response']['reasonDesc'].' (id='.$v->getId().')',
                            'grant_uid' =>0,
                            'grant_username' =>'backend',
                        );
                        $this->saveLog($log);
                        $message = array(
                            'message'=>'backend笨鸟预报出错'.' (id='.$v->getId().')',
                            'param'=>$param,
                            'res'=>$res,
                            'order_number'=>$v->getOrderNumber()
                        );
                        tradeLog::error('forecast',$message);
                    }
                }
            }
        }
        return array('status'=>1,'msg'=>'预报出错');
    }

    /**
     *
     * 手动 下发分拣指令
     */
    public function handSortingPackage($expressNumber, $sortFlag = false){
        $express_numbers = explode(',', trim($expressNumber));
        $orderObj = $this->getAllInfoByExpressNumber($express_numbers);
        if(count($orderObj)>1 && !$sortFlag){//被合包
            return array('status'=>1, 'msg'=>'有包裹被合包，只能分拣到公司');
        }
        $order_number = $orderObj[0]['order_number'];
        //收货人详情
        $receiverDetail = $this->getAddress($order_number);
        //获取物流号对应的id
        $orderLogisticsList = TrdOrderLogisticsTable::getInstance()->createQuery('m')->select('*')->whereIn('express_number',$express_numbers)->execute();
        foreach($orderLogisticsList as $kk=>$vv){
            if($vv->getForeignStatus() == 0) return array('status'=>1, 'msg'=>'有包裹还未入库');
        }
        if($sortFlag) {//发到公司
            $receiverDetail['res']['name'] = '徐晨';
            $receiverDetail['res']['mobile'] = '13817694356';
            $receiverDetail['res']['identityNumber'] = '321002198812045533';
            $receiverDetail['res']['country'] = '中国';
            $receiverDetail['res']['province'] = '上海';
            $receiverDetail['res']['city'] ='上海';
            $receiverDetail['res']['district'] = '虹口';
            $receiverDetail['res']['streetAddress'] = '上海市市辖区宝山区宝山工业园区金勺路1438号3号楼2楼';
            $receiverDetail['res']['zipCode'] = '200083';

            $receiverDetail['addr']['name'] = '徐晨';
            $receiverDetail['addr']['tel'] = '13817694356';
            $receiverDetail['addr']['addr'] = '上海上海 上海 上海 宝山区 宝山工业园区金勺路1438号3号楼2楼';
        }
        $expressNumberArr_new = array();
        foreach($orderLogisticsList as $m){
            $logistics_content = json_decode($m->getContent(),1);
            array_push($expressNumberArr_new,$m->getId());
            $logistics_content_str = array_merge($logistics_content,$receiverDetail['addr']);
            $m->setContent(json_encode($logistics_content_str));
            $m->save();
        }

        $logisticsId = 'SH'.$order_number.mt_rand(100000,999999);
        foreach ($express_numbers as $express_v){
            $softObj = new TrdOrderSofting();
            $softObj->setSoftingId($logisticsId);
            $softObj->setOrderNumber($order_number);
            $softObj->setExpressNumber($express_v);
            $softObj->save();
        }

        $param = $this->getCommonParam('LOGISTICS_SORTING_GOODS');
        $param['logisticsEventsRequest']['logisticsEvent']['eventBody']['tradeDetail']['tradeOrders'][0]['tradeOrderId'] = $order_number;
        $param['logisticsEventsRequest']['logisticsEvent']['eventBody']['tradeDetail']['tradeOrders'][0]['logisticsOrder']['logisticsId'] = $logisticsId;
        $param['logisticsEventsRequest']['logisticsEvent']['eventBody']['tradeDetail']['tradeOrders'][0]['logisticsOrder']['logisticsIdIncluded'] = implode(',',$expressNumberArr_new);
        $param['logisticsEventsRequest']['logisticsEvent']['eventBody']['tradeDetail']['tradeOrders'][0]['logisticsOrder']['segmentCode'] = 'PDX';
        $param['logisticsEventsRequest']['logisticsEvent']['eventBody']['tradeDetail']['tradeOrders'][0]['logisticsOrder']['routeId'] = 'USBDX';
        $param['logisticsEventsRequest']['logisticsEvent']['eventBody']['tradeDetail']['tradeOrders'][0]['logisticsOrder']['occurTime'] = date("Y-m-d H:i:s");

        $param['logisticsEventsRequest']['logisticsEvent']['eventBody']['tradeDetail']['tradeOrders'][0]['receiverDetail'] = $receiverDetail['res'];
        //echo json_encode($param);die;
        $flag = true;//默认有收获地址
        if(empty($receiverDetail['res']['name'])){
            $flag = false;
            $explanation = 'backend笨鸟分拣出错';
            $explanation.='（没有收货地址）';
            $log = array(
                'status' =>51,
                'order_number' =>$order_number,
                'explanation' =>$explanation,
                'grant_uid' =>0,
                'grant_username' =>'auto save',
            );
            $this->saveLog($log);
            $message = array(
                'message'=>$explanation,
                'param'=>$param,
                'res'=>array(),
                'order_number'=>$order_number
            );
            tradeLog::error('sorting',$message);
            return array('status'=>1, 'msg'=>'backend笨鸟分拣没有收获地址');
        }
        $result = $this->sendMessage($param,'LOGISTICS_SORTING_GOODS');
        if ($result){
            $res = json_decode($result,true);
            if ($res['responses']['responseItems']['response']['success']){
                $explanation = 'backend笨鸟分拣成功';
                if(!$flag) $explanation.='（没有收货地址）';
                $log = array(
                    'status' =>51,
                    'order_number' =>$order_number,
                    'explanation' =>$explanation,
                    'grant_uid' =>0,
                    'grant_username' =>'auto save',
                );
                $this->saveLog($log);
                $message = array(
                    'message'=>$explanation,
                    'param'=>$param,
                    'res'=>$res,
                    'order_number'=>$order_number
                );
                if(!$flag) {
                    tradeLog::waring('sorting',$message);
                    return array('status'=>1, 'msg'=>$flag);
                } else {
                    tradeLog::info('sorting',$message);
                    return array('status'=>0, 'msg'=>'backend分拣成功');
                }
            }else{
                $explanation = 'backend笨鸟分拣出错';
                if(!$flag) $explanation.='（没有收货地址）';
                $log = array(
                    'status' =>51,
                    'order_number' =>$order_number,
                    'explanation' =>$explanation.':'.$res['responses']['responseItems']['response']['reason'].':'.$res['responses']['responseItems']['response']['reasonDesc'],
                    'grant_uid' =>0,
                    'grant_username' =>'auto save',
                );
                $this->saveLog($log);
                $message = array(
                    'message'=>$explanation,
                    'param'=>$param,
                    'res'=>$res,
                    'order_number'=>$order_number
                );
                tradeLog::error('sorting',$message);
                return array('status'=>1, 'msg'=>$flag);
            }
        }
    }

    /**
     *
     * 手动 模拟 预报包裹
     * @param $data 包裹号
     */
    public function handImitateForecastPackage($data){
        if(!$data) return array('status'=>1,'msg'=>'不合法的参数');
        if(empty($data['logisticsOrder']['trackingNo'])) return array('status'=>1,'msg'=>'不合法的参数');
        $param = $this->getCommonParam('LOGISTICS_TRADE_PAID');
        $logistics = TrdOrderLogisticsTable::getInstance()->findOneByExpressNumber($data['logisticsOrder']['trackingNo']);
        if (!$logistics){
            $logistics = new TrdOrderLogistics();
            $logistics->set('express_number',$data['logisticsOrder']['trackingNo']);
            $logistics->set('type',2);
            $logistics->set('foreign_status',51);
            $logistics->save();
        } else{
            $logistics->set('type',2);
            $logistics->save();
        }
        $param['logisticsEventsRequest']['logisticsEvent']['eventBody']['tradeDetail']['tradeOrders'] = $data;
        //echo(json_encode($param));die;
        if (isset($param['logisticsEventsRequest']['logisticsEvent']['eventBody']) && !empty($param['logisticsEventsRequest']['logisticsEvent']['eventBody'])){
            $result = $this->sendMessage($param,'LOGISTICS_TRADE_PAID');
            if ($result){
                $res = json_decode($result,true);
                if ($res['responses']['responseItems']['response']['success']){//预报成功
                    $message = array(
                        'message'=>'backend模拟笨鸟预报成功',
                        'param'=>$param,
                        'res'=>$res,
                        'order_number'=>''
                    );
                    tradeLog::info('forecast',$message);
                    return array('status'=>0,'msg'=>'预报成功');
                }else{//失败
                    $message = array(
                        'message'=>'backend笨鸟预报出错',
                        'param'=>$param,
                        'res'=>$res,
                        'order_number'=>''
                    );
                    tradeLog::error('forecast',$message);
                }
            }
        }
        return array('status'=>1,'msg'=>'预报出错');
    }

    /**
     *
     * 手动 模拟 下发分拣指令
     */
    public function handImitateSortingPackage($expressNumberArr){
        if(empty($expressNumberArr)) return array('status'=>1,'msg'=>'不合法的参数');
        //获取物流号对应的id
        $orderLogisticsList = TrdOrderLogisticsTable::getInstance()->createQuery('m')->select('*')->whereIn('express_number',$expressNumberArr)->execute();
        foreach($orderLogisticsList as $kk=>$vv){
            if($vv->getForeignStatus() == 0) return array('status'=>1,'msg'=>'不合法的参数');
        }
        $address_content = array(
            'name'=>'徐晨',
            'tel'=>'13817694356',
            'addr'=>'上海上海 上海 上海 宝山区 上海市市辖区宝山区宝山工业园区金勺路1438号3号楼2楼'
        );
        $address['name'] = '徐晨';
        $address['mobile'] = '13817694356';
        $address['identityNumber'] = '321002198812045533';
        $address['country'] = '中国';
        $address['province'] = '上海';
        $address['city'] = '上海';
        $address['district'] = '虹口区';
        $address['streetAddress'] = '上海市市辖区宝山区宝山工业园区金勺路1438号3号楼2楼';
        $address['zipCode'] = '200083';
        $expressNumberArr_new = array();
        foreach($orderLogisticsList as $m){
            $logistics_content = json_decode($m->getContent(),1);
            array_push($expressNumberArr_new,$m->getId());
            $logistics_content_str = array_merge($logistics_content,$address_content);
            $m->setContent(json_encode($logistics_content_str));
            $m->save();
        }
        $time = time().mt_rand(100000,999999);
        $param = $this->getCommonParam('LOGISTICS_SORTING_GOODS');
        $param['logisticsEventsRequest']['logisticsEvent']['eventBody']['tradeDetail']['tradeOrders'][0]['tradeOrderId'] = $time;
        $param['logisticsEventsRequest']['logisticsEvent']['eventBody']['tradeDetail']['tradeOrders'][0]['logisticsOrder']['logisticsId'] = 'SHMN'.$time;
        $param['logisticsEventsRequest']['logisticsEvent']['eventBody']['tradeDetail']['tradeOrders'][0]['logisticsOrder']['logisticsIdIncluded'] = implode(',',$expressNumberArr_new);
        $param['logisticsEventsRequest']['logisticsEvent']['eventBody']['tradeDetail']['tradeOrders'][0]['logisticsOrder']['segmentCode'] = 'PDX';
        $param['logisticsEventsRequest']['logisticsEvent']['eventBody']['tradeDetail']['tradeOrders'][0]['logisticsOrder']['routeId'] = 'USBDX';
        $param['logisticsEventsRequest']['logisticsEvent']['eventBody']['tradeDetail']['tradeOrders'][0]['logisticsOrder']['occurTime'] = date("Y-m-d H:i:s");

        $param['logisticsEventsRequest']['logisticsEvent']['eventBody']['tradeDetail']['tradeOrders'][0]['receiverDetail'] = $address;
        //echo json_encode($param);die;
        $result = $this->sendMessage($param,'LOGISTICS_SORTING_GOODS');

        if ($result){
            $res = json_decode($result,true);
            if ($res['responses']['responseItems']['response']['success']){
                $explanation = 'backend模拟笨鸟分拣成功';
                $message = array(
                    'message'=>$explanation,
                    'param'=>$param,
                    'res'=>$res,
                    'order_number'=>''
                );
                tradeLog::info('sorting',$message);
                return array('status'=>0,'msg'=>'分拣成功');
            }else{
                $explanation = 'backend模拟笨鸟分拣出错';
                $message = array(
                    'message'=>$explanation,
                    'param'=>$param,
                    'res'=>$res,
                    'order_number'=>''
                );
                tradeLog::error('sorting',$message);
                return array('status'=>1,'msg'=>'分拣出错');
            }
        }
    }
}
