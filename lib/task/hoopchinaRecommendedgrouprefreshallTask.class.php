<?php

class hoopchinaRecommendedgrouprefreshallTask extends sfBaseTask
{
  protected function configure()
  {
    // // add your own arguments here
    // $this->addArguments(array(
    //   new sfCommandArgument('my_arg', sfCommandArgument::REQUIRED, 'My argument'),
    // ));

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
      // add your own options here
    ));

    $this->namespace        = 'hoopchina';
    $this->name             = 'recommended-group-refresh-all';
    $this->briefDescription = 'refresh all recommended group';
    $this->detailedDescription = <<<EOF
The [hoopchina:recommended-group-refresh-all|INFO] task does things.
Call it with:

  [php symfony hoopchina:recommended-group-refresh-all|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase('sns_vote')->getConnection(); 
    RecommendedGroupTable::generateAllGroupHtmlContent($connection);
  }
}
