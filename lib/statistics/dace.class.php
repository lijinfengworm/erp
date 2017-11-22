<?php

/*
 * dace 统计代码
 */

class dace {

    private static $GA_ACCOUNT = "WL_tch_wap";
    private static $VERSION = '1.0.1';
    private static $COOKIE_NAME = '__dacewap';
    private static $COOKIE_PATH = "/";
    private static $COOKIE_USER_PERSISTENCE = 63072000; // Two years in seconds.
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
        array("19","sm.cn","q"),
        array("20","www.haosou.com","q")
    );

    public static function daceAnalyticsGetImageUrl() {
        header('P3P: CP="CURa ADMa DEVa PSAo PSDo OUR BUS UNI PUR INT DEM STA PRE COM NAV OTC NOI DSP COR"'); // set p3p header
        $rwk = '';
        $rurl = '';
        $timeStamp = time();
        $domain = 'http://wap.hupu.com';
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
        $cookie = isset($_COOKIE[self::$COOKIE_NAME]) ? $_COOKIE[self::$COOKIE_NAME] : null;
        //Analyzing whether the new user
        $inu = $cookie ? 0 : 1;

        $guidHeader = isset($_SERVER["HTTP_X_DCMGUID"]) ? $_SERVER["HTTP_X_DCMGUID"] : '';
        if (empty($guidHeader)) {
            $guidHeader = isset($_SERVER["HTTP_X_UP_SUBNO"]) ? $_SERVER['HTTP_X_UP_SUBNO'] : '';
            ;
        }
        if (empty($guidHeader)) {
            $guidHeader = isset($_SERVER["HTTP_X_JPHONE_UID"]) ? $_SERVER['HTTP_X_JPHONE_UID'] : '';
        }
        if (empty($guidHeader)) {
            $guidHeader = isset($_SERVER["HTTP_X_EM_UID"]) ? $_SERVER['HTTP_X_EM_UID'] : '';
        }

        $visitorId = self::getVisitorId($guidHeader, $account, $userAgent, $cookie);

        // Always try and add the cookie to the response.
        setrawcookie(self::$COOKIE_NAME, $visitorId, $timeStamp + self::$COOKIE_USER_PERSISTENCE, self::$COOKIE_PATH, '.hupu.com');

        // Construct the gif hit url.
        $utmUrl = "//ccdace.hupu.com/_dace.gif?" .
                "et=wap" .
                "&vid=" . $visitorId .
                "&sid=" . $account .
                "&ref=" . urlencode($documentReferer) .
                "&path=" . urlencode($domain . $documentPath) .
                "&rkw=" . $rwk .
                "&rurl=" . $rurl .
                "&ip=" . self::getIP(cdn2clientip::getIp()) .
                "&v=" . self::$VERSION .
                "&inu=" . $inu .
                "&utmn=" . $timeStamp . self::getRandomNumber()
        ;
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

    // Generate a visitor id for this hit.
    // If there is a visitor id in the cookie, use that, otherwise
    // use the guid if we have one, otherwise use a random number.
    private static function getVisitorId($guid, $account, $userAgent, $cookie) {

        // If there is a value in the cookie, don't change it.
        if (!empty($cookie)) {
            return $cookie;
        }

        $message = "";
        if (!empty($guid)) {
            // Create the visitor id using the guid.
            $message = $guid . $account;
        } else {
            // otherwise this is a new user, create a new random id.
            $message = $userAgent . uniqid(self::getRandomNumber(), true);
        }

        $md5String = md5($message);

        return "0x" . substr($md5String, 0, 16);
    }

    // Get a random number string.
    private static function getRandomNumber() {
        return rand(0, 0x7fffffff);
    }

}

?>
