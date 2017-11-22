<?php

/*
 * 该filter通过cookie判断用户是否登录
 * 如果未登录，则会通过cookie自动登录
 * 成功的话 setAuthenticated(true)，setAttribute('uid')，setAttribute('uusername')
 */

class TradeUserLoginFilter extends sfFilter {

    public function initialize($context, $parameters = array()) {
        parent::initialize($context, $parameters);
        $this->cacheManager = $context->getViewCacheManager();
        $this->request = $context->getRequest();
        $this->response = $context->getResponse();
        $this->routing = $context->getRouting();
    }

    public function execute($filterChain) {
        $this->checkLoginThoughCookie();
        $filterChain->execute();
    }

    public function checkLoginThoughCookie() 
    {                   
        $passportClient = new PassportClient();      
        
        if ($passportClient->iflogin())
        {
            $passportUser = $passportClient->userinfo();
            
            $user = $this->getContext()->getUser();
            
            $user->setAttribute('uid', $passportUser['uid']);
            $user->setAttribute('username', $passportUser['username']);
            
            $this->getContext()->getUser()->setAuthenticated(true);
        }
        else
        {
            $this->getContext()->getUser()->getAttributeHolder()->clear();
            $this->getContext()->getUser()->setAuthenticated(false);
        }                        
    }    
}
