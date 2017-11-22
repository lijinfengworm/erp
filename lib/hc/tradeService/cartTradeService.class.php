<?php

/**
 * 购物车服务类
 * version:1.0
 */
class cartTradeService extends tradeService
{
    private $USA_FIXED_FREIGHT = 4.95; //美国本土固定运费
    /**
     * 添加购物车商品
     * @return array
     * @throws sfException
     */
    public function executeAdd()
    {
        $version = $this->getRequest()->getParameter('version', '');
        $hupuUid = $this->getUser()->getAttribute('uid');
        $hupuUname = $this->getUser()->getAttribute('username');
        $product_id = $this->getRequest()->getParameter('product_id');
        $goods_id = $this->getRequest()->getParameter('goods_id');
        $number = $this->getRequest()->getParameter('number', 1);
        $source = $this->getRequest()->getParameter('source');

        if (empty($hupuUid) || !is_numeric($hupuUid)) {
            return $this->error(501, '未登录');
        }

        if (!$product_id || !$goods_id) {
            return $this->error(400, '参数错误');
        }
        
        if ($number < 1) {
            return $this->error(400, '参数错误');
        }

        $redis = sfContext::getInstance()->getDatabaseConnection('kaluliRedis');
        $cartObj = TrdShoppingCartTable::getInstance()->createQuery()->select()->where('hupu_uid = ?', $hupuUid)->andWhere('goods_id = ?', $goods_id)->fetchOne();

        $product = TrdProductAttrTable::getInstance()->find($product_id);
        $goodsObj = TrdHaitaoGoodsTable::getInstance()->createQuery('m')
            ->select()
            ->where('m.id = ?', $goods_id)
            ->andWhere('m.product_id = ?', $product_id)
            ->fetchOne();

        if ($product) {
            if ($cartObj && $cartObj->getNumber() + $number > $product->getLimits()) {//超过库存
                return $this->error(401, '超过库存');
            } elseif ($number > $product->getLimits()) {
                return $this->error(401, '超过库存');
            }
        }

        if (!$goodsObj) {
            return $this->error(402, '子商品不存在');
        }

        if ($cartObj) {
            $cartObj->set('number', $cartObj->getNumber() + $number);
        } else {
            $cartObj = new TrdShoppingCart();
            $cartObj->set('number', $number);
            $cartObj->set('hupu_uid', $hupuUid);
            $cartObj->set('hupu_username', $hupuUname);
            $cartObj->set('product_id', $product_id);
            $cartObj->set('goods_id', $goods_id);
            $cartObj->set('source', $source);
        }
        $cartObj->save();
        $count = TrdShoppingCartTable::getInstance()->createQuery()->select()->where('hupu_uid = ?', $hupuUid)->count();
        $redis->set('trade_shopping_cart_' . $hupuUid, $count, 3600 * 24 * 180);
        $goods_attr = json_decode($goodsObj->getAttr(), 1);
        $img_path = tradeCommon::getQiNiuProxyPath($goods_attr['LargeImage']['URL']) . '?imageView2/1/w/40/h/40';
        return $this->success(array('status' => 0, 'data' => array('count' => $count, 'img_path' => $img_path), 'msg' => 'ok'));
    }

    /**
     * 删除购物车商品
     * @return array
     * @throws sfException
     */
    public function executeDelete()
    {
        $version = $this->getRequest()->getParameter('version', '');
        $hupuUid = $this->getUser()->getAttribute('uid');
        $hupuUname = $this->getUser()->getAttribute('username');
        $goods_id = $this->getRequest()->getParameter('goods_id');

        if (empty($hupuUid) || !is_numeric($hupuUid)) {
            return $this->error(501, '未登录');
        }

        if (!$goods_id) {
            return $this->error(400, '参数错误');
        }

        $redis = sfContext::getInstance()->getDatabaseConnection('kaluliRedis');
        TrdShoppingCartTable::getInstance()->createQuery('m')
            ->delete()
            ->where('m.hupu_uid = ?', $hupuUid)
            ->whereIn('m.goods_id', $goods_id)
            ->execute();
        $count = TrdShoppingCartTable::getInstance()->createQuery()->select()->where('hupu_uid = ?', $hupuUid)->count();
        $redis->set('trade_shopping_cart_' . $hupuUid, $count, 3600 * 24 * 180);
        return $this->success(array('status' => 0, 'data' => array('count' => $count), 'msg' => 'ok'));
    }

    /**
     * 修改购物车商品
     */
    public function executeEdit()
    {
        $version = $this->getRequest()->getParameter('version', '');
        $hupuUid = $this->getUser()->getAttribute('uid');
        $hupuUname = $this->getUser()->getAttribute('username');
        $goods_id = $this->getRequest()->getParameter('goods_id');
        $type = $this->getRequest()->getParameter('type');

        if (empty($hupuUid) || !is_numeric($hupuUid)) {
            return $this->error(501, '未登录');
        }

        if (!$goods_id) {
            return $this->error(400, '参数错误');
        }

        $cartObj = TrdShoppingCartTable::getInstance()->createQuery()->select()->where('goods_id = ?', $goods_id)->andWhere('hupu_uid = ?', $hupuUid)->fetchOne();
        if (!$cartObj) {
            return $this->error(404, '购物车不存在该商品');
        }
        $goodsObj = TrdHaitaoGoodsTable::getInstance()->createQuery('m')
            ->select()
            ->where('m.id = ?', $cartObj->getGoodsId())
            ->fetchOne();
        if (!$goodsObj) {
            return $this->error(401, '子商品不存在');
        }
        if ($goodsObj->getProductId()) {
            $productObj = TrdProductAttrTable::getInstance()->createQuery('m')
                ->select()
                ->where('m.id = ?', $goodsObj->getProductId())
                ->fetchOne();
            if ($productObj && $productObj->getLimits() && $type) {
                if ($cartObj->getNumber() >= $productObj->getLimits()) {
                    return $this->error(402, '超过库存');
                }
            }
        }
        if ($type) {//增加
            $cartObj->set('number', $cartObj->getNumber() + 1);
        } else {
            if ($cartObj->getNumber() == 1) {
                return $this->error(403, '只有一个不能再减少了');
            }
            $cartObj->set('number', $cartObj->getNumber() - 1);
        }
        $cartObj->save();

        $goods_attr = json_decode($goodsObj->getAttr(), 1);
        if ($goods_attr['Offers']['Offer']['OfferListing']['Price']['CurrencyCode'] == 'JPY') {
            $rate = TrdHaitaoCurrencyExchangeTable::getRate('jpy');
            $price = ceil($goods_attr['Offers']['Offer']['OfferListing']['Price']['Amount'] * $rate * 100) / 100;
        } elseif($goods_attr['Offers']['Offer']['OfferListing']['Price']['CurrencyCode'] == 'USD') {
            $rate = TrdHaitaoCurrencyExchangeTable::getRate();
            $price = ceil($goods_attr['Offers']['Offer']['OfferListing']['Price']['Amount'] * $rate) / 100;
        } else {
            $price = $goods_attr['Offers']['Offer']['OfferListing']['Price']['Amount'];
        }
        $number = $cartObj->getNumber();
        $total_price = $price * $number;
        $weight = $productObj->getWeight() ? $productObj->getWeight() : $productObj->getBusinessWeight();
        $total_freight = $this->getAllFreight($weight, $cartObj->getNumber());
        return $this->success(array('status' => 0, 'data' => array('total_price' => $total_price, 'freight' => $total_freight, 'weight' => $weight, 'price' => $price, 'number' => $number), 'msg' => 'ok'));
    }

    /**
     * 获取购物车商品数据
     * @return array
     */
    public function executeList()
    {
        $version = $this->getRequest()->getParameter('version', '');
        $hupuUid = $this->getUser()->getAttribute('uid');
        $hupuUname = $this->getUser()->getAttribute('username');
        $goodsIds = $this->getRequest()->getParameter('gid');
        $type = $this->getRequest()->getParameter('type', 0);//1表示需要 营销活动
        if (empty($hupuUid) || !is_numeric($hupuUid)) {
            return $this->error(501, '未登录');
        }

        //初始化数据
        $total_count = $total_number = $total_product_price = $total_freight = $total_count = 0;

        //获取购物车数据
        $cartObj = TrdShoppingCartTable::getInstance()->createQuery('m')->select()->where('m.hupu_uid = ?', $hupuUid);
        if ($goodsIds) $cartObj = $cartObj->whereIn('m.goods_id', $goodsIds);
        $cartObj = $cartObj->orderBy('created_at desc')->execute();

        $cartNumber = count($cartObj);//购物车数量

        if (count($cartObj) == 0) {
            return $this->success(array('status' => 0, 'data' => array('result' => array(), 'updateList' => array(), 'total_data' => array()), 'msg' => 'ok'));
        }

        //购物车商品ids 初始化商品信息 $goodsArr子商品详细信息 $productArr主商品详细信息
        $goods_ids = $product_ids = $goodsArr = $productArr = array();

        $this->getCartData($cartObj, $goods_ids, $product_ids, $goodsArr, $productArr);

        $result = $dataArray = $updateList = array();

        //存储营销活动需要的数据
        $act_goods_info = array();

        //运费分地区存储数组
        $weight_area = array('usa'=>array('weight'=>0, 'number'=>0), 'jp'=>array('weight'=>0, 'number'=>0), 'hk'=>array('weight'=>0, 'number'=>0), 'hkebay'=>array('weight'=>0, 'number'=>0));

        //6pm商品数组
        $usa_pm = array();

        foreach ($cartObj as $m => $n) {
            $m = $m + 1;
            if (!isset($goodsArr[$n->getGoodsId()])) {
                $n->delete();
                continue;
            }
            $goods_id = $goodsArr[$n->getGoodsId()]->getGoodsId();
            if (strpos($goods_id, 'usa.amazon') !== false) {
                $business = '美国亚马逊';
                $key = 0;
            } elseif (strpos($goods_id, '6pm') !== false) {
                $business = '6PM';
                $key = 1;
            } elseif (strpos($goods_id, 'gnc') !== false) {
                $business = 'GNC';
                $key = 2;
            } elseif (strpos($goods_id, 'levis') !== false) {
                $business = 'Levis';
                $key = 3;
            } elseif (strpos($goods_id, 'nbastore') !== false) {
                $business = 'NBAStore';
                $key = 4;
            } elseif (strpos($goods_id, 'jp.amazon') !== false) {
                $business = '日本亚马逊';
                $key = 5;
            } elseif (strpos($goods_id, 'cn.hkebay') !== false) {
                $business = TrdProductAttrTable::$zhifa_ebay_business;
                $key = 8;
            } elseif (strpos($goods_id, 'cn.hk') !== false) {
                $business = TrdProductAttrTable::$zhifa_business;
                $key = 6;
            } elseif (strpos($goods_id, 'cn.sh') !== false) {
                $business = TrdProductAttrTable::$zhifa_shihuo_business;
                $key = 7;
            } else {
                $business = 'unkown';
                $key = -1;
            }
            $productId = $goodsArr[$n->getGoodsId()]->getProductId();
            $result[$key]['data'][$m]['id'] = $n->getId();
            $result[$key]['data'][$m]['number'] = $n->getNumber();
            $result[$key]['data'][$m]['goods_id'] = $n->getGoodsId();
            $result[$key]['business'] = $business;

            $goods_attr = json_decode($goodsArr[$n->getGoodsId()]->getAttr(), 1);
            if ($goods_attr['Offers']['Offer']['OfferListing']['Price']['CurrencyCode'] == 'JPY') {
                $rate = TrdHaitaoCurrencyExchangeTable::getRate('jpy');
                $result[$key]['data'][$m]['price'] = ceil($goods_attr['Offers']['Offer']['OfferListing']['Price']['Amount'] * $rate * 100) / 100;
            } elseif($goods_attr['Offers']['Offer']['OfferListing']['Price']['CurrencyCode'] == 'USD') {
                $rate = TrdHaitaoCurrencyExchangeTable::getRate();
                $result[$key]['data'][$m]['price'] = ceil($goods_attr['Offers']['Offer']['OfferListing']['Price']['Amount'] * $rate) / 100;
            } else {
                $result[$key]['data'][$m]['price'] = $goods_attr['Offers']['Offer']['OfferListing']['Price']['Amount'];
            }
            $result[$key]['data'][$m]['total_price'] = $result[$key]['data'][$m]['price'] * $result[$key]['data'][$m]['number'];
            $result[$key]['data'][$m]['img_path'] = tradeCommon::getQiNiuProxyPath($goods_attr['LargeImage']['URL']) . '?imageView2/1/w/100/h/100';
            $result[$key]['data'][$m]['attr'] = array();
            if (isset($goods_attr['VariationAttributes']['VariationAttribute']) && !empty($goods_attr['VariationAttributes']['VariationAttribute'])) {
                $result[$key]['data'][$m]['attr'] = $goods_attr['VariationAttributes']['VariationAttribute'];
            }
            $result[$key]['data'][$m]['invalid'] = false;   //没失效
            $result[$key]['data'][$m]['updateFlag'] = false; //不要更新

            $result[$key]['data'][$m]['product_id'] = $productId;
            if ($goodsArr[$n->getGoodsId()]->getStatus() == 1 || !isset($productArr[$productId])) {
                $result[$key]['data'][$m]['invalid'] = true;//失效
            }
            $result[$key]['data'][$m]['title'] = $result[$key]['data'][$m]['freight'] = '';
            $result[$key]['data'][$m]['limits'] = 1;
            $result[$key]['data'][$m]['restriction'] = false;//是否显示限购 false不显示
            if (isset($productArr[$productId])) {
                $result[$key]['data'][$m]['title'] = $productArr[$productId]->getTitle();
                $weight = $productArr[$productId]->getWeight() ? $productArr[$productId]->getWeight() : $productArr[$productId]->getBusinessWeight();
                $result[$key]['data'][$m]['weight'] = $weight;
                $result[$key]['data'][$m]['freight'] = $business == TrdProductAttrTable::$zhifa_shihuo_business ? 0 : $this->getAllFreight($weight, $result[$key]['data'][$m]['number']);
                $result[$key]['data'][$m]['limits'] = $productArr[$productId]->getLimits();

                //limit 判断
                if (substr($goodsArr[$n->getGoodsId()]->getGoodsId(), 0, 2) == 'cn'){
                    if ($goodsArr[$n->getGoodsId()]->getTotalNum() < $productArr[$productId]->getLimits()){
                        $result[$key]['data'][$m]['limits'] = $goodsArr[$n->getGoodsId()]->getTotalNum();
                    }
                }
                if ($result[$key]['data'][$m]['limits'] < $result[$key]['data'][$m]['number']){
                    $result[$key]['data'][$m]['restriction'] = true;
                }
                if ($productArr[$productId]->getStatus() || $productArr[$productId]->getShowFlag() == 0) {
                    $result[$key]['data'][$m]['invalid'] = true;//失效
                }
                if (($weight > 0 || $business == TrdProductAttrTable::$zhifa_shihuo_business) && !$result[$key]['data'][$m]['restriction']) {
                    if (($cartNumber == 1 && $n->getNumber() == 1)) {
                        if ($business == '日本亚马逊') {
                            $weight_area['jp']['weight'] += ($weight) * $n->getNumber();
                            $weight_area['jp']['number'] += $n->getNumber();
                        } elseif ($business == TrdProductAttrTable::$zhifa_business) {
                            $weight_area['hk']['weight'] += ($weight) * $n->getNumber();
                            $weight_area['hk']['number'] += $n->getNumber();
                        } elseif ($business == TrdProductAttrTable::$zhifa_ebay_business) {
                            $weight_area['hkebay']['weight'] += ($weight) * $n->getNumber();
                            $weight_area['hkebay']['number'] += $n->getNumber();
                        } elseif($business != TrdProductAttrTable::$zhifa_shihuo_business) {
                            $weight_area['usa']['weight'] += ($weight) * $n->getNumber();
                            $weight_area['usa']['number'] += $n->getNumber();
                        }
                    } else {
                        if ($business == '日本亚马逊') {
                            $weight_area['jp']['weight'] += $weight < 0.5 ? 0.5 * $n->getNumber() : ($weight) * $n->getNumber();//总重量
                            $weight_area['jp']['number'] += $n->getNumber();
                        } elseif ($business == TrdProductAttrTable::$zhifa_business) {
                            $weight_area['hk']['weight'] += ($weight) * $n->getNumber();
                            $weight_area['hk']['number'] += $n->getNumber();
                        } elseif ($business == TrdProductAttrTable::$zhifa_ebay_business) {
                            $weight_area['hkebay']['weight'] += ($weight) * $n->getNumber();
                            $weight_area['hkebay']['number'] += $n->getNumber();
                        } elseif($business != TrdProductAttrTable::$zhifa_shihuo_business) {
                            $weight_area['usa']['weight'] += $weight < 0.5 ? 0.5 * $n->getNumber() : ($weight) * $n->getNumber();//总重量
                            $weight_area['usa']['number'] += $n->getNumber();
                        }
                    }
                    $total_number += $n->getNumber();
                    $total_count++;
                    $total_product_price += $result[$key]['data'][$m]['total_price'];
                    $total_freight += $result[$key]['data'][$m]['freight'];
                }

                //判断活动时间
                $now_time = time();
                if ($productArr[$productId]->getLastCrawlDate() + 3600 < $now_time) {
                    $result[$key]['data'][$m]['updateFlag'] = true;//更新
                }
            }
            if ($goodsIds && ($result[$key]['data'][$m]['invalid'] || ($weight == 0 && $business != TrdProductAttrTable::$zhifa_shihuo_business))) {//购物车确认页面 失效
                unset($result[$key]['data'][$m]);
                if (count($result[$key]['data']) < 1) {
                    unset($result[$key]);
                }
                continue;
            }

            //营销活动
            if ($type == 1 && !$result[$key]['data'][$m]['invalid']
                && ($weight > 0 || $business == TrdProductAttrTable::$zhifa_shihuo_business)){
                $act_goods_info[$n->getGoodsId()]['product_id'] = $productId;
                $act_goods_info[$n->getGoodsId()]['price'] = $result[$key]['data'][$m]['total_price'];
                $act_goods_info[$n->getGoodsId()]['goods_id'] = $n->getGoodsId();
                $act_goods_info[$n->getGoodsId()]['merchant'] = $goods_attr['Offers']['Offer']['Merchant']['Name'];

                //记录6pm的商品
                if ($key == 1){
                    $usa_pm[] = array(
                        'goods_id' => $n->getGoodsId(),
                        'price' => $result[$key]['data'][$m]['price'],
                        'exchange'=> $goods_attr['Offers']['Offer']['OfferListing']['Price']['Amount'] * $n->getNumber() / 100
                    );
                }
            }


            if ($result[$key]['data'][$m]['updateFlag']) {
                $dataArray[$n->getGoodsId()]['productId'] = $productId;
                $dataArray[$n->getGoodsId()]['data']['goods_id'] = $n->getGoodsId();
                $dataArray[$n->getGoodsId()]['data']['price'] = $result[$key]['data'][$m]['total_price'];
                $dataArray[$n->getGoodsId()]['data']['number'] = $result[$key]['data'][$m]['number'];
            }
        }

        //更新
        if ($dataArray) {
            foreach ($dataArray as $k => $v) {
                $updateList[] = $v;
            }
        }

        //营销活动
        if ($type == 1) {
            $serviceRequest = new tradeServiceClient();
            $serviceRequest->setMethod('marketing.getMarketingInfo');
            $serviceRequest->setVersion('1.0');
            $serviceRequest->setApiParam('goods_info', $act_goods_info);
            if (empty($goodsIds)) $serviceRequest->setApiParam('type', 1);
            $response = $serviceRequest->execute();
            if (!$response->hasError()) {
                $act_data = $response->getData();
            }
        }

        $total_product_freight = 0;//运费 需要根据地区算

        //计算运费
        foreach ($weight_area as $w => $a) {
            $total_product_freight += $a['weight'] ? $this->getAllFreight($a['weight'], $a['number'], false) : 0;
        }

        $total_price = $total_product_price + $total_product_freight;
        $save_freight = $total_freight - $total_product_freight;
        $total_data = array(
            'total_count' => $total_count,
            'total_price' => $total_price,
            'total_product_freight' => $total_product_freight,
            'total_product_price' => $total_product_price,
            'save_freight' => $save_freight,
            'original_total_price' => $total_price + $save_freight,
            'usa_freight' => 0,
        );

        if ($type == 1 && isset($act_data) && !empty($act_data['data']['data'])){
            $total_data['total_price'] = $total_data['total_price'] - (isset($act_data['data']['data']['activity_save']) ? $act_data['data']['data']['activity_save'] : 0);
            $total_data['activity'] = $act_data['data']['data'];
        }

        //判断6pm运费
        if(!empty($usa_pm)){
            $rate = TrdHaitaoCurrencyExchangeTable::getRate();
            $total_exchange = 0;
            foreach ($usa_pm as $k=>$v){
                $total_exchange += $v['exchange'];
                if(!empty($act_data['data']['data']['goods_info'][$v['goods_id']]) && !empty($act_data['data']['data']['goods_info'][$v['goods_id']]['marketing_fee'])){
                    $activity_save_usa = ceil($act_data['data']['data']['goods_info'][$v['goods_id']]['marketing_fee'] * 100 / $rate) / 100;
                    $total_exchange -= $activity_save_usa;
                }
            }
            if ($total_exchange < 50){
                $usa_freight = ceil($this->USA_FIXED_FREIGHT * $rate * 100) / 100;
                $total_data['usa_freight'] += $usa_freight;
                $total_data['total_price'] += $usa_freight;
                $total_data['original_total_price'] += $usa_freight;
            }
        }

        return $this->success(array('status' => 0, 'data' => array('result' => $result, 'updateList' => $updateList, 'total_data' => $total_data), 'msg' => 'ok'));
    }

    /**
     * 获取选中商品最新价格信息
     */
    public function executePrice()
    {
        $version = $this->getRequest()->getParameter('version', '');
        $hupuUid = $this->getUser()->getAttribute('uid');
        $hupuUname = $this->getUser()->getAttribute('username');
        $goodsIds = $this->getRequest()->getParameter('gid');
        $type = $this->getRequest()->getParameter('type', 0);//1表示需要 营销活动
        if (empty($hupuUid) || !is_numeric($hupuUid)) {
            return $this->error(501, '未登录');
        }

        if (!isset($goodsIds) || empty($goodsIds)) {
            return $this->error(400, '没有商品ID');
        }

        $total_count = 0;
        $total_product_price = 0;
        $total_product_freight = 0;
        $total_price = 0;
        $total_number = 0;
        $total_freight = 0;

        $cartObj = TrdShoppingCartTable::getInstance()->createQuery('m')
            ->select()
            ->where('m.hupu_uid = ?', $hupuUid)
            ->whereIn('m.goods_id', $goodsIds)
            ->orderBy('created_at desc')
            ->execute();
        $cartNumber = count($cartObj);
        if (count($cartObj) < 1) {
            return $this->error(401, '购物车中没有对应商品');
        }

        //购物车商品ids 初始化商品信息 $goodsArr子商品详细信息 $productArr主商品详细信息
        $goods_ids = $product_ids = $goodsArr = $productArr = array();

        $this->getCartData($cartObj, $goods_ids, $product_ids, $goodsArr, $productArr);

        $dataArray = $updateList = array();

        //存储营销活动需要的数据
        $act_goods_info = array();

        //运费分地区存储数组
        $weight_area = array('usa'=>array('weight'=>0, 'number'=>0), 'jp'=>array('weight'=>0, 'number'=>0), 'hk'=>array('weight'=>0, 'number'=>0), 'hkebay'=>array('weight'=>0, 'number'=>0));

        //6pm商品数组
        $usa_pm = array();

        foreach ($cartObj as $m => $n) {
            $goods_attr = json_decode($goodsArr[$n->getGoodsId()]->getAttr(), 1);
            $productId = $goodsArr[$n->getGoodsId()]->getProductId();
            $flag = false;//没失效
            if ($goodsArr[$n->getGoodsId()]->getStatus() == 1 || !isset($productArr[$productId])) {
                $flag = true;//失效
            }
            if (isset($productArr[$productId])) {
                if ($productArr[$productId]->getStatus() || $productArr[$productId]->getShowFlag() == 0) {
                    $flag = true;//失效
                }
                $weight = $productArr[$productId]->getWeight() ? $productArr[$productId]->getWeight() : $productArr[$productId]->getBusinessWeight();
                if (empty($weight)) $flag = true;//失效
                if (!$flag) {
                    if ($goods_attr['Offers']['Offer']['OfferListing']['Price']['CurrencyCode'] == 'JPY') {
                        $rate = TrdHaitaoCurrencyExchangeTable::getRate('jpy');
                        $price = ceil($goods_attr['Offers']['Offer']['OfferListing']['Price']['Amount'] * $rate * 100) / 100;
                    } elseif($goods_attr['Offers']['Offer']['OfferListing']['Price']['CurrencyCode'] == 'USD') {
                        $rate = TrdHaitaoCurrencyExchangeTable::getRate();
                        $price = ceil($goods_attr['Offers']['Offer']['OfferListing']['Price']['Amount'] * $rate) / 100;
                    } else {
                        $price = $goods_attr['Offers']['Offer']['OfferListing']['Price']['Amount'];
                    }
                    $price = $price * $n->getNumber();
                    $freight = $productArr[$productId]->getBusiness() == TrdProductAttrTable::$zhifa_shihuo_business ? 0 : $this->getAllFreight($weight, $n->getNumber());
                    if (($cartNumber == 1 && $n->getNumber() == 1)) {
                        if ($productArr[$productId]->getBusiness() == '日本亚马逊') {
                            $weight_area['jp']['weight'] += ($weight) * $n->getNumber();
                            $weight_area['jp']['number'] += $n->getNumber();
                        } elseif ($productArr[$productId]->getBusiness() == TrdProductAttrTable::$zhifa_business) {
                            $weight_area['hk']['weight'] += ($weight) * $n->getNumber();
                            $weight_area['hk']['number'] += $n->getNumber();
                        } elseif ($productArr[$productId]->getBusiness() == TrdProductAttrTable::$zhifa_ebay_business) {
                            $weight_area['hkebay']['weight'] += ($weight) * $n->getNumber();
                            $weight_area['hkebay']['number'] += $n->getNumber();
                        } elseif($productArr[$productId]->getBusiness() != TrdProductAttrTable::$zhifa_shihuo_business) {
                            $weight_area['usa']['weight'] += ($weight) * $n->getNumber();
                            $weight_area['usa']['number'] += $n->getNumber();
                        }
                    } else {
                        if ($productArr[$productId]->getBusiness() == '日本亚马逊') {
                            $weight_area['jp']['weight'] += $weight < 0.5 ? 0.5 * $n->getNumber() : ($weight) * $n->getNumber();//总重量
                            $weight_area['jp']['number'] += $n->getNumber();
                        } elseif ($productArr[$productId]->getBusiness() == TrdProductAttrTable::$zhifa_business) {
                            $weight_area['hkebay']['weight'] += ($weight) * $n->getNumber();
                            $weight_area['hkebay']['number'] += $n->getNumber();
                        } elseif ($productArr[$productId]->getBusiness() == TrdProductAttrTable::$zhifa_ebay_business) {
                            $weight_area['hk']['weight'] += ($weight) * $n->getNumber();
                            $weight_area['hk']['number'] += $n->getNumber();
                        } elseif($productArr[$productId]->getBusiness() != TrdProductAttrTable::$zhifa_shihuo_business) {
                            $weight_area['usa']['weight'] += $weight < 0.5 ? 0.5 * $n->getNumber() : ($weight) * $n->getNumber();//总重量
                            $weight_area['usa']['number'] += $n->getNumber();
                        }
                    }

                    //记录6pm的商品
                    if ($productArr[$productId]->getBusiness() == '6pm'){
                        $usa_pm[] = array(
                            'goods_id' => $n->getGoodsId(),
                            'price' => $price,
                            'exchange'=> $goods_attr['Offers']['Offer']['OfferListing']['Price']['Amount'] * $n->getNumber() / 100
                        );
                    }

                    $total_number += $n->getNumber();
                    $total_count++;
                    $total_product_price += $price;
                    $total_freight += $freight;

                    //营销活动
                    $act_goods_info[$n->getGoodsId()]['product_id'] = $productId;
                    $act_goods_info[$n->getGoodsId()]['price'] = $price;
                    $act_goods_info[$n->getGoodsId()]['goods_id'] = $n->getGoodsId();
                    $act_goods_info[$n->getGoodsId()]['merchant'] = $goods_attr['Offers']['Offer']['Merchant']['Name'];
                }
            }
        }

        //营销活动
        if ($type == 1) {
            $serviceRequest = new tradeServiceClient();
            $serviceRequest->setMethod('marketing.getMarketingInfo');
            $serviceRequest->setVersion('1.0');
            $serviceRequest->setApiParam('goods_info', $act_goods_info);
            $response = $serviceRequest->execute();
            if (!$response->hasError()) {
                $act_data = $response->getData();
            }
        }

        //计算运费
        foreach ($weight_area as $w => $a) {
            $total_product_freight += $a['weight'] ? $this->getAllFreight($a['weight'], $a['number'], false) : 0;
        }
        $total_price = $total_product_price + $total_product_freight;
        $save_freight = $total_freight - $total_product_freight;
        $data = array(
            'total_count' => $total_count,
            'total_product_price' => $total_product_price,
            'total_product_freight' => $total_product_freight,
            'total_price' => $total_price,
            'save_freight' => $save_freight,
            'original_total_price' => $total_price + $save_freight,
            'usa_freight' => 0,
        );

        if ($type == 1 && isset($act_data) && !empty($act_data['data']['data'])){
            $data['total_price'] = $data['total_price'] - $act_data['data']['data']['activity_save'];
            $data['activity'] = $act_data['data']['data'];
        }

        //判断6pm运费
        if(!empty($usa_pm)){
            $rate = TrdHaitaoCurrencyExchangeTable::getRate();
            $total_exchange = 0;
            foreach ($usa_pm as $k=>$v){
                $total_exchange += $v['exchange'];
                if(!empty($act_data['data']['data']['goods_info'][$v['goods_id']]) && !empty($act_data['data']['data']['goods_info'][$v['goods_id']]['marketing_fee'])){
                    $activity_save_usa = ceil($act_data['data']['data']['goods_info'][$v['goods_id']]['marketing_fee'] * 100 / $rate) / 100;
                    $total_exchange -= $activity_save_usa;
                }
            }
            if ($total_exchange < 50){
                $usa_freight = ceil($this->USA_FIXED_FREIGHT * $rate * 100) / 100;
                $data['usa_freight'] += $usa_freight;
                $data['total_price'] += $usa_freight;
                $data['original_total_price'] += $usa_freight;
            }
        }

        return $this->success(array('status' => 0, 'data' => $data, 'msg' => 'ok'));
    }

    /**
     * 获取购物车商品数量
     * @return array
     * @throws sfException
     */
    public function executeCount()
    {
        $this->getRequest()->getParameter('version');
        $hupuUid = $this->getUser()->getAttribute('uid');
        $hupuUname = $this->getUser()->getAttribute('username');

        if (empty($hupuUid) || !is_numeric($hupuUid)) {
            return $this->error(501, '未登录');
        }

        $redis = sfContext::getInstance()->getDatabaseConnection('kaluliRedis');
        $count = $redis->get('trade_shopping_cart_' . $hupuUid);
        if (!$count) {
            $count = TrdShoppingCartTable::getInstance()->createQuery()->select()->where('hupu_uid = ?', $hupuUid)->count();
            if ($count) {
                return $this->success(array('status' => 0, 'data' => array('count' => $count), 'msg' => 'ok'));
            } else {
                return $this->success(array('status' => 0, 'data' => array('count' => $count), 'msg' => '购物车为空'));
            }
        } else {
            return $this->success(array('status' => 0, 'data' => array('count' => $count), 'msg' => 'ok'));
        }
    }

    /**
     * 获取收货地址信息
     * 有address_id获取address_id数据，无则返回默认地址
     */
    public function executeAddress()
    {
        $version = $this->getRequest()->getParameter('version');
        $hupuUid = $this->getUser()->getAttribute('uid');
        $hupuUname = $this->getUser()->getAttribute('username');
        $address_id = $this->getRequest()->getParameter('address_id', 0);
        $listFlag = $this->getRequest()->getParameter('is_list', 0);

        if (empty($hupuUid) || !is_numeric($hupuUid)) {
            return $this->error(501, '未登录');
        }

        if (!is_numeric($address_id) && !is_array($address_id)) {
            return $this->error(401, '地址ID错误');
        }

        //获取送货地址
        $address = TrdUserDeliveryAddressTable::getInfoByHupuUid($hupuUid);
        $default = $other = array();
        if ($address) {
            if (!$listFlag) {
                $oldestAdd = $address[0];
                $oldestTime = $address[0]['created_at'];
                foreach ($address as $k => $v) {
                    $date = $v['created_at'];
                    if($date < $oldestTime) {
                        $oldestTime = $date;
                        $oldestAdd = $v;
                    }
                    if (($v['defaultflag'] == 1 && !$address_id)) {
                        $default[] = $v;
                        foreach ($default as $k => &$v) {
                            $v['identity_number'] = $v['identity_number'] ? substr($v['identity_number'], 0, 5) . '**********' . substr($v['identity_number'], 15, 3) : "";
                        }
                        return $this->success(array('status' => 0, 'data' => array('address' => $default), 'msg' => 'ok'));
                    }
                    if (is_array($address_id)) {
                        foreach ($address_id as $add) {
                            if ($add == $v['id']) {
                                $other[] = $v;
                            }
                        }
                    } else {
                        if ($address_id == $v['id']) {
                            $other[] = $v;
                        }
                    }
                }
                if (empty($default) && empty($other)) {
                    $default[] = $oldestAdd;
                    foreach ($default as $k => &$v) {
                        $v['identity_number'] = $v['identity_number'] ? substr($v['identity_number'], 0, 5) . '**********' . substr($v['identity_number'], 15, 3) : "";
                    }
                    return $this->success(array('status' => 0, 'data' => array('address' => $default), 'msg' => 'ok'));
                }
                foreach ($other as $k => &$v) {
                    $v['identity_number'] = $v['identity_number'] ? substr($v['identity_number'], 0, 5) . '**********' . substr($v['identity_number'], 15, 3) : "";
                }
                return $this->success(array('status' => 0, 'data' => array('address' => $other), 'msg' => 'ok'));
            } else {
                foreach ($address as $k => &$v) {
                    $v['identity_number'] = $v['identity_number'] ? substr($v['identity_number'], 0, 5) . '**********' . substr($v['identity_number'], 15, 3) : "";
                }
                return $this->success(array('status' => 0, 'data' => array('address' => $address), 'msg' => 'ok'));
            }

        } else {
            return $this->error(401, '无收货地址');
        }
    }

    /**
     * 提交订单
     * @return array
     * @throws sfException
     */
    public function executeSubmitOrder()
    {
        set_time_limit(0);
        $version = $this->getRequest()->getParameter('version', '');
        $hupuUid = $this->getUser()->getAttribute('uid');
        $hupuUname = $this->getUser()->getAttribute('username');

        $remark = $this->getRequest()->getParameter('remark');//备注
        $region_id = $this->getRequest()->getParameter('address_id');//地址
        $goods_ids = $this->getRequest()->getParameter('goods_id');
        $coupon_id = $this->getRequest()->getParameter('coupon_id', null);//礼品卡id
        $channel = $this->getRequest()->getParameter('channel', null); // app 渠道
        $sourceChannel = $this->getRequest()->getParameter('sourceChannel', 0); // 下单终端

        $type = $this->getRequest()->getParameter('type', 0); // 1表示需要营销活动
        $sh_union = $this->getRequest()->getParameter('sh_union'); // cps推广标志

        if (!$hupuUid) {
            return $this->error(501, '未登录');
        }
        if (!is_numeric($region_id)) {
            return $this->error(401, '请填写收货地址');
        }
        $address = TrdUserDeliveryAddressTable::getInstance()->find($region_id);
        if (!$address) {
            return $this->error(402, '请填写收货地址');
        }
        if (!$address->getIdentityNumber()) {
            return $this->error(403, '请填写身份证号码');
        }

//        $identityNumberObj = TrdIdentityNumberValidateTable::getInstance()->findOneByIdentityNumber($address->getIdentityNumber());
//        if (!$identityNumberObj){
//            $tradeBirdexNewService = new tradeBirdexNewService();
//            $identity = $tradeBirdexNewService->idcardValidate($address->getIdentityNumber(), $address->getName(), 3);
//            if ($identity == 'failed'){
//                return $this->error(405, '身份证或姓名验证错误，请修改或更换');
//            }
//            if ($identity == 'success'){
//                $identityNumberObj = new TrdIdentityNumberValidate();
//                $identityNumberObj->setIdentityNumber($address->getIdentityNumber());
//                $identityNumberObj->setName($address->getName());
//                $identityNumberObj->save();
//            }
//        } else if($identityNumberObj->getName() != $address->getName()){
//            return $this->error(405, '身份证或姓名验证错误，请修改或更换');
//        }

        $data_array = $goods_ids;

        if (empty($data_array)) {
            return $this->error(404, '参数异常');
        }

        $cartObj = TrdShoppingCartTable::getInstance()->createQuery('m')
            ->select()
            ->where('m.hupu_uid = ?', $hupuUid)
            ->whereIn('m.goods_id', $data_array)
            ->orderBy('created_at desc')->execute();

        $goodsObj = TrdHaitaoGoodsTable::getInstance()->createQuery('m')
            ->select()
            ->whereIn('m.id', $data_array)
            ->execute();

        $product_ids = $goodsArr = $productArr = array();
        if (count($goodsObj) > 0) {
            foreach ($goodsObj as $k => $v) {
                array_push($product_ids, $v->getProductId());
                $goodsArr[$v->getId()] = $v;
            }
        }

        if ($product_ids) {
            $productObj = TrdProductAttrTable::getInstance()->createQuery('m')
                ->select()
                ->whereIn('m.id', $product_ids)
                ->execute();
            if (count($productObj) > 0) {
                foreach ($productObj as $k => $v) {
                    $productArr[$v->getId()] = $v;
                }
            }
        }

        //存储营销活动需要的数据
        $act_goods_info = array();

        //运费分地区存储数组
        $weight_area = array('usa'=>array('weight'=>0, 'number'=>0), 'jp'=>array('weight'=>0, 'number'=>0), 'hk'=>array('weight'=>0, 'number'=>0), 'hkebay'=>array('weight'=>0, 'number'=>0));

        //6pm商品数组
        $usa_pm = array();
        //6pm商品重量数组
        $usa_pm_weight = array();

        $result = array();
        foreach ($cartObj as $m => $n) {
            $goods_id = $goodsArr[$n->getGoodsId()]->getGoodsId();
            $productId = $goodsArr[$n->getGoodsId()]->getProductId();
            $result[$m]['id'] = $n->getId();
            $result[$m]['source'] = $n->getSource();
            $result[$m]['number'] = $n->getNumber();
            $result[$m]['goods_id'] = $n->getGoodsId();

            if($productArr[$productId]->getLimits() < $n->getNumber()){
                return $this->error(407, '商品库存不足，请返回购物车修改');
            }

            $goods_attr = json_decode($goodsArr[$n->getGoodsId()]->getAttr(), 1);
            $name = $goods_attr['ASIN'];
            $new_attr = array();
            if (isset($goods_attr['VariationAttributes']['VariationAttribute']) &&
                !empty($goods_attr['VariationAttributes']['VariationAttribute'])
            ) {
                foreach ($goods_attr['VariationAttributes']['VariationAttribute'] as $k => $v) {
                    $new_attr[$v['Name']] = $v['Value'];
                }
            }
            $new_attr['price'] = $goods_attr['Offers']['Offer']['OfferListing']['Price']['Amount'] ? $goods_attr['Offers']['Offer']['OfferListing']['Price']['Amount'] : '';
            if ($name) {
                $new_attr['name'] = $name;
            }
            $new_attr['img'] = $goods_attr['LargeImage']['URL'];

            if ($goods_attr['Offers']['Offer']['OfferListing']['Price']['CurrencyCode'] == 'JPY') {
                $rate = TrdHaitaoCurrencyExchangeTable::getRate('jpy');
                $result[$m]['price'] = ceil($new_attr['price'] * $rate * 100) / 100;
            } elseif($goods_attr['Offers']['Offer']['OfferListing']['Price']['CurrencyCode'] == 'USD') {
                $rate = TrdHaitaoCurrencyExchangeTable::getRate();
                $result[$m]['price'] = ceil($new_attr['price'] * $rate) / 100;
            } else {
                $result[$m]['price'] = $new_attr['price'];
            }
            $result[$m]['attr'] = $new_attr;
            $result[$m]['product_id'] = $productId;

            $result[$m]['title'] = $productArr[$productId]->getTitle();
            $result[$m]['freight'] = $productArr[$productId]->getFreight();
            $result[$m]['weight'] = $productArr[$productId]->getWeight();
            $result[$m]['business_weight'] = $productArr[$productId]->getBusinessWeight();
            $result[$m]['business'] = $productArr[$productId]->getBusiness();
            if ($result[$m]['weight'] < 0 && $result[$m]['business_weight'] < 0 && $result[$m]['business'] != TrdProductAttrTable::$zhifa_shihuo_business) {
                return $this->error(405, '有商品运费有误，请联系客服');
                break;
            }

            if (!isset($productArr[$productId])) {
                unset($result[$m]);
                continue;
            }

            //营销活动
            if ($type == 1){
                $act_goods_info[$result[$m]['goods_id']]['product_id'] = $productId;
                $act_goods_info[$result[$m]['goods_id']]['price'] = $result[$m]['price']*$result[$m]['number'];
                $act_goods_info[$result[$m]['goods_id']]['goods_id'] = $result[$m]['goods_id'];
                $act_goods_info[$result[$m]['goods_id']]['merchant'] = $goods_attr['Offers']['Offer']['Merchant']['Name'];
            }

            //记录6pm的商品
            if ($productArr[$productId]->getBusiness() == '6pm'){
                $usa_pm[] = array(
                    'goods_id' => $result[$m]['goods_id'],
                    'price' => $result[$m]['price']*$result[$m]['number'],
                    'exchange'=> $goods_attr['Offers']['Offer']['OfferListing']['Price']['Amount'] * $result[$m]['number'] / 100,
                );
            }
        }
        if (count($result) != count($data_array)) {
            return $this->error(405, '选择的商品有异常情况，请返回购物车重新选择');
        }
        $order_sn = date('ymd') . substr(time(), -5) . substr(microtime(), 2, 5);
        $orderHisObj = TrdOrderTable::getInstance()->createQuery()->select('id')->where('order_number = ?', $order_sn)->execute();
        if ($orderHisObj) {
            $order_sn = date('ymd') . substr(time(), -5) . substr(microtime(), 2, 5);
        }

        $region = TrdUserDeliveryAddressTable::getInstance()->find($region_id);
        $address = $region->getName() . ' ' . $region->getRegion() . $region->getStreet() . '（邮编：' . $region->getPostcode() . '）' . ' ';
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

        //准备下单
        $time = date('Y-m-d H:i:s');
        $total_price = 0;
        $total_number = 0;
        $coupon_fee = 0;
        $total_product_price = 0;
        $total_product_freight = 0;
        $total_usa_freight = 0;//usa运费
        $total_jp_freight = 0;//jp运费
        $total_hk_freight = 0;//hk运费
        $total_hkebay_freight = 0;//hk运费
        $cartNumber = count($result);
        $usa_pm_freight = 0;//美国运费
        foreach ($result as $k => $v) {
            $weight = $v['weight'] ? $v['weight'] : $v['business_weight'];
            if ($cartNumber == 1 && $v['number'] == 1) {
                if ($v['business'] == '日本亚马逊') {
                    $weight_area['jp']['weight'] += ($weight) * $v['number'];
                    $weight_area['jp']['number'] += $v['number'];
                } elseif ($v['business'] == TrdProductAttrTable::$zhifa_business) {
                    $weight_area['hk']['weight'] += ($weight) * $v['number'];
                    $weight_area['hk']['number'] += $v['number'];
                } elseif ($v['business'] == TrdProductAttrTable::$zhifa_ebay_business) {
                    $weight_area['hkebay']['weight'] += ($weight) * $v['number'];
                    $weight_area['hkebay']['number'] += $v['number'];
                } elseif($v['business'] != TrdProductAttrTable::$zhifa_shihuo_business) {
                    $weight_area['usa']['weight'] += ($weight) * $v['number'];
                    $weight_area['usa']['number'] += $v['number'];

                    //存储6pm商品的重量
                    if ($v['business'] == '6pm'){
                        $usa_pm_weight[] = array(
                            'goods_id' => $v['goods_id'],
                            'number' => $v['number'],
                            'weight' => $weight,
                        );
                    }
                }
            } else {
                if ($v['business'] == '日本亚马逊') {
                    $weight_area['jp']['weight'] += $weight < 0.5 ? 0.5 * $v['number'] : ($weight) * $v['number'];//总重量
                    $weight_area['jp']['number'] += $v['number'];
                } elseif ($v['business'] == TrdProductAttrTable::$zhifa_business) {
                    $weight_area['hk']['weight'] += ($weight) * $v['number'];
                    $weight_area['hk']['number'] += $v['number'];
                } elseif ($v['business'] == TrdProductAttrTable::$zhifa_ebay_business) {
                    $weight_area['hkebay']['weight'] += ($weight) * $v['number'];
                    $weight_area['hkebay']['number'] += $v['number'];
                } elseif($v['business'] != TrdProductAttrTable::$zhifa_shihuo_business) {
                    $weight_area['usa']['weight'] += $weight < 0.5 ? 0.5 * $v['number'] : ($weight) * $v['number'];//总重量
                    $weight_area['usa']['number'] += $v['number'];

                    //存储6pm商品的重量
                    if ($v['business'] == '6pm'){
                        $usa_pm_weight[] = array(
                            'goods_id' => $v['goods_id'],
                            'number' => $v['number'],
                            'weight' => $weight < 0.5 ? 0.5 : $weight,
                        );
                    }
                }
            }
            $total_product_price += $v['price'] * $v['number'];
            $total_number += $v['number'];
        }
        foreach ($weight_area as $w => $a) {
            if ($w == 'jp') {
                $jp_freight = $a['weight'] ? $this->getAllFreight($a['weight'], $a['number'], false) : 0;
                $total_jp_freight += $jp_freight;
                $total_product_freight += $jp_freight;
            } elseif ($w == 'hk') {
                $hk_freight = $a['weight'] ? $this->getAllFreight($a['weight'], $a['number'], false) : 0;
                $total_hk_freight += $hk_freight;
                $total_product_freight += $hk_freight;
            } elseif ($w == 'hkebay') {
                $hkebay_freight = $a['weight'] ? $this->getAllFreight($a['weight'], $a['number'], false) : 0;
                $total_hkebay_freight += $hkebay_freight;
                $total_product_freight += $hkebay_freight;
            } else {
                $usa_freight = $a['weight'] ? $this->getAllFreight($a['weight'], $a['number'], false) : 0;
                $total_usa_freight += $usa_freight;
                $total_product_freight += $usa_freight;
            }
        }
        $freight_per_usa = $total_usa_freight ? floor(($total_usa_freight / $weight_area['usa']['weight']) * 100) / 100 : 0 ;
        $freight_per_jp = $total_jp_freight ? floor(($total_jp_freight / $weight_area['jp']['weight']) * 100) / 100 : 0 ;
        $freight_per_hk = $total_hk_freight ? floor(($total_hk_freight / $weight_area['hk']['weight']) * 100) / 100 : 0 ;
        $freight_per_hkebay = $total_hkebay_freight ? floor(($total_hkebay_freight / $weight_area['hkebay']['weight']) * 100) / 100 : 0 ;
        $original_price = $total_price = $total_product_price + $total_product_freight;

        $freight_per_usa_pm = 0;//6pm每磅的钱

        $activity_save = 0;//识货营销活动节省钱

        $stock_change_array = array();//记录库存需要变更的数组
        //获取Doctrine链接
        $connection_trade = sfContext::getInstance()->getDatabaseManager()->getDatabase('trade')->getDoctrineConnection();
        try {
            $connection_trade->beginTransaction();

            //营销活动
            if ($type == 1) {
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
                        return $this->error(406, '订单金额必须大于0元');
                        $total_price = 0;
                        $activity_save = $total_product_price + $total_product_freight;
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

            //判断6pm运费
            if(!empty($usa_pm)){
                $rate = TrdHaitaoCurrencyExchangeTable::getRate();
                $total_exchange = 0;
                foreach ($usa_pm as $k=>$v){
                    $total_exchange += $v['exchange'];
                    if(!empty($act_data['data']['data']['goods_info'][$v['goods_id']]) && !empty($act_data['data']['data']['goods_info'][$v['goods_id']]['marketing_fee'])){
                        $activity_save_usa = ceil($act_data['data']['data']['goods_info'][$v['goods_id']]['marketing_fee'] * 100 / $rate) / 100;
                        $total_exchange -= $activity_save_usa;
                    }
                }
                if ($total_exchange < 50){
                    $usa_pm_freight = ceil($this->USA_FIXED_FREIGHT * $rate * 100) / 100;
                    $total_price += $usa_pm_freight;
                    $original_price += $usa_pm_freight;
                    $total_product_freight += $usa_pm_freight;
                    $total_usa_pm_weight = 0;

                    foreach ($usa_pm_weight as $uk=>$uv){
                        $total_usa_pm_weight += $uv['weight'] * $uv['number'];
                    }

                    $freight_per_usa_pm = ceil(($usa_pm_freight / $total_usa_pm_weight) * 100) / 100 ;
                }
            }

            //验证礼品卡
            if ($coupon_id) {
                $serviceRequest = new tradeServiceClient();
                $serviceRequest->setMethod('lipinka.u.check');
                $serviceRequest->setVersion('1.0');
                $serviceRequest->setApiParam('user_id', $hupuUid);
                $serviceRequest->setApiParam('id', $coupon_id);
                $response = $serviceRequest->execute();
                if (!$response->hasError()) {
                    $data = $response->getData();
                    $coupon_fee = $data['data']['amount'];
                    $total_price -= $coupon_fee;
                    if ($total_price < 0) {
                        $total_price = 0;
                        $coupon_fee = $total_product_price + $total_product_freight;
                    }
                } else {
                    throw new Exception('礼品卡无法使用，请再试一次', 405);
                }
            }

            $freight_sum_usa = 0;
            $freight_sum_jp = 0;
            $freight_sum_hk = 0;
            $freight_sum_hkebay = 0;
            $freight_sum_usa_pm = 0;
            $count_usa = 0;
            $count_jp = 0;
            $count_hk = 0;
            $count_hkebay = 0;
            foreach ($result as $k => $v){
                $pre_marketing_fee = 0;
                $total_marketing_fee = 0;
                $marketing_fee = 0;
                //优惠金额
                if ($type == 1 && $activity_save > 0 && isset($goods_info[$v['goods_id']]) && isset($goods_info[$v['goods_id']]['marketing_fee'])){
                    $pre_marketing_fee = ceil($goods_info[$v['goods_id']]['marketing_fee'] * 100 / $v['number']) / 100;
                }

                for ($i = 0; $i < $v['number']; $i++) {
                    $orderObj = new TrdOrder();
                    $orderObj->setOrderNumber($order_sn);
                    $orderObj->setTitle($v['title']);
                    $orderObj->setProductId($v['product_id']);
                    $orderObj->setBusiness($v['business']);
                    $orderObj->setHupuUid($hupuUid);
                    $orderObj->setHupuUsername($hupuUname);
                    //商品id
                    $goods_prefix = $this->getGoodsIdPrefix($v['business']);
                    $goodsId = $goods_prefix . $v['attr']['name'];
                    //需要判断库存
                    if (substr($goodsId, 0, 2) == 'cn'){
                        $stockUpdate = TrdHaitaoGoodsTable::getInstance()
                            ->createQuery()
                            ->update()
                            ->where('id = ?', $v['goods_id'])
                            ->andWhere('total_num > ?',0)
                            ->andWhere('status = ?', 0)
                            ->set('total_num', 'total_num - 1')
                            ->set('lock_num', 'lock_num + 1')
                            ->execute();
                        if ($stockUpdate == 1){//库存足 需要发送库存变更通知
                            $stock_change_array[$v['product_id']] = $v['product_id'];
                        } else {//库存不足 抛出错误 回滚
                            throw new Exception('商品库存不足，请返回购物车修改', 407);
                        }
                    }
                    $orderObj->setGoodsId($goodsId);
                    $orderObj->setGid($v['goods_id']);
                    $orderObj->setAttr(json_encode($v['attr']));
                    $orderObj->setPrice($v['price']);
                    $weight = $v['weight'] ? $v['weight'] : $v['business_weight'];
                    $weight = $weight < 0.5 ? 0.5 : $weight;
                    $freight = 0;
                    if ($v['business'] == '日本亚马逊') {
                        if ($count_jp == $weight_area['jp']['number'] - 1) {
                            $freight = $total_jp_freight - $freight_sum_jp;
                        } else {
                            $freight = floor($freight_per_jp * $weight * 100) / 100;
                        }
                        $count_jp++;
                        $freight_sum_jp += $freight;
                    } elseif ($v['business'] == TrdProductAttrTable::$zhifa_business) {
                        if ($count_hk == $weight_area['hk']['number'] - 1) {
                            $freight = $total_hk_freight - $freight_sum_hk;
                        } else {
                            $freight = floor($freight_per_hk * $weight * 100) / 100;
                        }
                        $count_hk++;
                        $freight_sum_hk += $freight;
                    } elseif ($v['business'] == TrdProductAttrTable::$zhifa_ebay_business) {
                        if ($count_hkebay == $weight_area['hkebay']['number'] - 1) {
                            $freight = $total_hkebay_freight - $freight_sum_hkebay;
                        } else {
                            $freight = floor($freight_per_hkebay * $weight * 100) / 100;
                        }
                        $count_hkebay++;
                        $freight_sum_hkebay += $freight;
                    } elseif($v['business'] != TrdProductAttrTable::$zhifa_shihuo_business) {
                        if ($count_usa == $weight_area['usa']['number'] - 1) {
                            $freight = $total_usa_freight - $freight_sum_usa;
                        } else {
                            $freight = floor($freight_per_usa * $weight * 100) / 100;
                        }
                        $count_usa++;
                        $freight_sum_usa += $freight;

                        //计算每个子订单额外的美国本土的6pm运费
                        if ($v['business'] == '6pm' && $usa_pm_freight > 0) {
                            $freight_pm = ceil($freight_per_usa_pm * $weight * 100) / 100;
                            if ($freight_sum_usa_pm + $freight_pm > $usa_pm_freight) {
                                $freight_pm = $usa_pm_freight - $freight_sum_usa_pm;
                            }
                            $freight_sum_usa_pm += $freight_pm;
                            $freight += $freight_pm;
                        }
                    }
                    $orderObj->setExpressFee($freight);
                    $orderObj->setTotalPrice($v['price'] + $freight);
                    $orderObj->setOrderTime($time);
                    if ($v['business'] == TrdProductAttrTable::$zhifa_shihuo_business){//识货自己发 写入发货方式
                        $orderObj->setDeliveryType(5);
                        $orderObj->setDomesticExpressType(3);//仓库默认发圆通
                    } elseif ($v['business'] == TrdProductAttrTable::$zhifa_ebay_business){//ebay
                        $orderObj->setDeliveryType(6);
                    }

                    //保存优惠金额
                    if ($pre_marketing_fee > 0) {
                        if ($total_marketing_fee + $pre_marketing_fee >= $goods_info[$v['goods_id']]['marketing_fee']) {
                            $marketing_fee = $goods_info[$v['goods_id']]['marketing_fee'] - $total_marketing_fee;
                        } else {
                            $marketing_fee = $pre_marketing_fee;
                            $total_marketing_fee += $pre_marketing_fee;
                        }
                    }
                    $orderObj->setMarketingFee($marketing_fee);

                    $orderObj->setSource($v['source']);
                    if (in_array($v['source'], array(3, 4))) {
                        !empty($channel) && $orderObj->setChannel($channel);
                    }
                    $orderObj->save();
                }
            }

            $connection_trade->commit();
        } catch (Exception $e) {//事物回滚
            $connection_trade->rollBack();
            return $this->error($e->getCode(), $e->getMessage());
        }

        //插入主表 trd_main_order
        $mainOrderObj = new TrdMainOrder();
        $mainOrderObj->setOrderNumber($order_sn);
        $mainOrderObj->setHupuUid($hupuUid);
        $mainOrderObj->setHupuUsername($hupuUname);
        $mainOrderObj->setAddress($address);
        $mainOrderObj->setAddressAttr(json_encode($address_arr));
        $mainOrderObj->setExpressFee($total_product_freight);
        $mainOrderObj->setTotalPrice($total_price);
        $mainOrderObj->setOriginalPrice($original_price);
        $mainOrderObj->setCouponFee($coupon_fee);
        $mainOrderObj->setMarketingFee($activity_save);
        $mainOrderObj->setNumber($total_number);
        $mainOrderObj->setRemark($remark);
        $mainOrderObj->setOrderTime($time);
        $mainOrderObj->setSource($sourceChannel);
        $mainOrderObj->save();

        //使用礼品卡
        if ($coupon_id) {
            $serviceRequest = new tradeServiceClient();
            $serviceRequest->setMethod('lipinka.use');
            $serviceRequest->setVersion('1.0');
            $serviceRequest->setApiParam('user_id', $hupuUid);
            $serviceRequest->setApiParam('id', $coupon_id);
            $response = $serviceRequest->execute();
            if (!$response->hasError()) {
                $data = $response->getData();
                $coupon_fee = $data['data']['amount'];

                //插入礼品卡使用记录
                $activityDetailObi = new TrdOrderActivityDetail();
                $activityDetailObi->set('order_number', $order_sn);
                $activityDetailObi->set('activity_id', $coupon_id);
                $activityDetai_attr = array('code' => $data['data']['account'], 'price' => $coupon_fee);
                $activityDetailObi->set('attr', json_encode($activityDetai_attr));
                $activityDetailObi->save();
            }
        }

        //下单日志
        $history = new TrdHaitaoOrderHistory();
        $history->setOrderNumber($order_sn);
        $history->setHupuUid($hupuUid);
        $history->setHupuUsername($hupuUname);
        $history->setType(50);
        $exp = '购物车购买了' . count($result) . '样商品,共' . $total_number . '件';
        $history->setExplanation($exp);
        $history->save();

        //清除购物车
        TrdShoppingCartTable::getInstance()->createQuery('m')
            ->delete()
            ->where('m.hupu_uid = ?', $hupuUid)
            ->whereIn('m.goods_id', $data_array)
            ->execute();
        $count = TrdShoppingCartTable::getInstance()->createQuery()->select()->where('hupu_uid = ?', $hupuUid)->count();
        //更新用户redis购物车数量
        $redis = sfContext::getInstance()->getDatabaseConnection('kaluliRedis');
        $redis->set('trade_shopping_cart_' . $hupuUid, $count, 3600 * 24 * 180);

        //cps推广
        if (isset($sh_union) && !empty($sh_union)) {
            $message = array('order_number'=>$order_sn, 'cookie'=>$sh_union, 'type'=>'create');
            tradeCommon::sendMqMessage('order.detail', $message, 'order_detail_deferred');
        }

        //库存变更 发送消息
        if (!empty($stock_change_array)){
            foreach ($stock_change_array as $v){
                $message = array('product_id'=>$v);
                tradeCommon::sendMqMessage('product.stock',$message,'product_stock_deferred');
            }
        }

        return $this->success(array('status' => 0, 'data' => array('order_num' => $order_sn), 'msg' => 'ok'));
    }

    /**
     * 获取大家都在买
     * @return array
     */
    public function executeMostPurchase()
    {
        $version = $this->getRequest()->getParameter('version', '');
        $redis = sfContext::getInstance()->getDatabaseConnection('kaluliRedis');
        $key = "shihuo_haitao_most_purchase";
        $goodsObj = unserialize($redis->get($key));
        if(!$goodsObj){
            $time = time();
            $goodsObj = TrdOrderTable::getInstance()->createQuery('m')
                ->select('product_id, gid, attr, price, title, count(*) Total')
                ->groupBy('product_id')
                ->orderBy('Total desc')
                ->where('product_id is not null and product_id !=""')
                ->andwhere('order_time >= ?', date('Y-m-d H:i:s', $time - 86400*10))
                ->andWhere('order_time < ?', date('Y-m-d H:i:s', $time))
                ->limit(6)
                ->fetchArray();
            if ($goodsObj) {
                foreach ($goodsObj as $k => &$v) {
                    $attr = json_decode($v['attr'], true);
                    $v['img'] = tradeCommon::getQiNiuProxyPath($attr['img']) . '?imageView2/1/w/215/h/215';
                    if ($v['gid']) {
                        $v['url'] = 'http://m.shihuo.cn/daigou/' . $v['product_id'] . '-' . $v['gid'] . '.html';
                    } else {
                        $v['url'] = 'http://m.shihuo.cn/daigou/' . $v['product_id'] . '.html';
                    }
                    unset($v['attr']);
                    unset($v['Total']);
                }
                $redis->set($key,serialize($goodsObj),1200);
            }
        }
        return $this->success(array('status' => 0, 'data' => $goodsObj, 'msg' => 'ok'));
    }

    /**
     * 封装购物车相关数据
     * @param $cartObj
     * @param $goods_ids
     * @param $product_ids
     * @param $goodsArr
     * @param $productArr
     */
    private function getCartData($cartObj, &$goods_ids, &$product_ids, &$goodsArr, &$productArr)
    {
        foreach ($cartObj as $k => $v) {
            array_push($goods_ids, $v->getGoodsId());
        }

        //获取购物车商品信息
        $goodsObj = TrdHaitaoGoodsTable::getInstance()->createQuery('m')
            ->select()
            ->whereIn('m.id', $goods_ids)
            ->execute();

        if (count($goodsObj) > 0) {
            foreach ($goodsObj as $k => $v) {
                array_push($product_ids, $v->getProductId());
                $goodsArr[$v->getId()] = $v;
            }
        }

        if ($product_ids) {
            $productObj = TrdProductAttrTable::getInstance()->createQuery('m')
                ->select()
                ->whereIn('m.id', $product_ids)
                ->execute();
            if (count($productObj) > 0) {
                foreach ($productObj as $k => $v) {
                    $productArr[$v->getId()] = $v;
                }
            }
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
            if ($freight < 46) $freight = 46;
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
        if ($res < 46) $res = 46;
        return $res;
    }

    /**
     * 获取最新汇率
     */
    private function getLatestRate()
    {
        $rateInfo = TrdHaitaoCurrencyExchangeTable::getInstance()->createQuery()->select('exchange_rate')->where('currency_from = ?', 0)->andWhere('currency_to = ?', 1)->limit(1)->fetchOne();
        if ($rateInfo) {
            $rate = $rateInfo->getExchangeRate();
        } else {
            $rate = 6.3;
        }
        return $rate;
    }

    /**
     * 获取goods_id 前缀
     *
     */
    private function getGoodsIdPrefix($business)
    {
        if ($business == '美国亚马逊') {
            return 'usa.amazon.';
        }
        if ($business == '6pm') {
            return 'usa.6pm.';
        }
        if ($business == 'gnc') {
            return 'usa.gnc.';
        }
        if ($business == 'levis') {
            return 'usa.levis.';
        }
        if ($business == 'nbastore') {
            return 'usa.nbastore.';
        }
        if ($business == '日本亚马逊') {
            return 'jp.amazon.';
        }
        if ($business == TrdProductAttrTable::$zhifa_business) {
            return 'cn.hk.';
        }
        if ($business == TrdProductAttrTable::$zhifa_shihuo_business) {
            return 'cn.sh.';
        }
        if ($business == TrdProductAttrTable::$zhifa_ebay_business) {
            return 'cn.hkebay.';
        }
    }
}
