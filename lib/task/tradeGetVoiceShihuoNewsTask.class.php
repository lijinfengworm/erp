<?php

class tradeGetVoiceShihuoNewsTask extends sfBaseTask
{
  protected function configure()
  {
    // // add your own arguments here
    // $this->addArguments(array(
    //   new sfCommandArgument('my_arg', sfCommandArgument::REQUIRED, 'My argument'),
    // ));

    $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'trade'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
      // add your own options here
    ));

    $this->namespace        = 'trade';
    $this->name             = 'GetVoiceShihuoNews';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [trade:GetVoiceShiuoNews|INFO] task does things.
Call it with:

  [php symfony trade:GetVoiceShihuoNews|INFO]
EOF;
  }
  
  protected function execute($arguments = array(), $options = array()){
        sfContext::createInstance($this->configuration);    
    
        // initialize the database connection
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();
        
        $redis = sfContext::getInstance()->getDatabaseConnection('kaluliRedis');
        $redis->select(1);
        $count = ceil(($this->getVoiceShihuoNewsCount())/20);
        
        $page = $redis->get('shihuo_voice_news_page') ? $redis->get('shihuo_voice_news_page') : 1;
        for($i = $page;$i <= $count;$i++){
            $voice_shihuo_news = $this->getVoiceShihuoNews($i);
            if ($voice_shihuo_news){

                foreach ($voice_shihuo_news as $k=>$v){
                    $shihuo_voice_news_ids_hads = $redis->get('shihuo_voice_news_ids_hads') ? unserialize($redis->get('shihuo_voice_news_ids_hads')) : array();
                    if (!in_array($v['id'], $shihuo_voice_news_ids_hads)){
                        $subtitle = '';
                        $goods_state = 0;
                        if (!empty($v['attr'])){
                            $json_info = json_decode($v['attr'],true);
                            if (isset($json_info['subtitle']) && !empty($json_info['subtitle'])){
                                $subtitle = $json_info['subtitle'];
                            }
                            if (isset($json_info['goods_state']) && !empty($json_info['goods_state'])){
                                $goods_state = $json_info['goods_state'] == 'sellup' ? 1 : 0;
                            }
                        }
                        if ($v['img_path']){
                            $image_path = voiceConfig::getCDNDomain($v['id']).'/uploads/star/event/images/'.substr($v['img_path'], 0, 6).'/thumbnail-'.substr($v['img_path'], 7);
                        }

                        $procObject = new trdNews();
                        $procObject->fromArray(array(
                            'intro'=>$v['text'],
                            'text'=>$v['detail_text'],
                            'title'=>$v['detail_title'],
                            'subtitle'=>$subtitle,
                            'goods_state'=>$goods_state,
                            'orginal_url'=>$v['orginal_url'],
                            'orginal_type'=>$v['orginal_type'],
                            'publish_date'=>$v['publish_date'],
                            'hits'=>$v['hits'],
                            'author_id'=>$v['author_id'],
                            'editor_id'=>$v['editor_id'],
                            'support'=>$v['support'],
                            'against'=>$v['against'],
                            'created_at'=>$v['created_at'],
                            'updated_at'=>$v['updated_at'],
                            'img_path'=>isset($image_path) ? $image_path : '',
                            'img_link'=>$v['img_link'],
                        ));
                        $procObject->save();

                        //保存tag
                        $tags = $this->getVoiceTagsByNewsId($v['id']);
                        if (!empty($tags)){
                            foreach ($tags as $key=>$value){
                                $tag_info = $this->getTradeProductTagByName($value['voiceTag']['name']);
                                if (empty($tag_info)){
                                    $tag_id = $this->saveTradeProductTag($value['voiceTag']['name']);
                                    $this->saveTradeNewsTag($procObject->getId(), $tag_id);
                                } else {
                                    $this->saveTradeNewsTag($procObject->getId(), $tag_info[0]['id']);
                                }
                            }
                        }
                        array_push($shihuo_voice_news_ids_hads, $v['id']);
                        $redis->set('shihuo_voice_news_ids_hads',  serialize($shihuo_voice_news_ids_hads));
                    }
                }
            }
            $redis->set('shihuo_voice_news_page',$i);
        }
        exit;
  }
  
  protected function getVoiceShihuoNewsCount(){
       $query = twitterMessageTable::getInstance()->createQuery('m')
                ->where('m.is_delete = 0')
                ->andWhere('m.category = 9 and m.type = 6');
        return $query->count();       
  }
  
  protected function getVoiceShihuoNews($page = 1, $pagesize = 20){
       $query = twitterMessageTable::getInstance()->createQuery('m')
                ->where('m.is_delete = 0')
                ->andWhere('m.category = 9 and m.type = 6')
                ->orderBy('m.publish_date desc')
                ->offset(($page-1)*$pagesize)
                ->limit($pagesize);
        return $query->fetchArray();       
  }
 
  protected function getVoiceTagsByNewsId($message_id){
      if (!$message_id) return false;
      return voiceTagTwitterMessageTable::getInstance()->createQuery('m')
                ->leftJoin('m.voiceTag t')
                ->where('m.twitter_message_id=? ', $message_id)
                ->orderBy('m.is_default desc')
                ->fetchArray();
  }
  
  protected function getTradeProductTagByName($name){
      if (!$name) return false;
      return TrdProductTagTable::getInstance()->createQuery('m')
              ->where('m.name = ?',$name)
              ->fetchArray();
  }
  
  protected function saveTradeProductTag($tagname){
      if (!$tagname) return false;
       $procObject = new trdProductTag();
       $procObject->setName($tagname);
       $procObject->save();
       return  $procObject->getId();
  }
  
  protected function saveTradeNewsTag($newsid,$tagid){
      if (!$newsid) return false;
      if (!$tagid) return false;
      $procObject = new TrdNewsTag();
      $procObject->setTrdProductTagId($tagid);
      $procObject->setTrdNewsId($newsid);
      $procObject->save();
      return true;
  }
}
