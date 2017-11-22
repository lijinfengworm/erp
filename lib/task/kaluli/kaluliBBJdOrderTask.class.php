<?php
use PhpAmqpLib\Connection\AMQPConnection;
class kaluliBBJdOrderTask extends sfBaseTask
{
    CONST WEB_SITE = '//www.kaluli.com';
    CONST ERROR_NUM = 10;
    protected function configure()
    {
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'trade'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'trade'),
            new sfCommandOption('shop', null, sfCommandOption::PARAMETER_REQUIRED, 'The money name', 'JDQT'),
        ));

        $this->namespace        = 'kaluli';
        $this->name             = 'BBJdOrder';
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
            $this->createBBJdOrder($options);
            sleep(60);
            break;
        }
        exit();
    }

    /**
     * 同步物流信息
     * 从订单表查询到物流表的同步
     */
    public function createBBJdOrder($options) {
        if(empty($options)){
            throw new Exception("Error Processing Request", 1);
        }
    	//$order = KaluliMainOrderTable::getAllOrderByStatus(2);
    	$order = KllBBMainOrderTable::getAllOrderByStatusAndSource(4, $options['shop']);
        if(empty($order)){
            $this->log('订单不存在');
            exit();
        }
    	foreach ($order as $key => $item) {
            if($item['syn_api'] == 2){
                JdSynService::getInstance()->synLogistics($item);
                $this->log('物流同步完成');
            }
    	}
        return true;
    	
    }
  
    
    private  function checkMemory() {
        $nowmem = (int)(memory_get_usage() / 1024 / 1024);
        if ($nowmem > 60) {  //如果内存超过128M 那么主动退出
            exit(0);
        }
    }


}
