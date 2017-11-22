<?php

/**
 * Description of tradeWeixinPayApi
 *
 * @author Xavier
 */
class kaluliWeixinPayApi
{
    //正式环境
    public $appId = 'shihuo_kaluli';
    public $secret = 'db041c70ab75861a0671d21a0d4c340d';
    public $host = 'http://api.pay.hupu.com';

    public function __construct()
    {
        if (sfConfig::get('sf_environment') == 'dev') {
            $this->host = 'http://test.pay.hupu.com';
            $this->secret = 'db041c70ab75861a0671d21a0d4c340d';
            $this->appId = 'shihuo_kaluli';
        }
        if (sfConfig::get('sf_environment') == 'stg') {
            $this->host = 'http://pre-api.pay.hupu.com';
            $this->secret = 'db041c70ab75861a0671d21a0d4c340d';
            $this->appId = 'shihuo_kaluli';
        }
    }

    public static function getInstance() {
        static $handier = NULL;
        if (empty($handier)) {
            $handier = new self();
        }
        return $handier;
    }

    public function getWeChatOpenId($uri)
    {
        try{
            $jsApi = new Kll_JsApi_pub();
            if (!isset($_GET['code'])) {
                //触发微信返回code码
                $url = $jsApi->createOauthUrlForCode($uri);
                header("Location: $url");
            } else {
                //获取code码，以获取openid
                $code = $_GET['code'];
                $jsApi->setCode($code);
                $openid = $jsApi->getOpenId();
                if (!$openid) {
                    $url = $jsApi->createOauthUrlForCode($uri);
                    header("Location: $url");
                }
            }
            return array('success'=> true, 'code' => $code, 'openid' => $openid);
        }catch (Exception $e) {
            return array('success' => false);
        }
    }

    public function verify_callback()
    {
        $sign = $_GET['sign'];
        unset($_GET['sign']);
        unset($_GET['tkey']);
        ksort($_GET);
        if ($sign != base64_encode(hash_hmac('sha256', http_build_query($_GET), $this->secret, true))) {
            return false;
        }
        return true;
    }

    public function post($url, $param, $method = 'POST', $header = array())
    {
        $post_body = '';
        if ($method == "GET") {
            $url = $url . '?' . http_build_query($param);
        } else {
            ksort($param);
            $post_body = http_build_query($param);
        }

        $date = gmdate('D, d M Y H:i:s') . ' GMT';
        $request_line = $method . ' ' . $url;
        $request_string = $request_line . '\n' . 'date: ' . $date . '\n' . $post_body;
        $sign = base64_encode(hash_hmac('sha256', $request_string, $this->secret, true));
        $auth = 'Signature appId="' . $this->appId . '",algorithm="hmac-sha256",headers="request-line date",signature="' . $sign . '"';

        $headr = array();
        $headr[] = 'Date: ' . $date;
        $headr[] = 'Authorization: ' . $auth;
        $headr[] = 'Content-Type: application/x-www-form-urlencoded';

        if ($header) {
            $headr = array_merge($headr, $header);
        }

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $this->host . $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        if ($method == "POST") {
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $post_body);
        }
        

        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headr);
        curl_setopt($curl, CURLOPT_VERBOSE, true);
        $result = curl_exec($curl);
        curl_close($curl);
        return json_decode($result, true);
    }
}
