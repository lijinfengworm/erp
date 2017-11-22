<?php

/**
 * Class addressTradeService
 * version: 1.0
 */
class addressTradeService extends tradeService {

    /**
     * 获取我的收获地址列表
     *
     * $serviceRequest = new tradeServiceClient();
       $serviceRequest->setMethod('address.get.list');
       $serviceRequest->setVersion('1.0');
       $serviceRequest->setApiParam('user_id', 18244227);
       $serviceRequest->setUserToken($request->getCookie('u'));
       $response = $serviceRequest->execute();
     *
     */
    public function executeGetList()
    {
        $v = $this->getRequest()->getParameter('v');
        $type = $this->getRequest()->getParameter('type',false);//地址是否拼接 true是
        $hupuUid =  $userId = $this->getRequest()->getParameter('user_id',$this->getUser()->getAttribute('uid'));
        if (empty($hupuUid) || !is_numeric($hupuUid)) {
            return $this->error(501, '未登录');
        }
        $address = TrdUserDeliveryAddressTable::getInfoByHupuUid($hupuUid);
        if ($type) {
            $default = $other = array();
            foreach($address as $k=>$v){
                $v['address'] = $v['name'].' '.trim($v['region']).' '.$v['street'].'（邮编：'.$v['postcode'].'）'.' ';
                if ($v['mobile']){
                    $v['address'] .= '手机：'.$v['mobile'];
                } else {
                    $v['address'] .= '电话：'.$v['phonesection'].'-'.$v['phonecode'];
                    if ($v['phoneext']) $v['address'] .= '-'.$v['phoneext'];
                }
                if(!empty($v['identity_number'])) $v['address'] .= ' 身份证：'.substr($v['identity_number'],0,5).'**********'.substr($v['identity_number'],15,3);
                if ($v['defaultflag'] == 1){
                    $default[0] = $v;
                } else {
                    $other[$k] = $v;
                }
            }
            $address = array_merge($default,$other);
        }
        return $this->success(array('list' => $address));
    }

    /**
     * 设置默认地址
     *
     * $serviceRequest = new tradeServiceClient();
    $serviceRequest->setMethod('address.setDefault');
    $serviceRequest->setVersion('1.0');
    $serviceRequest->setApiParam('user_id', 18244227);
    $serviceRequest->setApiParam('address_id', 11111);
    $serviceRequest->setApiParam('default', true);
    $serviceRequest->setUserToken($request->getCookie('u'));
    $response = $serviceRequest->execute();
     *
     */
    public function executeSetDefault()
    {
        $hupuUid =  $userId = $this->getRequest()->getParameter('user_id',$this->getUser()->getAttribute('uid'));
        $address_id = $this->getRequest()->getParameter('address_id',0);
        $default = $this->getRequest()->getParameter('default',true);
        if (empty($hupuUid) || !is_numeric($hupuUid)) {
            return $this->error(501, '未登录');
        }
        if (empty($address_id) || !is_numeric($address_id)) {
            return $this->error(400, '参数非法');
        }
        $info = TrdUserDeliveryAddressTable::getInstance()->createQuery()->where('id = ?',$address_id)->andWhere('hupu_uid = ?',$hupuUid)->limit(1)->fetchOne();
        if(!$info){
            return $this->error(400, '参数非法');
        }
        if($default){//设为默认
            $default_address = TrdUserDeliveryAddressTable::getDefaultAddressByHuuUid($hupuUid);
            if ($default_address){
                $default_address->setDefaultflag(0);
                $default_address->save();
            }
            $info->setDefaultflag(1);
            $info->save();
        } else {//取消默认
            $info->setDefaultflag(0);
            $info->save();
        }
        return $this->success();
    }

    /**
     * 添加/修改 收获地址
     *
     * $serviceRequest = new tradeServiceClient();
    $serviceRequest->setMethod('address.save');
    $serviceRequest->setVersion('1.0');
    $serviceRequest->setApiParam('user_id', 18244227);
    $serviceRequest->setApiParam('data', array());
    $serviceRequest->setUserToken($request->getCookie('u'));
    $response = $serviceRequest->execute();
     *
     */
    public function executeSave()
    {
        $v = $this->getRequest()->getParameter('v');
        $hupuUid =  $userId = $this->getRequest()->getParameter('user_id',$this->getUser()->getAttribute('uid'));
        $data = $this->getRequest()->getParameter('data',array());
        $uname = $this->getUser()->getAttribute('username');//当前操作用户名

        if(!isset($data['id'])) $data['id'] = '';
        if(!isset($data['name'])) $data['name'] = '';
        if(!isset($data['identity_number'])) $data['identity_number'] = '';
        if(!isset($data['mobile'])) $data['mobile'] = '';
        if(!isset($data['phonesection'])) $data['phonesection'] = '';
        if(!isset($data['phonecode'])) $data['phonecode'] = '';
        if(!isset($data['phoneext'])) $data['phoneext'] = '';
        if(!isset($data['province'])) $data['province'] = '';
        if(!isset($data['city'])) $data['city'] = '';
        if(!isset($data['area'])) $data['area'] = '';
        if(!isset($data['street'])) $data['street'] = '';
        if(!isset($data['postcode'])) $data['postcode'] = '';
        if(!isset($data['defaultflag'])) $data['defaultflag'] = '';

        if(!$hupuUid){
            return $this->error(501, '未登录');
        }
        $errArray = array();
        if (!$data['name']){
            $errArray['name'] = '收货人不可为空';
        }
        if (preg_match("/\s/", $data['name'])) {
            $errArray['name'] = '收货人不合法';
        }
        $identity_number_check_falg = tradeCommon::idcard_verify_number($data['identity_number']);
        if (!$identity_number_check_falg) {
            $errArray['identity_number'] = '身份证不合法';
        }

//        $identityNumberObj = TrdIdentityNumberValidateTable::getInstance()->findOneByIdentityNumber($data['identity_number']);
//        if (!$identityNumberObj){
//            $tradeBirdexNewService = new tradeBirdexNewService();
//            $identity = $tradeBirdexNewService->idcardValidate($data['identity_number'], $data['name'], 3);
//            if ($identity == 'failed'){
//                $errArray['phonesection'] = '身份证或姓名验证错误，请修改或更换';
//            }
//            if ($identity == 'success'){
//                $identityNumberObj = new TrdIdentityNumberValidate();
//                $identityNumberObj->setIdentityNumber($data['identity_number']);
//                $identityNumberObj->setName($data['name']);
//                $identityNumberObj->save();
//            }
//        } else if($identityNumberObj->getName() != $data['name']){
//            $errArray['phonesection'] = '身份证或姓名验证错误，请修改或更换';
//        }

        if (strlen($data['name']) > 25){
            $errArray['name'] = '收货人长度不超过25个字符';
        }
        if(!$data['mobile']){
            $errArray['mobile'] = '手机号码必填';
        }
        if($data['mobile'] && !preg_match("/^1[34578][0-9]{9}$/",$data['mobile'])){
            $errArray['mobile'] = '手机号码必须为11位数字';
        }
        if($data['phonesection'] && !preg_match("/^[0-9]{3,6}$/",$data['phonesection'])){
            $errArray['phonesection'] = '区号必须为3到6位数字';
        }
        if($data['phonecode'] && !preg_match("/^[0-9]{5,10}$/",$data['phonecode'])){
            $errArray['phonecode'] = '电话必须为5到10位数字';
        }
        if($data['phoneext'] && !preg_match("/^[0-9]{0,5}$/",$data['phoneext'])){
            $errArray['phoneext'] = '电话分机必须少于6个数字';
        }
        if (!$data['province'] || $data['province']=='请选择'){
            $errArray['province'] = '地区选择有误';
        }
        if (!$data['city'] || $data['city']=='请选择'){
            $errArray['city'] = '地区选择有误';
        }
        if (!$data['street']){
            $errArray['street'] = '地址详情不可为空';
        }
        if (strlen($data['street']) > 120){
            $errArray['street'] = '地址详情长度不超过120个字符';
        }
        if (!$data['postcode']){
            $errArray['postcode'] = '邮编不可为空';
        }
        if($data['postcode'] && !preg_match("/^[0-9]{6}$/",$data['postcode'])){
            $errArray['postcode'] = '收货地址需要6位的邮政编码';
        }
        if(!empty($errArray)){
            return $this->error(400, '收获地址有误',$errArray);
        }

        //判断用户收获地址总数
        $address_count = TrdUserDeliveryAddressTable::getAddressCountByHuuUid($hupuUid);
        if ($address_count >9){
            return $this->error(401, '收货地址数量已到最大');
        }

        $region_ids = array($data['province'],$data['city'],$data['area']);
        $region_info = TrdRegionTable::getByIds($region_ids);
        $region = "";
        foreach ($region_info as $k=>$v){
            $region .= $v['region_name'].' ';
        }

        //查找是否已经有默认地址了
        if($data['defaultflag'] == 1){
            $default_address = TrdUserDeliveryAddressTable::getDefaultAddressByHuuUid($hupuUid);
            if ($default_address){
                $default_address->setDefaultflag(0);
                $default_address->save();
            }
        }

        if(!empty($data['id'])){
            $info = TrdUserDeliveryAddressTable::getInstance()->find($data['id']);
        } else {
            $info = new TrdUserDeliveryAddress();
        }
        $info->setHupuUid($hupuUid);
        $info->setHupuUsername($uname);
        $info->setIdentityNumber($data['identity_number']);
        $info->setName($data['name']);
        $info->setPostcode($data['postcode']);
        $info->setProvince($data['province']);
        $info->setCity($data['city']);
        $info->setArea($data['area']);
        $info->setMobile($data['mobile']);
        $info->setPhonesection($data['phonesection']);
        $info->setPhonecode($data['phonecode']);
        $info->setPhoneext($data['phoneext']);
        $info->setRegion($region);
        $info->setStreet($data['street']);
        $info->setDefaultflag($data['defaultflag']);
        $info->save();
        $return_id = $info->getId();
        $new_address = $data['name'].' '.$region.$data['street'].'（邮编：'.$data['postcode'].'）'.' ';
        if ($data['mobile']){
            $new_address .= '手机：'.$data['mobile'];
        } else {
            $new_address .= '电话：'.$data['phonesection'].'-'.$data['phonecode'];
            if ($data['phoneext']) $new_address .= '-'.$data['phoneext'];
        }
        if(!empty($data['identity_number'])) $new_address .= ' 身份证：'.substr($data['identity_number'],0,5).'**********'.substr($data['identity_number'],15,3);
        return $this->success(array('id' => $return_id,'data'=>$new_address));
    }

    /**
     * 根据id获取收货地址
     *
     * $serviceRequest = new tradeServiceClient();
    $serviceRequest->setMethod('address.getDetail');
    $serviceRequest->setVersion('1.0');
    $serviceRequest->setApiParam('id', 18244227);
    $serviceRequest->setUserToken($request->getCookie('u'));
    $response = $serviceRequest->execute();
     *
     */
    public function executeGetDetail()
    {
        $id = $this->getRequest()->getParameter('id');
        $hupuUid =  $userId = $this->getRequest()->getParameter('user_id',$this->getUser()->getAttribute('uid'));
        if (empty($hupuUid) || !is_numeric($hupuUid)) {
            return $this->error(501, '未登录');
        }
        if(empty($id) || !is_numeric($id)){
            return $this->error(400, '参数错误');
        }
        $trdUserDeliveryAddress = TrdUserDeliveryAddressTable::getInfoByUidId($hupuUid,$id);

        if(empty($trdUserDeliveryAddress))
        {
            return $this->error(400, '参数错误');
        }

        $regionArr = explode(' ',$trdUserDeliveryAddress['region']);
        $trdUserDeliveryAddress['provinceName'] = $regionArr[0];
        $trdUserDeliveryAddress['cityName'] = $regionArr[1];
        $trdUserDeliveryAddress['areaName'] = $regionArr[2];
        if(empty($trdUserDeliveryAddress['identity_number'])) $trdUserDeliveryAddress['identity_number'] = '';
        if(empty($trdUserDeliveryAddress['phonesection'])) $trdUserDeliveryAddress['phonesection'] = '';
        if(empty($trdUserDeliveryAddress['phonecode'])) $trdUserDeliveryAddress['phonecode'] = '';
        if(empty($trdUserDeliveryAddress['phoneext'])) $trdUserDeliveryAddress['phoneext'] = '';

        return $this->success(array('data'=>$trdUserDeliveryAddress));
    }

    /**
     * 删除 收获地址
     *
     * $serviceRequest = new tradeServiceClient();
    $serviceRequest->setMethod('address.delete');
    $serviceRequest->setVersion('1.0');
    $serviceRequest->setApiParam('user_id', 18244227);
    $serviceRequest->setApiParam('id', 18244227);
    $serviceRequest->setUserToken($request->getCookie('u'));
    $response = $serviceRequest->execute();
     *
     */
    public function executeDelete()
    {
        $v = $this->getRequest()->getParameter('v');
        $id = $this->getRequest()->getParameter('id');
        $hupuUid =  $userId = $this->getRequest()->getParameter('user_id',$this->getUser()->getAttribute('uid'));
        if (empty($hupuUid) || !is_numeric($hupuUid)) {
            return $this->error(501, '未登录');
        }
        if(empty($id) || !is_numeric($id)){
            return $this->error(400, '参数错误');
        }
        $addressObj = TrdUserDeliveryAddressTable::getInstance()->createQuery()->delete()->where('id = ?', $id)->andWhere('hupu_uid = ?', $hupuUid)->execute();
        return $this->success();
    }

    /**
     *
     * 获取所有的省
     * @param int $level
     * @return array
     */
    public function executeGetAllRegionProvince(){
        $level = $this->getRequest()->getParameter('level',1);
        $result = Doctrine_Query::create()
            ->select('t.region_id, t.region_name')
            ->from('TrdRegion t')
            ->where('t.region_type = ?', $level)
            ->fetchArray();
        return $this->success(array('list'=>$result));
    }

    /**
     *
     * 根据id获取下级
     * @param int $level
     * @return array
     */
    public function executeGetNextRegionById(){
        $id = $this->getRequest()->getParameter('id');
        if(empty($id) || !is_numeric($id)){
            return $this->error(400, '参数错误');
        }
        $result = Doctrine_Query::create()
            ->select('t.region_id, t.region_name')
            ->from('TrdRegion t')
            ->where('t.parent_id = ?', $id)
            ->fetchArray();
        return $this->success(array('list'=>$result));
    }
}