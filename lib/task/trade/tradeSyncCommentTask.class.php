<?php
use PhpAmqpLib\Connection\AMQPConnection;
class tradeSyncCommentTask extends sfBaseTask
{
    protected function configure()
    {
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'trade'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'trade'),
        ));

        $this->namespace        = 'trade';
        $this->name             = 'SyncComment';
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

        while(true) {
            $nowmem = memory_get_usage()/1024/1024;
            if($nowmem <60){
                tradeSyncCommentTask::sync();
            }else{
                break;
            }
        }
    }

    private static function sync(){
        $redis = sfContext::getInstance()->getDatabaseConnection('kaluliRedis');
        $key = 'trd.comment.num';
        $offset = (int)$redis->get($key);
        $num =  136447;
        $limit = 200;

        while($offset < $num){
             $res = tradeCommon::requestUrl("http://comment.hupu.com/interface/comment/showlistShihuoSpecified?offset={$offset}&limit={$limit}",'GET',NULL,NULL,5);
             if($res) {
                 $res = json_decode($res, true);

                 foreach($res as $res_v){
                     $comment = trdCommentTable::getInstance()->find($res_v['comment_id']);
                     if (!$comment) {
                         $comment = new trdComment();
                         $comment->setId($res_v['comment_id']);
                         $comment->setTypeId(1);
                         $comment->setProductId($res_v['topic_id']);
                         $comment->setUserId($res_v['uid']);
                         $comment->setUserName($res_v['username']);
                         $comment->setContent($res_v['contents']);
                         $comment->setIp(ip2long($res_v['ip']));
                         $comment->setImgsAttr(null);
                         $comment->setPraise($res_v['light_num']);
                         $comment->setCreatedAt(date('Y-m-d H:i:s', $res_v['publish_time']));
                         $comment->setUpdatedAt(date('Y-m-d H:i:s', $res_v['publish_time']));
                         $comment->save();
                     }
                 }

                 echo $offset = $res['comment_id'];
                 $redis->set($key, $offset);
             }
            usleep(200);
        }

        $redis->getConnection()->close();
    }
}
