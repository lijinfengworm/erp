<?php

class hcIndexTtserver {

    protected static $instance;

    public static function getInstance() {
        if (!isset(self::$instance)) {
            self::$instance = sfContext::getInstance()->getDatabaseConnection('hcIndex');
        }
        return self::$instance;
    }

    public static function getMobileUrl($url){
        if(preg_match('/(news)\.hoopchina.*\/(\d*)\.html/i', $url, $match) || 
           preg_match('/bbs\.hoopchina\..*?\/(\d*)\.html/i', $url, $match) ||    
           preg_match('/nba\.hupu\.com\/(news)\/\d*\/(\d*)\.html/i', $url, $match) ||
           preg_match('/bbs\.hupu\.com\/(\d*)\.html/i', $url, $match)){
           return $match[1] == 'news' ? url_for('@news?id=' . $match[2]) : url_for('@post?tid=' . $match[1]);
        }
        return false;
    }
    
    public static function getHeaderNews() {
        $news = array();
        sfProjectConfiguration::getActive()->loadHelpers('Url');
        $a = self::getInstance()->get('index_moudle_cache_news_imp1');        
        if($a){
            $header1 = unserialize($a);      
            $len = max(count($header1['title']), count($header1['ftitle']));
            for($i=0; $i<$len; $i++){
                if(isset($header1['url'][$i]) && $url = self::getMobileUrl($header1['url'][$i])){
                    $news[0][] = array('title' => $header1['title'][$i], 'url' => $url);
                }
                if(isset($header1['furl'][$i]) && $url = self::getMobileUrl($header1['furl'][$i])){
                    $news[0][] = array('title' => $header1['ftitle'][$i], 'url' => $url);
                }
            }
        }
        $a = self::getInstance()->get('index_moudle_cache_news_imp2');
        if($a){
            $header2 = unserialize($a); 
            $len = max(count($header2['title']), count($header2['ftitle']));
            for($i=0; $i<$len; $i++){
                if(isset($header2['url'][$i]) && $url = self::getMobileUrl($header2['url'][$i])){
                    $news[1][] = array('title' => $header2['title'][$i], 'url' => $url);
                }
                if(isset($header2['furl'][$i]) && $url = self::getMobileUrl($header2['furl'][$i])){
                    $news[1][] = array('title' => $header2['ftitle'][$i], 'url' => $url);
                }
            }   
        }
        $a = self::getInstance()->get('index_moudle_cache_news_imp3');
        if($a){
            $header3 = unserialize($a);            
            $len = max(count($header3['title']), count($header3['ftitle']));
            for($i=0; $i<$len; $i++){
                if(isset($header3['url'][$i]) && $url = self::getMobileUrl($header3['url'][$i])){
                    $news[2][] = array('title' => $header3['title'][$i], 'url' => $url);
                }
                if(isset($header3['furl'][$i]) && $url = self::getMobileUrl($header3['furl'][$i])){
                    $news[2][] = array('title' => $header3['ftitle'][$i], 'url' => $url);
                }
            }  
        }
        $a = self::getInstance()->get('index_moudle_cache_news_imp4');
        if($a){
            $header4 = unserialize($a);
            $len = max(count($header4['title']), count($header4['ftitle']));
            for($i=0; $i<$len; $i++){
                if(isset($header4['url'][$i]) && $url = self::getMobileUrl($header4['url'][$i])){
                    $news[3][] = array('title' => $header4['title'][$i], 'url' => $url);
                }
                if(isset($header4['furl'][$i]) && $url = self::getMobileUrl($header4['furl'][$i])){
                    $news[3][] = array('title' => $header4['ftitle'][$i], 'url' => $url);
                }
            }  
        }
        return $news;
    }

    /*
     * 获取步行街的数据
     */

    public static function getLeisures() {
        $result = unserialize(self::getInstance()->get('www_index_leisure'));
        $leisures = array();
        foreach ($result['class'] as $k => $v) {
            if (mb_convert_encoding($v, "UTF-8", "gbk") == '步行街') {
                if (preg_match('/.*?(\d*)-?(\d*)\.html/i', $result['href'][$k], $arr)) {
                    $title = str_replace(array('&amp;', '\t', '\r', '—', '<img src=\"http://w1.hoopchina.com.cn/index/images/photo.gif\">', '<img src=\"http://w1.hoopchina.com.cn/index/images/play.gif\">', '<img src=\"http://w3.hoopchina.com.cn/index/images/photo.gif\">', '<img src=\"http://w3.hoopchina.com.cn/index/images/play.gif\">'), array('&', ' &nbsp; &nbsp;', '', '&mdash;', '', '', '', ''), $result['title'][$k]);
                    $leisures[] = array('id' => $arr[1], 'title' => mb_convert_encoding($title, "UTF-8", "gbk"));
                }
            }
        }
        return $leisures;
    }

}