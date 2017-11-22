<?php

class xmlTask extends sfBaseTask
{
  protected function configure()
  {
     // add your own arguments here
     $this->addArguments(array(
       new sfCommandArgument('type', sfCommandArgument::REQUIRED, 'type'),
       new sfCommandArgument('season', sfCommandArgument::REQUIRED, 'season'),
     ));

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'gamespace'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'gamespace'),
      // add your own options here
    ));

    $this->namespace        = 'gamespace';
    $this->name             = 'xml';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [xml|INFO] task does things.
Call it with:

  [php symfony xml|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

    // add your code here    
    $type = trim($arguments['type']);
    $season = trim($arguments['season']);
    
    $routing = $this->getRouting();
    
    $url = $routing->generate('xml_put_contents', array('type' => $type, 'season' => $season), true);    
    
    return file_get_contents($url);
  }
}
