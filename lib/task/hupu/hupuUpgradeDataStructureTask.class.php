<?php

class hupuUpgradeDataStructureTask extends sfBaseTask
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
    $this->name             = 'upgrade-data-structure';
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
      
      $features = $p['feature'];            
      
      $keys = array_keys($p['feature']);
      
      if ($keys != array('link', 'title', 'abstract', 'links', 'image'))
      {
        $this->log('Data structure already upgraded or has been altered, exit');
        
        return;
      }
      
      $linkDataStructure = array('image', 'title', 'summary', 'link');
      $textLinkDataStructure = array('icon', 'title', 'link');
                          
      $newFeatures = array();
      
      $link1 = array_combine($linkDataStructure, array($features['image'], $features['title'], $features['abstract'], $features['link']));      
      
      $links[] = $link1;
      $textLink1 = array_shift($features['links']);
      
      $link2 = array_combine($linkDataStructure, array('', $features['title'], $features['title'], $features['link']));      
      $links[] = $link2;
      
      $textLinks = array();
      
      foreach ($features['links'] as $link)
      {
        $textLinks[] = array_combine($textLinkDataStructure, array($link['icon'], $link['title'], $link['link']));
      }            
      
      $newFeatures['links'] = $links;
      $newFeatures['text_links'] = $textLinks;
      
      $p['feature'] = $newFeatures;
      
      $databaseStorage->set('published', serialize($p), 0);
      
      $newData = unserialize($databaseStorage->get('published'));
      
      
      if (!is_array($newData) || !count($newData))
      {
        $this->log('Shit, converting failed, rollback.');
        
        $databaseStorage->set('published', $databaseStorage->get($backupKey), 0);
        
        $this->log('Ok, now we have rollbacked. We got our old data');
        
        return;
      }
      
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
