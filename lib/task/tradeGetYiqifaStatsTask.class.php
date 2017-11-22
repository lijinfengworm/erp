<?php

class tradeGetYiqifaStatsTask extends sfBaseTask
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
    $this->name             = 'GetYiqifaStats';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [trade:GetYiqifaStats|INFO] task does things.
Call it with:

  [php symfony trade:GetYiqifaStats|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    sfContext::createInstance($this->configuration);    
    
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

    $taskConfig = sfConfig::get('app_tasks');
    
    $redis = sfContext::getInstance()->getDatabaseConnection('kaluliRedis');
    $redis->select(1);
    for($t = time();$t > time()-86400*7;$t -= 86400)
    {
        $data = date('Y-m-d', $t);
        
        $info = YiqifaStats::getDataByDate($data);
        $redis->set('trd_yiqifa_stats'.$data,  serialize($info));
        $this->log($data.'  count:'.  count($info));   
    }
        
  }
}
