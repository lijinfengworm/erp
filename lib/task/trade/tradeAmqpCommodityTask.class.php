<?php
use PhpAmqpLib\Connection\AMQPConnection;
class tradeAmqpCommodityTask extends sfBaseTask
{
    protected function configure()
    {
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'trade'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'trade'),
        ));

        $this->namespace        = 'trade';
        $this->name             = 'AmqpCommodity';
        $this->briefDescription = '识货商品库同步';
        $this->detailedDescription = <<<EOF
The [trade:CreateCiTieForWP|INFO] task does things.
Call it with:

  [php symfony trade:AmqpCommodity|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array()){
        $time =  time();
        sfContext::createInstance($this->configuration);
        ini_set('memory_limit','128M');

        $amqpParams = sfConfig::get("app_mabbitmq_options_shihuo");
        $connection = new AMQPConnection($amqpParams['params']['host'], $amqpParams['params']['port'],$amqpParams['params']['user'], $amqpParams['params']['pass'], $amqpParams['params']['vhost']);

        $channel = $connection->channel();
        $channel->queue_declare('Commodity', false, true, false, false, false);
        $channel->queue_bind('Commodity', "amq.topic","shihuo.news.detail");
        $channel->queue_bind('Commodity', "amq.topic","shihuo.groupon.detail");
        $channel->queue_bind('Commodity', "amq.topic","shihuo.find.detail");
        $channel->queue_bind('Commodity', "amq.topic","shihuo.product.detail");
        $channel->basic_consume('Commodity', '', false, false, false, false, 'tradeAmqpCommodityTask::callback');

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
        $msgBody = json_decode($msg->body, true);

        $id   = $msgBody['id'];
        $type = $msgBody['type'];
        $channelType = $msgBody['channelType'];
        echo 'ID: ', $id, ';TYPE: ', $type ,' ;ChannelType: ', $channelType,PHP_EOL;

        switch ($channelType) {
            case 'news':
                self::_news($id, $type, $msg);
                break;
            case 'daigou':
                self::_product($id, $type, $msg);
                break;
            case 'groupon':
                self::_groupon($id, $type, $msg);
                break;
            case 'find':
                self::_find($id, $type, $msg);
                break;
        }
    }

    //优惠信息
    public static function _news($id, $type, $msg){
        $obj = trdNewsTable::getInstance()->find($id);
        if($obj && $type == 'update'){
            $commodity = $obj->getCommodity();
            $commodity = json_decode($commodity, true);

            if(
                !$obj->getIsDelete()
                && in_array($obj->getAuditStatus(), array(1, 4))
                && $commodity
                && empty($commodity['status'])
                && $commodity['goods_id']
            ){
                $serviceRequest = new tradeServiceClient();
                $serviceRequest->setMethod('goods.save.supplier');
                $serviceRequest->setVersion('1.0');
                $serviceRequest->setApiParam('goods_id', $commodity['goods_id']);
                $serviceRequest->setApiParam('name', TrdGoodsSupplierForm::getShopName($obj->getOrginalUrl()));
                $serviceRequest->setApiParam('description', $commodity['desc']);
                $serviceRequest->setApiParam('price', $obj->getPrice());
                $serviceRequest->setApiParam('url', $obj->getOrginalUrl());
                $serviceRequest->setApiParam('status', 0);
                $serviceRequest->setApiParam('from_type', 1);
                $serviceRequest->setApiParam('from_id', $obj->getId());
                $serviceRequest->setApiParam('notice', true);
                $response = $serviceRequest->execute();

                $error = $response->getError();
                if(!$error){
                    $info = $response->getValue('info');

                    //保存来源加入状态
                    $commodity['status'] = 1;
                    $obj->setCommodity(json_encode($commodity));
                    $obj->save();


                    echo 'add:'.$info['id'].PHP_EOL;
                }else{
                    echo 'add:error';
                }
            }
        }

        

        //ack
        $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
    }

    //海淘
    public static function _product($id, $type, $msg){
        $obj = trdProductAttrTable::getInstance()->find($id);

        if($type == 'update' && $obj){
            $commodity = $obj->getCommodity();
            $commodity = json_decode($commodity, true);

            if(
                 $obj->getStatus() == 0
                 && $obj->getShowFlag() == 1
                 && $obj->getPurchaseFlag() == 0
                 && $commodity
                 && empty($commodity['status'])
                 && $commodity['goods_id']
            ){
                $url = 'http://www.shihuo.cn/haitao/buy/'.$obj->getId().'.html';

                $serviceRequest = new tradeServiceClient();
                $serviceRequest->setMethod('goods.save.supplier');
                $serviceRequest->setVersion('1.0');
                $serviceRequest->setApiParam('goods_id', $commodity['goods_id']);
                $serviceRequest->setApiParam('name', TrdGoodsSupplierForm::getShopName($url));
                $serviceRequest->setApiParam('description', $commodity['desc']);
                $serviceRequest->setApiParam('price', $obj->getPrice());
                $serviceRequest->setApiParam('url', $url);
                $serviceRequest->setApiParam('status', 0);
                $serviceRequest->setApiParam('from_type', 3);
                $serviceRequest->setApiParam('from_id', $obj->getId());
                $serviceRequest->setApiParam('notice', true);
                $response = $serviceRequest->execute();

                $error = $response->getError();
                if(!$error){
                    $info = $response->getValue('info');
                    echo 'add:'.$info['id'].PHP_EOL;

                    //保存来源加入状态
                    $commodity['status'] = 1;
                    $obj->setCommodity(json_encode($commodity));
                    $obj->save();
                }else{
                    echo 'add:error';
                }
            }
        }

        

        //ack
        $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
    }

    //团购
    public static function _groupon($id, $type, $msg){
        $obj = trdGrouponTable::getInstance()->find($id);

        if($type == 'update' && $obj){
            $commodity = $obj->getCommodity();
            $commodity = json_decode($commodity, true);
            if(
                $obj->getStatus() == 6
                && $commodity
                && empty($commodity['status'])
                && $commodity['goods_id']
            ){
                $url = 'http://www.shihuo.cn/tuangou/'.$obj->getId();

                $serviceRequest = new tradeServiceClient();
                $serviceRequest->setMethod('goods.save.supplier');
                $serviceRequest->setVersion('1.0');
                $serviceRequest->setApiParam('goods_id', $commodity['goods_id']);
                $serviceRequest->setApiParam('name', TrdGoodsSupplierForm::getShopName($url));
                $serviceRequest->setApiParam('description', $commodity['desc']);
                $serviceRequest->setApiParam('price', $obj->getPrice());
                $serviceRequest->setApiParam('url', $url);
                $serviceRequest->setApiParam('status', 1);//团购默认下架
                $serviceRequest->setApiParam('from_type', 4);
                $serviceRequest->setApiParam('from_id', $obj->getId());
                $serviceRequest->setApiParam('notice', true);
                $response = $serviceRequest->execute();

                $error = $response->getError();
                if(!$error){
                    $info = $response->getValue('info');
                    echo 'add:'.$info['id'].PHP_EOL;

                    //保存来源加入状态
                    $commodity['status'] = 1;
                    $obj->setCommodity(json_encode($commodity));
                    $obj->save();
                }else{
                    echo 'add:error';
                }
            }
        }

        

        //ack
        $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
    }

    //爆料
    public static function _find($id, $type, $msg){
        $obj = trdItemallTable::getInstance()->find($id);

        if($type == 'create' && $obj){
            $commodity = $obj->getCommodity();
            $commodity = json_decode($commodity, true);
            if(
                $obj->getStatus() == 0
                && $obj->getIsHide() == 0
                && $obj->getIsSoldout() == 0
                && $commodity
                && empty($commodity['status'])
                && $commodity['goods_id']
            ){
                $serviceRequest = new tradeServiceClient();
                $serviceRequest->setMethod('goods.save.supplier');
                $serviceRequest->setVersion('1.0');
                $serviceRequest->setApiParam('goods_id', $commodity['goods_id']);
                $serviceRequest->setApiParam('name', TrdGoodsSupplierForm::getShopName($obj->getUrl()));
                $serviceRequest->setApiParam('description', $commodity['desc']);
                $serviceRequest->setApiParam('price', $obj->getPrice());
                $serviceRequest->setApiParam('url', $obj->getUrl());
                $serviceRequest->setApiParam('status', 0);
                $serviceRequest->setApiParam('from_type', 5);
                $serviceRequest->setApiParam('from_id', $obj->getId());
                $serviceRequest->setApiParam('notice', true);
                $response = $serviceRequest->execute();

                $error = $response->getError();
                if(!$error){
                    $info = $response->getValue('info');
                    echo 'add:'.$info['id'].PHP_EOL;

                    //保存来源加入状态
                    $commodity['status'] = 1;
                    $obj->setCommodity(json_encode($commodity));
                    $obj->save();
                }else{
                    echo 'add:error';
                }
            }
        }

        

        //ack
        $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
    }

}
