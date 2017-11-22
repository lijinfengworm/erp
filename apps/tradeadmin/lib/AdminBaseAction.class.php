<?php
/**
 * 后台 action 基类
 * 权限控制  封装常用方法
 * 注意：如果是自动生成的后台控制器 也必须要继承此类
 *   - 在 generator.yml 里配置 actions_base_class: AdminBaseAction
 * User: 梁天
 * Date: 2015/3/5
 */
class AdminBaseAction extends sfActions {


    /**
     * 后台初始化构造方法
     * 梁天  2015-03-5
     */
    public function initialize($context, $moduleName, $actionName){
        parent::initialize($context, $moduleName, $actionName);
        sfConfig::set('sf_web_debug', true);  //   关闭debug
        try {
            AuthMenu::CheckLogin();
            AuthMenu::AccessDecision();
        } catch (sfException $e) {
            if(UserService::getInstance()->isLogin()) {
                $this->showErrorExit($e->getMessage());
            } else {
                $this->redirect("@login");
            }
        }
        $this->logOpt();

        $this->setSearchShow();
    }


    /**
     * 判断搜索是否展示
     */
    public function setSearchShow() {
        $_is_show = false;
        $field = $this->getUser()->getAttribute(AuthMenu::getController().'.filters',NULL,'admin_module');
        $_field_key = array('text','from','to');
        if(!empty($field)) {
            foreach ($field as $key => $val) {
                foreach ($_field_key as $k => $v) {
                    if (isset($val[$v]) && !empty($val[$v])) {
                        $_is_show = true;
                    }
                }
            }
        }
        if(!empty($_is_show)) {
            $this->getUser()->setAttribute(AuthMenu::getController().'.show_search',1,'admin_module');
        } else {
            $this->getUser()->setAttribute(AuthMenu::getController().'.show_search',NULL,'admin_module');
        }
    }


    /**
     * Ajax方式返回数据到客户端
     * @access protected
     * @param mixed $data 要返回的数据  status 必填  1 成功  0 失败
     * @param String $type AJAX返回数据格式
     * @return void
     * 如果成功  $this->ajaxReturn(array('status'=>1,'data'=>'','info'=>'aaa','url'=>''));
     * 如果失败  $this->ajaxReturn(array('status'=>0,'data'=>'','info'=>'aaa'));
     */
    protected  function ajaxReturn($data, $type = '') {
        if (empty($type))  $type = sfConfig::get('app_codeConfig_def_ajax_type');
        FunBase::ajaxReturn($data,$type);
    }


    /**
     * 快捷ajaxReturn 方法 返回错误
     */
    protected function ajaxError($info = '',$url = '') {
        FunBase::ajaxReturn(array('status'=>0,'info'=>$info,'url'=>$url));
    }

    /**
     * 快捷ajaxReturn 方法 返回成功
     */
    protected function ajaxSuccess($info = '',$data = '',$url = '') {
        FunBase::ajaxReturn(array('status'=>1,'data'=>$data,'info'=>$info,'url'=>$url));
    }



    /**
     * 返回错误页面
     * @param $info  错误信息
     * @param string $jumpUrl  跳转网页 默认回前一页
     * @param int $waitSecond  跳转等待时间
     */
    protected function showError($info = '',$jumpUrl = '',$waitSecond = 5) {
        if(empty($jumpUrl)) $jumpUrl = !empty($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : $this->getController()->genUrl("@welcome") ;
        //判断是否是ajax跳转
        if(FunBase::is_ajax()) {
            $this->ajaxError($info,$jumpUrl);
        }
        $this->setVar('message',$info,true);
        $this->setVar('waitSecond',$waitSecond,true);
        $this->setVar('jumpUrl',$jumpUrl,true);
        $this->setTemplate('error','public');
    }


    /**
     * 返回错误页面 停止执行
     */
    protected function showErrorExit($info = '',$jumpUrl = '') {
        if(empty($jumpUrl)) $jumpUrl = !empty($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : $this->getController()->genUrl("@welcome") ;
        //判断是否是ajax跳转
        if(FunBase::is_ajax()) {
            $this->ajaxError($info,$jumpUrl);
        }
        $_html = '<html><head><title>错误</title></head><body>';
        $_html .= '<style type="text/css">';
        $_html .= '*{ padding: 0; margin: 0; }';
        $_html .= 'body{ background: #290C0C !important; font-family: "微软雅黑"; font-size: 16px; }';
        $_html .= '#error_exit_box{ padding: 24px 48px; color: #fff; }';
        $_html .= '#error_exit_box h1 { font-size: 80px; font-weight: normal; line-height: 120px; margin-bottom: 12px }';
        $_html .= '#error_exit_box a { color:#fff; }';
        $_html .= '</style>';
        $_html .= '<div id="error_exit_box"><h1>'.$info.'</h1>';
        $_html .= '<a href="'.$jumpUrl.'">返回上上一页</a> | <a href="'.$this->getController()->genUrl("@welcome").'">返回首页</a>';
        $_html .= '</div></body></html>';
        header('Content-Type:text/html;charset=utf-8');
        exit($_html);
    }



    /**
     * 存储用户搜索数据
     */
    protected function setSearchKey($key = '',$val = '') {
        $search = $this->getUser()->getAttribute('search_'.AuthMenu::getController());
        $search[$key] = $val;
        $this->getUser()->setAttribute('search_'.AuthMenu::getController(),$search);
    }


    /**
     * 获取用户搜索数据
     */
    protected function getSearchVal($key = '') {
        $search = $this->getUser()->getAttribute('search_'.AuthMenu::getController());
        if(isset($search[$key])) return $search[$key];
        return null;
    }



    /**
     * 清空搜索
     */
    protected function removeSearch() {
        return  $this->getUser()->getAttributeHolder()->remove('search_'.AuthMenu::getController());
    }

    /**
     * 日志操作
     * @params:
     */
    private  function logOpt(){
        $row = sfConfig::get('app_log_record_modules');
        if(in_array($this->moduleName, $row)) {
            $serviceClient = new kaluliServiceClient();
            $serviceClient->setMethod('log.add');
            //操作ID
            $optID = sfContext::getInstance()->getRequest()->getParameter('id');
            $serviceClient->setApiParam('opt_id', $optID);
            //操作内容
            $serviceClient->setApiParam('opt_json', json_encode(sfContext::getInstance()->getRequest()->getPostParameters()));
            //操作路径
            $optURI = $this->moduleName . '/' . $this->actionName;
            $serviceClient->setApiParam('opt_uri', $optURI);
            $serviceClient->setVersion('1.0');
            $serviceClient->setApiParam('uid', sfContext::getInstance()->getUser()->getTrdUserHuPuId());
            $serviceClient->execute();
        }
    }






}