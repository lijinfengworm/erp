<?php

class sfWsCacheBuildTask extends sfBaseTask
{
  protected function configure()
  {
    // // add your own arguments here
    // $this->addArguments(array(
    //   new sfCommandArgument('my_arg', sfCommandArgument::REQUIRED, 'My argument'),
    // ));

      $this->addOptions(array(
          new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'star'),
          new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
          new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
          new sfCommandOption('group', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name'),
          // add your own options here
      ));

    $this->namespace        = 'symfony';
    $this->name             = 'sfWsCacheBuild';
    $this->briefDescription = '生成缓存文件';
    $this->detailedDescription = <<<EOF
The [trade:sfCacheBuild|INFO] task does things.
Call it with:
    生成缓存文件
  [php symfony trade:sfCacheBuild|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
      // initialize the database connection
      $databaseManager = new sfDatabaseManager($this->configuration);
      $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

      if(empty($options['group'])) {echo '--group 参数没有设定!'.PHP_EOL;exit;}
      $this->log('group:'.$options['group']);

      $configuration = ProjectConfiguration::getApplicationConfiguration($options['group'], $options['env'], true);
      sfContext::createInstance($configuration);
      $configuration->configureWsInit();




  }
}
