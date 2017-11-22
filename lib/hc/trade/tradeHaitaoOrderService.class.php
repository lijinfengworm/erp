<?php

/*
 * 海淘接口服务
 */

class tradeHaitaoOrderService {

    private static $redis = null;//redis对象 
    
    
    /*
     * 进行一些初始化工作
     */

    public function __construct()
    {
        $this->getRedis();
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
     *  更新订单是否被评论
     * @param $order_number 订单号
     * @param $product_id 主商品id
     * @param $hupu_uid 用户id
     * return boolen
     */
    public static function saveOrderCommentStatus($order_number,$product_id,$goods_id,$hupu_uid){
        $orders = TrdOrderTable::getInstance()->createQuery()->where('order_number = ?',$order_number)->andWhere('product_id = ?',$product_id)->andWhere('gid = ?',$goods_id)->andWhere('hupu_uid = ?',$hupu_uid)->andWhere('status = ?',2)->execute();

        if (count($orders)>0){
            foreach ($orders as $order) {
                $order->setIsComment(1);
                $order->save();
            }
            return true;
        }
        return false;
    }


    /**
     *获取每种状态的订单数量
     *  
     */
    public function GetOrderNumByType($hupuUid, $type="all")
    {
        $return = array();
        if($type == "pendpay" || $type == "all")
        {
            $info = TrdMainOrderTable::getInstance()
                ->createQuery()
                ->select('count(1) AS total')
                ->andWhere('hupu_uid = ?',$hupuUid)
                ->andWhere("status = ?",0)
                ->fetchOne()
                ->toArray();
            $return["pendpay"] = $info['total'];
        }

        if($type == "pendsend" || $type == "all" )
        {
            $info = TrdMainOrderTable::getInstance()
                ->createQuery()
                ->select('count(1) AS total')
                ->andWhere('hupu_uid = ?',$hupuUid)
                ->andWhere("status = ?",1)
                ->fetchOne()
                ->toArray();
            $return["pendsend"] = $info['total'];
        }

        if($type == "pendreceipt" || $type == "all")
        {
            $info = TrdMainOrderTable::getInstance()
                ->createQuery()
                ->select('count(1) AS total')
                ->andWhere('hupu_uid = ?',$hupuUid)
                ->andWhere("status = ?",2)
                ->fetchOne()
                ->toArray();
            $return["pendreceipt"] = $info['total'];
        }

        if ($type == 'pendcomment' || $type == 'all') {
            $info = TrdMainOrderTable::getInstance()
                ->createQuery()
                ->select('count(1) AS total')
                ->andWhere('hupu_uid = ?', $hupuUid)
                ->andWhere('status = ?', 6)
                ->fetchOne()
                ->toArray();
            $return['pendcomment'] = $info['total'];
        }

        if($type == "all")
        {
            $info = TrdMainOrderTable::getInstance()
                ->createQuery()
                ->select('count(*) AS total')
                ->andWhere('hupu_uid = ?',$hupuUid)
                ->fetchOne()
                ->toArray();
            $return["all"] = $info['total'];
        }

        return $return;
    }
    
    /**
     * 获取订单
     * @param int $uid 用户id
     * @param int $pagesize 每页显示大小
     * @param int $page 页数
     * @param int $type all 表示全部 pendpay表示待付款 pendsend表示待发货 pendreceipt表示待收货
     * 
     */
    public function getOrderListByType($uid, $pagesize = 10,$page = 1,$type = "all",$countFlag = false){
        if (!$uid) return false;
        $offset = ($page - 1) * $pagesize;
        $mainOrderObj = TrdMainOrderTable::getInstance()->createQuery()->select()
                ->where('hupu_uid = ?',$uid)
                ->offset($offset)
                ->limit($pagesize)
                ->orderBy('order_time desc');
        if($type == "pendpay")
        {
            $mainOrderObj->andWhere("status = ?",0);
        }

        if($type == "pendsend")
        {
            $mainOrderObj->andWhere("status = ?",1);
        }

        if($type == "pendreceipt" )
        {
            $mainOrderObj->andWhere("status = ?",2);
        }
        if ($countFlag){
            return $mainOrderObj->count();
        }
        $mainOrder = $mainOrderObj->execute();
        if (count($mainOrder) == 0){
            return null;
        } 
        $order_number = array();
        $new_main_order = array();
        foreach($mainOrder as $k=>$v){
            array_push($order_number, $v->getOrderNumber());
            $new_main_order[$v->getOrderNumber()] = $v;
        }
        $orderObj = TrdOrderTable::getInstance()->createQuery()->select()
                ->where("order_number in (" . join(",", $order_number) . ") ")
                ->orderBy('order_time desc')
                ->execute();
        $data = $return = array();
        $i= 0;
        foreach ($orderObj as $k=>$v){
          if (!isset($data[$v->getOrderNumber()])) $i=0;
          $data[$v->getOrderNumber()][$i] = $v;
//          $data[$v->getOrderNumber()][$i]['id'] = $v->getId();
//          $data[$v->getOrderNumber()][$i]['product_id'] = $v->getProductId();
//          $data[$v->getOrderNumber()][$i]['gid'] = $v->getGId();
//          $data[$v->getOrderNumber()][$i]['title'] = $v->getTitle();
//          $data[$v->getOrderNumber()][$i]['business'] = $v->getBusiness();
//          $data[$v->getOrderNumber()][$i]['status'] = $v->getStatus();
//          $data[$v->getOrderNumber()][$i]['pay_status'] = $v->getPayStatus();
//          $data[$v->getOrderNumber()][$i]['mart_order_number'] = $v->getMartOrderNumber();
//          $data[$v->getOrderNumber()][$i]['mart_express_number'] = $v->getMartExpressNumber();
//          $data[$v->getOrderNumber()][$i]['total_price'] = $v->getTotalPrice();
//          $data[$v->getOrderNumber()][$i]['attr'] = json_decode($v->getAttr(),1);
          $i++;
        }
        $j=0;
        foreach($data as $k=>$v){
          $return[$j]['main_order'] = $new_main_order[$k];
          $return[$j]['order'] = $v;
          $j++;
        }
        return $return;
    }
    
    /**
     * 根据用户id获取订单
     * @param int $hupuUid 用户id
     * @param int $start_time 开始时间
     * @param int $end_time 结束时间
     * @param int $limit 条数
     * @param int $order 排序
     */
    public function getOrderListByUserId($hupuUid,$start_time,$end_time,$limit=0,$order="timedesc"){
        if (!$hupuUid) return false;
        $query = TrdMainOrderTable::getInstance()
            ->createQuery()
            ->select('*')
            ->andWhere('hupu_uid = ?',$hupuUid)
            ->andWhere("created_at > ?",$start_time)
            ->andWhere("created_at < ?",$end_time);

        if($limit)
        {
            $query->limit($limit);
        }

        if($order=="timedesc")
        {
            $query->orderBy('created_at desc');
        }
        $mainOrder = $query->execute();
        if (!$mainOrder){
            return array(0=>array('order_number'=>null,'order'=>null));
        } 
        $order_number = array();
        $new_main_order = array();
        foreach($mainOrder as $k=>$v){
            array_push($order_number, $v->getOrderNumber());
            $new_main_order[$v->getOrderNumber()] = $v;
        }
        $data = $return = array();
        if ($order_number){
            $orderObj = TrdOrderTable::getInstance()->createQuery()->select()
                ->where("order_number in (" . join(",", $order_number) . ") ")
                ->orderBy('order_time desc')
                ->execute();
            $i= 0;
            foreach ($orderObj as $k=>$v){
            if (!isset($data[$v->getOrderNumber()])) $i=0;
            $data[$v->getOrderNumber()][$i] = $v;
            $i++;
            }
            $j=0;
            foreach($data as $k=>$v){
            $return[$j]['main_order'] = $new_main_order[$k];
            $return[$j]['order'] = $v;
            $j++;
            }
        }
        return $return;
        
    }
    
    /**
     * 
     * 获取物流
     * @param string $express_num 国外快递号
     */
    public function getOrderLogistics($express_num, $order_number = ''){
        if (!$express_num) return false;
        $data = self::$redis->get($order_number.$express_num);
        if ($data){
            return unserialize($data);
        } else {
            $return = array();
            if ($order_number) {
                $orderObj = TrdOrderTable::getInstance()->createQuery()->select('')->where('order_number = ?',$order_number)->andWhere('mart_express_number = ?',$express_num)->fetchOne();
            } else {
                $orderObj = TrdOrderTable::getInstance()->findOneByMartExpressNumber($express_num);
            }
            if (!$orderObj) return false;
            if ($orderObj->getDeliveryType() == 5 || $orderObj->getDeliveryType() == 6){//识货上海仓库发货/ebay海外精选
                if ($orderObj->getDeliveryType() == 5){
                    $return[0]['event'] = '识货已发货 '.TrdOrderTable::$domestic_express_type[$orderObj->getDomesticExpressType()].' '.$orderObj->getDomesticOrderNumber();
                    $return[0]['time'] = date('Y-m-d H:i:s',$orderObj->getDomesticExpressTime());
                } else {
                    $return[0]['event'] = '识货已下单';
                    $return[0]['time'] = date('Y-m-d H:i:s',$orderObj->getMartOrderTime());
                    $return[1]['event'] = 'ebay香港发货 ' . TrdOrderTable::$domestic_express_type[$orderObj->getDomesticExpressType()].' '.$orderObj->getDomesticOrderNumber();
                    $return[1]['time'] = date('Y-m-d H:i:s',$orderObj->getDomesticExpressTime());
                }
                $company = $this->getLogisticsCode(TrdOrderTable::$domestic_express_type[$orderObj->getDomesticExpressType()]);
                //获取国内的物流
                $domesticObj = TrdOrderLogisticsTable::getInstance()->createQuery()->select('')->where('express_number = ?',$orderObj->getDomesticOrderNumber())->andWhere('excompany = ?',$company)->fetchOne();
                if ($domesticObj){//已经存在了
                    $domestic_content = json_decode($domesticObj->getContent(),1);
                    $domestic_kuadi100 = $this->formatDomesticLogistics($domestic_content);
                    if($domestic_kuadi100) $return = array_merge($return,$domestic_kuadi100);
                } else {//继续通知快递100
                    $order_number = $order_number ? $order_number : $orderObj->getOrderNumber();
                    $mainOrder = TrdMainOrderTable::getInstance()->findOneByOrderNumber($order_number);
                    $address = json_decode($mainOrder->getAddressAttr(), true);
                    $city = $address['province'].$address['city'];
                    $this->submitKuaidi100(TrdOrderTable::$domestic_express_type[$orderObj->getDomesticExpressType()], $orderObj->getDomesticOrderNumber(), $city);//提交到快递100
                }
            } else {
                $return[0]['event'] = '识货已下单';
                $return[0]['time'] = date('Y-m-d H:i:s',$orderObj->getFinishOrderTime());
                $return[1]['event'] = '商家已发货';
                $return[1]['time'] = date('Y-m-d H:i:s',$orderObj->getMartExpressTime());
                TrdOrderTable::getInstance()->getConnection()->close();
                $foreign_express_obj = TrdOrderLogisticsTable::getInstance()->findOneByExpressNumber($express_num);
                if (!$foreign_express_obj || ($foreign_express_obj && $foreign_express_obj->getForeignStatus() != 6)){//还在国外
                    if(!$foreign_express_obj || $foreign_express_obj->getType() == 0){//海带宝
                        try {
                            TrdOrderLogisticsTable::getInstance()->getConnection()->close();
                            if ($orderObj->getDeliveryType() == 3) {//海带宝 （日本仓库）
                                $foreignLogistics = tradeCommon::getContents('http://forecast.haidaibao.com/JPGetJSLogistic.aspx?key=A54E775B48187E8E&us_express='.$express_num.'&usercode=JWMST');
                            } else {//海带宝 （美国仓库）
                                $foreignLogistics = tradeCommon::getContents('http://forecast.haidaibao.com/GetJSLogistic.aspx?key=A54E775B48187E8E&us_express='.$express_num.'&usercode=JWMST');
                            }
                            if ($foreignLogistics){
                                $foreignLogistics_array = json_decode($foreignLogistics,1);
                                if ($foreignLogistics_array['state'] == 0 && !empty($foreignLogistics_array['packinfo'])){//正常返回
                                    $foreign_express_obj = TrdOrderLogisticsTable::getInstance()->findOneByExpressNumber($express_num);
                                    //先更新入库
                                    if (!$foreign_express_obj){
                                        $foreign_express_obj = new TrdOrderLogistics();
                                    }
                                    $foreign_express_obj->setExpressNumber($express_num);
                                    $foreign_express_obj->setForeignStatus($foreignLogistics_array['packinfo'][0]['logistics_status']);
                                    $foreign_express_obj->setContent(json_encode($foreignLogistics_array['packinfo'][0]));
                                    $foreign_express_obj->save();
                                    TrdOrderLogisticsTable::getInstance()->getConnection()->close();

                                    //if ($foreignLogistics_array['packinfo']['logistics_status'] == 6){//更新已发往国内}
                                    //拼接物流
                                    $foreign_haidaibao = $this->formatForeignLogistics($foreignLogistics_array['packinfo'][0]);
                                    if($foreign_haidaibao) $return = array_merge($return,$foreign_haidaibao);
                                    if($foreignLogistics_array['packinfo'][0]['logistics_status'] == 6){
                                        $addr = explode(' ',$foreignLogistics_array['packinfo'][0]['addr']);
                                        $this->submitKuaidi100($foreignLogistics_array['packinfo'][0]['cn_expcompany'],$foreignLogistics_array['packinfo'][0]['cn_express'],$addr[0]);//提交到快递100
                                    }

                                    //拼接虎扑自主发货的信息
                                    $domesticOrderNumber = $orderObj->getDomesticOrderNumber();
                                    if ($foreignLogistics_array['packinfo'][0]['logistics_status'] == 0 && !empty($domesticOrderNumber) && $orderObj->getDomesticExpressTime() > 0){
                                        $return[2]['event'] = '识货上海仓库发货 圆通 '.$domesticOrderNumber;
                                        $return[2]['time'] = date('Y-m-d H:i:s',$orderObj->getDomesticExpressTime());
                                    }
                                } elseif ($foreign_express_obj) {
                                    $domesticOrderNumber = $orderObj->getDomesticOrderNumber();
                                    //拼接虎扑自主发货的信息
                                    if (!empty($domesticOrderNumber) && $orderObj->getDomesticExpressTime() > 0){
                                        $return[2]['event'] = '识货上海仓库发货 圆通 '.$domesticOrderNumber;
                                        $return[2]['time'] = date('Y-m-d H:i:s',$orderObj->getDomesticExpressTime());
                                    }
                                }
                            }
                        } catch (Exception $e){
                            $message = array(
                                'message' => $e->getMessage(),
                                'param' => array('express_number' => $express_num),
                                'res' => array(),
                            );
                            tradeLog::error('Logistics', $message);
                        }
                    }elseif($foreign_express_obj->getType() == 2){//笨鸟
                        $content = json_decode($foreign_express_obj->getContent(),1);
                        //拼接物流
                        $foreign_haidaibao = $this->formatForeignLogisticsBirdex($content);
                        if($foreign_haidaibao) $return = array_merge($return,$foreign_haidaibao);
                    }

                } else {//=6直接返回
                    $content = json_decode($foreign_express_obj->getContent(),1);
                    //拼接物流
                    $foreign_haidaibao = $foreign_express_obj->getType() == 0 ? $this->formatForeignLogistics($content) : $this->formatForeignLogisticsBirdex($content);
                    if($foreign_haidaibao) $return = array_merge($return,$foreign_haidaibao);
                    $cn_express = str_replace("\"",'',$content['cn_express']);
                    $cn_express = trim($cn_express);
                    $company = $this->getLogisticsCode($content['cn_expcompany']);
                    //获取国内的物流
                    $domesticObj = TrdOrderLogisticsTable::getInstance()->createQuery()->select('')->where('express_number = ?',$cn_express)->andWhere('excompany = ?',$company)->fetchOne();
                    if ($domesticObj){//已经存在了
                        $domestic_content = json_decode($domesticObj->getContent(),1);
                        $domestic_kuadi100 = $this->formatDomesticLogistics($domestic_content);
                        if($domestic_kuadi100) $return = array_merge($return,$domestic_kuadi100);
                        if ($domesticObj->getDomesticStatus() == 'shutdown' && $orderObj->getDomesticOrderNumber() && $content['cn_express'] != $orderObj->getDomesticOrderNumber() && $orderObj->getDomesticExpressTime() > 0) {
                            $domestic['event'] = '识货上海仓库发货 圆通 '.$orderObj->getDomesticOrderNumber();
                            $domestic['time'] = date('Y-m-d H:i:s',$orderObj->getDomesticExpressTime());
                            array_push($return, $domestic);
                        }
                    } else {//继续通知快递100
                        $addr = explode(' ',$content['addr']);
                        $this->submitKuaidi100($content['cn_expcompany'],$content['cn_express'],$addr[0]);//提交到快递100
                    }

                    //更新订单物流
                    $orders = TrdOrderTable::getInstance()->createQuery()->where('mart_express_number = ?',$express_num)->andWhere('domestic_order_number is null')->execute();
                    if (count($orders)>0){
                        foreach ($orders as $order) {
                            $order->setDomesticExpressType(2);
                            $order->setDomesticOrderNumber($content['cn_express']);
                            $order->setDomesticExpressTime(time());
                            $order->save();
                        }
                    }
                }
            }
           self::$redis->set($order_number.$express_num,  serialize($return),3600);
        }
        return $return;
    }
    
    //拼接海带宝物流返回信息
    private function formatForeignLogistics($data){
        $return  = array();
        if(!$data) return false;
        if ($data['us_in_date']){
            $return[0]['event'] = '转运公司已入库，待装运';
            $return[0]['time'] = date('Y-m-d H:i:s',  strtotime(str_replace('/', '-', $data['us_in_date'])));
        }
        if ($data['us_out_date']){
            $return[1]['event'] = '已装运，发往机场';
            $return[1]['time'] = date('Y-m-d H:i:s',  strtotime(str_replace('/', '-', $data['us_out_date'])));
        }
        if ($data['depart_date']){
            $return[2]['event'] = '已起飞，发往中国';
            $return[2]['time'] = date('Y-m-d H:i:s',  strtotime(str_replace('/', '-', $data['depart_date'])));
        }
        if ($data['land_date']){
            $return[3]['event'] = '已降落，正在清关';
            $return[3]['time'] = date('Y-m-d H:i:s',  strtotime(str_replace('/', '-', $data['land_date'])));
        }
        if ($data['clear_date']){
            $return[4]['event'] = '已清关，待发货';
            $return[4]['time'] = date('Y-m-d H:i:s',  strtotime(str_replace('/', '-', $data['clear_date'])));
        }
        if ($data['cn_out_date']){
            $return[5]['event'] = '已发货 '.$data['cn_expcompany'].' '.$data['cn_express'];
            $return[5]['time'] = date('Y-m-d H:i:s',  strtotime(str_replace('/', '-', $data['cn_out_date'])));
        }
        return $return;
    }
    
    //拼接笨鸟物流返回信息
    private function formatForeignLogisticsBirdex($data){
        $return  = array();
        if(!$data) return false;
        if (isset($data['us_in_date'])){
            $return[0]['event'] = '转运公司已入库，待装运';
            $return[0]['time'] = $data['us_in_date'];
        }
        if (isset($data['us_out_date'])){
            $return[1]['event'] = '已装运，发往机场';
            $return[1]['time'] = $data['us_out_date'];
        }
        if (isset($data['aviation'])){
            foreach($data['aviation'] as $k => $v){
                $info = array(
                    'event' => $v['remark'],
                    'time' => $v['time'],
                );
                array_push($return, $info);
            }
        }elseif(isset($data['depart_date'])){
            $info = array(
                'event' => '已起飞，发往中国',
                'time' => $data['depart_date'],
            );
            array_push($return, $info);
        }
        if (isset($data['land_date'])){
            $info = array(
                'event' => '已降落，正在清关',
                'time' => $data['land_date'],
            );
            array_push($return, $info);
        } else if (isset($data['clear']['prepare_date'])){
            $info = array(
                'event' => '已降落，正在清关',
                'time' => $data['clear']['prepare_date'],
            );
            array_push($return, $info);
        } else if (isset($data['clear']['declaring_date'])){
            $info = array(
                'event' => '已降落，正在清关',
                'time' => $data['clear']['declaring_date'],
            );
            array_push($return, $info);
        }
        if (isset($data['clear']['need_tax_date'])){
            $info = array(
                'event' => '被税，金额：￥'.($data['clear']['logisticsCustomsDutys']/100).'，缴纳完关税方可放行',
                'time' => $data['clear']['need_tax_date'],
            );
            array_push($return, $info);
        }
        if (isset($data['clear_date'])){
            $info = array(
                'event' => '已清关，待发货',
                'time' => $data['clear_date'],
            );
            array_push($return, $info);
        }
        if (isset($data['cn_out_date'])){
            $info = array(
                'event' => '已发货 '.$data['cn_expcompany'].' '.$data['cn_express'],
                'time' => $data['cn_out_date'],
            );
            array_push($return, $info);
        }
        return $return;
    }
    
    //拼接快递100的物流信息
    private function formatDomesticLogistics($data){
        $return  = array();
        if(!$data) return false;
        foreach($data as $k=>$v){
            if($v['time']){
                $return[$k]['event'] = $v['context'];
                $return[$k]['time'] = $v['time'];
            }
        }
        return $return;
    }
    
    //提交给快递100
    public function submitKuaidi100($company,$number,$city){
        $url = 'http://www.kuaidi100.com/poll';
        $number = str_replace("\"",'',$number);
        $param['company'] = $this->getLogisticsCode($company);
        $param['number'] = $number;
        $param['to'] = $city;
        $param['key'] = 'aPScHmND5040';
        $param['parameters']['callbackurl'] = 'http://www.shihuo.cn/kuaidi100/push?number='.$param['number'].'&company='.$param['company'].'&city='.$param['to'];
        $param['parameters']['salt'] = '';
        $param['parameters']['resultv2'] = 0;
        $data['schema'] = 'json';
        $data['param'] = json_encode($param);
        $result = tradeCommon::getContents($url,$data,10,'post');
        $res = json_decode($result,1);
        return true;
    }
    
      
    //获取物流公司对应的编码
    private function getLogisticsCode($name){
        if(!$name) return false;
        if(strpos($name, '中通') !== false){
            return 'zhongtong';
        }elseif(strpos($name, '申通') !== false){
            return 'shentong';
        }elseif(strpos($name, '韵达') !== false){
            return 'yunda';
        }elseif(strpos($name, '天天') !== false){
            return 'tiantian';
        }else if(strpos($name, '顺丰') !== false){
            return 'shunfeng';
        }else if(strpos($name, '圆通') !== false){
            return 'yuantong';
        }else if(strpos($name, '邮政小包') !== false){
            return 'youzhengguonei';
        }else if(strpos($name, '快捷') !== false){
            return 'kuaijiesudi';
        }else if(strpos($name, '速尔') !== false){
            return 'suer';
        }else if(strpos($name, '汇通') !== false){
            return 'huitongkuaidi';
        }else{
            return 'ems';
        }
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
  
  /**
   * 添加购物车
   * @param int $hupuUid
   * @param string $hupu_username
   * @param int $product_id 
   * @param int $goods_id
   */
  public function addCart($hupuUid,$hupu_username,$product_id,$goods_id,$number,$source=0){
      if (!$hupuUid || !$hupu_username || !$product_id || !$goods_id) return false;
      $cartObj = TrdShoppingCartTable::getInstance()->createQuery()->select()->where('hupu_uid = ?',$hupuUid)->andWhere('goods_id = ?',$goods_id)->fetchOne();
      if ($cartObj){
          $product = TrdProductAttrTable::getInstance()->find($product_id);
          if ($product){
              if($cartObj->getNumber()+$number>$product->getLimits()){//超过库存
                  return array('status' => 1,'data'=>'','msg'=>'超过库存');
              }
          }
          $cartObj->set('number',$cartObj->getNumber()+$number);
      } else {
          $cartObj = new TrdShoppingCart();
          $cartObj->set('number',$number);
          $cartObj->set('hupu_uid',$hupuUid);
          $cartObj->set('hupu_username',$hupu_username);
          $cartObj->set('product_id',$product_id);
          $cartObj->set('goods_id',$goods_id);
          $cartObj->set('source',$source);
      }
      $cartObj->save();
      $count = TrdShoppingCartTable::getInstance()->createQuery()->select()->where('hupu_uid = ?',$hupuUid)->count();
      self::$redis->set('trade_shopping_cart_'.$hupuUid,$count,3600*24*180);
      $goodsObj = TrdHaitaoGoodsTable::getInstance()->createQuery('m')
                        ->select()
                        ->where('m.id = ?', $goods_id)
                        ->fetchOne();
      if(!$goodsObj) return array('count'=>$count);
      $goods_attr = json_decode($goodsObj->getAttr(),1);
      $img_path = tradeCommon::getQiNiuProxyPath($goods_attr['LargeImage']['URL']).'?imageView2/1/w/40/h/40';
      return array('status' => 0,'data'=>array('count'=>$count,'img_path'=>$img_path),'msg'=>'');
  }
  
  /**
   * 添加购物车某个商品数量
   * @param int $hupuUid
   * @param int $goods_id
   * @param boolen $flag true 增加一个商品 false 减少一个商品
   */
  public function addCartNumber($hupuUid,$goods_id,$flag = true){
      if (!$hupuUid || !$goods_id) return false;
      $cartObj = TrdShoppingCartTable::getInstance()->createQuery()->select()->where('goods_id = ?',$goods_id)->andWhere('hupu_uid = ?',$hupuUid)->fetchOne();
      if(!$cartObj) return false;
      $goodsObj = TrdHaitaoGoodsTable::getInstance()->createQuery('m')
                        ->select()
                        ->where('m.id = ?', $cartObj->getGoodsId())
                        ->fetchOne();
      if(!$goodsObj) return false;
      if ($goodsObj->getProductId()){
        $productObj = TrdProductAttrTable::getInstance()->createQuery('m')
                        ->select()
                        ->where('m.id = ?', $goodsObj->getProductId())
                        ->fetchOne();
        if ($productObj && $productObj->getLimits() && $flag){
            if ($cartObj->getNumber() >= $productObj->getLimits()) return false;
        }
        }
      if ($flag){//增加
          $cartObj->set('number',$cartObj->getNumber()+1);
      } else {
          if ($cartObj->getNumber() == 1) return false;
          $cartObj->set('number',$cartObj->getNumber()-1);
      }
      $cartObj->save();
      
      $goods_attr = json_decode($goodsObj->getAttr(),1);
      if ($goods_attr['Offers']['Offer']['OfferListing']['Price']['CurrencyCode'] == 'JPY') {
          $rate = TrdHaitaoCurrencyExchangeTable::getRate('jpy');
          $price = ceil($goods_attr['Offers']['Offer']['OfferListing']['Price']['Amount'] * $rate * 100) / 100;
      } elseif($goods_attr['Offers']['Offer']['OfferListing']['Price']['CurrencyCode'] == 'USD') {
          $rate = TrdHaitaoCurrencyExchangeTable::getRate();
          $price = ceil($goods_attr['Offers']['Offer']['OfferListing']['Price']['Amount'] * $rate) / 100;
      } else {
          $price = $goods_attr['Offers']['Offer']['OfferListing']['Price']['Amount'];
      }
      $total_price = $price*$cartObj->getNumber();
      $weight = $productObj->getWeight() ? $productObj->getWeight() : $productObj->getBusinessWeight();
      $total_freight = $this->getAllFreight($weight,$cartObj->getNumber());
      return array('price'=>$total_price,'freight'=>$total_freight,'weight'=>$weight);
  }
  
  /**
   * 删除购物车
   * @param int $hupuUid
   * @param array $goods_id
   */
  public function deleteCart($hupuUid,$goods_id){
      if (!$hupuUid || !$goods_id) return false;
      $cartObj = TrdShoppingCartTable::getInstance()->createQuery('m')
                        ->delete()
                        ->where('m.hupu_uid = ?',$hupuUid)
                        ->whereIn('m.goods_id', $goods_id)
                        ->execute();
      $count = TrdShoppingCartTable::getInstance()->createQuery()->select()->where('hupu_uid = ?',$hupuUid)->count();
      self::$redis->set('trade_shopping_cart_'.$hupuUid,$count,3600*24*180);
      return $count;
  }
  
  /**
   *
   * 获取购物车的列表
   * @param type $hupuUid
   * @return array 
   */
  public function getAllCartData($hupuUid,$data = array()){
      if (!$hupuUid) return false;
      $total_weight = $total_number = $total_product_price = $total_freight = $total_count = 0;
      $cartObj = TrdShoppingCartTable::getInstance()->createQuery('m')
                        ->select()
                        ->where('m.hupu_uid = ?',$hupuUid);
      if($data) $cartObj = $cartObj->whereIn('m.goods_id',$data);
      $cartObj = $cartObj->orderBy('created_at desc')->execute();
      if(count($cartObj) == 0) return false;
      $goods_ids = array();
      foreach($cartObj as $k=>$v){
          array_push($goods_ids,$v->getGoodsId());
      }
      
      $goodsObj = TrdHaitaoGoodsTable::getInstance()->createQuery('m')
                        ->select()
                        ->whereIn('m.id', $goods_ids)
                        ->execute();
      
      $product_ids = $goodsArr = $productArr = array();
      if (count($goodsObj)>0){
        foreach($goodsObj as $k=>$v){
            array_push($product_ids,$v->getProductId());
            $goodsArr[$v->getId()] = $v;
        }
      }
      
      if($product_ids){
        $productObj = TrdProductAttrTable::getInstance()->createQuery('m')
                            ->select()
                            ->whereIn('m.id', $product_ids)
                            ->execute();
      }
      if (count($productObj)>0){
          foreach($productObj as $kk=>$vv){
            $productArr[$vv->getId()] = $vv;
        }
      }
      $result = $dataArray = $updateList =  array();
      //取当前汇率
      $rateInfo = TrdHaitaoCurrencyExchangeTable::getInstance()->createQuery()->select('exchange_rate')->where('currency_from = ?',0)->andWhere('currency_to = ?',1)->limit(1)->fetchOne();
      if ($rateInfo){
          $rate = $rateInfo->getExchangeRate();
      } else {
          $rate = 6.3;
      }
      $cartNumber = count($cartObj);
      foreach ($cartObj as $m=>$n){
          if(!isset($goodsArr[$n->getGoodsId()])) {
              $n->delete();
              continue;
          }
          $goods_id = $goodsArr[$n->getGoodsId()]->getGoodsId();
          if (strpos($goods_id,'amazon') !== false){
               $business = '美国亚马逊';
               $key = 0;
          } elseif(strpos($goods_id,'6pm') !== false){
               $business = '6PM';
               $key = 1;
          } elseif(strpos($goods_id,'gnc') !== false){
              $business = 'GNC';
              $key = 2;
          } elseif(strpos($goods_id,'levis') !== false){
              $business = 'Levis';
              $key = 3;
          } elseif(strpos($goods_id,'nbastore') !== false){
              $business = 'NBAStore';
              $key = 4;
          }
          $productId = $goodsArr[$n->getGoodsId()]->getProductId();
          $result[$key]['data'][$m]['id'] = $n->getId();
          $result[$key]['data'][$m]['number'] = $n->getNumber();
          $result[$key]['data'][$m]['goods_id'] = $n->getGoodsId();
          $result[$key]['business'] = $business;
          
          $goods_attr = json_decode($goodsArr[$n->getGoodsId()]->getAttr(),1);
          $result[$key]['data'][$m]['price'] = ceil($goods_attr['Offers']['Offer']['OfferListing']['Price']['Amount']*$rate)/100;
          $result[$key]['data'][$m]['price'] = $result[$key]['data'][$m]['price']*$result[$key]['data'][$m]['number'];
          $result[$key]['data'][$m]['img_path'] = tradeCommon::getQiNiuProxyPath($goods_attr['LargeImage']['URL']).'?imageView2/1/w/100/h/100';
          $result[$key]['data'][$m]['attr'] = array();
          if (isset($goods_attr['VariationAttributes']['VariationAttribute']) && !empty($goods_attr['VariationAttributes']['VariationAttribute'])){
              $result[$key]['data'][$m]['attr'] = $goods_attr['VariationAttributes']['VariationAttribute'];
          }
          $result[$key]['data'][$m]['invalid'] = false;//没失效
          $result[$key]['data'][$m]['updateFlag'] = false;//不要更新
          
          $result[$key]['data'][$m]['product_id'] = $productId;
          if ($goodsArr[$n->getGoodsId()]->getStatus() == 1 || !isset($productArr[$productId])){
              $result[$key]['data'][$m]['invalid'] = true;//失效
          }
          $result[$key]['data'][$m]['title'] = $result[$key]['data'][$m]['freight'] = '';
          $result[$key]['data'][$m]['limits'] = 1;
          if (isset($productArr[$productId])){
              $result[$key]['data'][$m]['title'] = $productArr[$productId]->getTitle();
              $weight = $productArr[$productId]->getWeight() ? $productArr[$productId]->getWeight() : $productArr[$productId]->getBusinessWeight();
              $result[$key]['data'][$m]['weight'] = $weight;
              
              $result[$key]['data'][$m]['freight'] = $this->getAllFreight($weight,$result[$key]['data'][$m]['number']);
              $result[$key]['data'][$m]['limits'] = $productArr[$productId]->getLimits();
              if($productArr[$productId]->getStatus() || $productArr[$productId]->getShowFlag() == 0){
                  $result[$key]['data'][$m]['invalid'] = true;//失效
              }
              if(!empty($weight)){
                  if(($cartNumber == 1 && $n->getNumber() == 1)){
                      $total_weight +=($weight)*$n->getNumber();
                  } else {
                      $total_weight += $weight<0.5 ? 0.5*$n->getNumber() : ($weight)*$n->getNumber();//总重量
                  }
                    $total_number += $n->getNumber();
                    $total_count++;
                    $total_product_price += $result[$key]['data'][$m]['price'];
                    $total_freight += $result[$key]['data'][$m]['freight'];
              }
              
              //判断活动时间
            $now_time = time();
//            if ($productArr[$productId]->getStartDate() && $productArr[$productId]->getStartDate() > $now_time){
//                $result[$key]['data'][$m]['invalid'] = true;//失效
//            }
//            if ($productArr[$productId]->getEndDate() && $productArr[$productId]->getEndDate() < $now_time){
//                $result[$key]['data'][$m]['invalid'] = true;//失效
//            }
            if ($productArr[$productId]->getLastCrawlDate()+ 3600 < $now_time){
                $result[$key]['data'][$m]['updateFlag'] = true;//更新
            }
          }
          if ($data && ($result[$key]['data'][$m]['invalid'] || empty($weight))){//购物车确认页面 失效
              unset($result[$key]['data'][$m]);
              if (count($result[$key]['data']) < 1){
                  unset($result[$key]);
              }
              continue;
          }
          if ($result[$key]['data'][$m]['updateFlag']){
              $dataArray[$n->getGoodsId()]['productId'] = $productId;
              $dataArray[$n->getGoodsId()]['data']['goods_id'] = $n->getGoodsId();
              $dataArray[$n->getGoodsId()]['data']['price'] = $result[$key]['data'][$m]['price'];
              $dataArray[$n->getGoodsId()]['data']['number'] = $result[$key]['data'][$m]['number'];
          }
      }
      if ($dataArray){
          foreach($dataArray as $k=>$v){
              $updateList[] = $v;
          }
      }
      
      //计算运费
      $total_product_freight = $total_weight ? $this->getAllFreight($total_weight, $total_number, false) : 0;
      $total_price = $total_product_price+$total_product_freight;
      $save_freight = $total_freight - $total_product_freight;
      $total_data = array(
          'total_price'=>$total_price,
          'total_product_freight'=>$total_product_freight,
          'total_product_price'=>$total_product_price,
          'save_freight'=>$save_freight,
          );
      return array('result'=>$result,'updateList'=>$updateList,'total_data'=>$total_data);
  }
  
  /**
   *
   * 获取购物车的价格
   * @param type $hupuUid
   * @return array 
   */
  public function getCartAllPrice($hupuUid,$ids){
      if (!$hupuUid || empty($ids)) return false;
      $total_count = 0;
      $total_product_price = 0;
      $total_product_freight = 0;
      $total_price = 0;
      $total_number = 0;
      $total_weight = 0;
      $total_freight = 0;
      $cartObj = TrdShoppingCartTable::getInstance()->createQuery('m')
                        ->select()
                        ->where('m.hupu_uid = ?',$hupuUid)
                        ->whereIn('m.goods_id', $ids)
                        ->orderBy('created_at desc')
                        ->execute();
      if(count($cartObj) < 1) return false;
      $goods_ids = array();
      foreach($cartObj as $k=>$v){
          array_push($goods_ids,$v->getGoodsId());
      }
      
      $goodsObj = TrdHaitaoGoodsTable::getInstance()->createQuery('m')
                        ->select()
                        ->whereIn('m.id', $goods_ids)
                        ->execute();
      
      $product_ids = $goodsArr = $productArr = array();
      if (count($goodsObj)>0){
        foreach($goodsObj as $k=>$v){
            array_push($product_ids,$v->getProductId());
            $goodsArr[$v->getId()] = $v;
        }
      }
      if($product_ids){
        $productObj = TrdProductAttrTable::getInstance()->createQuery('m')
                            ->select()
                            ->whereIn('m.id', $product_ids)
                            ->execute();
      }
      if ($productObj){
          foreach($productObj as $kk=>$vv){
            $productArr[$vv->getId()] = $vv;
        }
      }
      $result = $dataArray = $updateList =  array();
      //取当前汇率
      $rateInfo = TrdHaitaoCurrencyExchangeTable::getInstance()->createQuery()->select('exchange_rate')->where('currency_from = ?',0)->andWhere('currency_to = ?',1)->limit(1)->fetchOne();
      if ($rateInfo){
          $rate = $rateInfo->getExchangeRate();
      } else {
          $rate = 6.3;
      }
      $cartNumber = count($cartObj);
      foreach ($cartObj as $m=>$n){
          $goods_attr = json_decode($goodsArr[$n->getGoodsId()]->getAttr(),1);
          $price = ceil($goods_attr['Offers']['Offer']['OfferListing']['Price']['Amount']*$rate)/100;
          $price = $price*$n->getNumber();
          $productId = $goodsArr[$n->getGoodsId()]->getProductId();
          
          $flag = false;//没失效
          if ($goodsArr[$n->getGoodsId()]->getStatus() == 1 || !isset($productArr[$productId])){
              $flag = true;//失效
          }
 
          if (isset($productArr[$productId])){
              
              if($productArr[$productId]->getStatus() || $productArr[$productId]->getShowFlag() == 0){
                  $flag = true;//失效
              }
              //判断活动时间
//            $now_time = time();
//            if ($productArr[$productId]->getStartDate() && $productArr[$productId]->getStartDate() > $now_time){
//                $flag  = true;//失效
//            }
//            if ($productArr[$productId]->getEndDate() && $productArr[$productId]->getEndDate() < $now_time){
//                $flag  = true;//失效
//            }
          $weight = $productArr[$productId]->getWeight() ? $productArr[$productId]->getWeight() : $productArr[$productId]->getBusinessWeight();
          if(empty($weight)) $flag  = true;//失效
          if(!$flag){
              $price = ceil($goods_attr['Offers']['Offer']['OfferListing']['Price']['Amount']*$rate)/100;
              $price = $price*$n->getNumber();
              
              $freight = $this->getAllFreight($weight,$n->getNumber());
              if(($cartNumber == 1 && $n->getNumber() == 1)){
                  $total_weight +=($weight)*$n->getNumber();
              } else {
                  $total_weight += $weight<0.5 ? 0.5*$n->getNumber() : ($weight)*$n->getNumber();//总重量
              }
              $total_number += $n->getNumber();
              $total_count++;
              $total_product_price += $price;
              $total_freight += $freight;
          }
          
        }
      }
      
      //计算运费
      if($total_weight > 0) $total_product_freight = $this->getAllFreight($total_weight, $total_number, false);
      $total_price = $total_product_price+$total_product_freight;
      $save_freight = $total_freight - $total_product_freight;
      return array('total_count'=>$total_count,'total_product_price'=>$total_product_price,'total_product_freight'=>$total_product_freight,'total_price'=>$total_price,'save_freight'=>$save_freight);
  }
  
  /**
   *
   * 获取邮费
   * @param type $hupuUid
   * @return array 
   */
  private function getAllFreight($weight,$number = 1,$flag = true){
      $res = 0;
      $freight = 0;
      if ($flag || $number == 1){//单间计算价格
          $freight = $weight*40;
          if($freight < 46) $freight = 46;
          $res = $freight*$number;
      } else {
          $res = $weight*32 + 16;
          $res = ceil($res*100)/100;
      }
      if ($weight > 1 && $weight <= 2) {
          $res += 2;
      } elseif ($weight > 2 && $weight <= 3) {
          $res += 3;
      } elseif ($weight > 3 && $weight <= 4) {
          $res += 4;
      } elseif ($weight > 4 && $weight <= 5) {
          $res += 5;
      } elseif ($weight > 5) {
          $res += 6;
      }
      if($res<46) $res =46;
      return $res;
  }
  //**********************************************************************************************************************
  /**
     *
     * 提交订单
     * @param array $param 参数 例如：
     ***************************
     * array(
     *    'product_id'=>1,//主商品id
     *    'goods_id'=>1,//子商品id
     *    'region_id'=>1,//地址id
     *    'number'=>1,//数量
     *    'remark'=>'识货君，麻烦送个老婆，谢谢',//用户备注
     *    'uid'=>'********',//用户uid
     *    'uname'=>'ddtey',//用户名
     *    'source'=>0, //0pc 1m站 2一键购 3app
     *    'platform' => 'ios' //ios 或者 andriod
     *    'channel' => 'xiaomi' // android渠道
     * )
     * **************************
     * return array 
     * *************************
     * array(
     *  'order_number'=>'2015030512121212121',//订单号
     *  'total_price'=>'234.12',//总价格
     * )
     * **************************
     */
    public function submitOrder($param){
        if(empty($param)) return array('errCode'=>1,'msg'=>'参数有误');
        if(!isset($param['product_id']) || !isset($param['goods_id']) || !isset($param['region_id']) || !isset($param['number']) || !isset($param['uid']) || !isset($param['uname']) || !isset($param['source'])) return array('errCode'=>1,'msg'=>'参数有误');
        extract($param);//生成以key为变量名、value为对应值的多组新变量。 
        $product_info = TrdProductAttrTable::getInstance()->find($product_id); 
        if (!$product_info || !$product_info->getShowFlag()) {
            return array('errCode'=>2,'msg'=>'该商品已被删除或者不支持代购识货君');
        }
        $goods_info = TrdHaitaoGoodsTable::getInstance()->createQuery('m')->select('*')->where('m.id = ?',$goods_id)->andWhere('m.product_id = ?',$product_id)->andWhere('m.status = 0')->limit(1)->fetchOne();   
        if (!$goods_info || !$goods_info->getAttr()) {
            return array('errCode'=>3,'msg'=>'该商品已被删除或者不支持代购识货君');
        }
        if ($number > $product_info->getLimits()){
            return array('errCode'=>4,'msg'=>'超过限购数了');
        }
        
        //判断活动时间
        /*$now_time = time();
        if ($product_info->getStartDate() && $product_info->getStartDate() > $now_time){
            return array('errCode'=>5,'msg'=>'代购时间未到');
        }
        if ($product_info->getEndDate() && $product_info->getEndDate() < $now_time){
            return array('errCode'=>6,'msg'=>'该代购已过期了');
        }*/
        if ($number > $product_info->getLimits()){
            return array('errCode'=>7,'msg'=>'超过限购数了');
        }
        $address = TrdUserDeliveryAddressTable::getInstance()->find($region_id);
        if (!$address){
            return array('errCode'=>8,'msg'=>'没有收货地址');
        }

        $freight = $product_info->getFreight();
        $goods_attr = json_decode($goods_info->getAttr(),true);
        //获取价格
        $exchange = $goods_attr['Offers']['Offer']['OfferListing']['Price']['FormattedPrice'];//外币假
        if ($goods_attr['Offers']['Offer']['OfferListing']['Price']['CurrencyCode'] == 'JPY') {
            $rate = TrdHaitaoCurrencyExchangeTable::getRate('jpy');
            $price = ceil($goods_attr['Offers']['Offer']['OfferListing']['Price']['Amount'] * $rate * 100) / 100;
        } elseif($goods_attr['Offers']['Offer']['OfferListing']['Price']['CurrencyCode'] == 'USD') {
            $rate = TrdHaitaoCurrencyExchangeTable::getRate();
            $price = ceil($goods_attr['Offers']['Offer']['OfferListing']['Price']['Amount'] * $rate) / 100;
        } else {
            $price = $goods_attr['Offers']['Offer']['OfferListing']['Price']['Amount'];
        }
       
        $total = $freight*$number + $price*$number;
        
        $name = $goods_attr['ASIN'];
        $new_attr = array();
        if (isset($goods_attr['VariationAttributes']['VariationAttribute']) && !empty($goods_attr['VariationAttributes']['VariationAttribute'])){
            foreach($goods_attr['VariationAttributes']['VariationAttribute'] as $k=>$v){
                $new_attr[$v['Name']] = $v['Value'];
            }
        }
        $new_attr['price'] = $goods_attr['Offers']['Offer']['OfferListing']['Price']['Amount'] ? $goods_attr['Offers']['Offer']['OfferListing']['Price']['Amount'] : '';
        if ($name){ $new_attr['name'] = $name;}
        if (preg_match('/images-amazon.com/',$goods_attr['LargeImage']['URL'])){
            $new_attr['img'] = 'http://shihuoproxy.hupucdn.com/' . $this->url_base64_encode($goods_attr['LargeImage']['URL'].'_SS500_.jpg');
        } else {
            $new_attr['img'] = 'http://shihuoproxy.hupucdn.com/' . $this->url_base64_encode($goods_attr['LargeImage']['URL']);
        }
        
        $order_sn = date('ymd').substr(time(),-5).substr(microtime(),2,5);
        $orderHisObj = TrdHaitaoOrderTable::getInstance()->createQuery()->select('id')->where('order_number = ?',$order_sn)->execute();
        if ($orderHisObj){
            $order_sn = date('ymd').substr(time(),-5).substr(microtime(),2,5);
        }
        
        $region = TrdUserDeliveryAddressTable::getInstance()->find($region_id);
        $address = $region->getName().' '.trim($region->getRegion()) . ' ' . trim($region->getStreet()).'（邮编：'.$region->getPostcode().'）'.' ';
        if ($region->getMobile()){
            $address .= '手机：'.$region->getMobile();
        } else {
            $address .= '电话：'.$region->getPhonesection().'-'.$region->getPhonecode();
            if ($region->getPhoneext()) $address .= '-'.$region->getPhoneext();
        }
        $street = explode(' ',trim($region->getRegion()));
        $address_arr = array(
            'name'=>$region->getName(),
            'postcode'=>$region->getPostcode(),
            'province'=>$street[0],
            'city'=>$street[1],
            'area'=>isset($street[2]) ? $street[2] : '',
            'mobile'=>$region->getMobile(),
            'region'=>$region->getRegion(),
            'street'=>$region->getStreet(),
            'identity_number'=>$region->getIdentityNumber()
        );

        $time = date('Y-m-d H:i:s');
        //保存订单
        //插入主表 trd_main_order
        $mainOrderObj = new TrdMainOrder();
        $mainOrderObj->setOrderNumber($order_sn);
        $mainOrderObj->setHupuUid($uid);
        $mainOrderObj->setHupuUsername($uname);
        $mainOrderObj->setAddress($address);
        $mainOrderObj->setAddressAttr(json_encode($address_arr));
        $mainOrderObj->setExpressFee($freight*$number);
        $mainOrderObj->setTotalPrice($total);
        $mainOrderObj->setNumber($number);
        $mainOrderObj->setRemark($remark);
        $mainOrderObj->setOrderTime($time);
        if ($param['sourceChannel']) $mainOrderObj->setSource($param['sourceChannel']);////下单来源
        $mainOrderObj->save();
        
        //保存子订单
        $i=0;
        for($i=0;$i<$number;$i++){
            $orderObj = new TrdOrder();
            $orderObj->setOrderNumber($order_sn);
            $orderObj->setTitle($product_info->getTitle());
            $orderObj->setProductId($product_id);
            $orderObj->setBusiness($product_info->getBusiness());
            $orderObj->setHupuUid($uid);
            $orderObj->setHupuUsername($uname);
            //商品id
            if ($product_info->getBusiness() == '6pm') {
                $goodsId = 'usa.6pm.' . $new_attr['name'];
            } else if ($product_info->getBusiness() == 'gnc') {
                $goodsId = 'usa.gnc.' . $new_attr['name'];
            } else if ($product_info->getBusiness() == 'levis') {
                $goodsId = 'usa.levis.' . $new_attr['name'];
            } else if ($product_info->getBusiness() == 'nbastore') {
                $goodsId = 'usa.nbastore.' . $new_attr['name'];
            } else if ($product_info->getBusiness() == '日本亚马逊') {
                $goodsId = 'jp.amazon.' . $new_attr['name'];
            } else if ($product_info->getBusiness() == TrdProductAttrTable::$zhifa_business) {
                $goodsId = 'cn.hk.' . $new_attr['name'];
            } else {
                $goodsId = 'usa.amazon.' . $new_attr['name'];
            }
            $orderObj->setGoodsId($goodsId);
            $orderObj->setGid($goods_id);
            $orderObj->setAttr(json_encode($new_attr));
            $orderObj->setPrice($price);
            $orderObj->setExpressFee($freight);
            $orderObj->setTotalPrice($price+$freight);
            $orderObj->setOrderTime($time);
            $orderObj->setSource($param['source']);//下单来源
            if (in_array($param['source'], array(3, 4))) {
                isset($param['channel']) && $orderObj->setChannel($param['channel']);
            }
            $orderObj->save();
        }
        
        
        //下单日志
        $history = new TrdHaitaoOrderHistory();
        $history->setOrderNumber($order_sn);
        $history->setHupuUid($uid);
        $history->setHupuUsername($uname);
        $history->setType(50);
        $history->setExplanation($product_info->getTitle());
        $history->save();
        return array('errCode'=>0,'msg'=>'','data'=>array('order_number'=>$order_sn,'total_price'=>$total));
    }
    
    /**
     *
     * 获取订单详情
     * @param int $order_number 订单号
     * @param int $uid 虎扑用户名
     * @return array
     */
    public function getOrderDetail($order_number,$uid){
        if(empty($order_number) || empty($uid)) return array('errCode'=>1,'msg'=>'参数有误');
        $orderObj = TrdOrderTable::getInstance()->createQuery()->select('*')->where('order_number = ?',$order_number)->andWhere('hupu_uid = ?',$uid)->execute();
        $mainOrderObj = TrdMainOrderTable::getInstance()->createQuery()->select('*')->where('order_number = ?',$order_number)->andWhere('hupu_uid = ?',$uid)->limit(1)->fetchOne();
        if(!$mainOrderObj) return array('errCode'=>2,'msg'=>'登录信息与订单号不匹配');
        if(count($orderObj)<1) return array('errCode'=>2,'msg'=>'登录信息与订单号不匹配');
        $num = $orderObj->count();
        $mainOrderArr = $orderArr = array();
        $mainOrderArr['order_number'] = $mainOrderObj->getOrderNumber();
        $mainOrderArr['order_time'] = $mainOrderObj->getOrderTime();
        $mainOrderArr['number'] = $mainOrderObj->getNumber();
        $mainOrderArr['total_price'] = $mainOrderObj->getTotalPrice();
        $mainOrderArr['address'] = $mainOrderObj->getAddress();
        $mainOrderArr['status'] = $mainOrderObj->getStatus();
        $mainOrderArr['tax_status'] = $mainOrderObj->getTaxStatus();
        $mainOrderArr['tax'] = (float) $mainOrderObj->getTax();
        $mainOrderArr['express_fee'] = $mainOrderObj->getExpressFee();
        $mainOrderArr['coupon_fee'] = $mainOrderObj->getCouponFee();
        $mainOrderArr['marketing_fee'] = $mainOrderObj->getMarketingFee();
        if ($mainOrderObj->getStatus() == 1 && ((strtotime($mainOrderObj->getPayTime()) + 600) <= time())) {
            $mainOrderArr['time_cancel_flag'] = false;
        } else {
            $mainOrderArr['time_cancel_flag'] = true;
        }
        $product_ids = array();
        foreach($orderObj as $k=>$v){
            $product_ids[] = $v->getProductId();
            $orderArr[$k]['id'] = $v->getId();
            $orderArr[$k]['status'] = $v->getStatus();
            $orderArr[$k]['pay_status'] = $v->getPayStatus();
            $orderArr[$k]['status_info'] = $v->getOrderStatusInfo();
            $orderArr[$k]['pay_time'] = $v->getPayTime();
            $orderArr[$k]['title'] = $v->getTitle();
            $orderArr[$k]['product_id'] = $v->getProductId();
            $orderArr[$k]['goods_id'] = $v->getGid();
            $orderArr[$k]['price'] = $v->getPrice();
            $orderArr[$k]['total_price'] = $v->getTotalPrice();
            $orderArr[$k]['express_fee'] = $v->getExpressFee();
            $orderArr[$k]['mart_express_number'] = $v->getMartExpressNumber();
            $orderArr[$k]['is_comment'] = $v->getIsComment() ? true : false;
            $attr = json_decode($v->getAttr(),1);
            $orderArr[$k]['exchange'] = $attr['price']/100;
            $orderArr[$k]['name'] = $attr['name'];
            $orderArr[$k]['img'] = tradeCommon::getQiNiuProxyPath($attr['img']);
            unset($attr['price']);
            unset($attr['name']);
            unset($attr['img']);
            $orderArr[$k]['attr'] = $attr;
        }

        $orders = TrdOrderTable::getInstance()->createQuery('a')
            ->select("count(goods_id) as goods_num, a.product_id")
            ->where('order_number = ?', $order_number)
            ->andWhere('hupu_uid = ?', $uid)
            ->groupBy('goods_id')
            ->fetchArray();

        $productObj = TrdProductAttrTable::getInstance()->createQuery('m')
            ->select("id, weight, business_weight,business")
            ->whereIn('m.id', $product_ids)
            ->fetchArray();
        $total_freight = 0;
        foreach ($productObj as $key => $product) {
            $weight = $product['weight'] ? $product['weight'] : $product['business_weight'];
            foreach ($orders as $k => $order) {
                if ($order['product_id'] == $product['id']) {
                    if ($product['business'] == TrdProductAttrTable::$zhifa_shihuo_business) {
                        $freight = 0;
                    } else {
                        $freight = $this->getAllFreight($weight, $order['goods_num']);
                    }
                    $total_freight += $freight;
                }
            }
        }

        if ((int) $total_freight == (int) $mainOrderObj['express_fee']) {
            $save_freight = 0;
        } else {
            $save_freight = $total_freight - $mainOrderObj['express_fee'];
        }
        $mainOrderArr['save_freight'] = $save_freight;

        return array('errCode'=>0,'msg'=>'','data'=>array('mainOrderArr'=>$mainOrderArr,'orderArr'=>$orderArr));
    }
    
    /**
     *
     * 获取订单购买链接
     * @param int $order_number 订单号
     * @param int $uid 虎扑用户名
     * @param int $type 0pc 1m站 2app
     * @return array
     */
    public function getOrderBuyLink($order_number,$uid,$type){
        if(!$order_number) return array('errCode'=>1,'msg'=>'参数有误');
        $mainOrderObj = TrdMainOrderTable::getInstance()->createQuery()->where('order_number = ?',$order_number)->andWhere('hupu_uid = ?',$uid)->andWhere('status = ?',0)->limit(1)->fetchOne();
        $orderObj = TrdOrderTable::getInstance()->createQuery()->where('order_number = ?',$order_number)->andWhere('hupu_uid = ?',$uid)->andWhere('status = ?',0)->limit(1)->fetchOne();
        if(!$mainOrderObj || !$orderObj) return array('errCode'=>2,'msg'=>'订单号非法');
        //付款
        $param = array(
            'title' => str_replace('*','',$orderObj->getTitle()),
            'userId' => $mainOrderObj->getHupuUid(),
            'source' => 1,
            'amount' => $mainOrderObj->getTotalPrice(),
            'channelId' => 'alipay_app',
            'callBackUrl' => 'http://m.shihuo.cn/daigou/orderPayResult',
            'notifyUrl' => 'http://www.shihuo.cn/haitao/orderCallback/'.$order_number,
            'orderPrefix' => 'SH'
        );
        $pay_api = new tradePayApi();
        $header = array();
        /*$env = sfConfig::get('sf_environment');
        if ('dev' == $env) {
            $header = array(
                'appId: shihuo_test1',
                'jsondoc: true'
            );
        }*/
        $json  = $pay_api->post('/pay-api/order/createRechargeTradeOrder', $param, 'POST', $header);
        if ($json){
            return array('errCode'=>0,'msg'=>'','data'=>array('url'=>$json->url));
        } else {
            return array('errCode'=>3,'msg'=>'跳转失败，请稍后再试');
        }
    }

    /**
     *用户自主取消订单
     * @param sfWebRequest $request
     * @return array
     */
    public function executeCancelOrderResult($order_number, $uid) {
        if(!$order_number || !$uid) return array('errCode' => 1,'msg' => '参数有误');
        $mainOrderObj = TrdMainOrderTable::getInstance()->createQuery()->where('order_number = ?',$order_number)->limit(1)->fetchOne();
        if (!$mainOrderObj){
            return array('errCode'=>2,'msg'=>'订单号非法');
        }
        if($mainOrderObj->getStatus() == 0 || ($mainOrderObj->getStatus() == 1 && strtotime($mainOrderObj->getPayTime())+600>time())){
            $exp = '未付款用户自主取消主订单';
            if ($mainOrderObj->getStatus() == 1) $exp = '已付款10分钟内用户自主取消主订单';
            $mainOrderObj->setStatus(4);//取消
            $mainOrderObj->save();
            //付款成功日志
            $history = new TrdHaitaoOrderHistory();
            $history->setOrderNumber($mainOrderObj->getOrderNumber());
            $history->setHupuUid($mainOrderObj->getHupuUid());
            $history->setHupuUsername($mainOrderObj->getHupuUsername());
            $history->setType(56);
            $history->setExplanation($exp);
            $history->save();
            //循环保存子订单
            $orderObj = TrdOrderTable::getInstance()->createQuery()->where('order_number = ?',$order_number)->execute();
            foreach($orderObj as $k=>$v){
                $v->setStatus(6);//用户取消
//                if($v->getPayStatus() == 1){// 已付款
//                    $v->setPayStatus(2);//待退款
//                    $v->setRefund($v->getTotalPrice());
//                    $v->setRefundExpressFee($v->getExpressFee());
//                    $v->setRefundPrice($v->getPrice());
//                    $v->setRefundRemark('已付款10分钟内用户自主取消订单');
//                }
                if (substr($v->getGoodsId(), 0, 2) == 'cn'){
                    $serviceRequest = new tradeServiceClient();
                    $serviceRequest->setMethod('daigouproduct.skuStock');
                    $serviceRequest->setVersion('1.0');
                    $serviceRequest->setApiParam('id', $v->getGid());
                    $serviceRequest->setApiParam('num', 1);
                    $serviceRequest->setApiParam('type', 4);//取消订单
                    $serviceRequest->execute();
                }
                $v->save();
            }
            $couponObj = TrdOrderActivityDetailTable::getInstance()->createQuery()->select()->where('order_number=?',$mainOrderObj->getOrderNumber())->andWhere('type = ?',0)->fetchOne();
            if($couponObj){
                $couponObj->set('refund_type',1);
                $couponObj->save();
                //礼品卡返回
                $serviceRequest = new tradeServiceClient();
                $serviceRequest->setMethod('lipinka.rollback');
                $serviceRequest->setVersion('1.0');
                $serviceRequest->setApiParam('user_id', $mainOrderObj->getHupuUid());
                $attr = json_decode($couponObj->getAttr(),true);
                $serviceRequest->setApiParam('card', $attr['code']);
                $response = $serviceRequest->execute();
            }
            return array('errCode'=>0,'msg'=>'取消成功');
        } else {
            return array('errCode'=>3,'msg'=>'此状态下没有权限取消订单~');
        }
    }

    /**
     *
     * 用户确认收货
     * 'id'=>子订单ID
     * 'order_number'=>订单号
     * 'uid'=>用户ID
     * 'type'=>0, //0pc 1m站 2app
     */
    public function executeConfirmReceiveGoods($id, $order_number, $uid, $type) {
        if (!$uid || !$order_number || !$id){
            return array('errCode' => 1,'msg' => '参数有误');
        }
        $mainObj = TrdMainOrderTable::getInstance()->createQuery()->select()->where('hupu_uid = ?', $uid)->andWhere('order_number = ?', $order_number)->limit(1)->fetchOne();
        if($mainObj && $mainObj->getTaxStatus() == 1){//需要支付关税
            return array('errCode' => 2,'msg' => '请先去电脑版个人中心支付关税！');
        }
        $orderObj = TrdOrderTable::getInstance()->createQuery()->select()->where('id = ?',$id)->andWhere('hupu_uid = ?',$uid)->andWhere('order_number = ?',$order_number)->andWhere('status = ?',1)->andWhere('pay_status = ?',1)->limit(1)->fetchOne();
        if ($orderObj) {
            $sourceType = 'pc';
            switch ($type) {
                case 0:
                    $sourceType = 'pc';
                    break;
                case 1:
                    $sourceType = 'm站';
                    break;
                case 2:
                    $sourceType = 'app';
                    break;
            }
            $explanation = $sourceType . '确认收货 (商品id='.$id.')';
            $orderObj->setStatus(2);
            $orderObj->save();
            //确认收货日志
            $history = new TrdHaitaoOrderHistory();
            $history->setOrderNumber($orderObj->getOrderNumber());
            $history->setHupuUid($orderObj->getHupuUid());
            $history->setHupuUsername($orderObj->getHupuUsername());
            $history->setType(52);
            $history->setExplanation($explanation);
            $history->save();

            //判断是否还要更新主订单
            $orderObject = TrdOrderTable::getInstance()->createQuery()->select()->where('hupu_uid = ?',$uid)->andWhere('order_number = ?',$order_number)->andWhere('status in (0,1,3,4)')->limit(1)->fetchOne();
            if(!$orderObject){
                //$mainObj = TrdMainOrderTable::getInstance()->createQuery()->select()->where('hupu_uid = ?',$uid)->andWhere('order_number = ?',$order_number)->limit(1)->fetchOne();
                $mainObj->setStatus(6);//待评价
                $mainObj->save();
            }
            return array('errCode' => 0,'msg'=>'确认成功');
        } else{
            return array('errCode' => 2,'msg' => '操作异常~');
        }
    }

    private function url_base64_encode($bin) {
        $base64 = base64_encode($bin);
        $base64 = str_replace('+', '-', $base64);
        $base64 = str_replace('/', '_', $base64);
        $base64 = str_replace('=', '', $base64);
        return $base64;
    }
}

