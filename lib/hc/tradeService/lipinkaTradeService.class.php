<?php

/**
 *   没错！ 这里就是礼品卡服务类
 *   维护人：梁天
 *   最新版本：1.0
 *   最后更新时间  2015-05-07
 *   创建时间  2015-04-27
 */
class lipinkaTradeService extends tradeService {

    private $errorCode = 400;


    private $userId = NULL;

    /**
     * 获取某个用户的礼品卡
        $serviceRequest = new tradeServiceClient();
        $serviceRequest->setMethod('lipinka.get.user');
        $serviceRequest->setVersion('1.0');
        $serviceRequest->setApiParam('user_id', 18244227);
        $serviceRequest->setApiParam('not_end_time', 1);
        $serviceRequest->setApiParam('is_start_time', 1);
        $serviceRequest->setApiParam('status', array(1));
        $serviceRequest->setUserToken($request->getCookie('u'));
     */
    public function executeGetUser() {
        $version = $this->getRequest()->getParameter('version','');
        //用户id
        $userId = $this->getRequest()->getParameter('user_id',$this->getUser()->getAttribute('uid'));
        //礼品卡状态
        $status = $this->getRequest()->getParameter('status');
        //是否不过期
        $not_end_time = $this->getRequest()->getParameter('not_end_time');
        //是否已开始
        $is_start_time = $this->getRequest()->getParameter('is_start_time');

        if(empty($userId)) {
            return $this->error(501, '未登录');
        }
        $bind = array();
        /*  根据用户id查找 */
        $bind['where']['user_id'] =  "user_id = " . (int)$userId;

        /*  根据过期时间查找  */
        if(!empty($not_end_time)) {
            $bind['where']['etime'] =  "etime > " . (int)time();
        }

        /*  根据开始时间查找  */
        if(!empty($is_start_time)) {
            $bind['where']['stime'] =  "stime < " . (int)time();
        }

        /* 根据类型查找 */
        if(!empty($status)) {
            $bind['whereIn']['status'] =  $status;
        }
        $data = TrdlipinkaCardTable::getAll($bind);
        return $this->success(array('total' => count($data), 'list' => $data),200,'ok');
    }

    /**
        绑定礼品卡
        $serviceRequest = new tradeServiceClient();
        $serviceRequest->setMethod('lipinka.bind');
        $serviceRequest->setVersion('1.0');
        $serviceRequest->setApiParam('user_id', 18244227);
        $serviceRequest->setApiParam('card', "hoMFydZjdK");
        $serviceRequest->setUserToken($request->getCookie('u'));
        $response = $serviceRequest->execute();
     */
    public  function executeBind() {
        $version = $this->getRequest()->getParameter('version','');
        $card = $this->getRequest()->getParameter('card','');
        $id = $this->getRequest()->getParameter('id','');
        $userId = $this->getRequest()->getParameter('user_id',$this->getUser()->getAttribute('uid'));
        //验证用户名
        if(empty($userId)) {
            return $this->error(501, '未登录');
        }
        //id 和 卡密必须传一个
        if(empty($id) && empty($card)) {
            return $this->error(407,'id和卡密必须传一个！');
        }
        $this->userId = $userId;

        //判断是大卡 还是小卡
        $_len = mb_strlen($card);
        if($_len == 6) {
            return $this->_bindLargeCard($card,$userId);
        } else {
            return $this->_bindLittleCard($id,$card,$userId);
        }
    }



    /**
     * 绑定大卡
     */
    public function _bindLargeCard($card = '',$userId) {
        /* 验证卡密 */
        if (!preg_match(TrdLipinkaCardTable::$cardLargeRegex, $card)) return $this->error(401,'卡号错误！');
        $largeData = TrdLipinkaLargeTable::getByCardOne($card,false);
        /* 判断是否存在 */
        if(empty($largeData)) return $this->error(402,'不存在！');
        /* 判断是否已经使用完了 */
        if($largeData->getNoReceive() <= 0 || $largeData->getStatus() == TrdLipinkaLarge::$STATUS_BEEN_USED) return $this->error(403,'全部使用完毕！');
        /* 判断是否过期 */
        if($largeData->getPostponeType() == TrdLipinkaRecord::$POSTPONE_BEFORE) {
            if(time() >=  $largeData->getEtime())  return $this->error(404,'礼品卡已过期！');
            if(time() <= $largeData->getStime())  return $this->error(405,'礼品卡还没开始！');
        }
        if($largeData->getPostponeType() == TrdLipinkaRecord::$POSTPONE_DYNAMIC) {
            $_time = $largeData->getOverdueTime();
            if(!empty($_time)) {
                if(time() > $_time) {
                    return $this->error(406, '该礼品卡已经超过了绑定期限！');
                }
            }
        }
        /* 判断当前用户是否绑定过  */
        if(TrdLipinkaCardTable::isLargeBand($largeData['id'],$userId)) {
            return $this->error(406,'您已经使用过了！');
        }
        /* 开始绑定  */
        $littleCard = TrdLipinkaCardTable::getLargeOne($largeData['id']);
        if(empty($littleCard)) return $this->error(402,'不存在！');
        $_little = $littleCard->toArray();

        /* 加锁   */
        $statusLock = tradeCommon::getLock('get_lipinka_large_id_' . $littleCard->getId(), 5);
        if ( $statusLock[0]['status'] < 1 ) {
            return $this->error(406,'同时领取该卡的人太多，稍后再试试吧！');
        }
        //标记已领取
        if($largeData->getPostponeType() == TrdLipinkaRecord::$POSTPONE_DYNAMIC) {
            $_day = ((int)$largeData->getPostponeDay() < 1) ? 1 : (int)$largeData->getPostponeDay();
            $_stime = time();
            $_etime = (int)($_stime + (86400 * $_day));
            $littleCard->setStime($_stime);
            $littleCard->setEtime($_etime);
            $_little['stime'] = $_stime;
            $_little['etime'] = $_etime;
        }
        $littleCard->setUserId($userId);
        $littleCard->setStatus(TrdLipinkaCardTable::$BIND_STATUS);
        $littleCard->save();
        //large  使用量增加1
        $_receive = (int)$largeData->getNoReceive()-1;
        $largeData->setNoReceive($largeData->getNoReceive()-1);
        if($_receive <= 0) {
            $largeData->setStatus(TrdLipinkaLarge::$STATUS_BEEN_USED);
        }
        $largeData->save();
        /* 解锁 */
        tradeCommon::releaseLock('get_lipinka_large_id_' . $littleCard->getId());
        return $this->success($_little,200,'绑定成功！');
    }




    /**
     * 绑定小卡
     */
    public function _bindLittleCard($id = '',$card = '',$userId) {
        /* 验证卡密 */
        try {
            $card = $this->_checkCard($id,$card,array(1,2,3,4,8,9));
        }catch(sfException $e) {
            return $this->error($this->errorCode,$e->getMessage());
        }
        //绑定卡密
        $bindCardData = TrdLipinkaCardTable::getInstance()->find($card['id']);
        //如果是动态时间 那么要自动设置
        if($card['postpone_type'] == TrdLipinkaRecord::$POSTPONE_DYNAMIC) {
            $_day = ((int)$card['postpone_day'] < 1) ? 1 : (int)$card['postpone_day'];
            $_stime = time();
            $_etime = (int)($_stime + (86400 * $_day));
            $bindCardData->setStime($_stime);
            $bindCardData->setEtime($_etime);
            $card['stime'] = $_stime;
            $card['etime'] = $_etime;
        }
        $bindCardData->setUserId($userId);
        $bindCardData->setStatus(TrdLipinkaCardTable::$BIND_STATUS);
        $bindCardData->save();
        return $this->success($card,200,'绑定成功！');
    }









    /**
     * 单个礼品卡检测
        $serviceRequest = new tradeServiceClient();
        $serviceRequest->setMethod('lipinka.u.check');
        $serviceRequest->setVersion('1.0');
        $serviceRequest->setApiParam('user_id', 18244227);
        $serviceRequest->setApiParam('card', "88q1vvZXiP");
        $serviceRequest->setUserToken($request->getCookie('u'));
        $response = $serviceRequest->execute();
     */
    public function executeUCheck() {
        $version = $this->getRequest()->getParameter('version','');
        $card = $this->getRequest()->getParameter('card','');
        $id = $this->getRequest()->getParameter('id','');
        $userId = $this->getRequest()->getParameter('user_id',$this->getUser()->getAttribute('uid'));
        //验证用户名
        if(empty($userId)) {
            return $this->error(501, '未登录');
        }
        //id 和 卡密必须传一个
        if(empty($id) && empty($card)) {
            return $this->error(407,'id和卡密必须传一个！');
        }
        $this->userId = $userId;

        /* 验证卡密 */
        try {
            $card = $this->_checkCard($id,$card,array(1,2,3,5,6,7,8));
        }catch(sfException $e) {
            return $this->error($this->errorCode,$e->getMessage());
        }
        //返回礼品卡信息
        return $this->success($card,200,'可以使用');
    }


    /*
     * 礼品卡回滚
        $serviceRequest = new tradeServiceClient();
        $serviceRequest->setMethod('lipinka.rollback');
        $serviceRequest->setVersion('1.0');
        $serviceRequest->setApiParam('user_id', 18244227);
        $serviceRequest->setApiParam('card', "hoMFydZjdK");
        $serviceRequest->setUserToken($request->getCookie('u'));
        $response = $serviceRequest->execute();
     */
    public function executeRollback() {
        $version = $this->getRequest()->getParameter('version','');
        $card = $this->getRequest()->getParameter('card','');
        $id = $this->getRequest()->getParameter('id','');
        $userId = $this->getRequest()->getParameter('user_id');
        //验证用户名
        if(empty($userId)) {
            return $this->error(501, '未登录');
        }
        //id 和 卡密必须传一个
        if(empty($id) && empty($card)) {
            return $this->error(407,'id和卡密必须传一个！');
        }
        $this->userId = $userId;
        /* 验证卡密 */
        try {
            $card = $this->_checkCard($id,$card,array(1,2,7));
        }catch(sfException $e) {
            return $this->error($this->errorCode,$e->getMessage());
        }

        //如果本身就没使用 那么直接返回true
        if($card['status'] == TrdLipinkaCardTable::$BIND_STATUS) {
            return $this->success($card,200,'成功回滚！');
        }


        //解除使用状态卡密
        $bindCardData = TrdLipinkaCardTable::getInstance()->find($card['id']);
        $bindCardData->setStatus(TrdLipinkaCardTable::$BIND_STATUS);
        $bindCardData->save();


        //拼装队列信息
        $_queue = array();
        $_queue['card'] = $card['account'];  //卡密
        $_queue['user_id'] = $userId; //绑定人id
        $_queue['status'] = TrdLipinkaCardTable::$BIND_STATUS; //类型

        //发送事件通知
        $amqpParams = sfConfig::get("app_mabbitmq_options_shihuo");
        $connection = new AMQPConnection($amqpParams['params']['host'], $amqpParams['params']['port'],$amqpParams['params']['user'], $amqpParams['params']['pass'], $amqpParams['params']['vhost']);
        $channel = $connection->channel();
        $arguments = array(
            "x-dead-letter-exchange" => array("S", "amq.topic"),
            "x-message-ttl" => array("I", 2000),
            "x-dead-letter-routing-key" => array("S", "shihuo.lipinka.use")
        );
        $channel->queue_declare('lipinka_queue_use', false, true, false, false, false, $arguments);
        $msg = new AMQPMessage(json_encode($_queue));
        $channel->basic_publish($msg, '', 'lipinka_queue_use');

        return $this->success($card,200,'成功回滚！');
    }





    /**
     * 使用礼品卡
        $serviceRequest = new tradeServiceClient();
        $serviceRequest->setMethod('lipinka.use');
        $serviceRequest->setVersion('1.0');
        $serviceRequest->setApiParam('user_id', 1111);
        $serviceRequest->setApiParam('card', 'MYcWiGWNaX');
        $serviceRequest->setUserToken($request->getCookie('u'));
        $response = $serviceRequest->execute();
     */
    public  function executeUse() {
        $version = $this->getRequest()->getParameter('version','');
        $card = $this->getRequest()->getParameter('card','');
        $id = $this->getRequest()->getParameter('id','');
        $userId = $this->getRequest()->getParameter('user_id',$this->getUser()->getAttribute('uid'));
        //验证用户名
        if(empty($userId)) {
            return $this->error(501, '未登录');
        }
        //id 和 卡密必须传一个
        if(empty($id) && empty($card)) {
            return $this->error(407,'id和卡密必须传一个！');
        }
        $this->userId = $userId;
        /* 验证卡密 */
        try {
            $card = $this->_checkCard($id,$card,array(1,2,3,5,6,7));
        }catch(sfException $e) {
            return $this->error($this->errorCode,$e->getMessage());
        }

        //绑定卡密
        $bindCardData = TrdLipinkaCardTable::getInstance()->find($card['id']);
        $bindCardData->setUserId($userId);
        $bindCardData->setStatus(TrdLipinkaCardTable::$USE_STATUS);
        $bindCardData->save();

        //拼装队列信息
        $_queue = array();
        $_queue['card'] = $card['account'];  //卡密
        $_queue['user_id'] = $userId; //绑定人id
        $_queue['status'] = TrdLipinkaCardTable::$USE_STATUS; //类型
        //发送事件通知
        $amqpParams = sfConfig::get("app_mabbitmq_options_shihuo");
        $connection = new AMQPConnection($amqpParams['params']['host'], $amqpParams['params']['port'],$amqpParams['params']['user'], $amqpParams['params']['pass'], $amqpParams['params']['vhost']);
        $channel = $connection->channel();
        $arguments = array(
            
            "x-dead-letter-exchange" => array("S", "amq.topic"),
            "x-message-ttl" => array("I", 2000),
            "x-dead-letter-routing-key" => array("S", "shihuo.lipinka.use")
        );
        $channel->queue_declare('lipinka_queue_use', false, true, false, false, false, $arguments);
        $msg = new AMQPMessage(json_encode($_queue));
        $channel->basic_publish($msg, '', 'lipinka_queue_use');
        return $this->success($card,200,'成功使用！');
    }


    /**
     * 设置错误码
     */
    private function setErrorCode($num) {
        $this->errorCode = $num;
    }


    /**
     * 检测礼品卡
     */
    private function _checkCard($id = '',$card = '',$check_type = array()) {
        //检测级别1  保证卡密格式正确
        if(in_array(1,$check_type)) {
            if(empty($card) && empty($id)) throw new sfException('不得为空！');
            if(empty($id)) {
                /* 判断长度 */
                if (strlen($card) != TrdLipinkaCardTable::$cardNum) {
                    $this->setErrorCode(401);
                    throw new sfException('卡号错误！');
                }
                /* 判断格式 */
                if (!preg_match(TrdLipinkaCardTable::$cardRegex, $card)) {
                    $this->setErrorCode(401);
                    throw new sfException('卡号错误！');
                }
            }
        }

        //获取礼品卡信息
        if(!empty($id)) {
            $card = TrdLipinkaCardTable::getInstance()->find($id);
            if(!empty($card)) $card = $card->toArray();
        } else {
            $card = TrdLipinkaCardTable::getByCardOne($card,true);
        }
        //2 去数据库检测卡密是否存在
        if(in_array(2,$check_type)) {
            /* 判断卡密是否存在 */
            if(empty($card)) {
                $this->setErrorCode(402);
                throw new sfException('礼品卡不存在！');
            }
        }
        /* 判断卡密是否过期 */
        if(in_array(3,$check_type)) {
            if($card['postpone_type'] == TrdLipinkaRecord::$POSTPONE_BEFORE &&  time() >=  $card['etime']) {
                $this->setErrorCode(403);
                throw new sfException('礼品卡已过期！');
            }
        }

        /* 判断卡密是否被绑定 */
        if(in_array(4,$check_type)) {
            if(!empty($card['user_id']))  {
                $this->setErrorCode(404);
                throw new sfException('已被绑定！');
            }
        }

        /* 判断卡密是否被使用 */
        if(in_array(5,$check_type)) {
            if(!empty($card['user_id']) && $card['status'] == TrdLipinkaCardTable::$USE_STATUS)  {
                $this->setErrorCode(405);
                throw new sfException('已被使用！');
            }
        }

        /* 判断卡密是否还没开始 */
        if(in_array(6,$check_type)) {
            if($card['postpone_type'] == TrdLipinkaRecord::$POSTPONE_BEFORE &&  time() <= $card['stime']) {
                $this->setErrorCode(406);
                throw new sfException('礼品卡使用期还未开始！');
            }
        }

        /* 判断卡密是不是本人绑定 */
        if(in_array(7,$check_type)) {
            if($this->userId != $card['user_id']) {
                $this->setErrorCode(408);
                throw new sfException('该礼品卡不是您的礼品卡！');
            }
        }

        /*判断礼品卡是否超过了绑定期限 */
        if(in_array(8,$check_type)) {
            //判断是否超过绑定期限
            if($card['status'] == TrdLipinkaCardTable::$OVERDUE_STATUS) {
                $this->setErrorCode(409);
                throw new sfException('该礼品卡已经超过了绑定期限！');
            }
            if($card['status'] != TrdLipinkaCardTable::$AVAILABLE_STATUS && empty($card['user_id'])) {
                $this->setErrorCode(409);
                throw new sfException('该礼品卡已经超过了绑定期限！');
            }
            if(!empty($card['overdue_time'])) {
                if(time() > $card['overdue_time']) {
                    //设置过期
                    //TrdLipinkaCardTable::setOverdueTime($card['id']);
                    $this->setErrorCode(409);
                    throw new sfException('该礼品卡已经超过了绑定期限！');
                }
            }
        }
        /* 判断是否真实的礼品卡  */
        if(in_array(9,$check_type)) {
            if (!empty($card['is_large'])) {
                $this->setErrorCode(402);
                throw new sfException('礼品卡不存在！');
            }
        }

        return $card;
    }















}