<?php
use PhpAmqpLib\Connection\AMQPConnection;
class tradeAmqpNewsSmsTask extends sfBaseTask
{
    public $killtag =  true;
    protected function configure()
    {
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'trade'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'trade'),
        ));

        $this->namespace        = 'trade';
        $this->name             = 'AmqpNewsSms';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [trade:AmqpNewsSms|INFO] task does things.
Call it with:

  [php symfony trade:AmqpNewsSms|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array()){
        sfContext::createInstance($this->configuration);

        ini_set('memory_limit','128M');

        $amqpParams = sfConfig::get("app_mabbitmq_options_shihuo");
        $connection = new AMQPConnection($amqpParams['params']['host'], $amqpParams['params']['port'],$amqpParams['params']['user'], $amqpParams['params']['pass'], $amqpParams['params']['vhost']);

        $channel = $connection->channel();
        $channel->queue_declare('NewsSms', false, true, false, false, false);
        $channel->queue_bind('NewsSms', "amq.topic","shihuo.news.detail");
        $channel->basic_consume('NewsSms', '', false, false, false, false, 'tradeAmqpNewsSmsTask::callback');

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

        $NewsId = $msgBody['id'];
        $type = $msgBody['type'];

        echo 'News_id: ' . $NewsId . ';type: ' . $type . PHP_EOL . PHP_EOL;
        switch ($type) {
            case 'create':
                self::_deliveryItemAdd($NewsId, $msg);
                break;
            case 'update':
                self::_deliveryItemUpdate($NewsId, $msg);
                break;
            case 'delete':
                self::_deliveryItemDelete($NewsId, $msg);
                break;
        }
    }

    private static function _deliveryItemAdd($NewsId, $msg)
    {
        $NewsTable = TrdNewsTable::getInstance()->find($NewsId);
//        TrdNewsTable::getInstance()->getConnection()->close();

        if ($NewsTable) {
            $time = date("Y-m-d H:i:s", strtotime(" +1 week",strtotime($NewsTable->getPublishDate())));
            if (date("Y-m-d H:i:s") <= $time || !$NewsTable->getBrandId()) {
                $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
            } else {
                $brandObj = TrdNewsBrandsTable::getInstance()->find($NewsTable->getBrandId());
                $item_brand = $brandObj->getBrandName();
                $dislikes_count = 0;
                $oversea = $NewsTable->getType() == 2 ? 1 : 0;
                $likes_count  = $NewsTable->getSupport();
                $comments_count = $NewsTable->getReplyCount();
                $item_title = $NewsTable->getTitle();
                $page_title = $NewsTable->getSubTitle(); 
                $item_source = $NewsTable->getOrginalType();
                $price = $NewsTable->getPrice();
                $old_price = $price;
                $item_rs_tags = '小编推荐';
                $quality = 3;
                $view_count = $NewsTable->getHits(); 
                $attendCount = round($view_count/10);
                $item_url = $NewsTable->getOrginalUrl();
                $item_thumbnail_url  = $NewsTable->getHeight() && $NewsTable->getWidth() ? str_replace('thumbnail', 'thumbnail480', $NewsTable->getImgPath()) : $NewsTable->getImgPath();
                $menuTable  = TrdMenuTable::getInstance();
                if($menuTable->getMenuNameById($NewsTable->getChildrenId()))
                {
                    $category = $menuTable->getMenuNameById($NewsTable->getChildrenId())->getName();
                }else{
                    $category = "";
                }
                $pageSource = '识货';
                $pageUrl = $NewsTable->getType() == 2 ? 'http://m.shihuo.cn/haitao/youhui/'.$NewsId.'.html' : 'http://m.shihuo.cn/youhui/'.$NewsId.'.html';


                $paramShopGuideItem = <<<EOF
            {
                "out_id": "news$NewsId",
                "page_url": "$pageUrl",
                "item_title": "$item_title",
                "page_title": "$page_title",
                "oversea": "$oversea",
                "category": "$category",
                "item_thumbnail_url" : "$item_thumbnail_url",
                "dislikes_count": "$dislikes_count",
                "comments_count": "$comments_count",
                "item_source": "$item_source",
                "item_brand": "$item_brand",
                "item_rs_tags": "$item_rs_tags",
                "price": "$price",
                "ori_price": "$old_price",
                "quality": "$quality",
                "view_count": "$view_count",
                "item_url": "$item_url",
                "app_infos":[{
                    "app_title": "识货",
                    "app_version": "1.0",
                    "goto_url": "shihuo://taobao.shihuo.detail",
                    "pkg_name": "com.taobao.shihuo",
                    "platform": "android",
                    "platform_version": "4.0"
                }],
                "page_source": "$pageSource",
                "bought_count": "$attendCount",
                "likes_count": "$likes_count"
            }
EOF;
                $c = new TaeShihuoTopClient();
                $req = new TaeDeliveryItemAddRequest();
                $req->setParamShopGuideItem($paramShopGuideItem);
                $resp = $c->execute($req);
                if (isset($resp->id) && $resp->id) {
                    $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
                } else {
                    echo 'Shenma Result: ' . json_encode($resp);
                    $msg->delivery_info['channel']->basic_nack($msg->delivery_info['delivery_tag']);
                }
            }
        } else {
            $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
        }
    }

    private static function _deliveryItemUpdate($NewsId, $msg)
    {
        $NewsTable = TrdNewsTable::getInstance()->find($NewsId);
//        TrdNewsTable::getInstance()->getConnection()->close();
        if ($NewsTable) {
            $time = date("Y-m-d H:i:s", strtotime(" +1 week",strtotime($NewsTable->getPublishDate())));
            if (date("Y-m-d H:i:s") <= $time || !$NewsTable->getBrandId()) {
                $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
            } else {
                $brandObj = TrdNewsBrandsTable::getInstance()->find($NewsTable->getBrandId());
                $item_brand = $brandObj->getBrandName();
                $dislikes_count = 0;
                $oversea = $NewsTable->getType() == 2 ? 1 : 0;
                $likes_count  = $NewsTable->getSupport();
                $comments_count = $NewsTable->getReplyCount();
                $item_title = $NewsTable->getTitle();
                $page_title  = $NewsTable->getSubTitle(); 
                $item_source = $NewsTable->getOrginalType();
                $price = $NewsTable->getPrice();
                $old_price = $price;

                $item_rs_tags = '小编推荐';
                $quality = 3;
                $view_count = $NewsTable->getHits(); 
                $attendCount = round($view_count/10);
                $item_url = $NewsTable->getOrginalUrl();
                $item_thumbnail_url  = $NewsTable->getHeight() && $NewsTable->getWidth() ? str_replace('thumbnail', 'thumbnail480', $NewsTable->getImgPath()) : $NewsTable->getImgPath();
                $menuTable  = TrdMenuTable::getInstance();
                if ($menuTable->getMenuNameById($NewsTable->getChildrenId())) {
                    $category = $menuTable->getMenuNameById($NewsTable->getChildrenId())->getName();
                } else {
                    $category = '';
                }
                $pageSource = '识货' ;
                $pageUrl = $NewsTable->getType() == 2 ? 'http://m.shihuo.cn/haitao/youhui/'.$NewsId.'.html' : 'http://m.shihuo.cn/youhui/'.$NewsId.'.html';


            $paramShopGuideItem = <<<EOF
            {
                "out_id": "news$NewsId",
                "page_url": "$pageUrl",
                "item_title": "$item_title",
                "page_title": "$page_title",
                "oversea": "$oversea",
                "category": "$category",
                "item_brand": "$item_brand",
                "item_thumbnail_url" : "$item_thumbnail_url",
                "dislikes_count": "$dislikes_count",
                "comments_count": "$comments_count",
                "item_source": "$item_source",
                "item_rs_tags": "$item_rs_tags",
                "price": "$price",
                "ori_price": "$old_price",
                "quality": "$quality",
                "view_count": "$view_count",
                "item_url": "$item_url",
                "app_infos":[{
                    "app_title": "识货",
                    "app_version": "1.0",
                    "goto_url": "shihuo://taobao.shihuo.detail",
                    "pkg_name": "com.taobao.shihuo",
                    "platform": "android",
                    "platform_version": "4.0"
                }],
                "page_source": "$pageSource",
                "bought_count": "$attendCount",
                "likes_count": "$likes_count"
            }
EOF;
                $c = new TaeShihuoTopClient();

                $req = new TaeDeliveryGetRequest();
                $req->setOutIds('news'.$NewsId);
                $resp = $c->execute($req);
                if (isset($resp->deliveries) && json_decode($resp->deliveries, true)) {
                    $reqUpdate = new TaeDeliveryItemUpdateRequest();
                    $reqUpdate->setParamShopGuideItem($paramShopGuideItem);
                    $resqUpdate = $c->execute($reqUpdate);
                    if (isset($resqUpdate->result) && $resqUpdate->result) {
                        $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
                    } else {
                        echo 'Shenma Result: ' . json_encode($resqUpdate);
                        $msg->delivery_info['channel']->basic_nack($msg->delivery_info['delivery_tag']);
                    }
                } else {
                    $reqAdd = new TaeDeliveryItemAddRequest();
                    $reqAdd->setParamShopGuideItem($paramShopGuideItem);
                    $resqAdd = $c->execute($reqAdd);
                    if (isset($resqAdd->id) && $resqAdd->id) {
                        $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
                    } else {
                        echo 'Shenma Result: ' . json_encode($resqAdd);
                        $msg->delivery_info['channel']->basic_nack($msg->delivery_info['delivery_tag']);
                    }
                }
            }
        } else {
            $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
        }
    }

    private static function _deliveryItemDelete($NewsId, $msg)
    {
        $NewsTable = TrdNewsTable::getInstance()->find($NewsId);
//        TrdNewsTable::getInstance()->getConnection()->close();

        if ($NewsTable->getId()) {
            $c = new TaeShihuoTopClient();
            $req = new TaeDeliveryItemDeleteRequest();
            $req->setOutId('news'.$NewsId);
            $resp = $c->execute($req);
            if (isset($resp->result) && $resp->result) {
                $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
            } else {
                echo 'Shenma Result: ' . json_encode($resp);
                $msg->delivery_info['channel']->basic_nack($msg->delivery_info['delivery_tag']);
            }
        } else {
            $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
        }
    }
}
