<?php
/*
 *加入营销活动集合
 **/
class joinActivitySetAction extends sfActions
{
    private $expire_time = 10368000;  //120天
    private $send_mq_time_space = 180; //3分钟
    private $activity_set_key = 'trade.activity.goods.set.{set_id}';               //集合ID存储
    private $activity_export_key = 'trade.activity.goods.export.{set_id}';         //计数key
    private $activity_set_filter_key = 'trade.activity.goods.filter.{set_id}';     //过滤id
    private $operate = array('add','delete','exists');
    public function executeJoinActivitySet(sfWebRequest $request){
        sfConfig::set('sf_web_debug', false);
        $goods_id  =  $request->getParameter('goods_id','');  //,号分隔
        $set_id    =  (int)$request->getParameter('set_id');
        $operate   =  $request->getParameter('operate');

        $return = array('status'=>false);
        if(!$goods_id || !$set_id || !$operate){
            $return['msg']  =  '参数丢失';
            return $this->renderText(json_encode($return));
        }
        if(!in_array($operate, $this->operate)){
            $return['msg']  =  '操作类型丢失';
            return $this->renderText(json_encode($return));
        }


        $goods_id = str_replace('，',',', $goods_id);
        $goods_id_arr = explode(',', $goods_id);
        if(!$goods_id_arr){
            $return['msg']   = 'goods_id非空';
            return $this->renderText(json_encode($return));
        }
        foreach($goods_id_arr as $goods_id_v){
            if(!is_numeric($goods_id_v)){
                $return['msg']              = 'goods_id非数字';
                return $this->renderText(json_encode($return));
            }
        }

        //集合信息
        $activitySet = TrdActivitySetTable::getInstance()->find($set_id);
        if($activitySet){
            if($activitySet->getStatus() == 0 && $operate != 'exists'){ //exists不需要验证状态
                $return['status'] = false;
                $return['msg']    = '正在更新中，请稍后再试。';
                return $this->renderText(json_encode($return));
            }
        }else{
            $return['status'] = false;
            $return['msg']    = '集合ID不存在';
            return $this->renderText(json_encode($return));
        }

        //导入集合redis key
        $redis = sfContext::getInstance()->getDatabaseConnection('tradeActivityRedis');
        $redis->select(5);
        $activity_set_filter_key = str_replace('{set_id}', $set_id, $this->activity_set_filter_key);
        $activity_set_filter     = unserialize($redis->get($activity_set_filter_key));

        if($activity_set_filter
            && !empty($activity_set_filter['filterData'])
            && !empty($activity_set_filter['filterSign'])
        ){
            $filterData = str_replace('，',',',$activity_set_filter['filterData']);
            $filterData = explode(',',rtrim($filterData,','));
            if('add' == $operate){
                $activity_set_filter['filterData'] =  join(',', array_unique(array_merge($filterData, $goods_id_arr)));
            }elseif('delete' == $operate){
                $activity_set_filter['filterData'] =  join(',', array_diff($filterData, $goods_id_arr));
            }elseif('exists' == $operate){
                $activity_intersect  = array_intersect($filterData, $goods_id_arr);
                if(!$activity_intersect){
                    $return['status'] = true;
                    $return['msg']    = 'success';
                }else{
                    $return['data']   = array_values($activity_intersect);
                }

                return $this->renderText(json_encode($return));
            }
        }else{
            $return['msg'] = '集合数据为空。';
            return $this->renderText(json_encode($return));
        }

        $redis->set($activity_set_filter_key, serialize($activity_set_filter));

        //集合状态初始化
        $activitySet->setStatus(0);
        $activitySet->save();


        $activity_export_key = str_replace('{set_id}', $set_id, $this->activity_export_key);
        $activity_set_key    = str_replace('{set_id}', $set_id, $this->activity_set_key);
        $redis->set($activity_export_key, 0, 30 * 24 * 3600);
        $redis->del($activity_set_key);

        $this->sendMQ($set_id);

        $return['status'] = true;
        $return['msg'] = 'success';
        return $this->renderText(json_encode($return));

    }


    private function sendMQ($set_id){
        //发到信息队列
        $message = array('id'=> $set_id);
        $message['status'] = 'reindex';

        $amqpParams = sfConfig::get("app_mabbitmq_options_shihuo");
        $connection = new AMQPConnection($amqpParams['params']['host'], $amqpParams['params']['port'], $amqpParams['params']['user'], $amqpParams['params']['pass'], $amqpParams['params']['vhost']);
        $channel = $connection->channel();
        $channel->queue_declare('activity_goods_export_deferred', false, true, false, false, false);

        $msg = new AMQPMessage(json_encode($message));
        $channel->basic_publish($msg, '', 'activity_goods_export_deferred');
    }
}