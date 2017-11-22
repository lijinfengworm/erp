<?php
class hupuClearCacheTask extends sfBaseTask
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
      new sfCommandOption('no-backup', null, sfCommandOption::PARAMETER_OPTIONAL, 'Clear the editrial data in cache', 'true'),      
    ));

    $this->namespace        = 'hupu';
    $this->name             = 'clear-cache';
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
      
      $cache = new MemcacheCache();
      $cache->setMemcache($cacheConnection);
      $cache->setNamespace('hupu.www.');            
      
      $databaseStorage = new DatabaseStorage('hupu.www.');                  
      
      $p = $cache->fetch('published');            
      
      if ($options['no-backup'] != 'false')
      {
        $this->log('Back up the data.');
        $cache->save('published.backup.'.date('YmdHis'), $p, 0);
      }
      
      $cache->delete('published');
         
      $this->log('Done.');
    }
    catch (Exception $e)
    {  
      $this->log('Exception caught'); 
      $this->log("Here is the back trace: \n---------\n".$e->getTraceAsString());
      
      exit;
    }
  }
}
