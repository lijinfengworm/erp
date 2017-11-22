<?php

class tradeUpdateInfoTask extends sfBaseTask
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
            new sfCommandOption('type', null, sfCommandOption::PARAMETER_REQUIRED, 'update type', '1'),
      // add your own options here
    ));

    $this->namespace        = 'trade';
    $this->name             = 'UpdateInfo';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [trade:GetYouhuiStats|INFO] task does things.
Call it with:

  [php symfony trade:UpdateInfo|INFO]
EOF;
  }
  
  protected function execute($arguments = array(), $options = array()){
        sfContext::createInstance($this->configuration);    
    
        // initialize the database connection
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();
        $redis = sfContext::getInstance()->getDatabaseConnection('kaluliRedis');
        $redis->select(1);
        if ($options['type'] == 1){//trd_usr_item表中的item_id 转化成item_all_id
            $key = 'trade_usr_item_id_to_all_key';
            
            $count = $this->getUserItemCount();
            if ($count > 0){
                $num = ceil($count/20);
                for($i=0;$i<$num;$i++){
                    $redis_id = $redis->get($key);
                    $items = $this->getUserItem($redis_id);
                    if (!empty($items)){
                        foreach ($items as $k=>$v){
                            $allitem =  TrdItemAllTable::getInstance()->findOneByShoeId($v['item_id']);
                            if ($allitem && $allitem->getId()){
                                $this->log($v['id']);
                                $useritem = TrdUserItemTable::getInstance()->find($v['id']);
                                $useritem->setItemAllId($allitem->getId());
                                $useritem->save();
                            } 
                        }
                    }
                    $redis->set($key,$v['id'],60*24*3600);
                }
                sleep(2);
            } else {
                echo 'update over!!!!';exit;
            }
            
        } elseif ($options['type'] == 2) {//把trd_all_items中的数据转移到sphinx中
            
            $key = 'trade_item_all_to_sphinx_key8';
            $redis_id = $redis->get($key);
            $count = $this->getAllItemCount($redis_id);
            if ($count > 0){
                $num = ceil($count/20);
                for($i=0;$i<$num;$i++){
                    $redis_id = $redis->get($key);
                    $items = $this->getAllItem($redis_id);
                    if (!empty($items)){
                        foreach ($items as $k=>$v){
                            if (empty($v['root_id']) && empty($v['children_id'])){
                                $this->log('id:'.$v['id']);
                                if ($v['shoe_id']){
                                    $shoeitem =  TrdItemTable::getInstance()->findOneById($v['shoe_id']);
                                    $allitem =  TrdItemAllTable::getInstance()->findOneById($v['id']);
                                    $brands = array(
                                        '1' => 3,
                                        '2' => 4,
                                        '3' => 5,
                                        '4' => 6,
                                        '5' => 8,
                                        '6' => 9,
                                        '14' => 10,
                                        '15' => 13,
                                        '16' => 11,
                                        '17' => 12,
                                        '18' => 20,
                                        '13' => 20,
                                        '12' => 20,
                                        '11' => 20,
                                        '10' => 20,
                                        '9' => 20,
                                        '7' => 20,
                                        '19' => 20,
                                        '8' => 7,
                                    );
                                    $categorys = array(
                                        '1' => 14,
                                        '2' => 15,
                                        '3' => 16,
                                        '9' => 17,
                                        '4' => 20,
                                        '5' => 18,
                                        '8' => 19,
                                        '10' => 20,
                                        '7' => 20,
                                        '6' => 20,
                                        '11' => 20,
                                    );
                                    if ($shoeitem && $shoeitem->getId()){
                                        $brandid = $shoeitem->getBrandId();
                                        $categoryid = $shoeitem->getCategoryId();
                                        $allitem->setRootId(1);
                                        $allitem->setChildrenId(8);
                                        $info = '';
                                        if (!empty($brandid) && !empty($brands[$brandid])){
                                            $info .= 'G1-A'.$brands[$brandid].',';
                                        }
                                        if (!empty($categoryid) && !empty($categorys[$categoryid])){
                                            $info .= 'G2-A'.$categorys[$categoryid].',';
                                        }
                                        $info .= 'G3-A1';
                                        $allitem->setAttrCollect($info);
                                        $allitem->save();
                                    }
                                    unset($shoeitem);
                                    unset($allitem);
                                    //hcRabbitMQPublisher::getInstance('shihuo_item')->publish(new hcAMQPMessage(array('id'=>$v['id'],'type'=>0,'shoe_id'=>$v['shoe_id'],'shoe'=>1),array('content_type' => 'text/plain')));
                                } else {
                                    $allitem =  TrdItemAllTable::getInstance()->find($v['id']);
                                    $category = array(
                                        '2' => "R1 C9",
                                        '3' => "R1",
                                        '4' => "R1",
                                        '5' => "R3",
                                        '6' => "R7",
                                    );
                                    if ($allitem && $allitem->getId()){
                                        $categoryid = $allitem->getCategoryAllId();
                                        if ($categoryid && $categoryid != 1){
                                            if ($categoryid == 2){
                                                $allitem->setRootId(1);
                                                $allitem->setChildrenId(9);
                                            } else if ($categoryid == 3 || $categoryid == 4){
                                                $allitem->setRootId(1);
                                            } else if ($categoryid == 5){
                                                $allitem->setRootId(3);
                                            } else if ($categoryid == 6){
                                                $allitem->setRootId(7);
                                            }
                                            $allitem->save();
                                        }
                                        unset($shoeitem);
                                        unset($allitem);
                                    } 
                                    //hcRabbitMQPublisher::getInstance('shihuo_item')->publish(new hcAMQPMessage(array('id'=>$v['id'],'type'=>0,'shoe'=>1),array('content_type' => 'text/plain')));
                                }
                            }
                        }
                    }
                    $redis->set($key,$v['id'],60*24*3600);
                }
                sleep(2);
            } else {
                echo 'update over!!!';
                exit;
            }
        } else if ($options['type'] == 3){//trd_desire表中的item_id 转化成item_all_id
            $key = 'trade_usr_desire_id_to_all_key';
            
            $count = $this->getUserDesireCount();
            if ($count > 0){
                $num = ceil($count/20);
                for($i=0;$i<$num;$i++){
                    $redis_id = $redis->get($key);
                    $items = $this->getUserDesire($redis_id);
                    if (!empty($items)){
                        foreach ($items as $k=>$v){
                            $allitem =  TrdItemAllTable::getInstance()->findOneByShoeId($v['item_id']);
                            if ($allitem && $allitem->getId()){
                                $this->log($v['id']);
                                $useritem = TrdDesireTable::getInstance()->find($v['id']);
                                $useritem->setItemAllId($allitem->getId());
                                $useritem->save();
                            } 
                        }
                    }
                    $redis->set($key,$v['id'],60*24*3600);
                }
                sleep(2);
            } else {
                echo 'update over!!!!';exit;
            }
            
        } elseif ($options['type'] == 4) {//获取trd_all_items中的图片尺寸
            
            $key = 'trade_item_all_update_image_key';
            $redis_id = $redis->get($key);
            $count = $this->getAllItemCount($redis_id);
            if ($count > 0){
                $num = ceil($count/20);
                for($i=0;$i<$num;$i++){
                    $redis_id = $redis->get($key);
                    $items = $this->getAllItem($redis_id);
                    if (!empty($items)){
                        foreach ($items as $k=>$v){
                            $this->log('id:'.$v['id']);
                            $allitem =  TrdItemAllTable::getInstance()->findOneById($v['id']);
                            if ($allitem && $allitem->getId()){
                                $width = 208;
                                $height = 117;
                                $imageinfo = getimagesize('http://c'.mt_rand(1,2).'.hoopchina.com.cn'.$v['img_url']);
                                if ($imageinfo){
                                    $width = $imageinfo[0];
                                    $height = $imageinfo[1];
                                }
                                $allitem->setHeight($height);
                                $allitem->setWidth($width);
                                $allitem->save();
                            }
                        }
                    }
                    $redis->set($key,$v['id'],60*24*3600);
                }
                sleep(2);
            } else {
                echo 'update over!!!';
                exit;
            }
        }
        exit;
  }
  
  protected function getUserItem($id){
      if ($id){
          $query = TrdUserItemTable::getInstance()->createQuery('m')
                ->where('m.item_id != ""')
                ->andWhere('m.item_all_id is NULL')
                ->andWhere("m.id > $id")
                ->orderBy('m.id asc')
                ->limit(20);
      } else {
          $query = TrdUserItemTable::getInstance()->createQuery('m')
                ->where('m.item_id != ""')
                ->andWhere('m.item_all_id is NULL')
                ->orderBy('m.id asc')
                ->limit(20);
      }
        return $query->fetchArray();
  }
  
  protected function getUserDesire($id){
      if ($id){
          $query = TrdDesireTable::getInstance()->createQuery('m')
                ->where('m.item_id != ""')
                ->andWhere('m.item_all_id is NULL')
                ->andWhere("m.id > $id")
                ->orderBy('m.id asc')
                ->limit(20);
      } else {
          $query = TrdDesireTable::getInstance()->createQuery('m')
                ->where('m.item_id != ""')
                ->andWhere('m.item_all_id is NULL')
                ->orderBy('m.id asc')
                ->limit(20);
      }
        return $query->fetchArray();
  }
  
  protected function getUserItemCount(){
        $query = TrdUserItemTable::getInstance()->createQuery('m')
        ->where('m.item_id != ?','')
        ->andWhere('m.item_all_id is NULL')
        ->orderBy('m.id asc');
        return $query->count();
  }
  protected function getUserDesireCount(){
        $query = TrdDesireTable::getInstance()->createQuery('m')
        ->where('m.item_id != ?','')
        ->andWhere('m.item_all_id is NULL')
        ->orderBy('m.id asc');
        return $query->count();
  }
  protected function getAllItem($id){
      if ($id){
          $query = TrdItemAllTable::getInstance()->createQuery('m')
                ->where('m.is_hide = ?', 0)
                ->andWhere('m.status = ?', 0)
                ->andWhere("m.id > $id")
                ->orderBy('m.id asc')
                ->limit(20);
      } else {
          $query = TrdItemAllTable::getInstance()->createQuery('m')
                ->where('m.is_hide = ?', 0)
                ->andWhere('m.status = ?', 0)
                ->orderBy('m.id asc')
                ->limit(20);
      }
      
        return $query->fetchArray();
  }
  
  protected function getAllItemCount($id){
      if ($id){
        $query = TrdItemAllTable::getInstance()->createQuery('m')
        ->where('m.is_hide = ?', 0)
        ->andWhere('m.status = ?', 0)
        ->andWhere("m.id > $id")        
        ->orderBy('m.id asc');
      } else {
          $query = TrdItemAllTable::getInstance()->createQuery('m')
            ->where('m.is_hide = ?', 0)
            ->andWhere('m.status = ?', 0)
            ->orderBy('m.id asc');
      }
        return $query->count();
  }
}
