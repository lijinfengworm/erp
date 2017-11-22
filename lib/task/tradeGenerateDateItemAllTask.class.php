<?php

class tradeGenerateDateItemAllTask extends sfBaseTask
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
    $this->name             = 'GenerateDateItemAll';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [trade:GenerateDateItemAll|INFO] task does things.
Call it with:

  [php symfony trade:refresh-iteminfo|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    sfContext::createInstance($this->configuration);    
    
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

    $taskConfig = sfConfig::get('app_tasks');
    
    $ttserver = sfContext::getInstance()->getDatabaseConnection('tradeMemcache');
    $key = 'tradeGenerateDateItem';
    $lastId = $ttserver->get($key);
    if(!empty($lastId))
    {
        $items = TrdItemTable::getInstance()->getItemsForRefresh(1000, $lastId,0);
    }else{
        $items = TrdItemTable::getInstance()->getItemsForRefresh(20, 0,1);
    }
    
    if(count($items) > 0)
    {
        foreach ($items as $item)
        {
        $item->syncToAll();
        $this->log('shoeid:'.$item->getId().':ok');
        $ttserver->set($key,$item->getId(),0,86400);
        }
    }else{
        $this->log('done');
        //$ttserver->set($key,0);
    }
        
  }
}
