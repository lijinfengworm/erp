<?php

class common {
    /*
     * UTF-8 字符串截取
     */

    static function utf_substr($str, $len, $addStr = '...') {
        if (strlen($str) < $len) {
            return $str;
        }
        for ($i = 0; $i < $len; $i++) {
            $temp_str = substr($str, 0, 1);
            if (ord($temp_str) > 127) {
                $i++;
                if ($i < $len) {
                    $new_str[] = substr($str, 0, 3);
                    $str = substr($str, 3);
                }
            } else {
                $new_str[] = substr($str, 0, 1);
                $str = substr($str, 1);
            }
        }
        return join($new_str) . $addStr;
    }

    /*
     * google统计
     */

    public static function googleAnalyticsGetImageUrl() {
        $GA_ACCOUNT = "MO-887303-21";       //写在api里
        $GA_PIXEL = "/ga.php";
        $referer = isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : '';
        $query = $_SERVER["QUERY_STRING"];
        $path = $_SERVER["REQUEST_URI"];
        $url = "";
        $url .= $GA_PIXEL . "?";
        $url .= "utmac=" . $GA_ACCOUNT;
        $url .= "&utmn=" . rand(0, 0x7fffffff);
        if (empty($referer)) {
            $referer = "-";
        }
        $url .= "&utmr=" . urlencode($referer);
        if (!empty($path)) {
            $url .= "&utmp=" . urlencode($path);
        }
        $url .= "&guid=ON";
        return str_replace("&", "&amp;", $url);
    }

    /*
     * curl 抓取远程页面
     */

    public static function Curl($url, $arr = null,$timeout = 15,$jump_loop = 7) {
        $curl = curl_init();
        $curlPost = $arr;
        curl_setopt($curl, CURLOPT_URL, trim($url));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_TIMEOUT, $timeout); //超时设置
        curl_setopt($curl, CURLOPT_USERAGENT,"Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; WOW64; Trident/5.0)" );
        //主要是设置语言这样能得到一个正确的地址
        $header[0] = "Accept: text/xml,application/xml,application/xhtml+xml,";
        $header[0] .= "text/html;q=0.9,text/plain;q=0.8,image/png,*/*;q=0.5";
        $header[] = "Cache-Control: max-age=0";
        $header[] = "Connection: keep-alive";
        $header[] = "Keep-Alive: 300";
        $header[] = "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7";
        $header[] = "Accept-Language: zh-CN";
        $header[] = "Pragma: ";
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查   
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 1); // 从证书中检查SSL加密算法是否存在
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION,1); // 使用自动跳转
        //302跳转最多次数
        curl_setopt($curl, CURLOPT_MAXREDIRS, $jump_loop);
        curl_setopt($curl, CURLOPT_AUTOREFERER, 1); // 自动设置Referer   
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        if($curlPost !== null)
        {
            
            curl_setopt($curl, CURLOPT_POST, 1); //post
            curl_setopt($curl, CURLOPT_POSTFIELDS, $curlPost);
        }
        $info = curl_exec($curl);
        curl_close($curl);
        return $info;
    }
    /*
     * curl 抓取远程页面
     */

    public static function CurlWithTimeout($url, $timeout = 1) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_USERAGENT,"Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; Trident/5.0)" );
        $a = curl_exec($ch);
        curl_close ($ch);
        return $a;
    }
    /*
     * 根据图片URL返回 图片在服务器上的路径、缩略图名、缩略图路径、缩略图URL
     */

    public static function getPathAndThumbnameBySrc($src) {
        $a = parse_url($src);
        //$a['host'] = str_replace('news.hoopchina.com', 'i1.hoopchina.com.cn', $a['host']);
        $fileinfo = pathinfo($a['path']);
        return array('old_path' => sfConfig::get('app_thumb_position') . $fileinfo['dirname'] . '/' . $fileinfo['filename'] . '.' . $fileinfo['extension'], 'thumb_name' => $fileinfo['filename'] . '_' . sfConfig::get('app_thumb_width') . 'x' . '.' . $fileinfo['extension'], 'thumb_path' => sfConfig::get('app_thumb_position') . $fileinfo['dirname'] . '/', 'src' => 'http://' . $a['host'] . $fileinfo['dirname'] . '/' . $fileinfo['filename'] . '_80x.' . $fileinfo['extension']);
    }

    /**
     * 获取球队名字
     *
     * @param int $teamId
     * @param string $league
     * return string
     */
    public static function getTeamName($teamId, $league = 'NBA') {
        $app_name = 'app_teams_' . $league;
        $configKey = sfConfig::get($app_name);
        return $configKey[$teamId];
    }

    /**
     * 创建一个新数组，数组内容来自另外一个数组指定的key
     *
     * @param array $array
     * @param array $keys
     */
    public static function createArrayByKey($array, $keys) {
        $newArray = array();
        foreach ($keys as $key) {
            if (isset($array[$key])) {
                $newArray[$key] = $array[$key];
            } else {
                $newArray[$key] = array(
                    'team_name' => self::getTeamName($key),
                    'default' => 'none',
                );
            }
        }
        return $newArray;
    }

    /**
     * 删除数组中指定的key
     *
     * @param array $array
     * @param array $keys
     * @return array
     */
    public static function deleteArrayByKey($array, $keys) {
        foreach ($keys as $key) {
            if (isset($array[$key]))
                unset($array[$key]);
        }
        return $array;
    }

    /**
     * 获取短评论的缓存目录
     *
     * @param string $dir
     * @return string
     */
    public static function getMinCommendCacheDir($dir) {
        $appName = sfContext::getInstance()->getConfiguration()->getApplication();
        $runEnv = sfContext::getInstance()->getConfiguration()->getEnvironment();
        $cacheDir = sfConfig::get('sf_cache_dir') . '/' . $appName . '/' . $runEnv . '/' . $dir . '/';
        return $cacheDir;
    }

    /**
     * 短评评论获取cache Handle
     *
     * @param mixed $cacheParam
     * @return object
     */
    public static function getCacheHandle(&$cacheParam) {
        $cacheHandle = new sfFileCache($cacheParam);
        return $cacheHandle;
    }

    /*
     * 输出页面第一、二行菜单
     */

     public static function outputHeaderLines() {
       /* if (sfContext::getInstance()->getUser()->isAuthenticated()) {
            $str = '<li class="rel"><a href="http://my.hupu.com" id="g_h">我的首页</a></li><li><a href="http://my.hupu.com/' . sfContext::getInstance()->getUser()->getAttribute('uid') . '" iusername="' . sfContext::getInstance()->getUser()->getAttribute('username') . '" iuid="' . sfContext::getInstance()->getUser()->getAttribute('uid') . '" id="g_m">我的空间</a></li><li class="line">|</li><li><a href="http://my.hupu.com/' . sfContext::getInstance()->getUser()->getAttribute('uid') . '">' . sfContext::getInstance()->getUser()->getAttribute('username') . '</a></li><li><a href="http://my.hupu.com/mymsg.php" id="g_i">短消息</a></li><li class="set"><a href="http://my.hupu.com/set.php" class="set_y">设置<span class="arr_d">&nbsp;</span></a><ul class="hide"><li><a href="http://my.hupu.com/set.php">设置</a></li><li><a href="http://my.hupu.com/bank.php">银行</a></li><li><a href="http://my.hupu.com/help.php">帮助</a></li></ul></li><li class="line">|</li><li><a href="' . url_for('pay/logout') . '">退出</a></li>';
        } else {
            $str = '<li class="f444">虎扑千万铁杆篮球迷欢迎你，请<a class="blue top_reg" href="http://passport.hupu.com/register?from=wwwTop">注册</a>或者<a class="blue" href="http://passport.hupu.com/login?from=wwwTop">登录</a></li>';
        }*/
        echo '<div class="wrapper">
                <ul class="topnav_l"></ul>
               <ul id="topNavLink">
            <li><a href="http://www.hupu.com">虎扑体育</a></li>
            <li><a href="http://nba.hupu.com">篮球</a></li>
            <li><a href="http://soccer.hupu.com">足球</a></li>
            <li><a href="http://f1.hupu.com">赛车</a></li>
            <li><a href="http://tennis.hupu.com">网球</a></li>
		    <li><a href="http://youxi.hupu.com">游戏</a></li>
            <li><a href="http://bbs.hupu.com/bxj">步行街</a></li>
            <li class="cDropDownMenu"> 
            	<a class="cSetH" href="javascript:;">更多<s></s></a>
                <div class="cDrapDown"> 
                     <a href="http://nfl.hupu.com">NFL</a>
                     <a href="http://mma.hupu.com">MMA</a>
                     <a href="http://olympics.hupu.com/">奥运</a>
                     <a href="http://caipiao.hupu.com">彩票</a>
                     <a href="http://www.hupu.com/mobile.html">手机虎扑</a>
                     <a href="http://www.hupu.com/jobs/">招聘</a>
                </div>
            </li>
            <li><a target="_blank" href="http://url.cn/1VCZeE">GEQ</a></li>
    </ul>
            </div>
        </div>
        <div id="header" style="margin-top:10px;">
            <div id="nav">
             
                <div class="clearfix"></div>
            </div>';
    }

    function isValidURL($url) {
        return preg_match('|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $url);
    }
    
    
    /*
     * 根据$num返回分组的组名
     * return array()
     * 如果$num <=26 返回 array(0 => 'A', 1 => 'B');
     * 如果$num >26 返回 arrat(0 => 'A1', 1 => 'A2'， 2 => 'B1', 3 => 'B2')
     */
    public static function getGroupNames($num){
        if($num<=26) return range('A', 'Z');
        $tmp = ceil($num/26);   //需要A-Z的次数
        $end = floor($num/$tmp); //最后一个字母的index
        $temp = range('A', 'Z');
        $pre = range('A', $temp[$end]); //组名的前缀
        $return = array();
        foreach($pre as $k => $v){
            for($i=1; $i<=$tmp; $i++){
                $return[] = $v.$i;
            }
        }
        return $return;
        
    }
    
    /*
     * 格式化时间戳成 2天5小时30分
     */
    public static function formateTimestamp($time){
        $d = floor($time/86400);
        $time = $time%86400;
        $h = floor($time/3600);
        $time = $time%3600;
        $m = floor($time/60);
        $str = '';
        $str = $d < 1 ? '' : $d.'天';
        $str .= $h.'小时'.$m.'分';
        return $str;
    }
    /*
     * 根据赛事状态返回路由名
     */
    public static function getMatchRoundNameByStatus($status){
        switch($status) {
            case 1:
                return '@gamepage?showdetail=1';
            case 2:
                return '@gamepage?showgroups=1';
            case 3:
                return '@gamepage?showresult=1';
            case 4:
                return '@gamepage?showteams=1';
                
        }
    }
    public static function getMatchRoundNameByStatus2($status){
  
        switch($status) {
            case 1:
                return '@gamepage?showdetail=1';
            case 2:
                return '@gamepage?showgroups=1';
            case 3:
                return '@gamepage?showresult=1';
            case 4:
                return '@gamepage?showteams=1';
                
        }
    }
    
    /*
     * 返回时间戳的周一、周二。。。
     */
    public static function getWeekDay($time){
        switch (date('w', $time)) {
            case 1:
                return '周一';
                break;
            case 2:
                return '周二';
                break;
            case 3:
                return '周三';
                break;
            case 4:
                return '周四';
                break;
            case 5:
                return '周五';
                break;
            case 6:
                return '周六';
                break;
            default:
                return '周日';
                break;
        }
    }

    /*
     * 通过用户名获取uid
     */
    public static function getUidByUsername($username){
        return file_get_contents('http://passport.hupu.com/ucenter/getUidByUsername.api?username='.trim($username));
    }
    
    /*
     * 自动注册用户
     */
    public static function autoRegisterUser($username, $password, $email, $pid = 101, $key = 'U9S0WC5SRD' ){
        $time = time();
        $sign = md5(md5($pid) . $key . $time . $username);
        $url = 'http://passport.hoopchina.com/interface_register?';
        $url .= 'pid=' . $pid . '&time=' . $time . '&sign=' . $sign . '&username=' . urlencode($username) . '&password=' . $password . '&email=' . $email;
        return file_get_contents($url);
    }
    
    /*
     * 通过自动注册错误代码返回详细信息
     */
    public static function autoRegisterErrorInfo($no){
        switch ($no){
            case -1:
                return '无效的用户名';
            case -2:
                return '用户名包含不良信息';
            case -3:
                return '用户名已被注册';
            case -4:
                return '邮箱地址不正确';
            case -5:
                return '邮箱不合法';
            case -6:
                return '密码长度不合法（6-20）';
            case -7:
                return '用户名长度不合法（4-15）';
            case -8:
                return '接口使用项目被停用';
            case -9:
                return '签名不正确';
            case -10:
                return '接口使用项目编号不存在';
            case -11:
                return '参数不完整';
            default:
                return '未知错误';
        }
    }
    
    /*
     * 返回xx分钟前
     * $time int
     */
    public static function getTime($date){
        $time = time() - $date; 
        if($time <= 30){
            return '刚刚';
        }elseif( $time < 3600){
            return ceil($time/60).'分钟前';
        }elseif($time<86400){
            return ceil($time/3600).'小时前';        
        }else{
            return date('n月j日', $date);
        }
    }
    
    public static function getMobileAdvertise(){
       $mcw = 250; //最大图片宽度 
       $aid = 77961;
       $request = sfContext::getInstance()->getRequest();  
       $ip = $request->getRemoteAddress();
       $url = urlencode('http://'.$_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI']);
       $ua = urlencode($request->getHttpHeader('User-Agent'));
       $request_url = 'http://mob.acs86.com/m.htm?g=3&a='.$aid.'&ip=' .  $ip . '&curl=' . $url . '&ua=' . $ua . '&mcw=' . $mcw;
       return self::CurlWithTimeout($request_url);
    }
}

?>

