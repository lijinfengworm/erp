<?php
use PhpAmqpLib\Connection\AMQPConnection;
class tradeSyncActivityGoodsTask extends sfBaseTask
{
    protected function configure()
    {
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'trade'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'trade'),
        ));

        $this->addArgument('type');
        $this->addArgument('status',false);

        $this->namespace        = 'trade';
        $this->name             = 'SyncActivityGoods';
        $this->briefDescription = '识货营销活动集合生成';
        $this->detailedDescription = <<<EOF
The [trade:CreateCiTieForWP|INFO] task does things.
Call it with:

  [php symfony trade:Amqp|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array()){
        sfContext::createInstance($this->configuration);
        ini_set('memory_limit','1024M');

        $amqpParams = sfConfig::get("app_mabbitmq_options_shihuo");
        $connection = new AMQPConnection($amqpParams['params']['host'], $amqpParams['params']['port'],$amqpParams['params']['user'], $amqpParams['params']['pass'], $amqpParams['params']['vhost']);

        $channel = $connection->channel();
        $channel->queue_declare('activity_goods_export_deferred', false, true, false, false, false);
        $channel->basic_consume('activity_goods_export_deferred', '', false, false, false, false, 'tradeSyncActivityGoodsTask::callback');

        while(true) {
            $nowmem = memory_get_usage()/1024/1024;
            if($nowmem <60){
                $channel->wait();
            }else{
                break;
            }
        }
    }

    public static function callback($msg){
        echo "msg:", $msg->body, "\n";
        $msgBody = json_decode($msg->body, true);

        $newsId = $msgBody['id'];
        $status = $msgBody['status'];

        $redis = sfContext::getInstance()->getDatabaseConnection('tradeActivityRedis');
        $redis->select(5);
        $activity_goods_export_k = 'trade.activity.goods.export.'.$newsId;
        $activity_goods_set_k = 'trade.activity.goods.set.'.$newsId;
        $activity_goods_fliter_k = 'trade.activity.goods.filter.'.$newsId;

        //找出总数
        $activitySetTable = TrdActivitySetTable::getInstance();
        $activitySet = $activitySetTable->find($newsId);
        $es_role = $activitySet->getRole();

        $es_role = json_decode($es_role,true);

        if($es_role){
            $es_role['size'] = 1;
            $array['_type'] = 'daigou';
            $array['data'] = $es_role;


            $es =new tradeElasticSearch();
            $indexData = $es->search($array);
            $indexData = json_decode($indexData,true);

            $res = self::checkData($indexData);
            if($res['status'])
                $total = $res['num'];
            else
                $total = 0;
        }else{
            $total = 0;
        }


        //处理
        if(self::esSearch($redis, $activity_goods_export_k, $activity_goods_set_k, $activity_goods_fliter_k, $total, $es_role)){
            //同步集合表
            $activitySet->setVersion($activitySet->getVersion() + 1);
            $activitySet->setStatus(1);
            $activitySet->setKey($activity_goods_set_k);
            $activitySet->save();

            //同步集合 活动关联表
            $marketingActivityTable = trdMarketingActivityTable::getInstance();
            $marketingActivityTable->createQuery()
                ->where('group_id = ?',$newsId)
                ->update()
                ->set('new_version ',$activitySet->getVersion())
                ->execute();

            //关闭连接
//            $activitySetTable->getConnection()->close();
//            $marketingActivityTable->getConnection()->close();
            $redis->close();

            echo 'to verion->'.$activitySet->getVersion().PHP_EOL;
            //ack
            $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
        }else{
//            $activitySetTable->getConnection()->close();
        }
    }

    /*
    *es search 并加入集合
    **/
    public static function esSearch($redis, $activity_goods_export_k, $activity_goods_set_k, $activity_goods_fliter_k, $total, $es_role){
        $activity_goods_export_num = (int)$redis->get($activity_goods_export_k);
        $activity_goods_fliter_data = unserialize($redis->get($activity_goods_fliter_k));

        while($activity_goods_export_num < $total){
            $es_role['size'] = 500;
            $es_role['from'] = $activity_goods_export_num ;
            $es_role['sort']['id']['order'] = 'asc';
            $array['_type'] = 'daigou';
            $array['data'] = $es_role;

            $es =new tradeElasticSearch();
            $indexData = $es->search($array);
            $indexData = json_decode($indexData,true);

            $res = self::checkData($indexData);

            if($res['status'])
                $result = $res['result'];
            else
                $result = array();

            //入集合
            foreach($result as $val){
                ++$activity_goods_export_num;

                echo 'flag:'.$activity_goods_export_num.',id:'.$val['id'].PHP_EOL;

                $redis->sadd($activity_goods_set_k, $val['id']);
                $redis->set($activity_goods_export_k, $activity_goods_export_num, 30*24*3600);
            }

            usleep(200);
        }


        //过滤ID
        if(
            $activity_goods_fliter_data
            && !empty($activity_goods_fliter_data['filterData'])
            && !empty($activity_goods_fliter_data['filterSign'])
        ){
            $filterData = str_replace('，',',',$activity_goods_fliter_data['filterData']);
            $filterData = explode(',',rtrim($filterData,','));

            foreach($filterData  as $filterData_val){
                /*if(
                    $productAttr = trdProductAttrTable::getInstance()->find((int)$filterData_val)
                ){*/
                    if($activity_goods_fliter_data['filterSign'] == '-'){
                        $redis->srem($activity_goods_set_k, $filterData_val);
                    }elseif($activity_goods_fliter_data['filterSign'] == '+'){
                        $redis->sadd($activity_goods_set_k, $filterData_val);
                    }

                    echo 'sign:'.$activity_goods_fliter_data['filterSign'].',id:'.$filterData_val.PHP_EOL;
                //}
            }
        }

        return true;
    }

    /*处理返回数据*/
    private static function checkData($indexData){
        $result = $return = array();
        if($indexData['status']){
            $data_hits= isset($indexData['data']['hits']) ? $indexData['data']['hits'] : array();
            if(!empty($data_hits['hits']) && is_array($data_hits['hits'])){
                foreach($data_hits['hits'] as $k=>$v){
                    $result[]['id'] =  isset($v['fields']['id'][0]) ? $v['fields']['id'][0] : $v['_source']['id'];
                }
            }

            $return['status'] = true;
            $return['num'] = $data_hits['total'];
            $return['result'] = $result;
        }else{
            $return['status'] = false;
        }

        return $return;
    }


}
