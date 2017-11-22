<?php
/**
 * This is the unofficial taobao API for getting the sold count of an item. 
 */
class TaobaoItemGetSoldCountRequest
{
    private $url = 'http://detailskip.taobao.com/json/ifq.htm?callback=shihuo&sid=1&q=1&id=';
    private $itemId;    
    
    public function __construct($itemId, $url = null)
    {
      if ($url)
      {
        $this->url = $url;
      }
      
      $this->itemId = $itemId;
    }
    
    public function send()
    {
      $response = file_get_contents($this->getUrl());
      
      $sellCount = 0;
      
      preg_match("/quanity: (\d+),/", $response, $matches);

      if(isset($matches[1])) {
          $sellCount = $matches[1];
      }

      return $sellCount;
    }
    
    public function getUrl()
    {
      return $this->url.$this->itemId;
    }
    
    public function __toString()
    {
      return $this->getUrl();
    }
}
