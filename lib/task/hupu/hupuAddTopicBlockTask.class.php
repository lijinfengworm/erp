<?php

class hupuAddTopicBlockTask extends sfBaseTask
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
      new sfCommandOption('config', null, sfCommandOption::PARAMETER_OPTIONAL, 'Which config set(in app.yml) you want to use, default to hupu, that is the configs under app_hupu key', 'hupu')
      // add your own options here
    ));

    $this->namespace        = 'hupu';
    $this->name             = 'add-topic-block';
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
      
      // $p = $cache->fetch('published');
      
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
      
      $p = unserialize($p);      
      
      $chinaTeamTopics = array('name' => '中国军团', 'posts' => array());
      
      $fixture = array('title' => '', 'link' => '', 'id' => '');
      
      for ($i = 0; $i < 9; $i++)
      {
        $chinaTeamTopics['posts'][] = $fixture;
      }
      
      //array_unshift();
      
      $p['topics'][] = $chinaTeamTopics;      
      
      $databaseStorage->set('published', serialize($p), 0);
      
      $newData = unserialize($databaseStorage->get('published'));           
      
      // unset the data in the cache
      $cache->delete('published');
      
      $this->log('Congrats, conversion succeeded');
    }
    catch (Exception $e)
    {  
      $this->log('Exception caught'); 
      $this->log("Here is the back trace: \n---------\n".$e->getTraceAsString());
      
      exit;
    }
  }
}
