<?php

/**
 * TrdItemAll
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    HC
 * @subpackage model
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class TrdItemAll extends BaseTrdItemAll
{
    private $externalUsername;

    public function setExternalUsername($externalUsername)
    {
      $this->externalUsername = $externalUsername;
    }
    
    public function getExternalUsername()
    {
      return $this->externalUsername;
    }

    //拿到所有状态的item
    function get_by_id_all($item_id) {
        $item = TrdItemAllTable::getInstance()
            ->createQuery()
            ->select('t.*, c.name, t.style_ids, b.name, b.id')
            ->from('TrdItemAll t')
            ->leftJoin('t.Category c')
            ->andWhere('id = ?', $item_id)
            ->fetchOne();

        return $item;
    }

    function get_by_id($item_id) {
        $item = TrdItemAllTable::getInstance()
            ->createQuery()
            ->select('t.*, c.name, c.shortcut')
            ->from('TrdItemAll t')
            ->leftJoin('t.Category c')
            ->where('t.is_hide = ?', TrdItemTable::SHOW)
            ->andWhere('t.status = ?', TrdItemTable::STATUS_NORMAL)
            ->andWhere('id = ?', $item_id)
            ->fetchOne();

        return $item;
    }

    function getSimilar($item_id, $condition, $limit = 8, $except_ids = array()) {
        $item = $this->get_by_id($item_id);
        $sql = TrdItemAllTable::getInstance()
            ->createQuery()
            ->setResultCacheLifeSpan(60*60*12)
            ->useResultCache()
            ->select('t.id, t.name, t.item_id, t.shoe_id, t.title, t.price, t.url,t.freight_payer, t.img_url, t.click_count, c.name, c.shortcut, t.sold_count, t.like_count')
            ->from('TrdItemAll t')
            ->leftJoin('t.Category c')
            ->where('t.is_hide = ?', TrdItemTable::SHOW)
            ->andWhere('t.status = ?', TrdItemTable::STATUS_NORMAL)
            ->andWhere('t.is_soldout = 0')
            ->andWhere('id != ?', $item["id"])
            ->groupby('t.item_id')
            ->limit($limit);

        if(isset($item["item_id"]) && $item["item_id"]) {
            $sql->andWhere('t.item_id != ?', $item["item_id"]);
        }

        if(!empty($except_ids)) {
            if($except_ids['id_str']) {
                $sql->addWhere("id not in  ({$except_ids['id_str']})");
            }

            if($except_ids['itemid_str']) {
                $sql->addWhere("item_id not in  ({$except_ids['itemid_str']})");
            }
        }

        //佣金
        if(in_array("give_money", $condition)) {
            $sql->addWhere("give_money > 0");
        }

        //同价格
        if(in_array("price", $condition)) {
            $sql->addWhere("price > ?", $item["price"] - 100);
            $sql->addWhere("price < ?", $item["price"] + 100);
        }


        //同分类
        if(in_array("category", $condition)) {
            $sql->addWhere("category_all_id = ?", $item["category_all_id"]);
        }

        return $sql->execute();

    }

    public function getLikeCountFromDesireTable()
    {
        $count = TrdDesireTable::getInstance()->createQuery('d')->select('count(*) sum')->where('d.item_all_id = ?',  $this->getId())->execute()->getFirst();
        return $count->getSum();
    }
    
    public function getRankByBaseDate() 
    {
       $modifiedFields = $this->getModified();
       if($this->isNew() && empty($modifiedFields['created_at']))
       {
           $count = time();
       }else{
           $count = strtotime($this->getCreatedAt());
       }       
//       if($this->getLikeCount() > 0)
//       {
//           $count += log($this->getLikeCount()+1,2)*3600;
//       }
       if($this->getClickCount() > 0)
       {
           $count += log10($this->getClickCount()+1)*3600;
       }
       
       if($this->getGiveMoney() > 0)
       {
           $count += 3600;
       }
       return $count;
    }    
    
    public function save(Doctrine_Connection $conn = null) 
    {
        $modifiedFields = $this->getModified(true);


        $updateRankArray = array('click_count','created_at','give_money','rank');
        if($this->isNew() || array_intersect($updateRankArray,array_keys($modifiedFields)))
        {
            $this->setRank($this->getRankByBaseDate());
        }

        parent::save($conn);
//        if($this->isNew() && $this->getItemId())
//        {
//            //$taobao  = new TaobaoUtil();
//            TaobaoItemUpdateUtil::getInstance()->addSubscription($this->getItemId());
//            //sfContext::getInstance()->getLogger()->debug('addddddddddddd--------------------------------------------------------------'.serialize($resp));
//        }
//        
//        if(array_intersect(array('is_hide'),array_keys($modifiedFields)) && $this->getItemId())
//        {
//            //$taobao  = new TaobaoUtil();
//            if($this->getIsHide() == 1){
//                TaobaoItemUpdateUtil::getInstance()->addDeleteSubscription($this->getItemId());
//                //sfContext::getInstance()->getLogger()->debug('deeeeeeeee------------------------------------------------------------'.serialize($resp));
//            }else{
//                TaobaoItemUpdateUtil::getInstance()->addSubscription($this->getItemId());
//                //sfContext::getInstance()->getLogger()->debug('addddddddddddd--------------------------------------------------------------'.serialize($resp));
//            }
//        }

        /*  $syncKeyArray =array('title','price','is_hide','is_showsports','tag_collect','publish_date','status','created_at','mart','root_id','children_id','attr_collect','click_count');
        $all_id = '';
        if($this->isNew() || array_intersect($syncKeyArray,array_keys($modifiedFields)))
        {
            $this->syncToSphinx();
        }*/

        //同步商品到个人中心
//        $syncAskKeyArray =array('title','price','is_hide','publish_date','status');
//        if($this->isNew() || array_intersect($syncAskKeyArray,array_keys($modifiedFields)))
//        {
//            $this->syncAskToSphinx();
//        }
        return $this->getId();
        
    }
    
    //发送消息到商品队列处理
    public function syncToSphinx()
    {
       /* if ($this->getStatus() == 1 || $this->getIsHide() == 1){
            hcRabbitMQPublisher::getInstance('shihuo_find')->publish(new hcAMQPMessage(array('id'=>$this->getId(),'type'=>1)));
        } else {
            hcRabbitMQPublisher::getInstance('shihuo_find')->publish(new hcAMQPMessage(array('id'=>$this->getId(),'type'=>0)));
        }*/
        return true;
    }

    //发送消息到ask个人中心
    public function syncAskToSphinx()
    {
        if ($this->getStatus() == 1 || $this->getIsHide() == 1){
            hcRabbitMQPublisher::getInstance('shihuo_user')->publish(new hcAMQPMessage(array('type'=>'delete','item_id'=>$this->getId())),'shihuo_user_product.delete');
        } else {
            hcRabbitMQPublisher::getInstance('shihuo_user')->publish(new hcAMQPMessage(array('type'=>'update','item_id'=>$this->getId())),'shihuo_user_product.update');
        }
        return true;
    }

    function getUsers() {
        $users = TrdUserTable::getInstance()
            ->createQuery()
            ->select('DISTINCT (ui.user_id) as user_id')
            ->from('TrdUserItem ui')
            ->leftJoin('ui.User u')
            ->where('ui.item_all_id = ?', $this->getId())
            ->execute();

        return $users;
    }

    public function isBanned()
    {
      return $this->getStatus() == TrdItemTable::STATUS_BANNED;
    }
    
    public function isBannedPermanent()
    {
      return $this->getStatus() == TrdItemTable::STATUS_BANNED_PERMANENT;
    }    

    public function clickAdd() {
        $this->setClickCount($this->getClickCount() + 1);
        $this->setHeat($this->getHeat() + 1);
        $this->save();
    }

    public function getLink($is_abusolut = false) {
        sfProjectConfiguration::getActive()->loadHelpers('Url');
        $url = "";
        $opt = array();

        if($is_abusolut) {
            $opt["abusolut"] = true;
        }

        $id = $this->getId();

        return url_for("@find_product_detail?item_id=" . $id, $opt);
    }

    function getImgBySize($size = 300) {
        return str_replace(".jpg", "_{$size}.jpg", $this->getImgUrl());
    }

    function getShopLink() {
        $link = "";

        if($this->getShopId()) {
            $shop = TrdShopTable::getInstance()->find($this->getShopId());
            if($shop) {
                if($shop->getExternalId()) {
                    $link = "http://shop" . $shop->getExternalId() . ".taobao.com";
                }
            }

        }

        return $link;
    }
        //提前信息
    function itemRefresh(){
        $memcache = sfContext::getInstance()->getDatabaseConnection('tradeMemcache');
        $key = "trade_item_id_" . $this->getItemId();

        if($memcache->get($key)) {
            
            return FALSE;
            //同一个商品一天只能更新一次
        }
        $time = time(); 
        if($this->getShoeId())
        {
            $trdItem = $this->getTrdItem();
            $trdItem->setCreatedAt(date("Y-m-d H:i:s", $time));
            $trdItem->setPublishDate($time);
            $trdItem->save();
        }else{
            $this->setCreatedAt(date("Y-m-d H:i:s", $time));
            $this->setPublishDate($time);
            $this->save();    
        }
        $memcache->set($key, 1, 0, 60 * 60 * 24);
        return TRUE;
    }
    function getUpdateUrl($opt = array()){ 
        sfProjectConfiguration::getActive()->loadHelpers('Url');
        if($this->getCategory()->getShortcut() == "shoe") {
            $id = $this->getShoeId();
        } else {
            $id = $this->getId();
        }
        return url_for("@item_all_update?item_id=" . $id, $opt);
    }

    function getPageUrl()
    {
        return tradeConfig::getDomain()."/detail/".$this->getId().".html";
    }



    function simpleFormatApp($imgwidth="300",$imgheight="300",$href="")
    {
        $go_app = sfConfig::get('app_app');
        $data = array();
        $data['id'] = $this->getId();
        $data['title']  = $this->getTitle();
        $data['name']  = $this->getName();
        $data['price']  = $this->getPrice();
        if($href)
        {
            $data['href'] =  $href;
        }else{
            $data['href'] =  $go_app['go']['href'].'?url='.urlencode($this->getUrl());
        }
        $data['img_path'] = trdItemAll::getImgPath($this->getImgUrl(),$imgwidth,$imgheight) ;
        $data['time'] =  (string)$this->getPublishDate();
        return $data;
    }

    function completeFormatApp($imgwidth="300",$imgheight="300",$href="")
    {
        $go_app = sfConfig::get('app_app');
        $data = array();
        $data['id'] =  $this->getId();
        $data['title']  = $this->getTitle();
        $data['name']  = $this->getName();
        $data['intro']  = $this->getMemo();
        $data['url']  = $this->getUrl();
        $data['price']  = $this->getPrice();
        $data['tag_collect']  = $this->getTagCollect();
        $data['rank']  = $this->getRank();
        if($href)
        {
            $data['href'] =  $href;
        }else{
            $data['href'] =  $go_app['go']['href'].'?url='.urlencode($this->getUrl());
        }
        $data['img_path'] = trdItemAll::getImgPath($this->getImgUrl(),$imgwidth,$imgheight) ;
        $data['time'] =  (string)$this->getPublishDate();
        return $data;
    }

    public function scanImage($image,$width,$height,$mode=2)
    {
        if($mode == 2){
            return $image.'?imageView2/'.$mode.'/w/'.intval($width);
        } else {
            return $image.'?imageView2/'.$mode.'/w/'.intval($width).'/h/'.intval($height);
        }
    }


    public function postInsert($event)
    {
        $message = array(
            'id' => $this->getId(),
            'type' => 'create',
            'channelType'=>'find'
        );
        $this->sendMqMessage($message);
        parent::postInsert($event);
    }
    public function preUpdate($event)
    {
        $new = $this->getModified();
        $modified = array_keys($new);

        $updateFields = array('title','price','click_count','root_id','children_id','attr_collect','tag_collect','publish_date','is_showsport','img_url','status','is_hide','is_soldout');
        if(array_intersect($updateFields,$modified)){
            $message = array(
                'id' => $this->getId(),
                'type' => 'update',
                'modified'=>$modified,
                'channelType'=>'find',
            );
            $this->sendMqMessage($message);
        }


        parent::preUpdate($event);
    }
    public function postDelete($event)
    {
        $message = array(
            'id' => $this->getId(),
            'type' => 'delete',
            'channelType'=>'find',
        );
        $this->sendMqMessage($message);
        parent::postDelete($event);
    }
    public function sendMqMessage($message)
    {
        $amqpParams = sfConfig::get("app_mabbitmq_options_shihuo");
        $connection = new AMQPConnection($amqpParams['params']['host'], $amqpParams['params']['port'],$amqpParams['params']['user'], $amqpParams['params']['pass'], $amqpParams['params']['vhost']);
        $channel = $connection->channel();
        $arguments = array(
            "x-dead-letter-exchange" => array("S", "amq.topic"),
            "x-message-ttl" => array("I", 2000),
            "x-dead-letter-routing-key" => array("S", "shihuo.find.detail")
        );
        $channel->queue_declare('find_deferred', false, true, false, false, false, $arguments);

        $msg = new AMQPMessage(json_encode($message));
        $channel->basic_publish($msg, '', 'find_deferred');
    }

    public function getDateToList()
    {
        $time = $this->getPublishDate();
        $timeDiff = time() - $time;
        if ($timeDiff <= 3600) {
            return ceil($timeDiff / 60) . '分钟前';
        } else if (($timeDiff > 3600 && $timeDiff <= 3600 * 24)) {
            return date("H:i", $time);
        } else {
            return date("m-d H:i", $time);
        }
    }

    /*获取图片（兼容）*/
    public static function getImgPath($img_url, $width = false, $height = false){
        if(false === strpos($img_url, 'http://')){
            $imgPath =  'http://kaluli.hoopchina.com.cn'.$img_url;
        }else{
            $imgPath =  $img_url;
        }

        if((false !== strpos($imgPath, 'hupucdn')) && ($width && $height)){
            $imgPath .="?imageView/1/w/{$width}/h/{$height}";
        }

        return $imgPath;
    }

}
