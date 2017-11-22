<?php

/*
 * 该filter通过cookie判断用户是否登录
 * 如果未登录，则会通过cookie自动登录
 * 成功的话 setAuthenticated(true)，setAttribute('uid')，setAttribute('uusername')
 */

class checkUserLoginFilter extends sfFilter {

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

    public function checkLoginThoughCookie() {

        $passportClient = new PassportClient();

        if ($passportClient->iflogin()) {
            $passportUser = $passportClient->userinfo();            
            $this->getContext()->getUser()->setAttribute('uid', $passportUser['uid']);
            $this->getContext()->getUser()->setAttribute('username', $passportUser['username']);            
            $this->getContext()->getUser()->setAuthenticated(true);
        }else{
            $this->getContext()->getUser()->getAttributeHolder()->clear();
            $this->getContext()->getUser()->setAuthenticated(false);

            //新声新版测试用户退出则返回旧版
            $voice_test_cookie_name = 'set_vt_power';//cookie名称
            $original_cookie_value = $this->request->getCookie($voice_test_cookie_name);
            if(!empty($original_cookie_value)){
                $this->response->setCookie($voice_test_cookie_name,'', time()-3600, '/'); //删除cookie
                sfContext::getInstance()->getController()->redirect($this->request->getUri());
            }
            
        }                        
    }
}
