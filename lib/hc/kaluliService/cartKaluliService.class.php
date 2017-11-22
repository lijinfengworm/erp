<?php

/**
 * 购物车服务类
 * version:1.0
 */
class cartKaluliService extends kaluliService
{

    //支付类型
    private $pay_type = array(
        1 => 'alipay',
        2 => 'weixinpay',
        'default' => 1
    );

    private $cart_type = array(
        'normal' => 0,
        'haitao' => 1,
        'benniao' => 2,
        'ningbo' => 4

    );

    private static $wareHoseName = array(
        0 => '自营普通仓',
        1 => '南沙保税仓',
        4 => '宁波保税仓'
    );

    private static $duty = 0.4;

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
        $redis->select(1);
        $cartObj = KaluliShoppingCartTable::getInstance()->createQuery()->select()->where('hupu_uid = ?', $hupuUid)->andWhere('goods_id = ?', $goods_id)->fetchOne();

        //判断主商品
        $serviceRequest = new kaluliServiceClient();
        $serviceRequest->setMethod('item.itemGet');
        $serviceRequest->setVersion('1.0');
        $serviceRequest->setApiParam('id', $product_id);
        $response = $serviceRequest->execute();
        if ($response->hasError()) {
            return $this->error(400, '非法的参数');
        }
        $main_data = $response->getData();

        //判断子商品
        $serviceRequest->setMethod('item.itemSkuGet');
        $serviceRequest->setVersion('1.0');
        $serviceRequest->setApiParam('id', $goods_id);
        $response = $serviceRequest->execute();

        if ($response->hasError()) {
            return $this->error(400, '非法的参数');
        }
        $goods_data = $response->getData();

        $limit = $goods_data['data']['sku']['total_num'];

        if ($cartObj && $cartObj->getNumber() + $number > $limit) {//超过库存
            return $this->error(401, '超过库存');
        } elseif ($number > $limit) {
            return $this->error(401, '超过库存');
        }


        if ($cartObj) {
            $cartObj->set('number', $cartObj->getNumber() + $number);
        } else {
            $cartObj = new KaluliShoppingCart();
            $cartObj->set('number', $number);
            $cartObj->set('hupu_uid', $hupuUid);
            $cartObj->set('hupu_username', $hupuUname);
            $cartObj->set('product_id', $product_id);
            $cartObj->set('goods_id', $goods_id);
            $cartObj->set('source', $source);
            //根据仓库不同存入购物车属性不同
            if ($goods_data['data']['sku']['storehouse_id'] == 10) { //南沙仓存入购物车属性变为海淘
                $cartObj->set('type', $this->cart_type['haitao']);
            }
            //宁波保税仓
            if ($goods_data['data']['sku']['storehouse_id'] == 19) {
                $cartObj->set('type', $this->cart_type['ningbo']);
            }

        }
        $cartObj->save();
        $count = KaluliShoppingCartTable::getInstance()->createQuery()->select()->where('hupu_uid = ?', $hupuUid)->count();
        $redis->set('kaluli_shopping_cart_' . $hupuUid, $count, 3600 * 24 * 180);
        return $this->success(array('count' => $count, 'img_path' => $main_data['data']['item']['pic'] . '?imageView2/1/w/40/h/40'));
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
        $redis->select(1);
        KaluliShoppingCartTable::getInstance()->createQuery('m')
            ->delete()
            ->where('m.hupu_uid = ?', $hupuUid)
            ->whereIn('m.goods_id', $goods_id)
            ->execute();
        $count = KaluliShoppingCartTable::getInstance()->createQuery()->select()->where('hupu_uid = ?', $hupuUid)->count();
        $redis->set('kaluli_shopping_cart_' . $hupuUid, $count, 3600 * 24 * 180);
        return $this->success(array('count' => $count));
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
        $type = $this->getRequest()->getParameter('type'); //type 1 增加 0 减少
        $activity_rate = $discount_rate = 1;
        $activity_type = 0;
        if (empty($hupuUid) || !is_numeric($hupuUid)) {
            return $this->error(501, '未登录');
        }

        if (!$goods_id) {
            return $this->error(400, '参数错误');
        }

        $cartObj = KaluliShoppingCartTable::getInstance()->createQuery()->select()->where('goods_id = ?', $goods_id)->andWhere('hupu_uid = ?', $hupuUid)->fetchOne();
        if (!$cartObj) {
            return $this->error(404, '购物车不存在该商品');
        }
        //商品实付价
        $redis = sfContext::getInstance()->getDatabaseConnection('kaluliRedis');
        $redis->select(1);
        $product_id_activity = $cartObj->getProductId();

        $activity = $redis->get('kaluli_marketing_activity_' . $product_id_activity);

        $activity_discount = unserialize($activity);
        if (!empty($activity_discount) && isset($activity_discount['detail']['discount_rate'])) {

            if (intval($activity_discount['detail']['mode']) == 2 && isset($activity_discount['data'][0]['attr1'])) {
                $activity_rate = intval($activity_discount['data'][0]['attr1']);
                $discount_rate = $activity_discount['detail']['discount_rate'];
                $activity_type = 2;
            }
            if (intval($activity_discount['detail']['mode']) == 3) {
                $discount_rate = $activity_discount['detail']['discount_rate'];
                $activity_type = 3;
            }
        }


        //判断子商品
        $serviceRequest = new kaluliServiceClient();
        $serviceRequest->setMethod('item.itemSkuGet');
        $serviceRequest->setVersion('1.0');
        $serviceRequest->setApiParam('id', $goods_id);
        $response = $serviceRequest->execute();

        if ($response->hasError()) {
            return $this->error(400, '非法的参数');
        }


        $goods_data = $response->getData();
        $limit = $goods_data['data']['sku']['total_num'];
        if ($type) {//增加
            if ($cartObj->getNumber() >= $limit) {
                return $this->error(402, '超过库存');
            }
            $cartObj->set('number', $cartObj->getNumber() + 1);
        } else {
            if ($cartObj->getNumber() == 1) {
                return $this->error(403, '只有一个不能再减少了');
            }
            $cartObj->set('number', $cartObj->getNumber() - 1);
        }
        $cartObj->save();

        $price = $goods_data['data']['sku']['discount_price'];
        $number = $cartObj->getNumber();
        $activity_flag = 0;

        if ($number >= $activity_rate) {
            $activity_flag = 1;
        }

        $total_price = $price * $number;
        $total_duty_fee = $this->getDutyFee($goods_data['data']['sku']['storehouse_id'], $total_price);
        return $this->success(array('total_price' => $total_price, 'price' => $price, 'number' => $number, 'dutyFee' => $total_duty_fee, 'discount_rate' => $discount_rate, 'activity_flag' => $activity_flag, 'activity_type' => $activity_type));
    }

    /**
     * 获取购物车商品数据
     * @return array
     */
    public function executeList()
    {
        $version = $this->getRequest()->getParameter('version', '');
        $hupuUid = $this->getUser()->getAttribute('uid');
        $goodsIds = $this->getRequest()->getParameter('gid');
        $region_id = $this->getRequest()->getParameter('address_id');//地址
        $express_type = $this->getRequest()->getParameter('express_type', KaluliOrder::$_DEFAULT_EXPRESS_TYPE);//快递类型 2顺丰 4圆通
        $type = $this->getRequest()->getParameter('type');//存在表示需要活动
        $sort = $this->getRequest()->getParameter('sort');//sort 商品聚合 不存在是营销活动 存在是仓库
        $cart_type = $this->getRequest()->getParameter('cart_type', 3);//区分获取的购物车list属性，0为普通货物，1为南沙仓货物，2为笨鸟仓货物,3为所有,4为宁波保税仓
        $card_type = $this->getRequest()->getParameter("card_type", "");
        $card_id = $this->getRequest()->getParameter("card_id", "");

        $expressTypes = $this->getRequest()->getParameter("expressTypes", ""); //数组形式 key=仓库id,val=快递id

        if (empty($hupuUid) || !is_numeric($hupuUid)) {
            return $this->error(501, '未登录');
        }
        //初始化数据
        $total_count = 0;
        $total_price = 0;
        $total_number = 0;
        $total_weight = 0;
        $total_duty_fee = 0;
        $total_product_price = 0;

        //是否海淘
        $is_ht = false;
        //获取购物车数据
        //设置了区分list属性
        if ($cart_type == 1 || $cart_type == 0 || $cart_type == 2 || $cart_type == 4) {
            $cartObj = KaluliShoppingCartTable::getInstance()->createQuery('m')->select()->where('m.hupu_uid = ?', $hupuUid)->andWhere('m.type = ?', $cart_type);
        } else {
            $cartObj = KaluliShoppingCartTable::getInstance()->createQuery('m')->select()->where('m.hupu_uid = ?', $hupuUid);
        }


        if ($goodsIds) $cartObj = $cartObj->whereIn('m.goods_id', $goodsIds);
        $cartObj = $cartObj->orderBy('created_at desc')->execute();
        if (count($cartObj) == 0) {
            return $this->success(array('result' => array(), 'total_data' => array()));
        }

        //购物车商品ids 初始化商品信息 $goodsArr子商品详细信息 $productArr主商品详细信息
        $goods_ids = $product_ids = $goodsArr = $productArr = $marketing_list = array();

        $this->getCartData($cartObj, $goods_ids, $product_ids, $goodsArr, $productArr);
        //如果没有商品 那么就要清空购物车
        if (empty($goodsArr)) {
            foreach ($cartObj as $k => $v) {
                //删除购物车
                $serviceRequest = new kaluliServiceClient();
                $serviceRequest->setMethod('cart.delete');
                $serviceRequest->setVersion('1.0');
                $serviceRequest->setApiParam('goods_id', $v->getGoodsId());
                $serviceRequest->setUserToken(sfContext::getInstance()->getRequest()->getCookie('u'));
                $response = $serviceRequest->execute();

            }
            return $this->success(array('result' => array(), 'total_data' => 0));
        }

        $result = $valid_ids = $lose_efficacy_list = array();
        $is_identify = 0; //身份证校验参数
        foreach ($cartObj as $m => $n) {

            $result[$m]['id'] = $n->getId();
            $result[$m]['insert_time'] = strtotime($n->getUpdatedAt());
            $result[$m]['warehouse_type'] = $cart_type;
            $result[$m]['number'] = $n->getNumber();
            $result[$m]['goods_id'] = $n->getGoodsId();
            $result[$m]['product_id'] = $n->getProductId();

            $result[$m]['price'] = $goodsArr[$n->getGoodsId()]['discount_price'];
            $result[$m]['total_price'] = $result[$m]['price'] * $result[$m]['number'];
            $result[$m]['img_path'] = $productArr[$n->getProductId()]['pic'] . '?imageView2/1/w/100/h/100';
            if (!empty($productArr[$n->getProductId()]['status'])) {
                $result[$m]['good_status'] = $productArr[$n->getProductId()]['status'];
            } else {
                //下架状态
                $result[$m]['good_status'] = 4;
            }

            $result[$m]['total_num'] = $goodsArr[$n->getGoodsId()]['total_num'];
            $result[$m]['title'] = $productArr[$n->getProductId()]['title'];
            $result[$m]['storehouse_id'] = $goodsArr[$n->getGoodsId()]['storehouse_id'];
            $result[$m]['weight'] = $goodsArr[$n->getGoodsId()]['weight'];
            $result[$m]['attr'] = $goodsArr[$n->getGoodsId()]['attr'] ? unserialize($goodsArr[$n->getGoodsId()]['attr']) : '';
            if ($goodsArr[$n->getGoodsId()]['storehouse_id'] == 10 || $goodsArr[$n->getGoodsId()]['storehouse_id'] == 20 || $goodsArr[$n->getGoodsId()]['storehouse_id'] == 16 || $goodsArr[$n->getGoodsId()]['storehouse_id'] == 5) {
                $is_identify = 1;
            } elseif ($goodsArr[$n->getGoodsId()]['storehouse_id'] == 19) {
                $is_identify = 2;
            }

            //税费统计 by 李斌
            $result[$m]['dutyFee'] = $this->getDutyFee($goodsArr[$n->getGoodsId()]['storehouse_id'], $result[$m]['total_price']);
            $total_duty_fee += $result[$m]['dutyFee'];
            //是否已售罄
            if ($goodsArr[$n->getGoodsId()]['status'] == 1 || $productArr[$n->getProductId()]['status'] == 4) {
                $result[$m]['status'] = 1;//已下架
            } elseif ($result[$m]['total_num'] == 0) {
                $result[$m]['status'] = 2;//已售罄
            } else {
                $result[$m]['status'] = 0;//正常
            }

            //是否超过库存
            if ($result[$m]['total_num'] < $result[$m]['number']) {
                $result[$m]['stock_status'] = 1;//超过了库存

            } else {
                $result[$m]['stock_status'] = 0;//正常
            }

            /* 仓库信息  梁天 */
            $result[$m]['ware'] = KaluliWarehousesTable::getOneWareById($goodsArr[$n->getGoodsId()]['storehouse_id']);

            if ($result[$m]['status'] == 0) {
                array_push($valid_ids, $n->getGoodsId());
//                if ($goodsArr[$n->getGoodsId()]['storehouse_id'] == 1) {//识货仓库才要计算运费
//                    $weight = $goodsArr[$n->getGoodsId()]['weight'];
//                    $total_weight += $weight * $n->getNumber();
//                }
                $total_number += $n->getNumber();
                $total_count++;
                $total_price += $result[$m]['total_price'];
                $total_product_price += $result[$m]['total_price'];

                //营销活动
                $marketing_list[$n->getGoodsId()]['product_id'] = $n->getProductId();
                $marketing_list[$n->getGoodsId()]['goods_id'] = $n->getGoodsId();
                $marketing_list[$n->getGoodsId()]['number'] = $n->getNumber();
                $marketing_list[$n->getGoodsId()]['price'] = $result[$m]['total_price'];
            }
        }

        $province_id = null;
        if ($region_id) {
            $address = TrdUserDeliveryAddressTable::getInstance()->find($region_id);
            if ($address) {
                $province_id = $address->get('province');
            }
        }


        $total_data = array(
            'total_count' => $total_count,
            'total_price' => $total_price,
            'original_price' => $total_product_price,
            'total_product_price' => $total_product_price,
            'total_weight' => $total_weight,
            'activity' => array(),
            'is_identify' => $is_identify,
            'total_duty_fee' => $total_duty_fee,
            'total_original_price' => $total_product_price
        );

        //如果需要活动
        if ($type) {
            if ($card_type != 2) {
                $serviceRequest = new kaluliServiceClient();
                $serviceRequest->setMethod('marketing.getActivity');
                $serviceRequest->setVersion('1.0');
                $serviceRequest->setApiParam('marketing_list', $marketing_list);
                if (empty($goodsIds)) $serviceRequest->setApiParam('type', 1);
                $response = $serviceRequest->execute();

                if (!$response->hasError()) {
                    $marketing_data = $response->getData();
                    $total_data['activity'] = $marketing_data['data']['data'];
                    $goods_info = $marketing_data['data']['data']['goods_info'];

                    $total_data['total_price'] -= $marketing_data['data']['data']['activity_save'];
                    $total_data['total_product_price'] -= $marketing_data['data']['data']['activity_save'];
                }
            } else {
                $serviceRequest = new kaluliServiceClient();
                $serviceRequest->setMethod('benefits.get.activity');
                $serviceRequest->setVersion('1.0');
                $serviceRequest->setApiParam('goods', $marketing_list);
                $serviceRequest->setApiParam('card_id', $card_id);

                if (empty($goodsIds)) $serviceRequest->setApiParam('type', 1);
                $response = $serviceRequest->execute();

                if (!$response->hasError()) {
                    $marketing_data = $response->getData();
                    $total_data['activity'] = $marketing_data['data']['data'];
                    $goods_info = $marketing_data['data']['data']['goods_info'];
                    $total_data['total_price'] -= $marketing_data['data']['data']['activity_save'];
                    $total_data['total_product_price'] -= $marketing_data['data']['data']['activity_save'];
                }
            }
        }


        $res = $res_arr = $res1 = array();
        $wareHouseInfo = array();
        $newResult = array();
        if ($sort) {

            foreach ($result as $k => $v) {
                if (isset($marketing_data['data']['data']['goods_info'][$v['goods_id']]['activity'])) {
                    $goods_activity = $marketing_data['data']['data']['goods_info'] ? $marketing_data['data']['data']['goods_info'][$v['goods_id']]['activity'] : array();
                    if ($goods_activity) {
                        foreach ($goods_activity as $kk => $vv) {
                            $v['activity_rate'] = $vv['attr2'];
                            $v['activity_mode'] = $vv['mode'];
                            break;
                        }
                    }
                }
                $res_arr[$v['storehouse_id']][] = $v;
            }

            //安仓库分,计算每个仓库分别的运费,税费 by 李斌
            try {
                $responseData = $this->getExpreeFeeByWarehouse($res_arr, $province_id, $expressTypes, $goods_info);
                $res_arr = $responseData['res_arr'];
                //总价增加运费
                $total_data['total_express_fee'] = $responseData['totalExpressFee'];
                $total_data['total_price'] += $responseData['totalExpressFee'];
                //总价增加税费
                $total_data['total_tax_fee'] = $responseData['totalTaxFee'];
                $total_data['total_price'] += $responseData['totalTaxFee'];
                $wareHouseInfo = $responseData['wareHouseInfo'];
            } catch (sfException $e) {
                return $this->error($e->getCode(), $e->getMessage());
            }
        } else {
            foreach ($result as $k => $v) {

                if ($v['status'] == 1 || $v['status'] == 2) {
                    $lose_efficacy_list[] = $v;
                } else {
                    if(isset($marketing_data)) {
                        $goods_activity = $marketing_data['data']['data']['goods_info'] ? $marketing_data['data']['data']['goods_info'][$v['goods_id']]['activity'] : array();
                    }else {
                        $goods_activity = array();
                    }
                    $key = 0;
                    $skey = 0;

                    if ($goods_activity) {
                        foreach ($goods_activity as $kk => $vv) {
                            if ($vv['mode'] == 1) {
                                $key = 1;
                                $skey = 1;
                                $v['activity_rate'] = $vv['attr2'];
                                $v['activity_mode'] = $vv['mode'];
                                break;
                            } elseif ($vv['mode'] == 2) {
                                $key = 2;
                                $skey = 2;
                                $v['activity_rate'] = $vv['attr2'];
                                $v['activity_mode'] = $vv['mode'];
                                break;
                            } elseif ($vv['mode'] == 3) {
                                $key = 2;
                                $v['activity_rate'] = $vv['attr2'];
                                $v['activity_mode'] = $vv['mode'];
                                $skey = 3;
                                break;
                            }
                        }
                        if ($skey == 3) {
                            $res1[0][] = $v;
                        } else {
                            $res1[$goods_activity[0]['id']][] = $v;
                            $activity = array();
                            $activity['activity_rate'] = $goods_activity[0]['attr2'];
                            $activity['activity_mode'] = $this->getModeIntro($goods_activity[0]['mode']);
                            $activity['activity_text'] = "满" . $goods_activity[0]['attr1'] . "件即享";
                            $activity['activity_name'] = $goods_activity[0]['intro'];
                            $activity['activity_type'] = $goods_activity[0]['id'];
                            if (!empty($goods_activity[0]['url'])) {
                                $activity['activity_url'] = $goods_activity[0]['url'];
                            } else {
                                $activity['activity_url'] = "https://m.kaluli.com/item/activity?id=".$goods_activity[0]['id'];

                            }
                            $res1[$goods_activity[0]['id']]['activity'] = $activity;

                            // $res1[$goods_activity[0]['id']]['activity_rate'] = $goods_activity[0]['attr2'];
                            //$res1[$goods_activity[0]['id']]['activity_mode'] = $goods_activity[0]['mode'];
                        }
                    } else {
                        $res1[0][] = $v;
                    }
                    $res[$key][] = $v;
                }
                if (isset($res[1])) $res_arr[1] = $res[1];
                if (isset($res[2])) $res_arr[2] = $res[2];
                if (isset($res[3])) $res_arr[3] = $res[3];
                if (isset($res[0])) $res_arr[0] = $res[0];
                //设置新的m站购物车数据返回值
                if(!empty($res1)) {
                    $newResult['warehouse_name'] = self::$wareHoseName[$cart_type];
                    $newResult['warehouse_type'] = $cart_type;
                    $list = $this->sortCartList($res1);
                    $newResult['data'] = $list['cartList'];
                    $newResult['first'] = $list['first'];
                    $newResult['price_data'] = $total_data;
                }
            }
        }

        return $this->success(array('result' => $res_arr, 'total_data' => $total_data, 'valid_ids' => $valid_ids, 'wareHouseInfo' => $wareHouseInfo, 'newResult' => $newResult, 'loseEfficacyList' => $lose_efficacy_list));

    }

    /**
     * 新的获取订单页的商品价格、运费和税费
     * kworm
     */
    public function executeNewOrder()
    {
        $version = $this->getRequest()->getParameter('version', '');
        $hupuUid = $this->getUser()->getAttribute('uid');
        $goodsIds = $this->getRequest()->getParameter('gid');
        $region_id = $this->getRequest()->getParameter('address_id');//地址
        $express_type = $this->getRequest()->getParameter('express_type', KaluliOrder::$_DEFAULT_EXPRESS_TYPE);//快递类型 2顺丰 4圆通
        $type = $this->getRequest()->getParameter('type');//存在表示需要活动
        $sort = $this->getRequest()->getParameter('sort');//sort 商品聚合 不存在是营销活动 存在是仓库
        $cart_type = $this->getRequest()->getParameter('cart_type', 3);//区分获取的购物车list属性，0为普通货物，1为南沙仓货物，2为笨鸟仓货物3为所有
        //获取优惠券
        $card_id = $this->getRequest()->getParameter('card_id');
        $expressTypes = $this->getRequest()->getParameter("expressTypes", ""); //数组形式 key=仓库id,val=快递id

        if (empty($hupuUid) || !is_numeric($hupuUid)) {
            return $this->error(501, '未登录');
        }
        //初始化数据
        $total_count = 0;
        $total_price = 0;
        $total_number = 0;
        $total_weight = 0;
        $total_duty_fee = 0;
        $total_product_price = 0;

        //获取购物车数据
        //设置了区分list属性
        if ($cart_type == 1 || $cart_type == 0 || $cart_type == 2) {
            $cartObj = KaluliShoppingCartTable::getInstance()->createQuery('m')->select()->where('m.hupu_uid = ?', $hupuUid)->andWhere('m.type = ?', $cart_type);
        } else {
            $cartObj = KaluliShoppingCartTable::getInstance()->createQuery('m')->select()->where('m.hupu_uid = ?', $hupuUid);
        }

        if ($goodsIds) $cartObj = $cartObj->whereIn('m.goods_id', $goodsIds);
        $cartObj = $cartObj->orderBy('created_at desc')->execute();

        if (count($cartObj) == 0) {
            return $this->success(array('result' => array(), 'total_data' => array()));
        }

        //购物车商品ids 初始化商品信息 $goodsArr子商品详细信息 $productArr主商品详细信息
        $goods_ids = $product_ids = $goodsArr = $productArr = $marketing_list = array();

        $this->getCartData($cartObj, $goods_ids, $product_ids, $goodsArr, $productArr);
        //如果没有商品 那么就要清空购物车
        if (empty($goodsArr)) {
            foreach ($cartObj as $k => $v) {
                //删除购物车
                $serviceRequest = new kaluliServiceClient();
                $serviceRequest->setMethod('cart.delete');
                $serviceRequest->setVersion('1.0');
                $serviceRequest->setApiParam('goods_id', $v->getGoodsId());
                $serviceRequest->setUserToken(sfContext::getInstance()->getRequest()->getCookie('u'));
                $response = $serviceRequest->execute();
            }
            return $this->success(array('result' => array(), 'total_data' => 0));
        }

        $result = $valid_ids = array();
        $is_identify = 0; //身份证校验参数
        foreach ($cartObj as $m => $n) {
            $result[$m]['insert_time'] = strtotime($n->getUpdatedAt());
            $result[$m]['id'] = $n->getId();

            $result[$m]['number'] = $n->getNumber();
            $result[$m]['goods_id'] = $n->getGoodsId();
            $result[$m]['product_id'] = $n->getProductId();

            $result[$m]['price'] = $goodsArr[$n->getGoodsId()]['discount_price'];
            $result[$m]['total_price'] = $result[$m]['price'] * $result[$m]['number'];
            $result[$m]['img_path'] = $productArr[$n->getProductId()]['pic'] . '?imageView2/1/w/100/h/100';
            if (!empty($productArr[$n->getProductId()]['status'])) {
                $result[$m]['good_status'] = $productArr[$n->getProductId()]['status'];
            } else {
                //下架状态
                $result[$m]['good_status'] = 4;
            }

            $result[$m]['total_num'] = $goodsArr[$n->getGoodsId()]['total_num'];
            $result[$m]['title'] = $productArr[$n->getProductId()]['title'];
            $result[$m]['storehouse_id'] = $goodsArr[$n->getGoodsId()]['storehouse_id'];
            $result[$m]['weight'] = $goodsArr[$n->getGoodsId()]['weight'];
            $result[$m]['attr'] = $goodsArr[$n->getGoodsId()]['attr'] ? unserialize($goodsArr[$n->getGoodsId()]['attr']) : '';
            if ($goodsArr[$n->getGoodsId()]['storehouse_id'] == 10 || $goodsArr[$n->getGoodsId()]['storehouse_id'] == 20 || $goodsArr[$n->getGoodsId()]['storehouse_id'] == 16 || $goodsArr[$n->getGoodsId()]['storehouse_id'] == 5) {
                $is_identify = 1;
            } elseif ($goodsArr[$n->getGoodsId()]['storehouse_id'] == 19) {
                $is_identify = 2;
            }
            //税费统计 by 李斌
            $result[$m]['dutyFee'] = $this->getDutyFee($goodsArr[$n->getGoodsId()]['storehouse_id'], $result[$m]['total_price']);

            $total_duty_fee += $result[$m]['dutyFee'];

            //是否已售罄
            if ($goodsArr[$n->getGoodsId()]['status'] == 1 || $productArr[$n->getProductId()]['status'] == 4 || $result[$m]['total_num'] == 0) {
                $result[$m]['status'] = 1;//已售罄
            } else {
                $result[$m]['status'] = 0;//正常
            }

            //是否超过库存
            if ($result[$m]['total_num'] < $result[$m]['number']) {
                $result[$m]['stock_status'] = 1;//超过了库存
            } else {
                $result[$m]['stock_status'] = 0;//正常
            }

            $result[$m]['ware'] = KaluliWarehousesTable::getOneWareById($goodsArr[$n->getGoodsId()]['storehouse_id']);

            if ($result[$m]['stock_status'] == 0 && $result[$m]['status'] == 0) {
                array_push($valid_ids, $n->getGoodsId());
//                if ($goodsArr[$n->getGoodsId()]['storehouse_id'] == 1) {//识货仓库才要计算运费
//                    $weight = $goodsArr[$n->getGoodsId()]['weight'];
//                    $total_weight += $weight * $n->getNumber();
//                }
                $total_number += $n->getNumber();
                $total_count++;
                $total_price += $result[$m]['total_price'];
                $total_product_price += $result[$m]['total_price'];

                //营销活动
                $marketing_list[$n->getGoodsId()]['product_id'] = $n->getProductId();
                $marketing_list[$n->getGoodsId()]['goods_id'] = $n->getGoodsId();
                $marketing_list[$n->getGoodsId()]['number'] = $n->getNumber();
                $marketing_list[$n->getGoodsId()]['price'] = $result[$m]['total_price'];
            }
        }

        $province_id = null;
        if ($region_id) {
            $address = TrdUserDeliveryAddressTable::getInstance()->find($region_id);
            if ($address) {
                $province_id = $address->get('province');
            }
        }


        $total_data = array(
            'total_count' => $total_count,
            'total_price' => $total_price,
            'original_price' => $total_product_price,
            'total_product_price' => $total_product_price,
            'total_weight' => $total_weight,
            'activity' => array(),
            'is_identify' => $is_identify,
            'total_duty_fee' => $total_duty_fee,
            'total_original_price' => $total_product_price
        );


        //如果需要活动, 会员权益单独写出的优惠
        if ($type && $type == 1) {
            $serviceRequest = new kaluliServiceClient();
            $serviceRequest->setMethod('benefits.get.activity');
            $serviceRequest->setVersion('1.0');
            $serviceRequest->setApiParam('goods', $marketing_list);
            $serviceRequest->setApiParam('card_id', $card_id);

            if (empty($goodsIds)) $serviceRequest->setApiParam('type', 1);
            $response = $serviceRequest->execute();

            if (!$response->hasError()) {
                $dis_data = $response->getData();
                $total_data['activity'] = $dis_data['data']['data'];
                $goods_info = $dis_data['data']['data']['goods_info'];
                $total_data['total_price'] -= $dis_data['data']['data']['activity_save'];
                $total_data['total_product_price'] -= $dis_data['data']['data']['activity_save'];

            }
        }

        $res = $res_arr = array();
        $wareHouseInfo = array();
        if ($sort) {
            foreach ($result as $k => $v) {
                $res_arr[$v['storehouse_id']][] = $v;
            }

            //根据goods_info,mark1
            //安仓库分,计算每个仓库分别的运费,税费 by 李斌
            try {
                $responseData = $this->getExpreeFeeByWarehouse($res_arr, $province_id, $expressTypes, $goods_info);

                $res_arr = $responseData['res_arr'];
                //总价增加运费
                $total_data['total_express_fee'] = $responseData['totalExpressFee'];
                $total_data['total_price'] += $responseData['totalExpressFee'];
                //总价增加税费
                $total_data['total_tax_fee'] = $responseData['totalTaxFee'];
                $total_data['total_price'] += $responseData['totalTaxFee'];
                $wareHouseInfo = $responseData['wareHouseInfo'];
            } catch (sfException $e) {
                return $this->error($e->getCode(), $e->getMessage());
            }
        } else {
            if (count($marketing_data['data']['data']['activity']) > 0) {
                foreach ($result as $k => $v) {
                    $goods_activity = $marketing_data['data']['data']['goods_info'] ? $marketing_data['data']['data']['goods_info'][$v['goods_id']]['activity'] : array();
                    $key = 0;
                    if ($goods_activity) {
                        foreach ($goods_activity as $kk => $vv) {
                            if ($vv['mode'] == 1) {
                                $key = 1;
                                break;
                            } elseif ($vv['mode'] == 2) {
                                $key = 2;
                                break;
                            }
                        }
                    }
                    $res[$key][] = $v;
                }
                if (isset($res[1])) $res_arr[1] = $res[1];
                if (isset($res[2])) $res_arr[2] = $res[2];
                if (isset($res[0])) $res_arr[0] = $res[0];
            } else {
                $res_arr[] = $result;
            }
        }


        return $this->success(array('result' => $res_arr, 'total_data' => $total_data, 'valid_ids' => $valid_ids, 'wareHouseInfo' => $wareHouseInfo));

    }

    /**
     * 获取选中商品最新价格信息
     */
    public function executePrice()
    {
        $version = $this->getRequest()->getParameter('version', '');
        $hupuUid = $this->getUser()->getAttribute('uid');
        $goodsIds = $this->getRequest()->getParameter('gid');
        $type = $this->getRequest()->getParameter('type');//需要活动
        if (empty($hupuUid) || !is_numeric($hupuUid)) {
            return $this->error(501, '未登录');
        }

        if (!isset($goodsIds) || empty($goodsIds)) {
            return $this->error(400, '没有商品ID');
        }

        $total_count = 0;
        $total_price = 0;
        $total_number = 0;
        $total_weight = 0;
        $total_duty_fee = 0;
        $is_ht = false; //是否海淘
        $is_benniao = false; //是否笨鸟
        $flag = false;
        $cartObj = KaluliShoppingCartTable::getInstance()->createQuery('m')
            ->select()
            ->where('m.hupu_uid = ?', $hupuUid)
            ->whereIn('m.goods_id', $goodsIds)
            ->orderBy('created_at desc')
            ->execute();

        if (count($cartObj) < 1) {
            return $this->error(401, '购物车中没有对应商品');
        }

        //购物车商品ids 初始化商品信息 $goodsArr子商品详细信息 $productArr主商品详细信息
        $goods_ids = $product_ids = $goodsArr = $productArr = $marketing_list = array();

        $this->getCartData($cartObj, $goods_ids, $product_ids, $goodsArr, $productArr);

        foreach ($cartObj as $m => $n) {
            $result[$m]['goods_id'] = $n->getGoodsId();
            $result[$m]['total_num'] = $goodsArr[$n->getGoodsId()]['total_num'];
            $result[$m]['storehouse_id'] = $goodsArr[$n->getGoodsId()]['storehouse_id'];
            if ($goodsArr[$n->getGoodsId()]['status'] == 1 || $productArr[$n->getProductId()]['status'] == 4) {
                $result[$m]['status'] = 1;//已下架
            } elseif ($result[$m]['total_num'] == 0) {
                $result[$m]['status'] = 2;//已售罄
            } else {
                $result[$m]['status'] = 0;//正常
            }
            //是否超过库存
            if ($result[$m]['total_num'] < $n->getNumber()) {
                $result[$m]['stock_status'] = 1;//超过了库存
            } else {
                $result[$m]['stock_status'] = 0;//正常
            }
            $result[$m]['num'] = $n->getNumber();

            $price = $goodsArr[$n->getGoodsId()]['discount_price'];
            $price = $price * $n->getNumber();
            $result[$m]['price'] = $price;
            if ($goodsArr[$n->getGoodsId()]['storehouse_id'] == 1) {//识货仓库才要计算运费
                $weight = $goodsArr[$n->getGoodsId()]['weight'];
                $total_weight += $weight * $n->getNumber();
            }
            $total_number += $n->getNumber();
            $total_count++;
            $total_price += $price;
            //增加税费逻辑 by libin
            // $total_duty_fee += $this->getDutyFee($goodsArr[$n->getGoodsId()]['storehouse_id'], $price);
            //营销活动
            $marketing_list[$n->getGoodsId()]['product_id'] = $n->getProductId();
            $marketing_list[$n->getGoodsId()]['goods_id'] = $n->getGoodsId();
            $marketing_list[$n->getGoodsId()]['number'] = $n->getNumber();
            $marketing_list[$n->getGoodsId()]['price'] = $price;
            if ($n->getType() == 1 || $n->getType() == 4) {
                $is_ht = true;
                $flag = true;
            }
            if ($n->getType() == 2) {
                $is_benniao = true;
            }
        }

        $data = array(
            'total_count' => $total_count,
            'original_price' => $total_price,
            'total_price' => $total_price,
            'total_weight' => $total_weight,
            'total_duty_fee' => $total_duty_fee,
            'activity' => array(),
            'result' =>$result
        );

        //如果需要活动
        if ($type) {
            $serviceRequest = new kaluliServiceClient();
            $serviceRequest->setMethod('marketing.getActivity');
            $serviceRequest->setVersion('1.0');
            $serviceRequest->setApiParam('marketing_list', $marketing_list);
            $response = $serviceRequest->execute();

            if (!$response->hasError()) {
                $marketing_data = $response->getData();

                $data['activity'] = $marketing_data['data']['data'];
                $data['total_price'] -= $marketing_data['data']['data']['activity_save'];
            }
        }

        //计算税费
        foreach($result as $k=>$v) {
            if(!empty($data['activity']['goods_info'])) {
                foreach ($data['activity']['goods_info'] as $gk => $gv) {
                    if($v['goods_id'] == $gv['goods_id']) {
                        if(isset($gv['marketing_fee'])) {
                            $total_duty_fee += $this->getDutyFee($v['storehouse_id'], $gv['price'] -$gv['marketing_fee']);
                            $result[$k]['duty_fee'] = $this->getDutyFee($v['storehouse_id'], $gv['price'] -$gv['marketing_fee']);
                            continue;
                        }
                    }
                }
                if(!isset($result[$k]['duty_fee'])) {
                    $total_duty_fee += $this->getDutyFee($v['storehouse_id'], $v['price']);
                    $result[$k]['duty_fee'] = $this->getDutyFee($v['storehouse_id'], $v['price']);
                }

            } else {
                $total_duty_fee += $this->getDutyFee($v['storehouse_id'],$v['price']);
                $result[$k]['duty_fee'] = $this->getDutyFee($v['storehouse_id'],$v['price']);
            }

        }
        $data['total_duty_fee'] = $total_duty_fee;
        $data['result'] = $result;


        //如果计算税费
        if ($data['total_price'] >= 2000 && $is_ht == true) {
            $data['is_duty'] = true;
        }
        //笨鸟相关
        if ($data['total_price'] > 1000 && $is_benniao == true) {
            $data['is_duty_benniao'] = true;
        }

        if($data['total_price'] >=2000 && $flag == true) {
            $data['flag'] = true;
        }

        //组装activityInfo
        if (isset($data['activity']['activity'][2])) {
            $data['activityInfo'] = KaluliFun::checkCartActivity($data['activity']['activity'][2]['list'], $data['activity']['goods_info']);
        }


        return $this->success(array('data' => $data));
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
        $redis->select(1);
        $count = $redis->get('kaluli_shopping_cart_' . $hupuUid);
        if (!$count) {
            $count = KaluliShoppingCartTable::getInstance()->createQuery()->select()->where('hupu_uid = ?', $hupuUid)->count();
        } else {
            $count = 0;
        }
        return $this->success(array('count' => $count));
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
        $linkFlag = $this->getRequest()->getParameter('linkFlag');
        $type = $this->getRequest()->getParameter('type');//存在表示需要活动

        $express_type = $this->getRequest()->getParameter('express_type', KaluliOrder::$_DEFAULT_EXPRESS_TYPE);//快递类型 2顺丰 4圆通
        $card_id = $this->getRequest()->getParameter('card_id', '');//优惠码
        $card_type = $this->getRequest()->getParameter('card_type', '');//优惠码类型
        //默认是需要活动，这里需添加一个判断，如果是排他活动则设置成2
        if ($card_type == 2) {
            $type = 2;
        }
        $is_wap = $this->getRequest()->getParameter('is_wap', 0);
        $kll_union = $this->getRequest()->getParameter('kll_union'); // cps推广标志
        $is_app = $this->getRequest()->getParameter("is_app", 0);//标识app支付位

        $pay_data = $this->getRequest()->getParameter('pay_data', array());  //支付参数
        $expressTypes = $this->getRequest()->getParameter("expressTypes", array());
        $nansha_flag = 0;
        $is_ht = false; //需要计算税费参数

        if (empty($express_type)) {
            $express_type = KaluliOrder::$_DEFAULT_EXPRESS_TYPE;
        }

        if (!$hupuUid) {
            return $this->error(501, '未登录');
        }
        if (!is_numeric($region_id)) {
            return $this->error(401, '收货地址不合法');
        }
        $address = TrdUserDeliveryAddressTable::getInstance()->find($region_id);
        if (!$address) {
            return $this->error(402, '收货地址不合法');
        }

        //校验收货地址是否为当前用户
        if ($address->hupu_uid != $hupuUid) {
            return $this->error(401, '收货地址不合法');
        }


        $data_array = $goods_ids;

        if (empty($data_array)) {
            return $this->error(404, '参数异常');
        }

        $cartObj = KaluliShoppingCartTable::getInstance()->createQuery('m')
            ->select()
            ->where('m.hupu_uid = ?', $hupuUid)
            ->whereIn('m.goods_id', $data_array)
            ->orderBy('created_at desc')->execute();


        //获取购物车商品信息
        $goodsObj = kaluliItemSkuTable::getSkuById($data_array);

        $product_ids = $goodsArr = $productArr = array();
        if (count($goodsObj) > 0) {
            foreach ($goodsObj as $k => $v) {

                array_push($product_ids, $v['item_id']);
                $goodsArr[$v['id']] = $v;
                //判断仓库
                if ($v['storehouse_id'] == 10 || $v['storehouse_id'] == 20 || $v['storehouse_id'] == 16 || $v['storehouse_id'] == 5) {
                    if (!$address->getIdentityNumber()) {
                        return $this->error(403, '收货地址必须要身份证号码');
                    }
                    if ((int)$v['storehouse_id'] == 10 || (int)$v['storehouse_id'] == 19 || (int)$v['storehouse_id'] == 20 || (int)$v['storehouse_id'] == 5 || (int)$v['storehouse_id'] == 16) {
                        $nansha_flag = 1;
                    }
                }

            }
        }

        if ($product_ids) {
            $productObj = KaluliItemTable::getItemByIds($product_ids);
            if (count($productObj) > 0) {
                foreach ($productObj as $k => $v) {
                    $productArr[$v['id']] = $v;
                }
            }
        }

        $total_weight = $total_count = $total_product_price = $total_number = $total_weight_price = $total_storehouse = 0;
        $result = $marketing_list = array();
        foreach ($cartObj as $m => $n) {
            $result[$m]['id'] = $n->getId();
            $result[$m]['number'] = $n->getNumber();
            $result[$m]['product_id'] = $n->getProductId();
            $result[$m]['goods_id'] = $n->getGoodsId();
            $result[$m]['price'] = $goodsArr[$n->getGoodsId()]['discount_price'];
            $result[$m]['total_price'] = $result[$m]['price'] * $result[$m]['number'];
            $result[$m]['img_path'] = $productArr[$n->getProductId()]['pic'];
            $result[$m]['storehouse_id'] = $goodsArr[$n->getGoodsId()]['storehouse_id'];
            $result[$m]['total_num'] = $goodsArr[$n->getGoodsId()]['total_num'];
            $result[$m]['code'] = $goodsArr[$n->getGoodsId()]['code'];
            $result[$m]['attr'] = $goodsArr[$n->getGoodsId()]['attr'];
            if ($result[$m]['total_num'] < $result[$m]['number']) {
                return $this->error(406, '有商品库存不足，请返回购物车重新选择');
            }
            $result[$m]['title'] = $productArr[$n->getProductId()]['title'];
            $result[$m]['weight'] = $goodsArr[$n->getGoodsId()]['weight'];

            if ($goodsArr[$n->getGoodsId()]['storehouse_id'] == 1) {//识货仓库才要计算运费
                $weight = $goodsArr[$n->getGoodsId()]['weight'];
                $total_weight += $weight * $n->getNumber();
                $total_weight_price += $result[$m]['total_price'];
                $total_storehouse++;//识货仓库里商品数目


            }
            $total_number += $n->getNumber();
            $total_count++;
            $total_product_price += $result[$m]['total_price'];

            //拼装活动需要的数据
            $marketing_list[$n->getGoodsId()]['product_id'] = $n->getProductId();
            $marketing_list[$n->getGoodsId()]['goods_id'] = $n->getGoodsId();
            $marketing_list[$n->getGoodsId()]['number'] = $n->getNumber();
            $marketing_list[$n->getGoodsId()]['price'] = $result[$m]['total_price'];
        }
        if (count($result) != count($data_array)) {
            return $this->error(405, '选择的商品有异常情况，请返回购物车重新选择');
        }
        $address = TrdUserDeliveryAddressTable::getInstance()->find($region_id);

        $express_fee = $coupon_fee = 0;
        if ($region_id) {
            $province_id = $address->get('province');
        }


        $total_price = $total_product_price;

        //生成订单号
        $order_sn = date('ymd') . substr(time(), -5) . substr(microtime(), 2, 5);

        $activity_save = 0;
        $goods_info = array();

        //如果需要活动,
        if ($type && $type == 1) {
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
                    $activity_save = $total_product_price + $express_fee;
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

        } elseif ($type && $type == 2) {
            //排它的活动
            $serviceRequest = new kaluliServiceClient();
            $serviceRequest->setMethod('benefits.get.activity');
            $serviceRequest->setVersion('1.0');
            $serviceRequest->setApiParam('goods', $marketing_list);
            $serviceRequest->setApiParam('card_id', $card_id);
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

        //按仓库整合订单
        $res_arr = array();
        $wareHouseInfo = array();
        foreach ($result as $k => $v) {
            $res_arr[$v['storehouse_id']][] = $v;
        }
        try {
            $responseData = $this->getExpreeFeeByWarehouse($res_arr, $province_id, $expressTypes, $goods_info);
            $res_arr = $responseData['res_arr'];
            //总价增加运费
            $express_fee = $responseData['totalExpressFee'];
            //总价增加税费
            $total_tax_fee = $responseData['totalTaxFee'];
            $wareHouseInfo = $responseData['wareHouseInfo'];
        } catch (sfException $e) {
            return $this->error($e->getCode(), $e->getMessage());
        }

        $original_price = $total_product_price + $express_fee;

        $total_price += $express_fee; //总价增加运费

        $total_price += $total_tax_fee; // 总价增加税费


        $child_total_price = $total_price;

        if ($card_id && $card_type == 1) {
            //优惠码验证

            $serviceRequest->setMethod('lipinka.use');
            $serviceRequest->setVersion('1.0');
            $serviceRequest->setApiParam('user_id', $hupuUid);
            $serviceRequest->setApiParam('id', $card_id);
            $serviceRequest->setApiParam('card_limit', array('order_money' => $total_product_price));
            $response = $serviceRequest->execute();
            if (!$response->hasError()) {

                $_lipinka_data = $response->getData();
                $coupon_fee = $_lipinka_data['data']['amount'];
                $total_price -= $coupon_fee;
                if ($total_price < 0) {
                    return $this->error(403, '礼品卡金额不能大于订单金额！');
                }

                //插入礼品卡使用记录
                $activityDetailObi = new KllOrderActivityDetail();
                $activityDetailObi->set('order_number', $order_sn);
                $activityDetailObi->set('activity_id', $card_id);
                $activityDetai_attr = array('code' => $_lipinka_data['data']['account'], 'price' => $coupon_fee);
                $activityDetailObi->set('attr', json_encode($activityDetai_attr));
                $activityDetailObi->save();
            } else {
                return $this->error(403, $response->getError());
            }
        }
        if ($nansha_flag && $total_price > 2000) {
            return $this->error(408, '跨境订单金额不能超过2000');
        }


        try {
            $time = date('Y-m-d H:i:s');
            //保存订单

            //插入主表 kll_main_order
            $mainOrderObj = new KaluliMainOrder();
            $mainOrderObj->setOrderNumber($order_sn);
            $mainOrderObj->setHupuUid($hupuUid);
            $mainOrderObj->setHupuUsername($hupuUname);
            $mainOrderObj->setExpressFee($express_fee);
            $mainOrderObj->setMarketingFee($activity_save);
            $mainOrderObj->setTotalPrice($total_price);
            $mainOrderObj->setOriginalPrice($original_price);
            $mainOrderObj->setCouponFee($coupon_fee);
            $mainOrderObj->setNumber($total_number);
            $mainOrderObj->setOrderTime($time);
            $mainOrderObj->setPayType(empty($pay_data['pay_type']) ? $this->pay_type['default'] : $pay_data['pay_type']);
            if (isset($total_tax_fee)) {
                $mainOrderObj->setDutyFee($total_tax_fee);
            }

            //判断是不是wap登陆
            if ($is_wap) {
                $mainOrderObj->setSource(1);
            }
            $mainOrderObj->save();

            //保存主订单副表
            $mainOrderAttrObj = new KaluliMainOrderAttr();
            $mainOrderAttrObj->setOrderNumber($order_sn);
            //拼接收货地址
            $street = explode(' ', trim($address->getRegion()));
            $address_arr = array(
                'name' => $address->getName(),
                'postcode' => $address->getPostcode(),
                'province' => $street[0],
                'city' => $street[1],
                'area' => isset($street[2]) ? $street[2] : '',
                'mobile' => $address->getMobile(),
                'region' => $address->getRegion(),
                'street' => $address->getStreet(),
                'identity_number' => $address->getIdentityNumber()
            );
            $mainOrderAttrObj->setAddressAttr(json_encode($address_arr));
            $mainOrderAttrObj->setRemark($remark);
            $mainOrderAttrObj->setIsRemind(0);
            //使用优惠券,存入
            if ($card_type == 1 && !empty($card_type)) {
                $mainOrderAttrObj->setCouponId($card_type);
            }


            $mainOrderAttrObj->save();

            //会员权益

            if (isset($card_type) && $card_type == 2) {

                $this->_useBenefits($order_sn, $card_id);
            }


            $serviceRequest = new kaluliServiceClient();

            //分仓库插入子订单
            foreach ($res_arr as $k => $v) {
                //计算总价
                $sumExpressFee = 0;
                $sumGoodsNumber = 1;
                $sumDutyFee = 0;
                $totalGoodsNumber = count($v); //计算每个仓库的所有子订单数量
                foreach ($v as $key => $val) {
                    //仓库运费分摊逻辑
                    if ($sumGoodsNumber == $totalGoodsNumber) {
                        $orderExpressFee = $wareHouseInfo[$k]['expressFee'] - $sumExpressFee;
                    } else {
                        $orderExpressFee = number_format(($val['weight'] / $wareHouseInfo[$k]['weight']) * $wareHouseInfo[$k]['expressFee'], 2, '.', '');
                        $sumExpressFee += $orderExpressFee;
                    }
                    //仓库税费分摊逻辑
                    if (isset($goods_info[$val['goods_id']]['marketing_fee'])) {
                        $marketingFee = empty($goods_info[$val['goods_id']]['marketing_fee']) ? 0 : $goods_info[$val['goods_id']]['marketing_fee'];
                    } else if (isset($goods_info[$val['goods_id']]['save'])) {
                        $marketingFee = empty($goods_info[$val['goods_id']]['save']) ? 0 : $goods_info[$val['goods_id']]['save'];
                    } else {
                        $marketingFee = 0;
                    }

                    if ($sumGoodsNumber == $totalGoodsNumber) {
                        $orderDutyFee = $wareHouseInfo[$k]['dutyFee'] - $sumDutyFee;
                    } else {
                        $orderDutyFee = number_format((($val['total_price'] + $orderExpressFee - $marketingFee) / $wareHouseInfo[$k]['totalPrice']) * $wareHouseInfo[$k]['dutyFee'], 2, '.', '');
                        $sumDutyFee += $orderDutyFee;
                    }
                    $sumGoodsNumber++;
                    //插入子订单
                    $attr = array();
                    $orderObj = new KaluliOrder();
                    $orderObj->setOrderNumber($order_sn);
                    $orderObj->setTitle($val['title']);
                    $orderObj->setProductId($val['product_id']);
                    $orderObj->setHupuUid($hupuUid);
                    $orderObj->setHupuUsername($hupuUname);
                    $orderObj->setGoodsId($val['goods_id']);
                    $orderObj->setPrice($val['price']);
                    $orderObj->setNumber($val['number']);
                    $orderObj->setExpressFee($orderExpressFee);
                    $orderObj->setMarketingFee($marketingFee);
                    $orderObj->setDomesticExpressType($expressTypes[$k]);
                    $orderObj->setTotalPrice($val['total_price'] + $orderExpressFee);
                    $orderObj->setDepotType($val['storehouse_id']);//发货仓库
                    $orderObj->setOrderTime($time);
                    $orderObj->setDutyFee($orderDutyFee);
                    if ($is_wap) {
                        $orderObj->setSource(1);
                    }
                    $orderObj->save();

                    //保存子订单 副表
                    $orderAttrObj = new KaluliOrderAttr();
                    $orderAttrObj->setOrderNumber($order_sn);
                    $orderAttrObj->setOrderId($orderObj->getId());
                    $orderAttrObj->setCode($val['code']);

                    if ($val['attr']) {
                        $goods_attr = unserialize($val['attr']);
                        $attr = $goods_attr['attr'];
                    }
                    $attr['img'] = $val['img_path'];
                    $orderAttrObj->setAttr(json_encode($attr));
                    $orderAttrObj->save();

                    //减库存
                    $serviceRequest->setMethod('item.skuStock');
                    $serviceRequest->setVersion('1.0');
                    $serviceRequest->setApiParam('id', $val['goods_id']);
                    $serviceRequest->setApiParam('num', $val['number']);
                    $serviceRequest->setApiParam('type', 1);//下单情况
                    $serviceRequest->execute();


                }
            }


//            //插入子订单
//            foreach ($result as $k => $v) {
//                $freight = 0;
//                $attr = array();
//                $orderObj = new KaluliOrder();
//                $orderObj->setOrderNumber($order_sn);
//                $orderObj->setTitle($v['title']);
//                $orderObj->setProductId($v['product_id']);
//                $orderObj->setHupuUid($hupuUid);
//                $orderObj->setHupuUsername($hupuUname);
//                $orderObj->setGoodsId($v['goods_id']);
//                $orderObj->setPrice($v['price']);
//                $orderObj->setNumber($v['number']);
//                if ($express_fee > 0 && $v['storehouse_id'] == 1) {//存在运费
//                    if ($total_storehouse == $freight_storehouse_num) {
//                        $freight = $express_fee - $freight_sum;
//                    } else {
//                        $freight = floor($freight_per * $weight * 100) / 100;
//                        $freight_storehouse_num ++;
//                    }
//                    $freight_sum += $freight;
//                }
//                $orderObj->setExpressFee($freight);
//                $marketing_fee = empty($goods_info[$v['goods_id']]['marketing_fee']) ? 0 : $goods_info[$v['goods_id']]['marketing_fee'];
//                $orderObj->setMarketingFee($marketing_fee);
//                $orderObj->setDomesticExpressType($express_type);
//                $orderObj->setTotalPrice($v['total_price'] + $freight);
//                $orderObj->setDepotType($v['storehouse_id']);//发货仓库
//                $orderObj->setOrderTime($time);
//                if($is_wap) {
//                    $orderObj->setSource(1);
//                }
//                $orderObj->save();
//
//                //保存子订单 副表
//                $orderAttrObj = new KaluliOrderAttr();
//                $orderAttrObj->setOrderNumber($order_sn);
//                $orderAttrObj->setOrderId($orderObj->getId());
//                $orderAttrObj->setCode($v['code']);
//
//                if($v['attr']){
//                    $goods_attr = unserialize($v['attr']);
//                    $attr = $goods_attr['attr'];
//                }
//                $attr['img'] = $v['img_path'];
//                $orderAttrObj->setAttr(json_encode($attr));
//                $orderAttrObj->save();
//
//                //减库存
//                $serviceRequest->setMethod('item.skuStock');
//                $serviceRequest->setVersion('1.0');
//                $serviceRequest->setApiParam('id',$v['goods_id']);
//                $serviceRequest->setApiParam('num',$v['number']);
//                $serviceRequest->setApiParam('type',1);//下单情况
//                $serviceRequest->execute();
//            }
        } catch (Exception $e) {
            return $this->error(502, $e->getMessage());
        }


        //记录到订单日志表
        $log = array(
            'status' => 0,
            'order_number' => $order_sn,
            'hupu_uid' => $hupuUid,
            'hupu_username' => $hupuUname,
            'explanation' => '购物车购买了' . $total_number . '样商品',
        );
        $this->saveLog($log);

        //记录到log文件
        $data['hupu_uid'] = $hupuUid;
        $data['hupu_username'] = $hupuUname;
        $message = array(
            'message' => '卡路里购物车下单成功',
            'param' => $data,
            'res' => array(),
            'order_number' => $order_sn
        );
        kaluliLog::info('kaluli-place', $message);

        //去支付 下个页面
        if ($is_wap) {
            $url = '//m.kaluli.com/auction/orderResult?order_number=' . $order_sn;
        } else {
            $url = '//www.kaluli.com/auction/orderResult?order_number=' . $order_sn;
        }


        //是否生成购买链接
        if ($linkFlag) {
            $serviceRequest->setMethod('order.getPayLink');
            $serviceRequest->setVersion('1.0');
            $serviceRequest->setApiParam('uid', $hupuUid);
            $serviceRequest->setApiParam('pay_data', $pay_data);
            $serviceRequest->setApiParam('is_wap', $is_wap);
            $serviceRequest->setApiParam("is_app", $is_app);
            $serviceRequest->setApiParam('order_number', $order_sn);
            $response = $serviceRequest->execute();
            if (!$response->hasError()) {
                $res = $response->getData();
                //$url = $res['data']['pay_url'];
            } else {
                return $this->error(502, '系统繁忙，请稍后再试');
            }
        }
        //清除购物车
        KaluliShoppingCartTable::getInstance()->createQuery('m')
            ->delete()
            ->where('m.hupu_uid = ?', $hupuUid)
            ->whereIn('m.goods_id', $data_array)
            ->execute();
        $count = KaluliShoppingCartTable::getInstance()->createQuery()->select()->where('hupu_uid = ?', $hupuUid)->count();
        //更新用户redis购物车数量
        $redis = sfContext::getInstance()->getDatabaseConnection('kaluliRedis');
        $redis->select(1);
        $redis->set('kaluli_shopping_cart_' . $hupuUid, $count, 3600 * 24 * 180);

        //cps推广
        $message = array('order_number' => $order_sn, 'cookie' => $kll_union, 'type' => 'create');
        kaluliFun::sendMqMessage('kalulicps.order.detail', $message, 'kaluli_order_detail_deferred');

        return $this->success($res['data']);
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
        $goodsObj = kaluliItemSkuTable::getSkuById($goods_ids);

        if (count($goodsObj) > 0) {
            foreach ($goodsObj as $k => $v) {
                array_push($product_ids, $v['item_id']);

                $goodsArr[$v['id']] = $v;
            }
        }

        if ($product_ids) {
            $productObj = kaluliItemTable::getItemByIds($product_ids,0);
            if (count($productObj) > 0) {
                foreach ($productObj as $k => $v) {
                    $productArr[$v['id']] = $v;
                }
            }
        }
    }


    //保存日志
    private function saveLog($data)
    {
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

    //获取税费
    private function getDutyFee($wareHouseId, $price)
    {
        $serviceRequest = new kaluliServiceClient();
        $serviceRequest->setVersion("1.0");
        $serviceRequest->setMethod("warehouse.GetTax");
        $serviceRequest->setApiParam("wareHouseId", $wareHouseId);
        $serviceRequest->setApiParam("price", $price);
        $response = $serviceRequest->execute();
        if ($response->hasError()) {
            return 0;
        }

        return $response->getValue("dutyFee");
    }

    /**
     * 根据转好的仓库商品分组计算运费数据,税费数据
     * @param $data
     * @param $regionId
     * @param $expressType
     *
     */
    private function getExpreeFeeByWarehouse($data, $regionId, $expressTypes, $good_market_info)
    {
        $totalExpressFee = 0;
        $totalTaxFee = 0;
        $wareHouseInfo = array();

        //遍历仓库
        foreach ($data as $k => $v) {
            //遍历仓库下的商品,计算运费
            if (empty($v)) continue;
            $weight = 0;
            $wareHouseId = $k;
            $totalPrice = 0; //计算仓库总价
            foreach ($v as $goodInfo) {
                $weight += ($goodInfo['weight'] * $goodInfo['number']);
                $totalPrice += $goodInfo['total_price'];
                //有活动优惠减去活动优惠
                if (!empty($good_market_info[$goodInfo['goods_id']]['marketing_fee'])) {
                    $totalPrice -= $good_market_info[$goodInfo['goods_id']]['marketing_fee'];
                }
                //是否是脱离其他的活动
                if (!empty($good_market_info[$goodInfo['goods_id']]['out'])) {
                    $totalPrice -= $good_market_info[$goodInfo['goods_id']]['save'];
                }
            }
            $serviceRequest = new kaluliServiceClient();
            $serviceRequest->setVersion("1.0");
            $serviceRequest->setMethod("warehouse.GetExpressFee");
            if (!empty($regionId)) {
                $serviceRequest->setApiParam("provinceId", $regionId);
            } else {
                $serviceRequest->setApiParam("isDefault", 1);
            }
            $serviceRequest->setApiParam("wareHouseId", $wareHouseId);
            $serviceRequest->setApiParam("weight", $weight);
            if (empty($expressTypes)) {
                $wareExpress = KllWarehousesExpressTable::getInstance()->findOneByWarehouseIdAndIsDefault($k, 1);
                if (empty($wareExpress)) {
                    throw new sfException("快递默认仓库不存在.仓库id" . $k);
                }
                $serviceRequest->setApiParam("expressType", $wareExpress->getExpressId());
            } else {
                $serviceRequest->setApiParam("expressType", $expressTypes[$k]);
            }
            $response = $serviceRequest->execute();
            if ($response->hasError()) {
                throw new sfException($response->getError());
            }
            $expressData = $response->getData();
            $expressList = $expressData['data'];
            $wareHouseInfo[$k]['expressList'] = $expressList;
            foreach ($expressList as $key => $val) {
                if ($val['isCheck'] == 1) {
                    $wareHouseInfo[$k]["expressFee"] = $val['fee'];
                }
            }
            $totalExpressFee += $wareHouseInfo[$k]['expressFee'];
            $totalPrice += $wareHouseInfo[$k]['expressFee']; // 单个仓库的总价=单件小品的小计综合-活动优惠+运费
            //计算税费
            $serviceRequest->setMethod("warehouse.GetTax");
            $serviceRequest->setVersion("1.0");
            $serviceRequest->setApiParam("wareHouseId", $k);
            $serviceRequest->setApiParam("price", $totalPrice);
            $response = $serviceRequest->execute();
            if (!$response->hasError()) {
                $totalTaxFee += $response->getValue("dutyFee");
                $wareHouseInfo[$k]["dutyFee"] = $response->getValue("dutyFee");
            }
            $wareHouseInfo[$k]['weight'] = $weight;
            $wareHouseInfo[$k]['totalPrice'] = $totalPrice;
            //获取税费规则
            //获取税费信息
            $serviceRequest->setMethod("warehouse.GetTaxInfo");
            $serviceRequest->setApiParam("wareHouseId", $k);
            $serviceRequest->setVersion("1.0");
            $response = $serviceRequest->execute();
            if (!$response->hasError()) {
                $taxInfo = $response->getData();
                $wareHouseInfo[$k]['taxInfo'] = $taxInfo['data']->toArray();
            }
        }
        return ['res_arr' => $data, 'totalExpressFee' => $totalExpressFee, "totalTaxFee" => $totalTaxFee, "wareHouseInfo" => $wareHouseInfo];

    }

    private function _useBenefits($order_number, $card_id)
    {
        if ($order_number && $card_id) {
            $benefits = KllMemberBenefitsTable::getInstance()->findOneById($card_id);
            if (!empty($benefits)) {
                $times = $benefits->getTimes();
                if ($times != 0) {
                    $times = $times - 1;
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


    private function sortCartList($cartList)
    {
        if (empty($cartList))
            return ['cartList' => [], 'first' => ""];
        foreach ($cartList as $k => $v) {
            if (isset($v['activity'])) {
                $activity = $v['activity'];
                unset($v['activity']);
                $cartList[$k] = KaluliFun::my_sort($v, 'insert_time', SORT_DESC, SORT_NUMERIC);
                $cartList[$k]['activity'] = $activity;
            } else {
                $cartList[$k] = KaluliFun::my_sort($v, 'insert_time', SORT_DESC, SORT_NUMERIC);

            }
            $sortTime[$k] = $v[0]['insert_time'];
        }

        //组建新数组
        arsort($sortTime);
        $newCartList = array();
        foreach ($sortTime as $sk => $sv) {
            $newCartList[$sk] = $cartList[$sk];
        }
        reset($sortTime);
        return ['cartList' => $newCartList, 'first' => current($sortTime)];

    }

    //格式化活动类型显示名称
    private function getModeIntro($mode)
    {
        if ($mode == 1) {
            return "满减";
        } elseif ($mode == 2) {
            return "折扣";
        } else {
            return "折扣";
        }
    }

    /**
     * 清空购物车失效商品
     */
    public function executeCleanLose()
    {
        $ids = $this->getRequest()->getParameter("data");
        $uid = $this->getRequest()->getParameter("uid");
        if (empty($ids)|| empty($uid)) {
            return $this->error("500", "不存在失效商品");
        }
        $redis = sfContext::getInstance()->getDatabaseConnection('kaluliRedis');
        $redis->select(1);
        KaluliShoppingCartTable::getInstance()->createQuery('m')
            ->delete()
            ->whereIn('m.id', $ids)
            ->execute();
        $count = KaluliShoppingCartTable::getInstance()->createQuery()->select()->where('hupu_uid = ?', $uid)->count();
        $redis->set('kaluli_shopping_cart_' . $uid, $count, 3600 * 24 * 180);
        return $this->success();

    }


    /**
     * 提交订单前校验购物车
     */

    public function executeCheckStatus() {
        $uid = $this->getRequest()->getParameter("uid");
        $goodsIds = $this->getRequest()->getParameter('gid');
        if (empty($uid) || !is_numeric($uid)) {
            return $this->error(501, '未登录');
        }

        if (!isset($goodsIds) || empty($goodsIds)) {
            return $this->error(400, '没有商品ID');
        }

        $cartObj = KaluliShoppingCartTable::getInstance()->createQuery('m')
            ->select()
            ->where('m.hupu_uid = ?', $uid)
            ->whereIn('m.goods_id', $goodsIds)
            ->orderBy('created_at desc')
            ->execute();

        if (count($cartObj) < 1) {
            return $this->error(401, '购物车中没有对应商品');
        }

        //购物车商品ids 初始化商品信息 $goodsArr子商品详细信息 $productArr主商品详细信息
        $goods_ids = $product_ids = $goodsArr = $productArr = $marketing_list = array();

        $this->getCartData($cartObj, $goods_ids, $product_ids, $goodsArr, $productArr);
        foreach($cartObj as $m=>$n) {
            //是否已售罄

            $result[$m]['total_num'] = $goodsArr[$n->getGoodsId()]['total_num'];
            $result[$m]['number'] = $n->getNumber();
            if ($goodsArr[$n->getGoodsId()]['status'] == 1 || $productArr[$n->getProductId()]['status'] == 4) {
                return $this->error("500",'有商品下架，请重新调整购物车');
            } elseif ($goodsArr[$n->getGoodsId()]['total_num'] == 0) {
                return $this->error("500",'有商品库存不足，请重新调整购物车');
            } else {
                $result[$m]['status'] = 0;//正常
            }

            //是否超过库存
            if ($result[$m]['total_num'] < $result[$m]['number']) {
                return $this->error("500",'有商品库存不足，请重新调整购物车');
            } else {
                $result[$m]['stock_status'] = 0;//正常
            }
        }

        return $this->success();

    }
}