<?php

class baidu {

    private static $VERSION = "wap-0.1";

    private static $VISIT_DURATION = 1800;

    private static $VISITOR_MAX_AGE = 31536000;

    private static $SEARCH_ENGINE_LIST = array(array("1", "baidu.com", "word|wd"), array("2", "google.com", "q"), array("3", "google.cn", "q"), array("4", "sogou.com", "query"), array("5", "zhongsou.com", "w"), array("6", "search.yahoo.com", "p"), array("7", "one.cn.yahoo.com", "p"), array("8", "soso.com", "w"), array("9", "114search.118114.cn", "kw"), array("10", "search.live.com", "q"), array("11", "youdao.com", "q"), array("12", "gougou.com", "search"), array("13", "bing.com", "q"));

    private static $searchEngine = "";
    private static $searchWord = "";

    private static function getQueryValue($url, $key) {
        preg_match("/(^|&|\\?|#)(" . $key . ")=([^&#]*)(&|$|#)/", $url, $matches);
        return count($matches) > 0 ? $matches[3] : null;
    }

    private static function getSourceType($path, $referer, $currentPageVisitTime, $lastPageVisitTime) {
        $parsedPath = parse_url($path);
        $parsedReferer = parse_url($referer);
        if (is_null($referer) || (!is_null($parsedPath) && !is_null($parsedReferer) && $parsedPath["host"] === $parsedReferer["host"])) {
            return ($currentPageVisitTime - $lastPageVisitTime > baidu::$VISIT_DURATION) ? 1 : 4;
        } else {
            $sel = baidu::$SEARCH_ENGINE_LIST;
            for ($i = 0, $l = count($sel); $i < $l; $i++) {
                if (preg_match("/" . $sel[$i][1] . "/", $referer)) {
                    baidu::$searchWord = baidu::getQueryValue($referer, $sel[$i][2]);
                    if (!is_null(baidu::$searchWord)) {
                        baidu::$searchEngine = $sel[$i][0];
                        return 2;
                    }
                }
            }
            return 3;
        } 
    }

    public static function trackPageView($siteId) {
        $path = (isset($_SERVER["HTTPS"]) && ($_SERVER["HTTPS"] === "on") ? 'https://' : 'http://') .
            $_SERVER['SERVER_NAME'] .
            (($_SERVER["SERVER_PORT"] === '80') ? '' : ':' . $_SERVER["SERVER_PORT"]) .
            $_SERVER['REQUEST_URI'];

        $referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : null;

        $currentPageVisitTime = time();

        $lastPageVisitTime = (int)$_COOKIE["Hm_lpvt_" . $siteId];

        $lastVisitTime = $_COOKIE["Hm_lvt_" . $siteId];

        $sourceType = baidu::getSourceType($path, $referer, $currentPageVisitTime, $lastPageVisitTime);
        $isNewVisit = ($sourceType == 4) ? 0 : 1;

        setCookie("Hm_lpvt_" . $siteId, $currentPageVisitTime, 0, "/");
        setCookie("Hm_lvt_" . $siteId, $currentPageVisitTime, time() + baidu::$VISITOR_MAX_AGE, "/");

        $pixelUrl = "http://hm.baidu.com/hm.gif" .
            "?si=" . $siteId .
            "&et=0" .
            "&nv=" . $isNewVisit .
            "&st=" . $sourceType .
            (baidu::$searchEngine !== "" ? "&se=" . baidu::$searchEngine : "") .
            (baidu::$searchWord !== "" ? "&sw=" . urlencode(baidu::$searchWord) : "") .
            (!is_null($lastVisitTime) ? "&lt=" . $lastVisitTime : "") .
            (!is_null($referer) ? "&su=" . urlencode($referer) : "") .
            "&v=" . baidu::$VERSION .
            "&rnd=" . rand(10e8, 10e9);

        return ($pixelUrl);
    }

}

?>
