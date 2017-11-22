<?php
/**
 * Common library
 */
class tradeCommon
{

    /**
     * 返回来源页的网址
     * @return string
     */
    public static function reurl()
    {
        return !empty($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : NULL;
    }

    /**
     * 返回页面访问类型
     * @return string
     */
    public static function method()
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    /**
     * 返回当前域名
     * @return string
     */
    public static function host()
    {
        return $_SERVER['HTTP_HOST'];
    }

    /**
     * 输出对象
     * @param all $rows     需要输出的对象
     * @param bool $type    以print_r还是var_dump来输出
     * @param bool $exit    输出后是否中止
     */
    public static function pr($rows, $type = false, $exit = false)
    {
        echo '<pre>';
        $type ? var_dump($rows) : print_r($rows);
        echo '</pre>';
        $exit && exit();
    }

    /**
     * 页面跳转
     * @param string $url
     * @param bool $t
     */
    public static function go($url, $type = false)
    {
        !$type ? header('Location:' . $url) : print('<script>window.location.href="' . $url . '"</script>');
        exit;
    }

    /**
     * 获取Reqest数据
     * @param string $vn    变量名
     * @param string $type  获取类型
     * @param string $default   默认值
     * @param bool $mysqlEncode 是否使用mysql_escape_string
     * @return string/int/array
     */
    public static function & request($vn, $type = 'G', $default = NULL, $mysqlEncode = false)
    {
        switch($type)
        {
            case 'G':
                $val = empty($_GET[$vn]) ? $default : $_GET[$vn];
                break;
            case 'P':
                $val = empty($_POST[$vn]) ? $default : $_POST[$vn];
                break;
            case 'C':
                $val = empty($_COOKIE[$vn]) ? $default : $_COOKIE[$vn];
                break;
            case 'S':
                $val = empty($_SESSION[$vn]) ? $default : $_SESSION[$vn];
                break;
            default :
                $val = empty($_REQUEST[$vn]) ? $default : $_REQUEST[$vn];
                break;
        }

        $val && !is_array($val) && $val = trim($val);

        if(!empty($val) && get_magic_quotes_gpc())
        {
            if(is_array($val))
            {
                foreach($val as $key => $v)
                {
                    $val[$key] = stripslashes($v);
                }
            }
            else
            {
                $val = stripslashes($val);
            }
        }

        if(!empty($val) && $mysqlEncode)
        {
            if(is_array($val))
            {
                foreach($val as $key => $v)
                {
                    $val[$key] = mysql_escape_string($v);
                }
            }
            else
            {
                $val = mysql_escape_string($val);
            }
        }

        return $val;
    }

    /**
     * 批量获取Request数据
     * @param array $cols
     * @param string $type
     * @return array
     */
    public static function multiRequest(array $cols, $type = 'G')
    {
        $result = array();

        foreach($cols as $k => $v)
        {
            $result[$v] = trim(common::request($v, $type));
        }

        return $result;
    }

    /**
     * 获取客户端IP
     * @return int(10)
     */
    public static function getip($ip2long = FALSE)
    {
        $ip = '';
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $arr    =   explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            $pos    =   array_search('unknown',$arr);
            if(false !== $pos) unset($arr[$pos]);
            $ip     =   trim($arr[0]);
        }elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ip     =   $_SERVER['HTTP_CLIENT_IP'];
        }elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $ip     =   $_SERVER['REMOTE_ADDR'];
        }
        if(!empty($ip) && $ip2long)
            $ip = bindec(decbin(ip2long($ip)));
        return $ip;
    }

    /**
     * 将一维数组转为字符串 (array('a' => 1, 'b' => 2, 'c => '+1') To: a=1, b=2, c=c+1)
     * @param array 需要转换的数组对象
     * @return string
     */
    public static function arrayToString(array $datas = array())
    {
        $str = NULL;

        if(!empty($datas))
        {
            $s = array('like', 'update', 'id', 'key');

            $i = 1;
            $dataCount = count($datas);
            foreach($datas as $key => $data)
            {
                $str .= (in_array($key, $s) ? '`' . $key . '`' : $key) . ($data && in_array($data, array('?+1', '?-1', '?+2', '?-2')) ? '=' . $key . strtr($data, array('?' => NULL)) : '=\'' . $data . '\'') . ($i < $dataCount ? ', ' : NULL);
                //$str .= (in_array($key, $s) ? '`' . $key . '`' : $key) . ($data && in_array($data, array('?+1', '?-1', '?+2', '?-2')) ? '=' . $key . strtr($data, array('?' => NULL)) : '=\'' . mysql_real_escape_string($data) . '\'') . ($i < $dataCount ? ', ' : NULL);
                $i++;
            }
        }
        return $str;
    }

    /**
     * 以HTTP方式远程获取内容
     * @param string $url
     * @param array $datas
     * @param int $timeout
     * @param string $method
     * @return string
     */
    public static function & getContents($url, $datas = array(), $timeout = 10, $method = 'GET', $contentType = NULL, $cookies = NULL)
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
        elseif($method == 'DELETE' || $method == 'PUT')
        {
            curl_setopt ($ch, CURLOPT_NOSIGNAL, TRUE);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        }

        !empty($cookies) && curl_setopt($ch, CURLOPT_COOKIE, $cookies);

        if($contentType)
        {
            curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: " . $contentType));
        }

        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $results = curl_exec($ch);

        if(curl_errno($ch))
        {
            return $results;
        }
        $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);  
        curl_close($ch);
        //echo 'curl_http_code:'.$http_status.' ';
        return $results;
    }

    /**
     * 以HTTP方式远程获取内容
     * @param string $url
     * @param array $datas
     * @param int $timeout
     * @param string $method
     * @return string
     */
    public static function & getBirdexContents($url, $datas = array(), $timeout = 10, $method = 'GET', $header, $cookies = NULL)
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
        elseif($method == 'DELETE' || $method == 'PUT')
        {
            curl_setopt ($ch, CURLOPT_NOSIGNAL, TRUE);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        }

        !empty($cookies) && curl_setopt($ch, CURLOPT_COOKIE, $cookies);

        if($header)
        {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        }

        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $results = curl_exec($ch);

        if(curl_errno($ch))
        {
            $results = 9999;
        }
        curl_close($ch);
        return $results;
    }

    /**
     * 对多URL地址进行GET/POST操作，并返回数据
     * @param array $urls
     * @param array||string $data
     * @param string $method
     * @param int $timeOut
     * @return array
     */
    public static function & getMultiContents(array $urls, $data = array(), $method = 'GET', $timeOut = 3)
    {
        $responses = array();

        $queue = curl_multi_init();
        $map = array();

        $query = $data ? (is_array($data) ? http_build_query($data, NULL, '&') : $data) : '';

        foreach($urls as $url)
        {
            $ch = curl_init();

            if($query)
            {
                if($method == 'GET')
                {
                    $url .= '?' . $query;
                }
                else
                {
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $query);
                }
            }

            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_TIMEOUT, $timeOut);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_NOSIGNAL, true);

            curl_multi_add_handle($queue, $ch);
            $map[(string) $ch] = $url;
        }

        do
        {
            while(($code = curl_multi_exec($queue, $active)) == CURLM_CALL_MULTI_PERFORM);

            if($code != CURLM_OK)
            {
                break;
            }

            while($done = curl_multi_info_read($queue))
            {

                $info = curl_getinfo($done['handle']);
                $info = array('http_code' => $info['http_code']);
                $error = curl_error($done['handle']);
                $error && $info['error'] = $error;
                $result = curl_multi_getcontent($done['handle']);
                $responses[$map[(string) $done['handle']]] = compact('info', 'result');

                curl_multi_remove_handle($queue, $done['handle']);
                curl_close($done['handle']);
            }

            if($active > 0)
            {
                curl_multi_select($queue, 0.5);
            }
        }
        while($active);

        curl_multi_close($queue);

        return $responses;
    }

    /**
     * 并发获取多个URL的内容
     * @param array $urls
     * @param int $timeount
     * @return array
     */
    public static function getUrlsContent($urls, $timeount = 3, $encodeUrl = TRUE)
    {
        $responses = array();

        if(!empty($urls))
        {
            !is_array($urls) && $urls = array($urls);

            $queue = curl_multi_init();
            $map = array();

            foreach($urls as $url)
            {
                $ch = curl_init();

                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_TIMEOUT, $timeount);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_HEADER, 0);
                curl_setopt($ch, CURLOPT_NOSIGNAL, true);

                curl_multi_add_handle($queue, $ch);
                $map[(string) $ch] = $url;
            }

            do
            {
                while(($code = curl_multi_exec($queue, $active)) == CURLM_CALL_MULTI_PERFORM);

                if($code != CURLM_OK)
                {
                    break;
                }

                while($done = curl_multi_info_read($queue))
                {
                    $info = curl_getinfo($done['handle']);
                    $info = array('http_code' => $info['http_code'], 'total_time' => $info['total_time']);
                    $error = curl_error($done['handle']);
                    !empty($error) && $info['error'] = $error;
                    $result = curl_multi_getcontent($done['handle']);

                    $k = $encodeUrl ? md5($map[(string) $done['handle']]) : $map[(string) $done['handle']];
                    $responses[$k] = compact('info', 'result');

                    curl_multi_remove_handle($queue, $done['handle']);
                    curl_close($done['handle']);
                }

                if($active > 0)
                {
                    curl_multi_select($queue, $timeount);
                }
            }
            while($active);

            curl_multi_close($queue);
        }

        return $responses;
    }

    /**
     * 开启页面Gzip
     * @param string $content
     * @return string
     */
    public static function ob_gzip($content)
    {
        if(!headers_sent() && extension_loaded("zlib") && !empty($_SERVER["HTTP_ACCEPT_ENCODING"]) && strstr($_SERVER["HTTP_ACCEPT_ENCODING"], "gzip"))
        {
            header('Content-type:application/json;charset=utf-8');

            $content = gzencode($content, 9);

            header("Content-Encoding: gzip");
            header("Vary: Accept-Encoding");
            header("Content-Length: " . strlen($content));
        }

        return $content;
    }

    /**
     * 版本号对比
     * @param string $version1
     * @param string $version2
     * @return boolean
     */
    public static function diffVersion($version1, $version2)
    {
        $version1 = common::versionToNumber($version1);
        $version2 = common::versionToNumber($version2);

        return $version1 > $version2;
    }

    /**
     * 将版本号转换为数字
     * @param string $version
     * @return int
     */
    public static function versionToNumber($version)
    {
        $number = 0;

        !$version && $version = '0.0.0';
        $vTmp = explode('.', $version);

        if(count($vTmp) == 3)
        {
            foreach($vTmp as $k => $v)
            {
                if(!is_numeric($v))
                {
                    $number = 0;

                    break;
                }
                else
                {
                    $number += intval($v) << (16 - $k * 8);
                }
            }
        }

        return $number;
    }

    /**
     * 格式化在线人数
     * @param int $num
     */
    public static function formatOnlineNum($num)
    {
        $num = is_numeric($num) ? intval($num) : 1;
        $num > 90 && $num = $num * 9;
        $num < 10000 && $newNum = $num;
        ($num >= 10000 && $num < 100000) && $newNum = round($num / 10000, 1) . '万';
        $num >= 100000 && $newNum = ceil($num / 10000) . '万';

        return $newNum;
    }

    /**
     * 与微博一样的计算字符长度（1个汉字算1个字，1个字母或数字，算0.5个字）
     * @param string $content
     * @return int
     */
    public static function weiboStrlen($content)
    {
        preg_match_all("/[\x80-\xff]/", $content, $cn);

        $i = mb_strlen($content, 'utf-8');
        $j = !empty($cn[0]) ? count($cn[0]) / 3 : 0;

        $i = ($i - $j) / 2 + $j;

        return $i;
    }

    /**
     * 组装地址
     * @param type $urlArray
     * @return string
     */
    public static function packUrl($urlArray)
    {

        if(empty($urlArray['query']))
        {
            $path = (isset($urlArray["path"]) ? $urlArray["path"] : "");
        }
        else
        {
            $path = (isset($urlArray["path"]) ? $urlArray["path"] : "/");
        }

        $url = (isset($urlArray["scheme"]) ? $urlArray["scheme"] . "://" : "") .
                (isset($urlArray["user"]) ? $urlArray["user"] . ":" : "") .
                (isset($urlArray["pass"]) ? $urlArray["pass"] . "@" : "") .
                (isset($urlArray["host"]) ? $urlArray["host"] : "") .
                (isset($urlArray["port"]) ? ":" . $urlArray["port"] : "") .
                $path .
                (!empty($urlArray['query']) ? "?" . http_build_query($urlArray['query']) : "") .
                ( isset($urlArray["fragment"]) ? "#" . $urlArray["fragment"] : "");

        return $url;
    }

    /**
     * 将内容中的HTML实体替换为字符串
     * @param array $data
     * @return string
     */
    public static function pregReplaceContentHtmlToString($data)
    {
        return common::htmlEntitiesToString($data[0]);
    }

    /**
     * 将HTML实体替换为字符串
     * @param string $str
     * @return string
     */
    public static function htmlEntitiesToString($str)
    {
        return mb_convert_encoding($str, 'utf-8', 'HTML-ENTITIES');
    }

    public static function dpb()
    {
        echo '<pre>';
        debug_print_backtrace();
        echo '</pre>';
    }

    //身份证号码验证
    public static function idcard_verify_number($idcard) {
        if(strlen($idcard)!=18) {
            return false;
        }

        $idcard_base = substr($idcard,0,17);

        //加权因子
        $factor = array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2);

        //校验码对应值
        $verify_number_list = array('1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2');

        $checksum = 0;
        for($i = 0; $i < strlen($idcard_base); $i++) {
            $checksum += substr($idcard_base, $i, 1) * $factor[$i];
        }

        $mod = $checksum % 11;
        $verify_number = $verify_number_list[$mod];

        if($verify_number == strtoupper(substr($idcard,17,1))){
            return true;

        } else {
            return false;
        }
    }
    
    /**
	 * Make an HTTP request
	 *
	 * @return string API results
	 * @ignore
	 */
	public static function requestUrl($url, $method, $postfields = NULL, $headers = null,$timeout = 60)
	{
		$curl = curl_init();
		
		//curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
		curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 15);
		curl_setopt($curl, CURLOPT_TIMEOUT, $timeout);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($curl, CURLOPT_HEADER, FALSE);
		
		switch ($method)
		{
			case 'POST' :
				curl_setopt($curl, CURLOPT_POST, TRUE);
				
				if ($postfields)
				{
					curl_setopt($curl, CURLOPT_POSTFIELDS, $postfields);
				}
			break;
			case 'PUT' :
				curl_setopt($curl, CURLOPT_PUT, true);
				
				if ($postfields)
				{
					curl_setopt($curl, CURLOPT_POSTFIELDS, $postfields);
				}
			break;
			case 'DELETE' :
				curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'DELETE');
				
				if ($postfields)
				{
					$url = "{$url}?{$postfields}";
				}
			break;
		}
		
		$headers[] = 'API-RemoteIP: ' . self::getip();
        curl_setopt($curl, CURLOPT_USERAGENT, isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : 'Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.1.2) Gecko/20090729 Firefox/3.5.2 GTB5');
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($curl, CURLINFO_HEADER_OUT, TRUE);
                curl_setopt ($curl,CURLOPT_REFERER,'http://passport.shihuo.cn');
		
		if (substr($url, 0, 8) == 'https://')
		{
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
		}
		
		$response = curl_exec($curl);
		
		curl_close($curl);
		
		return $response;
	}

    /**
     * 处理链接用的。第一个参数是 原链接 第二个参数是 要添加的参数 第三个参数是 过滤的参数 第四个参数是是否保留 #后面的参数 默认是保留
     * @param $url
     * @param array $params
     * @param array $fifter
     * @param bool $del_fragment
     * @return string
     */
    public static function fifterUrlParams($url, array $params, array $fifter = array(),$del_fragment = FALSE) {
        if(empty($url))
        {
            $url = $_SERVER['REQUEST_URI'];
        }
        if (empty($params) && empty($fifter) && empty($del_fragment)) {
            return $url;
        }
        $urllist = parse_url($url);
        $output = array();
        if (isset($urllist['query'])) {
            parse_str($urllist['query'], $output);
        }
        if (count($params) > 0) {
            foreach ($params as $key => $val) {
                $output[$key] = $val;
            }
        }
        if (count($fifter) > 0) {
            foreach ($output as $key => $val) {
                if (in_array($key, $fifter)) {
                    unset($output[$key]);
                }
            }
        }

        if(empty($output)){
            $path = (isset($urllist["path"]) ? $urllist["path"] : "");
        }else{
            $path = (isset($urllist["path"]) ? $urllist["path"] : "/");
        }

        $url = (isset($urllist["scheme"]) ? $urllist["scheme"] . "://" : "") .
            (isset($urllist["user"]) ? $urllist["user"] . ":" : "") .
            (isset($urllist["pass"]) ? $urllist["pass"] . "@" : "") .
            (isset($urllist["host"]) ? $urllist["host"] : "") .
            (isset($urllist["port"]) ? ":" . $urllist["port"] : "") .
            $path .
            (!empty($output) ? "?" . http_build_query($output) : "") .
            ( (isset($urllist["fragment"]) && $del_fragment == FALSE ) ? "#" . $urllist["fragment"] : "");
        return $url;
    }
    /**
     * 获取处理后链接参数用的。第一个参数是 原链接 第二个参数是 要添加的参数 第三个参数是 过滤的参数 第四个参数是是否保留 #后面的参数 默认是保留
     * @param $url
     * @param array $params
     * @param array $fifter
     * @return string
     */
    public static function fifterUrlParamsArray($url, array $params, array $fifter = array())
    {
        if(empty($url))
        {
            $url = $_SERVER['REQUEST_URI'];
        }
        $urllist = parse_url($url);
        $output = array();
        if (isset($urllist['query'])) {
            parse_str($urllist['query'], $output);
        }
        if (count($params) > 0) {
            foreach ($params as $key => $val) {
                $output[$key] = $val;
            }
        }
        if (count($fifter) > 0) {
            foreach ($output as $key => $val) {
                if (in_array($key, $fifter)) {
                    unset($output[$key]);
                }
            }
        }
        return $output;
    }
    //用mysql 加锁
    public static function getLock($lockStr,$timeOut){
        $con = Doctrine_Manager::getInstance()->getConnection('kaluli');
        $query = 'SELECT GET_LOCK(\''.addslashes($lockStr).'\','.$timeOut.') as status';
        return $con->fetchAssoc($query);
    }
    public static function releaseLock($lockStr){
        $con = Doctrine_Manager::getInstance()->getConnection('kaluli');
        $query = 'SELECT RELEASE_LOCK("'.addslashes($lockStr).'")';
        return $con->fetchAssoc($query);
    }

    public static function is_crawler() {
        $userAgent = strtolower($_SERVER['HTTP_USER_AGENT']);
        $spiders = array(
            'Googlebot', // Google 爬虫
            'Baiduspider', // 百度爬虫
            'Yahoo! Slurp', // 雅虎爬虫
            'YodaoBot', // 有道爬虫
            'msnbot', // Bing爬虫
            'Sogou', // 搜狗爬虫
            'iaskspider', // 新浪爱问爬虫
            'Mediapartners', // Google AdSense广告内容匹配爬虫
            'QihooBot', // 北大天网的搜索引擎爬虫
            'Gigabot', // Gigabot搜索引擎爬虫
            'spider', // 更多爬虫关键字
        );
        foreach ($spiders as $spider) {
            $spider = strtolower($spider);
            if (strpos($userAgent, $spider) !== false) {
                return true;
            }
        }
        return false;
    }

    //生成七牛镜像图片地址
    public static function getQiNiuProxyPath($url) {

        if (preg_match('/images-amazon.com/',$url)){
            $url = str_replace("_SS100_.jpg","",$url);
            $url = $url.'_SS500_.jpg';
        }

        $base64 = base64_encode($url);
        $base64 = str_replace('+', '-', $base64);
        $base64 = str_replace('/', '_', $base64);
        $base64 = str_replace('=', '', $base64);

        if(!strcmp($base64,'aHR0cDovL2Jicy5odXB1LmNvbS9iYnNrY3kvYXBpX25ld19pbWFnZS5waHA_dWlkPTI3NDEwMTc1JnR5cGU9Ymln')){
            return 'http://shihuo.hupucdn.com/ucditor/kulili/20161014/1476439884.jpg';
        }
        return 'http://shihuoproxy.hupucdn.com/' . $base64;

    }

	//获取代购网站前缀
	public static function getDaigouPrefix($item_url = '', $business = '') {
		if (preg_match('/amazon.com/',$item_url)){
			return 'usa.amazon.';
		}
		if (preg_match('/6pm.com/',$item_url)){
			return 'usa.6pm.';
		}
		if (preg_match('/gnc.com/',$item_url)){
			return 'usa.gnc.';
		}
		if (preg_match('/us.levi.com/',$item_url)){
			return 'usa.levis.';
		}
		if (preg_match('/store.nba.com/',$item_url)){
			return 'usa.nbastore.';
		}
        if (preg_match('/amazon.co.jp/',$item_url)){
            return 'jp.amazon.';
        }
        if ($business == TrdProductAttrTable::$zhifa_business){
            return 'cn.hk.';
        }
        if ($business == TrdProductAttrTable::$zhifa_shihuo_business){
            return 'cn.sh.';
        }
        if ($business == TrdProductAttrTable::$zhifa_ebay_business){
            return 'cn.hkebay.';
        }
		return '';
	}

    // 获取不同环境下版本控制号
    public static function getVersionByEnv() {
        $env = sfConfig::get('sf_environment');
        if ('prod' == $env) {
            return sfConfig::get('app_app_style_version');
        } else {
            return time();
        }
    }

    // 获取不同环境下js和css路径
    public static function getStaticPathByEnv() {
        $env = sfConfig::get('sf_environment');
        if ('prod' == $env) {
            return 'dist';
        } else {
            return 'dev';
        }
    }

    /**
     * 判断是否是来源app
     * @return boolean
     */
    public static function is_from_app($vIos = '2.2.0', $vAndroid = '2.2.0')
    {
        if (!isset($_SERVER['HTTP_USER_AGENT'])) {
            return false;
        }
        $user_agent = strtolower($_SERVER['HTTP_USER_AGENT']);
        if (preg_match('/playstation/i', $user_agent) || preg_match('/ipad/i', $user_agent) || preg_match('/ucweb/i', $user_agent)) {
            return false;
        }
        if (preg_match('/shihuo/i', $user_agent) && preg_match('/sc/i', $user_agent)) {
            $patternVersion = '/shihuo\/([0-9.]+)/i';
            if (preg_match($patternVersion, $user_agent, $matches)) {
                $v = $matches[1];
                if (stristr($user_agent, 'iphone')) {
                    if ($vIos <= $v) {
                        return true;
                    }
                } elseif (stristr($user_agent, 'android')) {
                    if ($vAndroid <= $v) {
                        return true;
                    }
                }
            }
        }
        return false;
    }

    /**
     * 生成验证码
     * @param sfWebRequest $request
     */
    public static function generateCaptcha()
    {
        sfProjectConfiguration::getActive()->loadHelpers('captcha');

//        $ImgPath = sfConfig::get('sf_root_dir').'/web/uploads/trade/captcha/';
//        if (false === file_exists($ImgPath))
//        {
//            mkdir($ImgPath,0777);
//        }
        $data = array(
            //      'img_path'      =>$ImgPath,
            //     'img_url'       => 'http://www.shihuo.cn/uploads/trade/captcha/',
            'img_width'     =>80,
            'expiration'    =>1800,
        );

        $s= create_captcha($data);

        return array(
            'word' => $s['word'],
         //   'image' => $s['image'],
        //    'img_url'=> $s['img_url'],
        //    'img_path' => $s['img_path']
        );
    }
    /**
     *
     * 识货发送topic消息
     * @param $routing_key
     * @param array $message
     * @param $queue
     * @return bool
     */
    public static function sendMqMessage($routing_key, $message = array(), $queue, $delayed = 2000)
    {
        if (!$routing_key || empty($message)) return false;
        try {
            $amqpParams = sfConfig::get("app_mabbitmq_options_shihuo");
            $connection = new AMQPConnection($amqpParams['params']['host'], $amqpParams['params']['port'], $amqpParams['params']['user'], $amqpParams['params']['pass'], $amqpParams['params']['vhost']);
            $channel = $connection->channel();
            $arguments = array(
                "x-dead-letter-exchange" => array("S", "amq.topic"),
                "x-message-ttl" => array("I", $delayed),
                "x-dead-letter-routing-key" => array("S", $routing_key)
            );
            $channel->queue_declare($queue, false, true, false, false, false, $arguments);

            $msg = new AMQPMessage(json_encode($message));
            $channel->basic_publish($msg, '', $queue);
        } catch(Exception $e) {
            //放入redis临时队列
            $data = array(
                'routing_key' => $routing_key,
                'message' => $message,
                'queue' => $queue,
                'delayed' => $delayed,
                'created_at' => time(),
            );
            $redis = new tradeUpdateRedisList('trade_mq_temporary_message');
            $redis->addList($data);
        }
        return true;
    }

    /**
     * 获取抓取的ip组
     */
    public static function getHaitaoRemoteIp($item_url){
        if(empty($item_url)) return false;
        $ips = array(
            0 => array(
                    'http://58.96.175.174:3000/getProductInfo?url=' . urlencode($item_url),
                    'http://47.88.21.51:3000/getProductInfo?url=' . urlencode($item_url)
                 ),
            1 => array(
                    'http://49.213.11.177:3000/getProductInfo?url=' . urlencode($item_url),
                    'http://47.88.3.145:3000/getProductInfo?url=' . urlencode($item_url)
                ),
        );
        $count = count($ips);
        $key = mt_rand(0, $count - 1);
        return $ips[$key];
    }
}

?>