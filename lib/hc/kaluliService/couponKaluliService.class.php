<?php
/**
 * 新的优惠券service
 */
class couponKaluliService extends kaluliService {
    
    /**
     * 获取配置好的，可用的优惠券,用户领取界面显示
     */
    public function executeGetCouponsIsUse()
    {
        $uid =  $this->request->getParameter('uid');
        $record_ids=$this->checkRecordId(''); //可用的送券配置
        if(!$record_ids)
        {
            return false;
        }
        $record_id=$record_ids[0]['record_id'];
        $arr_rid=  explode('|', trim($record_id));
        $record_info=array();
        $i=0;
        foreach($arr_rid as $k=>$v)
        {
            $coupon=$this->checkCouponByRecordId($v);   //card是否还有相关批次的券
            if($coupon)
            {
                $info=  KaluliLipinkaRecordTable::getInstance()->createQuery()->AndWhere('id= ? ',$v)->limit(1)->fetchOne();
                if($info)
                {
                    $i++;
                    $record_info[$i]['record_id']=$v;
                    $record_info[$i]['site_id']=$record_ids[0]['id'];
                    $record_info[$i]['card_limit_parse']=$this->_cardLimitToArr($info->getCard_limit());
                    $record_info[$i]['group_id']=$info->getGroup_id();
                    $record_info[$i]['amount']=($info->getAmount()) / ($info->getNum());
                    $record_info[$i]['count']=$this->getCountByRid($v, $uid);
                    //增加时间参数
                    $time = $info->getEtime();
                    if(empty($time)) {
                        $record_info[$i]['time'] = "有效期 ".$info->getPostponeDay()."天";
                    } else {
                        $record_info[$i]['time'] = "有效期至 ".date("Y-m-d",$info->getEtime());
                    }
                }
            }
        }

        return $record_info;
    }
    /**
     * 获取活动模板中的优惠券
     */
    public function executeGetCouponForTemplate(){
        $template_id = $this->request->getParameter("template_id");
        $uid = $this->request->getParameter("uid");
        $redis = sfContext::getInstance()->getDatabaseConnection('kaluliRedis');
        $redis->select(1);
       
        $coupon_serialize = $redis->get('kaluli_template_activity_' . $template_id);

        $coupon_list = unserialize($coupon_serialize);
        $record_info=array();
        $i=0;
        foreach($coupon_list as $k=>$v)
        {

            $coupon=$this->checkCouponByRecordId($v);   //card是否还有相关批次的券
            if($coupon)
            {
                $info=  KaluliLipinkaRecordTable::getInstance()->createQuery()->AndWhere('id= ? ',$v)->limit(1)->fetchOne();
                if($info)
                {
                    $i++;
                    $record_info[$i]['record_id']=$v;
                    $record_info[$i]['site_id']=0;
                    $record_info[$i]['card_limit_parse']=$this->_cardLimitToArr($info->getCard_limit());
                    $record_info[$i]['group_id']=$info->getGroup_id();
                    $record_info[$i]['amount']=($info->getAmount()) / ($info->getNum());
                    if(empty($uid)){
                        $record_info[$i]['count']=0;
                    }else{
                        $record_info[$i]['count']=$this->getCountByRid($v, $uid);
                        
                    }
                    //增加时间参数
                    $time = $info->getEtime();
                    if(empty($time)) {
                        $record_info[$i]['time'] = "有效期 ".$info->getPostponeDay()."天";
                    } else {
                        $record_info[$i]['time'] = "有效期至 ".date("Y-m-d",$info->getEtime());
                    }
                }
            }
        }
        return $record_info;
    }
    /**
     * 该批次是否兑换过
     * return $count兑换次数
     */
    private function getCountByRid($record_id,$uid)
    {
        $count=KllCouponsReceviedTable::getInstance()->createQuery()->where('record_id = ?',$record_id)->andWhere('hupu_uid = ?',$uid)->count();
        return $count;
    }
    //反解析限制规则
    private  function _cardLimitToArr($card_limit) {
        if(empty($card_limit)) return '';
        $card_limit_arr = explode(',',$card_limit);
        if(empty($card_limit_arr) || count($card_limit_arr) < 1) return '';
        $_return_arr = array();
        foreach($card_limit_arr as $k=>$v) {
            $_tmp = explode('=',$v);
            $_return_arr[$_tmp[0]] = $_tmp[1];
        }
        return $_return_arr;
    }    
    /**
     * 是否有可用的优惠券
     */
    private function checkCouponByRecordId($record_id)
    {
        $state=0;
        $now=time();
        $bind=[];
        $bind['record_id']='record_id='.$record_id;
        $bind['status']='status='.$state;
        $bind['stime']='stime <='.$now;
        $bind['etime']='etime >='.$now;
        $coupon= KaluliLipinkaCardTable::getInstance()->getAll(array('where'=>$bind,'limit'=>1));
        return $coupon;
    }
    
    /**
     * 是否有配置的批次号
     */
    private function checkRecordId($record_id)
    {
        $state=1;
        $now=time();
        $bind=[];
        if(!empty($record_id))
        {
            $bind['record_id']='record_id='.$record_id;
        }
        $bind['state']='state='.$state;
        $bind['s_time']='s_time <='.$now;
        $bind['e_time']='e_time >='.$now;
        $info=  KllSendCouponVipTable::getInstance()->getAll(array('where'=>$bind,'limit'=>1));
        return $info;
    }
    
    /**
     * 用户领取优惠券/data0/webroot/gitlab/kaluli-main-project/lib/hc/kaluliService/couponKaluliService.class.php
     */
    public function executeSendCoupon()
    {
        try
        {
            # 公用部分
            $uid =  $this->request->getParameter('uid');
            $uname =  $this->request->getParameter('uname');
            $record_id = $this->request->getParameter('record_id');
            $site_id = $this->request->getParameter('site_id');
            $data = array();
            if( empty($uid) || empty($uname) )
            {
                throw new Exception('未登录！',1);
            }
            if( empty($record_id) )
            {
                throw new Exception('参数有误！',2);
            }
            # 加锁5秒
            $statusLock = KaluliFun::getLock('kaluli_get_record_id_' . $record_id, 5);
            if ( $statusLock[0]['status'] < 1 )
            {
                throw new Exception('亲，排队抢购用户过多，请重试',3);
            }
            #检测批次号
            $record_info= KaluliLipinkaRecordTable::getInstance()->findOneBy('id', $record_id); //优惠券信息
            $coupon_site=  KllSendCouponVipTable::getInstance()->findOneBy('id',$site_id);      //优惠券配置信息
            $nowtime = time();
            if(!empty($coupon_site)){
                if(!$record_info || !$coupon_site)
                {
                    throw new Exception('亲，您这是要干啥？',3);
                }
                if($coupon_site->getState()==2) //配置已关闭
                {
                    throw new Exception('亲，兑换活动已结束了',3);
                }
               
                if ( $nowtime < ($record_info->getStime()) || $nowtime < ($coupon_site->getSTime()) )
                {
                    throw new Exception('亲，兑换活动未开始',3);
                }
                if ( $nowtime > ($record_info->getEtime()) || $nowtime > ($coupon_site->getETime())  )
                {
                    throw new Exception('亲，兑换活动已结束了',3);
                }
            }
            #是否兑换过
            $couponscount = $this->getCountByRid($record_id,$uid);;

            if ( $couponscount > 0 )
            {
                throw new Exception('亲，您已经兑换过了',3);
            }
            #检测券
            $receviedObj = new KllCouponsRecevied();
            $youhuiquan=  KaluliLipinkaCardTable::getInstance()->createQuery()->where('record_id = ?',$record_id)->andWhere('status = ?',0)->andWhere('stime <= ?',$nowtime)->andWhere('etime >= ?',$nowtime)->limit(1)->fetchOne();
            if(!empty($youhuiquan))
            {
                $receviedObj->setStime($youhuiquan->stime);
                $receviedObj->setEtime($youhuiquan->etime);
                $youhuiquan->setStatus(1);
                $youhuiquan->setUserId($uid);
                $youhuiquan->save();//更新奖品状态
                $receviedObj->setDetailId($youhuiquan->getId());
                $receviedObj->setAccount($youhuiquan->getAccount());
            }
            else
            {
                throw new Exception('优惠券没有库存啦',3);
            }
                # 更新优惠券信息到received表
                $receviedObj->setRecordId($record_id);
                $receviedObj->setHupuUid($uid);
                $receviedObj->setStatus(1);
                $receviedObj->setHupuUsername($uname);
                $receviedObj->setReceviedDate($nowtime);
                $receviedObj->save();
                
                #减锁
                KaluliFun::releaseLock('kaluli_get_record_id_' . $record_id);
                $data = array(
                    'status'=>1,
                    'data'=> array(),
                    'msg'=>"恭喜！领取成功！",
                );
            # 清除缓存
            $key = 'kaluli_index_coupons_status_uid_'.$uid;
            $redis = sfContext::getInstance()->getDatabaseConnection('kaluliRedis');
            $redis->select(1);
            $redis->del($key);
            return $this->success($data);
        }
        catch(Exception $e)
        {
            return $this->error($e->getCode(), $e->getMessage(),$data);
        }
    }
    
    /**
     * 订单送券--下单支付后，赠送优惠券
     */
    public function executeSendCouponByOrder()
    {
        $uid =  $this->request->getParameter('uid');
        $uname =  $this->request->getParameter('uname');
        $boo=false; ////是否成功赠送了优惠券
        try
        {
            if( empty($uid) || empty($uname) )
            {
                throw new Exception('未登录！',1);
            }
            $record_id=$this->getCouponSite();  ////获取启动的配置里面的批次号
            if(!$record_id)
            {
                throw new Exception('没有有效的配置！',2);
            }
            foreach($record_id as $k=>$v)   ////循环批次号--发券
            {
                #检测券
                $nowtime=time();
                $receviedObj = new KllCouponsRecevied();
                $youhuiquan=  KaluliLipinkaCardTable::getInstance()->createQuery()->where('record_id = ?',$v)->andWhere('status = ?',0)->andWhere('stime <= ?',$nowtime)->andWhere('etime >= ?',$nowtime)->limit(1)->fetchOne();
                if(!empty($youhuiquan))
                {
                    $receviedObj->setStime($youhuiquan->stime);
                    $receviedObj->setEtime($youhuiquan->etime);
                    $youhuiquan->setStatus(1);
                    $youhuiquan->setUserId($uid);
                    $youhuiquan->save();//更新奖品状态
                    $receviedObj->setDetailId($youhuiquan->getId());
                    $receviedObj->setAccount($youhuiquan->getAccount());
                    
                    # 更新优惠券信息到received表
                    $receviedObj->setRecordId($v);
                    $receviedObj->setHupuUid($uid);
                    $receviedObj->setStatus(1);
                    $receviedObj->setHupuUsername($uname);
                    $receviedObj->setReceviedDate($nowtime);
                    $receviedObj->save();

                    $boo=true;
                }
            }
            kaluliLog::info('Send_coupon_order_'.$uid,array('ok')); ////log记录送券的结果
             return $boo;
        }
        catch(Exception $e)
        {
            kaluliLog::info('Send_coupon_order_'.$uid,array($e->getMessage())); ////log记录送券的结果
            return $boo;
        }
    }
    /**
     * 订单送券--获取一个启动的有效配置
     * return array $record_id  该配置的批次号
     */
    private function getCouponSite()
    {
        $nowtime=time();
        $site=  KllSendCouponOrderTable::getInstance()->createQuery()->where('state = ?',1)->andWhere("position =?",1)->andWhere('s_time <= ?',$nowtime)->andWhere('e_time >= ?',$nowtime)->limit(1)->fetchOne();
        if($site)
        {
            $rid=$site->getRecordId();
            if($rid)
            {
                $record_id=explode('|', $rid);
                return $record_id;
            }
            return false;
        }
        return false;
    }
    
    #订单送券--优惠券剩余量达到预警--发送短信通知
    public  function executeSendMsg()
    {
        $position = $this->getRequest()->getParameter("position",1);
        $nowtime=time();
        $siteInfo=  KllSendCouponOrderTable::getInstance()->createQuery()->where('state = ?',1)->andWhere("position =?",$position)->andWhere('s_time <= ?',$nowtime)->andWhere('e_time >= ?',$nowtime)->limit(1)->fetchOne();

        $site=  KllSendMsgTable::getInstance()->findOneBy('id',$siteInfo->getId());
        if($site)
        {
            $boo=$this->check_nums($site->getNums());   ////是否达到警戒值
            $mobile=$site->getMobile();
            if((preg_match("/^[1][0-9]{10}$/", $mobile)) && $boo==true)
            {
                $kllMessage = new kllSendMessage();
                $kllMessage->send(array (
                    'phone' => $mobile,
                     'var' => array('mobile'=>strval($mobile)),
                     'tpl_id' => kllSendMessage::$_LIPINKA_REMIND_2,
                ));
                return true;
            }
            return false;
        }
        return false;
    }
    
    /**
     * 
     * @param type $nums 配置的警戒值
     * @return boolean
     */
    private function check_nums($nums)
    {
        $record_id=$this->getCouponSite();  ////获取一个有效配置
        if(!$record_id)
        {return false;}
        foreach($record_id as $k=>$v)
        {
            $where=[];
            $where['record_id']='record_id='.$v;    ////该有效配置的批次号剩余的优惠券数量
            $where['status']='status=0';    
            $count=KaluliLipinkaCardTable::getInstance()->getAll(array('where'=>$where,'select'=>'count(id) as num','limit'=>1,'is_count'=>1));
//            kaluliLog::info('check_nums',array($count,$nums)); ////log记录送券的结果
            if($count < $nums)
            {
                return true;
            }
        }
        return false;
    }

    /**
     * 判断用户是否领取过券
     */
    public function executeCheckNewCustomerCoupon() {
        $userId= $this->getRequest()->getParameter("userId");
        //判断已启动渠道,这个用户是否领过券
        $time = time();
        $info = KllActivityCouponTable::getInstance()->findOneByUserIdAndIsNew($userId,1);
        if($info) {
            $isReceive = 1;
        } else{
            $isReceive = 0;
        }
        return $this->success(['receive'=>$isReceive]);
    }
}