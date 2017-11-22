<?php

/**
 * Created by PhpStorm.
 * User: libin
 * Date: 2016/3/1
 * Time: 9:15
 */
class kaluliJsSdk
{
    private $appId;
    private $appSecret;
    private $redis;

    //用于存放实例化的对象
    static private $_instance;


    private function __construct()
    {

        $this->appId = KaluliWx::APPID;
        $this->appSecret = KaluliWx::KEY;

        //初始化redis服务
        $this->redis = sfContext::getInstance()->getDatabaseConnection('kaluliRedis');
        $this->redis->select(10);
    }


    //公共静态方法获取实例化的对象
    static public function getInstance()
    {
        if (!(self::$_instance instanceof self)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function getSignPackage()
    {
        $jsapiTicket = $this->getJsApiTicket();

        // 注意 URL 一定要动态获取，不能 hardcode.
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $url = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

        $timestamp = time();
        $nonceStr = $this->createNonceStr();

        // 这里参数的顺序要按照 key 值 ASCII 码升序排序
        $string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";

        $signature = sha1($string);

        $signPackage = array(
            "appId" => $this->appId,
            "nonceStr" => $nonceStr,
            "timestamp" => $timestamp,
            "url" => $url,
            "signature" => $signature,
            "rawString" => $string
        );
        return $signPackage;
    }

    private function createNonceStr($length = 16)
    {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $str = "";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }

    private function getJsApiTicket()
    {
        // jsapi_ticket 应该全局存储与更新，暂时先存入redis
        $ticket = $this->redis->get("kaluli.weixin.jsTicket");
        if (!$ticket) {
            $accessToken = $this->getAccessToken();
            // 如果是企业号用以下 URL 获取 ticket
            // $url = "https://qyapi.weixin.qq.com/cgi-bin/get_jsapi_ticket?access_token=$accessToken";
            $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token=$accessToken";
            $res = json_decode($this->httpGet($url));
            $ticket = $res->ticket;
            if ($ticket) {
                //将ticket写入redis，暂存10分钟
                $this->redis->set("kaluli.weixin.jsTicket", $ticket, 600);
            }
        }

        return $ticket;
    }

    public function getAccessToken()
    {
        // access_token 应该全局存储与更新，暂时修改为从redis存取
        $access_token = $this->redis->get("kaluli.weixin.accessToken");
        if (!$access_token) {
            // 如果是企业号用以下URL获取access_token
            // $url = "https://qyapi.weixin.qq.com/cgi-bin/gettoken?corpid=$this->appId&corpsecret=$this->appSecret";
            $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$this->appId&secret=$this->appSecret";
            $res = json_decode($this->httpGet($url));
            $access_token = $res->access_token;
            if ($access_token) {
                //将accessToken写入redis,暂时定缓存10分钟
                $this->redis->set("kaluli.weixin.accessToken", $access_token, 60);
            }
        }
        return $access_token;
    }

    private function httpGet($url)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 500);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_URL, $url);

        $res = curl_exec($curl);
        curl_close($curl);

        return $res;
    }

    /**
     * httpPost方法 by 李斌
     * @param $url
     * @param $data 普通post为array数据，按jsonPost为json数据
     * @param $type "normal"为普通array提交，"json"为按json格式提交,默认为normal
     */
    private function httpPost($url, $data, $type = "normal")
    {
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");//设置为post提交方式
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);//设置提交数据
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);//设置获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($curl, CURLOPT_TIMEOUT, 500);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        if ($type == "json") {
            curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen($data))
            );
        } else if ($type == "normal") {
            curl_setopt($curl, CURLOPT_HEADER, 1);
        }

        $res = curl_exec($curl);

        curl_close($curl);//关闭curl流

        return $res;
    }
}