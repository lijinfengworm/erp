<?php

class hupuShowDataTask extends sfBaseTask
{
  protected function configure()
  {
    // add your own arguments here
    $this->addArguments(array(
      new sfCommandArgument('page', sfCommandArgument::OPTIONAL, 'The page you want to generate, default to www', 'www'),
    ));

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'hupu'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'hupuHomepageDatabase'),
      new sfCommandOption('config', null, sfCommandOption::PARAMETER_OPTIONAL, 'Which config set(in app.yml) you want to use, default to hupu, that is the configs under app_hupu key', 'hupu'),
      new sfCommandOption('format', null, sfCommandOption::PARAMETER_OPTIONAL, 'Which config set(in app.yml) you want to use, default to hupu, that is the configs under app_hupu key', 'yml')
      // add your own options here
    ));

    $this->namespace        = 'hupu';
    $this->name             = 'show-data';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [hupu:generate|INFO] generates a static page of hupu main site.
Call it with:

  [php symfony hupu:generate|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {    
    try
    {
      sfContext::createInstance($this->configuration);        
      
      $configKey = $options['config'];
      
      $this->log(sprintf('Using configs under %s key', $configKey));
      
      $hupuConfig = sfConfig::get($configKey);
      
      $this->log('Check the connectivity of the cache and database servers.');             
      
      $dbConnection = sfContext::getInstance()->getDatabaseConnection('hupuHomepageDatabase');           
      
      $databaseStorage = new DatabaseStorage('hupu.www.');                            
      
      $p = $databaseStorage->get('published');
      $p = unserialize($p);
      
      $format = $options['format'];
      
      $formattedData = '';
      
      switch ($format)
      {
        case 'yml':
          $formattedData = sfYaml::dump($p, 100);  
        break;
        case 'array':
          $formattedData = var_export($p, true);
        break;        
        default:
          $formattedData = '';
        break;
      }
      
      $this->log($formattedData);
    }
    catch (Exception $e)
    {  
      $this->log('Exception caught'); 
      $this->log("Here is the back trace: \n---------\n".$e->getTraceAsString());
      
      exit;
    }
  }
}
