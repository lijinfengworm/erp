<?php

class hupuGenerateSnookerTask extends sfBaseTask
{
  protected function configure()
  {
    // // add your own arguments here
    // $this->addArguments(array(
    //   new sfCommandArgument('my_arg', sfCommandArgument::REQUIRED, 'My argument'),
    // ));

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'snooker'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'hupuHomepageDatabase'),
      // add your own options here
    ));

    $this->namespace        = 'hupu';
    $this->name             = 'generateSnooker';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [hupu:generateSnooker|INFO] task does things.
Call it with:

  [php symfony hupu:generateSnooker|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
     $contextInstance = sfContext::createInstance($this->configuration);
     $request = sfContext::getInstance()->getRequest();
     $request->setRelativeUrlRoot('/');

      $updator = new SnookerDataUpdator();
      $this->log('update start....');
      $this->updateSnookerData($updator);
      $this->log('update success!');
       
      $user = sfContext::getInstance()->getUser();
      //$user->setAttribute('uid', 1);
      $user->setAttribute('username', '小夏你好');
      $user->addCredentials('editor');
      $user->setAttribute('power',1);
      $user->setAuthenticated(true);
      
      $this->log('generate start.....');
      $snookerPage = $contextInstance->getController()->getPresentationFor('page', 'index');
      $snookerdir = sfConfig::get('sf_web_dir').'/generated/snooker';
      $isPutSuccess = file_put_contents($snookerdir.'/index.html', $snookerPage);
      if($isPutSuccess){
          $this->log('generate success!');
      }else{
          $this->log('generate failed!');
      }
  }
  
  private function updateSnookerData(SnookerDataUpdator $updator)
  {
      $updator->updateSnookerPhoto();
      $updator->updateSnookerVoice();
      $updator->updateSnookerHotVedio();
      $updator->updateSnookerNewVedio();
      $updator->updateSnookerBbsRLNum();
     
  }
}
