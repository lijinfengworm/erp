<?php

class hupuUpdateNBAMatchesTask extends sfBaseTask
{
  protected function configure()
  {
    // // add your own arguments here
    // $this->addArguments(array(
    //   new sfCommandArgument('my_arg', sfCommandArgument::REQUIRED, 'My argument'),
    // ));

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'hupu'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
      // add your own options here
    ));

    $this->namespace        = 'hupu';
    $this->name             = 'update-nba-matches';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [hupu:update-matches|INFO] task does things.
Call it with:

  [php symfony hupu:update-matches|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    sfContext::createInstance($this->configuration);
    $hupuConfig = sfConfig::get('app_hupu');
    
    $dataSource = new HupuDataSource($hupuConfig);
    $dataManager = new HupuDataManager($hupuConfig, false);
    
    $u = new HupuDataUpdator($dataSource, $dataManager);   
    
    $u->updateNBAMatches();

    $this->log('NBA matches updated.');        
  }
}