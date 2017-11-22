<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class omTeamUserHasLoginFilter extends sfFilter {

    public function initialize($context, $parameters = array()) {
        parent::initialize($context, $parameters);

        $this->cacheManager = $context->getViewCacheManager();
        $this->request = $context->getRequest();
        $this->response = $context->getResponse();
        $this->routing = $context->getRouting();
    }

    public function execute($filterChain) {
        $this->checkCookie();
        $filterChain->execute();
    }

    public function checkCookie() {
        if(!strpos($this->request->getReferer(), 'backend')){       //当用户从backend跳过来时 不做任何处理
            $passportClient = new PassportClient('hoopchina');
            if ($passportClient->iflogin()) {
                $passportUser = $passportClient->userinfo();
                if (!$this->getContext()->getUser()->isAuthenticated() || $this->getContext()->getUser()->getAttribute('uid') != $passportUser['uid']){
                    $this->getContext()->getUser()->setAttribute('uid', $passportUser['uid']);
                    $this->getContext()->getUser()->setAttribute('username', $passportUser['username']);
                    $this->getContext()->getUser()->setAuthenticated(true);
                }
                if ($resultset = omTeamTable::checkUserTeam($passportUser['uid'])) {                
                    $this->getContext()->getUser()->setAttribute('hasTeam', true);
                    $this->getContext()->getUser()->setAttribute('teamId', $resultset->id);
                    $this->getContext()->getUser()->setAttribute('teamName', $resultset->name);
                    $this->getContext()->getUser()->setAttribute('mobile', $resultset->mobile);
                    $this->getContext()->getUser()->setAttribute('leader_name', $resultset->leader_name);
                    //$this->getContext()->getUser()->setAttribute('teamLeaderName', $resultset->leader_name);
                    $this->getContext()->getUser()->setAttribute('teamAvgHigh',(int) $resultset->avg_high);
                    $this->getContext()->getUser()->setAttribute('teamAvgWight',(int) $resultset->avg_weight);
                    $this->getContext()->getUser()->setAttribute('logoUrl', $resultset->logo_url);
                }
            } else {

                $this->getContext()->getUser()->getAttributeHolder()->clear();
                $this->getContext()->getUser()->setAuthenticated(false);
            }
        }
    }    

}
