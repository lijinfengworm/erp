<?php

/*
 * voice爬取rss的爬虫
 */

class voiceRssSpider implements voiceSpider{
    
    public $timeout = 3; 
    private $content = null,
            $tagid = null;
    public $rss;
    public $weiboExp = '/<a[^>]*>?[微博|\[微博\]|\(微博\)|\(微博 数据\)|（微博）|（微博 数据）]<\/a>?/iU';


    public function __construct($rss, $tag_id) {
        $this->rss = $rss;
        $this->tagid = $tag_id;
    }
    
    public function work(){
        $this->getRss();
    }

    /*
     * 获取rss内容
     */
    public function getRss(){
        $this->content = @common::CurlWithTimeout($this->rss->getUrl(), $this->timeout);
        $this->getItems();
    }
    
    /*
     * 获取rss内容中的消息
     */
    public function getItems(){
        if(!$this->content) return ;
        $dom = new DOMDocument();
        @$dom->loadXML($this->content);
        $this->items = $dom->getElementsByTagName("item");
        $this->saveItems();
    }
    
    public function saveItems(){       
        $last_update = $this->rss->getLastUpdate();
        foreach($this->items as $item){           
            $created_at = $this->getPublishDate($item);            
            if($created_at > $this->rss->getLastUpdate()){               
                $value = $this->getValues($item);
                
                if (!$values['detail_text'])
                    continue;
                
                $this->save($value);
                $last_update = $last_update < $created_at ? $created_at : $last_update;
            }
        }
        if($last_update > $this->rss->getLastUpdate()){
            $this->rss->setLastUpdate($last_update);
            $this->rss->save();
        }
    }
    
    public function save($values){
        $m = new twitterMessage();
        $values['is_delete'] = 1;
        $m->fromArray($values);
        $m->save();
        $t = new voiceTagTwitterMessage();
        $vt = array('voice_tag_id' => $this->tagid, 'twitter_message_id' => $m->getId(), 'is_default' => 0);
        $t->fromArray($vt);
        $t->save();
    }

    /*
     * 获取单条消息需要保存的字段
     */
    public function getValues($item){ 
        
        $values = array();
        $values['voice_media_id'] = $this->getVoiceMeidaId();
        $values['orginal_url'] = $this->getOrginalUrl($item);
        $values['orginal_type'] = $this->getOrginalType();
        $detail = $this->getDetailItem($item);
        $url = parse_url($this->rss->getUrl());
        
        if (strpos($url['host'], '163') !==false){
            $values['text'] = $this->filterWeibo($this->getText($detail));
        } else {
            $values['text'] = $this->filterWeibo($this->getText($item));  
        }
        
        $values['detail_title'] = $this->getDetailTitle($detail);
        $values['detail_text'] = $this->getDetailText($detail);
        $values['img_link'] = $this->getImgLink($values['detail_text']);
        $values['detail_text'] = strip_tags($values['detail_text'], '<p>');
        $values['publish_date'] = $this->getPublishDate($item);
        $values['category'] = $this->getCategory();
        $values['type'] = $this->getType();
        return $values;
    }
    
    
    /*
     * 获取单条详细信息
     */
    public function getDetailItem($item){        
        return $this->loadHTML($this->getPage($this->getOrginalUrl($item)));
    }
    
    /*
     * 抓取远程页面 
     */
    public function getPage($url){
        return file_get_contents($url);
    }
    
    /*
     * 载入页面内容
     */
    public function loadHTML($content){
        $dom = new DOMDocument();
        @$dom->loadHTML($content);
        return $dom;
    }
    
    /*
     * 获取voice_media_id
     */
    public function getVoiceMeidaId(){
        return $this->rss->getVoiceMediaId();
    }
    
    /*
     * 获取单条信息的源地址
     */
    public function getOrginalUrl($item){
        $link = $item->getElementsByTagName("link");
        return $link->item(0)->nodeValue;
    }
    
    /*
     * 获取单条信息的来源名
     */
    public function getOrginalType(){
        return $this->rss->getVoiceMedia()->getName();
    }
    
    /*
     * 获取text字段值
     */
    public function getText($item){
        $text = $item->getElementsByTagName("description");
        return trim($text->item(0)->nodeValue);
    }
    
    /*
     * 获取detail_title字段值
     */
    public function getDetailTitle($page){
        return '';
    }
    
    /*
     * 获取detail_text字段值
     */
    public function getDetailText($page){
        return '';
    }
    
    /*
     * 获取publish_date字段值
     */
    public function getPublishDate($item){
        $date = $item->getElementsByTagName("pubDate");
        $date = $date->item(0)->nodeValue;
        return date('Y-m-d H:i:s', strtotime($date));
    }
    
    /*
     * 获取category字段值
     */
    public function getCategory(){
        return $this->rss->getCategory();
    }
    
    /*
     * 获取type字段值
     */
    public function getType(){
        return twitterMessageTable::$voicersstype;
    }
    
    /*
     * 过滤微博文字和文字链
     */
    public function filterWeibo($content){
        return preg_replace('/(<a[^>]*>)?(微博|\[微博\]|\(微博\)|\(微博 数据\)|（微博）|（微博 数据）)(<\/a>)?/iU', '', $content);
    }
    
    /*
     * 获取正文中的图片
     */
    public function getImgLink($content){
        preg_match ('/<img[^>]*src=[\'"]?([^>=\'"]*)[\'"]?[^>]+>/i', $content, $match);

        if (isset($match[1])){
            return $match[1];
        }
        
        return null;
    }
    
}

?>
