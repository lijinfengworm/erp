<?php

class hupuDeleteUpdateLockTask extends sfBaseTask
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
    $this->name             = 'delete-update-lock';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [hupu:generate|INFO] generates a static page of hupu main site.
Call it with:

  [php symfony hupu:generate|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {    
    sfContext::createInstance($this->configuration);
    
    // Set the root url, this is really important
    $request = sfContext::getInstance()->getRequest();
    $request->setRelativeUrlRoot('/');

    $configKey = 'app_'.$options['config'];
    
    $this->log(sprintf('Using configs under %s config key', $configKey));
    
    $hupuConfig = sfConfig::get($configKey);
    
    $this->log('Anthenticating the robot');
    
    $user = sfContext::getInstance()->getUser();
    $user->setAttribute('uid', 1);
    $user->setAttribute('username', 'robot');
    $user->addCredentials('editor');
    $user->setAuthenticated(true);                      
    
    $dataManager = new HupuDataManager($hupuConfig, false, 'hupuHomepageMemcache', $hupuConfig['memcache']['prefix']);
    
    $this->log('Checking the update lock.');
    
    if ($dataManager->getUpdatorLock())
    {
      $this->log('Update lock exists, we are going to delete it.');
      
      $dataManager->deleteUpdatorLock(); // Delete updator lock if we are done    
      
      if ($dataManager->getUpdatorLock())
      {
        return $this->log('[NOT DELETED] Update lock has not been deleted, something went wrong');
      }
        
      $this->log('[DELETED] Update lock deleted.');      
    }
    else
    {
      $this->log('Update lock does not exist, exit.');
    }
  }
}
