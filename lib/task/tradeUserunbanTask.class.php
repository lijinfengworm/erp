<?php

class tradeUserunbanTask extends sfBaseTask
{
  protected function configure()
  {
    // // add your own arguments here
    // $this->addArguments(array(
    //   new sfCommandArgument('my_arg', sfCommandArgument::REQUIRED, 'My argument'),
    // ));

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'trade'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'trade'),
      // add your own options here
    ));

    $this->namespace        = 'trade';
    $this->name             = 'user-unban';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [trade:user-unban|INFO] task does things.
Call it with:

  [php symfony trade:user-unban|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    sfContext::createInstance($this->configuration);
    
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

    // add your code here
    $affectUsers = TrdUserTable::getInstance()->unbanUsers();
    
    $this->log('Unbanned '.$affectUsers.' user(s)');    
  }
}
