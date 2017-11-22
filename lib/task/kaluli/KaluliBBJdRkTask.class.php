<?php
use PhpAmqpLib\Connection\AMQPConnection;
class KaluliBBJdRkTask extends sfBaseTask
{
    CONST WEB_SITE = '//www.kaluli.com';
    CONST ERROR_NUM = 10;
    protected function configure()
    {
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'trade'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'trade'),
            new sfCommandOption('shop', null, sfCommandOption::PARAMETER_REQUIRED, 'The money name', 'JDRK'),
        ));

        $this->namespace        = 'kaluli';
        $this->name             = 'SyncOrder';
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
     * 从EDB拿出订单
     * 从卡路里同步物流单号到EDB
     */
    public function createBBOrder($options) {
        if(empty($options)){
        	throw new Exception("Error Processing Request", 1);
        }
        //同步EDb订单
        JdSynService::getInstance()->syncRk($options);
        JdSynService::getInstance()->syncRkExpress();
    }
   
    
    private  function checkMemory() {
        $nowmem = (int)(memory_get_usage() / 1024 / 1024);
        if ($nowmem > 60) {  //如果内存超过128M 那么主动退出
            exit(0);
        }
    }


}
