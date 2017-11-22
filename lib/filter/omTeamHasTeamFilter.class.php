<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class omTeamHasTeamFilter extends sfFilter {

    public function initialize($context, $parameters = array()) {
        parent::initialize($context, $parameters);

        $this->cacheManager = $context->getViewCacheManager();
        $this->request = $context->getRequest();
        $this->response = $context->getResponse();
        $this->routing = $context->getRouting();
    }

    public function execute($filterChain) {
        $this->checkHasTeamInfo();
        $filterChain->execute();
    }

    public function checkHasTeamInfo() {
        //$this->getContext()->getUser()->getAttributeHolder()->clear();
        $userId = intval($this->getContext()->getUser()->getAttribute('uid'));
        $resultset = omTeamTable::checkUserTeam($userId);

        $modelName = $this->getContext()->getModuleName();
        $actionName = $this->getContext()->getActionName();
        if (!$resultset) {
            $state = $this->ifNeedHasTeamInfo($modelName, $actionName);
            if ($state && $modelName != "ommatchWaring") {
                if ($modelName == "game" && $actionName == "toMatch") {
                    WaringsPage::tableRedirect404('没有球队可没办法约战，快带着你的球队加入我们吧！', 'matchWaring');
                    die();
                } else {
                    WaringsPage::tableRedirect404('没有球队可没办法约战，快带着你的球队加入我们吧！');
                    die();
                }
            }
            $this->getContext()->getUser()->setAuthenticated(true);
        } else {
            $this->getContext()->getUser()->setAuthenticated(true);
            $status = $resultset->status; 
            $this->getContext()->getUser()->setAttribute('hasTeam', true);  //有球队 不管球队是否审核通过
            if ($status != omTeamTable::$Admin_Team_Checked) {
                if ($actionName != "waiteForCheck") {
                    $state = $this->ifNeedHasTeamInfo($modelName, $actionName);
                    
//                    if (($state && $modelName != "ommatchWaring" ) || ($modelName == "omTeam" && $actionName == "register" )) {
                    if ($modelName == "omTeam" && $actionName == "register" ) {
                        $url = $this->getContext()->getController()->genUrl('@register_step_2', true);
                        $this->redirectUrl($url, $actionName);
                        die();
                    }
                }
            } else {

                if ($modelName == "omTeam" && ($actionName == "register" || $actionName == "waiteForCheck" )) {
                    $url = $this->getContext()->getController()->genUrl('register_step_3', true);
                    $this->redirectUrl($url, $actionName);
                    die();
                }
            }

//            if(!$this->getContext()->getUser()->hasAttribute('teamQqGroup')){
            $teamQqGroup = omQQGroupTable::getTeamQqNumbers($resultset->id);
            $this->getContext()->getUser()->setAttribute('teamQqGroup', $teamQqGroup);
//            }
//            if(!$this->getContext()->getUser()->hasAttribute('teamAvgHigh')){
            $this->getContext()->getUser()->setAttribute('teamAvgHigh', $resultset->avg_high);
//            }
//            if(!$this->getContext()->getUser()->hasAttribute('teamAvgAge')){
            $this->getContext()->getUser()->setAttribute('teamAvgAge', $resultset->avg_age);
            $this->getContext()->getUser()->setAttribute('teamAvgWight', $resultset->avg_weight);
            $this->getContext()->getUser()->setAttribute('teamLeaderName', $resultset->leader_name);
            $this->getContext()->getUser()->setAttribute('logoUrl', $resultset->logo_url);
            $this->getContext()->getUser()->setAttribute('mobile', $resultset->mobile);
            $this->getContext()->getUser()->setAttribute('leader_name', $resultset->leader_name);
            $this->getContext()->getUser()->setAttribute('leader_id_number', $resultset->leader_id_number);
            $this->getContext()->getUser()->setAttribute('leader_qq_number', $resultset->leader_qq_number);
//            }
//            if(!$this->getContext()->getUser()->hasAttribute('hasball')){
            $this->getContext()->getUser()->setAttribute('hasball', $resultset->hasball);
//            }
//            if(!$this->getContext()->getUser()->hasAttribute('hasreferee')){
            $this->getContext()->getUser()->setAttribute('hasreferee', $resultset->hasreferee);
//            }
//            if(!$this->getContext()->getUser()->hasAttribute('uniform_color')){
            $this->getContext()->getUser()->setAttribute('uniform_color', $resultset->uniform_color);
//            }
//            if(!$this->getContext()->getUser()->hasAttribute('teamId')){
            //echo $resultset->id;
            $this->getContext()->getUser()->setAttribute('teamId', $resultset->id);
//            }
//            if(!$this->getContext()->getUser()->hasAttribute('teamName')){
            $this->getContext()->getUser()->setAttribute('teamName', $resultset->name);
//            }
        }
    }

    private function redirectUrl($url, $actionName) {
        if ($this->request->isXmlHttpRequest()) {
            if ($actionName == "needHelp") {
                echo "只有审核通过的球队才可以申请管理员协助";
            } else {
                echo '<script>window.location.href="' . $url . '"</script>';
            }
        } else {
            $this->getContext()->getController()->redirect($url);
        }
    }

    /*
     * 是否需要拥有球队
     * @param string $modelName 模块名称
     * @param string $actionName action名称
     */

    public function ifNeedHasTeamInfo($modelName, $actionName) {
        $exceptActionName = array(
            'omTeam' => array(
                'showHomepage', //别队球队主页
                'index', //个人球队日历页面
                'register', //注册页面
                'getPlaceByAjax', //ajax请求地址
                'getActivity',
                'activeTeam',
                'teamInfo',
            ),
            'game' => array(
                'index', //首页
                'waitForMatch', //约战详情1
                'matchDetail', //约战详情
                'calendarIndex', //约战日历中心
                'teamList', //球队列表
                'qqList', //qq列表
                'adminList', //制服组
                'everyStatusAppointment',
                'editAppointment',
                'about', //关于约战 
            ),
            'search' => array(
                'index', //搜索页面
            ),
            'ommatchWaring' => array(
                'index', //警告页面 
                'matchWaring',
            ),
            'matchs' => array(
                'index', //约战2期首页 
                'detail',
                'applying',
                'applyend',
                'process',
                'end',
                'applying1',
                'applying2',
                'getPlacesByMatch_idAndCity_id',
                'selectteams',
                'checkteamname',
            ),
        );
        if (isset($exceptActionName[$modelName])) {
            if (in_array($actionName, $exceptActionName[$modelName])) {
                return false;
            }
        }
        return true;
    }

}