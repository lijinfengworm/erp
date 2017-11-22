<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of 163RssSpider
 *
 * @author hcsyp
 */
class voice163RssSpider extends voiceRssSpider implements voiceSpider {
    
    /*
     * 获取detail_title字段值
     */
//    public function getDetailTitle($page){
//        if(!$page) return '';
//        $selector = new sfDomCssSelector($page);        
//        $title = $selector->matchSingle('h1#h1title');  
//        if(!empty($title->nodes)){
//            $values = $title->getValues();
//            return preg_replace('/\x20{1,}/U', ',', trim($values[0]));
//            
//        }
//        return '';
//    }
//    
//    /*
//     * 获取detail_text字段值
//     */
//    public function getDetailText($page){
//        if(!$page) return '';
//        $selector = new sfDomCssSelector($page);       
//        $content = $selector->matchSingle('div#endText');
//        if(!empty($content->nodes)){
//            $values = $content->getValues();
//
//            echo preg_replace(array('/(\(.*\)\s*$)/iU', '/微博/U', '/(.*)([\r\n|\r|\n])/iU', '/(<p>\s*<\/p>)/iU'), array('', '', '<p>$1</p>$2', ''), trim($values[0]));exit;
//
//            return trim($values[0]);            
//        }
//        return '';
//    }
    
    
    /*
     * 抓取远程页面  
     */
    public function getPage($url){
      $content = parent::getPage($url);
      return mb_convert_encoding($content, 'utf-8', 'gb2312');
    }
    
     /*
     * 获取单条详细信息
     */
    public function getDetailItem($item){  
        $url = $this->getOrginalUrl($item);        
        return $this->getPage($url);
    }
    /*
     * 获取detail_title字段值
     */
    public function getDetailTitle($page){
        if(!$page) return '';
        preg_match('/<h1[^>]*id="h1title"[^>]*>([^>]*)<\/h1>/i', $page, $match);
        if(isset ($match[1])){
            return mb_substr(preg_replace('/\x20{1,}/U', '，', trim($match[1])), 0, 20, 'utf-8');
        }
        return '';
    }
    
    /*
     * 获取detail_text字段值
     */
    public function getDetailText($page){
        if(!$page) return '';    
        preg_match('/<div[^>]*id="endText"[^>]*>([\s\S]*?)<div[^>]*class="left"[^>]*>/i', $page, $match);
        if(isset ($match[1])){
            $content = $match[1];
            $content = preg_replace('/<script[^>]*>[^>]*<\/script>/i', '', $content);
            $content = strip_tags($content,'<p>,<img>');
            $content = preg_replace('/<p[^>]*>/i', '<p>', $content);
            $content = $this->filterWeibo($content);
            $content = trim($content);
            return $content;
        }
        return '';
    }
    
    /*
     * 导语部分也就是新声中的简介
     */
    public function getText($page){
        if (!$page) return '';
        preg_match('/<p[^>]*class="summary"[^>]*>([^>]*)<\/p>/i', $page, $match);
        
        if (isset($match[1])){
            return mb_substr($match[1], 0, 200, 'utf-8');
        }
        
        return '';
    }
}

?>
