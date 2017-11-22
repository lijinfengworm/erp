<?php

class tradeTaobaoSubscriptionSocketTask extends sfBaseTask
{
  protected function configure()
  {
    // // add your own arguments here
    // $this->addArguments(array(
    //   new sfCommandArgument('my_arg', sfCommandArgument::REQUIRED, 'My argument'),
    // ));

    $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'trade'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
      // add your own options here
    ));

    $this->namespace        = 'trade';
    $this->name             = 'SubscriptionSocket';
    $this->briefDescription = '淘宝信息Socket服务';
    $this->detailedDescription = <<<EOF
The [trade:TaobaoSubscriptionSocket|INFO] task does things.
Call it with:

  [php symfony trade:TaobaoSubscriptionSocket|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
        sfContext::createInstance($this->configuration);    
        // initialize the database connection
        $databaseManager = new sfDatabaseManager($this->configuration);
        //$connection = $databaseManager->getDatabase($options['connection'])->getConnection();
//        $taobao = new TaobaoUtil();
//        $taobao->getIncrementSubscription();
        
        
        $taskConfig = sfConfig::get('app_tasks');       
        $rn = new RealtimeNotifyTask($this); 
        //线下环境用沙箱
        $this->config = ConfigTaobao::getTaobaoConfig(sfConfig::get('sf_environment')=='dev'?TRUE:FALSE);
        $rn->setConfig('server_host', $this->config['restServer']);
        $rn->setConfig( 'app_key', $this->config['taobaoke']['key']); 
        $rn->setConfig( 'secret', $this->config['taobaoke']['secret']); 
        $rn->setConfig('writable_dir', DIRECTORY_SEPARATOR.'tmp');
        $rn->run();

  }
}
