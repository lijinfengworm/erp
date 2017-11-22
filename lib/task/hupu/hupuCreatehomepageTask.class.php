<?php

class hupuCreatehomepageTask extends sfBaseTask
{
  protected function configure()
  {
    // // add your own arguments here
    // $this->addArguments(array(
    //   new sfCommandArgument('my_arg', sfCommandArgument::REQUIRED, 'My argument'),
    // ));

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'hupu'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
      // add your own options here
    ));

    $this->namespace        = 'hupu';
    $this->name             = 'create-homepage';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [hupu:create-homepage|INFO] task does things.
Call it with:

  [php symfony hupu:create-homepage|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    sfContext::createInstance($this->configuration);
    
    $hupuConfig = sfConfig::get('app_hupu');
    
    $user = sfContext::getInstance()->getUser();
    $user->setAttribute('uid', 1);
    $user->setAttribute('username', 'robot');
    $user->addCredentials('editor');
    $user->setAuthenticated(true);    
    
    sfContext::getInstance()->getController()->getPresentationFor("page", "generateHomepage");
        
    $this->log('Done.');     
  }
}
