<?php
use PhpAmqpLib\Connection\AMQPConnection;
class tradeAmqpNoticeTask extends sfBaseTask
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
        $this->name             = 'AmqpNotice';
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
        $queue = 'notice_queue_use_consume';
        $channel->queue_declare($queue, false, true, false, false, false);
        $channel->queue_bind($queue, "amq.topic","shihuo.notice.use");
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
            if( empty($msgBody['uid']) || empty($msgBody['type']) || empty($msgBody['content']) || empty($msgBody['time'])  )
            {
                goto end;
            }

            # 评论通知
            if( $msgBody['type'] == 1 )
            {
                if( empty($msgBody['from'])  || empty($msgBody['sender_uid']) || empty($msgBody['reply_content']) || empty($msgBody['product_id']))  goto end;
                sfProjectConfiguration::getActive()->loadHelpers('common');
                # 主表
                $row = new TrdNotices();
                $row->type = $msgBody['type'];
                $row->uid = $msgBody['uid'];
                $row->sender_uid = $msgBody['sender_uid'];
                $row->time = $msgBody['time'];
                $row->save();
                # 子表
                $rowAttr = new TrdNoticesAttr();
                $rowAttr->notice_id = $row->getId();
                $content = array(
                    'content' => substr_for_utf8(strip_tags($msgBody['content']),30),
                    'reply_content' => substr_for_utf8(strip_tags($msgBody['reply_content']),30),
                );
                $rowAttr->content = serialize($content);
                if(!empty($msgBody['attr']['msgCommentId'])) $rowAttr->comment_id = $msgBody['attr']['msgCommentId'];
                if(!empty($msgBody['attr']['msgReplyId'])) $rowAttr->reply_id = $msgBody['attr']['msgReplyId'];

                $attr = array(
                    'product_id' => $msgBody['product_id'],
                    'from' => $msgBody['from'],
                //    'commentId' => $msgBody['attr']['msgCommentId'],
                //    'replyId' => $msgBody['attr']['msgReplyId'],
                );
                $rowAttr->extra = serialize($attr);
                $rowAttr->save();
            }
            # 更新通知数量
            TrdNoticesCountTable::updateCount($msgBody['uid'],$msgBody['type']);

        }
        end:
        $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
    }

}
