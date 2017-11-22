<?php

/**
 * Class orderTradeService
 * version: 1.0
 */
class orderTradeService extends tradeService
{
    private $USA_FIXED_FREIGHT = 4.95; //美国本土固定运费

    /**
     * 我的订单数量
     * @param string type 订单类型（all：全部  pendpay：待付款  pendsend：待发货  pendreceipt：待收货  pendcomment：待评价）
     */
    public function executeMyorderCount()
    {
        $v = $this->getRequest()->getParameter('v');
        $type = $this->getRequest()->getParameter('type', 'all');

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
            $info = TrdMainOrderTable::getInstance()
                ->createQuery()
                ->select('count(1) AS total')
                ->andWhere('hupu_uid = ?', $hupuUid)
                ->andWhere('status = ?', 0)
                ->fetchOne()
                ->toArray();
            $return['pendpay'] = $info['total'];
        }
        if ($type == 'pendsend' || $type == 'all') {
            $info = TrdMainOrderTable::getInstance()
                ->createQuery()
                ->select('count(1) AS total')
                ->andWhere('hupu_uid = ?', $hupuUid)
                ->andWhere('status = ?', 1)
                ->fetchOne()
                ->toArray();
            $return['pendsend'] = $info['total'];
        }
        if ($type == 'pendreceipt' || $type == 'all') {
            $info = TrdMainOrderTable::getInstance()
                ->createQuery()
                ->select('count(1) AS total')
                ->andWhere('hupu_uid = ?', $hupuUid)
                ->andWhere('status = ?', 2)
                ->fetchOne()
                ->toArray();
            $return['pendreceipt'] = $info['total'];
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
        if ($type == 'all') {
            $info = TrdMainOrderTable::getInstance()
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
        $pageSize = $this->getRequest()->getParameter('pageSize', 10);

        $hupuUid = $this->getUser()->getAttribute('uid');
        $hupuUname = $this->getUser()->getAttribute('username');
        if (empty($hupuUid) || !is_numeric($hupuUid)) {
            return $this->error(501, '未登录');
        }
        if (!in_array($type, array('all', 'pendpay', 'pendsend', 'pendreceipt', 'pendcomment'))) {
            return $this->error(400, '参数错误');
        }
        if ($pageSize > 100) {
            $pageSize = 100;
        }
        if (!is_numeric($page) || (int)$page < 1) {
            return $this->error(400, '参数错误');
        }
        if (!is_numeric($pageSize) || (int)$pageSize < 1) {
            return $this->error(400, '参数错误');
        }

        $offset = ($page - 1) * $pageSize;
        $mainOrderObj = TrdMainOrderTable::getInstance()->createQuery()->select()
            ->where('hupu_uid = ?', $hupuUid)
            ->offset($offset)
            ->limit($pageSize)
            ->orderBy('order_time desc');

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
            $mainOrderObj->andWhere('status = ?', 6);
        }
        $mainOrder = $mainOrderObj->execute();
        $order_number = array();
        $new_main_order = array();
        $data = $return = array();
        if ($mainOrder->getData()) {
            foreach ($mainOrder as $k => $v) {
                $order_number[] = $v->getOrderNumber();
                $new_main_order[$v->getOrderNumber()] = array(
                    'order_number' => $v->getOrderNumber(),
                    'order_time' => $v->getOrderTime(),
                    'number' => $v->getNumber(),
                    'status' => $v->getStatus(),
                    'total_price' => $v->getTotalPrice(),
                    'total_express_fee' => $v->getExpressFee(),
                    'tax_status' => $v->getTaxStatus(),
                    'tax' => $v->getTax()
                );
            }
            $orderObj = TrdOrderTable::getInstance()->createQuery()->select()
                ->where("order_number in (" . join(",", $order_number) . ") ")
                ->orderBy('order_time desc')
                ->execute();
            $i = 0;
            foreach ($orderObj as $k => $v) {
                if (!isset($data[$v->getOrderNumber()])) {
                    $i = 0;
                }

                $attr = json_decode($v->getAttr(), true);
                if (!is_array($attr)) {
                    $attr = array();
                } else {
                    $img = tradeCommon::getQiNiuProxyPath($attr['img']) . '?imageView2/1/w/100/h/100';
                    unset($attr['price']);
                    unset($attr['name']);
                    unset($attr['img']);
                }
                $data[$v->getOrderNumber()][$i] = array(
                    'id' => $v->getId(),
                    'status' => $v->getOrderStatusInfo(),
                    'status_code' => $v->getStatus(),
                    'pay_status' => $v->getPayStatus(),
                    'is_comment' => $v->getIsComment() ? true : false,
                    'pay_time' => $v->getPayTime(),
                    'product_id' => $v->getProductId(),
                    'gid' => $v->getGId(),
                    'title' => $v->getTitle(),
                    'business' => $v->getBusiness(),
                    'price' => $v->getPrice(),
                    'mart_order_number' => $v->getMartOrderNumber(),
                    'express_fee' => $v->getExpressFee(),
                    'mart_express_number' => $v->getMartExpressNumber(),
                    'total_price' => $v->getTotalPrice(),
                    'img' => $img,
                    'attr' => $attr,
                    'mart_express_time' => $v->getMartExpressTime()
                );
                $i++;
            }
            $j = 0;
            foreach ($data as $k => $v) {
                $return[$j]['main_order'] = $new_main_order[$k];
                $return[$j]['order'] = array_values($v);
                $j++;
            }

        }

        return $this->success(array('myorder' => $return));
    }

    /**
     *
     * 立即购买，订单详情 （单个购买）
     * @param int product_id 主订单id
     * @param int goods_id 子订单id
     * @param int number 购买数量
     * $serviceRequest = new tradeServiceClient();
     * $serviceRequest->setMethod('order.orderDetail');
     * $serviceRequest->setVersion('1.0');
     * $serviceRequest->setApiParam('product_id', $product_id);
     * $serviceRequest->setApiParam('goods_id', $goods_id);
     * $serviceRequest->setApiParam('num', $num);
     * $response = $serviceRequest->execute();
     */
    public function executeOrderDetail()
    {
        //$hupuUid = $this->getUser()->getAttribute('uid');
        $product_id = $this->getRequest()->getParameter('product_id');
        $goods_id = $this->getRequest()->getParameter('goods_id');
        $num = $this->getRequest()->getParameter('number', 1);
        $type = $this->getRequest()->getParameter('type', 0); //1表示营销活动
        try {
//            if (empty($hupuUid)) {
//                throw new Exception('未登录', 500);
//            }
            if (empty($product_id) || empty($goods_id)) {
                throw new Exception('参数有误', 400);
            }

            if (!is_numeric($num) || $num < 1) {
                throw new Exception('参数有误', 400);
            }

            $product_info = TrdProductAttrTable::getInstance()->find($product_id);
            if (!$product_info || !$product_info->getShowFlag() || $product_info->getStatus() == 1) {
                throw new Exception('已下架或已售罄', 401);
            }

            $goods_info = TrdHaitaoGoodsTable::getInstance()->createQuery('m')->select('*')->where('m.id = ?',
                $goods_id)->andWhere('m.product_id = ?', $product_id)->andWhere('m.status = 0')->limit(1)->fetchOne();
            if (!$goods_info || !$goods_info->getAttr()) {
                throw new Exception('已下架或已售罄', 401);
            }

            if ($num > $product_info->getLimits()) {
                $num = $product_info->getLimits();
            }

            $goods_attr = json_decode($goods_info->getAttr(), true);

            if ($goods_attr['Offers']['Offer']['OfferListing']['Price']['CurrencyCode'] == 'JPY') {
                $rate = TrdHaitaoCurrencyExchangeTable::getRate('jpy');
                $price = ceil($goods_attr['Offers']['Offer']['OfferListing']['Price']['Amount'] * $rate * 100) / 100;
            } elseif($goods_attr['Offers']['Offer']['OfferListing']['Price']['CurrencyCode'] == 'USD') {
                $rate = TrdHaitaoCurrencyExchangeTable::getRate();
                $price = ceil($goods_attr['Offers']['Offer']['OfferListing']['Price']['Amount'] * $rate) / 100;
            } else {
                $price = $goods_attr['Offers']['Offer']['OfferListing']['Price']['Amount'];
            }
            //获取价格
            $exchange = $goods_attr['Offers']['Offer']['OfferListing']['Price']['FormattedPrice'];//外币假

            $name = $goods_attr['ASIN'];
            $img_path = tradeCommon::getQiNiuProxyPath($goods_attr['LargeImage']['URL']) . '?imageView2/1/w/100/h/100';
            $attr_val = array();
            if (isset($goods_attr['VariationAttributes']['VariationAttribute']) && !empty($goods_attr['VariationAttributes']['VariationAttribute'])) {
                $attr_val = $goods_attr['VariationAttributes']['VariationAttribute'];
            }
            $weight = $weight = $product_info->getWeight() ? $product_info->getWeight() : $product_info->getBusinessWeight();
            $original_freight = $product_info->getBusiness() == TrdProductAttrTable::$zhifa_shihuo_business ? 0 : $this->getAllFreight($weight, $num);
            if ($num > 1 && $product_info->getBusiness() != TrdProductAttrTable::$zhifa_shihuo_business) {
                $weight = $weight < 0.5 ? 0.5 * $num : $weight * $num;
            }
            $freight = $product_info->getBusiness() == TrdProductAttrTable::$zhifa_shihuo_business ? 0 : $this->getAllFreight($weight, $num, false);
            $save_freight = $original_freight - $freight;

            $total_price = $freight + $price * $num;
            $original_total_price = $price * $num + $original_freight;


            //营销活动
            $activity = array();
            $activity_save = 0;
            if ($type == 1) {
                $act_goods_info[$goods_id]['product_id'] = $product_id;
                $act_goods_info[$goods_id]['price'] = $price * $num;
                $act_goods_info[$goods_id]['goods_id'] = $goods_id;
                $act_goods_info[$goods_id]['merchant'] = $goods_attr['Offers']['Offer']['Merchant']['Name'];

                $serviceRequest = new tradeServiceClient();
                $serviceRequest->setMethod('marketing.getMarketingInfo');
                $serviceRequest->setVersion('1.0');
                $serviceRequest->setApiParam('goods_info', $act_goods_info);
                $response = $serviceRequest->execute();
                if (!$response->hasError()) {
                    $act_data = $response->getData();
                    $activity_save = $act_data['data']['data']['activity_save'];
                    $total_price -= $activity_save;
                    $activity = $act_data['data']['data'];
                }
            }

            //计算美国运费
            $usa_freight = 0;
            if ($product_info->getBusiness() == '6pm'){
                $activity_save_usa = 0;
                if($activity_save){
                    $activity_save_usa = ceil($activity_save * 100 / $rate) / 100;
                }
                if(($goods_attr['Offers']['Offer']['OfferListing']['Price']['Amount'] / 100) * $num - $activity_save_usa < 50){
                    $usa_freight = ceil($this->USA_FIXED_FREIGHT * $rate * 100) / 100;
                    $total_price += $usa_freight;
                    $original_total_price += $usa_freight;
                }
            }

            $return = array(
                'title' => $product_info->getTitle(), //标题
                'attr_val' => $attr_val, //规格
                'img_path' => $img_path, //图片
                'business' => $product_info->getBusiness(), //商家
                'price' => $price, //单价
                'exchange' => $exchange, //外币价
                'weight' => $weight, //重量
                'original_freight' => $original_freight, //原始总运费
                'freight' => $freight, //运费
                'usa_freight' => $usa_freight, //美国运费
                'save_freight' => $save_freight, //节省运费
                'original_total_price' => $original_total_price, //原始总价
                'total_price' => $total_price, //实付总价
                'product_total_price' => $price * $num, //商品总价
                'product_id' => $product_id, //主商品id
                'goods_id' => $goods_id, //子商品id
                'number' => $num, //数量,
                'product_limits' => $product_info->getLimits()
            );
            if (!empty($activity)) $return['activity'] = $activity;

            if (substr($goods_info->getGoodsId(), 0, 2) == 'cn') {
                $return['stock'] = $goods_info->getTotalNum() ? $goods_info->getTotalNum() : 0; // 商品库存
            }

        } catch (Exception $e) {
            return $this->error($e->getCode(), $e->getMessage());
        }

        return $this->success(array('data' => $return));
    }


    /**
     *
     * 立即购买 提交订单
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
     *    'coupon_id'=>0, 礼品卡id
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
    public function executeSubmitOrder()
    {
        set_time_limit(0);
        $param = $this->request->getParameter('param', array());
        $param['uid'] = isset($param['uid']) && !empty($param['uid']) ? $param['uid'] : $this->getUser()->getAttribute('uid');
        $param['uname'] = isset($param['uname']) && !empty($param['uname']) ? $param['uname'] : $this->getUser()->getAttribute('username');

        if (empty($param['uid'])) {
            return $this->error(500, '未登录');
        }

        if (!is_numeric($param['number']) || $param['number'] < 1){
            return $this->error(400, '参数有误');
        }

        if (!isset($param['product_id']) || !isset($param['goods_id']) || !isset($param['region_id']) || !isset($param['number']) || !isset($param['source'])) {
            return $this->error(400, '参数有误');
        }

        try {
            $product_info = TrdProductAttrTable::getInstance()->find($param['product_id']);
            if (!$product_info || !$product_info->getShowFlag()) {
                throw new Exception('已下架或已售罄', 401);
            }

            if ($param['number'] > $product_info->getLimits()) {
                throw new Exception('超过限购数了', 402);
            }
            $region = TrdUserDeliveryAddressTable::getInstance()->find($param['region_id']);
            if (!$region) {
                throw new Exception('没有收货地址', 403);
            }

            if (!$region->getIdentityNumber()) {
                throw new Exception('必须要身份证号码', 404);
            }

//            $identityNumberObj = TrdIdentityNumberValidateTable::getInstance()->findOneByIdentityNumber($region->getIdentityNumber());
//            if (!$identityNumberObj){
//                $tradeBirdexNewService = new tradeBirdexNewService();
//                $identity = $tradeBirdexNewService->idcardValidate($region->getIdentityNumber(), $region->getName(), 3);
//                if ($identity == 'failed'){
//                    throw new Exception('身份证或姓名验证错误，请修改或更换', 405);
//                }
//                if ($identity == 'success'){
//                    $identityNumberObj = new TrdIdentityNumberValidate();
//                    $identityNumberObj->setIdentityNumber($region->getIdentityNumber());
//                    $identityNumberObj->setName($region->getName());
//                    $identityNumberObj->save();
//                }
//            } else if($identityNumberObj->getName() != $region->getName()){
//                throw new Exception('身份证或姓名验证错误，请修改或更换', 405);
//            }

            //对子商品加锁，防止库存不足
            $status = tradeCommon::getLock('shihuo.product.goods' . $param['goods_id'], 5);//获取锁
            if ($status[0]['status'] < 1){
                throw new Exception('系统繁忙，请稍后再试', 502);
            }
            $goodsInfo = $goods_info = TrdHaitaoGoodsTable::getInstance()->createQuery('m')->select('*')->where('m.id = ?',
                $param['goods_id'])->andWhere('m.product_id = ?',
                $param['product_id'])->andWhere('m.status = 0')->limit(1)->fetchOne();

            if (!$goods_info || !$goods_info->getAttr()) {
                tradeCommon::releaseLock('shihuo.product.goods' . $param['goods_id']);//释放锁
                throw new Exception('已下架或已售罄', 401);
            }

            $weight = $product_info->getWeight() ? $product_info->getWeight() : $product_info->getBusinessWeight();
            if (empty($weight) && $product_info->getBusiness() != TrdProductAttrTable::$zhifa_shihuo_business) {
                tradeCommon::releaseLock('shihuo.product.goods' . $param['goods_id']);//释放锁
                throw new Exception('请联系客服修改运费', 402);
            }
            if ($param['number'] > 1 && $product_info->getBusiness() != TrdProductAttrTable::$zhifa_shihuo_business) {
                $weight = $weight < 0.5 ? 0.5 * $param['number'] : $weight * $param['number'];
            }
            $freight = $product_info->getBusiness() == TrdProductAttrTable::$zhifa_shihuo_business ? 0 : $this->getAllFreight($weight, $param['number'], false);
            $goods_attr = json_decode($goods_info->getAttr(), true);

            if ($goods_attr['Offers']['Offer']['OfferListing']['Price']['CurrencyCode'] == 'JPY') {
                $rate = TrdHaitaoCurrencyExchangeTable::getRate('jpy');
                $price = ceil($goods_attr['Offers']['Offer']['OfferListing']['Price']['Amount'] * $rate * 100) / 100;
            } elseif($goods_attr['Offers']['Offer']['OfferListing']['Price']['CurrencyCode'] == 'USD') {
                $rate = TrdHaitaoCurrencyExchangeTable::getRate();
                $price = ceil($goods_attr['Offers']['Offer']['OfferListing']['Price']['Amount'] * $rate) / 100;
            } else {
                $price = $goods_attr['Offers']['Offer']['OfferListing']['Price']['Amount'];
            }

            $original_price = $total_price = $freight + $price * $param['number'];

            $name = $goods_attr['ASIN'];
            $new_attr = array();
            if (isset($goods_attr['VariationAttributes']['VariationAttribute']) && !empty($goods_attr['VariationAttributes']['VariationAttribute'])) {
                foreach ($goods_attr['VariationAttributes']['VariationAttribute'] as $k => $v) {
                    $new_attr[$v['Name']] = $v['Value'];
                }
            }
            $new_attr['price'] = $goods_attr['Offers']['Offer']['OfferListing']['Price']['Amount'] ? $goods_attr['Offers']['Offer']['OfferListing']['Price']['Amount'] : '';
            if ($name) {
                $new_attr['name'] = $name;
            }
            $new_attr['img'] = $goods_attr['LargeImage']['URL'];
            $order_sn = date('ymd') . substr(time(), -5) . substr(microtime(), 2, 5);
            $orderHisObj = TrdOrderTable::getInstance()->createQuery()->select('id')->where('order_number = ?',
                $order_sn)->execute();
            if ($orderHisObj) {
                $order_sn = date('ymd') . substr(time(), -5) . substr(microtime(), 2, 5);
            }

            //地址拼接
            $address = $region->getName() . ' ' . trim($region->getRegion()) . ' ' . $region->getStreet() . '（邮编：' . $region->getPostcode() . '）' . ' ';
            if ($region->getMobile()) {
                $address .= '手机：' . $region->getMobile();
            } else {
                $address .= '电话：' . $region->getPhonesection() . '-' . $region->getPhonecode();
                if ($region->getPhoneext()) {
                    $address .= '-' . $region->getPhoneext();
                }
            }
            $street = explode(' ', trim($region->getRegion()));
            $address_arr = array(
                'name' => $region->getName(),
                'postcode' => $region->getPostcode(),
                'province' => $street[0],
                'city' => $street[1],
                'area' => isset($street[2]) ? $street[2] : '',
                'mobile' => $region->getMobile(),
                'region' => $region->getRegion(),
                'street' => $region->getStreet(),
                'identity_number' => $region->getIdentityNumber()
            );

            $stock_change_array = array();//记录库存需要变更的数组
            //获取Doctrine链接
            $connection_trade = sfContext::getInstance()->getDatabaseManager()->getDatabase('trade')->getDoctrineConnection();
            try {
                $connection_trade->beginTransaction();

                //营销活动
                $activity_save = 0;
                if (isset($param['type']) && $param['type'] == 1) {
                    $act_goods_info[$param['goods_id']]['product_id'] = $param['product_id'];
                    $act_goods_info[$param['goods_id']]['price'] = $price * $param['number'];
                    $act_goods_info[$param['goods_id']]['goods_id'] = $param['goods_id'];
                    $act_goods_info[$param['goods_id']]['merchant'] = $goods_attr['Offers']['Offer']['Merchant']['Name'];

                    $serviceRequest = new tradeServiceClient();
                    $serviceRequest->setMethod('marketing.getMarketingInfo');
                    $serviceRequest->setVersion('1.0');
                    $serviceRequest->setApiParam('goods_info', $act_goods_info);
                    $response = $serviceRequest->execute();
                    if (!$response->hasError()) {
                        $act_data = $response->getData();
                        $goods_info = $act_data['data']['data']['goods_info'];
                        $activity_save = $act_data['data']['data']['activity_save'];
                        $total_price -= $activity_save;
                        if ($total_price <= 0) {
                            throw new Exception('订单金额必须大于0元', 406);
                            $total_price = 0;
                            $activity_save = $original_price;
                        }

                        //插入营销活动记录
                        foreach ($act_data['data']['data']['activity'] as $k => $v){
                            foreach ($v['list'] as $kk => $vv) {
                                if ($vv['flag']) {
                                    $marketingDetailObi = new TrdOrderMarketingDetail();
                                    $marketingDetailObi->set('order_number', $order_sn);
                                    $marketingDetailObi->set('marketing_id', $vv['id']);
                                    $marketingDetailObi->set('attr', json_encode($vv));
                                    $marketingDetailObi->save();
                                }
                            }
                        }
                    }
                }

                //计算美国运费运费
                if ($product_info->getBusiness() == '6pm'){
                    $activity_save_usa = 0;
                    if($activity_save){
                        $activity_save_usa = ceil($activity_save * 100 / $rate) / 100;
                    }
                    if(($goods_attr['Offers']['Offer']['OfferListing']['Price']['Amount'] / 100) * $param['number'] - $activity_save_usa < 50){
                        $usa_freight = ceil($this->USA_FIXED_FREIGHT * $rate * 100) / 100;
                        $original_price += $usa_freight;
                        $total_price += $usa_freight;
                        $freight += $usa_freight;
                    }
                }

                $coupon_fee = 0;
                //验证礼品卡
                if (isset($param['coupon_id']) && !empty($param['coupon_id'])) {
                    $serviceRequest = new tradeServiceClient();
                    $serviceRequest->setMethod('lipinka.u.check');
                    $serviceRequest->setVersion('1.0');
                    $serviceRequest->setApiParam('user_id', $param['uid']);
                    $serviceRequest->setApiParam('id', $param['coupon_id']);
                    $response = $serviceRequest->execute();
                    if (!$response->hasError()) {
                        $data = $response->getData();
                        $coupon_fee = $data['data']['amount'];
                        $total_price -= $coupon_fee;
                        if ($total_price < 0) {
                            $total_price = 0;
                            $coupon_fee = $freight + $price * $param['number'];
                        }
                    } else {
                        throw new Exception('礼品卡无法使用，请再试一次', 408);
                    }
                }

                $time = date('Y-m-d H:i:s');
                //保存订单
                //插入主表 trd_main_order
                $mainOrderObj = new TrdMainOrder();
                $mainOrderObj->setOrderNumber($order_sn);
                $mainOrderObj->setHupuUid($param['uid']);
                $mainOrderObj->setHupuUsername($param['uname']);
                $mainOrderObj->setAddress($address);
                $mainOrderObj->setAddressAttr(json_encode($address_arr));
                $mainOrderObj->setExpressFee($freight);
                $mainOrderObj->setTotalPrice($total_price);
                $mainOrderObj->setOriginalPrice($original_price);
                $mainOrderObj->setCouponFee($coupon_fee);
                $mainOrderObj->setMarketingFee($activity_save);
                $mainOrderObj->setNumber($param['number']);
                $mainOrderObj->setRemark($param['remark']);
                if ($param['sourceChannel']) $mainOrderObj->setSource($param['sourceChannel']);
                $mainOrderObj->setOrderTime($time);
                $mainOrderObj->save();

                $freight_per = floor(($freight / $param['number']) * 100) / 100;
                $freight_sum = 0;

                $pre_marketing_fee = 0;
                $total_marketing_fee = 0;
                $marketing_fee = 0;
                //优惠金额
                if (isset($param['type']) && $param['type'] == 1 && $activity_save > 0 && isset($goods_info[$param['goods_id']]) && isset($goods_info[$param['goods_id']]['marketing_fee'])){
                    $pre_marketing_fee = ceil($goods_info[$param['goods_id']]['marketing_fee'] * 100 / $param['number']) / 100;
                }
                //保存子订单
                for ($i = 0; $i < $param['number']; $i++) {
                    $orderObj = new TrdOrder();
                    $orderObj->setOrderNumber($order_sn);
                    $orderObj->setTitle($product_info->getTitle());
                    $orderObj->setProductId($param['product_id']);
                    $orderObj->setBusiness($product_info->getBusiness());
                    $orderObj->setHupuUid($param['uid']);
                    $orderObj->setHupuUsername($param['uname']);
                    //商品id
                    $goodsId = tradeCommon::getDaigouPrefix($product_info->getUrl(), $product_info->getBusiness()) . $new_attr['name'];
                    //需要判断库存
                    if (substr($goodsId, 0, 2) == 'cn'){
                        $stockUpdate = TrdHaitaoGoodsTable::getInstance()
                            ->createQuery()
                            ->update()
                            ->where('id = ?', $param['goods_id'])
                            ->andWhere('total_num > ?',0)
                            ->andWhere('status = ?', 0)
                            ->set('total_num', 'total_num - 1')
                            ->set('lock_num', 'lock_num + 1')
                            ->execute();
                        if ($stockUpdate == 1){//库存足 需要发送库存变更通知
                            $stock_change_array[$param['product_id']] = $param['product_id'];
                        } else {//库存不足 抛出错误 回滚
                            throw new Exception('商品库存不足，请返回修改', 407);
                        }
                    }
                    $orderObj->setGoodsId($goodsId);
                    $orderObj->setGid($param['goods_id']);
                    $orderObj->setAttr(json_encode($new_attr));
                    $orderObj->setPrice($price);
                    if ($i == $param['number'] - 1) {
                        $freight_ch = $freight - $freight_sum;
                    } else {
                        $freight_ch = $freight_per;
                    }
                    $freight_sum += $freight_ch;
                    $orderObj->setExpressFee($freight_ch);
                    $orderObj->setTotalPrice($price + $freight_ch);
                    $orderObj->setOrderTime($time);
                    if ($product_info->getBusiness() == TrdProductAttrTable::$zhifa_shihuo_business){//识货自己发 写入发货方式
                        $orderObj->setDeliveryType(5);
                        $orderObj->setDomesticExpressType(3);//仓库默认发圆通
                    } elseif ($product_info->getBusiness() == TrdProductAttrTable::$zhifa_ebay_business){//ebay
                        $orderObj->setDeliveryType(6);
                    }

                    //保存优惠金额
                    if ($pre_marketing_fee > 0) {
                        if ($total_marketing_fee + $pre_marketing_fee >= $goods_info[$param['goods_id']]['marketing_fee']) {
                            $marketing_fee = $goods_info[$param['goods_id']]['marketing_fee'] - $total_marketing_fee;
                        } else {
                            $marketing_fee = $pre_marketing_fee;
                            $total_marketing_fee += $pre_marketing_fee;
                        }
                    }
                    $orderObj->setMarketingFee($marketing_fee);

                    $orderObj->setSource($param['source']);//下单来源
                    if (in_array($param['source'], array(3, 4))) {
                        isset($param['channel']) && $orderObj->setChannel($param['channel']);
                    }
                    if(isset($param['mobile']) && !empty($param['mobile'])) $orderObj->setMobile($param['mobile']);//非会员下单联系人手机号码
                    $orderObj->save();
                }

                $connection_trade->commit();
            } catch (Exception $e) {//事物回滚
                $connection_trade->rollBack();
                return $this->error($e->getCode(), $e->getMessage());
            }

            //使用礼品卡
            if (isset($param['coupon_id']) && !empty($param['coupon_id'])) {
                $serviceRequest = new tradeServiceClient();
                $serviceRequest->setMethod('lipinka.use');
                $serviceRequest->setVersion('1.0');
                $serviceRequest->setApiParam('user_id', $param['uid']);
                $serviceRequest->setApiParam('id', $param['coupon_id']);
                $response = $serviceRequest->execute();
                if (!$response->hasError()) {
                    $data = $response->getData();
                    $coupon_fee = $data['data']['amount'];

                    //插入礼品卡使用记录
                    $activityDetailObi = new TrdOrderActivityDetail();
                    $activityDetailObi->set('order_number', $order_sn);
                    $activityDetailObi->set('activity_id', $param['coupon_id']);
                    $activityDetai_attr = array('code' => $data['data']['account'], 'price' => $coupon_fee);
                    $activityDetailObi->set('attr', json_encode($activityDetai_attr));
                    $activityDetailObi->save();
                }
            }

            //下单日志
            $history = new TrdHaitaoOrderHistory();
            $history->setOrderNumber($order_sn);
            $history->setHupuUid($param['uid']);
            $history->setHupuUsername($param['uname']);
            $history->setType(50);
            $platform = $this->getSourceName($param['source']);
            $history->setExplanation($platform . '购买了：' .$product_info->getTitle());
            $history->save();

            //生成支付链接
            if (isset($param['linkFlag']) && $param['linkFlag']) {
                $serviceRequest = new tradeServiceClient();
                $serviceRequest->setMethod('order.payOrder');
                $serviceRequest->setVersion('1.0');
                $serviceRequest->setApiParam('uid', $param['uid']);
                $serviceRequest->setApiParam('order_number', $order_sn);
                $response = $serviceRequest->execute();
                if (!$response->hasError()){
                    $data = $response->getData();
                    $pay_url = $data['data']['url'];
                    $return = array(
                        'order_number' => $order_sn,
                        'total_price' => $total_price,
                        'pay_url' => $pay_url,
                    );
                } else {
                    return $this->error(405, $response->getError());
                }
            } else {
                $return = array(
                    'order_number' => $order_sn,
                    'total_price' => $total_price,
                    'pay_url' => ''
                );
            }

        } catch (Exception $e) {
            return $this->error($e->getCode(), $e->getMessage());
        }

        //cps推广
        if (isset($param['sh_union']) && !empty($param['sh_union'])) {
            $message = array('order_number'=>$order_sn, 'cookie'=>$param['sh_union'], 'type'=>'create');
            tradeCommon::sendMqMessage('order.detail',$message,'order_detail_deferred');
        }

        //库存变更 发送消息
        if (!empty($stock_change_array)){
            foreach ($stock_change_array as $v){
                $message = array('product_id'=>$v);
                tradeCommon::sendMqMessage('product.stock',$message,'product_stock_deferred');
            }
        }

        return $this->success(array('data' => $return));
    }

    /**
     * source 0pc 1m 2app
     * 生成支付链接
     */
    public function executePayOrder()
    {
        $v = $this->getRequest()->getParameter('version');
        $orderNumber = $this->getRequest()->getParameter('order_number');
        $hupuUid =  $this->getRequest()->getParameter('uid', $this->getUser()->getAttribute('uid'));
        $channelId = $this->getRequest()->getParameter('channel_id', 'alipay_www');
        $source = $this->getRequest()->getParameter('source', 0);

        if (empty($hupuUid) || !is_numeric($hupuUid)) {
            return $this->error(501, '未登录');
        }
        if (empty($orderNumber)) {
            return $this->error(400, '参数错误');
        }
        $mainOrderObj = TrdMainOrderTable::getInstance()->createQuery()
            ->where('order_number = ?', $orderNumber)->andWhere('hupu_uid = ?', $hupuUid)->andWhere('status = ?',
                0)->limit(1)->fetchOne();
        if (!$mainOrderObj) {
            return $this->error(401, '该订单不存在');
        }
        $orderObj = TrdOrderTable::getInstance()->createQuery()
            ->where('order_number = ?', $orderNumber)->andWhere('hupu_uid = ?', $hupuUid)->andWhere('status = ?',
                0)->limit(1)->fetchOne();
        if (!$orderObj) {
            return $this->error(401, '该订单不存在');
        }

        if ($source) {
            $callback = 'http://m.shihuo.cn/haitao/orderPayResult/' . $orderNumber;
        } else {
            $callback = 'http://www.shihuo.cn/haitao/orderPayResult/' . $orderNumber;
        }
        if ($mainOrderObj->getTotalPrice() == 0){
            return $this->error(401, '订单金额必须大于0元');
        }
        //付款
        $param = array(
            'title' => str_replace('*', '', $orderObj->getTitle()),
            'userId' => $mainOrderObj->getHupuUid(),
            'source' => 1,
            'amount' => $mainOrderObj->getTotalPrice(),
            'channelId' => $channelId,
            'callBackUrl' => $callback,
            'notifyUrl' => 'http://www.shihuo.cn/haitao/orderCallback/' . $orderNumber,
            'orderPrefix' => 'SH',
            'timeout' => 120
        );
        $pay_api = new tradePayApi();
        $json = $pay_api->post('/pay-api/order/createRechargeTradeOrder', $param);
        if ($json) {
            return $this->success(array('url' => $json->url));
        } else {
            return $this->error(402, '生成支付链接失败');
        }
    }

    /**
     * 获取下单成功页订单信息
     * @return array
     */
    public function executePayInfo()
    {
        $v = $this->getRequest()->getParameter('version');
        $uid = $this->getUser()->getAttribute('uid');
        $hupuUname = $this->getUser()->getAttribute('username');
        $order_number = $this->getRequest()->getParameter('order_number');
        if (!$uid) {
            $this->error(501, '亲，您还未登录~');

            return;
        }
        $order_number = $this->getRequest()->getParameter('order_number', 0);
        $orderObj = TrdMainOrderTable::getInstance()->createQuery()
            ->select('order_number, total_price, original_price, express_fee, status')
            ->where('order_number = ?', $order_number)
            ->andWhere('hupu_uid = ?', $uid)
            ->limit(1)
            ->fetchOne();

        if (!$orderObj) {
            $this->error(401, '您的登录信息与订单号不匹配');

            return;
        }
        $orders = TrdOrderTable::getInstance()->createQuery('a')
            ->select("count(goods_id) as goods_num, a.product_id, a.attr, a.price, a.title")
            ->where('order_number = ?', $order_number)
            ->andWhere('hupu_uid = ?', $uid)
            ->groupBy('goods_id')
            ->fetchArray();
        if (!$orders) {
            $this->error(402, '订单数据有误，请联系客服');

            return;
        }

        $product_ids = array();
        foreach ($orders as $key => &$value) {
            $order_attr = json_decode($value['attr'], true);
            $value['img_path'] = tradeCommon::getQiNiuProxyPath($order_attr['img']);
            unset($order_attr['price']);
            unset($order_attr['name']);
            unset($order_attr['img']);
            $value['sku'] = $order_attr;
            $product_ids[] = $value['product_id'];
        }

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

        if ((int) $total_freight == (int) $orderObj['express_fee']) {
            $save_freight = 0;
        } else {
            $save_freight = $total_freight - $orderObj['express_fee'];
        }

        return $this->success(
            array(
                'data' => array(
                    'order_num' => $orderObj->getOrderNumber(),
                    'order_status' => $orderObj->getStatus(),
                    'order_express_fee' => $orderObj->getExpressFee(),
                    'order_total_price' => $orderObj->getTotalPrice(),
                    'order_save_freight' => $save_freight,
                    'coupon_fee' => $orderObj->getCouponFee(),
                    'marketing_fee' => $orderObj->getMarketingFee(),
                    'orders' => $orders
                )
            )
        );
    }

    /**
     * source 0pc 1m 2app
     * 生成支付关税链接
     */
    public function executePayOrderTax()
    {
        $v = $this->getRequest()->getParameter('version');
        $orderNumber = $this->getRequest()->getParameter('order_number');
        $hupuUid = $this->getUser()->getAttribute('uid');
        $hupuUname = $this->getUser()->getAttribute('username');
        $channelId = $this->getRequest()->getParameter('channel_id', 'alipay_www');
        $source = $this->getRequest()->getParameter('source', 0);

        if (empty($hupuUid) || !is_numeric($hupuUid)) {
            return $this->error(501, '未登录');
        }
        if (empty($orderNumber)) {
            return $this->error(400, '参数错误');
        }

        $mainOrderObj = TrdMainOrderTable::getInstance()->createQuery()
            ->where('order_number = ?', $orderNumber)->andWhere('hupu_uid = ?', $hupuUid)->andWhere('tax_status = ?',
                1)->limit(1)->fetchOne();
        if (!$mainOrderObj) {
            return $this->error(401, '订单号非法');
        }

        if ($source) {
            $callback = 'http://m.shihuo.cn/haitao/orderPayResultTax/' . $orderNumber;
        } else {
            $callback = 'http://www.shihuo.cn/haitao/orderPayResultTax/' . $orderNumber;
        }
        //付款
        $param = array(
            'title' => $orderNumber . '订单号缴纳关税',
            'userId' => $mainOrderObj->getHupuUid(),
            'source' => 1,
            'amount' => $mainOrderObj->getTax(),
            'channelId' => $channelId,
            'callBackUrl' => $callback,
            'notifyUrl' => 'http://www.shihuo.cn/haitao/orderCallbackTax/' . $orderNumber,
            'orderPrefix' => 'SHGS',
            'timeout' => 120
        );
        $pay_api = new tradePayApi();
        $json = $pay_api->post('/pay-api/order/createRechargeTradeOrder', $param);
        if ($json) {
            return $this->success(array('url' => $json->url));
        } else {
            return $this->error(402, '生成支付链接失败');
        }
    }

    /**
     * 获取订单基本信息
     */
    public function executeGetOrderBaseInfo()
    {
        $orderNumber = $this->getRequest()->getParameter('order_number');

        if (empty($orderNumber)) {
            return $this->error(400, '参数错误');
        }

        $orderMainObj = TrdMainOrderTable::getInstance()->createQuery()->select('*')->where('order_number = ?',$orderNumber)->limit(1)->fetchOne();
        if (!$orderMainObj){
            return $this->error(401, '不存在该订单号');
        }

        if ($orderMainObj->getStatus() == 0){
            $status = '待付款';
        } elseif ($orderMainObj->getStatus() == 1) {
            $status = '待发货';
        } elseif ($orderMainObj->getStatus() == 2) {
            $status = '待收货';
        } elseif ($orderMainObj->getStatus() == 3) {
            $status = '已完成';
        } elseif ($orderMainObj->getStatus() == 4) {
            $status = '已取消';
        } elseif ($orderMainObj->getStatus() == 5) {
            $status = '已退款';
        } else {
            $status = '待评价';
        }

        if ($orderMainObj->getIbillingNumber()){
            $pay_status = '已支付';
        } else {
            $pay_status = '未支付';
        }
        return $this->success(array(
                'status' => $status, 'pay_status' => $pay_status, 'pay_time' => $orderMainObj->getPayTime(),
                'uid'=>$orderMainObj->getHupuUid()
            )
        );
    }

    /**
     *
     * 获取是哪个平台
     */
    private function getSourceName($source)
    {
        switch ($source) {
            case 0 :
                return 'PC';
            case 1 :
                return 'M站';
            case 2 :
                return '一键购';
            case 3 :
                return 'Android';
            case 4 :
                return 'IOS';
            default :
                return '';
        }
    }

    /**
     *
     * 获取当前的汇率
     */
    private function getCurrentRate()
    {
        $rateInfo = TrdHaitaoCurrencyExchangeTable::getInstance()->createQuery()->select('exchange_rate')->where('currency_from = ?',
            0)->andWhere('currency_to = ?', 1)->limit(1)->fetchOne();
        if ($rateInfo) {
            return $rateInfo->getExchangeRate();
        } else {
            return 6.3;
        }
    }

    /**
     * 获取邮费
     * @param $weight
     * @param int $number
     * @param bool $flag
     * @return float|int
     */
    private function getAllFreight($weight, $number = 1, $flag = true)
    {
        if ($flag || $number == 1) {//单间计算价格
            $freight = $weight * 40;
            if ($freight < 46) {
                $freight = 46;
            }
            $res = $freight * $number;
        } else {
            $res = $weight * 32 + 16;
            $res = ceil($res * 100) / 100;
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
        if ($res < 46) {
            $res = 46;
        }

        return $res;
    }
}