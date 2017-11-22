<?php
use PhpAmqpLib\Connection\AMQPConnection;
class tradeAmqpProductStockTask extends sfBaseTask
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
        $this->name             = 'AmqpProductStock';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [trade:AmqpHaiTaoOrder|INFO] task does things.
Call it with:

  [php symfony trade:AmqpProductStock|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array()){
        sfContext::createInstance($this->configuration);

        ini_set('memory_limit','128M');

        $amqpParams = sfConfig::get("app_mabbitmq_options_shihuo");
        $connection = new AMQPConnection($amqpParams['params']['host'], $amqpParams['params']['port'],$amqpParams['params']['user'], $amqpParams['params']['pass'], $amqpParams['params']['vhost']);

        $channel = $connection->channel();
        $channel->queue_declare('productInfo', false, true, false, false, false);
        $channel->queue_bind('productInfo', "amq.topic","product.stock");
        $channel->basic_consume('productInfo', '', false, false, false, false, 'tradeAmqpProductStockTask::callback');

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

        $product_id = $msgBody['product_id'];

        echo 'product_id: ' . $product_id . PHP_EOL . PHP_EOL;
        self::_updateProductStock($product_id, $msg);
    }

    public static function _updateProductStock($product_id, $msg)
    {
        $product = TrdProductAttrTable::getInstance()->find($product_id);
        if ($product) {
            $goods = TrdHaitaoGoodsTable::getInstance()->createQuery('m')
                ->select('*')
                ->where('m.product_id = ?', $product_id)
                ->andWhere('m.status = ?', 0)
                ->execute();
            if (count($goods) > 0) {
                $content = json_decode($product->getContent(), true);
                $json_attr = array();
                foreach ($goods as $k => $v) {
                    if ($v->getTotalNum() > 0){
                        $attr = json_decode($v->getAttr(), true);
                        $new_attr = array(
                            'name' => $attr['ASIN'],
                            'img' => $attr['ImageSets']['ImageSet'][0]['LargeImage']['URL'],
                        );
                        foreach ($attr['VariationAttributes']['VariationAttribute'] as $kk => $vv) {
                            $new_attr[$vv['Name']] = $vv['Value'];
                        }
                        $new_attr['price'] = $attr['Offers']['Offer']['OfferListing']['Price']['Amount'];
                        $new_attr['code'] = $attr['Offers']['Offer']['OfferListing']['Price']['CurrencyCode'];
                        $new_attr['gid'] = $v->getId();
                        $new_attr['stock'] = $v->getTotalNum();
                        array_push($json_attr, $new_attr);
                    }
                }
                $content['content'] = $json_attr;
                if (count($json_attr) == 0) {
                    $product->setShowFlag(0);
                } else {
                    $product->setShowFlag(1);
                }
                $product->setContent(base64_encode(gzcompress(json_encode($content))));
                $product->save();
            }
        }
        $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);//清除消息
    }
}
