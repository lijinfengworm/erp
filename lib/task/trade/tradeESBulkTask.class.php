<?php
use PhpAmqpLib\Connection\AMQPConnection;
class tradeESBulkTask extends sfBaseTask
{
    private $_index = 'shihuo_v1';
    private $_es_url;
    private $_es_redis;
    protected function configure()
    {
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'trade'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'trade'),
        ));
        $this->addArgument('act');                                 //操作
        $this->addArgument('channel', NULL, '请输入频道', 'all');  //频道


        $this->namespace        = 'trade';
        $this->name             = 'ESBulk';
        $this->briefDescription = '识货ES索引Bluk工具';
        $this->detailedDescription = <<<EOF
The [trade:CreateCiTieForWP|INFO] task does things.
Call it with:

  [php symfony trade:Amqp|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array()){
        sfContext::createInstance($this->configuration);
        ini_set('memory_limit','128M');

        $this->_es_url    =   sfConfig::get('app_shihuo_elasticsearch_url');
        $this->_es_url    =   sfConfig::get('app_shihuo_elasticsearch_testurl');
        $this->_es_redis  =   sfConfig::get('app_shihuo_elasticsearch_redis');

        $this->act        =   $act     = $arguments['act'];
        $this->channel    =   $channel = $arguments['channel'];

        $nowmem = memory_get_usage()/1024/1024;
        while(true){
            if($nowmem <60){
                switch($act){
                    case 'update':
                        $this->update($channel);
                        break;
                    case 'create':
                        $this->create($channel);
                        break;
                    case 'mapping':
                        $this->mapping($channel);
                        break;
                    case 'delkey':
                        $this->delRedisKey($channel);
                        break;
                    case 'delmapping':
                        $this->delmapping($channel);
                        break;
                    default:
                        echo '没有此操作';exit;
                        break;
                }
            }else{
                break;
            }
        }
    }

    /*
    *create
    *
    **/
    private function create($channel){
        $redis = sfContext::getInstance()->getDatabaseConnection('kaluliRedis');
        $redis->select(5);
        $channel_flag_key = 'trade:2015es:task:bulk:flag:channel:'.$channel;
        $channel_flag_num = (int)$redis->get($channel_flag_key);
        switch($channel){
            case 'daigou':
                $table = 'trdProductAttr';
                break;
            case 'news':
                $table = 'trdNews';
                break;
            case 'find':
                $table = 'trdItemAll';
                break;
            case 'groupon':
                $table = 'trdGroupOn';
                break;
            case 'shaiwu':
                $table = 'trdShaiwuProduct';
                break;
            case 'newfind':
                $table = 'trdFind';
                break;
            case 'shop':
                $table = 'trdShopInfo';
                break;
        }

        $table = $table.'Table';
        $max_num = $table::getInstance()->createQuery()->orderBy('id DESC')->limit(1)->fetchOne()->getId();
        $limit = 500;

        $search_name = $channel.'Search';
        $search_obj  = new $search_name();
        while($channel_flag_num < $max_num) {
            $ids = array();
            $res = $table::getInstance()->createQuery()->select('id')->where('id > ?', $channel_flag_num)->limit($limit)->execute();
            foreach($res as $res_k=>$res_v){
                $ids[]  = $res_v['id'];
                $max_id = $res_v['id'];
            }

            $data = $search_obj->_updateData($ids);
            echo 'id:'.$max_id;
            if($data){
                $array = array(
                    '_type'=> $channel,
                    'data' => $data
                );
                $this->es_bulk($array, 'create');
            }

            $channel_flag_num = $max_id;
            $redis->set($channel_flag_key, $channel_flag_num);
            usleep(20);
        }

        exit;
    }

    /*
    *update
    *
    **/
    private function update($channel){
        $redis = sfContext::getInstance()->getDatabaseConnection('kaluliRedis');
        $redis->select(5);
        $channel_flag_key = 'trade:2015es:task:bulk:flag:channel:'.$channel;
        $channel_flag_num = (int)$redis->get($channel_flag_key);
        switch($channel){
            case 'daigou':
                $table = 'trdProductAttr';
                break;
            case 'news':
                $table = 'trdNews';
                break;
            case 'find':
                $table = 'trdItemAll';
                break;
            case 'groupon':
                $table = 'trdGroupOn';
                break;
            case 'shaiwu':
                $table = 'trdShaiwuProduct';
                break;
            case 'newfind':
                $table = 'trdFind';
                break;
            case 'shop':
                $table = 'trdShopInfo';
                break;
        }

        $table = $table.'Table';
        $max_num = $table::getInstance()->createQuery()->orderBy('id DESC')->limit(1)->fetchOne()->getId();
        $limit = 500;

        $search_name = $channel.'Search';
        $search_obj  = new $search_name();
        while($channel_flag_num < $max_num) {
            $ids = array();
            $res = $table::getInstance()->createQuery()->select('id')->where('id > ?', $channel_flag_num)->limit($limit)->execute();
            foreach($res as $res_k=>$res_v){
                $ids[]  = $res_v['id'];
                $max_id = $res_v['id'];
            }

            $data = $search_obj->_updateData($ids);
            echo 'id:'.$max_id;
            if($data){
                $array = array(
                    '_type'=> $channel,
                    'data' => $data
                );
                $this->es_bulk($array, 'update');
            }

            $channel_flag_num = $max_id;
            $redis->set($channel_flag_key, $channel_flag_num);
            usleep(20);
        }

        exit;
    }

    /*
   *mapping
   *
   **/
    private function mapping($channel){
        $search_name = $channel.'Search';
        $search_obj  = new $search_name();
        $data = $search_obj->_mappingData();

        if($data){
            $array = array(
                '_type'=> $channel,
                'data' => $data
            );
            $this->es_mapping($array);
        }
        exit;
    }

    /*
    *delRedisKey
    *
    **/
    private function delRedisKey($channel){
        $redis = sfContext::getInstance()->getDatabaseConnection('kaluliRedis');
        $redis->select(5);

        $channels = array('daigou', 'news', 'find', 'groupon', 'shaiwu', 'newfind', 'shop');
        if('all' == $channel){
            foreach($channels as $channels_v){
                $channel_flag_key = 'trade:2015es:task:bulk:flag:channel:'.$channels_v;
                $redis->del($channel_flag_key);

                echo 'del:'.$channels_v.PHP_EOL;
            }
        }elseif(in_array($channel, $channels)){
            $channel_flag_key = 'trade:2015es:task:bulk:flag:channel:'.$channel;
            $redis->del($channel_flag_key);

            echo 'del:'.$channel.PHP_EOL;
        }

        EXIT;
    }


    /*
    * es mapping
    */
    public  function es_mapping($array = array()){
        $_type = $array['_type'];
        $_data = $array['data'];
        if(!$_type || !$_data){
            return '参数不完整'.PHP_EOL;
        }

        $url = join('/', array(
            $this->_es_url,
            $this->_index,
            '_mapping',
            $_type,
        ));

        //mapping
        $data_json = tradeCommon::requestUrl($url, 'POST', json_encode($_data), NULL, 3);
        $data_arr  =  json_decode($data_json,true);

        //处理返回数据
        if(isset($data_arr['error']) ){
            echo  $data_arr['error'].PHP_EOL;
        }else{
            echo 'mapping'.PHP_EOL;
        }

    }

    /*
     * es bulk
    */
    public  function es_bulk($array = array(), $type = 'update'){
        $_type = $array['_type'];
        $_data = $array['data'];
        if(!$_type || !$_data){
            return '参数不完整'.PHP_EOL;
        }

        $url = join('/', array(
            $this->_es_url,
            $this->_index,
            $_type,
            '_bulk'
        ));

        $bulk_data = '';
        foreach($_data as $k=>$v){
            if('update' == $type){
                 $bulk_data .= "{ \"update\" : {\"_id\" : ".$v['id'].", \"_type\" : \"".$_type."\", \"_index\" : \"".$this->_index."\"} }\n{\"doc\" : ".json_encode($v)."}\n";
            }else{
                 $bulk_data .= '{ "create" : { "_index" : "'.$this->_index.'", "_type" : "'.$_type.'", "_id" : '.$v['id'].' } }'.PHP_EOL.json_encode($v).PHP_EOL;
            }
        }

        //bulk
        $data_json = tradeCommon::requestUrl($url, 'POST', $bulk_data, NULL, 3);
        $data_arr  =  json_decode($data_json,true);

        //处理返回数据
        if(isset($data_arr['error']) ){
            echo  $data_arr['error'].PHP_EOL;
        }else{

            echo 'bulk'.PHP_EOL;
        }
    }

}
