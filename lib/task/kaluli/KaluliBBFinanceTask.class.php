<?php
use PhpAmqpLib\Connection\AMQPConnection;
class kaluliBBFinanceTask extends sfBaseTask
{
    CONST WEB_SITE = '//www.kaluli.com';
    CONST ERROR_NUM = 10;
    protected function configure()
    {
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'trade'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'trade'),
            new sfCommandOption('type', null, sfCommandOption::PARAMETER_REQUIRED, 'The money name', 'KLL'),
        ));

        $this->namespace        = 'kaluli';
        $this->name             = 'BBFinance';
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
     * 主订单财审
     */
    public function createBBOrder($options) {
        if(empty($options)){
        	throw new Exception("Error Processing Request", 1);
        }
        // --type="KLL" 官网审单
        if($options['type'] == 'KLL'){
        	$bind['where']['finance_audit'] = 'finance_audit = 0'; 
        	$bind['where']['pay_time'] = 'pay_time !=""';
            $bind['where']['id'] = 'id > 40970';
        	$order = KaluliMainOrderTable::getAll($bind);
        }
        $user = 0;
        if(empty($order)){
            throw new Exception("订单为空", 1);
            exit();
        }
        
        $this->log('财务审核开始');
        foreach ($order as $key => $item) {

            try {
                //财务审核，轮询主订单
                $serviceRequest = new kaluliServiceClient();
                $this->log('审核订单号为:'.$item['order_number']);
                $serviceRequest->setMethod('Finance.audit');
                $serviceRequest->setApiParam('type', $options['type']);
                $serviceRequest->setApiParam('main_order', $item);
                $serviceRequest->setVersion('1.0');
                $response = $serviceRequest->execute();
                if ($response->hasError()) {
                    // $mainOrderObj->setStatus(5)->setUpdateTime(time())->save();
                }
                sleep(2);
            } catch (Exception $e) {
                // $mainOrderObj->setStatus(5)->setUpdateTime(time())->save();
                continue;
            }
            
        }
        $this->log('财务审核完成');
        
    }
    
    private  function checkMemory() {
        $nowmem = (int)(memory_get_usage() / 1024 / 1024);
        if ($nowmem > 60) {  //如果内存超过128M 那么主动退出
            exit(0);
        }
    }


}
