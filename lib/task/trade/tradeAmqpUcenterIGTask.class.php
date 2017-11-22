<?php
use PhpAmqpLib\Connection\AMQPConnection;
class tradeAmqpUcenterIGTask extends sfBaseTask
{
    protected function configure()
    {
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'trade'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'trade'),
        ));

        $this->namespace        = 'trade';
        $this->name             = 'AmqpUcenterIG';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [trade:CreateCiTieForWP|INFO] task does things.
Call it with:

  [php symfony trade:Amqp|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array()){
        sfContext::createInstance($this->configuration);

        ini_set('memory_limit','128M');
        $amqpParams = sfConfig::get("app_mabbitmq_options_shihuo");
        $connection = new AMQPConnection($amqpParams['params']['host'], $amqpParams['params']['port'],$amqpParams['params']['user'], $amqpParams['params']['pass'], $amqpParams['params']['vhost']);

        $channel = $connection->channel();
		$channel->basic_qos(null, 1, null);
		$channel->queue_declare('ucenterIG', false, true, false, false, false);
        $channel->queue_bind('ucenterIG', "amq.topic","shihuo.baoliao");
        $channel->queue_bind('ucenterIG', "amq.topic","shihuo.news.recommend");
        $channel->queue_bind('ucenterIG', "amq.topic","shihuo.find.praise");
        $channel->queue_bind('ucenterIG', "amq.topic","shihuo.groupon.praise");
        $channel->queue_bind('ucenterIG', "amq.topic","shihuo.daigou.recommend");
        $channel->queue_bind('ucenterIG', "amq.topic","shihuo_comment_jifen");
        $channel->basic_consume('ucenterIG', '', false, false, false, false, 'tradeAmqpUcenterIGTask::callback');

        while(count($channel->callbacks) ) {
            $nowmem = memory_get_usage()/1024/1024;
            if($nowmem <60){
                $channel->wait();
            }else{
                break;
            }
        }
    }

    public static function callback($msg)
    {
        $msgBody = json_decode($msg->body, true);

        $hupuUid = $msgBody['uid'];
        $action    = $msgBody['action'];
        $actionid = isset($msgBody['actionid'])?$msgBody['actionid']:0;
        $type = isset($msgBody['type'])?$msgBody['type']:0;
        $source = isset($msgBody['source']) ? strtolower($msgBody['source']) : 'app';
        $trdAccount = TrdAccountTable::getByHupuId($hupuUid);

        if(empty($trdAccount))
        {
            $args = array("uid" => $hupuUid);
            $rs = SnsInterface::getContents("getuserbaseinfo", "84", "62c7c5ccd161d52", $args, 'GET');
            $trdAccount = new TrdAccount();
            $trdAccount->setHupuUid($hupuUid);
            $trdAccount->setHupuUsername(iconv("GBK", "UTF-8", $rs['username']));
            $trdAccount->setIntegral(20);
            $trdAccount->setIntegralTotal(20);
            $trdAccount->save();

            $trdAccountHistory = new TrdAccountHistory();
            $trdAccountHistory->setHupuUid($hupuUid);
            $trdAccountHistory->setHupuUsername(iconv("GBK", "UTF-8", $rs['username']));
            $trdAccountHistory->setCategory(10);
            $trdAccountHistory->setType(0);
            $trdAccountHistory->setExplanation("首次登陆送积分");
            $trdAccountHistory->setActionid(0);
            $trdAccountHistory->setIntegral(20);
            $trdAccountHistory->setGold(0);
            $trdAccountHistory->setBeforeIntegral(0);
            $trdAccountHistory->setBeforeGold(0);
            $trdAccountHistory->setAfterIntegral(20);
            $trdAccountHistory->setAfterGold(0);
            $trdAccountHistory->save();
        }

        if($trdAccount)
        {
            $status = $trdAccount->getStatus();
            if($status == 0)
            {
                if(isset($msgBody['username'])){
                    $hupuUname = $msgBody['username'];
                }else{
                    $hupuUname = $trdAccount->getHupuUsername();
                }

                if($action == "BaoliaoThrough")
                {
                    $category = 8;
                }

                if($action == "newsRecommend")
                {
                    $category = 5;
                }

                if($action == "findPraise")
                {
                    $category = 6;
                }

                if($action == "grouponPraise")
                {
                    $category = 7;
                }

                if ('userRecommend' == $action || ('userRecommendImg' == $action)) {
                    $category = 11;
                }

                if ($action == "newsAgainst") {
                    $category = 14;
                }

                # 评论点赞和点踩
                if ($action == "commentAgainst") {
                    $category = 15;
                }

                # 评论
                if ($action == "comment") {
                    $category = 16;
                }

                # 评论删除
                if ($action == "commentDel") {
                    $category = 17;
                }

                # 晒物点赞踩
                if ($action == "shaiwuAgainst") {
                    $category = 18;
                }

                # 点赞
                if($category == 5 || $category == 6 || $category == 7 || $category == 14)
                {
                    $stime = date("Y-m-d 00:00:00");
                    $etime = date("Y-m-d H:i:s",strtotime("+1 day",strtotime($stime)));

                    $categorys = array();
                    $categorys[] = 5;
                    $categorys[] = 6;
                    $categorys[] = 7;
                    $categorys[] = 14;

                    $userTodayIG = TrdAccountHistoryTable::getSumIGByCateTime($hupuUid,$categorys,$stime,$etime);
                    $history = TrdAccountHistoryTable::getHistoryByActionid($hupuUid,$actionid,array($category),$type);
                    if($userTodayIG['integral'] < 10 && empty($history) )
                    {
                        if($category == 5 || $category == 6 || $category == 7 || $category == 14)
                        {
                            $integral = 1;
                        }

                        $beforeIntegral = $trdAccount->getIntegral();
                        $beforeGold     = $trdAccount->getGold();
                        $trdAccount->setIntegral($trdAccount->getIntegral()+$integral);
                        $trdAccount->setIntegralTotal($trdAccount->getIntegralTotal()+$integral);
                        $afterIntegral = $trdAccount->getIntegral();
                        $afterGlod     = $trdAccount->getGold();
                        $trdAccount->save();

                        if (in_array($category, array(5, 6, 7))) {
                            $explanation = '点赞加积分';
                        } else if (in_array($category, array(14))) {
                            $explanation = '无爱加积分';
                        }

                        $trdAccountHistory = new TrdAccountHistory();
                        $trdAccountHistory->setHupuUid($hupuUid);
                        $trdAccountHistory->setHupuUsername($hupuUname);
                        $trdAccountHistory->setCategory($category);
                        $trdAccountHistory->setType(0);
                        $trdAccountHistory->setExplanation($explanation);
                        $trdAccountHistory->setActionid($actionid);
                        $trdAccountHistory->setIntegral(1);
                        $trdAccountHistory->setGold(0);

                        $trdAccountHistory->setBeforeIntegral($beforeIntegral);
                        $trdAccountHistory->setBeforeGold($beforeGold);
                        $trdAccountHistory->setAfterIntegral($afterIntegral);
                        $trdAccountHistory->setAfterGold($afterGlod);
                        $trdAccountHistory->save();
                    }

                }
                # 评论 晒物 点赞点踩
                if($category == 15 || $category == 18)
                {
                    if(empty($actionid))
                    {
                        goto End;
                    }
                    if($category == 15)
                    {
                        $s = '评论';
                    }
                    elseif($category == 18)
                    {
                        $s = '晒物';
                    }
                    $stime = date("Y-m-d 00:00:00");
                    $etime = date("Y-m-d H:i:s",strtotime("+1 day",strtotime($stime)));

                    $categorys = array();
                    $categorys[] = $category;


                    # 评论人的积分操作
                    $userTodayIG = TrdAccountHistoryTable::getSumIGByCateTime($hupuUid,$categorys,$stime,$etime,0);
                    $history = TrdAccountHistoryTable::getHistoryByActionid($hupuUid,$actionid,$categorys,0);

                    if($userTodayIG['integral'] < 50 && empty($history) )
                    {
                        $integral = 1;
                        $beforeIntegral = $trdAccount->getIntegral();
                        $beforeGold     = $trdAccount->getGold();


                        $currentIntegral = $trdAccount->getIntegral()+$integral;
                        $trdAccount->setIntegral($currentIntegral);
                        $trdAccount->setIntegralTotal($currentIntegral+$integral);


                        $afterIntegral = $trdAccount->getIntegral();
                        $afterGlod     = $trdAccount->getGold();
                        $trdAccount->save();
                        $explanation = $s.'点赞或点踩加积分';
                        $trdAccountHistory = new TrdAccountHistory();
                        $trdAccountHistory->setHupuUid($hupuUid);
                        $trdAccountHistory->setHupuUsername($hupuUname);
                        $trdAccountHistory->setCategory($category);
                        $trdAccountHistory->setType(0);
                        $trdAccountHistory->setExplanation($explanation);
                        $trdAccountHistory->setActionid($actionid);
                        $trdAccountHistory->setIntegral($integral);
                        $trdAccountHistory->setGold(0);

                        $trdAccountHistory->setBeforeIntegral($beforeIntegral);
                        $trdAccountHistory->setBeforeGold($beforeGold);
                        $trdAccountHistory->setAfterIntegral($afterIntegral);
                        $trdAccountHistory->setAfterGold($afterGlod);
                        $trdAccountHistory->save();
                    }
                    # 被评论人的积分操作
                    $toUid = $msgBody['toUid'];
                    $trdAccount = TrdAccountTable::getByHupuId($toUid);
                    if($trdAccount)
                    {
                        $userTodayIG = TrdAccountHistoryTable::getSumIGByCateTime($toUid,$categorys,$stime,$etime,$type);

                        $history = TrdAccountHistoryTable::getHistoryByActionid($toUid,$actionid,$categorys,$type);

                        if($userTodayIG['integral'] < 50 && empty($history) )
                        {
                            $integral = 1;
                            $beforeIntegral = $trdAccount->getIntegral();
                            $beforeGold     = $trdAccount->getGold();

                            if($type == 1)
                            {
                                $currentIntegral = $trdAccount->getIntegral()-$integral;
                                if($currentIntegral<0)
                                {
                                    $currentIntegral = 0;
                                }
                            }
                            elseif($type == 0)
                            {
                                $currentIntegral = $trdAccount->getIntegral()+$integral;
                            }
                            else
                            {
                                goto End;
                            }

                            $trdAccount->setIntegral($currentIntegral);
                            if($type == 0)
                            {
                                $trdAccount->setIntegralTotal($currentIntegral+$integral);
                            }

                            $afterIntegral = $trdAccount->getIntegral();
                            $afterGlod     = $trdAccount->getGold();
                            $trdAccount->save();

                            if($type == 0){
                                $explanation = $s.'被点赞加积分';
                            } else if ($type == 1) {
                                $explanation = $s.'被点踩扣积分';
                            }

                            $trdAccountHistory = new TrdAccountHistory();
                            $trdAccountHistory->setHupuUid($toUid);
                            $trdAccountHistory->setHupuUsername($trdAccount->getHupuUsername());
                            $trdAccountHistory->setCategory($category);
                            $trdAccountHistory->setType($type);
                            $trdAccountHistory->setExplanation($explanation);
                            $trdAccountHistory->setActionid($actionid);
                            $trdAccountHistory->setIntegral($integral);
                            $trdAccountHistory->setGold(0);

                            $trdAccountHistory->setBeforeIntegral($beforeIntegral);
                            $trdAccountHistory->setBeforeGold($beforeGold);
                            $trdAccountHistory->setAfterIntegral($afterIntegral);
                            $trdAccountHistory->setAfterGold($afterGlod);
                            $trdAccountHistory->save();
                        }
                    }

                }
                # 评论加积分
                if($category == 16)
                {
                    if(empty($actionid))
                    {
                        goto End;
                    }
                    $stime = date("Y-m-d 00:00:00");
                    $etime = date("Y-m-d H:i:s",strtotime("+1 day",strtotime($stime)));

                    $categorys = array();
                    $categorys[] = $category;
                    $userTodayIG = TrdAccountHistoryTable::getSumIGByCateTime($hupuUid,$categorys,$stime,$etime,$type);
                    $history = TrdAccountHistoryTable::getHistoryByActionid($hupuUid,$actionid,$categorys,$type);
                    if($userTodayIG['integral'] < 50 && empty($history) )
                    {
                        $integral = 2;
                        $beforeIntegral = $trdAccount->getIntegral();
                        $beforeGold     = $trdAccount->getGold();


                        $currentIntegral = $trdAccount->getIntegral()+$integral;
                        $trdAccount->setIntegral($currentIntegral);
                        $trdAccount->setIntegralTotal($currentIntegral+$integral);


                        $afterIntegral = $trdAccount->getIntegral();
                        $afterGlod     = $trdAccount->getGold();
                        $trdAccount->save();
                        $explanation = '评论加积分';

                        $trdAccountHistory = new TrdAccountHistory();
                        $trdAccountHistory->setHupuUid($hupuUid);
                        $trdAccountHistory->setHupuUsername($hupuUname);
                        $trdAccountHistory->setCategory($category);
                        $trdAccountHistory->setType(0);
                        $trdAccountHistory->setExplanation($explanation);
                        $trdAccountHistory->setActionid($actionid);
                        $trdAccountHistory->setIntegral($integral);
                        $trdAccountHistory->setGold(0);

                        $trdAccountHistory->setBeforeIntegral($beforeIntegral);
                        $trdAccountHistory->setBeforeGold($beforeGold);
                        $trdAccountHistory->setAfterIntegral($afterIntegral);
                        $trdAccountHistory->setAfterGold($afterGlod);
                        $trdAccountHistory->save();
                    }

                }

                # 删除评论
                if($category == 17)
                {
                    if(empty($actionid))
                    {
                        goto End;
                    }

                    # 删除-10  + 撤销评论+2的积分  一共 12
                    $integral = 12;
                    $beforeIntegral = $trdAccount->getIntegral();
                    $beforeGold     = $trdAccount->getGold();

                    $currentIntegral = $trdAccount->getIntegral()-$integral;
                    if($currentIntegral<0)
                    {
                        $currentIntegral = 0;
                    }
                    $trdAccount->setIntegral($currentIntegral);
                    //$trdAccount->setIntegralTotal($currentIntegral+$integral);


                    $afterIntegral = $trdAccount->getIntegral();
                    $afterGlod     = $trdAccount->getGold();
                    $trdAccount->save();
                    $explanation = '删除评论';

                    $trdAccountHistory = new TrdAccountHistory();
                    $trdAccountHistory->setHupuUid($hupuUid);
                    $trdAccountHistory->setHupuUsername($hupuUname);
                    $trdAccountHistory->setCategory($category);
                    $trdAccountHistory->setType(1);
                    $trdAccountHistory->setExplanation($explanation);
                    $trdAccountHistory->setActionid($actionid);
                    $trdAccountHistory->setIntegral($integral);
                    $trdAccountHistory->setGold(0);

                    $trdAccountHistory->setBeforeIntegral($beforeIntegral);
                    $trdAccountHistory->setBeforeGold($beforeGold);
                    $trdAccountHistory->setAfterIntegral($afterIntegral);
                    $trdAccountHistory->setAfterGold($afterGlod);
                    $trdAccountHistory->save();

                    # 删除该评论相关的积分
                    $r = TrdAccountHistoryTable::getInstance()
                        ->createQuery()
                        ->where('hupu_uid = ?',$hupuUid)
                        ->andWhere('category = ?',16)
                        ->andWhere('actionid = ?',$actionid)
                        ->andWhere('type = ?',0)
                        ->fetchOne();
                    if($r) $r->delete();
                    # 删除相关点赞的人积分
                    $r = TrdAccountHistoryTable::getInstance()
                        ->createQuery()
                        ->andWhere('category = ?',15)
                        ->andWhere('actionid = ?',$actionid)
                        ->andWhere('type = ?',0)
                        ->execute();
                    if($r)
                    {
                        foreach($r as $v)
                        {
                            $trdAccount = TrdAccountTable::getByHupuId($v->hupu_uid);
                            if($trdAccount)
                            {
                                $currentIntegral = $trdAccount->getIntegral()-1;
                                if($currentIntegral<0)
                                {
                                    $currentIntegral = 0;
                                }
                                $trdAccount->setIntegral($currentIntegral);
                                $trdAccount->save();
                            }
                        }
                    }
                }

                # 爆料加金币
                if($category == 8)
                {
                    $history = TrdAccountHistoryTable::getHistoryByActionid($hupuUid,$actionid,array($category));
                    if(empty($history))
                    {
                        $gold =100 ;
                        $beforeIntegral = $trdAccount->getIntegral();
                        $beforeGold     = $trdAccount->getGold();
                        $trdAccount->setGold($trdAccount->getGold()+$gold);
                        $trdAccount->setGoldTotal($trdAccount->getGoldTotal()+$gold);
                        $afterIntegral = $trdAccount->getIntegral();
                        $afterGlod     = $trdAccount->getGold();
                        $trdAccount->save();

                        $trdAccountHistory = new TrdAccountHistory();
                        $trdAccountHistory->setHupuUid($hupuUid);
                        $trdAccountHistory->setHupuUsername($hupuUname);
                        $trdAccountHistory->setCategory($category);
                        $trdAccountHistory->setType(2);
                        $trdAccountHistory->setExplanation("爆料加金币");
                        $trdAccountHistory->setActionid($actionid);
                        $trdAccountHistory->setIntegral(0);
                        $trdAccountHistory->setGold(100);

                        $trdAccountHistory->setBeforeIntegral($beforeIntegral);
                        $trdAccountHistory->setBeforeGold($beforeGold);
                        $trdAccountHistory->setAfterIntegral($afterIntegral);
                        $trdAccountHistory->setAfterGold($afterGlod);
                        $trdAccountHistory->save();
                    }

                }
                # 海淘评价
                if ($category == 11) {
                    $history = TrdAccountHistoryTable::getHistoryByActionid($hupuUid,$actionid,array($category));
                    if (empty($history)) {
                        if ('userRecommend' == $action) {
                            $integral = 100;
                            $gold = 0;
                            $type = 0;
                        } elseif ('userRecommendImg' == $action) {
                            $integral = 150;
                            $gold = 50;
                            $type = 4;
                        }
                        $beforeIntegral = $trdAccount->getIntegral();
                        $beforeGold     = $trdAccount->getGold();
                        $trdAccount->setIntegral($trdAccount->getIntegral()+$integral);
                        $trdAccount->setIntegralTotal($trdAccount->getIntegralTotal()+$integral);
                        $trdAccount->setGold($trdAccount->getGold()+$gold);
                        $trdAccount->setGoldTotal($trdAccount->getGoldTotal()+$gold);
                        $afterIntegral = $trdAccount->getIntegral();
                        $afterGlod     = $trdAccount->getGold();
                        $trdAccount->save();

                        $trdAccountHistory = new TrdAccountHistory();
                        $trdAccountHistory->setHupuUid($hupuUid);
                        $trdAccountHistory->setHupuUsername($hupuUname);
                        $trdAccountHistory->setCategory($category);
                        $trdAccountHistory->setType($type);
                        $trdAccountHistory->setExplanation('海淘评价');
                        $trdAccountHistory->setSource($source);
                        $trdAccountHistory->setActionid($actionid);
                        $trdAccountHistory->setIntegral($integral);
                        $trdAccountHistory->setGold($gold);

                        $trdAccountHistory->setBeforeIntegral($beforeIntegral);
                        $trdAccountHistory->setBeforeGold($beforeGold);
                        $trdAccountHistory->setAfterIntegral($afterIntegral);
                        $trdAccountHistory->setAfterGold($afterGlod);
                        $trdAccountHistory->save();
                    }
                }
            }
        }
        End:
        $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
//        TrdAccountTable::getInstance()->getConnection()->close();
//        TrdAccountHistoryTable::getInstance()->getConnection()->close();
    }
}
