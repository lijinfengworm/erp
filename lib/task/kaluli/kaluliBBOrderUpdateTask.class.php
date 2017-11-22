<?php
use PhpAmqpLib\Connection\AMQPConnection;
class kaluliBBOrderUpdateTask extends sfBaseTask
{
    CONST WEB_SITE = '//www.kaluli.com';
    CONST ERROR_NUM = 10;
    protected function configure()
    {
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'trade'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'trade'),
        ));

        $this->namespace        = 'kaluli';
        $this->name             = 'BBOrderUpdate';
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
            $this->createBBOrder();
            sleep(60);
        }
    }

    /**
     * 同步物流信息
     * 从订单表查询到物流表的同步
     */
    public function createBBOrder() {
    	//查询已发货status=4的所有订单
    	$order = KllBBMainOrderTable::getAllOrderByStatus(4);
    	$this->log('开始同步物流单号');
    	$user = 0;
    	foreach ($order as $key => $item) {
            bbKaluliService::insertBBProcess($item['order_number'], 9, '物流轨迹');
            sleep(3);
                
    	}
    	$this->log('同步物流单结束');
        exit(0);
    }
    
    private  function checkMemory() {
        $nowmem = (int)(memory_get_usage() / 1024 / 1024);
        if ($nowmem > 60) {  //如果内存超过128M 那么主动退出
            exit(0);
        }
    }


}
