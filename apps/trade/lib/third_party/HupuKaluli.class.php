<?php

class HupuKaluli{
    
    static function send($aid,$uid,$key,$money){
        $time = time();       
        $sign = md5($time . $key);
        $apiConfig = sfConfig::get('app_api');
        $apiUrl = $apiConfig['kaluli']['url'].'?aid=' . $aid . '&uid=' . $uid . '&money=' . $money . '&time=' . $time . '&sign=' . $sign;
        return self::curlGet($apiUrl);
    }
    static function curlGet($url){
        $url=str_replace('&','&',$url); 
        $curl = curl_init(); 
        curl_setopt($curl, CURLOPT_URL, $url); 
        curl_setopt($curl, CURLOPT_HEADER, false); 
        curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 6.0; SeaPort/1.2; Windows NT 5.1; SV1; InfoPath.2)"); 
        curl_setopt($curl, CURLOPT_COOKIEJAR, 'cookie.txt'); 
        curl_setopt($curl, CURLOPT_COOKIEFILE, 'cookie.txt'); 
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); 
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 0); 
        $values = curl_exec($curl); 
        curl_close($curl); 
        return $values; 
    }    
}
?>
