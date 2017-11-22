<?php
/**
 * Created by PhpStorm.
 * User: libin
 * Date: 2017/5/11
 * Time: 下午9:09
 */


class BaseActions extends sfActions {

    protected $_newUserCoupon = array(
        'couponFee' => 0,  //优惠券金额
        'isShow' => 0,   //是否显示 1.显示优惠券 0.不显示优惠券
    );


    public function initialize($context, $moduleName, $actionName)
    {
        parent::initialize($context, $moduleName, $actionName);

        //$this->initNewUserCoupon($moduleName, $actionName);
    }


    //初始化优惠券配置
    protected function initNewUserCoupon($moduleName,$actionName)
    {
        //增加页面显示逻辑配置
        if(in_array($moduleName,['index','item','activityTemplate','cms','activity'])) {
            $uid = $this->getUser()->getAttribute('uid');
            $return = FunBase::checkUserNewCoupon($uid);
            if ($return['status'] == 2) {
                $activity = KllSendCouponOrderTable::getInstance()->createQuery()->where("position = ?", 2)->andWhere("state = ?", 1)
                    ->andWhere("s_time <?", time())->andWhere("e_time > ?", time())->andWhere("channel_id = 4")->fetchOne();
                if ($activity) {
                    $recordArr = explode("|", $activity->record_id);
                    $totalCouponPrice = 0;
                    foreach ($recordArr as $recordId) {
                        $couponRecord = KaluliLipinkaRecordTable::getInstance()->findOneById($recordId);
                        $price = ($couponRecord->amount) / ($couponRecord->num);
                        $totalCouponPrice += $price;
                    }
                    $this->_newUserCoupon['couponFee'] = $totalCouponPrice;
                    $this->_newUserCoupon['isShow'] = 1;
                }
            }
        }
        $this->newUserCoupon = $this->_newUserCoupon;

    }




}