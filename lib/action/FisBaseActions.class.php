<?php
    
/**
 * Class FisBaseController
 * 前端框架Fis通用action 实例化该action 既可以使用FIS
 * 梁天 2015-07-16
 */
class FisBaseActions extends sfActions
{

    //fis 的模板变量
    protected $_fis = null;

    //模板数组
    protected $_viewVars = array();

    //缓存变量
    protected $_cache = array();

    //用户属性
    protected $_user = array(
        'uid' => null,
        'username' => null
    );
    public $_hupu_app_flag = 0;

    //快捷属性 判断用户是否登陆
    protected $isLogin = false;

    //快捷属性 获取用户ID
    protected $uid = NULL;

    //达人标识
    protected $kol = NULL;

    //SEO 变量
    protected $_seo = array(
        'title' => '',
        'pageTitle' => '',
        'description' => '',
        'keywords' => ''
    );

    //是否设置了SEO
    private $_notSetSeo = false;

    protected $_newUserCoupon = array(
        'couponFee' => 0,  //优惠券金额
        'isShow' => 0,   //是否显示 1.显示优惠券 0.不显示优惠券
    );

    /**
     * 初始化方法
     * 梁天 2015-07-16s
     */
    public function initialize($context, $moduleName, $actionName)
    {
        parent::initialize($context, $moduleName, $actionName);
        /* 关闭debug */
        sfConfig::set('sf_web_debug', false);
        /* 加载模板 */
        $this->_fis = FisTpl::getInstance();
        /* 初始化站点 */
        $this->_cache = sfConfig::get('app_config');
        $this->assign('_Config', $this->_cache);
        /* 初始化用户 */

        $this->initUser();
        $user_agent = strtolower($_SERVER['HTTP_USER_AGENT']);
        if (preg_match('/kanqiu/i', $user_agent)) {
            $this->_hupu_app_flag = 1;
        }
        /*初始化新人红包判断*/
        $this->initNewUserCoupon($moduleName, $actionName);
    }


    /**
     * assigns a Smarty variable
     * 重写assign
     * @param  array|string $tpl_var the template variable name(s)
     * @param  mixed $value the value to assign
     * @param  boolean $nocache if true any output of this variable will be not cached
     *
     * @return Smarty_Internal_Data current Smarty_Internal_Data (or Smarty or Smarty_Internal_Template) instance for chaining
     */
    public function assign($tpl_var, $value = null, $nocache = false)
    {
        if (empty($tpl_var)) return false;
        $this->_fis->assign('_hupu_app_flag', $this->_hupu_app_flag);
        //商品活动
        $this->_fis->assign('_price_obj', FormatPrice::getInstance());
        if (is_array($tpl_var) && !$value) {
            foreach ($tpl_var as $tpl_var_key => $tpl_var_val) {
                $this->_viewVars[$tpl_var_key] = $tpl_var_val;
                $this->_fis->assign($tpl_var_key, $tpl_var_val, $nocache);
            }
        } else {
            $this->_viewVars[$tpl_var] = $value;
            $this->_fis->assign($tpl_var, $value, $nocache);
        }
    }

    public function append($tpl_var, $value = null, $merge = false, $nocache = false)
    {
        $this->_fis->append($tpl_var, $value = null, $merge = false, $nocache = false);
    }

    //打印出 assign  调试函数
    public function printVar()
    {
        header('Content-Type:text/html;charset=utf-8');
        echo json_encode($this->_viewVars);
        FunBase::myDebug($this->_viewVars);
        exit();
    }


    public function execute($request)
    {
        parent::execute($request);
       
        return sfView::NONE;
    }

    public function preExecute()
    {
        parent::preExecute();
        $jsSdk = kaluliJsSdk::getInstance();
        $signPackage = $jsSdk->getSignPackage();

        $this->assign("signPackage", $signPackage);
        $current_url = "https://".$_SERVER['SERVER_NAME'].$_SERVER["REQUEST_URI"];

        $current_url_json = urlencode($current_url);
        if($this->uid) {
            $kolInfo = KllKolTable::getInstance()->findOneByUserId($this->uid);
            if($kolInfo) {
                $this->assign("kolInfo",$kolInfo->toArray());
                $commision = $kolInfo->getCommision();
                $this->assign("commision",$commision/100);
                $current_url = "https://m.kaluli.com/kol/jumpTrack?kolId=".$kolInfo->getId()."&jumpUrl=".$current_url_json;
            }
        }
        $this->assign('default_share_pic', 'https://shihuo.hupucdn.com/ucditor/kulili/20161222/2d9cea39e1e5fe59e017ae6afd40393c1482386334.jpg');
        $this->assign("shareLink", $current_url);
        $form = new BaseForm();
        $this->assign("_csrf_token", $form->getCSRFToken('kaluli'));
        $this->assign("tokenId", $form->getCSRFToken('kaluli'));

        $flag = 0;
        if (isset($_COOKIE["code_num"]) && $_COOKIE["code_num"] > 5) {
            $flag = 1;
        }

        $this->assign('codeNumFlag', $flag);
    }

    /**
     * 用户微信登录
     * author by worm
     */
    protected function wechat($request)
    {
        $code = $request->getParameter("code");
        if (!empty($code)) {
            $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=" . KaluliWx::APPID . "&secret=" . KaluliWx::KEY . "&code=" . $code . "&grant_type=authorization_code";
            $access = KaluliFun::requestUrl($url, "POST");
            if (!empty($access)) {
                $open = json_decode($access, true);
                if (isset($open['openid']) && !empty($open['openid'])) {
                    $openID = $open['openid'];
                    $access_token = $open['access_token'];
                    $info_url = "https://api.weixin.qq.com/sns/userinfo?access_token=" . $access_token . "&openid=" . $openID . "&lang=zh_CN";
                    $user_info = KaluliFun::requestUrl($info_url, "GET");
                    $info = json_decode($user_info, true);
                    $expire = strtotime("1 years");
                    if (!empty($info['openid'])) {
                        //逻辑开始
                        if (empty($this->uid)) {
                            $user_union = KllUserUnionTable::getInstance()->findOneByUnionId($info['openid']);
                            if (!empty($user_union)) {
                                $user_id = $user_union->getUserId();
                                $userInfo = KllUserTable::getInstance()->findOneByUserId($user_id);

                                if (!empty($userInfo)) {
                                    $userId = $user_id;
                                    $userName = $userInfo->getUserName();
                                    $userName = kaluliFun::str_cut(strip_tags($userName), 5);
                                    $_COOKIE['um'] = $_COOKIE['u'] = $userId . '-' . $userName;
                                    setcookie('u', $userId . '-' . $userName, $expire, '/', 'kaluli.com', null, true);
                                    setcookie('um', $userId . '-' . $userName, $expire, '/', 'kaluli.com', null, true);
                                    /*  注入框架  */


                                    $this->uid = $this->_user['uid'] = $userId;
                                    $this->_user['username'] = $userName;
                                    $this->isLogin = true;
                                }
                            }
                        } else {
                            $user_union = KllUserUnionTable::getInstance()->findOneByUnionId($info['openid']);
                            if (empty($user_union)) {
                                $union = new KllUserUnion();
                                $union->setUserId($this->uid)->setType(2)->setUnionId($info['openid'])->setUnionUserName($info['nickname'])->setCtTime(time())->setUpTime(time())->save();
                                if (strrpos($this->_user['username'], "CAL_")) {
                                    $user = KllUserTable::getInstance()->findOnebyUserId($this->uid);
                                    if (!empty($info['nickname'])) {
                                        $tmp = KllUserTable::getInstance()->findOneByUserName($info['nickname']);
                                        if (!empty($tmp)) {
                                            $info['nickname'] = $info['nickname'] . '_' . time();
                                        }
                                        $user->setUserName($info['nickname'])->save();
                                    }

                                }

                            } else {
                                if (!empty($user_union)) {
                                    $user_id = $user_union->getUserId();
                                    $userInfo = KllUserTable::getInstance()->findOneByUserId($user_id);

                                    if (!empty($userInfo)) {
                                        $userId = $user_id;
                                        $userName = $userInfo->getUserName();
                                        $userName = kaluliFun::str_cut(strip_tags($userName), 5);
                                        $_COOKIE['um'] = $_COOKIE['u'] = $userId . '-' . $userName;
                                        setcookie('u', $userId . '-' . $userName, $expire, '/', 'kaluli.com', null, true);
                                        setcookie('um', $userId . '-' . $userName, $expire, '/', 'kaluli.com', null, true);
                                        /*  注入框架  */

                                        $this->uid = $this->_user['uid'] = $userId;
                                        $this->_user['username'] = $userName;
                                        $this->isLogin = true;
                                    }
                                }
                            }
                        }
                    }
                    // var_dump($info);exit;
                }
            }
        }else {
            $redirectUri = "https://" . $_SERVER['SERVER_NAME'] . $_SERVER["REQUEST_URI"];
            $redirectUri = urlencode($redirectUri);
            $url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=' . KaluliWx::APPID . '&redirect_uri=' . $redirectUri . '&response_type=code&scope=snsapi_base&state=oauth#wechat_redirect';
            $this->redirect($url); //获取code
        }
    }


    /**
     * 初始化用户
     */
    protected function initUser()
    {
        $user_agent = strtolower($_SERVER['HTTP_USER_AGENT']);
        if (preg_match('/kanqiu/i', $user_agent)) {
            if (isset($_COOKIE['um']) && isset($_COOKIE['u'])) {
                $_COOKIE['u'] = $_COOKIE['um'];
                $userInfo = explode("-", $_COOKIE['um']);

                $user = $this->getContext()->getUser();
                if (count($userInfo) < 2) { //假设原cookie有问题
                    $this->getContext()->getUser()->getAttributeHolder()->clear();
                    $this->isLogin = false;
                    $this->uid = null;
                } else {
                    $user->setAttribute('uid', $userInfo[0]);
                    $user->setAttribute('username', $userInfo[1]);
                    /*  注入框架  */
                    $this->uid = $this->_user['uid'] = $userInfo[0];
                    $this->_user['username'] = $userInfo[1];
                    $this->isLogin = true;
                    $expire = strtotime("1 years");
                    setcookie('ur', $this->uid, $expire, '/', 'kaluli.com', null, true);
                }
            } elseif (isset($_COOKIE['u']) && !isset($_COOKIE['um'])) {
                $hupuIDS = explode("%", $_COOKIE['u']);
                $hupu_id = $hupuIDS[0];
                $expire = strtotime("1 years");
                $loginData = userKaluliService::commonCall("UserToHuPu", ["username" => 'CAL_kalulitest', "hupu_id" => $hupu_id]);

                if (!empty($loginData) && $loginData['status'] == 200) {

                    $userId = $loginData['data']->getUserId();

                    $userName = $loginData['data']->getUserName();

                    userKaluliService::commonCall("UpdateTime", ["uid" => $userId]);
                    $userName = kaluliFun::str_cut(strip_tags($userName), 5);
                    $_COOKIE['um'] = $_COOKIE['u'] = $userId . '-' . $userName;
                    setcookie('u', $userId . '-' . $userName, $expire, '/', 'kaluli.com', null, true);
                    setcookie('um', $userId . '-' . $userName, $expire, '/', 'kaluli.com', null, true);
                    setcookie('ur', $userId, $expire, '/', 'kaluli.com', null, true);
                } elseif (!empty($loginData) && $loginData['status'] == 202) {
                    $userId = $loginData['data']->getUserId();
                    $new_name = function () use (&$new_name) {
                        $user_name = uniqid("CAL_");
                        $tmp = KllUserTable::getInstance()->findOneByUserName($user_name);
                        if (!empty($tmp)) {
                            return $this->new_name();
                        }
                        return $user_name;
                    };
                    $name = $new_name();
                    userKaluliService::commonCall("UpdateTime", ["uid" => $userId]);
                    userKaluliService::commonCall("UpdateUserName", ["uid" => $userId, 'user_name' => $name]);
                    //setcookie('uBindMobile', $userId, $expire, '/', 'kaluli.com', null, true);
                    $_COOKIE['um'] = $_COOKIE['u'] = $userId . '-' . $name;
                    setcookie('u', $userId . '-' . $name, $expire, '/', 'kaluli.com', null, true);
                    setcookie('um', $userId . '-' . $name, $expire, '/', 'kaluli.com', null, true);
                    setcookie('ur', $userId, $expire, '/', 'kaluli.com', null, true);
                }
                $userInfo = explode("-", $_COOKIE['u']);

                $user = $this->getContext()->getUser();

                $user->setAttribute('uid', $userInfo[0]);
                $user->setAttribute('username', $userInfo[1]);
                /*  注入框架  */
                $this->uid = $this->_user['uid'] = $userInfo[0];
                $this->_user['username'] = $userInfo[1];
                $this->isLogin = true;
            } else {
                $this->getContext()->getUser()->getAttributeHolder()->clear();
                $this->isLogin = false;
                $this->uid = null;
            }

        } else {
            if (isset($_COOKIE['u'])) {
                $userInfo = explode("-", $_COOKIE['u']);

                $user = $this->getContext()->getUser();
                if (count($userInfo) < 2) { //假设原cookie有问题
                    $this->getContext()->getUser()->getAttributeHolder()->clear();
                    $this->isLogin = false;
                    $this->uid = null;
                } else {
                    $user->setAttribute('uid', $userInfo[0]);
                    $user->setAttribute('username', $userInfo[1]);
                    /*  注入框架  */
                    $this->uid = $this->_user['uid'] = $userInfo[0];
                    $this->_user['username'] = $userInfo[1];
                    $this->isLogin = true;
                    $expire = strtotime("1 years");
                    setcookie('ur', $this->uid, $expire, '/', 'kaluli.com', null, true);
                }
            } else {
                $this->getContext()->getUser()->getAttributeHolder()->clear();
                $this->isLogin = false;
                $this->uid = null;
            }
        }

        $pathArray = $this->getContext()->getRequest()->getPathInfoArray();
        $exprie = time() + 86400;
        setcookie('remoteIp', $pathArray['REMOTE_ADDR'], $exprie, '/', 'kaluli.com', null, true);
        $this->assign('_User', $this->_user);
    }

    //重写display
    public function display($tpl_name = '', $vars = array())
    {
        if ((!empty($this->_viewVars) && is_array($this->_viewVars)) || (!empty($vars) && is_array($this->_viewVars))) {
            $viewVars = array_merge($this->_viewVars, $vars);
            foreach ($viewVars as $var => $val) {
                $this->_fis->assign($var, $val);
            }
        }
        //检测全局seo变量
        $this->_checkSeo();
        //如果没有设置模板名 自动设置模板名
        if ($tpl_name !== null) {
            if ($tpl_name === '') $tpl_name = sfContext::getInstance()->getModuleName() . '/page/' . sfContext::getInstance()->getActionName();
            // $a = $this->_fis->fetch($tpl_name . '.tpl');
            sfContext::getInstance()->getResponse()->setContent($this->_fis->fetch($tpl_name . '.tpl', NULL, NULL, NULL, FALSE));
        }
    }


    /**
     * 把设置的属性自动添加到视图层
     *
     * @param string $name 变量名
     * @param string $value 变量值
     */
    public function __set($name, $value)
    {
        $this->_viewVars[$name] = $value;
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
    protected function ajaxReturn($data, $type = '')
    {
        if (empty($type)) $type = sfConfig::get('app_codeConfig_def_ajax_type');
        FunBase::ajaxReturn($data, $type);
    }


    /**
     * 快捷ajaxReturn 方法 返回错误
     */
    protected function ajaxError($info = '', $url = '')
    {
        FunBase::ajaxReturn(array('status' => 0, 'info' => $info, 'url' => $url));
    }

    /**
     * 快捷ajaxReturn 方法 返回成功
     */
    protected function ajaxSuccess($info = '', $data = '', $url = '')
    {
        FunBase::ajaxReturn(array('status' => 1, 'data' => $data, 'info' => $info, 'url' => $url));
    }


    /**
     * 批量设置SEO
     * 如果传入一个数组  array('title'=>xxx,'keywords'=>'','description'=>'') 三个同时设置
     * 如果只有一个字符串参数 那么只设置 title  $this->setSeo('title')
     * 如果只有二个字符串参数 那么只设置 title 和 keywords  $this->setSeo('title','keywords')
     * 如果有三个字符串参数 那么全部设置   $this->setSeo('title','keywords','description')
     * 梁天 2015-07-18
     */
    protected function setSeo()
    {
        if (func_num_args() == 1 && is_array(func_get_arg(0))) {
            $seoArr = func_get_arg(0);
            foreach ($this->_seo as $key => $val) {
                if (isset($seoArr[$key])) {
                    $this->_assignSeo($key, $seoArr[$key]);
                }
            }
        } else if (func_num_args() == 1 && is_string(func_get_arg(0))) {
            $this->_assignSeo('title', func_get_arg(0));
        } else if (func_num_args() == 2) {
            $this->_assignSeo('title', func_get_arg(0));
            $this->_assignSeo('keywords', func_get_arg(1));
        } else if (func_num_args() == 3) {
            $this->_assignSeo('title', func_get_arg(0));
            $this->_assignSeo('keywords', func_get_arg(1));
            $this->_assignSeo('description', func_get_arg(2));
        } else if (func_num_args() == 4) {
            $this->_assignSeo('title', func_get_arg(0));
            $this->_assignSeo('pageTitle', func_get_arg(1));
            $this->_assignSeo('keywords', func_get_arg(2));
            $this->_assignSeo('description', func_get_arg(3));
        }
    }


    protected function setSeoTitle($title = '')
    {
        $this->_assignSeo('title', $title);
    }

    protected function setPageTitle($pageTitle = '')
    {
        $this->_assignSeo('pageTitle', $pageTitle);
    }

    protected function setSeoDescription($desc = '')
    {
        $this->_assignSeo('description', $desc);
    }

    protected function setSeoKeywords($keyword = '')
    {
        $this->_assignSeo('keywords', $keyword);
    }

    private function _assignSeo($key = '', $val = '')
    {
        if ($key !== '') {
            $this->_seo[$key] = $val;
        }
    }

    /**
     * 不继承默认的SEO 属性
     * @param $bool
     */
    protected function notSetDefaultSeo($bool)
    {
        $this->_notSetSeo = $bool;
    }


    //检测SEO是否设置
    private function _checkSeo()
    {
        if (!$this->_notSetSeo) {
            foreach ($this->_seo as $key => $val) {
                if (empty($val) && !empty($this->_cache[$key])) $this->_seo[$key] = $this->_cache[$key];
            }
        }
        $this->assign('_Seo', $this->_seo);
    }

    //初始化优惠券配置
    protected function initNewUserCoupon($moduleName,$actionName)
    {
        //增加页面显示逻辑配置
        if(in_array($moduleName,['index','item','activityTemplate','activity','encyclopedia','ucenter'])) {
            if(!in_array($actionName,['newUser','newUserS','newUserLose'])) {
                $return = FunBase::checkUserNewCoupon($this->uid);
                if ($return['status'] == 2) {
                    $activity = KllSendCouponOrderTable::getInstance()->createQuery()->where("position = ?", 2)->andWhere("state = ?", 1)
                        ->andWhere("s_time <?", time())->andWhere("e_time > ?", time())->andWhere("channel_id = 4")->fetchOne();
                    if ($activity) {
                        $recordArr = explode("|", $activity->record_id);
                        $totalCouponPrice = 0;
                        foreach ($recordArr as $recordId) {
                            $couponRecord = KaluliLipinkaRecordTable::getInstance()->findOneById($recordId);
                            $price = ($couponRecord->amount) / ($couponRecord->num);
                            $totalCouponPrice += $price;
                        }
                        $this->_newUserCoupon['couponFee'] = $totalCouponPrice;
                        $this->_newUserCoupon['isShow'] = 1;
                    }
                }
            }
        }
        $this->assign("_newUserCoupon",$this->_newUserCoupon);


    }


}