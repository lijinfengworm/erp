<?php

class hcAccessLogFilter extends sfFilter {

    public function initialize($context, $parameters = array()) {
        parent::initialize($context, $parameters);
        $this->context = $context;
        $this->cacheManager = $context->getViewCacheManager();
        $this->request = $context->getRequest();
        $this->response = $context->getResponse();
        $this->routing = $context->getRouting();
    }

    public function execute($filterChain) {
        $this->checkCookie();
//        $this->checkPlatform();
        $filterChain->execute();
    }

    public function checkCookie() {
        $passportClient = new PassportClient();
        if ($passportClient->iflogin()) {
            $passportUser = $passportClient->userinfo();
            $this->getContext()->getUser()->setAttribute('uid', $passportUser['uid']);
            $this->getContext()->getUser()->setAttribute('username', $passportUser['username']);
            $this->getContext()->getUser()->setAuthenticated(true);
            $groups = pw_membersTable::getInstance()->getGroupsByUid($passportUser['uid']);
            $this->getContext()->getUser()->setAttribute('groups', $groups);
            if (in_array(6, explode(',', $groups))) {
                $this->getContext()->getUser()->setAttribute('isBan', true);
            }
        }else{
            $this->getContext()->getUser()->getAttributeHolder()->remove('uid');
            $this->getContext()->getUser()->getAttributeHolder()->remove('username');
            $this->getContext()->getUser()->getAttributeHolder()->remove('groups');
            $this->getContext()->getUser()->getAttributeHolder()->remove('isBan');
            //$this->getContext()->getUser()->getAttributeHolder()->clear();
            $this->getContext()->getUser()->setAuthenticated(false);
        }          
    }
   public function checkPlatform() {
        $request = $this->getContext()->getRequest();
        $androidcookie = $request->getCookie('cookieWapPlatform');    
        if (stripos($request->getHttpHeader('User-Agent'), 'android') !== FALSE && $request->getUri() !== 'http://mobile.hupu.com/#app' && !isset($androidcookie) && !$request->isMethod('post') && !$request->hasParameter('platform')) {
            $env = sfContext::getInstance()->getConfiguration()->getEnvironment();
            $script_name = $env == 'prod' ? '' : 'mobile_' . $env . '.php/';
            $this->getContext()->getController()->redirect('http://' . $request->getHost() . '/' . $script_name . 'wapplatform?platform=android&referer=' . urlencode($request->getUri()));
            exit;
        } elseif ((stripos($request->getHttpHeader('User-Agent'), 'iphone') !== FALSE || stripos($request->getHttpHeader('User-Agent'), 'ipod') !== FALSE ) && $request->getUri() !== 'http://mobile.hupu.com/#app' && !isset($androidcookie) && !$request->isMethod('post') && !$request->hasParameter('platform')) {
            $env = sfContext::getInstance()->getConfiguration()->getEnvironment();
            $script_name = $env == 'prod' ? '' : 'mobile_' . $env . '.php/';
            $this->getContext()->getController()->redirect('http://' . $request->getHost() . '/' . $script_name . 'wapplatform?platform=apple&referer=' . urlencode($request->getUri()));
            exit;
        }
    }

}

