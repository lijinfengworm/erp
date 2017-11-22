<?php
use PhpAmqpLib\Connection\AMQPConnection;
class tradeAmqpESTask extends sfBaseTask
{
    protected function configure()
    {
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'trade'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'trade'),
        ));

        $this->namespace        = 'trade';
        $this->name             = 'AmqpES';
        $this->briefDescription = '识货ES索引同步';
        $this->detailedDescription = <<<EOF
The [trade:CreateCiTieForWP|INFO] task does things.
Call it with:

  [php symfony trade:AmqpES|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array()){
        $time =  time();
        sfContext::createInstance($this->configuration);

        ini_set('memory_limit','128M');

        $amqpParams = sfConfig::get("app_mabbitmq_options_shihuo");
        $connection = new AMQPConnection($amqpParams['params']['host'], $amqpParams['params']['port'],$amqpParams['params']['user'], $amqpParams['params']['pass'], $amqpParams['params']['vhost']);

        $channel = $connection->channel();
        $channel->queue_declare('ES', false, true, false, false, false);
        $channel->queue_bind('ES', "amq.topic","shihuo.news.detail");
        $channel->queue_bind('ES', "amq.topic","shihuo.groupon.detail");
        $channel->queue_bind('ES', "amq.topic","shihuo.find.detail");
        $channel->queue_bind('ES', "amq.topic","shihuo.product.detail");
        $channel->queue_bind('ES', "amq.topic","shihuo.shaiwu.detail");
        $channel->queue_bind('ES', "amq.topic","shihuo.shop.detail");
        $channel->queue_bind('ES', "amq.topic","shihuo.product.marketing.detail");
        $channel->queue_bind('ES', "amq.topic","shihuo.newfind.detail");
        $channel->queue_bind('ES', "amq.topic","shihuo.goods.notice.detail");
        $channel->basic_consume('ES', '', false, false, false, false, 'tradeAmqpESTask::callback');

        while(count($channel->callbacks)) {
            if(time() - $time > 60) break; //60秒退出

            $nowmem = memory_get_usage()/1024/1024;

            if($nowmem <120){
                $channel->wait();
            }else{
                break;
            }
        }
    }

    public static function callback($msg)
    {
        echo "msg:", $msg->body, "\n";
        $msgBody = json_decode($msg->body, true);

        $newsId = $msgBody['id'];
        $type = $msgBody['type'];
        $channelType = $msgBody['channelType'];
        echo 'news_id: ', $newsId, ';type: ', $type , ';channelType: ', $channelType,PHP_EOL;
        if($channelType == 'goods')
        {
            # 仓库商品单独处理
            $search = new goodsSearch();
            $styleId = !empty($msgBody['styleId'])?$msgBody['styleId']:0;
            switch ($type) {
                case 'create':
                    $search->create($newsId,$styleId);
                    break;
                case 'update':
                    $search->update($newsId,$styleId);
                    break;
                case 'delete':
                    $search->delete($newsId,$styleId);
                    break;
            }
            $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
        }
        else
        {
            switch ($type) {
                case 'create':
                    self::_deliveryItemAdd($newsId, $msg, $channelType);
                    break;
                case 'update':
                    self::_deliveryItemUpdate($newsId, $msg, $channelType);
                    break;
                case 'delete':
                    self::_deliveryItemDelete($newsId, $msg, $channelType);
                    break;
            }
        }
    }

    /*增加*/
    private static function _deliveryItemAdd($newsId, $msg,$channelType)
    {
        if($channelType == 'news'){
            $search = new newsSearch();
        }else if($channelType == 'groupon'){
            $search = new grouponSearch();
        }else if($channelType == 'find'){
            $search = new findSearch();
        }else if($channelType == 'daigou'){
            $search = new daigouSearch();
        }else if($channelType == 'shaiwu'){
            $search = new shaiwuSearch();
        }else if($channelType == 'product_marketing'){
            $search = new daigouMarketSearch();
        }else if($channelType == 'shop'){
            $search = new shopSearch();
        }else if($channelType == 'newfind'){
            $search = new newfindSearch(); 
        }else if($channelType == 'goods_notice'){ 
            $search = new goodsNoticeSearch(); 
        }

        echo $res = $search->create($newsId,true);

        $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
    }

    /*更新*/
    private static function _deliveryItemUpdate($newsId, $msg ,$channelType)
    {

        if($channelType == 'news'){
            $search = new newsSearch();
        }else if($channelType == 'groupon'){
            $search = new grouponSearch();
        }else if($channelType == 'find'){
            $search = new findSearch();
        }else if($channelType == 'daigou'){
            $search = new daigouSearch();
        }else if($channelType == 'shaiwu'){
            $search = new shaiwuSearch();
        }else if($channelType == 'product_marketing'){
            $search = new daigouMarketSearch();
        }else if($channelType == 'shop'){
            $search = new shopSearch();
        }else if($channelType == 'newfind'){
            $search = new newfindSearch();
        }else if($channelType == 'goods_notice'){
            $search = new goodsNoticeSearch();
        }

        echo $res = $search->update($newsId,true);
        $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
    }

    private static function _deliveryItemDelete($newsId, $msg ,$channelType)
    {
        if($channelType == 'news'){
            $search = new newsSearch();
        }else if($channelType == 'groupon'){
            $search = new grouponSearch();
        }else if($channelType == 'find'){
            $search = new findSearch();
        }else if($channelType == 'daigou'){
            $search = new daigouSearch();
        }else if($channelType == 'shaiwu'){
            $search = new shaiwuSearch();
        }else if($channelType == 'product_marketing'){
            $search = new daigouMarketSearch();
        }else if($channelType == 'shop'){
            $search = new shopSearch();
        }else if($channelType == 'newfind'){
            $search = new newfindSearch();
        }else if($channelType == 'goods_notice'){
            $search = new goodsNoticeSearch();
        }

        echo $res = $search->delete($newsId);

        $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
    }
}
