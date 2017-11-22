<?php
use PhpAmqpLib\Connection\AMQPConnection;
class kaluliMqWorkersTask extends sfBaseTask
{
    protected function configure()
    {
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'trade'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'trade'),
            new sfCommandOption('type', null, sfCommandOption::PARAMETER_REQUIRED, 'The money name', 'pay'),
        ));

        $this->namespace        = 'kaluli';
        $this->name             = 'MqWorkers';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [trade:CreateCiTieForWP|INFO] task does things.
Call it with:

  [php symfony kaluli:AmqpOrderSync|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array()){
        sfContext::createInstance($this->configuration);
        ini_set('memory_limit','120M');
        $mq = new KllAmqpMQ();
        $mq->getExchangeMqWorker($options);
        
    }

    
    
}
