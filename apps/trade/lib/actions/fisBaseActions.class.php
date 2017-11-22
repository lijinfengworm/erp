<?php

class tradeFisBaseActions extends FisBaseActions
{

    protected $_common = array(

    );

    public function initialize($context, $moduleName, $actionName)
    {
        parent::initialize($context, $moduleName, $actionName);
        $this->initCommon();

        $this->assign('_Common', $this->_common);

    }


    private function initCommon()
    {
        # 搜索栏tags
        $all_hotSearch_key = 'trade_search_all_hotSearch';
        $redis = sfContext::getInstance()->getDatabaseConnection('kaluliRedis');
        $res =   unserialize($redis->get($all_hotSearch_key));
        $this->_common['nav_tags'] = $res;

        # 消息数 购物车数
        $number = $userNoticesCount = '';
        $uid = $this->_user['uid'];
        if(!empty($uid))
        {
            $redis = sfContext::getInstance()->getDatabaseConnection('kaluliRedis');
            $redis->select(1);
            $number = $redis->get('trade_shopping_cart_'.$uid);

            $userNoticesCount = TrdNoticesCountTable::getCount($uid);
            if(is_array($userNoticesCount) && !empty($userNoticesCount))
            {
                $userNoticesCount = array_sum($userNoticesCount);
            }
            else
            {
                $userNoticesCount = 0;
            }
        }

        $this->_user['cart_number'] = $number;
        $this->_user['notice_number'] = $userNoticesCount;

        $this->assign('_User', $this->_user);
    }
}