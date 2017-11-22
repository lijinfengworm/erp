<?php
/**
 * Created by PhpStorm.
 * User: libin
 * Date: 2016/12/19
 * Time: 下午3:18
 * 活动专属服务类
 */
class activityKaluliService extends kaluliService {

    //判断活动存在
    public function executeCheckActivity(){
        $uid = $this->getRequest()->getParameter("uid");
        $itemId = $this->getRequest()->getParameter("itemId");
        if(empty($itemId)) {
           return $this->error(500,"参数错误");
        }

        $return  = $this->getItemActivity($itemId);
        if($return['code'] == 201) {
            return $this->success(array(),$return['code']);
        }
        $itemActivity = $return['itemActivity'];
        $activity = $return['activity'];
        if(empty($uid)) {
            return  $this->success(["itemActivity"=>$itemActivity->toArray(),"activity"=>$activity->toArray()],203,"用户未登录"); //用户未登录返回203
        }
        //判断用户是否达到购买数量
        $number = KllXbuyItemLogTable::getInstance()->createQuery()->select("sum(number) as num")->where("uid = ?",$uid)->andWhere("activity_id =?",$activity->getId())
            ->andWhere("item_id = ?",$itemId)->fetchOne();
        $num = $number->num;
        if($num >= $itemActivity->getNumber()) {
         return   $this->success(["itemActivity"=>$itemActivity->toArray(),"activity"=>$activity->toArray()],202,"超过最大购买数量"); //超过购买数量返回202
        }

       return $this->success(["itemActivity"=>$itemActivity->toArray(),"activity"=>$activity->toArray()]);//满足条件返回活动数据

    }

    //判断购买数量
    public function executeCheckNumber() {
        $num = $this->getRequest()->getParameter("num");
        $itemId =$this->getRequest()->getParameter("id");
        $uid    = $this->getRequest()->getParameter("uid");
        if(empty($num) || empty($itemId)) {
            return $this->error(500,"参数错误");
        }
        $return = $this->getItemActivity($itemId);
        if($return['code'] == 201) {
            return $this->success(array(),$return['code']);
        }
        //用户id不存在和最大数量进行比较
        if(empty($uid)) {
            if($num > $return['itemActivity']['number']) {
                return $this->success(array(),202); //超过最大购买数量返回202
            }
            return $this->success();
        }
        $itemActivity = $return['itemActivity'];
        $activity = $return['activity'];
        //获取用户已购买数量
        $number = KllXbuyItemLogTable::getInstance()->createQuery()->select("sum(number) as num")->where("uid = ?",$uid)->andWhere("activity_id =?",$activity->getId())
            ->andWhere("item_id = ?",$itemId)->fetchOne();
        $buyNum = $number->num;
        if(($buyNum + $num) >$return['itemActivity']['number'] ){
            return $this->success(array(),202);
        }
        return $this->success();
    }

    public function executeCheckNotPay(){
        $itemId = $this->getRequest()->getParameter("id");
        $uid = $this->getRequest()->getParameter("uid");
        if(empty($itemId) || empty($uid)) {
            return $this->error(500,"参数错误");
        }
        //查找订单
        $bind = array();
        $bind['is_count'] = true;
        $bind['select'] = 'count(id) as num';
        $bind['where'][] = "hupu_uid = ".$uid;
        $bind['where'][] = "product_id = ".$itemId;
        $bind['where'][] = "pay_status = 0 ";
        $bind['where'][] = "status = 0";
        $bind['where'][] = "is_activity != 0";
        $order = KaluliOrderTable::getAll($bind);
        if($order > 0) {
            return $this->success(array(),204);
        }
        return $this->success();
    }


    /**
     * 获取活动
     * @param $itemId
     * @return array
     */
    public function getItemActivity($itemId) {
        //获取活动代码
        $itemActivity = KllXbuyItemTable::getInstance()->findOneByItemIdAndStatus($itemId,1);
        if(!$itemActivity) {
            return ['code'=>201]; //返回值201活动商品不存在活动
        }
        //验证活动是否过期
        $activityId = $itemActivity->getActivityId();
        $activity = KllXbuyTable::getInstance()->findOneByIdAndStatus($activityId,1);
        if(!$activity) {
            return ['code'=>201];//返回值201活动商品不存在活动
        }
        $currentTime = time();
        if($currentTime > strtotime($activity->getEndTime())) {
            //已经大于活动时间,将活动停止
            $db = Doctrine_Manager::getInstance()->getConnection('kaluli');
            $db->beginTransaction();
            try {
                $activity->setStatus(2);
                $activity->save();
                $itemActivitys = KllXbuyItemTable::getInstance()->createQuery()->where("activity_id = ?", $activityId)->fetchArray();
                foreach ($itemActivitys as $k => $v) {
                    $data = KllXbuyItemTable::getInstance()->find($v['id']);
                    $data->setStatus(0);
                    $data->save();
                }
                $db->commit();
            }catch(Exception $e) {
                $db->rollback();
                $this->error($e->getCode(),$e->getMessage());
            }
            return  ['code'=>201]; //活动下线返回201
        }else if ($currentTime < strtotime($activity->getStartTime())) {
            return  ['code'=>201]; //活动未开始,返回201
        } else {
            return ['code' => 200, "itemActivity" => $itemActivity, "activity" => $activity];
        }
    }


    public function executeActivityCountDown() {
        $itemId = $this->getRequest()->getParameter("itemId");
        //获取活动代码
        $itemActivity = KllXbuyItemTable::getInstance()->findOneByItemIdAndStatus($itemId,1);
        if(!$itemActivity) {
            return $this->error("500","活动不存在"); //返回值201活动商品不存在活动
        }
        //验证活动是否过期
        $activityId = $itemActivity->getActivityId();
        $activity = KllXbuyTable::getInstance()->findOneByIdAndStatus($activityId,1);
        if(!$activity) {
            return $this->error("500","商品不存在");//返回值201活动商品不存在活动
        }
        $currentTime = time();
        //判断是否差别24小时
        $startTime = strtotime($activity->getStartTime());
        $diffTime = $startTime-$currentTime;
        if($diffTime > 0 && $diffTime < 60*60*24 ) {
                $return = [];
                $startDay = date("d",$startTime);
                $currentDay = date("d",$currentTime);
                if($startDay != $currentDay) {
                    $return['countDown'] = "明日".date("H:i",$startTime);
                } else {
                    $return['countDown'] = "今日".date("H:i",$startTime);
                }
                $return['activityPrice'] = $itemActivity->getPrice();
                $return['link'] = $activity->getDetailUrl();
                $return['time'] = $diffTime;
                return $this->success($return);
        }
    }

}