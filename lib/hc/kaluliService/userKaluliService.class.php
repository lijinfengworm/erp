<?php
/**
 * Created by PhpStorm.
 * User: libin
 * Date: 2016/6/24
 * Time: 17:00
 */

class userKaluliService extends kaluliService {

    public static function redis() {
        $redis =  sfContext::getInstance()->getDatabaseConnection('kaluliRedis');
        return $redis;
    }
    /**
     * 虎扑用户登陆后绑定操作
     */
    function executeMobileLoginToBindUser(){

        $hupuId = $this->request->getParameter('hupu_id');
        $source = $this->request->getParameter('source');
        $hupuId = Funbase::abnormal_uid_format($hupuId);
        $hupuUser = self::commonCall("GetHuPuUserInfo", ["hupu_id" => (int)$hupuId]);

        if($hupuUser["code"] == 1000){
            $mobile = $hupuUser["msg"]["mobile"];
            $hupuName = $hupuUser["msg"]["nickname"];
            $checkUser = KllUserTable::getInstance()->findOneByUserName($hupuName);
            if(!empty($checkUser)){
                $new_name = function() use (&$new_name){
                    $user_name = uniqid();
                    $tmp = KllUserTable::getInstance()->findOneByUserName($user_name);
                    if(!empty($tmp)){
                        return $this->new_name();
                    }
                    return $user_name;
                };
                $hupuName = $hupuName.$new_name();
            }
        }

        $isBind = self::commonCall("CheckIsKaluliUser", [ "hupu_id" => $hupuId, "type" => 1 ]);

        if(empty($isBind)){
            if(isset($mobile)){
                $check = self::commonCall("MobileIsInKaluli", ["mobile" => $mobile]);

                if(empty($check)){
                    $db = Doctrine_Manager::getInstance()->getConnection('kaluli');
                    $db->beginTransaction();
                    try {
                        //是否绑定虎扑
                        $user = new KllUser();
                        $user->setUserName($hupuName)
                            ->setMobile($mobile)
                            ->setCtTime(time())
                            ->setUpTime(time())
                            ->setLastLoginTime(time())
                            ->setSource($source)
                            ->save();
                        $newUserId = $user->getUserId();
                        $union = new KllUserUnion();
                        $hupuId = Funbase::abnormal_uid_format($hupuId);
                        $union->setUserId($newUserId)
                            ->setType(1)
                            ->setUnionId($hupuId)
                            ->setInfo(json_encode($hupuUser["msg"]))
                            ->setUnionUserName($hupuUser["msg"]["nickname"])
                            ->setCtTime(time())
                            ->setUpTime(time())
                            ->save();
                        $property = new KllUserProperty();
                        $property->setUserId($newUserId)
                            ->setUserName($hupuName)
                            ->setRegisterTime(time())
                            ->save();
                        $db->commit();
                        return $this->success($user,200,"绑定成功");

                    } catch (Exception $e) {
                        $db->rollback();
                        return $this->error(101,"绑定失败");
                    }

                }else{
                    $kaluli_user_id = $check->getUserId();

                    try{
                        $union = new KllUserUnion();
                        $hupuId = Funbase::abnormal_uid_format($hupuId);
                        $union->setUserId($kaluli_user_id)
                            ->setType(1)
                            ->setUnionId($hupuId)
                            ->setInfo(json_encode($hupuUser["msg"]))
                            ->setUnionUserName($hupuName)
                            ->setCtTime(time())
                            ->setUpTime(time())

                            ->save();

                        return $this->success($check,200,"绑定成功");
                    }catch(Exception $e){
                        return $this->error(101,"绑定失败");
                    }


                }
            }else{
                return $this->error(100,"虎扑手机号码不存在");

            }
        }else{
            $hupuId = Funbase::abnormal_uid_format($hupuId);
            $userId = $isBind->getUserId();

            $checkInfo = self::commonCall("CheckIsKaluliUser", [ "user_id" => $userId, "type" => 1 ]);
            if($checkInfo){
                $unionID = $checkInfo->getUnionId();
                if( $unionID != $hupuId){
                    
                    $bindMsg =  $checkInfo->setUnionId($hupuId)->save();
                }
            }


            $userInfo = self::commonCall("GetKaluliUserInfo", ["user_id" => $userId]);

            return $this->success($userInfo,200,"用户注册成功");
        }


    }
    //检查用户是否是卡路里用户
    function executeCheckIsKaluliUser(){
        $hupuId = $this->request->getParameter('hupu_id');
        $hupuId = Funbase::abnormal_uid_format($hupuId);
        $type = $this->request->getParameter('type');
        return KllUserUnionTable::getInstance()->findOneByTypeAndUnionId($type, $hupuId);
    }
    //获得卡路里用户信息
    function executeGetKaluliUserInfo(){
        $userId = $this->request->getParameter("user_id");

        return KllUserTable::getInstance()->findOneByUserId($userId);
    }

    function executeGetKaluliUserInfoProperty() {
        $userId = $this->request->getParameter("user_id");

        return KllUserPropertyTable::getInstance()->findOneByUserId($userId);
    }

    //获得虎扑用户信息
    function executeGetHuPuUserInfo(){
        $hupuId = $this->request->getParameter('hupu_id');
        $hupuId = Funbase::abnormal_uid_format($hupuId);
        $res = KaluliFun::requestUrl('https://passport.hupu.com/ucenter/getUserBaseInfo?uid='.$hupuId, "POST",[]);
        return  json_decode($res,true);
    }
    //检查手机号码是否已经到卡路里
    function executeMobileIsInKaluli(){
        $mobile = $this->request->getParameter("mobile");

        $check = KllUserTable::getInstance()->findOneByMobile($mobile);

        return $check;
    }
    function executeMobileLoginToKaluli(){
        $mobile = $this->request->getParameter("mobile");
        $password = $this->request->getParameter("password");

        $user = KllUserTable::getInstance()->findOneByMobileAndPassword($mobile, md5($password));

        return $user;
    }


    //用户登陆和注册
    public function executeLoginAndRegister(){
        $mobile = $this->request->getParameter("mobile");
        $activity = $this->request->getParameter("activity");
        $password = $this->request->getParameter("password");
        $authcode = $this->request->getParameter("authcode");
        $source = $this->request->getParameter("source");
        $kllUnion = $this->request->getParameter("kllUnion",'');
        $pwdLevel = 0;
        $match = explode('-', $password);

        if(!empty($match) && isset($match[1])){
            $password = $match[0];
            $pwdLevel = $match[1];
        }

        if(!empty($authcode)){
            $code = self::commonCall("AuthCheck", ["mobile" => $mobile, "authcode" => $authcode ]);
            if(!$code){
                return $this->error("500","验证码输入错误");
            }
        }

        //注册
        if($activity == 1){
            $check = self::commonCall("MobileIsInKaluli", ["mobile" => $mobile ]);

            if(!empty($check)){
                return $this->error(102,"用户已经存在");
            }else{
                try{
                    $user = new KllUser();

                    $password = md5($password);
                    $user->setMobile($mobile)->setPassword($password)->setCtTime(time())->setLastLoginTime(time())->setSource($source);
                    $user->save();
                    $userID = $user->getUserId();
                    $property = new KllUserProperty();
                    $property->setUserId($userID)->setPwdLevel($pwdLevel)->setRegisterTime(time())->save();
                    KaluliFun::saveUserLoginLog($userID,1);
                    $this->saveCpsUerInfo($kllUnion,$userID);
                    return $this->success($user,201,"用户注册成功");
                }catch(Exception $e){

                    return $this->error(103,"用户注册失败");
                }
            }

        }elseif($activity == 2){
            $check = self::commonCall("MobileIsInKaluli", ["mobile" => $mobile ]);
            if(!$check){
                try{
                    $user = new KllUser();
                    $user->setMobile($mobile)->setCtTime(time())->setLastLoginTime(time())->setSource($source);
                    $user->save();
                    $userID = $user->getUserId();
                    $this->saveCpsUerInfo($kllUnion,$userID);
                    KaluliFun::saveUserLoginLog($user->user_id,1);
                    return $this->success($user,201,"用户注册成功");
                }catch(Exception $e){
                    return $this->error(103,"用户登陆失败");
                }
            }
            KaluliFun::saveUserLoginLog($check->getUserId(),1);
            if($check->getUserName()){

                return $this->success($check,200,"用户登陆成功");
            }
            return $this->success($check,201,"用户登陆成功");
        }elseif($activity == 3){
            $check = self::commonCall("MobileLoginToKaluli", ["mobile" => $mobile, "password" => $password ]);

            if(!$check){
                return $this->error(106, "用户名和密码错误，<br>如已有手机账号，<br>请使用动态密码登录");
            }else{
                KaluliFun::saveUserLoginLog($check->getUserId(),1);
                if($check->getUserName()){

                    return $this->success($check,200,"用户登陆成功");
                }
                return $this->success($check,201,"用户登陆成功");
            }

        }else{
            return $this->error("105", "操作不存在");
        }

    }
    //更新用户时间
    public function executeUpdateTime(){
        $uid = $this->getRequest()->getParameter("uid");

        $user = KllUserTable::getInstance()->findOneByUserId($uid);

        $user->setLastLoginTime(time())->save();
        return true;
    }
    public static function commonCall($func, $params = []){
        $serviceRequest = new kaluliServiceClient();
        $serviceRequest->setVersion('1.0');
        $serviceRequest->setMethod('user.'.$func);
        if(!empty($params)){
            foreach($params as $k => $v){
                $serviceRequest->setApiParam($k, $v);
            }
        }
        $response = $serviceRequest->execute();
        return  $response->getData();
    }
    /**
     * 虎扑账号和密码登陆
     */
    public function executeUserToHuPu(){
        $username = $this->getRequest()->getParameter("username");
        $source = $this->getRequest()->getParameter("source");
        $hupuID = $this->getRequest()->getParameter("hupu_id");
        $kllUnion = $this->getRequest()->getParameter("kllUnion","");
        $hupuID = Funbase::abnormal_uid_format($hupuID);
        $userUnionInfo = self::commonCall("CheckUserIsExist", ["union_id" => $hupuID]);

        $hupuMobile = 0;

        $hupuInfo = self::commonCall("GetHuPuUserInfo", ["hupu_id" => $hupuID]);
        $hupuName = $username;
        if($hupuInfo["code"] == 1000){
            $hupuMobile = $hupuInfo["msg"]["mobile"];
            $hupuName = $hupuInfo["msg"]["nickname"];
        }else{
            $hupuInfo["msg"] = [];
        }

        $checkUser = KllUserTable::getInstance()->findOneByUserName($hupuName);

        if(!empty($checkUser)){
            $new_name = function() use (&$new_name){
                $user_name = uniqid();
                $tmp = KllUserTable::getInstance()->findOneByUserName($user_name);
                if(!empty($tmp)){
                    return $this->new_name();
                }
                return $user_name;
            };
            $hupuName = $hupuName.$new_name();
        }
        if(!empty($userUnionInfo)){

            $user_id = $userUnionInfo->getUserId();
            $userInfo = KllUserTable::getInstance()->findOneByUserId($user_id);
            if(!empty($userInfo)){
                $mobile = $userInfo->getMobile();
                self::commonCall("UpdateTime", ["uid" => $user_id]);
                if(empty($mobile)){
                    return $this->success($userInfo,202,"用户登陆成功");
                }else{
                    return $this->success($userInfo,200,"用户登陆成功");
                }
            }
            return $this->error(0, "登陆失败");

        }else{
            $user = [];
            if($hupuMobile != 0){
                $user = KllUserTable::getInstance()->findOneByMobile($hupuMobile);
            }

            if(!empty($user)){

                if($hupuMobile != 0){
                    
                    $code = self::commonCall("MobileToKaluli", ["hupu_id" => $hupuID, "mobile" => $hupuMobile]);

                    if($code == 101){
                        return $this->error(0, "手机号码已经绑定其他账号");
                    }
                    return $this->success($user,200,"用户登陆成功");
                }
                self::commonCall("UpdateTime", ["uid" => $user->getUserId()]);
                return $this->success($user,202,"用户登陆成功");
            }else{
                try{
                    $db = Doctrine_Manager::getInstance()->getConnection('kaluli');
                    $db->beginTransaction();
                    $user = new KllUser();
                    $user->setUserName($hupuName)->setMobile($hupuMobile)->setCtTime(time())->setLastLoginTime(time())->setSource($source)->save();
                    $property = new KllUserProperty();
                    $userID = $user->getUserId();
                    $property->setUserId($userID)->setUserName($hupuName)->setRegisterTime(time())->save();
                    $union = new KllUserUnion();
                    $hupuID = Funbase::abnormal_uid_format($hupuID);
                    $union->setUserId($userID)->setType(1)->setUnionUserName($hupuName)->setInfo(json_encode($hupuInfo["msg"]))->setUnionId($hupuID)->setCtTime(time())->save();
                    $db->commit();
                    $this->saveCpsUerInfo($kllUnion,$userID);
                    self::commonCall("UpdateTime", ["uid" => $userID ]);
                    if($hupuMobile != 0){

                        return $this->success($user,200,"用户登陆成功");
                    }
                    return $this->success($user,202,"用户登陆成功");
                }catch(Exception $e){

                    return $this->error(0, "同步失败");
                }
            }


        }
        return $this->error(0, "登陆失败");
    }
    /**
     * 手机号码是否存在卡路里里面
     */
    public function executeMobileToKaluli(){
        $hupu_id = $this->getRequest()->getParameter("hupu_id");
        $hupu_id = Funbase::abnormal_uid_format($hupu_id);
        $mobile = $this->getRequest()->getParameter("mobile");
        $check = KllUserTable::getInstance()->findOneByMobile($mobile);

        if(!empty($check)){
            $union = KllUserUnionTable::getInstance()->findOneByTypeAndUnionId(1, $hupu_id);
            if(!empty($union)){
                return 1;
            }else{
                $userId = $check->getUserId();
                //绑定操作
                $hupuUser = self::commonCall("GetHuPuUserInfo", ["hupu_id" => (int)$hupu_id]);

                if($hupuUser["code"] == 1000){
                    $mobile = $hupuUser["msg"]["mobile"];
                    $hupuName = $hupuUser["msg"]["nickname"];
                    $checkUser = KllUserTable::getInstance()->findOneByUserName($hupuName);
                    if(!empty($checkUser)){
                        $new_name = function() use (&$new_name){
                            $user_name = uniqid();
                            $tmp = KllUserTable::getInstance()->findOneByUserName($user_name);
                            if(!empty($tmp)){
                                return $this->new_name();
                            }
                            return $user_name;
                        };
                        $hupuName = $hupuName.$new_name();
                    }
                }
                $kaluli_user_name = $check->getUserName();

                if(empty($kaluli_user_name)){
                    try{
                        $check->setUserName($hupuName)->save();
                    }catch (Exception $e){
                        return 101;
                    }

                }

                $db = Doctrine_Manager::getInstance()->getConnection('kaluli');
                $db->beginTransaction();
                try {
                    $union = new KllUserUnion();
                    $hupu_id = Funbase::abnormal_uid_format($hupu_id);
                    $union->setUserId($userId)
                        ->setType(1)
                        ->setUnionId($hupu_id)
                        ->setInfo(json_encode($hupuUser["msg"]))
                        ->setUnionUserName($hupuUser["msg"]["nickname"])
                        ->setCtTime(time())
                        ->setUpTime(time())
                        ->save();

                    $db->commit();
                    return 1;

                } catch (Exception $e) {
                    $db->rollback();
                    return 101;
                }
            }
        }
        return 1;

    }
    /**
     * @return array
     * 发送验证码
     */
    public function executeSendAuthCode() {
        $mobile = $this->getRequest()->getParameter("mobile");
        if(empty($mobile)) {
            return $this->error("500","手机号码");
        }
        $check = $this->getRequest()->getParameter("check");
        $token = $this->getRequest()->getParameter("token");

        if($check == 1){
            $checkUser = self::commonCall("MobileIsInKaluli",["mobile" => $mobile ]);
            if(empty($checkUser)){
                return $this->error("500","手机号码不存在");
            }
        }
        //生成验证码
        $redis = self::redis();
        $redis->select(10);
        $mcode = $redis->get("mcode_".$mobile);
        if(empty($mcode)){
            $mcode = $this->generate_code();
            $redis->set("mcode_".$mobile,$mcode);
            $redis->expire("mcode_".$mobile,600); //设置过期时间，60秒
        }
        //发送验证码
        $kllMessage = new kllSendMessage();
        $result =  $kllMessage->send(array (
            'phone' =>$mobile,
            'var' => array('mcode'=>strval($mcode),'mobile'=>strval($mobile),'token'=>$token),
            'tpl_id' => kllSendMessage::$_MCODE_SEND,
        ));
        //发送完毕返回界面
        if($result['status'] != 1) {
            return $this->error("500",$result['msg']);
        }
        return $this->success();
    }

    /**
     * @return array
     * 校验验证码服务
     */
    public function executeAuthCheck(){
        $mobile = $this->getRequest()->getParameter("mobile");
        $authCode = $this->getRequest()->getParameter("authcode");
        if(empty($mobile) || empty($authCode)) {
            return $this->error("500","手机号和验证码不能为空");
        }

        $redis = self::redis();

        $redis->select(10);

        $mcode = $redis->get("mcode_".$mobile);

        if($mcode == $authCode) {
            return true;
        } else {
            return false;
        }

    }

    public function executeGetProperty() {
        $uid = $this->getRequest()->getParameter("uid");
        $userInfo = KllUserPropertyTable::getInstance()->findOneByUserId($uid);
        if(empty($userInfo)) {
            return $this->error("500","用户附加数据不存在");
        }
        //获取主表手机号
        $user = KllUserTable::getInstance()->findOneByUserId($uid);
        if(empty($user)) {
            return $this->error("500","用户数据不存在");
        }
        //处理成为列表展示数据
        $userData = array();
        $userData['uid'] = $uid;
        $userData['mobile'] = $user->mobile;
        $userData['username'] = $userInfo->user_name;
        $info = json_decode($userInfo->info,true);
        $userData['year'] = isset($info['year'])?$info['year'] : "";
        $userData['month'] = isset($info['month'])?$info['month'] : "";
        $userData['day'] = isset($info['day'])?$info['day'] : "";
        $userData['height'] = isset($info['height'])?$info['height'] : "";
        $userData['weight'] = isset($info['weight'])?$info['weight'] : "";
        $userData['fat'] = isset($info['fat'])?$info['fat']:"";
        $userData['sex'] = $userInfo->sex;
        $userData['province'] = $userInfo->province;
        $userData['city'] = $userInfo->city;
        $userData['profession'] = $userInfo->profession;
        return $this->success($userData);
    }

    /**
     * 更新用户
     * @return array
     */
    public function executeUpdateProperty() {
        $userInfo = $this->getRequest()->getParameter("userInfo");
        if(empty($userInfo)) {
            return $this->error("500","用户数据不存在");
        }
        //验证数据
        //判断昵称是否已经存在
        $response = self::commonCall("CheckUserName",['uid'=>$userInfo['uid'],'userName'=>$userInfo['username']]);
        if($response['status'] != 200) {
            return $this->error("500","昵称重复");
        }

        //添加事务，更新主表附表
        try {
            $db = Doctrine_Manager::getInstance()->getConnection('kaluli');
            $db->beginTransaction();
            //更新属性表
            $property = KllUserPropertyTable::getInstance()->findOneByUserId($userInfo['uid']);
            $property->setUserName($userInfo['username']);
            $property->setSex($userInfo['sex']);
            $property->setProvince($userInfo['province']);
            $property->setCity($userInfo['city']);
            $property->setProfession($userInfo['job']);
            $extraInfo = array(
                'year'=>$userInfo['year'],
                'month'=>$userInfo['month'],
                'day'=>$userInfo['day'],
                'height'=>$userInfo['height'],
                'weight'=>$userInfo['weight'],
                'fat' =>$userInfo['fat']
            );
            $property->setInfo(json_encode($extraInfo));
            $property->save();
            //更新主表
            $user = KllUserTable::getInstance()->findOneByUserId($userInfo['uid']);
            $user->setUserName($userInfo['username']);
            $user->save();
            //查询kol是否存在,存在更新userId
            $kolInfo = KllKolTable::getInstance()->findOneByUserId($userInfo['uid']);
            if(!empty($kolInfo)) {
                $kolInfo->setUserName($userInfo['username']);
                $kolInfo->save();
            }
            $db->commit();
            //更新完毕重置cookie
            $expire = strtotime("1 years");
            setcookie('u', $userInfo['uid'].'-'.$userInfo['username'], $expire, '/', 'kaluli.com', null, true);
        }catch(Exception $e) {
            $db->rollback();
            return $this->error("500",$e->getMessage());
        }
        return $this->success();
    }

    /**
     * 更新用户密码
     * return json
     *
     */
    function executeUpdatePasswordForUser(){
        $mobile = $this->getRequest()->getParameter("mobile");
        $password = $this->getRequest()->getParameter("password");
        $pwdLevel = 0;
        $match = explode('-', $password);

        if(!empty($match) && isset($match[1])){
            $password = $match[0];
            $pwdLevel = $match[1];
        }
        $user = self::commonCall("MobileIsInKaluli", ["mobile" => $mobile]);
        if(empty($user)){
            return $this->error(206, "用户不存在");
        }else{
            try{
                $password = md5($password);
                $user->setPassword($password)->save();
                $userID = $user->getUserId();

                $property  =  KllUserPropertyTable::getInstance()->findOneByUserId($userID);

                $property->setPwdLevel($pwdLevel)->save();
                return $this->success($user,200,"更新成功") ;
            }catch(Exception $e){
                return $this->error(207, "密码更新失败");
            }
        }
    }
    function executeCheckUserName() {
        $uid = $this->getRequest()->getParameter("uid");
        $userName = $this->getRequest()->getParameter("userName");
        $info = KllUserPropertyTable::getInstance()->createQuery()->select("*")->where("user_name= ?",$userName)->andWhere("user_id !=?",$uid)->fetchOne();
        if($info){
            return $this->error("500","该昵称已被使用，换一个试试");
        }
        return $this->success($info);
    }
    function executeCheckUserIsExist(){
        $union_id = $this->getRequest()->getParameter("union_id");
        $union_id = Funbase::abnormal_uid_format($union_id);
        return KllUserUnionTable::getInstance()->findOneByUnionId($union_id);
    }
    function executeUpdateMobile() {
        $userId = $this->getRequest()->getParameter("userId");
        $mobile = $this->getRequest()->getParameter("mobile");
        $code   = $this->getRequest()->getParameter("code");
        $result = self::commonCall("AuthCheck", ["mobile" => $mobile, "authcode" => $code ]);
        if(!$result){
            return $this->error("500","验证码输入错误");
        }

        $userInfo = KllUserTable::getInstance()->findOneByUserId($userId);
        if($userInfo) {
            $userInfo->setMobile($mobile);
            $userInfo->save();
            return $this->success();
        }
        return $this->error("500","用户不存在");
    }


    function generate_code($length = 6) {
        $min = pow(10 , ($length - 1));
        $max = pow(10, $length) - 1;
        return rand($min, $max);
    }
    //根据用户ID获得用户信息
    function executeGetUserInfoForAdmin(){
        $data = [];
        $userId = $this->getRequest()->getParameter("user_id");
        $data['user'] = KllUserTable::getInstance()->findOneByUserId($userId);
        $data['property'] = KllUserPropertyTable::getInstance()->findOneByUserId($userId);
        $data['address'] = TrdUserDeliveryAddressTable::getInstance()->findByHupuUid($userId);
        return $data;

    }
    //用户从虎扑登录过来后强制生成新的用户名
    function executeUpdateUserName(){
        $userId = $this->getRequest()->getParameter("uid");
        $user_name = $this->getRequest()->getParameter("user_name");
        $user = KllUserTable::getInstance()->findOneByUserId($userId);
        if(!empty($user)){
            $user->setUserName($user_name)->save();
        }

    }


    public function saveCpsUerInfo($kllUnion,$userId) {
        if(empty($kllUnion)) {
            return;
        }
        $cpsClickUserTable = KllCpsEventTable::getInstance();
        $cpsOrderTable = KllCpsOrderTable::getInstance();
        $cpsUserTable = KllCpsUserTable::getInstance();
        //获取推广用户详情
        $cpsUser = $cpsClickUserTable->findOneByCookie($kllUnion);
        $cpsBaseUser = $cpsUserTable->findOneByUnionId($cpsUser->getUnionId());
        //用户不是推广范围内的用户
        if (!$cpsBaseUser && (($cpsUser->getUnionId() != 'duomai') && ($cpsUser->getUnionId() != 'linktech') && ($cpsUser->getUnionId() != 18700) && ($cpsUser->getUnionId() != 'linkhaitao') && ($cpsUser->getUnionId() != 'kol'))) {
            return;
        }
        $cpsLoginUser = new KllCpsLoginUser();
        $cpsLoginUser->setMid( $cpsUser->getMid());
        $cpsLoginUser->setReferer($cpsUser->getReferer());
        $cpsLoginUser->setUid($userId);
        $cpsLoginUser->setUnionId($cpsUser->getUnionId());
        $cpsLoginUser->save();
        return;
    }
    

}