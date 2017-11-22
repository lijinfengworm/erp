<?php

/**
 * Created by PhpStorm.
 * User: libin
 * Date: 2017/9/16
 * Time: 上午10:06
 */
class kaluliQmOrderSyncTask extends sfBaseTask
{
    CONST WEB_SITE = '//www.kaluli.com';
    CONST ERROR_NUM = 10;


    protected function configure()
    {
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'trade'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'trade'),
            new sfCommandOption('type', null, sfCommandOption::PARAMETER_REQUIRED, 'The money name', 'PUSH'),
        ));

        $this->namespace = 'kaluli';
        $this->name = 'QmOrderSync';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [kaluli:CreateCiTieForWP|INFO] task does things.
Call it with:

  [php symfony trade:Amqp|INFO]
EOF;
    }

    /**
     * symfony默认执行函数
     * params: arguments, options
     * 把订单提交到快递100
     */
    protected function execute($arguments = array(), $options = array())
    {

        sfContext::createInstance($this->configuration);

        set_time_limit(0);
        ini_set('memory_limit', '128M');
        while (1) {
            //判断内存是否超出
            $this->checkMemory();
            //拉取订单
            $this->syncQmOrder($options);
            sleep(10);
            //物流回写
            $this->syncExpress($options);
            exit();
        }


    }

    public function test($options)
    {

        $qianmiApi = kaluliQianmi::getInstance();
        $redis = sfContext::getInstance()->getDatabaseConnection('kaluliRedis');
        $redis->select(10);
        $accessToken = $redis->get("kaluli.qianmi.accessToken");
        if ($accessToken) {
            $client = $qianmiApi->getClient();
            $req = new LogisticsCompaniesGetRequest;
            $req->setFields("id,code,name,seller_nick,url");
            $res = $client->execute($req, $accessToken);
            $this->log(json_encode($res));

        }
    }

    public function syncQmOrder($options)
    {
        $qianmiApi = kaluliQianmi::getInstance();
        $redis = sfContext::getInstance()->getDatabaseConnection('kaluliRedis');
        $redis->select(10);
        $accessToken = $redis->get("kaluli.qianmi.accessToken");
        if ($accessToken) {
            $this->log("qm sync start");
            $client = $qianmiApi->getClient();
            $req = new D2pTradesSoldGetRequest();
            $req->setFields("tid,orders,pay_time,total_trade_fee,discount_fee,reciver_state,reciver_city,reciver_district,reciver_address,reciver_name,reciver_mobile,created,post_fee");
            $req->setFlowStatus("pending_deliver");
            $startTime = date("Y-m-d H:i:s",strtotime("-1 day"));
            $req->setStartCreated($startTime);
            $res = $client->execute($req, $accessToken);
            $tradeInfo = json_decode(json_encode($res), true);
            $mainOrderList = $tradeInfo['trades']['trade'];
            foreach ($mainOrderList as $mk => $mainOrder) {
                $totalExpressFee = $mainOrder['post_fee'];
                $orderList = $mainOrder['orders']['order'];
                $orderForStoreHouse = array();
                $this->log(json_encode($orderList));
                foreach ($orderList as $k => $order) {
                    $goodsNo = $order['barcode'];
                    //根据goodsNo查找对应sku所在的仓库
                    $skuInfo = KaluliItemSkuTable::getInstance()->findOneByGoodsNo($goodsNo);
                    //获取仓库号
                    if ($skuInfo) {
                        $storeHouseId = $skuInfo->storehouse_id;
                    }
                    $orderInfo['Oid'] = $order['oid'];
                    $orderInfo['ProductId'] = $order['barcode'];
                    $orderInfo['Description'] = $order["title"];
                    $orderInfo['GoodsId'] = 2147483647;
                    $orderInfo['Name'] = $order['title'];
                    $orderInfo['PayTime'] = $mainOrder['pay_time'];
                    $orderInfo['PayStatus'] = 1;
                    $orderInfo['Price'] = $order['price'];
                    $orderInfo['Number'] = $order['num'];
                    $orderInfo['TotalPrice'] = $order['payment'];
                    $orderInfo['UpdateTime'] = $mainOrder['created'];
                    $orderInfo['CreatTime'] = $mainOrder['created'];
                    $orderInfo['ProductCode'] = $order['barcode'];
                    $orderInfo['Source'] = "QMFX";
                    $orderInfo['Receiver'] = $mainOrder['reciver_name'];
                    $orderForStoreHouse[$storeHouseId][] = $orderInfo;
                }

                $authList = FunBase::getPurchaserAuth();
                $count = count($orderForStoreHouse);
                $i = 1;
                $sumExpress = 0;
                foreach ($orderForStoreHouse as $sk => $sv) {
                    // 循环每个仓库的子订单
                    $child = array();
                    $totalPayMent = 0;
                    foreach ($sv as $ssk => $subOrder) {
                        $subOrderInfo = $subOrder;
                        unset($subOrderInfo['Oid']);
                        $subOrderInfo["ChildOrderNumber"] = $mainOrder['tid'] . "_" . $sk . "_" . $subOrder['Oid'];
                        $subOrderInfo['OriginOrderNumber'] = $mainOrder['tid'] . "_" . $sk;
                        $subOrderInfo['OrderNumber'] = $mainOrder['tid'] . "_" . $sk;
                        $subOrderInfo['WareHouse'] = $sk;
                        $child["Order"][] = $subOrderInfo;
                        $totalPayMent += $subOrderInfo['TotalPrice'];
                    }
                    //根据子订单凑成主订单
                    $child['Order']['Cot'] = count($sv);
                    $child['OrderNumber'] = "000000";
                    $child['OriginOrderNumber'] = $mainOrder['tid'] . "_" . $sk;
                    if($totalExpressFee ==0 ) {
                        $child['ExpressFee'] = 0.00;
                    } else {
                        if($i < $count) {
                            $expressFee = number_format(($totalExpressFee/$count),2);
                            $child['ExpressFee'] = $expressFee;
                            $sumExpress += $expressFee;
                            $i++;
                        } else {
                            $child['ExpressFee'] = $totalExpressFee - $sumExpress;
                        }
                    }
                    $totalPayMent +=$child['ExpressFee'];
                    $child['TotalPrice'] = $totalPayMent;
                    $child['RealPrice'] = $totalPayMent;
                    $child['PushPrice'] = $totalPayMent;

                    $child['DutyFee'] = "0.00";
                    $child['CouponFee'] = "0.00";
                    $child['PayStatus'] = 1;
                    $child['PayType'] = 1;
                    $child['PayTime'] = $mainOrder['pay_time'];
                    $child['Count'] = 1;
                    //宁波仓直接审核通过,其他仓待审核
                    if ($sk == 19) {
                        $child['Status'] = 2;
                    } else {
                        $child['Status'] = 1;
                    }
                    $child['Source'] = "QMFX";
                    $child['Uid'] = "000000";
                    $child['Batch'] = "000000";
                    $child['Payer'] = $mainOrder['reciver_name'];
                    $child['Province'] = $mainOrder['reciver_state'];
                    $child['City'] = $mainOrder['reciver_city'];
                    $child['Area'] = $mainOrder['reciver_district'];
                    $child['Address'] = $mainOrder['reciver_address'];
                    $child['Receiver'] = $mainOrder['reciver_name'];
                    $child['Account'] = "000000";
                    $authInfo = $authList[array_rand($authList, 1)];
                    $child['RealName'] = $authInfo['purchaser'];
                    $child['CardCode'] = $authInfo['card_number'];
                    $child['CardType'] = 1;
                    $child['UpdateTime'] = $mainOrder['created'];
                    $child['CreatTime'] = $mainOrder['created'];
                    $child['AuditTime'] = date("Y-m-d H:i:s", time());
                    $child['Mobile'] = $mainOrder['reciver_mobile'];
                    $dataReturn = new DataReturnForQm();
                    $dataXml['PACKAGE'] = $child;
                    $data = $dataReturn->xml_encode($dataXml);
                    $postData = $this->deployString($data);
                    $return = KaluliFun::requestUrl("http://erp.kaluli.com/kaluli_api/getData", "POST", ['data' => $postData]);
                    $xmlObj = new XMLParser();
                    list($flag, $xmldata) = $xmlObj->loadXmlString($return);
                    if ($flag) {
                        if ($xmldata['retcode'] == "00") {
                            $this->log("千米订单:" . $mainOrder['tid'] . "_" . $sk . "处理成功");
                        } else {
                            $this->log(json_encode($xmldata));
                        }
                    }
                }
            }
        }
    }


    public function syncExpress($options)
    {
        $qianmiApi = kaluliQianmi::getInstance();
        $redis = sfContext::getInstance()->getDatabaseConnection('kaluliRedis');
        $redis->select(10);
        $accessToken = $redis->get("kaluli.qianmi.accessToken");
        if ($accessToken) {
            //查询所有千米已有物流单号未同步的主订单
            $mainOrderList = KllBBMainOrderTable::getInstance()->createQuery()->where("source = 'QMFX'")->andWhere("status = 4")->andWhere("syn_api =2")->execute();
            //循环发货
            $this->log($mainOrderList);
            if ($mainOrderList) {
                foreach ($mainOrderList as $k => $v) {
                    //获取子订单信息
                    $packItems = []; //打包数据
                    $orderNumber = $v->order_number;
                    $info = explode("_", $v->origin_order_number);
                    $tid = $info[0];
                    $orderList = KllBBOrderTable::getInstance()->createQuery()->where("order_number = ?", $orderNumber)->fetchArray();
                    if (!empty($orderList)) {
                        //添加包裹数据
                        foreach ($orderList as $ok => $ov) {
                            $explode = explode("_", $ov['child_order_number']);
                            $oid = $explode[2];
                            $num = $ov['number'];
                            $packItems[] = $oid . ":" . $num;
                        }
                        //发货
                        $client = $qianmiApi->getClient();
                        $req = new D2pLogisticsSendRequest();
                        $req->setPackItems(implode(";", $packItems));
                        $expressInfo = $qianmiApi::getQmExpress($v->logistic_type);
                        $req->setCompanyId($expressInfo['id']);
                        $req->setCompanyName($expressInfo['name']);
                        $req->setPostFee($v->express_fee);
                        $req->setShipTypeId("express");
                        $req->setOutSid($v->logistic_number);
                        $req->setTid($tid);
                        $res = $client->execute($req, $accessToken);
                        $response = json_decode(json_encode($res), true);
                        if (isset($response['shipping']) && $response['shipping']['is_success'] == true) {
                            $v->setSynApi(3);
                            $v->save();
                        }
                    }

                }
            }
        }
    }


    private function deployString($xml)
    {
        $default = "000000";
        $encodeXml = base64_encode($xml);
        $count = strlen($encodeXml);
        $num = strlen(strval($count));

        $return = substr($default, 0, 6 - $num) . strval($count) . $encodeXml;
        return $return;

    }

    private function checkMemory()
    {
        $nowmem = (int)(memory_get_usage() / 1024 / 1024);
        if ($nowmem > 60) {  //如果内存超过128M 那么主动退出
            exit(0);
        }
    }


}