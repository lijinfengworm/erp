<?php

/**
 * 识货 消息推送 
 */
class tradeSendMessage
{
    private $apikey = '3b770696ec5ea841c37ecd86c3c477e7';
    private  $url="http://yunpian.com/v1/sms/tpl_send.json";
    public function __construct()
    {
    }

    /**
     * 模板接口发短信
     * apikey 为云片分配的apikey
     * tpl_id 为模板id
     *            666777 	【识货】#code# 如非本人操作，请忽略本短信
     *            666771 	【识货】您的验证码是#code# 如非本人操作，请忽略本短信
     * tpl_value 为模板值
     * mobile 为接受短信的手机号
     */
    public function send($mobile, $text ,$tpl_id = 666777){
        if (empty($mobile) || empty($text)) return false;
        $encoded_tpl_value = urlencode("#code#=$text");
        $post_string="apikey=$this->apikey&tpl_id=$tpl_id&tpl_value=$encoded_tpl_value&mobile=$mobile";

        return $this->sock_post($this->url, $post_string);
    }

    /**
    * url 为服务的url地址
    * query 为请求串
    */    
    private function sock_post($url,$query){
        $data = "";
        $info=parse_url($url);
        $fp=fsockopen($info["host"],80,$errno,$errstr,30);
        if(!$fp){
            return $data;
        }
        $head="POST ".$info['path']." HTTP/1.0\r\n";
        $head.="Host: ".$info['host']."\r\n";
        $head.="Referer: http://".$info['host'].$info['path']."\r\n";
        $head.="Content-type: application/x-www-form-urlencoded\r\n";
        $head.="Content-Length: ".strlen(trim($query))."\r\n";
        $head.="\r\n";
        $head.=trim($query);
        $write=fputs($fp,$head);
        $header = "";

        while ($str = trim(fgets($fp,4096))) {
             $header.=$str;
        }

        while (!feof($fp)) {
            $data .= fgets($fp,4096);
        }

        return $data;
    }
}