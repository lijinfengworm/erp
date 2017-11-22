<?php

class couponsTradeService extends tradeService
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
            $statusLock = tradeCommon::getLock('get_coupon_id_' . $activity_id, 5);
            if ( $statusLock[0]['status'] < 1 )
            {
                throw new Exception('亲，排队抢购用户过多，请重试',3);
            }

            $activityObj = TrdActivityTable::getInstance()->createQuery()->select('exchange_type,total,limits,recevied,integral,gold,start_date,expiry_date')->where('id = ?',$activity_id)->andWhere('is_delete = ?',0)->fetchOne();
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
            if( in_array($activityObj->root_type,array(0,1)) )
            {
                $couponscount = TrdCouponsReceviedTable::getInstance()->createQuery()->where('activity_id = ?',$activity_id)->andWhere('hupu_uid = ?',$uid)->count();
            }
            else
            {
                # 实物领取 验证
                $couponscount = TrdGoodsAddressTable::getInstance()->createQuery()->where('activity_id = ?',$activity_id)->andWhere('hupu_uid = ?',$uid)->count();
            }
            if ( $activityObj->getLimits() <= $couponscount )
            {
                throw new Exception('亲，您已经兑换过了',3);
            }

            if( $activityObj->root_type == 2 )
            {
                # 领取实物表单验证
                $goods = $this->request->getParameter('goods');

                if( empty($goods) )
                {
                    if ( $activityObj->exchange_type == 0 )
                    {
                        $t =  $activityObj->integral.' 积分 + '.$activityObj->gold.' 金币';
                    }
                    elseif ($activityObj->exchange_type == 2)
                    {
                        $t = $activityObj->integral.' 积分';
                    }
                    else if ($activityObj->exchange_type == 3)
                    {
                        $t = $activityObj->gold.' 金币';
                    }
                    else if ($activityObj->exchange_type == 4)
                    {
                        $t = '免费领取';
                    }
                    $data['data'] = array(
                        'title'     =>$activityObj->title,
                        'limits'    =>1,
                        'duihuan'   =>$t
                    );
                    throw new Exception('亲，请填写信息',10);
                }
                if( empty($goods['name']) || empty($goods['tel']) || empty($goods['province']) || empty($goods['city']) || empty($goods['address']) )
                {
                    throw new Exception('亲，联系信息貌似少填了什么哦',3);
                }
                $goods['tel'] = (int)$goods['tel'];
            }


            # 公用部分
            #验证金币积分兑换条件
            $accountObj = TrdAccountTable::getInstance()->createQuery()->where('hupu_uid = ?',$uid)->limit(1)->fetchOne();
            if ( empty($accountObj) )
            {
                $accountObj = new TrdAccount();
                $accountObj->setHupuUid($uid);
                $accountObj->setHupuUsername($uname);
                $accountObj->save();
            }
            $integral = $accountObj->getIntegral();
            $gold = $accountObj->getGold();
            if ( $activityObj->getExchangeType() == 0 )
            {
                if ( $integral < $activityObj->getIntegral() || $gold < $activityObj->getGold() )
                {
                    throw new Exception('您不满足兑换条件，加油吧，少年',3);
                }
            }
            elseif( $activityObj->getExchangeType() == 1 )
            {
                if ( $integral < $activityObj->getIntegral() && $gold < $activityObj->getGold() )
                {
                    throw new Exception('您不满足兑换条件，加油吧，少年',3);
                }
            }
            elseif( $activityObj->getExchangeType() == 2 )
            {
                if ( $integral < $activityObj->getIntegral() )
                {
                    throw new Exception('您不满足兑换条件，加油吧，少年',3);
                }
            }
            elseif( $activityObj->getExchangeType() == 3 )
            {
                if ( $gold < $activityObj->getGold() )
                {
                    throw new Exception('您不满足兑换条件，加油吧，少年',3);
                }
            }


            # 事务

            if( $activityObj->root_type == 0 )
            {
                # 优惠券
                if ( $activityObj->getType() == 2 && empty($mobile) )
                {
                    throw new Exception('请输入手机号码领取',4);
                }
                $couponsListObj = TrdCouponsListTable::getInstance()->createQuery()->where('id = ?',$activityObj->getListId())->andWhere('is_delete = ?',0)->limit(1)->fetchOne();
                $receviedObj = new TrdCouponsRecevied();
                if( $activityObj->getType() == 2 )
                {
                    //记录手机号码 兑换实物礼品
                    if( !empty($couponsListObj) )
                    {
                        $recevie_num = $couponsListObj->getRecevied() + 1;
                        $couponsListObj->setRecevied($recevie_num);
                        $couponsListObj->save();
                    }
                    $receviedObj->setMobile($mobile);
                }
                else
                {
                    //获取劵码
                    if( $activityObj->getListId() )
                    {
                        # 老优惠券领取方式
                        $youhuiquan = TrdCouponsDetailTable::getInstance()->createQuery()->select('account,pass')->where('list_id = ?',$activityObj->getListId())->andWhere('status = ?',0)->orderby('id asc')->limit(1)->fetchOne();
                    }
                    else
                    {
                        $youhuiquan = TrdCouponsDetailTable::getInstance()->createQuery()->where('activity_id = ?',$activityObj->id)->andWhere('status = ?',0)->orderby('id asc')->limit(1)->fetchOne();
                    }

                    if ( !empty($youhuiquan) )
                    {
                        if( $activityObj->getListId() )
                        {
                            # 兼容老优惠券
                            if(empty($couponsListObj))
                            {
                                throw new Exception('优惠券出错啦',3);
                            }
                            $receviedObj->setStime(strtotime($couponsListObj->start_date));
                            $receviedObj->setEtime(strtotime($couponsListObj->expiry_date));
                        }
                        else
                        {
                            $receviedObj->setStime($youhuiquan->stime);
                            $receviedObj->setEtime($youhuiquan->etime);
                        }

                        $youhuiquan->setStatus(1);
                        $youhuiquan->save();//更新奖品状态
                        $receviedObj->setDetailId($youhuiquan->getId());
                        $receviedObj->setAccount($youhuiquan->getAccount());
                        if ($youhuiquan->getPass()) $receviedObj->setPass($youhuiquan->getPass());
                    }
                    else
                    {
                        throw new Exception('礼品没有库存啦',3);
                    }
                }

                # 更新优惠券信息到received表
                $receviedObj->setActivityId($activityObj->getId());
                $receviedObj->setListId($activityObj->getListId());
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
                $lipinka = TrdCouponsReceviedTable::getInstance()->createQuery()->select('account,pass')->where('activity_id = ?',$activityObj->id)->andWhere('root_type = ?',1)->andWhere('status = ?',0)->orderby('id asc')->limit(1)->fetchOne();
                if( empty($lipinka) )
                {
                    throw new Exception('礼品没有库存啦',3);
                }
                # 绑定礼品卡
                $client = new tradeServiceClient();
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
                //todo xss处理
                # 实物领取

                $goodModel = new TrdGoodsAddress();
                $goodModel->setActivityId($activityObj->id);
                $goodModel->setHupuUid($uid);
                $goodModel->setHupuUsername($uname);
                $goodModel->setName($goods['name']);
                $goodModel->setTel($goods['tel']);
                $goodModel->setProvince($goods['province']);
                $goodModel->setCity($goods['city']);
                $goodModel->setAddress($goods['address']);
                if(!empty($goods['note']))
                {
                    $goodModel->setNote($goods['note']);
                }
                $goodModel->save();
                # 同步到大杂烩表
                $receviedObj = new TrdCouponsRecevied();
                $receviedObj->setRootType(2);
                $receviedObj->setActivityId($activityObj->id);
                $receviedObj->setStatus(1);
                $receviedObj->setAccount($goodModel->getId());
                $receviedObj->setEtime(strtotime('2038-01-01 00:00:00'));
                $receviedObj->setReceviedDate(time());
                $receviedObj->setHupuUid($uid);
                $receviedObj->setHupuUsername($uname);
                $receviedObj->save();
            }

            # 公共部分
            # 更新活动表领取数量
            $recevie_num_act = $activityObj->getRecevied() + 1;
            $activityObj->setRecevied($recevie_num_act);
            $activityObj->save();//更新活动表

            $message = array(
                'message' => '优惠券领用数量更新',
                'param' => array('recevied' => $recevie_num_act, 'activity_id' => $activityObj->id),
                'res' => array(),
            );
            tradeLog::info('shihuo_coupons', $message);

            $beforeIntegral = $accountObj->getIntegral();
            $beforeGold     = $accountObj->getGold();
            # 扣除对应积分金币
            if ( $activityObj->getExchangeType() == 0 )
            {
                $integral_num = $integral - $activityObj->getIntegral();
                $gold_num = $gold - $activityObj->getGold();
                $accountObj->setIntegral($integral_num);
                $accountObj->setGold($gold_num);
            }
            elseif( $activityObj->getExchangeType() == 1 )
            {
                $integral_num = $integral - $activityObj->getIntegral();
                $gold_num = $gold - $activityObj->getGold();
                $accountObj->setIntegral($integral_num);
                $accountObj->setGold($gold_num);
            }
            elseif( $activityObj->getExchangeType() == 2 )
            {
                $integral_num = $integral - $activityObj->getIntegral();
                $accountObj->setIntegral($integral_num);
            }elseif( $activityObj->getExchangeType() == 3 )
            {
                $gold_num = $gold - $activityObj->getGold();
                $accountObj->setGold($gold_num);
            }
            $afterIntegral = $accountObj->getIntegral();
            $afterGlod     = $accountObj->getGold();
            $accountObj->save();
            # 更新积分日志表
            $accountHistoryObj = new TrdAccountHistory();
            if ( $activityObj->getExchangeType() == 0 )
            {
                $integral_num = $activityObj->getIntegral();
                $gold_num = $activityObj->getGold();
                $accountHistoryObj->setIntegral($integral_num);
                $accountHistoryObj->setGold($gold_num);
            }
            elseif( $activityObj->getExchangeType() == 1 )
            {
                $integral_num = $activityObj->getIntegral();
                $gold_num = $activityObj->getGold();
                $accountHistoryObj->setIntegral($integral_num);
                $accountHistoryObj->setGold($gold_num);
            }
            elseif( $activityObj->getExchangeType() == 2 )
            {
                $integral_num = $activityObj->getIntegral();
                $accountHistoryObj->setIntegral($integral_num);
            }
            elseif( $activityObj->getExchangeType() == 3 )
            {
                $gold_num = $activityObj->getGold();
                $accountHistoryObj->setGold($gold_num);
            }
            $accountHistoryObj->setBeforeIntegral($beforeIntegral);
            $accountHistoryObj->setBeforeGold($beforeGold);
            $accountHistoryObj->setAfterIntegral($afterIntegral);
            $accountHistoryObj->setAfterGold($afterGlod);
            $accountHistoryObj->setActionid($activity_id);
            $accountHistoryObj->setHupuUid($uid);
            $accountHistoryObj->setHupuUsername($uname);
            $accountHistoryObj->setCategory(2);
            if ( $activityObj->getExchangeType() == 0 || $activityObj->getExchangeType() == 2 )
            {
                $type = 1;
            }
            elseif( $activityObj->getExchangeType() == 3 )
            {
                $type = 3;
            }
            else
            {
                $type = 4;
            }
            $accountHistoryObj->setType($type);
            $accountHistoryObj->setExplanation("兑换了 ".$activityObj->getTitle());
            $accountHistoryObj->save();



            tradeCommon::releaseLock('get_coupon_id_' . $activity_id);

            # 成功返回内容
            if( $activityObj->root_type == 0 )
            {
                # 优惠券部分
                if ( $activityObj->getType() == 2 && $mobile )
                {
                    $data = array(
                        'status'=>0,
                        'type'=>0,
                        'data'=> array(),
                        'msg'=>'兑换成功，客服稍后将会和您确认送货信息！',
                    );
                }
                elseif( $activityObj->getType() == 0 )
                {
                    $data = array(
                        'status'=>0,
                        'type'=>0,
                        'data'=> array('account'=>$youhuiquan->getAccount(),'pass'=>$youhuiquan->getPass(),'mart'=>$activityObj->getMart() ? $activityObj->getMart() : '商城','url'=>"http://go.shihuo.cn/u?url=".urlencode($activityObj->getReceiveUrl() . '?utm_source=ZMC_DtSzcmxsc')),
                        'msg'=>'兑换成功！礼品已发到个人中心！',
                    );
                }
                else
                {
                    $data = array(
                        'status'=>0,
                        'type'=>0,
                        'data'=> array(),
                        'msg'=>'兑换成功！礼品已发到个人中心！',
                    );
                }
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

            $db = Doctrine_Manager::getInstance()->getConnection('trade');
            $db->beginTransaction();

            $client = new tradeServiceClient();
            $client->setMethod('lipinka.bind');
            $client->setVersion('1.0');
            $client->setApiParam('card',$account);
            $client->setApiParam('user_id', $uid);
            $serviceData = $client->execute();

            if( false === $serviceData->hasError() )
            {
                $tt = $serviceData->getData();
                $_account = $tt['data']['account'];
                # 更新同步状态
                $card = TrdCouponsReceviedTable::getInstance()->createQuery()->where('account =?',$_account)->andWhere('root_type =1')->fetchOne();
                if( empty($card) )
                {
                    $card = new TrdCouponsRecevied();
                    $card->setRootType(1);
                    $card->setAccount($_account);
                    //todo 礼品卡id
                    $card->setListId($tt['data']['lipinka_id']);
                }
                $card->setStime($tt['data']['stime']);
                $card->setEtime($tt['data']['etime']);
                $card->setHupuUid($uid);
                $card->setHupuUsername($username);
                $card->setReceviedDate(time());
                $card->setStatus(1);
                $card->save();


                # 日志更新
                $history = new TrdAccountHistory();
                $history->setActionid(0);
                $history->setHupuUid($uid);
                $history->setHupuUsername($username);
                $history->setCategory(2);
                $history->setType(4);
                $history->setExplanation("兑换礼品卡");
                $history->save();
                $db->commit();
                return $this->success($data['msg'] = '兑换成功');
            }
            else
            {
                throw new Exception($serviceData->getMsg(),$serviceData->getStatusCode());
            }
        }
        catch(Exception $ex)
        {
            $db->rollback();
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
            $data['list'] = TrdActivityTable::getActivity($page,$pageSize,$type);
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
            if( empty($card) || ! $card instanceof TrdLipinkaCard )
            {
                throw new Exception('参数类型错误',402);
            }

            $row = TrdCouponsReceviedTable::getInstance()
                ->createQuery()
                ->where('root_type = 1')
                ->where('account = ?',$card->getAccount())
                ->fetchOne();
            if(!empty($row)) return false;
            $row = new TrdCouponsRecevied();
            $row->root_type = 1;
            $row->type = 1;
            $row->list_id = $card->getLipinkaId();
            $row->account = $card->getAccount();
            $row->stime = $card->getStime();
            $row->etime = $card->getEtime();
            $row->hupu_uid = $card->getUserId();
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
            if (!in_array($type, array(0, 1, 2))) {
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
            $coupons = TrdCouponsReceviedTable::getMyCouponsList($hupuUid, $type, $status, $page, $pageSize);
            return $this->success(array('list' => $coupons));
        } catch(Exception $e) {
            return $this->error(500, '系统错误');
        }
    }
} 