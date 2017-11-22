<?php

class ommatchtaskTask extends sfBaseTask
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
    $this->name             = 'ommatchtask';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [ommatchtask|INFO] task does things.
Call it with:

  [php symfony ommatchtask|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection

    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();
	//更新超过48小时的请求约战
	omApplyAppointmentTable::updateOutTimeApplyAppointnmet();
	
	//更新超过24小时未确认结果的约战任务
	omApplyAppointmentTable::updateOutTimeConfireAppointnmet();
    // add your code here
  }
}
