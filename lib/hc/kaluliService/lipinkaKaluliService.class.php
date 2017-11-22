<?php

/**
 *   没错！ 这里就是礼品卡服务类
 *   维护人：张文松
 *   最新版本：1.0
 *   最后更新时间  2015-10-27
 *   创建时间  2015-10-27
 */
class lipinkaKaluliService extends kaluliService {

    private $errorCode = 400;
    private $_code = array(
        'pq4act' => array('limitPrice'=>0, 'discountAmount'=>10, 'starttime'=>'2015-10-11 00:00:00', 'endtime'=>'2016-01-01 00:00:00'),
        'mdrpyg' => array('limitPrice'=>399, 'discountAmount'=>50, 'starttime'=>'2015-10-11 00:00:00', 'endtime'=>'2016-01-01 00:00:00'),
        'tsycq4' => array('limitPrice'=>499, 'discountAmount'=>70, 'starttime'=>'2015-10-11 00:00:00', 'endtime'=>'2016-01-01 00:00:00'),
        'c5chw4' => array('limitPrice'=>0, 'discountAmount'=>20, 'starttime'=>'2015-11-16 00:00:00', 'endtime'=>'2015-12-16 00:00:00'),
        '41gau4' => array('limitPrice'=>200, 'discountAmount'=>30,'starttime'=>'2015-11-16 00:00:00', 'endtime'=>'2015-12-16 00:00:00'),
        '5dbvtp' => array('limitPrice'=>500, 'discountAmount'=>100,'starttime'=>'2015-11-16 00:00:00', 'endtime'=>'2015-12-16 00:00:00'),
        'dqb8a9' => array('limitPrice'=>800, 'discountAmount'=>150,'starttime'=>'2015-11-16 00:00:00', 'endtime'=>'2015-12-16 00:00:00'),
    );
    private $userId = NULL;

    //限制规则
    private $card_limit = array();


    /**
     * 用户空间礼品卡展示
     * $serviceRequest = new kaluliServiceClient();
    $serviceRequest->setMethod('lipinka.user.lipinka');
    $serviceRequest->setVersion('1.0');
    $serviceRequest->setApiParam('user_id', 21306089);
    $serviceRequest->setApiParam('page_num', 15);
    $serviceRequest->setApiParam('type', array(1));
    $response = $serviceRequest->execute();

     */
    public function executeUserLipinka() {
        $version = $this->getRequest()->getParameter('version','');
        //用户id
        $userId = $this->getRequest()->getParameter('user_id');
        /**
         * 类型
         * 1,5,6  未使用
         * 2  已使用
         * 3  已过期
         * 4  未开始
         *
         */
        $_type = $this->getRequest()->getParameter('type',array());

        //当前页数
        $_page_now = $this->getRequest()->getParameter('page_now',1);
        //每页显示个数
        $_page_num = $this->getRequest()->getParameter('page_num',20);
        //排序
        $_order = $this->getRequest()->getParameter('order','stime desc');
        if(empty($userId)) return $this->error(501, '未登录');

        $_map = $_count_map =  $_select_map = array();

        /*  根据用户id查找 */
        $_select_map['where']['user_id'] =  "user_id = " . (int)$userId;

        //通过类型判断
        /* 判断限制规则  领取状态 0=未领取,1=领取,2=已使用 */
        //未使用
        if(in_array(1,$_type)) {
            $_select_map['where']['status'] =  'status = 1';
        }
        //已经使用
        if(in_array(2,$_type)) {
            $_select_map['where']['status'] = 'status = 2';
        }
        //已过期
        if(in_array(3,$_type)) {
            $_select_map['where']['etime'] =  "etime < " . (int)time();
        }
        //未开始
        if(in_array(4,$_type)) {
            $_select_map['where']['stime'] =  "stime > " . (int)time();
        }
        //未结束
        if(in_array(5,$_type)) {
            $_select_map['where']['etime'] = "etime > " . (int)time();
        }
        //已开始
        if(in_array(6,$_type)) {
            $_select_map['where']['stime'] =  "stime < " . (int)time();
        }

        //分页展示
        $_count_map['select'] = 'count(id) as num';
        $_count_map['limit'] = $_count_map['is_count'] = 1;
        if(!empty($_select_map['where'])) {
            if(!empty($_count_map['where'])) {
                $_count_map['where'] = array_merge($_select_map['where'],$_count_map['where']);
            } else {
                $_count_map['where'] = $_select_map['where'];
            }
        }

        $count = KalulilipinkaCardTable::getAll($_count_map);

        /* 分页 */
        $page = new Core_Lib_Page(array('total_rows'=>$count,'list_rows'=>$_page_num,'now_page'=>$_page_now));
        $page->now_page = $_page_now;
        $_map['offset'] = $page->first_row.','.$page->list_rows;
        $_map['limit'] = $_page_num;
        $_map['order'] = $_order;
        if(!empty($_select_map['where'])) {
            if(!empty($_map['where'])) {
                $_map['where'] = array_merge($_select_map['where'],$_map['where']);
            } else {
                $_map['where'] = $_select_map['where'];
            }
        }
        $data = KalulilipinkaCardTable::getAll($_map);
        //反解析限制
        foreach($data as $k=>$v) {
            if(!empty($v['card_limit'])) {
                $data[$k]['card_limit_parse'] =  $this->_cardLimitToArr($v['card_limit']);
            }
            $data[$k]['stime'] =  date('Y-m-d H:i:s',$v['stime']);
            $data[$k]['etime'] =  date('Y-m-d H:i:s',$v['etime']);
            //使用范围
            $group_id=$this->checkCouponScope($v['record_id']);
            if($group_id)
            {
                 $data[$k]['scope']=$group_id;
            }
            else
            {
                $data[$k]['scope']='';
            }
        }
        return $this->success(array('count'=>$count,'total' => count($data), 'list' => $data),200,'ok');
    }

    private function checkCouponScope($record_id)
    {
        $record_info=  KaluliLipinkaRecordTable::getInstance()->findOneBy('id', $record_id);
        if($record_info)
        {
            $group_id=$record_info->getGroupId();
            if($group_id)
            {
                return $group_id;
            }
            return false;
        }
        return false;
    }


    /**
     * 获取某个用户的礼品卡
    $serviceRequest = new kaluliServiceClient();
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
        //获取商品id
        $itemIds      = $this->getRequest()->getParameter("itemIds",array());
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
            $bind['where']['stime'] =  "stime <= " . (int)time();
        }

        /* 根据类型查找 */
        if(!empty($status)) {
            $bind['whereIn']['status'] =  $status;
        }
        $data = KalulilipinkaCardTable::getAll($bind);
        //反解析限制
        foreach($data as $k=>$v) {
            if(!empty($v['card_limit'])) {
                $data[$k]['card_limit_parse'] =  $this->_cardLimitToArr($v['card_limit']);
            }
            //先判断该券批次是否是单品券
            $record = KaluliLipinkaRecordTable::getInstance()->findOneById($v['record_id']);
            //是单品券再进行处理
            if(!empty($record->group_id)) {
                //判断用户的券是否有单品券，假如存在单品券，没有购买所需要的单品，不显示券
                $group = KllLipinkaRecordGroupTable::getInstance()->createQuery()
                    ->select("*")
                    ->whereIn("item_id", $itemIds)

                    ->andWhere("record_id = ?", $v['record_id'])
                    ->fetchArray();
                //查询不到数据，清除优惠券
                if(count($group) == 0) {
                    unset($data[$k]);
                }else {
                    $data[$k]["is_simple"] = 1;
                }
            }
        }
        



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
        $this->card_limit = $this->getRequest()->getParameter('card_limit');
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
        //$_len = mb_strlen($card);

        $is_large = $this->_checkIsLarge($card);
        if($is_large) {
            return $this->_bindLargeCard($card,$userId);
        } else {

            return $this->_bindLittleCard($id,$card,$userId);
        }
    }

    /**
     * 判断是大卡还是小卡
     */
    private function _checkIsLarge($card){
        $is_large = 0;//默认小卡
        $cardObj = KalulilipinkaCardTable::getByCardOne($card);
        if(!empty($cardObj)){
            $is_large = intval($cardObj['is_large']);
        }else{
            $largeObj = KalulilipinkaLargeTable::getInstance()->findOneByCard($card);
            if(!empty($largeObj)){
                $is_large = 1;
            }else{

                return $this->error(404,'礼品卡非法！');
            }
        }
        return $is_large;
    }
    /**
     * 绑定组合卡
     * @return array
     */
    public  function executeBindGroup() {
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

        //获取卡密信息
        $_cardGroupData = KllCardmultipleDataTable::getInstance()->getOne('m_card',$card);
        if(empty($_cardGroupData))  return $this->error(404,'礼品卡不存在！');
        if($_cardGroupData->getStatus() != KllCardmultipleData::$_STATUS_UNKNOWN)  return $this->error(404,'礼品卡已被领取！');

        $_sql_lock = 'kaluli.lipinka.bind.group'.$_cardGroupData->getId();
        $status = kaluliFun::getLock($_sql_lock,5);//获取锁
        if ($status[0]['status'] < 1)  {
            kaluliFun::releaseLock($_sql_lock);//释放锁
            return $this->error(404,'领取人太多，请稍后再试！');
        }
        //获取组合礼品卡信息
        $_cardGroup = KllCardmultipleTable::getInstance()->find($_cardGroupData->getMId());
        if(empty($_cardGroup)
            || $_cardGroup->getIsSuccess() != KllCardmultiple::$_IS_SUCCESS_OK
            || $_cardGroup->getStatus() != KllCardmultiple::$_STATUS_OK) {
            kaluliFun::releaseLock($_sql_lock);//释放锁
            return $this->error(404,'礼品卡非法！');
        }
        //获取卡卷包信息
        $_cardWare = KllCardWareTable::getOne('code',$_cardGroup->getCardwareCode());

        if(time() < strtotime($_cardWare->getStime())
            ||  time() > strtotime($_cardWare->getEtime())
            || $_cardWare->getStatus() != KllCardWare::$_STATUS_OK) {
            kaluliFun::releaseLock($_sql_lock);//释放锁
            return $this->error(404,'礼品卡非法2！');
        }

        $_card = json_decode($_cardGroupData->getCardData(),true);
        $serviceRequest = new kaluliServiceClient();
        $_isbind = true;
        $_err_message = NULL;

        //绑定
        foreach($_card as $k=>$v) {
            $serviceRequest->setMethod('lipinka.bind');
            $serviceRequest->setVersion('1.0');
            $serviceRequest->setApiParam('user_id', $userId);
            $serviceRequest->setApiParam('card', $v);
            $response = $serviceRequest->execute();
            if($response->hasError()) {
                $_isbind = false;
                $_err_message = $response->getError();
            }
        }
        //如果有问题 回滚
        if(!$_isbind) {
            foreach($_card as $k=>$v) {
                $serviceRequest->setMethod('lipinka.bind.rollback');
                $serviceRequest->setVersion('1.0');
                $serviceRequest->setApiParam('user_id', $userId);
                $serviceRequest->setApiParam('card', $v);
                $response = $serviceRequest->execute();
            }
            kaluliFun::releaseLock($_sql_lock);//释放锁
            return $this->error(404,$_err_message);
        }
        $_cardGroupData->setUid($userId);
        $_cardGroupData->setUTime(time());
        $_cardGroupData->setStatus(kllCardmultipleData::$_STATUS_USE);
        $_cardGroupData->save();
        kaluliFun::releaseLock($_sql_lock);//释放锁
        //判断是否到了警戒数量 通知人
        $_phone = $_cardGroup->getPhone();
        if(!empty($_phone) && $_cardGroup->getIsAlert() == 0) {
            //获取使用数量
            $_count = KllCardmultipleDataTable::getUnknownCard($_cardGroup->getId());
            if($_cardGroup->getAlertNum()  >= $_count) {
                //发短信通知
                //发送短信
                $kllMessage = new kllSendMessage();
                $kllMessage->send(array (
                    'phone' => $_phone,
                    'var' => array("id"=>strval($_cardGroup->getId()),"num"=>strval($_cardGroup->getAlertNum())),
                    'tpl_id' => kllSendMessage::$_LIPINKA_REMIND
                ));
                //标记已通知
                $_cardGroup->setIsAlert(1);
                $_cardGroup->save();
            }
        }
        return $this->success($card,200,'成功使用！');
    }



    /**
     * 绑定大卡
     */
    public function _bindLargeCard($card = '',$userId) {

        /* 验证卡密 */
        //var_dump($card);exit;
        if (!preg_match(KalulilipinkaCardTable::$cardLargeRegex, $card)) return $this->error(401,'卡号错误！');
        $largeData = KalulilipinkaLargeTable::getByCardOne(trim($card),false);
        
        /* 判断是否存在 */
        if(empty($largeData)) return $this->error(402,'不存在！');
        /* 判断是否已经使用完了 */
        if($largeData->getNoReceive() <= 0 || $largeData->getStatus() == KalulilipinkaLarge::$STATUS_BEEN_USED) return $this->error(403,'全部使用完毕！');
        /* 判断是否过期 */
        if($largeData->getPostponeType() == KalulilipinkaRecord::$POSTPONE_BEFORE) {
            if(time() >=  $largeData->getEtime())  return $this->error(404,'礼品卡已过期！');
            if(time() <= $largeData->getStime())  return $this->error(405,'礼品卡还没开始！');
        }
        if($largeData->getPostponeType() == KalulilipinkaRecord::$POSTPONE_DYNAMIC) {
            $_time = $largeData->getOverdueTime();
            if(!empty($_time)) {
                if(time() > $_time) {
                    return $this->error(406, '该礼品卡已经超过了绑定期限！');
                }
            }
        }
        /* 判断当前用户是否绑定过  */
        if(KalulilipinkaCardTable::isLargeBand($largeData['id'],$userId)) {
            return $this->error(406,'您已经使用过了！');
        }
        //判断限制条件
        $_record = KaluliLipinkaRecordTable::getInstance()->find($largeData->getRecordId());
        try {
            $this->parseCardLimit($this->card_limit,$_record->getCardLimit());
        } catch(sfException $e) {
            return $this->error(406,$e->getMessage());
        }

        /* 开始绑定  */
        $littleCard = KalulilipinkaCardTable::getLargeOne($largeData['id']);
        if(empty($littleCard)) return $this->error(402,'不存在！');
        $_little = $littleCard->toArray();

        /* 加锁   */
        
        $statusLock = tradeCommon::getLock('get_lipinka_large_id_' . $littleCard->getId(), 5);
        if ( $statusLock[0]['status'] < 1 ) {
            return $this->error(406,'同时领取该卡的人太多，稍后再试试吧！');
        }
        //标记已领取
        if($largeData->getPostponeType() == KalulilipinkaRecord::$POSTPONE_DYNAMIC) {
            $_day = ((int)$largeData->getPostponeDay() < 1) ? 1 : (int)$largeData->getPostponeDay();
            $_stime = time();
            $_etime = (int)($_stime + (86400 * $_day));
            $littleCard->setStime($_stime);
            $littleCard->setEtime($_etime);
            $_little['stime'] = $_stime;
            $_little['etime'] = $_etime;
        }
        $littleCard->setUserId($userId);
        $littleCard->setStatus(KalulilipinkaCardTable::$BIND_STATUS);
        $littleCard->save();
        //large  使用量增加1
        $_receive = (int)$largeData->getNoReceive()-1;
        $largeData->setNoReceive($largeData->getNoReceive()-1);
        if($_receive <= 0) {
            $largeData->setStatus(KalulilipinkaLarge::$STATUS_BEEN_USED);
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
            $card = $this->_checkCard($id,$card,array(1,2,3,4,8,9,10));
        }catch(sfException $e) {
            return $this->error($this->errorCode,$e->getMessage());
        }
        //绑定卡密
        $bindCardData = KalulilipinkaCardTable::getInstance()->find($card['id']);
        //如果是动态时间 那么要自动设置
        if($card['postpone_type'] == KalulilipinkaRecord::$POSTPONE_DYNAMIC) {
            $_day = ((int)$card['postpone_day'] < 1) ? 1 : (int)$card['postpone_day'];
            $_stime = time();
            $_etime = (int)($_stime + (86400 * $_day));
            $bindCardData->setStime($_stime);
            $bindCardData->setEtime($_etime);
            $card['stime'] = $_stime;
            $card['etime'] = $_etime;
        }
        $bindCardData->setUserId($userId);
        $bindCardData->setStatus(KalulilipinkaCardTable::$BIND_STATUS);
        $bindCardData->save();
        return $this->success($card,200,'绑定成功！');
    }









    /**
     * 单个礼品卡检测
    $serviceRequest = new kaluliServiceClient();
    $serviceRequest->setMethod('lipinka.user.check');
    $serviceRequest->setVersion('1.0');
    $serviceRequest->setApiParam('user_id', 18244227);
    $serviceRequest->setApiParam('card', "88q1vvZXiP");
    $serviceRequest->setUserToken($request->getCookie('u'));
    $response = $serviceRequest->execute();
     */
    public function executeUserCheck() {
        $version = $this->getRequest()->getParameter('version','');
        $card = $this->getRequest()->getParameter('card','');
        $id = $this->getRequest()->getParameter('id','');
        $userId = $this->getRequest()->getParameter('user_id',$this->getUser()->getAttribute('uid'));
        $this->card_limit = $this->getRequest()->getParameter('card_limit');
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
            $card = $this->_checkCard($id,$card,array(1,2,3,5,6,7,8,10));
        }catch(sfException $e) {
            return $this->error($this->errorCode,$e->getMessage());
        }
        //返回礼品卡信息
        return $this->success($card,200,'可以使用');
    }


    /**
     * 礼品卡绑定回滚
     * @return array
     */
    public function executeBindRollback(){
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
        if($card['status'] == KalulilipinkaCardTable::$BIND_STATUS) {
            return $this->success($card,200,'成功回滚！');
        }

        //解除使用状态卡密
        $bindCardData = KalulilipinkaCardTable::getInstance()->find($card['id']);
        $bindCardData->setStatus(KalulilipinkaCardTable::$AVAILABLE_STATUS);
        $bindCardData->setStime("");
        $bindCardData->setEtime("");
        $bindCardData->setUserId("");
        $bindCardData->save();
        return $this->success($card,200,'成功回滚！');
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
        if($card['status'] == KalulilipinkaCardTable::$BIND_STATUS) {
            return $this->success($card,200,'成功回滚！');
        }


        //解除使用状态卡密
        $bindCardData = KalulilipinkaCardTable::getInstance()->find($card['id']);
        $bindCardData->setStatus(KalulilipinkaCardTable::$BIND_STATUS);
        $bindCardData->save();

        /**
        //拼装队列信息
        $_queue = array();
        $_queue['card'] = $card['account'];  //卡密
        $_queue['user_id'] = $userId; //绑定人id
        $_queue['status'] = KalulilipinkaCardTable::$BIND_STATUS; //类型

        //发送事件通知
        $amqpParams = sfConfig::get("app_mabbitmq_options_kaluli");
        $connection = new AMQPConnection($amqpParams['params']['host'], $amqpParams['params']['port'],$amqpParams['params']['user'], $amqpParams['params']['pass'], $amqpParams['params']['vhost']);
        $channel = $connection->channel();
        $arguments = array(
            "x-dead-letter-exchange" => array("S", "amq.topic"),
            "x-message-ttl" => array("I", 2000),
            "x-dead-letter-routing-key" => array("S", "kaluli.lipinka.use")
        );
        $channel->queue_declare('kaluli_lipinka_queue_use', false, true, false, false, false, $arguments);
        $msg = new AMQPMessage(json_encode($_queue));
        $channel->basic_publish($msg, '', 'kaluli_lipinka_queue_use');
        */
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
        $this->card_limit = $this->getRequest()->getParameter('card_limit');
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
            $card = $this->_checkCard($id,$card,array(1,2,3,5,6,7,10));
        }catch(sfException $e) {
            return $this->error($this->errorCode,$e->getMessage());
        }

        //绑定卡密
        $bindCardData = KalulilipinkaCardTable::getInstance()->find($card['id']);
        $bindCardData->setUserId($userId);
        $bindCardData->setStatus(KalulilipinkaCardTable::$USE_STATUS);
        $bindCardData->save();


        /**

        //拼装队列信息
        $_queue = array();
        $_queue['card'] = $card['account'];  //卡密
        $_queue['user_id'] = $userId; //绑定人id
        $_queue['status'] = KalulilipinkaCardTable::$USE_STATUS; //类型
        //发送事件通知
        $amqpParams = sfConfig::get("app_mabbitmq_options_kaluli");
        $connection = new AMQPConnection($amqpParams['params']['host'], $amqpParams['params']['port'],$amqpParams['params']['user'], $amqpParams['params']['pass'], $amqpParams['params']['vhost']);
        $channel = $connection->channel();
        $arguments = array(
            "x-dead-letter-exchange" => array("S", "amq.topic"),
            "x-message-ttl" => array("I", 2000),
            "x-dead-letter-routing-key" => array("S", "kaluli.lipinka.use")
        );
        $channel->queue_declare('kaluli_lipinka_queue_use', false, true, false, false, false, $arguments);
        $msg = new AMQPMessage(json_encode($_queue));
        $channel->basic_publish($msg, '', 'kaluli_lipinka_queue_use');
         */
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
                // if (strlen($card) != KalulilipinkaCardTable::$cardNum) {
                //     $this->setErrorCode(401);
                //     throw new sfException('卡号错误！');
                // }
                /* 判断格式 */
                if (!preg_match(KalulilipinkaCardTable::$cardRegex, $card)) {
                    $this->setErrorCode(401);
                    throw new sfException('卡号错误！');
                }
            }
        }

        //获取礼品卡信息
        if(!empty($id)) {
            $card = KalulilipinkaCardTable::getInstance()->find($id);
            if(!empty($card)) $card = $card->toArray();
        } else {
            $card = KalulilipinkaCardTable::getByCardOne($card,true);
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
            if($card['postpone_type'] == KalulilipinkaRecord::$POSTPONE_BEFORE &&  time() >=  $card['etime']) {
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
            if(!empty($card['user_id']) && $card['status'] == KalulilipinkaCardTable::$USE_STATUS)  {
                $this->setErrorCode(405);
                throw new sfException('已被使用！');
            }
        }

        /* 判断卡密是否还没开始 */
        if(in_array(6,$check_type)) {
            if($card['postpone_type'] == KalulilipinkaRecord::$POSTPONE_BEFORE &&  time() <= $card['stime']) {
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
            if($card['status'] == KalulilipinkaCardTable::$OVERDUE_STATUS) {
                $this->setErrorCode(409);
                throw new sfException('该礼品卡已经超过了绑定期限！');
            }
            if($card['status'] != KalulilipinkaCardTable::$AVAILABLE_STATUS && empty($card['user_id'])) {
                $this->setErrorCode(409);
                throw new sfException('该礼品卡已经超过了绑定期限！');
            }
            if(!empty($card['overdue_time'])) {
                if(time() > $card['overdue_time']) {
                    //设置过期
                    //KalulilipinkaCardTable::setOverdueTime($card['id']);
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

        /* 判断限制规则  */
        if(in_array(10,$check_type)) {
            $this->parseCardLimit($this->card_limit,$card['card_limit']);
        }
        return $card;
    }


    /**
     * @param $check_card_limit  验证规则
     * @param $card_limit        实际规则
     * @return bool
     * @throws sfException
     */
    private function parseCardLimit($check_card_limit,$card_limit) {
        //判断是否有需要验证的规则
        if(empty($check_card_limit) || !is_array($check_card_limit) || count($check_card_limit) < 1) return true;
        $_card_limit_arr = $this->_cardLimitToArr($card_limit);
        if(empty($_card_limit_arr) || count($_card_limit_arr) < 1) return true;

       //价格验证
        if(!empty($_card_limit_arr['order_money']) && !empty($check_card_limit['order_money'])) {
            if((int)$check_card_limit['order_money'] < $_card_limit_arr['order_money']) throw new sfException('订单价格不满足使用礼品卡价格！');
        }
        return true;
    }






























    /**
     * 单个礼品卡检测
        $serviceRequest = new kaluliServiceClient();
        $serviceRequest->setMethod('lipinka.u.check');
        $serviceRequest->setVersion('1.0');
        $serviceRequest->setApiParam('card', "88q1vvZXiP");
        $serviceRequest->setUserToken($request->getCookie('u'));
        $response = $serviceRequest->execute();
     */
    public function executeUCheck() {
        $version = $this->getRequest()->getParameter('version','');
        $card = $this->getRequest()->getParameter('card','');
        $price = $this->getRequest()->getParameter('price','');//商品价格总额
        $userId = $this->getRequest()->getParameter('user_id',$this->getUser()->getAttribute('uid'));
        //验证用户名
        if(empty($userId)) {
            return $this->error(401, '未登录');
        }
        //卡密必须传一个
        if(empty($card)) {
            return $this->error(402,'优惠码不合法！');
        }
        if($price < 0) {
            return $this->error(403,'订单价格不合法！');
        }

        /* 验证卡密 */
        try {
            if (isset($this->_code[$card])){
                $codeInfo = $this->_code[$card];
                $time = date("Y-m-d H:i:s");
                if ($time >= $codeInfo['starttime'] && $time <= $codeInfo['endtime']){
                    if ($codeInfo['limitPrice'] > 0){
                        if ($price >= $codeInfo['limitPrice']){
                            //返回礼品卡信息
                            return $this->success(array('card'=>$card, 'coupon_fee'=>$codeInfo['discountAmount']),200,'可以使用');
                        } else {
                            throw new Exception('使用该优惠码商品金额必须满'.$codeInfo['limitPrice'].'元', 404);
                        }
                    } else {
                        //返回礼品卡信息
                        return $this->success(array('card'=>$card, 'coupon_fee'=>$codeInfo['discountAmount']),200,'可以使用');
                    }
                } else {
                    throw new Exception('优惠码不合法', 405);
                }
            } else {
                throw new Exception('优惠码不合法', 405);
            }
        } catch( Exception $e) {
            return $this->error($this->errorCode,$e->getMessage());
        }
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

    //根据优惠券id获取参与优惠的商品
    public function executeGetGoodsBycoupon()
    {
        $coupon_id = $this->getRequest()->getParameter('coupon_id','');
        $_page_now = $this->request->getParameter("page",1);
        $_page_num = $this->request->getParameter("pageSize",20);
        //根据优惠券id获取record_id
        $coupon_info=  KaluliLipinkaCardTable::getInstance()->findOneBy('id', $coupon_id);
        if(!$coupon_info)
        {
            return false;
        }
        $record_id=$coupon_info->getRecordId();
        //根据$record_id获取参与优惠的商品id
//        $record_group=  KllLipinkaRecordGroupTable::getInstance()->findBy('record_id', $record_id);
        //分页展示
        $_count_map['select'] = 'count(id) as num';
        $_count_map['limit'] = $_count_map['is_count'] = 1;
        $_count_map['where']['record_id']='record_id = ' .$record_id;
        $count=KllLipinkaRecordGroupTable::getInstance()->getAll($_count_map);
        
        $bind['where']['record_id']='record_id = ' .$record_id;
        $page = new Core_Lib_Page(array('total_rows'=>$count,'list_rows'=>$_page_num,'now_page'=>$_page_now));
        $bind['limit']=$_page_num;
        $bind['offset']=(($_page_now-1)*$_page_num).','.$page->list_rows;
        $page->now_page = $_page_now;
        $record_group=  KllLipinkaRecordGroupTable::getInstance()->getAll($bind);
        if(!$record_group)
        {
            return $this->error(500,"没有更多商品了");
        }    
        $goods_id=[];
        foreach ($record_group as $k)
        {
            $goods_id[]=$k['item_id'];
        }
        $goods_info=  KaluliItemTable::getInstance()->getItemByIds($goods_id);
        if($goods_info)
        {
            return $this->success(array('list' => $goods_info,'count'=>$count),200,'ok');
        }
        return false;
    }

    public function executeGetGoodsByRecord() {
        $record_id = $this->getRequest()->getParameter("record_id");
        $_page_now = $this->request->getParameter("page",1);
        $_page_num = $this->request->getParameter("pageSize",20);
        //分页展示
        $_count_map['select'] = 'count(id) as num';
        $_count_map['limit'] = $_count_map['is_count'] = 1;
        $_count_map['where']['record_id']='record_id = ' .$record_id;
        $count=KllLipinkaRecordGroupTable::getInstance()->getAll($_count_map);

        $bind['where']['record_id']='record_id = ' .$record_id;
        $page = new Core_Lib_Page(array('total_rows'=>$count,'list_rows'=>$_page_num,'now_page'=>$_page_now));
        $bind['limit']=$_page_num;
        $bind['offset']=(($_page_now-1)*$_page_num).','.$page->list_rows;
        $page->now_page = $_page_now;
        $record_group=  KllLipinkaRecordGroupTable::getInstance()->getAll($bind);
        if(!$record_group)
        {
            return $this->error(500,"没有更多商品了");
        }
        $goods_id=[];
        foreach ($record_group as $k)
        {
            $goods_id[]=$k['item_id'];
        }
        $goods_info=  KaluliItemTable::getInstance()->getItemByIds($goods_id);
        if($goods_info)
        {
            return $this->success(array('list' => $goods_info,'count'=>$count),200,'ok');
        }
        return false;
    }

    //根据coupon获取内容
    public function executeGetByCouponId() {
        $id = $this->getRequest()->getParameter("id");
        if(empty($id)){
            return $this->error(500,"id不存在");
        }
        $couponInfo = KaluliLipinkaCardTable::getInstance()->findOneById($id);
        if(empty($couponInfo)) {
            return $this->error(500,"优惠券不存在");
        }

        $amount = $couponInfo->getAmount();
        $cardLimit = $couponInfo->getCardLimit();
        if(!empty($cardLimit)) {
            $infos = explode("=",$cardLimit);
            $str = "满".$infos[1]."减".$amount;
        } else {
            $str = $amount."元优惠券";
        }
        return $this->success(array("str"=>$str));
    }

    //根据coupon获取内容
    public function executeGetByRecordId() {
        $id = $this->getRequest()->getParameter("id");
        if(empty($id)){
            return $this->error(500,"id不存在");
        }
        $couponInfo = KaluliLipinkaCardTable::getInstance()->findOneByRecordId($id);
        if(empty($couponInfo)) {
            return $this->error(500,"优惠券不存在");
        }

        $amount = $couponInfo->getAmount();
        $cardLimit = $couponInfo->getCardLimit();
        if(!empty($cardLimit)) {
            $infos = explode("=",$cardLimit);
            $str = "满".$infos[1]."减".$amount;
        } else {
            $str = $amount."元优惠券";
        }
        return $this->success(array("str"=>$str));
    }

}