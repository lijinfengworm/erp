<?php

class hoopchinaGeneratestatisticsTask extends sfBaseTask
{
  protected function configure()
  {
	$this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
    ));

    $this->namespace        = 'hoopchina';
    $this->name             = 'generate-statistics';
    $this->briefDescription = 'generate statistics for an app';
    $this->detailedDescription = <<<EOF
The [hoopchina:generate-statistics|INFO] task does things.
Call it with:

  [php symfony hoopchina:generate-statistics|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase('doctrine')->getConnection(); 
    statisticTable::countLogs();
  }
}
