<?php
/*
 * 抓取DACE数据
 * @author 韩晓林
 * @date   2015/09/22
 **/
 Class  getDaceData{
     private $daceUrl;
     private $daceConf;
     private $daceParams;
     private $isVid;
     private $isCache;
     private $cacheKey = 'trade:2015:dace:cache';
     public  $daceVid;

     public function __Construct(){
         $this->daceVid    =  isset($_COOKIE['_dacevid3']) ? $_COOKIE['_dacevid3'] : '';
     }

     /*
     *获取数据
     **/
     public function getData($daceUrl, $daceParams = array(), $isVid = true, $isCache = false){
         $this->daceConf   =  sfConfig::get('app_dace_api');
         $this->daceUrl    =  $daceUrl;
         $this->daceParams =  $daceParams;
         $this->isVid      =  $isVid;

         $url = $this->getUrl();
         if($isCache){
             $data =  $this->getDataForRedis($url);
         }else{
             $data =  $this->grab($url);
         }

         return $data;
     }

     /*
      *grab
      **/
     private function grab($url){
         $dataJson = tradeCommon::requestUrl(
             $url, 'GET', NULL, NULL, 3
         );

         $data = array();
         if(FunBase::is_json($dataJson)){
             $data = json_decode($dataJson, true);
         }

         return $data;
     }

     /*
     * get url
     */
     private function getUrl(){
         $url = $this->daceConf['url'].$this->daceUrl;
         if ($this->daceParams) {
             $url .= '?'.http_build_query($this->daceParams);
         }

         if($this->isVid){
             if(false === strpos($url, '?')){
                 $url .= '?vid='.$this->daceVid;
             }else{
                 $url .= '&vid='.$this->daceVid;
             }
         }
         return $url;
     }

     /*
     *hash code
     **/
     public function haseCode(){
         $hashCode = abs( FunBase::hashCode($this->daceVid) % 2 ) ;

         return $hashCode;
     }

     /*
     *redis
     */
     private function getDataForRedis($url){
         $redis = sfContext::getInstance()->getDatabaseConnection('kaluliRedis');
         $redis->select(1);

         $cacheKey =  $this->cacheKey.md5($url);
         $data = $redis->get($cacheKey);
         if(!$data){
             $data = $this->grab($url);
             $redis->set($cacheKey ,serialize($data), 60*10);
         }else{
             $data = unserialize($data);
         }

         return $data;
     }
 }
