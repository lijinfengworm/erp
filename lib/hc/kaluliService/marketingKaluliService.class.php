<?php

/**
 * 营销活动类
 * version:1.0
 */
class marketingKaluliService extends kaluliService
{
    /**
     *根据商品的id获取活动
     * @param int $product_id 商品id
     */
    public function executeGetDetailByid()
    {
        $product_id = $this->getRequest()->getParameter('product_id');

        $isDetail = $this->getRequest()->getParameter('isDetail',false);

        if (!$product_id) {
            return $this->error(400, '参数错误');
        }
        $return = array();

        $redis = sfContext::getInstance()->getDatabaseConnection('kaluliRedis');
        $redis->select(1);
        $data = $redis->get('kaluli_marketing_activity_' . $product_id);
        $data = [];
        if ($data) {
            $data = unserialize($data);
        } else {
            //获取全场活动
            $audience = KllMarketingActivityTable::getMarketingAudience();

            //获取商品所属的活动
            $belongs = KllMarketingActivityGroupTable::getMarketingBelongs($product_id);

            $act_ids = array();
            if (!empty($belongs)) {
                foreach ($belongs as $k => $v) {
                    array_push($act_ids, $v['activity_id']);
                }

                //根据活动id获取活动详情
                $belongs_act = KllMarketingActivityTable::getMarketingByIds($act_ids);

                if (!empty($belongs_act)) {
                    if (!empty($audience)) {
                        $return = array_merge($audience, $belongs_act);
                    } else {
                        $return = $belongs_act;
                    }
                }
            }

            if (!empty($return)) {
                foreach ($return as $k => $v){
                    $return[$k]['modeName'] = $this->getModeIntro($v['mode']);
                    $sortTmp[] = $v['attr1'];
                }
                array_multisort($return,SORT_DESC,$sortTmp);
            }

            $data['data'] = $return;

            if(!empty($return) && $isDetail === true)
            {
                $activitys = $maxEndtime = $urls = array();
                $isAudience = $isList = false;
                $aIds = '';
                foreach($return as $v)
                {
                    if($isAudience == false && $v['scope'] == 1)
                    {
                        $isAudience = true;
                    }
                    elseif($v['scope'] == 2)
                    {
                        $aIds .= $v['id'].',';
                    }
                    $title = $modeName = '';
                    $mode = $discount = 0;
                    if(!empty($v['attr1']) && !empty($v['attr2']))
                    {
                        if(empty($modeName))
                        {
                            $activitys['modeName'] = $v['modeName'];
                        }
//                        if($v['mode'] == 1)
//                        {
//                            $title = "满{$v['attr1']}减{$v['attr2']}";
//                        }elseif($v['mode'] == 2)
//                        {
//                            $v['attr2'] = $v['attr2']/10;
//                            $title = "满{$v['attr1']}件打{$v['attr2']}折";
//                        }
                        # 若有详情链接，就优先展示
                        if(empty($v['url']))
                        {
                            $isList = true;
                        }
                        else
                        {
                            $urls[] = $v['url'];
                        }
                        if($v['mode'] == 1 || $v['mode'] == 2 || $v['mode'] == 3)
                        {
                            $activitys['data'][$v['id']] = $v['intro'];
                            if($v['type'])
                            {
                                $maxEndtime[] = $v['etime'];
                            }
                        }
                        $mode = $v['mode'];
                        $discount = round($v["attr2"]/10, 1);
                    }
                }
                if($isList === false)
                {
                    $urls = array_unique($urls);
                    if(count($urls) == 1)
                    {
                        $activitys['url'] = $urls[0];
                    }
                }

                $detail['aIds'] = $aIds = trim($aIds,',');

                $detail['activitys'] = $activitys;
                $detail['endTime'] = !empty($maxEndtime)?max($maxEndtime)-time():0;

                $detail['discount_rate'] = $discount;
                $detail['mode'] = $mode;
                if($isList === false)
                {
                    $urls = array_unique($urls);
                    if(count($urls) == 1)
                    {
                        $activity_url = $urls[0];
                    }
                }
                if(!empty($activity_url))
                {
                    $url = $activity_url;
                    $murl = $activity_url;
                }
                else
                {
                    sfProjectConfiguration::getActive()->loadHelpers('Url');
                    if($isAudience === true)
                    {
                        $url = url_for('item/list?aIds=all&filter=1');
                        $murl ='https://m.kaluli.com/item/activity?id='.$aIds;
                    }else{
                        if(!empty($aIds))
                        {
                            $url = url_for('item/list?aIds='.$aIds);
                            $murl ='https://m.kaluli.com/item/activity?id='.$aIds;
                        }
                        else
                        {
                            $url = '';
                        }
                    }
                }
                $detail['url'] = $url;
                $detail['murl'] = $murl;
                $data['detail'] = $detail;

            }
            else
            {
                $data['detail'] = array();
            }
            $redis->set('kaluli_marketing_activity_' . $product_id, serialize($data), 86400);
        }

        return $this->success($data);
    }
    //检查同一个商品是否在进行中的活动
    public function executeCheckActivity(){
        ini_set('memory_limit', '-1');
        $flag = 1;
        $group_id = $this->getRequest()->getParameter('group_id');
        $stime = $this->getRequest()->getParameter('stime');
        $etime = $this->getRequest()->getParameter('etime');
        $redis = sfContext::getInstance()->getDatabaseConnection('kaluliRedis');
        $redis->select(9);

        $activity_group_id = $redis->sMembers('kll.activity.goods.'.$group_id);
        
        $on = KllMarketingActivityTable::getInstance()->findByStatus(3);
        $items = [];
       
        foreach ($on as $key => $proc) {
            $group = $proc->getGroupId();
            $item_tmp = KllMarketingActivityGroupTable::getInstance()->findByActivityId($group);
            $item_tmp_array = $redis->sMembers('kll.activity.goods.'.$group);
            $intersection = array_intersect($item_tmp_array, $activity_group_id);
            if(!empty($intersection)){
                $item_stime = $proc->getStime();
                $item_etime = $proc->getEtime();
                $cross = FunBase::isTimeCross($stime, $etime, (int)$item_stime, (int)$item_etime);
                if($cross){
                    $flag = 0;
                }
            }

        }
        
        return $this->success(array('data' => $flag));
        

    }
    //获取活动详情
    public function executeGetActivity()
    {
        $goods_info = $this->getRequest()->getParameter('marketing_list');
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
        $audience = KllMarketingActivityTable::getMarketingAudience();

        if (!empty($audience)) {
            $activity = $audience;
            foreach ($product_ids as $k => $v) {
                $item[$v] = $audience;
            }
        }

        //获取商品所属的活动
        $belongs = KllMarketingActivityGroupTable::getMarketingBelongs($product_ids);

        if (!empty($belongs)) {
            foreach ($belongs as $k => $v) {
                array_push($act_ids, $v['activity_id']);
            }

            //根据活动id获取活动详情
            $belongs_act = KllMarketingActivityTable::getMarketingByIds($act_ids);

            if (!empty($belongs_act)) {
                if (!empty($activity)) {
                    $activity = array_merge($activity, $belongs_act);
                } else {
                    $activity = $belongs_act;
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
                if (isset($item[$v['product_id']])) {
                    $goods_info[$k]['activity'] = $item[$v['product_id']];
                } else {
                    $goods_info[$k]['activity'] = array();
                }
            }

            if ($type == 1) {
                
                //根据活动类型组合数组
                $activity_new = array();
                foreach ($activity as $k => $v) {
                    $v['collectFlag'] = true;
                    $k_type = $v['mode'];
                    $activity_new[$k_type]['list'][] = $v;
                    $activity_new[$k_type]['collectFlag'] = true;
                }
                //判断是否需要凑单
                foreach ($goods_info as $k => $v) {
                    foreach ($v['activity'] as $kk => $vv) {
                        $flag = $this->checkMeetActivity($vv, $goods_info);
                        
                        $goods_info[$k]['activity'][$kk]['collectFlag'] = $flag;
                        $goods_info[$k]['collectFlag'] = $flag;
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
        $mode_attr2 = array();
        if (isset($res[1])) {//卡路里满减
            foreach ($res[1] as $k => $v){
                $mode_attr2[] = $v['attr2'];
            }
            array_multisort($mode_attr2, SORT_DESC, $res[1]);
            $mode_attr2 = array();
        }
        if (isset($res[2])) {//卡路里买几件打折
            foreach ($res[2] as $k => $v){
                $mode_attr2[] = $v['attr2'];
            }
            array_multisort($mode_attr2, SORT_ASC, $res[2]);
            $mode_attr2 = array();
        }
        if (isset($res[3])) {//卡路里单间打折
            foreach ($res[3] as $k => $v){
                $mode_attr2[] = $v['attr2'];
            }
            array_multisort($mode_attr2, $res[3]);
        }
        // array_multisort($mode_attr2, SORT_ASC,$mode_attr2);
        $activity_save = 0;
        $activity_meet_ids = array();//满足的活动id
        $activity_save_array = array();//每种活动省的钱

        //计算活动优惠
        if ($res) {
            if (isset($res[1])) {//卡路里满减
                $total_product_price = 0;
                foreach ($res[1] as $m => $n) {

                    $mode_attr2 = $this->checkMeetActivity($n, $goods_info);//商品是否满足此活动
                    if ($mode_attr2) {
                        $activity_save += $n['attr2'];
                        foreach ($goods_info as $k => $v) {
                            foreach ($v['activity'] as $kk => $vv) {
                                if ($vv['id'] == $n['id']) {//满足满减
                                    $goods_info[$k]['activity'][$kk]['flag'] = true;
                                    $total_product_price +=  $goods_info[$k]['price'];
                                    array_push($activity_meet_ids, $vv['id']);
                                } 
                            }
                        }
                        $activity_save_array[1] = $n['attr2'];
                        $goods_info = $this->getSaveGoodsInfo($n['attr2'], $n['id'], $total_product_price, $goods_info);
                        break;
                    }
                }
            }

            if (isset($res[2])) {//卡路里满几件打折
                $total_product_price = 0;
                $attr2 = 0;

                foreach ($res[2] as $m => $n) {
                    $mode_attr2 = $this->checkMeetActivity($n, $goods_info);//商品是否满足此活动
                    
                    if ($mode_attr2) {
                        foreach ($goods_info as $k => $v) {
                            $price = isset($v['dis_price']) ? $v['dis_price'] : $v['price'];
                            foreach ($v['activity'] as $kk => $vv) {
                                if ($vv['id'] == $n['id']) {//满足满几件打折
                                    $goods_info[$k]['activity'][$kk]['flag'] = true;
                                    $goods_info[$k]['kaluli_proportion_price'] = ceil($price * $n['attr2']) / 100;
                                    $attr2 += $price - $goods_info[$k]['kaluli_proportion_price'];
                                    $activity_save += $price - $goods_info[$k]['kaluli_proportion_price'];//节省的钱
                                    $total_product_price += isset($goods_info[$k]['dis_price']) ? $goods_info[$k]['dis_price'] : $goods_info[$k]['price'];
                                    $goods_info[$k]['marketing_fee'] = ($price - $goods_info[$k]['kaluli_proportion_price']);
                                    array_push($activity_meet_ids, $vv['id']);
                                }
                            }
                        }
                        $activity_save_array[2] = $attr2;
                       // $goods_info = $this->getSaveGoodsInfo($attr2, $n['id'], $total_product_price, $goods_info);
                    }
                }
            }
            
            if (isset($res[3])) {//卡路里单件打折
                $total_product_price = 0;
                $attr2 = 0;
                foreach ($res[3] as $m => $n) {
                    $mode_attr2 = $this->checkMeetActivity($n, $goods_info);//商品是否满足此活动
                    
                    if ($mode_attr2) {

                        foreach ($goods_info as $k => $v) {
                            $price = isset($v['dis_price']) ? $v['dis_price'] : $v['price'];
                            foreach ($v['activity'] as $kk => $vv) {

                                if ($vv['id'] == $n['id']) {//满足满几件打折
                                    $goods_info[$k]['activity'][$kk]['flag'] = true;
                                    $goods_info[$k]['kaluli_proportion_price'] = ceil($price * $n['attr2']) / 100;
                                    $attr2 += $price - $goods_info[$k]['kaluli_proportion_price'];
                                    $activity_save += $price - $goods_info[$k]['kaluli_proportion_price'];//节省的钱
                                    $total_product_price += isset($goods_info[$k]['dis_price']) ? $goods_info[$k]['dis_price'] : $goods_info[$k]['price'];
                                    $goods_info[$k]['marketing_fee'] = ($price - $goods_info[$k]['kaluli_proportion_price']);
                                    array_push($activity_meet_ids, $vv['id']);
                                } 
                            }
                        }
                        $activity_save_array[2] = $attr2;
               //         $goods_info = $this->getSaveGoodsInfo($attr2, $n['id'], $total_product_price, $goods_info);
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
                $k_type = $v['mode'];
                $activity_new[$k_type]['list'][] = $v;
                $activity_new[$k_type]['activity_save'] = isset($activity_save_array[$k_type]) ? $activity_save_array[$k_type] : 0;
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
                if (isset($vv['flag']) && $vv['flag']) {
                    $goods_info[$k]['activity'][$kk]['collectFlag'] = false;
                }
                if (!isset($vv['flag'])) {
                    $goods_info[$k]['activity'][$kk]['flag'] = false;
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
        $total_number = 0;
        if ($activity['mode'] == 1) {
            foreach ($goods_info as $k => $v) {
                foreach ($v['activity'] as $kk => $vv) {
                    if ($vv['id'] == $activity['id']) {//满足满减活动
                        $total_price += $v['price'];
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
                        $total_number += $v['number'];
                        if ($total_number >= $activity['attr1']) {
                            return true;
                            break;
                        }
                    }
                }
            }
        }elseif ($activity['mode'] == 3) {
            foreach ($goods_info as $k => $v) {
                foreach ($v['activity'] as $kk => $vv) {
                    if ($vv['id'] == $activity['id']) {//识货满减比例
                        $total_number += $v['number'];
                        if ($total_number >= $activity['attr1']) {
                            return true;
                            break;
                        }
                    }
                }
            }
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
            return "满减";
        } elseif ($mode == 2) {
            return "折扣";
        }else{
            return "折扣";
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
     * 计算每个商品卡路里活动优惠金额
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
                        $price = $v['price'];
                        $marketing_fee = ceil($price * $pre_price * 100) / 100;
                        if ($total_marketing_fee + $marketing_fee >= $attr2){
                            $marketing_fee = $attr2 - $total_marketing_fee;
                        }
                        $total_marketing_fee += $marketing_fee;
                        $goods_info[$k]['marketing_fee'] = isset($goods_info[$k]['marketing_fee']) ? ($goods_info[$k]['marketing_fee'] + $marketing_fee) : $marketing_fee;
                        $goods_info[$k]['dis_price'] = isset($goods_info[$k]['dis_price']) ? ($goods_info[$k]['dis_price'] - $goods_info[$k]['marketing_fee']) : ($price - $goods_info[$k]['marketing_fee']);
                    }
                }
            }
        }
        return $goods_info;
    }
}
