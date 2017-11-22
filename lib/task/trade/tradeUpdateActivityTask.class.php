<?php

class tradeUpdateActivityTask extends sfBaseTask
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
            new sfCommandOption('type', null, sfCommandOption::PARAMETER_REQUIRED, 'The money name', '1'),
            // add your own options here
        ));

        $this->namespace        = 'trade';
        $this->name             = 'UpdateActivity';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [trade:UpdateSitemapTask|INFO] task does things.
Call it with:

  [php symfony trade:UpdateSitemapTask|INFO]
EOF;
    }

  private static  $daigou_old_img_key = 'trade:activity:goods:old:img'; //商品旧图
  private static  $watermark = 'http://www.shihuo.cn/images/trade/activity/blackFriday/{off}_off.png'; //水印图
  private static  $activity_set_ids = array(  //黑五活动集合ID
        157 => 8,
        158 => 7.5,
        159 => 7.5,
        160 => 7
    );

    protected function execute($arguments = array(), $options = array())
    {
        //线下测试
        if(sfConfig::get('sf_environment') == 'dev') {
            self::$activity_set_ids = array(
                  30=>8
            );
        }

        sfContext::createInstance($this->configuration);
        set_time_limit(0);

//        $databaseManager = new sfDatabaseManager($this->configuration);
//        $connection = $databaseManager->getDatabase('trade')->getConnection();
        $activitys = TrdMarketingActivityTable::getInstance()->createQuery()
            ->where('status = ?',3)
            ->andWhere('scope = 2')
            ->andWhere('new_version > current_version')
            ->limit(1)
            ->execute();
        $this->log('query ');
        if($activitys->count() == 0)
        {
            $this->log('sleep 60');
            sleep(60*5);
            exit;
        }

        foreach($activitys as $activity)
        {
            # 集合id为空
            if(empty($activity->group_id))
            {
                $this->log('集合id为空');
                goto End;
            }
            # 如果活动正在更新,则继续,如果活动更新终端，继续更新
            if($activity->getIngVersion() == 0)
            {
                # 初始化
                $activity->setIngVersion($activity->getNewVersion());
                $activity->save();
            }


            $group = TrdActivitySetTable::getInstance()->find($activity->group_id);
            if(empty($group) || $group->status != 1 || empty($group->key))
            {
                $this->log('group status');
                goto End;
            }

            $redis = sfContext::getInstance()->getDatabaseConnection('tradeActivityRedis');
            $redis->select(5);

            $tmpkey = "activity_tmp_".$activity->id."_v_".$activity->ing_version;
            $statsKey = "activity_stats_".$activity->id."_v_".$activity->ing_version;
            $this->log($tmpkey);
            # 复制集合
            if($redis->get($statsKey) == false)
            {
                # 初始化
                if($redis->scard($group->key) == 0)
                {
                    $this->log('groupkey 0');
                    goto End;
                }
                $redis->sunionstore($tmpkey,$group->key);
                $redis->setex($statsKey,864000,1);
            }
            # 更新活动集合
            while($redis->scard($tmpkey) > 0)
            {
                $itemId = $redis->sRandMember($tmpkey);
                if(empty($itemId))
                {
                    $redis->sRem($tmpkey,$itemId);
                    continue;
                }
                $r = TrdMarketingActivityGroupTable::getInstance()->createQuery()->where('item_id =?',$itemId)->andWhere('activity_id =?',$activity->id)->fetchOne();
                $activity_set_ids = array_keys(self::$activity_set_ids);

                if(!$r)
                {
                    $r = new TrdMarketingActivityGroup();
                    $r->activity_id = $activity->id;
                    $r->item_id = $itemId;
                    $r->stime = $activity->stime;
                    $r->etime = $activity->etime;
                    $type = 'Add';
                    $r->version = $activity->ing_version;
                    //图片处理
                    $activity_set_ids = array_keys(self::$activity_set_ids);
                    if(in_array($activity->group_id, $activity_set_ids))  self::daigouImg('add', $itemId, $activity->group_id);
                    $r->save();
                }
                else
                {
                    $type = 'Update';
                    if($r->getVersion() != $activity->getIngVersion())
                    {
                        $r->setVersion($activity->getIngVersion());
                        $r->save();
                    }
                }
                $redis->sRem($tmpkey,$itemId);
                unset($r);
                $this->log( "Activity:{$activity->id} Item:{$itemId} Type:{$type}");
            }
            $this->log('del start');
            # 删除旧版本的集合数据
            while(1)
            {
                $dels = TrdMarketingActivityGroupTable::getInstance()->createQuery()->where('version < ?',$activity->ing_version)->andWhere('activity_id =?',$activity->id)->limit(10)->execute();
                if($dels->count() > 0)
                {
                    foreach($dels as $v)
                    {
                        $v->delete();
                        $this->log( "Activity:{$activity->id} Item:{$v->item_id} Type:Del ");
                        //图片处理
                        $activity_set_ids = array_keys(self::$activity_set_ids);
                        if(in_array($activity->group_id, $activity_set_ids))  self::daigouImg('delete', $itemId, $activity->group_id);
                    }
                }else{
                    break;
                }
                unset($dels);
            }
            # 更新完成
            End:

            $activity->setCurrentVersion($activity->getIngVersion());
            $this->log('set currentVersion '.$activity->getIngVersion());
            if($activity->getIngVersion() > 0)
            {
                $activity->setIngVersion(0);
            }
            $activity->save();
        }
    }


    //代购商品 图片操作
    private static function daigouImg($act, $goods_id, $group_id){
        $product = trdProductAttrTable::getInstance()->find($goods_id);

        if($product){
            switch($act){
                case 'add':
                    if($product->getPurchaseFlag() == 0){
                        $watermark_img  = str_replace('{off}',self::$activity_set_ids[$group_id],self::$watermark);
                        $watermark = $product->getImgPath()."?watermark/1/image/".base64_encode($watermark_img)."/dx/10/dy/10/gravity/SouthEast/ws/0.2";
                        $old_name  = substr($product->getImgPath(), strrpos($product->getImgPath(), '/'));

                        $tradeQiNiu = new tradeQiNiu();
                        $res = $tradeQiNiu->saveas($watermark, "blackfriday/{$group_id}{$old_name}");
                        $res = json_decode($res, true);

                        if($res['status']){
                            $redis = sfContext::getInstance()->getDatabaseConnection('tradeActivityRedis');
                            $redis->select(5);
                            if(!$redis->hget(self::$daigou_old_img_key, $goods_id)){
                                $redis->hset(self::$daigou_old_img_key, $goods_id, $product->getImgPath());
                            }


                            $product->setImgPath($res['url']);
                            $product->save();
                        }
                    }
                    break;
                case 'delete':
                    $redis = sfContext::getInstance()->getDatabaseConnection('tradeActivityRedis');
                    $redis->select(5);
                    $old_img = $redis->hget(self::$daigou_old_img_key, $goods_id);

                    if($old_img){
                        $product->setImgPath($old_img);
                        $product->save();

                        $redis->hdel(self::$daigou_old_img_key, $goods_id);
                    }
                    break;
            }
        }
    }
}
