<?php

/**
 * kol专用服务类
 * Class kolKaluliService
 */
class kolKaluliService extends kaluliService
{
    /**
     * 获取kol订单列表
     */
    public function executeGetOrders()
    {
        $kolId = $this->getRequest()->getParameter("kolId");
        $startTime = $this->getRequest()->getParameter("startTime");
        $endTime = $this->getRequest()->getParameter("endTime");

        $_page_now = $this->getRequest()->getParameter("page", 1);
        $_page_num = $this->getRequest()->getParameter("pageSize", 10);
        $type = $this->getRequest()->getParameter("type",1); //1.拿所有已支付订单 2.拿待确认订单
        //获取记录总数
        $_count_map['select'] = 'count(distinct(order_number)) as num';
        $_count_map['limit'] = $_count_map['is_count'] = 1;
        $_count_map['where']['kol_id'] = 'kol_id = ' . $kolId;
        if($type ==1) {
            $_count_map['where']['start_time'] = 'order_time >=' . $startTime;
            $_count_map['where']['end_time'] = 'order_time <=' . $endTime;
            $_count_map['where']['status'] = "status !=0";
        } elseif($type ==2) {
            $_count_map['where']['status'] = "status in (1,2,5)";
            $_count_map['where']['flag'] = " flag = 0";

        }
        $count = KllKolOrderTable::getInstance()->getAll($_count_map);

        $bind['select'] = "*,sum(commision) as commision";
        $bind['where']['kol_id'] = 'kol_id = ' . $kolId;
        if($type ==1) {
            $bind['where']['start_time'] = 'order_time >=' . $startTime;
            $bind['where']['end_time'] = 'order_time <=' . $endTime;
            $bind['where']['status'] = "status !=0";
        } elseif($type ==2) {
            $bind['where']['status'] = "status in (1,2,5)";
            $bind['where']['flag'] = " flag = 0";

        }
        $page = new Core_Lib_Page(array('total_rows' => $count, 'list_rows' => $_page_num, 'now_page' => $_page_now));
        $bind['limit'] = $_page_num;
        $bind['offset'] = (($_page_now - 1) * $_page_num) . ',' . $page->list_rows;
        $bind['groupBy'] = "order_number";
        $orders = KllKolOrderTable::getInstance()->getAll($bind);
        if (!$orders) {
            return $this->error(500, "没有更多订单了");
        }
        //构造返回值
        $orders = self::buildReturnInfo($orders);
        return $this->success(array('orders' => $orders, 'count' => $count), 200, 'ok');
    }

    /**
     *获取子订单信息
     */
    public function executeGetSubOrders()
    {
        $orderNumber = $this->getRequest()->getParameter("orderNumber");
        if (empty($orderNumber)) {
            return $this->error("500", "订单编号不存在");
        }
        $list = KllKolOrderTable::getInstance()->createQuery()->where("order_number = ?", $orderNumber)->fetchArray();
        if (!$list) {
            return $this->error(500, "不存在子订单数据");
        }
        $list = self::buildReturnInfo($list);
        return $this->success(array("list" => $list));
    }


    public function executeGetAccountLog()
    {
        $kolId = $this->getRequest()->getParameter("kolId");
        $startTime = $this->getRequest()->getParameter("startTime");
        $endTime = $this->getRequest()->getParameter("endTime");

        $_page_now = $this->getRequest()->getParameter("page", 1);
        $_page_num = $this->getRequest()->getParameter("pageSize", 20);
        //获取记录总数
        $_count_map['select'] = 'count(id) as num';
        $_count_map['limit'] = $_count_map['is_count'] = 1;
        $_count_map['where']['kol_id'] = 'kol_id = ' . $kolId;
        $_count_map['where']['start_time'] = 'ct_time >="' . $startTime . '"';
        $_count_map['where']['end_time'] = 'ct_time <="' . $endTime . '"';
        $count = KllKolAccountLogTable::getInstance()->getAll($_count_map);

        $bind['where']['kol_id'] = 'kol_id = ' . $kolId;
        $bind['where']['start_time'] = 'ct_time >="' . $startTime . '"';
        $bind['where']['end_time'] = 'ct_time <="' . $endTime . '"';
        $page = new Core_Lib_Page(array('total_rows' => $count, 'list_rows' => $_page_num, 'now_page' => $_page_now));
        $bind['limit'] = $_page_num;
        $bind['offset'] = (($_page_now - 1) * $_page_num) . ',' . $page->list_rows;
        $list = KllKolAccountLogTable::getInstance()->getAll($bind);
        if (!$list) {
            return $this->error(500, "没有更多数据了");
        }
        foreach ($list as $k => $v) {
            $list[$k]['ct_time'] = substr($v['ct_time'], 5, 5);
        }
        return $this->success(array('list' => $list, 'count' => $count), 200, 'ok');
    }

    /**
     * 根据开始结束时间获取订单
     */
    public function executeGetOrderNumbers()
    {
        $startTime = $this->getRequest()->getParameter("startTime");
        $endTime = $this->getRequest()->getParameter("endTime");
        $kolId = $this->getRequest()->getParameter("kolId");
        //获取记录总数
        $_count_map['select'] = 'count(distinct(order_number)) as num';
        $_count_map['limit'] = $_count_map['is_count'] = 1;
        $_count_map['where']['kol_id'] = 'kol_id = ' . $kolId;
        $_count_map['where']['start_time'] = 'order_time >=' . $startTime;
        $_count_map['where']['end_time'] = 'order_time <=' . $endTime;
        $_count_map['where']['status'] = 'status != 0';
        $count = KllKolOrderTable::getInstance()->getAll($_count_map);
        if (!$count) {
            $count = 0;
        }
        return $this->success(array("count" => $count));
    }

    public function executeGetKolChannel()
    {
        $channel = $this->getRequest()->getParameter("channel");
        if (empty($channel)) {
            return $this->error("500", "参数错误");
        }
        $channelInfo = KllKolChannelTable::getInstance()->findOneByChannelCodeAndStatus($channel, 1);
        if (!$channelInfo) {
            //获取不到拿默认的平台
            $channelInfo = KllKolChannelTable::getInstance()->findOneByChannelCode("pingtai");
        }
        return $this->success(array("channelInfo" => $channelInfo->toArray()));
    }


    private static function buildReturnInfo($list)
    {
        if (empty($list)) return array();
        //构造返回值
        foreach ($list as $k => $v) {
            $list[$k]['order_time'] = date("Y.m.d", $v['order_time']);
            switch ($v['status']) {
                case 0:
                    $list[$k]['statusName'] = "待付款";
                    break;
                case 1:
                    $list[$k]['statusName'] = "已付款";
                    break;
                case 2:
                    $list[$k]['statusName'] = "已发货";
                    break;
                case 3:
                    $list[$k]['statusName'] = "取消";
                    break;
                case 4:
                    $list[$k]['statusName'] = "退货";
                    break;
                case 5:
                    $list[$k]['statusName'] = "完成";
                    break;
            }
        }
        return $list;
    }

    public function executeGetKolInfo()
    {
        $kolId = $this->getRequest()->getParameter("kolId");
        if (empty($kolId)) {
            return $this->error("500", "参数错误");
        }
        $kolInfo = KllKolTable::getInstance()->findOneByIdAndStatus($kolId, 1);
        if (!$kolInfo) {
            return $this->error("500", "达人不存在");
        }
        $ctTime = strtotime($kolInfo->getCtTime());
        $days = ceil((time() - $ctTime) / (3600 * 24));
        $kolInfo = $kolInfo->toArray();
        $kolInfo['days'] = abs($days);

        return $this->success(array("kolInfo" => $kolInfo));

    }

    public function executeCheckIsKol()
    {
        $userId = $this->getRequest()->getParameter("userId");
        if (empty($userId)) {
            return $this->error("500", "参数错误");
        }
        $kol = KllKolTable::getInstance()->findOneByUserId($userId);
        if ($kol) {
            return $this->error("501", "已经是达人了");
        }
        return $this->success();
    }

    public function executeRegisterKol()
    {
        $userId = $this->getRequest()->getParameter("userId");
        $account = $this->getRequest()->getParameter("account");
        $channelId = $this->getRequest()->getParameter("channelId");
        $nickName = $this->getRequest()->getParameter("nickName");
        $headImage = $this->getRequest()->getParameter("headImage");
        $wxUserName = $this->getRequest()->getParameter("wxUserName");
        if (empty($userId) || empty($account)  || empty($channelId) || empty($nickName)) {
            return $this->error("500", "缺少参数");
        }
        $userInfo = KllUserTable::getInstance()->findOneByUserId($userId);
        //验证微信用户名
        try {
            $db = Doctrine_Manager::getInstance()->getConnection('kaluli');
            $db->beginTransaction();
            $checkName = KaluliFun::checkWord($wxUserName);
            if ($checkName["code"] == 0) {
                //存在非法的以原来用户名为准
                $userName = $userInfo->getUserName();
            } else {
                //判断是否存在同一个用户名
                $response = kaluliService::commonServiceCall("user", "check.user.name", ['uid' => $userId, 'userName' => $wxUserName]);
                if ($response->hasError()) {
                    $userName = $userInfo->getUserName();
                } else {
                    $userName = $wxUserName;
                    //更新用户表,用户属性表
                    $userInfo->setUserName($wxUserName);
                    $userInfo->save();
                    $userProperty = KllUserPropertyTable::getInstance()->findOneByUserId($userId);
                    $userProperty->setUserName($wxUserName);
                    $userProperty->save();
                    //更新完毕重置cookie
                    $expire = strtotime("1 years");
                    setcookie('u', $userId . '-' . $userName, $expire, '/', 'kaluli.com', null, true);

                }
            }

            //注册成为达人
            $return = self::createBenefits($channelId);
            if ($return['status'] == 0) {
                return $this->error("500", $return["msg"]);
            }
            //保存kol内容
            $kol = new KllKol();
            $kol->setAccount($account);
            $kol->setUserId($userId);
            $kol->setUserName($userName);
            $kol->setCommision($return['commision']);
            $kol->setMobile($userInfo->getMobile());
            $kol->setChannelId($channelId);
            $kol->setHeadImage($headImage);
            $kol->setNickName($nickName);
            $kol->save();
            //生成kol账户
            $kolAccount = new KllKolAccount();
            $kolAccount->setKolId($kol->getId());
            $kolAccount->save();
            $db->commit();
        }catch(Exception $e) {
            $db->rollback();
            return $this->error("500",$e->getMessage());
        }
        return $this->success(array("kolId" => $kol->getId()));

    }

    //获取达人优惠码
    public function executeGetCodeByKol(){
        $kolId = $this->getRequest()->getParameter("kolId");
        $kolInfo = KllKolTable::getInstance()->findOneByIdAndStatus($kolId,1);
        if(!$kolInfo){
            return $this->error("500","达人不存在");
        }
        //获取达人优惠码
        $benefitsInfo = KllMemberBenefitsTable::getInstance()->findOneByIdAndStatus($kolInfo->getBenefitsId(),1);
        if(!$benefitsInfo) {
            return $this->error("500","优惠码不存在或者已过期");
        }
        return $this->success(array("benefitsInfo"=>$benefitsInfo->toArray(),"kolInfo"=>$kolInfo->toArray()));
    }

    /**
     * 根据渠道创建会员权益
     * @param $code
     * @param $channelId
     */
    private static function createBenefits($channelId)
    {
        $channel = KllKolChannelTable::getInstance()->findOneByIdAndStatus($channelId, 1);
        if (!$channel) {
            return ['status' => 0, "msg" => "渠道信息不存在"];
        }

        return ['status' => 1,"commision" => $channel->getCommision()];

    }
}