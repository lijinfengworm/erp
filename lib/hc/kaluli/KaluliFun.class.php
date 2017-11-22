<?php

/**
 * 卡路里公共函数类
 * About: 梁天
 */
class KaluliFun
{


    /**
     * 快速实例化类
     */
    public static function getObject($className, $options = array())
    {
        if (empty($className)) throw new sfException('classname不得为空！');
        static $_instance = array();
        $guid = $className . self::to_guid_string($options);
        if (!isset($_instance[$guid])) {
            if (class_exists($className)) {
                $_instance[$guid] = new $className($options);
            } else {
                throw new sfException('对象 ' . $className . '不存在！');
            }
        }
        return $_instance[$guid];
    }

    public static function to_guid_string($mix)
    {
        if (is_object($mix)) {
            return spl_object_hash($mix);
        } elseif (is_resource($mix)) {
            $mix = get_resource_type($mix) . strval($mix);
        } else {
            $mix = serialize($mix);
        }
        return md5($mix);
    }


    /**
     * array  to  xml
     */
    public static function xml_encode($data, $root = 'root', $item = 'item', $attr = '', $id = 'id', $encoding = 'utf-8')
    {
        if (is_array($attr)) {
            $_attr = array();
            foreach ($attr as $key => $value) {
                $_attr[] = "{$key}=\"{$value}\"";
            }
            $attr = implode(' ', $_attr);
        }
        $attr = trim($attr);
        $attr = empty($attr) ? '' : " {$attr}";
        $xml = "<?xml version=\"1.0\" encoding=\"{$encoding}\"?>";
        $xml .= "<{$root}{$attr}>";
        $xml .= data_to_xml($data, $item, $id);
        $xml .= "</{$root}>";
        return $xml;
    }


    /**
     * 判断是否是合格的手机客户端
     * @return boolean
     */
    public static function is_mobile()
    {
        if (!isset($_SERVER['HTTP_USER_AGENT'])) {
            return false;
        }
        $user_agent = strtolower($_SERVER['HTTP_USER_AGENT']);

        if (preg_match('/playstation/i', $user_agent) OR preg_match('/ipad/i', $user_agent) OR preg_match('/ucweb/i', $user_agent)) {
            return false;
        }
        if (preg_match('/iemobile/i', $user_agent) OR preg_match('/mobile\ssafari/i', $user_agent) OR preg_match('/iphone/i', $user_agent) OR preg_match('/android/i', $user_agent) OR preg_match('/symbian/i', $user_agent) OR preg_match('/series40/i', $user_agent)) {
            return true;
        }
        return false;
    }

    //是否能应该跳转到m
    public static function redirect_mobile()
    {
        $redirectPc = sfContext::getInstance()->getRequest()->getCookie('redirectPc');
        if ($redirectPc) {
            return false;
        }
        return true;
    }


    //每行解析成一个数组  $is_unique 是否去重复
    public static function textareaToArray($string = '', $is_unique = false)
    {
        if (stristr($_SERVER['HTTP_USER_AGENT'], 'Win')) {
            $crlf = "\r\n";
        } elseif (stristr($_SERVER['HTTP_USER_AGENT'], 'Mac')) {
            $crlf = "\r"; // for very old MAC OS
        } else {
            $crlf = "\n";
        }
        $arr = explode($crlf, $string);
        $_return = array();
        foreach ($arr as $k => $v) {
            $v = trim($v);
            if (!empty($v) && $v != '') {
                $_return[$k] = trim($v);
            }
        }
        if ($is_unique) {
            $_return = array_unique($_return);
        }
        return $_return;
    }


    /**
     * 格式化显示小数价格
     * @param $price  价格
     * @param int $decimal 显示小数点后面几位
     * @return string   返回价格
     * Demo  FunBase::price_format(100.15,1);   显示 100.2
     * FunBase::price_format(100);   显示 100
     * FunBase::price_format(5+5.33333，2);   显示 10.33
     */
    public static function price_format($price, $decimal = 2)
    {
        if (!strstr($price, '.')) {
            $decimal = 0;
        }
        $_price = number_format($price, $decimal);
        return $_price;
    }


    /**
     * 将二维数组指定的一个键转换成字符串
     * @param type $arr
     * @param type $par
     */
    public static function get_current_array($arr, $par, $ico = ',', $one = false)
    {
        $data = array();
        if (is_array($arr)) {
            foreach ($arr as $k => $v) {
                $data[] = $v[$par];
            }
            if ($one) {
                return $data;
            }
            return implode($ico, $data);
        }
        return $arr;
    }


    /**
     * 加密存储cookie
     * 梁天 2015-04-27
     */
    public static function SiteCookie($name, $value = '', $option = null)
    {
        //cookie 加盐码 暂时先写这里吧
        $_AUTHCODE = 'Sha@sltEdg#';
        // 默认设置
        $config = array(
            'prefix' => '', // cookie 名称前缀
            'expire' => 0, // cookie 保存时间
            'path' => '/', // cookie 保存路径
            'domain' => '', // cookie 有效域名
        );
        // 参数设置(会覆盖黙认设置)
        if (!empty($option)) {
            if (is_numeric($option))
                $option = array('expire' => $option);
            elseif (is_string($option))
                parse_str($option, $option);
            $config = array_merge($config, array_change_key_case($option));
        }
        // 清除指定前缀的所有cookie
        if (is_null($name)) {
            if (empty($_COOKIE))
                return;
            // 要删除的cookie前缀，不指定则删除config设置的指定前缀
            $prefix = empty($value) ? $config['prefix'] : $value;
            if (!empty($prefix)) {// 如果前缀为空字符串将不作处理直接返回
                foreach ($_COOKIE as $key => $val) {
                    if (0 === stripos($key, $prefix)) {
                        setcookie($key, '', time() - 3600, $config['path'], $config['domain']);
                        unset($_COOKIE[$key]);
                    }
                }
            }
            return;
        }
        $name = $config['prefix'] . $name;
        if ('' === $value) {
            $value = isset($_COOKIE[$name]) ? $_COOKIE[$name] : null; // 获取指定Cookie
            return self::authcode($value, "DECODE", $_AUTHCODE);
        } else {
            if (is_null($value)) {
                setcookie($name, '', time() - 3600, $config['path'], $config['domain']);
                unset($_COOKIE[$name]); // 删除指定cookie
            } else {
                //$value 加密
                $value = self::authcode($value, "", $_AUTHCODE);
                // 设置cookie
                $expire = !empty($config['expire']) ? time() + intval($config['expire']) : 0;
                setcookie($name, $value, $expire, $config['path'], $config['domain']);
                $_COOKIE[$name] = $value;
            }
        }
    }


    /**
     * 加密解密
     * @param type $string 明文 或 密文
     * @param type $operation DECODE表示解密,其它表示加密
     * @param type $key 密匙
     * @param type $expiry 密文有效期
     * @return string
     */
    public static function authcode($string, $operation = 'DECODE', $key = '', $expiry = 0)
    {
        // 动态密匙长度，相同的明文会生成不同密文就是依靠动态密匙
        $ckey_length = 4;
        // 密匙
        $key = md5(($key ? $key : ''));
        // 密匙a会参与加解密
        $keya = md5(substr($key, 0, 16));
        // 密匙b会用来做数据完整性验证
        $keyb = md5(substr($key, 16, 16));
        // 密匙c用于变化生成的密文
        $keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length) : substr(md5(microtime()), -$ckey_length)) : '';
        // 参与运算的密匙
        $cryptkey = $keya . md5($keya . $keyc);
        $key_length = strlen($cryptkey);
        // 明文，前10位用来保存时间戳，解密时验证数据有效性，10到26位用来保存$keyb(密匙b)，解密时会通过这个密匙验证数据完整性
        // 如果是解码的话，会从第$ckey_length位开始，因为密文前$ckey_length位保存 动态密匙，以保证解密正确
        $string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0) . substr(md5($string . $keyb), 0, 16) . $string;
        $string_length = strlen($string);
        $result = '';
        $box = range(0, 255);
        $rndkey = array();
        // 产生密匙簿
        for ($i = 0; $i <= 255; $i++) {
            $rndkey[$i] = ord($cryptkey[$i % $key_length]);
        }
        // 用固定的算法，打乱密匙簿，增加随机性，好像很复杂，实际上对并不会增加密文的强度
        for ($j = $i = 0; $i < 256; $i++) {
            $j = ($j + $box[$i] + $rndkey[$i]) % 256;
            $tmp = $box[$i];
            $box[$i] = $box[$j];
            $box[$j] = $tmp;
        }
        // 核心加解密部分
        for ($a = $j = $i = 0; $i < $string_length; $i++) {
            $a = ($a + 1) % 256;
            $j = ($j + $box[$a]) % 256;
            $tmp = $box[$a];
            $box[$a] = $box[$j];
            $box[$j] = $tmp;
            // 从密匙簿得出密匙进行异或，再转成字符
            $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
        }
        if ($operation == 'DECODE') {
            // substr($result, 0, 10) == 0 验证数据有效性
            // substr($result, 0, 10) - time() > 0 验证数据有效性
            // substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16) 验证数据完整性
            // 验证数据有效性，请看未加密明文的格式
            if ((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26) . $keyb), 0, 16)) {
                return substr($result, 26);
            } else {
                return '';
            }
        } else {
            // 把动态密匙保存在密文里，这也是为什么同样的明文，生产不同密文后能解密的原因
            // 因为加密后的密文可能是一些特殊字符，复制过程可能会丢失，所以用base64编码
            return $keyc . str_replace('=', '', base64_encode($result));
        }
    }


    /**
     *  [条件检索url处理] 对于query url中已经存在的数据进行删除;没有的参数进行添加
     * @param $queryKey   要增加或者改变的 key
     * @param string $queryVal key 对应的value
     * @return string   返回 修改后的 url
     * 梁天 2015-05-05
     */
    public static function searchUrl($queryKey, $queryVal = '')
    {
        if (is_array($queryKey)) {
            $concatStr = '';
            $fromStr = array();
            $toStr = array();

            foreach ($queryKey as $k => $v) {
                $urlVal = sfContext::getInstance()->getRequest()->getParameter($v);
                $tempVal = isset($queryVal[$k]) ? $queryVal[$k] : $queryVal;

                if ($urlVal === null) {
                    $concatStr .= '&' . $v . '=' . $tempVal;
                } else {
                    $fromStr[] = '&' . $v . '=' . $urlVal;
                    $toStr[] = '&' . $v . '=' . $tempVal;
                }
            }
            return self::clearUrl(str_replace($fromStr, $toStr, '?' . urldecode($_SERVER['QUERY_STRING'])) . $concatStr);
        } else {
            /*URL变量 arg[key] 格式支持
             *由于在 URL get方式传参时系统会把变量 arg[key] 直接判定为数组
             *所以这里需要对此类参数进行特殊处理;
             */
            preg_match('|(\w+)\[(\d+)\]|', $queryKey, $match);
            $urlVal = null;

            if (isset($match[2])) {
                //获取在url中已存储数据
                $urlArray = sfContext::getInstance()->getRequest()->getParameter($match[1]);
                if (isset($urlArray[$match[2]])) {
                    $urlVal = $urlArray[$match[2]];
                }
            } //考虑列表排序按钮的效果
            else {
                $urlVal = sfContext::getInstance()->getRequest()->getParameter($queryKey);
            }

            //如果此项url中没有$urlVal 并且 赋值还存在，则直接追加到url中即可
            if ($urlVal === null && $queryVal !== '') {
                return self::clearUrl('?' . $_SERVER['QUERY_STRING'] . '&' . $queryKey . '=' . $queryVal);
            } else {
                $fromStr[] = '&' . $queryKey . '=' . $urlVal;

                if ($queryVal === '') {
                    $toStr = '';
                } else {
                    $toStr[] = '&' . $queryKey . '=' . $queryVal;
                }
                return self::clearUrl(str_replace($fromStr, $toStr, '?' . urldecode($_SERVER['QUERY_STRING'])));
            }
        }
    }


    /**
     * 清理URL地址栏中的危险字符，防止XSS注入攻击
     * @param string $url
     * @return string
     */
    public static function clearUrl($url)
    {
        $url = trim($url);
        $url = strip_tags($url, ""); //清除HTML等代码
        $url = str_replace(array('\'', '"', '&#', "\\", "<", ">"), '', $url);
        $url = htmlspecialchars($url);
        return $url;
    }


    /**
     * 把返回的数据集转换成Tree
     * @param array $list 要转换的数据集
     * @param string $pid parent标记字段
     * @param string $level level标记字段
     * @return array
     */
    public static function list_to_tree($list, $pk = 'id', $pid = 'pid', $child = '_child', $root = 0)
    {
        // 创建Tree
        $tree = array();
        if (is_array($list)) {
            // 创建基于主键的数组引用
            $refer = array();
            foreach ($list as $key => $data) {
                $refer[$data[$pk]] =& $list[$key];
            }
            foreach ($list as $key => $data) {
                // 判断是否存在parent
                $parentId = $data[$pid];
                if ($root == $parentId) {
                    $tree[] =& $list[$key];
                } else {
                    //这里 直接无限分类  不管是第几层
                    if (isset($refer[$parentId])) {
                        $parent =& $refer[$parentId];
                        $parent[$child][] =& $list[$key];
                    }
                }
            }
        }
        return $tree;
    }


    /**
     * 判断是否是ajax跳转
     */
    public static function is_ajax()
    {
        return ((isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') ||
        !empty($_POST['ajax']) || !empty($_GET['ajax']) ? true : false);
    }


    /**
     * 把区间价格转换成真实价格
     */
    public static function getIntervalToPrice($price)
    {
        if (preg_match('/^\d+[\.]{0,1}\d*$/', $price)) {
            return $price;
        } else if (preg_match('/^(\d+[\.]{0,1}\d*)\-\d+[\.]{0,1}\d*$/', $price, $match)) {
            return $match[1];
        }
        return $price;
    }


    //金钱格式化  金钱、 是否输出  格式化方式
    public static function price_format_all($price, $is_echo = false, $frame_type = 0)
    {
        switch ($frame_type) {
            case 0:
                $price = number_format($price, 2, '.', '');
                break;
            case 1: // 保留不为 0 的尾数
                $price = preg_replace('/(.*)(\\.)([0-9]*?)0+$/', '\1\2\3', number_format($price, 2, '.', ''));
                if (substr($price, -1) == '.') {
                    $price = substr($price, 0, -1);
                }
                break;
            case 2: // 不四舍五入，保留1位
                $price = substr(number_format($price, 2, '.', ''), 0, -1);
                break;
            case 3: // 直接取整
                $price = intval($price);
                break;
            case 4: // 四舍五入，保留 1 位
                $price = number_format($price, 1, '.', '');
                break;
            case 5: // 先四舍五入，不保留小数
                $price = round($price);
                break;
        }
        if ($is_echo) sprintf("￥%s元", $price);
        return $price;
    }


    /**
     * 简洁版的AJAXreturn
     */
    public static function ajaxReturn($data, $type = 'json')
    {
        //self::myDebug($data);
        $data['state'] = $data['status'] ? "success" : "fail";
        switch (strtoupper($type)) {
            case 'JSON' :
                // 返回JSON数据格式到客户端 包含状态信息
                header('Content-Type:text/html; charset=utf-8');
                exit(json_encode($data));
            case 'XML' :
                // 返回xml格式数据
                header('Content-Type:text/xml; charset=utf-8');
                exit(FunBase::xml_encode($data));
            case 'JSONP':
                // 返回JSON数据格式到客户端 包含状态信息
                header('Content-Type:text/html; charset=utf-8');
                $handler = isset($data['callback']) ? $data['callback'] : 'callback';
                exit($handler . '(' . json_encode($data) . ');');
            case 'EVAL' :
                // 返回可执行的js脚本
                header('Content-Type:text/html; charset=utf-8');
                exit($data);
            default :
                // 用于扩展其他返回格式数据
                exit($data);
        }
    }


    /**
     * 获取客户端IP地址
     * @param integer $type 返回类型 0 返回IP地址 1 返回IPV4地址数字
     * @return mixed
     */
    public static function get_client_ip($type = 0)
    {
        $type = $type ? 1 : 0;
        static $ip = NULL;
        if ($ip !== NULL) return $ip[$type];
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            $pos = array_search('unknown', $arr);
            if (false !== $pos) unset($arr[$pos]);
            $ip = trim($arr[0]);
        } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        // IP地址合法验证
        $long = sprintf("%u", ip2long($ip));
        $ip = $long ? array($ip, $long) : array('0.0.0.0', 0);
        return $ip[$type];
    }


    /**
     * 生成随机码
     */
    public static function genRandomString($len = 6)
    {
        $chars = array(
            "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k",
            "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v",
            "w", "x", "y", "z", "A", "B", "C", "D", "E", "F", "G",
            "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R",
            "S", "T", "U", "V", "W", "X", "Y", "Z", "0", "1", "2",
            "3", "4", "5", "6", "7", "8", "9"
        );
        $charsLen = count($chars) - 1;
        // 将数组打乱
        shuffle($chars);
        $output = "";
        for ($i = 0; $i < $len; $i++) {
            $output .= $chars[mt_rand(0, $charsLen)];
        }
        return $output;
    }


    /**
     * 调试函数 很好用
     */
    public static function myDebug($str = '', $tips = '')
    {
        header('Content-Type:text/html;charset=utf-8');
        //输出地址
        $dbArr = debug_backtrace();
        $dbStr = '<span style="padding-left:15px;">{Path:';
        isset($dbArr[0]) && ($dbStr .= $dbArr[0]['file'] . ' [line:' . $dbArr[0]['line'] . ']');
        $dbStr .= '}</span>';
        echo '<pre style="-moz-border-radius:8px;width:99%;overflow:auto;margin:0;padding:1px 6px 6px;font-size:15px;border:1px solid #BBB;">';
        echo "<h3 style='background:-moz-linear-gradient(top,#ebebeb 0,#f8f8f8 31%,#fff 100%);
	background:-webkit-gradient(linear,left top,left bottom,color-stop(0%,#ebebeb),color-stop(31%,#f8f8f8),color-stop(100%,#fff));
	filter:progid:DXImageTransform.Microsoft.gradient(startColorstr=#EBEBEB,endColorstr=#ffffff,GradientType=0);padding:8px 0 7px 10px;  padding:2px 0 2px 3px;margin:0 0 15px;border-bottom:1px dashed #666;font-weight:normal;'>" . $tips . $dbStr . '</h3>';
        if (is_string($str)) {
            echo $str;
        } else if (is_array($str)) {
            print_r($str);
        } else {
            var_dump($str);
        }
        echo "</pre>";
        exit();
    }


    /**
     * 指定key的二维数组转换成一维数组
     */
    public static function DesignateArrayTwoToOne($arr, $keys)
    {
        $return = array();
        foreach ($arr as $k => $v) {
            $return[$v[$keys[0]]] = $v[$keys[1]];
        }
        return $return;
    }

    /**
     * 提取指定的二维数组 转换成一维数组
     */
    public static function DesignateNoKeyArrTwoToOne($arr, $keys, $is_distinct = false)
    {
        $return = array();
        foreach ($arr as $k => $v) {

            $return[] = trim($v[$keys]);
        }
        return $return;
    }


    /**
     * 字符截取
     * @param $string 需要截取的字符串
     * @param $length 长度
     * @param $dot
     */
    public static function str_cut($sourcestr, $length, $dot = '...')
    {
        $returnstr = '';
        $i = 0;
        $n = 0;
        $str_length = strlen($sourcestr); //字符串的字节数
        while (($n < $length) && ($i <= $str_length)) {
            $temp_str = substr($sourcestr, $i, 1);
            $ascnum = Ord($temp_str); //得到字符串中第$i位字符的ascii码
            if ($ascnum >= 224) {//如果ASCII位高与224，
                $returnstr = $returnstr . substr($sourcestr, $i, 3); //根据UTF-8编码规范，将3个连续的字符计为单个字符
                $i = $i + 3; //实际Byte计为3
                $n++; //字串长度计1
            } elseif ($ascnum >= 192) { //如果ASCII位高与192，
                $returnstr = $returnstr . substr($sourcestr, $i, 2); //根据UTF-8编码规范，将2个连续的字符计为单个字符
                $i = $i + 2; //实际Byte计为2
                $n++; //字串长度计1
            } elseif ($ascnum >= 65 && $ascnum <= 90) { //如果是大写字母，
                $returnstr = $returnstr . substr($sourcestr, $i, 1);
                $i = $i + 1; //实际的Byte数仍计1个
                $n++; //但考虑整体美观，大写字母计成一个高位字符
            } else {//其他情况下，包括小写字母和半角标点符号，
                $returnstr = $returnstr . substr($sourcestr, $i, 1);
                $i = $i + 1;            //实际的Byte数计1个
                $n = $n + 0.5;        //小写字母和半角标点等与半个高位字符宽...
            }
        }
        if ($str_length > strlen($returnstr)) {
            $returnstr = $returnstr . $dot; //超过长度时在尾处加上省略号
        }
        return $returnstr;
    }

    /**
     * 过滤XSS
     * @param $data 需要过滤的字符串
     * @param $htmlentities 转义
     * @param $dot
     **/
    public static function xssClean($data, $htmlentities = 0)
    {
        $htmlentities && $data = htmlentities($data, ENT_QUOTES, 'utf-8');
        // Fix &entity\n;
        $data = str_replace(array('&amp;', '&lt;', '&gt;'), array('&amp;amp;', '&amp;lt;', '&amp;gt;'), $data);
        $data = preg_replace('/(&#*\w+)[\x00-\x20]+;/u', '$1;', $data);
        $data = preg_replace('/(&#x*[0-9A-F]+);*/iu', '$1;', $data);
        $data = html_entity_decode($data, ENT_COMPAT, 'UTF-8');

        // Remove any attribute starting with "on" or xmlns
        $data = preg_replace('#(<[^>]+?[\x00-\x20"\'])(?:on|xmlns)[^>]*+>#iu', '$1>', $data);

        // Remove javascript: and vbscript: protocols
        $data = preg_replace('#([a-z]*)[\x00-\x20]*=[\x00-\x20]*([`\'"\\\\]*)[\x00-\x20]*j[\x00-\x20]*a[\x00-\x20]*v[\x00-\x20]*a[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2nojavascript...', $data);
        $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"\\\\]*)[\x00-\x20]*v[\x00-\x20]*b[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2novbscript...', $data);
        $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"\\\\]*)[\x00-\x20]*-moz-binding[\x00-\x20]*:#u', '$1=$2nomozbinding...', $data);

        // Only works in IE: <span style="width: expression(alert('Ping!'));"></span>
        $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"\\\\]*.*?expression[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
        $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"\\\\]*.*?behaviour[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
        $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"\\\\]*.*?s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:*[^>]*+>#iu', '$1>', $data);

        // Remove namespaced elements (we do not need them)
        $data = preg_replace('#</*\w+:\w[^>]*+>#i', '', $data);

        do {
            // Remove really unwanted tags
            $old_data = $data;
            $data = preg_replace('#</*(?:applet|b(?:ase|gsound|link)|embed|frame(?:set)?|i(?:frame|layer)|l(?:ayer|ink)|meta|object|s(?:cript|tyle)|title|xml)[^>]*+>#i', '', $data);
        } while ($old_data !== $data);

        // we are done...
        $data = self::filter_remote_img_type($data, FALSE);
        return $data;
    }

    /**
     * 过滤内容中有问题网络图片
     * @author phpseyo<phpseyo@qq.com>
     * @param string $text 过滤文本
     * @param boolean $bbcode 是否为BBCODE类型
     * @return string
     */
    public static function filter_remote_img_type($text, $bbcode = TRUE)
    {
        $pattern = $bbcode ? "/\[img[^\]]*\]\s*(.*?)+\s*\[\/img\]/is" : "/<img[^>]+src=[\'|\"]([^\'|\"]+)[\'|\"][^>]*[\/]?>/is";
        preg_match_all($pattern, $text, $matches);
        foreach ($matches[1] as $k => $src) {
            $data = get_headers($src);
            $header_str = implode('', $data);
            if (FALSE === strpos($header_str, 'Content-Type: image') || FALSE !== strpos($header_str, 'HTTP/1.1 401') || FALSE !== strpos($header_str, 'HTTP/1.1 404')) {
                $text = str_replace($matches[0][$k], '', $text);
            }
        }
        return $text;
    }


    /**
     * 时间格式化
     * @param date $date 时间
     * @return string
     */
    private static function timeFormat($date, $currenTime)
    {
        $commentTime = strtotime($date);
        $differenceTime = ($currenTime - $commentTime);

        if ($differenceTime < 60) {
            return '刚刚';
        } elseif ($differenceTime < (60 * 60)) {
            return floor(($differenceTime / 60)) . '分钟前';
        } elseif ($differenceTime < (60 * 60 * 24)) {
            return floor(($differenceTime / 60 / 60)) . '小时前';
        } else {
            return $date;
        }

    }

    /**
     * Make an HTTP request
     *
     * @return string API results
     * @ignore
     */
    public static function requestUrl($url, $method, $postfields = NULL, $headers = null, $timeout = 60)
    {
        $curl = curl_init();

        //curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 15);
        curl_setopt($curl, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_HEADER, FALSE);
        curl_setopt($curl, CURLOPT_USERAGENT, isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : 'Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.1.2) Gecko/20090729 Firefox/3.5.2 GTB5');
        $headers[] = 'API-RemoteIP: ' . self::getip();
        //$headers[] = 'Content-Type: application/vnd.ehking-v1.0+json';
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        switch ($method) {
            //POSTJSON
            case 'POSTJSON' :
                curl_setopt($curl, CURLOPT_POST, TRUE);
                if ($postfields) {
                    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                            'Content-Type: application/json',
                            'Content-Length: ' . strlen($postfields))
                    );
                }
                curl_setopt($curl, CURLOPT_POSTFIELDS, $postfields);
                break;

            case 'POST' :
                curl_setopt($curl, CURLOPT_POST, TRUE);
                if ($postfields) {
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $postfields);
                }
                break;
            case 'PUT' :
                curl_setopt($curl, CURLOPT_PUT, true);

                if ($postfields) {
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $postfields);
                }
                break;
            case 'DELETE' :
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'DELETE');

                if ($postfields) {
                    $url = "{$url}?{$postfields}";
                }
                break;
        }


        curl_setopt($curl, CURLOPT_URL, $url);

        curl_setopt($curl, CURLINFO_HEADER_OUT, TRUE);
        curl_setopt($curl, CURLOPT_REFERER, '//www.kaluli.com');

        if (substr($url, 0, 8) == 'https://') {
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        }

        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }

    /**
     * 获取客户端IP
     * @return int(10)
     */
    public static function getip($ip2long = FALSE)
    {
        if (!empty($_SERVER["REMOTE_ADDR"]))
            $ip = $_SERVER["REMOTE_ADDR"];
        else
            $ip = NULL;
        if ($ip && $ip2long)
            $ip = bindec(decbin(ip2long($ip)));

        return $ip;
    }

    //用mysql 加锁
    public static function getLock($lockStr, $timeOut)
    {
        $con = Doctrine_Manager::getInstance()->getConnection('kaluli');
        $query = 'SELECT GET_LOCK(\'' . addslashes($lockStr) . '\',' . $timeOut . ') as status';
        return $con->fetchAssoc($query);
    }

    public static function releaseLock($lockStr)
    {
        $con = Doctrine_Manager::getInstance()->getConnection('kaluli');
        $query = 'SELECT RELEASE_LOCK("' . addslashes($lockStr) . '")';
        return $con->fetchAssoc($query);
    }

    public static function is_crawler()
    {
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
    public static function getQiNiuProxyPath($url)
    {
        if (preg_match('/images-amazon.com/', $url)) {
            $url = str_replace("_SS100_.jpg", "", $url);
            $url = $url . '_SS500_.jpg';
        }
        $base64 = base64_encode($url);
        $base64 = str_replace('+', '-', $base64);
        $base64 = str_replace('/', '_', $base64);
        $base64 = str_replace('=', '', $base64);
        return 'http://shihuoproxy.hupucdn.com/' . $base64;
    }

    //判断2个二维数组某个子元素是否相等
    public static function checkArrSame($arr, $val)
    {
        foreach ($arr as $k => $v) {
            if ($v != $val) return false;
        }
        return true;
    }


    //测试日志函数     KaluliFun::dev_log('1---进入首次下单子函数','/gwyy/dev.log');
    public static function dev_log($str = '', $file_name = NULL)
    {
        if (empty($file_name)) return false;
        file_put_contents($file_name, var_export($str, true) . PHP_EOL, FILE_APPEND);
    }


    /**
     *
     * 获取快递费
     * @param $type 2 表示顺丰 1表示申通 3表示EMS 4表示圆通
     * @param $region_id 省id
     * @param $weight 重量 单位KG
     *
     */
    public static function getExpressFee($type, $region_id, $weight)
    {
        if ($type == 3) {//EMS统一20元
            $fee = 20;
        } elseif ($type == 2) {//顺丰
            $fee = 20;//首重统一20元
            if ($weight > 1) {//续重
                if ($region_id == 6 || $region_id == 29) {//广东和新疆9元
                    $fee += ($weight - 1) * 9;
                } elseif ($region_id == 12) {//黑龙江11元
                    $fee += ($weight - 1) * 11;
                } else {//其他地方5元
                    $fee += ($weight - 1) * 5;
                }
            }
        } elseif ($type == 4) {//圆通
            if ($region_id == 16 || $region_id == 25 || $region_id == 31 || $region_id == 3) {//江浙沪皖 6元
                $fee = 6;
                if ($weight > 1) {//续重
                    $fee += ($weight - 1) * 1;
                }
            } elseif ($region_id == 28 || $region_id == 29) {//西藏 新疆 20
                $fee = 20;
                if ($weight > 1) {//续重
                    $fee += ($weight - 1) * 16;
                }
            } elseif ($region_id == 22 || $region_id == 17 || $region_id == 6 || $region_id == 4 || $region_id == 2 || $region_id == 27 || $region_id == 10 || $region_id == 11 || $region_id == 13 || $region_id == 14) {//山东 江西 广东 福建 北京 天津 河北 河南 湖南 湖北 10元
                $fee = 10;
                if ($weight > 1) {//续重
                    $fee += ($weight - 1) * 6;
                }
            } else {
                $fee = 12;
                if ($weight > 1) {//续重
                    $fee += ($weight - 1) * 8;
                }
            }
        } elseif ($type == 1) {//申通
            if ($region_id == 16 || $region_id == 25 || $region_id == 31) {//江浙沪6元
                $fee = 6;
            } elseif ($region_id == 12 || $region_id == 15 || $region_id == 18 || $region_id == 2 || $region_id == 5 || $region_id == 19) {//东北三省 京甘内 15
                $fee = 15;
            } elseif ($region_id == 28 || $region_id == 29) {//西藏 新疆 20
                $fee = 20;
            } elseif ($region_id == 12 || $region_id == 15 || $region_id == 18 || $region_id == 2 || $region_id == 5 || $region_id == 19) {//云南 贵州 四川 广西 重庆 陕西 12
                $fee = 12;
            } else {
                $fee = 10;
            }
        }
        if ($region_id == 33 || $region_id == 34 || $region_id == 35) {//香港 澳门 台湾 50
            $fee = 50;
        }
        return $fee;
    }

    /**
     * 获取优惠券的显示顺序
     * @param $data
     * @param $total_price
     */
    public static function getCouponSortList($data, $total_price)
    {
        if (empty($data)) return false;
        $enable_list = $disable_list = array();
        $enable_amount = $disable_amount = array();
        foreach ($data as $k => $v) {
            if ($v['card_limit']) {//是否满足满减条件
                if ($total_price >= $v['card_limit_parse']['order_money']) {
                    $v['flag'] = true;
                    $enable_amount[]['amount'] = $v['amount'];
                    $enable_list[] = $v;
                } else {
                    $v['flag'] = false;
                    $disable_amount[]['amount'] = $v['amount'];
                    $disable_list[] = $v;
                }
            } else {
                $v['flag'] = true;
                $enable_amount[]['amount'] = $v['amount'];
                $enable_list[] = $v;
            }
        }
        if (count($enable_list) > 0) array_multisort($enable_amount, SORT_DESC, $enable_list);
        if (count($disable_list) > 0) array_multisort($disable_amount, SORT_ASC, $disable_list);
        return array_merge($enable_list, $disable_list);
    }


    /**
     *
     * kaluli发送topic消息
     * @param $routing_key
     * @param array $message
     * @param $queue
     * @return bool
     */
    public static function sendMqMessage($routing_key, $message = array(), $queue, $delayed = 2000)
    {
        if (!$routing_key || empty($message)) return false;
        try {
            $amqpParams = sfConfig::get("app_mabbitmq_options_kaluli");
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
            kaluliLog::info("sendMessage",$message);
        } catch (Exception $e) {
            //放入redis临时队列
            $data = array(
                'routing_key' => $routing_key,
                'message' => $message,
                'queue' => $queue,
                'delayed' => $delayed,
                'created_at' => time(),
            );
            $redis = new tradeUpdateRedisList('kaluli_mq_temporary_message');
            $redis->addList($data);
            kaluliLog::info("sendMessage",$e);

        }
        return true;
    }


    public static function generateCaptcha()
    {

        $data = array(
            'img_width' => 80,
            'expiration' => 1800,
        );

        $s = self::create_captcha($data);

        return array(
            'word' => $s['word'],
        );
    }


    public static function create_captcha($data = '', $img_path = '', $img_url = '', $font_path = '')
    {
        $defaults = array('word' => '', 'img_path' => '', 'img_url' => '', 'img_width' => '70', 'img_height' => '30', 'font_path' => '', 'expiration' => 7200);

        foreach ($defaults as $key => $val) {
            if (!is_array($data)) {
                if (!isset($$key) OR $$key == '') {
                    $$key = $val;
                }
            } else {
                $$key = (!isset($data[$key])) ? $val : $data[$key];
            }
        }


        if (!extension_loaded('gd')) {
            return FALSE;
        }


        if ($word == '') {
            $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

            $str = '';
            for ($i = 0; $i < 4; $i++) {
                $str .= substr($pool, mt_rand(0, strlen($pool) - 1), 1);
            }

            $word = $str;
        }

        $length = strlen($word);
        $angle = ($length >= 6) ? rand(-($length - 6), ($length - 6)) : 0;

        $x_axis = rand(6, (360 / $length) - 16);
        $y_axis = ($angle >= 0) ? rand($img_height, $img_width) : rand(6, $img_height);


        // PHP.net recommends imagecreatetruecolor(), but it isn't always available
        if (function_exists('imagecreatetruecolor')) {
            $im = imagecreatetruecolor($img_width, $img_height);
        } else {
            $im = imagecreate($img_width, $img_height);
        }


        $bg_color = imagecolorallocate($im, 255, 255, 255);
        $border_color = imagecolorallocate($im, 153, 102, 102);
        $text_color = imagecolorallocate($im, 0, 0, 255);
        $grid_color = imagecolorallocate($im, 255, 255, 255);
        ImageFilledRectangle($im, 0, 0, $img_width, $img_height, $bg_color);


        $use_font = ($font_path != '' AND file_exists($font_path) AND function_exists('imagettftext')) ? TRUE : FALSE;

        if ($use_font == FALSE) {
            $font_size = 5;
            //   $x = rand(0, $img_width/($length/3));
            $x = rand(0, $img_width / ($length));
            $y = 0;
        } else {
            $font_size = 16;
            $x = rand(0, $img_width / ($length / 1.5));
            $y = $font_size + 2;
        }

        for ($i = 0; $i < strlen($word); $i++) {
            if ($use_font == FALSE) {
                $y = rand(0, $img_height / 2);

                imagestring($im, $font_size, $x, $y, substr($word, $i, 1), $text_color);
                $x += ($font_size * 2);
            } else {
                $y = rand($img_height / 2, $img_height - 3);
                imagettftext($im, $font_size, $angle, $x, $y, $text_color, $font_path, substr($word, $i, 1));
                $x += $font_size;
            }
        }


        imagerectangle($im, 0, 0, $img_width - 1, $img_height - 1, $border_color);

        ImagePng($im, null);
        ImageDestroy($im);
        return array(
            'word' => $word,
        );
    }

    public static function checkWords($words)
    {
        $redis = sfContext::getInstance()->getDatabaseConnection('kaluliRedis');
        $redis->select(10);
        if ($redis->scontains($words, 1)) {
            return false;
        }
        return true;
    }

    public static function checkWord($word)
    {
        $word = preg_replace("/\s/", "", strip_tags($word));
        if (empty($word)) {
            return ["code" => 0, "msg" => "内容不能为空，请重新填写"];
        }
        $words = new badwords();
        $filter = $words->checkWord($word, 'kaluli');

        if (!empty($filter)) {
            return ["code" => 0, "msg" => "内容含有禁用词，请重新填写"];
        } else {
            
            if (mb_strlen($word) < 4 || mb_strlen($word) > 20) {
                return ["code" => 0, "msg" => "4-20个字符，可由中英文、数字、“_”、“-”组成"];
            } else {
                return ["code" => 1, "data" => $word];
            }


        }

    }

    /**
     * 记录用户登录日志
     * @param $userId
     * @param $status
     * @param int $type
     */
    public static function saveUserLoginLog($userId, $status, $type = 1)
    {
        $logInfo = array();
        if (!empty($userId)) {
            $logInfo['userId'] = $userId;
            $logInfo['loginTime'] = time();
            $logInfo['loginSite'] = $type;
            $logInfo['loginIp'] = $_SERVER["REMOTE_ADDR"];
            $logInfo['ctTime'] = time();
            $logInfo['loginStatus'] = $status;
            //调用接口记录日志
            $url = sfConfig::get("app_log_srv");
            self::requestUrl($url, "POSTJSON", json_encode($logInfo));
        }
    }

    /**
     * 二维数组排序
     * @param $arrays
     * @param $sort_key
     * @param int $sort_order
     * @param int $sort_type
     * @return array|bool
     */
    public static function my_sort($arrays, $sort_key, $sort_order = SORT_ASC, $sort_type = SORT_NUMERIC)
    {
        if (is_array($arrays)) {
            foreach ($arrays as $array) {
                if (is_array($array)) {
                    $key_arrays[] = $array[$sort_key];
                } else {
                    return false;
                }
            }
        } else {
            return false;
        }
        array_multisort($key_arrays, $sort_order, $sort_type, $arrays);
        return $arrays;
    }

    public static function invoke_fraud_api(array $params, $timeout = 500, $connection_timeout = 500) {
        $api_url = (sfConfig::get('sf_environment')!='dev')?"https://apitest.tongdun.cn/riskService/v1.1":"https://api.tongdun.cn/riskService/v1.1";

        $options = array(
            CURLOPT_POST => 1,            // 请求方式为POST
            CURLOPT_URL => $api_url,      // 请求URL
            CURLOPT_RETURNTRANSFER => 1,  // 获取请求结果
            // -----------请确保启用以下两行配置------------
            CURLOPT_SSL_VERIFYPEER => 1,  // 验证证书
            CURLOPT_SSL_VERIFYHOST => 2,  // 验证主机名
            // -----------否则会存在被窃听的风险------------
            CURLOPT_POSTFIELDS => http_build_query($params) // 注入接口参数
        );
        if (defined("CURLOPT_TIMEOUT_MS")) {
            $options[CURLOPT_NOSIGNAL] = 1;
            $options[CURLOPT_TIMEOUT_MS] = $timeout;
        } else {
            $options[CURLOPT_TIMEOUT] = ceil($timeout / 1000);
        }
        if (defined("CURLOPT_CONNECTTIMEOUT_MS")) {
            $options[CURLOPT_CONNECTTIMEOUT_MS] = $connection_timeout;
        } else {
            $options[CURLOPT_CONNECTTIMEOUT] = ceil($connection_timeout / 1000);
        }
        $ch = curl_init();
        curl_setopt_array($ch, $options);
        if(!($response = curl_exec($ch))) {
            // 错误处理，按照同盾接口格式fake调用结果
            return array(
                "success" => "false",
                "reason_code" => "000:调用API时发生错误[".curl_error($ch)."]"
            );
        }
        curl_close($ch);
        return json_decode($response, true);
    }

    
 
    public static function _formatContent($content)
    {

        $regex = "/.*?<a.*?target=\"_blank\">\s*(.*?)\s*<\/a>.*?/";
        preg_match_all($regex, $content, $matches);
        if (preg_match_all($regex, $content, $matches)) {
            $itemTable = KaluliItemTable::getInstance();

            if ($matches[1]) {
                foreach ($matches[1] as $val) {
                    if (is_numeric($val)) {

                        $ids = [$val];

                        $products = $itemTable::getMessageOff(array('ids' => $ids, 'select' => 'id,title,pic,sell_point,discount_price,price,intro'));
                        $newData = [];
                        foreach ($products as $v) {

                            $newData['id'] = $v['id'];
                            $newData['status'] = $v['status'];
                            $newData['title'] = $v['title'];
                            $newData['pic'] = isset($v['pic']) ? $v['pic'] : '';
                            $newData['sell_point'] = $v['sell_point'];
                            $newData['intro'] = $v['intro'];
                            $newData['price'] = $v['price'];
                            $newData['discount_price'] = $v['discount_price'];
                        }
                        //X元购逻辑判断
                        if (!empty($newData)) {

                            $serviceRequest = new kaluliServiceClient();
                            $serviceRequest->setVersion("1.0");
                            $serviceRequest->setMethod("activity.CheckActivity");
                            $serviceRequest->setApiParam("itemId", $newData['id']);
                            $response = $serviceRequest->execute();
                            if ($response->getStatusCode() == 203) {
                                $itemActivity = $response->getValue("itemActivity");
                                $newData['activityPrice'] = $itemActivity['price'];
                            }
                            //折扣
                            $redis = sfContext::getInstance()->getDatabaseConnection('kaluliRedis');
                            $redis->select(1);
                            $activity_discount = [];
                            $json = $redis->get('kaluli_marketing_activity_' . $newData['id']);
                            $activity_discount = unserialize($json);
                            if (!empty($activity_discount) && isset($activity_discount['detail']['discount_rate']) && intval($activity_discount['detail']['mode']) > 2) {
                                $discount_rate = $activity_discount['detail']['discount_rate'];
                                $newData['activityPrice'] = $newData['discount_price'] * $discount_rate / 10;

                            }
                            //下架后商品隐藏链接
                            $show_status = $ad_img = $bg_color = $font_color = '';
                            if ($newData['status'] == 4) {
                                $show_status = 'display:none;';
                                $bg_color = "background-color: #f7f7f7;";
                                $font_color = 'color: #999';
                            } else {
                                $ad_img = "//kaluli.hoopchina.com.cn/images/kaluli/cms/icon_advertisement.png";

                            }
                            $html = '<div class="recommend-goods" style="margin: 20px 0;background-color: #ffffff;border: 1px solid #e5e5e5; height: 212px; position:relative">';
                            $html .= '<div style="position: absolute; left: 0;bottom: 0; width:36px; text-align:center ; height: 24px; z-index:20; font-size: 12px; color:#cccccc;">广告</div>';
                            $html .= '<div class="recommend-left" style="display:inline-block; width:212px; height:212px; margin-left:12px; background-color:red;">';
                            $html .= '<img style="width: 212px;height: 212px;" src="'.$newData['pic'].'">';
                            $html .= '</div>';
                            $html .= '<div class="recommend-right" style="display:inline-block; margin-left:12px; padding-top:40px; height:212px;vertical-align:middle;">';
                            $html .= '<p><span style="font-size:14px; color:#FFFFFF; font-wight: bold; background-color: #fa6731; padding:0 3px 0 3px;">推荐</span></p >';
                            $html .= '<p style="font-size:18px; color:#333333; font-wight: bold; height:32px; line-height:32px;max-width:530px; overflow:hidden; text-overflow:ellipsis; display:-webkit-box; -webkit-box-orient:vertical; -webkit-line-clamp:1; ">'.$newData['title'].'</p >';
                            $html .= '<p style="font-size:16px; color:#666666; height:40px; line-height:20px; max-width:530px; overflow:hidden; text-overflow:ellipsis; display:-webkit-box; -webkit-box-orient:vertical; -webkit-line-clamp:2;">' . $newData['intro'] . '</p >';
                            $html .= '<p style="font-size:18px; color:#fa6731; position: absolute; bottom: 24px;">';
                            if (isset($newData['activityPrice'])) {
                                $newData['discount'] = number_format(($newData['activityPrice'] / $newData['discount_price']) * 10, 1, '.', '');
                                $html .= '<span class="" style="">卡路里价 :￥' . $newData['activityPrice'] . '</span><span class="" style="border: 1px solid #fa6731; border-radius: 6px; width:100px; height:100px; padding: 0 5px 0 5px; margin: 0 10px; font-size: 15px">' . $newData['discount'] . '折</span><span style="text-decoration:line-through; color:#999999; font-size:16px">￥' . $newData['discount_price'] . '</span></p></div>';
                            } else {
                                $newData['discount'] = number_format(($newData['discount_price'] / $newData['discount_price']) * 10, 1, '.', '');
                                $html .= '<span class="" style="">卡路里价 :￥' . $newData['discount_price'] . '</span></p></div>';
                            }
                            $html .= '<div style="position: absolute; right: 36px;bottom: 24px; color:#cccccc; font-size:16px">';
                            $html .= '<a href=" " target="_blank" onmouseover="this.style.cssText=\'color:fd6732; text-decoration:none;\'" onmouseout="this.style.cssText=\'color:#b3b3b3;text-decoration:none\'" style="text-decoration: none; color:#cccccc;">查看详情>></a></div></div>';
                            $content = str_replace("<a href=\"http://$val\" target=\"_blank\">$val</a>", $html, $content);
                            return $content;
                        }
                    }
                }
            }
        }

        return $content;
    }

    /**
     * 根据itemId获取市场价价格
     * @param $items
     * @return mixed
     */
    public static function getMarketingPrice($items) {
        $redis = sfContext::getInstance()->getDatabaseConnection('kaluliRedis');
        $redis->select(1);
        foreach($items as $key => &$item) {
            //折扣

            $activity_discount = [];
            $json = $redis->get('kaluli_marketing_activity_' . $item['id']);
            $activity_discount = unserialize($json);
            if(!empty($activity_discount) && isset($activity_discount['detail']['discount_rate']) && intval($activity_discount['detail']['mode']) > 2){
                $item['discount_rate'] = $activity_discount['detail']['discount_rate'];
            }
        }
        return $items;
    }


    public static function getNewUserTask($forShow = 0) {
        $redis = sfContext::getInstance()->getDatabaseConnection('kaluliRedis');
        $redis->select(10);
        //分组任务
        $redisKey = "kaluli.newUserTask";
        $data = json_decode($redis->get($redisKey),true);
        if(empty($data)) {
            return array();
        }
        $userTask = array();
        $newDataArr = array();
        for($i=0 ; $i < 4; $i++) {
            $newData['title'] = $data['title'.$i];
            $newData['desc']  = $data['desc'.$i];
            $newData['pic']   = $data['pic'.$i];
            $newData['num']   = $data['num'.$i];
            $newData['taskType'] = $data['taskType'.$i];
            $newData['taskContent'] = $data['taskContent'.$i];
            $newData['taskSection'] = $data['taskSection'.$i];
            $newData['recordId'] = $data['recordId'.$i];
            $newData['task'] = $i;
            $newDataArr[] = $newData;
        }
        if($forShow == 1) {
            $newDataArr['banner'] = $data['banner'];
            return $newDataArr;
        }
        //处理成任务格式
        foreach($newDataArr as $k => $v) {
            $userTask[$v['taskSection']][] = $v;
        }

        return $userTask;
        
    }



    //商品是否满足某一种活动
    public static function checkCartActivity($activity, $goods_info) {
        if (empty($activity) || empty($goods_info)) return false;
        //先写满折活动
        $returnInfo = array();
        foreach($activity as $ak => $av) {
            $flag = 0; //活动满足标识
            $total_number = 0;
            foreach ($goods_info as $k => $v) {
                foreach ($v['activity'] as $kk => $vv) {
                    if ($vv['id'] == $av['id']) {//识货满减比例
                        $total_number += $v['number'];
                        if ($total_number >= $av['attr1']) {
                            $flag = 1;
                        }
                    }
                }
            }
            $info['type'] = $av['id'];
            if($flag == 1) {
                $info['info'] = "已满足";
            } else {
                $info['info'] = "再购".($av['attr1']-$total_number)."件即享";

            }
            $returnInfo[] = $info;
        }
        return $returnInfo;


    }
    

}