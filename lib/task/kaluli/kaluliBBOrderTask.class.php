<?php
use PhpAmqpLib\Connection\AMQPConnection;
class kaluliBBOrderTask extends sfBaseTask
{
    CONST WEB_SITE = '//www.kaluli.com';
    CONST ERROR_NUM = 10;
    protected function configure()
    {
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'trade'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'trade'),
            new sfCommandOption('type', null, sfCommandOption::PARAMETER_REQUIRED, 'The money name', 'PUSH'),
        ));

        $this->namespace        = 'kaluli';
        $this->name             = 'BBOrder';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [kaluli:CreateCiTieForWP|INFO] task does things.
Call it with:

  [php symfony trade:Amqp|INFO]
EOF;
    }

    /**
     * symfony默认执行函数
     * params: arguments, options
     * 把订单提交到快递100
     */
    protected function execute($arguments = array(), $options = array()){
        sfContext::createInstance($this->configuration);
        set_time_limit(0);
        ini_set('memory_limit', '128M');
        while(1) {
            //判断内存是否超出
            $this->checkMemory();
            $this->createBBOrder($options);
            exit();
        }
        
    }

    /**
     * 询问支付单
     */
    public function createBBOrder($options) {
        $mq = new KllAmqpMQ();
        $order = KllBBMainOrderTable::getAllOrderByStatus(2);
        $user = 0;
        if(empty($order)){
            $this->log('订单不存在');
            exit();
        }
        foreach ($order as $key => $item) {

            try {
                //首先需要把平台已经支付的订单推送给银联。
                //银联去支付或者生成支付流水号，
                //我们通过轮询去拿到支付流水号
                $this->log('开始推送订单'.$item['order_number']);
                $mainOrderObj = KllBBMainOrderTable::getInstance()->findOneByOrderNumber($item['order_number']);
                $serviceRequest = new kaluliServiceClient();
                $mainOrderAttrObj = KllBBMainOrderAttrTable::getInstance()->findOneByOrderNumber($item['order_number']);
                if(!empty($mainOrderAttrObj)){
                    $item['real_name'] = $mainOrderAttrObj->getRealName();
                    $item['card_code'] = $mainOrderAttrObj->getCardCode();
                }
                $serviceRequest->setMethod('bb.sendXml');
                $serviceRequest->setApiParam('type', $options['type']);
                $serviceRequest->setApiParam('main_order', $item);
                $serviceRequest->setVersion('1.0');
                $response = $serviceRequest->execute();
                if ($response->hasError()) {
                    //写入日志
                    $message = ['order_number'  =>  $item['order_number'],'body'=>  ['支付出现错误', $response->getError()]];
                    $mq->setExchangeMqTast("kaluli.erp.log", ['msg' => $message]);
                }
                
            } catch (Exception $e) {
                //写入日志
                $message = ['order_number'  =>  $item['order_number'],'body'=>  ['支付出现错误(异常抛出)', $e->getMessage()]];
                $mq->setExchangeMqTast("kaluli.erp.log", ['msg' => $message]);
                $mainOrderObj->setStatus(5)->setUpdateTime(time())->save();
                continue;
            }
            $this->log('订单处理完成');
            sleep(20);
        }
    }
    
    private  function checkMemory() {
        $nowmem = (int)(memory_get_usage() / 1024 / 1024);
        if ($nowmem > 60) {  //如果内存超过128M 那么主动退出
            exit(0);
        }
    }


}
