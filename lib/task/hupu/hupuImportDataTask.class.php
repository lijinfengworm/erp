<?php

class hupuImportDataTask extends sfBaseTask
{
  protected function configure()
  {
    // add your own arguments here
    $this->addArguments(array(
      new sfCommandArgument('page', sfCommandArgument::OPTIONAL, 'The page you want to generate, default to www', 'www'),
      new sfCommandArgument('path', sfCommandArgument::OPTIONAL, 'The page you want to generate, default to www', ''),
    ));

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'hupu'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'hupuHomepageDatabase'),
      new sfCommandOption('config', null, sfCommandOption::PARAMETER_OPTIONAL, 'Which config set(in app.yml) you want to use, default to hupu, that is the configs under app_hupu key', 'hupu')
      // add your own options here
    ));

    $this->namespace        = 'hupu';
    $this->name             = 'import-data';
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
      
      $cacheConnection = sfContext::getInstance()->getDatabaseConnection('hupuHomepageTT');
      $dbConnection = sfContext::getInstance()->getDatabaseConnection('hupuHomepageDatabase');
      
      $cache = new MemcacheCache();
      $cache->setMemcache($cacheConnection);
      $cache->setNamespace('hupu.www.');            
      
      $databaseStorage = new DatabaseStorage('hupu.www.');                        
      
      $p = $databaseStorage->get('published');
      
      // Backup first
      $backupKey = 'published.backup.'.date('YmdHis');
      
      $databaseStorage->set($backupKey, $p, 0);
      
      $backup = $databaseStorage->get($backupKey);
      $backup = unserialize($backup);
      
      if (!is_array($backup) || !count($backup))
      {
        $this->log('Backup failed, exit.');
        
        return;
      }            
      
      if (!$arguments['path'])
      {
        $path = sfConfig::get('sf_data_dir').'/hupu/data/data.yml';
      }
      else
      {
        $path = realpath($arguments['path']);
      }
      
      try
      {
        $this->log('Load data from '.$path);
        
        $data = sfYaml::load($path);             
        
        $data = serialize($data);
        
        if (strlen($data) > 200)
        {
          $this->log('Importing ...');
          
          $databaseStorage->set('published', $data, 0);
        }
        else
        {
          $this->log('Data size is too small, possibly data corrupted');
        }
      }
      catch (InvalidArgumentException $e)
      {
        $this->log('YML file format is incorrect: '); 
        $this->log("Here is the back trace: \n---------\n".$e->getTraceAsString());
      }
      
      // unset the data in the cache
      $this->log('Deleting data from the cache');
      $cache->delete('published');
      
      $this->log('Congrats, data imported successfully');
    }
    catch (Exception $e)
    {  
      $this->log('Exception caught'); 
      $this->log("Here is the back trace: \n---------\n".$e->getTraceAsString());
      
      exit;
    }
  }
}
