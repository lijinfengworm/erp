<?php

    /**
     * 格式化字符串
     * 梁天  2015-02-27
     */
     function string_safe_filter($post){
        $post = trim($post);
        $post = strip_tags($post,""); //清除HTML等代码
        $post = str_replace("\t","",$post); //去掉制表符号
        $post = str_replace("\r\n","",$post); //去掉回车换行符号
        $post = str_replace("\r","",$post); //去掉回车
        $post = str_replace("\n","",$post); //去掉换行
        $post = str_replace("'","",$post); //去掉单引号
        return $post;
    }


    /**
     * 时间转换 某（月|日|分）前
     */
    function  time_tran($created_at) {
        if($created_at < 60)
            $created_at = $created_at.'分钟前';
        elseif($created_at >= 60 && $created_at < (60*24))
            $created_at = floor($created_at/60).'小时前';
        elseif($created_at >= (60*24) && $created_at < (60*24*30))
            $created_at = floor($created_at/60/24).'天前';
        else
            $created_at = floor($created_at/60/24/30).'月前';

        return $created_at;
    }


    /**
     * 判断是否是合格的手机客户端
     * @return boolean
     */
    function is_mobile()
    {
        if(!isset($_SERVER['HTTP_USER_AGENT']))
        {
            return false;
        }
        $user_agent = strtolower($_SERVER['HTTP_USER_AGENT']);

        if (preg_match('/playstation/i', $user_agent) OR preg_match('/ipad/i', $user_agent) OR preg_match('/ucweb/i', $user_agent))
        {
            return false;
        }
        if (preg_match('/iemobile/i', $user_agent) OR preg_match('/mobile\ssafari/i', $user_agent) OR preg_match('/iphone/i', $user_agent) OR preg_match('/android/i', $user_agent) OR preg_match('/symbian/i', $user_agent) OR preg_match('/series40/i', $user_agent))
        {
            return true;
        }
        return false;
    }
    //是否能应该跳转到m
    function redirect_mobile()
    {
        $redirectPc = sfContext::getInstance()->getRequest()->getCookie('redirectPc');
        if($redirectPc)
        {
            return false;
        }
        return true;
    }

    function cut_str($string, $sublen, $start = 0, $code = 'UTF-8')
    {
        if($code == 'UTF-8')
        {
            $pa ="/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/";
            preg_match_all($pa, $string, $t_string); if(count($t_string[0]) - $start > $sublen) return join('', array_slice($t_string[0], $start, $sublen))."...";
            return join('', array_slice($t_string[0], $start, $sublen));
        }
        else
        {
            $start = $start*2;
            $sublen = $sublen*2;
            $strlen = strlen($string);
            $tmpstr = ''; for($i=0; $i<$strlen; $i++)
        {
            if($i>=$start && $i<($start+$sublen))
            {
                if(ord(substr($string, $i, 1))>129)
                {
                    $tmpstr.= substr($string, $i, 2);
                }
                else
                {
                    $tmpstr.= substr($string, $i, 1);
                }
            }
            if(ord(substr($string, $i, 1))>129) $i++;
        }
            if(strlen($tmpstr)<$strlen ) $tmpstr.= "...";
            return $tmpstr;
        }
    }

    function substr_for_utf8($sourcestr,$cutlength,$flag = true)
    {
        $returnstr='';
        $i=0;
        $n=0;
        $str_length=strlen($sourcestr);    //字符串的字节数
        while (($n<$cutlength) and ($i<=$str_length))
        {
            $temp_str=substr($sourcestr,$i,1);
            $ascnum=Ord($temp_str); //得到字符串中第$i位字符的ascii码
            if ($ascnum>=224) //如果ASCII位高与224，
            {
                $returnstr=$returnstr.substr($sourcestr,$i,3); //根据UTF-8编码规范，将3个连续的字符计为单个字符
                $i=$i+3; //实际Byte计为3
                $n++; //字串长度计1
            }
            elseif ($ascnum>=192)//如果ASCII位高与192，
            {
                $returnstr=$returnstr.substr($sourcestr,$i,2); //根据UTF-8编码规范，将2个连续的字符计为单个字符
                $i=$i+2; //实际Byte计为2
                $n++; //字串长度计1
            }
            elseif ($ascnum>=65 && $ascnum<=90) //如果是大写字母，
            {
                $returnstr=$returnstr.substr($sourcestr,$i,1);
                $i=$i+1; //实际的Byte数仍计1个
                $n++; //但考虑整体美观，大写字母计成一个高位字符
            }
            else //其他情况下，包括小写字母和半角标点符号，
            {
                $returnstr=$returnstr.substr($sourcestr,$i,1);
                $i=$i+1;    //实际的Byte数计1个
                $n=$n+0.5;    //小写字母和半角标点等与半个高位字符宽…
            }
        }

        if ($flag)
        {
            if ($str_length>$cutlength)
            {
                $returnstr = $returnstr . "...";    //超过长度时在尾处加上省略号
            }
        }
        return $returnstr;
    }
    
    function getStatisticsUrl($url,$param){
        $path = parse_url($url);
        if (isset($path['fragment']) && $path['fragment']){
            if (!preg_match('/qk=/',$path['fragment'])){
                return $url; 
            } else {
               $url = str_replace('#'.$path['fragment'],'',$url);
            }
        }
        if (preg_match('/shihuo.cn/',$path['host']) || preg_match('/hupu.com/',$path['host'])){
            return $url.'#qk='.$param;
        }
       return $url; 
    }
    
 // 计算中文字符串长度
 function utf8_strlen($str = null) {
    $count = 0;
    for($i = 0; $i < strlen($str); $i++){
        $value = ord($str[$i]);
        if($value > 127) {
            $count++;
            if($value >= 192 && $value <= 223) $i++;
            elseif($value >= 224 && $value <= 239) $i = $i + 2;
            elseif($value >= 240 && $value <= 247) $i = $i + 3;
            else die('Not a UTF-8 compatible string');
        }
        $count++;
    }
    return $count;
    }
    
//计算是否可以代购
function getDaigouFlag($productId,$startDate = '',$endDate = ''){
    if (!$productId) {return false;}
    $time = time();
    if ($startDate && $endDate){
        if ($startDate > $time || $endDate < $time) {return false;}
    } elseif($startDate || $endDate){
        if ($startDate && $startDate > $time) {return false;}
        if ($endDate && $endDate < $time) {return false;}
    }
    return true;
}

function getTaobaoUserId($url){
    if(empty($url)) return false;
    $query = parse_url($url);
    if ($query['host'] == 'item.taobao.com' || ($query['host'] == 'detail.tmall.com' && strstr($query['path'], '/item.htm') != false)) {
        parse_str($query['query'], $queryStringArray);
        if (isset($queryStringArray['id']) && is_numeric($queryStringArray['id'])) {
            $taobao_id = $queryStringArray['id'];
            $memcache = sfContext::getInstance()->getDatabaseConnection('tradeMemcache');
            $memcacheKey = md5('get_nick_new_' . $taobao_id);
            $userId = $memcache->get($memcacheKey);
            if ($userId === FALSE) {
                $meta = get_meta_tags($url);
                $meta_microscope_data = explode('userid=',$meta['microscope-data']);
                if (isset($meta_microscope_data[1]) && !empty($meta_microscope_data[1])){
                    $userId = rtrim($meta_microscope_data[1],';');
                }
                if (empty($userId)) {
                    $memcache->set($memcacheKey, 0, 0, 60);
                } else {
                    $memcache->set($memcacheKey, $userId, 0, 86400 * 60);
                }
                return $userId;
            }
        }
    }
    return false;
}


function csvToArray($filePath=null)
{
   // $csv = new csv_to_array('xml/shaiwu_user.csv');
    $csv = new csv_to_array($filePath);
    $data = $csv->get_array();
    return $data;
}


function shaiwuLimitUser($uid = null)
{
    if(empty($uid)) return null;
    $key = 'shaiwuLimit';
    $redis = sfContext::getInstance()->getDatabaseConnection('kaluliRedis');

    if($redis->ttl($key) < 0)
    {
        $tmp = csvToArray('xml/shaiwu_user.csv');
        if(empty($tmp) && !is_array($tmp)) return false;

        foreach($tmp as $v)
        {
            if(!empty($v['uid']))
            {
                $redis->sAdd($key , $v['uid']);
            }
        }
        $redis->expireAt($key, time() + 86400*3);
    }

    return $redis->sIsMember($key, $uid);
}

/*
 * 海淘首页dace匹配
 * @author 韩晓林
 * @date 2015/5/6
 **/
function haitaoIndexDace($data){
    $pre = '';
    if(strpos($data,'youhui') != false){
        preg_match('/youhui\/(\d+)\.html/sU',$data,$match);
        $pre = 'youhui';
    }else if(strpos($data,'buy') != false){
        preg_match('/buy\/(\d+)-.*\.html/sU',$data,$match);
        $pre = 'daigou';
    }else if(strpos($data,'special') != false){
        preg_match('/index\?id=(\d+)$/sU',$data,$match);
        $pre = 'special';
    }

    if(isset($match[1]))
        return $pre.$match[1];
    else
        return false;
}

function getTaobaoTmallId($item_url){
    if(!$item_url) return false;
    $url = parse_url($item_url);
    if ($url['host'] == 'item.taobao.com' || ($url['host'] == 'detail.tmall.com' && strstr($url['path'], '/item.htm') != false)) {
        parse_str($url['query'], $queryStringArray);
        if (isset($queryStringArray['id']) && is_numeric($queryStringArray['id'])) {
            return $queryStringArray['id'];
        }
    }
    return '';
}

/*
 * banner 偏移
 *@author 韩晓林
 **/
function bannerOffset($banner, $bn){
    if($bn){
        if(strpos($bn, '.') !== false){
            $bns = explode('.', $bn);
        }elseif(is_numeric($bn)){
            $bns = array($bn);
        }

        if(!empty($bns)){
            $offset_bn = array();
            $offset_flag = true;
            foreach($bns as $bns_v){
                if(isset($banner[$bns_v-1])){
                    $offset_bn[$bns_v-1] = $banner[$bns_v-1];
                }else{
                    $offset_flag = false;
                    break;
                }
            }

            if($offset_flag && $offset_bn){
                $offset_diff = array_diff_key($banner, $offset_bn);
                $banner = array_merge($offset_bn, $offset_diff);
            }
        }
    }

    return $banner;
}