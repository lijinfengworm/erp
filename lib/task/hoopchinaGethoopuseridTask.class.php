<?php

class hoopchinaGethoopuseridTask extends sfBaseTask
{
  protected function configure()
  {
    // // add your own arguments here
    $this->addArguments(array(
       new sfCommandArgument('username', sfCommandArgument::REQUIRED, 'username'),
     ));

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
      // add your own options here
    ));

    $this->namespace        = 'hoopchina';
    $this->name             = 'get-hoop-user-id';
    $this->briefDescription = 'Get Hoop user ID by username';
    $this->detailedDescription = <<<EOF
The [hoopchina:get-hoop-user-id|INFO] task does things.
Call it with:

  [php symfony hoopchina:get-hoop-user-id|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

    // add your code here
    $userQuery = new QueryUser(trim($arguments['username']));
    
    if (!$userQuery->exists())
    {
      $this->logSection('hoopchina', 'User does not exist!');
    }
    else
    {
      $this->logSection('hoopchina', sprintf('User id is %s', $userQuery->getUserId()));
    }
  }
}
