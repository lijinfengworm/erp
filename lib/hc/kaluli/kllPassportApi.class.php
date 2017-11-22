<?php

/**
 * 识货 调用passport类
 */
class kllPassportApi
{
    private $appid = 10039;
    private $appkey =  'fea3c2799bc27cae097f32d019d6c5f1';
    private $method = 'POST';
    private $timeline = null;
    private $ip = null;
    private $from = 'kaluli';
    private $project = 'project';
    private $error_code =  array(
        '-7000'=>'appid不能为空',
        '-7004'=>'Appid不存在',
        '-7005'=>'appid被停用',
        '-7006'=>'sign签名不一致',
    );
    private $prefix_url = "https://passport.hupu.com";

    /**
     * 初始化一些公共的参数
     * @param $appid
     * @param $appkey
     * @param string $ip
     * @param string $from
     * @param string $project
     */
    public function __construct($appid = '', $appkey = '', $method = '', $ip= '', $from = '', $project = ''){
        $this->appid = $appid ? $appid : $this->appid;
        $this->appkey = $appkey ? $appkey : $this->appkey;
        $this->method = $method ? $method : $this->method;
        $this->ip = $ip ? $ip : tradeCommon::getip();
        $this->timeline = time();
        $this->from = $from ? $from : $this->from;
        $this->project = $project ? $project : $this->project;
    }

    /**
     * 发送请求
     * @param $url 要请求的passport接口url
     * @param array $param 要提交的参数
     * @$curlopt_header inter 1表示返回header 0 不返回
     */
    public function getContent($url, $param = array(), $curlopt_header = 0){
        if (empty($url) || empty($param) || !is_array($param)) return false;
        $arrays = array(
            'appid' => $this->appid,
            'method' => $this->method,
            'timeline' => $this->timeline,
            'ip' => $this->ip,
            'from' => $this->from,
            'project' => $this->project
        );

        $params = array_merge($arrays, $param);
        $url = $this->prefix_url.$url;

        //生成签名
        $params['sign'] = $this->getSign($params, $this->appkey);

        $status = $this->requestUrl($url,"GET", $params, $curlopt_header);
        return $status;
    }
    /**
     * 虎扑第三方登陆的账号和密码登陆
     */
    public function getContentToHuPu($url, $param = array(), $curlopt_header = 0){
        if (empty($url) || empty($param) || !is_array($param)) return false;
        $arrays = array(
            'appid' => $this->appid,
            'method' => $this->method,
            'timeline' => time(),
            'ip' => $this->ip,
            'from' => $this->from,
            'project' => $this->project,
            'mkt' => time()
        );

        $params = array_merge($arrays, $param);
        $url = $this->prefix_url.$url;


        //生成签名
        //$params['sign'] = $this->getSign($params, $this->appkey);
        $params['sign'] = md5(urlencode($params['username'])."U3JLDS08XS".$this->timeline);

        $status = $this->requestUrl($url,"GET", $params, $curlopt_header);
        return $status;
    }

    /**
     * 生成签名
     * @param array $params
     * @param string $key
     * @return string
     */
    public function getSign(array $params, $key)
    {
        $sign = NULL;
        ksort($params);
        $str = $this->arrayToString($params);
        if(function_exists('hash_hmac'))
        {
            $sign = hash_hmac("sha1", $str, $key);
        }
        else
        {
            $blocksize = 64;
            $hashfunc = 'sha1';
            strlen($key) > $blocksize && $key = pack('H*', $hashfunc($key));
            $key = str_pad($key, $blocksize, chr(0x00));
            $ipad = str_repeat(chr(0x36), $blocksize);
            $opad = str_repeat(chr(0x5c), $blocksize);
            $hmac = pack('H*', $hashfunc(($key ^ $opad) . pack('H*', $hashfunc(($key ^ $ipad) . $str))));
            $sign = base64_encode($hmac);
        }
        return $sign;
    }

    /**
     * 将一维数组转为字符串 (array('a' => 1, 'b' => 2, 'c => '+1') To: a=1, b=2, c=c+1)
     * @param array 需要转换的数组对象
     * @return string
     */
    static function arrayToString(array $datas = array())
    {
        $str = NULL;
        if(!empty($datas))
        {
            $i = 1;
            $dataCount = count($datas);
            foreach($datas as $key => $data)
            {
                $str .= $key . ($data && in_array($data, array('?+1', '?-1', '?+2', '?-2')) ? '=' . $key . strtr($data, array('?' => NULL)) : '=\'' . $data . '\'') . ($i < $dataCount ? ', ' : NULL);
                $i++;
            }
        }
        return $str;
    }

    private function requestUrl($url, $method = 'GET', $datas = array(), $curlopt_header = 0,$timeout = 10, $contentType = NULL, $cookies = NULL)
    {
        $method = strtoupper($method);

        $query = is_array($datas) ? http_build_query($datas, NULL, '&') : $datas;

        if($method == 'GET' && !empty($query))
        {
            $url .= '?' . $query;
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);

        if($method == 'POST' && !empty($query))
        {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $query);
        }

        !empty($cookies) && curl_setopt($ch, CURLOPT_COOKIE, $cookies);

        if($contentType)
        {
            curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: " . $contentType));
        }

        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, $curlopt_header);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查
        //curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, true); // 从证书中检查SSL加密算法是否存在

        $results = curl_exec($ch);

        if(curl_errno($ch))
        {
            echo 'Curl error: ' . curl_error($ch);
            exit;
        }
        $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        //echo 'curl_http_code:'.$http_status.' ';
        return $results;
    }


}