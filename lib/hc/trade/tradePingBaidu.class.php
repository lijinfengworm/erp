<?php

/*
 * ping 百度服务
 */

class tradePingBaidu {

    private static $domain = 'http://www.shihuo.cn';
    private static $api_url = 'http://ping.baidu.com/ping/RPC2';
    private $p_url;
    private $p_title;
    /*
     * 进行一些初始化工作
     */

    public function __construct($p_url,$p_title)
    {
        $this->p_url = $p_url;
        $this->p_title = $p_title;
    }

    public function postUrl($url, $postvar){
        $ch = curl_init();
        $headers = array(
            "POST".$url."HTTP/1.0",
            "Content-type: text/xml; charset=\"gb2312\"",
            "Accept: text/xml",
            "Content-length: ".strlen($postvar)
        );
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postvar);
        $res = curl_exec ($ch);
        curl_close ($ch);
        return $res;
    }

/**
 *
 * @param string $url
 * @param string $title 
 * @return boolean
 */
public function pingBaidu(){
    if (empty($this->p_title) || empty($this->p_url)) return false;
    $baiduurl = '';
    $baiduXML = "<?xml version=\"1.0\" encoding=\"gb2312\"?>
                <methodCall>
                <methodName>weblogUpdates.extendedPing</methodName>
                <params>
                <param><value><string>".$this->p_title."</string></value></param>
                <param><value><string>".self::$domain."</string></value></param>
                <param><value><string>".$this->p_url."</string></value></param>
                <param><value><string>".self::$domain."</string></value></param>
                </params>
                </methodCall>";
    $res = $this->postUrl(self::$api_url, $baiduXML);
    if ( strpos($res, "<int>0</int>") )
    {
        return true;//ping 成功
    }
    else
    {
        return false;//ping 失败
    }
}
}
