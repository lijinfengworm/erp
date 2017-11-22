<?php

class liangleAccessLogFilter extends sfFilter {

    public function initialize($context, $parameters = array()) {
        parent::initialize($context, $parameters);
        $this->context = $context;
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
            BackendUserTable::addLiangleCredentialByUid($passportUser['uid']);
            $this->getContext()->getUser()->setAuthenticated(true);
        }else{
            $this->getContext()->getUser()->getAttributeHolder()->clear();
            $this->getContext()->getUser()->setAuthenticated(false);
        }
                

    }

}
