<?php

/**
 * product actions.
 *
 * @package    HC
 * @subpackage product
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class appSuperActions extends sfActions
{
    protected $_v;
    protected $_platform;
    protected $_clientCode;
    protected $_channel;
    protected $_requestMethod;

    function __construct($context, $moduleName, $actionName) {
        parent::__construct($context, $moduleName, $actionName);
        sfConfig::set('sf_web_debug', false);
        $secret = '123456';
        $request = sfContext::getInstance()->getRequest();
        if (!in_array($moduleName . '/' . $actionName, array('app3/collectIssue'))) {
            $token = $request->getParameter('token');
            $getParame = array();

            $getParameters = $request->getGetParameters();
            $postParameters = $request->getPostParameters();

            unset($getParameters['token']);
            unset($postParameters['token']);
            foreach ($getParameters as $key => $val) {
                if (!is_array($val)) {
                    $getParame[$key] = $request->getParameter($key);
                }
            }
            foreach ($postParameters as $key => $val) {
                if (!is_array($val)) {
                    $getParame[$key] = $request->getParameter($key);
                }
            }
            ksort($getParame);
            $getStrParame = implode('', $getParame);
            $buldToken = md5($getStrParame . $secret);
            if ($buldToken != $token) {
                if ('cli' !== php_sapi_name()) {
                    exit('token error');
                }
            }
        }
    }

    /**
     * 获取属性信息
     */
    public function preExecute() {
        $request = sfContext::getInstance()->getRequest();
        $this->_requestMethod = strtolower($request->getMethod()); // 获取请求method
        $agent = strtolower($request->getUserAgent());

        // 获得客户端版本号
        $patternVersion = '/shihuo\/([0-9.]+)/i';
        if (preg_match($patternVersion, $agent, $matches)) {
            $v = $matches[1];
        } else {
            $v = $request->getParameter('v');
        }
        $this->_v = trim($v);

        // 获得客户端类型
        if (stristr($agent, 'iphone')) {
            $platform = 'ios';
        } else if (stristr($agent, 'android')) {
            $platform = 'android';
        } else {
            $platform = $request->getParameter('platform');
        }
        $this->_platform = trim($platform);

        $patternAttr = '/sc\((.+)\)/i';
        if (preg_match($patternAttr, $agent, $attrMatches)) {
            $attr = $attrMatches[1];
            $attrItem = explode(',', $attr);
            $this->_clientCode = trim($attrItem[0]); // 获得客户端设备唯一号
            $this->_channel = trim($attrItem[1]); // 获取客户端渠道
        }
        $this->getResponse()->setHttpHeader('Access-Control-Allow-Origin', '*');
    }

    /**
     * 验证无线设备请求的版本
     * @return bool
     */
    public function verifyRequestVersion($version = '2.2.0')
    {
        $flag = false;
        if ('ios' == $this->_platform) {
            if ($version <= $this->_v) {
                $flag = true;
            }
        } elseif ('android' == $this->_platform) {
            if ($version <= $this->_v) {
                $flag = true;
            }
        }
        return $flag;
    }

    /**
     * @param string $action 路由值
     * @param mix $params 参数
     * @param string $orgHref 老版本href
     * @param string $routeKey 路由键
     * @return string
     */
    public function getAppScheme($action, $params = array(), $orgHref = '', $routeKey = 'route')
    {
        $href = $orgHref;
        $hrefPrefix = 'shihuo://www.shihuo.cn';
        if ('ios' == $this->_platform) {
            if ('2.2.0' <= $this->_v) {
                $href = $hrefPrefix . '?' . $routeKey . '=' . $action;
                if ($params) {
                    if (is_array($params)) {
                        $href .= '&' . http_build_query($params);
                    } else {
                        $href .= '&' . $params;
                    }
                }
            }
        } elseif ('android' == $this->_platform) {
            if ('2.2.0' <= $this->_v) {
                $href = $hrefPrefix . '?' . $routeKey . '=' . $action;
                if ($params) {
                    if (is_array($params)) {
                        $href .= '&' . http_build_query($params);
                    } else {
                        $href .= '&' . $params;
                    }
                }
            }
        }
        return $href;
    }

    /**
     * 格式化返回数据
     * @param int $status 返回状态码
     * @param array $data 返回主体数据
     * @param string $msg 返回说明
     * @return string
     */
    public function formatResult($status = 0, $msg = 'ok', $data = array())
    {
        return json_encode(array('status' => $status, 'msg' => $msg, 'data' => $data));
    }

    public function qihuSafeFilter($post) {
        $post = trim($post);
        $post = strip_tags($post, ""); //清除HTML等代码
        $post = str_replace("\t", "", $post); //去掉制表符号
        $post = str_replace("\r\n", "", $post); //去掉回车换行符号
        $post = str_replace("\r", "", $post); //去掉回车
        $post = str_replace("\n", "", $post); //去掉换行
        $post = str_replace("'", "", $post); //去掉单引号
        $post = str_replace('/', "", $post); //去掉/
        $post = str_replace('\\', "", $post); //去掉\
        return $post;
    }
}
