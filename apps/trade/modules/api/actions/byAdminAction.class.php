<?php
/*
 *后台基础接口
 **/
Class byAdminAction extends sfActions
{
    public function executeByAdmin(sfWebRequest $request)
    {
        sfConfig::set('sf_web_debug', false);
        header("Content-type:text/html;charset=utf-8");

        //action
        $this->_request = $request;
        $act = $request->getParameter('act');
        if (method_exists($this, $act)) {
            if ($request->isXmlHttpRequest()) {
                return $this->$act();
            } else {
                return $this->$act();
                exit;
            }
        }
    }

    //七牛 base64
    private function base64(){
        $text = $this->_request->getParameter('text');
        $text = urldecode($text);
        $text = FunBase::base64ForQiniu($text);
        return $this->renderText(json_encode(
            array('text'=>$text))
        );
    }
}