<?php

class hupuGenerateTask extends sfBaseTask
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
    $this->name             = 'generate';
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
      
      // Set the root url, this is really important
      $request = sfContext::getInstance()->getRequest();
      
      $request->setRelativeUrlRoot('/');      
      
      $configKey = 'app_'.$options['config'];
      
      $this->log(sprintf('Using configs under %s key', $configKey));
      
      $hupuConfig = sfConfig::get($configKey);
      
      $this->log('Check the connectivity of the cache and database servers.');
      
      $cacheConnection = sfContext::getInstance()->getDatabaseConnection('hupuHomepageMemcache');
      $dbConnection = sfContext::getInstance()->getDatabaseConnection('hupuHomepageDatabase');
      
      if (!$cacheConnection || !$dbConnection)
      {
        $this->log('Cant connect to cache or database.'); 
        
        exit;
      }
      
      $this->log('Anthenticating the robot.');
      
      $user = sfContext::getInstance()->getUser();
      $user->setAttribute('uid', 1);
      $user->setAttribute('username', 'robot');
      $user->addCredentials('editor');
      $user->setAuthenticated(true);                     
      
      $dataSource = new HupuDataSource($hupuConfig);
      $dataManager = new HupuDataManager($hupuConfig, false, 'hupuHomepageMemcache', $hupuConfig['memcache']['prefix']);
      
      $this->log('Checking the update lock.');
      
      if ($dataManager->getUpdatorLock())
      {
        $this->log('Update lock exists, exit');
        
        return;
      }                
      
      $this->log('Putting an update lock.');
      
      $dataManager->setUpdatorLock();
      
      $this->log('Fetching data from APIs, this may take a while.');
      
      $u = new HupuDataUpdator($dataSource, $dataManager);
      
      $page = $arguments['page'];
      $methodName = 'update'.ucfirst(strtolower($page)).'Data';                  
      if (!method_exists($this, $methodName))
      {
        $this->log('[METHOD NOT FOUND]Can not find data update method for '.$page);
        
        $this->log('Deleting the update lock.');
    
        $dataManager->deleteUpdatorLock(); // Delete updator lock if we are done
      
        $this->log('Exiting...');
        
        return;
      }
      
      $this->$methodName($u);            
      
      $this->log('Set page to '.$page);
      
      $request->addRequestParameters(array('page' => $page));
      $this->log('Generating new page '.$page);
      
      sfContext::getInstance()->getController()->getPresentationFor("page", "generateHomepage");
      
    }
    catch (Exception $e)
    {  
      $this->log('Cant connect to cache or database, exception'); 
      $this->log("Here is the back trace: \n---------\n".$e->getTraceAsString());
      $this->log('Deleting the update lock.');
      
      $dataManager->deleteUpdatorLock(); // Delete updator lock if there is any exception
      
      $this->log('Exiting...');
      
      return;
    }
    
    $this->log('Deleting the update lock.');
    
    $dataManager->deleteUpdatorLock(); // Delete updator lock if we are done
    
    $this->log('Done.');   
  }
  
  private function updateWwwData(HupuDataUpdator $updator)
  {
    $configs = sfConfig::get('app_hupu');
    $updator->updateDbData($configs['api']['soccertopics']['url'], HupuHomepageData::WWWUSESOCCERINDEXTOPICS);
    $updator->updateNbaDbData($configs['api']['basketballtopics']['url'], HupuHomepageData::WWWUSEBASKETBALLINDEXTOPICS);
    $updator->updateVideos();
    $updator->updateF1TagVideo($configs['api']['f1tagvideo']['url'], HupuHomepageData::WWWTAGF1VIDEO);
    $updator->updatePhotos();
    $updator->updateTxtLinks();    
    $updator->updateSoccerNews();              
    $updator->updateTwitterMsgs();    
    $updator->updateBoardStats();    
    $updator->updatePostInfo();   
    $updator->updateZyPostInfo();
    $updator->updateImportantMatches();
    $updator->updateSoccerLingguangyishan();
    $updator->updateBasketballLingguangyishan();      
    $updator->updateBoardThreads();    
    $updator->updateForeignMedia();
    $updator->updateGame();
    //$updator->updateDbData($configs['api']['wwwshihuotuangou']['url'], HupuHomepageData::WWWSHIHUOTUANGOU);
    //识货团购新接口
    $updator->updateDbData($configs['api']['wwwshihuotuangou']['urlNew'], HupuHomepageData::WWWSHIHUOTUANNEW);
    $updator->updateDbData($configs['api']['soccer']['worldcup2014Url'], HupuHomepageData::WWWWORLDCUPNEWS);
  }
  
  private function updateTennisData(HupuDataUpdator $updator)
  {
    $updator->updateTennisStarVoice();
    $updator->updateATPRanking();
    //atp冠军车手
    $updator->updateATPRanking_woner();
    $updator->updateWTARanking();
    //wta冠军车手
    $updator->updateWTARanking_woner();
    $updator->updateTennisFeatures();
    $updator->updateBoardThreads();   
    //男选手视频
    $updator->updateManVedio();
    //女选手视频
    $updator->updateWomanVedio();
    //获取李娜等选手的新声新闻
    $updator->updatePlayerNews();
    //获取新闻专题
    $updator->updateTennisTopic();
    $updator->updateBoardStats();    
    $updator->updateTennisPhotos();
    $updator->updateTennisNewVideos();
    $updator->updateTennisHotVideos();
    $updator->updatePostInfo();   
  }
}
