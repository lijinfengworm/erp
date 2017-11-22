<?php
/**
 * 更新比赛的贝泰id
 * 
 */
class updateMatchBetTaiIdTask extends sfBaseTask
{
  protected function configure()
  {
    // // add your own arguments here

    $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'gamespace'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'gamespace'),
            new sfCommandOption('type', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', '1'),
      // add your own options here
    ));

    $this->namespace        = 'gamespace';
    $this->name             = 'updateMatchBetTaiId';
    $this->briefDescription = '更新比赛的贝泰id';
    $this->detailedDescription = <<<EOF
The [gamespace:updateMatchBetTaiId|INFO] task does things.
Call it with:

  [php symfony gamespace:updateMatchBetTaiId|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    sfContext::createInstance($this->configuration);    
    
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

    $taskConfig = sfConfig::get('app_tasks');
    
    
    $last_id = file_get_contents('/tmp/match_id_'.$options['type'].'.txt');
    
    //更新2012年赛季的数据
    $matchs = HoopMatchTable::getInstance()->createQuery()->where('season = ?',2012)->andWhere('id > ?',$last_id)->orderBy('id asc')->fetchArray();
    foreach($matchs as $match)
    {
        
        $this->log('start: '.$match['id'].' b:'.$match['beitai_mid']);
        if(empty($match['beitai_mid']))
        {
            $this->log('error: '.$match['id'].' b:'.$match['beitai_mid']);
            continue;
        }
        if($options['type'] == 1)
        {
            $stats = $this->updateMatchStats($match['beitai_mid'],$match['id']);
            if(empty($stats))
            {
                $this->log('error: '.$match['id'].' b:'.$match['beitai_mid'].' MatchStats');
                //return;
            }else{
                $this->log('success: '.$match['id'].' b:'.$match['beitai_mid'].' MatchStats');
            }
        }
        
        if($options['type'] == 2)
        {
            $stats = $this->updateMatchPlayerStats($match['beitai_mid'],$match['id']);
            if(empty($stats))
            {
                $this->log('error: '.$match['id'].' b:'.$match['beitai_mid'].' PlayerStats');
                //return;
            }else{
                $this->log('success: '.$match['id'].' b:'.$match['beitai_mid'].' PlayerStats');
            }
        }
        if($options['type'] == 3)
        {
            HoopMatchLiveTable::getInstance()->delLiveByMatchId($match['id']);
            $stats = $this->updateMatchLive($match['beitai_mid'],$match['id']);
            if(empty($stats))
            {
                $this->log('error: '.$match['id'].' b:'.$match['beitai_mid'].' MatchLive');
                //return;
            }else{
                $this->log('success : '.$match['id'].' b:'.$match['beitai_mid'].' MatchLive');
            }
            
        }
        file_put_contents('/tmp/match_id_'.$options['type'].'.txt', $match['id']);
        
        //$this->log('success: '.$match['id'].' b:'.$match['beitai_mid']);
        //exit;
    }
    
  }
  public function updateMatchStats($beitia_mid,$hupu_mid)
  {
      $url = BeitaiInterfaceUrl::getMatchTeamStat($beitia_mid);
      $callback = 'http://g.hupu.com/nba/accept/updateTeamMatchStats?match_id='.$hupu_mid;
      $data = $this->postToApi($url, $callback);
      
      if($data['status'] == 200)
      {
        return TRUE;
      }else{
        $this->log('stats is not 200');          
        return FALSE;
      }
      
      
  }
  public function updateMatchPlayerStats($beitia_mid,$hupu_mid)
  {
      $url = BeitaiInterfaceUrl::getMatchPlayerStat($beitia_mid);
      $callback = 'http://g.hupu.com/nba/accept/updatePlayerMatchStats?match_id='.$hupu_mid;
      $data = $this->postToApi($url, $callback);
      if($data['status'] == 200)
      {
        return TRUE;
      }else{
        $this->log('stats is not 200');          
        return FALSE;
      }
      return TRUE;

  }
  public function updateMatchLive($beitia_mid,$hupu_mid)
  {
      $url = BeitaiInterfaceUrl::getMatchAllLive($beitia_mid);
      $callback = 'http://g.hupu.com/nba/accept/updateLive?match_id='.$hupu_mid;
      $data = $this->postToApi($url, $callback);
      if($data['status'] == 200)
      {
        return TRUE;
      }else{
        $this->log('stats is not 200');
        return FALSE;
      }
      return TRUE;
      
  }
  public function postToApi($url,$callback)
  {
    
    $ch = curl_init($url);
    
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $data = curl_exec($ch);
    
    $result = trim($data);
    $result = json_decode($result,1);
    if (json_last_error() != JSON_ERROR_NONE)
    {
        $this->log('url data error');
        $this->log($url);
        $this->log($callback);        
        return FALSE;
    }
    
    
    $ch = curl_init($callback);
   
    curl_setopt($ch, CURLOPT_URL, $callback);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type:application/json'));
    $g_result = curl_exec($ch);
    
    $result = trim($g_result);
    $result = json_decode($result,1);
    
    if (json_last_error() != JSON_ERROR_NONE)
    {
        $this->log('post  error');
        $this->log($url);
        $this->log($callback);
        $this->log($g_result);
        return FALSE;
    }
    
    return $result;
  }
  
}