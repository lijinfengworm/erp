<?php

/**
 * 营销活动类
 * version:1.0
 */
class marketingTradeService extends tradeService
{
    /**
     *根据商品的id获取活动
     * @param int $product_id 商品id
     */
    public function executeGetDetailByid()
    {
        $product_id = $this->getRequest()->getParameter('product_id');
        $merchant = $this->getRequest()->getParameter('merchant', '');

        if (!$product_id) {
            return $this->error(400, '参数错误');
        }
        $return = array();

        $redis = sfContext::getInstance()->getDatabaseConnection('kaluliRedis');
        $redis->select(1);
        $data = $redis->get('shihuo_marketing_activity_' . $product_id . md5($merchant));
        if ($data) {
            $return = unserialize($data);
        } else {
            //获取全场活动
            $audience = TrdMarketingActivityTable::getMarketingAudience();

            if (!empty($audience)) {
                $return = $audience;
            }

            //获取商品所属的活动
            $belongs = TrdMarketingActivityGroupTable::getMarketingBelongs($product_id);

            $act_ids = array();
            if (!empty($belongs)) {
                foreach ($belongs as $k => $v) {
                    array_push($act_ids, $v['activity_id']);
                }

                //根据活动id获取活动详情
                $belongs_act = TrdMarketingActivityTable::getMarketingByIds($act_ids);
                if (!empty($belongs_act)) {//排除是美亚自营活动的费美亚自营商品
                    foreach ($belongs_act as $act_k => $act_v) {
                        if ($act_v['type'] == 3 && $merchant == 'Amazon.com') {
                            continue;
                        } elseif ($act_v['type'] == 3 ) {
                            unset($belongs_act[$act_k]);
                        }
                    }
                }

                if (!empty($belongs_act)) {
                    if (!empty($return)) {
                        $return = array_merge($return, $belongs_act);
                    } else {
                        $return = $belongs_act;
                    }
                }
            }

            if (!empty($return)) {
                foreach ($return as $k => $v){
                    $return[$k]['modeName'] = $this->getModeIntro($v['mode']);
                }
            }
            $redis->set('shihuo_marketing_activity_' . $product_id. md5($merchant), serialize($return), 60);
        }
        return $this->success(array('data' => $return));
    }

    /**
     *
     * 获取符合活动详情
     * @param array goods_info 子商品结合信息
     * as:
     * $serviceRequest->setMethod('marketing.GetMarketingInfo');
    $serviceRequest->setVersion('1.0');
    $goods_info = array(
        '0'=>array('product_id'=>43531,'goods_id'=>38804,'price'=>1253.69),
        '1'=>array('product_id'=>68283,'goods_id'=>419103,'price'=>250.74),
        '2'=>array('product_id'=>65946,'goods_id'=>406851,'price'=>182.78),
    );
    $serviceRequest->setApiParam('goods_info', $goods_info);
    $response = $serviceRequest->execute();
     */
    public function executeGetMarketingInfo()
    {
        $goods_info = $this->getRequest()->getParameter('goods_info');
        $type = $this->getRequest()->getParameter('type', 0);//为1只要活动列表，不需要活动详情

        if (empty($goods_info)) {
            return $this->error(400, '参数错误');
        }

        $return = array(
            'activity' => array(),
            'goods_info' => array(),
            'activity_save' => 0
        );

         $activity = $product_ids = array();

        foreach ($goods_info as $k=> $v) {
            array_push($product_ids, $v['product_id']);
        }

        $act_ids = $item = $activity_format = array();

        //获取全场活动
        $audience = TrdMarketingActivityTable::getMarketingAudience();

        if (!empty($audience)) {
            $activity = $audience;
            foreach ($product_ids as $k => $v) {
                $item[$v] = $audience;
            }
        }

        //获取商品所属的活动
        $belongs = TrdMarketingActivityGroupTable::getMarketingBelongs($product_ids);

        if (!empty($belongs)) {
            foreach ($belongs as $k => $v) {
                array_push($act_ids, $v['activity_id']);
            }

            $manzu_activity = array();

            //根据活动id获取活动详情
            $belongs_act = TrdMarketingActivityTable::getMarketingByIds($act_ids);
            if (!empty($belongs_act)) {//排除是美亚自营活动的费美亚自营商品
                foreach ($belongs_act as $act_k => $act_v) {
                    if ($act_v['type'] == 3) {
                        $flag = $this->checkAct($act_v['id'], $belongs, $goods_info);
                        if ($flag) $manzu_activity[] = $belongs_act[$act_k];
                    } else {
                        $manzu_activity[] = $belongs_act[$act_k];
                    }
                }
            }

            if (!empty($manzu_activity)) {
                if (!empty($activity)) {
                    $activity = array_merge($activity, $manzu_activity);
                } else {
                    $activity = $manzu_activity;
                }
            }

            //格式化
            foreach ($activity as $k=>$v) {
                $activity_format[$v['id']] = $v;
            }

            //每个product商品对应的活动
            foreach ($belongs as $k => $v) {
                if (isset($activity_format[$v['activity_id']])) {
                    $item[$v['item_id']][] = $activity_format[$v['activity_id']];
                }
            }
        }

        //判断活动是否满足
        if (!empty($activity)) {
            //每个goods商品对应的活动
            foreach ($goods_info as $k => $v) {
                $activity_array = array();
                if (isset($item[$v['product_id']])) {
                    foreach ($item[$v['product_id']] as $kk => $vv) {
                        if ($vv['type'] == 3 && $v['merchant'] != 'Amazon.com') {
                            continue;
                        } else {
                            $activity_array[] = $vv;
                        }
                    }
                    $goods_info[$k]['activity'] = $activity_array;
                } else {
                    $goods_info[$k]['activity'] = array();
                }
            }

            if ($type == 1) {
                //根据活动类型组合数组
                $activity_new = array();
                foreach ($activity as $k => $v) {
                    $v['collectFlag'] = true;
                    if ($v['type'] == 3) {
                        $k_type = 2;
                    } else {
                        $k_type = $v['type'];
                    }
                    $activity_new[$k_type]['list'][] = $v;
                    $activity_new[$k_type]['collectFlag'] = true;
                }
                //判断是否需要凑单
                foreach ($goods_info as $k => $v) {
                    foreach ($v['activity'] as $kk => $vv) {
                        $goods_info[$k]['activity'][$kk]['collectFlag'] = true;
                        $goods_info[$k]['collectFlag'] = true;
                    }
                }
                $return['activity'] = $activity_new;
                $return['goods_info'] = $goods_info;
                $return['activity_save'] = 0;
            } else {
                $return = $this->getMeetActivity($activity, $goods_info);
            }
        }

        return $this->success(array('data' => $return));
    }

    //判断活动是否满足
    private function getMeetActivity($activity, $goods_info)
    {
        $return = $res =  array();
        $amazon_act = array();
        if (empty($activity)) return false;
        //格式化活动
        foreach ($activity as $k => $v) {
            $res[$v['mode']][] = $v;
        }
        if (isset($res[4])){//美亚折扣 特殊
            if (count($res[4]) > 1) {
                foreach ($res[4] as $m=>$n) {
                    array_push($amazon_act, $n['attr2']);
                }
                $pos = array_search(max($amazon_act), $amazon_act);
                $max_dis =  $res[4][$pos];
            } else {
                $max_dis =  $res[4][0];
            }
            unset($res[4]);
            $res[4] = $max_dis;
        }

        $mode_attr2 = array();
        if (isset($res[1])) {//识货满减
            foreach ($res[1] as $k => $v){
                $mode_attr2[1][] = $v['attr2'];
            }
            array_multisort($mode_attr2[1], SORT_DESC, $res[1]);
        }
        if (isset($res[2])) {//识货满减比例
            foreach ($res[2] as $k => $v){
                $mode_attr2[2][] = $v['attr2'];
            }
            array_multisort($mode_attr2[2], SORT_ASC, $res[2]);
        }
        if (isset($res[3])) {//识货折扣活动
            foreach ($res[3] as $k => $v){
                $mode_attr2[3][] = $v['attr2'];
            }
            array_multisort($mode_attr2[3], SORT_ASC, $res[3]);
        }

        $activity_save = 0;
        $activity_meet_ids = array();//满足的活动id

        //计算活动优惠
        if ($res) {
            if (isset($res[4])) {
                foreach ($goods_info as $k => $v) {
                    $goods_info[$k]['platform_dis_price'] = $v['price'];
                    foreach ($v['activity'] as $kk => $vv) {
                        if ($vv['id'] == $res[4]['id']) {//满足亚马逊折扣
                            $goods_info[$k]['activity'][$kk]['platformFlag'] = true;
                            $goods_info[$k]['platform_dis_price'] = ceil($v['price'] * $res[4]['attr2'])/ 100;
                            $goods_info[$k]['marketing_fee'] = $v['price'] - $goods_info[$k]['platform_dis_price'];
                            $activity_save += $v['price'] - $goods_info[$k]['platform_dis_price'];//节省的钱
                            array_push($activity_meet_ids, $vv['id']);
                        } else {
                            $goods_info[$k]['activity'][$kk]['platformFlag'] = false;
                        }
                    }
                }
            }

            if (isset($res[3])) {//识货折扣活动
                $total_product_price = 0;
                $attr2 = 0;//识货活动优惠金额
                foreach ($res[3] as $m => $n) {
                    $mode_attr2 = $this->checkMeetActivity($n, $goods_info);//商品是否满足此活动
                    if ($mode_attr2) {
                        foreach ($goods_info as $k => $v) {
                            $price = isset($v['platform_dis_price']) ? $v['platform_dis_price'] : $v['price'];
                            foreach ($v['activity'] as $kk => $vv) {
                                if ($vv['id'] == $n['id']) {//满足折扣活动
                                    $goods_info[$k]['activity'][$kk]['shihuoFlag'] = true;
                                    $goods_info[$k]['shihuo_dis_price'] = ceil($price * $n['attr2'])/ 100;
                                    $attr2 += $price - $goods_info[$k]['shihuo_dis_price'];
                                    $activity_save += $price - $goods_info[$k]['shihuo_dis_price'];//
                                    $total_product_price += $goods_info[$k]['shihuo_dis_price'];
                                    array_push($activity_meet_ids, $vv['id']);
                                } else {
                                    $goods_info[$k]['activity'][$kk]['shihuoFlag'] = false;
                                }
                            }
                        }
                        $goods_info = $this->getSaveGoodsInfo($attr2, $n['id'], $total_product_price, $goods_info);
                        break;
                    }
                }
            }

            if (isset($res[2])) {//识货满减比例
                $total_product_price = 0;
                $attr2 = 0;//识货活动优惠金额
                foreach ($res[2] as $m => $n) {
                    $mode_attr2 = $this->checkMeetActivity($n, $goods_info);//商品是否满足此活动
                    if ($mode_attr2) {
                        foreach ($goods_info as $k => $v) {
                            $price = isset($v['platform_dis_price']) ? $v['platform_dis_price'] : $v['price'];
                            $price = isset($v['shihuo_dis_price']) ? $v['shihuo_dis_price'] : $price;
                            foreach ($v['activity'] as $kk => $vv) {
                                if ($vv['id'] == $n['id']) {//满足满减比例
                                    $goods_info[$k]['activity'][$kk]['shihuoFlag'] = true;
                                    $goods_info[$k]['shihuo_dis_price'] = ceil($price * $n['attr2'])/ 100;
                                    $attr2 += $price - $goods_info[$k]['shihuo_dis_price'];
                                    $activity_save += $price - $goods_info[$k]['shihuo_dis_price'];//节省的钱
                                    $total_product_price += $goods_info[$k]['shihuo_dis_price'];
                                    array_push($activity_meet_ids, $vv['id']);
                                } else {
                                    $goods_info[$k]['activity'][$kk]['shihuoFlag'] = isset($goods_info[$k]['activity'][$kk]['shihuoFlag']) ? $goods_info[$k]['activity'][$kk]['shihuoFlag'] : false;
                                }
                            }
                        }
                        $goods_info = $this->getSaveGoodsInfo($attr2, $n['id'], $total_product_price, $goods_info);
                        break;
                    }
                }
            }

            if (isset($res[1])) {//识货满减
                $total_product_price = 0;
                foreach ($res[1] as $m => $n) {
                    $mode_attr2 = $this->checkMeetActivity($n, $goods_info);//商品是否满足此活动
                    if ($mode_attr2) {
                        $activity_save += $n['attr2'];
                        foreach ($goods_info as $k => $v) {
                            $price = isset($v['platform_dis_price']) ? $v['platform_dis_price'] : $v['price'];
                            $price = isset($v['shihuo_dis_price']) ? $v['shihuo_dis_price'] : $price;
                            foreach ($v['activity'] as $kk => $vv) {
                                if ($vv['id'] == $n['id']) {//满足满减
                                    $goods_info[$k]['activity'][$kk]['shihuoFlag'] = true;
                                    //$goods_info[$k]['shihuo_dis_price'] = $price - $n['attr2'];
                                    $total_product_price += $price;
                                    array_push($activity_meet_ids, $vv['id']);
                                } else {
                                    $goods_info[$k]['activity'][$kk]['shihuoFlag'] = isset($goods_info[$k]['activity'][$kk]['shihuoFlag']) ? $goods_info[$k]['activity'][$kk]['shihuoFlag'] : false;
                                }
                            }
                        }
                        $goods_info = $this->getSaveGoodsInfo($n['attr2'], $n['id'], $total_product_price, $goods_info);
                        break;
                    }
                }
            }

        }
        
        if ($activity_meet_ids) {
            $activity_meet_ids = array_unique($activity_meet_ids);
        }

        foreach ($activity as $k => $v) {
            if (in_array($v['id'], $activity_meet_ids)) {
                $activity[$k]['flag'] = true;
            } else {
                $activity[$k]['flag'] = false;
            }
        }

        $activity_new = array();
        //根据活动类型组合数组
        if ($activity) {
            foreach ($activity as $k => $v) {
                $v['collectFlag'] = false;
                if (!$v['flag']) {
                    $v['collectFlag'] = true;
                }
                if ($v['type'] == 3) {
                    $k_type = 2;
                } else {
                    $k_type = $v['type'];
                }
                $activity_new[$k_type]['list'][] = $v;
                if ($v['collectFlag']) {
                    $activity_new[$k_type]['collectFlag'] = true;
                } elseif (!isset($activity_new[$k_type]['collectFlag'])){
                    $activity_new[$k_type]['collectFlag'] = false;
                }
            }
        }


        //判断是否需要凑单
        foreach ($goods_info as $k => $v) {
            foreach ($v['activity'] as $kk => $vv) {
                $goods_info[$k]['activity'][$kk]['collectFlag'] = true;
                if ((isset($vv['shihuoFlag']) && $vv['shihuoFlag']) || (isset($vv['platformFlag']) && $vv['platformFlag'])) {
                    $goods_info[$k]['activity'][$kk]['collectFlag'] = false;
                }
                if ($goods_info[$k]['activity'][$kk]['collectFlag']) {
                    $goods_info[$k]['collectFlag'] = true;
                } elseif (!isset($goods_info[$k]['collectFlag'])){
                    $goods_info[$k]['collectFlag'] = false;
                }
            }
        }

        $return['activity'] = $activity_new;
        $return['goods_info'] = $goods_info;
        $return['activity_save'] = $activity_save;
        return $return;
    }

    //商品是否满足某一种活动
    private function checkMeetActivity($activity, $goods_info) {
        if (empty($activity) || empty($goods_info)) return false;
        $total_price = 0;
        $price = 0;
        if ($activity['mode'] == 1) {
            foreach ($goods_info as $k => $v) {
                foreach ($v['activity'] as $kk => $vv) {
                    if ($vv['id'] == $activity['id']) {//满足满减活动
                        $price = isset($v['platform_dis_price']) ? $v['platform_dis_price'] : $v['price'];
                        $price = isset($v['shihuo_dis_price']) ? $v['shihuo_dis_price'] : $price;
                        $total_price += $price;
                        if ($total_price >= $activity['attr1']) {
                            return true;
                            break;
                        }
                    }
                }
            }
        } elseif ($activity['mode'] == 2) {
            foreach ($goods_info as $k => $v) {
                foreach ($v['activity'] as $kk => $vv) {
                    if ($vv['id'] == $activity['id']) {//识货满减比例
                        $price = isset($v['platform_dis_price']) ? $v['platform_dis_price'] : $v['price'];
                        $price = isset($v['shihuo_dis_price']) ? $v['shihuo_dis_price'] : $price;
                        $total_price += $price;
                        if ($total_price >= $activity['attr1']) {
                            return true;
                            break;
                        }
                    }
                }
            }
        } elseif ($activity['mode'] == 3) {
            return true;
        }


        return false;

    }

    //冒泡排序
    private function getSortArray($data)
    {
        for ($i = 1; $i < count($data); $i++) {
            for ($j = count($data) - 1; $j >= $i; $j--) {
                if ($data[$j] < $data[$j - 1]) {
                    $temp = $data[$j - 1];
                    $data[$j - 1] = $data[$j];
                    $data[$j] = $temp;
                }
            }
        }
        return $data;
    }

    //格式化活动类型显示名称
    private function getModeIntro($mode)
    {
        if ($mode == 1) {
            return "识货优惠";
        } elseif ($mode == 2) {
            return "识货满折";
        } elseif ($mode == 3) {
            return "识货折扣";
        } elseif ($mode == 4) {
            return "美亚折扣";
        }
    }

    //查看是否有自营商品满足活动
    private function checkAct($act_id, $belongs, $goods_info) {
        $product_id = array();
        foreach ($belongs as $K => $v) {
            if ($act_id == $v['activity_id']) {
                array_push($product_id,$v['item_id']);
            }
        }

        if ($product_id) {
            foreach ($goods_info as $k => $v) {
                if (in_array($v['product_id'], $product_id) && $v['merchant'] == 'Amazon.com') {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * 计算每个商品识货活动优惠金额
     * @param $attr2 总共优惠金额
     * @param $activity_id 当前满足的活动id
     * @param $total_product_price 总的商品价格
     * @param $goods_info 商品数组
     * @return array 商品数组
     */
    private function getSaveGoodsInfo($attr2, $activity_id, $total_product_price, $goods_info){
        $total_marketing_fee= 0;
        if ($total_product_price > 0) {
            $pre_price = ceil($attr2 * 10000 / $total_product_price) / 10000;
            foreach ($goods_info as $k => $v) {
                foreach ($v['activity'] as $kk => $vv) {
                    if ($vv['id'] == $activity_id) {//满足活动
                        $price = isset($v['platform_dis_price']) ? $v['platform_dis_price'] : $v['price'];
                        $price = isset($v['shihuo_dis_price']) ? $v['shihuo_dis_price'] : $price;
                        $marketing_fee = ceil($price * $pre_price * 100) / 100;
                        if ($total_marketing_fee + $marketing_fee >= $attr2){
                            $marketing_fee = $attr2 - $total_marketing_fee;
                        }
                        $total_marketing_fee += $marketing_fee;
                        if ($vv['mode'] == 1){//如果是满减
                            $goods_info[$k]['shihuo_dis_price'] = $price - $marketing_fee;
                        }
                        $goods_info[$k]['marketing_fee'] = isset($goods_info[$k]['marketing_fee']) ? ($goods_info[$k]['marketing_fee'] + $marketing_fee) : $marketing_fee;
                    }
                }
            }
        }
        return $goods_info;
    }
}
