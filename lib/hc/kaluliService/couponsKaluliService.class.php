<?php

class couponsKaluliService extends kaluliService
{
    # 领取优惠券
    public function executeReceive()
    {
     // $connection = Doctrine_Manager::getInstance()->getConnection('trade');
        try
        {
            # 公用部分
            $uid = $this->user->getAttribute('uid');
            $uname = $this->user->getAttribute('username');
            $activity_id = $this->request->getParameter('id');
            $mobile = $this->request->getParameter('mobile','');
            $data = array();
            if( empty($uid) )
            {
                throw new Exception('未登录！',1);
            }
            if( empty($activity_id) )
            {
                throw new Exception('参数有误！',2);
            }

            # 加锁5秒
            $statusLock = KaluliFun::getLock('kaluli_get_coupon_id_' . $activity_id, 5);
            if ( $statusLock[0]['status'] < 1 )
            {
                throw new Exception('亲，排队抢购用户过多，请重试',3);
            }

            $activityObj = KllActivityTable::getInstance()->createQuery()->select('total,limits,recevied,start_date,expiry_date')->where('id = ?',$activity_id)->andWhere('is_delete = ?',0)->fetchOne();
            if ( empty($activityObj) )
            {
                throw new Exception('亲，您这是要干啥？',3);
            }
            if( !in_array($activityObj->root_type,array(0,1,2)) )
            {
                throw new Exception('亲，类型有问题哦？',3);
            }

            $nowtime = time();
            if ( $nowtime < strtotime($activityObj->getStartDate()) )
            {
                throw new Exception('亲，兑换活动未开始',3);
            }
            if ( $nowtime > strtotime($activityObj->getExpiryDate()) )
            {
                throw new Exception('亲，兑换活动已结束了',3);
            }
            if ( $activityObj->getRecevied() >= $activityObj->getTotal() )
            {
                throw new Exception('亲，您下手慢了，被别人兑换光了！',3);
            }

            # 以下是限制用户领取数量验证
            $couponscount = KllCouponsReceviedTable::getInstance()->createQuery()->where('activity_id = ?',$activity_id)->andWhere('hupu_uid = ?',$uid)->count();

            if ( $activityObj->getLimits() <= $couponscount )
            {
                throw new Exception('亲，您已经兑换过了',3);
            }

            # 公用部分

            # 事务
            if( $activityObj->root_type == 0 )
            {
                # 优惠券
                $receviedObj = new KllCouponsRecevied();
                $youhuiquan = KllCouponsDetailTable::getInstance()->createQuery()->where('activity_id = ?',$activityObj->id)->andWhere('status = ?',0)->orderby('id asc')->limit(1)->fetchOne();

                if ( !empty($youhuiquan) )
                {
                    $receviedObj->setStime($youhuiquan->stime);
                    $receviedObj->setEtime($youhuiquan->etime);
                    $youhuiquan->setStatus(1);
                    $youhuiquan->save();//更新奖品状态
                    $receviedObj->setDetailId($youhuiquan->getId());
                    $receviedObj->setAccount($youhuiquan->getAccount());
                }
                else
                {
                    throw new Exception('礼品没有库存啦',3);
                }

                # 更新优惠券信息到received表
                $receviedObj->setActivityId($activityObj->getId());
                $receviedObj->setHupuUid($uid);
                $receviedObj->setStatus(1);
                $receviedObj->setHupuUsername($uname);
                $receviedObj->setReceviedDate($nowtime);
                $receviedObj->save();

                # 更新老礼品表
                if(!empty($couponsListObj))
                {
                    $recevie_num = $couponsListObj->getRecevied() + 1;
                    $couponsListObj->setRecevied($recevie_num);
                    $couponsListObj->save();//更新奖品表
                }
            }
            elseif( $activityObj->root_type == 1 )
            {
                # 礼品卡
                $lipinka = KllCouponsReceviedTable::getInstance()->createQuery()->select('account')->where('activity_id = ?',$activityObj->id)->andWhere('root_type = ?',1)->andWhere('status = ?',0)->orderby('id asc')->limit(1)->fetchOne();
                if( empty($lipinka) )
                {
                    throw new Exception('礼品没有库存啦',3);
                }
                # 绑定礼品卡
                $client = new kaluliServiceClient();
                $client->setMethod('lipinka.bind');
                $client->setVersion('1.0');
                $client->setUserToken($_COOKIE['u']);
                $client->setApiParam('card',$lipinka->account);

                $return = $client->execute();
                if( true === $return->hasError() )
                {
                    throw new Exception($return->getMsg(),$return->getStatusCode());
                }
                $serviceCard = $return->getData();
                # 更新礼品卡
                $lipinka->setStime($serviceCard['data']['stime']);
                $lipinka->setEtime($serviceCard['data']['etime']);
                $lipinka->setHupuUid($uid);
                $lipinka->setHupuUsername($uname);
                $lipinka->setReceviedDate($nowtime);
                $lipinka->setStatus(1);
                $lipinka->save();
            }
            else
            {
                throw new Exception('类型出错',33);
            }

            # 公共部分
            # 更新活动表领取数量
            $recevie_num_act = $activityObj->getRecevied() + 1;
            $activityObj->setRecevied($recevie_num_act);
            $activityObj->save();//更新活动表

            $message = array(
                'message' => '卡路里优惠券领用数量更新',
                'param' => array('recevied' => $recevie_num_act, 'activity_id' => $activityObj->id),
                'res' => array(),
            );
            kaluliLog::info('kaluli_coupons', $message);

            KaluliFun::releaseLock('kaluli_get_coupon_id_' . $activity_id);

            # 成功返回内容
            if( $activityObj->root_type == 0 )
            {
                # 优惠券部分
                $data = array(
                    'status'=>0,
                    'type'=>0,
                    'data'=> array('account'=>$youhuiquan->getAccount(),'mart'=>$activityObj->getMart() ? $activityObj->getMart() : '商城','url'=>"http://go.shihuo.cn/u?url=".urlencode($activityObj->getReceiveUrl() . '?utm_source=ZMC_DtSzcmxsc')),
                    'msg'=>'兑换成功！礼品已发到个人中心！',
                );
            }
            elseif( $activityObj->root_type == 1 )
            {
                $data = array(
                    'status'=>0,
                    'type'=>1,
                    'data'=> array('amount'=>$serviceCard['data']['amount']),
                    'msg'=>"1张 识货{$serviceCard['data']['amount']}元礼品卡",
                );
                if($activityObj->getReceiveUrl())
                {
                    $data['data']['url'] = $activityObj->getReceiveUrl();
                }
            }
            else
            {
                $data = array(
                    'status'=>0,
                    'type'=>2,
                    'data'=> array(),
                    'msg'=>"恭喜！领取成功！",
                );
            }
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

    # 兑换礼品卡接口
    public function executeDuihuan()
    {
        try
        {
            $account = $this->request->getParameter('account');
            $user_id = $this->request->getParameter('user_id','');
            if(empty($account))
            {
                throw new Exception('缺少参数',402);
            }
            $uid = $user_id ? $user_id : $this->getUser()->getAttribute('uid');
            $username = $this->getUser()->getAttribute('username');
            if(empty($uid))
            {
                throw new Exception('缺少用户ID',500);
            }

 
            $client = new kaluliServiceClient();
 
            $client->setMethod('lipinka.bind');
            $client->setVersion('1.0');
            $client->setUserToken($_COOKIE['u']);
            $client->setApiParam('card',$account);
            $serviceData = $client->execute();

            if( false === $serviceData->hasError() )
            {
                $tt = $serviceData->getData();
                $_account = $tt['data']['account'];
                # 更新同步状态
//                $card = TrdCouponsReceviedTable::getInstance()->createQuery()->where('account =?',$_account)->andWhere('root_type =1')->fetchOne();
//                if( empty($card) )
//                {
//                    $card = new TrdCouponsRecevied();
//                    $card->setRootType(1);
//                    $card->setAccount($_account);
//                    //todo 礼品卡id
//                    $card->setListId($tt['data']['lipinka_id']);
//                }
//                $card->setStime($tt['data']['stime']);
//                $card->setEtime($tt['data']['etime']);
//                $card->setHupuUid($uid);
//                $card->setHupuUsername($username);
//                $card->setReceviedDate(time());
//                $card->setStatus(1);
//                $card->save();
//
//
//                # 日志更新
//                $history = new TrdAccountHistory();
//                $history->setActionid(0);
//                $history->setHupuUid($uid);
//                $history->setHupuUsername($username);
//                $history->setCategory(2);
//                $history->setType(4);
//                $history->setExplanation("兑换礼品卡");
//                $history->save();
              
                return $this->success($data['msg'] = '兑换成功');
            }
            else
            {
                throw new Exception($serviceData->getMsg(),$serviceData->getStatusCode());
            }
        }
        catch(Exception $ex)
        {
            return $this->error($ex->getCode(), $ex->getMessage());
        }
    }

    # 优惠券列表
    public function executeList()
    {
        try
        {
            $page = $this->request->getParameter("page",1);
            $type = $this->request->getParameter('type',null);
            $pageSize = (int)$this->request->getParameter('pageSize');
            if(empty($pageSize)) $pageSize = 30;
            $data['list'] = KllActivityTable::getActivity($page,$pageSize,$type);
            if( empty($data['list']) )
            {
                throw new Exception('数据为空',502);
            }
            $data['pageSize'] = $pageSize;

            return $this->success($data);
        }
        catch(Exception $ex)
        {
            return $this->error($ex->getCode(), $ex->getMessage());
        }
    }

    # 优惠券同步状态
    public function executeReceivedByBackend()
    {
        try
        {
            $card = $this->request->getParameter("card");
            if( empty($card) || ! $card instanceof KaluliLipinkaCard )
            {
                throw new Exception('参数类型错误',402);
            }

            $row = KllCouponsReceviedTable::getInstance()
                ->createQuery()
                ->where('root_type = 1')
                ->where('account = ?',$card->getAccount())
                ->fetchOne();
            if(!empty($row)) return false;
            $row = new KllCouponsRecevied();
            $row->root_type = 1; 
            $row->list_id = $card->getLipinkaId();
            $row->account = $card->getAccount();
            $row->stime = $card->getStime();
            $row->etime = $card->getEtime();
            $row->hupu_uid = $card->getUserId();
            $row->card_limit = $card->getCardLimit();
            $userinfo  = TrdAccountTable::getByHupuId($card->getUserId());
            if(!empty($userinfo)) $row->hupu_username = $userinfo->hupu_username;
            $row->recevied_date = time();
            $row->status = 1;
            $row->save();

            return $this->success();
        }
        catch(Exception $ex)
        {
            return $this->error($ex->getCode(), $ex->getMessage());
        }
    }

    /**
     * 我的礼品列表
     * @return array
     */
    public function executeMyCoupons()
    {
        $v = $this->getRequest()->getParameter('version');
        $hupuUid =  $this->getUser()->getAttribute('uid');
        $hupuUname = $this->getUser()->getAttribute('username');
        if (empty($hupuUid) || !is_numeric($hupuUid)) {
            return $this->error(501, '未登录');
        }

        $type = $this->getRequest()->getParameter('type', null);
        $status = $this->getRequest()->getParameter('status', 'now');
        if (null !== $type && ('' !== $type)) {
            if (!in_array($type, array(0, 1))) {
                return $this->error(400, '参数错误');
            }
        }
        if (!in_array($status, array('now', 'over', 'use'))) {
            return $this->error(400, '参数错误');
        }

        $page = $this->getRequest()->getParameter('page', 1);
        $pageSize = $this->getRequest()->getParameter('pageSize', 20);
        if ($pageSize > 100) $pageSize = 100;
        if (!is_numeric($page) || (int) $page < 1) {
            return $this->error(400, '参数错误');
        }
        if (!is_numeric($pageSize) || (int) $pageSize < 1) {
            return $this->error(400, '参数错误');
        }
        try {
            $coupons = KllCouponsReceviedTable::getMyCouponsList($hupuUid, $type, $status, $page, $pageSize);
            return $this->success(array('list' => $coupons));
        } catch(Exception $e) {
            return $this->error(500, '系统错误');
        }
    }

    # 首页优惠券
    public function executeFrontpage()
    {
        try
        {
            $uid = $this->user->getAttribute('uid');
            $redis = sfContext::getInstance()->getDatabaseConnection('kaluliRedis');
            $redis->select(10);
            # 优惠券
            $coupons_key = 'kaluli.index.coupons';
            $this->coupons = $this->userCoupons = $frontActivityIds = array();
            $redisCouponData = unserialize($redis->get($coupons_key));
            if(empty($redisCouponData) || !is_array($redisCouponData))
            {
                throw new Exception('数据为空',502);
            }

            $couponsIds = array_keys($redisCouponData);
            if($couponsIds)  $coupons = KllActivityTable::getActivitys($couponsIds);
            if(!empty($coupons))
            {
                foreach($coupons as $v)
                {
                    # 绑定描述内容
                    if(!empty($redisCouponData[$v['id']]))
                    {
                        $this->coupons[] = $redisCouponData[$v['id']];
                        $frontActivityIds[] = $v['id'];
                    }
                }

                if(empty($this->coupons))
                {
                    throw new Exception('没有匹配的活动',504);
                }

                # 获取用户已经领取的优惠券
                if( !empty($uid) )
                {
                    $tmp = KllCouponsReceviedTable::getCouponsStatusByUser($uid,$frontActivityIds);

                    $this->userCoupons = $tmp['actitvity_ids'];
                    if(!empty($this->userCoupons))
                    {
                        foreach($this->coupons as $k=>$v)
                        {
                            if(in_array($v['id'],$this->userCoupons))
                            {
                                $this->coupons[$k]['received'] = true;
                            }
                        }
                    }
                }

                $data['list'] =  $this->coupons;
                return $this->success($data);
            }
            else
            {
                throw new Exception('没有正在进行中的活动',503);
            }
        }
        catch(Exception $ex)
        {
            return $this->error($ex->getCode(), $ex->getMessage());
        }
    }
} 