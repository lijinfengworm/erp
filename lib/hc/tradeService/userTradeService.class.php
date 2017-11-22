<?php

/**
 * Class userTradeService
 * version: 1.0
 */
class userTradeService extends tradeService
{

    /**
     * 获取个人中心的信息
     */
    public function executeInfoGet()
    {
        $v = $this->getRequest()->getParameter('version');
        $hupuUid = $this->getUser()->getAttribute('uid');
        $hupuUname = $this->getUser()->getAttribute('username');

        if (empty($hupuUid) || !is_numeric($hupuUid)) {
            return $this->error(501, '未登录');
        }

        $redis = sfContext::getInstance()->getDatabaseConnection('kaluliRedis');
        $key = 'trade:userinfo:uid:'.$hupuUid;
        $userInfo = unserialize($redis->get($key));
        if (!$userInfo) {
            $userInfo = array();
            if ($user = TrdAccountTable::getByHupuId($hupuUid)) {
                $levelInfo = $user->getLevel();
                $hupuUid = $user->getHupuUid();
                $userInfo = array(
                    'hupu_uid' => $hupuUid,
                    'hupu_username' => $hupuUname,
                    'avatar' => 'http://bbs.hupu.com/bbskcy/api_new_image.php?uid=' . $hupuUid . '&type=big',
                    'level' => $levelInfo['level'],
                    'level_name' => $levelInfo['name'],
                    'integral' => (int)$user->getIntegral(),
                    'gold' => (int)$user->getGold(),
                    'balance' => 0
                );
                $redis->set($key, serialize($userInfo), 5);
            }

        }
        return $this->success(array('user_info' => $userInfo));
    }

    /**
     *更新个人中心的信息
     */
    public function executeInfoUpdate()
    {
        $v = $this->getRequest()->getParameter('version');
        $hupuUid = $this->getUser()->getAttribute('uid');
        $hupuUname = $this->getUser()->getAttribute('username');

        if (empty($hupuUid) || !is_numeric($hupuUid)) {
            return $this->error(501, '未登录');
        }

        $redis = sfContext::getInstance()->getDatabaseConnection('kaluliRedis');
        $key = 'trade:userinfo:uid:'.$hupuUid;

        $userInfo = array();
        if ($user = TrdAccountTable::getByHupuId($hupuUid)) {
            $levelInfo = $user->getLevel();
            $hupuUid = $user->getHupuUid();
            $userInfo = array(
                'hupu_uid' => $hupuUid,
                'hupu_username' => $hupuUname,
                'avatar' => 'http://bbs.hupu.com/bbskcy/api_new_image.php?uid=' . $hupuUid . '&type=big',
                'level' => $levelInfo['level'],
                'level_name' => $levelInfo['name'],
                'integral' => (int)$user->getIntegral(),
                'gold' => (int)$user->getGold(),
                'balance' => 0
            );
            $redis->set($key, serialize($userInfo), 5);
        }

        return $this->success(array('user_info' => $userInfo));
    }
    /**
     * @param source : pc,m,app
     */
    public function executeSignin()
    {
        $v = $this->getRequest()->getParameter('version');
        $source = $this->getRequest()->getParameter('source', 'pc');
        $hupuUid = $this->getUser()->getAttribute('uid');
        $hupuUname = $this->getUser()->getAttribute('username');

        if (empty($hupuUid) || !is_numeric($hupuUid)) {
            return $this->error(501, '未登录');
        }
        if (!in_array($source, array('pc', 'm', 'app'))) {
            return $this->error(400, '参数错误');
        }
        $trdAccount = TrdAccountTable::getByHupuId($hupuUid);
        $tag = TrdAccountHistoryTable::isSigninToday($hupuUid);
        if (empty($tag)) {
            $day = TrdAccountHistoryTable::getSigninDay($hupuUid);
            $signinIntegral = ($day + 1) * 5;
            $randIntegral = rand(0, 20);
            $integral = $signinIntegral + $randIntegral;
            $explanation = '签到积分:' . $signinIntegral . '彩蛋积分:' . $randIntegral;

            $beforeIntegral = $trdAccount->getIntegral();
            $beforeGold = $trdAccount->getGold();

            $accountIntegral = $trdAccount->getIntegral() + $integral;
            $accountTotalIntegral = $trdAccount->getIntegralTotal() + $integral;

            $trdAccount->setIntegral($accountIntegral);
            $trdAccount->setIntegralTotal($accountTotalIntegral);
            $trdAccount->save();

            $afterIntegral = $trdAccount->getIntegral();
            $afterGold = $trdAccount->getGold();

            $trdAccountHistory = new TrdAccountHistory();
            $trdAccountHistory->setHupuUid($hupuUid);
            $trdAccountHistory->setHupuUsername($hupuUname);
            $trdAccountHistory->setSource($source);
            $trdAccountHistory->setCategory(4);
            $trdAccountHistory->setType(0);
            $trdAccountHistory->setExplanation($explanation);
            $trdAccountHistory->setIntegral($integral);
            $trdAccountHistory->setGold(0);
            $trdAccountHistory->setBeforeIntegral($beforeIntegral);
            $trdAccountHistory->setBeforeGold($beforeGold);
            $trdAccountHistory->setAfterIntegral($afterIntegral);
            $trdAccountHistory->setAfterGold($afterGold);
            $trdAccountHistory->save();

            $levelInfo = $trdAccount->getLevel();
            $res = array(
                'integral' => $integral,
                'total_integral' => $afterIntegral,
                'level' => $levelInfo['level'],
                'level_name' => $levelInfo['name'],
            );
            return $this->success($res);
        } else {
            return $this->error(401, '今天已经签到过了');
        }
    }

    //保存身份证号
    public function executeSaveIdentityNumber()
    {
        $version = $this->getRequest()->getParameter('version');
        $hupuUid = $this->getUser()->getAttribute('uid');
        $hupuUname = $this->getUser()->getAttribute('username');
        $identity_number = $this->getRequest()->getParameter('identity_number');
        $region_id = $this->getRequest()->getParameter('address_id');

        if (empty($hupuUid) || !is_numeric($hupuUid)) {
            return $this->error(501, '未登录');
        }

        if (!$region_id || !$identity_number)
            return $this->error(401, '参数非法');

        $identity_number_check_falg = tradeCommon::idcard_verify_number($identity_number);
        if (!$identity_number_check_falg) {
            return $this->error(402, '身份证不合法');
        }

        $address = TrdUserDeliveryAddressTable::getInstance()->createQuery()->select('*')
            ->where('id = ?', $region_id)
            ->andWhere('hupu_uid = ?', $hupuUid)
            ->fetchOne();
        if (!$address) {
            return $this->error(403, '参数非法');
        }
        $address->set('identity_number', $identity_number);
        $address->save();
        return $this->success(array('status' => 0, 'data' => '', 'msg' => 'ok'));
    }

    /**
     * 用户活动接口
    $serviceRequest = new tradeServiceClient();
    $serviceRequest->setMethod('user.activity.noviciate.get');
    $serviceRequest->setApiParam('type', $type);
    $serviceRequest->setVersion('1.0');
    $serviceRequest->setUserToken($request->getCookie('u'));
     */
    private $allowActivityType  = array(1, 2);
    private $userLipinkaKey     = 'trade:all:user:gift:lipinka:aid:{id}:lm:{amount}';
    private $userLipinkaCopyKey = 'trade:all:user:gift:lipinka:copy:aid:{id}:lm:{amount}';
    public function executeActivityNoviciateGet()
    {
        $userActivityType =  $this->getRequest()->getParameter('type');
        $hupuUname        =  $this->getUser()->getAttribute('username');
        $hupuUid          =  $this->getUser()->getAttribute('uid');
        if (empty($hupuUid) || !is_numeric($hupuUid)) {
            return $this->error(501, '未登录.');
        }
        if (empty($userActivityType) || !is_numeric($userActivityType)) {
            return $this->error(405, '活动类型缺失.');
        }

        //允许活动类型
        if(!in_array($userActivityType, $this->allowActivityType)){
            return $this->error(406, '活动类型不允许.');
        }

        //是否有活动
        $userActivity  = trdUserActivityTable::isHasActivity($userActivityType);
        if(!$userActivity || $userActivity->getStatus() != 0 ){
            return $this->error(401, '活动未开放.');
        }
        $userActivityId   =  $userActivity->getId();
        $userActivityAttr =  unserialize($userActivity->getAttr());

        //是否领取过
        $receive = trdUserActivityReceiveTable::getActivityInfo($hupuUid, $userActivityId, $userActivity->getType());
        if($receive){
            return $this->error(402, '您已经领取过了，请去个人中心查看.');
        }

        //各类型不同判断
        if($userActivity->getType() == 1){
            //是否有订单
            $isHasOrder = trdOrderTable::isHasOrder($hupuUid);
            if($isHasOrder){
                return $this->error(403, '您已经在识货购买过商品，本次活动无法参与.');
            }
        }

        //绑定礼品卡
        $activityId   = $userActivity->getId();
        $activityName = $userActivity->getName();

        $return = array(
            'status'=>200,
            'data'=>''
        );
        foreach($userActivityAttr['lipinka'] as $amount => $amount_num){
            //获取礼品卡
            $card = $this->_getCard($amount, $activityId, $activityName);

            //绑定
            if($card){
                $bind = $this->_lipinkaBind($card);

                if(!$bind){
                    $return['status'] = 404;
                    $return['data']  .= $amount.'优惠券领取失败';
                }
            }else{
                $return['status'] = 404;
                $return['data']  .= $amount.'优惠券领取失败';
            }
        }

        //保存
        $receive = new trdUserActivityReceive();
        $receive->setUserId($hupuUid);
        $receive->setUsername($hupuUname);
        $receive->setActivityId($userActivityId);
        $receive->save();

        //返回数据
        if( $return['status'] == 200){
            $return['data'] = '领取成功，请去个人中心查看';
        }else{
            return $this->error($return['status'], $return['data']);
        }

        return $this->success($return);
    }

    /**
     * 用户活动获取礼品卡接口
    $serviceRequest = new tradeServiceClient();
    $serviceRequest->setMethod('user.activity.card.get');
    $serviceRequest->setApiParam('amount', amount);
    $serviceRequest->setApiParam('activity_id', activity_id);
    $serviceRequest->setApiParam('activity_name', activity_name);
    $serviceRequest->setVersion('1.0');
    $serviceRequest->setUserToken($request->getCookie('u'));
     */
    public function executeActivityCardGet()
    {
        $amount         =  $this->getRequest()->getParameter('amount');
        $activity_id    =  $this->getRequest()->getParameter('activity_id');
        $activity_name  =  $this->getRequest()->getParameter('activity_name');
        $hupuUid        =  $this->getRequest()->getParameter('uid');
        if (empty($hupuUid) || !is_numeric($hupuUid)) {
            return $this->error(501, '用户信息缺失.');
        }

        if (empty($amount) || empty($activity_id) || empty($activity_name)) {
            return $this->error(405, '参数缺失.');
        }

        //绑定
        $card = $this->_getCard($amount, $activity_id, $activity_name);
        if($card){
            $bind = $this->_lipinkaBind($card, $hupuUid);

            if($bind){
                return $this->success(array(
                    'status'=> 200,
                    'data'  => array(
                        'card'=>$card
                    )
                ));
            }else{
                return $this->error(406, '优惠券领取失败.');
            }
        }else{
            return $this->error(406, '优惠券领取失败.');
        }
    }

    private function _getCard($amount, $activity_id, $activity_name){
        $redis           = sfContext::getInstance()->getDatabaseConnection('kaluliRedis');
        $persistence     = sfContext::getInstance()->getDatabaseConnection('tradePersistenceRedis');
        $redis->select(5);
        $lipinkaKey      =  str_replace(array('{id}','{amount}'),array($activity_id ,$amount),$this->userLipinkaKey);
        $lipinkaCopyKey  =  str_replace(array('{id}','{amount}'),array($activity_id ,$amount),$this->userLipinkaCopyKey);

        $lipinka_count = (int)$redis->scard($lipinkaKey);
        if(!$lipinka_count){//数据丢失，可从持久化中读取
            $persistenceInfo = unserialize($persistence->get($lipinkaCopyKey));
            if($persistenceInfo){
                foreach($persistenceInfo as  $persistenceInfo_v){
                    $redis->sadd($lipinkaKey, $persistenceInfo_v);
                }
            }
            $lipinka_count = (int)$redis->scard($lipinkaKey);
        }

        //不足发短信补充 小于2000发短信提示
        if($lipinka_count <= 2000 && ($lipinka_count % 100 == 0 )){
            tradeLog::info('youhuiquan', array('msg'=>'用户活动'.$activity_name.'优惠券现有'.$lipinka_count.'请补充'));//log 提醒

            if(sfConfig::get('sf_environment') == 'dev') { //短信提醒运营
                $phone = '13764131575'; //测试
            }else{
                $phone = '13804901224'; //运营
            }

            $tradeSendMessage = new tradeSendMessage();
            $tradeSendMessage->send($phone, '用户活动'.$activity_name.'优惠券现有'.$lipinka_count.'请补充');
        }

        //礼品卡
        $card = $redis->spop($lipinkaKey);

        //持久化(对50取模持久化)
        if(0 == ($lipinka_count / 50)){
            $data_all = $redis->sunion ($lipinkaKey);
            $persistence->set($lipinkaCopyKey, serialize($data_all));
        }

        return  $card;
    }

    private function _lipinkaBind($card, $user_id = null){
        $client = new tradeServiceClient();
        $client->setMethod('coupons.duihuan');
        $client->setVersion('1.0');
        $client->setApiParam('account',$card);
        if($user_id){
            $client->setApiParam('user_id', $user_id);
        }else{
            $client->setUserToken($_COOKIE['u']);
        }
        

        $response = $client->execute();
        $res      = $response->getData();

        if($res['status'] != 200){
            return false;
        }else{
            return true;
        }
    }


}