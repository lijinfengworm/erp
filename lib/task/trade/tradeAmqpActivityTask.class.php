<?php
use PhpAmqpLib\Connection\AMQPConnection;
class tradeAmqpActivityTask extends sfBaseTask
{
    CONST WEB_SITE = 'http://www.shihuo.cn';
    CONST ERROR_NUM = 10;
    protected function configure()
    {
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'trade'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'trade'),
        ));

        $this->namespace        = 'trade';
        $this->name             = 'AmqpActivity';
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
        $queue = 'activity_marketing_use_consume';
        $channel->queue_declare($queue, false, true, false, false, false);
        $channel->queue_bind($queue, "amq.topic","shihuo.activity_marketing.use");
        $channel->basic_consume($queue, '', false, false, false, false, 'tradeAmqpNoticeTask::callback');

        while(count($channel->callbacks) )
        {
            $nowmem = memory_get_usage()/1024/1024;
            if($nowmem <60){

                $channel->wait();
            }else{

                break;
            }
        }
    }

    /*
     *回调
     **/
    public static function callback($msg)
    {
        echo "msg:", $msg->body, "\n";
        $msgBody = json_decode($msg->body, true);

        if($msgBody)
        {
            # 初始化
            if( !empty($msgBody['activity_id']) && $msgBody['type'] == 'new' )
            {
                $activity = TrdMarketingActivityTable::getInstance()->find($msgBody['activity_id']);
                if(empty($activity) || $activity->scope !=2 ||  empty($activity->group_id))
                {
                    goto end;
                }

                $group = TrdActivitySetTable::getInstance()->find($activity->group_id);
                if(empty($group) || $group->status != 1 || empty($group->key))
                {
                    goto end;
                }

                $redis = sfContext::getInstance()->getDatabaseConnection('tradeActivityRedis');
                $redis->select(5);
                $redisGroupSize = $redis->scard($group->key);
                if($redisGroupSize == 0)
                {
                    goto end;
                }
                # 复制集合
                $tmpkey = "activity_tmp_".$activity->id;
                $key = "activity_marketing_".$activity->id;
                $redis->sunionstore($tmpkey,$group->key);

                # 获取当前活动的版本
//                $connection = Doctrine_Manager::getInstance()->getConnection('trade');
//                $query = "SELECT max(version) as max from trd_marketing_activity_group where activity_id='{$activity->id}'";
//                $statement = $connection->execute($query);
//                $statement->execute();
//                $resultset = $statement->fetch(PDO::FETCH_ASSOC);
//                $currentVersion = $resultset['max'];
//                $currentVersion++;
                # 更新只数据库和当前活动集合
                while($itemId = $redis->sPop($tmpkey) )
                {
                    $r = new TrdActivitySet();
                    $r->activity_id = $activity->id;
                    $r->item_id = $itemId;
                    $r->stime = $activity->stime;
                    $r->etime = $activity->etime;
                    $r->save();
                    if($r->getId())
                    {
                        $redis->sadd($key,$itemId);
                    }
                }

            }
            elseif(!empty($msgBody['group_id']))
            {
                # 获取当前活动的版本
                $connection = Doctrine_Manager::getInstance()->getConnection('trade');
                $query = "SELECT max(version) as max from trd_marketing_activity where activity_id='{$activity->id}'";
                $statement = $connection->execute($query);
                $statement->execute();
                $resultset = $statement->fetch(PDO::FETCH_ASSOC);
                $currentVersion = $resultset['max'];
                $currentVersion++;

                $activitys = TrdMarketingActivityTable::getInstance()->where('group_id = ?',$msgBody['group_id'])->andWhere('scope = 2')->fetchArray();
                if(empty($activitys)) goto end;
                foreach($activitys as $activity)
                {
                    # 更新集合
                    $key = "activity_marketing_".$activity['id'];

                }
            }


        }
        end:
        $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
    }

}
