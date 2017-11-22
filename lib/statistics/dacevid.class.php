<?php

/*
 * dace 统计代码
 */

class daceVid {

    private static $GA_ACCOUNT = "WL_biz_sh_m";
    private static $VERSION = '1.0.1';
    private static $COOKIE_NAME = '__dacem';
    private static $VID3 = '__dacevid3';
    private static $VST = '__dacemvst';
    private static $COOKIE_PATH = "/";
    private static $COOKIE_USER_PERSISTENCE = 63072000; // Two years in seconds.
    private static $DOMAIN = 'http://m.shihuo.cn';
    private static $SEARCH_ENGINE_LIST = array(
        array("1", "baidu.com", "word|wd"),
        array("2", "google.com", "q"),
        array("4", "sogou.com", "query"),
        array("6", "search.yahoo.com", "p"),
        array("7", "yahoo.cn", "q"),
        array("8", "soso.com", "w"),
        array("11", "youdao.com", "q"),
        array("12", "gougou.com", "search"),
        array("13", "bing.com", "q"),
        array("14", "so.com", "q"),
        array("14", "so.360.cn", "q"),
        array("15", "jike.com", "q"),
        array("16", "qihoo.com", "kw"),
        array("17", "etao.com", "q"),
        array("18", "soku.com", "keyword"),
        array("19","sm.cn","q")
    );

    public static function daceAnalyticsGetImageUrl() {
        $rwk = '';
        $rurl = '';
        $documentReferer = isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : '';
        $documentPath = isset($_SERVER["REQUEST_URI"]) ? $_SERVER["REQUEST_URI"] : '';
        if ($documentReferer) {
            $parsedReferer = parse_url($documentReferer);
            $sel = self::$SEARCH_ENGINE_LIST;
            $rurl = urlencode($parsedReferer["host"]);
            if (!preg_match('/hupu.com/', $parsedReferer["host"])) {
                for ($i = 0, $l = count($sel); $i < $l; $i++) {
                    if (preg_match("/" . $sel[$i][1] . "/", $parsedReferer["host"])) {
                        $rwk = self::getQueryValue($documentReferer, $sel[$i][2]);
                        if (!is_null($rwk) || $sel[$i][0] === "2" || $sel[$i][0] === "14" || $sel[$i][0] === "17") {
                            $rurl = $sel[$i][1];
                            break;
                        }
                    }
                }
            }
        }
        $account = self::$GA_ACCOUNT;
        $userAgent = isset($_SERVER["HTTP_USER_AGENT"]) ? $_SERVER["HTTP_USER_AGENT"] : '';

// Try and get visitor cookie from the request.
        if (isset($_COOKIE[self::$VID3]))
            $cookie = $_COOKIE[self::$VID3];
        else if (isset($_COOKIE[self::$COOKIE_NAME]))
            $cookie = $_COOKIE[self::$COOKIE_NAME];
        else
            $cookie = null;


        //此处修改逻辑为先记录变量 $vst ， 因为 setrawcookie 需要服务器往浏览器发送命令写入cookies，存在延迟时间(异步)， 所以直接在下面判断 $_COOKIE 会存在为空的现象
        $vst = NULL;
        if (!isset($_COOKIE[self::$VST])) {
            $vst = self::getActionId();
            setrawcookie(self::$VST, $vst, NULL, self::$COOKIE_PATH, '.shihuo.cn');
        }

        $vst = $vst ? $vst : $_COOKIE[self::$VST];

        //原代码
        /*if (!isset($_COOKIE[self::$VST]))
            setrawcookie(self::$VST, self::getActionId(), NULL, self::$COOKIE_PATH, '.shihuo.cn');

        $vst = isset($_COOKIE[self::$VST]) ? $_COOKIE[self::$VST] : '';*/

//Analyzing whether the new user
//wl_biz_sh_m
// Construct the gif hit url.
        if ($cookie) {
            $utmUrl = json_encode(array(
                "cookie" => TRUE,
                "dom" => "et=m" .
                "&vid=" . $cookie .
                "&sid=" . $account .
                "&ref=" . urlencode($documentReferer) .
                "&path=" . urlencode(self::$DOMAIN . $documentPath) .
                "&rkw=" . $rwk .
                "&rurl=" . $rurl .
                "&ip=" . self::getIP($_SERVER["REMOTE_ADDR"]) .
                "&v=" . self::$VERSION .
                "&vst=" . $vst .
                "&inu=" . 0 .
                "&utmn=" . time() . self::getRandomNumber(),
            ));
        } else {
            $utmUrl = json_encode(array(
                "cookie" => FALSE,
                "dom" => "et=m" .
                "&sid=" . $account .
                "&ref=" . urlencode($documentReferer) .
                "&path=" . urlencode(self::$DOMAIN . $documentPath) .
                "&rkw=" . $rwk .
                "&rurl=" . $rurl .
                "&ip=" . self::getIP($_SERVER["REMOTE_ADDR"]) .
                "&v=" . self::$VERSION .
                "&vst=" . $vst .
                "&utmn=" . time() . self::getRandomNumber(),
            ));
        }
        return $utmUrl;
    }

    private static function getQueryValue($url, $key) {
        preg_match("/(^|&|\\?|#)(" . $key . ")=([^&#]*)(&|$|#)/", $url, $matches);
        return count($matches) > 0 ? $matches[3] : NULL;
    }

// The last octect of the IP address is removed to anonymize the user.
    private static function getIP($remoteAddress) {
        if (empty($remoteAddress)) {
            return "";
        }

// Capture the first three octects of the IP address and replace the forth
// with 0, e.g. 124.455.3.123 becomes 124.455.3.0
        $regex = "/^([^.]+\.[^.]+\.[^.]+\.).*/";
        if (preg_match($regex, $remoteAddress, $matches)) {
            return $matches[1] . "0";
        } else {
            return "";
        }
    }

// Get a random number string.
    private static function getRandomNumber() {
        return rand(0, 0x7fffffff);
    }

    //生成用户唯一标识
    private static function S4() {
        return substr(dechex((((1 + rand()) * 0x10000) | 0)), 0, 4);
    }

    private static function getActionId() {
        return self::S4() . self::S4() . "." . self::S4() . self::S4();
    }

}

?>
