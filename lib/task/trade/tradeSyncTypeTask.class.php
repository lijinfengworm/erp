<?php
use PhpAmqpLib\Connection\AMQPConnection;
class tradeSyncTypeTask extends sfBaseTask
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
        $this->name             = 'SyncType';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [trade:CreateCiTieForWP|INFO] task does things.
Call it with:

  [php symfony trade:Amqp|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array()){
        sfContext::createInstance($this->configuration);
        ini_set('memory_limit','256M');

        $type = $arguments['type'];
        $status = $arguments['status'];
        while(true) {
            $nowmem = memory_get_usage()/1024/1024;
            if($nowmem <60){
                tradeSyncTypeTask::sync($type,$status);
            }else{
                break;
            }
        }
    }

    private static function sync($type,$status = false){
        if(!$type) echo '无效的type'.PHP_EOL;

        $redis = sfContext::getInstance()->getDatabaseConnection('kaluliRedis');
        $redis->select(5);
        $type_tmp_num_key = $type.'_type_tmp_num';
        $type_former_r_c = $type.'_type_r_c';

        $type_tmp_num = (int)$redis->get($type_tmp_num_key);      //临时值
        $special = false;
        if($type == 'news'){
            $table = 'trdNews';                                     //新闻
        }else if($type == 'seo_news'){
            $table = 'trdSeoNews';                                  //seo 新闻
        }else if($type == 'product'){
            $table = 'trdProductAttr';                              //商品
        }else if($type == 'item'){
            $table = 'trdItemAll';                                 //发现
        }else if($type == 'shaiwu'){
            $table = 'trdShaiwuProduct';                            //晒物
        }else if($type == 'baoliao'){
            $table = 'trdBaoliao';                                  //爆料
        }else if($type == 'daigou_tags'){
            $table = 'trdDaigouTags';                               //代购评价tag
        }else if($type == 'daigou_brand'){
            $table = 'trdDaigouBrand';                              //代购brand
        }else if($type == 'group'){
            $table = 'trdGroup';                                    //group
            $special = true;
        }else{
            echo '无效的type:'.$type.PHP_EOL;exit;
        }
        $table .= 'Table';

        //回滚
        if($status){
            self::rollback($type_former_r_c,$type_tmp_num_key,$redis,$table);
            exit;
        }


        $table_obj = $table::getInstance()->createQuery()->orderBy('id DESC')->limit(1)->fetchOne();
        $num =  $table_obj->getId();
        $limit = 200;

        if($special) {
            self::specialTable($type,$type_tmp_num,$num,$table,$type_tmp_num_key,$limit,$type_former_r_c,$redis);
        }else{
            while($type_tmp_num < $num){
                $res = $table::getInstance()->createQuery()->where('id >= ?',$type_tmp_num)->limit($limit)->execute();
                if($res) {
                    foreach($res as $res_v){

                        if(!$redis->hget($type_former_r_c,$res_v->getId())){
                            $old_compare = array('root_id'=>$res_v->getRootId(),'children_id'=>$res_v->getChildrenId());
                            $new_compare = FunBase::typeOldToNew($res_v->getChildrenId() ,'children', 'daigou');
                            if($res_v->getChildrenId() && !$new_compare) {  //保存失败的信息
                                $message = array(
                                    'message'=>$type.$res_v->getId().'not found',
                                    'param'=>$old_compare,
                                );
                                tradeLog::info('info',$message);
                            }else{
                                $res_v->setRootId($new_compare[0]);
                                $res_v->setChildrenId($new_compare[1]);
                                $res_v->save();
                            }

                            $type_tmp_num = $res_v->getId();
                            echo $type_tmp_num.PHP_EOL;

                            $redis->set($type_tmp_num_key, $type_tmp_num);
                            $redis->hset($type_former_r_c,$res_v->getId(),serialize($old_compare));  //保存信息备用
                        }else{
                            $type_tmp_num = $res_v->getId();
                            echo $res_v->getId().PHP_EOL;
                            $redis->set($type_tmp_num_key, $res_v->getId());
                        }
                    }
                }
                usleep(200);
            }
        }

        exit;
    }

    /*
   *特殊表处理
   *
    **/
    private static function specialTable($type,$type_tmp_num,$num,$table,$type_tmp_num_key,$limit,$type_former_r_c,$redis){
        switch($type){
            case 'group':
                while($type_tmp_num < $num){
                    $res = $table::getInstance()->createQuery()->where('id >= ?',$type_tmp_num)->limit($limit)->execute();
                    if($res) {
                        foreach($res as $res_v){

                            if(!$redis->hget($type_former_r_c,$res_v->getId())){
                                $old_compare = array('children_id'=>$res_v->getMenuId());
                                $new_compare = FunBase::typeOldToNew($res_v->getMenuId() ,'children', 'daigou');
                                if($res_v->getMenuId() && !$new_compare) {  //保存失败的信息
                                    $message = array(
                                        'message'=>$type.$res_v->getId().'not found',
                                        'param'=>$old_compare,
                                    );
                                    tradeLog::info('info',$message);
                                }else{
                                    $res_v->setMenuId($new_compare[1]);
                                    $res_v->save();
                                }

                                $type_tmp_num = $res_v->getId();
                                echo $type_tmp_num.PHP_EOL;

                                $redis->set($type_tmp_num_key, $type_tmp_num);
                                $redis->hset($type_former_r_c,$res_v->getId(),serialize($old_compare));  //保存信息备用
                            }else{
                                echo $res_v->getId().PHP_EOL;

                            }

                        }
                    }
                    usleep(200);
                }
                break;
        }
    }

    /*
     *rollback
     **/
    private static function rollback($type_r_c_key,$type_tmp_num_key,$redis,$table){
        $all = $redis->hGetAll($type_r_c_key);
        krsort($all);

        foreach($all as $key=>$val){
            $val = unserialize($val);

            $res = $table::getInstance()->find($key);
            if($res){
                $res->setRootId($val['root_id']);
                $res->setChildrenId($val['children_id']);
                $res->save();

                echo $key.'回滚成功'.PHP_EOL;

                $redis->hdel($type_r_c_key,$key);
                $redis->set($type_tmp_num_key, $key);
            }

            usleep(200);
        }
    }
}
