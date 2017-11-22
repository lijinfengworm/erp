<?php

class tradeHaitaoForecastTask extends sfBaseTask
{
    protected function configure()
    {
        // // add your own arguments here
        // $this->addArguments(array(
        //   new sfCommandArgument('my_arg', sfCommandArgument::REQUIRED, 'My argument'),
        // ));

        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'trade'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
            new sfCommandOption('type', null, sfCommandOption::PARAMETER_REQUIRED, 'The money name', '1'),
            // add your own options here
        ));

        $this->namespace        = 'trade';
        $this->name             = 'HaitaoForecast';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [trade:HaitaoForecastTask|INFO] task does things.
Call it with:

  [php symfony trade:HaitaoForecast|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array()){
        exit('111');
        sfContext::createInstance($this->configuration);
        // initialize the database connection
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();
        $redis = sfContext::getInstance()->getDatabaseConnection('kaluliRedis');
        $failed = $redis->hGetAll('shihuo.haitao.forecast');
        $order = $this->getForecastInfo($failed);
        if($order){
            $this->log($order->getId());
            $orders = $this->getOrderInfoByExpressNumber($order->getMartExpressNumber());
            $orders_format = $this->formatData($orders->toArray());
            $packageinfo = array();
            foreach ($orders_format['data'] as $k=>$v){
                //获取商品信息
                $goodsInfo = TrdHaitaoGoodsTable::getInstance()->findOneByGoodsId($v['goods_id']);
                if (!$goodsInfo){
                    $log = array(
                        'status' =>51,
                        'order_number' =>$v['order_number'],
                        'explanation' =>'预报时获取不到goods信息：'.$v['goods_id'],
                        'grant_uid' =>0,
                        'grant_username' =>'crontab',
                    );
                    $this->saveLog($log);
                    $redis->hset('shihuo.haitao.forecast',$v['id'],$v['mart_express_number']);
                    $redis->expire('shihuo.haitao.forecast', 3600);
                    exit();
                    break;
                }
                $attr = json_decode($goodsInfo->getAttr(),1);
                $ProductGroup = str_replace('_',' ',$attr['ItemAttributes']['ProductGroup']);
                //记录$ProductGroup
                $redis->hset('shihuo.haitao.forecast.ProductGroup',md5($attr['ItemAttributes']['ProductGroup']),$attr['ItemAttributes']['ProductGroup']);
                $Brand = $attr['ItemAttributes']['Brand'];
                $ProductGroup_zh = tradeCommon::getContents('https://fanyi.youdao.com/openapi.do?keyfrom=chrome&key=1361128838&type=data&doctype=json&version=1.2&q='.$ProductGroup);
                $package = json_decode($ProductGroup_zh,1);
                $package_name = '日常用品';
                if (isset($package['translation'][0]) && !empty($package['translation'][0])){
                    if(preg_match("/^[\x{4e00}-\x{9fa5}]+$/u",$package['translation'][0])){
                        $package_name = $package['translation'][0];
                    }
                }
                $package = array();
                $package['packagename'] = $package_name;
                $package['brand'] = preg_replace('/[^a-zA-Z]/','',substr($Brand,0,20));
                $package['type'] = '';
                $package['num'] = $v['number'];
                $package['unit'] = $attr['Offers']['Offer']['OfferListing']['Price']['Amount']/100;
                $packageinfo['packageinfo'][] = $package;
            }
//            print_r($packageinfo);
            $orders_format['address']['packageinfo'] = json_encode($packageinfo);
            $res = tradeCommon::getContents('http://www.haidaibao.com/Company/CompanyAddInfo?key=A54E775B48187E8E&usercode=JWMST&express='.$order->getMartExpressNumber().'&'.http_build_query($orders_format['address']));
            $result = json_decode($res,1);
//            print_r($result);

            if ($result['state'] == 0){
                foreach($orders as $k=>$v){//记录日志
                    $v->setForecast(1);
                    $v->save();
                    $log = array(
                        'status' =>51,
                        'order_number' =>$v->getOrderNumber(),
                        'explanation' =>'预报成功'.' (id='.$v->getId().')',
                        'grant_uid' =>0,
                        'grant_username' =>'crontab',
                    );
                    $this->saveLog($log);
                }
            } else {//失败
                foreach($orders as $k=>$v){//记录日志
                    $log = array(
                        'status' =>51,
                        'order_number' =>$v->getOrderNumber(),
                        'explanation' =>'预报出错：'.$result['errorMessage'].',包裹号：'.$result['expressInfo'].' (id='.$v->getId().')',
                        'grant_uid' =>0,
                        'grant_username' =>'crontab',
                    );
                    $this->saveLog($log);
                }
            }
        }

    }
    //获取需要预报的数据
    protected function getForecastInfo($data)
    {
        $failed = array();//定义预报失败的包裹
        if ($data){
            foreach ($data as $k=>$v){
                $failed[] = $v;
            }
        }
        $query =  TrdOrderTable::getInstance()->createQuery('m')
            ->select('*')
            ->where('mart_express_number is not null and mart_express_number !=""')
            ->andWhere('status = ?',1)
            ->andWhere('pay_status = ?',1)
            ->andWhere('forecast = ?',0);
        if ($failed) $query->andWhereNotIn('mart_express_number', $failed);
        return $query->fetchOne();
    }
    //根据包裹号获取订单
    protected function getOrderInfoByExpressNumber($express_num)
    {
        $query = TrdOrderTable::getInstance()->createQuery('m')
            ->select('*')
            ->where('mart_express_number = ?',$express_num)
            ->andWhere('status = ?',1)
            ->andWhere('pay_status = ?',1)
            ->andWhere('forecast = ?',0);
        return $query->execute();
    }
    //格式化数据
    private function formatData($return){
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
        return $res;
    }
    //保存日志
    private function saveLog($data){
        $historyObj = new TrdHaitaoOrderHistory();
        if (isset($data['status'])) $historyObj->setType($data['status']);
        if (isset($data['order_number'])) $historyObj->setOrderNumber($data['order_number']);
        if (isset($data['explanation'])) $historyObj->setExplanation($data['explanation']);
        if (isset($data['grant_uid'])) $historyObj->setGrantUid($data['grant_uid']);
        if (isset($data['grant_username'])) $historyObj->setGrantUsername($data['grant_username']);
        $historyObj->save();
    }

}
