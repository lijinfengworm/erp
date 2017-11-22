<?php

/*
 * 该filter通过cookie判断用户是否登录
 * 如果未登录，则会通过cookie自动登录
 * 成功的话 setAuthenticated(true)，setAttribute('uid')，setAttribute('uusername')
 */

class voiceCheckUserLoginFilter extends sfFilter {

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
            
            //针对新声内页nginx层增加fast_cgi_cache缓存时进行一些权限的判断的cookie设置
            if($this->getContext()->getUser()->hasAttribute('uid')){
                //set_nwc = set_nginx_web_cache
                $voice_cookie_name = 'set_nwc_info';
                $cookie_uid = 0;
                
                $original_cookie_value = $this->request->getCookie($voice_cookie_name);
                if(!empty($original_cookie_value)){
                    $cookie_info = explode('|', $original_cookie_value);
                    $cookie_uid = (int)$cookie_info[0];
                }
                
                if((int)$this->getContext()->getUser()->getAttribute('uid') != $cookie_uid){
                    if($this->getContext()->getUser()->isVoiceWhitelistUser() || $this->getContext()->getUser()->isWebVoiceDel()){
                         $this->response->setCookie($voice_cookie_name, (int)$this->getContext()->getUser()->getAttribute('uid') . '|no', time()+7*24*3600, '/');//不进行nginx_web_cache
                     }else{
                         $this->response->setCookie($voice_cookie_name, (int)$this->getContext()->getUser()->getAttribute('uid') . '|yes', time()+7*24*3600, '/');//进行nginx_web_cache
                     }
                 }

                //用户当天在线时长统计
                userRankProc::getInstance()->addUserOnlineTimeCountHash((int)$this->getContext()->getUser()->getAttribute('uid'));
            }
             
        }else{
            $this->getContext()->getUser()->getAttributeHolder()->clear();
            $this->getContext()->getUser()->setAuthenticated(false);
        }                        
    }
}
