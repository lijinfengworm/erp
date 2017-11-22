<?php

/**
 * 识货公共函数类
 * About: 梁天
 */
class FunBase
{

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
     * 对指定的二维数组 key 排序
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
        return str_replace(array('"', '&#', "\\", "<", ">"), '', $url);
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

    public static function redirect($url, $time = 0, $msg = '')
    {
        //多行URL地址支持
        $url = str_replace(array("\n", "\r"), '', $url);
        if (empty($msg))
            $msg = "系统将在{$time}秒之后自动跳转到{$url}！";
        if (!headers_sent()) {
            // redirect
            if (0 === $time) {
                header('Location: ' . $url);
            } else {
                header("refresh:{$time};url={$url}");
                echo($msg);
            }
            exit();
        } else {
            $str = "<meta http-equiv='Refresh' content='{$time};URL={$url}'>";
            if ($time != 0)
                $str .= $msg;
            exit($str);
        }
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
        if ($is_echo) $price = sprintf("￥%s元", $price);
        return $price;
    }


    /**
     * 简洁版的AJAXreturn
     */
    public static function ajaxReturn($data, $type = 'json', $state_flag = true)
    {
        $state_flag && ($data['state'] = $data['status'] ? "success" : "fail");
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

    //获取用户IP (new)
    public static function getIp()
    {
        if (getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')) {
            $PHP_IP = getenv('HTTP_CLIENT_IP');
        } elseif (getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
            $PHP_IP = getenv('HTTP_X_FORWARDED_FOR');
        } elseif (getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
            $PHP_IP = getenv('REMOTE_ADDR');
        } elseif (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
            $PHP_IP = $_SERVER['REMOTE_ADDR'];
        }
        preg_match("/[\d\.]{7,15}/", $PHP_IP, $ipmatches);
        $PHP_IP = $ipmatches[0] ? $ipmatches[0] : 'unknown';
        return $PHP_IP;
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
        echo '<pre style="background:#fff;-moz-border-radius:8px;width:99%;overflow:auto;margin:0;padding:1px 6px 6px;font-size:15px;border:1px solid #BBB;">';
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
     * 简易抽奖函数
     * $priceData  抽奖数据 比如
     * $a = array(
     * 1=>5,
     * 2=>45,
     * 3=>50
     * );
     * $max  ＝ 100  暂时只支持%必中
     * $is_return  返回key1  还是value2
     */
    public static function simplePrize($priceData, $max = 100, $is_return = 1)
    {
        $_rand = mt_rand(6789, 23456);
        $_i = 1;
        $totalArr = $tmpArr = array();
        foreach ($priceData as $key => $value) {
            $tmpArr[$_i] = $value;
            $totalArr[$_i] = $key;
            $_i++;
        }
        $tmpArr[0] = 0;
        foreach ($tmpArr as $key => $value) {
            if ($key == 1) $tmpArr[$key] += $_rand;
            if ($key > 0) $tmpArr[$key] += $tmpArr[$key - 1];
        }
        $seed = $_rand + mt_rand(1, $max);
        for ($i = 1; $i < count($tmpArr); $i++) {
            if ($tmpArr[$i - 1] < $seed && $seed <= $tmpArr[$i]) {
                if ($is_return == 1) return $totalArr[$i];
                if ($is_return == 2) return $priceData[$totalArr[$i]];
            }
        }
        return false;
    }


    /**
     * 网络图片转七牛
     * @param $img_name  图片路径
     * @param string $new_path 七牛新路径
     * @param string $thumbnail 是否缩略
     * @return null|string
     */
    public static function setCrawlerImg($img_name, $new_path = 'images/', $thumbnail = '')
    {
        if (!is_array($img_name)) {
            $img_attr = array($img_name);
        } else {
            $img_attr = $img_name;
        }

        $_imgs = array();
        foreach ($img_attr as $img_attr_v) {
            //天猫图片 前面没有 http:  要加上
            if (preg_match("/^\/\/.*/", $img_attr_v)) $img_attr_v = 'http:' . $img_attr_v;

            $extend = pathinfo($img_attr_v);
            $_filename = $new_path . substr(md5($extend['filename']), 0, 8) . date('His');
            $_suffix = strtolower($extend["extension"]);
            $qiniu = new tradeQiNiu();
            $qiniu_img = $qiniu->uploadRemoteImage($img_attr_v, $_filename . '.' . $_suffix);
            if (empty($qiniu_img)) {
                $qiniu_img = null;
            }
            //是否要缩略
            if (!empty($thumbnail)) {
                $qiniu_img = $qiniu_img . '?imageView2/1/' . $thumbnail;
            }
            $_imgs[] = $qiniu_img;
        }

        if (!is_array($img_name)) {
            $_imgs = current($_imgs);
        }
        return $_imgs;
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
     * 权限转换指定字符串
     */
    public static function AccessItemToFormat($arr, $type, $default = '')
    {
        $_result = array();
        $_html = $_selected = '';
        if ($type == 'string') {
            foreach ($arr as $k => $v) {
                $_result[] = $v['value'] . '=' . $v['text'];
            }
            return implode("|", $_result);
        } else if ($type == 'html_select') {
            foreach ($arr as $k => $v) {
                if (isset($default)) {
                    if ($default == $v['value']) $_selected = 'selected="selected"';
                }
                $_html .= '<option  ' . $_selected . ' value="' . $v['value'] . '">' . $v['text'] . '</option>';
                $_selected = '';
            }
            return $_html;
        }
        return '';
    }


    //utf-8截取
    public static function getsubstrutf8($string, $start = 0, $sublen, $append = true)
    {
        $pa = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/";
        preg_match_all($pa, $string, $t_string);
        if (count($t_string[0]) - $start > $sublen && $append == true) {
            return join('', array_slice($t_string[0], $start, $sublen)) . "...";
        } else {
            return join('', array_slice($t_string[0], $start, $sublen));
        }
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
     * 人性化时间格式
     * @param $commentTime  指定时间
     * @param $currenTime   当前时间  time()
     * @param string $type before  之前   after 之后
     * @return null|string   根据type返回具体的文字
     * 梁天  2015-05-13
     */
    public static function humanizeTime($commentTime, $currenTime, $type = 'before')
    {
        $_arr['before'] = array(0 => '刚刚', 1 => '分钟前', 2 => '小时前', 3 => '天前');
        $_arr['after'] = array(0 => '刚刚', 1 => '分钟后', 2 => '小时后', 3 => '天后');
        if ($type == 'before') {
            $differenceTime = (int)($currenTime - $commentTime);
        } else if ($type == 'after') {
            $differenceTime = (int)($commentTime - $currenTime);
        }
        if ($differenceTime < 60) {
            return $_arr[$type][0];
        } elseif ($differenceTime < (60 * 60)) {
            return floor(($differenceTime / 60)) . $_arr[$type][1];
        } elseif ($differenceTime < (60 * 60 * 24)) {
            return floor(($differenceTime / 60 / 60)) . $_arr[$type][2];
        } else {
            return floor($differenceTime / (60 * 60 * 24)) . $_arr[$type][3];
        }
        return NULL;
    }

    /*
     *hash
     **/
    public static function hashCode($s)
    {
        $len = strlen($s);
        $hash = 0;
        for ($i = 0; $i < $len; $i++) {
            //一定要转成整型
            $hash = (int)($hash * 31 + ord($s[$i]));
            //64bit下判断符号位
            if (($hash & 0x80000000) == 0) {
                //正数取前31位即可
                $hash &= 0x7fffffff;
            } else {
                //负数取前31位后要根据最小负数值转换下
                $hash = ($hash & 0x7fffffff) - 2147483648;
            }
        }
        return $hash;
    }

    /*
     * 是否是json
    */
    public static function is_json($str)
    {
        return !is_null(json_decode($str));
    }

    /*
     *旧分类转换成新分类
     *@$channel 频道
     **/
    public static function typeOldToNew($id, $type = 'children', $channel = 'all')
    {
        if ($channel == 'all') {
            $compare_root = array(
                1 => 1,
                2 => 2,
                3 => 3,
                4 => 4,
                5 => 5,
                6 => 4,
                7 => 7,
            );
        } else if ($channel == 'daigou') {
            $compare_root = array(
                1 => 1,
                2 => 2,
                3 => 3,
                4 => 4,
                5 => 5,
                7 => 7,
                49 => 2,
                50 => 50,
                51 => 51,
                52 => 7,
                53 => 7,
            );
        }

        if ($channel == 'all') {
            $compare_children = array();
        } else if ($channel == 'daigou') {
            $compare_children = array(
                '8' => '1:8',
                '9' => '1:9',
                '10' => '1:10',
                '11' => '1:11',
                '12' => '1:12',
                '13' => '2:13',
                '14' => '2:14',
                '15' => '7:48',
                '16' => '3:16',
                '17' => '3:16',
                '18' => '3:16',
                '19' => '3:16',
                '20' => '3:20',
                '21' => '3:16',
                '22' => '3:16',
                '23' => '3:23',
                '24' => '3:16',
                '26' => '4:38',
                '27' => '4:27', //厨房用具
                '28' => '6:70', //+个人护理
                '29' => '5:29',
                '31' => '5:31', //基础营养
                '32' => '5:31',
                '33' => '5:31',
                '34' => '5:31',
                '37' => '4:27',
                '38' => '4:38',
                '39' => '4:39',
                '41' => '7:48',
                '42' => '7:42',
                '43' => '51:43',
                '44' => '7:44', //办公文具
                '45' => '7:48',
                '46' => '7:48',
                '47' => '2:47',
                '48' => '7:48',
                '54' => '2:54',
                '55' => '7:48',
                '56' => '50:56', //精品男包
                '57' => '50:57', //潮流女包
                '58' => '50:58',
                '59' => '50:59',
                '60' => '50:60', //公文包
                '61' => '51:61',
                '62' => '7:48',
                '63' => '7:48',
                '64' => ' 51:64',
                '65' => '51:65', //眼镜
                '66' => '5:31',
                '67' => '5:31',
            );
        }


        if ($type == 'root') {
            $compare = $compare_root;
        } else {
            $compare = $compare_children;
        }

        if (isset($compare[$id])) {
            return explode(':', $compare[$id]);
        } else {
            return false;
        }
    }

    // 计算中文字符串长度
    public static function utf8_strlen($str = null)
    {
        $count = 0;
        for ($i = 0; $i < strlen($str); $i++) {
            $value = ord($str[$i]);
            if ($value > 127) {
                $count++;
                if ($value >= 192 && $value <= 223) $i++;
                elseif ($value >= 224 && $value <= 239) $i = $i + 2;
                elseif ($value >= 240 && $value <= 247) $i = $i + 3;
                else die('Not a UTF-8 compatible string');
            }
            $count++;
        }
        return $count;
    }

    //base 64 for qiniu
    public static function base64ForQiniu($text)
    {
        $text = base64_encode($text);
        $text = str_replace(array('+', '/'), array('-', '_'), $text);

        return $text;
    }

    //根据频道获取 分类表
    public static function getMenuTable($channel = 'all')
    {
        if ($channel == 'daigou') {
            $menuTable = 'TrdDaigouMenuTable';
        } else {
            $menuTable = 'TrdMenuTable';
        }
        return $menuTable;
    }

    //地址栏 特殊字符转义
    public static function escapeSpecialCharacters($word, $flip = false)
    {
        $compare = array(
            "'" => "*",
        );

        if ($flip) {
            $compare = array_flip($compare);
        }

        return strtr($word, $compare);
    }

    /**
     * 用于pc，m站与百川openim关联
     */
    public static function relateOpenim($uid = '')
    {
        if (isset($_COOKIE['openim_code']) && !empty($_COOKIE['openim_code'])) {
            $openimCode = $_COOKIE['openim_code'];
        } else {
            $openimCode = FunBase::genRandomString(30);
            setcookie('openim_code', $openimCode, strtotime('2025-01-01'), '/', '.shihuo.cn');
        }
        $prefix = 'shihuo_'; // open_uid 前缀

        if ($uid) {
            $openimInfo = TrdOpenimInfoTable::getInstance()->createQuery()
                ->addWhere('user_id = ?', $uid)
                ->limit(1)
                ->fetchOne();
            if (empty($openimInfo)) {
                $openimInfo = TrdOpenimInfoTable::getInstance()->createQuery()
                    ->where('cookie_str = ?', $openimCode)
                    ->addWhere('user_id = 0')
                    ->limit(1)
                    ->fetchOne();
                if (empty($openimInfo)) {
                    $openPassword = 'sh_' . self::genRandomString(32);
                    $openPassword = md5($openPassword);

                    $db = Doctrine_Manager::getInstance()->getConnection('trade');
                    $db->beginTransaction();

                    $openimInfoData = new TrdOpenimInfo();
                    $openimInfoData->setCookieStr($openimCode);
                    $openimInfoData->setUserId($uid);
                    $openimInfoData->setOpenPassword($openPassword);
                    $openimInfoData->save();
                    $openUid = $prefix . $openimInfoData->getId();
                    $openimInfoData->setOpenUsername($openUid);
                    $openimInfoData->save();

                    $params[] = array(
                        'userid' => $openUid,
                        'password' => $openPassword
                    );
                    $c = new TaeShihuoTopClient();
                    $req = new OpenimUsersAddRequest();
                    $req->setUserinfos(json_encode($params));
                    $resp = $c->execute($req);
                    if (isset($resp->uid_succ->string) && $resp->uid_succ->string) {
                        $db->commit();
                    } else {
                        $db->rollback();
                        return array('status' => false, 'msg' => 'openim添加用户失败');
                    }
                } else {
                    $openimInfo->setUserId($uid);
                    $openimInfo->save();

                    $openUid = $openimInfo['open_username'];
                    $openPassword = $openimInfo['open_password'];
                }
            } else {
                $openUid = $openimInfo['open_username'];
                $openPassword = $openimInfo['open_password'];
            }
        } else {
            $openimInfo = TrdOpenimInfoTable::getInstance()->createQuery()
                ->where('cookie_str = ?', $openimCode)
                ->addWhere('user_id = 0')
                ->limit(1)
                ->fetchOne();
            if (empty($openimInfo)) {
                $openPassword = 'sh_' . self::genRandomString(32);
                $openPassword = md5($openPassword);

                $db = Doctrine_Manager::getInstance()->getConnection('trade');
                $db->beginTransaction();

                $openimInfoData = new TrdOpenimInfo();
                $openimInfoData->setCookieStr($openimCode);
                $openimInfoData->setOpenPassword($openPassword);
                $openimInfoData->save();
                $openUid = $prefix . $openimInfoData->getId();
                $openimInfoData->setOpenUsername($openUid);
                $openimInfoData->save();

                $params[] = array(
                    'userid' => $openUid,
                    'password' => $openPassword
                );
                $c = new TaeShihuoTopClient();
                $req = new OpenimUsersAddRequest();
                $req->setUserinfos(json_encode($params));
                $resp = $c->execute($req);
                if (isset($resp->uid_succ->string) && $resp->uid_succ->string) {
                    $db->commit();
                } else {
                    $db->rollback();
                    return array('status' => false, 'msg' => 'openim添加用户失败');
                }
            } else {
                $openUid = $openimInfo['open_username'];
                $openPassword = $openimInfo['open_password'];
            }
        }
        $groupId = 157701755;
        if ('dev' == sfConfig::get('sf_environment')) {
            $groupId = 157827988;
        }
        $res = array(
            'status' => true,
            'open_uid' => $openUid,
            'open_password' => $openPassword,
            'kefu_account' => '识货小伙计',
            'kefu_group' => $groupId
        );
        return $res;
    }

    //判断浏览器版本
    public static function checkBrowser()
    {
        $_returnArr = array();
        if (strpos(strtolower($_SERVER['HTTP_USER_AGENT']), strtolower('MicroMessenger')) !== false) {
            $_returnArr['name'] = 'weixin';
        } else if (strpos(strtolower($_SERVER['HTTP_USER_AGENT']), strtolower('shihuo')) !== false) {
            if (strpos(strtolower($_SERVER['HTTP_USER_AGENT']), strtolower('android')) !== false) {
                $_returnArr['type'] = 'android';
            }
            if (strpos(strtolower($_SERVER['HTTP_USER_AGENT']), strtolower('ios')) !== false) {
                $_returnArr['type'] = 'ios';
            }
            preg_match('/shihuo\/([\w]+\.[\w]+\.[\w]+)/', $_SERVER['HTTP_USER_AGENT'], $match_callback);
            $_returnArr['version'] = $match_callback[1];
            if (!empty($match_callback[1])) {
                $_version_number = explode('.', $match_callback[1]);
                $_returnArr['versionNumber'] = implode('', $_version_number);
            }
            $_returnArr['name'] = 'app';
        } else {
            $_returnArr['name'] = 'wap';
        }
        return $_returnArr;
    }

    # 卡路里
    public static function relateOpenimKaluli($uid = '')
    {
        if (isset($_COOKIE['openim_code']) && !empty($_COOKIE['openim_code'])) {
            $openimCode = $_COOKIE['openim_code'];
        } else {
            $openimCode = FunBase::genRandomString(30);
            setcookie('openim_code', $openimCode, strtotime('2025-01-01'), '/', '.kaluli.com');
        }
        $prefix = 'kaluli_'; // open_uid 前缀
        $appkey = sfConfig::get("app_kaluli_qianniu_appkey");
        $sercrt = sfConfig::get("app_kaluli_qianniu_sercrt");

        if ($uid) {
            $openimInfo = KllOpenimInfoTable::getInstance()->createQuery()
                ->addWhere('user_id = ?', $uid)
                ->limit(1)
                ->fetchOne();
            if (empty($openimInfo)) {
                $openimInfo = KllOpenimInfoTable::getInstance()->createQuery()
                    ->where('cookie_str = ?', $openimCode)
                    ->addWhere('user_id = 0')
                    ->limit(1)
                    ->fetchOne();
                if (empty($openimInfo)) {
                    $openPassword = 'sh_' . self::genRandomString(32);
                    $openPassword = md5($openPassword);

                    $db = Doctrine_Manager::getInstance()->getConnection('kaluli');
                    $db->beginTransaction();

                    $openimInfoData = new KllOpenimInfo();
                    $openimInfoData->setCookieStr($openimCode);
                    $openimInfoData->setUserId($uid);
                    $openimInfoData->setOpenPassword($openPassword);
                    $openimInfoData->save();
                    $openUid = $prefix . $openimInfoData->getId();
                    $openimInfoData->setOpenUsername($openUid);
                    $openimInfoData->save();

                    $params[] = array(
                        'userid' => $openUid,
                        'password' => $openPassword
                    );
                    $c = new TaeShihuoTopClient();
                    $c->appkey = $appkey;
                    $c->secretKey = $sercrt;
                    //todo  更换kaluli appkey
                    $req = new OpenimUsersAddRequest();
                    $req->setUserinfos(json_encode($params));
                    $resp = $c->execute($req);
                    if (isset($resp->uid_succ->string) && $resp->uid_succ->string) {
                        $db->commit();
                    } else {
                        $db->rollback();
                        return array('status' => false, 'msg' => 'openim添加用户失败');
                    }
                } else {
                    $openimInfo->setUserId($uid);
                    $openimInfo->save();

                    $openUid = $openimInfo['open_username'];
                    $openPassword = $openimInfo['open_password'];
                }
            } else {
                $openUid = $openimInfo['open_username'];
                $openPassword = $openimInfo['open_password'];
            }
        } else {
            $openimInfo = KllOpenimInfoTable::getInstance()->createQuery()
                ->where('cookie_str = ?', $openimCode)
                ->addWhere('user_id = 0')
                ->limit(1)
                ->fetchOne();
            if (empty($openimInfo)) {
                $openPassword = 'sh_' . self::genRandomString(32);
                $openPassword = md5($openPassword);

                $db = Doctrine_Manager::getInstance()->getConnection('kaluli');
                $db->beginTransaction();

                $openimInfoData = new KllOpenimInfo();
                $openimInfoData->setCookieStr($openimCode);
                $openimInfoData->setOpenPassword($openPassword);
                $openimInfoData->save();
                $openUid = $prefix . $openimInfoData->getId();
                $openimInfoData->setOpenUsername($openUid);
                $openimInfoData->save();

                $params[] = array(
                    'userid' => $openUid,
                    'password' => $openPassword
                );
                $c = new TaeShihuoTopClient();
                $c->appkey = $appkey;
                $c->secretKey = $sercrt;
                $req = new OpenimUsersAddRequest();
                $req->setUserinfos(json_encode($params));
                $resp = $c->execute($req);
                if (isset($resp->uid_succ->string) && $resp->uid_succ->string) {
                    $db->commit();
                } else {
                    $db->rollback();
                    return array('status' => false, 'msg' => 'openim添加用户失败');
                }
            } else {
                $openUid = $openimInfo['open_username'];
                $openPassword = $openimInfo['open_password'];
            }
        }

        $groupId = 158694802;
        if ('dev' == sfConfig::get('sf_environment')) {
            $groupId = 158699128;
        }
        $res = array(
            'status' => true,
            'open_uid' => $openUid,
            'open_password' => $openPassword,
            'kefu_account' => 'hzn520cd',
            'kefu_group' => $groupId
        );
        return $res;
    }

    public static function existsActivitySet($set_id, $goods_id)
    {
        $activity_set_filter_key = 'trade.activity.goods.filter.{set_id}';     //过滤id

        //导入集合redis key
        $redis = sfContext::getInstance()->getDatabaseConnection('tradeActivityRedis');
        $redis->select(5);
        $activity_set_filter_key = str_replace('{set_id}', $set_id, $activity_set_filter_key);
        $activity_set_filter = unserialize($redis->get($activity_set_filter_key));

        if ($activity_set_filter
            && !empty($activity_set_filter['filterData'])
            && !empty($activity_set_filter['filterSign'])
        ) {
            $filterData = str_replace('，', ',', $activity_set_filter['filterData']);
            $filterData = explode(',', rtrim($filterData, ','));

            $activity_intersect = array_intersect($filterData, array($goods_id));
            if ($activity_intersect) {
                return true;
            } else {
                return false;
            }

            return false;

        } else {
            return false;
        }
    }

    /*
    *是否是utf-8
    **/
    public static function check_utf8($str)
    {
        $len = strlen($str);
        for ($i = 0; $i < $len; $i++) {
            $c = ord($str[$i]);
            if ($c > 128) {
                if (($c > 247)) return false;
                elseif ($c > 239) $bytes = 4;
                elseif ($c > 223) $bytes = 3;
                elseif ($c > 191) $bytes = 2;
                else return false;
                if (($i + $bytes) > $len) return false;
                while ($bytes > 1) {
                    $i++;
                    $b = ord($str[$i]);
                    if ($b < 128 || $b > 191) return false;
                    $bytes--;
                }
            }
        }
        return true;
    }


    # 七牛图片另存外
    public static function cutGoodsPic($pic_url = null, $qiuniu_path = '')
    {
        if (empty($pic_url) || empty($qiuniu_path)) return '';
        $picInfo = getimagesize($pic_url);
        list($width, $height) = $picInfo;
        if (empty($width) || empty($height)) return '';

        if ($width > 800 && $height > 800 && ($width == $height || $height > $width)) {
            $newpic = $pic_url . '?imageView2/1/w/800';
        } elseif ($width > 800 && $height > 800 && $width > $height) {
            $newpic = $pic_url . '?imageView2/1/h/800';
        } elseif ($width > $height) {
            $newpic = $pic_url . '?imageView2/1/h/' . $height;
        } elseif ($height > $width) {
            $newpic = $pic_url . '?imageView2/1/w/' . $width;
        } else {
            return $pic_url;
        }

        if (!empty($newpic)) {
            # 另存为
            $config = sfConfig::get('app_trade_qiniu');
            $bucket = $config['bucket'];
            $secretKey = $config['secretKey'];
            $accessKey = $config['accessKey'];
            $hostUrl = $config['uploadHost'];
            $newUrl = str_replace('http://', '', $newpic) . '|saveas/' . FunBase::base64ForQiniu("{$bucket}:{$qiuniu_path}");
            $sign = FunBase::base64ForQiniu(hash_hmac('sha1', $newUrl, $secretKey, true));
            $final = $newUrl . '/sign/' . "{$accessKey}:{$sign}";
            $rs = json_decode(self::getcurl($final), true);

            if (!empty($rs['key'])) {
                return $hostUrl . '/' . $rs['key'];
            } else {
                return $newpic;
            }
        }

        return $pic_url;

    }

    public static function getcurl($url)
    {
        $ch = curl_init();
        $timeout = 50;
        // var_dump($url);exit;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $file_contents = curl_exec($ch);
        curl_close($ch);
        return $file_contents;
    }
    /**
     * 发送socket的请求
     */
    public static function getCurlXml($host, $port, $message){
        
        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        @socket_connect($socket, $host, $port);
     
        $num = 0;
        $length = strlen($message);
        do
        {
            $buffer = substr($message, $num);
            $ret = @socket_write($socket, $buffer);
            $num += $ret;
        } while ($num < $length);
     
        $ret = '';
        do
        {
            $buffer = @socket_read($socket, 1024, PHP_BINARY_READ);
            $ret .= $buffer;
        } while (strlen($buffer) == 1024);
     
        socket_close($socket);
     
        return $ret;

    }
    /**
     * 发送http的xml请求
     * @param  [type] $requestXML [description]
     * @return [type]             [description]
     */
    public static function sendRequest($requestXML)
    {
        $url = 'http://erp.kaluli.com/kaluli_api/getData';
        $header[] = "Content-type: text/xml";//定义content-type为xml
        $xml = '<xml>xmldata</xml>';//要发送的xml
        $ch = curl_init(); //初始化curl
        curl_setopt($ch, CURLOPT_URL, $url);//设置链接
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//设置是否返回信息
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);//设置HTTP头
        curl_setopt($ch, CURLOPT_POST, 1);//设置为POST方式
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);//POST数据
        $res = curl_exec($ch);//接收返回信息
        if(curl_errno($ch)){//出错则显示错误信息
            print curl_error($ch);
        }
        curl_close($ch); //关闭curl链接
        return  $res;//显示返回信息
    }
    /**
     * 接收 socket的请求
     * @param  [type] $host [ip]
     * @param  [type] $port [端口]
     */
    public static function getSocketData($host, $port){
        set_time_limit( 0 );
        ob_implicit_flush();
        $socket = socket_create( AF_INET, SOCK_STREAM, SOL_TCP );
        socket_bind( $socket, $host, $port );
        socket_listen($socket);
        $acpt=socket_accept($socket);
        $hear = '';
        while ( $acpt ) {
            $words=fgets(STDIN);
            socket_write($acpt,$words);
            $hear=socket_read($acpt,1024);
            
            if("bye\r\n"==$hear){
                socket_shutdown($acpt);
                break;
            }
            usleep( 1000 );
        }
        socket_close($socket);
        return $hear;
    }   


    public static function formalUrlParams($url)
    {
        $data = array();
        $parameter = explode('&', end(explode('?', $url)));
        foreach ($parameter as $val) {
            $tmp = explode('=', $val);
            $data[$tmp[0]] = $tmp[1];
        }
        return $data;
    }

    /**
     * 加密
     */
    public static function encrypt($code, $key)
    {
        return base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($key), $code, MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND)));
    }

    /**
     * 解密
     */
    public static function decrypt($code, $key)
    {
        return mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($key), base64_decode($code), MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND));
    }

    public static function check_info($data)
    {
        $redis = sfContext::getInstance()->getDatabaseConnection('kaluliRedis');
        $redis->select(10);
        $index = -1;
        $result = array();
        foreach ($data as $k => $v) {
            $index++;
            if ($index % 10 == 0) {
                $p = intval(substr($k, -1));

                $tmp['ID'] = $data['ID' . $p];
                $tmp['price'] = $data['price' . $p];
                $tmp['or_price'] = $data['or_price' . $p];
                $tmp['title'] = $data['title' . $p];
                $tmp['discount'] = number_format($data['price' . $p] / $data['or_price' . $p] * 10, 1, '.', '');
                $tmp['image'] = $data['image' . $p];
                $tmp['effect'] = $data['effect' . $p];
                $tmp['order'] = $data['order' . $p];
                $tmp['intro'] = $data['intro' . $p];
                $tmp['index_show'] = $data['index_show' . $p];
                $tmp['good_flag'] = $data['good_flag' . $p];
                $sales = json_decode($redis->get('kaluli_sales_count' . $tmp['ID']), true);
                if (!$sales) {
                    $productattrobj = KaluliItemAttr::getInstance()->find($tmp['ID']);
                    $sales = $productattrobj->getSales_count();
                    $redis->set('kaluli_sales_count' . $tmp['ID'], $sales, 10);
                }
                $tmp['sales_count'] = $sales;
                $result[] = $tmp;

            } else {
                continue;
            }
        }
        //按order排序
        foreach ($result as $good) {
            $order[] = $good['order'];
        }
        array_multisort($order, SORT_ASC, $result);
        $result = KaluliItemForm::sortByStock($result);
        return $result;
    }

    //检查会员权益
    private static function isUseBenefits($arr, $gid)
    {
        foreach ($arr as $key => $value) {
            if (isset($value['card_type']) && $value['card_type'] == 2) {
                $check = KllMemberBenefitsTable::getInstance()->findOneById($value['id']);
                if (!empty($check)) {
                    $status = $check->getStatus();
                    $times = $check->getTimes();
                    if ($status == 0 || $times == 0) {
                        $arr[$key]['flag'] = 0;
                    }
                }
                $range = $check->getRange();
                if ($range == 2) {
                    $goods_id = KllMemberBenefitsSkuTable::getInstance()->findByMbId($value['id']);
                    if (!empty($goods_id)) {
                        $gds = [];
                        foreach ($goods_id as $k => $gd) {
                            $gds[] = $gd->getSkuId();
                        }
                        $ret = array_intersect($gid, $gds);
                        if (empty($ret)) {
                            $arr[$key]['flag'] = 0;
                        }
                    }
                }
                if ($range == 1) {
                    $arr[$key]['flag'] = 1;
                }
            }
        }
        return $arr;
    }

    public static function getCurrentLipinka($couponList, $account = 0, $gid = [])
    {

        if (isset($_COOKIE['card'])) {
            //检查一下失效的会员权益
            $arr = json_decode($_COOKIE['card'], true);

            $arr = self::isUseBenefits($arr, $gid);
            if (!empty($couponList)) {
                $couponList = array_merge($couponList, $arr);
                //这个去重是有问题的

                $couponList = self::assoc_unique($couponList, 'id');

            } else {
                $couponList = $arr;
            }
        }

        if (empty($couponList)) {
            return [];
        } else {
            foreach ($couponList as $k => $cl) {
                if (empty($cl)) {
                    unset($couponList[$k]);
                }
            }
        }

        if (empty($account)) {
            foreach ($couponList as &$item) {
                $item['current'] = 0;
                if (is_numeric($item['etime'])) {
                    $item['etime'] = date("Y-m-d", intval($item['etime']));
                }
                if (!isset($item['card_type'])) {
                    $item['card_type'] = 1;
                }
            }
            foreach ($couponList as &$item) {
                if ($item['flag']) {
                    if ($item['card_type'] == 2) {
                        $item['current'] = 2;
                        $isCurrent = 1;
                        break;
                    }
                }
            }
            if(!isset($isCurrent)) {
                foreach ($couponList as &$item) {
                    if ($item['flag']) {
                        if ($item['card_type'] == 1) {
                            $item['current'] = 1;
                            break;
                        }
                    }
                }
            }
        } else {
            $default = 0;
            foreach ($couponList as &$item) {
                # code...
                if ($item['account'] == $account && $item['flag']) {
                    $default = $item['id'];
                }


                $item['current'] = 0;
            }
            if ($default) {
                foreach ($couponList as &$item) {
                    if ($item['id'] == $default) {
                        $item['current'] = 1;
                    }
                }
            } else {
                foreach ($couponList as &$item) {
                    if ($item['flag']) {
                        $item['current'] = 1;
                        break;
                    }
                }
            }
        }
        //排序
        $order = array();
        foreach ($couponList as $val) {
            $order[] = $val['current'];

        }
        array_multisort($order, SORT_DESC, $couponList);

        foreach ($couponList as $key => $value) {
            if (!$value['flag']) {
                unset($couponList[$key]);
                array_push($couponList, $value);
            }
        }
        $couponList = array_values($couponList);
        //检查会员权益是否可用
        foreach ($couponList as $b => $bn) {
            if (is_numeric($bn['etime'])) {
                $couponList[$b]['etime'] = date("Y-m-d", $bn['etime']);
            }
            if (isset($bn['card_type']) && $bn['card_type'] == 2) {
                $flag = KllMemberBenefitsTable::getInstance()->useBenefist($bn['account'], $gid);

                $couponList[$b]['flag'] = $flag;
            }
            if (isset($bn['card_type']) && $bn['card_type'] == 1 && $bn['flag']) {
                if (isset($_COOKIE['u'])) {
                    $cu = explode('-', $_COOKIE['u']);
                    if (isset($cu[0])) {
                        $serviceRequest = new kaluliServiceClient();
                        $serviceRequest->setMethod('lipinka.user.check');
                        $serviceRequest->setVersion('1.0');
                        $serviceRequest->setApiParam('user_id', $cu[0]);
                        $serviceRequest->setApiParam('card', $bn['account']);
                        $response = $serviceRequest->execute();
                        if (!$response->hasError()) {
                            continue;
                        } else {
                            $couponList[$b]['flag'] = 0;
                        }
                    }

                }
            }
        }

        $expire = strtotime("1 day");
        $_COOKIE['card'] = json_encode($couponList);
        setcookie('card', json_encode($couponList), $expire, '/', 'kaluli.com', null, true);
        return $couponList;
    }

    //获取物流公司对应的编码
    public static function getLogisticsCode($company)
    {
        if (!$company) return false;
        switch ($company) {
            case 1:
                return 'shentong';
                break;
            case 2:
                return 'shunfeng';
                break;
            case 3:
                return 'ems';
                break;
            case 4:
                return 'yuantong';
                break;
            case 5:
                return 'yunda';
                break;
            case 6:
                return 'zhongtong';
                break;
            case 7:
                return 'tiantian';
                break;
            case 8:
                return 'huitongkuaidi';
                break;
            case 9:
                return 'zhaijisong';
                break;
            case 28:
                return 'qita';
                break;
            case 33:
                return 'suer';
                break;
            default:
                return '';
                break;
        }
    }

    //获得快递公司
    public static function getDomesticExpress($type)
    {
        switch ($type) {
            case 1:
                return '申通';
                break;
            case 2:
                return '顺丰';
                break;
            case 3:
                return 'EMS';
                break;
            case 4:
                return '圆通';
                break;
            case 5:
                return '韵达';
                break;
            case 6:
                return '中通';
                break;
            case 7:
                return '天天';
                break;
            case 8:
                return '汇通';
                break;
            case 9:
                return '宅急送';
                break;
            case 28:
                return '其他';
                break;
            case 33:
                return '速尔';
                break;
            default:
                return '未知';
                break;
        }
    }

    //去除重复的二维数组
    public static function assoc_unique($arr, $key)
    {
        $tmp_arr = array();
        foreach ($arr as $k => $v) {
            if (isset($v[$key])) {
                if (in_array($v[$key], $tmp_arr)) {
                    unset($arr[$k]);
                } else {
                    $tmp_arr[] = $v[$key];
                }
            }

        }
        sort($arr); //sort函数对数组进行排序
        return $arr;
    }
    //判断两个时间有交集
    public static function  isTimeCross($beginTime1 = '', $endTime1 = '', $beginTime2 = '', $endTime2 = '')  
    {  
        $status = $beginTime2 - $beginTime1;  
        if ($status > 0)  
        {  
            $status2 = $beginTime2 - $endTime1;  
            if ($status2 >= 0)  
            {  
                return false;  
            }  
            else  
            {  
                return true;  
            }  
        }  
        else  
        {  
            $status2 = $endTime2 - $beginTime1;  
            if ($status2 > 0)  
            {  
                return true;  
            }  
            else  
            {  
                return false;  
            }  
        }  
    }  
     //虎扑过来的非正常用户过滤
    public static function abnormal_uid_format($hupu_id){

        if(is_numeric($hupu_id)){
            return $hupu_id;
        }else{
            $hupu_id_arr =  explode('|', $hupu_id);
            
            if(!empty($hupu_id_arr) && isset($hupu_id_arr[0]) && is_numeric($hupu_id_arr[0])){
                return $hupu_id_arr[0];
            }
            $hupu_id_a = explode("-", $hupu_id);
            if(!empty($hupu_id_a) && isset($hupu_id_a[0])  && is_numeric($hupu_id_a[0])){
                return $hupu_id_a[0];
            }
            $hupu_id_special = explode("%", $hupu_id);
            if(!empty($hupu_id_special) && isset($hupu_id_special[0])  && is_numeric($hupu_id_special[0])){
                return $hupu_id_special[0];
            }
        }
        return $hupu_id;
    }


    public static function checkUserNewCoupon($userId) {
        if(empty($userId)) {
            return ['status'=>2,'msg'=>'ok'];//未登录用户展示
        }
        //新用户继续判断是否领取券
        $response = kaluliService::commonServiceCall("coupon","CheckNewCustomerCoupon",['userId'=>$userId]);
        if($response->hasError()) {
            return ['status'=>0,"msg"=>$response->getMsg()];
        }
        $receive = $response->getValue("receive");
        if($receive ==1) {
            return ['status'=>3,"msg"=>"您已经领取过了哦~"];
        } else {
            //判断用户是否下过订单
            $response = kaluliService::commonServiceCall("order","CheckUserOrders",['uid'=>$userId]);
            $isorder = $response->getData();

            if($response->hasError() ||  $isorder['data']['isemptyorder'] == 0) {
                return ['status' => 1, "msg" => "只有未下过单的新用户才能领取哦~"];//非新用户直接略过
            } else {
                return ['status'=>2,"msg"=>'ok','userId'=>$userId];

            }
        }

    }
    /**
     * 清空特殊字符
     */
    public static function clearData($data){
        if(!empty($data)){
            foreach ($data as &$item) {
                $item = trim(str_replace("\n","",$item));
                //清除特殊字符
                $regex = "/\/|\~|\!|\@|\#|\\$|\%|\^|\&|\*|\(|\)|\+|\{|\}|\:|\<|\>|\?|\[|\]|\,|\.|\/|\;|\'|\`|\=|\\\|\|/";
                $item = preg_replace($regex,"",$item);
            }
        }

    }
    /**
     * 驼峰式转下划线
     */
    public static function uncamelize($camelCaps,$separator='_')
    {
        return strtolower(preg_replace('/([a-z])([A-Z])/', "$1" . $separator . "$2", $camelCaps));
    }
    /**
     * 下划线转驼峰式
     */
    public static function camelize($uncamelized_words,$separator='_')
    {
        $uncamelized_words = $separator. str_replace($separator, " ", strtolower($uncamelized_words));
        return ucfirst( ltrim(str_replace(" ", "", ucwords($uncamelized_words)), $separator ));
    }


    public static function getPurchaserAuth() {
        $redis = sfContext::getInstance()->getDatabaseConnection('kaluliRedis');
        $redis->select(10);
        $purchaserAuth = $redis->get("kaluli.purchaser.auth");
        if(!$purchaserAuth) {
            $list = KllPurchaserAuthTable::getInstance()->createQuery()->orderBy("id DESC")->limit(50)->fetchArray();
            if(!empty($list)) {
                $redis->set("kaluli.purchaser.auth",json_encode($list),3600);
            }
        } else {
            $list = json_decode($purchaserAuth,true);
        }

        return $list;

    }



}