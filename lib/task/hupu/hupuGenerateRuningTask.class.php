<?php

class hupuGenerateRuningTask extends sfBaseTask
{
  protected function configure()
  {
      
      $this->addArguments(array(
      new sfCommandArgument('page', sfCommandArgument::OPTIONAL, 'The page you want to generate, default to www', 'run'),
    ));
      
    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'hupu'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'hupuHomepageDatabase'),
      
    ));

    $this->namespace        = 'hupu';
    $this->name             = 'generate-runing';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [hupuGenerateRuning|INFO] task does things.
Call it with:

  [php symfony hupuGenerateRuning|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
     $contextInstance = sfContext::createInstance($this->configuration);
      
      // Set the root url, this is really important
      $request = sfContext::getInstance()->getRequest();
      $request->setRelativeUrlRoot('/');      

      $this->log('Check the connectivity of the cache and database servers.');
      
      $configKey = 'app_run';
      $this->log(sprintf('Using configs under %s key', $configKey));
      $hupuConfig = sfConfig::get($configKey);

      $cacheConnection = sfContext::getInstance()->getDatabaseConnection('hupuHomepageMemcache');
      $dbConnection = sfContext::getInstance()->getDatabaseConnection('hupuHomepageDatabase');
      
      if (!$cacheConnection || !$dbConnection)
      {
        $this->log('Cant connect to cache or database.'); 
        
        exit;
      }
      
     /* $contextInstance->getContext()->getUser()->setAttribute('username', '小夏你好');    
      $contextInstance->getContext()->getUser()->addCredentials('editor');
      $contextInstance->getContext()->getUser()->setAttribute('power','run');
      $contextInstance->getContext()->getUser()->setAuthenticated(true);*/
      $user = sfContext::getInstance()->getUser();
      //$user->setAttribute('uid', 1);
      $user->setAttribute('username', '小夏你好');
      $user->addCredentials('editor');
      $user->setAttribute('power','run');
      $user->setAuthenticated(true);
      
      $dataSource = new HupuDataSource($hupuConfig);
      $dataManager = new HupuDataManager($hupuConfig, false, 'hupuHomepageMemcache', $hupuConfig['memcache']['prefix']);
      $u = new HupuDataUpdator($dataSource, $dataManager);
      $page = $arguments['page'];
      
      $this->updateRuningData($u);
      
      $request->addRequestParameters(array('page' => $page));
      $runPage = $contextInstance->getController()->getPresentationFor('runing', 'index');
      //var_dump($runPage);die;
      $rundir = sfConfig::get('sf_web_dir').'/generated/run';
      $isPutSuccess = file_put_contents($rundir.'/index.html', $runPage);
      if($isPutSuccess){
          echo 'success';
      }else{
          echo 'failed';
      }
      
  }
  /**
   * @desc 跑步 视频最热5，最新6|新声|图集
   * @param HupuDataUpdator $updator 
   */
  
private function updateRuningData(HupuDataUpdator $updator)
  {
    $configs = sfConfig::get('app_run');
    $updator->updateRunData($configs['api']['videoTop5']['url'], HupuHomepageData::VIDEOHOT);
    $updator->updateDbData($configs['api']['runingvoice']['url'], HupuHomepageData::RUNVOICE);
    $updator->updateRuningPhotos();
    $updator->updateRuningVideos();
//    $updator->updatePostInfo();
    
  }
}
