<?php

/**
 * 后台模块
 * About 梁天
 */
class publicActions extends AdminBaseAction {

    /**
     * 用户登录
     */
    public function executeLogin(sfWebRequest $request) {
        if(UserService::getInstance()->isLogin()) {
            $this->redirect('@welcome');
        }
        if ($request->isMethod('post')) {
            $userData = $request->getParameter('login');
            if (empty($userData['username']) || empty($userData['password'])) {
                $this->ajaxReturn(array('status'=>0,'info'=>'用户名或者密码不能为空，请重新输入！'));
            }
            try {
                UserService::getInstance()->login($userData['username'],$userData['password']);
                $this->ajaxReturn(array('status'=>1,'info'=>'登录成功！','url'=>$this->getController()->genUrl("@welcome")));
            }catch(sfException $e) {
                $this->ajaxReturn(array('status'=>0,'info'=>$e->getMessage()));
            }
        }
    }



    /**
     * 后台欢迎页面
     */
    public function executeWelcome(sfWebRequest $request) {
        $_role_data = $this->getUser()->getTrdRole();
        $this->setVar('role',current($_role_data['role_item']) ? current($_role_data['role_item']) : '未知用户组');
    }



    public  function executeAaa(sfWebRequest $request) {

        try {



            //FunBase::myDebug($shop);

            $edb = KaluliFun::getObject('KllErpDriver'.sfConfig::get('app_kll_erp_type'));


            //先获取订单
            /*
                     $api = $edb->builder('edbTradeGet');
                     $api->setOutTid('1601233738745363-5313');
                     $api->setBeginTime(date('Y-m-d H:i:s',time()-86400*100));
                     $api->setEndTime(date('Y-m-d H:i:s',time()));
                     $result  = $edb->exec($api,'post');
                     $dd = json_decode($result['result'],true);

                     FunBase::myDebug($dd);



                               //FunBase::myDebug($tid);


                                          //撤销订单
                                          $api = $edb->builder('edbTradeCancel');
                                          $api->setTid($tid);
                                          $api->setXmlValues();
                                          $result  = $edb->exec($api,'post');
                                          $order = json_decode($result['result'],true);

                                          FunBase::myDebug($order);


                                              */


            //写入订单
           //KllEdbSyncService::getInstance()->sync('order_create',array('_id'=>1650));
            KllEdbSyncService::getInstance()->sync('order_express',array('id'=>1580));

            exit();



            //获取商品基本信息
            /*
            $api = $edb->builder('edbProductBaseInfoGet');
            $api->setBarCode('HK748927028706');
            $result  = $edb->exec($api,'post');
            $order = json_decode($result['result'],true);
        */

            //获取商品库存
            /*
            $api = $edb->builder('edbProductGet');
            $api->setBarCode($shop['code']);
            $api->setStandard($shop['ware_sku']);
            $result  = $edb->exec($api,'post');
            $order = json_decode($result['result'],true);
            */









            //获取订单
            /*
            $api = $edb->builder('edbTradeGet');
            $api->setOutTid('1601074661986448-1578');
            $api->setBeginTime(date('Y-m-d H:i:s',time()-86400*100));
            $api->setEndTime(date('Y-m-d H:i:s',time()));
            $result  = $edb->exec($api,'post');
            $order = json_decode($result['result'],true);
            */




            /*  写入订单 */
            /*
            $api = $edb->builder('edbTradeAdd');
            $api->setOutTid('1234567');
            $api->setShopId('2');
            $api->setStorageId('1');
            $api->setOrderDate(date('Y-m-d H:i:s',time()));
            $api->setBarCode('QI14121100001');
            $api->setProductTitle('卡路里商城');
            $api->setStandard('11222');
            $api->setXmlValues();
            $result  = $edb->exec($api,'post');
            $order = json_decode($result['result'],true);
            */

            FunBase::myDebug($order);
            exit();
        } catch(sfException $e) {
            FunBase::myDebug($e->getMessage());
        }

        return sfView::NONE;
    }














    /**
     * 错误页面
     */
    public function executeShowError() {

    }


    /**
     * 退出登录
     */
    public function executeLogout(sfWebRequest $request) {
        if (UserService::getInstance()->logout()) {
            $this->redirect("login");
        }
    }



}
