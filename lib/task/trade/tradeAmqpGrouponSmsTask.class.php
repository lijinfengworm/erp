<?php
use PhpAmqpLib\Connection\AMQPConnection;
class tradeAmqpGrouponSmsTask extends sfBaseTask
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
        $this->name             = 'AmqpGrouponSms';
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
        $channel->queue_declare('grouponSms', false, true, false, false, false);
        $channel->queue_bind('grouponSms', "amq.topic","shihuo.groupon.detail");
        $channel->basic_consume('grouponSms', '', false, false, false, false, 'tradeAmqpGrouponSmsTask::callback');

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

        $grouponId = $msgBody['id'];
        $type = $msgBody['type'];

        echo 'groupon_id: ' . $grouponId . ';type: ' . $type . PHP_EOL . PHP_EOL;
        switch ($type) {
            case 'create':
                self::_deliveryItemAdd($grouponId, $msg);
                break;
            case 'update':
                self::_deliveryItemUpdate($grouponId, $msg);
                break;
            case 'delete':
                self::_deliveryItemDelete($grouponId, $msg);
                break;
        }
    }

    private static function _deliveryItemAdd($grouponId, $msg)
    {
        $grouponTable = TrdGrouponTable::getInstance();
        $groupon = $grouponTable->getGrouponInfo($grouponId);
//        $grouponTable->getConnection()->close();

        if ($groupon) {
            $nowEndTime = date('Y-m-d 10:00:00');
            $endTime = $groupon['end_time'];
            if ($endTime <= $nowEndTime) {
                $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
            } else {
                $itemTitle = $pageTitle = $groupon['title'];
                $category = $groupon['category_name'];
                $originalPrice = $groupon['original_price'];
                $price = $groupon['price'];
                $attr = unserialize($groupon['attr']);
                $imageFirst = $attr['images_frist'];
                $brandName = $groupon['brand_name'];
                $attendCount = $groupon['attend_count'];
                $discountRatio = (string) $groupon['discount'] . '折';
                $likesCount = $groupon['praise'];

                $pageSource = '识货';

                $groupon = new TrdGroupon();
                $pageUrl = $groupon->getMobileUrl($grouponId);

                $paramShopGuideItem = <<<EOF
                {
                    "out_id": "$grouponId",
                    "page_url": "$pageUrl",
                    "page_title": "$pageTitle",
                    "oversea": 0,
                    "category": "$category",
                    "item_thumbnail_url" : "$imageFirst",
                    "ori_price": "$originalPrice",
                    "price": "$price",
                    "item_title": "$itemTitle",
                    "item_brand": "$brandName",
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
                    "discount_ratio": "$discountRatio",
                    "likes_count": "$likesCount"
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

    private static function _deliveryItemUpdate($grouponId, $msg)
    {
        $grouponTable = TrdGrouponTable::getInstance();
        $groupon = $grouponTable->getGrouponInfo($grouponId);
//        $grouponTable->getConnection()->close();

        if ($groupon) {
            $nowEndTime = date('Y-m-d 10:00:00');
            $endTime = $groupon['end_time'];
            if ($endTime <= $nowEndTime) {
                $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
            } else {
                $itemTitle = $pageTitle = $groupon['title'];
                $category = $groupon['category_name'];
                $originalPrice = $groupon['original_price'];
                $price = $groupon['price'];
                $attr = unserialize($groupon['attr']);
                $imageFirst = $attr['images_frist'];
                $brandName = $groupon['brand_name'];
                $attendCount = $groupon['attend_count'];
                $discountRatio = (string) $groupon['discount'] . '折';
                $likesCount = $groupon['praise'];

                $pageSource = '识货';

                $groupon = new TrdGroupon();
                $pageUrl = $groupon->getMobileUrl($grouponId);

                $paramShopGuideItem = <<<EOF
                {
                    "out_id": "$grouponId",
                    "page_url": "$pageUrl",
                    "page_title": "$pageTitle",
                    "oversea": 0,
                    "category": "$category",
                    "item_thumbnail_url" : "$imageFirst",
                    "ori_price": "$originalPrice",
                    "price": "$price",
                    "item_title": "$itemTitle",
                    "item_brand": "$brandName",
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
                    "discount_ratio": "$discountRatio",
                    "likes_count": "$likesCount"
                }
EOF;
                $c = new TaeShihuoTopClient();

                $req = new TaeDeliveryGetRequest();
                $req->setOutIds($grouponId);
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

    private static function _deliveryItemDelete($grouponId, $msg)
    {
        $grouponTable = TrdGrouponTable::getInstance();
        $groupon = $grouponTable->find($grouponId);
//        $grouponTable->getConnection()->close();

        if ($groupon->getId()) {
            $c = new TaeShihuoTopClient();
            $req = new TaeDeliveryItemDeleteRequest();
            $req->setOutId($grouponId);
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
